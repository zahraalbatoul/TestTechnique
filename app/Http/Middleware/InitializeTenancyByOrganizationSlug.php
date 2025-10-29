<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Custom middleware to initialize tenancy by organization slug from route parameter.
 */
class InitializeTenancyByOrganizationSlug
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $organizationParam = $request->route('organization');
        
        // Handle route model binding - could be Organization object or slug string
        if ($organizationParam instanceof Organization) {
            $organization = $organizationParam;
            $slug = $organization->slug;
        } else {
            $slug = $organizationParam;
            if (!$slug) {
                abort(404, 'Organization slug not found in route');
            }

            $organization = Organization::where('slug', $slug)->first();

            if (!$organization) {
                abort(404, "Organization with slug '{$slug}' not found");
            }
        }

        // Check if user belongs to this organization (only if authenticated)
        if ($request->user()) {
            // Refresh the relationship to get latest
            $user = $request->user();
            $user->load('organizations');
            
            if (!$user->organizations->contains($organization->id)) {
                abort(403, 'You do not have access to this organization');
            }
        }

        // Initialize tenancy (this will create the database if it doesn't exist)
        try {
            tenancy()->initialize($organization);
        } catch (\Exception $e) {
            \Log::error('Failed to initialize tenancy', [
                'slug' => $organization->slug ?? $slug,
                'org_id' => $organization->id,
                'error' => $e->getMessage()
            ]);
            
            // Try to create the database if it doesn't exist
            if (str_contains($e->getMessage(), 'database') || str_contains($e->getMessage(), 'Database')) {
                // Trigger tenant creation event which will create the database
                event(new \Stancl\Tenancy\Events\TenantCreated($organization));
                // Try again
                tenancy()->initialize($organization);
            } else {
                throw $e;
            }
        }

        // Ensure tenant migrations are present (first visit for existing seeded tenants)
        try {
            $schema = \Illuminate\Support\Facades\Schema::connection('tenant');
            if (!$schema->hasTable('projects') || !$schema->hasTable('tasks')) {
                \Illuminate\Support\Facades\Artisan::call('tenants:migrate', [
                    '--tenants' => $organization->id,
                    '--force' => true,
                ]);
            }
        } catch (\Throwable $t) {
            \Log::warning('Tenant schema check/migrate failed', [
                'org_id' => $organization->id,
                'error' => $t->getMessage(),
            ]);
        }

        return $next($request);
    }
}

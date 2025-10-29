<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 *     name="Organizations",
 *     description="Organization management endpoints"
 * )
 */
class OrganizationController extends Controller
{
    /**
     * Display a listing of the user's organizations.
     */
    public function index()
    {
        $organizations = auth()->user()->organizations;

        return view('organizations.index', compact('organizations'));
    }

    /**
     * Show the form for creating a new organization.
     */
    public function create()
    {
        return view('organizations.create');
    }

    /**
     * Store a newly created organization.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:tenants,slug'],
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            // Ensure uniqueness
            $counter = 1;
            $originalSlug = $validated['slug'];
            while (Organization::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $organization = Organization::create($validated);

        // Attach the creating user as a member
        auth()->user()->organizations()->attach($organization);

        return redirect()->route('organizations.show', $organization)
            ->with('success', 'Organization created successfully!');
    }

    /**
     * Display the specified organization.
     */
    public function show(Organization $organization)
    {
        // Ensure user has access
        if (!auth()->user()->organizations->contains($organization)) {
            abort(403, 'You do not have access to this organization');
        }

        return view('organizations.show', compact('organization'));
    }

    /**
     * Show form to join an organization by code/slug
     */
    public function showJoinForm()
    {
        return view('organizations.join');
    }

    /**
     * Join an existing organization
     */
    public function join(Request $request)
    {
        $validated = $request->validate([
            'slug' => ['required', 'string', 'exists:tenants,slug'],
        ]);

        $organization = Organization::where('slug', $validated['slug'])->firstOrFail();
        $user = auth()->user();

        // Check if user is already a member
        if ($user->organizations->contains($organization)) {
            return redirect()->route('organizations.show', $organization)
                ->with('info', 'You are already a member of this organization.');
        }

        // Attach user to organization
        $user->organizations()->attach($organization);

        return redirect()->route('organizations.show', $organization)
            ->with('success', 'Successfully joined the organization!');
    }
}

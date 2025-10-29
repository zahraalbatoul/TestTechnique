<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

/**
 * Organization model representing a tenant in the multi-tenant system.
 * Each organization has its own isolated database with projects and tasks.
 */
class Organization extends Tenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'id',
        'name',
        'slug',
        'data',
    ];

    /**
     * Get the attributes that should be stored in the database columns
     * (not in the JSON data column).
     *
     * @return array<string>
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'slug',
        ];
    }

    /**
     * Get all users belonging to this organization.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_user', 'organization_id', 'user_id')
            ->withTimestamps();
    }
}

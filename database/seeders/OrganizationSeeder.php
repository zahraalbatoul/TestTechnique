<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo users
        $user1 = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => bcrypt('password'),
            ]
        );

        // Create organizations
        $org1 = Organization::firstOrCreate(
            ['slug' => 'acme-corp'],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Acme Corporation',
            ]
        );

        $org2 = Organization::firstOrCreate(
            ['slug' => 'tech-startup'],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Tech Startup Inc',
            ]
        );

        // Attach users to organizations
        if (!$user1->organizations->contains($org1)) {
            $user1->organizations()->attach($org1);
        }
        if (!$user1->organizations->contains($org2)) {
            $user1->organizations()->attach($org2);
        }
        if (!$user2->organizations->contains($org1)) {
            $user2->organizations()->attach($org1);
        }
    }
}

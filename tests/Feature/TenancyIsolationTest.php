<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenancyIsolationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that data is isolated between tenants.
     */
    public function test_tenant_data_isolation(): void
    {
        // Create two organizations
        $org1 = Organization::create([
            'id' => 'org-1',
            'name' => 'Organization 1',
            'slug' => 'org-1',
        ]);

        $org2 = Organization::create([
            'id' => 'org-2',
            'name' => 'Organization 2',
            'slug' => 'org-2',
        ]);

        // Initialize tenancy for org1
        tenancy()->initialize($org1);

        // Create project in org1
        $project1 = Project::create([
            'name' => 'Org1 Project',
            'status' => 'planning',
        ]);

        // End tenancy and initialize org2
        tenancy()->end();
        tenancy()->initialize($org2);

        // Create project in org2
        $project2 = Project::create([
            'name' => 'Org2 Project',
            'status' => 'planning',
        ]);

        // Verify org2 can only see its own project
        $this->assertEquals(1, Project::count());
        $this->assertEquals('Org2 Project', Project::first()->name);

        // Switch back to org1
        tenancy()->end();
        tenancy()->initialize($org1);

        // Verify org1 can only see its own project
        $this->assertEquals(1, Project::count());
        $this->assertEquals('Org1 Project', Project::first()->name);
        $this->assertNotEquals($project2->id, Project::first()->id);
    }

    /**
     * Test that users can belong to multiple organizations.
     */
    public function test_user_multi_organization_membership(): void
    {
        $user = User::factory()->create();

        $org1 = Organization::create([
            'id' => 'org-1',
            'name' => 'Organization 1',
            'slug' => 'org-1',
        ]);

        $org2 = Organization::create([
            'id' => 'org-2',
            'name' => 'Organization 2',
            'slug' => 'org-2',
        ]);

        // Attach user to both organizations
        $user->organizations()->attach([$org1->id, $org2->id]);

        $this->assertTrue($user->organizations->contains($org1));
        $this->assertTrue($user->organizations->contains($org2));
        $this->assertCount(2, $user->organizations);
    }
}

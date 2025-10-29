<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Projects",
 *     description="Project management endpoints (tenant-scoped)"
 * )
 *
 * @OA\Schema(
 *     schema="Project",
 *     type="object",
 *     required={"id","name","status"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="My Project"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Project description"),
 *     @OA\Property(property="status", type="string", enum={"planning","in_progress","on_hold","completed","cancelled"}, example="planning"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class ProjectController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/t/{organization}/projects",
     *     summary="List all projects",
     *     tags={"Projects"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="organization",
     *         in="path",
     *         required=true,
     *         description="Organization slug",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of projects",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Project"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $projects = Project::with('tasks')->get();

        return response()->json($projects);
    }

    /**
     * @OA\Post(
     *     path="/api/t/{organization}/projects",
     *     summary="Create a new project",
     *     tags={"Projects"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="organization",
     *         in="path",
     *         required=true,
     *         description="Organization slug",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="My Project"),
     *             @OA\Property(property="description", type="string", example="Project description"),
     *             @OA\Property(property="status", type="string", enum={"planning","in_progress","on_hold","completed","cancelled"}, example="planning")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Project created",
     *         @OA\JsonContent(ref="#/components/schemas/Project")
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:planning,in_progress,on_hold,completed,cancelled'],
        ]);

        $project = Project::create($validated);

        return response()->json($project, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/t/{organization}/projects/{id}",
     *     summary="Get a specific project",
     *     tags={"Projects"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="organization",
     *         in="path",
     *         required=true,
     *         description="Organization slug",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Project details",
     *         @OA\JsonContent(ref="#/components/schemas/Project")
     *     )
     * )
     */
    public function show(Project $project): JsonResponse
    {
        $project->load('tasks');

        return response()->json($project);
    }

    /**
     * @OA\Put(
     *     path="/api/t/{organization}/projects/{id}",
     *     summary="Update a project",
     *     tags={"Projects"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="organization",
     *         in="path",
     *         required=true,
     *         description="Organization slug",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="status", type="string", enum={"planning","in_progress","on_hold","completed","cancelled"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Project updated",
     *         @OA\JsonContent(ref="#/components/schemas/Project")
     *     )
     * )
     */
    public function update(Request $request, Project $project): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:planning,in_progress,on_hold,completed,cancelled'],
        ]);

        $project->update($validated);

        return response()->json($project);
    }

    /**
     * @OA\Delete(
     *     path="/api/t/{organization}/projects/{id}",
     *     summary="Delete a project",
     *     tags={"Projects"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="organization",
     *         in="path",
     *         required=true,
     *         description="Organization slug",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Project deleted"
     *     )
     * )
     */
    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json(null, 204);
    }

    // Web route methods
    public function indexPage()
    {
        $organization = tenant();
        $projects = Project::with(['tasks' => function($query) {
            $query->latest()->limit(3);
        }])->latest()->get();
        
        return view('projects.index', compact('organization', 'projects'));
    }

    public function create()
    {
        $organization = tenant();
        return view('projects.create', compact('organization'));
    }

    public function edit(Project $project)
    {
        $organization = tenant();
        return view('projects.edit', compact('project', 'organization'));
    }

    public function showWeb(Project $project)
    {
        $organization = tenant();
        $project->load('tasks.assignedUser');
        return view('projects.show', compact('project', 'organization'));
    }

    public function storeWeb(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'status' => ['nullable', 'in:planning,in_progress,on_hold,completed,cancelled'],
            ]);

            if (!isset($validated['status']) || empty($validated['status'])) {
                $validated['status'] = 'planning';
            }

            // Create in tenant connection
            $organization = tenant();
            if (!$organization) {
                \Log::error('No tenant context when creating project');
                return back()->withErrors(['name' => 'No organization context. Please try again.'])->withInput();
            }

            $project = Project::query()->create($validated);
            \Log::info('Project created', ['project_id' => $project->id, 'org' => $organization->slug]);

            return redirect()
                ->route('tenant.projects.index', ['organization' => $organization->slug])
                ->with('success', 'Project created successfully!');
        } catch (\Throwable $e) {
            \Log::error('Project create failed', [
                'org' => tenant('id'),
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors(['name' => 'Failed to create project: '.$e->getMessage()])
                ->withInput();
        }
    }

    public function updateWeb(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:planning,in_progress,on_hold,completed,cancelled'],
        ]);

        $project->update($validated);

        return redirect()->route('tenant.projects.show', [tenant()->slug, $project])
            ->with('success', 'Project updated successfully!');
    }

    public function destroyWeb(Project $project)
    {
        $project->delete();

        return redirect()->route('tenant.dashboard', tenant()->slug)
            ->with('success', 'Project deleted successfully!');
    }
}


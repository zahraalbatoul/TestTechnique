<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Tasks",
 *     description="Task management endpoints (tenant-scoped)"
 * )
 *
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     required={"id","project_id","title","status","priority"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="project_id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Complete task"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="status", type="string", enum={"todo","in_progress","review","done"}, example="todo"),
 *     @OA\Property(property="priority", type="string", enum={"low","medium","high","urgent"}, example="medium"),
 *     @OA\Property(property="due_date", type="string", format="date", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/t/{organization}/tasks",
     *     summary="List all tasks",
     *     tags={"Tasks"},
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
     *         description="List of tasks",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Task"))
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $tasks = Task::with('project')->get();

        return response()->json($tasks);
    }

    /**
     * Get tasks for a specific project
     */
    public function byProject(Project $project): JsonResponse
    {
        $tasks = $project->tasks;

        return response()->json($tasks);
    }

    /**
     * @OA\Post(
     *     path="/api/t/{organization}/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
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
     *             required={"project_id","title"},
     *             @OA\Property(property="project_id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Complete task"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="status", type="string", enum={"todo","in_progress","review","done"}),
     *             @OA\Property(property="priority", type="string", enum={"low","medium","high","urgent"}),
     *             @OA\Property(property="due_date", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:todo,in_progress,review,done'],
            'priority' => ['nullable', 'in:low,medium,high,urgent'],
            'due_date' => ['nullable', 'date'],
        ]);

        $task = Task::create($validated);

        return response()->json($task->load('project'), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/t/{organization}/tasks/{id}",
     *     summary="Get a specific task",
     *     tags={"Tasks"},
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
     *         description="Task details",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function show(Task $task): JsonResponse
    {
        $task->load('project');

        return response()->json($task);
    }

    /**
     * @OA\Put(
     *     path="/api/t/{organization}/tasks/{id}",
     *     summary="Update a task",
     *     tags={"Tasks"},
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
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="status", type="string", enum={"todo","in_progress","review","done"}),
     *             @OA\Property(property="priority", type="string", enum={"low","medium","high","urgent"}),
     *             @OA\Property(property="due_date", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     )
     * )
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:todo,in_progress,review,done'],
            'priority' => ['sometimes', 'in:low,medium,high,urgent'],
            'due_date' => ['nullable', 'date'],
        ]);

        $task->update($validated);

        return response()->json($task->load('project'));
    }

    /**
     * @OA\Delete(
     *     path="/api/t/{organization}/tasks/{id}",
     *     summary="Delete a task",
     *     tags={"Tasks"},
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
     *         description="Task deleted"
     *     )
     * )
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json(null, 204);
    }

    // Web route methods
    public function create(Project $project)
    {
        return view('tasks.create', compact('project'));
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Quick action: mark task as done.
     */
    public function complete(Task $task)
    {
        $task->update(['status' => 'done']);
        return redirect()->back()->with('success', 'Task marked as done');
    }
}

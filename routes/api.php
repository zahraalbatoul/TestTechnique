<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes (no tenant context)
Route::prefix('api')->group(function () {
    // Public organization info
    Route::get('/organizations', [App\Http\Controllers\OrganizationController::class, 'index'])
        ->name('api.organizations.index');

    // Auth routes
    Route::post('/register', [App\Http\Controllers\Auth\ApiAuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\Auth\ApiAuthController::class, 'login']);
});

// Tenant-scoped API routes (require tenant context)
Route::prefix('api/t/{organization}')->middleware([
    \App\Http\Middleware\InitializeTenancyByOrganizationSlug::class,
    'auth:sanctum',
])->group(function () {
    // Projects
    Route::apiResource('projects', App\Http\Controllers\ProjectController::class);
    
    // Tasks
    Route::apiResource('tasks', App\Http\Controllers\TaskController::class);
    
    // Get tasks for a specific project
    Route::get('projects/{project}/tasks', [App\Http\Controllers\TaskController::class, 'byProject'])
        ->name('api.tasks.by-project');
    
    // Current organization info
    Route::get('/organization', function () {
        return response()->json(tenant());
    })->name('api.organization.current');
});


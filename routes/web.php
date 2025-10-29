<?php

use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Organization management (central routes)
    Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
    Route::get('/organizations/create', [OrganizationController::class, 'create'])->name('organizations.create');
    Route::post('/organizations', [OrganizationController::class, 'store'])->name('organizations.store');
    Route::get('/organizations/join', [OrganizationController::class, 'showJoinForm'])->name('organizations.join');
    Route::post('/organizations/join', [OrganizationController::class, 'join'])->name('organizations.join.store');
    Route::get('/organizations/{organization}', [OrganizationController::class, 'show'])->name('organizations.show');
});

// Tenant-scoped routes (organization-specific)
Route::prefix('t/{organization}')->middleware([
    'auth',
    \App\Http\Middleware\InitializeTenancyByOrganizationSlug::class,
])->group(function () {
    Route::get('/', function () {
        $organization = tenant();
        return view('tenant.dashboard', ['organization' => $organization]);
    })->name('tenant.dashboard');

    // Projects (tenant web route names namespaced to avoid API name collisions)
    Route::get('projects', [ProjectController::class, 'indexPage'])->name('tenant.projects.index');
    Route::get('projects/create', [ProjectController::class, 'create'])->name('tenant.projects.create');
    Route::post('projects', [ProjectController::class, 'storeWeb'])->name('tenant.projects.store');
    Route::get('projects/{project}', [ProjectController::class, 'showWeb'])->name('tenant.projects.show');
    Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('tenant.projects.edit');
    Route::patch('projects/{project}', [ProjectController::class, 'updateWeb'])->name('tenant.projects.update');
    Route::delete('projects/{project}', [ProjectController::class, 'destroyWeb'])->name('tenant.projects.destroy');
    
    // Tasks (tenant web namespaced)
    Route::get('projects/{project}/tasks', [TaskController::class, 'index'])->name('tenant.tasks.index');
    Route::get('projects/{project}/tasks/create', [TaskController::class, 'create'])->name('tenant.tasks.create');
    Route::post('projects/{project}/tasks', [TaskController::class, 'store'])->name('tenant.tasks.store');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tenant.tasks.show');
    Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('tenant.tasks.edit');
    Route::patch('tasks/{task}', [TaskController::class, 'update'])->name('tenant.tasks.update');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tenant.tasks.destroy');
    Route::patch('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tenant.tasks.complete');
});

require __DIR__.'/auth.php';

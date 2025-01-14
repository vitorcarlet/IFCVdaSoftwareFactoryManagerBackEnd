<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PublicProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionsController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::get('/hello', [AuthController::class, 'hello']); // Public endpoint
    Route::post('/login', [AuthController::class, 'login']); // Public login endpoint
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']); // Protected route for the authenticated user
        Route::post('/logout', [AuthController::class, 'logout']); // Protected route for logout
        Route::post('/change-password', [AuthController::class, 'changePassword']); // Protected route for logout
    });
});

// Project Routes
Route::prefix('projects')->middleware('auth:sanctum')->group(function () {
    Route::post('/add', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/', [ProjectController::class, 'index']);
    Route::get('/my-projects', [ProjectController::class, 'myProjects']);
    Route::get('/{id}', [ProjectController::class, 'show']);
    Route::put('/{id}', [ProjectController::class, 'update']);
    Route::delete('/{id}', [ProjectController::class, 'destroy']);
    Route::post('/{id}/add-hours', [ProjectController::class, 'addHours']);
    Route::get('/{id}/hours-and-deadlines', [ProjectController::class, 'hoursAndDeadlines']);
    Route::get('/pending', [ProjectController::class, 'pendingProjects']);
    Route::post('/{id}/approve', [ProjectController::class, 'approve']);
    Route::post('/{id}/reject', [ProjectController::class, 'reject']);
});


//need to use the auth middleware to protect the routes
// Dashboard Routes
Route::prefix('dashboard')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/manager', [DashboardController::class, 'manager']);
        Route::get('/student', [DashboardController::class, 'student']);
    });

// Idea Routes
Route::prefix('ideas')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [IdeaController::class, 'store']);
    Route::get('/', [IdeaController::class, 'index']);
    Route::get('/{id}', [IdeaController::class, 'show']);
    Route::put('/{id}', [IdeaController::class, 'update']);
    Route::delete('/{id}', [IdeaController::class, 'destroy']);
});

// Project Routes
// Route::prefix('projects')->middleware('auth:sanctum')->group(function () {
//     Route::post('/add', [ProjectController::class, 'store']);
//     Route::get('/', [ProjectController::class, 'index']);
//     Route::get('/my-projects', [ProjectController::class, 'myProjects']);
//     Route::get('/{id}', [ProjectController::class, 'show']);
//     Route::put('/{id}', [ProjectController::class, 'update']);
//     Route::delete('/{id}', [ProjectController::class, 'destroy']);
//     Route::post('/{id}/add-hours', [ProjectController::class, 'addHours']);
//     Route::get('/{id}/hours-and-deadlines', [ProjectController::class, 'hoursAndDeadlines']);
//     Route::get('/pending', [ProjectController::class, 'pendingProjects']);
//     Route::post('/{id}/approve', [ProjectController::class, 'approve']);
//     Route::post('/{id}/reject', [ProjectController::class, 'reject']);
// });



// Public Project Routes
Route::prefix('public/projects')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [PublicProjectController::class, 'index']);
    Route::get('/{id}', [PublicProjectController::class, 'show']);
});

// Report Routes
Route::prefix('reports')->middleware('auth:sanctum')->group(function () {
    Route::get('/project/{id}', [ReportController::class, 'projectReport']);
    Route::get('/all-projects', [ReportController::class, 'allProjects']);
    Route::post('/complex', [ReportController::class, 'complexReport']);
});

// Meeting Routes
Route::prefix('meetings')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [MeetingController::class, 'store']);
    Route::get('/all', [MeetingController::class, 'index']);
    Route::get('/my-meetings', [MeetingController::class, 'myMeetings']);
    Route::get('/{projectId}', [MeetingController::class, 'projectMeetings']);
    Route::get('/details/{id}', [MeetingController::class, 'show']);
    Route::put('/{id}', [MeetingController::class, 'update']);
    Route::delete('/{id}', [MeetingController::class, 'destroy']);
    Route::post('/{id}/upload', [MeetingController::class, 'uploadDocument']);
});

// User Routes
Route::prefix('users')->middleware('auth:sanctum')->group(function () {
    Route::post('/register', [UserController::class, 'store']);
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
    Route::put('/{id}/edit', [UserController::class, 'edit']);
    Route::get('/{id}/history', [UserController::class, 'history']);
});

Route::prefix('permissions')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [PermissionsController::class, 'index']);
    Route::post('/', [PermissionsController::class, 'store']);
    Route::get('/{permission}', [PermissionsController::class, 'show']);
    Route::put('/{permission}', [PermissionsController::class, 'update']);
    Route::delete('/{permission}', [PermissionsController::class, 'destroy']);
});

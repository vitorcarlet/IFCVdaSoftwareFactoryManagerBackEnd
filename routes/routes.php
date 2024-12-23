<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('/hello', [AuthController::class, 'hello']); // Public endpoint
    Route::post('/login', [AuthController::class, 'login']); // Public login endpoint
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']); // Protected route for the authenticated user
        Route::post('/logout', [AuthController::class, 'logout']); // Protected route for logout
    });
});


//need to use the auth middleware to protect the routes
// Dashboard Routes
Route::prefix('dashboard')->middleware('auth:api')->group(function () {
    Route::get('/manager', [DashboardController::class, 'manager']);
    Route::get('/student', [DashboardController::class, 'student']);
});

// Idea Routes
Route::prefix('ideas')->middleware('auth:api')->group(function () {
    Route::post('/', [IdeaController::class, 'store']);
    Route::get('/', [IdeaController::class, 'index']);
    Route::get('/{id}', [IdeaController::class, 'show']);
    Route::put('/{id}', [IdeaController::class, 'update']);
    Route::delete('/{id}', [IdeaController::class, 'destroy']);
});

// Project Routes
Route::prefix('projects')->middleware('auth:api')->group(function () {
    Route::post('/', [ProjectController::class, 'store']);
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

// Public Project Routes
Route::prefix('public/projects')->group(function () {
    Route::get('/', [PublicProjectController::class, 'index']);
    Route::get('/{id}', [PublicProjectController::class, 'show']);
});

// Report Routes
Route::prefix('reports')->middleware('auth:api')->group(function () {
    Route::get('/project/{id}', [ReportController::class, 'projectReport']);
    Route::get('/all-projects', [ReportController::class, 'allProjects']);
});

// Meeting Routes
Route::prefix('meetings')->middleware('auth:api')->group(function () {
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
Route::prefix('users')->middleware('auth:api')->group(function () {
    Route::post('/register', [UserController::class, 'store']);
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
    Route::put('/{id}/edit', [UserController::class, 'edit']);
    Route::get('/{id}/history', [UserController::class, 'history']);
});

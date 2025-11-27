<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    Auth\AuthenticateController,
    Auth\ForgotPasswordController,
    Auth\ResetPasswordController,

    HomeController,
    UserController,

    ProjectController,
    CollaboratorController,
};

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticateController::class, "index"])->name('login');
    Route::post('/login', [AuthenticateController::class, "authenticate"])->name('authenticate');

    Route::get('/forgot-password', [ForgotPasswordController::class, "index"])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, "sendLink"])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, "index"])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, "updatePassword"])->name('password.update');
});

Route::middleware("auth")->group(function () {
    Route::post('/logout', [AuthenticateController::class, "logout"])->name('logout');

    Route::get('/', HomeController::class)->name('dashboard');

    Route::resource('/projects', ProjectController::class)->names("projects")->only('index');

    Route::middleware('role:admin')->group(function () {
        Route::resource('/users', UserController::class);
        Route::put('/users/{user}/toggle_status', [UserController::class, 'toggle_status'])->name('users.toggle_status');
    });

    Route::middleware('role:admin,coordinator')->group(function () {
        Route::resource('/collaborators', CollaboratorController::class)->names("collaborators")->only('index');
        Route::get('/collaborators/heatmap', [CollaboratorController::class, 'heatmap'])->name("collaborators.heatmap");
    });
});

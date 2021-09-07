<?php

use App\Http\Controllers\Designs\{UploadController, DesignController};
use App\Http\Controllers\Auth\{ForgotPasswordController,
    LoginController,
    RegisterController,
    ResetPasswordController,
    VerificationController
};
use App\Http\Controllers\User\{SettingsController, UserController};
use Illuminate\Support\Facades\Route;


// Public route
Route::get('me', [UserController::class, 'getMe'])->name('user.me');

// Route group for authenticated users only
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('user.logout');

    Route::put('settings/profile', [SettingsController::class, 'updateProfile'])->name('user.profile');
    Route::put('settings/password', [SettingsController::class, 'updatePassword'])->name('user.password');

    // Upload Designs
    Route::post('designs', [UploadController::class, 'upload'])->name('designs.upload');
    Route::put('designs/{id}', [DesignController::class, 'update'])->name('designs.update');
    Route::delete('designs/{id}', [DesignController::class, 'destroy'])->name('designs.delete');
});


// Route for guests user only
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('register', [RegisterController::class, 'register'])->name('user.register');
    Route::post('login', [LoginController::class, 'login'])->name('user.login');
    Route::post('verification/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('verification/resend', [VerificationController::class, 'resend'])->name('verification.resend');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');


});

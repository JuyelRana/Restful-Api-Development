<?php

use App\Http\Controllers\Comment\CommentController;
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
Route::get('me', [UserController::class, 'getMe'])->name('users.me');

// Get Designs
Route::get('designs', [DesignController::class, 'index'])->name('designs.index');
Route::get('designs/{id}', [DesignController::class, 'findDesignById'])->name('designs.findDesign');

// Get users
Route::get('users', [UserController::class, 'index'])->name('users.index');

// Route group for authenticated users only
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('users.logout');

    Route::put('settings/profile', [SettingsController::class, 'updateProfile'])->name('users.profile');
    Route::put('settings/password', [SettingsController::class, 'updatePassword'])->name('users.password');

    // Upload Designs
    Route::post('designs', [UploadController::class, 'upload'])->name('designs.upload');
    Route::put('designs/{id}', [DesignController::class, 'update'])->name('designs.update');
    Route::delete('designs/{id}', [DesignController::class, 'destroy'])->name('designs.delete');

    // Likes and UnLikes
    Route::post('designs/{id}/like', [DesignController::class, 'like'])->name('designs.like');
    Route::get('designs/{id}/liked', [DesignController::class, 'checkIfUserHasLiked'])->name('designs.liked');


    //Comments
    Route::post('designs/{id}/comments', [CommentController::class, 'store'])->name('designs.comments.store');
    Route::put('comments/{id}', [CommentController::class, 'update'])->name('designs.comments.update');
    Route::delete('comments/{id}', [CommentController::class, 'destroy'])->name('designs.comments.delete');
});


// Route for guests user only
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('register', [RegisterController::class, 'register'])->name('users.register');
    Route::post('login', [LoginController::class, 'login'])->name('users.login');
    Route::post('verification/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('verification/resend', [VerificationController::class, 'resend'])->name('verification.resend');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');


});

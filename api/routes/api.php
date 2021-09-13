<?php

use App\Http\Controllers\Auth\{ForgotPasswordController,
    LoginController,
    RegisterController,
    ResetPasswordController,
    VerificationController
};
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\Designs\{DesignController, UploadController};
use App\Http\Controllers\Team\{InvitationController, TeamController};
use App\Http\Controllers\User\{SettingsController, UserController};
use Illuminate\Support\Facades\Route;


// Public route
Route::get('me', [UserController::class, 'getMe'])->name('users.me');

// Get Designs
Route::get('designs', [DesignController::class, 'index'])->name('designs.index');
Route::get('designs/{id}', [DesignController::class, 'findDesignById'])->name('designs.findDesign');
Route::get('designs/slug/{slug}', [DesignController::class, 'findBySlug'])->name('designs.findBySlug');

// Get users
Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::get('user/{username}', [UserController::class, 'findByUsername'])->name('users.findByUsername');
Route::get('users/{id}/designs', [DesignController::class, 'getForUser'])->name('users.designs.getForUser');

// Get team by slug
Route::get('teams/slug/{slug}', [TeamController::class, 'findBySlug'])->name('teams.findBySlug');
Route::get('teams/{id}/designs', [DesignController::class, 'getForTeam'])->name('teams.getForTeam');

// Search Designs
Route::get('search/designs', [DesignController::class, 'search'])->name('designs.search');
Route::get('search/designers', [UserController::class, 'search'])->name('users.search');

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

    // Teams
    Route::apiResource('teams', TeamController::class);
    Route::get('users/teams', [TeamController::class, 'fetchUserTeams'])->name('users.teams');
    Route::delete('teams/{id}/users/{user_id}', [TeamController::class, 'removeFromTeam'])->name('users.teams.remove');

    // Invitations
    Route::post('invitations/{teamId}', [InvitationController::class, 'invite'])->name('invitations.invite');
    Route::post('invitations/{id}/resend', [InvitationController::class, 'resend'])->name('invitations.resend');
    Route::post('invitations/{id}/respond', [InvitationController::class, 'respond'])->name('invitations.respond');
    Route::delete('invitations/{id}', [InvitationController::class, 'destroy'])->name('invitations.delete');

    // Chats
    Route::post('chats', [ChatController::class, 'sendMessage'])->name('chats.sendMessage');
    Route::get('chats', [ChatController::class, 'getUserChats'])->name('chats.getUserChats');
    Route::get('chats/{id}/messages', [ChatController::class, 'getChatMessages'])->name('chats.getChatMessages');
    Route::put('chats/{id}/markedAsRead', [ChatController::class, 'markedAsRead'])->name('chats.markedAsRead');
    Route::delete('messages/{id}', [ChatController::class, 'destroyMessage'])->name('chats.destroyMessages');

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

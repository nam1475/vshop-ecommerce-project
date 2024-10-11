<?php

use App\Http\Controllers\Customer\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Customer\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Customer\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Customer\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Customer\Auth\ProviderAuthController;
use App\Http\Controllers\Customer\Auth\NewPasswordController;
use App\Http\Controllers\Customer\Auth\PasswordController;
use App\Http\Controllers\Customer\Auth\PasswordResetLinkController;
use App\Http\Controllers\Customer\Auth\RegisteredUserController;
use App\Http\Controllers\Customer\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/* Guest: Chưa đăng nhập */
Route::middleware('guest:customer')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('auth/{provider}/redirect', [ProviderAuthController::class, 'redirect'])->name('auth.provider.redirect');
    Route::get('auth/{provider}/callback', [ProviderAuthController::class, 'callback'])->name('auth.provider.callback');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');
    
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])  
                ->name('password.store');
});

/* Customer: Đã đăng nhập */
Route::middleware('customer')->group(function () {
// Route::middleware('auth')->group(function () {
    /* Email Verification */
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    /* Password */
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');
    
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    
    /* Logout */
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

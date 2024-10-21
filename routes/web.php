<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', App\Livewire\Home::class)->name('home');

// Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/auth/login', App\Livewire\Auth\Login::class)->name('login');
    Route::get('/auth/register', App\Livewire\Auth\Register::class)->name('register');
});

Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');

// email verification view
Route::get('/email/verify', App\Livewire\Auth\VerifyEmail::class)
    ->middleware('auth', 'unverified')
    ->name('verification.notice');

// email verification confirm
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('home');
})->middleware(['auth', 'signed'])->name('verification.verify');

// forgot-password
Route::get('/forgot-password', App\Livewire\Auth\ForgotPassword::class)->middleware('guest')->name('password.request');

// reset password
Route::get('/reset-password/{token}', App\Livewire\Auth\ResetPassword::class)->middleware('guest')->name('password.reset');

Route::middleware(['auth', 'check'])->group(function () {
    Route::get('/admin/users', App\Livewire\Admin\Users::class)->name('admin.users');
    Route::get('/admin/cars', App\Livewire\Admin\Cars::class)->name('admin.cars');
    Route::get('/admin/transaction', App\Livewire\Admin\Transaction::class)->name('admin.transaction');
    Route::get('/admin/return', App\Livewire\Admin\Returned::class)->name('admin.return');
    Route::get('/admin/payment', App\Livewire\Admin\Payment::class)->name('admin.payment');
});
<?php

use Illuminate\Support\Facades\Route;
use Modules\App\Http\Controllers\Api\V1\Auth\AuthController;
use Modules\App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use Modules\App\Http\Controllers\Api\V1\Auth\ResetPasswordController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['guest'])->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('forgot-password', [ForgotPasswordController::class, 'index'])->name('forgot-password');
    Route::post('reset-password/{token}', [ResetPasswordController::class, 'index'])->name('reset-password');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

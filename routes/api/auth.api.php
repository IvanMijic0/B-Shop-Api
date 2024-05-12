<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Constants\AuthRoutes;


Route::group(
    [
        'prefix' => 'auth'
    ],
    function () {
        Route::post('/login', [AuthController::class, AuthRoutes::LOGIN]);
        Route::post('/register', [AuthController::class, AuthRoutes::REGISTER]);
        Route::post('/validateToken', [AuthController::class, AuthRoutes::VALIDATE_TOKEN]);
        Route::post('/logout', [AuthController::class, AuthRoutes::LOGOUT]);
        Route::post('/me', [AuthController::class, AuthRoutes::ME]);
        Route::middleware(['auth:api'])->group(function () {
            Route::get('/generateTOTPSecret', [AuthController::class, AuthRoutes::GENERATE_TOTP_SECRET]);
            Route::get('/generateTOTPQRCode', [AuthController::class, AuthRoutes::GENERATE_TOTP_QR_CODE]);
            Route::post('/validateTOTPSecret', [AuthController::class, AuthRoutes::VALIDATE_TOTP_SECRET]);
        });
    }
);
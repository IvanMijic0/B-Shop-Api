<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Test_CurlController;
use App\Http\Controllers\AuthController;
use App\Constants\AuthRoutes;
use App\Constants\TestRoutes;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $requeuse OTPHP\TOTP;st->user();
// });

Route::group(
    [
        'prefix' => 'auth'
    ],
    function () {
        Route::post('/login', [AuthController::class, AuthRoutes::LOGIN]);
        Route::post('/register', [AuthController::class, AuthRoutes::REGISTER]);
        Route::post('/validateToken', [AuthController::class, AuthRoutes::VALIDATE_TOKEN]);
        Route::middleware(['auth:api'])->group(function () {
            Route::get('/generateTOTPSecret', [AuthController::class, AuthRoutes::GENERATE_TOTP_SECRET]);
            Route::get('/generateTOTPQRCode', [AuthController::class, AuthRoutes::GENERATE_TOTP_QR_CODE]);
            Route::post('/validateTOTPSecret', [AuthController::class, AuthRoutes::VALIDATE_TOTP_SECRET]);
        });
    }
);

Route::post('/test-text-message', [Test_CurlController::class, TestRoutes::SEND_TEXT_MESSAGE]);
Route::get('/test-curl-get', [Test_CurlController::class, TestRoutes::TEST_GET]);
Route::post('/test-curl-post', [Test_CurlController::class, TestRoutes::TEST_POST]);
Route::put('/test-curl-put', [Test_CurlController::class, TestRoutes::TEST_PUT]);
Route::put('/test-curl-put2', [Test_CurlController::class, TestRoutes::TEST_PUT2]);
Route::patch('/test-curl-patch', [Test_CurlController::class, TestRoutes::TEST_PATCH]);
Route::delete('/test-curl-delete', [Test_CurlController::class, TestRoutes::TEST_DELETE]);

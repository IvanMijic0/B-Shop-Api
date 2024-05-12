<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Test_CurlController;
use App\Constants\TestRoutes;

Route::post('/test-text-message', [Test_CurlController::class, TestRoutes::SEND_TEXT_MESSAGE]);
Route::get('/test-curl-get', [Test_CurlController::class, TestRoutes::TEST_GET]);
Route::post('/test-curl-post', [Test_CurlController::class, TestRoutes::TEST_POST]);
Route::put('/test-curl-put', [Test_CurlController::class, TestRoutes::TEST_PUT]);
Route::put('/test-curl-put2', [Test_CurlController::class, TestRoutes::TEST_PUT2]);
Route::patch('/test-curl-patch', [Test_CurlController::class, TestRoutes::TEST_PATCH]);
Route::delete('/test-curl-delete', [Test_CurlController::class, TestRoutes::TEST_DELETE]);
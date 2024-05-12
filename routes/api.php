<?php

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

require_once 'api/auth.api.php';
require_once 'api/test.api.php';
require_once 'api/mailer.api.php';
require_once 'api/user.api.php';
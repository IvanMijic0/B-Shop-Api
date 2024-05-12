<?php

use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::apiResource('review', ReviewController::class);
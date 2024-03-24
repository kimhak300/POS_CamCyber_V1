<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Testing\TestingController;
use App\Http\Controllers\Testing\EmailController;

Route::get('/calculate', [TestingController::class, 'calculate']);

// ========================================================================>> Send Email
Route::post('/send-email', [EmailController::class, 'sendEmailRaw']);

// ========================================================================>> Telegram Bot


// ========================================================================>> JSReport


// ========================================================================>> File Service



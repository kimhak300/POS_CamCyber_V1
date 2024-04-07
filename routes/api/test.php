<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Testing\TestingController;
use App\Http\Controllers\Testing\EmailController;
use App\Http\Controllers\Testing\ExternalService\TelegramController;
use App\Http\Controllers\Testing\ExternalService\JSReportController;

// ========================================================================>> PHP Function
Route::get('/calculate', [TestingController::class, 'calculate']);

// ========================================================================>> Telegram Bot
Route::get('/send-telegram-bot/sendMessage',    [TelegramController::class, 'sendMessage']);
Route::post('/send-telegram-bot/sendPhoto',     [TelegramController::class, 'sendPhoto']);
Route::get('/send-telegram-bot/sendLocation',  [TelegramController::class, 'sendLocation']);

// ========================================================================>> Send Email
Route::post('/send-email', [EmailController::class, 'sendEmailRaw']);

// ========================================================================>> JSReport

Route::post('/report/generate-invoice', [JSReportController::class, 'generate-invoice']);

// ========================================================================>> File Service



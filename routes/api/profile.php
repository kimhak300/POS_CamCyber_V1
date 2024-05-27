<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Profile\ProfileController;

Route::get('/',                 [ProfileController::class, 'view']);
Route::post('/',                [ProfileController::class, 'update']);
Route::post('/change-password', [ProfileController::class, 'changePassword']);


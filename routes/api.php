<?php


use Illuminate\Support\Facades\Route;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
    */

    // ==============================================>>  Login
    Route::group(['prefix' => 'auth'], function () {
        require(__DIR__ . '/api/auth.php');
    });

    // ==============================================>>  Protected routes from Unauthorized Access
    Route::group(['middleware' => ['jwt.verify']], function () {

        // Add ur code here

    });


     // ==============================================>>  Test with Third Party via API
    Route::group(['prefix' => 'testing'], function () {
        require(__DIR__ . '/api/test.php');
    });

<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {
    Route::group(['middleware' => ['languageMobile']], function () {

        Route::post('/send-otp', [AuthController::class, 'sendOtp']);
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
        Route::post('/reset', [AuthController::class, 'resetPassword']);
        Route::post('/check-code', [AuthController::class, 'checkCode']);
        Route::post('/confirm-reset', [AuthController::class, 'confirmReset']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        Route::post('verify', [AuthController::class, 'verify']);

        Route::get('privacy', [SettingController::class, 'privacy']);
        Route::get('about', [SettingController::class, 'about']);
        Route::get('terms', [SettingController::class, 'terms']);
        Route::get('contact', [SettingController::class, 'contact']);
        Route::get('header', [SettingController::class,'header']);
        Route::get('ads', [PageController::class,'ads']);

        Route::get('blogs', [PageController::class,'blogs']);
        Route::get('cities', [PageController::class,'cities']);
        Route::get('currencies', [PageController::class,'currencies']);
        Route::get('sliders', [PageController::class,'sliders']);
        Route::get('countries', [PageController::class,'countries']);
        Route::get('categories', [PageController::class,'categories']);
        Route::get('sub_categories', [PageController::class,'sub_categories']);
        Route::get('articles', [PageController::class,'articles']);
        Route::get('footer', [SettingController::class,'footer']);
        Route::get('jobs', [PageController::class,'jobs']);

        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::get('rates/{id}/{type}', [PageController::class, 'getRates']);
            Route::post('save_rate', [PageController::class, 'saveRates']);
            Route::get('get_prefered_setting', [PageController::class, 'getPreferedSetting']);
            Route::post('change_prefered_setting', [PageController::class, 'changePreferedSetting']);
            Route::get('change-language/{language}', [PageController::class, 'changeLang']);
            Route::post('/update-profile', [AuthController::class, 'updateProfile']);
            Route::post('/send-code', [AuthController::class, 'sendCode']);
            Route::post('/change-password', [AuthController::class, 'changePassword']);
            Route::post('/update-email', [AuthController::class, 'updateEmail']);
            Route::post('/update-phone', [AuthController::class, 'updatePhone']);
            Route::get('/profile', [AuthController::class, 'profile']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });
});

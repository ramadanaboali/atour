<?php

use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\Vendor\EffectivenesController;
use App\Http\Controllers\Api\V1\Vendor\OrderController;
use App\Http\Controllers\Api\V1\Vendor\AuthController;
use App\Http\Controllers\Api\V1\Vendor\GiftController;
use App\Http\Controllers\Api\V1\Vendor\TripController;
use App\Http\Controllers\Api\V1\Vendor\ServiceController;
use App\Http\Controllers\Api\V1\Vendor\VendorController;
use App\Models\PlayerId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {
    Route::group(['middleware' => ['languageMobile']], function () {

        Route::post('/send-otp', [AuthController::class, 'sendOtp']);
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

        Route::post('/setup1', [AuthController::class, 'setup1']);
        Route::post('/setup2', [AuthController::class, 'setup2']);
        Route::post('/setup3', [AuthController::class, 'setup3']);
        Route::post('/setup4', [AuthController::class, 'setup4']);
        Route::post('/setup5', [AuthController::class, 'setup5']);
        Route::post('/setup6', [AuthController::class, 'setup6']);
        Route::post('/setup7', [AuthController::class, 'setup7']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::post('reset', [AuthController::class, 'resetPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        Route::post('confirm-reset', [AuthController::class, 'confirmReset']);
        Route::post('check-code', [AuthController::class, 'checkCode']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('verify', [App\Http\Controllers\Api\V1\AuthController::class, 'verify']);
        Route::get('privacy', [App\Http\Controllers\Api\V1\SettingController::class, 'privacy']);
        Route::get('about', [App\Http\Controllers\Api\V1\SettingController::class, 'about']);
        Route::get('terms', [App\Http\Controllers\Api\V1\SettingController::class, 'terms']);
        Route::get('contact', [App\Http\Controllers\Api\V1\SettingController::class, 'contact']);
        Route::get('header', [App\Http\Controllers\Api\V1\SettingController::class,'header']);
        Route::get('ads', [App\Http\Controllers\Api\V1\PageController::class,'ads']);

        Route::get('blogs', [App\Http\Controllers\Api\V1\PageController::class,'blogs']);
        Route::get('cities', [App\Http\Controllers\Api\V1\PageController::class,'cities']);
        Route::get('currencies', [App\Http\Controllers\Api\V1\PageController::class,'currencies']);
        Route::get('sliders', [App\Http\Controllers\Api\V1\PageController::class,'sliders']);
        Route::get('countries', [App\Http\Controllers\Api\V1\PageController::class,'countries']);
        Route::get('categories', [App\Http\Controllers\Api\V1\PageController::class,'categories']);
        Route::get('sub_categories', [App\Http\Controllers\Api\V1\PageController::class,'sub_categories']);
        Route::get('articles', [App\Http\Controllers\Api\V1\PageController::class,'articles']);
        Route::get('footer', [App\Http\Controllers\Api\V1\SettingController::class,'footer']);
        Route::get('jobs', [App\Http\Controllers\Api\V1\PageController::class,'jobs']);
        Route::get('onboardings', [App\Http\Controllers\Api\V1\PageController::class,'jobs']);

        Route::get('faqs', [HomeController::class,'faqs']);

        Route::get('onboardings', [App\Http\Controllers\Api\V1\PageController::class,'onboardings']);
        Route::get('features', [App\Http\Controllers\Api\V1\PageController::class,'features']);
        Route::get('requirements', [App\Http\Controllers\Api\V1\PageController::class,'requirements']);
        Route::get('all-locations', [App\Http\Controllers\Api\V1\PageController::class,'allLocations']);

        Route::group(['middleware' => 'auth:sanctum'], function () {

            Route::post('/store-player-id', function (Request $request) {
                $request->validate([
                    'player_id' => 'required|string',
                ]);
                Log::info('vendor-player_id'.auth()->user()->id.'--'.$request->player_id);

                PlayerId::updateOrCreate([
                    'player_id' => $request->player_id,
                ], [
                    'user_id' => auth()->user()->id,
                    'player_id' => $request->player_id,
                ]);

                return response()->apiSuccess('Player ID saved successfully');
            });
               // Public Rating API Routes
        Route::prefix('ratings')->group(function () {
            Route::get('/supplier/{supplier}', [App\Http\Controllers\Api\V1\RatingController::class, 'supplierRatings']);
            Route::get('/supplier/{supplier}/stats', [App\Http\Controllers\Api\V1\RatingController::class, 'supplierStats']);
            Route::get('/recent', [App\Http\Controllers\Api\V1\RatingController::class, 'recentRatings']);
            Route::get('/top-suppliers', [App\Http\Controllers\Api\V1\RatingController::class, 'topRatedSuppliers']);
        });


            /////user/////
            Route::get('/profile', [AuthController::class, 'profile']);
            Route::post('update-profile', [AuthController::class, 'updateProfile']);
            Route::post('update-image', [App\Http\Controllers\Api\V1\AuthController::class, 'updateimage']);
            Route::post('logout', [AuthController::class, 'logout']);
            Route::delete('delete-account', [App\Http\Controllers\Api\V1\AuthController::class, 'deleteAccount']);
            Route::post('fcm-token', [App\Http\Controllers\Api\V1\AuthController::class, 'updateToken']);

            Route::post('contact-us', [PageController::class, 'contactUs']);
            Route::get('trips', [TripController::class, 'index']);
            Route::post('trips', [TripController::class, 'store']);
            Route::post('add-offers', [TripController::class, 'storeOffer']);
            Route::get('trips/{id}', [TripController::class, 'show']);
            Route::put('trips/{trip}', [TripController::class, 'update']);
            Route::delete('trips/{id}', [TripController::class, 'delete']);

            Route::get('effectivenes', [EffectivenesController::class, 'index']);
            Route::post('effectivenes', [EffectivenesController::class, 'store']);
            Route::get('effectivenes/{id}', [EffectivenesController::class, 'show']);
            Route::put('effectivenes/{effectivenes}', [EffectivenesController::class, 'update']);
            Route::delete('effectivenes/{id}', [EffectivenesController::class, 'delete']);

            Route::get('gifts', [GiftController::class, 'index']);
            Route::post('gifts', [GiftController::class, 'store']);
            Route::get('gifts/{id}', [GiftController::class, 'show']);
            Route::put('gifts/{gift}', [GiftController::class, 'update']);
            Route::delete('gifts/{id}', [GiftController::class, 'delete']);

            //addnewrouteheredontdeletemeplease

            Route::get('services', [ServiceController::class, 'index']);
            Route::post('services', [ServiceController::class, 'store']);
            Route::get('services/{services}', [ServiceController::class, 'show']);
            Route::put('services/{services}', [ServiceController::class, 'update']);


            Route::get('withdrwal', [OrderController::class, 'withdrwal']);
            Route::get('home-page', [OrderController::class, 'homePage']);
            Route::get('wallet-page', [OrderController::class, 'walletPage']);
            Route::get('invoices', [OrderController::class, 'invoices']);
            Route::get('pendding-orders', [OrderController::class, 'penddingRequests']);
            // Route::get('invoices', [OrderController::class, 'invoices']);
            Route::get('accept/{type}/{id}', [OrderController::class, 'acceptOrder']);
            Route::get('confirm/{type}/{id}', [OrderController::class, 'confirmOrder']);
            Route::get('compleate-order/{type}/{id}', [OrderController::class, 'deliverOrder']);
            Route::get('cancel/{type}/{id}', [OrderController::class, 'cancelOrder']);
            Route::get('orders/{type}/{id}', [OrderController::class, 'showOrder']);
            Route::get('show-all/{type}', [OrderController::class, 'getAll']);
            Route::get('vendor-status', [VendorController::class, 'status']);
            Route::get('notifications', [VendorController::class, 'notifications']);
            Route::get('notifications-read/{id}', [VendorController::class, 'readNotification']);
            Route::get('notifications-read-all', [VendorController::class, 'readAllNotification']);
            Route::post('change-password', [AuthController::class, 'changePassword']);
            Route::post('/send-code', [AuthController::class, 'sendCode']);
            Route::post('/update-email', [AuthController::class, 'updateEmail']);
            Route::post('/update-phone', [AuthController::class, 'updatePhone']);

        });
    });
});

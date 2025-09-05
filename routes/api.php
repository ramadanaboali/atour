<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Customer\OrderController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\Vendor\VendorController;
use App\Models\Notification;
use App\Models\PlayerId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\OneSignalService;
use Illuminate\Support\Facades\Log;

Route::post('/send-notification', function (Request $request) {
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'title' => 'required|string',
        'message' => 'required|string',
    ]);

    $response = OneSignalService::sendToUser($request->user_id, $request->title, $request->message);

    return response()->json($response);
});

Route::get('/send-broadcast', function (Request $request) {


    $response = OneSignalService::sendToAll("test title", "test message");


    return response()->json($response);
});
Route::get('/send-user', function (Request $request) {


    $response = OneSignalService::sendToUser($request->id, "تجربة الاشعار", "test message");


    return response()->json($response);
});



Route::get('/notifications', function (Request $request) {
    return response()->json(Notification::latest()->get());
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'v1'], function () {
    Route::group(['middleware' => ['languageMobile']], function () {
        Route::post('/send-otp', [AuthController::class, 'sendOtp']);
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
        Route::post('/reset', [AuthController::class, 'resetPassword']);
        Route::post('check-code', [AuthController::class, 'checkCode']);
        Route::post('/confirm-reset', [AuthController::class, 'confirmReset']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
        Route::post('verify', [AuthController::class, 'verify']);
        Route::get('faqs', [HomeController::class,'faqs']);
        Route::get('cities', [HomeController::class,'cities']);
        Route::get('privacy', [SettingController::class, 'privacy']);
        Route::get('about', [SettingController::class, 'about']);
        Route::get('terms', [SettingController::class, 'terms']);
        Route::get('contact', [SettingController::class, 'contact']);
        Route::get('header', [SettingController::class,'header']);
        Route::get('ads', [PageController::class,'ads']);
        Route::get('blogs', [PageController::class,'blogs']);
        Route::get('blogs/{id}', [PageController::class,'blog']);
        Route::get('cities', [PageController::class,'cities']);
        Route::get('cities/{id}', [PageController::class,'getCity']);
        Route::get('currencies', [PageController::class,'currencies']);
        Route::get('sliders', [PageController::class,'sliders']);
        Route::get('countries', [PageController::class,'countries']);
        Route::get('countries/{id}', [PageController::class,'getCountry']);

        Route::get('categories', [PageController::class,'categories']);
        Route::get('sub_categories', [PageController::class,'sub_categories']);
        Route::get('articles', [PageController::class,'articles']);
        Route::get('footer', [SettingController::class,'footer']);
        Route::get('jobs', [PageController::class,'jobs']);
        Route::get('why_bookings', [PageController::class,'whyBookings']);
        Route::get('search_by_city/{id}', [HomeController::class,'searchByCity']);
        Route::get('city-trips/{id}', [PageController::class,'cityTrips']);
        Route::get('offers', [PageController::class,'getOffers']);
        Route::get('top_cities', [PageController::class,'topCities']);
        Route::get('home', [HomeController::class,'home']);
        Route::get('onboardings', [PageController::class,'onboardings']);
        Route::get('features', [PageController::class,'features']);
        Route::get('requirements', [PageController::class,'requirements']);
        Route::get('all-locations', [PageController::class,'allLocations']);
        Route::get('delivery-cost/{city_id}/{vendor_id}', [PageController::class,'deliveryCost']);

        // Ticket Support Routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('tickets', [App\Http\Controllers\Api\V1\TicketController::class, 'index']);
            Route::post('tickets', [App\Http\Controllers\Api\V1\TicketController::class, 'store']);
            Route::get('tickets/{id}', [App\Http\Controllers\Api\V1\TicketController::class, 'show']);
            Route::post('tickets/{id}/reply', [App\Http\Controllers\Api\V1\TicketController::class, 'reply']);
            Route::patch('tickets/{id}/close', [App\Http\Controllers\Api\V1\TicketController::class, 'close']);
            Route::patch('tickets/{id}/reopen', [App\Http\Controllers\Api\V1\TicketController::class, 'reopen']);
        });

        Route::get('notifications-read/{id}', [VendorController::class, 'readNotification']);

        Route::get('trip-calendar/{trip}', [HomeController::class,'getYearlyAvailability']);

        Route::get('trips', [HomeController::class,'trips']);
        Route::get('gifts', [HomeController::class,'gifts']);
        Route::get('effectivenes', [HomeController::class,'effectivenes']);
        Route::get('similar_trips/{id}', [HomeController::class,'similler_trips']);
        Route::get('trips/{id}', [HomeController::class,'trip']);
        Route::get('gifts/{id}', [HomeController::class,'gift']);
        Route::get('effectivenes/{id}', [HomeController::class,'effectivene']);
        Route::get('trip-programs/{id}', [HomeController::class,'tripPrograms']);

        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::post('/store-player-id', function (Request $request) {
                $request->validate([
                    'player_id' => 'required|string',
                ]);
                Log::info($request->player_id);
                PlayerId::updateOrCreate([
                    'player_id' => $request->player_id,
                ], [
                    'user_id' => auth()->user()->id,
                    'player_id' => $request->player_id,
                ]);

                return response()->apiSuccess('Player ID saved successfully');
            });
            Route::post('contact-us', [PageController::class, 'contactUs']);

            Route::post('fcm-token', [App\Http\Controllers\Api\V1\AuthController::class, 'updateToken']);

            Route::get('bookings', [OrderController::class, 'bookings']);
            Route::get('save-favourite/{type}/{id}', [HomeController::class, 'saveFavourite']);
            Route::get('remove-favourite/{type}/{id}', [HomeController::class, 'deleteFavourite']);
            Route::get('favourite', [HomeController::class, 'favourite']);
            Route::get('trip-pay/{id}', [OrderController::class, 'tripPay']);
            Route::get('effectivene-pay/{id}', [OrderController::class, 'effectivenePay']);
            Route::get('gift-pay/{id}', [OrderController::class, 'giftPay']);
            Route::get('cancel/{type}/{id}', [OrderController::class, 'cancelOrder']);
            Route::get('rates', [PageController::class, 'getAllRates']);
            Route::get('rates/{id}/{type}', [PageController::class, 'getRates']);
            Route::get('get_prefered_setting', [PageController::class, 'getPreferedSetting']);
            Route::get('change-language/{language}', [PageController::class, 'changeLang']);
            Route::post('booking-gift', [OrderController::class, 'bookingGifts']);
            Route::post('change_prefered_setting', [PageController::class, 'changePreferedSetting']);
            Route::post('booking-trip', [OrderController::class, 'bookingTrip']);
            Route::post('booking-effectivene', [OrderController::class, 'bookingEffectivenes']);
            Route::post('save_rate', [PageController::class, 'saveRates']);
            Route::post('/update-profile', [AuthController::class, 'updateProfile']);
            Route::post('/send-code', [AuthController::class, 'sendCode']);
            Route::post('/change-password', [AuthController::class, 'changePassword']);
            Route::post('/update-email', [AuthController::class, 'updateEmail']);
            Route::post('/update-phone', [AuthController::class, 'updatePhone']);
            Route::get('/profile', [AuthController::class, 'profile']);
            Route::post('/logout', [AuthController::class, 'logout']);

            Route::get('trip-payment/{id}', [HomeController::class, 'tripPayment']);
            Route::get('gift-payment/{id}', [HomeController::class, 'giftPayment']);
            Route::get('effectivenes-payment/{id}', [HomeController::class, 'effectivenePayment']);
            Route::get('notifications', [VendorController::class, 'notifications']);
            Route::get('notifications-read-all', [VendorController::class, 'readAllNotification']);

            // Rating API Routes
            Route::prefix('ratings')->group(function () {
                Route::post('/', [App\Http\Controllers\Api\V1\RatingController::class, 'store']);
                Route::get('/my-ratings', [App\Http\Controllers\Api\V1\RatingController::class, 'customerRatings']);
                Route::get('/can-rate', [App\Http\Controllers\Api\V1\RatingController::class, 'canRate']);
            });

        });
    });
});

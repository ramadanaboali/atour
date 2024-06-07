    <?php

use App\Http\Controllers\Api\V1\Vendor\OrderController;
use App\Http\Controllers\Api\V1\Vendor\AuthController;
use App\Http\Controllers\Api\V1\Vendor\TripProgramController;
use App\Http\Controllers\Api\V1\Vendor\TripController;
use App\Http\Controllers\Api\V1\Vendor\ServiceController;
use Illuminate\Http\Request;
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
        Route::post('/login', [AuthController::class, 'login']);

        Route::post('reset-password', [App\Http\Controllers\Api\V1\AuthController::class, 'resetPassword']);
        Route::post('confirm-reset', [App\Http\Controllers\Api\V1\AuthController::class, 'confirmReset']);
        Route::post('check-code', [App\Http\Controllers\Api\V1\AuthController::class, 'checkCode']);
        Route::post('register', [App\Http\Controllers\Api\V1\AuthController::class, 'register']);
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

        Route::group(['middleware' => 'auth:sanctum'], function () {
            /////user/////
            Route::post('update-profile', [App\Http\Controllers\Api\V1\AuthController::class, 'updateProfile']);
            Route::post('update-image', [App\Http\Controllers\Api\V1\AuthController::class, 'updateimage']);
            Route::post('logout', [App\Http\Controllers\Api\V1\AuthController::class, 'logout']);
            Route::delete('delete-account', [App\Http\Controllers\Api\V1\AuthController::class, 'deleteAccount']);
            Route::post('fcm-token', [App\Http\Controllers\Api\V1\AuthController::class, 'updateToken']);

            Route::get('trips', [TripController::class, 'index']);
            Route::post('trips', [TripController::class, 'store']);
            Route::get('trips/{id}', [TripController::class, 'show']);
            Route::put('trips/{trip}', [TripController::class, 'update']);
            Route::delete('trips/{trip}', [TripController::class, 'delete']);




        //addnewrouteheredontdeletemeplease

            Route::get('services', [ServiceController::class, 'index']);
            Route::post('services', [ServiceController::class, 'store']);
            Route::get('services/{services}', [ServiceController::class, 'show']);
            Route::put('services/{services}', [ServiceController::class, 'update']);



            Route::post('orders/status', [OrderController::class, 'updateStatus']);
            Route::get('orders', [OrderController::class, 'index']);
            Route::get('orders/{id}', [OrderController::class, 'index']);
            Route::get('trip_programs', [TripProgramController::class, 'index']);
            Route::post('trip_programs', [TripProgramController::class, 'store']);
            Route::get('trip_programs/{id}', [TripProgramController::class, 'show']);
            Route::put('trip_programs/{trip_program}', [TripProgramController::class, 'update']);
            Route::delete('trip_programs/{trip_program}', [TripProgramController::class, 'delete']);




        });
    });
});

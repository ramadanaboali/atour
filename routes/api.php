    <?php

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
        Route::post('login', [App\Http\Controllers\Api\V1\AuthController::class, 'login']);
        Route::post('reset-password', [App\Http\Controllers\Api\V1\AuthController::class, 'resetPassword']);
        Route::post('confirm-reset', [App\Http\Controllers\Api\V1\AuthController::class, 'confirmReset']);
        Route::post('check-code', [App\Http\Controllers\Api\V1\AuthController::class, 'checkCode']);
        Route::post('register', [App\Http\Controllers\Api\V1\AuthController::class, 'register']);
        Route::post('verify', [App\Http\Controllers\Api\V1\AuthController::class, 'verify']);
        Route::get('privacy', [App\Http\Controllers\Api\V1\SettingController::class, 'privacy']);
        Route::get('terms', [App\Http\Controllers\Api\V1\SettingController::class, 'terms']);
        Route::get('contact', [App\Http\Controllers\Api\V1\SettingController::class, 'contact']);
        Route::get('header', [App\Http\Controllers\Api\V1\SettingController::class,'header']);

        Route::get('blogs', [App\Http\Controllers\Api\V1\PageController::class,'blogs']);
        Route::get('cities', [App\Http\Controllers\Api\V1\PageController::class,'cities']);
        Route::get('currencies', [App\Http\Controllers\Api\V1\PageController::class,'currencies']);
        Route::get('sliders', [App\Http\Controllers\Api\V1\PageController::class,'sliders']);
        Route::get('countries', [App\Http\Controllers\Api\V1\PageController::class,'countries']);
        Route::get('categories', [App\Http\Controllers\Api\V1\PageController::class,'categories']);
        Route::get('sub_categories', [App\Http\Controllers\Api\V1\PageController::class,'sub_categories']);

        Route::group(['middleware' => 'auth:sanctum'], function () {
            /////user/////
            Route::get('get_prefered_setting', [App\Http\Controllers\Api\V1\PageController::class, 'getPreferedSetting']);
            Route::post('change_prefered_setting', [App\Http\Controllers\Api\V1\PageController::class, 'changePreferedSetting']);
            Route::get('change-language/{language}', [App\Http\Controllers\Api\V1\PageController::class, 'changeLang']);
            Route::post('update-profile', [App\Http\Controllers\Api\V1\AuthController::class, 'updateProfile']);
            Route::post('update-image', [App\Http\Controllers\Api\V1\AuthController::class, 'updateimage']);
            Route::post('logout', [App\Http\Controllers\Api\V1\AuthController::class, 'logout']);
            Route::delete('delete-account', [App\Http\Controllers\Api\V1\AuthController::class, 'deleteAccount']);
            Route::post('fcm-token', [App\Http\Controllers\Api\V1\AuthController::class, 'updateToken']);
        });
    });
});

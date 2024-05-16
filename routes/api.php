    <?php

use App\Http\Controllers\Api\V1\AuthController;
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


Route::post('/reset', [AuthController::class, 'resetPassword']);
Route::post('/check-code', [AuthController::class, 'checkCode']);
Route::post('/confirm-reset', [AuthController::class, 'confirmReset']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


        Route::post('login', [App\Http\Controllers\Api\V1\AuthController::class, 'login']);
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
            Route::get('get_prefered_setting', [App\Http\Controllers\Api\V1\PageController::class, 'getPreferedSetting']);
            Route::post('change_prefered_setting', [App\Http\Controllers\Api\V1\PageController::class, 'changePreferedSetting']);
            Route::get('change-language/{language}', [App\Http\Controllers\Api\V1\PageController::class, 'changeLang']);

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

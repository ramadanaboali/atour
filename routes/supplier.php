    <?php

use App\Http\Controllers\Api\V1\Supplier\AuthController;
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

        Route::post('/setup1', [AuthController::class, 'setup1']);
        Route::post('/setup2', [AuthController::class, 'setup2']);
        Route::post('/setup3', [AuthController::class, 'setup3']);
        Route::post('/setup4', [AuthController::class, 'setup4']);
        Route::post('/setup5', [AuthController::class, 'setup5']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::post('/reset', [AuthController::class, 'resetPassword']);
        Route::post('/check-code', [AuthController::class, 'checkCode']);
        Route::post('/confirm-reset', [AuthController::class, 'confirmReset']);

        Route::group(['middleware' => 'auth:sanctum'], function () {
            /////user/////

// Route::get('subscriptions', [SubscriptionController::class, 'index'])->middleware('vendorPermission:subscriptions.view');

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

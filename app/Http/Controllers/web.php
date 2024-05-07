<?php

use Illuminate\Support\Facades\Route;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use MrShan0\PHPFirestore\Fields\FirestoreArray;
use MrShan0\PHPFirestore\Fields\FirestoreBytes;
use MrShan0\PHPFirestore\Fields\FirestoreGeoPoint;
use MrShan0\PHPFirestore\Fields\FirestoreObject;
use MrShan0\PHPFirestore\Fields\FirestoreTimestamp;
use MrShan0\PHPFirestore\FirestoreClient;
use MrShan0\PHPFirestore\FirestoreDocument;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Auth::routes();
Auth::routes([
    'register' => false,
    //'login' => false,
]);

Route::get('/', function () {
    if (!auth()->check()) {
        return to_route('login');
    } else {
        return to_route('account.home');
    }
})->name('home');
Route::get('/policy', [App\Http\Controllers\Front\PageController::class, 'policy'])->name('policy');

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return "Storage is linked";
});

Route::group(['middleware' => 'auth', 'as'=>'account.', 'prefix' => 'account'], function () {
    Route::get('/', [App\Http\Controllers\Account\AccountController::class, 'index'])->name('home');
    Route::get('courses', [App\Http\Controllers\Account\CourseController::class, 'courses'])->name('courses.index');
    Route::get('courses/{id}', [App\Http\Controllers\Account\CourseController::class, 'singleCourse'])->name('courses.single');
    Route::get('lesson/{id}', [App\Http\Controllers\Account\CourseController::class, 'singleLesson'])->name('lessons.single');
    Route::post('comment/{id}', [App\Http\Controllers\Account\CourseController::class, 'comment'])->name('lessons.comment');
});

<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::get('/privacy', [HomeController::class, 'privacy']);
Route::get('/language', [HomeController::class, 'language'])->name('language');
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created';
});
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return 'Cache cleared';
});
Route::get('/install-permissions', function () {
    Artisan::call('install:permissions');
    return 'Permission updated';
});


Route::post('sendcode', [App\Http\Controllers\Api\V1\AuthController::class, 'sendCode'])->name('sendcode');

Route::post('send/email', [App\Http\Controllers\Admin\AuthController::class, 'resetPassword'])->name('password.reset');
Route::get('confirm/email', [App\Http\Controllers\Admin\AuthController::class, 'resetConfirm'])->name('password.confirm');
Route::post('password/save', [App\Http\Controllers\Admin\AuthController::class, 'savePassword'])->name('password.save');

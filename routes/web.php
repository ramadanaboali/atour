<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/privacy', [HomeController::class, 'privacy']);
Route::get('/language', [HomeController::class, 'language'])->name('language');
Auth::routes();
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


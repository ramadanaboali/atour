<?php

use Illuminate\Support\Facades\Route;

Route::middleware('throttle:60,1')->group(function () {
    Route::get('admin/login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('admin.login');
    Route::post('admin/login', [App\Http\Controllers\Admin\AuthController::class, 'postLogin'])->name('admin.postLogin');
    Route::post('admin/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');
    Route::group(['middleware' => ['language']], function () {

        Route::group(
            ['middleware' => 'authenticate.admin', 'as' => 'admin.'],
            function () {
                Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index']);
            }
        );
        Route::group(['middleware' => 'authenticate.admin', 'as' => 'admin.', 'prefix' => 'admin'], function () {
            Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('home');

            Route::patch('/fcm-token', [App\Http\Controllers\Admin\AdminController::class, 'updateToken'])->name('fcmToken');
            Route::post('/send-notification', [App\Http\Controllers\Admin\AdminController::class,'notification'])->name('notification');


            Route::get('sliders/select', [App\Http\Controllers\Admin\SliderController::class, 'select'])->name('sliders.select');
            Route::delete('sliders/bulk', [App\Http\Controllers\Admin\SliderController::class, 'deleteBulk'])->name('sliders.deleteBulk')->middleware('adminPermission:sliders.delete');
            Route::get('sliders/list', [App\Http\Controllers\Admin\SliderController::class, 'list'])->name('sliders.list')->middleware('adminPermission:sliders.view');
            Route::post('sliders', [App\Http\Controllers\Admin\SliderController::class, 'store'])->name('sliders.store')->middleware('adminPermission:sliders.create');
            Route::delete('sliders/{id}', [App\Http\Controllers\Admin\SliderController::class, 'destroy'])->name('sliders.destroy')->middleware('adminPermission:sliders.delete');
            Route::get('sliders', [App\Http\Controllers\Admin\SliderController::class, 'index'])->name('sliders.index')->middleware('adminPermission:sliders.view');
            Route::get('sliders/create', [App\Http\Controllers\Admin\SliderController::class, 'create'])->name('sliders.create')->middleware('adminPermission:sliders.create');
            Route::match(['PUT', 'PATCH'], 'sliders/{id}', [App\Http\Controllers\Admin\SliderController::class, 'update'])->name('sliders.update')->middleware('adminPermission:sliders.edit');
            Route::get('sliders/{id}/edit', [App\Http\Controllers\Admin\SliderController::class, 'edit'])->name('sliders.edit')->middleware('adminPermission:sliders.edit');

            Route::get('notifications/select', [App\Http\Controllers\Admin\NotificationController::class, 'select'])->name('notifications.select');
            Route::delete('notifications/bulk', [App\Http\Controllers\Admin\NotificationController::class, 'deleteBulk'])->name('notifications.deleteBulk')->middleware('adminPermission:notifications.delete');
            Route::get('notifications/list', [App\Http\Controllers\Admin\NotificationController::class, 'list'])->name('notifications.list')->middleware('adminPermission:notifications.view');
            Route::post('notifications', [App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('notifications.store')->middleware('adminPermission:notifications.create');
            Route::delete('notifications/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy')->middleware('adminPermission:notifications.delete');
            Route::get('notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index')->middleware('adminPermission:notifications.view');
            Route::get('notifications/create', [App\Http\Controllers\Admin\NotificationController::class, 'create'])->name('notifications.create')->middleware('adminPermission:notifications.create');
            Route::match(['PUT', 'PATCH'], 'notifications/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'update'])->name('notifications.update')->middleware('adminPermission:notifications.edit');
            Route::get('notifications/{id}/edit', [App\Http\Controllers\Admin\NotificationController::class, 'edit'])->name('notifications.edit')->middleware('adminPermission:notifications.edit');

            Route::get('companies/select', [App\Http\Controllers\Admin\CompanyController::class, 'select'])->name('companies.select');
            Route::delete('companies/bulk', [App\Http\Controllers\Admin\CompanyController::class, 'deleteBulk'])->name('companies.deleteBulk')->middleware('adminPermission:companies.delete');
            Route::get('companies/list', [App\Http\Controllers\Admin\CompanyController::class, 'list'])->name('companies.list')->middleware('adminPermission:companies.view');
            Route::post('companies', [App\Http\Controllers\Admin\CompanyController::class, 'store'])->name('companies.store')->middleware('adminPermission:companies.create');
            Route::delete('companies/{id}', [App\Http\Controllers\Admin\CompanyController::class, 'destroy'])->name('companies.destroy')->middleware('adminPermission:companies.delete');
            Route::get('companies', [App\Http\Controllers\Admin\CompanyController::class, 'index'])->name('companies.index')->middleware('adminPermission:companies.view');
            Route::get('companies/create', [App\Http\Controllers\Admin\CompanyController::class, 'create'])->name('companies.create')->middleware('adminPermission:companies.create');
            Route::match(['PUT', 'PATCH'], 'companies/{id}', [App\Http\Controllers\Admin\CompanyController::class, 'update'])->name('companies.update')->middleware('adminPermission:companies.edit');
            Route::get('companies/{id}/edit', [App\Http\Controllers\Admin\CompanyController::class, 'edit'])->name('companies.edit')->middleware('adminPermission:companies.edit');

            Route::get('categories/select', [App\Http\Controllers\Admin\CategoryController::class, 'select'])->name('categories.select');
            Route::delete('categories/bulk', [App\Http\Controllers\Admin\CategoryController::class, 'deleteBulk'])->name('categories.deleteBulk')->middleware('adminPermission:categories.delete');
            Route::get('categories/list', [App\Http\Controllers\Admin\CategoryController::class, 'list'])->name('categories.list')->middleware('adminPermission:categories.view');
            Route::post('categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store')->middleware('adminPermission:categories.create');
            Route::delete('categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('adminPermission:categories.delete');
            Route::get('categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index')->middleware('adminPermission:categories.view');
            Route::get('categories/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create')->middleware('adminPermission:categories.create');
            Route::match(['PUT', 'PATCH'], 'categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update')->middleware('adminPermission:categories.edit');
            Route::get('categories/{id}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit')->middleware('adminPermission:categories.edit');

            Route::get('countries/select', [App\Http\Controllers\Admin\CountryController::class, 'select'])->name('countries.select');
            Route::delete('countries/bulk', [App\Http\Controllers\Admin\CountryController::class, 'deleteBulk'])->name('countries.deleteBulk')->middleware('adminPermission:countries.delete');
            Route::get('countries/list', [App\Http\Controllers\Admin\CountryController::class, 'list'])->name('countries.list')->middleware('adminPermission:countries.view');
            Route::post('countries', [App\Http\Controllers\Admin\CountryController::class, 'store'])->name('countries.store')->middleware('adminPermission:countries.create');
            Route::delete('countries/{id}', [App\Http\Controllers\Admin\CountryController::class, 'destroy'])->name('countries.destroy')->middleware('adminPermission:countries.delete');
            Route::get('countries', [App\Http\Controllers\Admin\CountryController::class, 'index'])->name('countries.index')->middleware('adminPermission:countries.view');
            Route::get('countries/create', [App\Http\Controllers\Admin\CountryController::class, 'create'])->name('countries.create')->middleware('adminPermission:countries.create');
            Route::match(['PUT', 'PATCH'], 'countries/{id}', [App\Http\Controllers\Admin\CountryController::class, 'update'])->name('countries.update')->middleware('adminPermission:countries.edit');
            Route::get('countries/{id}/edit', [App\Http\Controllers\Admin\CountryController::class, 'edit'])->name('countries.edit')->middleware('adminPermission:countries.edit');

            Route::get('users/select', [App\Http\Controllers\Admin\UserController::class, 'select'])->name('users.select');
            Route::get('users/list', [App\Http\Controllers\Admin\UserController::class, 'list'])->name('users.list')->middleware('adminPermission:users.view');
            Route::post('users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store')->middleware('adminPermission:users.create');
            Route::post('restore/{id}', [App\Http\Controllers\Admin\UserController::class, 'restore'])->name('users.restore')->middleware('adminPermission:users.create');
            Route::delete('users/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy')->middleware('adminPermission:users.delete');
            Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index')->middleware('adminPermission:users.view');
            Route::get('users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create')->middleware('adminPermission:users.create');
            Route::match(['PUT', 'PATCH'], 'users/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update')->middleware('adminPermission:users.edit');
            Route::get('users/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit')->middleware('adminPermission:users.edit');
            Route::get('users/show/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show')->middleware('adminPermission:users.show');

            Route::get('roles/select', [App\Http\Controllers\Admin\RoleController::class, 'select'])->name('roles.select');
            Route::get('roles/list', [App\Http\Controllers\Admin\RoleController::class, 'list'])->name('roles.list')->middleware('adminPermission:roles.view');
            Route::post('roles', [App\Http\Controllers\Admin\RoleController::class, 'store'])->name('roles.store')->middleware('adminPermission:roles.create');
            Route::delete('roles/{id}', [App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('roles.destroy')->middleware('adminPermission:roles.delete');
            Route::get('roles', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('roles.index')->middleware('adminPermission:roles.view');
            Route::get('roles/create', [App\Http\Controllers\Admin\RoleController::class, 'create'])->name('roles.create')->middleware('adminPermission:roles.create');
            Route::match(['PUT', 'PATCH'], 'roles/{id}', [App\Http\Controllers\Admin\RoleController::class, 'update'])->name('roles.update')->middleware('adminPermission:roles.edit');
            Route::get('roles/{id}/edit', [App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('roles.edit')->middleware('adminPermission:roles.edit');


            Route::get('settings/general', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index')->middleware('adminPermission:settings.general');
            Route::get('settings/site_Settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.site')->middleware('adminPermission:settings.general');
            Route::get('settings/header_settings', [App\Http\Controllers\Admin\SettingController::class, 'header'])->name('settings.header')->middleware('adminPermission:settings.header');
            Route::get('settings/footer_settings', [App\Http\Controllers\Admin\SettingController::class, 'footer_settings'])->name('settings.footer')->middleware('adminPermission:settings.footer');
            Route::get('settings/home_settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.home')->middleware('adminPermission:settings.general');
            Route::get('settings/slider_settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.slider')->middleware('adminPermission:settings.slider');
            Route::get('settings/about_settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.about')->middleware('adminPermission:settings.general');
            Route::get('settings/terms_preivasy_settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.terms_preivasy')->middleware('adminPermission:settings.general');
            Route::get('settings/experience_settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.experience')->middleware('adminPermission:settings.general');
            Route::get('settings/about', [App\Http\Controllers\Admin\SettingController::class, 'about'])->name('settings.about')->middleware('adminPermission:settings.about');
            Route::get('settings/privacy', [App\Http\Controllers\Admin\SettingController::class, 'privacy'])->name('settings.term_condition')->middleware('adminPermission:settings.privacy');
            Route::get('settings/terms', [App\Http\Controllers\Admin\SettingController::class, 'terms'])->name('settings.terms')->middleware('adminPermission:settings.terms');
            Route::post('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update')->middleware('adminPermission:settings.edit');


            Route::get('profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
            Route::post('update-profile', [App\Http\Controllers\Admin\ProfileController::class, 'updateProfile'])->name('profile.update');
            Route::get('change-password', [App\Http\Controllers\Admin\ProfileController::class, 'changePassword'])->name('profile.change_password');
            Route::post('update-password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.update_password');





            //addnewrouteheredontdeletemeplease

            Route::get('blogs/select', [App\Http\Controllers\Admin\BlogController::class, 'select'])->name('blogs.select');
            Route::delete('blogs/bulk', [App\Http\Controllers\Admin\BlogController::class, 'deleteBulk'])->name('blogs.deleteBulk')->middleware('adminPermission:blogs.delete');
            Route::get('blogs/list', [App\Http\Controllers\Admin\BlogController::class, 'list'])->name('blogs.list')->middleware('adminPermission:blogs.view');
            Route::post('blogs', [App\Http\Controllers\Admin\BlogController::class, 'store'])->name('blogs.store')->middleware('adminPermission:blogs.create');
            Route::delete('blogs/{id}', [App\Http\Controllers\Admin\BlogController::class, 'destroy'])->name('blogs.destroy')->middleware('adminPermission:blogs.delete');
            Route::get('blogs', [App\Http\Controllers\Admin\BlogController::class, 'index'])->name('blogs.index')->middleware('adminPermission:blogs.view');
            Route::get('blogs/create', [App\Http\Controllers\Admin\BlogController::class, 'create'])->name('blogs.create')->middleware('adminPermission:blogs.create');
            Route::match(['PUT', 'PATCH'], 'blogs/{id}', [App\Http\Controllers\Admin\BlogController::class, 'update'])->name('blogs.update')->middleware('adminPermission:blogs.edit');
            Route::get('blogs/{id}/edit', [App\Http\Controllers\Admin\BlogController::class, 'edit'])->name('blogs.edit')->middleware('adminPermission:blogs.edit');




            Route::get('currencies/select', [App\Http\Controllers\Admin\CurrencyController::class, 'select'])->name('currencies.select');
            Route::delete('currencies/bulk', [App\Http\Controllers\Admin\CurrencyController::class, 'deleteBulk'])->name('currencies.deleteBulk')->middleware('adminPermission:currencies.delete');
            Route::get('currencies/list', [App\Http\Controllers\Admin\CurrencyController::class, 'list'])->name('currencies.list')->middleware('adminPermission:currencies.view');
            Route::post('currencies', [App\Http\Controllers\Admin\CurrencyController::class, 'store'])->name('currencies.store')->middleware('adminPermission:currencies.create');
            Route::delete('currencies/{id}', [App\Http\Controllers\Admin\CurrencyController::class, 'destroy'])->name('currencies.destroy')->middleware('adminPermission:currencies.delete');
            Route::get('currencies', [App\Http\Controllers\Admin\CurrencyController::class, 'index'])->name('currencies.index')->middleware('adminPermission:currencies.view');
            Route::get('currencies/create', [App\Http\Controllers\Admin\CurrencyController::class, 'create'])->name('currencies.create')->middleware('adminPermission:currencies.create');
            Route::match(['PUT', 'PATCH'], 'currencies/{id}', [App\Http\Controllers\Admin\CurrencyController::class, 'update'])->name('currencies.update')->middleware('adminPermission:currencies.edit');
            Route::get('currencies/{id}/edit', [App\Http\Controllers\Admin\CurrencyController::class, 'edit'])->name('currencies.edit')->middleware('adminPermission:currencies.edit');




            Route::get('adds/select', [App\Http\Controllers\Admin\AddController::class, 'select'])->name('adds.select');
            Route::delete('adds/bulk', [App\Http\Controllers\Admin\AddController::class, 'deleteBulk'])->name('adds.deleteBulk')->middleware('adminPermission:adds.delete');
            Route::get('adds/list', [App\Http\Controllers\Admin\AddController::class, 'list'])->name('adds.list')->middleware('adminPermission:adds.view');
            Route::post('adds', [App\Http\Controllers\Admin\AddController::class, 'store'])->name('adds.store')->middleware('adminPermission:adds.create');
            Route::delete('adds/{id}', [App\Http\Controllers\Admin\AddController::class, 'destroy'])->name('adds.destroy')->middleware('adminPermission:adds.delete');
            Route::get('adds', [App\Http\Controllers\Admin\AddController::class, 'index'])->name('adds.index')->middleware('adminPermission:adds.view');
            Route::get('adds/create', [App\Http\Controllers\Admin\AddController::class, 'create'])->name('adds.create')->middleware('adminPermission:adds.create');
            Route::match(['PUT', 'PATCH'], 'adds/{id}', [App\Http\Controllers\Admin\AddController::class, 'update'])->name('adds.update')->middleware('adminPermission:adds.edit');
            Route::get('adds/{id}/edit', [App\Http\Controllers\Admin\AddController::class, 'edit'])->name('adds.edit')->middleware('adminPermission:adds.edit');

            Route::get('rates/list', [App\Http\Controllers\Admin\AdminController::class, 'listRate'])->name('rates.list')->middleware('adminPermission:rates.view');
            Route::get('favorites/list', [App\Http\Controllers\Admin\AdminController::class, 'listFavorite'])->name('favorites.list')->middleware('adminPermission:favorites.view');




            Route::get('why_bookings/select', [App\Http\Controllers\Admin\WhyBookingController::class, 'select'])->name('why_bookings.select');
            Route::delete('why_bookings/bulk', [App\Http\Controllers\Admin\WhyBookingController::class, 'deleteBulk'])->name('why_bookings.deleteBulk')->middleware('adminPermission:why_bookings.delete');
            Route::get('why_bookings/list', [App\Http\Controllers\Admin\WhyBookingController::class, 'list'])->name('why_bookings.list')->middleware('adminPermission:why_bookings.view');
            Route::post('why_bookings', [App\Http\Controllers\Admin\WhyBookingController::class, 'store'])->name('why_bookings.store')->middleware('adminPermission:why_bookings.create');
            Route::delete('why_bookings/{id}', [App\Http\Controllers\Admin\WhyBookingController::class, 'destroy'])->name('why_bookings.destroy')->middleware('adminPermission:why_bookings.delete');
            Route::get('why_bookings', [App\Http\Controllers\Admin\WhyBookingController::class, 'index'])->name('why_bookings.index')->middleware('adminPermission:why_bookings.view');
            Route::get('why_bookings/create', [App\Http\Controllers\Admin\WhyBookingController::class, 'create'])->name('why_bookings.create')->middleware('adminPermission:why_bookings.create');
            Route::match(['PUT', 'PATCH'], 'why_bookings/{id}', [App\Http\Controllers\Admin\WhyBookingController::class, 'update'])->name('why_bookings.update')->middleware('adminPermission:why_bookings.edit');
            Route::get('why_bookings/{id}/edit', [App\Http\Controllers\Admin\WhyBookingController::class, 'edit'])->name('why_bookings.edit')->middleware('adminPermission:why_bookings.edit');




            Route::get('departments/select', [App\Http\Controllers\Admin\DepartmentController::class, 'select'])->name('departments.select');
            Route::delete('departments/bulk', [App\Http\Controllers\Admin\DepartmentController::class, 'deleteBulk'])->name('departments.deleteBulk')->middleware('adminPermission:departments.delete');
            Route::get('departments/list', [App\Http\Controllers\Admin\DepartmentController::class, 'list'])->name('departments.list')->middleware('adminPermission:departments.view');
            Route::post('departments', [App\Http\Controllers\Admin\DepartmentController::class, 'store'])->name('departments.store')->middleware('adminPermission:departments.create');
            Route::delete('departments/{id}', [App\Http\Controllers\Admin\DepartmentController::class, 'destroy'])->name('departments.destroy')->middleware('adminPermission:departments.delete');
            Route::get('departments', [App\Http\Controllers\Admin\DepartmentController::class, 'index'])->name('departments.index')->middleware('adminPermission:departments.view');
            Route::get('departments/create', [App\Http\Controllers\Admin\DepartmentController::class, 'create'])->name('departments.create')->middleware('adminPermission:departments.create');
            Route::match(['PUT', 'PATCH'], 'departments/{id}', [App\Http\Controllers\Admin\DepartmentController::class, 'update'])->name('departments.update')->middleware('adminPermission:departments.edit');
            Route::get('departments/{id}/edit', [App\Http\Controllers\Admin\DepartmentController::class, 'edit'])->name('departments.edit')->middleware('adminPermission:departments.edit');




            Route::get('articles/select', [App\Http\Controllers\Admin\ArticleController::class, 'select'])->name('articles.select');
            Route::delete('articles/bulk', [App\Http\Controllers\Admin\ArticleController::class, 'deleteBulk'])->name('articles.deleteBulk')->middleware('adminPermission:articles.delete');
            Route::get('articles/list', [App\Http\Controllers\Admin\ArticleController::class, 'list'])->name('articles.list')->middleware('adminPermission:articles.view');
            Route::post('articles', [App\Http\Controllers\Admin\ArticleController::class, 'store'])->name('articles.store')->middleware('adminPermission:articles.create');
            Route::delete('articles/{id}', [App\Http\Controllers\Admin\ArticleController::class, 'destroy'])->name('articles.destroy')->middleware('adminPermission:articles.delete');
            Route::get('articles', [App\Http\Controllers\Admin\ArticleController::class, 'index'])->name('articles.index')->middleware('adminPermission:articles.view');
            Route::get('articles/create', [App\Http\Controllers\Admin\ArticleController::class, 'create'])->name('articles.create')->middleware('adminPermission:articles.create');
            Route::match(['PUT', 'PATCH'], 'articles/{id}', [App\Http\Controllers\Admin\ArticleController::class, 'update'])->name('articles.update')->middleware('adminPermission:articles.edit');
            Route::get('articles/{id}/edit', [App\Http\Controllers\Admin\ArticleController::class, 'edit'])->name('articles.edit')->middleware('adminPermission:articles.edit');




            Route::get('sub_categories/select', [App\Http\Controllers\Admin\SubCategoryController::class, 'select'])->name('sub_categories.select');
            Route::delete('sub_categories/bulk', [App\Http\Controllers\Admin\SubCategoryController::class, 'deleteBulk'])->name('sub_categories.deleteBulk')->middleware('adminPermission:sub_categories.delete');
            Route::get('sub_categories/list', [App\Http\Controllers\Admin\SubCategoryController::class, 'list'])->name('sub_categories.list')->middleware('adminPermission:sub_categories.view');
            Route::post('sub_categories', [App\Http\Controllers\Admin\SubCategoryController::class, 'store'])->name('sub_categories.store')->middleware('adminPermission:sub_categories.create');
            Route::delete('sub_categories/{id}', [App\Http\Controllers\Admin\SubCategoryController::class, 'destroy'])->name('sub_categories.destroy')->middleware('adminPermission:sub_categories.delete');
            Route::get('sub_categories', [App\Http\Controllers\Admin\SubCategoryController::class, 'index'])->name('sub_categories.index')->middleware('adminPermission:sub_categories.view');
            Route::get('sub_categories/create', [App\Http\Controllers\Admin\SubCategoryController::class, 'create'])->name('sub_categories.create')->middleware('adminPermission:sub_categories.create');
            Route::match(['PUT', 'PATCH'], 'sub_categories/{id}', [App\Http\Controllers\Admin\SubCategoryController::class, 'update'])->name('sub_categories.update')->middleware('adminPermission:sub_categories.edit');
            Route::get('sub_categories/{id}/edit', [App\Http\Controllers\Admin\SubCategoryController::class, 'edit'])->name('sub_categories.edit')->middleware('adminPermission:sub_categories.edit');




            Route::get('jobs/select', [App\Http\Controllers\Admin\JobController::class, 'select'])->name('jobs.select');
            Route::delete('jobs/bulk', [App\Http\Controllers\Admin\JobController::class, 'deleteBulk'])->name('jobs.deleteBulk')->middleware('adminPermission:jobs.delete');
            Route::get('jobs/list', [App\Http\Controllers\Admin\JobController::class, 'list'])->name('jobs.list')->middleware('adminPermission:jobs.view');
            Route::post('jobs', [App\Http\Controllers\Admin\JobController::class, 'store'])->name('jobs.store')->middleware('adminPermission:jobs.create');
            Route::delete('jobs/{id}', [App\Http\Controllers\Admin\JobController::class, 'destroy'])->name('jobs.destroy')->middleware('adminPermission:jobs.delete');
            Route::get('jobs', [App\Http\Controllers\Admin\JobController::class, 'index'])->name('jobs.index')->middleware('adminPermission:jobs.view');
            Route::get('jobs/create', [App\Http\Controllers\Admin\JobController::class, 'create'])->name('jobs.create')->middleware('adminPermission:jobs.create');
            Route::match(['PUT', 'PATCH'], 'jobs/{id}', [App\Http\Controllers\Admin\JobController::class, 'update'])->name('jobs.update')->middleware('adminPermission:jobs.edit');
            Route::get('jobs/{id}/edit', [App\Http\Controllers\Admin\JobController::class, 'edit'])->name('jobs.edit')->middleware('adminPermission:jobs.edit');




            Route::get('offers/select', [App\Http\Controllers\Admin\OfferController::class, 'select'])->name('offers.select');
            Route::delete('offers/bulk', [App\Http\Controllers\Admin\OfferController::class, 'deleteBulk'])->name('offers.deleteBulk')->middleware('adminPermission:offers.delete');
            Route::get('offers/list', [App\Http\Controllers\Admin\OfferController::class, 'list'])->name('offers.list')->middleware('adminPermission:offers.view');
            Route::post('offers', [App\Http\Controllers\Admin\OfferController::class, 'store'])->name('offers.store')->middleware('adminPermission:offers.create');
            Route::delete('offers/{id}', [App\Http\Controllers\Admin\OfferController::class, 'destroy'])->name('offers.destroy')->middleware('adminPermission:offers.delete');
            Route::get('offers', [App\Http\Controllers\Admin\OfferController::class, 'index'])->name('offers.index')->middleware('adminPermission:offers.view');
            Route::get('offers/create', [App\Http\Controllers\Admin\OfferController::class, 'create'])->name('offers.create')->middleware('adminPermission:offers.create');
            Route::match(['PUT', 'PATCH'], 'offers/{id}', [App\Http\Controllers\Admin\OfferController::class, 'update'])->name('offers.update')->middleware('adminPermission:offers.edit');
            Route::get('offers/{id}/edit', [App\Http\Controllers\Admin\OfferController::class, 'edit'])->name('offers.edit')->middleware('adminPermission:offers.edit');
            Route::get('offers/show/{id}', [App\Http\Controllers\Admin\OfferController::class, 'show'])->name('offers.show')->middleware('adminPermission:offers.view');



            Route::get('accountants', [App\Http\Controllers\Admin\OrderController::class, 'accountants'])->name('accountants.list')->middleware('adminPermission:accountants.view');

            Route::get('orders/select', [App\Http\Controllers\Admin\OrderController::class, 'select'])->name('orders.select');
            Route::delete('orders/bulk', [App\Http\Controllers\Admin\OrderController::class, 'deleteBulk'])->name('orders.deleteBulk')->middleware('adminPermission:orders.delete');
            Route::get('orders/list', [App\Http\Controllers\Admin\OrderController::class, 'list'])->name('orders.list')->middleware('adminPermission:orders.view');
            Route::post('orders', [App\Http\Controllers\Admin\OrderController::class, 'store'])->name('orders.store')->middleware('adminPermission:orders.create');
            Route::delete('orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy')->middleware('adminPermission:orders.delete');


            Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index')->middleware('adminPermission:orders.view');
            Route::get('new-orders', [App\Http\Controllers\Admin\OrderController::class, 'newOrders'])->name('new_orders.index')->middleware('adminPermission:new_orders.view');
            Route::get('current-orders', [App\Http\Controllers\Admin\OrderController::class, 'currentOrders'])->name('current_orders.index')->middleware('adminPermission:current_orders.view');
            Route::get('canceled-orders', [App\Http\Controllers\Admin\OrderController::class, 'canceledOrders'])->name('canceled_orders.index')->middleware('adminPermission:canceled_orders.view');
            Route::get('orders/show/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show')->middleware('adminPermission:orders.show');

            Route::get('suppliers/select', [App\Http\Controllers\Admin\SupplierController::class, 'select'])->name('suppliers.select');
            Route::delete('suppliers/bulk', [App\Http\Controllers\Admin\SupplierController::class, 'deleteBulk'])->name('suppliers.deleteBulk')->middleware('adminPermission:suppliers.delete');
            Route::get('suppliers/list', [App\Http\Controllers\Admin\SupplierController::class, 'list'])->name('suppliers.list')->middleware('adminPermission:suppliers.view');
            Route::post('suppliers/first_setup', [App\Http\Controllers\Admin\SupplierController::class, 'firstSetup'])->name('suppliers.first_setup')->middleware('adminPermission:suppliers.create');
            Route::delete('suppliers/{id}', [App\Http\Controllers\Admin\SupplierController::class, 'destroy'])->name('suppliers.destroy')->middleware('adminPermission:suppliers.delete');
            Route::get('suppliers', [App\Http\Controllers\Admin\SupplierController::class, 'index'])->name('suppliers.index')->middleware('adminPermission:suppliers.view');
            Route::match(['PUT', 'PATCH'], 'suppliers/{id}', [App\Http\Controllers\Admin\SupplierController::class, 'update'])->name('suppliers.update')->middleware('adminPermission:suppliers.edit');
            Route::get('suppliers/{id}/edit', [App\Http\Controllers\Admin\SupplierController::class, 'edit'])->name('suppliers.edit')->middleware('adminPermission:suppliers.edit');
            Route::get('suppliers-new', [App\Http\Controllers\Admin\SupplierController::class, 'newSuppliers'])->name('suppliers.new')->middleware('adminPermission:suppliers.new');
            Route::get('current-suppliers', [App\Http\Controllers\Admin\SupplierController::class, 'currentSuppliers'])->name('suppliers.current')->middleware('adminPermission:suppliers.current');
            Route::get('suppliers-request', [App\Http\Controllers\Admin\SupplierController::class, 'requestJoin'])->name('suppliers.requests')->middleware('adminPermission:suppliers.requests');
            Route::get('suppliers/status/{id}', [App\Http\Controllers\Admin\SupplierController::class, 'status'])->name('suppliers.status')->middleware('adminPermission:suppliers.status');
            Route::get('suppliers/show/{id}', [App\Http\Controllers\Admin\SupplierController::class, 'show'])->name('suppliers.show')->middleware('adminPermission:suppliers.show');
            Route::get('suppliers-orders/list', [App\Http\Controllers\Admin\SupplierController::class, 'orders'])->name('suppliers.orders')->middleware('adminPermission:suppliers.view');




            Route::get('clients/select', [App\Http\Controllers\Admin\ClientController::class, 'select'])->name('clients.select');
            Route::delete('clients/bulk', [App\Http\Controllers\Admin\ClientController::class, 'deleteBulk'])->name('clients.deleteBulk')->middleware('adminPermission:clients.delete');
            Route::get('clients/list', [App\Http\Controllers\Admin\ClientController::class, 'list'])->name('clients.list')->middleware('adminPermission:clients.view');
            Route::post('clients', [App\Http\Controllers\Admin\ClientController::class, 'store'])->name('clients.store')->middleware('adminPermission:clients.create');
            Route::delete('clients/{id}', [App\Http\Controllers\Admin\ClientController::class, 'destroy'])->name('clients.destroy')->middleware('adminPermission:clients.delete');
            Route::get('clients', [App\Http\Controllers\Admin\ClientController::class, 'index'])->name('clients.index')->middleware('adminPermission:clients.view');
            Route::get('clients/status/{id}', [App\Http\Controllers\Admin\ClientController::class, 'status'])->name('clients.status')->middleware('adminPermission:clients.status');
            Route::get('new-clients', [App\Http\Controllers\Admin\ClientController::class, 'newClients'])->name('new_clients.index')->middleware('adminPermission:new_clients.view');
            Route::get('current-clients', [App\Http\Controllers\Admin\ClientController::class, 'currentClients'])->name('current_clients.index')->middleware('adminPermission:current_clients.view');
            Route::get('clients/create', [App\Http\Controllers\Admin\ClientController::class, 'create'])->name('clients.create')->middleware('adminPermission:clients.create');
            Route::match(['PUT', 'PATCH'], 'clients/{id}', [App\Http\Controllers\Admin\ClientController::class, 'update'])->name('clients.update')->middleware('adminPermission:clients.edit');
            Route::get('clients/{id}/edit', [App\Http\Controllers\Admin\ClientController::class, 'edit'])->name('clients.edit')->middleware('adminPermission:clients.edit');

            Route::get('clients/show/{id}', [App\Http\Controllers\Admin\ClientController::class, 'show'])->name('clients.show')->middleware('adminPermission:clients.show');




            Route::get('cities/select', [App\Http\Controllers\Admin\CityController::class, 'select'])->name('cities.select');
            Route::delete('cities/bulk', [App\Http\Controllers\Admin\CityController::class, 'deleteBulk'])->name('cities.deleteBulk')->middleware('adminPermission:cities.delete');
            Route::get('cities/list', [App\Http\Controllers\Admin\CityController::class, 'list'])->name('cities.list')->middleware('adminPermission:cities.view');
            Route::post('cities', [App\Http\Controllers\Admin\CityController::class, 'store'])->name('cities.store')->middleware('adminPermission:cities.create');
            Route::delete('cities/{id}', [App\Http\Controllers\Admin\CityController::class, 'destroy'])->name('cities.destroy')->middleware('adminPermission:cities.delete');
            Route::get('cities', [App\Http\Controllers\Admin\CityController::class, 'index'])->name('cities.index')->middleware('adminPermission:cities.view');
            Route::get('cities/create', [App\Http\Controllers\Admin\CityController::class, 'create'])->name('cities.create')->middleware('adminPermission:cities.create');
            Route::match(['PUT', 'PATCH'], 'cities/{id}', [App\Http\Controllers\Admin\CityController::class, 'update'])->name('cities.update')->middleware('adminPermission:cities.edit');
            Route::get('cities/{id}/edit', [App\Http\Controllers\Admin\CityController::class, 'edit'])->name('cities.edit')->middleware('adminPermission:cities.edit');





        });
    });
});

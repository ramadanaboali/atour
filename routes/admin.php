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
            Route::delete('sliders/bulk', [App\Http\Controllers\Admin\SliderController::class, 'deleteBulk'])->name('sliders.deleteBulk')->middleware('permission:sliders.delete');
            Route::get('sliders/list', [App\Http\Controllers\Admin\SliderController::class, 'list'])->name('sliders.list')->middleware('permission:sliders.view');
            Route::post('sliders', [App\Http\Controllers\Admin\SliderController::class, 'store'])->name('sliders.store')->middleware('permission:sliders.create');
            Route::delete('sliders/{id}', [App\Http\Controllers\Admin\SliderController::class, 'destroy'])->name('sliders.destroy')->middleware('permission:sliders.delete');
            Route::get('sliders', [App\Http\Controllers\Admin\SliderController::class, 'index'])->name('sliders.index')->middleware('permission:sliders.view');
            Route::get('sliders/create', [App\Http\Controllers\Admin\SliderController::class, 'create'])->name('sliders.create')->middleware('permission:sliders.create');
            Route::match(['PUT', 'PATCH'], 'sliders/{id}', [App\Http\Controllers\Admin\SliderController::class, 'update'])->name('sliders.update')->middleware('permission:sliders.edit');
            Route::get('sliders/{id}/edit', [App\Http\Controllers\Admin\SliderController::class, 'edit'])->name('sliders.edit')->middleware('permission:sliders.edit');

            Route::get('notifications/select', [App\Http\Controllers\Admin\NotificationController::class, 'select'])->name('notifications.select');
            Route::delete('notifications/bulk', [App\Http\Controllers\Admin\NotificationController::class, 'deleteBulk'])->name('notifications.deleteBulk')->middleware('permission:notifications.delete');
            Route::get('notifications/list', [App\Http\Controllers\Admin\NotificationController::class, 'list'])->name('notifications.list')->middleware('permission:notifications.view');
            Route::post('notifications', [App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('notifications.store')->middleware('permission:notifications.create');
            Route::delete('notifications/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy')->middleware('permission:notifications.delete');
            Route::get('notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index')->middleware('permission:notifications.view');
            Route::get('notifications/create', [App\Http\Controllers\Admin\NotificationController::class, 'create'])->name('notifications.create')->middleware('permission:notifications.create');
            Route::match(['PUT', 'PATCH'], 'notifications/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'update'])->name('notifications.update')->middleware('permission:notifications.edit');
            Route::get('notifications/{id}/edit', [App\Http\Controllers\Admin\NotificationController::class, 'edit'])->name('notifications.edit')->middleware('permission:notifications.edit');

            Route::get('companies/select', [App\Http\Controllers\Admin\CompanyController::class, 'select'])->name('companies.select');
            Route::delete('companies/bulk', [App\Http\Controllers\Admin\CompanyController::class, 'deleteBulk'])->name('companies.deleteBulk')->middleware('permission:companies.delete');
            Route::get('companies/list', [App\Http\Controllers\Admin\CompanyController::class, 'list'])->name('companies.list')->middleware('permission:companies.view');
            Route::post('companies', [App\Http\Controllers\Admin\CompanyController::class, 'store'])->name('companies.store')->middleware('permission:companies.create');
            Route::delete('companies/{id}', [App\Http\Controllers\Admin\CompanyController::class, 'destroy'])->name('companies.destroy')->middleware('permission:companies.delete');
            Route::get('companies', [App\Http\Controllers\Admin\CompanyController::class, 'index'])->name('companies.index')->middleware('permission:companies.view');
            Route::get('companies/create', [App\Http\Controllers\Admin\CompanyController::class, 'create'])->name('companies.create')->middleware('permission:companies.create');
            Route::match(['PUT', 'PATCH'], 'companies/{id}', [App\Http\Controllers\Admin\CompanyController::class, 'update'])->name('companies.update')->middleware('permission:companies.edit');
            Route::get('companies/{id}/edit', [App\Http\Controllers\Admin\CompanyController::class, 'edit'])->name('companies.edit')->middleware('permission:companies.edit');

            Route::get('categories/select', [App\Http\Controllers\Admin\CategoryController::class, 'select'])->name('categories.select');
            Route::delete('categories/bulk', [App\Http\Controllers\Admin\CategoryController::class, 'deleteBulk'])->name('categories.deleteBulk')->middleware('permission:categories.delete');
            Route::get('categories/list', [App\Http\Controllers\Admin\CategoryController::class, 'list'])->name('categories.list')->middleware('permission:categories.view');
            Route::post('categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store')->middleware('permission:categories.create');
            Route::delete('categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('permission:categories.delete');
            Route::get('categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index')->middleware('permission:categories.view');
            Route::get('categories/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create')->middleware('permission:categories.create');
            Route::match(['PUT', 'PATCH'], 'categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update')->middleware('permission:categories.edit');
            Route::get('categories/{id}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit')->middleware('permission:categories.edit');

            Route::get('countries/select', [App\Http\Controllers\Admin\CountryController::class, 'select'])->name('countries.select');
            Route::delete('countries/bulk', [App\Http\Controllers\Admin\CountryController::class, 'deleteBulk'])->name('countries.deleteBulk')->middleware('permission:countries.delete');
            Route::get('countries/list', [App\Http\Controllers\Admin\CountryController::class, 'list'])->name('countries.list')->middleware('permission:countries.view');
            Route::post('countries', [App\Http\Controllers\Admin\CountryController::class, 'store'])->name('countries.store')->middleware('permission:countries.create');
            Route::delete('countries/{id}', [App\Http\Controllers\Admin\CountryController::class, 'destroy'])->name('countries.destroy')->middleware('permission:countries.delete');
            Route::get('countries', [App\Http\Controllers\Admin\CountryController::class, 'index'])->name('countries.index')->middleware('permission:countries.view');
            Route::get('countries/create', [App\Http\Controllers\Admin\CountryController::class, 'create'])->name('countries.create')->middleware('permission:countries.create');
            Route::match(['PUT', 'PATCH'], 'countries/{id}', [App\Http\Controllers\Admin\CountryController::class, 'update'])->name('countries.update')->middleware('permission:countries.edit');
            Route::get('countries/{id}/edit', [App\Http\Controllers\Admin\CountryController::class, 'edit'])->name('countries.edit')->middleware('permission:countries.edit');

            Route::get('users/select', [App\Http\Controllers\Admin\UserController::class, 'select'])->name('users.select');
            Route::get('users/list', [App\Http\Controllers\Admin\UserController::class, 'list'])->name('users.list')->middleware('permission:users.view');
            Route::post('users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store')->middleware('permission:users.create');
            Route::post('restore/{id}', [App\Http\Controllers\Admin\UserController::class, 'restore'])->name('users.restore')->middleware('permission:users.create');
            Route::delete('users/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:users.delete');
            Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index')->middleware('permission:users.view');
            Route::get('users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create')->middleware('permission:users.create');
            Route::match(['PUT', 'PATCH'], 'users/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update')->middleware('permission:users.edit');
            Route::get('users/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit')->middleware('permission:users.edit');
            Route::get('users/show/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show')->middleware('permission:users.show');

            Route::get('roles/select', [App\Http\Controllers\Admin\RoleController::class, 'select'])->name('roles.select');
            Route::get('roles/list', [App\Http\Controllers\Admin\RoleController::class, 'list'])->name('roles.list')->middleware('permission:roles.view');
            Route::post('roles', [App\Http\Controllers\Admin\RoleController::class, 'store'])->name('roles.store')->middleware('permission:roles.create');
            Route::delete('roles/{id}', [App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:roles.delete');
            Route::get('roles', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('roles.index')->middleware('permission:roles.view');
            Route::get('roles/create', [App\Http\Controllers\Admin\RoleController::class, 'create'])->name('roles.create')->middleware('permission:roles.create');
            Route::match(['PUT', 'PATCH'], 'roles/{id}', [App\Http\Controllers\Admin\RoleController::class, 'update'])->name('roles.update')->middleware('permission:roles.edit');
            Route::get('roles/{id}/edit', [App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:roles.edit');


            Route::get('settings/general', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index')->middleware('permission:settings.general');
            Route::get('settings/about', [App\Http\Controllers\Admin\SettingController::class, 'about'])->name('settings.about')->middleware('permission:settings.about');
            Route::get('settings/privacy', [App\Http\Controllers\Admin\SettingController::class, 'privacy'])->name('settings.privacy')->middleware('permission:settings.privacy');
            Route::get('settings/terms', [App\Http\Controllers\Admin\SettingController::class, 'terms'])->name('settings.terms')->middleware('permission:settings.terms');
            Route::post('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update')->middleware('permission:settings.edit');


            Route::get('profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
            Route::post('update-profile', [App\Http\Controllers\Admin\ProfileController::class, 'updateProfile'])->name('profile.update');
            Route::get('change-password', [App\Http\Controllers\Admin\ProfileController::class, 'changePassword'])->name('profile.change_password');
            Route::post('update-password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.update_password');









        //addnewrouteheredontdeletemeplease

            Route::get('sub_categories/select', [App\Http\Controllers\Admin\SubCategoryController::class, 'select'])->name('sub_categories.select');
            Route::delete('sub_categories/bulk', [App\Http\Controllers\Admin\SubCategoryController::class, 'deleteBulk'])->name('sub_categories.deleteBulk')->middleware('permission:sub_categories.delete');
            Route::get('sub_categories/list', [App\Http\Controllers\Admin\SubCategoryController::class, 'list'])->name('sub_categories.list')->middleware('permission:sub_categories.view');
            Route::post('sub_categories', [App\Http\Controllers\Admin\SubCategoryController::class, 'store'])->name('sub_categories.store')->middleware('permission:sub_categories.create');
            Route::delete('sub_categories/{id}', [App\Http\Controllers\Admin\SubCategoryController::class, 'destroy'])->name('sub_categories.destroy')->middleware('permission:sub_categories.delete');
            Route::get('sub_categories', [App\Http\Controllers\Admin\SubCategoryController::class, 'index'])->name('sub_categories.index')->middleware('permission:sub_categories.view');
            Route::get('sub_categories/create', [App\Http\Controllers\Admin\SubCategoryController::class, 'create'])->name('sub_categories.create')->middleware('permission:sub_categories.create');
            Route::match(['PUT', 'PATCH'], 'sub_categories/{id}', [App\Http\Controllers\Admin\SubCategoryController::class, 'update'])->name('sub_categories.update')->middleware('permission:sub_categories.edit');
            Route::get('sub_categories/{id}/edit', [App\Http\Controllers\Admin\SubCategoryController::class, 'edit'])->name('sub_categories.edit')->middleware('permission:sub_categories.edit');




            Route::get('jobs/select', [App\Http\Controllers\Admin\JobController::class, 'select'])->name('jobs.select');
            Route::delete('jobs/bulk', [App\Http\Controllers\Admin\JobController::class, 'deleteBulk'])->name('jobs.deleteBulk')->middleware('permission:jobs.delete');
            Route::get('jobs/list', [App\Http\Controllers\Admin\JobController::class, 'list'])->name('jobs.list')->middleware('permission:jobs.view');
            Route::post('jobs', [App\Http\Controllers\Admin\JobController::class, 'store'])->name('jobs.store')->middleware('permission:jobs.create');
            Route::delete('jobs/{id}', [App\Http\Controllers\Admin\JobController::class, 'destroy'])->name('jobs.destroy')->middleware('permission:jobs.delete');
            Route::get('jobs', [App\Http\Controllers\Admin\JobController::class, 'index'])->name('jobs.index')->middleware('permission:jobs.view');
            Route::get('jobs/create', [App\Http\Controllers\Admin\JobController::class, 'create'])->name('jobs.create')->middleware('permission:jobs.create');
            Route::match(['PUT', 'PATCH'], 'jobs/{id}', [App\Http\Controllers\Admin\JobController::class, 'update'])->name('jobs.update')->middleware('permission:jobs.edit');
            Route::get('jobs/{id}/edit', [App\Http\Controllers\Admin\JobController::class, 'edit'])->name('jobs.edit')->middleware('permission:jobs.edit');




            Route::get('offers/select', [App\Http\Controllers\Admin\OfferController::class, 'select'])->name('offers.select');
            Route::delete('offers/bulk', [App\Http\Controllers\Admin\OfferController::class, 'deleteBulk'])->name('offers.deleteBulk')->middleware('permission:offers.delete');
            Route::get('offers/list', [App\Http\Controllers\Admin\OfferController::class, 'list'])->name('offers.list')->middleware('permission:offers.view');
            Route::post('offers', [App\Http\Controllers\Admin\OfferController::class, 'store'])->name('offers.store')->middleware('permission:offers.create');
            Route::delete('offers/{id}', [App\Http\Controllers\Admin\OfferController::class, 'destroy'])->name('offers.destroy')->middleware('permission:offers.delete');
            Route::get('offers', [App\Http\Controllers\Admin\OfferController::class, 'index'])->name('offers.index')->middleware('permission:offers.view');
            Route::get('offers/create', [App\Http\Controllers\Admin\OfferController::class, 'create'])->name('offers.create')->middleware('permission:offers.create');
            Route::match(['PUT', 'PATCH'], 'offers/{id}', [App\Http\Controllers\Admin\OfferController::class, 'update'])->name('offers.update')->middleware('permission:offers.edit');
            Route::get('offers/{id}/edit', [App\Http\Controllers\Admin\OfferController::class, 'edit'])->name('offers.edit')->middleware('permission:offers.edit');




            Route::get('orders/select', [App\Http\Controllers\Admin\OrderController::class, 'select'])->name('orders.select');
            Route::delete('orders/bulk', [App\Http\Controllers\Admin\OrderController::class, 'deleteBulk'])->name('orders.deleteBulk')->middleware('permission:orders.delete');
            Route::get('orders/list', [App\Http\Controllers\Admin\OrderController::class, 'list'])->name('orders.list')->middleware('permission:orders.view');
            Route::post('orders', [App\Http\Controllers\Admin\OrderController::class, 'store'])->name('orders.store')->middleware('permission:orders.create');
            Route::delete('orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy')->middleware('permission:orders.delete');


Route::get('orders', [App\Http\Controllers\Admin\SupplierController::class, 'index'])->name('orders.index')->middleware('permission:orders.view');
Route::get('new-orders', [App\Http\Controllers\Admin\SupplierController::class, 'index'])->name('new_orders.index')->middleware('permission:new_orders.view');
Route::get('current-orders', [App\Http\Controllers\Admin\SupplierController::class, 'index'])->name('current_orders.index')->middleware('permission:current_orders.view');


            Route::get('orders/create', [App\Http\Controllers\Admin\OrderController::class, 'create'])->name('orders.create')->middleware('permission:orders.create');
            Route::match(['PUT', 'PATCH'], 'orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'update'])->name('orders.update')->middleware('permission:orders.edit');
            Route::get('orders/{id}/edit', [App\Http\Controllers\Admin\OrderController::class, 'edit'])->name('orders.edit')->middleware('permission:orders.edit');




            Route::get('suppliers/select', [App\Http\Controllers\Admin\SupplierController::class, 'select'])->name('suppliers.select');
            Route::delete('suppliers/bulk', [App\Http\Controllers\Admin\SupplierController::class, 'deleteBulk'])->name('suppliers.deleteBulk')->middleware('permission:suppliers.delete');
            Route::get('suppliers/list', [App\Http\Controllers\Admin\SupplierController::class, 'list'])->name('suppliers.list')->middleware('permission:suppliers.view');
            Route::post('suppliers', [App\Http\Controllers\Admin\SupplierController::class, 'store'])->name('suppliers.store')->middleware('permission:suppliers.create');
            Route::delete('suppliers/{id}', [App\Http\Controllers\Admin\SupplierController::class, 'destroy'])->name('suppliers.destroy')->middleware('permission:suppliers.delete');

            Route::get('suppliers', [App\Http\Controllers\Admin\SupplierController::class, 'index'])->name('suppliers.index')->middleware('permission:suppliers.view');
            Route::get('new-suppliers', [App\Http\Controllers\Admin\SupplierController::class, 'index'])->name('new_suppliers.index')->middleware('permission:new_suppliers.view');
            Route::get('current-suppliers', [App\Http\Controllers\Admin\SupplierController::class, 'index'])->name('current_suppliers.index')->middleware('permission:current_suppliers.view');

            Route::get('suppliers/create', [App\Http\Controllers\Admin\SupplierController::class, 'create'])->name('suppliers.create')->middleware('permission:suppliers.create');
            Route::match(['PUT', 'PATCH'], 'suppliers/{id}', [App\Http\Controllers\Admin\SupplierController::class, 'update'])->name('suppliers.update')->middleware('permission:suppliers.edit');
            Route::get('suppliers/{id}/edit', [App\Http\Controllers\Admin\SupplierController::class, 'edit'])->name('suppliers.edit')->middleware('permission:suppliers.edit');




            Route::get('clients/select', [App\Http\Controllers\Admin\ClientController::class, 'select'])->name('clients.select');
            Route::delete('clients/bulk', [App\Http\Controllers\Admin\ClientController::class, 'deleteBulk'])->name('clients.deleteBulk')->middleware('permission:clients.delete');
            Route::get('clients/list', [App\Http\Controllers\Admin\ClientController::class, 'list'])->name('clients.list')->middleware('permission:clients.view');
            Route::post('clients', [App\Http\Controllers\Admin\ClientController::class, 'store'])->name('clients.store')->middleware('permission:clients.create');
            Route::delete('clients/{id}', [App\Http\Controllers\Admin\ClientController::class, 'destroy'])->name('clients.destroy')->middleware('permission:clients.delete');
            Route::get('clients', [App\Http\Controllers\Admin\ClientController::class, 'index'])->name('clients.index')->middleware('permission:clients.view');
            Route::get('new-clients', [App\Http\Controllers\Admin\ClientController::class, 'index'])->name('new_clients.index')->middleware('permission:new_clients.view');
            Route::get('current-clients', [App\Http\Controllers\Admin\ClientController::class, 'index'])->name('current_clients.index')->middleware('permission:current_clients.view');
            Route::get('clients/create', [App\Http\Controllers\Admin\ClientController::class, 'create'])->name('clients.create')->middleware('permission:clients.create');
            Route::match(['PUT', 'PATCH'], 'clients/{id}', [App\Http\Controllers\Admin\ClientController::class, 'update'])->name('clients.update')->middleware('permission:clients.edit');
            Route::get('clients/{id}/edit', [App\Http\Controllers\Admin\ClientController::class, 'edit'])->name('clients.edit')->middleware('permission:clients.edit');




            Route::get('cities/select', [App\Http\Controllers\Admin\CityController::class, 'select'])->name('cities.select');
            Route::delete('cities/bulk', [App\Http\Controllers\Admin\CityController::class, 'deleteBulk'])->name('cities.deleteBulk')->middleware('permission:cities.delete');
            Route::get('cities/list', [App\Http\Controllers\Admin\CityController::class, 'list'])->name('cities.list')->middleware('permission:cities.view');
            Route::post('cities', [App\Http\Controllers\Admin\CityController::class, 'store'])->name('cities.store')->middleware('permission:cities.create');
            Route::delete('cities/{id}', [App\Http\Controllers\Admin\CityController::class, 'destroy'])->name('cities.destroy')->middleware('permission:cities.delete');
            Route::get('cities', [App\Http\Controllers\Admin\CityController::class, 'index'])->name('cities.index')->middleware('permission:cities.view');
            Route::get('cities/create', [App\Http\Controllers\Admin\CityController::class, 'create'])->name('cities.create')->middleware('permission:cities.create');
            Route::match(['PUT', 'PATCH'], 'cities/{id}', [App\Http\Controllers\Admin\CityController::class, 'update'])->name('cities.update')->middleware('permission:cities.edit');
            Route::get('cities/{id}/edit', [App\Http\Controllers\Admin\CityController::class, 'edit'])->name('cities.edit')->middleware('permission:cities.edit');





        });
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\HatcheryCategoryController;
use App\Http\Controllers\Admin\HatcheryLocationController;
use App\Http\Controllers\Admin\HatcheryController;
use App\Http\Controllers\Admin\HatcheryPostController;
use App\Http\Controllers\Admin\HatcherySeedController;
use App\Http\Controllers\Admin\BroadStockController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BannerController;
// Redirect root URL to login
Route::get('/', function () {
    return redirect('/admin');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('welcome');
    })->name('login');
});
// Admin Profile Routes
Route::get('admin/profile', [AdminController::class, 'profile'])->name('admin_profile');
Route::put('admin/profile', [AdminController::class, 'updateAdminProfile'])->name('update_admin_profile');

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');
Auth::routes();

Route::group(['middleware' => ['auth', \App\Http\Middleware\IsAdmin::class]], function () {
    Route::get('/admin', [HomeController::class, 'index'])->name('admin');
    Route::resource('admin/site-settings', '\App\Http\Controllers\Admin\SettingsController');

    // Vendor routes
    Route::resource('admin/vendors', VendorController::class);
    Route::get('admin/vendors/{vendor}/credentials', [VendorController::class, 'getCredentials'])
        ->name('vendors.credentials');
    Route::put('admin/vendors/{vendor}/activate', [VendorController::class, 'activate'])
        ->name('vendors.activate');

    Route::post('/admin/vendor-profile-image-delete', [VendorController::class, 'deleteProfileImage'])
    ->name('admin.image.delete');

    Route::resource('admin/hatchery-categories', HatcheryCategoryController::class);
    Route::resource('admin/hatchery-locations', HatcheryLocationController::class);
    Route::resource('admin/hatcheries', HatcheryController::class);
    Route::resource('admin/hatchery-updates', HatcheryPostController::class);
    Route::resource('admin/hatchery-seeds', HatcherySeedController::class);
    Route::resource('admin/broad-stocks', BroadStockController::class);
    Route::resource('admin/bookings', BookingController::class);
    Route::resource('admin/banners', BannerController::class);







});

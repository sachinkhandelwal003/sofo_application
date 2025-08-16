<?php


use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\CallerController;
use App\Routes\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\StoreOnBoardController;
use App\Http\Controllers\Admin\StoreIteamController;
use App\Http\Controllers\Admin\AppUserController;
use App\Http\Controllers\Admin\ReviewController;


/*
|--------------------------------------------------------------------------
| Web Routes For Admin
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Admin & Sub-Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'permission', 'authCheck', 'verified'])->group(function () {
    Profile::routes();
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

    // ----------------------- Role Routes ----------------------------------------------------
    Route::controller(RolesController::class)->name('roles')->group(function () {
        Route::get('roles', 'index')->middleware('isAllow:102,can_view');
        Route::post('roles', 'save')->middleware('isAllow:102,can_add');
        Route::put('roles', 'update')->middleware('isAllow:102,can_edit');
        Route::delete('roles', 'delete')->middleware('isAllow:102,can_delete');
        Route::get('roles/permission/{id}', 'permission')->name('.permission.view')->middleware('isAllow:102,can_edit');
        Route::put('roles/permission', 'permission_update')->name('.permission.update')->middleware('isAllow:102,can_edit');
    });

    // ----------------------- Admin and Sub Admin Routes ----------------------------------------------------
    Route::controller(UsersController::class)->group(function () {
        Route::get('users', 'index')->name('users')->middleware('isAllow:103,can_view');
        Route::get('users/add', 'add')->name('users.add')->middleware('isAllow:103,can_add');
        Route::post('users/add', 'save')->name('users.add')->middleware('isAllow:103,can_add');
        Route::get('users/{slug}', 'edit')->name('users.edit')->middleware('isAllow:103,can_edit');
        Route::post('users/{slug}', 'update')->name('users.edit')->middleware('isAllow:103,can_edit');
        Route::delete('users', 'delete')->name('users')->middleware('isAllow:103,can_delete');
        Route::get('users/permission/{id}', 'permission')->name('users.permission.view')->middleware('isAllow:103,can_edit');
        Route::put('users/permission', 'permission_update')->name('users.permission.update')->middleware('isAllow:103,can_edit');
    });

    // ----------------------- Country Routes ----------------------------------------------------
    Route::controller(CountryController::class)->name('countries')->group(function () {
        Route::get('countries', 'index')->middleware('isAllow:105,can_view');
        Route::post('countries', 'save')->middleware('isAllow:105,can_add');
        Route::put('countries', 'update')->middleware('isAllow:105,can_edit');
        Route::delete('countries', 'delete')->middleware('isAllow:105,can_delete');
    });


    // ----------------------- States Routes ----------------------------------------------------
    Route::controller(StateController::class)->name('states')->group(function () {
        Route::get('states', 'index')->middleware('isAllow:105,can_view');
        Route::post('states', 'save')->middleware('isAllow:105,can_add');
        Route::put('states', 'update')->middleware('isAllow:105,can_edit');
        Route::delete('states', 'delete')->middleware('isAllow:105,can_delete');
    });

    // ----------------------- City Routes ----------------------------------------------------
    Route::controller(CityController::class)->name('cities')->group(function () {
        Route::get('cities', 'index')->middleware('isAllow:106,can_view');
        Route::post('cities', 'save')->middleware('isAllow:106,can_add');
        Route::put('cities', 'update')->middleware('isAllow:106,can_edit');
        Route::delete('cities', 'delete')->middleware('isAllow:106,can_delete');
    });

    // ----------------------- CMS Routes ----------------------------------------------------
    Route::controller(CmsController::class)->group(function () {
        Route::get('cms', 'index')->name('cms')->middleware('isAllow:104,can_view');
        Route::get('cms/add', 'add')->name('cms.add')->middleware('isAllow:104,can_add');
        Route::post('cms/add', 'save')->name('cms.add')->middleware('isAllow:104,can_add');
        Route::get('cms/{id}', 'edit')->name('cms.edit')->middleware('isAllow:104,can_edit');
        Route::post('cms', 'slug')->name('cms.slug')->middleware('isAllow:104,can_edit');
        Route::post('cms/{id}', 'update')->name('cms.edit')->middleware('isAllow:104,can_edit');
        Route::delete('cms', 'delete')->name('cms')->middleware('isAllow:104,can_delete');
    });
    // ----------------------- review Routes ----------------------------------------------------
    Route::controller(ReviewController::class)->group(function () {
        Route::get('review', 'index')->name('review')->middleware('isAllow:104,can_view');
        Route::get('review/add', 'add')->name('review.add')->middleware('isAllow:104,can_add');
        Route::post('review/add', 'save')->name('review.add')->middleware('isAllow:104,can_add');
        Route::get('review/{id}', 'edit')->name('review.edit')->middleware('isAllow:104,can_edit');
        Route::post('review', 'slug')->name('review.slug')->middleware('isAllow:104,can_edit');
        Route::post('review/{id}', 'update')->name('review.edit')->middleware('isAllow:104,can_edit');
        Route::delete('review', 'delete')->name('review')->middleware('isAllow:104,can_delete');
    });

    // ----------------------- Callers Routes ----------------------------------------------------
    Route::controller(CallerController::class)->group(function () {
        Route::get('callers', 'index')->name('callers')->middleware('isAllow:104,can_view');
        Route::get('callers/add', 'add')->name('callers.add')->middleware('isAllow:104,can_add');
        Route::post('callers/add', 'save')->name('callers.add')->middleware('isAllow:104,can_add');
        Route::get('callers/{id}', 'edit')->name('callers.edit')->middleware('isAllow:104,can_edit');
        Route::post('callers', 'slug')->name('callers.slug')->middleware('isAllow:104,can_edit');
        Route::post('callers/{id}', 'update')->name('callers.edit')->middleware('isAllow:104,can_edit');
        Route::delete('callers', 'delete')->name('callers')->middleware('isAllow:104,can_delete');
    });


    // ----------------------- Plans Routes ----------------------------------------------------
    Route::controller(PlanController::class)->group(function () {
        Route::get('plans', 'index')->name('plans')->middleware('isAllow:104,can_view');
        Route::get('plans/add', 'add')->name('plans.add')->middleware('isAllow:104,can_add');
        Route::post('plans/add', 'save')->name('plans.add')->middleware('isAllow:104,can_add');
        Route::get('plans/{id}', 'edit')->name('plans.edit')->middleware('isAllow:104,can_edit');
        Route::post('plans', 'slug')->name('plans.slug')->middleware('isAllow:104,can_edit');
        Route::post('plans/{id}', 'update')->name('plans.edit')->middleware('isAllow:104,can_edit');
        Route::delete('plans', 'delete')->name('plans')->middleware('isAllow:104,can_delete');
    });


    // ----------------------- Orders Routes ----------------------------------------------------
    Route::controller(OrderController::class)->group(function () {
        Route::get('orders', 'index')->name('orders')->middleware('isAllow:104,can_view');
        Route::get('orders/add', 'add')->name('orders.add')->middleware('isAllow:104,can_add');
        Route::post('orders/add', 'save')->name('orders.add')->middleware('isAllow:104,can_add');
        Route::get('orders/{id}', 'edit')->name('orders.edit')->middleware('isAllow:104,can_edit');
        Route::post('orders', 'slug')->name('orders.slug')->middleware('isAllow:104,can_edit');
        Route::post('orders/{id}', 'update')->name('orders.edit')->middleware('isAllow:104,can_edit');
        Route::delete('orders', 'delete')->name('orders')->middleware('isAllow:104,can_delete');
    });

    // ----------------------- Category Routes ----------------------------------------------------
    Route::controller(CategoryController::class)->group(function () {
        Route::get('categories', 'index')->name('categories')->middleware('isAllow:104,can_view');
        Route::get('categories/add', 'add')->name('categories.add')->middleware('isAllow:104,can_add');
        Route::post('categories/add', 'save')->name('categories.add')->middleware('isAllow:104,can_add');
        Route::get('categories/{id}', 'edit')->name('categories.edit')->middleware('isAllow:104,can_edit');
        Route::post('categories', 'slug')->name('categories.slug')->middleware('isAllow:104,can_edit');
        Route::post('categories/{id}', 'update')->name('categories.update')->middleware('isAllow:104,can_edit');
        Route::delete('categories', 'delete')->name('categories')->middleware('isAllow:104,can_delete');
    });

    // ----------------------- Stores Routes ----------------------------------------------------
    Route::controller(StoreOnBoardController::class)->group(function () {
        Route::get('add-iteam', 'index')->name('additeam')->middleware('isAllow:104,can_view');
        Route::get('stores', 'index')->name('stores')->middleware('isAllow:104,can_view');
        Route::get('stores/add', 'add')->name('stores.add')->middleware('isAllow:104,can_add');
        Route::post('stores/add', 'save')->name('stores.add')->middleware('isAllow:104,can_add');
        Route::get('stores/{id}', 'edit')->name('stores.edit')->middleware('isAllow:104,can_edit');
        Route::post('stores', 'slug')->name('stores.slug')->middleware('isAllow:104,can_edit');
        Route::post('stores/{id}', 'update')->name('stores.update')->middleware('isAllow:104,can_edit');
        Route::delete('stores', 'delete')->name('stores')->middleware('isAllow:104,can_delete');
        Route::get('stores/{vendorId}/items', 'viewItems')->name('stores.items')->middleware('isAllow:104,can_view');
    });

    // ----------------------- Store Iteam Routes ----------------------------------------------------
    Route::controller(StoreIteamController::class)->group(function () {
        Route::get('store-iteam', 'index')->name('store-iteam')->middleware('isAllow:104,can_view');
        Route::get('store-iteam/add', 'add')->name('store-iteam.add')->middleware('isAllow:104,can_add');
        Route::post('store-iteam/add', 'save')->name('store-iteam.add')->middleware('isAllow:104,can_add');
        Route::get('store-iteam/{id}', 'edit')->name('store-iteam.edit')->middleware('isAllow:104,can_edit');
        Route::post('store-iteam', 'slug')->name('store-iteam.slug')->middleware('isAllow:104,can_edit');
        Route::post('store-iteam/{id}', 'update')->name('store-iteam.update')->middleware('isAllow:104,can_edit');
        Route::delete('store-iteam', 'delete')->name('store-iteam')->middleware('isAllow:104,can_delete');
    });

    // ----------------------- Vendor Routes ----------------------------------------------------
    Route::controller(VendorController::class)->group(function () {
        Route::get('vendor', 'index')->name('vendor')->middleware('isAllow:104,can_view');
        Route::post('vendor/change-status', 'changeStatus')
            ->name('vendor.changeStatus')
            ->middleware('isAllow:104,can_edit');
        Route::get('vendor/add', 'add')->name('vendor.add')->middleware('isAllow:104,can_add');
        Route::post('vendor/add', 'save')->name('vendor.add')->middleware('isAllow:104,can_add');
        Route::get('vendor/{id}', 'edit')->name('vendor.edit')->middleware('isAllow:104,can_edit');
        Route::post('vendor/{id}', 'update')->name('vendor.update')->middleware('isAllow:104,can_edit');
        Route::delete('vendor', 'delete')->name('vendor')->middleware('isAllow:104,can_delete');
    });

    // ----------------------- Appusers Routes ----------------------------------------------------
    Route::controller(AppUserController::class)->group(function () {
        Route::get('app-user', 'index')->name('app-user')->middleware('isAllow:104,can_view');
        Route::get('app-user/add', 'add')->name('app-user.add')->middleware('isAllow:104,can_add');
        Route::post('app-user/add', 'save')->name('app-user.add')->middleware('isAllow:104,can_add');
        Route::get('app-user/{id}', 'edit')->name('app-user.edit')->middleware('isAllow:104,can_edit');
        Route::post('app-user', 'slug')->name('app-user.slug')->middleware('isAllow:104,can_edit');
        Route::post('app-user/{id}', 'update')->name('app-user.update')->middleware('isAllow:104,can_edit');
        Route::delete('app-user', 'delete')->name('app-user')->middleware('isAllow:104,can_delete');
    });
    Route::get('/admin/categories/search', [StoreOnBoardController::class, 'search']);
    Route::any('setting/{id}', [SettingController::class, 'setting'])->name('setting')->middleware('isAllow:101,can_view');
    Route::get('database-backup', [SettingController::class, 'database_backup'])->name('database_backup')->middleware('isAllow:101,can_view');
    Route::get('server-control', [SettingController::class, 'serverControl'])->name('server-control')->middleware('isAllow:101,can_view');
    Route::post('server-control', [SettingController::class, 'serverControlSave'])->name('server-control')->middleware('isAllow:101,can_view');
});

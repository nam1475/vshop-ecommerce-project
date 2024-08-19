<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\CartController;

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

/* User */
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'home')->name('home');
});

Route::middleware('user')->name('user.')->group(function () {
    Route::controller(ProfileController::class)->name('profile.')->group(function () {
        Route::get('/profile/edit', 'edit')->name('edit');
        Route::patch('/profile/update', 'update')->name('update');
        Route::delete('/profile/delete', 'destroy')->name('destroy');
    });

    Route::controller(CartController::class)->name('cart.')->group(function () {
        Route::get('/cart/list', 'list')->name('list');
        Route::post('/cart/store/{productId}', 'store')->name('store');
        Route::patch('/cart/update/{cartId}', 'update')->name('update');
        Route::delete('/cart/delete/{cartId}', 'delete')->name('delete');
    });
});


/* Admin */
Route::controller(AdminAuthController::class)->prefix('admin')->name('admin.')->group(function () {
    Route::middleware('redirectAdmin')->group(function () {
        Route::get('login', 'showLoginForm')->name('login');
        Route::post('login', 'login')->name('login.action');
    });
    Route::post('logout')->name('logout')->middleware('admin');
});

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::controller(AdminController::class)->name('admin.')->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    Route::controller(AdminProductController::class)->name('admin.')->group(function () {
        Route::get('/product/list', 'list')->name('product.list');
        Route::get('/product/add', 'add')->name('product.add');
        Route::post('/product/store', 'store')->name('product.store');
        Route::get('/product/edit/{id}', 'edit')->name('product.edit');
        Route::put('/product/update/{id}', 'update')->name('product.update');
        Route::delete('/product/delete/{id}', 'delete')->name('product.delete');
        Route::delete('/product/delete/image/{id}', 'deleteImage')->name('product.delete.image');
    });

});


require __DIR__.'/auth.php';

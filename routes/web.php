<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminBrandController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Customer\CustomerHomeController;
use App\Http\Controllers\Customer\CustomerCartController;
use App\Http\Controllers\Customer\CustomerCategoryController;
use App\Http\Controllers\Customer\CustomerOrderController;
use App\Http\Controllers\Customer\CustomerProductController;
use App\Http\Controllers\Customer\CustomerAccountController;
use App\Http\Controllers\Admin\AdminCustomerController;

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
// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::controller(CustomerHomeController::class)->group(function () {
    Route::get('/', 'home')->name('home');
});

Route::middleware('customer')->name('customer.')->group(function () {
    Route::middleware('session.guard:shop')->group(function () {

        Route::controller(CustomerAccountController::class)->prefix('account')->name('account.')->group(function () {
            Route::get('/edit', 'edit')->name('edit');
            Route::patch('/update', 'update')->name('update');
            Route::delete('/delete', 'destroy')->name('destroy');
            
            Route::get('/info', 'info')->name('info');
            Route::patch('/info/change-password', 'changePassword')->name('info.changePassword');
            Route::patch('/info/update-account-info', 'updateAccountInfo')->name('info.update');

            Route::get('/orders', 'orderList')->name('order.list');
            Route::get('/order/details/{orderId}', 'orderDetails')->name('order.details');
            Route::patch('/order/cancel/{orderId}', 'cancelOrder')->name('order.cancel');
            
            Route::prefix('address')->name('address.')->group(function () {
                Route::get('/', 'address')->name('index');
                Route::post('/address/store', 'storeAddress')->name('store');
                Route::patch('/address/update/{addressId}', 'updateAddress')->name('update');
                Route::delete('/address/delete/{addressId}', 'deleteAddress')->name('delete'); 
            });

            Route::get('/location', 'location')->name('location');
        });
    
        Route::controller(CustomerCartController::class)->prefix('cart')->name('cart.')->group(function () {
            Route::get('/list', 'list')->name('list');
            Route::post('/store/{productId}', 'store')->name('store');
            Route::patch('/update/{cartId}', 'update')->name('update');
            Route::delete('/delete/{cartId}', 'delete')->name('delete');
            Route::post('/order-again/{id}', 'orderAgain')->name('orderAgain');
        });
        
        Route::controller(CustomerCategoryController::class)->name('category.')->group(function () {
            Route::get('/category/{slug}', 'list')->name('list');
        });
    
        Route::controller(CustomerProductController::class)->name('product.')->group(function () {
            Route::get('/product/{slug}', 'details')->name('details');
        });
        
        Route::controller(CustomerOrderController::class)->prefix('order')->name('order.')->group(function () {
            Route::post('/store', 'store')->name('store');
            Route::get('/success', 'success')->name('success');
            Route::get('/cancel', 'cancel')->name('cancel');
        });
        
    });

});


/* Admin */
Route::controller(AdminAuthController::class)->prefix('admin')->name('admin.')->group(function () {
    Route::middleware('redirectAdmin')->group(function () {
        Route::get('login', 'showLoginForm')->name('login');
        Route::post('login', 'login')->name('login.action');
    });
    Route::post('logout', 'logout')->name('logout')->middleware('admin');
});

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::middleware('session.guard:admin')->group(function () {

        Route::controller(AdminDashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('dashboard');
        });

        Route::controller(AdminProductController::class)->prefix('product')->name('product.')->group(function () {
            Route::get('/list', 'list')->name('list');
            Route::get('/add', 'add')->name('add');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id?}', 'delete')->name('delete');
            Route::delete('/delete/image/{id}', 'deleteImage')->name('delete.image');
        });

        Route::controller(AdminCategoryController::class)->prefix('category')->name('category.')->group(function () {
            Route::get('/list', 'list')->name('list');
            Route::get('/add', 'add')->name('add');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/update/{id}', 'update')->name('update');
            // Route::post('/update/{id}', 'update')->name('update');
            Route::delete('/delete/image/{id}', 'deleteImage')->name('delete.image');
            Route::delete('/delete/{id}', 'delete')->name('delete');
        });

        Route::controller(AdminBrandController::class)->prefix('brand')->name('brand.')->group(function () {
            Route::get('/list', 'list')->name('list');
            Route::get('/add', 'add')->name('add');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'delete')->name('delete');
        });

        Route::controller(AdminOrderController::class)->prefix('order')->name('order.')->group(function () {
            Route::get('/list', 'list')->name('list');
            Route::get('/add', 'add')->name('add');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::patch('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'delete')->name('delete');
        });

        Route::controller(AdminCustomerController::class)->prefix('customer')->name('customer.')->group(function () {
            Route::get('/list', 'list')->name('list');
            Route::delete('/delete/{id}', 'delete')->name('delete');
        });



    });
});


require __DIR__.'/auth.php';

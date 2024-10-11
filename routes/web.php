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
use App\Http\Controllers\Admin\AdminPermissionController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\Auth\AdminNewPasswordController;
use App\Http\Controllers\Admin\Auth\AdminPasswordResetLinkController;

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

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::controller(CustomerHomeController::class)->group(function () {
    // Route::get('/', 'home')->name('home')->middleware('verified');
    Route::get('/', 'home')->name('home');
});

Route::name('customer.')->group(function () {
    /* Guest */
    Route::controller(CustomerCategoryController::class)->name('category.')->group(function () {
        Route::get('/category/{slug}', 'index')->name('index');
    });

    Route::controller(CustomerProductController::class)->name('product.')->group(function () {
        Route::get('/product/{slug}', 'details')->name('details');
    });
    /* Customer */
    Route::middleware(['customer', 'verified'])->group(function () {
        Route::middleware('session.guard:shop')->group(function () {
            
            Route::controller(CustomerAccountController::class)->prefix('account')->name('account.')->group(function () {
                Route::get('/edit', 'edit')->name('edit');
                Route::patch('/update', 'update')->name('update');
                Route::delete('/delete', 'destroy')->name('destroy');
                
                Route::get('/info', 'info')->name('info');
                Route::patch('/info/change-password', 'changePassword')->name('info.changePassword');
                Route::patch('/info/update-account-info', 'updateAccountInfo')->name('info.update');

                Route::get('/orders', 'orderIndex')->name('order.index');
                Route::get('/order/details/{orderId}', 'orderDetails')->name('order.details');
                Route::patch('/order/cancel/{orderId}', 'cancelOrder')->name('order.cancel');
                
                Route::prefix('address')->name('address.')->group(function () {
                    Route::get('/', 'address')->name('index');
                    Route::post('/address/store', 'storeAddress')->name('store');
                    Route::patch('/address/update/{addressId}', 'updateAddress')->name('update');
                    Route::delete('/address/delete/{addressId}', 'deleteAddress')->name('delete'); 
                });
                
            });
            // Route::resource('address', CustomerAccountController::class)->only(['index', 'store', 'update', 'destroy']);
            
            Route::controller(CustomerCartController::class)->prefix('cart')->name('cart.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store/{productId}', 'store')->name('store');
                Route::patch('/update/{cartId}', 'update')->name('update');
                Route::delete('/destroy/{cartId}', 'destroy')->name('destroy');
                Route::post('/order-again/{orderId}', 'orderAgain')->name('orderAgain');
            });

            // Route::resource('cart', CustomerCartController::class)->except(['create', 'edit']);
            // Route::post('/cart/order-again/{orderId}', [CustomerCartController::class, 'orderAgain'])->name('cart.orderAgain');
        
            Route::controller(CustomerOrderController::class)->prefix('order')->name('order.')->group(function () {
                Route::post('/store', 'store')->name('store');
                Route::get('/success', 'success')->name('success');
                Route::get('/cancel', 'cancel')->name('cancel');
            });
            
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

Route::controller(AdminPasswordResetLinkController::class)->prefix('admin')->name('admin.')->group(function () {
    Route::get('forgot-password', 'create')->name('password.request');
    Route::post('forgot-password', 'store')->name('password.email');
});

Route::controller(AdminNewPasswordController::class)->prefix('admin')->name('admin.')->group(function () {
    Route::get('reset-password/{token}', 'create')->name('password.reset');
    Route::post('reset-password', 'store')->name('password.store');
});

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::middleware('session.guard:admin')->group(function () {

        Route::resource('dashboard', AdminDashboardController::class)->only('index');

        Route::resource('product', AdminProductController::class)->except('destroy');
        Route::controller(AdminProductController::class)->prefix('product')->name('product.')->group(function () {
            Route::delete('/{id?}', 'destroy')->name('destroy');
            // Route::delete('/delete/image/{id}', 'deleteImage')->name('delete.image');
            Route::delete('/delete/image/{productId}/{imageId}', 'deleteImage')->name('delete.image');
        }); 

        Route::resource('category', AdminCategoryController::class)->except('destroy');
        Route::controller(AdminCategoryController::class)->prefix('category')->name('category.')->group(function () {
            Route::delete('/{id?}', 'destroy')->name('destroy');
            // Route::delete('/delete/image/{id}', 'deleteImage')->name('delete.image');
            Route::delete('/delete/image/{categoryId}/{imageId}', 'deleteImage')->name('delete.image');
        });
        
        Route::resource('brand', AdminBrandController::class)->except('destroy');
        Route::delete('brand/{id?}', [AdminBrandController::class, 'destroy'])->name('brand.destroy');
        
        Route::resource('order', AdminOrderController::class)->except(['create', 'destroy']);
        Route::delete('order/{id?}', [AdminOrderController::class, 'destroy'])->name('order.destroy');
        
        Route::resource('customer', AdminCustomerController::class)->except(['create', 'store', 'update', 'destroy']);
        Route::delete('customer/{id?}', [AdminCustomerController::class, 'destroy'])->name('customer.destroy');

        Route::resource('role', AdminRoleController::class)->except('destroy');
        Route::delete('role/{id?}', [AdminRoleController::class, 'destroy'])->name('role.destroy');

        Route::resource('permission', AdminPermissionController::class)->except('destroy');
        Route::delete('permission/{id?}', [AdminPermissionController::class, 'destroy'])->name('permission.destroy');

        Route::resource('user', AdminUserController::class)->except('destroy');
        Route::delete('user/{id?}', [AdminUserController::class, 'destroy'])->name('user.destroy');

        Route::resource('user', AdminUserController::class)->except('destroy');
        Route::delete('user/{id?}', [AdminUserController::class, 'destroy'])->name('user.destroy');

        // Route::prefix('user')->name('user.')->group(function () {
            Route::resource('profile', AdminProfileController::class)->only('index', 'update', 'destroy'); 
            Route::put('profile/password/update', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
        // });
        // Route::delete('user/{id?}', [AdminUserController::class, 'destroy'])->name('user.destroy');



    });
});


require __DIR__.'/auth.php';

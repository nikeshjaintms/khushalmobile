<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DealerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
})->name('login');


Route::post('/login', [App\Http\Controllers\UserController::class, 'login'])->name('admin.login.post');


Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/logout', [App\Http\Controllers\UserController::class, 'logout'])->name('admin.logout');


    Route::get('/', function () {
        return view('index');
    })->name('dashboard');

    Route::controller(App\Http\Controllers\BrandController::class)->group(function () {
        Route::get('/brand', 'index')->name('admin.brand.index');
        Route::get('/brand/create', 'create')->name('admin.brand.create');
        Route::post('/brand/store', 'store')->name('admin.brand.store');
        Route::get('/brand/edit/{id}', 'edit')->name('admin.brand.edit');
        Route::put('/brand/update/{id}', 'update')->name('admin.brand.update');
        Route::delete('/brand/delete/{id}', 'destroy')->name('admin.brand.delete');
    });

    Route::controller(\App\Http\Controllers\ProductController::class)->group(function () {
        Route::get('/product', 'index')->name('admin.product.index');
        Route::get('/product/create', 'create')->name('admin.product.create');
        Route::post('/product/store', 'store')->name('admin.product.store');
        Route::get('/product/edit/{id}', 'edit')->name('admin.product.edit');
        Route::put('/product/update/{id}', 'update')->name('admin.product.update');
        Route::delete('/product/delete/{id}', 'destroy')->name('admin.product.delete');

    });

    Route::prefix('customer')->controller(CustomerController::class)->group(function () {
        Route::get('/', 'index')->name('admin.customer.index');
        Route::get('create', 'create')->name('admin.customer.create');
        Route::post('store', 'store')->name('admin.customer.store');
        Route::get('edit/{id}', 'edit')->name('admin.customer.edit');
        Route::put('update/{id}', 'update')->name('admin.customer.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.customer.delete');
    });

    Route::prefix('dealer')->controller(DealerController::class)->group(function () {
        Route::get('/', 'index')->name('admin.dealer.index');
        Route::get('create', 'create')->name('admin.dealer.create');
        Route::post('store', 'store')->name('admin.dealer.store');
        Route::get('edit/{id}', 'edit')->name('admin.dealer.edit');
        Route::put('update/{id}', 'update')->name('admin.dealer.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.dealer.delete');
    });


});


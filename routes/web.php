<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\TransctionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DailyNoteController;
use App\Http\Controllers\DeductionController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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

    Route::get('/notes', [DashboardController::class, 'displaynotes'])->name('notes.index');
    Route::post('/notes', [DashboardController::class, 'store'])->name('notes.store');
    Route::delete('/notes/destroy/{id}', [DashboardController::class, 'destroy'])->name('notes.destroy');
    Route::get('/dashboard-data', [DashboardController::class, 'index'])->name('dashboard.data');
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
        Route::get('/get-products/{brand_id}', 'getProducts')->name('admin.product.getproducts');

        Route::get('/get-product-price/{id}', 'getPrice')->name('admin.product.getPrice');

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

    Route::prefix('purchase')->controller(PurchaseController::class)->group(function () {
        Route::get('/', 'index')->name('admin.purchase.index');
        Route::get('create', 'create')->name('admin.purchase.create');
        Route::post('store', 'store')->name('admin.purchase.store');
        Route::get('/{id}', 'show')->name('admin.purchase.show');
        Route::get('edit/{id}', 'edit')->name('admin.purchase.edit');
        Route::put('update/{id}', 'update')->name('admin.purchase.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.purchase.delete');
    });

    Route::prefix('transaction')->controller(TransctionController::class)->group(function () {
        Route::get('/', 'index')->name('admin.transaction.index');
        Route::get('create', 'create')->name('admin.transaction.create');
        Route::post('store', 'store')->name('admin.transaction.store');
        Route::get('/{id}', 'show')->name('admin.transaction.show');
        Route::get('edit/{id}', 'edit')->name('admin.transaction.edit');
        Route::put('update/{id}', 'update')->name('admin.transaction.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.transaction.delete');
    });
// web.php
    Route::get('/daily-notes', [DashboardController::class, 'display'])->name('daily-notes.index');
    Route::post('/daily-notes', [DashboardController::class, 'store'])->name('daily-note.store');

    Route::prefix('sale')->controller(SaleController::class)->group(function () {
        Route::get('/', 'index')->name('admin.sale.index');
        Route::get('create', 'create')->name('admin.sale.create');
        Route::post('store', 'store')->name('admin.sale.store');
        Route::get('edit/{id}', 'edit')->name('admin.sale.edit');
        Route::put('update/{id}', 'update')->name('admin.sale.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.sale.delete');
        Route::get('/{id}', 'show')->name('admin.sale.show');
        Route::get('/get-imeis/{product_id}', 'getImeis')->name('admin.sale.get-imeis');

    });
    Route::get('invoice-pdf/{id}', [InvoiceController::class, 'generatePDF'])->name('admin.invoice.index');

    Route::prefix('daily-notes')->controller(DailyNoteController::class)->group(function () {
        Route::get('/', 'index')->name('admin.daily-notes.index');
        Route::get('create', 'create')->name('admin.daily-notes.create');
        Route::post('store', 'store')->name('admin.daily-notes.store');
        Route::get('edit/{id}', 'edit')->name('admin.daily-notes.edit');
        Route::put('update/{id}', 'update')->name('admin.daily-notes.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.daily-notes.delete');
    });

    Route::prefix('deduction')->controller(DeductionController::class)->group(function (){
        Route::get('/', 'index')->name('admin.deduction.index');
        Route::get('create', 'create')->name('admin.deduction.create');
        Route::post('store', 'store')->name('admin.deduction.store');
        Route::get('edit/{id}', 'edit')->name('admin.deduction.edit');
        Route::put('update/{id}', 'update')->name('admin.deduction.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.deduction.delete');
        Route::post('finance-details','getFinanceDetails')->name('admin.customer.finance.details');
        Route::post('/admin/finance/deductions', 'getDeductions')->name('admin.finance.deductions');


    });

});


<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\financeMasterController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransctionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DailyNoteController;
use App\Http\Controllers\DeductionController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
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



Route::post('/login', [UserController::class, 'login'])->name('admin.login.post');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('index');
    })->name('dashboard');

    Route::get('/logout', [UserController::class, 'logout'])->name('admin.logout');

    Route::prefix('user')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('admin.user.index')->middleware('permission:index-user');
        Route::get('create', 'create')->name('admin.user.create')->middleware('permission:create-user');
        Route::post('store', 'store')->name('admin.user.store');
        Route::get('{id}', 'show')->name('admin.user.show')->middleware('permission:show-user');
        Route::get('/edit/{id}', 'edit')->name('admin.user.edit')->middleware('permission:edit-user');
        Route::put('/update/{id}', 'update')->name('admin.user.update');
        Route::delete('/delete/{id}', 'destroy')->name('admin.user.delete')->middleware('permission:delete-user');
    });

    Route::prefix('role')->controller(RoleController::class)->group(function () {
        Route::get('/', 'index')->name('admin.role.index')->middleware('permission:index-role');
        Route::get('create', 'create')->name('admin.role.create')->middleware('permission:create-role');
        Route::post('store', 'store')->name('admin.role.store');
        Route::get('edit/{id}', 'edit')->name('admin.role.edit')->middleware('permission:edit-role');
        Route::put('update/{id}', 'update')->name('admin.role.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.role.delete')->middleware('permission:delete-role');
    });

    Route::prefix('permission')->controller(PermissionController::class)->group(function () {
        Route::get('/', 'index')->name('admin.permission.index')->middleware('permission:index-permission');
        Route::get('create', 'create')->name('admin.permission.create')->middleware('permission:create-permission');
        Route::post('store', 'store')->name('admin.permission.store');
        Route::get('edit/{id}', 'edit')->name('admin.permission.edit')->middleware('permission:edit-permission');
        Route::put('update/{id}', 'update')->name('admin.permission.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.permission.delete')->middleware('permission:delete-permission');
    });

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/notes', 'displaynotes')->name('notes.index');
        //->middleware('permission:index-notes');
        Route::post('/notes', 'store')->name('notes.store');
        Route::delete('/notes/destroy/{id}', 'destroy')->name('notes.destroy');
        Route::get('/dashboard-data', 'index')->name('dashboard.data');
        Route::get('/daily-notes', 'display')->name('daily-notes.index');
        Route::post('/daily-notes', 'store')->name('daily-note.store');
    });

    Route::prefix('brand')->controller(BrandController::class)->group(function () {
        Route::get('/', 'index')->name('admin.brand.index')->middleware('permission:index-brand');
        Route::get('create', 'create')->name('admin.brand.create')->middleware('permission:create-brand');
        Route::post('store', 'store')->name('admin.brand.store');
        Route::get('edit/{id}', 'edit')->name('admin.brand.edit')->middleware('permission:edit-brand');
        Route::put('update/{id}', 'update')->name('admin.brand.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.brand.delete')->middleware('permission:delete-brand');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('get-products/{brand_id}', 'getProducts')->name('admin.product.getproducts');
        Route::get('/get-product-price/{id}', 'getPrice')->name('admin.product.getPrice');

        Route::prefix('product')->group(function () {
            Route::get('/', 'index')->name('admin.product.index')->middleware('permission:index-product');
            Route::get('create', 'create')->name('admin.product.create')->middleware('permission:create-product');
            Route::post('store', 'store')->name('admin.product.store');
            Route::get('edit/{id}', 'edit')->name('admin.product.edit')->middleware('permission:edit-product');
            Route::put('update/{id}', 'update')->name('admin.product.update');
            Route::delete('delete/{id}', 'destroy')->name('admin.product.delete')->middleware('permission:delete-product');
        });
    });

    Route::prefix('customer')->controller(CustomerController::class)->group(function () {
        Route::get('/', 'index')->name('admin.customer.index')->middleware('permission:index-customer');
        Route::get('create', 'create')->name('admin.customer.create')->middleware('permission:create-customer');
        Route::post('store', 'store')->name('admin.customer.store');
        Route::get('edit/{id}', 'edit')->name('admin.customer.edit')->middleware('permission:create-brand');
        Route::put('update/{id}', 'update')->name('admin.customer.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.customer.delete')->middleware('permission:delete-customer');
    });

    Route::prefix('dealer')->controller(DealerController::class)->group(function () {
        Route::get('/', 'index')->name('admin.dealer.index')->middleware('permission:index-dealer');
        Route::get('create', 'create')->name('admin.dealer.create')->middleware('permission:create-dealer');
        Route::post('store', 'store')->name('admin.dealer.store');
        Route::get('edit/{id}', 'edit')->name('admin.dealer.edit');
            //->middleware('permission:edit-dealer');
        Route::put('update/{id}', 'update')->name('admin.dealer.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.dealer.delete')->middleware('permission:delete-dealer');
    });

    Route::prefix('purchase')->controller(PurchaseController::class)->group(function () {
        Route::get('/', 'index')->name('admin.purchase.index')->middleware('permission:index-purchase');
        Route::get('create', 'create')->name('admin.purchase.create')->middleware('permission:create-purchase');
        Route::post('store', 'store')->name('admin.purchase.store');
        Route::get('/{id}', 'show')->name('admin.purchase.show')->middleware('permission:show-purchase');
        Route::get('edit/{id}', 'edit')->name('admin.purchase.edit')->middleware('permission:edit-purchase');
        Route::put('update/{id}', 'update')->name('admin.purchase.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.purchase.delete')->middleware('permission:delete-purchase');
    });

    Route::prefix('transaction')->controller(TransctionController::class)->group(function () {
        Route::get('/', 'index')->name('admin.transaction.index')->middleware('permission:index-transaction');
        Route::get('create', 'create')->name('admin.transaction.create');
        Route::post('store', 'store')->name('admin.transaction.store');
        Route::get('/{id}', 'show')->name('admin.transaction.show');
        Route::get('edit/{id}', 'edit')->name('admin.transaction.edit');
        Route::put('update/{id}', 'update')->name('admin.transaction.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.transaction.delete');
    });

    Route::prefix('sale')->controller(SaleController::class)->group(function () {
        Route::get('/', 'index')->name('admin.sale.index')->middleware('permission:index-sale');
        Route::get('create', 'create')->name('admin.sale.create')->middleware('permission:create-sale');
        Route::post('store', 'store')->name('admin.sale.store');
        Route::get('edit/{id}', 'edit')->name('admin.sale.edit')->middleware('permission:edit-sale');
        Route::put('update/{id}', 'update')->name('admin.sale.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.sale.delete')->middleware('permission:delete-sale');
        Route::get('/{id}', 'show')->name('admin.sale.show')->middleware('permission:delete-sale');
        Route::get('/get-imeis/{product_id}', 'getImeis')->name('admin.sale.get-imeis');

    });

    Route::get('invoice-pdf/{id}', [InvoiceController::class, 'generatePDF'])->name('admin.invoice.index');

    Route::prefix('daily-notes')->controller(DailyNoteController::class)->group(function () {
        Route::get('/', 'index')->name('admin.daily-notes.index')->middleware('permission:index-notes');
        Route::get('create', 'create')->name('admin.daily-notes.create');
        Route::post('store', 'store')->name('admin.daily-notes.store');
        Route::get('edit/{id}', 'edit')->name('admin.daily-notes.edit');
        Route::put('update/{id}', 'update')->name('admin.daily-notes.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.daily-notes.delete');
    });

    Route::prefix('deduction')->controller(DeductionController::class)->group(function () {
        Route::get('/', 'index')->name('admin.deduction.index')->middleware('permission:index-deduction');
        Route::get('create', 'create')->name('admin.deduction.create')->middleware('permission:create-deduction');
        Route::post('store', 'store')->name('admin.deduction.store');
        Route::get('edit/{id}', 'edit')->name('admin.deduction.edit')->middleware('permission:edit-deduction');
        Route::put('update/{id}', 'update')->name('admin.deduction.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.deduction.delete')->middleware('permission:delete-deduction');
        Route::post('finance-details', 'getFinanceDetails')->name('admin.customer.finance.details');
        Route::post('/admin/finance/deductions', 'getDeductions')->name('admin.finance.deductions');
    });

    Route::prefix('financeMaster')->controller(financeMasterController::class)->group(function () {
        Route::get('/', 'index')->name('admin.financeMaster.index')->middleware('permission:index-finance-master');
        Route::get('create', 'create')->name('admin.financeMaster.create');
        Route::post('store', 'store')->name('admin.financeMaster.store');
        Route::get('edit/{id}', 'edit')->name('admin.financeMaster.edit');
        Route::put('update/{id}', 'update')->name('admin.financeMaster.update');
        Route::delete('delete/{id}', 'destroy')->name('admin.financeMaster.delete');
    });
});

<?php

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


Route::prefix('admin')->middleware('auth')->group(function(){
Route::get('/logout', [App\Http\Controllers\UserController::class, 'logout'])->name('admin.logout');



Route::get('/', function () {
    return view('
    index');
})->name('dashboard');

    Route::controller(App\Http\Controllers\BrandController::class)->group(function(){
        Route::get('/brand', 'index')->name('admin.brand.index');
        Route::get('/brand/create', 'create')->name('admin.brand.create');
        Route::post('/brand/store', 'store')->name('admin.brand.store');
        Route::get('/brand/edit/{id}', 'edit')->name('admin.brand.edit');
        Route::put('/brand/update/{id}', 'update')->name('admin.brand.update');
        Route::delete('/brand/delete/{id}', 'destroy')->name('admin.brand.delete');
    });

});


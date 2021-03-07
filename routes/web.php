<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/',[ProductController::class,'productCreate'])->name('productCreate');;
Route::post('productData',[ProductController::class,'productData'])->name('productData');
Route::get('products/list', [ProductController::class, 'getProducts'])->name('products.list');
Route::get('product', [ProductController::class, 'index'])->name('product');
Route::post('productUpdate', [ProductController::class, 'productUpdate'])->name('productUpdate');
Route::post('productDelete', [ProductController::class, 'productDelete'])->name('productDelete');
Route::post('productImport', [ProductController::class, 'productImport'])->name('productImport');
Route::get('productExport', [ProductController::class, 'productExport'])->name('productExport');
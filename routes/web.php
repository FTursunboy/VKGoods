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

Route::get('/', [\App\Http\Controllers\GoodController::class, 'index']);
Route::get('/yml', [\App\Http\Controllers\GoodController::class, 'download'])->name('download.yml');
Route::post('/update/{good}', [\App\Http\Controllers\GoodController::class, 'update'])->name('update');
Route::get('get/goods/service', [\App\Services\GetGoods::class, 'connect']);



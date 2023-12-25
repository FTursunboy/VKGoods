<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('get/goods', [\App\Http\Controllers\ConnectVK::class, 'connect']);
Route::get('get/goods/service', [\App\Services\GetGoods::class, 'connect']);
Route::get('get/goods/return', [\App\Services\GetGoods::class, 'returnGoods']);
Route::get('change/avail', [\App\Services\GetGoods::class, 'returnGoods']);

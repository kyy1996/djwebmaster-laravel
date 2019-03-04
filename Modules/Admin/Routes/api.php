<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:token')->get('/web', function (Request $request) {
//    return $request->user();
//});


Illuminate\Support\Facades\Route::middleware('web')->prefix('/ajax')->group(function () {
    Illuminate\Support\Facades\Route::prefix('/admin')->group(\App\Common\Util::commonRouteBindCallback('Admin'));
});

Illuminate\Support\Facades\Route::middleware('web')->prefix('/page')->group(function () {
    Illuminate\Support\Facades\Route::prefix('/admin')->group(\App\Common\Util::commonRouteBindCallback('Admin'));
});

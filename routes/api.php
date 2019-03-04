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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::get('article/index', 'Article\\ArticleController@getIndex');
Illuminate\Support\Facades\Route::middleware('web')->prefix('/ajax')->group(function () {
    Route::prefix('/common')->group(function () {
        Route::prefix('/auth')->group(function () {
            \Illuminate\Support\Facades\Auth::routes([
                'verify' => true,
            ]);
        });
    });
    Illuminate\Support\Facades\Route::prefix('/common')->group(\App\Common\Util::commonRouteBindCallback());
});

Illuminate\Support\Facades\Route::middleware('web')->prefix('/page')->group(function () {
    Route::prefix('/common')->group(function () {
        Route::prefix('/auth')->group(function () {
            \Illuminate\Support\Facades\Auth::routes([
                'verify' => true,
            ]);
        });
    });
    Illuminate\Support\Facades\Route::prefix('/common')->group(\App\Common\Util::commonRouteBindCallback());
});

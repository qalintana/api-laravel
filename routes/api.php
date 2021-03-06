<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->namespace('Api')->group(function () {

    Route::name('realstate.')->group(function () {
        Route::resource('real-states', 'RealStateController');
    });

    Route::name('category.')->group(function () {
        Route::get('categories/{id}/real-state', 'CategoryController@realStates');
        Route::resource('categories', 'CategoryController');
    });

    Route::name('users.')->group(function () {
        Route::resource('users', 'UserController');
    });

    Route::name('photos.')->prefix('photos')->group(function () {
        Route::delete('/{id}', 'RealStatePhotoController@removePhoto')->name('delete');
        Route::put('/set-thumb/{photoId}/{realStateId}', 'RealStatePhotoController@setThumb')->name('update');
    });
});

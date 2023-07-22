<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\EnsureUserHasRole;

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
Route::middleware('auth:sanctum')->get('user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {
    Route::get('/get/nft/{id}', 'NftController@getToken');
    Route::get('/get/history/action/label/{id}', 'NftController@getHistoryActionLabel');
    Route::get('/get/nft/status/label/{id}', 'NftController@getNFTStatusLabel');

    Route::post('/client/register', 'RegisterController@register');
    Route::post('/client/login', 'LoginController@login');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::post('/admin/add/history', 'NftController@addHistory');
        
        Route::post('/client/logout', 'LoginController@logout');
        Route::post('/client/profile/edit-profile', 'ProfileController@updateProfile');
        Route::post('/client/profile/edit-password', 'ProfileController@updatePassword');

        Route::post('/client/add/wallets-address', 'NftController@addWalletsAddress');
        Route::post('/client/get/wallets-address', 'NftController@getWalletsAddress');
        Route::post('/client/get/user-by-address', 'NftController@getUserByAddress');
    });

    Route::post('/admin/login', 'AdminLoginController@login');

    Route::middleware(['auth:sanctum', EnsureUserHasRole::class])->group(function () {
        Route::get('/admin', function (Request $request) {
            return $request->user();
        });
        Route::post('/admin/logout', 'AdminLoginController@logout');

        Route::post('/admin/mint', 'NftController@mint');
        Route::get('/admin/get/history', 'NftController@getHistories');
    });
});
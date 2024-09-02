<?php

use Illuminate\Support\Facades\Route;
use Towoju5\SmartMetaManager\SmartMetaManager;

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

Route::group(['prefix' => 'api/meta'], function () {
    Route::prefix('{model}/{id}')->group(function () {
        Route::get('/', [SmartMetaManager::class, 'index']);
        Route::post('/', [SmartMetaManager::class, 'store']);
        Route::get('{key}', [SmartMetaManager::class, 'show']);
        Route::put('{key}', [SmartMetaManager::class, 'update']);
        Route::delete('{key}', [SmartMetaManager::class, 'destroy']);
    });

    Route::get('user-meta', [SmartMetaManager::class, 'getUserMeta']);
});

<?php

use Illuminate\Support\Facades\Route;
use Towoju5\SmartMetaManager\AuthMiddleware;
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

Route::group(['prefix' => 'api/meta', 'middleware' => [AuthMiddleware::class, 'auth:' . config('meta_models.auth_guard')]], function () {
    Route::get('{model}', [SmartMetaManager::class, 'getModelMeta']);
    Route::post('{model}', [SmartMetaManager::class, 'setMeta']);
    Route::get('{model}/search', [SmartMetaManager::class, 'searchMeta']);
    Route::get('user/all', [SmartMetaManager::class, 'getAllMeta']);
    Route::get('{model}/{key}', [SmartMetaManager::class, 'getMeta']);
    Route::put('{model}/{key}', [SmartMetaManager::class, 'updateMeta']);
    Route::delete('{model}/{key}', [SmartMetaManager::class, 'deleteMeta']);
    Route::post('{model}/check', [SmartMetaManager::class, 'checkMetaKeyValue']);
});

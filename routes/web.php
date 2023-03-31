<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\SubAffiliateController;


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


Route::get('/',                     [AffiliateController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Start Affiliate Area
|--------------------------------------------------------------------------
*/

Route::prefix('affiliate')->name('affiliate.')->group(function () {
    // Route::get('/search/{items?}', [AffiliateController::class, 'search'])->name('search');
    Route::post('store',                [AffiliateController::class, 'store'])->name('store');
    Route::put('update',                [AffiliateController::class, 'update'])->name('update');
    Route::get('status/{id}',           [AffiliateController::class, 'status'])->name('status');
    Route::get('edit/{id}',             [AffiliateController::class, 'edit'])->name('edit');
    Route::get('delete/{id}',           [AffiliateController::class, 'destroy'])->name('delete');
    Route::get('permanent-delete/{id}', [AffiliateController::class, 'permanentDelete'])->name('permanent-delete');
    Route::get('restore/{id}',          [AffiliateController::class, 'restore'])->name('restore');
    Route::get('{items?}',              [AffiliateController::class, 'index'])->name('index');
});

/*
|--------------------------------------------------------------------------
| Start Sub Affiliate Area
|--------------------------------------------------------------------------
*/

Route::prefix('sub-affiliate')->name('sub-affiliate.')->group(function () {
    // Route::get('/search/{items?}', [SubAffiliateController::class, 'search'])->name('search');
    Route::post('store',                [SubAffiliateController::class, 'store'])->name('store');
    Route::put('update',                [SubAffiliateController::class, 'update'])->name('update');
    Route::get('add/{id}',              [SubAffiliateController::class, 'add'])->name('add');
    Route::get('status/{id}',           [SubAffiliateController::class, 'status'])->name('status');
    Route::get('edit/{id}',             [SubAffiliateController::class, 'edit'])->name('edit');
    Route::get('delete/{id}',           [SubAffiliateController::class, 'destroy'])->name('delete');
    Route::get('permanent-delete/{id}', [SubAffiliateController::class, 'permanentDelete'])->name('permanent-delete');
    Route::get('restore/{id}',          [SubAffiliateController::class, 'restore'])->name('restore');
    Route::get('{items?}',              [SubAffiliateController::class, 'index'])->name('index');
});

/*
|--------------------------------------------------------------------------
| Start Api Key Area
|--------------------------------------------------------------------------
*/

Route::prefix('api-key')->name('api-key.')->group(function () {
    // Route::get('/search/{items?}', [ApiKeyController::class, 'search'])->name('search');
    Route::post('store',                [ApiKeyController::class, 'store'])->name('store');
    Route::get('status/{id}',           [ApiKeyController::class, 'status'])->name('status');
    // Route::get('edit/{id}',             [ApiKeyController::class, 'edit'])->name('edit');
    Route::get('delete/{id}',           [ApiKeyController::class, 'destroy'])->name('delete');
    Route::get('permanent-delete/{id}', [ApiKeyController::class, 'permanentDelete'])->name('permanent-delete');
    Route::get('restore/{id}',          [ApiKeyController::class, 'restore'])->name('restore');
    Route::get('/{id}/{items?}',        [ApiKeyController::class, 'index'])->name('index');
});
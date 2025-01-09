<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {

// });

Route::get('categories/trashed', [CategoryController::class, 'getTrashed']);
Route::get('categories/restore/{id}', [CategoryController::class, 'restore']);
Route::apiResource('categories', CategoryController::class)->names('categories');

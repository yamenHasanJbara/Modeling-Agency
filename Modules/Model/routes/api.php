<?php

use Illuminate\Support\Facades\Route;
use Modules\Model\Http\Controllers\ModelController;

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

Route::get('models/trashed', [ModelController::class, 'getTrashed']);
Route::get('models/restore/{id}', [ModelController::class, 'restore']);
Route::post('models/update/{id}', [ModelController::class, 'update']);
Route::apiResource('models', ModelController::class)->names('models');

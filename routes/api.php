<?php

use App\Http\Controllers\Api\InventoryController;
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

Route::get('v1/inventory/items/{snackbarUuid}', [InventoryController::class, 'getInventoryItems ']);
Route::post('v1/inventory/items/add', [InventoryController::class, 'addInventoryItem']);

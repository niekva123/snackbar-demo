<?php

use App\Actions\Inventory\CreateItem;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\OrderController;
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

Route::get('v1/inventory/{snackbarUuid}/items', [InventoryController::class, 'getInventoryItems']);
Route::post('v1/inventory/{snackbar}/items/create', [CreateItem::class, 'asController']);
Route::post('v1/inventory/{snackbarUuid}/items/{itemUuid}/edit', [InventoryController::class, 'changeInventoryItem']);
Route::delete('v1/inventory/{snackbarUuid}/items/{itemUuid}', [InventoryController::class, 'removeInventoryItem']);

Route::get('v1/order/{orderUuid}', [OrderController::class, 'getOrder']);
Route::post('v1/snackbar/{snackbarUuid}/order/create', [OrderController::class, 'createOrder']);
Route::post('v1/order/{orderUuid}/orderitem/add', [OrderController::class, 'addOrderItem']);
Route::post('v1/order/{orderUuid}/orderitem/{orderItemUuid}', [OrderController::class, 'changeOrderItemAmount']);
Route::delete('v1/order/{orderUuid}/orderitem/{orderItemUuid}', [OrderController::class, 'removeOrderItem']);

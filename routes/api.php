<?php

use App\Http\Controllers\Method\MethodBrowseController;
use App\Http\Controllers\Method\MethodController;
use App\Http\Controllers\Payment\PaymentBrowseController;
use App\Http\Controllers\Payment\PaymentController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('', function () {
    return 'Beep Beep server is Online Jenkins';
});

Route::get('list/payment', [PaymentBrowseController::class, 'List']);
Route::get('list/payment/method', [MethodBrowseController::class, 'List']);
Route::get('list/payment/method/{uuid}', [MethodBrowseController::class, 'Detail']);


Route::prefix('payment')->group(function () {
    Route::get('retail', [PaymentController::class, 'Retail'])->middleware([
        'Create.Retail'
    ]);

    Route::get('va', [PaymentController::class, 'VirtualAccount'])->middleware([
        'Create.Va'
    ]);
});

Route::prefix('method')->group(function () {
    Route::get('static/va', [MethodController::class, 'StaticVirtualAccount'])->middleware([
        'Create.Static.Va'
    ]);
});

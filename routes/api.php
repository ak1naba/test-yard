<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthorizationController::class, 'register']);
Route::post('/login', [AuthorizationController::class, 'login']);

Route::group(['middleware'=>'auth:sanctum'], function (){
    Route::post('/logout', [AuthorizationController::class, 'logout']);

    Route::get('/get-balance',[WalletController::class, 'getBalance']);
    Route::post('/replenishment-balance',[WalletController::class, 'replenishWallet']);
    Route::get('/get-history',[WalletController::class, 'getOperationsWallet']);

    Route::post('/transfer',[TransactionController::class, 'transfer']);
});


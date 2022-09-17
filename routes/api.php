<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;


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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/infouser', [AuthController::class, 'infoUser'])->middleware('auth:sanctum');

Route::get('get-customers', [CustomerController::class, 'getCustomers'])->name('api-customers-get')->middleware('auth:sanctum');;

Route::post('create-customer', [CustomerController::class, 'store'])->name('api-customers-create')->middleware('auth:sanctum');

Route::put('update-customer/{id}', [CustomerController::class, 'update'])->name('api-customers-update')->middleware('auth:sanctum');

Route::delete('delete-customer/{id}', [CustomerController::class, 'deleteCustomer'])->name('api-customers-delete')->middleware('auth:sanctum');
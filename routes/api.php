<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeController;

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

Route::post('/login', [EmployeeController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/employees/deleted', [EmployeeController::class, 'trashed']);
    Route::post('employees/restore/{id}', [EmployeeController::class, 'restore']);
    Route::post('employees/bulk-delete', [EmployeeController::class, 'bulkDelete']);
    
    Route::apiResource('employees', EmployeeController::class);
});

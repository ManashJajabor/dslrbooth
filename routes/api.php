<?php

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

Route::get('trigger',[\App\Http\Controllers\RazorpayController::class,'trigger'])->name('trigger');
Route::get('trigger-b',[\App\Http\Controllers\RazorpayController::class,'triggerB'])->name('trigger-b');

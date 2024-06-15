<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[\App\Http\Controllers\RazorpayController::class,'home'])->name('home');

Route::post('razorpay-payment',[\App\Http\Controllers\RazorpayController::class,'store'])->name('razorpay.payment.store');
Route::get('success',[\App\Http\Controllers\RazorpayController::class,'success'])->name('success');
Route::post('print',[\App\Http\Controllers\RazorpayController::class,'print'])->name('print');


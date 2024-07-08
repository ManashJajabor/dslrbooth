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

//Route::post('razorpay-payment',[\App\Http\Controllers\RazorpayController::class,'store'])->name('razorpay.payment.store');
Route::post('success',[\App\Http\Controllers\RazorpayController::class,'success'])->name('success');
Route::post('print',[\App\Http\Controllers\RazorpayController::class,'print'])->name('print');
Route::get('hitdslr',[\App\Http\Controllers\RazorpayController::class,'hitdslr'])->name('hitdslr');


Route::post('start',[\App\Http\Controllers\RazorpayController::class,'start'])->name('razorpay.payment.start');
Route::post('payment-check',[\App\Http\Controllers\RazorpayController::class,'payCheck'])->name('payment-check');
Route::get('payment-check1',[\App\Http\Controllers\RazorpayController::class,'payCheck1'])->name('payment-check1');


Route::get('close',[\App\Http\Controllers\RazorpayController::class,'close'])->name('close');


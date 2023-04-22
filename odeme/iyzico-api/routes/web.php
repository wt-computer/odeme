<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', [\App\Http\Controllers\PaymentController::class, 'pay'])->name('payment');
Route::post('/pay', [\App\Http\Controllers\PaymentController::class, 'pay']);
Route::get('/success', function (){return view('success');});
Route::get('/error', function (){return view('error');});
//Route::get('/payment', function (){return view('payment');});
Route::match(['get', 'post'], '/payment', [\App\Http\Controllers\PaymentController::class, 'createPaymentForm'])->name('payment.create');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\paycometController;
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

Route::get('/', function () {
    return view('welcome');
});


// Ruta para mostrar la página de prueba (index)
Route::get('/payment/test', [PaycometController::class, 'index'])->name('payment.test.index');

// Ruta para manejar el callback del pago
Route::post('/payment/callback', [PaycometController::class, 'handleCallback'])->name('payment.callback');

// Ruta para manejar el callback SOAP del pago
Route::post('/payment/callback-soap', [PaycometController::class, 'handleCallbackSoap'])->name('payment.callback.soap');

// Ruta para mostrar el resultado de la autenticación de Paycomet
Route::get('/payment/result', [PaycometController::class, 'authenticationResult'])->name('payment.result');


//
//
//Route::get('/prueba', [paycometController::class, 'index'])->name('index');
//Route::post('/prueba/callback', [paycometController::class, 'callback'])->name('callback.prueba');
//Route::post('/prueba/callbackSoap', [paycometController::class, 'callbackSoap'])->name('callbackSoap.prueba');
//
//Route::get('operation/paycomet/result', [paycometController::class, 'paycometAuthenticationResult'])->name('paycomet.result');
//

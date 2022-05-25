<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\MitraMobilController;
use App\Http\Controllers\Api\MobilController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\ShiftController;
use App\Http\Controllers\Api\DetailShiftController;
use App\Http\Controllers\Api\ReservasiMobilController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('login','App\\Http\\Controllers\\Api\\AuthController@login');
Route::resource('customer',CustomerController::class);
Route::post('customer/{id}','App\\Http\\Controllers\\Api\\CustomerController@update');
Route::post('ubahpassword/{id}','App\\Http\\Controllers\\Api\\CustomerController@updatePassword');
Route::resource('driver',DriverController::class);
Route::post('driver/{id}','App\\Http\\Controllers\\Api\\DriverController@update');
Route::get('availableDrivers','App\\Http\\Controllers\\Api\\DriverController@showAvailableDriver');
Route::resource('pegawai',PegawaiController::class);
Route::post('pegawai/{id}','App\\Http\\Controllers\\Api\\PegawaiController@update');
Route::post('ubahpasswordpegawai/{id}','App\\Http\\Controllers\\Api\\PegawaiController@updatePassword');
Route::get('showWithoutManager','App\\Http\\Controllers\\Api\\PegawaiController@showWithoutManager');
Route::resource('mitra',MitraMobilController::class);
Route::post('mitra/{id}','App\\Http\\Controllers\\Api\\MitraMobilController@update');
Route::resource('mobil',MobilController::class);
Route::post('mobil/{id}','App\\Http\\Controllers\\Api\\MobilController@update');
Route::get('catalog','App\\Http\\Controllers\\Api\\MobilController@showTersedia');
Route::get('contract','App\\Http\\Controllers\\Api\\MobilController@showContractLimit');
Route::resource('promo',PromoController::class);
Route::post('promo/{id}','App\\Http\\Controllers\\Api\\PromoController@update');
Route::get('promoByCust/{id_customer}','App\\Http\\Controllers\\Api\\PromoController@showByIdCustomer');
Route::resource('shift',ShiftController::class);
Route::post('shift/{id}','App\\Http\\Controllers\\Api\\ShiftController@update');
Route::resource('jadwal',DetailShiftController::class);
Route::post('jadwal/{id}','App\\Http\\Controllers\\Api\\DetailShiftController@update');
Route::resource('reservasi',ReservasiMobilController::class);
Route::post('reservasi/{id}','App\\Http\\Controllers\\Api\\ReservasiMobilController@update');
Route::get('reservasiCustomer/{id_customer}','App\\Http\\Controllers\\Api\\ReservasiMobilController@showCustomer');
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

Route::get('/', function () {
    return redirect()->route('login');
});
//Route::get('/login', function () {
//    return view('auth.login');
//});

//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/history/data/{start_datetime}/{end_datetime}', [App\Http\Controllers\HomeController::class, 'display_data_history'])->name('display_data_history');
Route::get('/history/data', [App\Http\Controllers\HomeController::class, 'historyData'])->name('display_data_history');
Route::get('/live/data', [App\Http\Controllers\HomeController::class, 'liveData'])->name('display_data_live');
Route::get('/logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('logout');
Route::get('/login', [App\Http\Controllers\LoginController::class, 'index'])->name('login');
Route::post('/userCheck', [App\Http\Controllers\LoginController::class, 'userCheck'])->name('userCheck');

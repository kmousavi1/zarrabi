<?php

use App\Http\Controllers\HomeController;
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

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/chart', [\App\Http\Controllers\ChartController::class, 'index'])->name('chart');
Route::get('/history/data/{startDate}/{endDate}', [HomeController::class, 'historyData_'])->name('historyData_');
Route::get('/history/data', [HomeController::class, 'historyData'])->name('historyData');
Route::get('/live/data', [HomeController::class, 'liveData'])->name('liveData');
Route::get('/live/latest-data', [HomeController::class, 'getLatestData'])->name('liveData');


Route::get('/logout', [HomeController::class, 'logout'])->name('logout');
Route::get('/login', [App\Http\Controllers\LoginController::class, 'index'])->name('login');
Route::post('/userCheck', [App\Http\Controllers\LoginController::class, 'userCheck'])->name('userCheck');

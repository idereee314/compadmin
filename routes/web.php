<?php

use Illuminate\Support\Facades\Route;
use Auth\LoginController;

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

Route::get('/clearcache', function()
{
    Cache::flush();
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    return "Cache cleared".date("D M d, Y G:i a");
});

Route::get('/phpinfo', function()
{
    phpinfo();
});

Route::get('login', [LoginController::class, 'showLogin'])->name('show.login');
Route::post('login', [LoginController::class, 'doLogin'])->name('do.login');

Route::get('/', function () {
    return view('welcome');
});

<?php

use Illuminate\Support\Facades\Route;
use Auth\LoginController;
use dashboard\HomeController;
use core\CompadUserController;
use member\MemberController;
use event\EventRegistrationController;

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

Route::group([
    'prefix' => '',
    'middleware' => 'auth'
], function(){
    //Home
    Route::get('/home', [HomeController::class, 'index']);

    //User
    Route::resource('/compaduser', 'core\CompadUserController', ['names' => 'compaduser']);
    Route::any('/compaduser/data/list', [CompadUserController::class, 'getDatatableList'])->name('compaduser.data.list');

    //Member
    Route::resource('/member', 'member\MemberController', ['names' => 'member']);
    Route::any('/member/data/list', [MemberController::class, 'getDatatableList'])->name('member.data.list');

    //Event
    Route::resource('/event/registration', 'event\EventRegistrationController', ['names' => 'event.registration']);
    Route::any('/event/registration/data/list', [EventRegistrationController::class, 'getDatatableList'])->name('event.registration.data.list');
});


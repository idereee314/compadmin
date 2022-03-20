<?php

use Illuminate\Support\Facades\Route;
use Auth\LoginController;
use dashboard\HomeController;
use core\CompadUserController;
use member\MemberController;
use academy\AcademyController;
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
    Route::resource('/user', 'core\CompadUserController', ['names' => 'user']);
    Route::any('/user/data/list', [CompadUserController::class, 'getDatatableList'])->name('user.data.list');
    Route::get('/user/search/data', 'core\CompadUserController@searchUser')->name('user.search');

    //Member
    Route::resource('/member', 'member\MemberController', ['names' => 'member']);
    Route::any('/member/data/list', 'member\MemberController@getDatatableList')->name('member.data.list');
    Route::get('/member/show/image/profile/{member}', 'member\MemberController@showImageProfile')->name('member.show.imageprofile');
    Route::get('/member/show/image/id/{member}', 'member\MemberController@showImageId')->name('member.show.imageid');
    Route::get('/member/search/data', 'member\MemberController@searchMember')->name('member.search');
    Route::get('/member/create/connect/user/{member}', 'member\MemberController@createConnectUser')->name('create.connect.user');
    Route::post('/member/update/connect/user/{member}', 'member\MemberController@updateConnectUser')->name('update.connect.user');

    //Event
    Route::resource('/event/registration', 'event\EventRegistrationController', ['names' => 'event.registration']);
    Route::any('/event/registration/data/list', 'event\EventRegistrationController@getDatatableList')->name('event.registration.data.list');
    
    //Entry
    Route::resource('/event/entry', 'reference\EventEntryController', ['names' => 'event.entry']);
    Route::any('/event/entry/data/list', 'reference\EventEntryController@getDatatableList')->name('event.entry.data.list');
    Route::post('/event/entry/by/event', 'reference\EventEntryController@getEntryByEventId')->name('event.entry.by.event');

    //Entry Age
    Route::resource('/event/entry/age', 'reference\EventEntryAgeController', ['names' => 'event.entry.age']);
    Route::any('/event/entry/age/data/list', 'reference\EventEntryAgeController@getDatatableList')->name('event.entry.age.data.list');
    Route::post('/event/entry/age/by/entry', 'reference\EventEntryAgeController@getEntryAgeByEntryId')->name('event.entry.age.by.entry');

    //Entry Belt
    Route::resource('/event/entry/belt', 'reference\EventEntryBeltController', ['names' => 'event.entry.belt']);
    Route::any('/event/entry/belt/data/list', 'reference\EventEntryBeltController@getDatatableList')->name('event.entry.belt.data.list');
    Route::post('/event/entry/belt/by/entry', 'reference\EventEntryBeltController@getEntryBeltByEntryId')->name('event.entry.belt.by.entry');

    //Entry Weight
    Route::resource('/event/entry/weight', 'reference\EventEntryWeightController', ['names' => 'event.entry.weight']);
    Route::any('/event/entry/weight/data/list', 'reference\EventEntryWeightController@getDatatableList')->name('event.entry.weight.data.list');
    Route::post('/event/entry/weight/by/entry', 'reference\EventEntryWeightController@getEntryWeightByEntryId')->name('event.entry.weight.by.entry');
    Route::post('/event/entry/weight/by/age', 'reference\EventEntryWeightController@getEntryWeightByAgeId')->name('event.entry.weight.by.age');

    //Academy
    Route::resource('/academy', 'academy\AcademyController', ['names' => 'academy']);
    Route::any('/academy/data/list', 'academy\AcademyController@getDatatableList')->name('academy.data.list');
});


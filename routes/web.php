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

Route::get('/', [LoginController::class, 'showLogin'])->name('show.login');
Route::post('login', [LoginController::class, 'doLogin'])->name('do.login');

Route::get('logout', [LoginController::class, 'doLogout'])->name('system.logout');

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/bracket/{eventId}/{entryId}/{entryAgeId}/{entryBeltId}/{entryWeightId}', 'event\EventRegistrationController@bracketShow')->name('event.registration.bracket.show');
Route::get('/bracket/print/{eventId}/{entryId}/{entryAgeId}/{entryBeltId}/{entryWeightId}', 'event\EventRegistrationController@bracketPrint')->name('event.registration.bracket.print');

Route::group([
    'prefix' => '',
    'middleware' => 'auth'
], function(){
    //Home
    Route::get('/home', [HomeController::class, 'index']);

    //User
    Route::resource('/user', 'core\CompadUserController', ['names' => 'user']);
    Route::get('/system/um/user/change/password', 'core\CompadUserController@changePassword')->name('user.change.my.password');
    Route::post('/system/um/user/check/password',  'core\CompadUserController@checkUserPassword')->name('user.check.password');
    Route::get('/system/user/search/data', 'core\CompadUserController@searchUser')->name('system.user.search');
    Route::post('/system/user/update/{id}/password', 'core\CompadUserController@updateUserPassword')->name('user.update.password');
    Route::any('/user/data/list', [CompadUserController::class, 'getDatatableList'])->name('user.data.list');

    Route::get('/user/change/{id}/password', 'core\CompadUserController@changeUserPassword')->name('user.change.password');
    Route::post('/user/update/{id}/password', 'core\CompadUserController@updateCompadUserPassword')->name('compad.user.update.password');

    Route::get('/user-role/{id}/edit', 'core\CompadUserController@roleEdit')->name('user.role.edit');
    Route::post('/role/{id}/roleUpdate', 'core\CompadUserController@roleUpdate')->name('user.role.update');

    //Role
    Route::resource('/role', 'core\CompadRoleController', ['names' => 'role']);
    Route::get('role/{id}/view/menu', 'core\CompadRoleController@viewMenu')->name('role.view.menu');
    Route::any('/role/data/list', 'core\CompadRoleController@getDatatableList')->name('role.data.list');

    //Member
    Route::resource('/member', 'member\MemberController', ['names' => 'member']);
    Route::any('/member/data/list', 'member\MemberController@getDatatableList')->name('member.data.list');
    Route::get('/member/show/image/{image}/{member}', 'member\MemberController@showImage')->name('member.show.image');
    Route::get('/member/search/data', 'member\MemberController@searchMember')->name('member.search');
    Route::get('/member/create/connect/user/{member}', 'member\MemberController@createConnectUser')->name('create.connect.user');
    Route::post('/member/update/connect/user/{member}', 'member\MemberController@updateConnectUser')->name('update.connect.user');
    Route::get('/user/search/data', 'member\MemberController@searchUser')->name('user.search');
    Route::get('/member/create/status/{member}', 'member\MemberController@createMemberStatus')->name('create.member.status');
    Route::post('/member/update/status/{member}', 'member\MemberController@updateMemberStatus')->name('update.member.status');
    Route::get('/member/list/by/event/{member}', 'member\MemberController@memberListByEvent');

    //Academy
    Route::resource('/academy', 'academy\AcademyController', ['names' => 'academy']);
    Route::any('/academy/data/list', 'academy\AcademyController@getDatatableList')->name('academy.data.list');
    Route::post('/academy/isother', 'academy\AcademyController@getIsOther')->name('academy.isother');
    Route::get('/academy/search/org', 'academy\AcademyController@findOrganizationByName')->name('academy.search.org');

     //Event Team
    Route::resource('/event/registration/team', 'event\EventRegistrationController', ['names' => 'event.team.registration']);
    Route::any('/event/registration/team/data/list', 'event\EventRegistrationController@getDatatableList')->name('event.team.registration.data.list');
    
    Route::get('/event/registration/team/change/status', 'event\EventTeamRegistrationStatusController@change')->name('event.team.registration.change.status');
    Route::post('/event/registration/team/changed/status', 'event\EventTeamRegistrationStatusController@changed')->name('event.team.registration.changed.status');

    //team member
    Route::get('/event/registration/team/member/change/status', 'event\EventTeamMemberRegistrationStatusController@change')->name('event.team.member.registration.change.status');
    Route::post('/event/registration/team/member/changed/status', 'event\EventTeamMemberRegistrationStatusController@changed')->name('event.team.member.registration.changed.status');

    Route::get('/event/registration/team/member/create', 'event\EventRegistrationController@createTeamMember')->name('event.team.member.create');
    Route::post('/event/registration/team/member/store', 'event\EventRegistrationController@storeTeamMember')->name('event.team.member.store');
    Route::get('/event/registration/team/member/{id}/edit', 'event\EventRegistrationController@editTeamMember')->name('event.team.member.edit');
    Route::put('/event/registration/team/member/update/{id}', 'event\EventRegistrationController@updateTeamMember')->name('event.team.member.update');
    Route::post('/event/registration/team/member/{id}/remove', 'event\EventRegistrationController@removeTeamMember')->name('event.team.member.remove');

    Route::get('/event/registration/team/athlete_team/{id}', 'event\EventRegistrationController@MeduulegPrint')->name('event.registration.meduuleg');
    Route::get('/event/registration/team/athlete_team/{id}/pdf', 'event\EventRegistrationController@generatePdf')->name('generate-pdf');

    //Event
    Route::resource('/event/registration', 'event\EventRegistrationController', ['names' => 'event.registration']);
    Route::any('/event/registration/data/list', 'event\EventRegistrationController@getDatatableList')->name('event.registration.data.list');
    Route::post('/event/registration/take/config', 'event\EventRegistrationController@getConfigByEntryId')->name('event.registration.take.config');
    Route::get('/event/registration/create/award', 'event\EventRegistrationController@createPlace')->name('event.registration.create.award');
    Route::post('/event/registration/take/award', 'event\EventRegistrationController@takePlace')->name('event.registration.take.award');
    Route::get('/event/competition', 'event\EventRegistrationController@showCard')->name('event.competition.card');
    Route::get('/event/sports', 'event\EventRegistrationController@showSportCard')->name('event.sport.card');
    Route::get('/event/competition/{sportId}', 'event\EventRegistrationController@showCardJiuJitsu')->name('event.jiujitsu.competition.card');
    
    Route::get('/event/registration/change/status', 'event\EventRegistrationStatusController@change')->name('event.registration.change.status');
    Route::post('/event/registration/changed/status', 'event\EventRegistrationStatusController@changed')->name('event.registration.changed.status');
    Route::get('/event/registration/print/mandat', 'event\EventRegistrationController@printMandateByEventAndStatus')->name('event.registration.print.mandat');

    Route::get('/event/registration/print/certificate', 'event\EventRegistrationController@printUrgumjlulByMember')->name('event.registration.print.certificate');

    
    Route::resource('/event/config', 'event\EventConfigController', ['names' => 'event.config']);
    Route::any('/event/config/data/list', 'event\EventConfigController@getDatatableList')->name('event.config.data.list');
    Route::get('/event/search/data', 'event\EventConfigController@searchEvent')->name('event.search');
    Route::get('/event/config/copy/create/{eventConfigId}', 'event\EventConfigController@configCopy')->name('event.config.copy.create');
    Route::post('/event/config/copy/store/{eventConfigId}', 'event\EventConfigController@configCopyExecute')->name('event.config.copy.store');
    Route::get('/event/config-tabs', 'event\EventConfigController@includeTab')->name('event.config.tabs');

    Route::resource('/event/user', 'event\EventUserController', ['names' => 'event.user']);

    Route::resource('/event/toplist/point', 'reference\EventToplistPointController', ['names' => 'event.toplist.point']);
    Route::resource('/event/refund/request', 'event\EventRefundRequestController', ['names' => 'event.refund.request']);
    Route::any('/event/refund/request/data/list', 'event\EventRefundRequestController@getDatatableList')->name('event.refund.request.data.list');

    
    Route::get('/event/registration/bracket/generation', 'event\EventRegistrationController@bracketGeneration')->name('event.registration.bracket.generation');
    
    // test schedule
    Route::get('/event/{eventId}/schedule', 'event\EventRegistrationController@schedule')->name('event.schedule');

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

    //Entry Fee
    Route::resource('/event/entry/fee', 'reference\EventEntryFeeController', ['names' => 'event.entry.fee']);

    //Country
    Route::resource('/country', 'country\CountryController', ['names' => 'country']);
    Route::any('/country/data/list', 'country\CountryController@getDatatableList')->name('country.data.list');
    Route::get('/country/search/org', 'country\CountryController@findCountryByName')->name('country.search.org');

    //stats
    Route::get('/event/{eventId}/statistics', 'event\EventRegistrationController@statistics')->name('event.statistics');
});

Route::get('/event/{eventId}/bracket', 'event\EventRegistrationController@treeBracket')->name('event.bracket');
Route::get('/event/{eventId}/bracket/show', 'event\EventRegistrationController@showBracket')->name('event.show.bracket');

//result
Route::get('/event/{eventId}/results', 'event\EventRegistrationController@results')->name('event.results');
Route::get('/event/{eventId}/toplist', 'event\EventRegistrationController@toplist')->name('event.toplist');

//profile
Route::get('/profile/{member}','member\MemberController@profile')->name('member.profile');
Route::get('/profile/{member}/event','member\MemberController@profileEvent')->name('member.profile.event');
Route::get('/profile/{member}/results','member\MemberController@profileResult')->name('member.profile.results');
Route::get('/upcoming','member\MemberController@profileUpcoming')->name('upcoming');
Route::get('/pastEvent','member\MemberController@profilePastEvent')->name('pastEvent');

//Academies Profile
Route::get('/academies/{academyId}','academy\AcademyController@academies')->name('profile.academies');
Route::get('/academies/{academyId}/members','academy\AcademyController@academyMembers')->name('academy.members');
Route::get('/academies/{academyId}/statistics','academy\AcademyController@academyStatistics')->name('academy.statistics');
Route::get('/academies/{academyId}/pastEvent','academy\AcademyController@pastEvent')->name('academy.pastEvent');

//memberCard
Route::get('/memberCard/{member}','member\MemberController@memberCard')->name('member.card');
Route::get('memberCard/{member}/membership-card', [MemberController::class, 'generateMembershipCard'])->name('membership-card');

//Ranking
Route::get('/{sport_id}/ranking','event\EventConfigController@ranking')->name('ranking');

//info
Route::get('/{sport_id}/reference','event\EventConfigController@reference')->name('reference.information');
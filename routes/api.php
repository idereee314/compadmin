<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'prefix' => '',
], function() {
    /** START REQUEST */
    // References
    Route::get('/sport/certificate/print', 'event\EventRegistrationController@printCertificateByMember')->name('api.sport.certificate.print');
    
});

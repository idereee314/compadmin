<?php

Route::group(['prefix' => '' , 'name' => '', 'middleware' => ['auth']], function () 
{
    // Unit
    Route::post('/unit/soumDistrict', 'UnitController@getSoumDistrictByAimagCityId')->name('unit.soum.district');
    Route::post('/unit/bagKhoroo', 'UnitController@getBagKhorooBySoumDistrictId')->name('unit.bag.khoroo');

    //Object location
    Route::get('/object/edit', 'object\ObjectLocationController@editObjectLocation')->name('location.object.edit');
    Route::get('/object/get/data', 'object\ObjectLocationController@getObjectData')->name('location.object.get.object.data');
    Route::get('/object/find/{id}', 'object\ObjectLocationController@getObjectById')->name('location.object.find');
    Route::get('/object/entrance', 'object\ObjectLocationController@getEntranceByObjectId')->name('location.object.entrance');
    Route::get('/object/center/unit/{id}/{type}', 'object\ObjectLocationController@getUnitCenterByIdAndType')->name('location.object.center.unit');
    Route::get('/object/center/{id}', 'object\ObjectLocationController@getLocationCenterById')->name('location.object.center');
 
    Route::get('/object/edit/info', 'object\ObjectLocationController@editObjectLocationInfo')->name('location.object.edit.info');
    Route::post('/object/edit/info/update', 'object\ObjectLocationController@updateObjectLocationInfo')->name('location.object.edit.info.update');

    //Configuration
    Route::resource('/configuration', 'configuration\ConfigurationController', ['names'=>'configuration']);
    Route::post('/configuration/table/data','configuration\ConfigurationController@getDatatableList')->name('configuration.datalist');

    // Road object type
    Route::resource('/road/object/type', 'reference\RoadObjectTypeController', ['names'=>'road.object.type']);
    Route::post('/road/object/type/table/data','reference\RoadObjectTypeController@getDatatableList')->name('road.object.type.datalist');
});
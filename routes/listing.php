<?php

Route::group(['middleware' => ['auth']], function () 
{
    
    /** ORGANIZATION */
    Route::resource('/organization', 'organization\OrganizationController', ['names'=>'organization']);
    Route::post('/organization/table/data','organization\OrganizationController@getDatatableList')->name('organization.datalist');
    Route::get('/organization/table/tabs', 'organization\OrganizationController@includeTab')->name('organization.tabs');
    Route::get('/organization/service/add', 'organization\OrganizationController@addService')->name('organization.service.add');
    Route::post('/organization/service/attach/{id}', 'organization\OrganizationController@attachService')->name('organization.service.attach');
    Route::post('/organization/service/detach/{id}', 'organization\OrganizationController@detachService')->name('organization.service.detach');
    Route::get('/organization/event/add', 'organization\OrganizationController@addEvent')->name('organization.event.add');
    Route::post('/organization/event/attach', 'organization\OrganizationController@attachEvent')->name('organization.event.attach');
    Route::post('/organization/event/detach/{orgId}', 'organization\OrganizationController@detachEvent')->name('organization.event.detach');
    Route::get('/organization/by/tree', 'organization\OrganizationController@organizationByParent')->name('organization.by.tree');
    Route::get('/organization/by/name', 'organization\OrganizationController@findOrganizationByName')->name('organization.by.name');
    Route::get('/organization/copy/{id}', 'organization\OrganizationController@copyOrganization')->name('organization.copy');
    Route::post('/organization/save/copied', 'organization\OrganizationController@saveCopiedOrganization')->name('organization.save.copy');
    Route::get('/organization/add/status', 'organization\OrganizationController@addStatus')->name('organization.add.status');
    Route::post('/organization/attach/status', 'organization\OrganizationController@attachStatus')->name('organization.attach.status');
    Route::get('/organization/categories/by/id', 'organization\OrganizationController@getCategoriesByOrgId')->name('organization.categories');
    Route::get('/organization/category/services/id', 'organization\OrganizationController@getServicesByCategoryId')->name('organization.category.services');

    Route::get('/organization/from/burtgel','organization\OrganizationController@burtgel')->name('organization.burtgel');
    Route::post('/organization/table/data/burtgel','organization\OrganizationController@getOrganizationFromBurtgel')->name('organization.datalist.burtgel');

    // Organization Worktime
    Route::resource('/organization/worktime', 'organization\OrganizationWorktimeController', ['names'=>'organization.worktime']);
    
    // Organization Address
    Route::resource('/organization/address', 'organization\OrganizationAddressController', ['names'=>'organization.address']);
    Route::put('/organization/address/each/update', 'organization\OrganizationAddressController@updateByOne');
    Route::post('/organization/address/get/soum/district', 'organization\OrganizationAddressController@getSoumDistrictByAimagCity')->name('organization.address.get.soum.district');
    Route::post('/organization/address/get/bag/khoroo', 'organization\OrganizationAddressController@getBagKhorooBySoumDistrict')->name('organization.address.get.bag.khoroo');
    Route::get('/organization/address/get/location/center/point/{id}/{type}', 'organization\OrganizationAddressController@getLocationCenterPoint')->name('organization.address.get.location.center');
    Route::get('/organization/address/get/organization/address/{id}', 'organization\OrganizationAddressController@getOrganizationAddress')->name('organization.address.get.organization.address');
    Route::get('/organization/address/get/organization/address/by/point/{point}', 'organization\OrganizationAddressController@getOrganizationAddressByPoint')->name('organization.address.get.organization.address.by.point');
    Route::get('/organization/address/by/name', 'organization\OrganizationAddressController@objectByName')->name('object.by.name');
    
    // Organization Picture
    Route::resource('/organization/picture', 'organization\OrganizationPictureController', ['names'=>'organization.picture']);
    Route::get('/organization/picture/show/image/{id}', 'organization\OrganizationPictureController@showImage')->name('organization.show.image');
    Route::post('/organization/picture/remove/image','organization\OrganizationPictureController@removeImage')->name('organization.picture.remove.cover');

    // Organization Contact 
    Route::resource('/organization/contact', 'organization\OrganizationContactController', ['names'=>'organization.contact']);

    // Organization Banner
    Route::resource('/organization/banner', 'organization\OrganizationBannerController', ['names'=>'organization.banner']);

    // Organization Social
    Route::resource('/organization/social', 'organization\OrganizationSocialController', ['names'=>'organization.social']);

});
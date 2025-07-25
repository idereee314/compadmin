<?php

Route::group(['middleware' => ['auth']], function () 
{
    // References start

    //Category
    Route::resource('/reference/category', 'reference\CategoryController', ['names'=>'reference.category']);
    Route::post('/reference/category/table/data','reference\CategoryController@getDatatableList')->name('reference.category.datalist');
    Route::get('/reference/category/show/icon/{category}','reference\CategoryController@showIcon')->name('reference.category.show.icon');
    Route::post('/reference/category/remove/cover','reference\CategoryController@removeImage')->name('reference.category.remove.cover');
    Route::post('/reference/category/get/last/show/order/by/parent','reference\CategoryController@getLastShowOrderByParent')->name('reference.category.get.show.order.by.parent');
    Route::get('/reference/category/search/service','reference\CategoryController@searchServices')->name('reference.category.search.service');
    Route::get('/reference/category/edit/tabs','reference\CategoryController@includeTab')->name('reference.category.tabs');

    Route::get('/reference/category/service/add','reference\CategoryController@addService')->name('reference.category.service.add');
    Route::post('/reference/category/service/attach/{id}', 'reference\CategoryController@attachService')->name('reference.category.service.attach');
    Route::post('/reference/category/service/detach/{id}', 'reference\CategoryController@detachService')->name('reference.category.service.detach');

    //Feature
    Route::resource('/reference/features', 'reference\FeaturesController', ['names'=>'reference.features']);
    Route::post('/reference/features/table/data','reference\FeaturesController@getDatatableList')->name('reference.features.datalist');
    Route::get('/reference/features/show/icon/{features}','reference\FeaturesController@showIcon')->name('reference.features.show.icon');

    //Contact type
    Route::resource('/reference/contact/type', 'reference\ContactTypeController', ['names'=>'reference.contact.type']);
    Route::post('/reference/contact/type/table/data','reference\ContactTypeController@getDatatableList')->name('reference.contact.type.datalist');
    Route::get('/reference/contact/type/show/icon/{features}','reference\ContactTypeController@showIcon')->name('reference.contact.type.show.icon');

    // Picture type
    Route::resource('/reference/picture/type', 'reference\PictureTypeController', ['names'=>'reference.picture.type']);
    Route::post('/reference/picture/type/table/data','reference\PictureTypeController@getDatatableList')->name('reference.picture.type.datalist');

    // Organization status
    Route::resource('/reference/organization/status', 'reference\OrganizationStatusController', ['names'=>'reference.organization.status']);
    Route::any('/reference/organization/status/table/data','reference\OrganizationStatusController@getDatatableList')->name('reference.organization.status.datalist');

    // Organization type
    Route::resource('/reference/organization/type', 'reference\OrganizationTypeController', ['names'=>'reference.organization.type']);
    Route::any('/reference/organization/type/table/data','reference\OrganizationTypeController@getDatatableList')->name('reference.organization.type.datalist');

    //Service
    Route::resource('/reference/service', 'reference\ServiceController', ['names'=>'reference.service']);
    Route::post('/reference/service/table/data','reference\ServiceController@getDatatableList')->name('reference.service.datalist');
    Route::get('/reference/service/by/tree', 'reference\ServiceController@getByTree')->name('service.by.tree');
    Route::get('/reference/service/by/parent', 'reference\ServiceController@getByChildren')->name('service.by.parent');
    Route::get('/reference/service/show/tree', 'reference\ServiceController@showTree')->name('service.show.tree');

    // References end
    
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
    
    // Event
    Route::resource('/event/register', 'event\EventController', ['names'=>'event']);
    Route::any('/event/register/table/data','event\EventController@getDatatableList')->name('event.datalist');
    Route::get('/event/register/table/tabs', 'event\EventController@includeTab')->name('event.tabs');
    Route::get('/event/register/change/image', 'event\EventController@reChangePicture')->name('event.change.image');

    // Event Picture
    Route::resource('/event/picture', 'event\EventPictureController', ['names'=>'event.picture']);
    Route::get('/event/picture/show/image/{id}', 'event\EventPictureController@showImage')->name('event.show.image');
    Route::post('/event/picture/remove/image','event\EventPictureController@removeImage')->name('event.picture.remove');

    // Event Organizer
    Route::resource('/event/organizer', 'event\EventOrganizerController', ['names'=>'event.organizer']);

    // Event Location
    Route::resource('/event/location', 'event\EventLocationController', ['names'=>'event.location']);
    Route::post('/event/location/table/data','event\EventLocationController@getDatatableList')->name('event.location.datalist');

    /** PROSERVICE */
    Route::resource('/proservice', 'proservice\ProserviceController', ['names'=>'proservice']);
    Route::post('/proservice/table/data','proservice\ProserviceController@getDatatableList')->name('proservice.datalist');

    /** BANNER */
    Route::resource('/banner', 'banner\BannerController', ['names'=>'banner']);
});
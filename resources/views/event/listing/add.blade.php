@extends('default')

@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/pages/wizard/wizard-4.css')}}">
    <link href="{{asset('/assets/js/plugins/custom/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('/assets/js/plugins/custom/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{asset('assets/js/plugins/custom/jasny-bootstrap-fileinput/css/jasny-bootstrap-fileinput.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/custom/croppie-master/croppie.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/custom/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/custom/ol/css/ol.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/custom/ol/css/ol.smart.css')}}">

@endsection
@section('content')
    <!--begin::Main-->
    <!--begin::Header Mobile-->
    @include('layouts.mobile')
    <!--end::Header Mobile-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="d-flex flex-row flex-column-fluid page">
            <!--begin::Aside-->
            @include('layouts.aside')
            <!--end::Aside-->
            <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                <!--begin::Header-->
                @include('layouts.header')
                <!--end::Header-->
                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <!--begin::Subheader-->
                    <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
                        <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                            <!--begin::Details-->
                            <div class="d-flex align-items-center flex-wrap mr-2">
                                <!--begin::Title-->
                                <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">{{ trans('display.event_list') }}</h2>
                                <!--end::Title-->
                                <!--begin::Breadcrumb-->
                                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                                    <li class="breadcrumb-item text-muted">
                                        <a href="javascript:;" class="text-muted">{{ trans('display.general_new') }}</a>
                                    </li>
                                </ul>
                                <!--end::Breadcrumb-->
                            </div>
                            <!--end::Details-->
                        </div>
                    </div>
                    <!--end::Subheader-->
                    <!--begin::Entry-->
                    <div class="d-flex flex-column-fluid">
                        <!--begin::Container-->
                        <div class="container">
                            <div class="card card-custom card-transparent">
                                <div class="card-body p-0">
                                    <!--begin: Wizard-->
                                    <div class="wizard wizard-4" id="create-event-list-wizard" data-wizard-state="step-first" data-wizard-clickable="true">
                                        <!--begin: Wizard Nav-->
                                        <div class="wizard-nav">
                                            <div class="wizard-steps">
                                                <!--begin::Wizard Step 1 Nav-->
                                                <div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
                                                    <div class="wizard-wrapper">
                                                        <div class="wizard-number">1</div>
                                                        <div class="wizard-label">
                                                            <div class="wizard-title">{{ trans('display.general_registration') }}</div>
                                                            <div class="wizard-desc">{{ trans('display.general_event_information') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end::Wizard Step 1 Nav-->
                                                <!--begin::Wizard Step 2 Nav-->
                                                <div class="wizard-step" data-wizard-type="step">
                                                    <div class="wizard-wrapper">
                                                        <div class="wizard-number">2</div>
                                                        <div class="wizard-label">
                                                            <div class="wizard-title">{{ trans('display.general_notes') }}</div>
                                                            <div class="wizard-desc">{{ trans('display.general_information_notes') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end::Wizard Step 2 Nav-->
                                                <!--begin::Wizard Step 3 Nav-->
                                                <div class="wizard-step" data-wizard-type="step">
                                                    <div class="wizard-wrapper">
                                                        <div class="wizard-number">3</div>
                                                        <div class="wizard-label">
                                                            <div class="wizard-title">{{trans("display.general_image")}}</div>
                                                            <div class="wizard-desc">{{trans("display.pictures_details")}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end::Wizard Step 3 Nav-->
                                                <!--begin::Wizard Step 4 Nav-->
                                                <div class="wizard-step" data-wizard-type="step">
                                                    <div class="wizard-wrapper">
                                                        <div class="wizard-number">4</div>
                                                        <div class="wizard-label">
                                                            <div class="wizard-title">{{trans("display.general_location")}}</div>
                                                            <div class="wizard-desc">{{trans("display.general_location_info")}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end::Wizard Step 4 Nav-->
                                            </div>
                                        </div>
                                        <!--end: Wizard Nav-->
                                        <!--begin: Wizard Body-->
                                        <div class="card card-custom card-shadowless rounded-top-0">
                                            <div class="card-body p-0">
                                                <div class="row justify-content-center py-8 px-8 py-lg-15 px-lg-10">
                                                    <div class="col-xl-12 col-xxl-7">
                                                        <!--begin: Wizard Form-->
                                                        <form class="form mt-0 mt-lg-10" id="create-event-list-form" method="POST" action="{{ route('event.list.store') }}" enctype="multipart/form-data">
                                                            <!--begin: Wizard Step 1-->
                                                            <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
                                                                <!-- <div class="mb-10 font-weight-bold text-dark"><h5>Хүсэлтийн мэдээлэл оруулах</h5></div> -->
                                                                <!--begin::Category-->
                                                                <div class="form-group">
                                                                    <label>{{ trans('display.general_category') }}: <span class="text-danger">*</span></label>
                                                                    <div class="input-group input-group-solid">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">
                                                                                <i class="la la-book"></i>
                                                                            </span>
                                                                        </div>

                                                                        @php
                                                                            $lockedCategoryId = 357; // автоматаар сонгогдох, өөрчлөгдөхгүй ID
                                                                        @endphp

                                                                        <!-- disabled select -->
                                                                        <select class="form-control form-control-solid selectpicker" id="category" name="category_disabled" disabled>
                                                                            @forelse(@$categories as $category)
                                                                                <option value="{{ $category->id }}"
                                                                                    {{ $category->id == $lockedCategoryId ? 'selected' : '' }}>
                                                                                    {{ $category->name }}
                                                                                </option>
                                                                            @empty
                                                                                <option disabled>{{ trans('messages.error_no_record') }}</option>
                                                                            @endforelse
                                                                        </select>

                                                                        <!-- actual value to submit -->
                                                                        <input type="hidden" name="category" value="{{ $lockedCategoryId }}">

                                                                        <div class="error-here"></div>
                                                                    </div>
                                                                </div>
                                                                <!--end::Category-->
                                                                <!--begin::sport type-->
                                                                <div class="form-group">
                                                                    <label>{{ trans('display.general_sport_type') }}: <span class="text-danger">*</span></label>
                                                                    <div class="input-group input-group-solid">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">
                                                                                <i class="la la-book"></i>
                                                                            </span>
                                                                        </div>
                                                                        <select class="form-control form-control-solid selectpicker" id="sport" name="sport" data-msg-required="{{ trans('messages.validation_field_required') }}">
                                                                            <option value="">-- {{ trans('display.general_select') }} --</option>
                                                                            @forelse(@$sports as $sport)
                                                                            <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                                                                            @empty
                                                                            <option disabled>{{ trans('messages.error_no_record') }}</option>
                                                                            @endforelse
                                                                        </select>
                                                                        <div class="error-here"></div>
                                                                    </div>
                                                                    <div class="error-here"></div>
                                                                </div>
                                                                <!--end::sport type-->
                                                                <!--begin::title native-->
                                                                <div class="form-group">
                                                                    <label>{{ trans('display.general_title_mongolian') }}:</label>
                                                                    <div class="input-group input-group-solid">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">
                                                                                <i class="la la-phone"></i>
                                                                            </span>
                                                                        </div>
                                                                        <input type="text" class="form-control" name="title_mongolia" id="title_mongolia" data-inputmask="'regex': '[А-Яа-яЁёҮүӨөҮҮӨӨ\\s]*'"/>
                                                                    </div>
                                                                    <div class="error-here"></div>
                                                                </div>
                                                                <!--end::title native-->
                                                                <!--begin::title_english-->
                                                                <div class="form-group">
                                                                    <label>{{ trans('display.general_title_english') }}: <span class="text-danger">*</span></label>
                                                                    <div class="input-group input-group-solid">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">
                                                                                <i class="la la-phone"></i>
                                                                            </span>
                                                                        </div>
                                                                        <input type="text" class="form-control" name="name_english" id="name_english" data-inputmask="'regex': '[a-zA-Z\\s]*'" data-rule-required="true" data-msg-required="{{ trans('validation.required', ['Attribute' => trans('display.general_title_english')]) }}"/>
                                                                    </div>
                                                                    <div class="error-here"></div>
                                                                </div>
                                                                <!--end::title_english-->
                                                                <!--begin::Input-->
                                                                <div class="form-group">
                                                                    <label>{{ trans('display.general_duration') }}: <span class="text-danger">*</span></label>
                                                                    <div class="input-group input-group-solid date">
                                                                        <input type="text" class="form-control date-range-picker-time" name="dates" id="dates" readonly="readonly"/>
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">
                                                                                <i class="la la-calendar-check-o"></i> 
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="error-here"></div>
                                                                </div>
                                                                <!--end::Input-->
                                                                <div class="row" id="div-event-date">
                                
                                                                </div>
                                                                <div class="page-header d-flex justify-content-between align-items-center">
                                                                    <h4 class="mb-0">{{ trans('display.organization') }}</h4>
                                                                    <button type="button" class="btn btn-success btn-xs" id="btn-row-add">
                                                                        <i class="fa fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="form-group" id="div-form-group" style="display: none">
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn">
                                                                            <select class="form-control inline" name="roles[]" id="role">
                                                                                @forelse(@$roles as $key => $role)
                                                                                <option value="{{ $key }}">{{ $role }}</option>
                                                                                @empty
                                                                                @endforelse
                                                                            </select>
                                                                        </span>
                                                                        <input class="form-control" type="text" name="organizations[]" id="organization"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--end: Wizard Step 1-->
                                                            <!--begin: Wizard Step 2-->
                                                            <div class="pb-5" data-wizard-type="step-content">
                                                                <div class="mb-10 font-weight-bold text-dark"><h5>{{ trans('display.general_notes') }}</h5></div>
                                                                <!--begin::Input-->
                                                                <div class="form-group">
                                                                    <!-- <label>{{ trans('display.general_notes') }}: <span class="text-danger">*</span></label> -->
                                                                    <div class="input-group">                                                                        
				                                                        <textarea class="form-control form-control-solid editor" rows="10"></textarea>
                                                                    </div>
                                                                </div>
                                                                <!--end::Input-->
                                                            </div>
                                                            <!--end: Wizard Step 2-->
                                                            <!--begin: Wizard Step 3-->
                                                            <div class="pb-5" data-wizard-type="step-content">
                                                                <div class="mb-10 font-weight-bold text-dark"><h5>{{ trans('display.general_image') }}</h5></div>
                                                                <!--begin::Picture_type-->
                                                                <div class="form-group row">
			                                                    	<label class="col-3 col-form-label">{{trans('display.select_picture_type')}}</label>
			                                                    	<div class="col-9">
			                                                    		<input class="form-control" type="text" disabled="disabled" value="{{ $pictureType->description }} {{ $pictureType->width }}X{{ $pictureType->height }}"/>
			                                                    	</div>
			                                                    </div>
                                                                <!--end::Picture_type-->

                                                                <div class="form-group row">
                                                                    <label class="col-md-3 col-sm-6 text-right">{{ trans('display.general_image') }} <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9 col-sm-6">
                                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                            <span class="btn btn-success btn-file">
                                                                                <span class="fileinput-new">{{ trans('display.general_file_select') }}</span>
                                                                                <span class="fileinput-exists">{{ trans('display.general_file_change') }}</span>
                                                                                <input type="hidden" value="" name="...">
                                                                                <input type="file" name="cover_image" id="btn-upload" accept="image/*" value=""  data-rule-required="true" data-msg-required="{{ trans('validation.required') }}" data-rule-filesize="10485760" data-msg-filesize="{{ trans('messages.validation_file_size') }}">
                                                                            </span>
                                                                            <span class="fileinput-filename"></span>
                                                                            <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
                                                                        </div>
                                                                        <div class="error-here"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-md-3 col-sm-6 text-right">{{ trans('display.general_image_rotate') }} <span class="text-danger">*</span></label>
                                                                    <div class="col-md-9 col-sm-6">
                                                                        <button class="btn btn-info rotate" data-deg="90" type="button">{{ trans('display.general_image_rotate_left') }}</button>
                                                                        <button class="btn btn-info rotate" data-deg="-90" type="button">{{ trans('display.general_image_rotate_right') }}</button>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                </div>
                                                                <div class="row">
                                                                    <div id="img_canvas"></div>
                                                                </div>
                                                            </div>
                                                            <!--end: Wizard Step 3-->
                                                            <!--begin: Wizard Step 4-->
                                                            <div class="pb-5" data-wizard-type="step-content">
                                                                <h4 class="page-header">Байршил</h4>
                                                                <div class="form-group row">
                                                                    <label class="col-3 col-form-label text-right">{{trans('display.organization_branches')}}</label>
                                                                    <div class="col-9 col-form-label">
                                                                        <div class="checkbox-inline">
                                                                            <label class="checkbox checkbox-success">
                                                                                <input id="checkbox-success-all-org" type="checkbox" name="is_all_branch" value="1">
                                                                                <span></span>
                                                                            </label>
                                                                            <input type="hidden" name="organization_branch" id="organization_branch"/>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
			                                                    	<label>{{trans('display.organization_branches')}}</label>
			                                                    	<div class="input-group">
			                                                    		<div class="input-group-prepend">
			                                                    			<span class="input-group-text">
			                                                    				<label class="checkbox checkbox-inline checkbox-success">
			                                                    					<input type="checkbox" checked=""/>
			                                                    					<span></span>
			                                                    				</label>
			                                                    			</span>
			                                                    		</div>
			                                                    		<input type="hidden" name="organization_branch" id="organization_branch"/>
			                                                    	</div>
			                                                    </div>

                                                                <div class="form-group">
                                                                    <label class="col-md-3 col-sm-6 text-right">{{trans('display.general_object')}}</label>
                                                                    <div class="col-md-9 col-sm-6">
                                                                        <input type="text" name="object_locations" id="object_locations" class="form-control" data-role="tagsinput" readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-md-3 col-sm-6 text-right">{{trans('display.general_score')}}</label>
                                                                    <div class="col-md-9 col-sm-6">
                                                                        <input type="text" name="location_datas" id="location_datas" class="form-control" data-role="tagsinput" readonly/>
                                                                    </div>
                                                                </div>
                                                                <h4 class="page-header">Газрын зураг</h4>
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn">
                                                                            <select class="form-control inline" name="aimag_city" id="aimag_city">
                                                                                <option value="">-- {{ trans('display.aimag_city') }} --</option>
                                                                                @forelse(@$aimagCity as $city)
                                                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                                                @empty
                                                                                @endforelse
                                                                            </select>
                                                                        </span>
                                                                        <span class="input-group-btn">
                                                                            <input class="form-control" type="text" name="soum_district" id="soum_district"/>
                                                                        </span>
                                                                        <input class="form-control" type="text" name="bag_khoroo" id="bag_khoroo"/>
                                                                        <span class="input-group-btn">
                                                                            <button type="button" class="btn btn-info" id="btn-zoom-unit">Харах</button>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div id="mapid" class="map-sidebar-map" name="" style="height:600px !important;">
                                                                    
                                                                    </div>
                                                                    <div id="mappopup" class="ol-popup" style="max-height: 250px;overflow-y: scroll;">
                                                                        <a href="#" id="mappopup-closer" class="ol-popup-closer"></a>
                                                                        <div id="mappopup-content"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--end: Wizard Step 4-->
                                                            <!--begin: Wizard Actions-->
                                                            <div class="d-flex justify-content-between border-top mt-5 pt-10">
                                                                <div class="mr-2">
                                                                    <button type="button" class="btn btn-light-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-prev">{{ trans('display.general_previous') }}</button>
                                                                </div>
                                                                <div>
                                                                    <button type="submit" class="btn btn-success font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-submit">{{ trans('display.general_save') }}</button>
                                                                    <button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-next">{{ trans('display.general_next') }}</button>
                                                                </div>
                                                            </div>
                                                            <!--end: Wizard Actions-->
                                                        </form>
                                                        <!--end: Wizard Form-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end: Wizard Bpdy-->
                                    </div>
                                    <!--end: Wizard-->
                                </div>
                            </div>
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Entry-->
                </div>
                <!--end::Content-->
                <!--begin::Footer-->
                @include('layouts.footer')
                <!--end::Footer-->
            </div>
            @include ('event.listing.modals_detail')
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Main-->
@endsection
@section('javascript')
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/jasny-bootstrap-fileinput/js/jasny-bootstrap.fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/croppie-master/croppie.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/ol/build/ol.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/ol-ext-master/dist/ol-ext.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/bootstrap-wizard/jquery.bootstrap.wizard.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<script>
$(document).ready(function() {
    $("#create-event-list-form input").inputmask();
    $('.selectpicker').selectpicker({
        placeholder: "-- {{ trans('display.general_select') }} --"
    });
    $('.select2').select2({
        placeholder: "-- {{ trans('display.general_select') }} --"
    });
    $('#request_date').datepicker({
        rtl: KTUtil.isRTL(),
        todayHighlight: true,
        orientation: "bottom left",
        format: 'yyyy-mm-dd',
        templates: {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>'
        },
        language: 'mn'
    });

    $('#create-event-list-form .date-range-picker-time').daterangepicker({
        showWeekNumbers: true,
        showDropdowns: true,
        //timePicker: true,
        //timePicker24Hour: true,
        //autoUpdateInput: false,
        //timePickerIncrement: 10,
        minYear: 2021,
        maxYear: parseInt(moment().format("YYYY"), 1),
        locale: {
            format: 'YYYY-MM-DD',
            separator: " аас ",
            applyLabel: "Оруулах",
            cancelLabel: "Болих",
            fromLabel: "аас",
            toLabel: "руу",
            customRangeLabel: "Сонголт",
            daysOfWeek: [
                "Ня",
                "Да",
                "Мя",
                "Лха",
                "Пү",
                "Ба",
                "Бя"
            ],
            firstDay: 1
        }
    }, function(start, end, label) {
        var html = "";
        for(var d = new Date(start); d <= new Date(end); d.setDate(d.getDate() + 1))
        {
            html += '\
            <div class="form-group">\
                <div class="col-md-3 col-sm-12">\
                    <input type="type" class="form-control" name="event_date[]" id="event_date" value="'+moment(d).format("YYYY-MM-DD")+'" readonly/>\
                </div>\
                <div class="col-md-9 col-sm-12">\
                    <div class="row">\
                        <div class="col-md-6">\
                            <div class="timepicker input-group">\
                                <input class="form-control" type="text" data-format="hh:mm" data-inputmask="hh:mm" name="start_time[]" id="start_time" data-rule-required="true" data-msg-required=""/>\
                                <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>\
                            </div>\
                        </div>\
                        <div class="col-md-6">\
                            <div class="timepicker input-group">\
                                <input class="form-control" type="text" data-format="hh:mm" data-inputmask="hh:mm" name="end_time[]" id="end_time" data-rule-required="true" data-msg-required=""/>\
                                <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
            </div>\
            ';
            $.when($("#div-event-date").html(html)).then(function( data, textStatus, jqXHR ) {
                $(":input").inputmask(); 
                $('.timepicker').datetimepicker({
                    timePicker24Hour: true,
                    pickDate: false,
                    timeFormat:  "hh:mm",
                    pickSeconds: false,
                    minuteStep: 1,
                    container: '.modal-content'
                });
            });                    
        }
    });

    $('.editor').summernote({
        height: 150,
        toolbar: [
            [ 'style', [ 'style' ] ],
            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
            [ 'fontname', [ 'fontname' ] ],
            [ 'fontsize', [ 'fontsize' ] ],
            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
            [ 'table', [ 'table' ] ],
            [ 'view', [ 'undo', 'redo', 'codeview' ] ]
        ]
    });

    var orgWidth = null;
    var orgHeight = null;
    var $basic = null;
    var imgWidth = Math.round($("#create-event-list-form input[name=picture_type]").data('width') / 2);
    var imgHeight = Math.round($("#create-event-list-form input[name=picture_type]").data('height') / 2);
    var img = null;
    var croppedData = null;
    var orginalData = null;
    var role;
    var organizers;
    var i = 0;

    $('#img_canvas').croppie('destroy');
        $basic = $('#img_canvas').croppie({
        enableExif: true,
        viewport: {
            width: imgWidth,
            height: imgHeight,
            type: 'square'
        },
        boundary: { 
            width: 1000, 
            height: 700 
        },
        showZoomer: true,
        enableOrientation: true
    });

    $('#btn-upload').on('change', function(){
        if (this.files && this.files[0]) {
            if ( this.files[0].type.match(/^image\//) ) {
                var reader = new FileReader();
                reader.onload = function(evt) {
                    img = new Image();
                    
                    img.onload = function() {
                        $basic.croppie('bind', {
                            url: evt.target.result,
                            orientation: 1,
                            zoom: 0
                        });                 
                    }
                    img.src = evt.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
            else {
                alert("{{ trans('messages.error_file_type') }} {{ trans('messages.warning_image_file') }}");
            }
        }
        else {
            alert("{{ trans('messages.error_no_record') }}");
        }
    });

    $('.rotate').on('click', function(ev) {
        $basic.croppie('rotate', parseInt($(this).data('deg')));
    });

    $('#validation-wizard').bootstrapWizard({
        'onNext': function(tab, navigation, index) {
            var $valid = $("#create-event-list-form").valid();
            if(!$valid) {
                $validator.focusInvalid();
            }
            
            if(index == 2)
            {
                role = $("#create-event-list-form select[name^=roles]")[0];
                organizers = $(role).closest('.form-group').find('input[name^=organizations]').val();
                
                $("#create-event-list-form input[name=organization_branch]").val(null).trigger('change.select2');
                if(organizers.length > 0)
                {
                    $("#create-event-list-form input[name=organization_branch]").data("select2").opts.minimumInputLength = 0;
                }
                else
                {
                    $("#create-event-list-form input[name=organization_branch]").data("select2").opts.minimumInputLength = 3;
                }
                $(".ol-unselectable").css('display', 'block');
            }
        },
        onTabClick: function(tab, navigation, index) {
            var $valid = $("#create-event-list-form").valid();
            if(!$valid) {
                $validator.focusInvalid();
            }
            if(index == 2)
            {
                role = $("#create-event-list-form select[name^=roles]")[0];
                organizers = $(role).closest('.form-group').find('input[name^=organizations]').val();
                
                $("#create-event-list-form input[name=organization_branch]").val(null).trigger('change.select2');
                if(organizers.length > 0)
                {
                    $("#create-event-list-form input[name=organization_branch]").data("select2").opts.minimumInputLength = 0;
                }
                else
                {
                    $("#create-event-list-form input[name=organization_branch]").data("select2").opts.minimumInputLength = 3;
                }
            }
            $(".ol-unselectable").css('display', 'block');
        }
    });

    $("#btn-row-add").on('click', function() {
        $('.select2').select2({
            placeholder: "-- {{ trans('display.general_select') }} --"
        });
        var selCount = $("#create-event-list-form select[name^=roles]:first option").length;
        var sel = $("#div-form-group");
        var clone = sel.clone(true, true);
        i++;

        if(selCount >= i) {
            clone.attr("id", "organizationId" + i);
            clone.show();

            clone.insertBefore('#div-form-group').find('input').select2({
                width: 'resolve',
                tags: true,
                tokenSeparators: [',', ' '],
                dropdownAutoWidth: true,
                placeholder: "-- {{ trans('display.general_select') }} --",
                ajax: {
                    type: 'GET',
                    url: '{!! route('organization.by.tree') !!}',
                    data: function(params) {
                        return { q: params };
                    },
                    processResults: function(data) {
                        return { results: data };
                    },
                    cache: true
                },
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                maximumSelectionLength: 30,
                minimumInputLength: 3
            });

            // clone.find("select option").eq(i - 1).prop("selected", 'selected');
            // clone.find("select option:selected").prop("disabled", true);
        }
    });

    $("#create-event-list-form input[name=organization_branch]").select2({
        width: 'resolve',
        tags: true,
        tokenSeparators: [',', ' '],
        dropdownAutoWidth : true,
        placeholder: "-- {{ trans('display.general_select') }} --",
        ajax: {
            type: 'GET',
            url: '{!! route('organization.by.name') !!}',
            data: function (params) {
                return {
                    orgIds: organizers,
                    q: params
                };
            },
            processResults: function (data) {
                return {results: data}
            },
            cache: true
        },
        id: 'id',
        closeOnSelect: true,
        allowClear: true,
        maximumSelectionLength: 10,
        //minimumInputLength: 3,
        formatSelection: function (item) {
            return item.name;
        },
        formatResult: function (item) {
            return item.name;
        }
    });

    $('#create-event-list-form input[name=object_locations]').tagsinput({
        freeInput: false,
        maxTags: 20,
        itemValue: function(item) {
            return item.id;
        },
        itemText: function(item) {
            return item.text;
        }
    });
    $('#create-event-list-form input[name=object_datas]').tagsinput({
        freeInput: false,
        maxTags: 20
    });
    $('#create-event-list-form input[name=organization_branch]').on('select2-selecting', function (e) {
        //console.log(e.choice.firstname);
        $('#create-event-list-form input[name=object_locations]').tagsinput('add', {id: e.choice.address.object_location_id, text: e.choice.address.object_location.object_name});
        $.ajax({
            url: '/location/object/find/'+e.choice.address.object_location_id,
            type: 'GET',
            success: function(response) {
                if (response != "") {
                    addSelectedLocationToMap(map, 'selectObject', response[1]);
                }
            },
            error: function (xhr, textStatus, error) {
                console.log(xhr.statusText);
                console.log(textStatus);
                console.log(error);
            },
            async: false,
            cache: true,
            processData: false,
            contentType: false        
        });
        
    }).on("select2-removing", function(e) {
        $('#create-event-list-form input[name=object_locations]').tagsinput('remove', e.address.object_location_id);
    });
    $("#create-event-list-form select[name=aimag_city]").on("change", function()
    {
        var aimagId = $(this).val();
        $.ajax({
            type: 'POST',
            url: '/unit/soumDistrict',
            data: {aimagCityId: aimagId},
            success: function (data) {
                $('#create-event-list-form input[name=soum_district]').select2({
                    placeholder: "-- {{ trans('display.soum_district') }} --",
                    data: {results: JSON.parse(data), text: function (item) {
                        return item.name;
                    }},
                    id: 'id',
                    closeOnSelect: true,
                    allowClear: true,
                    formatSelection: function (item) {
                        return item.name;
                    },
                    formatResult: function (item) {
                        return item.name;
                    },
                }).select2("enable", true);
            },
            error: function (xhr, textStatus, error) {
                console.log(xhr.statusText);
                console.log(textStatus);
                console.log(error);
            },
            async: false
        });
        $('#create-event-list-form input[name=bag_khoroo]').select2({data: ""}).select2("enable", false);
    });
    $("#create-event-list-form input[name=soum_district]").on("change", function()
    {
        var soumId = $(this).val();
        $.ajax({
            type: 'POST',
            url: '/location/unit/bagKhoroo',
            data: {soumDistrictId: soumId},
            success: function (data) {
                $('#create-event-list-form input[name=bag_khoroo]').select2({
                    placeholder: "-- {{ trans('display.bag_khoroo') }} --",
                    data: {results: JSON.parse(data), text: function (item) {
                        return item.name;
                    }},
                    id: 'id',
                    closeOnSelect: true,
                    allowClear: true,
                    formatSelection: function (item) {
                        return item.name;
                    },
                    formatResult: function (item) {
                        return item.name;
                    },
                }).select2("enable", true);
            },
            error: function (xhr, textStatus, error) {
                console.log(xhr.statusText);
                console.log(textStatus);
                console.log(error);
            },
            async: false
        });    
    });
    $("#btn-zoom-unit").on('click', function(){
        const urlParams = new URLSearchParams($("#create-event-list-form").serialize());
        if(!!urlParams.get('bag_khoroo') && urlParams.get('bag_khoroo') != '')
        {
            locationType = "Bag";
            showLocationId = $("#create-event-list-form input[name=bag_khoroo]").val();
        }
        else if(!!urlParams.get('soum_district') && urlParams.get('soum_district') != '')
        {
            locationType = "Soum";
            showLocationId = $("#create-event-list-form input[name=soum_district]").val();
        }
        else if(!!urlParams.get('aimag_city') && urlParams.get('aimag_city') != '')
        {
            locationType = "Aimag";
            showLocationId = $("#create-event-list-form select[name=aimag_city]").val();
        }
        else 
        {
            $.alert({
                title: '{!! trans('messages.info_title') !!}',
                content: 'Засаг захиргааны хил сонгоно уу!'
            });
            return false;
        }
        map.updateSize();
        changeLayerVisible(map, 'objectLayer', true, geoserver, addObjectLayerMethodName, [showLocationId], true);
        addLocationById(map, 'selectedLocation', showLocationId, locationType);
    });

    wizard = new KTWizard('create-event-list-wizard', {
        startStep: 1,
        clickableSteps: true,
    });
    //wizard.goTo(4);
    // Change event
    wizard.on('change', function (wizard) {
        KTUtil.scrollTop();
        if (wizard.getStep() > wizard.getNewStep()) {
            return;
        }
        
        $('#create-event-list-form').validate({
            ignore: function (index, el) {
                var $el = $(el);
                var state = $(el).data('wizard-state');
                if (typeof state !== 'undefined') {
                    return true;
                }

                return $el.is(':hidden');
            },
            highlight:function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                //form.submit();
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: new FormData(form),
                    success: function(response) {                           
                        if(response.status == 'success')
                        {
                            toastr.success(response.msg);
                            window.open('{!! route('event.list.index') !!}', '_self');
                        }
                        else 
                        {
                            toastr.error(response.errors, response.msg, {
                                "closeButton": true,
                                "timeOut": "0",
                                "extendedTimeOut": "0",
                            });
                        }
                    },
                    error: function (xhr, textStatus, error) {
                        console.log(xhr.statusText);
                        console.log(textStatus);
                        console.log(error);
                    },
                    async: false,
                    processData: false,
                    contentType: false
                });
            },
            errorPlacement: function(error, element) {
                if($(element).parents('.form-group').find(".error-here").length > 0){
                    error.appendTo($(element).parents('.form-group').find(".error-here"));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        if ($('#create-event-list-form').length > 0) {
            if ($('#create-event-list-form').valid()) {
                wizard.goTo(wizard.getNewStep());

                KTUtil.scrollTop();
            } else {
                Swal.fire({
                    text: "Уучлаарай! Алдаа илэрсэн байна, Дахин оролдоно уу.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Okay",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light"
                    }
                }).then(function () {
                    KTUtil.scrollTop();
                });
            }
        }

        return false;
    });

    // Submit event
    wizard.on('submit', function (wizard) {
        Swal.fire({
            text: "Бүгд боллоо! Мэдээллийг хадгалахад итгэлтэй байна уу.",
            icon: "success",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Тийм, хадгалах!",
            cancelButtonText: "Үгүй, цуцлах",
            customClass: {
                confirmButton: "btn font-weight-bold btn-primary",
                cancelButton: "btn font-weight-bold btn-default"
            }
        }).then(function (result) {
            if (result.value) {
                $('#create-event-list-form').submit();
            } else if (result.dismiss === 'cancel') {
                Swal.fire({
                    text: "Оруулсан мэдээллийг хадгалаагүй болно! Мэдээллээ дахин шалгана уу.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, тэгье!",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-primary",
                    }
                });
            }
        });
    });
}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

</script>
@stop
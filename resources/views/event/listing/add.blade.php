@extends('default')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/pages/wizard/wizard-4.css')}}">
    <link href="{{asset('/assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css"/>
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
                                                            <div class="wizard-title">{{ trans('display.contact_us') }}</div>
                                                            <div class="wizard-desc">Утасны дугаар болон имэйл</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end::Wizard Step 2 Nav-->
                                                <!--begin::Wizard Step 3 Nav-->
                                                <div class="wizard-step" data-wizard-type="step">
                                                    <div class="wizard-wrapper">
                                                        <div class="wizard-number">3</div>
                                                        <div class="wizard-label">
                                                            <div class="wizard-title">Хавсралт</div>
                                                            <div class="wizard-desc">Хавсралт баримт бичиг</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end::Wizard Step 3 Nav-->
                                                <!--begin::Wizard Step 4 Nav-->
                                                <div class="wizard-step" data-wizard-type="step">
                                                    <div class="wizard-wrapper">
                                                        <div class="wizard-number">4</div>
                                                        <div class="wizard-label">
                                                            <div class="wizard-title">Хариу</div>
                                                            <div class="wizard-desc">Хариу хүлээн авах мэдээлэл</div>
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
                                                                            <option value="0">-- {{ trans('display.general_select') }} --</option>
                                                                            @forelse(@$sports as $sport)
                                                                            <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                                                                            @empty
                                                                            @endforelse
                                                                        </select>
                                                                        <div class="error-here"></div>
                                                                    </div>
                                                                    <div class="error-here"></div>
                                                                </div>
                                                                <!--end::sport type-->
                                                                <!--begin::name_english-->
                                                                <div class="form-group">
                                                                    <label>{{ trans('display.general_name_en') }}: <span class="text-danger">*</span></label>
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text" class="form-control" name="name_english" id="name_english" data-rule-required="true" data-msg-required="{{ trans('validation.required', ['Attribute' => trans('display.general_name')]) }}"/>
                                                                    </div>
                                                                </div>
                                                                <!--end::name_english-->
                                                                <!--begin::Name native-->
                                                                <div class="form-group">
                                                                    <label>{{ trans('display.general_name') }}: <span class="text-danger">*</span></label>
                                                                    <div class="input-group input-group-solid">                                                                        
                                                                        <input type="text" class="form-control" name="name" id="name" data-rule-required="true" data-msg-required="{{ trans('validation.required', ['Attribute' => trans('display.general_name')]) }}"/>
                                                                    </div>
                                                                </div>
                                                                <!--end::Name native-->
                                                                <!--begin::Input-->
                                                                <div class="form-group">
                                                                    <label>{{ trans('display.general_duration') }}: <span class="text-danger">*</span></label>
                                                                    <div class="input-group input-group-solid date">
                                                                        <input type="text" class="form-control date-range-picker-time" name="dates" data-rule-required="true" data-msg-required="{{ trans('validation.required') }}">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">
                                                                                <i class="la la-calendar-check-o"></i> 
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="error-here"></div>
                                                                    <div class="row" id="div-event-date">
                                                                </div>
                                                                <!--end::Input-->
                                                            </div>
                                                            <!--end: Wizard Step 1-->
                                                            <!--begin: Wizard Step 2-->
                                                            <div class="pb-5" data-wizard-type="step-content">
                                                                <div class="mb-10 font-weight-bold text-dark"><h5>Холбоо барих мэдээлэл оруулах</h5></div>
                                                                <div class="row">
                                                                    <div class="col-xl-6">
                                                                        <!--begin::Input-->
                                                                        <div class="form-group">
                                                                            <label>{{ trans('display.request_contacts') }} 1: <span class="text-danger">*</span></label>
                                                                            <div class="input-group input-group-solid">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">
                                                                                        <i class="la la-phone"></i>
                                                                                    </span>
                                                                                </div>
                                                                                <input type="text" class="form-control form-control-solid" name="contact_phones[]" id="contact_phones" data-inputmask="'mask': '9{8}', 'greedy': false" data-rule-required="true" data-msg-required="{{ trans('validation.required', ['Attribute' => trans('display.request_contacts')]) }}" data-rule-digits="true" data-msg-digits="{{ trans('validation.phones', ['Attribute' => trans('display.request_contacts')]) }}"/>
                                                                            </div>
                                                                            <div class="error-here"></div>
                                                                        </div>
                                                                        <!--end::Input-->
                                                                    </div>
                                                                    <div class="col-xl-6">
                                                                        <!--begin::Input-->
                                                                        <div class="form-group">
                                                                            <label>{{ trans('display.request_contacts') }} 2: </label>
                                                                            <div class="input-group input-group-solid">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">
                                                                                        <i class="la la-phone"></i>
                                                                                    </span>
                                                                                </div>
                                                                                <input type="text" class="form-control form-control-solid" name="contact_phones[]" id="contact_phones" data-inputmask="'mask': '9{8}', 'greedy': false" data-rule-digits="true" data-msg-digits="{{ trans('validation.phones', ['Attribute' => trans('display.request_contacts')]) }}"/>
                                                                            </div>
                                                                            <div class="error-here"></div>
                                                                        </div>
                                                                        <!--end::Input-->
                                                                    </div>
                                                                </div>
                                                                <!--begin::Input-->
                                                                <div class="form-group">
                                                                    <label>{{ trans('display.request_contact_email') }}: <span class="text-danger">*</span></label>
                                                                    <div class="input-group input-group-solid">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">
                                                                                <i class="la la-at"></i>
                                                                            </span>
                                                                        </div>
                                                                        <input type="text" class="form-control" name="contact_email" id="contact_email" data-rule-required="true" data-msg-required="{{ trans('validation.required', ['Attribute' => trans('display.request_contact_email')]) }}" data-rule-email="true" data-msg-email="{{ trans('validation.email', ['Attribute' => trans('display.request_contact_email')]) }}" data-inputmask="'alias': 'email'"/>
                                                                    </div>
                                                                    <span class="form-text text-muted">Хариуг цахимаар авах бол энэ имэйл хаягаар илгээнэ.</span>
                                                                </div>
                                                                <!--end::Input-->
                                                            </div>
                                                            <!--end: Wizard Step 2-->
                                                            <!--begin: Wizard Step 3-->
                                                            <div class="pb-5" data-wizard-type="step-content">
                                                                <div class="mb-10 font-weight-bold text-dark"><h5>Баримт бичгийн бүрдүүлбэр шалгах</h5></div>
                                                                <div id="div-file-append"></div>
                                                            </div>
                                                            <!--end: Wizard Step 3-->
                                                            <!--begin: Wizard Step 4-->
                                                            <div class="pb-5" data-wizard-type="step-content">
                                                                <!--begin::Input-->
                                                                <div class="form-group response">
                                                                    <label>{{ trans('display.request_response_type') }}: <span class="text-danger">*</span></label>
                                                                    <div class="input-group input-group-solid">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">
                                                                                <i class="la la-book"></i>
                                                                            </span>
                                                                        </div>
                                                                        <select class="form-control select" name="response_type_id" id="response_type_id" data-style="form-control-solid" data-rule-required="true" data-msg-required="{{ trans('validation.required', ['Attribute' => trans('display.request_response_type')]) }}">
                                                                        </select>
                                                                    </div>
                                                                    <div class="error-here"></div>
                                                                </div>
                                                                <!--end::Input-->
                                                                
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
<script type="text/javascript" src="{{asset('assets/js/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
<script>
$(document).ready(function() {
    var applicantSelect, agentSelect;
    $("#create-event-list-form input").inputmask();
    $('.selectpicker').selectpicker();
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

    $('.date-range-picker-time').daterangepicker({
        autoUpdateInput: false,
        showWeekNumbers: true,
        showDropdowns: true,
        //timePicker: true,
        //timePicker24Hour: true,
        autoUpdateInput: false,
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
    });

    $('.date-range-picker-time').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' аас ' + picker.endDate.format('YYYY-MM-DD'));
    });

    $('.date-range-picker-time').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
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
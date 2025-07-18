@extends('default')

@section('css')
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.css')}}">
@endsection

@section('content')
<!--begin::Main-->
<!--begin::Header Mobile-->
@include('layouts.mobile')
<!--end::Header Mobile-->
<!--begin::Aside-->
@include('layouts.aside')
<!--end::Aside-->
<!--begin::Wrapper-->
<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
    <!--begin::Header-->
    @include('layouts.header')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid">
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
                            <a href="javascript:;" class="text-muted">{{ trans('display.general_registration') }}</a>
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
                <!--begin::Card-->
                <div class="card card-custom">
                    <div class="card-body">
                        
                        <!--begin::Accordion-->
                        <div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle" id="search">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#search-event">
                                        <span class="svg-icon svg-icon-primary">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Angle-double-right.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999)" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                        <div class="card-label pl-4">{{ trans('display.general_search_list') }}</div>
                                    </div>
                                </div>
                                <div id="search-event" class="collapse" data-parent="#search">
                                    <div class="card-body">
                                        <!--begin: Search Form-->
                                        <form class="mb-10" id="event-search-form" method="POST">
                                            <div class="row mb-6">
                                                <div class="col-lg-2 mb-lg-0 mb-6">
                                                    <label>{{trans('display.human_register_number')}}</label>
                                                    <input type="text" class="form-control datatable-input" name="search_register_number" id="search_register_number" data-col-index="1">
                                                </div>
                                                <div class="col-lg-2 mb-lg-0 mb-6">
                                                    <label>{{trans('display.general_name')}}</label>
                                                    <input type="text" class="form-control datatable-input" name="search_name" id="search_name" data-col-index="2">
                                                </div>
                                                <div class="col-lg-2 mb-lg-0 mb-6">
                                                    <label>{{trans('display.human_firstname')}}</label>
                                                    <input type="text" class="form-control datatable-input" name="search_firstname" id="search_firstname" data-col-index="3">
                                                </div>
                                                <div class="col-lg-2 mb-lg-0 mb-6">
                                                    <label>{{trans('display.human_phone_number')}}</label>
                                                    <input type="tel" class="form-control datatable-input" name="search_phone_number" id="search_phone_number" data-col-index="4">
                                                </div>                                                
                                                <div class="col-lg-2 mb-lg-0 mb-6">
                                                    <label>{{ trans('display.general_status') }}:</label>
                                                    <select class="form-control selectpicker datatable-input" name="search_status" id="search_status" data-col-index="6">
                                                        <option value="">-- {{ trans('display.general_all') }} --</option>
                                                        @forelse(@Config::get('enums.member_status') as $key => $status)
                                                        <option value="{{ $key }}">{{ $status }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>                                            
                                            <div class="row mt-8">
                                                <div class="col-lg-12">
                                                    <button type="submit" class="btn btn-primary btn-primary--icon">
                                                        <span>
                                                            <i class="la la-search"></i>
                                                            <span>{{ trans('display.general_search') }}</span>
                                                        </span>
                                                    </button>
                                                    <button type="reset" class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                                                        <span>
                                                            <i class="la la-close"></i>
                                                            <span>{{ trans('display.general_reset') }}</span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Accordion-->
                        <div class="separator separator-solid mb-5"></div>
                        <!--begin: Datatable-->
                        <table class="table table-separate table-head-custom" id="event-datatable" style="margin-top: 13px !important">
                            <thead>
                                <tr>
                                    <th width="5%">№</th>
                                    <th width="20%">{{trans('display.general_name')}}</th>
                                    <th width="20%">{{trans('display.general_event_date')}}</th>
                                    <!-- <th width="10%">{{trans('display.general_description')}}</th> -->
                                    <th width="10%">{{trans('display.event_details')}}</th>
                                    <th width="10%">{{trans('display.general_status')}}</th>
                                    <!-- <th width="10%">{{trans('display.organization')}}</th> -->                                    
                                    <th width="20%">{{trans('display.general_created_at')}}</th>
                                    <th width="10%">{{trans('display.general_manage')}}</th>
                                </tr>
                            </thead>
                        </table>
                        <!--end: Datatable-->
                    </div>
                </div>
                <!--end::Card-->
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
@include ($view_path.'.modals')
<!--end::Wrapper-->
<!--end::Main-->
@section('javascript')
<script src="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<script>
$(document).ready(function() {
    eventTable = $("#event-datatable").DataTable({
        processing: false,
        serverSide: true,
        autoWidth: true,
        select: true,
        responsive: true,
        dataType: 'json',
        paginationType: "full_numbers",
        ajax: {
            url: '{{route('event.data.list')}}',
            type: 'POST',
            data: function ( d ) {
                d.search_name = $('#event-search-form input[id="search_name"]').val();
            },
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                width: "30px"
            },
            {data: 'name'},
            {data: 'event_date'},
            {data: 'description'},
            {data: 'status'},            
            {data: 'created_at'},
            {data: 'action'},
        ],
        columnDefs: [ 
        {
            searchable: false,
            orderable: false,
            targets: [0]
        },{
            class: "text-center",
            targets: [0]
        }],
        order: [[ 5, "desc" ]],
        dom: "<'top'B><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
        buttons: [
        {
            text: '<i class="la la-plus"></i> {{ trans('display.general_new') }}',
            className: "btn btn-light-danger font-weight-bolder mb-2 {{ SecurityHelper::checkPermission(@Config::get('permission.event_registration'), Config::get('permission.editable')) ? '' : 'd-none' }}",
            action: function ( e, dt, node, config ) {
                window.open('{!! route('event.list.create') !!}', '_self');
            }
        }]
	});

    $('#event-datatable tbody').on( 'click', 'tr td a.description', function () {
        var eventId = $(this).data("eventid");

        $.get('list/'+eventId, showEventDescriptionModal);
    });

    function showEventDescriptionModal( data ) {
        $('#eventDetailModal').modal("show");
        $('#eventDetailModal').on('shown.bs.modal', function(){
            $('#eventDetailModal .modal-content').html(data);
            $('.selectpicker').selectpicker();
            $(this).off('shown.bs.modal');
        });
        $('#eventDetailModal').on('hidden.bs.modal', function(){
            $('#eventDetailModal .modal-content').empty();
        });
    }

    function showAddModal( data ) {
        $('#eventModal').modal();
        $('#eventModal').on('shown.bs.modal', function(){
            $('#eventModal .modal-content').html(data);
            $('.selectpicker').selectpicker();
            $("#register_number").inputmask({ regex: "[А-Я]{2}[0-9]*"});
            $('.only-phone').inputmask("99 99 99 99");

            $('#birth').datepicker({
                rtl: KTUtil.isRTL(),
                todayHighlight: true,
                orientation: "bottom left",
                format: 'yyyy-mm-dd',
                templates: {
                    leftArrow: '<i class="la la-angle-right"></i>',
                    rightArrow: '<i class="la la-angle-left"></i>'
                }
            });

            $('#is_foreigner').on('change', function() {    
                if (this.checked) {
                    $("#register_passport").prop('disabled', false);
                    $("#register_number").prop('disabled', true);
                } else {
                    $("#register_passport").prop('disabled', true);
                    $("#register_number").prop('disabled', false);
                }
            });

            $('#add-member-form').validate({
                ignore: [],
                highlight:function(element) {
                    $(element).parents('.form-group').addClass('has-error has-feedback');
                },
                unhighlight: function(element) {
                    $(element).parents('.form-group').removeClass('has-error');
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: new FormData(form),
                        success: function(response) {
                            if(response.status == 'success')
                            {
                                $('#eventModal').find("#close").trigger('click');
                                toastr.success(response.msg);
                                memberTable.draw('page');
                            }
                            else {
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
                    if($(element).parents('.form-group').find(".error-here")){
                        error.appendTo($(element).parents('.form-group').find(".error-here"));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            $(this).off('shown.bs.modal');
        });

        $('#eventModal').on('hidden.bs.modal', function(){
            $('#eventModal .modal-body').empty();
        });
    }

}).ajaxStart($.blockUI).ajaxStop($.unblockUI);
</script>
@endsection
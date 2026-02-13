@extends('default')

@section('styles')
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.css')}}">
@endsection

@section('content')

<section id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed page-loading">
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
                <div class="d-flex flex-column-fluid">
                    <!--begin::Container-->
                    <div class="container">
                        <!--begin::Card-->
                        <div class="card card-custom">
                            <div class="card-header flex-wrap py-5">
                                <div class="card-title">
                                    <h3 class="card-label"> Гишүүнчлэлтэй академи жагсаалт 
                                    <span class="d-block text-muted pt-2 font-size-sm">Академи</span></h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle" id="search">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title collapsed" data-toggle="collapse" data-target="#search-academy">
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
                                                <div class="card-label pl-4">Хайлт</div>
                                            </div>
                                        </div>
                                        <div id="search-academy" class="collapse" data-parent="#search">
                                            <div class="card-body">
                                                <!--begin: Search Form-->
                                                <form class="mb-10" id="academy-search-form" method="POST">
                                                    <div class="row mb-6">
                                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                                            <label>{{trans('display.comp_academy_name')}}</label>
                                                            <input type="text" class="form-control datatable-input" name="name" id="name" data-col-index="1">
                                                        </div>
                                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                                            <label>{{ trans('display.general_type') }}:</label>
                                                            <select class="form-control selectpicker datatable-input" name="search_type" id="search_type" data-col-index="6">
                                                                <option value="">-- {{ trans('display.general_all') }} --</option>
                                                                @forelse(@Config::get('enums.org_type') as $key => $type)
                                                                <option value="{{ $key }}">{{ $type }}</option>
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
                                <!--begin: Datatable-->
                                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="panel-sub-heading">

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline" id="membership_academy_datatable" role="grid" aria-describedby="kt_datatable_info" style="width: 1235px;">
                                                <thead>
                                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                        <th style="width:30px;">№</th>
                                                        <th width="14%">{{ trans('display.general_name') }}</th>
                                                        <th width="14%">{{ trans('display.general_name_en') }}</th>
                                                        <th width="10%">{{ trans('display.general_founded_year') }}</th>
                                                        <th width="10%">{{ trans('display.general_sport_type') }}</th>
                                                        <th width="10%">{{ trans('display.general_type') }}</th>
                                                        <th width="10%">{{ trans('display.general_price') }}</th>
                                                        <th width="10%">{{ trans('display.general_start_date') }}</th>
                                                        <th width="10%">{{ trans('display.general_end_date') }}</th>
                                                        <th width="10%">{{ trans('display.general_created_at') }}</th>
                                                        <th width="8%">{{ trans('display.general_manage') }}</th>
                                                    </tr>
                                                </thead>
                                            </table>    
                                        </div>
                                    </div>
                                </div>
                                <!--end: Datatable-->
                            </div>
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--begin::Footer-->
                @include('layouts.footer')
                <!--end::Footer-->
            </div>
            @include ($view_path.'.membership.modals')
            <!--end::Wrapper-->
        <!--end::Main-->
</section>

@section('javascript')
<script src="{{asset('assets/js/plugins/custom/datatables/datatables.js')}}"></script>
<script src="{{asset('assets/js/smart.js')}}"></script>
<script>
$(document).ready(function() {
    academyTable = $("#membership_academy_datatable").DataTable({ 
        processing:     true,
        serverSide:     true,
        deferRender:    true,
        autoWidth:      true,
        filter:         false,
        responsive:     true,
        dataType: 'json',
        paginationType: "full_numbers",
        ajax: {
            url: '{{route('membership.academy.data.list')}}',
            type: 'POST',
            data: function ( d ) {
                d.name = $('#academy-search-form input[id="name"]').val();
                d.type = $('#academy-search-form select[id="search_type"]').val();
            },
        },
        columns: [
            {
                data: null,
                width: "30px",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'name', defaultContent: '' },
            { data: 'name_en', defaultContent: '' },
            { data: 'founded_year', defaultContent: '' },
            { data: 'sport_name', defaultContent: '' },
            { data: 'membership_type_name', defaultContent: '' },
            { data: 'membership_type_price', defaultContent: '' },
            { data: 'membership_start_date', defaultContent: '' },
            { data: 'membership_end_date', defaultContent: '' },
            { data: 'created_at', defaultContent: '' },
            { data: 'action', defaultContent: '' }
        ],
        columnDefs: [ 
        {
            searchable: false,
            orderable: false,
            targets: [0]
        },{
            class: "text-center",
            targets: [0,3,4,5,6,7,8,9]
        }],
        order: [[ 9, "desc" ]],
        dom: "<'top'B><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
        buttons: [
        {
            text: '<i class="la la-plus"></i> Шинээр нэмэх',
            className: "btn btn-light-danger font-weight-bolder mb-2 {{ SecurityHelper::checkPermission(@Config::get('permission.academy'), Config::get('permission.editable')) ? '' : 'd-none' }}",
            action: function ( e, dt, node, config ) {
                $.get('{!! route('membership.academy.list.create') !!}', showAddModal);
            }
        }]
	});

    $('#academy-search-form').on('submit', function(e) {
        academyTable.draw();
        e.preventDefault();
    });

    $("#kt_reset").click(function(e){
        e.preventDefault();
        $('.datatable-input').each(function() {
            $(this).val('');
            academyTable.column($(this).data('col-index')).search('', false, false);
            
            $("#search_type").val('').selectpicker("refresh");
        });
        academyTable.draw();
    });

    $('#membership_academy_datatable tbody').on( 'click', 'tr td a.edit', function () {
        var membershipId = $(this).data("membershipid");
        $.get('/membership/academy/list/'+membershipId+'/edit', showEditModal);
        
    });

    $('#membership_academy_datatable tbody').on( 'click', 'tr td a.delete', function () {
        var membershipId = $(this).data('membershipid');
        Swal.fire({
            title: '{{ trans('messages.info_confirm_delete_title') }}',
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: '{{ trans('display.general_confirm_button_yes') }}',
            cancelButtonText: '{{ trans('display.general_confirm_button_no') }}',
            customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: 'btn fw-bold btn-active-light-primary'
            },
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: '/membership/academy/list/'+membershipId,
                    type: 'POST',
                    success: function(response) {
                        if(response.status == 'success')
                        {                           
                            toastr.success(response.msg);
                            if(academyTable != undefined)
                            {
                                academyTable.draw();
                            }
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
                    async: false
                });
            }
        });
    });

}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

//Modal
function showAddModal( data ) {
    $('#membershipAcademyModal').modal();
    $('#membershipAcademyModal').on('shown.bs.modal', function(){
        $('#membershipAcademyModal .modal-content').html(data);
        $('.selectpicker').selectpicker();

        var currentYear = parseInt(moment().format("YYYY"), 10);

        $("#start_date").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 2015,
            maxYear: currentYear,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        $("#end_date").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 2015,
            maxYear: currentYear + 10,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        $('#create-membership-academy-list-form [name="academy_id"]').select2({
            width: '100%',
            dropdownAutoWidth: true,
            dropdownParent: $('#membershipAcademyModal'),
            placeholder: "-- {{ trans('display.general_select') }} --",
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{!! route('academy.search.sport') !!}',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        sport_id: $('#create-membership-academy-list-form select[name="sport_id"]').val(),
                        q: params.term || ''
                    };
                },
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            }
        });

        $('#create-membership-academy-list-form select[name="sport_id"]').on('change', function () {
            var academySelect = $('#create-membership-academy-list-form [name="academy_id"]');

            academySelect.val(null).trigger('change');
        
            if (!$(this).val()) {
                academySelect.prop('disabled', true);
                return;
            }
        
            academySelect.prop('disabled', false);
            setTimeout(function() {
                academySelect.select2('open');
            }, 100);
        });

        if (!$('#create-membership-academy-list-form select[name="sport_id"]').val()) {
            $('#create-membership-academy-list-form [name="academy_id"]').prop('disabled', true);
        }

        $('#create-membership-academy-list-form').validate({
            ignore: [],
            highlight:function(element) {
                $(element).parents('.form-group').addClass('has-error has-feedback');
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').removeClass('has-error');
            },
            submitHandler: function(form) {
                $(form).find(':submit').attr('data-kt-indicator', 'on').prop('disabled', true);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    dataType: 'json',
                    data:  new FormData(form),
                    success: function(response) {             
                        if(response.status == 'success')
                        {
                            $('#membershipAcademyModal').find("#close").trigger('click');
                            toastr.success( response.msg);
                            academyTable.draw();
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
                })
                .always(() => {
                    $(form).find(':submit').attr('data-kt-indicator', 'off').prop('disabled', false); 
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

        $(this).off('shown.bs.modal');
    });

    $('#membershipAcademyModal').on('hidden.bs.modal', function(){
        $('#membershipAcademyModal .modal-content').empty();
    });
}

function showEditModal(data){
    $('#membershipAcademyModal').modal();
        $('#membershipAcademyModal').on('shown.bs.modal', function(){
            $('#membershipAcademyModal .modal-content').html(data);
            $('.selectpicker').selectpicker();      

            var currentYear = new Date().getFullYear();

            var commonOptions = {
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: true,
                locale: {
                    format: 'YYYY-MM-DD',
                    applyLabel: "Сонгох",
                    cancelLabel: "Цуцлах",
                }
            };

            $("#start_date").daterangepicker({
                ...commonOptions,
                minYear: 2015,
                maxYear: currentYear + 5,
            });

            $("#end_date").daterangepicker({
                ...commonOptions,
                minYear: 2015,
                maxYear: currentYear + 10,
            });

            $('#start_date').on('apply.daterangepicker', function(ev, picker) {
                $('#end_date').data('daterangepicker').minDate = picker.startDate;

                if ($('#end_date').data('daterangepicker').startDate < picker.startDate) {
                    $('#end_date').val(picker.startDate.format('YYYY-MM-DD'));
                }
            });

            $('#edit-membership-academy-list-form').validate({
                ignore: [],
                highlight:function(element) {
                    $(element).parents('.form-group').addClass('has-error has-feedback');
                },
                unhighlight: function(element) {
                    $(element).parents('.form-group').removeClass('has-error');
                },
                submitHandler: function(form) {
                    $(form).find(':submit').attr('data-kt-indicator', 'on').prop('disabled', true);
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data:  new FormData(form),
                        success: function(response) {
                            if(response.status == 'success')
                            {
                                toastr.success(response.msg);
                                $('#membershipAcademyModal').find("#close").trigger('click');
                                if(academyTable != undefined)
                                {
                                    var page = academyTable.page.info().page;
                                    academyTable.page(page).draw('page');
                                }
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
                    })
                    .always(() => {
                        $(form).find(':submit').attr('data-kt-indicator', 'off').prop('disabled', false); 
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

        $(this).off('shown.bs.modal');
    });

    $('#membershipAcademyModal').on('hidden.bs.modal', function(){
        $('#membershipAcademyModal .modal-content').empty();
    });

}
</script>
@endsection
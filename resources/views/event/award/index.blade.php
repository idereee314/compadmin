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
                                    <h3 class="card-label">Шагналын жингийн жагсаалт 
                                    <span class="d-block text-muted pt-2 font-size-sm">{{ $event->name }}</span></h3>
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
                                                <form class="mb-10" id="award-search-form" method="POST">
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
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline" id="award_datatable" role="grid" aria-describedby="kt_datatable_info" style="width: 1235px;">
                                                <thead>
                                                    <tr>
                                                        <th>№</th>
                                                        <th width="5%">{{trans('display.general_manage')}}</th>
                                                        <th width="15%">{{trans('display.comp_entry')}}</th>
                                                        <th width="5%">{{trans('display.human_gender_code')}}</th>
                                                        <th width="10%">{{trans('display.age_title')}}</th>
                                                        <th width="20%">{{trans('display.comp_entry_belt')}}</th>
                                                        <th width="20%">{{trans('display.comp_entry_weight')}}</th>
                                                        <th width="20%">{{trans('display.weight_is_finish')}}</th>
                                                        <th width="5%">{{trans('display.weight_award_cermony')}}</th>
                                                        <!-- <th width="5%">{{trans('display.general_created_at')}}</th>                                                         -->
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
            @include ($view_path.'.modals')
            <!--end::Wrapper-->
        <!--end::Main-->
</section>

@section('javascript')
<script src="{{asset('assets/js/plugins/custom/datatables/datatables.js')}}"></script>
<script src="{{asset('assets/js/smart.js')}}"></script>
<script>
$(document).ready(function() {
    awardTable = $("#award_datatable").DataTable({
        processing:     true,
        serverSide:     true,
        deferRender:    true,
        autoWidth:      true,
        filter:         false,
        responsive:     true,
        dataType: 'json',
        paginationType: "full_numbers",
        ajax: {
            url: '{{ route("event.award.ceremony.datalist", ["eventId" => $eventId]) }}',
            type: 'POST',
            data: function ( d ) {
                d.name = $('#award-search-form input[id="name"]').val();
                d.type = $('#award-search-form select[id="search_type"]').val();
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
            {data: 'action'},
            { data: 'entry_name',   name: 'e.name' },
            { data: 'gender'},
            {
                data: function (row) {
                    if (row.start_age !== null && row.end_age !== null) {
                        return row.start_age + ' - ' + row.end_age;
                    }
                    return '';
                },
                name: 'a.start_age',
                orderable: true,
                searchable: false
            },
            { data: 'belt_name', name: 'b.name' },
            { data: 'weight_value', name: 'w.weight' },
            { data: 'is_finish', name: 'ed.is_finish' },
            { data: 'medal_given', name: 'ed.medal_given' },
            // { data: 'created_at', name: 'ed.created_at' },
        ],
        columnDefs: [ 
        {
            searchable: false,
            orderable: false,
            targets: [0]
        },{
            class: "text-center",
            targets: [0, 1,2,3,4,5,6,7,8]
        }],
       
        dom: "<'top'B><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
        buttons: [
        {
            text: '<i class="la la-plus"></i> Шинээр нэмэх',
            className: "btn btn-light-danger font-weight-bolder mb-2 {{ SecurityHelper::checkPermission(@Config::get('permission.award'), Config::get('permission.editable')) ? '' : 'd-none' }}",
            action: function ( e, dt, node, config ) {

            }
        }]
	});

    $('#award-search-form').on('submit', function(e) {
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

    $('#award_datatable tbody').on('click', 'tr td a.edit', function () {
        var divisionId = $(this).data('divisionid');
        var eventId = {{ $eventId }};

        $.get('/event/' + eventId + '/award/ceremony/' + divisionId + '/edit', showEditModal);
    });

}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

function showEditModal(data){
    $('#weightModal').modal();
        $('#weightModal').on('shown.bs.modal', function(){
            $('#weightModal .modal-content').html(data);

            $('#edit-award-ceremony').validate({
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
                                $('#weightModal').find("#close").trigger('click');
                                if(awardTable != undefined)
                                {
                                    var page = awardTable.page.info().page;
                                    awardTable.page(page).draw('page');
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

    $('#weightModal').on('hidden.bs.modal', function(){
        $('#weightModal .modal-content').empty();
    });

}

</script>
@endsection
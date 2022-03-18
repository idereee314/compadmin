@extends('default')

@section('styles')
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.css')}}">
{{-- <link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-3.5.3/css/select2.min.css')}}"> --}}
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
                                    <h3 class="card-label">Тэмцээний жагсаалт 
                                    <span class="d-block text-muted pt-2 font-size-sm">Тэмцээний бүртгэлийн хэсэг</span></h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Search Form-->
                                <form class="mb-15" id="event-registration-search-form" method="POST">
                                    <div class="row mb-6">
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>RecordID:</label>
                                            <input type="text" class="form-control datatable-input" placeholder="E.g: 4590" data-col-index="0" />
                                        </div>
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>OrderID:</label>
                                            <input type="text" class="form-control datatable-input" placeholder="E.g: 37000-300" data-col-index="1" />
                                        </div>
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>Country:</label>
                                            <select class="form-control datatable-input" data-col-index="2">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>Agent:</label>
                                            <input type="text" class="form-control datatable-input" placeholder="Agent ID or name" data-col-index="4" />
                                        </div>
                                    </div>
                                    <div class="row mb-8">
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>Ship Date:</label>
                                            <div class="input-daterange input-group" id="kt_datepicker">
                                                <input type="text" class="form-control datatable-input" name="start" placeholder="From" data-col-index="5" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-ellipsis-h"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control datatable-input" name="end" placeholder="To" data-col-index="5" />
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>Status:</label>
                                            <select class="form-control datatable-input" data-col-index="6">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>Type:</label>
                                            <select class="form-control datatable-input" data-col-index="7">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-8">
                                        <div class="col-lg-12">
                                        <button class="btn btn-primary btn-primary--icon" id="kt_search">
                                            <span>
                                                <i class="la la-search"></i>
                                                <span>Search</span>
                                            </span>
                                        </button>&#160;&#160;
                                        <button class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                                            <span>
                                                <i class="la la-close"></i>
                                                <span>Reset</span>
                                            </span>
                                        </button></div>
                                    </div>
                                </form>
                                <!--begin: Datatable-->
                                <div class="dataTables_wrapper dt-bootstrap4">
                                    <div class="panel-sub-heading">

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-separate table-head-custom" id="event-registration-datatable" style="margin-top: 13px !important">
                                                <thead>
                                                <tr>
													<th colspan="4">{{ trans('display.comp_member') }}</th>
													<th colspan="8">{{ trans('display.comp_title') }}</th>
												</tr>
                                                <tr>
                                                    <th width="15px">No.</th>
                                                    <th width="15%">{{trans('display.comp_event')}}</th>
                                                    <th width="5%">{{trans('display.human_register_number')}}</th>
                                                    <th width="15%">{{trans('display.human_name')}}</th>
                                                    <th width="8%">{{trans('display.human_phone_number')}}</th>
                                                    <th width="15%">{{trans('display.comp_entry')}}</th>
                                                    <th width="15%">{{trans('display.comp_entry_age')}}</th>
                                                    <th width="15%">{{trans('display.comp_entry_belt')}}</th>
                                                    <th width="15%">{{trans('display.comp_entry_weight')}}</th>
                                                    <th width="15%">{{trans('display.general_status')}}</th>
                                                    <th width="8%">{{trans('display.general_created_at')}}</th>
                                                    <th width="5%">{{trans('display.general_manage')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                    
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>{{trans('display.comp_event')}}</th>
                                                    <th>{{trans('display.human_register_number')}}</th>
                                                    <th>{{trans('display.human_name')}}</th>
                                                    <th>{{trans('display.human_phone_number')}}</th>
                                                    <th>{{trans('display.comp_entry')}}</th>
                                                    <th>{{trans('display.comp_entry_age')}}</th>
                                                    <th>{{trans('display.comp_entry_belt')}}</th>
                                                    <th>{{trans('display.comp_entry_weight')}}</th>
                                                    <th>{{trans('display.general_status')}}</th>
                                                    <th>{{trans('display.general_created_at')}}</th>
                                                    <th>{{trans('display.general_manage')}}</th>
                                                </tr>
                                                </tfoot>
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
<script src="{{asset('assets/js/plugins/custom/select2-ng/select2.min.js')}}"></script>
{{-- <script src="{{asset('assets/js/plugins/custom/select2-3.5.3/js/select2.min.js')}}"></script> --}}
<!--<script src="{{asset('assets/js/plugins/custom/select2-ng/select2.min.js')}}"></script>-->
<script src="{{asset('assets/js/smart.js')}}"></script>

<script>
$(document).ready(function() {
    $('.kt-selectpicker').selectpicker();
    eventTable = $("#event-registration-datatable").DataTable({
        processing:     true,
        serverSide:     true,
        deferRender:    true,
        autoWidth:      true,
        filter:         false,
        responsive:     false,
        dataType: 'json',
        paginationType: "full_numbers",
        ajax: {
            url: '{{route('event.registration.data.list')}}',
            type: 'POST',
            data: function ( d ) {
                d.name = $('#user-search-form input[id="name"]').val();
                d.email = $('#user-search-form input[id="email"]').val();
                d.role = $('#user-search-form select[id="role"]').val();
            },
        },
        drawCallback: function(settings) {
            var api = this.api();
            var rows = api.rows({page: 'current'}).nodes();
            var last = null;

            api.column(1, {page: 'current'}).data().each(function(group, i) {
                if (last !== group) {
                    $(rows).eq(i).before(
                        '<tr class="group"><td colspan="11">&nbsp;' + group + '</td></tr>',
                    );
                    last = group;
                }
            });
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                width: "30px"
            },
            {data: 'event.name'},
            {data: 'member.register_number'},
            {
                data: 'member', 
                render: function (data, type, row, meta) {
                    var name = data.lastname.substr(0,1) + '. ' + data.firstname;
                    return name;
                },
                "defaultContent": ""
            },
            {data: 'member.contact_phone'},
            {data: 'entry.name', "defaultContent": ""},
            {
                data: 'age',
                render: function (data, type, row, meta) {
                    var age;
                    if(data.end_age != null)
                    {
                        age = data.start_age + '-' + data.end_age;
                    }
                    else 
                    {
                        age = data.start_age + '+';
                    }
                    return age;
                }, "defaultContent": ""
            },
            {data: 'belt.name', "defaultContent": ""},
            {data: 'weight.weight', "defaultContent": ""},
            {data: 'status', "defaultContent": ""},
            {data: 'created_at'},
            {data: 'action'},
        ],
        columnDefs: [ 
        {
            // hide columns by index number
            targets: [1],
            visible: false,
        },
        {
            searchable: false,
            orderable: false,
            targets: [0]
        },{
            class: "text-center",
            targets: [0]
        }],
        order: [[ 10, "desc" ]],
        dom: "<'top'B><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
        buttons: [
        {
            text: '<i class="la la-plus"></i> Шинээр нэмэх',
            className: "btn btn-light-danger font-weight-bolder mb-2",
            action: function ( e, dt, node, config ) {
                $.get('{!! route('event.registration.create') !!}', showAddModal);
            }
        }]
	});

    $('#event-registration-search-form').on('submit', function(e) {
        eventTable.draw();
        e.preventDefault();
    });

    $('#event-registration-datatable tbody').on( 'click', 'tr td a.edit', function () {
        var id = $(this).data("registrationid");

        $.get('registration/'+id+'/edit', showEditModal);
    });

    $('#event-registration-datatable tbody').on( 'click', 'tr td a.delete', function () {
        var id = $(this).data("registrationid");

        Swal.fire({
            title: "Та устгахдаа итгэлтэй байна уу",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Тийм",
            cancelButtonText: 'Үгүй',
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: 'btn btn-secondary'
            },
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: 'registration/' + id,
                    type: 'DELETE',
                    success: function(response) {
                        $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                        eventTable.draw();
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

    $('#memberModal').modal();
    $('#memberModal').on('shown.bs.modal', function(){
        $('#memberModal .modal-content').html(data);
        $('#create-event-registration-form select[name=event_id]').select2({
            placeholder: "-- {{ trans('display.general_select') }} --"
        });

        $('#create-event-registration-form input[name=entry_id]').select2({data: ""});
        $('#create-event-registration-form input[name=entry_age_id]').select2({data: ""});
        $('#create-event-registration-form input[name=entry_belt_id]').select2({data: ""});
        $('#create-event-registration-form input[name=entry_weight_id]').select2({data: ""});

        $('#create-event-registration-form select[name=event_id]').on('change', function(){
            var eventId = $(this).val();
            var jsonData;

            $.ajax({
                type: 'POST',
                url: '{!! route('event.entry.by.event') !!}',
                data: {event_id: eventId},
                success: function (data) {
                    jsonData = JSON.parse(data);
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

            $('#create-event-registration-form input[name=entry_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: {results: jsonData, text: function (item) {
                    return item;
                }},
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                formatSelection: function (item) {
                return item.name;
                },
                formatResult: function (item) {
                    return item.name;
                }
            });

        });

        $('#create-event-registration-form input[name=entry_id]').on('change', function(){
            var entryId = $(this).val();
            var jsonDataAge;
            var jsonDataBelt;

            $.ajax({
                type: 'POST',
                url: '{!! route('event.entry.age.by.entry') !!}',
                data: {entry_id: entryId},
                success: function (data) {
                    jsonDataAge = JSON.parse(data);
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

            $.ajax({
                type: 'POST',
                url: '{!! route('event.entry.belt.by.entry') !!}',
                data: {entry_id: entryId},
                success: function (data) {
                    jsonDataBelt = JSON.parse(data);
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

            $('#create-event-registration-form input[name=entry_age_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: {results: jsonDataAge, text: function (item) {
                    return item;
                }},
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                formatSelection: function (item) {
                    return item.start_age + '-' + item.end_age;
                },
                formatResult: function (item) {
                    return item.start_age + '-' + item.end_age;
                }
            });

            $('#create-event-registration-form input[name=entry_belt_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: {results: jsonDataBelt, text: function (item) {
                    return item;
                }},
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                formatSelection: function (item) {
                    return item.name;
                },
                formatResult: function (item) {
                    return item.name;
                }
            });
        });

        $('#create-event-registration-form input[name=entry_age_id]').on('change', function(){
            var ageId = $(this).val();
            var jsonData;

            $.ajax({
                type: 'POST',
                url: '{!! route('event.entry.weight.by.age') !!}',
                data: {entry_age_id: ageId},
                success: function (data) {
                    jsonData = JSON.parse(data);
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

            $('#create-event-registration-form input[name=entry_weight_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: {results: jsonData, text: function (item) {
                    return item;
                }},
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                formatSelection: function (item) {
                return item.weight;
                },
                formatResult: function (item) {
                    return item.weight;
                }
            }); 
        });

        $('#create-event-registration-form input[name=member_id]').select2({
            width: 'resolve',
            dropdownAutoWidth : true,
            dropdownParent: $('#memberModal'),
            placeholder: "-- {{ trans('display.general_select') }} --",
            ajax: {
                type: 'GET',
                url: '{!! route('member.search') !!}',
                data: function (params) {
                    return {
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
            maximumSelectionLength: 30,
            minimumInputLength: 3,
            formatSelection: function (item) {
                return item.firstname + ": " + item.lastname;
            },
            formatResult: function (item) {
                return item.firstname + ": " + item.lastname;
            }
        });

        $('#create-event-registration-form').validate({
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
                        $('#memberModal').find("#close").trigger('click');
                        $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                        eventTable.draw();
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

        $('#create-event-registration-form select[name=event_id]').trigger('change');

        $(this).off('shown.bs.modal');
    });

    $('#memberModal').on('hidden.bs.modal', function(){
        $('#memberModal .modal-body').empty();
    });
}

function showEditModal(data){
    $('#memberModal').modal();
    $('#memberModal').on('shown.bs.modal', function(){
        $('#memberModal .modal-content').html(data);

        $('#update-event-registration-form select[name=event_id]').select2({
            placeholder: "-- {{ trans('display.general_select') }} --"
        });

        $('#update-event-registration-form input[name=entry_id]').select2({data: ""});
        $('#update-event-registration-form input[name=entry_age_id]').select2({data: ""});
        $('#update-event-registration-form input[name=entry_belt_id]').select2({data: ""});
        $('#update-event-registration-form input[name=entry_weight_id]').select2({data: ""});

        $('#update-event-registration-form select[name=event_id]').on('change', function(){
            var eventId = $(this).val();
            var jsonData;

            $.ajax({
                type: 'POST',
                url: '{!! route('event.entry.by.event') !!}',
                data: {event_id: eventId},
                success: function (data) {
                    jsonData = JSON.parse(data);
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

            $('#update-event-registration-form input[name=entry_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: {results: jsonData, text: function (item) {
                    return item;
                }},
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                formatSelection: function (item) {
                return item.name;
                },
                formatResult: function (item) {
                    return item.name;
                }
            });
        });

        $('#update-event-registration-form select[name=event_id]').trigger('change');
        $('#update-event-registration-form select[name=entry_id]').trigger('change');

        $('#update-event-registration-form input[name=entry_id]').on('change', function(){
            var entryId = $(this).val();
            var jsonDataAge;
            var jsonDataBelt;

            console.log(jsonDataAge)

            $.ajax({
                type: 'POST',
                url: '{!! route('event.entry.age.by.entry') !!}',
                data: {entry_id: entryId},
                success: function (data) {
                    jsonDataAge = JSON.parse(data);
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

            $.ajax({
                type: 'POST',
                url: '{!! route('event.entry.belt.by.entry') !!}',
                data: {entry_id: entryId},
                success: function (data) {
                    jsonDataBelt = JSON.parse(data);
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

            $('#update-event-registration-form input[name=entry_age_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: {results: jsonDataAge, text: function (item) {
                    return item;
                }},
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                formatSelection: function (item) {
                    return item.start_age + '-' + item.end_age;
                },
                formatResult: function (item) {
                    return item.start_age + '-' + item.end_age;
                }
            });

            $('#update-event-registration-form input[name=entry_belt_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: {results: jsonDataBelt, text: function (item) {
                    return item;
                }},
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                formatSelection: function (item) {
                    return item.name;
                },
                formatResult: function (item) {
                    return item.name;
                }
            });
        });
       
        $('#update-event-registration-form select[name=entry_age_id]').trigger('change');
        $('#update-event-registration-form select[name=entry_belt_id]').trigger('change');

        $('#update-event-registration-form input[name=entry_age_id]').on('change', function(){
            var ageId = $(this).val();
            var jsonData;

            $.ajax({
                type: 'POST',
                url: '{!! route('event.entry.weight.by.age') !!}',
                data: {entry_age_id: ageId},
                success: function (data) {
                    jsonData = JSON.parse(data);
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

            $('#update-event-registration-form input[name=entry_weight_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: {results: jsonData, text: function (item) {
                    return item;
                }},
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                formatSelection: function (item) {
                return item.weight;
                },
                formatResult: function (item) {
                    return item.weight;
                }
            }); 
        });

        $('#update-event-registration-form input[name=member_id]').select2({
            width: 'resolve',
            dropdownAutoWidth : true,
            dropdownParent: $('#memberModal'),
            placeholder: "-- {{ trans('display.general_select') }} --",
            ajax: {
                type: 'GET',
                url: '{!! route('member.search') !!}',
                data: function (params) {
                    return {
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
            maximumSelectionLength: 30,
            minimumInputLength: 3,
            formatSelection: function (item) {
                return item.firstname + ": " + item.lastname;
            },
            formatResult: function (item) {
                return item.firstname + ": " + item.lastname;
            }
        });

        $('#update-event-registration-form').validate({
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
                        $('#memberModal').find("#close").trigger('click');
                        $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                        eventTable.draw();
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

       
        // $('#update-event-registration-form select[name=entry_age_id]').trigger('change');
        // $('#update-event-registration-form select[name=entry_belt_id]').trigger('change');
        // $('#update-event-registration-form select[name=entry_weight_id]').trigger('change');
        // $('#update-event-registration-form select[name=member_id]').trigger('change');

        $(this).off('shown.bs.modal');
    });
}
</script>
@endsection
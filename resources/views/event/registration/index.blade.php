@extends('default')

@section('styles')
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-4.1.0/css/select2.min.css')}}">
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
                                            <label>{{ trans('display.comp_title') }}:</label>
                                            <select class="form-control datatable-input" name="search_event" id="search_event" data-col-index="0">
                                                <option value="">-- {{ trans('display.general_all') }} --</option>
                                                @forelse(@$competitions as $competition)
                                                <option value="{{ $competition->event_id }}">{{ $competition->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>{{ trans('display.comp_entry') }}:</label>
                                            <select class="form-control datatable-input" name="search_entry" id="search_entry" data-col-index="1">
                                                <option value="">-- {{ trans('display.general_all') }} --</option>
                                                @forelse(@$eventEntries as $eventEntry)
                                                <option value="{{ $eventEntry->id }}">{{ $eventEntry->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>{{ trans('display.comp_entry_age') }}:</label>
                                            <select class="form-control datatable-input" name="search_entry_age" id="search_entry_age" data-col-index="2">
                                                <option value="">-- {{ trans('display.general_all') }} --</option>
                                                @forelse(@$configAges as $age)
                                                <option value="{{ $age->id }}">{{ $age->start_age }}-{{ $age->end_age }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>{{ trans('display.comp_entry_belt') }}:</label>
                                            <select class="form-control datatable-input" name="search_entry_belt" id="search_entry_belt" data-col-index="3">
                                                <option value="">-- {{ trans('display.general_all') }} --</option>
                                                @forelse(@$configBelts as $belt)
                                                <option value="{{ $belt->id }}">{{ $belt->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-8">
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>{{ trans('display.comp_entry_weight') }}:</label>
                                            <select class="form-control datatable-input" name="search_entry_weight" id="search_entry_weight" data-col-index="4">
                                                <option value="">-- {{ trans('display.general_all') }} --</option>
                                                @forelse(@$configWeights as $weight)
                                                <option value="{{ $weight->id }}">{{ $weight->weight }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>Оролцогч:</label>
                                            <input type="text" class="form-control datatable-input" name="member" id="member" placeholder="Оролцогчийн мэдээллээр хайх" data-col-index="5" />
                                        </div>
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>{{ trans('display.general_date') }}:</label>
                                            <div class="input-daterange input-group" id="kt_datepicker">
                                                <input type="text" class="form-control datatable-input" name="start" placeholder="From" data-col-index="7" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-ellipsis-h"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control datatable-input" name="end" placeholder="To" data-col-index="7" />
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>{{ trans('display.general_status') }}:</label>
                                            <select class="form-control datatable-input" name="search_status" id="search_status" data-col-index="8">
                                                <option value="">-- {{ trans('display.general_all') }} --</option>
                                                @forelse(@Config::get('enums.event_registeation_status') as $key => $status)
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
                                            <button type="reset" class="btn btn-secondary btn-secondary--icon">
                                                <span>
                                                    <i class="la la-close"></i>
                                                    <span>{{ trans('display.general_reset') }}</span>
                                                </span>
                                            </button>
                                        </div>
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
<script src="{{asset('assets/js/plugins/custom/select2-4.1.0/js/select2.min.js')}}"></script>
<!--<script src="{{asset('assets/js/plugins/custom/select2-ng/select2.min.js')}}"></script>-->
<script src="{{asset('assets/js/smart.js')}}"></script>

<script>
$(document).ready(function() {
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
                d.event = $('#event-registration-search-form select[id="search_event"]').val();
                d.entry = $('#event-registration-search-form select[id="search_entry"]').val();
                d.entryAge = $('#event-registration-search-form select[id="search_entry_age"]').val();
                d.entryBelt = $('#event-registration-search-form select[id="search_entry_belt"]').val();
                d.entryWeight = $('#event-registration-search-form select[id="search_entry_weight"]').val();
                d.status = $('#event-registration-search-form select[id="search_status"]').val();
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

    $('#event-registration-search-form select[name=search_event]').on('change', function(){
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

        $('#event-registration-search-form select[name=search_entry]').select2({
            placeholder: "-- {{ trans('display.general_all') }} --",
            data: jsonData,
            id: 'id',
            closeOnSelect: true,
            allowClear: true,
            templateSelection: function (item) {
                return item.name;
            },
            templateResult: function (item) {
                return item.name;
            }
        });

        $('#event-registration-search-form select[name=search_entry_age]').select2({data: ""});
        $('#event-registration-search-form select[name=search_entry_belt]').select2({data: ""});
        $('#event-registration-search-form select[name=search_entry_weight]').select2({data: ""});
    });

    $('#event-registration-search-form select[name=search_entry]').on('change', function(){
        var entryId = $(this).val();
        var jsonConfig;
        var jsonDataAge;
        var jsonDataBelt;

        $.ajax({
            type: 'POST',
            url: '{!! route('event.registration.take.config') !!}',
            data: {entry_id: entryId},
            success: function (data) {
                jsonConfig = JSON.parse(data);
                jsonDataAge = jsonConfig['age'];
                jsonDataBelt = jsonConfig['belt'];
            },
            error: function (xhr, textStatus, error) {
                console.log(xhr.statusText);
                console.log(textStatus);
                console.log(error);
            },
            async: false
        });

        $('#event-registration-search-form select[name=search_entry_age]').select2({
            placeholder: "-- {{ trans('display.general_all') }} --",
            data: jsonDataAge,
            id: 'id',
            closeOnSelect: true,
            allowClear: true,
            templateSelection: function (item) {
                return item.age;
            },
            templateResult: function (item) {
                return item.age;
            }
        });

        $('#event-registration-search-form select[name=search_entry_belt]').select2({
            placeholder: "-- {{ trans('display.general_all') }} --",
            data: jsonDataBelt,
            id: 'id',
            closeOnSelect: true,
            allowClear: true,
            templateSelection: function (item) {
                return item.name;
            },
            templateResult: function (item) {
                return item.name;
            }
        });

        $('#event-registration-search-form select[name=search_entry_weight]').select2({data: ""});
    });

    $('#event-registration-search-form select[name=search_entry_age]').on('change', function(){
        var ageId = $(this).val();
        var jsonDataWeight;

        $.ajax({
            type: 'POST',
            url: '{!! route('event.entry.weight.by.age') !!}',
            data: {entry_age_id: ageId},
            success: function (data) {
                jsonDataWeight = JSON.parse(data);
            },
            error: function (xhr, textStatus, error) {
                console.log(xhr.statusText);
                console.log(textStatus);
                console.log(error);
            },
            async: false
        });

        $('#event-registration-search-form select[name=search_entry_weight]').select2({
            placeholder: "-- {{ trans('display.general_all') }} --",
            data: jsonDataWeight,
            id: 'id',
            closeOnSelect: true,
            allowClear: true,
            templateSelection: function (item) {
                return item.weight;
            },
            templateResult: function (item) {
                return item.weight;
            }
        }); 
    });

    $(".reset").click(function(){
        $('#event-registration-search-form select.chosen-select').val([]).trigger('chosen:updated');
        $(':input', '#event-registration-search-form')
         .not(':button, :submit, :reset')
         .val('')
         .removeAttr('checked')
         .removeAttr('selected')
         .attr('value', '');

        $('#event-registration-search-form')[0].reset();
    });

}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

//Modal
function showAddModal( data ) {

    $('#memberModal').modal();
    $('#memberModal').on('shown.bs.modal', function(){
        $('#memberModal .modal-content').html(data);
        $('#create-event-registration-form select[name=member_id]').select2();
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
                data: jsonData,
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                templateSelection: function (item) {
                    return item.name;
                },
                templateResult: function (item) {
                    return item.name;
                }
            });

            $('#create-event-registration-form input[name=entry_age_id]').select2({data: ""});
            $('#create-event-registration-form input[name=entry_belt_id]').select2({data: ""});
            $('#create-event-registration-form input[name=entry_weight_id]').select2({data: ""});
        });

        $('#create-event-registration-form input[name=entry_id]').on('change', function(){
            var entryId = $(this).val();
            var jsonConfig;
            var jsonDataAge;
            var jsonDataBelt;

            $.ajax({
                type: 'POST',
                url: '{!! route('event.registration.take.config') !!}',
                data: {entry_id: entryId},
                success: function (data) {
                    jsonConfig = JSON.parse(data);
                    jsonDataAge = jsonConfig['age'];
                    jsonDataBelt = jsonConfig['belt'];
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
                data: jsonDataAge,
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                templateSelection: function (item) {
                    return item.age;
                },
                templateResult: function (item) {
                    return item.age;
                }
            });

            $('#create-event-registration-form input[name=entry_belt_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: jsonDataBelt,
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                templateSelection: function (item) {
                    return item.name;
                },
                templateResult: function (item) {
                    return item.name;
                }
            });

            $('#create-event-registration-form input[name=entry_weight_id]').select2({data: ""});
        });

        $('#create-event-registration-form input[name=entry_age_id]').on('change', function(){
            var ageId = $(this).val();
            var jsonDataWeight;

            $.ajax({
                type: 'POST',
                url: '{!! route('event.entry.weight.by.age') !!}',
                data: {entry_age_id: ageId},
                success: function (data) {
                    jsonDataWeight = JSON.parse(data);
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
                data: jsonDataWeight,
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                templateSelection: function (item) {
                    return item.weight;
                },
                templateResult: function (item) {
                    return item.weight;
                }
            }); 
        });
        
        $('#create-event-registration-form select[name=member_id]').select2({
            width: 'resolve',
            dropdownAutoWidth : true,
            dropdownParent: $('#memberModal'),
            placeholder: "-- {{ trans('display.general_select') }} --",
            minimumInputLength: 3,
            ajax: {
                url: '{!! route('member.search') !!}',
                delay: 1500,
                data: function (params) {
                    var query = {
                        q: params.term
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: JSON.parse(data)
                    };
                },
                cache: true
            },
            templateSelection: function (item) {
//                return item.firstname || item.lastname;
                return item.firstname + ": " + item.lastname;
            },
            templateResult: function (item) {
//                return item.firstname || item.lastname;
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

        $('#update-event-registration-form select[name=entry_id]').select2({
            placeholder: "-- {{ trans('display.general_select') }} --"
        });
        $('#update-event-registration-form select[name=entry_age_id]').select2({
            placeholder: "-- {{ trans('display.general_select') }} --"
        });
        $('#update-event-registration-form select[name=entry_belt_id]').select2({
            placeholder: "-- {{ trans('display.general_select') }} --"
        });
        $('#update-event-registration-form select[name=entry_weight_id]').select2({
            placeholder: "-- {{ trans('display.general_select') }} --"
        });

        $('#update-event-registration-form select[name=entry_id]').on('change', function(){
            var entryId = $(this).val();
            var jsonDataConfig;
            var jsonDataBelt;
            var jsonDataAge;

            $.ajax({
                type: 'POST',
                url: '{!! route('event.registration.take.config') !!}',
                data: {entry_id: entryId},
                success: function (data) {
                    jsonDataConfig = JSON.parse(data);
                    jsonDataBelt = jsonDataConfig['belt'];
                    jsonDataAge = jsonDataConfig['age'];
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

            $('#update-event-registration-form select[name=entry_belt_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: jsonDataBelt,
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                templateSelection: function (item) {
                    return item.name;
                },
                templateResult: function (item) {
                    return item.name;
                }
            });

            $('#update-event-registration-form select[name=entry_age_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data:jsonDataAge,
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                templateSelection: function (item) {
                    return item.age;
                },
                templateResult: function (item) {
                    return item.age;
                }
            });

            $('#update-event-registration-form select[name=entry_weight_id]').select({data: ''});
        });

        $('#update-event-registration-form select[name=entry_age_id]').on('change', function(){
            var ageId = $(this).val();
            var jsonDataWeight;

            $.ajax({
                type: 'POST',
                url: '{!! route('event.entry.weight.by.age') !!}',
                data: {entry_age_id: ageId},
                success: function (data) {
                    jsonDataWeight = JSON.parse(data);
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

            $('#update-event-registration-form select[name=entry_weight_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: jsonDataWeight,
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                templateSelection: function (item) {
                    return item.weight;
                },
                templateResult: function (item) {
                    return item.weight;
                }
            }); 
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

        $(this).off('shown.bs.modal');
    });
}
</script>
@endsection
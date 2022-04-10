@extends('default')

@section('css')
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
                                    <h3 class="card-label">Тэмцээний тохиргоо 
                                    <span class="d-block text-muted pt-2 font-size-sm">Тэмцээний тохиргооны хэсэг</span></h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Items-->
                                {{-- <div class="d-flex align-items-center flex-wrap pb-5 border-bottom">
                                    <!--begin: Item-->
                                    <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                                        <span class="mr-4">
                                            <i class="flaticon-file-2 icon-2x text-muted font-weight-bold"></i>
                                        </span>
                                        <div class="d-flex flex-column flex-lg-fill">
                                            <span class="text-dark-75 font-weight-bolder font-size-sm">{{ array_sum(@$eventRegStatusCount) }} {{ trans('display.general_all') }}</span>
                                            <a href="javascript:;" class="text-primary font-weight-bolder filter-status-count" data-status="">Харах</a>
                                        </div>
                                    </div>
                                    <!--end: Item-->
                                    @forelse(@$eventRegStatusCount as $key => $count)
                                    <!--begin: Item-->
                                    <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                                        <span class="mr-4">
                                            <i class="flaticon-file-2 icon-2x text-muted font-weight-bold"></i>
                                        </span>
                                        <div class="d-flex flex-column flex-lg-fill">
                                            <span class="text-dark-75 font-weight-bolder font-size-sm">{{ $count }} {{ @Config::get('enums.event_registeation_status')[$key] }}</span>
                                            <a href="javascript:;" class="text-primary font-weight-bolder filter-status-count" data-status="{{ $key }}">Харах</a>
                                        </div>
                                    </div>
                                    <!--end: Item-->
                                    @empty
                                    @endforelse
                                </div> --}}
                                <!--begin: Items-->
                                <!--begin::Accordion-->
                                {{-- <div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle" id="search">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title collapsed" data-toggle="collapse" data-target="#search-registration">
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
                                        <div id="search-registration" class="collapse" data-parent="#search">
                                            <div class="card-body">
                                                <!--begin: Search Form-->
                                                <form class="mb-10" id="event-registration-search-form" method="POST">
                                                    <div class="row mb-6">
                                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                                            <label>{{ trans('display.comp_title') }}:</label>
                                                            <select class="form-control selectpicker datatable-input" name="search_event" id="search_event" data-col-index="0">
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
                                                                <option value="{{ $eventEntry->id }}">{{ $eventEntry->name }} - {{ @Config::get('enums.gender_code')[$eventEntry->gender_code] }}</option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                                            <label>{{ trans('display.comp_entry_age') }}:</label>
                                                            <select class="form-control datatable-input" name="search_entry_age" id="search_entry_age" data-col-index="2">
                                                                <option value="">-- {{ trans('display.general_all') }} --</option>
                                                                @forelse(@$configAges as $age)
                                                                <option value="{{ $age->id }}">{{ $age->start_age }}-{{ $age->end_age }}</option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                                            <label>{{ trans('display.comp_entry_belt') }}:</label>
                                                            <select class="form-control datatable-input" name="search_entry_belt" id="search_entry_belt" data-col-index="3">
                                                                <option value="">-- {{ trans('display.general_all') }} --</option>
                                                                @forelse(@$configBelts as $belt)
                                                                <option value="{{ $belt->id }}">{{ $belt->name }}</option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                                            <label>{{ trans('display.comp_entry_weight') }}:</label>
                                                            <select class="form-control datatable-input" name="search_entry_weight" id="search_entry_weight" data-col-index="4">
                                                                <option value="">-- {{ trans('display.general_all') }} --</option>
                                                                @forelse(@$configWeights as $weight)
                                                                <option value="{{ $weight->id }}">{{ $weight->weight }}</option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-8">
                                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                                            <label>{{ trans('display.comp_academy') }}:</label>
                                                            <select class="form-control selectpicker datatable-input" data-live-search="true" name="search_academy" id="search_academy" data-col-index="5">
                                                                <option value="">-- {{ trans('display.general_all') }} --</option>
                                                                @forelse(@$academies as $academy)
                                                                <option value="{{ $academy->id }}">{{ $academy->name }}</option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                                            <label>{{ trans('display.general_date') }}:</label>
                                                            <div class="input-daterange input-group" id="kt_datepicker">
                                                                <input type="text" class="form-control datatable-input" name="search_date[]" id="start" placeholder="From" data-col-index="7" />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-ellipsis-h"></i>
                                                                    </span>
                                                                </div>
                                                                <input type="text" class="form-control datatable-input" name="search_date[]" id="end" placeholder="To" data-col-index="7" />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                                            <label>Оролцогч:</label>
                                                            <input type="text" class="form-control datatable-input" name="search_member" id="search_member" placeholder="Оролцогчийн мэдээллээр хайх" data-col-index="8"/>
                                                        </div>
                                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                                            <label>Жин шалгасан эсэх:</label>
                                                            <select class="form-control selectpicker datatable-input" name="search_is_weight" id="search_is_weight" data-col-index="9">
                                                                <option value="">-- {{ trans('display.general_all') }} --</option>
                                                                @forelse(@Config::get('enums.boolean_type') as $key => $type)
                                                                <option value="{{ $key }}">{{ $type }}</option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                                            <label>{{ trans('display.general_status') }}:</label>
                                                            <select class="form-control selectpicker datatable-input" name="search_status" id="search_status" data-col-index="10">
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
                                </div> --}}
                                <!--end::Accordion-->
                                <!--begin: Datatable-->
                                <div class="dataTables_wrapper dt-bootstrap4">
                                    <div class="panel-sub-heading">

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-separate table-head-custom" id="event-config-datatable">
                                                <thead>
                                                <tr>
                                                    <th width="5%">No.</th>
                                                    <th width="35%">{{trans('display.general_name')}}</th>
                                                    <th width="15%">{{trans('display.reg_start_date')}}</th>
                                                    <th width="15%">{{trans('display.reg_end_date')}}</th>
                                                    <th width="15%">{{trans('display.general_created_at')}}</th>
                                                    <th width="15%">{{trans('display.general_manage')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
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
<script src="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.js')}}"></script>

<script>
$(document).ready(function() {
    eventConfigTable = $("#event-config-datatable").DataTable({
        processing:     true,
        serverSide:     true,
        //deferRender:    true,
        //autoWidth:      true,
        //filter:         false,
        responsive:     true,
        dataType: 'json',
        paginationType: "full_numbers",
        ajax: {
            url: '{{route('event.config.data.list')}}',
            type: 'POST',
            data: function ( d ) {
                var dateArr = {};
                $('#event-registration-search-form input[name^="search_date"]').map(function(){
                    dateArr[this.id] = this.value;
                }).get();
                d.event = $('#event-registration-search-form select[id="search_event"]').val();
            },
        },
        // drawCallback: function(settings) {
        //     var api = this.api();
        //     var rows = api.rows({page: 'current'}).nodes();
        //     var last = null;

        //     api.column(1, {page: 'current'}).data().each(function(group, i) {
        //         if (last !== group) {
        //             $(rows).eq(i).before(
        //                 '<tr class="group"><td colspan="14">&nbsp;' + group + '</td></tr>',
        //             );
        //             last = group;
        //         }
        //     });
        // },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                width: "30px"
            },
            {data: 'event.name', "defaultContent": ""},
            {data: 'reg_start_date'},
            {data: 'reg_end_date'},
            {data: 'created_at'},
            {data: 'action'},
        ],
        columnDefs: [ 
        {
            // hide columns by index number
            // targets: [1],
            // visible: false,
        },
        {
            searchable: false,
            orderable: false,
            targets: [0,1,2,3,4,5]
        },{
            class: "text-center",
            targets: [0,2,3,4,5]
        }],
        order: [[ 4, "desc" ]],
        dom: "<'row'<'col-sm-6 text-left'B><'col-sm-6 text-right'<'#colvis'>>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
        buttons: [
            {
                text: '<i class="la la-plus"></i> Шинээр нэмэх',
                className: "btn btn-light-danger font-weight-bolder mb-2",
                action: function ( e, dt, node, config ) {
                    $.get('{!! route('event.config.create') !!}', showAddModal);
                }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-print"></i> {!! trans('display.general_excel') !!}',
                className: "btn btn-light-warning font-weight-bolder mb-2",
                title: 'Оролцогчийн жагсаалт',
                customize: function ( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('c[r=A1] t', sheet).text( 'Тэмцээнд оролцогчид' );
                },
                exportOptions: {
                    columns: [ 0,2,3,4,5,6,7,8,9],
                    modifier: {
                        order: 'current',
                        page: 'all',
                        focused: undefined,
                        selected: undefined
                    }
                }
            },
        ]
	});

    $('#kt_datepicker').datepicker({
        todayHighlight: true,
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>',
        },
    });

    // $('#event-registration-search-form').on('submit', function(e) {
    //     eventConfigTable.draw();
    //     e.preventDefault();
    // });

    // $('#event-config-datatable tbody').on( 'click', 'tr td a.edit', function () {
    //     var id = $(this).data("configid");

    //     $.get('config/'+id+'/edit', showEditModal);
    // });

    $('#event-config-datatable tbody').on( 'click', 'tr td a.delete', function () {
        var id = $(this).data("configid");

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
                    url: 'config/' + id,
                    type: 'DELETE',
                    success: function(response) {
                        $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                        eventConfigTable.draw();
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

    $('#event-config-datatable tbody').on( 'click', 'tr td a.copy', function () {
        var id = $(this).data("configid");

        $.get('config/copy/create/'+id, showCopyModal);

        // Swal.fire({
        //     title: "Та хуулахдаа итгэлтэй байна уу",
        //     icon: "warning",
        //     showCancelButton: true,
        //     confirmButtonText: "Тийм",
        //     cancelButtonText: 'Үгүй',
        //     customClass: {
        //         confirmButton: "btn btn-primary",
        //         cancelButton: 'btn btn-secondary'
        //     },
        // }).then(function(result) {
        //     if (result.value) {
        //         $.ajax({
        //             url: 'config/copy/' + id,
        //             type: 'POST',
        //             success: function(response) {
        //                 $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
        //                 eventConfigTable.draw();
        //             },
        //             error: function (xhr, textStatus, error) {
        //                 console.log(xhr.statusText);
        //                 console.log(textStatus);
        //                 console.log(error);
        //             },
        //             async: false
        //         });
        //     }
        // });
    });

    $("#kt_reset").click(function(e){
        e.preventDefault();
        $('.datatable-input').each(function() {
            $(this).val('');
            eventConfigTable.column($(this).data('col-index')).search('', false, false);
        });
        eventConfigTable.draw();
    });

}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

//Modal
function showAddModal( data ) {

    $('#eventConfigModal').modal();
    $('#eventConfigModal').on('shown.bs.modal', function(){
        $('#eventConfigModal .modal-content').html(data);
        $('.selectpicker').selectpicker();
        $('#reg_start_date').datetimepicker();
        $('#reg_end_date').datetimepicker();

        $('#create-event-config-form select[name=event_id]').select2();

        $('#create-event-config-form select[name=event_id]').select2({
            width: 'resolve',
            dropdownAutoWidth : true,
            dropdownParent: $('#eventConfigModal'),
            placeholder: "-- {{ trans('display.general_select') }} --",
            minimumInputLength: 3,
            ajax: {
                url: '{!! route('event.search') !!}',
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
                return item.name;
            },
            templateResult: function (item) {
                return item.name;
            }
        });

        $('#create-event-config-form').validate({
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
                        $('#eventConfigModal').find("#close").trigger('click');
                        $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                        eventConfigTable.draw();
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

    $('#eventConfigModal').on('hidden.bs.modal', function(){
        $('#eventConfigModal .modal-content').empty();
    });
}

function showCopyModal(data){
    $('#eventConfigModal').modal();
    $('#eventConfigModal').on('shown.bs.modal', function(){
        $('#eventConfigModal .modal-content').html(data);
        $('.selectpicker').selectpicker();

        $('#reg_start_date').datetimepicker();
        $('#reg_end_date').datetimepicker();

        $('#create-event-config-copy-form select[name=event_id]').select2();

        $('#create-event-config-copy-form select[name=event_id]').select2({
            width: 'resolve',
            dropdownAutoWidth : true,
            dropdownParent: $('#eventConfigModal'),
            placeholder: "-- {{ trans('display.general_select') }} --",
            minimumInputLength: 3,
            ajax: {
                url: '{!! route('event.search') !!}',
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
                return item.name;
            },
            templateResult: function (item) {
                return item.name;
            }
        });

        $('#create-event-config-copy-form').validate({
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
                        var page = eventConfigTable.page.info().page;
                        $('#eventConfigModal').find("#close").trigger('click');
                        $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                        eventConfigTable.page(page).draw('page');
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

    $('#eventConfigModal').on('hidden.bs.modal', function(){
        $('#eventConfigModal .modal-content').empty();
    });
}

</script>
@endsection
@stop
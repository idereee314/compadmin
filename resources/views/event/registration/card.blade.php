@extends('default')

@section('css')
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
                        <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Тэмцээнүүд</h5>
                        <!--end::Title-->
                        <!--begin::Separator-->
                        <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>
                        <!--end::Separator-->
                        <!--begin::Search Form-->
                        <div class="d-flex align-items-center" id="kt_subheader_search">
                            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Нийт {{ @$pagination->total() }}</span>
                        </div>
                        <!--end::Search Form-->
                    </div>
                    <!--end::Details-->
                </div>
            </div>
            <!--end::Subheader-->
            <!--begin::Entry-->
            <div class="d-flex flex-column-fluid">
                <!--begin::Container-->
                <div class="container">
                    <!--begin::Row-->
                    @foreach(array_chunk(@$events, 2) as $chunk)
                    <div class="row">
                    @foreach(@$chunk as $event)
                        <div class="col-xl-6">
                            <!--begin::Card-->
                            <div class="card card-custom gutter-b card-stretch">
                                <!--begin::Body-->
                                <div class="card-body">
                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center">
                                        <!--begin::Pic-->
                                        <div class="flex-shrink-0 mr-4 symbol symbol-65 symbol-circle">
                                            <img src="{{ \Storage::disk('s3')->url(@$event['pictures_mobile_cover'][0]['dir_url'].'/thumbnail/'.@$event['pictures_mobile_cover'][0]['url']) }}" alt="image" />
                                        </div>
                                        <!--end::Pic-->
                                        <!--begin::Info-->
                                        <div class="d-flex flex-column mr-auto">
                                            <!--begin: Title-->
                                            <a href="#" class="card-title text-hover-primary font-weight-bolder font-size-h5 text-dark mb-1">{{ @$event['name'] }}</a>
                                            <span class="text-muted font-weight-bold">{{ Carbon\Carbon::parse(@$event['event_date'])->format('Y M d') }} - {{ Carbon\Carbon::parse(@$event['due_date'])->format('Y M d') }}</span>
                                            <!--end::Title-->
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Section-->
                                    <!--begin::Content-->
                                    <div class="d-flex flex-wrap mt-14">
                                        <div class="mr-12 d-flex flex-column mb-7">
                                            <span class="d-block font-weight-bold mb-4">Эхлэх</span>
                                            <span class="btn btn-light-primary btn-sm font-weight-bold btn-upper btn-text">{{ Carbon\Carbon::parse(@$event['reg_start_date'])->format('y M, d') }}</span>
                                        </div>
                                        <div class="mr-12 d-flex flex-column mb-7">
                                            <span class="d-block font-weight-bold mb-4">Дуусах</span>
                                            <span class="btn btn-light-danger btn-sm font-weight-bold btn-upper btn-text">{{ Carbon\Carbon::parse(@$event['reg_end_date'])->format('y M, d') }}</span>
                                        </div>
                                        <!--begin::Progress-->
                                        <div class="flex-row-fluid mb-7">
                                            <span class="d-block font-weight-bold mb-4">Бүртгэлийн явц</span>
                                            <div class="d-flex align-items-center pt-2">
                                                <div class="progress progress-xs mt-2 mb-2 w-100">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ round(@$event['status_approved'] ? @$event['status_approved'] / @$event['registration_count'] * 100 : 0) }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="ml-3 font-weight-bolder">{{ round(@$event['status_approved'] ? @$event['status_approved'] / @$event['registration_count'] * 100 : 0) }}%</span>
                                            </div>
                                        </div>
                                        <!--end::Progress-->
                                    </div>
                                    <!--end::Content-->
                                    <!--begin::Text-->
                                    <p class="mb-7 mt-3">{{ Str::words(strip_tags(@$event['description']), 20, '...') }}</p>
                                    <!--end::Text-->
                                    <!--begin::Blog-->
                                    <div class="d-flex flex-wrap">
                                        <!--begin: Item-->
                                        <div class="mr-12 d-flex flex-column mb-7">
                                            <span class="font-weight-bolder mb-4">{{ @Config::get('enums.gender_code')[2] }}</span>
                                            <span class="font-weight-bolder font-size-h5 pt-1">
                                            <span class="font-weight-bold text-dark-50"><i class="icon-md fas fa-female"></i></span> {{ @$event['members'] ? array_count_values(array_column(@$event['members'], 'gender_code'))[2] : '' }}</span>
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Item-->
                                        <div class="mr-12 d-flex flex-column mb-7">
                                            <span class="font-weight-bolder mb-4">{{ @Config::get('enums.gender_code')[1] }}</span>
                                            <span class="font-weight-bolder font-size-h5 pt-1">
                                            <span class="font-weight-bold text-dark-50"><i class="icon-md fas fa-male"></i></span> {{ @$event['members'] ? array_count_values(array_column(@$event['members'], 'gender_code'))[1] : '' }}</span>
                                        </div>
                                        <!--end::Item-->
                                        <!--begin::Item-->
                                        <div class="d-flex flex-column flex-lg-fill float-left mb-7">
                                            <span class="font-weight-bolder mb-4">Оролцогч</span>
                                            <div class="symbol-group symbol-hover">
                                                @php $count = 0; @endphp
                                                @forelse(@$event['members'] as $member)
                                                @if($count < 10)
                                                    <div class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title="{{ $member['firstname'] }} {{ $member['lastname'] }}">
                                                        <img alt="Pic" src="{{ \Storage::disk('s3')->url(@$member['profile_url']) }}" style="width: 30px; height: 30px"/>
                                                    </div>
                                                @endif
                                                @php $count ++; @endphp
                                                @empty
                                                @endforelse
                                                @if($count > 10)
                                                <div class="symbol symbol-30 symbol-circle symbol-light">
                                                    <span class="symbol-label font-weight-bold">+</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <!--end::Item-->
                                    </div>
                                    <!--end::Blog-->
                                </div>
                                <!--end::Body-->
                                <!--begin::Footer-->
                                <div class="card-footer d-flex align-items-center">
                                    <div class="d-flex">
                                        <div class="d-flex align-items-center mr-7">
                                            <span class="svg-icon svg-icon-gray-500">
                                                <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo5/dist/../src/media/svg/icons/Communication/Sending.svg-->
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"/>
                                                        <path d="M8,13.1668961 L20.4470385,11.9999863 L8,10.8330764 L8,5.77181995 C8,5.70108058 8.01501031,5.63114635 8.04403925,5.56663761 C8.15735832,5.31481744 8.45336217,5.20254012 8.70518234,5.31585919 L22.545552,11.5440255 C22.6569791,11.5941677 22.7461882,11.6833768 22.7963304,11.794804 C22.9096495,12.0466241 22.7973722,12.342628 22.545552,12.455947 L8.70518234,18.6841134 C8.64067359,18.7131423 8.57073936,18.7281526 8.5,18.7281526 C8.22385763,18.7281526 8,18.504295 8,18.2281526 L8,13.1668961 Z" fill="#000000"/>
                                                        <path d="M4,16 L5,16 C5.55228475,16 6,16.4477153 6,17 C6,17.5522847 5.55228475,18 5,18 L4,18 C3.44771525,18 3,17.5522847 3,17 C3,16.4477153 3.44771525,16 4,16 Z M1,11 L5,11 C5.55228475,11 6,11.4477153 6,12 C6,12.5522847 5.55228475,13 5,13 L1,13 C0.44771525,13 6.76353751e-17,12.5522847 0,12 C-6.76353751e-17,11.4477153 0.44771525,11 1,11 Z M4,6 L5,6 C5.55228475,6 6,6.44771525 6,7 C6,7.55228475 5.55228475,8 5,8 L4,8 C3.44771525,8 3,7.55228475 3,7 C3,6.44771525 3.44771525,6 4,6 Z" fill="#000000" opacity="0.3"/>
                                                    </g>
                                                </svg><!--end::Svg Icon-->
                                            </span>
                                            <a href="#" class="font-weight-bolder text-primary ml-2">{{ @$event['registration_count'] }} {{ @Config::get('enums.event_registeation_status')['created'] }}</a>
                                        </div>
                                        <div class="d-flex align-items-center mr-7">
                                            <span class="svg-icon svg-icon-gray-500">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group-chat.svg-->
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"/>
                                                        <rect fill="#000000" opacity="0.3" x="11.5" y="2" width="2" height="4" rx="1"/>
                                                        <rect fill="#000000" opacity="0.3" x="11.5" y="16" width="2" height="5" rx="1"/>
                                                        <path d="M15.493,8.044 C15.2143319,7.68933156 14.8501689,7.40750104 14.4005,7.1985 C13.9508311,6.98949895 13.5170021,6.885 13.099,6.885 C12.8836656,6.885 12.6651678,6.90399981 12.4435,6.942 C12.2218322,6.98000019 12.0223342,7.05283279 11.845,7.1605 C11.6676658,7.2681672 11.5188339,7.40749914 11.3985,7.5785 C11.2781661,7.74950085 11.218,7.96799867 11.218,8.234 C11.218,8.46200114 11.2654995,8.65199924 11.3605,8.804 C11.4555005,8.95600076 11.5948324,9.08899943 11.7785,9.203 C11.9621676,9.31700057 12.1806654,9.42149952 12.434,9.5165 C12.6873346,9.61150047 12.9723317,9.70966616 13.289,9.811 C13.7450023,9.96300076 14.2199975,10.1308324 14.714,10.3145 C15.2080025,10.4981676 15.6576646,10.7419985 16.063,11.046 C16.4683354,11.3500015 16.8039987,11.7268311 17.07,12.1765 C17.3360013,12.6261689 17.469,13.1866633 17.469,13.858 C17.469,14.6306705 17.3265014,15.2988305 17.0415,15.8625 C16.7564986,16.4261695 16.3733357,16.8916648 15.892,17.259 C15.4106643,17.6263352 14.8596698,17.8986658 14.239,18.076 C13.6183302,18.2533342 12.97867,18.342 12.32,18.342 C11.3573285,18.342 10.4263378,18.1741683 9.527,17.8385 C8.62766217,17.5028317 7.88033631,17.0246698 7.285,16.404 L9.413,14.238 C9.74233498,14.6433354 10.176164,14.9821653 10.7145,15.2545 C11.252836,15.5268347 11.7879973,15.663 12.32,15.663 C12.5606679,15.663 12.7949989,15.6376669 13.023,15.587 C13.2510011,15.5363331 13.4504991,15.4540006 13.6215,15.34 C13.7925009,15.2259994 13.9286662,15.0740009 14.03,14.884 C14.1313338,14.693999 14.182,14.4660013 14.182,14.2 C14.182,13.9466654 14.1186673,13.7313342 13.992,13.554 C13.8653327,13.3766658 13.6848345,13.2151674 13.4505,13.0695 C13.2161655,12.9238326 12.9248351,12.7908339 12.5765,12.6705 C12.2281649,12.5501661 11.8323355,12.420334 11.389,12.281 C10.9583312,12.141666 10.5371687,11.9770009 10.1255,11.787 C9.71383127,11.596999 9.34650161,11.3531682 9.0235,11.0555 C8.70049838,10.7578318 8.44083431,10.3968355 8.2445,9.9725 C8.04816568,9.54816454 7.95,9.03200304 7.95,8.424 C7.95,7.67666293 8.10199848,7.03700266 8.406,6.505 C8.71000152,5.97299734 9.10899753,5.53600171 9.603,5.194 C10.0970025,4.85199829 10.6543302,4.60183412 11.275,4.4435 C11.8956698,4.28516587 12.5226635,4.206 13.156,4.206 C13.9160038,4.206 14.6918294,4.34533194 15.4835,4.624 C16.2751706,4.90266806 16.9686637,5.31433061 17.564,5.859 L15.493,8.044 Z" fill="#000000"/>
                                                    </g>
                                                </svg>
                                                <!--end::Svg Icon-->
                                            </span>
                                            <a href="#" class="font-weight-bolder text-primary ml-2">{{ @$event['status_approved'] }} {{ @Config::get('enums.event_registeation_status')['approved'] }}</a>
                                        </div>
                                    </div>
                                    {{-- {{Auth::user()->id}} --}}
                                    <a href="/event/registration?event_id={{@$event['id']}}" class="btn btn-primary btn-sm text-uppercase font-weight-bolder mt-5 mt-sm-0 mr-auto mr-sm-0 ml-sm-auto">{{ trans('display.general_detail') }}</a>
                                </div>
                                <!--end::Footer-->
                            </div>
                            <!--end::Card-->
                        </div>
                    @endforeach
                    </div>
                    @endforeach
                    <!--end::Row-->
                    <!--begin::Pagination-->
                    {{ @$pagination->links('pagination::theme') }}
                    <!--end::Pagination-->
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
    eventTable = $("#event-registration-datatable").DataTable({
        processing:     true,
        serverSide:     true,
        //deferRender:    true,
        //autoWidth:      true,
        //filter:         false,
        responsive:     true,
        dataType: 'json',
        paginationType: "full_numbers",
        ajax: {
            url: '{{route('event.registration.data.list')}}',
            type: 'POST',
            data: function ( d ) {
                var dateArr = {};
                $('#event-registration-search-form input[name^="search_date"]').map(function(){
                    dateArr[this.id] = this.value;
                }).get();
                d.event = $('#event-registration-search-form select[id="search_event"]').val();
                d.entry = $('#event-registration-search-form select[id="search_entry"]').val();
                d.entryAge = $('#event-registration-search-form select[id="search_entry_age"]').val();
                d.entryBelt = $('#event-registration-search-form select[id="search_entry_belt"]').val();
                d.entryWeight = $('#event-registration-search-form select[id="search_entry_weight"]').val();
                d.status = $('#event-registration-search-form select[id="search_status"]').val();
                d.member = $('#event-registration-search-form input[id="search_member"]').val();
                d.gender = $('#event-registration-search-form select[id="search_gender"]').val();
                d.academy = $('#event-registration-search-form select[id="search_academy"]').val();
                d.is_weight = $('#event-registration-search-form select[id="search_is_weight"]').val();
                d.date = dateArr;
            },
        },
        drawCallback: function(settings) {
            var api = this.api();
            var rows = api.rows({page: 'current'}).nodes();
            var last = null;

            api.column(1, {page: 'current'}).data().each(function(group, i) {
                if (last !== group) {
                    $(rows).eq(i).before(
                        '<tr class="group"><td colspan="14">&nbsp;' + group + '</td></tr>',
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
            
            {data: 'event'},
            {data: 'member', "defaultContent": ""},
            /*
            {data: 'profile_url', "defaultContent": ""},
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
            */
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
                },
                name: "age.start_age", "defaultContent": ""
            },
            {data: 'belt.name', "defaultContent": ""},
            {data: 'weight.weight', "defaultContent": ""},
            {data: 'academy_name'},
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
            targets: [0,1,2,7,10]
        },{
            class: "text-center",
            targets: [0,1]
        }],
        order: [[ 9, "desc" ]],
        dom: "<'row'<'col-sm-6 text-left'B><'col-sm-6 text-right'<'#colvis'>>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
        buttons: [
            {
                text: '<i class="la la-plus"></i> Шинээр нэмэх',
                className: "btn btn-light-danger font-weight-bolder mb-2",
                action: function ( e, dt, node, config ) {
                    $.get('{!! route('event.registration.create') !!}', showAddModal);
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

    $('#event-registration-search-form').on('submit', function(e) {
        eventTable.draw();
        e.preventDefault();
    });

    $('#event-registration-datatable tbody').on( 'click', 'tr td a.edit', function () {
        var id = $(this).data("registrationid");

        $.get('registration/'+id+'/edit', showEditModal);
    });

    $('#event-registration-datatable tbody').on( 'click', 'tr td a.show-image', function () 
    {
        var id = $(this).data("id");
        var type = $(this).data("type");

        $.get('/member/show/image/'+type+'/'+id, function( data ) {
            $('#showImageModal').modal();
            $('#showImageModal').on('shown.bs.modal', function(){
                $('#showImageModal .modal-content').html(data);

                $(this).off('shown.bs.modal');
            });

            $('#showImageModal').on('hidden.bs.modal', function(){
                $('#showImageModal .modal-content').empty();
            });
        });
    });

    $('#event-registration-datatable tbody').on( 'click', 'tr td a.win-place', function () 
    {
        var id = $(this).data("registrationid");

        $.get('registration/create/award?event_reg_id='+id, function( data ) {
            $('#memberModal').modal();
            $('#memberModal').on('shown.bs.modal', function(){
                $('#memberModal .modal-content').html(data);
                $('#event-award-form').validate({
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
                                var page = eventTable.page.info().page;
                                $('#memberModal').find("#close").trigger('click');
                                $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                                eventTable.page(page).draw('page');
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
                $('#memberModal .modal-content').empty();
            });
        });
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
    });

    $(".filter-status-count").on('click', function(){
        var status = $(this).data('status');

        $('#event-registration-search-form select[name=search_status]').val(status);
        $('#event-registration-search-form').submit();
    });

    $("#kt_reset").click(function(e){
        e.preventDefault();
        $('.datatable-input').each(function() {
            $(this).val('');
            eventTable.column($(this).data('col-index')).search('', false, false);
        });
        eventTable.draw();
    });

}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

//Modal
function showAddModal( data ) {

    $('#memberModal').modal();
    $('#memberModal').on('shown.bs.modal', function(){
        $('#memberModal .modal-content').html(data);
        $('.selectpicker').selectpicker();
        $('#create-event-registration-form select[name=member_id]').select2();
        $('#create-event-registration-form select[name=event_id]').select2({
            placeholder: "-- {{ trans('display.general_select') }} --"
        });
        $('#create-event-registration-form input[name=entry_id]').select2({data: ""});
        $('#create-event-registration-form input[name=entry_age_id]').select2({data: ""});
        $('#create-event-registration-form input[name=entry_belt_id]').select2({data: ""});
        $('#create-event-registration-form input[name=entry_weight_id]').select2({data: ""});
        $('#create-event-registration-form input[name=academy_id]').select2({data: ""});

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
                    console.log(data);
                    return {
                        results: JSON.parse(data)
                    };
                },
                cache: true
            },
            templateSelection: function (item) {
                return item.firstname + ": " + item.lastname;
            },
            templateResult: function (item) {
                return item.firstname + ": " + item.lastname;
            }
        });

        $('#create-event-registration-form select[name=academy_id]').on('change', function(){
            var academyId = $(this).val(); 
            $.ajax({
                type: 'POST',
                url: '{!! route('academy.isother') !!}',
                data: {academy_id: academyId},
                success: function (data) {
                    $('#academy_name_other').addClass('d-none');
                    $("#academy_name").attr("disabled", true);
                    $("#academy_name").val("");
                    jsonData = JSON.parse(data);

                    if(jsonData) {
                        $('#academy_name_other').removeClass('d-none');
                        $("#academy_name").attr("disabled", false);
                    }              
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });
        }) 
        

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
        $('#memberModal .modal-content').empty();
    });
}

function showEditModal(data){
    $('#memberModal').modal();
    $('#memberModal').on('shown.bs.modal', function(){
        $('#memberModal .modal-content').html(data);
        $('.selectpicker').selectpicker();

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

        $('#update-event-registration-form select[name=academy_id]').on('change', function(){
            var academyId = $(this).val(); 
            $.ajax({
                type: 'POST',
                url: '{!! route('academy.isother') !!}',
                data: {academy_id: academyId},
                success: function (data) {
                    $('#academy_name_other').addClass('d-none');
                    $("#academy_name").attr("disabled", true);
                    $("#academy_name").val("");
                    jsonData = JSON.parse(data);

                    if(jsonData) {
                        $('#academy_name_other').removeClass('d-none');
                        $("#academy_name").attr("disabled", false);
                    }              
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });
        }) 

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
                        var page = eventTable.page.info().page;
                        $('#memberModal').find("#close").trigger('click');
                        $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                        eventTable.page(page).draw('page');
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
        $('#memberModal .modal-content').empty();
    });
}
</script>
@endsection
@stop
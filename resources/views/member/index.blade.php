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
                                    <h3 class="card-label">Гишүүдийн жагсаалт 
                                    <span class="d-block text-muted pt-2 font-size-sm">Гишүүд</span></h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Items-->
                                <div class="d-flex align-items-center flex-wrap pb-5 border-bottom">
                                    <!--begin: Item-->
                                    <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                                        <span class="mr-4">
                                            <i class="flaticon-file-2 icon-2x text-muted font-weight-bold"></i>
                                        </span>
                                        <div class="d-flex flex-column flex-lg-fill">
                                            <span class="text-dark-75 font-weight-bolder font-size-sm">{{ array_sum(@$memberGenderCount) }} {{ trans('display.general_all') }}</span>
                                            <a href="javascript:;" class="text-primary font-weight-bolder filter-gender-count" data-status="">Харах</a>
                                        </div>
                                    </div>
                                    <!--end: Item-->
                                    @forelse(@$memberGenderCount as $key => $count)
                                    <!--begin: Item-->
                                    <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                                        <span class="mr-4">
                                            <i class="flaticon-file-2 icon-2x text-muted font-weight-bold"></i>
                                        </span>
                                        <div class="d-flex flex-column flex-lg-fill">
                                            <span class="text-dark-75 font-weight-bolder font-size-sm">{{ $count }} {{ @Config::get('enums.gender_code')[$key] }}</span>
                                            <a href="javascript:;" class="text-primary font-weight-bolder filter-gender-count" data-gendercode="{{ $key }}">Харах</a>
                                        </div>
                                    </div>
                                    <!--end: Item-->
                                    @empty
                                    @endforelse
                                </div>
                                <!--begin: Items-->
                                <!--begin::Accordion-->
                                <div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle" id="search">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title collapsed" data-toggle="collapse" data-target="#search-member">
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
                                        <div id="search-member" class="collapse" data-parent="#search">
                                            <div class="card-body">
                                                <!--begin: Search Form-->
                                                <form class="mb-10" id="member-search-form" method="POST">
                                                    <div class="row mb-6">
                                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                                            <label>{{trans('display.human_register_number')}}</label>
                                                            <input type="text" class="form-control datatable-input" name="search_register_number" id="search_register_number" data-col-index="1">
                                                        </div>
                                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                                            <label>{{trans('display.human_lastname')}}</label>
                                                            <input type="text" class="form-control datatable-input" name="search_lastname" id="search_lastname" data-col-index="2">
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
                                                            <label>{{trans('display.human_gender_code')}}</label>
                                                            <select class="form-control datatable-input" name="search_gender_code" id="search_gender_code" data-col-index="5">
                                                                <option value="">-- {{ trans('display.general_all') }} --</option>
                                                                @forelse(@Config::get('enums.gender_code') as $key => $gender)
                                                                <option value="{{ $key }}">{{ $gender }}</option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-2 mb-lg-0 mb-6">
                                                            <label>{{ trans('display.general_status') }}:</label>
                                                            <select class="form-control datatable-input" name="search_status" id="search_status" data-col-index="6">
                                                                <option value="">-- {{ trans('display.general_all') }} --</option>
                                                                @forelse(@Config::get('enums.member_status') as $key => $status)
                                                                <option value="{{ $key }}">{{ $status }}</option>
                                                                @empty
                                                                @endforelse
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-6">
                                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                                            <label>{{ trans('display.comp_entry_age') }}:</label>
                                                            <div class="input-daterange input-group" id="kt_datepicker">
                                                                <input type="number" min="1" max="100" class="form-control datatable-input" name="search_age[]" id="start" placeholder="From" data-col-index="7" />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-ellipsis-h"></i>
                                                                    </span>
                                                                </div>
                                                                <input type="number" min="1" max="100" class="form-control datatable-input" name="search_age[]" id="end" placeholder="To" data-col-index="7" />
                                                            </div>
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
                                <!--begin: Datatable-->
                                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="panel-sub-heading">

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline" id="member-datatable" role="grid" aria-describedby="kt_datatable_info" style="width: 1235px;">
                                                <thead>
                                                    <tr role="row">
                                                        <th>No.</th>
                                                        {{-- <th>{{trans('display.username')}}</th> --}}
                                                        <th>{{trans('display.profile_photo')}}</th>
                                                        <th>{{trans('display.human_register_number')}}</th>
                                                        <th>{{trans('display.human_lastname')}}</th>
                                                        <th>{{trans('display.human_firstname')}}</th>
                                                        <th>{{trans('display.human_contact_phone')}}</th>
                                                        <th>{{trans('display.human_birth')}}</th>
                                                        <th>{{trans('display.id_photo')}}</th>
                                                        <th>{{trans('display.general_connect')}}</th>
                                                        <th>{{trans('display.general_status')}}</th>
                                                        <th>{{trans('display.general_created_at')}}</th>
                                                        <th>{{trans('display.general_manage')}}</th>
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
<script src="{{asset('assets/js/plugins/custom/select2-ng/select2.min.js')}}"></script>
<script src="{{asset('assets/js/smart.js')}}"></script>

<script>
$(document).ready(function() {
    memberTable = $("#member-datatable").DataTable({
        processing:     true,
        serverSide:     true,
        deferRender:    true,
        autoWidth:      true,
        filter:         false,
        responsive:     false,
        dataType: 'json',
        paginationType: "full_numbers",
        ajax: {
            url: '{{route('member.data.list')}}',
            type: 'POST',
            data: function ( d ) {
                var ageArr = {};
                $('#member-search-form input[name^="search_age"]').map(function(){
                    ageArr[this.id] = this.value;
                }).get();
                d.register_number = $('#member-search-form input[id="search_register_number"]').val();
                d.lastname = $('#member-search-form input[id="search_lastname"]').val();
                d.firstname = $('#member-search-form input[id="search_firstname"]').val();
                d.phone_number = $('#member-search-form input[id="search_phone_number"]').val();
                d.gender = $('#member-search-form select[id="search_gender_code"]').val();
                d.status = $('#member-search-form select[id="search_status"]').val();
                d.age = ageArr;
            },
        },
        "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            $(nRow).attr('id', aData[0]);
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                width: "30px"
            },
            {data: 'profile_photo'},
            {data: 'register_number'},
            {data: 'lastname'},
            {data: 'firstname'},
            {data: 'contact_phone'},
            {data: 'birth'},
            {data: 'id_photo', "defaultContent": ''},
            {data: 'connect_user', "defaultContent": ''},
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
            targets: [0, 6, 7, 8, 9, 10]
        }],
        order: [[ 10, "desc" ]],
        dom: "<'top'B><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
        buttons: [
        {
            text: '<i class="la la-plus"></i> Шинээр нэмэх',
            className: "btn btn-light-danger font-weight-bolder mb-2",
            action: function ( e, dt, node, config ) {
                $.get('{!! route('member.create') !!}', showAddModal);
            }
        }]
	});

    $('#member-search-form').on('submit', function(e) {
        memberTable.draw();
        e.preventDefault();
    });

    $('#member-datatable tbody').on( 'click', 'tr td a.edit', function () 
    {
        var id = $(this).data("id");
        $.get('member/' + id + '/edit', memberEditModal);
    });


    $('#member-datatable tbody').on( 'click', 'tr td a.show-image', function () 
    {
        var id = $(this).data("id");
        var type = $(this).data("type");

        $.get('member/show/image/'+type+'/'+id, function( data ) {
            $('#showImageModal').modal();
            $('#showImageModal').on('shown.bs.modal', function(){
                $('#showImageModal .modal-content').html(data);

                $(this).off('shown.bs.modal');
            });

            $('#showImageModal').on('hidden.bs.modal', function(){
                $('#showImageModal .modal-body').empty();
            });
        });
    });

    $(".filter-gender-count").on('click', function(){
        var genderCode = $(this).data('gendercode');

        $('#member-search-form select[name=search_gender_code]').val(genderCode);
        $('#member-search-form').submit();
    });

    $("#kt_reset").click(function(e){
        e.preventDefault();
        $('.datatable-input').each(function() {
            $(this).val('');
            memberTable.column($(this).data('col-index')).search('', false, false);
        });
        memberTable.draw();
    });

}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

//Modal
function showAddModal( data ) {

    $('#memberAddModal').modal();
    $('#memberAddModal').on('shown.bs.modal', function(){
        $('#memberAddModal .modal-content').html(data);

        $("#register_number").inputmask({ regex: "[А-Я]{2}[0-9]*"});
        $('.only-phone').inputmask("99 99 99 99");

        $('#birth').datepicker({
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-right"></i>',
                rightArrow: '<i class="la la-angle-left"></i>'
            }
        })

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
                        $('#memberAddModal').find("#close").trigger('click');
                        $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                        memberTable.draw();
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

    $('#memberAddModal').on('hidden.bs.modal', function(){
        $('#memberAddModal .modal-body').empty();
    });
}

function memberEditModal(data)
{
    $('#memberEditModal').modal();
    $('#memberEditModal').on('shown.bs.modal', function(){
        $('#memberEditModal .modal-content').html(data);

        $("#register_number").inputmask({ regex: "[А-Я]{2}[0-9]*"});
        $('.only-phone').inputmask("99 99 99 99");

        $('#edit-member-form').validate({
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
                    data:  new FormData(form),
                    success: function(response) {
                        $('#memberEditModal').find("#close").trigger('click');
                        $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                        memberTable.draw();
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

    $('#memberEditModal').on('hidden.bs.modal', function(){
        $('#memberEditModal .modal-body').empty();
    });
}

//UserDelete
function memberDelete(id)
{
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
                url: 'member/' + id,
                type: 'DELETE',
                success: function(response) {
                    $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                    memberTable.draw();
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
}

function connectUser(id)
{
    $.get('/member/create/connect/user/'+id, function( data ) {
        if (data.status) {
            $('#connetUserModal').modal();
            $('#connetUserModal').on('shown.bs.modal', function(){
                $('#connetUserModal .modal-content').html(data.view);
                
                $('#connect-user-form input[name=user_id]').select2({
                    width: 'resolve',
                    dropdownAutoWidth : true,
                    dropdownParent: $('#connetUserModal'),
                    placeholder: "-- {{ trans('display.general_select') }} --",
                    ajax: {
                        type: 'GET',
                        url: '{!! route('user.search') !!}',
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
                    minimumInputLength: 8,
                    formatSelection: function (item) {
                        return item.firstname + ": " + item.lastname;
                    },
                    formatResult: function (item) {
                        return item.firstname + ": " + item.lastname;
                    }
                });

                $('#connect-user-form').validate({
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
                                $('#connetUserModal').find("#close").trigger('click');
                                $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                                memberTable.draw();
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
        else
        {
            $('.panel-sub-heading').html(data.view).fadeIn().delay(5000).fadeOut();
        }
    });
}

function chnageMemberStatus(id)
{
    $.get('/member/create/status/'+id, function( data ) {
        if (data.status) {
            $('#memberStatusModal').modal();
            $('#memberStatusModal').on('shown.bs.modal', function(){
                $('#memberStatusModal .modal-content').html(data.view);

                $('#member-status-form').validate({
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
                                $('#memberStatusModal').find("#close").trigger('click');
                                $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                                memberTable.draw();
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
        else
        {
            $('.panel-sub-heading').html(data.view).fadeIn().delay(5000).fadeOut();
        }
    });

    $('#memberStatusModal').on('hidden.bs.modal', function(){
        $('#memberStatusModal .modal-body').empty();
    });
}

</script>
@endsection
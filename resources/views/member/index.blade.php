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
                                <form class="mb-15" id="member-search-form" method="POST">
                                    <div class="row mb-6">
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>{{trans('display.human_register_number')}}</label>
                                            <input class="form-control" name="register_number_search" id="register_number_search" placeholder="{{trans('display.human_register_number')}}" value="">
                                        </div>
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>{{trans('display.human_lastname')}}</label>
                                            <input class="form-control" name="lastname" id="lastname" placeholder="{{trans('display.human_lastname')}}" value="">
                                        </div>
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>{{trans('display.human_firstname')}}</label>
                                            <input class="form-control" name="firstname" id="firstname" placeholder="{{trans('display.human_firstname')}}" value="">
                                        </div>
                                        <div class="col-lg-3 mb-lg-0 mb-6">
                                            <label>{{trans('display.human_phone_number')}}</label>
                                            <input class="form-control" name="phone_number" id="phone_number" placeholder="{{trans('display.human_phone_number')}}" value="">
                                        </div>
                                    </div>
                                    <div class="row mt-8">
                                        <button class="btn btn-primary btn-primary--icon" id="data-search" type="submit">
                                            <span>
                                                <i class="la la-search"></i>
                                                <span>{{trans('display.general_search')}}</span>
                                            </span>
                                        </button>&#160;&#160;
                                        <button class="btn btn-secondary btn-secondary--icon reset" type="button">
                                            <span>
                                                <i class="la la-close"></i>
                                                <span>{{trans('display.general_clear')}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </form>
                                <!--begin: Datatable-->
                                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="panel-sub-heading">

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline" id="member_datatable" role="grid" aria-describedby="kt_datatable_info" style="width: 1235px;">
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
    memberTable = $("#member_datatable").DataTable({
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
                d.register_number = $('#member-search-form input[id="register_number_search"]').val();
                d.lastname = $('#member-search-form input[id="lastname"]').val();
                d.firstname = $('#member-search-form input[id="firstname"]').val();
                d.phone_number = $('#member-search-form input[id="phone_number"]').val();
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
        order: [[ 9, "desc" ]],
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

    $(".reset").click(function(){
        $(':input', '#member-search-form')
         .not(':button, :submit, :reset')
         .val('')
         .attr('value', '');
    });

}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

//Modal
function showAddModal( data ) {

    $('#memberAddModal').modal();
    $('#memberAddModal').on('shown.bs.modal', function(){
        $('#memberAddModal .modal-content').html(data);

        $("#register_number").inputmask({ regex: "[А-Я]{2}[0-9]*"});
        $('.only-phone').inputmask("99 99 99 99");

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

function memberEditModal(data){
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

function memberEdit(id)
{
    $.get('/member/' + id + '/edit', memberEditModal);
}

function showImageProfile(id)
{
    $.get('/member/show/image/profile/'+id, function( data ) {
        if (data.status) {
            $('#showImageModal').modal();
            $('#showImageModal').on('shown.bs.modal', function(){
                $('#showImageModal .modal-content').html(data.view);

                $(this).off('shown.bs.modal');
            });
        }
        else
        {
            $('.panel-sub-heading').html(data.view).fadeIn().delay(5000).fadeOut();
        }
    });
}

function showImageId(id)
{
    $.get('/member/show/image/id/'+id, function( data ) {
        if (data.status) {
            $('#showImageModal').modal();
            $('#showImageModal').on('shown.bs.modal', function(){
                $('#showImageModal .modal-content').html(data.view);

                $(this).off('shown.bs.modal');
            });
        }
        else
        {
            $('.panel-sub-heading').html(data.view).fadeIn().delay(5000).fadeOut();
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
                    maximumSelectionLength: 30,
                    minimumInputLength: 3,
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

</script>
@endsection
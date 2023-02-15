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
                                    <h3 class="card-label">Хэрэглэгчийн жагсаалт 
                                    <span class="d-block text-muted pt-2 font-size-sm">Системийн хэрэглэгчид</span></h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Datatable-->
                                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="panel-sub-heading">

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-separate table-head-custom" id="user-datatable" style="margin-top: 13px !important">
                                                <thead>
                                                    <tr>
                                                        <th width="5px">No.</th>
                                                        <th width="15%">{{trans('display.username')}}</th>
                                                        <th width="10%">{{trans('display.human_email')}}</th>
                                                        <th width="15%">{{trans('display.human_firstname')}}</th>
                                                        <th width="15%">{{trans('display.human_lastname')}}</th>
                                                        <th width="10%">{{trans('display.human_phone_number')}}</th>
                                                        <th width="25%">{{trans('display.role')}}</th>
                                                        <th width="10%">{{trans('display.general_created_at')}}</th>
                                                        <th width="5">{{trans('display.general_manage')}}</th>
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
    userTable = $("#user-datatable").DataTable({
        processing:     true,
        serverSide:     true,
        deferRender:    true,
        autoWidth:      true,
        filter:         false,
        responsive:     false,
        dataType: 'json',
        paginationType: "full_numbers",
        ajax: {
            url: '{{route('user.data.list')}}',
            type: 'POST',
            data: function ( d ) {
                d.name = $('#user-search-form input[id="name"]').val();
                d.email = $('#user-search-form input[id="email"]').val();
                d.role = $('#user-search-form select[id="role"]').val();
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
            {data: 'username'},
            {data: 'email'},
            {data: 'firstname'},
            {data: 'lastname'},
            {data: 'phone_number'},
            {data: 'role'},
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
            targets: [0, 6, 7]
        }],
        order: [[ 7, "desc" ]],
        dom: "<'top'B><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
        buttons: [
        {
            text: '<i class="la la-plus"></i> Шинээр нэмэх',
            className: "btn btn-light-danger font-weight-bolder {{ SecurityHelper::checkPermission(@Config::get('permission.user'), Config::get('permission.editable')) ? '' : 'd-none' }}",
            action: function ( e, dt, node, config ) {
                $.get('{!! route('user.create') !!}', showAddModal);
            }
        }]
	});

    $('#user-search-form').on('submit', function(e) {
        userTable.draw();
        e.preventDefault();
    });

}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

//Modal
function showAddModal( data ) {

    $('#compadUserAddModal').modal();
    $('#compadUserAddModal').on('shown.bs.modal', function(){
        $('#compadUserAddModal .modal-content').html(data);

        $('#add-user-form').validate({
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
                        if(response.status == 'success'){
                            $('#compadUserAddModal').find("#close").trigger('click');
                            toastr.success(response.msg);
                            if(userTable != undefined)
                            {
                                userTable.draw();
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

    $('#compadUserAddModal').on('hidden.bs.modal', function(){
        $('#compadUserAddModal .modal-body').empty();
    });
}

function compadUserEditModal(data){
    $('#compadUserEditModal').modal();
        $('#compadUserEditModal').on('shown.bs.modal', function(){
            $('#compadUserEditModal .modal-content').html(data);

            $('#edit-user-form').validate({
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
                        if(response.status == 'success'){
                        $('#compadUserEditModal').find('#close').trigger('click');
                        toastr.success(response.msg);
                        if(userTable != undefined)
                        {
                            var page = userTable.page.info().page;
                            userTable.page(page).draw('page');
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

$('#compadUserEditModal').on('hidden.bs.modal', function(){
    $('#compadUserEditModal .modal-body').empty();
});

}

function changePassModal(data){
    $('#userchangePasswordModal').modal();
    $('#userchangePasswordModal').on('shown.bs.modal', function(){
    $('#userchangePasswordModal .modal-content').html(data);

    $(":input").inputmask();

    $('#change-password-form').validate({
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
                data: $(form).serialize(),
                success: function(response) {
                    $('#change-password-form').find("#close").trigger('click');
                    $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                    userTable.draw();
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            }).done(function(data) {
                //submitButton.prop('disabled', false);
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
        $('#userChangePasswordModal').on('hidden.bs.modal', function(){
        $('#userChangePasswordModal .modal-body').empty();
    });
}

//UserDelete
function compadUserDelete(id)
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
                url: 'user/' + id,
                type: 'DELETE',
                success: function(response) {
                    $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                    userTable.draw();
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

function compadUserEdit(id)
{
    $.get('/user/' + id + '/edit', compadUserEditModal);
}

function changePassword(id)
{
    $.get('/user/change/' + id + '/password', changePassModal);
}

function editRole(id)
{
    $.get('/user-role/' + id + '/edit', roleEditModal);
}


function roleEditModal(data){
    $('#roleEditModal').modal();
        $('#roleEditModal').on('shown.bs.modal', function(){
            $('#roleEditModal .modal-content').html(data);

            $('#edit-role-form').validate({
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: new FormData(form),
                    success: function(response) {
                        $('#roleEditModal').find("#close").trigger('click');
                        $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                        userTable.draw();
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

    $('#roleEditModal').on('hidden.bs.modal', function(){
        $('#roleEditModal .modal-body').empty();
    });

}

</script>
@endsection
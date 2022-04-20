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
                                    <h3 class="card-label">Дүрийн жагсаалт 
                                    <span class="d-block text-muted pt-2 font-size-sm">Системийн дүрүүд</span></h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Datatable-->
                                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="panel-sub-heading">

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-separate table-head-custom" id="role-datatable" style="margin-top: 13px !important">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center border-right" width="15px">No.</th>
                                                        <th width="">{{trans('display.general_name')}}</th>
                                                        <th width="">{{trans('display.general_code')}}</th>
                                                        <th width="">{{trans('display.menus_count')}}</th>
                                                        <th width="20%">{{trans('display.general_created_at')}}</th>
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
    rolerTable = $("#role-datatable").DataTable({
        processing:     true,
        serverSide:     true,
        deferRender:    true,
        autoWidth:      true,
        filter:         false,
        responsive:     false,
        dataType: 'json',
        paginationType: "full_numbers",
        ajax: {
            url: '{{route('role.data.list')}}',
            type: 'POST',
            data: function ( d ) {
                d.name = $('#user-search-form input[id="name"]').val();
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
            {data: 'code'},
            {data: 'menu_count'},
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
            targets: [0, 3, 4, 5]
        }],
        order: [[ 4, "desc" ]],
        dom: "<'top'B><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
        buttons: [
        {
            text: '<i class="la la-plus"></i> Шинээр нэмэх',
            className: "btn btn-light-danger font-weight-bolder {{ SecurityHelper::checkPermission(@Config::get('permission.role'), Config::get('permission.editable')) && Auth::user()->roles->first()->code == 'admin' ? '' : 'd-none' }}",
            action: function ( e, dt, node, config ) {
                $.get('{!! route('role.create') !!}', showAddModal);
            }
        }]
	});

    $('#user-search-form').on('submit', function(e) {
        rolerTable.draw();
        e.preventDefault();
    });

    $('#role-datatable tbody').on( 'click', 'tr td a.edit', function () {
        var roleId = $(this).data("roleid");

        $.get('role/'+roleId+'/edit', showEditModal);
    });

    $('#role-datatable tbody').on( 'click', 'tr td a.delete', function () {
        var roleId = $(this).data("roleid");
        console.log(roleId)
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
                    url: 'role/' + roleId,
                    type: 'DELETE',
                    success: function(response) {
                        $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                        rolerTable.draw();
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

    function showAddModal(data) {
        $('#roleModal').modal();
            $('#roleModal').on('shown.bs.modal', function(){
                $('#roleModal .modal-body').html(data);

                $(".dropdown-item").on('click', function(){
                    var permission = $(this).data("permission");
                    $(this).parent().prev().text($(this).text());

                    if(permission != '')
                    {
                        $(this).parent().prev().prev().val(permission);
                    }
                });

                $('#add-role-form').validate({
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
                            $('#roleModal').find("#close").trigger('click');
                            $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                            rolerTable.draw();
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

        $('#roleModal').on('hidden.bs.modal', function(){
            $('#roleModal .modal-body').empty();
        });
    }

    function showEditModal(data){
        $('#roleModal').modal();
            $('#roleModal').on('shown.bs.modal', function(){
                $('#roleModal .modal-body').html(data);

                $(".dropdown-item").on('click', function(){
                    var permission = $(this).data("permission");
                    $(this).parent().prev().text($(this).text());

                    if(permission != '')
                    {
                        $(this).parent().prev().prev().val(permission);
                    }
                });

                $('#edit-role-form').validate({
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
                            $('#roleModal').find("#close").trigger('click');
                            $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                            rolerTable.draw();
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

        $('#roleModal').on('hidden.bs.modal', function(){
            $('#roleModal .modal-body').empty();
        });

    }

}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

function viewMenuModal(data){
    $('#roleModal').modal();
        $('#roleModal').on('shown.bs.modal', function(){
            $('#roleModal .modal-body').html(data);

        $(this).off('shown.bs.modal');
    });

    $('#roleModal').on('hidden.bs.modal', function(){
        $('#roleModal .modal-body').empty();
    });
}

function showMenu(id)
{
    $.get('role/'+id+'/view/menu', viewMenuModal);
}
</script>
@endsection
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
                            </div>
                            <div class="card-body">
                                <!--begin: Datatable-->
                                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="panel-sub-heading">

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline" id="member_datatable" role="grid" aria-describedby="kt_datatable_info" style="width: 1235px;">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="15px">No.</th>
                                                        {{-- <th  class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="15%">{{trans('display.username')}}</th> --}}
                                                        <th  class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="15%">{{trans('display.human_register_number')}}</th>
                                                        <th  class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="15%">{{trans('display.human_firstname')}}</th>
                                                        <th  class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="15%">{{trans('display.human_lastname')}}</th>
                                                        <th  class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="8%">{{trans('display.human_contact_phone')}}</th>
                                                        <th  class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="8%">{{trans('display.human_birth')}}</th>
                                                        <th  class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="8%">{{trans('display.profile_photo')}}</th>
                                                        <th  class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="8%">{{trans('display.id_photo')}}</th>
                                                        <th  class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="8%">{{trans('display.general_created_at')}}</th>
                                                        <th  class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="5">{{trans('display.general_manage')}}</th>
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
                d.name = $('#user-search-form input[id="name"]').val();
                d.email = $('#user-search-form input[id="email"]').val();
                d.role = $('#user-search-form select[id="role"]').val();
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
            // {data: 'user.firstname'},
            {data: 'register_number'},
            {data: 'firstname'},
            {data: 'lastname'},
            {data: 'contact_phone'},
            {data: 'birth'},
            {data: 'profile_photo'},
            {data: 'id_photo'},
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
            targets: [0, 6, 7, 8, 9]
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

    $('#user-search-form').on('submit', function(e) {
        memberTable.draw();
        e.preventDefault();
    });

}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

//Modal
function showAddModal( data ) {

    $('#memberAddModal').modal();
    $('#memberAddModal').on('shown.bs.modal', function(){
        $('#memberAddModal .modal-content').html(data);

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

function compadUserEdit(id)
{
    $.get('/member/' + id + '/edit', memberEditModal);
}

</script>
@endsection
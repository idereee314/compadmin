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
                                    <h3 class="card-label">Column Rendering 
                                    <div class="text-muted pt-2 font-size-sm">custom colu rendering</div></h3>
                                </div>
                                <div class="card-toolbar">
                                    <!--begin::Dropdown-->
                                    <div class="dropdown dropdown-inline mr-2">
                                        <!--begin::Dropdown Menu-->
                                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                            <!--begin::Navigation-->
                                            <ul class="navi flex-column navi-hover py-2">
                                                <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Choose an option:</li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon">
                                                            <i class="la la-print"></i>
                                                        </span>
                                                        <span class="navi-text">Print</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon">
                                                            <i class="la la-copy"></i>
                                                        </span>
                                                        <span class="navi-text">Copy</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon">
                                                            <i class="la la-file-excel-o"></i>
                                                        </span>
                                                        <span class="navi-text">Excel</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon">
                                                            <i class="la la-file-text-o"></i>
                                                        </span>
                                                        <span class="navi-text">CSV</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon">
                                                            <i class="la la-file-pdf-o"></i>
                                                        </span>
                                                        <span class="navi-text">PDF</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <!--end::Navigation-->
                                        </div>
                                        <!--end::Dropdown Menu-->
                                    </div>
                                    <!--end::Dropdown-->
                                    <!--begin::Button-->
                                    <a href="#" class="btn btn-primary font-weight-bolder">
                                    <span class="svg-icon svg-icon-md">
                                        <!--begin::Svg Icon | path:/metronic/theme/html/demo5/dist/assets/media/svg/icons/Design/Flatten.svg-->
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                                                <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3"></path>
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                    </span>New Record</a>
                                    <!--end::Button-->
                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Datatable-->
                                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <table class="dataTables_length" id="user_datatable">
                                                <label>Show <select name="kt_datatable_length" aria-controls="kt_datatable" class="custom-select custom-select-sm form-control form-control-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries</label></table></div><div class="col-sm-12 col-md-6"><div id="kt_datatable_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="kt_datatable"></label></div></div></div><div class="row"><div class="col-sm-12"><table class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline" id="kt_datatable" role="grid" aria-describedby="kt_datatable_info" style="width: 1235px;">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="15px">No.</th>
                                                        <th  class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="15%">{{trans('display.username')}}</th>
                                                        <th  class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="20%">{{trans('display.human_email')}}</th>
                                                        <th  class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="15%">{{trans('display.human_firstname')}}</th>
                                                        <th  class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="15%">{{trans('display.human_lastname')}}</th>
                                                        {{-- <th  class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="10%">{{trans('display.role')}}</th> --}}
                                                        <th  class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1" colspan="1" width="10%">{{trans('display.general_created_at')}}</th>
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
            <!--end::Wrapper-->
        <!--end::Main-->
</section>

@section('javascript')
<script src="{{asset('assets/js/plugins/custom/datatables/datatables.js')}}"></script>
<script src="{{asset('assets/js/smart.js')}}"></script>

<script>
$(document).ready(function() {

    userTable = $("#user_datatable").DataTable({
        processing:     true,
        serverSide:     true,
        deferRender:    true,
        autoWidth:      true,
        filter:         false,
        responsive:     false,
        dataType: 'json',
        paginationType: "full_numbers",
        ajax: {
            url: '{{route('compaduser.data.list')}}',
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
            {data: 'username'},
            {data: 'email'},
            {data: 'firstname'},
            {data: 'lastname'},
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
            targets: [0]
        }],
        order: [[ 5, "desc" ]],
        dom: '<"float-left"B><"float-right"l><"clear">tip',
        buttons: [
        {
            text: '<i class="fa fa-plus-square"></i> Шинээр нэмэх',
            className: "btn btn-success mb-2 {{ SecurityHelper::checkPermission(@Config::get('permission.user'), Config::get('permission.editable')) ? '' : 'd-none' }}",
            action: function ( e, dt, node, config ) {
                $.get('{!! route('compaduser.create') !!}', showAddModal);
            }
        }]
	});

//     $('#user-search-form').on('submit', function(e) {
//         userTable.draw();
//         e.preventDefault();
//     });

//     $('#user_datatable tbody').on('click', 'tr[role=row]', function () {
//     var tr = $(this);
//     var row = examTable.row(this);
    
//     if ( row.child.isShown() ) {
//         row.child.hide();
//         //tr.removeClass('shown');
//         examTable.$('tr.selected').removeClass('shown selected');
//     }
//     else {
//         row.child( row.data().details ).show();
//         tr.addClass('shown selected');
//     }
// });
}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

//Modal
// function showAddModal( data ) {
//     $('#userAddModal').modal();
//     $('#userAddModal').on('shown.bs.modal', function(){
//         $('#userAddModal .modal-body').html(data);

//         $('#add-user-form').validate({
//             ignore: [],
//             highlight:function(element) {
//                 $(element).parents('.form-group').addClass('has-error has-feedback');
//             },
//             unhighlight: function(element) {
//                 $(element).parents('.form-group').removeClass('has-error');
//             },
//             submitHandler: function(form) {
//                 $.ajax({
//                     url: form.action,
//                     type: form.method,
//                     data: new FormData(form),
//                     success: function(response) {
//                         $('#userAddModal').find("#close").trigger('click');
//                         $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
//                         userTable.draw();
//                     },
//                     error: function (xhr, textStatus, error) {
//                         console.log(xhr.statusText);
//                         console.log(textStatus);
//                         console.log(error);
//                     },
//                     async: false,
//                     processData: false,
//                     contentType: false
//                 });
//             },
//             errorPlacement: function(error, element) {
//                 if($(element).parents('.form-group').find(".error-here")){
//                     error.appendTo($(element).parents('.form-group').find(".error-here"));
//                 } else {
//                     error.insertAfter(element);
//                 }
//             }
//         });

//         $(this).off('shown.bs.modal');
//     });

//     $('#userAddModal').on('hidden.bs.modal', function(){
//         $('#userAddModal .modal-body').empty();
//     });
// }

// function userEditModal(data){
//     $('#userEditModal').modal();
//         $('#userEditModal').on('shown.bs.modal', function(){
//             $('#userEditModal .modal-body').html(data);

//             $('#edit-user-form').validate({
//             ignore: [],
//             highlight:function(element) {
//                 $(element).parents('.form-group').addClass('has-error has-feedback');
//             },
//             unhighlight: function(element) {
//                 $(element).parents('.form-group').removeClass('has-error');
//             },
//             submitHandler: function(form) {
//                 $.ajax({
//                     url: form.action,
//                     type: form.method,
//                     data:  new FormData(form),
//                     success: function(response) {
//                         $('#userEditModal').find("#close").trigger('click');
//                         $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
//                         userTable.draw();
//                     },
//                     error: function (xhr, textStatus, error) {
//                         console.log(xhr.statusText);
//                         console.log(textStatus);
//                         console.log(error);
//                     },
//                     async: false,
//                     processData: false,
//                     contentType: false
//                 });
//             },
//             errorPlacement: function(error, element) {
//                 if($(element).parents('.form-group').find(".error-here")){
//                     error.appendTo($(element).parents('.form-group').find(".error-here"));
//                 } else {
//                     error.insertAfter(element);
//                 }
//             }
//         });

//         $(this).off('shown.bs.modal');
//     });

//     $('#userEditModal').on('hidden.bs.modal', function(){
//         $('#userEditModal .modal-body').empty();
//     });

// }

// //UserDelete
// function userDelete(id)
// {
//     Swal.fire({
//         title: "Та устгахдаа итгэлтэй байна уу",
//         icon: "warning",
//         showCancelButton: true,
//         confirmButtonText: "Тийм",
//         cancelButtonText: 'Үгүй',
//         customClass: {
//             confirmButton: "btn btn-primary",
//             cancelButton: 'btn btn-secondary'
//         },
//     }).then(function(result) {
//         if (result.value) {
//             $.ajax({
//                 url: 'user/' + id,
//                 type: 'DELETE',
//                 success: function(response) {
//                     $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
//                     userTable.draw();
//                 },
//                 error: function (xhr, textStatus, error) {
//                     console.log(xhr.statusText);
//                     console.log(textStatus);
//                     console.log(error);
//                 },
//                 async: false
//             });
//         }
//     });
// }

// function userEdit(id)
// {
//     $.get('/admin/user/' + id + '/edit', userEditModal);
// }

</script>
@endsection
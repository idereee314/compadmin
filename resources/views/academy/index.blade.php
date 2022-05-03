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
                                    <h3 class="card-label">Академи жагсаалт 
                                    <span class="d-block text-muted pt-2 font-size-sm">Академи</span></h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Datatable-->
                                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="panel-sub-heading">

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline" id="academy_datatable" role="grid" aria-describedby="kt_datatable_info" style="width: 1235px;">
                                                <thead>
                                                    <tr role="row">
                                                        <th>No.</th>
                                                        <th width="15%">{{trans('display.general_type')}}</th>
                                                        <th width="20%">{{trans('display.organization')}}</th>
                                                        <th width="20%">{{trans('display.general_name')}}</th>
                                                        <th width="20%">{{trans('display.general_name_en')}}</th>
                                                        <th width="5%">{{trans('display.general_sort_order')}}</th>
                                                        <th width="10%">{{trans('display.general_created_at')}}</th>
                                                        <th width="5%">{{trans('display.general_manage')}}</th>
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
    academyTable = $("#academy_datatable").DataTable({
        processing:     true,
        serverSide:     true,
        deferRender:    true,
        autoWidth:      true,
        filter:         false,
        responsive:     false,
        dataType: 'json',
        paginationType: "full_numbers",
        ajax: {
            url: '{{route('academy.data.list')}}',
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
            {data: 'type', "defaultContent": ""},
            {data: 'organization.name', "defaultContent": ""},
            {data: 'name'},
            {data: 'name_en'},
            {data: 'sort_order'},
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
            targets: [0,5,7]
        }],
        order: [[ 6, "desc" ]],
        dom: "<'top'B><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
        buttons: [
        {
            text: '<i class="la la-plus"></i> Шинээр нэмэх',
            className: "btn btn-light-danger font-weight-bolder mb-2 {{ SecurityHelper::checkPermission(@Config::get('permission.academy'), Config::get('permission.editable')) ? '' : 'd-none' }}",
            action: function ( e, dt, node, config ) {
                $.get('{!! route('academy.create') !!}', showAddModal);
            }
        }]
	});

    $('#user-search-form').on('submit', function(e) {
        academyTable.draw();
        e.preventDefault();
    });

}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

//Modal
function showAddModal( data ) {

    $('#academyModal').modal();
    $('#academyModal').on('shown.bs.modal', function(){
        $('#academyModal .modal-content').html(data);
        $('.selectpicker').selectpicker();      

        $('#create-academy-form select[name=organization_id]').select2({
            width: 'resolve',
            dropdownAutoWidth : true,
            dropdownParent: $('#academyModal'),
            placeholder: "-- {{ trans('display.general_select') }} --",
            minimumInputLength: 3,
            allowClear: true,
            ajax: {
                url: '{!! route('academy.search.org') !!}',
                delay: 500,
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
        }).on('select2:select', function(e){
            $('#create-academy-form input[name=name]').val(e.params.data.name);
            $('#create-academy-form input[name=name_en]').val(e.params.data.name_en);
        });

        $('#create-academy-form').validate({
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
                        $('#academyModal').find("#close").trigger('click');
                        $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                        academyTable.draw();
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

    $('#academyModal').on('hidden.bs.modal', function(){
        $('#academyModal .modal-content').empty();
    });
}

function academyModal(data){
    $('#academyModal').modal();
        $('#academyModal').on('shown.bs.modal', function(){
            $('#academyModal .modal-content').html(data);
            $('.selectpicker').selectpicker();      

            $('#edit-academy-form select[name=new_organization_id]').select2({
                width: 'resolve',
                dropdownAutoWidth : true,
                dropdownParent: $('#academyModal'),
                placeholder: "-- {{ trans('display.general_select') }} --",
                minimumInputLength: 3,
                ajax: {
                    url: '{!! route('academy.search.org') !!}',
                    delay: 500,
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
            }).on('select2:select', function(e){
                $('#create-academy-form input[name=name]').val(e.params.data.name);
                $('#create-academy-form input[name=name_en]').val(e.params.data.name_en);
            });

            $('#edit-academy-form').validate({
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
                        $('#academyModal').find("#close").trigger('click');
                        $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                        academyTable.draw();
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

$('#academyModal').on('hidden.bs.modal', function(){
    $('#academyModal .modal-content').empty();
});

}

//UserDelete
function academyDelete(id)
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
                url: 'academy/' + id,
                type: 'DELETE',
                success: function(response) {
                    $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                    academyTable.draw();
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

function academyEdit(id)
{
    $.get('/academy/' + id + '/edit', academyModal);
}

</script>
@endsection
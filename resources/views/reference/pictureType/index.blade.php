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
                                <h3 class="card-label">{{trans('menu.picture_type')}}
                                <span class="d-block text-muted pt-2 font-size-sm"></span></h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle" id="search">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title collapsed" data-toggle="collapse" data-target="#search-picture-type">
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
                                            <div class="card-label pl-4">{{ trans('display.general_search') }}</div>
                                        </div>
                                    </div>
                                    <div id="search-picture-type" class="collapse" data-parent="#search">
                                        <div class="card-body">
                                            <!--begin: Search Form-->
                                            <form class="mb-10" id="picture-type-search-form" method="POST">
                                                <div class="row mb-6">
                                                    <div class="col-lg-2 mb-lg-0 mb-6">
                                                        <label>{{trans('display.general_name')}}</label>
                                                        <input type="text" class="form-control datatable-input" name="name" id="name" data-col-index="1">
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
                            <!--begin: Datatable-->
                            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <div class="panel-sub-heading">
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline" id="picture_type_datatable" role="grid" aria-describedby="kt_datatable_info" style="width: 1235px;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center border-right" width="15px">No.</th>
                                                    <th width="10%">{{trans('display.general_code')}}</th>
                                                    <th width="20%">{{trans('display.general_description')}}</th>
                                                    <th width="5%">{{trans('display.height')}}</th>
                                                    <th width="5%">{{trans('display.width')}}</th>
                                                    <th width="10%">{{trans('display.object_type')}}</th>
                                                    <th width="30%">{{trans('display.dir_url')}}</th>
                                                    <th width="30%">{{trans('display.general_created_at')}}</th>
                                                    <th width="30px">{{trans('display.general_manage')}}</th>
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
    pictureTypeTable = $("#picture_type_datatable").DataTable({
        processing:     true,
        serverSide:     true,
        deferRender:    true,
        autoWidth:      true,
        filter:         false,
        responsive:     true,
        dataType: 'json',
        paginationType: "full_numbers",
        ajax: {
            url: '{{route('picture.type.data.list')}}',
            type: 'POST',
            data: function ( d ) {
                d.name = $('#picture-type-search-form input[id="name"]').val();
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
            {data: 'name_en'},
            {data: 'abbreviation'},
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
            targets: [0]
        }],
        order: [[ 5, "desc" ]],
        dom: "<'top'B><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
        buttons: [
        {
            text: '<i class="la la-plus"></i> {{ trans('display.general_new') }}',
            className: "btn btn-light-danger font-weight-bolder mb-2 {{ SecurityHelper::checkPermission(@Config::get('permission.picture_type'), Config::get('permission.editable')) ? '' : 'd-none' }}",
            action: function ( e, dt, node, config ) {
                $.get('{!! route('picture.type.create') !!}', showAddModal);
            }
        }]
	});

    $('#picture-type-search-form').on('submit', function(e) {
        pictureTypeTable.draw();
        e.preventDefault();
    });

    $("#kt_reset").click(function(e){
        e.preventDefault();
        $('.datatable-input').each(function() {
            $(this).val('');
            pictureTypeTable.column($(this).data('col-index')).search('', false, false);
        });
        pictureTypeTable.draw();
    });

}).ajaxStart($.blockUI).ajaxStop($.unblockUI);
</script>
@endsection
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
                                <div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle" id="search">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title collapsed" data-toggle="collapse" data-target="#search-academy">
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
                                        <div id="search-weight" class="collapse" data-parent="#search">
                                            <div class="card-body">
                                                <!--begin: Search Form-->
                                                <form class="mb-10" id="weight-search-form" method="POST">
                                                    <div class="row mb-6">
                                                        
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
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline" id="academy_datatable" role="grid" aria-describedby="kt_datatable_info" style="width: 1235px;">
                                                <thead>
                                                    <tr role="row">
                                                        <th>№</th>
                                                        <th width="15%">{{trans('display.comp_entry')}}</th>
                                                        <th width="5%">{{trans('display.human_gender_code')}}</th>
                                                        <th width="20%">{{trans('display.comp_entry_belt')}}</th>
                                                        <th width="20%">{{trans('display.comp_entry_weight')}}</th>
                                                        <th width="20%">{{trans('display.weight_is_finish')}}</th>
                                                        <th width="5%">{{trans('display.weight_award_cermony')}}</th>
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

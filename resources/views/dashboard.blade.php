@extends('default')

@section('styles')

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

                <div class="subheader py-lg-2 subheader-solid" id="kt_subheader">
                    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                        <div class="d-flex align-items-center flex-wrap mr-2">
                            <i class="fas fa-home pr-4" style="color:black"> </i>
                            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                            <h2 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{trans('menu.home')}}</h2>
                            <span style="font-style: italic">
                              
                            </span>
                        </div>

                        <div class="d-flex align-items-center">
                            <a class="btn btn-sm btn-light font-weight-bold mr-2" >
                                <span class="text-muted font-size-base font-weight-bold mr-2">{{trans('display.general_you_are_here')}}:</span>
                                <span class="text-primary font-size-base font-weight-bolder" id="kt_dashboard_daterangepicker_date">{{trans('menu.home')}}</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!--end::Header-->

                <!--begin::Footer-->
                @include('layouts.footer')
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        <!--end::Main-->
</section>

@section('javascript')

@endsection

@endsection


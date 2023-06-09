@extends('default')

@section('css')
@endsection

@section('content')
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
        <!--begin::Content-->
        <div class="content d-flex flex-column flex-column-fluid">
            <!--begin::Subheader-->
            <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
                <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                    <!--begin::Details-->
                    <div class="d-flex align-items-center flex-wrap mr-2">
                        <!--begin::Title-->
                        <h3 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Спортууд</h3>
                        <!--end::Title-->
                        <!--begin::Separator-->
                        <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-5 bg-gray-200"></div>
                        <!--end::Separator-->
                        <!--begin::Search Form-->
                        <div class="d-flex align-items-center" id="kt_subheader_search">
                            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">Нийт </span>
                            
                        </div>
                        <!--end::Search Form-->
                    </div>
                    <!--end::Details-->
                </div>
            </div>
            <!--end::Subheader-->
            <!--begin::Entry-->
            <div class="d-flex flex-column-fluid">
                <!--begin::Container-->
                <div class="container">
                    <!--begin::Row-->
                    @foreach(array_chunk($sports, 3) as $chunk)
                    <div class="row">
                        @foreach($chunk as $sport)
                        <div class="col-xl-4">
                            <!--begin::Card-->
                            <div class="card card-custom overlay">
                                <div class="card-body p-0">
                                    <div class="overlay-wrapper">
                                        <img src="/assets/media/stock-600x400/{{ $sport['id'] }}.jpg" alt="" class="w-100 rounded"/>
                                    </div>
                                    <div class="overlay-layer m-5 rounded align-items-start justify-content-end">
                                        <div class="d-flex flex-column mt-5 mr-5 align-items-end">
                                            <!-- <span class="label label-warning label-xl label-inline mb-1">{{ $sport['name'] }}</span> -->
                                            <a href="#" class="label label-warning label-xl label-inline mb-1 font-size-h4 font-weight-bolder text-white text-hover-light">{{ $sport['name'] }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Card-->
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                    <!--end::Row-->
                </div>

                <!--end::Container-->
            </div>
            <!--end::Entry-->
        </div>
        <!--end::Content-->
        <!--begin::Footer-->
        @include('layouts.footer')
        <!--end::Footer-->
    </div>
    <!--end::Wrapper-->
<!--end::Main-->
@section('javascript')
<script src="{{ asset('assets/js/plugins/custom/datatables/datatables.bundle.js') }}"></script>

@endsection
@stop
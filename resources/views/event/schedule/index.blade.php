@extends('default')

@section('css')
@endsection

@section('content')
<!--begin::Main-->
<!--begin::Wrapper-->
    <div class="d-flex flex-column flex-row-fluid" id="kt_wrapper">
        <div class="content d-flex flex-column flex-column-fluid">
            <!--begin::Subheader-->
            <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
                <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                    <div class="d-flex align-items-center flex-wrap mr-2">
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Тэмцээний хуваарь</h2>
                    </div>
                </div>
            </div>
            <!--end::Subheader-->
            <!--begin::Entry-->
            <div class="d-flex flex-column-fluid">
                <div class="container">
                    <div class="card card-custom gutter-b">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0 mr-7">
                                    <div class="symbol symbol-50 symbol-lg-120">
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
                                        <div class="mr-3">
                                            <span class="d-flex align-items-center text-dark font-size-h5 font-weight-bold mr-3">{{ @$event->name }}</span>
                                            <div class="d-flex flex-wrap my-2">
                                                <span class="text-muted font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                                    <span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24"/>
                                                                <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3"/>
                                                                <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"/>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                    {{ @Carbon\Carbon::parse($event->config->reg_start_date)->format('y M, d g:i A') }} / {{ @Carbon\Carbon::parse(@$event->config->reg_end_date)->format('y M, d g:i A') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap justify-content-between row">
                                        <div class="col-md-12">
                                            <div class="flex-grow-1 font-weight-bold text-dark-50 py-2 py-lg-2 mr-5">{{ Str::words(strip_tags(@$event->description), 20, '...') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- HTML only: search box + match container --}}
                    @include('event.config.tab_match_html')
                </div>
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
{{-- JS runs here, after plugins.bundle.js (jQuery + selectpicker) are loaded --}}
@include('event.config.tab_match_js')
@endsection
@stop
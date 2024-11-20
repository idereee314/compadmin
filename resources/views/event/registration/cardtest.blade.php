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
            <!--begin::Entry-->
            <div class="d-flex flex-column-fluid">
                <!--begin::Container-->
                <div class="container">
                    <div class="card-body ">
                        <!--begin::Nav Tabs-->
                        <ul class="dashboard-tabs nav nav-pills nav-danger row row-paddingless m-0 p-0 flex-column flex-sm-row" role="tablist">
                            <!--begin::Item-->
                            <li class="nav-item d-flex col-sm flex-grow-1 flex-shrink-0 mr-3 mb-3 mb-lg-0">
                            	<a class="nav-link border py-10 d-flex flex-grow-1 rounded flex-column align-items-center active" data-toggle="pill">
                                    <span class="nav-text font-size-lg py-2 font-weight-bolder text-center">
                                        {{ trans('display.upcoming_event_list') }}
                                    </span>
                                </a>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="nav-item d-flex col-sm flex-grow-1 flex-shrink-0 mr-0 mb-3 mb-lg-0">
                            	<a class="nav-link border py-10 d-flex flex-grow-1 rounded flex-column align-items-center" data-toggle="pill">
                                    <span class="nav-text font-size-lg py-2 font-weight-bolder text-center">
                                        {{ trans('display.past_event_list') }}
                                    </span>
                                </a>
                            </li>
                            <!--end::Item-->
                        </ul>
                        <!--end::Nav Tabs-->

                        <!--begin::Nav Content-->
                        <div class="tab-content m-0 p-0">
        	                <div class="tab-pane active" id="upcoming_event_lists" role="tabpanel">

                            </div>
        	                <div class="tab-pane" id="past_event_lists" role="tabpanel">

                            </div>
                        </div>
                        <!--end::Nav Content-->
                    </div>
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
<script>
$(document).ready(function() {
    $(".navi-item #generate-bracket").on('click', function(){
        var eventId = $(this).data('eventid');

        $.ajax({
            url: '{!! route('event.registration.bracket.generation') !!}?event_id='+eventId,
            type: 'GET',
            success: function(response) {
                if(response.status == 'success')
                {
                    toastr.success(response.msg);
                }
                else {
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
    });
}).ajaxStart($.blockUI).ajaxStop($.unblockUI);
</script>
@endsection
@stop
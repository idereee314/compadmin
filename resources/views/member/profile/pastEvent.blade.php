<style>
    .card-content {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      height: 100%;
      font-size: 20px;
      color: black;
    }

    .card-header {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      height: 100%;
      font-size: 20px;
      color: black;
    }
    
    .card-content i {
      margin-bottom: 5px;
    }

    .card {
      margin-bottom: 20px;
    }

    @media only screen and (max-width: 768px) {
      .card {
        width: 100%;
      }
    }
    .event-name {
        justify-content: center;
    }
    
</style>

@extends('default')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/js/plugins/custom/jstree/dist/themes/default/style.min.css')}}">
@endsection
@include('layouts.mobile_v2')
@section('content')

<!--begin::Main-->
<!--begin::Wrapper-->
    <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper" style="background-image: url('{{ asset('assets/media/bg/bg-3.jpg') }}');">
    @include('layouts.header_v2')
        <!--begin::Content-->
        <div class="content d-flex flex-column flex-column-fluid">
            <!--begin::Entry-->
            <div class="d-flex flex-column-fluid">
                <!--begin::Container-->
                <div class="container"> 
                    <!--begin::Subheader-->
                    <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
                        <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                            <!--begin::Details-->
                            <div class="d-flex align-items-center flex-wrap mr-2">
                                <!--begin::Breadcrumb-->
                                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                                    <li class="breadcrumb-item text-muted">
                                        <a href="/event/competition" class="text-muted">Тэмцээнүүд</a>
                                    </li>
                                    <li class="breadcrumb-item text-muted">
                                        <a href="/pastEvent" style="color: #f96815;"><strong>Болж өнгөрсөн жюү жицүгийн тэмцээнүүд</strong></a>
                                    </li>
                                </ul>
                                <!--end::Breadcrumb-->
                            </div>
                            <!--end::Details-->
                        </div>
                    </div>
                    <!--end::Subheader-->  
                    <div class="d-flex justify-content-center mb-5">
                        <h1 class="text-center text-uppercase bold margin-bottom-xs-16 margin-bottom-sm-0" style="font-size: 4rem; color: #0f4b63;"><strong>Болж өнгөрсөн жюү жицүгийн тэмцээнүүд</strong></h1>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-5 pt-5">
                        <div class="col-lg-8">
                            <div>
                                
                                @foreach($pastEventJiuJitsuData as $pastEventJiuJitsu)
                                    <div class="card" style="border-left-color: #0f4b63; border-left-width: 1rem; ">
                                        <div class="card-content">
                                            <div class="event-date">
                                                <i class="flaticon-calendar-with-a-clock-time-tools mr-1" style="color:#f96815;"></i><small>{{date('Y-m-d', strtotime($pastEventJiuJitsu->event_date))}}</small>                                                 
                                            </div> 
                                            <div class="event-name">
                                                <span class="text-center">{{ $pastEventJiuJitsu->event_name }}</span> 
                                            </div> 
                                            <div class="event-location mb-2"">                                                
                                                <small class="text-muted"> <i class="flaticon2-location mr-2" style="color:#f96815;"></i>{{ $pastEventJiuJitsu->object_name }} </small>
                                            </div>
                                            
                                        </div>
                                    </div>
                                 
                                @endforeach   
                            </div>  
                                                                                      
                        </div>
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

@stop

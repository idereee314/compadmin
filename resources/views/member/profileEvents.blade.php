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
    .action-title {
        justify-content: center;
    }
</style>

@extends('default')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/js/plugins/custom/jstree/dist/themes/default/style.min.css')}}">
@endsection
@include('layouts.mobile')
@section('content')

<!--begin::Main-->
<!--begin::Wrapper-->
    <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper" style="background-image: url('assets/media/bg/bg-3.jpg');">
    @include('layouts.header_v2')
        <!--begin::Content-->
        <div class="content d-flex flex-column flex-column-fluid">
            <!--begin::Entry-->
            <div class="d-flex flex-column-fluid">
                <!--begin::Container-->
                <div class="container">
                    <div class="d-flex justify-content-center mb-5">
                        <div class="d-flex align-items-center flex-column">
                            @if(@$member->profile_url xor ((@env('production') && \Storage::disk('s3')->exists($member->profile_url)) || @env('local')))
                                <div class="rounded-cyrcle">
                                    <img src="{{\Storage::disk('s3')->url($member->profile_url)}}" style="background-size:cover; width: 180px; height:180px; border-radius: 50%;">
                                </div>
                            @endif
                            <div class="ml-5 mt-5">                                            
                                <h1 class="text-center text-uppercase bold margin-bottom-xs-16 margin-bottom-sm-0" style="font-size: 4rem; color: #0f4b63;"><strong>{{$member->lastname}} {{$member->firstname}}</strong></h1>
			                </div>  
                            <div class="ml-5">                                            
                                <span class="text-center mr-5" style="font-size: 20px;color: #0f4b63;"><strong>Нас : {{$member->age}}</strong></span>
                                <span class="text-center ml-5" style="font-size: 20px;color: #0f4b63;"><strong>Хүйс : {{ Config::get("enums.gender_code")[@$member->gender_code] }}</strong></span>
			                </div>                                                                                                   
                        </div>                        
                    </div>

                    <!--begin::Subheader-->
                    <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
                        <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                            <!--begin::Details-->
                            <div class="d-flex align-items-center flex-wrap mr-2">
                                <!--begin::Breadcrumb-->
                                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                                    <li class="breadcrumb-item text-muted">
                                        <a href="/profile/{{ $member->id}}" class="text-muted">Профайл</a>
                                    </li>
                                    <li class="breadcrumb-item text-muted">
                                        <a href="/profile/{{ $member->id}}/event" style="color: black;"><strong>Миний бүртгүүлсэн тэмцээн</strong></a>
                                    </li>
                                </ul>
                                <!--end::Breadcrumb-->
                            </div>
                            <!--end::Details-->
                        </div>
                    </div>
                    <!--end::Subheader-->

                    <div class="d-flex justify-content-center mt-5 pt-5">
                        <div class="col-lg-12">                            
                            <div class="card">
                                <div class="card-header" style="color: #0f4b63;"><i class="la la-trophy icon-4x" style="color: #f96815;"></i><strong>Миний бүртгүүлсэн тэмцээн</strong></div>
                                <div class="card-body" style="color: #0f4b63;">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-head-custom" id="event-team-registration-datatable">
                                        <thead>
                                            <tr> 
                                                <th class="text-center" style="color: #0f4b63;">#</th>                                                                                                                                     
                                                <th class="text-center" style="color: #0f4b63;">{{trans('display.event_title')}}</th>
                                                <th class="text-center" style="color: #0f4b63;">{{trans('display.comp_academy_name')}}</th>
                                                <th class="text-center" style="color: #0f4b63;">{{trans('display.comp_entry_belt')}}</th>
                                                <th class="text-center" style="color: #0f4b63;">{{trans('display.comp_entry')}}</th>
                                                <th class="text-center" style="color: #0f4b63;">{{trans('display.comp_entry_age')}}</th>                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($memberAllData as $memberDatas)
                                                <tr>
                                                    <td class="text-center border-right" style="color: #0f4b63;">{{ ++$loop->index }}</td>                                                     
                                                    <td class="text-center border-right" style="color: #0f4b63;"><strong>{{ $memberDatas->event_name }}</strong></td>
                                                    <td class="text-center border-right" style="color: #0f4b63;"><strong>{{ $memberDatas->academy_name }}</strong></td>
                                                    <td class="text-center border-right" style="color: #0f4b63;"><strong>{{ $memberDatas->belt }}</strong></td>
                                                    <td class="text-center border-right" style="color: #0f4b63;"><strong>{{ $memberDatas->entries }}</strong></td>
                                                    <td class="text-center border-right" style="color: #0f4b63;"><strong>{{ $memberDatas->start_age }}-{{ $memberDatas->end_age }}</strong></td>
                                                </tr>
                                            @endforeach 
                                        </tbody> 
                                    </table>
                                </div>
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

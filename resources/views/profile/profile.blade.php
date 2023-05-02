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
                    <div class="d-flex justify-content-center mb-5">
                        <div class="d-flex align-items-center flex-column">
                            @if(@$member->profile_url xor ((@env('production') && \Storage::disk('s3')->exists($member->profile_url)) || @env('local')))
                                <div class="rounded-cyrcle">
                                    <img src="{{\Storage::disk('s3')->url($member->profile_url)}}" style="background-size:cover; width: 180px; height:180px; border-radius: 50%;">
                                </div>
                            @endif
                            <div class="ml-5 mt-5">                                            
                                <h1 class="text-center text-uppercase bold margin-bottom-xs-16 margin-bottom-sm-0" style="font-size: 4rem; color: #0f4b63;"><img class="mb-3 mr-2" src="/assets/images/flags/4x3/{{$countries->abbreviation}}.svg" alt="flag" width="60" height="40 "><strong>{{$member->lastname}} {{$member->firstname}}</strong></h1>
			                </div>  
                            <div class="ml-5">                                            
                                <span class="text-center mr-5" style="font-size: 20px;color: #0f4b63;"><strong>Нас : {{$member->age}}</strong></span>
                                <span class="text-center ml-5" style="font-size: 20px;color: #0f4b63;"><strong>Хүйс : {{ Config::get("enums.gender_code")[@$member->gender_code] }}</strong></span>
                                <span class="text-center ml-5" style="font-size: 20px;color: #0f4b63;"><strong>Улс : {{$countries->name }}</strong></span>
			                </div>                                                                                                   
                        </div>                    
                    </div>

                    <div class="d-flex justify-content-center mt-5 pt-5">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header" style="color: #0f4b63; ">
                                    <strong>Академи</strong>
                                </div>
                                <div class="card-body" style="color: #0f4b63;">
                                    <ul>
                                        @foreach($athleteAcademyInfo as $academyInfo)
                                            <li>
                                                <a href="#" style="color: #0f4b63; font-size: 16px">                                                    
                                                    @if($academyInfo->name == 'Бусад')   
                                                        <strong>{{ $academyInfo->name }} </strong> - {{ $academyInfo->academy_name }}                                                        
                                                    @else 
                                                        {{ $academyInfo->name }}                                                                                        
                                                    @endif
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @if($member->age > 18)
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header" style="color: #0f4b63; ">
                                    <strong>Их сургууль</strong>
                                </div>
                                <div class="card-body" style="color: #0f4b63;">
                                    <ul>
                                        @foreach($athleteUniversityInfo as $universityInfo)
                                            <li>
                                                <a href="#" style="color: #0f4b63; font-size: 16px">                                                  
                                                    @if($universityInfo->name == 'Бусад')   
                                                        <strong>{{ $universityInfo->name }} </strong> - {{ $universityInfo->academy_name }}                                                        
                                                    @else 
                                                        {{ $universityInfo->name }}                                                                                        
                                                    @endif
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header" style="color: #0f4b63; ">
                                    <strong>Сургууль</strong>
                                </div>
                                <div class="card-body" style="color: #0f4b63;">
                                    <ul>
                                        @foreach($athleteSchoolInfo as $schoolInfo)
                                            <li>
                                                <a href="#" style="color: #0f4b63; font-size: 16px">
                                                    @if($schoolInfo->name == 'Бусад')   
                                                        <strong>{{ $schoolInfo->name }} </strong> - {{ $schoolInfo->academy_name }}                                                        
                                                    @else 
                                                        {{ $schoolInfo->name }}                                                                                        
                                                    @endif
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>


                    <div class="d-flex justify-content-center mt-5 pt-5">
                        <div class="col-lg-4">
                            <a href="/profile/{{ $member->id}}/results" target="_blank">
                                <div class="card">
                                <div class="card-content text-center" style="color: #0f4b63;"><i class="fas fa-medal icon-4x" style="color: #f96815;"></i>Оролцсон тэмцээн үр дүн</div>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4">
                            <a href="/profile/{{ $member->id}}/event" target="_blank">
                                <div class="card">
                                    <div class="card-content text-center" style="color: #0f4b63;"><i class="la la-trophy icon-4x" style="color: #f96815;"></i>Бүртгүүлсэн тэмцээн</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-5 pt-5">
                        <div class="col-lg-8" class="event-list">
                            <div class="d-flex align-items-center flex-wrap justify-content-start row mt-5 mx-5 mb-2">
                                <div class="col-lg-6">
                                    <span class="action-title" style="font-size: 16px"><strong> Удахгүй болох жюү жицүгийн тэмцээнүүд </strong></span> 
                                </div>
                                <div class="col-lg-6 text-right">
                                    <a href="/upcoming" class="" target="_blank">
                                        <span class="action-link" style="color:#f96815; font-size: 16px">Бүгдийг харах<span class="ml-2"><i class="fas fa-chevron-right"></i></span></span>
                                    </a>
                                </div> 
                            </div>
                            <div>
                                @foreach($upcomingEventJiuJitsuData as $upcomingjiujitsu)
                                <div class="event-list">
                                    <div class="card" style="border-left-color: #f96815; border-left-width: 1rem; ">
                                        <div class="card-content">                                            
                                            <div class="event-date">
                                                <i class="flaticon-calendar-with-a-clock-time-tools mr-1 mt-2" style="color:#f96815;"></i>
                                                <small>{{date('Y-m-d', strtotime($upcomingjiujitsu->event_date))}}</small>                                                 
                                            </div> 
                                            <div class="event-name mt-2 text-center">
                                                {{ $upcomingjiujitsu->event_name }}
                                            </div>
                                            <!-- <div class="event-reg-date">
                                                <i class="flaticon-calendar-with-a-clock-time-tools mr-1 mt-2" style="color:#f96815;"></i>
                                                <small class="text-muted">{{date('Y-m-d', strtotime($upcomingjiujitsu->reg_start_date))}} -> {{date('Y-m-d', strtotime($upcomingjiujitsu->reg_end_date))}}</small>                                                 
                                            </div>  -->
                                            <div class="event-location mt-2 mb-2">                                                
                                                <small class="text-muted"> <i class="flaticon2-location mr-2" style="color:#f96815;"></i>{{ $upcomingjiujitsu->object_name }} </small>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>
                                @endforeach   
                            </div>                                                              
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-5 pt-5">
                        <div class="col-lg-8" class="event-list">
                            <div class="d-flex align-items-center flex-wrap justify-content-start row mt-5 mx-5 mb-2">
                                <div class="col-lg-6">
                                    <span class="action-title" style="font-size: 16px"><strong> Болж өнгөрсөн жюү жицүгийн тэмцээнүүд </strong></span> 
                                </div>
                                <div class="col-lg-6 text-right">
                                    <a href="/pastEvent" class="" target="_blank">
                                        <span class="action-link" style="color:#f96815; font-size: 16px">Бүгдийг харах<span class="ml-2"><i class="fas fa-chevron-right"></i></span></span>
                                    </a>
                                </div> 
                            </div>
                            <div>
                                @for($i = 0; $i < 3 && $i < count($pastEventJiuJitsuData); $i++)
                                <div class="event-list">
                                    <div class="card" style="border-left-color: #0f4b63; border-left-width: 1rem; ">
                                        <div class="card-content">                                            
                                            <div class="event-date text-center">
                                                <i class="flaticon-calendar-with-a-clock-time-tools mr-1 mt-2" style="color:#f96815;"></i>
                                                <small>{{date('Y-m-d', strtotime($pastEventJiuJitsuData[$i]->event_date))}}</small>                                                 
                                            </div> 
                                            <div class="event-name mt-2 text-center">
                                                {{ $pastEventJiuJitsuData[$i]->event_name }}
                                            </div>
                                            <div class="event-location mt-2 mb-2">                                                
                                                <small class="text-muted"> <i class="flaticon2-location mr-2" style="color:#f96815;"></i>{{ $pastEventJiuJitsuData[$i]->object_name }} </small>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>
                                @endfor   
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

<style>
    .card-content {
        max-width:500px;
        margin-left:auto;
        margin-right:auto;
        background:#151a27;
        position:relative
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

@section('content')

<!--begin::Main-->
<!--begin::Wrapper-->
    <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">

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
                    
                        @if($member->age > 18 && ($athleteUniversityInfo != null || $athleteSchoolInfo != null))
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
                        @elseif($athleteSchoolInfo != null)
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
                </div>
                <!--end::Container-->
            </div>
            <!--end::Entry-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Wrapper-->
<!--end::Main-->
@stop

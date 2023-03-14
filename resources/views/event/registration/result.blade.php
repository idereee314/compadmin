<style>
    .gold-medal-icon {
      color: #FFD700;
    }
    .silver-medal-icon {
        color: #C0C0C0;
    }
    .bronze-medal-icon {
        color: #CD7F32;
    }
    .gold {
        background-color: #FFD700;
    }
    .silver {
        background-color: #C0C0C0;
    }
    .bronze {
        background-color: #CD7F32;
    }
    div.card-title.text-center {
        
        text-align: center;
    }
</style>
@extends('default')
@section('css')
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/jstree/dist/themes/default/style.min.css')}}">
@endsection
@section('content')
@include('layouts.mobile')
@include('layouts.aside')
<!--begin::Main-->
<!--begin::Wrapper-->
    <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
    @include('layouts.header')
        <div class="content d-flex flex-column flex-column-fluid">
            <!--begin::Subheader-->
            <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
                <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                    <!--begin::Details-->
                    <div class="d-flex align-items-center flex-wrap mr-2">
                        <!--begin::Title-->
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">{{trans('display.results')}}</h2>
                        <!--end::Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('event.registration.index').'?event_id='.@$event->id }}" class="text-muted">Бүртгэл</a>
                            </li>
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('event.competition.card') }}" class="text-muted">Тэмцээн</a>
                            </li>
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Details-->
                </div>
            </div>
            <!--end::Subheader-->
            <!--begin::Entry-->
            <div class="d-flex flex-column-fluid">
                <!--begin::Container-->
                <div class="container">
                <div class="card card-custom gutter-b">
                        <div class="card-body">
                            <!--begin::Top-->
                            <div class="d-flex">
                                <!--begin::Pic-->
                                <div class="flex-shrink-0 mr-7">
                                    <div class="symbol symbol-50 symbol-lg-120">
                                        <img alt="Pic" src="{{ \Storage::disk('s3')->url(@$event->picturesMobileCover->first()->dir_url.'/thumbnail/'.@$event->picturesMobileCover->first()->url) }}">
                                    </div>
                                </div>
                                <!--end::Pic-->
                                <!--begin: Info-->
                                <div class="flex-grow-1">
                                    <!--begin::Title-->
                                    <div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
                                        <!--begin::User-->
                                        <div class="mr-3">
                                            <!--begin::Name-->
                                            <a href="{{ route('event.registration.index').'?event_id='.@$event->id }}" class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">{{@$event->name}}
                                            <i class="flaticon2-correct text-success icon-md ml-2"></i></a>
                                            <!--end::Name-->
                                            <!--begin::Contacts-->
                                            <div class="d-flex flex-wrap my-2">
                                                <a href="javascript:;" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                                <span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
                                                    <!--begin::Svg Icon | path:/metronic/theme/html/demo5/dist/assets/media/svg/icons/Communication/Mail-notification.svg-->
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"/>
                                                            <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3"/>
                                                            <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"/>
                                                            <rect fill="#000000" opacity="0.3" x="10" y="9" width="7" height="2" rx="1"/>
                                                            <rect fill="#000000" opacity="0.3" x="7" y="9" width="2" height="2" rx="1"/>
                                                            <rect fill="#000000" opacity="0.3" x="7" y="13" width="2" height="2" rx="1"/>
                                                            <rect fill="#000000" opacity="0.3" x="10" y="13" width="7" height="2" rx="1"/>
                                                            <rect fill="#000000" opacity="0.3" x="7" y="17" width="2" height="2" rx="1"/>
                                                            <rect fill="#000000" opacity="0.3" x="10" y="17" width="7" height="2" rx="1"/>
                                                        </g>
                                                    </svg>
                                                    <!--end::Svg Icon-->
                                                </span>{{ @Carbon\Carbon::parse($event->config->reg_start_date)->format('y M, d g:i A') }} / {{ @Carbon\Carbon::parse(@$event->config->reg_end_date)->format('y M, d g:i A') }}</a>
                                            </div>
                                            <!--end::Contacts-->
                                        </div>
                                        <!--begin::User-->
                                        <!--begin::Actions-->
                                        <!--
                                        <div class="my-lg-0 my-1">
                                            <a href="#" class="btn btn-sm btn-light-primary font-weight-bolder text-uppercase mr-2">Ask</a>
                                            <a href="#" class="btn btn-sm btn-primary font-weight-bolder text-uppercase">Hire</a>
                                        </div>
                                        -->
                                        <!--end::Actions-->
                                    </div>
                                    <!--end::Title-->
                                    <!--begin::Content-->
                                    <div class="d-flex align-items-center flex-wrap justify-content-between row">
                                        <div class="col-md-7">
                                            <!--begin::Description-->
                                            <div class="flex-grow-1 font-weight-bold text-dark-50 py-2 py-lg-2 mr-5">{{ Str::words(strip_tags(@$event->description), 20, '...') }}</div>
                                            <!--end::Description-->
                                        </div>
                                        
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Info-->
                            </div>

                            
                            <!--begin::Accordion-->
                            <div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle" id="search">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title collapsed" data-toggle="collapse" data-target="#search-registration">
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
                                
                                </div>
                            </div>
                            <!--end::Accordion-->

                        <div class="separator separator-solid mb-5"></div>
                        </div>
                    </div>
                    <!--begin::Row-->
                    
                    <div class="row">                        
                        <div class="col-xl-12">
                            <!--begin::Card-->
                            <div class="card card-custom gutter-b">
                                <div class="card-header">
                                    <div class="card-title text-center">Нийт медаль
                                        <!-- <h3 class="card-label text-center"><strong></strong></h3> -->
                                    </div>
                                </div>
                                <div class="card-body">     
                                @if(count($eventAllMedal) > 0 || count($eventAllMedal) == 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-head-custom" id="event-team-registration-datatable">
                                        <thead>
                                            <tr>                                                                                        
                                                <th class="text-center"><i class="fas fa-medal icon-2x gold-medal-icon"></i> АЛТ</th>
                                                <th class="text-center"><i class="fas fa-medal icon-2x silver-medal-icon"></i> МӨНГӨ</th>
                                                <th class="text-center"><i class="fas fa-medal icon-2x bronze-medal-icon"></i> ХҮРЭЛ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($eventAllMedal as $medals)
                                                <tr>                                                    
                                                    <td class="text-center border-right"><strong>{{ $medals->gold }}</strong></td>
                                                    <td class="text-center border-right"><strong>{{ $medals->silver }}</strong></td>
                                                    <td class="text-center border-right"><strong>{{ $medals->bronze }}</strong></td>
                                                </tr>
                                            @endforeach 
                                        </table>
                                        </tbody>                                        
                                    </div>
                                @else
                                    <tr>
                                        <td colspan="12" class="text-center"><strong>{{ trans('messages.empty_toplist') }}</strong></td>
                                    </tr>
                                @endif
                                </div>
                            </div>
                            <!--end::Card-->                            
                        </div>
                       
                        <div class="col-xl-12">
                            <!--begin::Card-->
                            <div class="card card-custom gutter-b">
                                <!-- <div class="card-header">
                                    <div class="card-title text-center">
                                        <h3 class="card-label"><strong></strong></h3>
                                    </div>
                                </div> -->
                                <div class="card-body">    
                                @if(count($eventResult) > 0 || count($eventResult) == 0)                               
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-head-custom" id="event-team-registration-datatable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">{{trans('display.human_name')}}</th>
                                                <th class="text-center">{{trans('display.comp_academy_name')}}</th>
                                                <th class="text-center">{{trans('display.comp_entry')}}</th>
                                                <th class="text-center">{{trans('display.comp_entry_belt')}}</th>
                                                <th class="text-center">{{trans('display.comp_entry_weight')}}</th>
                                            </tr>
                                        </thead>                                        
                                        <tbody>
                                                @foreach($eventResult as $results)
                                                <tr>
                                                    @if($results->place_number > 3)     
                                                    <td class="text-center border-right"><strong>{{ $results->place_number }}</strong></td>
                                                    @else
                                                    <td class="text-center border-right"><strong><i class="{{ Config::get("enums.event_award")[@$results->place_number] }}"></i></strong></td>
                                                    @endif
                                                    <td class="text-center border-right"><strong>{{ $results->fullname }}</strong></td> 
                                                    
                                                    @if($results->academy_name == 'Бусад')                       
                                                    <td class="text-center border-right"><strong>{{ $results->busad }}</strong></td>                                                                                                        
                                                    @else 
                                                    <td class="text-center border-right"><strong>{{ $results->academy_name }}</strong></td>  	            
                                                    @endif

                                                    <td class="text-center border-right"><strong>{{ $results->category_name }} </strong></td>
                                                    <td class="text-center border-right"><strong>{{ $results->bus }} </strong></td>
                                                    <td class="text-center border-right"><strong>{{ $results->weight }}</strong></td>
                                                    
                                                </tr>
                                                @endforeach 
                                        </table>
                                        </tbody>                                        
                                    </div>                                
                                </div>
                                @else
                                    <tr>
                                        <td colspan="12" class="text-center"><strong>{{ trans('messages.empty_toplist') }}</strong></td>
                                    </tr>
                                @endif
                            </div>
                            <!--end::Card-->                            
                        </div>                        
                    </div>                    
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
    @include ($view_path.'.modals')
    <!--end::Wrapper-->
<!--end::Main-->
@section('javascript')
<script src="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<script src="{{asset('assets/js/plugins/custom/jstree/jstree.bundle.js')}}"></script>
<script>

//Modal
function showBracketModal( data ) {

$('#bracketModal').modal();
$('#bracketModal').on('shown.bs.modal', function(){
    $('#bracketModal .card-body').html(data);

    $(this).off('shown.bs.modal');
});

$('#bracketModal').on('hidden.bs.modal', function(){
    $('#bracketModal .card-body').empty();
});
}
</script>
@endsection
@stop
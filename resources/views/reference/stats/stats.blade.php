@extends('default')

@section('css')
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.css')}}">
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
    <!--end::Header-->
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Details-->
                <div class="d-flex align-items-center flex-wrap mr-2">
                    <!--begin::Title-->
                    <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Тэмцээний Статистик</h2>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="javascript:;" class="text-muted">Бүртгэл</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('event.config.index') }}" class="text-muted">Статистик</a>
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
                <!--begin::Card-->
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
                                    <div class="col-md-5">
                                        <!--begin::Progress-->
                                        <div class="d-flex mt-4 mt-sm-0 float-right">
                                            <span class="font-weight-bold mr-4">Бүртгэлийн явц</span>
                                            <div class="progress progress-xs mt-2 mb-2 flex-shrink-0 w-150px w-xl-250px">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{@$progressPercent}}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="font-weight-bolder text-dark ml-4">{{ @$progressPercent }}%</span>
                                        </div>
                                        <!--end::Progress-->
                                    </div>
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Info-->
                        </div>
                        
                        @if(!@$eventFees->isEmpty())
                            <!--begin::Separator-->
                            <div class="separator separator-solid my-7"></div>
                            <!--end::Separator-->
                            <!--begin::Bottom-->
                            <div class="d-flex align-items-center flex-wrap">
                                <!--begin: Item-->
                                <div class="d-flex align-items-center flex-lg-fill mr-10 my-1 btn btn-light-success btn-hover-success btn-filter-amount" data-amount="">
                                    <span class="mr-4">
                                        <i class="flaticon-piggy-bank text-success icon-3x font-weight-bold"></i>
                                    </span>
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bolder font-size-sm">{{ trans('display.general_total') }}/{{ $eventFees->flatten(1)->count() }}</span>
                                        <span class="font-weight-bolder font-size-h5">
                                        <span class="font-weight-bold">{{ trans('display.general_tug') }}</span>{{ number_format($eventFees->flatten(1)->sum('fee_amount'), 0) }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center flex-lg-fill mr-10 my-1 btn btn-light-success btn-hover-success btn-filter-amount" data-amount="">
                                    <span class="mr-4">
                                        <i class="flaticon-piggy-bank text-success icon-3x font-weight-bold"></i>
                                    </span>
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bolder font-size-sm">Зохион байгуулагчруу шилжих</span>
                                        <span class="font-weight-bolder font-size-h5">
                                        <span class="font-weight-bold">{{ trans('display.general_tug') }}</span>{{ number_format($eventFees->flatten(1)->sum('fee_amount') * 0.9, 0) }}</span>
                                    </div>
                                </div>
                                <!--end: Item-->
                                @forelse($eventFees as $key => $fee)
                                <!--begin: Item-->
                                <div class="d-flex align-items-center flex-lg-fill mr-5 my-1 btn btn-hover-light-success btn-filter-amount" data-amount="{{ $key }}">
                                    <span class="mr-4">
                                        <i class="flaticon-pie-chart text-success icon-3x font-weight-bold"></i>
                                    </span>
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bolder font-size-sm">{{ number_format($key, 0) }}/{{ count($fee) }}</span>
                                        <span class="font-weight-bolder font-size-h5">
                                        <span class="text-success font-weight-bold">{{ trans('display.general_tug') }}</span>{{ number_format($fee->sum('fee_amount'), 0) }}</span>
                                    </div>
                                </div>
                                <!--end: Item-->
                                @empty
                                @endforelse
                            </div>                        
                            <!--end::Bottom-->
                            @endif
                        <div class="separator separator-solid"></div>
                    </div>
                    <input type="hidden" name="tab_id" id="tab_id" value="{{ isset($tab_id)? $tab_id: 'tab1-1'}}"/>
                    <input type="hidden" name="event_id" id="event_id" value="{{ @$eventConfig->event->id }}"/>
                    <!--begin::Card header-->
                    <div class="card-header card-header-tabs-line nav-tabs-line-3x">
                        <!--begin::Toolbar-->
                        <div class="card-toolbar">
                            <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                                @forelse(@$tabs as $tab)
                                <li class="nav-item mr-3 {{@$tab_id == $tab['number'] ? 'active' : '' }}">
                                    <a href="#{{$tab['number']}}" data-toggle="tab" name="{{$tab['number']}}" class="nav-link app_tab" data-tabid="{{$tab['number']}}"  data-tabcode="{{$tab['code']}}" data-tabname="{{$tab['name']}}">
                                        <span class="nav-icon">
                                            <i class="fas {{ @$tab['icon'] }}"></i>
                                        </span>
                                        <span class="nav-text font-size-lg">{{ $tab['title'] }}</span>
                                    </a>
                                </li>
                                @empty
                                @endforelse
                            </ul>
                        </div>
                        <div class="pull-right"></div>
                        <div class="clearfix"></div>
                    </div>
                    <!--end::Card header-->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-1">
                                <div class="row">
                                    <div class="col-xl-4">
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b">
                                            <div class="card-header">
                                                <div class="card-title title-center">
                                                    <h3 class="card-label"><strong> Нийт бүртгэл </strong></h3>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                            @if(count($eventRegistrationStatusStats) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-head-custom">
                                                        <thead>
                                                            <tr>                                                                
                                                                <th class="text-center">{{trans('display.general_status')}}</th>
                                                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($eventRegistrationStatusStats as $stats)
                                                                <tr>
                                                                    <td class="min-w-200px border-right"><strong>{{ Config::get("enums.event_registration_status_for_stats")[@$stats->status] }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ $stats->status_count }}</strong></td>
                                                                </tr>
                                                            @endforeach 
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                            <tr>
                                                <td colspan="12" class="text-center"><strong>{{ trans('display.general_no_athlete') }}</strong></td>
                                            </tr>
                                            @endif 
                                            </div>
                                        </div>
                                        <!--end::Card-->
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h3 class="card-label"><strong> {{ trans('display.comp_country_name') }} (Баталгаажсан) </strong></h3>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                            @if(count($eventRegistrationCountryStats) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-head-custom">
                                                        <thead>
                                                            <tr>                                
                                                                <th class="text-center"> {{ trans('display.comp_country_name') }} </th>
                                                                <th class="text-center">{{trans('display.general_org_count')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($eventRegistrationCountryStats as $stats)
                                                                <tr>                                                                                                         
                                                                    <td class="min-w-200px text-center border-right"><strong>{{ $stats->name }} - {{ strtoupper($stats->abbreviation) }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ $stats->count_country }}</strong></td>
                                                                </tr>
                                                            @endforeach 

                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <tr>
                                                    <td colspan="12" class="text-center"><strong>{{ trans('display.general_no_athlete') }}</strong></td>
                                                </tr>
                                            @endif 
                                            </div>
                                        </div>
                                        <!--end::Card-->
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h3 class="card-label"><strong> {{ trans('display.comp_country_name') }} (Бүгд)</strong></h3>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                            @if(count($eventRegistrationCountryAllStats) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-head-custom">
                                                        <thead>
                                                            <tr>                                
                                                                <th class="text-center"> {{ trans('display.comp_country_name') }} </th>
                                                                <th class="text-center">{{trans('display.general_org_count')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($eventRegistrationCountryAllStats as $stats)
                                                                <tr>                                                                                                         
                                                                    <td class="min-w-200px text-center border-right"><strong>{{ $stats->name }} - {{ strtoupper($stats->abbreviation) }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ $stats->count_country }}</strong></td>
                                                                </tr>
                                                            @endforeach 

                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                            <tr>
                                                <td colspan="12" class="text-center"><strong>{{ trans('display.general_no_athlete') }}</strong></td>
                                            </tr>
                                            @endif 
                                            </div>
                                        </div>
                                        <!--end::Card-->
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h3 class="card-label"><strong> Байгууллага (Баталгаажсан) </strong></h3>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                            @if(count($eventRegistrationOrgTypeStats) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-head-custom">
                                                        <thead>
                                                            <tr>                                
                                                                <th class="text-center"> Байгууллага </th>
                                                                <th class="text-center">{{trans('display.general_org_count')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($eventRegistrationOrgTypeStats as $stats)
                                                                <tr>                                                                                                         
                                                                    <td class="min-w-200px text-center border-right"><strong>{{ Config::get("enums.org_type")[@$stats->org_type] }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ $stats->org_count }}</strong></td>
                                                                </tr>
                                                            @endforeach 

                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <tr>
                                                    <td colspan="12" class="text-center"><strong>{{ trans('display.general_no_athlete') }}</strong></td>
                                                </tr>
                                            @endif 
                                            </div>
                                        </div>
                                        <!--end::Card-->
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h3 class="card-label"><strong> Байгууллага (Бүгд)</strong></h3>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                            @if(count($eventRegistrationOrgTypeStats) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-head-custom">
                                                        <thead>
                                                            <tr>                                
                                                                <th class="text-center"> Байгууллага </th>
                                                                <th class="text-center">{{trans('display.general_org_count')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($eventRegistrationOrgTypeStats as $stats)
                                                                <tr>                                                                                                         
                                                                    <td class="min-w-200px text-center border-right"><strong>{{ Config::get("enums.org_type")[@$stats->org_type] }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ $stats->org_count }}</strong></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                            <tr>
                                                <td colspan="12" class="text-center"><strong>{{ trans('display.general_no_athlete') }}</strong></td>
                                            </tr>
                                            @endif 
                                            </div>
                                        </div>
                                        <!--end::Card-->
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h3 class="card-label"><strong> Хүйс (Баталгаажсан) </strong></h3>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                            @if(count($eventRegistrationGenderStats) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-head-custom">
                                                        <thead>
                                                            <tr>                                
                                                                <th class="text-center"> Хүйс </th>
                                                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($eventRegistrationGenderStats as $stats)
                                                                <tr>                                                                                                         
                                                                    <td class="min-w-200px text-center border-right"><strong>{{ Config::get("enums.gender_code")[@$stats->gender_code] }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ $stats->gender_count }}</strong></td>
                                                                </tr>
                                                            @endforeach                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <tr>
                                                    <td colspan="12" class="text-center"><strong>{{ trans('display.general_no_athlete') }}</strong></td>
                                                </tr>
                                            @endif
                                            </div>
                                        </div>
                                        <!--end::Card-->
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h3 class="card-label"><strong> Хүйс (Бүгд)</strong></h3>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                            @if(count($eventRegistrationGenderAllStats) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-head-custom">
                                                        <thead>
                                                            <tr>                                
                                                                <th class="text-center"> Хүйс </th>
                                                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($eventRegistrationGenderAllStats as $stats)
                                                                <tr>                                                                                                         
                                                                    <td class="min-w-200px text-center border-right"><strong>{{ Config::get("enums.gender_code")[@$stats->gender_code] }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ $stats->gender_count }}</strong></td>
                                                                </tr>
                                                            @endforeach 

                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                            <tr>
                                                <td colspan="12" class="text-center"><strong>{{ trans('display.general_no_athlete') }}</strong></td>
                                            </tr>
                                            @endif
                                            </div>
                                        </div>
                                        <!--end::Card-->
                                    </div>
                                    <div class="col-xl-4">
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b">                            
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h3 class="card-label"><strong> Тэмцээний ангилал (Баталгаажсан)</strong></h3>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                            @if(count($eventRegistrationEntriesStats) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-head-custom">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th class="text-center"> Тэмцээнд оролцох төрлүүд </th>
                                                                <th class="text-center"> Хүйс </th>
                                                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($eventRegistrationEntriesStats as $stats)
                                                                <tr>
                                                                    <td class="text-center border-right">{{ ++$loop->index }}</td>
                                                                    <td width="60%" class="border-right"><strong>{{ $stats->name }}</strong></td>
                                                                    <td width="15%" class="text-center border-right"><strong>{{ Config::get("enums.gender_code_for_stats")[@$stats->gender_code] }}</strong></td>
                                                                    <td width="15%" class="text-center border-right"><strong>{{ $stats->entry_count }}</strong></td>
                                                                </tr>
                                                            @endforeach 
                                                        </tbody>
                                                    </table>                                                                                
                                                </div>
                                            @else
                                                <tr>
                                                    <td colspan="12" class="text-center"><strong>{{ trans('display.general_no_athlete') }}</strong></td>
                                                </tr>
                                            @endif
                                            </div>
                                        </div>
                                        <!--end::Card-->
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h3 class="card-label"><strong> Тэмцээний ангилал (Бүгд) </strong></h3>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                            @if(count($eventRegistrationEntriesAllStats) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-head-custom">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th class="text-center"> Тэмцээнд оролцох төрлүүд </th>
                                                                <th class="text-center"> Хүйс </th>
                                                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($eventRegistrationEntriesAllStats as $stats)
                                                                <tr>
                                                                    <td class="text-center border-right">{{ ++$loop->index }}</td>
                                                                    <td width="60%" class="border-right"><strong>{{ $stats->name }}</strong></td>
                                                                    <td width="15%" class="text-center border-right"><strong>{{ Config::get("enums.gender_code_for_stats")[@$stats->gender_code] }}</strong></td>
                                                                    <td width="15%" class="text-center border-right"><strong>{{ $stats->entry_count }}</strong></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>                                        
                                                </div>
                                            @else
                                                <tr>
                                                    <td colspan="12" class="text-center"><strong>{{ trans('display.general_no_athlete') }}</strong></td>
                                                </tr>
                                            @endif
                                            </div>
                                        </div>
                                        <!--end::Card-->
                                    </div>

                                    <div class="col-xl-4">
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h3 class="card-label"><strong> Тэмцээнд бүртгүүлсэн академи (Баталгаажсан)</strong></h3>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                            @if(count($eventRegistrationAcademyStats) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-head-custom">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th class="text-center">{{trans('display.comp_academy_name')}}</th>
                                                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($eventRegistrationAcademyStats as $stats)
                                                                <tr>
                                                                    <td class="text-center border-right">{{ ++$loop->index }}</td>
                                                                    <td class="min-w-200px text-center border-right"><strong>{{ $stats->name }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ $stats->academy_count }}</strong></td>
                                                                </tr>
                                                            @endforeach 
                                                        </tbody>  
                                                    </table>                                                                              
                                                </div>
                                            @else
                                                <tr>
                                                    <td colspan="12" class="text-center"><strong>{{ trans('display.general_no_athlete') }}</strong></td>
                                                </tr>
                                            @endif 
                                            </div>
                                        </div>
                                        <!--end::Card-->
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-2">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-head-custom" id="financeTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="text-center">{{trans('display.payment_id')}}</th>
                                                <th class="text-center">{{trans('display.payment_date')}}</th>
                                                <th class="text-center">{{trans('display.profile_title')}}</th>
                                                <th class="text-center">{{trans('display.comp_academy_name')}}</th>
                                                <th class="text-center">{{trans('display.general_amount')}}</th>
                                                <th class="text-center">{{trans('display.payment_from_type')}}</th>
                                                <th class="text-center">{{trans('display.general_status')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($finance as $payment)
                                                <tr>
                                                    <td class="text-center border-right">{{ ++$loop->index }}</td>
                                                    <td class="text-center border-right"><strong>{{ $payment->paymentid }}</strong></td>
                                                    <td class="text-center border-right"><strong>{{ $payment->date }}</strong></td>
                                                    <td class="min-w-200px text-center border-right"><strong>{{ $payment->lastname }} {{ $payment->firstname }}</strong></td>
                                                    <td class="text-center border-right"><strong>{{ $payment->academyname }}</strong></td>
                                                    <td class="text-center border-right"><strong>{{ $payment->amount }}</strong></td>
                                                    <td class="text-center border-right"><strong>{{ Config::get("enums.payment_from_type")[$payment->from_type] }}</strong></td>
                                                    <td class="text-center border-right">
                                                        <span class="label label-{{ $payment->status == 1 ? 'success' : 'warning' }} label-inline font-weight-lighter mr-2">
                                                            {{ Config::get("enums.payment_status")[$payment->status] }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach 
                                        </tbody>  
                                    </table>                                                                              
                                </div>
                            </div>

                            <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-3">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b">                            
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <?php
                                                    $totalCount = 0;
                                                    foreach ($countedWeightForOrg as $counted) {
                                                        $totalCount += $counted->counted_weight;
                                                    }
                                                    ?>
                                                    <h3 class="card-label"><strong> Нийт жингийн жагсаалт болон [Нийт <?php echo $totalCount; ?> жин байна.]</strong></h3>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-head-custom" id="financeTable" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th class="text-center">{{trans('display.general_category')}}</th>
                                                                <th class="text-center">{{trans('display.human_gender_code')}}</th>
                                                                <th class="text-center">{{trans('display.comp_entry_weight')}}</th>
                                                                <th class="text-center">{{trans('display.comp_entry_belt')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($statsWeightForOrg as $weights)
                                                                <tr>
                                                                    <td class="text-center border-right">{{ ++$loop->index }}</td>
                                                                    <td class="text-center border-right"><strong>{{ $weights->entry_name }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ Config::get("enums.gender_code")[@$weights->gender_code] }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ $weights->weight }}</strong></td>
                                                                    <td class="min-w-200px text-center border-right"><strong>{{ $weights->belt_name }}</strong></td>
                                                                </tr>
                                                            @endforeach 
                                                        </tbody>  
                                                    </table>                                                                              
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Card-->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h3 class="card-label"><strong> Тамирчид бүртгүүлсэн ангилал (Баталгаажсан)</strong></h3>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                            @if(count($registredCountedWeightForOrgApproved) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-head-custom">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th class="text-center">{{trans('display.comp_academy_name')}}</th>
                                                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($registredCountedWeightForOrgApproved as $stats)
                                                                <tr>
                                                                    <td class="text-center border-right">{{ ++$loop->index }}</td>
                                                                    <td class="min-w-200px text-center border-right"><strong>{{ $stats->category_name }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ $stats->weight }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ $stats->belt_name }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ $stats->athlete_count }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ Config::get("enums.gender_code")[@$stats->gender_code] }}</strong></td>
                                                                </tr>
                                                            @endforeach 
                                                        </tbody>  
                                                    </table>                                                                              
                                                </div>
                                            @else
                                                <tr>
                                                    <td colspan="12" class="text-center"><strong>{{ trans('display.general_no_athlete') }}</strong></td>
                                                </tr>
                                            @endif 
                                            </div>
                                        </div>
                                        <!--end::Card-->
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h3 class="card-label"><strong> Тамирчид бүртгүүлсэн ангилал (Бүгд) </strong></h3>
                                                </div>
                                            </div>
                                            <div class="card-body">                                            
                                            @if(count($registredCountedWeightForOrgAll) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-head-custom">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th class="text-center">{{trans('display.comp_academy_name')}}</th>
                                                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                                <th class="text-center">{{trans('display.general_athlete_count')}}</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($registredCountedWeightForOrgAll as $stats)
                                                                <tr>
                                                                    <td class="text-center border-right">{{ ++$loop->index }}</td>
                                                                    <td class="min-w-200px text-center border-right"><strong>{{ $stats->category_name }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ $stats->weight }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ $stats->belt_name }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ $stats->athlete_count }}</strong></td>
                                                                    <td class="text-center border-right"><strong>{{ Config::get("enums.gender_code")[@$stats->gender_code] }}</strong></td>
                                                                </tr>
                                                            @endforeach 
                                                        </tbody>  
                                                    </table>                                                                              
                                                </div>
                                            @else
                                                <tr>
                                                    <td colspan="12" class="text-center"><strong>{{ trans('display.general_no_athlete') }}</strong></td>
                                                </tr>
                                            @endif
                                            </div>
                                        </div>
                                        <!--end::Card-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card-->
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
<script type="text/javascript">

$(document).ready(function() {
    if ('{{ old('tab_id') }}' != '' || '{{ $tab_id }}' != '') {
        $('a[name={{ old('tab_id')? old('tab_id'): $tab_id }}]').trigger('click');
    }
    $('#financeTable').DataTable({
        responsive: true,
        columnDefs: [
            {
                render: function (data, type, row) {
                    return data;
                },
                targets: 1,
            },
            { visible: false, targets: [1] },
        ],
        order: [[2, 'asc']],
    });

}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

</script>
@endsection
@stop

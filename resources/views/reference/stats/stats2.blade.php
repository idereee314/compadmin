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
                        <!-- <div class="separator separator-solid"></div> -->
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
                                                    <h3 class="card-label"><strong> {{ trans('display.comp_country_name') }} [Баталгаажсан] </strong></h3>
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
                                                    <h3 class="card-label"><strong> {{ trans('display.comp_country_name') }} [Бүгд]</strong></h3>
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
                                                    <h3 class="card-label"><strong> Байгууллага [Баталгаажсан] </strong></h3>
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
                                                    <h3 class="card-label"><strong> Байгууллага [Бүгд]</strong></h3>
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
                                                    <h3 class="card-label"><strong> Хүйс [Баталгаажсан] </strong></h3>
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
                                                    <h3 class="card-label"><strong> Хүйс [Бүгд]</strong></h3>
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
                                                    <h3 class="card-label"><strong> Тэмцээний ангилал [Баталгаажсан]</strong></h3>
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
                                                    <h3 class="card-label"><strong> Тэмцээний ангилал [Бүгд] </strong></h3>
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
                                                    <h3 class="card-label"><strong> Тэмцээнд бүртгүүлсэн академи [Баталгаажсан]</strong></h3>
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
                                <div class="row">
                                    <div class="col-lg-12">
		                                <div class="card mb-8">
		                                	<div class="card-body">
		                                		<div class="p-6">
                                                    <?php
                                                    $totalWeights = 0;
                                                    $totalApprovalWeights = 0;
                                                    $totalRegAllWeights = 0;
                                                    foreach ($countedWeightForOrg as $counted) {
                                                        $totalWeights += $counted->counted_weight;
                                                    }
                                                    foreach ($registredCountedWeightForOrgApproved as $counted) {
                                                        $totalApprovalWeights += $counted->total_count;
                                                    }
                                                    foreach ($registredCountedWeightForOrgAll as $counted) {
                                                        $totalRegAllWeights += $counted->total_count;   
                                                    }                                         
                                                    ?>
                                                    <h2 class="card-label"><strong> Тэмцээнд нийт <?php echo $totalWeights; ?> жин байгаагаас <?php echo $totalRegAllWeights ?> жинд хүмүүс бүртгэгдэж үүнээс <?php echo $totalApprovalWeights; ?> жингийн хүмүүс бүртгэлээ баталгаажуулсан байна.</strong></h2>

		                                			<!--begin::Accordion-->
		                                			<div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle" id="accordionExample1">
		                                				<!--begin::Item-->
		                                				<div class="card">
		                                					<!--begin::Header-->
		                                					<div class="card-header" id="headingOne1">
		                                						<div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne1" aria-expanded="false" aria-controls="collapseOne1" role="button">
		                                							<span class="svg-icon svg-icon-primary"><!--begin::Svg Icon | path:/metronic/theme/html/demo3/dist/assets/media/svg/icons/Navigation/Angle-double-right.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                                                <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero"></path>
                                                                                <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "></path>
                                                                            </g>
                                                                        </svg>
                                                                        <!--end::Svg Icon-->
                                                                    </span>
                                                                    <div class="card-label text-dark pl-4">
                                                                        <?php
                                                                        $totalCount = 0;
                                                                        foreach ($countedWeightForOrg as $counted) {
                                                                            $totalCount += $counted->counted_weight;
                                                                        }
                                                                        ?>
                                                                        Нийт жингийн жагсаалт болон [Тэмцээнд нийт <?php echo $totalCount; ?> жин байна.]
                                                                    </div>
		                                						</div>
		                                					</div>
		                                					<!--end::Header-->

		                                					<!--begin::Body-->
		                                					<div id="collapseOne1" class="collapse" aria-labelledby="headingOne1" data-parent="#accordionExample1" style="">
		                                						<!-- <div class="card-body text-dark-50 font-size-lg pl-12">
                                                                        
                                                                </div> -->
                                                                <div class="card-body text-dark-50 font-size-lg pl-12">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-hover table-bordered table-head-custom" style="width:100%">
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
		                                					<!--end::Body-->
		                                				</div>
		                                				<!--end::Item-->

		                                				<!--begin::Item-->
		                                				<div class="card border-top-0">
		                                					<!--begin::Header-->
		                                					<div class="card-header" id="headingTwo1">
		                                						<div class="card-title collapsed" data-toggle="collapse" data-target="#collapseTwo1" aria-expanded="false" aria-controls="collapseTwo1" role="button">
		                                							<span class="svg-icon svg-icon-primary"><!--begin::Svg Icon | path:/metronic/theme/html/demo3/dist/assets/media/svg/icons/Navigation/Angle-double-right.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                                                <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero"></path>
                                                                                <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "></path>
                                                                            </g>
                                                                        </svg>
                                                                        <!--end::Svg Icon-->
                                                                    </span>
                                                                    <div class="card-label text-dark pl-4">
                                                                        <?php
                                                                        $totalCount = 0;
                                                                        foreach ($registredCountedWeightForOrgApproved as $counted) {
                                                                            $totalCount += $counted->total_count;
                                                                        }
                                                                        ?>
                                                                        Тамирчид бүртгүүлсэн ангилал [Баталгаажсан] болон [Нийт <?php echo $totalCount; ?> жин байна.]
                                                                    </div>
		                                						</div>
		                                					</div>
		                                					<!--end::Header-->

		                                					<!--begin::Body-->
		                                					<div id="collapseTwo1" class="collapse" aria-labelledby="headingTwo1" data-parent="#accordionExample1" style="">
		                                						<div class="card-body text-dark-50 font-size-lg pl-12">
                                                                    @if(count($registredWeightForOrgApproved) > 0)
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
                                                                                    @foreach($registredWeightForOrgApproved as $stats)
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
		                                					<!--end::Body-->
		                                				</div>
		                                				<!--end::Item-->

		                                				<!--begin::Item-->
		                                				<div class="card">
		                                					<!--begin::Header-->
		                                					<div class="card-header" id="headingThree1">
		                                						<div class="card-title collapsed" data-toggle="collapse" data-target="#collapseThree1" aria-expanded="false" aria-controls="collapseThree1" role="button">
		                                							<span class="svg-icon svg-icon-primary"><!--begin::Svg Icon | path:/metronic/theme/html/demo3/dist/assets/media/svg/icons/Navigation/Angle-double-right.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                                                <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero"></path>
                                                                                <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "></path>
                                                                            </g>
                                                                        </svg><!--end::Svg Icon-->
                                                                    </span>
                                                                    <div class="card-label text-dark pl-4">
                                                                        <?php
                                                                        $totalCount = 0;
                                                                        foreach ($registredCountedWeightForOrgAll as $counted) {
                                                                            $totalCount += $counted->total_count;
                                                                        }
                                                                        ?>
                                                                        Тамирчид бүртгүүлсэн ангилал [Бүгд] болон [Нийт <?php echo $totalCount; ?> жин байна.]
                                                                    </div>
		                                						</div>
		                                					</div>
		                                					<!--end::Header-->

		                                					<!--begin::Body-->
		                                					<div id="collapseThree1" class="collapse" aria-labelledby="headingThree1" data-parent="#accordionExample1" style="">
		                                						<div class="card-body text-dark-50 font-size-lg pl-12">
                                                                    @if(count($registredWeightForOrgAll) > 0)
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
                                                                                    @foreach($registredWeightForOrgAll as $stats)
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
		                                					<!--end::Body-->
		                                				</div>
		                                				<!--end::Item-->
		                                			</div>
		                                			<!--end::Accordion-->
		                                		</div>
		                                	</div>
		                                </div>
	                                </div>
                                </div>
                            </div>

                            <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-3">
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

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
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">{{trans('display.general_event_stats')}}</h2>
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
                                    <div id="search-registration" class="collapse" data-parent="#search">
                                        <div class="card-body">
                                            <!--begin: Search Form-->
                                            <form class="mb-5" id="event-registration-search-form" method="POST">
                                                <input type="hidden" name="search_event" id="search_event" value="{{ @$event->id }}"/>
                                                <div class="row mb-6">
                                                    <div class="col-lg-3 mb-lg-0 mb-6">
                                                        <label>{{ trans('display.comp_entry') }}:</label>
                                                        <select class="form-control selectpicker datatable-input" name="search_entry" id="search_entry" data-col-index="1">
                                                            <option value="">-- {{ trans('display.general_all') }} --</option>
                                                            @forelse(@$eventEntries as $eventEntry)
                                                            <option value="{{ $eventEntry->id }}">{{ $eventEntry->name }} - {{ @Config::get('enums.gender_code')[$eventEntry->gender_code] }}</option>
                                                            @empty
                                                            @endforelse
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-2 mb-lg-0 mb-6">
                                                        <label>{{ trans('display.comp_entry_age') }}:</label>
                                                        <select class="form-control datatable-input" name="search_entry_age" id="search_entry_age" data-col-index="2">
                                                            <option value="">-- {{ trans('display.general_all') }} --</option>
                                                            
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-2 mb-lg-0 mb-6">
                                                        <label>{{ trans('display.comp_entry_belt') }}:</label>
                                                        <select class="form-control datatable-input" name="search_entry_belt" id="search_entry_belt" data-col-index="3">
                                                            <option value="">-- {{ trans('display.general_all') }} --</option>
                                                            
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-2 mb-lg-0 mb-6">
                                                        <label>{{ trans('display.comp_entry_weight') }}:</label>
                                                        <select class="form-control datatable-input" name="search_entry_weight" id="search_entry_weight" data-col-index="4">
                                                            <option value="">-- {{ trans('display.general_all') }} --</option>
                                                            
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3 mb-lg-0 mb-6">
                                                        <label>{{ trans('display.comp_academy') }}:</label>
                                                        <select class="form-control selectpicker datatable-input" data-live-search="true" name="search_academy" id="search_academy" data-col-index="5">
                                                            <option value="">-- {{ trans('display.general_all') }} --</option>
                                                            @forelse(@$academies as $academy)
                                                            <option value="{{ $academy->id }}">{{ $academy->name }}</option>
                                                            @empty
                                                            @endforelse
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-8">
                                                    <div class="col-lg-3 mb-lg-0 mb-6">
                                                        <label>{{ trans('display.general_date') }}:</label>
                                                        <div class="input-daterange input-group" id="kt_datepicker">
                                                            <input type="text" class="form-control datatable-input" name="search_date[]" id="start" placeholder="From" data-col-index="7" />
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="la la-ellipsis-h"></i>
                                                                </span>
                                                            </div>
                                                            <input type="text" class="form-control datatable-input" name="search_date[]" id="end" placeholder="To" data-col-index="7" />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 mb-lg-0 mb-6">
                                                        <label>Оролцогч:</label>
                                                        <input type="text" class="form-control datatable-input" name="search_member" id="search_member" placeholder="Оролцогчийн мэдээллээр хайх" data-col-index="8"/>
                                                    </div>
                                                    <div class="col-lg-2 mb-lg-0 mb-6">
                                                        <label>Жин шалгасан эсэх:</label>
                                                        <select class="form-control selectpicker datatable-input" name="search_is_weight" id="search_is_weight" data-col-index="9">
                                                            <option value="">-- {{ trans('display.general_all') }} --</option>
                                                            @forelse(@Config::get('enums.boolean_type') as $key => $type)
                                                            <option value="{{ $key }}">{{ $type }}</option>
                                                            @empty
                                                            @endforelse
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-2 mb-lg-0 mb-6">
                                                        <label>{{ trans('display.general_status') }}:</label>
                                                        <select class="form-control selectpicker datatable-input" name="search_status" id="search_status" data-col-index="10">
                                                            <option value="">-- {{ trans('display.general_all') }} --</option>
                                                            @forelse(@Config::get('enums.event_registration_status') as $key => $status)
                                                            <option value="{{ $key }}">{{ $status }}</option>
                                                            @empty
                                                            @endforelse
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3 mb-lg-0 mb-6">
                                                        <label>{{ trans('display.general_amount') }}:</label>
                                                        <select class="form-control selectpicker datatable-input" name="search_amount" id="search_amount" data-col-index="11">
                                                            <option value="">-- {{ trans('display.general_all') }} --</option>
                                                            @forelse($eventFees as $key => $amount)
                                                            <option value="{{ $key }}">{{ $key }}</option>
                                                            @empty
                                                            @endforelse
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-8">
                                                    <div class="col-lg-4 mb-lg-0 mb-6">
                                                        <label>Бүртгэлийн дугаар:</label>
                                                        <input type="text" class="form-control datatable-input" name="search_reg_id" id="search_reg_id" placeholder="Бүртгэлийн дугаар" data-col-index="8"/>
                                                    </div>
                                                </div>
                                                <div class="row mt-8">
                                                    <div class="col-lg-12">
                                                        <button type="submit" class="btn btn-primary btn-primary--icon">
                                                            <span>
                                                                <i class="la la-search"></i>
                                                                <span>{{ trans('display.general_search') }}</span>
                                                            </span>
                                                        </button>
                                                        <button type="reset" class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                                                            <span>
                                                                <i class="la la-close"></i>
                                                                <span>{{ trans('display.general_reset') }}</span>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
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
                        <div class="col-xl-4">
                            <!--begin::Card-->
                            <div class="card card-custom gutter-b">
                                <div class="card-header">
                                    <div class="card-title title-center">
                                        <h3 class="card-label"><strong> Нийт бүртгэл </strong></h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                @if(count($eventRegistrationGenderStats) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-head-custom" id="event-team-registration-datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th class="text-center">{{trans('display.general_status')}}</th>
                                                    <th class="text-center">{{trans('display.general_athlete_count')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($eventRegistrationStatusStats as $stats)
                                                    <tr>
                                                        <td class="text-center border-right">{{ ++$loop->index }}</td>                                                    
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
                                        <h3 class="card-label"><strong> Хүйс (Баталгаажсан) </strong></h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                @if(count($eventRegistrationGenderStats) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-head-custom" id="event-team-registration-datatable">
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
                                @if(count($eventRegistrationGenderStats) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-head-custom" id="event-team-registration-datatable">
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
                                        <h3 class="card-label"><strong> Тэмцээний ангилал (Бүгд) </strong></h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                @if(count($eventRegistrationGenderStats) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-head-custom" id="event-team-registration-datatable">
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
                                        </table>
                                        </tbody>
                                        
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
                                        <h3 class="card-label"><strong> Тэмцээний ангилал (Баталгаажсан)</strong></h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                @if(count($eventRegistrationEntriesStats) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-head-custom" id="event-team-registration-datatable">
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
                                        </table>
                                        </tbody>
                                        
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
                                        <table class="table table-hover table-bordered table-head-custom" id="event-team-registration-datatable">
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
                                        </table>
                                        </tbody>                                        
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
$(document).ready(function() {
    $('.div-tree').jstree({
        "core" : {
            "themes" : {
                "responsive": true
            }
        },
        "types" : {
            "default" : {
                "icon" : "fa fa-folder text-warning"
            },
            "file" : {
                "icon" : "fa fa-file  text-warning"
            }
        },
        "plugins": ["types"]
    });

    $('.div-tree').on('select_node.jstree', function(e,data) {
        var elData = data.node.data.jstree;
        //console.log(data.node.data.jstree.type);
        if(typeof elData.type !== 'undefined' && elData.type == 'file')
        {
            $.get('/bracket/{{@$event->id}}/'+elData.entry_id+'/'+elData.age_id+'/'+elData.belt_id+'/'+elData.weight_id+'', showBracketModal);
        }
    });
}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

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
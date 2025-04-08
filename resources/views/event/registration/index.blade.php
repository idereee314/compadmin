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
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Details-->
                <div class="d-flex align-items-center flex-wrap mr-2">
                    <!--begin::Title-->
                    <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Оролцогчдын жагсаалт</h2>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="javascript:;" class="text-muted">Бүртгэл</a>
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
                                        <a href="javascript:;" class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">{{@$event->name}} 
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
                        <!--end::Top-->
                        @if(Auth::user()->roles->first() == null || Auth::user()->roles->first()->code == 'staff')
                        @elseif(Auth::user()->roles->first()->code == 'admin' || 
                                Auth::user()->roles->first()->code == 'mjjf' ||
                                (isset(Auth::user()->roles[1]) && Auth::user()->roles[1]->code == 'mjjf') || 
                                Auth::user()->roles->first()->code == 'event')
                            
                            
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
                        @endif
                        
                    </div>
                </div>
                <!--begin::Card-->
                <div class="card card-custom">
                    <div class="card-body">
                        @if(@$eventRegStatusCount)
                        <div class="d-flex align-items-center flex-wrap">
                            <!--begin: Item-->
                            <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                                <span class="mr-4">
                                    <i class="flaticon-users icon-2x"></i>
                                </span>
                                <div class="d-flex flex-column flex-lg-fill">
                                    <span class="text-dark-75 font-weight-bolder font-size-sm">{{ array_sum(@$eventRegStatusCount) }} {{ trans('display.general_all') }}</span>
                                    <a href="javascript:;" class="text-primary font-weight-bolder btn-filter-status-count" data-status="">Харах</a>
                                </div>
                            </div>                           
                            @forelse(@$eventRegStatusCount as $key => $count)
                            <!--begin: Item-->
                            <div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
                                <span class="mr-4">
                                    <i class="flaticon-user-add icon-2x"></i>
                                </span>
                                <div class="d-flex flex-column">
                                    <span class="text-dark-75 font-weight-bolder font-size-sm">{{ $count }} {{ @Config::get('enums.event_registration_status')[$key] }}</span>
                                    <a href="#" class="text-primary font-weight-bolder btn-filter-status-count" data-status="{{ $key }}">Харах</a>
                                </div>
                            </div>
                            <!--end: Item-->
                            @empty
                            @endforelse
                        </div>
                        <div class="separator separator-solid mt-5"></div>
                        @endif
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
                                                    <label>Шагнал авсан эсэх:</label>
                                                    <select class="form-control selectpicker datatable-input" name="is_award" id="is_award" data-col-index="9">
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
                                                
                                                <div class="col-lg-2 mb-lg-0 mb-4 mt-5 ">
                                                    <label>Хасагдсан эсэх:</label>
                                                    <select class="form-control selectpicker datatable-input" name="search_is_disqualify" id="search_is_disqualify" data-col-index="9">
                                                        <option value="">-- {{ trans('display.general_all') }} --</option>
                                                        @forelse(@Config::get('enums.boolean_type') as $key => $type)
                                                        <option value="{{ $key }}">{{ $type }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </div>

                                                <div class="col-lg-2 mb-lg-0 mb-4 mt-5">
                                                    <label>{{ trans('display.comp_country_name') }}:</label>
                                                    <select class="form-control selectpicker datatable-input" data-live-search="true" name="search_country" id="search_country" data-col-index="5">
                                                        <option value="">-- {{ trans('display.general_all') }} --</option>
                                                        @forelse(@$countries as $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </div>

                                                <!-- <div class="col-lg-2 mb-lg-0 mb-6 mt-5">
                                                    <label>Оролцогчдийн тоо:</label>
                                                    <input type="text" class="form-control datatable-input" name="search_memberCount" id="search_memberCount" placeholder="Оролцогчдийн тоо бичнэ үү" data-col-index="8"/>
                                                </div> -->
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
                        <!--begin: Datatable-->
                        <table class="table table-separate table-head-custom" id="event-registration-datatable" style="margin-top: 13px !important">
                            <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th width="20%">{{trans('display.comp_member')}}</th>
                                    <th width="10%">{{trans('display.comp_entry')}}</th>
                                    <th width="5%">{{trans('display.comp_entry_age')}}</th>
                                    <th width="8%">{{trans('display.human_gender_code')}}</th>
                                    <th width="8%">{{trans('display.comp_entry_belt')}}</th>
                                    <th width="5%">{{trans('display.comp_entry_weight')}}</th>
                                    <th width="10%">{{trans('display.comp_academy')}}</th>
                                    <th width="1%">{{trans('display.general_status')}}</th>
                                    <th width="1%">{{trans('display.comp_place_number')}}</th>
                                    <th width="8%">{{trans('display.general_created_at')}}</th>
                                    <th width="15%">{{trans('display.general_manage')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <!--end: Datatable-->
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
<script>
$(document).ready(function() {
        eventTable = $("#event-registration-datatable").DataTable({
        processing: false,
        serverSide: true,
        autoWidth: true,
        select: true,
        responsive: true,
        dataType: 'json',
        paginationType: "full_numbers",
        ajax: {
            url: '{{ route('event.registration.data.list') }}',
            type: 'POST',
            data: function ( d ) {
                var dateArr = {};
                $('#event-registration-search-form input[name^="search_date"]').map(function(){
                    dateArr[this.id] = this.value;
                }).get();
                d.event = $('#event-registration-search-form input[id="search_event"]').val();
                d.entry = $('#event-registration-search-form select[id="search_entry"]').val();
                d.entryAge = $('#event-registration-search-form select[id="search_entry_age"]').val();
                d.entryBelt = $('#event-registration-search-form select[id="search_entry_belt"]').val();
                d.entryWeight = $('#event-registration-search-form select[id="search_entry_weight"]').val();
                d.status = $('#event-registration-search-form select[id="search_status"]').val();
                d.member = $('#event-registration-search-form input[id="search_member"]').val();
                d.gender = $('#event-registration-search-form select[id="search_gender"]').val();
                d.academy = $('#event-registration-search-form select[id="search_academy"]').val();
                d.is_weight = $('#event-registration-search-form select[id="search_is_weight"]').val();
                d.is_disqualify = $('#event-registration-search-form select[id="search_is_disqualify"]').val();
                d.amount = $('#event-registration-search-form select[id="search_amount"]').val();
                d.reg_id = $('#event-registration-search-form input[id="search_reg_id"]').val();
                d.countEntryWeight = $('#event-registration-search-form input[id="search_memberCount"]').val();   
                d.country = $('#event-registration-search-form select[id="search_country"]').val();
                d.date = dateArr;
                d.is_award = $('#event-registration-search-form select[id="is_award"]').val();
            },
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                width: "30px"
            },
            {data: 'member', "defaultContent": ""},
            {data: 'entry.name', "defaultContent": ""},
            {data: 'gender', "defaultContent": ""},
            {
                data: 'age',
                render: function (data, type, row, meta) {
                    var age;
                    if(data.end_age != null)
                    {
                        age = data.start_age + '-' + data.end_age;
                    }
                    else 
                    {
                        age = data.start_age + '+';
                    }
                    return age;
                },
                name: "age.start_age", "defaultContent": ""
            },
            {data: 'belt.name', "defaultContent": ""},
            {data: 'weight.weight', "defaultContent": ""},
            {data: 'academy_name'},
            {data: 'status', "defaultContent": ""},
            {data: 'award', "defaultContent": ""},
            {data: 'created_at'},
            {data: 'action'},
        ],
        columnDefs: [
        {
            searchable: false,
            orderable: false,
            targets: [0,1,9]
        },{
            class: "text-center",
            targets: [0]
        }],
        order: [[ 10, "desc" ]],
        dom: "<'row'<'col-sm-8 text-left'B><'col-sm-6 text-right'<'#colvis'>>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
        buttons: [
            {
                text: '<i class="la la-plus"></i> Шинээр нэмэх',
                className: "btn btn-light-danger font-weight-bolder mb-2 {{ SecurityHelper::checkPermission(@Config::get('permission.event_registration'), Config::get('permission.editable')) ? '' : 'd-none' }}",
                action: function ( e, dt, node, config ) {
                    $.get('{!! route('event.registration.create') !!}?event_id='+$('#event-registration-search-form input[id="search_event"]').val(), showAddModal);
                }
            },
            {
                text: '<i class="far fa-address-card"></i> Мандат хэвлэх',
                className: "btn btn-light-success font-weight-bolder mb-2 {{ SecurityHelper::checkPermission(@Config::get('permission.event_registration'), Config::get('permission.editable')) ? '' : 'd-none' }}",
                action: function ( e, dt, node, config ) {
                    window.open('{!! route('event.registration.print.mandat') !!}?'+$('#event-registration-search-form').serialize(), '_blank');
                }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-print"></i> Оролцогчдын жагсаалт {!! trans('display.general_excel') !!}',
                className: "btn btn-light-warning font-weight-bolder mb-2",
                title: 'Оролцогчийн жагсаалт',
                customize: function ( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('c[r=A1] t', sheet).text( 'Тэмцээнд оролцогчид' );
                },
                exportOptions: {
                    columns: [ 0,1,2,3,4,5,6,7,8,9,10],
                    modifier: {
                        order: 'current',
                        page: 'all',
                        focused: undefined,
                        selected: undefined
                    }
                }
            },
        ]
	});

    $('#kt_datepicker').datepicker({
        todayHighlight: true,
        format: 'yyyy-mm-dd',
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>',
        },
    });

    $('#event-registration-search-form').on('submit', function(e) {
        eventTable.draw();
        e.preventDefault();
    });

    $('#event-registration-datatable tbody').on( 'click', 'tr td a.edit', function () {
        var id = $(this).data("registrationid");

        $.get('registration/'+id+'/edit', showEditModal);
    });

    $('#event-registration-datatable tbody').on( 'click', 'tr td a.weight', function () {
        var id = $(this).data("registrationid");

        $.get('registration/'+id+'/weight', showWeightModal);
    });

    $('#event-registration-datatable tbody').on( 'click', 'tr td button.btn-status', function () {
        var id = $(this).data("registrationid");

        $.get('registration/change/status?reg_id='+id, showStatusModal);
    });

    $('#event-registration-datatable tbody').on( 'click', 'tr td a.show-image', function () 
    {
        var id = $(this).data("id");
        var type = $(this).data("type");

        $.get('/member/show/image/'+type+'/'+id, function( data ) {
            $('#showImageModal').modal();
            $('#showImageModal').on('shown.bs.modal', function(){
                $('#showImageModal .modal-content').html(data);

                $(this).off('shown.bs.modal');
            });

            $('#showImageModal').on('hidden.bs.modal', function(){
                $('#showImageModal .modal-content').empty();
            });
        });
    });

    $('#event-registration-datatable tbody').on( 'click', 'tr td a.win-place', function () 
    {
        var id = $(this).data("registrationid");

        $.get('registration/create/award?event_reg_id='+id, function( data ) {
            $('#memberModal').modal();
            $('#memberModal').on('shown.bs.modal', function(){
                $('#memberModal .modal-content').html(data);
                $('#event-award-form').validate({
                    ignore: [],
                    highlight:function(element) {
                        $(element).parents('.form-group').addClass('has-error has-feedback');
                    },
                    unhighlight: function(element) {
                        $(element).parents('.form-group').removeClass('has-error');
                    },
                    submitHandler: function(form) {
                        $.ajax({
                            url: form.action,
                            type: form.method,
                            data: new FormData(form),
                            success: function(response) {
                                var page = eventTable.page.info().page;
                                if(response.status == 'success')
                                {
                                    $('#memberModal').find("#close").trigger('click');
                                    toastr.success(response.msg);
                                    eventTable.page(page).draw('page');
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
                    },
                    errorPlacement: function(error, element) {
                        if($(element).parents('.form-group').find(".error-here")){
                            error.appendTo($(element).parents('.form-group').find(".error-here"));
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });

                $(this).off('shown.bs.modal');
            });

            $('#memberModal').on('hidden.bs.modal', function(){
                $('#memberModal .modal-content').empty();
            });
        });
    });

    $('#event-registration-datatable tbody').on( 'click', 'tr td a.delete', function () {
        var id = $(this).data("registrationid");

        Swal.fire({
            title: "Та устгахдаа итгэлтэй байна уу",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Тийм",
            cancelButtonText: 'Үгүй',
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: 'btn btn-secondary'
            },
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: 'registration/' + id,
                    type: 'DELETE',
                    success: function(response) {
                        if(response.status == 'success')
                        {
                            toastr.success(response.msg);
                            eventTable.draw();
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
                    async: false
                });
            }
        });
    });

    $('#event-registration-search-form select[name=search_entry]').on('change', function(){
        var entryId = $(this).val();
        var jsonConfig;
        var jsonDataAge;
        var jsonDataBelt;

        $.ajax({
            type: 'POST',
            url: '{!! route('event.registration.take.config') !!}',
            data: {entry_id: entryId},
            success: function (data) {
                jsonConfig = JSON.parse(data);
                jsonDataAge = jsonConfig['age'];
                jsonDataBelt = jsonConfig['belt'];
            },
            error: function (xhr, textStatus, error) {
                console.log(xhr.statusText);
                console.log(textStatus);
                console.log(error);
            },
            async: false
        });

        $('#event-registration-search-form select[name=search_entry_age]').select2({
            placeholder: "-- {{ trans('display.general_all') }} --",
            data: jsonDataAge,
            id: 'id',
            closeOnSelect: true,
            allowClear: true,
            templateSelection: function (item) {
                return item.name;
            },
            templateResult: function (item) {
                return item.name;
            }
        });

        $('#event-registration-search-form select[name=search_entry_belt]').select2({
            placeholder: "-- {{ trans('display.general_all') }} --",
            data: jsonDataBelt,
            id: 'id',
            closeOnSelect: true,
            allowClear: true,
            templateSelection: function (item) {
                return item.name;
            },
            templateResult: function (item) {
                return item.name;
            }
        });

        $('#event-registration-search-form select[name=search_entry_weight]').select2({data: ""});
    });

    $('#event-registration-search-form select[name=search_entry_age]').on('change', function(){
        var ageId = $(this).val();
        var jsonDataWeight;

        $.ajax({
            type: 'POST',
            url: '{!! route('event.entry.weight.by.age') !!}',
            data: {entry_age_id: ageId},
            success: function (data) {
                jsonDataWeight = JSON.parse(data);
            },
            error: function (xhr, textStatus, error) {
                console.log(xhr.statusText);
                console.log(textStatus);
                console.log(error);
            },
            async: false
        });

        $('#event-registration-search-form select[name=search_entry_weight]').select2({
            placeholder: "-- {{ trans('display.general_all') }} --",
            data: jsonDataWeight,
            id: 'id',
            closeOnSelect: true,
            allowClear: true,
            templateSelection: function (item) {
                return item.weight;
            },
            templateResult: function (item) {
                return item.weight;
            }
        }); 
    });

    $('#event-registration-search-form select[name=search_entry_age]').on('change', function(){
        var ageId = $(this).val();
        var jsonDataWeight;

        $.ajax({
            type: 'POST',
            url: '{!! route('event.entry.weight.by.age') !!}',
            data: {entry_age_id: ageId},
            success: function (data) {
                jsonDataWeight = JSON.parse(data);
            },
            error: function (xhr, textStatus, error) {
                console.log(xhr.statusText);
                console.log(textStatus);
                console.log(error);
            },
            async: false
        });
    });

    $(".btn-filter-status-count").on('click', function(){
        var status = $(this).data('status');

        $('#event-registration-search-form select[name=search_status]').val(status);
        $('#event-registration-search-form').submit();
    });

    $(".btn-filter-amount").on('click', function(){
        var amount = $(this).data('amount');

        $('#event-registration-search-form select[name=search_status]').val('{{ @Config::get('smart.event_registration_status')['approved'] }}');
        $('#event-registration-search-form select[name=search_amount]').val(amount);
        $('#event-registration-search-form').submit();
    });

    $("#kt_reset").click(function(e){
        e.preventDefault();
        $('.datatable-input').each(function() {
            $(this).val('');
            eventTable.column($(this).data('col-index')).search('', false, false);

            $("#search_entry").val('').selectpicker("refresh");            
            $('#event-registration-search-form select[name=search_entry_age]').select2({data: ""});
            $('#event-registration-search-form select[name=search_entry_belt]').select2({data: ""});
            $('#event-registration-search-form select[name=search_entry_weight]').select2({data: ""});
            $("#search_academy").val('').selectpicker("refresh"); 
            $("#search_is_weight").val('').selectpicker("refresh");
            $("#search_is_disqualify").val('').selectpicker("refresh");
            $("#search_status").val('').selectpicker("refresh");
            $("#search_amount").val('').selectpicker("refresh");
            $("#search_country").val('').selectpicker("refresh"); 
            
        });
        eventTable.draw();
    });

}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

//Modal
function showAddModal( data ) {

    $('#memberModal').modal();
    $('#memberModal').on('shown.bs.modal', function(){
        $('#memberModal .modal-content').html(data);
        $('.selectpicker').selectpicker();

        $('#create-event-registration-form select[name=member_id]').select2();
        $('#create-event-registration-form input[name=entry_age_id]').select2({data: ""});
        $('#create-event-registration-form input[name=entry_belt_id]').select2({data: ""});
        $('#create-event-registration-form input[name=entry_weight_id]').select2({data: ""});
        $('#create-event-registration-form input[name=academy_id]').select2({data: ""});

        $('#create-event-registration-form select[name=entry_id]').on('change', function(){
            var entryId = $(this).val();
            var jsonConfig;
            var jsonDataAge;
            var jsonDataBelt;

            $.ajax({
                type: 'POST',
                url: '{!! route('event.registration.take.config') !!}',
                data: {entry_id: entryId},
                success: function (data) {
                    jsonConfig = JSON.parse(data);
                    jsonDataAge = jsonConfig['age'];
                    jsonDataBelt = jsonConfig['belt'];
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

            $('#create-event-registration-form input[name=entry_age_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: jsonDataAge,
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                templateSelection: function (item) {
                    return item.name;
                },
                templateResult: function (item) {
                    return item.name;
                }
            });

            $('#create-event-registration-form input[name=entry_belt_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: jsonDataBelt,
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                templateSelection: function (item) {
                    return item.name;
                },
                templateResult: function (item) {
                    return item.name;
                }
            });

            $('#create-event-registration-form input[name=entry_weight_id]').select2({data: ""});
        });
        
        $('#create-event-registration-form input[name=entry_age_id]').on('change', function(){
            var ageId = $(this).val();
            var jsonDataWeight;

            $.ajax({
                type: 'POST',
                url: '{!! route('event.entry.weight.by.age') !!}',
                data: {entry_age_id: ageId},
                success: function (data) {
                    jsonDataWeight = JSON.parse(data);
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

            $('#create-event-registration-form input[name=entry_weight_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: jsonDataWeight,
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                templateSelection: function (item) {
                    return item.weight;
                },
                templateResult: function (item) {
                    return item.weight;
                }
            }); 
        });
        
        $('#create-event-registration-form select[name=member_id]').select2({
            width: 'resolve',
            dropdownAutoWidth : true,
            dropdownParent: $('#memberModal'),
            placeholder: "-- {{ trans('display.general_select') }} --",
            minimumInputLength: 3,
            ajax: {
                url: '{!! route('member.search') !!}',
                delay: 1500,
                data: function (params) {
                    var query = {
                        q: params.term
                    }
                    return query;
                },

                processResults: function (data) {
                    console.log(data);
                    return {
                        results: JSON.parse(data)
                    };
                },
                cache: true
            },
            templateSelection: function (item) {
                return item.fullname;
            },
            templateResult: function (item) {
                return item.fullname;
            }
        });

        $('#create-event-registration-form select[name=academy_id]').on('change', function(){
            var academyId = $(this).val(); 
            $.ajax({
                type: 'POST',
                url: '{!! route('academy.isother') !!}',
                data: {academy_id: academyId},
                success: function (data) {
                    $('#academy_name_other').addClass('d-none');
                    $("#academy_name").attr("disabled", true);
                    $("#academy_name").val("");
                    jsonData = JSON.parse(data);

                    if(jsonData) {
                        $('#academy_name_other').removeClass('d-none');
                        $("#academy_name").attr("disabled", false);
                    }              
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });
        }) 
        

        $('#create-event-registration-form').validate({
            ignore: [],
            highlight:function(element) {
                $(element).parents('.form-group').addClass('has-error has-feedback');
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').removeClass('has-error');
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: new FormData(form),
                    success: function(response) {
                        if(response.status == 'success')
                        {
                            $('#memberModal').find("#close").trigger('click');
                            toastr.success(response.msg);
                            eventTable.draw();
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
            },
            errorPlacement: function(error, element) {
                if($(element).parents('.form-group').find(".error-here")){
                    error.appendTo($(element).parents('.form-group').find(".error-here"));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        $(this).off('shown.bs.modal');
    });

    $('#memberModal').on('hidden.bs.modal', function(){
        $('#memberModal .modal-content').empty();
    });
}

function showEditModal(data){
    $('#memberModal').modal();
    $('#memberModal').on('shown.bs.modal', function(){
        $('#memberModal .modal-content').html(data);
        $('.selectpicker').selectpicker();
        $('#update-event-registration-form select[name=entry_age_id]').select2({
            placeholder: "-- {{ trans('display.general_select') }} --"
        });
        $('#update-event-registration-form select[name=entry_belt_id]').select2({
            placeholder: "-- {{ trans('display.general_select') }} --"
        });
        $('#update-event-registration-form select[name=entry_weight_id]').select2({
            placeholder: "-- {{ trans('display.general_select') }} --"
        });
        
        $('#update-event-registration-form select[name=entry_id]').on('change', function(){
            var entryId = $(this).val();
            var jsonDataConfig;
            var jsonDataBelt;
            var jsonDataAge;

            $.ajax({
                type: 'POST',
                url: '{!! route('event.registration.take.config') !!}',
                data: {entry_id: entryId},
                success: function (data) {
                    jsonDataConfig = JSON.parse(data);
                    jsonDataBelt = jsonDataConfig['belt'];
                    jsonDataAge = jsonDataConfig['age'];
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

            $('#update-event-registration-form select[name=entry_belt_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: jsonDataBelt,
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                templateSelection: function (item) {
                    return item.name;
                },
                templateResult: function (item) {
                    return item.name;
                }
            });

            $('#update-event-registration-form select[name=entry_age_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data:jsonDataAge,
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                templateSelection: function (item) {
                    return item.name;
                },
                templateResult: function (item) {
                    return item.name;
                }
            });

            $('#update-event-registration-form select[name=entry_weight_id]').select({data: ''});
        });

        $('#update-event-registration-form select[name=entry_age_id]').on('change', function(){
            var ageId = $(this).val();
            var jsonDataWeight;

            $.ajax({
                type: 'POST',
                url: '{!! route('event.entry.weight.by.age') !!}',
                data: {entry_age_id: ageId},
                success: function (data) {
                    jsonDataWeight = JSON.parse(data);
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

            $('#update-event-registration-form select[name=entry_weight_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: jsonDataWeight,
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                templateSelection: function (item) {
                    return item.weight;
                },
                templateResult: function (item) {
                    return item.weight;
                }
            });
        });

        $('#update-event-registration-form select[name=academy_id]').on('change', function(){
            var academyId = $(this).val(); 
            $.ajax({
                type: 'POST',
                url: '{!! route('academy.isother') !!}',
                data: {academy_id: academyId},
                success: function (data) {
                    $('#academy_name_other').addClass('d-none');
                    $("#academy_name").attr("disabled", true);
                    $("#academy_name").val("");
                    jsonData = JSON.parse(data);

                    if(jsonData) {
                        $('#academy_name_other').removeClass('d-none');
                        $("#academy_name").attr("disabled", false);
                    }              
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });
        });

        $('#update-event-registration-form input[name=is_weight_checked]').on('click', function(){
            if($(this).is(':checked')){
                $('#update-event-registration-form input[name=current_weight]').prop('disabled', false);
                $('#update-event-registration-form input[name=weight_desc]').prop('disabled', false);
            } else {
                $('#update-event-registration-form input[name=current_weight]').prop('disabled', true);
                $('#update-event-registration-form input[name=weight_desc]').prop('disabled', true);
            }
        });

        $('#update-event-registration-form').validate({
            ignore: [],
            highlight:function(element) {
                $(element).parents('.form-group').addClass('has-error has-feedback');
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').removeClass('has-error');
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: new FormData(form),
                    success: function(response) {
                        var page = eventTable.page.info().page;
                        if(response.status == 'success')
                        {
                            $('#memberModal').find("#close").trigger('click');
                            toastr.success(response.msg);
                            eventTable.page(page).draw('page');
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
            },
            errorPlacement: function(error, element) {
                if($(element).parents('.form-group').find(".error-here")){
                    error.appendTo($(element).parents('.form-group').find(".error-here"));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        $('#update-event-registration-form select[name=status]').trigger('change');

        $(this).off('shown.bs.modal');
    });

    $('#memberModal').on('hidden.bs.modal', function(){
        $('#memberModal .modal-content').empty();
    });
}

function showWeightModal(data){
    $('#weightModal').modal();
    $('#weightModal').on('shown.bs.modal', function(){
        $('#weightModal .modal-content').html(data);
        $('.selectpicker').selectpicker();
        $('#update-event-registration-form select[name=entry_age_id]').select2({
            placeholder: "-- {{ trans('display.general_select') }} --"
        });
        $('#update-event-registration-form select[name=entry_belt_id]').select2({
            placeholder: "-- {{ trans('display.general_select') }} --"
        });
        $('#update-event-registration-form select[name=entry_weight_id]').select2({
            placeholder: "-- {{ trans('display.general_select') }} --"
        });
        
        $('#update-event-registration-form select[name=entry_id]').on('change', function(){
            var entryId = $(this).val();
            var jsonDataConfig;
            var jsonDataBelt;
            var jsonDataAge;

            $.ajax({
                type: 'POST',
                url: '{!! route('event.registration.take.config') !!}',
                data: {entry_id: entryId},
                success: function (data) {
                    jsonDataConfig = JSON.parse(data);
                    jsonDataBelt = jsonDataConfig['belt'];
                    jsonDataAge = jsonDataConfig['age'];
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

            $('#update-event-registration-form select[name=entry_belt_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: jsonDataBelt,
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                templateSelection: function (item) {
                    return item.name;
                },
                templateResult: function (item) {
                    return item.name;
                }
            });

            $('#update-event-registration-form select[name=entry_age_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data:jsonDataAge,
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                templateSelection: function (item) {
                    return item.name;
                },
                templateResult: function (item) {
                    return item.name;
                }
            });

            $('#update-event-registration-form select[name=entry_weight_id]').select({data: ''});
        });

        $('#update-event-registration-form select[name=entry_age_id]').on('change', function(){
            var ageId = $(this).val();
            var jsonDataWeight;

            $.ajax({
                type: 'POST',
                url: '{!! route('event.entry.weight.by.age') !!}',
                data: {entry_age_id: ageId},
                success: function (data) {
                    jsonDataWeight = JSON.parse(data);
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

            $('#update-event-registration-form select[name=entry_weight_id]').select2({
                placeholder: "-- {{ trans('display.general_select') }} --",
                data: jsonDataWeight,
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                templateSelection: function (item) {
                    return item.weight;
                },
                templateResult: function (item) {
                    return item.weight;
                }
            });
        });

        $('#update-event-registration-form').validate({
            ignore: [],
            highlight:function(element) {
                $(element).parents('.form-group').addClass('has-error has-feedback');
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').removeClass('has-error');
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: new FormData(form),
                    success: function(response) {
                        var page = eventTable.page.info().page;
                        if(response.status == 'success')
                        {
                            $('#weightModal').find("#close").trigger('click');
                            toastr.success(response.msg);
                            eventTable.page(page).draw('page');
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
            },
            errorPlacement: function(error, element) {
                if($(element).parents('.form-group').find(".error-here")){
                    error.appendTo($(element).parents('.form-group').find(".error-here"));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        $('#update-event-registration-form select[name=status]').trigger('change');

        $(this).off('shown.bs.modal');
    });

    $('#weightModal').on('hidden.bs.modal', function(){
        $('#weightModal .modal-content').empty();
    });
}

function showStatusModal(data){
    $('#memberModal').modal();
    $('#memberModal').on('shown.bs.modal', function(){
        $('#memberModal .modal-content').html(data);
        $('.selectpicker').selectpicker();

        $('#change-status-form select[name=status]').on('change', function(){
            var status = $(this).val(); 
            
            if(status == '{{ @Config::get('smart.event_registration_status')['created']}}')
            {
                $(".payment").hide();
                $(".payment").find(':input').prop('disabled', true);
            }
            else 
            {
                $(".payment").show();
                $(".payment").find(':input').prop('disabled', false);

                if(status == '{{ @Config::get('smart.event_registration_status')['approved']}}')
                {
                    $('#change-status-form input[name=payment_status]').prop("checked", true);
                }
                else 
                {
                    $('#change-status-form input[name=payment_status]').prop("checked", false)
                }
            }

            // refunded бол refund section харуулна
            if (status === '{{ Config::get("smart.event_registration_status")["refunded"] }}') {
                $(".refund").show();
            } else {
                $(".refund").hide();
                $("#description").val('');
            }
        }) 

        $('#change-status-form').validate({
            ignore: [],
            highlight:function(element) {
                $(element).parents('.form-group').addClass('has-error has-feedback');
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').removeClass('has-error');
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: new FormData(form),
                    success: function(response) {
                        var page = eventTable.page.info().page;
                        if(response.status == 'success')
                        {
                            $('#memberModal').find("#close").trigger('click');
                            toastr.success(response.msg);
                            eventTable.page(page).draw('page');
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
            },
            errorPlacement: function(error, element) {
                if($(element).parents('.form-group').find(".error-here")){
                    error.appendTo($(element).parents('.form-group').find(".error-here"));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        $(this).off('shown.bs.modal');
    });

    $('#memberModal').on('hidden.bs.modal', function(){
        $('#memberModal .modal-content').empty();
    });
}

function showStatisticModal(data){
    $('#statsModal').modal();
    $('#statsModal').on('shown.bs.modal', function(){
        $('#statsModal .modal-content').html(data);


        $('#change-status-form').validate({
            ignore: [],
            highlight:function(element) {
                $(element).parents('.form-group').addClass('has-error has-feedback');
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').removeClass('has-error');
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: new FormData(form),
                    success: function(response) {
                        var page = eventTable.page.info().page;
                        if(response.status == 'success')
                        {
                            $('#statsModal').find("#close").trigger('click');
                            toastr.success(response.msg);
                            eventTable.page(page).draw('page');
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
            },
            errorPlacement: function(error, element) {
                if($(element).parents('.form-group').find(".error-here")){
                    error.appendTo($(element).parents('.form-group').find(".error-here"));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        $(this).off('shown.bs.modal');
    });

    $('#statsModal').on('hidden.bs.modal', function(){
        $('#statsModal .modal-content').empty();
    });
}
</script>
@endsection
@stop
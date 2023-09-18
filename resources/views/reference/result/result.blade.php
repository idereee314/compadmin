@extends('default')

@section('css')
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.css')}}">
@endsection

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
    .background-image: {
        url('assets/media/bg/bg-3.jpg');
    }
</style>

@section('content')
<!--begin::Main-->
<!--begin::Header Mobile-->
@include('layouts.mobile')
<!--end::Header Mobile-->
<!--begin::Aside-->

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
                    <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Тэмцээний Үр дүн</h2>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="javascript:;" class="text-muted">Бүртгэл</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('event.config.index') }}" class="text-muted">Үр дүн</a>
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
                                    <div class="col-md-10">
                                        <!--begin::Description-->
                                        <div class="flex-grow-1 font-weight-bold text-dark-50 py-2 py-lg-2 mr-5">{{ Str::words(strip_tags(@$event->description), 50, '...') }}</div>
                                        <!--end::Description-->
                                    </div>
                                    
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Info-->
                        </div>
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
                                @if($resultType == 1) 
                                <!--begin::Row-->
                                <div class="row">
                                    <div class="col-xl-12">
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b" id="toplist">
                                            <div class="card-header">
                                                <div class="card-title text-center">
                                                    <h3 class="card-label"><strong>{{trans('display.best_academy')}}</strong></h3>
                                                </div>
                                                <div class="card-toolbar">
		                                        	<a href="#" class="btn btn-icon btn-circle btn-sm btn-light-primary mr-1" data-card-tool="toggle">
		                                        	<i class="ki ki-arrow-down icon-nm"></i>
		                                        	</a>
		                                        	<a href="#" class="btn btn-icon btn-circle btn-sm btn-light-success mr-1" data-card-tool="reload">
		                                        	<i class="ki ki-reload icon-nm"></i>
		                                        	</a>
		                                        </div>
                                            </div>
                                            <div class="card-body">
                                            @if(count($eventToplist) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-head-custom">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th class="text-center">{{trans('display.comp_academy_name')}}</th>
                                                            <th class="text-center"><i class="la la-medal icon-2x gold-medal-icon"></i></th>
                                                            <th class="text-center"><i class="la la-medal icon-2x silver-medal-icon"></i></th>
                                                            <th class="text-center"><i class="la la-medal icon-2x bronze-medal-icon"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($eventToplist as $results)
                                                            <tr>
                                                                <td class="text-center border-right">{{ ++$loop->index }}</td>
                                                                <td class="min-w-200px text-center border-right"><strong>{{ $results->name }}</strong></td>
                                                                <td class="text-center border-right"><strong>{{ $results->gold }}</strong></td>
                                                                <td class="text-center border-right"><strong>{{ $results->silver }}</strong></td>
                                                                <td class="text-center border-right"><strong>{{ $results->bronze }}</strong></td>
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
                                </div>
                                <!--end::Row-->
                                @else 
                                <!--begin::Row-->
                                <div class="row">
                                    <div class="col-xl-12">
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b" id="toplist_point">
                                            <div class="card-header">
                                                <div class="card-title text-center">
                                                    <h3 class="card-label"><strong>{{trans('display.best_academy')}}</strong></h3>
                                                </div>
                                                <div class="card-toolbar">
		                                        	<a href="#" class="btn btn-icon btn-circle btn-sm btn-light-primary mr-1" data-card-tool="toggle">
		                                        	<i class="ki ki-arrow-down icon-nm"></i>
		                                        	</a>
		                                        	<a href="#" class="btn btn-icon btn-circle btn-sm btn-light-success mr-1" data-card-tool="reload">
		                                        	<i class="ki ki-reload icon-nm"></i>
		                                        	</a>
		                                        </div>
                                            </div>
                                            <div class="card-body">
                                            @if(count($eventToplistPoint) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-head-custom">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th class="text-center">{{trans('display.comp_academy_name')}}</th>
                                                            <th class="text-center"><i class="la la-medal icon-2x gold-medal-icon"></i></th>
                                                            <th class="text-center"><i class="la la-medal icon-2x silver-medal-icon"></i></th>
                                                            <th class="text-center"><i class="la la-medal icon-2x bronze-medal-icon"></i></th>
                                                            <th class="text-center"> НИЙТ ОНОО</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($eventToplistPoint as $results)
                                                            <tr>
                                                                <td class="text-center border-right">{{ ++$loop->index }}</td>
                                                                <td class="min-w-200px text-center border-right"><strong>{{ $results->name }}</strong></td>
                                                                <td class="text-center border-right"><strong>{{ $results->gold }}</strong></td>
                                                                <td class="text-center border-right"><strong>{{ $results->silver }}</strong></td>
                                                                <td class="text-center border-right"><strong>{{ $results->bronze }}</strong></td>
                                                                <td class="text-center border-right"><strong>{{ $results->total_point }}</strong></td>
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
                                </div>
                                <!--end::Row-->
                                @endif
                            </div>
                            <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-2">
                                <!--begin::Row-->
                                <div class="row">                        
                                    <div class="col-xl-12">
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h3 class="card-label"><strong>Нийт медаль</strong></h3>
                                                </div>
                                            </div>
                                            <div class="card-body">                                
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-head-custom">
                                                        <thead>
                                                            <tr>                                                                                        
                                                                <th class="text-center"><i class="la la-medal icon-2x gold-medal-icon"></i> АЛТ</th>
                                                                <th class="text-center"><i class="la la-medal icon-2x silver-medal-icon"></i> МӨНГӨ</th>
                                                                <th class="text-center"><i class="la la-medal icon-2x bronze-medal-icon"></i> ХҮРЭЛ</th>
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
                                                        </tbody> 
                                                    </table>
                                                </div>        
                                            </div>
                                        </div>
                                        <!--end::Card-->                            
                                    </div> 

                                    @foreach(collect($eventResult)->groupBy('category_name') as $categoryName => $categoryResults)
                                        @foreach(collect($categoryResults)->groupBy(function($item) 
                                        {
                                            return $item->start_age . '-' . $item->end_age;
                                        }) as $age => $ages)
                                        @php
                                            $ageArray = explode('-', $age);
                                            $startage = $ageArray[0];
                                            $end_age = $ageArray[1];
                                        @endphp
                                            @foreach(collect($ages)->groupBy('bus') as $bus => $belts)
                                                @foreach(collect($belts)->groupBy('weight') as $weight => $weights)
                                                    @foreach(collect($weights)->groupBy('gender_code') as $genderCode => $genders)
                                                        <div class="col-xl-6">
                                                            <!--begin::Card-->
                                                            <div class="card card-custom gutter-b">
                                                                <div class="card-header">
                                                                    <div class="card-title text-center">
                                                                        <h3 class="card-label"><strong>{{ $categoryName }} |  {{Config::get("enums.gender_code")[$genderCode]}} | ({{$startage}}-{{$end_age}}) | {{ $bus }} | {{ $weight }}</strong></h3>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">                                                              
                                                                    <div class="table-responsive">
                                                                        <table class="table table-hover table-bordered table-head-custom">                                                                        
                                                                            <tbody>
                                                                                @foreach($weights as $result)
                                                                                    <tr>
                                                                                        @if($result->place_number > 3)
                                                                                            <td class="text-center border-right"><strong>{{ $result->place_number }}</strong></td>
                                                                                        @else
                                                                                            <td class="text-center border-right"><strong><i class="{{ Config::get("enums.event_award")[@$result->place_number] }}"></i></strong></td>
                                                                                        @endif
                                                                                        <td class="text-center border-right">
                                                                                            <div class="d-flex align-items-center">
                                                                                                @if(@$result->profile_url xor ((@env('production') && \Storage::disk('s3')->exists($result->profile_url)) || @env('local')))
                                                                                                    <a href="javascript:;" class="show-image" data-id="{{$result->memberid}}" data-type="profile">
                                                                                                        <div class="symbol symbol-100 flex-shrink-0 rounded-circle">
                                                                                                            <img src="{{\Storage::disk('s3')->url($result->profile_url)}}" alt="Profile" style="width: 60; height:60;">
                                                                                                        </div>
                                                                                                    </a>
                                                                                                @endif
                                                                                                <div class="ml-3">                                            
			                                                                                        <span class="text-dark-75 line-height-sm d-block pb-3" style="white-space: nowrap;"><strong>{{$result->fullname}}</strong></span>
                                                                                                    <span class="text-dark-75 line-height-sm d-block pb-2">{{ $result->academy_name }}</span>
			                                                                                    </div>                                                                              
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach 
                                                                            </tbody>                                        
                                                                        </table>                                  
                                                                    </div>                                
                                                                </div>                            
                                                            </div>
                                                            <!--end::Card-->                            
                                                        </div> 
                                                    @endforeach 
                                                @endforeach 
                                            @endforeach 
                                        @endforeach 
                                    @endforeach
                                </div>                    
                                <!--end::Row-->
                            </div>
                            <div class="tab-pane fade {{ @$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-3">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-head-custom" id="categoriesTable" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="55%" class="text-center">{{ trans('display.general_categories_name') }}</th>
                                                <th width="10%" class="text-center">Оролцож буй тамирчдын тоо</th>
                                                <th width="10%" class="text-center">Дууссан эсэх</th>
                                                <th width="10%" class="text-center">Медаль гардуулсан эсэх</th>
                                                <th width="10%" class="text-center">{{ trans('display.general_manage') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(collect($eventResult)->groupBy('category_name') as $categoryName => $categoryResults)
                                                @foreach(collect($categoryResults)->groupBy(function($item) {
                                                    return $item->start_age . '-' . $item->end_age;
                                                }) as $age => $ages)
                                                    @php
                                                        $ageArray = explode('-', $age);
                                                        $startage = $ageArray[0];
                                                        $end_age = $ageArray[1];
                                                    @endphp
                                                    @foreach(collect($ages)->groupBy('bus') as $bus => $belts)
                                                        @foreach(collect($belts)->groupBy('weight') as $weight => $weights)
                                                            @foreach(collect($weights)->groupBy('gender_code') as $genderCode => $genders)
                                                                <tr>
                                                                    <td class="text-center border-right">{{ ++$loop->index }}</td>
                                                                    <td class="text-center border-right">
                                                                        <strong>
                                                                            {{ $categoryName }} | {{ Config::get("enums.gender_code")[$genderCode] }} | ({{ $startage }}-{{ $end_age }}) | {{ $bus }} | {{ $weight }}
                                                                        </strong>
                                                                    </td>
                                                                    <td class="text-center border-right"><strong></strong></td>
                                                                    <td class="text-center border-right"><strong><input type="checkbox" name="checkboxes[]"></strong></td>
                                                                    <td class="text-center border-right"><strong></strong></td>
                                                                    <td class="text-center border-right"><strong></strong></td>
                                                                </tr>
                                                            @endforeach
                                                        @endforeach
                                                    @endforeach
                                                @endforeach
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
<script src="{{asset('assets/js/plugins/custom/jstree/jstree.bundle.js')}}"></script>

<script type="text/javascript">

$(document).ready(function() {
    if ('{{ old('tab_id') }}' != '' || '{{ $tab_id }}' != '') {
        $('a[name={{ old('tab_id')? old('tab_id'): $tab_id }}]').trigger('click');
    }

    $('#categoriesTable').DataTable({
        "columnDefs": [{
            "targets": 0,   // The first column index (0-based) to apply the automatic numbering
            "orderable": false, // Prevent automatic sorting for the numbering column
            "searchable": false, // Disable searching for the numbering column
            "width": "1%", // Adjust the width of the numbering column as needed
            "render": function(data, type, row, meta) {
                return meta.row + 1; // Display row index (0-based) incremented by 1
            }
        }]
    });

    // auto reload every 10 min
    setTimeout(function() {
        location.reload();
    }, 600000); // 10 minutes = 300000 milliseconds

    // This card is lazy initialized using data-card="true" attribute. You can access to the card object as shown below and override its behavior
    var card = new KTCard('toplist');
    var card = new KTCard('toplist_point');
    // Reload event handlers
    card.on('reload', function (card) {
    	toastr.info('Дахин ачааллаа');

    	KTApp.block(card.getSelf(), {
    		overlayColor: '#ffffff',
    		type: 'loader',
    		state: 'primary',
    		opacity: 0.3,
    		size: 'lg'
    	});

    	// update the content here

    	setTimeout(function () {
    		KTApp.unblock(card.getSelf());
    	}, 2000);
    });


}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

</script>
@endsection
@stop
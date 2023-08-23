<title>Тэмцээний Удирдлагын Систем Статистик</title>
@extends('default')
@section('css')
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/jstree/dist/themes/default/style.min.css')}}">
@endsection
<style>
    .nav-text {
        height: 20px;
        line-height: 20px; /* Ensures the text is vertically centered within the 20px height */
    }

    .selected {
      border: 2px solid blue;
    }

    .selected-row {
      background-color: #f0f0f0;
    }
</style>

@section('content')
@include('layouts.mobile')
@include('layouts.aside')
<!--begin::Main-->
<!--begin::Wrapper-->
<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper" style="background-image: url('{{ asset('assets/media/bg/bg-3.jpg') }}');">
@include('layouts.header_v2')
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <!--begin::Top-->
                        <div class="d-flex">
                            <!--begin::Pic-->
                            <!-- <div class="flex-shrink-0 mr-7">
                                <div class="symbol symbol-50 symbol-lg-120">

                                </div>
                            </div> -->
                            <!--end::Pic-->
                            <!--begin: Info-->
                            <div class="flex-grow-1">
                                <!--begin::Title-->
                                <div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
                                    <!--begin::User-->
                                    <div class="mr-3">
                                        <!--begin::Name-->
                                        <a class="d-flex align-items-center text-dark text-hover-primary font-size-h1 font-weight-bold mr-3">{{$academy->name}}<i class="flaticon2-correct text-success icon-md ml-2"></i></a>
                                        <!--end::Name-->
                                        
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
                                        <!-- <div class="flex-grow-1 font-weight-bold text-dark-50 py-2 py-lg-2 mr-5">{{ Str::words(strip_tags(@$event->description), 20, '...') }}</div> -->
                                        <!--end::Description-->
                                    </div>
                                    
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Info-->
                        </div>
                        
                        <div class="separator separator-solid mb-5"></div>

                        <div class="d-flex align-items-center flex-wrap mt-8">
                            <!--begin::Item-->
                            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                                <span class="mr-4">
                                    <i class="flaticon-piggy-bank display-4 text-muted font-weight-bold"></i>
                                </span>
                                <div class="d-flex flex-column text-dark-75">
                                    <span class="font-weight-bolder font-size-sm">Earnings</span>
                                    <span class="font-weight-bolder font-size-h5"><span class="text-dark-50 font-weight-bold">$</span>249,500</span>
                                </div>
                            </div>
                            <!--end::Item-->

                            <!--begin::Item-->
                            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                                <span class="mr-4">
                                    <i class="flaticon-pie-chart display-4 text-muted font-weight-bold"></i>
                                </span>
                                <div class="d-flex flex-column text-dark-75">
                                    <span class="font-weight-bolder font-size-sm">Net</span>
                                    <span class="font-weight-bolder font-size-h5"><span class="text-dark-50 font-weight-bold">$</span>782,300</span>
                                </div>
                            </div>
                            <!--end::Item-->

                            <!--begin::Item-->
                            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                                <span class="mr-4">
                                    <i class="flaticon-file-2 display-4 text-muted font-weight-bold"></i>
                                </span>
                                <div class="d-flex flex-column flex-lg-fill">
                                    <span class="text-dark-75 font-weight-bolder font-size-sm">73 Tasks</span>
                                    <a href="#" class="text-primary font-weight-bolder">View</a>
                                </div>
                            </div>
                            <!--end::Item-->

                            <!--begin::Item-->
                            <div class="d-flex align-items-center flex-lg-fill mb-2 float-left">
                                <span class="mr-4">
                                    <i class="flaticon-network display-4 text-muted font-weight-bold"></i>
                                </span>
                                <div class="symbol-group symbol-hover">
                                    @php
                                        $count = 0;
                                    @endphp
                                    @forelse($athlete as $athletes)
                                        @if($count < 10)
                                            <div class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title="{{ $athletes->member_fname }} {{ $athletes->member_lname }}">
                                                <img alt="Pic" src="{{ \Storage::disk('s3')->url($athletes->member_profile_photo) }}" style="width: 30px; height: 30px"/>
                                            </div>
                                        @endif
                                        @php
                                            $count++;
                                        @endphp
                                    @empty
                                    @endforelse
                                    @if($count > 10)
                                        <div class="symbol symbol-30 symbol-circle symbol-light">
                                            <span class="symbol-label font-weight-bold">+</span>
                                        </div>
                                    @endif
                                </div>

                            </div>
                            <!--end::Item-->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <input type="hidden" name="tab_id" id="tab_id" value="{{ isset($tab_id) ? $tab_id : 'tab1-1' }}"/>
                        <ul class="nav nav-tabs nav-bold flex-column nav-pills nav-tabs-line-3x">
                            @forelse($tabs as $tab)
                                <li class="nav-item mr-3 {{ $tab_id == $tab['number'] ? 'active' : '' }}">
                                    <a href="#{{ $tab['number'] }}" data-toggle="tab" name="{{ $tab['number'] }}" class="nav-link app_tab" data-tabid="{{ $tab['number'] }}" data-tabcode="{{ $tab['code'] }}" data-tabname="{{ $tab['name'] }}">
                                        <span class="nav-icon">
                                            <i class="fas {{ $tab['icon'] }}"></i>
                                        </span>
                                        <span class="nav-text font-size-xl">{{ $tab['title'] }}</span>
                                    </a>
                                </li>
                            @empty
                            @endforelse
                        </ul>
                    </div>
                    <div class="col-10">
                        <div class="tab-content">
                            <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-1">
                                <div class="card-header">
                                    Home
                                </div>
                                <div class="card-body">
                                    <a href="">test1</a>
                                </div>
                            </div>
                            <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-2">
                                <div class="card-header">
                                    stats
                                </div>
                                <div class="card-body">
                                    <a href="">test2</a>
                                </div>
                            </div>
                            <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-3">
                                <div class="card-header">
                                    <h1>Тамирчид</h1>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table" id="athleteTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="text-center">{{trans('display.payment_id')}}</th>
                                                <th class="text-center">{{trans('display.payment_date')}}</th>
                                                <th class="text-center">{{trans('display.profile_title')}}</th>                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($athlete as $athletes)
                                                <tr>
                                                    <td class="text-center border-right border-left align-middle">{{ $loop->index + 1 }}</td>
                                                    <td class="text-center "><img class="mb-1" alt="profile" src="{{ \Storage::disk('s3')->url($athletes->member_profile_photo) }}" style="width: 80px; height: 80px; border-radius: 50%" /></td>
                                                    <td class="">
                                                        <strong>                                                                
                                                                <img class="mb-1 rounded" src="/assets/images/flags/4x3/{{ Config::get('enums.country_alpha')[$athletes->member_country] }}.svg" alt="flag" width="25" height="15">
                                                                {{ $athletes->member_lname }} <strong>{{ $athletes->member_fname }}</strong>
                                                        </strong>
                                                        <span class="text-dark-75 line-height-sm d-block pb-2">
                                                            <i class="la la-address-book"></i> {{ $athletes->member_birthday }},
                                                            <i class="la la-phone"></i> {{ Config::get("enums.gender_code")[@$athletes->member_gender] }}
                                                        </span>
                                                        <span class="text-dark-75 line-height-sm d-block pb-2">
                                                            <a href="/profile/{{$athletes->member_id}}" type="button" class="btn btn-primary" target="_blank">View profile</a>
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach 
                                        </tbody>
                                        </table>                                                                              
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-4">
                                <div class="card-header">
                                    {{ $tab['title'] }}
                                </div>
                                <div class="card-body">
                                    <a href="">test4</a>
                                </div>
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
@section('javascript')
<script src="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<script src="{{asset('assets/js/plugins/custom/jstree/jstree.bundle.js')}}"></script>
<script type="text/javascript">

    $(document).ready(function() {
        if ('{{ old('tab_id') }}' != '' || '{{ $tab_id }}' != '') {
            $('a[name={{ old('tab_id')? old('tab_id'): $tab_id }}]').trigger('click');
        }
        

    }).ajaxStart($.blockUI).ajaxStop($.unblockUI);

</script>
@endsection
@stop
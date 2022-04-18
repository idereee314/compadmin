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
                    <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Тэмцээний тохиргоо</h2>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="javascript:;" class="text-muted">Бүртгэл</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('event.config.index') }}" class="text-muted">Тэмцээний тохиргоо</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="javascript:;" class="text-muted">Засварлах</a>
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
                <div class="card card-custom m-4">
                    @if( $errors->count() > 0 )
                    <div class="panel-sub-heading">
                        <div class="callout callout-danger">
                            <p>
                                @if (Session::has('message'))
                                    {{ Session::get('message') }}
                                @endif

                                {{ HTML::ul($errors->all()) }}
                            </p>
                        </div>
                    </div>
                    @endif

                    <input type="hidden" name="tab_id" id="tab_id" value="{{ isset($tab_id)? $tab_id: 'tab1-1'}}"/>
                    <input type="hidden" name="event_config_id" id="event_config_id" value="{{ @$eventConfig->id }}"/>
                    <input type="hidden" name="event_id" id="event_id" value="{{ @$eventConfig->event->id }}"/>

                    <div class="card-header card-header-tabs-line">
                        <div class="pull-left">
                            @if(empty(@$tabs))
                            <div class="alert alert-info no-margin">
                                {!! trans('messages.warning_no_app_type_tab') !!}
                            </div>
                            @else
                            <ul class="nav nav-tabs nav-tabs-line" id="config_tabs">
                                @foreach($tabs as $tab)
                                    <li class="nav-item {{@$tab_id == $tab['number'] ? 'active' : '' }}">
                                        <a href="#{{$tab['number']}}" data-toggle="tab" name="{{$tab['number']}}" class="nav-link app_tab" data-tabid="{{$tab['number']}}"  data-tabcode="{{$tab['code']}}" data-tabname="{{$tab['name']}}">
                                            <i class="{{ $tab['icon'] }}"></i>
                                            <div>
                                                <span>{{ $tab['title'] }}</span>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                        <div class="pull-right">
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="card-body p-0">
                        @if(empty(@$tabs))
                        <div class="alert alert-info no-margin">
                            {!! trans('messages.warning_no_app_type_tab') !!}
                        </div>
                        @else
                        <div class="tab-pane fade show active">
                            <div class="tab-content">
                                <div class="form-sub-heading">
                                </div>
                                @foreach($tabs as $tab)
                                <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active' : '' }}" id="{{ $tab['number'] }}">

                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
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
<script  type="text/javascript">

$(document).ready(function() {
    
    $("#config_tabs li a").on('click', function() {
        var tab_id = $(this).data("tabid");
        var name = $(this).data("tabname");
        var event_config_id = $("#event_config_id").val();
        var code = $(this).data("tabcode");

        $.get('{!! route('event.config.tabs') !!}', {event_config_id: event_config_id, tab_id: tab_id, name: name, code: code})
        .done(function( data ) {
            $(".tab-content").find("#"+tab_id).empty().html(data);
        }).fail(function(xhr) {
            if(xhr.status === 500)
            {
                $(".tab-content").find("#"+tab_id).html(xhr.responseText);
            }
        });
    });

    if('{{ old('tab_id') }}' != '' || '{{ $tab_id }}' != '') {
        $('a[name={{ old('tab_id')? old('tab_id'): $tab_id }}]').trigger('click');
    };
    // $('a[name=$('input[name=tab_id]').val()? $('input[name=tab_id]').val(): $tab_id]').trigger('click');
    
}).ajaxStart($.blockUI).ajaxStop($.unblockUI);
</script>
@endsection
@stop
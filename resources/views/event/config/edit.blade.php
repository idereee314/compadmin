@extends('default')

@section('css')
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.css')}}">
@endsection

@section('content')

<section id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed page-loading">
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
                                <input type="hidden" name="event_config_id" id="event_config_id" value="{{ @$event_config_id}}"/>
                                <input type="hidden" name="event_id" id="event_id" value="{{ @$eventConfig->event->id}}"/>
        
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
                                        <div class="tab-pane fade show active mt-8">
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
                <!--begin::Footer-->
                @include('layouts.footer')
                <!--end::Footer-->
            </div>
            @include ($view_path.'.modals')
            <!--end::Wrapper-->
        <!--end::Main-->
</section>

@section('javascript')
<script src="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<script  type="text/javascript">

$(document).ready(function() {
    
    $("#config_tabs li a").on('click', function() {
        var tab_id = $(this).data("tabid");
        var name = $(this).data("tabname");
        var event_config_id = $("#event_config_id").val();
        var event_id = $("#event_id").val();
        var code = $(this).data("tabcode");

        $.get('{!! route('event.config.tabs') !!}', {event_config_id: event_config_id, event_id: event_id, tab_id: tab_id, name: name, code: code})
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
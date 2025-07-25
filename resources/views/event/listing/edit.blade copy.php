@extends('layout')
@section('css')
    {!! HTML::style('/assets/js/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css', array('media'=>'screen')) !!}
    {!! HTML::style('/assets/js/plugins/bootstrap-datepicker-vitalets/css/datepicker.css', array('media'=>'screen')) !!}
    {!! HTML::style('/assets/js/plugins/bootstrap-daterangepicker/daterangepicker.css', array('media'=>'screen')) !!}
    {!! HTML::style('/assets/js/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css', array('media'=>'screen')) !!}
    {!! HTML::style('/assets/js/plugins/jasny-bootstrap-fileinput/css/jasny-bootstrap-fileinput.min.css', array('media'=>'screen')) !!}
    {!! HTML::style('/assets/js/plugins/croppie-master/croppie.css', array('media'=>'screen')) !!}
    {!! HTML::style('/assets/js/plugins/ol/css/ol.css', array('media'=>'screen')) !!}
    {!! HTML::style('/assets/js/plugins/ol/css/ol.smart.css', array('media'=>'screen')) !!}
@stop
@section('content')

<!--[if lt IE 9]>
<p class="upgrade-browser">Upps!! You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/" target="_blank">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<!-- START @WRAPPER -->
<section id="wrapper">

@include ('layouts._header')

@include ('layouts._sidebar_left')

<!-- START @PAGE CONTENT -->
    <section id="page-content">
        <div class="header-content">
            <h2><i class="fa fa-home"></i>{{trans('menu.event')}}  <span>{{trans('display.general_edit')}} </span></h2>
            <div class="breadcrumb-wrapper hidden-xs">
                <span class="label">{{trans('display.general_you_are_here')}}:</span>
                <ol class="breadcrumb">
                    <li class="active">{{trans('menu.event')}}</li>
                </ol>
            </div>
        </div><!-- /.header-content -->
        <!-- Start page header -->

        <div class="body-content animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <!-- Start input fields - basic form -->
                    <div class="panel rounded shadow">
                        {{--<div class="panel-heading">--}}
                            {{--<div class="clearfix">--}}
                                 {{--</div>--}}
                        {{--</div><!-- /.panel-heading -->--}}
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
                    @endif<!-- /.panel-subheading -->
                    <div class="panel panel-tab panel-tab-double rounded shadow">
                            <input type="hidden" name="tab_id" id="tab_id" value="{{ isset($tab_id)? $tab_id: 'tab2-1'}}"/>
                            <input type="hidden" name="event_id" id="event_id" value="{{ @$event_id}}"/>
                            <!-- Start tabs heading -->
                            <div class="panel-heading no-padding">
                                <div class="pull-left">
                                    @if(empty(@$tabs))
                                        <div class="alert alert-info no-margin">
                                            {!! trans('messages.warning_no_app_type_tab') !!}
                                        </div>
                                    @else
                                        <ul class="nav nav-tabs" id="event_tabs">
                                            @foreach($tabs as $tab)
                                                <li class="{{@$tab_id == $tab['number'] ? 'active' : '' }}">
                                                    <a href="#{{$tab['number']}}" data-toggle="tab" name="{{$tab['number']}}" class="app_tab" data-tabid="{{$tab['number']}}"  data-tabcode="{{$tab['code']}}" data-tabname="{{$tab['name']}}">
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
                            </div><!-- /.panel-heading -->
                            <div class="panel-body no-padding">
                                @if(empty(@$tabs))
                                    <div class="alert alert-info no-margin">
                                        {!! trans('messages.warning_no_app_type_tab') !!}
                                    </div>
                                @else                                    
                                    <div class="tab-pane fade in active inner-all">
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
                        </div><!-- /.panel -->
                    </div><!-- /.panel -->
                    <!--/ End input fields - basic form-->
                </div><!-- /.col-md-6 -->
            </div><!-- /.row -->
        </div><!-- /.body-content -->
        @include ($view_path.'.modals')

        <!-- Start footer content -->
    <!--/ End footer content -->
    </section><!-- /#page-content -->
    <!--/ END PAGE CONTENT -->

    <!-- START @SIDEBAR RIGHT -->
<!--/ END SIDEBAR RIGHT -->

</section><!-- /#wrapper -->
<!--/ END WRAPPER -->


<!-- START JAVASCRIPT SECTION (Load javascripts at bottom to reduce load time) -->
@stop
@section('javascript')

<!-- START @PAGE LEVEL PLUGINS -->
<!--/ END PAGE LEVEL PLUGINS -->

<!-- START @PAGE LEVEL SCRIPTS -->
<script type="text/javascript" src="{{asset('assets/js/smart.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/chosen_v1.2.0/chosen.jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/bootstrap-datepicker-vitalets/js/bootstrap-datepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/moment-develop/min/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/jasny-bootstrap-fileinput/js/jasny-bootstrap.fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/croppie-master/croppie.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/ol/build/ol.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/ol-ext-master/dist/ol-ext.js')}}"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#event_tabs li a").on('click', function(){
        var tab_id = $(this).data("tabid");
        var name = $(this).data("tabname");
        var id = $("#event_id").val();
        var code = $(this).data("tabcode");
        
        if($(".tab-content").find("#"+tab_id).children().length == 0)
        {
            $.get('{!! route('event.tabs') !!}', {id: id, tab_id: tab_id, name: name, code: code})
                .done(function( data ) {
                    $(".tab-content").find("#"+tab_id).empty().html(data);
                }).fail(function(xhr) {
                if(xhr.status === 500)
                {
                    $(".tab-content").find("#"+tab_id).html(xhr.responseText);
                }
            });
        }
    });
    if('{{ Input::old('tab_id') }}' != '' || '{{ $tab_id }}' != '') {
        $('a[name={{ Input::old('tab_id')? Input::old('tab_id'): $tab_id }}]').trigger('click');
    };
}).ajaxStart($.blockUI).ajaxStop($.unblockUI);
</script>
@stop
<style>
.bootstrap-timepicker-widget.dropdown-menu { z-index: 1050 !important; }
</style>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="pull-left">
            <h3 class="panel-title">{{ trans('display.general_new') }}</h3>
        </div>
        <div class="pull-right">
            <button class="btn btn-sm" data-action="collapse" data-toggle="tooltip" data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body no-padding">
        <!-- Start basic wizard vertical -->
        <div id="validation-wizard">
            <div class="panel panel-tab panel-tab-double panel-tab-vertical row no-margin rounded shadow">
                <!-- Start tabs heading -->
                <div class="panel-heading no-padding col-md-3">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab2-1" data-toggle="tab">
                                <i class="fa fa-user"></i>
                                <div>
                                    <span class="text-strong">{{ trans('display.general_step') }} 1</span>
                                    <span>Event details</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#tab2-2" data-toggle="tab">
                                <i class="fa fa-file-text"></i>
                                <div>
                                    <span class="text-strong">{{ trans('display.general_step') }} 2</span>
                                    <span>Pictures details</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#tab2-3" data-toggle="tab">
                                <i class="fa fa-map-marker"></i>
                                <div>
                                    <span class="text-strong">{{ trans('display.general_step') }} 3</span>
                                    <span>Location details</span>
                                </div>
                            </a>
                        </li>
                </div><!-- /.panel-heading -->
                <!--/ End tabs heading -->

                <!-- Start tabs content -->
                <div class="panel-body col-md-9">
                    <form method="POST" id="event-create-form" class="tab-content form-horizontal smart-form" action="{!! route('event.store') !!}">
                        <div class="tab-pane fade in active inner-all" id="tab2-1">
                            <h4 class="page-header">Бүртгэл</h4>
                            <!--
                            <div class="form-group form-group-divider">
                                <div class="form-inner">
                                    <h4 class="no-margin"><span class="label label-success label-circle">1</span> Ерөнхий мэдээлэл</h4>
                                </div>
                            </div>-->
                            <br/>
                            <div class="form-group">
                                <label class="col-sm-3 text-right">{{trans('display.general_category')}} <span class="asterisk">*</span></label>
                                <div class="col-md-9 col-sm-12">
                                    <select class="chosen-select" multiple name="category[]" data-placeholder="-- {{ trans('display.general_select') }} --" data-rule-required="true" data-msg-required="{{ trans('validation.required') }}">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="error-here"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 text-right">{{trans('display.general_title')}} <span class="asterisk">*</span></label>
                                <div class="col-md-9 col-sm-12">
                                    <input class="form-control" type="text" name="name" id="name" data-rule-required="true" data-msg-required="{{ trans('validation.required') }}">
                                    <div class="error-here"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 text-right">{{trans('display.general_description')}}</label>
                                <div class="col-md-9 col-sm-12">
                                    <textarea class="form-control" rows="4" cols="50" name="description" id="description"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 text-right">{{trans('display.general_duration')}} <span class="asterisk">*</span></label>
                                <div class="col-md-9 col-sm-12">
                                    <input type="text" class="form-control date-range-picker-time" name="dates" data-rule-required="true" data-msg-required="{{ trans('validation.required') }}">
                                </div>
                            </div>
                            <div class="row" id="div-event-date">
                                
                            </div>
                            <h4 class="page-header">
                                <div class="pull-left">Байгууллага</div>
                                <div class="pull-right"><button type="button" class="btn btn-success btn-xs" id="btn-row-add"><i class="fa fa-plus"></i></button></div>
                                <div class="clearfix"></div>
                            </h4>

                            <div class="form-group" id="div-form-group" style="display: none">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <select class="form-control inline" name="roles[]" id="role">
                                            @forelse(@$roles as $key => $role)
                                            <option value="{{ $key }}">{{ $role }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </span>
                                    <input class="form-control" type="text" name="organizations[]" id="organization"/>
                                    <!--<span class="input-group-addon bg-warning">.00</span>-->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade inner-all" id="tab2-2">
                            <h4 class="page-header">Зураг</h4>
                            <!--
                            <div class="form-group form-group-divider">
                                <div class="form-inner">
                                    <h4 class="no-margin"><span class="label label-success label-circle">2</span> Зураг</h4>
                                </div>
                            </div>
                            <br/>
                            -->
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 col-sm-6 text-right">{{trans('display.select_picture_type')}} <span class="asterisk">*</span></label>
                                    <div class="col-md-8 col-sm-6">
                                        <input type="hidden" name="picture_type" id="picture_type" value="{{ $pictureType->id }}" data-width="{{$pictureType->width}}" data-height="{{$pictureType->height}}"/>
                                        <label>
                                            {{ $pictureType->description }} {{ $pictureType->width }}X{{ $pictureType->height }}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-sm-6 text-right">{{ trans('display.general_image') }} <span class="asterisk">*</span></label>
                                    <div class="col-md-8 col-sm-6">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn btn-success btn-file">
                                                <span class="fileinput-new">{{ trans('display.general_file_select') }}</span>
                                                <span class="fileinput-exists">{{ trans('display.general_file_change') }}</span>
                                                <input type="hidden" value="" name="...">
                                                <input type="file" name="cover_image" id="btn-upload" accept="image/*" value=""  data-rule-required="true" data-msg-required="{{ trans('validation.required') }}" data-rule-filesize="10485760" data-msg-filesize="{{ trans('messages.validation_file_size') }}">
                                            </span>
                                            <span class="fileinput-filename"></span>
                                            <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
                                        </div>
                                        <div class="error-here"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-sm-6 text-right">{{ trans('display.general_image_rotate') }} <span class="asterisk">*</span></label>
                                    <div class="col-md-8 col-sm-6">
                                        <button class="btn btn-info rotate" data-deg="-90" type="button">Rotate Left</button>
                                        <button class="btn btn-info rotate" data-deg="90" type="button">Rotate Right</button>
                                    </div>
                                <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div id="img_canvas"></div>
                            </div>
                        </div>
                        <div class="tab-pane fade inner-all" id="tab2-3">
                            <h4 class="page-header">Байршил</h4>
                            <div class="form-group">
                                <label class="col-md-3 col-sm-6 text-right">{{trans('display.organization_branches')}}</label>
                                <div class="col-md-9 col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon" >
                                            <div class="ckbox ckbox-success">
                                                <input id="checkbox-success-all-org" type="checkbox" name="is_all_branch" value="1">
                                                <label for="checkbox-success-all-org"></label>
                                            </div>
                                        </span>
                                        <input type="hidden" name="organization_branch" id="organization_branch"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-sm-6 text-right">{{trans('display.general_object')}}</label>
                                <div class="col-md-9 col-sm-6">
                                    <input type="text" name="object_locations" id="object_locations" class="form-control" data-role="tagsinput" readonly/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-sm-6 text-right">{{trans('display.general_point')}}</label>
                                <div class="col-md-9 col-sm-6">
                                    <input type="text" name="location_datas" id="location_datas" class="form-control" data-role="tagsinput" readonly/>
                                </div>
                            </div>
                            <h4 class="page-header">Газрын зураг</h4>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <select class="form-control inline" name="aimag_city" id="aimag_city">
                                            <option value="">-- {{ trans('display.aimag_city') }} --</option>
                                            @forelse(@$aimagCity as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </span>
                                    <span class="input-group-btn">
                                        <input class="form-control" type="text" name="soum_district" id="soum_district"/>
                                    </span>
                                    <input class="form-control" type="text" name="bag_khoroo" id="bag_khoroo"/>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-info" id="btn-zoom-unit">Харах</button>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div id="mapid" class="map-sidebar-map" name="" style="height:600px !important;">
                                
                                </div>
                                <div id="mappopup" class="ol-popup" style="max-height: 250px;overflow-y: scroll;">
                                    <a href="#" id="mappopup-closer" class="ol-popup-closer"></a>
                                    <div id="mappopup-content"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Start pager -->
                    <div class="panel-footer no-bg">
                        <ul class="pager wizard no-margin">
                            <li class="previous"><a href="javascript:void(0);">{{ trans('display.general_previous') }}</a></li>
                            <li class="next"><a href="javascript:void(0);">{{ trans('display.general_next') }}</a></li>
                        </ul>
                    </div>
                    <!--/ End pager -->
                </div><!-- /.panel-body -->
                <!--/ End tabs content -->
            </div><!-- /.panel -->
        </div><!-- /#basic-wizard-vertical -->
        <!--/ End basic wizard vertical-->
    </div>
    <div class="modal-footer">
        <button type="button" id="close" class="btn btn-default" data-dismiss="modal">{{ trans('display.general_close') }}</button>
        <button type="submit" class="btn btn-success" id="btn-submit">{{ trans('display.general_save') }}</button>
    </div>
</div>
<!-- LAYER -->
<script type="text/javascript" src="{{asset('js/script/base/corelayers.js')}}"></script>
<script type="text/javascript" src="{{asset('js/script/event/map_script_event.js')}}"></script>
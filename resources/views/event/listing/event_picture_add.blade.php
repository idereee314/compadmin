<form method="POST" id="picture-add-form" class="form-horizontal smart-form" action="{!! route('event.picture.store') !!}" enctype="multipart/form-data">
    <input type="hidden" name="event_id" id="event_id" value="{{ $eventId }}">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-left">
                <h3 class="panel-title">{{ trans('display.general_create') }}</h3>
            </div>
            <div class="pull-right">
                <button class="btn btn-sm" data-action="collapse" data-toggle="tooltip" data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body no-padding">
            <div class="col-md-4 col-sm-12">
                <div class="form-body">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6">
                            <label class="pull-right">{{trans('display.select_picture_type')}} <span class="asterisk">*</span></label>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <select class="form-control" name="picture_type" id="picture_type">
                                @foreach($pictureType as $type)
                                    <option value="{{ $type->id }}" data-width="{{$type->width}}" data-height="{{$type->height}}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}" {{ in_array($type->id, $insertedPictures) ? 'disabled' : '' }}>{{ $type->description }} {{ $type->width }}X{{ $type->height }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6">
                            <label class="pull-right">{{ trans('display.select_image') }} <span class="asterisk">*</span></label>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <span class="btn btn-success btn-file">
                                    <span class="fileinput-new">{{ trans('display.general_file_select') }}</span>
                                    <span class="fileinput-exists">{{ trans('display.general_file_change') }}</span>
                                    <input type="hidden" value="" name="file"><input type="file" name="file" id="btn-upload" accept="image/*" value="" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                                </span>
                                <span class="fileinput-filename"></span>
                                <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
                            </div>
                            <div class="error-here"></div>
                        </div>
                    </div>
                </div>
                <div class="form-footer">
                    <div class="pull-right">
                        <button class="btn btn-info rotate" data-deg="-90" type="button">Rotate Left</button>
                        <button class="btn btn-info rotate" data-deg="90" type="button">Rotate Right</button>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div id="test">
            </div>
            <div class="col-md-8 col-sm-12">
                <div id="canvas"></div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" id="close" class="btn btn-default" data-dismiss="modal">{{ trans('display.general_close') }}</button>
        <button type="submit" class="btn btn-success">{{ trans('display.general_save') }}</button>
    </div>
</form>
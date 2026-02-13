<form method="POST" id="picture-type-create-form" class="form-horizontal smart-form" action="{!! route('reference.picture.type.store') !!}">
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
        <div class="form-body">
            <div class="form-group">
                <div class="col-md-4 col-sm-6">
                    <label class="pull-right">{{trans('display.general_code')}} <span class="asterisk">*</span></label> 
                </div>
                <div class="col-md-6 col-sm-10">
                    <input class="form-control" type="text" name="code" id="code" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <div class="error-here"></div>
                    <span class="text-muted help-block">{{trans('display.only_insert_letters')}}</span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4 col-sm-6">
                    <label class="pull-right">{{trans('display.general_description')}}</label> 
                </div>
                <div class="col-md-6 col-sm-10">
                    <textarea class="form-control" rows="4" cols="50" name="description" id="description"></textarea>
                    <div class="error-here"></div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4 col-sm-6">
                    <label class="pull-right">{{trans('display.height')}} <span class="asterisk">*</span></label>
                </div>
                <div class="col-md-6 col-sm-10">
                    <input class="form-control" type="text" name="height" id="height" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <div class="error-here"></div>
                    <span class="text-muted help-block">{{trans('display.only_insert_number')}}</span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4 col-sm-6">
                    <label class="pull-right">{{trans('display.width')}} <span class="asterisk">*</span></label>
                </div>
                <div class="col-md-6 col-sm-10">
                    <input class="form-control" type="text" name="width" id="width" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <div class="error-here"></div>
                    <span class="text-muted help-block">{{trans('display.only_insert_number')}}</span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4 col-sm-6">
                    <label class="pull-right">{{trans('display.object_type')}} <span class="asterisk">*</span></label>
                </div>
                <div class="col-md-6 col-sm-6">
                    <select class="form-control" name="object_type" id="object_type" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                        <option selected="" value="">{{ trans('display.object_type') }}</option>
                        @foreach(Config::get('smart.object_types') as $key => $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    <div class="error-here"></div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4 col-sm-6">
                    <label class="pull-right">{{trans('display.dir_url')}} <span class="asterisk">*</span></label>
                </div>
                <div class="col-md-6 col-sm-10">
                    <input class="form-control" type="text" name="dir_url" id="dir_url" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <div class="error-here"></div>
                    <span class="text-muted help-block">{{trans('display.only_insert_letters_and_slash')}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" id="close" class="btn btn-default" data-dismiss="modal">{{ trans('display.general_close') }}</button>
        <button type="submit" class="btn btn-success">{{ trans('display.general_save') }}</button>
    </div>
</form>
<script>
    setInputFilter(document.getElementById("height"), function(value) {
    return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    });

    setInputFilter(document.getElementById("width"), function(value) {
    return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    });
</script>
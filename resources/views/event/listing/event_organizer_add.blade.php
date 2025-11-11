<form method="POST" id="organizer-add-form" class="form-horizontal smart-form" action="{!! route('event.organizer.store') !!}">
    <input type="hidden" name="event_id" id="event_id" value="{{ $event->id }}"/>
    <div class="panel panel-default">
    <div class="panel-heading">
        <div class="pull-left">
            <h3 class="panel-title">{{ trans('display.general_add') }}</h3>
        </div>
        <div class="pull-right">
            <button class="btn btn-sm" data-action="collapse" data-toggle="tooltip" data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body no-padding">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-md-4 col-sm-6 text-right">{{ trans('display.organizer_role') }} <span class="asterisk">*</span></label>
                <div class="col-md-7 col-sm-6">
                    <select class="form-control" name="role" id="role" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                        @foreach($roles as $key => $role)
                            <option value="{{ $key }}">{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-md-4 col-sm-6 text-right">{{ trans('display.organization') }} <span class="asterisk">*</span></label>
                <div class="col-md-7 col-sm-6">
                    <input type="text" name="organizations" id="organizations"/>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="modal-footer">
        <button type="button" id="close" class="btn btn-default" data-dismiss="modal">{{ trans('display.general_close') }}</button>
        <button type="submit" class="btn btn-success">{{ trans('display.general_save') }}</button>
    </div>
</form>
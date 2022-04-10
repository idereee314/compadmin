<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/jasny-bootstrap-fileinput/css/jasny-bootstrap-fileinput.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2-bootstrap.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2-custom.css')}}">

<form class="form" method="POST" id="create-event-config-copy-form" action="{{ route('event.config.copy.store', $eventConfigId) }}">
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_new')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.event_name')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control" id="event_id" name="event_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                </select>
                <div class="error-here"></div>
            </div>
        </div> 
    
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.reg_start_date')}}<span class="text-danger"> *</span></label>
           <div class="col-md-9 col-lg-6">
                <div class="input-group date" id="reg_start_date" data-target-input="nearest">
                    <input type="text" name="reg_start_date" class="form-control datetimepicker-input"  placeholder="{{trans('display.reg_start_date')}}" data-target="#reg_start_date" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                    <div class="input-group-append" data-target="#reg_start_date" data-toggle="datetimepicker">
                        <span class="input-group-text">
                            <i class="ki ki-calendar"></i>
                        </span>
                    </div>
                </div>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.reg_end_date')}}<span class="text-danger"> *</span></label>
           <div class="col-md-9 col-lg-6">
                <div class="input-group date" id="reg_end_date" data-target-input="nearest">
                    <input type="text" name="reg_end_date" class="form-control datetimepicker-input" placeholder="{{trans('display.reg_end_date')}}" data-target="#reg_end_date" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                    <div class="input-group-append" data-target="#reg_end_date" data-toggle="datetimepicker">
                        <span class="input-group-text">
                            <i class="ki ki-calendar"></i>
                        </span>
                    </div>
                </div>
                <div class="error-here"></div>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
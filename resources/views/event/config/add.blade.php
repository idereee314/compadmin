<form class="form" method="POST" id="create-event-config-form" action="{{ route('event.config.store') }}">
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_new')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.event_title')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control" id="event_id" name="event_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                </select>
                <div class="error-here"></div>
            </div>
        </div> 
    
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.reg_date')}}<span class="text-danger"> *</span></label>
            <div class="col-md-9 col-lg-6">
                <div class="input-group" id="kt_reg_date">
                    <input type="text" name="reg_date" id="reg_date" class="form-control" readonly="readonly" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="la la-calendar-check-o"></i>
                        </span>
                    </div>
                </div>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.reg_payment_date')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <div class="input-group date">
                    <input type="text" name="reg_payment_date" id="reg_payment_date" data-toggle="datetimepicker" data-target="#reg_payment_date" class="form-control datetimepicker-input" readonly="readonly" data-rule-required="true" data-msg-required="{{ trans('validation.required', ['Attribute' => '']) }}"/>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="la la-calendar-check-o"></i>
                        </span>
                    </div>
                </div>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.reg_update_date')}}<span class="text-danger"> *</span></label>
            <div class="col-md-9 col-lg-6">
                <div class="input-group" id="kt_reg_update_date">
                    <input type="text" name="reg_update_date" id="reg_update_date" data-toggle="datetimepicker" data-target="#reg_update_date" class="form-control datetimepicker-input" readonly="readonly" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="la la-calendar-check-o"></i>
                        </span>
                    </div>
                </div>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_org_type')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <select class="form-control select2" id="org_types" name="org_types[]" multiple="multiple" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@Config::get('enums.org_type') as $key => $type)
                    <option value="{{ $key }}">{{ $type }}</option>
                    @empty
                    @endforelse
                </select>
                <div class="error-here"></div>
            </div>
        </div> 
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form> 
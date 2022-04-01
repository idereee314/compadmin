<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/jasny-bootstrap-fileinput/css/jasny-bootstrap-fileinput.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2-bootstrap.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2-custom.css')}}">

<form class="form" method="POST" id="create-event-registration-form" action="{{ route('event.registration.store') }}">
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_new')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_member')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control" id="member_id" name="member_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                </select>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_title')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control" id="event_id" name="event_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@$competitions as $competition)
                    <option value="{{ $competition->event_id }}">{{ $competition->event->name }}: /{{ $competition->reg_start_date.'-'.$competition->reg_end_date }}/</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input class="form-control" type="text" id="entry_id" name="entry_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_belt')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input class="form-control" id="entry_belt_id" name="entry_belt_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_age')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input class="form-control" id="entry_age_id" name="entry_age_id"  onchange="ageFunction(event)" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_weight')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input class="form-control" id="entry_weight_id" name="entry_weight_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_academy')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" data-live-search="true" id="academy_id" name="academy_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@$academies as $academy)
                    <option value="{{ $academy->id }}">{{ $academy->name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row d-none" id="academy_name_other">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_academy_name')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input class="form-control" id="academy_name" name="academy_name"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_status')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" id="status" name="status" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@Config::get("enums.event_registeation_status") as $key => $status)
                    <option value="{{ $key }}">{{ $status }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
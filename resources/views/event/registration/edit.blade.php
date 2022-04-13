<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/jasny-bootstrap-fileinput/css/jasny-bootstrap-fileinput.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2-bootstrap.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2-custom.css')}}">

<form class="form" method="POST" id="update-event-registration-form" action="{{ route('event.registration.update', $eventRegistration->id)}}">
    <input type="hidden" name="_method" value="put" />
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_edit')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_member')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input class="form-control form-control-lg" disabled value="{{ $eventRegistration->member->fullname }}"/>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" id="entry_id" name="entry_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    @forelse(@$eventEntries as $entry)
                    <option value="{{ $entry['id'] }}" {{ $eventRegistration->entry_id == @$entry->id ? 'selected' : ''}}>{{ $entry->name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_belt')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control form-control-input" id="entry_belt_id" name="entry_belt_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    @forelse(@$configBelts as $belt)
                    <option value="{{ $belt->id }}" {{ $eventRegistration->entry_belt_id == $belt->id ? 'selected' : ''}}>{{ $belt->name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_age')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control form-control-input" id="entry_age_id" name="entry_age_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    @forelse(@$configAges as $age)
                    <option value="{{ $age->id }}" {{ $eventRegistration->entry_age_id == $age->id ? 'selected' : ''}}>{{ @$age->start_age }} - {{ @$age->end_age }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_weight')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control form-control-lg" id="entry_weight_id" name="entry_weight_id" value="{{$eventRegistration->entry_weight_id}}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    @forelse(@$configWeights as $weight)
                    <option value="{{ $weight->id }}" {{ $eventRegistration->entry_weight_id == $weight->id ? 'selected' : ''}}>{{ @$weight->weight }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_academy')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" data-live-search="true" id="academy_id" name="academy_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@$academies as $academy)
                    <option value="{{ $academy->id }}" {{$eventRegistration->academy_id == $academy->id ? 'selected' : ''}}>{{ $academy->name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row {{$eventRegistration->academy->is_other == 0 ? 'd-none' : ''}}" id="academy_name_other">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_academy_name')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input class="form-control" id="academy_name" name="academy_name" {{ $eventRegistration->academy->is_other == 0 ? 'disabled' : '' }} value="{{$eventRegistration->academy_name}}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_status')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" id="status" name="status" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@Config::get("enums.event_registeation_status") as $key => $status)
                    <option value="{{ $key }}" {{$eventRegistration->status == $key ? 'selected' : ''}}>{{ $status }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
        @if($eventRegistration->status == @Config::get('smart.event_registeation_status')['approved'])
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"></span></label>
            <div class="col-md-9">
                <div class="checkbox-inline">
                    <label class="checkbox checkbox-success"> 
                    <input type="checkbox" name="is_weight_checked" id="is_weight_checked" {{ @$eventRegistration->is_weight_checked ? 'checked' : ''}}> 
                    <span></span>Жин шалгасан эсэх</label>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
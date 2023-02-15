<form class="form" method="POST" id="update-event-entry-weight-form" action="{{ route('event.entry.weight.update', $eventEntryWeight->id) }}">
    <input type="hidden" name="_method" value="put" />
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_edit')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_type')}}: <span class="text-danger">*</span></label>
            <div class="col-md-6">
                <select class="form-control selectpicker" type="text" id="entry_id" name="entry_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@$eventEntries as $entry)
                    <option value="{{ $entry['id'] }}" {{ $eventEntryWeight->entry_id == @$entry->id ? 'selected' : ''}}>{{ $entry->name }}</option>
                    @empty
                    @endforelse
                </select>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_age')}}: <span class="text-danger">*</span></label>
            <div class="col-md-6">
                <select class="form-control" id="entry_age_id" name="entry_age_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@$configAges as $age)
                    <option value="{{ $age->id }}" {{ $eventEntryWeight->entry_age_id == $age->id ? 'selected' : ''}}>{{ @$age->start_age }} - {{ @$age->end_age }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.weight')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="text" class="form-control" value="{{$eventEntryWeight->weight}}" autocomplete="off" name="weight" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_max_entry')}}: </label>
            <div class="col-md-9 col-lg-6">
                <input class="form-control" type="number" id="max_entry" name="max_entry" value="{{$eventEntryWeight->max_entry}}">
            </div>
        </div> 
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
<form class="form" method="POST" id="create-event-entry-weight-form" action="{{ route('event.entry.weight.store') }}">
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_new')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_type')}}: <span class="text-danger">*</span></label>
            <div class="col-md-6">
                <select class="form-control selectpicker" data-live-search="true" data-size="7" name="entry_id" id="entry_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @foreach(@$eventEntries as $entry)
                    <option value="{{ $entry->id }}">{{ $entry->name }}</option>
                    @endforeach
                </select>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_age')}}: <span class="text-danger">*</span></label>
            <div class="col-md-6">
                <input class="form-control" id="entry_age_id" name="entry_age_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">Жин: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="text" class="form-control" autocomplete="off" name="weight" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_max_entry')}}: </label>
            <div class="col-md-9 col-lg-6">
                <input class="form-control" type="number" id="max_entry" name="max_entry">
            </div>
        </div> 
    </div>



    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
<form class="form" method="POST" id="update-event-entry-fee-form" action="{{ route('event.entry.fee.update', $eventEntryFee->id) }}">
    <input type="hidden" name="_method" value="put" />
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
                <select class="form-control selectpicker" data-live-search="true" name="entry_id" id="entry_id">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @foreach(@$eventEntries as $entry)
                    <option value="{{ $entry->id }}" {{$eventEntryFee->entry_id == $entry->id ? 'selected' : ''}}>{{ $entry->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_end_date')}}:<span class="text-danger"> *</span></label>
            <div class="col-md-9 col-lg-6">
                <div class="input-group date">
                    <input type="text" name="end_date" id="end_date" class="form-control" value="{{$eventEntryFee->end_date}}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
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
            <label class="col-md-3 col-form-label text-right">Төлбөр: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="number" class="form-control" value="{{$eventEntryFee->entrance_fee}}" min="5000" step="5000" autocomplete="off" name="entrance_fee"  data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
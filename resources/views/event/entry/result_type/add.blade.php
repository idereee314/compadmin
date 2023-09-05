<form class="form" method="POST" id="create-event-entry-age-form" action="{{ route('event.entry.age.store') }}">
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
                    @forelse(@$eventEntries as $entry)
                    <option value="{{ $entry->id }}">{{ $entry->name }}</option>
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
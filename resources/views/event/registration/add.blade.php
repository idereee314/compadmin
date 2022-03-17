

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
                <input class="form-control form-control-lg" id="member_id" name="member_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_title')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control datatable-input" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@$competitions as $competition)
                    <option value="{{ $competition->event_id }}">{{ $competition->event->name }}: /{{ $competition->reg_start_date.'-'.$competition->reg_end_date }}/</option>
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
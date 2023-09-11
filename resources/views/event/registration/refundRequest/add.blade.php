<form class="form" method="POST" id="create-event-refund-request-form" action="{{ route('event.refund.request.store') }}">
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_new')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <input type="hidden" name="event_id" id="event_id" value="{{ @$eventRefundRequest[0]->event_id}}"/>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_member')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <select class="form-control" id="member_id" name="member_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                </select>
                <div class="error-here"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_academy')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <select class="form-control selectpicker" data-live-search="true" id="academy_id" name="academy_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@$academies as $academy)
                    <option value="{{ $academy->id }}">{{ $academy->name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_amount')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="number" class="form-control" autocomplete="off" name="amount" min="1" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>
        
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
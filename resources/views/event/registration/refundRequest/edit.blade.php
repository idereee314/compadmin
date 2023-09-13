<form class="form" method="POST" id="update-event-refund-request-form" action="{{ route('event.refund.request.update', $eventRefundRequest->id) }}">
    <input type="hidden" name="_method" value="put" />
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_edit')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <input hidden name="event_id" id="event_id" value="{{ @$eventRefundRequest->event->id}}"/>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_member')}}: </label>
            <div class="col-md-9 col-lg-6">
                <input class="form-control form-control-lg" disabled value="{{ $eventRefundRequest->member->fullname }}"/>
                <input class="form-control form-control-lg" id="member_id" name="member_id" hidden value="{{ $eventRefundRequest->member->id }}"/>
            </div>
        </div>
    
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_academy')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <select class="form-control selectpicker" data-live-search="true" id="academy_id" name="academy_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@$academies as $academy)
                    <option value="{{ $academy->id }}" {{$eventRefundRequest->academy_id == $academy->id ? 'selected' : ''}}>{{ $academy->name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_amount')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="number" class="form-control" autocomplete="off" name="amount" min="1" value="{{ $eventRefundRequest->amount }}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_status')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <select class="form-control selectpicker" id="status" name="status" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@Config::get('enums.event_refund_request_status') as $key => $status)
                    <option value="{{ $key }}" {{$eventRefundRequest->status == $key ? 'selected' : ''}}>{{ $status }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">Тайлбар</label>
            <div class="col-md-9 col-lg-6">         
                <textarea name="description" id="description" class="form-control" cols="10"  placeholder="Тайлбар" value="{{ @$eventRefundRequest->description }}"></textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
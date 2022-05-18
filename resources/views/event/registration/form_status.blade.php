<form class="form" method="POST" id="change-status-form" action="{{ route('event.registration.changed.status') }}">
    <input type="hidden" name="event_registration_id" id="event_registration_id" value="{{ $eventRegistration->id }}"/>
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_status')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <div class="row"> 
            <div class="col-md-5">    
                <div class="timeline timeline-2">
                    <div class="timeline-bar"></div>
                    @forelse(@$eventRegistration->statuses as $status)
                    <!--begin::Item-->
                    <div class="timeline-item">
                        <div class="timeline-badge bg-{{ @Config::get('smart.event_registration_status_class')[$status->status] }}"></div>
                        <div class="timeline-content d-flex align-items-center justify-content-between">
                            <span class="mr-3">
                                {{ @Config::get('enums.event_registration_status')[$status->status] }}
                                {{ @$status->changedBy ? 'by '.@$status->changedBy->username : '' }}
                            </span>
                            <span class="text-muted text-right">{{ Carbon\Carbon::parse($status->changed_at)->format('y M, d g:i A') }}</span>
                        </div>
                    </div>
                    <!--end::Item-->
                    @empty
                    @endforelse
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right">{{trans('display.general_status')}}: <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <select class="form-control selectpicker" id="status" name="status" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                            <option value="{{ $eventRegistration->status }}" selected="selected">{{ @Config::get("enums.event_registration_status")[$eventRegistration->status] }}</option>
                            @forelse(@$nextStatuses as $nextStatus)
                            <option value="{{ $nextStatus }}">{{ @Config::get("enums.event_registration_status")[$nextStatus] }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="form-group row payment" style="{{ @$eventRegistration->status == @Config::get('smart.event_registration_status')['created'] ? 'display: none' : ''}}">
                    <label class="col-md-3 col-form-label text-right">{{ trans('display.general_amount') }}: <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <label class="checkbox checkbox-inline checkbox-success">
                                        <input type="checkbox" name="payment_status" id="payment_status" value="1" {{ @$eventRegistration->payment && @$eventRegistration->payment->status ? 'checked=checked' : ''}}>
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <input type="number" class="form-control" id="amount" name="amount" value="{{ @$eventRegistration->payment->amount }}" {{ empty(@$eventRegistration->payment) || $eventRegistration->payment->from_type == 'admin' ? '' : 'readonly'}} min="0" max="{{ @$entryFees->max('entrance_fee')}}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
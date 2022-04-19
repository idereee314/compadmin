<form class="form" method="POST" id="create-event-user-form" action="{{ route('event.user.store') }}">
    <input type="hidden" name="event_id" value="{{$eventId}}" />
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_new')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.username')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control select2" id="user_id" name="user_id[]" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}" multiple>
                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                </select>
                <div class="error-here"></div>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
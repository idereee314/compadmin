<form method="POST" id="member-status-form" action="{{route('update.member.status', $member->id)}}">
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_status')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
    <div class="card-body">
        <div class="form-group d-flex justify-content-center">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_status')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control datatable-input" id="status" name="status" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@Config::get("enums.member_status") as $key => $status)
                    <option value="{{ $key }}" {{$member->status == $key ? 'selected' : ''}}>{{ $status }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{ trans('display.general_close') }}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
<form class="form" id="edit-award-ceremony" action="{{ route('event.award.ceremony.update', ['eventId'=>$eventId,'ceremony'=>$division->id]) }}" method="POST">
    @method('PUT')
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_status_change')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
    <div class="card-body m-4">
        <input type="hidden" name="event_id" id="event_id" value="{{ $eventId }}">
        <input type="hidden" name="belt_id" id="belt_id" value="{{ $division->belt_id }}">
        <input type="hidden" name="weight_id" id="weight_id" value="{{ $division->weight_id }}">

        <div class="form-group mb-7">
            <div class="d-flex align-items-center justify-content-between">
                <label for="is_finish" class="mb-0 font-weight-bold">{{ trans('display.weight_is_finish') }}</label>
                <div>
                    <input type="hidden" name="is_finish" value="0">
                    <label class="checkbox checkbox-outline checkbox-primary checkbox-lg mb-0">
                        <input type="checkbox" id="is_finish" name="is_finish" value="1" {{ old('is_finish', (int)($division->is_finish ?? 0)) == 1 ? 'checked' : '' }}>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group mb-2">
            <div class="d-flex align-items-center justify-content-between">
                <label for="medal_given" class="mb-0 font-weight-bold">{{ trans('display.weight_award_cermony') }}</label>
                <div>
                    <input type="hidden" name="medal_given" value="0">
                    <label class="checkbox checkbox-outline checkbox-success checkbox-lg mb-0">
                        <input type="checkbox" id="medal_given" name="medal_given" value="1" {{ old('medal_given', (int)($division->medal_given ?? 0)) == 1 ? 'checked' : '' }}>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
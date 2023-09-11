<form class="form" method="POST" id="update-event-toplist-point-form" action="{{ route('event.toplist.point.update', $eventToplistPoint->id) }}">
    <input type="hidden" name="_method" value="put" />
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_edit')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <input type="hidden" name="event_id" value={{$eventId}} >
        
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.start_position')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="number" class="form-control" autocomplete="off" name="start_pos" value="{{$eventToplistPoint->start_pos}}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.end_position')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="number" class="form-control" autocomplete="off" name="end_pos" value="{{$eventToplistPoint->end_pos}}"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_point')}}: </label>
            <div class="col-md-9 col-lg-6">
                <input type="number" class="form-control" autocomplete="off" name="point" value="{{$eventToplistPoint->point}}"/>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
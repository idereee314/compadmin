<form class="form" method="POST" id="update-event-config-entries-form" action="{{ route('event.entry.update', $eventEntry->id) }}">
    <input type="hidden" name="_method" value="put" />
    <input type="hidden" name="event_id" value="{{ $eventEntry->event_id }}"/>
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_edit')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_name')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="text" class="form-control" autocomplete="off" name="name" value="{{$eventEntry->name}}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_name_en')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="text" class="form-control" autocomplete="off" name="name_en" value="{{$eventEntry->name_en}}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.human_gender_code')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <div class="radio-inline">
                    @foreach(Config::get("enums.gender_code") as $key => $gender)
                        <label class="radio radio-rounded" for="{{$key}}">
                            <input type="radio" name="gender_code" value="{{$key}}" id="{{$key}}" {{$key == $eventEntry->gender_code ? 'checked' : ''}} />
                            <span></span>
                            {{$gender}}
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="error-here"></div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.entrance_fee')}}: </label>
            <div class="col-md-9 col-lg-6">
                <input type="number" class="form-control" value="{{$eventEntry->entrance_fee}}" min="5000" step="5000" name="entrance_fee"  data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
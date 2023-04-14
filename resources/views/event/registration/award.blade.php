<form class="form" method="POST" id="event-award-form" action="{{ route('event.registration.take.award') }}">
    <input type="hidden" name="event_registration_id" value="{{ $eventRegistration->id }}" />
    <input type="hidden" name="id" value="{{ @$eventRegistration->award->id }}" />
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.comp_award_place')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_member')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <p class="form-control-plaintext text-muted">{{ mb_substr(@$eventRegistration->member->lastname,0,1).'.'.@$eventRegistration->member->firstname }}</p>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_title')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <p class="form-control-plaintext text-muted">{{ @$eventRegistration->event->name }}</p>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <p class="form-control-plaintext text-muted">{{ @$eventRegistration->entry->name }}</p>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_belt')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <p class="form-control-plaintext text-muted">{{ @$eventRegistration->belt->name }}</p>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_age')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <p class="form-control-plaintext text-muted">{{ @$eventRegistration->age->name }}</p>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_weight')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <p class="form-control-plaintext text-muted">{{ @$eventRegistration->weight->weight }}</p>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_place_number')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-5">
                <input type="number" min="1" class="form-control" name="place_number" id="place_number" value="{{ @$eventRegistration->award->place_number }}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required')}}"/>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
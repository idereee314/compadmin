<form class="form" method="POST" id="create-event-registration-form" action="{{ route('event.registration.store') }}">
    <input type="hidden" name="event_id" id="event_id" value="{{ $event_id }}"/>
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_new')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" id="entry_id" name="entry_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@$entries as $entry)
                    <option value="{{ $entry->id }}">{{ $entry->name }}</option>
                    @empty
                    @endforelse
                </select>
                <div class="error-here"></div>
            </div>
        </div> 

        <!-- <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_team')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input class="form-control" id="team_id" name="team_id"/>
            </div>
        </div> -->

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_team')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <!-- <select class="form-control selectpicker" data-live-search="true" name="team_id" id="team_id" data-col-index="5">
                    <option value="">-- {{ trans('display.general_all') }} --</option>
                    @forelse(@$team_list as $teams)
                    <option value="{{ $teams->id }}">{{ $teams->name }}</option>
                    @empty
                    @endforelse
                </select> -->
                <input class="form-control" id="team_name" name="team_name" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required')}}"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_academy')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" data-live-search="true" id="academy_id" name="academy_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@$academies as $academy)
                    <option value="{{ $academy->id }}">{{ $academy->name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row d-none" id="academy_name_other">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_academy_name')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input class="form-control" id="academy_name" name="academy_name" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required')}}"/>
                <div class="error-here"></div>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
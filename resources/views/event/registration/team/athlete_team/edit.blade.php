<form class="form" method="POST" id="update-event-team-registration-form" action="{{ route('event.team.registration.update', $eventTeamRegistration->id)}}">
    <input type="hidden" name="_method" value="put" />
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_edit')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_academy')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-8">
                <select class="form-control selectpicker" data-live-search="true" name="academy_id" id="academy_id" data-col-index="5">
                    <option value="">-- {{ trans('display.general_all') }} --</option>
                    @forelse(@$academies as $academy)
                    <option value="{{ $academy->id }}" {{ $eventTeamRegistration->academy->id == $academy->id ? 'selected' : ''}} >{{ $academy->name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_team')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-8">
                <select class="form-control selectpicker" data-live-search="true" name="team_id" id="team_id" data-col-index="5">
                    <option value="">-- {{ trans('display.general_all') }} --</option>
                    @forelse(@$team_list as $teams)
                    <option value="{{ $teams->id }}" {{ $eventTeamRegistration->team->id == $teams->id ? 'selected' : ''}} >{{ $teams->name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-8">
                <select class="form-control selectpicker" data-live-search="true" name="entry_id" id="entry_id" data-col-index="5">
                    <option value="">-- {{ trans('display.general_all') }} --</option>
                    @forelse(@$eventEntries as $eventEntry)
                    <option value="{{ $eventEntry->id }}" {{ $eventTeamRegistration->entry->id == $eventEntry->id ? 'selected' : ''}} >{{ $eventEntry->name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        @if($eventTeamRegistration->status == @Config::get('smart.event_registration_status')['approved'])
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">Мэдээлэл шалгалт</label>
            <div class="col-md-9 col-lg-5">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <label class="checkbox checkbox-inline">
                                <input type="checkbox" name="is_info_checked" id="is_info_checked" > 
                                <span></span>
                            </label>
                        </span>
                    </div>
                    <input type="text" class="form-control" name="info_description" id="info_description">
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
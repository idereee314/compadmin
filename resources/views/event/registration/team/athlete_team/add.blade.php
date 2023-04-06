<form class="form" method="POST" id="create-event-team-member-registration-form" action="{{ route('event.team.member.store') }}">
<input type="hidden" name="event_id" id="event_id" value="{{ $event_id }}"/>
<input type="hidden" name="team_id" id="team_id" value="{{ $team_id }}"/>

    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel"><strong>{{trans('display.general_new_athlete')}}</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body"> 
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"><strong>{{trans('display.comp_member')}}: </strong><span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control" id="member_id" name="member_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                </select>
                <div class="error-here"></div>
            </div>
        </div>

        <!-- <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"><strong>{{trans('display.comp_voll_position')}}: </strong></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" data-live-search="true" name="athlete_position" id="athlete_position" data-col-index="5">
                    <option value="">-- {{ trans('display.general_all') }} --</option>
                    @forelse(@Config::get('enums.athlete_position') as $key => $position)
                        <option value="{{ $key }}">{{ $position }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div> -->

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"><strong>{{trans('display.comp_voll_role')}}: </strong></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" data-live-search="true" name="athlete_role" id="athlete_role" data-col-index="5">
                    <option value="">-- {{ trans('display.general_all') }} --</option>
                    @forelse(@Config::get('enums.athlete_role') as $key => $roles)
                        <option value="{{ $key }}">{{ $roles }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"><strong>{{trans('display.sport_title')}}: </strong></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" data-live-search="true" name="sport_title" id="sport_title" data-col-index="5">
                    <option value="">-- {{ trans('display.general_all') }} --</option>
                    @forelse(@Config::get('enums.sport_title') as $key => $rank)
                        <option value="{{ $key }}">{{ $rank }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"><strong>{{trans('display.comp_athlete_weight')}}:</strong></label>
            <div class="col-md-9">
                <input type="number" class="form-control" name="athlete_weight" id="athlete_weight" placeholder="{{trans('display.comp_athlete_weight')}}"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"><strong>{{trans('display.comp_athlete_height')}}:</strong></label>
            <div class="col-md-9">
                <input type="number" class="form-control" name="athlete_height" id="athlete_height" placeholder="{{trans('display.comp_athlete_height')}}"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"><strong>{{trans('display.comp_athlete_jersey_number')}}:</strong></label>
            <div class="col-md-9">
                <input type="number" class="form-control" name="jersey_number" id="jersey_number" placeholder="{{trans('display.comp_athlete_jersey_number')}}"/>
            </div>
        </div>

    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
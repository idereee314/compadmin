<form class="form" method="POST" id="update-event-team-member-registration-form" action="{{ route('event.team.member.update', $teamMember->id)}}">
    <input type="hidden" name="_method" value="put" />
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_edit_athlete')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
    <div class="card-body"> 
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"><strong>{{trans('display.comp_member')}}:</strong></label>
            <div class="col-md-9">
                <!-- <a name="member_id" id="member_id">{{@$teamMember->member->lastname}} {{@$teamMember->member->firstname}}</a> -->
                <input type="text" class="form-control" disabled name="member_id" id="member_id" value="{{@$teamMember->member->lastname}} {{@$teamMember->member->firstname}}"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"><strong> {{trans('display.general_team')}}: <span class="text-danger">*</span></strong></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" data-live-search="true" name="team_id" id="team_id" data-col-index="5">
                    <option value="">-- {{ trans('display.general_all') }} --</option>
                    @foreach($team_list as $team)
                        <option value="{{ $team->id }}" {{ $team->id == $teamMember->team_id ? 'selected' : '' }}>{{ $team->name }}</option>
                    @endforeach
                </select>
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
            <label class="col-md-3 col-form-label text-right"><strong>{{trans('display.comp_voll_role')}}:</strong></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" data-live-search="true" name="athlete_role" id="athlete_role" data-col-index="5">
                    <option value="">-- {{ trans('display.general_all') }} --</option>
                    @foreach(config('enums.athlete_role') as $key => $role)
                        <option value="{{ $key }}" {{ (optional($teamMember->member->memberAttribute->where('attribute_id', 3)->where('sport_id', 2)->first())->value == $key) ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"><strong>{{trans('display.sport_title')}}:</strong></label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="sport_title" placeholder="{{trans('display.sport_title')}}" id="sport_title" value="{{ optional(@$teamMember->member->memberAttribute->where('attribute_id', 4)->where('sport_id', 2)->first())->value }}"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"><strong>{{trans('display.comp_athlete_weight')}}:</strong></label>
            <div class="col-md-9">
                <input type="number" class="form-control" name="athlete_weight" placeholder="{{trans('display.comp_athlete_weight')}}" id="athlete_weight" value="{{ optional(@$teamMember->member->memberAttribute->where('attribute_id', 2)->where('sport_id', 2)->first())->value }}"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"><strong>{{trans('display.comp_athlete_height')}}:</strong></label>
            <div class="col-md-9">
            <input type="number" class="form-control" name="athlete_height" placeholder="{{trans('display.comp_athlete_height')}}" id="athlete_height" value="{{ optional(@$teamMember->member->memberAttribute->where('attribute_id', 1)->where('sport_id', 2)->first())->value }}"/>            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"><strong>{{trans('display.comp_athlete_jersey_number')}}:</strong></label>
            <div class="col-md-9">
            <input type="number" class="form-control" name="jersey_number" id="jersey_number" placeholder="{{trans('display.comp_athlete_jersey_number')}}" value="{{ optional(@$teamMember->member->memberAttribute->where('attribute_id', 5)->where('sport_id', 2)->first())->value }}"/>            </div>
        </div>

        <div class="form-group row">
            <label class="col-3 col-form-label"></label>
            <div class="col-9 col-form-label">
                <div class="checkbox-inline">
                    <label class="checkbox checkbox-lg checkbox-success">
                        <input type="checkbox" name="is_team_lead" {{ @$teamMember->is_team_lead ? 'checked' : ''}}/>
                        <span></span>
                        <strong> Багийн ахлагч эсэх </strong>
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
<form class="form" method="POST" id="update-event-team-member-registration-form" action="{{ route('event.team.member.update', $teamMember->id)}}">
    <input type="hidden" name="_method" value="put" />
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_edit_athlete')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
    <div class="card-body"> 
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
                        <option value="{{ $key }} {{ @$roles == @$teamMember->member->memberAttribute->where('attribute_id', 3)->where('sport_id', 2)->first()->value ? 'selected' : ''}}">{{ $roles }}</option>
                    @empty
                    @endforelse
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

    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
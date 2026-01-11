<form class="form" method="POST" id="create-membership-academy-list-form" action="{{route('membership.academy.list.store')}}">
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_new')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{ trans('display.general_sport_type') }}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" data-live-search="true" id="sport_id" name="sport_id" required data-validation-required-message="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse($sport as $spo)
                        <option value="{{ $spo->id }}">{{ $spo->name }} - {{ $spo->name_en }}</option>
                    @empty
                        <option value="">{{ trans('display.no_results_found') }}</option>
                    @endforelse
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_academy')}}:  <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control" name="academy_id" id="academy_id" required data-validation-required-message="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{ trans('display.membership_type') }}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" data-live-search="true" id="membership_type_id" name="membership_type_id" required data-validation-required-message="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse($membershipType as $type)
                        <option value="{{ $type->id }}">{{ $type->name }} - {{ $type->name_en }} - {{ $type->price }}</option>
                    @empty
                        <option value="">{{ trans('display.no_results_found') }}</option>
                    @endforelse
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_start_date')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" id="start_date" name="start_date" readonly data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_end_date')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" id="end_date" name="end_date" readonly data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>
    </div>
    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
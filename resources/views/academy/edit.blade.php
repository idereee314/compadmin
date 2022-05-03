<form class="form" id="edit-academy-form" action="{{route('academy.update', $academy->id)}}"  method="POST">
    <input type="hidden" name="_method" value="put" />
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_edit')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body m-4">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_type')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" id="type" name="type" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@Config::get('enums.org_type') as $key => $type)
                    <option value="{{ $key }}" {{ $academy->type == $key ? 'selected' : '' }}>{{ $type }}</option>
                    @empty
                    @endforelse
                </select>
                <div class="error-here"></div>
            </div>
        </div> 
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.organization')}}: </label>
            <div class="col-md-9">
                <p class="form-control-plaintext text-muted">{{ @$academy->organization->name }}</p>
                <select class="form-control" name="new_organization_id" id="new_organization_id">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_name')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="name" value="{{ $academy->name }}"  data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_name_en')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="name_en" value="{{ $academy->name_en }}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_sort_order')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="number" class="form-control" min="1" max="5000" step="1" name="sort_order" value="{{ $academy->sort_order }}"  data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div> 
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
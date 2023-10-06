<form class="form" method="POST" id="update-mat-settings-form" action="{{ route('config.mat.update', $configMat->id) }}">
    <input type="hidden" name="_method" value="put" />
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_edit')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <input type="hidden" name="event_id" value={{$eventId}}>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_name')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="text" class="form-control" value="{{$configMat->name}}" autocomplete="off" name="name"  data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_name_en')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="text" class="form-control" value="{{$configMat->name_en}}" autocomplete="off" name="name_en"  data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">Prefix: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="text" class="form-control" value="{{$configMat->prefix}}" autocomplete="off" name="prefix"/>
            </div>
        </div>

    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
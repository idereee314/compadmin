
<form class="form" method="POST" id="change-password-form" action="{{route('compad.user.update.password', $id)}}">
    @csrf
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{ trans('display.user_password_change') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body m-4">
        <div class="form-group row">
            <label  class="col-md-3 col-form-label text-right">{{trans('display.user_password')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="password" class="form-control"autocomplete="off" name="password" id="password" placeholder="{{trans('display.user_password')}}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}" data-rule-minlength="8" data-msg-minlength="{{ trans('messages.validation_register_field_password_min') }}"/>
                <div class="error-here"></div>
            </div>
        </div>
        <div class="form-group row">
            <label  class="col-md-3 col-form-label text-right">{{trans('display.user_password_confirm')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="password" class="form-control" autocomplete="off" name="cpassword" autocomplete="off" id="password_confirmation" placeholder="{{trans('display.user_password_confirm')}}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}" data-rule-equalTo="#password" data-msg-equalTo="{{trans('messages.validation_register_field_password_confirmed')}}"/>
                <div class="error-here"></div>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>


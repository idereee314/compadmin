

<form class="form" method="POST" id="add-user-form" action="{{route('user.store')}}">
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_new')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.human_lastname')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" autocomplete="off" name="lastname" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.human_firstname')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" autocomplete="off" name="firstname"  data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.human_email')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="email" class="form-control" autocomplete="off" name="email" data-rule-required="true" data-rule-email="true" data-msg-required="{{ trans('messages.validation_field_required') }}" data-msg-email="{{ trans('messages.validation_mail') }}" data-inputmask="'alias': 'email'"/>
                <div class="error-here"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.username')}}: <span class="text-danger">*</span></label>
           <div class="col-md-9">
            <input class="form-control" name="username" id="username" autocomplete="off" maxlength="20" placeholder="<?php echo e(trans('display.username')); ?>"  data-rule-required="true" data-msg-required="<?php echo e(trans('messages.validation_field_required')); ?>" data-rule-max-lenght="20" data-msg-max="<?php echo e(trans('messages.validation_length')); ?>">
               <div class="error-here"></div>
               <span class="text-muted help-block">{{ trans('messages.info_max_length', ['number' => 20]) }}</span>
           </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.human_phone_number')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="number" class="form-control" name="phone_number" id="phone_number" autocomplete="off" placeholder="{{trans('display.human_phone_number')}}">
                <div class="error-here"></div>
            </div>
        </div>      
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.user_password')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="password" class="form-control" autocomplete="off" name="password" id="password" placeholder="{{trans('display.user_password')}}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}" data-rule-minlength="8" data-msg-minlength="{{ trans('messages.validation_register_field_password_min') }}"/>
                <div class="error-here"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.user_password_confirm')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="password" class="form-control" name="cpassword" autocomplete="off" id="password_confirmation" placeholder="{{trans('display.user_password_confirm')}}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}" data-rule-equalTo="#password" data-msg-equalTo="{{trans('messages.validation_register_field_password_confirmed')}}"/>
                <div class="error-here"></div>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>


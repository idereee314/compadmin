

<form class="form" id="edit-compaduser-form" action="{{route('compaduser.update', $compadUser->id)}}"  method="POST">
    <input type="hidden" name="_method" value="put" />
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_edit')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body m-4">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.human_lastname')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" autocomplete="off" name="firstname" value="{{ $compadUser->lastname }}"  data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.human_firstname')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" autocomplete="off" name="lastname" value="{{ $compadUser->firstname }}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.human_email')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="email" class="form-control" autocomplete="off" name="email" value="{{ $compadUser->email }}" data-rule-required="true" data-rule-email="true" data-msg-required="{{ trans('messages.validation_field_required') }}" data-msg-email="{{ trans('messages.validation_mail') }}" data-inputmask="'alias': 'email'"/>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.username')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input class="form-control" name="username" id="username" autocomplete="off" value="{{ $compadUser->username }}" placeholder="{{trans('display.username')}}">
                <div class="error-here"></div>
            </div>
        </div>
    </div>


    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>

</form>

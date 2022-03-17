<form class="form" method="POST" id="add-member-form" action="{{route('member.store')}}">
    @csrf
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_new')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-right">{{trans('display.human_register_number')}}: <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control" autocomplete="off" name="register_number" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                    <div class="error-here"></div>
                </div>
            </div>

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
                <label class="col-md-3 col-form-label text-right">{{trans('display.human_contact_phone')}}: <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <input type="number" class="form-control" name="contact_phone" id="contact_phone" autocomplete="off" placeholder="{{trans('display.human_contact_phone')}}" onkeyup="numberOnly(this)">
                    <div class="error-here"></div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-right">{{trans('display.human_gender_code')}}: <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <div class="radio-inline">
                        @foreach(Config::get("smart.gender_code") as $key => $gender)
                            <label class="radio radio-rounded">
                                <input type="radio" name="gender_code" value="{{$key}}" />
                                <span></span>
                                {{$gender}}
                            </label>
                        @endforeach
                        <div class="error-here"></div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-right">{{trans('display.human_birth')}}<span class="text-danger"> *</span></label>
                <div class="col-md-9">
                    <input class="form-control" type="date" name="birth" id="birth" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                    <div class="error-here"></div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-right">{{trans('display.profile_photo')}}: <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <div class="image-input image-input-outline" id="profile_photo">
                        <div class="image-input-wrapper" style="background-image: url(/assets/media/users/100_1.jpg)"></div>
                       
                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                         <i class="fa fa-pen icon-sm text-muted"></i>
                         <input type="file" name="profile_photo" accept=".png, .jpg, .jpeg" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                         <input type="hidden" name="profile_avatar_remove"/>
                        </label>
                       
                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                         <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                    </div>
                    <div class="error-here"></div>
                </div>
            </div>    
            
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-right">{{trans('display.human_contact_phone')}}: <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <div class="image-input image-input-outline" id="id_photo">
                        <div class="image-input-wrapper" style="background-image: url(/assets/media/users/100_1.jpg)"></div>
                       
                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                         <i class="fa fa-pen icon-sm text-muted"></i>
                         <input type="file" name="id_photo" accept=".png, .jpg, .jpeg" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                         <input type="hidden" name="profile_avatar_remove"/>
                        </label>
                       
                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                         <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                    </div>
                    <div class="error-here"></div>
                </div>
            </div>   
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>

<script>
    function numberOnly(input)
    {
        var num =  /[^0-9]/gi;
        input.value = input.value.replace(num, '');
    }

    var profilePhoto = new KTImageInput('profile_photo');
    var idPhoto = new KTImageInput('id_photo');
</script>
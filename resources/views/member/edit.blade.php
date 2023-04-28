<form class="form" id="edit-member-form" action="{{route('member.update', $member->id)}}"  method="POST" enctype="multipart/form-data">
    <input type="hidden" name="_method" value="put" />
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_edit')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body m-4">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.human_register_number')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" autocomplete="off" name="register_number" id="register_number" value="{{ $member->register_number }}"  data-inputmask-regex="^[А-ЯӨҮа-яөү]{2}[0-9]{8}$" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}" style="text-transform: uppercase;"/>
                <div class="error-here"></div>
                <span class="form-text text-muted">Регистрын дугаарын үсгийг томоор бичнэ</span>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.human_lastname')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" autocomplete="off" name="lastname" value="{{ $member->lastname }}"  data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.human_firstname')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" autocomplete="off" name="firstname" value="{{ $member->firstname }}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.human_contact_phone')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control only-phone" name="contact_phone" id="contact_phone" value="{{ $member->contact_phone }}" autocomplete="off" value="{{ $member->phone_number }}" placeholder="{{trans('display.human_contact_phone')}}">
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.human_gender_code')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <div class="radio-inline">
                    @foreach(Config::get("smart.gender_code") as $key => $gender)
                        <label class="radio radio-rounded">
                            <input type="radio" name="gender_code" value="{{$key}}" {{$key == $member->gender_code ? 'checked' : ''}} />
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
                <div class="input-group date">
                    <input type="text" name="birth" id="birth" class="form-control" value="{{ $member->birth }}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="la la-calendar-check-o"></i>
                        </span>
                    </div>
                </div>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.human_country')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" data-live-search="true" id="country_id" name="country_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@$countries as $country)
                    <option value="{{ $country->id }}" {{$member->country_id == $country->id ? 'selected' : ''}}>{{ $country->name }} - {{ $country->name_en }} - {{ $country->abbreviation }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.profile_photo')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <div class="image-input image-input-outline" id="profile_photo">
                    @if (@$member->profile_url xor ((@env('production') && \Storage::disk('s3')->exists($member->profile_url)) || @env('local')))
                        <img alt="..." id="profile-image" src="{{\Storage::disk('s3')->url($member->profile_url)}}" style="max-width: 150px; max-height:120px">
                    @else
                        <img alt="..." id="profile-image" alt="" src="{{asset('/assets/media/users/100_1.jpg')}}" style="max-width: 150px; max-height:120px">
                    @endif
                   
                    <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                     <i class="fa fa-pen icon-sm text-muted"></i>
                     <input type="file" name="profile_photo" accept=".png, .jpg, .jpeg" onchange="changeImage(event)"/>
                     <input type="hidden" name="profile_photo_remove" id="profile_photo_remove"/>
                    </label>
                   
                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"  onclick="removeImage()" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                     <i class="ki ki-bold-close icon-xs text-muted"></i>
                    </span>
                </div>
            </div>
        </div>    
        
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.id_photo')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <div class="image-input image-input-outline" id="id_photo">
                    @if (@$member->id_url xor ((@env('production') && \Storage::disk('s3')->exists($member->id_url)) || @env('local')))
                        <img alt="..." id="id-image" src="{{\Storage::disk('s3')->url($member->id_url)}}" style="max-width: 150px; max-height:120px">
                    @else
                        <img alt="..." id="id-image" alt="" src="{{asset('/assets/media/users/100_1.jpg')}}" style="max-width: 150px; max-height:120px">
                    @endif
                    <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                     <i class="fa fa-pen icon-sm text-muted"></i>
                     <input type="file" name="id_photo" src="" accept=".png, .jpg, .jpeg" onchange="changeIdImage(event)"/>
                     <input type="hidden" name="id_photo_remove" id="id_photo_remove"/>
                    </label>
                   
                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"  onclick="removeIdImage()" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                     <i class="ki ki-bold-close icon-xs text-muted"></i>
                    </span>
                </div>
               
            </div>
        </div>  
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_status')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control selectpicker" id="status" name="status" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@Config::get('enums.member_status') as $key => $status)
                    <option value="{{ $key }}" {{$member->status == $key ? 'selected' : ''}}>{{ $status }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>

</form>

<script>
    var profile_photo = document.getElementById("profile_photo");
    var id_photo = document.getElementById("id_photo");

    function changeImage(event)
    {
        if(event.target.files.length > 0){
            var element = document.getElementById("profile-image");
            profile_photo.classList.add('image-input-changed')
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("profile-image");
            document.getElementById("profile_photo_remove").value = 'on';
            $('#profile-image').hide();
            $('#profile-image').fadeIn(900);

            preview.src = src;
            preview.style.display = "block";
        }
    }
    function changeIdImage(event)
    {
        if(event.target.files.length > 0){
            var element = document.getElementById("id-image");
            id_photo.classList.add('image-input-changed')
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("id-image");
            document.getElementById("id_photo_remove").value = 'on';
            $('#id-image').hide();
            $('#id-image').fadeIn(900);

            preview.src = src;
            preview.style.display = "block";
        }
    }

    function removeImage()
    {
        var src = "";
        var preview = document.getElementById("profile-image");
        document.getElementById("profile_photo_remove").value = 'off';
        profile_photo.classList.remove('image-input-changed');
        preview.src = src;
    }

    function removeIdImage()
    {   
        var src = "";
        var preview = document.getElementById("id-image");
        id_photo.classList.remove('image-input-changed');
        document.getElementById("id_photo_remove").value = 'off';
        preview.src = src;
    }
    
</script>
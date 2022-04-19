@extends('default')

@section('styles')
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.css')}}">
@endsection

@section('content')

<section id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed page-loading">
    <!--begin::Main-->
    <!--begin::Header Mobile-->
        @include('layouts.mobile')
    <!--end::Header Mobile-->
        <!--begin::Aside-->
        @include('layouts.aside')
        <!--end::Aside-->
        <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                <!--begin::Header-->
                @include('layouts.header')
                
                <div class="d-flex flex-column-fluid">
                    <!--begin::Container-->
                    <div class="container">
                        <!--begin::Card-->
                        <div class="card card-custom">
                            <div class="card-header flex-wrap py-5">
                                <div class="card-title">
                                    <h3 class="card-label">Нууц үг солих 
                                    <span class="d-block text-muted pt-2 font-size-sm">Хэрэглэгч</span></h3>
                                </div>
                            </div>
                            <form id="change-password-form" class="form-horizontal smart-form" action="{{ route('user.update.password', $id) }}" method="POST">
                                <div class="card-body">
                                <!--begin: Datatable-->
                                    @csrf 
                                        <div class="panel-sub-heading"></div>
                                        <div class="form-group row">
                                            <label class="col col-md-4 col-form-label text-right">{{trans('display.user_current_password')}}: <span class="text-danger">*</span></label>
                                            <div class="col col-md-6">
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-lock"></i></span></div>
                                                    <input type="password" autocomplete="off" class="form-control" name="current_password" id="current_password" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}" data-rule-minlength="8" data-msg-minlength="{{ trans('messages.validation_register_field_password_min') }}">
                                                    </div>
                                                <div class="error-here"></div>
                                            </div>
                                        </div>
                
                                        <div class="form-group row">
                                            <label class="col col-md-4 col-form-label text-right">{{trans('display.user_password')}}: <span class="text-danger">*</span></label>
                                            <div class="col col-md-6">
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-unlock"></i></span></div>
                                                    <input type="password" autocomplete="off" class="form-control" name="password" id="password" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}" data-rule-minlength="8" data-msg-minlength="{{ trans('messages.validation_register_field_password_min') }}">
                                                    </div>
                                                <div class="error-here"></div>
                                            </div>
                                        </div>
                
                                        <div class="form-group row">
                                            <label class="col col-md-4 col-form-label text-right">{{trans('display.user_password_confirm')}}: <span class="text-danger">*</span></label>
                                            <div class="col col-md-6">
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-unlock-alt"></i></span></div>
                                                        <input type="password" autocomplete="off" class="form-control" name="password_confirmation" id="password_confirmation" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}" data-rule-equalTo="#password" data-msg-equalTo="{{trans('messages.validation_register_field_password_confirmed')}}">
                                                    </div>
                                                <div class="error-here"></div>
                                            </div>
                                        </div>
                                    </div>
                               
                                    <!--end: Datatable-->
                                    <div class="card-footer">
                                        <div class="float-right">
                                            <button class="btn btn-success" type="submit">{{trans('display.general_save')}}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--begin::Footer-->
                @include('layouts.footer')
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        <!--end::Main-->
</section>

@section('javascript')


<script>
$(document).ready(function() {
    $('#change-password-form').validate({
    ignore: [],
    highlight:function(element) {
        $(element).parents('.form-group').addClass('has-error has-feedback');
    },
    unhighlight: function(element) {
        $(element).parents('.form-group').removeClass('has-error');
    },
    submitHandler: function(form) {
        var oldPassword = $(form).find('#current_password').val();
        $.post('{!! route('user.check.password') !!}', {password: oldPassword}, function(data){
            if (data == false) {
                 swal.fire({
                        text: "Одоогийн нууц үг буруу байна",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Дахин оролдох",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function() {
                        KTUtil.scrollTop();
                    });
                return false;
            }
            else
            {
                $(form).find('#current_password').after('');
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    beforeSend: function() {
                        $('#preloader').show();
                    },
                    success: function(response) {
                        $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                    },
                    error: function (xhr, textStatus, error) {
                        console.log(xhr.statusText);
                        console.log(textStatus);
                        console.log(error);
                    },
                    async: false
                }).done(function(data) {
                    //submitButton.prop('disabled', false);
                });
            }
        });
    },
    errorPlacement: function(error, element) {
        if($(element).parents('.form-group').find(".error-here").length > 0){
             error.appendTo($(element).parents('.form-group').find(".error-here"));
        } else {
              error.insertAfter(element);
        }
    }
});
});

</script>
@endsection
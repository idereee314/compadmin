
@extends('default')

@section('styles')
    <link rel="stylesheet" href="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.css')}}">
@endsection

@section('content')

<section id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed page-loading">
    @include('layouts.mobile')
    @include('layouts.aside')
        <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
            @include('layouts.header')

            <div class="subheader py-lg-2 subheader-solid" id="kt_subheader">
                <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                    <div class="d-flex align-items-center flex-wrap mr-2">
                        <i class="fas fa-home pr-4" style="color:black"> </i>
                        <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                        <h2 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{trans('menu.user_management_user')}}</h2>
                        <span style="font-style: italic">
                            {{trans('display.user_password_change')}}
                        </span>
                    </div>
                </div>
            </div>
            <form id="change-password-form" class="form-horizontal smart-form" action="{{ route('user.update.password', $id) }}" method="POST">
                @csrf
                <div class="card card-custom gutter-b m-4">
                    <div class="card-header flex-wrap py-3 bg-gray-100">
                        <div class="card-title">
                            <h3 class="card-label">{{trans('display.user_password_change') }}
                            </h3>
                        </div>
                        <div class="card-toolbar">
                            <div>
                                <button class="btn btn-sm" data-container="body" data-action="collapse" data-toggle="tooltip" data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
                                <button class="btn btn-sm" data-container="body" data-action="remove" data-toggle="tooltip" data-placement="top" data-title="Remove"><i class="fa fa-times"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="card-body">
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

                    <div class="card-footer bg-gray-100">
                        <div class="float-right">
                            <button class="btn btn-success" type="submit">{{trans('display.general_save')}}</button>
                        </div>
                    </div>
                </div>
            </form>
            @include('layouts.footer')
        </div>
</section>

@endsection

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

@stop

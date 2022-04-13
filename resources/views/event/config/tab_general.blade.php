<form class="form" method="POST" id="update-event-config-form" action="{{ route('event.config.update', $eventConfig->id) }}">
    <input type="hidden" name="_method" value="put" />
    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.event_title')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="hidden" name="event_id" value="{{$eventConfig->event_id}}">
                <input class="form-control form-control-lg" disabled value="{{ $eventConfig->event->name }}"/>
            </div>
        </div> 
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.reg_date')}}<span class="text-danger"> *</span></label>
            <div class="col-md-9 col-lg-6">
                <div class="input-group" id="kt_reg_date">
                    <input type="text" name="reg_date" id="reg_date" class="form-control" readonly="readonly" value="{{ Carbon\Carbon::parse(@$eventConfig->reg_start_date)->format('Y-m-d H:i') }} / {{ Carbon\Carbon::parse(@$eventConfig->reg_end_date)->format('Y-m-d H:i') }}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="la la-calendar-check-o"></i>
                        </span>
                    </div>
                </div>
                <div class="error-here"></div>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>

<script>
$(document).ready(function() {
    $('#kt_reg_date').daterangepicker({
        buttonClasses: ' btn',
        applyClass: 'btn-primary',
        cancelClass: 'btn-secondary',
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'YYYY-MM-DD H:mm'
        }
    }, function(start, end, label) {
        $('#kt_reg_date .form-control').val( start.format('YYYY-MM-DD H:mm') + ' / ' + end.format('YYYY-MM-DD H:mm'));
    });

    $('#update-event-config-form').validate({
        ignore: [],
        highlight:function(element) {
            $(element).parents('.form-group').addClass('has-error has-feedback');
        },
        unhighlight: function(element) {
            $(element).parents('.form-group').removeClass('has-error');
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: new FormData(form),
                success: function(response) {
                    var tab_id = $("#config_tabs").find("li.active a").data("tabid");
                    $(".tab-content").find("#" + tab_id).empty();

                    $("#config_tabs").find("li.active a").trigger('click');
                    $('.form-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false,
                processData: false,
                contentType: false
            });
        },
        errorPlacement: function(error, element) {
            if($(element).parents('.form-group').find(".error-here")){
                error.appendTo($(element).parents('.form-group').find(".error-here"));
            } else {
                error.insertAfter(element);
            }
        }
    });
});
</script>
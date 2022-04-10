<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/jasny-bootstrap-fileinput/css/jasny-bootstrap-fileinput.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2-bootstrap.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2-custom.css')}}">

<form class="form" method="POST" id="update-event-config-form" action="{{ route('event.config.update', $eventConfig->id) }}">
    <input type="hidden" name="_method" value="put" />
    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.event_name')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="hidden" name="event_id" value="{{$eventConfig->event_id}}">
                <input class="form-control form-control-lg" disabled value="{{$eventConfig->event->name.'/'.$eventConfig->event->event_date.'-'.$eventConfig->event->due_date}}"/>
            </div>
        </div> 
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.reg_start_date')}}<span class="text-danger"> *</span></label>
           <div class="col-md-9 col-lg-6">
                <div class="input-group date" id="reg_start_date" data-target-input="nearest">
                    <input type="text" name="reg_start_date" class="form-control datetimepicker-input" value="{{ $eventConfig->reg_start_date }}" placeholder="{{trans('display.reg_start_date')}}"  data-target="#reg_start_date" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                    <div class="input-group-append" data-target="#reg_start_date" data-toggle="datetimepicker">
                        <span class="input-group-text">
                            <i class="ki ki-calendar"></i>
                        </span>
                    </div>
                </div>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.reg_end_date')}}<span class="text-danger"> *</span></label>
           <div class="col-md-9 col-lg-6">
                <div class="input-group date" id="reg_end_date"  data-target-input="nearest">
                    <input type="text" name="reg_end_date" class="form-control datetimepicker-input" value="{{ $eventConfig->reg_end_date }}"  placeholder="{{trans('display.reg_end_date')}}"  data-target="#reg_end_date" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                    <div class="input-group-append" data-target="#reg_end_date" data-toggle="datetimepicker">
                        <span class="input-group-text">
                            <i class="ki ki-calendar"></i>
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
                        // var page = eventConfigTable.page.info().page;
                        // $('#eventConfigModal').find("#close").trigger('click');
                        // $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                        // eventConfigTable.page(page).draw('page');
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
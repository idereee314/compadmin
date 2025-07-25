<form method="POST" id="event-edit-form" class="form-horizontal smart-form" action="{!! route('event.update', $event->id) !!}">
    <input type="hidden" name="_method" value="put" />
        <div class="form-body">
            <div class="form-group">
                <label class="col-sm-3 text-right">{{trans('display.general_category')}} <span class="asterisk">*</span></label>
                <div class="col-md-9 col-sm-12 col-lg-7">
                    <select class="chosen-select" multiple name="category[]" data-placeholder="-- {{ trans('display.general_select') }} --" data-rule-required="true" data-msg-required="{{ trans('validation.required') }}">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $event->categories->contains($category->id) ? 'selected="selected"' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="error-here"></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 text-right">{{trans('display.general_name')}} <span class="asterisk">*</span></label> 
                <div class="col-md-9 col-sm-12 col-lg-7">
                    <input class="form-control" type="text" name="name" id="name" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}" value="{{$event->name}}">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 text-right">{{trans('display.general_description')}}</label>
                <div class="col-md-9 col-sm-12 col-lg-7">
                    <textarea class="form-control" rows="4" cols="50" name="description" id="description">{{$event->description}}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 text-right">{{trans('display.general_duration')}}  <span class="asterisk">*</span></label>
                <div class="col-md-9 col-sm-12 col-lg-7">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input class="form-control date-range-picker-time" id="dates" name="dates" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}" value="{{ Carbon\Carbon::parse(@$event->event_date)->format('Y-m-d') }} аас {{ Carbon\Carbon::parse(@$event->due_date)->format('Y-m-d') }}"/>
                        <div class="error-here"></div>
                    </div>
                </div>
            </div>
            <div class="row" id="div-event-date">
                @forelse($event->datetimes as $date)
                <div class="form-group">
                    <div class="col-md-3 col-sm-12 text-right">
                        <input type="type" class="form-control inline" name="event_date[]" id="event_date" value="{{ Carbon\Carbon::parse($date->start_date)->format('Y-m-d') }}" readonly/>
                    </div>
                    <div class="col-md-9 col-sm-12 col-lg-7">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="timepicker input-group">
                                    <input class="form-control" type="text" data-format="hh:mm" data-inputmask="hh:mm" name="start_time[]" id="start_time" value="{{ Carbon\Carbon::parse($date->start_date)->format('H:i') }}" data-rule-required="true" data-msg-required=""/>
                                    <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="timepicker input-group">
                                    <input class="form-control" type="text" data-format="hh:mm" data-inputmask="hh:mm" name="end_time[]" id="end_time" value="{{ Carbon\Carbon::parse($date->end_date)->format('H:i') }}" data-rule-required="true" data-msg-required=""/>
                                    <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse
            </div>
            <div class="form-group">
                <label class="col-sm-3 text-right">{{trans('display.general_status')}} <span class="asterisk">*</span></label>
                <div class="col-md-9 col-sm-12 col-lg-7">
                    <select class="form-control" name="status" data-rule-required="true" data-msg-required="{{ trans('validation.required') }}">
                        @foreach($statuses as $status)
                            <option value="{{ $status['code'] }}" {{ $status['code'] == $event->status ? 'selected="selected"' : '' }}>{{ $status['label'] }}</option>
                        @endforeach
                    </select>
                    <div class="error-here"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" id="close" class="btn btn-default" data-dismiss="modal">{{ trans('display.general_close') }}</button>
        <button type="submit" class="btn btn-success">{{ trans('display.general_save') }}</button>
    </div>
</form>
<script type="text/javascript">
$('.chosen-select').chosen({ max_selected_options: 1 });
$(':input').inputmask();

$('.timepicker').datetimepicker({
    timePicker24Hour: true,
    pickDate: false,
    timeFormat:  "hh:mm",
    pickSeconds: false,
    minuteStep: 1,
});

$('.date-range-picker-time').daterangepicker({
    showWeekNumbers: true,
    showDropdowns: true,
    //timePicker: true,
    //timePicker24Hour: true,
    autoUpdateInput: false,
    //timePickerIncrement: 10,
    minYear: 2021,
    maxYear: parseInt(moment().format("YYYY"), 1),
    locale: {
        format: 'YYYY-MM-DD',
        separator: " аас ",
        applyLabel: "Оруулах",
        cancelLabel: "Болих",
        fromLabel: "аас",
        toLabel: "руу",
        customRangeLabel: "Сонголт",
        daysOfWeek: [
            "Ня",
            "Да",
            "Мя",
            "Лха",
            "Пү",
            "Ба",
            "Бя"
        ],
        firstDay: 1
    }
}, function(start, end, label) {
    var html = "";
    for(var d = new Date(start); d <= new Date(end); d.setDate(d.getDate() + 1))
    {
        html += '\
        <div class="form-group">\
            <div class="col-md-3 col-sm-12 text-right">\
                <input type="type" class="form-control inline" name="event_date[]" id="event_date" value="'+moment(d).format("YYYY-MM-DD")+'" readonly/>\
            </div>\
            <div class="col-md-9 col-sm-12 col-lg-7">\
                <div class="row">\
                    <div class="col-md-6">\
                        <div class="timepicker input-group">\
                            <input class="form-control" type="text" data-format="hh:mm" data-inputmask="hh:mm" name="start_time[]" id="start_time" data-rule-required="true" data-msg-required=""/>\
                            <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>\
                        </div>\
                    </div>\
                    <div class="col-md-6">\
                        <div class="timepicker input-group">\
                            <input class="form-control" type="text" data-format="hh:mm" data-inputmask="hh:mm" name="end_time[]" id="end_time" data-rule-required="true" data-msg-required=""/>\
                            <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>\
                        </div>\
                    </div>\
                </div>\
            </div>\
        </div>\
        ';

        $.when($("#div-event-date").html(html)).then(function( data, textStatus, jqXHR ) {
            $(":input").inputmask(); 
            $('.timepicker').datetimepicker({
                timePicker24Hour: true,
                pickDate: false,
                timeFormat:  "hh:mm",
                pickSeconds: false,
                minuteStep: 1,
            });
        });                    
    }
});

$('.date-range-picker-time').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm') + ' аас ' + picker.endDate.format('YYYY-MM-DD HH:mm'));
});

$('.date-range-picker-time').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
});

$('#event-edit-form').validate({
	ignore: [],
	highlight:function(element) {
		$(element).parents('.form-group').addClass('has-error has-feedback');
	},
	unhighlight: function(element) {
		$(element).parents('.form-group').removeClass('has-error');
	},
	submitHandler: function(form) {

        let org_id = $("#event_id").val();

		$.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize(),
            success: function(response) {
                var tab_id = $("#event_tabs").find("li.active a").data("tabid");
                $(".tab-content").find("#" + tab_id).empty();
                $("#event_tabs").find("li.active a").trigger('click');
                $('.form-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
            },
            error: function (xhr, textStatus, error) {
                console.log(xhr.statusText);
                console.log(textStatus);
                console.log(error);
            },
            async: false          
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

</script>
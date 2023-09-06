<form class="form" method="post" id="update-event-config-form" action="{{ route('event.config.update', $eventConfig->id) }}">
    <input type="hidden" name="_method" value="put"/>
    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.event_title')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="hidden" name="event_id" value="{{$eventConfig->event_id}}">
                <input class="form-control form-control-lg" disabled value="{{ $eventConfig->event->name }}"/>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_sport_type')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <select class="form-control selectpicker" id="sport_id" name="sport_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                    @foreach($sports as $type)
                        <option value="{{ $type->id }}" {{ $type->id == @$eventConfig->sport_id ? 'selected': '' }}>- {{ $type->name }}</option>
                    @endforeach
                </select>
                <div class="error-here"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_rank_season')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <select class="form-control selectpicker" id="eventRankSeason" name="eventRankSeason" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                    @foreach($eventRankSeason as $season)
                        <option value="{{ $season->id }}" {{ $season->id == @$eventConfig->event_rank_season_id ? 'selected': '' }}>- {{ $season->name }} - {{ Config::get("enums.sport_category")[$season->sport_id] }}</option>
                    @endforeach
                </select>
                <div class="error-here"></div>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_event_category')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <select class="form-control selectpicker" id="eventCategory" name="eventCategory" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                    @foreach($eventCategory as $category)
                        <option value="{{ $category->id }}" {{ $category->id == @$eventConfig->point_type_id ? 'selected': '' }}>- {{ $category->name }}</option>
                    @endforeach
                </select>
                <div class="error-here"></div>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.reg_date')}}<span class="text-danger"> *</span></label>
            <div class="col-md-9 col-lg-6">
                <div class="input-group" id="kt_reg_date">
                    <input type="text" name="reg_date" id="reg_date" class="form-control" readonly="readonly" value="{{ Carbon\Carbon::parse(@$eventConfig->reg_start_date)->format('Y-m-d H:i:s') }} / {{ Carbon\Carbon::parse(@$eventConfig->reg_end_date)->format('Y-m-d H:i:s') }}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
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
            <label class="col-md-3 col-form-label text-right">{{trans('display.reg_payment_date')}}<span class="text-danger"> *</span></label>
            <div class="col-md-9 col-lg-6">
                <div class="input-group" id="kt_reg_payment_date">
                    <input type="text" name="reg_payment_date" id="reg_payment_date" data-toggle="datetimepicker" data-target="#reg_payment_date" class="form-control datetimepicker-input" readonly="readonly" value="{{ Carbon\Carbon::parse(@$eventConfig->payment_final_date)->format('Y-m-d H:i:s') }}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
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
            <label class="col-md-3 col-form-label text-right">{{trans('display.reg_update_date')}}<span class="text-danger"> *</span></label>
            <div class="col-md-9 col-lg-6">
                <div class="input-group" id="kt_reg_update_date">
                    <input type="" name="reg_update_date" id="reg_update_date" data-toggle="datetimepicker" data-target="#reg_update_date" class="form-control datetimepicker-input" readonly="readonly" value="{{ Carbon\Carbon::parse(@$eventConfig->update_final_date)->format('Y-m-d H:i:s') }}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
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
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_result_type')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <select class="form-control selectpicker" id="eventResultType" name="eventResultType" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                    @foreach($eventResultType as $type)
                        <option value="{{ $type->id }}" {{ $type->id == @$eventConfig->event_result_type_id ? 'selected': '' }}>- {{ $type->name }} </option>
                    @endforeach
                </select>
                <div class="error-here"></div>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_org_type')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <select class="form-control select2" id="org_types" name="org_types[]" multiple="multiple" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@Config::get('enums.org_type') as $key => $type)
                    <option value="{{ $key }}" {{ \Illuminate\Support\Str::contains(@$eventConfig->org_types, $key) ? 'selected="selected"' : '' }}>{{ $type }}</option>
                    @empty
                    @endforelse
                </select>
                <div class="error-here"></div>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"></label>
            <div class="col-md-9 col-lg-6">
                <label class="checkbox">
                    <input type="checkbox" name="is_athlete_limit" id="is_athlete_limit">
                    <span></span>&nbsp;
                    Лимиттэй эсэх
                </label>
            </div>
        </div>

        <div class="form-group row d-none" id="athletes_limit">
            <label class="col-md-3 col-form-label text-right">Тамирчдын тоо: </label>
            <div class="col-md-9 col-lg-6">
                <input type="number" class="form-control" autocomplete="off" name="athlete_limit" id="athlete_limit"/>
            </div>
        </div>
        @if(@$eventConfig->sport_id == 1)
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"></label>
            <div class="col-md-9 col-lg-6">
                <label class="checkbox">
                    <input type="checkbox" name="is_disqualify">
                    <span></span>&nbsp;
                    Жингээр хасагдсан бол шууд хасна.
                </label>
            </div>
        </div>
        @endif

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"></label>
            <div class="col-md-9 col-lg-6">
                <label class="checkbox">
                    <input type="checkbox" name="is_active" {{ @$eventConfig->is_active ? 'checked="checked"' : '' }}>
                    <span></span>&nbsp;
                    {{ trans('display.general_active') }}
                </label>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right"></label>
            <div class="col-md-9 col-lg-6">
                <label class="checkbox">
                    <input type="checkbox" name="is_team" id="is_team" {{ @$eventConfig->is_team ? 'checked="checked"' : '' }}>
                    <span></span>&nbsp;
                    {{ trans('display.general_is_team') }}
                </label>
            </div>
        </div>
        <div class="form-group row d-none" id="is_athlete_must_pay">
            <label class="col-md-3 col-form-label text-right"></label>
            <div class="col-md-9 col-lg-6">
                <label class="checkbox">
                    <input type="checkbox" name="is_athlete_pay" >
                    <span></span>&nbsp;
                    {{ trans('display.general_is_pay_athlete') }}
                </label>
            </div>
        </div>
        
    </div>

    <div class="modal-footer text-right">
        <a href="{{ route('event.config.index') }}" class="btn btn-light-primary font-weight-bold">{{trans('display.general_back')}}</a>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>

<script>
$(document).ready(function() {
    $('#sport_id').selectpicker();
    $('#eventCategory').selectpicker();
    $('#eventRankSeason').selectpicker();
    $('#eventResultType').selectpicker();
    $('#kt_reg_date').daterangepicker({
        buttonClasses: ' btn',
        applyClass: 'btn-primary',
        cancelClass: 'btn-secondary',
        startDate: '{{ @$eventConfig->reg_start_date ? Carbon\Carbon::parse($eventConfig->reg_start_date)->format('Y-m-d g:i A') : '' }}',
        endDate: '{{ @$eventConfig->reg_end_date ? Carbon\Carbon::parse(@$eventConfig->reg_end_date)->format('Y-m-d g:i A') : '' }}',
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'YYYY-MM-DD hh:mm A'
        },
        
    }, function(start, end, label) {
        $('#kt_reg_date .form-control').val( start.format('YYYY-MM-DD hh:mm A') + ' / ' + end.format('YYYY-MM-DD hh:mm A'));
    });

    $('#reg_payment_date').datetimepicker({
        rtl: KTUtil.isRTL(),
        todayHighlight: true,
        orientation: "bottom left",
        format: 'yyyy-MM-D HH:mm',
        templates: {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>',
        },
        locale: 'mn',
    });

    $('#reg_update_date').datetimepicker({
        rtl: KTUtil.isRTL(),
        todayHighlight: true,
        orientation: "bottom left",
        format: 'yyyy-MM-D HH:mm',
        templates: {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>'
        },
        locale: 'mn',
    });

    $('#update-event-config-form select[id=org_types]').select2({});

    $('#is_athlete_limit').on('change', function() {    
        if(this.checked) {
            $("#athletes_limit").removeClass('d-none');
        }
        else {
            $("#athletes_limit").addClass('d-none');
        }
    });
    $('#is_athlete_limit').trigger('change');

    $('#is_team').on('change', function() {    
        if(this.checked) {
            $("#is_athlete_must_pay").removeClass('d-none');
        }
        else {
            $("#is_athlete_must_pay").addClass('d-none');
        }
    });
    $('#is_team').trigger('change');

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
                    if(response.status == 'success')
                    {
                        $(".tab-content").find("div.active").empty();
                        $("#config_tabs").find("li.active a").trigger('click');
                        toastr.success(response.msg);
                    }
                    else {
                        toastr.error(response.errors, response.msg, {
                            "closeButton": true,
                            "timeOut": "0",
                            "extendedTimeOut": "0",
                        });
                    }
                    
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
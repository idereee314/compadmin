<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/jasny-bootstrap-fileinput/css/jasny-bootstrap-fileinput.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2-bootstrap.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2-custom.css')}}">

<form class="form" method="POST" id="create-event-registration-form" action="{{ route('event.registration.store') }}">
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_new')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_member')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input class="form-control form-control-lg" id="member_id" name="member_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_title')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control datatable-input" id="event_id" name="event_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    @forelse(@$competitions as $competition)
                    <option value="{{ $competition->event_id }}">{{ $competition->event->name }}: /{{ $competition->reg_start_date.'-'.$competition->reg_end_date }}/</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input class="form-control form-control-lg" type="text" id="entry_id" name="entry_id" onchange="entryFunction(event)" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_belt')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input class="form-control form-control-lg" id="entry_belt_id" name="entry_belt_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_age')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input class="form-control form-control-lg" id="entry_age_id" name="entry_age_id"  onchange="ageFunction(event)" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
            </div>
        </div> 

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_weight')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input class="form-control form-control-lg" id="entry_weight_id" name="entry_weight_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_academy')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control datatable-input" id="academy_id" name="academy_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@$competitions as $competition)
                    <option value="{{ $competition->event_id }}">{{ $competition->event->name }}: /{{ $competition->reg_start_date.'-'.$competition->reg_end_date }}/</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_status')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <select class="form-control datatable-input" id="status" name="status" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @forelse(@Config::get("enums.event_registeation_status") as $key => $status)
                    <option value="{{ $key }}">{{ $status }}</option>
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
    function entryFunction(event) {
        $.ajax({
            type: 'POST',
                url: '{!! route('event.entry.age.by.entry') !!}',
                data: {entry_id:  event.val},
                success: function (data) {
                    jsonData = JSON.parse(data);

                    $('#create-event-registration-form input[name=entry_age_id]').select2({
                        placeholder: "-- {{ trans('display.general_select') }} --",
                        data: {results: jsonData, text: function (item) {
                            return item;
                        }},
                        id: 'id',
                        closeOnSelect: true,
                        allowClear: true,
                        formatSelection: function (item) {
                        return item.start_age + '-' + item.end_age;
                        },
                        formatResult: function (item) {
                            return item.start_age + '-' + item.end_age;
                        }
                    });

                    // entry_belt_id
                   
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });

        $.ajax({
            type: 'POST',
                url: '{!! route('event.entry.belt.by.entry') !!}',
                data: {entry_id:  event.val},
                success: function (data) {
                    jsonData = JSON.parse(data);
                    $('#create-event-registration-form input[name=entry_belt_id]').select2({
                        placeholder: "-- {{ trans('display.general_select') }} --",
                        data: {results: jsonData, text: function (item) {
                            return item;
                        }},
                        id: 'id',
                        closeOnSelect: true,
                        allowClear: true,
                        formatSelection: function (item) {
                        return item.name;
                        },
                        formatResult: function (item) {
                            return item.name;
                        }
                    });                   
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });
    }

    function ageFunction(event) {
        $.ajax({
            type: 'POST',
                url: '{!! route('event.entry.weight.by.entry') !!}',
                data: {age_id:  event.val},
                success: function (data) {
                    jsonData = JSON.parse(data);

                    $('#create-event-registration-form input[name=entry_belt_id]').select2({
                        placeholder: "-- {{ trans('display.general_select') }} --",
                        data: {results: jsonData, text: function (item) {
                            return item;
                        }},
                        id: 'id',
                        closeOnSelect: true,
                        allowClear: true,
                        formatSelection: function (item) {
                        return item.start_age;
                        },
                        formatResult: function (item) {
                            return item.start_age;
                        }
                    });
                   
                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });
    }
</script>

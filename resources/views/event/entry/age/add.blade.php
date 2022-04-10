<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/jasny-bootstrap-fileinput/css/jasny-bootstrap-fileinput.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2-bootstrap.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2-custom.css')}}">

<form class="form" method="POST" id="create-event-entry-age-form" action="{{ route('event.entry.age.store') }}">
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_new')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">Төрөл: <span class="text-danger">*</span></label>
            <div class="col-md-6">
                <select class="form-control kt-selectpicker" data-live-search="true" data-size="7" name="entry_id" id="entry_id">
                    <option value="">-- {{ trans('display.general_select') }} --</option>
                    @foreach(@$eventEntries as $entry)
                    <option value="{{ $entry->id }}">{{ $entry->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">Доод нас: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="number" class="form-control" autocomplete="off" name="start_age"  data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">Дээд нас: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="number" class="form-control" autocomplete="off" name="end_age"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">Боломжит нас: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="number" class="form-control" autocomplete="off" name="possible_age" />
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
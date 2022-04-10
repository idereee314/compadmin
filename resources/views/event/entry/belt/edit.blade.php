<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/jasny-bootstrap-fileinput/css/jasny-bootstrap-fileinput.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2-bootstrap.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2-custom.css')}}">

<form class="form" method="POST" id="update-event-entry-belt-form" action="{{ route('event.entry.belt.update', $eventEntryBelt->id) }}">
    <input type="hidden" name="_method" value="put" />
    <input type="hidden" name="entry_id" value="{{$eventEntryBelt->entry_id}}" />
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
                    <option value="{{ $entry->id }}" {{$eventEntryBelt->entry_id == $entry->id ? 'selected' : ''}}>{{ $entry->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_name')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="text" class="form-control" autocomplete="off" name="name" value="{{$eventEntryBelt->name}}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_name_en')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="text" class="form-control" autocomplete="off" name="name_en" value="{{$eventEntryBelt->name_en}}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.possible_belts')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9 col-lg-6">
                <input type="number" class="form-control" value="{{@$eventEntryBelt->possible_belts}}" autocomplete="off" name="possible_belts"/>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
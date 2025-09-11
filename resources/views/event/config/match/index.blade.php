<form action="{{ route('event.config.match.generate', ['event_id' => $eventId]) }}" method="POST" class="form"
    id="event-config-days-entries-form">
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
    <div class="form-group row mt-3">
        <label class="col-md-3 col-form-label text-right">{{ trans('display.general_start_date') }}:<span
                class="text-danger"> *</span></label>
        <div class="col-md-9 col-lg-6">
            <div class="input-group date">
                <input type="text" name="start_date" id="start_date" class="form-control" readonly="readonly"
                    data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}" />
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
        <label class="col-md-3 col-form-label text-right">{{ trans('display.general_end_date') }}:<span
                class="text-danger"> *</span></label>
        <div class="col-md-9 col-lg-6">
            <div class="input-group date">
                <input type="text" name="end_date" id="end_date" class="form-control" readonly="readonly"
                    data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}" />
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
        <label class="col-md-3 col-form-label text-right">{{ trans('display.match_mate') }}: <span
                class="text-danger">*</span></label>
        <div class="col-md-9 col-lg-6">
            <input type="number" class="form-control" name="mate_number" min="1" data-rule-required="true"
                data-msg-required="{{ trans('messages.validation_field_required') }}" />
            <div class="error-here"></div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold"
            data-dismiss="modal">{{ trans('display.general_close') }}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{ trans('display.general_save') }}</button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add focus effect to inputs
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.classList.add('border-primary', 'shadow-sm');
            });
            input.addEventListener('blur', () => {
                input.classList.remove('border-primary', 'shadow-sm');
            });
        });
    });
</script>

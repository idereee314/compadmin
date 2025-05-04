<form action="{{ route('event.config.match.winner', ['match_id' => $matchId]) }}" method="POST" class="form"
    id="event-config-days-entries-form">
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
    <div class="form-group row mt-3">
        <label class="col-md-3 col-form-label text-right">Төрөл: <span class="text-danger">*</span></label>
        <div class="col-md-6">
            <select class="form-control selectpicker" data-live-search="true" name="reg_win_id" id="reg_win_id">
                <option value="">-- {{ trans('display.winning') }} --</option>
                @forelse(@$registered as $entry)
                    <option value="{{ $entry->id }}">{{ $entry->member->fullname }}</option>
                @empty
                @endforelse
            </select>
        </div>
    </div>


    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold"
            data-dismiss="modal">{{ trans('display.general_close') }}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{ trans('display.general_save') }}</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        const registered = @json($registered);
        console.log('Inputs:', registered);
    })
    document.addEventListener('DOMContentLoaded', function() {
        // Add focus effect to inputs
        console.log('DOMContentLoaded event fired');
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

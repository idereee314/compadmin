<form class="form" method="POST" id="update-event-registration-form" action="{{ route('event.registration.update', $eventRegistration->id) }}">
    <input type="hidden" name="_method" value="put" />
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_weight_in')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>    
    <div class="card-body" style="background-color: #FFFFFF; padding: 20px; border-radius: 10px; color: #000000; width: 400px; margin: auto; font-family: Arial, sans-serif;">
    <div style="font-size: 16px; margin-bottom: 10px;">
        <strong>Оролцогч:</strong> {{ $eventRegistration->member->fullname }}
    </div>
    <div style="font-size: 16px; margin-bottom: 20px;">
        <strong>Төрөл:</strong> {{$checkEntry}} / {{$checkBelt}} / {{$checkWeight}}КГ
    </div>
    
    <div style="justify-content: space-between; padding: 15px; background-color: #F8F8F8; border-radius: 8px; margin-bottom: 20px;">
        <div>
            <strong>Сонгосон жингийн ангилал</strong>
            <div style="margin-top: 5px;">
                <span style="background-color: #1E90FF; color: #FFFFFF; padding: 5px 10px; border-radius: 15px; font-size: 14px;">{{$checkWeight}}КГ</span>
            </div>
            <div style="font-size: 12px; color: #999999;">Зөвшөөрөгдсөн хязгаар: - {{$checkWeight}}КГ</div>
        </div>
        <div>
            <strong>Жин оруулах (кг)</strong>
            <input type="number" placeholder="0.00" style="margin-top: 5px; width: 80px; font-size: 16px; text-align: center; border: 1px solid #CCCCCC; border-radius: 5px; padding: 5px;">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_belt')}}: <span class="text-danger">*</span></label>
        <div class="col-md-9 col-lg-7">
            <select class="form-control form-control-input" id="entry_belt_id" name="entry_belt_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}" >
                @forelse(@$configBelts as $belt)
                    <option value="{{ $belt->id }}" {{ $eventRegistration->entry_belt_id == $belt->id ? 'selected' : ''}}>{{ $belt->name }}</option>
                @empty
                @endforelse
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_age')}}: <span class="text-danger">*</span></label>
        <div class="col-md-9 col-lg-7">
            <select class="form-control form-control-input" id="entry_age_id" name="entry_age_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                @forelse(@$configAges as $age)
                <option value="{{ $age->id }}" {{ $eventRegistration->entry_age_id == $age->id ? 'selected' : ''}}>{{ @$age->start_age }} - {{ @$age->end_age }}</option>
                @empty
                @endforelse
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-right">{{trans('display.comp_entry_weight')}}: <span class="text-danger">*</span></label>
        <div class="col-md-9 col-lg-7">
            <select class="form-control form-control-lg" id="entry_weight_id" name="entry_weight_id" value="{{$eventRegistration->entry_weight_id}}" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                @forelse(@$configWeights as $weight)
                <option value="{{ $weight->id }}" {{ $eventRegistration->entry_weight_id == $weight->id ? 'selected' : ''}}>{{ @$weight->weight }}</option>
                @empty
                @endforelse
            </select>
        </div>
    </div>

    <div style="padding: 15px; background-color: #F8F8F8; border-radius: 8px; margin-bottom: 20px;">
        <strong>Status</strong>
        <div style="justify-content: space-around; margin-top: 10px;">
            <button style="background-color: #D3D3D3; border: 1px solid #CCCCCC; padding: 10px 20px; border-radius: 5px; font-size: 16px;">OK</button>
            <button style="background-color: #808080; color: #FFFFFF; border: 1px solid #CCCCCC; padding: 10px 20px; border-radius: 5px; font-size: 16px;">Unknown</button>
            <button style="background-color: #D3D3D3; border: 1px solid #CCCCCC; padding: 10px 20px; border-radius: 5px; font-size: 16px;">DQ</button>
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <label for="comment" style="font-size: 16px; font-weight: bold;">Comment <span style="font-weight: normal; font-size: 14px; color: #999999;">(optional)</span></label>
        <textarea id="comment" placeholder="Comment" style="width: 100%; padding: 10px; border: 1px solid #CCCCCC; border-radius: 5px; font-size: 16px; resize: vertical;"></textarea>
    </div>
</div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
<script>
    function checkWeight() {
        const weight = parseFloat(document.getElementById('weightInput').value) || 0;
        const allowedWeight = 62; // Set your allowed weight limit here

        if (weight > allowedWeight) {
            document.getElementById('status').textContent = 'ХЭТРҮҮЛСЭН';
            document.getElementById('status').style.color = '#FF0000';
        } else {
            document.getElementById('status').textContent = 'ХҮЛЭЭГДЭЖ БАЙНА';
            document.getElementById('status').style.color = '#FF4500';
        }
    }

    function changeStatus(status) {
        document.getElementById('status').textContent = status;
        if (status === 'OK') {
            document.getElementById('status').style.color = '#00FF00';
        } else if (status === 'Unknown') {
            document.getElementById('status').style.color = '#FFA500';
        } else if (status === 'DQ') {
            document.getElementById('status').style.color = '#FF0000';
        }
    }
</script>
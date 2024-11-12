<form class="form" method="POST" id="update-event-registration-form" action="{{ route('event.registration.update', $eventRegistration->id) }}">
    <input type="hidden" name="_method" value="put" />
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_weight_in')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>    
    <div class="card-body" style="background-color: #FFFFFF; padding: 30px; border-radius: 10px; color: #000000;">
        <div class="row">
            <div class="col-md-12 text-center">
                <div style="display: inline-block; margin-bottom: 20px;">
                    @if(@$eventRegistration->member->profile_url xor ((@env('production') && \Storage::disk('s3')->exists($eventRegistration->member->profile_url)) || @env('local')))
                        <div class="text-center w-100">
                            <img src="{{ \Storage::disk('s3')->url($eventRegistration->member->profile_url) }}" 
                                 alt="Athlete Photo" 
                                 style="width: 180px; height: 180px; border: 3px solid #000000; object-fit: cover;">
                        </div>
                    @endif
                </div>
                <div style="font-size: 20px; display: flex; align-items: center; justify-content: center;">
                    <img src="/assets/images/flags/4x3/{{$countries->abbreviation}}.svg" alt="flag" style="width: 40px; margin-right: 10px;">
                    <span style="color: #000000;">{{$countries->name}}</span>
                </div>
                <div>
                    <h1 style="margin: 10px 0; font-size: 36px; font-weight: bold; color: #000000;">{{ $eventRegistration->member->fullname }}</h1>
                    @if(@$eventRegistration->status == "approved")
                        <span class="label label-success label-inline font-weight-lighter mr-2">Баталгаажсан</span>
                    @elseif(@$eventRegistration->status == "created")
                        <span class="label label-warning label-inline font-weight-lighter mr-2">Баталгаажаагүй</span>
                    @elseif(@$eventRegistration->status == "canceled")
                        <span class="label label-danger label-inline font-weight-lighter mr-2">Цуцалсан</span>
                    @endif
                </div>
                <div style="font-size: 18px; margin: 5px 0; color: #000000;">{{$checkEntry}} / {{$checkBelt}} / <span id="athleteWeight">{{$checkWeight}}</span> KG</div>
                <div id="status" style="font-size: 24px; color: #FF4500; margin: 10px 0;">ХҮЛЭЭГДЭЖ БАЙНА</div>
                <div style="font-size: 60px; font-weight: bold; margin-top: 20px; color: #000000;">
                    <input type="number" id="weightInput" placeholder="0" style="width: 100px; font-size: 50px; text-align: center; border: 1px solid #000000; border-radius: 5px;" 
                           oninput="checkWeight()">
                    <span style="font-size: 30px;">KG</span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
<script>
    const maxWeight = parseFloat(document.getElementById('athleteWeight').innerText) || 0;

    function checkWeight() {
        const inputWeight = parseFloat(document.getElementById('weightInput').value);
        const status = document.getElementById('status');

        if (isNaN(inputWeight)) {
            status.innerText = 'ХҮЛЭЭГДЭЖ БАЙНА';
            status.style.color = '#FF4500';
            return;
        }
        if (inputWeight <= maxWeight) {
            status.innerText = 'БАТАЛСАН';
            status.style.color = '#008000';
        } 
        else
        {
            status.innerText = 'ТАТГАЛЗСАН';
            status.style.color = '#FF4500';
        }
    }
</script>

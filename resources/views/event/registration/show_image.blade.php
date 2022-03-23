<form method="POST" id="member-show-image-form" action="javascript:;">
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_image')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
    <div class="card-body m-4">
        <div class="form-group d-flex justify-content-center">
            @if(@$profile_photo)
                <img alt="..." src="{{@Config::get('smart.cloud_image_url').@$profile_photo}}" style="width: 100%">
            @else
                <img alt="..." src="{{@Config::get('smart.cloud_image_url').@$id_photo}}" style="width: 100%">
            @endif
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{ trans('display.general_close') }}</button>
    </div>
</form>
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/jasny-bootstrap-fileinput/css/jasny-bootstrap-fileinput.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2-bootstrap.css')}}">
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/select2-ng/select2-custom.css')}}">

<form method="POST" id="connect-user-form" action="{{route('update.connect.user', $id)}}">
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_connect')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>
    <div class="card-body m-4">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.username')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                @if(@$firstname)
                    <input class="form-control form-control-lg" value="{{@$firstname}}"/>
                @else
                    <input class="form-control form-control-lg" id="user_id" name="user_id" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/> 
                @endif
            </div>
        </div> 
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{ trans('display.general_close') }}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">
            {{@$firstname == null ? trans('display.general_save') : 'Салгах'}}
        </button>
    </div>
</form>
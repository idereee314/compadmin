<div class="modal-header bg-gray-100">
    <h5 class="modal-title" id="exampleModalLabel">{{trans('display.menu_title')}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<div class="card-body m-4">
    <form class="form">
        @foreach ($menus as $key => $menu)     
        <div class="form-group row">
            <label class="col-3 col-form-label"> {{trans("menu.$menu->menu")}}</label>
            <div class="col-9 col-form-label">
                <div class="checkbox-inline">
                    <label class="checkbox checkbox-outline checkbox-success">
                        <input type="checkbox" name="Checkboxe{{$key}}" {{$menu->operation == 'visit' ? 'checked' : ''}}/>
                        <span></span>
                        {{trans('display.general_see')}}
                    </label>
                    <label class="checkbox checkbox-outline checkbox-success">
                        <input type="checkbox" name="Checkboxes{{$key}}" {{$menu->operation == 'editable' ? 'checked' : ''}} />
                        <span></span>
                        {{trans('display.general_edit')}}
                    </label>
                </div>
            </div>
        </div>
        @endforeach
    </form>
</div>

<div class="modal-footer text-right bg-gray-100 border-top-0">
    <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{ trans('display.general_close') }}</button>
</div>
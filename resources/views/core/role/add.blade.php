
<form class="form" method="POST" id="add-role-form" action="{{route('role.store')}}">
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.general_new')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body m-4">
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_name')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" autocomplete="off" name="name"  data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label text-right">{{trans('display.general_code')}}: <span class="text-danger">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" autocomplete="off" name="code"  data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}"/>
                <div class="error-here"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label text-right">{{trans('display.menu_title')}}:</label>
            <div class="col-9 col-form-label">
                <div class="card-body">
                @foreach ($menus as $key => $menu)
                    <div class="form-group">
                        <div class="input-group input-group-solid">
                            @if(empty(@$menu['children']))
                            <input type="text" class="form-control" readonly value="{{ $menu['label'] }}">
                            @else
                            <div class="row">
                                <div class="col-4">
                                    <input type="text" class="form-control" readonly value="{{ $menu['label'] }}">
                                </div>
                                <div class="col-8">
                                @foreach(@$menu['children'] as $key => $child)
                                    <div class="form-group" style="margin: 0">
                                        <div class="input-group input-group-solid">
                                            <input type="text" class="form-control" readonly value="{{ $child['label'] }}">
                                            @if(!empty(@$child['permission']))
                                            <div class="input-group-prepend">
                                                <input type="hidden" name="menus[{{ $key }}]" value=""/>
                                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ trans('display.general_operation') }}</button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="javascript:;">{{ trans('display.general_operation') }}</a>
                                                    @foreach(@$child['permission'] as $childPermission)
                                                    <a class="dropdown-item" href="javascript:;" data-permission="{{ $childPermission }}">{{ @Config::get('enums.permission_opration')[$childPermission] }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                            @endif
                            @if(!empty(@$menu['permission']))
                            <div class="input-group-append">
                                <input type="hidden" name="menus[{{ $key }}]" value=""/>
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ trans('display.general_operation') }}</button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="javascript:;">{{ trans('display.general_operation') }}</a>
                                    @foreach(@$menu['permission'] as $permission)
                                    <a class="dropdown-item" href="javascript:;" data-permission="{{ $permission }}">{{ @Config::get('enums.permission_opration')[$permission] }}</a>
                                    @endforeach
                                </div>
                            </div>                                
                            @endif
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>
<div class="card-body p-0">
    <div class="card card-custom">
        <!--begin::Card header-->
        <div class="card-header card-header-tabs-line nav-tabs-line-3x">
            <!--begin::Toolbar-->
            <div class="card-toolbar">
                <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x" id="config_tabs">
                    @forelse(@$tabs as $tab)
                    <li class="nav-item mr-3">
                        <a href="#{{$tab['number']}}" data-toggle="tab" name="{{$tab['number']}}" class="nav-link {{ @$tab_id == $tab['number'] ? 'active' : '' }}" data-tabid="{{$tab['number']}}"  data-tabcode="{{$tab['code']}}" data-tabname="{{$tab['name']}}">
                            <span class="nav-icon">
                                <i class="fas {{ @$tab['icon'] }}"></i>
                            </span>
                            <span class="nav-text font-size-lg">{{ $tab['title'] }}</span>
                        </a>
                    </li>
                    @empty
                    @endforelse
                </ul>
            </div>
            
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <!--end::Card header-->
        <div class="card-body p-0">
            @if(empty(@$tabs))
            <div class="alert alert-info no-margin">
                {!! trans('messages.warning_no_app_type_tab') !!}
            </div>
            @else
            <div class="tab-pane fade show">
                <div class="tab-content p-10">
                    <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-1">
                        <div class="row">
                            <label class="col-md-3 col-form-label text-right">{{trans('display.event_title')}}: <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-6">
                                <p class="form-control-plaintext text-muted">{{ $eventConfig->event->name }}</p>
                            </div>
                        </div> 
                        <div class="row">
                            <label class="col-md-3 col-form-label text-right">{{trans('display.reg_date')}}<span class="text-danger"> *</span></label>
                            <div class="col-md-9 col-lg-6">
                                <p class="form-control-plaintext text-muted">{{ Carbon\Carbon::parse(@$eventConfig->reg_start_date)->format('Y-m-d H:i:s') }} / {{ Carbon\Carbon::parse(@$eventConfig->reg_end_date)->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-3 col-form-label text-right">{{trans('display.comp_org_type')}}: <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-6">
                                <p class="form-control-plaintext text-muted">{{ $eventConfig->org_types }}</p>
                            </div>
                        </div> 
                        <div class="row">
                            <label class="col-md-3 col-form-label text-right"></label>
                            <div class="col-md-9 col-lg-6">
                                <label class="checkbox">
                                    <input type="checkbox" name="is_active" {{ @$eventConfig->is_active ? 'checked="checked"' : '' }} disabled>
                                    <span></span>&nbsp;
                                    {{ trans('display.general_active') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-2">
                        <!--begin::Table-->
                        <table class="table table-separate table-head-custom dtr-inline">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="min-w-200px text-left">{{trans('display.general_name')}}</th> 
                                    <th class="min-w-100px text-left">{{trans('display.general_name_en')}}</th>
                                    <th class="min-w-125px text-center">{{trans('display.human_gender_code')}}</th>
                                    <th class="min-w-125px text-center">{{trans('display.entrance_fee')}}</th>
                                    <th class="min-w-110px text-center">{{trans('display.general_created_at')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(@$entries as $entry)
                                <tr>
                                    <td class="pl-0 py-4 text-center">{{ ++$loop->index }}</td>
                                    <td class="pl-0">{{$entry->name}}</td>
                                    <td class="text-left">{{$entry->name_en}}</td>
                                    <td class="text-center">{{Config::get("enums.gender_code")[$entry->gender_code]}}</td>
                                    <td class="text-center">{{$entry->entrance_fee}}</td>
                                    <td class="text-center">{{$entry->created_at}}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ trans('display.general_no_record') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="modal-footer text-right bg-gray-100 border-top-0">
    <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
</div>
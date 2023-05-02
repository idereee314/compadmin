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
            <div class="card-toolbar">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
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
                                <select class="form-control select2" id="org_types" name="org_types[]" disabled multiple="multiple" data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                                    <option value="0" text-muted>-- {{ trans('display.general_select') }} --</option>
                                    @forelse(@Config::get('enums.org_type') as $key => $type)
                                    <option value="{{ $key }}" {{ \Illuminate\Support\Str::contains(@$eventConfig->org_types, $key) ? 'selected="selected"' : '' }}>{{ $type }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                <div class="error-here"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label text-right">{{trans('display.general_sport_type')}}: <span class="text-danger">*</span></label>
                            <div class="col-md-9 col-lg-6">
                                <select class="form-control selectpicker" id="sport_id" name="sport_id" disabled data-rule-required="true" data-msg-required="{{ trans('messages.validation_field_required') }}">
                                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                                    @foreach($sports as $type)
                                        <option value="{{ $type->id }}" text-muted {{ $type->id == @$eventConfig->sport_id ? 'selected': '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <div class="error-here"></div>
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
                        <div class="row">
                            <label class="col-md-3 col-form-label text-right"></label>
                            <div class="col-md-9 col-lg-6">
                                <label class="checkbox">
                                    <input type="checkbox" name="is_athlete_limit" id="is_athlete_limit" disabled>
                                    <span></span>&nbsp;
                                    Лимиттэй эсэх
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-3 col-form-label text-right"></label>
                            <div class="col-md-9 col-lg-6">
                                <label class="checkbox">
                                    <input type="checkbox" name="is_team" id="is_team" {{ @$eventConfig->is_team ? 'checked="checked"' : '' }} disabled>
                                    <span></span>&nbsp;
                                    {{ trans('display.general_is_team') }}
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
                    <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-3">
                        <!--begin::Table-->
                        <table class="table table-separate table-head-custom dtr-inline">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="min-w-200px text-left">{{trans('display.general_name')}}</th> 
                                    <th class="min-w-100px text-left">{{trans('display.general_name_en')}}</th>
                                    <th class="min-w-125px text-center">{{trans('display.possible_belts')}}</th>
                                    <th class="min-w-125px text-center">{{trans('display.general_created_at')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(@$configBelsts as $key => $group)
                                <tr class="table-secondary">
                                    <td colspan="6" class="text-primary font-weight-bolder"><i class="mr-5"></i>{{++$loop->index}}. {{ @$entries->where('id', @$key)->first()->fullname }}</td>
                                </tr>
                                @foreach(@$group as $belt)
                                <tr>
                                    <td class="text-center">{{$loop->parent->index+1}}. {{++$loop->index}}</td>
                                    <td>{{$belt->name}}</td>
                                    <td>{{$belt->name_en}}</td>
                                    <td class="text-center">{{$belt->possible_belts}}</td>
                                    <td class="text-center">{{$belt->created_at}}</td>
                                </tr>
                                @endforeach
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ trans('display.general_no_record') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                    <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-4">
                        <!--begin::Table-->
                        <table class="table table-separate table-head-custom dtr-inline">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="min-w-200px text-left">{{trans('display.start_age')}}</th> 
                                    <th class="min-w-100px text-left">{{trans('display.end_age')}}</th>
                                    
                                    <th class="min-w-110px text-center">{{trans('display.general_created_at')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(@$configAges as $key => $group)
                                <tr class="table-secondary">
                                    <td colspan="6" class="text-primary font-weight-bolder"><i class="mr-5"></i>{{++$loop->index}}. {{ @$entries->where('id', @$key)->first()->fullname }}</td>
                                </tr>
                                @foreach($group as $age)
                                <tr>
                                    <td class="text-center">{{$loop->parent->index+1}}. {{++$loop->index}}</td>
                                    <td>{{$age->start_age}}</td>
                                    <td>{{$age->end_age}}</td>

                                    <td class="text-center">{{$age->created_at}}</td>                                
                                </tr>
                                @endforeach
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ trans('display.general_no_record') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                    <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-5">
                        <!--begin::Table-->
                        <table class="table table-separate table-head-custom dtr-inline">
                            <thead>
                                <tr>
                                    <th class="w-75px text-center">#</th>
                                    <th class="min-w-200px text-left">{{trans('display.age_title')}}</th> 
                                    <th class="min-w-100px text-left">{{trans('display.weight')}}</th>
                                    <th class="min-w-100px text-center">{{trans('display.comp_max_entry')}}</th>
                                    <th class="min-w-110px text-center">{{trans('display.general_created_at')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(@$configWeights as $key => $group)
                                <tr class="table-secondary">
                                    <td colspan="6" class="text-primary font-weight-bolder"><i class="mr-5"></i>{{++$loop->index}}. {{ @$entries->where('id', @$key)->first()->fullname }}</td>
                                </tr>
                                    @foreach($group as $keyAge => $age)
                                    <tr class="table-secondary">
                                        <td colspan="6" class="text-primary font-weight-bolder"><i class="mr-5"></i>{{$loop->parent->index+1}}.{{++$loop->index}}. {{ @$configAges[$key]->where('id', $keyAge)->first()->name }}</td>
                                    </tr>
                                        @foreach($age as $weight)
                                        <tr>
                                            <td class="text-center">{{$loop->parent->parent->index+1}}. {{$loop->parent->index+1}}. {{++$loop->index}}</td>
                                            <td>{{$weight->age->name}}</td>
                                            <td>{{$weight->weight}}</td>
                                            <td class="text-center">{{$weight->max_entry}}</td>
                                            <td class="text-center">{{$weight->created_at}}</td>    
                                        </tr>
                                        @endforeach
                                    @endforeach
                                    @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ trans('display.general_no_record') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                    <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-6">
                        <!--begin::Table-->
                        <table class="table table-separate table-head-custom dtr-inline">
                            <thead>
                                <tr>
                                    <th class="w-40px text-center">#</th>
                                    <th class="min-w-200px text-left">Дуусах хугацаа</th> 
                                    <th class="min-w-200px text-left">Төлбөр</th> 
                                    <th class="min-w-110px text-center">{{trans('display.general_created_at')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(@$configEntriesFees as $key => $group)
                                <tr class="table-secondary">
                                    <td colspan="6" class="text-primary font-weight-bolder"><i class="mr-5"></i>{{++$loop->index}}. {{ @$entries->where('id', @$key)->first()->fullname }}</td>
                                </tr>
                                @foreach($group as $fee)
                                <tr>
                                    <td class="text-center">{{++$loop->index}}</td>
                                    <td>{{$fee->end_date}}</td>
                                    <td>{{$fee->entrance_fee}}</td>
                                    <td class="text-center">{{$fee->created_at}}</td>
                                </tr>
                                @endforeach
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">{{ trans('display.general_no_record') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                    <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-7">
                        <!--begin::Table-->
                        <table class="table table-separate table-head-custom dtr-inline">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="min-w-200px text-left">{{trans('display.human_lastname')}}</th>  
                                    <th class="min-w-125px text-left">{{trans('display.human_firstname')}}</th>
                                    <th class="min-w-110px text-center">{{trans('display.general_created_at')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(@$eventUsers as $eventUser)
                                <tr>
                                    <td class="pl-0 py-4 text-center">{{ ++$loop->index }}</td>
                                    <td class="pl-0">{{$eventUser->user->lastname}}</td>
                                    <td class="text-left">{{$eventUser->user->firstname}}</td>
                                    <td class="text-center">{{$eventUser->created_at}}</td>                                    
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

<script  type="text/javascript">

$(document).ready(function() {
    $('#sport_id').selectpicker();
    $('#org_types').select2();

    // if('{{ old('tab_id') }}' != '' || '{{ $tab_id }}' != '') {
    //     $('a[name={{ old('tab_id')? old('tab_id'): $tab_id }}]').trigger('click');
    // };
    // $('a[name=$('input[name=tab_id]').val()? $('input[name=tab_id]').val(): $tab_id]').trigger('click');
    
}).ajaxStart($.blockUI).ajaxStop($.unblockUI);
</script>
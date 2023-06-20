        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <input type="hidden" name="tab_id" id="tab_id" value="{{ isset($tab_id)? $tab_id: 'tab1-1'}}"/>
                    <input type="hidden" name="event_id" id="event_id" value="{{ @$eventConfig->event->id }}"/>
                    <!--begin::Card header-->
                    <div class="card-header card-header-tabs-line nav-tabs-line-3x">
                        <!--begin::Toolbar-->
                        <div class="card-toolbar">
                            <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                                @forelse(@$tabs as $tab)
                                <li class="nav-item mr-3 {{@$tab_id == $tab['number'] ? 'active' : '' }}">
                                    <a href="#{{$tab['number']}}" data-toggle="tab" name="{{$tab['number']}}" class="nav-link app_tab" data-tabid="{{$tab['number']}}"  data-tabcode="{{$tab['code']}}" data-tabname="{{$tab['name']}}">
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
                        <div class="pull-right"></div>
                        <div class="clearfix"></div>
                    </div>
                    <!--end::Card header-->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-1">
                                test1
                            </div>
                            <div class="tab-pane fade in {{@$tab_id == $tab['number'] ? 'active show' : '' }}" id="tab1-2">
                                test2
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
    <!--end::Content-->

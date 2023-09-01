<section class="">
    <!--begin::Details-->
    <div class="d-flex align-items-center flex-wrap mr-2">
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="" class="text-muted">{{ $sport->name}}</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="" class="text-muted">Чансаа</a>
                        </li>
                    <li class="breadcrumb-item text-muted" id="test">
                        <a href="" style="color: black;"><strong>MJJF 2022-2023</strong></a>
                    </li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Details-->   
        </div>
    </div>
</section>               

<section class="deed-select">
    <div class="container">
        <div class="form-group row">
            <!-- <label class="col-form-label text-right col-lg-3 col-sm-12">Live Search</label> -->
            <div class="col-lg-3 col-md-9 col-sm-3">
                <select class="form-control selectpicker" data-size="7" data-live-search="true" data-size="3" data-live-search="true">
                    <option value="0">-- {{ trans('display.general_select') }} --</option>
                    @foreach($eventRankSeason as $season)
                        <option value="{{ $season->id }}"><strong>{{ $season->name }} - {{ Config::get("enums.sport_category")[$season->sport_id] }}</strong></option>
                    @endforeach
                </select>
                <div class="error-here"></div>
            </div>
        </div>
    </div>
</section>



<section class="contents">
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b">
                <input type="hidden" name="tab_id" id="tab_id" value="{{ isset($tab_id)? $tab_id: 'tab1-1'}}"/>

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
                        @forelse($tabs as $tab)
                            <div class="tab-pane fade {{ $tab_id == $tab['number'] ? 'active show' : '' }}" id="{{ $tab['number'] }}">
                                @include("reference.ranking.{$tab['name']}")
                            </div>
                        @empty
                            No tab content available.
                        @endforelse
                    </div>                                
                </div>                            
            </div>
            <!--end::Card--> 
        </div>   
    </div>
</section>


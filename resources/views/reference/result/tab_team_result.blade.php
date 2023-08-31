<!--begin::Row-->
<div class="row">
    <div class="col-xl-12">
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title text-center">
                    <h3 class="card-label"><strong>{{trans('display.best_academy')}}</strong></h3>
                </div>
            </div>
            <div class="card-body">
            @if(count($eventToplist) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-head-custom" id="event-team-registration-datatable">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">{{trans('display.comp_academy_name')}}</th>
                            <th class="text-center"><i class="fas fa-medal icon-2x gold-medal-icon"></i> АЛТ</th>
                            <th class="text-center"><i class="fas fa-medal icon-2x silver-medal-icon"></i> МӨНГӨ</th>
                            <th class="text-center"><i class="fas fa-medal icon-2x bronze-medal-icon"></i> ХҮРЭЛ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventToplist as $results)
                            <tr>
                                <td class="text-center border-right">{{ ++$loop->index }}</td>
                                <td class="min-w-200px text-center border-right"><strong>{{ $results->name }}</strong></td>
                                <td class="text-center border-right"><strong>{{ $results->gold }}</strong></td>
                                <td class="text-center border-right"><strong>{{ $results->silver }}</strong></td>
                                <td class="text-center border-right"><strong>{{ $results->bronze }}</strong></td>
                            </tr>
                        @endforeach 
                    </table>
                    </tbody>                                        
                </div>
            @else
                <tr>
                    <td colspan="12" class="text-center"><strong>{{ trans('messages.empty_toplist') }}</strong></td>
                </tr>
            @endif
            </div>
        </div>
        <!--end::Card-->                            
    </div>
</div>
<!--end::Row-->
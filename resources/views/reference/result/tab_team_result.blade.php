@php
    $toplists = [];

    if ($resultType == 1) {
        $toplists[] = [
            'title' => trans('display.best_academy'),
            'list' => $eventToplist,
            'withPoint' => false,
            'id' => 'toplist'
        ];
    }   
    elseif ($resultType == 2) {
        $toplists[] = [
            'title' => trans('display.best_academy'),
            'list' => $eventToplistPoint,
            'withPoint' => true,
            'id' => 'toplist_point'
        ];
    }   
    elseif ($resultType == 3) {
        $toplists[] = [
            'title' => trans('display.best_academy'),
            'list' => $eventToplist,
            'withPoint' => false,
            'id' => 'toplist_general'
        ];
        $toplists[] = [
            'title' => trans('display.best_academy') . ' - Эрэгтэй',
            'list' => $GenderResultMale,
            'withPoint' => false,
            'id' => 'toplist_male'
        ];
        $toplists[] = [
            'title' => trans('display.best_academy') . ' - Эмэгтэй',
            'list' => $GenderResultFemale,
            'withPoint' => false,
            'id' => 'toplist_female'
        ];
    }   
    elseif ($resultType == 4) {
        $toplists[] = [
            'title' => trans('display.best_academy'),
            'list' => $eventToplistPoint,
            'withPoint' => true,
            'id' => 'toplist_general'
        ];
        $toplists[] = [
            'title' => trans('display.best_academy') . ' - Эрэгтэй',
            'list' => $GenderResultPointMale,
            'withPoint' => true,
            'id' => 'toplist_male'
        ];
        $toplists[] = [
            'title' => trans('display.best_academy') . ' - Эмэгтэй',
            'list' => $GenderResultPointFemale,
            'withPoint' => true,
            'id' => 'toplist_female'
        ];
    }
    
@endphp

@foreach($toplists as $toplist)
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom gutter-b" id="{{ $toplist['id'] }}">
                <div class="card-header">
                    <div class="card-title text-center">
                        <h3 class="card-label"><strong>{{ $toplist['title'] }}</strong></h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="#" class="btn btn-icon btn-circle btn-sm btn-light-primary mr-1" data-card-tool="toggle">
                            <i class="ki ki-arrow-down icon-nm"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($toplist['list']) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-head-custom">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">{{ trans('display.comp_academy_name') }}</th>
                                        <th class="text-center"><i class="la la-medal icon-2x gold-medal-icon"></i></th>
                                        <th class="text-center"><i class="la la-medal icon-2x silver-medal-icon"></i></th>
                                        <th class="text-center"><i class="la la-medal icon-2x bronze-medal-icon"></i></th>
                                        @if($toplist['withPoint'])
                                            <th class="text-center">НИЙТ ОНОО</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($toplist['list'] as $result)
                                        <tr>
                                            <td class="text-center border-right">{{ $loop->iteration }}</td>
                                            <td class="min-w-200px text-center border-right"><strong>{{ $result->name }}</strong></td>
                                            <td class="text-center border-right"><strong>{{ $result->gold }}</strong></td>
                                            <td class="text-center border-right"><strong>{{ $result->silver }}</strong></td>
                                            <td class="text-center border-right"><strong>{{ $result->bronze }}</strong></td>
                                            @if($toplist['withPoint'])
                                                <td class="text-center border-right"><strong>{{ $result->total_point }}</strong></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center"><strong>{{ trans('messages.empty_toplist') }}</strong></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach

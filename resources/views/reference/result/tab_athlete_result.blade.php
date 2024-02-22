<!--begin::Row-->
<div class="row">
    <div class="col-xl-12">
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label"><strong>Нийт медаль</strong></h3>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-head-custom">
                        <thead>
                            <tr>
                                <th class="text-center"><i class="fas fa-medal icon-2x gold-medal-icon"></i> АЛТ</th>
                                <th class="text-center"><i class="fas fa-medal icon-2x silver-medal-icon"></i> МӨНГӨ</th>
                                <th class="text-center"><i class="fas fa-medal icon-2x bronze-medal-icon"></i> ХҮРЭЛ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventAllMedal as $medals)
                            <tr>
                                <td class="text-center border-right"><strong>{{ $medals->gold }}</strong></td>
                                <td class="text-center border-right"><strong>{{ $medals->silver }}</strong></td>
                                <td class="text-center border-right"><strong>{{ $medals->bronze }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--end::Card-->
        @foreach(collect($eventResult)->groupBy('gender_code') as $genderCode => $genderResults)
            <div class="col-xl-12">
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">{{Config::get("enums.gender_code")[$genderCode]}}</h3>
                        </div>
                    </div>
                    
                    <div class="card-body">
                    {{dd($genderResults);}}
                        @foreach($genderResults->groupBy('category_name') as $categoryName => $categoryResults)
                            @foreach($categoryResults->groupBy(function($item) { return $item->start_age . '-' . $item->end_age; }) as $age => $ages)
                                @php
                                    $ageArray = explode('-', $age);
                                    $startage = $ageArray[0];
                                    $end_age = $ageArray[1];
                                @endphp
                                @foreach($ages->groupBy('bus') as $bus => $belts)
                                    @foreach($belts->groupBy('weight') as $weight => $weights)
                                        <div class="col-xl-6">
                                            <div class="card card-custom gutter-b">
                                                <div class="card-header">
                                                    <div class="card-title text-center">
                                                        <h3 class="card-label"><strong>{{ $categoryName }} | {{ $genderCode }} | ({{$startage}}-{{$end_age}}) | {{ $bus }} | {{ $weight }}</strong></h3>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-bordered table-head-custom">
                                                            <tbody>
                                                                @foreach($weights as $result)
                                                                <tr>
                                                                    @if($result->place_number > 3)
                                                                    <td class="text-center border-right"><strong>{{ $result->place_number }}</strong></td>
                                                                    @else
                                                                    <td class="text-center border-right"><strong><i class="{{ Config::get("enums.event_award")[@$result->place_number] }}"></i></strong></td>
                                                                    @endif
                                                                    <td class="text-center border-right">
                                                                        <div class="d-flex align-items-center">
                                                                            @if(@$result->profile_url xor ((@env('production') && \Storage::disk('s3')->exists($result->profile_url)) || @env('local')))
                                                                            <a href="javascript:;" class="show-image" data-id="{{$result->memberid}}" data-type="profile">
                                                                                <div class="symbol symbol-100 flex-shrink-0 rounded-circle">
                                                                                    <img src="{{\Storage::disk('s3')->url($result->profile_url)}}" alt="Profile" style="width: 60px; height: 60px;">
                                                                                </div>
                                                                            </a>
                                                                            @endif
                                                                            <div class="ml-3">
                                                                                <span class="text-dark-75 line-height-sm d-block pb-3" style="white-space: nowrap;"><strong>{{$result->fullname}}</strong></span>
                                                                                <span class="text-dark-75 line-height-sm d-block pb-2">{{ $result->academy_name }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<!--end::Row-->

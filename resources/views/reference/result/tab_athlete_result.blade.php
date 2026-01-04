<!--begin::Нийт медаль-->
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-label"><strong>Нийт медаль</strong></h3>
    </div>
    <div class="card-body">
        <div class="table-responsive d-none d-md-block">
            <table class="table table-bordered text-center">
                <thead class="thead-light">
                    <tr>
                        <th><i class="la la-medal gold-medal-icon"></i> АЛТ</th>
                        <th><i class="la la-medal silver-medal-icon"></i> МӨНГӨ</th>
                        <th><i class="la la-medal bronze-medal-icon"></i> ХҮРЭЛ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($getToplistByGoldMedalFromEvent as $medals)
                        <tr>
                            <td><strong>{{ $medals->gold }}</strong></td>
                            <td><strong>{{ $medals->silver }}</strong></td>
                            <td><strong>{{ $medals->bronze }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-block d-md-none">
            @foreach($getToplistByGoldMedalFromEvent as $medals)
                <div class="card mb-3 border shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="la la-medal gold-medal-icon"></i> <strong>Алт:</strong></span>
                            <span>{{ $medals->gold }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="la la-medal silver-medal-icon"></i> <strong>Мөнгө:</strong></span>
                            <span>{{ $medals->silver }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="la la-medal bronze-medal-icon"></i> <strong>Хүрэл:</strong></span>
                            <span>{{ $medals->bronze }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!--end::Нийт медаль-->

<!--begin::Category бүрд нарийн мэдээлэл-->
@foreach(collect($eventResult)->groupBy('category_id') as $categoryId => $categoryGroup)
    @php $categoryName = $categoryGroup->first()->category_name; @endphp
    @foreach($categoryGroup->groupBy('gender_code') as $genderCode => $genderGroup)
        <div class="mb-8">
            <div class="py-3 px-4 rounded bg-light-primary border-left border-primary mb-5 shadow-sm">
                <h4 class="mb-0 text-primary font-weight-bold">
                    {{ $categoryName }} - {{ config('enums.gender_code')[$genderCode] }}
                </h4>
            </div>

            @foreach($genderGroup->groupBy('ageid') as $ageid => $ageGroup)
                @php
                    $ageItem = $ageGroup->first();
                    $startAge = $ageItem->start_age;
                    $endAge = $ageItem->end_age ?? '∞';
                @endphp
                @foreach($ageGroup->groupBy('belt_id') as $beltId => $beltGroup)
                    @php $beltName = $beltGroup->first()->bus; @endphp
                    @foreach($beltGroup->groupBy('weight_id') as $weightId => $athletes)
                        @php $weight = $athletes->first()->weight; @endphp
                        <div class="mb-5">
                            <div class="bg-light rounded p-3 px-4 mb-3 shadow-sm">
                                <h6 class="mb-0 font-weight-bold text-dark">
                                    {{ $categoryName }} | {{ config('enums.gender_code')[$genderCode] }} | 
                                    ({{ $startAge }} - {{ $endAge }}) | {{ $beltName }} | {{ $weight }}
                                </h6>
                            </div>

                            <!-- Athletes with image -->
                            <div class="bg-white rounded shadow-sm">
                                @foreach($athletes as $result)
                                    <div class="d-flex align-items-center justify-content-between border-bottom px-4 py-3 hover-bg-light">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-4">
                                                @if($result->place_number > 3)
                                                    <span class="font-weight-bold">{{ $result->place_number }}</span>
                                                @else
                                                    <i class="{{ config('enums.event_award')[$result->place_number] }} icon-lg"></i>
                                                @endif
                                            </div>
                                            <div class="d-flex align-items-center">
                                                @if($result->profile_url && ((env('production') && \Storage::disk('s3')->exists($result->profile_url)) || env('local')))
                                                    <a href="javascript:;" class="show-image" data-id="{{ $result->memberid }}" data-type="profile">
                                                        <div class="symbol symbol-100 flex-shrink-0 rounded-circle overflow-hidden">
                                                            <img src="{{ \Storage::disk('s3')->url($result->profile_url) }}" alt="Profile" style="width: 60px; height: 60px;">
                                                        </div>
                                                    </a>
                                                @endif
                                                <div class="ml-3">
                                                    <span class="text-dark-75 font-weight-bold d-block" style="white-space: nowrap;">
                                                        {{ $result->fullname }}
                                                    </span>
                                                    <span class="text-muted small">{{ $result->academy_name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- End: Athletes -->
                        </div>
                    @endforeach
                @endforeach
            @endforeach
        </div>
    @endforeach
@endforeach
<!--end::Category бүрд нарийн мэдээлэл-->

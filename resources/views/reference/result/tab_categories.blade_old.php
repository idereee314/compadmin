<div class="table-responsive">
    <table class="table table-hover table-bordered table-head-custom" id="categoriesTable" style="width: 100%">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="55%" class="text-center">{{ trans('display.general_categories_name') }}</th>
                <th width="10%" class="text-center">Тамирчдын тоо</th>
                <th width="10%" class="text-center">Дууссан эсэх</th>
                <th width="10%" class="text-center">Медаль гардуулсан</th>
                <th width="10%" class="text-center">{{ trans('display.general_manage') }}</th>
            </tr>
        </thead>
        <tbody>
            @php $rowIndex = 1; @endphp
            @foreach(collect($eventResult)->groupBy('category_id') as $categoryId => $categoryResults)
                @php $categoryName = $categoryResults->first()->category_name; @endphp
                @foreach($categoryResults->groupBy('ageid') as $ageid => $ages)
                    @php
                        $ageItem = $ages->first();
                        $startage = $ageItem->start_age;
                        $end_age = $ageItem->end_age ?? '∞';
                    @endphp
                    @foreach($ages->groupBy('belt_id') as $beltId => $belts)
                        @php $belt = $belts->first()->bus; @endphp
                        @foreach($belts->groupBy('weight_id') as $weightId => $weights)
                            @php $weight = $weights->first()->weight; @endphp
                            @foreach($weights->groupBy('gender_code') as $genderCode => $genders)
                                @php
                                    $first = $genders->first();
                                    $athleteCount = count($genders);

                                    // Дууссан эсэх (place_number != null)
                                    $isFinished = $genders->firstWhere('place_number', '!=', null) !== null;

                                    // Медаль гардуулсан эсэх
                                    $medalGiven = $first->medal_given ?? false;

                                    // Дууссан бол цайвар ногоон мөр
                                    $rowStyle = $isFinished ? 'style=background-color:#d4edda' : '';
                                @endphp
                                <tr {!! $rowStyle !!}>
                                    <td class="text-center border-right">{{ $rowIndex++ }}</td>
                                    <td class="text-center border-right">
                                        <strong>
                                            {{ $categoryName }} |
                                            {{ config('enums.gender_code')[$genderCode] }} |
                                            ({{ $startage }}-{{ $end_age }}) |
                                            {{ $belt }} |
                                            {{ $weight }}
                                        </strong>
                                    </td>
                                    <td class="text-center border-right">
                                        <span class="badge badge-info">{{ $athleteCount }}</span>
                                    </td>
                                    <td class="text-center border-right">
                                        <span class="badge {{ $isFinished ? 'badge-success' : 'badge-danger' }}">
                                            {{ $isFinished ? '✓' : '✗' }}
                                        </span>
                                    </td>
                                    <td class="text-center border-right">
                                        <span class="badge {{ $medalGiven ? 'badge-success' : 'badge-danger' }}">
                                            {{ $medalGiven ? '✓' : '✗' }}
                                        </span>
                                    </td>
                                    <td class="text-center border-right">
                                        <a href="#" class="btn btn-sm btn-outline-primary medal">
                                            {{ trans('display.general_edit') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>

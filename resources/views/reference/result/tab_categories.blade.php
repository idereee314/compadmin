<div class="table-responsive">
    <table class="table table-hover table-bordered table-head-custom" id="categoriesTable" style="width: 100%">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="55%" class="text-center">{{ trans('display.general_categories_name') }}</th>
                <th width="10%" class="text-center">Оролцож буй тамирчдын тоо</th>
                <th width="10%" class="text-center">Дууссан эсэх</th>
                <th width="10%" class="text-center">Медаль гардуулсан эсэх</th>
                <th width="10%" class="text-center">{{ trans('display.general_manage') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach(collect($eventResult)->groupBy('category_name') as $categoryName => $categoryResults)
                @foreach(collect($categoryResults)->groupBy(function($item) {
                    return $item->start_age . '-' . $item->end_age;
                }) as $age => $ages)
                    @php
                        $ageArray = explode('-', $age);
                        $startage = $ageArray[0];
                        $end_age = $ageArray[1];
                    @endphp
                    @foreach(collect($ages)->groupBy('bus') as $bus => $belts)
                        @foreach(collect($belts)->groupBy('weight') as $weight => $weights)
                            @foreach(collect($weights)->groupBy('gender_code') as $genderCode => $genders)
                                <tr>
                                    <td class="text-center border-right">{{ ++$loop->index }}</td>
                                    <td class="text-center border-right">
                                        <strong>
                                            {{ $categoryName }} | {{ Config::get("enums.gender_code")[$genderCode] }} | ({{ $startage }}-{{ $end_age }}) | {{ $bus }} | {{ $weight }}
                                        </strong>
                                    </td>
                                    <td class="text-center border-right"><strong></strong></td>
                                    <td class="text-center border-right"><strong><input type="checkbox" name="checkboxes[]"></strong></td>
                                    <td class="text-center border-right"><strong></strong></td>
                                    <td class="text-center border-right"><strong></strong></td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
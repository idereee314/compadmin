<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title"><strong>Жингүүдийн медалийн байдал</strong></h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center table-responsive-md">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Жингийн мэдээлэл</th>
                        <th>Медаль авсан тамирчид</th>
                        <th>Медаль гардуулсан</th>
                        <th>Тэмцээн дууссан</th>
                    </tr>
                </thead>
                <tbody>
                    @php $rowIndex = 1; @endphp
                    @foreach($registeredWeights as $regWeight)
                        @php
                            $weightId = $regWeight->weight_id;
                            $beltId = $regWeight->belt_id;

                            $categoryName = $regWeight->category_name;
                            $gender = config('enums.gender_code')[$regWeight->gender_code] ?? '-';
                            $age = '(' . ($regWeight->start_age ?? '-') . '-' . ($regWeight->end_age ?? '-') . ')';
                            $beltName = $regWeight->belt_name ?? '-';
                            $weight = $regWeight->weight ?? '-';

                            // belt_id, weight_id, start_age болон end_age ашиглаж ялгана
                            $weightGroup = collect($eventResult)->filter(function ($item) use ($weightId, $beltId, $regWeight) {
                                return $item->weight_id == $weightId &&
                                       $item->belt_id == $beltId &&
                                       $item->start_age == $regWeight->start_age &&
                                       $item->end_age == $regWeight->end_age;
                            });

                            $hasAward = $weightGroup->isNotEmpty();
                            $medalGiven = $weightGroup->first()->medal_given ?? false;
                            $isFinished = $weightGroup->contains(fn($r) => !is_null($r->place_number));

                            $awardedAthletes = $weightGroup->filter(fn($athlete) =>
                                in_array($athlete->place_number, [1, 2, 3])
                            )->sortBy('place_number')->values();
                        @endphp

                        <tr>
                            <td>{{ $rowIndex++ }}</td>

                            {{-- Жингийн мэдээлэл --}}
                            <td class="text-left">
                                <div class="p-2 border rounded bg-light">
                                    <div style="font-weight: bold;">{{ $categoryName }} | {{ $gender }}</div>
                                    <div style="font-size: 13px; color: #555;">Нас: {{ $age }}</div>
                                    <div style="font-size: 13px; color: #555;">Бүс: {{ $beltName }}</div>
                                    <div style="font-size: 13px; color: #555;">Жин: {{ $weight }}</div>
                                </div>
                            </td>

                            {{-- Медаль авсан тамирчид --}}
                            <td class="text-left">
                                @if($awardedAthletes->isNotEmpty())
                                    <ol class="pl-3 mb-0">
                                        @foreach($awardedAthletes as $athlete)
                                            <li>
                                                {{ $athlete->fullname }}
                                                @if($athlete->academy_name)
                                                    ({{ $athlete->academy_name }})
                                                @endif
                                            </li>
                                        @endforeach
                                    </ol>
                                @else
                                    <span class="text-muted">Медаль авсан тамирчин байхгүй</span>
                                @endif
                            </td>

                            {{-- Медаль гардуулсан --}}
                            <td>
                                <span class="badge {{ $medalGiven ? 'badge-success' : 'badge-danger' }} px-3 py-1 rounded-pill">
                                    {{ $medalGiven ? '✓ Өгсөн' : '✗ Өгөөгүй' }}
                                </span>
                            </td>

                            {{-- Тэмцээн дууссан --}}
                            <td>
                                <span class="badge {{ $isFinished ? 'badge-primary' : 'badge-secondary' }} px-3 py-1 rounded-pill">
                                    {{ $isFinished ? '✓ Дууссан' : '✗ Дуусаагүй' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

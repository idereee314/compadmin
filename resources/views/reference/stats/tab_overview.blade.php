<div class="row">
    @php
        $cardSections = [
            [
                'title' => 'Нийт бүртгэл',
                'stats' => $eventRegistrationStatusStats,
                'headers' => [
                    trans('display.general_status'),
                    trans('display.general_athlete_count')
                ],
                'rows' => function($stats) {
                    return [
                        Config::get("enums.event_registration_status_for_stats")[@$stats->status],
                        $stats->status_count
                    ];
                }
            ],
            [
                'title' => trans('display.comp_country_name') . ' [Баталгаажсан]',
                'stats' => $eventRegistrationCountryStats,
                'headers' => [
                    trans('display.comp_country_name'),
                    trans('display.general_athlete_count')
                ],
                'rows' => function($stats) {
                    return [
                        $stats->name . ' - ' . strtoupper($stats->abbreviation),
                        $stats->count_country
                    ];
                }
            ],
            [
                'title' => trans('display.comp_country_name') . ' [Бүгд]',
                'stats' => $eventRegistrationCountryAllStats,
                'headers' => [
                    trans('display.comp_country_name'),
                    trans('display.general_athlete_count')
                ],
                'rows' => function($stats) {
                    return [
                        $stats->name . ' - ' . strtoupper($stats->abbreviation),
                        $stats->count_country
                    ];
                }
            ],
            [
                'title' => 'Байгууллага [Баталгаажсан]',
                'stats' => $eventRegistrationOrgTypeStats,
                'headers' => [
                    'Байгууллага',
                    trans('display.general_org_count')
                ],
                'rows' => function($stats) {
                    return [
                        Config::get("enums.org_type")[@$stats->org_type],
                        $stats->org_count
                    ];
                }
            ],
            [
                'title' => 'Байгууллага [Бүгд]',
                'stats' => $eventRegistrationOrgTypeStats,
                'headers' => [
                    'Байгууллага',
                    trans('display.general_org_count')
                ],
                'rows' => function($stats) {
                    return [
                        Config::get("enums.org_type")[@$stats->org_type],
                        $stats->org_count
                    ];
                }
            ],
            [
                'title' => 'Хүйс [Баталгаажсан]',
                'stats' => $eventRegistrationGenderStats,
                'headers' => [
                    'Хүйс',
                    trans('display.general_athlete_count')
                ],
                'rows' => function($stats) {
                    return [
                        Config::get("enums.gender_code")[@$stats->gender_code],
                        $stats->gender_count
                    ];
                }
            ],
            [
                'title' => 'Хүйс [Бүгд]',
                'stats' => $eventRegistrationGenderAllStats,
                'headers' => [
                    'Хүйс',
                    trans('display.general_athlete_count')
                ],
                'rows' => function($stats) {
                    return [
                        Config::get("enums.gender_code")[@$stats->gender_code],
                        $stats->gender_count
                    ];
                }
            ],
            [
                'title' => 'Тэмцээний ангилал [Баталгаажсан]',
                'stats' => $eventRegistrationEntriesStats,
                'headers' => [
                    '#',
                    'Тэмцээнд оролцох төрлүүд',
                    'Хүйс',
                    trans('display.general_athlete_count')
                ],
                'rows' => function($stats) use (&$loop) {
                    return [
                        $loop->index + 1,
                        $stats->name,
                        Config::get("enums.gender_code_for_stats")[@$stats->gender_code],
                        $stats->entry_count
                    ];
                }
            ],
            [
                'title' => 'Тэмцээний ангилал [Бүгд]',
                'stats' => $eventRegistrationEntriesAllStats,
                'headers' => [
                    '#',
                    'Тэмцээнд оролцох төрлүүд',
                    'Хүйс',
                    trans('display.general_athlete_count')
                ],
                'rows' => function($stats) use (&$loop) {
                    return [
                        $loop->index + 1,
                        $stats->name,
                        Config::get("enums.gender_code_for_stats")[@$stats->gender_code],
                        $stats->entry_count
                    ];
                }
            ],
            [
                'title' => 'Тэмцээнд бүртгүүлсэн академи [Баталгаажсан]',
                'stats' => $eventRegistrationAcademyStats,
                'headers' => [
                    '#',
                    trans('display.comp_academy_name'),
                    trans('display.general_athlete_count')
                ],
                'rows' => function($stats) use (&$loop) {
                    return [
                        $loop->index + 1,
                        $stats->name,
                        $stats->academy_count
                    ];
                }
            ],
            [
                'title' => 'Тэмцээнд бүртгүүлсэн академи [Бүгд]',
                'stats' => $eventRegistrationAllAcademyStats,
                'headers' => [
                    '#',
                    trans('display.comp_academy_name'),
                    trans('display.general_athlete_count')
                ],
                'rows' => function($stats) use (&$loop) {
                    return [
                        $loop->index + 1,
                        $stats->name,
                        $stats->academy_count
                    ];
                }
            ]
        ];
    @endphp

    @foreach($cardSections as $section)
        <div class="col-xl-4">
            <!--begin::Card-->
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label"><strong>{{ $section['title'] }}</strong></h3>
                    </div>
                </div>
                <div class="card-body">
                @if(count($section['stats']) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-head-custom">
                            <thead>
                                <tr>
                                    @foreach($section['headers'] as $header)
                                        <th class="text-center">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($section['stats'] as $stats)
                                    <tr>
                                        @foreach($section['rows']($stats) as $cell)
                                            <td class="text-center border-right"><strong>{{ $cell }}</strong></td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center"><strong>{{ trans('display.general_no_athlete') }}</strong></div>
                @endif
                </div>
            </div>
            <!--end::Card-->
        </div>
    @endforeach
</div>

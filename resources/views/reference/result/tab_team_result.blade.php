@php
    $toplists = [];

    if ($resultType == 1) {
        $toplists[] = [
            'title' => trans('display.best_academy'),
            'list' => $eventToplist,
            'withPoint' => false,
            'id' => 'toplist'
        ];
    } elseif ($resultType == 2) {
        $toplists[] = [
            'title' => trans('display.best_academy'),
            'list' => $eventToplistPoint,
            'withPoint' => true,
            'id' => 'toplist_point'
        ];
    } elseif ($resultType == 3) {
        $toplists[] = [
            'title' => trans('display.best_academy'),
            'list' => $eventToplist,
            'withPoint' => false,
            'id' => 'toplist_general'
        ];
        $toplists[] = [
            'title' => trans('display.best_academy') . ' - ' . config('enums.gender_code')['1'],
            'list' => $genderResultMale,
            'withPoint' => false,
            'id' => 'toplist_male'
        ];
        $toplists[] = [
            'title' => trans('display.best_academy') . ' - ' . config('enums.gender_code')['2'],
            'list' => $genderResultFemale,
            'withPoint' => false,
            'id' => 'toplist_female'
        ];
    } elseif ($resultType == 4) {
        $toplists[] = [
            'title' => trans('display.best_academy'),
            'list' => $eventToplistPoint,
            'withPoint' => true,
            'id' => 'toplist_general'
        ];
        $toplists[] = [
            'title' => trans('display.best_academy') . ' - ' . config('enums.gender_code')['1'],
            'list' => $genderResultPointMale,
            'withPoint' => true,
            'id' => 'toplist_male'
        ];
        $toplists[] = [
            'title' => trans('display.best_academy') . ' - ' . config('enums.gender_code')['2'],
            'list' => $genderResultPointFemale,
            'withPoint' => true,
            'id' => 'toplist_female'
        ];
    } elseif ($resultType == 5) {
        $toplists[] = [
            'title' => trans('display.best_academy') . ' (Медаль + Оноо + Оролцогч)',
            'list' => $eventToplistWithAthleteCount,
            'withPoint' => true,
            'withAthleteCount' => true,
            'id' => 'toplist_point_athlete'
        ];
    }
@endphp
@foreach($toplists as $toplist)
    <div class="row mb-5">
        <div class="col-xl-12">
            <div class="card card-custom gutter-b" id="{{ $toplist['id'] }}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title text-center">
                        <h3 class="card-label"><strong>{{ $toplist['title'] }}</strong></h3>
                    </div>
                    <div class="card-toolbar">
                        <button class="btn btn-sm btn-light-primary" onclick="printToplist('{{ $toplist['id'] }}', '{{ $toplist['title'] }}', '{{ $event->name }}')">
                            <i class="la la-print"></i> {{ trans('display.general_print') }}
                        </button>
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
                                        <th class="text-center">🥇</th>
                                        <th class="text-center">🥈</th>
                                        <th class="text-center">🥉</th>
                                        @if($toplist['withAthleteCount'] ?? false)
                                            <th class="text-center">👥 {{ trans('display.general_total_athlete') }}</th>
                                        @endif
                                        @if($toplist['withPoint'])
                                            <th class="text-center">{{ trans('display.general_total_score') }}</th>
                                            <th class="text-center">📌</th> {{-- Popover тайлбар товч --}}
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($toplist['list'] as $result)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $result->name }}</td>
                                            <td class="text-center">{{ $result->gold }}</td>
                                            <td class="text-center">{{ $result->silver }}</td>
                                            <td class="text-center">{{ $result->bronze }}</td>
                                            @if($toplist['withAthleteCount'] ?? false)
                                                <td class="text-center">{{ $result->total_athletes }}</td>
                                            @endif
                                            @if($toplist['withPoint'])
                                                <td class="text-center">{{ $result->total_point }}</td>
                                                <td class="text-center">
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-light-info"
                                                        data-toggle="popover"
                                                        data-html="true"
                                                        data-placement="top"
                                                        title="{{trans('display.general_desc_score')}}"
                                                        data-content=""
                                                        onclick="generatePopover(this, {
                                                            name: '{{ $result->name }}',
                                                            gold: {{ $result->gold }},
                                                            silver: {{ $result->silver }},
                                                            bronze: {{ $result->bronze }},
                                                            total_athletes: {{ $result->total_athletes ?? 0 }},
                                                            point_config: pointConfig
                                                        })">
                                                        {{trans('display.general_detail')}}
                                                    </button>
                                                </td>
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
<script>
    const pointConfig = @json($pointConfig);
    const withAthleteCountMap = {};

    withAthleteCountMap["{{ $toplist['id'] }}"] = {{ $toplist['withAthleteCount'] ?? 'false' }};

    function printToplist(id, title, eventName) {
    const card = document.getElementById(id);
    const originalTable = card.querySelector('table');
    const table = originalTable.cloneNode(true);

    // 📌 баганын индексийг олно
    const headerRow = table.querySelector('thead tr');
    const detailColIndex = Array.from(headerRow.children).findIndex(th => th.textContent.includes("📌"));

    // Хэрвээ 📌 багана байвал түүнийг header болон body-с устгана
    if (detailColIndex !== -1) {
        headerRow.deleteCell(detailColIndex);
        table.querySelectorAll('tbody tr').forEach(row => {
            row.deleteCell(detailColIndex);
        });
    }

    const logoUrl = '{{ url("assets/images/logo/uniq_logo_with_spaceX.png") }}';
    const today = new Date().toLocaleDateString('mn-MN');

    const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>${eventName}</title>
                    <style>
                        body {
                            font-family: DejaVu Sans, sans-serif;
                            padding: 30px;
                        }
                        .logo {
                            text-align: center;
                            margin-bottom: 10px;
                        }
                        h2 {
                            text-align: center;
                            margin: 0;
                            font-size: 20px;
                        }
                        .subtitle {
                            text-align: center;
                            font-size: 14px;
                            color: #666;
                            margin-bottom: 15px;
                        }
                        .date {
                            text-align: left;
                            font-size: 13px;
                            margin-bottom: 10px;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 10px;
                        }
                        th {
                            background: #f5f5f5;
                            font-weight: bold;
                        }
                        th, td {
                            border: 1px solid #000;
                            padding: 8px;
                            text-align: center;
                            font-size: 13px;
                        }
                        .signature-block {
                            margin-top: 60px;
                            display: flex;
                            justify-content: space-between;
                        }
                        .signature {
                            width: 40%;
                            border-top: 1px solid #000;
                            text-align: center;
                            padding-top: 5px;
                            font-size: 13px;
                        }
                    </style>
                </head>
                <body>
                    <div class="logo">
                        <img src="${logoUrl}" alt="Logo" width="80">
                    </div>

                    <h2>${eventName}</h2>
                    <div class="subtitle">${title}</div>
                    <div class="date"><strong>Огноо:</strong> ${today}</div>

                    ${table.outerHTML}

                    <div class="signature-block">
                        <div class="signature">Шүүгчийн гарын үсэг</div>
                        <div class="signature">Зохион байгуулагчийн гарын үсэг</div>
                    </div>
                </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }

    function generatePopover(btn, data) {
        const tableId = btn.closest('.card').id;
        const withAthleteCount = withAthleteCountMap[tableId] ?? false;

        const noMedal = data.total_athletes - (data.gold + data.silver + data.bronze);
        const pGold = data.gold * data.point_config.gold;
        const pSilver = data.silver * data.point_config.silver;
        const pBronze = data.bronze * data.point_config.bronze;

        const LABEL_TOTAL_SCORE = "{{ trans('display.general_total_score') }}";

        let pOthers = 0;
        let html = `
            🥇: ${data.gold} × ${data.point_config.gold} = <strong>${pGold}</strong><br>
            🥈: ${data.silver} × ${data.point_config.silver} = <strong>${pSilver}</strong><br>
            🥉: ${data.bronze} × ${data.point_config.bronze} = <strong>${pBronze}</strong><br>
        `;

        if (withAthleteCount && data.total_athletes) {
            pOthers = noMedal * data.point_config.other;
            html += `👥: ${noMedal} × ${data.point_config.other} = <strong>${pOthers}</strong><br>`;
        }

        const total = pGold + pSilver + pBronze + pOthers;
        html += `<hr class="my-1"><strong>💯 ${LABEL_TOTAL_SCORE}:</strong> ${total}`;

        $(btn).popover('dispose');
        $(btn).attr('data-content', html).popover('show');
    }

    $(document).on('click', function (e) {
        $('[data-toggle="popover"]').each(function () {
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });
</script>
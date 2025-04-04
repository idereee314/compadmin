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

            'list' => $GenderResultMale,
            'withPoint' => false,
            'id' => 'toplist_male'
        ];
        $toplists[] = [
            'title' => trans('display.best_academy') . ' - ' . config('enums.gender_code')['2'],
            'list' => $GenderResultFemale,
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
            'list' => $GenderResultPointMale,
            'withPoint' => true,
            'id' => 'toplist_male'
        ];
        $toplists[] = [
            'title' => trans('display.best_academy') . ' - ' . config('enums.gender_code')['2'],
            'list' => $GenderResultPointFemale,
            'withPoint' => true,
            'id' => 'toplist_female'
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
                        <button class="btn btn-sm btn-light-primary" onclick="printToplist('{{ $toplist['id'] }}', '{{ $toplist['title'] }}')">
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
                                        <th class="text-center"><i class="la la-medal icon-2x gold-medal-icon"></i></th>
                                        <th class="text-center"><i class="la la-medal icon-2x silver-medal-icon"></i></th>
                                        <th class="text-center"><i class="la la-medal icon-2x bronze-medal-icon"></i></th>
                                        @if($toplist['withPoint'])
                                            <th class="text-center">{{trans('display.general_total_score') }}</th>
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
<script>
    function printToplist(id, title) {
        const card = document.getElementById(id);
        const table = card.querySelector('table').outerHTML;
        const logoUrl = '{{ url("assets/images/logo/uniq_logo_with_spaceX.png") }}';
        const today = new Date().toLocaleDateString('mn-MN');

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>${title}</title>
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

                    <h2>Шилдэг Академийн Жагсаалт</h2>
                    <div class="subtitle">${title}</div>
                    <div class="date"><strong>Огноо:</strong> ${today}</div>

                    ${table}

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
</script>
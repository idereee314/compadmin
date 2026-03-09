@php
    /**
     * 1. ӨГӨГДӨЛ БОЛОВСРУУЛАХ (DATA PREPARATION)
     */
    $all_athletes = [];
    if (isset($members) && count($members) > 0) {
        foreach($members as $m) {
            if(isset($m->lastname_one) && $m->lastname_one != null) {
                $all_athletes[] = [
                    'lastname'  => $m->lastname_one,
                    'firstname' => $m->firstname_one,
                    'academy'   => $m->acname_one ?? 'Академигүй'
                ];
            }
            if(isset($m->lastname_two) && $m->lastname_two != null) {
                $all_athletes[] = [
                    'lastname'  => $m->lastname_two,
                    'firstname' => $m->firstname_two,
                    'academy'   => $m->acname_two ?? 'Академигүй'
                ];
            }
        }
    }

    $count = count($all_athletes);
@endphp

{{-- Match lookup variables ($matchByOrder, $loserMatchesList, etc.) provided by controller --}}

<div style="width:1100px; margin:0 auto; background-color: white; border: 1px solid #ccc; padding: 40px; font-family: sans-serif; color: #333;">

    <div style="border-bottom: 2px solid #e11d48; margin-bottom: 25px; padding-bottom: 10px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2 style="margin:0; font-size: 22px;">{{ @$eventConfig->event->name }}</h2>
            <div style="font-size: 14px; color: #444; margin-top: 5px; font-weight: bold;">
                {{ @$entry->name }} | {{ @$age->name }} | {{ @$weight->weight }}кг | {{ Config::get("enums.gender_code")[@$entry->gender_code] ?? '' }} | {{ !empty(@$eventConfig->event->event_date) ? date_format(date_create(@$eventConfig->event->event_date), 'Y-m-d') : '' }}
            </div>
        </div>
        <div style="text-align: right; font-size: 12px; font-weight: bold; color: #e11d48; text-transform: uppercase;">
            {{ $count }} ТАМИРЧИНТАЙ ОНООЛТ ({{ $count <= 2 ? 'BEST OF THREE' : 'ROUND ROBIN' }})
        </div>
    </div>

    {{-- CASE 1: 2 ТАМИРЧИНТАЙ БОЛ (Best of Three) --}}
    @if($count == 2)
        @php
            $t1 = $all_athletes[0];
            $t2 = $all_athletes[1];
            $bo3_list = [
                ['r' => $t1, 'b' => $t2, 'l' => 'Match 1', 'order' => 1],
                ['r' => $t2, 'b' => $t1, 'l' => 'Match 2', 'order' => 3],
                ['r' => $t1, 'b' => $t2, 'l' => 'Match 3 (Хэрэв шаардлагатай бол)', 'order' => 9999]
            ];
        @endphp

        <div style="max-width: 700px; margin: 0 auto;">
            <h3 style="text-align: center; background: #f1f5f9; padding: 10px; border: 1px solid #cbd5e1; font-size: 16px;">BEST OF THREE (2 ТАМИРЧИН)</h3>
            @foreach($bo3_list as $m)
                @php $md = $matchByOrder[$m['order']] ?? null; $scores = $getScores($md); @endphp
                <div style="margin-bottom: 25px;">
                    <div style="font-size: 11px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px;">{{ $m['l'] }}@if($md && $md->status === 'C' && $md->win_method) <span style="color:#16a34a;">({{ $md->win_method }})</span>@endif</div>
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #475569;">
                        <tr @if($md && $isWinner($md->reg_one_id, $md)) style="background:#f0fdf4;" @endif>
                            <td style="height: 50px; padding: 0;">
                                <div style="width: 8px; height: 100%; background: #ef4444; float: left;"></div>
                                <div style="padding: 5px 15px;">
                                    {!! $fmtPlayer($md->lastname_one ?? $m['r']['lastname'], $md->firstname_one ?? $m['r']['firstname']) !!}
                                    <span style="display: block; font-size: 11px; color: #64748b;">{{ $md->acname_one ?? $m['r']['academy'] }}</span>
                                </div>
                            </td>
                            <td style="width: 70px; background: #f8fafc; border-left: 1px solid #475569; text-align:center; font-weight:bold; font-size:16px;">{{ $scores['red'] }}</td>
                        </tr>
                        <tr @if($md && $isWinner($md->reg_two_id, $md)) style="background:#f0fdf4;" @endif>
                            <td style="height: 50px; padding: 0; border-top: 1px solid #e2e8f0;">
                                <div style="width: 8px; height: 100%; background: #3b82f6; float: left;"></div>
                                <div style="padding: 5px 15px;">
                                    {!! $fmtPlayer($md->lastname_two ?? $m['b']['lastname'], $md->firstname_two ?? $m['b']['firstname']) !!}
                                    <span style="display: block; font-size: 11px; color: #64748b;">{{ $md->acname_two ?? $m['b']['academy'] }}</span>
                                </div>
                            </td>
                            <td style="width: 70px; background: #f8fafc; border-left: 1px solid #475569; text-align:center; font-weight:bold; font-size:16px;">{{ $scores['blue'] }}</td>
                        </tr>
                    </table>
                </div>
            @endforeach
        </div>

    {{-- CASE 2: 3-5 ТАМИРЧИНТАЙ БОЛ (Single Pool Round Robin) --}}
    @elseif($count > 2 && $count <= 5)
        @php
            if ($count == 3) {
                $rrMatches = [
                    ['r' => $all_athletes[0], 'b' => $all_athletes[1], 'l' => 'Match 1', 'order' => 1],
                    ['r' => $all_athletes[0], 'b' => $all_athletes[2], 'l' => 'Match 2', 'order' => 2],
                    ['r' => $all_athletes[1], 'b' => $all_athletes[2], 'l' => 'Match 3', 'order' => 3],
                ];
            } elseif ($count == 4) {
                $rrMatches = [
                    ['r' => $all_athletes[0], 'b' => $all_athletes[1], 'l' => 'Match 1', 'order' => 1],
                    ['r' => $all_athletes[2], 'b' => $all_athletes[3], 'l' => 'Match 2', 'order' => 2],
                    ['r' => $all_athletes[0], 'b' => $all_athletes[2], 'l' => 'Match 3', 'order' => 3],
                    ['r' => $all_athletes[1], 'b' => $all_athletes[3], 'l' => 'Match 4', 'order' => 4],
                    ['r' => $all_athletes[0], 'b' => $all_athletes[3], 'l' => 'Match 5', 'order' => 5],
                    ['r' => $all_athletes[1], 'b' => $all_athletes[2], 'l' => 'Match 6', 'order' => 6],
                ];
            } else {
                $rrMatches = [
                    ['r' => $all_athletes[0], 'b' => $all_athletes[1], 'l' => 'Match 1', 'order' => 1],
                    ['r' => $all_athletes[2], 'b' => $all_athletes[3], 'l' => 'Match 2', 'order' => 2],
                    ['r' => $all_athletes[0], 'b' => $all_athletes[4], 'l' => 'Match 3', 'order' => 3],
                    ['r' => $all_athletes[1], 'b' => $all_athletes[2], 'l' => 'Match 4', 'order' => 4],
                    ['r' => $all_athletes[3], 'b' => $all_athletes[4], 'l' => 'Match 5', 'order' => 5],
                    ['r' => $all_athletes[0], 'b' => $all_athletes[2], 'l' => 'Match 6', 'order' => 6],
                    ['r' => $all_athletes[1], 'b' => $all_athletes[4], 'l' => 'Match 7', 'order' => 7],
                    ['r' => $all_athletes[0], 'b' => $all_athletes[3], 'l' => 'Match 8', 'order' => 8],
                    ['r' => $all_athletes[2], 'b' => $all_athletes[4], 'l' => 'Match 9', 'order' => 9],
                    ['r' => $all_athletes[1], 'b' => $all_athletes[3], 'l' => 'Match 10', 'order' => 10],
                ];
            }
        @endphp

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
            @foreach($rrMatches as $m)
                @php $md = $matchByOrder[$m['order']] ?? null; $scores = $getScores($md); @endphp
                <div style="margin-bottom: 10px;">
                    <div style="font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 3px;">{{ $m['l'] }}@if($md && $md->status === 'C' && $md->win_method) <span style="color:#16a34a;">({{ $md->win_method }})</span>@endif</div>
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #475569;">
                        <tr @if($md && $isWinner($md->reg_one_id, $md)) style="background:#f0fdf4;" @endif>
                            <td style="height: 44px; padding: 0;">
                                <div style="width: 6px; height: 100%; background: #ef4444; float: left;"></div>
                                <div style="padding: 4px 10px; line-height: 1.2;">
                                    {!! $fmtPlayer($md->lastname_one ?? $m['r']['lastname'], $md->firstname_one ?? $m['r']['firstname']) !!}
                                    <div style="font-size: 10px; color: #64748b;">{{ $md->acname_one ?? $m['r']['academy'] }}</div>
                                </div>
                            </td>
                            <td style="width: 50px; background: #f1f5f9; border-left: 1px solid #475569; text-align:center; font-weight:bold;">{{ $scores['red'] }}</td>
                        </tr>
                        <tr @if($md && $isWinner($md->reg_two_id, $md)) style="background:#f0fdf4;" @endif>
                            <td style="height: 44px; padding: 0; border-top: 1px solid #e2e8f0;">
                                <div style="width: 6px; height: 100%; background: #3b82f6; float: left;"></div>
                                <div style="padding: 4px 10px; line-height: 1.2;">
                                    {!! $fmtPlayer($md->lastname_two ?? $m['b']['lastname'], $md->firstname_two ?? $m['b']['firstname']) !!}
                                    <div style="font-size: 10px; color: #64748b;">{{ $md->acname_two ?? $m['b']['academy'] }}</div>
                                </div>
                            </td>
                            <td style="width: 50px; background: #f1f5f9; border-left: 1px solid #475569; text-align:center; font-weight:bold;">{{ $scores['blue'] }}</td>
                        </tr>
                    </table>
                </div>
            @endforeach
        </div>
        <div style="text-align: center; margin-top: 30px; font-weight: bold; font-size: 13px;">* Бүх тамирчид хоорондоо тойргоор барилдаж ялагчийг тодруулна.</div>

    {{-- CASE 3: 6 ТАМИРЧИНТАЙ БОЛ (Pools + Bracket) --}}
    @else
        @php
            // Snake seeding: Pool A = Seed 1, Seed 4, Seed 5 / Pool B = Seed 2, Seed 3, Seed 6
            $poolA = [$all_athletes[0], $all_athletes[3], $all_athletes[4]];
            $poolB = [$all_athletes[1], $all_athletes[2], $all_athletes[5]];

            $matchesA = [
                ['r' => $poolA[0] ?? null, 'b' => $poolA[1] ?? null, 'l' => 'Match 1', 'order' => 1],
                ['r' => $poolA[0] ?? null, 'b' => $poolA[2] ?? null, 'l' => 'Match 3', 'order' => 3],
                ['r' => $poolA[1] ?? null, 'b' => $poolA[2] ?? null, 'l' => 'Match 5', 'order' => 5],
            ];
            $matchesB = [
                ['r' => $poolB[0] ?? null, 'b' => $poolB[1] ?? null, 'l' => 'Match 2', 'order' => 2],
                ['r' => $poolB[0] ?? null, 'b' => $poolB[2] ?? null, 'l' => 'Match 4', 'order' => 4],
                ['r' => $poolB[1] ?? null, 'b' => $poolB[2] ?? null, 'l' => 'Match 6', 'order' => 6],
            ];

            $sf1 = $matchByOrder[201] ?? null;
            $sf2 = $matchByOrder[202] ?? null;
            $gold = $matchByOrder[9999] ?? null;
        @endphp

        <div style="display:flex; justify-content:space-between; gap: 40px; margin-bottom: 35px;">
            @foreach([['pool' => $matchesA, 'label' => 'Pool A', 'color' => '#ef4444'], ['pool' => $matchesB, 'label' => 'Pool B', 'color' => '#3b82f6']] as $poolData)
                <div style="flex:1;">
                    <div style="background:#f8fafc; padding:10px; font-weight:bold; border:1px solid #cbd5e1; margin-bottom:15px; border-left:6px solid {{ $poolData['color'] }};">
                        {{ $poolData['label'] }} - Тойргоор (Round Robin)
                    </div>
                    @foreach($poolData['pool'] as $m)
                        @php $md = $matchByOrder[$m['order']] ?? null; $scores = $getScores($md); @endphp
                        <div style="margin-bottom: 14px;">
                            <div style="font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 3px;">{{ $m['l'] }}@if($md && $md->status === 'C' && $md->win_method) <span style="color:#16a34a;">({{ $md->win_method }})</span>@endif</div>
                            <table style="width: 100%; border-collapse: collapse; border: 1px solid #475569; background:#fff;">
                                <tr @if($md && $isWinner($md->reg_one_id, $md)) style="background:#f0fdf4;" @endif>
                                    <td style="height: 44px; padding: 0;">
                                        <div style="width: 6px; height: 100%; background: #ef4444; float: left;"></div>
                                        <div style="padding: 4px 10px; line-height: 1.2;">
                                            @if($md && $md->lastname_one) {!! $fmtPlayer($md->lastname_one, $md->firstname_one) !!} <div style="font-size:10px;color:#64748b;">{{ $md->acname_one }}</div>
                                            @elseif($m['r']) {{ $m['r']['lastname'] }} <strong>{{ $m['r']['firstname'] }}</strong> <div style="font-size:10px;color:#64748b;">{{ $m['r']['academy'] }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="width: 50px; background: #f1f5f9; border-left: 1px solid #475569; text-align:center; font-weight:bold;">{{ $scores['red'] }}</td>
                                </tr>
                                <tr @if($md && $isWinner($md->reg_two_id, $md)) style="background:#f0fdf4;" @endif>
                                    <td style="height: 44px; padding: 0; border-top: 1px solid #e2e8f0;">
                                        <div style="width: 6px; height: 100%; background: #3b82f6; float: left;"></div>
                                        <div style="padding: 4px 10px; line-height: 1.2;">
                                            @if($md && $md->lastname_two) {!! $fmtPlayer($md->lastname_two, $md->firstname_two) !!} <div style="font-size:10px;color:#64748b;">{{ $md->acname_two }}</div>
                                            @elseif($m['b']) {{ $m['b']['lastname'] }} <strong>{{ $m['b']['firstname'] }}</strong> <div style="font-size:10px;color:#64748b;">{{ $m['b']['academy'] }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="width: 50px; background: #f1f5f9; border-left: 1px solid #475569; text-align:center; font-weight:bold;">{{ $scores['blue'] }}</td>
                                </tr>
                            </table>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div style="text-align:center; margin-top: 10px; font-weight:bold; font-size: 13px; color:#64748b;">
            * 6 тамирчныг 3,3-аар нь 2 хэсэгт хувааж тойргоор барилдуулна (Pool A / Pool B).
        </div>

        {{-- Semi-finals + Final with live data --}}
        <div style="display:flex; align-items:flex-start; justify-content:center; gap: 80px; margin-top: 35px; position:relative;">
            <div style="width: 360px;">
                @foreach([['md' => $sf1, 'label' => 'Match 7 (Semi)', 'mb' => '35px', 'dir' => 'down'], ['md' => $sf2, 'label' => 'Match 8 (Semi)', 'mb' => '0', 'dir' => 'up']] as $sf)
                    @php $scores = $getScores($sf['md']); @endphp
                    <div style="margin-bottom: {{ $sf['mb'] }}; position:relative;">
                        <div style="font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 3px;">{{ $sf['label'] }}@if($sf['md'] && $sf['md']->status === 'C' && $sf['md']->win_method) <span style="color:#16a34a;">({{ $sf['md']->win_method }})</span>@endif</div>
                        <table style="width: 100%; border-collapse: collapse; border: 1px solid #111827; background:#fff;">
                            <tr @if($sf['md'] && $isWinner($sf['md']->reg_one_id, $sf['md'])) style="background:#f0fdf4;" @endif>
                                <td style="height: 46px; padding: 0;">
                                    <div style="width: 6px; height: 100%; background:#ef4444; float:left;"></div>
                                    <div style="padding: 5px 12px;">{!! $fmtPlayer($sf['md']->lastname_one ?? null, $sf['md']->firstname_one ?? '') !!}@if($sf['md'] && $sf['md']->acname_one)<div style="font-size:10px;color:#64748b;">{{ $sf['md']->acname_one }}</div>@endif</div>
                                </td>
                                <td style="width: 60px; background: #f1f5f9; border-left: 1px solid #111827; text-align:center; font-weight:bold;">{{ $scores['red'] }}</td>
                            </tr>
                            <tr @if($sf['md'] && $isWinner($sf['md']->reg_two_id, $sf['md'])) style="background:#f0fdf4;" @endif>
                                <td style="height: 46px; padding: 0; border-top: 1px solid #e2e8f0;">
                                    <div style="width: 6px; height: 100%; background:#3b82f6; float:left;"></div>
                                    <div style="padding: 5px 12px;">{!! $fmtPlayer($sf['md']->lastname_two ?? null, $sf['md']->firstname_two ?? '') !!}@if($sf['md'] && $sf['md']->acname_two)<div style="font-size:10px;color:#64748b;">{{ $sf['md']->acname_two }}</div>@endif</div>
                                </td>
                                <td style="width: 60px; background: #f1f5f9; border-left: 1px solid #111827; text-align:center; font-weight:bold;">{{ $scores['blue'] }}</td>
                            </tr>
                        </table>
                        @if($sf['dir'] === 'down')
                            <div style="position:absolute; right:-60px; top:50%; width:60px; border-top:1px solid #94a3b8;"></div>
                            <div style="position:absolute; right:-60px; top:50%; height:60px; border-right:1px solid #94a3b8;"></div>
                            <div style="position:absolute; right:-80px; top:calc(50% + 60px); width:20px; border-top:1px solid #94a3b8;"></div>
                        @else
                            <div style="position:absolute; right:-60px; top:50%; width:60px; border-top:1px solid #94a3b8;"></div>
                            <div style="position:absolute; right:-60px; top:calc(50% - 60px); height:60px; border-right:1px solid #94a3b8;"></div>
                            <div style="position:absolute; right:-80px; top:calc(50% - 60px); width:20px; border-top:1px solid #94a3b8;"></div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div style="width: 360px; margin-top: 60px;">
                @php $scores = $getScores($gold); @endphp
                <div style="font-size: 10px; font-weight: bold; color: #0f172a; text-transform: uppercase; margin-bottom: 6px; text-align:center;">
                    Match 9 (FINAL)@if($gold && $gold->status === 'C' && $gold->win_method) <span style="color:#16a34a;">({{ $gold->win_method }})</span>@endif
                </div>
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; background:#fff;">
                    <tr @if($gold && $isWinner($gold->reg_one_id, $gold)) style="background:#f0fdf4;" @endif>
                        <td style="height: 52px; padding: 0;">
                            <div style="width: 6px; height: 100%; background:#ef4444; float:left;"></div>
                            <div style="padding: 6px 12px; font-weight:bold;">{!! $fmtPlayer($gold->lastname_one ?? null, $gold->firstname_one ?? '') !!}@if($gold && $gold->acname_one)<div style="font-size:10px;color:#64748b;font-weight:normal;">{{ $gold->acname_one }}</div>@endif</div>
                        </td>
                        <td style="width: 70px; background: #f1f5f9; border-left: 1px solid #000; text-align:center; font-weight:bold; font-size:16px;">{{ $scores['red'] }}</td>
                    </tr>
                    <tr @if($gold && $isWinner($gold->reg_two_id, $gold)) style="background:#f0fdf4;" @endif>
                        <td style="height: 52px; padding: 0; border-top: 1px solid #000;">
                            <div style="width: 6px; height: 100%; background:#3b82f6; float:left;"></div>
                            <div style="padding: 6px 12px; font-weight:bold;">{!! $fmtPlayer($gold->lastname_two ?? null, $gold->firstname_two ?? '') !!}@if($gold && $gold->acname_two)<div style="font-size:10px;color:#64748b;font-weight:normal;">{{ $gold->acname_two }}</div>@endif</div>
                        </td>
                        <td style="width: 70px; background: #f1f5f9; border-left: 1px solid #000; text-align:center; font-weight:bold; font-size:16px;">{{ $scores['blue'] }}</td>
                    </tr>
                </table>
            </div>
        </div>
    @endif

    <div style="margin-top: 50px;">
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; text-align: center;">
            <tr style="background: #f1f5f9;">
                <th style="border: 1px solid #000; padding: 10px; width: 30%;">Байр</th>
                <th style="border: 1px solid #000; padding: 10px;">Тамирчны нэр / Академи</th>
            </tr>
            <tr><td style="border: 1px solid #000; padding: 15px; font-weight: bold;">🥇 1-р байр</td><td style="border: 1px solid #000;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 15px; font-weight: bold;">🥈 2-р байр</td><td style="border: 1px solid #000;"></td></tr>
            <tr><td style="border: 1px solid #000; padding: 15px; font-weight: bold;">🥉 3-р байр</td><td style="border: 1px solid #000;"></td></tr>
        </table>
    </div>

    <div style="margin-top: 60px; border-top: 1px solid #ddd; padding-top: 20px; font-size: 14px; display: flex; justify-content: space-between;">
        <span>Ерөнхий шүүгч: ........................................... / ................................. /</span>
        <span>Огноо: {{ date('Y-m-d') }}</span>
    </div>
</div>
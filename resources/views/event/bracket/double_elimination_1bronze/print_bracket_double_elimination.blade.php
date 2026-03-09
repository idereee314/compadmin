{{-- =========================================================================
    DOUBLE ELIMINATION BRACKET (8 / 16 / 32) — UNIFIED BLADE
    + "MATCH 1" label style (Sportdata-like)
   ========================================================================= --}}

@php
    // ---------------------------
    // 0) Bracket size detect
    // ---------------------------
    $bracketSize = (int) ($bracketSize ?? 0);

    if (!in_array($bracketSize, [8,16,32], true)) {
        $membersCount = is_countable($members ?? null) ? count($members) : 0;
        $bracketSize  = ($membersCount >= 16) ? 32 : (($membersCount >= 8) ? 16 : 8);
    }

    // ---------------------------
    // 1) Configs (seed pairs + rounds)
    // ---------------------------
    $configs = [
        8 => [
            'seedPairs' => [
                0 => [1, 8], 1 => [5, 4], 2 => [3, 6], 3 => [7, 2],
            ],
            'rounds_count'  => 3,
            'total_matches' => 4,
            'header_color'  => '#2563eb',
            'title_font'    => '20px',
            'sub_font'      => '13px',
            'label'         => '8 ТАМИРЧИНТАЙ ОНООЛТ',
            'th_titles'     => ['ШӨВГИЙН 8 (QF)', 'ХАГАС ШИГШЭЭ (SF)', 'ШИГШЭЭ'],
            'v_height_mul'  => 60,
            'pad_y'         => 25,
        ],
        16 => [
            'seedPairs' => [
                0 => [1, 16], 1 => [9, 8], 2 => [5, 12], 3 => [13, 4],
                4 => [3, 14], 5 => [11, 6], 6 => [7, 10], 7 => [15, 2],
            ],
            'rounds_count'  => 4,
            'total_matches' => 8,
            'header_color'  => '#e11d48',
            'title_font'    => '22px',
            'sub_font'      => '14px',
            'label'         => '16 ТАМИРЧИНТАЙ ОНООЛТ',
            'th_titles'     => ['1/8 ШИГШЭЭ', 'ШӨВГИЙН 8 (QF)', 'ХАГАС ШИГШЭЭ (SF)', 'ШИГШЭЭ'],
            'v_height_mul'  => 52,
            'pad_y'         => 20,
        ],
        32 => [
            'seedPairs' => [
                [1,32],[17,16],[9,24],[25,8],
                [5,28],[21,12],[13,20],[29,4],
                [3,30],[19,14],[11,22],[27,6],
                [7,26],[23,10],[15,18],[31,2],
            ],
            'rounds_count'  => 5,
            'total_matches' => 16,
            'header_color'  => '#16a34a',
            'title_font'    => '22px',
            'sub_font'      => '14px',
            'label'         => '32 ТАМИРЧИНТАЙ ОНООЛТ',
            'th_titles'     => ['1/16 ШИГШЭЭ','1/8 ШИГШЭЭ','ШӨВГИЙН 8 (QF)','ХАГАС ШИГШЭЭ (SF)','ШИГШЭЭ'],
            'v_height_mul'  => 46,
            'pad_y'         => 16,
        ],
    ];

    $cfg           = $configs[$bracketSize];
    $seedPairs     = $cfg['seedPairs'];
    $rounds_count  = $cfg['rounds_count'];
    $total_matches = $cfg['total_matches'];

    // ---------------------------
    // 2) Safe member getter (match row)
    // ---------------------------
    $getMatchRow = function(int $index) use ($members) {
        if (is_array($members ?? null)) return $members[$index] ?? null;
        if ($members instanceof \Illuminate\Support\Collection) return $members->get($index);
        return null;
    };

    // ---------------------------
    // 3) Display helpers
    // ---------------------------
    $fmtName = function($lastname, $firstname) {
        if (!empty($lastname)) {
            return e($lastname) . ' <strong>' . e($firstname) . '</strong>';
        }
        return '<span style="color:#999">BYE</span>';
    };

    // ---------------------------
    // 4) WINNER BRACKET match № (round бүр дээр тасралтгүй)
    //   16: R1=1..8, R2=9..12, R3=13..14, Final=15
    //   8 : R1=1..4, R2=5..6, Final=7
    //   32: R1=1..16, R2=17..24, R3=25..28, R4=29..30, Final=31
    // ---------------------------
    $roundStartNo = [];
    $tmp = 1;
    for ($r=0; $r<$rounds_count; $r++) {
        $roundStartNo[$r] = $tmp;
        $tmp += (int) ($total_matches / (int) pow(2, $r));
    }

    $getMatchNo = function(int $r, int $index) use ($roundStartNo) {
        $step = (int) pow(2, $r);
        $slot = (int) floor($index / $step);
        return $roundStartNo[$r] + $slot;
    };

    // Winner bracket-ийн хамгийн сүүлийн match №: N-1 (8=>7, 16=>15, 32=>31)
    $lastWinnerMatchNo = (int) ($bracketSize - 1);

    // ---------------------------
    // 5) REPECHAGE match № (placeholders дээр үргэлжлүүлээд)
    // ---------------------------
    $repNo = $lastWinnerMatchNo;

    $nextRepNo = function() use (&$repNo) {
        $repNo++;
        return $repNo;
    };

    // ---------------------------
    // 6) Map (round_index, slot) → order_no for winner bracket
    // ---------------------------
    $getOrderNo = function(int $r, int $slot) use ($rounds_count) {
        if ($r === $rounds_count - 1) return 9999; // Final
        if ($r === 0) return $slot + 1; // First round: 1, 2, 3, ...
        return ($r + 1) * 100 + $slot + 1; // Later rounds: 201, 202, ...
    };
@endphp

@include('event.bracket._match_lookup')

@php
    // Group loser matches by round for repechage display
    $_loserRounds = [];
    $_bronzeList = [];
    foreach ($loserMatchesList as $_lm) {
        if ($_lm->order_no >= 9990) {
            $_bronzeList[] = $_lm;
        } else {
            $_rk = (int) floor($_lm->order_no / 100);
            $_loserRounds[$_rk][] = $_lm;
        }
    }
    ksort($_loserRounds);
    $_lrv = array_values($_loserRounds);
@endphp

<div style="width:1600px; margin:0 auto; background-color: white; border: 1px solid #ccc; padding: 30px; font-family: 'Helvetica', 'Arial', sans-serif; position: relative; min-height: 1000px; color: #333;">

    {{-- ========================= HEADER ========================= --}}
    <div style="border-bottom: 2px solid {{ $cfg['header_color'] }}; margin-bottom: 25px; padding-bottom: 10px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2 style="margin:0; font-size: {{ $cfg['title_font'] }};">{{ @$eventConfig->event->name }}</h2>
            <div style="font-size: {{ $cfg['sub_font'] }}; color: #444; margin-top: 5px; font-weight: bold;">
                {{ @$entry->name }} |
                {{ @$age->name }} |
                {{ @$weight->weight }}кг |
                {{ Config::get("enums.gender_code")[@$entry->gender_code] ?? '' }} |
                {{ !empty(@$eventConfig->event->event_date) ? date_format(date_create(@$eventConfig->event->event_date), 'Y-m-d') : '' }}
            </div>
        </div>

        <div style="text-align: right; font-size: 12px; font-weight: bold; color: #e11d48;">
            {{ $cfg['label'] }}
        </div>
    </div>

    {{-- ========================= WINNER BRACKET ========================= --}}
    <div style="margin-bottom: 50px;">
        <table width="100%" border="0" style="border-collapse: collapse; table-layout: fixed;">
            <thead>
                <tr>
                    @foreach($cfg['th_titles'] as $t)
                        <th class="round-title">{{ $t }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @for($index = 0; $index < $total_matches; $index++)
                    @php $m = $getMatchRow($index); @endphp
                    <tr>
                        @for($r = 0; $r < $rounds_count; $r++)
                            @php
                                $step       = (int) pow(2, $r);
                                $is_visible = ($index % $step === 0);
                            @endphp

                            @if($is_visible)
                                @php
                                    $slot = (int) floor($index / $step);
                                    $orderNo = $getOrderNo($r, $slot);
                                    $md = $matchByOrder[$orderNo] ?? null;
                                    $sc = $md ? $getScores($md) : ['red' => '', 'blue' => ''];
                                @endphp
                                <td rowspan="{{ $step }}" valign="middle" style="position: relative;">
                                    <div class="connector-container" style="padding: {{ $cfg['pad_y'] }}px 40px {{ $cfg['pad_y'] }}px 0;">
                                        <table width="100%" border="0" class="match-box">
                                            <tr>
                                                <td class="player-cell" @if($md && $isWinner($md->reg_one_id, $md)) style="background:#d4edda;" @endif>
                                                    <div class="indicator-bar {{ (($index / $step) % 2 == 0) ? 'red-bg' : 'blue-bg' }}">
                                                        @if($md && $md->status === 'C')
                                                            {{ $sc['red'] }}
                                                        @elseif($r === 0)
                                                            {{ $m->seed_one ?? ($seedPairs[$index][0] ?? '') }}
                                                        @endif
                                                    </div>
                                                    <div class="player-info">
                                                        @if($r === 0)
                                                            <div class="p-name">{!! $fmtName($m->lastname_one ?? null, $m->firstname_one ?? '') !!}</div>
                                                            <div class="p-academy">{{ $m->acname_one ?? '' }}</div>
                                                        @elseif($md)
                                                            <div class="p-name">{!! $fmtPlayer($md->lastname_one, $md->firstname_one) !!}</div>
                                                            <div class="p-academy">{{ $md->acname_one ?? '' }}</div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="player-cell" style="border-top: 1px solid #e2e8f0;{{ $md && $isWinner($md->reg_two_id, $md) ? ' background:#d4edda;' : '' }}">
                                                    <div class="indicator-bar {{ (($index / $step) % 2 == 0) ? 'blue-bg' : 'red-bg' }}">
                                                        @if($md && $md->status === 'C')
                                                            {{ $sc['blue'] }}
                                                        @elseif($r === 0)
                                                            {{ $m->seed_two ?? ($seedPairs[$index][1] ?? '') }}
                                                        @endif
                                                    </div>
                                                    <div class="player-info">
                                                        @if($r === 0)
                                                            <div class="p-name">{!! $fmtName($m->lastname_two ?? null, $m->firstname_two ?? '') !!}</div>
                                                            <div class="p-academy">{{ $m->acname_two ?? '' }}</div>
                                                        @elseif($md)
                                                            <div class="p-name">{!! $fmtPlayer($md->lastname_two, $md->firstname_two) !!}</div>
                                                            <div class="p-academy">{{ $md->acname_two ?? '' }}</div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>

                                        @if($r < $rounds_count - 1)
                                            @php
                                                $is_top   = ((($index / $step) % 2) == 0);
                                                $v_height = $step * $cfg['v_height_mul'];
                                            @endphp
                                            <div class="line-logic {{ $is_top ? 'l-down' : 'l-up' }}" style="height: {{ $v_height }}px;"></div>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    {{-- ========================= REPECHAGE ========================= --}}
    <div class="repechage-header">ТОРГУУЛИЙН БАРИЛДААН / REPECHAGE</div>

    @if($bracketSize === 8)
    <table width="100%" style="margin-top: 20px; border-collapse: collapse; table-layout: fixed;">
        <thead>
            <tr>
                <th class="rep-col-title">QF-д хожигдсон</th>
                <th class="rep-col-title">RP (QF-L → RP-W)</th>
                <th class="rep-col-title" style="background: #fff7ed; color: #c2410c;">Хүрэл медаль (1) 🥉</th>
            </tr>
        </thead>
        <tr>
            <td width="33.33%" valign="top">
                @for($i=0; $i<2; $i++)
                    @php $lm = $_lrv[0][$i] ?? null; $lsc = $lm ? $getScores($lm) : ['red'=>'','blue'=>'']; @endphp
                    <div class="rep-wrapper" style="margin-top: 30px;">
                        <table class="match-box-sm">
                            <tr><td class="cell-sm" @if($lm && $isWinner($lm->reg_one_id, $lm)) style="background:#d4edda;" @endif>
                                <div class="indicator-bar-sm red-bg">{{ $lsc['red'] }}</div>
                                <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_one, $lm->firstname_one) : '' !!}</div>
                            </td></tr>
                            <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;{{ $lm && $isWinner($lm->reg_two_id, $lm) ? ' background:#d4edda;' : '' }}">
                                <div class="indicator-bar-sm blue-bg">{{ $lsc['blue'] }}</div>
                                <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_two, $lm->firstname_two) : '' !!}</div>
                            </td></tr>
                        </table>
                        <div class="rep-line-straight"></div>
                    </div>
                @endfor
            </td>
            <td width="33.33%" valign="top">
                @for($i=0; $i<2; $i++)
                    @php $lm = $_lrv[1][$i] ?? null; $lsc = $lm ? $getScores($lm) : ['red'=>'','blue'=>'']; @endphp
                    <div class="rep-wrapper" style="margin-top: 30px;">
                        <table class="match-box-sm">
                            <tr><td class="cell-sm" @if($lm && $isWinner($lm->reg_one_id, $lm)) style="background:#d4edda;" @endif>
                                <div class="indicator-bar-sm red-bg">{{ $lsc['red'] }}</div>
                                <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_one, $lm->firstname_one) : '' !!}</div>
                            </td></tr>
                            <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;{{ $lm && $isWinner($lm->reg_two_id, $lm) ? ' background:#d4edda;' : '' }}">
                                <div class="indicator-bar-sm blue-bg">{{ $lsc['blue'] }}</div>
                                <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_two, $lm->firstname_two) : '' !!}</div>
                            </td></tr>
                        </table>
                        <div class="rep-line {{ $i % 2 == 0 ? 'r-down' : 'r-up' }}"></div>
                    </div>
                @endfor
            </td>
            <td width="33.33%" valign="top">
                @php $lm = $_bronzeList[0] ?? null; $lsc = $lm ? $getScores($lm) : ['red'=>'','blue'=>'']; @endphp
                <div class="rep-wrapper" style="margin-top: 85px; margin-bottom: 110px;">
                    <table class="match-box-sm">
                        <tr><td class="cell-sm" @if($lm && $isWinner($lm->reg_one_id, $lm)) style="background:#d4edda;" @endif>
                            <div class="indicator-bar-sm red-bg">{{ $lsc['red'] }}</div>
                            <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_one, $lm->firstname_one) : '' !!}</div>
                        </td></tr>
                        <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;{{ $lm && $isWinner($lm->reg_two_id, $lm) ? ' background:#d4edda;' : '' }}">
                            <div class="indicator-bar-sm blue-bg">{{ $lsc['blue'] }}</div>
                            <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_two, $lm->firstname_two) : '' !!}</div>
                        </td></tr>
                    </table>
                    <div style="margin-left: 8px; font-size: 16px;">🥉</div>
                </div>
            </td>
        </tr>
    </table>
    @elseif($bracketSize === 16)
    <table width="100%" style="margin-top: 20px; border-collapse: collapse; table-layout: fixed;">
        <thead>
            <tr>
                <th class="rep-col-title">R1-д хожигдсон</th>
                <th class="rep-col-title">vs QF-д хожигдсон</th>
                <th class="rep-col-title">Дараагийн шат (RP)</th>
                <th class="rep-col-title">vs SF-д хожигдсон</th>
                <th class="rep-col-title" style="background: #fff7ed; color: #c2410c;">Хүрэл медаль (1) 🥉</th>
            </tr>
        </thead>
        <tr>
            <td width="20%" valign="top">
                @for($i=0; $i<4; $i++)
                    @php $lm = $_lrv[0][$i] ?? null; $lsc = $lm ? $getScores($lm) : ['red'=>'','blue'=>'']; @endphp
                    <div class="rep-wrapper" style="margin-top: 25px;">
                        <table class="match-box-sm">
                            <tr><td class="cell-sm" @if($lm && $isWinner($lm->reg_one_id, $lm)) style="background:#d4edda;" @endif>
                                <div class="indicator-bar-sm red-bg">{{ $lsc['red'] }}</div>
                                <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_one, $lm->firstname_one) : '' !!}</div>
                            </td></tr>
                            <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;{{ $lm && $isWinner($lm->reg_two_id, $lm) ? ' background:#d4edda;' : '' }}">
                                <div class="indicator-bar-sm blue-bg">{{ $lsc['blue'] }}</div>
                                <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_two, $lm->firstname_two) : '' !!}</div>
                            </td></tr>
                        </table>
                        <div class="rep-line {{ $i % 2 == 0 ? 'r-down' : 'r-up' }}"></div>
                    </div>
                @endfor
            </td>
            <td width="20%" valign="top">
                @for($i=0; $i<4; $i++)
                    @php $lm = $_lrv[1][$i] ?? null; $lsc = $lm ? $getScores($lm) : ['red'=>'','blue'=>'']; @endphp
                    <div class="rep-wrapper" style="margin-top: 25px;">
                        <table class="match-box-sm">
                            <tr><td class="cell-sm" @if($lm && $isWinner($lm->reg_one_id, $lm)) style="background:#d4edda;" @endif>
                                <div class="indicator-bar-sm red-bg">{{ $lsc['red'] }}</div>
                                <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_one, $lm->firstname_one) : '' !!}</div>
                            </td></tr>
                            <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;{{ $lm && $isWinner($lm->reg_two_id, $lm) ? ' background:#d4edda;' : '' }}">
                                <div class="indicator-bar-sm blue-bg">{{ $lsc['blue'] }}</div>
                                <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_two, $lm->firstname_two) : '' !!}</div>
                            </td></tr>
                        </table>
                        <div class="rep-line {{ $i % 2 == 0 ? 'r-down' : 'r-up' }}"></div>
                    </div>
                @endfor
            </td>
            <td width="20%" valign="top">
                @for($i=0; $i<2; $i++)
                    @php $lm = $_lrv[2][$i] ?? null; $lsc = $lm ? $getScores($lm) : ['red'=>'','blue'=>'']; @endphp
                    <div class="rep-wrapper" style="margin-top: 85px; margin-bottom: 110px;">
                        <table class="match-box-sm">
                            <tr><td class="cell-sm" @if($lm && $isWinner($lm->reg_one_id, $lm)) style="background:#d4edda;" @endif>
                                <div class="indicator-bar-sm red-bg">{{ $lsc['red'] }}</div>
                                <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_one, $lm->firstname_one) : '' !!}</div>
                            </td></tr>
                            <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;{{ $lm && $isWinner($lm->reg_two_id, $lm) ? ' background:#d4edda;' : '' }}">
                                <div class="indicator-bar-sm blue-bg">{{ $lsc['blue'] }}</div>
                                <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_two, $lm->firstname_two) : '' !!}</div>
                            </td></tr>
                        </table>
                        <div style=" position:absolute; right:0; top:50%; width:35px; height:1.5px; background:#94a3b8;"></div>
                    </div>
                @endfor
            </td>
            <td width="20%" valign="top">
                @for($i=0; $i<2; $i++)
                    @php $lm = $_lrv[3][$i] ?? null; $lsc = $lm ? $getScores($lm) : ['red'=>'','blue'=>'']; @endphp
                    <div class="rep-wrapper" style="margin-top: 85px; margin-bottom: 110px;">
                        <table class="match-box-sm">
                            <tr><td class="cell-sm" @if($lm && $isWinner($lm->reg_one_id, $lm)) style="background:#d4edda;" @endif>
                                <div class="indicator-bar-sm red-bg">{{ $lsc['red'] }}</div>
                                <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_one, $lm->firstname_one) : '' !!}</div>
                            </td></tr>
                            <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;{{ $lm && $isWinner($lm->reg_two_id, $lm) ? ' background:#d4edda;' : '' }}">
                                <div class="indicator-bar-sm blue-bg">{{ $lsc['blue'] }}</div>
                                <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_two, $lm->firstname_two) : '' !!}</div>
                            </td></tr>
                        </table>
                        <div class="rep-line {{ $i % 2 == 0 ? 'r-down' : 'r-up' }}"></div>
                    </div>
                @endfor
            </td>
            <td width="20%" valign="top">
                @php $lm = $_bronzeList[0] ?? null; $lsc = $lm ? $getScores($lm) : ['red'=>'','blue'=>'']; @endphp
                <div style="position: relative; min-height: 420px;">
                    <div style="position:absolute; top:52%; transform: translateY(-60%); width: 100%; display:flex; align-items:center;">
                        <table class="match-box-sm">
                            <tr><td class="cell-sm" @if($lm && $isWinner($lm->reg_one_id, $lm)) style="background:#d4edda;" @endif>
                                <div class="indicator-bar-sm red-bg">{{ $lsc['red'] }}</div>
                                <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_one, $lm->firstname_one) : '' !!}</div>
                            </td></tr>
                            <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;{{ $lm && $isWinner($lm->reg_two_id, $lm) ? ' background:#d4edda;' : '' }}">
                                <div class="indicator-bar-sm blue-bg">{{ $lsc['blue'] }}</div>
                                <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_two, $lm->firstname_two) : '' !!}</div>
                            </td></tr>
                        </table>
                        <div style="margin-left: 8px; font-size: 16px;">🥉</div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    @else
<table width="100%" style="margin-top: 20px; border-collapse: collapse; table-layout: fixed;">
    <thead>
        <tr>
            <th class="rep-col-title">R1-д хожигдсон</th>
            <th class="rep-col-title">vs R2-д хожигдсон</th>
            <th class="rep-col-title">Дараагийн шат</th>
            <th class="rep-col-title">vs QF-д хожигдсон</th>
            <th class="rep-col-title">Дагах хагас шигшээ</th>
            <th class="rep-col-title">vs SF-д хожигдсон</th>
            <th class="rep-col-title" style="background: #fff7ed; color: #c2410c;">Хүрэл медаль (1) 🥉</th>
        </tr>
    </thead>

    <tr>
        {{-- COL 1: R1 losers (8 blocks) --}}
        <td width="14.28%" valign="top">
            @for($i=0; $i<8; $i++)
                @php $lm = $_lrv[0][$i] ?? null; $lsc = $lm ? $getScores($lm) : ['red'=>'','blue'=>'']; @endphp
                <div class="rep-wrapper rep32" style="margin-top: 18px;">
                    <table class="match-box-sm">
                        <tr><td class="cell-sm" @if($lm && $isWinner($lm->reg_one_id, $lm)) style="background:#d4edda;" @endif>
                            <div class="indicator-bar-sm red-bg">{{ $lsc['red'] }}</div>
                            <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_one, $lm->firstname_one) : '' !!}</div>
                        </td></tr>
                        <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;{{ $lm && $isWinner($lm->reg_two_id, $lm) ? ' background:#d4edda;' : '' }}">
                            <div class="indicator-bar-sm blue-bg">{{ $lsc['blue'] }}</div>
                            <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_two, $lm->firstname_two) : '' !!}</div>
                        </td></tr>
                    </table>
                    <div class="rep-line {{ $i % 2 == 0 ? 'r-down' : 'r-up' }}" style="height: 52px;"></div>
                </div>
            @endfor
        </td>

        {{-- COL 2: vs R2 losers (8 blocks) --}}
        <td width="14.28%" valign="top">
            @for($i=0; $i<8; $i++)
                @php $lm = $_lrv[1][$i] ?? null; $lsc = $lm ? $getScores($lm) : ['red'=>'','blue'=>'']; @endphp
                <div class="rep-wrapper rep32" style="margin-top: 18px;">
                    <table class="match-box-sm">
                        <tr><td class="cell-sm" @if($lm && $isWinner($lm->reg_one_id, $lm)) style="background:#d4edda;" @endif>
                            <div class="indicator-bar-sm red-bg">{{ $lsc['red'] }}</div>
                            <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_one, $lm->firstname_one) : '' !!}</div>
                        </td></tr>
                        <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;{{ $lm && $isWinner($lm->reg_two_id, $lm) ? ' background:#d4edda;' : '' }}">
                            <div class="indicator-bar-sm blue-bg">{{ $lsc['blue'] }}</div>
                            <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_two, $lm->firstname_two) : '' !!}</div>
                        </td></tr>
                    </table>
                    <div class="rep-line {{ $i % 2 == 0 ? 'r-down' : 'r-up' }}" style="height: 52px;"></div>
                </div>
            @endfor
        </td>

        {{-- COL 3: next stage (4 blocks) --}}
        <td width="14.28%" valign="top">
            @for($i=0; $i<4; $i++)
                @php $lm = $_lrv[2][$i] ?? null; $lsc = $lm ? $getScores($lm) : ['red'=>'','blue'=>'']; @endphp
                <div class="rep-wrapper rep32" style="margin-top: 62px; margin-bottom: 110px;">
                    <table class="match-box-sm">
                        <tr><td class="cell-sm" @if($lm && $isWinner($lm->reg_one_id, $lm)) style="background:#d4edda;" @endif>
                            <div class="indicator-bar-sm red-bg">{{ $lsc['red'] }}</div>
                            <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_one, $lm->firstname_one) : '' !!}</div>
                        </td></tr>
                        <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;{{ $lm && $isWinner($lm->reg_two_id, $lm) ? ' background:#d4edda;' : '' }}">
                            <div class="indicator-bar-sm blue-bg">{{ $lsc['blue'] }}</div>
                            <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_two, $lm->firstname_two) : '' !!}</div>
                        </td></tr>
                    </table>
                    <div class="rep-line-straight"></div>
                </div>
            @endfor
        </td>

        {{-- COL 4: vs QF losers (4 blocks) --}}
        <td width="14.28%" valign="top">
            @for($i=0; $i<4; $i++)
                @php $lm = $_lrv[3][$i] ?? null; $lsc = $lm ? $getScores($lm) : ['red'=>'','blue'=>'']; @endphp
                <div class="rep-wrapper rep32" style="margin-top: 62px; margin-bottom: 110px;">
                    <table class="match-box-sm">
                        <tr><td class="cell-sm" @if($lm && $isWinner($lm->reg_one_id, $lm)) style="background:#d4edda;" @endif>
                            <div class="indicator-bar-sm red-bg">{{ $lsc['red'] }}</div>
                            <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_one, $lm->firstname_one) : '' !!}</div>
                        </td></tr>
                        <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;{{ $lm && $isWinner($lm->reg_two_id, $lm) ? ' background:#d4edda;' : '' }}">
                            <div class="indicator-bar-sm blue-bg">{{ $lsc['blue'] }}</div>
                            <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_two, $lm->firstname_two) : '' !!}</div>
                        </td></tr>
                    </table>
                    <div class="rep-line {{ $i % 2 == 0 ? 'r-down' : 'r-up' }}" style="height: 64px;"></div>
                </div>
            @endfor
        </td>

        {{-- COL 5: follow semi (2 blocks) --}}
        <td width="14.28%" valign="top">
            @for($i=0; $i<2; $i++)
                @php $lm = $_lrv[4][$i] ?? null; $lsc = $lm ? $getScores($lm) : ['red'=>'','blue'=>'']; @endphp
                <div class="rep-wrapper rep32" style="margin-top: 140px; margin-bottom: 300px;">
                    <table class="match-box-sm">
                        <tr><td class="cell-sm" @if($lm && $isWinner($lm->reg_one_id, $lm)) style="background:#d4edda;" @endif>
                            <div class="indicator-bar-sm red-bg">{{ $lsc['red'] }}</div>
                            <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_one, $lm->firstname_one) : '' !!}</div>
                        </td></tr>
                        <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;{{ $lm && $isWinner($lm->reg_two_id, $lm) ? ' background:#d4edda;' : '' }}">
                            <div class="indicator-bar-sm blue-bg">{{ $lsc['blue'] }}</div>
                            <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_two, $lm->firstname_two) : '' !!}</div>
                        </td></tr>
                    </table>
                    <div class="rep-line-straight"></div>
                </div>
            @endfor
        </td>

        {{-- COL 6: SF-L vs RP-W (2 blocks) --}}
        <td width="14.28%" valign="top">
            @for($i=0; $i<2; $i++)
                @php $lm = $_lrv[5][$i] ?? null; $lsc = $lm ? $getScores($lm) : ['red'=>'','blue'=>'']; @endphp
                <div class="rep-wrapper rep32" style="margin-top: 140px; margin-bottom: 300px;">
                    <table class="match-box-sm">
                        <tr><td class="cell-sm" @if($lm && $isWinner($lm->reg_one_id, $lm)) style="background:#d4edda;" @endif>
                            <div class="indicator-bar-sm red-bg">{{ $lsc['red'] }}</div>
                            <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_one, $lm->firstname_one) : '' !!}</div>
                        </td></tr>
                        <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;{{ $lm && $isWinner($lm->reg_two_id, $lm) ? ' background:#d4edda;' : '' }}">
                            <div class="indicator-bar-sm blue-bg">{{ $lsc['blue'] }}</div>
                            <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_two, $lm->firstname_two) : '' !!}</div>
                        </td></tr>
                    </table>
                    <div class="rep-line {{ $i % 2 == 0 ? 'r-down' : 'r-up' }}" style="height: 150px;"></div>
                </div>
            @endfor
        </td>

        {{-- COL 7: FINAL BRONZE (W vs W) -> 1 bronze --}}
        <td width="14.28%" valign="top">
            @php $lm = $_bronzeList[0] ?? null; $lsc = $lm ? $getScores($lm) : ['red'=>'','blue'=>'']; @endphp
            <div style="position: relative; min-height: 720px;">
                <div style="position:absolute; top:50%; transform: translateY(-50%); width:100%; display:flex; align-items:center; padding-right:35px;">
                    <table class="match-box-sm">
                        <tr><td class="cell-sm" @if($lm && $isWinner($lm->reg_one_id, $lm)) style="background:#d4edda;" @endif>
                            <div class="indicator-bar-sm red-bg">{{ $lsc['red'] }}</div>
                            <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_one, $lm->firstname_one) : '' !!}</div>
                        </td></tr>
                        <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;{{ $lm && $isWinner($lm->reg_two_id, $lm) ? ' background:#d4edda;' : '' }}">
                            <div class="indicator-bar-sm blue-bg">{{ $lsc['blue'] }}</div>
                            <div class="player-info-sm">{!! $lm ? $fmtPlayer($lm->lastname_two, $lm->firstname_two) : '' !!}</div>
                        </td></tr>
                    </table>
                    <div style="margin-left: 8px; font-size: 16px;">🥉</div>
                </div>
            </div>
        </td>
    </tr>
</table>

    @endif

    {{-- ========================= SIGNATURE ========================= --}}
    <div style="margin-top: 80px; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px;">
        Ерөнхий шүүгч: ........................................... / ............................. / Огноо: {{ date('Y-m-d') }}
    </div>

</div>

<style>
    /* Column headers */
    .round-title {
        font-size: 11px;
        padding: 10px;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        text-transform: uppercase;
        text-align: center;
        font-weight: bold;
    }

    .rep-col-title {
        font-size: 10px;
        padding: 8px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        font-weight: bold;
        text-align: center;
    }

    /* Match boxes */
    .match-box {
        border-collapse: collapse;
        border: 1.5px solid #64748b;
        background: white;
        width: 100%;
        z-index: 2;
        position: relative; /* IMPORTANT for match-label */
    }

    .match-box-sm {
        border-collapse: collapse;
        border: 1.2px solid #94a3b8;
        background: white;
        width: 100%;
        z-index: 2;
        position: relative; /* IMPORTANT for match-label */
    }

    .player-cell { height: 50px; padding: 0; font-size: 11px; }
    .cell-sm { height: 32px; font-size: 10px; }

    .indicator-bar {
        width: 28px;
        height: 50px;
        float: left;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 10px;
        margin-right: 10px;
    }

    .indicator-bar-sm {
        width: 24px;
        height: 32px;
        float: left;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 9px;
        margin-right: 8px;
        flex-shrink: 0;
    }

    .red-bg { background-color: #e11d48; }
    .blue-bg { background-color: #2563eb; }

    .player-info { padding-top: 8px; line-height: 1.2; }
    .player-info-sm { overflow: hidden; white-space: nowrap; text-overflow: ellipsis; padding-top: 8px; font-size: 9px; line-height: 1.2; }
    .p-name { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .p-academy { font-size: 9px; color: #666; font-weight: normal; margin-top: 2px; }

    .connector-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .rep-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        padding-right: 35px;
    }

    /* Winner bracket connector */
    .line-logic {
        position: absolute;
        right: 0;
        width: 40px;
        border-right: 1.5px solid #94a3b8;
        z-index: 1;
    }
    .line-logic::before {
        content: '';
        position: absolute;
        top: 50%;
        left: -40px;
        width: 40px;
        height: 1.5px;
        background: #94a3b8;
    }
    .line-logic::after {
        content: '';
        position: absolute;
        width: 15px;
        height: 1.5px;
        background: #94a3b8;
        right: -15px;
    }

    .l-down { top: 50%; border-top: 1.5px solid #94a3b8; }
    .l-down::after { top: 100%; }

    .l-up { bottom: 50%; border-bottom: 1.5px solid #94a3b8; }
    .l-up::after { bottom: 100%; }

    /* Repechage connector */
    .rep-line {
        position: absolute;
        right: 0;
        width: 35px;
        border-right: 1.5px solid #94a3b8;
        z-index: 1;
    }
    .rep-line::before {
        content: '';
        position: absolute;
        top: 50%;
        left: -35px;
        width: 35px;
        height: 1.5px;
        background: #94a3b8;
    }
    .r-down { top: 50%; height: 60px; border-top: 1.5px solid #94a3b8; }
    .r-up   { bottom: 50%; height: 60px; border-bottom: 1.5px solid #94a3b8; }

    .rep-line-straight {
        position: absolute;
        right: 0;
        width: 35px;
        height: 1.5px;
        background: #94a3b8;
        top: 50%;
    }

    .repechage-header {
        background: #334155;
        color: white;
        padding: 10px 15px;
        font-weight: bold;
        margin-top: 50px;
        text-transform: uppercase;
        font-size: 13px;
    }
</style>

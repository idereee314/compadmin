@php
    /**
     * 1. ӨГӨГДӨЛ БОЛОВСРУУЛАХ (DATA PREPARATION)
     * $members коллекцоос ирж буй тамирчдыг нэг массив руу цуглуулна.
     */
    $all_athletes = [];
    if (isset($members) && count($members) > 0) {
        foreach($members as $m) {
            // Эхний тамирчны мэдээллийг авах
            if(isset($m->lastname_one) && $m->lastname_one != null) {
                $all_athletes[] = [
                    'lastname'  => $m->lastname_one,
                    'firstname' => $m->firstname_one,
                    'academy'   => $m->acname_one ?? ''
                ];
            } 
            // Хэрэв нэг мөрөнд хоёр дахь тамирчин байгаа бол (image_667590.png шиг бүтэцтэй бол)
            if(isset($m->lastname_two) && $m->lastname_two != null) {
                $all_athletes[] = [
                    'lastname'  => $m->lastname_two,
                    'firstname' => $m->firstname_two,
                    'academy'   => $m->acname_two ?? ''
                ];
            }
        }
    }

    /**
     * 2. ХЭСЭГТ ХУВААХ (POOL SPLITTING)
     * Snake seeding: Pool 1 = Seed 1, Seed 4, Seed 5 / Pool 2 = Seed 2, Seed 3, Seed 6
     */
    $p1 = [$all_athletes[0], $all_athletes[3], $all_athletes[4]];
    $p2 = [$all_athletes[1], $all_athletes[2], $all_athletes[5]];

    // Pool 1-ийн оноолт (Match 1, 3, 5)
    $matches1 = [
        ['r' => $p1[0] ?? null, 'b' => $p1[1] ?? null, 'l' => 'Match 1', 'order' => 1],
        ['r' => $p1[0] ?? null, 'b' => $p1[2] ?? null, 'l' => 'Match 3', 'order' => 3],
        ['r' => $p1[1] ?? null, 'b' => $p1[2] ?? null, 'l' => 'Match 5', 'order' => 5]
    ];

    // Pool 2-ийн оноолт (Match 2, 4, 6)
    $matches2 = [
        ['r' => $p2[0] ?? null, 'b' => $p2[1] ?? null, 'l' => 'Match 2', 'order' => 2],
        ['r' => $p2[0] ?? null, 'b' => $p2[2] ?? null, 'l' => 'Match 4', 'order' => 4],
        ['r' => $p2[1] ?? null, 'b' => $p2[2] ?? null, 'l' => 'Match 6', 'order' => 6]
    ];
@endphp

{{-- Match lookup variables ($matchByOrder, $loserMatchesList, etc.) provided by controller --}}

@php
    $sf1 = $matchByOrder[201] ?? null; $sf1sc = $sf1 ? $getScores($sf1) : ['red'=>'','blue'=>''];
    $sf2 = $matchByOrder[202] ?? null; $sf2sc = $sf2 ? $getScores($sf2) : ['red'=>'','blue'=>''];
    $fin = $matchByOrder[9999] ?? null; $finsc = $fin ? $getScores($fin) : ['red'=>'','blue'=>''];
@endphp

<div style="width:1150px; margin:0 auto; background-color: white; border: 1px solid #ccc; padding: 40px; font-family: 'Helvetica', 'Arial', sans-serif; color: #333;">
    
    <div style="border-bottom: 2px solid #2563eb; margin-bottom: 25px; padding-bottom: 10px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2 style="margin:0; font-size: 20px;">{{ @$eventConfig->event->name }}</h2>
            <div style="font-size: 13px; color: #444; margin-top: 5px; font-weight: bold;">
                {{ @$entry->name }} | {{ Config::get("enums.gender_code")[@$entry->gender_code] }} | {{ @$age->name }} | {{ @$weight->weight }}кг | {{ date_format(date_create(@$eventConfig->event->event_date), 'Y-m-d') }}
            </div>
        </div>
        <div style="text-align: right; font-size: 11px; font-weight: bold; color: #2563eb;">
            5-6 тамирчинтай оноолт
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; gap: 50px; margin-bottom: 50px;">
        
        <div style="flex: 1;">
            <div style="background: #f8fafc; padding: 10px; font-weight: bold; border: 1px solid #cbd5e1; margin-bottom: 20px; border-left: 5px solid #ef4444;">
                Тойргоор (Round Robin) Хэсэг 1:
            </div>
            @foreach($matches1 as $m)
                @php $md = $matchByOrder[$m['order']] ?? null; $sc = $md ? $getScores($md) : ['red'=>'','blue'=>'']; @endphp
                <div class="match-container">
                    <div class="match-label">{{ $m['l'] }}</div>
                    <table class="match-table">
                        <tr>
                            <td class="player-cell" @if($md && $isWinner($md->reg_one_id, $md)) style="background:#d4edda;" @endif>
                                <div class="side-bar red"></div>
                                <div class="p-data">
                                    @if($m['r'])
                                        {{ $m['r']['lastname'] }} <strong>{{ $m['r']['firstname'] }}</strong>
                                        <span>{{ $m['r']['academy'] }}</span>
                                    @else <span class="bye">BYE / Хоосон</span> @endif
                                </div>
                            </td>
                            <td class="score-cell">{{ $sc['red'] }}</td>
                        </tr>
                        <tr>
                            <td class="player-cell" style="border-top: 1px solid #e2e8f0;{{ $md && $isWinner($md->reg_two_id, $md) ? ' background:#d4edda;' : '' }}">
                                <div class="side-bar blue"></div>
                                <div class="p-data">
                                    @if($m['b'])
                                        {{ $m['b']['lastname'] }} <strong>{{ $m['b']['firstname'] }}</strong>
                                        <span>{{ $m['b']['academy'] }}</span>
                                    @else <span class="bye">BYE / Хоосон</span> @endif
                                </div>
                            </td>
                            <td class="score-cell" style="border-top: 1px solid #e2e8f0;">{{ $sc['blue'] }}</td>
                        </tr>
                    </table>
                </div>
            @endforeach
        </div>

        <div style="flex: 1;">
            <div style="background: #f8fafc; padding: 10px; font-weight: bold; border: 1px solid #cbd5e1; margin-bottom: 20px; border-left: 5px solid #3b82f6;">
                Тойргоор (Round Robin) Хэсэг 2:
            </div>
            @foreach($matches2 as $m)
                @php $md = $matchByOrder[$m['order']] ?? null; $sc = $md ? $getScores($md) : ['red'=>'','blue'=>'']; @endphp
                <div class="match-container">
                    <div class="match-label">{{ $m['l'] }}</div>
                    <table class="match-table">
                        <tr>
                            <td class="player-cell" @if($md && $isWinner($md->reg_one_id, $md)) style="background:#d4edda;" @endif>
                                <div class="side-bar red"></div>
                                <div class="p-data">
                                    @if($m['r'])
                                        {{ $m['r']['lastname'] }} <strong>{{ $m['r']['firstname'] }}</strong>
                                        <span>{{ $m['r']['academy'] }}</span>
                                    @else <span class="bye">BYE / Хоосон</span> @endif
                                </div>
                            </td>
                            <td class="score-cell">{{ $sc['red'] }}</td>
                        </tr>
                        <tr>
                            <td class="player-cell" style="border-top: 1px solid #e2e8f0;{{ $md && $isWinner($md->reg_two_id, $md) ? ' background:#d4edda;' : '' }}">
                                <div class="side-bar blue"></div>
                                <div class="p-data">
                                    @if($m['b'])
                                        {{ $m['b']['lastname'] }} <strong>{{ $m['b']['firstname'] }}</strong>
                                        <span>{{ $m['b']['academy'] }}</span>
                                    @else <span class="bye">BYE / Хоосон</span> @endif
                                </div>
                            </td>
                            <td class="score-cell" style="border-top: 1px solid #e2e8f0;">{{ $sc['blue'] }}</td>
                        </tr>
                    </table>
                </div>
            @endforeach
        </div>
    </div>

    <div style="text-align: center; margin: -20px 0 40px 0; font-size: 13px; font-style: italic; color: #64748b;">
        6 тамирчныг 3, 3-аар нь тойргоор барилдах 2 хэсэгт хуваана.
    </div>

    <div style="display: flex; align-items: center; position: relative; padding: 20px 0;">
        
        <div style="width: 300px;">
            <div class="ko-box" style="margin-bottom: 80px;">
                <div class="match-label">Match 7 (Semi)</div>
                <table class="match-table">
                    <tr>
                        <td class="player-cell" @if($sf1 && $isWinner($sf1->reg_one_id, $sf1)) style="background:#d4edda;" @endif>
                            <div class="side-bar red"></div>
                            <div class="p-data">
                                @if($sf1 && !empty($sf1->lastname_one))
                                    {{ $sf1->lastname_one }} <strong>{{ $sf1->firstname_one }}</strong>
                                    <span>{{ $sf1->acname_one ?? '' }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="score-cell">{{ $sf1sc['red'] }}</td>
                    </tr>
                    <tr>
                        <td class="player-cell" style="border-top: 1px solid #eee;{{ $sf1 && $isWinner($sf1->reg_two_id, $sf1) ? ' background:#d4edda;' : '' }}">
                            <div class="side-bar blue"></div>
                            <div class="p-data">
                                @if($sf1 && !empty($sf1->lastname_two))
                                    {{ $sf1->lastname_two }} <strong>{{ $sf1->firstname_two }}</strong>
                                    <span>{{ $sf1->acname_two ?? '' }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="score-cell" style="border-top: 1px solid #eee;">{{ $sf1sc['blue'] }}</td>
                    </tr>
                </table>
                <div class="line-to-right down"></div>
            </div>
            <div class="ko-box">
                <div class="match-label">Match 8 (Semi)</div>
                <table class="match-table">
                    <tr>
                        <td class="player-cell" @if($sf2 && $isWinner($sf2->reg_one_id, $sf2)) style="background:#d4edda;" @endif>
                            <div class="side-bar red"></div>
                            <div class="p-data">
                                @if($sf2 && !empty($sf2->lastname_one))
                                    {{ $sf2->lastname_one }} <strong>{{ $sf2->firstname_one }}</strong>
                                    <span>{{ $sf2->acname_one ?? '' }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="score-cell">{{ $sf2sc['red'] }}</td>
                    </tr>
                    <tr>
                        <td class="player-cell" style="border-top: 1px solid #eee;{{ $sf2 && $isWinner($sf2->reg_two_id, $sf2) ? ' background:#d4edda;' : '' }}">
                            <div class="side-bar blue"></div>
                            <div class="p-data">
                                @if($sf2 && !empty($sf2->lastname_two))
                                    {{ $sf2->lastname_two }} <strong>{{ $sf2->firstname_two }}</strong>
                                    <span>{{ $sf2->acname_two ?? '' }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="score-cell" style="border-top: 1px solid #eee;">{{ $sf2sc['blue'] }}</td>
                    </tr>
                </table>
                <div class="line-to-right up"></div>
            </div>
        </div>

        <div style="width: 300px; margin-left: 85px;">
            <div class="ko-box">
                <div class="match-label" style="text-align: center; color: #1e293b;">Match 9 - FINAL</div>
                <table class="match-table" style="border: 2px solid #000;">
                    <tr>
                        <td class="player-cell" style="height: 50px;{{ $fin && $isWinner($fin->reg_one_id, $fin) ? ' background:#d4edda;' : '' }}">
                            <div class="side-bar red"></div>
                            <div class="p-data" style="font-weight: bold;">
                                @if($fin && !empty($fin->lastname_one))
                                    {{ $fin->lastname_one }} <strong>{{ $fin->firstname_one }}</strong>
                                    <span>{{ $fin->acname_one ?? '' }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="score-cell" style="font-weight: bold;">{{ $finsc['red'] }}</td>
                    </tr>
                    <tr>
                        <td class="player-cell" style="height: 50px; border-top: 2px solid #000;{{ $fin && $isWinner($fin->reg_two_id, $fin) ? ' background:#d4edda;' : '' }}">
                            <div class="side-bar blue"></div>
                            <div class="p-data" style="font-weight: bold;">
                                @if($fin && !empty($fin->lastname_two))
                                    {{ $fin->lastname_two }} <strong>{{ $fin->firstname_two }}</strong>
                                    <span>{{ $fin->acname_two ?? '' }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="score-cell" style="font-weight: bold; border-top: 2px solid #000;">{{ $finsc['blue'] }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div style="margin-top: 60px; border-top: 1px solid #ddd; padding-top: 20px; font-size: 14px;">
        Ерөнхий шүүгч ................................................. / ......................................... / Огноо: .....................
    </div>
</div>

<style>
    .match-container { margin-bottom: 25px; width: 100%; }
    .match-label { font-size: 11px; font-weight: bold; color: #94a3b8; margin-bottom: 5px; text-transform: uppercase; }
    .match-table { width: 100%; border-collapse: collapse; border: 1px solid #475569; background: #fff; }
    .player-cell { height: 48px; padding: 0; position: relative; }
    .side-bar { width: 6px; height: 100%; float: left; }
    .side-bar.red { background: #ef4444; }
    .side-bar.blue { background: #3b82f6; }
    .p-data { padding: 5px 12px; line-height: 1.3; font-size: 12px; }
    .p-data strong { font-size: 13px; color: #000; display: inline-block; }
    .p-data span { display: block; font-size: 10px; color: #64748b; }
    .bye { color: #cbd5e1; font-style: italic; }
    .score-cell { width: 50px; background: #f1f5f9; border-left: 1px solid #475569; text-align: center; font-weight: bold; font-size: 13px; }

    .ko-box { position: relative; width: 100%; }
    .line-to-right { position: absolute; right: -80px; width: 80px; border-right: 2px solid #94a3b8; }
    .line-to-right.down { top: 50%; height: 75px; border-top: 2px solid #94a3b8; }
    .line-to-right.up { bottom: 50%; height: 75px; border-bottom: 2px solid #94a3b8; }

    .rank-box { width: 180px; padding: 12px; margin-bottom: 10px; border-radius: 6px; font-weight: bold; text-align: center; border: 1px solid #cbd5e1; font-size: 13px; }
    .gold { background: #fefce8; border-color: #facc15; color: #854d0e; }
    .silver { background: #f8fafc; border-color: #cbd5e1; color: #475569; }
    .bronze { background: #fff7ed; border-color: #fdba74; color: #9a3412; }
</style>
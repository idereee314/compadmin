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

<div style="width:1100px; margin:0 auto; background-color: white; border: 1px solid #ccc; padding: 40px; font-family: sans-serif; color: #333;">

    <div style="border-bottom: 2px solid #e11d48; margin-bottom: 25px; padding-bottom: 10px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2 style="margin:0; font-size: 22px;">{{ @$eventConfig->event->name }}</h2>
            <div style="font-size: 14px; color: #444; margin-top: 5px; font-weight: bold;">
                {{ @$entry->name }} | {{ @$age->name }} | {{ @$weight->weight }}кг | {{ Config::get("enums.gender_code")[@$entry->gender_code] }} | {{ date_format(date_create(@$eventConfig->event->event_date), 'Y-m-d') }}
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
                ['r' => $t1, 'b' => $t2, 'l' => 'Match 1'],
                ['r' => $t2, 'b' => $t1, 'l' => 'Match 2'],
                ['r' => $t1, 'b' => $t2, 'l' => 'Match 3 (Хэрэв шаардлагатай бол)']
            ];
        @endphp
        
        <div style="max-width: 700px; margin: 0 auto;">
            <h3 style="text-align: center; background: #f1f5f9; padding: 10px; border: 1px solid #cbd5e1; font-size: 16px;">BEST OF THREE (2 ТАМИРЧИН)</h3>
            @foreach($bo3_list as $m)
                <div style="margin-bottom: 25px;">
                    <div style="font-size: 11px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px;">{{ $m['l'] }}</div>
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #475569;">
                        <tr>
                            <td style="height: 50px; padding: 0;">
                                <div style="width: 8px; height: 100%; background: #ef4444; float: left;"></div>
                                <div style="padding: 5px 15px;">
                                    {{ $m['r']['lastname'] }} <strong>{{ $m['r']['firstname'] }}</strong>
                                    <span style="display: block; font-size: 11px; color: #64748b;">{{ $m['r']['academy'] }}</span>
                                </div>
                            </td>
                            <td style="width: 70px; background: #f8fafc; border-left: 1px solid #475569;"></td>
                        </tr>
                        <tr>
                            <td style="height: 50px; padding: 0; border-top: 1px solid #e2e8f0;">
                                <div style="width: 8px; height: 100%; background: #3b82f6; float: left;"></div>
                                <div style="padding: 5px 15px;">
                                    {{ $m['b']['lastname'] }} <strong>{{ $m['b']['firstname'] }}</strong>
                                    <span style="display: block; font-size: 11px; color: #64748b;">{{ $m['b']['academy'] }}</span>
                                </div>
                            </td>
                            <td style="width: 70px; background: #f8fafc; border-left: 1px solid #475569;"></td>
                        </tr>
                    </table>
                </div>
            @endforeach
        </div>

    {{-- CASE 2: 3-5 ТАМИРЧИНТАЙ БОЛ (Single Pool Round Robin) --}}
    @elseif($count > 2 && $count <= 5)
        @php
            if ($count == 3) {
                $matches = [
                    ['r' => $all_athletes[0], 'b' => $all_athletes[1], 'l' => 'Match 1'],
                    ['r' => $all_athletes[0], 'b' => $all_athletes[2], 'l' => 'Match 2'],
                    ['r' => $all_athletes[1], 'b' => $all_athletes[2], 'l' => 'Match 3'],
                ];
            } elseif ($count == 4) {
                // 4 players: C(4,2) = 6 matches
                $matches = [
                    ['r' => $all_athletes[0], 'b' => $all_athletes[1], 'l' => 'Match 1'],
                    ['r' => $all_athletes[2], 'b' => $all_athletes[3], 'l' => 'Match 2'],
                    ['r' => $all_athletes[0], 'b' => $all_athletes[2], 'l' => 'Match 3'],
                    ['r' => $all_athletes[1], 'b' => $all_athletes[3], 'l' => 'Match 4'],
                    ['r' => $all_athletes[0], 'b' => $all_athletes[3], 'l' => 'Match 5'],
                    ['r' => $all_athletes[1], 'b' => $all_athletes[2], 'l' => 'Match 6'],
                ];
            } else {
                // 5 players: C(5,2) = 10 matches — every player vs every other player
                $matches = [
                    ['r' => $all_athletes[0], 'b' => $all_athletes[1], 'l' => 'Match 1'],
                    ['r' => $all_athletes[2], 'b' => $all_athletes[3], 'l' => 'Match 2'],
                    ['r' => $all_athletes[0], 'b' => $all_athletes[4], 'l' => 'Match 3'],
                    ['r' => $all_athletes[1], 'b' => $all_athletes[2], 'l' => 'Match 4'],
                    ['r' => $all_athletes[3], 'b' => $all_athletes[4], 'l' => 'Match 5'],
                    ['r' => $all_athletes[0], 'b' => $all_athletes[2], 'l' => 'Match 6'],
                    ['r' => $all_athletes[1], 'b' => $all_athletes[4], 'l' => 'Match 7'],
                    ['r' => $all_athletes[0], 'b' => $all_athletes[3], 'l' => 'Match 8'],
                    ['r' => $all_athletes[2], 'b' => $all_athletes[4], 'l' => 'Match 9'],
                    ['r' => $all_athletes[1], 'b' => $all_athletes[3], 'l' => 'Match 10'],
                ];
            }
        @endphp
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
            @foreach($matches as $m)
                <div style="margin-bottom: 10px;">
                    <div style="font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 3px;">{{ $m['l'] }}</div>
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #475569;">
                        <tr>
                            <td style="height: 44px; padding: 0; position: relative;">
                                <div style="width: 6px; height: 100%; background: #ef4444; float: left;"></div>
                                <div style="padding: 4px 10px; line-height: 1.2;">
                                    {{ $m['r']['lastname'] }} <strong>{{ $m['r']['firstname'] }}</strong>
                                    <div style="font-size: 10px; color: #64748b;">{{ $m['r']['academy'] }}</div>
                                </div>
                            </td>
                            <td style="width: 50px; background: #f1f5f9; border-left: 1px solid #475569;"></td>
                        </tr>
                        <tr>
                            <td style="height: 44px; padding: 0; border-top: 1px solid #e2e8f0;">
                                <div style="width: 6px; height: 100%; background: #3b82f6; float: left;"></div>
                                <div style="padding: 4px 10px; line-height: 1.2;">
                                    {{ $m['b']['lastname'] }} <strong>{{ $m['b']['firstname'] }}</strong>
                                    <div style="font-size: 10px; color: #64748b;">{{ $m['b']['academy'] }}</div>
                                </div>
                            </td>
                            <td style="width: 50px; background: #f1f5f9; border-left: 1px solid #475569;"></td>
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
    
            // Pool A: Match 1,3,5
            $matchesA = [
                ['r' => $poolA[0] ?? null, 'b' => $poolA[1] ?? null, 'l' => 'Match 1'],
                ['r' => $poolA[0] ?? null, 'b' => $poolA[2] ?? null, 'l' => 'Match 3'],
                ['r' => $poolA[1] ?? null, 'b' => $poolA[2] ?? null, 'l' => 'Match 5'],
            ];
    
            // Pool B: Match 2,4,6
            $matchesB = [
                ['r' => $poolB[0] ?? null, 'b' => $poolB[1] ?? null, 'l' => 'Match 2'],
                ['r' => $poolB[0] ?? null, 'b' => $poolB[2] ?? null, 'l' => 'Match 4'],
                ['r' => $poolB[1] ?? null, 'b' => $poolB[2] ?? null, 'l' => 'Match 6'],
            ];
        @endphp
    
        {{-- POOLS --}}
        <div style="display:flex; justify-content:space-between; gap: 40px; margin-bottom: 35px;">
            {{-- POOL A --}}
            <div style="flex:1;">
                <div style="background:#f8fafc; padding:10px; font-weight:bold; border:1px solid #cbd5e1; margin-bottom:15px; border-left:6px solid #ef4444;">
                    Pool A - Тойргоор (Round Robin)
                </div>
    
                @foreach($matchesA as $m)
                    <div style="margin-bottom: 14px;">
                        <div style="font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 3px;">
                            {{ $m['l'] }}
                        </div>
    
                        <table style="width: 100%; border-collapse: collapse; border: 1px solid #475569; background:#fff;">
                            <tr>
                                <td style="height: 44px; padding: 0;">
                                    <div style="width: 6px; height: 100%; background: #ef4444; float: left;"></div>
                                    <div style="padding: 4px 10px; line-height: 1.2;">
                                        @if($m['r'])
                                            {{ $m['r']['lastname'] }} <strong>{{ $m['r']['firstname'] }}</strong>
                                            <div style="font-size: 10px; color: #64748b;">{{ $m['r']['academy'] }}</div>
                                        @else
                                            <div style="font-size: 11px; color:#cbd5e1; font-style:italic;">BYE / Хоосон</div>
                                        @endif
                                    </div>
                                </td>
                                <td style="width: 50px; background: #f1f5f9; border-left: 1px solid #475569;"></td>
                            </tr>
    
                            <tr>
                                <td style="height: 44px; padding: 0; border-top: 1px solid #e2e8f0;">
                                    <div style="width: 6px; height: 100%; background: #3b82f6; float: left;"></div>
                                    <div style="padding: 4px 10px; line-height: 1.2;">
                                        @if($m['b'])
                                            {{ $m['b']['lastname'] }} <strong>{{ $m['b']['firstname'] }}</strong>
                                            <div style="font-size: 10px; color: #64748b;">{{ $m['b']['academy'] }}</div>
                                        @else
                                            <div style="font-size: 11px; color:#cbd5e1; font-style:italic;">BYE / Хоосон</div>
                                        @endif
                                    </div>
                                </td>
                                <td style="width: 50px; background: #f1f5f9; border-left: 1px solid #475569;"></td>
                            </tr>
                        </table>
                    </div>
                @endforeach
            </div>
    
            {{-- POOL B --}}
            <div style="flex:1;">
                <div style="background:#f8fafc; padding:10px; font-weight:bold; border:1px solid #cbd5e1; margin-bottom:15px; border-left:6px solid #3b82f6;">
                    Pool B - Тойргоор (Round Robin)
                </div>
    
                @foreach($matchesB as $m)
                    <div style="margin-bottom: 14px;">
                        <div style="font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 3px;">
                            {{ $m['l'] }}
                        </div>
    
                        <table style="width: 100%; border-collapse: collapse; border: 1px solid #475569; background:#fff;">
                            <tr>
                                <td style="height: 44px; padding: 0;">
                                    <div style="width: 6px; height: 100%; background: #ef4444; float: left;"></div>
                                    <div style="padding: 4px 10px; line-height: 1.2;">
                                        @if($m['r'])
                                            {{ $m['r']['lastname'] }} <strong>{{ $m['r']['firstname'] }}</strong>
                                            <div style="font-size: 10px; color: #64748b;">{{ $m['r']['academy'] }}</div>
                                        @else
                                            <div style="font-size: 11px; color:#cbd5e1; font-style:italic;">BYE / Хоосон</div>
                                        @endif
                                    </div>
                                </td>
                                <td style="width: 50px; background: #f1f5f9; border-left: 1px solid #475569;"></td>
                            </tr>
    
                            <tr>
                                <td style="height: 44px; padding: 0; border-top: 1px solid #e2e8f0;">
                                    <div style="width: 6px; height: 100%; background: #3b82f6; float: left;"></div>
                                    <div style="padding: 4px 10px; line-height: 1.2;">
                                        @if($m['b'])
                                            {{ $m['b']['lastname'] }} <strong>{{ $m['b']['firstname'] }}</strong>
                                            <div style="font-size: 10px; color: #64748b;">{{ $m['b']['academy'] }}</div>
                                        @else
                                            <div style="font-size: 11px; color:#cbd5e1; font-style:italic;">BYE / Хоосон</div>
                                        @endif
                                    </div>
                                </td>
                                <td style="width: 50px; background: #f1f5f9; border-left: 1px solid #475569;"></td>
                            </tr>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    
        <div style="text-align:center; margin-top: 10px; font-weight:bold; font-size: 13px; color:#64748b;">
            * 6 тамирчныг 3,3-аар нь 2 хэсэгт хувааж тойргоор барилдуулна (Pool A / Pool B).
        </div>
    
        {{-- BRACKET (хоосон хайрцаг - гараар бөглөх) --}}
        <div style="display:flex; align-items:flex-start; justify-content:center; gap: 80px; margin-top: 35px; position:relative;">
            <div style="width: 360px;">
                <!-- MATCH 7 -->
                <div style="margin-bottom: 35px; position:relative;">
                    <div style="font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 3px;">Match 7 (Semi)</div>

                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #111827; background:#fff;">
                        <tr>
                            <td style="height: 46px; padding: 0;">
                                <div style="width: 6px; height: 100%; background:#ef4444; float:left;"></div>
                                <div style="padding: 5px 12px;"></div>
                            </td>
                            <td style="width: 60px; background: #f1f5f9; border-left: 1px solid #111827;"></td>
                        </tr>
                        <tr>
                            <td style="height: 46px; padding: 0; border-top: 1px solid #e2e8f0;">
                                <div style="width: 6px; height: 100%; background:#3b82f6; float:left;"></div>
                                <div style="padding: 5px 12px;"></div>
                            </td>
                            <td style="width: 60px; background: #f1f5f9; border-left: 1px solid #111827;"></td>
                        </tr>
                    </table>

                    <!-- CONNECTOR: Match 7 -> Final TOP (зураг дээрх шиг) -->
                    <!-- 1) баруун тийш гарна -->
                    <div style="position:absolute; right:-60px; top:50%; width:60px; border-top:1px solid #94a3b8;"></div>
                    <!-- 2) доош бууна (final-ийн дээд мөр хүртэл) -->
                    <div style="position:absolute; right:-60px; top:50%; height:60px; border-right:1px solid #94a3b8;"></div>
                    
                    <!-- 3) final руу орох -->
                    <div style="position:absolute; right:-80px; top:calc(50% + 60px); width:20px; border-top:1px solid #94a3b8;"></div>
                    
                </div>

                <!-- MATCH 8 -->
                <div style="position:relative;">
                    <div style="font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 3px;">Match 8 (Semi)</div>

                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #111827; background:#fff;">
                        <tr>
                            <td style="height: 46px; padding: 0;">
                                <div style="width: 6px; height: 100%; background:#ef4444; float:left;"></div>
                                <div style="padding: 5px 12px;"></div>
                            </td>
                            <td style="width: 60px; background: #f1f5f9; border-left: 1px solid #111827;"></td>
                        </tr>
                        <tr>
                            <td style="height: 46px; padding: 0; border-top: 1px solid #e2e8f0;">
                                <div style="width: 6px; height: 100%; background:#3b82f6; float:left;"></div>
                                <div style="padding: 5px 12px;"></div>
                            </td>
                            <td style="width: 60px; background: #f1f5f9; border-left: 1px solid #111827;"></td>
                        </tr>
                    </table>

                    <!-- CONNECTOR: Match 8 -> Final BOTTOM (зураг дээрх шиг) -->
                    <!-- 1) баруун тийш гарна -->
                    <div style="position:absolute; right:-60px; top:50%; width:60px; border-top:1px solid #94a3b8;"></div>
                    <!-- 2) дээш гарна (final-ийн доод мөр хүртэл) -->
                    <div style="position:absolute; right:-60px; top:calc(50% - 60px); height:60px; border-right:1px solid #94a3b8;"></div>
                    <!-- 3) final руу орох -->
                    <div style="position:absolute; right:-80px; top:calc(50% - 60px); width:20px; border-top:1px solid #94a3b8;"></div>
                </div>
            </div>

            <!-- RIGHT COLUMN (FINAL) -->
            <div style="width: 360px; margin-top: 60px;">
                <div style="font-size: 10px; font-weight: bold; color: #0f172a; text-transform: uppercase; margin-bottom: 6px; text-align:center;">
                    Match 9 (FINAL)
                </div>

                <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; background:#fff;">
                    <tr>
                        <td style="height: 52px; padding: 0;">
                            <div style="width: 6px; height: 100%; background:#ef4444; float:left;"></div>
                            <div style="padding: 6px 12px; font-weight:bold;"></div>
                        </td>
                        <td style="width: 70px; background: #f1f5f9; border-left: 1px solid #000;"></td>
                    </tr>
                    <tr>
                        <td style="height: 52px; padding: 0; border-top: 1px solid #000;">
                            <div style="width: 6px; height: 100%; background:#3b82f6; float:left;"></div>
                            <div style="padding: 6px 12px; font-weight:bold;"></div>
                        </td>
                        <td style="width: 70px; background: #f1f5f9; border-left: 1px solid #000;"></td>
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


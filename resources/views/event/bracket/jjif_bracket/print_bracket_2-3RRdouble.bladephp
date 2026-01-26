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
            {{ $count }} ТАМИРЧИНТАЙ ОНООЛТ ({{ $count <= 4 ? 'ROUND ROBIN' : 'POOLS' }})
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

    {{-- CASE 2: 3-4 ТАМИРЧИНТАЙ БОЛ (Single Pool Round Robin) --}}
    @elseif($count > 2 && $count <= 4)
        @php
            if ($count == 3) {
                $matches = [
                    ['r' => $all_athletes[0], 'b' => $all_athletes[1], 'l' => 'Match 1'],
                    ['r' => $all_athletes[0], 'b' => $all_athletes[2], 'l' => 'Match 2'],
                    ['r' => $all_athletes[1], 'b' => $all_athletes[2], 'l' => 'Match 3'],
                ];
            } else {
                $matches = [
                    ['r' => $all_athletes[0], 'b' => $all_athletes[1], 'l' => 'Match 1'],
                    ['r' => $all_athletes[2], 'b' => $all_athletes[3], 'l' => 'Match 2'],
                    ['r' => $all_athletes[0], 'b' => $all_athletes[2], 'l' => 'Match 3'],
                    ['r' => $all_athletes[1], 'b' => $all_athletes[3], 'l' => 'Match 4'],
                    ['r' => $all_athletes[0], 'b' => $all_athletes[3], 'l' => 'Match 5'],
                    ['r' => $all_athletes[1], 'b' => $all_athletes[2], 'l' => 'Match 6'],
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

    {{-- CASE 3: 5-6 ТАМИРЧИНТАЙ БОЛ (Pools + Bracket) --}}
    @else
        <div style="text-align: center; padding: 100px; color: #64748b; border: 2px dashed #e2e8f0; border-radius: 8px;">
            <h3 style="margin:0;">5-6 тамирчны оноолтын хэсэг</h3>
            <p>Энд хэсгийн (Pool A, Pool B) барилдаануудыг харуулна.</p>
        </div>
    @endif

    <div style="margin-top: 50px;">
        <table style="width: 100%; border-collapse: collapse; border: 2px solid #000; text-align: center;">
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
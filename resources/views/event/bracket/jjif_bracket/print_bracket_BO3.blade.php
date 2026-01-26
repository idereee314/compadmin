@php
    /**
     * 1. ӨГӨГДӨЛ БОЛОВСРУУЛАХ (DATA PREPARATION)
     * Энэ хэсэг нь бүх тохиолдолд (2, 4, 6 хүн) ажиллана.
     */
    $all_athletes = [];
    if (isset($members) && count($members) > 0) {
        foreach($members as $m) {
            if(isset($m->lastname_one) && $m->lastname_one != null) {
                $all_athletes[] = [
                    'lastname'  => $m->lastname_one,
                    'firstname' => $m->firstname_one,
                    'academy'   => $m->acname_one ?? ''
                ];
            } 
            if(isset($m->lastname_two) && $m->lastname_two != null) {
                $all_athletes[] = [
                    'lastname'  => $m->lastname_two,
                    'firstname' => $m->firstname_two,
                    'academy'   => $m->acname_two ?? ''
                ];
            }
        }
    }

    $count = count($all_athletes);
@endphp

<div style="width:1100px; margin:0 auto; background-color: white; border: 1px solid #ccc; padding: 40px; font-family: sans-serif;">

    <div style="border-bottom: 2px solid #e11d48; margin-bottom: 25px; padding-bottom: 10px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2 style="margin:0; font-size: 22px;">{{ @$eventConfig->event->name }}</h2>
            <div style="font-size: 14px; color: #444; margin-top: 5px; font-weight: bold;">
                {{ @$entry->name }} | {{ @$age->name }} | {{ @$weight->weight }}кг | {{ Config::get("enums.gender_code")[@$entry->gender_code] }} | {{ date_format(date_create(@$eventConfig->event->event_date), 'Y-m-d') }}
            </div>
        </div>
        <div style="text-align: right; font-size: 12px; font-weight: bold; color: #e11d48;">
            2 ТАМИРЧИНТАЙ ОНООЛТ
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
        
        <div style="max-width: 600px; margin: 0 auto;">
            <h3 style="text-align: center; background: #f1f5f9; padding: 10px; border: 1px solid #cbd5e1;">BEST OF THREE (2 ТАМИРЧИН)</h3>
            @foreach($bo3_list as $m)
                <div style="margin-bottom: 20px;">
                    <div style="font-size: 11px; font-weight: bold; color: #94a3b8; text-transform: uppercase;">{{ $m['l'] }}</div>
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #475569;">
                        <tr>
                            <td style="height: 45px; padding: 0;">
                                <div style="width: 8px; height: 100%; background: #ef4444; float: left;"></div>
                                <div style="padding: 5px 12px;">{{ $m['r']['lastname'] }} <strong>{{ $m['r']['firstname'] }}</strong></div>
                            </td>
                            <td style="width: 60px; background: #f8fafc; border-left: 1px solid #475569;"></td>
                        </tr>
                        <tr>
                            <td style="height: 45px; padding: 0; border-top: 1px solid #eee;">
                                <div style="width: 8px; height: 100%; background: #3b82f6; float: left;"></div>
                                <div style="padding: 5px 12px;">{{ $m['b']['lastname'] }} <strong>{{ $m['b']['firstname'] }}</strong></div>
                            </td>
                            <td style="width: 60px; background: #f8fafc; border-left: 1px solid #475569;"></td>
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
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            @foreach($matches as $m)
                <div>
                    <div style="font-size: 11px; font-weight: bold; color: #94a3b8;">{{ $m['l'] }}</div>
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #475569;">
                        <tr><td style="height: 40px; padding: 5px 10px; border-bottom: 1px solid #eee;">{{ $m['r']['lastname'] }} <strong>{{ $m['r']['firstname'] }}</strong></td><td style="width: 50px; background: #eee;"></td></tr>
                        <tr><td style="height: 40px; padding: 5px 10px;">{{ $m['b']['lastname'] }} <strong>{{ $m['b']['firstname'] }}</strong></td><td style="width: 50px; background: #eee;"></td></tr>
                    </table>
                </div>
            @endforeach
        </div>

    {{-- CASE 3: 5-6 ТАМИРЧИНТАЙ БОЛ (Pools + Bracket) --}}
    @else
        <div style="text-align: center; color: #64748b;">5-6 тамирчны оноолтын хэсэг энд байна...</div>
        {{-- (Өмнөх 5-6 хүнтэй кодыг энд нэмж болно) --}}
    @endif

</div>
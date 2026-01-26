<div style="width:1150px; margin:0 auto; background-color: white; border: 1px solid #ccc; padding: 30px; font-family: 'Helvetica', 'Arial', sans-serif; position: relative; min-height: 1400px; color: #333;">
    
    <?php
        $seedPairs = [
            0 => [1, 16], 1 => [9, 8], 2 => [5, 12], 3 => [13, 4],
            4 => [3, 14], 5 => [11, 6], 6 => [7, 10], 7 => [15, 2]
        ];
        $rounds_count = 4; 
        $total_matches = 8; 
    ?>

    <div style="border-bottom: 2px solid #e11d48; margin-bottom: 25px; padding-bottom: 10px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2 style="margin:0; font-size: 22px;">{{ @$eventConfig->event->name }}</h2>
            <div style="font-size: 14px; color: #444; margin-top: 5px; font-weight: bold;">
                {{ @$entry->name }} | {{ @$age->name }} | {{ @$weight->weight }}кг | {{ Config::get("enums.gender_code")[@$entry->gender_code] }} | {{ date_format(date_create(@$eventConfig->event->event_date), 'Y-m-d') }}
            </div>
        </div>
        <div style="text-align: right; font-size: 12px; font-weight: bold; color: #e11d48;">
            16 ТАМИРЧИНТАЙ ОНООЛТ
        </div>
    </div>

    <div style="margin-bottom: 60px;">
        <table width="100%" border="0" style="border-collapse: collapse; table-layout: fixed;">
            <thead>
                <tr>
                    <th class="round-title">1/8 ШИГШЭЭ</th>
                    <th class="round-title">ШӨВГИЙН 8 (QF)</th>
                    <th class="round-title">ХАГАС ШИГШЭЭ (SF)</th>
                    <th class="round-title">ШИГШЭЭ</th>
                </tr>
            </thead>
            <tbody>
                @for($index = 0; $index < $total_matches; $index++)
                <?php $m = $members[$index] ?? null; ?>
                <tr>
                    @for($r = 0; $r < $rounds_count; $r++)
                        <?php 
                            $step = pow(2, $r); 
                            $is_visible = ($index % $step == 0);
                        ?>
                        @if($is_visible)
                            <td rowspan="{{ $step }}" valign="middle" style="position: relative;">
                                <div class="connector-container">
                                    <table width="100%" border="0" class="match-box">
                                        <tr>
                                            <td class="player-cell">
                                                <div class="indicator-bar {{ ($index / $step) % 2 == 0 ? 'red-bg' : 'blue-bg' }}">
                                                    @if($r == 0) {{ $m->seed_one ?? $seedPairs[$index][0] }} @endif
                                                </div>
                                                <div class="player-info">
                                                    @if($r == 0) 
                                                        <div class="p-name">{!! $m->lastname_one != null ? $m->lastname_one.' <strong>'.$m->firstname_one.'</strong>' : 'BYE' !!}</div>
                                                        <div class="p-academy">{{ $m->acname_one }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="player-cell" style="border-top: 1px solid #e2e8f0;">
                                                <div class="indicator-bar {{ ($index / $step) % 2 == 0 ? 'blue-bg' : 'red-bg' }}">
                                                    @if($r == 0) {{ $m->seed_two ?? $seedPairs[$index][1] }} @endif
                                                </div>
                                                <div class="player-info">
                                                    @if($r == 0) 
                                                        <div class="p-name">{!! $m->lastname_two != null ? $m->lastname_two.' <strong>'.$m->firstname_two.'</strong>' : 'BYE' !!}</div>
                                                        <div class="p-academy">{{ $m->acname_two }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    @if($r < $rounds_count - 1)
                                        <?php 
                                            $is_top = (($index / $step) % 2 == 0);
                                            $v_height = ($step * 52); 
                                        ?>
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

    <div class="repechage-header">ТОРГУУЛИЙН БАРИЛДААН / REPECHAGE</div>

    <table width="100%" style="margin-top: 20px; border-collapse: collapse; table-layout: fixed;">
        <thead>
            <tr>
                <th class="rep-col-title">R1-д хожигдсон</th>
                <th class="rep-col-title">vs QF-д хожигдсон</th>
                <th class="rep-col-title">Дагах хагас шигшээ</th>
                <th class="rep-col-title" style="background: #fff7ed; color: #c2410c;">Хүрэл медаль</th>
            </tr>
        </thead>
        <tr>
            <td width="25%" valign="top">
                @for($i=0; $i<4; $i++)
                <div class="rep-wrapper" style="margin-top: 25px;">
                    <table class="match-box-sm">
                        <tr><td class="cell-sm"><div class="indicator-bar-sm red-bg">L</div></td></tr>
                        <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;"><div class="indicator-bar-sm blue-bg">L</div></td></tr>
                    </table>
                    <div class="rep-line {{ $i % 2 == 0 ? 'r-down' : 'r-up' }}"></div>
                </div>
                @endfor
            </td>
            <td width="25%" valign="top">
                @for($i=0; $i<4; $i++)
                <div class="rep-wrapper" style="margin-top: 25px;">
                    <table class="match-box-sm">
                        <tr><td class="cell-sm"><div class="indicator-bar-sm red-bg">QF-L</div></td></tr>
                        <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;"><div class="indicator-bar-sm blue-bg">W</div></td></tr>
                    </table>
                    <div class="rep-line {{ $i % 2 == 0 ? 'r-down' : 'r-up' }}"></div>
                </div>
                @endfor
            </td>
            <td width="25%" valign="top">
                @for($i=0; $i<2; $i++)
                <div class="rep-wrapper" style="margin-top: 85px; margin-bottom: 110px;">
                    <table class="match-box-sm">
                        <tr><td class="cell-sm"><div class="indicator-bar-sm red-bg">W</div></td></tr>
                        <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;"><div class="indicator-bar-sm blue-bg">W</div></td></tr>
                    </table>
                    <div class="rep-line-straight"></div>
                </div>
                @endfor
            </td>
            <td width="25%" valign="top">
                @for($i=0; $i<2; $i++)
                <div class="rep-wrapper" style="margin-top: 85px; margin-bottom: 110px;">
                    <table class="match-box-sm" style="border: 1.5px solid #d97706; background: #fffbeb;">
                        <tr><td class="cell-sm"><div class="indicator-bar-sm red-bg">SF-L</div></td></tr>
                        <tr><td class="cell-sm" style="border-top: 1px solid #f1f5f9;"><div class="indicator-bar-sm blue-bg">RP-W</div></td></tr>
                    </table>
                    <div style="margin-left: 8px; font-size: 16px;">🥉</div>
                </div>
                @endfor
            </td>
        </tr>
    </table>
</div>

<style>
    .round-title { font-size: 11px; padding: 10px; background: #f8fafc; border: 1px solid #cbd5e1; text-transform: uppercase; text-align: center; font-weight: bold; }
    .rep-col-title { font-size: 10px; padding: 8px; background: #f1f5f9; border: 1px solid #e2e8f0; font-weight: bold; text-align: center; }
    
    .match-box { border-collapse: collapse; border: 1.5px solid #64748b; background: white; width: 100%; z-index: 2; position: relative; }
    .match-box-sm { border-collapse: collapse; border: 1.2px solid #94a3b8; background: white; width: 100%; z-index: 2; position: relative; }
    
    .player-cell { height: 50px; padding: 0; font-size: 11px; }
    .cell-sm { height: 32px; display: flex; align-items: center; }

    .indicator-bar { width: 28px; height: 50px; float: left; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 10px; margin-right: 10px; }
    .indicator-bar-sm { width: 24px; height: 32px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 9px; margin-right: 8px; flex-shrink: 0; }
    
    .red-bg { background-color: #e11d48; }
    .blue-bg { background-color: #2563eb; }
    
    .player-info { padding-top: 8px; line-height: 1.2; }
    .p-name { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .p-academy { font-size: 9px; color: #666; font-weight: normal; margin-top: 2px; }

    .connector-container { position: relative; display: flex; align-items: center; padding: 20px 40px 20px 0; }
    .rep-wrapper { position: relative; display: flex; align-items: center; padding-right: 35px; }

    .line-logic { position: absolute; right: 0; width: 40px; border-right: 1.5px solid #94a3b8; z-index: 1; }
    .line-logic::before { content: ''; position: absolute; top: 50%; left: -40px; width: 40px; height: 1.5px; background: #94a3b8; }
    .line-logic::after { content: ''; position: absolute; width: 15px; height: 1.5px; background: #94a3b8; right: -15px; }
    
    .l-down { top: 50%; border-top: 1.5px solid #94a3b8; }
    .l-down::after { top: 100%; }
    .l-up { bottom: 50%; border-bottom: 1.5px solid #94a3b8; }
    .l-up::after { bottom: 100%; }

    .rep-line { position: absolute; right: 0; width: 35px; border-right: 1.5px solid #94a3b8; z-index: 1; }
    .rep-line::before { content: ''; position: absolute; top: 50%; left: -35px; width: 35px; height: 1.5px; background: #94a3b8; }
    .r-down { top: 50%; height: 60px; border-top: 1.5px solid #94a3b8; }
    .r-up { bottom: 50%; height: 60px; border-bottom: 1.5px solid #94a3b8; }
    .rep-line-straight { position: absolute; right: 0; width: 35px; height: 1.5px; background: #94a3b8; top: 50%; }

    .repechage-header { background: #334155; color: white; padding: 10px 15px; font-weight: bold; margin-top: 50px; text-transform: uppercase; font-size: 13px; }
</style>
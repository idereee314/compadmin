<div style="width:800px; margin:0 auto; background-color: white; border: 1px solid black; padding: 20px; font-family: sans-serif;">
    <table width="100%" border="0" style="margin-bottom: 20px; border-bottom: 2px solid red;">
        <tr>
           <td style="font-size: 18px; font-weight: bold;">{{ @$eventConfig->event->name }} | {{ date_format(date_create(@$eventConfig->event->event_date), 'Y-m-d') }}</td>
        </tr>
        <tr>
            <td style="padding-bottom: 5px;">
                {{ @$entry->name }} | {{ @$age->name }} | {{ @$belt->name }} | {{ @$weight->weight }}кг | {{ Config::get("enums.gender_code")[@$entry->gender_code] }}
            </td>
        </tr>
    </table>

    <table width="100%" border="0" class="bracket-table">
        <tr>
            @for($i = 0; $i < $round; $i++)
                <th width="{{80/($round+1)}}%">{{ $i == $round - 1 ? 'Хагас шигшээ' : 'Тойрог ' . ($i + 1) }}</th>
            @endfor
            <th width="20%">Шигшээ / Finals</th>
        </tr>
        
        <?php $k = 0; ?>
        @foreach($members as $member)
        <tr>
            @for($i = 0; $i < $round; $i++)
                @if($i == 0)
                    <td>
                        <div class="connector">
                            <table width="100%" border="1" class="match-box">                            
                                <tr><td class="player-cell red-seed">{!! $member->lastname_one ?? 'BYE' !!} <strong>{{$member->firstname_one}}</strong></td></tr>
                                <tr><td class="player-cell blue-seed">{!! $member->lastname_two ?? 'BYE' !!} <strong>{{$member->firstname_two}}</strong></td></tr>
                            </table>
                        </div>
                    </td>
                @elseif($k % pow(2, $i) == 0)
                    <td rowspan="{{ pow(2, $i) }}">
                        <div class="connector">
                            <div class="linebox"></div>
                            <table width="100%" border="1" class="match-box">
                                <tr><td class="player-cell winner-cell" height="25">Winner {{ $i }}</td></tr>
                                <tr><td class="player-cell winner-cell" height="25">Winner {{ $i }}</td></tr>
                            </table>
                        </div>
                    </td>
                @endif
            @endfor

            @if($k == 0)
                <td rowspan="{{ count($members) }}">
                    <div class="connector">
                        <div class="linebox"></div>
                        <table width="100%" border="1">
                            <tr><td class="final-cell gold">1st Place</td></tr>
                            <tr><td class="final-cell silver">2nd Place</td></tr>
                        </table>
                    </div>
                </td>
            @endif
            <?php $k++; ?>
        </tr>
        @endforeach
    </table>

    <div style="margin-top: 40px;">
        <h3 style="border-left: 5px solid red; padding-left: 10px;">Торгуулийн барилдаан / Repechage (Double Elimination)</h3>
        
        <table width="80%" border="0" style="margin-top: 20px;">
            <tr>
                <td width="30%">
                    <div class="repechage-label">Lost in Quarter-final</div>
                    <table width="100%" border="1" class="match-box">
                        <tr><td class="player-cell red-seed"></td></tr>
                        <tr><td class="player-cell blue-seed"></td></tr>
                    </table>
                </td>
                <td width="10%" align="center"><div class="horizontal-line"></div></td>
                <td width="30%">
                    <div class="repechage-label">Lost in Semi-final</div>
                    <table width="100%" border="1" class="match-box">
                        <tr><td class="player-cell red-seed"></td></tr>
                        <tr><td class="player-cell blue-seed"></td></tr>
                    </table>
                </td>
                <td width="30%">
                    <div class="connector">
                        <div class="linebox-short"></div>
                        <table width="100%" border="1">
                            <tr><td class="final-cell bronze">3rd Place</td></tr>
                        </table>
                    </div>
                </td>
            </tr>

            <tr><td colspan="4" style="height: 30px;"></td></tr>

            <tr>
                <td>
                    <table width="100%" border="1" class="match-box">
                        <tr><td class="player-cell red-seed"></td></tr>
                        <tr><td class="player-cell blue-seed"></td></tr>
                    </table>
                </td>
                <td align="center"><div class="horizontal-line"></div></td>
                <td>
                    <table width="100%" border="1" class="match-box">
                        <tr><td class="player-cell red-seed"></td></tr>
                        <tr><td class="player-cell blue-seed"></td></tr>
                    </table>
                </td>
                <td>
                    <div class="connector">
                        <div class="linebox-short"></div>
                        <table width="100%" border="1">
                            <tr><td class="final-cell bronze">3rd Place</td></tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<style>
    .bracket-table { border-collapse: collapse; }
    .player-cell { font-size: 11px; padding: 5px; height: 25px; min-width: 140px; }
    .red-seed { border-left: 5px solid #ff4d4d; background-color: #f9f9f9; }
    .blue-seed { border-left: 5px solid #4d79ff; background-color: #f9f9f9; }
    .winner-cell { background-color: #f0f0f0; text-align: center; color: #666; }
    
    .final-cell { padding: 10px; text-align: center; font-weight: bold; font-size: 12px; }
    .gold { background-color: #FFD700; }
    .silver { background-color: #C0C0C0; }
    .bronze { background-color: #CD7F32; color: white; }

    .connector { display: flex; align-items: center; position: relative; padding-left: 25px; }
    .linebox { height: 100%; width: 20px; position: absolute; border: 1px solid #444; border-left: none; left: 5px; top: 0; }
    .linebox-short { height: 2px; width: 20px; background: #444; position: absolute; left: 5px; }
    .horizontal-line { width: 100%; height: 2px; background: #444; }
    .repechage-label { font-size: 9px; color: #666; margin-bottom: 2px; }
    
    .match-box { border-collapse: collapse; margin: 5px 0; }
</style>
@php
    // Map (round_index, slot) → order_no for single elimination
    $getOrderNo = function(int $ri, int $slot) use ($round) {
        if ($ri === $round - 1) return 9999; // Final
        if ($ri === 0) return $slot + 1;     // First round: 1, 2, 3, ...
        return ($ri + 1) * 100 + $slot + 1;  // Later rounds: 201, 202, ...
    };
@endphp

@include('event.bracket._match_lookup')

<div style="width:{{ $width }}; margin:0 auto; background-color: white;border: black;border-width: 1px;padding: 20px;">
    <table width="100%" style="width:100%" border="0">
        <tr>
           <td>{{ @$eventConfig->event->name }} | {{ date_format(date_create(@$eventConfig->event->event_date), 'Y-m-d') }}</td>
        </tr>
        <tr>
            <td>
                {{ @$entry->name }} | {{ @$age->name }} | {{ @$belt->name }} | {{ @$weight->weight }}кг | {{ Config::get("enums.gender_code")[@$entry->gender_code] }}
            </td>
        </tr>
    </table>
    <table width="100%" style="width:100%" border="0">
        <tr>
            <td width="50%" align="center"></td>
            <td width="50%" align="right"></td>
        </tr>
    </table>
    <table width="100%" style="width:100%;" id="table1" border="0" class="lvlone-table">
        <tr>
            @for($i = 0; $i < $round; $i++)
            <th style="padding-bottom: 10px;" width="{{100/$round}}%">Тойрог {{ $i + 1 }}</th>
            @endfor
        </tr>
        <?php
            $k = 0;
            $byeList = array();
            foreach($members as $key => $member)
            {
                if($member->lastname_one != null && $member->lastname_two == null)
                {
                    $byeList[$key] = array('lastname'=> $member->lastname_one, 'firstname'=> $member->firstname_one, 'academy'=> $member->acname_one);
                }
                else if($member->lastname_one == null && $member->lastname_two != null)
                {
                    $byeList[$key] = array('lastname'=> $member->lastname_two, 'firstname'=> $member->firstname_two, 'academy'=> $member->acname_two);
                }
                else
                {
                    $byeList[$key] = array('lastname'=> null);
                }
            }
        ?>
        @foreach($members as $member)
        <tr>
            @for($i = 0; $i < $round; $i++)
                @if($i == 0)
                    @php
                        $md0 = $matchByOrder[$getOrderNo(0, $k)] ?? null;
                    @endphp
                    <td>
                        <div class="connector">
                            <div class="linebox"></div>
                            <div class="linebox_two"></div>
                            <table width="100%" style="width:100%;" id="table1" border="1">
                                <tr>
                                    <td width="50%" align="center" style="font-size: 11px;{{ $md0 && $isWinner($md0->reg_one_id, $md0) ? ' background:#d4edda;' : '' }}">
                                    {!! $member->lastname_one != null? $member->lastname_one.' <strong>'.$member->firstname_one.'</strong>': 'BYE'!!}<br>
                                    {{$member->acname_one}}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" align="center" style="font-size: 11px;{{ $md0 && $isWinner($md0->reg_two_id, $md0) ? ' background:#d4edda;' : '' }}">
                                    {!! $member->lastname_two != null? $member->lastname_two.' <strong>'.$member->firstname_two.'</strong>': 'BYE'!!}<br>
                                    {{$member->acname_two}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                @else
                    @if($k % pow(2, $i) == 0)
                    @php
                        $slotSe = (int) floor($k / pow(2, $i));
                        $orderNoSe = $getOrderNo($i, $slotSe);
                        $mdSe = $matchByOrder[$orderNoSe] ?? null;
                    @endphp
                    <td rowspan="{{ pow(2, $i) }}">
                        <div class="connector">
                            <div class="linebox"></div>
                            <div class="linebox_two"></div>
                            <table width="100%" style="width:100%;" id="table1" border="1">
                                <tr>
                                    <td width="50%" align="center" style="font-size: 11px;{{ $mdSe && $isWinner($mdSe->reg_one_id, $mdSe) ? ' background:#d4edda;' : '' }}">
                                        @if($mdSe && !empty($mdSe->lastname_one))
                                            {!! $mdSe->lastname_one.' <strong>'.$mdSe->firstname_one.'</strong>' !!}<br>
                                            {{ $mdSe->acname_one ?? '' }}
                                        @elseif($i == 1)
                                            {!! @$byeList[$k]['lastname'] != null? @$byeList[$k]['lastname'].' <strong>'.@$byeList[$k]['firstname'].'</strong>': ''!!}<br>
                                            {{@$byeList[$k]['academy']}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" align="center" style="font-size: 11px;{{ $mdSe && $isWinner($mdSe->reg_two_id, $mdSe) ? ' background:#d4edda;' : '' }}">
                                        @if($mdSe && !empty($mdSe->lastname_two))
                                            {!! $mdSe->lastname_two.' <strong>'.$mdSe->firstname_two.'</strong>' !!}<br>
                                            {{ $mdSe->acname_two ?? '' }}
                                        @elseif($i == 1)
                                            {!! @$byeList[$k + 1]['lastname'] != null? @$byeList[$k + 1]['lastname'].' <strong>'.@$byeList[$k + 1]['firstname'].'</strong>': ''!!}<br>
                                            {{@$byeList[$k + 1]['academy']}}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    @endif
                @endif
            @endfor
            <?php $k++; ?>
        </tr>
        @endforeach

        <tr>
            <td colspan="{{$round}}" style="padding: 20px">
                Ерөнхий шүүгч ................................... / ............................. / Огноо: ...............
            </td>
        </tr>
    </table>
</div>
<style>
    .lvlone-table tr td{
        padding-bottom: 5px;
        height: inherit;
    }
    .lvlone-table tr td table{
        min-height: 50px;
    }
    .lvlone-table tr td:first-child .connector{
        border: none;
        padding-left: 0px;
    }
    .lvlone-table tr td:first-child .linebox{
        display: none;
    }
    .lvlone-table tr td:first-child .linebox_two{
        display: none;
    }    
    .lvlone-table tr{
        height: 1px;
    }
    .lvlone-table tr td{
        height: inherit;
    }
    .connector{
        display: flex;
        align-items: center;
        position: relative;
        padding-left: 15px;
        height: 50%;
    }
    .linebox{
        height: 100%;
        width: 8px;
        position: absolute;
        border: 2px solid #444;
        border-left-width: 0px;
        margin-left: -17px;
    }
    .linebox_two{
        height: 2px;
        width: 8px;
        position: absolute;
        border-left-width: 0px;
        margin-left: -7px;
        border-radius: 0;
        border-color: #444;
        border-width: 0 0 2px 0;
        border-style: none none solid none;
    }
</style>
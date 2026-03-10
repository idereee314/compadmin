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
            // Prefer match data (seeded order) over bracket data (legacy order)
            $useMatchData = false;
            if (!empty($matchByOrder)) {
                foreach ($members as $mKey => $member) {
                    $md = $matchByOrder[$mKey + 1] ?? null;
                    if ($md && ($md->lastname_one || $md->lastname_two)) {
                        $useMatchData = true;
                        break;
                    }
                }
            }
            $byeList = array();
            foreach($members as $key => $member)
            {
                $src = ($useMatchData && isset($matchByOrder[$key + 1])) ? $matchByOrder[$key + 1] : null;
                $ln1 = $src ? $src->lastname_one : $member->lastname_one;
                $fn1 = $src ? $src->firstname_one : $member->firstname_one;
                $ac1 = $src ? ($src->acname_one ?? '') : $member->acname_one;
                $ln2 = $src ? $src->lastname_two : $member->lastname_two;
                $fn2 = $src ? $src->firstname_two : $member->firstname_two;
                $ac2 = $src ? ($src->acname_two ?? '') : $member->acname_two;
                if($ln1 != null && $ln2 == null)
                {
                    $byeList[$key] = array('lastname'=> $ln1, 'firstname'=> $fn1, 'academy'=> $ac1);
                }
                else if($ln1 == null && $ln2 != null)
                {
                    $byeList[$key] = array('lastname'=> $ln2, 'firstname'=> $fn2, 'academy'=> $ac2);
                }
                else
                {
                    $byeList[$key] = array('lastname'=> null);
                }
            }
        ?>        
        @foreach($members as $mKey => $member)
        <tr>
            @for($i = 0; $i < $round; $i++)
                @if($i == 0)
                    @php
                        $src = ($useMatchData && isset($matchByOrder[$mKey + 1])) ? $matchByOrder[$mKey + 1] : null;
                        $ln1 = $src ? $src->lastname_one : $member->lastname_one;
                        $fn1 = $src ? $src->firstname_one : $member->firstname_one;
                        $ac1 = $src ? ($src->acname_one ?? '') : $member->acname_one;
                        $ln2 = $src ? $src->lastname_two : $member->lastname_two;
                        $fn2 = $src ? $src->firstname_two : $member->firstname_two;
                        $ac2 = $src ? ($src->acname_two ?? '') : $member->acname_two;
                    @endphp
                    <td>
                        <div class="connector">
                            <div class="linebox"></div>
                            <div class="linebox_two"></div>
                            <table width="100%" style="width:100%;" id="table1" border="1">
                                <tr>
                                    <td width="50%" align="center" style="font-size: 11px;">
                                    {!! $ln1 != null? $ln1.' <strong>'.$fn1.'</strong>': 'BYE'!!}<br>
                                    {{$ac1}}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" align="center" style="font-size: 11px;">
                                    {!! $ln2 != null? $ln2.' <strong>'.$fn2.'</strong>': 'BYE'!!}<br>
                                    {{$ac2}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                @else
                    @if($k % pow(2, $i) == 0)
                    <td rowspan="{{ pow(2, $i) }}">
                        <div class="connector">
                            <div class="linebox"></div>
                            <div class="linebox_two"></div>
                            <table width="100%" style="width:100%;" id="table1" border="1">
                            @if($i == 1)
                                <tr>
                                    <td width="50%" align="center" style="font-size: 11px;">
                                        {!! @$byeList[$k]['lastname'] != null? @$byeList[$k]['lastname'].' <strong>'.@$byeList[$k]['firstname'].'</strong>': ''!!}<br>
                                        {{@$byeList[$k]['academy']}}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" align="center" style="font-size: 11px;">
                                        {!! @$byeList[$k + 1]['lastname'] != null? @$byeList[$k + 1]['lastname'].' <strong>'.@$byeList[$k + 1]['firstname'].'</strong>': ''!!}<br>
                                        {{@$byeList[$k + 1]['academy']}}
                                    </td> 
                                </tr>
                            @else
                            <tr>
                                <td width="50%" align="center" style="font-size: 11px;"></td>
                            </tr>
                            <tr>
                                <td width="50%" align="center" style="font-size: 11px;"></td>
                            </tr>
                            @endif
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
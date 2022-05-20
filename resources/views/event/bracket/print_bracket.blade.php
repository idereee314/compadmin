<div style="width:{{ $width }}; margin:0 auto; background-color: white;border: black;border-width: 1px;border-style: dotted;padding: 20px;">
    <table width="100%" style="width:100%" border="0">
        <tr>
           <td>{{ @$eventConfig->event->name }} | {{ date_format(date_create(@$eventConfig->event->event_date), 'Y-m-d') }}</td>
        </tr>
        <tr>
            <td>
                {{ @$entry->name }} | {{ @$age->name }} | {{ @$belt->name }} | {{ @$weight->weight }}кг
            </td>
        </tr>
    </table>
    <table width="100%" style="width:100%" border="0">        
        <tr>
            <td width="50%" align="center">
            </td>
            <td width="50%" align="right">
            </td>
        </tr>
    </table>
    <table width="100%" style="width:100%;" id="table1" border="0" class="lvlone-table">
        <tr>
            @for($i = 0; $i < $round; $i++)
            <th style="padding-bottom: 10px;" width="{{100/$round}}%">Тойрог {{ $i + 1 }}</th>
            @endfor
        </tr>
        <?php $k = 0; ?>
        @foreach($members as $member)
        <tr>
            @for($i = 0; $i < $round; $i++)
                @if($i == 0)
                    <td>    
                        <div class="connector">
                            <div class="linebox"></div>
                            <div class="linebox_two"></div>
                            <table width="100%" style="width:100%;" id="table1" border="1">
                                <tr>
                                    <td width="50%" align="center" style="font-size: 11px;border-right: 1px solid #cdd0d4;">
                                    {!! $member->lastname_one != null? $member->lastname_one.' <strong>'.$member->firstname_one.'</strong>': 'BYE'!!}<br>
                                    {{$member->acname_one}}
                                    </td>
                                    <td width="50%" align="center" style="font-size: 11px;">
                                    {!! $member->lastname_two != null? $member->lastname_two.' <strong>'.$member->firstname_two.'</strong>': 'BYE'!!}<br>
                                    {{$member->acname_two}}
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
                                <tr>
                                    <td width="50%" align="center" style="font-size: 11px;border-right: 1px solid #cdd0d4;">
                                    TBD
                                    </td>
                                    <td width="50%" align="center" style="font-size: 11px;">
                                    TBD
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
                Ерөнхий шүүгч ................................... /............................. / Огноо: ...............
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
        background: #444;
        margin-left: -7px;
    }
</style>
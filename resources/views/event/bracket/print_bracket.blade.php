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
    <table width="100%" style="width:100%" id="table1" border="1">
        <tr>
            @for($i = 0; $i < $round; $i++)
            <th width="{{100/$round}}%">Тойрог {{ $i + 1 }}</th>
            @endfor
        </tr>
        <?php $k = 0; ?>
        @foreach($members as $member)
        <tr>
            @for($i = 0; $i < $round; $i++)
                @if($i == 0)
                    <td>    
                        <table width="100%" style="width:100%" id="table1">
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
                    </td>
                @else
                    @if($k % pow(2, $i) == 0)
                    <td rowspan="{{ pow(2, $i) }}">
                            
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
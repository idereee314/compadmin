<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="shortcut icon" href="{{asset('assets/images/logo/uniq_logo.ico')}}" />
    <title>UniQ Sport Bracket System</title>
    <style>
        body{
            font-family: Tahoma;
        }
        
        #table1 {
            border-collapse: collapse;
        }

        .td1 {
            border: 1px solid black;
            padding: 1px;
            font-size: 11px;
        }
    </style>
</head>

<body>
    @php
        $all_athletes = [];
        if (isset($members) && count($members) > 0) {
            foreach($members as $m) {
                if(!empty($m->lastname_one)) {
                    $all_athletes[] = [
                        'lastname'  => $m->lastname_one,
                        'firstname' => $m->firstname_one,
                        'academy'   => $m->acname_one ?? 'Академигүй'
                    ];
                }
                if(!empty($m->lastname_two)) {
                    $all_athletes[] = [
                        'lastname'  => $m->lastname_two,
                        'firstname' => $m->firstname_two,
                        'academy'   => $m->acname_two ?? 'Академигүй'
                    ];
                }
            }
        }

        $count = count($all_athletes);

        /**
         * bracketType-г controller-оос явуулж болно.
         * Хэрвээ явуулаагүй бол count-оор автоматаар тааруулна.
         *
         * Боломжит утга:
         *  - 'RR_2_6'
         *  - 'DOUBLE' <- end 8,16,32 iin bracket bga
         */

        $bracketType = null;

        if (!$bracketType) {
            if ($count <= 6) $bracketType = 'RR_2_6';
            else $bracketType = 'DOUBLE';

        }
    @endphp

    @switch($bracketType)
        @case('RR_2_6')
            @include('event.bracket.double_elimination_1bronze.print_bracket_RRdouble')
            @break
        @case('DOUBLE')
            @include('event.bracket.double_elimination_1bronze.print_bracket_double_elimination')
            @break

        @default
            <div style="padding:40px; border:1px dashed #cbd5e1; color:#64748b;">
                Bracket template олдсонгүй. bracketType={{ $bracketType }}, count={{ $count }}
            </div>

    @endswitch

</body>
</html>
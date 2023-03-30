<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
@extends('default')

@section('css')
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.css')}}">
@endsection
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>UniQ Competition System</title>
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
<body style="background-color: gray">      
<div style="width: 842px;height: 595px; margin:0 auto; background-color: white;border: black;border-width: 1px;padding: 20px;">
    <div class="table-responsive">
        <table style="width:95%; border-width: 3px; vertical-align: center; margin: 0 auto;">
            <tr>
                <th style="font-size:12px; border-width: 3px;" width="10%" class="text-center">MVA 0103002</th>
                <th style="font-size:12px; border-width: 3px;" width="70%" class="text-center">БАГ ТАМИРЧДЫН МЭДҮҮЛЭГ</th>
                <th style="font-size:12px; border-width: 3px;" width="20%" class="text-left">МУБИС-ийн Биеийн Тамирын Сургууль</th>
            </tr>
        </table>
    </div>
    
    <div class="d-flex align-items-center flex-wrap justify-content-start row mt-5 mx-5 ">
    
        <div class="col-md-6">
            <a style="color: #000000; font-size: 12px">Зөвшөөрөв . . . . . . . . . . . . . . . . . . . . . . . . . . . . .</a> 
        </div>
        <div class="col-md-6 text-right"> 
            <a style="color: #000000; font-size: 12px" > {{ date_format(date_create(@$event->event_date), 'Y оны m сарын d өдөр') }} </a>
        </div>
        <div class="col-md-2 text-center">
            <div><a style="color: #000000; font-size:10px;">/тамга/</a></div>
        </div>
    </div>
    
    
    <div class="mt-5">
        <div class="text-center">                
            <h3 style="font-size:12px;"><strong> {{@$event->name}} Волейболын идэрчүүдийн аварга шалгаруулах тэмцээн </strong></h3>
        </div>
    </div>
    
    <div class="d-flex flex-wrap justify-content-between mt-5">
        <div>
            <a style="color: #000000; font-size: 12px">Багийн нэр: {{ $eventTeamRegistration->team->name }}</a> 
        </div>
        <div class="col-md-2"> 
            <a style="color: #000000; font-size: 12px">{{trans('display.human_gender_code')}}: {{ Config::get("enums.gender_code")[@$eventEntries->gender_code] }}</a>
        </div>
    </div>
    
    <input type="hidden" name="event_id" id="event_id" value="{{ $event_id }}"/>
    <input type="hidden" name="team_id" id="team_id" value="{{ $eventTeamRegistration->team->id }}">
    <div class="table-responsive mt-2">
        <table width="100%" style="width:100%;" id="event-team-registration-datatable" border="1">
            @if(count($eventTeamRegistration->teamathlete) > 0)
            <thead>
                <tr>
                    <th style="font-size:10px; font-weight: normal;" width="5px" class="text-center">Хувийн дугаар</th>
                    <th style="font-size:10px; font-weight: normal;" width="35%" class="text-center">Овог нэр</th>
                    <th style="font-size:10px; font-weight: normal;" width="10%" class="text-center">Регистрийн дугаар</th>
                    <th style="font-size:10px; font-weight: normal;" width="10px" class="text-center">Биеийн жин</th>
                    <th style="font-size:10px; font-weight: normal;" width="10px" class="text-center">Биеийн өндөр</th>
                    <th style="font-size:10px; font-weight: normal;" width="10%" class="text-center">Тоглолтын үүрэг</th>
                    <th style="font-size:10px; font-weight: normal;" width="20%" class="text-center">Спортын цол зэрэг</th>
                    <th style="font-size:10px; font-weight: normal;" width="10%" class="text-left">Холбогдох дугаар</th>
                </tr>
            </thead>
            @php
                $teamMemberCount = 0;
                $teamAverageAge = null;
                $teamAge = null;
                $teamWeight = null;
                $teamHeight = null;
                $teamLeader = null;
                $teamAverageHeight = null;
            @endphp
            <tbody>
            @foreach($eventTeamRegistration->teamathlete as $athlete)
                
                    <tr>
                        <td class="text-center" style="font-size:10px;">{{ @$athlete->member->memberAttribute->where('attribute_id', 5)->where('sport_id', 2)->first()->value }}</td>
                        <td class="text-left" style="font-size:10px;">{{$athlete->member->lastname}} {{$athlete->member->firstname}}</td>
                        <td class="text-left" style="font-size:10px;">{{ $athlete->member->register_number }}</td>
                        <td class="text-center" style="font-size:10px;">{{ @$athlete->member->memberAttribute->where('attribute_id', 2)->where('sport_id', 2)->first() ? $athlete->member->memberAttribute->where('attribute_id', 2)->where('sport_id', 2)->first()->value.'кг' : '' }}</td>
                        <td class="text-center" style="font-size:10px;">{{ @$athlete->member->memberAttribute->where('attribute_id', 1)->where('sport_id', 2)->first() ? $athlete->member->memberAttribute->where('attribute_id', 1)->where('sport_id', 2)->first()->value.'см' : '' }}</td>
                        <td class="text-center" style="font-size:10px;">{{ @$athlete->member->memberAttribute->where('attribute_id', 3)->where('sport_id', 2)->first()->value }}</td>
                        <td class="text-center" style="font-size:10px;">{{ @$athlete->member->memberAttribute->where('attribute_id', 4)->where('sport_id', 2)->first()->value }}</td>
                        <td class="text-left" style="font-size:10px;">{{$athlete->member->contact_phone}}</td>
                    </tr>
                    @if(@$athlete->is_team_lead)
                        @php
                            @$teamLeader = $athlete->member;
                        @endphp
                    @endif
                    @php
                        $teamMemberCount = $teamMemberCount += 1;
                        $teamAge = $teamAge + $athlete->member->age;

                        $teamHeight = $teamHeight + @$athlete->member->memberAttribute->where('attribute_id', 1)->where('sport_id', 2)->first()->value;
                        $teamAverageAge = round($teamAge/$teamMemberCount, 2);

                        $teamAverageHeight = round($teamHeight/$teamMemberCount, 2);
                    @endphp
                
            @endforeach
            </tbody>
            @else
                <tr>
                    <td colspan="12" class="text-center"><strong>{{ trans('display.general_team_no_athlete') }}</strong></td>
                </tr>
            @endif                
        </table>
    </div>
    
    <div class="row mt-3">
    <div class="col-md-4">
        @if (@$teamLeader)           
            <div style="font-size:10px;">Багийн ахлагч: {{@$teamLeader->lastname}} {{ @$teamLeader->firstname }} </div>
        @else
            <div style="font-size:10px;">Багийн ахлагч: </div>
        @endif
        <div style="font-size:10px;">Багийн дасгалжуулагч: </div>
    </div>
    <div class="col-md-4">
        <div style="font-size:10px;">Багийн дундаж нас: {{@$teamAverageAge}}</div>
        <div style="font-size:10px;">Багийн дундаж өндөр: {{@$teamAverageHeight}}</div>
    </div>
    <div class="col-md-4">
        <div style="font-size:10px;">Хувцасны өнгө: </div>
        <div style="font-size:10px;">Холбогдох дугаар: {{ @$teamLeader->contact_phone}}</div>
    </div>
</div>

<style>
  table {
    border-collapse: collapse;
    border: 1px solid black;
    border-style: solid; /* Add this property to change the border style */
  }
  th, td {
    border: 1px solid black;
    padding-bottom: 2px;
    padding-top: 2px;
    text-align: left;
  }
</style>

</div>

</body>
</html>



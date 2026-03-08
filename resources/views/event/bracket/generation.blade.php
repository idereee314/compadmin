@extends('event.bracket.layout')

@section('styles')
@endsection

@section('content')
    <div class="container">
        @if (isset($round))
            @php
                // Extract all individual athletes from bracket pairs
                $all_athletes = [];
                if (isset($members) && count($members) > 0) {
                    foreach ($members as $m) {
                        if (!empty($m->lastname_one)) {
                            $all_athletes[] = [
                                'lastname'  => $m->lastname_one,
                                'firstname' => $m->firstname_one,
                                'academy'   => $m->acname_one ?? 'Академигүй'
                            ];
                        }
                        if (!empty($m->lastname_two)) {
                            $all_athletes[] = [
                                'lastname'  => $m->lastname_two,
                                'firstname' => $m->firstname_two,
                                'academy'   => $m->acname_two ?? 'Академигүй'
                            ];
                        }
                    }
                }

                $count = count($all_athletes);

                // Determine bracket type from eventConfig
                $printCode = @$eventConfig->bracketType->print_code ?? '';

                // Determine display mode: RR (2-6 players) or DOUBLE (7+ players)
                $bracketDisplayType = ($count <= 6) ? 'RR_2_6' : 'DOUBLE';

                // Set width for single elimination partial
                $width = '1600px';
            @endphp

            @if($printCode === 'print_de_single_bronze')
                {{-- Double Elimination - 1 Bronze --}}
                @if($bracketDisplayType === 'RR_2_6')
                    @include('event.bracket.double_elimination_1bronze.print_bracket_RRdouble')
                @else
                    @include('event.bracket.double_elimination_1bronze.print_bracket_double_elimination')
                @endif

            @elseif($printCode === 'print_single_elim')
                {{-- Single Elimination --}}
                @include('event.bracket.single_elimination.print_bracket')

            @else
                {{-- Double Elimination - 2 Bronze (default) --}}
                @if($bracketDisplayType === 'RR_2_6')
                    @include('event.bracket.double_elimination_2bronze.print_bracket_RRdouble')
                @else
                    @include('event.bracket.double_elimination_2bronze.print_bracket_double_elimination')
                @endif
            @endif
        @endif
        <div style="padding-top:50px">
            <a id="print" class="btn btn-primary font-weight-bold"
                href="/bracket/print/{{ $eventId }}/{{ $entryId }}/{{ $entryAgeId }}/{{ $entryBeltId }}/{{ $entryWeightId }}" target="_blank">{{ trans('display.general_print') }}</a>
            <a id="edit" class="btn btn-warning font-weight-bold" href="/bracket/edit/{{ $eventId }}/{{ $entryId }}/{{ $entryAgeId }}/{{ $entryBeltId }}/{{ $entryWeightId }}" target="_blank">{{ trans('display.general_edit') }}</a>
        </div>
    </div>
@stop

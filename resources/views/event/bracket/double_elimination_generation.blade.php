@extends('event.bracket.layout')

@section('styles')
@endsection

@section('content')
<div class="container">
    <div class="tournament-bracket tournament-bracket--rounded">
        {{-- Winners Bracket --}}
        @if(isset($winnersRounds) && isset($losersRounds))
            @for($round = 1; $round <= max($winnersRounds, $losersRounds); $round++)
                <div class="tournament-bracket__round">
                    <h3 class="tournament-bracket__round-title">
                        @if ($round <= $winnersRounds)
                            Winners Bracket Round {{$round}}
                        @else
                            Losers Bracket Round {{$round - $winnersRounds}}
                        @endif
                    </h3>
                    <ul class="tournament-bracket__list">
                        @foreach($matches[$round] as $match)
                            <li class="tournament-bracket__item">
                                <div class="tournament-bracket__match" tabindex="0">
                                    <table class="tournament-bracket__table">
                                        <tbody class="tournament-bracket__content">
                                            <tr class="tournament-bracket__team @if($match->winner == 1) tournament-bracket__team--winner @endif">
                                                <td class="tournament-bracket__country">
                                                    <abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{$match->team1->acname}}</abbr>
                                                </td>
                                                <td class="tournament-bracket__country">
                                                    <abbr class="tournament-bracket__code">
                                                        {{ $match->team1->lastname != null ? $match->team1->lastname.' '.$match->team1->firstname : 'BYE' }}
                                                    </abbr>
                                                    <br>
                                                    <span class="tournament-bracket__flag flag-icon flag-icon-ca" aria-label="Flag"></span>
                                                </td>
                                            </tr>
                                            <tr class="tournament-bracket__team @if($match->winner == 2) tournament-bracket__team--winner @endif">
                                                <td class="tournament-bracket__country">
                                                    <abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{$match->team2->acname}}</abbr>
                                                </td>
                                                <td class="tournament-bracket__country">
                                                    <abbr class="tournament-bracket__code">
                                                        {{ $match->team2->lastname != null ? $match->team2->lastname.' '.$match->team2->firstname : 'BYE' }}
                                                    </abbr>
                                                    <br>
                                                    <span class="tournament-bracket__flag flag-icon flag-icon-kz" aria-label="Flag"></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endfor
        @endif
    </div>
    <div style="padding-top:50px">      
        <a id="print" class="btn btn-primary font-weight-bold" href="/bracket/print/{{ $eventId }}/{{ $entryId }}/{{ $entryAgeId }}/{{ $entryBeltId }}/{{ $entryWeightId }}" target="_blank">{{trans('display.general_print')}}</a>
    </div>
</div>
@stop

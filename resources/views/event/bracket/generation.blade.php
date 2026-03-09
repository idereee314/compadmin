@extends('event.bracket.layout')

@section('styles')
@endsection

@section('content')
    <div class="container">
        <div class="tournament-bracket tournament-bracket--rounded">
            @if (isset($round))
                @php
                    // Build byeList: winners from first round who had a BYE opponent
                    $byeList = [];
                    foreach ($members as $key => $member) {
                        if ($member->lastname_one != null && $member->lastname_two == null) {
                            $byeList[$key] = ['lastname' => $member->lastname_one, 'firstname' => $member->firstname_one, 'academy' => $member->acname_one, 'reg_id' => $member->ro ?? null];
                        } elseif ($member->lastname_one == null && $member->lastname_two != null) {
                            $byeList[$key] = ['lastname' => $member->lastname_two, 'firstname' => $member->firstname_two, 'academy' => $member->acname_two, 'reg_id' => $member->rt ?? null];
                        } else {
                            $byeList[$key] = ['lastname' => null];
                        }
                    }

                    // Build round-by-round order_no mapping
                    $roundCount = $round;
                    $matchCount = count($members);
                    $getOrderNo = function(int $r, int $slot) use ($roundCount) {
                        if ($r === $roundCount - 1) return 9999;
                        if ($r === 0) return $slot + 1;
                        return ($r + 1) * 100 + $slot + 1;
                    };
                @endphp

                @for ($i = 0; $i < $round; $i++)
                    <div class="tournament-bracket__round">
                        <h3 class="tournament-bracket__round-title">Тойрог {{ $i + 1 }}</h3>
                        @if ($i == 0)
                            {{-- ROUND 1: show actual bracket pairings --}}
                            <ul class="tournament-bracket__list">
                                @foreach ($members as $mKey => $member)
                                    @php
                                        $md = $matchByOrder[$mKey + 1] ?? null;
                                        $r1won = ($md && $md->status === 'C');
                                    @endphp
                                    <li class="tournament-bracket__item">
                                        <div class="tournament-bracket__match" tabindex="0">
                                            <table class="tournament-bracket__table">
                                                <tbody class="tournament-bracket__content">
                                                    <tr class="tournament-bracket__team {{ $r1won && $isWinner($md->reg_one_id, $md) ? 'tournament-bracket__team--winner' : '' }}">
                                                        <td class="tournament-bracket__country">
                                                            <abbr class="tournament-bracket__code"
                                                                style="text-transform: capitalize !important">{{ $member->acname_one }}</abbr>
                                                        </td>
                                                        <td class="tournament-bracket__country">
                                                            <abbr class="tournament-bracket__code">
                                                                {!! $member->lastname_one != null ? e($member->lastname_one) . ' <strong>' . e($member->firstname_one) . '</strong>' : 'BYE' !!}
                                                            </abbr>
                                                        </td>
                                                        @if($r1won)
                                                            <td class="tournament-bracket__score"><span class="tournament-bracket__number">{{ (int)$md->red_score }}</span></td>
                                                        @endif
                                                    </tr>
                                                    <tr class="tournament-bracket__team {{ $r1won && $isWinner($md->reg_two_id, $md) ? 'tournament-bracket__team--winner' : '' }}">
                                                        <td class="tournament-bracket__country">
                                                            <abbr class="tournament-bracket__code"
                                                                style="text-transform: capitalize !important">{{ $member->acname_two }}</abbr>
                                                        </td>
                                                        <td class="tournament-bracket__country">
                                                            <abbr class="tournament-bracket__code">
                                                                {!! $member->lastname_two != null ? e($member->lastname_two) . ' <strong>' . e($member->firstname_two) . '</strong>' : 'BYE' !!}
                                                            </abbr>
                                                        </td>
                                                        @if($r1won)
                                                            <td class="tournament-bracket__score"><span class="tournament-bracket__number">{{ (int)$md->blue_score }}</span></td>
                                                        @endif
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            @php
                                $slotsInRound = (int)(count($members) / pow(2, $i));
                            @endphp
                            <ul class="tournament-bracket__list">
                                @for ($k = 0; $k < $slotsInRound; $k++)
                                    @php
                                        $orderNo = $getOrderNo($i, $k);
                                        $md = $matchByOrder[$orderNo] ?? null;
                                        $hasMatch = ($md !== null);
                                        $isComplete = ($hasMatch && $md->status === 'C');
                                    @endphp

                                    @if ($i == 1 && !$hasMatch)
                                        {{-- Round 2 fallback: show BYE winners --}}
                                        @php $t = $k * 2; @endphp
                                        <li class="tournament-bracket__item">
                                            <div class="tournament-bracket__match" tabindex="0">
                                                <table class="tournament-bracket__table">
                                                    <tbody class="tournament-bracket__content">
                                                        <tr class="tournament-bracket__team">
                                                            <td class="tournament-bracket__country">
                                                                <abbr class="tournament-bracket__code"
                                                                    style="text-transform: capitalize !important">{{ @$byeList[$t]['academy'] }}</abbr>
                                                            </td>
                                                            <td class="tournament-bracket__country">
                                                                <abbr class="tournament-bracket__code">
                                                                    {!! @$byeList[$t]['lastname'] != null
                                                                        ? e(@$byeList[$t]['lastname']) . ' <strong>' . e(@$byeList[$t]['firstname']) . '</strong>'
                                                                        : 'TBD' !!}
                                                                </abbr>
                                                            </td>
                                                        </tr>
                                                        <tr class="tournament-bracket__team">
                                                            <td class="tournament-bracket__country">
                                                                <abbr class="tournament-bracket__code"
                                                                    style="text-transform: capitalize !important">{{ @$byeList[$t + 1]['academy'] }}</abbr>
                                                            </td>
                                                            <td class="tournament-bracket__country">
                                                                <abbr class="tournament-bracket__code">
                                                                    {!! @$byeList[$t + 1]['lastname'] != null
                                                                        ? e(@$byeList[$t + 1]['lastname']) . ' <strong>' . e(@$byeList[$t + 1]['firstname']) . '</strong>'
                                                                        : 'TBD' !!}
                                                                </abbr>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </li>
                                    @elseif ($hasMatch)
                                        {{-- Match exists: show real data with scores --}}
                                        <li class="tournament-bracket__item">
                                            <div class="tournament-bracket__match" tabindex="0">
                                                <table class="tournament-bracket__table">
                                                    <tbody class="tournament-bracket__content">
                                                        <tr class="tournament-bracket__team {{ $isComplete && $isWinner($md->reg_one_id, $md) ? 'tournament-bracket__team--winner' : '' }}">
                                                            <td class="tournament-bracket__country">
                                                                <abbr class="tournament-bracket__code"
                                                                    style="text-transform: capitalize !important">{{ $md->acname_one ?? '' }}</abbr>
                                                            </td>
                                                            <td class="tournament-bracket__country">
                                                                <abbr class="tournament-bracket__code">
                                                                    {!! $md->lastname_one ? e($md->lastname_one) . ' <strong>' . e($md->firstname_one) . '</strong>' : 'TBD' !!}
                                                                </abbr>
                                                            </td>
                                                            @if($isComplete)
                                                                <td class="tournament-bracket__score"><span class="tournament-bracket__number">{{ (int)$md->red_score }}</span></td>
                                                            @endif
                                                        </tr>
                                                        <tr class="tournament-bracket__team {{ $isComplete && $isWinner($md->reg_two_id, $md) ? 'tournament-bracket__team--winner' : '' }}">
                                                            <td class="tournament-bracket__country">
                                                                <abbr class="tournament-bracket__code"
                                                                    style="text-transform: capitalize !important">{{ $md->acname_two ?? '' }}</abbr>
                                                            </td>
                                                            <td class="tournament-bracket__country">
                                                                <abbr class="tournament-bracket__code">
                                                                    {!! $md->lastname_two ? e($md->lastname_two) . ' <strong>' . e($md->firstname_two) . '</strong>' : 'TBD' !!}
                                                                </abbr>
                                                            </td>
                                                            @if($isComplete)
                                                                <td class="tournament-bracket__score"><span class="tournament-bracket__number">{{ (int)$md->blue_score }}</span></td>
                                                            @endif
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </li>
                                    @else
                                        {{-- No match data: show TBD --}}
                                        <li class="tournament-bracket__item">
                                            <div class="tournament-bracket__match" tabindex="0">
                                                <table class="tournament-bracket__table">
                                                    <tbody class="tournament-bracket__content">
                                                        <tr class="tournament-bracket__team">
                                                            <td class="tournament-bracket__country">
                                                                <abbr class="tournament-bracket__code">TBD</abbr>
                                                            </td>
                                                        </tr>
                                                        <tr class="tournament-bracket__team">
                                                            <td class="tournament-bracket__country">
                                                                <abbr class="tournament-bracket__code">TBD</abbr>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </li>
                                    @endif
                                @endfor
                            </ul>
                        @endif
                    </div>
                @endfor
            @endif
        </div>
        <div style="padding-top:50px">
            <a id="print" class="btn btn-primary font-weight-bold"
                href="/bracket/print/{{ $eventId }}/{{ $entryId }}/{{ $entryAgeId }}/{{ $entryBeltId }}/{{ $entryWeightId }}" target="_blank">{{ trans('display.general_print') }}</a>
            <a id="edit" class="btn btn-warning font-weight-bold" href="/bracket/edit/{{ $eventId }}/{{ $entryId }}/{{ $entryAgeId }}/{{ $entryBeltId }}/{{ $entryWeightId }}" target="_blank">{{ trans('display.general_edit') }}</a>
        </div>
    </div>
@stop

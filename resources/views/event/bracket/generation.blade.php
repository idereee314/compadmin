@extends('event.bracket.layout')

@section('styles')
@endsection

@section('content')
    <div class="container">
        <div class="tournament-bracket tournament-bracket--rounded">
            @if (isset($round))
                @php
                    // Build round 1 source: prefer match data (seeded order) over bracket data (legacy order)
                    $round1Sources = [];
                    $useMatchData = false;
                    foreach ($members as $mKey => $member) {
                        $md = $matchByOrder[$mKey + 1] ?? null;
                        if ($md && ($md->lastname_one || $md->lastname_two)) {
                            $useMatchData = true;
                        }
                        $round1Sources[$mKey] = $md;
                    }

                    // Build byeList: winners from first round who had a BYE opponent
                    $byeList = [];
                    foreach ($members as $key => $member) {
                        $src = ($useMatchData && isset($round1Sources[$key]) && $round1Sources[$key]) ? $round1Sources[$key] : null;
                        $ln1 = $src ? $src->lastname_one : $member->lastname_one;
                        $fn1 = $src ? $src->firstname_one : $member->firstname_one;
                        $ac1 = $src ? ($src->acname_one ?? '') : $member->acname_one;
                        $rid1 = $src ? ($src->reg_one_id ?? null) : ($member->ro ?? null);
                        $ln2 = $src ? $src->lastname_two : $member->lastname_two;
                        $fn2 = $src ? $src->firstname_two : $member->firstname_two;
                        $ac2 = $src ? ($src->acname_two ?? '') : $member->acname_two;
                        $rid2 = $src ? ($src->reg_two_id ?? null) : ($member->rt ?? null);
                        if ($ln1 != null && $ln2 == null) {
                            $byeList[$key] = ['lastname' => $ln1, 'firstname' => $fn1, 'academy' => $ac1, 'reg_id' => $rid1];
                        } elseif ($ln1 == null && $ln2 != null) {
                            $byeList[$key] = ['lastname' => $ln2, 'firstname' => $fn2, 'academy' => $ac2, 'reg_id' => $rid2];
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
                                        // Use match data for names when available (seeded order)
                                        $ln1 = ($useMatchData && $md) ? $md->lastname_one : $member->lastname_one;
                                        $fn1 = ($useMatchData && $md) ? $md->firstname_one : $member->firstname_one;
                                        $ac1 = ($useMatchData && $md) ? ($md->acname_one ?? '') : $member->acname_one;
                                        $ln2 = ($useMatchData && $md) ? $md->lastname_two : $member->lastname_two;
                                        $fn2 = ($useMatchData && $md) ? $md->firstname_two : $member->firstname_two;
                                        $ac2 = ($useMatchData && $md) ? ($md->acname_two ?? '') : $member->acname_two;
                                    @endphp
                                    <li class="tournament-bracket__item">
                                        <div class="tournament-bracket__match" tabindex="0">
                                            <table class="tournament-bracket__table">
                                                <tbody class="tournament-bracket__content">
                                                    <tr class="tournament-bracket__team {{ $r1won && $isWinner($md->reg_one_id, $md) ? 'tournament-bracket__team--winner' : '' }}">
                                                        <td class="tournament-bracket__country">
                                                            <abbr class="tournament-bracket__code"
                                                                style="text-transform: capitalize !important">{{ $ac1 }}</abbr>
                                                        </td>
                                                        <td class="tournament-bracket__country">
                                                            <abbr class="tournament-bracket__code">
                                                                {!! $ln1 != null ? e($ln1) . ' <strong>' . e($fn1) . '</strong>' : 'BYE' !!}
                                                            </abbr>
                                                        </td>
                                                        @if($r1won)
                                                            <td class="tournament-bracket__score"><span class="tournament-bracket__number">{{ (int)$md->red_score }}</span></td>
                                                        @endif
                                                    </tr>
                                                    <tr class="tournament-bracket__team {{ $r1won && $isWinner($md->reg_two_id, $md) ? 'tournament-bracket__team--winner' : '' }}">
                                                        <td class="tournament-bracket__country">
                                                            <abbr class="tournament-bracket__code"
                                                                style="text-transform: capitalize !important">{{ $ac2 }}</abbr>
                                                        </td>
                                                        <td class="tournament-bracket__country">
                                                            <abbr class="tournament-bracket__code">
                                                                {!! $ln2 != null ? e($ln2) . ' <strong>' . e($fn2) . '</strong>' : 'BYE' !!}
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

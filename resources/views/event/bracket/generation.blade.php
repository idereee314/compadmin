@extends('event.bracket.layout')

@section('styles')
@endsection

@section('content')
    <div class="container">
        @if (isset($round))
            <?php
            // Extract all individual athletes from bracket pairs
            $all_athletes = [];
            foreach ($members as $m) {
                if (isset($m->lastname_one) && $m->lastname_one != null) {
                    $all_athletes[] = [
                        'lastname'  => $m->lastname_one,
                        'firstname' => $m->firstname_one,
                        'academy'   => $m->acname_one ?? ''
                    ];
                }
                if (isset($m->lastname_two) && $m->lastname_two != null) {
                    $all_athletes[] = [
                        'lastname'  => $m->lastname_two,
                        'firstname' => $m->firstname_two,
                        'academy'   => $m->acname_two ?? ''
                    ];
                }
            }
            $athleteCount = count($all_athletes);
            ?>

            {{-- 6 PLAYERS: Pool format with snake seeding --}}
            @if($athleteCount == 6)
                <?php
                    // Snake seeding: Pool A = Seed 1, Seed 4, Seed 5 / Pool B = Seed 2, Seed 3, Seed 6
                    $poolA = [$all_athletes[0], $all_athletes[3], $all_athletes[4]];
                    $poolB = [$all_athletes[1], $all_athletes[2], $all_athletes[5]];

                    // Pool A: Match 1,3,5
                    $matchesA = [
                        ['r' => $poolA[0], 'b' => $poolA[1], 'l' => 'Match 1'],
                        ['r' => $poolA[0], 'b' => $poolA[2], 'l' => 'Match 3'],
                        ['r' => $poolA[1], 'b' => $poolA[2], 'l' => 'Match 5'],
                    ];

                    // Pool B: Match 2,4,6
                    $matchesB = [
                        ['r' => $poolB[0], 'b' => $poolB[1], 'l' => 'Match 2'],
                        ['r' => $poolB[0], 'b' => $poolB[2], 'l' => 'Match 4'],
                        ['r' => $poolB[1], 'b' => $poolB[2], 'l' => 'Match 6'],
                    ];
                ?>

                <div style="display:flex; justify-content:space-between; gap: 40px; margin-bottom: 35px;">
                    {{-- POOL A --}}
                    <div style="flex:1;">
                        <div style="background:#f8fafc; padding:10px; font-weight:bold; border:1px solid #cbd5e1; margin-bottom:15px; border-left:6px solid #ef4444;">
                            Pool A - Тойргоор (Round Robin)
                        </div>

                        @foreach($matchesA as $m)
                            <div style="margin-bottom: 14px;">
                                <div style="font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 3px;">
                                    {{ $m['l'] }}
                                </div>

                                <table style="width: 100%; border-collapse: collapse; border: 1px solid #475569; background:#fff;">
                                    <tr>
                                        <td style="height: 44px; padding: 0;">
                                            <div style="width: 6px; height: 100%; background: #ef4444; float: left;"></div>
                                            <div style="padding: 4px 10px; line-height: 1.2;">
                                                {{ $m['r']['lastname'] }} <strong>{{ $m['r']['firstname'] }}</strong>
                                                <div style="font-size: 10px; color: #64748b;">{{ $m['r']['academy'] }}</div>
                                            </div>
                                        </td>
                                        <td style="width: 50px; background: #f1f5f9; border-left: 1px solid #475569;"></td>
                                    </tr>

                                    <tr>
                                        <td style="height: 44px; padding: 0; border-top: 1px solid #e2e8f0;">
                                            <div style="width: 6px; height: 100%; background: #3b82f6; float: left;"></div>
                                            <div style="padding: 4px 10px; line-height: 1.2;">
                                                {{ $m['b']['lastname'] }} <strong>{{ $m['b']['firstname'] }}</strong>
                                                <div style="font-size: 10px; color: #64748b;">{{ $m['b']['academy'] }}</div>
                                            </div>
                                        </td>
                                        <td style="width: 50px; background: #f1f5f9; border-left: 1px solid #475569;"></td>
                                    </tr>
                                </table>
                            </div>
                        @endforeach
                    </div>

                    {{-- POOL B --}}
                    <div style="flex:1;">
                        <div style="background:#f8fafc; padding:10px; font-weight:bold; border:1px solid #cbd5e1; margin-bottom:15px; border-left:6px solid #3b82f6;">
                            Pool B - Тойргоор (Round Robin)
                        </div>

                        @foreach($matchesB as $m)
                            <div style="margin-bottom: 14px;">
                                <div style="font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 3px;">
                                    {{ $m['l'] }}
                                </div>

                                <table style="width: 100%; border-collapse: collapse; border: 1px solid #475569; background:#fff;">
                                    <tr>
                                        <td style="height: 44px; padding: 0;">
                                            <div style="width: 6px; height: 100%; background: #ef4444; float: left;"></div>
                                            <div style="padding: 4px 10px; line-height: 1.2;">
                                                {{ $m['r']['lastname'] }} <strong>{{ $m['r']['firstname'] }}</strong>
                                                <div style="font-size: 10px; color: #64748b;">{{ $m['r']['academy'] }}</div>
                                            </div>
                                        </td>
                                        <td style="width: 50px; background: #f1f5f9; border-left: 1px solid #475569;"></td>
                                    </tr>

                                    <tr>
                                        <td style="height: 44px; padding: 0; border-top: 1px solid #e2e8f0;">
                                            <div style="width: 6px; height: 100%; background: #3b82f6; float: left;"></div>
                                            <div style="padding: 4px 10px; line-height: 1.2;">
                                                {{ $m['b']['lastname'] }} <strong>{{ $m['b']['firstname'] }}</strong>
                                                <div style="font-size: 10px; color: #64748b;">{{ $m['b']['academy'] }}</div>
                                            </div>
                                        </td>
                                        <td style="width: 50px; background: #f1f5f9; border-left: 1px solid #475569;"></td>
                                    </tr>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div style="text-align:center; margin-top: 10px; font-weight:bold; font-size: 13px; color:#64748b;">
                    * 6 тамирчныг 3,3-аар нь 2 хэсэгт хувааж тойргоор барилдуулна (Pool A / Pool B).
                </div>

                {{-- BRACKET (Semi-finals + Final) --}}
                <div style="display:flex; align-items:flex-start; justify-content:center; gap: 80px; margin-top: 35px; position:relative;">
                    <div style="width: 360px;">
                        <!-- MATCH 7 -->
                        <div style="margin-bottom: 35px; position:relative;">
                            <div style="font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 3px;">Match 7 (Semi)</div>

                            <table style="width: 100%; border-collapse: collapse; border: 1px solid #111827; background:#fff;">
                                <tr>
                                    <td style="height: 46px; padding: 0;">
                                        <div style="width: 6px; height: 100%; background:#ef4444; float:left;"></div>
                                        <div style="padding: 5px 12px;"></div>
                                    </td>
                                    <td style="width: 60px; background: #f1f5f9; border-left: 1px solid #111827;"></td>
                                </tr>
                                <tr>
                                    <td style="height: 46px; padding: 0; border-top: 1px solid #e2e8f0;">
                                        <div style="width: 6px; height: 100%; background:#3b82f6; float:left;"></div>
                                        <div style="padding: 5px 12px;"></div>
                                    </td>
                                    <td style="width: 60px; background: #f1f5f9; border-left: 1px solid #111827;"></td>
                                </tr>
                            </table>

                            <div style="position:absolute; right:-60px; top:50%; width:60px; border-top:1px solid #94a3b8;"></div>
                            <div style="position:absolute; right:-60px; top:50%; height:60px; border-right:1px solid #94a3b8;"></div>
                            <div style="position:absolute; right:-80px; top:calc(50% + 60px); width:20px; border-top:1px solid #94a3b8;"></div>
                        </div>

                        <!-- MATCH 8 -->
                        <div style="position:relative;">
                            <div style="font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 3px;">Match 8 (Semi)</div>

                            <table style="width: 100%; border-collapse: collapse; border: 1px solid #111827; background:#fff;">
                                <tr>
                                    <td style="height: 46px; padding: 0;">
                                        <div style="width: 6px; height: 100%; background:#ef4444; float:left;"></div>
                                        <div style="padding: 5px 12px;"></div>
                                    </td>
                                    <td style="width: 60px; background: #f1f5f9; border-left: 1px solid #111827;"></td>
                                </tr>
                                <tr>
                                    <td style="height: 46px; padding: 0; border-top: 1px solid #e2e8f0;">
                                        <div style="width: 6px; height: 100%; background:#3b82f6; float:left;"></div>
                                        <div style="padding: 5px 12px;"></div>
                                    </td>
                                    <td style="width: 60px; background: #f1f5f9; border-left: 1px solid #111827;"></td>
                                </tr>
                            </table>

                            <div style="position:absolute; right:-60px; top:50%; width:60px; border-top:1px solid #94a3b8;"></div>
                            <div style="position:absolute; right:-60px; top:calc(50% - 60px); height:60px; border-right:1px solid #94a3b8;"></div>
                            <div style="position:absolute; right:-80px; top:calc(50% - 60px); width:20px; border-top:1px solid #94a3b8;"></div>
                        </div>
                    </div>

                    <!-- FINAL -->
                    <div style="width: 360px; margin-top: 60px;">
                        <div style="font-size: 10px; font-weight: bold; color: #0f172a; text-transform: uppercase; margin-bottom: 6px; text-align:center;">
                            Match 9 (FINAL)
                        </div>

                        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; background:#fff;">
                            <tr>
                                <td style="height: 52px; padding: 0;">
                                    <div style="width: 6px; height: 100%; background:#ef4444; float:left;"></div>
                                    <div style="padding: 6px 12px; font-weight:bold;"></div>
                                </td>
                                <td style="width: 70px; background: #f1f5f9; border-left: 1px solid #000;"></td>
                            </tr>
                            <tr>
                                <td style="height: 52px; padding: 0; border-top: 1px solid #000;">
                                    <div style="width: 6px; height: 100%; background:#3b82f6; float:left;"></div>
                                    <div style="padding: 6px 12px; font-weight:bold;"></div>
                                </td>
                                <td style="width: 70px; background: #f1f5f9; border-left: 1px solid #000;"></td>
                            </tr>
                        </table>
                    </div>
                </div>

            {{-- OTHER PLAYER COUNTS: Standard elimination bracket --}}
            @else
                <?php
                $byeList = [];
                
                foreach ($members as $key => $member) {
                    if ($member->lastname_one != null && $member->lastname_two == null) {
                        $byeList[$key] = ['lastname' => $member->lastname_one, 'firstname' => $member->firstname_one, 'academy' => $member->acname_one, 'is_dq_one' => $member->is_dq_one];
                    } elseif ($member->lastname_one == null && $member->lastname_two != null) {
                        $byeList[$key] = ['lastname' => $member->lastname_two, 'firstname' => $member->firstname_two, 'academy' => $member->acname_two, 'is_dq_two' => $member->is_dq_two];
                    } else {
                        $byeList[$key] = ['lastname' => null];
                    }
                }
                ?>
                <div class="tournament-bracket tournament-bracket--rounded">
                    @for ($i = 0; $i < $round; $i++)
                        <div class="tournament-bracket__round">
                            <h3 class="tournament-bracket__round-title">Тойрог {{ $i + 1 }}</h3>
                            @if ($i == 0)
                                <ul class="tournament-bracket__list">
                                    @foreach ($members as $member)
                                        <li class="tournament-bracket__item">
                                            <div class="tournament-bracket__match" tabindex="0">
                                                <table class="tournament-bracket__table">
                                                    <tbody class="tournament-bracket__content">
                                                        <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                                            <td class="tournament-bracket__country">
                                                                <abbr class="tournament-bracket__code"
                                                                    style="text-transform: capitalize !important">{{ $member->acname_one }}</abbr>
                                                            </td>
                                                            <td class="tournament-bracket__country">
                                                                <abbr
                                                                    class="tournament-bracket__code">{{ $member->lastname_one != null ? $member->lastname_one . ' ' . $member->firstname_one : 'BYE' }}</abbr><br>
                                                                <span class="tournament-bracket__flag flag-icon flag-icon-ca"
                                                                    aria-label="Flag"></span>
                                                            </td>
                                                        </tr>
                                                        <tr class="tournament-bracket__team">
                                                            <td class="tournament-bracket__country">
                                                                <abbr class="tournament-bracket__code"
                                                                    style="text-transform: capitalize !important">{{ $member->acname_two }}</abbr>
                                                            </td>
                                                            <td class="tournament-bracket__country">
                                                                <abbr
                                                                    class="tournament-bracket__code">{{ $member->lastname_two != null ? $member->lastname_two . ' ' . $member->firstname_two : 'BYE' }}</abbr><br>
                                                                <span class="tournament-bracket__flag flag-icon flag-icon-kz"
                                                                    aria-label="Flag"></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <?php
                                $total = $total / 2;
                                ?>
                                <ul class="tournament-bracket__list">
                                    @for ($k = 0; $k < $total; $k++)
                                        @if ($i == 1)
                                            <?php $t = $k * 2; ?>
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
                                                                    <abbr
                                                                        class="tournament-bracket__code">{!! @$byeList[$t]['lastname'] != null
                                                                            ? @$byeList[$t]['lastname'] . ' <strong>' . @$byeList[$t]['firstname'] . '</strong>'
                                                                            : 'TBD' !!}</abbr>
                                                                </td>
                                                            </tr>
                                                            <tr
                                                                class="tournament-bracket__team tournament-bracket__team--winner">
                                                                <td class="tournament-bracket__country">
                                                                    <abbr class="tournament-bracket__code"
                                                                        style="text-transform: capitalize !important">{{ @$byeList[$t + 1]['academy'] }}</abbr>
                                                                </td>
                                                                <td class="tournament-bracket__country">
                                                                    <abbr
                                                                        class="tournament-bracket__code">{!! @$byeList[$t + 1]['lastname'] != null
                                                                            ? @$byeList[$t + 1]['lastname'] . ' <strong>' . @$byeList[$t + 1]['firstname'] . '</strong>'
                                                                            : 'TBD' !!}</abbr>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </li>
                                        @else
                                            <li class="tournament-bracket__item">
                                                <div class="tournament-bracket__match" tabindex="0">
                                                    <table class="tournament-bracket__table">
                                                        <tbody class="tournament-bracket__content">
                                                            <tr class="tournament-bracket__team">
                                                                <td class="tournament-bracket__country">
                                                                    <abbr class="tournament-bracket__code">TBD</abbr>
                                                                </td>
                                                            </tr>
                                                            <tr
                                                                class="tournament-bracket__team tournament-bracket__team--winner">
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
                </div>
            @endif
        @endif
        <div style="padding-top:50px">
            <a id="print" class="btn btn-primary font-weight-bold"
                href="/bracket/print/{{ $eventId }}/{{ $entryId }}/{{ $entryAgeId }}/{{ $entryBeltId }}/{{ $entryWeightId }}" target="_blank">{{ trans('display.general_print') }}</a>
            <a id="edit" class="btn btn-warning font-weight-bold" href="/bracket/edit/{{ $eventId }}/{{ $entryId }}/{{ $entryAgeId }}/{{ $entryBeltId }}/{{ $entryWeightId }}" target="_blank">{{ trans('display.general_edit') }}</a>
        </div>
    </div>
@stop

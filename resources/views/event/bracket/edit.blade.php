@extends('event.bracket.layout')

@section('styles')
<!-- Include custom styles if necessary -->
<style>
    .tournament-bracket__list {
        list-style: none;
        padding: 0;
    }

    .tournament-bracket__item {
        cursor: move;
    }

    .ui-state-highlight {
        height: 2.5em;
        line-height: 2.5em;
        border: dashed 2px #009ef7;
        background-color: #f0f0f0;
    }
</style>
@endsection

@section('content')
<div class="container">
    <!-- The form for updating the bracket order -->
    <form class="form" method="POST" id="update-event-bracket-form" action="{{ route('event.registration.bracket.update', [$eventId, $entryId, $entryAgeId, $entryBeltId, $entryWeightId]) }}">
        @csrf
        @method('PUT') <!-- Use PUT method for updating -->

        <div class="tournament-bracket tournament-bracket--rounded">
        @if(isset($round))
            <?php
                $byeList = array();

                foreach($members as $key => $member) {
                    if($member->lastname_one != null && $member->lastname_two == null) {
                        $byeList[$key] = array('lastname'=> $member->lastname_one, 'firstname'=> $member->firstname_one, 'academy'=> $member->acname_one, 'is_dq_one'=> $member->is_dq_one);
                    } else if($member->lastname_one == null && $member->lastname_two != null) {
                        $byeList[$key] = array('lastname'=> $member->lastname_two, 'firstname'=> $member->firstname_two, 'academy'=> $member->acname_two, 'is_dq_two'=> $member->is_dq_two);
                    } else {
                        $byeList[$key] = array('lastname'=> null);
                    }
                }
            ?>

            <!-- Loop through rounds -->
            @for($i = 0; $i < $round; $i++)
            <div class="tournament-bracket__round">
                <h3 class="tournament-bracket__round-title">Тойрог {{$i + 1}}</h3>

                @if($i == 0)
                    <ul class="tournament-bracket__list" id="sortable-round-{{$i}}">
                    @foreach($members as $member)
                        <li class="tournament-bracket__item" data-id="{{ $member->ro }}">
                            <div class="tournament-bracket__match" tabindex="0">
                                <table class="tournament-bracket__table">
                                    <tbody class="tournament-bracket__content">
                                        <tr class="tournament-bracket__team tournament-bracket__team--winner">
                                            <td class="tournament-bracket__country">
                                                <abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{$member->acname_one}}</abbr>
                                            </td>
                                            <td class="tournament-bracket__country">
                                                <abbr class="tournament-bracket__code">{{ $member->lastname_one != null? $member->lastname_one.' '.$member->firstname_one: 'BYE'}}</abbr>
                                            </td>                                  
                                        </tr>
                                        <tr class="tournament-bracket__team">
                                            <td class="tournament-bracket__country">
                                                <abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{$member->acname_two}}</abbr>
                                            </td>
                                            <td class="tournament-bracket__country">
                                                <abbr class="tournament-bracket__code">{{ $member->lastname_two != null? $member->lastname_two.' '.$member->firstname_two: 'BYE'}}</abbr>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                    @endforeach
                    </ul>
                @else
                    <!-- Handle later rounds with BYEs -->
                    <?php $total = $total / 2; ?>
                    <ul class="tournament-bracket__list" id="sortable-round-{{$i}}">
                    @for($k = 0; $k < $total; $k++)
                        @if($i == 1)
                            <?php $t = $k * 2; ?>
                            <li class="tournament-bracket__item" data-id="{{ @$byeList[$t]['lastname'] }}">
                                <div class="tournament-bracket__match" tabindex="0">
                                    <table class="tournament-bracket__table">
                                        <tbody class="tournament-bracket__content">
                                            <tr class="tournament-bracket__team">
                                                <td class="tournament-bracket__country">
                                                    <abbr class="tournament-bracket__code" style="text-transform: capitalize !important">{{@$byeList[$t]['academy']}}</abbr>
                                                </td>
                                                <td class="tournament-bracket__country">
                                                    <abbr class="tournament-bracket__code">{!! @$byeList[$t]['lastname'] != null? @$byeList[$t]['lastname'].' <strong>'.@$byeList[$t]['firstname'].'</strong>': 'TBD'!!}</abbr>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </li>
                        @else
                            <!-- TBD logic -->
                            <li class="tournament-bracket__item">
                                <div class="tournament-bracket__match" tabindex="0">
                                    <table class="tournament-bracket__table">
                                        <tbody class="tournament-bracket__content">
                                            <tr class="tournament-bracket__team">
                                                <td class="tournament-bracket__country">
                                                    <abbr class="tournament-bracket__code">TBD</abbr>
                                                </td>
                                            </tr>
                                            <tr class="tournament-bracket__team tournament-bracket__team--winner">
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

        <!-- Hidden input to store the new order -->
        <input type="hidden" id="bracket-order" name="bracket_order" />

        <!-- Submit Button to Save Order -->
        <button type="submit" class="btn btn-success">Save Bracket Order</button>
    </form> 
</div>
@endsection

@section('scripts')
<!-- Include Sortable.js for drag-and-drop functionality -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Sortable for each round
        var rounds = document.querySelectorAll('.tournament-bracket__list');
        rounds.forEach(function (round) {
            Sortable.create(round, {
                animation: 150,
                onEnd: function (evt) {
                    let order = [];
                    round.querySelectorAll('li').forEach(function (item) {
                        order.push(item.getAttribute('data-id'));
                    });

                    // Store the new order in the hidden input
                    document.getElementById('bracket-order').value = JSON.stringify(order);
                }
            });
        });
    });
</script>
@endsection

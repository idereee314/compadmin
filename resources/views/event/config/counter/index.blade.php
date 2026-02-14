@if (!isset($matchId) || empty($matchId))
  <script>
    window.close();
  </script>
  {{-- Prevent further rendering --}}
  @php exit; @endphp
@endif

{{-- @extends('default')

@section('styles')
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.css')}}">
@endsection

@section('content') --}}


<link rel="stylesheet" href="{{asset('css/scoreboard/scoreboard.css')}}">

<form id="winnerForm" action="{{ route('event.config.match.winner', ['match_id' => $matchId]) }}" method="POST"  style="display:none;">

</form>

    <div class="container">
        <div class="row">
            <div class="player-info">
                <div class="player-row-1">
                    <img src="{{asset('assets/images/flags/4x3/' . ($registered[0]->abb )) .'.svg'}}" alt="Mongolia Flag" class="player-flag">
                    <div class="player-name">
                        @if (!empty($registered) && isset($registered[0]))
                            {{$registered[0]->member->firstname ?? '-'}}
                            {{$registered[0]->member->lastname }}
                        @else
                            TBD
                        @endif
                    </div>
                </div>
                <div class="player-row-2">
                    <div class="country-code">{{$registered[1]->abb_full ?? '-'}}</div>
                    <img src="{{asset('assets/images/logo/club/' . ($registered[0]->academy_id )) .'.jpg'}}" alt="Club Logo" class="club-logo"
                    onerror="this.src='{{asset('assets/images/logo/club/0.png')}}'">
                    <div class="club-name">
                        @if (!empty($registered) && isset($registered[0]))
                            {{$registered[0]->academy->name }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="row-content">
                <!-- First row content goes here -->
            </div>
            <div class="action-buttons" id="actionButtons1">
                <table class="button-table">
                    <tr>
                        <td><button class="action-button positive" onclick="event.stopPropagation(); adjustScore('red', 1)">+1</button></td>
                        <td><button class="action-button positive" onclick="event.stopPropagation(); adjustScore('red', 2)">+2</button></td>
                        <td><button class="action-button positive" onclick="event.stopPropagation(); adjustScore('red', 3)">+3</button></td>
                        <td><button class="action-button positive" onclick="event.stopPropagation(); adjustScore('red', 4)">+4</button></td>
                        <td><button class="action-button positive" onclick="event.stopPropagation(); adjustAdvantage('red', 1)">+A</button></td>
                        <td><button class="action-button positive" onclick="event.stopPropagation(); adjustPenalty('red', 1)">+P</button></td>
                        <td><button class="action-button white" onclick="event.stopPropagation(); toggleMedical('red')">MEDIC</button></td>
                    </tr>
                    <tr>
                        <td><button class="action-button negative" onclick="event.stopPropagation(); adjustScore('red', -1)">-1</button></td>
                        <td><button class="action-button negative" onclick="event.stopPropagation(); adjustScore('red', -2)">-2</button></td>
                        <td><button class="action-button negative" onclick="event.stopPropagation(); adjustScore('red', -3)">-3</button></td>
                        <td><button class="action-button negative" onclick="event.stopPropagation(); adjustScore('red', -4)">-4</button></td>
                        <td><button class="action-button negative" onclick="event.stopPropagation(); adjustAdvantage('red', -1)">-A</button></td>
                        <td><button class="action-button negative" onclick="event.stopPropagation(); adjustPenalty('red', -1)">-P</button></td>
                        <td><button class="action-button white" onclick="event.stopPropagation(); toggleStalling('red')">STALLING</button></td>
                    </tr>
                </table>
            </div>
            <div class="scores-container">
                <div class="stalling-alert" id="redStallingAlert" onclick="stopStalling('red')">
                    <div class="stalling-timer" id="redStallingTimer">00:10</div>
                    <div class="stalling-label">Stalling</div>
                </div>
                <div class="medical-alert" id="redMedicalAlert">
                    <div class="medical-timer" id="redMedicalTimer">02:00</div>
                    <div class="medical-label">Medical</div>
                    <div class="medical-controls">
                        <button class="medical-control-btn center" id="redMedicalPlay" onclick="event.stopPropagation(); toggleMedicalPlay('red')">PLAY</button>
                        <button class="medical-control-btn top" onclick="event.stopPropagation(); swapMedical('red')">SWAP</button>
                        <button class="medical-control-btn bottom" onclick="event.stopPropagation(); resetMedical('red')">RESET</button>
                        <button class="medical-control-btn left" onclick="event.stopPropagation(); adjustMedicalTimer('red', -1)">-1</button>
                        <button class="medical-control-btn right" onclick="event.stopPropagation(); adjustMedicalTimer('red', 1)">+1</button>
                    </div>
                </div>
                <div class="small-scores">
                    <div class="small-score-wrapper">
                        <div class="small-score-label" id="redAdvLabel">Advantage</div>
                        <div class="small-score" id="redAdv">0</div>
                    </div>
                    <div class="small-score-wrapper">
                        <div class="small-score-label" id="redPenLabel">Penalty</div>
                        <div class="small-score" id="redPen">0</div>
                    </div>
                </div>
                <div class="score-square red" id="redScore">0</div>
            </div>
        </div>
        <div class="row">
            <div class="player-info">
                <div class="player-row-1">
                    <img src="{{asset('assets/images/flags/4x3/' . ($registered[1]->abb )) .'.svg'}}" alt="Mongolia Flag" class="player-flag">
                    <div class="player-name">
                        @if (!empty($registered) && isset($registered[1]))
                            {{$registered[1]->member->firstname ?? '-'}}
                            {{$registered[1]->member->lastname }}
                        @else
                            TBD
                        @endif
                    </div>
                </div>
                <div class="player-row-2">
                    <div class="country-code">{{$registered[1]->abb_full ?? '-'}}</div>
                    <img src="{{asset('assets/images/logo/club/' . ($registered[1]->academy_id )) .'.jpg'}}" alt="Club Logo" class="club-logo"
                    onerror="this.src='{{asset('assets/images/logo/club/0.png')}}'">
                    <div class="club-name">
                        @if (!empty($registered) && isset($registered[1]))
                            {{$registered[1]->academy->name ?? '-'}}
                        @endif
                    </div>
                </div>
            </div>
            <div class="row-content">
                <!-- Second row content goes here -->
            </div>
            <div class="action-buttons" id="actionButtons2">
                <table class="button-table">
                    <tr>
                        <td><button class="action-button positive" onclick="event.stopPropagation(); adjustScore('blue', 1)">+1</button></td>
                        <td><button class="action-button positive" onclick="event.stopPropagation(); adjustScore('blue', 2)">+2</button></td>
                        <td><button class="action-button positive" onclick="event.stopPropagation(); adjustScore('blue', 3)">+3</button></td>
                        <td><button class="action-button positive" onclick="event.stopPropagation(); adjustScore('blue', 4)">+4</button></td>
                        <td><button class="action-button positive" onclick="event.stopPropagation(); adjustAdvantage('blue', 1)">+A</button></td>
                        <td><button class="action-button positive" onclick="event.stopPropagation(); adjustPenalty('blue', 1)">+P</button></td>
                        <td><button class="action-button white" onclick="event.stopPropagation(); toggleMedical('blue')">MEDIC</button></td>
                    </tr>
                    <tr>
                        <td><button class="action-button negative" onclick="event.stopPropagation(); adjustScore('blue', -1)">-1</button></td>
                        <td><button class="action-button negative" onclick="event.stopPropagation(); adjustScore('blue', -2)">-2</button></td>
                        <td><button class="action-button negative" onclick="event.stopPropagation(); adjustScore('blue', -3)">-3</button></td>
                        <td><button class="action-button negative" onclick="event.stopPropagation(); adjustScore('blue', -4)">-4</button></td>
                        <td><button class="action-button negative" onclick="event.stopPropagation(); adjustAdvantage('blue', -1)">-A</button></td>
                        <td><button class="action-button negative" onclick="event.stopPropagation(); adjustPenalty('blue', -1)">-P</button></td>
                        <td><button class="action-button white" onclick="event.stopPropagation(); toggleStalling('blue')">STALLING</button></td>
                    </tr>
                </table>
            </div>
            <div class="scores-container">
                <div class="stalling-alert" id="blueStallingAlert" onclick="stopStalling('blue')">
                    <div class="stalling-timer" id="blueStallingTimer">00:10</div>
                    <div class="stalling-label">Stalling</div>
                </div>
                <div class="medical-alert" id="blueMedicalAlert">
                    <div class="medical-timer" id="blueMedicalTimer">02:00</div>
                    <div class="medical-label">Medical</div>
                    <div class="medical-controls">
                        <button class="medical-control-btn center" id="blueMedicalPlay" onclick="event.stopPropagation(); toggleMedicalPlay('blue')">PLAY</button>
                        <button class="medical-control-btn top" onclick="event.stopPropagation(); swapMedical('blue')">SWAP</button>
                        <button class="medical-control-btn bottom" onclick="event.stopPropagation(); resetMedical('blue')">RESET</button>
                        <button class="medical-control-btn left" onclick="event.stopPropagation(); adjustMedicalTimer('blue', -1)">-1</button>
                        <button class="medical-control-btn right" onclick="event.stopPropagation(); adjustMedicalTimer('blue', 1)">+1</button>
                    </div>
                </div>
                <div class="small-scores">
                    <div class="small-score-wrapper">
                        <div class="small-score-label" id="blueAdvLabel">Advantage</div>
                        <div class="small-score" id="blueAdv">0</div>
                    </div>
                    <div class="small-score-wrapper">
                        <div class="small-score-label" id="bluePenLabel">Penalty</div>
                        <div class="small-score" id="bluePen">0</div>
                    </div>
                </div>
                <div class="score-square blue" id="blueScore">0</div>
            </div>
        </div>
        <!-- Timer section -->
        <div class="timer-section" id="timerSection">
            <div class="match-info">
                <div class="match-details" id="matchDetails">
                    <span class="marquee-content">{{ $bracket->entry->name }} / {{ $bracket->age->name }} / {{ $bracket->belt->name }}  / {{ $bracket->weight->weight }}KG</span>
                </div>
                <div class="match-stage" id="matchStage">
                    <span class="marquee-content">
                        @if($bracket->is_double_loser)
                             {{ $bracket->round }}
                        @else
                             {{ $bracket->round }}
                        @endif
                    </span>
                </div>
            </div>
            <div class="timer" id="timerDisplay">
                05:00
                <div class="timer-controls" id="timerControls">
                    <table class="timer-control-table">
                        <tr>
                            <td><button class="timer-control-button" onclick="event.stopPropagation(); adjustTimer(-30)">-30</button></td>
                            <td><button class="timer-control-button" onclick="event.stopPropagation(); adjustTimer(-1)">-1</button></td>
                            <td><button class="timer-control-button" id="playPauseBtn" onclick="event.stopPropagation(); togglePlayPause()">PLAY</button></td>
                            <td><button class="timer-control-button" onclick="event.stopPropagation(); adjustTimer(1)">+1</button></td>
                            <td><button class="timer-control-button" onclick="event.stopPropagation(); adjustTimer(30)">+30</button></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="control-buttons" id="controlButtons">
                <table class="control-table">
                    <tr>
                        <td><button class="control-button" onclick="event.stopPropagation(); undoScoringAction()">UNDO SCORING ACTION</button></td>
                        <td><button class="control-button" onclick="event.stopPropagation(); switchSides()">SWITCH SIDES</button></td>
                    </tr>
                    <tr>
                        <td><button class="control-button" onclick="event.stopPropagation()">BACK TO BRACKET</button></td>
                        <td><button class="control-button" onclick="event.stopPropagation()">BACK TO FIGHTORDER</button></td>
                        <td><button class="control-button" onclick="event.stopPropagation(); showEndGame()">END GAME</button></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- End Game Panels -->
    <!-- Panel 1: Draw/DQ/NoShow - right side between rows -->
    <div class="end-game-panel end-game-draw" id="endGameDraw">
        <table class="end-game-table" style="background: #000; padding: 10px;">
            <tr>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceDraw('DRAW')">DRAW</button></td>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceDraw('DOUBLE WO/DQ')">DOUBLE WO/DQ</button></td>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceDraw('DOUBLE NO SHOW')">DOUBLE NO SHOW</button></td>
            </tr>
        </table>
    </div>

    <!-- Panel 2: Red Won By - left side, row 1 -->
    <div class="end-game-panel end-game-red" id="endGameRed">
        <table class="end-game-table" style="background: #000; padding: 10px;">
            <tr>
                <td colspan="4"><div class="end-game-header red-header">WON BY:</div></td>
            </tr>
            <tr>
                <td colspan="2"><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('red', 'POINTS', {{!empty($registered) && isset($registered[0]) ? $registered[0]->id : 'null'}} )">POINTS</button></td>
                <td colspan="2"><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('red', 'SUBMISSION', {{!empty($registered) && isset($registered[0]) ? $registered[0]->id : 'null'}} )">SUBMISSION</button></td>
            </tr>
            <tr>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('red', 'DISQUALIFICATION', {{!empty($registered) && isset($registered[0]) ? $registered[0]->id : 'null'}} )">DISQUALIFICATION</button></td>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('red', 'WALKOVER', {{!empty($registered) && isset($registered[0]) ? $registered[0]->id : 'null'}} )">WALKOVER</button></td>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('red', 'NO SHOW', {{!empty($registered) && isset($registered[0]) ? $registered[0]->id : 'null'}} )">NO SHOW</button></td>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('red', 'DECISION', {{!empty($registered) && isset($registered[0]) ? $registered[0]->id : 'null'}} )">DECISION</button></td>
            </tr>
        </table>
    </div>

    <!-- Panel 3: Blue Won By - left side, row 2 -->
    <div class="end-game-panel end-game-blue" id="endGameBlue">
        <table class="end-game-table" style="background: #000; padding: 10px;">
            <tr>
                <td colspan="4"><div class="end-game-header blue-header">WON BY:</div></td>
            </tr>
            <tr>
                <td colspan="2"><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('blue', 'POINTS', {{!empty($registered) && isset($registered[1]) ? $registered[1]->id : 'null'}} )">POINTS</button></td>
                <td colspan="2"><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('blue', 'SUBMISSION', {{!empty($registered) && isset($registered[1]) ? $registered[1]->id : 'null'}} )">SUBMISSION</button></td>
            </tr>
            <tr>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('blue', 'DISQUALIFICATION', {{!empty($registered) && isset($registered[1]) ? $registered[1]->id : 'null'}} )">DISQUALIFICATION</button></td>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('blue', 'WALKOVER', {{!empty($registered) && isset($registered[1]) ? $registered[1]->id : 'null'}} )">WALKOVER</button></td>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('blue', 'NO SHOW', {{!empty($registered) && isset($registered[1]) ? $registered[1]->id : 'null'}} )">NO SHOW</button></td>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('blue', 'DECISION', {{!empty($registered) && isset($registered[1]) ? $registered[1]->id : 'null'}} )">DECISION</button></td>
            </tr>
        </table>
    </div>

    <button class="stalling-button" id="stallingButton" onclick="event.stopPropagation(); toggleDoubleStalling()">DOUBLE STALLING</button>

    <!-- Winner Banner -->
    <div class="winner-banner" id="winnerBanner"></div>

    <!-- Draw Banners (for DRAW, DOUBLE WO/DQ, DOUBLE NO SHOW) -->
    <div class="draw-banner top-banner" id="drawBannerTop"></div>
    <div class="draw-banner bottom-banner" id="drawBannerBottom"></div>

    <!-- Post-Result Buttons (appear after winner/draw announced) -->
    <div class="post-result-buttons" id="postResultButtons">
        <table style="background: #000; padding: 10px; border-collapse: separate; border-spacing: 0;">
            <tr>
                <td>
                    <table class="post-result-table" style="width: 100%;">
                        <tr>
                            <td style="padding-bottom: 6px;">
                                <button class="post-result-button save-button" onclick="event.stopPropagation()" style="width: 100%;">SAVE</button>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 0;">
                                <table class="post-result-table">
                                    <tr>
                                        <td><button class="post-result-button" onclick="event.stopPropagation(); goBack()">BACK</button></td>
                                        <td><button class="post-result-button" onclick="event.stopPropagation()">FIGHTORDER</button></td>
                                        <td><button class="post-result-button" onclick="event.stopPropagation()">BRACKET</button></td>
                                        <td><button class="post-result-button" onclick="event.stopPropagation()">NEXT</button></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

<input id="reg_win_id" type="hidden" name="reg_win_id" value=""></input>


<script src="{{ asset('js/scoreboard/scoreboard.js') }}"></script>

<script>
    const bracket = @json($bracket);
    const allData = @json($registered);
    const winnerData = allData[2];
    const regOne = allData[0];
    console.log('Registered One:', bracket);

    const localStorageKey = `${bracket.event_id}_${bracket.day_id}_${bracket.mat_id}`;
    const localStorageData = JSON.parse(localStorage.getItem(localStorageKey));
    console.log('Local Storage Data:', localStorageData);
    if (localStorageData) {
      const id = localStorageData.findIndex(x => x == {{ $matchId }});
      window.redirectToNextCounter = function() {
        let nextMatchId;
        if (id < localStorageData.length - 1) {
          nextMatchId = localStorageData[id + 1];
        } else {
          nextMatchId = localStorageData[id];
        }
          window.location.href = "{{ route('event.config.match.edit_status', ['match_id' => ':matchId']) }}".replace(':matchId', nextMatchId);
      }
      window.redirectToPrevCounter = function() {
        let prevMatchId;
        if (id > 0) {
          prevMatchId = localStorageData[id - 1];
        } else {
          prevMatchId = localStorageData[id];
        }
          window.location.href = "{{ route('event.config.match.edit_status', ['match_id' => ':matchId']) }}".replace(':matchId', prevMatchId);
      }
    } else {
      window.redirectToNextCounter = function() {
        window.location.href = "{{ route('event.config.counter.next', ['match_id' => $matchId]) }}";
      }
      window.redirectToPrevCounter = function() {
        window.location.href = "{{ route('event.config.counter.prev', ['match_id' => $matchId]) }}";
      }
    }
</script>
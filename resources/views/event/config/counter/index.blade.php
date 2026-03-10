@if (!isset($matchId) || empty($matchId))
  <script>
    window.close();
  </script>
  {{-- Prevent further rendering --}}
  @php exit; @endphp
@endif

<html lang="{{ app()->getLocale() }}">
	<!--begin::Head-->
	<head><base href="">
		<meta charset="utf-8" />
		<meta name="description" content="Competition, Тэмцээн" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="author" content="Smart Data LLC">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />

		<!-- Global Theme Styles (used by all pages) -->
		<link rel="stylesheet" href="{{ asset('/assets/plugins/global/plugins.bundle.css') }}" media="screen" />
		<link rel="stylesheet" href="{{ asset('/assets/plugins/custom/prismjs/prismjs.bundle.css') }}" media="screen" />
		<link rel="stylesheet" href="{{ asset('/assets/css/style.bundle.css') }}" media="screen" />
		<link rel="stylesheet" href="{{ asset('/assets/css/style.bundle.css') }}" media="screen" />
        <link rel="stylesheet" href="{{asset('css/scoreboard/scoreboard.css')}}">

		<!--end::Global Theme Styles-->
		
		<!--begin::Layout Themes(used by all pages)-->
		@yield('css')
		<!--end::Layout Themes-->
		<link rel="shortcut icon" href="{{asset('assets/images/logo/uniq_logo.ico')}}" />
		<title>Тэмцээний Удирдлагын Систем</title>
	</head>

<body>

<form id="winnerForm" action="{{ route('event.config.match.winner', ['match_id' => $matchId]) }}" method="POST"  style="display:none;">

</form>
    <div class="">
        <div class="row">
            <div class="player-info">
                @if (!empty($registered[0]))
                <div class="player-row-1">
                    <img src="{{asset('assets/images/flags/4x3/' . ($registered[0]->abb ?? 'un') .'.svg')}}" alt="Flag" class="player-flag">
                    <div class="player-name">
                        {{$registered[0]->member->firstname ?? '-'}}
                        {{$registered[0]->member->lastname ?? ''}}
                    </div>
                </div>
                <div class="player-row-2">
                    <div class="country-code">{{$registered[0]->abb_full ?? '-'}}</div>
                    <img src="{{asset('assets/images/logo/club/' . ($registered[0]->academy_id ?? 0) .'.jpg')}}" alt="Club Logo" class="club-logo"
                    onerror="this.src='{{asset('assets/images/logo/club/0.png')}}'">
                    <div class="club-name">{{$registered[0]->academy->name ?? ''}}</div>
                </div>
                @else
                <div class="player-row-1">
                    <div class="player-name">{{ ($isBye[0] ?? false) ? 'BYE' : 'TBD' }}</div>
                </div>
                @endif
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
                @if (!empty($registered[1]))
                <div class="player-row-1">
                    <img src="{{asset('assets/images/flags/4x3/' . ($registered[1]->abb ?? 'un') .'.svg')}}" alt="Flag" class="player-flag">
                    <div class="player-name">
                        {{$registered[1]->member->firstname ?? '-'}}
                        {{$registered[1]->member->lastname ?? ''}}
                    </div>
                </div>
                <div class="player-row-2">
                    <div class="country-code">{{$registered[1]->abb_full ?? '-'}}</div>
                    <img src="{{asset('assets/images/logo/club/' . ($registered[1]->academy_id ?? 0) .'.jpg')}}" alt="Club Logo" class="club-logo"
                    onerror="this.src='{{asset('assets/images/logo/club/0.png')}}'">
                    <div class="club-name">{{$registered[1]->academy->name ?? ''}}</div>
                </div>
                @else
                <div class="player-row-1">
                    <div class="player-name">{{ ($isBye[1] ?? false) ? 'BYE' : 'TBD' }}</div>
                </div>
                @endif
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
                    <span class="marquee-content">{{ $bracket->round }}</span>
                </div>
            </div>
            <div class="timer" id="timerDisplay" data-duration="{{ $bracket->entry->duration ?? 5 }}">
                {{ sprintf('%02d:%02d', floor(($bracket->entry->duration ?? 5)), 0) }}
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
                <td colspan="2"><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('red', 'POINTS' ); setWinnerId({{ $registered[0]->id ?? 'null' }})">POINTS</button></td>
                <td colspan="2"><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('red', 'SUBMISSION'); setWinnerId({{ $registered[0]->id ?? 'null' }})">SUBMISSION</button></td>
            </tr>
            <tr>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('red', 'DISQUALIFICATION'); setWinnerId({{ $registered[0]->id ?? 'null' }})">DISQUALIFICATION</button></td>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('red', 'WALKOVER'); setWinnerId({{ $registered[0]->id ?? 'null' }})">WALKOVER</button></td>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('red', 'NO SHOW'); setWinnerId({{ $registered[0]->id ?? 'null' }})">NO SHOW</button></td>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('red', 'DECISION'); setWinnerId({{ $registered[0]->id ?? 'null' }})">DECISION</button></td>
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
                <td colspan="2"><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('blue', 'POINTS' ); setWinnerId({{ $registered[1]->id ?? 'null' }})">POINTS</button></td>
                <td colspan="2"><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('blue', 'SUBMISSION'); setWinnerId({{ $registered[1]->id ?? 'null' }})">SUBMISSION</button></td>
            </tr>
            <tr>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('blue', 'DISQUALIFICATION'); setWinnerId({{ $registered[1]->id ?? 'null' }})">DISQUALIFICATION</button></td>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('blue', 'WALKOVER'); setWinnerId({{ $registered[1]->id ?? 'null' }})">WALKOVER</button></td>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('blue', 'NO SHOW'); setWinnerId({{ $registered[1]->id ?? 'null' }})">NO SHOW</button></td>
                <td><button class="end-game-button" onclick="event.stopPropagation(); announceWinner('blue', 'DECISION'); setWinnerId({{ $registered[1]->id ?? 'null' }})">DECISION</button></td>
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
                                <button class="post-result-button save-button" onclick="event.stopPropagation(); saveMatchResult()" style="width: 100%;">SAVE</button>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 0;">
                                <table class="post-result-table">
                                    <tr>
                                        <td><button class="post-result-button" onclick="event.stopPropagation(); goBack()">BACK</button></td>
                                        <td><button class="post-result-button" onclick="event.stopPropagation()">FIGHTORDER</button></td>
                                        <td><button class="post-result-button" onclick="event.stopPropagation()">BRACKET</button></td>
                                        <td><button class="post-result-button" onclick="event.stopPropagation(); redirectToNextCounter()">NEXT</button></td>
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


<script>var matchDuration = {{ $bracket->entry->duration ?? 5 }};</script>
<script src="{{ asset('js/scoreboard/scoreboard.js') }}?v={{ time() }}"></script>
<!--begin::Global Config(global config for global JS scripts)-->
<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#6993FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#E1E9FF", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
<!--end::Global Config-->
<!--begin::Global Theme Bundle(used by all pages)-->
<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('assets/plugins/custom/prismjs/prismjs.bundle.js')}}"></script>
<script src="{{asset('assets/js/scripts.bundle.js')}}"></script>
<script src="{{ asset('assets/js/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{asset('assets/js/smart.js')}}"></script>

<script src="{{asset('assets/js/plugins/custom/blockui/jquery.blockUI.js')}}"> </script>
<script src="{{asset('assets/js/plugins/custom/jquery-confirm/jquery-confirm.min.js') }}"></script>
<script src="{{asset('assets/js/plugins/custom/jquery-validation/dist/jquery.validate.js') }}"></script>

<script>
    const bracket = @json($bracket);
    const allData = @json($registered);
    const winnerData = allData[2] ?? null;
    const regOne = allData[0] ?? null;

    // Track BYE match IDs that should be skipped by NEXT button
    let byeMatchIds = [];

    const localStorageKey = `${bracket.event_id}_${bracket.day_id}_${bracket.mat_id}`;
    const localStorageData = JSON.parse(localStorage.getItem(localStorageKey));
    if (localStorageData) {
      const id = localStorageData.findIndex(x => x == {{ $matchId }});
      window.redirectToNextCounter = function() {
        // Find the next match that is NOT a BYE auto-completed match
        let nextIdx = id + 1;
        while (nextIdx < localStorageData.length && byeMatchIds.includes(localStorageData[nextIdx])) {
          nextIdx++;
        }
          const nextMatchId = nextIdx < localStorageData.length ? localStorageData[nextIdx] : localStorageData[id];
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

    
    if(winnerData && winnerData.id && regOne) {
        const localStorageKey1 = `{{ $matchId }}-winner`;
        const localStorageData1 = JSON.parse(localStorage.getItem(localStorageKey1));
        if (localStorageData1) {
            announceWinner(winnerData.id === regOne.id ? 'red' : 'blue', localStorageData1.method);
        }
    }

    function saveMatchResult() {
        const form = document.getElementById('winnerForm');
        const regWinId = form.querySelector('input[name="reg_win_id"]').value;
        const winnerMethod = form.querySelector('input[name="reg_win_method"]').value;
        console.log('Saving match result with winner reg ID:', regWinId);
        postData = {
            '_token': '{{ csrf_token() }}',
            'reg_win_id': regWinId || null,
            'win_method': winnerMethod,
            'red_score': redScore,
            'blue_score': blueScore,
            'red_advantage': redAdvantage,
            'blue_advantage': blueAdvantage,
            'red_penalty': redPenalty,
            'blue_penalty': bluePenalty
        };
        fetch("{{ route('event.config.match.winner', ['match_id' => $matchId]) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(postData)
        })        .then(response => response.json())
        .then(data => {
            toastr.success(data.msg);
            localStorage.setItem(`{{ $matchId }}-winner`, JSON.stringify({winner: regWinId, method: winnerMethod}));
            // Capture BYE match IDs so NEXT button can skip them
            if (data.bye_match_ids && data.bye_match_ids.length > 0) {
                byeMatchIds = data.bye_match_ids;
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
</script>
</body>
</html>
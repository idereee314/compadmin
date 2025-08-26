<link rel="stylesheet" href="{{asset('css/scoreboard/scoreboard_css_style2.css')}}">
<link rel="stylesheet" href="{{asset('css/scoreboard/scoreboard_css_timer.css')}}">

<form id="winnerForm" action="{{ route('event.config.counter.winner', ['match_id' => $matchId]) }}" method="POST"  style="display:none;">

</form>

  <div class="top-section">
      <!-- Left Side -->
      <div class="top-left-section">

            <div class="name-section">
              <img class="flag" src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Flag_of_Mongolia.svg/1920px-Flag_of_Mongolia.svg.png" alt="Mongolian Flag" />
              <div class="name-texts">
                <div id="winner1"></div>
                <div class="full-name-wrapper">
                  <div id="fullNameTop" class="full-name short">{{$registered[0]->member->fullname ?? '-'}}</div>
                </div>
                <div id="clubNameTop" class="club-name">{{$registered[0]->academy->name ?? '-'}}</div>
              </div>
            </div>


            <div class="controls-mini">
              <div class="controls">
                <button class="positive" onclick="changeScore('top', 1)">+1</button>
                <button class="positive" onclick="changeScore('top', 2)">+2</button>
                <button class="positive" onclick="changeScore('top', 3)">+3</button>
                <button class="positive" onclick="changeScore('top', 4)">+4</button>
                <button class="positive" onclick="changeMiniScore('top', 'advantage', 1)">+A</button>
                <button class="positive" onclick="changeMiniScore('top', 'penalty', 1)">+P</button>
                <button class="positive mplay">MEDIC</button>

                <button class="negative" onclick="changeScore('top', -1)">-1</button>
                <button class="negative" onclick="changeScore('top', -2)">-2</button>
                <button class="negative" onclick="changeScore('top', -3)">-3</button>
                <button class="negative" onclick="changeScore('top', -4)">-4</button>
                <button class="negative" onclick="changeMiniScore('top', 'advantage', -1)">-A</button>
                <button class="negative" onclick="changeMiniScore('top', 'penalty', -1)">-P</button>
                <button id="sbtn1" class="sbuttons">STALLING</button>
              </div>
            </div>
      </div>

      <!-- Middle -->
      <div class="top-mid-section">
          <div class="stimer-container">
            <div id="stimer1" class="stimer">10</div>
            <button id="sbtn4" class="stalling_transparent_button"; style="border: none; background: rgba(0, 0, 0, 0.0); color: black;"> </button>
          </div>

          <div class="mtimer-container" data-id="0">
              <div class="mcontrols">
                  <div class="mrow">
                      <button class="mminus">-1</button>
                      <button class="mplay">Play</button>
                      <button class="mplus">+1</button>
                  </div>
                  <div class="mrow"><button class="mreset">Reset</button></div>
              </div>
              <span class="mtime">2:00</span>
          </div>

      </div>

      <!-- Right Side -->
      <div class="top-right-section">
          <div class="column-left">
              <div class="mini-scores">
                  <div class="mini-score-box1">
                      <div class="mini-label">Advantage</div>
                      <div id="advantageTop" class="mini-score">0</div>
                  </div>
                  <div class="mini-score-box2">
                        <div class="mini-label">Penalty</div>
                        <div id="penaltyTop" class="mini-score">0</div>
                  </div>
              </div>
          </div>
                  
          <div id="scoreTop" class="score-container score-top">
              <div id="scoreValueTop" class="score">0</div>
          </div>

      </div>

  </div>

  <div class="double-stimer-container">
      <div></div>
      <div class="dsbuttons">
          <button id="sbtn2" class="ds_button">DOUBLE STALLING</button>
      </div>
      <div></div>
  </div>

  <div class="middle-section">
      <!-- Left Side -->
      <div class="middle-left-section">
        <div class="name-section">
          <img class="flag" src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Flag_of_Mongolia.svg/1920px-Flag_of_Mongolia.svg.png" alt="Mongolian Flag" />
          <div class="name-texts">
            <div id="winner2"></div>
            <div class="full-name-wrapper">
              <div id="fullNameBottom" class="full-name short">{{$registered[1]->member->fullname ?? '-'}}</div>
            </div>
            <div id="clubNameBottom" class="club-name">{{$registered[1]->academy->name ?? '-'}}</div>
          </div>
      </div>

        <div class="controls-mini" id="controlsMini">
          <div class="controls">
            <button class="positive" onclick="changeScore('bottom', 1)">+1</button>
            <button class="positive" onclick="changeScore('bottom', 2)">+2</button>
            <button class="positive" onclick="changeScore('bottom', 3)">+3</button>
            <button class="positive" onclick="changeScore('bottom', 4)">+4</button>
            <button class="positive" onclick="changeMiniScore('bottom', 'advantage', 1)">+A</button>
            <button class="positive" onclick="changeMiniScore('bottom', 'penalty', 1)">+P</button>
            <button class="positive mplay">MEDIC</button>

            <button class="negative" onclick="changeScore('bottom', -1)">-1</button>
            <button class="negative" onclick="changeScore('bottom', -2)">-2</button>
            <button class="negative" onclick="changeScore('bottom', -3)">-3</button>
            <button class="negative" onclick="changeScore('bottom', -4)">-4</button>
            <button class="negative" onclick="changeMiniScore('bottom', 'advantage', -1)">-A</button>
            <button class="negative" onclick="changeMiniScore('bottom', 'penalty', -1)">-P</button>
            <button id="sbtn3" class="sbuttons">STALLING</button>
          </div>
        </div>

      </div>

      <!-- Middle -->
      <div class="middle-mid-section">
        <div class="stimer-container">
          
          <div id="stimer2" class="stimer">10</div>
          <div class="sbuttons">
            <button id="sbtn5" class="stalling_transparent_button"; style="border: none; background: rgba(0, 0, 0, 0.0); color: black;"> </button>
          </div>
        </div>

        <div class="mtimer-container" data-id="1">
            <div class="mcontrols">
                <div class="mrow">
                    <button class="mminus">-1</button>
                    <button class="mplay">Play</button>
                    <button class="mplus">+1</button>
                </div>
                <div class="mrow"><button class="mreset">Reset</button></div>
            </div>
            <span class="mtime">2:00</span>
        </div>

      </div>

      <!-- Right Side -->

      <div class="middle-right-section">
          <div class="column-left">

              <div class="mini-scores">
                <div class="mini-score-box1">
                  <div class="mini-label">Advantage</div>
                  <div id="advantageBottom" class="mini-score">0</div>
                </div>
                <div class="mini-score-box2">
                  <div class="mini-label">Penalty</div>
                  <div id="penaltyBottom" class="mini-score">0</div>
                </div>
              </div>
          </div>      

          <div id="scoreBottom" class="score-container score-bottom">
              <div id="scoreValueBottom" class="score">0</div>
          </div>



      </div>


  </div>

  <div class="bottom-section">
      <div class="exit-buttons">
        <button class="end-button" onclick="close_window()">Exit</button>
        <button class="end-button" onclick="window.open()">Duplicate</button>
        <button class="end-button" onclick="requestFullscreen()">Full Screen</button>
        <button id="endBtn" class="end-button">End Game</button>

        <div class="popup" id="popup1">
          <div class="popup-header red-header">WON BY:</div>
          <div class="end-top-row">
            <button class="end-button" onclick="chooseWinner('RED', 'POINTS', {{$registered[0]->id}})">POINTS</button>
            <button class="end-button" onclick="chooseWinner('RED', 'SUBMISSION', {{$registered[0]->id}}))">SUBMISSION</button>
          </div>
          <div class="end-bottom-row">
            <button class="end-button" onclick="chooseWinner('RED', 'DISQUALIFICATION', {{$registered[0]->id}}))">DISQUALIFICATION</button>
            <button class="end-button"onclick="chooseWinner('RED', 'WALKOVER', {{$registered[0]->id}}))">WALKOVER</button>
            <button class="end-button"onclick="chooseWinner('RED', 'NOSHOW', {{$registered[0]->id}}))">NOSHOW</button>
            <button class="end-button"onclick="chooseWinner('RED', 'DECISION', {{$registered[0]->id}}))">DECISION</button>
          </div>
        </div>

        <div class="popup" id="popup2">
          <div class="popup-header blue-header">WON BY:</div>
          <div class="end-top-row">
            <button class="end-button" onclick="chooseWinner('BLUE','POINTS', {{$registered[1]->id}})">POINTS</button>
            <button class="end-button" onclick="chooseWinner('BLUE','SUBMISSION', {{$registered[1]->id}})">SUBMISSION</button>
          </div>
          <div class="end-bottom-row">
            <button class="end-button" onclick="chooseWinner('BLUE','DISQUALIFICATION', {{$registered[1]->id}})">DISQUALIFICATION</button>
            <button class="end-button" onclick="chooseWinner('BLUE','WALKOVER', {{$registered[1]->id}})">WALKOVER</button>
            <button class="end-button" onclick="chooseWinner('BLUE','NOSHOW', {{$registered[1]->id}})">NOSHOW</button>
            <button class="end-button" onclick="chooseWinner('BLUE','DECISION', {{$registered[1]->id}})">DECISION</button>
          </div>
        </div>
      </div>

      <div class="popup" id="popup3">
        <div class="end-double-btn">
          <button class="end-button" onclick="doubleLoser('WO/DQ')">DOUBLE WO/DQ</button>
          <button class="end-button" onclick="doubleLoser('NO SHOW')">DOUBLE NO SHOW</button>
        </div>
      </div>

      <div class="clock-section">
          <div class="clock-container">
            <div id="timer">5:00</div>
          </div>

          <div class="clock-controls">
          <button onclick="adjustTime(-30)">-30</button>
          <button onclick="adjustTime(-1)">-1</button>
          <button id="startPauseBtn" onclick="toggleTimer()">Start</button>
          <button onclick="adjustTime(1)">+1</button>
          <button onclick="adjustTime(30)">+30</button>
      </div>
  </div>




<script>
  console.log(@json($registered));
</script>

<script src="{{ asset('js/scoreboard/scoreboard_js_clock.js') }}"></script>
<script src="{{ asset('js/scoreboard/scoreboard_js_endgame.js') }}"></script>
<script src="{{ asset('js/scoreboard/scoreboard_js_mtimer.js') }}"></script>
<script src="{{ asset('js/scoreboard/scoreboard_js_script.js') }}"></script>
<script src="{{ asset('js/scoreboard/scoreboard_js_stalling.js') }}"></script>
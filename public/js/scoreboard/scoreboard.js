        // End Game logic
        const form = document.getElementById('winnerForm');
        function setWinnerId(winnerId) {
            let input = form.querySelector('input[name="reg_win_id"]');
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'reg_win_id';
                form.appendChild(input);
            }
            input.value = winnerId;
        }   
        function setWinnerMethod(winnerMethod) {
            let input = form.querySelector('input[name="reg_win_method"]');
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'reg_win_method';
                form.appendChild(input);
            }
            input.value = winnerMethod;
        }   
        let endGameVisible = false;

        function showEndGame() {
            endGameVisible = true;
            document.getElementById('endGameDraw').classList.add('visible');
            document.getElementById('endGameRed').classList.add('visible');
            document.getElementById('endGameBlue').classList.add('visible');
            
            // Hide timer controls and control buttons
            document.getElementById('timerControls').style.display = 'none';
            document.getElementById('controlButtons').style.display = 'none';
        }

        function hideEndGame() {
            endGameVisible = false;
            document.getElementById('endGameDraw').classList.remove('visible');
            document.getElementById('endGameRed').classList.remove('visible');
            document.getElementById('endGameBlue').classList.remove('visible');
        }

        function announceWinner(player, method) {
            hideEndGame();
            
            const banner = document.getElementById('winnerBanner');
            banner.textContent = `WINNER BY ${method}`;
            setWinnerMethod(method);
            banner.classList.add('visible');
            banner.classList.add(player === 'red' ? 'red-winner' : 'blue-winner');
            
            // Keep timer controls and control buttons hidden
            document.getElementById('timerControls').style.display = 'none';
            document.getElementById('controlButtons').style.display = 'none';
            
            // Show post-result buttons
            document.getElementById('postResultButtons').classList.add('visible');
        }

        function announceDraw(result) {
            hideEndGame();

            setWinnerMethod(result);
            setWinnerId('');

            const topBanner = document.getElementById('drawBannerTop');
            const bottomBanner = document.getElementById('drawBannerBottom');
            
            topBanner.textContent = result;
            bottomBanner.textContent = result;
            
            topBanner.classList.add('visible');
            bottomBanner.classList.add('visible');
            
            // Keep timer controls and control buttons hidden
            document.getElementById('timerControls').style.display = 'none';
            document.getElementById('controlButtons').style.display = 'none';
            
            // Show post-result buttons
            document.getElementById('postResultButtons').classList.add('visible');
        }

        function goBack() {
            // Hide all banners
            const winnerBanner = document.getElementById('winnerBanner');
            winnerBanner.classList.remove('visible', 'red-winner', 'blue-winner');
            
            const drawTopBanner = document.getElementById('drawBannerTop');
            const drawBottomBanner = document.getElementById('drawBannerBottom');
            drawTopBanner.classList.remove('visible');
            drawBottomBanner.classList.remove('visible');
            
            // Hide post-result buttons
            document.getElementById('postResultButtons').classList.remove('visible');
            
            // Show timer controls and control buttons
            document.getElementById('timerControls').style.display = '';
            document.getElementById('controlButtons').style.display = '';
        }

        // Stalling timer logic
        const stallingState = {
            red:  { seconds: 10, interval: null, active: false },
            blue: { seconds: 10, interval: null, active: false }
        };

        function formatStallingTime(seconds) {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        }

        function updatePlayerInfoRight(player) {
            const row = player === 'red'
                ? document.querySelectorAll('.row')[0]
                : document.querySelectorAll('.row')[1];
            const playerInfo = row.querySelector('.player-info');

            const stallingActive = stallingState[player].active;
            const medicalActive = medicalState[player].active;

            let extraVh = 0;
            if (stallingActive) extraVh += 31.5;
            if (medicalActive) extraVh += 31.5;

            if (extraVh > 0) {
                playerInfo.style.right = `calc(400px + ${extraVh}vh)`;
            } else {
                playerInfo.style.right = '400px';
            }
        }

        // Medical timer logic
        const medicalState = {
            red:  { seconds: 120, interval: null, active: false, running: false },
            blue: { seconds: 120, interval: null, active: false, running: false }
        };

        function formatMedicalTime(seconds) {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        }

        function toggleMedical(player) {
            const state = medicalState[player];
            const alertEl = document.getElementById(player + 'MedicalAlert');
            const timerEl = document.getElementById(player + 'MedicalTimer');
            const playBtn = document.getElementById(player + 'MedicalPlay');
            
            if (!state.active) {
                // First press: show and start timer
                state.seconds = 120;
                state.active = true;
                state.running = true;
                timerEl.textContent = formatMedicalTime(state.seconds);
                alertEl.classList.add('active');
                alertEl.classList.remove('paused');
                playBtn.textContent = 'PAUSE';
                updatePlayerInfoRight(player);

                if (state.interval) clearInterval(state.interval);
                state.interval = setInterval(() => {
                    if (state.running && state.seconds > 0) {
                        state.seconds--;
                        timerEl.textContent = formatMedicalTime(state.seconds);
                        if (state.seconds <= 0) {
                            state.running = false;
                            clearInterval(state.interval);
                            state.interval = null;
                            alertEl.classList.add('paused');
                            playBtn.textContent = 'PLAY';
                        }
                    }
                }, 1000);
            } else {
                // Toggle play/pause
                toggleMedicalPlay(player);
            }
        }

        function toggleMedicalPlay(player) {
            const state = medicalState[player];
            const alertEl = document.getElementById(player + 'MedicalAlert');
            const timerEl = document.getElementById(player + 'MedicalTimer');
            const playBtn = document.getElementById(player + 'MedicalPlay');

            if (!state.running) {
                // Start/Resume
                state.running = true;
                alertEl.classList.remove('paused');
                playBtn.textContent = 'PAUSE';

                if (!state.interval) {
                    state.interval = setInterval(() => {
                        if (state.running && state.seconds > 0) {
                            state.seconds--;
                            timerEl.textContent = formatMedicalTime(state.seconds);
                            if (state.seconds <= 0) {
                                state.running = false;
                                clearInterval(state.interval);
                                state.interval = null;
                                alertEl.classList.add('paused');
                                playBtn.textContent = 'PLAY';
                            }
                        }
                    }, 1000);
                }
            } else {
                // Pause
                state.running = false;
                alertEl.classList.add('paused');
                playBtn.textContent = 'PLAY';
            }
        }

        function adjustMedicalTimer(player, seconds) {
            const state = medicalState[player];
            const timerEl = document.getElementById(player + 'MedicalTimer');
            
            state.seconds = Math.max(0, Math.min(120, state.seconds + seconds));
            timerEl.textContent = formatMedicalTime(state.seconds);
        }

        function resetMedical(player) {
            const state = medicalState[player];
            const alertEl = document.getElementById(player + 'MedicalAlert');
            const timerEl = document.getElementById(player + 'MedicalTimer');
            const playBtn = document.getElementById(player + 'MedicalPlay');

            state.running = false;
            state.active = false;
            state.seconds = 120;
            if (state.interval) {
                clearInterval(state.interval);
                state.interval = null;
            }
            
            alertEl.classList.remove('active', 'paused');
            timerEl.textContent = formatMedicalTime(120);
            playBtn.textContent = 'PLAY';
            updatePlayerInfoRight(player);
        }

        function swapMedical(player) {
            const opponent = player === 'red' ? 'blue' : 'red';
            
            // Swap the states
            const tempState = { ...medicalState[player] };
            medicalState[player] = { ...medicalState[opponent] };
            medicalState[opponent] = { ...tempState };

            // Clear intervals on both
            if (medicalState[player].interval) clearInterval(medicalState[player].interval);
            if (medicalState[opponent].interval) clearInterval(medicalState[opponent].interval);
            medicalState[player].interval = null;
            medicalState[opponent].interval = null;

            // Update both displays
            ['red', 'blue'].forEach(p => {
                const state = medicalState[p];
                const alertEl = document.getElementById(p + 'MedicalAlert');
                const timerEl = document.getElementById(p + 'MedicalTimer');
                const playBtn = document.getElementById(p + 'MedicalPlay');

                if (state.active) {
                    timerEl.textContent = formatMedicalTime(state.seconds);
                    alertEl.classList.add('active');
                    
                    if (state.running) {
                        alertEl.classList.remove('paused');
                        playBtn.textContent = 'PAUSE';
                        // Restart interval
                        state.interval = setInterval(() => {
                            if (state.running && state.seconds > 0) {
                                state.seconds--;
                                timerEl.textContent = formatMedicalTime(state.seconds);
                                if (state.seconds <= 0) {
                                    state.running = false;
                                    clearInterval(state.interval);
                                    state.interval = null;
                                    alertEl.classList.add('paused');
                                    playBtn.textContent = 'PLAY';
                                }
                            }
                        }, 1000);
                    } else {
                        alertEl.classList.add('paused');
                        playBtn.textContent = 'PLAY';
                    }
                } else {
                    alertEl.classList.remove('active', 'paused');
                    timerEl.textContent = formatMedicalTime(120);
                    playBtn.textContent = 'PLAY';
                }
                
                updatePlayerInfoRight(p);
            });
        }

        function startStalling(player) {
            const state = stallingState[player];
            const alertEl = document.getElementById(player + 'StallingAlert');
            const timerEl = document.getElementById(player + 'StallingTimer');

            state.seconds = 10;
            state.active = true;
            timerEl.textContent = formatStallingTime(state.seconds);
            alertEl.classList.add('active');
            alertEl.classList.remove('paused');
            updatePlayerInfoRight(player);

            if (state.interval) clearInterval(state.interval);
            state.interval = setInterval(() => {
                state.seconds--;
                timerEl.textContent = formatStallingTime(state.seconds);
                if (state.seconds <= 0) {
                    stopStalling(player);
                    checkDoubleStallFinished();
                }
            }, 1000);
        }

        function stopStalling(player) {
            const state = stallingState[player];
            const alertEl = document.getElementById(player + 'StallingAlert');

            state.active = false;
            state.seconds = 10;
            if (state.interval) { clearInterval(state.interval); state.interval = null; }
            alertEl.classList.remove('active', 'paused');
            updatePlayerInfoRight(player);
        }

        function checkDoubleStallFinished() {
            // If both timers have run out naturally, hide the Double Stalling button
            if (!stallingState.red.active && !stallingState.blue.active) {
                stallingButton.classList.remove('visible');
            }
        }

        function toggleStalling(player) {
            const state = stallingState[player];
            if (state.active) {
                stopStalling(player);
            } else {
                startStalling(player);
            }
        }

        function toggleDoubleStalling() {
            const redActive = stallingState.red.active;
            const blueActive = stallingState.blue.active;

            // If both are already active, stop both and hide Double Stalling button
            if (redActive && blueActive) {
                stopStalling('red');
                stopStalling('blue');
                stallingButton.classList.remove('visible');
            } else {
                // Start both and keep Double Stalling button visible
                startStalling('red');
                startStalling('blue');
                stallingButton.classList.add('visible');
                // Hide action buttons using classList
                document.getElementById('actionButtons1').classList.remove('visible');
                document.getElementById('actionButtons2').classList.remove('visible');
            }
        }

        function switchSides() {
            const row1 = document.querySelectorAll('.row')[0];
            const row2 = document.querySelectorAll('.row')[1];

            // --- Swap player info HTML ---
            const info1 = row1.querySelector('.player-info');
            const info2 = row2.querySelector('.player-info');
            const tempInfo = info1.innerHTML;
            info1.innerHTML = info2.innerHTML;
            info2.innerHTML = tempInfo;

            // --- Swap main scores ---
            const tempRedScore = redScore;
            redScore = blueScore;
            blueScore = tempRedScore;
            updateScoreDisplay('red');
            updateScoreDisplay('blue');

            // --- Swap advantages ---
            const tempRedAdv = redAdvantage;
            redAdvantage = blueAdvantage;
            blueAdvantage = tempRedAdv;
            updateAdvantageDisplay('red');
            updateAdvantageDisplay('blue');

            // --- Swap penalties ---
            const tempRedPen = redPenalty;
            redPenalty = bluePenalty;
            bluePenalty = tempRedPen;
            updatePenaltyDisplay('red');
            updatePenaltyDisplay('blue');

            // --- Swap stalling states ---
            const tempStalling = { ...stallingState.red };
            stallingState.red = { ...stallingState.blue };
            stallingState.blue = { ...tempStalling };

            // Reassign intervals (they still point to old timers — clear and restart if active)
            ['red', 'blue'].forEach(player => {
                const state = stallingState[player];
                const timerEl = document.getElementById(player + 'StallingTimer');
                const alertEl = document.getElementById(player + 'StallingAlert');

                if (state.interval) clearInterval(state.interval);
                state.interval = null;

                if (state.active) {
                    timerEl.textContent = formatStallingTime(state.seconds);
                    alertEl.classList.add('active');
                    state.interval = setInterval(() => {
                        state.seconds--;
                        timerEl.textContent = formatStallingTime(state.seconds);
                        if (state.seconds <= 0) {
                            stopStalling(player);
                            checkDoubleStallFinished();
                        }
                    }, 1000);
                } else {
                    alertEl.classList.remove('active', 'paused');
                }
            });

            // --- Swap medical states ---
            const tempMedical = { ...medicalState.red };
            medicalState.red = { ...medicalState.blue };
            medicalState.blue = { ...tempMedical };
            // Reassign medical intervals
            ['red', 'blue'].forEach(player => {
                const state = medicalState[player];
                const timerEl = document.getElementById(player + 'MedicalTimer');
                const alertEl = document.getElementById(player + 'MedicalAlert');

                if (state.interval) clearInterval(state.interval);
                state.interval = null;

                if (state.active) {
                    timerEl.textContent = formatMedicalTime(state.seconds);
                    alertEl.classList.add('active');
                    if (state.running) {
                        alertEl.classList.remove('paused');
                        state.interval = setInterval(() => {
                            if (state.running) {
                                state.seconds--;
                                timerEl.textContent = formatMedicalTime(state.seconds);
                                if (state.seconds <= 0) {
                                    state.running = false;
                                    clearInterval(state.interval);
                                    state.interval = null;
                                    alertEl.classList.add('paused');
                                }
                            }
                        }, 1000);
                    } else {
                        alertEl.classList.add('paused');
                    }
                } else {
                    alertEl.classList.remove('active', 'paused');
                }
            });

            // --- Recalculate player-info right offsets ---
            updatePlayerInfoRight('red');
            updatePlayerInfoRight('blue');
        }

        // Score variables
        let redScore = 0;
        let blueScore = 0;
        let redAdvantage = 0;
        let blueAdvantage = 0;
        let redPenalty = 0;
        let bluePenalty = 0;
        
        // Score history for undo
        let scoreHistory = [];

        const redScoreElement = document.getElementById('redScore');
        const blueScoreElement = document.getElementById('blueScore');
        const redAdvElement = document.getElementById('redAdv');
        const blueAdvElement = document.getElementById('blueAdv');
        const redPenElement = document.getElementById('redPen');
        const bluePenElement = document.getElementById('bluePen');
        const redAdvLabel = document.getElementById('redAdvLabel');
        const blueAdvLabel = document.getElementById('blueAdvLabel');
        const redPenLabel = document.getElementById('redPenLabel');
        const bluePenLabel = document.getElementById('bluePenLabel');

        // Save current state to history
        function saveScoreState() {
            scoreHistory.push({
                redScore,
                blueScore,
                redAdvantage,
                blueAdvantage,
                redPenalty,
                bluePenalty
            });
        }

        // Undo last scoring action
        function undoScoringAction() {
            if (scoreHistory.length === 0) return;
            
            const previousState = scoreHistory.pop();
            redScore = previousState.redScore;
            blueScore = previousState.blueScore;
            redAdvantage = previousState.redAdvantage;
            blueAdvantage = previousState.blueAdvantage;
            redPenalty = previousState.redPenalty;
            bluePenalty = previousState.bluePenalty;
            
            updateScoreDisplay('red');
            updateScoreDisplay('blue');
            updateAdvantageDisplay('red');
            updateAdvantageDisplay('blue');
            updatePenaltyDisplay('red');
            updatePenaltyDisplay('blue');
        }

        // Update score display
        function updateScoreDisplay(player) {
            const scoreElement = player === 'red' ? redScoreElement : blueScoreElement;
            const score = player === 'red' ? redScore : blueScore;
            scoreElement.textContent = score;
            if (score >= 10) {
                scoreElement.classList.add('small-digits');
            } else {
                scoreElement.classList.remove('small-digits');
            }
        }

        // Update advantage display
        function updateAdvantageDisplay(player) {
            const advElement = player === 'red' ? redAdvElement : blueAdvElement;
            const advLabel = player === 'red' ? redAdvLabel : blueAdvLabel;
            const advantage = player === 'red' ? redAdvantage : blueAdvantage;
            advElement.textContent = advantage;
            if (advantage > 0) {
                advElement.classList.add('advantage-active');
                advLabel.classList.add('advantage-active');
            } else {
                advElement.classList.remove('advantage-active');
                advLabel.classList.remove('advantage-active');
            }
        }

        // Update penalty display
        function updatePenaltyDisplay(player) {
            const penElement = player === 'red' ? redPenElement : bluePenElement;
            const penLabel = player === 'red' ? redPenLabel : bluePenLabel;
            const penalty = player === 'red' ? redPenalty : bluePenalty;
            penElement.textContent = penalty;
            if (penalty > 0) {
                penElement.classList.add('penalty-active');
                penLabel.classList.add('penalty-active');
            } else {
                penElement.classList.remove('penalty-active');
                penLabel.classList.remove('penalty-active');
            }
        }

        // Adjust score
        function adjustScore(player, points) {
            saveScoreState();
            if (player === 'red') {
                redScore = Math.max(0, redScore + points);
            } else {
                blueScore = Math.max(0, blueScore + points);
            }
            updateScoreDisplay(player);
        }

        // Adjust advantage — simple, independent of penalty
        function adjustAdvantage(player, points) {
            saveScoreState();
            if (player === 'red') {
                redAdvantage = Math.max(0, redAdvantage + points);
            } else {
                blueAdvantage = Math.max(0, blueAdvantage + points);
            }
            updateAdvantageDisplay(player);
        }

        // Adjust penalty with explicit rules per penalty level
        function adjustPenalty(player, points) {
            saveScoreState();
            const opponent = player === 'red' ? 'blue' : 'red';
            const currentPenalty = player === 'red' ? redPenalty : bluePenalty;

            if (points > 0) {
                // Increase penalty by 1
                if (player === 'red') redPenalty++;
                else bluePenalty++;

                if (currentPenalty === 1) {
                    // Was 1, now 2: add 1 to opponent's advantage (without saving state again)
                    if (opponent === 'red') {
                        redAdvantage = Math.max(0, redAdvantage + 1);
                        updateAdvantageDisplay('red');
                    } else {
                        blueAdvantage = Math.max(0, blueAdvantage + 1);
                        updateAdvantageDisplay('blue');
                    }
                } else if (currentPenalty === 2) {
                    // Was 2, now 3: add 2 to opponent's main score (without saving state again)
                    if (opponent === 'red') {
                        redScore = Math.max(0, redScore + 2);
                        updateScoreDisplay('red');
                    } else {
                        blueScore = Math.max(0, blueScore + 2);
                        updateScoreDisplay('blue');
                    }
                }
                // 0→1 or 3+→4+: only penalty increases, no side effects

            } else if (points < 0) {
                if (currentPenalty <= 0) return; // Can't go below 0

                const newPenalty = currentPenalty - 1;
                if (player === 'red') redPenalty = newPenalty;
                else bluePenalty = newPenalty;

                if (currentPenalty === 3) {
                    // Was 3, now 2: deduct 2 from opponent's main score (without saving state again)
                    if (opponent === 'red') {
                        redScore = Math.max(0, redScore - 2);
                        updateScoreDisplay('red');
                    } else {
                        blueScore = Math.max(0, blueScore - 2);
                        updateScoreDisplay('blue');
                    }
                } else if (currentPenalty === 2) {
                    // Was 2, now 1: decrease opponent's advantage by 1 (without saving state again)
                    if (opponent === 'red') {
                        redAdvantage = Math.max(0, redAdvantage - 1);
                        updateAdvantageDisplay('red');
                    } else {
                        blueAdvantage = Math.max(0, blueAdvantage - 1);
                        updateAdvantageDisplay('blue');
                    }
                }
                // 1→0 or 4+→3+: only penalty decreases, no side effects
            }

            updatePenaltyDisplay(player);
        }

        // Timer variables
        const timerDisplay = document.getElementById('timerDisplay');
        const playPauseBtn = document.getElementById('playPauseBtn');
        
        // Parse the starting time from what PHP rendered on screen
        const _initText = timerDisplay.childNodes[0].textContent.trim();
        const _initMatch = _initText.match(/(\d+):(\d+)/);
        let timerSeconds = _initMatch
            ? parseInt(_initMatch[1], 10) * 60 + parseInt(_initMatch[2], 10)
            : (typeof matchDuration !== 'undefined' ? matchDuration : 5) * 60;

        let timerInterval = null;
        let isRunning = false;
        let bellPlayed = false; // Track if bell has been played

        // Audio context for sounds
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();

        // Play bell sound (at 10 seconds)
        function playBell() {
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
            oscillator.type = 'sine';
            
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.5);
        }

        // Play horn sound (at 0 seconds)
        function playHorn() {
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.setValueAtTime(200, audioContext.currentTime);
            oscillator.frequency.exponentialRampToValueAtTime(150, audioContext.currentTime + 0.8);
            oscillator.type = 'sawtooth';
            
            gainNode.gain.setValueAtTime(0.4, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.8);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.8);
        }

        // Format seconds to MM:SS
        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        }

        // Update timer display
        function updateTimerDisplay() {
            const timeText = timerDisplay.childNodes[0];
            timeText.textContent = formatTime(timerSeconds) + ' ';
        }

        // Adjust timer by seconds
        function adjustTimer(seconds) {
            timerSeconds = Math.max(0, timerSeconds + seconds);
            updateTimerDisplay();
            
            // Reset bell flag if timer is adjusted above 10 seconds
            if (timerSeconds > 10) {
                bellPlayed = false;
            }
        }

        // Toggle play/pause
        function togglePlayPause() {
            if (isRunning) {
                pauseTimer();
            } else {
                playTimer();
            }
        }

        // Start timer
        function playTimer() {
            if (timerSeconds <= 0) return;
            
            isRunning = true;
            timerDisplay.classList.remove('paused');
            timerDisplay.classList.add('running');
            playPauseBtn.textContent = 'PAUSE';
            
            timerInterval = setInterval(() => {
                timerSeconds--;
                updateTimerDisplay();
                
                // Play bell at 10 seconds
                if (timerSeconds === 10 && !bellPlayed) {
                    playBell();
                    bellPlayed = true;
                }
                
                // Play horn and pause at 0 seconds
                if (timerSeconds <= 0) {
                    playHorn();
                    pauseTimer();
                    bellPlayed = false; // Reset for next time
                }
            }, 1000);
        }

        // Pause timer
        function pauseTimer() {
            isRunning = false;
            timerDisplay.classList.remove('running');
            timerDisplay.classList.add('paused');
            playPauseBtn.textContent = 'PLAY';
            
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
        }

        // Keyboard control - spacebar toggles play/pause
        document.addEventListener('keydown', function(e) {
            if (e.code === 'Space') {
                e.preventDefault();
                togglePlayPause();
            }
        });

        function showButton() {
            // Old stalling button logic - removed
        }

        function hideButton() {
            const button = document.getElementById('stallingButton');
            const actionButtons1 = document.getElementById('actionButtons1');
            const actionButtons2 = document.getElementById('actionButtons2');
            button.classList.remove('visible');
            actionButtons1.classList.remove('visible');
            actionButtons2.classList.remove('visible');
        }

        // Action buttons logic for first row
        const row1 = document.querySelectorAll('.row')[0];
        const actionButtons1 = document.getElementById('actionButtons1');
        const stallingButton = document.getElementById('stallingButton');

        row1.addEventListener('click', function(e) {
            // Check if click is on the buttons container or its children
            if (actionButtons1.contains(e.target)) {
                return; // Do nothing if clicking on buttons
            }
            
            // Toggle buttons visibility when clicking on row but not on buttons
            if (!actionButtons1.classList.contains('visible')) {
                actionButtons2.classList.remove('visible'); // Hide other player's buttons
                actionButtons1.classList.add('visible');
                stallingButton.classList.add('visible');
            } else {
                actionButtons1.classList.remove('visible');
                stallingButton.classList.remove('visible');
            }
        });

        // Action buttons logic for second row
        const row2 = document.querySelectorAll('.row')[1];
        const actionButtons2 = document.getElementById('actionButtons2');

        row2.addEventListener('click', function(e) {
            // Check if click is on the buttons container or its children
            if (actionButtons2.contains(e.target)) {
                return; // Do nothing if clicking on buttons
            }
            
            // Toggle buttons visibility when clicking on row but not on buttons
            if (!actionButtons2.classList.contains('visible')) {
                actionButtons1.classList.remove('visible'); // Hide other player's buttons
                actionButtons2.classList.add('visible');
                stallingButton.classList.add('visible');
            } else {
                actionButtons2.classList.remove('visible');
                stallingButton.classList.remove('visible');
            }
        });

        // Hide buttons when clicking outside both rows
        document.addEventListener('click', function(e) {
            if (!row1.contains(e.target) && !row2.contains(e.target)) {
                actionButtons1.classList.remove('visible');
                actionButtons2.classList.remove('visible');
                stallingButton.classList.remove('visible');
            }
        });

        // Control buttons logic for timer section
        const timerSection = document.getElementById('timerSection');
        const controlButtons = document.getElementById('controlButtons');
        const timerControls = document.getElementById('timerControls');

        timerSection.addEventListener('click', function(e) {
            // Check if click is on the buttons container or its children
            if (controlButtons.contains(e.target) || timerControls.contains(e.target)) {
                return; // Do nothing if clicking on buttons
            }
            
            // Toggle buttons visibility when clicking on timer section but not on buttons
            if (!controlButtons.classList.contains('visible')) {
                controlButtons.classList.add('visible');
                timerControls.classList.add('visible');
            } else {
                controlButtons.classList.remove('visible');
                timerControls.classList.remove('visible');
            }
        });

        // Hide control buttons when clicking outside timer section
        document.addEventListener('click', function(e) {
            if (!timerSection.contains(e.target)) {
                controlButtons.classList.remove('visible');
                timerControls.classList.remove('visible');
            }
        });

        // Check if text overflows and enable marquee
        function checkOverflow() {
            const details = document.getElementById('matchDetails');
            const stage = document.getElementById('matchStage');
            
            [details, stage].forEach(element => {
                const content = element.querySelector('.marquee-content');
                const parent = element;
                
                // Reset first
                content.classList.remove('duplicate', 'animate');
                const originalText = content.textContent.split('    ')[0]; // Get original text
                content.textContent = originalText;
                
                // Check if overflow exists
                if (parent.scrollWidth > parent.clientWidth) {
                    const text = originalText;
                    content.setAttribute('data-text', text);
                    content.classList.add('duplicate');
                    content.textContent = text + '    ' + text;
                    content.classList.add('animate');
                }
            });
        }

        // window.addEventListener('load', checkOverflow);
        // window.addEventListener('resize', checkOverflow);

let duration = 5 * 60;
let remaining = duration;
let interval = null;
let warningPlayed = false;

const display = document.getElementById("timer");
const startPauseBtn = document.getElementById("startPauseBtn");

function updateDisplay() {
  let min = Math.floor(remaining / 60);
  let sec = remaining % 60;
  display.textContent = `${min}:${sec.toString().padStart(2, '0')}`;
}

function playTone(frequency, duration) {
  const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
  const oscillator = audioCtx.createOscillator();
  const gainNode = audioCtx.createGain();

  oscillator.connect(gainNode);
  gainNode.connect(audioCtx.destination);

  oscillator.type = 'sine';
  oscillator.frequency.value = frequency;
  oscillator.start();

  gainNode.gain.setValueAtTime(1, audioCtx.currentTime);
  gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration / 1000);

  oscillator.stop(audioCtx.currentTime + duration / 1000);
}

function playWarningSound() {
  // High short beep (880Hz, 200ms)
  playTone(880, 200);
}

function playHornSound() {
  // Lower longer beep (220Hz, 800ms)
  playTone(220, 800);
}

function toggleTimer() {
  if (interval) {
    pauseTimer();
  } else {
    startTimer();
  }
}

function startTimer() {
  if (remaining <= 0) return;
  warningPlayed = false; // reset warning flag

  interval = setInterval(() => {
    if (remaining > 0) {
      remaining--;
      updateDisplay();

      if (remaining === 10 && !warningPlayed) {
        playWarningSound();
        warningPlayed = true;
      }
    } else {
      clearInterval(interval);
      interval = null;
      startPauseBtn.textContent = "Start";
      playHornSound();
    }
  }, 1000);
  startPauseBtn.textContent = "Pause";
}

function pauseTimer() {
  clearInterval(interval);
  interval = null;
  startPauseBtn.textContent = "Start";
}

function adjustTime(seconds) {
  remaining = Math.max(0, remaining + seconds);
  updateDisplay();
}

window.onload = () => {
  updateDisplay();

  window.addEventListener('keydown', (e) => {
    if (e.code === 'Space') {
      e.preventDefault();
      toggleTimer();
    }
  });
};
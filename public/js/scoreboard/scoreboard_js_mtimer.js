document.querySelectorAll('.mtimer-container').forEach(container => {
    let timeDisplay = container.querySelector('.mtime');
    let playBtn = container.querySelector('.mplay');
    let resetBtn = container.querySelector('.mreset');
    let minusBtn = container.querySelector('.mminus');
    let plusBtn = container.querySelector('.mplus');
    let swapBtn = container.querySelector('.mswap');

    let time = 120; // in seconds (2:00)
    let interval = null;

    function updateDisplay() {
        let minutes = Math.floor(time / 60);
        let seconds = time % 60;
        timeDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    }

    playBtn.addEventListener('click', () => {
        if (interval) {
            clearInterval(interval);
            interval = null;
            playBtn.textContent = "Play";
        } else {
            interval = setInterval(() => {
                if (time > 0) {
                    time--;
                    updateDisplay();
                } else {
                    clearInterval(interval);
                    playBtn.textContent = "Play";
                }
            }, 1000);
            playBtn.textContent = "Pause";
        }
    });

    resetBtn.addEventListener('click', () => {
        clearInterval(interval);
        interval = null;
        time = 120;
        updateDisplay();
        playBtn.textContent = "Play";
    });

    minusBtn.addEventListener('click', () => {
        time--;
        updateDisplay();
    });

    plusBtn.addEventListener('click', () => {
        time++;
        updateDisplay();
    });

    updateDisplay();
});

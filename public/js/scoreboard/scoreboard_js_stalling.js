let stimer1 = 10, stimer2 = 10;
    let sinterval1 = null, sinterval2 = null;

    const stimer1Display = document.getElementById("stimer1");
    const stimer2Display = document.getElementById("stimer2");
    const sbtn1 = document.getElementById("sbtn1");
    const sbtn2 = document.getElementById("sbtn2");
    const sbtn3 = document.getElementById("sbtn3");
    const sbtn4 = document.getElementById("sbtn4");
    const sbtn5 = document.getElementById("sbtn5");

    function startTimer1() {
      if (sinterval1) return;
      sinterval1 = setInterval(() => {
        stimer1--;
        stimer1Display.textContent = stimer1;
        if (stimer1 === 0) {
          clearInterval(sinterval1);
          sinterval1 = null;
          stimer1 = 10;
          stimer1Display.textContent = stimer1;
          // sbtn1.textContent = "Start Timer 1";
        }
      }, 1000);
    }

    function startTimer2() {
      if (sinterval2) return;
      sinterval2 = setInterval(() => {
        stimer2--;
        stimer2Display.textContent = stimer2;
        if (stimer2 === 0) {
          clearInterval(sinterval2);
          sinterval2 = null;
          stimer2 = 10;
          stimer2Display.textContent = stimer2;
          // sbtn3.textContent = "Start Timer 2";
        }
      }, 1000);
    }

    function resetTimer1() {
      clearInterval(sinterval1);
      sinterval1 = null;
      stimer1 = 10;
      stimer1Display.textContent = stimer1;
    }

    function resetTimer2() {
      clearInterval(sinterval2);
      sinterval2 = null;
      stimer2 = 10;
      stimer2Display.textContent = stimer2;
    }

    sbtn1.addEventListener("click", () => {
      if (!sinterval1) {
        startTimer1();
        // sbtn1.textContent = "Reset Timer 1";
      } else {
        resetTimer1();
        // sbtn1.textContent = "Start Timer 1";
      }
    });
    sbtn4.addEventListener("click", () => {
      if (!sinterval1) {
        startTimer1();
      } else {
        resetTimer1();
      }
    });

    sbtn2.addEventListener("click", () => {
      const bothStopped = !sinterval1 && !sinterval2;
      if (bothStopped) {
        startTimer1();
        startTimer2();
        // sbtn1.textContent = "Reset Timer 1";
        // sbtn3.textContent = "Reset Timer 2";
        // sbtn2.textContent = "Reset Both";
      } else {
        resetTimer1();
        resetTimer2();
        // sbtn1.textContent = "Start Timer 1";
        // sbtn3.textContent = "Start Timer 2";
        // sbtn2.textContent = "Start Both";
      }
    });

    sbtn3.addEventListener("click", () => {
      if (!sinterval2) {
        startTimer2();
        // sbtn3.textContent = "Stop Timer 2";
      } else {
        resetTimer2();
        // sbtn3.textContent = "Start Timer 2";
      }
    });
    sbtn5.addEventListener("click", () => {
      if (!sinterval2) {
        startTimer2();
      } else {
        resetTimer2();
      }
    });
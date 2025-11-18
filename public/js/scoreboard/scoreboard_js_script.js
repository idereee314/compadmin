let scores = { top: 0, bottom: 0 };
  let miniScores = {
    top: { advantage: 0, penalty: 0 },
    bottom: { advantage: 0, penalty: 0 }
  };

  function changeScore(player, delta) {
    scores[player] = Math.max(0, scores[player] + delta);
    updateScoreDisplay(player);
  }

  function changeMiniScore(player, type, delta) {
    miniScores[player][type] = Math.max(0, miniScores[player][type] + delta);
    updateMiniScoreDisplay(player, type);
  }

  function updateScoreDisplay(player) {
    const scoreEl = document.getElementById(`scoreValue${capitalize(player)}`);
    const score = scores[player];

    scoreEl.classList.remove('two-digits', 'three-digits');
    if (score > 99) {
      scoreEl.classList.add('three-digits');
    } else if (score > 9) {
      scoreEl.classList.add('two-digits');
    }
    scoreEl.textContent = score;
  }

  function updateMiniScoreDisplay(player, type) {
    const el = document.getElementById(`${type}${capitalize(player)}`);
    el.textContent = miniScores[player][type];
  }

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  function checkMarquee(id) {
    const el = document.getElementById(id);
    const wrapper = el.parentElement;
    if (el.scrollWidth > wrapper.clientWidth) {
      el.classList.add('marquee');
    } else {
      el.classList.remove('marquee');
    }
  }

  // Initialize scores and marquee on load
  updateScoreDisplay('top');
  updateScoreDisplay('bottom');
  updateMiniScoreDisplay('top', 'advantage');
  updateMiniScoreDisplay('top', 'penalty');
  updateMiniScoreDisplay('bottom', 'advantage');
  updateMiniScoreDisplay('bottom', 'penalty');

  checkMarquee('fullNameTop');
  checkMarquee('fullNameBottom');

  // Optional: re-check marquee on window resize
  window.addEventListener('resize', () => {
    checkMarquee('fullNameTop');
    checkMarquee('fullNameBottom');
  });


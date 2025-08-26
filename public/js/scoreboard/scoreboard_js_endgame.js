const popup1 = document.getElementById("popup1");
const popup2 = document.getElementById("popup2");
const winnerDiv1 = document.getElementById("winner1");
const winnerDiv2 = document.getElementById("winner2");
const controlsMini = document.getElementById("controlsMini");
const popup3 = document.getElementById("popup3");

document.getElementById("endBtn").addEventListener("click", () => {
    popup1.style.display = "block";
    popup2.style.display = "block";
    popup3.style.display = "block";
    winnerDiv1.textContent = ""; // clear previous winner
    winnerDiv2.textContent = ""; // clear previous winner
});

function chooseWinner(color, type, selectedWinnerId) {
    const lowerColor = color.toLowerCase();
    const upperType = type.toUpperCase();

    // Hide all popups
    popup1.style.display = "none";
    popup2.style.display = "none";
    popup3.style.display = "none";


    if (lowerColor === "red") {
        winnerDiv1.textContent = `Winner by ${upperType}!`;
        winnerDiv1.style.color = "black";
        winnerDiv1.style.textAlign = "center";
        winnerDiv1.style.background = "yellow";
        winnerDiv1.style.fontSize = "3.5rem";
        winnerDiv1.style.fontWeight = "bold";

    } else if (lowerColor === "blue") {
        winnerDiv2.textContent = `Winner by ${upperType}!`;
        winnerDiv2.style.color = "black";
        winnerDiv2.style.textAlign = "center";
        winnerDiv2.style.background = "yellow";
        winnerDiv2.style.fontSize = "3.5rem";
        winnerDiv2.style.fontWeight = "bold";
    }
    setWinnerId(selectedWinnerId); 
    form.submit();
}

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


function doubleLoser(type) {
    const doubleType = type.toUpperCase();

    // Hide all popups
    popup1.style.display = "none";
    popup2.style.display = "none";
    popup3.style.display = "none";

    if (doubleType === 'WO/DQ') {
        winnerDiv1.textContent = `DOUBLE ${doubleType}!`;
        winnerDiv1.style.color = "black";
        winnerDiv1.style.textAlign = "center";
        winnerDiv1.style.background = "white";
        winnerDiv1.style.fontSize = "2rem";
        winnerDiv1.style.fontWeight = "bold";
        winnerDiv2.textContent = `DOUBLE ${doubleType}!`;
        winnerDiv2.style.color = "black";
        winnerDiv2.style.textAlign = "center";
        winnerDiv2.style.background = "white";
        winnerDiv2.style.fontSize = "2rem";
        winnerDiv2.style.fontWeight = "bold";
    } else if (doubleType === 'NO SHOW') {
        winnerDiv1.textContent = `DOUBLE ${doubleType}!`;
        winnerDiv1.style.color = "black";
        winnerDiv1.style.textAlign = "center";
        winnerDiv1.style.background = "white";
        winnerDiv1.style.fontSize = "2rem";
        winnerDiv1.style.fontWeight = "bold";
        winnerDiv2.textContent = `DOUBLE ${doubleType}!`;
        winnerDiv2.style.color = "black";
        winnerDiv2.style.textAlign = "center";
        winnerDiv2.style.background = "white";
        winnerDiv2.style.fontSize = "2rem";
        winnerDiv2.style.fontWeight = "bold";
    }
}

function close_window() {
  if (confirm("Close Window?")) {
    close();
  }
}
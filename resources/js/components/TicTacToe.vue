<template>
    <div class="container">
      <div class="header">
        <h1>Tic Tac Toe</h1>
  
        <div class="computer-new-game">
          <button ref="computerPlayAgainButton" class="computer-new button" @click="startComputerGameAgain">
            New Game 1 Player
          </button>
        </div>
  
        <div class="opponent-new-game">
          <button ref="opponentPlayAgainButton" class="opponent-new button" @click="startOpponentGameAgain">
            New Game 2 Players
          </button>
        </div>
  
        <div class="options_container">
          <select ref="dropdown" @change="handleDropdownChange">
            <option value="x">Start as X</option>
            <option value="o">Start as O</option>
          </select>
        </div>
      </div>
  
      <div ref="board" class="board">
        <div class="row" v-for="col in [0, 1, 2]" :key="col">
          <div
            v-for="row in [0, 1, 2]"
            :key="col + row * 3"
            class="space"
            :id="col + row * 3"
            @click="handleBoardClick(col + row * 3)"
          >
            <h2 v-if="boardArr[col + row * 3]" class="x">{{ boardArr[col + row * 3].toUpperCase() }}</h2>
          </div>
        </div>
      </div>
  
      <div class="footer">
        <div class="text">
          <h2 id="text" :class="{ appear: textVisible }">{{ text }}</h2>
        </div>
        <div class="start-buttons">
          <div class="start-btn-div">
            <button ref="opponentStartButton" class="opponent-start-btn start-btn" @click="opponentStartGame">
              2 Players
            </button>
          </div>
          <div class="start-btn-div">
            <button ref="computerStartButton" class="computer-start-btn start-btn" @click="computerStartGame">
              1 Player
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'TicTacToe',
    data() {
      return {
        boardArr: Array(9).fill(''),
        turn: 0,
        gameStarted: false,
        inPlay: false,
        computerGame: false,
        thisGame: 'x',
        nextGame: 'x',
        winningToken: false,
        text: '',
        textVisible: false,
        winningArray: [
          [0, 1, 2], [3, 4, 5], [6, 7, 8],
          [0, 3, 6], [1, 4, 7], [2, 5, 8],
          [0, 4, 8], [2, 4, 6],
        ],
      };
    },
    methods: {
      opponentStartGame() {
        this.startGame();
        this.computerGame = false;
      },
      computerStartGame() {
        this.startGame();
        this.computerGame = true;
      },
      startComputerGameAgain() {
        this.inPlay = false;
        this.computerGame = true;
        this.playAgain();
      },
      startOpponentGameAgain() {
        this.inPlay = false;
        this.computerGame = false;
        this.playAgain();
      },
      startGame() {
        this.$refs.opponentStartButton.classList.add('disapear');
        this.$refs.computerStartButton.classList.add('disapear');
        this.gameStarted = true;
  
        setTimeout(() => {
          this.$refs.board.classList.add('show-board');
          this.$refs.computerPlayAgainButton.classList.add('button-appear');
          this.$refs.opponentPlayAgainButton.classList.add('button-appear');
        }, 900);
      },
      playAgain() {
        this.resetBoard();
        this.gameStarted = true;
        this.textVisible = false;
      },
      handleDropdownChange(e) {
        this.nextGame = e.target.value;
      },
      handleBoardClick(index) {
        if (!this.gameStarted || this.boardArr[index] || this.winningToken) return;
  
        if (!this.inPlay) {
          this.thisGame = this.nextGame;
          this.inPlay = true;
        }
  
        this.placeLogic(index, 0);
        this.turn++;
        this.checkBoard();
  
        if (this.computerGame && this.gameStarted && !this.winningToken) {
          this.turn++;
          this.computerTurn();
        }
      },
      placeLogic(index, i) {
        const isXTurn = this.thisGame === 'x' ? this.turn % 2 === i : this.turn % 2 !== i;
        this.placeToken(index, isXTurn ? 'x' : 'o');
      },
      placeToken(index, token) {
        this.boardArr[index] = token;
      },
      checkBoard() {
        const result = this.checkForWin();
        if (result.win) {
          this.setWin(result.token);
          return;
        }
        this.checkForDraw();
      },
      setWin(token) {
        this.winningToken = token;
        this.toggleText(`${token.toUpperCase()} Wins!`);
      },
      checkForWin() {
        for (const [a, b, c] of this.winningArray) {
          if (this.boardArr[a] && this.boardArr[a] === this.boardArr[b] && this.boardArr[b] === this.boardArr[c]) {
            return { win: true, token: this.boardArr[a] };
          }
        }
        return { win: false, token: null };
      },
      checkForDraw() {
        if (this.turn === 9 && !this.checkForWin().win) {
          this.toggleText('Draw Game');
          this.winningToken = true;
        }
      },
      toggleText(message) {
        this.text = message;
        this.textVisible = true;
      },
      resetBoard() {
        this.turn = 0;
        this.winningToken = false;
        this.boardArr = Array(9).fill('');
        this.text = '';
      },
      emptyIndexes() {
        return this.boardArr.map((v, i) => v === '' ? i : null).filter(v => v !== null);
      },
      checkIfClose(token) {
        for (const index of this.emptyIndexes()) {
          const temp = [...this.boardArr];
          temp[index] = token;
          const win = this.checkForWin(temp);
          if (win.win && Math.floor(Math.random() * 15) !== 1) return index;
        }
        return false;
      },
      computerTurn() {
        let index = this.checkIfClose(this.thisGame === 'x' ? 'o' : 'x') ||
                    this.checkIfClose(this.thisGame) ||
                    this.emptyIndexes()[Math.floor(Math.random() * this.emptyIndexes().length)];
  
        this.gameStarted = false;
        setTimeout(() => {
          this.placeLogic(index, 1);
          this.gameStarted = true;
          this.checkBoard();
        }, 1000);
      }
    }
  };
  </script>


<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Roboto", sans-serif;
    }

    .container {
        width: 100%;
        height: 140vh;
        flex-direction: column;
        align-items: center;
        display: flex;
    }

    .no-transition {
        transition: none !important;
    }

    .appear {
        opacity: 1 !important;
    }

    .disapear {
        opacity: 0 !important;
        pointer-events: none !important;
    }

    body {
        background-image: linear-gradient(40deg, #e36414, #c98bb9);
        background-size: cover;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        text-align: center;
    }

    .board {
        position: relative;
        display: flex;
        margin: 0 auto;
        height: 600px;
        width: 600px;
        justify-content: center;
        align-items: center;
        opacity: 0;
        pointer-events: none;
        transition: all 1s ease;
    }

    @media (max-width: 547px) {
        .board {
            height: 400px;
            width: 400px;
        }
    }

    .show-board {
        opacity: 1;
    }

    .button {
        color: azure;
        background: #ff3c38;
        border-radius: 7px;
        padding: 15px 60px;
        text-align: center;
        font-size: 2rem;
        margin: 0 auto;
        transition: all 1s ease;
        border: none;
        cursor: pointer !important;
        font-size: 16px;
        margin: 0 auto;
        opacity: 0;
        transition: opacity 1s ease, color 0.3s ease, background 0.3s ease;
        pointer-events: all;
    }

    .button:hover {
        transform: all 0.3s ease;
        background-color: #ee0400;
    }

    .button-appear {
        opacity: 0.9;
    }

    .button:focus {
        outline: 0;
    }

    .computer-new-game {
        margin-bottom: 0.5rem;
    }

    .start-btn {
        background-color: #ff3c38;
        border: none;
        position: relative;
        bottom: 10rem;
        border-radius: 5px;
        color: azure;
        padding: 15px 40px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        cursor: pointer;
        transition: opacity 1s ease, color 0.3s ease, background 0.3s ease;
    }

    .start-btn:hover {
        background-color: azure;
        color: #ff3c38;
    }

    .start-btn:focus {
        outline: 0;
    }

    .start-buttons {
        display: flex;
        justify-content: center;
    }

    .start-btn-div {
        margin: 10px;
    }

    @media (max-width: 547px) {
        .start-btn {
            padding: 15px 38px;
        }
    }

    @media (max-width: 768px) {
        .start-btn {
            padding: 17.5px 44px;
        }
    }

    .button::selection {
        background: transparent;
    }
    .button::-moz-selection {
        background: transparent;
    }
    .footer {
        display: flex;
        flex-direction: column;
        width: 70%;
    }

    #text {
        color: rgb(46, 46, 46);
        text-align: center;
        font-size: 2rem;
        margin: 0 auto;
        opacity: 0;
        transition: all 1s ease;
        background: transparent;
        border: none;
    }

    #text::selection {
        background: transparent;
    }
    #text::-moz-selection {
        background: transparent;
    }
    .header {
        height: 250px;
        width: 100%;
        margin-top: 30px;
    }

    h1 {
        font-size: 4rem;
        text-align: center;
        color: rgb(41, 41, 41);
    }

    h1::selection {
        background: transparent;
    }
    h1::-moz-selection {
        background: transparent;
    }
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        outline: none;
        border: none;
        /* color: azure; */
        width: 7rem;
        padding: 0.7rem;
        cursor: pointer;
        background: #ff3c38;
    }

    .options_container {
        margin: 1rem;
        position: absolute;
        overflow: hidden;
        bottom: 80%;
        right: 10%;
        border-radius: 3px;
    }

    @media (max-width: 1100px) {
        .options_container {
            left: 75%;
            margin-right: 10px; 
        }
    }

    @media (max-width: 645px) {
        .options_container {
            left: 75%;
            bottom: 78%;
            margin-right: 10px; 
        }
    }

    @media (max-width: 547px) {
        .options_container {
            bottom: 2%;
            left: 75%;
            margin-right: 10px; 
        }
    }
    .space {
        pointer-events: all;
        cursor: pointer;
        border: rgba(24, 24, 24, 0.8) 3px solid;
        border-radius: 5px;
        background-image: linear-gradient(
            140deg,
            rgba(255, 255, 255, 0.1),
            rgba(255, 255, 255, 0.3)
        );
        height: 100%;
        width: 95%;
        justify-content: space-between;
        flex-wrap: wrap;
        margin: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: all 0.3s ease;
    }

    .space:hover {
        background-image: linear-gradient(
            140deg,
            rgba(255, 255, 255, 0.2),
            rgba(255, 255, 255, 0.4)
        );
    }

    .row {
        height: 100%;
        width: 33%;
        display: flex;
        flex-direction: column;
    }

    .x {
        font-size: 70px;
        font-family: "RocknRoll One", sans-serif;
        color: rgba(17, 17, 17, 0.9);
    }

    .x::selection {
        background: transparent;
    }
    .x::-moz-selection {
        background: transparent;
    }
</style>
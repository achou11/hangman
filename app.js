readTextFile("uploads/test.txt");


var words;
function readTextFile(file) {
    var rawFile = new XMLHttpRequest();
    rawFile.open("GET", file, false);
    rawFile.onreadystatechange = function ()
    {
        if(rawFile.readyState === 4)
        {
            if(rawFile.status === 200 || rawFile.status == 0)
            {
                var allText = rawFile.responseText;
                words = allText.split("\n");
            }
        }
    }
    rawFile.send(null);
}



// List of words to choose randomly from
//var words = ['happy', 'sadness', 'madness', 'angry', 'tired', 'excited', 'hormonal'];

words = words.map(v => v.toLowerCase());


// Create scene variables to update the graphics
var sceneNumInt = 1;
var sceneNumStr = 'scene' + sceneNumInt;


// Set number of lives for user
var livesTag = document.getElementById('num-lives');
var lives = 10;
livesTag.innerHTML = lives;


// Randomly select a word for user to guess from generated array
var targetWord = words[ Math.floor(Math.random() * words.length) ];
console.log('Target word: ' + targetWord);

var targetWordList = targetWord.split('');
console.log('Target word list: ' + targetWordList);


// Create array of list of same length as target word, filled with underscores
// to portray to user; will be filled as user correctly guesses letters
var blankWord = Array.apply(null, Array(targetWord.length)).map(String.prototype.valueOf,'_');
console.log('Blank word: ' + blankWord);


// Show updated word to user as letters are guessed
var showWord = document.getElementById('show-word');
showWord.innerHTML = blankWord.join(' ');


// Keep track of letters already guessed by user
var alreadyGuessed = document.getElementById('already-guessed');
var alreadyGuessedArray = [];


// If enter key is pressed, submit guess;
// if esc key is pressed, clear input
function enterKeyChange() {
    var submitButton = document.getElementById('submit-btn');
    if (event.keyCode == 13) {
        submitButton.click();
        document.getElementById('user-guess').value = '';
    } else if (event.keyCode == 27) {
        document.getElementById('user-guess').value = '';
    }

}

// Show input error message
function showInputMessage(message) {
    var inputError = document.getElementById('input-error');
    if (message === '') {
        inputError.innerHTML = '';
    } else {
        inputError.innerHTML = '(' + message + '...)';
    }
}

// Add 1 to score if user wins
function changeScore(status) {
    var scoreTag = document.getElementById("show-score");
    var scoreValue = parseInt(scoreTag.innerHTML);

    if (status) {
        scoreValue++;
    } else {
        scoreValue--;
    }

    scoreTag.innerHTML = scoreValue;
    //console.log(typeof(scoreValue));
    return scoreValue.toString();
}



// Where the game play happens after user guesses
function enterGuess() {
    var userGuess = document.getElementById('user-guess').value.toLowerCase();

    document.getElementById('user-guess').value = '';
    // Error check user's guess
    if (alreadyGuessedArray.includes(userGuess)) {
        showInputMessage('Already guessed that letter');
        return;
    } else if (userGuess.length > 1) {
        showInputMessage('Please only guess one letter at a time');
        return;
    } else if (userGuess.length == 0) {
        showInputMessage('You gotta type something in the damn box');
        return;
    } else if (!userGuess.match(/[a-z]/)) {
        showInputMessage('Letters only please');
        return;
    }

    // If user's input is valid, remove last invalid input message
    showInputMessage('');

    alreadyGuessedArray.push(userGuess);
    alreadyGuessed.innerHTML = alreadyGuessedArray.join(' ');

    console.log('Guess is ' + userGuess);

    // Create array containing indices of where guess occurs in letter if it's correct
    var indexArray = [];
    if (targetWordList.includes(userGuess)) {
        console.log('Already guessed letters: ' + alreadyGuessedArray);

        targetWordList.forEach(function(element, index) {
            if (element === userGuess) {
                indexArray.push(index);
            }
        });

        // Replace all occurrences of guessed letter into displayed word and redisplay to user
        indexArray.forEach(function(idx) {
            blankWord.splice(idx, 1, targetWordList[idx]);
        });

        console.log('updated target is ' + blankWord);

        showWord.innerHTML = blankWord.join(' ');
    } else if (targetWordList.includes(userGuess) === false) {
        // decrease number of lives by 1
        lives--;
        livesTag.innerHTML = lives;

        // Update the canvas
        // When sceneNumInt exceeds 10, the player has lost
        sceneNumInt += 1;
        sceneNumStr = 'hangman' + sceneNumInt;
        newSource = "uploads/" + sceneNumStr + ".png";
        document.getElementById('scene').src = newSource;

        console.log('Lives remaining: ' + lives);
        console.log('Incorrect guess. Try again!');  // if guess is incorrect
    }

    // User correctly guesses all letters in word
    if (blankWord.join('') === targetWord) {
        document.getElementById('win-lose').innerHTML = 'Congrats. You won!';
        var newScoreWin = changeScore(true);
        updateUserScore(newScoreWin);
    }

    // User runs out of lives
    if (lives == 0) {
        document.getElementById('win-lose').innerHTML = 'Game over. You lost.<br/>But at least you got away :)';
        var newScoreLose = changeScore(false);
        updateUserScore(newScoreLose);
    }
}

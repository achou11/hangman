// List of words to choose randomly from
var words = ['happy', 'sadness', 'madness', 'angry', 'tired', 'excited', 'hormonal'];

// Lowercase all words in list
words = words.map(function(w) {
    return w.toLowerCase();
});


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

// Where the game play happens
function enterGuess() {
    var userGuess = document.getElementById('user-guess').value.toLowerCase();

    // Error check user's guess
    if (alreadyGuessedArray.includes(userGuess)) {
        console.log('Already guessed that letter!');
        return;
    } else if (userGuess.length > 1) {
        console.log('Please only guess one letter at a time. Thanks!');
        return;
    } else if (userGuess.length == 0) {
        console.log('You gotta type something in the damn box.');
        return;
    } 
       
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
        console.log('Lives remaining: ' + lives);
        console.log('Incorrect guess. Try again!');  // if guess is incorrect
    }

    // User correctly guesses all letters in word
    if (blankWord.join('') === targetWord) {
        document.getElementById('win-lose').innerHTML = 'Congrats. You won!';
    }

    // User runs out of lives
    if (lives == 0) {
        document.getElementById('win-lose').innerHTML = 'Game over. You lost';        
    }

}
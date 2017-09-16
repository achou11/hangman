
// List of words to choose randomly from */
var words = ['happy', 'sadness', 'madness', 'angry', 'tired', 'excited', 'hormonal'];


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

// Where the gameplay happens
function enterGuess() {
    var userGuess = document.getElementById('user-guess').value;
    console.log('Guess is ' + userGuess);

    // Create array containing indices of where guess occurs in letter if it's correct
    var indexArray = [];
    if (targetWordList.includes(userGuess)) {
        targetWordList.forEach(function(element, index) {
            if (element === userGuess) {
                indexArray.push(index);
            }
        });

        
        console.log('Index array is ' + indexArray);

        // Replace all occurrences of guessed letter into displayed word and redisplay to user
        indexArray.forEach(function(idx) {
            console.log(idx);
            blankWord.splice(idx, 1, targetWordList[idx]);
        });

        console.log('updated target is ' + blankWord);
        
        showWord.innerHTML = blankWord.join(' ');
    } 
    // If guess is incorrect
    else if (targetWordList.includes(userGuess) === false) {
        console.log('Incorrect guess. Try again!');
    }
    
}
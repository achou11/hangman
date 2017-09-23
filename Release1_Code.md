# **User Stories for First Release**

**NOTE**: Some code blocks will have an arrow next to them to indicate that they are collapsible. We do this for blocks exceeding 10 lines. Click on the arrow or any space along the same line to toggle the code blocks. Try out this example: 

<details>
	<summary>hello.js</summary>

```javascript
var example = "Hello World!"
```
</details>

<br>

## Pre-Game  

 
#### User Registration  

  
User registration in implemented in `index.php`. Within this file, there is embedded html that serves as a template to render the user signup page. [[see source](https://github.com/achou11/swEng-project0/blob/master/index.php)]
	

<details>
	<summary>index.php (lines 283-304)</summary>

```html
<div id = "signupForm" style = "display: none;">
	<form name = "signupForm" method="post" action="index.php#signupForm" autocomplete="off">
		
		<input type="text" id="username" name="username" placeholder="<?php echo $userError; ?>" maxlength="50" value="<?php echo $username ?>" />
		<input type="text" id="email" name="email" placeholder="<?php echo $emailError; ?>" maxlength="40"  value="<?php echo $email ?>"/>
		<input type="password" id="pword" name="pass" placeholder="<?php echo $passError; ?>" maxlength="35" />
		<input type="password" id="pword2" name="pass2" placeholder="<?php echo $passError2; ?>" maxlength="35" />
		<div style = "margin-top: 25px"></div>
		<?php
		if ( isset($errMSG) ) {
			
			?>
			<span class="text-danger"></span> <?php echo $errMSG; ?>
			<?php
		}
		?>

		<input type="submit" name="btn-signup" value="Sign up">
	
	</form>
	<div class = "guest-sign"><a href = "home.html">Play as guest</a></div>
</div>
```  
</details>

<br> 

It also validates the user signup credentials before entering it into the database.

<details>
	<summary>index.php (lines 22-136)</summary>

```php
$userError = "Username";
$emailError = "Email";
$passError = "Password";
$passError2 = "Re-enter Password";
	
if ( isset($_POST['btn-signup']) ) {
	
	// clean user inputs to prevent sql injections
	`
	
	$username = trim($_POST['username']);
	$username = strip_tags($username);
	$username = htmlspecialchars($username);
	
	$email = trim($_POST['email']);
	$email = strip_tags($email);
	$email = htmlspecialchars($email);
	
	$pass = trim($_POST['pass']);
	$pass = strip_tags($pass);
	$pass = htmlspecialchars($pass);
	$pass2 = trim($_POST['pass2']);
	$pass2 = strip_tags($pass2);
	$pass2 = htmlspecialchars($pass2
	);
	
	
	
	
	// basic name validation
	if (empty($username)) {
		$error = true;
		$userError = "Please enter a username";
	} else if (!preg_match("/^[a-zA-Z0-9 ]+$/",$username)) {
		$error = true;
		unset($username);
		$userError = "No special characters";
	}	else {
		// check email exist or not
		$query = "SELECT userName FROM users WHERE userName='$username'";
		$result = mysqli_query($conn, $query);
		$count = mysqli_num_rows($result);
		if($count!=0){
			$error = true;
			unset($username);
			$userError = "Username taken";
		}
	}
	// basic age validation
	
	
	//basic email validation
	if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
		$error = true;
		$emailError = "Enter a valid email.";
	}
	else {
		// check email exist or not
		$query = "SELECT userEmail FROM users WHERE userEmail='$email'";
		$result = mysqli_query($conn, $query);
		$count = mysqli_num_rows($result);
		if($count!=0){
			$error = true;
			unset($email);
			$emailError = "Provided Email is already in use.";
		}
	}
	// password validation
	if (empty($pass)){
		$error = true;
		$passError = "Please enter password.";
	} else if(strlen($pass) < 6) {
		$error = true;
		$passError = "Enter at least 6 characters.";
	}
	
	if ($pass != $pass2){
		$error = true;
		$passError2 = "Password does not match!";
	}
	// password encrypt using SHA256();
	$password = hash('sha256', $pass);
	
	// if there's no error, continue to signup
	if( !$error ) {
		$query = "INSERT INTO users(userName,userEmail,userPass) VALUES('$username','$email','$password')";
		$res = mysqli_query($conn, $query);
		
		
		
		if ($res) {
			$errTyp = "success";
			$errMSG = "Successfully registered, you may login now";
			
		$res=mysqli_query($conn, "SELECT userId, userName, userPass FROM users WHERE (userName='$username')");
		$row=mysqli_fetch_array($res);
			$_SESSION['user'] = $row['userId'];
			header("Location: home.php");
			
		
		} else {
			$errTyp = "danger";
			$errMSG = "Something went wrong, try again later...";
		}
			
	}
}
```
</details>

<br>


## Post-Login: Admin  

#### Access to Users and Other Admins Information 

The admin page contains a list of registered users and relevant information about each user, such as their ID, username, email, and number of points. The admin can also remove users at their own discretion. [[see source](https://github.com/achou11/swEng-project0/blob/master/homeadmin.php)]

The following code is written in a `.php` file and uses an html template to render the users list.

<details>
	<summary>homeadmin.php (lines 55-94)</summary>

```php
<div class = "users">
<h3 style = "text-align: center;">Users</h3>
<div id = "table-scroll">
  <table class = "userlog">
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Email</th>
      <th>Points</th>
      <th>Remove User</th>
      <th>Promote User</th>
    </tr>
    <?php
      while($usersRow = mysqli_fetch_array($usersQuery)){
      
      $headAdminPromote="";
      
      if($userRow["userName"] == "ipawds")
      {
  
        $headAdminPromote="<a href='promoteMember.php?id=".$usersRow['userId']."'onclick = \"return confirm('Are you sure you want to promote?')\">Promote</a>";
      }
      else
      {
        $headAdminPromote="-";
      }
          echo
              "<tr>
              <td>" .$usersRow["userId"]. "</td>
              <td>" .$usersRow["userName"]. "</td>
              <td>" .$usersRow["userEmail"]. "</td>
              <td>" .$usersRow["userPoints"]. "</td>
              <td>" ."<a href='deleteMember.php?id=".$usersRow['userId']."' onclick = \"return confirm('Are you sure you want to delete?')\">Delete</a>" . "</td>
              <td>" .$headAdminPromote. "</td>
              </tr>";
      }
    ?>
  </table>
</div>
</div>

```
</details>

<br>

Admins can also see other admins and can see their ID, username, and email by similar means.

<details>
	<summary>homeadmin.php (lines 96-137)</summary>
	
```php
<div class = "admins">
<h3 style = "text-align: center;">Administrators</h3>
<table class = "adminlog">
  <tr>
    <th>ID</th>
    <th>Username</th>
    <th>Email</th>
    <th>Remove User</th>
    <th>Demote User</th>
  </tr>
  <?php
    while($adminsRow = mysqli_fetch_array($adminsQuery)){
    //HEAD ADMIN FUNCTIONS ONLY SHOWN FOR HEAD ADMIN
    
    $headAdminDelete="";
    $headAdminDemote="";
    
    if($userRow["userName"] == "ipawds")
    {
      $headAdminDelete="<a href='deleteMember.php?id=".$adminsRow['userId']."' onclick = \"return confirm('Are you sure you want to delete?')\">Delete</a>";
      $headAdminDemote="<a href='demoteMember.php?id=".$adminsRow['userId']."'onclick = \"return confirm('Are you sure you want to demote?')\">Demote</a>";
    }
    else
    {
      $headAdminDelete="-";
      $headAdminDemote="-";
    }
      
        echo
            "<tr>
            <td>" .$adminsRow["userId"]. "</td>
            <td>" .$adminsRow["userName"]. "</td>
            <td>" .$adminsRow["userEmail"]. "</td>
            <td>".$headAdminDelete."</td>
            <td>".$headAdminDemote."</td>
            </tr>";
    }
  ?>
</table>
</div>
```
</details>

<br>

In order to see the correct information that corresponds to each type of user, the database is queried.

<details>
	<summary>homeadmin.php (lines 1-28)</summary>

```php
<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
  	
	// if session is not set this will redirect to login page
	if( !isset($_SESSION['user']) ) {
		header("Location: index.php");
		exit;
	}
	
	// select loggedin users detail
	$res=mysqli_query($conn, "SELECT * FROM users WHERE userId=".$_SESSION['user']);
	$userRow=mysqli_fetch_array($res);
	$userAdmin = $userRow['userAdmin'];
	
	if($userAdmin != 1){
	
	  header("Location: home.php");
	  exit;
	}
	
	
	
	$usersQuery = mysqli_query($conn, "SELECT userId, userName, userEmail, userPoints FROM users WHERE userAdmin = 0");
	$adminsQuery = mysqli_query($conn, "SELECT userId, userName, userEmail FROM users WHERE (userAdmin = 1) AND userID !=".$_SESSION['user']);
	//$usersRow=mysqli_fetch_array($usersQuery);
?>
```
</details>

<br>

#### Uploading Text Files  

Admins have the privelage of uploading a text file containing words that the game can choose from. The file should be newline-separated (`\n`) when uploaded. 

The following code renders the upload feature for the admin page. [[see source](https://github.com/achou11/swEng-project0/blob/master/homeadmin.php)]

```php
<div class = "upload-image">
	<form action="upload.php" method="post" enctype="multipart/form-data">
		  Select file to upload (only JPG, JPEG, PNG & GIF, and txt files are allowed):
		  <input type="file" name="fileToUpload" id="fileToUpload">
		  <input type="submit" value="Upload File" name="submit">
	</form>
</div>
```  
<br>

After the `.txt	` file is uploaded, it is stored into a specific directory that the game references when randomly selecting a word. [[see source](https://github.com/achou11/swEng-project0/blob/master/upload.php)]

<details>
	<summary>upload.php</summary>
	
```php

<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" && $imageFileType != "txt") {
    echo "Sorry, only JPG, JPEG, PNG & GIF, and txt files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html>
<body>
<form action="homeadmin.php">
    <input type="submit" value="Back" />
</form>
</body>
</html>

```
</details>

<br>

## In-Game: Users and Guests


#### Game Interface

Game interface is implemented in [index.html](https://github.com/achou11/swEng-project0/blob/master/index.html). At the beginning of and during game play, it includes the following components:

* Text input area for user to enter guess. (lines 30-37)

```html
<div id="guess-input">
    Enter guess:

    <input id="user-guess" name="guess" type="text" value="" onkeydown="enterKeyChange()" autofocus>

    <input id="submit-btn" type="submit" onclick="enterGuess()">

</div>
```

* A list of the letters already guessed by the player. (line 26)

```html
<p>Already guessed letters: <span id="already-guessed"></span></p>
```

* Display of word to be guessed, where hidden letters are represented with an underscore character. (line 41)

```html
<p id="show-word"></p>
```

* Display of remaining lives for player. (line 28)

```html
<p id="lives">Number of lives: <span id="num-lives"></span></p>
```

* Area displaying hangman graphics. (lines 20-22)
    
```html
<p class="image-background">
  <img src="uploads/hangman1.png" id="scene" alt="hangman image"/>
</p>
```

* A button to play again or reset the game. (line 43)

```html
<div id="reset">
	<button id="reset-btn" onclick="window.location.reload()">New Game</button>
</div>
```

* For registered users, display of score. (line 18)

```html
<p id="show-score">0</p>
```

<br>

#### Default Graphics

When player begins game, the initial hangman graphic is displayed. As the player incorrectly guesses letters, the hangman graphic is updated to reflect a loss in number of lives. 

This functionality is implemented in [app.js](https://github.com/achou11/swEng-project0/blob/master/app.js). It is specifically located within the `enterGuess` function, when the user's guess is incorrect. The script references the `.png` files located in the `uploads/` directory of the repository.


<details>
	<summary>app.js (lines 151-163)</summary>

```javascript
else if (targetWordList.includes(userGuess) === false) {
    // decrease number of lives by 1
    lives--;
    livesTag.innerHTML = lives;

    // Update the canvas
    // When sceneNumInt exceeds 10, the player has lost
    sceneNumInt += 1;
    sceneNumStr = 'hangman' + sceneNumInt;
    newSource = "uploads/" + sceneNumStr + ".png";
    document.getElementById('scene').src = newSource;

}
```
</details>

<br>

#### Guessing a Letter

The user inputs their guess using their keyboard, and either presses the submit button or the `Enter` key to guess the letter of their choice. The user can clear their guess using the `esc` key or by manually clearing it using the backspace key.

<details>
	<summary>app.js (lines 68-79)</summary>
	
```javascript
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

```
</details>

<br>


#### Playing the Game

The game interaction and page manipulation is implemented in [app.js](https://github.com/achou11/swEng-project0/blob/master/app.js). The user guesses letters from the alphabet in order to fully guess the word correctly. If the user guesses a letter correctly, the letter will be displayed in the holding place for the word that's being guessed.

<details>
	<summary>app.js (lines 134-151)</summary>
	
```javascript
// Create array containing indices of where guess occurs in letter if it's correct
var indexArray = [];
if (targetWordList.includes(userGuess)) {

    targetWordList.forEach(function(element, index) {
        if (element === userGuess) {
            indexArray.push(index);
        }
    });

    // Replace all occurrences of guessed letter into displayed word and 		redisplay to user
    indexArray.forEach(function(idx) {
        blankWord.splice(idx, 1, targetWordList[idx]);
	});


    showWord.innerHTML = blankWord.join(' ');
 } 
```
</details>

<br>

If the user incorrectly guesses a letter, their lives decrease by 1, and the hangman image will have another body part added to it.


<details>
	<summary>app.js (lines 151-163)</summary>

```javascript
else if (targetWordList.includes(userGuess) === false) {
    // decrease number of lives by 1
    lives--;
    livesTag.innerHTML = lives;

    // Update the canvas
    // When sceneNumInt exceeds 10, the player has lost
    sceneNumInt += 1;
    sceneNumStr = 'hangman' + sceneNumInt;
    newSource = "uploads/" + sceneNumStr + ".png";
    document.getElementById('scene').src = newSource;

}
```
</details>

<br>

After guessing any letter, the list of the letters guessed by the player is updated.

```javascript
alreadyGuessedArray.push(userGuess);
alreadyGuessed.innerHTML = alreadyGuessedArray.join(' ');
```

<br>


#### Winning the Game

If all of the letters in the word are correctly guessed before losing all 10 lives, the user wins the game. This is implemented in `app.js`, lines 165-170.

```javascript
// User correctly guesses all letters in word
if (blankWord.join('') === targetWord) {
    document.getElementById('win-lose').innerHTML = 'Congrats. You won!';
    var newScoreWin = changeScore(true);
    updateUserScore(newScoreWin);
}
```

<br>


#### Losing the Game
If the user runs out of lives before guessing all of the letters in the word, the user loses. This is implemented in `app.js`, lines 172-177.

```javascript
// User runs out of lives
if (lives == 0) {
    document.getElementById('win-lose').innerHTML = 'Game over. The correct answer was ' + targetWord + '.<br/>But hey - at least you got away :)';
    var newScoreLose = changeScore(false);
    updateUserScore(newScoreLose);
}
```

<br>

#### Record Scores

Registered users have a score that is associated to their account. This score changes depending on if the user wins or loses.

If the user wins, the number of points added to their score is the multiplicative product of the number of lives remaining and the length of the word being guessed. If the user loses, their score doesn't change. This portion is implemented in `app.js`, lines 91-99.

```javascript
// Add to score if user wins
// Don't change score if user loses

function changeScore(status) {
    if (status) {
        var scoreValue = lives * targetWord.length;
    } else {
        var scoreValue = 0;
    }
```

<br>

After changing the user's score, the database is updated with the new score for the user. This is implemented in `home.php` and `updateScore.php`.

<details>
	<summary>home.php (lines 35-45)</summary>

```php
<script>
  function updateUserScore(value) {
    var request = new XMLHttpRequest();
    var user_id = "<?php echo $userRow['userId']; ?>";
    request.open("POST", "updateScore.php?q="+value+"&id="+user_id);
    console.log("Request sent!");
    request.send();
}
</script>
```
</details>

<br>

<details>
	<summary>updateScore.php</summary>

```php
<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
  // sql to promote a record
  $value = (int)$_GET['q'];
  $sql = "UPDATE users SET userPoints = userPoints + '".$value."' WHERE userId='".$_GET['id']."'";
  if ($conn->query($sql) === TRUE) {
     header("Location: homeadmin.php");
  } else {
      echo "Error promoting record: " . $conn->error;
  }
  $conn->close();
?>
```
</details>

<br>


#### The "New Game" Button

The user presses the "New Game" button under two circumstances: 
 
* The user has won or lost the game, and wishes to play again.
* The user wishes to start a new game during their current game.

This is implemented in `index.html`, line 43.

```html
<div id="reset">
	<button id="reset-btn" onclick="window.location.reload()">New Game</button>
</div>
```

<br>


#### Leaderboard

The leaderboard displays the registered users with the top 10 scores while the game is being played. This is implemented in `home.php`. [[see source](https://github.com/achou11/swEng-project0/blob/master/home.php)] 

<details>
	<summary>home.php (lines 104-130)</summary>

```php
<div class = "leaderboard">
	<div style = "text-align: center; font-size: 30px;">LEADERBOARD</div>

	<table class = "leaders">
	  <tr>
	    <th>Rank</th>
	    <th>Username</th>
	    <th>Score</th>
	  </tr>
	<?php
	  $rank = 1;
	  while($leadersRow = mysqli_fetch_array($leadersQuery)){
	        echo
	            "<tr><td>"
	            .$rank.
	            "</td><td>"
	            .ucfirst(strtolower($leadersRow['userName'])).
	            "</td><td>"
	            .$leadersRow["userPoints"].
	            "</td></tr>";
	        $rank++;
	  }
	?>
	  
	</table>

</div>
```

<br>

<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
	// if session is not set this will redirect to login page
	if( !isset($_SESSION['user']) ) {
		header("Location: index.php");
		exit;
	}
	if($_SESSION['user'] == 10000){
	
	  header("Location: homeadmin.php");
	  exit;
	}
	// select loggedin users detail
	$res=mysqli_query($conn, "SELECT * FROM users WHERE userId=".$_SESSION['user']);
	$userRow=mysqli_fetch_array($res);
	
	$leadersQuery = mysqli_query($conn, "SELECT userFirstName, userLastName, userPoints FROM users WHERE userId NOT IN (SELECT userId FROM users WHERE userId = 10000) ORDER BY userPoints DESC LIMIT 10");
//	$leadersRow=mysqli_fetch_array($leadersQuery);
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome <?php echo $userRow['userEmail']; ?>!</title>
<link rel="stylesheet" href="css/master.css" type="text/css"  />
</head>

<body>
  <div class = "menu">
    
    <div class = "logout-block">
        <div class = "logout-text"><a href="logout.php?logout">Sign Out</a></div>
    </div>
    
    <div class = "user-block">
      <div class = "user-text">Welcome <?php echo $userRow['userFirstName']; ?>!</div>
    </div>
      



  </div>
  
  <div class = "game">
    <p class = "image-background">
    <img src = "uploads/hangman1.png" id = "scene" style="width:300px;"/>
    </p>

    <div id="reset"><button id="reset-btn" onclick="window.location.reload()">New Game</button></div>

    <br>

    <div id="guess-input">
        Enter guess:

        <input id="user-guess" name="guess" type="text" value="" onkeydown="enterKeyChange()" autofocus>

        <input id="submit-btn" type="submit" onclick="enterGuess()">
    </div>

    <p id="show-word"></p>

    <p>Already guessed letters: <span id="already-guessed"></span></p>
    <p id="lives">Number of lives: <span id="num-lives"></span></p>

    <h3 id="win-lose"></h3>


    <script src="app.js"></script>
  </div>
  
  <div class = "leaderboard">
    <div style = "text-align: center; font-size: 30px;">LEADERBOARD</div>

    <table class = "leaders">
      <tr>
        <th>Rank</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Score</th>
      </tr>
    <?php
      $rank = 1;
      while($leadersRow = mysqli_fetch_array($leadersQuery)){
            echo
                "<tr><td>"
                .$rank.
                "</td><td>"
                .$leadersRow["userFirstName"].
                "</td><td>"
                .$leadersRow["userLastName"].
                "</td><td>"
                .$leadersRow["userPoints"].
                "</td></tr>";
            $rank++;
      }
    ?>
      
    </table>

  </div>
</body>
</html>



<?php ob_end_flush(); ?>
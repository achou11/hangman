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
	
	if($userAdmin == 1){
	
	  header("Location: homeadmin.php");
	  exit;
	}
		
	$leadersQuery = mysqli_query($conn, "SELECT userId, userName, userPoints FROM users WHERE userAdmin NOT IN (SELECT userAdmin FROM users WHERE userAdmin = 1) ORDER BY userPoints DESC LIMIT 10");
//	$leadersRow=mysqli_fetch_array($leadersQuery);
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Welcome <?php echo $userRow['userEmail']; ?>!</title>
<link rel="stylesheet" href="css/master.css" type="text/css"  />

</head>


<body>
  <div class = "menu">
    
    <div class = "logout-block">
        <div class = "logout-text"><a href="logout.php?logout">Sign Out</a></div>
    </div>
    
    <div class = "user-block">
      <div class = "user-text">Welcome <?php echo ucfirst(strtolower($userRow['userName'])); ?>!</div>
    </div>
      



  </div>

  <div class = "game">
    <p class="image-background">
    <img src="uploads/hangman1.png" id="scene" style="width:300px; height:150;"/>
    </p>

    <br>

    <p>Already guessed letters: <span id="already-guessed"></span></p>

    <p id="lives">Number of lives: <span id="num-lives"></span></p>

    <div id="guess-input">
        Enter guess:

        <input id="user-guess" name="guess" type="text" value="" onkeydown="enterKeyChange()" autofocus>

        <input id="submit-btn" type="submit" onclick="enterGuess()">

    </div>

    <p id="input-error"></p>

    <p id="show-word"></p>

    <h3 id="win-lose"></h3>
    
    <div id="reset"><button id="reset-btn" onclick="window.location.reload()">New Game</button></div>

    <script src="app.js"></script>
  </div>
  
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
</body>
</html>



<?php ob_end_flush(); ?>
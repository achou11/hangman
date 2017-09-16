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
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="css/style.css" type="text/css" />
</head>

<body>
  <div class = "menu">
    <div class = "logout-block"><a href="logout.php?logout">Sign Out</a></div>
    <div class = "user-block"><?php echo $userRow['userEmail']; ?></div>

  </div>
  
  <div class = "game">
    
    <div id="guess-input">
        Enter guess:

        <input id="user-guess" name="guess" type="text" value="" onkeydown="enterKeyChange()" autofocus>
  
        <input id="submit-btn" type="submit" onclick="enterGuess()">
    </div>

    <div id="reset-btn"><button id="reset-btn" onclick="window.location.reload()">New Game</button></div>

    <p id="show-word"></p>

    <script src="app.js"></script>
  </div>
  
  <div class = "leaderboard">
    <table>
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
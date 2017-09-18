<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
	// if session is not set this will redirect to login page
	if( !isset($_SESSION['user']) ) {
		header("Location: index.php");
		exit;
	}
	if($_SESSION['user'] != 10000){
	
	  header("Location: home.php");
	  exit;
	}
	// select loggedin users detail
	$res=mysqli_query($conn, "SELECT * FROM users WHERE userId=".$_SESSION['user']);
	$userRow=mysqli_fetch_array($res);
	
	
	$usersQuery = mysqli_query($conn, "SELECT userId, userFirstName, userLastName, userEmail FROM users WHERE userId NOT IN (SELECT userId FROM users WHERE userId = 10000)");
	//$usersRow=mysqli_fetch_array($usersQuery);
?>




<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome Administrator!</title>
<link rel="stylesheet" href="css/master.css" type="text/css"  />
</head>

<body>
  <div class = "menu">

    <div class = "user-block">
      <div class = "user-text">
        Welcome <?php echo $userRow['userFirstName']; ?>!
      </div>
      <a href="logout.php?logout">
        <div class = "logout-block">
          <div class = "logout-text">Sign Out</div>
        </div>
      </a>
    </div>

  </div>
  <div class = "users">
    <?php
      while($usersRow = mysqli_fetch_array($usersQuery)){
            echo
                "<div>" .
                $usersRow["userId"]. ": " .
                $usersRow["userFirstName"] . " " .
                $usersRow["userLastName"]. " " .
                $usersRow["userEmail"]. " " .
                "<span><a href='deleteMember.php?id=".$usersRow['userId']."'>Delete</a></span>" .
                "<br></div>";
      }
    ?>
    

  
  </div>
  <div class = "upload-image">
    <form action="upload.php" method="post" enctype="multipart/form-data">
      Select file to upload (only JPG, JPEG, PNG & GIF, and txt files are allowed):
      <input type="file" name="fileToUpload" id="fileToUpload">
      <input type="submit" value="Upload File" name="submit">
    </form>
  </div>

</body>
</html>



<?php ob_end_flush(); ?>
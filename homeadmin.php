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




<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>Administrator</title>
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
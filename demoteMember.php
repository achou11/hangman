<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
  // sql to promote a record
  $sql = "UPDATE users SET userAdmin = 0 WHERE userId='".$_GET['id']."'";
  if ($conn->query($sql) === TRUE) {
     header("Location: homeadmin.php");
  } else {
      echo "Error promoting record: " . $conn->error;
  }

  $conn->close();

?>
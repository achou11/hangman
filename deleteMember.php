<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
  // sql to delete a record
  $sql = "DELETE FROM users WHERE userId='".$_GET['id']."'";
  if ($conn->query($sql) === TRUE) {
     header("Location: homeadmin.php");
  } else {
      echo "Error deleting record: " . $conn->error;
  }

  $conn->close();

?>
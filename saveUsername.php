<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
  // sql to promote a record
  $value = $_GET['q'];
  $sql = "UPDATE users SET userName = '".$value."' WHERE userId='".$_GET['id']."'";
  if ($conn->query($sql) === TRUE) {
     header("Location: homeadmin.php");
  } else {
      echo "Error promoting record: " . $conn->error;
  }

  $conn->close();

?>
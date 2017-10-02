<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
  // sql to promote a record
  $value = $_GET['q'];
  $password = hash('sha256', $value);
  $sql = "UPDATE users SET userPass = '".$password."' WHERE userId='".$_GET['id']."'";
  if ($conn->query($sql) === TRUE) {
     header("Refresh:0");
  } else {
      echo "Error promoting record: " . $conn->error;
  }

  $conn->close();

?>
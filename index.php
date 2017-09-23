<?php

	ob_start();
	session_start();
	require_once 'dbconnect.php';


	// it will never let you open index(login) page if session is set
	if ( isset($_SESSION['user'])!="" ) {
		header("Location: home.php");
		exit;
	}
	
	$error = false;
	
	
	
//###########################################################
//#################### Sign Up ERRORS #######################
//###########################################################

	$userError = "Username";
	$emailError = "Email";
	$passError = "Password";
	$passError2 = "Re-enter Password";

	
	if ( isset($_POST['btn-signup']) ) {
		
		// clean user inputs to prevent sql injections
		
		
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
//###########################################################
//#################### LOGIN ERRORS #########################
//###########################################################
	$logUserError = "Username or Email";
	$logPassError = "Password";
	$logError = false;
	$adminLogin = false;
	if( isset($_POST['btn-login']) ) {
		
		// prevent sql injections/ clear user invalid inputs
		$logUser = trim($_POST['logUser']);
		$logUser = strip_tags($logUser);
		$logUser = htmlspecialchars($logUser);
		
		$logPass = trim($_POST['logPass']);
		$logPass = strip_tags($logPass);
		$logPass = htmlspecialchars($logPass);
		// prevent sql injections / clear user invalid inputs
		
		if(empty($logUser)){
			$logError = true;
			$logUserError = "Please enter your credentials.";
		}
		/*
		else if ( !filter_var($logUser,FILTER_VALIDATE_EMAIL) ) {
			$logError = true;
			$logUserError = "Please enter valid credentials.";
		}
		*/
		if(empty($logPass)){
			$logError = true;
			$logPassError = "Please enter your password.";
		}
		
		// if there's no error, continue to login
		if (!$logError) {
			
			$password = hash('sha256', $logPass); // password hashing using SHA256
		  
			$res=mysqli_query($conn, "SELECT userId, userName, userPass FROM users WHERE (userName='$logUser') OR (userEmail='$logUser')");
			$row=mysqli_fetch_array($res);
			$count = mysqli_num_rows($res); // if uname/pass correct it returns must be 1 row
			

			if( $count == 1 && $row['userPass']==$password ) {
				$_SESSION['user'] = $row['userId'];
				header("Location: home.php");
			} else {
			  unset($logUser);
			  unset($logPass);
				$logErrMSG = "Incorrect Credentials, Please try again...";
			}
			
		}
		
	}

?>


<!DOCTYPE html>


<head>

<title>Hangman</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link href="css/form.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/master.css" rel="stylesheet" type="text/css" media="all" />


</head>

<html>
<script>
function showForm(a)
{
    if(a==1)
	{
//		document.getElementById("signupForm").style.display="none";
//		document.getElementById("loginForm").style.display="block";
		document.getElementById("signupLink").style.textDecoration = "none";
		document.getElementById("loginLink").style.textDecoration = "underline";
//		document.getElementById("navBar").className = "Two";
	}
    else
	{
//		document.getElementById("signupForm").style.display="block";
//		document.getElementById("loginForm").style.display="none";
		document.getElementById("signupLink").style.textDecoration = "underline";
		document.getElementById("loginLink").style.textDecoration = "none";
//		document.getElementById("navBar").className = "One";
	}
	
}



setInterval(
function()
{
	if(window.location.href.indexOf("loginForm") > -1) {
		document.getElementById("loginForm").style.display="block";
		document.getElementById("signupForm").style.display="none";
		document.getElementById("signupLink").style.textDecoration = "none";
		document.getElementById("loginLink").style.textDecoration = "underline";
	}
	else{
		document.getElementById("signupForm").style.display="block";
		document.getElementById("loginForm").style.display="none";
		document.getElementById("signupLink").style.textDecoration = "underline";
		document.getElementById("loginLink").style.textDecoration = "none";
	}
}, 10);


</script>
<body  style = "background-image: url('uploads/background.png'); background-size:cover; background-position: left top; background-repeat: no-repeat;">


<div class = "signContainer">

  <!--
	<div class = "signImage">
		<img src="logos/Logo_Purple.png" alt="Logo" style="width:250px;height:120px;">
	</div>
	-->
	<div class = "signSloganContainer">
		Hangman
		<br>
		<div style = "font-size: 18px;">A sadistic word game.</div>
	</div>
	<div class="signNavContainer">
	  <ul>
		<li><a href = "index.php#signupForm" id = "signupLink">Sign up</a></li>
		<li><a href = "index.php#loginForm" id = "loginLink">Log in</a></li>
	<!--	<hr id = "navBar" class="One"> -->
		<!-- <hr /> -->
		
	  </ul>
	</div>
	<br>
	<div class = "signTextContainer">
	
		<!--####################### SIGN UP FORM   #######################-->
		
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
		
		<div id = "loginForm" style = "display: none;">
			<!--####################### LOG IN FORM   #######################-->
			<form name = "loginForm" method="post" action="index.php#loginForm" autocomplete="off">
				<input type="text" name="logUser" placeholder="<?php echo $logUserError; ?>" value="<?php echo $logUser ?>" maxlength="40" />
				<input type="password" name="logPass" placeholder="<?php echo $logPassError; ?>" maxlength="15" />
				<div style = "margin-top: 25px"></div>
				<?php
				if ( isset($logErrMSG) ) {
					
					?>
					<span class="text-danger""><?php echo $logErrMSG; ?></span>
	  
					<?php
				}
				?>
				<input type="submit" name="btn-login" id="btn-login" value="Log in" />
			</form>
		</div>
	</div>

</div>

<div class = "footer">Created by: Jianing (Colin) Xie, Andrew Chou, Hayden Cowart, Zhihao Li, Yifan Gao, Ronghao Zhang</div>
</body>
</html>



<?php ob_end_flush(); ?>
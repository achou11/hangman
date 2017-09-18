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

	$fnameError = "First name";
	$lnameError = "Last name";
	$emailError = "Email";
	$passError = "Password";
	$passError2 = "Re-enter Password";

	
	if ( isset($_POST['btn-signup']) ) {
		
		// clean user inputs to prevent sql injections
		
		
		$fname = trim($_POST['firstname']);
		$fname = strip_tags($fname);
		$fname = htmlspecialchars($fname);
		
		$lname = trim($_POST['lastname']);
		$lname = strip_tags($lname);
		$lname = htmlspecialchars($lname);
		
		
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

		if (empty($fname)) {
			$error = true;
			$fnameError = "Please enter your full first name.";
		} else if (strlen($fname) < 2) {
			$error = true;
			$fnameError = "First name must have at least 2 characters.";
		} else if (!preg_match("/^[a-zA-Z ]+$/",$fname)) {
			$error = true;
			$fnameError = "Name must contain alphabets and space.";
		}


		if (empty($lname)) {
			$error = true;
			$lnameError = "Please enter your full last name.";
		} else if (strlen($lname) < 2) {
			$error = true;
			$lnameError = "Last name must have at least 2 characters.";
		} else if (!preg_match("/^[a-zA-Z ]+$/",$lname)) {
			$error = true;
			$lnameError = "Name must contain alphabets and space.";
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
				$emailError = "Provided Email is already in use.";
			}
		}
		// password validation
		if (empty($pass)){
			$error = true;
			$passError = "Please enter password.";
		} else if(strlen($pass) < 6) {
			$error = true;
			$passError = "Password needs at least 6 characters.";
		}
		
		if ($pass != $pass2){
			$error = true;
			$passError2 = "Password does not match!";
		}
		// password encrypt using SHA256();
		$password = hash('sha256', $pass);
		
		// if there's no error, continue to signup
		if( !$error ) {
			$query = "INSERT INTO users(userFirstName,userLastName,userEmail,userPass) VALUES('$fname','$lname','$email','$password')";
			$res = mysqli_query($conn, $query);
				
			if ($res) {
				$errTyp = "success";
				$errMSG = "Successfully registered, you may login now";
				unset($fname);
				unset($lname);
				unset($email);
				unset($pass);
			} else {
				$errTyp = "danger";
				$errMSG = "Something went wrong, try again later...";
			}
				
		}
		
		
	}
//###########################################################
//#################### LOGIN ERRORS #########################
//###########################################################
	$logEmailError = "Email";
	$logPassError = "Password";
	$logError = false;
	$adminLogin = false;
	if( isset($_POST['btn-login']) ) {
		
		// prevent sql injections/ clear user invalid inputs
		$logEmail = trim($_POST['logEmail']);
		$logEmail = strip_tags($logEmail);
		$logEmail = htmlspecialchars($logEmail);
		
		$logPass = trim($_POST['logPass']);
		$logPass = strip_tags($logPass);
		$logPass = htmlspecialchars($logPass);
		// prevent sql injections / clear user invalid inputs
		
		if(empty($logEmail)){
			$logError = true;
			$logEmailError = "Please enter your email address.";
		} else if ( !filter_var($logEmail,FILTER_VALIDATE_EMAIL) ) {
			$logError = true;
			$logEmailError = "Please enter valid email address.";
		}
		
		if($logEmail == "admin@admin.com"){
		  $adminLogin = true;
		}
		else{
		  $adminLogin = false;
		}
		if(empty($logPass)){
			$logError = true;
			$logPassError = "Please enter your password.";
		}
		
		// if there's no error, continue to login
		if (!$logError) {
			
			$password = hash('sha256', $logPass); // password hashing using SHA256
		  
			$res=mysqli_query($conn, "SELECT userId, userFirstName, userPass FROM users WHERE userEmail='$logEmail'");
			$row=mysqli_fetch_array($res);
			$count = mysqli_num_rows($res); // if uname/pass correct it returns must be 1 row
			
			if($adminLogin == false){
  			if( $count == 1 && $row['userPass']==$password ) {
  				$_SESSION['user'] = $row['userId'];
  				header("Location: home.php");
  			} else {
  				$logErrMSG = "Incorrect Credentials, Please try again...";
  			}
			}
			else{
  			if( $count == 1 && $row['userPass']==$password ) {
  				$_SESSION['user'] = $row['userId'];
  				header("Location: homeadmin.php");
  			} else {
  				$logErrMSG = "Incorrect Credentials, Please try again...";
  			}
			}
				
		}
		
	}

?>


<!DOCTYPE html>


<head>

<title>Hangman</title>

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
<body>


<div class = "signContainer">

  <!--
	<div class = "signImage">
		<img src="logos/Logo_Purple.png" alt="Logo" style="width:250px;height:120px;">
	</div>
	-->
	<div class = "signSloganContainer">
		Hangman
		<br>
		<br>
		<div style = "font-size: 18px;">The world's first sadistic word game.</div>
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
				
				<input type="text" id="fname" name="firstname" placeholder="<?php echo $fnameError; ?>" maxlength="50" value="<?php echo $fname ?>" />  <!--<div class="glyphicon glyphicon-exclamation-sign"></div> -->
				<input type="text" id="lname" name="lastname" placeholder="<?php echo $lnameError; ?>" maxlength="50" value="<?php echo $lname ?>" />
				<input type="text" id="email" name="email" placeholder="<?php echo $emailError; ?>" maxlength="40"  value="<?php echo $email ?>"/>
				<input type="password" id="pword" name="pass" placeholder="<?php echo $passError; ?>" maxlength="35" />
				<input type="password" id="pword2" name="pass2" placeholder="<?php echo $passError2; ?>" maxlength="35" />
				<?php
				if ( isset($errMSG) ) {
					
					?>
					<br>
					<br>
					<span class="text-danger"></span> <?php echo $errMSG; ?>
					<?php
				}
				?>

				<input type="submit" name="btn-signup" value="Sign up">
		
			</form>
		</div>
		
		<div id = "loginForm" style = "display: none;">
			<!--####################### LOG IN FORM   #######################-->
			<form name = "loginForm" method="post" action="index.php#loginForm" autocomplete="off">
				<input type="text" name="logEmail" placeholder="<?php echo $logEmailError; ?>" value="<?php echo $logEmail ?>" maxlength="40" />
				<input type="password" name="logPass" placeholder="<?php echo $logPassError; ?>" maxlength="15" />
					<br>
					<br>
				<?php
				if ( isset($logErrMSG) ) {
					
					?>

					<span class="text-danger"><?php echo $logErrMSG; ?></span>
	  
					<?php
				}
				?>
				<input type="submit" name="btn-login" id="btn-login" value="Log in" />
			</form>
		</div>
	</div>
</div>

</body>

</html>



<?php ob_end_flush(); ?>
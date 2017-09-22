# **User Stories for First Release**

**NOTE**: Code blocks will have an arrow next to them to indicate that they are collapsable. Click on the arrow or any space along the same line to toggle the code blocks. Try out this example: 

<details>

<summary>**hello.js**</summary>

```javascript
var example = "Hello World!"
```

</details>

<br>

## Pre-Game  

 
#### User Registration  

  
User registration was implemented in `index.php`. Within this file, there is embedded html that serves as a template to render the user signup page. 


	
<details>
	<summary>
	[index.php](https://github.com/achou11/swEng-project0/blob/master/index.php) (lines 283-304):
	</summary>

```html
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
```  
</details>

The following `.php` code takes validates the user signup credentials.

<details>
	<summary>
	[index.php](https://github.com/achou11/swEng-project0/blob/master/index.php) (lines 22-136):  
	</summary>

```php
$userError = "Username";
$emailError = "Email";
$passError = "Password";
$passError2 = "Re-enter Password";
	
if ( isset($_POST['btn-signup']) ) {
	
	// clean user inputs to prevent sql injections
	`
	
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
```
</details>

<br>


## Post-Login: Admin  

#### Access to Users and Other Admins Information 

The admin page contains a list of registered users and relevant information about each user, such as their ID, username, email, and number of points. The admin can also remove users at their own discretion. [[see source](https://github.com/achou11/swEng-project0/blob/master/homeadmin.php)]

The following code is written in a `.php` file and uses an html template to render the users list.

<details>
	<summary>
	**`homeadmin.php (lines 55-94)`**:
	</summary>

```php
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

```
</details>

Admins can also see other admins and can see their ID, username, and email by similar means.

<details>
	<summary>
	**`homeadmin.php (lines 96-137)`**: 
	</summary>
	
```php
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
```
</details>

In order to see the correct information that corresponds to each type of user, the database is queried.

<details>
	<summary>
	**`homeadmin.php (lines 1-28)`**:
	</summary>

```php
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
```


</details>

<br>

#### Uploading Text Files  

Admins have the privelage of uploading a text file containing words that the game can choose from. The file should be `\n` separated when uploaded. 

The following code renders the upload feature for the admin page. [[see source](https://github.com/achou11/swEng-project0/blob/master/homeadmin.php)]

<details>
	<summary>
	**`homeadmin.php (lines 138-144)`**:
	</summary>

```php
<div class = "upload-image">
	<form action="upload.php" method="post" enctype="multipart/form-data">
		  Select file to upload (only JPG, JPEG, PNG & GIF, and txt files are allowed):
		  <input type="file" name="fileToUpload" id="fileToUpload">
		  <input type="submit" value="Upload File" name="submit">
	</form>
</div>
```  
</details>

After the `.txt	` file is uploaded, it is stored into a specific directory that the game references when randomly selecting a word. [[see source](https://github.com/achou11/swEng-project0/blob/master/upload.php)]

<details>
	<summary>
	**`upload.php`**:
	</summary>
	
```php

<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" && $imageFileType != "txt") {
    echo "Sorry, only JPG, JPEG, PNG & GIF, and txt files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html>
<body>
<form action="homeadmin.php">
    <input type="submit" value="Back" />
</form>
</body>
</html>

```
</details>

<br>

## In-Game: Users and Guests


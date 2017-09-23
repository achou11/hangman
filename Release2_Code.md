# **User Stories for Second Release**

**NOTE**: Some code blocks will have an arrow next to them to indicate that they are collapsible. We do this for blocks exceeding 10 lines. Click on the arrow or any space along the same line to toggle the code blocks. Try out this example: 

<details>
	<summary>hello.js</summary>

```javascript
var example = "Hello World!"
```
</details>

<br>


## Pre-Game


#### Guest Login

If player wants does not want to login or register as a user, there is an option to play as a guest. Guests do not have scores tied to them. This is implemented in `index.php`, line 303.

```php
<div class = "guest-sign"><a href = "home.html">Play as guest</a></div>
```

<br>


## Post-login Admin

#### Admin Privileges
There are two types of admins - head admin and normal admin. Head admin can promote and delete users, and delete and demote normal admins. Normal admins can only remove users. Here are different functions:

* As head admin, promoting users to normal admin

<details>
	<summary>homeadmin.php (lines 70-80)</summary>
	
```php
$headAdminPromote="";
  
if($userRow["userName"] == "ipawds")
{
  
$headAdminPromote="<a href='promoteMember.php?id=".$usersRow['userId']."'onclick = \"return confirm('Are you sure you want to promote?')\">Promote</a>";
}
else
{
$headAdminPromote="-";
  }
```
</details>

<details>
	<summary>promoteMember.php</summary>

```php
<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
  // sql to promote a record
  $sql = "UPDATE users SET userAdmin = 1 WHERE userId='".$_GET['id']."'";
  if ($conn->query($sql) === TRUE) {
     header("Location: homeadmin.php");
  } else {
      echo "Error promoting record: " . $conn->error;
  }
  $conn->close();
?>
```
</details>

<br>

* As head admin, delete and demote normal admins. The `deleteMember.php` is also used for normal admins deleting users.

<details>
	<summary>homeadmin.php (lines 110-123)</summary>

```php
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
```
</details>

<br>

<details>
	<summary>deleteMember.php</summary>
	
```php
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
```
</details>

<br>

<details>
	<summary>demoteMember.php</summary>
	
```php
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
```
</details>

<br>


#### Access Game as Admin
From the admin page, admins can also play the game via a link to `homeadmingame.php` [[see source](https://github.com/achou11/swEng-project0/blob/master/homeadmingame.php)]. This is implemented in `homeadmin.php`, lines 44-46. 

```php
<div class = "home-block">
  <div class = "home-text"><a href = "homeadmingame.php">Game Page</a></div>
</div>
```

<br>


#### Uploading the Logo

Admins can upload a `.png` file that is displayed in the page rendered by `index.php`. This is implemented in `homeadmin.php` (lines 138-144) and `upload.php` [[see source](https://github.com/achou11/swEng-project0/blob/master/upload.php)].

```php
<div class = "upload-image">
	<form action="upload.php" method="post" enctype="multipart/form-data">Select file to upload (only JPG, JPEG, PNG & GIF, and txt files are allowed):
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" value="Upload File" name="submit">
	</form>
</div>
```


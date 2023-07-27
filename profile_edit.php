<?php
	require_once "./link.php";
	
	session_start();
	
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location: login.php");
		exit;
	}else{
		$uid = $_SESSION["uid"];
		$username = $_SESSION["username"];
		$_SESSION["postaccess"] = false;
    }
	
	$result = mysqli_query($link, "SELECT * FROM users WHERE uid=$uid");
	$row = mysqli_fetch_assoc($result); 
	
	$dob = $gender = $password = $new_password = $confirm_password = "";
	$username_error = $dob_error = $gender_error = $password_error = $new_password_error = $confirm_password_error = "";
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$password = trim($_POST["password"]);
		if(password_verify($password, $row["password_hash"])){
			$username = trim($_POST["username"]);
			$dob = trim($_POST["dob"]);
			$gender = trim($_POST["gender"]);
			$new_password = trim($_POST["newpassword"]);
			$confirm_password = trim($_POST["confirmpassword"]);
			
			if($username == $row["username"]){
				$username_error = "";
			}else{
				$resultm = mysqli_query($link, "SELECT * FROM users WHERE username=$username;");
				if(mysqli_num_rows($resultm) > 0){
					$username_error = "This username is already exists.";
				}else{
					$username_error = "";
				}
			}
			
			if(!empty($new_password) || !empty($confirm_password)){
				if(strlen($new_password) < 6){
					$new_password_error = "Password must be atleast 6 characters.";
				}else{
					$new_password_error = "";
				}
				
				if(empty($password_error) && $new_password != $confirm_password){
					$confirm_password_error = "Password did not match";
				}else{
					$confirm_password_error = "";
				}
				
				if(empty($username_error) && empty($new_password_error) && empty($confirm_password_error)){
					$password_hash = password_hash($new_password,PASSWORD_DEFAULT);
					if(mysqli_query($link, "UPDATE users SET username='$username', password_hash='$password_hash', date_of_birth='$dob', gender='$gender' WHERE uid=$uid;")){
						echo "<script>alert('Information and password updated Success!'); window.location.href='./profile.php';</script>";
					}
				}
			}else{
				if(empty($username_error)){
					if(mysqli_query($link, "UPDATE users SET username='$username', date_of_birth='$dob', gender='$gender' WHERE uid=$uid;")){
						$_SESSION["username"] = $username;
						echo "<script>alert('Information updated Success!'); window.location.href='./profile.php';</script>";
					}
				}
			}
		}else{
			$password_error = "Please enter the correct password";
		}
	}
	
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$year = date("Y")-16;
	$month = date("m");
	$day = date("d");
	$maxDateTime = date("$year-$month-$day");
?>

<html lang="en">
	<head>
		<title>User profile</title>
		
		<link rel="stylesheet" type="text/css" href="./css/navigation.css">
		<link rel="stylesheet" type="text/css" href="./css/style.css">
	</head>
	<body class="body2">
		<ul>
			<li><a href="./index.php">X-Voter</a></li>
			<li class="dropdown"><a class="hoverclass" href="#">As Organizer</a>
				<div class="dropdown-content">
					<a href="./createVote.php">Create vote</a>
					<a href="./userCreatedVote.php">My Organized Vote</a>
				</div>	
			</li>
			<li class="dropdown"><a class="hoverclass" href="#">As Voter</a>
				<div class="dropdown-content">
					<a href="./asvoter.php">Vote List</a>
					<a href="./userVotedHistory.php">My Voted History</a>
				</div>	
			</li>

			<li class="dropdown" style="float:right; margin-right:50px; width:5%;"><a class="checkedclass" href="./profile.php"><?php echo $username; ?></a>
				<div class="dropdown-content" style="float:left">
					<a href="./logout.php">Sign out</a>
				</div>
			</li>
		</ul>

		<div class="titlebox">
			<a href="./profile.php"><img src="./img/icon-back.png" alt="Back to main page" class="icon"></a>
			<h1>User Profile</h1>
		</div>

		<fieldset id="profiledetails">
			<form class="voteform" method="POST" enctype="multipart/form-data">
				<p style="margin-left:15%">E-mail<br>
				<input type="email" name="email" disabled value="<?php echo $row['email']; ?>"></p>
				<p style="margin-left:15%">Username<br>
				<input style="width:80%;" type="text" name="username" required value="<?php echo $row['username']; ?>"><br><span>
					<?php if(isset($username_error)){echo $username_error;}?>
				</span></p>
				<p style="margin-left:15%">Date of Birth<br><input type="date" name="dob" required max="<?php echo $maxDateTime ; ?>" value="<?php echo $row['date_of_birth']; ?>"></p>
				<p style="margin-left:15%">Gender<br>
				<select style="width:80%;" name="gender" required><?php if($row['gender'] == 'M'){ ?>
					<option value='M' checked>Male<option value='F'>Female<?php }else if($row['gender'] == 'F'){ ?>
					<option value='F' checked>Female<option value='M'>Male<?php } ?>
				</select></p><hr>
				<p style="margin-left:15%">If you want to change your password<input type="password" name="newpassword" placeholder="Enter your new password" value=""><br><span>
					<?php if(isset($new_password_error)){echo $new_password_error; }?>
				</span></p>
				<p style="margin-left:15%"><input type="password" name="confirmpassword" placeholder="Confirm your new password" value=""><br><span>
					<?php if(isset($confirm_password_error)){echo $confirm_password_error;}?>
				</span></p><hr>
				<p style="margin-left:15%">Enter your password for update your information<br>
				<input type="password" name="password" required placeholder="Enter your password" value=""><br><span>
					<?php if(isset($password_error)){echo $password_error; }?></span></p>
				<p style="margin-left:15%"><input style="width:80%" type="submit" value="Update details" onclick="return Update();"></p>
			</form>
		</fieldset>
		<script>
			function Update(){
				con = confirm("Do you sure want to update your user detail?");
				return con;
			}
		</script>
	</body>
</html>
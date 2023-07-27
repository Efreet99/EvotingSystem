<?php
	session_start();
	
	require_once "../link.php";
	 
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location: ../login.php");
		exit;
	}

	if(!isset($_SESSION["admin"]) || $_SESSION["admin"] !== true){
		header("location: ../index.php");
		exit;
	}else{
		$aid = $_SESSION["aid"];
	}
	
	$result = mysqli_query($link, "SELECT * FROM admin WHERE aid=$aid");
	$row = mysqli_fetch_assoc($result);
	
	$adminname = $password = $confirm_password = $dob = $gender = "";
	$adminname_error = $password_error = $confirm_password_error = $dob_error = $gender_error = "";
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$adminname = trim($_POST["adminname"]);
		$password = trim($_POST["password"]);
		$confirm_password = trim($_POST["confirmpassword"]);
		$dob = trim($_POST["dob"]);
		$gender = trim($_POST["gender"]);
		
		setcookie("adminname", $adminname, time()+90);
		setcookie("password", $password, time()+90);
		setcookie("confirmpassword", $confirm_password, time()+90);
		setcookie("dob", $dob, time()+90);
		
		$resultun = mysqli_query($link,"SELECT uid FROM users WHERE username = '$adminname'");
		$resultan = mysqli_query($link,"SELECT uid FROM admin WHERE adminname = '$adminname'");
		if(mysqli_num_rows($resultun) > 0 || mysqli_num_rows($resultan) > 0){
			$adminname_error = "This username is already exists.";
		}elseif(empty($adminname)){
			$adminname_error = "Please fill in the username.";
		}else{
			$adminname_error = "";
		}
		
		if(strlen($password) < 6){
			$password_error = "Password must be atleast 6 characters.";
			unset($_COOKIE["password"]);
		}elseif(empty($password)){
			$password_error = "Please enter the password.";
		}else{
			$password_error = "";
		}
		
		if(empty($confirm_password)){
			$confirm_password_error = "Please enter the confirm password.";
		}elseif(empty($password_error) && $password != $confirm_password){
			$confirm_password_error = "Password did not match";
			unset($_COOKIE["confirmpassword"]);
		}else{
			$confirm_password_error = "";
		}
		
		if($dob == ''){
			$dob_error = "Please enter your age.";
		}else{
			$dob_error = "";
		}
		
		if($gender == ''){
			$gender_error = "Please choose a gender.";
		}else{
			$gender_error = "";
		}
		
		if(empty($adminname_error) && empty($password_error) && empty($confirm_password_error) && empty($dob_error) && empty($gender_error)){
			$password_hash = password_hash($password,PASSWORD_DEFAULT);
			if(mysqli_query($link, "UPDATE admin SET adminname='$adminname', password_hash='$password_hash', date_of_birth='$dob', gender='$gender',admin_status='active' WHERE aid=$aid;")){
				session_start();
				
				unset($_COOKIE["adminname"]);
				unset($_COOKIE["password"]);
				unset($_COOKIE["confirmpassword"]);
				unset($_COOKIE["dob"]);
				$_SESSION["adminname"] = $adminname;
				echo "<script>alert('Authorized Success!'); window.location.href='./admin_index.php';</script>";
				exit();
			}else{ ?>
				<script>alert("Oops! Something went wrong. Please try again later.");</script>
			<?php }
		}
	}
	
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$year = date("Y")-18;
	$month = date("m");
	$day = date("d");
	$maxDateTime = date("$year-$month-$day");
?>

<html>
	<head>
		<title>Authorized Admin</title>
		<link rel="stylesheet" href="../css/style.css">
		<link rel="stylesheet" type="text/css" href="../css/navigation.css">
		<link rel="icon" href="./avatar.png" type="image" sizes="16x16">
	</head>
	<body onload="FocusAdminname()">
		<ul class="no0">
			<li><a href="./admin_index.php">Home</a></li>
			<li style="float:right;"><a href="../logout.php">Sign out</a></li>
        </ul>

		<div class="titlebox">
			<h1>Authorized Account</h1>
		</div>
		
		<fieldset class="adminlist">
			<form class="registerform" style="margin-left:25%;" method="POST">
				<p><input style="width:70%;" type="email" id="adminemail" name="adminemail" disabled value="<?php echo $row["adminemail"]; ?>"></p>
				<p><input style="width:70%;" type="text" name="adminname" required placeholder="Admin name" value="<?php echo isset($_COOKIE['adminname']) ? $adminname:''; ?>"><br><span>
					<?php if(isset($adminname_error)){echo $adminname_error;}?>
				</span></p>
				<p><input style="width:70%;" type="password" name="password" required placeholder="Password" value="<?php echo isset($_COOKIE['password']) ? $password:''; ?>"><br><span>
					<?php if(isset($password_error)){echo $password_error;}?>
				</span></p>
				<p><input style="width:70%;" type="password" name="confirmpassword" required placeholder="Confirm password" value="<?php echo isset($_COOKIE['confirmpassword']) ? $confirm_password:''; ?>"><br><span>
					<?php if(isset($confirm_password_error)){echo $confirm_password_error;}?>
				</span></p>
				<p><input style="width:70%;" type="date" name="dob" required max="99" placeholder="Date of Birth" value="<?php echo isset($_COOKIE['dob']) ? $age:''; ?>"><br><span>
					<?php if(isset($dob_error)){echo $dob_error;}?>
				</span></p>
				<p><select style="width:70%;" name="gender" required>
					<option value='' checked>Gender
					<option value='M'>Male
					<option value='F'>Female
				</select><br><span>
					<?php if(isset($gender_error)){echo $gender_error;}?>
				</span></p>
				<p><input style="width:70%;" type="submit" value="Authorized"></p>
			</form>
			<script>
				function FocusAdminname(){
					document.getElementById("adminname").focus();
				}
			</script>
		</fieldset>
	</body>
</html>
<?php
	require_once "./link.php";
	
	session_start();
	
	$email = $_SESSION["email"];
	
	$username = $password = $confirm_password = $dob = $gender = "";
	$username_error = $password_error = $confirm_password_error = $dob_error = $gender_error = "";
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$username = trim($_POST["username"]);
		$password = trim($_POST["password"]);
		$confirm_password = trim($_POST["confirmpassword"]);
		$dob = trim($_POST["dob"]);
		$gender = trim($_POST["gender"]);
		
		setcookie("username", $username, time()+90);
		setcookie("password", $password, time()+90);
		setcookie("confirmpassword", $confirm_password, time()+90);
		setcookie("dob", $dob, time()+90);
		
		$resultun = mysqli_query($link,"SELECT uid FROM users WHERE username = '$username'");
		$resultan = mysqli_query($link,"SELECT uid FROM admin WHERE adminname = '$username'");
		if(mysqli_num_rows($resultun) > 0 || mysqli_num_rows($resultan) > 0){
			$username_error = "This username is already exists.";
		}elseif(empty($username)){
			$username_error = "Please fill in the username.";
		}else{
			$username_error = "";
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
			$dob_error = "Please enter your date of birth.";
		}else{
			$dob_error = "";
		}
		
		if($gender == ''){
			$gender_error = "Please choose a gender.";
		}else{
			$gender_error = "";
		}
		
		if(empty($username_error) && empty($password_error) && empty($confirm_password_error) && empty($dob_error) && empty($gender_error)){
			$password_hash = password_hash($password,PASSWORD_DEFAULT);
			if(mysqli_query($link, "INSERT INTO users (username, email, password_hash, date_of_birth, gender, user_status) VALUES ('$username', '$email', '$password_hash', '$dob', '$gender', 'active');") && mysqli_query($link, "DELETE FROM verify WHERE email='$email';")){
				unset($_COOKIE["username"]);
				unset($_COOKIE["password"]);
				unset($_COOKIE["confirmpassword"]);
				unset($_COOKIE["dob"]);
				echo "<script>alert('Register Success!'); window.location.href='./login.php';</script>";
				exit();
			}else{ ?>
				<script>alert("Oops! Something went wrong. Please try again later.");</script>
			<?php }
		}
	}
	
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$year = date("Y")-16;
	$month = date("m");
	$day = date("d");
	$maxDateTime = date("$year-$month-$day");
?>

<html>
	<head>
		<title>Register</title>

		<link rel="stylesheet" type="text/css" href="./css/LoginRegister.css">
		<link rel="stylesheet" type="text/css" href="./css/navigation.css">
		<link rel="icon" href="./avatar.png" type="image" sizes="16x16">
		<script>
			function FocusUsername(){
				document.getElementById("username").focus();
			}
		</script>
	</head>

	<body class="body1" onload="FocusUsername()">
		<ul>
			<li><a style="color:#5e5e5e" href="./index.php">X-Voter</a></li>
		</ul>


		<div class="registerbox1">
			<fieldset>
			<h2 class="registertitle">Register</h2>
				<h6>Please enter your information to complete the register.</h6>
			
				<form class="registerform" method="POST">
					<p><input type="email" name="email" disabled value="<?php echo $email; ?>"></p>
					<p><input type="text" id="username" name="username" required placeholder="Username" value="<?php echo isset($_COOKIE['username']) ? $username:''; ?>"><br><span>
						<?php if(isset($username_error)){echo $username_error;}?>
					</span></p>
					<p><input type="password" name="password" required placeholder="Password" value="<?php echo isset($_COOKIE['password']) ? $password:''; ?>"><br><span>
						<?php if(isset($password_error)){echo $password_error;}?>
					</span></p>
					<p><input type="password" name="confirmpassword" required placeholder="Confirm password" value="<?php echo isset($_COOKIE['confirmpassword']) ? $confirm_password:''; ?>"><br><span>
						<?php if(isset($confirm_password_error)){echo $confirm_password_error;}?>
					</span></p>
					<p><input type="date" name="dob" required placeholder="Date of Birth" max="<?php echo $maxDateTime ; ?>" value="<?php echo isset($_COOKIE['dob']) ? $dob:''; ?>"><br><span>
						<?php if(isset($dob_error)){echo $dob_error;}?>
					</span></p>
					<p><select name="gender" required>
						<option value='' checked>Gender
						<option value='M'>Male
						<option value='F'>Female
					</select><br><span>
						<?php if(isset($gender_error)){echo $gender_error;}?>
					</span></p>
					<p><input type="submit" value="Register"><input type="reset" value="Reset"></p>
				</form>
			</fieldset>
		</div>
	</body>
</html>
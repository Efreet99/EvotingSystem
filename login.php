<?php
	session_start();
	
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		header("location: index.php");
		exit;
	}

	require_once './link.php';
	
	$account = $password = "";
	$access_error = "";
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$account = trim($_POST["account"]);
		$password = trim($_POST["password"]);
		
		setcookie("account", $account, time()+30);
		setcookie("password", $password, time()+30);
		
		if(empty($access_error)){
			$resulta1 = mysqli_query($link, "SELECT * FROM admin WHERE adminname = '$account'");
			$resulta2 = mysqli_query($link, "SELECT * FROM admin WHERE adminemail = '$account'");
			$resultu1 = mysqli_query($link, "SELECT * FROM users WHERE username = '$account'");
			$resultu2 = mysqli_query($link, "SELECT * FROM users WHERE email = '$account'");
			
			if(mysqli_num_rows($resultu1) == 1 || mysqli_num_rows($resultu2) == 1){
				if(mysqli_num_rows($resultu1) == 1){
					$rowu = mysqli_fetch_assoc($resultu1);
					if(password_verify($password, $rowu["password_hash"])){
						if($rowu["user_status"] == "active"){
							session_start();
							$_SESSION["loggedin"] = true;
							$_SESSION["uid"] = $rowu["uid"];
							$_SESSION["username"] = $rowu["username"];
							unset($_COOKIE["account"]);
							unset($_COOKIE["password"]);
							echo "<script>alert('Login success!'); window.location.href='./index.php';</script>";
							exit();
						}else if($rowu["user_status"] == "blocked"){
							unset($_COOKIE["account"]);
							unset($_COOKIE["password"]);
							echo "<script>alert('Your account has been blocked, Please contact the administrator for the reason.'); window.location.href='./index.php';</script>";
						}else if($rowu["user_status"] == "permanent suspended"){
							unset($_COOKIE["account"]);
							unset($_COOKIE["password"]);
							echo "<script>alert('Your account has been permanent suspended, Please contact the administrator.'); window.location.href='./index.php';</script>";
						}
					}else{
						$access_error = "Password invalid";
						unset($_COOKIE["password"]);
					}
				}else if(mysqli_num_rows($resultu2) == 1){
					$rowu = mysqli_fetch_assoc($resultu2);
					if(password_verify($password, $rowu["password_hash"])){
						if($rowu["user_status"] == "active"){
							session_start();
							$_SESSION["loggedin"] = true;
							$_SESSION["uid"] = $rowu["uid"];
							$_SESSION["username"] = $rowu["username"];
							unset($_COOKIE["account"]);
							unset($_COOKIE["password"]);
							echo "<script>alert('Login success!'); window.location.href='./index.php';</script>";
							exit();
						}else if($rowu["user_status"] == "blocked"){
							unset($_COOKIE["account"]);
							unset($_COOKIE["password"]);
							echo "<script>alert('Your account has been blocked, Please contact the administrator for the reason.'); window.location.href='./index.php';</script>";
						}else if($rowu["user_status"] == "permanent suspended"){
							unset($_COOKIE["account"]);
							unset($_COOKIE["password"]);
							echo "<script>alert('Your account has been permanent suspended, Please contact the administrator.'); window.location.href='./index.php';</script>";
						}
					}else{
						$access_error = "Password invalid";
						unset($_COOKIE["password"]);
					}
				}else{
					$access_error = "User dosen't exists";
				}
			}else if(mysqli_num_rows($resulta1) == 1 || mysqli_num_rows($resulta2) == 1){
				if(mysqli_num_rows($resulta1) == 1){
					$rowa = mysqli_fetch_assoc($resulta1);
					if(password_verify($password, $rowa["password_hash"])){
						if($rowa["admin_status"] == "active" || $rowa["admin_status"] == "unauthorized"){
							session_start();
							$_SESSION["admin"] = true;
							$_SESSION["loggedin"] = true;
							$_SESSION["aid"] = $rowa["aid"];
							$_SESSION["adminname"] = $rowa["adminname"];
							unset($_COOKIE["account"]);
							unset($_COOKIE["password"]);
							echo "<script>alert('Login success!'); window.location.href='./index.php';</script>";
							exit();
						}else if($rowa["admin_status"] == "suspended"){
							unset($_COOKIE["account"]);
							unset($_COOKIE["password"]);
							echo "<script>alert('Your account has been suspended.'); window.location.href='./index.php';</script>";
						}else if($rowa["admin_status"] == "permanent suspended"){
							unset($_COOKIE["account"]);
							unset($_COOKIE["password"]);
							echo "<script>alert('Your account has been permanent suspended.'); window.location.href='./index.php';</script>";
						}
					}else{
						$access_error = "Password invalid";
						unset($_COOKIE["password"]);
					}
				}else if(mysqli_num_rows($resulta2) == 1){
					$rowa = mysqli_fetch_assoc($resulta2);
					if(password_verify($password, $rowa["password_hash"])){
						if($rowa["admin_status"] == "active" || $rowa["admin_status"] == "unauthorized"){
							session_start();
							$_SESSION["admin"] = true;
							$_SESSION["loggedin"] = true;
							$_SESSION["aid"] = $rowa["aid"];
							$_SESSION["adminname"] = $rowa["adminname"];
							unset($_COOKIE["account"]);
							unset($_COOKIE["password"]);
							echo "<script>alert('Login success!'); window.location.href='./index.php';</script>";
							exit();
						}else if($rowa["admin_status"] == "suspended"){
							unset($_COOKIE["account"]);
							unset($_COOKIE["password"]);
							echo "<script>alert('Your account has been suspended.'); window.location.href='./index.php';</script>";
						}else if($rowa["admin_status"] == "permanent suspended"){
							unset($_COOKIE["account"]);
							unset($_COOKIE["password"]);
							echo "<script>alert('Your account has been permanent suspended.'); window.location.href='./index.php';</script>";
						}
					}else{
						$access_error = "Password invalid";
						unset($_COOKIE["password"]);
					}
				}else{
					$access_error = "User dosen't exists";
				}
			}else{
				$access_error = "User dosen't exists";
			}
		}
	}
?>

<html>
	<head>
		<title>Sign in</title>

		<link rel="stylesheet" type="text/css" href="./css/LoginRegister.css">
		<link rel="stylesheet" type="text/css" href="./css/navigation.css">
		<link rel="icon" href="./avatar.png" type="image" sizes="16x16">
		<script>
			function FocusAccount(){
				document.getElementById("account").focus();
			}
		</script>
	</head>

	<body class="body1" onload="FocusAccount()">
	<ul>
			<li><a href="./index.php">X-Voter</a></li>
			<li><a href="./aboutus.html">About Us</a></li>
			<li><a href="./contactus.php">Contact Us</a></li>
			<li style="float:right;"><a class="checkedclass" href="./login.php">Sign In</a></li>
			<li style="float:right;"><a class="hoverclass" href="./emailverify.php">Register</a></li>
		</ul>

		<div class="registerbox1" style="margin-top:150px;">
			<fieldset>
				<h2 class="registertitle">Sign in</h2>
				<form class="registerform" method="POST">
					<p><input style="margin-top:10px;" type="text" name="account" id="account" required placeholder="Username/E-mail" value="<?php echo isset($_COOKIE['account']) ? $account : '';?>"></p>
					<p style="margin-bottom:0px"><input type="password" name="password" id="password" required placeholder="Password" value="<?php echo isset($_COOKIE['password']) ? $password : '';?>"><br><span style="color:red;"><?php if(isset($access_error)){echo $access_error;}?></span></p>
					<p style="text-align:right; margin-top:0px; font-size:12px"><a href="./emailverify.php?forgotpassword">Forgot your password?</a></p>
					<p><input type="submit" class="submit" value="Login"></p>
					<p>Don't have an account? <a href="./emailverify.php">Register Now.</a></p>
				</form>
			</fieldset>
		</div>
	</body>
</html>
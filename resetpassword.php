<?php
	require_once "./link.php";
	
	session_start();
	
	if(isset($_REQUEST["resetpassword"])){
		$uid = $_REQUEST["uid"];
		
		$password = $confirm_password = "";
		$password_error = $confirm_password_error = "";
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			$password = trim($_POST["password"]);
			$confirm_password = trim($_POST["confirmpassword"]);
			
			setcookie("password", $password, time()+90);
			setcookie("confirmpassword", $confirm_password, time()+90);
			
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
			
			if(empty($password_error) && empty($confirm_password_error)){
				$password_hash = password_hash($password,PASSWORD_DEFAULT);
				if(mysqli_query($link, "UPDATE users SET password_hash='$password_hash' WHERE uid=$uid;")){
					unset($_COOKIE["password"]);
					unset($_COOKIE["confirmpassword"]);
					echo "<script>alert('Your password has been reset.'); window.location.href='./login.php';</script>";
					exit();
				}
			}
		} ?>
<html>
	<head>
		<title>Reset your password</title>

		<link rel="stylesheet" type="text/css" href="./css/LoginRegister.css">
		<link rel="stylesheet" type="text/css" href="./css/navigation.css">
		<script>
			function FocusNewpassword(){
				document.getElementById("password").focus();
			}
		</script>
	</head>

	<body class="body1" onload="FocusNewpassword()">
		<ul>
			<li><a style="color:#5e5e5e" href="./index.php">X-Voter</a></li>
		</ul>
		
		<div class="registerbox1">
			<fieldset>
			<h2 class="registertitle">Resetpassword</h2>
			
				<form class="registerform" method="POST">
					<p><input type="password" name="password" required placeholder="Password" value="<?php echo isset($_COOKIE['password']) ? $password:''; ?>"><br><span>
						<?php if(isset($password_error)){echo $password_error;}?>
					</span></p>
					<p><input type="password" name="confirmpassword" required placeholder="Confirm password" value="<?php echo isset($_COOKIE['confirmpassword']) ? $confirm_password:''; ?>"><br><span>
						<?php if(isset($confirm_password_error)){echo $confirm_password_error;}?>
					</span></p>
					<p><input type="submit" value="Reset"></p>
				</form>
			</fieldset>
		</div>
	</body>
</html>
	<?php }
?>
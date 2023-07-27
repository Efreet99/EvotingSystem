<?php
	require_once "./link.php";
	
	session_start();
	
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		header("location: index.php");
		exit;
	}
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	
	require 'vendor/autoload.php';
	
	$email = "";
	$email_error = "";
	
	if(isset($_REQUEST["forgotpassword"])){
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			date_default_timezone_set("Asia/Kuala_Lumpur");
			$expired_datetime = date('Y-m-d\TH:i:s',time()+259200);
			$email = trim($_POST["email"]);
			$hash = md5(rand(0,1000));
			
			$resultun = mysqli_query($link,"SELECT uid FROM users WHERE email = '$email'");
			if(mysqli_num_rows($resultun) == 0){
				$email_error = "This email not register yet.";
			}elseif(empty($email)){
				$email_error = "Please fill in the email.";
			}else{
				$email_error = "";
			}
			
			if(empty($email_error)){
				
				$mail = new PHPMailer(true);
				
				$mail->isSMTP();
				$mail->Host       = 'smtp.gmail.com';
				$mail->SMTPAuth   = true;
				$mail->Username   = 'xvoter.operation@gmail.com';
				$mail->Password   = 'X-voter210202';
				$mail->Port       = 587;
				
				$mail->setFrom('xvoter.operation@gmail.com', 'X-Voter');
				$mail->addAddress($email);
				
				$body = "
				<b>Reset your password</b><br>
				You can click the following link continue to reset your password:<br>
				<br>
				https://xvoter.ap.ngrok.io/evotingSystem/verify.php?forgot&email=$email&hash=$hash
				<br><br>
				This email will be expired within 3 days.";
				
				$mail->isHTML(true); 
				$mail->Subject = 'Email Verification';
				$mail->Body    = $body;
				$mail->AltBody = strip_tags($body);
				
				if($mail->send()){
					$result = mysqli_query($link, "SELECT * FROM verify WHERE email='$email'");
					if(mysqli_num_rows($result) > 0){
						mysqli_query($link, "UPDATE verify SET hash='$hash', expired_date='$expired_datetime' WHERE email='$email'");
						echo "<script>alert('An email has been sent. Please check your mailbox. This may take several time.'); window.location.href='./emailverify.php?forgotpassword';</script>";
						exit();
					}else{
						mysqli_query($link, "INSERT INTO verify (email, hash, expired_date) VALUES ('$email', '$hash', '$expired_datetime')");
						echo "<script>alert('An email has been sent. Please check your mailbox. This may take several time.'); window.location.href='./emailverify.php?forgotpassword';</script>";
						exit();
					}
				}
			}
		}
?>

<html>
	<head>
		<title>Reset your password</title>
		<link rel="stylesheet" type="text/css" href="./css/LoginRegister.css">
		<link rel="stylesheet" type="text/css" href="./css/navigation.css">
		<link rel="icon" href="./avatar.png" type="image" sizes="16x16">
		<script>
			function FocusMail(){
				document.getElementById("email").focus();
			}
		</script>
	</head>
	<body class="body1" onload="FocusMail()">
		<ul>
			<li><a href="./index.php">X-Voter</a></li>
			<li><a href="./aboutus.html">About Us</a></li>
			<li><a href="./contactus.php">Contact Us</a></li>
			<li style="float:right;"><a class="checkedclass" href="./login.php">Sign In</a></li>
			<li style="float:right;"><a class="hoverclass" href="./emailverify.php">Register</a></li>
		</ul>

		<div class="registerbox1" style="margin-top:150px;">
			<fieldset>
				<h2 class="registertitle">Email verification</h2>
				<form class="registerform" method="POST">
					<p><input id="email" type="email" name="email" required placeholder="E-mail" value="<?php echo isset($_COOKIE['email']) ? $email:''; ?>"><br><span>
						<?php if(isset($email_error)){echo $email_error;}?>
					</span></p>
					<p><input type="submit" value="Send email"></p>
					<p>Remember your password? <a href="./login.php">Login Here.</a></p>
				</form>
			</fieldset>
		</div>
	</body>
</html>

<?php	
	}else{
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			date_default_timezone_set("Asia/Kuala_Lumpur");
			$expired_datetime = date('Y-m-d\TH:i:s',time()+259200);
			$email = trim($_POST["email"]);
			$hash = md5(rand(0,1000));
			
			$resultun = mysqli_query($link,"SELECT * FROM users WHERE email = '$email'");
			$resultan = mysqli_query($link,"SELECT * FROM admin WHERE adminemail = '$email'");
			if(mysqli_num_rows($resultun) > 0 || mysqli_num_rows($resultan) > 0){
				$email_error = "This email has been registered.";
			}elseif(empty($email)){
				$email_error = "Please fill in the email.";
			}else{
				$email_error = "";
			}
			
			if(empty($email_error)){
				
				$mail = new PHPMailer(true);
				
				$mail->isSMTP();
				$mail->Host       = 'smtp.gmail.com';
				$mail->SMTPAuth   = true;
				$mail->Username   = 'xvoter.operation@gmail.com';
				$mail->Password   = 'X-voter210202';
				$mail->Port       = 587;
				
				$mail->setFrom('xvoter.operation@gmail.com', 'X-Voter');
				$mail->addAddress($email);
				
				$body = "
				<b>Thanks for sign up!</b><br>
				You can click the following link continue to register your account:<br>
				<br>
				https://xvoter.ap.ngrok.io/evotingSystem/verify.php?verify&email=$email&hash=$hash
				<br><br>
				This email will be expired within 3 days.";
				
				$mail->isHTML(true); 
				$mail->Subject = 'Email Verification';
				$mail->Body    = $body;
				$mail->AltBody = strip_tags($body);
				
				if($mail->send()){
					$result = mysqli_query($link, "SELECT * FROM verify WHERE email='$email'");
					if(mysqli_num_rows($result) > 0){
						mysqli_query($link, "UPDATE verify SET hash='$hash', expired_date='$expired_datetime' WHERE email='$email'");
						echo "<script>alert('An email has been sent. Please check your mailbox. This may take several time.'); window.location.href='./emailverify.php';</script>";
						exit();
					}else{
						mysqli_query($link, "INSERT INTO verify (email, hash, expired_date) VALUES ('$email', '$hash', '$expired_datetime')");
						echo "<script>alert('An email has been sent. Please check your mailbox. This may take sevral time.'); window.location.href='./emailverify.php';</script>";
						exit();
					}
				}
			}
		}
?>

<html>
	<head>
		<title>Verify your email</title>
		<link rel="stylesheet" type="text/css" href="./css/LoginRegister.css">
		<link rel="stylesheet" type="text/css" href="./css/navigation.css">
		<script>
			function FocusMail(){
				document.getElementById("email").focus();
			}
		</script>
	</head>
	<body class="body1" onload="FocusMail()">
		<ul>
			<li><a href="./index.php">X-Voter</a></li>
			<li><a href="./aboutus.html">About Us</a></li>
			<li><a href="./contactus.php">Contact Us</a></li>
			<li style="float:right;"><a class="hoverclass" href="./login.php">Sign In</a></li>
			<li style="float:right;"><a class="checkedclass" href="./emailverify.php">Register</a></li>
		</ul>
		<div class="registerbox1" style="margin-top:150px;">
			<fieldset>
				<h2 class="registertitle">Register</h2>
				<h6>Enter your email, We will send you the verification link.</h6>
				<form class="registerform" method="POST">
					<p><input id="email" type="email" name="email" required placeholder="E-mail" value="<?php echo isset($_COOKIE['email']) ? $email:''; ?>"><br><span>
						<?php if(isset($email_error)){echo $email_error;}?>
					</span></p>
					<p><input type="submit" value="Send email"></p>
					<p>Already have an account? <a href="./login.php">Login Here.</a></p>
				</form>
			</fieldset>
		</div>
	</body>
</html>

	<?php } ?>
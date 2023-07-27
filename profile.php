<?php
	require_once "./link.php";
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	
	require 'vendor/autoload.php';
	
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
	$email = "";
	$email_error = "";
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$expired_datetime = date('Y-m-d\TH:i:s',time()+259200);
		$email = trim($_POST["email"]);
		$hash = md5(rand(0,1000));
		
		if($stmt = mysqli_prepare($link,"SELECT * FROM users WHERE email = ?")){
			$email_error = "";
			mysqli_stmt_bind_param($stmt,"s",$email);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			if(mysqli_stmt_num_rows($stmt) > 0){
				$email_error = "The email is already registered.";
			}
			mysqli_stmt_close($stmt);
		}else if(empty($email)){
			$email_error = "Please fill in the email.";
		}else{
			$email_error = "";
		}
		
		if(empty($email_error)){
			$password = trim($_POST["password"]);
			if(password_verify($password, $row["password_hash"])){
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
				<b>This is a verify mail to change email address.<br>
				If you don't have do this, please ignore this email.</b><br>
				You can click the following link continue to change your email address:<br>
				<br>
				https://xvoter.ap.ngrok.io/evotingSystem/verify.php?change&uid=$uid&email=$email&hash=$hash
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
						echo "<script>alert('An email has been sent. Please check your mailbox. This may take several time.'); window.location.href='./profile.php';</script>";
						exit();
					}else{
						mysqli_query($link, "INSERT INTO verify (email, hash, expired_date) VALUES ('$email', '$hash', '$expired_datetime')");
						echo "<script>alert('An email has been sent. Please check your mailbox. This may take sevral time.'); window.location.href='./profile.php';</script>";
						exit();
					}
				}
			}else{
				$password_error = "Please enter the correct password";
			}
		}
	}
?>

<html>
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
			<a href="./index.php"><img src="./img/icon-back.png" alt="Back to main page" class="icon"></a>
			<h1>User Profile</h1>
		</div>

		<fieldset class="voteform" id="changeto" >
			<form style="margin-left:10%">
				<p>E-mail<br>
				<input style="width:70%;" type="email" name="email" disabled value="<?php echo $row['email']; ?>"><input type="button" style="width:20%;" value="Change E-mail" onclick="change()"><br><span>
				<?php if(isset($email_error)){echo $email_error;}?> 
				<?php if(isset($password_error)){echo $password_error; }?>
				</span></p>
				<p>Username<br>
				<input style="width:90%;" type="text" name="username" disabled value="<?php echo $row['username']; ?>"></p>
				<p>Date of Birth<br>
				<input style="width:90%;" type="date" name="dob" disabled value="<?php echo $row['date_of_birth']; ?>"></p>
				<p>Gender<br>
				<select style="width:90%;" name="gender" disabled>
					<?php if($row['gender'] == "M"){ ?>
					<option value='M' checked>Male
					<option value='F'>Female
					<?php }else if($row['gender'] == 'F'){ ?>
					<option value='F' checked>Female
					<option value='M'>Male
					<?php } ?>
				</select></p>
			</form>
			<p><a href="./profile_edit.php"><input style="margin-left:10%; width:80%;" type="submit" value="Edit profile"></a></p>
		</fieldset>
		<script>
		function change(){
			document.getElementById("changeto").innerHTML='<form method="post" style="margin-left:10%"><p>E-mail<br><input style="width:70%;" type="email" name="email" required placeholder="Enter email that you want to change here"><a href="./profile.php"><input type="button" style="width:20%; float:right;" value="Back to profile"></a></p><p><br>Enter your password for update your information<input style="width:70%;" type="password" required name="password" placeholder="Enter your password" value=""><input type="submit" style="width:20%; float:right;" value="Send verify mail"></p></form>';
		}
		</script>
	</body>
</html>
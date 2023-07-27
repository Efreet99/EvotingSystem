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
		$adminname = $_SESSION["adminname"];
	}
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	
	require '../vendor/autoload.php';
	
	$adminemail = "";
	$adminemail_error = "";
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$adminemail = trim($_POST["adminemail"]);
		
		setcookie("adminemail", $adminemail, time()+90);
		
		$resultun = mysqli_query($link,"SELECT * FROM users WHERE email = '$adminemail'");
		$resultan = mysqli_query($link,"SELECT * FROM admin WHERE adminemail = '$adminemail'");
		if(mysqli_num_rows($resultun) > 0 || mysqli_num_rows($resultan) > 0){
			$adminemail_error = "This email has been registered.";
		}elseif(empty($adminemail)){
			$adminemail_error = "Please fill in the email.";
		}else{
			$adminemail_error = "";
		}
		
		function passwordGenerate($length){
			$cha = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$chalen = strlen($cha);
			$ran = "";
			for($i = 0; $i < $length; $i++){
				$ran .= $cha[rand(0, $chalen-1)];
			}
			return $ran;
		}
		
		if(empty($adminemail_error)){
			$password = trim(passwordGenerate(6));
			
			$mail = new PHPMailer(true);
				
			$mail->isSMTP();
			$mail->Host       = 'smtp.gmail.com';
			$mail->SMTPAuth   = true;
			$mail->Username   = 'xvoter.operation@gmail.com';
			$mail->Password   = 'X-voter210202';
			$mail->Port       = 587;
			
			$mail->setFrom('xvoter.operation@gmail.com', 'X-Voter');
			$mail->addAddress($adminemail);
			
			$body = "
			<b>Authorized</b><br>
			You are the admin of the X-Voter, please use the following detail to login and complete the authorization:<br>
			<br>
			Account: $adminemail <br>
			Password: $password
			<br><br>";
			
			$mail->isHTML(true); 
			$mail->Subject = 'Admin Authorized';
			$mail->Body    = $body;
			$mail->AltBody = strip_tags($body);
			
			if($mail->send()){
				$password_hash = password_hash($password,PASSWORD_DEFAULT);
				if(mysqli_query($link, "INSERT INTO admin (adminemail, password_hash, admin_status) VALUES ('$adminemail', '$password_hash', 'unauthorized');")){
					unset($_COOKIE["adminemail"]);
					echo "<script>alert('Authorized email has been sent.'); window.location.href='./list_admin.php';</script>";
					exit();
				}else{ ?>
					<script>alert("Oops! Something went wrong. Please try again later.");</script>
				<?php }
			}
		}
	}
?>

<html>
	<head>
		<title>Authorized Email</title>
		<link rel="stylesheet" href="../css/style.css">
		<link rel="stylesheet" type="text/css" href="../css/navigation.css">
		<link rel="icon" href="./avatar.png" type="image" sizes="16x16">
	</head>
	<body onload="FocusAdminemail()">
		<ul class="no0">
			<li><a href="./admin_index.php">Home</a></li>
			<li class="dropdown"><a class="checkedclass" href="#">Manage</a>
				<div class="dropdown-content">
					<a class="checkedclass" href="./list_admin.php">Admin</a>
					<a href="./list_user.php">User</a>
					<a href="./list_vote.php">Vote</a>
					<a href="./list_report.php">Report</a>
					<a href="./list_message.php">Message</a>
				</div>
			</li>
			<li class="dropdown" style="float:right; margin-right:50px; width:5%;"><a class="hoverclass" href="./detail_admin.php?admin_detail&caid=<?php echo $aid; ?>"><?php echo $adminname; ?></a>
				<div class="dropdown-content" style="float:left">
					<a href="../logout.php">Sign out</a>
				</div>
			</li>
        </ul>

		<div class="titlebox">
			<a href="./list_admin.php"><img src="../img/icon-back.png" alt="Back to main page" class="icon"></a>
			<h1>Send Authorized Email</h1>
		</div>
		
		<fieldset class="adminlist">
			<form class="registerform" style="margin-left:25%;" method="POST">
				<p><input style="width:70%;" type="email" id="adminemail" name="adminemail" required placeholder="Admin email" value="<?php echo isset($_COOKIE['adminemail']) ? $adminemail:''; ?>"><br><span>
					<?php if(isset($adminemail_error)){echo $adminemail_error;}?>
				</span></p>
				<p><input style="width:70%;" type="submit" value="Send Authorized" onclick="Add()"></p>
			</form>
			<script>
				function Add(){
					con = confirm("Do you sure want to authorized this email as a admin account?");
					return con;
				}
				function FocusAdminemail(){
					document.getElementById("adminemail").focus();
				}
			</script>
		</fieldset>
	</body>
</html>
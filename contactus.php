<?php 
	require_once "./link.php";
		
	session_start();

	$messageEmail = $messageContent = "";
	$messageEmail_error = $messageContent_error = "";

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$messageEmail = trim($_POST["messageEmail"]);
		$messageContent = trim($_POST["messageContent"]);

		if(empty($messageEmail)){
			$messageEmail_error = "Please fill in the email.";
		}else{
			$messageEmail_error = "";
		}

		if(empty($messageContent)){
			$messageContent_error = "Please fill in the message.";
		}else{
			$messageContent_error = "";
		}

		if(empty($messageEmail_error)  && empty($messageContent_error)){
			if(mysqli_query($link, "INSERT INTO message (message_email, message_content, message_status) VALUES ('$messageEmail', '$messageContent', 'pending')"))
			echo "<script>alert('The message has been sent successfully! We will contact you as soon as posible.'); window.location.href='./contactus.php';</script>";
		}else{ ?>
			<script>alert("Oops! Something went wrong. Please try again later.");</script>
		<?php }
	}
?>

<html>
	<head>
		<title>Contact Us</title>
		<link rel="stylesheet" type="text/css" href="./css/style.css">
		<link rel="stylesheet" type="text/css" href="./css/navigation.css">
		<link rel="icon" href="./avatar.png" type="image" sizes="16x16">
	</head>

	<body class="body2">
		<ul>
			<li><a href="./index.php">X-Voter</a></li>
			<li><a class="hoverclass" href="./aboutus.html">About Us</a></li>
			<li><a class="checkedclass" href="./contactus.php">Contact Us</a></li>
			<li style="float:right;"><a class="hoverclass" href="./login.php">Sign In</a></li>
			<li style="float:right;"><a class="hoverclass" href="./emailverify.php">Register</a></li>
		</ul>
			
		<div style="margin-top:130px; text-align:center;"></div>
		
		<fieldset >
			<div class="centerbox" style="margin-bottom:60px;">
				<form class="contactform" method="POST">
					<p><h2 style="margin-bottom:0px; color:#AC3B61">Contact With Us</h2><br>If You Have Any Question.</p>
					<p><input type="email" name="messageEmail" required placeholder="Enter Your Email"><span>
						<?php if(isset($messageEmail_error)){echo $messageEmail_error;}?>
					</span><p>
					<textarea rows="4" cols="75" name="messageContent" placeholder="What you want to say with us."></textarea>
					<br><span>
						<?php if(isset($messageContent_error)){echo $messageContent_error;}?>
					</span>
					<p><input style="background-color:#BAB2B5;" type="submit" name="btn-send" value="Send Message"></p>
				</form>
				
				<p style="padding-top:20px;"><h2 style="margin-bottom:0px;color:#AC3B61">You Also Can Direct Email to Us</h2><br><a href="mailto:xvoter.operation@gmail.com">xvoter.operation@gmail.com</a></p>
			</div>
		</fieldset>
		
	</body>
</html>


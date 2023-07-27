<?php
	require_once "./link.php";
	
	if(isset($_REQUEST["verify"])){
		$email = $_REQUEST['email'];
		$hash = $_REQUEST['hash'];
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$current_datetime = date('Y-m-d\TH:i:s',time());
		$result = mysqli_query($link, "SELECT * FROM verify WHERE email='$email'");
		$row = mysqli_fetch_assoc($result);
		
		if($row["expired_date"] <= $current_datetime){
			echo "<script>alert('Your verify mail already expired. Please resend your verify mail.'); window.location.href='./emailverify.php';</script>";
			exit();
		}else{
			if($hash == $row['hash']){
				session_start();
				$_SESSION["email"] = $email;
				echo "<script>alert('Verified success!'); window.location.href='./register.php';</script>";
				exit();
			}else{
				echo "<script>alert('Oops! Something went wrong. Please try again later.'); window.location.href='./emailverify.php';</script>";
				exit();
			}
		}
	}else if(isset($_REQUEST["forgot"])){
		$email = $_REQUEST['email'];
		$hash = $_REQUEST['hash'];
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$current_datetime = date('Y-m-d\TH:i:s',time());
		$result = mysqli_query($link, "SELECT * FROM verify WHERE email='$email'");
		$row = mysqli_fetch_assoc($result);
		
		if($row["expired_date"] <= $current_datetime){
			echo "<script>alert('Your verify mail already expired. Please resend your verify mail.'); window.location.href='./emailverify.php?forgotpassword';</script>";
			exit();
		}else{
			if($hash == $row['hash']){
				session_start();
				$result = mysqli_query($link, "SELECT * FROM users WHERE email='$email'");
				$row = mysqli_fetch_assoc($result);
				$uid = $row["uid"];
				mysqli_query($link, "DELETE FROM verify WHERE email='$email';");
				echo "<script>alert('Verified success! Please reset your password.'); window.location.href='./resetpassword.php?resetpassword&uid=$uid';</script>";
				exit();
			}else{
				echo "<script>alert('Oops! Something went wrong. Please try again later.'); window.location.href='./emailverify.php?forgotpassword';</script>";
				exit();
			}
		}
	}else if(isset($_REQUEST["change"])){
		$uid = $_REQUEST["uid"];
		$email = $_REQUEST["email"];
		$hash = $_REQUEST['hash'];
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$current_datetime = date('Y-m-d\TH:i:s',time());
		$resultu = mysqli_query($link, "SELECT * FROM users WHERE uid='$uid'");
		$rowu = mysqli_fetch_assoc($resultu);
		$resultv = mysqli_query($link, "SELECT * FROM verify WHERE email='$email'");
		$rowv = mysqli_fetch_assoc($resultv);
		
		if($rowv["expired_date"] <= $current_datetime){
			echo "<script>alert('Your verify mail already expired. Please resend your verify mail.'); window.location.href='./profile.php';</script>";
			exit();
		}else{
			if($hash == $rowv['hash']){
				mysqli_query($link, "UPDATE users SET email='$email' WHERE uid='$uid';");
				mysqli_query($link, "DELETE FROM verify WHERE email='$email';");
				echo "<script>alert('Verified success!'); window.location.href='./profile.php';</script>";
				exit();
			}else{
				echo "<script>alert('Oops! Something went wrong. Please try again later.'); window.location.href='./profile.php';</script>";
				exit();
			}
		}
	}else{
		echo "<script>alert('Oops! Something went wrong. Please try again later.'); window.location.href='./profile.php';</script>";
		exit();
	}
?>
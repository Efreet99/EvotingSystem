<?php
	session_start();
	if(session_destroy()){
		$message = "You are loggged out.";
		echo "<script>alert('$message'); window.location.href='./';</script>";
		exit;
	}
?>
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
		$result = mysqli_query($link, "SELECT * FROM admin WHERE aid=$aid");
		$row = mysqli_fetch_assoc($result);
		if($row["admin_status"] == "unauthorized"){
			header("location: ./authorized_admin.php?authorized&aid=$aid");
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Admin Home</title>
			<link rel="stylesheet" type="text/css" href="../css/navigation.css">
			<link rel="stylesheet" href="../css/style.css">
			<link rel="icon" href="./avatar.png" type="image" sizes="16x16">
		<style>
			body{
				text-align: center;
			}
		</style>
	</head>

	<body>	
		<ul class="no0">
			<li><a class="checkedclass" href="./admin_index.php">Home</a></li>
			<li class="dropdown"><a class="hoverclass" href="#">Manage</a>
				<div class="dropdown-content">
					<a href="./list_admin.php">Admin</a>
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
		
		<div style="margin-top:100px; text-align:center;">
			<p><h1><b>Administrator Panel</b></h1>
		</div>

		<div class="indexbox" style="margin-top:50px;">
			<div>
				<a href="./list_admin.php"><div class="managebox"><p>Manage Admin</p></div></a>
				<a href="./list_user.php"><div class="managebox"><p>Manage User</p></div></a><br>
				<a href="./list_report.php"><div class="managebox"><p>Manage Report</p></div></a>
				<a href="./list_vote.php"><div class="managebox"><p>Manage Vote</p></div></a>
				<a href="./list_message.php"><div class="managebox"><p>Manage Message</p></div></a>
			</div>
		</div>
	</body>
</html>

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
	
	$result = mysqli_query($link, "SELECT * FROM message ORDER BY mid DESC");
	
	if(isset($_POST["all"])){
		$result = mysqli_query($link, "SELECT * FROM message ORDER BY mid DESC");
	}else if(isset($_POST["pending"])){
		$result = mysqli_query($link, "SELECT * FROM message WHERE message_status='pending' ORDER BY mid DESC");
	}
	
	if(isset($_POST["search"])){
		$searchtxt=trim($_POST["searchtext"]);
		$result = mysqli_query($link, "SELECT * FROM message WHERE message_email LIKE '%$searchtxt%' ORDER BY mid DESC");
	}
	
	$count = mysqli_num_rows($result);
?>

<html>
	<head>
		<title>Message List</title>
		<link rel="stylesheet" href="../css/style.css">
		<link rel="stylesheet" type="text/css" href="../css/navigation.css">
		<link rel="icon" href="./avatar.png" type="image" sizes="16x16">
	</head>

	<body>
		<ul class="no0">
			<li><a href="./admin_index.php">Home</a></li>
			<li class="dropdown"><a class="checkedclass" href="#">Manage</a>
				<div class="dropdown-content">
					<a href="./list_admin.php">Admin</a>
					<a href="./list_user.php">User</a>
					<a href="./list_vote.php">Vote</a>
					<a href="./list_report.php">Report</a>
					<a class="checkedclass" href="./list_message.php">Message</a>
				</div>
			</li>
			<li class="dropdown" style="float:right; margin-right:50px; width:5%;"><a class="hoverclass" href="./detail_admin.php?admin_detail&caid=<?php echo $aid; ?>"><?php echo $adminname; ?></a>
				<div class="dropdown-content" style="float:left">
					<a href="../logout.php">Sign out</a>
				</div>
			</li>
        </ul>

		<div class="titlebox">
			<a href="admin_index.php"><img src="../img/icon-back.png" alt="Back to main page" class="icon"></a>
			<h1>List of Message</h1>
		</div>

		<fieldset class="adminlist">
		<form method="post">
			<p><input type="text" name="searchtext" placeholder="Search email"><input type="submit" name="search" value="Search"></p>
			<p><input type="submit" name="all" value="All" style="width:50%;"><input type="submit" name="pending" value="Pending" style="width:50%;"></p>
			<table style="margin-left:5%;" cellpadding="0" cellspacing="0" border="1" id="admintable">
				<tr>
                    <th>Message ID</th>
					<th>Email</th>
					<th>Message Content</th>
					<th>Message Send At</th>
					<th>Status</th>
					<th>Edit</th>
				</tr>
				<?php
					while($row = mysqli_fetch_assoc($result)){
				?>
				<tr>
					<td style="padding-left: 8px;"><?php echo $row["mid"]; ?></td>
					<td style="padding-left: 8px;"><a href="mailto:<?php echo $row["message_email"]; ?>"><?php echo $row["message_email"]; ?></a></td>
					<td style="padding-left: 8px;"><?php echo $row["message_content"]; ?></td>
                    <td style="padding-left: 8px;"><?php echo $row["message_send_at"]; ?></td>
					<?php if($row["message_status"] == "pending"){ ?>
						<td style="padding-left: 8px;"><span style='color:#ff9933;'><?php echo $row["message_status"]; ?></span></td>
					<?php }else if($row["message_status"] == "replied"){ ?>
						<td style="padding-left: 8px;"><span style='color:#00e6ac;'><?php echo $row["message_status"]; ?></span></td>
					<?php }else if($row["message_status"] == "discarded"){ ?>
						<td style="padding-left: 8px;"><span><?php echo $row["message_status"]; ?></span></td>
					<?php } ?>
					<td><a href="./detail_message.php?message_detail&mid=<?php echo $row['mid']?>"><input class="detailbtn" type="button" value="Detail"></a></td>
				</tr>
				<?php
					}
				?>
				
			</table>
			<p> Total of message : <?php echo $count; ?></p>
		</form>
		</fieldset>
	</body>
</html>
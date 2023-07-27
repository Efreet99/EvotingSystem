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

    $result = mysqli_query($link, "SELECT * FROM posts ORDER BY pid DESC");
        
    if(isset($_POST["search"])){
        $searchtxt=trim($_POST["searchtext"]);
        $result = mysqli_query($link, "SELECT * FROM posts WHERE post_title LIKE '%$searchtxt%' ORDER BY pid DESC");
    }

	$count = mysqli_num_rows($result);
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$currentDateTime = date('Y-m-d\TH:i:s',time());
?>

<html>
	<head>
		<title>Vote List</title>
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
					<a class="checkedclass" href="./list_vote.php">Vote</a>
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
			<a href="admin_index.php"><img src="../img/icon-back.png" alt="Back to main page" class="icon"></a>
			<h1>List of Vote</h1>
		</div>

		<fieldset class="adminlist">
			<form method="post">
				<p><input type="text" name="searchtext" placeholder="Search post title"><input type="submit" name="search" value="Search"></p>
				<table style="margin-left:5%;" cellpadding="0" cellspacing="0" border="1" id="admintable">
					<tr>
						<th>Vote ID</th>
						<th>Vote Title</th>
						<th>Vote Mode</th>
						<th>Status</th>
						<th>Edit</th>
					</tr>
					<?php
						while($row = mysqli_fetch_assoc($result)){
					?>
					<tr>
						<td style="padding-left: 8px;"><?php echo $row["pid"]; ?></td>
						<td style="padding-left: 8px;"><?php echo $row["post_title"]; ?></td>
						<td style="padding-left: 8px;"><?php echo $row["post_mode"]; ?></td>
						<td style="padding-left: 8px;"><?php if($row["post_expired_date"] <= $currentDateTime){ echo "<span>Completed</span>"; }else{ echo "<span style='color:#00e6ac;'>On-going</span>";} ?></td>
						<td><a href="detail_vote.php?vote_detail&pid=<?php echo $row['pid']?>"><input class="detailbtn" type="button" value="Detail"></a></td>
					</tr>
					<?php
						}
					?>
					
				</table>
				<p> Total of Vote : <?php echo $count; ?></p>
			</form>
		</fieldset>
	</body>
</html>
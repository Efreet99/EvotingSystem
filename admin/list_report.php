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

    $result = mysqli_query($link, "SELECT * FROM report ORDER BY rid DESC");
	
	if(isset($_POST["all"])){
		$result = mysqli_query($link, "SELECT * FROM report ORDER BY rid DESC");
	}else if(isset($_POST["pending"])){
		$result = mysqli_query($link, "SELECT * FROM report WHERE action='pending' ORDER BY rid DESC");
	}
        
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
		<title>Report List</title>
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
					<a class="checkedclass" href="./list_report.php">Report</a>
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
			<a href="./admin_index.php"><img src="../img/icon-back.png" alt="Back to main page" class="icon"></a>
			<h1>List of Report</h1>
		</div>

		<fieldset class="adminlist">
			<form method="post">
				<p><input type="text" name="searchtext" placeholder="Search post title"><input type="submit" name="search" value="Search"></p>
				<p><input type="submit" name="all" value="All" style="width:50%;"><input type="submit" name="pending" value="Pending" style="width:50%;"></p>
				<table style="margin-left:5%;" cellpadding="0" cellspacing="0" border="1" id="admintable">
					<tr>
						<th>Report ID</th>
						<th>Report Reason</th>
						<th>Reported Voting</th>
						<th>Status</th>
						<th>Action</th>
						<th>Edit</th>
					</tr>
					<?php
						while($row = mysqli_fetch_assoc($result)){
					?>
					<tr>
						<td style="padding-left: 8px;"><?php echo $row["rid"]; ?></td>
						<td style="padding-left: 8px;"><?php echo $row["report_reason"]; ?></td>
						<td style="padding-left: 8px;"><?php echo $row["reported_pid"]; ?></td>
						<?php $resultp = mysqli_query($link, "SELECT * FROM posts WHERE pid = '".$row["reported_pid"]."';");
						$rowp = mysqli_fetch_assoc($resultp); ?>
						<td style="padding-left: 8px;"><?php if($rowp["post_expired_date"] <= $currentDateTime){ echo "<span>Completed</span>"; }else{ echo "<span style='color:#00e6ac;'>On-going</span>";} ?></td>
						<?php if($row["action"] == "pending"){ ?>
							<td style="padding-left: 8px;"><span style='color:#ff9933;'><?php echo $row["action"]; ?></span></td>
						<?php }else if($row["action"] == "not applicable"){ ?>
							<td style="padding-left: 8px;"><span style='color:#00e6ac;'><?php echo $row["action"]; ?></span></td>
						<?php }else if($row["action"] == "blocked"){ ?>
							<td style="padding-left: 8px;"><span><?php echo $row["action"]; ?></span></td>
						<?php } ?>
						<td><a href="./detail_report.php?report_detail&rid=<?php echo $row['rid']?>"><input class="detailbtn" type="button" value="Detail"></a></td>
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
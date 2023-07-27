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
	
	$result = mysqli_query($link, "SELECT * FROM admin");
	
	if(isset($_POST["search"])){
		$searchtxt=trim($_POST["searchtext"]);
		$result = mysqli_query($link, "SELECT * FROM admin WHERE adminname LIKE '%$searchtxt%'");
	}
	
	$count = mysqli_num_rows($result);
?>

<html>
	<head>
		<title>Admin List</title>
		<link rel="stylesheet" href="../css/style.css">
		<link rel="stylesheet" type="text/css" href="../css/navigation.css">
		<link rel="icon" href="./avatar.png" type="image" sizes="16x16">
	</head>

	<body>
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
			<a href="admin_index.php"><img src="../img/icon-back.png" alt="Back to main page" class="icon"></a>
			<h1>List of Admin</h1>
		</div>

		<fieldset class="adminlist">
		<form method="post">
			<p><input type="text" name="searchtext" placeholder="Search admin name"><input type="submit" name="search" value="Search"></p>
			<table style="margin-left:5%;" cellpadding="0" cellspacing="0" border="1" id="admintable">
				<tr>
					<th>Admin ID</th>
					<th>Admin Email</th>
					<th>Admin Name</th>
					<th>Status</th>
                    <th>Detail</th>
				</tr>
				<?php
					while($row = mysqli_fetch_assoc($result)){
				?>
				<tr>
					<td style="padding-left: 8px;"><?php echo $row["aid"]; ?></td>
					<td style="padding-left: 8px;"><?php echo $row["adminemail"]; ?></td>
					<td style="padding-left: 8px;"><?php echo $row["adminname"]; ?></td>
					<?php if($row['admin_status'] == "active"){ ?>
						<td style="padding-left: 8px;"><span style='color:#00e6ac;'><?php echo $row["admin_status"]; ?></span></td>
					<?php }else if($row['admin_status'] == "suspended"){ ?>
						<td style="padding-left: 8px;"><span style='color:#ff9933;'><?php echo $row["admin_status"]; ?></span></td>
					<?php }else if($row['admin_status'] == "permanent suspended"){ ?>
						<td style="padding-left: 8px;"><span><?php echo $row["admin_status"]; ?></span></td>
					<?php }else if($row['admin_status'] == "unauthorized"){ ?>
						<td style="padding-left: 8px;"><span style='color:#ff9933;'><?php echo $row["admin_status"]; ?></span></td>
					<?php }
					if($aid == 1 || $aid == $row["aid"]){ ?>
						<td><a href="./detail_admin.php?admin_detail&caid=<?php echo $row['aid']; ?>"><input class="detailbtn" type="button" value="Detail"></a></td>
					<?php }else{ ?>
						<td><input class="detailbtn" type="button" disabled value="Detail"></td>
					<?php } ?>
				</tr>
				<?php
					}
				?>
				
			</table>
			<p> Total of admin : <?php echo $count; ?></p>
		</form>
		<?php if($aid == 1){ ?>
			<a href="add_admin.php"><input type="button" value="Add Admin"></a>
		<?php } ?>
		</fieldset>
	</body>
</html>
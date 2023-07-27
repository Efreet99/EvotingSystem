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

    if(isset($_REQUEST['admin_detail']))
    {
        $caid = $_REQUEST["caid"];
        $result = mysqli_query($link, "SELECT * from admin where aid=$caid");
        $row = mysqli_fetch_assoc($result);
    }
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$adminname = trim($_POST["adminname"]);
		$dob = trim($_POST["dob"]);
		$gender = trim($_POST["gender"]);
		$status = trim($_POST["status"]);
		
		mysqli_query($link, "UPDATE admin SET adminname='$adminname', date_of_birth='$dob', gender='$gender', admin_status='$status' WHERE aid=$caid");
		if($aid == $row["aid"]){
			$_SESSION["adminname"] = $adminname;
		}
		echo "<script>window.location.href='./detail_admin.php?admin_detail&caid=$caid'</script>";
		exit();
	}
	
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$year = date("Y")-18;
	$month = date("m");
	$day = date("d");
	$maxDateTime = date("$year-$month-$day");
?>

<html>
	<head>
		<title>Admin Detail</title>
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
			<li class="dropdown" style="float:right; margin-right:15px; width:5%;"><a class="hoverclass" href="./detail_admin.php?admin_detail&caid=<?php echo $aid; ?>"><?php echo $adminname; ?></a>
				<div class="dropdown-content" style="float:left">
					<a href="../logout.php">Sign out</a>
				</div>
			</li>
        </ul>

        <div class="titlebox">
			<a href="list_admin.php"><img src="../img/icon-back.png" class="icon"></a>
			<h1>Detail of Admin</h1>
        </div>
        
        <fieldset class="adminlist">
		<form method="POST">
            <?php
                echo "<br><b>Admin ID</b><br>";
                echo $row["aid"]; 
				if($row["admin_status"] == "active"){ 
					echo "<br><br><b>Admin Email</b><br>"; ?>
					<input type="email" name="adminemail" disabled value="<?php echo $row['adminemail']; ?>">
					<?php echo "<br><br><b>Admin Name</b><br>"; ?>
					<input type="text" name="adminname" required value="<?php echo $row['adminname']; ?>">
					<?php echo "<br><br><b>DOB</b><br>"; ?>
					<input type="date" name="dob" required max="<?php echo $maxDateTime ; ?>" value="<?php echo $row['date_of_birth']; ?>">
					<?php echo "<br><br><b>Gender</b><br>"; ?>
					<select name="gender" required><?php if($row['gender'] == 'M'){ ?>
						<option value='M' checked>Male<option value='F'>Female<?php }else if($row['gender'] == 'F'){ ?>
						<option value='F' checked>Female<option value='M'>Male<?php } ?>
					</select>
                <?php }else{
					echo "<br><br><b>Admin Email</b><br>"; ?>
					<input type="email" name="adminemail" readonly value="<?php echo $row['adminemail']; ?>">
					<?php echo "<br><br><b>Admin Name</b><br>"; ?>
					<input type="text" name="adminname" readonly value="<?php echo $row['adminname']; ?>">
					<?php echo "<br><br><b>DOB</b><br>"; ?>
					<input type="date" name="dob" readonly max="<?php echo $maxDateTime ; ?>" value="<?php echo $row['date_of_birth']; ?>">
					<?php echo "<br><br><b>Gender</b><br>"; ?>
					<select name="gender" readonly><?php if($row['gender'] == 'M'){ ?>
						<option value='M' checked>Male<?php }else if($row['gender'] == 'F'){ ?>
						<option value='F' checked>Female<?php } ?>
					</select>
				<?php }
				echo "<br><br><b>Account Created Time</b><br>";
                echo $row["created_at"];
				echo "<br><br><b>Account status</b><br>";
				if($aid == 1){ ?>
					<select name="status" required>
					<?php if($row['admin_status'] == "active"){ ?>
						<option value="active" checked>active<option value="suspended">temporary suspended<option value="permanent suspended">permanent suspended<?php }else if($row['admin_status'] == "suspended"){ ?>
						<option value="suspended" checked>temporary suspended<option value="active">active<option value="permanent suspended">permanent suspended<?php }else if($row['admin_status'] == "unauthorized"){ ?>
						<option value="unauthorized" checked>unauthorized<option value="permanent suspended">permanent suspended<?php }else if($row['admin_status'] == "permanent suspended"){ ?>
						<option value="permanent suspended" checked>permanent suspended
					<?php }
				}else{ ?>
					<select name="status" readonly>
					<option value="<?php echo $row['admin_status']; ?>"><?php echo $row['admin_status']; ?></option>
				<?php }  ?>
				</select>
				<br><br><input type="submit" value="Update admin status">
		</form>
        </fieldset>
	</body>
</html>
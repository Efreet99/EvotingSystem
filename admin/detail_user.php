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
	
    if(isset($_REQUEST['user_detail']))
    {
        $uid = $_REQUEST["uid"];
        $result = mysqli_query($link, "SELECT * from users where uid=$uid");
        $row = mysqli_fetch_assoc($result);
    }
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$status = trim($_POST["status"]);
		
		mysqli_query($link, "UPDATE users SET user_status='$status' WHERE uid=$uid");
		echo "<script>window.location.href='./detail_user.php?user_detail&uid=$uid'</script>";
		exit();
	}
?>

<html>
	<head>
		<title>User Detail</title>
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
					<a class="checkedclass" href="./list_user.php">User</a>
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
			<a href="list_user.php"><img src="../img/icon-back.png" class="icon"></a>
			<h1>Detail of User</h1>
        </div>
        
        <fieldset class="adminlist">
		<form method="POST">
            <?php
                echo "<br><b>User ID</b><br>";
                echo $row["uid"]; 
                echo "<br><br><b>User Name</b><br>"; ?>
                <input type="text" name="username" disabled value="<?php echo $row['username']; ?>">
                <?php echo "<br><br><b>User Email</b><br>"; ?>
                <input type="email" name="email" disabled value="<?php echo $row['email']; ?>">
                <?php echo "<br><br><b>DOB</b><br>"; ?>
                <input type="date" name="dob" disabled value="<?php echo $row['date_of_birth']; ?>">
                <?php echo "<br><br><b>Gender</b><br>"; ?>
                <select name="gender" disabled><?php if($row['gender'] == 'M'){ ?>
					<option value='M' checked>Male<option value='F'>Female<?php }else if($row['gender'] == 'F'){ ?>
					<option value='F' checked>Female<option value='M'>Male<?php } ?>
				</select>
				<?php echo "<br><br><b>Account Created Time</b><br>";
                echo $row["user_created_at"];
				echo "<br><br><b>Account status</b><br>"; ?>
                <select name="status" required><?php if($row['user_status'] == "active"){ ?>
					<option value="active" checked>active<option value="blocked">temporary blocked<option value="permanent suspended">permanent suspended <?php }else if($row['user_status'] == "blocked"){ ?>
					<option value="blocked" checked>temporary blocked<option value="active">active<option value="permanent suspended">permanent suspended <?php }else if($row['user_status'] == "permanent suspended"){ ?>
					<option value="permanent suspended" checked>permanent suspended<?php } ?>
				</select>
			<br><br><input type="submit" value="Update user status">
		</form>
        </fieldset>
	</body>
</html>
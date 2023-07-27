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

    if(isset($_REQUEST['report_detail'])){
        $rid = $_REQUEST["rid"];
    }
?>

<html>
	<head>
		<title>Report Detail</title>
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
			<a href="./list_report.php"><img src="../img/icon-back.png" alt="Back to main page" class="icon"></a>
			<h1>Detail of Report</h1>
		</div>
        <fieldset class="adminlist">
		<form method="POST">
			<?php
				$result = mysqli_query($link, "SELECT * FROM report WHERE rid=$rid");
				$row = mysqli_fetch_assoc($result);
				if($_SERVER["REQUEST_METHOD"] == "POST"){
					$action = trim($_POST["action"]);
					
					mysqli_query($link, "UPDATE report SET action='$action' WHERE rid=$rid");
					if($action == "blocked"){
						mysqli_query($link, "UPDATE posts SET post_status='blocked' WHERE pid='".$row["reported_pid"]."';");
					}else{
						mysqli_query($link, "UPDATE posts SET post_status='approve' WHERE pid='".$row["reported_pid"]."';");
					}
					echo "<script>window.location.href='./detail_report.php?report_detail&rid=$rid'</script>";
					exit();
				}
				echo "<br><b>Report ID</b><br>";
				echo $row["rid"]; 
				echo "<br><br><b>Report Reason</b><br>"; 
				echo $row["report_reason"];
				echo "<br><br><b>Report Description</b><br>"; 
				echo $row["report_desc"]; 
				echo "<br><br><b>Reporter</b><br>";
				$resultreru = mysqli_query($link, "SELECT * FROM users WHERE uid='".$row["reporter_uid"]."';");
				$rowreru = mysqli_fetch_assoc($resultreru);
				echo "<a href='./detail_user.php?user_detail&uid=".$rowreru["uid"]."'>UID   :".$rowreru["uid"]."<br>";
				echo "Username :".$rowreru["username"]."<br></a>";
				echo "<br><br><b>Reported Voting</b><br>";
				$resultredv = mysqli_query($link, "SELECT * FROM posts WHERE pid='".$row["reported_pid"]."';");
				$rowredv = mysqli_fetch_assoc($resultredv);
				echo "<a href='./detail_vote.php?vote_detail&pid=".$rowredv["pid"]."'>PID   :".$rowredv["pid"]."<br>";
				echo "Title :".$rowredv["post_title"]."<br></a>";
				echo "<br><br><b>Reported User</b><br>";
				$resultredu = mysqli_query($link, "SELECT * FROM users WHERE uid='".$row["reported_uid"]."';");
				$rowredu = mysqli_fetch_assoc($resultredu);
				echo "<a href='./detail_user.php?user_detail&uid=".$rowredu["uid"]."'>UID   :".$rowredu["uid"]."<br>";
				echo "Username :".$rowredu["username"]."<br></a>";
				echo "<br><br><b>Action</b><br>";?>
				<select name="action" required><?php if($row['action'] == "pending"){ ?>
					<option value="pending" checked>pending<option value="not applicable">It's ok, not applicable the report<option value="blocked">block the voting post<?php }else if($row['action'] == "not applicable"){ ?>
					<option value="not applicable" checked>It's ok, not applicable the report <?php }else if($row['action'] == "blocked"){ ?>
					<option value="blocked" checked>voting post has been blocked<?php } ?>
				</select>
			<br><br><input type="submit" value="Update report status">
		</form>
        </fieldset>
	</body>
</html>
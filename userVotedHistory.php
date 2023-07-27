<?php
	require_once "./link.php";
	
	session_start();
	
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location: login.php");
		exit;
	}else{
		$uid = $_SESSION["uid"];
		$username = $_SESSION["username"];
		$_SESSION["postaccess"] = false;
    }
	
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$currentDateTime = date('Y-m-d\TH:i:s',time());

    
?>

<html>
	<head>
		<title>My voted history</title>
		<link rel="stylesheet" type="text/css" href="./css/style.css">
		<link rel="stylesheet" type="text/css" href="./css/navigation.css">
		<style>
			details > summary {
				margin:auto;
				margin-bottom:0px;
				margin-top:0px;
				padding: 5px;
				border: none;
				box-shadow: 1px 1px 2px #bbbbbb;
				cursor: pointer;
				width:80%;
			}

			details > p {
				margin-bottom:0px;
				margin-top:20px;
				padding: 5px;
				margin: 0;
				box-shadow: 1px 1px 2px #bbbbbb;
				width:80%;
			}
		</style>
		<script>
			function toSAll(){
				document.getElementById("changeCat").innerHTML='<?php $result = mysqli_query($link, "SELECT * FROM countoption, posts WHERE countoption.uid='$uid' AND countoption.pid=posts.pid AND count>0 GROUP BY posts.pid ORDER BY posts.pid DESC;"); while($row = mysqli_fetch_assoc($result)){ ?> <a href="./voteDetail.php?view&pid=<?php echo $row['pid']; ?>" style="color:#000000; text-decoration:none;"><fieldset class="show" style="margin-bottom:15px"><h2 style="text-align: center;"><?php echo $row["post_title"]; ?></h2><div class="summarybox"><p><?php echo $row["post_desc"]; ?></p></div><p>Voting method: <?php echo strtoupper($row["vote_method"]); ?><br>Voting-mode:<?php echo strtoupper($row["post_mode"]); ?><br>Started date:<?php echo $row["post_created_date"]; ?><br><?php if($row["post_expired_date"] <= $currentDateTime){ echo "Status: <span>Completed</span><br>Expired date: <span>$row[post_expired_date] </span>"; }else{ echo "Status: <span style=\'color:#00ffbf\'>On-going</span><br>Expired date: <span style=\'color:#00ffbf\'>$row[post_expired_date] </span>";} ?></p></fieldset></a><?php } ?>'; 
			}
			function toCompleted(){
				document.getElementById("changeCat").innerHTML='<?php $result = mysqli_query($link, "SELECT * FROM countoption, posts WHERE countoption.uid='$uid' AND countoption.pid=posts.pid AND post_expired_date<='$currentDateTime' AND count>0 GROUP BY posts.pid ORDER BY posts.pid DESC;"); while($row = mysqli_fetch_assoc($result)){ ?> <a href="./voteDetail.php?view&pid=<?php echo $row['pid']; ?>" style="color:#000000; text-decoration:none;"><fieldset class="show" style="margin-bottom:15px"><h2 style="text-align: center;"><?php echo $row["post_title"]; ?></h2><div class="summarybox"><p><?php echo $row["post_desc"]; ?></p></div><p>Voting method: <?php echo strtoupper($row["vote_method"]); ?><br>Voting-mode:<?php echo strtoupper($row["post_mode"]); ?><br>Started date:<?php echo $row["post_created_date"]; ?><br><?php if($row["post_expired_date"] <= $currentDateTime){ echo "Status: <span>Completed</span><br>Expired date: <span>$row[post_expired_date] </span>"; }else{ echo "Status: <span style=\'color:#00ffbf\'>On-going</span><br>Expired date: <span style=\'color:#00ffbf\'>$row[post_expired_date] </span>";} ?></p></fieldset></a><?php } ?>'; 
			}
			function toOngoing(){
				document.getElementById("changeCat").innerHTML='<?php $result = mysqli_query($link, "SELECT * FROM countoption, posts WHERE countoption.uid='$uid' AND countoption.pid=posts.pid AND post_expired_date>'$currentDateTime' AND count>0 GROUP BY posts.pid ORDER BY posts.pid DESC;"); while($row = mysqli_fetch_assoc($result)){ ?> <a href="./voteDetail.php?view&pid=<?php echo $row['pid']; ?>" style="color:#000000; text-decoration:none;"><fieldset class="show" style="margin-bottom:15px"><h2 style="text-align: center;"><?php echo $row["post_title"]; ?></h2><div class="summarybox"><p><?php echo $row["post_desc"]; ?></p></div><p>Voting method: <?php echo strtoupper($row["vote_method"]); ?><br>Voting-mode:<?php echo strtoupper($row["post_mode"]); ?><br>Started date:<?php echo $row["post_created_date"]; ?><br><?php if($row["post_expired_date"] <= $currentDateTime){ echo "Status: <span>Completed</span><br>Expired date: <span>$row[post_expired_date] </span>"; }else{ echo "Status: <span style=\'color:#00ffbf\'>On-going</span><br>Expired date: <span style=\'color:#00ffbf\'>$row[post_expired_date] </span>";} ?></p></fieldset></a><?php } ?>'; 
			}
		</script>
	</head>

	<body class="body2">
		<ul>
			<li><a href="./index.php">X-Voter</a></li>
			<li class="dropdown"><a class="hoverclass" href="#">As Organizer</a>
				<div class="dropdown-content">
					<a href="./createVote.php">Create vote</a>
					<a href="./userCreatedVote.php">My Organized Vote</a>
				</div>	
			</li>
			<li class="dropdown"><a class="checkedclass" href="#">As Voter</a>
				<div class="dropdown-content">
					<a href="./asvoter.php">Vote List</a>
					<a class="checkedclass" href="./userVotedHistory.php">My Voted History</a>
				</div>	
			</li>

			<li class="dropdown" style="float:right; margin-right:50px; width:5%;"><a class="hoverclass" href="./profile.php"><?php echo $username; ?></a>
				<div class="dropdown-content" style="float:left">
					<a href="./logout.php">Sign out</a>
				</div>
			</li>
		</ul>

		<div class="titlebox">
			<a href="index.php"><img src="./img/icon-back.png" alt="Back to main page" class="icon"></a>
			<h1>My Voted History</h1>
		</div>
		
		<p class="adminlist">
			<details>
				<summary>Filter</summary>
				<p style="margin:auto">
				Status<br>
				<input type="radio" name="status_category" onclick="toSAll()" checked>All   <input type="radio" name="status_category" onclick="toOngoing()">On-going   <input type="radio" name="status_category" onclick="toCompleted()">Completed</p>
			</details>
		</p>
		
		<span id="changeCat">
		<?php 
		$result = mysqli_query($link, "SELECT * FROM countoption, posts WHERE countoption.uid=$uid AND countoption.pid=posts.pid AND count>0 GROUP BY posts.pid ORDER BY posts.pid DESC;");
		while($row = mysqli_fetch_assoc($result)){ ?>
			<a href="./voteDetail.php?view&pid=<?php echo $row['pid']; ?>" style="color:#000000; text-decoration:none;">
				<fieldset class="show" style="margin-bottom:15px">
					<h2 style="text-align: center;"><?php echo $row["post_title"]; ?></h2>
					<div class="summarybox">
						<p><?php echo $row["post_desc"]; ?></p>
					</div>
					<p>Voting method: <?php echo strtoupper($row["vote_method"]); ?><br>
					Voting-mode:<?php echo strtoupper($row["post_mode"]); ?><br>
					Started date:<?php echo $row["post_created_date"]; ?><br>
					<?php
					if($row["post_expired_date"] <= $currentDateTime){
						echo "Status: <span>Completed</span><br>Expired date: <span>$row[post_expired_date] </span>";
					}else{
						echo "Status: <span style='color:#00ffbf;'>On-going</span><br>Expired date: <span style='color:#00ffbf;'>$row[post_expired_date] </span>";
					}
					?></p>
				</fieldset> 
			</a>
		<?php }?>
		<span>
	</body>
</html>
<?php
	require_once "./link.php";
	
	session_start();
	
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location: login.php");
		exit;
	}else{
		$uid = $_SESSION["uid"];
		$username = $_SESSION["username"];
    }
	
	if(isset($_REQUEST["vote"])){
		$pid = $_REQUEST["pid"];
		$oid = $_REQUEST["oid"];
		$count = $_REQUEST["count"];
		if($result = mysqli_query($link, "INSERT INTO countoption (uid, pid, oid, count) VALUES ($uid, $pid, $oid, $count)")){
			echo "<script>alert('Your vote has been recorded'); window.location.href='./voteDetail.php?view&pid=$pid'</script>";
		}
	}
	
	if(isset($_REQUEST["votes"])){
		$vote_error = "";
		$pid = $_REQUEST["pid"];
		$oid = $_REQUEST["oid"];
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			$count = trim($_POST["count"]);
			if($count == 0){
				$vote_error = "Your vote can't be 0.";
			}else{
				$vote_error = "";
			}
			if(empty($vote_error)){
				if($result = mysqli_query($link, "INSERT INTO countoption (uid, pid, oid, count) VALUES ($uid, $pid, $oid, $count)")){
					echo "<script>alert('Your vote has been recorded'); window.location.href='./voteDetail.php?view&pid=$pid'</script>";
				}
			}
		}
		$result = mysqli_query($link, "SELECT * FROM posts, optiontable WHERE posts.pid = $pid AND optiontable.oid = $oid AND posts.pid = optiontable.pid");
		$row = mysqli_fetch_assoc($result);
		$resultcountthis = mysqli_query($link, "SELECT SUM(count) AS countsumthis FROM countoption WHERE uid = $uid AND oid = $oid");
		$rowcountthis = mysqli_fetch_assoc($resultcountthis); 
		$countsumthis = $rowcountthis["countsumthis"]; ?>
		<html>
			<head>
				<title>Vote Detail</title>
				<link rel="stylesheet" type="text/css" href="./css/style.css">
				<link rel="stylesheet" type="text/css" href="./css/navigation.css">
			</head>
			<body class="body2">
			<ul>
				<li><a style="color:#5e5e5e" href="./index.php">X-Voter</a></li>
			</ul>
			<div class="titlebox">
				<a href="./voteDetail.php?view&pid=<?php echo $pid; ?>"><img src="./img/icon-back.png" alt="Back to main page" class="icon"></a>
				<h1>Vote Detail</h1>
			</div>
				<fieldset class="process">
					<form method="POST">
						<h2>How many times you want to vote for this option?</h2>
						<h3>Voting title: <?php echo $row["post_title"]; ?><br>
						Description: <?php echo $row["post_desc"]; ?></h3>
						<h4>Option <?php echo $row["option_name"]; ?><br>
						Option description: <?php if($row["option_desc"] != NULL){echo $row["option_desc"];}else{echo "None";} ?></h4>
						<?php if($row["option_image"] != null){
							$imageURL = './uploads/'.$row["option_image"];
							echo "<p><img width='100%' src='$imageURL'/></p>"; 
						} ?>
						<?php $resultcount = mysqli_query($link, "SELECT SUM(count) AS countsum FROM countoption WHERE uid = $uid AND pid = $pid");
						$rowcount = mysqli_fetch_assoc($resultcount); 
						$countsum = $rowcount["countsum"]; 
						if(mysqli_num_rows($resultcount) == 0){
							$i = $row["number_votes"] - 0;
						}else{
							$i = $row["number_votes"] - $countsum;
						}
						$j = 0;?>
						<p>
						<select name="count">
						<?php while($j-1 < $i){ ?>
							<option value="<?php echo $j; ?>"><?php echo $j; ?></option>
						<?php $j++;
						} ?>
						</select>
						<span>
							<?php if(isset($vote_error)){echo $vote_error;}?><br>
							Warning: Once you voted, you can't reset your option, you only can vote more time for this option.
						</span>
							<br>You already vote for this option <span><?php echo isset($countsumthis) ? $countsumthis:'0'; ?></span> times, You only have <span><?php echo $row["number_votes"] - $countsum; ?></span> times left to vote.
						</p>
						<p><input type="submit" value="Vote" onclick="return Vote();"></p>
					</form>
				</fieldset>
				<script>
					function Vote(){
						con = confirm("Do you sure want to vote this option?");
						return con;
					}
				</script>
			</body>
		</html>
	<?php }
?>
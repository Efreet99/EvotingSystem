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
	
	if(isset($_REQUEST["report"])){
		$pid = $_REQUEST["pid"];
		$result = mysqli_query($link, "SELECT * FROM posts WHERE pid=$pid");
		$row = mysqli_fetch_assoc($result);
		$reporter_uid = $uid;
		$reported_uid = $row["uid"];
		$reported_pid = $pid;
		
		$reason = "";
		$reason_error = "";
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			$reason = trim($_POST["reason"]);
			$reason_deac = trim($_POST["desc"]);
			
			if(empty($reason)){
				$reason_error = "Please select a reason.";
			}else{
				$reason_error = "";
			}
			
			if(empty($reason_error)){
				mysqli_query($link, "INSERT INTO report (report_reason, report_desc, reporter_uid, reported_uid, reported_pid, action) VALUES ('$reason', '$reason_desc', '$reporter_uid', '$reported_uid', '$reported_pid', 'pending');");
				echo "<script>alert('Your report has been recoeded'); window.location.href='./voteDetail.php?view&pid=$pid';</script>";
			}
		}
?>
<html>
    <head>
        <title>Report Voting</title>
        <link rel="stylesheet" type="text/css" href="./css/style.css">
		<link rel="stylesheet" type="text/css" href="./css/navigation.css">
		<script>
			function ifOther(){
				if(document.getElementById("reason").value == "other"){
					document.getElementById("changeCondition").innerHTML='<p>Report Description<span>*</span>:<br><textarea rows="4" cols="75" name="desc" required placeholder="This is required."></textarea></p>';
				}else{
					document.getElementById("changeCondition").innerHTML='<p>Report Description:<br><textarea rows="4" cols="75" name="desc" placeholder="More datail, this is optional."></textarea></p>';
				}
			}
		</script>
    </head>
	<body class="body2">
		<ul>
			<li><a style="color:#5e5e5e" href="./index.php">X-Voter</a></li>
		</ul>
		<div class="titlebox">
			<a href="./voteDetail.php?view&pid=<?php echo $pid; ?>"><img src="./img/icon-back.png" alt="Back to main page" class="icon"></a>
			<h1>Report Voting</h1>
		</div>
		<fieldset>
			<form method="POST">
				<h2>The reason to report this voting?</h2> 
				<h3>Voting title: <?php echo $row["post_title"]; ?><br>
				Description: <?php echo $row["post_desc"]; ?></h3>
				<p>Report Reason<span>*</span>:<br>
				<select style="width:200px;" name="reason" id="reason" onchange="ifOther()"><br>
					<option value="">--</option>
					<option value="nudity">Nudity</option>
					<option value="violence or suicide or self-injury">Violence or Suicide or Self-injury</option>
					<option value="harassment">Harassment</option>
					<option value="false news or spam">False News or Spam</option>
					<option value="unauthorized sales">Unauthorized Sales</option>
					<option value="hate speech or terrorism">Hate Speech or Terrorism</option>
					<option value="other">Other</option>
				</select><br>
				<span>
					<?php if(isset($reason_error)){echo $reason_error;} ?>
				</span>
				</p>
				<div id="changeCondition">
				<p>Report Description:<br>
					<textarea rows="4" cols="75" name="desc" placeholder="More datail, this is optional."></textarea>
				</p>
				</div>
				<p><input type="submit" value="Report"></p>
			</form>
        </fieldset>
	</body>
</html>
	<?php } ?>
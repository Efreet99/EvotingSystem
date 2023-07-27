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
	
	if(isset($_REQUEST["view"])){
		$pid = $_REQUEST["pid"];
		$result = mysqli_query($link, "SELECT * FROM posts where pid = $pid");
		$resultoption = mysqli_query($link, "SELECT * FROM optiontable where pid = $pid");
		$resultc = mysqli_query($link, "SELECT * FROM countoption WHERE uid = $uid AND pid = $pid");
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$currentDateTime = date('Y-m-d\TH:i:s',time());
?>

<html>
    <head>
        <title>Vote Detail</title>
        <link rel="stylesheet" type="text/css" href="./css/style.css">
		<link rel="stylesheet" type="text/css" href="./css/navigation.css">
		<link rel="icon" href="./avatar.png" type="image" sizes="16x16">
		<style>
			details > summary {
				padding: 5px;
				width: 98%;
				border: none;
				box-shadow: 1px 1px 2px #bbbbbb;
				cursor: pointer;
			}

			details > p {
				width: 98%;
				padding: 5px;
				margin: 0;
				box-shadow: 1px 1px 2px #bbbbbb;
			}
		</style>
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
			<li class="dropdown"><a class="hoverclass" href="#">As Voter</a>
				<div class="dropdown-content">
					<a href="./asvoter.php">Vote List</a>
					<a href="./userVotedHistory.php">My Voted History</a>
				</div>	
			</li>

			<li class="dropdown" style="float:right; margin-right:50px; width:5%;"><a class="hoverclass" href="./profile.php"><?php echo $username; ?></a>
				<div class="dropdown-content" style="float:left">
					<a href="./logout.php">Sign out</a>
				</div>
			</li>
		</ul>

        <div class="titlebox">
			<a href="./asvoter.php"><img src="./img/icon-back.png" alt="Back to main page" class="icon"></a>
			<h1>Vote Detail</h1>
		</div>

		<fieldset>
			<form method="POST">
            <?php
				$row = mysqli_fetch_assoc($result);
				if($row["post_status"] == "approve"){
					if($row["post_expired_date"] <= $currentDateTime){
						echo "<script>window.location.href='./displayresult.php?display&pid=$pid'</script>";
					}
					if(isset($_POST["endvote"])){
						if(mysqli_query($link, "UPDATE posts SET post_expired_date='$currentDateTime' WHERE pid=$pid;")){
							echo "<script>alert('Your vote has been ended.'); window.location.href='./displayresult.php?display&pid=$pid'</script>";
							exit();
						}
					}
					$post_password = "";
					$post_password_error = "";
					if(isset($_POST["access"])){
						if($_POST["post_password"]){
							setcookie("post_password", $post_password, time()+60);
							$post_password = trim($_POST["post_password"]);
							if($post_password == $row["post_password"]){
								$_SESSION["postaccess"] = true;
								unset($_COOKIE["post_password"]);
								$post_password_error = "";
								echo "<script>window.location.href='./voteDetail.php?view&pid=$pid';</script>";
							}else{
								$_SESSION["postaccess"] = false;
								$post_password_error = "Password is not valid";
							}
						}
					}?>

					<h2 style="text-align: center;"><?php echo $row["post_title"]; ?></h2>
					<hr style="1px solid black"></hr>
					
					<p><?php echo $row["post_desc"]; ?></p>
					<p style="width:50%; float:left; height:100px;">
						Voting method : <span class="vdetail_colour"><?php echo strtoupper($row["vote_method"]); ?></span> Voting<br>
						Start at   : <?php echo $row["post_created_date"]; ?><br>
						Expired at   : <span class="vdetail_colour"><?php echo $row["post_expired_date"]; ?></span><br>
						Voting-mode  : <span class="vdetail_colour"><?php echo strtoupper($row["post_mode"]); ?></span>
						<?php if($row["vote_method"] == "multi" || $row["vote_method"] == "multi-point"){ ?>
						<br>Each voter can vote  : <span class="vdetail_colour"><?php echo $row["number_votes"]; ?></span> times
						<?php } ?>
					</p>
					<?php
					$rowc = mysqli_fetch_assoc($resultc);
					$isvoted = mysqli_num_rows($resultc);
					
					if(($isvoted != 0 && $isvoted <= $row["number_votes"]) || $uid == $row["uid"]){
						$_SESSION["postaccess"] = true;
					}

					if($row["post_mode"] == "private" && $uid != $row["uid"] && $_SESSION["postaccess"] !== true){ ?>
						<div class="accessform">
							<h2 class="accessform_title">Pass verification</h2>
							<p><span>This is a private voting. You need password to access before you voting.</span></p>
							<input type="text" required placeholder="Enter Vote Password" name="post_password" value="<?php echo isset($_COOKIE['post_password']) ? $post_password : '';?>"><br>
							<input type="submit" value="Access" name="access"><br><span><?php if(isset($post_password_error)){echo $post_password_error;}?></span>
						</div>
					<?php }else{?>
						<p style="width:50%; float:right; height:100px;">
						<?php if($uid != $row["uid"]){
							$resultr = mysqli_query($link, "SELECT * FROM report WHERE reporter_uid=$uid AND reported_pid=$pid;");
							if(mysqli_num_rows($resultr) == 0){ ?>
								<span>Not appropriate?</span> <a href="./report.php?report&pid=<?php echo $pid; ?>"><input type="button" value="Report this Voting"></a>
							<?php }else{
								echo "<span>You have already reported this voting.<br>Thank you for your contribution to a good network environment.</span>";
							}
						}?>
						</p>
						
						<?php if($uid == $row["uid"] && !empty($row["post_password"])){ ?>
							<p>Share the password to your friend: <span><?php echo $row["post_password"]; ?></span></p>
						<?php }
						if($uid == $row["uid"]){ ?>
							<p>You can end your vote early. If you want to end early, press this button. <span class="showresult"><input type="submit" value="End your vote now" onclick="return End();" name="endvote"></span></p>
						<?php } ?>

							<table cellpadding="0" cellspacing="0" border="1px solid black" class="showresult" style="margin:auto;">
								<tr>
									<th>Option</th>
									<th>Total vote(s)</th>
									<?php if($uid != $row["uid"]){ ?>
										<th>Your vote(s)</th>
									<?php } ?>
								</tr>
							<?php while($rowo = mysqli_fetch_assoc($resultoption)){ 
								if($uid == $row["uid"]){ ?>
									<tr style="background-color:#ccffdc;">
								<?php }else{ ?>
									<tr>
								<?php } ?>
									
									<td style="width:600px; border-top: 1px solid black;">
										<details>
											<summary><?php echo $rowo["option_name"]; ?></summary>
											<?php if($rowo["option_desc"] != NULL || $rowo["option_image"] != NULL){ ?>
											<p style="margin-bottom:8px;"><?php echo $rowo["option_desc"];
											if($rowo["option_image"] != null){
												$imageURL = './uploads/'.$rowo["option_image"];
												echo "<br><img width='100%' src='$imageURL'/>"; 
											} ?></p>
											<?php } ?>
										</details>
									</td>

									<?php
									$oid = $rowo["oid"];
									if($row["vote_method"] == "single"){
										$resultu = mysqli_query($link, "SELECT SUM(count) AS countsumu FROM countoption WHERE oid = $oid AND uid = $uid");
										$rowu = mysqli_fetch_assoc($resultu);
										$countsumu = $rowu["countsumu"];
										if($isvoted == 0 && $uid != $row["uid"]){ ?>
											<td colspan="2" style="width:75px; height:50px; text-align:center;"><a href="vote_process.php?vote&pid=<?php echo $pid ?>&oid=<?php echo $oid ?>&count=1" onclick="return Vote();"><input type="button" value="Vote"></a></td>
										<?php }else if($uid == $row["uid"]){
											$resultv = mysqli_query($link, "SELECT SUM(count) AS countsumv FROM countoption WHERE oid = $oid");
											$rowv = mysqli_fetch_assoc($resultv);
											$countsumv = $rowv["countsumv"];
											if($countsumv > 0){
												echo "<td style='width:75px; height:50px; text-align:center;'>$countsumv</td>";
											}else{
												echo "<td style='width:75px; height:50px; text-align:center;'>0</td>";
											}
										}else{
											$resultv = mysqli_query($link, "SELECT SUM(count) AS countsumv FROM countoption WHERE oid = $oid");
											$rowv = mysqli_fetch_assoc($resultv);
											$countsumv = $rowv["countsumv"];
											if($countsumv > 0){
												echo "<td style='width:75px; height:50px; text-align:center;'>$countsumv</td>";
											}else{
												echo "<td style='width:75px; height:50px; text-align:center;'>0</td>";
											}
											if($countsumu > 0){
												echo "<td style='text-align:center; background-color:#b3ffcc;'>$countsumu</td>";
											}else{
												echo "<td style='text-align:center;'>0</td>";
											}
										} 
									}else if($row["vote_method"] == "multiple"){
										$resultu = mysqli_query($link, "SELECT SUM(count) AS countsumu FROM countoption WHERE oid = $oid AND uid = $uid");
										$rowu = mysqli_fetch_assoc($resultu);
										$countsumu = $rowu["countsumu"];
										if($isvoted == 0 && $uid != $row["uid"]){ ?>
											<td colspan="2" style="width:75px; height:50px; text-align:center;"><a href="vote_process.php?vote&pid=<?php echo $pid ?>&oid=<?php echo $oid ?>&count=1" onclick="return Vote();"><input type="button" value="Vote"></a></td>
										<?php }else if($isvoted != 0 && $isvoted < $row["number_votes"] && $uid != $row["uid"]){
											if($countsumu > 0){
												echo "<td></td><td style='width:75px; height:50px; text-align:center; background-color:#b3ffcc;'>$countsumu</td>";
											}else{
												echo "<td colspan='2' style='width:75px; height:50px; text-align:center;'><a href='vote_process.php?vote&pid=$pid&oid=$oid&count=1' onclick='return Vote();'><input type='button' value='Vote'></a></td>";
											}
										}else if($uid == $row["uid"]){
											$resultv = mysqli_query($link, "SELECT SUM(count) AS countsumv FROM countoption WHERE oid = $oid");
											$rowv = mysqli_fetch_assoc($resultv);
											$countsumv = $rowv["countsumv"];
											if($countsumv > 0){
												echo "<td style='width:75px; height:50px; text-align:center;'>$countsumv</td>";
											}else{
												echo "<td style='width:75px; height:50px; text-align:center;'>0</td>";
											}
										}else{
											$resultv = mysqli_query($link, "SELECT SUM(count) AS countsumv FROM countoption WHERE oid = $oid");
											$rowv = mysqli_fetch_assoc($resultv);
											$countsumv = $rowv["countsumv"];
											if($countsumv > 0){
												echo "<td style='width:75px; height:50px; text-align:center;'>$countsumv</td>";
											}else{
												echo "<td style='width:75px; height:50px; text-align:center;'>0</td>";
											}
											if($countsumu > 0){
												echo "<td style='text-align:center; background-color:#b3ffcc;'>$countsumu</td>";
											}else{
												echo "<td style='text-align:center;'>0</td>";
											}
										}
									}else if($row["vote_method"] == "multi-point"){
										$resultu = mysqli_query($link, "SELECT SUM(count) AS countsumu FROM countoption WHERE oid = $oid AND uid = $uid");
										$rowu = mysqli_fetch_assoc($resultu);
										$countsumu = $rowu["countsumu"];
										$resultp = mysqli_query($link, "SELECT SUM(count) AS countsump FROM countoption WHERE uid = $uid AND pid = $pid");
										$rowp = mysqli_fetch_assoc($resultp);
										$countsump = $rowp["countsump"];
										if($countsump < $row["number_votes"] && $uid != $row["uid"]){ ?>
											<td colspan="2" style="width:75px; height:50px; text-align:center;"><a href="vote_process.php?votes&pid=<?php echo $pid ?>&oid=<?php echo $oid ?>"><input type="button" value="Vote"></a></td>
										<?php }else if($uid == $row["uid"]){
											$resultv = mysqli_query($link, "SELECT SUM(count) AS countsumv FROM countoption WHERE oid = $oid");
											$rowv = mysqli_fetch_assoc($resultv);
											$countsumv = $rowv["countsumv"];
											if($countsumv > 0){
												echo "<td style='width:75px; height:50px; text-align:center;'>$countsumv</td>";
											}else{
												echo "<td style='width:75px; height:50px; text-align:center;'>0</td>";
											}
										}else{
											$resultv = mysqli_query($link, "SELECT SUM(count) AS countsumv FROM countoption WHERE oid = $oid");
											$rowv = mysqli_fetch_assoc($resultv);
											$countsumv = $rowv["countsumv"];
											if($countsumv > 0){
												echo "<td style='width:75px; height:50px; text-align:center;'>$countsumv</td>";
											}else{
												echo "<td style='width:75px; height:50px; text-align:center;'>0</td>";
											}
											if($countsumu > 0){
												echo "<td style='text-align:center; background-color:#b3ffcc;'>$countsumu</td>";
											}else{
												echo "<td style='text-align:center;'>0</td>";
											}
										}
									} ?>
								</tr>
							<?php } ?>
						</table>
					<?php } ?>
					<hr style="margin-left:5%; width:90%">
					<h3 style="text-align:center;">Comment</h3>
					<hr style="margin-left:10%; width:80%">
					<?php if($row["post_mode"] == "private" && $uid != $row["uid"] && $_SESSION["postaccess"] !== true){ ?>
						<div style="margin-left:10%; width:80%">
							<p>You don't have permission to read and write the comment.</p>
						</div>
					<?php }else{ ?>
						<div class="commentclass" style="margin-left:10%; width:80%">
							<textarea style="width:100%" rows="4" cols="75" name="content" placeholder="comment here"></textarea><br>
							<input style="margin-left:10%; width:80%" type="submit" name="comment" value="comment"><br>
						</div>
						<?php $resultcom = mysqli_query($link, "SELECT * FROM comment, users WHERE pid=$pid AND comment.uid=users.uid ORDER BY comid DESC");
						if(mysqli_num_rows($resultcom) == 0){ ?>
							<hr style="margin-left:10%; width:80%">
							<div style="margin-left:10%; width:80%">
								<p>Don't have comment yet, first to comment?</p>
							</div>
						<?php }else{
							while($rowcom = mysqli_fetch_assoc($resultcom)){ ?>
							<hr style="margin-left:10%; width:80%">
							<div style="margin-left:10%; width:80%">
								<b><?php echo $rowcom["username"];
									if($row["uid"] == $rowcom["uid"]){
										echo " <span><i>Organizer</i></span>";
									}
								?></b>
								<i style="float:right;"><?php echo $rowcom["comment_at"]; ?></i><br>
								<?php echo $rowcom["comment_content"]; ?>
							</div>
							<?php }
						}
						if(isset($_POST["comment"])){
							$content = trim($_POST["content"]);
							
							mysqli_query($link, "INSERT INTO comment (comment_content, pid, uid) VALUES ('$content', '$pid', '$uid')");
							echo "<script>window.location.href='./voteDetail.php?view&pid=$pid'</script>";
						}
					} 
				}else if($row["post_status"] == "blocked"){ ?>
					<h2 style="text-align: center;">This voting has been blocked.</h2>
				<?php }else{ ?>
					<h2 style="text-align: center;">The voting post dosen't exists.</h2>
				<?php } ?>
			</form>
        </fieldset>
		
		<script>
			function Vote(){
				con = confirm("Do you sure want to vote this option?");
				return con;
			}
			function End(){
				con = confirm("Do you sure want to end your vote early?");
				return con;
			}
		</script>
    </body>
</html>
<?php
	}
?>
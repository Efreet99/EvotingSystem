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

	if(isset($_REQUEST["display"])){
		$pid = $_REQUEST["pid"];
		$result = mysqli_query($link, "SELECT * FROM posts where pid = $pid");
		$resultc = mysqli_query($link, "SELECT * FROM countoption WHERE uid = $uid AND pid = $pid");
		$highestcount = $youvotethis = 0;
		date_default_timezone_set("Asia/Kuala_Lumpur");
		$currentDateTime = date('Y-m-d\TH:i:s',time());
?>

<html>
    <head>
        <title>Vote Result</title>
        <link rel="stylesheet" type="text/css" href="./css/style.css">
		<link rel="stylesheet" type="text/css" href="./css/navigation.css">
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
			<h1>Vote Result</h1>
		</div>
		
		<fieldset>
			<?php
			$row = mysqli_fetch_assoc($result);
			if($row["post_status"] == "approve"){
				if($row["post_expired_date"] > $currentDateTime){
					echo "<script>window.location.href='./voteDetail.php?view&pid=$pid'</script>";
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
							echo "<script>window.location.href='./displayresult.php?display&pid=$pid';</script>";
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
					Voting method: <span class="vdetail_colour"><?php echo strtoupper($row["vote_method"]); ?></span> Voting<br>
					Start at  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp : <?php echo $row["post_created_date"]; ?><br>
					Ended at  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp : <span class="vdetail_colour"><?php echo $row["post_expired_date"]; ?></span><br>
					Voting-mode &nbsp : <span class="vdetail_colour"><?php echo strtoupper($row["post_mode"]); ?></span>
					<?php if($row["vote_method"] == "multi" || $row["vote_method"] == "multi-point"){ ?>
					<br>Each voter can vote  : <span class="vdetail_colour"><?php echo $row["number_votes"]; ?></span> times
					<?php } ?>
				</p>
				<?php 
				$rowc = mysqli_fetch_assoc($resultc);
				$isvoted = mysqli_num_rows($resultc);
				$resultt = mysqli_query($link, "SELECT SUM(count) AS countsumt FROM countoption WHERE pid = $pid");
				$rowt = mysqli_fetch_assoc($resultt);
				$countsumt = $rowt["countsumt"];	
				
				if(($isvoted != 0 && $isvoted <= $row["number_votes"]) || $uid == $row["uid"]){
					$_SESSION["postaccess"] = true;
				} 
				
				if($row["post_mode"] == "private" && $uid != $row["uid"] && $_SESSION["postaccess"] !== true){ ?>
					<table cellpadding="0" cellspacing="0" border="1px solid black" class="showresult" style="margin:auto;">
						<form method="POST">
							<div class="accessform">
								<h2 class="accessform_title">Sorry</h2>
								<p><span>This is a private voting. Only organizer and voter can access view this result.<br>If you want to view the result, you can acces with the password.</span></p>
								<input type="text" required placeholder="Enter Vote Password" name="post_password" value="<?php echo isset($_COOKIE['post_password']) ? $post_password : '';?>"><br>
								<input type="submit" value="Access" name="access"><br><span><?php if(isset($post_password_error)){echo $post_password_error;}?></span>
							</div>
						</form>
					</table>
				<?php }else{ ?>
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
				
				
					$resultoption = mysqli_query($link, "SELECT * FROM optiontable where pid = $pid");
					while($rowo = mysqli_fetch_assoc($resultoption)){
						$oid = $rowo["oid"];
						$resultu = mysqli_query($link, "SELECT SUM(count) AS countsumu FROM countoption WHERE oid = $oid AND uid = $uid");
						$rowu = mysqli_fetch_assoc($resultu);
						$countsumu = $rowu["countsumu"];
						$resultv = mysqli_query($link, "SELECT SUM(count) AS countsumv FROM countoption WHERE oid = $oid");
						$rowv = mysqli_fetch_assoc($resultv);
						$countsumv = $rowv["countsumv"];
						if($highestcount < $countsumv){
							$highestcount = $countsumv;
							$highestrow = $rowo;
							$youvotethis = $countsumu;
						}
					}
					?>
					
					<p>
						<h1 style="text-align:center;">The Winner</h1>
						<?php if($highestcount != 0){ ?>
						<table border="1px solid black" class="showresult" style="margin:auto; text-align:center;">
							<tr>
								<th>Option</th>
								<th>Total votes</th>
								<?php if($row["uid"] != $uid){ ?><th>You vote for this option</th><?php } ?>
							</tr>
							<tr>
								<td><?php echo $highestrow["option_name"]; ?></td>
								<td><?php echo $highestcount; ?></td>
								<?php if($row["uid"] != $uid){ ?><td><?php if($youvotethis){echo $youvotethis;}else{echo 0;} ?></td><?php } ?>
							</tr>
						</table>
						<?php }else{ ?>
						<h2 style="text-align:center;">None</h2>
						<?php } ?>
					</p>
					<h3 style="text-align:center;">Voting details</h3>
					<?php $resultoption = mysqli_query($link, "SELECT * FROM optiontable where pid = $pid"); ?>
					<table cellpadding="0" cellspacing="0" border="1px solid black" class="showresult" style="margin:auto; width:85%;">
						<tr>
							<th colspan="2">Option</th>
							<th>Total Voted</th>
						</tr>
						<?php $ttlvote = 0;
						while($rowo = mysqli_fetch_assoc($resultoption)){ 
							$oid = $rowo["oid"];
							$resultu = mysqli_query($link, "SELECT SUM(count) AS countsumu FROM countoption WHERE oid = $oid AND uid = $uid");
							$rowu = mysqli_fetch_assoc($resultu);
							$countsumu = $rowu["countsumu"];
							$resultv = mysqli_query($link, "SELECT SUM(count) AS countsumv FROM countoption WHERE oid = $oid");
							$rowv = mysqli_fetch_assoc($resultv);
							$countsumv = $rowv["countsumv"];
							$ttlvote += $countsumv;
							if($countsumv > 0){ ?>
								<tr>
									<td colspan="2"><?php echo $rowo["option_name"]; ?><span style="float: right;"><?php echo sprintf("%.2f", $countsumv/$countsumt*100.00); ?>%</span></td>
								<?php if($rowo["option_desc"] != NULL || $rowo["option_image"] != NULL){ ?>
									<td rowspan="3" style="text-align:center;"><?php echo $countsumv; ?></td>
								</tr>
								<tr>
									<th style="width:40%;">Option Description</th>
									<th style="width:50%;">Option Image</th>
								</tr>
								<tr>
									<td><?php echo $rowo["option_desc"]; ?></td>
									<td>
										<?php if($rowo["option_image"] != null){
											$imageURL = './uploads/'.$rowo["option_image"];
											echo "<img width='100%' src='$imageURL'/>"; 
										} ?>
									</td>
								</tr>
								<?php }else{ ?>
									<td style="text-align:center;"><?php echo $countsumv; ?></td>
								</tr>
								<?php }
							}else{ ?>
								<tr>
									<td colspan="2"><?php echo $rowo["option_name"]; ?><span style="float: right;"><?php echo sprintf("%.2f", 0); ?>%</span></td>
								<?php if($rowo["option_desc"] != NULL || $rowo["option_image"] != NULL){ ?>
									<td rowspan="3" style="text-align:center;">0</td>
								</tr>
								<tr>
									<th style="width:40%;">Option Description</th>
									<th style="width:50%;">Option Image</th>
								</tr>
								<tr>
									<td><?php echo $rowo["option_desc"]; ?></td>
									<td>
										<?php if($rowo["option_image"] != null){
											$imageURL = './uploads/'.$rowo["option_image"];
											echo "<img width='100%' src='$imageURL'/>"; 
										} ?>
									</td>
								</tr>
								<?php }else{ ?>
									<td style="text-align:center;">0</td>
								</tr>
								<?php }
							} 
						} ?>
						<tr>
							<th colspan="2">Total Sum<span style="float: right;"><?php echo sprintf("%.2f", 100); ?>%</span></th>
							<th style="text-align:center;"><?php echo $ttlvote; ?></th>
					</table>
					<h3 style="text-align:center;">Other you voted options</h3>
					<?php $resultoption = mysqli_query($link, "SELECT * FROM optiontable where pid = $pid"); ?>
					<table cellpadding="0" cellspacing="0" border="1px solid black" class="showresult" style="margin:auto; width:85%;">
						<tr>
							<th>Option</th>
							<th>Total Voted</th>
							<th>You Voted</th>
						</tr>
						<?php
						$ttluvote = 0;
						while($rowo = mysqli_fetch_assoc($resultoption)){ 
							$oid = $rowo["oid"];
							$resultu = mysqli_query($link, "SELECT SUM(count) AS countsumu FROM countoption WHERE oid = $oid AND uid = $uid");
							$rowu = mysqli_fetch_assoc($resultu);
							$countsumu = $rowu["countsumu"];
							$resultv = mysqli_query($link, "SELECT SUM(count) AS countsumv FROM countoption WHERE oid = $oid");
							$rowv = mysqli_fetch_assoc($resultv);
							$countsumv = $rowv["countsumv"];
							$ttluvote += $countsumu;
							if($countsumu > 0){ ?>
								<tr>
									<td><?php echo $rowo["option_name"]; ?></td>
									<td style="text-align:center;"><?php echo $countsumv; ?></td>
									<td style="text-align:center;"><?php echo $countsumu; ?></td>
								<tr>
							<?php }
						}
						if($ttluvote != 0){ ?>
							<tr>
								<th colspan="2">Total you voted</th>
								<th style="text-align:center;"><?php echo $ttluvote; ?></th>
							<tr>
						<?php }else{
							if($uid == $row["uid"]){ ?>
								<tr>
									<th colspan="3">You are the organizer for this voting</th>
								</tr>
							<?php }else{ ?>
								<tr>
									<th colspan="3">You don't have become a voter for this voting</th>
								</tr>
							<?php }
						}?>
					</table>
					<div style="margin-left:23%;" id="piechart"></div>
					<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>			
					<script>
						
					// Load google charts
					google.charts.load('current', {'packages':['corechart']});
					google.charts.setOnLoadCallback(drawChart);

					// Draw the chart and set the chart values
					function drawChart() 
					{
						var data = google.visualization.arrayToDataTable([
						['Option Name', 'Number of Votes'],
							<?php 
							$resultoption=mysqli_query($link, "SELECT * FROM optiontable where pid = $pid");
							$oid = $rowo["oid"];

							while($rowoptionname = mysqli_fetch_assoc($resultoption)){ 
								$oid = $rowoptionname["oid"];
								$resultvote = mysqli_query($link, "SELECT SUM(count) AS countsumv FROM countoption where oid = $oid");
								$rowv = mysqli_fetch_assoc($resultvote);
								$countsumv = $rowv["countsumv"];
								?>
								<?php if($countsumv == 0) { ?> 
								['<?php echo $rowoptionname["option_name"]; ?>', 0],
								<?php } else {?>
								['<?php echo $rowoptionname["option_name"]; ?>', <?php echo $rowv["countsumv"]; ?>],
								<?php }?>
							<?php }?>
						]);

						// Optional; add a title and set the width and height of the chart
						var options = {'title':'Number vote of Option', 'width':750, 'height':500};

						// Display the chart inside the <div> element with id="piechart"
						var chart = new google.visualization.PieChart(document.getElementById('piechart'));
						chart.draw(data, options);
					}
					</script>
					<form method="post">
						<hr style="margin-left:5%; width:90%">
						<h3 style="text-align:center;">Comment</h3>
						<hr style="margin-left:10%; width:80%">
						<?php if($row["post_mode"] == "private" && $uid != $row["uid"] && $_SESSION["postaccess"] !== true){ ?>
							<div style="margin-left:10%; width:80%">
								<p>You don't have permission to read and comment.</p>
							</div>
						<?php }else{ ?>
							<div style="margin-left:10%; width:80%">
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
						} ?>
					</form>
				<?php } 
			}else if($row["post_status"] == "blocked"){ ?>
				<h2 style="text-align: center;">This voting has been blocked.</h2>
			<?php }else{ ?>
				<h2 style="text-align: center;">The voting post dosen't exists.</h2>
			<?php } ?>
		</fieldset>
	</body>
</html>
	<?php } ?>
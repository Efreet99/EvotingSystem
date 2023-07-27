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

    if(isset($_REQUEST['vote_detail'])){
        $pid = $_REQUEST["pid"];
    }
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$status = trim($_POST["status"]);
		
		mysqli_query($link, "UPDATE posts SET post_status='$status' WHERE pid=$pid");
		echo "<script>window.location.href='./detail_vote.php?vote_detail&pid=$pid'</script>";
		exit();
	}
?>

<html>
	<head>
		<title>Vote Detail</title>
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
					<a class="checkedclass" href="./list_vote.php">Vote</a>
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
			<a href="list_vote.php"><img src="../img/icon-back.png" alt="Back to main page" class="icon"></a>
			<h1>Detail of Vote</h1>
		</div>
        <fieldset class="adminlist">
		<form method="POST">
            <div class="votedetail_left">
                <?php
                    $result = mysqli_query($link, "SELECT * FROM posts, users WHERE posts.pid=$pid AND posts.uid=users.uid");
                    $row = mysqli_fetch_assoc($result);
                    echo "<br><b>Vote ID</b><br>";
                    echo $row["pid"]; 
                    echo "<br><br><b>Vote Title</b><br>"; ?>
                    <textarea name="posttitle" disabled><?php echo $row["post_title"]; ?></textarea>
                    <?php echo "<br><br><b>Vote Description</b><br>"; ?>
                    <textarea name="postdesc" rows="8" cols="28" disabled><?php echo $row["post_desc"]; ?></textarea>
                    <?php echo "<br><br><b>Vote Mode</b><br>";
					if($row["post_mode"] == "public"){?>
                    <input type="radio" name="votingmode" value="public" checked disabled>Public <input type="radio" name="votingmode" value="private" disabled>Private
                    <?php }else{ ?>
					<input type="radio" name="votingmode" value="public" disabled>Public <input type="radio" name="votingmode" value="private" checked disabled>Private
					<?php }if($row["post_mode"]=="private"){
                        echo "<br><br><b>Vote Password</b><br>"; ?>
                        <input type="text" name="post_pass" disabled value="<?php echo $row["post_password"]; ?>">
                    <?php }
					echo "<br><br><b>Post Status</b><br>"; ?>
					<select name="status" required><?php if($row['post_status'] == "approve"){ ?>
						<option value="approve" checked>approve<option value="blocked">blocked <?php }else if($row['post_status'] == "blocked"){ ?>
						<option value="blocked" checked>blocked<option value="approve">approve <?php } ?>
					</select>
					<input style="width:80%;" type="submit" value="Update post status">
                    <?php echo "<br><br><b>Vote Expired Date</b><br>"; ?>
					<input type="datetime-local" name="expireddatetime" disabled value="<?php echo $row["post_expired_date"]; ?>"">
                    <?php echo "<br><br><b>Vote Method</b><br>";
                    echo $row["vote_method"]; ?>
                    <?php if($row["vote_method"] != "single"){
						echo "<br><br><b>Number of Vote per User</b><br>"; ?>
						<input type="number" name="numbervotes" disabled value="<?php echo $row["number_votes"]; ?>">
                    <?php }
					echo "<br><br><b>Vote Organizer ID/Name</b><br>";
                    echo "<a href='./detail_user.php?user_detail&uid=".$row["uid"]."'>UID   :".$row["uid"]."<br>";
					echo "Username :".$row["username"]."<br></a>";
					echo "<br><br><b>This voting has been reported</b><br>";
					$resultreport = mysqli_query($link, "SELECT * FROM report WHERE reported_pid=$pid");
					$count = mysqli_num_rows($resultreport);
					echo "$count times<br>";
					if($count != 0){
						echo "They are:";
					}
					while($rowreport = mysqli_fetch_assoc($resultreport)){
						echo "<a href='./detail_report.php?report_detail&rid=".$rowreport["rid"]."'><br>Reported Reason   :<br>".$rowreport["report_reason"]."</a>";
					}
                ?>
            </div>
		</form>
            <div class="votedetail_right">
                <table cellpadding="0" cellspacing="0" border="1" id="optiontable" style="width:100%">
                <h2>Option Table</h2>
                    <tr>
                        <th style="text-align:center;">Option ID</th>
                        <th style="text-align:center;">Option Name</th>
                        <th style="text-align:center;">Option Description</th>
						<th style="text-align:center;">Option Total Votes</th>
                    </tr>
                    <?php
                        $resultoption = mysqli_query($link, "SELECT * from optiontable where pid=$pid;");
						$ttlcount = 0;
                        while($rowo = mysqli_fetch_assoc($resultoption)){
							$oid = $rowo["oid"];
							$resultcountsum = mysqli_query($link, "SELECT SUM(count) AS countsum FROM countoption WHERE oid=$oid");
							$rowcountsum = mysqli_fetch_assoc($resultcountsum);
							$countsum = $rowcountsum["countsum"];
							$ttlcount += $countsum;
                        ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $rowo["oid"]; ?></td>
                            <td><textarea style="width:100%; height:100%;" disabled><?php echo $rowo["option_name"]; ?></textarea></td>
                            <td><textarea style="width:100%; height:100%;" disabled><?php echo $rowo["option_desc"]; ?></textarea></td>
							<?php if($rowo["option_image"] != NULL){
								if(mysqli_num_rows($resultcountsum) > 0){ ?>
									<td rowspan="2" style="text-align:center;"><?php echo $countsum; ?></td>
								<?php }else{ ?>
									<td rowspan="2" style="text-align:center;">0</td>
								<?php } 
							}else{ 
								if(mysqli_num_rows($resultcountsum) > 0){ ?>
									<td style="text-align:center;"><?php echo $countsum; ?></td>
								<?php }else{ ?>
									<td style="text-align:center;">0</td>
								<?php } 
							} ?>
                        </tr>
						<?php
							if($rowo["option_image"] != NULL){
								$imageURL = '../uploads/'.$rowo["option_image"];
								echo "<tr><td>Option Image</td><td colspan='2'><img width='100%' src='$imageURL'/></td></tr>";
							}
                        }
                    ?>
						<tr>
							<th colspan="3" style="text-align:center;">Total Votes</th>
							<th style="text-align:center;"><?php echo $ttlcount; ?></th>
						</tr>
                </table>
            </div>
			<div class="votedetail_right">
			<table cellpadding="0" cellspacing="0" border="1" id="optiontable" style="width:100%">
                <h2>Comment</h2>
				<tr>
					<th style="text-align:center;">Comment ID</th>
					<th style="text-align:center;">Content</th>
					<th style="text-align:center;">Comment By</th>
					<th style="text-align:center;">Comment At</th>
				</tr>
			<?php $resultcom = mysqli_query($link, "SELECT * FROM comment, users WHERE pid=$pid AND comment.uid=users.uid ORDER BY comid DESC");
			if(mysqli_num_rows($resultcom) == 0){ ?>
				<tr><td colspan="4" style="text-align:center;">Don't have comment yet</td></tr>
			<?php }else{
				while($rowcom = mysqli_fetch_assoc($resultcom)){ ?>
				<tr>
					<td style="text-align:center;"><?php echo $rowcom["comid"]; ?></td>
					<td><textarea style="width:100%; height:100%;" disabled><?php echo $rowcom["comment_content"]; ?></textarea></td>
					<td style="text-align:center;"><a href="./detail_user.php?user_detail&uid=<?php echo $rowcom["uid"]; ?>"><?php echo $rowcom["uid"]; ?></a></td>
					<td style="text-align:center;"><?php echo $rowcom["comment_at"]; ?></td>
				<hr style="margin-left:10%; width:80%">
				</tr>
				<?php }
			} ?>
			</table>
			</div>
        </fieldset>
	</body>
</html>
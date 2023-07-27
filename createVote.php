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
	
	$post_title = $post_desc = $post_mode = $post_password = $expired_datetime = $vote_method = $number_votes = "";
	$title_error = $desc_error = $mode_error = $datetime_error = $method_error = $option_error = "";
	$currentDateTime = date('Y-m-d\TH:i',time()+43200);
	$now = $currentDateTime;

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$post_title = trim($_POST["posttitle"]);
		$post_desc = trim($_POST["postdesc"]);
		$expired_datetime = trim($_POST["expireddatetime"]);
		$post_status = "approve";

		if(isset($_POST["votingmode"])){
			$post_mode = trim($_POST["votingmode"]);
		}else{
			empty($post_mode);
		}

		if(isset($_POST["votingmethod"])){
			$vote_method = trim($_POST["votingmethod"]);
		}else{
			empty($vote_method);
		}

		if(empty($post_title)){
			$title_error = "Please enter the post title";
		}else{
			$title_error = "";
		}

		if(empty($post_desc)){
			$desc_error = "Please enter the post description";
		}else{
			$desc_error = "";
		}
		
		if(empty($post_mode)){
			$mode_error = "Please select the publish mode";
		}else{
			$mode_error = "";
		}

		if(empty($vote_method)){
			$method_error = "Please select the vote method";
		}else{
			$method_error = "";
		}
		
		if($vote_method == "multi" || $vote_method == "multi-point"){
			$number_votes = trim($_POST["numbervotes"]);
		}else{
			$number_votes = 1;
		}

		if(empty($expired_datetime)){
			$datetime_error = "Please select the post expired date and time";
		}else{
			$datetime_error = "";
		}
		
		$o_image = array();
		$items = array();
		
		foreach ('option'.'o_desc'.(basename($_FILES["o_image"]["name"])) as $pos) {
			foreach ($_POST[$pos] as $id => $row) {
				$_POST[$pos][$id] = mysqli_real_escape_string($link, $row);
			}
		}
		
		$options = $_POST['option'];
		$o_descs = $_POST['o_desc'];
		$size = count($options);
		
		if($number_votes != 1 && $vote_method == "multi"){
			if($size <= $number_votes){
				$option_error = "Option must more than votes for each people.";
			}else{
				$option_error = "";
			}
		}else if($number_votes == 1 && $vote_method == "single"){
			if($size < 2){
				$option_error = "Option must at least two.";
			}else{
				$option_error = "";
			}
		}else{
			$option_error = "";
		}
		
		if(empty($title_error) && empty($mode_error) && empty($method_error) && empty($datetime_error) && empty($option_error)){
			
			$post_password_hash = "";
			
			if($post_mode == "private"){
				$post_password = trim(passwordGenerate(6));
			}else{
				empty($post_password);
			}
			
			if($stmt = mysqli_prepare($link, "INSERT INTO posts (post_title, post_desc, post_mode, post_password, post_status, post_expired_date, vote_method, number_votes, uid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)")){
				mysqli_stmt_bind_param($stmt, "sssssssii", $post_title, $post_desc, $post_mode, $post_password, $post_status, $expired_datetime, $vote_method, $number_votes, $uid);
				
				if(mysqli_stmt_execute($stmt)){
					$pid = mysqli_stmt_insert_id($stmt);
					$targetDir = "./uploads/";
					
					for($i = 0 ; $i < $size ; $i++){
						if($options[$i] != null){
							$fileName = basename($_FILES["o_image"]["name"][$i]);
							$filePath = $targetDir . $fileName;
							$fileType = pathinfo($filePath,PATHINFO_EXTENSION);
							$allowTypes = array('jpg','png','jpeg','gif','webp');
							$newFileName = idate("U")."_".uniqid().".".$fileType;
							$newFilePath = $targetDir.$newFileName;
							
							if(!empty($_FILES["o_image"]["name"][$i])){
								if(in_array($fileType, $allowTypes)){
									move_uploaded_file($_FILES["o_image"]["tmp_name"][$i], $newFilePath);
									$o_image[$i] = $newFileName;
								}
							}
							
							$items[$i] = array(
								"option" => $options[$i], 
								"o_desc" => $o_descs[$i],
								"o_image" => $o_image[$i]
							);
						}else{
							$size--;
						}
					}

					if (!empty($items)) {   
						$values = array();
						foreach($items as $item){
							$values[] = "($pid, '{$item['option']}', '{$item['o_desc']}', '{$item['o_image']}')";
						}
					
						$values = implode(", ", $values);
					
						$sql = "INSERT INTO optiontable (pid, option_name, option_desc, option_image) VALUES {$values} ";

						$result = mysqli_query($link, $sql);
						if ($result) {
							echo "<script>alert('Your vote has been create.');window.location.href='./voteDetail.php?view&pid=$pid';</script>";
							exit();
						} else {
							echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
						}
					}
				}else{ ?>
					<script>alert("Oops! Something went wrong. Please try again later.");</script>
				<?php }
				mysqli_stmt_close($stmt);
			}
		}
		mysqli_close($link);
	}
	
	function passwordGenerate($length){
		$cha = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$chalen = strlen($cha);
		$ran = "";
		for($i = 0; $i < $length; $i++){
			$ran .= $cha[rand(0, $chalen-1)];
		}
		return $ran;
	}
	date_default_timezone_set("Asia/Kuala_Lumpur");
	$currentDateTime = date('Y-m-d\TH:i:s',time()+43200);
	$now = $currentDateTime;
?>

<html>
	<head>
		<title>Create voting Vote</title>

		<link rel="stylesheet" type="text/css" href="./css/navigation.css">
		<link rel="stylesheet" type="text/css" href="./css/style.css">
	</head>
	
	<body class="body2">
		<ul>
			<li><a href="./index.php">X-Voter</a></li>
			<li class="dropdown"><a class="checkedclass" href="#">As Organizer</a>
				<div class="dropdown-content">
					<a class="checkedclass" href="./createVote.php">Create vote</a>
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
			<a href="index.php"><img src="./img/icon-back.png" alt="Back to main page" class="icon"></a>
			<h1>Create Vote</h1>
		</div>
		
		<fieldset>
			<form class="voteform" method="POST" enctype="multipart/form-data">
				<p>
					Title<span>*</span>: <br><input type="text" style="margin-bottom: 8px;" name="posttitle" required placeholder="Maximum 100 characters." >
					<span>
						<?php if(isset($title_error)){echo $title_error;}?>
					</span>
				</p>

				<p>
					Description<span>*</span>:<br><textarea rows="4" cols="75" name="postdesc" placeholder="Maximum 300 characters"></textarea>
					<span>
						<?php if(isset($desc_error)){echo $desc_error;}?>
					</span>
				</p>
	
				<p>
					Voting-mode<span>*</span>: 	
					<br><input type="radio" name="votingmode" onclick="primode()" value="public">Public
					<br><input type="radio" name="votingmode" onclick="pubmode()" value="private">Private<br>
					<span id="changemode">
						<?php if(isset($mode_error)){echo $mode_error;}?>
					</span>
				</p>

				<p>
					Voting expired at<span>*</span>: <br>
					<input type="datetime-local" name="expireddatetime" value="<?php echo $currentDateTime ; ?>" min="<?php echo $now ; ?>"><br>
					<span>
						Vote period must at least 12 hour.
					</span>
				</p>

				<p>
					Voting method<span>*</span>: <br>
					<select style="width:200px;" name="votingmethod" onchange="votemethod(value)"><br>
						<option value="">--</option>
						<option value="single">Single Vote</option>
						<option value="multiple">Mutliple Vote</option>
						<option value="multi-point">Multi-point</option>
					</select><br>
					<span id="changemethod">
						Please select a vote method.
						<?php if(isset($method_error)){echo $method_error;} ?>
					</span>
				</p>

				<p style="clear:both;">
					<table border="1" id="selectiont" width="100%">
						Option<span>*</span>:<br>
						<tr>
							<th width="20%">Option</th>
							<th>Option Description</th>
							<th>Option Image</th>
						</tr>
						<tr>
							<td><input name="option[]" id="option" style="width:100%" type="text" required></td>
							<td><input name="o_desc[]" id="o_desc" style="width:100%" type="text"></td>
							<td><input name="o_image[]" id="o_image" style="width:100%" type="file"></td>
						</tr>

						<tr>
							<td><input name="option[]" id="option" style="width:100%" type="text" required></td>
							<td><input name="o_desc[]" id="o_desc" style="width:100%" type="text"></td>
							<td><input name="o_image[]" id="o_image" style="width:100%" type="file"></td>
						</tr>
					</table>
					<span>
						<?php if(isset($option_error)){echo $option_error;}?>
					</span>
					<input type="button" onclick="createSel()" style="margin-left:10%;margin-top:20px;margin-bottom:-7px;color:tomato" value="Add Option">
				</p>
				<p><input style="margin-left:10%;color:tomato" type="submit" value="Create post"><br><input style="background-color: #A9B2B1;margin-left:10%;" type="reset" value="Reset"></p>
			</form>
			<script>
				function primode(){
					document.getElementById("changemode").innerHTML="Publish to all users.";
				}
				function pubmode(){
					document.getElementById("changemode").innerHTML="Publish to users who know the password.";
				}

				function votemethod($value){       
					if($value=="single"){
						document.getElementById("changemethod").innerHTML="Each voter can only vote for one option once time.";
					}else if($value=="multiple"){
						document.getElementById("changemethod").innerHTML='Each voter can only vote for a few options once time.<p>How many times each voter can votes: <input type="number" name="numbervotes" required min="2" max="99"><br>Maximum is 99.<?php if(isset($method_error)){echo $method_error;} ?></p>';
					}else if($value=="multi-point"){
						document.getElementById("changemethod").innerHTML='Every voter can vote for each option several times.<p>How many times each voter can votes: <input type="number" name="numbervotes" required min="2" max="99"><br>Maximum is 99.<?php if(isset($method_error)){echo $method_error;} ?></p>';
					}else{
						document.getElementById("changemethod").innerHTML="Please select a vote method.";
					}
				}

				function createSel(){
					var table = document.getElementById("selectiont");
					var row = table.insertRow();
					var cell1 = row.insertCell(0);
					var cell2 = row.insertCell(1);
					var cell3 = row.insertCell(2);
					cell1.innerHTML = '<input name="option[]" id="option" style="width:100%" type="text">';
					cell2.innerHTML = '<input name="o_desc[]" id="o_desc" style="width:100%" type="text">';
					cell3.innerHTML = '<input name="o_image[]" id="o_image" style="width:100%" type="file">';
				}
			</script>
		</fieldset>
	</body>
</html>
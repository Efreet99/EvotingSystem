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
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	
	require '../vendor/autoload.php';

    if(isset($_REQUEST['message_detail'])){
        $mid = $_REQUEST["mid"];
    }
?>

<html>
	<head>
		<title>Message Detail</title>
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
					<a href="./list_report.php">Report</a>
					<a class="checkedclass" href="./list_message.php">Message</a>
				</div>
			</li>
			<li class="dropdown" style="float:right; margin-right:50px; width:5%;"><a class="hoverclass" href="./detail_admin.php?admin_detail&caid=<?php echo $aid; ?>"><?php echo $adminname; ?></a>
				<div class="dropdown-content" style="float:left">
					<a href="../logout.php">Sign out</a>
				</div>
			</li>
        </ul>

        <div class="titlebox">
			<a href="./list_message.php"><img src="../img/icon-back.png" alt="Back to main page" class="icon"></a>
			<h1>Detail of Message</h1>
		</div>
        <fieldset class="adminlist">
		<form method="POST">
			<?php
				$result = mysqli_query($link, "SELECT * FROM message WHERE mid=$mid");
				$row = mysqli_fetch_assoc($result);
				if(isset($_POST["reply"])){
					$reply = trim($_POST["reply"]);
					
					$mail = new PHPMailer(true);
					
					$mail->isSMTP();
					$mail->Host       = 'smtp.gmail.com';
					$mail->SMTPAuth   = true;
					$mail->Username   = 'xvoter.operation@gmail.com';
					$mail->Password   = 'X-voter210202';
					$mail->Port       = 587;
					
					$mail->setFrom('xvoter.operation@gmail.com', 'X-Voter');
					$mail->addAddress($row["message_email"]);
					
					$body = "<br>$reply<br>";
					
					$mail->isHTML(true); 
					$mail->Subject = 'Reply from X-Voter';
					$mail->Body    = $body;
					$mail->AltBody = strip_tags($body);
					
					if($mail->send()){
						if(mysqli_query($link, "UPDATE message SET message_status='replied', replied_message='$reply' WHERE mid=$mid;")){
							echo "<script>alert('You have reply the message.'); window.location.href='./list_message.php';</script>";
							exit();
						}else{ ?>
							<script>alert("Oops! Something went wrong. Please try again later.");</script>
						<?php }
					}
				}
				if(isset($_REQUEST["discard"])){
					$mid = $_REQUEST["mid"];
					
					if(mysqli_query($link, "UPDATE message SET message_status='discarded', replied_message='-' WHERE mid=$mid;")){
						echo "<script>alert('You have discard the message.'); window.location.href='./list_message.php';</script>";
						exit();
					}else{ ?>
						<script>alert("Oops! Something went wrong. Please try again later.");</script>
					<?php }
				}
				echo "<br><b>Message ID</b><br>";
				echo $row["mid"]; 
				echo "<br><br><b>Email</b><br>"; 
				echo $row["message_email"];
				echo "<br><br><b>Message Content</b><br>"; 
				echo $row["message_content"];
				echo "<br><br><b>Message Send At</b><br>"; 
				echo $row["message_send_at"];
				echo "<br><br><b>Status</b><br>";
				if($row["message_status"] == "pending"){
					echo "<span style='color:#ff9933;'>".$row["message_status"]."</span><br><br>";?>
					<textarea style='width:98%;' required placeholder='Reply the message' name='reply'></textarea><br><br>
				<?php }else if($row["message_status"] == "replied"){
					echo "<span style='color:#00e6ac;'>".$row["message_status"]."</span><br><br>";
					echo "<b>Replied Message</b><br>";
					echo $row["replied_message"];
				}else if($row["message_status"] == "discarded"){
					echo "<span>".$row["message_status"]."</span><br><br>";
				}
			if($row["message_status"] == "pending"){ ?>
				<input type='submit' value='Reply'><br><br>
				<a href="./detail_message.php?discard&mid=<?php echo $mid; ?>" onclick="return Discard();"><input type='button' name='discard' value='Discard this message'></a>
			<?php } ?>
			
		</form>
        </fieldset>
		<script>
			function Discard(){
				con = confirm("Do you sure want to discard this message?");
				return con;
			}
		</script>
	</body>
</html>
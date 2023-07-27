<?php 
	require_once "./link.php";
	
	session_start();
?>

<html>
	<head>
		<title>X-Voter</title>
		
		<link rel="stylesheet" type="text/css" href="./css/style.css">
		<link rel="stylesheet" type="text/css" href="./css/navigation.css">
		<link rel="icon" href="./avatar.png" type="image" sizes="16x16">
	</head>

	<?php if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){ ?>
		<body class="body2">
			<ul>
				<li><a style="color:#5e5e5e" href="./index.php">X-Voter</a></li>
				<li><a style="color:#5e5e5e" class="hoverclass" href="./aboutus.html">About Us</a></li>
				<li><a style="color:#5e5e5e" class="hoverclass" href="./contactus.php">Contact Us</a></li>
				<li style="float:right;"><a class="hoverclass" href="./login.php">Sign In</a></li>
				<li style="float:right;"><a class="hoverclass" href="./emailverify.php">Register</a></li>
			</ul>
			
			<div style="margin-top:100px; text-align:center;">
				
			</div>
			
			<div class="centerbox">
				<fieldset style="width:100%;margin-left:0%; ">
					<h1 class="indexxtitle" style="color:#AC3B61">Hi, Welcome to X-Voter.</h1>
					<h2 class="indexsubtitle">X-Voter is an online voting has a template of vote that can help you create a election more faster.</h2>
					<form>
						<p><a href="./emailverify.php"><input style="background-color:#BAB2B5;" type="button" value="Start Now"></a></p>
					</form>
					<h2 class="indexsubtitle">OR you can</h2>	
					<form>
						<p><a href="./login.php"><input style="background-color:#BAB2B5;" type="button" value="Sign In"></a></p>
					</form>

					<div class="container">
						<div class="smallcontainer">
							<img src="img/index1.png" >
							<h3>To reduce the error of the same voter voting more than once</h3>
							<p>All voters who vote will be submitted to the database. Therefore, this ensures that the same voter cannot vote multiple times in the same election.</p>
						</div>

						<div class="smallcontainer">
							<img src="img/index2.png" >
							<h3>To reduce error of vote counting</h3>
							<p>All vote will be submitted to the database to find out who has the most votes. This can minimize errors caused by manual counting.</p>
						</div>

						<div class="smallcontainer">
							<img src="img/index3.png">
							<h3>To improve the user needs</h3>
							<p>Add more diversified voting methods to meet the needs of more users.</p>
						</div>
					</div>
				</fieldset>
			</div>
		</body>

	
	<?php }else{
		$_SESSION["postaccess"] = false;
		if(isset($_SESSION["admin"]) && $_SESSION["admin"] === true){
			header("location: ./admin/admin_index.php");
			exit;
		}else{
			$uid = $_SESSION["uid"];
			$username = $_SESSION["username"];
			$_SESSION["postaccess"] = false;
			
			$result = mysqli_query($link, "SELECT * FROM users WHERE uid=$uid");
			$row = mysqli_fetch_assoc($result);
			?>
			<body class="body2">
				<ul>
			<li><a style="color:#5e5e5e" href="./index.php">X-Voter</a></li>
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

				<div style="margin-top:100px; text-align:center;">
					<p><h1>Hi, <b><?php echo $username; ?></b>. Welcome to our site.</h1>
				</div>

				<div style="margin-left:20%; float:left">
					<div class="usertutor">
						<p class="tutortitle"><b>Start as Organizer</b></p>
						<p class="tutorsubtitle">You can simply create an election for yourself, your company or your society.</p>
						<form>
							<p><a href="./createVote.php"><input style="background-color:#BAB2B5;" type="button" value="Create Now"></a></p>
						</form>	
					</div>
				</div>
				<div style="margin-right:20%; float:right">
					<div class="usertutor">
						<p class="tutortitle"><b>Start as Voter</b></p>
						<p class="tutorsubtitle">You can vote on options you’re interested in faster and easier.</p>
						<form>
							<p><a href="./asvoter.php"><input style="margin-top:25px;background-color:#BAB2B5;" type="button" value="Vote Now"></a></p>
						</form>	
					</div>
				</div>
			</body>
		<?php }
	} ?>
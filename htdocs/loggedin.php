<?php
include_once('src/db.php');

	if(!empty($_GET["username"]) && !empty($_GET["access_token"]) && !empty($_GET["expires_in"]))
	{
		$expiresIn = $_GET["expires_in"];
		setcookie("username", $_GET["username"], time()+$expiresIn, "/");
		setcookie("access_token", $_GET["access_token"], time()+$expiresIn, "/");
		setcookie("expires_in", $expiresIn, time()+$expiresIn, "/");
	}
	
	$token = password_hash($_GET['access_token'], PASSWORD_DEFAULT);
	
	$query = $db->prepare('SELECT * FROM users WHERE username = ?');
	$query->bind_param('s', $_GET['username']);
	
	$query->execute();
	
	$result = $query->get_result();
	if(!$result->num_rows) {
		$query = $db->prepare('INSERT INTO users (username, token) VALUES (?, ?)');
		$query->bind_param('ss', $_GET['username'], $token);
	
		$query->execute();
	} else {
		$query = $db->prepare('UPDATE users SET token = ? WHERE username = ?');
		$query->bind_param('ss', $token, $_GET['username']);
		
		$query->execute();
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include("src/head.php"); ?>
	</head>
	<body>
		<?php include ("navbar.php"); ?>
		<center><p>
			You will be redirected automatically in 3 seconds.<br/>
			If not <a href="index.php">click here</a>.
		</p></center>

		<script>
			setTimeout(function(){$(location).attr('href', 'index.php');}, 3000);
		</script>

	</body>
</html>
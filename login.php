<?php
session_start();
?>
<html lang="en">
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="wall.css">
</head>
<body id="body_login">
	<div class="container_login">
		<h1>The Wall</h1>
		<div class="login_box">
			<div id='er'>
				<?php
				if(isset($_SESSION['error']))
				{
					?>
					<p><?= $_SESSION['error']['message'] ?></p>
					<?php 
					unset($_SESSION['error']); 
					?>
					<?php
				}
				?>
			</div>
			<div id="login_log">
				<form action="process.php" method="post" > 
					<input type="hidden" name="action" value="login">
					<input type="text" name="email" placeholder="Enter Email">
					<input type="password" name="password" placeholder="Password">
					<input type="submit" name="Register" id="but">
				</form>
				<label id='log_lab'>New User? <a href="index.php"> Register here</a></label>
			</div>
		</div>
	</div>
</body>
</html>
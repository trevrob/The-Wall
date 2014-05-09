<?php 
session_start();
?>
<html>
<head>
	<title>New User Registration Form</title>
	<link rel="stylesheet" type="text/css" href="wall.css">
</head>
<body id='user_reg_bod'>
	<div class='wrapper'>
		<h1 class= 'reg_fo'> New User Registration </h1>
		<div class= 'container_reg'>
			<div id="user_reg_form">
				<form id ='reg_form' action="process.php" method="post" enctype="multipart/form-data"> 
					<input type="hidden" name="action" value="register">
					<input type="text" name="first_name" placeholder="Enter First Name"><br>
					<input type="text" name="last_name" placeholder="Enter Last Name"><br>
					<input type="text" name="email" placeholder="Enter Email"><br>
					<input type="password" name="password" placeholder="Password"><br>
					<input type="password" name="confirm_password" placeholder="Confirm Password"><br>
					<input type="text" name="birthdate" placeholder="Enter Birthday MM/DD/YYYY"><br>
					<input type="file" name="file"><br>
					<input type="submit" name="Register" id="sub">
				</form>
			</div>
			<div id='error_mes'>
				<?php
				if(isset($_SESSION['error']))
				{
					foreach($_SESSION['error'] as $name => $message)
					{
						?>
						<p id="reg"><?=$message ?></p>
						<?php

					}
				}
				elseif (isset($_SESSION['success_message'])) 
				{
					?>
					<p id="reg"><?=$_SESSION['success_message'] ?></p>
					<?php

				}
				?>
			</div>
		</div>
	</div>
</body>
</html>
<?php 
$_SESSION = array();
?>
<?php
session_start();
require_once('new_connection.php');
?>
<!doctype html>
<html lang ="en">
<head>
	<meta charset="UTF-8">
	<title>Profile</title>
	<link rel="stylesheet" type="text/css" href="wall.css">
</head>
<body id= 'body_profile'>
	<div class="container_profile">
		<?php
		$query = "SELECT users.first_name, users.last_name, users.email, users.file_path, users.birthdate 
		FROM users 
		WHERE id = ".$_SESSION['user_id'];
		$result = run_mysql_query($query);
		$row = fetch_record($query);
		?>
		<?php
		if(isset($_SESSION['user_id']))
			{
			?>
			<div id='head'> 
				<label id="the_wall">Welcome, <?= $row['first_name']. ' ' . $row['last_name'].' '?></label>
				<a href="process.php?logout=1"> Logout</a>
			</div>
			<?php
			}
			?>
		<div id="body">
			<div id="body_title">
				<b>Email:</b> <label id='person_email' style='display:inline-block'><?= $row['email']?> | </label>
				<b>DOB: </b><label id='person_birth'><?=date('M d, Y', strtotime($row['birthdate']))?></label>
			</div>
			<div id='body_pic'>
				<img width= '200' height='200' src="<?=$row['file_path'] ?>">
			</div>
		</div>
		<div id ='body1'>
			<form action="process.php" method="post" enctype="multipart/form-data"> 
				<input type="hidden" name="action" value="message">
				<textarea rows="3" cols="132" name = "content" placeholder="Enter your message here....."></textarea><br>
				<input type="submit" name="post_message" id="pos">
			</form>
			
		</div>	
		<div>
			<?php
			$query1 = "SELECT messages.message AS mes, messages.created_at AS mes_time, messages.user_id, users.first_name AS 
						mes_first, users.last_name AS mes_last, messages.id
						FROM messages LEFT JOIN users ON users.id = messages.user_id
						-- WHERE {$_SESSION['user_id']} = messages.user_id 
					    ORDER BY messages.created_at DESC";
			$message_result = fetch_all($query1);


			foreach ($message_result as $log) 
			{
			echo 
			"<div class = 'wall'>
				<div class= 'message_div'>
					<label class = 'message_name'> {$log['mes_first']} {$log['mes_last']} </label>
					<label class = 'message_time'> {$log['mes_time']} </label>
					<p class = 'message_body'> {$log['mes']} </p>
				</div>
				<div class='comment_div'>";

				$query2 = "SELECT comments.comment AS com, comments.created_at AS com_time, comments.user_id, comments.message_id AS 
							mes_id, users.first_name AS com_first,users.last_name AS com_last
							FROM comments LEFT JOIN users ON users.id = comments.user_id
							WHERE {$log['id']} = comments.message_id
							ORDER BY comments.created_at ASC";
	
				$comment_result = fetch_all($query2);
				// var_dump($comment_result);
				foreach ($comment_result as $log2) 
				{
					echo "<div class = 'comments_posted'>
							<label class = 'com_name'> {$log2['com_first']} {$log2['com_last']} </label>
							<label class = 'comment_time'> {$log2['com_time']} </label>
							<p = class='com_post'> {$log2['com']} </p>
						</div>";
				}
				

					echo"<form id ='form2' action='process.php' method='post' enctype='multipart/form-data'> 
							<input type='hidden' name='action' value='comment'>
							<input type='hidden' name='message_id' value='{$log['id']}'>
							<textarea rows='2' cols='155' name = 'content' placeholder='Enter your comment here.....''></textarea><br>
							<input type='submit'>
						</form>
				</div>
			</div>";
			}
			?>

		</div>
	</div>
</body>
</html>
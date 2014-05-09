<?php 
session_start();
require_once('new_connection.php');

	if (isset($_POST['action']) && $_POST['action'] == 'register')
	{
		register($_POST);
	}
	elseif(isset($_POST['action']) && $_POST['action'] == 'login')
	{
		login($_POST);
	}
	elseif (isset($_POST['action']) && $_POST['action'] == 'message' )  
	{
		messages($_POST);
	}
	elseif (isset($_POST['action']) && $_POST['action'] == 'comment' )  
	{
		comments($_POST);
	}
	elseif(isset($_GET['logout']))
	{
		logout();
		header('Location: login.php');
		exit;
	}	

	function login($post)
	{
		if(empty($post['email']) || empty($post['password']))
		{
			$_SESSION['error']['message'] = "Email or Password cannot be blank";
		}
		else
		{
			$query = "SELECT id, password FROM users WHERE email = '{$post['email']}'";
			$result = run_mysql_query($query);
			$row = fetch_record($query);

			if(empty($row))
			{
				$_SESSION['error']['message'] = 'Could not find Email in database';
			}
			else
			{
				if(crypt($post['password'], $row['password']) != $row['password'])
				{
					$_SESSION['error']['message'] = 'Incorrect Password';
				}
				else
				{
					$_SESSION['user_id'] = $row['id'];
					header('Location: profile.php?id='.$row['id']);
					exit;
				}
			}
		}
		header('Location: login.php');
		exit;
	}

	function logout()
	{
		$_SESSION = array();
		session_destroy();
	}

	function register($post)
	{
		foreach ($post as $name => $value)
		{
			if(empty($value))
			{
				$_SESSION['error'][$name] = "sorry, " . $name . " cannot be blank";
			}
			else
			{
				switch($name)
				{
					case 'first_name':
					case 'last_name':
						if(is_numeric($value))
						{
							$_SESSION['error'][$name] = $name . ' cannot contain numbers';
						}
					break;
					case 'email':
						if(!filter_var($value, FILTER_VALIDATE_EMAIL))
						{
							$_SESSION['error'][$name] = $name . ' is not a valid email';
						}
					break;
					case 'password':
						$password = $value;
						if(strlen($value) < 5)
						{
							$_SESSION['error'][$name] = $name . ' must be greater that 5 characters';
						}
					break;
					case 'confirm_password':
						if($password != $value)
						{
							$_SESSION['error'][$name] = $name . ' Passwords do not match';
						}
					break;
					case 'birthdate':
						$birthdate = explode('/', $value);
						if(!checkdate($birthdate[0], $birthdate[1], $birthdate[2]))
						{
							$_SESSION['error'][$name] = $name . ' is not a valid date';
						}
					break;
				}
			}
		}
		if($_FILES['file']['error'] > 0 )
		{
			$_SESSION['error']['file'] = "Error on file upload Return Code: " .$_FILES['file']['error'];
		}
		else
		{
			$directory = 'uploaded_pics';
			$file_name = $_FILES['file']['name'];
			$file_path = $directory.$file_name;
			if(file_exists($file_path))
			{
				$_SESSION['error']['file'] = $file_name. ' already exists';
			}
			else
			{
				if(!move_uploaded_file($_FILES['file']['tmp_name'], $file_path))
				{
					$_SESSION['error']['file'] = $file_name. ' could not be saved';
				}
			}
		}

		if(!isset($_SESSION['error']))
		{
			$_SESSION['success_message'] = "Congratulations you are now a member!";

			$salt = bin2hex(openssl_random_pseudo_bytes(22));
			$hash = crypt($post['password'], $salt);

			$f_birthdate = $birthdate[2]. '-' .$birthdate[0]. '-' . $birthdate[1];
			$query = "INSERT INTO users (first_name, last_name, email, password, birthdate, file_path, created_at, updated_at) 
					VALUES('{$post['first_name']}','{$post['last_name']}','{$post['email']}', '{$hash}','{$f_birthdate}',
					 '{$file_path}', NOW(), NOW())";
			
			run_mysql_query($query);

			$user_id = get_insertion_id();

			$_SESSION['user_id'] = $user_id;

			header('Location: profile.php?id='.$user_id);
			exit;
		}
	}

	function messages($post)
	{
		$user_id = $_SESSION['user_id'] ;
		$query1 = "INSERT INTO messages (message, created_at, updated_at, user_id) VALUES('{$post['content']}', NOW(), NOW(), {$user_id})" ;
		run_mysql_query($query1);
		$user_id = get_insertion_id();
		header('Location: profile.php?id='.$user_id);
		exit;
	}
	function comments($post)
	{
		$user_id = $_SESSION['user_id'] ;
		$id = $_POST['message_id'];
		$query = "INSERT INTO comments (comment, created_at, updated_at, user_id, message_id) VALUES('{$post['content']}', NOW(), NOW(), 
			{$user_id},{$id})" ;
		// echo $query; die();
		run_mysql_query($query);
		// $user_id = get_insertion_id();
		header('Location: profile.php?id='.$user_id);
		exit;
	}
	header('Location: index.php');
	exit;


?>
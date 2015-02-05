<?php
	include_once('core.start.php');
	
	if(empty($_POST['email'])) {
		$errors[] = 'Field "email" is empty.';
	} elseif(empty($_POST['password'])) {
		$errors[] = 'Field "password" is empty.';
	}
	
	$stmt =  $sql->stmt_init();
	
	if ($stmt = $sql->prepare("SELECT id, username FROM user_entity WHERE email=? and password=?")) {
		$stmt->bind_param("ss", $_POST['email'], $_POST['password']);
		
    $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($id, $name);
		$stmt->fetch();
		
		if($stmt->num_rows == 1) {
			$_SESSION['user'] = array(
				'id' => $id,
				'name' => $name,
				'email' => $_POST['email']
			);
			
			$redirect = 'index.html';
		} else {
			$errors[] = 'Account not found.';
		}
		
    $stmt->close();
	} else {
		$errors[] = 'Internal Server Error. Please try later.';
	}

	include_once('core.end.php');
?>
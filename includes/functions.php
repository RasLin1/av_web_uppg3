<?php

function cleanInput($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function login($pdo){
	$stmt_checkIfUserExists = $pdo->prepare("SELECT * FROM project_workers WHERE worker_username = :uname");
	$stmt_checkIfUserExists->bindValue(":uname", $_POST['uname'], PDO::PARAM_STR);
	$stmt_checkIfUserExists->execute();
	//Creates an array for the selected data
	$userData = $stmt_checkIfUserExists->fetch();
	
	if(!$userData){
		$errorMessages = "No such user in database.";
		$errorState = 1;
		return "falsen";
	}
	
	//checks that the passwords match
	elseif($userData){
	   $checkPasswordMatch = password_verify($_POST['u_pass'], $userData['worker_password']);
	}

	   if($checkPasswordMatch == true) {
			$_SESSION['uname'] = $userData['worker_username'];
			$_SESSION['urole'] = $userData['worker_role_fk'];
			$_SESSION['uid'] = $userData['worker_id'];
			echo "success!";
	   } 
	   else {
		  $errorMessages = "INVALID password";     
		  return "falsep";
	   }
}

function register($pdo){
	$regUserName = cleanInput($_POST['u_name']);
	//encrypts the password with password_hash()
	$encryptedPassword = password_hash($_POST['u_pass'], PASSWORD_DEFAULT);

	$stmt_registerUser = $pdo->prepare('INSERT INTO project_workers(worker_username, worker_password, worker_role_fk)values(:uname, :upass, :urole)');
	$stmt_registerUser->bindParam(":uname" ,$regUserName, PDO::PARAM_STR);
	$stmt_registerUser->bindParam(":upass" ,$encryptedPassword, PDO::PARAM_STR);
	$stmt_registerUser->bindParam(":urole" ,$_POST['user_role'], PDO::PARAM_STR);

	if($stmt_registerUser->execute()){
		header("Location: index.php?newuser=1");
	}
	else{
		return "Something went wrong";
	}
}


?>
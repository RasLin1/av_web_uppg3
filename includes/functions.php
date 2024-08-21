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
			return TRUE;
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

function newCustomer($pdo){
	$cleanFname = cleanInput($_SESSION['cus_fname']);
	$cleanLname = cleanInput($_SESSION['cus_lname']);

	$stmt_newCustomer = $pdo->prepare('INSERT INTO project_customers(cust_fname, cust_lname, cust_phone)values(:fname, :lname, :phone)');
	$stmt_newCustomer->bindParam(":fname" ,$cleanFname, PDO::PARAM_STR);
	$stmt_newCustomer->bindParam(":lname" ,$cleanLname, PDO::PARAM_STR);
	$stmt_newCustomer->bindParam(":phone" ,$_SESSION['cus_phone'], PDO::PARAM_STR);
	if($stmt_newCustomer->execute()){
		return "Successfully Inserted Customer";
	}
	else{
		return "ERROR";
	}
}

function newCar($pdo){
	$cleanLicense = cleanInput($_SESSION['car_license']);
	$cleanBrand = cleanInput($_SESSION['car_brand']);
	$CleanModel = cleanInput($_SESSION['car_model']);

	$stmt_newCar = $pdo->prepare('INSERT INTO project_cars(project_car_license, project_car_brand, project_car_model)values(:license, :brand, :model)');
	$stmt_newCar->bindParam(":license" ,$cleanLicense, PDO::PARAM_STR);
	$stmt_newCar->bindParam(":brand" ,$cleanBrand, PDO::PARAM_STR);
	$stmt_newCar->bindParam(":model" ,$CleanModel, PDO::PARAM_STR);
	if($stmt_newCar->execute()){
		return "Successfully Inserted Car";
	}
	else{
		return "ERROR";
	}
}

function newProject($pdo){
	$cleanProbDesc = cleanInput($_SESSION['probDesc']);
	$projStat = "1";
	$projWorker = "1";

	$stmt_newProj = $pdo->prepare('INSERT INTO projects(project_car_fk, project_customer_fk, project_problem_description, project_worker_fk , project_status_fk)values(:car, :cus, :prob, :worker, :stat)');
	$stmt_newProj->bindParam(":car", $_SESSION['car'], PDO::PARAM_INT);
	$stmt_newProj->bindParam(":cus", $_SESSION['customer'], PDO::PARAM_INT);
	$stmt_newProj->bindParam(":prob", $cleanProbDesc, PDO::PARAM_STR);
	$stmt_newProj->bindParam(":worker", $projWorker, PDO::PARAM_INT);
	$stmt_newProj->bindParam(":stat", $projStat, PDO::PARAM_INT);
	if($stmt_newProj->execute()){
		return "Successfully Inserted Car";
	}
	else{
		return "ERROR";
	}
}

?>
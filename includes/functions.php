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

function updateWorker($pdo){
	$regUserName = cleanInput($_POST['u_name']);

	$stmt_updateUser = $pdo->prepare('UPDATE project_workers SET worker_username = :uname, worker_password = :upass, worker_role_fk = :urole WHERE worker_id = :uid');
    $stmt_updateUser->bindParam(":uname", $regUserName, PDO::PARAM_STR);
    $stmt_updateUser->bindParam(":urole", $_POST['worker_role'], PDO::PARAM_STR);
    $stmt_updateUser->bindParam(":uid", $_SESSION['worker_id'], PDO::PARAM_INT);

	if($stmt_registerUser->execute()){
		header("Location: index.php?newuser=1");
	}
	else{
		return "Something went wrong";
	}
}

function updateWorkerHours($pdo) {
    $currentDate = date("Y/m/d");
    $stmt_updateHours = $pdo->prepare("INSERT INTO project_hours(hours, date, proj_fk, proj_worker_fk) VALUES(:h, :date, :proj, :worker)");
    $stmt_updateHours->bindParam(':h', $_SESSION['w_proj_h'], PDO::PARAM_INT);
    $stmt_updateHours->bindParam(':date', $currentDate, PDO::PARAM_INT);
    $stmt_updateHours->bindParam(':proj', $_SESSION['proj_fk'], PDO::PARAM_INT);
    $stmt_updateHours->bindParam(':worker', $_SESSION['uid'], PDO::PARAM_INT);
    if ($stmt_updateHours->execute()) {
            return;
        }
}

function getHours($pdo) {
    $stmt_getHours = $pdo->prepare("SELECT SUM(hours) as total_hours FROM project_hours WHERE proj_fk = :projectId");
    $stmt_getHours->bindParam(':projectId', $_SESSION['proj_id'], PDO::PARAM_INT);

    if ($stmt_getHours->execute()) {
        $result = $stmt_getHours->fetch(PDO::FETCH_ASSOC);
        return $result['total_hours'] ?? 0;
    }
    return 0; // Return 0 if the query fails
}

function getWorkerHours($pdo, $startDate, $endDate, $workerId) {
    // Ensure startDate and endDate are not empty
    if (empty($startDate) || empty($endDate)) {
        throw new InvalidArgumentException("Both start date and end date are required.");
    }

    // Prepare the SQL statement to include date filtering
    $stmt_getHours = $pdo->prepare("
        SELECT SUM(hours) as total_hours 
        FROM project_hours 
        WHERE proj_worker_fk = :worker_id 
        AND date BETWEEN :startDate AND :endDate
    ");

    // Bind the parameters
    $stmt_getHours->bindParam(':worker_id', $workerId, PDO::PARAM_INT);
    $stmt_getHours->bindParam(':startDate', $startDate, PDO::PARAM_STR);
    $stmt_getHours->bindParam(':endDate', $endDate, PDO::PARAM_STR);

    // Execute the statement
    if ($stmt_getHours->execute()) {
        $result = $stmt_getHours->fetch(PDO::FETCH_ASSOC);
        return $result['total_hours'] ?? 0; // Return total_hours or 0 if null
    }
    return 0; // Return 0 if the query fails
}

function newCustomer($pdo) {
    $cleanFname = cleanInput($_SESSION['cus_fname']);
    $cleanLname = cleanInput($_SESSION['cus_lname']);
    
    $stmt_newCustomer = $pdo->prepare('INSERT INTO project_customers(cust_fname, cust_lname, cust_phone) VALUES(:fname, :lname, :phone)');
    $stmt_newCustomer->bindParam(":fname", $cleanFname, PDO::PARAM_STR);
    $stmt_newCustomer->bindParam(":lname", $cleanLname, PDO::PARAM_STR);
    $stmt_newCustomer->bindParam(":phone", $_SESSION['cus_phone'], PDO::PARAM_STR);
    
    if ($stmt_newCustomer->execute()) {
        // Update XML file
        $customers = simplexml_load_file('xml/customers.xml');
        if ($customers === false) {
            // Error loading XML
            echo "Failed to load XML file. Errors:";
            foreach (libxml_get_errors() as $error) {
                echo "<br>", $error->message;
            }
            return; // Exit the function if XML loading fails
        }

        // Add a new customer node
        $customer = $customers->addChild('customer');
        $customer->addChild('firstname', $cleanFname);
        $customer->addChild('lastname', $cleanLname);
        $customer->addChild('phone', $_SESSION['cus_phone']);
        $customer->addChild('id', $pdo->lastInsertId()); // Assuming you have the ID

        // Save the updated XML back to the file
        file_put_contents('xml/customers.xml', $customers->asXML());

        $_SESSION['reload'] = true; // Set session variable to trigger reload
        return "Successfully Inserted Customer";
    } else {
        return "Error inserting customer.";
    }
}

function newCar($pdo) {
    $cleanLicense = cleanInput($_SESSION['car_license']);
    $cleanBrand = cleanInput($_SESSION['car_brand']);
    $cleanModel = cleanInput($_SESSION['car_model']);

    $stmt_newCar = $pdo->prepare('INSERT INTO project_cars(project_car_license, project_car_brand, project_car_model) VALUES(:license, :brand, :model)');
    $stmt_newCar->bindParam(":license", $cleanLicense, PDO::PARAM_STR);
    $stmt_newCar->bindParam(":brand", $cleanBrand, PDO::PARAM_STR);
    $stmt_newCar->bindParam(":model", $cleanModel, PDO::PARAM_STR);

    if ($stmt_newCar->execute()) {
        $curr_car_id = $pdo->lastInsertId();
        $cars = simplexml_load_file('xml/cars.xml');

        if ($cars === false) {
            echo "Failed to load XML file.";
            exit;
        }

        $car = $cars->addChild('car');
        $car->addChild('license', $cleanLicense);
        $car->addChild('brand', $cleanBrand);
        $car->addChild('model', $cleanModel);
        $car->addChild('id', $curr_car_id);

        file_put_contents('xml/cars.xml', $cars->asXML());
        doubleReload(); // Trigger double reload
        return "Successfully Inserted Car";
    } else {
        return "ERROR";
    }
}

function removeCar($pdo) {
    // First, delete related projects
    $stmt_deleteProjects = $pdo->prepare('DELETE FROM projects WHERE project_car_fk = :carid');
    $stmt_deleteProjects->bindParam(":carid", $_SESSION['car_id'], PDO::PARAM_INT);
    $stmt_deleteProjects->execute(); // Execute to delete projects associated with the car

    // Now delete the car
    $stmt_deleteCar = $pdo->prepare('DELETE FROM project_cars WHERE project_car_id = :carid');
    $stmt_deleteCar->bindParam(":carid", $_SESSION['car_id'], PDO::PARAM_INT);

    if ($stmt_deleteCar->execute()) {
        // Update XML file
        $cars = simplexml_load_file('xml/cars.xml');
        if ($cars === false) {
            // Error loading XML
            echo "Failed to load XML file. Errors:";
            foreach (libxml_get_errors() as $error) {
                echo "<br>", $error->message;
            }
            return; // Exit the function if XML loading fails
        }

        // Remove the car from XML
        foreach ($cars->car as $key => $car) {
            if ((string)$car->id == $_SESSION['car_id']) {
                unset($cars->car[$key]);
                break; // Exit the loop once we find and remove the car
            }
        }

        // Save the updated XML back to the file
        file_put_contents('xml/cars.xml', $cars->asXML());

        doubleReload(); // Trigger double reload
        return "Successfully Deleted Car";
    } else {
        return "Error deleting car.";
    }
}


function updateCar($pdo, $id, $brand, $model, $license) {
    $stmt = $pdo->prepare("UPDATE project_cars SET project_car_brand = :brand, project_car_model = :model, project_car_license = :license WHERE project_car_id = :id");
    $stmt->bindParam(':brand', $brand);
    $stmt->bindParam(':model', $model);
    $stmt->bindParam(':license', $license);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        $cars = simplexml_load_file('xml/cars.xml');
        if ($cars === false) {
            echo "Failed to load XML file.";
            return;
        }

        foreach ($cars->car as $car) {
            if ((string)$car->id == $id) {
                $car->brand = $brand;
                $car->model = $model;
                $car->license = $license;
                break;
            }
        }

        file_put_contents('xml/cars.xml', $cars->asXML());
        doubleReload(); // Trigger double reload
        return "Successfully Updated Car";
    } else {
        return "Error updating car.";
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

function handleProjectUpdate($pdo) {
    // Check if required POST data exists
    if (isset($_POST['project_id'], $_POST['project_problem_description'], $_POST['project_work_description'], $_POST['project_status_fk'])) {
        $projectId = $_POST['project_id'];
        $problemDescription = $_POST['project_problem_description'];
        $workDescription = $_POST['project_work_description'];
        $projectStatusFk = $_POST['project_status_fk']; // Foreign key for project status

        try {
            // Prepare the SQL update statement
            $stmt_update = $pdo->prepare("UPDATE projects 
                                          SET project_problem_description = :problemDesc, 
                                              project_work_description = :workDesc,
                                              project_status_fk = :statusFk 
                                          WHERE project_id = :projectId");

            // Bind values
            $stmt_update->bindValue(':problemDesc', $problemDescription, PDO::PARAM_STR);
            $stmt_update->bindValue(':workDesc', $workDescription, PDO::PARAM_STR);
            $stmt_update->bindValue(':statusFk', $projectStatusFk, PDO::PARAM_INT); // Bind the foreign key
            $stmt_update->bindValue(':projectId', $projectId, PDO::PARAM_INT);

            // Execute the update
            if (!$stmt_update->execute()) {
                $errorInfo = $stmt_update->errorInfo(); // Get error information
                throw new Exception("Database Error: " . $errorInfo[2]); // Throw exception for error handling
            } else {
                header("Location: mechanic.php");
                return true; // Return true on success
            }
        } catch (Exception $e) {
            // Handle errors
            error_log($e->getMessage()); // Log the error message
            return false; // Return false on failure
        }
    } else {
        return false; // Return false if required data is missing
    }
}



function removeCustomer($pdo) {
    $stmt_newProj = $pdo->prepare('DELETE FROM project_customers WHERE cust_id = :custid');
    $stmt_newProj->bindParam(":custid", $_SESSION['cust_id'], PDO::PARAM_INT);

    if ($stmt_newProj->execute()) {
        // Update XML file
        $customers = simplexml_load_file('xml/customers.xml');
        if ($customers === false) {
            // Error loading XML
            echo "Failed to load XML file. Errors:";
            foreach (libxml_get_errors() as $error) {
                echo "<br>", $error->message;
            }
            return; // Exit the function if XML loading fails
        }

        // Remove the customer from XML
        foreach ($customers->customer as $key => $customer) {
            if ((string)$customer->id == $_SESSION['cust_id']) {
                unset($customers->customer[$key]);
                break; // Exit the loop once we find and remove the customer
            }
        }

        // Save the updated XML back to the file
        file_put_contents('xml/customers.xml', $customers->asXML());

        $_SESSION['reload'] = 3; // Set session variable to trigger three reloads
        return "Successfully Deleted Customer";
    } else {
        return "Error deleting customer.";
    }
}




// Function to update customer data in the database and XML
function updateCustomer($pdo, $id, $fname, $lname, $phone) {
    // Update database
    $stmt = $pdo->prepare("UPDATE project_customers SET cust_fname = :fname, cust_lname = :lname, cust_phone = :phone WHERE cust_id = :id");
    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        // Update XML file
        $customers = simplexml_load_file('xml/customers.xml');
        if ($customers === false) {
            // Error loading XML
            echo "Failed to load XML file. Errors:";
            foreach (libxml_get_errors() as $error) {
                echo "<br>", $error->message;
            }
            return; // Exit the function if XML loading fails
        }

        foreach ($customers->customer as $customer) {
            if ((string)$customer->id == $id) {
                $customer->firstname = $fname;
                $customer->lastname = $lname;
                $customer->phone = $phone;
                break; // Exit the loop once we find and update the customer
            }
        }

        // Save the updated XML back to the file
        file_put_contents('xml/customers.xml', $customers->asXML());

        $_SESSION['reload'] = 3; // Set session variable to trigger three reloads
        return "Successfully Updated Customer";
    } else {
        return "Error updating customer.";
    }
}



function newBill($pdo) {
    $projAccStat = "1";
    $errorMessage = '';

    try {
        // Get the total hours using getHours function
        $totalHours = getHours($pdo); 

        // Prepare the INSERT statement
        $stmt_newProj = $pdo->prepare(
            'INSERT INTO project_accounting(project_nr_fk, project_price, project_hours, project_accounting_status_fk) 
            VALUES (:car, :price, :hours, :stat)'
        );
        $stmt_newProj->bindParam(":car", $_SESSION['proj_id'], PDO::PARAM_INT);
        $stmt_newProj->bindParam(":price", $_SESSION['proj_price'], PDO::PARAM_INT);
        $stmt_newProj->bindParam(":hours", $totalHours, PDO::PARAM_INT); // Use $totalHours directly
        $stmt_newProj->bindParam(":stat", $projAccStat, PDO::PARAM_INT);

        // Execute the INSERT statement
        if ($stmt_newProj->execute()) {
            // Prepare the UPDATE statement
            $stmt_archiveProj = $pdo->prepare(
                'UPDATE projects SET project_status_fk = :proj_stat WHERE project_id = :curr_id'
            );
            $projStatus = '4';
            $stmt_archiveProj->bindParam(":proj_stat", $projStatus, PDO::PARAM_INT);
            $stmt_archiveProj->bindParam(":curr_id", $_SESSION['proj_id'], PDO::PARAM_INT);

            // Execute the UPDATE statement
            if ($stmt_archiveProj->execute()) {
                header("Location: accounting.php");
                exit(); // Ensure that the script stops executing after the redirect
            } else {
                // Debugging information
                $errorInfo = $stmt_archiveProj->errorInfo();
                $errorMessage = "Something went wrong with the UPDATE statement: " . $errorInfo[2];
            }
        } else {
            // Debugging information
            $errorInfo = $stmt_newProj->errorInfo();
            $errorMessage = "ERROR executing the INSERT statement: " . $errorInfo[2];
        }
    } catch (PDOException $e) {
        $errorMessage = "PDOException: " . $e->getMessage();
    }

    // Print the error message if it exists
    if ($errorMessage !== '') {
        echo "<div style='color: red; font-weight: bold;'>$errorMessage</div>";
    }
}




function doubleReload() {
    echo "
    <script>
        if (!window.location.href.includes('reloaded=true')) {
            setTimeout(function() {
                var url = window.location.href;
                if (url.indexOf('?') > -1) {
                    url += '&reloaded=true'; // If query params exist, append
                } else {
                    url += '?reloaded=true'; // If no query params, add it
                }
                window.location.href = url;
            }, 100); // First reload after 100 ms
        }
    </script>
    ";
}
?>
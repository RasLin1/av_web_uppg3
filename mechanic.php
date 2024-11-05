<?php
include_once 'includes/header.php';

$activeProjects = $pdo->query("SELECT * FROM projects INNER JOIN project_cars ON projects.project_car_fk = project_cars.project_car_id WHERE project_status_fk = '2'")->fetchAll();

$queuedProjects = $pdo->query("SELECT * FROM projects INNER JOIN project_cars ON projects.project_car_fk = project_cars.project_car_id WHERE project_status_fk = '1'")->fetchAll();

$allProjects = $pdo->query("SELECT * FROM projects INNER JOIN project_cars ON projects.project_car_fk = project_cars.project_car_id WHERE project_status_fk = '1' OR project_status_fk = '2' OR project_status_fk = '3'")->fetchAll();



// Check if urole exists and redirect based on role
if (!isset($_SESSION['urole'])) {
    // Redirect if urole is not set in the session
    header("Location: index.php");
    exit(); // Important to stop further script execution
} elseif ($_SESSION['urole'] < 1) {
    // Redirect if the user's role is greater than the allowed minimum role
    header("Location: index.php");
    exit();
}

if(isset($_POST['saveHours'])){
    $_SESSION['proj_fk'] = $_POST['projects_fk'];
    $_SESSION['w_proj_h'] = $_POST['worker_proj_hours'];
    $updateHours = updateWorkerHours($pdo);
}

?>

<div class="container">
    
    <div id="active_projects" class="container">
        <h3>Active Projects</h3>
        <?php 
        if($activeProjects == '0'){
            echo "No Active Projects";
        }
        else{
        foreach ($activeProjects as $row){
            $currCarId = $row['project_car_fk'];
            $currCar = $pdo->query("SELECT * FROM project_cars WHERE project_car_id = $currCarId");
            echo "<div class='row'>
                <string>{$row['project_car_license']}  - {$row['project_car_brand']} {$row['project_car_model']}</string>
                <form action='singleProject.php' method='POST'>
                    <input type='hidden' name='currProjectId' value='{$row['project_id']}' />
                    <input type='submit' name='showSingleCar' value='More&nbsp;Info' />
                </form>
                
            </div><br><br>";}
        }
        ?>
    </div>
    <div id="queued_projects" class="container">
    <h3>Queued Projects</h3>
        <?php 
        foreach ($queuedProjects as $row){;
            echo "<div class='row'>
                <string>{$row['project_car_license']}  - {$row['project_car_brand']} {$row['project_car_model']}</string>
                <form action='singleProject.php' method='POST'>
                    <input type='hidden' name='currProjectId' value='{$row['project_id']}' />
                    <input type='submit' name='showSingleCar' value='More&nbsp;Info' />
                </form>
            </div>";
        }
        ?>
    </div><br><br>
    <div id="view_buttons" class="container">
    <div class="row">
        <form method="POST" action="allCustomers.php" >
            <input type="submit" value="View All Customers"></input>
        </form>
    </div><br><br>
    <div class="row">
        <form method="POST" action="allCars.php" >
            <input type="submit" value="View All Cars"></input>
        </form>
    </div><br><br>
    <div class="row">
        <form method="POST" action="newProject.php" >
            <input type="submit" value="New Project"></input>
        </form>
    </div><br><br>
    
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#workingHoursModal">
            Adjust Working Hours
        </button>
    </div>
</div>


<div class="modal fade" id="workingHoursModal" tabindex="-1" aria-labelledby="workingHoursModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="workingHoursModalLabel">Edit Work Hours</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="workerProjHours" class="form-label">Project Hours</label>
                        <input type="number" name="worker_proj_hours" id="workerProjHours" required/>
                    </div>
                    <div class="mb-3">
                        <label for="projectsFk" class="form-label">Project</label>
                        <select class="form-select" id="projectsFk" name="projects_fk">
                            <?php
                            
                            // Loop through the projects and create options
                            foreach ($allProjects as $proj) {
                                $currProjCarId = $proj['project_car_fk'];
                                $currProjOwnerId = $proj['project_customer_fk'];
                                $currProjCar = $pdo->query("SELECT * FROM project_cars WHERE project_car_id = $currProjCarId")->fetch();
                                $currProjCus = $pdo->query("SELECT * FROM project_customers WHERE cust_id = $currProjOwnerId")->fetch();
                                echo "<option value=\"" . htmlspecialchars($proj['project_id']) . "\">" . $currProjCar['project_car_license'] . " - " . $currProjCus['cust_fname'] . " " . $currProjCus['cust_lname'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Hidden input to pass the project ID -->
                    <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($projectData['project_id']); ?>">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="saveHours" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php 
include_once 'includes/footer.php';
?>


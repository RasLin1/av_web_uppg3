<?php
include_once 'includes/header.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);


// Handle project update request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_id'])) {
    // Call the function to update the project
    $updateSuccess = handleProjectUpdate($pdo); 

    if ($updateSuccess) {
        // Success message or redirect logic
        echo "<div class='alert alert-success'>Project updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>An error occurred while updating the project. Please check the logs for details.</div>";
    }
}

// Fetch project data
$stmt_selectProject = $pdo->prepare("SELECT * FROM projects WHERE project_id = :projId");
$stmt_selectProject->bindValue(":projId", $_POST['currProjectId'], PDO::PARAM_STR);
$stmt_selectProject->execute();
$projectData = $stmt_selectProject->fetch();

// Fetch related car data
$stmt_selectCar = $pdo->prepare("SELECT * FROM project_cars WHERE project_car_id = :projId");
$stmt_selectCar->bindValue(":projId", $projectData['project_car_fk'], PDO::PARAM_STR);
$stmt_selectCar->execute();
$carData = $stmt_selectCar->fetch();

// Fetch related customer data
$stmt_selectCustomer = $pdo->prepare("SELECT * FROM project_customers WHERE cust_id = :projId");
$stmt_selectCustomer->bindValue(":projId", $projectData['project_customer_fk'], PDO::PARAM_STR);
$stmt_selectCustomer->execute();
$customerData = $stmt_selectCustomer->fetch();

$projet = $pdo->query("SELECT * FROM projects INNER JOIN project_cars ON projects.project_car_fk = project_cars.project_car_id WHERE project_status_fk = '1'")->fetchAll();

?>

<div class="container">
    <div class="row">
        <p> Customer: <?php echo htmlspecialchars($customerData['cust_fname']) . " " . htmlspecialchars($customerData['cust_lname']) . " " . htmlspecialchars($customerData['cust_phone']); ?></p>
        <p> Car: <?php echo htmlspecialchars($carData['project_car_brand']) . " " . htmlspecialchars($carData['project_car_model']) . " " . htmlspecialchars($carData['project_car_license']); ?></p>
        
        <p> Project Problem Description:</p>
        <p><?php echo htmlspecialchars($projectData['project_problem_description']); ?></p>
        
        <p> Project Work Description:</p>
        <p><?php echo htmlspecialchars($projectData['project_work_description']); ?></p>

        <!-- Button to open the modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProjectModal">
            Edit Project
        </button>
    </div>
</div>

<!-- Modal for editing project descriptions -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProjectModalLabel">Edit Project Descriptions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="problemDescription" class="form-label">Project Problem Description</label>
                        <textarea class="form-control" id="problemDescription" name="project_problem_description" rows="3"><?php echo htmlspecialchars($projectData['project_problem_description']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="workDescription" class="form-label">Project Work Description</label>
                        <textarea class="form-control" id="workDescription" name="project_work_description" rows="3"><?php echo htmlspecialchars($projectData['project_work_description']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="projectStatusFk" class="form-label">Project Status</label>
                        <select class="form-select" id="projectStatusFk" name="project_status_fk">
                            <?php
                            // Fetch available statuses from the database
                            $stmt_status = $pdo->prepare("SELECT * FROM project_status");
                            $stmt_status->execute();
                            $statuses = $stmt_status->fetchAll(PDO::FETCH_ASSOC);

                            // Loop through the statuses and create options
                            foreach ($statuses as $status) {
                                $selected = ($projectData['project_status_fk'] == $status['project_status_id']) ? 'selected' : '';
                                echo "<option value=\"" . htmlspecialchars($status['project_status_id']) . "\" $selected>" . htmlspecialchars($status['project_status_name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Hidden input to pass the project ID -->
                    <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($projectData['project_id']); ?>">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
include_once 'includes/footer.php';
?>

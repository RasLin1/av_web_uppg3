<?php 
include_once 'includes/header.php';

$stmt_selectWorker = $pdo->prepare("SELECT * FROM project_workers WHERE worker_id = :workerId");
$stmt_selectWorker->bindValue(":workerId", $_POST['currWorkerId'], PDO::PARAM_STR);
$stmt_selectWorker->execute();
$workerData = $stmt_selectWorker->fetch();

$stmt_selectWorkerRole = $pdo->prepare("SELECT * FROM project_worker_role WHERE worker_role_id = :workerRoleId");
$stmt_selectWorkerRole->bindValue(":workerRoleId", $workerData['worker_role_fk'], PDO::PARAM_STR);
$stmt_selectWorkerRole->execute();
$workerRoleData = $stmt_selectWorkerRole->fetch();

$_SESSION['worker_id'] = $workerData['worker_id'];
$currentRoleId = $workerRoleData['worker_role_id'];

$allWorkerRoles = $pdo->query("SELECT * FROM project_worker_role")->fetchAll();
?>

<div class="container">
    <p>Worker Username: <?php echo $workerData['worker_username']; ?></p>
    <p>Worker Role: <?php echo $workerRoleData['worker_role_name']; ?></p>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#workingHoursModal">
        Edit Profile
    </button>
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
                        <label for="workerUsername" class="form-label">Username</label>
                        <input type="text" name="worker_username" id="workerUsername" value="<?php echo $workerData['worker_username']?> " required/>
                    </div>
                    <div class="mb-3">
                        <label for="workerRole" class="form-label">Role</label>
                        <select class="form-select" id="workerRole" name="worker_role">
                            <?php foreach ($allWorkerRoles as $row): ?>
                                <option value="<?php echo $row['worker_role_id']; ?>" 
                                    <?php echo ($row['worker_role_id'] == $currentRoleId) ? 'selected' : ''; ?>>
                                    <?php echo $row['worker_role_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Hidden input to pass the project ID -->
                    <input type="hidden" name="worker_id" value="<?php echo htmlspecialchars($projectData['project_id']); ?>">
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
include_once 'includes/footer.php'
?>
<?php
include_once 'includes/header.php';

$allWorkers = $pdo->query(" SELECT project_workers.*, project_worker_role.worker_role_name FROM project_workers INNER JOIN project_worker_role ON project_workers.worker_role_fk = project_worker_role.worker_role_id")->fetchAll();

// Check if urole exists and redirect based on role
if (!isset($_SESSION['urole'])) {
    // Redirect if urole is not set in the session
    header("Location: index.php");
    exit(); // Important to stop further script execution
} elseif ($_SESSION['urole'] < 3) {
    // Redirect if the user's role is greater than the allowed minimum role
    header("Location: index.php");
    exit();
}


?>

<div class="container">
    <div id="mechanics" class="container">
        <h3>All Workers</h3>
        <?php 
        foreach ($allWorkers as $row){
            echo "  <div class='row'>
                        <string>{$row['worker_username']} - {$row['worker_role_name']}</string><br>
                        <form action='singleWorker.php' method='POST'>
                            <input type='hidden' name='currWorkerId' value='{$row['worker_id']}' />
                            <input type='submit' name='editUser' value='More&nbsp;Info' />
                        </form>
                    </div>";
                }
        ?>
    </div><br>
</div>

<?php 
include_once 'includes/footer.php';
?>
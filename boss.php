<?php
include_once 'includes/header.php';

$mechanics = $pdo->query("SELECT * FROM project_workers WHERE worker_role_fk = '1'")->fetchAll();

$accountants = $pdo->query("SELECT * FROM project_workers WHERE worker_role_fk = '2'")->fetchAll();

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
    <form method="POST">
        <h3>Choose time period</h3>
        <input type="date" id="startDate" name="startDate" required/>
        <label> - </label>
        <input type="date" id="endDate" name="endDate" required/><br><br>
        <input type="submit" value="Search" name="searchForHours"/><br><br>
    </form>
</div>

<div>
    <?php 
    if (isset($_POST['searchForHours'])) {
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        foreach ($mechanics as $row){
            $workerId = $row['worker_id'];
        try {
            $totalHours = getWorkerHours($pdo, $startDate, $endDate, $workerId);
            echo $row['worker_username']." - ". $totalHours;
        } catch (InvalidArgumentException $e) {
            echo "Error: " . $e->getMessage();
        }
    }}
    ?>
</div>
<br><br>

<div id="view_buttons" class="container">
    <div class="row">
        <form method="POST" action="register.php" >
            <input type="submit" value="Create new account"></input>
        </form>
    </div><br>
    <div class="row">
        <form method="POST" action="allWorkers.php" >
            <input type="submit" value="View all worker accounts"></input>
        </form>
    </div><br>
</div>




<?php 
include_once 'includes/footer.php';
?>

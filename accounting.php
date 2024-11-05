<?php
include_once 'includes/header.php';

$unbilled = $pdo->query("SELECT * FROM project_accounting WHERE project_accounting_status_fk = '1'")->fetchAll();

$billed = $pdo->query("SELECT * FROM project_accounting WHERE project_accounting_status_fk = '2'")->fetchAll();

// Check if urole exists and redirect based on role
if (!isset($_SESSION['urole'])) {
    // Redirect if urole is not set in the session
    header("Location: index.php");
    exit(); // Important to stop further script execution
} elseif ($_SESSION['urole'] < 2) {
    // Redirect if the user's role is greater than the allowed minimum role
    header("Location: index.php");
    exit();
}

?>

<div class="container">
    <div id="unbilled" class="container">
        <h3>Unbilled</h3>
        <?php 
        foreach ($unbilled as $row){
            $currProjId = $row['project_nr_fk'];
            $currProj = $pdo->query("SELECT * FROM projects WHERE project_id = $currProjId")->fetch();
            $currProjCarId = $currProj['project_car_fk'];
            $currProjOwnerId = $currProj['project_customer_fk'];
            $currProjCar = $pdo->query("SELECT * FROM project_cars WHERE project_car_id = $currProjCarId")->fetch();
            $currProjCus = $pdo->query("SELECT * FROM project_customers WHERE cust_id = $currProjOwnerId")->fetch();
            echo "  <div class='row'>
                        <string>{$currProjCar['project_car_license']}  - {$currProjCus['cust_fname']}  {$currProjCus['cust_lname']}</string><br>
                        <form action='singleBill.php' method='POST'>
                            <input type='hidden' name='currAccountingId' value='{$row['project_accounting_id']}' />
                            <input type='submit' name='showSingleBill' value='More&nbsp;Info' />
                        </form>
                    </div>";
                }
        ?>
    </div>
    <div id="billed" class="container">
    <h3>Billed</h3>
        <?php 
        foreach ($billed as $row){
            $currProjId = $row['project_nr_fk'];
            $currProj = $pdo->query("SELECT * FROM projects WHERE project_id = $currProjId")->fetch();
            $currProjCarId = $currProj['project_car_fk'];
            $currProjOwnerId = $currProj['project_customer_fk'];
            $currProjCar = $pdo->query("SELECT * FROM project_cars WHERE project_car_id = $currProjCarId")->fetch();
            $currProjCus = $pdo->query("SELECT * FROM project_customers WHERE cust_id = $currProjOwnerId")->fetch();
            echo "  <div class='row'>
                        <string>{$currProjCar['project_car_license']}  - {$currProjCus['cust_fname']}  {$currProjCus['cust_lname']}</string><br>
                        <form action='singleBill.php' method='POST'>
                            <input type='hidden' name='currAccountingId' value='{$row['project_accounting_id']}' />
                            <input type='submit' name='showSingleCar' value='More&nbsp;Info' />
                        </form>
                    </div>";
                }
        ?>
    </div>
    <div id="view_buttons" class="container">
    <div class="row">
        <form method="POST" action="newBill.php" >
            <input type="submit" value="Create new bill"></input>
        </form>
    </div><br><br>
    </div>
</div>

<?php 
include_once 'includes/footer.php';
?>

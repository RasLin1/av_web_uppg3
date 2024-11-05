<?php 
include_once 'includes/header.php';

$stmt_selectBill = $pdo->prepare("SELECT * FROM project_accounting WHERE project_accounting_id = :accId");
$stmt_selectBill->bindValue(":accId", $_POST['currAccountingId'], PDO::PARAM_STR);
$stmt_selectBill->execute();
$billData = $stmt_selectBill->fetch();

$currProjId = $billData['project_nr_fk'];
$currProj = $pdo->query("SELECT * FROM projects WHERE project_id = $currProjId")->fetch();
$currProjCarId = $currProj['project_car_fk'];
$currProjOwnerId = $currProj['project_customer_fk'];
$currProjCar = $pdo->query("SELECT * FROM project_cars WHERE project_car_id = $currProjCarId")->fetch();
$currProjCus = $pdo->query("SELECT * FROM project_customers WHERE cust_id = $currProjOwnerId")->fetch();
?>

<div class="container">
    <form method="POST">
    <p>Bill Info: <?php echo $currProjCar['project_car_license'] . " - " . $currProjCus['cust_fname'] . " " . $currProjCus['cust_lname'];?></p>
        <label for="project_hours">Hours:</label><br>
        <input name="project_hours" type="number" value="<?php echo $billData['project_hours']; ?>" min="1" max="500" required/><br><br>
        <label for="project_price">Price:</label><br>
        <input name="project_price" type="number" value="<?php echo $billData['project_price']; ?>" min="1" max="100000" placeholder="€" required/><br><br>
        <input type="submit" id="newBill" name="newBill" value="Create New Bill"/>
    </form>
</div>

<?php 
include_once 'includes/footer.php'
?>
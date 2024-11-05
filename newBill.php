<?php 
include_once 'includes/header.php';

$done_projects = $pdo->query("SELECT * FROM projects INNER JOIN project_cars ON projects.project_car_fk = project_cars.project_car_id WHERE project_status_fk = '3'")->fetchAll();

if(isset($_POST['newBill'])){
    $_SESSION['proj_id'] = $_POST['project_id'];
    $_SESSION['proj_price'] = $_POST['project_price'];
    $newBill = newBill($pdo);
}
?>

<div class="container">
    <form method="POST">
        <label for="project_id">Select Project:</label>
        <select name="project_id" id="project_id">
            <?php foreach ($done_projects as $proj): ?>
                <option value="<?php echo cleanInput($proj['project_id']); ?>" >
                    <?php echo cleanInput($proj['project_car_license']) ; ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        <label for="project_price">Price:</label><br>
        <input name="project_price" type="number" min="1" max="100000" placeholder="€" required/><br><br>
        <input type="submit" id="newBill" name="newBill" value="Create New Bill"/>
    </form>
</div>

<?php 
include_once 'includes/footer.php'
?>
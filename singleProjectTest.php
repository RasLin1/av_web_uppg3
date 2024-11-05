<?php 
include 'includes/header.php';

// Initialize debug session variable if not set
if (!isset($_SESSION['debug'])) {
    $_SESSION['debug'] = [];
}

// Capture POST data debug info
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    addDebugInfo("POST Data: " . print_r($_POST, true));
}

// Check if 'currProjectId' and 'currProjectOwnerId' are set in POST
if (isset($_POST['currProjectId']) && isset($_POST['currProjectOwnerId'])) {
    $currProjectId = $_POST['currProjectId'];
    $currProjectOwnerId = $_POST['currProjectOwnerId'];
    addDebugInfo("Project ID: $currProjectId, Owner ID: $currProjectOwnerId");
} else {
    addDebugInfo("Project ID or Owner ID is missing!");
    header("Location: index.php"); // Redirect if these values are not set
    exit;
}

// Fetch project details
$stmt = $pdo->prepare("SELECT * FROM projects WHERE project_id = :project_id");
$stmt->execute(['project_id' => $currProjectId]);
$currProject = $stmt->fetch();

// Check if project is found
if (!$currProject) {
    addDebugInfo("Project not found.");
    exit;
}

// Fetch project-related details
$stmtBody = $pdo->prepare("SELECT project_type_name FROM project_project_type WHERE project_type_id = :project_type_id");
$stmtBody->execute(['project_type_id' => $currProject['project_project_type_fk']]);
$currProjectBody = $stmtBody->fetch();

$stmtDrive = $pdo->prepare("SELECT project_category_name FROM project_project_category WHERE project_category_id = :project_category_id");
$stmtDrive->execute(['project_category_id' => $currProject['project_project_category_fk']]);
$currProjectDrive = $stmtDrive->fetch();

$stmtFuel = $pdo->prepare("SELECT project_stage_name FROM project_project_stage WHERE project_stage_id = :project_stage_id");
$stmtFuel->execute(['project_stage_id' => $currProject['project_project_stage_fk']]);
$currProjectFuel = $stmtFuel->fetch();

$stmtTrans = $pdo->prepare("SELECT project_priority_name FROM project_project_priority WHERE project_priority_id = :project_priority_id");
$stmtTrans->execute(['project_priority_id' => $currProject['project_project_priority_fk']]);
$currProjectTrans = $stmtTrans->fetch();

// Handle form submission to change sale status
if (isset($_POST['changeSaleStatus'])) {
    addDebugInfo("Processing form submission...");

    $saleStatus = $_POST['completion_status']; // Get sale status from form
    addDebugInfo("Sale Status: $saleStatus");
    addDebugInfo("Current Project ID: $currProjectId");

    // Call the function to update sale status
    $result = updateSaleStatus($pdo, $currProjectId, $saleStatus);
    
    addDebugInfo("Update Sale Status Result: " . $result);
}

?>

<div class="container">
    <div class="row">
        <div class="col-6">
            <h2><?php echo  $currProject['project_brand'] . ' ' . $currProject['project_model']; ?></h2> 
            <div class="row">
                <div class="col-6">
                    <p> Milage: <?php echo $currProject['project_milage']; ?> km</p> <br>
                    <p> Price: <?php echo $currProject['project_price']; ?> €</p> <br>
                    <p> Engine: <?php echo $currProject['project_engine_displacement'] ." | ". $currProjectFuel['project_stage_name']; ?></p> <br>
                    <p> Weight: <?php echo $currProject['project_hp']; ?></p> <br>
                    <p> Emissions: <?php echo $currProject['project_emmisions']; ?> g/km</p> <br>
                    <p> Body Type: <?php echo $currProjectBody['project_type_name']; ?></p> <br>
                </div>
                <div class="col-6">
                    <p> Model Year: <?php echo $currProject['project_model_year']; ?></p> <br>
                    <p> License: <?php echo $currProject['project_license']; ?></p> <br>
                    <p> Last Inspection: <?php echo $currProject['project_inspection_date']; ?></p> <br>
                    <p> Weight: <?php echo $currProject['project_weight']; ?> kg</p> <br>
                    <p> Drive Type: <?php echo $currProjectDrive['project_category_name']; ?></p> <br>
                    <p> Transmission Type: <?php echo $currProjectTrans['project_priority_name']; ?></p> <br>
                </div>
                
                
            </div>
                
        </div>
        <div class="row">
                    <div class="col-6">
                        <h5>Description</h5>
                        <p><?php echo $currProject['project_description'];?></p>
                    </div>
                    <div class="col-6">
                        <h5>Technical Description</h5>
                        <p><?php echo $currProject['project_technical_descripton'];?></p>
                    </div>
                </div>
                <div>
                    <?php 
                        if ($_SESSION['owner'] == $currProject['project_owner_fk']) {
    echo "
    <form method='POST'>
        <label for='completion_status'>Change Selling Status:</label>
        <select id='completion_status' name='completion_status' required>
            <option value='0' ".($currProject['project_sold_status'] == 0 ? "selected" : "").">For sale</option>
            <option value='1' ".($currProject['project_sold_status'] == 1 ? "selected" : "").">Sold</option>
        </select><br><br>
        <input type='hidden' name='currProjectId' value='$currProjectId'> <!-- Pass project ID -->
        <input type='hidden' name='currProjectOwnerId' value='$currProjectOwnerId'> <!-- Pass project owner ID -->
        <input type='submit' name='changeSaleStatus' value='Update Status'></input>
    </form>
    ";
}
                    ?>
                </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

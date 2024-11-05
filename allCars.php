<?php
include_once 'includes/header.php';

// Fetch all cars from the database
$cars = $pdo->query('SELECT * FROM project_cars')->fetchAll();

if (isset($_POST['removeCar'])) {
    $_SESSION['car_id'] = $_POST['car_id'];
    $removeCar = removeCar($pdo); // Remove car logic (function should be adjusted for cars)
}

// Handle new car submission
if (isset($_POST['newCarSubmit'])) {
    $_SESSION['project_car_brand'] = $_POST['project_car_brand'];
    $_SESSION['project_car_model'] = $_POST['project_car_model'];
    $_SESSION['project_car_license'] = $_POST['project_car_license'];
    $newCar = newCar($pdo); // New car logic (function should be adjusted for cars)
}

// Handle car update submission
if (isset($_POST['saveUpdatedCar'])) {
    $carId = $_POST['car_id'];
    $carBrand = $_POST['project_car_brand'];
    $carModel = $_POST['project_car_model'];
    $carLicense = $_POST['project_car_license'];

    // Update car in the database
    updateCar($pdo, $carId, $carBrand, $carModel, $carLicense); // Update car function
}

?>

<h5>All Cars</h5>

<?php 
foreach ($cars as $row) {
    echo "<div class='row'>
        <strong>{$row['project_car_brand']} {$row['project_car_model']} (License: {$row['project_car_license']})</strong><br><br>
        <form method='POST' style='display:inline;' onsubmit='return false;'> <!-- Prevent default form submission -->
            <input type='hidden' value='{$row['project_car_id']}' name='car_id'>
            <button type='button' onclick='openUpdateModal({$row['project_car_id']}, \"{$row['project_car_brand']}\", \"{$row['project_car_model']}\", \"{$row['project_car_license']}\")'>Update Car</button>
        </form>
        <form method='POST' style='display:inline;'>
            <input type='hidden' value='{$row['project_car_id']}' name='car_id'>
            <input name='removeCar' type='submit' value='Remove Car'>
        </form>
    </div>";
}
?>

<!-- New Car Modal -->
<div id="newCar" class="">
    <br><br><button id="carModBtn">New Car</button><br><br>
    <div id="carModal" class="modal carModal">
        <div class="modal-content">
            <span class="carclose close">&times;</span>
            <form action="" method="POST">
                <label for="project_car_brand">Brand:</label><br>
                <input type="text" id="project_car_brand" name="project_car_brand" class="" required><br><br>
                <label for="project_car_model">Model:</label><br>
                <input type="text" id="project_car_model" name="project_car_model" class="" required><br><br>
                <label for="project_car_license">License:</label><br>
                <input type="text" id="project_car_license" name="project_car_license" required><br><br>
                <input type="submit" id="newCarSubmit" name="newCarSubmit" value="Insert Car">
            </form>
        </div>
    </div>
</div>

<!-- Update Car Modal -->
<div id="updateCar" class="">
    <div id="updateModal" class="modal updateModal">
        <div class="modal-content">
            <span class="updateclose close">&times;</span>
            <form action="" method="POST">
                <input type="hidden" id="update_car_id" name="car_id">
                <label for="update_project_car_brand">Brand:</label><br>
                <input type="text" id="update_project_car_brand" name="project_car_brand" required><br><br>
                <label for="update_project_car_model">Model:</label><br>
                <input type="text" id="update_project_car_model" name="project_car_model" required><br><br>
                <label for="update_project_car_license">License:</label><br>
                <input type="text" id="update_project_car_license" name="project_car_license" required><br><br>
                <input type="submit" name="saveUpdatedCar" value="Save Changes">
            </form>
        </div>
    </div>
</div>

<script>
// Modal functionality for the new car modal
var carmodal = document.getElementById("carModal");
var carbtn = document.getElementById("carModBtn");
var carspan = document.getElementsByClassName("carclose")[0];

carbtn.onclick = function() {
    carmodal.style.display = "block";
}

carspan.onclick = function() {
    carmodal.style.display = "none";
}

// Open the update modal with car data
function openUpdateModal(carId, brand, model, license) {
    document.getElementById("update_car_id").value = carId;
    document.getElementById("update_project_car_brand").value = brand;
    document.getElementById("update_project_car_model").value = model;
    document.getElementById("update_project_car_license").value = license;
    
    var updatemodal = document.getElementById("updateModal");
    updatemodal.style.display = "block"; // Show the update modal
}

// Close the update modal
var updateCloseBtn = document.getElementsByClassName("updateclose")[0];
updateCloseBtn.onclick = function() {
    document.getElementById("updateModal").style.display = "none";
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == carmodal) {
        carmodal.style.display = "none";
    } else if (event.target == document.getElementById("updateModal")) {
        document.getElementById("updateModal").style.display = "none";
    }
}
</script>

<?php
include_once 'includes/footer.php';
?>

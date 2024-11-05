<?php
include_once 'includes/header.php';

$customers = $pdo->query('SELECT * FROM project_customers')->fetchAll();
$cars = $pdo->query('SELECT * FROM project_cars')->fetchAll();

if (isset($_POST['newCusSubmit'])) {
    $_SESSION['cus_fname'] = $_POST['cus_fname'];
    $_SESSION['cus_lname'] = $_POST['cus_lname'];
    $_SESSION['cus_phone'] = $_POST['cus_phone'];
    $newCus = newCustomer($pdo);
}

if (isset($_POST['newCarSubmit'])) {
    $_SESSION['car_license'] = $_POST['car_license'];
    $_SESSION['car_brand'] = $_POST['car_brand'];
    $_SESSION['car_model'] = $_POST['car_model'];
    $newCar = newCar($pdo);
}

if (isset($_POST['newProject'])) {
    $_SESSION['customer'] = $_POST['customerId']; // Use the hidden input for the customer ID
    $_SESSION['car'] = $_POST['carId']; // Use the hidden input for the car ID
    $_SESSION['probDesc'] = $_POST['probDesc'];
    $newProject = newProject($pdo);
}
?>

<div class="container">
    <form method="POST">
        <label for="customerSearch">Customer:</label><br>
        <input name="customerSearch" type="text" size="30" onkeyup="showResultCus(this.value)" value="">
        <div id="livesearch"></div>
        <input type="hidden" id="customerId" name="customerId" value=""> <!-- Hidden input for the customer ID -->
        
        <label for="carSearch">Car:</label><br>
        <input name="carSearch" type="text" size="30" onkeyup="showResultCar(this.value)" value="">
        <div id="carLivesearch"></div>
        <input type="hidden" id="carId" name="carId" value=""> <!-- Hidden input for the car ID -->
        
        <label for="probDesc">Problem Description</label><br>
        <textarea id="probDesc" name="probDesc" rows="4" cols="50"></textarea><br><br>
        
        <input type="submit" name="newProject" value="Submit"><br><br>
    </form>

    <div id="newCustomer" class="">
        <button id="cusModBtn">New Customer</button><br><br>
        <div id="cusModal" class="modal cusModal">
            <div class="modal-content">
                <span class="cusclose close">&times;</span>
                <form action="" method="POST">
                    <label for="cus_fname">First name:</label><br>
                    <input type="text" id="cus_fname" name="cus_fname" class="" required><br><br>
                    <label for="cus_lname">Last name:</label><br>
                    <input type="text" id="cus_lname" name="cus_lname" class="" required><br><br>
                    <label for="cus_phone">Phone:</label><br>
                    <input type="number" id="cus_phone" name="cus_phone" required><br><br>
                    <input type="submit" id="newCusSubmit" name="newCusSubmit" value="Insert Customer">
                </form>
            </div>
        </div>
    </div>

    <div id="newCar" class="">
        <button id="carModBtn">New Car</button><br><br>
        <div id="carModal" class="modal carModal">
            <div class="modal-content">
                <span class="carclose close">&times;</span>
                <form action="" method="POST">
                    <label for="car_license">Car License:</label><br>
                    <input type="text" id="car_license" name="car_license" class="" required><br><br>
                    <label for="car_brand">Brand:</label><br>
                    <input type="text" id="car_brand" name="car_brand" class="" required><br><br>
                    <label for="car_model">Model:</label><br>
                    <input type="text" id="car_model" name="car_model" class="" required><br><br>
                    <input type="submit" id="newCarSubmit" name="newCarSubmit" value="Insert Car">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Modal functionality for customer modal
var cusmodal = document.getElementById("cusModal");
var cusbtn = document.getElementById("cusModBtn");
var cusspan = document.getElementsByClassName("cusclose")[0];

cusbtn.onclick = function() {
    cusmodal.style.display = "block";
}

cusspan.onclick = function() {
    cusmodal.style.display = "none";
}

// Modal functionality for car modal
var carmodal = document.getElementById("carModal");
var carbtn = document.getElementById("carModBtn");
var carspan = document.getElementsByClassName("carclose")[0];

carbtn.onclick = function() {
    carmodal.style.display = "block";
}

carspan.onclick = function() {
    carmodal.style.display = "none";
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == cusmodal) {
        cusmodal.style.display = "none";
    } else if (event.target == carmodal) {
        carmodal.style.display = "none";
    }
}

// Prevent page refresh
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

// Live search functionality for customers
function showResultCus(str) {
    if (str.length == 0) {
        document.getElementById("livesearch").innerHTML = "";
        document.getElementById("livesearch").style.border = "0px";
        return;
    }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("livesearch").innerHTML = this.responseText;
            document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
        }
    }
    xmlhttp.open("GET", "includes/liveCusSearch.php?q=" + str, true);
    xmlhttp.send();
}

// Live search functionality for cars
function showResultCar(str) {
    if (str.length == 0) {
        document.getElementById("carLivesearch").innerHTML = "";
        document.getElementById("carLivesearch").style.border = "0px";
        return;
    }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("carLivesearch").innerHTML = this.responseText;
            document.getElementById("carLivesearch").style.border = "1px solid #A5ACB2";
        }
    }
    xmlhttp.open("GET", "includes/liveCarSearch.php?q=" + str, true);
    xmlhttp.send();
}

function handleClick(id, name) {
    document.getElementsByName("customerSearch")[0].value = name; // Fill the input field with the clicked name
    document.getElementById("customerId").value = id; // Store the ID in a hidden field
    document.getElementById("livesearch").innerHTML = ""; // Clear the live search results
    document.getElementById("livesearch").style.border = "0px"; // Remove the border
}

function handleCarClick(id, license) {
    document.getElementsByName("carSearch")[0].value = license; // Fill the input field with the clicked license
    document.getElementById("carId").value = id; // Store the car ID in a hidden field
    document.getElementById("carLivesearch").innerHTML = ""; // Clear the live search results
    document.getElementById("carLivesearch").style.border = "0px"; // Remove the border
}
</script>

<?php
include_once 'includes/footer.php';
?>

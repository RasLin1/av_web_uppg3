<?php
include_once 'includes/header.php';

$customers = $pdo->query('SELECT * FROM project_customers')->fetchAll();
$cars = $pdo->query('SELECT * FROM project_cars')->fetchAll();

if(isset($_POST['newCusSubmit'])){
    $_SESSION['cus_fname'] = $_POST['cus_fname'];
    $_SESSION['cus_lname'] = $_POST['cus_lname'];
    $_SESSION['cus_phone'] = $_POST['cus_phone'];
    $newCus = newCustomer($pdo);
}

if(isset($_POST['newCarSubmit'])){
    $_SESSION['car_license'] = $_POST['car_license'];
    $_SESSION['car_brand'] = $_POST['car_brand'];
    $_SESSION['car_model'] = $_POST['car_model'];
    $newCar = newCar($pdo);
}

if(isset($_POST['newProject'])){
    $_SESSION['customer'] = $_POST['customer'];
    $_SESSION['car'] = $_POST['car'];
    $_SESSION['probDesc'] = $_POST['probDesc'];
    $newCar = newProject($pdo);
}

?>

<div class="container">
    <div id="customerChooser" class="container">
        <div id="existingCustomer" class="row">
            <form id="" method="POST">
                <h4>Choose Customer:</h4>
                
            </form>
        </div>
        
    </div>
    <div id="carChooser" class="container">
    <div id="existingCar" class="row">
            <form id="" method="POST">
                <h4>Choose Car:</h4>
                
            </form>
        </div>
        
    </div>
    <form method="POST">
        <label for="customer">Customer:</label><br>
            <select id="customer" name="customer">
                <?php
                foreach ($customers as $row)
                {
                echo "<option value='{$row['cust_id']}' name='{$row['cust_id']}'>{$row['cust_fname']} {$row['cust_lname']}</option>";
                }
                ?>
            </select><br><br>
        <label for="car">Car:</label><br>
            <select id="car" name="car">
                <?php
                foreach ($cars as $row)
                    {
                    echo "<option value='{$row['project_car_id']}' name='{$row['project_car_id']}'>{$row['project_car_license']} - {$row['project_car_brand']}{$row['project_car_model']}</option>";
                    }
                ?>
            </select><br><br>
            <label for="probDesc">Problem Description</label><br>
            <textarea id="probDesc" name="probDesc" rows="4" cols="50"></textarea><br><br>
            <input type="submit" name="newProject"></input><br><br>
    </form>


    <div id="newCustomer" class="">
            <!-- Trigger/Open The Modal -->
            <button id="cusModBtn">New Customer</button><br><br>

            <!-- The Modal -->
            <div id="cusModal" class="modal cusModal">

            <!-- Modal content -->
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
            <!-- Trigger/Open The Modal -->
            <button id="carModBtn">New Car</button><br><br>

            <!-- The Modal -->
            <div id="carModal" class="modal carModal">

            <!-- Modal content -->
            <div class="modal-content">
                <span class="carclose close">&times;</span>
                <form action="" method="POST">
                <label for="car_license">Car License:</label><br>
                <input type="text" id="car_license" name="car_license" class=""><br><br>
                <label for="car_brand">Brand:</label><br>
                <input type="text" id="car_brand" name="car_brand" class=""><br><br>
                <label for="car_model">Model:</label><br>
                <input type="text" id="car_model" name="car_model" class=""><br><br>
                <input type="submit" id="newCarSubmit" name="newCarSubmit" value="Insert Car">
                </form>
            </div>
            </div> 
    </div>
    
</div>


<script>
// Get the modal
var cusmodal = document.getElementById("cusModal");

// Get the button that opens the modal
var cusbtn = document.getElementById("cusModBtn");

// Get the <span> element that closes the modal
var cusspan = document.getElementsByClassName("cusclose")[0];

// When the user clicks on the button, open the modal
cusbtn.onclick = function() {
    cusmodal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
cusspan.onclick = function() {
    cusmodal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == cusmodal) {
    cusmodal.style.display = "none";
  }
}

// Get the modal
var carmodal = document.getElementById("carModal");

// Get the button that opens the modal
var carbtn = document.getElementById("carModBtn");

// Get the <span> element that closes the modal
var carspan = document.getElementsByClassName("carclose")[0];

// When the user clicks on the button, open the modal
carbtn.onclick = function() {
    carmodal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
carspan.onclick = function() {
    carmodal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == carmodal) {
    carmodal.style.display = "none";
  }
}

if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}

</script>

<style>
    /* The Modal (background) */
.Modal {
    display: none; /* Hidden by default */
	position: fixed; /* Stay in place */
	z-index: 1; /* Sit on top */
	left: 0;
	top: 0;
	width: 100%; /* Full width */  
	height: 100%; /* Full height */
	overflow: auto; /* Enable scroll if needed */
	background-color: rgb(0,0,0); /* Fallback color */
	background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  }


  
  /* Modal Content/Box */
  .modal-content {
	background-color: #fefefe;
	margin: 15% auto; /* 15% from the top and centered */
	padding: 20px;
	border: 1px solid #888;
	width: 80%; /* Could be more or less, depending on screen size */
    color: black;
  }
  
  /* The Close Button */
  .close {
	color: #aaa;
	float: right;
	font-size: 28px;
	font-weight: bold;
  }
  
  .close:hover,
  .close:focus {
	color: black;
	text-decoration: none;
	cursor: pointer;
  }
</style>
<?php 
include_once 'includes/footer.php';
?>
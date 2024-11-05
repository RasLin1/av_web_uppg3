<?php
include_once 'includes/header.php';

$customers = $pdo->query('SELECT * FROM project_customers')->fetchAll();

if (isset($_POST['removeCust'])) {
    $_SESSION['cust_id'] = $_POST['cust_id'];
    $removeCust = removeCustomer($pdo);
}

// Handle new customer submission
if (isset($_POST['newCusSubmit'])) {
    $_SESSION['cus_fname'] = $_POST['cus_fname'];
    $_SESSION['cus_lname'] = $_POST['cus_lname'];
    $_SESSION['cus_phone'] = $_POST['cus_phone'];
    $newCus = newCustomer($pdo);
}

// Handle customer update submission
if (isset($_POST['saveUpdatedCust'])) {
    $custId = $_POST['cust_id'];
    $custFname = $_POST['cust_fname'];
    $custLname = $_POST['cust_lname'];
    $custPhone = $_POST['cust_phone'];

    // Update customer in the database and XML
    updateCustomer($pdo, $custId, $custFname, $custLname, $custPhone);
}

?>

<h5>All Customers</h5>

<?php 
foreach ($customers as $row) {
    echo "<div class='row'>
        <strong>{$row['cust_fname']} {$row['cust_lname']} {$row['cust_phone']}</strong><br><br>
        <form method='POST' style='display:inline;' onsubmit='return false;'> <!-- Prevent default form submission -->
            <input type='hidden' value='{$row['cust_id']}' name='cust_id'>
            <button type='button' onclick='openUpdateModal({$row['cust_id']}, \"{$row['cust_fname']}\", \"{$row['cust_lname']}\", \"{$row['cust_phone']}\")'>Update Customer</button>
        </form>
        <form method='POST' style='display:inline;'>
            <input type='hidden' value='{$row['cust_id']}' name='cust_id'>
            <input name='removeCust' type='submit' value='Remove Customer'>
        </form>
    </div>";
}
?>

<!-- New Customer Modal -->
<div id="newCustomer" class="">
<br><br><button id="cusModBtn">New Customer</button><br><br>
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

<!-- Update Customer Modal -->
<div id="updateCustomer" class="">
    <div id="updateModal" class="modal updateModal">
        <div class="modal-content">
            <span class="updateclose close">&times;</span>
            <form action="" method="POST">
                <input type="hidden" id="update_cust_id" name="cust_id">
                <label for="update_cust_fname">First name:</label><br>
                <input type="text" id="update_cust_fname" name="cust_fname" required><br><br>
                <label for="update_cust_lname">Last name:</label><br>
                <input type="text" id="update_cust_lname" name="cust_lname" required><br><br>
                <label for="update_cust_phone">Phone:</label><br>
                <input type="number" id="update_cust_phone" name="cust_phone" required><br><br>
                <input type="submit" name="saveUpdatedCust" value="Save Changes">
            </form>
        </div>
    </div>
</div>

<script>
// Modal functionality for the new customer modal
var cusmodal = document.getElementById("cusModal");
var cusbtn = document.getElementById("cusModBtn");
var cusspan = document.getElementsByClassName("cusclose")[0];

cusbtn.onclick = function() {
    cusmodal.style.display = "block";
}

cusspan.onclick = function() {
    cusmodal.style.display = "none";
}

// Open the update modal with customer data
function openUpdateModal(custId, fname, lname, phone) {
    document.getElementById("update_cust_id").value = custId;
    document.getElementById("update_cust_fname").value = fname;
    document.getElementById("update_cust_lname").value = lname;
    document.getElementById("update_cust_phone").value = phone;
    
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
    if (event.target == cusmodal) {
        cusmodal.style.display = "none";
    } else if (event.target == document.getElementById("updateModal")) {
        document.getElementById("updateModal").style.display = "none";
    }
}



</script>

<?php
include_once 'includes/footer.php';



?>

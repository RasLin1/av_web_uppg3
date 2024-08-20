<?php
include_once 'includes/header.php';

$userRoles = $pdo->query('SELECT * FROM project_worker_role')->fetchAll();

if(isset($_POST['createUser'])){
    register($pdo);
}

?>

<h2>User Register</h2>
<div class="mw-500 mx-auto">
<form action="" method="POST">
  <label for="u_name">Username:</label><br>
  <input type="text" id="u_name" name="u_name" class=""><br><br>
  <label for="user_role">Role:</label>
        <select id="user_role" name="user_role">
		<?php
		foreach ($userRoles as $row)
				{
				echo "<option value='{$row['worker_role_id']}' name='{$row['worker_role_id']}'>{$row['worker_role_name']}</option>";
				}
				?>
        </select><br><br>
  <label for="u_pass">Enter Password:</label><br>
  <input type="password" id="u_pass" name="u_pass" class=""><br><br>
  <input type="submit" name="createUser" value="Register">
</form><br>
</div>

<?php
include_once 'includes/footer.php';
?>
<?php
include_once 'includes/header.php';

if(isset($_POST['login'])){
    $loginUser = login($pdo);

    if($loginUser == "true"){
        echo "Login Success";
    }

    elseif($loginUser == "falsen"){
        echo "<div class='alert alert-danger' role='alert'>
        Wrong username or email!
        </div>";
    }

    elseif($loginUser == "falsep"){
        echo "<div class='alert alert-danger' role='alert'>
        Wrong password!
        </div>";
    }
}

if(isset($_POST['logout'])){
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// $_POST['uname'], $_POST['u_pass']
?>

<!--
<div class="container"><br>
    <div class="row">
        <form method="POST" action="mechanic.php" >
            <input type="submit" value="Mechanic"></input>
        </form>
    </div><br><br>
    <div class="row">
        <form method="POST" action="accounting.php" >
            <input type="submit" value="Accounting"></input>
        </form>
    </div><br><br>
    <div class="row">
        <form method="POST" action="boss.php" >
            <input type="submit" value="Boss"></input>
        </form>
    </div><br><br>
</div>
-->

<div class="container" id="loginPage">
    <div class="row">
        <h2>User Login</h2>
        <iframe name="votar" style="display:none;"></iframe>
        <form action="" method="POST" target="">
            <label for="uname">Username:</label><br>
            <input type="text" id="uname" name="uname" required="required"><br><br>
            <label for="u_pass">Enter Password:</label><br>
            <input type="password" id="u_pass" name="u_pass" required="required"><br><br>
            <input type="submit" name="login" value="Login">
        </form><br>

        <form method="POST" action="">
            <input type="submit" name="logout" value="Logout"></input>
        </form>
    </div>
</div>


<?php 
include_once 'includes/footer.php';
?>


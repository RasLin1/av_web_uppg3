<?php
include_once 'includes/header.php';

$activeProjects = $pdo->query("SELECT * FROM projects INNER JOIN project_cars ON projects.project_car_fk = project_cars.project_car_id WHERE project_status_fk = '2'")->fetchAll();

$queuedProjects = $pdo->query("SELECT * FROM projects INNER JOIN project_cars ON projects.project_car_fk = project_cars.project_car_id WHERE project_status_fk = '1'")->fetchAll();

?>

<div class="container">
    <div id="active_projects" class="container">
        <h3>Active Projects</h3>
        <?php 
        if($activeProjects == '0'){
            echo "No Active Projects";
        }
        else{
        foreach ($activeProjects as $row){
            $currCarId = $row['project_car_fk'];
            $currCar = $pdo->query("SELECT * FROM project_cars WHERE project_car_id = $currCarId");
            echo "<div class='row'>
                <string>{$row['project_car_license']}  - {$row['project_car_brand']} {$row['project_car_model']}</string>
                <form method='POST'>
                    <input type='hidden' value=''></input>
                    <input type='submit' value='Läs Mera'></input>
                </form>
            </div>";}
        }
        ?>
    </div>
    <div id="queued_projects" class="container">
    <h3>Queued Projects</h3>
        <?php 
        foreach ($queuedProjects as $row){;
            echo "<div class='row'>
                <string>{$row['project_car_license']}  - {$row['project_car_brand']} {$row['project_car_model']}</string>
                <form method='POST'>
                    <input type='hidden' value=''></input>
                    <input type='submit' value='Läs Mera'></input>
                </form>
            </div>";
        }
        ?>
    </div>
    <div id="view_buttons" class="container">
    <div class="row">
        <form method="POST" action="allCustomers.php" >
            <input type="submit" value="View All Customers"></input>
        </form>
    </div><br><br>
    <div class="row">
        <form method="POST" action="allCars.php" >
            <input type="submit" value="View All Cars"></input>
        </form>
    </div><br><br>
    <div class="row">
        <form method="POST" action="newProject.php" >
            <input type="submit" value="New Project"></input>
        </form>
    </div><br><br>
    </div>
</div>

<?php 
include_once 'includes/footer.php';
?>


<?php 
include 'includes/config.php';
include 'includes/functions.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

?>

<!DOCTYPE html>
<html>
<head>

	<title>Poweroll</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="assets/script/script.js" defer></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
</head>

<body>
<div class="container">
<div id="nav">
	<div>
		<h2>Poweroll</h2>
		<ul>
            <li><a href="index.php">Front Page</a></li>
			<li><a href="mechanic.php">Mechanic</a></li>
            <li><a href="accounting.php">Accounting</a></li>
            <li><a href="boss.php">Boss</a></li>
		</ul>
	</div>

</div>
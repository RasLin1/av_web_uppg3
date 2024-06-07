<?php

function cleanInput($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function addScore($pdo){
	$moves = cleanInput($_POST['moves']);
	$time = cleanInput($_POST['time']);
	$stmt_insertNewScore = $pdo->prepare('INSERT INTO highscore (moves, time) VALUES (:moves, :time)');
	$stmt_insertNewScore->bindParam(':moves', $moves, PDO::PARAM_STR);
	$stmt_insertNewScore->bindParam(':time', $time, PDO::PARAM_STR);
	$stmt_insertNewScore->execute();
	return TRUE;
}

function sortByOrder($a, $b) {
    if ($a['time'] > $b['time']) {
        return 1;
    } elseif ($a['time'] < $b['time']) {
        return -1;
    }
    return 0;
}

?>
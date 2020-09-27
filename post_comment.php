<?php
	error_reporting(0);
	require_once("database.php");
	session_start();
	$rating = $_POST['rating'];
	$text = $_POST['text'];
	$uid = $_SESSION["user_id"];
	$product_id = $_POST['id'];
	if ($stmt = $mysqli->prepare("INSERT INTO `ratings` (`product_id`, `user_id`, `rating`, `text`) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE `rating` = ? , `text` = ?, `timestamp` = current_timestamp()")) {
		$stmt->bind_param("iiisis", $product_id, $uid, $rating, $text, $rating, $text);
		$stmt->execute();
	}
?>

<?php
	session_start();
	error_reporting(0);
	$id = $_POST["id"];
	if (isset($_POST['quantity']) && $_POST['quantity'] > 0) {
		if (!in_array($id, array_keys($_SESSION['cart']))) {
			array_push($_SESSION['cart'], array($id, 'quantity' => 0));
		}
		$_SESSION['cart'][$id]['quantity'] = $_SESSION['cart'][$id]['quantity'] + $_POST['quantity'];
	}
	$sum = 0;
	foreach (array_keys($_SESSION['cart']) as $key) {
		$sum += $_SESSION['cart'][$key]['quantity'];
	}
	echo $sum;
?>

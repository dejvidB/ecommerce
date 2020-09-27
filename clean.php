<?php
	error_reporting(0);
	session_start();
	$action = $_POST['action'];

	if ($action == 'clean') {
		unset($_SESSION['cart']);
	} elseif ($_POST['action'] == 'remove' && isset($_POST['id'])) {
		$_SESSION['cart'] = array_except($_SESSION['cart'], [$_POST['id']]);
		$_SESSION['cart'] = check($_SESSION['cart']);
	} elseif ($_POST['action'] == 'update' && isset($_POST['id']) && isset($_POST['qty']) && $_POST['qty'] >= 0 && $_POST['qty'] <= 100) {
		$_SESSION['cart'][$_POST['id']]['quantity'] = $_POST['qty'];
		$_SESSION['cart'] = check($_SESSION['cart']);
	}

	function array_except($array, $keys){
		return array_diff_key($array, array_flip((array) $keys));
	}

	function check($array){
		$sum = 0;
		foreach (array_keys($array) as $key) {
			$sum += $array[$key]['quantity'];
		}
		if ($sum == 0) {
			unset($array);
		}
		return $array;
	}
?>

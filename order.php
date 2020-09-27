<?php
	error_reporting(0);
	require_once("database.php");
	session_start();
	$_SESSION['mail'] = $_POST['mail'];
	$_SESSION['name'] = $_POST['name'];
	$_SESSION['lastname'] = $_POST['lastname'];
	$_SESSION['country'] = $_POST['country'];
	$_SESSION['region'] = $_POST['region'];
	$_SESSION['city'] = $_POST['city'];
	$_SESSION['street'] = $_POST['address'];
	$_SESSION['zip'] = intval($_POST['zip']);
	if ($_POST['action'] == "order") {
		$first = true;
		$total = 0;
		if (count($_SESSION['cart']) > 0) {
			foreach (array_keys($_SESSION['cart']) as $key) {
				if ($key > 0 && $_SESSION['cart'][$key]['quantity'] > 0) {
					if ($first) {
						mysqli_query($mysqli, "INSERT INTO `orders`() VALUES ()");
						$ord_id = mysqli_insert_id($mysqli);
						echo $ord_id;
						$first = false;
						if (isset($_SESSION['user_id'])) {
							$uid = $_SESSION['user_id'];
						} else {
							$uid = null;
						}
						if ($stmt = $mysqli->prepare("INSERT INTO `order_info`(`order_id`, `user_id`, `mail`, `name`, `lastname`, `country`, `region`, `city`, `address`, `zip`) VALUES (?,?,?,?,?,?,?,?,?,?)")) {
							$stmt->bind_param("iissssssss", $ord_id, $uid, $_POST['mail'], $_POST['name'], $_POST['lastname'], $_POST['country'], $_POST['region'], $_POST['city'], $_POST['address'], $_POST['zip']);
							$stmt->execute();
							$result = $stmt->get_result();
							$stmt->fetch();
						}
					}

					if ($stmt = $mysqli->prepare("SELECT * FROM `products` WHERE `id` = ?")) {
						$stmt->bind_param("i", $key);
						$stmt->execute();
						$result = $stmt->get_result();
						$stmt->fetch();
						$row = $result->fetch_assoc();
						$price = $row['price'];
						$quantity = $_SESSION['cart'][$key]['quantity'];
						$sub_total = $price * $quantity;
						$total += $sub_total;
						$stmt = $mysqli->prepare("INSERT INTO `order_products`(`order_id`, `product_id`, `price`, `quantity`) VALUES (?,?,?,?)");
						$stmt->bind_param("iidi", $ord_id, $key, $price, $quantity);
						$stmt->execute();
					}
				}
			}
		}
		$stmt = $mysqli->prepare("UPDATE `orders` SET `total`= ? WHERE `order_id` = ?");
		$stmt->bind_param("di", $total, $ord_id);
		$stmt->execute();
	} elseif ($_POST['action'] == "update") {
		$stmt = $mysqli->prepare("UPDATE `order_info` SET `mail`=?,`name`=?,`lastname`=?,`country`=?,`region`=?,`city`=?,`address`=?,`zip`=? WHERE `order_id` = ?");
		$stmt->bind_param("sssssssii", $_POST['mail'], $_POST['name'], $_POST['lastname'], $_POST['country'], $_POST['region'], $_POST['city'], $_POST['address'], intval($_POST['zip']), $_POST['order_id']);
		$stmt->execute();
		echo $_POST['order_id'];
	}
?>

<?php
	require_once("database.php");
	session_start();
	$u_id = $_SESSION['user_id'];
	$orders_on_page = 5;
	$page = 0;
	$return_arr = array();
	if ($stmt = $mysqli->prepare("SELECT orders.* FROM orders INNER JOIN order_info ON orders.order_id = order_info.order_id AND order_info.user_id = ? ORDER BY `orders`.`date` DESC")){
		$stmt->bind_param("i", $u_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->fetch();
		while($order = $result->fetch_assoc()){
			$row_array = array();
			$row_array['order_id'] = $order['order_id'];
			$row_array['date'] = date('d/m/Y H:i', strtotime($order['date']));
			$row_array['total'] = $order['total'];
			$row_array['state'] = $order['state'];
			array_push($return_arr, $row_array);
		}
	}

	echo json_encode($return_arr);
?>

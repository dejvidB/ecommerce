<?php
session_start();
require_once("database.php");
$u_id = $_SESSION['user_id'];
$order_id = $_POST['order_id'];

if ($stmt = $mysqli->prepare("SELECT order_info.mail, CONCAT(`order_info`.`name`, ' ' ,`order_info`.`lastname`) AS `receiver`, CONCAT(order_info.address, ' ',order_info.city, ' ',order_info.zip, ' ', order_info.region, ' ', order_info.country) AS `address` FROM order_info WHERE `order_id` = ? AND user_id = ?")) {
	$stmt->bind_param("ii", $order_id, $u_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->fetch();
	while ($order = $result->fetch_assoc()) {
		echo '
			<tr class="order_tr" id="' . $order_id . '_info">
				<td colspan="5" style="overflow:hidden;">
				<div>
					<div class="order-tracking-products">
						<table class="table table-bordered">
							<thead style="background: #edf2f5;">
								<tr>
									<th class="text-center">Products</th>
									<th class="text-center">Quantity</th>
									<th class="text-center">Price</th>
									<th class="text-center">Sub-total</th>
								</tr>
							</thead>
							<tbody>';
		if ($stmt2 = $mysqli->prepare("SELECT order_products.product_id, order_products.price, order_products.quantity, products.title FROM `order_products` INNER JOIN products ON order_products.product_id = products.id WHERE order_products.order_id = ?")) {
			$stmt2->bind_param("i", $order_id);
			$stmt2->execute();
			$result2 = $stmt2->get_result();
			$stmt2->fetch();
			while ($product = $result2->fetch_assoc()) {
				echo '
								<tr>
									<td>
										<a target="_blank" title="' . $product['title'] . '" href="product?id=' . $product['product_id'] . '">' . $product['title'] . '</a>
									</td>
									<td class="text-center">' . $product['quantity'] . '</td>
									<td class="text-center">$' . $product['price'] . '<span class="icon publicicons-ok is-rect is-color-orange"></span></td>
									<td class="text-center order-tracking-product-price">
										<strong>$' . $product['quantity'] * $product['price'] . '</strong>
									</td>
								</tr>
						';
			}
		}
		echo '				</tbody>
						</table>
					</div>
					<div class="order-complete-total" style="border:1px solid black;">
						<div class="pull-left order-total-label is-color-black">
							<b>TOTAL:</b>
						</div>
					<div class="sell-price">
						<div class="pull-right teaser--product-final-price is-color-orange">
							<strong id="' . $order_id . '_total"></strong>
						</div>
						<div class="sell-price-vat"></div>
					</div>
				</div>
				</br>
				</br>
				<div class="order-complete-extra-details">
					<div class="row">
						<div class="col-xs-4">
							<div><strong>Receiver Name</strong></div>
							<div>
									' . $order['receiver'] . '</br><strong>Email Address</strong></br>' . $order['mail'] . '
							</div>
						</div>
						<div class="col-xs-4 text-center">
							<div><strong>Shipping address</strong></div>
							<p style="word-wrap: break-word; white-space: normal;"><a target="_blank" href="https://www.google.com/maps/place/' . $order['address'] . '">' . $order['address'] . '</a></p>
						</div>
						<div class="col-xs-4">
							<div><strong class="pull-right">Payment method</strong></div>
							</br>
							<div class="pull-right"><i class="fa fa-cc-paypal"></i> PayPal</div>
						</div>
					</div>
				</div>
			</div>
		</td></tr>';
	}
}
?>

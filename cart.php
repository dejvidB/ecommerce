<html>

<head>
	<?php include("navbar.php"); ?>
</head>

<body>
	<div class="container">
		<div class="row container">
			<?php
			if (count($_SESSION['cart']) > 0) {
				$total = 0;
				$item_count = 0;
				foreach (array_keys($_SESSION['cart']) as $key) {
					if ($key > 0 && $_SESSION['cart'][$key]['quantity'] > 0) {
						require_once("database.php");
						if ($stmt = $mysqli->prepare("SELECT * FROM `products` WHERE `id` = ?")) {
							$stmt->bind_param("i", $key);
							$stmt->execute();
							$result = $stmt->get_result();
							$stmt->fetch();
							$row = $result->fetch_assoc();
							$price = $row['price'];
							$quantity = $_SESSION['cart'][$key]['quantity'];
							$sub_total = $price * $quantity;
							$alias = $row['alias'];
							echo "
							<div class='row' style='margin-top:2%; margin-bottom:2%;'>
								<div class='col-md-8'>
									<div class='product-name m-b-10px'><h4><a href='product/{$alias}/{$key}'>{$row['title']}</a></h4></div>
									<form>
										<div>Quantity:</div>
										<div class='product-id' style='display:none;'>{$key}</div>
										<div class='input-group'>
											<input type='number' name='quantity' value='{$quantity}' class='form-control qty' id='{$key}' min='1' />
											<span class='input-group-btn'>
												<button id='{$key}' class='btn btn-default update'>Update</button>
											</span>
										</div>
									</form>
									<button id='{$key}' class='btn btn-default remove'>
										Remove from cart
									</button>
								</div>
								<div class='col-md-4'>
									<h4>Price: &#36;" . number_format($price, 2, '.', ',') . "</h4>
									<h5>Sub-total: &#36;" . number_format($sub_total, 2, '.', ',') . "</h4>
								</div>
							</div>";
							$item_count += $quantity;
							$total += $sub_total;
							$stmt->close();
						}
					}
				}
				echo "<hr>
						<div class='row'>
							<div class='col-md-2'>
								<button id='clean_cart' class='btn'>Clean cart</button>
							</div>
							<div class='col-md-6'></div>
							<div class='col-md-4'>
								<div class='cart-row'>
									<h4 class='m-b-10px'>Total ({$item_count} items)</h4>
									<h4>&#36;" . number_format($total, 2, '.', ',') . "</h4>
									<a href='checkout' class='btn btn-success m-b-10px'>
										<span class='glyphicon glyphicon-shopping-cart'></span> Proceed to Checkout
									</a>
								</div>
							</div>
						</div>";
			} else {
				echo "<div class='col-md-12'>
						<div class='alert alert-danger'>
							No products found in your cart!
						</div>
					</div>";
			}
			?>
		</div>
	</div>

	<script src="./js/cart.js"></script>
</body>

</html>

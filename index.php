<html>

<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<?php include("navbar.php"); ?>
</head>

<body>
	<div class="wrapper">
		<div class="container">
			<div id="deals_of_the_day">
				<?php require_once("deals_of_the_day.php"); ?>
			</div>
			<div id="best_sellers">
				<?php
				if ($stmt = $mysqli->prepare("SELECT DISTINCT
										products.id,
										products.alias,
										products.title,
										products.old_price,
										products.price
									FROM
										products
									INNER JOIN
										order_products ON (order_products.product_id = products.id)
									INNER JOIN
										orders ON (order_products.order_id = orders.order_id)
									ORDER BY (SELECT SUM(quantity) FROM order_products WHERE order_products.product_id = products.id) DESC
									LIMIT 10")) {
					$stmt->execute();
					$result = $stmt->get_result();
					$stmt->fetch();
					if (mysqli_num_rows($result) > 0) {
						echo '	<h2>Best sellers</h2>
								<div class="main-div-container">
									<button class="btn btn-left" style="grid-area: btn-left; background-color:white; border:none;"><i class="fa fa-chevron-left"></i></button>
									<div class="grid-container" style="grid-area: products-grid">
		  								<main class="grid-item main">
											<div class="items">';
						while ($myrow = $result->fetch_assoc()) {
							$id = $myrow['id'];
							$alias = $myrow['alias'];
							$title = $myrow['title'];
							$old_price = $myrow['old_price'];
							$price = $myrow['price'];
							$reviews = $mysqli->query("SELECT SUM(`rating`) AS `sum` , COUNT(*) AS `count` FROM `ratings` WHERE `product_id` = {$id}");
							while ($row = $reviews->fetch_assoc()) {
								$count_reviews = $row['count'];
								$reviews_sum = $row['sum'];
							}
							include("product_template.php");
						}
						echo '				</div>
		   								</main>
									</div>
									<button class="btn btn-right" style="grid-area: btn-right; background-color:white; border:none;"><i class="fa fa-chevron-right"></i></button>
								</div>';
					}
				}
				?>
			</div>
			<div id="recommendations">
				<?php if (!empty($_SESSION['history'])) {
					require_once("recommendations.php");
				} ?>
			</div>
			<div id="already_seen">
				<?php if (!empty($_SESSION['history'])) {
					$shown = array();
					echo '	<h2>In case you want to see them again..</h2>';
					echo '	<div class="main-div-container">
								<button class="btn btn-left" style="grid-area: btn-left; background-color:white; border:none;"><i class="fa fa-chevron-left"></i></button>
									<div class="grid-container" style="grid-area: products-grid">
										<main class="grid-item main">
											<div class="items">';
					foreach (array_reverse($_SESSION['history']) as $id) {
						if (!in_array($id, $shown)) {
							array_push($shown, $id);
							if ($stmt = $mysqli->prepare("SELECT * FROM products WHERE products.id = ?")) {
								$stmt->bind_param("i", $id);
								$stmt->execute();
								$result = $stmt->get_result();
								$stmt->fetch();
								if (mysqli_num_rows($result) > 0) {
									while ($myrow = $result->fetch_assoc()) {
										$id = $myrow['id'];
										$alias = $myrow['alias'];
										$title = $myrow['title'];
										$old_price = $myrow['old_price'];
										$price = $myrow['price'];
										unset($count_reviews);
										include("product_template.php");
									}
								}
							}
						}
					}
					echo '					</div>
										</main>
									</div>
									<button class="btn btn-right" style="grid-area: btn-right; background-color:white; border:none;"><i class="fa fa-chevron-right"></i></button>
								</div>';
				}
				?>
			</div>
		</div>
	</div>

	<script src="./js/index.js"></script>
</body>
</html>

<?php
	error_reporting(0);
	require_once("database.php");
	$limit = 10;
	$shown = array();
	echo '	<h2>Recommended for you</h2>
			<div class="main-div-container">
				<button class="btn btn-left" style="grid-area: btn-left; background-color:white; border:none;"><i class="fa fa-chevron-left"></i></button>
				<div class="grid-container" style="grid-area: products-grid">
					<main class="grid-item main">
					<div class="items">';
	foreach (array_reverse($_SESSION['history']) as $id) {
		if ($stmt = $mysqli->prepare("SELECT * FROM products WHERE products.sub_id = (SELECT products.sub_id FROM products WHERE products.id = ?) AND products.id <> ?")) {
			$stmt->bind_param("ii", $id, $id);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->fetch();
			if (mysqli_num_rows($result) > 0) {
				while ($myrow = $result->fetch_assoc()) {
					if (!in_array(intval($myrow['id']), $shown) && $myrow['id'] != array_values(array_slice($_SESSION['history'], -1))[0]) {
						array_push($shown, intval($myrow['id']));
						$reviews = $mysqli->query("SELECT SUM(`rating`) AS `sum` , COUNT(*) AS `count` FROM `ratings` WHERE `product_id` = {$myrow['id']}");
						while ($row = $reviews->fetch_assoc()) {
							$count_reviews = $row['count'];
							$reviews_sum = $row['sum'];
						}
						$id = $myrow['id'];
						$alias = $myrow['alias'];
						$title = $myrow['title'];
						$old_price = $myrow['old_price'];
						$price = $myrow['price'];
						include("product_template.php");
					}
				}
			}
		}
	}
	echo '			</div>
				</main>
			</div>
			<button class="btn btn-right" style="grid-area: btn-right; background-color:white; border:none;"><i class="fa fa-chevron-right"></i></button>
			</div>';
?>

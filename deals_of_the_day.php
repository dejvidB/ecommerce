<?php
	require_once("database.php");
	if ($stmt = $mysqli->prepare("SELECT * FROM `products` WHERE `old_price` > `price` ORDER BY (`price`-`old_price`)/`price`*100")){
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->fetch();
		if(mysqli_num_rows($result) > 0){
			echo '	<h2>Get \'em before it\'s too late!</h2>
					<div class="main-div-container">
						<button class="btn btn-left" style="grid-area: btn-left; background-color:white; border:none;"><i class="fa fa-chevron-left"></i></button>
						<div class="grid-container" style="grid-area: products-grid">
							<main class="grid-item main">
								<div class="items">';
			while($myrow = $result->fetch_assoc()) {
				$reviews = $mysqli->query("SELECT SUM(`rating`) AS `sum` , COUNT(*) AS `count` FROM `ratings` WHERE `product_id` = {$myrow['id']}");
				while($row = $reviews->fetch_assoc()){
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
			echo '				</div>
						</main>
						</div>
						<button class="btn btn-right" style="grid-area: btn-right; background-color:white; border:none;"><i class="fa fa-chevron-right"></i></button>
					</div>';
		}
	}
?>

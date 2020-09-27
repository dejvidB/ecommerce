<html>

<head>
	<?php
	require_once("navbar.php");
	?>
	<link rel="stylesheet" type="text/css" href="./css/category.css">
</head>

<body>
	<div class="container">
		<?php
		$exists = false;
		if (isset($_GET['alias'])) {
			if ($stmt = $mysqli->prepare("SELECT `category_id` FROM `categories` WHERE `alias` = ?")) {
				$stmt->bind_param("s", $_GET['alias']);
				$stmt->execute();
				$result = $stmt->get_result();
				$stmt->fetch();
				while ($row = $result->fetch_assoc()) {
					$exists = true;
					$cat_id = $row['category_id'];
				}
			}
		} else if (isset($_GET['id'])) {
			$cat_id = $_GET['id'];
			$exists = true;
		}

		if ($exists) {
			if ($stmt = $mysqli->prepare("SELECT `sub_id`, `name`, `alias` FROM `sub_categories` WHERE `cat_id` = ?")) {
				$stmt->bind_param("i", $cat_id);
				$stmt->execute();
				$result = $stmt->get_result();
				$stmt->fetch();
				if ($stmt2 = $mysqli->prepare("SELECT `name`,`description` FROM `categories` WHERE `category_id` = ?")) {
					$stmt2->bind_param("i", $cat_id);
					$stmt2->execute();
					$result2 = $stmt2->get_result();
					$stmt2->fetch();
					while ($myrow2 = $result2->fetch_assoc()) {
						$exists = true;
						echo "<h1>{$myrow2['name']}</h1><h4>{$myrow2['description']}</h4>";
					}
					if ($stmt4 = $mysqli->prepare("SELECT * FROM `sub_categories` WHERE `cat_id` = ?")) {
						$stmt4->bind_param("i", $cat_id);
						$stmt4->execute();
						$result4 = $stmt4->get_result();
						$stmt4->fetch();
						echo "<ul id='sub_list'>";
						while ($myrow4 = $result4->fetch_assoc()) {
							$sub_id = $myrow4['sub_id'];
							$name = $myrow4['name'];
							$sub_alias = $myrow4['alias'];
							$cat_alias = $_GET['alias'];
							echo "<li><h4><a href='category/{$cat_alias}/{$sub_alias}'>{$name}</a></h4></li>";
						}
						echo "</ul>";
					}
				}
				while ($myrow = $result->fetch_assoc()) {
					if ($stmt3 = $mysqli->prepare("SELECT * FROM `products` WHERE `sub_id` = ? LIMIT 4")) {
						$stmt3->bind_param("i", $myrow['sub_id']);
						$stmt3->execute();
						$result3 = $stmt3->get_result();
						$stmt3->fetch();
						if (mysqli_num_rows($result3) > 0) {
							echo "<hr><h3>{$myrow['name']}</h3>" . '<div class="items">';
							while ($myrow3 = $result3->fetch_assoc()) {
								echo '<div class="col-xs-12 col-md-3">';
								$id = $myrow3['id'];
								$alias = $myrow3['alias'];
								$title = $myrow3['title'];
								$old_price = $myrow3['old_price'];
								$price = $myrow3['price'];
								include("product_template.php");
								echo "</div>";
							}
							echo '</div>';
							$sub_alias = $myrow['alias'];
							echo "<a href='category/{$cat_alias}/{$sub_alias}' class='pull-right'><h4>View all products in {$myrow['name']}</h4></a>";
						}
					}
				}
			}
		} else {
			echo "<div class='alert alert-danger'>";
			echo "Category not found!";
			echo "</div>";
		}
		?>
	</div>

	<script src="./js/category.js"></script>
</body>

</html>

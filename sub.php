<html>

<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<?php
	require_once("navbar.php");
	if (isset($_GET['alias'])) {
		if ($stmt = $mysqli->prepare("SELECT
								`sub_id`, `sub_categories`.`name` AS `sub_name`, `categories`.`name` AS 'cat_name', `categories`.`alias` AS 'cat_alias', `categories`.`description` FROM `sub_categories`
								INNER JOIN `categories` ON `categories`.`category_id` = `sub_categories`.`cat_id`
								WHERE
								`sub_categories`.`alias` = ?")) {
			$stmt->bind_param("s", $_GET['alias']);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->fetch();
			while ($row = $result->fetch_assoc()) {
				$id = $row['sub_id'];
				$cat_alias = $row['cat_alias'];
				$cat_desc = $row['description'];
				$cat_name = $row['cat_name'];
				$sub_name = $row['sub_name'];
			}
		}
	}
	$min = 0;
	$max = 10000;
	if (isset($_GET['min']) && is_numeric($_GET['min']) && $_GET['min'] > 0) {
		$min = $_GET['min'];
	} else {
		if ($stmt = $mysqli->prepare("SELECT MIN(`price`) AS 'min' FROM `products` WHERE `sub_id` = ?")) {
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->fetch();
			while ($row = $result->fetch_assoc()) {
				$min = $row['min'];
			}
		}
	}
	if (isset($_GET['max']) && is_numeric($_GET['max']) && $_GET['max'] < 10000) {
		$max = $_GET['max'];
	} else {
		if ($stmt = $mysqli->prepare("SELECT MAX(`price`) AS 'max' FROM `products` WHERE `sub_id` = ?")) {
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->fetch();
			while ($row = $result->fetch_assoc()) {
				$max = $row['max'];
			}
		}
	}
	if (!isset($_GET['order_by'])) {
		$_GET['order_by'] = 'featured';
	}
	switch ($_GET['order_by']) {
		case "featured":
			$text = 'Featured <span class="caret"></span>';
			break;
		case "price_asc":
			$text = 'Price (Low to High) <span class="caret"></span>';
			break;
		case "price_desc":
			$text = 'Price (High to Low) <span class="caret"></span>';
			break;
		default:
			$text = 'Featured <span class="caret"></span>';
			$_GET['order_by'] = 'featured';
			break;
	}
	$order_by = $_GET['order_by'];
	echo "<script>$(document).ready(function(){
					$('#order_by_btn').html('$text');
					$('#$order_by').addClass('btn-info');
				  });
		  </script>";

	?>

	<link rel="stylesheet" type="text/css" href="./css/sub.css">
</head>

<body>
	<div id="mySidenav" class="sidenav">
		<a href="#" class="pull-right" id="closebtn">&#9932;</a>
		<hr>
		<div id="price_range" style="padding: 8px 8px 8px 8px;">
			<label>Price range:</label>
			<div class="input-group">
				<input id="price_min" type="text" class="form-control" placeholder="From" value="$<?php echo $min; ?>" />
				<span class="input-group-addon">-</span>
				<input id="price_max" type="text" class="form-control" placeholder="To" value="$<?php echo $max; ?>" />
				<div class="input-group-btn">
					<button id="change_prc" class="btn btn-default" type="submit">></button>
				</div>
			</div>
		</div>
		<hr>
	</div>
	<div class="container">
		<div class="row">
			<h4>
				<div style="display:inline;">
					<a title="Home" href="index"><span class="glyphicon glyphicon-home"></span></a> > <a title="<?php echo $cat_desc; ?>" href="category/<?php echo $cat_alias; ?>"><?php echo $cat_name; ?></a> > <?php echo $sub_name; ?>
				</div>
			</h4>
		</div>
		<div class="row">
			<button class="btn btn-default" type="button" id="filters_opener">&#9776; Filters</button>
			<div class="dropdown pull-right">
				<button class="btn btn-default dropdown-toggle" id="order_by_btn" type="button" data-toggle="dropdown">
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" id="order_by_dropdown">
					<li class="dropdown-item"><a id="featured" class="btn" type="button">Featured</a></li>
					<li class="dropdown-item"><a id="price_asc" class="btn" type="button">Price (Low to High)</a></li>
					<li class="dropdown-item"><a id="price_desc" class="btn" type="button">Price (High to Low)</a></li>
				</ul>
			</div>
		</div>
		<div id="filter_list" style="margin:25px 0 25px 0;">
			<div id="filter_container">
				<div class="chip" id="price_chip">
					<?php echo "$" . $min . " - $" . $max; ?>
					<span class="closebtn">&times;</span>
				</div>
			</div>
		</div>

		<?php
		$stmt3 = $mysqli->prepare("SELECT * FROM `products` WHERE `sub_id` = ? AND `price` >= ? AND `price` <= ? ORDER BY `id`");
		switch ($_GET['order_by']) {
			case "price_asc":
				$stmt3 = $mysqli->prepare("SELECT * FROM `products` WHERE `sub_id` = ? AND `price` >= ? AND `price` <= ? ORDER BY `price` ASC LIMIT 4");
				break;
			case "price_desc":
				$stmt3 = $mysqli->prepare("SELECT * FROM `products` WHERE `sub_id` = ? AND `price` >= ? AND `price` <= ? ORDER BY `price` DESC LIMIT 4");
				break;
			default:
				$_GET['order_by'] = 'featured';
				break;
		}
		$stmt3->bind_param("idd", $id, $min, $max);
		$stmt3->execute();
		$result3 = $stmt3->get_result();
		$stmt3->fetch();
		if (mysqli_num_rows($result3) > 0) {
			echo "<div class='items'>";
			while ($myrow3 = $result3->fetch_assoc()) {
				echo '<div class="col-xs-12 col-md-3">';
				$id = $myrow3['id'];
				$reviews = $mysqli->query("SELECT SUM(`rating`) AS `sum` , COUNT(*) AS `count` FROM `ratings` WHERE `product_id` = {$id}");
				while ($row = $reviews->fetch_assoc()) {
					$count_reviews = $row['count'];
					$reviews_sum = $row['sum'];
				}
				$alias = $myrow3['alias'];
				$title = $myrow3['title'];
				$old_price = $myrow3['old_price'];
				$price = $myrow3['price'];
				include("product_template.php");
				echo "</div>";
			}
			echo "</div>";
		} else {
			echo '<div class="alert alert-danger col-md-6 col-md-offset-3 text-center">Sorry, we couldn\'t find any products matching your criteria.</div>';
		}
		?>

	</div>

	<script src="./js/sub.js"></script>
</body>

</html>

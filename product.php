<html>

<head>
	<?php
	include("navbar.php");
	?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="./css/product.css">
</head>

<body>
	<?php
	$product_id = 0;
	if (isset($_GET["id"])) {
		if ($_GET["id"] > 0 && $_GET["id"] < 1000000) {
			$product_id = $_GET["id"];
			if ($stmt = $mysqli->prepare("SELECT products.id, products.title, products.old_price, products.price , sub_categories.sub_id, sub_categories.name AS 'sub_name', sub_categories.alias AS 'sub_alias', categories.category_id, categories.name AS 'cat_name', categories.alias AS 'category_alias', categories.description AS 'category_desc' FROM `products` INNER JOIN `sub_categories` ON `products`.sub_id = `sub_categories`.`sub_id` INNER JOIN `categories` ON `sub_categories`.`cat_id` = `categories`.`category_id` AND `products`.`id` = ?")) {
				$stmt->bind_param("i", $product_id);
				$stmt->execute();
				$result = $stmt->get_result();
				$stmt->fetch();
				while ($myrow = $result->fetch_assoc()) {
					$title = $myrow['title'];
					echo "<title>{$title} - BRAND</title>";
					$old_price = $myrow['old_price'];
					$price = $myrow['price'];
					$cat_name = $myrow['cat_name'];
					$cat_id = $myrow['category_id'];
					$cat_alias = $myrow['category_alias'];
					$cat_desc = $myrow['category_desc'];
					$sub_name = $myrow['sub_name'];
					$sub_id = $myrow['sub_id'];
					$sub_alias = $myrow['sub_alias'];
					array_push($_SESSION['history'], intval($product_id));
				}
				$stmt->close();
			}
		}
	}
	$has_ordered = false;
	$state = "none";
	if (isset($_SESSION['user_id'])) {
		if ($stmt = $mysqli->prepare("SELECT orders.state FROM orders INNER JOIN order_info ON orders.order_id = order_info.order_id AND order_info.user_id = ? INNER JOIN order_products ON order_info.order_id = order_products.order_id AND order_products.product_id = ?")) {
			$stmt->bind_param("ii", $_SESSION['user_id'], $product_id);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->fetch();
			while ($myrow = $result->fetch_assoc()) {
				$state = $myrow['state'];
			}
			if ($state == "Completed") {
				$has_ordered = true;
			}
			$stmt->close();
		}
	}
	?>


	<div class="container">
		<div>
			<h4>
				<div style="display:inline;">
					<a href="index"><span class="glyphicon glyphicon-home"></span></a> > <a title="<?php echo $cat_desc; ?>" href="category/<?php echo $cat_alias; ?>"><?php echo $cat_name; ?></a> > <a href="category/<?php echo $cat_alias . "/" . $sub_alias; ?>"><?php echo $sub_name; ?></a> > <?php echo $title; ?>
				</div>
			</h4>
		</div>

		<?php
		if (isset($title)) { ?>
			<div class="row">
				<div class="col-sm-6 first">
					<div id="myCarousel" class="carousel slide" data-ride="carousel">
						<!-- Indicators -->
						<ol class="carousel-indicators">
							<?php
							$files = glob($product_id . '/*{.jpg,.png}', GLOB_BRACE);
							$pos = 0;
							foreach ($files as $file) {
								echo '
							  <li style="border: 1px solid black;" data-target="#myCarousel" data-slide-to="' . $pos . '"></li>
						';
								$pos += 1;
							}
							?>
						</ol>
						<!-- Wrapper for slides -->
						<div class="carousel-inner">
							<?php
							$files = glob($product_id . '/*{.jpg,.png}', GLOB_BRACE);
							foreach ($files as $file) {
								//echo '<li style="display:inline"><img class="photos img-rounded" src="' . $file . '" alt="..."></img></li>';
								$class = "item";
								if (strpos($file, "background") !== false) {
									$class = "item active";
								}
								echo '
							  <div class="' . $class . '">
								<img class="unzoomed" src="' . $file . '" alt="" style="width:100%;">
							  </div>
						';
							}
							?>

						</div>
						<!-- Left and right controls -->
						<a class="left carousel-control" href="#myCarousel" data-slide="prev">
							<span class="glyphicon glyphicon-chevron-left"></span>
							<span class="sr-only">Previous</span>
						</a>
						<a class="right carousel-control" href="#myCarousel" data-slide="next">
							<span class="glyphicon glyphicon-chevron-right"></span>
							<span class="sr-only">Next</span>
						</a>
					</div>
					<?php

					if ($stmt = $mysqli->prepare("SELECT SUM(`rating`) AS `sum`, COUNT(*) AS `count`,
		(SELECT COUNT(*) FROM `ratings` WHERE `rating` = 1 AND product_id = ?) AS `one`,
		(SELECT COUNT(*) FROM `ratings` WHERE `rating` = 2 AND product_id = ?) AS `two`,
		(SELECT COUNT(*) FROM `ratings` WHERE `rating` = 3 AND product_id = ?) AS `three`,
		(SELECT COUNT(*) FROM `ratings` WHERE `rating` = 4 AND product_id = ?) AS `four`,
		(SELECT COUNT(*) FROM `ratings` WHERE `rating` = 5 AND product_id = ?) AS `five`
		 FROM ratings WHERE product_id = ?")) {
						$stmt->bind_param("iiiiii", $product_id, $product_id, $product_id, $product_id, $product_id, $product_id);
						$stmt->execute();
						$result = $stmt->get_result();
						$stmt->fetch();
						$row = $result->fetch_assoc();
						$sum = $row['sum'];
						$count = $row['count'];
						if ($count > 0) {
							$average = $sum / $count;
							$one = $row['one'];
							$two = $row['two'];
							$three = $row['three'];
							$four = $row['four'];
							$five = $row['five'];
							$reviews_count = $count;
						} else {
							$average = 0;
							$one = 0;
							$two = 0;
							$three = 0;
							$four = 0;
							$five = 0;
							$count = 1;
							$reviews_count = 0;
						}
						$rats = array();
						array_push($rats, $one);
						array_push($rats, $two);
						array_push($rats, $three);
						array_push($rats, $four);
						array_push($rats, $five);
						$styles = array();
						array_push($styles, "progress-bar-danger");
						array_push($styles, "progress-bar-warning");
						array_push($styles, "progress-bar-info");
						array_push($styles, "progress-bar-success");
						array_push($styles, "progress-bar-success progress-bar-striped");
					}
					?>
					<div class="row" id="rating">
						<div class="col-xs-12">
							<div class="well well-sm">
								<div class="row">
									<div class="col-xs-12 col-md-6 text-center">
										<h1 class="rating-num"><?php echo number_format($average, 1); ?></h1>
										<p>out of 5.0</p>
										<div class="rating">
											<?php
											for ($i = 1; $i <= floor($average); $i++) {
												echo '<span class="fa fa-star checked font"></span>';
											}
											if ($average - floor($average) > 0) {
												echo '<i class="fa fa-star-half-o font" aria-hidden="true"></i>';
											}
											for ($i = 1; $i <= 5 - ceil($average); $i++) {
												echo '<span class="glyphicon glyphicon-star-empty star-color font"></span>';
											}
											?>
										</div>
										<div>
											<span class="glyphicon glyphicon-user"></span><?php echo $reviews_count; ?> reviews from verified customers
										</div>
									</div>
									<div class="col-xs-12 col-md-6">
										<div class="rating-desc">
											<?php
											for ($i = 5; $i >= 1; $i--) {
												echo '
											<div class="row" style="display:inline; margin:0px;">
												<div class="col-xs-3 col-md-3 text-right">
													<span class="glyphicon glyphicon-star"></span>' . $i . '
												</div>
												<div class="col-xs-9 col-md-9 pull-right">
													<div class="progress">
														<div class="progress-bar ' . $styles[$i - 1] . '" role="progressbar" aria-valuenow="20"
															aria-valuemin="0" aria-valuemax="100" style="width:' . $rats[$i - 1] / $count * 100 . '%">
															<span class="sr-only">' . $rats[$i - 1] . '</span>
														</div>
													</div>
												</div>
											</div>';
											}
											?>
										</div>
									</div>
								</div>
								<div class="row">
									<a id="go_to_reviews" class="col-xs-offset-5" href="#reviews">Go to reviews</a>
								</div>
							</div>
						</div>
					</div>
					<div id="reviews">
						<div id="review_place">

						</div>
						<a href="#" id="load_more" class="btn pull-right">Load older reviews</a>
					</div>
					<?php
					$has_reviewed = false;
					$text = "";
					if ($has_ordered) {
						if ($stmt = $mysqli->prepare("SELECT `rating`, `text`, `timestamp` FROM `ratings` WHERE `product_id` = ? AND `user_id` = ?")) {
							$stmt->bind_param("ii", $product_id, $_SESSION['user_id']);
							$stmt->execute();
							$result = $stmt->get_result();
							$stmt->fetch();
							$user_review = $result->fetch_assoc();
							$rating = $user_review['rating'];
							$text = htmlspecialchars($user_review['text']);
							$date = $user_review['timestamp'];
							if (mysqli_num_rows($result) > 0) {
								$has_reviewed = true;
							}
						}
					}
					?>
					<div id="write">
						<div id="post-review-box">
							<div class="col-md-12 col-xs-12">
								<p><i class="fa fa-pencil" aria-hidden="true"></i> Write your review:</p>
								<textarea rows="10" id="review_text" 
									<?php
										if (!$has_ordered) {
											echo 'disabled';
										}
									?> 
									class="form-control animated" id="new-review" name="comment" placeholder="Enter your review here... 
									<?php
										if (!$has_ordered) {
											echo 'You must be logged in and have purchased this item to write a review.';
										}
									?>
									">
									<?php echo htmlspecialchars($text); ?>
								</textarea>
								</br>
								<div id="rating-stars" class='rating-stars text-right' <?php if (!$has_ordered) {
																							echo 'style="display:none;"';
																						} ?>>
									<ul id='stars'>
										<li class='star' title='Poor' data-value='1'>
											<i class='fa fa-star fa-fw'></i>
										</li>
										<li class='star' title='Fair' data-value='2'>
											<i class='fa fa-star fa-fw'></i>
										</li>
										<li class='star' title='Good' data-value='3'>
											<i class='fa fa-star fa-fw'></i>
										</li>
										<li class='star' title='Excellent' data-value='4'>
											<i class='fa fa-star fa-fw'></i>
										</li>
										<li class='star' title='WOW!!!' data-value='5'>
											<i class='fa fa-star fa-fw'></i>
										</li>
									</ul>
								</div>
								<div class="text-right">
									<p style="display:none;" id="last_edit"></p>
									<button id="post_it" <?php if (!$has_ordered) {
																echo 'disabled';
															} ?> class="btn btn-success" type="submit">Post <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
								</div>
								<div class="message-box text-center" style="display:none; padding:10px 10px; border:1px solid #eee;background:#f9f9f9;"></div>
							</div>
						</div>
					</div>

				</div>
				<div class="col-sm-6 second">
					<h2><?php echo $title; ?></h2>
					<div id="product_prc"><?php if ($old_price > 0) {
												echo '<hr><span style="text-decoration: line-through; color:black; font-size:20px;"><span style="color:red; font-size:20px">$' . $old_price . '</span></span>';
												echo '<h4 style="font-size:24px;">$' . $price . '</h4>';
												echo '<span style="margin-top:auto; color:yellow; background-color:red; font-size:18px; flex:100%;">' . number_format(($price - $old_price) / $price * 100, 0) . '% FOR A LIMITED TIME!</span><hr>';
											} else {
												echo '<h4 style="font-size:22px;">$' . $price . '</h4>';
											} ?></div>
					<div id="price_form_group" class="form-group">
						<label for="quantity" class="col-2 col-form-label">Quantity</label>
						<div class="input-group">
							<input class="form-control" type="number" value="1" min="1" id="quantity" />
							<div class="input-group-btn">
								<button id="add_to_cart" class="btn btn-success">Add to cart</button>
							</div>
						</div>
					</div>
					<h3 style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; font-size: 18px; font-family:Times New Roman;"><?php echo file_get_contents($product_id . "/description.txt"); ?></h3>
				</div>
				<div class="third">

				</div>
			</div>

			<div id="myModal" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">

						</div>
						<div class="modal-body"></div>
						<div class="modal-footer">
							<button id="go" type="button" class="btn" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
		<?php
		} else {
			echo "Wrong product id.";
		} ?>
		<div id="also_bought">
			<?php
			if ($stmt = $mysqli->prepare("SELECT DISTINCT
								products.id, products.alias, products.title, products.old_price, products.price
							 FROM `order_products`
							 INNER JOIN
								products ON (products.id = order_products.product_id)
							 WHERE `order_id` IN (SELECT `order_id` FROM order_products WHERE `product_id` = ?)
							 AND `product_id` <> ?
							 ORDER BY 
								(SELECT SUM(quantity) FROM order_products WHERE order_products.product_id = products.id)
							 DESC LIMIT 10")) {
				$stmt->bind_param("ii", $product_id, $product_id);
				$stmt->execute();
				$result = $stmt->get_result();
				$stmt->fetch();
				if (mysqli_num_rows($result) > 0) {
					echo '	<hr>
							<h2>Customers who bought this item also bought</h2>
							<div class="grid-container">
  								<main class="grid-item main">
									<div class="items">';
					while ($myrow = $result->fetch_assoc()) {
						echo '			<div class="item">';
						echo '				<div class="product" id="' . $myrow['alias'] . '/' . $myrow['id'] . '">
		  										<div class="product-body">
													<img style="" class="img-responsive" src="' . $myrow['id'] . '/background.jpg"
													alt="...">
												<div>
			  									<h4 class="text-center">' . $myrow['title'] . '</h4>
											</div>
										  </div>';
										  
						if ($myrow['old_price'] > $myrow['price']) {
							echo '
											<div class="text-center" style="margin-top:auto;">
												<span style="color:yellow;background-color:red;font-size:18px;">' . number_format(($myrow['price'] - $myrow['old_price']) / $myrow['price'] * 100, 0) . '% OFF NOW!</span>
											</div>
		  									<div>
												<h5 class="text-center">
												<span style="text-decoration: line-through; color:black; font-size:18px;">
													<span style="color:red; font-size:20px">$' . $myrow['old_price'] . '</span>
												</span>$' . $myrow['price'] . '
												</h5>';
						} else {
							echo '
												<div>
													<h5 class="text-center">
													$' . $myrow['price'] . '
													</h5>';
						}
						echo '
		  										</div>
		  										<div class="text-center" style="margin-top:auto;">
													<button class="btn btn-success add_to_cart">Add to cart</button>
		  										</div>';
						echo '				</div>
										</div>';
					}
					echo '			</div>
		   						</main>
							</div>';
				}
			}
			?>
		</div>
		<div id="same_price">
			<?php

			if ($stmt = $mysqli->prepare("SELECT * FROM products WHERE products.sub_id = (SELECT products.sub_id FROM products WHERE products.id = ?) AND products.id <> ? AND products.price >= (0.50 * (SELECT products.price FROM products WHERE products.id = ?)) AND products.price <= (1.50 * (SELECT products.price FROM products WHERE products.id = ?))")) {
				$stmt->bind_param("iiii", $product_id, $product_id, $product_id, $product_id);
				$stmt->execute();
				$result = $stmt->get_result();
				$stmt->fetch();
				if (mysqli_num_rows($result) > 0) {
					echo '	<hr><h2>You might also consider these items on the same price range <span style="font-size:18px;">(&plusmn; 50%)</span></h2>
							<div class="grid-container">
							<main class="grid-item main">
								<div class="items">';
					while ($myrow = $result->fetch_assoc()) {
						echo '		<div class="item">';
						echo '		<div class="product" id="' . $myrow['alias'] . '/' . $myrow['id'] . '">
		 							<div class="product-body">
										<img style="" class="img-responsive" src="' . $myrow['id'] . '/background.jpg"
										alt="...">
										<div>
											<h4 class="text-center">' . $myrow['title'] . '</h4>
										</div>
									</div>
		  ';
						if ($myrow['old_price'] > $myrow['price']) {
							echo '
								<div class="text-center" style="margin-top:auto;">
									<span style="color:yellow;background-color:red;font-size:18px;">' . number_format(($myrow['price'] - $myrow['old_price']) / $myrow['price'] * 100, 0) . '% OFF NOW!</span>
								</div>
								<div>
									<h5 class="text-center">
									<span style="text-decoration: line-through; color:black; font-size:18px;">
										<span style="color:red; font-size:20px">$' . $myrow['old_price'] . '</span>
									</span>$' . $myrow['price'] . '
									</h5>';
						} else {
							echo '
									<div>
									<h5 class="text-center">
									$' . $myrow['price'] . '
									</h5>';
						}
						echo '
								</div>
								<div class="text-center" style="margin-top:auto;">
									<button class="btn btn-success add_to_cart">Add to cart</button>
								</div>';
						echo '	</div>
							</div>';
					}
					echo '	</div>
						</main>
					</div><hr>';
				}
			}
			?>
		</div>

		<div id="recommendations">
			<?php
			if (!empty($_SESSION['history'])) {
				require_once("recommendations.php");
			} ?>
		</div>

	</div>

	<div id="add_to_cart_fixed">
		<button id="add_to_cart_fixed_btn" title="Add to cart"></button>
	</div>

	<script>
		function load_reviews() {
			$.post("load_reviews", {
					"product_id": <?php echo $product_id; ?>,
					"page": page
				})
				.done(function(data) {
					if (data != "0") {
						$("#review_place").append(data);
						page += 1;
					} else {
						if (page > 0) {
							$("#load_more").html("There aren't any other reviews");
							$("#load_more").attr("disabled", true);
						} else {
							$("#load_more").hide();
						}
					}
				});
		}

		<?php
		if ($has_reviewed) {
			echo "
			var stars = $('li.star');
			for (i = 0; i < " . $rating . "; i++) {
				$(stars[i]).addClass('selected');
			}

			$('#last_edit').text('Last edit: " . date('
				d / m / y H: i ', strtotime($date)) . "').fadeIn('slow');
			ratingValue = " . $rating . ";
			";
		} ?>

		$("body").on('click', '#post_it', function(e) {
			change_msg(ratingValue);
			if (ratingValue > 0) {
				$.post("post_comment", {
						"rating": ratingValue,
						"text": $("#review_text").val(),
						"id": <?php echo $product_id; ?>
					})
					.done(function(data) {
						$(".message-box").html(msg).fadeIn("slow");
					});
			} else {
				$(".message-box").html("Please, choose a star to rate the product.").show();
			}
		});

		$("#add_to_cart").click(function() {
			$.post("add", {
					"id": <?php echo $product_id; ?>,
					"quantity": $("#quantity").val()
				})
				.done(function(data) {
					$(".cart").html('<span class="glyphicon glyphicon-shopping-cart"></span> ' + data);
				});
		});
	</script>

	<script src="./js/product.js"></script>
</body>

</html>

<html>

<head>
	<?php
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		if (!isset($_COOKIE['dparts'])) {
			$_COOKIE['dparts'] = "open";
		}
		if (!isset($_COOKIE['search-mob'])) {
			$_COOKIE['search-mob'] = "open";
		}

		error_reporting(0);
		require_once("database.php");

		if (!isset($_SESSION['cart'])) {
			$_SESSION['cart'] = array();
		}
		if (!isset($_SESSION['history'])) {
			$_SESSION['history'] = array();
		}
		if (!isset($_SESSION['logged'])) {
			$_SESSION['logged'] = 0;
		}

		if (!isset($_SESSION['categories'])) {
			$_SESSION['categories'] = array();
			$result = $mysqli->query("SELECT * FROM `categories` ORDER BY `ordering`;");
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$category = array();
					$category['name'] = $row['name'];
					$category['title'] = $row['description'];
					$category['alias'] = $row['alias'];
					array_push($_SESSION['categories'], $category);
				}
			}
		}
		if (strpos($_SERVER['REQUEST_URI'], "sign") === false) {
			$_SESSION['RETURN_URL'] = $_SERVER['REQUEST_URI'];
		}
	?>
	<base href="/ecommerce/">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">

	<link rel="shortcut icon" href="favicon.ico" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<link rel="stylesheet" type="text/css" href="./css/navbar.css">
	<link rel="stylesheet" type="text/css" href="./css/footer.css">

</head>

<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="index">BRAND</a>
			<a class="cart btn navbar-brand" href="cart.php" style="color:white;">
				<span class="glyphicon glyphicon-shopping-cart"></span>
				<?php $sum = 0;
				foreach (array_keys($_SESSION['cart']) as $key) {
					$sum += $_SESSION['cart'][$key]['quantity'];
				}
				echo $sum; ?>
			</a>
			<a class="btn navbar-brand pull-right <?php if ($_COOKIE['dparts'] == "closed") {
														echo "closed";
													} ?>" style="color:white;" id="showdparts">Departments <span class="dropup"><span class="caret"></span></span></a>
			<a id="sign-mob" class="sign btn navbar-brand pull-right" <?php if (isset($_SESSION['user_id'])) {
																			echo 'data-toggle="modal" data-target="#user_settings"';
																		} else {
																			echo 'href="sign.php"';
																		} ?> style="color:white;">
				<span class="glyphicon glyphicon-user"></span> <?php if (!isset($_SESSION['user_id'])) {
																	echo 'Sign in';
																} else {
																	echo 'Hi, ' . $_SESSION['user_name'] . ' <span class="caret"></span>';
																} ?>
			</a>
			<a id="search-mob" style="color:white;" class="btn navbar-brand"><span class="glyphicon glyphicon-search"><span class="dropup"><span class="caret"></span></span></a>
		</div>
		<form class="navbar-form navbar-right ">
			<div class="input-group" style="width:100%;">
				<div class="input-group stylish-input-group">
					<input id="searchbar" type="text" class="form-control dropdown" placeholder="Search">
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-search"></span>
					</span>
				</div>
				<div class="dropdown-menu" id="results" style="width:100%; overflow:hidden; overflow-y:auto; max-height:250px;"></div>
				<div class="dropdown pull-right">
					<a id="sign-pc" href="sign" class="btn navbar-right pull-right <?php if (isset($_SESSION['user_id'])) {
																						echo 'dropdown-toggle';
																					} ?>" type="button" <?php if (isset($_SESSION['user_id'])) {
																											echo 'data-toggle="dropdown"';
																										} ?> style="color:white;">
						<span class="glyphicon glyphicon-user"></span> <?php if (!isset($_SESSION['user_id'])) {
																			echo 'Sign in';
																		} else {
																			echo 'Hi, ' . $_SESSION['user_name'] . ' <span class="caret"></span>';
																		} ?>
					</a>
					<ul class="dropdown-menu">
						<li><a href="sign.php">Profile</a></li>
						<li><a href="my_orders.php">My orders</a></li>
						<li><a class="sign_out" href="#">Sign out</a></li>
					</ul>
				</div>
			</div>
		</form>
	</div>
	<div id="dparts" <?php if ($_COOKIE['dparts'] == "closed") {
							echo "style='display:none;'";
						} ?>>
		<ul>
			<?php
			foreach ($_SESSION['categories'] as $category) {
				echo "<li><a href='category?alias={$category['alias']}' title='{$category['title']}' class='nav-links'>{$category['name']}</a></li>";
			}
			?>
		</ul>
	</div>
</nav>

<body>

	<!-- Modal -->
	<div id="user_settings" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Options</h4>
				</div>
				<div class="modal-body">
					<ul class="list-group">
						<li class="list-group-item"><a href="sign.php">Profile</a></li>
						<li class="list-group-item"><a href="my_orders.php">My orders</a></li>
						<li class="list-group-item"><a class="sign_out" href="#">Sign out</a></li>
					</ul>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>

		</div>
	</div>

	<div id="snackbar">
		Successfully added to <a href="cart">cart <span class="glyphicon glyphicon-shopping-cart"></span></a>!
		</br>
		<a href="cart" style="text-decoration: underline;">Go to cart<span class="glyphicon glyphicon-shopping-cart"></span></a>
	</div>

	<button onclick="topFunction()" id="myBtn" title="Go to top"><span class="glyphicon glyphicon-arrow-up"></span></button>

	<!-- Latest compiled and minified JavaScript -->
	<script src="bootstrap/js/jquery.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>

	<script src="./js/navbar.js"></script>
</body>

</html>

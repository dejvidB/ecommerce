<html>

<head>
	<?php
	session_start();
	if (!isset($_SESSION["user_id"])) {
		header("Location: index");
	}
	include("navbar.php");
	?>
	<link src="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" />
</head>

<body>
	<div class="container">
		<div class="table-responsive" id="all_orders">
			<table class="table">
				<thead>
					<tr>
						<th scope="col" class='text-center'>Ord. No</th>
						<th scope="col" class='text-center'>Order date</th>
						<th scope="col" class='text-center'>Total</th>
						<th scope="col" class='text-center'>Order info</th>
						<th scope="col" class='text-center'>State</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>

	<script src="./js/my_orders.js"></script>
</body>

</html>

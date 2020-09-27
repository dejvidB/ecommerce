<html>

<head>
	<?php
	require("navbar.php");
	if (isset($_SESSION['user_id'])) {
		$stmt = $mysqli->prepare("SELECT * FROM `users` WHERE `id` = ?");
		$stmt->bind_param('i', $_SESSION['user_id']);
		$stmt->execute();
		$row = $stmt->get_result()->fetch_assoc();
		set_user_info($row['mail'], $row['name'], $row['lastname'], $row['country'], $row['region'], $row['address'], $row['city'], $row['address'], $row['zip']);
	}
	if (!isset($_SESSION['mail'])) {
		set_user_info("", "", "", "", "", "", "", "", "");
	}
	function set_user_info($mail, $name, $lastname, $country, $region, $street, $city, $address, $zip)
	{
		$_SESSION['mail'] = $mail;
		$_SESSION['name'] = $name;
		$_SESSION['lastname'] = $lastname;
		$_SESSION['country'] = $country;
		$_SESSION['region'] = $region;
		$_SESSION['street'] = $street;
		$_SESSION['city'] = $city;
		$_SESSION['address'] = $address;
		$_SESSION['zip'] = $zip;
	}
	?>

	<link rel="stylesheet" type="text/css" href="./css/checkout.css">
</head>

<body>
	<br>
	<div class="container">
		<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th scope="col">Product name</th>
						<th scope="col">Price</th>
						<th scope="col">Quantity</th>
						<th scope="col">Sub-total</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$total = 0;
					$item_count = 0;
					if (count($_SESSION['cart']) > 0) {
						foreach (array_keys($_SESSION['cart']) as $key) {
							if ($key > 0 && $_SESSION['cart'][$key]['quantity'] > 0) {
								if ($stmt = $mysqli->prepare("SELECT * FROM `products` WHERE `id` = ?")) {
									$stmt->bind_param("i", $key);
									$stmt->execute();
									$result = $stmt->get_result();
									$stmt->fetch();
									$row = $result->fetch_assoc();
									$price = $row['price'];
									$quantity = $_SESSION['cart'][$key]['quantity'];
									$sub_total = $price * $quantity;
									$total += $sub_total;
									echo '
											<tr>
											<th scope="row"><a href="product?id=' . $key . '">' . $row['title'] . '</a></th>
											<td>$' . $price . '</td>
											<td>' . $quantity . '</td>
											<td>$' . $sub_total . '</td>
											</tr>
										';
								}
							}
						}
					}
					?>
					<tr>
						<th scope="row" colspan="3"></th>
						<td>Total: $<?php echo $total; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php if (!isset($_SESSION['user_id'])) {
			echo '
				<div class="alert alert-info alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
					<h4>Most of our customers prefer to sing in before checkout, but you can always checkout without sign in.</h4>
					<h5>What you get if you sign in:</h5>
					<ul>
						<li>Fast checkout on the next purchases</li>
						<li>Emails with the best offers, if you want to.</li>
						<li>Etc</li>
					</ul>
				</div>
			';
		}
		?>

		<div class="container">
			<h3><span class="badge">1</span> Delivery information</h3>
			<div id="step1">
				<hr>
				<form>
					<div class="form-group">
						<label class="required" for="email">Email address</label>
						<input value="<?php echo $_SESSION['mail']; ?>" type="email" class="form-control" id="email" required placeholder="Enter your email address" required>
						<small class="form-text text-muted">We will never share your email with anyone else.</small>
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<label class="required" for="name">First name</label>
							<input value="<?php echo $_SESSION['name']; ?>" type="text" class="form-control" id="name" required placeholder="Enter your first name">
						</div>
						<div class="form-group col-md-6">
							<label class="required" for="surname">Last name</label>
							<input value="<?php echo $_SESSION['lastname']; ?>" type="text" class="form-control" id="lastname" required placeholder="Enter your last name">
						</div>
					</div>
					<div class="form-group">
						<label class="required" for="country">Country</label>
						<select class="form-control" id="country">
							<option id="choose">Choose your country</option>
						</select>
					</div>
					<div class="form-group">
						<label class="required" for="region">State/Province/Region</label>
						<input value="<?php echo $_SESSION['region']; ?>" type="text" class="form-control" id="region" required placeholder="Enter your region/state">
					</div>
					<div class="form-group">
						<label class="required" for="city">City</label>
						<input value="<?php echo $_SESSION['city']; ?>" type="text" class="form-control" id="city" required placeholder="Enter your city">
					</div>
					<div class="form-group">
						<label class="required" for="street">Street address</label>
						<input value="<?php echo $_SESSION['street']; ?>" type="text" class="form-control" id="street" required placeholder="Enter your street address">
					</div>
					<div class="form-group">
						<label class="required" for="zip">ZIP code</label>
						<input value="<?php echo $_SESSION['zip']; ?>" type="number" class="form-control" min="1" id="zip" required placeholder="Enter your ZIP code">
					</div>
					<a id="continue" class='btn pull-right btn-success'>Continue to payment</a>
			</div>
			<h3><span class="badge">2</span> Payment</h3>
			<div id="payment">
				<button id="edit" class="btn">Edit shipment details</button>
			</div>
			<div id="success">
				<h2 class="alert alert-success">Success</h2>
			</div>
		</div>

		<div id="Modal" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Modal Header</h4>
					</div>
					<div class="modal-body">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Edit details</button>
						<button id="go" type="button" class="btn btn-success" data-dismiss="modal">Continue to payment</button>
					</div>
				</div>

			</div>
		</div>

	</div>

	<script>
		$.post("getcountries", {})
			.done(function(data) {
				result = JSON.parse(data);
				for (var country in result) {
					$("#country").append("<option>" + result[country]['name'] + "</option>");
				}
				<?php
				if ($_SESSION['mail'] != "") {
					echo "$('#country').val('" . $_SESSION['country'] . "');";
				}
				?>
				$.getJSON("https://api.ipify.org/?format=json", function(e) {
					var ip = e.ip;
					$.post("getlocation", {
							"ip": ip
						})
						.done(function(data2) {
							result2 = JSON.parse(data2);
							var pos = 0;
							for (var country in result) {
								if (result2['country_code'] == result[country]['alpha2Code'] || result2['country_code'] == result[country]['alpha3Code']) {
									$("#country").val(result[country]['name']);
								}
							}
							$("#choose").remove();
							$("#zip").val(result2['postal_code']);
							$("#city").val(result2['city']);
							$("#region").val(result2['subdivision']);
						});
				});
			});
	</script>

	<script src="./js/checkout.js"></script>
</body>

</html>

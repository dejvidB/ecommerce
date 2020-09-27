<?php
	require_once("database.php");

	if ($stmt = $mysqli->prepare("SELECT * FROM `products`")){
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->fetch();
		
		echo '<div id="products" class="row" style="display: flex; flex-wrap: wrap;">';
		while($myrow = $result->fetch_assoc()) {
			echo '
					<div class="col-sm-6 col-md-3" style="padding-top:20px;">
						<div class="thumbnail product" id="' . $myrow['id'] . '" style="cursor:pointer; height:100%;">
						<img class="img-responsive img-fluid" src="'. $myrow['id'] . '/background.jpg" alt="...">
						<div class="caption">
							<div class="row"><h4 style="overflow-wrap: break-word; word-wrap: break-word;" class="text-center">' . $myrow['title'] . '</h4></div>';
							if(is_numeric($myrow['old_price'])){
								echo '<div class="row text-center"><span style="color:yellow;background-color:red;font-size:18px;">'. number_format(($myrow['price']-$myrow['old_price'])/$myrow['price']*100, 0) .'% OFF NOW!</span></div>';
								echo '<div class="row pull-right"><h5 class="pull-right col-xs-6" style="font-size:18px;"><span style="text-decoration: line-through; color:red; font-size:18px;">$'. $myrow['old_price'] .'</span> $'. $myrow['price'] . '</h5></div>';
							}else{
								echo '<div class="row"><h5 class="pull-right col-xs-6" style="font-size:18px;">$'. $myrow['price'] . '</h5></div>';
							}
							echo '
							<div class="row" style="position:absolute; bottom:10px; right:10%; margin:0;">';
							echo '<div class="row"><button style="margin-right:5px;" class="btn btn-success pull-right add_to_cart">Add to cart</button></div>
							</div>
						</div>
						</div>
					</div>';
			}
			echo "</div>";
	}
?>

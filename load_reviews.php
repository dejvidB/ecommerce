<?php
	require_once("database.php");
	$id = $_POST['product_id'];
	$reviews_on_page = 5;
	$page = $_POST['page'] * $reviews_on_page;
	if ($stmt = $mysqli->prepare("SELECT `ratings`.*, users.name FROM `ratings` INNER JOIN users ON `ratings`.`user_id` = `users`.`id` AND `ratings`.`product_id` = ? ORDER BY `ratings`.`timestamp` DESC LIMIT ?, ?")){
		$stmt->bind_param("iii", $id, $page, $reviews_on_page);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->fetch();
		while($review = $result->fetch_assoc()) {
			echo '
				<div class="card" style="margin-bottom:2rem; border:1px solid black; border-radius:5px; padding:10px;">
					<div class="card-body">
						<div class="row">
							<div class="col-md-2 image">
								<img src="https://image.ibb.co/jw55Ex/def_face.jpg" class="img img-rounded img-fluid img-responsive"/>
								<p class="text-secondary text-center">'. date('d/m/Y H:i', strtotime($review['timestamp'])) .'</p>
							</div>
							<div class="col-sm-12 col-md-10">
								<p>
									<div>
										<a class="float-left"><strong>'. $review['name'] .'</strong></a>
										<div class="pull-right">';
									for($i = 1; $i <= $review['rating']; $i++){
										echo '<span class="fa fa-star checked font"></span>';
									}	
									for($i = 1; $i <= 5 - $review['rating']; $i++){
										echo '<span class="glyphicon glyphicon-star-empty star-color font"></span>';
									}

						echo '			</div>
									</div>
								</p>
								<div class="clearfix"></div>';
							$display = 50;
							if(strlen($review['text']) > $display){
								echo'
									<p style="word-break: break-word;">'. htmlspecialchars(mb_substr($review['text'], 0, $display)) .'<span class="dots">...</span><span class="more">'. htmlspecialchars(mb_substr($review['text'], $display, strlen($review['text']) - 1)) .'</span> </br><a href="#" class="read_more">Read more</a></p>
								';
							}else{
								echo "<p>". htmlspecialchars($review['text']) ."</p>";
							}
						echo '
							</div>
						</div>
					</div>
				</div>
			';
		}
		if(mysqli_num_rows($result) == 0){
			echo "0";
		}
	}
?>

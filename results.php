<?php
	$q = $_POST['q'];
	require_once("database.php");
	echo '	<div class="row">
				<div style="width:100%; height:100%;">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-2">
									<button type="button" class="close pull-left">&times;</button>
								</div>
								<div class="col-md-10">
									<h4>Search results</h4>
								</div>
							</div>
							<div class="table-container">
								<table class="table table-filter">
									<tbody>';
	if ($stmt = $mysqli->prepare("SELECT `products`.`id`, `products`.`title`, `products`.`price`, `sub_categories`.`name` AS 'sub_name' FROM `products` INNER JOIN `sub_categories` ON `products`.`sub_id` = `sub_categories`.`sub_id` AND `products`.`title` LIKE ? LIMIT 10")) {
		$query = '%' . $q . '%';
		$stmt->bind_param("s", $query);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->fetch();
		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				echo '
										<tr>
											<td>
												<div class="person">
													<div class="media">
														<div class="pull-left">
															<img src="" class="media-photo">
														</div>
														<div class="media-body">
														<span class="media-meta pull-right">$' . $row['price'] . '</span>
														<h4 class="title"> <a href="product?id=' . $row['id'] . '">';
				$title = strtolower($row['title']);
				$start_pos = strpos($title, strtolower($q));
				$len = strlen(strtolower($q));
				for ($i = 0; $i < strlen($title); $i++) {
					if ($i == $start_pos) {
						echo '<span style="background-color:yellow;">';
					}
					if ($i == $start_pos + $len) {
						echo '</span>';
					}
					echo $title[$i];
				}
				echo '
														</h4>
														<p class="summary">in ' . $row['sub_name'] . '</p>
														</div>
													</div>
												</div>
											</td>
										</tr>';
			}
		}
	}
	echo '
									</tbody>
								</table>	
							</div>
						</div>
					</div>
				<div class="content-footer">';
	if (mysqli_num_rows($result) == 0) {
		echo "		<div class='alert alert-danger'> No results could be found. </div>";
	}
	echo '		
				</div>
			</div>';
	$stmt->close();
?>

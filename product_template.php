<?php
error_reporting(0);
echo "	<div class='item' title='$title'>";
echo '		<div class="product" id="'. $alias .'/' . $id .'">
				<div class="product-body">
					<img style="" class="img-responsive" src="'. $id .'/background.jpg" alt="...">
				<div>
				<h4 class="text-center">'. $title .'</h4>
			</div>
		</div>';
if($old_price > $price){
	echo'
  		<div class="text-center" style="margin-top:auto;">
			<span style="color:yellow;background-color:red;font-size:18px;">'. number_format(($price-$old_price)/$price*100, 0) .'% OFF NOW!</span>
  		</div>
  		<div>
			<h5 class="text-center">
	  		<span style="text-decoration: line-through; color:black; font-size:18px;">
			<span style="color:red; font-size:20px">$'. $old_price .'</span>
	  		</span>$'. $price .'
			</h5>';
}else{
	echo '
  			<div class="text-center" style="margin-top:auto;">
				<span style="color:white; font-size:18px;">-</span>
 			</div>
  			<div>
				<h5 class="text-center">
	  			$'. $price .'
				</h5>
	';
}
if(isset($count_reviews)){
	if($count_reviews > 0 && number_format(($reviews_sum/$count_reviews), 1) >= 3){
		$average = $reviews_sum/$count_reviews;
		echo '	<div class="text-center" style="font-size:15px;">';
		for ($i = 1; $i <= floor($average); $i++){
			echo '	<span class="fa fa-star checked font"></span>';
		}
		if($average - floor($average) > 0){
			echo '	<i class="fa fa-star-half-o font" aria-hidden="true"></i>';
		}
		for($i = 1; $i <= 5 - ceil($average); $i++){
			echo '	<span class="glyphicon glyphicon-star-empty star-color font"></span>';
		}
		echo '  	<span style="color:black;">(<span style="color:blue;">'. $count_reviews .'</span>)</span>';
		echo '	</div>';
	}else{
	  echo '
	  <div class="text-center" style="margin-top:auto; font-size:15px;">
		<span style="color:white;">-</span>
	  </div>';
	}
}
echo '
  			</div>
  			<div class="text-center" style="margin-top:auto;">
				<button class="btn btn-success add_to_cart" title="">Add to cart</button>
  			</div>';
  echo '</div>
  	</div>';
?>

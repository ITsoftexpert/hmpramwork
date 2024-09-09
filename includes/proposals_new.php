<?php
		
	$get_delivery = $db->select("instant_deliveries",['proposal_id'=>$proposal_id]);
	$row_delivery = $get_delivery->fetch();
	// $enable_delivery = $row_delivery->enable;

	if($videoPlugin == 1){
		$proposal_videosettings =  $db->select("proposal_videosettings",array('proposal_id'=>$proposal_id))->fetch();
		$enableVideo = $proposal_videosettings->enable;
	}else{
		$enableVideo = 0;
	}
?>
<!-- New Design -->
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
	<div class="card-box card">
	<a href="<?= $site_url; ?>/proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" class="subcategory">
	  <picture class="card-img-top">
	    <img src="<?= $proposal_img1; ?>">
	  </picture>
	  <div class="card-body">
	    <div class="d-flex">
	      <div class="rounded-circle overflow-hidden d-flex justify-content-center align-items-center user-pic">
	        <img class="w-100 h-100" src="<?= $seller_image; ?>" alt="">
	      </div>
	      <div class="px-3 d-flex justify-content-between w-100 align-items-center">
	        <div class="">
	          <h5 class="card-title font-weight-bold mb-0"><?= $seller_user_name; ?></h5>
	          <small class="text-secondary"><?= $seller_level; ?></small>
	        </div>
	        <div class="text-secondary">
	          <!-- <i class="fa fa-heart"></i> -->
	        <?php if(isset($_SESSION['seller_user_name'])){ ?>
				<?php if($proposal_seller_id != $login_seller_id){ ?>
				<i data-id="<?= $proposal_id; ?>" href="#" class="fa fa-heart <?= $show_favorite_class; ?>" data-toggle="tooltip" data-placement="top" title="Favorite"></i>
				<?php } ?>
				<?php }else{ ?>
				<a href="#" data-toggle="modal" data-target="#login-modal">
					<i class="fa fa-heart proposal-favorite" data-toggle="tooltip" data-placement="top" title="Favorite"></i>
				</a>
			<?php } ?>
	        </div>
	      </div>
	    </div>
	    <p class="my-4"><?= $proposal_title; ?></p>
	    <div class="star-fill d-flex">
	      <div class="font-weight-bold text-warning">
	        <i class="fa fa-star"></i>
	        <span><?php if($proposal_rating == "0"){ echo "0.0"; }else{ printf("%.1f", $average_rating); } ?></span>
	        <span class="font-weight-normal text-secondary">(<?= $count_reviews; ?>)</span>
	      </div>
	    </div>

	  </div>

	  <div class=" d-flex justify-content-between align-items-center py-3 px-3 bt-xs-1">

	    <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1003 1057" height="30px">
	      <style>
	        .a {
	          fill: #f5a42a
	        }

	        .b {
	          fill: #fab62f
	        }

	        .c {
	          fill: #ffd054
	        }

	        .d {
	          fill: #ed6872
	        }

	        .e {
	          fill: #e05560
	        }

	        .f {
	          fill: #e6cbbf
	        }

	        .g {
	          fill: #f2dfd5
	        }
	      </style>
	      <path class="a" d="m638.7 275.8h-274.4l-62.7-275.4h399.7z" />
	      <path class="b" d="m500.4 275.8h-136.1l-62.7-275.4h198.8z" />
	      <path class="b" d="m1002.6 965.1c0 50.6-41 91.5-91.6 91.5h-0.5-223.4-371.4-223.3-0.5c-50.6 0-91.6-40.9-91.6-91.5 0-17 11.7-39.8 28.9-62.6-7.2-32.8-11-66.9-11-101.9 0-266.9 216.3-548.9 483.3-548.9 266.8 0 483.2 282 483.2 548.9 0 35-3.9 69-11 101.9 17.2 22.7 28.9 45.6 28.9 62.6z" />
	      <path class="c" d="m500.4 1056.6h-408.5c-50.6 0-91.6-40.9-91.6-91.5 0-17 11.7-39.8 28.9-62.6-7.2-32.8-11-66.9-11-101.9 0-266.6 215.8-548.1 482.2-548.9z" />
	      <path fill-rule="evenodd" class="d" d="m668.7 304h-334.5c-18.3 0-33.1-14.8-33.1-33 0-18.3 14.8-33.1 33.1-33.1h334.5c18.3 0 33.1 14.8 33.1 33.1 0 18.2-14.8 33-33.1 33zm-13.8-66.3h-307c-18.2 0-33-14.7-33-33 0-18.2 14.8-33.1 33-33.1h307c18.3 0 33.1 14.9 33.1 33.1 0 18.3-14.8 33-33.1 33z" />
	      <path fill-rule="evenodd" class="e" d="m701.7 270.8c0 0 0.1 0.1 0.1 0.2 0 18.2-14.8 33-33.1 33h-334.5c-18.3 0-33.1-14.8-33.1-33q0-0.1 0-0.2zm-13.8-66.6c0 0.2 0.1 0.3 0.1 0.5 0 18.3-14.8 33-33 33h-307c-18.3 0-33.1-14.7-33.1-33 0-0.2 0.1-0.3 0.1-0.5z" />
	      <path class="f" d="m712.1 676.8c0 116.3-94.3 210.7-210.6 210.7-116.4 0-210.7-94.4-210.7-210.7 0-116.3 94.3-210.6 210.7-210.6 116.3 0 210.6 94.3 210.6 210.6z" />
	      <path class="g" d="m500.4 887.4c-115.9-0.6-209.6-94.6-209.6-210.6 0-116 93.7-210 209.6-210.6z" />
	      <path class="e" d="m483.3 797.6c-46.5-5-61.7-31.6-61.7-66.4h43.1c0.9 18.3 9 28.2 36.2 28.2 25.8 0 37.6-10.8 37.6-37.8 0-28.2-33.5-28.2-58.9-31.6-27-3.4-55.5-16.8-55.5-66.1 0-39 20.8-61.7 59.2-66.7v-33.4h38.1v33.8c31 4.9 51.2 22.6 54.6 62h-43c-1.3-20.1-11.9-26-32.7-26-23.5 0-33.1 7.4-33.1 30 0 30.1 23.9 27.6 60.1 33.2 27.7 4.4 54 15.5 54 63.3 0 47.7-17.9 71.6-59.9 77.2v32.5h-38.1z" />
	    </svg>
	    <div>
	      <span class="text-secondary"><?= $lang['proposals']['starting_at']; ?> </span>
	      <strong class="text-largest pl-2"><?= showPrice($proposal_price); ?></strong>
	    </div>
	  </div>
	</a>
	</div>
	</div>
<!-- New Design End -->
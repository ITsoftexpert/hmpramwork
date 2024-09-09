<div class="container proposal-footer" id="related"><!-- Container starts -->

	<!-- portfolio -->

	<h4 class="mt-3">Portfolio</h4>

	<style>
		.ramrow {
			display: flex;
			flex-wrap: wrap;
			/* justify-content: space-evenly; */
		}

		.ramcard {
			width: 23%;
			padding: 10px;
			border: 1px solid lightgray;
			border-radius: 5px;
			margin: 10px;
			position: relative;
		}

		.ramspan {
			margin: auto;
			color: white;
		}

		.rambazz-placeholder {
			height: 30px;
			width: 50px;
			margin-top: -30px;
			/* to offset the margin-top from rambazz */
		}

		.rambazz {
			height: 30px;
			display: flex;
			align-items: center;
			background-color: rgba(0, 0, 0, 0.5);
			/* transparent black */
			width: 50px;
			position: absolute;
			left: 76.5%;
			top: 199px;
			/* adjust as necessary */
			border-radius: 5px;
		}

		.ramimg {
			width: 100%;
			height: 220px;
			border-radius: 5px;
			border: 1px solid lightgray;
		}

		@media (max-width: 600px) {
			.ramcard {
				width: 100%;
				margin: 10px 0;
			}
		}

		.mbbm {
			margin: 0;
			padding: 0;
		}
	</style>
	<!-- portfolio -->
	<?php

	// Fetch portfolios
	$portfolios = $db->select("portfolios", array("seller_id" => $proposal_seller_id));
	while ($portfolio = $portfolios->fetch()) {
		$seller_id =  $portfolio->seller_id;
		$portfolio_id = $portfolio->id;  
		$projectTitle = $portfolio->project_title;
		$description = $portfolio->description;
		$referenced_url = $portfolio->referenced_url;


		function limitWords($text, $limit)
		{
			$words = explode(' ', $text);
			if (count($words) > $limit) {
				return implode(' ', array_slice($words, 0, $limit)) . '...';
			}
			return $text;
		}

		// Assuming you have already fetched $projectTitle and $description from the database
		$limitedTitle = limitWords($projectTitle, 1); // Limit to 10 words
		$limitedDescription = limitWords($description, 3); // Adjust the limit as needed
	?>


		<div class="row ramrow">
			<div class="ramcard">
				<?php
				// Fetch and display images
				$images = $db->select("portfolio_images", array("portfolio_id" => $portfolio_id));
				if ($images) {
					while ($image = $images->fetch()) {
						$image_path = $image->image_path;
					}
				}
				echo "<img src='../../portfolio/" . $image_path . "' alt='Portfolio Image' class='ramimg'><br>";
				?>
				<div class="rambazz-placeholder"></div>
				<div class="rambazz">
					<p class="ramspan"><span><i class="fa fa-image"></i></span>
						1</p>
				</div>
				<!--<h2 class="mt-4" style="overflow-wrap: break-word;"><?= $limitedTitle; ?> </h2>-->
				<!--<p class="mbbm"><?= $limitedDescription; ?></p>-->

				<a href="<?= $site_url; ?>/port_folio?id=<?= $portfolio_id; ?>" target="_blank"> <!-- individual page link -->
					<h2 class="mt-4" style="overflow-wrap: break-word;"><?= $limitedTitle; ?> </h2>
				</a>
				<p class="mbbm"><?= $limitedDescription; ?></p>


				<p class="mt-3 pb-"><a href="<?= $referenced_url; ?>">Link</a></p>
			</div>
		</div>		
	<?php		
	}

	?>
<button>read more</button>

<hr>












	<!-- portfolio -->
	<div class="row">
		<div class="col-md-12">
			<?php if ($deviceType == "phone") { ?>
				<h4 class="mt-3 mb-2 <?= ($lang_dir == "right" ? 'text-right' : '') ?>">
					<?php
					echo str_replace('{seller_user_name}', "<a href='../../$proposal_seller_user_name' class='text-success'>$proposal_seller_user_name</a>", $lang['proposal']['other_proposals']);
					?>
				</h4>
			<?php } else { ?>
				<h2 class="mt-3 <?= ($lang_dir == "right" ? 'text-right' : '') ?>">
					<?php
					echo str_replace('{seller_user_name}', "<a href='../../$proposal_seller_user_name' class='text-success'>$proposal_seller_user_name</a>", $lang['proposal']['other_proposals']);
					?>
				</h2>
				<hr>
			<?php } ?>
			<div class="row flex-wrap proposals mb-2">
				<?php
				$get_proposals = $db->query("select * from proposals where not proposal_id='$proposal_id' AND proposal_seller_id='$proposal_seller_id' AND proposal_status='active'");
				while ($row_proposals = $get_proposals->fetch()) {
					$proposal_id = $row_proposals->proposal_id;
					$proposal_title = $row_proposals->proposal_title;
					$proposal_price = $row_proposals->proposal_price;
					if ($proposal_price == 0) {
						$get_p_1 = $db->select("proposal_packages", array("proposal_id" => $proposal_id, "package_name" => "Basic"));
						$proposal_price = $get_p_1->fetch()->price;
					}
					$proposal_img1 = getImageUrl2("proposals", "proposal_img1", $row_proposals->proposal_img1);
					$proposal_video = $row_proposals->proposal_video;
					$proposal_seller_id = $row_proposals->proposal_seller_id;
					$proposal_rating = $row_proposals->proposal_rating;
					$proposal_url = $row_proposals->proposal_url;
					$proposal_featured = $row_proposals->proposal_featured;
					$proposal_enable_referrals = $row_proposals->proposal_enable_referrals;
					$proposal_referral_money = $row_proposals->proposal_referral_money;
					if (empty($proposal_video)) {
						$video_class = "";
					} else {
						$video_class = "video-img";
					}
					$get_seller = $db->select("sellers", array("seller_id" => $proposal_seller_id));
					$row_seller = $get_seller->fetch();
					$seller_user_name = $row_seller->seller_user_name;
					$seller_image = getImageUrl2("sellers", "seller_image", $row_seller->seller_image);
					$seller_level = $row_seller->seller_level;
					$seller_status = $row_seller->seller_status;
					if (empty($seller_image)) {
						$seller_image = "empty-image.png";
					}
					// Select Proposal Seller Level
					@$seller_level = $db->select("seller_levels_meta", array("level_id" => $seller_level, "language_id" => $siteLanguage))->fetch()->title;
					$proposal_reviews = array();
					$select_buyer_reviews = $db->select("buyer_reviews", array("proposal_id" => $proposal_id));
					$count_reviews = $select_buyer_reviews->rowCount();
					while ($row_buyer_reviews = $select_buyer_reviews->fetch()) {
						$proposal_buyer_rating = $row_buyer_reviews->buyer_rating;
						array_push($proposal_reviews, $proposal_buyer_rating);
					}
					$total = array_sum($proposal_reviews);
					@$average_rating = $total ? $total / count($proposal_reviews) : 0;

				?>
					<?php
					if ($deviceType == "phone") {
						echo "<div class='col-md-12 mb-3'>";
						include("../screens/mobile_sidebar_proposals.php");
						echo "</div>";
					} else {
						echo "<div class='col-xl-2dot4 col-lg-3 col-md-4 col-sm-6 mb-md-4 mb-3'>";
						include("../screens/desktop_sidebar_proposals.php");
						echo "</div>";
					}
					?>
				<?php } ?>
			</div>
		</div>
	</div>

	<?php
	$get_proposals = $db->query("select * from proposals where not proposal_id='$proposal_id' AND proposal_seller_id='$proposal_seller_id' AND proposal_status='active'");
	$count_proposals = $get_proposals->rowCount();
	if ($count_proposals == 0) {
		$rtl = ($lang_dir == "right" ? 'text-right' : '');
		echo "<center><h5 class='pb-4 pt-4 text-muted $rtl'>{$lang['proposal']['no_other_proposals']}</h5></center>";
	}
	?>
	<?php if (isset($_SESSION['seller_user_name'])) { ?>
		<div class="row">
			<div class="col-md-12">
				<?php if ($deviceType == "phone") { ?>
					<h4 class="mt-3 mb-2 <?= ($lang_dir == "right" ? 'text-right' : '') ?>"><?= $lang['proposal']['recently_viewed']; ?></h4>
				<?php } else { ?>
					<h2 class="mt-3 <?= ($lang_dir == "right" ? 'text-right' : '') ?>"><?= $lang['proposal']['recently_viewed']; ?></h2>
					<hr>
				<?php } ?>
				<div class="row flex-wrap proposals mb-2">
					<?php
					$select_recent = $db->query("select * from recent_proposals where seller_id='$login_seller_id' order by 1 DESC LIMIT 0,4");
					while ($row_recent = $select_recent->fetch()) {
						$proposalId = $row_recent->proposal_id;
						// $get_proposals = $db->select("proposals", array("proposal_id" => $proposalId, "proposal_status" => "active"));
						$get_proposals = $db->select("proposals", array("proposal_id" => $proposalId));
						$count_proposals = $get_proposals->rowCount();

						$row_proposals = $get_proposals->fetch();

						$proposal_id = $row_proposals->proposal_id;
						$proposal_title = $row_proposals->proposal_title;
						$proposal_price = $row_proposals->proposal_price;
						if ($proposal_price == 0) {
							$get_p_1 = $db->select("proposal_packages", array("proposal_id" => $proposal_id, "package_name" => "Basic"));
							$proposal_price = $get_p_1->fetch()->price;
						}
						$proposal_img1 = getImageUrl2("proposals", "proposal_img1", $row_proposals->proposal_img1);
						$proposal_video = $row_proposals->proposal_video;
						$proposal_seller_id = $row_proposals->proposal_seller_id;
						$proposal_rating = $row_proposals->proposal_rating;
						$proposal_url = $row_proposals->proposal_url;
						$proposal_featured = $row_proposals->proposal_featured;
						$proposal_enable_referrals = $row_proposals->proposal_enable_referrals;
						$proposal_referral_money = $row_proposals->proposal_referral_money;
						if (empty($proposal_video)) {
							$video_class = "";
						} else {
							$video_class = "video-img";
						}
						$get_seller = $db->select("sellers", array("seller_id" => $proposal_seller_id));
						$row_seller = $get_seller->fetch();
						$seller_user_name = $row_seller->seller_user_name;
						$seller_image = getImageUrl2("sellers", "seller_image", $row_seller->seller_image);
						$seller_level = $row_seller->seller_level;
						$seller_status = $row_seller->seller_status;
						if (empty($seller_image)) {
							$seller_image = "empty-image.png";
						}
						// Select Proposal Seller Level
						@$seller_level = $db->select("seller_levels_meta", array("level_id" => $seller_level, "language_id" => $siteLanguage))->fetch()->title;
						$proposal_reviews = array();
						$select_buyer_reviews = $db->select("buyer_reviews", array("proposal_id" => $proposal_id));
						$count_reviews = $select_buyer_reviews->rowCount();
						while ($row_buyer_reviews = $select_buyer_reviews->fetch()) {
							$proposal_buyer_rating = $row_buyer_reviews->buyer_rating;
							array_push($proposal_reviews, $proposal_buyer_rating);
						}
						$total = array_sum($proposal_reviews);
						@$average_rating = $total ? $total / count($proposal_reviews) : 0;
						if ($count_proposals == 1) {

					?>
							<?php
							if ($deviceType == "phone") {
								echo "<div class='col-md-12 mb-3'>";
								include("../screens/mobile_sidebar_proposals.php");
								echo "</div>";
							} else {
								echo "<div class='col-xl-2dot4 col-lg-3 col-md-4 col-sm-6 mb-md-4 mb-3'>";
								include("../screens/desktop_sidebar_proposals.php");
								echo "</div>";
							}
							?>
					<?php
						}
					}
					?>
				</div>
			</div>
		</div>

		<?php
		$select_recent = $db->query("select * from recent_proposals where seller_id='$login_seller_id' order by 1 DESC LIMIT 0,4");
		$count_recent = $select_recent->rowCount();
		if ($count_recent == 0) {
			echo "<center><h5 class='pb-4 pt-4 text-muted'><i class='fa fa-meh-o '></i> This seller has no other proposals. </h5></center>";
		}
		?>
	<?php } ?>
	<?php if ($deviceType == "phone") { ?>
		<div class="row mb-3"><!--- row Starts --->
			<div class="col-md-12">
				<div class="proposal-tags-container mt-2 <?= ($lang_dir == "right" ? 'text-right' : '') ?>"><!--- proposal-tags-container Starts --->
					<?php
					$tags = explode(",", $proposal_tags);
					foreach ($tags as $tag) {
						// $tag = strtolower($tag);
					?>
						<div class="proposal-tag" style="margin-bottom: 30px;<?= ($lang_dir == "right" ? 'float: right;' : '') ?>">
							<a class="<?= ($lang_dir == "right" ? 'text-right' : '') ?>" href="../../tags/<?= str_replace(" ", "-", $tag); ?>"><span><?= $tag; ?></span></a>
						</div>
					<?php } ?>
				</div><!--- proposal-tags-container Ends --->
			</div>

			<div class="col-md-12">
				<div class="mb-5 text-center copyright-box"><!--- copyright-box Starts --->
					<p class="mb-2 <?= ($lang_dir == "right" ? 'text-right' : '') ?>">
						Copyright © <a href="#" class="text-primary strike"><?= ucfirst($proposal_seller_user_name); ?></a> All Rights Reversed.
					</p>
					<?php
					if (isset($_SESSION['seller_user_name'])) {
						if ($login_seller_id != $proposal_seller_id) {
							$count_reports = $db->count("reports", array("reporter_id" => $login_seller_id, "content_id" => $proposal_id));
							if ($count_reports == 0) {
					?>
								<p class="<?= ($lang_dir == "right" ? 'text-right' : '') ?>"><a class="text-danger" href="#" data-toggle="modal" data-target="#report-modal"><i class="fa fa-flag"></i> Report This Proposal</a></p>
					<?php
							}
						}
					}
					?>
				</div><!--- copyright-box Ends --->
			</div>
		</div><!--- row Ends --->
	<?php } ?>

</div><!-- Container ends -->
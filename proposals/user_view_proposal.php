<?php
// print("<pre>" . print_r($row_login_seller, true) . "</pre>");
// exit;
// check if purchased is expired or not
$num_gigs = $row_login_seller->no_of_gigs;
if ($row_plan_detail) {
	$getTotalProposals = $db->query("SELECT count(*) as total FROM `proposals` where proposal_seller_id = $row_plan_detail->seller_id AND proposal_status = 'active' AND created_at >= '$row_plan_detail->memb_start_date' AND created_at <= '$row_plan_detail->memb_end_date'");
	$objTotalProposals = $getTotalProposals->fetch();
	$totalProposal = $objTotalProposals->total;
	// print("<pre>" . print_r(($objTotalProposals)) . "</pre>");
	// exit;
} else {
	// update seller information
	$getTotalProposals = $db->query("SELECT count(*) as total FROM `proposals` where proposal_seller_id = $row_login_seller->seller_id AND proposal_status = 'active'");
	$objTotalProposals = $getTotalProposals->fetch();
	$totalProposal = $objTotalProposals->total;
}

// print("<pre>" . print_r([$totalProposal, $num_gigs], true) . "</pre>");
// exit;
if ($totalProposal >= $num_gigs) {
	$flag = 1;
} else {
	$flag = 0;
}

// $select_sellers = $db->select("sellers", array("seller_user_name" => $_SESSION['seller_user_name']));
// $row_sellers = $select_sellers->fetch();

// $checkuser = $db->select("memb_plan_detail where seller_id = $row_sellers->seller_id and memb_status = 'active'  order by id desc LIMIT 1");
// $row_purchsed = $checkuser->fetch();
// if ($row_purchsed) {
// 	$exp_date = $row_purchsed->memb_end_date;
// 	$row_purchsed_detail = $db->select("membership_table where id = " . $row_purchsed->memb_tbl_id . "  LIMIT 1");
// 	$row_purchsed_plan = $row_purchsed_detail->fetch();
// } else {
// 	$exp_date = 'new update';
// 	$row_purchsed_detail = $db->select("membership_table where id = 1  LIMIT 1");
// 	$row_purchsed_plan = $row_purchsed_detail->fetch();
// }
$limit = isset($homePerPage) ? $homePerPage : 5;
?>
<style>
	@media (max-width:768px) {
		.badge-float-right {
			float: right;
			margin-top: -3px;
			padding-top: 5px;
			margin-right: -9px !important;
		}

		.text-align-center {
			text-align: center;
		}

		.margin-auto {
			margin: auto;
			/* box-shadow: 2px 2px 5px black; */
			/* border: 2px solid red; */
		}

		.display_flex {
			width: 100%;
			display: flex;
			/* border: 2px solid red; */
		}

		.display_flex-1 {
			width: 100%;
			display: flex;
			margin-top: 20px !important;
			/* border: 2px solid red; */
		}

		.font-size-3 {
			font-size: 13px !important;
			padding: 10px !important;
			text-align: center;
		}

		.heading_3 {
			font-size: 20px;
			width: 100%;
		}
	}

	.box-shadow-cs5 {
		font-size: 20px;
		width: 100%;
	}

	.font-size-3 {
		border: 1px solid lightgray !important;
		text-align: center;
	}

	.float_right {
		float: right;
	}

	.badge-float-right {
		float: right;
		margin-top: -3px;
		padding-top: 5px;
		margin-right: -9px !important;
	}


	.pt-pr {
		padding: 9px 15px 9px 9px;
	}


	.box-shadow-new-propo {
		background-color: #EBEBEB !important;
		box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
		border: none !important;
		color: #000;
	}

	.notify_you_model {
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		background-color: grey;
		opacity: 0.5;
		position: fixed;
	}

	.active-proposals-nitin {
		background-color: #00cedc !important;
		border: none !important;
		color: #fff !important;
		font-size: 17px !important;
		padding: 2px 10px 1px 5px;

	}

	@media only screen and (max-width: 768px) {
		.active-proposel-seller-sec {
			display: none;
		}
	}

	.custom-dropdown {
		/* position: relative; */
		display: inline-block;
	}

	.custom-dropdown-content {
		display: none;
		position: absolute;
		right: 17px;
		background-color: white;
		min-width: 160px;
		box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
		z-index: 999;
	}

	.custom-dropdown-content a {
		color: black;
		padding: 12px 16px;
		text-decoration: none;
		display: block;
	}

	.custom-dropdown-content a:hover {
		background-color: #f1f1f1;
	}

	.custom-dropdown-button {
		background-color: #00cedc;
		border: none;
		color: #fff;
		border-radius: 5px;
	}

	/* ######################################################3 */

	/* Base styles for second dropdown */
	.secondsellerdropdown-dropdown {
		position: relative;
		display: block;
		width: 100%;
		text-align: center;
		margin-top: 20px;
	}

	/* Button */
	.secondsellerdropdown-btn {
		color: #000 !important;
		background-color: #ebebeb !important;
		box-shadow: rgba(0, 0, 0, 0.12) 0px 1px 3px, rgba(0, 0, 0, 0.24) 0px 1px 2px;
		width: fit-content;
		border: none;
		padding: 11px 15px;
		font-size: 17px;
		font-weight: 600;
		gap: 8px;
		align-items: center;
	}

	/* Dropdown icon */
	.secondsellerdropdown-icon {
		margin-left: 10px;
		font-size: 14px;
		vertical-align: middle;
	}

	/* Dropdown content */
	.secondsellerdropdown-content {
		display: none;
		position: absolute;
		background-color: #fff;
		border: 1px solid #ced4da;
		width: 100%;
		z-index: 1;
		padding: 10px;
		left: 0;
		top: 100%;
	}

	/* List styling */
	.secondsellerdropdown-list {
		list-style: none;
		padding: 0;
		margin: 0;
	}

	/* Items */
	.secondsellerdropdown-item-active,
	.secondsellerdropdown-item-paused,
	.secondsellerdropdown-item-pending,
	.secondsellerdropdown-item-modification,
	.secondsellerdropdown-item-draft,
	.secondsellerdropdown-item-declined {
		margin-bottom: 8px;
	}

	/* Links */
	.secondsellerdropdown-link-active,
	.secondsellerdropdown-link-paused,
	.secondsellerdropdown-link-pending,
	.secondsellerdropdown-link-modification,
	.secondsellerdropdown-link-draft,
	.secondsellerdropdown-link-declined {
		display: flex;
		justify-content: space-between;
		padding: 8px;
		text-decoration: none;
		color: #212529;
	}

	/* Badges */
	.secondsellerdropdown-badge-active,
	.secondsellerdropdown-badge-paused,
	.secondsellerdropdown-badge-pending,
	.secondsellerdropdown-badge-modification,
	.secondsellerdropdown-badge-draft,
	.secondsellerdropdown-badge-declined {
		padding: 4px;
		border-radius: 5px;
		height: 25px;
		width: 25px;
		text-align: center;
		font-size: 14px;
		color: #fff;
		background-color: #00cedc;
	}

	.secondsellerdropdown-link-active:hover {
		background-color: #ebebeb;
	}

	.secondsellerdropdown-link-paused:hover {
		background-color: #ebebeb;
	}

	.secondsellerdropdown-link-pending:hover {
		background-color: #ebebeb;
	}

	.secondsellerdropdown-link-modification:hover {
		background-color: #ebebeb;
	}

	.secondsellerdropdown-link-draft:hover {
		background-color: #ebebeb;
	}

	.secondsellerdropdown-link-declined:hover {
		background-color: #ebebeb;
	}


	.secondsellerdropdown-btn-ok {
		background-color: #4CAF50;
		color: white;
		padding: 10px 20px;
		border: none;
		cursor: pointer;
		width: 100%;
		margin-top: 10px;
	}

	.secondsellerdropdown-btn-ok:hover {
		background-color: #45a049;
	}

	@media (max-width: 768px) {
		.secondsellerdropdown-dropdown {
			display: block;
		}

		.secondsellerdropdown-content {
			display: none;
		}

		.secondsellerdropdown-content.active {
			display: block;
		}

		.view-my-proposal-hide-on-mobile {
			display: none;
		}
	}

	@media (min-width: 1024px) {
		.secondsellerdropdown-btn {
			display: none;
		}
	}

	@media (min-width: 1024px) {
		.firstsellerdropdown-btn {
			display: none;
		}
	}


	/* byuer seller order summary page css new add */
	.order-card {
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		padding: 16px;
		max-width: 100%;
		background-color: #fff;
		font-family: Arial, sans-serif;
	}

	.manage-req-heading-main {
		font-size: 13px;
	}

	@media (min-width: 1024px) {
		.order-card {
			display: none;
		}
	}

	.Order-Summary {
		margin: 0 0 10px;
		font-size: 1.25em;
		font-weight: bold;
		color: #a7a9ac;
	}

	.Order-Status-textmain {
		font-size: 1.25em;
		color: #a7a9ac;
	}

	.order-content {
		display: flex;
		gap: 10px;
		margin-bottom: 10px;
	}

	.order-image img {
		width: 100px;
		height: 85px;
		border-radius: 4px;
		object-fit: cover;
	}

	.order-text p {
		font-size: 13px;
		color: #333;
	}

	.order-text a {
		color: #007bff;
		text-decoration: none;
	}

	.order-info i {
		margin-right: 4px;
	}

	.order-status {
		display: flex;
		justify-content: space-between;
		align-items: center;
		border-top: 1px solid #e0e0e0;
		padding-top: 10px;
	}

	@media (max-width: 767px) {
		.buyer-nitin-edit-sec {
			display: none;
		}
	}

	.info-container {
		display: flex;
		font-size: 0.85em;
		color: #555;
		gap: 40px;
	}

	.info-item {
		position: relative;
		cursor: pointer;
	}

	.info-item .heading {
		position: absolute;
		bottom: 120%;
		left: 50%;
		transform: translateX(-50%);
		padding: 5px 10px;
		background-color: rgba(0, 0, 0, 0.8);
		color: white;
		font-size: 14px;
		border-radius: 5px;
		opacity: 0;
		pointer-events: none;
		transition: all 0.4s ease;
		white-space: nowrap;
	}

	.info-item:hover .heading {
		opacity: 1;
		bottom: 100%;
	}

	.buyer-active-orderdataby-nitin {
		display: flex;
		gap: 20px;
		flex-direction: column;
	}

	.mobile-d-nones {
		display: none;
	}
</style>
<div class="col-md-12 padding-40">
	<div class="alert alert-info text-center mt-3 pt-3 pb-3 box-shadow-can-post hide-on-mobile">
		You can post <?php echo $totalProposal >= $num_gigs ? 0 : $num_gigs - $totalProposal ?> number of proposals.
	</div>
	<div class="col_md_12 display_flex-1 mt-0 mb-0 float_right justify-content-center">
		<?php if ($totalProposal >= $num_gigs) { ?>
			<a class="btn btn-success box-shadow-new-propo hide-on-mobile mobile-d-nones" href="<?= $site_url ?>/start_selling"><i class="fa fa-plus-circle"></i> <?= $lang['button']['add_new_proposal']; ?></a>
		<?php } else { ?>

			<a class="btn btn-success box-shadow-new-propo text_center margin-auto" href="<?= $site_url ?>/proposals/create_proposal"><i class="fa fa-plus-circle"></i> <?= $lang['button']['add_new_proposal']; ?></a>
		<?php } ?>
	</div>
	<!-- 
	<div class="notify_you_model">
<div> </div>
</div> -->

	<div class="secondsellerdropdown-dropdown" id="secondsellerdropdownContainer">
		<button class="secondsellerdropdown-btn" onclick="toggleSecondsellerDropdown()">Manage Requests
			<span class="secondsellerdropdown-icon"><i class="fa-solid fa-caret-down"></i></span>
		</button>
		<div class="secondsellerdropdown-content" id="secondsellerdropdownMenu">
			<ul class="secondsellerdropdown-list">
				<li class="secondsellerdropdown-item-active">
					<a href="#" class="secondsellerdropdown-link-active">Active Proposals <span class="secondsellerdropdown-badge-active">0</span></a>
				</li>
				<li class="secondsellerdropdown-item-paused">
					<a href="#" class="secondsellerdropdown-link-paused">Paused Proposals <span class="secondsellerdropdown-badge-paused">0</span></a>
				</li>
				<li class="secondsellerdropdown-item-pending">
					<a href="#" class="secondsellerdropdown-link-pending">Pending Proposals <span class="secondsellerdropdown-badge-pending">0</span></a>
				</li>
				<li class="secondsellerdropdown-item-modification">
					<a href="#" class="secondsellerdropdown-link-modification">Requires Modification <span class="secondsellerdropdown-badge-modification">0</span></a>
				</li>
				<li class="secondsellerdropdown-item-draft">
					<a href="#" class="secondsellerdropdown-link-draft">Draft <span class="secondsellerdropdown-badge-draft">0</span></a>
				</li>
				<li class="secondsellerdropdown-item-declined">
					<a href="#" class="secondsellerdropdown-link-declined">Declined <span class="secondsellerdropdown-badge-declined">0</span></a>
				</li>
			</ul>
			<button class="secondsellerdropdown-btn-ok">OK</button>
		</div>
	</div>

	<div class="clearfix"></div>
	<!-- <div class="dropdown mt-3 seller-active-order-nitin">
		<button class="btn btn-secondary dropdown-toggle active-proposals-nitin" type="button" id="proposalDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			Active Proposals
		</button>
		<div class="dropdown-menu" aria-labelledby="proposalDropdown">
			<a class="dropdown-item <?= $active; ?>" href="#active-proposals" data-toggle="tab">
				<?= $lang['tabs']['active_proposals']; ?> &nbsp; &nbsp; <span class="badge badge-success float-right"><?= $count_active_proposals; ?></span>
			</a>
			<a class="dropdown-item <?= (isset($_GET['paused'])) ? "active" : ""; ?>" href="#pause-proposals" data-toggle="tab">
				<?= $lang['tabs']['pause_proposals']; ?> &nbsp; &nbsp; <span class="badge badge-success float-right"><?= $count_pause_proposals; ?></span>
			</a>
			<a class="dropdown-item <?= (isset($_GET['pending'])) ? "active" : ""; ?>" href="#pending-proposals" data-toggle="tab">
				<?= $lang['tabs']['pending_proposals']; ?> &nbsp; &nbsp; <span class="badge badge-success float-right"><?= $count_pending_proposals; ?></span>
			</a>
			<a class="dropdown-item <?= (isset($_GET['modification'])) ? "active" : ""; ?>" href="#modification-proposals" data-toggle="tab">
				<?= $lang['tabs']['requires_modification']; ?> &nbsp; &nbsp; <span class="badge badge-success float-right"><?= $count_modification_proposals; ?></span>
			</a>
			<a class="dropdown-item <?= (isset($_GET['draft'])) ? "active" : ""; ?>" href="#draft-proposals" data-toggle="tab">
				<?= $lang['tabs']['draft']; ?> &nbsp; &nbsp; <span class="badge badge-success float-right"><?= $count_draft_proposals; ?></span>
			</a>
			<a class="dropdown-item <?= (isset($_GET['declined'])) ? "active" : ""; ?>" href="#declined-proposals" data-toggle="tab">
				<?= $lang['tabs']['declined']; ?> &nbsp; &nbsp; <span class="badge badge-success float-right"><?= $count_declined_proposals; ?></span>
			</a>
		</div>
	</div> -->

	<ul class="nav nav-tabs flex-column flex-sm-row mt-3 oldseller-section view-my-proposal-hide-on-mobile">
		<li class="nav-item width-increased">
			<a href="#active-proposals" data-toggle="tab" class="nav-link make-black <?= $active; ?> pt-pr">
				<?= $lang['tabs']['active_proposals']; ?> &nbsp; &nbsp; <span class="badge badge-success badge-float-right"><?= $count_active_proposals; ?></span>
			</a>
		</li>
		<li class="nav-item width-increased">
			<a href="#pause-proposals" data-toggle="tab" class="nav-link make-black <?= (isset($_GET['paused'])) ? "active" : ""; ?>  pt-pr">
				<?= $lang['tabs']['pause_proposals']; ?> &nbsp; &nbsp; <span class="badge badge-success badge-float-right"><?= $count_pause_proposals; ?></span>
			</a>
		</li>
		<li class="nav-item width-increased">
			<a href="#pending-proposals" data-toggle="tab" class="nav-link make-black <?= (isset($_GET['pending'])) ? "active" : ""; ?>  pt-pr">
				<?= $lang['tabs']['pending_proposals']; ?> &nbsp; &nbsp; <span class="badge badge-success badge-float-right"><?= $count_pending_proposals; ?></span>
			</a>
		</li>
		<li class="nav-item width-increaseds">
			<a href="#modification-proposals" data-toggle="tab" class="nav-link make-black <?= (isset($_GET['modification'])) ? "active" : ""; ?>  pt-pr">
				<?= $lang['tabs']['requires_modification']; ?> &nbsp; &nbsp; <span class="badge badge-success badge-float-right"><?= $count_modification_proposals; ?></span>
			</a>
		</li>
		<li class="nav-item width-increasese">
			<a href="#draft-proposals" data-toggle="tab" class="nav-link make-black <?= (isset($_GET['draft'])) ? "active" : ""; ?>  pt-pr">
				<?= $lang['tabs']['draft']; ?> &nbsp; &nbsp; <span class="badge badge-success badge-float-right"><?= $count_draft_proposals; ?></span>
			</a>
		</li>
		<li class="nav-item width-increases">
			<a href="#declined-proposals" data-toggle="tab" class="nav-link make-black <?= (isset($_GET['declined'])) ? "active" : ""; ?>  pt-pr">
				<?= $lang['tabs']['declined']; ?> &nbsp; &nbsp; <span class="badge badge-success badge-float-right"><?= $count_declined_proposals; ?></span>
			</a>
		</li>
	</ul>


	<script>
		function notifyYou() {
			alert('hello');
		}
	</script>

	<div class="tab-content active-proposel-seller-sec">
		<div id="active-proposals" class="tab-pane fade show <?= $active; ?>">
			<div class="table-responsive box-table mt-3 box-shadow-act-pro">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="font-size-3"><?= $lang['th']['proposal_title']; ?></th>
							<th class="font-size-3"><?= $lang['th']['proposal_price']; ?></th>
							<th class="font-size-3"><?= $lang['th']['views']; ?></th>
							<th class="font-size-3"><?= $lang['th']['orders']; ?></th>
							<th class="font-size-3"><?= $lang['th']['actions']; ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (isset($_GET["page"]) && $active == "active") {
							$dPageNumber = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
							if (!is_numeric($dPageNumber)) {
								die('Invalid page number!');
							} //incase of invalid page number
						} else {
							$dPageNumber = 1; //if there's no page number, set it to 1
						}

						$start_from =  (($dPageNumber - 1) * $limit);
						$where_limit = " order by proposal_id DESC LIMIT $start_from, $limit";

						$q_page =  $db->query("SELECT * FROM proposals WHERE proposal_seller_id=:proposal_seller_id AND proposal_status=:proposal_status", array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'active'));
						$totalDRows = $q_page->rowCount();

						//break records into pages
						$totalDPages = ceil($totalDRows / $limit);
						if ($totalDRows > 0) {
							$select_proposals =  $db->query("SELECT * FROM proposals WHERE proposal_seller_id=:proposal_seller_id AND proposal_status=:proposal_status $where_limit", array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'active'));

							while ($row_proposals = $select_proposals->fetch()) {
								$proposal_id = $row_proposals->proposal_id;
								$proposal_title = $row_proposals->proposal_title;
								$proposal_views = $row_proposals->proposal_views;
								$proposal_price = $row_proposals->proposal_price;
								if ($proposal_price == 0) {
									$get_p = $db->select("proposal_packages", array("proposal_id" => $proposal_id, "package_name" => "Basic"));
									$proposal_price = $get_p->fetch()->price;
								}
								$proposal_img1 = getImageUrl2("proposals", "proposal_img1", $row_proposals->proposal_img1);
								$proposal_url = $row_proposals->proposal_url;
								$proposal_featured = $row_proposals->proposal_featured;
								$count_orders = $db->count("orders", array("proposal_id" => $proposal_id));
						?>
								<tr>
									<td class="proposal-title"> <?= $proposal_title; ?> </td>
									<td class="text-success"> <?= showPrice($proposal_price); ?> </td>
									<td><?= $proposal_views; ?></td>
									<td><?= $count_orders; ?></td>
									<td class="text-center">
										<div class="dropdown">
											<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"></button>
											<div class="dropdown-menu">
												<a href="<?= $site_url; ?>/proposals/<?= $login_seller_user_name; ?>/<?= $proposal_url; ?>" class="dropdown-item"> Preview </a>
												<?php if ($proposal_featured == "no") { ?>
													<a href="#" class="dropdown-item" id="featured-button-<?= $proposal_id; ?>">Make Proposal Featured</a>
												<?php } else { ?>
													<a href="#" class="dropdown-item text-success">Already Featured </a>
												<?php } ?>
												<a href="<?= $site_url; ?>/proposals/pause_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Deactivate Proposal</a>
												<a href="<?= $site_url; ?>/proposals/view_coupons?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> View Coupons</a>
												<a href="<?= $site_url; ?>/proposals/view_referrals?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> View Referrals</a>
												<a href="<?= $site_url; ?>/proposals/edit_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Edit </a>
												<a href="<?= $site_url; ?>/proposals/delete_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this proposal?')"> Delete </a>
											</div>
										</div>
										<script>
											$("#featured-button-<?= $proposal_id; ?>").click(function() {
												proposal_id = "<?= $proposal_id; ?>";
												$.ajax({
														method: "POST",
														url: "<?= $site_url; ?>/proposals/pay_featured_listing",
														data: {
															proposal_id: proposal_id
														}
													}).done(function(data) {
														$("#featured-proposal-modal").html(data);
													})
													.fail((jqXHR, textStatus, errorThrown) => {
														console.log('fail', jqXHR.status);
														alert(jqXHR.status)
													});
											});
										</script>
									</td>
								</tr>
							<?php }
						} else {
							?>
							<tr class="table-danger box-shadow-bg-color">
								<td colspan="5" class="box-shadow-cs5">
									<center>
										<h3 class='pb-4 pt-4 heading_3'>
											<i class='fa fa-meh-o'></i> You currently have no proposals/services to sell.
										</h3>
									</center>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<nav id="pagination-proposals-acive" aria-label="Draft proposals navigation">
					<?= pagination($limit, $dPageNumber, $totalDRows, $totalDPages, $site_url . "/proposals/view_proposals?page="); ?>
				</nav>
			</div>
		</div>
		<div id="pause-proposals" class="tab-pane fade show <?= (isset($_GET['paused'])) ? "active" : ""; ?>">
			<div class="table-responsive box-table mt-3 box-shadow-act-pro">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="font-size-3"><?= $lang['th']['proposal_title']; ?></th>
							<th class="font-size-3"><?= $lang['th']['proposal_price']; ?></th>
							<th class="font-size-3"><?= $lang['th']['views']; ?></th>
							<th class="font-size-3"><?= $lang['th']['orders']; ?></th>
							<th class="font-size-3"><?= $lang['th']['actions']; ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (isset($_GET["page"]) && isset($_GET['paused'])) {
							$dPageNumber = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
							if (!is_numeric($dPageNumber)) {
								die('Invalid page number!');
							} //incase of invalid page number
						} else {
							$dPageNumber = 1; //if there's no page number, set it to 1
						}

						$start_from =  (($dPageNumber - 1) * $limit);
						$where_limit = " order by proposal_id DESC LIMIT $start_from, $limit";

						$q_page =  $db->query("SELECT * FROM proposals WHERE proposal_seller_id=:proposal_seller_id AND (proposal_status='pause' or proposal_status='admin_pause')", array("proposal_seller_id" => $login_seller_id));
						$totalDRows = $q_page->rowCount();

						//break records into pages
						$totalDPages = ceil($totalDRows / $limit);
						if ($totalDRows > 0) {
							$select_proposals =  $db->query("SELECT * FROM proposals WHERE proposal_seller_id=:proposal_seller_id AND (proposal_status='pause' or proposal_status='admin_pause') $where_limit", array("proposal_seller_id" => $login_seller_id));

							while ($row_proposals = $select_proposals->fetch()) {
								$proposal_id = $row_proposals->proposal_id;
								$proposal_title = $row_proposals->proposal_title;
								$proposal_views = $row_proposals->proposal_views;
								$proposal_price = $row_proposals->proposal_price;
								if ($proposal_price == 0) {
									$get_p = $db->select("proposal_packages", array("proposal_id" => $proposal_id, "package_name" => "Basic"));
									$proposal_price = $get_p->fetch()->price;
								}
								$proposal_img1 = getImageUrl2("proposals", "proposal_img1", $row_proposals->proposal_img1);
								$proposal_url = $row_proposals->proposal_url;
								$proposal_featured = $row_proposals->proposal_featured;
								$proposal_status = $row_proposals->proposal_status;

								$count_orders = $db->count("orders", array("proposal_id" => $proposal_id));

								if ($proposal_status == "admin_pause") {
									$onclick = <<<EOT
								onclick="return confirm('{$lang['view_proposals']['admin_pause_proposal']}')"
								EOT;
								} else {
									$onclick = "";
								}

						?>
								<tr>
									<td class="proposal-title"> <?= $proposal_title; ?> </td>
									<td class="text-success"> <?= showPrice($proposal_price); ?> </td>
									<td><?= $proposal_views; ?></td>
									<td><?= $count_orders; ?></td>
									<td class="text-center">
										<div class="dropdown">
											<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"></button>
											<div class="dropdown-menu">
												<a href="<?= $site_url; ?>/proposals/<?= $login_seller_user_name; ?>/<?= $proposal_url; ?>" class="dropdown-item"> Preview </a>
												<a href="<?= $site_url; ?>/proposals/activate_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item" <?= $onclick; ?>>
													Activate
												</a>
												<a href="<?= $site_url; ?>/proposals/view_referrals?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> View Referrals</a>
												<a href="<?= $site_url; ?>/proposals/edit_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Edit </a>
												<a href="<?= $site_url; ?>/proposals/delete_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this proposal?')"> Delete </a>
											</div>
										</div>
									</td>
								</tr>
							<?php }
						} else {
							?>
							<tr class="table-danger box-shadow-bg-color">
								<td colspan="5" class="box-shadow-cs5">
									<center>
										<h3 class='pb-4 pt-4 heading_3'>
											<i class='fa fa-meh-o'></i> You currently have no paused proposals/services
										</h3>
									</center>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<nav id="pagination-proposals-pause" aria-label="Draft proposals navigation">
					<?= pagination($limit, $dPageNumber, $totalDRows, $totalDPages, $site_url . "/proposals/view_proposals?pause&page="); ?>
				</nav>
			</div>
		</div>
		<div id="pending-proposals" class="tab-pane fade show <?= (isset($_GET['pending'])) ? "active" : ""; ?>">
			<div class="table-responsive box-table mt-3 box-shadow-act-pro">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="font-size-3"><?= $lang['th']['proposal_title']; ?></th>
							<th class="font-size-3"><?= $lang['th']['proposal_price']; ?></th>
							<th class="font-size-3"><?= $lang['th']['views']; ?></th>
							<th class="font-size-3"><?= $lang['th']['orders']; ?></th>
							<th class="font-size-3"><?= $lang['th']['actions']; ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (isset($_GET["page"]) && isset($_GET['pending'])) {
							$dPageNumber = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
							if (!is_numeric($dPageNumber)) {
								die('Invalid page number!');
							} //incase of invalid page number
						} else {
							$dPageNumber = 1; //if there's no page number, set it to 1
						}

						$start_from =  (($dPageNumber - 1) * $limit);
						$where_limit = " order by proposal_id DESC LIMIT $start_from, $limit";

						$q_page =  $db->query("SELECT * FROM proposals WHERE proposal_seller_id=:proposal_seller_id AND proposal_status=:proposal_status", array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'pending'));
						$totalDRows = $q_page->rowCount();

						//break records into pages
						$totalDPages = ceil($totalDRows / $limit);
						if ($totalDRows > 0) {
							$select_proposals =  $db->query("SELECT * FROM proposals WHERE proposal_seller_id=:proposal_seller_id AND proposal_status=:proposal_status $where_limit", array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'pending'));

							while ($row_proposals = $select_proposals->fetch()) {
								$proposal_id = $row_proposals->proposal_id;
								$proposal_title = $row_proposals->proposal_title;
								$proposal_views = $row_proposals->proposal_views;
								$proposal_price = $row_proposals->proposal_price;
								if ($proposal_price == 0) {
									$get_p = $db->select("proposal_packages", array("proposal_id" => $proposal_id, "package_name" => "Basic"));
									$proposal_price = $get_p->fetch()->price;
								}
								$proposal_img1 = getImageUrl2("proposals", "proposal_img1", $row_proposals->proposal_img1);
								$proposal_url = $row_proposals->proposal_url;
								$proposal_featured = $row_proposals->proposal_featured;
								$count_orders = $db->count("orders", array("proposal_id" => $proposal_id));
						?>
								<tr>
									<td class="proposal-title"> <?= $proposal_title; ?> </td>
									<td class="text-success"> <?= showPrice($proposal_price); ?> </td>
									<td><?= $proposal_views; ?></td>
									<td><?= $count_orders; ?></td>
									<td class="text-center">
										<div class="dropdown">
											<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"></button>
											<div class="dropdown-menu">
												<a href="<?= $site_url; ?>/proposals/<?= $login_seller_user_name; ?>/<?= $proposal_url; ?>" class="dropdown-item"> Preview </a>
												<a href="<?= $site_url; ?>/proposals/edit_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Edit </a>
												<a href="<?= $site_url; ?>/proposals/delete_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this proposal?')"> Delete </a>
											</div>
										</div>
									</td>
								</tr>
							<?php }
						} else {
							?>
							<tr class="table-danger box-shadow-bg-color">
								<td colspan="5" class="box-shadow-cs5">
									<center>
										<h3 class='pb-4 pt-4 heading_3'>
											<i class='fa fa-meh-o'></i> You currently have no proposals/services pending.
										</h3>
									</center>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<nav id="pagination-proposals-pending" aria-label="Draft proposals navigation">
					<?= pagination($limit, $dPageNumber, $totalDRows, $totalDPages, $site_url . "/proposals/view_proposals?pending&page="); ?>
				</nav>
			</div>
		</div>
		<div id="modification-proposals" class="tab-pane fade show <?= (isset($_GET['modification'])) ? "active" : ""; ?>">
			<div class="table-responsive box-table mt-3 box-shadow-act-pro">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="font-size-3"><?= $lang['th']['modification_proposal_title']; ?></th>
							<th class="font-size-3"><?= $lang['th']['modification_message']; ?></th>
							<th class="font-size-3"><?= $lang['th']['actions']; ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (isset($_GET["page"]) && isset($_GET['modification'])) {
							$dPageNumber = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
							if (!is_numeric($dPageNumber)) {
								die('Invalid page number!');
							} //incase of invalid page number
						} else {
							$dPageNumber = 1; //if there's no page number, set it to 1
						}

						$start_from =  (($dPageNumber - 1) * $limit);
						$where_limit = " order by proposal_id DESC LIMIT $start_from, $limit";

						$q_page =  $db->query("SELECT * FROM proposals WHERE proposal_seller_id=:proposal_seller_id AND proposal_status=:proposal_status", array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'modification'));
						$totalDRows = $q_page->rowCount();

						//break records into pages
						$totalDPages = ceil($totalDRows / $limit);
						if ($totalDRows > 0) {
							$select_proposals =  $db->query("SELECT * FROM proposals WHERE proposal_seller_id=:proposal_seller_id AND proposal_status=:proposal_status $where_limit", array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'modification'));

							while ($row_proposals = $select_proposals->fetch()) {
								$proposal_id = $row_proposals->proposal_id;
								$proposal_title = $row_proposals->proposal_title;
								$proposal_url = $row_proposals->proposal_url;
								$select_modification = $db->select("proposal_modifications", array("proposal_id" => $proposal_id));
								$row_modification = $select_modification->fetch();
								$modification_message = $row_modification->modification_message;
						?>
								<tr>
									<td class="proposal-title"> <?= $proposal_title; ?> </td>
									<td> <?= $modification_message; ?></td>
									<td class="text-center">
										<div class="dropdown">
											<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"></button>
											<div class="dropdown-menu">
												<a href="<?= $site_url; ?>/proposals/submit_approval?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Submit For Approval </a>
												<a href="<?= $site_url; ?>/proposals/<?= $login_seller_user_name; ?>/<?= $proposal_url; ?>" class="dropdown-item"> Preview </a>
												<a href="<?= $site_url; ?>/proposals/edit_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Edit </a>
												<a href="<?= $site_url; ?>/proposals/delete_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this proposal?')"> Delete </a>
											</div>
										</div>
									</td>
								</tr>
							<?php }
						} else {
							?>
							<tr class="table-danger box-shadow-bg-color">
								<td colspan="5" class="box-shadow-cs5">
									<center>
										<h3 class='pb-4 pt-4 heading_3'>
											<i class='fa fa-meh-o'></i> You currently have no modifications requested.
										</h3>
									</center>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<nav id="pagination-proposals-modification" aria-label="modification proposals navigation">
					<?= pagination($limit, $dPageNumber, $totalDRows, $totalDPages, $site_url . "/proposals/view_proposals?modification&page="); ?>
				</nav>
			</div>
		</div>
		<div id="draft-proposals" class="tab-pane fade show <?= (isset($_GET['draft'])) ? "active" : ""; ?>">
			<div class="table-responsive box-table mt-3 box-shadow-act-pro">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="font-size-3"><?= $lang['th']['proposal_title']; ?></th>
							<th class="font-size-3"><?= $lang['th']['proposal_price']; ?></th>
							<th class="font-size-3"><?= $lang['th']['views']; ?></th>
							<th class="font-size-3"><?= $lang['th']['orders']; ?></th>
							<th class="font-size-3"><?= $lang['th']['actions']; ?></th>
						</tr>
					</thead>
					<tbody class="box-shadow-draft">
						<?php
						if (isset($_GET["page"]) && isset($_GET['draft'])) {
							$dPageNumber = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
							if (!is_numeric($dPageNumber)) {
								die('Invalid page number!');
							} //incase of invalid page number
						} else {
							$dPageNumber = 1; //if there's no page number, set it to 1
						}

						$start_from =  (($dPageNumber - 1) * $limit);
						$where_limit = " order by proposal_id DESC LIMIT $start_from, $limit";

						$q_page =  $db->query("SELECT * FROM proposals WHERE proposal_seller_id=:proposal_seller_id AND proposal_status=:proposal_status", array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'draft'));
						$totalDRows = $q_page->rowCount();

						//break records into pages
						$totalDPages = ceil($totalDRows / $limit);
						if ($totalDRows > 0) {
							$select_proposals =  $db->query("SELECT * FROM proposals WHERE proposal_seller_id=:proposal_seller_id AND proposal_status=:proposal_status $where_limit", array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'draft'));
							while ($row_proposals = $select_proposals->fetch()) {
								$proposal_id = $row_proposals->proposal_id;
								$proposal_title = $row_proposals->proposal_title;
								$proposal_views = $row_proposals->proposal_views;
								$proposal_price = $row_proposals->proposal_price;
								if ($proposal_price == 0) {
									$get_p = $db->select("proposal_packages", array("proposal_id" => $proposal_id, "package_name" => "Basic"));
									$proposal_price = $get_p->fetch()->price;
								}
								$proposal_img1 = getImageUrl2("proposals", "proposal_img1", $row_proposals->proposal_img1);
								$proposal_url = $row_proposals->proposal_url;
								$proposal_featured = $row_proposals->proposal_featured;
								$count_orders = $db->count("orders", array("proposal_id" => $proposal_id));
						?>
								<tr>
									<td class="proposal-title"> <?= $proposal_title; ?> </td>
									<td class="text-success"> <?= showPrice($proposal_price); ?> </td>
									<td><?= $proposal_views; ?></td>
									<td><?= $count_orders; ?></td>
									<td class="text-center">
										<div class="dropdown">
											<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"></button>
											<div class="dropdown-menu">
												<a href="<?= $site_url; ?>/proposals/edit_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Edit </a>
												<a href="<?= $site_url; ?>/proposals/delete_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this proposal?')"> Delete </a>
											</div>
										</div>
									</td>
								</tr>
							<?php }
						} else {
							?>
							<tr class="table-danger box-shadow-bg-color">
								<td colspan="5" class="box-shadow-cs5">
									<center>
										<h3 class='pb-4 pt-4 heading_3'>
											<i class='fa fa-meh-o'></i> You currently have no proposals/services in draft.
										</h3>
									</center>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<nav id="pagination-proposals-draft" aria-label="Draft proposals navigation">
					<?= pagination($limit, $dPageNumber, $totalDRows, $totalDPages, $site_url . "/proposals/view_proposals?draft&page="); ?>
				</nav>
			</div>
		</div>
		<div id="declined-proposals" class="tab-pane fade show <?= (isset($_GET['declined'])) ? "active" : ""; ?>">
			<div class="table-responsive box-table mt-3 box-shadow-act-pro">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="font-size-3"><?= $lang['th']['proposal_title']; ?></th>
							<th class="font-size-3"><?= $lang['th']['proposal_price']; ?></th>
							<th class="font-size-3"><?= $lang['th']['views']; ?></th>
							<th class="font-size-3"><?= $lang['th']['orders']; ?></th>
							<th class="font-size-3"><?= $lang['th']['actions']; ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (isset($_GET["page"]) && isset($_GET['declined'])) {
							$dPageNumber = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
							if (!is_numeric($dPageNumber)) {
								die('Invalid page number!');
							} //incase of invalid page number
						} else {
							$dPageNumber = 1; //if there's no page number, set it to 1
						}

						$start_from =  (($dPageNumber - 1) * $limit);
						$where_limit = " order by proposal_id DESC LIMIT $start_from, $limit";

						$q_page =  $db->query("SELECT * FROM proposals WHERE proposal_seller_id=:proposal_seller_id AND proposal_status=:proposal_status", array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'declined'));
						$totalDRows = $q_page->rowCount();

						//break records into pages
						$totalDPages = ceil($totalDRows / $limit);
						if ($totalDRows > 0) {
							$select_proposals =  $db->query("SELECT * FROM proposals WHERE proposal_seller_id=:proposal_seller_id AND proposal_status=:proposal_status $where_limit", array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'declined'));
							while ($row_proposals = $select_proposals->fetch()) {
								$proposal_id = $row_proposals->proposal_id;
								$proposal_title = $row_proposals->proposal_title;
								$proposal_views = $row_proposals->proposal_views;
								$proposal_price = $row_proposals->proposal_price;
								if ($proposal_price == 0) {
									$get_p = $db->select("proposal_packages", array("proposal_id" => $proposal_id, "package_name" => "Basic"));
									$proposal_price = $get_p->fetch()->price;
								}
								$proposal_img1 = getImageUrl2("proposals", "proposal_img1", $row_proposals->proposal_img1);
								$proposal_url = $row_proposals->proposal_url;
								$proposal_featured = $row_proposals->proposal_featured;
								$count_orders = $db->count("orders", array("proposal_id" => $proposal_id));
						?>
								<tr>
									<td class="proposal-title"> <?= $proposal_title; ?> </td>
									<td class="text-success"> <?= showPrice($proposal_price); ?> </td>
									<td><?= $proposal_views; ?></td>
									<td><?= $count_orders; ?></td>
									<td class="text-center">
										<div class="dropdown">
											<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"></button>
											<div class="dropdown-menu">
												<a href="<?= $site_url; ?>/proposals/delete_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this proposal?')"> Delete </a>
											</div>
										</div>
									</td>
								</tr>
							<?php }
						} else {
							?>
							<tr class="table-danger box-shadow-bg-color">
								<td colspan="5" class="box-shadow-cs5">
									<center>
										<h3 class='pb-4 pt-4 heading_3'>
											<i class='fa fa-meh-o'></i> You currently have no proposals/services declined.
										</h3>
									</center>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<nav id="pagination-proposals-draft" aria-label="Draft proposals navigation">
					<?= pagination($limit, $dPageNumber, $totalDRows, $totalDPages, $site_url . "/proposals/view_proposals?declined&page="); ?>
				</nav>
			</div>
		</div>
	</div>
	<!-- nitin add mobile view seller active proposals -->
	<div class="buyer-active-orderdataby-nitin mt-4">
		<div class="order-card">
			<!-- <h3 class="Order-Summary">Order Summary</h3> -->
			<div class="order-content">
				<!-- <div class="order-image">
					<img src="https://images.unsplash.com/photo-1688888745596-da40843a8d45?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mjd8fHByb2ZpbGUlMjBwaG90b3xlbnwwfHwwfHx8MA%3D%3D" alt="Order Image">
				</div> -->
				<div class="order-text">
					<h3 class="manage-req-heading-main">Expert nnnnnn in CSS and HTML: Crafting Responsive and Accessible Web Designs</h3>
					<div class="order-info">
						<div class="info-container">
							<div class="info-item">
								<i class="fa-solid fa-basket-shopping"></i> 0
								<span class="heading">Orders</span>
							</div>
							<div class="info-item">
								<i class="fa-solid fa-eye"></i> 0
								<span class="heading"> Views</span>
							</div>
							<div class="info-item">
								<i class="fa-solid fa-sack-dollar"></i> 25.00
								<span class="heading">Proposal's Price</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="order-status">
				<span class="Order-Status-textmain">Actions</span>
				<div class="custom-dropdown">
					<button class="custom-dropdown-button" id="dropdownBtn">
						<i class="fa-solid fa-caret-down"></i>
					</button>
					<div class="custom-dropdown-content" id="dropdownMenu">
						<a href="#">Preview</a>
						<a href="#">Make Proposal Featured</a>
						<a href="#">Deactivate Proposal</a>
						<a href="#">View Coupons</a>
						<a href="#">View Referrals</a>
						<a href="#">Edit</a>
						<a href="#">Delete</a>
					</div>
				</div>
			</div>
		</div>
		<div class="order-card">
			<!-- <h3 class="Order-Summary">Order Summary</h3> -->
			<div class="order-content">
				<!-- <div class="order-image">
					<img src="https://images.unsplash.com/photo-1688888745596-da40843a8d45?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mjd8fHByb2ZpbGUlMjBwaG90b3xlbnwwfHwwfHx8MA%3D%3D" alt="Order Image">
				</div> -->
				<div class="order-text">
					<h3 class="manage-req-heading-main">2Experienced Web Developer Specializing in User-Friendly, Responsive Websites </h3>
					<div class="order-info">
						<div class="info-container">
							<div class="info-item">
								<i class="fa-solid fa-basket-shopping"></i> 0
								<span class="heading">Orders</span>
							</div>
							<div class="info-item">
								<i class="fa-solid fa-eye"></i> 0
								<span class="heading"> Views</span>
							</div>
							<div class="info-item">
								<i class="fa-solid fa-sack-dollar"></i> 25.00
								<span class="heading">Proposal's Price</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="order-status">
				<span class="Order-Status-textmain">Actions</span>
				<div class="custom-dropdown">
					<button class="custom-dropdown-button" id="dropdownBtn">
						<i class="fa-solid fa-caret-down"></i>
					</button>
					<div class="custom-dropdown-content" id="dropdownMenu">
						<a href="#">Preview</a>
						<a href="#">Make Proposal Featured</a>
						<a href="#">Deactivate Proposal</a>
						<a href="#">View Coupons</a>
						<a href="#">View Referrals</a>
						<a href="#">Edit</a>
						<a href="#">Delete</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		document.querySelectorAll(".custom-dropdown-button").forEach(button => {
			button.addEventListener("click", function(event) {
				const dropdownMenu = this.nextElementSibling;
				dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
				event.stopPropagation(); // Prevent event from bubbling up
			});
		});

		window.addEventListener("click", function() {
			document.querySelectorAll(".custom-dropdown-content").forEach(menu => {
				menu.style.display = "none"; // Hide all dropdowns
			});
		});
	</script>
	<script>
		function toggleSecondsellerDropdown() {
			var dropdownMenu = document.getElementById("secondsellerdropdownMenu");
			dropdownMenu.classList.toggle("active"); // Use class to toggle visibility
		}
	</script>

</div>
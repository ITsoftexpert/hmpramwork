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
		/* box-shadow: 0px 0px 1px gray, inset 0px 0px 75px #fc503d; */
	}

	.font-size-3 {
		border: 1px solid lightgray !important;
		text-align: center;
		/* box-shadow: 0px 0px 5px black, inset 0px 0px 15px #00c8d4; */
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

	.width-increased {
		/* box-shadow: inset 0px 0px 15px whitesmoke; */
	}

	.width-increaseds {
		/* box-shadow: inset 0px 0px 15px whitesmoke; */
	}

	.width-increasese {
		/* box-shadow:inset 0px 0px 15px whitesmoke; */
	}

	.width-increases {
		/* box-shadow: inset 0px 0px 15px whitesmoke; */
	}

	.pt-pr {
		padding: 9px 15px 9px 9px;
	}

	.padding-40 {
		/* padding: 1px 18px 2px 18px !important; */
	}

	.box-shadow-act-pro {
		/* box-shadow: 0px 0px 2px gray; */
	}

	.box-shadow-can-post {
		/* box-shadow: 0px 0px 1px black; */
	}

	.box-shadow-bg-color {
		/* box-shadow: 0px 0px 5px black, inset 0px 0px 25px gray; */
	}
	.box-shadow-draft{
		/* box-shadow: 0px 0px 5px black, inset 0px 0px 30px gray; */
	}
	.box-shadow-new-propo{
		/* box-shadow: 2px 2px 5px black; */
	}
	.notify_you_model{
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		background-color: grey;
		opacity: 0.5;
		position: fixed;
	}
</style>
<div class="col-md-12 padding-40">
	<div class="alert alert-info text-align-center mt-3 pt-3 pb-3 box-shadow-can-post">
		You can post <?php echo $totalProposal >= $num_gigs ? 0 : $num_gigs - $totalProposal ?> number of proposals.
	</div>
	<div class="col_md_12 display_flex-1 mt-2 mb-2 float_right">
		<?php if ($totalProposal >= $num_gigs) { ?>
			<a class="btn btn-success box-shadow-new-propo" href="<?= $site_url ?>/start_selling"><i class="fa fa-plus-circle"></i> <?= $lang['button']['add_new_proposal']; ?></a>
		<?php } else { ?>

			<a class="btn btn-success box-shadow-new-propo text_center margin-auto" href="<?= $site_url ?>/proposals/create_proposal"><i class="fa fa-plus-circle"></i> <?= $lang['button']['add_new_proposal']; ?></a>
		<?php } ?>
	</div>
<!-- 
	<div class="notify_you_model">
<div> </div>
</div> -->
	<div class="clearfix"></div>
	<ul class="nav nav-tabs flex-column flex-sm-row mt-3">
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
	function notifyYou(){
		alert('hello');
	}
</script>

	<div class="tab-content">
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
</div>
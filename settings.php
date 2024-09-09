<?php
session_start();
require_once("includes/db.php");
require_once("functions/email.php");
if (!isset($_SESSION['seller_user_name'])) {
	echo "<script>window.open('login','_self')</script>";
}
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_name = $row_login_seller->seller_name;
$login_seller_email = $row_login_seller->seller_email;
$login_seller_pass = $row_login_seller->seller_pass;
$login_seller_phone = $row_login_seller->seller_phone;
$login_seller_paypal_email = $row_login_seller->seller_paypal_email;
$login_seller_payoneer_email = $row_login_seller->seller_payoneer_email;
$login_seller_image = $row_login_seller->seller_image;
$login_seller_cover_image = $row_login_seller->seller_cover_image;

$login_seller_address = $row_login_seller->seller_address;
// $login_seller_cover_image = $row_login_seller->seller_cover_image;
// $login_seller_cover_image = $row_login_seller->seller_cover_image;
// $login_seller_cover_image = $row_login_seller->seller_cover_image;
// $login_seller_cover_image = $row_login_seller->seller_cover_image;


$login_seller_image_s3 = $row_login_seller->seller_image_s3;
$login_seller_cover_image_s3 = $row_login_seller->seller_cover_image_s3;

$login_seller_address_img1 = $row_login_seller->seller_address_img1;


$login_seller_headline = $row_login_seller->seller_headline;
$login_seller_country = $row_login_seller->seller_country;
$login_seller_city = $row_login_seller->seller_city;
$login_seller_timzeone = $row_login_seller->seller_timezone;
$login_seller_language = $row_login_seller->seller_language;
$login_seller_about = $row_login_seller->seller_about;
$login_seller_account_number = $row_login_seller->seller_m_account_number;
$login_seller_account_name = $row_login_seller->seller_m_account_name;
$login_seller_wallet = $row_login_seller->seller_wallet;
$login_seller_enable_sound = $row_login_seller->enable_sound;
$login_seller_enable_notifications = $row_login_seller->enable_notifications;
$login_seller_verification = $row_login_seller->seller_verification;

$select_seller_accounts = $db->select("seller_accounts", array("seller_id" => $login_seller_id));
$row_seller_accounts = $select_seller_accounts->fetch();
$current_balance = $row_seller_accounts->current_balance;

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$enable_payoneer = $row_payment_settings->enable_payoneer;
$enable_paypal = $row_payment_settings->enable_paypal;
$enable_dusupay = $row_payment_settings->enable_dusupay;

if ($lang_dir == "right") {
	$floatRight = "";
} else {
	$floatRight = "float-right";
}

// Memmbership
$no_of_gigs = $row_login_seller->no_of_gigs;
$bids_per_month = $row_login_seller->bids_per_month;
$skills = $row_login_seller->skills;

// Profile Weightness
$profileWeight = $professionalWeight = $accountWeight = 0;
$qSellerWeight = $db->select("seller_profile_weights", array("seller_id" => $login_seller_id));
$oSellerWeight = $qSellerWeight->fetch();
if ($oSellerWeight) {
	$profileWeight = $oSellerWeight->profile_weight;
	$professionalWeight = $oSellerWeight->professional_weight;
	$accountWeight = $oSellerWeight->account_weight;
}
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
	<title><?= $site_name; ?> - <?= $lang["menu"]["settings"]; ?> </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= $site_desc; ?>">
	<meta name="keywords" content="<?= $site_keywords; ?>">
	<meta name="author" content="<?= $site_author; ?>">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
	<link href="styles/bootstrap.css" rel="stylesheet">
	<link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
	<link href="styles/styles.css" rel="stylesheet">
	<link href="styles/user_nav_styles.css" rel="stylesheet">
	<link href="font_awesome/css/font-awesome.css" rel="stylesheet">
	<link href="styles/owl.carousel.css" rel="stylesheet">
	<link href="styles/owl.theme.default.css" rel="stylesheet">
	<link href="styles/sweat_alert.css" rel="stylesheet">
	<link href="styles/animate.css" rel="stylesheet">
	<link href="styles/croppie.css" rel="stylesheet">
	<!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
	<script src="js/ie.js"></script>
	<script type="text/javascript" src="js/sweat_alert.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/croppie.js"></script>
	<?php if ($paymentGateway == 1) { ?>
		<script src="plugins/paymentGateway/javascript/script.js"></script>
	<?php } ?>
	<?php if (!empty($site_favicon)) { ?>
		<link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
	<?php } ?>
	<script src="<?= $site_url; ?>/js/jquery.easy-autocomplete.min.js"></script>
	<link href="<?= $site_url; ?>/styles/easy-autocomplete.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<style>
		@media (max-width:768px) {
			.full-width {
				width: 100%;
				/* border:1px solid green; */
				font-size: 22px;
				display: flex;
			}

			.text-align-center {
				text-align: center !important;
				margin: auto;
				/* color: #2e8780; */
			}

			/* .margin-top {
				border: 1px solid green;
			} */

		}



		.box-shadow-8 {
			border: 1px solid gray;
			/* box-shadow: 0px 0px 10px black, inset 0px 0px 155px lightseagreen; */
		}

		.border12 {
			border: 2px solid black;
			height: 60px;
			padding-top: 18px;
			font-size: 16px;
			/* box-shadow:inset 0px 0px 15px gray; */
		}

		.square-box {
			/* border:1px solid red; */
			width: 50px;
			height: 35px;
			margin-top: -7px;
			background-color: whitesmoke;
			color: green;
			padding: 9px 0px 0px 14px;
			font-size: 13px;
			border-radius: 3px;
			/* box-shadow:inset 0px 0px 10px gray; */
		}



		@media(max-width:1100px) and (min-width:900px) {
			.border12 {
				/* border: 2px solid black; */
				height: 60px;
				padding: 18px 8px;
				font-size: 14px;
				/* box-shadow:inset 0px 0px 15px gray; */
			}

			.square-box {
				/* border:1px solid red; */
				width: 35px;
				height: 35px;
				margin-top: -7px;
				background-color: whitesmoke;
				color: green;
				padding: 9px 0px 0px 8px;
				font-size: 11px;
				border-radius: 3px;
				/* box-shadow:inset 0px 0px 10px gray; */
			}

			.box-shadow8 {
				border: 1px solid gray;
				/* box-shadow: 0px 0px 10px black, inset 0px 0px 55px lightseagreen; */
				/* height:30vh !important; */
				padding: 8px;
			}
		}

		.required_parameter_details {
			background-color: #e5e5e5;
			padding: 1rem;
			border-radius: 5px;
		}

		.background_color_greys {
			/* background-color: #e5e5e5; */
			margin-bottom: 3px;
		}

		@media(max-width:899px) and (min-width:768px) {
			.border12 {
				/* border: 2px solid black; */
				height: 60px;
				padding: 18px 5px;
				font-size: 14px;
				/* box-shadow:inset 0px 0px 15px gray; */
			}

			.square-box {
				/* border:1px solid red; */
				width: 30px;
				height: 35px;
				margin-top: -7px;
				background-color: whitesmoke;
				color: green;
				padding: 9px 0px 0px 5px;
				font-size: 11px;
				border-radius: 3px;
				/* box-shadow:inset 0px 0px 10px gray; */
			}

			.box-shadow8 {
				border: 1px solid gray;
				/* box-shadow: 0px 0px 10px black, inset 0px 0px 55px lightseagreen; */
				/* height:30vh !important; */
				padding: 5px;
			}
		}

		.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
			background-color: transparent !important;
			color: black !important;
			border: 2px solid #00cedc !important;
		}
	</style>
</head>

<body class="is-responsive">
	<?php require_once("includes/user_header.php"); ?>
	<div class="container-fluid p-5">
		<div class="row terms-page justify-content-center" style="<?= ($lang_dir == "right" ? 'direction: rtl;' : '') ?>">
			<div class="col-md-3 mb-3">
				<div class="card">
					<div class="card-body box-shadow8">
						<ul class="nav nav-pills flex-column  mb-4">
							<li class="nav-item background_color_greys">
								<a data-toggle="pill" href="#profile_settings" class="nav-link border border12
						<?php
						if (!isset($_GET['profile_settings']) and !isset($_GET['account_settings']) and !isset($_GET['professional_settings'])) {
							echo "active";
						}
						if (isset($_GET['profile_settings'])) {
							echo "active";
						}
						?>
						">
									<?= $lang["titles"]["settings"]["profile_settings"]; ?>
									<span class="square-box pull-right"><?= $profileWeight ?? 0 ?>%</span>
								</a>
							</li>
							<li class="nav-item background_color_greys">
								<a data-toggle="pill" href="#professional_settings" class="nav-link border border12 <?php if (isset($_GET['professional_settings'])) {
																														echo "active";
																													} ?> ">
									<?= $lang["titles"]["settings"]["professional_settings"]; ?>
									<span class="square-box pull-right"><?= $professionalWeight ?? 0 ?>%</span>
								</a>
							</li>
							<li class="nav-item background_color_greys">
								<a data-toggle="pill" href="#account_settings" class="nav-link border border12 <?php if (isset($_GET['account_settings'])) {
																													echo "active";
																												} ?> ">
									<?= $lang["titles"]["settings"]["account_settings"]; ?>
									<span class="square-box pull-right"><?= $accountWeight ?? 0 ?>%</span>
								</a>
							</li>
						</ul>
					</div>
					<div class="m-3 required_parameter_details" id="seller-sidebar" style="<?= $activeTab == "buyer" ? "display: none" : "" ?>">
						<div class="card card1  text-dark border-0 px-2 py-3 mb-4 box-shadow-buyer1">
							<div class="d-flex align-items-center justify-content-space-between">
								<h6>Set up your account</h6>
								<h6><?= $totalWeight ?>%</h6>
							</div>
							<div class="progress" style="height: 10px; border-radius: 1rem;">
								<div class="progress-bar" role="progressbar" style="width: <?= $totalWeight ?>%;" aria-valuenow="<?= $totalWeight ?>" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
						</div>
						<?php if ($profileWeight == 0) { ?>
							<div class="card card1 theme-bg text-white border-0   px-2  py-3 d-flex align-items-center justify-content-space-between w-100 flex-row mb-4 box-shadow-buyer1">
								<a href="<?= $site_url ?>/settings?profile_settings">
									<h6 class="mb-0 text-white"> <i class="fa fa-user mr-1"></i> <span>Complete your profile</span></h6>
								</a>
								<label class="mb-0 text-white font-weight-bold">+40%</label>
							</div>
						<?php
						}
						if ($professionalWeight == 0) {
						?>
							<div class="card card1 theme-bg text-white border-0   px-2  py-3 d-flex align-items-center justify-content-space-between w-100 flex-row mb-4 box-shadow-buyer1">
								<a href="<?= $site_url ?>/settings?professional_settings">
									<h6 class="mb-0 text-white"> <i class="fa fa-briefcase mr-1"></i> <span>Complete your professional</span></h6>
								</a>
								<label class="mb-0 text-white font-weight-bold">+40%</label>
							</div>
						<?php
						}
						if ($accountWeight == 0) {
						?>
							<div class="card card1 theme-bg text-white border-0  px-2  py-3 d-flex align-items-center justify-content-space-between w-100 flex-row mb-4 box-shadow-buyer1">
								<a href="<?= $site_url ?>/settings?account_settings">
									<h6 class="mb-0 text-white"> <i class="fa fa-money mr-1"></i> <span>Complete your account</span></h6>
								</a>
								<label class="mb-0 text-white font-weight-bold">+20%</label>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="col-md-8 mb-4">
				<div class="card box-shadow-8">
					<div class="card-body m-1 p-3">
						<div class="tab-content">
							<div id="profile_settings" class="tab-pane fade <?php if (!isset($_GET['profile_settings']) and !isset($_GET['account_settings'])  and !isset($_GET['professional_settings'])) {
																				echo "show active";
																			}
																			if (isset($_GET['profile_settings'])) {
																				echo "show active";
																			} ?>">
								<h2 class="mb-4 full-width"><span class="text-align-center"><?= $lang["titles"]["settings"]["profile_settings"]; ?></span></h2>
								<?php require_once("profile_settings.php") ?>
							</div>
							<div id="professional_settings" class="tab-pane fade <?php if (isset($_GET['professional_settings'])) {
																						echo "show active";
																					} ?>">
								<h2 class="mb-4 full-width margin-top p-"><span class="text-align-center"><?= $lang["titles"]["settings"]["professional_settings"]; ?></span></h2>
								<hr>
								<?php require_once("professional_settings.php") ?>
							</div>
							<div id="account_settings" class="tab-pane fade <?php if (isset($_GET['account_settings'])) {
																				echo "show active";
																			} ?>">
								<h2 class="mb-4 full-width"><span class="text-align-center"><?= $lang["titles"]["settings"]["account_settings"]; ?></span></h2>
								<?php require_once("account_settings.php") ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="insertimageModal" class="modal" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					Crop & Insert Image <button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<div id="image_demo" style="width:100% !important;"></div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="img_type" value="">
					<button class="btn btn-success crop_image"><?= $lang['button']['crop_image']; ?></button>
					<button type="button" class="btn btn-default" data-dismiss="modal"><?= $lang['button']['close']; ?></button>
				</div>
			</div>
		</div>
	</div>
	<div id="wait"></div>

	<?php require_once("includes/footer.php"); ?>
</body>

</html>
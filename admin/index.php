<?php

session_start();
include("includes/db.php");
include("../functions/mailer.php");
if (!isset($_SESSION['admin_email'])) {
	echo "<script>window.open('login','_self');</script>";
}

if ((time() - $_SESSION['loggedin_time']) > 9800) {
	echo "<script>window.open('logout?session_expired','_self');</script>";
}

if (!$_SESSION['adminLanguage']) {
	$_SESSION['adminLanguage'] = 1;
}

$adminLanguage = $_SESSION['adminLanguage'];
$row_language = $db->select("languages", array("id" => $adminLanguage))->fetch();
$currentLanguage = $row_language->title;
$admin_email = $_SESSION['admin_email'];

$get_admin = $db->query("select * from admins where admin_email=:a_email OR admin_user_name=:a_user_name", array("a_email" => $admin_email, "a_user_name" => $admin_email));
$row_admin = $get_admin->fetch();
$admin_id = $row_admin->admin_id;
$login_admin_id = $row_admin->admin_id;
$admin_name = $row_admin->admin_name;
$admin_user_name = $row_admin->admin_user_name;
$admin_image = $row_admin->admin_image;
$admin_country = $row_admin->admin_country;
$admin_job = $row_admin->admin_job;
$admin_contact = $row_admin->admin_contact;
$admin_about = $row_admin->admin_about;

$get_rights = $db->select("admin_rights", array("admin_id" => $admin_id));
$row_rights = $get_rights->fetch();
$a_settings = $row_rights->settings;
$a_plugins = $row_rights->plugins;
$a_pages = $row_rights->pages;
$a_blog = $row_rights->blog;
$a_feedback = $row_rights->feedback;
$a_video_schedules = $row_rights->video_schedules;
$a_proposals = $row_rights->proposals;
$a_accounting = $row_rights->accounting;
$a_payouts = $row_rights->payouts;
$a_reports = $row_rights->reports;
$a_inbox = $row_rights->inbox;
$a_reviews = $row_rights->reviews;
$a_buyer_requests = $row_rights->buyer_requests;
$a_restricted_words = $row_rights->restricted_words;
$a_alerts = $row_rights->notifications;
$a_cats = $row_rights->cats;
$a_delivery_times = $row_rights->delivery_times;
$a_seller_languages = $row_rights->seller_languages;
$a_seller_skills = $row_rights->seller_skills;
$a_seller_levels = $row_rights->seller_levels;
$a_customer_support = $row_rights->customer_support;
$a_coupons = $row_rights->coupons;
$a_slides = $row_rights->slides;
$a_sellers = $row_rights->sellers;
$a_slides = $row_rights->slides;
$a_terms = $row_rights->terms;
$a_orders = $row_rights->orders;
$a_referrals = $row_rights->referrals;
$a_files = $row_rights->files;
$a_knowledge_bank = $row_rights->knowledge_bank;
$a_currencies = $row_rights->currencies;
$a_languages = $row_rights->languages;
$a_m_plan = $row_rights->membplan;


$a_admins = $row_rights->admins;
$a_videos = $row_rights->videos;
$a_instruction = $row_rights->instruction;

$cat_attr = $row_rights->cat_attr;
$cat_attr_icon = $row_rights->cat_attr_icon;

$get_app_license = $db->select("app_license");
$row_app_license = $get_app_license->fetch();
$purchase_code = $row_app_license->purchase_code;
$license_type = $row_app_license->license_type;
$website = $row_app_license->website;

$count_sellers = $db->count("sellers");
$count_notifications = $db->count("admin_notifications", array("status" => "unread"));
$count_orders = $db->count("orders", array("order_active" => "yes"));
$count_orders_cancel = $db->count("orders", array("order_status" => "cancellation requested"));
$count_proposals = $db->count("proposals", array("proposal_status" => "pending"));
// $count_support_tickets = $db->count("support_tickets", array("status" => "open"));
$q_support_tickets = $db->query("SELECT * FROM support_tickets WHERE enquiry_id != 1 AND status = :status", array("status" => "open"));
$count_support_tickets = $q_support_tickets->rowCount();
$q_osupport_tickets = $db->query("SELECT * FROM support_tickets WHERE enquiry_id = 1 AND status = :status", array("status" => "open"));
$count_osupport_tickets = $q_osupport_tickets->rowCount();
$count_requests = $db->count("buyer_requests", array("request_status" => "pending"));
$count_referrals = $db->count("referrals", array("status" => "pending"));
$count_proposals_referrals = $db->count("proposal_referrals", array("status" => "pending"));


$order_reports = $db->count("reports", array("content_type" => "order", "status" => ""));
$message_reports = $db->count("reports", array("content_type" => "message", "status" => ""));
$proposal_reports = $db->count("reports", array("content_type" => "proposal", "status" => ""));
$job_reports = $db->count("reports", array("content_type" => "buyer_requests", "status" => ""));
$view_offers_reports = $db->count("reports", array("content_type" => "view_offers", "status" => ""));
$user_reports = $db->count("reports", array("content_type" => "user", "status" => ""));

$proposal_del_requests = $db->count("proposals", array("proposal_status" => "deleted"));

$total_reports = $order_reports + $message_reports + $proposal_reports + $job_reports + $view_offers_reports + $user_reports;

// Seller Profile Update
$qSellerProfileCount = $db->query("SELECT * FROM sellers_profile_tmp WHERE status = 0 GROUP BY seller_id");
$countSellerProfileReq = $qSellerProfileCount->rowCount();

$qSellerProCount = $db->query("SELECT * FROM seller_pro_info WHERE status = 0 GROUP BY seller_id");
$countSellerProReq = $qSellerProCount->rowCount();

$totalSellerProfilesUpdateReq = $countSellerProReq + $countSellerProfileReq;

// feedbacks
$qFeedbacks = $db->query("SELECT ideas.id, COUNT(DISTINCT comments.id) totalComments FROM ideas LEFT JOIN comments ON ideas.id = comments.idea_id GROUP BY ideas.id;");
$totalPendingFeedback = 0;
if ($qFeedbacks->rowCount() > 0) {
	while ($oFeedbacks = $qFeedbacks->fetch()) {
		if ($oFeedbacks->totalComments == 0) {
			$totalPendingFeedback++;
		}
	}
}
function autoLoader($className)
{
	require_once("../functions/$className.php");
}
spl_autoload_register('autoLoader');

$core = new Core;
$paymentGateway = $core->checkPlugin("paymentGateway");
$videoPlugin = $core->checkPlugin("videoPlugin");
$notifierPlugin = $core->checkPlugin("notifierPlugin");

if ($notifierPlugin == 1) {
	require_once("$dir/plugins/notifierPlugin/functions.php");
}

?>
<!DOCTYPE html>
<html class="no-js">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Admin Panel - Control Your Entire Site.</title>
	<meta name="description" content="hiremyprofile">
	<meta name="author" content="https://github.com/jaygaha">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" href="apple-icon.png">
	<link rel="stylesheet" href="assets/css/normalize.css">
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link href="../styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/themify-icons.css">
	<link rel="stylesheet" href="assets/css/flag-icon.min.css">
	<link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
	<link rel="stylesheet" href="assets/scss/style.css">
	<style>
		.d-none-on-backend-precessing {
			display: table !important;
		}

		.right-panel {
			width: 80% !important;
		}

		.position_fixed_background {
			/* position:fixed; */
			/* display: flex; */
		}

		/* .nav-bar-head-ler{
			position: fixed !important;
			z-index: 2222;
			background-color: #272c33;
			padding:0 18px 0 10px !important;
			margin:0 -5px !important;
		} */
		.pos-iti-onf-xed {
			/* position:fixed; */
			/* border: 2px solid green; */
		}
	</style>
	<?php if (!empty($site_favicon)) { ?>
		<link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
	<?php } ?>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="assets/css/sweat_alert.css">
	<script type="text/javascript" src="assets/js/ie.js"></script>
	<script type="text/javascript" src="assets/js/sweat_alert.js"></script>
	<script src="../js/jquery.min.js"></script>
	<script>
		function alert_error(text) {
			Swal('', text, 'error');
		}

		function alert_success(text, url) {
			swal({
				type: 'success',
				timer: 3000,
				text: text,
				onOpen: function() {
					swal.showLoading()
				}
			}).then(function() {
				if (url != "") {
					window.open(url, '_self');
				}
			});
		}

		function alert_error(text, url) {
			swal({
				type: 'error',
				timer: 3000,
				text: text,
				onOpen: function() {
					swal.showLoading()
				}
			}).then(function() {
				if (url != "") {
					window.open(url, '_self');
				}
			});
		}

		function alert_confirm(text, url) {
			swal({
				text: text,
				type: 'warning',
				showCancelButton: true
			}).then((result) => {
				if (result.value) {
					if (url != "") {
						window.open(url, '_self');
					}
				}
			});
		}
	</script>
</head>

<body>

	<script id="minimal" src="assets/js/minimal.js"></script>

	<!-- <script id="minimal" src="assets/js/minimal.js" data-purchase-code="<?= $purchase_code; ?>" data-license="<?= $license_type; ?>"data-website="<?= $website; ?>"></script> -->

	<!-- Left Panel -->
	<div class="position_fixed_background">
		<aside id="left-panel" class="d-none-on-backend-precessing left-panel">
			<nav class="navbar navbar-expand-sm navbar-default pos-iti-onf-xed" id="scrolling">
				<div class="navbar-header">
					<button class="navbar-toggler nav-bar-head-ler" type="button" data-toggle="collapse" data-target="#main-menu">
						<i class="fa fa-bars"></i>
					</button>
					<a class="navbar-brand nav-bar-head-ler" href="index?dashboard">
						<?= $site_name; ?> <span class="badge badge-success p-2 font-weight-bold">ADMIN</span>
					</a>
					<a class="navbar-brand hidden nav-bar-head-ler" href="./"><span class="badge badge-success pt-2 pb-2">A</span></a>
				</div>
				<div id="main-menu" class="main-menu collapse navbar-collapse">
					<?php include("includes/sidebar.php"); ?>
				</div>
			</nav>
			<div class="container clearfix">
		</aside>


		<!-- Left Panel -->

		<!-- Right Panel -->
		<div id="right-panel" class="right-panel">

			<!-- Header-->
			<header id="header" class="header header-stycky-position">
				<div class="header-menu"><?php include("includes/admin_header.php"); ?></div>
			</header>
			<!-- Header-->

			<?php

			if ((empty($purchase_code) or empty($license_type) or empty($website)) and is_localhost() == false) {
				include("proceed.php");
			} else {

				$check_purchase = check_purchase();
				if ($check_purchase == 0 and is_localhost() == false) {
					include("proceed.php");
				} else {
					include("includes/body.php");
				}
			}

			?>

			<style>
				.d-none-on-backend-precessing {
					display: table-cell !important;
				}
			</style>

			<div class="container clearfix">
				<!--- container clearfix Starts --->
				<div class="row">
					<!--- row Starts --->
					<div id="languagePanel" class="bg-light col-md-12 p-2 pb-0 mzb-0">
						<!--- languagePanel Starts --->
						<div class="row">
							<div class="col-md-6">
								<!--- col-md-6 Starts --->
								<p class="col-form-label font-weight-normal mb-0 pb-0">
									Current Selected Language: <strong><?= $currentLanguage; ?></strong>
								</p>
							</div>
							<!--- col-md-6 Ends --->
							<div class="col-md-6 float-right">
								<!--- col-md-6 Starts --->
								<div class="form-group row mb-0 pb-0">
									<!--- form-group row Starts --->
									<label class="col-md-2"></label>
									<label class="col-md-4 col-form-label"> Change Language: </label>
									<div class="col-md-6">
										<select id="languageSelect" class="form-control">
											<?php
											$get_languages = $db->select("languages");
											while ($row_languages = $get_languages->fetch()) {
												$id = $row_languages->id;
												$title = $row_languages->title;
											?>
												<option data-url="<?= "$site_url/admin/index?change_language=$id"; ?>" <?= ($id == $_SESSION["adminLanguage"]) ? "selected" : ""; ?>>
													<?= $title; ?>
												</option>
											<?php } ?>
										</select>
									</div>
								</div>
								<!--- form-group row Ends --->
							</div>
							<!--- col-md-6 Ends -->
						</div>
					</div>
					<!--- languagePanel Ends --->
				</div>
				<!--- row Ends --->
			</div>
			<!--- container clearfix Ends --->

			<br><br><br>
			<script>
				$(document).ready(function() {
					$("#languageSelect").change(function() {
						var url = $("#languageSelect option:selected").data("url");
						window.location.href = url;
					});
				});

				// scrolling script

				function scrollingOver() {
					let scrolling = document.querySelector("#scrolling");

				}
			</script>

		</div><!-- Right Panel -->

	</div>
	<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
	<script>
		CKEDITOR.replace('editor1');
	</script>
	<script src="assets/js/popper.min.js"></script>
	<script src="assets/js/plugins.js"></script>
</body>

</html>
<?php
error_reporting(0);
session_start();
require_once("../includes/db.php");

// if (isset($_SESSION['seller_user_name'])) {
// 	echo "<script>window.open('../login','_self')</script>";
// }
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
// data for remainder email
$remainder_alert = $row_login_seller->remainder_alert;
$seller_verification = $row_login_seller->seller_verification;

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$min_proposal_price = $row_payment_settings->min_proposal_price;

$countProposals = $db->count("proposals", ['proposal_seller_id' => $login_seller_id]);
if (isset($_POST['seller_verification_btn_form'])) {
	$remainder_alert = $_POST['remainder_value'];

	// Correct the update query to target only the current seller
	$putverify = $db->update("sellers", array(
		"remainder_alert" => $remainder_alert
	), array(
		"seller_user_name" => $login_seller_user_name  // Ensure the correct seller is targeted
	));

	if ($putverify) {
		var_dump("successfully updated");
		exit;
	} else {
		var_dump("decline update");
		exit;
	}
	
}




if (isset($_POST['submit'])) {
	$rules = array(
		"request_title" => "required",
		"request_description" => "required",
		"cat_id" => "required",
		"child_id" => "required",
		"sub_child_id" => "required",

		"request_budget" => "number|required",
		"delivery_time" => "required"
	);
	$messages = array("cat_id" => "you need to select a category", "child_id" => "you need to select a child category", "sub_child_id" => "you need to select a sub child category");
	$val = new Validator($_POST, $rules, $messages);
	if ($val->run() == false) {
		Flash::add("form_errors", $val->get_all_errors());
		Flash::add("form_data", $_POST);
		echo "<script> window.open('post_request','_self');</script>";
	} else {
		$request_title = $input->post('request_title');
		$request_description = $input->post('request_description');
		$cat_id = $input->post('cat_id');
		$child_id = $input->post('child_id');
		$sub_child_id = $input->post('sub_child_id');
		$request_budget = $input->post('request_budget');
		$delivery_time = $input->post('delivery_time');
		$request_file = $_FILES['request_file']['name'];
		$request_file_tmp = $_FILES['request_file']['tmp_name'];
		$request_date = date("F, d, Y");
		$allowed = array('jpeg', 'jpg', 'gif', 'png', 'tif', 'avi', 'mpeg', 'mpg', 'mov', 'rm', '3gp', 'flv', 'mp4', 'zip', 'rar', 'mp3', 'wav', 'pdf', 'docx', 'txt');
		$file_extension = pathinfo($request_file, PATHINFO_EXTENSION);

		if (!empty($request_file)) {
			if (!in_array($file_extension, $allowed)) {
				echo "<script>alert('{$lang['alert']['extension_not_supported']}')</script>";
				echo "<script>window.open('post_request','_self')</script>";
				exit();
			}
			$request_file = pathinfo($request_file, PATHINFO_FILENAME);
			$request_file = $request_file . "_" . time() . ".$file_extension";
			uploadToS3("request_files/$request_file", $request_file_tmp);


			// Get the file size
			// $file_size = filesize($request_file_tmp);
			// echo "Uploaded file size: " . $file_size . " bytes";
		}
		$isS3 = $enable_s3;

		$insert_request = $db->insert("buyer_requests", array("seller_id" => $login_seller_id, "cat_id" => $cat_id, "child_id" => $child_id, "sub_child_id" => $sub_child_id, "request_title" => $request_title, "request_description" => $request_description, "request_file" => $request_file, "delivery_time" => $delivery_time, "request_budget" => $request_budget, "request_date" => $request_date, "isS3" => $isS3, "request_status" => 'pending'));
	}
}

?>

<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
	<title><?= $site_name; ?> - <?= $lang["titles"]["post_request"]; ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= $site_desc; ?>">
	<meta name="keywords" content="<?= $site_keywords; ?>">
	<meta name="author" content="<?= $site_author; ?>">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
	<link href="../styles/bootstrap.css" rel="stylesheet">
	<link href="../styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
	<link href="../styles/styles.css" rel="stylesheet">
	<link href="../styles/user_nav_styles.css" rel="stylesheet">
	<link href="../font_awesome/css/font-awesome.css" rel="stylesheet">
	<link href="../styles/sweat_alert.css" rel="stylesheet">
	<link href="../styles/animate.css" rel="stylesheet">
	<!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
	<script src="../js/ie.js"></script>
	<script type="text/javascript" src="../js/sweat_alert.js"></script>
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<?php if (!empty($site_favicon)) { ?>
		<link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
	<?php } ?>

	<style>
		.display-flex {
			/* border:1px solid green; */
			width: 95%;
			/* display: flex; */
			/* float: right; */
			margin: auto;
		}

		.display-flex input {
			width: 100%;
			margin: auto;
			margin-top: 8px;
			/* box-shadow: 2px 2px 5px black, inset 0px 0px 15px #0a79f7; */
		}

		.submit-button {
			border: 1px solid #00c8d4;
			background-color: #00c8d4;
			color: white;
			padding: 13px 20px;
			text-align: center;
			margin: auto;
			font-size: 15px;
			font-weight: 500;
			border-radius: 3px;
		}

		.taxt-blue {
			/* border: 2px solid green; */
			/* color: #126e6b; */
			/* margin-top: -9.5rem; */
			margin-left: 18px
				/* text-align: center; */
		}

		.margin-l {
			margin-left: 1px;
			/* border:2px solid green; */
		}

		.box-shadow1 {
			/* box-shadow:0px 0px 5px black, inset 0px 0px 50px lightseagreen; */
		}

		.padding-top1 {
			/* padding:48px 0px 0px 15px; */
			/* margin:auto; */
		}

		.padding-top2 {
			/* padding:35px 0px 0px 20px; */
			/* margin:auto; */
		}

		.box-shadow-post-req {
			/* box-shadow:0px 0px 3px black, inset 0px 0px 15px #90979e; */
		}

		.margin-top-30 {
			margin-top: 30px;
		}

		#custom_delivery_time {
			width: 40%;
			padding: 5px 8px;
			border-radius: 3px;
			border: 1px solid lightgray;
		}

		#delevery_days_badge {
			display: none;
			padding: 5px;
		}

		@media (max-width:768px) {


			.form-curb input {
				width: 100%;
			}

			.w-001-df {
				width: 100%;
				display: flex;
			}

			.w-001-dfi {
				width: 100%;
				/* display: flex; */
			}

			.w-001-dfa {
				width: 100%;
				display: flex;
			}

			.padding-alter3 {
				/* padding:0px 10px; */
				/* margin-top: 1px !important; */
			}

			.pl-zero {
				padding-left: 0px !important;
			}

			.font-size-14px {
				font-size: 11px;
				font-weight: 300;
				padding: 4px;
				/* color: #12adb5 !important; */
				font-style: italic;

			}

			.form-group input::placeholder {
				font-size: 13px;
				font-weight: 400;
				color: lightgray;
			}

			.form-group textarea::placeholder {
				font-size: 13px;
				font-weight: 400;
				color: lightgray;
			}

			.form-group input {
				/* border:1px solid green; */
				font-size: 13px;
				font-weight: 400;
			}

			.heading-5 {
				margin-bottom: 20px !important;
			}

			#category {
				font-size: 13px;
				font-weight: 400;
				padding-left: 5px;
				padding-bottom: 7px;
				/* border:1px solid red; */
				height: 40px;
				color: gray;
			}

			.text-align-center {
				text-align: center;
			}

			.font-18px {
				font-size: 16px;
				font-weight: 400;
				/* color: #126e6b; */
			}

			.display-none {
				/* display: none; */
				/* border:1px solid green !important; */
			}

			.form-curb input::placeholder {
				/* border: 2px solid green; */
				color: lightgray;
				font-size: 13px;
				font-weight: 400;
			}

			.taxt-blue {
				/* color: #126e6b; */
				/* margin-top: 3rem; */
				text-align: center;
			}

			.submit-button {
				border: 1px solid #00c8d4;
				background-color: #00c8d4;
				color: white;
				padding: 10px 20px;
				text-align: center;
				margin: auto;
				font-size: 15px;
				font-weight: 500;
				border-radius: 3px;
			}

			.display-flex {
				/* border:1px solid green; */
				width: 100%;
				display: flex;
				margin: auto;
			}

			.display-flex a {
				width: 100%;
				margin: auto;
				margin-top: 8px;
				/* box-shadow: 1px 1px 5px black; */
			}

			.display-flex input {
				width: 100%;
				margin: auto;
				margin-top: 8px;
				/* box-shadow: 1px 1px 5px black; */
			}

			.b-margin {
				margin-bottom: 22px;
				/* border:2px solid green; */
			}
		}

		@media(min-width:769px) {
			.padding-top-bottom-2 {
				padding: 25px 0;
			}

			.padding-left-3 {
				padding-left: 60px !important;
			}

			.padding-x-axis {
				padding: 0 32px 0 0 !important;
			}

			.form-curb input {
				width: 100% !important;
			}

			.w-001-df {
				width: 100%;
				display: flex;
			}

			.w-001-dfi {
				width: 100%;
				/* display: flex; */
			}

			.w-001-dfa {
				width: 100%;
				display: flex;
			}
		}

		.display-sub-none {
			display: none;
		}

		.display-sub-sub-none {
			display: none;
		}
	</style>
</head>

<body class="is-responsive">
	<?php
	if (isset($insert_request) && $insert_request) {
		echo "<script>
			swal({
			  type: 'success',
			  text: 'Your request has been submitted successfully!',
			  timer: 3000,
			  onOpen: function(){
				  swal.showLoading()
			  }
			}).then(function(){
				  window.open('manage_requests.php','_self');
			});
		</script>";
	}


	// if ($seller_verification == "ok") { 
	// 	if ( $totalWeight < 70) { 
	// 		echo "
	// 	<div class='container-fluid py-5'>
	// 		<div class='alert alert-danger rounded-0 mt-0 text-center'>
	// 		In other to add, Please complete your profile and professional info first.
	// 		</div>
	// 	</div>
	// 	";
	// 	} else {
	// 	echo "
	// 	<div class='container-fluid py-5'>
	// 		<div class='alert alert-danger rounded-0 mt-0 text-center'>
	// 		Please confirm your email to use this feature.
	// 		</div>
	// 	</div>
	// 	";
	// 	}
	//} else { 
	?>


	<?php
	require_once("../includes/user_header.php");
	?>
	<div class="container-fluid py-1 px-5">
		<h1 class="mb-4 taxt-blue padding-alter3 margin-top-30">
			<i class="fa fa-plus-circle" aria-hidden="true">
			</i>
			<?= $lang["titles"]["post_request"]; ?>
		</h1>
		<div class="row margin-l padding-alter3">
			<!--- row Starts --->
			<div class="col-xl-8 col-lg-8 post-request col-md-12 pl-zero">
				<?php
				$form_errors = Flash::render("form_errors");
				$form_data = Flash::render("form_data");
				if (is_array($form_errors)) {
				?>
					<div class="alert alert-danger">
						<!--- alert alert-danger Starts --->
						<ul>
							<?php $i = 0;
							foreach ($form_errors as $error) {
								$i++; ?>
								<li>
									<?= $i ?>.
									<?= ucfirst($error); ?>
								</li>
							<?php } ?>
						</ul>
					</div>
					<!--- alert alert-danger Ends --->
				<?php } ?>
				<div class="card rounded-0 mb-5 box-shadow1">
					<div class="card-body padding-top-bottom-2">
						<form method="post" enctype="multipart/form-data" name="jobForm" onsubmit="return validateForm()">
							<div class="row row-1 padding-top1">
								<div class="col-md-2 d-md-block d-none">
									<!--<i class="fa fa-pencil-square-o fa-4x text-success"></i>-->
									<img style="position:relative; top: -12px; left:15px;" width="64" src="../images/comp/book.png">
								</div>
								<div class="col-md-8 col-sm-12">
									<div class="row">
										<div class="col-xl-12 col-lg-12 col-md-12 px-1">
											<div class="form-group">
												<!--SESSION START FOR REQUEST TITLE START -->
												<?php if (isset($_SESSION['seller_user_name'])) { ?>
													<input type="text" name="request_title" id="request_title" placeholder="<?= $lang['placeholder']['request_title']; ?>" class="form-control input-lg box-shadow-post-req" required="" value="<?php isset($form_data['request_title']) ? $form_data['request_title'] : "";  ?>" minlength="30" maxlength="150">
												<?php } else { ?>
													<a href="#" data-toggle="modal" data-target="#login-modal">
														<input type="text" name="" id="" placeholder="<?= $lang['placeholder']['request_title']; ?>" class="form-control input-lg box-shadow-post-req" required="" minlength="30" maxlength="150">
													</a>
												<?php } ?>
												<!--SESSION START FOR REQUEST TITLE END -->
												<span class="text-dark d-block font-size-14px">min: 30 max: 150 characters <span class="pull-right"><i class="text-danger" id="title-typed-characters">0</i> characters</span></span>
											</div>
											<div class="form-group">
												<!--SESSION START FOR REQUEST DESC START -->
												<?php if (isset($_SESSION['seller_user_name'])) { ?>
													<textarea name="request_description" id="request_description" rows="5" cols="73" maxlength="2000" class="form-control box-shadow-post-req" placeholder="<?= $lang['placeholder']['request_desc']; ?>" required="" minlength="50" maxlength="2000"><?php if (isset($form_data['request_description'])) {
																																																																											echo $form_data['request_description'];
																																																																										} ?></textarea>
												<?php } else { ?>
													<a href="#" data-toggle="modal" data-target="#login-modal">
														<textarea name="request_description" id="request_description" rows="5" cols="73" maxlength="2000" class="form-control box-shadow-post-req" placeholder="<?= $lang['placeholder']['request_desc']; ?>" required="" minlength="50" maxlength="2000"><?php if (isset($form_data['request_description'])) {
																																																																												echo $form_data['request_description'];
																																																																											} ?></textarea>
													</a>
												<?php } ?>
												<!-- SESSION START FOR REQUEST DESC END-->
												<span class="text-dark d-block font-size-14px">min: 50 max: 2000 characters <span class="pull-right"><i class="text-danger" id="request_description-typed-characters">0</i> characters</span></span>
											</div>
											<div class="form-group">
												<!--SESSION START FOR REQUEST FILE START -->
												<?php if (isset($_SESSION['seller_user_name'])) { ?>
													<input type="file" name="request_file" id="file">
													<p id="fileInfo"></p>

												<?php } else { ?>
													<a href="#" data-toggle="modal" data-target="#login-modal">
														<input type="file" name="request_file" id="file">
													</a>
												<?php } ?>
												<!--SESSION START FOR REQUEST FILE END -->
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php if (isset($_SESSION['seller_user_name'])) { ?>
								<div class="row-2  padding-top2">
									<hr class="card-hr">
									<h5 class="mb-5 ml-3 heading-5 text-align-center font-18px"><?= $lang['post_request']['heading_1']; ?></h5>
									<div class="row mb-2 ">
										<div class="col-md-2 d-md-block d-none ">
											<!-- <i class="fa fa-folder-open fa-4x text-success"></i> -->
											<img style="position:relative; top: -12px; left:15px;" src="../images/comp/folder.png">
										</div>

										<div class="col-md-8 col-sm-12">
											<div class="row">
												<div class="col-xl-4 col-md-6 px-1 mb-2">
													<select class="form-control box-shadow-post-req" name="cat_id" id="category" required="">
														<option value="" class="hidden">
															<?= $lang['placeholder']['select_category']; ?>
														</option>
														<?php
														$get_cats = $db->select("categories");
														while ($row_cats = $get_cats->fetch()) {
															$cat_id = $row_cats->cat_id;
															$get_meta = $db->select("cats_meta", ["cat_id" => $cat_id, "language_id" => $siteLanguage]);
															$row_meta = $get_meta->fetch();
															$cat_title = $row_meta->cat_title;
														?>
															<option value="<?= $cat_id; ?>">
																<?= $cat_title; ?>
															</option>
														<?php } ?>
													</select>
												</div>

												<div class="col-xl-4 col-md-6 px-1 mb-2 display-sub-none">
													<select class="form-control box-shadow-post-req" name="child_id" id="sub-category" required="">
														<option value="" class="hidden">
															<?= $lang['placeholder']['select_sub_category']; ?>
														</option>
													</select>
												</div>

												<div class="col-xl-4 col-md-6 px-1 mb-2 display-sub-sub-none">
													<select class="form-control box-shadow-post-req" name="sub_child_id" id="sub-sub-category" required="">
														<option value="" class="hidden">
															<?= $lang['placeholder']['select_sub_sub_category']; ?>
														</option>
													</select>
												</div>

												<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
												<script>
													$(document).ready(function() {
														$('#category').change(function() {
															var cat_id = $(this).val();
															$('.display-sub-none').hide();
															$('.display-sub-sub-none').hide();

															$.ajax({
																url: 'get_subcategories',
																method: 'POST',
																data: {
																	cat_id: cat_id
																},
																success: function(response) {
																	$('.display-sub-none').show();
																	$('.display-sub-sub-none').hide();
																	$('#sub-category').html(response);
																	$('#sub-sub-category').html('<option value="" class="hidden"><?= $lang['placeholder']['select_sub_sub_category']; ?></option>');

																}
															});
														});

														$('#sub-category').change(function() {
															var sub_cat_id = $(this).val();
															$.ajax({
																url: 'get_sub_subcategories',
																method: 'POST',
																data: {
																	sub_cat_id: sub_cat_id
																},
																success: function(response) {
																	$('#sub-sub-category').html(response);
																	$('.display-sub-none').show();
																	$('.display-sub-sub-none').show();
																}
															});
														});
													});
												</script>

											</div>
										</div>
									</div>
								</div>
							<?php } ?>
							<div class="row-3 padding-top2">
								<hr class="card-hr">
								<h5 class="mb-4 ml-3 text-align-center font-18px"> <?= $lang['post_request']['heading_2']; ?> </h5>
								<div class="row mb-4">
									<div class="col-md-1 d-md-block d-none display-none">
										<!--<i class="fa fa-clock-o fa-4x text-success "></i>-->
										<img style="position:relative; left:15px;" src="../images/comp/timetable.png">
									</div>
									<div class="col-md-11 col-sm-12 mt-3 padding-left-3">
										<?php
										if (isset($_SESSION['seller_user_name'])) {
											$get_delivery_times = $db->select("delivery_times");
											while ($row_delivery_times = $get_delivery_times->fetch()) {
												$delivery_proposal_title = $row_delivery_times->delivery_proposal_title;
										?>
												<!-- HTML -->
												<label class="custom-control custom-radio">
													<input type="radio" value="<?= $delivery_proposal_title; ?>" <?php if (isset($form_data['delivery_time']) && $form_data['delivery_time'] == $delivery_proposal_title) {
																														echo "checked";
																													} ?> name="delivery_time" class="custom-control-input">
													<span class="custom-control-indicator"></span>
													<span class="custom-control-description"><?= $delivery_proposal_title; ?></span>
												</label>
											<?php } ?>
											<label class="custom-control custom-radio">
												<input type="radio" value="custom" <?php if (isset($form_data['delivery_time']) && $form_data['delivery_time'] == 'custom') {
																						echo "checked";
																					} ?> name="delivery_time" class="custom-control-input" id="custom_radio">
												<span class="custom-control-indicator"></span>
												<span class="custom-control-description">Custom delivery time</span>
											</label>
											<div class="d-flex w-100">
												<input type="number" oninput="if(this.value == 0) this.value = ''; if(this.value.length > 3) this.value = this.value.slice(0, 3);" name="custom_delivery_time" id="custom_delivery_time" placeholder="Enter custom delivery time (in days)" <?php if (!(isset($form_data['delivery_time']) && $form_data['delivery_time'] == 'custom')) {
																																												echo 'style="display:none;"';
																																											} ?>>
												<span id="delevery_days_badge" style="<?php if (!(isset($form_data['delivery_time']) && $form_data['delivery_time'] == 'custom')) {
																							echo 'display:none;';
																						} ?>">days</span>
											</div>

											<script>
												document.addEventListener('DOMContentLoaded', function() {
													const customRadio = document.getElementById('custom_radio');
													const customInput = document.getElementById('custom_delivery_time');
													const customInputBadge = document.getElementById('delevery_days_badge');
													const radioButtons = document.querySelectorAll('input[name="delivery_time"]');

													function handleCustomInputVisibility() {
														if (customRadio.checked) {
															customInput.style.display = 'block';
															customInputBadge.style.display = 'inline-block'; // Use inline-block for badge
														} else {
															customInput.style.display = 'none';
															customInputBadge.style.display = 'none';
															customInput.value = ''; // Clear the custom input when not selected
														}
													}

													customRadio.addEventListener('change', handleCustomInputVisibility);

													radioButtons.forEach(function(radio) {
														if (radio !== customRadio) {
															radio.addEventListener('change', handleCustomInputVisibility);
														}
													});

													// Update delivery_time field value on form submission
													document.forms['jobForm'].addEventListener('submit', function() {
														if (customRadio.checked) {
															// Set the delivery_time field to the custom input value
															document.querySelector('input[name="delivery_time"][value="custom"]').value = customInput.value.trim();
														} else {
															// If another option is selected, clear the custom value
															document.querySelector('input[name="delivery_time"][value="custom"]').value = 'custom';
														}
													});

													// Initialize custom input visibility on page load
													handleCustomInputVisibility();
												});
											</script>

											<?php } else {
											$get_delivery_times = $db->select("delivery_times");
											while ($row_delivery_times = $get_delivery_times->fetch()) {
												$delivery_proposal_title = $row_delivery_times->delivery_proposal_title;
											?>
												<a href="#" data-toggle="modal" data-target="#login-modal">
													<label class="custom-control custom-radio">
														<input type="radio" value="<?= $delivery_proposal_title; ?>" <?php if (isset($form_data['delivery_time']) and $form_data['delivery_time'] == $delivery_proposal_title) {
																															echo "checked";
																														} ?> name="delivery_time" class="custom-control-input" required="">
														<span class="custom-control-indicator"></span>
														<span class="custom-control-description"><?= $delivery_proposal_title; ?></span>
													</label>
												</a>
											<?php } ?>
										<?php } ?>
									</div>
								</div>



							</div>
							<div class="row-4 b-margin padding-top2">
								<hr class="card-hr">
								<h5 class="mb-4 ml-3 text-align-center font-18px"> <?= $lang['post_request']['heading_3']; ?></h5>
								<div class="row">
									<div class="col-md-1 d-md-block d-none">
										<!--<i class="fa fa-money fa-4x text-success fa-work"></i>-->
										<img style="position:relative; left:15px;" src="../images/comp/budget.png">
									</div>
									<div class="col-md-10 col-sm-12 col-xl-8 offset-md-1 mt-3 px-1">
										<div class="input-group form-curb w-001-df">
											<span class="input-group-addon font-weight-bold box-shadow-post-req">
												<?= $s_currency; ?>
											</span>
											<?php if (isset($_SESSION['seller_user_name'])) { ?>
												<input type="number" oninput="if(this.value < 5) this.value = ''; if(this.value.length > 6) this.value = this.value.slice(0, 6);" name="request_budget" min="<?= $min_proposal_price; ?>" placeholder="<?= $lang['placeholder']['5_minimum']; ?>" class="form-control input-lg box-shadow-post-req w-001-dfi" value="<?= isset($form_data['request_budget']) ? $form_data['request_budget'] : null;  ?>" required="" oninput="checkValidity(this);">
											<?php } else { ?>
												<a href="#" data-toggle="modal" data-target="#login-modal" class="w-001-dfa">
													<input type="number" name="request_budget" min="<?= $min_proposal_price; ?>" placeholder="<?= $lang['placeholder']['5_minimum']; ?>" class="form-control input-lg box-shadow-post-req w-001-dfi" value="<?= isset($form_data['request_budget']) ? $form_data['request_budget'] : null;  ?>" required="" oninput="checkValidity(this);">
												</a>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
							<hr class="card-hr">
							<div class="display-flex">
								<?php if (isset($_SESSION['seller_user_name'])) { ?>
									<input class="submit-button" type="submit" name="submit" value="<?= $lang['button']['submit_request']; ?>" style="cursor:pointer;" class="btn btn-success btn-lg float-right">
								<?php } else { ?>
									<a href="#" data-toggle="modal" data-target="#login-modal">
										<input class="submit-button" type="submit" name="submit" value="<?= $lang['button']['submit_request']; ?>" style="cursor:pointer;" class="btn btn-success btn-lg float-right">
									</a>
								<?php } ?>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-lg-4 col-md-4 request-sidebar">
				<!--- col-xl-3 col-md-2 request-sidebar Starts --->
				<?php include("../includes/request_sidebar.php"); ?>
			</div>
			<!--- col-xl-3 col-md-2 request-sidebar Ends --->
		</div>
		<!--- row Ends --->
		<?php if ($remainder_alert == 0 && $seller_verification == "ok") { ?>
			<h3 style="margin-top: 160px;">form</h3>
			<?= var_dump($seller_user_name); ?>
			<?= var_dump($login_seller_user_name); ?>
			<form action="" method="post">
				<input type="text" name="remainder_value" id="" value="1">
				<button type="submit" name="seller_verification_btn_form">submit</button>
			</form>
		<?php } ?>
	</div>
	<!--- container-fluid Ends --->
	<?php //} 
	?>
	<script>
		const textAreaElement = document.querySelector("#request_title");
		const typedCharactersElement = document.querySelector("#title-typed-characters");
		const maximumCharacters = 150;

		textAreaElement.addEventListener("input", (event) => {
			const typedCharacters = textAreaElement.value.length;
			if (typedCharacters > maximumCharacters) {
				return false;
			}
			typedCharactersElement.textContent = typedCharacters;
		});

		const descTextAreaElement = document.querySelector("#request_description");
		const descTypedCharactersElement = document.querySelector("#request_description-typed-characters");
		const descMaximumCharacters = 2000;

		descTextAreaElement.addEventListener("input", (event) => {
			const descTypedCharacters = descTextAreaElement.value.length;
			if (descTypedCharacters > descMaximumCharacters) {
				return false;
			}
			descTypedCharactersElement.textContent = descTypedCharacters;
		});

		function wordCount(field, minWord = 50) {
			var number = 0;

			// Split the value of input by
			// space to count the words
			var matches = $(field).val().split(" ");

			// Count number of words
			number = matches.filter(function(word) {
				return word.length > 0;
			}).length;

			// Final number of words
			$(".descCount").text(number);
		}

		function validateForm() {
			return true
		}

		$(document).ready(function() {
			$('.h-2').css("visibility", "hidden");
			$('.h-3').css("visibility", "hidden");
			$('.h-4').css("visibility", "hidden");

			$('.container-fluid').hover(function() {
				$('.h-1').css("visibility", "visible");
				$('.h-2').css("visibility", "hidden");
				$('.h-3').css("visibility", "hidden");
				$('.h-4').css("visibility", "hidden");
			});

			$('.row-1').mouseover(function() {
				$('.h-1').css("visibility", "visible");
				$('.h-2').css("visibility", "hidden");
				$('.h-3').css("visibility", "hidden");
				$('.h-4').css("visibility", "hidden");
			});

			$('.row-2').mouseover(function() {
				$('.h-1').css("visibility", "hidden");
				$('.h-2').css("visibility", "visible");
				$('.h-3').css("visibility", "hidden");
				$('.h-4').css("visibility", "hidden");
			});

			$('.row-3').mouseover(function() {
				$('.h-1').css("visibility", "hidden");
				$('.h-2').css("visibility", "hidden");
				$('.h-3').css("visibility", "visible");
				$('.h-4').css("visibility", "hidden");
			});

			$('.row-4').mouseover(function() {
				$('.h-1').css("visibility", "hidden");
				$('.h-2').css("visibility", "hidden");
				$('.h-3').css("visibility", "hidden");
				$('.h-4').css("visibility", "visible");
			});

			$('.row-2,.row-3,.row-4').mouseout(function() {
				$('.h-1').css("visibility", "visible");
				$('.h-2').css("visibility", "hidden");
				$('.h-3').css("visibility", "hidden");
				$('.h-4').css("visibility", "hidden");
			});

			// $("textarea")
			// 	.each(function() {
			// 		var input = "#" + this.id;

			// 		// Count words when keyboard
			// 		// key is released
			// 		$(this).keyup(function() {
			// 			wordCount(input);
			// 		});
			// 	});
			// $("#textarea").keydown(function() {
			// 	var textarea = $("#textarea").val();
			// 	$(".descCount").text(textarea.length);
			// });

			// $("#sub-category").hide();

			// $("#category").change(function() {
			// 	$("#sub-category").show();
			// 	var category_id = $(this).val();
			// 	$.ajax({
			// 		url: "fetch_subcategory",
			// 		method: "POST",
			// 		data: {
			// 			category_id: category_id
			// 		},
			// 		success: function(data) {
			// 			$("#sub-category").html(data);
			// 		}
			// 	});
			// });

		});

		//           ./////////////////////////////////////////////////////

		document.getElementById('file').addEventListener('change', function() {
			var file = this.files[0];
			var fileInfo = "File type: " + file.type + "<br>" +
				"File size: " + file.size + " bytes";
			document.getElementById('fileInfo').innerHTML = fileInfo;
		});
	</script>

	<script>
		function checkValidity(input) {
			if (input.type === 'number') {
				var numberString = input.value.toString(); // Convert number to string
				if (numberString.length > 8) {
					// If the length exceeds 8, truncate the value
					input.value = parseFloat(numberString.slice(0, 8)); // Keep only first 8 digits
				}
			}
		}
	</script>



	<?php require_once("../includes/footer.php"); ?>
</body>

</html>
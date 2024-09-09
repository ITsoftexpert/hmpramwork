<?php
session_start();
require_once("includes/db.php");
if (!isset($_SESSION['seller_user_name'])) {
	echo "<script>window.open('login','_self')</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

if (isset($_GET['n_id'])) {

	$notification_id = $input->get('n_id');

	$get_notification = $db->select("notifications", array("notification_id" => $notification_id));

	if ($get_notification->rowCount() == 0) {
		echo "<script>window.open('dashboard','_self')</script>";
	} else {

		$row_notification = $get_notification->fetch();
		$order_id = $row_notification->order_id;
		$reason = $row_notification->reason;
		$update_notification = $db->update("notifications", array("status" => 'read'), array("notification_id" => $notification_id));
		if ($update_notification) {
			if ($reason == "modification" or $reason == "approved" or $reason == "declined") {
				echo "<script>window.open('proposals/view_proposals?{$reason}','_self')</script>";
			} elseif ($reason == "approved_request" or $reason == "unapproved_request" or $reason == "modification_request") {
				echo "<script>window.open('requests/manage_requests?tab={$reason}','_self');</script>";
			} elseif ($reason == "withdrawal_approved" or $reason == "withdrawal_declined") {
				echo "<script>window.open('withdrawal_requests?id=$order_id','_self');</script>";
			} elseif ($reason == "profile_modification" or $reason == "professional_modification" or $reason == "account_modification") {
				$lk = $reason == "profile_modification" ? "settings?profile_settings" : ($reason == "professional_modification" ? "settings?professional_settings" : "settings?account_settings");
				echo "<script>window.open('{$lk}','_self');</script>";
			} elseif ($reason == "profile_approved" or $reason == "professional_approved" or $reason == "account_approved") {
				$lk = $reason == "profile_approved" ? "settings?profile_settings" : ($reason == "professional_approved" ? "settings?professional_settings" : "settings?account_settings");
				echo "<script>window.open('{$lk}','_self');</script>";
			} else {
				echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";
			}
		}
	}
}

if (isset($_GET['delete'])) {
	$delete_id = $input->get('delete');
	$delete_notification = $db->delete("notifications", array('notification_id' => $delete_id, "receiver_id" => $login_seller_id));
	if ($delete_notification->rowCount() == 1) {
		echo "<script>alert('One notification has been deleted.')</script>";
		echo "<script>window.open('notifications','_self')</script>";
	} else {
		echo "<script>window.open('notifications','_self')</script>";
	}
}

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
	<title><?= $site_name; ?> - <?= $lang["titles"]["notifications"]; ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="description" content="<?= $site_desc; ?>">
	<meta name="keywords" content="<?= $site_keywords; ?>">
	<meta name="author" content="<?= $site_author; ?>">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
	<link href="styles/bootstrap.css" rel="stylesheet">
	<link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
	<link href="styles/styles.css" rel="stylesheet">
	<link href="styles/user_nav_styles.css" rel="stylesheet">
	<link href="font_awesome/css/font-awesome.css" rel="stylesheet">
	<link href="styles/animate.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<?php if (!empty($site_favicon)) { ?>
		<link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
	<?php } ?>

	<style>
		.alter-top-margin0{
			/* margin-top:-100px; */
			padding: 2rem;
		}
		@media (max-width:768px) {
			.full-width-margin {
				width: 100%;
				display: flex;
				margin-top: 2vh;
				margin-bottom: -2vh;
				/* color: #256156; */
				/* font-size: 20px !important; */
			}
			.alter-top-margin0{
			/* margin-top:-125px; */
			padding: 0;
		}
		
			.text-align-center {
				text-align: center;
				margin: auto;
			}

			.full-width-1 {
				width: 95%;
				margin-left: 0px !important;
				display: flex;
				/* color: #256156; */
				font-size: 20px !important;
			}

			.text-align-center-1 {
				text-align: center;
				margin: auto;
			}

			.font-size-3 {
				font-size: 13px !important;
				text-align: center;
				padding-left: 5px !important;
				padding-right: 5px !important;
			}

			.content-center {
				/* border: 1px solid green !important; */
				padding-top: 3vh !important;
			}

			.content-center-1 {
				/* border: 1px solid green !important; */
				padding-top: 3vh !important;
			}

			.content-center-2 {
				/* border: 1px solid green !important; */
				padding-top: 2.5vh !important;
			}

			.font-size {
				font-size: 14px !important;
			}

		
		}
	</style>
</head>

<body class="is-responsive">
	<?php require_once("includes/user_header.php"); ?>
	<div class="container-fluid">
		<div class="row alter-top-margin0">
			<div class="col-md-12">
				<h2 class="full-width-margin"><span class="text-align-center"><?= $lang["titles"]["notifications"]; ?></span></h2>
				<div class="table-responsive box-table mt-4">
					<h2 class="mt-3 mb-3 ml-3 full-width-1"> <span class="text-align-center-1"><?= $lang["notifications"]["all"]; ?> </span></h2>
					<table class="table table-bordered inbox-conversations">
						<thead>
							<tr>
								<th class="font-size-3"><?= $lang['th']['sender']; ?></th>
								<th class="font-size-3"><?= $lang['th']['message']; ?></th>
								<th class="font-size-3"><?= $lang['th']['date']; ?></th>
								<th class="font-size-3"><?= $lang['th']['delete']; ?></th>
							</tr>
						</thead>
						<tbody>
							<?php

							$select_notofications = $db->select("notifications", array("receiver_id" => $login_seller_id), "DESC");
							$count_all_notifications = $select_notofications->rowCount();
							while ($row_notifications = $select_notofications->fetch()) {
								$notification_id = $row_notifications->notification_id;
								$sender_id = $row_notifications->sender_id;
								$order_id = $row_notifications->order_id;
								$reason = $row_notifications->reason;
								$date = $row_notifications->date;
								$status = $row_notifications->status;

								// Select Sender Details
								$select_sender = $db->select("sellers", array("seller_id" => $sender_id));
								$row_sender = $select_sender->fetch();
								$sender_user_name = @$row_sender->seller_user_name;
								$sender_image = @getImageUrl2("sellers", "seller_image", $row_sender->seller_image);

								if (strpos($sender_id, 'admin') !== false) {
									$admin_id = trim($sender_id, "admin_");
									$get_admin = $db->select("admins", array("admin_id" => $admin_id));
									$sender_user_name = "Admin";
									@$sender_image = @getImageUrl("admins", $get_admin->fetch()->admin_image);
								}

							?>
								<tr class="<?php if ($status == 'unread') {
												echo 'table-active';
											} ?>">
									<td class="inbox-seller content-center-1 font-size">
										<?php if (!empty($sender_image)) { ?>
											<?php if (strpos($sender_id, "admin_") !== false) { ?>
												<img src="<?= $sender_image; ?>" class="rounded-circle">
											<?php } else { ?>
												<img src="<?= $sender_image; ?>" class="rounded-circle">
											<?php } ?>
										<?php } else { ?>
											<img src="user_images/empty-image.png" class="rounded-circle">
										<?php } ?>
										<h6 class="mb-4 content-center-2">
											<a href="<?= $site_url; ?>/notifications?n_id=<?= $notification_id; ?>"><?= $sender_user_name; ?></a>
										</h6>
									</td>
									<td width="400" class="font-size">
										<a href="<?= $site_url; ?>/notifications?n_id=<?= $notification_id; ?>">
											<?= include("includes/comp/notification_reasons.php"); ?>
										</a>
								 	</td>
									<td> <?= $date; ?> </td>
									<td class="content-center">
										<a href="notifications?delete=<?= $notification_id; ?>" class="text-white btn btn-danger">
											<i class="fa fa-trash-o"></i>
										</a>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php
					if ($count_all_notifications == 0) {
						echo "
        	<center>
      		<h3 class='pt-4 pb-4'> <i class='fa fa-meh-o'></i> {$lang['notifications']['no_notifications']} </h3>
        	</center>";
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<?php require_once("includes/footer.php"); ?>
</body>

</html>
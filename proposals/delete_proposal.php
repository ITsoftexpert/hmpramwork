<?php
session_start();
require_once("../includes/db.php");
if (!isset($_SESSION['seller_user_name'])) {
	echo "<script>window.open('../login','_self')</script>";
}
if (isset($_GET['proposal_id'])) {
	$status = isset($_GET['change_status']) ? $_GET['change_status'] : false;
	$proposal_id = $_GET['proposal_id'];
	if ($status == 'true') {
		$update_status = $db->update("proposals", array('proposal_status' => 'pending'), array("proposal_id" => $proposal_id));
	}

	$delete_id = $input->get('proposal_id');

	$count_orders = $db->count("orders", array("proposal_id" => $delete_id));
	$qOrders = $db->query("SELECT * FROM orders WHERE proposal_id=:proposal_id AND order_active='yes' AND order_status IN ('progress', 'pending')", array("proposal_id" => $delete_id));

	if ($qOrders->rowCount() > 0)
	{
		echo "<script>alert('This proposal has pending or progressing order so can not delete.');</script>";
		echo "<script>window.open('view_proposals.php','_self');</script>";
		die();
	} else {

		$login_seller_user_name = $_SESSION['seller_user_name'];
		$select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
		$row_login_seller = $select_login_seller->fetch();
		$login_seller_id = $row_login_seller->seller_id;


		$update_proposal = $db->update("proposals", array("proposal_status" => 'deleted'), array('proposal_id' => $delete_id, "proposal_seller_id" => $login_seller_id));

		@$deleteTopRated = $db->delete("top_proposals", array("proposal_id" => $delete_id));

		if ($update_proposal) {
			echo "<script>alert('One proposal has been deleted.');</script>";
			echo "<script>window.open('view_proposals.php','_self');</script>";
		} else {
			echo "<script>window.open('../index','_self');</script>";
		}
	}
}

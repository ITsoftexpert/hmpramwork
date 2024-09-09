<?php

session_start();
include("includes/db.php");
include("functions/payment.php");
require_once("functions/functions.php");
if (!isset($_SESSION['seller_user_name'])) {
    echo "<script>window.open('login.php','_self');</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

if (isset($_POST['tazapay'])) {
    $get_payment_settings = $db->select("payment_settings");
    $row_payment_settings = $get_payment_settings->fetch();
    $featured_fee = $row_payment_settings->featured_fee;
    $processing_fee = processing_fee($featured_fee);

    $select_proposals = $db->select("proposals", array("proposal_id" => $_SESSION['f_proposal_id']));
    $row_proposals = $select_proposals->fetch();
    $proposal_title = $row_proposals->proposal_title;

    $payment = new Payment();

    $data = [];
    $data['type'] = "featured_listing";
    $data['content_id'] = $_SESSION['f_proposal_id'];
    $data['name'] = $proposal_title;
    $data['desc'] = "Featured Listing Payment";
    $data['qty'] = 1;
    $data['price'] = $featured_fee;
    $data['sub_total'] = $featured_fee;
    $data['processing_fee'] = $processing_fee;
    $data['total'] = $featured_fee + $processing_fee;
    $data['callback_url'] = "$site_url/tazapay_listing_complete";
    $data['complete_url'] = "$site_url/tazapay_listing_complete";
    $data['error_url'] = "$site_url/tazapay_listing_error";

    $payment->tazapay($data);
} else {
    echo "<script>window.open('index','_self')</script>";
}

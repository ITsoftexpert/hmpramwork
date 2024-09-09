<?php
session_start();
include("../includes/db.php");
include("../functions/payment.php");
require_once("../functions/functions.php");
if (!isset($_SESSION['success_data']['payment_info']['data']['txn_no'])) {
    echo "<script>window.open('login','_self');</script>";
    exit;
}
$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$enable_tazapay = $row_payment_settings->tazapay;
$enable_tazapay_sandbox = $row_payment_settings->tazapay_sandbox;
$enable_tazapay_secret = $row_payment_settings->tazapay_secret;
$enable_tazapay_access = $row_payment_settings->tazapay_access;
$select_sellers = $db->select("sellers", array("seller_user_name" => $_SESSION['seller_user_name']));
$row_sellers = $select_sellers->fetch();
if ($enable_tazapay_sandbox == 'yes') {
    $t_url = 'https://api-sandbox.tazapay.com';
} else {
    $t_url = 'https://app.tazapay.com';
}


$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $t_url . '/v1/checkout/' . $_SESSION['success_data']['payment_info']['data']['txn_no'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Content-Type:application/json',
        'Authorization: Basic ' . base64_encode($enable_tazapay_access . ':' . $enable_tazapay_secret)
    ),
));

$response = curl_exec($curl);
curl_close($curl);
$data = json_decode($response, true);

// insert into database
$offer_id = $_SESSION['c_message_offer_id'];
$proposal_id = $_SESSION['c_proposal_id'];
$proposal_qty = $_SESSION['c_proposal_qty'];
$amount = $_SESSION["c_sub_total"];
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

$_SESSION['message_offer_id'] = $offer_id;
//$_SESSION['checkout_seller_id'] = $offer_id;
//$_SESSION['proposal_id'] = $proposal_id;
//$_SESSION['proposal_qty'] = $proposal_qty;
//$_SESSION['proposal_price'] = $amount;
//$_SESSION['proposal_delivery'] = $_SESSION['c_proposal_delivery'];
//$_SESSION['proposal_revisions'] = $_SESSION['c_proposal_revisions'];
//if(isset($_SESSION['c_proposal_extras'])){
//    $_SESSION['proposal_extras'] = $_SESSION['c_proposal_extras'];
//}
//if(isset($_SESSION['c_proposal_minutes'])){
//    $_SESSION['proposal_minutes'] = $_SESSION['c_proposal_minutes'];
//}
//$_SESSION['method'] = "tazapay";
echo "<script>window.open('../order','_self');</script>";


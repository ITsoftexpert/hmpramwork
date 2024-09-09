<?php

session_start();
include("includes/db.php");
include("functions/payment.php");
require_once("functions/functions.php");
if (!isset($_SESSION['txn_no'])) {
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
    CURLOPT_URL => $t_url . '/v1/checkout/' . $_SESSION['txn_no'],
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

$payment = new Payment();
$payment->tazapay_execute();

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

// insert into database
$date = new DateTime();
$date = $date->format("Y-m-d H:i:s");
$end_date = date('Y-m-d H:i:s', strtotime($date . " + 30 day"));
if ($data['data']['state'] == 'Awaiting_Payment') {
    $status = 'Active';
} else {
    $status = 'Active';
}
// print("<pre>" . print_r($data, true) . "</pre>");
// exit;
$db->delete("memb_plan_detail", array('seller_id' => $_SESSION['c_proposal_id']));

$insert_plan_detail = $db->insert("memb_plan_detail", array(
    "seller_id" => $row_sellers->seller_id, "memb_tbl_id" => $_SESSION['c_proposal_id'],
    "memb_start_date" => $date, "memb_end_date" => $end_date, "memb_pyment_status" => $data['data']['state'], "memb_status" => $status, "payment_method" => "Tazapay"
));

// update seller information
$getPlan = $db->select("membership_table where id = " . $_SESSION['c_proposal_id'] . " LIMIT 1");
$objPlan = $getPlan->fetch();

$db->update("sellers", array(
    "no_of_gigs" => $objPlan->create_active_service,
    "bids_per_month" => $objPlan->bids_per_month,
    "skills" => $objPlan->skills,
    "comission_per_sale" => $objPlan->percentage_per_project,
    "create_porfolio" => $objPlan->create_portfolio,
    "create_porfolio" => $objPlan->create_portfolio,
    "project_bookmarks" => $objPlan->project_bookmark,
), array(
    "seller_id" => $row_sellers->seller_id,
));

unset($_SESSION['txn_no']);


if ($insert_plan_detail) {
    echo '<script> header(\'location: index\');
</script>';
    header('location: index');
} else {
    echo '<h1> Something went wrong! Please contact admin';
}

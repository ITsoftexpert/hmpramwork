<?php
session_start();
include("includes/db.php");
include("functions/payment.php");
require_once("functions/functions.php");
if (!isset($_SESSION['seller_user_name'])) {
    echo "<script>window.open('login','_self');</script>";
}

$reference_no = mt_rand();

$select_proposals = $db->select("membership_table", array("id" => $_SESSION['c_proposal_id']));
$row_proposals = $select_proposals->fetch();
$row_plan_price_month = $row_proposals->plan_price_month;
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


$data = [];
$data['type'] = 'membership';

$data['reference_no'] = $reference_no;
$data['content_id'] = $_SESSION['c_proposal_id'];

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $t_url . '/v1/checkout',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
    "buyer": {
        "email": "' . $row_sellers->seller_email . '",
        "contact_code": "+1",
        "contact_number": "1111111111",
        "country": "US",
        "ind_bus_type": "Business",
        "business_name": "' . $_SESSION['seller_user_name'] . '"
    },
    "seller": {
        "email": "Info@hiremyprofile.com",
        "contact_code": "+1",
        "contact_number": "1111111111",
        "country": "US",
        "ind_bus_type": "Business",
        "business_name": "HireMyProfile"
    },
    "fee_paid_by": "buyer",
    "fee_percentage": 0,
    "invoice_currency": "USD",
    "invoice_amount": ' . $row_plan_price_month . ',
    "txn_description": "Membership plan",
    "callback_url": "' . $site_url . '/tazapay_complete",
    "complete_url": "' . $site_url . '/tazapay_complete",
    "error_url": "' . $site_url . '/tazapay_error",
     "txn_docs": [
        {
            "type": "Proforma Invoice",
            "url": "https://image.shutterstock.com/image-vector/luxurious-nature-floral-leaf-ornament-600w-1938357313.jpg",
            "name": "Screenshot from 2022-01-17 20-41-22.png"
        }
    ],
    "attributes": {
        "doc_url": "https://media.istockphoto.com/photos/historic-buildings-of-wall-street-in-the-financial-district-of-lower-picture-id1337246025?s=612x612"
    }
}',
    CURLOPT_HTTPHEADER => array(
        "Authorization: Basic " . base64_encode($enable_tazapay_access . ':' . $enable_tazapay_secret),
        "Content-Type: application/json"
    ),
));

$response = curl_exec($curl);

$responseCode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

$downloadLength = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

if(curl_errno($curl)) {
    print curl_error($curl);
} else {
    // DEBUGGING
    // if($responseCode == "200") echo "successful request";

    // echo " # download length : " . $downloadLength;

    // echo "<pre>"; print_r($response); exit;

    curl_close($curl);

    $data = json_decode($response, true);

    $_SESSION['txn_no'] = $data['data']['txn_no'];
    header('location:' . $data['data']['redirect_url']);
}

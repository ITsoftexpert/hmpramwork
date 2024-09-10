<?php

session_start();
include("../includes/db.php");
include("../functions/payment.php");
require_once("../functions/functions.php");
if(!isset($_SESSION['seller_user_name'])){
    echo "<script>window.open('login','_self');</script>";
}

$reference_no = mt_rand();
$select_offers = $db->select("messages_offers",array("offer_id"=>$_SESSION['c_message_offer_id']));
$row_offers = $select_offers->fetch();
$proposal_id = $row_offers->proposal_id;
$amount = $row_offers->amount;
$processing_fee = processing_fee($amount);

$select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));
$row_proposals = $select_proposals->fetch();
$proposal_title = $row_proposals->proposal_title;
$_SESSION["c_sub_total"] = $amount;


$proposal_seller_id = $row_proposals->proposal_seller_id;


$seller_fetch = $db->select("sellers",array("seller_id" => $proposal_seller_id));
$proposal_seller = $seller_fetch->fetch();


$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$enable_tazapay_secret = $row_payment_settings->tazapay_secret;
$enable_tazapay_access = $row_payment_settings->tazapay_access;
$enable_tazapay_sandbox = $row_payment_settings->tazapay_sandbox;
if ($enable_tazapay_sandbox == 'yes'){
    $t_url = 'https://api-sandbox.tazapay.com';
} else {
    $t_url = 'https://app.tazapay.com';
}


$buyer_fetch = $db->select("sellers",array("seller_user_name" => $_SESSION['seller_user_name']));
$prop_buyer = $buyer_fetch->fetch();
$_SESSION['message_offer_buyer_id']= $prop_buyer->seller_id;
$data = [];
$data['type'] = 'proposal';

$data['reference_no'] = $reference_no;
$data['content_id'] = $proposal_id;
$data['name'] = $row_proposals->proposal_title;
$data['qty'] = 1;
$data['price'] = $amount;
$data['delivery_id'] = $proposal_id;
$data['revisions'] = 66;
$data['processing_fee'] = $processing_fee;

$data['sub_total'] = $amount;
$data['total'] = $amount+$processing_fee;



$data['desc'] = "Proposal Payment";

$data['cancel_url'] = "$site_url/cancel_payment";

$curl = curl_init();

$user = curl_setopt_array($curl, array(
    CURLOPT_URL => $t_url.'/v1/checkout',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
    "buyer": {
        "email": "'.$prop_buyer->seller_email.'",
        "contact_code": "+1",
        "contact_number": "1111111111",
        "country": "US", 
        "ind_bus_type": "Business",
        "business_name": "'.$_SESSION['seller_user_name'].'"
    },
    "seller": {
        "email": "'.$proposal_seller->seller_email.'",
        "contact_code": "+1",
        "contact_number": "1111111111",
        "country": "US",
        "ind_bus_type": "Business",
        "business_name": "'.$proposal_seller->seller_name.'"
    },
    "fee_paid_by": "buyer",
    "fee_percentage": 0,
    "invoice_currency": "USD",
    "invoice_amount": '.$data['total'].',
    "txn_description": "Proposal purchase",
    "callback_url": "'.$site_url.'/conversations/tazapay_complete",
    "complete_url": "'.$site_url.'/conversations/tazapay_complete",
    "error_url": "'.$site_url.'/tazapay_error",
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
        "Authorization: Basic ".base64_encode($enable_tazapay_access.':'.$enable_tazapay_secret),
        'Content-Type: application/json'
    ),
));

echo $response = curl_exec($curl);

curl_close($curl);
$data['payment_info'] = json_decode($response,true);
$_SESSION['success_data'] = $data;
//$_SESSION['success_data'] = $data['data']['txn_no'];
header('location:'. $data['payment_info']['data']['redirect_url']);

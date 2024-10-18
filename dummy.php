<?php
session_start();
require_once("../includes/db.php");
require_once("../functions/mailer.php");

if (!isset($_SESSION['seller_user_name'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}
echo "5 hour left";
// Get current time and other necessary variables
$today_without_time = date('F, d, Y');
$now = date('H:i:s'); // Get current time (hours:minutes)
$target_time = '09:01:10'; // Time when you want to send the email
$today = date('Y-m-d H:i:s');
$yesterday = date('Y-m-d H:i:s', strtotime('-24 hours'));

// Get POST data from AJAX request
if (isset($_POST['seller_id']) && isset($_POST['order_id']) && isset($_POST['cancellation_message']) && isset($_POST['cancellation_reason'])) {
    echo "<script> alert('hello'); </script>";
    $seller_id = $_POST['seller_id'];
    $order_id = $_POST['order_id'];
    $cancellation_message = $_POST['cancellation_message'];
    $cancellation_reason = $_POST['cancellation_reason'];

    // Fetch seller details from database
    $seller_user_name_i = $db->select("sellers", array("seller_id" => $seller_id))->fetch();
    if (!$seller_user_name_i) {
        echo json_encode(['error' => 'Seller not found']);
        exit();
    }

    $seller_user_name = $seller_user_name_i->seller_user_name;

    // Check if order cancellation request exists and process accordingly
    $buyer_ocancellation_request = $db->select(
        "buyer_cancellation_request",
        array(
            "order_id" => $order_id,
            "seller_id" => $seller_id,
            "td_status" => 'sent'  // Check if the first email has been sent
        )
    )->rowCount();

    if ($buyer_ocancellation_request == 0) {
        // Insert new cancellation request into the database
        $three_days = date('Y-m-d H:i:s', strtotime('+3 days'));
        $insert_cancellation_email = $db->insert(
            "buyer_cancellation_request",
            array(
                "order_id" => $order_id,
                "seller_id" => $seller_id,
                "three_days" => $three_days,
                "td_status" => 'sent'
            )
        );

        if ($insert_cancellation_email) {
            // Send the first email immediately
            $data = [];
            $data['template'] = "order_cancellation_request_to_seller";
            $data['to'] = "kumshubham25@gmail.com"; // Recipient email
            $data['subject'] = "$site_name: Buyer Cancellation Request";
            $data['user_name'] = $seller_user_name;
            $data['cancellation_message'] = $cancellation_message;
            $data['order_url'] = "$site_url/order_details?order_id=$order_id"; // Link to buyer requests

            // Send the email
            send_mail($data);


            echo "5 hour left";
            // Respond to AJAX
            echo json_encode(['status' => 'success', 'message' => 'Email sent and cancellation recorded']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert cancellation request']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Cancellation request already exists']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data received']);
}

<?php
session_start();
require_once("../../includes/db.php");

// Check if the session variable is set
if (!isset($_SESSION['seller_user_name'])) {
    echo "<script>window.open('login', '_self');</script>";
    exit();
}

// Check all POST data
var_dump($_POST);
exit;

// Get POST data from the request
$order_id = $_POST['order_id'];
$order_number = $_POST['order_number'];
$email_sent = $_POST['email_sent'];

var_dump($order_id);
var_dump($order_number);
var_dump($email_sent);

exit;
// Fetch the seller's data from the database
$twenty_four_notify = $db->select("orders", array("order_id" => $order_id))->fetch();
$isEmail_sent = $twenty_four_notify->email_sent;

// If email hasn't been sent yet
if ($isEmail_sent == 0) {

    // Update the order record to indicate that the email has been sent
    $insertwenty_notify = $db->update("orders", array("email_sent" => $email_sent), array("order_id" => $order_id));

    if ($insertwenty_notify) {
        // Optionally, send the email here
        // send_mail($data);
        echo json_encode(["status" => "success", "message" => "Email sent successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update email_sent field."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Email already sent."]);
}
exit();

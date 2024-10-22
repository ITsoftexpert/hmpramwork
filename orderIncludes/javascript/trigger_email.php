<?php
session_start();
require_once("../../includes/db.php");
require_once("functions/mailer.php");

// Check if the session variable is set
if (!isset($_SESSION['seller_user_name'])) {
    echo "<script>window.open('login', '_self');</script>";
    exit();
}

if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $order_number = $_POST['order_number'];
    $seller_user_name = $_POST['seller_name'];

    $check_email_send = $db->select("orders", array("email_sent" => 0, "order_id" => $order_id));
    if ($check_email_send->rowCount() > 0) {
        $update_email_send = $db->update("orders", array("email_sent" => 2), array("order_id" => $order_id));
        if ($update_email_send) {
            $data = [];
            $data['template'] = "remaining_24h_order_complete";
            $data['to'] = "kumshubham25@gmail.com";
            $data['subject'] = "$site_name: 24 hours left for order deadline";
            $data['user_name'] = $seller_user_name;
            $data['order_number'] = $order_number;
            $data['link_url'] = "$site_url/order_details?order_id=$order_id";
            send_mail($data);
        } else {
            echo "already sent email";
        }
    } else {
        echo "already sent email2";
    }
} else {
    echo "no data";
}

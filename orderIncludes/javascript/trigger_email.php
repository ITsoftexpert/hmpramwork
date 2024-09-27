<?php
require_once("../../includes/db.php");

$order_id = $_POST['order_id'];

$is_sent_email = check_if_email_send($order_id);

if (!$is_sent_email) {
    $data = [];
    $data['template'] = "remaining_24h_order_complete";
    $data['to'] = "kumshubham25@gmail.com";
    $data['subject'] = "$site_name: 24 hours left for order deadline";
    $data['user_name'] = $seller_user_name;
    $data['order_number'] = $order_number;
    $data['link_url'] = "$site_url/order_details?order_id=$order_id";
    send_mail($data);

    mark_email_as_sent($order_id);
    echo "Email Sent.";
} else {
    echo "Email Already Sent.";
}


function check_if_email_send($order_id)
{
    global $db;
    // Query the database to see if the email was already sent
    $query_if_emailsent = $db->query("SELECT email_sent FROM orders WHERE order_id = '$order_id'");
    if ($query_if_emailsent->rowCount() > 0) {
        $result = $query_if_emailsent->fetch();
        return $result->email_sent == 1;
    } else {
        return false;
    }
}


function mark_email_as_sent($order_id)
{
    global $db;
    $db->query("UPDATE orders SET email_sent = 1 WHERE order_id = '$order_id'");
}

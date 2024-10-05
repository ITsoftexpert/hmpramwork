<?php
// select login user details
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_level = $row_login_seller->seller_level;


// update order
$update_order = $db->update("orders", array("order_status" => "Delivery accepted", "order_active" => "no"), array("order_id" => $order_id));
$db->update("milestone", array("milestone_status" => "Delivery accepted", "order_id" => $order_id), array("milestone_id" => $milestone_id));

// update messages
$update_messages = $db->update("order_conversations", array("status" => "message"), array("order_id" => $order_id, "status" => "delivered"));

// Insert notification
$date = date("F d, Y");
$insert_notification = $db->insert("notifications", array("receiver_id" => $seller_id, "sender_id" => $buyer_id, "order_id" => $order_id, "reason" => "delivery_accepted", "date" => $date, "status" => "unread"));

/// sendPushMessage Starts
$notification_id = $db->lastInsertId();
sendPushMessage($notification_id);
/// sendPushMessage Ends
if($update_order){
    echo "<script> alert('hello'); </script>";
}else{
    echo "<script> alert('bye'); </script>";

}
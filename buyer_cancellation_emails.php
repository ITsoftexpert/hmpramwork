<?php
session_start();
require_once("includes/db.php");
require_once("functions/mailer.php");

if (!isset($_SESSION['seller_user_name'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}


if (isset($_GET['login_seller_id'])) {
    $login_seller_id = $_GET['login_seller_id'];
    $buyer_id = $_GET['buyer_id'];
    echo "seller_id " . $login_seller_id;
    echo "buyer_id " . $buyer_id;

    $get_information_bcr = $db->select("buyer_cancellation_request", array("sender_id" => $login_seller_id));
    while ($row_information_bcr = $get_information_bcr->fetch()) {
        $order_id = $row_information_bcr->order_id;
        $receiver_id = $row_information_bcr->receiver_id;

        echo "order_id: ".  $order_id;

        $select_order_cancellation = $db->select("orders", array("order_id" => $order_id));
        while($select_order_cancell_row = $select_order_cancellation->fetch()){
           
            echo "order status ". $order_status;
        }
       
    }
}

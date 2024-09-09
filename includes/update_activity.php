<?php

include('db.php');

if (isset($_GET['seller_id'])) {
    $sellerId = $_GET['seller_id'];
    $get_seller = $db->select("sellers",array("seller_id" => $sellerId));
    $row_seller = $get_seller->fetch();
    $seller_id = $row_seller->seller_id;

    $last_activity = date("Y-m-d H:i:s");

    $update_seller = $db->update("sellers",array("seller_activity" => $last_activity),array("seller_id" => $seller_id));
    echo true;
}
echo false;

<?php

$isAjax = 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');
if (!$isAjax)
    die("No direct access.");

require_once("../includes/db.php");
require_once("../functions/email.php");

$reason = $input->post('reason');
$additional_information = $input->post('additional_information');
$reporter_id = $input->post('reporter_id');
$content_id = $input->post('content_id');
$content_type = $input->post('content_type');
$url = $input->post('url');
$date = date("F d, Y");

$insert = $db->insert("reports", array("reporter_id" => $reporter_id, "content_id" => $content_id, "content_type" => $content_type, "reason" => $reason, "additional_information" => $additional_information, "date" => $date));

$insert_notification = $db->insert("admin_notifications", array("seller_id" => $reporter_id, "content_id" => $content_id, "reason" => "{$content_type}_report", "date" => $date, "status" => "unread"));

if ($insert_notification) {
    $select_login_seller = $db->select("sellers", array("seller_id" => $reporter_id));
    $row_login_seller = $select_login_seller->fetch();
    send_report_email("proposal", $row_login_seller->seller_name, $url, $date);
}

echo json_encode(["data" => "OK"]);
exit;

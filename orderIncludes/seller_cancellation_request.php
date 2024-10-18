<?php
session_start();
require_once("../includes/db.php");
require_once("../functions/mailer.php");

if (!isset($_SESSION['seller_user_name'])) {
    echo "<script>window.open('../login','_self')</script>";
}


$today_without_time = date('F, d, Y');
$now = date('H:i:s'); // Get current time (hours:minutes)
$target_time = '09:01:10'; // Time when you want to send the email
$today = date('Y-m-d H:i:s');
$yesterday = date('Y-m-d H:i:s', strtotime('-24 hours'));
$today_without_time = date('F, d, Y'); // Today's date without time


if (isset($_GET['seller_id'])) {
    $seller_id = $_GET['seller_id'];  

    $seller_user_name_i = $db->select("sellers", array("seller_id" => $seller_ids))->fetch();
    $seller_user_name = $seller_user_name_i->seller_user_name;
     
    $select_seller_proposal = $db->select("proposals", array("proposal_seller_id" => $seller_ids));
 
    if ($now == $target_time) {
        $trigger_email = $db->select("trigger_email", array("tirgger_email_date" => $request_date_data))->rowCount();
        if ($trigger_email == 0) {
            $trigger_email_send = $db->insert(
                "trigger_email",
                array(
                    "tirgger_email_date" => $request_date_data,
                    "today_date" => $today_without_time,
                    "status" => "sent",
                )
            );
            if ($trigger_email_send) {
                $data = [];
                $data['template'] = "recent_released_jobs"; // Template file name
                $data['to'] = "kumshubham25@gmail.com"; // Recipient email
                $data['subject'] = "$site_name: Recent Released Jobs"; // Email subject
                $data['user_name'] = $seller_user_name; // Seller's username
                $data['jobs'] = $jobs; // Pass the jobs array
                $data['project_post_url'] = "$site_url/requests/buyer_requests"; // Link to buyer requests

                // Try to send the email
                if (send_mail($data)) {
                    // echo "hello"; // Email sent successfully
                } else {
                    // echo "BYE"; // Failed to send the email
                }
            } else {
                echo "email sent already";
            }
        } else {
            echo "no";
        };
    } else {
     
    }
}

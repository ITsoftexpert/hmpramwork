<?php
session_start();
require_once("includes/db.php");
require_once("functions/mailer.php");

if (!isset($_SESSION['seller_user_name'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

if (isset($_GET['sender_id'])) {
    $sender_id = $_GET['sender_id'];
    $receiver_id = $_GET['receiver_id'];
    $order_id = $_GET['order_id'];
    echo "sender_id " . $sender_id;
    echo "receiver_id " . $receiver_id;
    echo "order id: " . $order_id;

    // Fetch distinct buyer cancellation requests to avoid repetition
    $get_information_bcr = $db->select("buyer_cancellation_request", array("order_id" => $order_id, "sender_id" => $sender_id));
    echo $get_information_bcr->rowCount();

    // Process each unique cancellation request
    while ($row_information_bcr = $get_information_bcr->fetch()) {
        $three_days = $row_information_bcr->three_days; // "09:56: Oct 19, 2024"    
        // Convert three_days to a DateTime object
        $three_days_datetime = DateTime::createFromFormat('H:i: M d, Y', $three_days);
        // Check if the DateTime object was created successfully
        if (!$three_days_datetime) {
            echo "Failed to parse date: $three_days<br>";
            continue; // Skip this iteration
        }
        // Get the current date and time
        $current_datetime = new DateTime();
        // Add 48 hours to the three_days DateTime
        $future_datetime = clone $three_days_datetime; // Clone to preserve original
        $future_datetime->modify('+48 hours');

        $lessfive_datetime = clone $three_days_datetime; // Clone to preserve original
        $lessfive_datetime->modify('+67 hours');
        // Display the future DateTime
        echo "Future DateTime (after 48 hours): " . $future_datetime->format('Y-m-d H:i:s') . "<br>";
        echo "Future DateTime (after 67 hours): " . $lessfive_datetime->format('Y-m-d H:i:s') . "<br>";

       
        // Check if the current date and time is greater than or equal to the future DateTime
        if ($current_datetime >= $future_datetime) {           
            echo "48 hours have passed. Sending email...<br>";            
        } else {
            echo "48 hours have not passed yet.<br>";
            $select_fh_status = $db->select("buyer_cancellation_request", array("order_id" => $order_id, "tfh_status" => 'sent'))->rowCount();
         
            if ($select_fh_status == 0) {
                $insert_second_email = $db->insert("buyer_cancellation_request", array("twenty_four_hours" => $current_datetime, "tfh_status" => 'sent'), array("order_id" => $order_id));
                if ($insert_second_email) {
                    $data = [];
                    $data['template'] = "recent_released_jobs"; // Template file name
                    $data['to'] = "kumshubham25@gmail.com"; // Recipient email
                    $data['subject'] = "$site_name: Recent Released Jobs"; // Email subject
                    $data['user_name'] = $seller_user_name; // Seller's username
                    $data['jobs'] = $jobs; // Pass the jobs array
                    $data['project_post_url'] = "$site_url/requests/buyer_requests"; // Link to buyer requests

                    // Try to send the email
                    send_mail($data);
                }
            }
        }
    }
}

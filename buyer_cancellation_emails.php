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
    

    // Fetch distinct buyer cancellation requests to avoid repetition
    $get_information_bcr = $db->select("buyer_cancellation_request", array("order_id" => $order_id, "sender_id" => $sender_id));
    $order_status_check = $db->select("orders", array("order_id" => $order_id))->fetch();
    $order_status = $order_status_check->order_status;
    if ($order_status == "cancellation requested") {
        $seller_emails_details = $db->select("sellers", array("seller_id" => $receiver_id))->fetch();
        $seller_email = $seller_emails_details->seller_email;
         $seller_user_name = $seller_emails_details->seller_user_name;

        // Process each unique cancellation request
        while ($row_information_bcr = $get_information_bcr->fetch()) {
            $three_days = $row_information_bcr->three_days; 
            $role = $row_information_bcr->role; 
            
            // Convert three_days to a DateTime object
            $three_days_datetime = DateTime::createFromFormat('H:i: M d, Y', $three_days);
            // Check if the DateTime object was created successfully
            if (!$three_days_datetime) {
                
                continue; // Skip this iteration
            }
            date_default_timezone_set('Asia/Kolkata');
            // Get the current date and time
            $current_datetime = new DateTime();

            // Add 48 hours to the three_days DateTime
            $future_datetime = clone $three_days_datetime; // Clone to preserve original
            $future_datetime->modify('+48 hours');

            $lessfive_datetime = clone $three_days_datetime; // Clone to preserve original
            $lessfive_datetime->modify('+67 hours');
       
            // Display the future DateTime
            $three_days_datetime->format('Y-m-d H:i:s');
            $future_datetime->format('Y-m-d H:i:s');
            $lessfive_datetime->format('Y-m-d H:i:s');
            $current_datetime->format('Y-m-d H:i:s');

            // Check if the current date and time is greater than or equal to the future DateTime
            if ($current_datetime >= $future_datetime) {
               
                $select_tfh_status = $db->select("buyer_cancellation_request", array("order_id" => $order_id, "tfh_status" => 'sent'))->rowCount();

                if ($select_tfh_status == 0) {
                    // Attempt to update the database
                    $insert_second_email = $db->update("buyer_cancellation_request", array("twenty_four_hours" => $current_datetime->format('Y-m-d H:i:s'), "tfh_status" => 'sent'), array("order_id" => $order_id));

                    if ($insert_second_email) {
                        $data = [];
                        $data['template'] = "order_cancellation_request_to_bs"; // Template file name
                        $data['to'] = "kumshubham25@gmail.com"; // Recipient email
                        // $data['to'] = $seller_email;
                        $data['subject'] = "$site_name: Order Cancellation Request"; // Email subject
                        $data['user_name'] = $seller_user_name; // Seller's username
                        $data['time_left'] = "24 hours left"; // Seller's username
                        $data['role'] = $role;
                        $data['request_date'] = $three_days;
                        $data['order_url'] = "$site_url/order_details?order_id=$order_id"; // Link to buyer requests
                        send_mail($data); // Assume this function returns true on success
                    } else {
                       
                    }
                } else {
                   
                }
            } else {
                
            }

            if ($current_datetime >= $lessfive_datetime) {
                // echo "67 hours have passed. Sending email...<br>";
                $select_fh_status = $db->select("buyer_cancellation_request", array("order_id" => $order_id, "fh_status" => 'sent'))->rowCount();

                if ($select_fh_status == 0) {
                    // Attempt to update the database
                    $insert_third_email = $db->update("buyer_cancellation_request", array("five_hours" => $current_datetime->format('Y-m-d H:i:s'), "fh_status" => 'sent'), array("order_id" => $order_id));

                    if ($insert_third_email) {
                        $data = [];
                        $data['template'] = "order_cancellation_request_to_bs"; // Template file name
                        $data['to'] = "kumshubham25@gmail.com"; // Recipient email
                        // $data['to'] = $seller_email;
                        $data['subject'] = "$site_name: Order Cancellation Request"; // Email subject
                        $data['user_name'] = $seller_user_name; // Seller's username
                        $data['time_left'] = "5 hours left"; // Seller's username
                        $data['role'] = $role;
                        $data['request_date'] = $three_days;
                        $data['order_url'] = "$site_url/order_details?order_id=$order_id"; // Link to buyer requests
                        send_mail($data); // Assume this function returns true on success

                    } else {
                      
                    }
                } else {
                   
                }
            } else {
               
            }
        }
    }
}

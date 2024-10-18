<?php
session_start();
require_once("../includes/db.php");
require_once("../functions/mailer.php");

if (!isset($_SESSION['seller_user_name'])) {
    echo "<script>window.open('../login','_self')</script>";
}

$three_days_later = date('Y-m-d H:i:s', strtotime('+3 days'));

function get_random_color()
{
    // Generate a random hexadecimal color code
    $random_color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    return $random_color;
}

$today_without_time = date('F, d, Y');
$now = date('H:i:s'); // Get current time (hours:minutes)
$target_time = '09:01:10'; // Time when you want to send the email
$today = date('Y-m-d H:i:s');
$yesterday = date('Y-m-d H:i:s', strtotime('-24 hours'));
$today_without_time = date('F, d, Y'); // Today's date without time


if (isset($_GET['seller_id'])) {
    $seller_ids = $_GET['seller_id'];
   
 
    // Array to store unique request IDs
    $unique_request_ids = [];
    $jobs = []; // Array to hold job details

    $seller_user_name_i = $db->select("sellers", array("seller_id" => $seller_ids))->fetch();
    $seller_user_name = $seller_user_name_i->seller_user_name;
  
    // Fetch proposals based on seller ID
    $select_seller_proposal = $db->select("proposals", array("proposal_seller_id" => $seller_ids));

    while ($row_seller_proposal = $select_seller_proposal->fetch()) {
        $proposal_cat_id = $row_seller_proposal->proposal_cat_id;
        $proposal_child_id = $row_seller_proposal->proposal_child_id;

        $queryyy = "SELECT * FROM buyer_requests 
             WHERE request_date = '$today_without_time' 
             AND cat_id = $proposal_cat_id 
            AND child_id = $proposal_child_id 
            ORDER BY request_date DESC 
             LIMIT 4";

        // Execute the query
        $select_seller_offers = $db->query($queryyy);
        //     echo $select_seller_offers->rowCount();
        // }
        while ($row_seller_offers = $select_seller_offers->fetch()) {
            $request_id = $row_seller_offers->request_id;

            // Check if the request ID is already in the array
            if (!in_array($request_id, $unique_request_ids)) {
                // If not, add it to the array
                $unique_request_ids[] = $request_id;

                // Fetch the required job details and store in $jobs array
                $jobs[] = array(
                    'request_id' => $row_seller_offers->request_id,
                    'request_title' => $row_seller_offers->request_title,
                    'request_description' => $row_seller_offers->request_description,
                    'request_budget' => $row_seller_offers->request_budget,
                    'delivery_time' => $row_seller_offers->delivery_time,
                    'bg_color' => get_random_color() // Assuming you have a function to generate a random color
                );
            }
        }
    }

    $check_today_email_send = $db->select("buyer_requests", array(
        "request_date" => $today_without_time,
        "cat_id" => $proposal_cat_id,
        "child_id" => $proposal_child_id
    ))->fetch();
    $request_ids = $check_today_email_send->request_id;
    $request_date_data = $check_today_email_send->request_date;
    // echo "status: " . $request_date_data;

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

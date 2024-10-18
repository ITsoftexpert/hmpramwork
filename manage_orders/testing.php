<div class="col-md-12 mt-4">
    <?php
    function get_random_color()
    {
        // Generate a random hexadecimal color code
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    $today = date('Y-m-d H:i:s');
    $yesterday = date('Y-m-d H:i:s', strtotime('-24 hours'));
    $today_without_time = date('F, d, Y');

    // Array to store unique request IDs
    $unique_request_ids = [];
    $jobs = []; // Array to hold job details

    // Fetch proposals based on seller ID
    $select_seller_proposal = $db->select("proposals", array("proposal_seller_id" => $seller_id));

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
    ?>

    <!-- HTML form to trigger the email -->
   
    <?php
   
        // Check the last time email was sent
        $seller_id = $_SESSION['seller_user_id']; // Assuming seller ID is stored in session
        $check_last_sent_query = $db->select("email_log", array("seller_id" => $seller_id));
        $last_sent_row = $check_last_sent_query->fetch();

        if ($last_sent_row) {
            $last_sent_time = $last_sent_row['last_sent_time'];
            $time_diff = strtotime($today) - strtotime($last_sent_time);

            // Check if 24 hours have passed
            if ($time_diff < 86400) {
                echo "Email was already sent within the last 24 hours.";
                return; // Exit without sending email
            }
        }

        // Prepare data to send via email
        $data = [];
        $data['template'] = "recent_released_jobs"; // Template file name
        $data['to'] = "kumshubham25@gmail.com"; // Recipient email
        $data['subject'] = "$site_name: Recent Released Jobs"; // Email subject
        $data['user_name'] = $seller_user_name; // Seller's username
        $data['jobs'] = $jobs; // Pass the jobs array (which now includes detailed job information)
        $data['project_post_url'] = "$site_url/requests/buyer_requests"; // Link to the buyer requests page

        // Send the email
        send_mail($data);

        // If email is sent, update the last sent time
        if ($last_sent_row) {
            // Update the existing record
            $db->update("email_log", array("last_sent_time" => $today), array("seller_id" => $seller_id));
        } else {
            // Insert a new record
            $db->insert("email_log", array("seller_id" => $seller_id, "last_sent_time" => $today));
        }

        echo "Email sent successfully.";
  
    ?>
</div>

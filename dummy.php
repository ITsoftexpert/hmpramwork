<?php
// Insert milestone
if (isset($_POST["submit_milestone"])) {
  $task_amount = $_POST['task_amount'];
  $delivery_time = $_POST['delivery_time'];
  $task_description = $_POST['task_description'];
  $task_title = $_POST['task_title'];
  
  // Fetch the initial order_number related to the request_id
  $check_if_onumber = $db->select("milestone", array("request_id" => $request_id))->fetch();
  $order_number = $check_if_onumber->order_number;

  // Initialize a flag for checking existing order numbers
  $new_order_number = $order_number;
  $suffix = 0;  // Start with no suffix

  // Check if the base order_number exists or if an order number with a suffix already exists
  do {
      // Create the next order number with suffix (if any)
      $current_order_number = $suffix == 0 ? $new_order_number : $new_order_number . '-' . $suffix;

      // Check if this specific order number already exists in the milestone table
      $existing_order_number = $db->select("milestone", array(
          "request_id" => $request_id,
          "order_number" => $current_order_number
      ))->fetch();
      
      // If it exists, increment the suffix and try again
      $suffix++;

  } while ($existing_order_number);  // Repeat until no matching order number is found

  // Now we have a unique order number, insert the milestone
  $insert_milestone_data = $db->insert(
      "milestone",
      array(
          "task_amount" => $task_amount,
          "delivery_time" => $delivery_time . " days",
          "task_description" => $task_description,
          "request_id" => $request_id,
          "sender_id" => $sender_id,
          "seller_id" => $login_seller_id,
          "proposal_id" => $proposal_id,
          "offer_id" => $offer_id,
          "task_title" => $task_title,
          "order_number" => $current_order_number  // Use the unique order number
      )
  );
}

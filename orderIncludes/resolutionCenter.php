<div class="row">
  <div class="col-md-10 offset-md-1">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-8 offset-md-2">
            <div class="card">
              <div class="card-body">
                <h3 class="text-center" id="form_heading_ocr">Order Cancellation Request</h3>
              </div>
            </div>

            <select name="" id="form_selector" class="form-control">
              <option value="cancelation_form_show">Order Cancellation Request</option>
              <?php if ($seller_id == $login_seller_id) { ?>
                <option value="request_create_milestone">Request To Buyer Create Milestone</option>
              <?php } ?>
              <?php if ($order_status === "Extend Delivery Request") { ?>
                <option value=""></option>
                <?php } else {
                if ($seller_id == $login_seller_id) { ?>
                  <option value="extend_form_show">Delivery Extend Request</option>
              <?php }
              } ?>
            </select>

            <form method="post" id="extend_form_show" style="display: none;">
              <div class="form-group">
                <textarea name="extend_delivery_message" placeholder="Please be as detailed as possible..." rows="10" class="form-control" required></textarea>
              </div>
              <div class="form-group">
                <label class="font-weight-bold">Extend Delivery Request Reason</label>
                <select name="extend_reason" class="form-control" required>
                  <option value="" class="hidden">Select extend delivery reason</option>
                  <option>I encountered unexpected technical issues that require additional time to resolve.</option>
                  <option>I need to conduct more thorough testing to ensure quality and reliability.</option>
                  <option>There was a delay in receiving necessary feedback or approvals from stakeholders.</option>
                  <option>I have encountered unforeseen personal circumstances that have affected my availability.</option>
                  <option>I am awaiting crucial resources or materials needed to complete the project.</option>
                  <option>I am balancing multiple projects, and prioritizing tasks has taken longer than anticipated.</option>
                  <option>The scope of the project has expanded, requiring more time to meet new requirements.</option>
                  <option>I need to refine the project to meet higher standards and expectations.</option>
                  <option>I am collaborating with external partners or vendors, and coordination is taking longer than expected.</option>
                  <option>There have been interruptions or delays beyond my control, impacting project timelines.</option>
                </select>
              </div>

              <div class="form-group">
                <label class="font-weight-bold">Extend Delivery Time</label>
                <input type="datetime-local" name="order_time_extend" class="form-control" required>
              </div>



              <input type="submit" name="submit_extend_request" value="Submit Extension Request" class="btn btn-success float-right">
            </form>

            <form method="post" id="cancelation_form_show">
              <div class="form-group">
                <textarea name="cancellation_message" placeholder="Please be as detailed as possible..." rows="10" class="form-control" required></textarea>
              </div>
              <div class="form-group">
                <label class="font-weight-bold"> Cancellation Request Reason </label>
                <select name="cancellation_reason" class="form-control">
                  <option class="hidden"> Select Cancellation Reason </option>
                  <?php if ($seller_id == $login_seller_id) { ?>
                    <option> Buyer is not responding. </option>
                    <option> Buyer is extremely rude. </option>
                    <option> Buyer requested that I cancel this order.</option>
                    <option> Buyer expects more than what this proposal can offer.</option>
                  <?php } elseif ($buyer_id == $login_seller_id) { ?>
                    <option> Seller is not responding. </option>
                    <option> Seller is extremely rude. </option>
                    <option> Order does meet requirements. </option>
                    <option> Seller asked me to cancel. </option>
                    <option> Seller cannot do required task. </option>
                  <?php }  ?>
                </select>
              </div>
              <input type="submit" name="submit_cancellation_request" value="Submit Cancellation Request" class="btn btn-success float-right">
            </form>
            <form method="post" id="request_create_milestone" style="display: none;">

              <div class="form-group mt-3">
                <label class="font-weight-bold">Number of Milestone</label>
                <input type="number" name="milestone_quantity" id="" class="form-control" placeholder="5" required>
              </div>

              <div class="form-group">
                <label class="font-weight-bold">Expected Amount ($)</label>
                <input type="number" name="amount_expected" id="" class="form-control" placeholder="$" required>
              </div>

              <div class="form-group">
                <label class="font-weight-bold">Duration for Milestone (Days)</label>
                <input type="number" name="duration_milestone_expect" id="" class="form-control" placeholder="days" required>
              </div>

              <div class="form-group">
                <textarea name="decription_miles_expect" placeholder="Please be as detailed as possible..." rows="10" class="form-control" required></textarea>
              </div>

              <input type="submit" name="request_milestone_create_btn" value="Submit" class="btn btn-success float-right">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php

?>


<script>
  document.getElementById('form_selector').addEventListener('change', function() {
    var extend_form_show = document.getElementById('extend_form_show');
    var cancelation_form_show = document.getElementById('cancelation_form_show');
    var form_heading_ocr = document.getElementById('form_heading_ocr');

    // Hide both sections initially
    extend_form_show.style.display = 'none';
    cancelation_form_show.style.display = 'none';
    request_create_milestone.style.display = 'none';

    // Get the selected value
    var selectedOption = this.value;

    // Display the selected section
    if (selectedOption === 'extend_form_show') {
      extend_form_show.style.display = 'block';
      form_heading_ocr.innerHTML = 'Delivery Extend Request';
    } else if (selectedOption === 'cancelation_form_show') {
      cancelation_form_show.style.display = 'block';
      form_heading_ocr.innerHTML = 'Order Cancellation Request';
    } else if (selectedOption === 'request_create_milestone') {
      request_create_milestone.style.display = 'block';
      form_heading_ocr.innerHTML = 'Request To Buyer Create Milestone';
    }
  });
</script>

<?php
if (isset($_POST['submit_cancellation_request'])) {
  $cancellation_message = $input->post('cancellation_message');
  $cancellation_reason = $input->post('cancellation_reason');
  $last_update_date = date("h:i: M d, Y");
  if ($seller_id == $login_seller_id) {
    $receiver_id = $buyer_id;
    $role = "seller";
    $enter_emails_data = $db->insert("buyer_cancellation_request", array(
      "order_id" => $order_id,
      "sender_id" => $seller_id,
      "receiver_id" => $buyer_id,
      "role" => $role,
      "three_days" => $last_update_date,
      "td_status" => "sent"
    ));


    $data = [];
    $data['template'] = "order_cancellation_request_to_bs"; // Template file name
    $data['to'] = "kumshubham25@gmail.com"; // Recipient email
    // $data['to'] = $seller_email;
    $data['subject'] = "$site_name: Order Cancellation Request"; // Email subject
    $data['user_name'] = $seller_user_name; // Seller's username
    $data['time_left'] = "72 hours left"; // 
    $data['role'] = $role;
    $data['request_date'] = $last_update_date;
    $data['order_url'] = "$site_url/order_details?order_id=$order_id"; // Link to buyer requests
    send_mail($data); // Assume this function returns true on success

  } else {
    $role = "buyer";
    $receiver_id = $seller_id;
    $enter_emails_data = $db->insert("buyer_cancellation_request", array(
      "order_id" => $order_id,
      "sender_id" => $buyer_id,
      "receiver_id" => $seller_id,
      "three_days" => $last_update_date,
      "td_status" => "sent"
    ));


    $data = [];
    $data['template'] = "order_cancellation_request_to_bs"; // Template file name
    $data['to'] = "kumshubham25@gmail.com"; // Recipient email
    // $data['to'] = $seller_email;
    $data['subject'] = "$site_name: Order Cancellation Request"; // Email subject
    $data['user_name'] = $seller_user_name; // Seller's username
    $data['time_left'] = "72 hours left"; // 
    $data['role'] = $role;
    $data['request_date'] = $last_update_date;
    $data['order_url'] = "$site_url/order_details?order_id=$order_id"; // Link to buyer requests
    send_mail($data); // Assume this function returns true on success

  }


  if (send_cancellation_request($order_id, $order_number, $login_seller_id, $row_orders->proposal_id, $row_orders->seller_id, $row_orders->buyer_id, $last_update_date)) {
    $insert_order_conversation = $db->insert("order_conversations", array("order_id" => $order_id, "sender_id" => $login_seller_id, "message" => $cancellation_message, "date" => $last_update_date, "reason" => $cancellation_reason, "status" => "cancellation_request"));

    if ($insert_order_conversation) {
      $insert_notification = $db->insert("notifications", array("receiver_id" => $receiver_id, "sender_id" => $login_seller_id, "order_id" => $order_id, "reason" => "cancellation_request", "date" => $n_date, "status" => "unread"));

      /// sendPushMessage Starts
      $notification_id = $db->lastInsertId();
      sendPushMessage($notification_id);
      /// sendPushMessage Ends

      $update_order = $db->update("orders", array("order_status" => "cancellation requested"), array("order_id" => $order_id));
      $db->update("milestone", array("milestone_status" => "cancellation requested", "order_id" => $order_id), array("milestone_id" => $milestone_id));
      echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";
    }
  }
}
?>


<?php
if (isset($_POST['submit_extend_request'])) {
  $extend_delivery_message = $input->post('extend_delivery_message');
  $extend_reason = $input->post('extend_reason');
  $order_time_extend = $input->post('order_time_extend');
  $current_datetime = date("Y-m-d H:i:s"); // Current date and time in 'YYYY-MM-DD HH:MM:SS' format


  // Calculate the difference in days between today and the selected date
  $today = new DateTime(); // Today's date
  $selected_date_obj = new DateTime($order_time_extend); // Selected date from form input
  $interval = $today->diff($selected_date_obj);
  $order_duration_extend = $interval->days; // Difference in days




  $last_update_dated = date("h:i: M d, Y");
  if ($seller_id == $login_seller_id) {
    $receiver_id = $buyer_id;
  } else {
    $receiver_id = $seller_id;
  }

  $insert_extention_conversation = $db->insert("order_conversations", array("order_id" => $order_id, "sender_id" => $login_seller_id, "message" => $extend_delivery_message, "date" => $last_update_dated, "reason" => $extend_reason, "status" => "extendTimeRequest"));
  // echo "hello";

  if ($insert_extention_conversation) {
    $insert_extend_notification = $db->insert("notifications", array("receiver_id" => $receiver_id, "sender_id" => $login_seller_id, "order_id" => $order_id, "reason" => "extendTimeRequest", "date" => $n_date, "status" => "unread"));
    // echo "hello2";
    /// sendPushMessage Starts
    $notification_id = $db->lastInsertId();
    sendPushMessage($notification_id);
    /// sendPushMessage Ends

    $update_order = $db->update("orders", array("order_status" => "Extend Delivery Request"), array("order_id" => $order_id));
    $db->update("milestone", array("milestone_status" => "Extend Delivery Request", "order_id" => $order_id), array("milestone_id" => $milestone_id));
    echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";

    // $insert_extent_delivery = $db->insert("order_extras", array("order_id" => $order_id, 'name' => $extend_delivery, "price" => '25'));

    $insert_extension_time = $db->insert("delivery_extension", array(
      "extend_delivery_message" => $extend_delivery_message,
      "extend_reason" => $extend_reason,
      "order_duration_extend" => $order_duration_extend,
      "order_time_extend" => $order_time_extend,
      "order_date_extend" => $current_datetime,
      "order_number" => $order_number
    ));
    // $insert_extension_time =  $db->insert("delivery_extension", array("extend_delivery_message" => $extend_delivery_message, "extend_reason" => $extend_reason, "order_duration_extend" => $order_duration_extend, "order_time_extend" => $order_time_extend, "order_id" => $order_id));

    // mail send to buyer for notification on mobile
    $data = [];
    $data['template'] = "delivery_extent_req_to_buyer";
    $data['to'] = "kumshubham25@gmail.com";
    $data['subject'] = "$site_name: Delivery extention request";
    $data['user_name'] = $seller_user_name;
    $data['extend_reason'] = $extend_reason;
    $data['order_time_extend'] = $order_time_extend . " " . $order_date_extend;
    $data['order_duration_extend'] = $order_duration_extend;
    $data['order_number'] = $order_number;
    $data['extend_delivery_message'] = $extend_delivery_message;
    $data['link_url'] = "$site_url/order_details?order_id=$order_id";
    // Send the email
    send_mail($data);
  } else {
    echo "bye";
  }
} else {
  // echo "try again!";
}

?>
<?php
if (isset($_POST['request_milestone_create_btn'])) {
  $milestone_quantity = $_POST['milestone_quantity'];
  $amount_expected = $_POST['amount_expected'];
  $duration_milestone_expect = $_POST['duration_milestone_expect'];
  $decription_miles_expect = $_POST['decription_miles_expect'];
  $order_id = $order_id; // Assumed this is coming from an external source like session, etc.
  $order_number = $order_number; // Same assumption as above

  // Check if a milestone request already exists for this order
  $check_if_already = $db->select("request_milestone_create", array("order_id" => $order_id, "order_number" => $order_number));

  // If no previous request found, proceed to insert
  if ($check_if_already->rowCount() == 0) {
    $insert_request = $db->insert(
      "request_milestone_create",
      array(
        "milestone_quantity" => $milestone_quantity,
        "amount_expected" => $amount_expected,
        "duration_milestone_expect" => $duration_milestone_expect,
        "decription_miles_expect" => $decription_miles_expect,
        "order_id" => $order_id,
        "order_number" => $order_number,
        "rmc_status" => 'Create Milestone Requested' // Explicitly set status
      )
    );

    if ($insert_request) {
      $insert_cancelled_notification = $db->insert("notifications", array("receiver_id" => $receiver_id, "sender_id" => $login_seller_id, "order_id" => $order_id, "reason" => "milestone_create_req", "date" => $n_date, "status" => "unread"));
      $notification_id = $db->lastInsertId();
      sendPushMessage($notification_id);
      echo "<script>alert('Your Request Submitted Successfully');</script>";
    } else {
      echo "<script>alert('Request Submission Failed! Try Again.');</script>";
    }
  } else {
    // Fetch existing request and handle the status
    $fetch_data_req = $check_if_already->fetch();
    $rmc_status = $fetch_data_req->rmc_status;

    if ($rmc_status == "Create Milestone Requested") {
      echo "<script>alert('You already requested a milestone');</script>";
    } else if ($rmc_status == "milestone_req_rejected") {
      $insert_request = $db->update(
        "request_milestone_create",
        array(
          "milestone_quantity" => $milestone_quantity,
          "amount_expected" => $amount_expected,
          "duration_milestone_expect" => $duration_milestone_expect,
          "decription_miles_expect" => $decription_miles_expect,
          "order_id" => $order_id,
          "order_number" => $order_number,
          "rmc_status" => 'Create Milestone Requested' // Explicitly set status
        )
      );

      if ($insert_request) {
        $insert_cancelled_notification = $db->insert("notifications", array("receiver_id" => $receiver_id, "sender_id" => $login_seller_id, "order_id" => $order_id, "reason" => "milestone_create_req", "date" => $n_date, "status" => "unread"));
        $notification_id = $db->lastInsertId();
        sendPushMessage($notification_id);
        echo "<script>alert('Your Request Submitted Successfully');</script>";
      } else {
        echo "<script>alert('Request Submission Failed! Try Again.');</script>";
      }
    } else {
      echo "<script>alert('Your previous milestone request has been accepted');</script>";
    }
  }
}
?>
<?php if ($order_status == "progress" or $order_status == "revision requested" or $order_status == "extendTimeDeclined" or $order_status == "extendTimeAccepted") { ?>
  <?php if ($seller_id == $login_seller_id) { ?>
    <h2 class="text-center mt-4" id="countdown-heading">
      This Order Needs To Be Delivered Before This Day/Time:
    </h2>
  <?php } elseif ($buyer_id == $login_seller_id) { ?>
    <h2 class="text-center mt-4" id="countdown-heading">
      Your Order Should Be Ready On or Before This Day/Time:
    </h2>
  <?php } ?>
  <div id="countdown-timer">
    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 countdown-box">
        <p class="countdown-number" id="days"></p>
        <p class="countdown-title">Day(s)</p>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 countdown-box">
        <p class="countdown-number" id="hours"></p>
        <p class="countdown-title">Hours</p>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 countdown-box">
        <p class="countdown-number" id="minutes"></p>
        <p class="countdown-title">Minutes</p>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 countdown-box">
        <p class="countdown-number" id="seconds"></p>
        <p class="countdown-title">Seconds</p>
      </div>
    </div>
  </div>
<?php } ?>

<style>
  .order_cancelation_section_div {
    border: 1px solid transparent;
    width: 100%;
    height: 25rem;
    display: flex;
  }

  .order_cancelation_section_form {
    /* border: 1px solid lightcoral; */
    width: 40%;
    margin: auto;
    height: 17rem;
    padding: 1rem;
    box-shadow: 0px 0px 15px lightgray;
    border-radius: 10px;
  }

  .order_cancelation_section_input {
    width: 100%;
  }
</style>

<?php if ($buyer_id == $login_seller_id) { ?>
  <div class="order_cancelation_section_div">
    <div class="order_cancelation_section_form">
      <h2 class="text-center mb-4">Order Cancellation</h2>
      <form method="post">
        <textarea name="order_cancel_reason" class="order_cancelation_section_input" placeholder="Please be as detailed as possible..." rows="5" class="form-control" required></textarea>
        <button type="submit" name="order_cancelled_submission">Order Cancel</button>
      </form>
    </div>
  </div>


  <?php
  if (isset($_POST['order_cancelled_submission'])) {
    $order_cancel_reason = $input->post('order_cancel_reason');
    $last_update_dated = date("h:i: M d, Y");
    if ($seller_id == $login_seller_id) {
      $receiver_id = $buyer_id;
    } else {
      $receiver_id = $seller_id;
    }

    $insert_cancelled_conversation = $db->insert("order_conversations", array("order_id" => $order_id, "sender_id" => $login_seller_id, "message" => $order_cancel_reason, "date" => $last_update_dated, "reason" => "order_cancelled", "status" => "cancelled"));
    // echo "hello";

    if ($insert_cancelled_conversation) {
      $insert_cancelled_notification = $db->insert("notifications", array("receiver_id" => $receiver_id, "sender_id" => $login_seller_id, "order_id" => $order_id, "reason" => "order_cancelled", "date" => $n_date, "status" => "unread"));
      // echo "hello2";
      /// sendPushMessage Starts
      $notification_id = $db->lastInsertId();
      sendPushMessage($notification_id);
      /// sendPushMessage Ends

      $update_order = $db->update("orders", array("order_status" => "cancelled"), array("order_id" => $order_id));
      echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";
    }
  }
  ?>
  <!-- buyer instruction  -->
  <?php if (!empty($buyer_instruction)) { ?>
    <div class="card mb-3 mt-3">
      <!--- card mb-3 mt-3 Starts --->
      <div class="card-header">
        <h5>Getting Started</h5>
      </div>
      <div class="card-body">
        <h6>
          <b><?= $seller_user_name; ?></b>
          requires the following information in order to get started:
        </h6>
        <p>
          <?= $buyer_instruction; ?>
        </p>
      </div>
    </div>
    <!--- card mb-3 mt-3 Ends --->
  <?php } ?>
<?php } ?>
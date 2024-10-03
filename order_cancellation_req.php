<style>
  .order_cancelation_section_div {
    border: 1px solid transparent;
    width: 100%;
    /* height: 25rem; */
    /* display: none; */
  }

  .order_cancelation_section_form {
    /* border: 1px solid lightcoral; */
    width: 40%;
    margin: auto;
    /* height: 17rem; */
    padding: 1rem;
    box-shadow: 0px 0px 15px lightgray;
    border-radius: 10px;
  }

  .order_cancelation_section_input {
    width: 100%;
  }

  .order_cancellation_btn {
    padding: 10px 20px;
    border: none;
    background-color: grey;
    color: white;
    border-radius: 3px;
    margin: 10px auto;
  }
</style>

<?php if ($buyer_id == $login_seller_id) { ?>
  <div class="order_cancelation_section_div" id="order_cancelation_action">
    <div class="order_cancelation_section_form">
      <h2 class="text-center mb-4">Order Cancellation</h2>
      <form method="post">
        <textarea name="order_cancel_reason" class="order_cancelation_section_input" placeholder="Please be as detailed as possible..." rows="5" class="form-control" required></textarea>
        <div class="w-100 d-flex"><button type="submit" name="order_cancelled_submission" class="order_cancellation_btn">Order Cancel</button>
        </div>
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

      $update_order = $db->update("orders", array("order_status" => "cancelled", "order_id" => $order_id), array("order_id" => $order_id));
      $db->update("milestone", array("milestone_status" => "cancelled", "order_id" => $order_id), array("milestone_id" => $milestone_id));
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
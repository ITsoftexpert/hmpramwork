<style>
  .order_cancelation_section_div {
    border: 1px solid transparent;
    width: 100%;
    /* height: 25rem; */
    display: none;
  }

  .order_cancelation_section_form {
    margin: auto;
    padding: 1rem 1.5rem;
    box-shadow: 0px 0px 15px lightgray;
    border-radius: 10px;
  }

  .order_cancelation_section_input {
    width: 100%;
    padding: 8px 12px;
  }

  .order_cancellation_btn {
    padding: 10px 20px;
    border: none;
    background-color: #ff3b3b;
    color: white;
    border-radius: 3px;
    margin: 10px auto;
  }

  .closeFormOcancelbtn {
    border: 1px solid grey;
    float: inline-end;
    padding: 1px 5px;
    color: white;
    background-color: grey;
    border-radius: 3px;
  }
</style>

<?php if ($buyer_id == $login_seller_id) { ?>
  <div class="order_cancelation_section_div" id="order_cancelation_action">
    <div class="order_cancelation_section_form">
      <h3 class="text-center mb-4">Order Cancellation <span class="closeFormOcancelbtn" onclick="closeFormOcancel()"> X </span></h3>
      <h5> Your order delivery time has expired. Do you want to cancel this order?</h5>
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

    $insert_cancelled_conversation = $db->insert("order_conversations", array("order_id" => $order_id, "sender_id" => $login_seller_id, "message" => $order_cancel_reason, "date" => $last_update_dated, "reason" => "order_cancelled", "status" => "progress"));
    // echo "hello";

    if ($insert_cancelled_conversation) {
      $insert_cancelled_notification = $db->insert("notifications", array("receiver_id" => $receiver_id, "sender_id" => $login_seller_id, "order_id" => $order_id, "reason" => "order_cancelled", "date" => $n_date, "status" => "unread"));
      // echo "hello2";
      /// sendPushMessage Starts
      $notification_id = $db->lastInsertId();
      sendPushMessage($notification_id);
      /// sendPushMessage Ends

      $update_order = $db->update("orders", array("order_status" => "cancellation_request", "order_id" => $order_id), array("order_id" => $order_id));
      $db->update("milestone", array("milestone_status" => "cancellation_request", "order_id" => $order_id), array("milestone_id" => $milestone_id));
      echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";


      if ($update_order) {
        $get_order = $db->select("orders", array("order_id" => $order_id));
        $row_order = $get_order->fetch();
        $seller_id = $row_order->seller_id;
        $buyer_id = $row_order->buyer_id;
        $order_price = $row_order->order_price;
        $order_number = $row_order->order_number;
      }
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

<script>
  function closeFormOcancel() {
    var order_cancelation_action = document.getElementById("order_cancelation_action").style.display = "none";
  }
</script>
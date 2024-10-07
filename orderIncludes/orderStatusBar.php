<?php
if ($order_status == "Delivery accepted") {
  include_once("realease_pay_ment.php");
}
?>


<?php if ($order_status == "pending" or $order_status == "progress" or $order_status == "delivered" or $order_status == "revision requested" or $order_status == "cancellation requested" or $order_status) { ?>
  <div id="order-status-bar" style="margin-top: 151px;">
    <div class="container">
      <div class="row">
        <div class="col-md-10 offset-md-1">
          <h5 class="float-left mt-2">
            <?php
            // Check if '-' exists in the order_number
            if (strpos($order_number, '-') !== false) {

              $display_order_number = explode("-", $order_number)[0];
            } else {

              $display_order_number = $order_number;
            }
            ?>
            <span class="border border-success rounded p-1">Order: #<?= $display_order_number; ?></span>
            <!-- order name same for respected milestone -->



          </h5>
          <h5 class="float-right mt-2">
            Status: <span class="text-muted">
              <?php if ($order_status == "progress") {
                echo "In";
              } ?>
              <?= ucwords($order_status); ?>
            </span>
          </h5>
        </div>
      </div>
    </div>
  </div>
<?php } elseif ($order_status == "cancelled") { ?>
  <div id="order-status-bar" style="margin-top: 151px;">
    <div class="container">
      <div class="row">
        <div class="col-md-10 offset-md-1">
          <h5 class="float-left mt-2">
            <i class="fa fa-lg fa-times-circle text-danger"></i> Order Cancelled, Payment Has Been Refunded To Buyer.
          </h5>
          <h5 class="float-right mt-2">
            Status: <span class="text-muted">Cancelled</span>
          </h5>
        </div>
      </div>
    </div>
  </div>

<?php } elseif ($order_status == "completed") { ?>
  <div id="order-status-bar" class="completed text-white" style="margin-top: 151px;">
    <div class="row">
      <!--  <div class="col-md-10 offset-md-1"> -->
      <div class="container">
        <div class="col-md-10 offset-md-1">
          <?php if ($seller_id == $login_seller_id) { ?>
            <h5 class="float-left mt-2">
              <i class="fa fa-lg fa-check-circle"></i> Order Delivered. You Earned <?= showPrice($seller_price); ?>
            </h5>
            <h5 class="float-right mt-2">Status: Completed</h5>
          <?php } elseif ($buyer_id == $login_seller_id) { ?>
            <h5 class="float-left mt-2">
              <i class="fa fa-lg fa-check-circle"></i> Delivery Submitted
            </h5>
            <h5 class="float-right mt-2">Status: Completed</h5>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
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



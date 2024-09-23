<div class="box" align="center">
  <div class="container" style="max-width: 632px;margin: 0 auto;">
    <div class="row bg-white o_sans" style="padding-top: 0px; padding-bottom: 35px;">

      <div class="icon-container">
      <div class="icon" align="center">
          <img src="<?= img_url("decline-process-icon.png"); ?>" width="48" height="48" style="background-color: #ffffff; border:2px solid black; padding:10px; border-radius:50px;">
        </div>
      </div>

      <h2 class="o_heading">Delivery Extension Request Rejected</h2>

      <p class="text-muted">
        Hello, <?= $data['user_name']; ?>. Your request for an extension on the delivery of the order has been <strong style="color:red;">Rejected</strong>.
      </p>

      <div class="btn btn-green o_heading o_text" style="background-color: <?= $site_color;?>; width: 200px;">
        <a class="o_text-primary" href="<?= $data['link_url']; ?>" style="padding-left: 12px; padding-right: 12px;">
          View Order Details
        </a>
      </div>

      <p class="text-muted">You can check the updated status and timeline of your order using the link above.</p>

    </div>
  </div>
</div>

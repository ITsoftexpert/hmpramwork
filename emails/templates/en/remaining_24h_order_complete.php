<div class="box" align="center">
    <div class="container" style="max-width: 632px; margin: 0 auto; font-family: Arial, sans-serif;">
        <div class="row o_sans" style="padding-top:0px; padding-bottom:5px; background-color: white;">

            <div class="icon-container">
                <div class="icon bg-white" align="center">
                    <img src="<?= img_url("warning.png"); ?>" width="48" height="48" alt="Alert Icon" style="border:2px solid black; background-color:black; padding:12px; border-radius:50px;">
                </div>
            </div>

            <h2 class="o_heading o_mb-xxs" style="color:black;">Deadline Approaching: 24 Hours Remaining</h2>

            <p class="o_mb-md" style="color: grey;">
                This is a reminder that you have 24 hours remaining to complete your order <strong>#<?= $data['order_number']; ?></strong>. Please ensure all work is finalized and submitted before the deadline to avoid any penalties or delays.
            </p>

            <p class="o_mb-md" style="color: grey;">
                If you need more time or have any issues, please contact the buyer as soon as possible.
            </p>

            <div class="btn btn-white o_heading o_text">
                <a class="o_text-primary" href='<?= $data['link_url']; ?>'
                 style="color: #fff; background-color: <?= $site_color; ?>; border-radius: 4px;margin-bottom: 2rem;">
                    View Order Details
                </a>
            </div>

            <p class="o_mb-md" style="color: grey;">
                Thank you for your attention to this matter.<br>
                <strong><?= $site_name; ?> Team</strong>
            </p>

        </div>
    </div>
</div>
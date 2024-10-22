<div class="box" align="center" style="font-family: Arial, sans-serif; color: #333;">
    <div class="container" style="max-width: 632px; margin: 0 auto; padding: 0px; color: #000;">
        <div class="row bg-white" style="padding-top: 2px; padding-bottom: 24px; text-align: center;">

            <h4 style="color:black; margin-top: 0px;"><strong>Subject:</strong> Action Required: Order Cancellation Request from <?= $data['role']; ?></h4>
            <h5 style="font-size:18px; color:black; text-align: left; font-weight: 200;">Dear Seller,</h5>
            <p style="font-size:18px; color:black; text-align: left; font-weight: 200;">You have received an order cancellation request from a <?= $data['role']; ?>. Please review the details below:</p>
            <p style="font-size:18px; color:black; text-align:left;"><strong>Request date: <?= $data['request_date']; ?></strong></p>
            <p style="font-size:18px; color:black; text-align: left; font-weight: 200;">
                Please respond to this cancellation request at your earliest convenience. You have <strong><?= $data['time_left']; ?></strong> to reply, or the order will be automatically canceled after three days from the request date.
            </p>

            <p style="font-size:18px; color:black; font-weight: 200;">Thank you for your prompt attention to this matter!</p>

            <p style="font-size:18px; color:black; font-weight: 200;">
                You can view the order details here:
                <br><br>
                <a href="<?= $data['project_post_url']; ?>" style="color: #007BFF; text-decoration: none;">
                    <button style="background-color: <?= $site_color; ?>; color: white; padding: 12px 20px; border: none; border-radius: 3px;">View order details</button>
                </a>
            </p>
        </div>
    </div>
</div>
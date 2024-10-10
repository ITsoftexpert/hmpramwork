<div class="box" align="center" style="font-family: Arial, sans-serif; color: #333;">
    <div class="container" style="max-width: 632px; margin: 0 auto; padding: 0px;">
        <div class="row bg-white" style="padding-top: 2px; padding-bottom: 24px; text-align: center;">

            <div class="icon-container">
                <div class="icon" style="border: 1px solid #000; padding: 18px; display: inline-block;">
                    <img src="<?= img_url('paymentreleased.png'); ?>" width="48" height="48" alt="Payment Icon">
                </div>
            </div>

            <h2 style="font-size: 24px; color: #333;"><?= $data['subject']; ?></h2>
            <p style="color: #777; font-size: 16px;">
                <strong>Subject: </strong> The buyer has successfully released the payment for your task <strong>" <?= $data['task_title']; ?> "</strong>.
            </p>
            <p style="color: #777; font-size: 16px;">
                <strong>Task Amount:</strong> $<?= $data['task_amount']; ?><br>
                <strong>Order Number:</strong> <?= $data['order_number']; ?>
            </p>
            <p style="color: #777; font-size: 16px;">
                You can view more details by visiting your order page.
            </p>
            <div style="margin-top: 20px;">
                <a href="<?= $site_url; ?>/order/<?= $order_number; ?>" style="display: inline-block; background-color: <?= $site_color; ?>; color: #ffffff; padding: 12px 24px; text-decoration: none; font-size: 16px; width:200px;">
                    View Order
                </a>
            </div>
        </div>
    </div>
</div>
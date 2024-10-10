<div class="box" align="center" style="font-family: Arial, sans-serif; color: #333;">
    <div class="container" style="max-width: 632px; margin: 0 auto; padding: 0px;">
        <div class="row bg-white" style="padding-top: 25px; padding-bottom: 24px; text-align: center;">

            <div class="icon-container">
                <div class="icon" style="border: 1px solid #000; padding: 18px; display: inline-block;">
                    <img src="<?= img_url('review.png'); ?>" width="48" height="48" alt="Review Icon">
                </div>
            </div>

            <h2 style="font-size: 24px; color: #333;"><?= $data['subject']; ?></h2>
            <p style="color: #777; font-size: 16px;">
                <strong>Subject: </strong> We'd love to hear about your experience with the buyer.               
            </p>
            <p style="color: #777; font-size: 16px;">
                We’d love to hear your feedback about the buyer's performance for the order <strong>#<?= $data['order_number']; ?></strong>.
            </p>
            <p style="color: #777; font-size: 16px;">
                Please take a moment to leave a review by clicking the link below. Your feedback helps improve our marketplace for both buyers and sellers.
            </p>
            <div style="margin-top: 20px;">
                <a href="<?= $data['link_url']; ?>" style="display: inline-block; background-color: <?= $site_color; ?>; color: #ffffff; padding: 12px 24px; text-decoration: none; font-size: 16px; width:200px;">
                    Leave Your Review
                </a>
            </div>
        </div>
    </div>
</div>

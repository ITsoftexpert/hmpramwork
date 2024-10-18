<!-- Email Template Start -->
<div class="box" align="center" style="font-family: Arial, sans-serif; color: #333;">
    <div class="container" style="max-width: 632px; margin: 0 auto; padding: 0px;">
        <div class="row bg-white" style="padding-top: 2px; padding-bottom: 24px; text-align: center;">

            <h4 style="margin-top: 0px;"> <strong>Subject: </strong> Recent Released Jobs on <strong> "Hiremyprofile.com"! </strong></h4>
            <h5 style="text-align: left; font-weight: 200;">Thank you for choosing <strong> "Hiremyprofile.com"! </strong> Below are the latest job postings matching your criteria:</h5>


            <!-- Dynamic Job Listings Start -->
            <!-- Loop through the jobs array -->
            <?php foreach ($data['jobs'] as $job): ?>
                <div class="job-card" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; text-align: left;">
                    <div class="job-header" style="display: flex; align-items: center;">
                        <div class="avatar" style="
                width: 50px;
                height: 20px;
                background-color: <?= $job['bg_color'] ?>;
                color: white;
                font-size: 24px;
                font-weight: bold;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 50%;
                margin-right: 15px;
               
            ">
                            <?= strtoupper(substr($job['request_title'], 0, 1)) ?>
                        </div>
                        <div class="job-details">
                            <!-- Limit title to 70 characters -->
                            <h4 style="margin: 0;">
                                <?= htmlspecialchars(strlen($job['request_title']) > 70 ? substr($job['request_title'], 0, 70) . '...' : $job['request_title']) ?>
                            </h4>
                            <p style="margin: 0; font-size: 14px;">Budget: $<?= htmlspecialchars($job['request_budget']) ?> | Delivery Time: <?= htmlspecialchars($job['delivery_time']) ?> days</p>
                        </div>
                    </div>
                    <!-- Limit description to 70 characters -->
                    <p style="font-size: 14px; margin-bottom:0px;">
                        <?= htmlspecialchars(strlen($job['request_description']) > 70 ? substr($job['request_description'], 0, 70) . '...' : $job['request_description']) ?>
                    </p>
                    <a href="<?= $data['project_post_url']; ?>" style="color: #007BFF; text-decoration: none; font-size:13px;">View Job Details</a>
                </div>
            <?php endforeach; ?>
            <!-- Dynamic Job Listings End -->


            <a href="<?= $data['project_post_url']; ?>"><button style="background-color: <?= $site_color; ?>; color: white; padding: 12px 20px; border: none; border-radius: 3px;">View More Jobs</button></a>
        </div>
    </div>
</div>
<!-- Email Template End -->
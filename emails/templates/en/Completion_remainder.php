<div class="box" align="center">
  <div class="container" style="max-width: 632px;margin: 0 auto;">
    <div class="row bg-white o_sans" style="padding-top: 0px; padding-bottom: 35px;">

      <div class="icon-container">
        <div class="icon" align="center" style="background-color: #ffffff; border:1px solid black;">
          <img src="<?= img_url('profile_completion_remainder_required.png'); ?>" width="48" height="48">
        </div>
      </div>

      <h2 class="o_heading">Profile Completion Reminder</h2>

      <p class="text-muted">
        Hi <?= $data['user_name']; ?>,
      </p>

        <p> We noticed that your profile is incomplete. Completing your profile will help you unlock the full potential of our platform. Please take a moment to update your details and enjoy all the features available to you.</p>
      <p class="text-muted">
        <strong>Details : </strong><?= $data['inform']; ?><br>
        <strong>Additional Information : </strong><?= $data['matter']; ?>
      </p>

      <div class="btn btn-green o_heading o_text" style="background-color: <?= $site_color;?>; width: 200px;">
        <a class="o_text-primary" href='<?= $data['link_visitng']; ?>' style="padding-left: 12px; padding-right: 12px;">
          Complete Your Profile
        </a>
      </div>

    </div>
  </div>
</div>

<div class="box" align="center" style="font-family: Arial, sans-serif; color: #333;">
    <div class="container" style="max-width: 632px; margin: 0 auto; padding: 0px;">
        <div class="row bg-white" style="padding-top: 25px; padding-bottom: 24px; text-align: center;">

            <div class="icon-container">
                <p style="margin-top: 0px;margin-bottom: 0px;"> <img class="o_br-max" src="<?= img_url("person-circle.png"); ?>" width="48" height="48" style="max-width: 48px;-ms-interpolation-mode: bicubic;vertical-align: middle;border: 0;line-height: 100%;height: auto;outline: none;text-decoration: none;border-radius: 96px;"> <strong class="o_text-dark" style="color: #242b3d;"><?= $data['sender_user_name']; ?></strong> <span class="o_text-default o_text-xs" style="font-size: 14px;line-height: 21px;"> <span class="o_text-light" style="color: #82899a;"> ●</span> Sender</span></p>
                <p class="o_text-xxs o_text-light" style="font-size: 12px;line-height: 19px;color: #82899a;margin-top: 0px;margin-bottom: 0px;"><?= $data['message_date']; ?></p>

            </div>

            <h2 style="font-size: 24px; color: #333;"><?= $data['subject']; ?></h2>
            <p style="color: #777; font-size: 16px;">
                <strong>Subject: </strong> You have a unread message.
            </p>            
            <div style="margin-top: 20px;">
                <a class="o_text-white" href='<?= $site_url; ?>/conversations/inbox?single_message_id=<?= $data['message_group_id']; ?>' style="text-decoration: none;outline: none;color: #ffffff;display: block;padding: 12px 24px;mso-text-raise: 3px;">Reply to Message</a>
            </div>
        </div>
    </div>
</div>
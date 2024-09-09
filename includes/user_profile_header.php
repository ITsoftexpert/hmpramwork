<?php
$select_seller = $db->select("sellers", array("seller_user_name" => $get_seller_user_name));
$row_seller = $select_seller->fetch();
$seller_id = $row_seller->seller_id;
$seller_user_name = $row_seller->seller_user_name;
$seller_image = getImageUrl2("sellers", "seller_image", $row_seller->seller_image);
$seller_cover_image = $row_seller->seller_cover_image;


if (empty($seller_cover_image)) {
  $seller_cover_image = "images/user-background.jpg";
} else {
  $seller_cover_image = getImageUrl2("sellers", "seller_cover_image", $seller_cover_image);
}
$seller_country = $row_seller->seller_country;
$seller_headline = $row_seller->seller_headline;
$seller_about = $row_seller->seller_about;
$seller_skills = $row_seller->skills;
$seller_level = $row_seller->seller_level;
$seller_rating = $row_seller->seller_rating;
$seller_register_date = $row_seller->seller_register_date;
$seller_recent_delivery = $row_seller->seller_recent_delivery;

$seller_status = $row_seller->seller_status;
$select_buyer_reviews = $db->select("buyer_reviews", array("review_seller_id" => $seller_id));
$count_reviews = $select_buyer_reviews->rowCount();


if (!$count_reviews == 0) {
  $rattings = array();
  while ($row_buyer_reviews = $select_buyer_reviews->fetch()) {
    $buyer_rating = $row_buyer_reviews->buyer_rating;
    array_push($rattings, $buyer_rating);
  }
  $total = array_sum($rattings);
  @$average = $total / count($rattings);
  $average_rating = substr($average, 0, 1);
} else {
  $average = "0";
  $average_rating = "0";
}

$level_title = $db->select("seller_levels_meta", ["level_id" => $seller_level, "language_id" => $siteLanguage])->fetch()->title;
$count_proposals = $db->count("proposals", ["proposal_seller_id" => $seller_id, "proposal_status" => 'active']);

if (isset($login_seller_id)) {
  $follow_data = $db->select('follow_following_unfllow', array('followed_id' => $seller_id, 'follower_id' => $login_seller_id, 'status' => 'active'));
  $follow_tbl_data = $follow_data->fetch();
}
// print("<pre>" . print_r($follow_tbl_data, true) . "</pre>");
// exit;
$follow_id = isset($follow_tbl_data) && $follow_tbl_data ? $follow_tbl_data->id : 0;

?>
<style>
  .user-header-mt {
    margin-top: 150px;
  }

  @media screen and (min-width: 1024px) and (max-width: 1100px) {
    .user-header-mt {
        margin-top: 176px;
    }
}
  @media screen and  (max-width: 900px) {
    .user-header-mt {
        margin-top: 173px;
    }
}
  

  
</style>
<div class="col-md-12 user-header user-header-mt pl-5 pr-5 pt-5 pb-5">
  <!-- style="background: url(<?= $seller_cover_image; ?>);" -->
  <?php if (isset($_SESSION['seller_user_name'])) { ?>
    <?php if ($_SESSION['seller_user_name'] == $seller_user_name) { ?>
      <a href="settings?profile_settings" class="btn btn-edit btn-success"><i class="fa fa-pencil"></i> Edit&nbsp;</a>
    <?php } ?>
  <?php } ?>
  <div class="profile-image float-lg-left flaot-md-left float-none mr-4">
    <?php if (!empty($seller_image)) { ?>
      <img src="<?= $seller_image; ?>" class="rounded-circle">
    <?php } else { ?>
      <img src="user_images/empty-image.png" class="rounded-circle">
    <?php } ?>
    <?php if ($seller_level == 2) { ?>
      <img src="images/level_badge_1.png" class="level_badge">
    <?php } ?>
    <?php if ($seller_level == 3) { ?>
      <img src="images/level_badge_2.png" class="level_badge">
    <?php } ?>
    <?php if ($seller_level == 4) { ?>
      <img src="images/level_badge_3.png" class="level_badge">
    <?php } ?>
  </div>
  <div class="content-bar mt-3" style="background-color: #00000052;">
    <h1> Hi, I'm <?= ucfirst($seller_user_name); ?> </h1>
    <span class="headline">
      <?= $seller_headline; ?>
    </span>

    <div class="star-rating">
      <?php
      for ($seller_i = 0; $seller_i < $average_rating; $seller_i++) {
        echo " <i class='fa fa-star'></i> ";
      }
      for ($seller_i = $average_rating; $seller_i < 5; $seller_i++) {
        echo " <i class='fa fa-star-o'></i> ";
      }
      ?>
      <span class="text-white m-1"><strong>
          <?php printf("%.1f", $average); ?></strong> (<?= $count_reviews; ?>)</span>
      <?php if (!empty($seller_country)) { ?>
        <span class="text-white">
          <i class="fa fa-map-marker m-1"></i> <?= $seller_country; ?>
        </span>
      <?php } ?>
    </div>
    <?php if (check_status($seller_id) == "Online") { ?>
      <span class="user-is-online">
        <span class="h6"><i class="fa fa-circle"></i></span>
        <span><?= check_status($seller_id); ?></span>
      </span>
    <?php } ?>
  </div>
  <div class="content-bar">
    <?php if ($count_proposals != 0) { ?>
      <?php if (!isset($_SESSION['seller_user_name'])) { ?>
        <a class="btn btn-success mt-3" href="login.php"> Contact <small>(<?= $seller_user_name; ?>)</small></a>

      <?php } else { ?>
        <?php if ($_SESSION['seller_user_name'] != $seller_user_name) { ?>
          <a class="btn btn-success mt-3" href="<?= $site_url; ?>/conversations/message?seller_id=<?= $seller_id ?>">
            Contact <small>(<?= $seller_user_name; ?>)</small>

          </a>

          <?php if ($follow_id) { ?>
            <button class="follow btn btn-primary mt-3" id="unfollow" value="<?= $seller_id ?>">Unfollow</button>

            <!--<button class="follow btn btn-success mt-3" id="follow">
                    <span class="msg-following" id="Following">Following</span>
                  </button>-->
          <?php } else { ?>
            <button class="follow btn btn-outline-success mt-3 bg-secondary text-light" id="follow" value="<?= $seller_id ?>">Follow</button>
          <?php } ?>

          <button class="btn btn-outline-success mt-3 text-light bg-secondary">
            <i class="fa fa-thumbs-o-up" data-toggle="tooltip" data-placement="top" title="Recommend"></i> 0 recommendation
          </button>
          <!-- <button class="btn btn-success mt-3">
          <i class="fa fa-thumbs-up" data-toggle="tooltip" data-placement="top" title="Recommend"></i> 9 recommendation
        </button> -->

        <?php } ?>
      <?php } ?>
    <?php } else { ?>
      <br><br>
      <?= (empty($seller_headline) and check_status($seller_id) != "Online") ? "<br><br>" : ""; ?>
    <?php } ?>
  </div>

</div>

<div class="col-md-12 user-status">
  <ul>
    <li>
      <i class="fa fa-user"></i>
      <strong><?= $lang['user_profile']['member_since']; ?> </strong> <?= $seller_register_date; ?>
    </li>
    <?php if ($seller_recent_delivery != "none") { ?>
      <li>
        <i class="fa fa-truck fa-flip-horizontal"></i>
        <strong><?= $lang['user_profile']['recent_delivery']; ?> </strong> <?= $seller_recent_delivery; ?>
      </li>
    <?php } ?>
    <?php if ($seller_level != 1) { ?>
      <li>
        <i class="fa fa-bars"></i>
        <strong><?= $lang['user_profile']['seller_level']; ?> </strong> <?= $level_title; ?>
      </li>
    <?php } ?>
  </ul>
</div>
<!--Follow Button CSS Start-->
<style>
  button .msg-follow,
  button .msg-following,
  button .msg-unfollow {
    display: none;
    height: 34px;
    width: 112px;
    /*border: 1px solid #00cedc;*/
  }

  .following {
    background: yellowgreen;
    border: 0px;
  }

  button .msg-follow {
    display: inline;
  }

  button.following .msg-follow {
    display: none;
  }

  button.following .msg-following {
    display: inline;
  }

  button.following:not(.wait):hover .msg-following {
    display: none;
  }

  button.following:not(.wait):hover .msg-unfollow {
    display: inline;
  }
</style>

<script>
  /*$('button').click(function(){
        var $this = $(this);
        $this.toggleClass('following')
        if($this.is('.following')){
            $this.addClass('wait');

     //       alert("<?= $seller_id . $login_seller_id ?>");

        }
        var dataString = {
        "seller_id": <?= $seller_id ?>,
        "login_seller_id" : <?= $login_seller_id ?>
        };

        /!*dataString['seller_id'] = [<?= $seller_id ?>];
        dataString['login_seller_id'] = [<?= $login_seller_id ?>];
*!/
       // dataString = new array('seller_id'=><?= $seller_id ?>, 'login_seller_id'=><?= $login_seller_id ?>) ; // array?
        var jsonString = JSON.stringify(dataString);

       /!* $.ajax({
            type: "POST",
            url: "following.php",
            data: {data : jsonString},
            cache: false,

            success: function(response){
               // alert(response);
                console.log(response);

            }
        });*!/


    }).on('mouseleave',function(){
        $(this).removeClass('wait');
    })*/
</script>
<!--Follow button css ends-->
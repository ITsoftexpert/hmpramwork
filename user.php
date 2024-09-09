<?php
session_start();
require_once("includes/db.php");
require_once("functions/functions.php"); 
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (isset($_SESSION['seller_user_name'])) {

  $login_seller_user_name = $_SESSION['seller_user_name'];
  $select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
  $row_login_seller = $select_login_seller->fetch();
  $login_seller_id = $row_login_seller->seller_id;
  if (isset($_GET['delete_language'])) {
    // echo "hello";
    $delete_language_id = $input->get('delete_language');
    $delete_language = $db->delete("languages_relation", array("relation_id" => $delete_language_id, "seller_id" => $login_seller_id));
    if ($delete_language->rowCount() == 1) {
     echo "<script>alert('One Language has been deleted.')</script>";
     echo "<script> window.open('$login_seller_user_name','_self') </script>";
      
    } else {
      echo "<script> window.open('$login_seller_user_name','_self') </script>";
     echo "<script>alert('One Language has been deleted.')</script>";
     
    }
  }
  if (isset($_GET['delete_skill'])) {   
    $delete_skill_id = $input->get('delete_skill');
    $delete_skill = $db->delete("skills_relation", array("relation_id" => $delete_skill_id, "seller_id" => $login_seller_id));
    if ($delete_skill->rowCount() == 1) {
      echo "<script>alert('One skill has been deleted.')</script>";
       echo "<script> window.open('$login_seller_user_name','_self') </script>";
    } else {
       echo "<script> window.open('$login_seller_user_name','_self') </script>";
      echo "<script>alert('One skill hasn't been deleted.')</script>";
    }
  }
}

$get_seller_user_name = $input->get('slug');
$select_seller = $db->query("select * from sellers where seller_user_name=:u_name AND NOT seller_status='deactivated' AND NOT seller_status='block-ban'", array("u_name" => $get_seller_user_name));
$count_seller = $select_seller->rowCount();


if (isset($_SESSION['seller_user_name'])) {
  /* seller skills details*/
  $select_memb_plan_detail = $db->select("`memb_plan_detail` mp LEFT JOIN `membership_table` m ON mp.memb_tbl_id = m.id WHERE mp.seller_id = $login_seller_id AND mp.memb_status = 'Active' AND mp.memb_end_date >= CURRENT_TIMESTAMP ORDER BY mp.id DESC LIMIT 1");
  $row_plan_detail = $select_memb_plan_detail->fetch();
}

$select_sellers = $db->select("sellers", array("seller_user_name" => $get_seller_user_name));
$rows_seller = $select_sellers->fetch();

$num_of_skills = $rows_seller->skills;

if (isset($row_plan_detail) && $row_plan_detail) {
  // $memb_tbl_id = $row_plan_detail->memb_tbl_id;
} else {
  // free plan
  $membership_table = $db->select("membership_table", array("id" => 1));
  $row_plan_detail = $membership_table->fetch();
}

// print("<pre>" . print_r($row_plan_detail, true) . "</pre>");
// exit;
/* seller skills details*/

if ($count_seller == 0) {
//  echo "<script>window.open('$get_seller_user_name','_self');</script>";
}

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
  <title><?= $site_name; ?> - <?= ucfirst($get_seller_user_name) . "'s Profile"; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?= $site_desc; ?>">
  <meta name="keywords" content="<?= $site_keywords; ?>">
  <meta name="author" content="<?= $site_author; ?>">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
  <link href="styles/bootstrap.css" rel="stylesheet">
  <link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
  <link href="styles/styles.css" rel="stylesheet">
  <link href="styles/proposalStyles.css" rel="stylesheet">
  <link href="styles/categories_nav_styles.css" rel="stylesheet">
  <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
  <link href="styles/owl.carousel.css" rel="stylesheet">
  <link href="styles/owl.theme.default.css" rel="stylesheet">
  <link href="styles/sweat_alert.css" rel="stylesheet">
  <link rel="stylesheet" href="styles/chosen.css">
  <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
  <script src="js/ie.js"></script>
  <script type="text/javascript" src="js/sweat_alert.js"></script>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
  <?php if (!empty($site_favicon)) { ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
  <?php } ?>
  <style>
    .fixed {
      position: fixed;
      top: 0;
      transition: all 3s linear;
      left: 0;
      width: 100%;
      z-index: 100;
    }

  

   
    @media(min-width:768px) and (max-width:1440px) {
      .padding-alter8 {
        margin: 0px 0px;
      }
    }
    @media(min-width:640px) and (max-width:767px) {
      .padding-alter8 {
        margin: 0px 10px;
      }
    }

    @media (max-width:767px) {   
      .padding-alter8a {
        border: 1px solid green;
        margin-top: 0px !important;
      }
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>

<body class="is-responsive">
  <?php require_once("includes/header.php"); ?>
  <?php require_once("includes/user_profile_header.php"); ?>
  <div class="container">
    <!-- Container starts -->
    <div class="row padding-alter8">
      <div class="col-md-4 mt-5 mb-4 ">   
        <?php require_once("includes/user_sidebar.php"); ?>  
      </div>
      <div class="col-md-8">
        <div class="row">
          <div class="col-md-12"> 
            <div class="card mt-5 mb-4 rounded-0 padding-alter8a">
              <div class="card-body box-shadow-6a">
                <h2>
                  <?= str_replace('{user_name}', $get_seller_user_name, $lang['user_profile']['user_proposals']); ?>
                </h2>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <?php
          $get_proposals = $db->select("proposals", array("proposal_seller_id" => $seller_id, "proposal_status" => "active"));
          $count_proposals = $get_proposals->rowCount();
          if ($count_proposals == 0) {
          ?>
            <div class="col-md-12">
              <?php if (isset($_SESSION['seller_user_name']) and $seller_user_name == $_SESSION['seller_user_name']) { ?>
                <h3 class=" text-center mb-5 p-2">
                  <i class="fa fa-smile-o"></i>

                  <?php
                  $trans = $lang['user_profile']['login_no_proposals'];
                  $trans = str_replace("{user_name}", $get_seller_user_name, $trans);
                  $trans = str_replace("{a_url}", "$site_url/proposals/create_proposal", $trans);
                  echo $trans;
                  ?>

                </h3>
              <?php } else { ?>
                <h3 class="text-center mb-5 p-2">
                  <i class="fa fa-smile-o"></i>
                  <?= str_replace('{user_name}', $get_seller_user_name, $lang['user_profile']['no_proposals']); ?>
                </h3>
              <?php } ?>
            </div>
          <?php 
          }
          while ($row_proposals = $get_proposals->fetch()) {
            $proposal_id = $row_proposals->proposal_id;
            $proposal_title = $row_proposals->proposal_title;
            $proposal_price = $row_proposals->proposal_price;
            if ($proposal_price == 0) {
              $get_p_1 = $db->select("proposal_packages", array("proposal_id" => $proposal_id, "package_name" => "Basic"));
              $proposal_price = $get_p_1->fetch()->price;
            }
            $proposal_img1 = getImageUrl2("proposals", "proposal_img1", $row_proposals->proposal_img1);
            $proposal_video = $row_proposals->proposal_video;
            $proposal_seller_id = $row_proposals->proposal_seller_id;
            $proposal_rating = $row_proposals->proposal_rating;
            $proposal_url = $row_proposals->proposal_url;
            $proposal_featured = $row_proposals->proposal_featured;
            $proposal_enable_referrals = $row_proposals->proposal_enable_referrals;
            $proposal_referral_money = $row_proposals->proposal_referral_money;
            if (empty($proposal_video)) {
              $video_class = "";
            } else {
              $video_class = "video-img";
            }
            $get_seller = $db->select("sellers", array("seller_id" => $proposal_seller_id));
            $row_seller = $get_seller->fetch();
            $seller_user_name = $row_seller->seller_user_name;
            $seller_image = getImageUrl2("sellers", "seller_image", $row_seller->seller_image);
            $seller_level = $row_seller->seller_level;
            $seller_status = $row_seller->seller_status;
            if (empty($seller_image)) {
              $seller_image = "empty-image.png";
            }
            // Select Proposal Seller Level
            @$seller_level = $db->select("seller_levels_meta", array("level_id" => $seller_level, "language_id" => $siteLanguage))->fetch()->title;
            $proposal_reviews = array();
            $select_buyer_reviews = $db->select("buyer_reviews", array("proposal_id" => $proposal_id));
            $count_reviews = $select_buyer_reviews->rowCount();
            while ($row_buyer_reviews = $select_buyer_reviews->fetch()) {
              $proposal_buyer_rating = $row_buyer_reviews->buyer_rating;
              array_push($proposal_reviews, $proposal_buyer_rating);
            }
            $total = array_sum($proposal_reviews);
            @$average_rating = $total ? $total / count($proposal_reviews) : 0;
            @$count_favorites = $db->count("favorites", array("proposal_id" => $proposal_id, "seller_id" => $login_seller_id));
            if ($count_favorites == 0) {
              $show_favorite_class = "proposal-favorite";
            } else {
              $show_favorite_class = "proposal-unfavorite";
            }
          ?>
            <div class="col-lg-4 col-md-6 col-sm-6 mb-5 mt-3">
              <?php require("includes/proposals.php"); ?>
            </div>
          <?php } ?>
          <?php if (isset($_SESSION['seller_user_name']) and $_SESSION['seller_user_name'] == $get_seller_user_name) { ?>
            <a href="proposals/create_proposal" class="col-lg-4 col-md-6 col-sm-6 mb-5 mt-3">
              <div class="proposal-card-base mp-proposal-card add-new-proposal box-shadow-6">
                <?= $lang['button']['create_new_proposal']; ?>
              </div>
            </a>
          <?php } ?>
        </div>
        <?php include("includes/user_footer.php"); ?>
      </div>
    </div>
  </div> <!-- Container ends -->
  <?php require_once("includes/footer.php"); ?>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script type="text/javascript">
    $("select[name='language_id']").chosen({
      width: "100%"
    });
    $("select[name='skill_id']").chosen({
      width: "100%"
    });
    $("#country, #year_of_graduation").select2();
    $("#university").select2({
      tags: true,
      maximumSelectionLength: 1
    });
    $("#degree").select2({
      tags: true,
      maximumSelectionLength: 1
    });

    $("select[name='language_id']").change(function() {
      var value = $(this).val();
      if (value == "custom") {
        $('.language-title').removeClass("d-none");
        $('.language-title input').attr('required', 'required');
      } else {
        $('.language-title').addClass("d-none");
        $('.language-title input').removeAttr('required');
      }
    });

    $("select[name='skill_id']").change(function() {

      var value = $(this).val();
      if (value == "custom") {
        $('.skill-name').removeClass("d-none");
        $('.skill-name input').attr('required', 'required');
      } else {
        $('.skill-name').addClass("d-none");
        $('.skill-name input').removeAttr('required');
      }

    });

    $(document).ready(function() {

      $('#good').hide();
      $('#bad').hide();

      $('.all').click(function() {
        $("#dropdown-button").html("Most Recent");
        $(".all").attr('class', 'dropdown-item all active');
        $(".bad").attr('class', 'dropdown-item bad');
        $(".good").attr('class', 'dropdown-item good');
        $("#all").show();
        $("#good").hide();
        $("#bad").hide();
      });

      $('.good').click(function() {
        $("#dropdown-button").html("Positive Reviews");
        $(".all").attr('class', 'dropdown-item all');
        $(".bad").attr('class', 'dropdown-item bad');
        $(".good").attr('class', 'dropdown-item good active');
        $("#all").hide();
        $("#good").show();
        $("#bad").hide();
      });

      $('.bad').click(function() {
        $("#dropdown-button").html("Negative Reviews");
        $(".all").attr('class', 'dropdown-item all');
        $(".bad").attr('class', 'dropdown-item bad active');
        $(".good").attr('class', 'dropdown-item good');
        $("#all").hide();
        $("#good").hide();
        $("#bad").show();
      });

    });
  </script>
  <!-- <script>
    var stickyOffset = $('.sticky').offset().top;

    $(window).scroll(function() {
      var sticky = $('.sticky'),
        scroll = $(window).scrollTop();

      if (scroll >= stickyOffset) {
        sticky.addClass('fixed');

        $('.container-fluid ').css('margin-top', '140px')
      } else {
        sticky.removeClass('fixed')
        $('.container-fluid ').css('margin-top', '0px')
      }
    });
  </script> -->
  <!-- <script>
     new header script
    $(document).ready(function() {
      var sticky = $('.sticky');
      var stickyOffset = sticky.offset().top;

      $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= stickyOffset) {
          sticky.addClass('fixed');
          $('.container-fluid').css('margin-top', '140px');
          sticky.css('transition', 'all 2s linear'); 
        } else {
          sticky.removeClass('fixed');
          $('.container-fluid').css('margin-top', '0px');
          sticky.css('transition', 'all 2s linear'); 
        }
      });
    });
  </script> -->

</body>

</html>
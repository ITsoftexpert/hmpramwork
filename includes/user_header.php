<?php
require_once("db.php");
require_once("extra_script.php");
if (!isset($_SESSION['error_array'])) {
  $error_array = array();
} else {
  $error_array = $_SESSION['error_array'];
}

if (isset($_SESSION['seller_user_name'])) {
  require_once("seller_levels.php");
  $seller_user_name = $_SESSION['seller_user_name'];
  $get_seller = $db->select("sellers", array("seller_user_name" => $seller_user_name));
  $row_seller = $get_seller->fetch();
  $seller_id = $row_seller->seller_id;
  $seller_email = $row_seller->seller_email;
  $seller_verification = $row_seller->seller_verification;
  $seller_image = getImageUrl2("sellers", "seller_image", $row_seller->seller_image);
  $count_cart = $db->count("cart", array("seller_id" => $seller_id));
  $select_seller_accounts = $db->select("seller_accounts", array("seller_id" => $seller_id));
  $count_seller_accounts = $select_seller_accounts->rowCount();
  if ($count_seller_accounts == 0) {
    $db->insert("seller_accounts", array("seller_id" => $seller_id));
  }
  $row_seller_accounts = $select_seller_accounts->fetch();
  $current_balance = $row_seller_accounts->current_balance;

  $get_general_settings = $db->select("general_settings");
  $row_general_settings = $get_general_settings->fetch();
  $enable_referrals = $row_general_settings->enable_referrals;
  $count_active_proposals = $db->count("proposals", array("proposal_seller_id" => $seller_id, "proposal_status" => 'active'));

  // Profile Weightness
  $profileWeight = $professionalWeight = $accountWeight = 0;
  $qSellerWeight = $db->select("seller_profile_weights", array("seller_id" => $seller_id));
  $oSellerWeight = $qSellerWeight->fetch();
  if ($oSellerWeight) {
    $profileWeight = $oSellerWeight->profile_weight;
    $professionalWeight = $oSellerWeight->professional_weight;
    $accountWeight = $oSellerWeight->account_weight;
  }
  $totalWeight = $profileWeight + $professionalWeight + $accountWeight;
}

function get_real_user_ip()
{
  //This is to check ip from shared internet network
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}
$ip = get_real_user_ip();

if (!isset($_COOKIE['close_announcement']) or @$_COOKIE['close_announcement'] != $bar_last_updated) {
  include("comp/announcement_bar.php");
}
$get_general_settings = $db->select("general_settings");
$row_general_settings = $get_general_settings->fetch();
$site_color = $row_general_settings->site_color;
$site_hover_color = $row_general_settings->site_hover_color;
$site_border_color = $row_general_settings->site_border_color;
?>
<link href="<?= $site_url; ?>/styles/scoped_responsive_and_nav.css" rel="stylesheet">
<link href="<?= $site_url; ?>/styles/vesta_homepage.css" rel="stylesheet">
<link href="<?= $site_url; ?>/styles/some-changes.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<style>
  .sub_header {
    background: #e5e5e5;
    min-height: 30px;
  }

  @media (max-width:420px) {
    .gnav-header #mobilemenu {
      padding-top: 0px !important;
      margin-left: -2px;
    }

    #style-join-border-red {
      /* margin-top: -2px; */
      display: none;
    }

    #log-in-mod-el {
      padding: 3px 12px;
      /* border: 1px solid red; */
      background-color: #00cedc;
      color: white;
      border-radius: 2px;
    }
  }

  @media (max-width:480px) {
    #style-join-border-red {
      /* margin-top: -2px; */
      display: none;
    }

    #log-in-mod-el {
      padding: 3px 12px;
      /* border: 1px solid red; */
      background-color: #00cedc;
      color: white;
      border-radius: 2px;
    }
  }

  @media (min-width: 1025px) {
    .style-display-flex {
      display: none !important;
    }

    .account-nav {
      /* border: 2px solid green; */
      padding-right: 0px;
      /* padding-top: 7px; */
    }

    .mobile_screen_respo_logo {
      display: none !important;
    }
  }

  @media (max-width: 1024px) {
    .sub_header {
      display: none;
    }

    .account-nav {
      /* border: 2px solid green; */
      padding-right: 21px;
      padding-top: 18px;
    }
  }

  @media(max-width:768px) and (min-width:641px) {
    .account-nav {
      /* border: 2px solid green; */
      padding-right: 16px;
      padding-top: 18px;
    }
  }

  @media(max-width:420px) {

    .mobile_screen_respo_logo {
      display: block;
    }

    .mobile_screen_respo_logo_img {
      margin: auto;
      margin-top: 7px;      
    }

    .account-nav {
      /* border: 2px solid green; */
      padding-right: 16px;
      padding-top: 18px !important;
    }
  }

  @media(min-width:900px) and (max-width:1024) {
    .account-nav {
      padding-right: 23px !important;
    }
  }

  @media(min-width:768px) and (max-width:899px) {
    .account-nav {
      /* border: 2px solid green; */
      padding-right: 16px !important;
      padding-top: 18px !important;
    }
  }

  .sub_header .sub_header_inner {
    max-width: 1450px;
    margin: 0 auto;
    width: 100%;
  }


  .sub_header_menu {
    list-style: none;
    margin: 0;
    padding: 10px 10px 5px 7px;
  }

  .sub_header_menu li {
    display: inline;
    padding: 0 10px;
  }

  .sub_header_inner .right_text {
    padding: 0px 0px;
  }

  .sub_header_inner .right_text a {
    text-decoration: underline;
    color: #00cedc;
  }

  #search-bar-btn-respo {
    padding-right: 15px;
    padding-left: 15px;
  }

  @media(min-width:768px) {
    .gnav-header .search-nav {
      position: relative;
      float: left;
      width: 35%;
      margin: auto;
      /* height: 68px; */
      margin-top: 3px;
      /* margin-left: 10%; */
      /* border:2px solid green; */
    }
  }

  @media(min-width:1025px) {
    .gnav-header .search-nav {
      position: relative;
      float: left;
      width: 35%;
      margin: auto;
      height: 45px;
      margin-top: 3px;
      margin-left: 3%;
      /* border:2px solis green; */
    }
  }

  @media(min-width:1095px) {
    .gnav-header .search-nav {
      position: relative;
      float: left;
      width: 39%;
      margin: auto;
      height: 45px;
      margin-top: 3px;
      margin-left: 6%;
      /* border:2px solis green; */
    }
  }

  @media (max-width:768px) {
    #gnav-search {
      /* border: 2px solid green; */
      width: 100%;
      display: flex;
    }

    .gnav-header .search-nav {
      position: relative;
      float: left;
      width: 45%;
      margin: auto;
      /* height: 68px; */
      margin-top: 3px;
      /* margin-left: 10%; */
    }

    .mobile_screen_respo_logo {
      display: block;
      width: 70%;
      /* border:1px solid green; */
    }

    .mobile_screen_respo_logo_img {
      margin: auto;
      margin-top: 7px;
      margin-left: 7px;
      height: 29px;
    }
  }





  @media(min-width:421px) and (max-width:639px) {
    .margin-left {
      /* border:1px solid green !important; */
      margin-left: 9px;
      padding-left: 7px !important;
      /* padding-top: 9px !important; */
      /* margin-top: -6px !important; */
    }

    .account-nav {
      /* border: 2px solid green; */
      padding-right: 16px;
      padding-top: 18px !important;
    }
  }

  .style-display-flex {
    display: flex;
    /* padding-right: 15%; */
    padding-top: 10px;
    height: 48px;
  }

  @media(min-width:640px) and (max-width:768px) {
    .margin-left {
      /* border:1px solid green !important; */
      /* margin-left: -9px; */
      padding-left: 3px !important;
      padding-top: 11px !important;
      /* margin-top: -6px !important; */
    }
  }

  @media(min-width:769px) and (max-width:1024px) {
    .mobile_screen_respo_logo_img {
      margin: auto;
    }

    .style-display-flex {
      display: -webkit-box;
      /* padding-right: 15%; */
      padding-top: 10px;
      height: 48px;
    }

    .margin-left {
      /* border:1px solid green !important; */
      /* margin-left: -9px; */
      padding-left: 3px !important;
      padding-top: 15px !important;
      /* margin-top: -6px !important; */
    }

    .mobile_screen_respo_logo {
      display: flex;
      width: 70%;
    }
  }
</style>
<div class="sticky">
  <div id="gnav-header" class="gnav-header global-nav clear gnav-3 box-shadow-header-top">
    <header id="gnav-header-inner" class="gnav-header-inner clear apply-nav-height col-group has-svg-icons body-max-width">
      <div class="col-xs-12">
        <div id="gigtodo-logo" class="apply-nav-height gigtodo-logo-svg gigtodo-logo-svg-logged-in <?php if (isset($_SESSION["seller_user_name"])) {
                                                                                                      echo "loggedInLogo";
                                                                                                    } ?>">
          <a href="<?= $site_url; ?>">
            <?php if ($site_logo_type == "image") { ?>
              <img class="desktop" src="<?= $site_logo_image; ?>" width="150">
            <?php } else { ?>
              <span class="desktop text-logo"><?= $site_logo_text; ?></span>
            <?php } ?>
            <?php if ($enable_mobile_logo == 1) { ?>
              <img class="mobile" src="" height="25">
            <?php } ?>
          </a>
        </div>
        <div class="style-display-flex">
          <button id="mobilemenu" class="unstyled-button mobile-catnav-trigger margin-left apply-nav-height icon-b-1 tablet-catnav-enabled <?= ($enable_mobile_logo == 0) ? "left" : ""; ?>">
            <span class="screen-reader-only"></span>
            <div class="text-gray-lighter text-body-larger">
              <span class="gigtodo-icon hamburger-icon nav-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path d="M20,6H4A1,1,0,1,1,4,4H20A1,1,0,0,1,20,6Z" />
                  <path d="M20,13H4a1,1,0,0,1,0-2H20A1,1,0,0,1,20,13Z" />
                  <path d="M20,20H4a1,1,0,0,1,0-2H20A1,1,0,0,1,20,20Z" />
                </svg>
              </span>
            </div>
          </button>
          <div class="mobile_screen_respo_logo">
            <img class="mobile_screen_respo_logo_img" src="<?= $site_logo_image; ?>" height="25">
            <!-- <a href="<?= $site_url; ?>" class="style-display-flex-a">
                        <img class="mobile-pic-respo-sett" src="<?= $site_logo_image; ?>">
                    </a> -->
          </div>
        </div>
        <div class="catnav-search-bar search-browse-wrapper with-catnav" id="search-bar-btn-respo">
          <div class="search-browse-inner" id="search-bar-btn-respo-inn">
            <form id="gnav-search" class="search-nav expanded-search apply-nav-height" method="post">
              <div class="gnav-search-inner clearable" id="inner-search-btn-bar">
                <div class="search-input-wrapper text-field-wrapper">
                  <input id="search-query" class="w-100 rounded" name="search_query" placeholder="<?= $lang['search']['placeholder']; ?>" value="<?= @$_SESSION["search_query"]; ?>" autocomplete="off">
                </div>
                <div class="search-button-wrapper hide">
                  <button class="btn btn-primary" style="color:#FFF;background-color: <?php echo $site_color; ?>" name="search" type="submit" value="Search">
                    <?= $lang['search']['button']; ?>
                  </button>
                </div>
              </div>
              <ul class="search-bar-panel d-none"></ul>
            </form>
          </div>
        </div>
        <?php
        if (isset($_POST['search'])) {
          $search_query = $input->post('search_query');
          $_SESSION['search_query'] = $search_query;
          echo "<script>window.open('$site_url/search.php','_self')</script>";
        }
        ?>
        <ul class="account-nav apply-nav-height <?php echo (!isset($_SESSION["seller_user_name"])) ? 'guest_user' : ''; ?> ">
          <?php if (!isset($_SESSION["seller_user_name"])) { ?>
            <!--                        <li class="register-link">-->
            <!--                            <a href="--><? //= $site_url; 
                                                        ?><!--/freelancers">--><? //= $lang['freelancers_menu']; 
                                                                                ?><!--</a>-->
            <!--                        </li>-->
            <li class="sell-on-gigtodo-link d-none d-lg-block">
              <a href="#" data-toggle="modal" data-target="#register-modal">
                <span class="sell-copy"><?= $lang['find_job']; ?></span>
                <span class="sell-copy short"><?= $lang['become_seller']; ?></span>
              </a>
            </li>
            <li class="register-link" id="log-in-mod-el">
              <a href="#" data-toggle="modal" data-target="#login-modal"><?= $lang['sign_in']; ?></a>
            </li>
            <li class="sign-in-link mr-lg-0" id="style-join-border-red">
              <a href="#" class="btn btn_join" style="color: white;background-color: <?php echo $site_color; ?>" data-toggle="modal" data-target="#register-modal">
                <?php if ($deviceType == "phone") {
                  echo $lang['mobile_join_now'];
                } else {
                  echo $lang['join_now'];
                } ?>
              </a>
            </li>
          <?php
          } else {
            require_once("comp/UserMenu.php");
          }
          ?>
        </ul>
      </div>
    </header>
  </div>

  <?php include("comp/mobile_menu.php"); ?>
  <div class="clearfix"></div>
  <?php include("comp/categories_nav.php"); ?>
  <?php if (isset($_SESSION['seller_user_name'])) { ?>
    <?php include("navigations.php"); ?>
  <?php } else { ?>
    <style>
      /* .container-fluid,
      .order-page {
        margin-top: 25px !important;
        ;
      } */
    </style>
  <?php } ?>
</div>


<div class="clearfix"></div>
<?php if (isset($_GET['not_available'])) { ?>
  <!-- Alert to show wrong url or unregistered account end -->
  <div class="alert alert-danger text-center mb-0 h6">
    <?= $lang['not_availble']; ?>
  </div>
<?php } ?>

<?php
if (isset($_SESSION['seller_user_name'])) {
  if ($seller_verification != "ok") {
?>
    <div class="alert alert-warning clearfix activate-email-class mb-0 mt-150px">
      <div class="float-left mt-2">
        <i style="font-size: 125%;" class="fa fa-exclamation-circle"></i>
        <?php
        $message = $lang['popup']['email_confirm']['text'];
        $message = str_replace('{seller_email}', $seller_email, $message);
        $message = str_replace('{link}', "$site_url/customer_support", $message);
        echo $message;
        ?>
      </div>
      <div class="float-right">
        <button id="send-email" class="btn btn-success btn-sm float-right text-white"><?= $lang["popup"]["email_confirm"]['button']; ?></button>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        $("#send-email").click(function() {
          $("#wait").addClass('loader');
          $.ajax({
            method: "POST",
            url: "<?= $site_url; ?>/includes/send_email",
            success: function() {
              $("#wait").removeClass('loader');
              $("#send-email").html("Resend Email");
              swal({
                type: 'success',
                text: '<?= $lang['alert']['confirmation_email']; ?>',
              });
            }
          });
        });
      });
    </script>
     <script>
      //downthere of header
      document.addEventListener('DOMContentLoaded', function() {
        var containerfluid = document.querySelectorAll('.container-fluid');
        containerfluid.forEach(function(element) {
          element.style.setProperty('margin-top', '0px', 'important');
        });
      });
      // my profile
      document.addEventListener('DOMContentLoaded', function() {
        var userheadermt = document.querySelectorAll('.user-header-mt');
        userheadermt.forEach(function(element) {
          element.style.setProperty('margin-top', '0px', 'important');
        });
      });
    </script>
   
<?php  }
} ?>


<?php require_once("register_login_forgot_modals.php"); ?>
<?php require_once("register_login_forgot.php"); ?>
<?php require_once("external_stylesheet.php"); ?>
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
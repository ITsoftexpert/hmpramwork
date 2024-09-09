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


<!--<div class="top-header position-relative d-lg-block d-none">
  <div class="container">
    <div class="row justify-content-between">
      <div class="col-md-4">

      </div>
      <div class="col-md-4">
        <div class="middle position-relative ">
          <div class="bg-white d-flex justify-content-center align-items-center">

          </div>
        </div>
      </div>
      <div class="col-md-4"></div>
    </div>
  </div>
</div>-->

<div id="gnav-header" class="gnav-header global-nav clear gnav-3">
  <header id="gnav-header-inner" class="gnav-header-inner clear apply-nav-height col-group has-svg-icons body-max-width w-100 d-flex justify-content-between px-0">
    <div class="row position-relative align-items-center justify-content-between w-100">



      <div class="col-lg-2 z-index-4 position-static col-auto padding-right-xs-0">
        <ul class="list-unstyled d-flex mb-0 justify-content-center">
          <li class="d-flex align-items-center">
            <div id="gigtodo-logo" class="d-lg-none d-flex align-items-center m-0 gigtodo-logo-svg gigtodo-logo-svg-logged-in  <?php if (isset($_SESSION["seller_user_name"])) {
                                                                                                                                  echo "loggedInLogo";
                                                                                                                                } ?>">
              <a href="<?= $site_url; ?>">
                <?php if ($site_logo_type == "image") { ?>
                  <img class="desktop" src="<?= $site_logo_image; ?>" width="150">
                  <img class="desktop m-0" src="<?= $site_url; ?>/images/hmp/logo.jpeg" width="250">
                <?php } else { ?>
                  <span class="desktop text-logo"><?= $site_logo_text; ?></span>
                <?php } ?>
                <?php if ($enable_mobile_logo == 1) { ?>
                  <img class="mobile m-0" src="<?= $site_mobile_logo; ?>" height="25">
                <?php } ?>
              </a>
            </div>

            <a id="mobilemenu" class="ml-2 align-items-center unstyled-button d-lg-none d-flex   icon-b-1 tablet-catnav-enabled <?= ($enable_mobile_logo == 0) ? "left" : ""; ?>">
              <span class="gigtodo-icon hamburger-icon nav-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path d="M20,6H4A1,1,0,1,1,4,4H20A1,1,0,0,1,20,6Z" />
                  <path d="M20,13H4a1,1,0,0,1,0-2H20A1,1,0,0,1,20,13Z" />
                  <path d="M20,20H4a1,1,0,0,1,0-2H20A1,1,0,0,1,20,20Z" />
                </svg>
              </span>
              <!-- <span class="text-secondary">Hire by category</span> -->
            </a>
          </li>

          <li class=" mr-4 d-lg-block d-none">


            <div id="" class="gigtodo-logo-svg-logged-in ">
              <a href="http://localhost/hmp_local">
                <img class="desktop" src="<?= $site_url; ?>/images/hmp/logo.jpeg" width="200" style="margin-top: 0px;
                        margin-right: -100px">
              </a>
            </div>

          </li>




          <!--
          <li class=" mr-4 d-lg-block d-none">
            <a data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
              <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="12px">
                <path d="M0,7L0,5L16,5L16,7L0,7ZM0,0L16,0L16,2L0,2L0,0ZM12,12L0,12L0,10L12,10L12,12Z"></path>
              </svg>
              <span class="text-secondary">Hire by category</span>
            </a>
          </li>
            -->
          <!--<li class=" mr-4 d-lg-block d-none">
            <a class="text-secondary">How it Works?</a>
          </li>-->
        </ul>


        <?php /*
        <div class="collapse position-absolute w-100" id="navbarToggleExternalContent" style="top: 70%;">
          <div data-ui="cat-nav" id="desktop-category-nav" class="ui-toolkit cat-nav">
            <div class=" bg-transparent-homepage-experiment hide-xs hide-sm hide-md ">
              <div class="body-max-width row">
                <ul class="col-xs-2 body-max-width display-flex-xs  bg-white flex-column font-weight-bold py-3 px-0 pr-0 up-arrow position-relative" style="box-shadow: 0px 0px 5px rgb(0 0 0 / 20%), 0px 0px 5px rgb(0 0 0 / 20%);" role="menubar" data-ui="top-nav-category-list" aria-activedescendant="catnav-primary-link-10855">
                  <?php
                  $get_categories = $db->query("select * from categories where cat_featured='yes'" . ($lang_dir == "right" ? 'order by 1 DESC' : '') . " LIMIT 0,9");
                  while ($row_categories = $get_categories->fetch()) {
                    $cat_id = $row_categories->cat_id;
                    $cat_url = $row_categories->cat_url;
                    $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
                    $row_meta = $get_meta->fetch();
                    @$cat_title = $row_meta->cat_title;
                  ?>
                    <li class="top-nav-item pt-xs-1 pb-xs-1 pl-xs-2 pr-xs-2 display-flex-xs align-items-center text-center " data-linkable="true" data-ui="top-nav-category-link" data-node-id="c-<?= $cat_id; ?>">
                      <a class="px-3 py-1 d-flex justify-content-between align-items-center w-100" href="<?= $site_url; ?> /categories/<?= $cat_url; ?>">
                        <span>
                          <?= @$cat_title; ?>
                        </span>

                        <svg class="" xmlns="http://www.w3.org/2000/svg" width="7" height="11">
                          <path d="M0.3,10.7L0.3,10.7c0.4,0.4,0.9,0.4,1.3,0L7,5.5L1.6,0.3C1.2-0.1,0.7,0,0.3,0.3l0,0c-0.4,0.4-0.4,1,0,1.3l4,3.9l-4,3.9C-0.1,9.8-0.1,10.4,0.3,10.7z"></path>
                        </svg>
                      </a>
                    </li>
                  <?php } ?>
                  <?php
                  $count = $db->count("categories", array("cat_featured" => "yes"));
                  if ($count > 10) {
                  ?>
                    <li class="top-nav-item pt-xs-1 pb-xs-1 pl-xs-2 pr-xs-2 display-flex-xs align-items-center text-center" data-linkable="true" data-ui="top-nav-category-link" data-node-id="c-more">
                      <a href="#"><?= $lang['more']; ?></a>
                    </li>
                    <?php
                  } else {
                    $get_categories = $db->query("select * from categories where cat_featured='yes'" . ($lang_dir == "right" ? 'order by 1 DESC' : '') . " LIMIT 9,1");
                    while ($row_categories = $get_categories->fetch()) {
                      $cat_id = $row_categories->cat_id;
                      $cat_url = $row_categories->cat_url;
                      $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
                      $row_meta = $get_meta->fetch();
                      @$cat_title = $row_meta->cat_title;
                    ?>
                      <li class="top-nav-item pt-xs-1 pb-xs-1 pl-xs-2 pr-xs-2 display-flex-xs align-items-center text-center" data-linkable="true" data-ui="top-nav-category-link" data-node-id="c-<?= $cat_id; ?>">
                        <a href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
                          <?= @$cat_title; ?>
                        </a>
                      </li>
                  <?php }
                  } ?>
                </ul>
                <div class="col-centered col-xs-10 pl-0">
                  <div class="h-100">
                    <?php
                    $get_categories = $db->query("select * from categories where cat_featured='yes'" . ($lang_dir == "right" ? 'order by 1 DESC' : '') . " LIMIT 0,10");
                    while ($row_categories = $get_categories->fetch()) {
                      $cat_id = $row_categories->cat_id;
                      $cat_url = $row_categories->cat_url;
                      $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
                      $row_meta = $get_meta->fetch();
                      @$cat_title = $row_meta->cat_title;
                      $count = $db->count("categories_children", array("child_parent_id" => $cat_id));
                      if ($count > 0) {
                    ?>
                    <div class="body-sub-width vertical-align-top sub-nav-container bg-white overflow-hidden  catnav-mott-control display-none h-100" style="box-shadow: 15px 0px 5px rgb(0 0 0 / 20%), 0px 0px 5px rgb(0 0 0 / 20%);" data-ui="sub-nav" aria-hidden="true" data-node-id="c-<?= $cat_id; ?>">
                      <div class="width-full h-100">
                        <div class="row h-100">
                          <ul class="list-unstyled   p-xs-3 pl-xs-5  col-xs-6 align-content-flex-start row" role="presentation">
                            <?php
                            $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id' LIMIT 0,10");
                            while ($row_child_cat = $get_child_cat->fetch()) {
                              $child_id = $row_child_cat->child_id;
                              $child_url = $row_child_cat->child_url;
                              $get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
                              $row_meta = $get_meta->fetch();
                              $child_title = $row_meta->child_title;
                            ?>
                              <li class="col-xs-6 text-nowrap ">
                                <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
                                  <?= $child_title; ?>
                                </a>
                              </li>
                            <?php } ?>
                          </ul>
                          <!-- <div class="b-lg-1 h-100"></div> -->
                          <ul class="list-unstyled   p-xs-3 pl-xs-5  row col-xs-6 align-content-flex-start " role="presentation">
                            <?php
                            $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id' LIMIT 10,10");
                            while ($row_child_cat = $get_child_cat->fetch()) {
                              $child_id = $row_child_cat->child_id;
                              $child_url = $row_child_cat->child_url;
                              $get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
                              $row_meta = $get_meta->fetch();
                              $child_title = $row_meta->child_title;
                            ?>
                              <li class="col-xs-6 text-nowrap">
                                <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
                                  <?= $child_title; ?>
                                </a>
                              </li>
                            <?php } ?>
                          </ul>
                        </div>
                        <div class="row">
                          <ul class="list-unstyled  col-xs-6 row p-xs-3 pl-xs-5 align-content-flex-start" role="presentation">
                            <?php
                            $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id' LIMIT 20,10");
                            while ($row_child_cat = $get_child_cat->fetch()) {
                              $child_id = $row_child_cat->child_id;
                              $child_url = $row_child_cat->child_url;
                              $get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
                              $row_meta = $get_meta->fetch();
                              $child_title = $row_meta->child_title;
                            ?>
                              <li>
                                <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
                                  <?= $child_title; ?>
                                </a>
                              </li>
                            <?php } ?>
                          </ul>
                          <ul class="list-unstyled  col-xs-6 row p-xs-3 pl-xs-5 align-content-flex-start" role="presentation">
                            <?php
                            $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id' LIMIT 30,10");
                            while ($row_child_cat = $get_child_cat->fetch()) {
                              $child_id = $row_child_cat->child_id;
                              $child_url = $row_child_cat->child_url;
                              $get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
                              $row_meta = $get_meta->fetch();
                              $child_title = $row_meta->child_title;
                            ?>
                              <li>
                                <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
                                  <?= $child_title; ?>
                                </a>
                              </li>
                            <?php } ?>
                          </ul>
                        </div>
                      </div>
                    </div>
                      <?php } ?>
                    <?php } ?>
                    <div class="body-sub-width vertical-align-top sub-nav-container bg-white overflow-hidden bl-xs-1 bb-xs-1 br-xs-1 catnav-mott-control display-none" data-ui="sub-nav" aria-hidden="true" data-node-id="c-more">
                      <div class="width-full display-flex-xs">
                        <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">
                          <?php
                          $get_categories = $db->query("select * from categories where cat_featured='yes' LIMIT 9,19");
                          while ($row_categories = $get_categories->fetch()) {
                            $cat_id = $row_categories->cat_id;
                            $cat_url = $row_categories->cat_url;
                            $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
                            $row_meta = $get_meta->fetch();
                            $cat_title = $row_meta->cat_title;
                          ?>
                            <li>
                              <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
                                <?= @$cat_title; ?>
                              </a>
                            </li>
                          <?php } ?>
                        </ul>
                        <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">
                          <?php
                          $get_categories = $db->query("select * from categories where cat_featured='yes' LIMIT 19,29");
                          while ($row_categories = $get_categories->fetch()) {
                            $cat_id = $row_categories->cat_id;
                            $cat_url = $row_categories->cat_url;
                            $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
                            $row_meta = $get_meta->fetch();
                            $cat_title = $row_meta->cat_title;
                          ?>
                            <li>
                              <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
                                <?= @$cat_title; ?>
                              </a>
                            </li>
                          <?php } ?>
                        </ul>
                        <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">
                          <?php
                          $get_categories = $db->query("select * from categories where cat_featured='yes' LIMIT 29,39");
                          while ($row_categories = $get_categories->fetch()) {
                            $cat_id = $row_categories->cat_id;
                            $cat_url = $row_categories->cat_url;
                            $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
                            $row_meta = $get_meta->fetch();
                            $cat_title = $row_meta->cat_title;
                          ?>
                            <li>
                              <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
                                <?= @$cat_title; ?>
                              </a>
                            </li>
                          <?php } ?>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
          */ ?>


      </div>
      <div class="col-lg-6 col-12 mobile-search-div collapse d-lg-block h-100 pt-lg-2" id="search-mobile">
        <div class="d-flex flex-column align-items-center align-items-center justify-content-center  w-100 header-logo">


          <div class="input-group">
            <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
            <a href="#" class="btn btn_join m-0" style="color: white;background-color:#00C8D4 !important;" data-toggle="modal" data-target="#register-modal">
              Search</a>
          </div>
          <!--logo-->
          <?php /*
          <div id="gigtodo-logo" class="float-none text-center w-100 d-lg-block d-none  mr-0 gigtodo-logo-svg gigtodo-logo-svg-logged-in  <?php if (isset($_SESSION["seller_user_name"])) { echo "loggedInLogo"; } ?>">
            <a href="<?= $site_url; ?>">
              <?php if ($site_logo_type == "image") { ?>
                <!-- <img class="desktop" src="<?= $site_logo_image; ?>" width="150"> -->
                <img class="desktop mt-0" src="<?= $site_url; ?>/images/hmp/logo.jpeg" width="250">
              <?php } else { ?>
                <span class="desktop text-logo"><?= $site_logo_text; ?></span>
              <?php } ?>
              <?php if ($enable_mobile_logo == 1) { ?>
                <img class="mobile" src="<?= $site_mobile_logo; ?>" height="25">
              <?php } ?>
            </a>
          </div> */ ?>
          <!--logo-->


          <?php /*


              <form id="gnav-search" class="search-nav expanded-search d-flex align-items-center w-100 justify-content-center " method="post">

                <div class="form-control-header w-100 mx-lg-3">
                  <div class="search__shadow"></div>

                  <input id="search-query" class="form-control px-2 border-0   mx-auto text-center py-3 px-4" name="search_query" placeholder="<?= $lang['search']['placeholder']; ?>" value="<?= @$_SESSION["search_query"]; ?>" autocomplete="off">
                </div>

                  <?php /*
                <button name="search" type="submit" class="position-relative d-lg-block d-flex justify-content-center align-items-center z-index-1 boxs bg-white " style="border: none;">
                  <!-- <?= $lang['search']['button']; ?> -->
                  <img src="<?= $site_url; ?>/images/hmp/search.png">
                </button>


                <a class="position-relative d-lg-none d-flex justify-content-center align-items-center z-index-1 boxs" data-toggle="collapse" href="#search-mobile" role="button" aria-expanded="false" aria-controls="search-mobile">
                  <svg fill="#fff" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
                    <path d="M16.7,16.7L16.7,16.7c-0.4,0.4-1,0.4-1.4,0L10,11.4l-5.3,5.3c-0.4,0.4-1,0.4-1.4,0l0,0c-0.4-0.4-0.4-1,0-1.4L8.6,10L3.3,4.7
                      c-0.4-0.4-0.4-1,0-1.4l0,0c0.4-0.4,1-0.4,1.4,0L10,8.6l5.3-5.3c0.4-0.4,1-0.4,1.4,0l0,0c0.4,0.4,0.4,1,0,1.4L11.4,10l5.3,5.3C17.1,15.7,17.1,16.3,16.7,16.7z"></path>
                  </svg>
                </a>
                <!-- <div class="gnav-search-inner clearable">
                  <label for="search-query" class="screen-reader-only">Search for items</label>
                </div> -->
                <ul class="search-bar-panel d-none"></ul>*/
          ?>


          <?php
          if (isset($_POST['search'])) {
            $search_query = $input->post('search_query');
            $_SESSION['search_query'] = $search_query;
            echo "<script>window.open('$site_url/search.php','_self')</script>";
          }
          ?>
        </div>
      </div>
      <div class="col-lg-4 col-auto d-lg-flex justify-content-center padding-left-xs-0 icon-group" style="margin-left: -100px; margin-top: 10px;">
        <!-- <ul class="account-nav position-static d-lg-block d-flex align-items-center "> -->
        <?php if (!isset($_SESSION["seller_user_name"])) { ?>
          <ul class="account-nav position-static d-lg-block align-items-center ">
            <li class="d-lg-none d-block register-link"> <a class="m-0"><img src="images/hmp/hi.png" alt="" srcset="" width="25px"></a>
            </li>
            <li class="d-lg-none d-block register-link"><a class="m-0" data-toggle="collapse" href="#search-mobile" role="button" aria-expanded="false" aria-controls="search-mobile" class="position-relative d-block z-index-1"><img src="<?= $site_url; ?>/images/hmp/search.png"></a></li>
            <li class="register-link">
              <!-- <a href="<?= $site_url; ?>/freelancers"><?= $lang['freelancers_menu']; ?></a> -->
              <a class="text-primary m-0" href="#">How it Works?</a>
            </li>
            <li class="sell-on-gigtodo-link d-none d-lg-block">
              <a class="m-0" href="#" data-toggle="modal" data-target="#register-modal">
                <!-- <span class="sell-copy"><?= $lang['become_seller']; ?></span> -->
                <!-- <span class="sell-copy short"><?= $lang['become_seller']; ?></span> -->
                <a class="text-secondary m-0" href="<?= $site_url; ?>/freelancers">Find Job</a>
              </a>
            </li>
            <li class="register-link">
              <a href="#" data-toggle="modal" data-target="#login-modal"><?= $lang['sign_in']; ?></a>
              <a class="text-secondary m-0" data-toggle="modal" data-target="#login-modal">Log In</a>
            </li>
            <li class="sign-in-link">
              <a href="#" class="btn btn_join m-0" style="color: white;background-color:#00C8D4 !important;" data-toggle="modal" data-target="#register-modal">
                <?php if ($deviceType == "phone") {
                  echo $lang['mobile_join_now'];
                } else {
                  echo $lang['join_now'];
                } ?>
              </a>
            </li>
          </ul>
        <?php
        } else {
          require_once("comp/UserMenu.php");
        }
        ?>
      </div>
    </div>

  </header>




  <div class="row bg-light">
    <div class="row position-relative align-items-center justify-content-between w-100">
      <div class="col-lg-4 z-index-4 position-static col-auto padding-right-xs-0">
        <ul class="list-unstyled d-flex mb-0 justify-content-center">
          <li class="d-flex align-items-center">
            <div id="gigtodo-logo" class="d-lg-none d-flex align-items-center m-0 gigtodo-logo-svg gigtodo-logo-svg-logged-in  <?php if (isset($_SESSION["seller_user_name"])) {
                                                                                                                                  echo "loggedInLogo";
                                                                                                                                } ?>">
              <a href="<?= $site_url; ?>">
                <?php if ($site_logo_type == "image") { ?>
                  <img class="desktop" src="<?= $site_logo_image; ?>" width="150">
                  <img class="desktop m-0" src="<?= $site_url; ?>/images/hmp/logo.jpeg" width="250">
                <?php } else { ?>
                  <span class="desktop text-logo"><?= $site_logo_text; ?></span>
                <?php } ?>
                <?php if ($enable_mobile_logo == 1) { ?>
                  <img class="mobile m-0" src="<?= $site_mobile_logo; ?>" height="25">
                <?php } ?>
              </a>
            </div>
            <a id="mobilemenu" class="ml-2 align-items-center unstyled-button d-lg-none d-flex   icon-b-1 tablet-catnav-enabled <?= ($enable_mobile_logo == 0) ? "left" : ""; ?>">
              <span class="gigtodo-icon hamburger-icon nav-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                  <path d="M20,6H4A1,1,0,1,1,4,4H20A1,1,0,0,1,20,6Z" />
                  <path d="M20,13H4a1,1,0,0,1,0-2H20A1,1,0,0,1,20,13Z" />
                  <path d="M20,20H4a1,1,0,0,1,0-2H20A1,1,0,0,1,20,20Z" />
                </svg>
              </span>
              <!-- <span class="text-secondary">Hire by category</span> -->
            </a>
          </li>
          <li class=" mr-4 d-lg-block d-none">
            <a data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
              <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="12px">
                <path d="M0,7L0,5L16,5L16,7L0,7ZM0,0L16,0L16,2L0,2L0,0ZM12,12L0,12L0,10L12,10L12,12Z"></path>
              </svg>
              <span class="text-secondary">Hire by category</span>
            </a>
          </li>
          <li class=" mr-4 d-lg-block d-none">
            <a class="d-lg-block d-none" href="https://5dwebinfotech.com/hiremyprofile//freelancers">
              <img src="images/hmp/hi.png" alt="" srcset="" width="17px">
              Post Job Free</a>
          </li>
        </ul>

        <div class="collapse position-absolute w-100" id="navbarToggleExternalContent" style="top: 70%;">
          <div data-ui="cat-nav" id="desktop-category-nav" class="ui-toolkit cat-nav">
            <div class=" bg-transparent-homepage-experiment hide-xs hide-sm hide-md ">
              <div class="body-max-width row">
                <ul class="col-xs-2 body-max-width display-flex-xs  bg-white flex-column font-weight-bold py-3 px-0 pr-0 up-arrow position-relative" style="box-shadow: 0px 0px 5px rgb(0 0 0 / 20%), 0px 0px 5px rgb(0 0 0 / 20%);" role="menubar" data-ui="top-nav-category-list" aria-activedescendant="catnav-primary-link-10855">
                  <?php
                  $get_categories = $db->query("select * from categories where cat_featured='yes'" . ($lang_dir == "right" ? 'order by 1 DESC' : '') . " LIMIT 0,9");
                  while ($row_categories = $get_categories->fetch()) {
                    $cat_id = $row_categories->cat_id;
                    $cat_url = $row_categories->cat_url;
                    $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
                    $row_meta = $get_meta->fetch();
                    @$cat_title = $row_meta->cat_title;
                  ?>
                    <li class="top-nav-item pt-xs-1 pb-xs-1 pl-xs-2 pr-xs-2 display-flex-xs align-items-center text-center " data-linkable="true" data-ui="top-nav-category-link" data-node-id="c-<?= $cat_id; ?>">
                      <a class="px-3 py-1 d-flex justify-content-between align-items-center w-100" href="<?= $site_url; ?> /categories/<?= $cat_url; ?>">
                        <span>
                          <?= @$cat_title; ?>
                        </span>

                        <svg class="" xmlns="http://www.w3.org/2000/svg" width="7" height="11">
                          <path d="M0.3,10.7L0.3,10.7c0.4,0.4,0.9,0.4,1.3,0L7,5.5L1.6,0.3C1.2-0.1,0.7,0,0.3,0.3l0,0c-0.4,0.4-0.4,1,0,1.3l4,3.9l-4,3.9C-0.1,9.8-0.1,10.4,0.3,10.7z"></path>
                        </svg>
                      </a>
                    </li>
                  <?php } ?>
                  <?php
                  $count = $db->count("categories", array("cat_featured" => "yes"));
                  if ($count > 10) {
                  ?>
                    <li class="top-nav-item pt-xs-1 pb-xs-1 pl-xs-2 pr-xs-2 display-flex-xs align-items-center text-center" data-linkable="true" data-ui="top-nav-category-link" data-node-id="c-more">
                      <a href="#"><?= $lang['more']; ?></a>
                    </li>
                    <?php
                  } else {
                    $get_categories = $db->query("select * from categories where cat_featured='yes'" . ($lang_dir == "right" ? 'order by 1 DESC' : '') . " LIMIT 9,1");
                    while ($row_categories = $get_categories->fetch()) {
                      $cat_id = $row_categories->cat_id;
                      $cat_url = $row_categories->cat_url;
                      $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
                      $row_meta = $get_meta->fetch();
                      @$cat_title = $row_meta->cat_title;
                    ?>
                      <li class="top-nav-item pt-xs-1 pb-xs-1 pl-xs-2 pr-xs-2 display-flex-xs align-items-center text-center" data-linkable="true" data-ui="top-nav-category-link" data-node-id="c-<?= $cat_id; ?>">
                        <a href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
                          <?= @$cat_title; ?>
                        </a>
                      </li>
                  <?php }
                  } ?>
                </ul>
                <div class="col-centered col-xs-10 pl-0">
                  <div class="h-100">
                    <?php
                    $get_categories = $db->query("select * from categories where cat_featured='yes'" . ($lang_dir == "right" ? 'order by 1 DESC' : '') . " LIMIT 0,10");
                    while ($row_categories = $get_categories->fetch()) {
                      $cat_id = $row_categories->cat_id;
                      $cat_url = $row_categories->cat_url;
                      $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
                      $row_meta = $get_meta->fetch();
                      @$cat_title = $row_meta->cat_title;
                      $count = $db->count("categories_children", array("child_parent_id" => $cat_id));
                      if ($count > 0) {
                    ?>
                        <div class="body-sub-width vertical-align-top sub-nav-container bg-white overflow-hidden  catnav-mott-control display-none h-100" style="box-shadow: 15px 0px 5px rgb(0 0 0 / 20%), 0px 0px 5px rgb(0 0 0 / 20%);" data-ui="sub-nav" aria-hidden="true" data-node-id="c-<?= $cat_id; ?>">
                          <div class="width-full h-100">
                            <div class="row h-100">
                              <ul class="list-unstyled   p-xs-3 pl-xs-5  col-xs-6 align-content-flex-start row" role="presentation">
                                <?php
                                $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id' LIMIT 0,10");
                                while ($row_child_cat = $get_child_cat->fetch()) {
                                  $child_id = $row_child_cat->child_id;
                                  $child_url = $row_child_cat->child_url;
                                  $get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
                                  $row_meta = $get_meta->fetch();
                                  $child_title = $row_meta->child_title;
                                ?>
                                  <li class="col-xs-6 text-nowrap ">
                                    <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
                                      <?= $child_title; ?>
                                    </a>
                                  </li>
                                <?php } ?>
                              </ul>
                              <!-- <div class="b-lg-1 h-100"></div> -->
                              <ul class="list-unstyled   p-xs-3 pl-xs-5  row col-xs-6 align-content-flex-start " role="presentation">
                                <?php
                                $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id' LIMIT 10,10");
                                while ($row_child_cat = $get_child_cat->fetch()) {
                                  $child_id = $row_child_cat->child_id;
                                  $child_url = $row_child_cat->child_url;
                                  $get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
                                  $row_meta = $get_meta->fetch();
                                  $child_title = $row_meta->child_title;
                                ?>
                                  <li class="col-xs-6 text-nowrap">
                                    <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
                                      <?= $child_title; ?>
                                    </a>
                                  </li>
                                <?php } ?>
                              </ul>
                            </div>
                            <div class="row">
                              <ul class="list-unstyled  col-xs-6 row p-xs-3 pl-xs-5 align-content-flex-start" role="presentation">
                                <?php
                                $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id' LIMIT 20,10");
                                while ($row_child_cat = $get_child_cat->fetch()) {
                                  $child_id = $row_child_cat->child_id;
                                  $child_url = $row_child_cat->child_url;
                                  $get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
                                  $row_meta = $get_meta->fetch();
                                  $child_title = $row_meta->child_title;
                                ?>
                                  <li>
                                    <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
                                      <?= $child_title; ?>
                                    </a>
                                  </li>
                                <?php } ?>
                              </ul>
                              <ul class="list-unstyled  col-xs-6 row p-xs-3 pl-xs-5 align-content-flex-start" role="presentation">
                                <?php
                                $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id' LIMIT 30,10");
                                while ($row_child_cat = $get_child_cat->fetch()) {
                                  $child_id = $row_child_cat->child_id;
                                  $child_url = $row_child_cat->child_url;
                                  $get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
                                  $row_meta = $get_meta->fetch();
                                  $child_title = $row_meta->child_title;
                                ?>
                                  <li>
                                    <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
                                      <?= $child_title; ?>
                                    </a>
                                  </li>
                                <?php } ?>
                              </ul>
                            </div>
                          </div>
                        </div>
                      <?php } ?>
                    <?php } ?>
                    <div class="body-sub-width vertical-align-top sub-nav-container bg-white overflow-hidden bl-xs-1 bb-xs-1 br-xs-1 catnav-mott-control display-none" data-ui="sub-nav" aria-hidden="true" data-node-id="c-more">
                      <div class="width-full display-flex-xs">
                        <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">
                          <?php
                          $get_categories = $db->query("select * from categories where cat_featured='yes' LIMIT 9,19");
                          while ($row_categories = $get_categories->fetch()) {
                            $cat_id = $row_categories->cat_id;
                            $cat_url = $row_categories->cat_url;
                            $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
                            $row_meta = $get_meta->fetch();
                            $cat_title = $row_meta->cat_title;
                          ?>
                            <li>
                              <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
                                <?= @$cat_title; ?>
                              </a>
                            </li>
                          <?php } ?>
                        </ul>
                        <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">
                          <?php
                          $get_categories = $db->query("select * from categories where cat_featured='yes' LIMIT 19,29");
                          while ($row_categories = $get_categories->fetch()) {
                            $cat_id = $row_categories->cat_id;
                            $cat_url = $row_categories->cat_url;
                            $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
                            $row_meta = $get_meta->fetch();
                            $cat_title = $row_meta->cat_title;
                          ?>
                            <li>
                              <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
                                <?= @$cat_title; ?>
                              </a>
                            </li>
                          <?php } ?>
                        </ul>
                        <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">
                          <?php
                          $get_categories = $db->query("select * from categories where cat_featured='yes' LIMIT 29,39");
                          while ($row_categories = $get_categories->fetch()) {
                            $cat_id = $row_categories->cat_id;
                            $cat_url = $row_categories->cat_url;
                            $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
                            $row_meta = $get_meta->fetch();
                            $cat_title = $row_meta->cat_title;
                          ?>
                            <li>
                              <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
                                <?= @$cat_title; ?>
                              </a>
                            </li>
                          <?php } ?>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>





</div>
<?php include("mobile_menu.php"); ?>



<div class="clearfix"></div>
<?php include("comp/categories_nav.php"); ?>
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
<?php /*include("includes/navigations.php"); */ ?>
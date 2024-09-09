<?php
error_reporting(0);
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
// echo "<pre>";
// print_r($row_login_seller);
// exit;
$login_seller_id = $row_login_seller->seller_id;
$login_seller_name = $row_login_seller->seller_name;
$login_user_name = $row_login_seller->seller_user_name;
$login_seller_offers = $row_login_seller->seller_offers;
$relevant_requests = $row_general_settings->relevant_requests;

$select_sellers = $db->select("sellers", array("seller_user_name" => $_SESSION['seller_user_name']));
$row_sellers = $select_sellers->fetch();

$select_memb_plan_detail = $db->select("`memb_plan_detail` mp LEFT JOIN `membership_table` m ON mp.memb_tbl_id = m.id WHERE mp.seller_id = $login_seller_id AND mp.memb_status = 'Active' AND mp.memb_end_date >= CURRENT_TIMESTAMP ORDER BY mp.id DESC LIMIT 1");
$row_plan_detail = $select_memb_plan_detail->fetch();

// $checkuser = $db->select("memb_plan_detail where seller_id = $row_sellers->seller_id and memb_status = 'active'  order by id desc LIMIT 1");
// $row_purchsed = $checkuser->fetch();
if ($row_plan_detail) {
    $planName = $row_plan_detail->plan_name;
    // $exp_date = $row_plan_detail->memb_end_date;
    // $row_purchsed_detail = $db->select("membership_table where id = " . $row_plan_detail->memb_tbl_id . "  LIMIT 1");
    // $row_purchsed_plan = $row_purchsed_detail->fetch();
} else {
    $exp_date = 'new update';
    $row_purchsed_detail = $db->select("membership_table where id = 1  LIMIT 1");
    $row_purchsed_plan = $row_purchsed_detail->fetch();
    $planName = $row_purchsed_plan->plan_name;
}

$count_active_proposals = $db->count("proposals", array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'active'));
$count_total_proposals = $db->count("proposals", array("proposal_seller_id" => $login_seller_id));

$count_pause_proposals = $db->query("select * from proposals where proposal_seller_id=$login_seller_id and (proposal_status='pause' or proposal_status='admin_pause')")->rowCount();

$count_pending_proposals = $db->count("proposals", array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'pending'));

$count_modification_proposals = $db->count("proposals", array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'modification'));

$count_draft_proposals = $db->count("proposals", array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'draft'));

$count_declined_proposals = $db->count("proposals", array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'declined'));

$select_seller_accounts = $db->select("seller_accounts", array("seller_id" => $login_seller_id));
$row_seller_accounts = $select_seller_accounts->fetch();
$current_balance = $row_seller_accounts->current_balance;
$month_earnings = $row_seller_accounts->month_earnings;

// POST METHOD IN INDEX.PHP
$activeTab = "buyer";
if (isset($_COOKIE["bkmark_seller_" . $_SESSION['seller_user_name']])) {
    $activeTab = "seller";
}
?>

<style>
    .carousel-item img,
    .carousel-item video {
        height: auto !important;
        background-color: black;
    }

    #sub-category {
        width: auto;
    }

    .about-section-2 .nav.nav-tabs.flex-column.flex-sm-row,
    .tab-content {
        width: 100%;
    }

    .about-section-1 .nav-tabs .nav-link.active,
    .about-section-1 .nav-tabs .nav-item.show .nav-link {
        color: #fff;
        background-color: #00c8d4;
        width: 50%;
        text-align: center;
        font-weight: normal;
    }

    .about-section-1 .nav-tabs .nav-link {
        color: #495057;
        background-color: #fafafa;
        width: 50%;
        text-align: center;
        font-weight: normal;
        border: 1px solid #f0f2f2;
    }

    .about-section-1 .nav-tabs .nav-link.active::after {
        content: '';
        position: relative;
        left: -40px;
        top: 38px;
        width: 0;
        height: 0;
        border-left: 10px solid transparent;
        border-right: 10px solid transparent;
        border-top: 10px solid #00c8d4;
        clear: both;
    }

    .about-section-1 .nav-tabs {
        border-bottom: none;
    }

    .user_border {
        border: 1px solid #edefed;
        padding-top: 20px;
    }

    .hide {
        display: none;
    }

    .nitin-1 {
        margin: auto;
        width: 100%;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;

    }

    .proposals_image_div {
        width: 100%;
        height: 150px;
        overflow: hidden;
        padding: 5px;
    }


    /* respo-nsive-screen-for-size-less-then-768px-section-start */
    @media (max-width: 767px) {


        .alter-top-margin {
            /* margin-top: 50px !important; */
            padding: 0rem;
        }

        .b-t-m-resize {
            border: 1px solid lightblue;
            border-top: none;
        }

        #featured-candi-response {
            margin-top: 10px;
        }

        .o-view_all_styling-div {
            text-align: center;
            align-items: center;
            justify-content: center;
            display: flex;
        }






        .respo-for-mob-scr {
            /* background-color: #00C8D4; */
            /* border: 2px solid black; */
            justify-content: center;
            width: 100%;
        }

        #mobile_responsive_screen {
            display: block;
            margin: 50px auto;
            width: 100%;
        }

        #disp-lay-fl-ex {
            /* border:2px solid blue; */
            margin: auto;
        }

        .second-class-respo {
            /* border:2px solid red;  */
            font-size: 13px;
        }

        .btn-view-hire-respo {
            border: 2px solid #00C8D4;
            margin: auto;
            width: 100%;
        }

        .btn-view-hire-respo:hover {
            text-decoration: underline;
            border: 3px solid green;
        }

        .mobile-pic-respo-sett {
            display: none;
        }




        .row {
            margin-left: 0px;
            margin-right: 0px;
        }


        .col-1,
        .col-2,
        .col-3,
        .col-4,
        .col-5,
        .col-6,
        .col-7,
        .col-8,
        .col-9,
        .col-10,
        .col-11,
        .col-12,
        .col,
        .col-auto,
        .col-sm-1,
        .col-sm-2,
        .col-sm-3,
        .col-sm-4,
        .col-sm-5,
        .col-sm-6,
        .col-sm-7,
        .col-sm-8,
        .col-sm-9,
        .col-sm-10,
        .col-sm-11,
        .col-sm-12,
        .col-sm,
        .col-sm-auto,
        .col-md-1,
        .col-md-2,
        .col-md-3,
        .col-md-4,
        .col-md-5,
        .col-md-6,
        .col-md-7,
        .col-md-8,
        .col-md-9,
        .col-md-10,
        .col-md-11,
        .col-md-12,
        .col-md,
        .col-md-auto,
        .col-lg-1,
        .col-lg-2,
        .col-lg-3,
        .col-lg-4,
        .col-lg-5,
        .col-lg-6,
        .col-lg-7,
        .col-lg-8,
        .col-lg-9,
        .col-lg-10,
        .col-lg-11,
        .col-lg-12,
        .col-lg,
        .col-lg-auto,
        .col-xl-1,
        .col-xl-2,
        .col-xl-3,
        .col-xl-4,
        .col-xl-5,
        .col-xl-6,
        .col-xl-7,
        .col-xl-8,
        .col-xl-9,
        .col-xl-10,
        .col-xl-11,
        .col-xl-12,
        .col-xl,
        .col-xl-auto {
            position: relative;
            width: 100%;
            min-height: 1px;
            padding-right: 2px;
            padding-left: 2px;
        }





        .title-selling-order-headling {
            /* background-color: green; */
            text-align: center;
        }

        .text-align-center {
            text-align: center;
        }

        .heading_3 {
            font-size: 20px;
            width: 100%;
        }

        table tr td:first-child {
            min-width: none !important;
        }

        .text-align-right {
            margin-left: 10px;
            /* margin-top: 4px; */
        }

        .text_center {
            /* border: 2px solid green; */
            margin: auto !important;
            /* box-shadow: 1px 1px 7px black; */
        }

        .col_md_12 {
            /* border: 2px solid blue; */
            width: 100%;
            display: flex;
        }

        .heading_4 {
            font-size: 16px;
        }
    }

    .text-align-right {
        margin-left: 10px;
        /* margin-top: 4px; */
    }

    .margin-top-25 {
        margin-top: 0.25rem !important;
    }

    @media(min-width:768px) and (max-width:1024px) {
        .alter-top-margin {
            /* margin-top: 50px !important; */
            /* padding: 0px !important; */
        }

    }

    /* respo-nsive-screen-for-size-less-then-768px-section-end */
    /* respo-nsive-screen-for-size-between-768px-to-1600px-section-start */
    @media (min-width: 768px) and (max-width: 1600px) {
        .b-t-m-resize {
            border: 1px solid lightblue;
            border-top: none;
        }

        .alter-top-margin {
            /* margin-top: 50px !important; */
            padding: 2.5rem 2.5rem 0rem;
        }

        #featured-candi-response {
            margin-top: 10px;
        }

        .o-view_all_styling-div {
            height: 50px;
        }



        .second-class-respo {
            /* border:2px solid red;  */
            font-size: 13px;
        }

        .btn-view-hire-respo {
            border: 2px solid #00C8D4;
            margin: auto;
            width: 100%;
        }

        .btn-view-hire-respo:hover {
            text-decoration: underline;
            border: 3px solid green;
        }
    }

    .strong-text {
        font-size: 22px;
    }

    .box-shadow-bbody1 {
        /* box-shadow: inset 0px 0px 65px #ebfaf8; */
        border: 1px solid lightgrey;
    }



    .pl-6 {
        /* padding-left: 2rem !important; */
    }

    .textendstyle {
        text-align: end;
    }

    .card_footer_bottom_amount {
        display: flex;
        justify-content: space-between;
        padding: 1rem;
        background-color: #e5e5e54f;
    }

    .theme-color-starting-at {
        color: #00cedc;
        padding-top: 4px;
    }

    .create_proposal_atleast {
        display: flex;
        width: 100%;
        padding: 13px;
        border-radius: 3px;
        background-color: white;
        /* border: 1px solid #00C8D4; */
        margin-top: 1rem;
    }

    .papargraph_block {
        display: block;
        width: 100%;
        margin: auto;
        font-size: 19px;
        /* background-color: green; */
    }

    .icon_bblock {
        display: block;
        width: 30%;
        margin: auto;
        /* background-color: blue; */
    }

    .btn_bblock {
        display: block;
        width: 50%;
        margin: auto;
        text-align: center;
        /* background-color: red; */
    }

    .btn_get_btn {
        background-color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        border: 1px solid #00cedc;
        font-size: 18px;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .box-shadow-bbody {
            padding: 0px !important;
            margin: 0px !important;
        }
    }



    /* respo-nsive-screen-for-size-between-768px-to-1600px-section-end */
</style>
<script>
    function myBookmark(x) {
        var typeId = x.getAttribute('data-bookmark-seller');
        // console.log(typeId)
        // typeId == true ? x.classList.toggle("fa-bookmark-o") : x.classList.toggle("fa-bookmark");
        var form = document.createElement("form");
        var element1 = document.createElement("input");

        form.method = "POST";
        form.action = "";

        element1.value = typeId;
        element1.name = "seller_bookmark";
        form.appendChild(element1);

        document.body.appendChild(form);

        form.submit();
    }
    $(function() {
        // $("a#buyer_tab").click(function(){
        //     $('#buyer-sidebar').show();
        //     $('#seller-sidebar').hide();
        // });
        $('.nav-tabs a').click(function() {
            var tabValue = $(this).data('value')
            if (tabValue == "buyer") {
                $('body #buyer-sidebar').show();
                $('body #seller-sidebar').hide();
            } else {
                $('body #seller-sidebar').show();
                $('body #buyer-sidebar').hide();
            }
            $(this).tab('show');
        })
    })
</script>
<div class="container-fluid">
    <div class="row alter-top-margin">
        <div class="user_border buyer_box col-xl-3 mb-5 col-lg-3 box-shadow-bbody1 <?= ($lang_dir == "right" ? 'order-2 order-sm-1' : '') ?> hidden-xs">
            <?php include("includes/buyer_home_sidebar.php"); ?>
        </div>
        <div class="user_border seller_box hide col-xl-3 col-lg-3 box-shadow-bbody1 <?= ($lang_dir == "right" ? 'order-2 order-sm-1' : '') ?> hidden-xs">
            <?php include("includes/buyer_home_sidebar.php"); ?>
        </div>
        <div class="col-xl-9 col-lg-9 p-3 box-shadow-bbody">
            <div class="about-section-1">
                <div class="top_bas nav nav-tabs font-weight-bold text-largest" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link strech_full_scr  box-shadow-sfs <?= $activeTab == "buyer" ? "active" : "" ?>" data-toggle="tab" data-value="buyer" href="#Buyer" role="tab" aria-selected="<?= $activeTab == "buyer" ? "true" : "false" ?>" id="buyer_tab"><span class="strong-text"> Buyer </span></a>
                    <?php if ($activeTab == "seller") { ?>
                        <a class="nav-item nav-link active strech_full_scr box-shadow-sfs" data-toggle="tab" data-value="seller" href="#Seller" role="tab" aria-selected="true" id="seller_tab">Seller<i onclick="myBookmark(this)" data-bookmark-seller="no" class="fa fa-bookmark text-align-right" data-toggle="tooltip" data-placement="top" title="Remove Bookmark"></i></a>
                    <?php } else { ?>
                        <a class="nav-item nav-link  box-shadow-sfs" data-toggle="tab" data-value="seller" href="#Seller" role="tab" aria-selected="false" id="seller_tab"> <span class="strong-text"> Seller </span><i onclick="myBookmark(this)" data-bookmark-seller="yes" class="fa fa-bookmark-o" data-toggle="tooltip" data-placement="top" title="Add Bookmark"></i></a>
                    <?php } ?>
                </div>
            </div>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade <?= $activeTab == "buyer" ? "show active" : "" ?>" id="Buyer" role="tabpanel">
                    <div class="about-section-2 pt-5">
                        <div class="col-xs-12 px-1">
                            <div class="card rounded-0  box-shadow-buyer-body">
                                <div class="card-body p-0">
                                    <div class="row pl-3 pr-3 pb-2 pt-2 mt-4">
                                        <div class="col-xs-6 text-center border-box">
                                            <a href="purchases"> <?php $count_orders = $db->count("orders", array("buyer_id" => $login_seller_id, "order_active" => 'yes')); ?>
                                                <img width="" src="images/comp/shopping-bags.png" alt="shopping-bags">
                                                <h5 class="text-muted pt-2"> <?= $lang["dashboard"]['open_purchases']; ?></h5>
                                                <h3 class="text-success"><?= $count_orders; ?> </h3>
                                            </a>
                                        </div>
                                        <div class="col-xs-6 text-center border-box">
                                            <a href="revenue"><img width="" src="images/comp/accounting.png" alt="accounting">
                                                <h5 class="text-muted pt-2"> <?= $lang["dashboard"]['balance']; ?></h5>
                                                <h3 class="text-success"><?= showPrice($current_balance); ?></h3>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="">
                            <!-- Order start -->
                            <br>
                            <br>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <h1 class="text-center"><?= $lang["titles"]["buying_orders"]; ?></h1>
                                </div>
                                <div class="col-md-12">
                                    <?php $homePerPage = 5;
                                    include('user_buying_orders.php'); ?>
                                </div>
                            </div>
                            <!-- Order End -->

                            <!-- Start Manage Request -->
                            <div class="row">
                                <div class="col-md-12 mt-5 mb-3 margin-top-25">
                                    <h1 class="text-align-center"> <?= $lang["titles"]["manage_requests"]; ?> </h1>
                                </div>
                                <div class="col-md-12">
                                    <?php include('requests/manage_requests_body.php'); ?>
                                </div>
                            </div>
                            <!-- End Magage Request -->

                            <!-- Buyer Contacts -->
                            <div class="row">
                                <div class="col-md-12 mt-5 mb-3 margin-top-25">
                                    <h1 class="<?= ($lang_dir == "right" ? 'text-right' : '') ?> text-align-center"><?= $lang["titles"]["manage_contacts"]; ?></h1>
                                </div>
                                <div class="col-md-12">
                                    <?php include('buyer_contacts.php'); ?>
                                </div>
                            </div>
                            <!-- End Buyer Contacts -->
                            <!--  -->

                            <!-- top plan purchased people displayed section start -->

                            <!-- ######################################## -->
                            <!-- ############### NEW HTML CODE: END ############### --- ############### NEW HTML CODE: END ############### -->





                            <!-- top plan purchased people displayed section end -->


                            <!-- Recently Vi ewd Start -->
                            <div class="row" id="card_show">
                                <div class="col-md-12 mt-5 mb-3 margin-top-25">
                                    <h1 class="pull-left"> <?= $lang['sidebar']['recently_viewed']; ?> </h1>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="row">
                                        <?php
                                        $select_recent = $db->query("select * from recent_proposals WHERE seller_id='$login_seller_id' AND EXISTS (SELECT * FROM proposals WHERE proposals.proposal_id = recent_proposals.proposal_id AND proposals.proposal_status='active') order by 1 DESC LIMIT 0,3");
                                        $count_recent = $select_recent->rowCount();
                                        if ($count_recent == 0) {
                                            // echo "<p class='text-muted'> <i class='fa fa-frown-o'></i> {$lang['sidebar']['no_recently_viewed']} </p>";
                                            echo "<center>
                                 <h3 class='pt-4 pb-4'>
                                    <i class='fa fa-meh-o'></i> {$lang['sidebar']['no_recently_viewed']}
                                 </h3>
                              </center>";
                                        } else {
                                        ?>
                                            <?php
                                            $i = 0;
                                            $select_recent = $db->query("select * from recent_proposals where seller_id='$login_seller_id' order by 1 DESC LIMIT 0,3");
                                            while ($row_recent = $select_recent->fetch()) {
                                                $proposal_id = $row_recent->proposal_id;
                                                $get_proposals = $db->query("select * from proposals where proposal_id='$proposal_id' AND proposal_status='active'");
                                                $count_proposals = $get_proposals->rowCount();
                                                if ($count_proposals == 1) {
                                                    $i++;
                                                    $row_proposals = $get_proposals->fetch();
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


                                                    $get_delivery = $db->select("instant_deliveries", ['proposal_id' => $proposal_id]);
                                                    $row_delivery = $get_delivery->fetch();
                                                    $enable_delivery = $row_delivery->enable;

                                                    if ($videoPlugin == 1) {
                                                        $proposal_videosettings = $db->select("proposal_videosettings", array('proposal_id' => $proposal_id))->fetch();
                                                        $enableVideo = $proposal_videosettings->enable;
                                                    } else {
                                                        $enableVideo = 0;
                                                    }
                                            ?>
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 mb-4">
                                                        <div class="card-box card">
                                                            <div class="subcategory">
                                                                <div class="proposals_image_div">
                                                                    <a href="proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>">
                                                                        <picture class="card-img-top">
                                                                            <img class="rounded-0" src="<?= $proposal_img1; ?>">
                                                                        </picture>
                                                                    </a>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="d-flex">
                                                                        <div class="rounded-circle overflow-hidden d-flex justify-content-center align-items-center user-pic">
                                                                            <img src="<?= $seller_image; ?>" alt="" width="32" height="32">
                                                                        </div>
                                                                        <div class="px-3 d-flex justify-content-between w-100 align-items-center">
                                                                            <div class="">
                                                                                <a href="<?= $site_url; ?>/<?= $seller_user_name; ?>" class="seller-name">
                                                                                    <h5 class="card-title font-weight-bold mb-0"><?= $seller_user_name; ?></h5>
                                                                                </a>
                                                                                <!-- <small class="text-secondary">New Seller</small> -->
                                                                            </div>
                                                                            <!-- <div class="text-secondary">
                                                                              <i class="fa fa-heart"></i>
                                                                            </div> -->
                                                                        </div>
                                                                    </div>
                                                                    <p class="my-3">
                                                                        <?= truncate($proposal_title, 50); ?> <a href="<?= $site_url; ?>/<?= $seller_user_name; ?>" class="btn-link">
                                                                            Read more
                                                                        </a>
                                                                    </p>
                                                                    <!-- <p class="textendstyle mt-1"></p> -->
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <div class="font-weight-bold text-info">
                                                                            <i class="fa fa-star"></i>
                                                                            <span><?php if ($proposal_rating == "0") {
                                                                                        echo "0.0";
                                                                                    } else {
                                                                                        printf("%.1f", $average_rating);
                                                                                    } ?></span>
                                                                            <span class="font-weight-normal text-secondary">(<?= $count_reviews; ?>)</span>
                                                                        </div>
                                                                        <div class="brand-logo">
                                                                            <?= $seller_level; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card_footer_bottom_amount">
                                                                    <!-- <ul class="list-inline mb-0">
                                                                        <li class="list-inline-item dropdown">
                                                                            <a id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="text-muted fa fa-bars"></a>
                                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                              <a class="dropdown-item" href="#">Action</a>
                                                                              <a class="dropdown-item" href="#">Another action</a>
                                                                              <a class="dropdown-item" href="#">Something else here</a>
                                                                            </div>
                                                                        </li>
                                                                        <li class="list-inline-item">
                                                                          <a class="text-muted fa fa-heart"></a>
                                                                        </li>
                                                                    </ul> -->
                                                                    <div class="theme-color-starting-at">
                                                                        <strong>STARTING AT </strong>
                                                                    </div>
                                                                    <div>
                                                                        <strong class="text-largest pl-2"><?= showPrice($proposal_price); ?></strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>




                            <style>
                                .slider-container {
                                    display: flex;
                                    overflow: hidden;
                                    position: relative;
                                    gap: 16px;
                                    /* Adjust gap between cards if needed */
                                }

                                .slider-wrapper {
                                    display: flex;
                                    overflow-x: auto;
                                    scroll-snap-type: x mandatory;
                                    -webkit-overflow-scrolling: touch;
                                    gap: 16px;
                                    /* Adjust gap between cards if needed */
                                    scroll-behavior: smooth;
                                }

                                .slider-item {
                                    flex: 0 0 auto;
                                    scroll-snap-align: start;
                                    width: 100%;
                                    /* Ensures each card takes full width */
                                    box-sizing: border-box;
                                }

                                .slider-container::-webkit-scrollbar {
                                    display: none;
                                    /* Hide the scrollbar */
                                }

                                .slider-button {
                                    position: absolute;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    background-color: rgba(0, 0, 0, 0.5);
                                    color: white;
                                    border: none;
                                    padding: 10px;
                                    cursor: pointer;
                                    z-index: 10;
                                }

                                .slider-button-prev {
                                    left: 0;
                                }

                                .slider-button-next {
                                    right: 0;
                                }

                                @media (max-width: 768px) {
                                    .slider-wrapper {
                                        flex-wrap: nowrap;
                                    }
                                }

                                .slider-container {
                                    position: relative;
                                    overflow: hidden;
                                    width: 100%;
                                }

                                .slider-wrapper {
                                    display: flex;
                                    overflow-x: auto;
                                    scroll-snap-type: x mandatory;
                                    -webkit-overflow-scrolling: touch;
                                    gap: 16px;
                                    /* Adjust gap between cards if needed */
                                    scroll-behavior: smooth;
                                }

                                .slider-item {
                                    flex: 0 0 auto;
                                    scroll-snap-align: start;
                                    width: 100%;
                                    /* Ensures each card takes full width */
                                    box-sizing: border-box;
                                }

                                .slider-container::-webkit-scrollbar {
                                    display: none;
                                    /* Hide the scrollbar for WebKit browsers */
                                }

                                .slider-wrapper {
                                    scrollbar-width: none;
                                    /* Hide scrollbar for Firefox */
                                }

                                .slider-button {
                                    position: absolute;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    background-color: rgba(0, 0, 0, 0.5);
                                    color: white;
                                    border: none;
                                    padding: 10px;
                                    cursor: pointer;
                                    z-index: 10;
                                }

                                .slider-button-prev {
                                    left: 0;
                                }

                                .slider-button-next {
                                    right: 0;
                                }

                                @media (min-width: 600px) and (max-width: 768px) {
                                    #card_hide {
                                        width: 70% !important;
                                        margin-left:auto;
                                        margin-right: auto;
                                    }
                                }


                                @media (min-width: 768px) {
                                    #card_hide {
                                        display: none !important;
                                    }
                                }

                                @media (max-width: 768px) {
                                    #card_show {
                                        display: none !important;
                                    }

                                }
                            </style>

                            <div class="row" id="card_hide">
                                <div class="col-md-12 mt-5 mb-3 margin-top-25">
                                    <h1 class="pull-left"> <?= $lang['sidebar']['recently_viewed']; ?> </h1>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="slider-container">
                                        <button class="slider-button slider-button-prev">&lt;</button>
                                        <div class="slider-wrapper"> <!-- Slider Wrapper -->
                                            <?php
                                            $select_recent = $db->query("select * from recent_proposals WHERE seller_id='$login_seller_id' AND EXISTS (SELECT * FROM proposals WHERE proposals.proposal_id = recent_proposals.proposal_id AND proposals.proposal_status='active') order by 1 DESC LIMIT 0,3");
                                            $count_recent = $select_recent->rowCount();
                                            if ($count_recent == 0) {
                                                echo "<center>
                             <h3 class='pt-4 pb-4'>
                                <i class='fa fa-meh-o'></i> {$lang['sidebar']['no_recently_viewed']}
                             </h3>
                          </center>";
                                            } else {
                                                $i = 0;
                                                while ($row_recent = $select_recent->fetch()) {
                                                    $proposal_id = $row_recent->proposal_id;
                                                    $get_proposals = $db->query("select * from proposals where proposal_id='$proposal_id' AND proposal_status='active'");
                                                    $count_proposals = $get_proposals->rowCount();
                                                    if ($count_proposals == 1) {
                                                        $i++;
                                                        $row_proposals = $get_proposals->fetch();
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

                                                        $get_seller = $db->select("sellers", array("seller_id" => $proposal_seller_id));
                                                        $row_seller = $get_seller->fetch();
                                                        $seller_user_name = $row_seller->seller_user_name;
                                                        $seller_image = getImageUrl2("sellers", "seller_image", $row_seller->seller_image);
                                                        $seller_level = $row_seller->seller_level;
                                                        $seller_status = $row_seller->seller_status;
                                                        if (empty($seller_image)) {
                                                            $seller_image = "empty-image.png";
                                                        }
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
                                                        $get_delivery = $db->select("instant_deliveries", ['proposal_id' => $proposal_id]);
                                                        $row_delivery = $get_delivery->fetch();
                                                        $enable_delivery = $row_delivery->enable;
                                                        if ($videoPlugin == 1) {
                                                            $proposal_videosettings = $db->select("proposal_videosettings", array('proposal_id' => $proposal_id))->fetch();
                                                            $enableVideo = $proposal_videosettings->enable;
                                                        } else {
                                                            $enableVideo = 0;
                                                        }
                                            ?>
                                                        <div class="slider-item"> <!-- Slider Item -->
                                                            <div class="card-box card">
                                                                <div class="subcategory">
                                                                    <div class="proposals_image_div">
                                                                        <a href="proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>">
                                                                            <picture class="card-img-top">
                                                                                <img class="rounded-0" src="<?= $proposal_img1; ?>">
                                                                            </picture>
                                                                        </a>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="d-flex">
                                                                            <div class="rounded-circle overflow-hidden d-flex justify-content-center align-items-center user-pic">
                                                                                <img src="<?= $seller_image; ?>" alt="" width="32" height="32">
                                                                            </div>
                                                                            <div class="px-3 d-flex justify-content-between w-100 align-items-center">
                                                                                <div class="">
                                                                                    <a href="<?= $site_url; ?>/<?= $seller_user_name; ?>" class="seller-name">
                                                                                        <h5 class="card-title font-weight-bold mb-0"><?= $seller_user_name; ?></h5>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <p class="my-3">
                                                                            <?= truncate($proposal_title, 50); ?> <a href="<?= $site_url; ?>/<?= $seller_user_name; ?>" class="btn-link">
                                                                                Read more
                                                                            </a>
                                                                        </p>
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <div class="font-weight-bold text-info">
                                                                                <i class="fa fa-star"></i>
                                                                                <span><?php if ($proposal_rating == "0") {
                                                                                            echo "0.0";
                                                                                        } else {
                                                                                            printf("%.1f", $average_rating);
                                                                                        } ?></span>
                                                                                <span class="font-weight-normal text-secondary">(<?= $count_reviews; ?>)</span>
                                                                            </div>
                                                                            <div class="brand-logo">
                                                                                <?= $seller_level; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card_footer_bottom_amount">
                                                                        <div class="theme-color-starting-at">
                                                                            <strong>STARTING AT </strong>
                                                                        </div>
                                                                        <div>
                                                                            <strong class="text-largest pl-2"><?= showPrice($proposal_price); ?></strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                        <button class="slider-button slider-button-next">&gt;</button>
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const slider = document.querySelector('.slider-wrapper');
                                    const prevButton = document.querySelector('.slider-button-prev');
                                    const nextButton = document.querySelector('.slider-button-next');

                                    prevButton.addEventListener('click', () => {
                                        slider.scrollBy({
                                            left: -window.innerWidth,
                                            behavior: 'smooth'
                                        });
                                    });

                                    nextButton.addEventListener('click', () => {
                                        slider.scrollBy({
                                            left: window.innerWidth,
                                            behavior: 'smooth'
                                        });
                                    });
                                });
                            </script>












                            <!-- Recently Viewed End -->

                            <!-- Buy again Start -->
                            <div class="row">
                                <div class="col-md-12 mt-5 mb-3 margin-top-25">
                                    <h1 class="pull-left"> Buy again from your favorite
                                        sellers </h1>
                                </div>

                                <?php include('buy_it_again.php'); ?>
                            </div>
                            <!-- Buy again End -->
                            <!-- Buyer Videos -->

                            <!-- Seller Videos -->
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade <?= $activeTab == "seller" ? "show active" : "" ?>" id="Seller" role="tabpanel">
                    <div>
                        <div id="seller-sidebar" style="<?= $activeTab == "buyer" ? "display: none" : "" ?>">

                        </div>


                        <div class="create_proposal_atleast">
                            <div class="icon_bblock"> <img src="images/sales.png" class="img-fluid center-block" alt="none"></div>
                            <div class="papargraph_block"><?= $lang['sidebar']['start_selling']['desc']; ?></div>
                            <div class="btn_bblock"> <button onclick="location.href='settings?profile_settings'" class="btn_get_btn">
                                    <?= $lang['sidebar']['start_selling']['button']; ?>
                                </button></div>
                        </div>

                        <div class="about-section-2 pt-5">
                            <div class="">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="alert alert-info col-xs-12 text-align-center box-shadow-yham py-3">
                                            Current Membership- <?php echo $planName; ?>
                                            <?php if (!$row_purchsed) {
                                                echo 'please <a href="membership_subs">upgrade</a> ';
                                            } ?></php>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="card rounded-0 box-shadow-start-btm">
                                            <div class="card-body p-0">
                                                <div class="row pl-3 pr-3 pb-2 pt-2 mt-4">
                                                    <div class="col-md-4 col-sm-6 text-center border-box">
                                                        <?php
                                                        $count_orders = $db->count("orders", array("seller_id" => $login_seller_id, "order_status" => 'completed'));
                                                        ?>
                                                        <a href="selling_orders">
                                                            <img width="" src="images/comp/completed.png" alt="completed">
                                                            <h5 class="text-muted pt-2"> <?= $lang["dashboard"]['orders_completed']; ?></h5>
                                                            <h3 class="text-success"><?= $count_orders; ?></h3>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6 text-center border-box">
                                                        <?php
                                                        $count_orders = $db->count("orders", array("seller_id" => $login_seller_id, "order_active" => 'yes'));
                                                        ?>
                                                        <a href="selling_orders">
                                                            <img width="" src="images/comp/debt.png" alt="debt">
                                                            <h5 class="text-muted pt-2"> <?= $lang["dashboard"]['sales_in_queue']; ?></h5>
                                                            <h3 class="text-success"><?= $count_orders; ?></h3>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6 text-center border-box">
                                                        <a href="revenue">
                                                            <img width="" src="images/comp/financial.png" alt="financial">
                                                            <h5 class="text-muted pt-2"> <?= $lang["dashboard"]['earnings']; ?> </h5>
                                                            <h3 class="text-success"><?= showPrice($month_earnings); ?></h3>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-5">
                                        <h1 class="<?= ($lang_dir == "right" ? 'text-right' : '') ?> title-selling-order-headling"><?= $lang["titles"]["selling_orders"]; ?></h1>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <?php include('user_selling_orders.php'); ?>
                                    </div>
                                </div>
                                <!-- Buyer request -->
                                <div class="row buyer-requests">
                                    <div class="col-md-12 mt-3">
                                        <h1 class="<?= ($lang_dir == "right" ? 'text-right' : '') ?> text-align-center"><?= $lang["titles"]["buyer_requests"]; ?></h1>
                                    </div>
                                    <div class="col-md-12">
                                        <?php
                                        include('requests/user_buyer_requests.php');
                                        ?>
                                    </div>
                                </div>
                                <!-- End Buyer Request  -->
                                <!-- Proposal -->
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <h1 class="<?= ($lang_dir == "right" ? 'text-right' : '') ?> text-align-center"><?= $lang["titles"]["view_proposals"]; ?></h1>
                                    </div>
                                    <div class="append-modal"></div>
                                    <?php
                                    $active = "active";
                                    include('proposals/user_view_proposal.php');
                                    ?>
                                </div>
                                <!-- End Proposal -->
                                <!-- Buyer Contacts -->
                                <div class="row">

                                    <div class="col-md-12 mt-3">
                                        <h1 class="<?= ($lang_dir == "right" ? 'text-right' : '') ?> text-align-center"><?= $lang["titles"]["manage_contacts"]; ?> </h1>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <?php include('seller_contacts.php'); ?>
                                    </div>
                                </div>
                                <!-- End Buyer Contacts -->
                                <!-- Seller Videos -->

                                <!-- Seller Videos -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="third_section_div_style desktop-view-only">
    <div class="third_sectioninner_div_style">
        <div class="third_sectioninner_60">
            <p class="margin_according_content color_theme_color">Freelance talent hub</p>
            <h2 class="margin_according_content mb-3">Access a world of independent talent.</h2>
            <div class="third_sectioninner_60paragraph">
                <div class="main-list-content-div-fth">
                    <div class="image-icon-fth"><img src="images/first_icon.png" alt="no image" width="100%" class="m-auto">
                    </div>
                    <div class="content-img-fth">
                        <p>
                            "Hiremyprofile" offers a user-friendly platform where individuals can build comprehensive profiles showcasing their skills, experience, and expertise across various industries.
                        </p>
                    </div>
                </div>

                <div class="main-list-content-div-fth">
                    <div class="image-icon-fth"><img src="images/quality_icon.png" alt="no image" width="100%" class="m-auto">
                    </div>
                    <div class="content-img-fth">
                        <p>
                            Employers can efficiently explore these profiles to identify candidates who align with their project needs or job openings.
                        </p>
                    </div>
                </div>

                <div class="main-list-content-div-fth">
                    <div class="image-icon-fth"><img src="images/payment_icon.png" alt="no image" width="100%" class="m-auto">
                    </div>
                    <div class="content-img-fth">
                        <p>
                            The platform enhances communication channels, enabling direct interaction between employers and job seekers to discuss opportunities.
                        </p>
                    </div>
                </div>


                <div class="main-list-content-div-fth">
                    <div class="image-icon-fth"><img src="images/fourth_icon.png" alt="no image" width="100%" class="m-auto">
                    </div>
                    <div class="content-img-fth">
                        <p>
                            With detailed insights into each candidate's qualifications, "Hiremyprofile" aims to facilitate successful matches and productive collaborations in the hiring process.
                        </p>
                    </div>
                </div>

            </div>
        </div>
        <div class="third_sectioninner_40">
            <video width="100%" id="videotemp">
                <source src="videos/yjky.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="play-icon" id="play-icon"><i class="fa fa-play"></i></div>

            <!-- <iframe width="100%" height="100%" src="videos/yjky.mp4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
        </div>
    </div>
</div>
<!-- Container ends -->
<div class="append-modal"></div>
<div id="featured-proposal-modal"></div>
<div id="quota-finish" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h5"><i class="fa fa-frown-o fa-move-up"></i> Request Quota Reached</h5>
                <button class="close" data-dismiss="modal"> &times;</button>
            </div>
            <div class="modal-body">
                <center>
                    <h5>You can only send a max of 10 offers per day. Today you've maxed out. Try again tomorrow. </h5>
                </center>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // $(".carousel-indicators").css({"bottom": "75px"});

        var slider = $('#demo3').carousel({
            interval: 4000
        });

        var active = $(".carousel-item.active").find("video");
        var active_length = active.length;

        if (active_length == 1) {
            slider.carousel('pause');
            $(".carousel-indicators").css({
                "bottom": "75px"
            });
        }

        $("#demo3").on('slide.bs.carousel', function(event) {
            var eq = event.to;
            var video = $(event.relatedTarget).find("video");
            if (video.length == 1) {
                slider.carousel('pause');
                $(".carousel-indicators").css({
                    "bottom": "75px"
                });
                video.trigger('play');
            } else {
                $(".carousel-indicators").css({
                    "bottom": "20px"
                });
            }
        });

        $('video').on('ended', function() {
            slider.carousel({
                'pause': false
            });
        });

        $('#buyer_tab').on('click', function() {
            $('.seller_tab').addClass('hide');
            $('.buyer_tab').removeClass('hide');
            $('.seller_box').addClass('hide');
            $('.buyer_box').removeClass('hide');
        });
        $('#seller_tab').on('click', function() {
            $('.buyer_tab').addClass('hide');
            $('.seller_tab').removeClass('hide');
            $('.buyer_box').addClass('hide');
            $('.seller_box').removeClass('hide');
        });

    });
</script>
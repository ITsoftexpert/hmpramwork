<?php
// error_reporting(0);
session_start();
require_once("../includes/db.php");
require_once("../functions/functions.php");

// if(isset($_SESSION['seller_user_name'])){
// 	echo "<script>window.open('{$site_url}/login','_self')</script>";
// }

if (isset($_GET['cat_url'])) {
    if (isset($_GET['cat_child_url'])) {
        $array = explode("/", $input->get('cat_url'));
        $cat_url = $array[0];
    } else {
        $cat_url = $input->get('cat_url');
    }
    unset($_SESSION['cat_child_id']);
    unset($_SESSION['attr_id']); // Unset sub-sub-category session
    unset($_SESSION['skill_id']);

    $get_cat = $db->select("categories", array('cat_url' => urlencode($cat_url)));
    $count_cat = $get_cat->rowCount();
    if ($count_cat == 0) {
        // echo "<script>window.open('$site_url/index?not_available','_self');</script>";
    }
    $cat_id = $get_cat->fetch()->cat_id;
    $_SESSION['cat_id'] = $cat_id;
}

if (isset($_GET['cat_child_url'])) {
    unset($_SESSION['cat_id']);
    unset($_SESSION['attr_id']); // Unset sub-sub-category session
    unset($_SESSION['skill_id']);

    $get_cat = $db->select("categories", array('cat_url' => urlencode($cat_url)));
    $cat_id = $get_cat->fetch()->cat_id;

    $get_child = $db->select("categories_children", array('child_parent_id' => $cat_id, 'child_url' => urlencode($input->get('cat_child_url'))));
    $count_child = $get_child->rowCount();
    if ($count_child == 0) {
        // echo "<script>window.open('$site_url/index?not_available','_self');</script>";
    }
    $child_id = $get_child->fetch()->child_id;
    $_SESSION['cat_child_id'] = $child_id;
}

if (isset($_GET['cat_attr'])) {
    unset($_SESSION['cat_id']);
    unset($_SESSION['cat_child_id']);
    unset($_SESSION['skill_id']);
    $get_cat = $db->select("categories", array('cat_url' => urlencode($cat_url)));
    $cat_id = $get_cat->fetch()->cat_id;
    $get_child = $db->select("categories_children", array('child_parent_id' => $cat_id, 'child_url' => urlencode($input->get('cat_child_url'))));


    $get_attr_child = $db->select("cat_attribute", array('cat_attr' => urlencode($input->get('cat_attr'))));
    $sub_sub_child = $get_attr_child->fetch();
    $attr_title = $sub_sub_child->cat_attr;
    $attr_id = $sub_sub_child->attr_id;
    $child_id = $sub_sub_child->child_id;
    $count_attr_child = $get_attr_child->rowCount();
    if ($count_attr_child == 0) {
        // echo "<script>window.open('$site_url/index?not_available','_self');</script>";
    } else {
        $_SESSION['attr_id'] = $attr_id;
    }
}

// skill filter query/code

if (isset($_GET['skill_url'])) {
    // var_dump("hello");
    unset($_SESSION['cat_id']);
    unset($_SESSION['cat_child_id']);
    unset($_SESSION['attr_id']);
    $get_all_skill = $db->select("seller_skills", array('skill_url' => urlencode($input->get('skill_url'))));
    $row_all_skill = $get_all_skill->fetch();
    $skill_title = $row_all_skill->skill_title;
    $skill_id = $row_all_skill->skill_id;
    $skill_url = $row_all_skill->skill_url;

    $count_all_skill = $get_all_skill->rowCount();
    if ($count_all_skill == 0) {
        // echo "<script>window.open('$site_url/index?not_available','_self');</script>";
    } else {
        $_SESSION['skill_id'] = $skill_id;
    }
} else {
    // var_dump("hello2");
}



?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
    <!-- category -->
    <?php
    if (isset($_SESSION['cat_id'])) {
        $cat_id = $_SESSION['cat_id'];
        $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
        $row_meta = $get_meta->fetch();
        $cat_title = $row_meta->cat_title;
        $cat_desc = $row_meta->cat_desc;
    ?>
        <title><?= $site_name; ?> - <?= $cat_title; ?> </title>
        <meta name="description" content="<?= $cat_desc; ?>">
    <?php } ?>
    <!-- end category -->
    <!-- sub category -->
    <?php
    if (isset($_SESSION['cat_child_id'])) {
        $cat_child_id = $_SESSION['cat_child_id'];
        $get_meta = $db->select("child_cats_meta", array("child_id" => $cat_child_id, "language_id" => $siteLanguage));
        $row_meta = $get_meta->fetch();
        $child_title = $row_meta->child_title;
        $child_desc = $row_meta->child_desc;
    ?>
        <title><?= $site_name; ?> - <?= $child_title; ?></title>
        <meta name="description" content="<?= $child_desc; ?>">
    <?php } ?>
    <!-- end sub category -->
    <!-- attribute -->
    <?php
    if (isset($_SESSION['skill_id'])) {
        $skill_id = $_SESSION['skill_id'];
        $get_meta = $db->select("seller_skills", array("skill_id" => $skill_id));
        $row_meta = $get_meta->fetch();
        $skill_title = $row_meta->skill_title;
        // $child_desc = $row_meta->child_desc;
    ?>
        <title><?= $site_name; ?> - <?= $skill_title; ?></title>
        <!-- <meta name="description" content="<?= $child_desc; ?>"> -->
    <?php } ?>
    <!-- attribute end -->

    <!-- skill details -->
    <?php
    if (isset($_SESSION['attr_id'])) {
        $attr_id = $_SESSION['attr_id'];
        $get_meta = $db->select("sub_subcategories", array("attr_id" => $attr_id, "language_id" => $siteLanguage));
        $row_meta = $get_meta->fetch();
        $sub_subcategory_name = $row_meta->sub_subcategory_name;
        // $child_desc = $row_meta->child_desc;
    ?>
        <title><?= $site_name; ?> - <?= $sub_subcategory_name; ?></title>
        <!-- <meta name="description" content="<?= $child_desc; ?>"> -->
    <?php } ?>
    <!-- skill details -->



    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="<?= $site_author; ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
    <link href="<?= $site_url; ?>/styles/bootstrap.css" rel="stylesheet">
    <link href="<?= $site_url; ?>/styles/custom.css" rel="stylesheet">
    <!-- Custom css code from modified in admin panel --->
    <link href="<?= $site_url; ?>/styles/styles.css" rel="stylesheet">
    <link href="<?= $site_url; ?>/styles/categories_nav_styles.css" rel="stylesheet">
    <link href="<?= $site_url; ?>/font_awesome/css/font-awesome.css" rel="stylesheet">
    <link href="<?= $site_url; ?>/styles/sweat_alert.css" rel="stylesheet">
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="<?= $site_url; ?>/js/ie.js"></script>
    <script type="text/javascript" src="<?= $site_url; ?>/js/sweat_alert.js"></script>
    <script type="text/javascript" src="<?= $site_url; ?>/js/jquery.min.js"></script>
    <?php if (!empty($site_favicon)) { ?>
        <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
    <?php } ?>


</head>

<body class="bg-white is-responsive">
    <?php require_once("../includes/header.php"); ?>

    <div class="container-fluid pb-4 pt-4 px-4">
        <!-- Container start -->

        <!-- display_images.php -->


        <div class="row padding-alter11 padding-alter11a">
            <div class="col-md-12 margin-bottom-minus">
                <center>
                    <?php
                    if (isset($_SESSION['cat_id'])) {
                        $cat_id = $_SESSION['cat_id'];
                        $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
                        $row_meta = $get_meta->fetch();
                        $cat_title = $row_meta->cat_title;
                        $cat_desc = $row_meta->cat_desc;
                    ?>
                        <h1 class="margin-top-minus"> <?= $cat_title; ?> </h1>
                        <p class="lead background-green"><?= $cat_desc; ?></p>

                    <?php } ?>
                    <!-- category -->

                    <?php
                    if (isset($_SESSION['cat_child_id'])) {
                        $cat_child_id = $_SESSION['cat_child_id'];
                        $get_meta = $db->select("child_cats_meta", array("child_id" => $cat_child_id, "language_id" => $siteLanguage));
                        $row_meta = $get_meta->fetch();
                        $child_title = $row_meta->child_title;
                        $child_desc = $row_meta->child_desc;
                    ?>
                        <h1> <?= $child_title; ?> </h1>
                        <p class="lead"><?= $child_desc; ?></p>
                    <?php  } ?>
                    <!-- sub category -->

                    <?php
                    if (isset($_SESSION['attr_id'])) {
                        $attr_id = $_SESSION['attr_id'];
                        $get_meta = $db->select("sub_subcategories", array("attr_id" => $attr_id, "language_id" => $siteLanguage));
                        $row_meta = $get_meta->fetch();
                        $sub_subcategory_name = $row_meta->sub_subcategory_name;
                        // $child_desc = $row_meta->child_desc;
                    ?>
                        <h1> <?= $sub_subcategory_name; ?> </h1>
                        <!-- <p class="lead"><?= $child_desc; ?></p> -->
                    <?php  } ?>
                    <!-- attribute -->

                    <?php
                    if (isset($_SESSION['skill_id'])) {
                        $skill_id = $_SESSION['skill_id'];
                        $get_meta = $db->select("seller_skills", array("skill_id" => $skill_id));
                        $row_meta = $get_meta->fetch();
                        $skill_title = $row_meta->skill_title;
                        // $child_desc = $row_meta->child_desc;
                    ?>
                        <h1> <?= $skill_title; ?></h1>
                        <!-- <meta name="description" content="<?= $child_desc; ?>"> -->
                    <?php } ?>
                    <!-- attribute end -->
                </center>
                <div class="sub-sub-category-slider-outer ">
                    <div class="sub-sub-category-slider">
                        <?php
                        if (isset($_SESSION['cat_child_id'])) {
                            $cat_child_id = $_SESSION['cat_child_id'];

                            $get_senior_cat =  $db->select("categories_children", array("child_id" => $cat_child_id));
                            $row_senior_cat = $get_senior_cat->fetch();
                            $child_url = $row_senior_cat->child_url;
                            $child_parent_id = $row_senior_cat->child_parent_id;

                            $get_parent_cat =  $db->select("categories", array("cat_id" => $child_parent_id));
                            $row_parent_cat = $get_parent_cat->fetch();
                            $cat_url = $row_parent_cat->cat_url;

                            $get_sub_sub_cat = $db->select("cat_attribute", array("child_id" => $cat_child_id, "language_id" => $siteLanguage));
                            while ($row_sub_sub_cat = $get_sub_sub_cat->fetch()) {
                                $attr_id = $row_sub_sub_cat->attr_id;
                                $cat_attr = $row_sub_sub_cat->cat_attr;

                                // Fetching sub-subcategory details
                                $get_sub_meta = $db->select("sub_subcategories", array("attr_id" => $attr_id, "language_id" => $siteLanguage));
                                while ($row_sub_meta = $get_sub_meta->fetch()) {
                                    $sub_subcategory_name = $row_sub_meta->sub_subcategory_name;
                                    $image = $row_sub_meta->image;
                                    $image_path = $site_url . "/uploads/icons/" . $image; // Adjust this path according to your setup
                        ?>
                                    <div class="sub-sub-category-tablet">

                                        <a class="nav-link text-info d-flex p-0 <?php if ($attr_id == @$_SESSION['attr_id']) {
                                                                                    echo "active";
                                                                                } ?>" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>/<?= $cat_attr; ?>">
                                            <div class="icon-sub-sub-category"><img class="image-style-icon" src="<?= $image_path; ?>" alt="img"></div>
                                            <div class="sub_subcategory_name_showing"><?= $sub_subcategory_name; ?> </div>
                                        </a>
                                    </div>
                        <?php
                                }
                            }
                        }
                        ?>
                    </div>
                    <button class="prev-slide slide-button" onclick="moveSlide(-1)">&#10094;</button>
                    <button class="next-slide slide-button" onclick="moveSlide(1)">&#10095;</button>
                </div>

                <style>
                    .sub-sub-category-slider-outer {
                        position: relative;
                        width: 100%;
                        overflow: hidden;
                    }

                    .sub-sub-category-slider {
                        display: flex;
                        transition: transform 0.5s ease-in-out;
                    }

                    .sub-sub-category-tablet {
                        border: 1px solid #d3d3d3a8;
                        margin: auto 1rem;
                        padding: 0.5rem;
                        border-radius: 50px;
                        flex: 0 0 auto;
                        /* Prevent shrinking or expanding */
                    }

                    .sub-sub-category-tablet:hover {
                        border: 1px solid grey;
                    }

                    .icon-sub-sub-category {
                        background-color: #d3d3d342;
                        width: 3.5rem;
                        height: 3.5rem;
                        /* border: 1px solid lightgrey; */
                        border-radius: 50px;
                        margin-right: 1rem;
                        display: flex;
                    }

                    .image-style-icon {
                        width: 75%;
                        margin: auto;
                        border-radius: 10px;
                    }

                    .sub_subcategory_name_showing {
                        padding-right: 1rem;
                        align-content: center;
                    }

                    .slide-button {
                        position: absolute;
                        top: 50%;
                        transform: translateY(-50%);
                        background-color: rgba(0, 0, 0, 0.5);
                        color: white;
                        border: none;
                        padding: 10px;
                        cursor: pointer;
                    }

                    .prev-slide {
                        left: 10px;
                    }

                    .next-slide {
                        right: 10px;
                    }

                    /* skills */
                    .skills_name_showing {
                        border: 1px solid #d3d3d3a8;
                        width: auto;
                        /* background-color: #d3d3d330; */
                        padding: 1rem 2rem;
                        border-radius: 50px;
                        height: 4rem;
                        align-content: center;
                    }

                    .skills_name_showing:hover {
                        background-color: #d3d3d3a8;
                    }
                </style>
                <script>
                    let currentIndex = 0;

                    function showSlide(index) {
                        const slides = document.querySelector('.sub-sub-category-slider');
                        const totalSlides = slides.children.length;
                        if (index >= totalSlides) {
                            currentIndex = 0;
                        } else if (index < 0) {
                            currentIndex = totalSlides - 1;
                        } else {
                            currentIndex = index;
                        }
                        slides.style.transform = `translateX(-${currentIndex * 50 / totalSlides}%)`;
                    }

                    function moveSlide(step) {
                        showSlide(currentIndex + step);
                    }

                    // Initialize the slider
                    showSlide(currentIndex);
                </script>

                <div class="w-100 d-flex">
                    <?php





                    if (isset($_SESSION['attr_id'])) {
                        $attr_id = $_SESSION['attr_id'];
                        $get_meta = $db->select("sub_subcategories", array("attr_id" => $attr_id));
                        $row_meta = $get_meta->fetch();
                        $attr_id = $row_meta->attr_id;
                        $subcategory_id = $row_meta->subcategory_id;



                        $get_sub_sub_cat = $db->select("cat_attribute", array("attr_id" => $attr_id));
                        $row_sub_sub_cat = $get_sub_sub_cat->fetch();
                        $attr_id = $row_sub_sub_cat->attr_id;
                        $cat_attr = $row_sub_sub_cat->cat_attr;
                        $child_id = $row_sub_sub_cat->child_id;


                        $get_senior_cat =  $db->select("categories_children", array("child_id" => $child_id));
                        $row_senior_cat = $get_senior_cat->fetch();
                        $child_url = $row_senior_cat->child_url;
                        $child_parent_id = $row_senior_cat->child_parent_id;

                        $get_parent_cat =  $db->select("categories", array("cat_id" => $child_parent_id));
                        $row_parent_cat = $get_parent_cat->fetch();
                        $cat_url = $row_parent_cat->cat_url;

                        $get_all_skill = $db->select("seller_skills", array("sub_child_id" => $attr_id));
                        while ($row_all_skill = $get_all_skill->fetch()) {
                            $skill_title = $row_all_skill->skill_title;
                            $skill_id = $row_all_skill->skill_id;
                            $skill_url = $row_all_skill->skill_url;


                    ?>


                            <a class="nav-link text-info d-flex p-0 <?php if ($skill_id == @$_SESSION['skill_id']) {
                                                                        echo "active";
                                                                    } ?>" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>/<?= $cat_attr; ?>/<?= $skill_url; ?>">
                                <!-- <div class="icon-sub-sub-category"></div> -->
                                <div class="skills_name_showing"><?= $skill_title; ?></div>
                            </a>
                    <?php
                        }
                    }
                    ?>
                </div>





                <hr class="mt-3 pt-2">
            </div>
        </div>
        <div class="row mt-3 padding-alter11a">
            <div class="col-lg-3 col-md-4 col-sm-12 <?= ($lang_dir == "right" ? 'order-2 order-sm-1' : '') ?>">
                <?php require_once("../includes/category_sidebar.php"); ?>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12 <?= ($lang_dir == "right" ? 'order-1 order-sm-2' : '') ?>">
                <div class="row flex-wrap proposals box-shadow-fwp <?= ($lang_dir == "right" ? 'justify-content' : '') ?>" id="category_proposals">
                    <?php get_category_proposals(); ?>
                </div>
                <div id="wait"></div>
                <br>
                <div class="row justify-content-center mb-5 mt-0">
                    <!-- row justify-content-center Starts -->
                    <nav>
                        <!-- nav Starts -->
                        <ul class="pagination" id="category_pagination">
                            <?php get_category_pagination(); ?>
                        </ul>
                    </nav>
                    <!-- nav Ends -->
                </div>
            </div>
        </div>
    </div>
    <?php // } 
    ?>
    <!-- Container ends -->
    <div class="append-modal"></div>
    <?php require_once("../includes/footer.php"); ?>
    <?php if ($seller_verification == "ok" or $seller_verification != "ok") { ?>
        <script>
            function get_category_proposals() {

                var sPath = '';

                var aInputs = $('li').find('.get_online_sellers');
                var aKeys = Array();
                var aValues = Array();

                iKey = 0;

                $.each(aInputs, function(key, oInput) {

                    if (oInput.checked) {
                        aKeys[iKey] = oInput.value
                    };

                    iKey++;

                });

                if (aKeys.length > 0) {

                    var sPath = '';

                    for (var i = 0; i < aKeys.length; i++) {

                        sPath = sPath + 'online_sellers[]=' + aKeys[i] + '&';

                    }

                }

                var instant_delivery = $('.get_instant_delivery:checked').val();
                sPath = sPath + 'instant_delivery[]=' + instant_delivery + '&';

                var order = $('.get_order:checked').val();
                sPath = sPath + 'order[]=' + order + '&';

                var aInputs = $('li').find('.get_seller_country');
                var aKeys = Array();
                var aValues = Array();
                iKey = 0;
                $.each(aInputs, function(key, oInput) {
                    if (oInput.checked) {
                        aKeys[iKey] = oInput.value
                    };
                    iKey++;
                });
                if (aKeys.length > 0) {
                    for (var i = 0; i < aKeys.length; i++) {
                        sPath = sPath + 'seller_country[]=' + aKeys[i] + '&';
                    }
                }

                var aInputs = $('li').find('.get_seller_city');
                var aKeys = Array();
                var aValues = Array();
                iKey = 0;
                $.each(aInputs, function(key, oInput) {
                    if (oInput.checked) {
                        aKeys[iKey] = oInput.value
                    };
                    iKey++;
                });
                if (aKeys.length > 0) {
                    for (var i = 0; i < aKeys.length; i++) {
                        sPath = sPath + 'seller_city[]=' + aKeys[i] + '&';
                    }
                }


                var cat_url = "<?= $input->get('1`'); ?>";

                sPath = sPath + 'cat_url=' + cat_url + '&';

                <?php if (isset($_REQUEST['cat_child_url'])) { ?>

                    var cat_child_url = "<?= $input->get('cat_child_url'); ?>";

                    sPath = sPath + 'cat_child_url=' + cat_child_url + '&';

                    var url_plus = "../";

                <?php } else { ?>

                    var url_plus = "";

                <?php } ?>

                <?php if (isset($_REQUEST['cat_attr'])) { ?>
                    var cat_attr = "<?= $input->get('cat_attr'); ?>";
                    sPath = sPath + 'cat_attr=' + cat_attr + '&';

                    var url_plus = "../";

                <?php } else { ?>

                    var url_plus = "";

                <?php } ?>


                // skill

                <?php if (isset($_REQUEST['skill_url'])) { ?>
                    var skill_url = "<?= $input->get('skill_url'); ?>";
                    sPath = sPath + 'skill_url=' + skill_url + '&';

                    var url_plus = "../";

                <?php } else { ?>

                    var url_plus = "";

                <?php } ?>
                // skill


                var aInputs = Array();

                var aInputs = $('li').find('.get_delivery_time');

                var aKeys = Array();

                var aValues = Array();

                iKey = 0;

                $.each(aInputs, function(key, oInput) {

                    if (oInput.checked) {

                        aKeys[iKey] = oInput.value

                    };

                    iKey++;

                });

                if (aKeys.length > 0) {

                    for (var i = 0; i < aKeys.length; i++) {

                        sPath = sPath + 'delivery_time[]=' + aKeys[i] + '&';

                    }

                }

                var aInputs = Array();

                var aInputs = $('li').find('.get_seller_level');

                var aKeys = Array();

                var aValues = Array();

                iKey = 0;

                $.each(aInputs, function(key, oInput) {

                    if (oInput.checked) {

                        aKeys[iKey] = oInput.value

                    };

                    iKey++;

                });

                if (aKeys.length > 0) {

                    for (var i = 0; i < aKeys.length; i++) {

                        sPath = sPath + 'seller_level[]=' + aKeys[i] + '&';

                    }

                }

                var aInputs = Array();

                var aInputs = $('li').find('.get_seller_language');

                var aKeys = Array();

                var aValues = Array();

                iKey = 0;

                $.each(aInputs, function(key, oInput) {

                    if (oInput.checked) {

                        aKeys[iKey] = oInput.value

                    };

                    iKey++;

                });

                if (aKeys.length > 0) {

                    for (var i = 0; i < aKeys.length; i++) {

                        sPath = sPath + 'seller_language[]=' + aKeys[i] + '&';

                    }

                }

                $('#wait').addClass("loader");

                $.ajax({

                    url: url_plus + "../category_load",
                    method: "POST",
                    data: sPath + 'zAction=get_category_proposals',
                    success: function(data) {

                        $('#category_proposals').html('');

                        $('#category_proposals').html(data);

                        $('#wait').removeClass("loader");

                    }

                });

                $.ajax({

                    url: url_plus + "../category_load",
                    method: "POST",
                    data: sPath + 'zAction=get_category_pagination',
                    success: function(data) {

                        $('#category_pagination').html('');

                        $('#category_pagination').html(data);

                    }

                });

            }

            $('.get_instant_delivery').click(function() {
                get_category_proposals();
            });

            $('.get_order').click(function() {
                get_category_proposals();
            });

            $('.get_seller_country').click(function() {
                get_category_proposals();
            });

            $('.get_seller_city').click(function() {
                get_category_proposals();
            });

            $('.get_online_sellers').click(function() {
                get_category_proposals();
            });

            $('.get_delivery_time').click(function() {
                get_category_proposals();
            });

            $('.get_seller_level').click(function() {
                get_category_proposals();
            });

            $('.get_seller_language').click(function() {
                get_category_proposals();
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {

                $(".get_seller_country").click(function() {
                    if ($(".get_seller_country:checked").length > 0) {

                        $(".clear_seller_country").show();
                        $('.seller-cities li').addClass('d-none');

                        var aInputs = $('li').find('.get_seller_country');
                        var cities = Array();
                        iKey = 0;
                        $.each(aInputs, function(key, oInput) {
                            if (oInput.checked) {
                                var country = oInput.value
                                var city = $('.seller-cities li[data-country="' + country + '"]');
                                var city_name = city.find("label input").val();
                                city.removeClass('d-none');
                                if (city.length) {
                                    cities[iKey] = city_name;
                                    console.log(city_name);
                                }
                                iKey++;
                            };
                        });

                        if (cities.length > 0) {
                            $(".seller-cities").removeClass('d-none');
                        } else {
                            $(".seller-cities").addClass('d-none');
                        }

                    } else {
                        $(".seller-cities").addClass('d-none');
                        $(".clear_seller_country").hide();
                        clearCity();
                    }
                });

                $(".get_seller_city").click(function() {
                    if ($(".get_seller_city:checked").length > 0) {
                        $(".clear_seller_city").show();
                    } else {
                        $(".clear_seller_city").hide();
                    }
                });

                $(".get_cat_id").click(function() {
                    if ($(".get_cat_id:checked").length > 0) {
                        $(".clear_cat_id").show();
                    } else {
                        $(".clear_cat_id").hide();
                    }
                });
                $(".get_delivery_time").click(function() {
                    if ($(".get_delivery_time:checked").length > 0) {
                        $(".clear_delivery_time").show();
                    } else {
                        $(".clear_delivery_time").hide();
                    }
                });
                $(".get_seller_level").click(function() {
                    if ($(".get_seller_level:checked").length > 0) {
                        $(".clear_seller_level").show();
                    } else {
                        $(".clear_seller_level").hide();
                    }
                });
                $(".get_seller_language").click(function() {
                    if ($(".get_seller_language:checked").length > 0) {
                        $(".clear_seller_language").show();
                    } else {
                        $(".clear_seller_language").hide();
                    }
                });
                $(".clear_seller_country").click(function() {
                    $(".clear_seller_country").hide();
                });
                $(".clear_seller_city").click(function() {
                    $(".clear_seller_city").hide();
                });
                $(".clear_cat_id").click(function() {
                    $(".clear_cat_id").hide();
                });
                $(".clear_delivery_time").click(function() {
                    $(".clear_delivery_time").hide();
                });
                $(".clear_seller_level").click(function() {
                    $(".clear_seller_level").hide();
                });
                $(".clear_seller_language").click(function() {
                    $(".clear_seller_language").hide();
                });
            });

            function clearCountry() {
                $('.get_seller_country').prop('checked', false);
                $('.get_seller_city').prop('checked', false);
                $(".seller-cities").addClass('d-none');
                get_category_proposals();
            }

            function clearCity() {
                $('.get_seller_city').prop('checked', false);
                get_category_proposals();
            }

            function clearCat() {
                $('.get_cat_id').prop('checked', false);
                get_category_proposals();
            }

            function clearDelivery() {
                $('.get_delivery_time').prop('checked', false);
                get_category_proposals();
            }

            function clearLevel() {
                $('.get_seller_level').prop('checked', false);
                get_category_proposals();
            }

            function clearLanguage() {
                $('.get_seller_language').prop('checked', false);
                get_category_proposals();
            }
        </script>
    <?php } ?>
</body>

</html>
<?php
session_start();
require_once("includes/db.php");
require_once("social-config.php");



if (isset($_GET['id'])) {
    $portfolio_id = $_GET['id'];
    $portfolio = $db->select("portfolios", array("id" => $portfolio_id))->fetch();

    if ($portfolio) {
        $projectTitle = $portfolio->project_title;
        $description = $portfolio->description;
        $referenced_url = $portfolio->referenced_url;
        $seller_id = $portfolio->seller_id;

        // Fetch images
        $images = $db->select("portfolio_images", array("portfolio_id" => $portfolio_id));
        $image_paths = [];
        while ($image = $images->fetch()) {
            $image_paths[] = $image->image_path;
        }

        $portfolio_skills = $db->select("portfolio_skills", array("portfolio_id" => $portfolio_id));
        $portfolio_skill_detail = [];
        while ($portfolio_skill = $portfolio_skills->fetch()) {
            $portfolio_skill_detail[] = $portfolio_skill->skill;
        }

        $portfolio_videos = $db->select("portfolio_videos", array("portfolio_id" => $portfolio_id));
        $portfolio_video = [];
        while ($portfolio_videos_url = $portfolio_videos->fetch()) {
            $portfolio_video[] = $portfolio_videos_url->video_url;
        }

        $seller_info = $db->select("sellers", array("seller_id" => $seller_id));
        $seller_inform = $seller_info->fetch();
        $seller_user_name = $seller_inform->seller_user_name;
        $seller_name = $seller_inform->seller_name;
        $seller_city = $seller_inform->seller_city;
        $seller_country = $seller_inform->seller_country;
        $seller_image = $seller_inform->seller_image;
    } else {
        echo "Portfolio not found.";
        exit;
    }
} else {
    echo "No portfolio ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
    <title><?= $site_name; ?> - Portfolio </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $site_desc; ?>">
    <meta name="keywords" content="<?= $site_keywords; ?>">
    <meta name="author" content="<?= $site_author; ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
    <link href="styles/bootstrap.css" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet">
    <link href="styles/categories_nav_styles.css" rel="stylesheet">
    <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
    <link href="styles/owl.carousel.css" rel="stylesheet">
    <link href="styles/owl.theme.default.css" rel="stylesheet">
    <?php if (!empty($site_favicon)) { ?>
        <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
    <?php } ?>
    <link href="styles/sweat_alert.css" rel="stylesheet">
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="js/ie.js"></script>
    <script type="text/javascript" src="js/sweat_alert.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .width_alteration_for_btn {
            width: 150px;
            padding: 1rem;
            border-radius: 5px !important;
            margin: 3rem 0 1rem;
        }

        .top_descriptive_sect {

            padding: 1rem 3rem 0;
            /* background-color: #e5e5e5; */
        }

        .description_poert {
            font-size: 18px;

        }

        .seller_username_style {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .seller_other_locate {
            font-size: 18px;
        }

        /* CSS variables */
        .container_div {

            width: 100%;
            display: flex;
        }

        .container_div_inner {

            width: 90%;
            margin: auto;
        }

        a {
            text-decoration: none;
            color: var(--white-text-white);
        }

        .ran_links {
            display: flex;
            /* margin-bottom: 20px; */
        }

        .ramlink a,
        .ramlink1 a,
        .ramlink2 a {
            list-style: none;
            text-decoration: none;
        }

        .ramlink a {
            margin-left: 5px;
            color: black;
        }

        .ram_persion_img {
            width: 100%;
        }

        .ram_persion_img img {
            width: 600px;
            margin-bottom: 2rem;
            box-shadow: 0px 0px 30px lightgray;
            border-radius: 5px;
        }

        .ramlink1 a {
            color: black;
        }

        .ramlink2 a {
            color: rgb(39, 2, 252);
        }

        .ramlink2 a:hover {
            color: rgb(141, 123, 247);
            text-decoration: underline;
        }

        /* .rami {
            margin-left: 5px;
        } */

        .ramlinktext {
            margin-left: 5px;
        }

        .seeplogo {
            height: 80px;
            border-radius: 5px;
        }

        .ram_h1 {
            padding: 0px;
            margin: 0px;
            margin-bottom: 20px;
        }

        .logo_lefttext {
            display: flex;
        }

        .fa-linkedin:hover {
            color: white;
            background-color: #0A66C2;

        }

        .fa-twitter:hover {
            background-color: #1DA1F2;
            color: white;
        }

        .fa-facebook:hover {
            background-color: #1877F2;
            color: white;

        }

        .fa-instagram:hover {
            background: #f09433;
            background: -moz-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            background: -webkit-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f09433', endColorstr='#bc1888', GradientType=1);
            color: white;
        }

        .ramcity {
            margin-left: 10px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .ramleft_container,
        .ramright_container {
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        .ramleft_container {
            /* background-color: #333; */
        }



        .button {
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            background-color: aqua;
        }

        .rating_gnumber {
            background-color: orange;
            padding: 3px 0px 3px 5px;
            border-radius: 5px;
            color: white;

            text-align: center;
        }

        .ram_number {
            margin-left: 20px;
        }

        .skill_name_ram {
            display: flex;
            flex-wrap: wrap;
            /* ताकि टेक्स्ट नई लाइन में आ सके */
            gap: 10px;
            /* गेप सेट करें */
            width: 80%;
        }

        .skill_links {
            padding: 10px 20px;
            margin: 0;
            /* यहां पर गेप जोड़ें */
            border: 1px solid lightslategrey;
            font-weight: 600;
            border-radius: 5px;
        }


        .social_links {
            display: flex;
            flex-wrap: wrap;
            width: 70%;
        }

        .skill_links2 {

            padding: 10px 30px;
            margin: 0 10px;
            /* यहां पर गेप जोड़ें */
            border: 1px solid lightslategrey;
            font-weight: 600;
            border-radius: 5px;

        }

        /* Responsive styles */
        @media (min-width: 768px) {

            .ramleft_container,
            .ramright_container {
                width: 48%;
            }
        }

        @media (min-width: 1024px) {

            .ramleft_container,
            .ramright_container {
                width: 49%;
            }
        }

        .ram_linktext {
            margin-left: 20px;
        }

        .border_radius_add_iframe {
            box-shadow: 0px 0px 30px lightgray;
            border-radius: 5px;
        }
    </style>




<body class="is-responsive">
    <?php require_once("includes/header.php"); ?>

    <div class="container_div">
        <div class="container_div_inner">
            <div class="top_descriptive_sect">

                <div class="ran_links">
                    <p class="ramlink1"> <a href="#"><b><?= $site_name; ?> </b></a> <span class="rami"><i class="fa-thin fa-greater-than"></i></span></p>
                    <p class="ramlink"> <a href="#"> <b> <?= $seller_name; ?> </b></a> <span class="rami"><i class="fa-thin fa-greater-than"></i></span></p>
                    <p class="ramlinktext"><?= $projectTitle; ?></p>
                </div>

                <h1 class="ram_h1"><?= $projectTitle; ?></h1>
                <p class="ramlink2"><span class="ramitext">by <a href="#"><?= $seller_name; ?></a> </span></p>
            </div>

            <div class="container ram">

                <div class="ramleft_container">
                    <div class="logo_lefttext">
                        <div class="imglogo">
                            <img src="<?= $seller_image; ?>" alt="" class=" seeplogo">

                        </div>

                        <div class="ram_linktext">
                            <p class="linktext_big seller_username_style"> <a href="#"><?= $seller_name; ?> (<?= $seller_user_name; ?>)</a>
                            </p>

                            <p class="lintext d-flex seller_other_locate">
                                <span class="mr-0"><i class="fa fa-home"></i></span>
                                <span class="ramcity"><?= $seller_city; ?>, </span>
                                <span class="country ml-1"> <?= $seller_country; ?></span>
                            </p>
                        </div>

                    </div>
                    <hr>


                    <h2 class="ramabout mt-5">About project</h2>

                    <p class="lorem description_poert"><?= $description; ?>
                    </p>

                    <!-- <h2 class="ramh2 mt-5">$25 USD/hr</h2> -->


                    <?php
                    if (!isset($_SESSION['seller_user_name'])) { ?>
                        <a href="#" data-toggle="modal" data-target="#login-modal" class="btn btn-lg btn-block btn-success box-shadow-cheader width_alteration_for_btn">Hire me</a>
                    <?php } else { ?>
                        <a href="<?= $site_url ?>/conversations/message?seller_id=<?= $proposal_seller_id; ?>" class="btn btn-lg btn-block btn-success box-shadow-cheader width_alteration_for_btn">Hire me</a>
                    <?php } ?>

                    <div class="ram_linktext">



                        <!-- <p class="lintext">
                            <span class="rating_gnumber">4.4 </span>
                            <span class="ram_number">
                                6781 reviews</span>

                        </p> -->
                    </div>
                    <p class="hr">
                        <hr>
                    </p>

                    <h2 class="ramh2">Tags</h2>

                    <div class="skill_name_ram mb-5">
                        <?php foreach ($portfolio_skill_detail as $skill) : ?>
                            <p class="skill_links"><?= $skill ?></p>
                        <?php endforeach ?>
                    </div>





                    <?php
                    function getEmbedUrl($url)
                    {
                        if (strpos($url, 'youtube.com') !== false) {
                            // Convert YouTube URL to embed URL
                            preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches);
                            $video_id = $matches[1];
                            return 'https://www.youtube.com/embed/' . $video_id;
                        } elseif (strpos($url, 'vimeo.com') !== false) {
                            // Convert Vimeo URL to embed URL
                            preg_match('/vimeo.com\/([0-9]+)/', $url, $matches);
                            $video_id = $matches[1];
                            return 'https://player.vimeo.com/video/' . $video_id;
                        } else {
                            // Return the original URL if it's not recognized
                            return $url;
                        }
                    }
                    ?>

                    <?php foreach ($portfolio_video as $video_url) : ?>
                        <iframe src="<?= getEmbedUrl($video_url) ?>" width="560" height="315" frameborder="0" allowfullscreen class="border_radius_add_iframe"></iframe>
                    <?php endforeach ?>

                    <br><br>
                    <p><b>Project reference link :</b><a href="<?= $referenced_url; ?>" class="text-primary"> <?= $referenced_url; ?></a></p>

                </div>

                <div class="ramright_container" bg>
                    <?php foreach ($image_paths as $image_path) : ?>
                        <div class="ram_persion_img">

                            <img src='portfolio/<?= $image_path; ?>' alt='Portfolio Image'>

                        </div>
                    <?php endforeach; ?>
                    <!-- <p class="descram">This case-study is about how Lorem ipsum
                        dolor sit amet, consectetur adipiscing elit. Integer nec
                        odio. Praesent libero. Sed cursus ante dapibus diam.

                    </p> -->

                    <h4>Share this Portfolio</h4>

                    <div class="social_links">

                        <p class=""> <a href="#"><i class="fa-brands fa-instagram skill_links2"></i></a> </p>
                        <p class=""> <a href="#"><i class="fa-brands fa-facebook skill_links2"></i></a> </p>
                        <p class=""> <a href="#"><i class="fa-brands fa-twitter skill_links2"></i></a> </p>
                        <p class=""> <a href="#"><i class="fa-brands fa-linkedin skill_links2"></i></a> </p>


                    </div>

                </div>

            </div>
        </div>
    </div>
    <script>
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
    </script>



    <?php require_once("includes/footer.php"); ?>
</body>

</html>
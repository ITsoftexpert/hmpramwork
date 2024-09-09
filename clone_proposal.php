<?php

session_start();
require_once("includes/db.php");

?>


<script>
    function declineCloning() {
        var cloneProposalPopup = document.getElementById("cloneProposalPopup");
        cloneProposalPopup.style.display = "none";
    }
</script>

<?php
if (isset($_GET['proposal_id'])) {
    $proposal_id = $_GET['proposal_id'];

    // Fetch the proposal data from the database
    $get_proposal = $db->select("proposals", array("proposal_id" => $proposal_id));
    if ($get_proposal->rowCount() > 0) {
        $fetch_proposal = $get_proposal->fetch();
        $proposal_title = $fetch_proposal->proposal_title;
        $proposal_url = $fetch_proposal->proposal_url;
        $proposal_cat_id = $fetch_proposal->proposal_cat_id;
        $proposal_child_id = $fetch_proposal->proposal_child_id;
        $proposal_attr_id = $fetch_proposal->proposal_attr_id;
        $skill_title_id = $fetch_proposal->skill_title_id;
        $proposal_price = $fetch_proposal->proposal_price;
        $proposal_img1 = $fetch_proposal->proposal_img1;
        $proposal_img2 = $fetch_proposal->proposal_img2;
        $proposal_img3 = $fetch_proposal->proposal_img3;
        $proposal_img4 = $fetch_proposal->proposal_img4;
        $proposal_video = $fetch_proposal->proposal_video;
        $proposal_img1_s3 = $fetch_proposal->proposal_img1_s3;
        $proposal_img2_s3 = $fetch_proposal->proposal_img2_s3;
        $proposal_img3_s3 = $fetch_proposal->proposal_img3_s3;
        $proposal_img4_s3 = $fetch_proposal->proposal_img4_s3;
        $proposal_video_s3 = $fetch_proposal->proposal_video_s3;
        $proposal_yt_url = $fetch_proposal->proposal_yt_url;
        $proposal_desc = $fetch_proposal->proposal_desc;
        $buyer_instruction = $fetch_proposal->buyer_instruction;
        $proposal_tags = $fetch_proposal->proposal_tags;
        $proposal_enable_referrals = $fetch_proposal->proposal_enable_referrals;
        $proposal_referral_money = $fetch_proposal->proposal_referral_money;
        $proposal_referral_code = $fetch_proposal->proposal_referral_code;
        $proposal_featured = $fetch_proposal->proposal_featured;
        $proposal_toprated = $fetch_proposal->proposal_toprated;
        $delivery_id = $fetch_proposal->delivery_id;
        $proposal_revisions = $fetch_proposal->proposal_revisions;
        $level_id = $fetch_proposal->level_id;
        $language_id = $fetch_proposal->language_id;
        $proposal_rating = $fetch_proposal->proposal_rating;
        $proposal_views = $fetch_proposal->proposal_views;
        $proposal_status = "pending";
        $direct_order = $fetch_proposal->direct_order;
    } else {
        echo "Proposal not found.";
        exit;
    }
} else {
    echo "No proposal ID provided.";
    exit;
}
?>

<!-- popoup clone proposal -->








<!-- popoup clone proposal -->
<!-- form clone proposal -->
<?php
if (isset($_POST['clone_proposal_form'])) {
    $proposal_title = $_POST['proposal_title'];
    $proposal_url = $_POST['proposal_url'];
    $proposal_cat_id = $_POST['proposal_cat_id'];
    $proposal_child_id = $_POST['proposal_child_id'];
    $proposal_attr_id = $_POST['proposal_attr_id'];
    $skill_title_id = $_POST['skill_title_id'];
    $proposal_price = $_POST['proposal_price'];
    $proposal_img1 = $_POST['proposal_img1'];
    $proposal_img2 = $_POST['proposal_img2'];
    $proposal_img3 = $_POST['proposal_img3'];
    $proposal_img4 = $_POST['proposal_img4'];
    $proposal_video = $_POST['proposal_video'];
    $proposal_img1_s3 = $_POST['proposal_img1_s3'];
    $proposal_img2_s3 = $_POST['proposal_img2_s3'];
    $proposal_img3_s3 = $_POST['proposal_img3_s3'];
    $proposal_img4_s3 = $_POST['proposal_img4_s3'];
    $proposal_video_s3 = $_POST['proposal_video_s3'];
    $proposal_yt_url = $_POST['proposal_yt_url'];
    $proposal_desc = $_POST['proposal_desc'];
    $buyer_instruction = $_POST['buyer_instruction'];
    $proposal_tags = $_POST['proposal_tags'];
    $proposal_enable_referrals = $_POST['proposal_enable_referrals'];
    $proposal_referral_money = $_POST['proposal_referral_money'];
    $proposal_referral_code = $_POST['proposal_referral_code'];
    $proposal_featured = $_POST['proposal_featured'];
    $proposal_toprated = $_POST['proposal_toprated'];
    $delivery_id = $_POST['delivery_id'];
    $proposal_seller_id = $_POST['proposal_seller_id'];
    $proposal_revisions = $_POST['proposal_revisions'];
    $level_id = $_POST['level_id'];
    $language_id = $_POST['language_id'];
    $proposal_rating = $_POST['proposal_rating'];
    $proposal_views = $_POST['proposal_views'];
    $proposal_status = $_POST['proposal_status'];
    $direct_order = $_POST['direct_order'];
    $clone_status = "yes";

    // Assuming $db is your database connection object

    $inert_clone_proposal = $db->insert("proposals", [
        "proposal_title" => $proposal_title,
        "proposal_url" => $proposal_url,
        "proposal_cat_id" => $proposal_cat_id,
        "proposal_child_id" => $proposal_child_id,
        "proposal_attr_id" => $proposal_attr_id,
        "skill_title_id" => $skill_title_id,
        "proposal_price" => $proposal_price,
        "proposal_img1" => $proposal_img1,
        "proposal_img2" => $proposal_img2,
        "proposal_img3" => $proposal_img3,
        "proposal_img4" => $proposal_img4,
        "proposal_video" => $proposal_video,
        "proposal_img1_s3" => $proposal_img1_s3,
        "proposal_img2_s3" => $proposal_img2_s3,
        "proposal_img3_s3" => $proposal_img3_s3,
        "proposal_img4_s3" => $proposal_img4_s3,
        "proposal_video_s3" => $proposal_video_s3,
        "proposal_yt_url" => $proposal_yt_url,
        "proposal_desc" => $proposal_desc,
        "buyer_instruction" => $buyer_instruction,
        "proposal_tags" => $proposal_tags,
        "proposal_enable_referrals" => $proposal_enable_referrals,
        "proposal_referral_money" => $proposal_referral_money,
        "proposal_referral_code" => $proposal_referral_code,
        "proposal_featured" => $proposal_featured,
        "proposal_toprated" => $proposal_toprated,
        "delivery_id" => $delivery_id,
        "proposal_seller_id" => $login_seller_id,
        "proposal_revisions" => $proposal_revisions,
        "level_id" => $level_id,
        "language_id" => $language_id,
        "proposal_rating" => $proposal_rating,
        "proposal_views" => $proposal_views,
        "proposal_status" => $proposal_status,
        "direct_order" => $direct_order,
        "clone_status" => $clone_status
    ]);

    if ($inert_clone_proposal) { ?>
        <script>
            alert("Proposal cloned successfully.");
            window.location.href = "<?= $site_url . '/' . $seller_user_name; ?>";
        </script>
    <?php } else { ?>
        <script>
            alert("There was an error cloning the proposal. Please try again.");
        </script>

<?php }
}
?>




<!-- form clone proposal -->



<?php
session_start();
require_once("includes/db.php");
require_once("social-config.php");
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
    <title><?= $site_name; ?> - clone proposal</title>
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
    <style>
        .clone_confirm_proposal {

            background: white;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 400px;
            margin: auto;
            border-radius: 10px;
        }

        /* .bg-color-custom{
        background-color: #fff;
    } */
        .btn-clone-popoup {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .btn-clone-popoup button {
            padding: 10px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-clone-popoup .bg-success {
            background-color: green;
        }

        .btn-clone-popoup .bg-danger {
            background-color: red;
        }

        .clone_icon_propo {
            text-align: center;
            margin-bottom: 2rem;
            margin-top: 1rem;
        }

        .form_clone_propopsal_style {
            border: 1px solid grey;

        }

        .inform_clone_propopsal_style {
            border: 2px solid green;
            width: 80%;
            margin: auto;
        }

        .input_clone_propopsal_style {
            width: 45%;
            padding: 0.7rem;
        }

        .hidden_form_copy_form {
            display: none;
        }

        .font-size-19 {
            font-size: 19px;
        }
    </style>

</head>

<body class="is-responsive">
    <?php require_once("includes/header.php"); ?>
    <div class="container mt-5 mb-5 pb-1 site-theme-color">
        <div class="clone_confirm_proposal" id="cloneProposalPopup">
            <div class="clone_icon_propo"> <img src="<?= $site_url; ?>/images/copy_proposal_icon.png" class="m-auto" alt="clone icon"></div>
            <h3 class="text-center">Proposal Cloning Confirmation</h3>
            <p class="text-center mt-4 mb-4 font-size-19">Are you certain you want to duplicate this proposal?</p>

            <form method="post" enctype="multipart/form-data">
                <div class="hidden_form_copy_form">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_title" value="<?= $proposal_title; ?>-1" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_seller_id" value="<?= $login_seller_id; ?>" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_url" value="<?= $proposal_url; ?>-1" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_cat_id" value="<?= $proposal_cat_id; ?>" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_child_id" value="<?= $proposal_child_id; ?>" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_attr_id" value="<?= $proposal_attr_id; ?>" id=""><br>
                    <input class="input_clone_propopsal_style" type="hidden" name="skill_title_id" value="<?= $skill_title_id; ?>" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_price" value="<?= $proposal_price; ?>" id=""><br>
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_img1" value="<?= $proposal_img1; ?>" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_img2" value="<?= $proposal_img2; ?>" id=""><br>
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_img3" value="<?= $proposal_img3; ?>" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_img4" value="<?= $proposal_img4; ?>" id=""><br>
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_video" value="<?= $proposal_video; ?>" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_img1_s3" value="<?= $proposal_img1_s3; ?>" id=""><br>
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_img2_s3" value="<?= $proposal_img2_s3; ?>" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_img3_s3" value="<?= $proposal_img3_s3; ?>" id=""><br>
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_img4_s3" value="<?= $proposal_img4_s3; ?>" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_video_s3" value="<?= $proposal_video_s3; ?>" id=""><br>
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_yt_url" value="<?= $proposal_yt_url; ?>" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_desc" value="<?= $proposal_desc; ?>" id=""><br>
                    <input class="input_clone_propopsal_style" type="hidden" name="buyer_instruction" value="<?= $buyer_instruction; ?>" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_tags" value="<?= $proposal_tags; ?>" id=""><br>
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_enable_referrals" value="<?= $proposal_enable_referrals; ?>" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_referral_money" value="<?= $proposal_referral_money; ?>" id=""><br>
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_referral_code" value="<?= $proposal_referral_code; ?>" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_featured" value="<?= $proposal_featured; ?>" id=""><br>
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_toprated" value="<?= $proposal_toprated; ?>" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="delivery_id" value="0" id=""><br>
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_revisions" value="<?= $proposal_revisions; ?>" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="level_id" value="<?= $level_id; ?>" id=""><br>
                    <input class="input_clone_propopsal_style" type="hidden" name="language_id" value="<?= $language_id; ?>" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_rating" value="0" id=""><br>
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_views" value="0" id="">
                    <input class="input_clone_propopsal_style" type="hidden" name="proposal_status" value="<?= $proposal_status; ?>" id=""><br>
                    <input class="input_clone_propopsal_style" type="hidden" name="direct_order" value="<?= $direct_order; ?>" id=""><br>
                </div>
                <div class="btn-clone-popoup"> <button class="bg-success" type="submit" name="clone_proposal_form"><i class="fa fa-check"></i> Yes! i want to clone</button> </div>
            </form>
            <div class="btn-clone-popoup"><a href="<?= $site_url; ?>/<?= $seller_user_name; ?>"><button class="bg-danger"><i class="fa fa-close"></i> No! i don't want to</button> </a></div>

        </div>
    </div>
    <?php require_once("includes/footer.php"); ?>
</body>

</html>
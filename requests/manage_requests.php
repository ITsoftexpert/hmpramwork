<?php
session_start();
require_once("../includes/db.php");
if (!isset($_SESSION['seller_user_name'])) {
    echo "<script>window.open('../login','_self')</script>";
}
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
    <title><?= $site_name; ?> - <?= $lang["titles"]["manage_requests"]; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $site_desc; ?>">
    <meta name="keywords" content="<?= $site_keywords; ?>">
    <meta name="author" content="<?= $site_author; ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
    <link href="../styles/bootstrap.css" rel="stylesheet">
    <link href="../styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
    <link href="../styles/styles.css" rel="stylesheet">
    <link href="../styles/user_nav_styles.css" rel="stylesheet">
    <link href="../font_awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../styles/sweat_alert.css" rel="stylesheet">
    <link href="../styles/animate.css" rel="stylesheet">
    <script type="text/javascript" src="../js/sweat_alert.js"></script>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <?php if (!empty($site_favicon)) { ?>
        <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
    <?php } ?>
    <style>
        .padding-10 {
            padding: 9px 15px;
        }

        @media (max-width:641px)  and (max-width:767px) {
            .top-padding {
                /* border:1px solid green; */
                padding: 2px 0px 0px 1px;
                text-align: center;
            }

            .padding-alter4 {
                padding: 0px 15px;
            }


        }

        @media (max-width:640px){
            .width-increase-5 {
                width: 100%;
                height: 40px;
                /* box-shadow: 0px 0px 1px gray, inset 0px 0px 15px #d5f5ee; */
            }
            .padding-alter4 {
                padding: 0px 0px;
            }
            .top-padding {
                /* border:1px solid green; */
                padding: 2px 0px 0px 1px;
                /* text-align: center; */
                width: 100%;
                display: flex;
            }

            .text-align-center1 {
                text-align: center;
                margin: auto;
            }
        }

        @media(min-width:768px) {

            .badge-float-right {
                float: right;
                margin-top: -3px;
                padding-top: 5px;
                margin-right: -9px !important;
            }

            .padding-alter4 {
                padding: 0px 15px;
            }
        }
    </style>
</head>

<body class="is-responsive">
    <?php require_once("../includes/user_header.php"); ?>
    <div class="container-fluid px-5 py-5">
        <div class="row padding-alter4">
            <div class="col-md-12 mb-2 pb-3">
                <h1 class="pull-left top-padding mt-1"> <span class="text-align-center1"> <?= $lang["titles"]["manage_requests"]; ?> </span></h1>
                <a href="post_request" class="btn btn-success pull-right pt-2 mt-1 width-increase-5">
                    <i class="fa fa-plus-circle"></i> Post New Request
                </a>
            </div>
            <div class="col-md-12">
                <?php include('manage_requests_body.php'); ?>
            </div>
        </div>
    </div>
    <?php require_once("../includes/footer.php"); ?>
</body>

</html>
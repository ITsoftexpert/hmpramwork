<?php
error_reporting(0);
session_start();
if (isset($_SESSION['seller_user_name'])) {
    echo "<script>window.open('login','_self')</script>";
}
require_once("includes/db.php");
require_once("social-config.php");
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
 
<head>
    <title><?= $site_name; ?>- Featured Candidates </title>
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
    <link href="<?= $site_url; ?>/styles/update-style.css" rel="stylesheet">
    <link href="<?= $site_url; ?>/styles/featured-candidate-style.css" rel="stylesheet">
    <?php if (!empty($site_favicon)) { ?>
        <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
    <?php } ?>
    <link href="styles/sweat_alert.css" rel="stylesheet">
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="js/ie.js"></script>
    <script type="text/javascript" src="js/sweat_alert.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>

    <style>
        .div-for-styling {
            /* border: 2px solid green; */
            width: 80%;
            margin: auto;
            padding: 50px;
        }
    </style>
</head>

<body class="is-responsive">
    <?php require_once("includes/header.php"); ?>


    <div class="div-for-styling">

        <h1>Privacy Policy for Login with Facebook API</h1>

        <p>Your privacy is important to us. This Privacy Policy outlines how [Your Website or Application] collects, uses, and protects your personal information when you log in using Facebook API.</p>

        <h2>Information We Collect:</h2>
        <p>We collect personal information such as your name, email address, and Facebook profile information when you log in using Facebook API.</p>

        <h2>Use of Information:</h2>
        <p>We use your personal information to create and manage your user account, personalize your experience on our platform, and communicate with you about our services.</p>

        <!-- Add other sections as needed -->

        <h2>Changes to Privacy Policy:</h2>
        <p>We reserve the right to update this Privacy Policy at any time. We will notify you of any changes by posting the new Privacy Policy on this page.</p>

        <h2>Contact Us:</h2>
        <p>If you have any questions or concerns about our Privacy Policy or the use of Facebook API login, please contact us at [Your Contact Information].</p>

        <p>By logging in with Facebook API on [Your Website or Application], you consent to the collection and use of your personal information as described in this Privacy Policy.</p>

    </div>




</body>

</html>
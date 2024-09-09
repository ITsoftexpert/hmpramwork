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
        <h1>Data Deletion Instructions</h1>
        <p>We understand the importance of your privacy and want to provide you with the ability to manage your data stored within our application. If you wish to delete your data from our records, please follow the steps below:</p>

        <h4>Step 1: Access Your Account Settings</h4>
        <ol>
            <li>Log in to your account on [Your Application Name].</li>
            <li>Navigate to your account settings or profile settings page.</li>
        </ol>

        <h4>Step 2: Locate Data Deletion Option</h4>
        <ol>
            <li>Once in your account settings, look for the option to manage your data or privacy settings.</li>
            <li>You may find a section specifically dedicated to data deletion or removal.</li>
        </ol>

        <h4>Step 3: Initiate Data Deletion Process</h4>
        <ol>
            <li>Follow the prompts or instructions provided to initiate the data deletion process.</li>
            <li>You may be asked to confirm your decision or provide additional information for verification purposes.</li>
        </ol>

        <h4>Step 4: Confirmation</h4>
        <ol>
            <li>After initiating the data deletion process, you will receive confirmation that your request has been received.</li>
            <li>Please allow [Specify Timeframe, e.g., 7 days] for the deletion process to be completed.</li>
        </ol>

        <h4>Step 5: Verification</h4>
        <ol>
            <li>Once the data deletion process is completed, you will receive a final confirmation.</li>
            <li>You may also verify the deletion of your data by logging into your account and checking your profile or account settings.</li>
        </ol>

        <h4>Additional Assistance</h4>
        <p>If you encounter any difficulties or have questions about the data deletion process, please don't hesitate to contact our support team at [admin-info@hiremyprofile.com].</p>

        <p>Thank you for entrusting us with your data. We are committed to protecting your privacy and ensuring a transparent and straightforward data management process.</p>

    </div>
    <?php require_once("includes/footer.php"); ?>
</body>

</html>
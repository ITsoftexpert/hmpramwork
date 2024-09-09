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
        .term_and_condition_section{
            /* border: 2px solid gray; */
            padding: 5% 12%;
            font-size: 17px;
        }
    </style>
</head>

<body class="is-responsive">
    <?php require_once("includes/header.php"); ?>

    <div class="term_and_condition_section">
        <h1 class="text-center mb-3"> Terms and Conditions</h1>
        <p>Welcome to HireMyProfile.com! By accessing this website and using our services, you agree to comply
            with and be bound by the following terms and conditions of use. Please read these terms carefully
            before using our platform.
        </p>
        <ol>
            <li> 1. Acceptance of Terms: By using HireMyProfile.com, you agree to the terms and conditions
                outlined herein. If you do not agree with any of these terms, please do not use our website.
            </li>
            <li> 2. User Accounts: To access certain features of our website, you may be required to create
                an account. You are responsible for maintaining the confidentiality of your account information
                and for all activities that occur under your account.
            </li>
            <li> 3. Freelancer Profiles: Freelancers are solely responsible for the accuracy and completeness
                of the information provided in their profiles. HireMyProfile.com does not guarantee the
                authenticity or quality of services offered by freelancers.
            </li>
            <li> 4. Client Requirements: Clients must provide accurate and detailed descriptions of their
                project requirements. HireMyProfile.com is not responsible for any misunderstandings or
                disputes between clients and freelancers.
            </li>
            <li> 5. Project Payments: Clients agree to pay freelancers for completed projects through our
                platform. HireMyProfile.com may deduct a commission from the total payment as specified in
                our fee schedule.
            </li>
            <li> 6. Intellectual Property: All content and materials uploaded to HireMyProfile.com,
                including but not limited to profiles, project descriptions, and feedback, are the property
                of their respective owners. Users agree not to infringe upon the intellectual property rights
                of others.
            </li>
            <li> 7. Prohibited Activities: Users are prohibited from engaging in any unlawful, fraudulent,
                or abusive activities on our platform. This includes but is not limited to spamming,
                hacking, or impersonating others.
            </li>
            <li> 8. Termination of Accounts: HireMyProfile.com reserves the right to suspend or terminate
                user accounts that violate these terms and conditions or engage in prohibited activities.
            </li>
            <li> 9. Modification of Terms: We reserve the right to modify these terms and conditions at
                any time without prior notice. Your continued use of our website constitutes acceptance
                of these changes.
            </li>
            <li> 10. Governing Law: These terms and conditions shall be governed by and construed in
                accordance with the laws of [Your Country], without regard to its conflict of law provisions.
          (this is dummy content)
            </li>
        </ol>
    </div>


    <?php require_once("includes/footer.php"); ?>
</body>

</html>
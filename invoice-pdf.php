<?php
session_start();
require_once("includes/db.php");

// Check if the user is logged in
if (!isset($_SESSION['seller_user_name'])) {
    echo "<script>window.open('login','_self');</script>";
    exit();
}
// Fetch all orders (or any other data you want to list)

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
    <title> <?= $site_name; ?> - Invoice PDF </title>
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

    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>



</head>

<body class="is-responsive">
    <?php require_once("includes/header.php"); ?>

    <div class="container"> 
        <h2 class="text-center mt-4 mb-3"> <u>My Orders</u> </h2>
        <ul class="list-group">
            <?php
            $orders = $db->select("orders", array("buyer_id" => $seller_id));

            while ($row_order = $orders->fetch()) {
            ?>
                <li class="list-group-item my-2">
                    Order Number : #<?= $row_order->order_number; ?>
                    <span class="float-right">
                        <a href="download-invoice.php?order_id=<?= $row_order->order_id; ?>" class="btn btn-primary btn-sm">View Invoice Details</a>
                    </span>
                </li>
            <?php } ?>
        </ul>
    </div>



    <?php require_once("includes/footer.php"); ?>
</body>

</html>
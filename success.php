<?php
error_reporting(0);
session_start();
if (!isset($_SESSION['seller_user_name'])) {
    echo "<script>window.open('login','_self')</script>";
}
require_once("includes/db.php");
require_once("social-config.php");
?>


<?php

if (!empty($_GET)) {
    $_SESSION['package'] = $_GET['item_name'];
    $_SESSION['txn_id'] = $_GET['txn_id'];
    $_SESSION['amount'] = $_GET['amt'];
    $_SESSION['currency'] = $_GET['cc'];
    $_SESSION['payment_fee'] = $_GET['payment_fee'];
    $_SESSION['mc_currency'] = $_GET['mc_currency'];
    $_SESSION['status'] = $_GET['st'];
    $_SESSION['payer_id'] = $_GET['PayerID'];
    $_SESSION['payer_email'] = $_GET['payer_email'];
    $_SESSION['payer_name'] = $_GET['first_name'] . ' ' . $_GET['last_name'];
}



$select_sellers = $db->select("sellers", array("seller_user_name" => $_SESSION['seller_user_name']));
$row_sellers = $select_sellers->fetch();

$date = new DateTime();
$date = $date->format("Y-m-d H:i:s");
$end_date = date('Y-m-d H:i:s', strtotime($date . " + 30 day"));

// Assuming the status 'Completed' indicates a successful payment
if ($_SESSION['status'] == 'Completed') {
    $status = 'Active';
} else {
    $status = 'Pending'; // or another status based on the transaction status
}

// Insert into the `memb_plan_detail` table
$insert_plan_detail = $db->insert("memb_plan_detail", array(
    "seller_id" => $row_sellers->seller_id,
    "memb_tbl_id" => $_SESSION['c_proposal_id'],
    "memb_start_date" => $date,
    "memb_end_date" => $end_date,
    "memb_pyment_status" => $_SESSION['status'],
    "memb_status" => $status,
    "payment_method" => "PayPal",
    // "txn_id" => $_SESSION['txn_id'],
    // "amount" => $_SESSION['amount'],
    // "currency" => $_SESSION['currency'],
    // "payer_id" => $_SESSION['payer_id'],
    // "payer_email" => $_SESSION['payer_email'],
    // "payer_name" => $_SESSION['payer_name']
));

// Update seller information
$getPlan = $db->select("membership_table where id = " . $_SESSION['c_proposal_id'] . " LIMIT 1");
$objPlan = $getPlan->fetch();

$db->update("sellers", array(
    "no_of_gigs" => $objPlan->create_active_service,
    "bids_per_month" => $objPlan->bids_per_month,
    "skills" => $objPlan->skills,
    "comission_per_sale" => $objPlan->percentage_per_project,
    "create_porfolio" => $objPlan->create_portfolio,
    "project_bookmarks" => $objPlan->project_bookmark,
), array(
    "seller_id" => $row_sellers->seller_id,
));


if ($insert_plan_detail) {
    // echo '<script>window.location.href="index";</script>';
    // header('location: index');
} else {
    echo '<h1>Something went wrong! Please contact admin.</h1>';
}
?>


<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
    <title><?= $site_name; ?> - Paypal Success Payments </title>
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
    <style>
        .card_container_box {
            margin: 2rem auto;
            width: 100%;
            display: flex;
        }

        .card_design_style {
            display: block;
            margin: auto;
            padding: 2rem;
            border-radius: 10px;
            width: 40%;
            box-shadow: 2px 2px 1px lightgrey, inset 0px 0px 10px lightgray;
        }

        .thirty_per_cent {
            width: 30%;
        }

        .table_body_style tr td {
            text-align: end;
        }
    </style>

    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="js/ie.js"></script>
    <script type="text/javascript" src="js/sweat_alert.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <!-- Include jsPDF library from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>

<body class="is-responsive">
    <?php require_once("includes/header.php"); ?>

    <div class="alert alert-success">
        <strong>Success!</strong> Payment has been successful.
    </div>

    <div class="card_container_box">
        <div class="card_design_style">
            <h1 class="text-center mb-4"><u>Payment Status</u></h1>
            <table class="w-100" id="payment-details">
                <tbody class="w-100 table_body_style">
                    <tr class="w-100 p-3">
                        <th class="thirty_per_cent py-2">Payer</th>
                        <td><?= $_SESSION['payer_name']; ?></td>
                    </tr>
                    <tr>
                        <th class="py-2">Plan</th>
                        <td><?= $_SESSION['package']; ?></td>
                    </tr>
                    <tr>
                        <th class="py-2">Transaction id</th>
                        <td><?= $_SESSION['txn_id']; ?></td>
                    </tr>
                    <tr>
                        <th class="py-2">Payer ID</th>
                        <td><?= $_SESSION['payer_id']; ?></td>
                    </tr>
                    <tr>
                        <th class="py-2">Payer Email</th>
                        <td><?= $_SESSION['payer_email']; ?></td>
                    </tr>
                    <tr>
                        <th class="py-2">Txn ID</th>
                        <td><?= $_SESSION['txn_id']; ?></td>
                    </tr>
                    <tr>
                        <th class="py-2">Payment Currency</th>
                        <td><?= $_SESSION['mc_currency']; ?></td>
                    </tr>
                    <tr>
                        <th class="py-2">Payment Fee</th>
                        <td><?= $_SESSION['payment_fee']; ?></td>
                    </tr>
                    <tr>
                        <th class="py-2">Paid Amount</th>
                        <td><?= $_SESSION['amount']; ?></td>
                    </tr>
                    <tr>
                        <th class="py-2">Status</th>
                        <td><?= $_SESSION['status']; ?></td>
                    </tr>
                </tbody>
            </table>
            <!-- Add Download Button -->
            <button id="download-pdf" class="btn btn-primary mt-3">Download Payment Details</button>
            <a href="<?= $site_url; ?>/membership_subs">
                <button class="btn btn-warning mt-3">Go To Back</button>
            </a>
        </div>
    </div>

    <script>
        // Function to download the payment details as PDF
        document.getElementById('download-pdf').addEventListener('click', function() {
            // Import jsPDF
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            // Create the content for the PDF
            let content = '';
            content += 'Payer: <?= $_SESSION['payer_name']; ?>\n';
            content += 'Plan: <?= $_SESSION['package']; ?>\n';
            content += 'Transaction ID: <?= $_SESSION['txn_id']; ?>\n';
            content += 'Payer ID: <?= $_SESSION['payer_id']; ?>\n';
            content += 'Payer Email: <?= $_SESSION['payer_email']; ?>\n';
            content += 'Payment Currency: <?= $_SESSION['mc_currency']; ?>\n';
            content += 'Payment Fee: <?= $_SESSION['payment_fee']; ?>\n';
            content += 'Paid Amount: <?= $_SESSION['amount']; ?>\n';
            content += 'Status: <?= $_SESSION['status']; ?>\n';

            // Add the content to the PDF
            doc.text(content, 10, 10);

            // Download the PDF
            doc.save('payment-details.pdf');
        });
    </script>

    <?php
    unset($_SESSION['txn_id']);
    require_once("includes/footer.php"); ?>
</body>

</html>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include necessary files (DB connection, session, etc.)
require_once("includes/db.php");
// if (!isset($_SESSION['seller_user_name'])) {
//     echo "<script>window.open('login','_self')</script>";
// }
// Get data from PayPal return URL


$buyer_id = $_GET['buyer_id'];
$item_number = $_GET['item_number'];
$item_name = $_GET['item_name'];
$receiver_id = $_GET['receiver_id'];
$payment_date = $_GET['payment_date'];
$order_number = $_GET['order_number'];
$milestone_id = $_GET['milestone_id'];
$txn_id = $_GET['tx']; // PayPal Transaction ID
$payment_status = $_GET['st']; // Payment Status (e.g., "Completed")
// Insert data into the database only if the payment was successful 


$dateortime = new DateTime($payment_date);
$formattedDate = $dateortime->format("y-m-d");
$formattedTime = $dateortime->format("h-m-s");

if ($payment_status == "Completed") {

    $get_milestone_data = $db->select("milestone", array("milestone_id" => $milestone_id));
    $fetchCurrentMilestone = $get_milestone_data->fetch();
    $task_amount = $fetchCurrentMilestone->task_amount;
    $request_id = $fetchCurrentMilestone->request_id;
    $sender_id = $fetchCurrentMilestone->sender_id;
    $seller_id = $fetchCurrentMilestone->seller_id;
    $proposal_id = $fetchCurrentMilestone->proposal_id;
    $offer_id = $fetchCurrentMilestone->offer_id;
    $delivery_time = $fetchCurrentMilestone->delivery_time;

    // Check if the order already exists in the database
    $order_exists = $db->select("orders", array("order_number" => $order_number))->rowCount();
    if ($order_exists == 0) {
        // Insert the order into the orders table        
        $db->insert("orders", array(
            "order_number" => $order_number,
            "seller_id" => $sender_id,
            "buyer_id" => $seller_id,
            "order_price" => $task_amount,
            "order_duration" => $delivery_time,
            "proposal_id" => $proposal_id,
            "milestone_id" => $milestone_id,
            "milestone_enable" => "yes",
            "order_qty" => 1,
            "order_status" => "pending"
        ));
    }

    $update_milestone_status = $db->update("milestone", array('milestone_status' => 'paid', 'order_number' => $order_number), array("milestone_id" => $milestone_id));

    // Fetch the inserted order details
    $order_details = $db->select("orders", array("order_number" => $order_number))->fetch();
    $order_id = $order_details->order_id;
    $seller_id = $order_details->seller_id;
    $order_price = $order_details->order_price;
    $reason = "order";
    $method = "paypal";
    $date = $formattedDate;

    $insert_purchase = $db->insert("purchases", array(
        "order_id" => $order_id,
        "seller_id" => $seller_id,
        "amount" => $order_price,
        "reason" => $reason,
        "method" => $method,
        "date" => $date
    ));
}
?>




<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
    <title><?= $site_name; ?> - Order Payment Details </title>
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
        .payment_result_display_div {
            /* border: 1px solid grey; */
            width: 100%;
            display: flex;
        }

        .payment_result_display_div_inner {
            width: 40%;
            margin: 2rem auto 4rem;
            padding: 1rem 2rem 2rem;
            /* border: 1px solid grey; */
            box-shadow: 0px 0px 10px lightgray;
            border-radius: 10px;
        }

        .payment_result_display_table {
            width: 100%;
        }

        .payment_result_display_table th,
        td {
            /* border: 1px solid lightgrey; */
            padding: 10px;
        }

        .payment_result_display_table td {
            text-align: end;
        }

        .btn_style_order_deatails_rediect {
            border: none;
            background-color: #00cedc;
            color: white;
            padding: 10px;
            border-radius: 3px;
        }
    </style>

</head>

<body class="is-responsive">
    <?php require_once("includes/header.php"); ?>
    <?php if ($payment_status == "Completed") { ?>

        <div class="payment_result_display_div">
            <div class="payment_result_display_div_inner">

                <h3 class="text-center p-3">Payment Status</h3>
                <div class="alert alert-success">
                    <strong>Success!</strong> Payment has been successful.
                </div>
                <?php
                if (!empty($order_details)) { ?>
                    <table class="payment_result_display_table">
                        <tbody>
                            <tr>
                                <th>Order Number: </th>
                                <td><?= $order_details->order_number; ?></td>
                            </tr>
                            <tr>
                                <th>Order Item: </th>
                                <td><?= $item_name; ?></td>
                            </tr>
                            <!-- <tr>
                                <th>Item Number: </th>
                                <td><?= $item_number; ?></td>
                            </tr>                      -->
                            <tr>
                                <th>Order Amount: </th>
                                <td> $<?= $order_details->order_price; ?></td>
                            </tr>
                            <tr>
                                <th>Paid Amount: </th>
                                <td> $<?= $order_details->order_price; ?></td>
                            </tr>
                            <tr>
                                <th>Order Status: </th>
                                <td> <?= $order_details->order_status; ?></td>
                            </tr>

                            <tr>
                                <th>Payment Status: </th>
                                <td><?= $payment_status; ?></td>
                            </tr>
                            <tr>
                                <th>Payment Date & Time: </th>
                                <td><?= $formattedDate; ?> / <?= $formattedTime; ?></td>
                            </tr>
                            <tr>
                                <th>Receiver ID: </th>
                                <td><?= $receiver_id; ?></td>
                            </tr>
                            <tr>
                                <th>Transaction ID: </th>
                                <td><?= $txn_id; ?></td>
                            </tr>


                        </tbody>
                    </table>
                    <div class="text-center mt-3">
                        <a href="<?= $site_url; ?>/order_details?order_id=<?= $order_id; ?>"><button class="btn_style_order_deatails_rediect"> Go To Order Details </button></a>
                    </div>
                <?php } else { ?>
                    <p>No data found.</p>


                <?php }
            } else { ?>
                <div class="alert alert-danger mt-5">
                    <p>Payment failed or was not completed.</p>
                </div>
            <?php } ?>
            </div>
        </div>

        <?php require_once("includes/footer.php"); ?>
</body>

</html>
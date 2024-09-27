<?php

session_start();
require_once("includes/db.php");
require_once("functions/processing_fee.php");
if (!isset($_SESSION['seller_user_name']) && !isset($_GET['pid'])) {
    header("location: login");
}


$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$enable_paypal = $row_payment_settings->enable_paypal;
$enable_paypal = $row_payment_settings->enable_paypal;
$paypal_client_id = $row_payment_settings->paypal_app_client_id;
$paypal_email = $row_payment_settings->paypal_email;
$paypal_currency_code = $row_payment_settings->paypal_currency_code;
$paypal_sandbox = $row_payment_settings->paypal_sandbox;
$enable_dusupay = $row_payment_settings->enable_dusupay;
$dusupay_method = $row_payment_settings->dusupay_method;
$dusupay_provider_id = $row_payment_settings->dusupay_provider_id;

if ($paypal_sandbox == "on") {
    $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} elseif ($paypal_sandbox == "off") {
    $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}

$enable_stripe = $row_payment_settings->enable_stripe;
$enable_tazapay = $row_payment_settings->tazapay;
$enable_payza = $row_payment_settings->enable_payza;
$payza_test = $row_payment_settings->payza_test;
$payza_currency_code = $row_payment_settings->payza_currency_code;
$payza_email = $row_payment_settings->payza_email;

$enable_coinpayments = $row_payment_settings->enable_coinpayments;
$coinpayments_merchant_id = $row_payment_settings->coinpayments_merchant_id;
$coinpayments_currency_code = $row_payment_settings->coinpayments_currency_code;

$enable_mercadopago = $row_payment_settings->enable_mercadopago;

if ($paymentGateway == 1) {

    $get_plugin = $db->query("select * from plugins where folder='paymentGateway'");
    $row_plugin = $get_plugin->fetch();
    $paymentGatewayVersion = $row_plugin->version;
    if ($paymentGatewayVersion >= 1.2) {
        $enable_2checkout = $row_payment_settings->enable_2checkout;
    } else {
        $enable_2checkout = "no";
    }
} else {
    $enable_2checkout = "no";
}

$enable_paystack = $row_payment_settings->enable_paystack;

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_email = $row_login_seller->seller_email;

if (isset($_GET['pid'])) {

    $_SESSION['c_proposal_id'] = $input->get('pid');
    $_SESSION['c_proposal_qty'] = 1;

    if (isset($_SESSION['c_proposal_id'])) {

        $proposal_id = $_SESSION['c_proposal_id'];
        $proposal_qty = $_SESSION['c_proposal_qty'];

        $plan_id = $input->get('pid');
        $plan_detail = $db->select("membership_table", array("id" => $plan_id));
        $row_plan = $plan_detail->fetch();
        $proposal_title = $row_plan->plan_name;
        $proposal_plan_id = $row_plan->id;
        $sub_total = $row_plan->plan_price_month;

?>
        <!DOCTYPE html>

        <html lang="en" class="ui-toolkit">

        <head>
            <title><?= $site_name; ?> - Checkout</title>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="author" content="<?= $site_author; ?>">
            <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
            <link href="styles/bootstrap.css" rel="stylesheet">
            <link href="styles/styles.css" rel="stylesheet">
            <link href="styles/categories_nav_styles.css" rel="stylesheet">
            <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
            <link href="styles/owl.carousel.css" rel="stylesheet">
            <link href="styles/owl.theme.default.css" rel="stylesheet">
            <link href="styles/sweat_alert.css" rel="stylesheet">
            <script type="text/javascript" src="js/sweat_alert.js"></script>
            <script type="text/javascript" src="js/jquery.min.js"></script>
            <script src="https://checkout.stripe.com/checkout.js"></script>

            <!-- Include the PayPal JavaScript SDK -->
            <script src="https://www.paypal.com/sdk/js?client-id=<?= $paypal_client_id; ?>&commit=true&disable-funding=credit,card&currency=<?= $paypal_currency_code; ?>"></script>

            <?php if (!empty($site_favicon)) { ?>
                <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
            <?php } ?>
        </head>

        <body class="is-responsive">
            <?php
            require_once("includes/header.php");

            if ($seller_verification != "ok") {
                echo "
                <div class='alert alert-danger rounded-0 mt-0 text-center'>
                Please confirm your email to use this feature.
                </div>";
            } else {

                $site_logo_image = getImageUrl2("general_settings", "site_logo", $row_general_settings->site_logo);

                $coupon_usage = "no";
                $coupon_type = "";


            ?>
                <div class="container mt-5 mb-5">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="row">
                                <?php if ($current_balance >= $sub_total) { ?>
                                    <div class="col-md-12 mb-3">
                                        <div class="card payment-options">
                                            <div class="card-header">
                                                <h5><i class="fa fa-dollar"></i> Available Shopping Balance</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-1">
                                                        <input id="shopping-balance" type="radio" name="method" class="form-control radio-input" checked>
                                                    </div>
                                                    <div class="col-11">
                                                        <p class="lead mt-2">
                                                            Personal Balance - <?= $login_seller_user_name; ?>
                                                            <span class="text-success font-weight-bold"><?= showPrice($current_balance); ?></span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php
                                $exist_sellers = $db->query("SELECT * FROM proposals WHERE proposal_seller_id = '$login_seller_id'");
                                while ($exist_proposals = $exist_sellers->fetch()) {
                                    $proposal_seller_id = $exist_proposals->proposal_seller_id;
                                }

                                if (empty($proposal_seller_id)) { ?>
                                    <div id="demo">
                                        <div style="padding:0px 10px;">
                                            <table>
                                                <tr class='table-danger'>
                                                    <td colspan='5'>
                                                        <center class="box-shadow-bg-clr">
                                                            <h3 class='pb-4 pt-4 pl-5 pr-5'>
                                                                Please create a proposal in order to find relevant job. <a href='<?= $site_url; ?> /proposals/create_proposal' class='text-info'>Click here</a> to create proposal
                                                            </h3>
                                                        </center>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                <?php } ?>



                                <div class="col-md-12 mb-3">
                                    <div class="card payment-options">
                                        <div class="card-header">
                                            <h5><i class="fa fa-credit-card"></i> Payment Options</h5>
                                        </div>
                                        <div class="card-body">
                                            <?php if ($enable_paypal == "yes") { ?>
                                                <div class="row">
                                                    <div class="col-1">
                                                        <input id="paypal" type="radio" name="method" class="form-control radio-input" <?php
                                                                                                                                        if ($current_balance < $sub_total) {
                                                                                                                                            echo "checked";
                                                                                                                                        }
                                                                                                                                        ?>>
                                                    </div>
                                                    <div class="col-11">
                                                        <img src="images/paypal.png" height="50" class="ml-2 width-xs-100">
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($enable_stripe == "yes") { ?>
                                                <?php if ($enable_paypal == "yes") { ?>
                                                    <hr>
                                                <?php } ?>
                                                <div class="row">
                                                    <div class="col-1">
                                                        <input id="credit-card" type="radio" name="method" class="form-control radio-input" <?php
                                                                                                                                            if ($current_balance < $sub_total) {
                                                                                                                                                if ($enable_paypal == "no") {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            }
                                                                                                                                            ?>>
                                                    </div>
                                                    <div class="col-11">
                                                        <img src="images/credit_cards.jpg" height="50" class="ml-2 width-xs-100">
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if ($enable_tazapay == "yes") { ?>
                                                <?php if ($enable_stripe == "yes") { ?>
                                                    <hr>
                                                <?php } ?>
                                                <div class="row">
                                                    <div class="col-1">
                                                        <input id="taza-pay" type="radio" name="method" class="form-control radio-input" <?php
                                                                                                                                            if ($current_balance < $sub_total) {
                                                                                                                                                if ($enable_paypal == "no") {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            }
                                                                                                                                            ?>>
                                                    </div>
                                                    <div class="col-11">
                                                        <img src="images/tazapay.svg" height="50" class="ml-2 width-xs-100">
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php
                                            if ($enable_2checkout == "yes") {
                                                require_once("plugins/paymentGateway/paymentMethod1.php");
                                            }
                                            ?>

                                            <?php if ($enable_mercadopago == "1") { ?>
                                                <?php if ($enable_paypal == "yes" or $enable_stripe == "yes" or $enable_2checkout == "yes") { ?>
                                                    <hr>
                                                <?php } ?>
                                                <div class="row">
                                                    <div class="col-1">
                                                        <input id="mercadopago" type="radio" name="method" class="form-control radio-input" <?php
                                                                                                                                            if ($current_balance < $sub_total) {
                                                                                                                                                if ($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "1") {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            }
                                                                                                                                            ?>>
                                                    </div>
                                                    <div class="col-11">
                                                        <img src="images/mercadopago.png" height="50" class="ml-2 width-xs-100">
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <?php if ($enable_coinpayments == "yes") { ?>
                                                <?php if ($enable_paypal == "yes" or $enable_stripe == "yes" or $enable_2checkout == "yes" or $enable_mercadopago == "1") { ?>
                                                    <hr>
                                                <?php } ?>
                                                <div class="row">
                                                    <div class="col-1">
                                                        <input id="coinpayments" type="radio" name="method" class="form-control radio-input" <?php
                                                                                                                                                if ($current_balance < $sub_total) {
                                                                                                                                                    if ($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "0") {
                                                                                                                                                        echo "checked";
                                                                                                                                                    }
                                                                                                                                                }
                                                                                                                                                ?>>
                                                    </div>
                                                    <div class="col-11">
                                                        <img src="images/coinpayments.png" height="50" class="ml-2 width-xs-100">
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <?php if ($enable_paystack == "yes") { ?>
                                                <?php if ($enable_paypal == "yes" or $enable_stripe == "yes" or $enable_2checkout == "yes" or $enable_mercadopago == "1" or $enable_coinpayments == "yes") { ?>
                                                    <hr>
                                                <?php } ?>
                                                <div class="row">
                                                    <div class="col-1">
                                                        <input id="paystack" type="radio" name="method" class="form-control radio-input" <?php
                                                                                                                                            if ($current_balance < $sub_total) {
                                                                                                                                                if ($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "0" and $enable_coinpayments == "no") {
                                                                                                                                                    echo "checked";
                                                                                                                                                }
                                                                                                                                            }
                                                                                                                                            ?>>
                                                    </div>
                                                    <div class="col-11">
                                                        <img src="images/paystack.png" height="50" class="ml-2 width-xs-100">
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <?php if ($enable_dusupay == "yes") { ?>
                                                <?php if ($enable_paypal == "yes" or $enable_stripe == "yes" or $enable_2checkout == "yes" or $enable_mercadopago == "1" or $enable_paystack == "yes" or $enable_coinpayments == "yes") { ?>
                                                    <hr>
                                                <?php } ?>
                                                <div class="row">
                                                    <div class="col-1">
                                                        <input id="mobile-money" type="radio" name="method" class="form-control radio-input" <?php
                                                                                                                                                if ($current_balance < $sub_total) {
                                                                                                                                                    if ($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "0" and $enable_coinpayments == "no" and $enable_paystack == "no") {
                                                                                                                                                        echo "checked";
                                                                                                                                                    }
                                                                                                                                                }
                                                                                                                                                ?>>
                                                    </div>
                                                    <div class="col-11">
                                                        <img src="images/dusupay.png" height="50" class="ml-2 width-xs-100">
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!--        order summary-->
                        <div class="col-md-5">
                            <div class="card checkout-details">
                                <div class="card-header">
                                    <h5><i class="fa fa-file-text-o"></i> Order Summary </h5>
                                </div>
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <!--                                        <img src="--><? //= $proposal_img1;
                                                                                                        ?>
                                            <!--" class="img-fluid">-->
                                        </div>
                                        <div class="col-md-8">
                                            <h5><?= $proposal_title; ?></h5>
                                        </div>
                                    </div>

                                    <hr>

                                    <h6>Membership Plan Price: <span class="float-right"><?= showPrice($sub_total); ?> </span>
                                    </h6>

                                    <h6>Proposal's Quantity: <span class="float-right"><?= $proposal_qty; ?></span></h6>

                                    <h5 class="font-weight-bold">
                                        Proposal's Total: <span class="float-right total-price"><?= showPrice($sub_total); ?></span>
                                    </h5>
                                    <hr>
                                    <?php include("checkoutPayMethodstazapay.php"); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <script>
                    $(document).ready(function() {
                        <?php if ($current_balance >= $sub_total) { ?>
                            $('.total-price').html('<?= showPrice($sub_total); ?>');
                            $('.processing-fee').hide();
                        <?php } else { ?>
                            $('.total-price').html('<?= showPrice($total); ?>');
                            $('.processing-fee').show();
                        <?php } ?>
                        <?php if ($current_balance >= $sub_total) { ?>
                            $('#mercadopago-form').hide();
                            $('#mobile-money-form').hide();
                            $('#coinpayments-form').hide();
                            $('#paypal-form').hide();
                            $('#tazpay-form').hide();
                            $('#paystack-form').hide();
                            $('#credit-card-form').hide();
                            $('#2checkout-form').hide();
                        <?php } else { ?>
                            $('#shopping-balance-form').hide();
                        <?php } ?>
                        <?php if ($current_balance < $sub_total) { ?>
                            <?php if ($enable_paypal == "yes") { ?>
                            <?php } else { ?>
                                $('#paypal-form').hide();
                            <?php } ?>
                        <?php } ?>

                        <?php if ($current_balance < $sub_total) { ?>
                            <?php if ($enable_paypal == "yes") { ?>
                                $('#credit-card-form').hide();
                                $('#2checkout-form').hide();
                                $('#tazpay-form').hide();
                                $('#mobile-money-form').hide();
                                $('#mercadopago-form').hide();
                                $('#coinpayments-form').hide();
                                $('#paystack-form').hide();
                            <?php } elseif ($enable_paypal == "no" and $enable_stripe == "yes") { ?>
                                $('#2checkout-form').hide();
                                $('#coinpayments-form').hide();
                                $('#mercadopago-form').hide();
                                $('#mobile-money-form').hide();
                                $('#paystack-form').hide();
                            <?php } elseif ($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "yes") { ?>
                                $('#coinpayments-form').hide();
                                $('#mercadopago-form').hide();
                                $('#mobile-money-form').hide();
                                $('#paystack-form').hide();
                            <?php } elseif ($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "1") { ?>
                                $('#coinpayments-form').hide();
                                $('#mobile-money-form').hide();
                                $('#paystack-form').hide();
                            <?php } elseif ($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "0" and $enable_coinpayments == "yes") { ?>
                                $('#mobile-money-form').hide();
                                $('#paystack-form').hide();
                            <?php } elseif ($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "0" and $enable_coinpayments == "no" and $enable_paystack == "yes") { ?>
                                $('#mobile-money-form').hide();
                            <?php } ?>
                        <?php } ?>

                        $('#shopping-balance').click(function() {
                            $('.total-price').html('<?= showPrice($sub_total); ?>');
                            $('.processing-fee').hide();
                            $('#mobile-money-form').hide();
                            $('#credit-card-form').hide();
                            $('#2checkout-form').hide();
                            $('#coinpayments-form').hide();
                            $('#paystack-form').hide();
                            $('#tazpay-form').hide();
                            $('#mercadopago-form').hide();
                            $('#paypal-form').hide();
                            $('#shopping-balance-form').show();
                        });
                        $('#paypal').click(function() {
                            $('.total-price').html('<?= showPrice($total); ?>');
                            $('.processing-fee').show();
                            $('#mobile-money-form').hide();
                            $('#credit-card-form').hide();
                            $('#2checkout-form').hide();
                            $('#paypal-form').show();
                            $('#shopping-balance-form').hide();
                            $('#coinpayments-form').hide();
                            $('#paystack-form').hide();
                            $('#mercadopago-form').hide();
                            $('#tazpay-form').hide();
                        });

                        $('#taza-pay').click(function() {
                            $('.total-price').html('<?= showPrice($total); ?>');
                            $('.processing-fee').show();
                            $('#mobile-money-form').hide();
                            $('#credit-card-form').hide();
                            $('#2checkout-form').hide();
                            $('#paypal-form').hide();
                            $('#tazpay-form').show();
                            $('#shopping-balance-form').hide();
                            $('#coinpayments-form').hide();
                            $('#paystack-form').hide();
                            $('#mercadopago-form').hide();
                        });





                        $('#credit-card').click(function() {
                            $('.total-price').html('<?= showPrice($total); ?>');
                            $('.processing-fee').show();
                            $('#mobile-money-form').hide();
                            $('#credit-card-form').show();
                            $('#2checkout-form').hide();
                            $('#paypal-form').hide();
                            $('#shopping-balance-form').hide();
                            $('#coinpayments-form').hide();
                            $('#paystack-form').hide();
                            $('#mercadopago-form').hide();
                            $('#tazpay-form').hide();
                        });
                        $('#2checkout').click(function() {
                            $('.total-price').html('<?= showPrice($total); ?>');
                            $('.processing-fee').show();
                            $('#mobile-money-form').hide();
                            $('#credit-card-form').hide();
                            $('#2checkout-form').show();
                            $('#paypal-form').hide();
                            $('#shopping-balance-form').hide();
                            $('#coinpayments-form').hide();
                            $('#paystack-form').hide();
                            $('#mercadopago-form').hide();
                            $('#tazpay-form').hide();
                        });
                        $('#mobile-money').click(function() {
                            $('.total-price').html('<?= showPrice($total); ?>');
                            $('.processing-fee').show();
                            $('#mobile-money-form').show();
                            $('#credit-card-form').hide();
                            $('#2checkout-form').hide();
                            $('#paypal-form').hide();
                            $('#shopping-balance-form').hide();
                            $('#coinpayments-form').hide();
                            $('#paystack-form').hide();
                            $('#mercadopago-form').hide();
                            $('#tazpay-form').hide();
                        });
                        $('#coinpayments').click(function() {
                            $('.total-price').html('<?= showPrice($total); ?>');
                            $('.processing-fee').show();
                            $('#2checkout-form').hide();
                            $('#mercadopago-form').hide();
                            $('#mobile-money-form').hide();
                            $('#credit-card-form').hide();
                            $('#coinpayments-form').show();
                            $('#paystack-form').hide();
                            $('#paypal-form').hide();
                            $('#shopping-balance-form').hide();
                            $('#tazpay-form').hide();
                        });
                        $('#paystack').click(function() {
                            $('.total-price').html('<?= showPrice($total); ?>');
                            $('.processing-fee').show();
                            $('#mercadopago-form').hide();
                            $('#mobile-money-form').hide();
                            $('#credit-card-form').hide();
                            $('#2checkout-form').hide();
                            $('#coinpayments-form').hide();
                            $('#paystack-form').show();
                            $('#paypal-form').hide();
                            $('#shopping-balance-form').hide();
                            $('#tazpay-form').hide();
                        });
                        $('#mercadopago').click(function() {
                            $('.total-price').html('<?= showPrice($total); ?>');
                            $('.processing-fee').show();
                            $('#mercadopago-form').show();
                            $('#mobile-money-form').hide();
                            $('#credit-card-form').hide();
                            $('#2checkout-form').hide();
                            $('#coinpayments-form').hide();
                            $('#paystack-form').hide();
                            $('#paypal-form').hide();
                            $('#shopping-balance-form').hide();
                            $('#tazpay-form').hide();
                        });
                    });
                </script>
            <?php } ?>
            <?php require_once("includes/footer.php"); ?>

            <script src="<?= $site_url ?>/js/paypal.js" id="paypal-js" data-base-url="<?= $site_url; ?>" data-payment-type="proposal"></script>

        </body>

        </html>
<?php
    }
}

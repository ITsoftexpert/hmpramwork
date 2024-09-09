<?php

session_start();
require_once("includes/db.php");

if (!isset($_SESSION['seller_user_name'])) {
    echo "<script>window.open('login','_self')</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$senderId = $row_login_seller->seller_id;

$offerId = $input->get("id");
$qOffer =  $db->select("send_offers", ["offer_id" => $offerId, "sender_id" => $senderId]);
$oOffer = $qOffer->fetch();

if (!$oOffer) {
    echo "<script>window.open('/','_self')</script>";
}

if (isset($_POST['submit-offer'])) {
    $updateForm['description'] = $input->post('description');
    $updateForm['delivery_time'] = $input->post('delivery_time');
    $updateForm['amount'] = $input->post('amount');

    $up = $db->update("send_offers", $updateForm, ["offer_id" => $offerId]);

    if ($up) {
        echo "<script>alert('Proposal offer updated successfully!') </script>";
        echo "<script>window.open('offer-edit?id={$offerId}', '_self')</script>";
    }
}

$request_id = $oOffer->request_id;
$proposal_id = $oOffer->proposal_id;
$description = $oOffer->description;
$delivery_time = $oOffer->delivery_time;
$amount = $oOffer->amount;

$get_requests = $db->select("buyer_requests", array("request_id" => $request_id));
$row_requests = $get_requests->fetch();
$request_title = $row_requests->request_title;
$request_description = $row_requests->request_description;
$request_seller_id = $row_requests->seller_id;

$select_request_seller = $db->select("sellers", array("seller_id" => $request_seller_id));
$row_request_seller = $select_request_seller->fetch();
$request_seller_image = $row_request_seller->seller_image;

$select_proposals = $db->select("proposals", array("proposal_id" => $proposal_id));
$row_proposals = $select_proposals->fetch();
$proposal_title = $row_proposals->proposal_title;
?>
<!DOCTYPE html>

<html lang="en" class="ui-toolkit">

<head>
    <title><?= $site_name; ?> - Edit Offer</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $site_desc; ?>">
    <meta name="keywords" content="<?= $site_keywords; ?>">
    <meta name="author" content="<?= $site_author; ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
    <link href="styles/bootstrap.css" rel="stylesheet">
    <link href="styles/fontawesome-stars.css" rel="stylesheet">
    <link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
    <link href="styles/styles.css" rel="stylesheet">
    <link href="styles/proposalStyles.css" rel="stylesheet">
    <link href="styles/user_nav_styles.css" rel="stylesheet">
    <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
    <link href="styles/owl.carousel.css" rel="stylesheet">
    <link href="styles/owl.theme.default.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script src="https://checkout.stripe.com/checkout.js"></script>
    <link href="styles/sweat_alert.css" rel="stylesheet">
    <link href="styles/animate.css" rel="stylesheet">
    <script type="text/javascript" src="js/ie.js"></script>
    <script type="text/javascript" src="js/sweat_alert.js"></script>
    <script type="text/javascript" src="js/jquery.barrating.min.js"></script>
    <script type="text/javascript" src="js/jquery.sticky.js"></script>
    <?php if (!empty($site_favicon)) { ?>
        <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
    <?php } ?>

</head>

<body class="is-responsive">
    <?php require_once("includes/user_header.php"); ?>
    <div class="container" style="margin-top: 195px !important;">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <h1 class="mb-4"> Your Proposal Details </h1>
                        <div class="modal-body">
                            <div class="request-summary"><!--- request-summary Starts --->
                                <?php if (!empty($request_seller_image)) { ?>
                                    <img src="<?= $site_url; ?>/user_images/<?= $request_seller_image; ?>" width="50" height="50" class="rounded-circle">
                                <?php } else { ?>
                                    <img src="<?= $site_url; ?>/user_images/empty-image.png" width="50" height="50" class="rounded-circle">
                                <?php } ?>
                                <div id="request-description"><!--- request-description Starts --->
                                    <h6 class="text-success mb-1"> <?= $request_title; ?> </h6>
                                    <p> <?= $request_description; ?> </p>
                                </div><!--- request-description Ends --->
                            </div><!--- request-summary Ends --->

                            <form id="proposal-details-form" method="post" action="">
                                <div class="selected-proposal p-3"><!--- selected-proposal p-3 Starts --->
                                    <h5> <?= $proposal_title; ?> </h5>
                                    <hr>
                                    <div class="form-group"><!--- form-group Starts --->
                                        <label class="font-weight-bold"> Description : </label>
                                        <textarea name="description" class="form-control" required=""><?= $description ?></textarea>
                                    </div><!--- form-group Ends --->
                                    <hr>
                                    <div class="form-group"><!--- form-group Starts --->
                                        <label class="font-weight-bold form-control-label"> Delivery Time : </label>
                                        <select class="form-control" name="delivery_time">
                                            <?php
                                            $get_delivery_times = $db->select("delivery_times");
                                            while ($row_delivery_times = $get_delivery_times->fetch()) {
                                                $delivery_proposal_title = $row_delivery_times->delivery_proposal_title;
                                                $selected = $delivery_time == $delivery_proposal_title ? 'selected' : '';
                                                echo "<option value='$delivery_proposal_title' {$selected}> $delivery_proposal_title </option>";
                                            }
                                            ?>
                                        </select>
                                    </div><!--- form-group Ends --->
                                    <hr>
                                    <div class="form-group"><!--- form-group Starts --->
                                        <label class="font-weight-bold"> Total Offer Amount : </label>
                                        <div class="input-group">
                                            <span class="input-group-addon font-weight-bold"> <?= $s_currency; ?> </span>
                                            <input type="number" name="amount" class="form-control" min="5" placeholder="5 Minimum" required="" value="<?= $amount ?>">
                                        </div>
                                    </div><!--- form-group Ends --->
                                    <div class="form-group"><!--- form-group Starts --->
                                        <button type="submit" class="btn btn-success" name="submit-offer">Submit Offer</button>
                                    </div><!--- form-group Ends --->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("includes/footer.php"); ?>
</body>

</html>
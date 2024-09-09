<?php
@session_start();

if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login','_self');</script>";
    exit();
}
$seller_id = $input->get('single_seller');
$get_seller = $db->select("sellers", array("seller_id" => $seller_id));
$row_seller = $get_seller->fetch();

if (!$row_seller) {
    echo "<script>alert('Invalid Seller ID')</script>";
    echo "<script>window.open('admin/index?view_sellers','_self');</script>";
    exit();
}

// Profile weightness
$qWeigtiness = $db->select("seller_profile_weights", array("seller_id" => $seller_id));
$oWeigtiness = $qWeigtiness->fetch();

// HANDLE SUBMIT
// Profile
if (isset($_POST['profile-approve'])) {
    $seller_language = $input->post("seller_language");

    if ($seller_language > 0) {
        $update_proposals = $db->update("proposals", array("language_id" => $seller_language), array("proposal_seller_id" => $seller_id));
        $sel_languages_relation = $db->query("SELECT * FROM languages_relation WHERE seller_id='$seller_id' AND language_id='$seller_language'");
        $count_languages_relation = $sel_languages_relation->rowCount();

        if ($count_languages_relation == 0)
            $insert_language = $db->insert("languages_relation", array("seller_id" => $seller_id, "language_id" => $seller_language, "language_level" => 'conversational'));
    }
    $qSPUpdate = $db->query("SELECT * FROM sellers_profile_tmp WHERE seller_id = :seller_id ORDER BY 1 DESC LIMIT 1", ['seller_id' => $seller_id]);
    $oSPUpdate = $qSPUpdate->fetch();

    $upForm['seller_name'] = $oSPUpdate->seller_name;
    $upForm['seller_phone'] = $oSPUpdate->seller_phone;
    $upForm['seller_country'] = $oSPUpdate->seller_country;
    $upForm['seller_city'] = $oSPUpdate->seller_city;
    $upForm['seller_timezone'] = $oSPUpdate->seller_timezone;
    $upForm['seller_language'] = $oSPUpdate->seller_language;
    $upForm['seller_image'] = $oSPUpdate->seller_image;
    $upForm['seller_image_s3'] = $oSPUpdate->seller_image_s3;
    $upForm['seller_cover_image'] = $oSPUpdate->seller_cover_image;
    $upForm['seller_cover_image_s3'] = $oSPUpdate->seller_cover_image_s3;
    $upForm['seller_headline'] = $oSPUpdate->seller_headline;
    $upForm['seller_about'] = $oSPUpdate->seller_about;

    $update = $db->update("sellers", $upForm, ["seller_id" => $seller_id]);

    if ($update) {

        $db->update("sellers_profile_tmp", ['status' => 1], ["seller_id" => $seller_id]);

        $weightnForm = ['profile_weight' => 40, 'seller_id' => $seller_id];
        if ($oWeigtiness) { // Update
            $db->update("seller_profile_weights", $weightnForm, ["seller_id" => $seller_id]);
        } else { // Add
            $db->insert("seller_profile_weights", $weightnForm);
        }
        $last_update_date = date("F d, Y");
        $db->insert("notifications", array("receiver_id" => $seller_id, "sender_id" => "admin_$admin_id", "order_id" => $seller_id, "reason" => "profile_approved", "date" => $last_update_date, "status" => "unread"));
        $db->insert_log($admin_id, "profile_approved", $seller_id, "inserted");
        echo "<script>alert_success('Profile modification request approved.', 'index?single_seller=" . $seller_id . "');</script>";
    } else {
        echo "<script>alert_error('Profile modification request could not approved.', 'index?single_seller=" . $seller_id . "');</script>";
    }
    exit();
}
if (isset($_POST['profile-modification'])) {
    $feedback = $input->post("profile_feedback");
    $profileId = $input->post("profile_id");
    $update = false;

    $form = ['feedback' => $feedback, 'admin_id' => $admin_id, 'status' => 2];
    $update = $db->update("sellers_profile_tmp", $form, ["id" => $profileId, "seller_id" => $seller_id]);

    if ($update) {
        $last_update_date = date("F d, Y");
        $db->insert("notifications", array("receiver_id" => $seller_id, "sender_id" => "admin_$admin_id", "order_id" => $seller_id, "reason" => "profile_modification", "date" => $last_update_date, "status" => "unread"));

        $db->insert_log($admin_id, "profile_modification", $seller_id, "inserted");
        echo "<script>alert_success('Modification request sent to seller.', 'index?single_seller=" . $seller_id . "');</script>";
    } else {
        echo "<script>alert_error('Modification request could not sent.', 'index?single_seller=" . $seller_id . "');</script>";
    }
}
// professional approve
if (isset($_POST['professional-approve'])) {
    $update = $db->update("seller_pro_info", ['status' => 1], ["seller_id" => $seller_id]);
    if ($update) {
        $weightnForm = ['professional_weight' => 40, 'seller_id' => $seller_id];
        if ($oWeigtiness) { // Update
            $db->update("seller_profile_weights", $weightnForm, ["seller_id" => $seller_id]);
        } else { // Add
            $db->insert("seller_profile_weights", $weightnForm);
        }
        $last_update_date = date("F d, Y");
        $db->insert("notifications", array("receiver_id" => $seller_id, "sender_id" => "admin_$admin_id", "order_id" => $seller_id, "reason" => "professional_approved", "date" => $last_update_date, "status" => "unread"));

        $db->insert_log($admin_id, "professional_approved", $seller_id, "inserted");
        echo "<script>alert_success('Modification request approved.', 'index?single_seller=" . $seller_id . "');</script>";
    } else {
        echo "<script>alert_error('Modification request could not approved.', 'index?single_seller=" . $seller_id . "');</script>";
    }
}
if (isset($_POST['professional-modification'])) {
    $feedback = $input->post("professional_feedback");
    $professionalIds = $input->post("professional_id");

    $professionalIdsArr = explode(", ", $professionalIds);
    if (count($professionalIdsArr) > 0) {
        $update = false;
        foreach ($professionalIdsArr as $id) {
            $form = ['feedback' => $feedback, 'admin_id' => $admin_id, 'status' => 2];

            $update = $db->update("seller_pro_info", $form, ["id" => $id, "seller_id" => $seller_id]);
        }
        if ($update) {
            $last_update_date = date("F d, Y");
            $db->insert("notifications", array("receiver_id" => $seller_id, "sender_id" => "admin_$admin_id", "order_id" => $seller_id, "reason" => "professional_modification", "date" => $last_update_date, "status" => "unread"));

            $db->insert_log($admin_id, "professional_modification", $seller_id, "inserted");
            echo "<script>alert_success('Modification request sent to seller.', 'index?single_seller=" . $seller_id . "');</script>";
        } else {
            echo "<script>alert_error('Modification request could not sent.', 'index?single_seller=" . $seller_id . "');</script>";
        }
    }
}

$seller_name = $row_seller->seller_name;
$seller_user_name = $row_seller->seller_user_name;
$seller_level = $row_seller->seller_level;
$seller_email = $row_seller->seller_email;
$seller_paypal_email = $row_seller->seller_paypal_email;
$seller_payoneer_email = $row_seller->seller_payoneer_email;
$seller_phone = $row_seller->seller_phone;
$seller_image = getImageUrl2("sellers", "seller_image", $row_seller->seller_image);
$seller_about = $row_seller->seller_about;
$seller_verification = $row_seller->seller_verification;
$seller_headline = $row_seller->seller_headline;
$seller_country = $row_seller->seller_country;
$seller_ip = $row_seller->seller_ip;
$seller_register_date = $row_seller->seller_register_date;
$seller_language = $row_seller->seller_language;

$select_seller_accounts = $db->select("seller_accounts", array("seller_id" => $seller_id));
$row_seller_accounts = $select_seller_accounts->fetch();
$withdrawn = $row_seller_accounts->withdrawn;
$used_purchases = $row_seller_accounts->used_purchases;
$pending_clearance = $row_seller_accounts->pending_clearance;
$current_balance = $row_seller_accounts->current_balance;

$get_seller_languages = $db->select("seller_languages", array("language_id" => $seller_language));
$row_seller_languages = $get_seller_languages->fetch();
@$language_title = $row_seller_languages->language_title;

$level_title = $db->select("seller_levels_meta", array("level_id" => $seller_level, "language_id" => $adminLanguage))->fetch()->title;

$qProfessional = $db->select("seller_pro_info", ["seller_id" => $seller_id]);
$cProfessional = $qProfessional->rowCount();

$qSellerUpdate = $db->query("SELECT * FROM sellers_profile_tmp WHERE seller_id = :seller_id ORDER BY 1 DESC LIMIT 1", ['seller_id' => $seller_id]);
$cSellerUpdate = $qSellerUpdate->rowCount();
?>

<div class="breadcrumbs"><!--- breadcrumbs Starts --->
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1><i class="menu-icon fa fa-user"></i> User on <?= ucfirst($site_name) ?></h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Single Seller Details</li>
                </ol>
            </div>
        </div>
    </div>
</div><!--- breadcrumbs Ends --->

<div class="container-fluid"><!--- container Starts --->
    <div class="row"><!--- 2 row Starts --->
        <div class="col-lg-12"><!--- col-lg-12 Starts --->
            <div class="card"><!--- card Starts --->
                <div class="card-header"><!--- card-header Starts --->
                    <h4 class="h4">
                        <i class="fa fa-info-circle text-success"></i>
                        <?= $seller_user_name; ?>'s Info
                    </h4>
                </div><!--- card-header Ends --->

                <div class="card-body row"><!--- card-body row Starts --->
                    <div class="col-md-4"><!--- col-md-4 Starts --->
                        <div class="seller-info mb-3">
                            <!--- seller-info mb-3 Starts --->

                            <?php if (!empty($seller_image)) { ?>

                                <img src="<?= $seller_image; ?>" class="rounded img-fluid">

                            <?php } else { ?>

                                <img src="../user_images/empty-image.png" class="rounded img-fluid">

                            <?php } ?>

                            <div class="seller-info-title">
                                <!--- seller-info-title Starts --->

                                <span class="seller-info-inner text-capitalize"> <?= $seller_user_name; ?> </span>

                                <span class="seller-info-type"> <?= $seller_country; ?> </span>


                            </div>
                            <!--- seller-info-title Ends --->

                        </div>
                        <!--- seller-info mb-3 Ends --->

                        <div class="mb-3">
                            <!--- mb-3 Starts --->

                            <div class="widget-content-expanded">
                                <!--- widget-content-expanded Starts --->

                                <p class="lead">

                                    <span class="font-weight-bold"> Full Name : </span> <?= $seller_name; ?>

                                </p>


                                <p class="lead">

                                    <span class="font-weight-bold"> Username : </span> <?= $seller_user_name; ?>

                                </p>

                                <p class="lead">
                                    <span class="font-weight-bold"> Email : </span> <?= $seller_email; ?>
                                </p>

                                <?php if (!empty($seller_paypal_email)) { ?>
                                    <p class="lead">
                                        <span class="font-weight-bold"> Paypal Email : </span> <?= $seller_paypal_email; ?>
                                    </p>
                                <?php } ?>

                                <?php if (!empty($seller_payoneer_email)) { ?>
                                    <p class="lead">
                                        <span class="font-weight-bold"> Payoneer Email : </span> <?= $seller_payoneer_email; ?>
                                    </p>
                                <?php } ?>

                                <?php if (!empty($seller_phone)) { ?>
                                    <p class="lead">
                                        <span class="font-weight-bold"> Phone : </span> <?= $seller_phone; ?>
                                    </p>
                                <?php } ?>

                                <p class="lead">

                                    <span class="font-weight-bold"> Level : </span> <?= $level_title; ?>

                                </p>

                                <p class="lead">

                                    <span class="font-weight-bold">Main Conversational Language :</span><?= $language_title; ?>

                                </p>


                                <p class="lead">

                                    <span class="font-weight-bold"> Email Verification : </span>

                                    <?php

                                    if ($seller_verification == "ok") {

                                        echo ucfirst($seller_verification);
                                    } else {

                                        echo "No";
                                    }

                                    ?>

                                </p>


                                <p class="lead">

                                    <span class="font-weight-bold"> Ip Address : </span> <?= $seller_ip; ?>

                                </p>


                                <p class="lead">

                                    <span class="font-weight-bold"> Country : </span> <?= $seller_country; ?>

                                </p>


                                <p class="lead">

                                    <span class="font-weight-bold"> Register Date : </span> <?= $seller_register_date; ?>

                                </p>

                            </div>
                            <!--- widget-content-expanded Ends --->

                            <hr class="dotted short">

                            <h5 class="text-muted font-weight-bold"> Headline </h5>

                            <p>
                                <?= $seller_headline; ?>
                            </p>

                        </div><!--- mb-3 Ends --->

                        <div class="mb-3"><!--- mb-3 Starts --->

                            <hr class="dotted">

                            <h5 class="text-muted font-weight-bold">About</h5>

                            <p><?= $seller_about; ?></p>

                        </div><!--- mb-3 Ends --->

                    </div><!--- col-md-4 Ends --->

                    <div class="col-md-8"><!--- col-md-8 Starts --->
                        <h3 class="pb-1">Profile Information</h3>
                        <div class="table-responsive pt-1">
                            <!--- table-responsive mt-4 Starts --->
                            <table class="table table-bordered">
                                <tbody>
                                    <!--- tbody Starts --->
                                    <?php
                                    if ($cSellerUpdate > 0) {
                                        $oSellerUpdate = $qSellerUpdate->fetch();

                                        $proUpdateId = $oSellerUpdate->id;
                                        $userStatus = $oSellerUpdate->status;
                                        $reviewRemark = $userStatus == 0 ? 'review' : ($userStatus == 2 ? 'modification' : 'active');

                                        $seller_name = $oSellerUpdate->seller_name;
                                        $seller_phone = $oSellerUpdate->seller_phone;
                                        $seller_country = $oSellerUpdate->seller_country;
                                        $seller_city = $oSellerUpdate->seller_city;
                                        $seller_timzeone = $oSellerUpdate->seller_timezone;
                                        $seller_language = $oSellerUpdate->seller_language;
                                        $seller_image = $oSellerUpdate->seller_image;
                                        $seller_image_s3 = $oSellerUpdate->seller_image_s3;
                                        $seller_cover_image = $oSellerUpdate->seller_cover_image;
                                        $seller_cover_image_s3 = $oSellerUpdate->seller_cover_image_s3;
                                        $seller_headline = $oSellerUpdate->seller_headline;
                                        $seller_about = $oSellerUpdate->seller_about;
                                        $seller_address = $oSellerUpdate->seller_address;
                                        $seller_address_img1 = $oSellerUpdate->seller_address_img1;
                                        $seller_address_img2 = $oSellerUpdate->seller_address_img2;
                                        $seller_region = $oSellerUpdate->seller_region;
                                        $seller_postal_code = $oSellerUpdate->seller_postal_code;
                                    ?>
                                        <?php if ($reviewRemark == 'review') : ?>
                                            <tr class="table-info">
                                                <td><?= $lang['label']['full_name']; ?></td>
                                                <td><?= $seller_name ?></td>
                                            </tr>
                                            <tr class="table-info">
                                                <td><?= $lang['label']['phone']; ?></td>
                                                <td><?= $seller_phone ?></td>
                                            </tr>
                                            <!-- address -->
                                            <tr class="table-info">
                                                <td>Address</td>
                                                <td><?= $seller_address ?></td>
                                            </tr>
                                            <!-- Address img1 -->
                                            <tr class="table-info">
                                                <td>Address img front</td>
                                                <td><?php
                                                if(!empty($seller_address_img1)){?>
                                                        <a href="../uploads/<?= $seller_address_img1; ?>" target="_blank"><img src="../uploads/<?= $seller_address_img1; ?>" width="80" class="rounded img-fluid"></a>
                                                <?php }
                                                ?></td>
                                            </tr>
                                            <!-- address img2 -->
                                            <tr class="table-info">
                                                <td>Address img back</td>
                                                <td><?php
                                                 if(!empty($seller_address_img2)){?>
                                                        <a href="../uploads/<?= $seller_address_img2; ?>" target="_blank"><img src="../uploads/<?= $seller_address_img2; ?>" width="80" class="rounded img-fluid"></a>

                                                 
                                                <?php } ?>
                                            </td>
                                            </tr>
                                            <!-- postal code -->
                                            <tr class="table-info">
                                                <td>Postal code</td>
                                                <td><?= $seller_postal_code ?></td>
                                            </tr>
                                            <!-- region -->
                                            <tr class="table-info">
                                                <td>Region / state</td>
                                                <td><?= $seller_region ?></td>
                                            </tr>

                                            <tr class="table-info">
                                                <td><?= $lang['label']['city']; ?></td>
                                                <td><?= $seller_city ?></td>
                                            </tr>
                                            <tr class="table-info">
                                                <td><?= $lang['label']['country']; ?></td>
                                                <td><?= $seller_country ?></td>
                                            </tr>
                                            <tr class="table-info">
                                                <td><?= $lang['label']['timezone']; ?></td>
                                                <td><?= $seller_timzeone ?></td>
                                            </tr>
                                            <tr class="table-info">
                                                <td><?= $lang['label']['profile_photo']; ?></td>
                                                <td>
                                                    <?php if (!empty($seller_image)) { ?>
                                                        <a href="../user_images/<?= $seller_image; ?>" target="_blank"><img src="../user_images/<?= $seller_image; ?>" width="80" class="rounded img-fluid"></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr class="table-info">
                                                <td><?= $lang['label']['cover_photo']; ?></td>
                                                <td>
                                                    <?php if (!empty($seller_cover_image)) { ?>
                                                        <a href="../cover_images/<?= $seller_cover_image; ?>" target="_blank">
                                                            <img src="../cover_images/<?= $seller_cover_image; ?>" width="80" class="rounded img-fluid">
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr class="table-info">
                                                <td><?= $lang['label']['headline']; ?></td>
                                                <td><?= $seller_headline ?></td>
                                            </tr>
                                            <tr class="table-info">
                                                <td><?= $lang['label']['description']; ?></td>
                                                <td><?= $seller_about ?></td>
                                            </tr>
                                            <tr class="table-info">
                                                <td colspan="2" class="font-weight-light">Seller have requested to review their profile updates.</td>
                                            </tr>
                                            <tr class="table-info">
                                                <td colspan="2">
                                                    <form action="" method="POST" class="mb-1">
                                                        <input type="hidden" name="seller_language" value="<?= $seller_language ?>">
                                                        <button class="btn btn-success" type="submit" name="profile-approve" formaction=""><i class="fa fa-thumbs-o-up"></i> Approve</button>
                                                    </form>

                                                    <form action="" method="POST">
                                                        <textarea class="form-control mb-1" name="profile_feedback" rows="3" required minlength="5" placeholder="Enter Modification Message"></textarea>
                                                        <input type="hidden" name="profile_id" value="<?= $proUpdateId ?>" />
                                                        <button class="btn btn-warning" type="submit" name="profile-modification"><i class="fa fa-pencil-square-o"></i> Modification Request</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                        endif;
                                        if ($reviewRemark == 'active') :
                                        ?>
                                            <tr class="table-success">
                                                <td colspan="2" class="font-weight-light">Seller hasn't sent any profile update.</td>
                                            </tr>
                                        <?php
                                        endif;
                                        if ($reviewRemark == 'modification') :
                                        ?>
                                            <tr class="table-warning">
                                                <td colspan="2" class="font-weight-light">You have send modification requests.</td>
                                            </tr>
                                        <?php
                                        endif;
                                    } else { ?>
                                        <tr class="table-danger">
                                            <td colspan="2">Seller haven't requested thier profile update yet.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody><!--- tbody Ends --->
                            </table>
                        </div>

                        <h3 class="pb-1">Professional Info </h3>
                        <div class="table-responsive pt-1">
                            <!--- table-responsive mt-4 Starts --->
                            <table class="table table-bordered">
                                <!--- table table-bordered table-hover Starts --->
                                <thead>
                                    <!--- thead Starts --->
                                    <tr>
                                        <th>Title</th>
                                        <th>Organization</th>
                                        <th>Description</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <!--- thead Ends --->
                                <tbody>
                                    <!--- tbody Starts --->
                                    <?php
                                    if ($cProfessional > 0) {
                                        $showModificationForm = false;
                                        $sellerProInfo = [];
                                        $professionalStatus = null;
                                        while ($oProfessional = $qProfessional->fetch()) {

                                            // $proId = $oProfessional->id;
                                            // $catId = $oProfessional->category_id;
                                            // $subCatId = $oProfessional->sub_category_id;
                                            // $startDate = $oProfessional->start_date;
                                            // $endDate = $oProfessional->end_date;

                                            $proInfoId = $oProfessional->id;
                                            $categoryId = $oProfessional->category_id;
                                            $still_working = $oProfessional->still_working;
                                            $description = $oProfessional->description;
                                            $start_date = $oProfessional->start_date;
                                            $end_date = $oProfessional->end_date;
                                            $subCategoryId = $oProfessional->sub_category_id;

                                            $professionalStatus = $status = $oProfessional->status; // 1=active 0=pending 2=modification
                                            $trClass = $status == 1 ? 'table-success' : ($status == 2 ? 'table-info' : 'table-warning');
                                            if ($status == 0) {
                                                $showModificationForm = true;
                                            }
                                            $sellerProInfo[] = $proId;

                                    ?>
                                            <tr class="<?= $trClass ?>">
                                                <td>
                                                    <?= $categoryId; ?>
                                                </td>

                                                <td>
                                                    <?= $subCategoryId; ?>
                                                </td>

                                                <td>
                                                    <?= $description; ?>
                                                </td>

                                                <td>
                                                    <?= $start_date; ?> | <?= $end_date; ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        if ($professionalStatus == 0) :
                                        ?>
                                            <tr class="table-info">
                                                <td colspan="4" class="font-weight-light">Seller have requested to review their professional information updates.</td>
                                            </tr>
                                        <?php
                                        endif;
                                        if ($professionalStatus == 2) :
                                        ?>
                                            <tr class="table-warning">
                                                <td colspan="4" class="font-weight-light">You have send modification requests.</td>
                                            </tr>
                                        <?php
                                        endif;
                                        if ($showModificationForm) :
                                            $proInfoIds = count($sellerProInfo) > 0 ? implode(', ', $sellerProInfo) : '';
                                        ?>
                                            <tr class="table-info">
                                                <td colspan="4">
                                                    <form action="" method="POST" class="mb-1">
                                                        <button class="btn btn-success" type="submit" name="professional-approve" formaction=""><i class="fa fa-thumbs-o-up"></i> Approve</button>
                                                    </form>

                                                    <form action="" method="POST">
                                                        <textarea class="form-control mb-1" name="professional_feedback" id="exampleFormControlTextarea1" rows="3" required minlength="5" placeholder="Enter Modification Message"></textarea>
                                                        <input type="hidden" name="professional_id" value="<?= $proInfoIds ?>" />
                                                        <button class="btn btn-warning" type="submit" name="professional-modification"><i class="fa fa-pencil-square-o"></i> Modification Request</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                        endif;
                                    } else { ?>
                                        <tr class="table-danger">
                                            <td colspan="4">Seller haven't added thier professional info yet.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody><!--- tbody Ends --->

                            </table><!--- table table-bordered table-hover Ends --->

                        </div><!--- table-responsive mt-4 Ends --->

                        <h3 class="pb-1">Orders </h3>

                        <div class="row box">
                            <!--- row box Starts --->

                            <div class="text-center border-box col-md-3">
                                <!--- text-center border-box col-md-3 Starts --->

                                <p> Canceled Orders </p>

                                <?php

                                $count_orders = $db->count("orders", ["seller_id" => $seller_id, "order_status" => 'cancelled']);

                                ?>

                                <h2><?= $count_orders; ?></h2>

                            </div><!--- text-center border-box col-md-3 Ends --->


                            <div class="text-center border-box col-md-3"><!--- text-center border-box col-md-3 Starts --->

                                <p> Delivered Orders </p>

                                <?php

                                $count_orders = $db->count("orders", ["seller_id" => $seller_id, "order_status" => 'delivered']);

                                ?>

                                <h2>
                                    <?= $count_orders; ?>
                                </h2>

                            </div>
                            <!--- text-center border-box col-md-3 Ends --->


                            <div class="text-center border-box col-md-3">
                                <!--- text-center border-box col-md-3 Starts --->

                                <p> Completed Orders </p>

                                <?php

                                $count_orders = $db->count("orders", ["seller_id" => $seller_id, "order_status" => 'completed']);

                                ?>

                                <h2>
                                    <?= $count_orders; ?>
                                </h2>

                            </div>
                            <!--- text-center border-box col-md-3 Ends --->



                            <div class="text-center border-box col-md-3">
                                <!--- text-center border-box col-md-3 Starts --->

                                <p>Current Active Orders </p>

                                <?php

                                $count_orders = $db->count("orders", ["seller_id" => $seller_id, "order_active" => 'yes']);


                                ?>

                                <h2><?= $count_orders; ?></h2>

                            </div><!--- text-center border-box col-md-3 Ends --->

                        </div><!--- row box Ends --->

                        <h3 class="pb-1">Earnings</h3>

                        <div class="row box"><!--- row box Starts --->

                            <div class="text-center border-box col-md-3"><!--- text-center border-box col-md-3 Starts --->

                                <p> Withdrawals </p>

                                <h2><?= $withdrawn; ?></h2>

                            </div><!--- text-center border-box col-md-3 Ends --->

                            <div class="text-center border-box col-md-3"><!--- text-center border-box col-md-3 Starts --->

                                <p> Used on Proposals</p>

                                <h2><?= $used_purchases; ?></h2>

                            </div><!--- text-center border-box col-md-3 Ends --->

                            <div class="text-center border-box col-md-3">
                                <!--- text-center border-box col-md-3 Starts --->

                                <p> Pending </p>

                                <h2><?= $pending_clearance; ?></h2>

                            </div>
                            <!--- text-center border-box col-md-3 Ends --->


                            <div class="text-center border-box col-md-3">
                                <!--- text-center border-box col-md-3 Starts --->

                                <p> Availble Income </p>

                                <h2>
                                    <?= $current_balance; ?>
                                </h2>

                            </div>
                            <!--- text-center border-box col-md-3 Ends --->

                        </div><!--- row box Ends --->

                        <h2>Proposals/Services</h2>

                        <div class="table-responsive pt-1">
                            <!--- table-responsive mt-4 Starts --->

                            <table class="table table-bordered">
                                <!--- table table-bordered table-hover Starts --->

                                <thead>
                                    <!--- thead Starts --->

                                    <tr>

                                        <th>Proposal's Title</th>

                                        <th>Proposal's Image</th>

                                        <th>Proposal's Price</th>

                                        <th>Proposal's Status</th>

                                    </tr>

                                </thead>
                                <!--- thead Ends --->

                                <tbody>
                                    <!--- tbody Starts --->

                                    <?php

                                    // $get_proposals = $db->select("proposals",array("proposal_seller_id" => $seller_id));

                                    $get_proposals = $db->query("select * from proposals where proposal_seller_id=$seller_id AND proposal_status not in ('modification','draft','deleted')");

                                    $count_proposals = $get_proposals->rowCount();

                                    if ($count_proposals == 0) {

                                        echo "
                                        <tr>
                                            <td colspan='4'>
                                                <h3 class='text-center pt-1 pb-1'>This seller has no proposals/services.</h3>
                                            </td>
                                        </tr>
                                        ";
                                    }

                                    while ($row_proposals = $get_proposals->fetch()) {

                                        $proposal_id = $row_proposals->proposal_id;
                                        $proposal_title = $row_proposals->proposal_title;
                                        $proposal_img1 = getImageUrl2("proposals", "proposal_img1", $row_proposals->proposal_img1);
                                        $proposal_price = $row_proposals->proposal_price;
                                        $proposal_status = $row_proposals->proposal_status;

                                        if ($proposal_price == 0) {
                                            $proposal_price = "";
                                            $get_p = $db->select("proposal_packages", array("proposal_id" => $proposal_id));
                                            while ($row = $get_p->fetch()) {
                                                $proposal_price .= " | $s_currency" . $row->price;
                                            }
                                        } else {
                                            $proposal_price = "$s_currency" . $proposal_price;
                                        }

                                    ?>

                                        <tr>

                                            <td>
                                                <?= $proposal_title; ?>
                                            </td>

                                            <td>

                                                <img src="<?= $proposal_img1; ?>" width="60" height="60">

                                            </td>

                                            <td>
                                                <?= $proposal_price; ?>
                                            </td>

                                            <td>
                                                <?= ucfirst($proposal_status); ?>
                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody><!--- tbody Ends --->

                            </table><!--- table table-bordered table-hover Ends --->

                        </div><!--- table-responsive mt-4 Ends --->

                        <?php

                        if ($paymentGateway == 1) {
                            include("../plugins/paymentGateway/admin/single_seller.php");
                        }

                        ?>

                    </div><!--- col-md-8 Ends --->

                </div><!--- card-body row Ends --->
            </div><!--- card Ends --->
        </div><!--- col-lg-12 Ends --->
    </div><!--- 2 row Ends --->
</div><!--- container Ends --->
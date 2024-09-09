<?php
session_start();
require_once("includes/db.php");
if (!isset($_SESSION['seller_user_name'])) {
    echo "<script>window.open('login','_self')</script>";
}
//echo $login_seller_id;

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_level = $row_login_seller->seller_level;
$login_seller_rating = $row_login_seller->seller_rating;
$login_seller_recent_delivery = $row_login_seller->seller_recent_delivery;
$login_seller_country = $row_login_seller->seller_country;
$login_seller_register_date = $row_login_seller->seller_register_date;
$login_seller_image = getImageUrl2("sellers", "seller_image", $row_login_seller->seller_image);
$login_seller_payouts = $row_login_seller->seller_payouts;

if (empty($login_seller_country)) {
    $login_seller_country = "&nbsp;";
}

$select_seller_accounts = $db->select("seller_accounts", array("seller_id" => $login_seller_id));
$row_seller_accounts = $select_seller_accounts->fetch();
$current_balance = $row_seller_accounts->current_balance;
$month_earnings = $row_seller_accounts->month_earnings;

if (isset($_GET['n_id'])) {
    $notification_id = $input->get('n_id');
    $get_notification = $db->select("notifications", array("notification_id" => $notification_id, "receiver_id" => $login_seller_id));
    if ($get_notification->rowCount() == 0) {
        echo "<script>window.open('dashboard','_self')</script>";
    } else {
        $row_notification = $get_notification->fetch();
        $order_id = $row_notification->order_id;
        $reason = $row_notification->reason;
        $update_notification = $db->update("notifications", array("status" => 'read'), array("notification_id" => $notification_id));
        if ($update_notification) {
            if ($reason == "modification" or $reason == "approved" or $reason == "declined") {
                echo "<script>window.open('proposals/view_proposals','_self');</script>";
            } else if ($reason == "offer") {
                echo "<script>window.open('$site_url/requests/view_offers?request_id=$order_id','_self')</script>";
            } elseif ($reason == "approved_request" or $reason == "unapproved_request") {
                echo "<script>window.open('requests/manage_requests','_self');</script>";
            } elseif ($reason == "withdrawal_approved" or $reason == "withdrawal_declined") {
                echo "<script>window.open('withdrawal_requests?id=$order_id','_self');</script>";
            } else {
                echo "<script>window.open('order_details?order_id=$order_id','_self');</script>";
            }
        }
    }
}

if (isset($_GET['delete_notification'])) {
    $delete_id = $input->get('delete_notification');
    $delete_notification = $db->delete("notifications", array('notification_id' => $delete_id, "receiver_id" => $login_seller_id));
    if ($delete_notification->rowCount() == 1) {
        echo "<script>alert('One notification has been deleted.')</script>";
        echo "<script>window.open('dashboard','_self')</script>";
    } else {
        echo "<script>window.open('dashboard','_self')</script>";
    }
}

$select = $db->select("seller_payment_settings", ["level_id" => $login_seller_level]);
$row = $select->fetch();
$payout_day = $row->payout_day;
$payout_time = $row->payout_time;
$payout_anyday = $row->payout_anyday;
$payout_date = date("F $payout_day, Y") . " $payout_time";
$payout_date = new DateTime($payout_date);

$p_date = "";
if (empty($payout_anyday) and $login_seller_payouts == 0 and date("d") <= $payout_day) {
    $p_date = $payout_date->format("F d, Y H:i A");
} else if ($login_seller_payouts > 1) {
    $interval = new DateInterval('P1M');
    $payout_date->add($interval);
    $p_date = $payout_date->format("F d, Y H:i A");
}

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
    <title><?= $site_name; ?> - <?= ucfirst($lang["menu"]["membership_plan"]); ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $site_desc; ?>">
    <meta name="keywords" content="<?= $site_keywords; ?>">
    <meta name="author" content="<?= $site_author; ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
    <link href="styles/bootstrap.css" rel="stylesheet">
    <link href="styles/custom.css" rel="stylesheet">
    <!-- Custom css code from modified in admin panel --->
    <link href="styles/styles.css" rel="stylesheet">
    <link href="styles/user_nav_styles.css" rel="stylesheet">
    <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
    <link href="styles/owl.carousel.css" rel="stylesheet">
    <link href="styles/owl.theme.default.css" rel="stylesheet">
    <link href="styles/sweat_alert.css" rel="stylesheet">
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="js/ie.js"></script>
    <script type="text/javascript" src="js/sweat_alert.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <?php if (!empty($site_favicon)) { ?>
        <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
    <?php } ?>
    <style>
        .fixed {
            position: fixed;
            transition: all 3s linear;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 100;
        }

        .alter-margin-top {
            /* margin-top: -130px; */
            margin-bottom: 40px;
        }

        .padding-top-10 {
            padding-top: 10px;
        }

        @media(min-width:1025px) {
            .content {
                margin: 0px 100px;
            }
        }

        @media (min-width:768px) and (max-width:1024px) {
            .display_flex_001 {
                width: 100%;
                display: flex;
            }

            .content {
                margin: 0px 22px;
            }
        }

        @media(max-width:768px) {
            .menu {
                margin: 0 auto;
                padding: 20px !important;

            }
        }

        @media(max-width:640px) {
            .menu {
                margin: 0 auto;
                padding: 10px !important;

            }
        }
    </style>
</head>

<body class="is-responsive">
    <?php require_once("includes/user_header.php") ?>
    <section class='content'>
        <div class="container-fluid pt-5">
            <div class="row alter-margin-top">
                <div class="col-md-12 mb-4 bg-light text-center reg-sec-1 ">
                    <!--- col-lg-8 col-md-7 mb-3 Starts --->

                    <h2 class="padding-top-10"> Try Freelancer Membership</h2>
                    <p>
                        Designed to maximise your freelancers success and earnings! <br>
                        Change plans anytime, condition apply see FAQ.
                    </p>

                    <!--                        <div class="btn-group" role="group" aria-label="Basic example">-->
                    <!--                            <button type="button" class="btn btn-success btn-group-sm">Monthly Plan</button>-->
                    <!--                            <button type="button" class="btn btn-secondary btn-group-sm">Annuel Plan</button>-->
                    <!--                        </div>-->


                </div>
                <!--- col-lg-8 col-md-7 mb-3 Ends --->
            </div>


            <div class="row-fluid clearfix">
                <div class="pricing-wrapper comparison-table clearfix style-3 display_flex_001">
                    <div class="col-md-4 pricing-col list-feature">
                        <div class="pricing-card">
                            <div class="pricing-header">
                                <h5>Choose Your Plan</h5>
                                <p>Compare Package Feature</p>
                            </div>
                            <div class="pricing-feature">
                                <li>
                                    <p> Create Active Service </p>
                                </li>
                                <li>
                                    <p> Bids Per Month </p>
                                </li>
                                <li>
                                    <p> Skills </p>
                                </li>
                                <li>
                                    <p> Client Engagement </p>
                                </li>
                                <li>
                                    <p> Daily Withdrawal Requests </p>
                                </li>
                                <!-- <li>
                                    <p> Employer Followings </p>
                                </li> -->
                                <!-- <li>
                                    <p> Percentage Per Project </p>
                                </li> -->
                                <li>
                                    <p> Get Recommendation </p>
                                </li>
                                <!-- <li>
                                    <p> Create Portfolio </p>
                                </li> -->
                                <li>
                                    <p> Custom Description </p>
                                </li>
                                <li>
                                    <p> Referrals </p>
                                </li>
                                <li>
                                    <p> Project Bookmarks </p>
                                </li>
                                <li>
                                    <p> Custom Cover Photo </p>
                                </li>
                                <li>
                                    <p> Create a Offer Coupon </p>
                                </li>
                                <li>
                                    <p> Free Highlighted Bids </p>
                                </li>
                                <!-- <li>
                                    <p> Hide Project Offer for Other Freelancers </p>
                                </li> -->
                                <li>
                                    <p> Type of Account </p>
                                </li>
                            </div>
                        </div>
                    </div>



                    <?php
                    $get_plan = $db->select("membership_table", ["plan_status" => "Active"]);
                    // check if the user has already subscribed for this package
                    $checkuser = $db->select("`memb_plan_detail` mp LEFT JOIN `membership_table` m ON mp.memb_tbl_id = m.id WHERE mp.seller_id = $row_login_seller->seller_id AND mp.memb_status = 'Active' AND mp.memb_end_date >= CURRENT_TIMESTAMP ORDER BY mp.id DESC LIMIT 1");
                    $rowPurchased = $checkuser->fetch();
                    // echo "<pre>"; print_r($get_plan->fetch()); exit;
                    // echo "<pre>"; print_r($planSubscribed); exit;
                    // print("<pre>" . print_r($rowPurchased, true) . "</pre>");
                    // exit;


                    while ($row_plan = $get_plan->fetch()) {
                        $plan_id = $row_plan->id;
                        $plan_name = $row_plan->plan_name;
                        $create_active_service = $row_plan->create_active_service;
                        $bids_per_month = $row_plan->bids_per_month;
                        $skills = $row_plan->skills;
                        $client_engagement = $row_plan->client_engagement;
                        $daily_withdrawal_req = $row_plan->daily_withdrawal_req;
                        $employer_follow = $row_plan->employer_follow;
                        $percentage_per_project = $row_plan->percentage_per_project;
                        $get_recom = $row_plan->get_recom;
                        $create_portfolio = $row_plan->create_portfolio;
                        $custome_desc = $row_plan->custome_desc;
                        $referrals = $row_plan->referrals;
                        $project_bookmark = $row_plan->project_bookmark;
                        $custom_cover_photo = $row_plan->custom_cover_photo;
                        $create_offer_coupon = $row_plan->create_offer_coupon;
                        $free_highlight_bids = $row_plan->free_highlight_bids;
                        $hide_project_others = $row_plan->hide_project_others;
                        $account_type = $row_plan->account_type;
                        $plan_price_month = $row_plan->plan_price_month;
                        //                            $plan_discount_month = $row_plan->plan_discount_price;
                        $plan_price_annuel = $row_plan->plan_price_annuel;
                        $plan_discount_annuel = $row_plan->plan_discount_annuel;
                        $plan_status = $row_plan->plan_status;
                    ?>


                        <div class="col-md-2 pricing-col <?php if ($plan_id == 1) {
                                                                echo "person";
                                                            } elseif ($plan_id == 2) {
                                                                echo "current unlim";
                                                            } else {
                                                                echo "business";
                                                            }
                                                            ?>">
                            <div class="pricing-card">
                                <div class="pricing-header">
                                    <h5><?= $plan_name ?></h5>
                                    <div class="price-box">
                                        <div class="price"><?php if ($plan_price_month == 0) {
                                                                echo "Free";
                                                            } else {
                                                                echo $plan_price_month;
                                                            } ?>
                                            <div class="currency">$</div>
                                            <div class="plan">/M</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pricing-feature">
                                    <li>
                                        <p>


                                            <i class="<?php if ($create_active_service == "Yes") {
                                                            echo "fa fa-check Available";
                                                        } elseif ($create_active_service == "No") {
                                                            echo "fa fa-times unavailable";
                                                        } else {
                                                            echo "";
                                                        } ?> "> <?php if ($create_active_service == "Yes" || $create_active_service == "No") {
                                                                    echo "";
                                                                } else {
                                                                    echo $create_active_service;
                                                                }; ?> </i>

                                        </p>
                                    </li>
                                    <li>
                                        <p>

                                            <i class="<?php if ($bids_per_month == "Yes") {
                                                            echo "fa fa-check Available";
                                                        } elseif ($bids_per_month == "No") {
                                                            echo "fa fa-times unavailable";
                                                        } else {
                                                            echo "";
                                                        } ?> "> <?php if ($bids_per_month == "Yes" || $bids_per_month == "No") {
                                                                    echo "";
                                                                } else {
                                                                    echo $bids_per_month;
                                                                }; ?> </i>


                                        </p>
                                    </li>
                                    <li>
                                        <p>

                                            <i class="<?php if ($skills == "Yes") {
                                                            echo "fa fa-check Available";
                                                        } elseif ($skills == "No") {
                                                            echo "fa fa-times unavailable";
                                                        } else {
                                                            echo "";
                                                        } ?> "> <?php if ($skills == "Yes" || $skills == "No") {
                                                                    echo "";
                                                                } else {
                                                                    echo $skills;
                                                                }; ?> </i>

                                        </p>
                                    </li>
                                    <li>
                                        <p>

                                            <i class="<?php if ($client_engagement == "Yes") {
                                                            echo "fa fa-check Available";
                                                        } elseif ($client_engagement == "No") {
                                                            echo "fa fa-times unavailable";
                                                        } else {
                                                            echo "";
                                                        } ?> "> <?php if ($client_engagement == "Yes" || $client_engagement == "No") {
                                                                    echo "";
                                                                } else {
                                                                    echo $client_engagement;
                                                                }; ?> </i>
                                        </p>
                                    </li>
                                    <li>
                                        <p>

                                            <i class="<?php if ($daily_withdrawal_req == "Yes") {
                                                            echo "fa fa-check Available";
                                                        } elseif ($daily_withdrawal_req == "No") {
                                                            echo "fa fa-times unavailable";
                                                        } else {
                                                            echo "";
                                                        } ?> "> <?php if ($daily_withdrawal_req == "Yes" || $daily_withdrawal_req == "No") {
                                                                    echo "";
                                                                } else {
                                                                    echo $daily_withdrawal_req;
                                                                }; ?> </i>
                                        </p>
                                    </li>
                                    <!-- <li>
                                        <p>

                                            <i class="<?php if ($employer_follow == "Yes") {
                                                            echo "fa fa-check Available";
                                                        } elseif ($employer_follow == "No") {
                                                            echo "fa fa-times unavailable";
                                                        } else {
                                                            echo "";
                                                        } ?> "> <?php if ($employer_follow == "Yes" || $employer_follow == "No") {
                                                                    echo "";
                                                                } else {
                                                                    echo $employer_follow;
                                                                }; ?> </i>

                                        </p>
                                    </li> -->
                                    <!-- <li>
                                        <p>
                                            <span> <?= $percentage_per_project . "%"; ?> </span>


                                        </p>
                                    </li> -->
                                    <li>
                                        <p>
                                            <i class="<?php if ($get_recom == "Yes") {
                                                            echo "fa fa-check Available";
                                                        } elseif ($get_recom == "No") {
                                                            echo "fa fa-times unavailable";
                                                        } else {
                                                            echo "";
                                                        } ?> "> <?php if ($get_recom == "Yes" || $get_recom == "No") {
                                                                    echo "";
                                                                } else {
                                                                    echo $get_recom;
                                                                }; ?> </i>

                                        </p>
                                    </li>
                                    <!-- <li>
                                        <p>


                                            <i class="<?php if ($create_portfolio == "Yes") {
                                                            echo "fa fa-check Available";
                                                        } elseif ($create_portfolio == "No") {
                                                            echo "fa fa-times unavailable";
                                                        } else {
                                                            echo "";
                                                        } ?>"> <?= $create_portfolio; ?> </i>
                                        </p>
                                    </li> -->
                                    <li>
                                        <p>
                                            <i class="<?php if ($custome_desc == "Yes") {
                                                            echo "fa fa-check Available";
                                                        } elseif ($custome_desc == "No") {
                                                            echo "fa fa-times unavailable";
                                                        } else {
                                                            echo "";
                                                        } ?>"> <?php if ($custome_desc == "Yes" || $custome_desc == "No") {
                                                                    echo "";
                                                                } else {
                                                                    echo $custome_desc;
                                                                }; ?> </i>
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            <i class="<?php if ($referrals == "Yes") {
                                                            echo "fa fa-check Available";
                                                        } elseif ($referrals == "No") {
                                                            echo "fa fa-times unavailable";
                                                        } else {
                                                            echo "";
                                                        } ?>"> <?php if ($referrals == "Yes" || $referrals == "No") {
                                                                    echo "";
                                                                } else {
                                                                    echo $referrals;
                                                                }; ?> </i>
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            <i class="<?php if ($project_bookmark == "Yes") {
                                                            echo "fa fa-check Available";
                                                        } elseif ($project_bookmark == "No") {
                                                            echo "fa fa-times unavailable";
                                                        } else {
                                                            echo "";
                                                        } ?>"> <?php if ($project_bookmark == "Yes" || $project_bookmark == "No") {
                                                                    echo "";
                                                                } else {
                                                                    echo $project_bookmark;
                                                                }; ?> </i>
                                        </p>
                                    </li>
                                    <li>
                                        <p>

                                            <i class="<?php if ($custom_cover_photo == "Yes") {
                                                            echo "fa fa-check Available";
                                                        } elseif ($custom_cover_photo == "No") {
                                                            echo "fa fa-times unavailable";
                                                        } else {
                                                            echo "";
                                                        } ?>"> <?php if ($custom_cover_photo == "Yes" || $custom_cover_photo == "No") {
                                                                    echo "";
                                                                } else {
                                                                    echo $custom_cover_photo;
                                                                }; ?> </i>

                                        </p>
                                    </li>
                                    <li>
                                        <p>


                                            <i class="<?php if ($create_offer_coupon == "Yes") {
                                                            echo "fa fa-check Available";
                                                        } elseif ($create_offer_coupon == "No") {
                                                            echo "fa fa-times unavailable";
                                                        } else {
                                                            echo "";
                                                        } ?>"> <?php if ($create_offer_coupon == "Yes" || $create_offer_coupon == "No") {
                                                                    echo "";
                                                                } else {
                                                                    echo $create_offer_coupon;
                                                                }; ?> </i>

                                        </p>
                                    </li>
                                    <li>
                                        <p>

                                            <i class="<?php if ($free_highlight_bids == "Yes") {
                                                            echo "fa fa-check Available";
                                                        } elseif ($free_highlight_bids == "No") {
                                                            echo "fa fa-times unavailable";
                                                        } else {
                                                            echo "";
                                                        } ?>"> <?php if ($free_highlight_bids == "Yes" || $free_highlight_bids == "No") {
                                                                    echo "";
                                                                } else {
                                                                    echo $free_highlight_bids;
                                                                }; ?></i>


                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            <i class="<?php if ($account_type == "Yes") {
                                                            echo "fa fa-check Available";
                                                        } elseif ($account_type == "No") {
                                                            echo "fa fa-times unavailable";
                                                        } else {
                                                            echo "";
                                                        } ?>"> <?php if ($account_type == "Yes" || $account_type == "No") {
                                                                    echo "";
                                                                } else {
                                                                    echo $account_type;
                                                                }; ?> </i>

                                        </p>
                                    </li>
                                    <!-- <li>
                                        <p>
                                            <i class="fa fa-times unavailable"></i>
                                        </p>
                                        <?php echo "hello world!"; ?>
                                    </li> -->
                                </div>
                                <div class="pricing-footer">
                                    <a href="<?php echo $plan_price_month == 0 ? "#" : $site_url . "/buy_plan?pid=" . $plan_id ?>" class="btn btn-act rounded btn-line<?php echo (($plan_price_month == 0 & !$row_purchsed) || ($row_purchsed && $row_purchsed->memb_tbl_id == $plan_id)) ? " disabled" : ""; ?>">
                                        <span>
                                            <?php
                                            if (!$rowPurchased && $plan_price_month == 0) {
                                                echo "Free (Current)";
                                            } else {
                                                if (!$rowPurchased) {
                                                    echo "Upgrade";
                                                } else {
                                                    if ($rowPurchased->id == $plan_id) {
                                                        echo "Activated";
                                                    } else if ($row_plan->plan_price_month != 0 && $rowPurchased->plan_price_month > $row_plan->plan_price_month) {
                                                        echo "Downgrade";
                                                    } else if ($row_plan->plan_price_month != 0 && $rowPurchased->plan_price_month < $row_plan->plan_price_month) {
                                                        echo "Upgrade";
                                                    } else {
                                                        echo "Free";
                                                    }
                                                }
                                            }
                                            //                                             $isPlanActive = false;
                                            //                                             // TODO
                                            //                                             // check if purchased is expired or not
                                            //                                             if ($row_purchsed) {
                                            //                                                 $expiryDateTime = date_create($row_purchsed->memb_end_date);
                                            //                                                 $expiryDateTime = date_format($expiryDateTime, "Y-m-d H:i:s");
                                            //                                                 $endDate = strtotime($expiryDateTime);
                                            //                                                 $today = strtotime("now");
                                            //                                                 $isPlanActive = $today > $endDate ? false : true;
                                            //                                             }
                                            // // echo $isPlanActive ? "YES" : "NO"; echo $row_purchsed->plan_price_month ." -- " .$row_plan->plan_price_month; exit;
                                            //                                             if ((!$row_purchsed && $plan_price_month == 0) || ($row_purchsed && $plan_id == $row_purchsed->memb_tbl_id)) {
                                            //                                                 echo "Activated";
                                            //                                             } else {
                                            //                                                 if (!$row_purchsed) {
                                            //                                                     echo "Upgrade";
                                            //                                                 } else {
                                            //                                                     if ($row_plan->plan_price_month != 0 && $row_purchsed->plan_price_month > $row_plan->plan_price_month)
                                            //                                                         echo "Downgrade";
                                            //                                                     else if ($row_plan->plan_price_month != 0 && $row_purchsed->plan_price_month < $row_plan->plan_price_month)
                                            //                                                         echo 'Upgrade';
                                            //                                                     else
                                            //                                                         echo "Free";
                                            //                                                 }
                                            //                                             }
                                            ?>
                                        </span>
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <!-- Faqs -->
            <div class="row mt-5" style="padding-top: 15px">
                <div class="col-md-12 mb-12 bg-light mb-12 reg-sec-1 " style="bottom: 40px">
                    <!--- col-lg-8 col-md-7 mb-3 Starts --->

                    <div class="menu">
                        <h1>FAQs</h1>
                        <div class="question">
                            <input type="checkbox" id="type1" class="accordion">
                            <label for="type1">
                                What is an accordion?
                                <div id="icon">

                                    <span aria-hidden="true"></span>
                                </div>
                            </label>
                            <ul id="links1">
                                <li>
                                    Аccordion is a user interface component that allows the user to hide or reveal content.
                                </li>
                            </ul>
                        </div>
                        <div class="question">
                            <input type="checkbox" id="type2" class="accordion">
                            <label for="type2">
                                When should we use accordion?
                                <div id="icon">
                                    <span aria-hidden="true"></span>
                                </div>
                            </label>
                            <ul id="links2">
                                <li>
                                    Accordions can be used to navigate through websites and pages. They can group links to
                                    help the user navigate through interfaces. This is especially helpful in mobile design
                                    as it allows you to collapse information into different sections or pages.
                                </li>
                            </ul>
                        </div>
                        <div class="question">
                            <input type="checkbox" id="type3" class="accordion">
                            <label for="type3">
                                Why accordions are not always a good solution?
                                <div id="icon">
                                    <span aria-hidden="true"></span>
                                </div>
                            </label>
                            <ul id="links3">
                                <li>
                                    While they can make a web page easier to read, accordions should be used in moderation
                                    elements to describe situations or deliver information clearly. If users need to see
                                    most or all of the information on a page, it is better to use well-formatted text
                                    instead.
                                </li>
                            </ul>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    </section>

    <?php require_once("includes/footer.php"); ?>
    <style>
        /* ======================== */
        /*   Typography         */
        /* ========================
    Custome CSS only for membership_subs plan
    */


        @media (min-width: 992px) {
            .container {
                width: 970px;
            }

            .col-md-1,
            .col-md-2,
            .col-md-3,
            .col-md-4,
            .col-md-5,
            .col-md-6,
            .col-md-7,
            .col-md-8,
            .col-md-9,
            .col-md-10,
            .col-md-11,
            .col-md-12 {
                float: left;
            }

            .col-md-1 {
                width: 8.33333%;
            }

            .col-md-2 {
                width: 16.66667%;
            }

            .col-md-3 {
                width: 25%;
            }

            .col-md-4 {
                width: 33.33333%;
            }

            .col-md-5 {
                width: 41.66667%;
            }

            .col-md-6 {
                width: 50%;
            }

            .col-md-7 {
                width: 58.33333%;
            }

            .col-md-8 {
                width: 66.66667%;
            }

            .col-md-9 {
                width: 75%;
            }

            .col-md-10 {
                width: 83.33333%;
            }

            .col-md-11 {
                width: 91.66667%;
            }

            .col-md-12 {
                width: 100%;
            }

            .col-md-pull-0 {
                right: auto;
            }

            .col-md-pull-1 {
                right: 8.33333%;
            }

            .col-md-pull-2 {
                right: 16.66667%;
            }

            .col-md-pull-3 {
                right: 25%;
            }

            .col-md-pull-4 {
                right: 33.33333%;
            }

            .col-md-pull-5 {
                right: 41.66667%;
            }

            .col-md-pull-6 {
                right: 50%;
            }

            .col-md-pull-7 {
                right: 58.33333%;
            }

            .col-md-pull-8 {
                right: 66.66667%;
            }

            .col-md-pull-9 {
                right: 75%;
            }

            .col-md-pull-10 {
                right: 83.33333%;
            }

            .col-md-pull-11 {
                right: 91.66667%;
            }

            .col-md-pull-12 {
                right: 100%;
            }

            .col-md-push-0 {
                left: auto;
            }

            .col-md-push-1 {
                left: 8.33333%;
            }

            .col-md-push-2 {
                left: 16.66667%;
            }

            .col-md-push-3 {
                left: 25%;
            }

            .col-md-push-4 {
                left: 33.33333%;
            }

            .col-md-push-5 {
                left: 41.66667%;
            }

            .col-md-push-6 {
                left: 50%;
            }

            .col-md-push-7 {
                left: 58.33333%;
            }

            .col-md-push-8 {
                left: 66.66667%;
            }

            .col-md-push-9 {
                left: 75%;
            }

            .col-md-push-10 {
                left: 83.33333%;
            }

            .col-md-push-11 {
                left: 91.66667%;
            }

            .col-md-push-12 {
                left: 100%;
            }

            .col-md-offset-0 {
                margin-left: 0%;
            }

            .col-md-offset-1 {
                margin-left: 8.33333%;
            }

            .col-md-offset-2 {
                margin-left: 16.66667%;
            }

            .col-md-offset-3 {
                margin-left: 25%;
            }

            .col-md-offset-4 {
                margin-left: 33.33333%;
            }

            .col-md-offset-5 {
                margin-left: 41.66667%;
            }

            .col-md-offset-6 {
                margin-left: 50%;
            }

            .col-md-offset-7 {
                margin-left: 58.33333%;
            }

            .col-md-offset-8 {
                margin-left: 66.66667%;
            }

            .col-md-offset-9 {
                margin-left: 75%;
            }

            .col-md-offset-10 {
                margin-left: 83.33333%;
            }

            .col-md-offset-11 {
                margin-left: 91.66667%;
            }

            .col-md-offset-12 {
                margin-left: 100%;
            }
        }


        @media (min-width: 1200px) {
            .container {
                width: 1170px;
            }

            .col-lg-1,
            .col-lg-2,
            .col-lg-3,
            .col-lg-4,
            .col-lg-5,
            .col-lg-6,
            .col-lg-7,
            .col-lg-8,
            .col-lg-9,
            .col-lg-10,
            .col-lg-11,
            .col-lg-12 {
                float: left;
            }

            .col-lg-1 {
                width: 8.33333%;
            }

            .col-lg-2 {
                width: 16.66667%;
            }

            .col-lg-3 {
                width: 25%;
            }

            .col-lg-4 {
                width: 33.33333%;
            }

            .col-lg-5 {
                width: 41.66667%;
            }

            .col-lg-6 {
                width: 50%;
            }

            .col-lg-7 {
                width: 58.33333%;
            }

            .col-lg-8 {
                width: 66.66667%;
            }

            .col-lg-9 {
                width: 75%;
            }

            .col-lg-10 {
                width: 83.33333%;
            }

            .col-lg-11 {
                width: 91.66667%;
            }

            .col-lg-12 {
                width: 100%;
            }

            .col-lg-pull-0 {
                right: auto;
            }

            .col-lg-pull-1 {
                right: 8.33333%;
            }

            .col-lg-pull-2 {
                right: 16.66667%;
            }

            .col-lg-pull-3 {
                right: 25%;
            }

            .col-lg-pull-4 {
                right: 33.33333%;
            }

            .col-lg-pull-5 {
                right: 41.66667%;
            }

            .col-lg-pull-6 {
                right: 50%;
            }

            .col-lg-pull-7 {
                right: 58.33333%;
            }

            .col-lg-pull-8 {
                right: 66.66667%;
            }

            .col-lg-pull-9 {
                right: 75%;
            }

            .col-lg-pull-10 {
                right: 83.33333%;
            }

            .col-lg-pull-11 {
                right: 91.66667%;
            }

            .col-lg-pull-12 {
                right: 100%;
            }

            .col-lg-push-0 {
                left: auto;
            }

            .col-lg-push-1 {
                left: 8.33333%;
            }

            .col-lg-push-2 {
                left: 16.66667%;
            }

            .col-lg-push-3 {
                left: 25%;
            }

            .col-lg-push-4 {
                left: 33.33333%;
            }

            .col-lg-push-5 {
                left: 41.66667%;
            }

            .col-lg-push-6 {
                left: 50%;
            }

            .col-lg-push-7 {
                left: 58.33333%;
            }

            .col-lg-push-8 {
                left: 66.66667%;
            }

            .col-lg-push-9 {
                left: 75%;
            }

            .col-lg-push-10 {
                left: 83.33333%;
            }

            .col-lg-push-11 {
                left: 91.66667%;
            }

            .col-lg-push-12 {
                left: 100%;
            }

            .col-lg-offset-0 {
                margin-left: 0%;
            }

            .col-lg-offset-1 {
                margin-left: 8.33333%;
            }

            .col-lg-offset-2 {
                margin-left: 16.66667%;
            }

            .col-lg-offset-3 {
                margin-left: 25%;
            }

            .col-lg-offset-4 {
                margin-left: 33.33333%;
            }

            .col-lg-offset-5 {
                margin-left: 41.66667%;
            }

            .col-lg-offset-6 {
                margin-left: 50%;
            }

            .col-lg-offset-7 {
                margin-left: 58.33333%;
            }

            .col-lg-offset-8 {
                margin-left: 66.66667%;
            }

            .col-lg-offset-9 {
                margin-left: 75%;
            }

            .col-lg-offset-10 {
                margin-left: 83.33333%;
            }

            .col-lg-offset-11 {
                margin-left: 91.66667%;
            }

            .col-lg-offset-12 {
                margin-left: 100%;
            }

            .visible-lg {
                display: block !important;
            }

            table.visible-lg {
                display: table !important;
            }

            tr.visible-lg {
                display: table-row !important;
            }

            th.visible-lg,
            td.visible-lg {
                display: table-cell !important;
            }

            .visible-lg-block {
                display: block !important;
            }

            .visible-lg-inline {
                display: inline !important;
            }

            .visible-lg-inline-block {
                display: inline-block !important;
            }

            .hidden-lg {
                display: none !important;
            }
        }

        @media print {

            *,
            *:before,
            *:after {
                background: transparent !important;
                color: #000 !important;
                box-shadow: none !important;
                text-shadow: none !important;
            }

            a,
            a:visited {
                text-decoration: underline;
            }

            a[href]:after {
                content: " (" attr(href) ")";
            }

            abbr[title]:after {
                content: " (" attr(title) ")";
            }

            a[href^="#"]:after,
            a[href^="javascript:"]:after {
                content: "";
            }

            pre,
            blockquote {
                border: 1px solid #999;
                page-break-inside: avoid;
            }

            thead {
                display: table-header-group;
            }

            tr,
            img {
                page-break-inside: avoid;
            }

            img {
                max-width: 100% !important;
            }

            p,
            h2,
            h3 {
                orphans: 3;
                widows: 3;
            }

            h2,
            h3 {
                page-break-after: avoid;
            }

            .navbar {
                /* display: none; */
            }

            .btn>.caret,
            .dropup>.btn>.caret {
                border-top-color: #000 !important;
            }

            .label {
                border: 1px solid #000;
            }

            .table {
                border-collapse: collapse !important;
            }

            .table td,
            .table th {
                background-color: #fff !important;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid #ddd !important;
            }

            .visible-print {
                display: block !important;
            }

            table.visible-print {
                display: table !important;
            }

            tr.visible-print {
                display: table-row !important;
            }

            th.visible-print,
            td.visible-print {
                display: table-cell !important;
            }

            .visible-print-block {
                display: block !important;
            }

            .visible-print-inline {
                display: inline !important;
            }

            .visible-print-inline-block {
                display: inline-block !important;
            }

            .hidden-print {
                display: none !important;
            }
        }


        /* =================== */
        /*   Pricing Wrapper   */
        /* =================== */
        .pricing-wrapper {
            font-family: roboto;
        }

        .pricing-wrapper:hover .current {
            top: 0;
            padding-bottom: 20px;
            padding-top: 20px;
        }

        .pricing-wrapper:hover .current .pricing-card {
            -webkit-transition: all 0.2s linear 0s;
            transition: all 0.2s linear 0s;
            box-shadow: none;
        }

        .pricing-wrapper:hover .current .pricing-footer {
            height: 80px;
        }

        .pricing-wrapper:hover .current .pricing-footer a {
            bottom: 0;
            position: relative;
        }

        .pricing-wrapper:hover .current:hover {
            top: 0;
            padding-top: 0;
            padding-bottom: 0;
        }

        .pricing-wrapper:hover .current:hover .pricing-card {
            box-shadow: 0 0 20px -2px rgba(0, 0, 0, 0.25);
        }

        .pricing-wrapper:hover .current:hover .pricing-footer {
            height: 120px;
        }

        .pricing-wrapper:hover .current:hover .pricing-footer a {
            bottom: -20px;
            position: relative;
        }

        .stripped-table [class*=col-] {
            padding: 0;
            margin: -1px;
        }

        .stripped-table .pricing-col {
            padding: 20px 0;
        }

        .stripped-table.current {
            padding-left: 0;
            padding-right: 0;
        }

        .stripped-table .ribbon {
            left: calc(100% - 124px);
        }

        /* =================== */
        /*   Pricing Cards     */
        /* =================== */
        .pricing-card {
            position: relative;
            border: 1px solid #ddd;
            width: 100%;
            top: 0;
            -webkit-transition: all 0.2s linear 0s;
            transition: all 0.2s linear 0s;
            z-index: 1;
        }

        /* =================== */
        /*   Pricing Header    */
        /* =================== */
        .pricing-header {
            background: #fff;
            position: relative;
            height: 200px;
            border-bottom: 1px solid #1976d2;
            margin: -1px;
            margin-bottom: 0;
            text-align: center;
        }

        .pricing-header h5 {
            background: #2196f3;
            color: #fff;
            font-size: 16px;
            line-height: normal;
            margin: 0 auto;
            padding: 15px 20px;
            text-transform: uppercase;
        }

        .pricing-header p {
            margin: 0 auto;
            color: #fff;
            display: inline-block;
            font-style: italic;
        }

        /* =================== */
        /*   Pricing Box       */
        /* =================== */
        .price-box {
            border-radius: 100px;
            display: block;
            margin: 25px auto;
            position: relative;
            line-height: 100px;
            height: 100px;
            width: 100px;
        }

        .price-box:before {
            border-radius: 50%;
            box-shadow: 0 0 0 5px white inset, 0 1px 2px transparent;
            content: "";
            height: 100%;
            left: 0;
            position: absolute;
            top: 0;
            width: 100%;
        }

        .price-box .price {
            color: #fff;
            display: inline-block;
            font-family: roboto;
            font-size: 34px;
            font-weight: 600;
            position: relative;
            letter-spacing: -2px;
        }

        .price-box .currency {
            font-size: 50%;
            font-weight: 600;
            left: -10px;
            line-height: inherit;
            position: absolute;
            top: -6px;
            letter-spacing: 0;
        }

        .price-box .plan {
            bottom: -26px;
            font-family: roboto;
            font-size: 36%;
            font-weight: 400;
            left: 0;
            letter-spacing: 0px !important;
            margin: 0 auto;
            position: absolute;
            right: 0;
            text-transform: capitalize;
        }

        /* ============================ */
        /*   Pricing Feature(Content)   */
        /* ============================ */
        .pricing-feature {
            position: relative;
            text-align: left;
        }

        .pricing-feature li {
            list-style: none;
            padding: 13px 0;
            border-bottom: 1px solid #ddd;
            background: #eee;
        }

        .pricing-feature li:last-child {
            border-bottom: none;
        }

        .pricing-feature li span {
            text-transform: capitalize;
            font-weight: bold;
        }

        .pricing-feature li span,
        .pricing-feature li i {
            float: right;
        }

        .pricing-feature li p {
            margin: 0;
            font-size: 13.5px;
            text-transform: capitalize;
            padding: 0 20px;
            line-height: normal;
        }

        /* ============================ */
        /*   Pricing Footer             */
        /* ============================ */
        .pricing-footer {
            border-top: 1px solid #ddd;
            padding: 0 20px;
            height: 80px;
            -webkit-transition: height 0.2s linear 0s;
            transition: height 0.2s linear 0s;
            text-align: center;
        }

        .pricing-footer a {
            bottom: 0;
            margin-top: 20px;
            position: relative;
        }

        /* =================== */
        /*   Pricing Ribbons     */
        /* =================== */
        .ribbon {
            background: #222;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.23), 0 1px 5px rgba(0, 0, 0, 0.16);
            height: 35px;
            line-height: 35px;
            margin: 0 auto;
            position: absolute;
            left: calc(100% - 110px);
            top: 70px;
            width: 35px;
            z-index: 10;
            -webkit-transition: all 0.2s linear;
            transition: all 0.2s linear;
            border-radius: 100px;
            float: right;
            cursor: pointer;
        }

        .ribbon:hover {
            width: 90px;
        }

        .ribbon:hover span {
            visibility: visible;
            -webkit-transition: all 0.2s linear 0.18s;
            transition: all 0.2s linear 0.18s;
        }

        .ribbon span {
            top: 0;
            color: #ffffff;
            font-size: 13px;
            font-weight: 500;
            float: left;
            visibility: hidden;
        }

        .ribbon i {
            color: #FFC61B;
            font-weight: 800;
            font-size: 14px;
            margin: 11px;
            z-index: 10;
            float: left;
        }

        /* ======================= */
        /*   Pricing Comparison    */
        /* ======================= */
        .comparison-table [class*=col-] {
            padding: 0;
            margin: -1px;
        }

        .comparison-table .pricing-col {
            padding: 20px 0;
        }

        .comparison-table.current {
            padding-left: 0;
            padding-right: 0;
        }

        .comparison-table [class*=col-]:first-child {
            margin: 0;
        }

        .comparison-table [class*=col-]:first-child:hover {
            top: 0;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .comparison-table [class*=col-]:first-child:hover .pricing-card {
            margin: 0;
            box-shadow: none;
        }

        .comparison-table .pricing-feature li {
            background: #fff;
            text-align: center;
        }

        .comparison-table .pricing-feature li i {
            line-height: normal;
        }

        .comparison-table .pricing-feature li span,
        .comparison-table .pricing-feature li i {
            float: none;
        }

        .comparison-table .pricing-feature li:nth-child(2n+1) {
            background: #eee;
        }

        .comparison-table .ribbon {
            left: calc(100% - 124px);
        }

        .list-feature {
            z-index: 0;
        }

        .list-feature .pricing-header {
            background: #fff !important;
            padding: 30px 15px;
            border-bottom-color: #ddd !important;
            text-align: center;
            margin-top: -2px;
        }

        .list-feature .pricing-header h5 {
            background: #fff !important;
            color: #424242;
            font-weight: 500;
            font-size: 39px;
            padding: 0;
            margin-top: 30px;
        }

        .list-feature .pricing-header p {
            color: #444;
            margin-top: 5px;
        }

        .list-feature .pricing-feature {
            text-align: left;
        }

        .pricing-col {
            -webkit-transition: all 0.2s linear 0s;
            transition: all 0.2s linear 0s;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .pricing-col:hover {
            padding-bottom: 0;
            padding-top: 0;
        }

        .pricing-col:hover>.pricing-card {
            top: 0px;
            box-shadow: 0 0 20px -2px rgba(0, 0, 0, 0.25);
            -webkit-transition: all 0.2s linear 0s;
            transition: all 0.2s linear 0s;
            z-index: 0;
        }

        .pricing-col:hover>.pricing-card .pricing-footer {
            height: 120px;
        }

        .pricing-col:hover>.pricing-card .pricing-footer a {
            bottom: -20px;
            position: relative;
        }

        .pricing-col.current {
            padding-top: 0;
            padding-bottom: 0;
            margin-left: 0;
            margin-right: 0;
        }

        .current {
            top: 0;
        }

        .current .pricing-card {
            z-index: 0;
            -webkit-transition: all 0.2s linear 0s;
            transition: all 0.2s linear 0s;
            box-shadow: 0 0 20px -2px rgba(0, 0, 0, 0.25);
        }

        .current .pricing-footer {
            height: 120px;
        }

        .current .pricing-footer a {
            bottom: -20px;
            position: relative;
        }

        .current:hover {
            -webkit-transition: all 0.2s linear 0s;
            transition: all 0.2s linear 0s;
            top: -20px;
        }

        .current:hover .pricing-footer {
            height: 120px;
        }

        .current:hover .pricing-footer a {
            bottom: -20px;
            position: relative;
        }

        /* ======================== */
        /*   Tooltips               */
        /* ======================== */

        /* ======================== */
        /*   Button Color       */
        /* ======================== */

        /*=====================================================================
        MENU
    ======================================================================*/


        /*=====================================================================
        MOBILE MENU
    ======================================================================*/

        .style-3 .pricing-card {
            border: 1px solid #ddd;
        }

        .style-3 .pricing-header {
            border-bottom: none;
            margin: 0;
            height: 160px;
        }

        .style-3 .pricing-header h5 {
            background: #fff;
            color: #424242;
            border-bottom: 1px solid #ddd;
        }

        .style-3 .price-box {
            border-radius: 0;
            width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
        }

        .style-3 .price {
            color: #616161;
            font-size: 50px;
            font-weight: 400;
            line-height: normal;
        }

        .style-3 .currency {
            top: 12px;
            font-size: 22px;
            left: -16px;
            font-weight: 300;
        }

        .style-3 .plan {
            bottom: 12px;
            left: auto;
            margin: 0;
            left: calc(100% + 5px);
            font-weight: 400;
            font-size: 12px;
            width: 50px;
            text-align: left;
            line-height: normal;
        }

        .style-3 .pricing-feature li {
            background: #f5f5f5;
        }

        .style-3 .pricing-feature li:first-child {
            border-top: 1px solid #ddd;
        }

        .style-3 .pricing-feature li span,
        .style-3 .pricing-feature li i {
            float: right;
        }

        .style-3 .pricing-feature li:nth-child(2n+1) {
            background: #eee;
        }

        .style-3 .ribbon {
            top: 35%;
            right: -10px;
            left: auto;
            border-radius: 0;
        }

        .style-3 .ribbon:after {
            border-right: 10px solid transparent;
            border-top: 7px solid rgba(0, 0, 0, 0.5);
            bottom: -7px;
            content: "";
            position: absolute;
            right: 0;
        }

        .style-3 .person .price {
            color: #2196f3;
        }

        .style-3 .person .plan {
            color: #757575;
        }

        .style-3 .corp .price {
            color: #4caf50;
        }

        .style-3 .corp .plan {
            color: #757575;
        }

        .style-3 .unlim .price {
            color: #f44336;
        }

        .style-3 .unlim .plan {
            color: #757575;
        }

        .style-3 .business .price {
            color: #ffc107;
        }

        .style-3 .business .plan {
            color: #757575;
        }

        .style-3 .pricing-footer {
            background: #fff;
        }

        .style-3.comparison-table [class*=col-]:first-child {
            margin: -1px;
        }

        .style-3.comparison-table .list-feature .pricing-header h5 {
            margin-top: 0;
            border-bottom: none;
        }

        .style-3.comparison-table .pricing-feature li p,
        .style-3.comparison-table .pricing-feature li i,
        .style-3.comparison-table .pricing-feature li span {
            float: none;
        }

        .style-3 .pricing-footer .btn-act.btn-line {
            background: none;
            border-color: #2196f3;
            color: #2196f3;
        }

        .style-3 .pricing-footer .btn-act.btn-line:hover {
            background: #2196f3;
            border-color: #2196f3;
            color: #fff;
        }

        .style-3 .pricing-footer .btn-act.btn-bg {
            background-color: #2196f3;
            border-color: #2196f3;
        }

        .style-3 .pricing-footer .btn-act.btn-bg:hover {
            background: #212121;
            border-color: #212121;
        }

        section.footer {
            background: #F5F5F5;
            text-align: center;
            font-size: 20px;
        }

        /*CSS for FAQ Accordion*/

        :root {
            --black: #4d5974;
            --red: #E07A5F;
            --grey: #e5e5e5;
        }

        /*body{
      font-family: sans-serif;
      color:var(--black);
      font-size: 18px;
    }*/
        .menu {
            margin: 0 auto;
            padding: 40px;

        }

        label {
            display: inline-block;
            margin: 0 0 4px 0;
            padding: 15px 15px 15px 0;
            line-height: 1;
            cursor: pointer;
            border-bottom: 1px solid var(--grey);
        }

        .question {
            position: relative;
        }

        input {
            /* display: none; */
        }

        .menu ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .menu li {
            height: 0;
            overflow: hidden;
            transition: all 0.5s;
            font-size: 16px;
        }

        #icon span {
            position: absolute;
            top: 11px;
            right: 11px;
            display: inline-block;
            width: 22px;
            height: 22px;
            border: 1px solid var(--black);
            border-radius: 22px;
        }

        #icon span::before {
            display: block;
            position: absolute;
            content: '';
            top: 9px;
            left: 5px;
            width: 10px;
            height: 2px;
            background: var(--black);
        }

        #icon span::after {
            display: block;
            position: absolute;
            content: '';
            top: 5px;
            left: 9px;
            width: 2px;
            height: 10px;
            background: var(--black);
        }

        /*Open tab*/

        #type1:checked~#links1 li,
        #type2:checked~#links2 li,
        #type3:checked~#links3 li {
            height: 65px;
            opacity: 1;
            padding-top: 15px;
        }

        /*Style open tab*/
    </style>
    <!-- <script>
         new header script
        $(document).ready(function() {
            var sticky = $('.sticky');
            var stickyOffset = sticky.offset().top;

            $(window).scroll(function() {
                var scroll = $(window).scrollTop();

                if (scroll >= stickyOffset) {
                    sticky.addClass('fixed');
                    $('.container-fluid').css('margin-top', '140px');
                    sticky.css('transition', 'all 2s linear');
                } else {
                    sticky.removeClass('fixed');
                    $('.container-fluid').css('margin-top', '0px');
                    sticky.css('transition', 'all 2s linear');
                }
            });
        });
    </script> -->
</body>
<!-- <script>
    var stickyOffset = $('.sticky').offset().top;

    $(window).scroll(function() {

        var sticky = $('.sticky'),
            scroll = $(window).scrollTop();

        if (scroll >= stickyOffset) {
            sticky.addClass('fixed');

            $('.container-fluid ').css('margin-top', '0pz !important')
        } else {
            sticky.removeClass('fixed')
            $('.container-fluid ').css('margin-top', '0px')
        }
    });
</script> -->

</html>
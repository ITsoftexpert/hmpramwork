<?php

session_start();

require_once("includes/db.php");

if (!isset($_SESSION['seller_user_name'])) {

    echo "<script>window.open('login','_self')</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_referral = $row_login_seller->seller_referral;

$referral_money = $row_general_settings->referral_money;

?>
<!DOCTYPE html>

<html lang="en" class="ui-toolkit">

<head>

    <title><?= $site_name; ?> - <?= $lang["titles"]["my_referrals"]; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $site_desc; ?>">
    <meta name="keywords" content="<?= $site_keywords; ?>">
    <meta name="author" content="<?= $site_author; ?>">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">

    <link href="styles/bootstrap.css" rel="stylesheet">
    <link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
    <link href="styles/styles.css" rel="stylesheet">
    <link href="styles/user_nav_styles.css" rel="stylesheet">
    <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery.min.js"></script>

    <?php if (!empty($site_favicon)) { ?>

        <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">

    <?php } ?>

    <style>
        .alter-margin-top {
            /* margin-top: -150px; */
        }

        .border {
            border: 0px solid #e9ecef !important;
        }

        .rounded {
            border-radius: 0px !important;
        }

        .border3 {
            /* border:1px solid red !important; */
            /* box-shadow: 0px 0px 5px black, inset 0px 0px 25px black; */
        }

        .font-size-16 {
            /* font-size: 17px !important; */
            line-height: 1.5;
            text-align: center;
            /* box-shadow: 0px 0px 5px black, inset 0px 0px 15px gray; */
            border: 1px solid lightgray !important;
        }

        .font-size-16 mark {
            background-color: #f5e3e1;
        }

        .border-4 {
            /* border:2px solid green !important; */
            box-shadow: 0px 0px 5px gray;
        }

        .font-size-3 {
            font-size: 13px;
            text-align: center;
            padding: 10px !important;
        }

        .bg-color {
            background-color: #f5c6cb;
        }

        .box-shadow-border1 {
            /* box-shadow: 0px 0px 5px black, inset 0px 0px 45px #00c8d4; */
        }

        .box-shadow-border4 {
            /* box-shadow: 0px 0px 5px black, inset 0px 0px 15px #00c8d4; */
        }

        .box-shadow-bordered {
            /* box-shadow: 0px 0px 2px black, inset 0px 0px 15px lightcoral; */
        }

        @media (max-width:768px) {
            .font-size-16 {
                font-size: 17px !important;
                line-height: 1.5;
                text-align: center;
            }

            /* . {
                font-size: 11px !important;
                padding: 8px !important;
                text-align: center;
            } */

            .heading_3 {
                font-size: 20px;
                width: 100%;
            }

            .display-4 {
                font-size: 2.5rem !important;
            }

            .center-align {
                text-align: center;
                margin-bottom: 2vh;
            }



            .bg-color {
                background-color: #f5c6cb;
            }
        }

        @media(min-width:1024px){
            .box-shadow-border1 {
            margin-top: 20px;
        }
        }
    </style>

</head>

<body class="is-responsive">

    <?php require_once("includes/user_header.php"); ?>

    <div class="container-fluid">

        <div class="row justify-content-center alter-margin-top">

            <div class="col-lg-9 col-md-10 mt-3 mb-5 border1">

                <div class="card rounded-0 box-shadow-border1">

                    <div class="card-body m-1 ">

                        <h1 class="center-align border2"> <?= $lang["titles"]["my_referrals"]; ?> </h1>

                        <p class="lead mb-4">

                            <?php

                            $tr = $lang['my_referrals']['desc'];
                            $tr = str_replace("{s_currency}", $s_currency, $tr);
                            $tr = str_replace("{referral_money}", $referral_money, $tr);

                            echo $tr;

                            ?>

                        </p>

                        <h4 class="border border-primary rounded p-3 font-size-16">

                            <?= $lang['my_referrals']['link']; ?>
                            <mark> <?= $site_url; ?>?referral=<?= $login_seller_referral; ?> </mark>

                        </h4>

                        <p class="lead text-danger mb-5"><?= $lang['my_referrals']['note']; ?></p>

                        <div class="row mb-5">

                            <div class="col-md-4 mb-3">

                                <div class="card text-white border-success">

                                    <div class="card-header text-center bg-success border3">

                                        <div class="display-4"> <?php

                                                                $select = $db->query("SELECT SUM(comission) AS total FROM referrals where seller_id='$login_seller_id' AND status='approved'");
                                                                $total = $select->fetch()->total;
                                                                $total = $total > 0 || $total !== null ? $total : "0";

                                                                echo showPrice($total);

                                                                ?>

                                        </div>

                                        <div class="font-weight-bold"><?= $lang['referrals']['approved']; ?></div>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-4 mb-3">

                                <div class="card text-white border-secondary">

                                    <div class="card-header text-center bg-secondary border3">

                                        <div class="display-4"> <?php

                                                                $select = $db->query("SELECT SUM(comission) AS total FROM referrals where seller_id='$login_seller_id' AND status='pending'");
                                                                $total = $select->fetch()->total;
                                                                $total = $total > 0 || $total !== null ? $total : "0";

                                                                echo showPrice($total);

                                                                ?>

                                        </div>

                                        <div class="font-weight-bold"><?= $lang['referrals']['pending']; ?></div>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-4 mb-3">

                                <div class="card text-white border-danger">

                                    <div class="card-header text-center bg-danger border3">

                                        <div class="display-4"> <?php

                                                                $select = $db->query("SELECT SUM(comission) AS total FROM referrals where seller_id='$login_seller_id' AND status='declined'");
                                                                $total = $select->fetch()->total;
                                                                $total = $total > 0 || $total !== null ? $total : "0";

                                                                echo showPrice($total);

                                                                ?>

                                        </div>

                                        <div class="font-weight-bold"><?= $lang['referrals']['declined']; ?></div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="table-responsive border border-secondary rounded border-4">

                            <table class="table table-bordered mb-0">

                                <thead>

                                    <tr class="card-header box-shadow-border4">

                                        <th class="font-size-3"><?= $lang['th']['username']; ?></th>
                                        <th class="font-size-3"><?= $lang['th']['signup_date']; ?></th>
                                        <th class="font-size-3"><?= $lang['th']['your_commission']; ?></th>
                                        <th class="font-size-3"><?= $lang['th']['status']; ?></th>

                                    </tr>

                                </thead>

                                <tbody class="box-shadow-bordered">

                                    <?php

                                    $sel_referrals = $db->select("referrals", array("seller_id" => $login_seller_id), "DESC");
                                    $count_referrals = $sel_referrals->rowCount();

                                    if ($count_referrals == 0) {
                                        echo "
                        <tr>
                           <td class='text-center bg-color m-4 box-shadow-bordered' colspan='4'>
                              <h3 class='pb-2 pt-2 heading_3'>
                                 <i class='fa fa-meh-o'></i> {$lang['my_referrals']['no_referrals']}
                              </h3>
                           </td>
                        </tr>";
                                    } else {

                                        while ($row_referrals = $sel_referrals->fetch()) {

                                            $referred_id = $row_referrals->referred_id;
                                            $comission = $row_referrals->comission;
                                            $date = $row_referrals->date;
                                            $status = $row_referrals->status;

                                            $select_seller = $db->select("sellers", array("seller_id" => $referred_id));
                                            $row_seller = $select_seller->fetch();
                                            $seller_user_name = $row_seller->seller_user_name;

                                    ?>

                                            <tr>

                                                <td><?= $seller_user_name; ?></td>
                                                <td><?= $date; ?></td>
                                                <td><?= $s_currency; ?><?= $comission; ?></td>

                                                <td class="font-weight-bold
                   <?php

                                            if ($status == "approved") {
                                                echo "text-success";
                                            } elseif ($status == "pending") {
                                                echo "text-secondary";
                                            } elseif ($status == "declined") {
                                                echo "text-danger";
                                            }

                    ?>"> <?= $status; ?>

                                                </td>

                                            </tr>

                                    <?php }
                                    } ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php require_once("includes/footer.php"); ?>

</body>

</html>
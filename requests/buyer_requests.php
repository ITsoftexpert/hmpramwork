<?php
session_start();
require_once("../includes/db.php");

if (!isset($_SESSION['seller_user_name'])) {
  echo "<script>window.open('../login','_self')</script>";
} 

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_offers = $row_login_seller->seller_offers;
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
  <title><?= $site_name; ?> - Recent Buyer Requests.</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?= $site_desc; ?>">
  <meta name="keywords" content="<?= $site_keywords; ?>">
  <meta name="author" content="<?= $site_author; ?>">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
  <link href="../styles/bootstrap.css" rel="stylesheet">
  <link href="../styles/custom.css" rel="stylesheet">
  <!-- Custom css code from modified in admin panel --->
  <link href="../styles/styles.css" rel="stylesheet">
  <link href="../styles/user_nav_styles.css" rel="stylesheet">
  <link href="../font_awesome/css/font-awesome.css" rel="stylesheet">
  <link href="../styles/sweat_alert.css" rel="stylesheet">
  <script type="text/javascript" src="../js/sweat_alert.js"></script>
  <script type="text/javascript" src="../js/jquery.min.js"></script>
  <?php if (!empty($site_favicon)) { ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
  <?php } ?>
  <style>
    .box-shadow-sbr {
      /* box-shadow:0px 0px 5px black, inset 0px 0px 15px lightgray; */
      height: 45px;
    }

    .box-shadow-sbrb {
      /* box-shadow:0px 0px 5px black, inset 0px 0px 15px #00c8d4; */
      height: 45px;
      padding:0.375rem 1.25rem;
    }

    .padding-alter9 {
      /* margin-top: -120px; */
      /* border:2px solid green; */
      padding: 2rem;
    }
    .margin-top-6 {
        margin-top: 1rem;
        /* border:2px solid green; */
        padding-left: 0px !important;
        padding-right: 0px !important;
      }

    @media (max-width:768px) {
      .padding-alter9 {
        /* margin-top: -130px; */
        /* border:2px solid green; */
        padding: 0px 10px;
      }

      .width-100 {
        width: 100%;
        display: flex;
        margin-top: -5px !important;
        margin-bottom: 16px !important;
        /* border:1px solid yellow; */
      }



      .margin-top-5 {
        /* background-color: green; */
        padding-top: 2px;
      }

      .margin-top-6 {
        margin-top: 1rem;
        /* border:2px solid green; */
        padding-left: 0px !important;
        padding-right: 0px !important;
      }

      .heading-3 {
        /* background-color: green; */
        font-size: 16px !important;
        /* border: 1px solid green;         */
        width: 40%;
        padding-top: 5px;
        /* margin: 50% !important; */

      }

     

      .font-size-th {
        padding: 1px !important;
        /* font-size: 1px !important; */
        /* font-weight: 300; */
        /* background-color: green; */
      }

      .font-size {
        font-size: 13px !important;
        /* margin: 10px !important; */
        /* border: 1px solid green; */
        width: 40% !important;
        /* background-color: green !important; */
      }

      .heading_3 {
        font-size: 20px;
        width: 100%;
      }
    }
    @media (max-width:640px) {
      .padding-alter9 {
        /* margin-top: -130px; */
        /* border:2px solid green; */
        padding: 0px 0px;
      }
    }
  </style>
</head>

<body class="is-responsive">
  <?php require_once("../includes/user_header.php"); ?>
  <div class="container-fluid">
    <div class="row buyer-requests padding-alter9">
      <div class="col-md-12">
        <!--        <h1 class="col-md-9 float-left">-->
        <!-- <? ////= $lang["titles"]["buyer_requests"];
              ?>
       <h1>-->
        <div class="col-md-3 float-right margin-top-6">
          <div class="input-group">
            <input type="text" id="search-input" placeholder="Search Buyer Requests" class="form-control box-shadow-sbr">
            <span class="input-group-btn">
              <button class="btn btn-success box-shadow-sbrb" id="req-search"> <i class="fa fa-search"></i> </button>
            </span>
          </div>
        </div>
      </div>
      <div class="col-md-12 mt-4">
        <?php
        if (isset($_SESSION['seller_user_name'])) {
        ?>
          <h5 class="text-right mr-3">
            <i class="fa fa-list-alt margin-top-5"></i> &nbsp;<?= $login_seller_offers; ?> Bids Left This Month
          </h5>
        <?php } ?>
        <div class="clearfix"></div>
        <?php include('user_buyer_requests.php'); ?>
      </div>
    </div>
    <div class="append-modal"></div>
    <div id="quota-finish" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title h5"><i class="fa fa-frown-o fa-move-up"></i> Request Quota Reached</h5>
            <button class="close" data-dismiss="modal"> &times; </button>
          </div>
          <div class="modal-body">
            <center>
              <h5>You can only send a max of 10 offers per day. Today you've maxed out. Try again tomorrow. </h5>
            </center>
          </div>
          <div class="modal-footer">
            <button class="btn btn-success" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php require_once("../includes/footer.php"); ?>
</body>

</html>
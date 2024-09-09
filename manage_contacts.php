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
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
   <title><?= $site_name; ?> - <?= $lang["titles"]["manage_contacts"]; ?>.</title>
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
      .nav-item-width {
         margin: auto;
         /* border:1px solid green; */
         width: 50%;
         text-align: center;
      }

      .padding-11 {
         padding: 9px 15px;
         /* width: 200px; */
         text-align: center;
         /* box-shadow: 0px 0px 5px black, inset 0px 0px 15px #00c8d4; */
      }

      .badge-float-right {
         float: right;
         margin-top: -3px;
         padding-top: 5px;
         margin-right: -9px !important;
      }

      .padding-alter5 {
            /* margin-top: -115px; */
            padding: 1.5rem 1.5rem;
         }
      @media (max-width:767px) {
         .full-width {
            /* border: 1px solid blue; */
            width: 100%;
         }

         .padding-alter5 {
            /* margin-top: -140px; */
            padding: 0;
         }

         .nav-item-width {
            margin: auto;
            /* border:1px solid green; */
            width: 50%;
            text-align: center;
         }

         .badge-float-right {
            float: right;
            margin-top: -3px;
            padding-top: 5px;
            margin-right: -9px !important;
         }

         .heading_4 {
            font-size: 16px;
         }

         .text-align-center {
            text-align: center;
            margin: auto;
         }

         .font-size-3 {
            font-size: 13px !important;
            padding: 10px !important;
            text-align: center;
         }

         .heading_3 {
            font-size: 20px;
            width: 100%;
         }

         .heading-4 {
            /* border:1px solid green; */
            font-size: 18px !important;
            text-align: center;
         }

         .heading-3 {
            /* background-color: green; */
            font-size: 20px;
         }

         .full-width-h {
            /* border-bottom: 1px solid lightgray; */
            width: 100%;
            display: flex;
            margin-top: 13px !important;
         }

         .text-align-center-s {
            text-align: center;
            margin: auto;
            /* border:1px solid green; */
         }

         .top-margin {
            margin-top: 25px !important;
         }

      }
   </style>
</head>

<body class="is-responsive">
   <?php require_once("includes/user_header.php"); ?>
   <div class="container-fluid">
      <div class="row padding-alter5">
         <div class="col-md-12 mt-1">
            <h1 class="full-width-h"><span class="text-align-center-s"><?= $lang["titles"]["manage_contacts"]; ?></span></h1>
            <ul class="nav nav-tabs mt-4 mb-4 full-width top-margin"><!-- nav nav-tabs mt-5 mb-3 Starts -->
               <?php
               $count_my_buyers = $db->count("my_buyers", array("seller_id" => $login_seller_id));
               ?>
               <li class="nav-item nav-item-width ">
                  <a href="#my_buyers" data-toggle="tab" class="nav-link make-black padding-11 
                  <?php
                  if (!isset($_GET['my_buyers']) and !isset($_GET['my_sellers'])) {
                     echo "active";
                  }
                  if (isset($_GET['my_buyers'])) {
                     echo "active";
                  }
                  ?>">
                     <?= $lang['tabs']['my_buyers']; ?> <span class="badge badge-success badge-float-right"><?= $count_my_buyers; ?></span>
                  </a>
               </li>
               <?php
               $count_my_sellers = $db->count("my_buyers", array("buyer_id" => $login_seller_id));
               ?>
               <li class="nav-item nav-item-width">
                  <a href="#my_sellers" data-toggle="tab" class="nav-link make-black padding-11
            <?php
            if (isset($_GET['my_sellers'])) {
               echo "active";
            }
            ?>
            ">
                     <?= $lang['tabs']['my_sellers']; ?> <span class="badge badge-success badge-float-right"><?= $count_my_sellers; ?></span>
                  </a>
               </li>
            </ul>
            <div class="tab-content mt-2">
               <div id="my_buyers" class="tab-pane fade 
      <?php
      if (!isset($_GET['my_buyers']) and !isset($_GET['my_sellers'])) {
         echo "show active";
      }
      if (isset($_GET['my_buyers'])) {
         echo "show active";
      }
      ?>
      ">
                  <?php include('seller_contacts.php'); ?>
               </div>
               <div id="my_sellers" class="tab-pane fade
   <?php
   if (isset($_GET['my_sellers'])) {
      echo "show active";
   }
   ?>
   ">
                  <?php include('buyer_contacts.php'); ?>
               </div>
            </div>
         </div>
      </div>
   </div>
   <?php require_once("includes/footer.php"); ?>
</body>

</html>
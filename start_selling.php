<?php

session_start();
require_once("includes/db.php");
require_once("social-config.php");

?>
<!DOCTYPE html>

<html lang="en" class="ui-toolkit">

<head>

   <title> <?= $site_name; ?> - <?= $lang["titles"]["start_selling"]; ?> </title>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="<?= $site_desc; ?>">
   <meta name="keywords" content="<?= $site_keywords; ?>">
   <meta name="author" content="<?= $site_author; ?>">

   <link href="styles/bootstrap.css" rel="stylesheet">

   <link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->

   <link href="styles/styles.css" rel="stylesheet">

   <link href="styles/categories_nav_styles.css" rel="stylesheet">

   <link href="font_awesome/css/font-awesome.css" rel="stylesheet">

   <link href="styles/owl.carousel.css" rel="stylesheet">

   <link href="styles/owl.theme.default.css" rel="stylesheet">

   <link href="styles/sweat_alert.css" rel="stylesheet">

   <link href="styles/animate.css" rel="stylesheet">

   <script type="text/javascript" src="js/sweat_alert.js"></script>
   <script type="text/javascript" src="js/jquery.min.js"></script>

   <?php if (!empty($site_favicon)) { ?>
      <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
   <?php } ?>

   <style>
      .swal2-popup .swal2-styled.swal2-confirm {
         background-color: #28a745;
      }

      @media (max-width:768px) {
         .border-green {
            border: 1px solid whitesmoke;
            background-color: #f2faf8;
         }

         .border-red {
            /* border: 1px solid red; */
         }

         #start_selling_body .row-1 h3 {
            left: 0px;
            text-align: center;
            color: #00cedc;
            padding: 12px 0px 10px 0px !important;
         }


         .margin_padding_none {
            margin-top: 0px !important;
            margin-bottom: 0px !important;
            margin-left: 0px !important;
            margin-right: 0px !important;
            padding: 0px !important;
            display: none;
         }

         .align-center {
            text-align: left;
            /* margin: auto; */
         }

         #start_selling_body .row-1 img {
            position: relative;
            left: 0px;
            padding: 15px 23.5% 15px 23.5%;
            margin: auto;
            margin-top: 2rem;
            margin-bottom: 1rem;
            display: flex;
            background-color: #f2faf8;
            /* border: 1px solid lightgray; */
         }

         .font-size-increase {
            /* border:1px solid blue; */
            font-size: 16px;
            color: gray;
            text-align: justify;
            padding: 0px 20px;
         }

         hr {
            display: none;
         }

         #start_selling_body .row-2 img {
            position: relative;
            left: 0px;
            margin: auto;
            margin-top: 2rem;
            margin-bottom: 1rem;
            display: flex;
            padding: 15px 23.5% 15px 23.5%;
            /* border: 1px solid lightgray; */
            background-color: #f2faf8;
            /* box-shadow: 1px 1px 20px 1px black, inset 1px 1px 20px 1px gray; */
         }

         #start_selling_body .row-2 h3 {
            text-align: center;
            color: #00cedc;
            left: 0px;
            padding: 12px 0px 10px 0px !important;
         }

         .width-80plus {
            width: 82.5%;
            /* height:6vh; */
            text-align: center;
            margin: auto;
            padding:12px;
            font-size: 16px !important;
            box-shadow: 1px 1px 5px black;
         }
         .margin-top-decrease{
            margin-top: -80px;
         }
      }
      .margin-top-alter-nest{margin-top:151px;}
    @media(min-width:768px){
      .row {
      margin-left: 0px !important;
      margin-right: 0px !important;
    }
    .font-size-increase {          
            font-size: 16px;
            color: gray;
            text-align: justify;
            /* padding: 0px 20px; */
         }
         .align-center {
            text-align: justify;
            /* margin: auto; */
         }
    }
   </style>

</head>

<body class="is-responsive">

   <?php require_once("includes/header.php"); ?>

   <header id="start_selling" class="margin-top-alter-nest">

      <h2 class="text-center text-white"><?= $lang['start_selling']['title']; ?></h2>
      <h3 class="text-center text-white"><?= $lang['start_selling']['desc']; ?></h3>

      <?php if (isset($_SESSION['seller_user_name'])) { ?>

         <div class="text-center">
            <a href="proposals/create_proposal" class="btn btn-success btn-lg btn_start_selling px-5 py-3">
               <i class="fa fa-pencil-square-o"></i> <?= $lang["start_selling"]['create_proposal']; ?>
            </a>
         </div>

      <?php } ?>

      <?php if (!isset($_SESSION['seller_user_name'])) { ?>

         <div class="text-center">
            <button data-toggle="modal" data-target="#register-modal" class="btn btn-success btn-lg btn_start_selling">
               <i class="fa fa-user-plus"></i> <?= $lang["start_selling"]['create_account']; ?>
            </button>
         </div>

      <?php } ?>

   </header>
   <br><br>

   <section id="start_selling_body">

      <div class="container">

         <h2 class="text-center pb-5 pt-5 border-green"><?= $lang["start_selling"]['title2']; ?></h2>

         <div class="row row-1">

            <div class="col-md-4 border-red">

               <img src="images/comp/create-icon.png" class="align-center">
               <h3 class="pb-4"><?= $lang['start_selling']['column_1']['title']; ?></h3>
               <p class="font-size-increase"><?= $lang['start_selling']['column_1']['desc']; ?></p>

            </div>

            <div class="col-md-4 border-red">

               <img src="images/comp/approve-icon.png" class="align-center">
               <h3 class="pb-4"><?= $lang['start_selling']['column_2']['title']; ?></h3>
               <p class="font-size-increase"><?= $lang['start_selling']['column_2']['desc']; ?></p>

            </div>

            <div class="col-md-4 border-red">

               <img src="images/comp/receive-icon.png" class="align-center">
               <h3 class="pb-4"><?= $lang['start_selling']['column_3']['title']; ?></h3>
               <p class="font-size-increase"><?= $lang['start_selling']['column_3']['desc']; ?></p>

            </div>

         </div>

         <br /><br />
         <hr><br /><br />

         <span class="margin_padding_none" style="padding: 200px; margin:200px;"></span>

         <div class="row row-2 margin-top-decrease">

            <div class="col-md-4 border-red">

               <img src="images/comp/delivered-icon.png" class="align-center">
               <h3 class="pb-4"><?= $lang['start_selling']['column_4']['title']; ?></h3>
               <p class="font-size-increase"><?= $lang['start_selling']['column_4']['desc']; ?></p>

            </div>

            <div class="col-md-4 border-red">

               <img src="images/comp/rate-icon.png" class="align-center">
               <h3 class="pb-4"><?= $lang['start_selling']['column_5']['title']; ?></h3>
               <p class="font-size-increase"><?= $lang['start_selling']['column_5']['desc']; ?></p>

            </div>

            <div class="col-md-4 border-red">

               <img src="images/comp/earn-icon.png" class="align-center">

               <h3 class="pb-4"><?= $lang['start_selling']['column_6']['title']; ?></h3>
               <p class="font-size-increase"><?= $lang['start_selling']['column_6']['desc']; ?></p>

            </div>

         </div>

      </div>

      <br />
      <br />


   </section>

   <?php if (isset($_SESSION['seller_user_name'])) { ?>

      <div class="text-center ">
         <a href="proposals/create_proposal" class="btn btn-success btn-lg btn_start_selling width-80plus px-5 py-3">
            <i class="fa fa-pencil-square-o"></i> Create A Proposal
         </a>
      </div>

   <?php } ?>

   <?php if (!isset($_SESSION['seller_user_name'])) { ?>

      <div class="text-center">
         <button data-toggle="modal" data-target="#register-modal" class="btn btn-success btn-lg btn_start_selling px-5 py-3">
            <i class="fa fa-user-plus"></i> Create An Account
         </button>
      </div>

   <?php } ?>

   <div class="pb-5"></div><br><br>

   <?php require_once("includes/footer.php"); ?>

</body>

</html>
<?php
session_start();
require_once("includes/db.php");
require_once("functions/functions.php");

// if (isset($_SESSION['seller_user_name'])) {
//   echo "<script>window.open('{$site_url}/login','_self')</script>";
// }

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
  <title> <?= $site_name; ?> - <?= $lang['freelancers']['title']; ?> </title>
  <meta name="description" content="">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="<?= $site_author; ?>">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
  <link href="styles/bootstrap.css" rel="stylesheet">
  <link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
  <link href="styles/styles.css" rel="stylesheet">
  <link href="styles/freelancers.css" rel="stylesheet">
  <link href="styles/categories_nav_styles.css" rel="stylesheet">
  <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
  <link href="styles/sweat_alert.css" rel="stylesheet">
  <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
  <script src="js/ie.js"></script>
  <script type="text/javascript" src="js/sweat_alert.js"></script>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <?php if (!empty($site_favicon)) { ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
  <?php } ?>

  <style>
    .body-background-none{
      /* background-color: white !important; */
      /* padding-top: 50px; */
      margin-top:-5px !important;
    }
    @media(min-width:1200px) {
      /* .col-xl-10 {
        -ms-flex: 0 0 83.333333%;
        flex: 0 0 97.333333%;
        max-width: 97.333333%;
      } */
    }

    @media(max-width:1024px) and (min-width:769px) {
      .margin-top {
        margin-top: 2rem;
        margin-bottom: -1rem;
      }
      .padding-alter10a{
        /* border:2px solid green; */
        padding:0px 13px;
      }
    }


    @media(max-width:768px) {
      .container-fluid {
        /* margin-top: 4px !important; */
      }
      .padding-alter10a{
        /* border:2px solid green; */
        padding:0px 1px;
      }

      .margin-top {
        margin-top: 2rem;
        margin-bottom: -1rem;
      }

      .font-size-13px {
        font-size: 16px;
        font-weight: 300;
        color: gray;
      }
    }

    @media(min-width:640px) and (max-width:768px){
      .padding-alter10a{
        /* border:2px solid green; */
        padding:0px 13px;
      }
    }
  </style>
</head>

<body class="is-responsive">
  <?php require_once("includes/header.php"); ?>

  <div class="container-fluid body-background-none">
    <!-- Container start -->
    <div class="row padding-alter10a">
      <div class="col-md-12 margin-top">
        <center>
          <h1> <?= $lang['freelancers']['title']; ?> </h1>
          <p class="lead font-size-13px"><?= $lang['freelancers']['desc']; ?></p>
        </center>
        <hr class="mt-4 pt-2">
      </div>
    </div>
    <div class="row mt-3 justify-content-center padding-alter10a">
      <!-- <div class="col-lg-10 col-md-12"> -->
      <div class="col-xl-10 col-lg-12 col-md-12">
        <div class="row">
          <div class="col-lg-3 col-md-4 col-sm-12 <?= ($lang_dir == "right" ? 'order-2 order-sm-1' : '') ?>">
            <?php require_once("includes/freelancer_sidebar.php"); ?>
          </div>
          <div class="col-lg-9 col-md-8 col-sm-12 <?= ($lang_dir == "right" ? 'order-1 order-sm-2' : '') ?>">
            <div class="row flex-wrap" id="freelancers">
              <!-- Here Freelancers Gona Show -->
              <?php get_freelancers(); ?>
            </div>
            <div id="wait"></div>
            <br>
            <div class="row justify-content-center mb-5 mt-0">
              <!-- row justify-content-center Starts -->
              <nav>
                <!-- nav Starts -->
                <ul class="pagination" id="freelancer_pagination">
                  <!-- Here Pagination Gona Show -->
                  <?php get_freelancer_pagination(); ?>
                </ul>
              </nav><!-- nav Ends -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!-- Container ends -->
  <?php require_once("includes/footer.php"); ?>
  <script>
    function get_freelancers() {
      var sPath = '';
      var aInputs = Array();

      var aInputs = $('li').find('.get_online_sellers');
      var aKeys = Array();
      var aValues = Array();
      iKey = 0;
      $.each(aInputs, function(key, oInput) {
        if (oInput.checked) {
          aKeys[iKey] = oInput.value
        };
        iKey++;
      });
      if (aKeys.length > 0) {
        var sPath = '';
        for (var i = 0; i < aKeys.length; i++) {
          sPath = sPath + 'online_sellers[]=' + aKeys[i] + '&';
        }
      }

      var aInputs = $('li').find('.get_seller_country');
      var aKeys = Array();
      var aValues = Array();
      iKey = 0;
      $.each(aInputs, function(key, oInput) {
        if (oInput.checked) {
          aKeys[iKey] = oInput.value
        };
        iKey++;
      });
      if (aKeys.length > 0) {
        for (var i = 0; i < aKeys.length; i++) {
          sPath = sPath + 'seller_country[]=' + aKeys[i] + '&';
        }
      }

      var aInputs = Array();
      var aInputs = $('li').find('.get_seller_level');
      var aKeys = Array();
      var aValues = Array();
      iKey = 0;
      $.each(aInputs, function(key, oInput) {
        if (oInput.checked) {
          aKeys[iKey] = oInput.value
        };
        iKey++;
      });
      if (aKeys.length > 0) {
        for (var i = 0; i < aKeys.length; i++) {
          sPath = sPath + 'seller_level[]=' + aKeys[i] + '&';
        }
      }

      var aInputs = Array();
      var aInputs = $('li').find('.get_seller_language');
      var aKeys = Array();
      var aValues = Array();
      iKey = 0;
      $.each(aInputs, function(key, oInput) {
        if (oInput.checked) {
          aKeys[iKey] = oInput.value
        };
        iKey++;
      });
      if (aKeys.length > 0) {
        for (var i = 0; i < aKeys.length; i++) {
          sPath = sPath + 'seller_language[]=' + aKeys[i] + '&';
        }
      }


      var skills = $.map($('input[name="sellerSkills"]:checked'), function(c) {
        return c.value;
      })

      if (skills.length > 0) {
        sPath = sPath + 'seller_skills[]=' + skills + '&';
      }

      $('#wait').addClass("loader");
      $.ajax({
        url: "freelancer_load",
        method: "POST",
        data: sPath + 'zAction=get_freelancers',
        success: function(data) {
          $('#freelancers').html('');
          $('#freelancers').html(data);
          $('#wait').removeClass("loader");
        }
      });
      $.ajax({
        url: "freelancer_load",
        method: "POST",
        data: sPath + 'zAction=get_freelancer_pagination',
        success: function(data) {
          $('#freelancer_pagination').html('');
          $('#freelancer_pagination').html(data);
        }
      });
    }

    $('.get_online_sellers').click(function() {
      get_freelancers();
    });
    $('.get_seller_country').click(function() {
      get_freelancers();
    });
    $('.get_delivery_time').click(function() {
      get_freelancers();
    });
    $('.get_seller_level').click(function() {
      get_freelancers();
    });
    $('.get_seller_language').click(function() {
      get_freelancers();
    });
    $('.get_seller_skill').click(function() {
      get_freelancers();
    });

    $(document).ready(function() {
      $(".get_seller_country").click(function() {
        if ($(".get_seller_country:checked").length > 0) {
          $(".clear_seller_country").show();
        } else {
          $(".clear_seller_country").hide();
        }
      });
      $(".get_delivery_time").click(function() {
        if ($(".get_delivery_time:checked").length > 0) {
          $(".clear_delivery_time").show();
        } else {
          $(".clear_delivery_time").hide();
        }
      });
      $(".get_seller_level").click(function() {
        if ($(".get_seller_level:checked").length > 0) {
          $(".clear_seller_level").show();
        } else {
          $(".clear_seller_level").hide();
        }
      });
      $(".get_seller_language").click(function() {
        if ($(".get_seller_language:checked").length > 0) {
          $(".clear_seller_language").show();
        } else {
          $(".clear_seller_language").hide();
        }
      });
      $(".get_seller_skill").click(function() {
        if ($(".get_seller_skill:checked").length > 0) {
          $(".clear_seller_skill").show();
        } else {
          $(".clear_seller_skill").hide();
        }
      });
      $(".clear_seller_country").click(function() {
        $(".clear_seller_country").hide();
      });
      $(".clear_delivery_time").click(function() {
        $(".clear_delivery_time").hide();
      });
      $(".clear_seller_level").click(function() {
        $(".clear_seller_level").hide();
      });
      $(".clear_seller_language").click(function() {
        $(".clear_seller_language").hide();
      });
      $(".clear_seller_skill").click(function() {
        $(".clear_seller_skill").hide();
      });
    });

    function clearCountry() {
      $('.get_seller_country').prop('checked', false);
      get_freelancers();
    }

    function clearDelivery() {
      $('.get_delivery_time').prop('checked', false);
      get_freelancers();
    }

    function clearLevel() {
      $('.get_seller_level').prop('checked', false);
      get_freelancers();
    }

    function clearLanguage() {
      $('.get_seller_skill').prop('checked', false);
      get_freelancers();
    }

    function clearSkill() {
      $('.get_seller_skill').prop('checked', false);
      get_freelancers();
    }
  </script>
</body>

</html>
<?php
session_start();
require_once("includes/db.php");
require_once("social-config.php");
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
  <title> <?= $site_name; ?> - <?= $lang['titles']['how_it_works']; ?> </title>
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
  <link href="styles/categories_nav_styles.css" rel="stylesheet">
  <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
  <link href="styles/owl.carousel.css" rel="stylesheet">
  <link href="styles/owl.theme.default.css" rel="stylesheet">
  <link href="styles/sweat_alert.css" rel="stylesheet">
  <link href="styles/animate.css" rel="stylesheet">
  <?php if (!empty($site_favicon)) { ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
  <?php } ?>
  <script type="text/javascript" src="js/ie.js"></script>
  <script type="text/javascript" src="js/sweat_alert.js"></script>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <style>
    .swal2-popup .swal2-styled.swal2-confirm {
      background-color: #28a745;
    }
    .alter-top-margin-how-to{
      margin-top:105px;
    }
    .display-flex-alter{
    text-align: center;
    }
    @media(max-width:767px){
      .display-flex-alter{
        width:100%;
        display:flex;
      }
      .text-align-center-alter{
        text-align:center;
        margin:auto;
        /* background-color:yellow; */
      }
  .color-of-heading{
    color:#00c8d4;
    text-align: center;
  }
  .color-of-paragraph{
    color:gray; 
    text-align: justify;
    padding: 10px 5px;
    /* margin-top: 1rem; */
  }
  .color-align-alter-title{
    text-align: center;
    /* background-color: #f1fef7; */
    /* margin-bottom: 15px; */
  }
  .color-align-alter-desc{
    text-align: justify;
    padding:5px;
  }
  .background-color-azure{
  background-color: #f1fef7;
}
    }
  </style>
</head>

<body class="is-responsive">
  <?php require_once("includes/header.php"); ?>
  <header id="how_to" class="alter-top-margin-how-to">
    <div class="cell">
      <h2 class="text-center text-white"><?= $lang['how_it_works']['title']; ?></h2>
      <h3 class="text-center"><?= $lang['how_it_works']['desc']; ?></h3>
    </div>
  </header>
  <div class="container">
    <div class="row pb-4">
      <div class="col-md-12">
        <div class="card mt-4 mb-3 rounded-0">
          <div class="card-body background-color-azure">
            <h2 class="color-align-alter-title"><?= $lang['how_it_works']['buyers']['title']; ?></h2>
          </div>
        </div>
        <p class="pb-4 color-align-alter-desc">
          <?= str_replace('{site_name}', $site_name, $lang['how_it_works']['buyers']['desc']); ?>
        </p>
        <div class="row pt-3">
          <div class="col-md-6">
            <div class="row pt-4">
              <div class="col-md-3 display-flex-alter">
                <i class="fa fa-search fa-adj text-align-center-alter" style="color:#3498db;"></i>
              </div>
              <div class="col-md-9">
                <div class="content-talks center-text-alter">
                  <?= $lang['how_it_works']['buyers']['column_1']; ?>
                </div>
              </div>
            </div>
            <div class="row pt-4">
              <div class="col-md-3 display-flex-alter">
                <i class="fa fa-comments-o fa-adj text-align-center-alter" style="color:#e74c3c;"></i>
              </div>
              <div class="col-md-9">
                <div class="content-talks center-text-alter">
                  <?= $lang['how_it_works']['buyers']['column_2']; ?>
                </div>
              </div>
            </div>
            <div class="row pt-4">
              <div class="col-md-3 display-flex-alter">
                <i class="fa fa-refresh fa-adj text-align-center-alter" style="color:#9b59b6;"></i>
              </div>
              <div class="col-md-9">
                <div class="content-talks center-text-alter">
                  <?= $lang['how_it_works']['buyers']['column_3']; ?>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="row pt-4">
              <div class="col-md-3 display-flex-alter">
                <i class="fa fa-file-text-o fa-adj text-align-center-alter" style="color:#f1c40f;"></i>
              </div>
              <div class="col-md-9">
                <div class="content-talks center-text-alter">
                  <?= $lang['how_it_works']['buyers']['column_4']; ?>
                </div>
              </div>
            </div>
            <div class="row pt-4">
              <div class="col-md-3 display-flex-alter">
                <i class="fa fa-check-square-o fa-adj text-align-center-alter" style="color:#2ecc71;"></i>
              </div>
              <div class="col-md-9">
                <div class="content-talks center-text-alter">
                  <?= $lang['how_it_works']['buyers']['column_5']; ?>
                </div>
              </div>
            </div>
            <div class="row pt-4">
              <div class="col-md-3 display-flex-alter">
                <i class="fa fa-star fa-adj text-align-center-alter" style="color:#f1c40f;"></i>
              </div>
              <div class="col-md-9">
                <div class="content-talks center-text-alter">
                  <?= $lang['how_it_works']['buyers']['column_6']; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row pb-4">
      <div class="col-md-12">
        <div class="card mt-4 mb-3 rounded-0">
          <div class="card-body background-color-azure">
            <h2 class="color-align-alter-title"><?= $lang['how_it_works']['sellers']['title']; ?></h2>
          </div>
        </div>
        <p class="color-align-alter-desc"><?= str_replace('{site_name}', $site_name, $lang['how_it_works']['sellers']['desc']); ?></p>
        <div class="row pt-3">
          <div class="col-md-6">
            <div class="row pt-4">
              <div class="col-md-3 display-flex-alter">
                <i class="fa fa-pencil-square-o fa-adj text-align-center-alter" style="color:#3498db;"></i>
              </div>
              <div class="col-md-9">
                <div class="content-talks center-text-alter">
                  <?= $lang['how_it_works']['sellers']['column_1']; ?>
                </div>
              </div>
            </div>
            <div class="row pt-4 pb-4">
              <div class="col-md-3 display-flex-alter">
                <i class="fa fa fa-line-chart fa-adj text-align-center-alter" style="color:#2ecc71;"></i>
              </div>
              <div class="col-md-9">
                <div class="content-talks center-text-alter">
                  <?= $lang['how_it_works']['sellers']['column_2']; ?>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="row pt-4">
              <div class="col-md-3 display-flex-alter">
                <i class="fa fa-comments fa-adj text-align-center-alter" style="color:#e74c3c;"></i>
              </div>
              <div class="col-md-9">
                <div class="content-talks center-text-alter">
                  <?= $lang['how_it_works']['sellers']['column_3']; ?>
                </div>
              </div>
            </div>
            <div class="row pt-4 pb-4">
              <div class="col-md-3 display-flex-alter">
                <i class="fa fa-clock-o fa-adj text-align-center-alter" style="color:#9b59b6;"></i>
              </div>
              <div class="col-md-9">
                <div class="content-talks center-text-alter">
                  <?= $lang['how_it_works']['sellers']['column_4']; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("includes/footer.php"); ?>
</body>

</html>
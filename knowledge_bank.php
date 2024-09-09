<?php
session_start();
require_once("includes/db.php");
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
  <title> <?= $site_name; ?> - <?= $lang["titles"]["knowledge_bank"]; ?> </title>
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
  <link href="styles/knowledge_base.css" rel="stylesheet">
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
    .fourth_div_sect {
      width: 30%;
      margin: 15px !important;
      overflow: hidden;
      float: left;
      height: 60vh;
      /* background-color: #fafcfc; */
      border: 1px solid lightgray;
      box-shadow: 0px 0px 3px black;
    }



    .article_img_div {
      width: 100%;
      height: 40vh;
      border-radius: 1px 1px 1px 55px;
      /* border:2px solid gray; */
    }

    .article_heading {
      color: red;
      font-size: 17px;
      font-weight: 800;
      padding: 15px 4px 1px 4px;
    }

    .article_body {
      color: gray;
      font-weight: 600;
      font-size: 20px;
      padding: 5px 4px 1px 4px;
    }

    .padding-next-for-impro {
      padding-left: 35px;
      padding-top: 20px;
      /* height:fit-content; */
      /* background-color: #fafcfc; */
    }

    .padding-next-for-impro2 {
      /* padding-left: 35px; */
      padding-top: 15px;
      /* background-color: #f7f7f7; */
    }

    .container_image_margin {
      width: 80%;
      margin: auto;
      /* border: 1px solid green; */
      height: 100vh;
      /* padding: 0 0 0 70px; */
      /* display: flex; */
    }

    .container_for_faq_cat {
      border: 1px solid #000;
      width: 85%;
      height: 220px;
      float: left;
      padding: 20px;
      margin: 20px auto;
      background-color: white;
      box-shadow: 0px 0px 1px 1px gray;
      /* text-align: center; */
      border-radius: 10px;
    }

    .container_for_faq_cat:hover {
      border: 1px solid #00CEDC;
      /* transition-duration: 0.3s; */
      box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
      border-radius: 10px 0px 10px 0px;

    }

    .text-align-center-1 {
      text-align: center;
      padding-top: 15px;
      font-size: 17px;
    }   

    .image_restyling {
      /* border: 1px solid green;   */
      margin-top: 15px;
    }

    .image_restyle_paragra {
      /* border:1px solid green; */
      text-align: center;
    }

    .text-center-main-h {
      text-align: center;
      width: 100%;
      /* border:1px solid green; */
    }
    .in-line-blo-ck{
      display: flex;
      float: left;
      width: 33%;
    }
   
    
    @media(max-width:768px){
      .in-line-blo-ck{
      display:contents;
      float: left;
      width: 33%;
    }

    .container_for_faq_cat {
      border: 1px solid lightgray;
      width: 100%;
      height: 230px;
      float: left;
      padding: 20px;
      margin: 20px auto;
      background-color: white;
      box-shadow: 0px 0px 1px 1px gray;
      /* text-align: center; */
      border-radius: 8px;
    }
    }
  </style>
  <?php require_once("includes/external_stylesheet.php"); ?>
</head> 

<body style="background-color: #E4E4E4;" class="is-responsive">
  <div class="header" style="<?= ($lang_dir == "right" ? 'direction: rtl;' : '') ?>">
    <div class="container pb-4">
      <a class="navbar-brand logo text-success" href="<?= $site_url; ?>/index">
        <?php if ($site_logo_type == "image") { ?>
          <img src="<?= $site_logo_image; ?>" width="150" style="margin-top:8%;">
        <?php } else { ?>
          <?= $site_logo_text; ?>
        <?php } ?>
      </a>
      <div class="text-center">
        <h2 class="text-white mt-5"><?= $lang['knowledge_bank']['title']; ?></h2>
        <h4 class="text-white"><?= $lang['knowledge_bank']['desc']; ?></h4>
      </div>
      <div class="text-center reduceForm">
        <form action="" method="post">
          <div class="input-group space50 mb-4">
            <input type="text" name="search_query" class="form-control" value="" required placeholder="<?= $lang['placeholder']['search_questions']; ?>">
            <div class="input-group-append move-icon-up" style="cursor:pointer;">
              <button name="search_article" type="submit" class="search_button">
                <img src="images/srch2.png" class="srch2">
              </button>
            </div>
          </div>
        </form>
        <?php
        if (isset($_POST['search_article'])) {
          $search_query = $input->post('search_query');
          echo "<script>window.open('$site_url/search_articles?search=$search_query','_self')</script>";
        }
        ?>
      </div>
    </div>
  </div>
  <div class="container mt-5">
    <div class="row" style="<?= ($lang_dir == "right" ? 'direction: rtl;' : '') ?>">
      <?php
      $get_cats = $db->select("article_cat", array("language_id" => $siteLanguage));
      while ($row_cats = $get_cats->fetch()) {
        $article_cat_id = $row_cats->article_cat_id;
        $article_cat_title = $row_cats->article_cat_title;
      ?>
        <div class="text-center-main-h mt-4">
          <h3 class="make-black pb-1 "><?= $article_cat_title; ?> </h3>
        </div>
        <div class="col-md-12 padding-next-for-impro">
          <div class="container_image_margin">
            <!-- Category -->
            <?php
            $get_articles = "select DISTINCT * from knowledge_bank where cat_id='$article_cat_id' AND language_id='$siteLanguage'";
            $get_articles = $db->select("knowledge_bank", array("cat_id" => $article_cat_id, "language_id" => $siteLanguage));
            $count_articles = $get_articles->rowCount();
            if ($count_articles == 0) {
              echo "No articles to display at the moment.";
            }
            while ($row_articles = $get_articles->fetch()) {
              $article_id = $row_articles->article_id;
              $article_url = $row_articles->article_url;
              $article_heading = $row_articles->article_heading;
              $article_body = $row_articles->article_body;
              $right_image = $row_articles->right_image;
              $top_image = getImageUrl2("knowledge_bank", "top_image", $row_articles->top_image);
              $bottom_image = $row_articles->bottom_image;
            ?>
 
              <a href="<?= $site_url ?>/search_articles?search=<?= $article_heading; ?>" class="in-line-blo-ck" >
                <div class="container_for_faq_cat">
                  <p class="image_restyle_paragra">
                    <img src="<?php echo $top_image ?>" alt="" width="100px" class="image_restyling">
                  </p>
                  <h6 class="text-align-center-1">
                    <?= $article_heading; ?>
                  </h6>
                </div>
              </a>
            <?php } ?>
          </div>
           <!--<br><br> -->
        <?php } ?>
        </div>
        <!-- <div class="col-md-9 padding-next-for-impro2">
          <?php
          $get_articles = "select * from knowledge_bank where cat_id='$article_cat_id' AND language_id='$siteLanguage'";
          $get_articles = $db->select("knowledge_bank", array("cat_id" => $article_cat_id, "language_id" => $siteLanguage));
          $count_articles = $get_articles->rowCount();
          while ($row_articles = $get_articles->fetch()) {
            $article_id = $row_articles->article_id;
            $article_url = $row_articles->article_url;
            $article_heading = $row_articles->article_heading;
            $article_body = $row_articles->article_body;
            $right_image = $row_articles->right_image;
            $top_image = getImageUrl2("knowledge_bank", "top_image", $row_articles->top_image);
            $bottom_image = $row_articles->bottom_image;
          ?>

            <div class="thirdth_div_sect">
              <div class="fourth_div_sect">
                <div><img src="<?php echo $top_image; ?>" class="article_img_div"></div>
                <div class="article_heading"><?= $article_heading; ?></div>
                <div class="article_body_div">
                  <h6 class="article_body"><?= $article_body ?></h6>
                </div>
              </div>
            </div>
          <?php } ?>
        </div> -->

    </div>
  </div>
  <?php include "includes/footer.php"; ?>
</body>

</html>
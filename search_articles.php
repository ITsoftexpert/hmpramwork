<?php

session_start();

require_once("includes/db.php");

?>

<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>

    <title> <?= $site_name; ?> - Search Articles </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $site_desc; ?>">
    <meta name="keywords" content="<?= $site_keywords; ?>">
    <meta name="author" content="<?= $site_author; ?>">


    <link href="styles/bootstrap.css" rel="stylesheet">

    <link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->

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
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/sweat_alert.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>


    <style>
        .form-control {

            width: 200px;
        }

        .height_fit_for_content {
            height: fit-content;
        }

        .one_main_sect {
            border-left: 1px solid gray;

            width: 100%;
            height: fit-content;
        }

        .image_sect {
            /* border: 1px solid red; */
            width: 100%;
            height: fit-content;
            padding-left: 55px;
            /* text-align: center; */
        }

        .image_heading {
            width: 90%;
            height: fit-content;
            font-size: 30px;
            font-weight: 700;
            color: black;
            padding: 30px 0 20px 55px;

        }

        .article_body {
            /* border: 1px solid black; */
            width: 90%;
            padding: 50px 0 0 55px;
            height: fit-content;
            font-size: 16px;
            line-height: 1.7;
            font-weight: 400;
            color: black;
            text-align: justify;
        }

        #resize_image {
            width: 85%;
            /* height: 50%; */
            border:1px solid gray;
        }

        .image_heading_paragraph {
            width: 90%;
            height: fit-content;
            font-size: 35px;
            font-family: 'Montserrat-Regular';
            font-weight: bolder;
            color: black;
            padding-left: 55px;
        }

        /* .border_ltor{
            border-left:1px solid gray;
           
        } */
        .heading_styling {
            border: 1px solid gray;
            padding: 18px;
            margin: 5px;
            font-size: 16px;
            font-weight: 800;
            color: gray;
        }

        .heading_styling:hover {
            /* border:1px solid black; */
            background-color: black;
            color: white;
        }
        .videos_section_div{
            /* border:1px solid gray; */
            height: 30rem;
            padding-top: 3rem;
            padding-left: 55px;
            border-left: 1px solid gray;
        }
        .videos_section_div iframe{
            box-shadow: 0px 0px 4px gray;
            border-radius: 5px;            
        }
        .color_red{
            color:red;
            font-size:21px;
            font-weight:bolder;
        }
        .heading_4_style{
            /* border:2px solid green; */
            font-size: 17px;
        }
        .heading_6_style{
            /* border:2px solid red; */
            font-size: 20px; 
            font-weight: 900;
        }
        .heading_4a_style{
            /* border:2px solid gray; */
            font-size: 19px;
            font-weight: 600;
        }
        .heading_3_style{
            /* border:2px solid yellow; */
            font-size: 30px;
            font-weight: bolder;
        }
        .listing_number{           
            margin-left:40px;
            padding-left:10px;
        }
         .listing_bullet{           
            margin-left:40px;
            padding-left:10px;
        }
        .heading_6a_style{
            /* list-style-type: disc; */
            /* border:2px solid pink; */
            font-size: 18px;
            font-weight:500;
            color:gray;
        }
       
        .italic_font_style{
            font-style: italic;
        }
    </style>

</head> 
 
<body class="is-responsive">

    <div class="header">

        <div class="container pb-5">

            <a class="navbar-brand logo text-success " href="<?= $site_url; ?>">

                <?php if ($site_logo_type == "image") { ?>

                    <img src="<?= $site_logo_image; ?>" width="150" style="margin-top:8%;">

                <?php } else { ?>

                    <?= $site_logo_text; ?>

                <?php } ?>

            </a>


            <div class="text-center">

                <h2 class="text-white mt-5">KNOWLEDGE BANK FOR <?= strtoupper($site_name); ?></h2>

                <h4 class="text-white">Everything you need to know</h4>

            </div>

            <div class="text-center reduceForm mb-4">

                <form action="" method="post">

                    <div class="input-group space50">

                        <input type="text" name="search_query" required class="form-control" value="<?= $input->get('search'); ?>" placeholder="Search Questions">

                        <div class="input-group-append move-icon-up" style="cursor:pointer;">

                            <button name="search_article" type="submit" class="search_button">

                                <img src="images/srch2.png" class="srch2">

                            </button>

                        </div>

                    </div>

                </form>

                <?php

                if (isset($_POST['search_article'])) {

                    $search_query = $input->post('search');

                    echo "<script>window.open('$site_url/search_articles.php?search=$search_query','_self')</script>";
                }

                ?>

            </div>

        </div>

    </div>

    <div class="container mt-5 mb-5">

        <div class="row">

            <div class="col-md-3 border_ltor">
                <?php
                $get_category = $db->select("article_cat", array("language_id" => $siteLanguage));
                while ($row_category = $get_category->fetch()) {
                    $article_category_id = $row_category->article_cat_id;
                    $article_category_title = $row_category->article_cat_title;
                ?>
                    <?php
                    $give_articles = "select DISTINCT * from knowledge_bank where cat_id='$article_category_id' AND language_id='$siteLanguage'";
                    $give_articles = $db->select("knowledge_bank", array("cat_id" => $article_category_id, "language_id" => $siteLanguage));
                    $counter_articles = $give_articles->rowCount();
                    if ($counter_articles == 0) {
                        echo "No articles to display at the moment.";
                    }
                    while ($rowside_articles = $give_articles->fetch()) {
                        $articleses_id = $rowside_articles->article_id;
                        $articleses_url = $rowside_articles->article_url;
                        $articleses_heading = $rowside_articles->article_heading;
                        $articleses_body = $rowside_articles->article_body;
                        $righteses_image = $rowside_articles->right_image;
                        $topeses_image = getImageUrl2("knowledge_bank", "top_image", $rowside_articles->top_image);
                        $bottomeses_image = $rowside_articles->bottom_image;
                    ?>
                        <a href="<?= $site_url ?>/search_articles?search=<?= $articleses_heading; ?>">
                            <div class="heading_styling">
                                <?= $articleses_heading; ?>
                            </div>
                        </a>


                <?php }
                } ?>

            </div>
            <div class="col-md-9 height_fit_for_content">
                <?php
                $search = $input->get('search');
                $get_articles = $db->query("select * from knowledge_bank where article_heading like :search AND language_id='$siteLanguage' order by 1 DESC", array("search" => "%$search%"));
                $count_articles = $get_articles->rowCount();
                if ($count_articles == 0) {
                    echo "No articles to display at the moment.";
                }
                while ($row_articles = $get_articles->fetch()) {
                    $article_id = $row_articles->article_id;
                    $article_url = $row_articles->article_url;
                    $article_heading = $row_articles->article_heading;
                    $article_body = $row_articles->article_body;                   
                    $right_image = getImageUrl2("knowledge_bank", "right_image", $row_articles->right_image);
                    $bottom_image = $row_articles->bottom_image;
                ?>
                    <div class="one_main_sect">
                        <div class="image_heading_paragraph">
                            <p> Something here to help you out what you want to know about
                                "<?Php echo  $article_heading ?>"</p>
                        </div>
                        <div class="image_sect">
                            <img src=" <?php echo  $right_image ?>" alt="" id="resize_image">
                        </div>
                        <!-- <div class="image_heading">
                            <?Php echo  $article_heading ?>
                        </div> -->
                        <div class="article_body"><?php echo $article_body ?> </div>
                    </div>
                   
            </div>
        <?php } ?>
        </div>
    </div>

    </div>

    <?php include "includes/footer.php"; ?>

</body>

</html>
<style>
  .how_it_works_link a:hover {
    color: grey !important;
  }
</style>
<div class="sub_header col-xs-12 sm-hidden box-shadow-cat-nav">
  <div class="sub_header_inner row">
    <div class="col-sm-6">
      <ul class="sub_header_menu">
        <li>
          <a href="<?= $site_url ?>/categories/graphics-design">
            <i class="fa fa-search"></i> Hire By Category
          </a>
        </li>
        <li>
          <!-- <a href="<?= $site_url ?>/requests/post_request">
            <i class="fa fa-pencil"></i> Post Job Free
          </a> -->
          <?php if (isset($_SESSION['seller_user_name'])) {
          ?>
            <a href="<?= $site_url ?>/requests/post_request">
              <i class="fa fa-pencil"></i> Post Job Free
            </a>
          <?php
          } else {
          ?>
            <a href="<?= $site_url ?>/login">
              <i class="fa fa-pencil"></i> Post Job Free
            </a>
          <?php
          }

          ?>

        </li>
        <li>
          <a href="<?= $site_url ?>/freelancers">
            <i class="fa fa-user"></i> Hire an Expert
          </a>
        </li>
        <?php if (isset($_SESSION['seller_user_name'])) { ?>
          <li>
            <a href="<?= $site_url ?>/requests/buyer_requests">
              <i class="fa fa-user"></i> Find Job
            </a>
          </li>
        <?php } ?>
      </ul>
    </div>
    <div class="col-sm-6" align="right">
      <ul class="sub_header_menu">
        <li>
          <?php echo $row_general_settings->subheader_tagline; ?>
        </li>
        <li class="how_it_works_link">
          <a href="<?= $site_url ?>/how-it-works" style="font-size:18px;" class="text-primary">How it works?</a>
        </li>
      </ul>
    </div>
  </div>
</div>
<div data-ui="cat-nav" id="desktop-category-nav" class="ui-toolkit cat-nav  d-none">
  <div class="bg-white bg-transparent-homepage-experiment bb-xs-1 hide-xs hide-sm hide-md">
    <div class="col-group body-max-width">
      <ul class="col-xs-12 body-max-width display-flex-xs justify-content-space-between" role="menubar" data-ui="top-nav-category-list" aria-activedescendant="catnav-primary-link-10855">
        <?php
        $get_categories = $db->query("select * from categories where cat_featured='yes'" . ($lang_dir == "right" ? 'order by 1 DESC' : '') . " LIMIT 0,9");
        while ($row_categories = $get_categories->fetch()) {
          $cat_id = $row_categories->cat_id;
          $cat_url = $row_categories->cat_url;
          $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
          $row_meta = $get_meta->fetch();
          @$cat_title = $row_meta->cat_title;
        ?>
          <li class="top-nav-item pt-xs-1 pb-xs-1 pl-xs-2 pr-xs-2 display-flex-xs align-items-center text-center" data-linkable="true" data-ui="top-nav-category-link" data-node-id="c-<?= $cat_id; ?>">
            <a href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
              <?= @$cat_title; ?>
            </a>
          </li>
        <?php } ?>

        <?php

        $count = $db->count("categories", array("cat_featured" => "yes"));
        if ($count > 10) {

        ?>

          <li class="top-nav-item pt-xs-1 pb-xs-1 pl-xs-2 pr-xs-2 display-flex-xs align-items-center text-center" data-linkable="true" data-ui="top-nav-category-link" data-node-id="c-more">
            <a href="#"><?= $lang['more']; ?></a>
          </li>

          <?php

        } else {

          $get_categories = $db->query("select * from categories where cat_featured='yes'" . ($lang_dir == "right" ? 'order by 1 DESC' : '') . " LIMIT 9,1");
          while ($row_categories = $get_categories->fetch()) {
            $cat_id = $row_categories->cat_id;
            $cat_url = $row_categories->cat_url;
            $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
            $row_meta = $get_meta->fetch();
            @$cat_title = $row_meta->cat_title;

          ?>

            <li class="top-nav-item pt-xs-1 pb-xs-1 pl-xs-2 pr-xs-2 display-flex-xs align-items-center text-center" data-linkable="true" data-ui="top-nav-category-link" data-node-id="c-<?= $cat_id; ?>">
              <a href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
                <?= @$cat_title; ?>
              </a>
            </li>

        <?php }
        } ?>

      </ul>
    </div>
  </div>

  <div class="position-absolute col-xs-12 col-centered z-index-4">
    <div>
      <?php
      $get_categories = $db->query("select * from categories where cat_featured='yes'" . ($lang_dir == "right" ? 'order by 1 DESC' : '') . " LIMIT 0,10");
      while ($row_categories = $get_categories->fetch()) {
        $cat_id = $row_categories->cat_id;
        $cat_url = $row_categories->cat_url;
        $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
        $row_meta = $get_meta->fetch();
        @$cat_title = $row_meta->cat_title;
        $count = $db->count("categories_children", array("child_parent_id" => $cat_id));
        if ($count > 0) {
      ?>
          <div class="body-sub-width vertical-align-top sub-nav-container bg-white overflow-hidden bl-xs-1 bb-xs-1 br-xs-1 catnav-mott-control display-none" data-ui="sub-nav" aria-hidden="true" data-node-id="c-<?= $cat_id; ?>">
            <div class="width-full display-flex-xs">
              <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">
                <?php
                $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id' LIMIT 0,10");
                while ($row_child_cat = $get_child_cat->fetch()) {
                  $child_id = $row_child_cat->child_id;
                  $child_url = $row_child_cat->child_url;
                  $get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
                  $row_meta = $get_meta->fetch();
                  $child_title = $row_meta->child_title;
                ?>
                  <li>
                    <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
                      <?= $child_title; ?>
                    </a>
                  </li>
                <?php } ?>
              </ul>
              <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">
                <?php
                $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id' LIMIT 10,10");
                while ($row_child_cat = $get_child_cat->fetch()) {
                  $child_id = $row_child_cat->child_id;
                  $child_url = $row_child_cat->child_url;
                  $get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
                  $row_meta = $get_meta->fetch();
                  $child_title = $row_meta->child_title;
                ?>
                  <li>
                    <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
                      <?= $child_title; ?>
                    </a>
                  </li>
                <?php } ?>
              </ul>
              <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">
                <?php
                $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id' LIMIT 20,10");
                while ($row_child_cat = $get_child_cat->fetch()) {
                  $child_id = $row_child_cat->child_id;
                  $child_url = $row_child_cat->child_url;
                  $get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
                  $row_meta = $get_meta->fetch();
                  $child_title = $row_meta->child_title;

                ?>
                  <li>
                    <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
                      <?= $child_title; ?>
                    </a>
                  </li>
                <?php } ?>
              </ul>
              <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">
                <?php
                $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id' LIMIT 30,10");
                while ($row_child_cat = $get_child_cat->fetch()) {
                  $child_id = $row_child_cat->child_id;
                  $child_url = $row_child_cat->child_url;
                  $get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
                  $row_meta = $get_meta->fetch();
                  $child_title = $row_meta->child_title;
                ?>
                  <li>
                    <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
                      <?= $child_title; ?>
                    </a>
                  </li>
                <?php } ?>
              </ul>
            </div>
          </div>
        <?php } ?>
      <?php } ?>

      <div class="body-sub-width vertical-align-top sub-nav-container bg-white overflow-hidden bl-xs-1 bb-xs-1 br-xs-1 catnav-mott-control display-none" data-ui="sub-nav" aria-hidden="true" data-node-id="c-more">

        <div class="width-full display-flex-xs">

          <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">

            <?php

            $get_categories = $db->query("select * from categories where cat_featured='yes' LIMIT 9,19");
            while ($row_categories = $get_categories->fetch()) {

              $cat_id = $row_categories->cat_id;
              $cat_url = $row_categories->cat_url;

              $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
              $row_meta = $get_meta->fetch();
              $cat_title = $row_meta->cat_title;

            ?>

              <li>
                <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
                  <?= @$cat_title; ?>
                </a>
              </li>

            <?php } ?>

          </ul>

          <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">

            <?php

            $get_categories = $db->query("select * from categories where cat_featured='yes' LIMIT 19,29");
            while ($row_categories = $get_categories->fetch()) {

              $cat_id = $row_categories->cat_id;
              $cat_url = $row_categories->cat_url;

              $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
              $row_meta = $get_meta->fetch();
              $cat_title = $row_meta->cat_title;

            ?>

              <li>
                <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
                  <?= @$cat_title; ?>
                </a>
              </li>

            <?php } ?>

          </ul>


          <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">

            <?php

            $get_categories = $db->query("select * from categories where cat_featured='yes' LIMIT 29,39");
            while ($row_categories = $get_categories->fetch()) {

              $cat_id = $row_categories->cat_id;
              $cat_url = $row_categories->cat_url;

              $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
              $row_meta = $get_meta->fetch();
              $cat_title = $row_meta->cat_title;

            ?>

              <li>
                <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
                  <?= @$cat_title; ?>
                </a>
              </li>

            <?php } ?>

          </ul>

        </div>

      </div>

    </div>
  </div>
</div>
<!-- <script>
  var lastScrollTop = 0;
  
  window.addEventListener('scroll', function() {
    var hidescrolldown = document.querySelector('#hidescrolldown');
    var scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > lastScrollTop) {
      // Scrolling down
      hidescrolldown.style.display = 'none';
    } else {
      // Scrolling up
      hidescrolldown.style.display = 'block';
    }

    lastScrollTop = scrollTop;
  });
</script> -->



<?php include("mobile_menu.php"); ?>
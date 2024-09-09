<?php
function proposalsQueryWhere($type, $filter_type)
{
   global $db;

   $online_sellers = array();
   $where_online = array();
   $where_country = array();
   $where_level = array();
   $where_language = array();
   $values = array();
   $where_path = "";
   $where_online = array();
   $where_city = array();
   $where_cat = array();
   $where_child_cat = array(); // Sub-category array
   $where_attr_child = array(); // Sub-sub-category array
   $where_skill_id = array(); // skills array
   $where_delivery_times = array();
   $instant_delivery = 0;

   if (isset($_REQUEST['online_sellers'])) {
      $sellers = $db->query("select * from sellers");
      while ($seller = $sellers->fetch()) {
         if (check_status($seller->seller_id) == "Online") {
            array_push($online_sellers, $seller->seller_id);
         }
      }
   }
   // Conditions
   if (isset($_REQUEST['online_sellers'])) {
      $i = 0;
      foreach ($_REQUEST['online_sellers'] as $value) {
         if ($value != 0) {
            foreach ($online_sellers as $seller_id) {
               $i++;
               $where_online[] = "sellers.seller_id=:seller_id_$i";
               $values["seller_id_$i"] = $seller_id;
            }
            $where_path .= "online_sellers[]=" . $value . "&";
         }
      }
   }
   if (isset($_REQUEST['instant_delivery'])) {
      $instant_delivery = $_REQUEST['instant_delivery'][0];
   }
   if (isset($_REQUEST['seller_country'])) {
      $i = 0;
      foreach ($_REQUEST['seller_country'] as $value) {
         $i++;
         if ($value != "undefined") {
            $where_country[] = "sellers.seller_country=:seller_country_$i";
            $values["seller_country_$i"] = $value;
            $where_path .= "seller_country[]=" . $value . "&";
         }
      }
   }
   if (isset($_REQUEST['seller_city'])) {
      $i = 0;
      foreach ($_REQUEST['seller_city'] as $value) {
         $i++;
         if ($value != "undefined") {
            $where_city[] = "sellers.seller_city=:seller_city_$i";
            $values["seller_city_$i"] = $value;
            $where_path .= "seller_city[]=" . $value . "&";
         }
      }
   }
   if (isset($_REQUEST['cat_id'])) {
      $i = 0;
      foreach ($_REQUEST['cat_id'] as $value) {
         $i++;
         if ($value != 0) {
            $where_cat[] = "proposal_cat_id=:proposal_cat_id_$i";
            $values["proposal_cat_id_$i"] = $value;
            $where_path .= "cat_id[]=" . $value . "&";
         }
      }
   }
   if (isset($_REQUEST['child_id'])) {
      $i = 0;
      foreach ($_REQUEST['child_id'] as $value) {
         $i++;
         if ($value != 0) {
            $where_child_cat[] = "proposal_child_id=:proposal_child_id_$i";
            $values["proposal_child_id_$i"] = $value;
            $where_path .= "child_id[]=" . $value . "&";
         }
      }
   }

   if (isset($_REQUEST['attr_id'])) {
      $i = 0;
      foreach ($_REQUEST['attr_id'] as $value) {
         $i++;
         if ($value != 0) {
            $where_attr_child[] = "proposal_attr_id=:proposal_attr_id_$i";
            $values["proposal_attr_id_$i"] = $value;
            $where_path .= "attr_id[]=" . $value . "&";
         }
      }
   }

   if (isset($_REQUEST['skill_id'])) {
      $i = 0;
      foreach ($_REQUEST['skill_id'] as $value) {
         $i++;
         if ($value != 0) {
            $where_attr_child[] = "skill_title_id=:skill_title_id_$i";
            $values["skill_title_id_$i"] = $value;
            $where_path .= "skill_id[]=" . $value . "&";
         }
      }
   }

   if (isset($_REQUEST['delivery_time'])) {
      $i = 0;
      foreach ($_REQUEST['delivery_time'] as $value) {
         $i++;
         if ($value != 0) {
            $where_delivery_times[] = "delivery_id=:delivery_id_$i";
            $values["delivery_id_$i"] = $value;
            $where_path .= "delivery_time[]=" . $value . "&";
         }
      }
   }
   if (isset($_REQUEST['seller_level'])) {
      $i = 0;
      foreach ($_REQUEST['seller_level'] as $value) {
         $i++;
         if ($value != 0) {
            $where_level[] = "level_id=:level_id_$i";
            $values["level_id_$i"] = $value;
            $where_path .= "seller_level[]=" . $value . "&";
         }
      }
   }
   if (isset($_REQUEST['seller_language'])) {
      $i = 0;
      foreach ($_REQUEST['seller_language'] as $value) {
         $i++;
         if ($value != 0) {
            $where_language[] = "language_id=:language_id_$i";
            $values["language_id_$i"] = $value;
            $where_path .= "seller_language[]=" . $value . "&";
         }
      }
   }

   $query_where = "WHERE proposals.proposal_status='active' ";

   if ($filter_type == "search") {
      $search_query = $_SESSION['search_query'];
      $s_value = "%$search_query%";
      $query_where .= "AND proposals.proposal_title LIKE :proposal_title";
      $values['proposal_title'] = $s_value;
   } elseif ($filter_type == "featured") {
      $query_where .= "AND proposals.proposal_featured=:proposal_featured";
      $values['proposal_featured'] = 'yes';
   } elseif ($filter_type == "top") {
      $query_where .= "AND proposals.level_id=:level_id";
      $values['level_id'] = 4;
   } elseif ($filter_type == "tag") {
      if (isset($_REQUEST['tag'])) {
         $tag = $_REQUEST['tag'];
         $query_where .= "AND proposals.proposal_tags LIKE :proposal_tags";
         $values['proposal_tags'] = "%$tag%";
      }
   }

   if (isset($_REQUEST['cat_url'])) {
      $get_cat = $db->select("categories", array('cat_url' => urlencode($_REQUEST['cat_url'])));
      $count_cat = $get_cat->rowCount();
      if ($count_cat > 0) {
         $cat_id = $get_cat->fetch()->cat_id;
         $query_where .= "AND proposals.proposal_cat_id=:cat_id";
         $values['cat_id'] = $cat_id;
      }
   }

   if (count($where_online) > 0) {
      $query_where .= addAnd($query_where) . " (" . implode(" or ", $where_online) . ")";
   }
   if ($instant_delivery == 1) {
      $query_where .= " AND instant_deliveries.enable=1";
   }
   if (count($where_country) > 0) {
      $query_where .= " AND (" . implode(" or ", $where_country) . ")";
   }
   if (count($where_city) > 0) {
      $query_where .= " AND (" . implode(" or ", $where_city) . ")";
   }
   if (count($where_cat) > 0) {
      $query_where .= " AND (" . implode(" or ", $where_cat) . ")";
   }
   if (count($where_child_cat) > 0) { // Sub-category filter condition
      $query_where .= " AND (" . implode(" or ", $where_child_cat) . ")";
   }
   if (count($where_attr_child) > 0) {
      $query_where .= " AND (" . implode(" or ", $where_attr_child) . ")";
   }
   if (count($where_delivery_times) > 0) {
      $query_where .= " AND (" . implode(" or ", $where_delivery_times) . ")";
   }
   if (count($where_level) > 0) {
      $query_where .= " AND (" . implode(" or ", $where_level) . ")";
   }
   if (count($where_language) > 0) {
      $query_where .= " AND (" . implode(" or ", $where_language) . ")";
   }

   if ($type == "query_where") {
      return $query_where;
   } elseif ($type == "where_path") {
      return $where_path;
   } elseif ($type == "values") {
      return $values;
   }
}

function get_proposals($filter_type)
{
   global $input;
   global $siteLanguage;
   global $db;
   global $enable_referrals;
   global $lang;
   global $dir;
   global $s_currency;
   global $login_seller_id;
   global $videoPlugin;
   global $site_url;

   $query_where = proposalsQueryWhere("query_where", $filter_type);
   $where_path = proposalsQueryWhere("where_path", $filter_type);
   $where_values = proposalsQueryWhere("values", $filter_type);

   $per_page = 16;
   if (isset($_GET['page'])) {
      $page = $input->get('page');
      if ($page == 0) {
         $page = 1;
      }
   } else {
      $page = 1;
   }

   $orderBy = isset($_REQUEST['order']) ? $_REQUEST['order'][0] : 'DESC';

   $start_from = ($page - 1) * $per_page;
   if ($filter_type == "random")
      $where_limit = " ORDER BY rand() LIMIT {$per_page} OFFSET {$start_from}";
   else
      $where_limit = " ORDER BY created_at {$orderBy} LIMIT {$per_page} OFFSET {$start_from}";

   if (isset($_SESSION['cat_child_id']) || isset($_SESSION['cat_id']) || isset($_SESSION['attr_id']) || isset($_SESSION['skill_id'])) {
      $skill_id = $_SESSION['skill_id'] ?? null;
      $attr_id = $_SESSION['attr_id'] ?? null;
      $cat_child_id = $_SESSION['cat_child_id'] ?? null;
      $cat_id = $_SESSION['cat_id'] ?? null;

      if ($skill_id) {
         $query_where .= " AND proposals.skill_title_id = :skill_id";
         $where_values[':skill_id'] = $skill_id;
      }

      if ($attr_id) {
         $query_where .= " AND proposals.proposal_attr_id = :attr_id";
         $where_values[':attr_id'] = $attr_id;
      }

      if ($cat_child_id) {
         $query_where .= " AND proposals.proposal_child_id = :cat_child_id";
         $where_values[':cat_child_id'] = $cat_child_id;
      }

      if ($cat_id) {
         $query_where .= " AND proposals.proposal_cat_id = :cat_id";
         $where_values[':cat_id'] = $cat_id;
      }
   }

   $sProposals = "SELECT DISTINCT proposals.* FROM proposals 
                   INNER JOIN sellers ON proposals.proposal_seller_id = sellers.seller_id 
                   INNER JOIN instant_deliveries ON proposals.proposal_id = instant_deliveries.proposal_id 
                   {$query_where} {$where_limit}";
   $qProposals = $db->query($sProposals, $where_values);
   if ($qProposals->rowCount() > 0) {
      // echo "hello";
      while ($row_proposals = $qProposals->fetch()) {
         $proposal_id = $row_proposals->proposal_id;
         $proposal_title = $row_proposals->proposal_title;
         $proposal_price = $row_proposals->proposal_price;
         $proposal_cat_id = $row_proposals->proposal_cat_id;
         $proposal_child_id = $row_proposals->proposal_child_id;
         $proposal_attr_id = $row_proposals->proposal_attr_id;
         $skill_title_id = $row_proposals->skill_title_id;

         // echo $proposal_child_id; 
         // echo " - ";
         // echo  $proposal_cat_id;

         if ($proposal_price == 0) {
            $get_p_1 = $db->select("proposal_packages", array("proposal_id" => $proposal_id, "package_name" => "Basic"));
            $proposal_price = $get_p_1->fetch()->price;
         }

         $proposal_img1 = getImageUrl2("proposals", "proposal_img1", $row_proposals->proposal_img1);
         $proposal_video = $row_proposals->proposal_video;
         $proposal_seller_id = $row_proposals->proposal_seller_id;
         $proposal_rating = $row_proposals->proposal_rating;
         $proposal_url = $row_proposals->proposal_url;
         $proposal_featured = $row_proposals->proposal_featured;
         $proposal_enable_referrals = $row_proposals->proposal_enable_referrals;
         $proposal_referral_money = $row_proposals->proposal_referral_money;

         $video_class = empty($proposal_video) ? "" : "video-img";

         $get_seller = $db->select("sellers", array("seller_id" => $proposal_seller_id));
         $row_seller = $get_seller->fetch();
         $seller_user_name = $row_seller->seller_user_name;
         $seller_image = getImageUrl2("sellers", "seller_image", $row_seller->seller_image);
         $seller_level = $row_seller->seller_level;
         $seller_status = $row_seller->seller_status;

         $seller_image = empty($seller_image) ? "empty-image.png" : $seller_image;

         $seller_level = @$db->select("seller_levels_meta", array("level_id" => $seller_level, "language_id" => $siteLanguage))->fetch()->title;

         $proposal_reviews = [];
         $select_buyer_reviews = $db->select("buyer_reviews", array("proposal_id" => $proposal_id));
         $count_reviews = $select_buyer_reviews->rowCount();

         while ($row_buyer_reviews = $select_buyer_reviews->fetch()) {
            $proposal_buyer_rating = $row_buyer_reviews->buyer_rating;
            array_push($proposal_reviews, $proposal_buyer_rating);
         }

         $total = array_sum($proposal_reviews);
         $average_rating = $total ? $total / count($proposal_reviews) : 0;

         $count_favorites = $db->count("favorites", array("proposal_id" => $proposal_id, "seller_id" => $login_seller_id));
         $show_favorite_class = $count_favorites == 0 ? "proposal-favorite" : "proposal-unfavorite";

         echo '<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-3">';
         require("$dir/includes/proposals.php");
         echo '</div>';
      }
   } else {
     
      if ($filter_type == "search") {
         echo "
            <div class='col-md-12'>
                <h1 class='text-center mt-4'><i class='fa fa-meh-o'></i>{$lang['search']['no_results']}</h1>
            </div>";
      } elseif ($filter_type == "category") {
         if (isset($_SESSION['cat_child_id'])) {
            echo "
                <div class='col-md-12'>
                    <h1 class='text-center mt-4'> <i class='fa fa-meh-o'></i> {$lang['sub_category']['no_results']} </h1>
                </div>";
         } else {
            echo "
                <div class='col-md-12'>
                    <h1 class='text-center mt-4'><i class='fa fa-meh-o'></i> {$lang['category']['no_results']} </h1>
                </div>";
         }
      } elseif ($filter_type == "tag") {
         if (isset($_SESSION['tag'])) {
            echo "
                <div class='col-md-12'>
                    <h1 class='text-center mt-4'><i class='fa fa-meh-o'></i> {$lang['tag_proposals']['no_results']} </h1>
                </div>";
         }
      } else {
         echo "
            <div class='col-md-12'>
                <h1 class='text-center mt-4'>
                    <i class='fa fa-meh-o'></i> {$lang['search']['no_results']}
                </h1>
            </div>";
      }
   }
}


function get_pagination($filter_type)
{
   global $db;
   global $input;
   global $lang;
   global $input;

   $query_where = proposalsQueryWhere("query_where", $filter_type);
   $where_path = proposalsQueryWhere("where_path", $filter_type);
   $where_values = proposalsQueryWhere("values", $filter_type);

   $per_page = 16;
   if (isset($_GET['page'])) {
      $page = $input->get('page');
      if ($page == 0) {
         $page = 1;
      }
   } else {
      $page = 1;
   }

   $orderBy = isset($_REQUEST['order']) ? $_REQUEST['order'][0] : 'DESC';
   if ($filter_type == "random")
      $where_limit = " ORDER BY rand()";
   else
      $where_limit = " ORDER BY created_at {$orderBy}";

   $sProposals = "SELECT DISTINCT proposals.* from proposals INNER JOIN sellers ON proposals.proposal_seller_id = sellers.seller_id INNER JOIN instant_deliveries ON proposals.proposal_id = instant_deliveries.proposal_id {$query_where} {$where_limit}";
   $qProposals = $db->query($sProposals, $where_values);
   $total_records = $qProposals->rowCount();

   if ($total_records > 0) {
      $total_pages = ceil($total_records / $per_page);
      echo "
      <li class='page-item'>
      <a class='page-link' href='?page=1&$where_path'>{$lang['pagination']['first_page']}</a>
      </li>";
      echo "<li class='page-item " . (1 == $page ? "active" : "") . "'><a class='page-link' href='?page=1&$where_path'>1</a></li>";
      $i = max(2, $page - 5);
      if ($i > 2)
         echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
      for (; $i < min($page + 6, $total_pages); $i++) {
         echo "<li class='page-item";
         if ($i == $page) {
            echo " active ";
         }
         echo "'><a href='?page=$i&$where_path' class='page-link'>" . $i . "</a></li>";
      }
      if ($i != $total_pages and $total_pages > 1) {
         echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
      }
      if ($total_pages > 1) {
         echo "<li class='page-item " . ($total_pages == $page ? "active" : "") . "'><a class='page-link' href='?page=$total_pages&$where_path'>$total_pages</a></li>";
      }
      echo "
      <li class='page-item'>
      <a class='page-link' href='?page=$total_pages&$where_path'>{$lang['pagination']['last_page']}</a>
      </li>";
   }
}

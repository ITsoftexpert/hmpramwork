  <style>
     @media (max-width:768px) {
        .heading_4 {
           font-size: 16px;
        }
        .font-size-3 {
        padding: 10px !important;
        text-align: center;
        /* border:1px solid lightgray !important; */
     }
     .heading-41{
      font-size:16px;
      text-align: center;
   }

     }
     @media (min-width:768px) {
     .heading_4 {
        font-size: 20px;
        text-align: center;
        /* padding: 25px 0px; */
        /* height:65px; */
        /* box-shadow:0px 0px 5px black, inset 0px 0px 25px #00c8d4; */
     }
     .heading-41{
      font-size:20px;
      text-align: center;
      padding:4px 0px;
      /* color:#045e5d; */
   }

     .font-size-3 {
        padding: 13px !important;
        text-align: center;
        /* border:1px solid lightgray !important; */
     }
   }
    
  </style>
 
  <div class="table-responsive box-table box-shadow-box-table">
     <h4 class="heading-41 text-align-center mt-3"> <?= $lang['manage_contacts']['my_sellers']; ?> </h4>
     <table class="table table-bordered mt-3">
        <thead>
           <tr>
              <th class="font-size-3"><?= $lang['th']['seller_name']; ?></th>
              <th class="font-size-3"><?= $lang['th']['completed_orders']; ?></th>
              <th class="font-size-3"><?= $lang['th']['amount_spent']; ?></th>
              <th class="font-size-3"><?= $lang['th']['last_order_date']; ?></th>
           </tr>
        </thead>
        <tbody>
           <?php
            $limit = 10;
            if (isset($_GET["page"])) {
               $pageNumber = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
               if (!is_numeric($pageNumber)) {
                  die('Invalid page number!');
               } //incase of invalid page number
            } else {
               $pageNumber = 1; //if there's no page number, set it to 1
            }
            $start_from =  (($pageNumber - 1) * $limit);
            $where_limit = " order by id DESC LIMIT $start_from, $limit";

            $sel_my_sellers_page =  $db->query("SELECT * FROM my_sellers WHERE buyer_id=:buyer_id", array("buyer_id" => $login_seller_id));
            $totalRows = $sel_my_sellers_page->rowCount();
            $row_my_sellers_page = $sel_my_sellers_page->fetch();

            //break records into pages
            $totalPages = ceil($totalRows / $limit);
            if ($totalRows > 0) {
               $sel_my_sellers =  $db->query("SELECT * FROM my_sellers WHERE buyer_id=:buyer_id $where_limit", array("buyer_id" => $login_seller_id));

               while ($row_my_sellers = $sel_my_sellers->fetch()) {
                  $seller_id = $row_my_sellers->seller_id;
                  $completed_orders = $row_my_sellers->completed_orders;
                  $amount_spent = $row_my_sellers->amount_spent;
                  $last_order_date = $row_my_sellers->last_order_date;
                  $select_seller = $db->select("sellers", array("seller_id" => $seller_id));
                  $row_seller = $select_seller->fetch();
                  $seller_image = getImageUrl2("sellers", "seller_image", @$row_seller->seller_image);
                  $seller_user_name = @$row_seller->seller_user_name;
            ?>
                 <tr>
                    <td>
                       <?php if (!empty($seller_image)) { ?>
                          <img src="<?= $seller_image; ?>" class="rounded-circle contact-image">
                       <?php } else { ?>
                          <img src="user_images/empty-image.png" class="rounded-circle contact-image">
                       <?php } ?>
                       <div class="contact-title">
                          <h6> <?= $seller_user_name; ?> </h6>
                          <a href="<?= $seller_user_name; ?>" target="blank" class="text-success"> User Profile </a> |
                          <a href="selling_history?seller_id=<?= $seller_id; ?>" target="blank" class="text-success"> History </a>
                       </div>
                    </td>
                    <td><?= $completed_orders; ?></td>
                    <td><?= showPrice($amount_spent); ?></td>
                    <td>
                       <?= $last_order_date; ?>
                    </td>
                    <td class="text-center">
                       <a href="conversations/message?seller_id=<?= $seller_id; ?>" target="blank" class="btn btn-success">
                          <i class="fa fa-comment"></i>
                       </a>
                    </td>
                 </tr>
              <?php
               }  // while
            } else {
               ?>
              <tr class="table-danger">
                 <td colspan="5" class="box-shadow-head3">
                    <center>
                       <h3 class='pb-4 pt-4 heading_3'>
                          <i class='fa fa-meh-o'></i> <?= $lang['manage_contacts']['no_sellers'] ?>
                       </h3>
                    </center>
                 </td>
              </tr>
           <?php } ?>
        </tbody>
     </table>
     <nav id="pagination-buyer-contacts" aria-label="Active request navigation">
        <?= pagination($limit, $pageNumber, $totalRows, $totalPages, $site_url . "/manage_contacts?my_sellers&page="); ?>
     </nav>
  </div>
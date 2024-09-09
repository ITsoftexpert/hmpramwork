<?php if (isset($_SESSION['seller_user_name'])) { ?>
   <style>
      .box-shadow2 {
         /* box-shadow: 0px 0px 5px black, inset 0px 0px 20px lightseagreen; */
      }
      /* .request-sidebar .card-body:before {
         box-shadow: -1px -1px 5px black;
      } */
   </style>
   <div class="card border-0 rounded-0 mb-5 h-1 box-shadow2">
      <div class="card-body pt-3">
         <h5><img src="../images/comp/light-bulb.png"> <?= $lang['post_request']['column_1']['title']; ?></h5>
         <p><?= $lang['post_request']['column_1']['desc']; ?></p>
         <p class="breadcrumb mb-0">
            <?= $lang['post_request']['column_1']['example']; ?>
         </p>
      </div>
   </div>
<?php } ?>

<?php if (isset($_SESSION['seller_user_name'])) { ?>
   <div class="card border-0 rounded-0 mb-0 h-2 box-shadow2">
      <div class="card-body pt-3">
         <h5><img src="../images/comp/light-bulb.png"> <?= $lang['post_request']['column_2']['title']; ?></h5>
         <p><?= $lang['post_request']['column_2']['desc']; ?></p>
         <p class="breadcrumb mb-0">
            <?= $lang['post_request']['column_2']['example']; ?>
         </p>
      </div>
   </div>
<?php } ?>

<?php if (isset($_SESSION['seller_user_name'])) { ?>
   <div class="card border-0 rounded-0 mb-0 h-3 box-shadow2">
      <div class="card-body pt-3">
         <h5><img src="../images/comp/light-bulb.png"> <?= $lang['post_request']['column_3']['title']; ?></h5>
         <p><?= $lang['post_request']['column_3']['desc']; ?></p>
      </div>
   </div>
<?php } ?>

<?php if (isset($_SESSION['seller_user_name'])) { ?>
   <div class="card border-0 rounded-0 mb-3 h-4 box-shadow2">
      <div class="card-body pt-3">
         <h5>
            <img src="../images/comp/light-bulb.png">
            <?= $lang['post_request']['column_4']['title']; ?>
         </h5>
         <p><?= $lang['post_request']['column_4']['desc']; ?></p>
      </div>
   </div>
<?php } ?>
<style>
  .new-hover-effects:hover {
    color: #00C8D4 !important;
    background-color: #E5E5E5;
  }

  @media (max-width:767px) {
    .px-3{
      padding:0px 15px !important;
    }
  
  }
  @media(max-width:420px){
    .box-shadow-navigate{
      padding:20px 16px 10px 16px;
  }
  }
  @media(min-width:421px) and (max-width:640px){
    .box-shadow-navigate{
      padding:15px 14px 10px 16px;
  }
  }
  @media(min-width:641px) and (max-width:768px){
    .box-shadow-navigate{
      padding:15px 15px 10px 18px;
  }
  }
  @media(min-width:768px) and (max-width:899px){
    .box-shadow-navigate{
      padding:15px 15px 10px 18px;
  }
  }
  @media(min-width:900px) and (max-width:1024px){
    .box-shadow-navigate{
      padding:2px 21px 10px 21px;
  }
  }
  
  @media(min-width:1025px){
    .box-shadow-navigate{
  padding:5px 24px 5px 24px;
  }
  }
</style>
 

<nav class="navbar navbar-expand-lg bg-white bb-xs-1  font-weight-bold navbar-light box-shadow-navigate">
  <a class="navbar-brand d-block d-lg-none" href="#">Sub Menu</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav justify-content-between w-100">
      <li class="active">
        <?php
        if (isset($_SESSION['seller_user_name'])) {
        ?>
          <a class="nav-link text-muted new-hover-effects" href="<?= $site_url; ?>/dashboard">Dashboard <span class="sr-only">(current)</span></a>
        <?php } else { ?>
          <a class="nav-link text-muted new-hover-effects" href="<?= $site_url; ?>">Home <span class="sr-only">(current)</span></a>
        <?php } ?>
      </li>
      <li class="dropdown">
        <a class="nav-link text-muted new-hover-effects dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?= $lang["menu"]['selling']; ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <?php if ($count_active_proposals > 0) { ?>
            <a class="dropdown-item" href="<?= $site_url; ?>/selling_orders">
              <?= $lang["menu"]['orders']; ?>
            </a>
          <?php } ?>
          <a class="dropdown-item" href="<?= $site_url; ?>/proposals/view_proposals">
            <?= $lang["menu"]['my_proposals']; ?>
          </a>
          <?php if ($count_active_proposals > 0) { ?>
            <a class="dropdown-item" href="<?= $site_url; ?>/proposals/create_coupon">
              <?= $lang["menu"]['create_coupon']; ?>
            </a>
          <?php } ?>
          <?php if ($count_active_proposals > 0) { ?>
            <a class="dropdown-item" href="<?= $site_url; ?>/requests/buyer_requests">
              <?= $lang["menu"]['buyer_requests']; ?>
            </a>
          <?php } ?>
          <a class="dropdown-item" href="<?= $site_url; ?>/revenue">
            <?= $lang["menu"]['revenues']; ?>
          </a>
          <?php if ($count_active_proposals > 0) { ?>
            <a class="dropdown-item" href="<?= $site_url; ?>/withdrawal_requests">
              <?= $lang["menu"]['withdrawal_requests']; ?>
            </a>
          <?php } ?>
        </div>
      </li>
      <li class="dropdown">
        <a class="nav-link text-muted new-hover-effects dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?= $lang["menu"]['buying']; ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?= $site_url; ?>/buying_orders">
            <?= $lang["menu"]['orders']; ?>
          </a>
          <a class="dropdown-item" href="<?= $site_url; ?>/purchases">
            <?= $lang["menu"]['purchases']; ?>
          </a>
          <a class="dropdown-item" href="<?= $site_url; ?>/favorites">
            <?= $lang["menu"]['favorites']; ?>
          </a>
        </div>
      </li>
      <li class="dropdown">
        <a class="nav-link text-muted new-hover-effects dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?= $lang["menu"]['requests']; ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?= $site_url; ?>/requests/post_request">
            <?= $lang["menu"]['post_request']; ?>
          </a>
          <a class="dropdown-item" href="<?= $site_url; ?>/requests/manage_requests">
            <?= $lang["menu"]['manage_requests']; ?>
          </a>
        </div>
      </li>
      <li class="dropdown">
        <a class="nav-link text-muted new-hover-effects dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?= $lang["menu"]['contacts']; ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?= $site_url; ?>/manage_contacts?my_buyers">
            <?= $lang["menu"]['my_buyers']; ?>
          </a>
          <a class="dropdown-item" href="<?= $site_url; ?>/manage_contacts?my_sellers">
            <?= $lang["menu"]['my_sellers']; ?>
          </a>
        </div>
      </li>
      <?php if ($enable_referrals == "yes") { ?>
        <li class="dropdown">
          <a class="nav-link text-muted new-hover-effects dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= $lang["menu"]['my_referrals']; ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="<?= $site_url; ?>/my_referrals" data-target="#referrals">
              <?= $lang["menu"]['user_referrals']; ?>
            </a>
            <a class="dropdown-item" href="<?= $site_url; ?>/proposal_referrals" data-target="#referrals">
              <?= $lang["menu"]['proposal_referrals']; ?>
            </a>
          </div>
        </li>
      <?php } ?>
      <li class="">
        <a class="nav-link text-muted new-hover-effects" href="<?= $site_url; ?>/conversations/inbox"><?= $lang["menu"]['inbox_messages']; ?> </a>
      </li>
      <li class="">
        <a class="nav-link text-muted new-hover-effects" href="<?= $site_url; ?>/notifications">Notifications </a>
      </li>
      <li class="">
        <a class="nav-link text-muted new-hover-effects" href="<?= $site_url; ?>/<?= $_SESSION['seller_user_name']; ?>"><?= $lang["menu"]['my_profile']; ?></a>
      </li>
      <li class="dropdown">
        <a class="nav-link text-muted new-hover-effects dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?= $lang["menu"]['settings']; ?>
        </a>
        <div class="dropdown-menu  dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?= $site_url; ?>/settings?profile_settings">
            <?= $lang["menu"]['profile_settings']; ?>
          </a>
          <a class="dropdown-item" href="<?= $site_url; ?>/settings?professional_settings">
            <?= $lang["menu"]['professional_settings']; ?>
          </a>
          <a class="dropdown-item" href="<?= $site_url; ?>/settings?account_settings">
            <?= $lang["menu"]['account_settings']; ?>
          </a>
        </div>
      </li>
    </ul>
  </div>
</nav>
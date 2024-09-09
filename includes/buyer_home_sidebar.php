<style>
  .box-shadow-buyer {
    /* box-shadow: 0px 0px 3px black; */
    border: 1px solid #e5e5e5 !important;
  }

  .bbw {
    border-bottom: 1px solid #fff !important;
  }

  .box-shadow-buyer1 {
    /* box-shadow: 0px 0px 3px black; */
    margin-top: 6px;
    border: 1px solid #e5e5e5 !important;
  }

  .restyling_div_alter {
    border-bottom: 1px solid white;
    border-right: 1px solid white;
    padding: 10px 0px;
    text-align: center;
    margin: 3px;
  }

  .restyling_div_alter:hover {
    /* background-color: #02b5c9;    */
    border-bottom: 1px solid green;
    border-right: 1px solid green;
  }

  .fafa-styleing {
    /* border:1px solid green; */
    font-size: 20px;
  }

  /* .background_site_color {
    background-color: #d1ecf1;
  } */

  #heading_padding_restyle {
    padding: 20px 10px 0 10px;
    font-size: 17px;
    font-weight: 500;
  }
</style>

<div class="card card1 theme-bg text-white border-0 px-sm-4 px-2  py-3 w-100 mb-4 box-shadow-buyer">
  <label class="mb-0"><?= $lang['welcome']; ?>.</label>
  <h4><?= ucfirst($login_user_name); ?></h4>
  <div class="d-flex align-items-center justify-content-space-between">
    <h5><a class="text-white" href="">@<?= ($login_user_name); ?></a></h5>
  </div>
  <div>
    <h6><span>Current Membership - &nbsp;&nbsp;</span><?php echo strtoupper($row_purchsed_plan->plan_name); ?></h6>
  </div>
</div>

<div id="buyer-sidebar" style="<?= $activeTab == "seller" ? "display: none" : "" ?>">
  <div class="card card1 background_site_color font-weight-bold border-0 px-sm-4 px-2 py-3 d-flex align-items-center justify-content-space-between w-100 flex-row mb-4 box-shadow-buyer">
    <div class="row">
      <div class="col restyling_div_alter">
        <a class="py-2 " href="<?= $site_url; ?>/requests/post_request" title="Post a Job Free">
          <i class="fa fa-plus-square fafa-styleing" aria-hidden="true"></i><br>
          Post a Job Free
        </a>
      </div>
      <div class="col restyling_div_alter">
        <a class="py-2" href="<?= $site_url; ?>/search" title="Find a Service">
          <i class="fa fa-search fafa-styleing" aria-hidden="true"></i><br>
          Find a Service
        </a>
      </div>
      <div class="w-100"></div>
      <div class="col restyling_div_alter">
        <a class="py-2" href="<?= $site_url; ?>/freelancers" title="Hire an Expert">
          <i class="fa fa-user fafa-styleing" aria-hidden="true"></i><br>
          Hire an Expert
        </a>
      </div>
      <div class="col restyling_div_alter">
        <a class="py-2" href="<?= $site_url; ?>/customer_support" title="Contact Support">
          <i class="fa fa-phone-square fafa-styleing" aria-hidden="true"></i><br>
          Contact Support
        </a>
      </div>
    </div>
  </div>
</div>

<div id="seller-sidebar" style="<?= $activeTab == "buyer" ? "display: none" : "" ?>">
  <div class="card card1 background_site_color font-weight-bold border-0 px-sm-4 px-2 py-3 d-flex align-items-center justify-content-space-between w-100 flex-row mb-4 box-shadow-buyer1">
    <div class="row">
      <div class="col restyling_div_alter">
        <a class="py-2 " href="<?= $site_url; ?>/requests/buyer_requests" title="Find job">
          <i class="fa fa-search fafa-styleing" aria-hidden="true"></i><br>
          Find job
        </a>
      </div>
      <div class="col restyling_div_alter">
        <a class="py-2" href="<?= $site_url; ?>/proposals/create_proposal" title="Post a Service">
          <i class="fa fa-plus-square fafa-styleing" aria-hidden="true"></i><br>
          Post a Service
        </a>
      </div>
      <div class="w-100"></div>
      <div class="col restyling_div_alter">
        <a class="py-2" href="<?= $site_url; ?>/proposals/view_proposals" title="Start Selling">
          <i class="fa fa-usd fafa-styleing" aria-hidden="true"></i><br>
          Start Selling
        </a>
      </div>
      <div class="col restyling_div_alter">
        <a class="py-2" href="<?= $site_url; ?>/customer_support" title="Contact Support">
          <i class="fa fa-phone-square fafa-styleing" aria-hidden="true"></i><br>
          Contact Support
        </a>
      </div>
    </div>
  </div>
</div>
<div id="buyer-sidebar" style="<?= $activeTab == "seller" ? "display: none" : "" ?>">
<?php if ($totalWeight >= 80) { ?>
  <div class="card rounded-0  mb-3 card_user ">
    <p id="heading_padding_restyle">If You Want To Become A Seller</p>
    <div class="card-body box-shadow-c-body">
      <img src="images/sales.png" class="img-fluid center-block" alt="none">
      <h4><?= $lang['sidebar']['start_selling']['title']; ?></h4>
      <p><?= $lang['sidebar']['start_selling']['desc']; ?></p>
      <button onclick="location.href='start_selling'" class="btn get_btn">
        <?= $lang['sidebar']['start_selling']['button']; ?>
      </button>
    </div>
  </div>
  <?php } else { ?>
<div class="card rounded-0  mb-3 card_user ">
    <p id="heading_padding_restyle">If You Want To Become A Seller</p>
    <div class="card-body box-shadow-c-body">
      <img src="images/sales.png" class="img-fluid center-block" alt="none">
      <h4><?= $lang['sidebar']['start_selling']['title']; ?></h4>
      <p><?= $lang['sidebar']['start_selling']['desc']; ?></p>
      <button onclick="location.href='settings?profile_settings'" class="btn get_btn">
        <?= $lang['sidebar']['start_selling']['button']; ?>
      </button>
    </div>
  </div>
  <?php } ?>
</div>
<div id="seller-sidebar" style="<?= $activeTab == "buyer" ? "display: none" : "" ?>">
  <div class="card card1 text-dark border-0  px-sm-4 px-2  py-3 d-flex align-items-center justify-content-space-between w-100 flex-row bbw box-shadow-buyer1">
    <h6 class="mb-0"> <i class="fa fa-list-ol mr-2"></i> <span>Available Proposal Points</span></h6>
    <label class="mb-0 font-weight-bold"><?php echo $row_sellers->no_of_gigs; ?></label>
  </div>

  <div class="card card1  border-0  px-sm-4 px-2  py-3 d-flex align-items-center justify-content-space-between w-100 flex-row bbw box-shadow-buyer1">
    <h6 class="mb-0"> <i class="fa fa-hand-o-up mr-2"></i> <span>Available Bids Credit</span></h6>
    <label class="mb-0 font-weight-bold"><?php echo $row_sellers->bids_per_month; ?></label>
  </div>

  <div class="card card1 border-0  px-sm-4 px-2  py-3 d-flex align-items-center justify-content-space-between w-100 flex-row bbw box-shadow-buyer1">
    <h6 class="mb-0"> <i class="fa fa-plus mr-2"></i> <span>No of skills to be listed</span></h6>
    <label class="mb-0 font-weight-bold"><?php echo $row_sellers->skills; ?></label>
  </div>

  <div class="card card1 border-0 mb-2 px-sm-4 px-2  py-3 d-flex align-items-center justify-content-space-between w-100 flex-row bbw box-shadow-buyer1">
    <h6 class="mb-0"> <i class="fa fa-dollar mr-2"></i> <span>Order Processing Fee</span></h6>
    <label class="mb-0 font-weight-bold"><?php echo $row_sellers->comission_per_sale; ?>%</label>
  </div>

  <div class="card card1 border-0 mb-2 px-sm-4 px-2  py-3 d-flex align-items-center justify-content-space-between w-100 flex-row bbw box-shadow-buyer1">
    <h6 class="mb-0"> <i class="fa fa-credit-card mr-2"></i> <span>Number of portfolios remaining</span></h6>
    <label class="mb-0 font-weight-bold"><?php echo $row_sellers->create_porfolio; ?></label>
  </div>

  <div class="card card1 border-0  px-sm-4 px-2  py-3 d-flex align-items-center justify-content-space-between w-100 flex-row bbw box-shadow-buyer1">
    <h6 class="mb-0"> <i class="fa fa-bookmark mr-2"></i> <span>Can bookmark projects</span></h6>
    <label class="mb-0 font-weight-bold"><?php echo $row_sellers->project_bookmarks; ?></label>
  </div>
  <div class="card card1 border-0 px-sm-4 px-2  py-3 box-shadow-buyer1">
    <a class="" href="<?= $site_url ?>/membership_subs">
      <div class="d-flex align-items-center justify-content-space-between">
        <h6 class="pr-4">Accelerate your career by upgrading to a membership today. Get started <i class="fa fa-arrow-right"></i></h6>
        <svg fill="#fff" width="50px" x="0px" y="0px" viewBox="0 0 490 490" style="enable-background:new 0 0 490 490;">
          <path d="M122.5,34.031L0,245.001l122.5,210.968h245L490,245.001L367.5,34.031H122.5z M287.558,318.293h-85.116l-42.557-73.292l42.557-73.292h85.116l42.557,73.292L287.558,318.293z" />
        </svg>
      </div>
    </a>  
  </div>
</div>

<div id="seller-sidebar" style="<?= $activeTab == "buyer" ? "display: none" : "" ?>">
  <div class="card card1 text-dark border-0 px-4 py-3 mb-4 box-shadow-buyer1">
    <div class="d-flex align-items-center justify-content-space-between">
      <h6>Set up your account</h6>
      <h6><?= $totalWeight ?>%</h6>
    </div>
    <div class="progress" style="height: 10px; border-radius: 1rem;">
      <div class="progress-bar" role="progressbar" style="width: <?= $totalWeight ?>%;" aria-valuenow="<?= $totalWeight ?>" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
  </div>
  <?php if ($profileWeight == 0) { ?>
    <div class="card card1 border-0  px-sm-4 px-2  py-3 d-flex align-items-center justify-content-space-between w-100 flex-row mb-4 box-shadow-buyer1">
      <a href="<?= $site_url ?>/settings?profile_settings">
        <h6 class="mb-0"> <i class="fa fa-user mr-3"></i> <span>Complete your profile</span></h6>
      </a>
      <label class="mb-0 font-weight-bold">+40%</label>
    </div>
  <?php
  }
  if ($professionalWeight == 0) {
  ?>
    <div class="card card1 border-0  px-sm-4 px-2  py-3 d-flex align-items-center justify-content-space-between w-100 flex-row mb-4 box-shadow-buyer1">
      <a href="<?= $site_url ?>/settings?professional_settings">
        <h6 class="mb-0"> <i class="fa fa-briefcase mr-3"></i> <span>Complete your professional</span></h6>
      </a>
      <label class="mb-0 font-weight-bold">+40%</label>
    </div>
  <?php
  }
  if ($accountWeight == 0) {
  ?>
    <div class="card card1 border-0  px-sm-4 px-2  py-3 d-flex align-items-center justify-content-space-between w-100 flex-row mb-4 box-shadow-buyer1">
      <a href="<?= $site_url ?>/settings?account_settings">
        <h6 class="mb-0"> <i class="fa fa-money mr-3"></i> <span>Complete your account</span></h6>
      </a>
      <label class="mb-0 font-weight-bold">+20%</label>
    </div>
  <?php } ?>
  <!-- <div class="card card1 bg-white theme-text  px-0 py-3 bl-xs-0 br-xs-0 rounded-0 mb-4">
    <h3>Try Corporate Membership for FREE</h3>
  </div> -->
</div>
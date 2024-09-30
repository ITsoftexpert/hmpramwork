<?php
@session_start();
require_once("includes/db.php");
if (!isset($_SESSION['seller_user_name'])) {
  echo "<script>window.open('login','_self')</script>";
}
?>

<style>
  @media (max-width:768px) {
    .text-align-center-1 {
      text-align: center;
      margin: auto;
    }

    .full-width-1 {
      /* border: 1px solid blue; */
      width: 100%;
      display: flex;
      /* color: #2e8780; */
      margin-top: -10px;
      /* font-size: ; */
    }

    .full-width-2 {
      /* border: 1px solid blue; */
      width: 100%;
      display: flex;
      margin-top: 1.5rem;
      /* color: #2e8780; */
      /* font-size: ; */
    }

    .text-align-center-2 {
      text-align: center;
      margin: auto;
    }

    .deactivation-title {
      color: #3c6263 !important;
      font-weight: 400;
      font-size: 16px;
      text-align: center;
      margin: auto;
    }


  }

  .box-shadow-acc-set {
    /* box-shadow:0px 0px 5px gray ,inset 0px 0px 15px lightgray; */
    border: 1px solid gray;
    height: 45px !important;
    padding-top: 7px;
    font-size: 15px;
  }

  .box-shadow-submit-btnn {
    padding: 8px 10px;
  }

  .box-shadow-de-acc {
    /* box-shadow: 2px 2px 5px black, inset 0px 0px 15px #ab0202; */
    height: 45px !important;
    width: 100%;
    /* margin-right: 0.25rem; */
  }

  .submit_paypal_email_btn_style {
    width: 100%;
    text-align: end;
  }
</style>
<?php
if ($enable_paypal == "yes") {
  if (isset($_POST['submit_paypal_email'])) {

    $seller_paypal_email = strip_tags($input->post('seller_paypal_email'));

    $update_seller = $db->update("sellers", ["seller_paypal_email" => $seller_paypal_email], ["seller_id" => $login_seller_id]);

    if ($update_seller) {
      $weightnForm = ['account_weight' => 20, 'seller_id' => $seller_id];
      if ($totalWeight > 0)
        $db->update("seller_profile_weights", $weightnForm, ["seller_id" => $seller_id]);
      else
        $db->insert("seller_profile_weights", $weightnForm);
      echo "
        <script>
          swal({
            type: 'success',
            text: 'PayPal email updated successfully!',
            timer: 3000,
            onOpen: function(){
              swal.showLoading()
            }
          }).then(function(){
            window.open('settings?account_settings','_self')
          });
        </script>";
    }
  }
?>

  <h5 class="mb-4 full-width-1"> <span class="text-align-center-1"><?= $lang['settings']['paypal_heading']; ?></span> </h5>
  <form method="post" class="clearfix mb-3">
    <div class="form-group row">
      <div class="col-md-12">
        <label class=""> <?= $lang['label']['paypal_email']; ?> </label>
        <input type="email" name="seller_paypal_email" value="<?= $login_seller_paypal_email; ?>" placeholder="<?= $lang['placeholder']['paypal_email']; ?> address" class="form-control box-shadow-acc-set" required />
      </div>
    </div>
    <div class="submit_paypal_email_btn_style">
      <button type="submit" name="submit_paypal_email" class="btn btn-success box-shadow-submit-btnn">
        <i class="fa fa-envelope-o"></i> &nbsp; <?= $lang['button']['change_paypal_email']; ?>
      </button>
    </div>
  </form>
<?php
}

if ($paymentGateway == 1) {
  include("plugins/paymentGateway/account_settings.php");
}
?>

<?php if ($enable_payoneer == 1) { ?>

  <hr>
  <h5 class="mb-4"> <?= $lang['settings']['payoneer_heading']; ?> </h5>
  <form method="post" class="clearfix mb-3">
    <div class="form-group row">
      <label class="col-md-4 col-form-label"> <?= $lang['label']['payoneer_email']; ?> </label>
      <div class="col-md-8">
        <input type="email" name="seller_payoneer_email" value="<?= $login_seller_payoneer_email; ?>" placeholder="Enter payoneer email" class="form-control box-shadow-acc-set" required>
      </div>
    </div>
    <button type="submit" name="submit_payoneer_email" class="btn btn-success <?= $floatRight ?>">
      <?= $lang['button']['change_payoneer_email']; ?>
    </button>
  </form>
  <?php
  if (isset($_POST['submit_payoneer_email'])) {
    $seller_payoneer_email = strip_tags($input->post('seller_payoneer_email'));
    $update_seller = $db->update("sellers", array("seller_payoneer_email" => $seller_payoneer_email), array("seller_id" => $login_seller_id));
    if ($update_seller) {
      $weightnForm = ['account_weight' => 20, 'seller_id' => $seller_id];
      if ($totalWeight > 0)
        $db->update("seller_profile_weights", $weightnForm, ["seller_id" => $seller_id]);
      else
        $db->insert("seller_profile_weights", $weightnForm);
      echo "<script>
    swal({
      type: 'success',
      text: 'Payoneer email updated successfully!',
      timer: 3000,
      onOpen: function(){
        swal.showLoading()
      }
    }).then(function(){
        // Read more about handling dismissals
        window.open('settings?account_settings','_self')
    });</script>";
    }
  }
  ?>

<?php } ?>

<?php if ($enable_dusupay == "yes") { ?>

  <hr>
  <h5 class="mb-4"> <?= $lang['settings']['mobile_money_heading']; ?> </h5>
  <form method="post" class="clearfix mb-3">
    <div class="form-group row">
      <label class="col-md-4 col-form-label"> <?= $lang['label']['account_number']; ?> </label>
      <div class="col-md-8">
        <input type="text" name="m_account_number" value="<?= $login_seller_account_number; ?>" placeholder="<?= $lang['placeholder']['account_number']; ?>" class="form-control box-shadow-acc-set" required>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-md-4 col-form-label"> <?= $lang['label']['account_owner']; ?> </label>
      <div class="col-md-8">
        <input type="text" name="m_account_name" value="<?= $login_seller_account_name; ?>" placeholder="<?= $lang['placeholder']['account_owner']; ?>" class="form-control box-shadow-acc-set" required>
      </div>
    </div>
    <button type="submit" name="update_mobile_money" class="btn btn-success <?= $floatRight ?>">
      <?= $lang['button']['update_mobile_money']; ?>
    </button>
  </form>
<?php
  if (isset($_POST['update_mobile_money'])) {
    $m_account_number = strip_tags($input->post('m_account_number'));
    $m_account_name = strip_tags($input->post('m_account_name'));
    $update_seller = $db->update("sellers", array("seller_m_account_number" => $m_account_number, "seller_m_account_name" => $m_account_name), array("seller_id" => $login_seller_id));
    if ($update_seller) {
      $weightnForm = ['account_weight' => 20, 'seller_id' => $seller_id];
      if ($totalWeight > 0)
        $db->update("seller_profile_weights", $weightnForm, ["seller_id" => $seller_id]);
      else
        $db->insert("seller_profile_weights", $weightnForm);
      echo "<script>
      swal({
        type: 'success',
        text: 'Mobile Money Updated Successfully!',
        timer: 3000,
        onOpen: function(){
          swal.showLoading()
        }
      }).then(function(){
        window.open('settings?account_settings','_self')
      });
      </script>";
    }
  }
}

?>
<hr>
<h5 class="mb-4 full-width-2"> <span class="text-align-center-2"><?= $lang['settings']['bitcoin_wallet_heading']; ?></span> </h5>
<form method="post" class="clearfix mb-3">
  <div class="form-group row">
    <div class="col-md-12">
      <label class=""> <?= $lang['label']['wallet_address']; ?> </label>
      <input type="text" name="seller_wallet" value="<?= $login_seller_wallet; ?>" placeholder="<?= $lang['placeholder']['wallet_address']; ?>" class="form-control box-shadow-acc-set" />
      <small class="text-danger"><?= $lang['note']['wallet_address']; ?></small>
    </div>
  </div>
  <div class="submit_paypal_email_btn_style">
    <button type="submit" name="submit_wallet" class="btn btn-success box-shadow-submit-btnn">
      <i class="fa fa-pencil-square-o"></i> &nbsp;<?= $lang['button']['update_wallet_address']; ?>
    </button>
  </div>
</form>
<?php
if (isset($_POST['submit_wallet'])) {
  $seller_wallet = $input->post('seller_wallet');
  $update_seller = $db->update("sellers", array("seller_wallet" => $seller_wallet), array("seller_id" => $login_seller_id));
  if ($update_seller) {
    $weightnForm = ['account_weight' => 20, 'seller_id' => $seller_id];
    if ($totalWeight > 0)
      $db->update("seller_profile_weights", $weightnForm, ["seller_id" => $seller_id]);
    else
      $db->insert("seller_profile_weights", $weightnForm);
    echo "<script>
    swal({
      type: 'success',
      text: 'Wallet Address updated successfully!',
      timer: 3000,
      onOpen: function(){
        swal.showLoading()
      }
    }).then(function(){
        window.open('settings?account_settings','_self')
    });
  </script>";
  }
}
?>
<hr>
<h5 class="mb-4 full-width-2"> <span class="text-align-center-2"><?= $lang['settings']['real_time_notifications']; ?> </span></h5>
<form method="post" class="clearfix">
  <div class="form-group row mb-3">
    <div class="col-md-6">
      <label class=""> <?= $lang['label']['enable_sound']; ?> </label>
      <select name="enable_sound" class="form-control box-shadow-acc-set">
        <?php if ($login_seller_enable_sound == "yes") { ?>
          <option value="yes"> Yes </option>
          <option value="no"> No </option>
        <?php } elseif ($login_seller_enable_sound == "no") { ?>
          <option value="no"> No </option>
          <option value="yes"> Yes </option>
        <?php } ?>
      </select>
    </div>

    <div class="col-md-6">
      <label class=""> <?= $lang['label']['enable_notifications']; ?> </label>
      <select name="enable_notifications" class="form-control box-shadow-acc-set">
        <?php if ($login_seller_enable_notifications == "1") { ?>
          <option value="1"> Yes </option>
          <option value="0"> No </option>
        <?php } elseif ($login_seller_enable_notifications == "0") { ?>
          <option value="0"> No </option>
          <option value="1"> Yes </option>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="submit_paypal_email_btn_style">
    <button type="submit" name="update_sound" class="btn btn-success mt-1 box-shadow-submit-btnn">
      <i class="fa fa-pencil-square-o"></i> &nbsp;<?= $lang['button']['update_changes']; ?>
    </button>
  </div>
</form>
<?php
if (isset($_POST['update_sound'])) {
  $enable_sound = strip_tags($input->post('enable_sound'));
  $enable_notifications = strip_tags($input->post('enable_notifications'));
  $update_seller = $db->update("sellers", ["enable_sound" => $enable_sound, "enable_notifications" => $enable_notifications], ["seller_id" => $login_seller_id]);
  if ($update_seller) {
    echo "<script>
      swal({
        type: 'success',
        text: 'Realtime notifications settings updated successfully.',
        timer: 2000,
        onOpen: function(){
          swal.showLoading()
        }
      }).then(function(){
        window.open('settings?account_settings','_self')
      });
      </script>";
  }
}
?>
<hr>
<h5 class="mb-4 full-width-2"> <span class="text-align-center-2"><?= $lang['settings']['change_pass']; ?> </span></h5>
<?php
$form_errors = Flash::render("change_pass_errors");
$form_data = Flash::render("form_data");
if (is_array($form_errors)) {
?>
  <div class="alert alert-danger"><!--- alert alert-danger Starts --->
    <ul class="list-unstyled mb-0">
      <?php $i = 0;
      foreach ($form_errors as $error) {
        $i++; ?>
        <li class="list-unstyled-item"><?= $i ?>. <?= ucfirst($error); ?></li>
      <?php } ?>
    </ul>
  </div><!--- alert alert-danger Ends --->
<?php } ?>
<form method="post" class="clearfix mb-3">
  <div class="form-group row">
    <div class="col-md-4">
      <label class=""> <?= $lang['label']['enter_old_pass']; ?> </label>
      <input type="text" name="old_pass" class="form-control box-shadow-acc-set" required="" />
    </div>

    <div class="col-md-4">
      <label class=""> <?= $lang['label']['enter_new_pass']; ?> </label>
      <input type="text" name="new_pass" class="form-control box-shadow-acc-set" required="" />
    </div>

    <div class="col-md-4">
      <label class=""> <?= $lang['label']['confirm_new_pass']; ?> </label>
      <input type="text" name="new_pass_again" class="form-control box-shadow-acc-set" required="" />
    </div>
  </div>


  <div class="submit_paypal_email_btn_style">
    <button type="submit" name="change_password" class="btn btn-success box-shadow-submit-btnn">
      <i class="fa fa-unlock-alt"></i> &nbsp;<?= $lang['button']['change_password']; ?>
    </button>
  </div>
</form>
<!-- two factor varification -->
<style>
  .toggle_switch_button {
    position: relative;
    display: inline-block;
    width: 55px;
    height: 30px;
  }

  .toggle_switch_button input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .toggle_slider_button {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
  }

  .toggle_slider_button:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
  }

  input:checked+.toggle_slider_button {
    background-color: #2196F3;
  }

  input:checked+.toggle_slider_button:before {
    transform: translateX(26px);
  }

  .toggle_slider_button.round_span_style {
    border-radius: 34px;
  }

  .toggle_slider_button.round_span_style:before {
    border-radius: 50%;
  }

  .style_div_two_factor_verification {
    border: 1px solid lightgrey;
    display: flex;
    padding: 15px 0 8px 10px;
    border-radius: 5px;
  }

  .one_partion_2fa {
    width: 90%;
    padding: 6px 0;
  }

  .two_partion_2fa {
    width: 10%;
    text-align: center;
  }

  .responseMessage_style {
    padding: 10px;
    width: fit-content;
    margin-top: 10px;
    border-radius: 5px;
    display: none;
    color: white;
    font-size: 14px;
  }
</style>
<hr>
<h5>Two Factor Verification</h5>
<div class="style_div_two_factor_verification">
  <div class="one_partion_2fa">
    <h6>Verification Enable/Disable</h6>
    <div id="responseMessage" class="responseMessage_style"></div>

  </div>
  <div class="two_partion_2fa"> <label class="toggle_switch_button">
      <input type="checkbox" id="enable_2fa">
      <span class="toggle_slider_button round_span_style"></span>
    </label>

  </div>

</div>


<script>
  $(document).ready(function() {
    $('#enable_2fa').change(function() {
      var isChecked = $(this).is(':checked'); // Check if the checkbox is checked
      var status = isChecked ? 'activate' : 'deactivate'; // Determine the status

      $.ajax({
        url: 'update_2fa_status', // PHP script to handle the request
        type: 'POST',
        data: {
          status: status
        },
        success: function(response) {
          var bgColor = isChecked ? '#28a745' : '#ffc107'; // Green for activate, Yellow for deactivate

          $('#responseMessage').css('background-color', bgColor).html(response).fadeIn().delay(3000).fadeOut();
        }
      });
    });
  });
</script>



<!-- two factor varification -->

<?php
if (isset($_POST['change_password'])) {
  $rules = array(
    "old_pass" => "required",
    "new_pass" => "required",
    "new_pass_again" => "required"
  );
  $messages = array("old_pass" => "Old Password Is Required.", "new_pass" => "New Password Is Required.", "new_pass_again" => "New Password Again Is Required.");
  $val = new Validator($_POST, $rules, $messages);
  if ($val->run() == false) {
    Flash::add("change_pass_errors", $val->get_all_errors());
    Flash::add("form_data", $_POST);
    echo "<script> window.open('settings?account_settings','_self');</script>";
  } else {
    $old_pass = $input->post('old_pass');
    $new_pass = $input->post('new_pass');
    $new_pass_again = $input->post('new_pass_again');
    $get_seller = $db->select("sellers", array("seller_id" => $login_seller_id));
    $row_seller = $get_seller->fetch();
    $hash_password = $row_seller->seller_pass;
    $decrypt_password = password_verify($old_pass, $hash_password);
    if ($decrypt_password == 0) {
      echo "<script>
      swal({
        type: 'warning',
        html: $('<div>').addClass('some-class').text('Your old password is invalid, please try again!.'),
        animation: false,
        customClass: 'animated tada'
      });
      </script>";
    } else {
      if ($new_pass != $new_pass_again) {
        echo "<script>alert('Your New Password dose not match.');</script>";
      } else {
        $encrypted_password = password_hash($new_pass, PASSWORD_DEFAULT);
        $update_pass = $db->update("sellers", array("seller_pass" => $encrypted_password), array("seller_id" => $login_seller_id));
        echo "<script>
        swal({
          type: 'success',
          text: 'Password updated successfully, login with your new password.',
          timer: 3000,
          onOpen: function(){
            swal.showLoading();
          }
        }).then(function(){
          window.open('logout','_self')
        });
        </script>";
      }
    }
  }
}
?>
<hr>
<h5 class="mb-2 full-width-2"> <span class="text-align-center-2"><?= $lang['settings']['account_deactivation']; ?></span> </h5>
<ul class="list-unstyled <?= $floatRight ?>">
  <li class="lead mb-2 full-width-2">
    <span class="deactivation-title"><?= $lang['settings']['what_happens']['title']; ?></span>
  </li>
  <li><i class="fa fa-hand-o-right"></i> <?= $lang['settings']['what_happens']['1']; ?> </li>
  <li><i class="fa fa-hand-o-right"></i> <?= $lang['settings']['what_happens']['2']; ?> </li>
  <li><i class="fa fa-hand-o-right"></i> <?= $lang['settings']['what_happens']['3']; ?> </li>
  <li><i class="fa fa-hand-o-right"></i> <?= $lang['settings']['what_happens']['4']; ?> </li>
</ul>
<div class="clearfix"></div>
<form method="post">
  <?php if (!$current_balance == 0) { ?>
    <div class="form-group"><!-- form-group Starts -->
      <h5 class="pt-3 pb-3"> <?= $lang['settings']['please_withdraw']; ?> </h5>
    </div><!-- form-group Ends -->
    <button type="submit" name="deactivate_account" disabled class="btn btn-danger box-shadow-de-acc <?= $floatRight ?>">
      <i class="fa fa-frown-o"></i> &nbsp; <?= $lang['button']['deactivate_account']; ?>
    </button>
  <?php } elseif ($current_balance == 0) { ?>
    <div class="form-group">
      <label> Why Are You Leaving? </label>
      <select name="deactivate_reason" class="form-control box-shadow-acc-set" required>
        <option value=""> <?= $lang['settings']['reason']['1']; ?> </option>
        <option> <?= $lang['settings']['reason']['2']; ?> </option>
        <option> <?= $lang['settings']['reason']['3']; ?> </option>
        <option> <?= $lang['settings']['reason']['4']; ?> </option>
        <option> <?= $lang['settings']['reason']['5']; ?> </option>
        <option> <?= $lang['settings']['reason']['6']; ?> </option>
        <option> <?= $lang['settings']['reason']['7']; ?> </option>
        <option> <?= $lang['settings']['reason']['8']; ?> </option>
        <option> <?= $lang['settings']['reason']['9']; ?> </option>
        <option> <?= $lang['settings']['reason']['10']; ?> </option>
      </select>
    </div>
    <div class="submit_paypal_email_btn_style">
      <button type="submit" name="deactivate_account" class="btn btn-danger box-shadow-submit-btnn">
        <i class="fa fa-frown-o"></i> &nbsp; <?= $lang['button']['deactivate_account']; ?>
      </button>
    </div>
  <?php } ?>
</form>
<?php
if (isset($_POST['deactivate_account'])) {

  $update_seller = $db->update("sellers", array("seller_status" => 'deactivated', "acc_status" => 'deactivated'), array("seller_id" => $login_seller_id));
  if ($update_seller) {

    $sel_orders = $db->select("orders", array("seller_id" => $login_seller_id, "order_active" => "yes"));
    while ($row_orders = $sel_orders->fetch()) {
      $order_id = $row_orders->order_id;
      $seller_id = $row_orders->seller_id;
      $buyer_id = $row_orders->buyer_id;
      $order_price = $row_orders->order_price;
      $notification_date =  date("F d, Y");
      $purchase_date = date("F d, Y");
      $insert_notification = $db->insert("notifications", array("receiver_id" => $buyer_id, "sender_id" => $seller_id, "order_id" => $order_id, "reason" => "order_cancelled", "date" => $notification_date, "status" => "unread"));

      /// sendPushMessage Starts
      $notification_id = $db->lastInsertId();
      sendPushMessage($notification_id);
      /// sendPushMessage Ends

      $insert_purchase = $db->insert("purchases", array("seller_id" => $buyer_id, "order_id" => $order_id, "amount" => $order_price, "date" => $purchase_date, "method" => "order_cancellation"));
      $update_balance = $db->update("seller_accounts", array("used_purchases" => "used_purchases-$order_price", "current_balance" => "current_balance+$order_price"), array("seller_id" => $buyer_id));
      $update_orders = $db->update("orders", array("order_status" => 'cancelled', "order_active" => 'no'), array("order_id" => $order_id));
      $db->update("milestone", array("milestone_status" => 'cancelled', "order_id" => $order_id), array("milestone_id" => $milestone_id));
    }
    // $delete_proposals = $db->delete("proposals",array("proposal_seller_id" => $seller_id));
    $update_proposals = $db->update("proposals", array("proposal_status" => 'deleted'), array("proposal_seller_id" => $seller_id));
    unset($_SESSION['seller_user_name']);
    echo "<script>
      swal({
        type: 'success',
        text: 'Your account has been deactivated successfully. Goodbye!',
        timer: 3000,
        onOpen: function(){
        swal.showLoading()
        }
      }).then(function(){
        window.open('settings?profile_settings','');
        console.log('Account deactivated successfully');
      })
      </script>";
  }
}
?>
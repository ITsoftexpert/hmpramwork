<?php

session_start();
require_once("includes/db.php");
require_once("functions/mailer.php");
require_once("social-config.php");

if (isset($_SESSION['seller_user_name'])) {
  $login_seller_user_name = $_SESSION['seller_user_name'];
  $select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
  $row_login_seller = $select_login_seller->fetch();
  $login_seller_id = $row_login_seller->seller_id;
  $login_seller_email = $row_login_seller->seller_email;
  $login_seller_user_name = $row_login_seller->seller_user_name;
}

$recaptcha_site_key = $row_general_settings->recaptcha_site_key;
$recaptcha_secret_key = $row_general_settings->recaptcha_secret_key;

if ($lang_dir == "right") {
  $floatRight = "float-right";
} else {
  $floatRight = "float-left";
}

// $recaptcha_site_key = "";
// $recaptcha_secret_key = "";

$orderNumber = isset($_GET['order_number']) ? $_GET['order_number'] : "";
$enquiryId = isset($_GET['enquiry_id']) ? $_GET['enquiry_id'] : "";
$userType = "";
if ($orderNumber && isset($_SESSION['seller_user_name'])) {
  $qOrder = $db->select("orders", array("order_number" => $orderNumber));
  $oOrder = $qOrder->fetch();
  $sellerId = $oOrder->seller_id;
  $buyerId = $oOrder->buyer_id;
  if ($login_seller_id == $sellerId)
    $userType = "Seller";
  if ($login_seller_id == $buyerId)
    $userType = "Buyer";
}
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
  <title><?= $site_name; ?> - <?= $lang["titles"]["customer_support"]; ?></title>
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
  <link href="styles/sweat_alert.css" rel="stylesheet">
  <script type="text/javascript" src="js/ie.js"></script>
  <script type="text/javascript" src="js/sweat_alert.js"></script>
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/sweat_alert.js"></script>
  <?php if (!empty($site_favicon)) { ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
  <?php } ?>
  <style>
    .fixed {
      position: fixed;
      transition: all 3s linear;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 100;
    }

    .box-shadow-7 {
      /* box-shadow: 0px 0px 5px black,inset 0px 0px 35px #00c8d4; */
      background-color: white;
      /* text-shadow: 0px 0px 2px black; */
    }

    .box-shadow-7body {
      /* box-shadow: 0px 0px 5px black,inset 0px 0px 35px gray; */
      background-color: white;
      /* text-shadow: 0px 0px 2px black; */
    }

    .box-shadow-7stag {
      /* box-shadow: 0px 0px 5px black,inset 0px 0px 10px lightseagreen; */
      background-color: white;
      /* text-shadow: 0px 0px 2px black; */
    }

    .padding-alter13 {
      padding: 0px 2rem;
    }

    .margin-top-120 {
      margin-top: 30px;
      /* margin-bottom: 50px; */
    }

    @media (max-width:768px) {
      .full-width {
        width: 100%;
        display: flex;
        /* color: #256156; */
        font-size: 20px !important;
      }

      .text-align-center {
        text-align: center;
        margin: auto;
      }

      .margin-top-120 {
        margin-top: 30px;
        /* margin-bottom: 50px; */
      }

      .padding-alter13 {
        padding: 0px 15px;
      }
    }

    @media(max-width:639px) {
      .padding-alter13 {
        padding: 0px 1px;
      }
    }

    @media(min-width:1024px) {
      .margin-top-120 {
        margin-top: 0px;
        /* margin-bottom: 50px; */
      }
    }
  </style>
</head>

<body class="is-responsive">

  <?php require_once("includes/header.php"); ?>

  <div class="container-fluid pb-4 margin-top-120"><!-- Container starts -->
    <div class="row padding-alter13">
      <?php
      $get_contact_support = $db->select("contact_support");
      $row_contact_support = $get_contact_support->fetch();
      $contact_email = $row_contact_support->contact_email;
      $get_meta = $db->select("contact_support_meta", array('language_id' => $siteLanguage));
      $row_meta = $get_meta->fetch();
      $contact_heading = $row_meta->contact_heading;
      $contact_desc = $row_meta->contact_desc;
      ?>
      <div class="col-md-12">
        <?php if (!isset($_SESSION['seller_user_name'])) { ?>
          <div class="alert alert-warning rounded-0">
            <p class="lead mt-1 mb-1 text-center">
              <strong>Sorry!</strong> You can't submit a support request without logging in first. If you have a general question, please email us at <?= $contact_email; ?>.
            </p>
          </div>
        <?php } ?>
      </div>
    </div>
    <div class="row customer-support padding-alter13" style="<?= ($lang_dir == "right" ? 'direction: rtl;' : '') ?>">
      <div class="col-md-12">
        <div class="card mt-4 mb-4">
          <div class="card-header text-center make-white pt-4  box-shadow-7">
            <h2 class="full-width"><span class="text-align-center"><?= $enquiryId == 1 ? 'Raise a dispute' : $contact_heading; ?></span></h2>
            <p class="text-muted pt-1"><?= $contact_desc; ?></p>
          </div>
          <div class="card-body box-shadow-7body">
            <center>
              <form class="col-md-8 contact-form mt-4 mb-5" action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="form-group">
                  <?php if ($enquiryId == 1) { ?>
                    <input type="hidden" name="enquiry_type" value="<?= $enquiryId ?>" />
                  <?php } else { ?>
                    <label class="<?= $floatRight ?>"><?= $lang['label']['select_enquiry']; ?></label>
                    <select name="enquiry_type" class="form-control select_tag box-shadow-7stag" required>
                      <option value="" url="customer_support"><?= $lang['label']['select_enquiry2']; ?></option>
                      <?php
                      $get_enquiry_types = $db->select("enquiry_types");
                      while ($row_enquiry_types = $get_enquiry_types->fetch()) {
                        $enquiry_id = $row_enquiry_types->enquiry_id;
                        $enquiry_title = $row_enquiry_types->enquiry_title;
                        echo "<option value='$enquiry_id' " . (@$_GET['enquiry_id'] == $enquiry_id ? "selected" : "") . " url='customer_support?enquiry_id=$enquiry_id'>
                        $enquiry_title
                        </option>";
                      }
                      ?>
                    </select>
                  <?php } ?>
                </div>
                <?php if (isset($_GET['enquiry_id'])) { ?>
                  <div class="form-group">
                    <label class="<?= $floatRight ?>"><?= $lang['label']['subject']; ?></label>
                    <input type="text" class="form-control" name="subject" required="">
                  </div>
                  <div class="form-group">
                    <label class="<?= $floatRight ?>"><?= $lang['label']['message']; ?></label>
                    <textarea class="form-control" rows="6" name="message" required></textarea>
                  </div>
                  <?php if ($_GET['enquiry_id'] == 1 or $_GET['enquiry_id'] == 2) { ?>
                    <div class="form-group">
                      <label class="<?= $floatRight ?>"><?= $lang['label']['order_number']; ?></label>
                      <input type="text" class="form-control" name="order_number" required="" value="<?= $orderNumber ?>" maxlength="15">
                    </div>
                    <div class="form-group">
                      <label class="<?= $floatRight ?>"><?= $lang['label']['user_role']; ?></label>
                      <select name="user_role" class="form-control" required>
                        <option value="" class="hidden">Select user role</option>
                        <option <?= $userType == 'Buyer' ? 'selected' : '' ?>>Buyer</option>
                        <option <?= $userType == 'Seller' ? 'selected' : '' ?>>Seller</option>
                      </select>
                    </div>
                  <?php } ?>
                  <div class="form-group">
                    <label class="<?= $floatRight ?>"><?= $lang['label']['attachment']; ?></label>
                    <input type="file" class="form-control" name="file" id="file" required>
                    <p id="fileInfo" class="float-left"></p>
                  </div>


                  <script>
                    document.getElementById('file').addEventListener('change', function() {
                      var file = this.files[0];
                      var fileInfo = "File type: " + file.type + "<br>" +
                        "File size: " + file.size + " bytes";
                      document.getElementById('fileInfo').innerHTML = fileInfo;
                    });
                  </script>

                  <?php if (!empty($recaptcha_site_key) and !empty($recaptcha_secret_key)) { ?>
                    <div class="form-group">
                      <label><?= $lang['label']['google_recaptch']; ?></label>
                      <div class="g-recaptcha" data-sitekey="<?= $recaptcha_site_key; ?>"></div>
                    </div>
                  <?php } ?>
                  <div class="text-center">
                    <button class="btn btn-success btn-lg" name="submit" type="submit">
                      <i class="fa fa-paper-plane"> <?= $lang['button']['submit_request2']; ?></i>
                    </button>
                  </div>
                <?php } ?>
              </form>
            </center>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Container ends -->
  <?php
  if (isset($_POST['submit'])) {
    if (!isset($_SESSION['seller_user_name'])) {
      echo "
   <script>
  swal({
    type: 'warning',
    text: 'Opps! You need to be logged in to submit support requests.',
    timer: 6000,
      onOpen: function(){
    swal.showLoading()
    }
    }).then(function(){
      // Read more about handling dismissals
      window.open('login.php','_self')
    });
  </script>
  ";
      exit();
    } else {

      if (!empty($recaptcha_site_key) and !empty($recaptcha_secret_key)) {

        $secret_key = "$recaptcha_secret_key";
        $response = $input->post('g-recaptcha-response');
        $remote_ip = $_SERVER['REMOTE_ADDR'];
        $url = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$response&remoteip=$remote_ip");
        $result = json_decode($url, TRUE);
      } else {
        $result['success'] = 1;
      }

      if ($result["success"] == 1) {
        $enquiry_type = $input->post('enquiry_type');
        $subject = $input->post('subject');
        $message = $input->post('message');
        if ($enquiry_type == 1 or $enquiry_type == 2) {
          $order_number = $input->post('order_number');
          $order_rule = $input->post('user_role');
        } else {
          $order_number = "";
          $order_rule = "";
        }
        $file = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $allowed = array('jpeg', 'jpg', 'gif', 'png', 'tif', 'avi', 'mpeg', 'mpg', 'mov', 'rm', '3gp', 'flv', 'mp4', 'zip', 'rar', 'mp3', 'wav', 'txt');
        $file_extension = pathinfo($file, PATHINFO_EXTENSION);
        if (!in_array($file_extension, $allowed) & !empty($file)) {
          echo "<script>alert('{$lang['alert']['extension_not_supported']}')</script>";
        } else {

          if (!empty($file)) {
            $file = pathinfo($file, PATHINFO_FILENAME);
            $file = $file . "_" . time() . ".$file_extension";
            uploadToS3("ticket_files/$file", $file_tmp);
          } else {
            $file = "";
          }

          $isS3 = $enable_s3;

          $number = mt_rand();
          date_default_timezone_set('Asia/Kolkata');
          $isS3 = $enable_s3;
          $date = date("h:i A M d, Y");
          // $date = date("h:i M d, Y");
          $insert_support_ticket = $db->insert("support_tickets", array("enquiry_id" => $enquiry_type, "number" => $number, "sender_id" => $login_seller_id, "subject" => $subject, "message" => $message, "order_number" => $order_number, "order_rule" => $order_rule, "attachment" => $file, "date" => $date, "isS3" => $isS3, "status" => 'open'));
          if ($insert_support_ticket) {

            // ORDER DISPUTE SUPPORT
            if ($enquiry_type == 1) {
              $qOrder = $db->select('orders', ['order_number' => $order_number]);
              $oOrder = $qOrder->fetch();
              $orderSellerId = $oOrder->seller_id;
              $orderBuyerId = $oOrder->buyer_id;
              $defendedId = $login_seller_id == $orderSellerId ? $orderBuyerId : $orderSellerId;

              $db->insert("notifications", array("receiver_id" => $defendedId, "sender_id" => $login_seller_id, "order_id" => $order_number, "reason" => "dispute_raised", "date" => $date, "status" => "unread"));
            }

            $get_enquiry_types = $db->select("enquiry_types", array("enquiry_id" => $enquiry_type));
            $row_enquiry_types = $get_enquiry_types->fetch();
            $enquiry_title = $row_enquiry_types->enquiry_title;

            // Send Email To Admin Code Starts
            $data = [];
            $data['template'] = "customer_support";
            $data['to'] = $contact_email;
            $data['subject'] = $subject;
            $data['user_name'] = "";
            $data['seller_user_name'] = $login_seller_user_name;
            $data['seller_email'] = $login_seller_email;
            $data['enquiry_title'] = $enquiry_title;
            $data['message'] = $message;
            $data['attachment'] = $file;
            send_mail($data);
            // Send Email To Admin Code Ends

            /// Send Email To Sender Code Starts
            $data = [];
            $data['template'] = "customer_support_2";
            $data['to'] = $login_seller_email;
            $data['subject'] = "$site_name - Your Message has been received.";
            $data['user_name'] = $login_seller_user_name;
            send_mail($data);
            /// Send Email To Sender Code Ends
            echo "
      <script>
      swal({
        type: 'success',
        text: 'Message submitted successfully!',
        timer: 6000,
      })
      </script>";
          }
        }
      } else {
        echo "
    <script>
    swal({
      type: 'warning',
      text: 'Please select captcha and try again!',
      timer: 6000,
    })
    </script>";
      }
    }
  }
  ?>
  <?php require_once("includes/footer.php"); ?>
  <script>
    $(document).ready(function() {
      $(".select_tag").change(function() {
        url = $(".select_tag option:selected").attr('url');
        window.location.href = url;
      });
    });
  </script>
  <!-- <script>
    var stickyOffset = $('.sticky').offset().top;

    $(window).scroll(function() {

      var sticky = $('.sticky'),
        scroll = $(window).scrollTop();

      if (scroll >= stickyOffset) {
        sticky.addClass('fixed');
        $('.container-fluid ').css('margin-top', '140px');
        $('.fixed ').css('transition', 'all 2s linear');
      } else {
        sticky.removeClass('fixed')
        $('.container-fluid ').css('margin-top', '0px');
        $('.sticky ').css('transition', 'all 2s linear');
      }
    });


    new header script
  </script> -->
<!--   
  <script>
    $(document).ready(function() {
      var sticky = $('.sticky');
      var stickyOffset = sticky.offset().top;

      $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= stickyOffset) {
          sticky.addClass('fixed');
          $('.container-fluid').css('margin-top', '140px');
          sticky.css('transition', 'all 2s linear'); 
        } else {
          sticky.removeClass('fixed');
          $('.container-fluid').css('margin-top', '0px');
          sticky.css('transition', 'all 2s linear'); 
        }
      });
    });
  </script> -->

</body>

</html>
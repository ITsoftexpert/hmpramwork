<?php

session_start();
require_once("includes/db.php");
require_once("functions/email.php");
require_once("functions/functions.php");

if (!isset($_SESSION['seller_user_name'])) {
  echo "<script>window.open('login','_self')</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_timezone = $row_login_seller->seller_timezone;

$order_id = $input->get("order_id");

$get_orders = $db->query("select * from orders where (seller_id=$login_seller_id or buyer_id=$login_seller_id) AND order_id=:o_id", array("o_id" => $order_id));
$count_orders = $get_orders->rowCount();

if ($count_orders == 0) {
  echo "<script>window.open('index.php?not_available','_self')</script>";
}

$row_orders = $get_orders->fetch();
$seller_id = $row_orders->seller_id;
$buyer_id = $row_orders->buyer_id;
$order_price = $row_orders->order_price;
$order_number = $row_orders->order_number;
$proposal_id = $row_orders->proposal_id;
$order_status = $row_orders->order_status;
$complete_time = $row_orders->complete_time;
$order_date_extend = $row_orders->order_date_extend;
$order_time_extend = $row_orders->order_time_extend;

if ($videoPlugin == 1) {
  require_once("plugins/videoPlugin/order_details.php");
} else {
  $enableVideo = 0;
  $count_schedule = 0;
}

$get_site_logo_image = $row_general_settings->site_logo_image;
$order_auto_complete = $row_general_settings->order_auto_complete;

if ($order_status == "delivered") {
  $currentDate = new DateTime("now");
  if (!empty($complete_time)) {
    $endDate = new DateTime($complete_time);
    if ($currentDate >= $endDate) {
      require_once("orderIncludes/orderComplete.php");
    }
  }
}

if ($seller_id == $login_seller_id) {
  $receiver_id = $buyer_id;
} else {
  $receiver_id = $seller_id;
}

function watermarkImage($image, $data)
{

  global $site_watermark;

  $fileType = pathinfo($image, PATHINFO_EXTENSION);
  if ($fileType == "jpg" or $fileType == "jpeg" or $fileType == "png") {

    $to_image = imagecreatefromstring(file_get_contents($data));
    $stamp = imagecreatefromstring(file_get_contents("images/$site_watermark"));
    $spacing = 15;
    $spacing_double = $spacing  * 2;

    list($width, $height) = getimagesize($data);
    list($stamp_width, $stamp_height) = getimagesize("images/$site_watermark");

    $offsetX = ($width  - ($stamp_width + $spacing)) / 2;
    $offsetY = ($height - ($stamp_height + $spacing)) / 2;

    imagecopy($to_image, $stamp, $offsetX, $offsetY, 0, 0, $stamp_width, $stamp_height);

    ob_start();
    imagejpeg($to_image, null, 100);
    $image_contents = ob_get_clean();
    imagedestroy($to_image);

    uploadToS3("$image", "", $image_contents);
  } else {
    uploadToS3("$image", $data);
  }
}

$qOffers = $db->select("send_offers", array("sender_id" => $login_seller_id, "proposal_id" => $proposal_id));
$cOffers = $qOffers->rowCount();
$cRequests = 0;
if ($cOffers > 0) {
  $oOffers = $qOffers->fetch();
  $requestId = $oOffers->request_id;

  $qRequests = $db->select("buyer_requests", array("request_id" => $requestId));
  $cRequests = $qRequests->rowCount();
  $oRequests = $qRequests->fetch();
  $requestTitle = $oRequests->request_title;
}
?>

<!DOCTYPE html>

<html lang="en" class="ui-toolkit">

<head>
  <title><?= $site_name; ?> - Order Management For: #<?= $order_number; ?></title>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?= $site_desc; ?>">
  <meta name="keywords" content="<?= $site_keywords; ?>">
  <meta name="author" content="<?= $site_author; ?>">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
  <link href="styles/bootstrap.css" rel="stylesheet">
  <link href="styles/fontawesome-stars.css" rel="stylesheet">
  <link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
  <link href="styles/styles.css" rel="stylesheet">
  <link href="styles/proposalStyles.css" rel="stylesheet">
  <link href="styles/user_nav_styles.css" rel="stylesheet">
  <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
  <link href="styles/owl.carousel.css" rel="stylesheet">
  <link href="styles/owl.theme.default.css" rel="stylesheet">
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script src="https://checkout.stripe.com/checkout.js"></script>
  <link href="styles/sweat_alert.css" rel="stylesheet">
  <link href="styles/animate.css" rel="stylesheet">
  <script type="text/javascript" src="js/ie.js"></script>
  <script type="text/javascript" src="js/sweat_alert.js"></script>
  <script type="text/javascript" src="js/jquery.barrating.min.js"></script>
  <script type="text/javascript" src="js/jquery.sticky.js"></script>
  <?php if (!empty($site_favicon)) { ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
  <?php } ?>

  <!-- Include the PayPal JavaScript SDK -->
  <script src="https://www.paypal.com/sdk/js?client-id=<?= $paypal_client_id; ?>&disable-funding=credit,card&currency=<?= $paypal_currency_code; ?>"></script>

  <script>
    function alert_success(text, url) {
      swal({
        type: 'success',
        timer: 3000,
        text: text,
        onOpen: function() {
          swal.showLoading()
        }
      }).then(function() {
        if (url != "") {
          window.open(url, '_self');
        }
      });
    }
  </script>

</head>

<body class="is-responsive">
  <?php require_once("includes/user_header.php"); ?>
  <?php require_once("orderIncludes/orderDetails.php"); ?>
  <?php require_once("orderIncludes/orderStatusBar.php"); ?>

  <div class="container mt-2 pt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-8 offset-md-1">
            <h1 class="mb-4"> Order Management </h1>
          </div>
          <div class="col-md-3">
            <a href="<?= $site_url ?>/customer_support?enquiry_id=1&order_number=<?= $order_number ?>" class="btn btn-danger"><i class="fa fa-envelope-o" aria-hidden="true"></i> Raise a dispute</a>

          </div>
          <div class="col-md-10 offset-md-1">

            <ul class="nav nav-tabs mb-3 mt-3">
              <li class="nav-item">
                <a href="#order-activity" data-toggle="tab" class="nav-link active make-black ">Order Activity</a>
              </li>
              <?php if ($order_status == "pending" or $order_status == "progress" or $order_status == "delivered" or $order_status == "revision requested" or  $order_status == "Extend Delivery Request" or $order_status == "extendTimeDeclined") { ?>
                <li class="nav-item">
                  <a href="#resolution-center" data-toggle="tab" class="nav-link make-black">Resolution Center</a>
                </li>
              <?php } else { ?>
                <li class="nav-item">
                  <a href="#resolution-center" data-toggle="tab" class="nav-link make-black">Resolution Center</a>
                </li>
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>




      <div class="col-md-12 tab-content mt-2 mb-4">
        <div id="order-activity" class="tab-pane fade show active">
          <div class="row">
            <div class="col-md-10 offset-md-1">

              <?php require_once("orderIncludes/orderDetailsCard.php"); ?>
              <?php require_once("orderIncludes/orderTimeCounterBuyerInstruction.php"); ?>




              <?php
              if ($videoPlugin == 1) {
                require_once("plugins/videoPlugin/videoCall/setVideoSessionTime.php");
              }

              ?>
              <div id="order-conversations" class="mt-3">
                <?php require_once("orderIncludes/order_conversations.php"); ?>
              </div>

              <?php require_once("orderIncludes/orderDeliverButton.php"); ?>

              <div class="proposal_reviews mt-5">
                <?php

                if ($order_status == "completed") {
                  include("orderIncludes/orderReviews.php");
                  if ($count_buyer_reviews == 1 and $login_seller_id == $buyer_id) {
                    include("orderIncludes/orderTip.php");
                  }
                }
                ?>
              </div>
              <?php require_once("orderIncludes/insertMessageBox.php"); ?>
            </div>
          </div>
        </div>
        <div id="resolution-center" class="tab-pane fade">
          <?php
          if ($order_status == "pending" or $order_status == "progress" or $order_status == "delivered" or $order_status == "revision requested" or $order_status == "Extend Delivery Request" or $order_status == "extendTimeDeclined") {
            require_once("orderIncludes/resolutionCenter.php");
          } else {
            require_once("orderIncludes/resolutionCenter.php");
          }
          ?>
        </div>
      </div>

    </div>
  </div>
  <style>
    .delivery_extension_received {
      width: 100%;
    }

    .delivery_extension_received_inner {
      width: 100%;
      display: flex;
    }

    .form_extension_style {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 500px;
      margin: auto;
    }

    .heading_two_style {
      margin-top: 0;
      font-size: 20px;
      color: #333;
    }

    .textarea_input_style {
      width: calc(100% - 20px);
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      resize: vertical;
      width: 100%;
    }

    .radio-container {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
    }

    .radio-container label {
      font-size: 16px;
      /* color: #333; */
      cursor: pointer;
      /* border: 1px solid grey; */
      padding: 0.8rem 3rem;
      border-radius: 5px;
      box-shadow: 0px 0px 5px grey;
    }

    input[type="radio"] {
      margin-right: 5px;
    }

    .bg-success-light {
      background-color: green;
      color: #fff;
    }

    .bg-danger-light {
      background-color: #d00101;
      color: #fff;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #00cdce;
      border: none;
      border-radius: 4px;
      color: white;
      font-size: 16px;
      cursor: pointer;
      /* transition: background-color 0.3s; */
    }

    /* button:hover {
      background-color: #218838;
    } */
  </style>

<?php
if ($display_delivery_extend === "yes") {
    // Prepare and execute the query to select the latest delivery extension data based on order number and current date
    $delivery_extension_query = $db->query("SELECT * FROM delivery_extension WHERE order_number = :order_number AND DATE(order_date_extend) = CURRENT_DATE ORDER BY id DESC LIMIT 1", array("order_number" => $order_number));

    if ($delivery_extension_query) {
        // Fetch and display the latest data
        $delivery_extension_data = $delivery_extension_query->fetch(PDO::FETCH_ASSOC);
        if ($delivery_extension_data) {
            $order_number = $delivery_extension_data['order_number'];
            $order_duration_extend = $delivery_extension_data['order_duration_extend'];
            $order_time_extend = $delivery_extension_data['order_time_extend'];
            $order_date_extend = $delivery_extension_data['order_date_extend'];
            $extend_reason = $delivery_extension_data['extend_reason'];
            $extend_delivery_message = $delivery_extension_data['extend_delivery_message'];
?>

            <div class="delivery_extension_received">
                <div class="delivery_extension_received_inner">
                    <form method="post" class="form_extension_style">
                        <h2 class="heading_two_style">Delivery Extend Request From Seller</h2>
                        <?php
                        echo "<b>Order Number : </b>" . $order_number . "<br>";
                        echo "<b>Extend Delivery Duration : </b>" . $order_duration_extend . ' days' . "<br>";
                        echo "<b>Extend Delivery Date : </b>" . $order_date_extend . "<br>";
                        echo "<b>Extend Delivery Time: </b>" . $order_time_extend . "<br>";
                        echo "<b>Extend Reason : </b>" . $extend_reason . "<br>";
                        echo "<b>Extend Delivery Message : </b>" . $extend_delivery_message . "<br><br>";
                        ?>
                        <input type="text" name="order_duration_extend" value="<?= $order_duration_extend ?> days" id=""><br>
                        <input type="text" name="order_date_extend" value="<?= $order_date_extend ?>" id=""><br>
                        <input type="text" name="order_time_extend" value="<?= $order_time_extend ?>" id=""><br>
                        <input type="text" name="extend_reason" value="<?= $extend_reason ?>" id=""><br>
                        <input type="text" name="extend_delivery_message" value="<?= $extend_delivery_message ?>" id=""><br><br>

                        <textarea name="message" class="textarea_input_style" id="" rows="4" required></textarea><br>
                        <div class="radio-container">
                            <label class="bg-danger-light"><input type="radio" name="extend_result" id="" value="extendTimeAccepted" required> Accept</label>
                            <label class="bg-success-light"><input type="radio" name="extend_result" id="" value="extendTimeDeclined" required> Decline</label>
                        </div>
                        <button type="submit" name="submit_extend_result">Submit</button>
                    </form>
                </div>
            </div>
<?php
        } else {
            echo "No delivery extension data found for order number: " . $order_number . " and current date.";
        }
    } else {
        echo "Query failed.";
    }
}

if (isset($_POST['submit_extend_result'])) {
    $message = $input->post('message');
    $extend_result = $input->post('extend_result');
    $order_duration_extend = $input->post('order_duration_extend');
    $order_date_extend = $input->post('order_date_extend');
    $order_time_extend = $input->post('order_time_extend');
    $extend_reason = $input->post('extend_reason');
    $extend_delivery_message = $input->post('extend_delivery_message');

    $last_update_date = date("h:i: M d, Y");
    if ($seller_id == $login_seller_id) {
        $receiver_id = $buyer_id;
    } else {
        $receiver_id = $seller_id;
    }

    // Insert extend result into order_conversations
    $insert_extend_result = $db->insert("order_conversations", array(
        "order_id" => $order_id,
        "sender_id" => $login_seller_id,
        "message" => $message,
        "date" => $last_update_date,
        "reason" => $extend_result,
        "status" => $extend_result
    ));

    if ($insert_extend_result) {
        $insert_result_notification = $db->insert("notifications", array(
            "receiver_id" => $receiver_id,
            "sender_id" => $login_seller_id,
            "order_id" => $order_id,
            "reason" => $extend_result,
            "date" => $n_date,
            "status" => "unread"
        ));

        // Send push notification
        $notification_id = $db->lastInsertId();
        sendPushMessage($notification_id);

        if ($extend_result == "extendTimeAccepted") {
            // Update order with extended details
            $update_order = $db->update("orders", array(
                "order_status" => $extend_result,
                "order_duration_extend" => $order_duration_extend,
                "order_date_extend" => $order_date_extend,
                "order_time_extend" => $order_time_extend,
                "extend_reason" => $extend_reason,
                "extend_delivery_message" => $extend_delivery_message
            ), array("order_id" => $order_id));
        } else {
            // Update order status
            $update_order = $db->update("orders", array(
                "order_status" => $extend_result
            ), array("order_id" => $order_id));
        }

        echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";
    } else {
        echo "Try again!";
    }
}
?>





  <?php require_once("orderIncludes/modals/reportModal.php"); ?>

  <?php if ($videoPlugin == 1) {
    require_once("plugins/videoPlugin/videoCall/videoCallModal.php");
  } ?>

  <?php require_once("orderIncludes/modals/deliverOrderRevisionRequestModal.php"); ?>
  <?php require_once("orderIncludes/javascript/orderjs.php"); ?>

  <?php if ($videoPlugin == 1) { ?>

    <script type="text/javascript" src="plugins/videoPlugin/js/browser.js"></script>
    <script type="text/javascript" id="call-js" src="plugins/videoPlugin/js/orderVideoCall.js" data-base-url="<?= $site_url; ?>" data-order-id="<?= $order_id; ?>" data-proposal-id="<?= $proposal_id; ?>" data-login-seller-id="<?= $login_seller_id; ?>" data-seller-id="<?= $seller_id; ?>" data-buyer-id="<?= $buyer_id; ?>" data-start-call="<?= (isset($_GET['start_call'])) ? 1 : 0; ?>" data-warning-message="<?= $warning_message; ?>" data-order-call-time="<?= (new DateTime() >= $orderCallTime) ? 1 : 0; ?>" data-video-session-time="<?= $videoSessionTime; ?>"></script>

  <?php } ?>

  <?php require_once("includes/footer.php"); ?>

</body>

</html>
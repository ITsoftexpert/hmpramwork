<?php
if (isset($_SESSION['seller_user_name'])) {
  $login_seller_user_name = $_SESSION['seller_user_name'];
  $select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
  $row_login_seller = $select_login_seller->fetch();
  $login_seller_id = $row_login_seller->seller_id;
  $login_seller_email = $row_login_seller->seller_email;
  $login_seller_user_name = $row_login_seller->seller_user_name;
}

$query = "select * from support_tickets where sender_id = $login_seller_id order by 1 DESC";
$stmt = $db->query($query);
$support_tickets = $stmt->fetchAll();

if ($lang_dir == "right") {
  $floatRight = "float-right";
} else {
  $floatRight = "float-left";
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
      position: fixed;transition: all 3s linear;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 100;
    }

    .font-size-3 {
      padding: 13px !important;
      text-align: center;
      /* box-shadow: inset 0px 0px 15px #00c8d4; */
      /* text-shadow: 0px 0px 1px black; */
    }

    .box-shadow-7 {
      /* box-shadow:0px 0px 3px black, inset 0px 0px 30px lightseagreen; */
      background-color: white;
      /* text-shadow: 0px 0px 2px gray; */
      /* text-shadow: 0px 0px 2px black; */
    }

    .box-shadow-7body {
      /* box-shadow:0px 0px 3px black, inset 0px 0px 30px gray; */
      background-color: white;
      /* text-shadow: 0px 0px 2px black; */
    }

    .padding-alter-12 {
      /* border: 1px solid green; */
      padding: 0px 15px;
    }

    .margin-top-120 {
      margin-top: 80px !important;
      margin-bottom: 30px !important;
    }

    @media (max-width:768px) {

      .padding-alter-12 {
        /* border: 1px solid green; */
        padding: 0px 15px;
      }

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

      .font-size-3 {
        font-size: 13px !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
        text-align: center;
      }

      .padding-6rem {
        padding: 0.6rem !important;
      }

      .margin-top-120 {
        margin-top: 80px !important;
        margin-bottom: 15px !important;
      }

      .table {
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
      }
    }

    @media (max-width:639px) {

      .padding-alter-12 {
        /* border: 1px solid green; */
        padding: 0px 1px;
      }
    }
  </style>
</head>

<body class="is-responsive">

  <?php require_once("includes/header.php"); ?>

  <div class="container-fluid margin-top-120 px-5 pb-5"><!-- Container starts -->

    <div class="row customer-support mb-1 padding-alter-12" style="<?= ($lang_dir == "right" ? 'direction: rtl;' : '') ?>">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header text-center make-white box-shadow-7">
            <h2 class="full-width mt-3 mb-3"><span class="text-align-center">My Tickets List</span></h2>
          </div>
          <div class="card-body padding-6rem box-shadow-7body">
            <div class="table-responsive">
              <table class="table table-bordered table-hover tickets-list mt-3">
                <thead>
                  <tr>
                    <th class="font-size-3"><?= $lang['th']['ticket_number']; ?></th>
                    <th class="font-size-3"><?= $lang['th']['subject']; ?></th>
                    <th class="font-size-3"><?= $lang['th']['message']; ?></th>
                    <th class="font-size-3"><?= $lang['th']['order_number']; ?></th>
                    <th class="font-size-3"><?= $lang['th']['rule']; ?></th>
                    <th class="font-size-3"><?= $lang['th']['status']; ?></th>
                    <th class="font-size-3"><?= $lang['th']['action']; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($support_tickets as $ticket) :
                    $message = strlen($ticket->message) > 100 ? substr($ticket->message, 0, 50) . '....' : $ticket->message;
                  ?>
                    <tr>
                      <td>#<?= $ticket->number; ?></td>
                      <td><?= $ticket->subject; ?></td>
                      <td><?= $message; ?></td>
                      <td><?= $ticket->order_number; ?></td>
                      <td><?= $ticket->order_rule; ?></td>
                      <td><?= ucfirst($ticket->status); ?></td>
                      <td><a class="btn btn-success" href="<?= $site_url . "/support?view_conversation&ticket_id=" . $ticket->ticket_id; ?>">View</a></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Container ends -->

  <?php require_once("includes/footer.php"); ?>
  <!-- <script>
    var stickyOffset = $('.sticky').offset().top;

    $(window).scroll(function() {

      var sticky = $('.sticky'),
        scroll = $(window).scrollTop();

      if (scroll >= stickyOffset) {
        sticky.addClass('fixed');

        $('.container-fluid ').css('margin-top', '140px')
      } else {
        sticky.removeClass('fixed')
        $('.container-fluid ').css('margin-top', '0px')
      }
    });
  </script> -->
  <!-- <script>
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
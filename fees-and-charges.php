<?php
session_start();
require_once("includes/db.php");
require_once("social-config.php");
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
  <title><?= $site_name; ?> - Fees And Charges </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?= $site_desc; ?>">
  <meta name="keywords" content="<?= $site_keywords; ?>">
  <meta name="author" content="<?= $site_author; ?>">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
  <link href="styles/bootstrap.css" rel="stylesheet">
  <link href="styles/styles.css" rel="stylesheet">
  <link href="styles/categories_nav_styles.css" rel="stylesheet">
  <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
  <link href="styles/owl.carousel.css" rel="stylesheet">
  <link href="styles/owl.theme.default.css" rel="stylesheet">
  <?php if (!empty($site_favicon)) { ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
  <?php } ?>
  <link href="styles/sweat_alert.css" rel="stylesheet">
  <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
  <script src="js/ie.js"></script>
  <script type="text/javascript" src="js/sweat_alert.js"></script>
  <script type="text/javascript" src="js/jquery.min.js"></script>
</head>

<body class="is-responsive">
  <?php require_once("includes/header.php"); ?>
  <div class="container mt-4 mb-5 site-theme-color">
    <div class="row">
      <div class="col-md-12 text-center">
        <h1 class="heading_font_our_policies">Our Policies</h1>
        <p class="lead">Refund Policy, Pricing & Promotion Policy. </p>
        <hr>
      </div>
    </div>


    <style>
      .heading_font_our_policies {
        font-size: 35px !important;
        font-weight: 600;
      }

      .site-theme-color {
        background-color: #fff;
      }

      .termconditoncontentdiv {
        /* border: 1px solid green; */
        width: 100%;
        display: flex;
      }

      /* sidebar */
      .termcondtionsidebar {
        background-color: #e5e5e5;
        width: 25%;
        padding: 1rem 1rem;
        /* border-right: 2px solid lightgray; */
      }

      .termconditoncontent {
        width: 75%;
        padding: 1rem 2rem;

      }

      .paragraphtermcondition {
        font-size: 1.3rem;
        line-height: 2.5rem;
        text-align: justify;
      }

      .list-unorder-term-condition {
        font-size: 1.3rem;
        /* color: gray; */
        line-height: 2.5rem;
        text-align: justify;
      }

      .sidebar-list-details {
        font-size: 1.3rem;
        line-height: 3rem;
        list-style-type: none;
        font-weight: 700;
      }

      .refund-timeline-table {
        border: 1px solid grey;
      }

      .refund-timeline-table tr {
        width: 100%;
      }

      .refund-timeline-table tr th {
        width: 20%;
        border: 1px solid grey;
        font-size: 1.3rem;
        line-height: 2.5rem;
        text-align: center;
      }

      .refund-timeline-table tr td {
        width: 80%;
        font-size: 1.3rem;
        line-height: 2.5rem;
        border: 1px solid grey;
        padding: 1rem;
      }



      /* Apply height and scrolling to both containers */
      .termcondtionsidebar,
      .termconditoncontent {
        height: 70vh;
       
        overflow-y: auto;
      
        padding-right: 15px;
        
      }

    
      .termcondtionsidebar::-webkit-scrollbar,
      .termconditoncontent::-webkit-scrollbar {
        width: 5px;
    
      }

      .termcondtionsidebar::-webkit-scrollbar-track,
      .termconditoncontent::-webkit-scrollbar-track {
        background: #f1f1f1;
        
        border-radius: 10px;
       
      }

      .termcondtionsidebar::-webkit-scrollbar-thumb,
      .termconditoncontent::-webkit-scrollbar-thumb {
        background-color: #00CEDC;
      
        border-radius: 10px;
       
        height: 5px;
        
        min-height: 5px;
   
      }

      .termcondtionsidebar::-webkit-scrollbar-thumb:hover,
      .termconditoncontent::-webkit-scrollbar-thumb:hover {
        background-color: #555;
        /* Darker color on hover */
      }

      /* Hide the sidebar on screens smaller than 768px */
      @media only screen and (max-width: 768px) {
        .termcondtionsidebar {
          display: none;
          /* Hide the sidebar */
        }

        .termconditoncontent {
          width: 100%;
          padding-left: 0.5rem !important;
          padding-right: 0.5rem !important;

        }
        .termconditoncontent{
          scr  
        }

      }
    </style>

    <div class="termconditoncontentdiv">
      <div class="termcondtionsidebar">
        <ul class="pl-1">
          <a href="#1">
            <li class="sidebar-list-details">Refund to your Wallet Balance</li>
          </a>
          <a href="#2">
            <li class="sidebar-list-details">Refund timeline</li>
          </a>
          <a href="#3">
            <li class="sidebar-list-details">Incomplete refunds</li>
          </a>
          <a href="#4">
            <li class="sidebar-list-details">Refunds to a different card</li>
          </a>
          <a href="#5">
            <li class="sidebar-list-details">Refund currency</li>
          </a>
          <a href="#6">
            <li class="sidebar-list-details">Cancellations</li>
          </a>
          <a href="#7">
            <li class="sidebar-list-details">Different kinds of cancellations</li>
          </a>
          <a href="#8">
            <li class="sidebar-list-details">Using the Resolution Center</li>
          </a>
          <a href="#9">
            <li class="sidebar-list-details">Cancellations & your seller level</li>
          </a>
          <a href="#10">
            <li class="sidebar-list-details">Order Completion Rate (OCR)</li>
          </a>
          <a href="#11">
            <li class="sidebar-list-details">Tips to decrease cancellations</li>
          </a>
          <a href="#12">
            <li class="sidebar-list-details">Delivery</li>
          </a>
          <a href="#13">
            <li class="sidebar-list-details">General guidelines</li>
          </a>
          <a href="#14">
            <li class="sidebar-list-details">Prevent cancellations</li>
          </a>
        </ul>

      </div>
      <div class="termconditoncontent">

        <h2 id="1"> Refund to your Wallet Balance </h2>
        <p class="paragraphtermcondition">If your order was canceled, the funds will be credited to your Hiremyprofile
          Balance. Hiremyprofile doesn't automatically process refunds to your payment provider after an order's
          cancellation. You will see the credited funds just next to your profile picture.
        </p>

        <br>
        <h2 id="2">Refund timeline</h2>
        <p class="paragraphtermcondition">Once a refund is initiated, the refund will take different amounts of time to
          reflect—depending on which method you choose to receive the funds.
          Example: PayPal or Credit Card. </p>

        <table class="refund-timeline-table">
          <tr>
            <th>PayPal</th>
            <td>Refunds are usually completed within 24 - 72 hours of the request being processed by Customer Support.
            </td>
          </tr>
          <tr>
            <th>Credit card</th>
            <td>Refunds take slightly longer due to the card issuer's processing time. Usually, credit card refunds are
              completed within 7 to 10 days of the request being processed by Customer Support. However, in extreme
              cases, it can take up to 2 weeks.
            </td>
          </tr>
        </table>

        <br>
        <h2 id="3">Incomplete refunds</h2>
        <p class="paragraphtermcondition">If the funds credited to your payment provider are incomplete, remember that
          orders are refunded to your Hiremyprofile balance, and sometimes those funds are reused when you make any new
          purchases. This is why the funds credited to your payment provider may seem incomplete. Check your most recent
          orders to confirm if they were paid for using your Hiremyprofile balance. If you have any issues regarding
          your account balance, contact <a href="https://www.hiremyprofile.com//customer_support"
            class="text-primary">Customer Support</a>.
        </p>

        <br>
        <h2 id="4">Refunds to a different card or PayPal account</h2>
        <p class="paragraphtermcondition">We are only able to refund funds to the original payment source used to
          process the payment. Hiremyprofile is not able to change the card or PayPal account to where the funds will be
          credited.
        </p>

        <br>
        <h2 id="5">Refund currency</h2>
        <p class="paragraphtermcondition">If you paid for your order using a currency other than US dollars, your refund
          will be processed in the same currency and amount used in the payment.
        </p>
        <p class="paragraphtermcondition">For any further questions or concerns relating to your refund, contact <a
            href="https://www.hiremyprofile.com//customer_support" class="text-primary">Customer Support</a>.
        </p>

        <br>
        <h2 id="6">Cancellations</h2>
        <p class="paragraphtermcondition">While you should always aim to satisfy your buyers’ needs, sometimes things
          don’t work as planned and cancellation is the best way to resolve an ongoing order. When addressing an issue,
          be sure to communicate clearly and politely throughout the entire cancellation process.
        </p>

        <br>
        <h3 id="7">Different kinds of cancellations</h3>
        <p class="paragraphtermcondition">Important! Cancellations should always be considered as a last resort.
          Canceling orders negatively affects the buyer experience, as well as your valuable time and potential income.
        </p>
        <ul>
          <li class="list-unorder-term-condition">Buyer-requested cancellations: If an order is marked as very late (24
            hours or more), buyers can request to cancel. If you have already worked on the order, this can result in
            you not receiving payment. Avoid this Situation.
          </li>

          <li class="list-unorder-term-condition">Seller-requested cancellations: If you request to cancel, the order
            will be canceled if your buyer doesn't respond (after 48 hours). Always use the <a
              href="https://www.hiremyprofile.com/customer_support" class="text-primary">Resolution Center</a> to work
            things out with your buyer before contacting Customer Support.
          </li>

          <li class="list-unorder-term-condition">Mutual cancellations: When both parties agree to cancel a job, resolve
            an order using this cancellation.
            <br>Important: If your buyer doesn’t respond to you within 2 days of initiating a mutual cancellation, the
            job is automatically canceled. The same applies if a buyer requests the cancellation, and you remain
            unresponsive.
          </li>

          <li class="list-unorder-term-condition">Admin: If you can’t agree with your buyer, or if your buyer won’t
            mutually cancel, contact <a href="https://www.hiremyprofile.com//customer_support"
              class="text-primary">Customer Support</a> to assist.
          </li>
        </ul>

        <br>
        <h2 id="8">Using the Resolution Center</h2>
        <p class="paragraphtermcondition">We always encourage both our sellers and buyers to try and resolve disputes
          within ongoing orders and to avoid cancellations. To easily resolve any disputes with your buyer, use the <a
            href="https://www.hiremyprofile.com/customer_support" class="text-primary">Resolution Center</a>.
        </p>



        <br>
        <h2 id="9">Cancellations & your seller level</h2>
        <p class="paragraphtermcondition">When evaluating a seller, all cancellations are taken into account. We are
          aware that some cancellations are inevitable and those cancellations have a lower impact on your performance
          scores.
        </p>

        <br>
        <h3 id="10">Order Completion Rate (OCR)</h3>
        <p class="paragraphtermcondition">Order completion refers to the successful conclusion of an order—where a buyer
          receives and approves the service that they purchased. One of the ways your performance as a seller is
          evaluated through the OCR:
        </p>
        <p class="paragraphtermcondition"> By keeping a steady OCR, build relationships with buyers, and grow your
          business. It also enables you to track the quality of the service you’re providing and influence your
          Service’s position in the search rankings.
        </p>

        <br>
        <h3 id="11">Tips to decrease cancellations</h3>
        <br>
        <h4 id="12">Delivery</h4>
        <p class="paragraphtermcondition">Always do your best to deliver on time: </p>
        <ul>
          <li class="list-unorder-term-condition">Plan your work, and make sure your delivery time is realistic </li>
          <li class="list-unorder-term-condition">If on-time delivery is not possible, use the Extend Delivery option in
            the <a href="https://www.hiremyprofile.com/customer_support" class="text-primary">Resolution Center</a></li>
        </ul>
        <p class="paragraphtermcondition">Important! Delivering an empty message or sending incomplete work, to avoid
          late delivery, is a violation of Hiremyprofile’s Terms of Service. This can result in your account being
          reviewed.
        </p>

        <br>
        <h4 id="13">General guidelines </h4>
        <ul>
          <li class="list-unorder-term-condition">Make sure to only provide services that match your skill set—don’t
            offer to do anything you can’t do or are not qualified to do
          </li>
          <li class="list-unorder-term-condition">On your Service page, accurately display your latest and original work
          </li>
          <li class="list-unorder-term-condition">Set reasonable delivery times
          </li>
          <li class="list-unorder-term-condition">Be very clear about the pricing and scope of your Service—clear enough
            to avoid misunderstandings.
          </li>
          <li class="list-unorder-term-condition">Communicate with your buyer and make sure you’re aligned on the
            service—if you are confused, contact your buyer for clarification
          </li>
          <li class="list-unorder-term-condition">When you receive an order, make sure that you have all the necessary
            information to start working
          </li>
          <li class="list-unorder-term-condition">If cancellation is inevitable, don’t wait until the last minute—give
            your buyer an adequate warning
          </li>
        </ul>
        <br>
        <h4 id="14">Prevent cancellations:</h4>
        <ul>
          <li class="list-unorder-term-condition">Be proactive: Communicate all the necessary info on your Service
            description, requirements, and extras before the order to set you up for success before it’s even purchased
          </li>
          <li class="list-unorder-term-condition">Be prepared: If one of your buyers is unresponsive, initiate a mutual
            cancellation
          </li>
          <li class="list-unorder-term-condition">Review delivery times: If you find that you are often tempted to
            cancel because you’re too busy, give yourself more time to deliver
          </li>
        </ul>
      </div>
    </div>

  </div>
  <?php require_once("includes/footer.php"); ?>
</body>

</html>
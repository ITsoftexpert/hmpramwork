<?php
session_start();
require_once("includes/db.php");
require_once("social-config.php");
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
  <title><?= $site_name; ?> - Terms and Conditions.</title>
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

  <style>
    .termandconditiondivthird {
      width: 75%;
      padding: 1rem 2rem;
    }

    .paragrapgh_privacy_policy {
      font-size: 1.3rem;
      line-height: 2.5rem;
      text-align: justify;
    }

    .list_privacy_policy {
      font-size: 1.3rem;
      line-height: 2.5rem;
      text-align: justify;
    }

    .address_privacy_policy {
      font-size: 1.1rem;
      line-height: 2rem;
      text-align: justify;
    }

    .heading_font_our_privacy {
      font-size: 35px !important;
      font-weight: 600;
    }

    .main_class_privacy {
      width: 90%;
      margin: auto;
      display: flex;
    }

    @media (max-width: 768px) {
      .termandconditiondivside {
        display: none;
      }

      .termandconditiondivthird {
        width: 100%;
        padding: 1rem 2rem;
        overflow: non;
      }

    }



    .termandconditiondivside {
      background-color: #e5e5e5;
      width: 25%;
      padding: 1rem 1rem;
    }

    .sidebar-list-details {
      font-size: 1.3rem;
      line-height: 3rem;
      list-style-type: none;
      font-weight: 700;
    }

    .fixed {
      position: fixed;
      transition: all 3s linear;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 100;
    }

    .termandconditiondivsec {
      /* border: 1px solid grey; */
      width: 85%;
      margin: auto;
    }

    .paragrapgh_term_and_conditions {
      font-size: 1.3rem;
      line-height: 2.5rem;
      text-align: justify;
    }

    .list_term_and_conditions {
      font-size: 1.3rem;
      line-height: 2.5rem;
      text-align: justify;
    }

    .address_copyright_policy {
      font-size: 1.1rem;
      line-height: 2rem;
      text-align: justify;
    }



    /* Ensure both containers have height and enable scrolling */
    .termandconditiondivside,
    .termandconditiondivthird {
      height: 70vh;
      /* Adjust as needed for your layout */
      overflow-y: auto;
      /* Enable vertical scrolling */
      padding-right: 15px;
      /* Prevent content from hiding behind the scrollbar */
    }

    @media (max-width: 768px) {

      .termandconditiondivside,
      .termandconditiondivthird {


        overflow-y: visible;
        width: 100%;
        padding: 0 !important;
        padding-right: 0px !important;

        /* padding-right: 15px; */

      }

      .container-fluid,
      .order-page {
        margin-top: 10px !important;
      }
    }

    /* Scrollbar styling for WebKit browsers */
    .termandconditiondivside::-webkit-scrollbar,
    .termandconditiondivthird::-webkit-scrollbar {
      width: 5px;
      /* Width of the scrollbar */
    }

    .termandconditiondivside::-webkit-scrollbar-track,
    .termandconditiondivthird::-webkit-scrollbar-track {
      background: #f1f1f1;
      /* Track background */
      border-radius: 10px;
      /* Round corners for track */
    }

    .termandconditiondivside::-webkit-scrollbar-thumb,
    .termandconditiondivthird::-webkit-scrollbar-thumb {
      background-color: #00CEDC;
      /* Scroll thumb color */
      border-radius: 10px;
      /* Round corners for thumb */
      height: 5px;
      /* Scroll thumb height */
      min-height: 5px;
      /* Minimum height of the scroll thumb */
    }

    .termandconditiondivside::-webkit-scrollbar-thumb:hover,
    .termandconditiondivthird::-webkit-scrollbar-thumb:hover {
      background-color: #555;
      /* Darker color on hover */
    }

    €@media (max-width: 768px) {
      site-theme-color_rampal {
        margin-top: 65px !important;
      }
    }

    @media (max-width: 480px) {
      #style-join-border-red {
        margin-top: 0px !important;
     
      }
    }
  </style>
</head>

<body class="is-responsive">
  <?php require_once("includes/header.php"); ?>


  <div class="container-fluid mt-5 mb-5 pb-1 pt-5 site-theme-color site-theme-color_rampal">
    <div class="row">
      <div class="col-md-12 text-center">
        <h1>Terms & conditions</h1>
        <p class="paragrapgh_term_and_conditions text-center lead pb-0"> Welcome to HIREMYPROFILE a Product of
          Preferred Outsourcing Pvt. Ltd. </p>
        <hr>
      </div>
    </div>
    <div class="main_class_privacy">
      <div class="termandconditiondivside">
        <ul class="pl-1">
          <a href="#a">
            <li class="sidebar-list-details" title="">Key Terms
            </li>

          </a>
          <a href="#b">
            <li class="sidebar-list-details" title=""> Overview
            </li>

          </a>
          <a href="c">
            <li class="sidebar-list-details" title=""> SELLERS
            </li>

          </a>
          <a href="#d">
            <li class="sidebar-list-details" title=""> Basics
            </li>

          </a>
          <a href="#e">
            <li class="sidebar-list-details" title="">Services
            </li>

          </a>
          <a href="#f">
            <li class="sidebar-list-details" title=""> Levels
            </li>

          </a>
          <a href="#g">
            <li class="sidebar-list-details" title="The advancement criteria can be found here:"> The advancement
            </li>

          </a>
          <a href="#h">
            <li class="sidebar-list-details" title="">Featured Sellers
            </li>

          </a>
          <a href="#i">
            <li class="sidebar-list-details" title=""> Custom proposals
            </li>

          </a>
          <a href="#j">
            <li class="sidebar-list-details" title=""> Stock Images
            </li>

          </a>
          <a href="#k">
            <li class="sidebar-list-details" title=""> BUYERS
            </li>

          </a>
          <a href="#l">
            <li class="sidebar-list-details" title="">Purchasing
            </li>

          </a>
          <a href="#m">
            <li class="sidebar-list-details" title=""> ORDERS
            </li>

          </a>
          <a href="#n">
            <li class="sidebar-list-details" title=""> Handling Orders
            </li>

          </a>
          <a href="#o">
            <li class="sidebar-list-details" title=""> Reviews
            </li>

          </a>
          <a href="#p">
            <li class="sidebar-list-details" title=""> Disputes and Cancellations
            </li>

          </a>
          <a href="#q">
            <li class="sidebar-list-details" title=""> User Conduct and Protection
            </li>

          </a>
          <a href="#r">
            <li class="sidebar-list-details" title=""> Basics
            </li>

          </a>
          <a href="#s">
            <li class="sidebar-list-details" title=""> Orders
            </li>

          </a>
          <a href="#t">
            <li class="sidebar-list-details" title=""> Services
            </li>

          </a>
          <a href="#u">
            <li class="sidebar-list-details" title="">Reporting Violations
            </li>

          </a>
          <a href="#v">
            <li class="sidebar-list-details" title=""> Violations
            </li>

          </a>
          <a href="#w">
            <li class="sidebar-list-details" title="">Non-Permitted Usage
            </li>

          </a>
          <a href="#x">
            <li class="sidebar-list-details" title=""> Abuse and Spam
            </li>

          </a>
          <a href="#y">
            <li class="sidebar-list-details" title=""> Proprietary Restrictions
            </li>

          </a>
          <a href="#z">
            <li class="sidebar-list-details" title=""> Feedback Rights
            </li>

          </a>
          <a href="#aa">
            <li class="sidebar-list-details" title=""> Confidentiality
            </li>

          </a>
          <a href="#ab">
            <li class="sidebar-list-details" title=""> General Terms
            </li>

          </a>
          <a href="#ac">
            <li class="sidebar-list-details" title=""> User Generated Content
            </li>

          </a>
          <a href="#ad">
            <li class="sidebar-list-details" title=""> Ownership
            </li>

          </a>
          <a href="#ae">
            <li class="sidebar-list-details" title=""> Voice Over Commercial Buy-Out
            </li>

          </a>
          <a href="#af">
            <li class="sidebar-list-details" title=""> Here's what you can do with a Commercial Rights Buy-Out:
            </li>

          </a>
          <a href="#ag">
            <li class="sidebar-list-details" title=""> What's not allowed?
            </li>

          </a>
          <a href="#ah">
            <li class="sidebar-list-details" title=""> This Buy-Out is subject to HMP's Terms of Service.
            </li>

          </a>
          <a href="#ai">
            <li class="sidebar-list-details" title=""> Service Commercial Use License
            </li>

          </a>
          <a href="#aj">
            <li class="sidebar-list-details" title=""> Important Notes:
            </li>

          </a>
          <a href="#ak">
            <li class="sidebar-list-details" title=""> Disclaimer of Warranties
            </li>

          </a>
          <a href="#al">
            <li class="sidebar-list-details" title=""> Machine Translation
            </li>

          </a>
          <a href="#am">
            <li class="sidebar-list-details" title=""> Limitation on Liability
            </li>

          </a>




        </ul>
      </div>
      <div class="termandconditiondivthird">

        <p class="paragrapgh_term_and_conditions">The following terms of service (these "Terms of Service"),
          govern your access to and use of the Hire my profile website and mobile application, including any
          content, functionality and services offered on or through www.hiremyprofile.com or the hire my
          profile mobile application. Hire my profile referred hereto as "HMP" "we" or "us" and “you” or
          “user” means you as an user of the Site.
        </p>
        <p class="paragrapgh_term_and_conditions">
          Preferred Outsourcing Pvt Ltd grants you (“User” or “you” or “your”) permission to access and use
          the Site and Content, subject to these T&Cs. By accessing or using any part of the Site -
          hiremyprofile.com, you acknowledge that you have read, understood, and agree to be bound by these
          T&Cs. If you are accepting these T&Cs on behalf of a company or other legal entity (“User Entity”),
          you must have the legal authority to bind that User Entity to these T&Cs. In such a case, “you” or
          “your” or “User” will refer to that User Entity. If you do not have the authority to bind the User
          Entity or do not agree with these T&Cs, you must not accept these T&Cs or access or use the Site or
          Content.
        </p>
        <p class="paragrapgh_term_and_conditions">
          If you do not want to agree to these Terms of Service or the Privacy Policy, you must not access or
          use the Site. For more detailed policies surrounding the activity and usage on the Site, please
          access the designated articles herein.
        </p>
        <p class="paragrapgh_term_and_conditions">This Site is offered and available to users who are at least
          18 years of age and of legal age to form a binding contract. If you are under 18 and at least 13
          years of age, you are only permitted to use the Site through an account owned by a parent or legal
          guardian with their appropriate permission. If you are under 13 you are not permitted to use the
          Site or the HMP services. By using the Site, you represent and warrant that you meet all of the
          foregoing eligibility requirements. If you do not meet all of these requirements, you must not
          access or use the Site
        </p>
        <p class="paragrapgh_term_and_conditions">Our Customer Support team is available 24/7 if you have any
          questions regarding the Site or Terms of Service. Contacting our Customer Support team can be
          performed by submitting a request here.
        </p>
        <p class="paragrapgh_term_and_conditions">The original language of these Terms of Service, as well as
          all other texts throughout the Site, is English.
        </p>

        <h4 id="a">Key Terms</h4>
        <ul style="list-style-type: none;">
          <li class="list_term_and_conditions mb-2"><b>1. Buyers -</b> Are users who purchase services on HMP.
          </li>
          <li class="list_term_and_conditions mb-2"><b>2. Sellers -</b> Are users who offer and perform
            services on HMP.
          </li>
          <li class="list_term_and_conditions mb-2"><b>3. Proposals -</b> Are exclusive offers that a Seller
            can create in response to specific requirements posted by a Buyer.
          </li>
          <li class="list_term_and_conditions mb-2"><b>4. Jobs -</b> Are requests made by a Buyer to receive a
            Custom proposals from a Seller.
          </li>
          <li class="list_term_and_conditions mb-2"><b>5. Subscription -</b> Allow Sellers to offer services
            in different formats and prices. It can include upgrades, which lets Sellers price their service
            for a basic price of over $5.​
          </li>
          <li class="list_term_and_conditions mb-2"><b>7. Service Page -</b> Is where the Seller can describe
            their service and it's terms, and the Buyer can purchase the Service and create an work order.
          </li>
          <li class="list_term_and_conditions mb-2"><b>8. Work Order -</b> Page is where Buyers and Sellers
            communicate with each other in connection with an ordered Service & are the formal agreements
            between a Buyer and Seller after a purchase was made from the Seller’s Service Page.
          </li>
        </ul>
        <h4 id="b">Overview</h4>
        <ul>
          <li class="list_term_and_conditions mb-2">Only registered users may buy and sell on HMP.
            Registration is free. In registering for an account, you agree to provide us with accurate,
            complete and updated information and must not create an account for fraudulent or misleading
            purposes. You are solely responsible for any activity on your account and for maintaining the
            confidentiality and security of your password. We are not liable for any acts or omissions by
            you in connection with your account.
          </li>
          <li class="list_term_and_conditions mb-2">Buyers pay HMP in advance to create an order (see Payment
            Terms).
          </li>
          <li class="list_term_and_conditions mb-2">Services are not purchased directly. Buyers must
            thoroughly discuss their requirements with the seller first. Only when a mutual understanding is
            reached will the seller send a custom proposal, and the work order will commence.
          </li>
          <li class="list_term_and_conditions mb-2">For fees and payments please read the Payment Terms
          </li>
          <li class="list_term_and_conditions mb-2">Sellers must fulfill their orders, and may not cancel
            orders on a regular basis or without cause. Cancelling orders will affect Sellers’ reputation
            and status.
          </li>
          <li class="list_term_and_conditions mb-2">Sellers gain account statuses (Levels) based on their
            performance and reputation. Advanced levels provide their owners with benefits, including
            offering services for higher prices through Service Extras, or selling their Service in
            multiples.
          </li>
          <li class="list_term_and_conditions mb-2">Users may not offer or accept payments using any method
            other than placing an work order through hiremyprofile.com.
          </li>
          <li class="list_term_and_conditions mb-2">When purchasing a Service, Buyers are granted all rights
            for the delivered work, unless otherwise specified by the Seller on their Service page. Note:
            some Services charge additional payments (through Extras) for Commercial Use License. See our
            “Ownership” and “Commercial Use License” sections below for more information.
          </li>
          <li class="list_term_and_conditions mb-2">HMP retains the right to use all published delivered works
            and Logo Designs for HMP marketing and promotion purposes.
          </li>
          <li class="list_term_and_conditions mb-2">We care about your privacy. You can read our Privacy
            Policy here.
          </li>
        </ul>

        <h4 id="c">SELLERS</h4>

        <h4 id="d">Basics</h4>

        <ul>
          <li class="list_term_and_conditions mb-2">Sellers create Services on HMP to allow Buyers to purchase
            their services.
          </li>
          <li class="list_term_and_conditions mb-2">Sellers may also offer Custom Offers to Buyers in addition
            to their Services.
          </li>
          <li class="list_term_and_conditions mb-2">Each Service you sell and successfully complete, accredits
            your account with a revenue equal to 90% of the purchase amount.
          </li>
          <li class="list_term_and_conditions mb-2">HMP accredits Sellers once an order is completed. See our
            "Orders" section below for a definition of a completed order.
          </li>
          <p class="paragrapgh_term_and_conditions">For more information about receiving payments, fees and
            taxes see the Payment Terms.
          </p>
          <li class="list_term_and_conditions mb-2">The Seller's rating is calculated based on the work order
            reviews posted by Buyers. High ratings allow Sellers to obtain advanced Seller levels (see
            Levels below). In certain cases, exceedingly low ratings may lead to the suspension of the
            Seller’s account.
          </li>
          <li class="list_term_and_conditions mb-2">For security concerns, HMP may temporarily disable a
            Seller’s ability to withdraw revenue to prevent fraudulent or illicit activity. This may come as
            a result of security issues, improper behavior reported by other users, or associating multiple
            HMP accounts to a single withdrawal provider.
          </li>
        </ul>

        <br>
        <h4 id="e">Services</h4>
        <ul>
          <li class="list_term_and_conditions mb-2">
            Sellers are allowed to post a select amount of active services based on their Membership plan
            they have purchased.
            <ul>
              <li class="list_term_and_conditions mb-2">Create 5 Active Services for Sellers with Basic
                membership plan ( free for a month)
              </li>
              <li class="list_term_and_conditions mb-2">Create 10 Active Services for Sellers with
                professional membership plan
              </li>
              <li class="list_term_and_conditions mb-2">Create 25 Active Services for Sellers with premium
                membership plan
              </li>
            </ul>
          </li>
          <li class="list_term_and_conditions mb-2">Active Services created on HMP are User Generated Content.
          </li>
          <li class="list_term_and_conditions mb-2">Services and/or users may be removed by HMP from the Site
            for violations of these Terms of Service and/or our Community Standards, which may include (but
            are not limited to) the following violations and/or materials:
          </li>
          <li class="list_term_and_conditions mb-2">Illegal or Fraudulent services
          </li>
          <li class="list_term_and_conditions mb-2">Copyright Infringement, Trademark Infringement, and
            violation of a third party's terms of service reported through our Intellectual Property Claims
            Policy found here
          </li>
          <li class="list_term_and_conditions mb-2">Adult oriented services, Pornographic,
            Inappropriate/Obscene
          </li>
          <li class="list_term_and_conditions mb-2">Intentional copies of other Sellers active Services
          </li>
          <li class="list_term_and_conditions mb-2">Spam, nonsense, or violent or deceptive messages or write
            ups
          </li>
          <li class="list_term_and_conditions mb-2">Services misleading to Buyers or others
          </li>
          <li class="list_term_and_conditions mb-2">Reselling of regulated goods
          </li>
          <li class="list_term_and_conditions mb-2">Offering to prepare academic works on behalf of Buyers
          </li>
          <li class="list_term_and_conditions mb-2">Exceedingly low quality Services
          </li>
          <li class="list_term_and_conditions mb-2">Promoting HMP and/or HMP Services through activities that
            are prohibited by any laws, regulations, and/or third parties' terms of service, as well as
            through any marketing activity that negatively affects our relationships with our users or
            partners.
          </li>
          <li class="list_term_and_conditions mb-2">Services that are removed for violations mentioned above,
            may result in the suspension of the Seller’s account.
          </li>
          <li class="list_term_and_conditions mb-2">Services that are removed for violations are not eligible
            to be restored or edited.
          </li>
          <li class="list_term_and_conditions mb-2">Services may be removed from our Search feature due to
            poor performance and/or user misconduct.
          </li>
          <li class="list_term_and_conditions mb-2">Services may include pre-approved website URLs contained
            within the Service description and requirements box. Services containing websites promoting
            content, which violates HMP’s Terms of Service and/or our Community Standards, will be removed.
          </li>
          <li class="list_term_and_conditions mb-2">All active services created by sellers are required to
            have an appropriate Service image related to the service offered. An option to upload two
            additional images are available to all Sellers. Sellers must deliver the same quality of service
            as shown on their images. Recurring deliveries that don’t match the quality shown on the Service
            images may lead to the Seller’s account losing Seller status or becoming permanently disabled.
          </li>
          <li class="list_term_and_conditions mb-2">Services may contain an approved Service Video uploaded
            through the Service management tools available on HMP.
          </li>
          <li class="list_term_and_conditions mb-2">Statements on the Service Page that undermine or
            circumvent these Terms of Service is prohibited.
          </li>
          <li class="list_term_and_conditions mb-2">Certain categories are available only to Premium Sellers
            to create active services. If you are not a Premium Seller, creating a services available to
            Premium seller only may result in removal of your service.
          </li>
          <li class="list_term_and_conditions mb-2">Sellers can add different packages for a service offered -
            Basic, standard and advance. These are in addition to service and will have extra features for
            additional price. Sellers can also add service extras to thier offered package.
          </li>
          <li class="list_term_and_conditions mb-2">Service Extras may be removed for violations of our Terms
            of Service and/or our Community Standards. For specific terms, please see the Services section
            above for a list of conditions that violate our Terms of Service. An active service is subject
            to be removed due to violations found in Service Extras.
          </li>
          <li class="list_term_and_conditions mb-2">Services offered through Service Extras must be related to
            the base service and part of the deliverables on the Order.
          </li>
          <li class="list_term_and_conditions mb-2">Sevice Extras may cover different categories of services
            that are components to a higher quality delivered service.
          </li>
          <li class="list_term_and_conditions mb-2">Sellers have the option to extend the duration of an Order
            for each Service Extra that is added to the Order. This is to cover the time needed to complete
            the extra service.
          </li>
        </ul>


        <br>
        <h4 id="f">Levels</h4>
        <ul>
          <li class="list_term_and_conditions mb-2">HMP is all about helping Sellers leverage their skills.
          </li>
          <li class="list_term_and_conditions mb-2">We seek to empower top performing Sellers with helpful
            tools to grow their business.
          </li>
          <li class="list_term_and_conditions mb-2">Sellers who invest in self-promotion may achieve greater
            customer satisfaction.
          </li>
          <li class="list_term_and_conditions mb-2">And, if they deliver on time and maintain high quality and
            ratings, HMP may reward them with new statuses, special opportunities, benefits, and tools that
            come with it.
          </li>
          <li class="list_term_and_conditions mb-2">HMP Sellers can gain account Levels based on their
            activity, performance and reputation.
          </li>
        </ul>

        <h4 id="g">The advancement criteria can be found here:</h4>
        <ul>
          <li class="list_term_and_conditions mb-2">Advancement in Levels are updated periodically by an
            automated system.
          </li>
          <li class="list_term_and_conditions mb-2">The current Levels a Seller can achieve are, Level 1, 2,
            and Top Rated.
          </li>
          <li class="list_term_and_conditions mb-2">Sellers who cannot maintain their high quality service,
            experience a severe drop in ratings, or stop delivering on time risk losing their Seller status
            and the benefits that come with it.
          </li>

          <p class="paragrapgh_term_and_conditions"><b>For example: </b> late deliveries, warnings to the
            Seller’s account and cancellations can cause a Seller to move to a different Level.
          </p>

          <li class="list_term_and_conditions mb-2">Advanced levels provide their owners with additional
            benefits, including offering Services for higher prices through Service Extras, or selling their
            Service in multiples.
          </li>
        </ul>

        <br>
        <h4 id="h">Featured Sellers</h4>
        <p class="paragrapgh_term_and_conditions">Featured Sellers are chosen manually by HMP Team through an
          ongoing review process based on seniority, volume of sales, extremely high ratings, exceptional
          customer care, high order completion rate and community leadership.
          Featured Sellers are showcased on the home page of HMP website and they gain access to more
          extensive features than other sellers, including exclusive access to beta features and VIP support.
        </p>
        <p class="paragrapgh_term_and_conditions">Features sellers eligibility is constantly evaluated by HMP to
          ensure that the quality standards and expectations of the Featured selection is kept.
          HMP retains the right to change a Featured Seller status in light of such evaluation.
          In addition, Featured Sellers who cannot maintain their high quality service through a severe drop
          in ratings, stop delivering on time, increased cancellation rate or violate our Terms of Service
          and/or our Community Standards, also risk losing their status and the benefits that come with it.
        </p>


        <br>
        <h4 id="i">Custom proposals</h4>
        <ul>
          <li class="list_term_and_conditions mb-2">Sellers can also send Custom proposals addressing specific
            requirements of a Buyer.
          </li>
          <li class="list_term_and_conditions mb-2">Custom proposals are defined by the Seller with the exact
            description of the service, the price and the time expected to deliver the service.
          </li>
          <li class="list_term_and_conditions mb-2">Custom proposal are sent from the conversation page.
          </li>
          <li class="list_term_and_conditions mb-2">Services provided through Custom proposal may not violate
            HMP's Terms of Service and/or our Community Standards.
          </li>
          <li class="list_term_and_conditions mb-2">Sellers are required to deliver proof of completion of
            services in the Order Page. Please see our Orders section for further details.
          </li>
          <li class="list_term_and_conditions mb-2">Communication for handling Orders should be performed on
            HMP, through the Order Page. Users who engage and communicate off of HMP will not be protected
            by our Terms of Service.
          </li>
        </ul>

        <br>
        <h4 id="j">Stock Images</h4>

        <ul>
          <li class="list_term_and_conditions mb-2">When you purchase stock images, you receive a license to
            use the images under the terms specified by the seller. You do not own the images, and your use
            is subject to the seller’s licensing terms.
          </li>
          <p class="paragrapgh_term_and_conditions"><b>Important:</b> Each selected Stock Image is authorized
            for a one time use.</p>
          <li class="list_term_and_conditions mb-2">The selected image is integrated with the delivered work
            only and not as a stand-alone or for recurring use. Sellers are unauthorized to share the
            original image file with the Buyer or any third party.
          </li>
          <p class="paragrapgh_term_and_conditions"><b>Important:</b> If you cancel an Order that includes a
            Stock Image, the usage of that Stock Image will be cancelled as well and you will no longer be
            able to use the Stock Image.
          </p>
          <li class="list_term_and_conditions mb-2">When using Stock Images, you represent and warrant the
            following:
          </li>
          <li class="list_term_and_conditions mb-2">You may not sell, modify, re-use, re-sell, distribute,
            display, reproduce or make any other use of the Stock Images.
          </li>
          <li class="list_term_and_conditions mb-2">Stock Image(s) may not be used:
          </li>
          <li class="list_term_and_conditions mb-2">On a stand-alone basis with no other content;
          </li>
          <li class="list_term_and_conditions mb-2">For pornographic, defamatory or other unlawful purposes;
          </li>
          <li class="list_term_and_conditions mb-2">As templates used to create multiple copies of the same
            Service;
          </li>
          <li class="list_term_and_conditions mb-2">For the purpose of enabling file-sharing of the image
            file; or
          </li>
          <li class="list_term_and_conditions mb-2">In logos, trademarks, service marks or any other branding
            or identifiers.
          </li>
          <li class="list_term_and_conditions mb-2">If Stock Images featuring individuals is used in
            connection with a sensitive, unflattering or controversial subject, you must include a statement
            that the image is used for illustrative purposes only and the individual is a model.
          </li>
          <li class="list_term_and_conditions mb-2">You may not activate the “right-click” function in Stock
            Images, remove any metadata in Stock Images, or reverse engineer, decompile, or disassemble the
            Stock Image(s) to enable the download or use Stock Images on a stand-alone basis.
          </li>
          <li class="list_term_and_conditions mb-2">No ownership or copyrights to Stock Images are granted to
            you.
          </li>
          <li class="list_term_and_conditions mb-2">Users can contact HMP's <b>customer support</b> department
            for assistance here.
          </li>
        </ul>


        <br>
        <h3>Shipping Physical Deliverables</h3>
        <p class="paragrapgh_term_and_conditions">Some of the services on HMP are delivered physically (arts and
          crafts, collectable items, etc.). For these types of Services, Sellers may decide to define a
          shipping pricing factor.
        </p>
        <p class="paragrapgh_term_and_conditions">Services that include a shipping pricing factor must have
          physical deliverables sent to Buyers.
        </p>
        <p class="paragrapgh_term_and_conditions">Shipping costs added to a Service only pertains to the cost
          Sellers require to ship physical items to Buyers.
        </p>
        <p class="paragrapgh_term_and_conditions">Important: Buyers who purchase Services that require physical
          delivery, will be asked to provide a shipping address.
        </p>
        <p class="paragrapgh_term_and_conditions">Sellers are responsible for all shipping arrangements once the
          Buyer provides the shipping address.
        </p>
        <p class="paragrapgh_term_and_conditions">HMP does not handle or guarantee shipping, tracking, quality,
          and condition of items or their delivery and shall not be responsible or liable for any damages or
          other problems resulting from shipping.
        </p>
        <p class="paragrapgh_term_and_conditions">A tracking number is a great way to avoid disputes related to
          shipping. We require entering the tracking number if available in the Order Page when delivering
          your work.
        </p>

        <br>
        <h4 id="k">BUYERS</h4>
        <p class="paragrapgh_term_and_conditions">You may not offer direct payments to Sellers using payment
          systems outside of the HMP platform.
        </p>
        <p class="paragrapgh_term_and_conditions">HMP retains the right to use all publicly published delivered
          works for HMP marketing and promotional purposes.
        </p>
        <p class="paragrapgh_term_and_conditions">Buyers may request a specific service from the Post a Request
          feature. Services requested on HMP must be an allowed service on HMP. Users should refrain from
          using the Post a Request feature for any purpose other than looking for services on HMP.
        </p>

        <br>
        <h4 id="l">Purchasing</h4>

        <p class="paragrapgh_term_and_conditions">Please refer to the Payment Terms for making Payments through
          the HMP platform and to learn about fees and taxes.
        </p>
        <p class="paragrapgh_term_and_conditions">In addition, Buyers can request a Custom Order which addresses
          specific Buyer requirements, and receive a Custom proposal from Sellers through the site.
        </p>
        <p class="paragrapgh_term_and_conditions">You may not offer Sellers to pay, or make payment using any
          method other than through the hiremyprofile.com site.
          <br><br>
          In case you have been asked to use an alternative payment method, please report it immediately to
          Customer Support here.
        </p>

        <br>
        <h4 id="m">ORDERS</h4>
        <ol>

          <li class="list_term_and_conditions mb-2">Once payment is confirmed, your order will be created and
            given a unique HMP order number.
          </li>
          <li class="list_term_and_conditions mb-2">Sellers must deliver completed files and/or proof of work
            using the Deliver Work button (located on the Order page) according to the service that was
            purchased and advertised.
          </li>
          <li class="list_term_and_conditions mb-2">The Deliver Work button may not be abused by Sellers to
            circumvent Order guidelines described in these Terms of Service. Using the “Deliver Work” button
            when an Order was not fulfilled may result in a cancellation of that Order after review, affect
            the Seller’s rating and result in a warning to Seller.
          </li>
          <li class="list_term_and_conditions mb-2">An Order is marked as Complete after it is marked as
            Delivered and then accepted by a Buyer. An Order will be automatically marked as Complete if not
            accepted and no request for modification was submitted within 3 days after the Order was marked
            as Delivered.
          </li>
          <li class="list_term_and_conditions mb-2">We encourage our Buyers and Sellers to try and settle
            conflicts amongst themselves.
            <br>If for any reason this fails after using the Resolution Center or if you encounter
            non-permitted usage on the Site, users can contact HMp's Customer Support department for
            assistance here.
            <br>For more information about disputes, Order cancellations and refunds please refer to the
            Payment Terms.
          </li>
          <li class="list_term_and_conditions mb-2">If Buyers and Sellers meet in person in order for the
            Seller to perform the service.
          </li>
          <li class="list_term_and_conditions mb-2">In such cases, users should note that HMP does not
            guarantee the behavior, conduct, safety, suitability or ability of either Buyers or Sellers.
          </li>
          <li class="list_term_and_conditions mb-2"> Both Buyers and Sellers agree that the entire risk
            arising out of their meeting and/or their use or performance of local services remains solely
            with them, and HMP has no responsibility or liability related to any local services provided by
            the Sellers.
          </li>
          <li class="list_term_and_conditions mb-2"> In the event that the service is performed on the Buyers’
            premises, Buyers are encouraged to maintain proper insurance policies to cover their liability
            as the premise owner.
          </li>
          <li class="list_term_and_conditions mb-2"> HMP's Terms of Service and Community Standards remain
            applicable to Orders that are performed outside of the marketplace (including, among others, the
            below restrictions on Unlawful Use, Inappropriate Behavior & Language, and Targeted Abuse).
          </li>
        </ol>

        <br>
        <h4 id="n">Handling Orders</h4>
        <p class="paragrapgh_term_and_conditions">When a Buyer orders a Service, the Seller is notified by email
          as well as notifications on the site while logged into the account.
        </p>
        <p class="paragrapgh_term_and_conditions">Sellers are required to meet the delivery time they specified
          when creating their Service. Failing to do so will allow the Buyer to cancel the Order when an Order
          is marked as late and may harm the Seller's status.
        </p>
        <p class="paragrapgh_term_and_conditions">Sellers must send completed files and/or proof of work using
          the Deliver Completed Work button (located on the Order page) to mark the Order as Delivered.
        </p>
        <p class="paragrapgh_term_and_conditions">Users are responsible for scanning all transferred files for
          viruses and malware. HMP will not be held responsible for any damages which might occur due to site
          usage, use of content or files transferred.
        </p>
        <p class="paragrapgh_term_and_conditions">Buyers may use the "Request Revisions" feature located on the
          Order Page while an Order is marked as Delivered if the delivered materials do not match the
          Seller's description on their Service page or they do not match the requirements sent to the Seller
          at the beginning of the order process.
        </p>

        <br>
        <h4 id="o">Reviews</h4>
        <ol>
          <li class="list_term_and_conditions mb-2">Feedback reviews provided by Buyers while completing an
            Order are an essential part of HMP's rating system. Reviews demonstrate the Buyer's overall
            experience with the Sellers and their service. Buyers are encouraged to communicate to the
            Seller any concerns experienced during their active order in regards to the service provided by
            the Seller.
          </li>
          <li class="list_term_and_conditions mb-2">Leaving a Buyer's feedback is a basic prerogative of a
            Buyer. Feedback reviews will not be removed unless there are clear violations of our Terms of
            Service and/or our Community Standards.
          </li>
          <li class="list_term_and_conditions mb-2">To prevent any misuse of our Feedback system, all feedback
            reviews must come from legitimate servcice provided exclusively through the HMP platform from
            users within our Community. Purchases arranged, determined to artificially enhance Seller
            ratings, or to abuse the HMP platform with purchases from additional accounts, will result in a
            permanent suspension of all related accounts.
          </li>
          <li class="list_term_and_conditions mb-2">Feedback comments given by Buyers are publicly displayed
            on a Seller’s Service page.
          </li>
          <li class="list_term_and_conditions mb-2">Work Samples are the delivered images and videos sent to a
            Buyer in a delivery message. Work Samples are added to a Seller's Live Portfolio on their
            Service page if the Buyer chooses to publish the Work Sample while providing a public feedback
            review.
          </li>
          <li class="list_term_and_conditions mb-2">Withholding the delivery of services, files, or
            information required to complete the service with the intent to gain favorable reviews or
            additional services is prohibited.
          </li>
          <li class="list_term_and_conditions mb-2">Responding and posting a review: Once work is delivered,
            the Buyer has three days to respond. If no response is provided within the response period, the
            Order will be considered completed.
          </li>
          <li class="list_term_and_conditions mb-2">Users are allowed to leave reviews on Orders up to 10 days
            after an Order is marked as complete. No new reviews may be added to an Order after 10 days.
          </li>
          <li class="list_term_and_conditions mb-2">Sellers may not solicit the removal of feedback reviews
            from their Buyers through mutual cancellations.
          </li>
          <li class="list_term_and_conditions mb-2">Once the Buyer submits his/her review, the Seller will
            receive a notification and will also have the opportunity to leave a review about working with
            the Buyer. Please Note: At this stage, Sellers cannot see the Buyer's review.
          </li>
          <li class="list_term_and_conditions mb-2">Once both Seller and Buyer have completed their reviews,
            or the 10 days have passed, all posted reviews are made public.
          </li>
          <li class="list_term_and_conditions mb-2">Responding to a review: Once both reviews from the Buyer
            and Seller have been published, the Seller can reply to the Buyer’s review from the Order page,
            seen on the Seller’s Service page, beneath the Buyer’s feedback.
          </li>
          <li class="list_term_and_conditions mb-2">Feedback reviews are unavailable for orders made through
            the Logo Maker.
          </li>
        </ol>


        <br>
        <h4 id="p">Disputes and Cancellations</h4>
        <p class="paragrapgh_term_and_conditions">We encourage our Buyers and Sellers to try and settle
          conflicts amongst themselves. If for any reason this fails after using the Resolution Center or if
          you encounter non-permitted usage on the Site, users can contact HMP's Customer Support department
          for assistance here. For more information about disputes, Order cancellations and refunds please
          refer to the Payment Terms.
        </p>
        <p class="paragrapgh_term_and_conditions">We may use automated systems that analyze your uploaded Logo
          Design to help detect and prevent infringement or other illegal content.
        </p>

        <br>
        <h4 id="q">User Conduct and Protection</h4>
        <p class="paragrapgh_term_and_conditions">HMP enables people around the world to create, share, sell and
          purchase nearly any service they need at an unbeatable value. Services offered on HMP reflect the
          diversity of an expanding Freelnce economy. Members of the HMP community communicate and engage
          through active orders, social media, and on HMP’s Community Forums.
        </p>
        <p class="paragrapgh_term_and_conditions">HMP maintains a friendly, community spirited, and professional
          environment. Users should keep to that spirit while participating in any activity or extensions of
          HMP. This section relates to the expected conduct users should adhere to while interacting with each
          other on HMP.
        </p>
        <p class="paragrapgh_term_and_conditions">To report a violation of our Terms of Service and/or our
          Community Standards, User Misconduct, or inquiries regarding your account, please contact our
          Customer Support team here.
        </p>

        <br>
        <h4 id="r">Basics</h4>
        <p class="paragrapgh_term_and_conditions">To protect our users' privacy, user identities are kept
          anonymous. Requesting or providing Email addresses, Skype/IM/telegram usernames, telephone numbers,
          whatapp number or any other personal contact details to communicate outside of HMP in order to
          circumvent or abuse the HMP messaging system or HMP platform is not permitted.
        </p>
        <p class="paragrapgh_term_and_conditions">Any necessary exchange of personal information required to
          continue a service may be exchanged within the Order Page.
        </p>
        <p class="paragrapgh_term_and_conditions">HMP does not provide any guarantee of the level of service
          offered to Buyers. You may use the dispute resolution tools provided to you in the Order Page.
        </p>
        <p class="paragrapgh_term_and_conditions">HMP does not provide protection for users who interact outside
          of the HMP platform.
        </p>
        <p class="paragrapgh_term_and_conditions">All information and file exchanges must be performed
          exclusively on HMP's platform.
        </p>
        <p class="paragrapgh_term_and_conditions">Rude, abusive, improper language, or violent messages will not
          be tolerated and may result in an account warning or the suspension/removal of your account.
        </p>
        <p class="paragrapgh_term_and_conditions">HMP is open to everyone. You undertake not to discriminate
          against any other user based on gender, race, age, religious affiliation, sexual orientation or
          otherwise and you acknowledge that such discrimination may result in the suspension/removal of your
          account.
        </p>
        <p class="paragrapgh_term_and_conditions">Users may not submit proposals or solicit parties introduced
          through HMP to contract, engage with, or pay outside of HMP.
        </p>

        <br>
        <h4 id="s">Orders</h4>
        <p class="paragrapgh_term_and_conditions">Users with the intention to defame competing Sellers by
          ordering from competing services will have their reviews removed or further account status related
          actions determined by review by our Trust & Safety team.
        </p>
        <p class="paragrapgh_term_and_conditions">Users are to refrain from spamming or soliciting previous
          Buyers or Sellers to pursue removing/modifying reviews or cancelling orders that do not align on
          Order Cancellation or Feedback policies.
        </p>

        <br>
        <h4 id="t">Services</h4>
        <p class="paragrapgh_term_and_conditions">Users may report services created by freelancer to Customer
          Support that may be in violation of HMP's Terms of Service based on the reported Service's
          replicated similarity to pre-existing services (copycats).
        </p>
        <p class="paragrapgh_term_and_conditions">Sellers warrant that any content included in their Services
          shall be original work conceived by the Sellers and shall not infringe any third party rights,
          including, without limitation, copyrights, trademarks or service marks. In the event that certain
          music or stock-footage media are incorporated within the Services, Sellers represent and warrant
          that they hold a valid license to use such music and/or footage and to include them in the Services.
        </p>
        <p class="paragrapgh_term_and_conditions">HMP will respond to clear and complete notices of alleged
          copyright or trademark infringement. Our Intellectual Property claims procedures can be reviewed
          here.
        </p>

        <br>
        <h4 id="u">Reporting Violations</h4>
        <p class="paragrapgh_term_and_conditions">If you come across any content that may violate our Terms of
          Service and/or our Community Standards, you should report it to us through the appropriate channels
          created to handle those issues as outlined in our Terms of Service. All cases are reviewed by our
          Trust & Safety team. To protect individual privacy, the results of the investigation are not shared.
          You can review our Privacy Policy for more information.
        </p>

        <br>
        <h4 id="v">Violations</h4>
        <p class="paragrapgh_term_and_conditions">Users may receive a warning to their account for violations of
          our Terms of Service and/or our Community Standards or any user misconduct reported to our Trust and
          Safety team. A warning will be sent to the user's email address and will be displayed for such user
          on the Site. Warnings do not limit account activity, but can lead to your account losing Seller
          statuses or becoming permanently disabled based on the severity of the violation.
        </p>

        <br>
        <h4 id="w">Non-Permitted Usage</h4>
        <ul>
          <li class="list_term_and_conditions mb-2"><b>Adult Services & Pornography -</b> HMP does not allow
            any exchange of adult oriented or pornographic materials and services.
          </li>
          <li class="list_term_and_conditions mb-2"><b>Inappropriate Behavior & Language -</b> Communication
            on HMP should be friendly, constructive, and professional. HMP condemns bullying, harassment,
            and hate speech towards others. We allow users a medium for which messages are exchanged between
            individuals, a system to rate orders, and to engage on larger platforms such as our Community
            Forum and Social Media pages.
          </li>
          <li class="list_term_and_conditions mb-2"><b>Phishing and Spam -</b> Members’ security is a top
            priority. Any attempts to publish or send malicious content with the intent to compromise
            another member’s account or computer environment is strictly prohibited. Please respect our
            members privacy by not contacting them with offers, questions, suggestions or anything which is
            not directly related to their Services or orders.
          </li>
          <li class="list_term_and_conditions mb-2"><b>Privacy & Identity -</b> You may not publish or post
            other people's private and confidential information. Any exchange of personal information
            required for the completion of a service must be provided in the Order Page. Sellers further
            confirm that whatever information they receive from the Buyer, which is not public domain, shall
            not be used for any purpose whatsoever other than for the delivery of the work to the Buyer. Any
            users who engage and communicate off of HMP will not be protected by our Terms of Service.
          </li>
          <li class="list_term_and_conditions mb-2"><b>Authentic HMP Profile -</b> You may not create a false
            identity on HMP, misrepresent your identity, create a HMP profile for anyone other than yourself
            (a real person), or use or attempt to use another user’s account or information; Your profile
            information, including your description, skills, location, etc., while may be kept anonymous,
            must be accurate and complete and may not be misleading, illegal, offensive or otherwise
            harmful. HMP reserves the right to require users to go through a verification process in order
            to use the Site (whether by using ID, phone, camera, etc.).
          </li>
          <li class="list_term_and_conditions mb-2"><b>Intellectual Property Claims -</b> HMP will respond to
            clear and complete notices of alleged copyright or trademark infringement, and/or violation of
            third party’s terms of service. Our Intellectual Property claims procedures can be reviewed
            here.
          </li>
          <li class="list_term_and_conditions mb-2"><b>Fraud / Unlawful Use -</b> You may not use HMP for any
            unlawful purposes or to conduct illegal activities.
          </li>
        </ul>

        <br>
        <h4 id="x">Abuse and Spam</h4>

        <p class="paragrapgh_term_and_conditions"><b>1. Multiple Accounts -</b> To prevent fraud and abuse,
          users are limited to one active HMP account and one active HMP Business account. Any additional
          account determined to be created to circumvent guidelines, promote competitive advantages, or
          mislead the HMP community will be disabled. Mass account creation may result in disabling of all
          related accounts. Note: any violations of HMP Terms of Service and/or our Community Standards is a
          cause for permanent suspension of all accounts.
        </p>
        <p class="paragrapgh_term_and_conditions"><b>2. Targeted Abuse -</b> We do not tolerate users who engage
          in targeted abuse or harassment towards other users on HMP. This includes creating new multiple
          accounts to harass members through our message or ordering system.
        </p>
        <p class="paragrapgh_term_and_conditions"><b>3. Selling Accounts -</b> You may not buy or sell HMP
          accounts.
        </p>


        <br>
        <h4 id="y">Proprietary Restrictions</h4>
        <p class="paragrapgh_term_and_conditions">The Site, including its general layout, look and feel, design,
          information, content and other materials available thereon, is exclusively owned by HMP and
          protected by trademark, and other intellectual property laws.
          <b>Hiremyprofile is registered trademarks owned exclusively by Preferred Outsourcing.</b>
          Users have no right, and specifically agree not to do the following with respect to the Site or any
          part, component or extension of the Site (including its mobile applications):

        <ul style="list-style-type:upper-alpha;">
          <li class="list_term_and_conditions mb-2"> Copy, transfer, adapt, modify, distribute, transmit,
            display, create derivative works, publish or reproduce it, in any manner;
          </li>
          <li class="list_term_and_conditions mb-2"> Reverse assemble, decompile, reverse engineer or
            otherwise attempt to derive its source code, underlying ideas, algorithms, structure or
            organization;
          </li>
          <li class="list_term_and_conditions mb-2"> Remove any copyright notice, identification or any other
            proprietary notices;
          </li>
          <li class="list_term_and_conditions mb-2"> Use automation software (bots), hacks, modifications
            (mods) or any other unauthorized third-party software designed to modify the Site;
          </li>
          <li class="list_term_and_conditions mb-2"> Attempt to gain unauthorized access to, interfere with,
            damage or disrupt the Site or the computer systems or networks connected to the Site;
          </li>
          <li class="list_term_and_conditions mb-2"> Circumvent, remove, alter, deactivate, degrade or thwart
            any technological measure or content protections of the Site;
          </li>
          <li class="list_term_and_conditions mb-2"> Use any robot, spider, crawlers or other automatic
            device, process, software or queries that intercepts, “mines,” scrapes or otherwise accesses the
            Site to monitor, extract, copy or collect information or data from or through the Site, or
            engage in any manual process to do the same,
          </li>
          <li class="list_term_and_conditions mb-2">Introduce any viruses, trojan horses, worms, logic bombs
            or other materials that are malicious or technologically harmful into our systems,
          </li>
          <li class="list_term_and_conditions mb-2"> Use the Site in any manner that could damage, disable,
            overburden or impair the Site, or interfere with any other users’ enjoyment of the Site or
          </li>
          <li class="list_term_and_conditions mb-2"> Access or use the Site in any way not expressly permitted
            by these Terms of Service. Users also agree not to permit or authorize anyone else to do any of
            the foregoing.
          </li>
        </ul>

        </p>
        <p class="paragrapgh_term_and_conditions">Except for the limited right to use the Site according to
          these Terms of Service, HMP owns all right, title and interest in and to the Site (including any and
          all intellectual property rights therein) and you agree not to take any action(s) inconsistent with
          such ownership interests. We reserve all rights in connection with the Site and its content (other
          than UGC) including, without limitation, the exclusive right to create derivative works.
        </p>

        <br>
        <h4 id="z">Feedback Rights</h4>
        <p class="paragrapgh_term_and_conditions">To the extent that you provide HMP with any comments,
          suggestions or other feedback regarding the HMP platform, you will be deemed to have granted HMP an
          exclusive, royalty-free, fully paid up, perpetual, irrevocable, worldwide ownership rights in the
          Feedback. HMP is under no obligation to implement any Feedback it may receive from users.
        </p>

        <br>
        <h4 id="aa">Confidentiality</h4>
        <p class="paragrapgh_term_and_conditions">Sellers should recognize that there might be a need for Buyers
          to disclose certain confidential information to be used by Sellers for the purpose of delivering the
          ordered work, and to protect such confidential information from unauthorized use and disclosure.
          <br> Therefore, Sellers agree to treat any information received from Buyers as highly sensitive, top
          secret and classified material.
          <br> Without derogating from the generality of the above, Sellers specifically agree to
        <ul style="list-style-type: upper-roman;">

          <li class="list_term_and_conditions mb-2">Maintain all such information in strict confidence;
          </li>
          <li class="list_term_and_conditions mb-2"> Not disclose the information to any third parties;
          </li>
          <li class="list_term_and_conditions mb-2"> Not use the information for any purpose except for
            delivering the ordered work; and
          </li>
          <li class="list_term_and_conditions mb-2"> Not to copy or reproduce any of the information without
            the Buyer’s permission.
          </li>
        </ul>
        </p>

        <br>
        <h4 id="ab">General Terms</h4>
        <p class="paragrapgh_term_and_conditions">HMP reserves the right to put any account on hold or
          permanently disable accounts due to breach of these Terms of Service and/or our Community Standards
          or due to any illegal or inappropriate use of the Site or services.
        </p>
        <p class="paragrapgh_term_and_conditions">Violation of HMP's Terms of Service and/or our Community
          Standards may get your account disabled permanently.
        </p>
        <p class="paragrapgh_term_and_conditions">Users with disabled accounts will not be able to sell or buy
          on HMP.
        </p>
        <p class="paragrapgh_term_and_conditions">Users who have violated our Terms of Service and/or our
          Community Standards and had their account disabled may contact our Customer Support team for more
          information surrounding the violation and status of the account.
        </p>
        <p class="paragrapgh_term_and_conditions">Users have the option to enable account Security features to
          protect their account from any unauthorized usage.
        </p>
        <p class="paragrapgh_term_and_conditions">Users must be able to verify their account ownership through
          Customer Support by providing materials that prove ownership of that account.
        </p>
        <p class="paragrapgh_term_and_conditions">Disputes should be handled using HMP's dispute resolution
          tools ('Resolution Center' on the order page) or by contacting HMP Customer Support.
        </p>
        <p class="paragrapgh_term_and_conditions">HMP may make changes to its Terms of Service from time to
          time. When these changes are made, HMP will make a new copy of the terms of service available on
          this page.
        </p>
        <p class="paragrapgh_term_and_conditions">You understand and agree that if you use HMP after the date on
          which the Terms of Service have changed, HMP will treat your use as acceptance of the updated Terms
          of Service.
        </p>

        <br>
        <h4 id="ac">User Generated Content</h4>
        <p class="paragrapgh_term_and_conditions">User Generated Content ("UGC") - refers to the content added
          by users as opposed to content created by the Site.
          <br> All content uploaded to HMP by our users (Buyers and Sellers) is User Generated Content.
          <br> HMP does not check user uploaded/created content for appropriateness, violations of copyright,
          trademarks, other rights or violations and the user uploading/creating such content shall be solely
          responsible for it and the consequences of using, disclosing, storing, or transmitting it.
          <br> By uploading to, or creating content on, the HMP platform, you represent and warrant that you
          own or have obtained all rights, licenses, consents, permissions, power and/or authority, necessary
          to use and/or upload such content and that such content or the use thereof in the Site does not and
          shall not
        </p>
        <ul style="list-style-type: lower-alpha;">
          <li class="list_term_and_conditions mb-2"> Infringe or violate any intellectual property,
            proprietary or privacy, data protection or publicity rights of any third party;
          </li>
          <li class="list_term_and_conditions mb-2">Violate any applicable local, state, federal and
            international laws, regulations and conventions; and/or
          </li>
          <li class="list_term_and_conditions mb-2">Violate any of your or third party’s policies and/or terms
            of service.
          </li>
        </ul>
        <p class="paragrapgh_term_and_conditions">
          We invite everyone to report violations together with proof of ownership as appropriate. <br>
          Reported violating content may be removed or disabled.
        </p>
        <p class="paragrapgh_term_and_conditions">Furthermore, HMP is not responsible for the content, quality
          or the level of service provided by the Sellers (even if they are Pro Sellers, Top Rated Sellers,
          offer Promoted Services or otherwise).
          We provide no warranty with respect to the Services, their delivery, any communications between
          Buyers and Sellers.
          We encourage users to take advantage of our rating system, our community and common sense in
          choosing appropriate services.
        </p>
        <p class="paragrapgh_term_and_conditions">By offering a service, the Seller undertakes that he/she has
          sufficient permissions, rights and/or licenses to provide, sell or resell the service that is
          offered on HMP. Sellers advertising online their services must comply with laws and terms of service
          of the advertising platform or relevant website used to advertise. Failing to do so may result in
          removal of the Service and may lead to the suspension of Seller's account.
        </p>
        <p class="paragrapgh_term_and_conditions">For specific terms related to Intellectual Property rights and
          for reporting claims of copyright infringement (DMCA notices) or trademark infringement - please see
          our Intellectual Property Claims Policy which forms an integral part of these Terms of Service. Note
          that it is our policy in appropriate circumstances to disable and/or terminate the accounts of users
          who are repeat infringers.
        </p>

        <br>
        <h4 id="ad">Ownership</h4>
        <p class="paragrapgh_term_and_conditions"><b>Ownership and limitations: </b>
        <ul>
          <li class="list_term_and_conditions mb-2">When purchasing a Service on HMP, unless clearly stated
            otherwise on the Seller's Service page/description, when the work is delivered, and subject to
            payment, the Buyer is granted all intellectual property rights, including but not limited to,
            copyright in the work delivered from the Seller, and the Seller waives any and all moral rights
            therein.
          </li>
          <li class="list_term_and_conditions mb-2">Accordingly, the Seller expressly assigns to the Buyer the
            copyright in the delivered work.
          </li>
          <li class="list_term_and_conditions mb-2">All transfer and assignment of intellectual property to
            the Buyer shall be subject to full payment for the Service, and the delivery may not be used if
            payment is cancelled for any reason.
          </li>
          <li class="list_term_and_conditions mb-2">For removal of doubt, in custom created work (such as art
            work, design work, report generation etc.), the delivered work and its copyright shall be the
            exclusive property of the Buyer and, upon delivery, the Seller agrees that it thereby, pursuant
            to these Terms of Service, assigns all right, title and interest in and to the delivered work to
            the Buyer.
          </li>
          <li class="list_term_and_conditions mb-2">Some Services (including for custom created work) charge
            additional payments (through Service Extras) for a Commercial Use License. This means that if
            you purchase the Service for personal use, you will own all rights you require for such use, and
            will not need the Commercial Use License.
          </li>
          <li class="list_term_and_conditions mb-2">If you intend to use it for any charge or other
            consideration, or for any purpose that is directly or indirectly in connection with any
            business, or other undertaking intended for profit, you will need to buy the Commercial Use
            License through a Service Extra and will have broader rights that cover your business use.
          </li>
        </ul>
        </p>

        <br>
        <h3>For Voice Over Services </h3>
        <ul>
          <li class="list_term_and_conditions mb-2"> When the work is delivered, and subject to payment, the
            Buyer is purchasing basic rights, (which means the Buyer is paying a one time fee allowing them
            to use the work forever and for any purpose except for commercials, radio, television and
            internet commercial spots).
          </li>
          <li class="list_term_and_conditions mb-2">If you intend to use the Voice Over to promote a product
            and/or service (with the exception of paid marketing channels), you will need to purchase the
            Commercial Rights (Buy-Out) through Service Extra.
          </li>
          <li class="list_term_and_conditions mb-2"> If you intend to use the Voice Over in radio, television
            and internet commercials, you will need to purchase the Full Broadcast Rights (Buy-Out) through
            Service Extra.
          </li>
          <li class="list_term_and_conditions mb-2">For further information on the type of buy-outs, please
            read below.
          </li>
        </ul>
        </p>
        <p class="paragrapgh_term_and_conditions">Furthermore, users (both Buyers and Sellers) agree that unless
          they explicitly indicate otherwise, the content users voluntarily create/upload to HMP, including
          Service texts, photos, videos, usernames, user photos, user videos and any other information,
          including the display of delivered work, may be used by HMP for no consideration for marketing
          and/or other purposes.
        </p>

        <br>
        <h4 id="ae">Voice Over Commercial Buy-Out</h4>

        <p class="paragrapgh_term_and_conditions">When purchasing a Voice Over Service, the Seller grants you a
          perpetual, exclusive, non-transferable, worldwide license to use the purchased Voice Over (except
          for commercials, radio, television and internet commercial spots).
        </p>
        <p class="paragrapgh_term_and_conditions">"Upgrade to a ""Commercial Rights Buy-Out"" with your order
          and use the Voice Over in any way to promote your business, create internal training materials, or
          use it in non-broadcast projects!
        </p>

        <h4 id="af"> Here's what you can do with a Commercial Rights Buy-Out:
        </h4>
        <ul>
          <li class="list_term_and_conditions mb-2">Promote your business: Use the Voice Over in explainer
            videos on your website, social media, or email campaigns.
          </li>
          <li class="list_term_and_conditions mb-2">Create internal content: Use it in audiobooks, podcast
            intros, or training videos for your employees.
          </li>
        </ul>

        <br>
        <h4 id="ag">What's not allowed?</h4>
        <ol class="list-style-type:loer-alpha;">
          <li class="list_term_and_conditions mb-2">Using the Voice Over in anything illegal, immoral, or
            damaging to someone's reputation.
          </li>
          <li class="list_term_and_conditions mb-2">Using it in paid marketing channels (like TV commercials
            or paid online ads)."
          </li>
          <li class="list_term_and_conditions mb-2">By purchasing a Full Broadcast Rights (Buy-Out) with your
            order, in addition to the Commercial Rights, the Seller grants you with a license for full
            broadcasting, which includes internet, radio, and TV "paid channels" including (by way of
            example): television commercials, radio commercials, internet radio, and music/video streaming
            platforms, and strictly excludes any illegal, immoral or defamatory purpose.
          </li>
        </ol>


        <h4 id="ah">This Buy-Out is subject to HMP's Terms of Service.</h4>
        <ul>
          <li class="list_term_and_conditions mb-2">There is no warranty, express or implied, with the
            purchase of this delivery, including with respect to fitness for a particular purpose.
          </li>
          <li class="list_term_and_conditions mb-2">Neither the Seller nor HMP will be liable for any claims,
            or incidental, consequential or other damages arising out of this license, the delivery or your
            use of the delivery.
          </li>
        </ul>

        <br>
        <h4class="ai">Service Commercial Use License</h4>
          <p class="paragrapgh_term_and_conditions">"By purchasing a ""Commercial Use License"" with your order,
            you gain the exclusive right to use the delivered work for any business purpose you like, forever
            and anywhere in the world. This includes using it in your advertising, promotions, website, product,
            software, or any other business tool.
          </p>
          <p class="paragrapgh_term_and_conditions">Here's what's NOT included: Using the work for anything
            illegal, immoral, or harmful to someone's reputation.
          </p>

          <h4 id="aj">Important Notes:</h4>
          <ul>

            <li class="list_term_and_conditions mb-2">I (the Seller) still own the original work, even though
              you have a license to use it.
            </li>

            <li class="list_term_and_conditions mb-2">This license follows the rules outlined in HMP's Terms of
              Service.
            </li>

            <li class="list_term_and_conditions mb-2">There are no guarantees that the work will be perfect for
              your specific needs, and neither I nor HMP are responsible for any issues that arise from your
              use of the work."
            </li>
          </ul>

          <br>
          <h4 id="ak">Disclaimer of Warranties</h4>
          <ol>
            <li class="list_term_and_conditions mb-2">YOUR USE OF THE SITE, ITS CONTENT AND ANY SERVICES OR
              ITEMS OBTAINED THROUGH THE SITE IS AT YOUR OWN RISK.
            </li>
            <li class="list_term_and_conditions mb-2">THE SITE, ITS CONTENT AND ANY SERVICES OR ITEMS OBTAINED
              THROUGH THE SITE ARE PROVIDED ON AN "AS IS" AND "AS AVAILABLE" BASIS, WITHOUT ANY WARRANTIES OF
              ANY KIND, EITHER EXPRESS OR IMPLIED.
            </li>
            <li class="list_term_and_conditions mb-2">NEITHER HMP NOR ANY PERSON ASSOCIATED WITH HMP MAKES ANY
              WARRANTY OR REPRESENTATION WITH RESPECT TO THE COMPLETENESS, SECURITY, RELIABILITY, QUALITY,
              ACCURACY OR AVAILABILITY OF THE SITE.
            </li>
            <li class="list_term_and_conditions mb-2">THE FOREGOING DOES NOT AFFECT ANY WARRANTIES WHICH CANNOT
              BE EXCLUDED OR LIMITED UNDER APPLICABLE LAW.
            </li>
          </ol>

          <br>
          <h4 id="al">Machine Translation</h4>
          <ul>

            <li class="list_term_and_conditions mb-2">Certain user-generated content on the Site has been
              translated for your convenience using translation software powered by Google Translate.
            </li>
            <li class="list_term_and_conditions mb-2"> Reasonable efforts have been made to provide an accurate
              translation, however, no automated translation is perfect nor is it intended to replace human
              translators.
            </li>
            <li class="list_term_and_conditions mb-2">Such translations are provided as a service to users of
              the Site, and are provided "as is".
            </li>
            <li class="list_term_and_conditions mb-2"> No warranty of any kind, either expressed or implied, is
              made as to the accuracy, reliability, or correctness of such translations made from English into
              any other language.
            </li>
            <li class="list_term_and_conditions mb-2"> Some user-generated content (such as images, videos,
              Flash, etc.) may not be accurately translated or translated at all due to the limitations of the
              translation software.
            </li>
            <li class="list_term_and_conditions mb-2">The official text is the English version of the Site.
            </li>
            <li class="list_term_and_conditions mb-2">Any discrepancies or differences created in the
              translation are not binding and have no legal effect for compliance or enforcement purposes.
            </li>
          </ul>
          <p class="paragrapgh_term_and_conditions"> If any questions arise related to the accuracy of the
            information contained in the translated content, please refer to the English version of the content
            which is the official version.
          </p>

          <br>
          <h4 id="am">Limitation on Liability</h4>
          <p class="paragrapgh_term_and_conditions">IN NO EVENT WILL HMP, ITS AFFILIATES OR THEIR LICENSORS,
            SERVICE PROVIDERS, EMPLOYEES, AGENTS, OFFICERS OR DIRECTORS BE LIABLE FOR DAMAGES OF ANY KIND, UNDER
            ANY LEGAL THEORY, ARISING OUT OF OR IN CONNECTION WITH YOUR USE, OR INABILITY TO USE, THE SITE, ANY
            WEBSITES LINKED TO IT, ANY CONTENT ON THE SITE OR SUCH OTHER WEBSITES OR ANY SERVICES OR ITEMS
            OBTAINED THROUGH THE SITE OR SUCH OTHER WEBSITES, INCLUDING ANY DIRECT, INDIRECT, SPECIAL,
            INCIDENTAL, CONSEQUENTIAL OR PUNITIVE DAMAGES, INCLUDING BUT NOT LIMITED TO, PERSONAL INJURY, PAIN
            AND SUFFERING, EMOTIONAL DISTRESS, LOSS OF REVENUE, LOSS OF PROFITS, LOSS OF BUSINESS OR ANTICIPATED
            SAVINGS, LOSS OF USE, LOSS OF GOODWILL, LOSS OF DATA, AND WHETHER CAUSED BY TORT (INCLUDING
            NEGLIGENCE), BREACH OF CONTRACT OR OTHERWISE, EVEN IF FORESEEABLE.
          </p>
          <p class="paragrapgh_term_and_conditions">THE FOREGOING DOES NOT AFFECT ANY LIABILITY WHICH CANNOT BE
            EXCLUDED OR LIMITED UNDER APPLICABLE LAW.
          </p>
      </div>
    </div>
  </div>



  <?php require_once("includes/footer.php"); ?>
</body>

</html>
<?php
session_start();
require_once("includes/db.php");
require_once("social-config.php");
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
    <title><?= $site_name; ?> - Copyright Policy</title>
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
        .termandconditiondivsec {
            /* border: 1px solid grey; */
            width: 85%;
            margin: auto;
        }

        .paragrapgh_copyright_policy {
            font-size: 1.3rem;
            line-height: 2.5rem;
            text-align: justify;
        }

        .list_copyright_policy {
            font-size: 1.3rem;
            line-height: 2.5rem;
            text-align: justify;
        }

        .address_copyright_policy {
            font-size: 1.1rem;
            line-height: 2rem;
            text-align: justify;
        }



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

            width: 85%;
            margin: auto;
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

        .address_copyright_policy {
            font-size: 1.1rem;
            line-height: 2rem;
            text-align: justify;
        }

        @media only screen and (max-width: 768px) {
            .termandconditiondivside {
                display: none;
            }

            .termandconditiondivthird {
                width: 100%;
                padding: 0rem 0rem;
                text-align: center;
            }

            .termandconditiondivthird {
                height: auto !important;
                overflow-y: unset !important;

            }
        }

        .termandconditiondivthird {
            height: 70vh;
            overflow-y: auto;
        }




        .termandconditiondivthird::-webkit-scrollbar {
            width: 5px;

        }

        .termandconditiondivthird::-webkit-scrollbar-track {
            background: #f1f1f1;

            border-radius: 10px;

        }

        .termandconditiondivthird::-webkit-scrollbar-thumb {
            background-color: #00CEDC;

            border-radius: 10px;

            min-height: 5px;

        }


        .termandconditiondivthird::-webkit-scrollbar-thumb:hover {
            background-color: #555;

        }

        @media only screen and (max-width: 768px) {
            .termandconditiondivside {
                display: none;
                /* Hide the sidebar */
            }
        }
    </style>
</head>

<body class="is-responsive">
    <?php require_once("includes/header.php"); ?>



    <div class="container-fluid mt-5 mb-5 pb-1 pt-5 site-theme-color overflow_ram">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Copyright Policy</h1>
                <p class="paragrapgh_copyright_policy text-center lead pb-0"> Copyright & Intellectual Property Claims
                    Policy </p>
                <hr>
            </div>
        </div>
        <div class="main_class_privacy">
            <div class="termandconditiondivside">
                <ul class="pl-1">
                    <a href="#ram1">
                        <li class="sidebar-list-details" title="DMCA COUNTER-NOTICE REQUIREMENTS"> COUNTER-NOTICE
                        </li>

                    </a>
                    <a href="#ram2">
                        <li class="sidebar-list-details" title="REPORTING TRADEMARK INFRINGEMENT">REPORTING </li>

                    </a>

                    <a href="#ram5">
                        <li class="sidebar-list-details" title="REPEAT INFRINGERS">REPEAT INFRINGERS</li>

                    </a>


                </ul>
            </div>
            <div class="termandconditiondivthird">
                <p class="paragrapgh_copyright_policy">hiremyprofile.com content is based on User Generated Content
                    (UGC). HMP does not check user uploaded/created content for violations of copyright or other rights.
                    However, if you believe any of the uploaded content violates your copyright or a related exclusive
                    right, you should follow the process below. HMP looks into reported violations and removes or
                    disables content shown to be violating third party rights.
                </p>
                <p class="paragrapgh_copyright_policy">In case you encounter any violation of intellectual property
                    rights on HMP, please use HMP's easy-to-use online tools, which allow users to add all of the
                    relevant information to their report. Learn more on how to report content on HMP here.
                </p>

                <p class="paragrapgh_copyright_policy">Note that we will provide the user who is allegedly infringing
                    your copyright with information about the Notice and allow them to respond. In cases where
                    sufficient proof of infringement is provided, we may remove or suspend the reported materials prior
                    to receiving the user's response. In cases where the allegedly infringing user provides us with a
                    proper counter-notice indicating that it is permitted to post the allegedly infringing material, we
                    may notify you and then replace the removed or disabled material.
                </p>
                <p class="paragrapgh_copyright_policy">Please be aware that if you knowingly materially misrepresent
                    that material or activity on the Website is infringing your copyright, you may be held liable for
                    damages (including costs and attorneys' fees)
                </p>

                <br>
                <h4>DMCA COUNTER-NOTICE REQUIREMENTS</h4>
                <p class="paragrapgh_copyright_policy">If you believe that material you posted on the site was removed
                    or access to it was disabled by mistake or misidentification, you may file a counter-notice with us
                    (a "Counter-Notice") by submitting written notification to our DMCA Claims agent (identified above).
                    Pursuant to the DMCA, the Counter-Notice must include substantially the following:
                </p>
                <ol>
                    <li class="list_copyright_policy">Your physical or electronic signature.
                    </li>
                    <li class="list_copyright_policy">An identification of the material that has been removed or to
                        which access has been disabled and the location at which the material appeared before it was
                        removed or access disabled.
                    </li>
                    <li class="list_copyright_policy">Adequate information by which we can contact you (including your
                        name, postal address, telephone number and, if available, e-mail address).
                    </li>
                    <li class="list_copyright_policy">A statement under penalty of perjury by you that you have a good
                        faith belief that the material identified above was removed or disabled as a result of a mistake
                        or misidentification of the material to be removed or disabled.
                    </li>
                    <li class="list_copyright_policy">A statement that you will consent to the jurisdiction of the
                        Federal District Court for the judicial district in which your address is located (or if you
                        reside outside the United States for any judicial district in which the Website may be found)
                        and that you will accept service from the person (or an agent of that person) who provided the
                        Website with the complaint at issue.
                    </li>
                </ol>
                <p class="paragrapgh_copyright_policy">The DMCA allows us to restore the removed content if the party
                    filing the original DMCA Notice does not file a court action against you within ten business days of
                    receiving the copy of your Counter-Notice. Please be aware that if you knowingly materially
                    misrepresent that material or activity on the Website was removed or disabled by mistake or
                    misidentification, you may be held liable for damages (including costs and attorneys' fees) under
                    Section 512(f) of the DMCA.
                </p>


                <br>
                <h4>REPORTING TRADEMARK INFRINGEMENT</h4>
                <p class="paragrapgh_copyright_policy">hiremyprofile.com's content is based on User Generated Content
                    (UGC). HMP does not check user uploaded/created content for violations of trademark or other rights.
                    However, if you believe any of the uploaded content violates your trademark, you should follow the
                    process below. HMP looks into reported violations and removes or disables content shown to be
                    violating third party trademark rights.
                </p>
                <p class="paragrapgh_copyright_policy">In order to allow us to review your report promptly and
                    effectively, a trademark infringement notice ("TM Notice") should include the following:
                </p>
                <ul>
                    <li class="list_copyright_policy">Identification of your trademark and the goods/services for which
                        you claim trademark rights.
                    </li>
                    <li class="list_copyright_policy">Your trademark registration certificate and a printout from the
                        pertinent country's trademark office records showing current status and title of the
                        registration. Alternatively, a statement that your mark is unregistered, together with a court
                        ruling confirming your rights.
                    </li>
                    <li class="list_copyright_policy">A short description of how our user(s) allegedly infringe(s) your
                        trademark(s).
                    </li>
                    <li class="list_copyright_policy">Clear reference to the materials you allege are infringing and
                        which you are requesting to be removed, for example, the Service url, a link to the deliverable
                        provided to a user, etc.
                    </li>
                    <li class="list_copyright_policy">Your complete name, address, email address, and telephone number.
                    </li>
                    <li class="list_copyright_policy">A statement that you have a good faith belief that use of the
                        material in the manner complained of is not authorized by the trademark owner, its agent, or the
                        law.
                    </li>
                    <li class="list_copyright_policy">A statement made under penalty of perjury that the information
                        provided in the notice is accurate and that you are the trademark or are authorized to make the
                        complaint on behalf of the trademark owner.
                    </li>
                    <li class="list_copyright_policy">Your electronic or physical signature
                    </li>
                </ul>

                <p class="paragrapgh_copyright_policy">You can send your Notice to:
                </p>


                <h5>By Mail:</h5>
                <address class="address_copyright_policy">
                    Preferred Outsourcing Pvt. Ltd.<br>
                    Attn: Copyright claims<br>
                    126, FIRST FLOOR BANK ROAD<br>
                    Ambala, Haryana, India 133001
                </address>

                <br>
                <h5>By Email:-</h5>
                <p class="paragrapgh_copyright_policy">Alternatively you can submit the Notice electronically to
                    copyrights@hiremyprofile.com.
                </p>

                <p class="paragrapgh_copyright_policy"><b>Note: </b> that we will provide the user who is allegedly
                    infringing your trademark with information about the TM Notice and allow them to respond.
                    In cases where sufficient proof of infringement is provided, we may remove or suspend the reported
                    materials prior to receiving the user's response.
                    In cases where the allegedly infringing user provides us with information indicating that it is
                    permitted to post the allegedly infringing material, we may notify you and then replace the removed
                    or disabled material.
                    In all such cases, we will act in accordance with applicable law.
                </p>

                <br>
                <h4>REPEAT INFRINGERS</h4>
                <p class="paragrapgh_copyright_policy">It is our policy in appropriate circumstances to disable and/or
                    terminate the accounts of users who are repeat infringers.
                </p>

            </div>
        </div>
    </div>

















    <?php require_once("includes/footer.php"); ?>
</body>

</html>
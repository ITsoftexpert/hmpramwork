<?php
session_start();
require_once("includes/db.php");
if (!isset($_SESSION['seller_user_name'])) {

    echo "<script>window.open('login','_self')</script>";
}
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers", array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
    <title><?= $site_name; ?> - Proposals Ordered</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="<?= $site_keywords; ?>">
    <meta name="author" content="<?= $site_author; ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
    <link href="styles/bootstrap.css" rel="stylesheet">
    <link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
    <link href="styles/styles.css" rel="stylesheet">
    <link href="styles/user_nav_styles.css" rel="stylesheet">
    <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
    <link href="styles/owl.carousel.css" rel="stylesheet">
    <link href="styles/owl.theme.default.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <?php if (!empty($site_favicon)) { ?>
        <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
    <?php } ?>

</head>

<body class="is-responsive">
    <?php require_once("includes/user_header.php"); ?>
    <div class="container-fluid padding-alter1">
        <div class="row">
            <div class="col-md-12">

                <div class="col-md-12">

                    <div id="response_return">Checking time...</div> <!-- This will show the result -->
                    <h3>hellooo</h3>
                    <script>
                        // Function to check the time by calling the PHP script
                        function checkTime() {
                            var login_seller_id = <?= $login_seller_id; ?>;
                            $.ajax({
                                url: 'buyer_cancellation_emails', // PHP file that checks the time
                                type: 'GET',
                                data: {
                                    login_seller_id: login_seller_id,
                                }, // Pass the data as an object
                                success: function(response) {
                                    // Update the message div with the result
                                    $('#response_return').html(response);
                                },
                                error: function(xhr, status, error) {
                                    console.error("Error: " + error); // For debugging any AJAX errors
                                }
                            });
                        }
                        // Call the function every 10 seconds to update the time check
                        setInterval(checkTime, 1000); // 10000 ms = 10 seconds

                        // Call the function immediately when the page loads
                        checkTime();
                    </script>
                </div>

            </div>

        </div>
    </div>
    <?php require_once("includes/footer.php"); ?>
</body>

</html>
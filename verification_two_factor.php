<?php
session_start();
require_once("includes/db.php");

// Check if the user is in the process of OTP verification
if (!isset($_SESSION['otp_pending'])) {
    header('Location: login'); // Redirect to login if no OTP session is found
    exit();
}

$seller_user_name = $_SESSION['otp_pending']; // Get the username

// Fetch OTP from the database
$query = $db->select('two_factor_varification', array('seller_user_name' => $seller_user_name));
$row_otp_two_factor = $query->fetch();

$sent_otp = $row_otp_two_factor->verification_code;
$otp_created_at = $row_otp_two_factor->otp_created_at;


if (isset($_POST['submit'])) {
    $input_otp = $_POST['otp'];
    $expiry_time = strtotime($otp_created_at) + 600; // OTP valid for 10 minutes      
    if (time() > $expiry_time) {
        echo "<script>alert('OTP has expired. Please request a new one.');</script>";
    } elseif ($input_otp === $sent_otp) {
        // OTP verified successfully
        $_SESSION['seller_user_name'] = $seller_user_name; // Start full session now
        unset($_SESSION['otp_pending']); // Remove OTP pending session
        header('Location: /'); // Redirect to the dashboard
        exit();
    } else {
        if ($input_otp == $sent_otp) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    text: 'Verification successful!',
                    timer: 2000,
                    didOpen: function(){
                        Swal.showLoading()
                    }
                }).then(function(){
                    window.location.href = '$site_url'; // Redirect to the next page after success
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    text: 'Invalid verification code. Please try again.',
                    timer: 2000,
                    didOpen: function(){
                        Swal.showLoading()
                    }
                });
            </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>
    <title> <?= $site_name; ?> - <?= $lang['top_proposals']['title']; ?> </title>
    <meta name="description" content="">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="<?= $site_author; ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
    <link href="styles/bootstrap.css" rel="stylesheet">
    <link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
    <link href="styles/styles.css" rel="stylesheet">
    <link href="styles/categories_nav_styles.css" rel="stylesheet">
    <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
    <link href="styles/sweat_alert.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container_varification_div {
            width: 100%;
            height: 60vh;
            margin-top: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .verfication_form_div {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .verfication_form_div h2 {
            margin-bottom: 1rem;
            color: grey;
            font-size: 24px;
            font-weight: 500;
        }

        .verfication_form_div label {
            display: block;
            margin-bottom: 2.5rem;
            font-weight: 500;
            color: #333;
        }

        .verfication_form_div input[type="text"] {
            width: calc(100% - 0px);
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 1.3rem;
            font-size: 16px;
        }

        .verfication_form_div button {
            width: 100%;
            padding: 0.8rem;
            border: none;
            border-radius: 5px;
            background-color: dimgrey;
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .verfication_form_div button:hover {
            background-color: #005bb5;
        }
    </style>

    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="js/ie.js"></script>
    <script type="text/javascript" src="js/sweat_alert.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (!empty($site_favicon)) { ?>
        <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
    <?php } ?>


</head>

<body class="bg-white is-responsive">

    <?php require_once("includes/header.php"); ?>

    <div class="container_varification_div">
        <div class="verfication_form_div">
            <h2>Verify Your OTP</h2>            
            <form method="POST" action="">
                <label for="otp">Enter the OTP sent to your email:</label>
                <input type="text" name="otp" id="otp" required>
                <button type="submit" name="submit">Verify</button>
            </form>
        </div>
    </div>

    <?php require_once("includes/footer.php"); ?>


</body>

</html>
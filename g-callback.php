<?php

session_start();
require_once "g-config.php";

if (isset($_SESSION['access_token'])) {  
    $gClient->setAccessToken($_SESSION['access_token']);
   
} else if (isset($_GET['code'])) {
    $token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token;
} else {
    echo "<script> window.open('login','_self'); </script>";
    // exit();
}

$oAuth = new Google_Service_Oauth2($gClient);
$userData = $oAuth->userinfo->get();
$userData = $oAuth->userinfo->get();
$_SESSION['id'] = $userData->getId();
$_SESSION['email'] = $userData->getEmail();
$_SESSION['gender'] = $userData->getGender();
$_SESSION['picture'] = $userData->getPicture();
$_SESSION['familyName'] = $userData->getFamilyName();
$_SESSION['givenName'] = $userData->getGivenName();

header("Location: g-register.php");
exit();

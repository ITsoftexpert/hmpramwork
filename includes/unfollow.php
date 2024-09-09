<?php

session_start();

require_once("db.php");

if(isset($_SESSION['seller_user_name'])){

    $followed_id = $input->post('followed_id');

    $follower_id = $input->post('follower_id');


    $follower_insert = $db->update("follow_following_unfllow",array("status" => 'inactive'),array("follower_id" => $follower_id,
        "followed_id" => $followed_id));


    if($follower_insert)
    {
        echo "success";
    }





}


?>
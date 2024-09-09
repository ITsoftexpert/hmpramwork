<?php

session_start();

require_once("db.php");

if(isset($_SESSION['seller_user_name'])){

    $followed_id = $input->post('followed_id');

    $follower_id = $input->post('follower_id');

   // $delete_plan = $db->delete("membership_table",array('id' => $delete_id));

    /*echo $follower_id;
    die();*/

    $follow_data = $db->select('follow_following_unfllow', array('followed_id'=> $followed_id, 'follower_id'=>$follower_id));
    $follow_tbl_data = $follow_data->fetch();
    //$follow_tbl_data->id;
    if($follow_tbl_data->id){
        $delete_plan = $db->delete("follow_following_unfllow",array('followed_id'=> $followed_id, 'follower_id'=>$follower_id));

    }


    $follower_insert = $db->insert("follow_following_unfllow",array("follower_id" => $follower_id,
        "followed_id" => $followed_id, 'status'=>'active'));


    if($follower_insert)
    {
        //echo "success";
        $date = new DateTime($input->get("date"));
        $date = $date->format("F d, Y");
      /*  $user_data = $db->select('sellers', array('seller_id'=> $followed_id));
        $follower_user_data = $user_data->fetch();
        $user_name = $follower_user_data->seller_name;

        $user_data = $db->select('sellers', array('seller_id'=> $follower_id));
        $login_user_data = $user_data->fetch();
        $login_user_name = $login_user_data->seller_name;
        //echo $user_name." ".$login_user_name;*/

        $insert_notification = $db->insert("notifications",array("receiver_id" => $followed_id, "sender_id"=>$follower_id, "order_id"=>0, "reason"=> "following", "date"=>$date,
            "bell"=>"active", "status"=>"unread","fcm_notification_status"=>NULL));

        if($insert_notification)
        {
            echo "inserted notification";
        }


          //  "followed_id" => $followed_id, 'status'=>'active'));


    }





}


?>
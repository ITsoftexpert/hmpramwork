<?php

@session_start();
require_once("../includes/db.php");


if(isset($_POST['message_group_id']) &&  $_SESSION['seller_user_name'] ){
    $offer_id =   preg_replace('/\D/', '', $_POST['offer_id_reject']);
    $message_group_id =  preg_replace('/\D/', '', $_POST['message_group_id']);
    $db->query('UPDATE messages_offers SET  status = "rejected" where offer_id = "'.$offer_id.'"');
    $msg  = $db->select("inbox_messages",array("message_group_id" => $message_group_id))  or die(mysqli_erro($db));
    $msg_row = $msg->fetch();
    if($msg_row) {
        // update databases
        $reject_reasaon = strip_tags($_POST['reject_reasaon']);

        // updates messages
        $db->query('insert into inbox_messages  set 
                        message_group_id = "'.$msg_row->message_group_id.'", 
                        message_sender = "'.$msg_row->message_sender.'",
                        message_desc = "'.$reject_reasaon.'",
                        dateAgo = now(),
                        message_date = now(),
                        message_offer_id = 0
                        ') or die(mysqli_erro($db));
        echo 'Request rejected';
    } else {
        echo 'Something went wrong';
    }



} else{
    echo '<script>alert("Not a valid request"</script>';
}

?>

<?php

@session_start();

if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login','_self');</script>";
} else {
    if (isset($_GET['delete_report'])) {
        $report_id = $input->get('delete_report');
        $type = $input->get('type');
        $action = isset($_GET['action']) ? $input->get('action') : false;

        // Delete action
        if ($action == false) {
            $delete_report =  $db->delete("reports", array('id' => $report_id));

            if ($delete_report) {

                $insert_log = $db->insert_log($admin_id, "{$type}_report", $report_id, "deleted");


                echo "<script>alert('One {$type} Report Has Been Deleted Successfully.');</script>";

                echo "<script>window.open('index?{$type}_reports','_self');</script>";
            }
        } else {
            $data = ["status" => "action_taken"];
            $update = $db->update("reports", $data, ["id" => $report_id]);
            if ($update) {
                $qReport = $db->select("reports", ['id' => $report_id]);
                $oReport = $qReport->fetch();

                $user = $db->select("sellers", ["seller_id" => $oReport->reporter_id])->fetch();

                $last_update_date = date("F d, Y");

                $insert_notification = $db->insert("notifications", array("receiver_id" => $user->seller_id, "sender_id" => "admin_$admin_id", "order_id" => $report_id, "reason" => "{$type}_report_action_taken", "date" => $last_update_date, "status" => "unread"));
                if ($insert_notification) {
                    /// sendPushMessage Starts
                    $notification_id = $db->lastInsertId();
                    sendPushMessage($notification_id);
                    /// sendPushMessage Ends

                    // if ($notifierPlugin == 1) {
                    // 	$smsText = $lang['notifier_plugin']['proposal_approved'];
                    // 	sendSmsTwilio("", $smsText, $seller_phone);
                    // }
                }
                $insert_log = $db->insert_log($admin_id, "{$type}_report", $report_id, "action_taken");

                echo "<script>alert('One Report has been Updated.');</script>";
                echo "<script>window.open('index?{$type}_reports','_self');</script>";
            }
        }
    }

?>

<?php } ?>
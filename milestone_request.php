<?php
$select_milestone_request = $db->select("request_milestone_create", array("order_id" => $order_id));

if (isset($_POST['milestone_request_submit'])) {
    $request_action = $_POST["request_action"];
    $action_describe = $_POST["action_describe"];

    if ($request_action == "milestone_req_accepted") {
        $action_on_request = $db->update("request_milestone_create", array("rmc_status" => $request_action, "action_describe" => $action_describe), array("order_id" => $order_id, "order_number" => $order_number));
        $insert_cancelled_notification = $db->insert("notifications", array("receiver_id" => $receiver_id, "sender_id" => $login_seller_id, "order_id" => $order_id, "reason" => "milestone_req_accepted", "date" => $n_date, "status" => "unread"));
        $notification_id = $db->lastInsertId();
        sendPushMessage($notification_id);
        /// sendPushMessage Ends
    } else {
        $action_on_request = $db->update("request_milestone_create", array("rmc_status" => $request_action, "action_describe" => $action_describe), array("order_id" => $order_id, "order_number" => $order_number));
        $insert_cancelled_notification = $db->insert("notifications", array("receiver_id" => $receiver_id, "sender_id" => $login_seller_id, "order_id" => $order_id, "reason" => "milestone_req_rejected", "date" => $n_date, "status" => "unread"));
        $notification_id = $db->lastInsertId();
        sendPushMessage($notification_id);
        /// sendPushMessage Ends
    }
?>
    <script>
        document.getElementById("container_miles_request").style.display = "none";
    </script>
<?php
}
?>
<style>
    .radio-container2 {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .radio-container2 label {
        font-size: 16px;
        width: 45%;
        text-align: center;
        /* color: #333; */
        cursor: pointer;
        /* border: 1px solid grey; */
        padding: 0.8rem 3rem;
        border-radius: 5px;
        box-shadow: 0px 0px 5px grey;
    }

    .bg-success-light2 {
        background-color: #00ae00d6;
        color: #fff;
    }

    .bg-danger-light2 {
        background-color: #ff0000e8;
        color: #fff;
    }

    .milestone_request_submit_btn {
        width: 45%;
        padding: 12px;
        background-color: #00cdce;
        border: none;
        border-radius: 4px;
        color: white;
        font-size: 16px;
        cursor: pointer;
        margin: 1rem 0;
        /* transition: background-color 0.3s; */
    }

    .container_miles_request {
        border: 1px solid lightgrey;
        border-radius: 5px;
    }

    .container_miles_request h3 {
        text-align: center;
        margin: 0.5rem 0 2rem;
    }
</style>
<?php while ($fetch_data_miles_req = $select_milestone_request->fetch()) {

    if ($fetch_data_miles_req->rmc_status == "Create Milestone Requested") { ?>
        <div class="container_miles_request" id="container_miles_request">
            <h3><u>Milestone Create Request</u></h3>
            <div class="div_milestone_request col-md-12 row">
                <div class="col-md-6">
                    <p><strong>Request Againt Order: </strong> #<?= $fetch_data_miles_req->order_number; ?></p>
                    <p><strong>Request Milestone Qty: </strong> <?= $fetch_data_miles_req->milestone_quantity; ?></p>
                    <p><strong>Expected Milestone Amount: </strong> <?= $fetch_data_miles_req->amount_expected; ?>$</p>
                    <p><strong>Duration For Milestone: </strong> <?= $fetch_data_miles_req->duration_milestone_expect; ?></p>
                    <p><strong>Description About Request: </strong> <?= $fetch_data_miles_req->decription_miles_expect; ?></p>
                    <p><strong>Request Status: </strong> <?= $fetch_data_miles_req->rmc_status; ?></p>
                </div>
                <div class="col-md-6 border pt-3 mb-4">
                    <form method="post">
                        <div class="radio-container2">
                            <label for="" class="bg-success-light2"><input type="radio" name="request_action" id="" value="milestone_req_accepted" required>&nbsp;&nbsp; Accept</label>
                            <label for="" class="bg-danger-light2"><input type="radio" name="request_action" id="" value="milestone_req_rejected" required> &nbsp;&nbsp;Reject</label>
                        </div>
                        <textarea name="action_describe" id="" class="form-control" rows="5" required></textarea>
                        <button type="submit" class="milestone_request_submit_btn" name="milestone_request_submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>

<?php }
} ?>
<style>
    .delivery_extension_received {
        width: 100%;
    }

    .delivery_extension_received_inner {
        width: 100%;
        display: flex;
    }

    .form_extension_style {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 500px;
        margin: auto;
    }

    .heading_two_style {
        margin-top: 0;
        font-size: 20px;
        color: #333;
    }

    .textarea_input_style {
        width: calc(100% - 20px);
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        resize: vertical;
        width: 100%;
    }

    .radio-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .radio-container label {
        font-size: 16px;
        /* color: #333; */
        cursor: pointer;
        /* border: 1px solid grey; */
        padding: 0.8rem 3rem;
        border-radius: 5px;
        box-shadow: 0px 0px 5px grey;
    }

    input[type="radio"] {
        margin-right: 5px;
    }

    .bg-success-light {
        background-color: green;
        color: #fff;
    }

    .bg-danger-light {
        background-color: #d00101;
        color: #fff;
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #00cdce;
        border: none;
        border-radius: 4px;
        color: white;
        font-size: 16px;
        cursor: pointer;
        /* transition: background-color 0.3s; */
    }

    /* button:hover {
      background-color: #218838;
    } */
</style>

<?php

// Prepare and execute the query to select the latest delivery extension data based on order number and current date
$delivery_extension_query = $db->query("SELECT * FROM delivery_extension WHERE order_number = :order_number AND DATE(order_date_extend) = CURRENT_DATE ORDER BY id DESC LIMIT 1", array("order_number" => $order_number));

if ($delivery_extension_query) {
    // Fetch and display the latest data
    $delivery_extension_data = $delivery_extension_query->fetch(PDO::FETCH_ASSOC);
    if ($delivery_extension_data) {
        $order_number = $delivery_extension_data['order_number'];
        $order_duration_extend = $delivery_extension_data['order_duration_extend'];
        $order_time_extend = $delivery_extension_data['order_time_extend'];
        $order_date_extend = $delivery_extension_data['order_date_extend'];
        $extend_reason = $delivery_extension_data['extend_reason'];
        $extend_delivery_message = $delivery_extension_data['extend_delivery_message'];
?>

        <div class="delivery_extension_received mt-4 mb-4">
            <div class="delivery_extension_received_inner">
                <form method="post" class="form_extension_style">
                    <h2 class="heading_two_style mb-4"><u>Delivery Extend Request From Seller</u></h2>
                    <p class="mb-1"><b>Order Number : </b> <?= $order_number; ?></p>
                    <p class="mb-1"><b>Extend Delivery Duration : </b> <?= $order_duration_extend; ?>days</p>
                    <p class="mb-1"><b>Extend Delivery Date : </b> <?= $order_date_extend; ?></p>
                    <p class="mb-1"><b>Extend Delivery Time: </b> <?= $order_time_extend; ?></p>
                    <p class="mb-1"><b>Extend Reason : </b> <?= $extend_reason; ?></p>
                    <p class="mb-4"><b>Extend Delivery Message : </b> <?= $extend_delivery_message; ?></p>

                    <input type="hidden" name="order_duration_extend" value="<?= $order_duration_extend ?> days" id="">
                    <input type="hidden" name="order_date_extend" value="<?= $order_date_extend ?>" id="">
                    <input type="hidden" name="order_time_extend" value="<?= $order_time_extend ?>" id="">
                    <input type="hidden" name="extend_reason" value="<?= $extend_reason ?>" id="">
                    <input type="hidden" name="extend_delivery_message" value="<?= $extend_delivery_message ?>" id="">

                    <textarea name="message" class="textarea_input_style" id="" rows="4" required></textarea><br>
                    <div class="radio-container">
                        <label class="bg-success-light"><input type="radio" name="extend_result" id="" value="extendTimeAccepted" required> Accept</label>
                        <label class="bg-danger-light"><input type="radio" name="extend_result" id="" value="extendTimeDeclined" required> Decline</label>
                    </div>
                    <button type="submit" name="submit_extend_result">Submit</button>
                </form>
            </div>
        </div>
        <hr>
<?php
    } else {
        echo "No delivery extension data found for order number: " . $order_number . " and current date.";
    }
} else {
    echo "Query failed.";
}


if (isset($_POST['submit_extend_result'])) {
    $message = $input->post('message');
    $extend_result = $input->post('extend_result');
    $order_duration_extend = $input->post('order_duration_extend');
    $order_date_extend = $input->post('order_date_extend');
    $order_time_extend = $input->post('order_time_extend');
    $extend_reason = $input->post('extend_reason');
    $extend_delivery_message = $input->post('extend_delivery_message');

    $last_update_date = date("h:i: M d, Y");
    if ($seller_id == $login_seller_id) {
        $receiver_id = $buyer_id;
    } else {
        $receiver_id = $seller_id;
    }

    // Insert extend result into order_conversations
    $insert_extend_result = $db->insert("order_conversations", array(
        "order_id" => $order_id,
        "sender_id" => $login_seller_id,
        "message" => $message,
        "date" => $last_update_date,
        "reason" => $extend_result,
        "status" => $extend_result
    ));

    if ($insert_extend_result) {
        $insert_result_notification = $db->insert("notifications", array(
            "receiver_id" => $receiver_id,
            "sender_id" => $login_seller_id,
            "order_id" => $order_id,
            "reason" => $extend_result,
            "date" => $n_date,
            "status" => "unread"
        ));

        // Send push notification
        $notification_id = $db->lastInsertId();
        sendPushMessage($notification_id);

        if ($extend_result == "extendTimeAccepted") {
            // Update order with extended details
            $update_order = $db->update("orders", array(
                "order_status" => $extend_result,
                "order_duration_extend" => $order_duration_extend,
                "order_date_extend" => $order_date_extend,
                "order_time_extend" => $order_time_extend,
                "extend_reason" => $extend_reason,
                "extend_delivery_message" => $extend_delivery_message
            ), array("order_id" => $order_id));
        } else {
            // Update order status
            $update_order = $db->update("orders", array(
                "order_status" => $extend_result
            ), array("order_id" => $order_id));
        }

        echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";
    } else {
        echo "Try again!";
    }
}
?>
<div id="position_fixed_div">
    <div class="position_absolute_div">
        <div class="form_milestone_div">
            <div class="milestone_form_display">
                <form method="post">
                    <h4 class="mb-4"><u>Create Milestone</u></h4>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Task Title</label><br>
                            <input class="col-md-12 p-3" type="text" name="task_title" id="" required><br>
                        </div>
                        <div class="col-md-12">
                            <label for="">Task Description</label><br>
                            <input class="col-md-12 p-3" type="text" name="task_description" id="" required><br>
                        </div>
                        <div class="col-md-6 mt-4">
                            <label for="">Task Amount ( In $ )</label> <br>
                            <input class="col-md-12 p-3" type="number" name="task_amount" id="" required><br>
                        </div>
                        <div class="col-md-6 mt-4">
                            <label for="">Delivery Date</label><br>
                            <input class="col-md-12 p-3" type="datetime-local" name="delivery_time" id="" required><br>
                        </div>
                        <input type="hidden" name="order_id" id="" value="<?= $order_id; ?>" required>
                        <input type="hidden" name="order_number" id="" value="<?= $order_number; ?>" required>
                    </div>
                    <div class="w-100 d-flex pt-5 pb-3 mt-2">
                        <button type="submit" class="m-auto submit_milestone_btn_style2" name="submit_milestone">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php

// insert milestone
if (isset($_POST["submit_milestone"])) {
    $task_amount = $_POST['task_amount'];
    $delivery_time = $_POST['delivery_time'];
    $task_title = $_POST['task_title'];
    $task_description = $_POST['task_description'];
    $order_number = $_POST['order_number'];
    $order_id = $_POST['order_id'];

    $slectordernum = $db->select("orders", array("order_id" => $order_id))->fetch();
    $milestone_id = $slectordernum->milestone_id;

    $selectExistMiles = $db->select("milestone", array("milestone_id" => $milestone_id))->fetch();
    $request_id = $selectExistMiles->request_id;
    $sender_id = $selectExistMiles->sender_id;
    $login_seller_id = $selectExistMiles->seller_id;
    $proposal_id = $selectExistMiles->proposal_id;
    $offer_id = $selectExistMiles->offer_id;

    $insert_milestone_data = $db->insert(
        "milestone",
        array(
            "task_amount" => $task_amount,
            "delivery_time" => $delivery_time,
            "task_description" => $task_description,
            "request_id" => $request_id,
            "sender_id" => $sender_id,
            "seller_id" => $login_seller_id,
            "proposal_id" => $proposal_id,
            "offer_id" => $offer_id
        )
    );

    if ($insert_milestone_data) {
        echo "Milestone Created Successfully <a href='$site_url/requests/manage_requests'>(View & Start)</a>";
    } else {
        echo "Milestone Not Created! Try Again";
    }
} ?>
<script>
    document.getElementById('selectnextstep').addEventListener('change', function() {
        var selectedValue = this.value;

        // Hide both sections initially
        document.getElementById('forreview').style.display = 'none';
        document.getElementById('forcontinue').style.display = 'none';

        // Show the appropriate section based on the selection
        if (selectedValue === 'completed') {
            document.getElementById('forreview').style.display = 'block';
        } else if (selectedValue === 'continue') {
            document.getElementById('forcontinue').style.display = 'block';
        }
    });
</script>
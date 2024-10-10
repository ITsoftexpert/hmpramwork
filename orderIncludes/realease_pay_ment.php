<style>
    .payment_release_fdiv {
        position: fixed;
        width: 100%;
        height: 100%;
        display: flex;
        z-index: 10;
        background-color: #ddddddb3;
        box-shadow: 0px 0px 50px 10px lightgray;
    }

    .payment_release_fdivin {
        position: absolute;
        background-color: white;
        border-radius: 10px;
        width: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        box-shadow: 0px 0px 10px grey;
        padding: 1rem;
    }

    .close_popup_styles {
        padding: 1px 5px 0px;
        border-radius: 3px;
        float: inline-end;
        background-color: black;
        color: white;
    }
</style>
<div class="payment_release_fdiv" id="payment_release_fdiv">
    <div class="payment_release_fdivin">
        <?php
        $select_miles_fpay = $db->select("milestone", array("order_number" => $order_number, "milestone_id" => $milestone_id))->fetch();

        ?>
        <h3 class="mb-4">Milestone Payment Release <span class="close_popup_styles" onclick="closePopupPaymentForm()"> X </span></h3>
        <p><strong>Milestone Title: </strong> <?= $select_miles_fpay->task_title; ?></p>
        <p><strong>Milestone Amount: </strong> <?= $select_miles_fpay->task_amount; ?>$</p>
        <form method="post">
            <button name="release_payment" type="submit" class="btn btn-success">
                Payment Release
            </button>
        </form>


        <?php


        if (isset($_POST['release_payment'])) {

            $data = [];
            $data['template'] = "buyer_released_payment";
            $data['to'] = "kumshubham25@gmail.com";
            $data['subject'] = "$site_name - Buyer released payment";
            $data['user_name'] = $seller_user_name;
            $data['task_title'] = $select_miles_fpay->task_title;
            $data['task_amount'] = $select_miles_fpay->task_amount;
            $data['order_number'] = $order_number;
            $data['order_link'] = "$site_url/order_details?order_id=$order_id";
            send_mail($data);
            require_once("orderIncludes/orderContinue.php");
        }
        ?>

    </div>
</div>
<script>
    function closePopupPaymentForm() {
        document.getElementById("payment_release_fdiv").style.display = "none";
    }
</script>
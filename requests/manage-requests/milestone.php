<div class="table-responsive box-table box-shadow-req-act">
    <table class="table table-bordered" id="requestModification">
        <thead>
            <tr>
                <th class="font-size-3">Title</th>
                <th class="font-size-3">Amount</th>
                <th class="font-size-3">Delivery Date</th>
                <th class="font-size-3">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr class="table-info">
                <td colspan="3">
                    data fetching...
                </td>
            </tr> 
        </tbody>
    </table>
    <nav id="pagination-request-modification" aria-label="modification request navigation"></nav>
</div>





<div class="col-md-12 pt-3">
    <div class="milestone-container">

        <?php
        $display_milestone = $db->select("milestone", array("seller_id" => $login_seller_id));

        if ($display_milestone->rowCount() > 0) {
            while ($fetch_milestone = $display_milestone->fetch()) {
                $task_amount = $fetch_milestone->task_amount;
                $delivery_date = $fetch_milestone->delivery_date;
                $task_description = $fetch_milestone->task_description;
                $request_id = $fetch_milestone->request_id;
                $sender_id = $fetch_milestone->sender_id;
                $proposal_id = $fetch_milestone->proposal_id;
                $offer_id = $fetch_milestone->offer_id;
        ?>
                <div class="milestone" style="border: 1px solid #ccc; padding: 15px; margin-bottom: 15px;">
                    <h4>Task :- <?= $task_description; ?></h4>
                    <p><strong>Amount :- </strong> $<?= $task_amount; ?></p>
                    <p><strong>Delivery Date :- </strong> <?= $delivery_date; ?></p>
                    <button class="pay_now_milestone_btn">Pay Now</button>
                    <button class="pay_now_milestone_btn"></button>
                    <button class="pay_now_milestone_btn">Pay Now</button>
                </div>
        <?php
            }
        } else {
            echo "<p>No milestones found.</p>";
        }
        ?>
    </div>

</div>
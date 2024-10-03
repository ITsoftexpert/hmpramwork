<style>
    .th_head_styling th {
        border: 1px solid lightgrey;
        padding: 1rem;
        text-align: center;
    }

    .td_body_styling td {
        border: 1px solid lightgray;
        padding: 13px;
        text-align: center;
    }
</style>
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="text-center" id="">Order's Milestone Details</h3>
                            </div>
                        </div>

                        <table class="col-md-12">
                            <thead>
                                <tr class="th_head_styling">
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Delivery Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $get_current_order_milestones = $db->select("milestone", array("order_id" => $order_id, "milestone_id" => $milestone_id));
                                while ($get_current_order_milestone = $get_current_order_milestones->fetch()) {
                                ?>
                                    <tr class="td_body_styling">
                                        <td><?= $get_current_order_milestone->task_title; ?></td>
                                        <td><?= $get_current_order_milestone->task_description; ?></td>
                                        <td><?= $get_current_order_milestone->task_amount; ?></td>
                                        <td><?= $get_current_order_milestone->delivery_time; ?></td>
                                        <td><?= $get_current_order_milestone->milestone_status; ?></td>
                                        <td>
                                            <select name="" id="orderOptions" onchange="handleSelect(this);">
                                                <option value="" selected>Select</option>
                                                <option value="<?= $site_url ?>/customer_support?enquiry_id=1&order_number=<?= $get_current_order_milestone->order_number ?>">Dispute</option>
                                            </select>
                                        </td>
                                    </tr>

                                    <script>
                                        function handleSelect(selectElement) {
                                            var value = selectElement.value;
                                            if (value) {
                                                window.location.href = value;
                                            }
                                        }
                                    </script>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
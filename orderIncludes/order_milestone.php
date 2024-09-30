<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="text-center" id="">Order's Milestone Details</h3>
                            </div>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Delivery Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $get_current_order_milestones = $db->select("milestone", array("order_id" => $order_id, "milestone_id" => $milestone_id));
                                while ($get_current_order_milestone = $get_current_order_milestones->fetch()) {
                                ?>
                                    <tr>
                                        <td><?= $get_current_order_milestone->task_title; ?></td>
                                        <td><?= $get_current_order_milestone->task_description; ?></td>
                                        <td><?= $get_current_order_milestone->task_amount; ?></td>
                                        <td><?= $get_current_order_milestone->delivery_time; ?></td>
                                        <td><?= $get_current_order_milestone->milestone_status; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
                                if (strpos($order_number, '-') !== false) {
                                    // '-' ke pehle ka portion le lo
                                    $display_order_number = explode("-", $order_number)[0];
                                } else {
                                    // Agar '-' nahi mila to original order_number use karo
                                    $display_order_number = $order_number;
                                }

                                // Direct query with proper string concatenation and LIKE condition
                                $get_current_order_milestones = $db->query("SELECT * FROM milestone WHERE order_number LIKE '" . $display_order_number . "-%'");

                                // Check if query executed successfully
                                if ($get_current_order_milestones) {
                                    while ($get_current_order_milestone = $get_current_order_milestones->fetch()) {
                                ?>
                                        <tr class="td_body_styling">
                                            <td><?= $get_current_order_milestone->task_title; ?></td>
                                            <td><?= $get_current_order_milestone->task_description; ?></td>
                                            <td><?= $get_current_order_milestone->task_amount; ?></td>
                                            <td><?= $get_current_order_milestone->delivery_time; ?></td>
                                            <td><?= $get_current_order_milestone->milestone_status; ?></td>
                                            <td>
                                                <?php if ($get_current_order_milestone->milestone_status == "completed") { ?>
                                                    <select name="" id="orderOptions" onchange="handleSelect(this);">
                                                        <option value="" selected>Select</option>
                                                        <option value="<?= $site_url ?>/customer_support?enquiry_id=1&order_number=<?= $get_current_order_milestone->order_number ?>">Dispute</option>
                                                    </select>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "Error: Could not execute query.";
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
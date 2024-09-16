<div class="table-responsive box-table box-shadow-req-act p-2">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th class="font-size-3">Title</th>
						<th class="font-size-3">Description</th>
						<th class="font-size-3">Amount</th>
						<th class="font-size-3">Delivery Date & Time</th>
						<th class="font-size-3">Order Action</th>
						<th class="font-size-3">More Actions</th>
					</tr>
				</thead>
				<tbody>
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
							$task_title = $fetch_milestone->task_title;
							$milestone_id = $fetch_milestone->milestone_id;
					?> <tr class="">
								<td><?= $task_title; ?> </td>
								<td><?= $task_description; ?> </td>
								<td>$<?= $task_amount; ?> </td>
								<td><?= $delivery_date; ?> </td>
								<td>
									<button id="order-now-<?= $milestone_id; ?>">Order Now</button>
								</td>
								<td>
									<div class="dropdown">
										<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"></button>
										<div class="dropdown-menu">
											<p class="mb-2" onclick="displayMileStoneForm()">Create Milestone</p>
											<p class="mb-2"><a href="">Close Project</a></p>
											<p class="mb-2"><a href="">Dispute</a></p>
											<p class="mb-2"><a href="">Payment Release</a></p>
										</div>
									</div>
								</td>
							</tr>
							<script>
								$("#order-now-<?= $milestone_id; ?>").click(function() {
									request_id = "<?= $request_id; ?>";
									milestone_id = "<?= $milestone_id; ?>";
									$.ajax({

                                      
										method: "POST",
										url: "milestone_submit_order",
										data: {
											request_id: request_id,
											milestone_id: milestone_id
										}
									}).done(function(data) {
										$("#append-modal").html(data);
									});
								});
							</script>
						<?php

						}
					} else {
						?>
						<tr class="table-danger">
							<td colspan="6">
								No milestones found
							</td>
						</tr>
					<?php }
					?>
				</tbody>
			</table>
		</div>


<div id="position_fixed_div">
    <div class="position_absolute_div">
        <div class="form_milestone_div">
            <div class="milestone_form_display">
                <form method="post">
                    <h4 class="mb-4"><u>Create Milestone</u>  <span class="span_close_btn_sttyle" onclick="displayNoneMileStoneForm()"> X </span></h4>
                    <div class="row">
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
                            <input class="col-md-12 p-3" type="datetime-local" name="delivery_date" id="" required><br>
                        </div>
                    </div>
                    <div class="w-100 d-flex pt-5 pb-3 mt-2">
                        <button type="submit" class="m-auto submit_milestone_btn_style2" name="submit_milestone">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div id="append-modal"></div>


<?php
// insert milestone
if (isset($_POST["submit_milestone"])) {
    $task_amount = $_POST['task_amount'];
    $delivery_date = $_POST['delivery_date'];
    $task_description = $_POST['task_description'];
    // $request_id = $request_id;
    // $sender_id = $sender_id;
    // $proposal_id = $proposal_id;
    // $offer_id = $offer_id;		
    $insert_milestone_data = $db->insert(
        "milestone",
        array(
            "task_amount" => $task_amount,
            "delivery_date" => $delivery_date,
            "task_description" => $task_description,
            "request_id" => $request_id,
            "sender_id" => $sender_id,
            "seller_id" => $login_seller_id,
            "proposal_id" => $proposal_id,
            "offer_id" => $offer_id,
        )
    );

    if ($insert_milestone_data) {
        echo "Milestone Created Successfully";
    } else {
        echo "Milestone Not Created! Try Again";
    }
}

?>

<script>
    function displayMileStoneForm(){
        var position_fixed_div = document.getElementById("position_fixed_div");
        position_fixed_div.style.display = "block";
    }
    function displayNoneMileStoneForm(){
        var position_fixed_div = document.getElementById("position_fixed_div");
        position_fixed_div.style.display = "none";
    }
</script>
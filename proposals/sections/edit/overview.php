<?php
$get_delivery_time =  $db->select("delivery_times", array('delivery_id' => $d_delivery_id));
$row_delivery_time = $get_delivery_time->fetch();
@$delivery_proposal_title = $row_delivery_time->delivery_proposal_title;

$get_meta = $db->select("cats_meta", array("cat_id" => $d_proposal_cat_id, "language_id" => $siteLanguage));
$row_meta = $get_meta->fetch();
$cat_title = $row_meta->cat_title;

$get_meta = $db->select("child_cats_meta", array("child_id" => $d_proposal_child_id, "language_id" => $siteLanguage));
$row_meta = $get_meta->fetch();
$child_title = $row_meta->child_title;


?>
<script>
	$(function() {
		<?php if ($check and (!isset($_GET['video']) and !isset($_GET['instant_delivery']) and !isset($_GET['publish']))) { ?>
			$('#direct_order').popover('show')
		<?php } ?>
		$("#direct_order").on('change', function() {
			if ($("#direct_order").is(":checked"))
				$('#direct_order').popover('show')
			else
				$('#direct_order').popover('hide')
		})
	});
</script>
<form action="#" method="post" class="proposal-form">
	<!--- form Starts -->
	<div class="row">
		<div class="col-xs-12">
			<div class="float-right switch-box">
				<span class="text"><?= $lang['edit_proposal']['direct_order']['enable']; ?></span>
				<!-- <label class="switch">
					<input type="checkbox" name="direct_order" id="direct_order" value="2" <?php echo $check; ?> />
					<span class="slider  direct_order"></span>
				</label> -->
				<label class="switch" id="switchOrder">
					<input type="checkbox" name="direct_order" id="direct_order" value="2" <?php echo $check; ?> data-toggle="popover" data-placement="right" data-trigger="manual" data-offset="0 72px" title="Information" data-content="When its off, Buyer can't buy this service directly." />
					<span class="slider instant-slider direct_order"></span>
				</label>
			</div>
		</div>
	</div>
	<div class="form-group row">
		<!--- form-group row Starts --->
		<div class="col-md-3"><?= $lang['label']['proposal_title']; ?></div>
		<div class="col-md-9">
			<textarea name="proposal_title" id="proposal_title" rows="2" placeholder="I Will" required="" class="form-control" minlength="50" maxlength="200"><?= $d_proposal_title; ?></textarea>
			<small class="form-text text-danger"><?= ucfirst($form_errors['proposal_title'] ?? ""); ?></small>
			<span class="text-dark d-block">min: 30  max: 100 characters <span class="pull-right"><i class="text-danger" id="typed-characters"><?= strlen($d_proposal_title) ?></i> characters</span></span>
		</div>
	</div>
	<!--- form-group row Ends --->

	<div class="form-group row">
		<!--- form-group row Starts --->
		<div class="col-md-3"> <?= $lang['label']['category']; ?> </div>
		<div class="col-md-9">
			<select name="proposal_cat_id" id="category" class="form-control mb-3" required>
				<option value="<?= $d_proposal_cat_id; ?>" selected> <?= $cat_title; ?> </option>
				<?php
				$get_cats = $db->query("select * from categories where not cat_id='$d_proposal_cat_id'");
				while ($row_cats = $get_cats->fetch()) {
					$cat_id = $row_cats->cat_id;
					$get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
					$row_meta = $get_meta->fetch();
					$cat_title = $row_meta->cat_title;
				?>
					<option value="<?= $cat_id; ?>"> <?= $cat_title; ?> </option>
				<?php } ?>
			</select>
			<small class="form-text text-danger"><?= ucfirst($form_errors['proposal_cat_id'] ?? ""); ?></small>
			<select name="proposal_child_id" id="sub-category" class="form-control" required>
				<option value="<?= $d_proposal_child_id; ?>" selected> <?= $child_title; ?> </option>
				<?php
				$get_c_cats = $db->query("select * from categories_children where child_parent_id='$d_proposal_cat_id' and not child_id='$d_proposal_child_id'");
				while ($row_c_cats = $get_c_cats->fetch()) {
					$child_id = $row_c_cats->child_id;
					$get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
					$row_meta = $get_meta->fetch();
					$child_title = $row_meta->child_title;
					echo "<option value='$child_id'> $child_title </option>";
				}
				?> 
			</select>

			<select name="proposal_attr_id" id="sub-sub-category" class="form-control" required>
				<option value="<?= $d_proposal_child_id; ?>" selected> <?= $child_title; ?> </option>
				<?php
				$get_c_cats = $db->query("select * from categories_children where child_parent_id='$d_proposal_cat_id' and not child_id='$d_proposal_child_id'");
				while ($row_c_cats = $get_c_cats->fetch()) {
					$child_id = $row_c_cats->child_id;
					$get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
					$row_meta = $get_meta->fetch();
					$child_title = $row_meta->child_title;
					echo "<option value='$child_id'> $child_title </option>";
				}
				?>
			</select>





		</div>
	</div>
	<!--- form-group row Ends --->

	<div class="form-group row d-none">
		<!--- form-group row Starts --->
		<div class="col-md-3"><?= $lang['label']['delivery_time']; ?></div>
		<div class="col-md-9">
			<select name="delivery_id" class="form-control" required="">
				<option value="<?= $d_delivery_id; ?>"> <?= $delivery_proposal_title; ?> </option>
				<?php
				$get_delivery_times = $db->query("select * from delivery_times where not delivery_id='$d_delivery_id'");
				while ($row_delivery_times = $get_delivery_times->fetch()) {
					$delivery_id = $row_delivery_times->delivery_id;
					$delivery_proposal_title = $row_delivery_times->delivery_proposal_title;
					echo "<option value='$delivery_id'>$delivery_proposal_title</option>";
				}
				?>
			</select>
		</div>
		<small class="form-text text-danger"><?= ucfirst($form_errors['delivery_id'] ?? ""); ?></small>
	</div>
	<!--- form-group row Ends --->

	<div class="form-group row d-none">
		<!--- form-group row Starts --->
		<!-- <div class="col-md-3"><?= $lang['label']['proposal_revisions']; ?></div>
	  <div class="col-md-9">
	    <select name="proposal_revisions" class="form-control" required="">
	    <?php
		foreach ($revisions as $key => $rev) {
			echo "<option value='$key' " . ($key == $d_proposal_revisions ? "selected" : "") . ">$rev</option>";
		}
		?>
	    </select>
	    <small class="muted">Set this to 0 if this proposal is for instant delivery.</small>
	  </div>
	  <small class="form-text text-danger"><?= ucfirst($form_errors['proposal_revisions'] ?? ""); ?></small> -->
	</div>
	<!--- form-group row Ends --->

	<?php if ($enable_referrals == "yes") { ?>
		<div class="form-group row">
			<!--- form-group row Starts --->
			<label class="col-md-3 control-label"> <?= $lang['label']['enable_referrals']; ?> </label>
			<div class="col-md-9">
				<select name="proposal_enable_referrals" class="proposal_enable_referrals form-control" required="">
					<?php if ($d_proposal_enable_referrals == "yes") { ?>
						<option value="yes"> Yes </option>
						<option value="no"> No </option>
					<?php } elseif ($d_proposal_enable_referrals == "no") { ?>
						<option value="no"> No </option>
						<option value="yes"> Yes </option>
					<?php } ?>
				</select>
				<small>If enabled, other users can promote your proposal by sharing it on different platforms.</small>
			</div>
		</div>
		<!--- form-group row Ends --->

		<div class="form-group row proposal_referral_money">
			<!--- form-group row Starts --->
			<label class="col-md-3 control-label"> <?= $lang['label']['promotion_commission']; ?> </label>
			<div class="col-md-9">
				<input type="number" name="proposal_referral_money" class="form-control" min="1" max="100" value="<?= $d_proposal_referral_money; ?>">
				<small>Figure should be in percentage. E.g 20 is the same as 20% from the sale of this proposal.</small>
				<br>
				<small> When another user promotes your proposal, how much would you like that user to get from the sale? (in dollars) </small>
			</div>
		</div>
		<!--- form-group row Ends --->
	<?php } ?>

	<div class="form-group row">
		<!--- form-group row Starts --->
		<div class="col-md-3"><?= $lang['label']['tags']; ?></div>
		<div class="col-md-9">
			<input type="text" name="proposal_tags" class="form-control" data-role="tagsinput" value="<?= $d_proposal_tags; ?>">
			<small>Press enter to add your own tags after adding word.</small>
			<br /><small>Enter at leaset 2 characters to get suggestions.</small>
		</div>
		<small class="form-text text-danger"><?= ucfirst($form_errors['proposal_tags'] ?? ""); ?></small>
	</div>
	<!--- form-group row Ends --->

	<div class="form-group mb-0">
		<!--- form-group Starts --->
		<a href="view_proposals" class="float-left btn btn-secondary"><?= $lang['button']['cancel']; ?></a>
		<input class="btn btn-success float-right" type="submit" value="<?= $lang['button']['save_continue']; ?>">
	</div>
	<div class="clearfix"></div>
	<?php if ($d_proposal_status == 'active') { ?>
		<div class="form-group mb-0 float-right">
			<small class="text-muted">Your proposal is "Active", if you edits the form it will go to reviews.</small>
		</div>
	<?php } ?>
	<!--- form-group Starts --->

</form>
<!--- form Ends -->
<script>
	const textAreaElement = document.querySelector("#proposal_title");
	const typedCharactersElement = document.querySelector("#typed-characters");
	const maximumCharacters = 100;

	textAreaElement.addEventListener("input", (event) => {
		const typedCharacters = textAreaElement.value.length;
		if (typedCharacters > maximumCharacters) {
			return false;
		}
		typedCharactersElement.textContent = typedCharacters;
	});
</script>
<?php
$count_all_proposals = $db->query("select * from proposals where proposal_status not in ('modification','draft','deleted')")->rowCount();

$count_active_proposals = $db->count("proposals", array("proposal_status" => "active"));
$count_featured_proposals = $db->count("proposals", array("proposal_status" => "active", "proposal_featured" => "yes"));
$count_pending_proposals = $db->count("proposals", array("proposal_status" => "pending"));
$count_pause_proposals = $db->query("select * from proposals where proposal_status='pause' or proposal_status='admin_pause'")->rowCount();
$count_delete_proposals = $db->count("proposals", array("proposal_status" => "deleted"));
$count_trash_proposals = $db->count("proposals", array("proposal_status" => "trash"));
?>
<form class="form-inline pb-2" method="get" action="filter_proposals.php">

    <div class="form-group">
        <!--- form-group Starts --->

        <label> Category : </label>

        <select name="cat_id" required class="form-control mb-2 ml-1 mr-sm-2 mb-sm-0">

            <option value=""> Select A Category </option>

            <?php

            $get_categories = $db->select("categories");
            while ($row_categories = $get_categories->fetch()) {
                $cat_id = $row_categories->cat_id;

                $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $adminLanguage));
                $cat_title = $get_meta->fetch()->cat_title;

                echo "<option value='$cat_id'>$cat_title</option>";
            }

            ?>

        </select> 

    </div>
    <!--- form-group Ends --->


    <div class="form-group">

        <label> Delivery Time: </label>

        <select name="delivery_id" class="form-control mb-2 ml-1 mr-sm-2 mb-sm-0">

            <option value=""> Select A Delivery Time </option>

            <?php

            $get_delivery_times = $db->select("delivery_times");
            while ($row_delivery_times = $get_delivery_times->fetch()) {

                $delivery_id = $row_delivery_times->delivery_id;
                $delivery_title = $row_delivery_times->delivery_title;

                echo "<option value='$delivery_id'>$delivery_title</option>";
            }

            ?>

        </select>

    </div>


    <div class="form-group">

        <label> Seller Level: </label>

        <select name="level_id" class="form-control mb-2 ml-1 mr-sm-2 mb-sm-0">

            <option value=""> Select A Seller Level </option>

            <?php

            $get_seller_levels = $db->select("seller_levels");

            while ($row_seller_levels = $get_seller_levels->fetch()) {

                $level_id = $row_seller_levels->level_id;
                $level_title = $db->select("seller_levels_meta", array("level_id" => $level_id, "language_id" => $adminLanguage))->fetch()->title;

                echo "<option value='$level_id'>$level_title</option>";
            }

            ?>

        </select>

    </div>

    <button type="submit" class="btn btn-success"> Filter</button>

</form>
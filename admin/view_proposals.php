<?php

@session_start();

if (!isset($_SESSION['admin_email'])) {

    echo "<script>window.open('login','_self');</script>";
} else {
?>

    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1><i class="menu-icon fa fa-table"></i> Proposals</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li class="active">Proposals</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        <div class="row ">
            <!--- 1 row Starts --->

            <div class="col-lg-12">
                <!--- col-lg-12 Starts --->

                <div class="p-3 mb-3  ">
                    <!--- p-3 mb-3 filter-form Starts --->

                    <h2 class="pb-4">Filter Proposals/Services</h2>

                    <?php include("includes/proposal_filter.php") ?>

                </div>
                <!--- p-3 mb-3 filter-form Ends --->

            </div>
            <!--- col-lg-12 Ends --->

        </div>
        <!--- 1 row Ends --->


        <div class="row mt-3">
            <!--- 2 row mt-3 Starts --->

            <div class="col-lg-12">
                <!--- col-lg-12 Starts --->

                <div class="card">
                    <!--- card Starts --->

                    <div class="card-header">
                        <!--- card-header Starts --->

                        <h4 class="h4">Proposals</h4>

                    </div>
                    <!--- card-header Ends --->

                    <div class="card-body">
                        <!--- card-body Starts --->
                        <?php include("includes/proposal_nav.php") ?>

                        <div class="table-responsive mt-4">
                            <!--- table-responsive mt-4 Starts --->

                            <table class="table  table-bordered table-striped">
                                <!--- table table-hover table-bordered Starts --->

                                <thead>
                                    <!--- thead Starts --->

                                    <tr>

                                        <th>Proposal's Title</th>

                                        <th>Proposal's Display Image</th>

                                        <th>Proposal's Price</th>

                                        <th>Proposal's Category</th>

                                        <th>Proposal's Order Queue</th>

                                        <th>Proposal's Status</th>

                                        <th>Proposal's Action Options</th>

                                    </tr>

                                </thead>
                                <!--- thead Ends --->

                                <tbody>
                                    <!--- tbody Starts --->

                                    <?php

                                    $per_page = 10;

                                    if ($_GET['view_proposals']) {

                                        $page = $_GET['view_proposals'];

                                        if ($page == 0) {
                                            $page = 1;
                                        }
                                    } else {

                                        $page = 1;
                                    }

                                    /// Page will start from 0 and multiply by per page

                                    $start_from = ($page - 1) * $per_page;

                                    $get_proposals = $db->query("select * from proposals where proposal_status not in ('modification','draft','deleted') order by 1 DESC LIMIT :limit OFFSET :offset", "", array("limit" => $per_page, "offset" => $start_from));

                                    while ($row_proposals = $get_proposals->fetch()) {

                                        $proposal_id = $row_proposals->proposal_id;
                                        $proposal_title = $row_proposals->proposal_title;
                                        $proposal_url = $row_proposals->proposal_url;
                                        $proposal_price = $row_proposals->proposal_price;
                                        $proposal_img1 = getImageUrl2("proposals", "proposal_img1", $row_proposals->proposal_img1);
                                        $proposal_cat_id = $row_proposals->proposal_cat_id;
                                        $proposal = $row_proposals->proposal_cat_id;
                                        $proposal_seller_id = $row_proposals->proposal_seller_id;
                                        $proposal_status = $row_proposals->proposal_status;
                                        $proposal_seller_id = $row_proposals->proposal_seller_id;
                                        $proposal_featured = $row_proposals->proposal_featured;
                                        $proposal_toprated = $row_proposals->proposal_toprated;

                                        if ($proposal_price == 0) {

                                            $proposal_price = "";

                                            $get_p = $db->select("proposal_packages", array("proposal_id" => $proposal_id));

                                            while ($row = $get_p->fetch()) {

                                                $proposal_price .= " | $s_currency" . $row->price;
                                            }
                                        } else {

                                            $proposal_price = "$s_currency" . $proposal_price;
                                        }


                                        $select_seller = $db->select("sellers", array("seller_id" => $proposal_seller_id));

                                        $seller_user_name = $select_seller->fetch()->seller_user_name;


                                        $select_orders = $db->query("select * from orders where proposal_id='$proposal_id' AND NOT order_status='complete' AND proposal_id='$proposal_id' AND NOT order_status='cancelled'");

                                        $proposal_order_queue = $select_orders->rowCount();


                                        $get_meta = $db->select("cats_meta", array("cat_id" => $proposal_cat_id, "language_id" => $adminLanguage));

                                        $cat_title = $get_meta->fetch()->cat_title;

                                    ?>

                                        <tr>

                                            <td><?= $proposal_title; ?></td>

                                            <td>

                                                <img src="<?= $proposal_img1; ?>" width="70" height="60">

                                            </td>

                                            <td><?= $proposal_price; ?></td>

                                            <td><?= $cat_title; ?></td>

                                            <td><?= $proposal_order_queue; ?></td>

                                            <td><?= ucfirst($proposal_status); ?></td>
                                            <?php if ($proposal_status == "active") { ?>

                                                <td>

                                                    <a title="View Proposal" href="../proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" target="_blank">

                                                        <i class="fa fa-eye"></i>

                                                    </a>

                                                    <?php if ($proposal_featured == "yes") { ?>

                                                        <a class="text-success" title="Remove Proposal From Featured Listing." href="index?remove_feature_proposal=<?= $proposal_id; ?>&page=<?= $page; ?>" />

                                                        <i class="fa fa-star-half-o"></i>

                                                        </a>

                                                    <?php } else { ?>

                                                        <a href="index?feature_proposal=<?= $proposal_id; ?>&page=<?= $page; ?>" title="Make Your Proposal Featured">
                                                            <i class="fa fa-star"></i>
                                                        </a>

                                                    <?php } ?>

                                                    <?php if ($proposal_toprated == 0) { ?>
                                                        <a href="index?toprated_proposal=<?= $proposal_id; ?>&page=<?= $page; ?>" title="Make Your Proposal Top Rated">
                                                            <i class="fa fa-heart" aria-hidden="true"></i>
                                                        </a>
                                                    <?php } else { ?>
                                                        <a class="text-danger" href="index?removetoprated_proposal=<?= $proposal_id; ?>&page=<?= $page; ?>" title="Remove Proposal From Top Rated Listing.">
                                                            <i class="fa fa-heartbeat" aria-hidden="true"></i>
                                                        </a>
                                                    <?php } ?>

                                                    <a title="Pause/Deactivate Proposal" href="index?pause_proposal=<?= $proposal_id; ?>&page=<?= $page; ?>">

                                                        <i class="fa fa-pause-circle"></i>

                                                    </a>


                                                    <a title="Delete Proposal" href="index?move_to_trash=<?= $proposal_id; ?>&page=<?= $page; ?>">

                                                        <i class="fa fa-trash"></i>

                                                    </a>


                                                </td>

                                            <?php } elseif ($proposal_status == "pause" or $proposal_status == "admin_pause") { ?>

                                                <td>

                                                    <a title="View Proposal" href="../proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" target="_blank">

                                                        <i class="fa fa-eye"></i> Preview

                                                    </a>

                                                    <br>

                                                    <a href="index?unpause_proposal=<?= $proposal_id; ?>&page=<?= $page; ?>">

                                                        <i class="fa fa-refresh"></i> Unpause

                                                    </a>

                                                    <br>

                                                    <a href="index?move_to_trash=<?= $proposal_id; ?>&page=<?= $page; ?>">

                                                        <i class="fa fa-trash"></i> Trash Proposal

                                                    </a>

                                                </td>

                                            <?php } elseif ($proposal_status == "pending") { ?>

                                                <td>

                                                    <a title="View Proposal" href="../proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" target="_blank">

                                                        <i class="fa fa-eye"></i> Preview

                                                    </a>

                                                    <br />

                                                    <a href="index?submit_modification=<?= $proposal_id; ?>&page=<?= $page; ?>">

                                                        <i class="fa fa-edit"></i> Submit For Modification

                                                    </a>

                                                    <br>

                                                    <a href="index?approve_proposal=<?= $proposal_id; ?>&page=<?= $page; ?>">

                                                        <i class="fa fa-check-square-o"></i> Approve

                                                    </a>

                                                    <br />

                                                    <a title="Decline this Proposal" href="index?decline_proposal=<?= $proposal_id; ?>&page=<?= $page; ?>">

                                                        <i class="fa fa-ban"></i> Decline

                                                    </a>

                                                </td>

                                            <?php } elseif ($proposal_status == "declined") { ?>

                                                <td>

                                                    <a href="index?approve_proposal=<?= $proposal_id; ?>&page=<?= $page; ?>">

                                                        <i class="fa fa-check-square-o"></i> Approve

                                                    </a>

                                                    <br />

                                                    <a href="index?submit_modification=<?= $proposal_id; ?>&page=<?= $page; ?>">

                                                        <i class="fa fa-edit"></i> Submit For Modification

                                                    </a>

                                                    <br>

                                                    <a title="View Proposal" href="../proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" target="_blank">

                                                        <i class="fa fa-eye"></i> Preview

                                                    </a>

                                                    <br />

                                                    <a href="index?delete_proposal=<?= $proposal_id; ?>&page=<?= $page; ?>">

                                                        <i class="fa fa-trash"></i> Delete Permanently

                                                    </a>

                                                </td>

                                            <?php } elseif ($proposal_status == "trash") { ?>

                                                <td>

                                                    <a href="index?restore_proposal=<?= $proposal_id; ?>&page=<?= $page; ?>">

                                                        <i class="fa fa-reply"></i> Restore Proposal

                                                    </a>

                                                    <br />

                                                    <a href="index?delete_proposal=<?= $proposal_id; ?>&page=<?= $page; ?>">

                                                        <i class="fa fa-trash"></i> Delete Permanently

                                                    </a>

                                                </td>


                                            <?php } ?>

                                        </tr>

                                    <?php } ?>

                                </tbody>
                                <!--- tbody Ends --->

                            </table>
                            <!--- table table-hover table-bordered Ends --->

                        </div>
                        <!--- table-responsive mt-4 Ends --->

                        <div class="d-flex justify-content-center">
                            <!--- d-flex justify-content-center Starts --->

                            <ul class="pagination">
                                <!--- pagination Starts --->

                                <?php

                                /// Now Select All From Proposals Table

                                $query = $db->query("select * from proposals where proposal_status not in ('modification','draft','deleted')");

                                /// Count The Total Records

                                $total_records = $query->rowCount();

                                /// Using ceil function to divide the total records on per page

                                $total_pages = ceil($total_records / $per_page);

                                echo "<li class='page-item'><a href='index?view_proposals=1' class='page-link'> First Page </a></li>";

                                echo "<li class='page-item " . (1 == $page ? "active" : "") . "'><a class='page-link' href='index?view_proposals=1'>1</a></li>";

                                $i = max(2, $page - 5);

                                if ($i > 2) {
                                    echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
                                }

                                for (; $i < min($page + 6, $total_pages); $i++) {

                                    echo "<li class='page-item";
                                    if ($i == $page) {
                                        echo " active ";
                                    }
                                    echo "'><a href='index?view_proposals=" . $i . "' class='page-link'>" . $i . "</a></li>";
                                }

                                if ($i != $total_pages and $total_pages > 1) {
                                    echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
                                }

                                if ($total_pages > 1) {
                                    echo "<li class='page-item " . ($total_pages == $page ? "active" : "") . "'><a class='page-link' href='index?view_proposals=$total_pages'>$total_pages</a></li>";
                                }

                                echo "<li class='page-item'><a href='index?view_proposals=$total_pages' class='page-link'>Last Page </a></li>";

                                ?>

                            </ul>
                            <!--- pagination Ends --->

                        </div>
                        <!--- d-flex justify-content-center Ends --->

                    </div>
                    <!--- card-body Ends --->

                </div>
                <!--- card Ends --->

            </div>
            <!--- col-lg-12 Ends --->

        </div>
        <!--- 2 row mt-3 Ends --->

    </div>

<?php } ?>
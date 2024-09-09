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
                    <h1><i class="menu-icon fa fa-table"></i> Proposals / Pending Proposals</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li class="active">Pending Proposals</li>
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

                        <h4 class="h4">

                            Proposals
                        </h4>

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

                                    $get_proposals = $db->query("select * from proposals where proposal_status='pending' order by 1 DESC");

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
                                            <td>


                                                <a title="Preview this Proposal" href="../proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" target="_blank">

                                                    <i class="fa fa-eye"></i> Preview

                                                </a>

                                                <br>

                                                <a title="Submit this Proposal to seller for modification" href="index?submit_modification=<?= $proposal_id; ?>">

                                                    <i class="fa fa-edit"></i> Submit For Modification

                                                </a>

                                                <br>

                                                <a title="Approve this Proposal" href="index?approve_proposal=<?= $proposal_id; ?>">

                                                    <i class="fa fa-check-square-o"></i> Approve

                                                </a>

                                                <br>

                                                <a title="Decline this Proposal" href="index?decline_proposal=<?= $proposal_id; ?>">

                                                    <i class="fa fa-ban"></i> Decline

                                                </a>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>
                                <!--- tbody Ends --->

                            </table>
                            <!--- table table-hover table-bordered Ends --->


                            <?php if ($count_pending_proposals == 0) {

                                echo "<center><h3 class='pt-3 pb-3'> No Proposals pending for approval</h3></center>";
                            }




                            ?>

                        </div>
                        <!--- table-responsive mt-4 Ends --->


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
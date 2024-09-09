<?php

@session_start();

if (!isset($_SESSION['admin_email'])) {

    echo "<script>window.open('login','_self');</script>";
} else {

    $count_all_requests = $db->count("buyer_requests");
    $count_active_requests = $db->count("buyer_requests", array("request_status" => "active"));
    $count_pending_requests = $db->count("buyer_requests", array("request_status" => "pending"));
    $count_unapproved_requests = $db->count("buyer_requests", array("request_status" => "unapproved"));
    $count_pause_requests = $db->count("buyer_requests", array("request_status" => "pause"));

    if (isset($_GET['status'])) {

        $status = $input->get('status');
    } else {

        $status = "";
    }

?>

    <div class="breadcrumbs pt-4">

        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1><i class="menu-icon fa fa-table"></i> Buyer Requests</h1>
                </div>
            </div>
        </div>

        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li class="active">Buyer Requests</li>
                    </ol>
                </div>
            </div>
        </div>

    </div>

    <style>
        #tenPercent {
            width: 11%
        }

        #fifteenPercent {
            width: 15%;
        }

        #twentyPercent {
            width: 20%;
        }

        #tenePercent {
            width: 10%;
        }

        #tennnPercent {
            width: 10%;
        }

        #twelvePercent {
            width: 12%;
        }

        #tennPercent {
            width: 10%;
        }

        .after-final-inspection-lm {
            /* width: 78%; */
            float: right;
        }

        #variableactive,
        #variablepending,
        #variablewhole,
        #variablepause,
        #variableunapproved {
            padding: 15px 30px;
        }
    </style>

    <div class="container-fluid">

        <div class="row"><!--- 2 row Starts --->

            <div class="col-lg-12"><!--- col-lg-12 Starts --->

                <div class="card after-final-inspection-lm"><!--- card Starts --->

                    <div class="card-header mt-2"><!--- card-header Starts --->

                        <h4 class="h4">View Pending Buyer Requests</h4>

                    </div><!--- card-header Ends --->

                    <div class="card-body"><!--- card-body Starts --->

                        <a href="index?buyer_requests" id="variablewhole" class="<?php if ($status == " all ") {
                                                                                        echo "text-muted ";
                                                                                    } ?> mr-2">

                            All (<?= $count_all_requests; ?>)

                        </a>

                        <span class="mr-1">|</span>

                        <a href="index?buyer_requests&status=active" id="variableactive" class="<?php if ($status == " all ") {
                                                                                                    echo "text-muted ";
                                                                                                } ?> mr-2">

                            Active (<?= $count_active_requests; ?>)

                        </a>

                        <span class="mr-1">|</span>

                        <a href="index?buyer_requests&status=pending" id="variablepending" class="<?php if ($status == " all ") {
                                                                                                        echo "text-muted ";
                                                                                                    } ?> mr-2">

                            Pending (<?= $count_pending_requests; ?>)

                        </a>

                        <span class="mr-1">|</span>

                        <a href="index?buyer_requests&status=unapproved" id="variableunapproved" class="<?php if ($status == " all ") {
                                                                                                            echo "text-muted ";
                                                                                                        } ?> mr-2">

                            Declined (<?= $count_unapproved_requests; ?>)

                        </a>

                        <span class="mr-1">|</span>

                        <a href="index?buyer_requests&status=pause" id="variablepause" class="<?php if ($status == " all ") {
                                                                                                    echo "text-muted ";
                                                                                                } ?> mr-2">

                            Paused (<?= $count_pause_requests; ?>)

                        </a>

                        <div class="table-responsive mt-3" style="overflow-wrap: anywhere;"><!--- table-responsive Starts --->

                            <table class="table table-bordered"><!--- table table-bordered table-hover Starts --->

                                <thead><!--- thead Starts --->

                                    <tr>

                                        <th id="tenPercent">BUYER</th>

                                        <th id="fifteenPercent">TITLE</th>

                                        <th id="twentyPercent">DESCRIPTION</th>

                                        <th id="tennPercent">FILE</th>

                                        <th id="twelvePercent">DURATION</th>

                                        <th id="tennnPercent">BUDGET</th>

                                        <th id="tenePercent">STATUS</th>

                                        <th>ACTION</th>

                                    </tr>

                                </thead><!--- thead Ends --->

                                <tbody><!--- tbody Starts --->

                                    <?php

                                    $per_page = 10;

                                    if ($_GET['buyer_requests']) {

                                        $page = $_GET['buyer_requests'];

                                        if ($page == 0) {
                                            $page = 1;
                                        }
                                    } else {

                                        $page = 1;
                                    }

                                    /// Page will start from 0 and multiply by per page

                                    $start_from = ($page - 1) * $per_page;

                                    $search = "%$status%";

                                    $get_requests = $db->query("select * from buyer_requests where request_status LIKE :request_status order by 1 DESC LIMIT :limit OFFSET :offset", array(":request_status" => $search), array("limit" => $per_page, "offset" => $start_from));

                                    $count_requests = $get_requests->rowCount();

                                    if ($count_requests == 0) {

                                        echo "<td colspan='8' class='text-center'>No Requests Found.</td>";
                                    }

                                    while ($row_requests = $get_requests->fetch()) {

                                        $request_id = $row_requests->request_id;
                                        $request_title = $row_requests->request_title;
                                        $request_description = $row_requests->request_description;
                                        $seller_id = $row_requests->seller_id;
                                        $request_budget = $row_requests->request_budget;
                                        $delivery_time = $row_requests->delivery_time;
                                        $request_file = $row_requests->request_file;
                                        $request_status = $row_requests->request_status;

                                        $select_seller = $db->select("sellers", array("seller_id" => $seller_id));
                                        $seller_user_name = $select_seller->fetch()->seller_user_name;

                                    ?>

                                        <tr>

                                            <td>
                                                <?= $seller_user_name; ?>
                                            </td>

                                            <td>
                                                <?= $request_title; ?>
                                            </td>

                                            <td>
                                                <?= $request_description; ?>
                                            </td>

                                            <td>

                                                <?php if (!empty($request_file)) { ?>
                                                    <a href="<?= getImageUrl("buyer_requests", $request_file); ?>" download="<?= $request_file; ?>" class="text-primary" id="downloadLink">
                                                        <i class="fa fa-download"></i><?= $request_file; ?>
                                                    </a>
                                                    <iframe id="fileDisplay" style="width:100%; height:500px; display: none;"></iframe>

                                                    <script>
                                                        document.getElementById('downloadLink').addEventListener('click', function() {
                                                            document.getElementById('fileDisplay').src = this.href;
                                                            document.getElementById('fileDisplay').style.display = 'block';
                                                        });
                                                    </script>
                                                <?php } else { ?>
                                                    No File Attached
                                                <?php } ?>
                                            </td>

                                            <td><?= $delivery_time; ?></td>

                                            <td>

                                                <?php if (!empty($request_budget)) { ?>

                                                    <?= showPrice($request_budget); ?>

                                                <?php } else { ?> No Budget Set

                                                <?php } ?>

                                            </td>

                                            <td>
                                                <?= ucfirst($request_status); ?>
                                            </td>

                                            <td>

                                                <?php if ($request_status == "pending") { ?>

                                                    <a href="index?approve_request=<?= $request_id; ?>" class="btn btn-success" onclick="return confirm('You are about to approve this request. Continue?')">
                                                        <i class="fa fa-thumbs-up text-white" style="width:12px;"></i> <span class="text-white pl-1">Approve</span>
                                                    </a>

                                                    <div class="mb-2"></div>

                                                    <a href="index?modification_request=<?= $request_id; ?>" class="btn btn-info">
                                                        <i class="fa fa-edit text-white" style="width:12px;"></i> <span class="text-white pl-1">Modification Request</span>
                                                    </a>

                                                    <div class="mb-2"></div>

                                                    <a href="index?unapprove_request=<?= $request_id; ?>" class="btn  pt-1" onclick="return confirm('You are about to delete this request permanently. Continue?')" style="background-color:red;">
                                                        <i class="fa fa-thumbs-down text-white" style="width:23px;"></i> <span class="text-white">Decline</span>
                                                    </a>

                                                    <div class="mb-2"></div>

                                                <?php } ?>

                                                <a href="index?delete_request=<?= $request_id; ?>" class="btn btn-danger pt-1" onclick="return confirm('You are about to delete this request permanently. Continue?')">
                                                    <i class="fa fa-trash text-white" style="width:23px;"></i> <span class="text-white">Delete&nbsp;</span>
                                                </a>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>
                                <!--- tbody Ends --->

                            </table>
                            <!--- table table-bordered table-hover Ends --->

                        </div>
                        <!--- table-responsive Ends --->

                        <div class="d-flex justify-content-center"><!--- d-flex justify-content-center Starts --->

                            <ul class="pagination"><!--- pagination Starts --->

                                <?php

                                /// Now Select All Data From Table

                                $query = $db->query("select * from buyer_requests where request_status LIKE :request_status", array(":request_status" => $search));

                                /// Count The Total Records

                                $total_records = $query->rowCount();

                                /// Using ceil function to divide the total records on per page

                                $total_pages = ceil($total_records / $per_page);

                                echo "<li class='page-item'><a href='index?buyer_requests=1&status=$status' class='page-link'> First Page </a></li>";

                                echo "<li class='page-item " . (1 == $page ? "active" : "") . "'><a class='page-link' href='index?buyer_requests=1&status=$status'>1</a></li>";

                                $i = max(2, $page - 5);

                                if ($i > 2)

                                    echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";

                                for (; $i < min($page + 6, $total_pages); $i++) {

                                    echo "<li class='page-item";
                                    if ($i == $page) {
                                        echo " active ";
                                    }
                                    echo "'><a href='index?buyer_requests=$i&status=$status' class='page-link'>" . $i . "</a></li>";
                                }
                                if ($i != $total_pages and $total_pages > 1) {
                                    echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
                                }

                                if ($total_pages > 1) {
                                    echo "<li class='page-item " . ($total_pages == $page ? "active" : "") . "'><a class='page-link' href='index?buyer_requests=$total_pages&status=$status'>$total_pages</a></li>";
                                }

                                echo "<li class='page-item'><a href='index?buyer_requests=$total_pages&status=$status' class='page-link'>Last Page </a></li>";

                                ?>

                            </ul><!--- pagination Ends --->

                        </div><!--- d-flex justify-content-center Ends --->

                    </div><!--- card-body Ends --->

                </div><!--- card Ends --->

            </div><!--- col-lg-12 Ends --->

        </div><!--- 2 row Ends --->

    </div>

<?php } ?>

<script>
    let variablewhole = document.getElementById("variablewhole");
    let variableunapproved = document.getElementById("variableunapproved");
    let variablepending = document.getElementById("variablepending");
    let variablepause = document.getElementById("variablepause");
    let variableactive = document.getElementById("variableactive");
</script>

<!-- pending -->
<?php if ($status == "pending") { ?>
    <script>
        variablepending.style = "color:white; background-color:black";
        variableunapproved.style = "color:black; background-color:lightgray";
        variablepause.style = "color:black; background-color:lightgray";
        variableactive.style = "color:black; background-color:lightgray";
        variablewhole.style = "color:black; background-color:lightgray";
    </script>

<?php } ?>
<!-- unapproved -->
<?php if ($status == "unapproved") { ?>
    <script>
        variablepending.style = "color:black; background-color:lightgray";
        variableunapproved.style = "color:white; background-color:black";
        variablepause.style = "color:black; background-color:lightgray";
        variableactive.style = "color:black; background-color:lightgray";
        variablewhole.style = "color:black; background-color:lightgray";
    </script>

<?php } ?>

<?php if ($status == "active") { ?>
    <script>
        variablepending.style = "color:black; background-color:lightgray";
        variableunapproved.style = "color:black; background-color:lightgray";
        variablepause.style = "color:black; background-color:lightgray";
        variableactive.style = "color:white; background-color:black";
        variablewhole.style = "color:black; background-color:lightgray";
    </script>

<?php } ?>

<!-- variablepause -->

<?php if ($status == "pause") { ?>
    <script>
        variablepending.style = "color:black; background-color:lightgray";
        variableunapproved.style = "color:black; background-color:lightgray";
        variablepause.style = "color:white; background-color:black";
        variableactive.style = "color:black; background-color:lightgray";
        variablewhole.style = "color:black; background-color:lightgray";
    </script>

<?php } ?>


<?php if ($status == "") { ?>
    <script>
        variablepending.style = "color:black; background-color:lightgray";
        variableunapproved.style = "color:black; background-color:lightgray";
        variablepause.style = "color:black; background-color:lightgray";
        variableactive.style = "color:black; background-color:lightgray";
        variablewhole.style = "color:white; background-color:black";
    </script>

<?php } ?>
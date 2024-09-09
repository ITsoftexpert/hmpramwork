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
                    <h1><i class="menu-icon fa fa-flag"></i> Reports / View Offer Reports </h1>
                </div>
            </div>
        </div>

    </div>


    <div class="container-fluid">

        <div class="row">
            <!--- 3 row Starts --->

            <div class="col-lg-12">
                <!--- col-lg-12 Starts --->

                <div class="card">
                    <!--- card Starts --->

                    <div class="card-header">
                        <!--- card-header Starts --->

                        <h4 class="h4">View All View Offer Reports</h4>

                    </div>
                    <!--- card-header Ends --->

                    <div class="card-body">
                        <!--- card-body Starts --->

                        <div class="table-responsive">
                            <!--- table-responsive Starts --->

                            <table class="table table-bordered">
                                <!---- table table-bordered table-hover Starts --->

                                <thead>
                                    <!--- thead Starts --->

                                    <tr>

                                        <th> No </th>

                                        <th> Reporter </th>

                                        <th> Job </th>

                                        <th> Reason </th>

                                        <th> Additional Information </th>

                                        <th> Date </th>

                                        <th> Actions </th>

                                    </tr>

                                </thead>
                                <!--- thead Ends --->

                                <tbody>
                                    <!--- tbody Starts --->

                                    <?php

                                    $i = 0;

                                    $select_reports = $db->select("reports", array('content_type' => 'view_offers', 'status' => ''));

                                    while ($row_reports = $select_reports->fetch()) {

                                        $id = $row_reports->id;

                                        $reporter_id = $row_reports->reporter_id;

                                        $content_id = $row_reports->content_id;

                                        $reason = $row_reports->reason;

                                        $additional_information = $row_reports->additional_information;

                                        $date = $row_reports->date;

                                        $status = $row_reports->status;


                                        $select_seller = $db->select("sellers", array("seller_id" => $reporter_id));

                                        $reporter_user_name = $select_seller->fetch()->seller_user_name;

                                        $qReq = $db->select("buyer_requests", array("request_id" => $content_id));

                                        $oReq = $qReq->fetch();
                                        $reqId = $oReq->request_id;
                                        $reqTitle = $oReq->request_title;
                                        $reqSellerId = $oReq->seller_id;

                                        $qReqUser = $db->select("sellers", array("seller_id" => $reqSellerId));

                                        $reqUserName = $qReqUser->fetch()->seller_user_name;

                                        $i++;

                                    ?>

                                        <tr>

                                            <td><?= $i; ?></td>

                                            <td>
                                                <a href="../<?= $reporter_user_name; ?>" target="_blank" class="text-success"><?= $reporter_user_name; ?></a>
                                            </td>

                                            <td>
                                                <a href="../requests/view_offers?request_id=<?= $reqId ?>" target="_blank" class="text-success"><?= $reqTitle; ?> - <?= $reqUserName ?></a>
                                            </td>

                                            <td><?= $reason; ?></td>

                                            <td><?= $additional_information; ?></td>

                                            <td>
                                                <?= $date; ?>
                                            </td>

                                            <td>

                                                <div class="dropdown">
                                                    <!--- dropdown Starts --->

                                                    <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">Actions</button>

                                                    <div class="dropdown-menu" style="min-width:50px !important;">
                                                        <a class="dropdown-item" href="index?type=offer&action=taken&delete_report=<?= $id; ?>">
                                                            <i class="fa fa-check-square-o"></i> Action Taken
                                                        </a>
                                                        <a class="dropdown-item" href="index?type=offer&delete_report=<?= $id; ?>">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </a>

                                                    </div>
                                                    <!--- dropdown-menu Ends --->

                                                </div>
                                                <!--- dropdown Ends --->

                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>
                                <!--- tbody Ends --->

                            </table>
                            <!---- table table-bordered table-hover Ends --->

                        </div>
                        <!--- table-responsive Ends --->

                    </div>
                    <!--- card-body Ends --->

                </div>
                <!--- card Ends --->

            </div>
            <!--- col-lg-12 Ends --->

        </div>
        <!--- 3 row Ends --->

    </div>

<?php } ?>
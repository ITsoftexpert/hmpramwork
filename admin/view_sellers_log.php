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
                    <h1><i class="menu-icon fa fa-users"></i> Users Login Devices </h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li class="active">Users Login Devices</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid">

        <div class="row mt-4">
            <!--- 3 row Starts --->

            <div class="col-lg-12">
                <!--- col-lg-12 Starts --->

                <div class="card">
                    <!--- card Starts --->

                    <div class="card-header"><!--- card-header Starts --->

                        <h4 class="h4"> <i class="fa fa-money-bill-alt"></i> Users Login Devices </h4>

                    </div><!--- card-header Ends --->

                    <div class="card-body"><!--- card-body Starts --->

                        <div class="table-responsive"><!--- table-responsive Starts --->

                            <table class="table table-striped table-bordered"><!--- table table-bordered table-hover Starts --->

                                <thead><!--- thead Starts --->
                                    <tr>
                                        <th>Login IP</th>
                                        <th>User's Username</th>
                                        <th>User's Email</th>
                                        <th>Login Device</th>
                                        <th>Login Date</th>
                                    </tr>

                                </thead><!--- thead Ends --->

                                <tbody><!--- tbody Starts --->
                                    <?php
                                    $get_sellers = $db->query("SELECT seller_ip, COUNT(seller_ip) total_ips FROM sellers GROUP BY seller_ip HAVING COUNT(*) > 1;");

                                    while ($row_sellers = $get_sellers->fetch()) {

                                        $qSellers = $db->select("sellers", array("seller_ip" => $row_sellers->seller_ip));

                                        // $i++;
                                        $i = 0;
                                        while ($oSellers = $qSellers->fetch()) {
                                            $seller_id = $oSellers->seller_id;
                                            $seller_user_name = $oSellers->seller_user_name;
                                            $seller_email = $oSellers->seller_email;
                                            $device_type = $oSellers->device_type;
                                            $seller_activity = $oSellers->seller_activity;
                                    ?>
                                        <tr>
                                        <?php if ($i == 0) { ?>
                                            <td rowspan="<?= $row_sellers->total_ips; ?>">
                                                <?= $row_sellers->seller_ip; ?>
                                            </td>
                                        <?php } ?>
                                            <td><?=$seller_user_name?></td>
                                            <td><?=$seller_email?></td>
                                            <td><?=$device_type?></td>
                                            <td><?=$seller_activity?></td>
                                        </tr>
                                    <?php
                                            $i++;
                                        }
                                    }
                                    ?>

                                </tbody><!--- tbody Ends --->

                            </table><!--- table table-bordered table-hover Ends --->

                        </div><!--- table-responsive Ends --->


                    </div><!--- card-body Ends --->

                </div><!--- card Ends --->

            </div><!--- col-lg-12 Ends --->

        </div><!--- 3 row Ends --->

    </div>

<?php } ?>
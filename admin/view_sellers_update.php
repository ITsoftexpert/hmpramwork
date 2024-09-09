<?php
@session_start();

if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login','_self');</script>";
    exit;
}

$tabActive = isset($_GET['tab']) ? $_GET['tab'] : "profile";

?>


<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1><i class="menu-icon fa fa-users"></i> All Users/Profile Modification Requests </h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Profile Modification Requests</li>
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
                    <h4 class="h4"> <i class="fa fa-money-bill-alt"></i> Profile Modification Requests</h4>
                </div><!--- card-header Ends --->

                <div class="card-body"><!--- card-body Starts --->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link <?= $tabActive == 'profile' ? ' active' : '' ?>" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Profile
                            <span class="badge badge-danger"><?= $countSellerProfileReq; ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $tabActive == 'professional' ? ' active' : '' ?>" id="professional-tab" data-toggle="tab" href="#professional" role="tab" aria-controls="professional" aria-selected="false">
                                Professional Info
                                <span class="badge badge-danger"><?= $countSellerProReq; ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $tabActive == 'account' ? ' active' : '' ?>" id="account-tab" data-toggle="tab" href="#account" role="tab" aria-controls="account" aria-selected="false">Account</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade <?= $tabActive == 'profile' ? ' show active' : '' ?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="table-responsive mt-2"><!--- table-responsive Starts --->
                                <table class="table table-bordered"><!--- table table-bordered table-hover Starts --->
                                    <thead><!--- thead Starts --->
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Email Verified</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead><!--- thead Ends --->
                                    <tbody><!--- tbody Starts --->
                                    <?php
                                        $per_page = 8;

                                        if ($tabActive == 'profile' && isset($_GET['page'])) {
                                            $page = $input->get('page');
                                            if ($page == 0)
                                                $page = 1;
                                        } else {
                                            $page = 1;
                                        }

                                        $i = ($page * $per_page) - 8;

                                        /// Now Select All From Proposals Table
                                        $query = $db->query("SELECT s.seller_id, s.seller_name, s.seller_email, s.seller_verification, s.seller_status FROM sellers s JOIN sellers_profile_tmp p ON (s.seller_id = p.seller_id) AND p.status = :status GROUP BY p.seller_id", ["status" => 0]);

                                        /// Count The Total Records
                                        $totalRecords = $query->rowCount();

                                        /// Page will start from 0 and multiply by per page
                                        $start_from = ($page - 1) * $per_page;

                                        $get_sellers = $db->query("SELECT s.seller_id, s.seller_name, s.seller_email, s.seller_verification, s.seller_status FROM sellers s JOIN sellers_profile_tmp p ON (s.seller_id = p.seller_id) AND p.status = :status GROUP BY p.seller_id ORDER BY 1 DESC  LIMIT :limit OFFSET :offset", array("status" => 0), array("limit" => $per_page, "offset" => $start_from));

                                        while ($row_sellers = $get_sellers->fetch()) {

                                            $seller_id = $row_sellers->seller_id;
                                            $seller_name = $row_sellers->seller_name;
                                            $seller_email = $row_sellers->seller_email;
                                            $seller_status = $row_sellers->seller_status;
                                            $seller_verification = $row_sellers->seller_verification;

                                            $email_verification = "No";
                                            if ($seller_verification == "ok")
                                                $email_verification = "Yes";

                                            $i++;
                                        ?>
                                            <tr>
                                                <td>
                                                    <?= $i; ?>
                                                </td>
                                                <td>
                                                    <?= $seller_name; ?>
                                                </td>
                                                <td>
                                                    <?= $seller_email; ?>
                                                </td>
                                                <td>
                                                    <?= $email_verification; ?>
                                                </td>
                                                <td width="150px;">
                                                    <div class="dropdown"><!--- dropdown Starts --->
                                                        <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">Actions</button>
                                                        <div class="dropdown-menu" style="margin-left:-125px;"><!--- dropdown-menu Starts --->
                                                            <a class="dropdown-item" href="index?single_seller=<?= $seller_id; ?>">
                                                                <i class="fa fa-info-circle"></i> User's Details
                                                            </a>
                                                            <?php if ($seller_status == "block-ban") { ?>
                                                                <a class="dropdown-item" href="index?unblock_seller=<?= $seller_id; ?>">
                                                                    <i class="fa fa-unlock"></i> Already Banned! Unblock Seller?
                                                                </a>
                                                            <?php } else { ?>
                                                                <a class="dropdown-item" href="index?ban_seller=<?= $seller_id; ?>">
                                                                    <i class="fa fa-ban"></i> Block / Ban User
                                                                </a>
                                                            <?php } ?>
                                                        </div><!--- dropdown-menu Ends --->
                                                    </div><!--- dropdown Ends --->
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody><!--- tbody Ends --->
                                </table><!--- table table-bordered table-hover Ends --->
                            </div><!--- table-responsive Ends --->

                            <div class="d-flex justify-content-center"><!--- d-flex justify-content-center Starts --->
                                <ul class="pagination"><!--- pagination Starts --->
                                <?php
                                    /// Using ceil function to divide the total records on per page
                                    $totalPages = ceil($totalRecords / $per_page);
                                    echo pagination($per_page, $page, $totalRecords, $totalPages, 'index?view_sellers_update&tab=profile&page=')
                                ?>
                                </ul><!--- pagination Ends --->
                            </div><!--- d-flex justify-content-center Ends --->
                        </div>

                        <div class="tab-pane fade <?= $tabActive == 'professional' ? ' show active' : '' ?>" id="professional" role="tabpanel" aria-labelledby="professional-tab">
                            <div class="table-responsive mt-2"><!--- table-responsive Starts --->
                                <table class="table table-bordered"><!--- table table-bordered table-hover Starts --->
                                    <thead><!--- thead Starts --->
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Email Verified</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead><!--- thead Ends --->
                                    <tbody><!--- tbody Starts --->
                                    <?php
                                        $per_page = 8;

                                        if ($tabActive == 'professional' && isset($_GET['page'])) {
                                            $page = $input->get('page');
                                            if ($page == 0)
                                                $page = 1;
                                        } else {
                                            $page = 1;
                                        }

                                        $i = ($page * $per_page) - 8;

                                        /// Now Select All From Proposals Table
                                        $query = $db->query("SELECT s.seller_id, s.seller_name, s.seller_email, s.seller_verification, s.seller_status FROM sellers s JOIN seller_pro_info p ON (s.seller_id = p.seller_id) AND p.status = :status GROUP BY p.seller_id", ["status" => 0]);

                                        /// Count The Total Records
                                        $totalRecords = $query->rowCount();

                                        /// Page will start from 0 and multiply by per page
                                        $start_from = ($page - 1) * $per_page;

                                        $get_sellers = $db->query("SELECT s.seller_id, s.seller_name, s.seller_email, s.seller_verification, s.seller_status FROM sellers s JOIN seller_pro_info p ON (s.seller_id = p.seller_id) AND p.status = :status GROUP BY p.seller_id ORDER BY 1 DESC  LIMIT :limit OFFSET :offset", array("status" => 0), array("limit" => $per_page, "offset" => $start_from));

                                        while ($row_sellers = $get_sellers->fetch()) {

                                            $seller_id = $row_sellers->seller_id;
                                            $seller_name = $row_sellers->seller_name;
                                            $seller_email = $row_sellers->seller_email;
                                            $seller_status = $row_sellers->seller_status;
                                            $seller_verification = $row_sellers->seller_verification;

                                            $email_verification = "No";
                                            if ($seller_verification == "ok")
                                                $email_verification = "Yes";

                                            $i++;
                                        ?>
                                            <tr>
                                                <td>
                                                    <?= $i; ?>
                                                </td>
                                                <td>
                                                    <?= $seller_name; ?>
                                                </td>
                                                <td>
                                                    <?= $seller_email; ?>
                                                </td>
                                                <td>
                                                    <?= $email_verification; ?>
                                                </td>
                                                <td width="150px;">
                                                    <div class="dropdown"><!--- dropdown Starts --->
                                                        <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">Actions</button>
                                                        <div class="dropdown-menu" style="margin-left:-125px;"><!--- dropdown-menu Starts --->
                                                            <a class="dropdown-item" href="index?single_seller=<?= $seller_id; ?>">
                                                                <i class="fa fa-info-circle"></i> User's Details
                                                            </a>
                                                            <?php if ($seller_status == "block-ban") { ?>
                                                                <a class="dropdown-item" href="index?unblock_seller=<?= $seller_id; ?>">
                                                                    <i class="fa fa-unlock"></i> Already Banned! Unblock Seller?
                                                                </a>
                                                            <?php } else { ?>
                                                                <a class="dropdown-item" href="index?ban_seller=<?= $seller_id; ?>">
                                                                    <i class="fa fa-ban"></i> Block / Ban User
                                                                </a>
                                                            <?php } ?>
                                                        </div><!--- dropdown-menu Ends --->
                                                    </div><!--- dropdown Ends --->
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody><!--- tbody Ends --->
                                </table><!--- table table-bordered table-hover Ends --->
                            </div><!--- table-responsive Ends --->

                            <div class="d-flex justify-content-center"><!--- d-flex justify-content-center Starts --->
                                <ul class="pagination"><!--- pagination Starts --->
                                <?php
                                    /// Using ceil function to divide the total records on per page
                                    $totalPages = ceil($totalRecords / $per_page);
                                    echo pagination($per_page, $page, $totalRecords, $totalPages, 'index?view_sellers_update&tab=professional&page=')
                                ?>
                                </ul><!--- pagination Ends --->
                            </div><!--- d-flex justify-content-center Ends --->
                        </div>

                        <div class="tab-pane fade <?= $tabActive == 'account' ? ' show active' : '' ?>" id="account" role="tabpanel" aria-labelledby="account-tab">
                            account
                        </div>
                    </div>
                </div><!--- card-body Ends --->
            </div><!--- card Ends --->
        </div><!--- col-lg-12 Ends --->
    </div><!--- 3 row Ends --->
</div>
<?php

@session_start();

if (!isset($_SESSION['admin_email'])) {

    echo "<script>window.open('login','_self');</script>";
} else {
    if (isset($_POST['submit'])) {
        $plan_name = $input->post('plan_name');
        $create_active_service = $input->post('create_active_service');
        $bids_per_month = $input->post('bids_per_month');
        $skills = $input->post('skills');
        $client_engagement = $input->post('client_engagement');
        $daily_withdrawal_req = $input->post('daily_withdrawal_req');
        $employer_follow = $input->post('employer_follow');
        $percentage_per_project = $input->post('percentage_per_project');
        $get_recom = $input->post('get_recom');
        $create_portfolio = $input->post('create_portfolio');
        $custome_desc = $input->post('custome_desc');
        $referrals = $input->post('referrals');
        $project_bookmark = $input->post('project_bookmark');
        $custom_cover_photo = $input->post('custom_cover_photo');
        $create_offer_coupon = $input->post('create_offer_coupon');
        $free_highlight_bids = $input->post('free_highlight_bids');
        $hide_project_others = $input->post('hide_project_others');
        $account_type = $input->post('account_type');
        $plan_price_month = $input->post('plan_price_month');
        $plan_discount_month = $input->post('plan_discount_month');

        $plan_price_annuel = $input->post('plan_price_annuel');
        $plan_discount_annuel = $input->post('plan_discount_annuel');

        $plan_status = $input->post('plan_status');


        $insert_plan = $db->insert("membership_table", array(
            "plan_name" => $plan_name, "create_active_service" => $create_active_service,
            "bids_per_month" => $bids_per_month, "skills" => $skills, "client_engagement" => $client_engagement, "daily_withdrawal_req" => $daily_withdrawal_req,
            "employer_follow" => $employer_follow, "percentage_per_project" => $percentage_per_project, "get_recom" => $get_recom, "create_portfolio" => $create_portfolio,
            "custome_desc" => $custome_desc, "referrals" => $referrals, "project_bookmark" => $project_bookmark, "custom_cover_photo" => $custom_cover_photo,
            "create_offer_coupon" => $create_offer_coupon, "free_highlight_bids" => $free_highlight_bids, "hide_project_others" => $hide_project_others, "account_type" => $account_type,
            "plan_price_month" => $plan_price_month, "plan_discount_month" => $plan_discount_month, "plan_price_annuel" => $plan_price_annuel, "plan_discount_annuel" => $plan_discount_annuel, "plan_status" => $plan_status
        ));

        die($insert_plan);

        if ($insert_plan) {
            //            $insert_id = $db->lastInsertId();
            //          $insert_log = $db->insert_log($admin_id,"slide",$insert_id,"inserted");
            echo "<script>alert('New Plan has been added');</script>";
            echo "<script>window.open('index?add_new_plan','_self');</script>";
        }

        //      }

        // }
    }
?>

    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1><i class="menu-icon fa fa-picture-o"></i> Add Plan </h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li class="active">Add New Plan</li>
                    </ol>
                </div>
            </div>
        </div>

    </div>


    <div class="container">


        <div class="row">
            <!--- 2 row Starts --->
            <div class="col-lg-12">
                <!--- col-lg-12 Starts --->
                <?php
                $form_errors = Flash::render("form_errors");
                $form_data = Flash::render("form_data");
                if (is_array($form_errors)) {
                ?>
                    <div class="alert alert-danger">
                        <!--- alert alert-danger Starts --->
                        <ul class="list-unstyled mb-0">
                            <?php $i = 0;
                            foreach ($form_errors as $error) {
                                $i++; ?>
                                <li class="list-unstyled-item"><?= $i ?>. <?= ucfirst($error); ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!--- alert alert-danger Ends --->
                <?php } ?>
                <div class="card">
                    <!--- card Starts --->
                    <div class="card-header">
                        <!--- card-header Starts --->
                        <h4 class="h4">
                            Add New Plan <br>
                            <small>This Plan will show in logged in homepage</small>
                        </h4>
                    </div>
                    <!--- card-header Ends --->
                    <div class="card-body">
                        <!--- card-body Starts --->
                        <form action="" method="post" enctype="multipart/form-data">
                            <!--- form Starts --->
                            <div class="form-row">
                                <div class="form-group col">
                                    <label for="plan_name">Plan Name:</label>
                                    <input type="text" class="form-control" name="plan_name" id="plan_name" placeholder="Plan Name" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="create_active_service">Create Active Service:</label>
                                    <input type="number" name="create_active_service" class="form-control" id="create_active_service" placeholder="Create Active Service" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="bids_per_month">Bids Per Month:</label>
                                    <input type="number" class="form-control" name="bids_per_month" id="bids_per_month" placeholder="Bids Per Month" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="skills">No. of Skills:</label>
                                    <input type="number" name="skills" class="form-control" id="skills" placeholder="No. of Skills" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="client_engagement" class="col-12">Client Engagement:</label>
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="client_engagement" id="client_engagement" value="No" checked required>
                                            <label class="form-check-label" for="client_engagement">No</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="client_engagement" id="client_engagement1" value="Yes" required>
                                            <label class="form-check-label" for="client_engagement1">Yes</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="daily_withdrawal_req" class="col-12">Daily Withdrawl Request:</label>
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="daily_withdrawal_req" id="daily_withdrawal_req" value="No" checked required>
                                            <label class="form-check-label" for="daily_withdrawal_req">No</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="daily_withdrawal_req" id="daily_withdrawal_req1" value="Yes" required>
                                            <label class="form-check-label" for="daily_withdrawal_req1">Yes</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="employer_follow" class="col-12">Employer Followings:</label>
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="employer_follow" id="employer_follow" value="No" checked required>
                                            <label class="form-check-label" for="employer_follow">No</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="employer_follow" id="employer_follow1" value="Yes" required>
                                            <label class="form-check-label" for="employer_follow1">Yes</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="percentage_per_project">Percentage Per Project:</label>
                                    <input type="number" step="any" name="percentage_per_project" class="form-control" id="percentage_per_project" placeholder="Percentage Per Project" required required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="get_recom" class="col-12">Get Recommendation:</label>
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="get_recom" id="get_recom" value="No" checked required>
                                            <label class="form-check-label" for="get_recom">No</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="get_recom" id="get_recom1" value="Yes" required>
                                            <label class="form-check-label" for="get_recom1">Yes</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="create_portfolio" class="col-12">Create Portfolio:</label>
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="create_portfolio" id="create_portfolio" value="No" checked required>
                                            <label class="form-check-label" for="create_portfolio">No</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="create_portfolio" id="create_portfolio1" value="Yes" required>
                                            <label class="form-check-label" for="create_portfolio1">Yes</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="custome_desc" class="col-12">Custom Description:</label>
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="custome_desc" id="custome_desc" value="No" checked required>
                                            <label class="form-check-label" for="custome_desc">No</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="custome_desc" id="custome_desc1" value="Yes" required>
                                            <label class="form-check-label" for="custome_desc1">Yes</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="referrals" class="col-12">Referrals:</label>
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="referrals" id="referrals" value="No" checked required>
                                            <label class="form-check-label" for="referrals">No</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="referrals" id="referrals1" value="Yes" required>
                                            <label class="form-check-label" for="referrals1">Yes</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="project_bookmark">Project Bookmarks:</label>
                                    <input type="text" name="project_bookmark" class="form-control" id="project_bookmark" placeholder="Project Bookmarks (No, Unlimited, Number)" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="custom_cover_photo" class="col-12">Custom Cover Photo:</label>
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="custom_cover_photo" id="custom_cover_photo" value="No" checked required>
                                            <label class="form-check-label" for="custom_cover_photo">No</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="custom_cover_photo" id="custom_cover_photo1" value="Yes" required>
                                            <label class="form-check-label" for="custom_cover_photo1">Yes</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="create_offer_coupon" class="col-12"> Create A Offer Coupon:</label>
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="create_offer_coupon" id="create_offer_coupon" value="No" checked required>
                                            <label class="form-check-label" for="create_offer_coupon">No</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="create_offer_coupon" id="create_offer_coupon1" value="Yes" required>
                                            <label class="form-check-label" for="create_offer_coupon1">Yes</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="free_highlight_bids">Free Highlighted Bids:</label>
                                    <input type="text" name="free_highlight_bids" class="form-control" id="free_highlight_bids" placeholder="Free Highlighted Bids (No, Yes, Number)" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="hide_project_others" class="col-12">Hide Project Offer for Other Freelancers:</label>
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="hide_project_others" id="hide_project_others" value="No" checked required>
                                            <label class="form-check-label" for="hide_project_others">No</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="hide_project_others" id="hide_project_others1" value="Yes" required>
                                            <label class="form-check-label" for="hide_project_others1">Yes</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="plan_price_month">Plan Price Month:</label>
                                    <input type="number" step="any" name="plan_price_month" class="form-control" id="plan_price_month" placeholder="Plan Price Month" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="plan_discount_month">Discount on Plan/Month:</label>
                                    <input type="number" step="any" name="plan_discount_month" class="form-control" id="plan_discount_month" placeholder="Discount on Plan/Month" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="plan_price_annuel">Plan Price Annual:</label>
                                    <input type="number" step="any" name="plan_price_annuel" class="form-control" id="plan_price_annuel" placeholder="Plan Price Annual" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="plan_discount_annuel">Discount on Plan/Annual:</label>
                                    <input type="number" step="any" name="plan_discount_annuel" class="form-control" id="plan_discount_annuel" placeholder="Discount on Plan/Annual" required>
                                </div>
                            </div>

                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label for="account_type" class="col-12">Type of Account:</label>
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="account_type" id="account_type" value="Business" required>
                                            <label class="form-check-label" for="account_type">Business</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="account_type" id="account_type1" value="Personal" required>
                                            <label class="form-check-label" for="account_type1">Personal</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="plan_status" class="col-12">Plan Status:</label>
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="plan_status" id="plan_status" value="Active" required>
                                            <label class="form-check-label" for="plan_status">Active</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="plan_status" id="plan_status1" value="Dective" required>
                                            <label class="form-check-label" for="plan_status1">Dective</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--<div class="form-group row">

                    <label class="col-md-3 checkbox-label"> Are You Sure Want to Add This Plan : </label>
                    &nbsp  &nbsp<div class="col-md-0 checkbox">
                         <input required type="checkbox" name="confirm" class="form-control">
                    </div>
                </div>
               -->
                            <!--- form-group row Ends --->



                            <!--- form-group row Ends --->
                            <div class="form-group row">
                                <!--- form-group row Starts --->
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-6">
                                    <input required type="submit" name="submit" class="btn btn-success form-control" value="Add New Plan">
                                </div>
                            </div>
                            <!--- form-group row Ends --->
                        </form>
                        <!--- form Ends --->
                    </div>
                    <!--- card-body Ends --->
                </div>
                <!--- card Ends --->
            </div>
            <!--- col-lg-12 Ends --->
        </div>
        <!--- 2 row Ends --->

    </div>
    <!--- Container Ends --->

<?php
}
?>
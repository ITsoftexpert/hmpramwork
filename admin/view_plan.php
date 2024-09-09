<?php

@session_start();
if(!isset($_SESSION['admin_email'])){
    echo "<script>window.open('login','_self');</script>";
}else{


    ?>
    <div class="breadcrumbs">

        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1><i class="menu-icon fa fa-picture-o"></i> Slides</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li class="active">
                            <a href="index?add_new_plan" class="btn btn-success">
                                <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Plan</span>
                            </a>
                        </li>
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

                <div class="card">
                    <!--- card Starts --->

                    <div class="card-header">
                        <!--- card-header Starts --->
                        <h4 class="h4">
                            View Membership Plans <br><small>These Plans will show up in logged in homepage</small>
                        </h4>
                    </div>
                    <!--- card-header Ends --->

                    <div class="card-body">
                        <!--- card-body Starts --->

                        <div class="row">
                            <!--- row Starts --->

                            <?php

//                            $get_plan = $db->select("membership_table",array("language_id" => $adminLanguage));
                            $get_plan = $db->select("membership_table");
                            while($row_plan = $get_plan->fetch()){

                                $plan_id = $row_plan->id;
                                $plan_name = $row_plan->plan_name;


                                ?>

                                <div class="col-lg-4 col-md-6 mb-lg-3 mb-3">
                                    <!--- col-lg-3 col-md-6 mb-lg-0 mb-3 Starts --->

                                    <div class="card">
                                        <!--- card Starts --->

                                        <div class="card-header text-center">

                                            <h5 class="h5 text-center"><?= $plan_name; ?> </h5>

                                        </div>



                                        <div class="card-footer"><!--- card-footer Starts --->

                                            <a href="index?remove_plan=<?= $plan_id; ?>" class="float-left btn btn-danger" title="Delete">

                                                <i class="fa fa-trash text-white"></i>

                                            </a>

                                            <a href="index?alter_plan=<?= $plan_id; ?>" class="float-right btn btn-success" title="edit">

                                                <i class="fa fa-pencil text-white"></i>

                                            </a>

                                            <div class="clearfix"></div>

                                        </div><!--- card-footer Ends --->

                                    </div><!--- card Ends --->

                                </div><!--- col-lg-3 col-md-6 mb-lg-0 mb-3 Ends --->

                            <?php } ?>

                        </div><!--- row Ends --->

                    </div><!--- card-body Ends --->

                </div><!--- card Ends --->

            </div><!--- col-lg-12 Ends --->

        </div><!--- 2 row Ends --->

    </div>

<?php } ?>
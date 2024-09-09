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
                    <h1><i class="menu-icon fa fa-flash"></i> Seller Skills / Insert</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li class="active">Add New Seller Skill</li>
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

                    <div class="alert alert-danger"><!--- alert alert-danger Starts --->

                        <ul class="list-unstyled mb-0">
                            <?php $i = 0;
                            foreach ($form_errors as $error) {
                                $i++; ?>
                                <li class="list-unstyled-item"><?= $i ?>. <?= ucfirst($error); ?></li>
                            <?php } ?>
                        </ul>

                    </div><!--- alert alert-danger Ends --->

                <?php } ?>

                <div class="card">
                    <!--- card Starts --->

                    <div class="card-header">
                        <!--- card-header Starts --->

                        <h4 class="h4">

                            Add New Skill

                        </h4>

                    </div>
                    <!--- card-header Ends --->

                    <div class="card-body">
                        <!--- card-body Starts --->

                        <form action="" method="post">
                            <!--- form Starts --->
                            <div class="form-group row">
                                <!--- form-group row Starts --->

                                <label class="col-md-3 control-label"> Seller category : </label>

                                <div class="col-md-6">

                                    <select class="custom-select w-100" name="skill_cat_id" id="category_skills" required="">
                                        <option value="" class="hidden">
                                            <?= $lang['placeholder']['select_category']; ?>
                                        </option>
                                        <?php
                                        $get_cats = $db->select("categories");
                                        while ($row_cats = $get_cats->fetch()) {
                                            $cat_id = $row_cats->cat_id;
                                            $get_meta = $db->select("cats_meta", ["cat_id" => $cat_id]);
                                            $row_meta = $get_meta->fetch();
                                            $cat_title = $row_meta->cat_title;
                                        ?>
                                            <option value="<?= $cat_id; ?>">
                                                <?= $cat_title; ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                </div>

                            </div>
                            <div class="form-group row">
                                <!--- form-group row Starts --->
                                <label class="col-md-3 control-label"> seller sub category : </label>
                                <div class="col-md-6">
                                    <select class="custom-select w-100" name="skill_child_id" id="skill-sub-category" required="">
                                        <option value="select-sub-category" selected> Select A Sub Category</option>
                                        <?php
                                        $get_c_cats = $db->select("categories_children");
                                        while ($row_c_cats = $get_c_cats->fetch()) {
                                            $child_id = $row_c_cats->child_id;
                                            $child_parent_id = $row_c_cats->child_parent_id;
                                            $get_meta = $db->select("child_cats_meta", array("child_id" => $child_id));
                                            $row_meta = $get_meta->fetch();
                                            $child_title = $row_meta->child_title;
                                        ?>
                                            <option class="sub-category-option" data-parent="<?= $child_parent_id; ?>" value="<?= $child_id; ?>">
                                                <?= $child_title; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <!--- form-group row Starts --->

                                <label class="col-md-3 control-label"> Attribute : </label>

                                <div class="col-md-6">

                                    <select class="form-control box-shadow-post-req" name="sub_child_id" id="skil-sub-sub-category" required="">
                                        <option value="" class="hidden">
                                            <?= $lang['placeholder']['select_sub_sub_category']; ?>
                                        </option>
                                    </select>

                                </div>

                            </div>
                            <div class="form-group row">
                                <!--- form-group row Starts --->

                                <label class="col-md-3 control-label"> Seller Skill Title : </label>

                                <div class="col-md-6">

                                    <input type="text" name="skill_title" class="form-control" required="">

                                </div>

                            </div>
                            <!--- form-group row Ends --->


                            <div class="form-group row">
                                <!--- form-group row Starts --->

                                <label class="col-md-3 control-label"></label>

                                <div class="col-md-6">

                                    <input type="submit" name="submit" class="btn btn-success form-control" value="Add Skill">

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

        <?php


        if (isset($_POST['submit'])) {
            $rules = array(
                "skill_cat_id" => "required",
                "skill_child_id" => "required",
                "sub_child_id" => "required",
                "skill_title" => "required"
            );

            $messages = array(
                "skill_cat_id" => "Category is required.",
                "skill_child_id" => "Sub Category is required.",
                "sub_child_id" => "Attribute is required.",
                "skill_title" => "Seller Skill Title is required."
            );

            $val = new Validator($_POST, $rules, $messages);

            if ($val->run() == false) {
                Flash::add("form_errors", $val->get_all_errors());
                Flash::add("form_data", $_POST);
                echo "<script>window.open('index?insert_seller_skill','_self');</script>";
            } else {
                $data = array(
                    "cat_id" => $_POST['skill_cat_id'],
                    "child_id" => $_POST['skill_child_id'],
                    "sub_child_id" => $_POST['sub_child_id'],
                    "skill_title" => $_POST['skill_title'] 
                );

                $insert_seller_skill = $db->insert("seller_skills", $data);

                if ($insert_seller_skill) {
                    $insert_id = $db->lastInsertId();
                    $insert_log = $db->insert_log($admin_id, "seller_skill", $insert_id, "inserted");

                    echo "<script>alert('One Seller Skill Has Been Inserted.');</script>";
                    echo "<script>window.open('index?view_seller_skills','_self');</script>";
                }
            }
        }
        ?>

       

    </div>

<?php } ?>


<script>
    $(document).ready(function() {
        $('#category_skills').change(function() {
            var skill_cat_id = $(this).val();
            // $('.display-sub-none').hide();
            // $('.display-sub-sub-none').hide();

            $.ajax({
                url: '../skill_sub_categories',
                method: 'POST',
                data: {
                    skill_cat_id: skill_cat_id
                },
                success: function(response) {
                    // $('.display-sub-none').show();
                    // $('.display-sub-sub-none').hide();
                    $('#skill-sub-category').html(response);
                    $('#skil-sub-sub-category').html('<option value="" class="hidden"><?= $lang['placeholder']['select_sub_sub_category']; ?></option>');

                }
            });
        });

        $('#skill-sub-category').change(function() {
            var skill_child_id = $(this).val();
            $.ajax({
                url: '../skill_sub_subcategories',
                method: 'POST',
                data: {
                    skill_child_id: skill_child_id
                },
                success: function(response) {
                    $('#skil-sub-sub-category').html(response);
                }
            });
        });
    });
</script>
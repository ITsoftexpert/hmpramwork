<?php
@session_start();
require_once("includes/db.php");
if (!isset($_SESSION['seller_user_name'])) {
    echo "<script>window.open('login','_self')</script>";
}

if (isset($_POST['submit_professional'])) {

    $occupations = $input->post("occupation");
    $form_status = $input->post("form_status");
    $inserted = 0;

    // OCCUPATIONS
    if (count($occupations) > 0) {
        // delete
        if (!is_null($form_status)) {
            $db->query("DELETE spi, spio FROM seller_pro_info spi LEFT JOIN seller_pro_info_options spio ON spi.id = spio.seller_pro_info_id WHERE spi.seller_id = :seller_id;", ['seller_id' => $login_seller_id]);
        }
        foreach ($occupations as $key => $occupation) {

            $form = [];
            $form['category_id'] = $occupation['category_id'];
            $form['sub_category_id'] = $occupation['sub_category_id'];
            $form['still_working'] = $occupation['still_working'];
            $form['description'] = $occupation['description'];
            $form['start_date'] = $occupation['start_date'];
            $form['end_date'] = $occupation['end_date'];
            $form['status'] = 0;
            $form['seller_id'] = $login_seller_id;

            // Assuming $occupation is being populated from $_POST['occupation']
            $occupation = isset($_POST['occupation']) ? $_POST['occupation'] : [];

            $insertForm = $db->insert("seller_pro_info", $form);
            if ($insertForm) {
                $newId = $db->lastInsertId();
                $options = isset($occupation['option_id']) ? $occupation['option_id'] : false;

                if ($options) {
                    foreach ($options as $j => $option) {
                        $optionForm = ["seller_pro_info_id" => $newId];
                        $optionForm['professional_info_id'] = $option;
                        $db->insert("seller_pro_info_options", $optionForm);
                        $inserted++;
                    }
                }
                $inserted++;
            }
        }

        if ($form_status == 1) {
            $db->update('seller_profile_weights', ['professional_weight' => null], ['seller_id' => $login_seller_id]);
        }
    }

    // SKILLS
    $formSkills = $_POST['skills'];
    $skill_cat_id = $_POST['skill_cat_id'];
    $skill_child_id = $_POST['skill_child_id'];
    $skill_sub_child_id = $_POST['skill_sub_child_id'];

    //     echo "<pre>";
    //     print_r($skill_cat_id);
    //     echo "</pre>";
    // exit();
    if (count($formSkills) > 0) {
        $skillsTotalAdded = $db->count("skills_relation", ["seller_id" => $login_seller_id]);
        $skillsTotalForm = count($formSkills);

        $skillsTotalCanAdd = $skillsTotalAdded > 0 ? $skillsTotalForm - $skillsTotalAdded : $skillsTotalForm;

        // If skills exceeds avaiable quota
        if ($skillsTotalCanAdd > $skills) {
            echo "<script>
              swal({
                type: 'warning',
                text: 'Available No of skills quota exceeds.',
                timer: 3000,
                onOpen: function(){
                  swal.showLoading()
                }
              }).then(function(){
                // Read more about handling dismissals
                window.open('settings?professional_settings','_self');
              });
              </script>";
            exit();
        }

        $skillError = [];
        $formSkills = $_POST['skills'];
        foreach ($formSkills as $key => $skill) {
            $skillId = $skill['id'];
            $skillLevel = $skill['level'];
            $cat_id = $skill_cat_id;
            $child_id = $skill_child_id;
            $sub_child_id = $skill_sub_child_id;

            if ($skillId == "custom") {
                $skill_name = $input->post('skill_name');
                $count = $db->count("seller_skills", ["skill_title" => $skill_name]);

                if ($count == 1) {
                    $skillError = $lang['alert']['skill_already_added'];
                } else {
                    $insert_skill = $db->insert("seller_skills", array("skill_title" => $skill_name));
                    $skillId = $db->lastInsertId();
                    $inserted++;
                }
            } else {
                $skillCountAlready = $db->count("skills_relation", ["seller_id" => $login_seller_id, 'skill_id' => $skillId]);
                // Add only if new found
                if ($skillCountAlready == 0) {
                    $sForm = [
                        "skill_id" => $skillId,
                        "skill_level" => $skillLevel,
                        "seller_id" => $login_seller_id,
                        "skill_cat_id" => $cat_id,
                        "skill_child_id" => $child_id,
                        "skill_sub_child_id" => $sub_child_id
                    ];
                    $db->insert("skills_relation", $sForm);
                    $inserted++;
                }
            }
        }
    }

    if ($inserted > 0) {
        echo "<script>
              swal({
                type: 'success',
                text: 'Professional Info updated successfully!',
                timer: 3000,
                onOpen: function(){
                  swal.showLoading()
                }
              }).then(function(){
                // Read more about handling dismissals
                window.open('settings?professional_settings','_self');
              });
              </script>";
        exit();
    } else {
        echo "<script>
              swal({
                type: 'warning',
                text: 'Professional Info didnot updated.',
                timer: 3000,
                onOpen: function(){
                  swal.showLoading()
                }
              }).then(function(){
                // Read more about handling dismissals
                window.open('settings?professional_settings','_self');
              });
              </script>";
        exit();
    }
}



$qProInfo = $db->select("seller_pro_info", array("seller_id" => $login_seller_id));
$getProInfo = $qProInfo;
$cProInfo = $qProInfo->rowCount();

$formStatus = true;
$showPendingMsg = false;
$modificationMsg = '';
$proStatus = null;
if ($cProInfo > 0) {
    $proInfoData = [];
    while ($oProInfo = $qProInfo->fetch()) {
        $proInfoData[] = $oProInfo;
        $proStatus = $oProInfo->status; // 1=active, 0=pending,2=modification
        $modificationMsg = $oProInfo->feedback;
        // $formStatus = $proStatus == 2 ? true : false;
        if ($proStatus == 0) {
            $showPendingMsg = true;
            $formStatus = false;
        }
    }
}

$totalProInfForm = $cProInfo > 0 ? $cProInfo : 1;

$earliest_year = 1950;
$form_errors = Flash::render("form_errors");
$form_data = Flash::render("form_data");

?>
<h5>Add experience</h4>
    <?php

    if ($formStatus) : //Show Form if needs to
        if ($modificationMsg != '') {
    ?>
            <div class="alert alert-warning" role="alert">
                Modification Message From Admin:<br /><?= $modificationMsg ?>
            </div>
        <?php } ?>
        <?php if ($proStatus == 1) { ?>
            <div class="col-md-12 p-0">
                <div class="alert alert-success mb-0" role="alert">
                    Your Professional Info is active.
                </div>
            </div>
        <?php } ?>
        <form method="post" runat="server" autocomplete="off">
            <div class="form-group">
                <div class="width_of_add_experience_form_div">
                    <div class="width_of_add_experience_form">
                        <div id="clone-area">
                            <?php
                            for ($i = 0; $totalProInfForm > $i; $i++) :
                                $catId = $startDate = $endDate = '';
                                $cOptions = 0;
                                if (isset($proInfoData)) {
                                    $spiId = $proInfoData[$i]->id;
                                    $catId = $proInfoData[$i]->category_id;
                                    $subCatId = $proInfoData[$i]->sub_category_id;
                                    $stillWorking = $proInfoData[$i]->still_working;
                                    $desCription = $proInfoData[$i]->description;
                                    $startDate = $proInfoData[$i]->start_date;
                                    $endDate = $proInfoData[$i]->end_date;

                                    $qOptions = $db->select("professional_info", array("category_id" => $catId, "sub_category_id" => $subCatId,  "status" => 1));
                                    $cOptions = $qOptions->rowCount();
                                }
                            ?>
                                <div class="cloneArea border_clone_form_style">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3 pl-0">
                                            <label for="category_0">Job title</label>
                                            <input type="text" id="category_0" value="<?= $catId; ?>" class=" form-control input_add_experience_form category_change" name="occupation[<?= $i ?>][category_id]" required>

                                        </div>

                                        <div class="col-md-6 mb-3 pl-0" id="sub-category">
                                            <label for="sub-category_0">Organization</label>
                                            <input type="text" id="sub-category_0" value="<?= $subCatId; ?>" class=" form-control input_add_experience_form sub-category_change" name="occupation[<?= $i ?>][sub_category_id]" required>

                                        </div>

                                        <div class="col-md-6 mb-3 pl-0">
                                            <label class="control-label" for="startdate_0">From</label>
                                            <input type="date" id="startdate_0" value="<?= $startDate; ?>" class=" form-control input_add_experience_form" name="occupation[<?= $i ?>][start_date]" required>

                                        </div>

                                        <!-- <div class="col-md-6 mb-3 pl-0">
                                            <label class="control-label" for="enddate_0">To</label>
                                            <input type="date" id="enddate_0" value="<?= $endDate; ?>" class=" form-control input_add_experience_form w-100" name="occupation[<?= $i ?>][end_date]">
                                        </div>
                                      

                                        <div class="col-md-6 mb-3 pl-0 pt-3 pb-2">
                                            <input type="checkbox" id="still_working_<?= $i ?>"
                                                value="1"
                                                class="large-checkbox-icon"
                                                name="occupation[<?= $i ?>][still_working]"
                                                <?= $stillWorking ? 'checked' : '' ?>>
                                            <label for="still_working_<?= $i ?>" class="ml-3">Still working</label>
                                        </div> -->

                                        <div class="col-md-6 mb-3 pl-0">
                                            <label class="control-label" for="enddate_<?= $i ?>">To</label>
                                            <input type="date" id="enddate_<?= $i ?>" value="<?= $endDate; ?>" class="form-control input_add_experience_form w-100" name="occupation[<?= $i ?>][end_date]">
                                        </div>

                                        <div class="col-md-6 mb-3 pl-0 pt-3 pb-2">
                                            <input type="checkbox" id="still_working_<?= $i ?>"
                                                value="1"
                                                class="large-checkbox-icon"
                                                name="occupation[<?= $i ?>][still_working]"
                                                <?= $stillWorking ? 'checked' : '' ?>>
                                            <label for="still_working_<?= $i ?>" class="ml-3">Still working</label>
                                        </div>

                                        <div class="col-md-12 mb-3 pl-0">
                                            <label class="control-label" for=""> Description </label>
                                            <input type="text" id="" value="<?= $desCription; ?>" class=" form-control input_add_experience_form w-100" name="occupation[<?= $i ?>][description]" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <a href="javascript:void(0)" class="remove-item btn btn-sm btn-danger mr-2"><i class="fa fa-trash-o"></i></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 p-0">
                    <a class="btn btn-sm p-2 add-more-btn-style" id="add-more" href="javascript:;" role="button"><i class="fa fa-plus"></i><span class="text-dark"> Add more experience</span></a>
                    <hr />
                </div>
            </div>


            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Function to toggle the visibility of the 'To' date input and label
                    function toggleEndDate(checkbox) {
                        var index = checkbox.id.split('_').pop();
                        var toLabel = document.querySelector('label[for="enddate_' + index + '"]');
                        var toInput = document.querySelector('#enddate_' + index);

                        if (checkbox.checked) {
                            toLabel.style.display = 'none';
                            toInput.style.display = 'none';
                        } else {
                            toLabel.style.display = '';
                            toInput.style.display = '';
                        }
                    }

                    // Attach event listeners and trigger toggle on page load
                    document.querySelectorAll('.large-checkbox-icon').forEach(function(checkbox) {
                        checkbox.addEventListener('change', function() {
                            toggleEndDate(this);
                        });
                        toggleEndDate(checkbox); // Initialize on page load
                    });
                });
            </script>



            <style>
                #attribute_details_style {
                    border: 1px solid lightgray;
                    padding: 15px 10px;
                    border-radius: 5px;
                }

                #attribute_details_style:focus {
                    border: none !important;
                    box-shadow: 0px 0px 2px 3px lightblue;
                }

                #attribute_details_style:focus-visible {
                    outline: none;
                    /* This prevents any default focus outline */
                }

                #attribute_details_style::selection {
                    background-color: lightblue;
                    /* You can keep or modify this if needed */
                    color: white;
                    /* Modify this to change the text color within the selection */
                }


                .add-more-btn-style {
                    margin: 1px 0;
                    border-radius: 5px;
                    padding: 10px;
                    color: #007bff;
                    border: 1px solid grey;
                }

                .border_clone_form_style {
                    border: 1px solid lightgrey;
                    padding: 1rem 0rem 1rem 1.3rem;
                    margin: 1rem 0;
                    border-radius: 10px;
                }

                .large-checkbox-icon {
                    width: 17px;
                    height: 17px;
                    transform: scale(1.5);
                    -webkit-transform: scale(1.5);
                    transform-origin: 0 0;
                    cursor: pointer;
                }

                #custom_input_skill_add {
                    width: 100%;
                    padding: 6px;
                    border-radius: 3px;
                    border: 1px solid lightgrey;
                }

                #custom_input_skill_add:focus {
                    border: 1px solid lightblue;
                    /* Border style on focus */
                    outline: none;
                    /* Remove the default outline */
                }

                .width_of_add_experience_form {
                    width: 100%;
                    /* border:2px solid green; */
                    margin: auto;
                }

                .width_of_add_experience_form_div {
                    width: 100%;
                    display: flex;
                }

                .input_add_experience_form {
                    padding: 22px 10px;
                }

                .skill_input_padding_style {
                    padding: 10px;
                    height: 3.3rem;
                    margin: 10px 0;
                }

                .border_skill_details_table {
                    border: 1px solid lightgray;
                    padding: 10px 10px 0;
                    border-radius: 10px;
                    margin: 1rem 0;
                }
            </style>
            <div class="form-group row mt-5">
                <h5 class="col-md-12">Add skills</h5>
                <div class="col-md-12 px-3">
                    <div class="row mb-2">
                        <!-- category -->
                        <div class="col-md-6">
                            <select class="custom-select skill_input_padding_style" name="skill_cat_id" id="category_skills">
                                <option value="" class="hidden">
                                    <?= $lang['placeholder']['select_category']; ?>
                                </option>
                                <?php
                                $get_cats = $db->select("categories");
                                while ($row_cats = $get_cats->fetch()) {
                                    $cat_id = $row_cats->cat_id;
                                    $get_meta = $db->select("cats_meta", ["cat_id" => $cat_id, "language_id" => $siteLanguage]);
                                    $row_meta = $get_meta->fetch();
                                    $cat_title = $row_meta->cat_title;
                                ?>
                                    <option value="<?= $cat_id; ?>">
                                        <?= $cat_title; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <!-- sub category -->
                        <div class="col-md-6 display-sub-none" style="display: none;">
                            <select class="custom-select skill_input_padding_style" name="skill_child_id" id="skill-sub-category">
                                <option value="select-sub-category" selected> Select A Sub Category</option>
                                <?php
                                $get_c_cats = $db->select("categories_children");
                                while ($row_c_cats = $get_c_cats->fetch()) {
                                    $child_id = $row_c_cats->child_id;
                                    $child_parent_id = $row_c_cats->child_parent_id;
                                    $get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
                                    $row_meta = $get_meta->fetch();
                                    $child_title = $row_meta->child_title;
                                ?>
                                    <option class="sub-category-option" data-parent="<?= $child_parent_id; ?>" value="<?= $child_id; ?>">
                                        <?= $child_title; ?><?= $child_id; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <!-- sub-sub-category -->
                        <div class="col-md-6 display-sub-sub-none" style="display: none;">
                            <select class="custom-select skill_input_padding_style box-shadow-post-req" name="skill_sub_child_id" id="skil-sub-sub-category">
                                <option value="" class="hidden">
                                    <?= $lang['placeholder']['select_sub_sub_category']; ?>
                                </option>
                            </select>
                        </div>
                        <!-- skills -->

                        <div class="col-md-6 display-sub-skill-none" style="display: none;">
                            <select name="skill_id" id="custom_input_skill_add" class="custom-select skill_input_padding_style">
                                <option value="">select skill</option>
                            </select>
                        </div>

                        <!-- levels -->
                        <div class="col-md-6 display-sub-skill-none" style="display: none;">
                            <select class="custom-select skill_input_padding_style" name="skill_level">
                                <option value="" class="hidden"><?= $lang['label']['select_level']; ?></option>
                                <option>Beginner</option>
                                <option>Intermediate</option>
                                <option>Expert</option>
                            </select>
                        </div>
                    </div>
                    <?php

                    ?>
                    <div class="border_skill_details_table">
                        <h5 class="text-center">Selected skills details</h5>
                        <table class="table" id="tblSkills">

                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Category</th>
                                    <th scope="col">Sub category</th>
                                    <th scope="col">Attribute</th>
                                    <th scope="col">Skills</th>
                                    <th scope="col">Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $q_skills_relation = $db->select("skills_relation", array("seller_id" => $login_seller_id));
                                if ($q_skills_relation->rowCount() > 0) {
                                    $i = 0;
                                    while ($row_seller_skills = $q_skills_relation->fetch()) {
                                        $skill_cat_id = $row_seller_skills->skill_cat_id;
                                        $skill_child_id = $row_seller_skills->skill_child_id;
                                        $skill_sub_child_id = $row_seller_skills->skill_sub_child_id;
                                        $relation_id = $row_seller_skills->relation_id;
                                        $skill_id = $row_seller_skills->skill_id;
                                        $skill_level = $row_seller_skills->skill_level;

                                        $get_skill = $db->select("seller_skills", array("skill_id" => $skill_id));
                                        $row_skill = $get_skill->fetch();
                                        $skill_title = $row_skill->skill_title;

                                        $get_skill_category = $db->select("cats_meta", array("cat_id" => $skill_cat_id));
                                        $row_skill_category = $get_skill_category->fetch();
                                        $skill_category_details = $row_skill_category->cat_title;

                                        $get_skill_sub_category = $db->select("child_cats_meta", array("child_id" => $skill_child_id));
                                        $row_skill_sub_category = $get_skill_sub_category->fetch();
                                        $skill_sub_category_details = $row_skill_sub_category->child_title;

                                        $get_skill_attribute = $db->select("sub_subcategories", array("attr_id" => $skill_sub_child_id));
                                        $row_skill_attribute = $get_skill_attribute->fetch();
                                        $skill_sub_subcategory = $row_skill_attribute->sub_subcategory_name;

                                ?>
                                        <tr>
                                            <td><?= $skill_category_details; ?></td>
                                            <td><?= $skill_sub_category_details; ?></td>
                                            <td><?= $skill_sub_subcategory ?></td>
                                            <th scope="row"><?= $skill_title; ?><input type="hidden" name="skills[<?= $i ?>][id]" value="<?= $skill_id; ?>"></th>
                                            <td><?= $skill_level; ?><input type="hidden" name="skills[<?= $i ?>][level]" value="<?= $skill_level; ?>">

                                                <a href="javascript:;" onclick="deleteSkill(<?= $relation_id; ?>)" class="text-primary"><i class="fa fa-trash-o"></i></a>
                                            </td>

                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr class="table-danger">
                                        <th colspan="5" scope="row">0 Skill added, please use above form to addss your skillsets.</th>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col py-2 px-0 mb-2">
                        <a href="javascript:;" class="" style="border:2px solid grey; padding:10px 10px; border-radius:5px;" onclick="addSkill()"><i class="fa fa-plus"></i> <span style="color:black; background-color:white;"> Add more skills </span></a>
                    </div>
                    <small class="text-muted">Note: Please press "Save Changes" button to save the changes.</small>
                </div>
            </div>

            <hr>
            <div class="submit_professional_btndiv">
                <button type="submit" name="submit_professional" class="btn btn-success" style="padding:8px 10px; border-radius: 5px;">
                    <i class="fa fa-floppy-o"></i> <?= $lang['button']['save_changes']; ?>
                </button>
            </div>
            <input type="hidden" name="form_status" value="<?= $proStatus ?>">
        </form>
    <?php else : ?>
        <div class="row">
            <?php if ($showPendingMsg) { ?>
                <div class="col-md-12">
                    <div class="alert alert-warning" role="alert">
                        Your4 latest Professional Info update request is in pending state. After approval, this message will disappear.
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-3">
                <?= $lang['label']['occupation']; ?>
            </div>
            <div class="col-md-9">
                <?php if ($cProInfo > 0) {
                    foreach ($proInfoData as $proRow) {
                        // dump($proRow);
                        $proInfoId = $proRow->id;
                        $categoryId = $proRow->category_id;
                        $still_working = $proRow->still_working;
                        $description = $proRow->description;
                        $start_date = $proRow->start_date;
                        $end_date = $proRow->end_date;
                        $subCategoryId = $proRow->sub_category_id;

                ?>
                        <div class="d-flex align-items-start flex-column border border-info mb-1">
                            <div class="p-2">
                                <b><?= $categoryId; ?> | <?= $subCategoryId ?> | <?= $description ?></b> - <br> <small class="text-muted"><?= $start_date; ?> | <?= $end_date ?></small>
                            </div>

                        </div>
                <?php
                    }
                } //$cProInfo
                ?>
            </div>
            <div class="col-md-12">
                <hr />
            </div>
            <div class="col-md-3">
                <?= $lang['label']['skills']; ?>
            </div>
            <div class="col-md-9">
                <table class="table table-striped" id="tblSkills">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Category</th>
                            <th scope="col">Sub category</th>
                            <th scope="col">Attribute</th>
                            <th scope="col">Skills</th>
                            <th scope="col">Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q_skills_relation = $db->select("skills_relation", array("seller_id" => $login_seller_id));
                        if ($q_skills_relation->rowCount() > 0) {
                            $i = 0;
                            while ($row_seller_skills = $q_skills_relation->fetch()) {

                                $relation_id = $row_seller_skills->relation_id;
                                $skill_id = $row_seller_skills->skill_id;
                                $skill_level = $row_seller_skills->skill_level;
                                $skill_cat_id = $row_seller_skills->skill_cat_id;
                                $skill_child_id = $row_seller_skills->skill_child_id;
                                $skill_sub_child_id = $row_seller_skills->skill_sub_child_id;

                                $get_skill = $db->select("seller_skills", array("skill_id" => $skill_id));
                                $row_skill = $get_skill->fetch();
                                $skill_title = $row_skill->skill_title;

                                $getskillcat = $db->select("cats_meta", ["cat_id" => $skill_cat_id]);
                                $rowskillcat = $getskillcat->fetch();
                                $cat_title = $rowskillcat->cat_title;

                                $getskillchild = $db->select("child_cats_meta", ["child_id" => $skill_child_id]);
                                $rowskillchild = $getskillchild->fetch();
                                $child_title = $rowskillchild->child_title;

                                $getskillsubchild = $db->select("sub_subcategories", ["attr_id" => $skill_sub_child_id]);
                                $rowskillsubchild = $getskillsubchild->fetch();
                                $sub_subcategory_name = $rowskillsubchild->sub_subcategory_name;

                        ?>
                                <tr>
                                    <th scope="row"><?= $cat_title; ?></th>
                                    <th scope="row"><?= $child_title; ?></th>
                                    <th scope="row"><?= $sub_subcategory_name; ?></th>
                                    <th scope="row"><?= $skill_title; ?></th>
                                    <td><?= $skill_level; ?></td>
                                </tr>
                            <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr class="table-danger">
                                <th colspan="5" scope="row">0 Skill added, please use above form to adds your skillsets.</th>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
    <!--  -->

    <style>
        .submit_professional_btndiv {
            width: 100%;
            display: flex;
            justify-content: end;
        }

        .img-thumbnail {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin: 5px;
            position: relative;
        }

        .remove-img {
            cursor: pointer;
            color: red;
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 20px;
            background: white;
            border-radius: 50%;
            padding: 2px;
        }

        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
        }

        .limit-message {
            color: red;
        }

        .tag {
            background-color: green;
            color: white;
            padding: 9px 10px;
            margin: 5px 5px 5px 0;
            line-height: 3rem;
            font-size: 13px;
            border-radius: 5px;
        }

        .portfolio-from-div {
            width: 100%;
            display: flex;
        }

        .portfolio-first {
            width: 46%;
            margin: 0 auto;
        }

        .portfolio-second {
            width: 46%;
            margin: 0 auto;
        }

        #projectTitle {
            padding: 1.7rem 1rem;
            /* margin-bottom: 1.5rem; */
            border: 1px solid lightgray;
            border-radius: 5px;
        }

        #portfolio-description {
            padding: 1rem;
            /* margin-bottom: 1.5rem; */
            border: 1px solid lightgray;
            border-radius: 5px;
        }

        #projectskills {
            padding: 1.7rem 1rem;
            /* margin-bottom: 1.5rem; */
            border: 1px solid lightgray;
            border-radius: 5px;
        }

        #imageUpload {
            padding: 0.9rem;
            /* margin-bottom: 1.5rem; */
            border: 1px solid lightgray;
            border-radius: 5px;
        }

        #videoUrl {
            padding: 1.7rem 1rem;
            /* margin-bottom: 1.5rem; */
            border: 1px solid lightgray;
            border-radius: 5px;
        }

        .portfolio-submit-btn {
            margin: 0 auto 1rem;
            /* width: 25%; */
            padding: 8px 20px;
            font-size: 16px !important;
            background-color: #00cedc !important;
            /* color: black !important; */
            border: none;
            border-radius: 5px;
        }


        #projectTitle::placeholder {
            color: lightgrey;
        }

        #projectskills::placeholder {
            color: lightgrey;
        }

        #portfolio-description::placeholder {
            color: lightgrey;
        }

        #videoUrl::placeholder {
            color: lightgrey;
        }

        #imageUpload::placeholder {
            color: lightgrey;
        }

        .media-item {
            padding: 5px 10px;
            background-color: lightgray;
            border-radius: 5px;
            margin: 10px 0;
        }

        .description_portfolio_div {
            width: 96%;
            margin: auto;
        }

        .description_portfolio_divinner {
            width: 100%;
        }

        #referenced_url {
            padding: 1.7rem 1rem;
            /* margin-bottom: 1.5rem; */
            border: 1px solid lightgray;
            border-radius: 5px;
        }

        #attribute_details {
            padding: 1.7rem 1rem;
            /* margin-bottom: 1.5rem; */
            border: 1px solid lightgray;
            border-radius: 5px;
        }

        .margin_top_for_sararation {
            border: 1px solid lightgrey;
            padding: 0;
            border-radius: 10px;
        }
    </style>

    <?php
    session_start();

    // Define maximum limits
    $maxTags = 5;
    $maxImages = 3;
    $maxVideos = 3;

    // Function to generate a unique filename
    function generateUniqueFilename($extension)
    {
        return uniqid('img_', true) . '.' . $extension;
    }

    // Initialize session array for uploaded images if not already set
    if (!isset($_SESSION['uploadedImages'])) {
        $_SESSION['uploadedImages'] = [];
    }

    // Check if the form is submitted
    if (isset($_POST['portfolio_form'])) {

        $projectTitle = $_POST['projectTitle'];
        $referenced_url = $_POST['referenced_url'];
        $skills = explode(',', $_POST['projectskills']); // assuming skills are passed as a comma-separated string
        $description = $_POST['portfolio-description'];
        $videoUrls = explode(',', $_POST['videoUrls']); // assuming video URLs are passed as a comma-separated string
        $seller_id = $login_seller_id;

        // Handle file uploads
        if (isset($_FILES['images']) && count($_FILES['images']['name']) > 0) {
            for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                $targetDir = "portfolio/";
                $imageFileType = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
                $uniqueFilename = generateUniqueFilename($imageFileType);
                $targetFile = $targetDir . $uniqueFilename;
                $check = getimagesize($_FILES['images']['tmp_name'][$i]);

                // Debugging Step: Print each file being processed
                echo "Processing file: " . $_FILES['images']['name'][$i] . "<br>";

                // Check if image file is an actual image or fake image
                if ($check !== false) {
                    // Move uploaded file to target directory
                    if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $targetFile)) {
                        $_SESSION['uploadedImages'][] = $uniqueFilename;
                    } else {
                        echo "There was an error uploading image: " . $_FILES['images']['name'][$i];
                    }
                } else {
                    echo "File is not an image: " . $_FILES['images']['name'][$i];
                }
            }
        }

        // Debugging to check the session uploaded images array
        // echo "<pre>";
        // print_r($_SESSION['uploadedImages']);
        // print_r($videoUrls);
        // echo "</pre>";


        // Store data in database
        $portfolio = $db->insert("portfolios", array("project_title" => $projectTitle, "referenced_url" => $referenced_url, "description" => $description, "seller_id" => $seller_id));

        // Get the last inserted portfolio ID
        $portfolio_id = $db->lastInsertId();

        // Insert skills
        foreach ($skills as $skill) {
            $portfolio_skills = $db->insert("portfolio_skills", array("portfolio_id" => $portfolio_id, "skill" => $skill));
            if (!$portfolio_skills) {
                echo "There Is Error In : " . $db->error;
            }
        }

        // Insert images
        foreach ($_SESSION['uploadedImages'] as $image) {
            $portfolio_images = $db->insert("portfolio_images", array("portfolio_id" => $portfolio_id, "image_path" => $image));
            if (!$portfolio_images) {
                echo "There Is Error In : " . $db->error;
            }
        }

        // Insert videos
        foreach ($videoUrls as $videoUrl) {
            $portfolio_videos = $db->insert("portfolio_videos", array("portfolio_id" => $portfolio_id, "video_url" => $videoUrl));
            if (!$portfolio_videos) {
                echo "There Is Error In : " . $db->error;
            }
        }

        // Clear session images after successful portfolio insert
        if ($portfolio) {
            $_SESSION['uploadedImages'] = [];
            echo "Portfolio insert success";
        } else {
            echo "Portfolio insert decline";
        }
    }
    ?>
    <hr>
    <?php
    $q_skills_relation = $db->select("skills_relation", array("seller_id" => $login_seller_id));

    if ($q_skills_relation->rowCount() > 0) { ?>

        <div class="container margin_top_for_sararation">
            <h4 class="text-center my-4"><u>Add Portfolio</u></h4>
            <form id="portfolioForm" method="post" enctype="multipart/form-data">
                <div class="portfolio-from-div">
                    <div class="portfolio-first">
                        <label for="projectTitle" class="col-md-12 pl-0">Project Title</label><br>
                        <input type="text" class="form-control col-md-12" id="projectTitle" name="projectTitle" placeholder="Enter your project title..." required>
                        <br>
                        <label for="projectskills" class="col-md-12 pl-0">Tags</label>
                        <input type="text" class="form-control col-md-12" id="projectskills" name="projectskills" placeholder="Enter skills and press Enter">
                        <div id="skillsContainer" class="mt-2 mb-2">
                            <!-- Tags will be appended here -->
                        </div>
                        <small id="tagLimitMessage" class="limit-message"></small>

                    </div>
                    <div class="portfolio-second">
                        <label for="referenced_url" class="col-md-12 pl-0">Reference Link</label>
                        <input type="url" class="form-control col-md-12" name="referenced_url" id="referenced_url" placeholder="Enter your project referenc url">
                        <br>
                        <label for="attribute_details" class="col-md-12 pl-0">Attribute</label>
                        <select name="attribute_details" id="attribute_details_style" class="col-md-12 text-dark">
                            <option value="" selected>Select an attribute</option>
                            <?php
                            $q_skills_relation = $db->select("skills_relation", array("seller_id" => $login_seller_id));

                            if ($q_skills_relation->rowCount() > 0) {
                                $i = 0;
                                $seen_attr_ids = array(); // Array to store unique attr_id values

                                while ($row_seller_skills = $q_skills_relation->fetch()) {

                                    $relation_id = $row_seller_skills->relation_id;
                                    $skill_id = $row_seller_skills->skill_id;
                                    $skill_level = $row_seller_skills->skill_level;
                                    $skill_cat_id = $row_seller_skills->skill_cat_id;
                                    $skill_child_id = $row_seller_skills->skill_child_id;
                                    $skill_sub_child_id = $row_seller_skills->skill_sub_child_id;

                                    $getskillsubchild = $db->select("sub_subcategories", ["attr_id" => $skill_sub_child_id]);
                                    $rowskillsubchild = $getskillsubchild->fetch();
                                    $attr_id = $rowskillsubchild->attr_id;
                                    $sub_subcategory_name = $rowskillsubchild->sub_subcategory_name;

                                    if (!in_array($attr_id, $seen_attr_ids)) {
                                        // If attr_id is not already in the array, add it to the dropdown
                                        $seen_attr_ids[] = $attr_id;
                            ?>
                                        <option class="" value="<?= $attr_id; ?>">
                                            <?= $sub_subcategory_name; ?>
                                        </option>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </select>

                    </div>
                </div>
                <div class="description_portfolio_div">
                    <div class="description_portfolio_divinner">
                        <br>
                        <label for="portfolio-description" class="col-md-12 pl-0">Description</label><br>
                        <textarea class="form-control col-md-12" id="portfolio-description" name="portfolio-description" placeholder="Enter your project description..." rows="3" required></textarea>
                        <br>
                    </div>
                </div>
                <div class="portfolio-from-div">
                    <div class="portfolio-first">
                        <label for="images" class="col-md-12 pl-0">Upload Images (Max 3)</label>
                        <input type="file" class="form-control-file col-md-12" id="imageUpload" name="images[]" accept="image/*" multiple>
                        <div id="imagePreview" class="image-preview-container mt-2">
                            <!-- Image previews will be appended here -->
                        </div>
                        <small id="imageLimitMessage" class="limit-message"></small> <br>
                    </div>
                    <div class="portfolio-second">
                        <label for="videos" class="col-md-12 pl-0">Add Video URLs (Max 3)</label>
                        <input type="url" class="form-control col-md-12" name="videoUrls" id="videoUrl" placeholder="Enter video URL and press Enter">
                        <div id="videoList" class="mt-2">
                            <!-- Video URLs will be appended here -->
                        </div>
                        <small id="videoLimitMessage" class="limit-message"></small>
                    </div>
                </div>
                <div class="w-100 d-flex mt-0">
                    <button type="submit" class="btn btn-primary portfolio-submit-btn" name="portfolio_form"><i class="fa fa-floppy-o"></i> Submit</button>
                </div>
            </form>
        </div>
    <?php } else { ?>

        <h5>Portfolio</h4>
            <p class="table-danger p-2">For making your portfolio choose skills</p>

        <?php } ?>
        <script>
            const maxTags = 5; // Maximum number of tags
            const maxImages = 3; // Maximum number of images
            const maxVideos = 3; // Maximum number of videos

            // Handle tag input
            document.getElementById('projectskills').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const input = e.target;
                    const value = input.value.trim();
                    const skillsContainer = document.getElementById('skillsContainer');
                    const tagLimitMessage = document.getElementById('tagLimitMessage');
                    const currentTagCount = skillsContainer.getElementsByClassName('tag').length;

                    if (value) {
                        if (currentTagCount < maxTags) {
                            const tag = document.createElement('span');
                            tag.className = 'tag';
                            tag.textContent = value;
                            skillsContainer.appendChild(tag);
                            input.value = '';
                            tagLimitMessage.textContent = '';
                        } else {
                            tagLimitMessage.textContent = `You can only add up to ${maxTags} tags.`;
                        }
                    }
                }
            });

            // Handle image upload
            document.getElementById('imageUpload').addEventListener('change', function(e) {
                const files = e.target.files;
                const imagePreview = document.getElementById('imagePreview');
                const imageLimitMessage = document.getElementById('imageLimitMessage');
                const existingImages = imagePreview.getElementsByClassName('img-thumbnail').length;

                if (files.length + existingImages > maxImages) {
                    imageLimitMessage.textContent = `You can only upload up to ${maxImages} images.`;
                    return;
                }

                imageLimitMessage.textContent = ''; // Clear any previous error messages
                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const imgContainer = document.createElement('div');
                        imgContainer.className = 'position-relative';

                        const img = document.createElement('img');
                        img.className = 'img-thumbnail';
                        img.file = file;
                        imgContainer.appendChild(img);

                        const removeBtn = document.createElement('span');
                        removeBtn.className = 'remove-img';
                        removeBtn.textContent = '×';
                        removeBtn.onclick = function() {
                            imgContainer.remove();
                            const fileInput = document.getElementById('imageUpload');
                            const dataTransfer = new DataTransfer();
                            Array.from(fileInput.files).forEach(f => {
                                if (f !== file) dataTransfer.items.add(f);
                            });
                            fileInput.files = dataTransfer.files;
                        };
                        imgContainer.appendChild(removeBtn);

                        imagePreview.appendChild(imgContainer);

                        const reader = new FileReader();
                        reader.onload = (function(aImg) {
                            return function(e) {
                                aImg.src = e.target.result;
                            };
                        })(img);
                        reader.readAsDataURL(file);
                    }
                });
            });

            // Handle video URL input
            document.getElementById('videoUrl').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const value = e.target.value.trim();
                    const videoList = document.getElementById('videoList');
                    const videoLimitMessage = document.getElementById('videoLimitMessage');
                    const currentVideoCount = videoList.getElementsByClassName('media-item').length;

                    if (value) {
                        if (currentVideoCount < maxVideos) {
                            const videoItem = document.createElement('div');
                            videoItem.className = 'media-item';
                            videoItem.textContent = value;
                            videoList.appendChild(videoItem);
                            e.target.value = '';
                            videoLimitMessage.textContent = '';
                        } else {
                            videoLimitMessage.textContent = `You can only add up to ${maxVideos} videos.`;
                        }
                    }
                }
            });

            document.getElementById('portfolioForm').addEventListener('submit', function(e) {
                // Serialize skills
                const skillsContainer = document.getElementById('skillsContainer');
                const tags = skillsContainer.getElementsByClassName('tag');
                let skillValues = [];
                for (let i = 0; i < tags.length; i++) {
                    skillValues.push(tags[i].textContent);
                }
                const skillsInput = document.createElement('input');
                skillsInput.type = 'hidden';
                skillsInput.name = 'projectskills';
                skillsInput.value = skillValues.join(',');
                this.appendChild(skillsInput);

                // Serialize video URLs
                const videoList = document.getElementById('videoList');
                const videos = videoList.getElementsByClassName('media-item');
                let videoValues = [];
                for (let i = 0; i < videos.length; i++) {
                    videoValues.push(videos[i].textContent);
                }
                const videosInput = document.createElement('input');
                videosInput.type = 'hidden';
                videosInput.name = 'videoUrls';
                videosInput.value = videoValues.join(',');
                this.appendChild(videosInput);
            });
        </script>
        <!--  -->
        <!-- skills -->














        <script src="<?= $site_url ?>/js/cloneData.js" type="text/javascript"></script>
        <script>
            var totalSkillsAvailable = <?= $skills ?>;
            $('a#add-more').cloneData({
                mainContainerId: 'clone-area', // Main container Should be ID
                cloneContainer: 'cloneArea', // Which you want to clone
                removeButtonClass: 'remove-item', // Remove button for remove cloned HTML
                removeConfirm: true, // default true confirm before delete clone item
                removeConfirmMessage: 'Are you sure want to delete?', // confirm delete message
                append: '<a href="javascript:void(0)" class="remove-item btn btn-sm btn-danger remove-social-media">Remove</a>', // Set extra HTML append to clone HTML
                minLimit: 1, // Default 1 set minimum clone HTML required
                maxLimit: 5, // Default unlimited or set maximum limit of clone HTML
                defaultRender: 1,
                excludeHTML: ".emptIt",
                init: function() {
                    // Initialize Plugin
                },
                beforeRender: function() {
                    // Before rendered callback called
                },
                afterRender: function() {
                    // After rendered callback called
                    $(".emptIt:last").addClass('d-none');

                    // Update IDs for the newly cloned elements
                    $('#clone-area .cloneArea').each(function(index) {
                        $(this).find('[id^=category_]').attr('id', 'category_' + index);
                        $(this).find('[id^=sub-category_]').attr('id', 'sub-category_' + index);
                        $(this).find('[id^=professional-info_]').attr('id', 'professional-info_' + index);
                    });
                },
                afterRemove: function() {
                    // After remove callback called
                },
                beforeRemove: function() {
                    // Before remove callback called
                }
            });

            $(document).on('change', 'select.category_change', function() {
                var categoryId = $(this).val();
                var selectId = this.id.match(/\d+/); // Extract number from ID
                var optionSelected = $(this).find("option:selected");
                var textSelected = optionSelected.text();

                $.ajax({
                    url: '<?= $site_url ?>/ajax/get-data',
                    dataType: 'json',
                    method: "POST",
                    data: {
                        categoryId: categoryId,
                        action: "get-category-option"
                    },
                    beforeSend: function(jqXHR) {
                        $("#responseArea_" + selectId).removeClass("d-none").addClass("d-block");
                        $("#responseArea_" + selectId + " span#categoryName").text(textSelected);
                    },
                    success: function(response) {
                        // Update sub-category dropdown options
                        $('#sub-category_' + selectId).html(response.subCategories);
                    }
                });
            });

            $(document).on('change', 'select.sub-category_change', function() {
                var subCategoryId = $(this).val();
                var selectId = this.id.match(/\d+/); // Extract number from ID

                $.ajax({
                    url: '<?= $site_url ?>/ajax/get-sub-category-data',
                    dataType: 'json',
                    method: "POST",
                    data: {
                        subCategoryId: subCategoryId,
                        action: "get-sub-category-option"
                    },
                    success: function(response) {
                        // Handle response if needed
                    }
                });
            });




            function deRequire(index) {
                var requiredCheckboxes = $("#responseArea_" + index + " .row :checkbox[required]");

                requiredCheckboxes.change(function() {
                    if (requiredCheckboxes.is(':checked')) {
                        requiredCheckboxes.removeAttr('required');
                    } else {
                        requiredCheckboxes.attr('required', 'required');
                    }
                });
            }

            function addSkill() {
                var skillSelectedId = $('select[name=skill_id]').find(":selected").val();
                var skillSelectedValue = $('select[name=skill_id]').find(":selected").text();
                var levelSelectedId = $('select[name=skill_level]').find(":selected").val();
                var levelSelectedValue = $('select[name=skill_level]').find(":selected").text();

                var selectedSkillCatId = $('select[name=skill_cat_id]').find(":selected").val();
                var selectedSkillCatValue = $('select[name=skill_cat_id]').find(":selected").text();
                var selectedSkillChildId = $('select[name=skill_child_id]').find(":selected").val();
                var selectedSkillChildValue = $('select[name=skill_child_id]').find(":selected").text();
                var selectedSkillSubChildId = $('select[name=skill_sub_child_id]').find(":selected").val();
                var selectedSkillSubChildValue = $('select[name=skill_sub_child_id]').find(":selected").text();

                // alert(optionSelectedId)
                // if (skillSelectedId === '') {
                //     alert("Please select skill");
                //     $('select[name=skill_id]').focus();
                //     return false
                // }

                if (levelSelectedId === '') {
                    alert("Please select skill level");
                    $('select[name=skill_level]').focus();
                    return false
                }

                $(".table-danger").remove();
                var trCount = $('#tblSkills tbody tr').length;
                var trNewCount = $('#tblSkills tbody tr.new').length;
                // alert(trNewCount + " " + totalSkillsAvailable)
                if (trNewCount === totalSkillsAvailable) {
                    alert("You have exceeded the available skills number.")
                    return false;
                }
                var index = trCount;
                // if (trCount > 1)
                // index = trCount - 1;
                var buffer = '<tr class="new">';
                buffer += '<th scope="row">' + selectedSkillCatValue + '<input type="hidden" name="skills[' + index + '][id]" value="' + selectedSkillCatId + '"></th>';
                buffer += '<th scope="row">' + selectedSkillChildValue + '<input type="hidden" name="skills[' + index + '][id]" value="' + selectedSkillChildId + '"></th>';
                buffer += '<th scope="row">' + selectedSkillSubChildValue + '<input type="hidden" name="skills[' + index + '][id]" value="' + selectedSkillSubChildId + '"></th>';
                buffer += '<th scope="row">' + skillSelectedValue + '<input type="hidden" name="skills[' + index + '][id]" value="' + skillSelectedId + '"></th>';
                buffer += '<td>' + levelSelectedValue + '<input type="hidden" name="skills[' + index + '][level]" value="' + levelSelectedValue + '"> <a href="javascript:;" onclick="deleteThis(this, null)" class="text-danger"><i class="fa fa-trash-o"></i></a></td>'
                buffer += '</tr>';
                $('#tblSkills tbody').append(buffer);

                $("select[name=skill_id] :selected").remove();
                $('select[name=skill_id]').prop('selectedIndex', 0);
                $('select[name=skill_level]').prop('selectedIndex', 0);
                return false;
            }

            function deleteThis(btn, id) {
                if (confirm("Are you sure want to delete this?")) {
                    if (id != null) {
                        $('body #wait').addClass("loader");

                        $.ajax({
                            url: "<?= $site_url ?>/ajax/remove-data",
                            dataType: "json",
                            method: "POST",
                            data: {
                                id,
                                action: 'skills',
                            }
                        }).done(function(data) {
                            $('body #wait').removeClass("loader");
                            location.reload();
                        });
                    }
                    var row = btn.parentNode.parentNode;
                    row.parentNode.removeChild(row);
                }
            }
        </script>



        <script>
            $(document).ready(function() {
                // Handle category change to fetch sub-categories
                $(document).on('change', '[id^=category_]', function() {
                    var categoryId = $(this).val();
                    var elementId = $(this).attr('id');
                    var idNumber = elementId.split('_')[1];

                    $.ajax({
                        url: 'get_sub_categories', // Path to your PHP script
                        type: 'POST',
                        data: {
                            cat_id: categoryId
                        },
                        success: function(response) {
                            $('#sub-category_' + idNumber).html(response);
                        },
                        error: function() {
                            alert('Failed to fetch sub-categories.');
                        }
                    });
                });




                // Handle sub-category change to fetch professional info
                $(document).on('change', '[id^=sub-category_]', function() {
                    var subCatId = $(this).val();
                    var elementId = $(this).attr('id');
                    var idNumber = elementId.split('_')[1];
                    var isEdit = $('#edit_mode_' + idNumber).val(); // Check if it's in edit mode

                    $.ajax({
                        url: 'get_professional_info', // Update with the path to your new script
                        type: 'POST',
                        data: {
                            sub_cat_id: subCatId,
                            is_edit: isEdit // Pass edit mode flag
                        },
                        success: function(response) {
                            $('#professional-info_' + idNumber).html(response);
                        },
                        error: function() {
                            alert('Failed to fetch professional info.');
                        }
                    });
                });
            });
        </script>


        <!-- sub category -->
        <script>
            function deleteSkill(relation_id) {
                if (confirm("Are you sure you want to delete this skill?")) {
                    // $('body #wait').addClass("loader"); // Show loader or indication

                    $.ajax({
                        url: "<?= $site_url ?>/ajax/delete-skill", // Path to your delete script
                        dataType: "json",
                        method: "POST",
                        data: {
                            id: relation_id, // Pass relation_id to identify which skill to delete
                            action: 'skills' // Action identifier, modify as needed
                        },
                        success: function(data) {
                            // $('body #wait').removeClass("loader"); // Remove loader or indication

                            if (data.result) {
                                // Skill deleted successfully, you can optionally update UI or reload the page
                                alert("Skill deleted successfully.");
                                location.reload(); // Reload the page or update UI as needed
                            } else {
                                alert("Failed to delete skill. Please try again.");
                            }
                        },
                        error: function() {
                            alert("Error occurred while deleting skill. Please try again.");
                        }
                    });
                }
            }
        </script>

        <!-- skill section -->
        <script>
            $(document).ready(function() {
                $('#category_skills').change(function() {
                    var skill_cat_id = $(this).val();
                    $('.display-sub-none').hide();
                    $('.display-sub-sub-none').hide();
                    $('.display-sub-skill-none').hide();

                    $.ajax({
                        url: 'skill_sub_categories',
                        method: 'POST',
                        data: {
                            skill_cat_id: skill_cat_id
                        },
                        success: function(response) {
                            $('.display-sub-none').show();
                            $('.display-sub-sub-none').hide();
                            $('.display-sub-skill-none').hide();
                            $('#skill-sub-category').html(response);
                            $('#skil-sub-sub-category').html('<option value="" class="hidden"><?= $lang['placeholder']['select_sub_sub_category']; ?></option>');

                        }
                    });
                });

                $('#skill-sub-category').change(function() {
                    var skill_child_id = $(this).val();
                    $.ajax({
                        url: 'skill_sub_subcategories',
                        method: 'POST',
                        data: {
                            skill_child_id: skill_child_id
                        },
                        success: function(response) {
                            $('.display-sub-sub-none').show();
                            $('.display-sub-skill-none').hide();
                            $('#skil-sub-sub-category').html(response);
                        }
                    });
                });

                $('#skil-sub-sub-category').change(function() {
                    var skill_sub_child_id = $(this).val();
                    $.ajax({
                        url: 'custom_input_skill_add',
                        method: 'POST',
                        data: {
                            skill_sub_child_id: skill_sub_child_id
                        },
                        success: function(response) {
                            $('.display-sub-skill-none').show();
                            $('#custom_input_skill_add').html(response);
                        }
                    })
                });
            });
        </script>
<?php error_reporting(0); ?>

<style>
    .copy_proposal_icon {
        display: block !important;
    }
</style>
<div class="card user-sidebar rounded-0 mb-4 ">
    <!--- card user-sidebar rounded-0 Starts -->
    <h4 class="pl-3 mx-1 my-3">Experience</h4>
    <?php
    $seller_experience = $db->select("seller_pro_info", array("seller_id" => $login_seller_id));

    echo '<div class="experience-section">';

    while ($fetch_data_experience = $seller_experience->fetch()) {
        $category_id = $fetch_data_experience->category_id;
        $sub_category_id = $fetch_data_experience->sub_category_id;
        $still_working = $fetch_data_experience->still_working ? 'Yes' : 'No';
        $description = $fetch_data_experience->description;
        $start_date = date('F Y', strtotime($fetch_data_experience->start_date));
        $end_date = $fetch_data_experience->still_working ? 'Present' : date('F Y', strtotime($fetch_data_experience->end_date));

        echo '
    <div class="experience-item">
        <div class="experience-header">
            <span class="experience-category">Job Title: ' . $category_id . '</span>          
        </div>
        <div class="experience-details">
            <p><strong>Organization:</strong> ' . $sub_category_id . '</p>
            <p><strong>Description:</strong> ' . $description . '</p>
            <p><strong>Duration:</strong> ' . $start_date . ' - ' . $end_date . '</p>
            <p><strong>Still Working:</strong> ' . $still_working . '</p>
        </div>
    </div>';
    }

    echo '</div>';
    ?>

    <style>
        .experience-section {
            width: 95%;
            margin: 0px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .experience-item {
            /* margin-bottom: 20px; */
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .experience-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .experience-category,
        .experience-sub-category {
            font-weight: bold;
            color: #333;
        }

        .experience-details p {
            margin: 5px 0;
            color: #555;
        }

        .experience-details strong {
            color: #222;
        }
    </style>

    <!-- 
    <hr class="card-hr"> -->


    

    <div class="card-body box-shadow-6">
    <hr>
        <!-- card-body Starts -->

        <h4><?= $lang['user_profile']['description']; ?></h4>
        <p><?= $seller_about; ?></p>

        <hr class="card-hr">
        <h4 class="float-left pt-1"><?= $lang['user_profile']['languages']; ?></h4>

        <?php if (isset($_SESSION['seller_user_name'])) { ?>

            <?php if ($login_seller_user_name == $seller_user_name) { ?>

                <ul class="list-unstyled">
                    <!-- list-unstyled Starts -->

                    <li class="mb-4 clearfix">

                        <button data-toggle="collapse" data-target="#language" class="btn btn-success float-right">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i> <?= $lang['button']['add_new']; ?>
                        </button>

                    </li>

                    <div id="language" class="collapse form-style mb-2">
                        <!-- language collapse form-style mb-2 Starts -->

                        <form method="post">
                            <!-- form Starts -->

                            <div class="form-group">
                                <!-- form-group Starts -->

                                <select class="form-control" name="language_id" required>

                                    <option value=""><?= $lang['label']['select_language']; ?></option>

                                    <?php

                                    $s_langs = array();

                                    $get = $db->select("languages_relation", array("seller_id" => $login_seller_id));

                                    while ($row = $get->fetch()) {

                                        array_push($s_langs, $row->language_id);
                                    }

                                    $s_langs = implode(",", $s_langs);

                                    if (!empty($s_langs)) {
                                        $query_where = "where not language_id IN ($s_langs)";
                                    } else {
                                        $query_where = "";
                                    }

                                    $get_languages = $db->query("select * from seller_languages $query_where");

                                    while ($row_languages = $get_languages->fetch()) {

                                        $language_id = $row_languages->language_id;
                                        $language_title = $row_languages->language_title;

                                    ?>
                                        <option value="<?= $language_id; ?>"> <?= $language_title; ?> </option>
                                    <?php } ?>

                                    <option value="custom">Custom Language</option>

                                </select>

                            </div><!-- form-group Ends -->

                            <div class="form-group language-title d-none">
                                <!-- form-group Starts -->

                                <input type="text" placeholder="Language Name" class="form-control" name="language_title">

                            </div><!-- form-group Ends -->

                            <div class="form-group">
                                <!-- form-group Starts -->

                                <select class="form-control" name="language_level" required>

                                    <option value="" class="hidden"><?= $lang['label']['select_level']; ?></option>
                                    <option value="basic"> Basic</option>
                                    <option value="Fluent"> Fluent</option>
                                    <option value="Conversational"> Conversational</option>
                                    <option value="Native or Bilingual"> Native or Bilingual</option>

                                </select>

                            </div><!-- form-group Ends -->

                            <div class="text-center">
                                <!-- text-center Starts -->

                                <button type="button" data-toggle="collapse" data-target="#language" class="btn btn-secondary">
                                    <?= $lang['button']['cancel']; ?>
                                </button>

                                <button type="submit" name="insert_language" class="btn btn-success">
                                    <?= $lang['button']['add']; ?>
                                </button>

                            </div><!-- text-center Ends -->

                        </form><!-- form Ends -->

                        <?php

                        if (isset($_POST['insert_language'])) {

                            $language_id = $input->post('language_id');
                            $language_level = $input->post('language_level');


                            if ($language_id == "custom") {

                                $language_title = $input->post('language_title');

                                $count = $db->count("seller_languages", ["language_title" => $language_title]);

                                if ($count == 1) {

                                    echo "<script>alert('{$lang['alert']['language_already_added']}');</script>";
                                    echo "<script>window.open('$seller_user_name','_self');</script>";

                                    exit();
                                } else {

                                    $insert_language = $db->insert("seller_languages", ["language_title" => $language_title]);
                                    $language_id = $db->lastInsertId();
                                }
                            }

                            $insert_language = $db->insert("languages_relation", array("seller_id" => $seller_id, "language_id" => $language_id, "language_level" => $language_level));
                            echo "<script>window.open('$seller_user_name','_self');</script>";
                        }

                        ?>

                    </div><!-- language collapse form-style mb-2 Ends -->

                </ul><!-- list-unstyled Ends -->

            <?php } ?>

        <?php } ?>

        <div class="clearfix"></div>

        <ul class="list-unstyled mt-3">
            <!-- list-unstyled mt-3 Starts -->

            <?php

            $select_languages_relation = $db->select("languages_relation", array("seller_id" => $seller_id));

            while ($row_languages_relation = $select_languages_relation->fetch()) {

                $relation_id = $row_languages_relation->relation_id;
                $language_id = $row_languages_relation->language_id;
                $language_level = $row_languages_relation->language_level;


                $get_language = $db->select("seller_languages", array("language_id" => $language_id));
                $row_language = $get_language->fetch();
                $language_title = $row_language->language_title;

            ?>

                <li class="card-li mb-1">
                    <!--- card-li mb-1 Starts -->

                    <?= $language_title; ?> - <span class="text-muted"> <?= $language_level; ?> </span>

                    <?php if (isset($_SESSION['seller_user_name'])) { ?>

                        <?php if ($login_seller_user_name == $seller_user_name) { ?>

                            <a href="user.php?delete_language=<?= $relation_id; ?>">
                                <i class="fa fa-trash-o"></i>
                            </a>

                        <?php } ?>

                    <?php } ?>

                </li>
                <!--- card-li mb-1 Ends -->

            <?php } ?>

        </ul><!-- list-unstyled mt-3 Ends -->

        <hr class="card-hr">

        <h4 class="float-left pt-1"><?= $lang['user_profile']['skills']; ?></h4>

        <?php if (isset($_SESSION['seller_user_name'])) { ?>

            <?php
            if ($login_seller_user_name == $seller_user_name) {
                $totalSkills = $row_plan_detail->skills;
                // $select_skills_relation = $db->select("skills_relation", array("seller_id" => $seller_id));
                $select_skills_relation = $db->query("select * from skills_relation WHERE seller_id='$seller_id'");
                $totalAddedSkills = $select_skills_relation->rowCount();
                // print("<pre>" . print_r([$totalAddedSkills, $totalSkills], true) . "</pre>");
                // exit;
                if ($totalAddedSkills < $totalSkills) {
            ?>

                    <ul class="list-unstyled">
                        <!-- list-unstyled Starts -->

                        <li class="mb-4 clearfix">

                            <button data-toggle="collapse" data-target="#add_skill" class="btn btn-success float-right">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i> <?= $lang['button']['add_new']; ?>
                            </button>

                        </li>

                        <div id="add_skill" class="collapse form-style mb-2">
                            <!-- add_skill collapse form-style mb-2 Starts -->

                            <form method="post">
                                <!-- form Starts -->

                                <div class="form-group">
                                    <!-- form-group Starts -->

                                    <select class="form-control" name="skill_id" required="">

                                        <option value=""><?= $lang['label']['select_skill']; ?></option>

                                        <?php

                                        $s_skills = array();

                                        $get = $db->select("skills_relation", array("seller_id" => $login_seller_id));

                                        while ($row = $get->fetch()) {

                                            array_push($s_skills, $row->skill_id);
                                        }

                                        $s_skills = implode(",", $s_skills);

                                        if (!empty($s_skills)) {
                                            $query_where = "where not skill_id IN ($s_skills)";
                                        } else {
                                            $query_where = "";
                                        }

                                        $get_seller_skills = $db->query("select * from seller_skills $query_where");
                                        while ($row_seller_skills = $get_seller_skills->fetch()) {

                                            $skill_id = $row_seller_skills->skill_id;
                                            $skill_title = $row_seller_skills->skill_title;

                                        ?>

                                            <option value="<?= $skill_id; ?>"> <?= $skill_title; ?> </option>

                                        <?php } ?>

                                        <option value="custom">Custom Skill</option>

                                    </select>

                                </div><!-- form-group Ends -->

                                <div class="form-group skill-name d-none">
                                    <!-- form-group Starts -->

                                    <input type="text" placeholder="Skill Name" class="form-control" name="skill_name">

                                </div><!-- form-group Ends -->

                                <div class="form-group">
                                    <!-- form-group Starts -->

                                    <select class="form-control" name="skill_level" required="">

                                        <option value="" class="hidden"><?= $lang['label']['select_level']; ?></option>
                                        <option> Beginner</option>
                                        <option> Intermediate</option>
                                        <option> Expert</option>

                                    </select>

                                </div><!-- form-group Ends -->

                                <div class="text-center">
                                    <!-- text-center Starts -->

                                    <button type="button" data-toggle="collapse" data-target="#add_skill" class="btn btn-secondary">
                                        <?= $lang['button']['cancel']; ?>
                                    </button>

                                    <button type="submit" name="insert_skill" class="btn btn-success">
                                        <?= $lang['button']['add']; ?>
                                    </button>

                                </div><!-- text-center Ends -->

                            </form><!-- form Ends -->

                            <?php
                            if (isset($_POST['insert_skill'])) {
                                $skill_id = $input->post('skill_id');
                                $skill_level = $input->post('skill_level');
                                $skills_total = $db->count("skills_relation", ["seller_id" => $seller_id]);

                                if ($num_of_skills == 0) {
                                    // echo "<script>alert('no more skill will be added')</script>";
                                    echo "<script>alert('No of skills quota exceeds, Total allowed $skills_total skills')</script>";
                                    echo "<script>window.open('$seller_user_name','_self');</script>";
                                } else {

                                    if ($skill_id == "custom") {
                                        $skill_name = $input->post('skill_name');
                                        $count = $db->count("seller_skills", ["skill_title" => $skill_name]);
                                        $skills_total = $db->count("skills_relation", ["seller_id" => $seller_id]);

                                        if ($count == 1) {
                                            echo "<script>alert('{$lang['alert']['skill_already_added']}');</script>";
                                            echo "<script>window.open('$seller_user_name','_self');</script>";
                                            exit();
                                        } else {
                                            $db->insert("seller_skills", array("skill_title" => $skill_name));
                                            $skill_id = $db->lastInsertId();
                                        }
                                    } else {
                                        /*echo "<script>alert('<?= $skills_total =?>')</script>";*/
                                        $db->insert("skills_relation", array("seller_id" => $seller_id, "skill_id" => $skill_id, "skill_level" => $skill_level));
                                    }
                                    echo "<script>window.open('$seller_user_name', '_self');</script>";
                                }
                            }

                            ?>

                        </div><!-- language collapse form-style mb-2 Ends -->

                    </ul><!-- list-unstyled Ends -->

            <?php
                }
            }
            ?>

        <?php } ?>

        <div class="clearfix"></div>

        <ul class="list-unstyled mt-3">
            <!-- list-unstyled mt-3 Starts -->

            <?php

            $select_skills_relation = $db->select("skills_relation", array("seller_id" => $seller_id));
            while ($row_skills_relation = $select_skills_relation->fetch()) {

                $relation_id = $row_skills_relation->relation_id;
                $skill_id = $row_skills_relation->skill_id;
                $skill_level = $row_skills_relation->skill_level;

                $get_skill = $db->select("seller_skills", array("skill_id" => $skill_id));
                $row_skill = $get_skill->fetch();
                $skill_title = $row_skill->skill_title;

            ?>

                <li class="card-li mb-1">
                    <!--- card-li mb-1 Starts -->

                    <?= $skill_title; ?> - <span class="text-muted"> <?= $skill_level; ?> </span>

                    <?php if (isset($_SESSION['seller_user_name'])) { ?>

                        <?php if ($login_seller_user_name == $seller_user_name) { ?>

                            <a href="user.php?delete_skill=<?= $relation_id; ?>">
                                <i class="fa fa-trash-o"></i>
                            </a>

                        <?php } ?>

                    <?php } ?>

                </li>
                <!--- card-li mb-1 Ends -->

            <?php } ?>

        </ul><!-- list-unstyled mt-3 Ends -->
        <hr class="card-hr">

        <!--        // add qualification-->
        <h4 class="float-left pt-1"><?= $lang['user_profile']['qualification']; ?></h4>

        <?php if (isset($_SESSION['seller_user_name'])) { ?>

            <?php if ($login_seller_user_name == $seller_user_name) { ?>
                <ul class="list-unstyled">
                    <!-- list-unstyled Starts -->
                    <li class="mb-4 clearfix">

                        <button data-toggle="collapse" data-target="#add_education" class="btn btn-success float-right">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i> <?= $lang['button']['add_new']; ?>
                        </button>

                    </li>
                    <div id="add_education" class="collapse form-style mb-2">
                        <!-- add_skill collapse form-style mb-2 Starts -->
                        <form method="post">
                            <!-- form Starts -->
                            <div class="form-group">
                                <select style="width:100%" name="country" id="country" class="form-control" data-placeholder="Select Country">
                                    <?php
                                    $countries = $db->query('SELECT * FROM `countries`');
                                    while ($country = $countries->fetch()) {
                                        echo '<option value="' . $country->id . '">' . $country->name . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <select style="width:100%" name="university" multiple id="university" class="form-control" data-placeholder="Select University">
                                    <?php
                                    $countries = $db->query('SELECT  distinct uninversity FROM education order by uninversity');
                                    while ($country = $countries->fetch()) {
                                        echo '<option value="' . $country->uninversity . '">' . $country->uninversity . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="input-group">
                                <!-- form-group Starts -->
                                <div class="col-xs-12 col-sm-4 no-padding" style="padding:0;">
                                    <select style="width:100%" class="form-control" id="degree_title" name="degree_title" required="">
                                        <!-- <option value=""><?= $lang['label']['degree_title']; ?></option> -->
                                        <option value="0" class="hidden">Course</option>
                                        <option value="associate">Associate</option>
                                        <option value="certificate">Certificate</option>
                                        <option value="ba">B.A.</option>
                                        <option value="barch">BArch</option>
                                        <option value="bm">BM</option>
                                        <option value="bfa">BFA</option>
                                        <option value="bsc">B.Sc.</option>
                                        <option value="ma">M.A.</option>
                                        <option value="mba">M.B.A.</option>
                                        <option value="mfa">MFA</option>
                                        <option value="msc">M.Sc.</option>
                                        <option value="jd">J.D.</option>
                                        <option value="md">M.D.</option>
                                        <option value="phd">Ph.D</option>
                                        <option value="llb">LLB</option>
                                        <option value="llm">LLM</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-xs-12 col-sm-8 no-padding" style="padding:0;">
                                    <input type="text" style="width:100%" class="form-control" name="degree_name" required="" placeholder="Specialized">

                                </div>
                            </div><!-- form-group Ends -->
                            <br>
                            <div class="form-group">
                                <select style="width:100%" name="year_of_graduation" id="year_of_graduation" class="form-control" data-placeholder="Select Year">
                                    <?php
                                    for ($i = 1960; $i <= date('Y'); $i++) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                                <br>

                                <div class="text-center mt-3">
                                    <!-- text-center Starts -->

                                    <button type="button" data-toggle="collapse" data-target="#add_education" class="btn btn-secondary">
                                        <?= $lang['button']['cancel']; ?>
                                    </button>

                                    <button type="submit" name="insert_education" class="btn btn-success">
                                        <?= $lang['button']['add']; ?>
                                    </button>

                                </div><!-- text-center Ends -->

                        </form><!-- form Ends -->

                        <?php
                        if (isset($_POST['insert_education'])) {
                            $country = $input->post('country');
                            $country = $input->post('country');
                            $university = $input->post('university');
                            $degree_title = $input->post('degree_title');
                            $degree_name = $input->post('degree_name');
                            $year_of_graduation = $input->post('year_of_graduation');

                            $insert_skill = $db->insert("education", array(
                                "title" => $degree_title,
                                "name" => $degree_name,
                                "Country" => $country,
                                "uninversity" => $university,
                                "year" => $year_of_graduation,
                                "user_id" => $seller_id,
                                "created_at" => date("Y-m-d H:i:s"),
                            ));
                            if ($insert_skill) {
                                echo '<script>alert("Education Added Successfully")</script>';
                            }
                        }

                        ?>

                    </div><!-- language collapse form-style mb-2 Ends -->
                </ul><!-- list-unstyled Ends -->
            <?php } ?>

        <?php } ?>
        <div class="clearfix"></div>
        <ul class="list-unstyled mt-3">
            <!-- list-unstyled mt-3 Starts -->

            <?php

            $select_skills_relation = $db->select("education", array("user_id" => $seller_id));
            while ($row_skills_relation = $select_skills_relation->fetch()) {

                $relation_id = $row_skills_relation->Country;

                $get_country = $db->select("countries", array("id" => $relation_id));
                $gcountry = $get_country->fetch();

            ?>

                <li class="card-li mb-1">
                    <!--- card-li mb-1 Starts -->

                    <?= $row_skills_relation->year ?> - <span class="text-muted"> <?= $row_skills_relation->uninversity . ' ,' . $gcountry->name; ?> </span><br>
                    <span class="text-muted"><?php echo strtoupper($row_skills_relation->title) . ' ' . $row_skills_relation->name; ?> </span>

                    <?php if (isset($_SESSION['seller_user_name'])) { ?>

                        <?php if ($login_seller_user_name == $seller_user_name) { ?>

                            <a href="user.php?delete_education=<?= $row_skills_relation->id; ?>">
                                <i class="fa fa-trash-o"></i>
                            </a>

                        <?php } ?>

                    <?php } ?>

                </li>
                <!--- card-li mb-1 Ends -->

            <?php } ?>

        </ul><!-- list-unstyled mt-3 Ends -->
        <hr class="card-hr">

        <!--        end add qualification-->
    </div><!-- card-body Ends -->

</div>
<?php if (isset($_SESSION['seller_user_name']) and $_SESSION['seller_user_name'] != $get_seller_user_name) { ?>
    <p class="mt-2"><a class="text-danger <?= ($lang_dir == "right" ? 'text-right' : '') ?>" href="#" data-toggle="modal" data-target="#report-modal-uni" data-content-id="<?= $seller_id ?>" data-content-type="user" data-url="<?= $site_url ?>/<?= $get_seller_user_name ?>"><svg viewBox="0 0 24 24" role="presentation" aria-hidden="true" focusable="false" style="height:12px;width:12px;fill:#000;margin-right:5px;">
                <path d="m22.39 5.8-.27-.64a207.86 207.86 0 0 0 -2.17-4.87.5.5 0 0 0 -.84-.11 7.23 7.23 0 0 1 -.41.44c-.34.34-.72.67-1.13.99-1.17.87-2.38 1.39-3.57 1.39-1.21 0-2-.13-3.31-.48l-.4-.11c-1.1-.29-1.82-.41-2.79-.41a6.35 6.35 0 0 0 -1.19.12c-.87.17-1.79.49-2.72.93-.48.23-.93.47-1.35.71l-.11.07-.17-.49a.5.5 0 1 0 -.94.33l7 20a .5.5 0 0 0 .94-.33l-2.99-8.53a21.75 21.75 0 0 1 1.77-.84c.73-.31 1.44-.56 2.1-.72.61-.16 1.16-.24 1.64-.24.87 0 1.52.11 2.54.38l.4.11c1.39.37 2.26.52 3.57.52 2.85 0 5.29-1.79 5.97-3.84a.5.5 0 0 0 0-.32c-.32-.97-.87-2.36-1.58-4.04zm-4.39 7.2c-1.21 0-2-.13-3.31-.48l-.4-.11c-1.1-.29-1.82-.41-2.79-.41-.57 0-1.2.09-1.89.27a16.01 16.01 0 0 0 -2.24.77c-.53.22-1.04.46-1.51.7l-.21.11-3.17-9.06c.08-.05.17-.1.28-.17.39-.23.82-.46 1.27-.67.86-.4 1.7-.7 2.48-.85.35-.06.68-.1.99-.1.87 0 1.52.11 2.54.38l.4.11c1.38.36 2.25.51 3.56.51 1.44 0 2.85-.6 4.18-1.6.43-.33.83-.67 1.18-1.02a227.9 227.9 0 0 1 1.85 4.18l.27.63c.67 1.57 1.17 2.86 1.49 3.79-.62 1.6-2.62 3.02-4.97 3.02z" fill-rule="evenodd"></path>
            </svg> Report User</a></p>
<?php } ?>
<!--- card user-sidebar rounded-0 Ends -->
<?php
if (isset($_GET['delete_education'])) {
    $delete_skill_id = $input->get('delete_education');
    $delete_skill = $db->delete("education", array("id" => $delete_skill_id, "user_id" => $login_seller_id));
    if ($delete_skill->rowCount() == 1) {
        echo "<script>alert('One education has been deleted.')</script>";
        echo "<script> window.open('$login_seller_user_name','_self') </script>";
    } else {
        echo "<script> window.open('$login_seller_user_name','_self') </script>";
    }
}


?>
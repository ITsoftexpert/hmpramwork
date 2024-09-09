<?php
$form_errors = Flash::render("form_errors");
$form_data = Flash::render("form_data");
if (empty($form_data)) {
    $form_data = $input->post();
}

function insertPackages($proposal_id)
{
    global $db;
    $insertPackage1 = $db->insert("proposal_packages", array("proposal_id" => $proposal_id, "package_name" => 'Basic', "price" => 5));
    $insertPackage2 = $db->insert("proposal_packages", array("proposal_id" => $proposal_id, "package_name" => 'Standard', "price" => 10));
    $insertPackage3 = $db->insert("proposal_packages", array("proposal_id" => $proposal_id, "package_name" => 'Advance', "price" => 15));
    if ($insertPackage3) {
        return true;
    }
}

include("sanitize_url.php");

if (isset($_POST['submit'])) {
    if ($pendingProposal > 0) {
        $rules = array(
            "proposal_title" => "required",
            "proposal_cat_id" => "required",
            "proposal_child_id" => "required",
            "proposal_attr_id" => "required",
            "proposal_tags" => "required",
        );

        $messages = array(
            "proposal_cat_id" => "you must need to select a category",
            "proposal_child_id" => "you must need to select a branch",
            "proposal_attr_id" => "you must need to select a attribute",
            "proposal_enable_referrals" => "you must need to enable or disable proposal referrals.",
            "proposal_img1" => "Proposal Image 1 Is Required.",
            "direct_order" => "Please make a order type choice"
        );
        $val = new Validator($_POST, $rules, $messages);
        if ($val->run() == false) {
            Flash::add("form_errors", $val->get_all_errors());
            Flash::add("form_data", $_POST);
            echo "<script> window.open('create_proposal','_self');</script>";
        } else {
            $proposal_title = $input->post('proposal_title');

            $sanitize_url = proposalUrl($proposal_title);
            $select_proposals = $db->select("proposals", array("proposal_seller_id" => $login_seller_id, "proposal_url" => $sanitize_url));
            $count_proposals = $select_proposals->rowCount();

            if ($count_proposals == 1) {
                echo "<script>
      swal({
      type: 'warning',
      text: 'Opps! Your Already Made A Proposal With Same Title Try Another.',
      })</script>";
            } else {
                $proposal_referral_code = mt_rand();
                $get_general_settings = $db->select("general_settings");
                $row_general_settings = $get_general_settings->fetch();
                $proposal_email = $row_general_settings->proposal_email;
                $site_email_address = $row_general_settings->site_email_address;
                $site_logo = $row_general_settings->site_logo;

                $data = $input->post();
                unset($data['submit']);
                $data['proposal_url'] = $sanitize_url;
                $data['proposal_seller_id'] = $login_seller_id;
                $data['proposal_featured'] = "no";
                if ($enable_referrals == "no") {
                    $data['proposal_enable_referrals'] = "no";
                }
                if (isset($_POST['direct_order'])) {
                    $direct_order = 2;
                } else {
                    $direct_order = 1;
                }
                $data['proposal_price'] = 0;
                $data['delivery_id'] = $db->query("select * from delivery_times")->fetch()->delivery_id;
                $data['level_id'] = $login_seller_level;
                $data['language_id'] = $login_seller_language;
                $data['proposal_status'] = "draft";
                $data['direct_order'] = $direct_order;
                $data['proposal_attr_id'] = $input->post("proposal_attr_id");
                $data['skill_title_id'] = $input->post("skill_title_id");



                $insert_proposal = $db->insert("proposals", $data);

                if ($insert_proposal) {

                    $proposal_id = $db->lastInsertId();

                    $db->insert("instant_deliveries", ["proposal_id" => $proposal_id]);
                    $updat_membership_stats = $db->query("update sellers  set no_of_gigs = no_of_gigs - 1 where seller_id = $row_sellers->seller_id");

                    if ($videoPlugin == 1) {
                        $cat_id = $input->post("proposal_cat_id");
                        $child_id = $input->post("proposal_child_id");
                        include("$dir/plugins/videoPlugin/proposals/checkVideo.php");
                    } else {
                        $redirect = "instant_delivery";
                    }

                    insertPackages($proposal_id);

                    echo "<script>
        swal({
          type: 'success',
          text: 'Details Saved.',
          timer: 2000,
          onOpen: function(){
            swal.showLoading()
          }
        }).then(function(){
          window.open('edit_proposal?proposal_id=$proposal_id&$redirect','_self')
        });
        </script>";
                } else {
                    echo $insert_proposal;
                    echo '<script>alert("NO NOT DONE")</script>';
                }
            }
        }
    } else {
        echo "<script>alert('Please upgrade you membership plan')</script>";
    }
}

?>
<script>
    $(function() {
        $('#direct_order').popover('show')

        $("#direct_order").on('change', function() {
            if ($("#direct_order").is(":checked"))
                $('#direct_order').popover('show')
            else
                $('#direct_order').popover('hide')
        })
    });
</script>
<style>
    @media (max-width:768px) {
        .full-width {
            /* border: 2px solid green; */
            width: 100%;
            padding: 5px 15px 5px 17px;
        }

        .padding-left {
            padding-left: 16px;
            /* background-color: red; */
            /* font-size: 15px !important; */
            /* font-weight: 500; */
            /* padding-right: 15px; */
        }

        .margin-l-b-r {
            margin: 0px 15px 15px 15px;
            text-align: center;
        }

        .font-size-small {
            font-size: 11px;
            color: #04c4ab !important;
            padding-left: 1px;
        }

        .div-textarea textarea {
            /* border:2px solid green; */
            font-size: 13px !important;
        }

        .div-textarea textarea::placeholder {
            color: lightgray;
            font-size: 12px;
            font-weight: 300;
        }

        .input-left-padding {
            /* border: 2px solid green; */
            padding-left: 3.5px;
            font-size: 13px !important;
        }

        .text-align-center {
            text-align: center !important;
        }

        .font-increase {
            font-size: 16px;
            /* color: #126e6b; */
        }


    }
</style>
<form action="#" method="post" class="proposal-form">
    <!--- form Starts -->
    <div class="row">
        <div class="col-xs-12">
            <div class="float-right switch-box full-width">
                <span class="text font-increase"><?= $lang['edit_proposal']['direct_order']['enable']; ?></span>
                <label class="switch float-right" id="switchOrder">
                    <input type="checkbox" name="direct_order" id="direct_order" value="1" checked data-toggle="popover" data-placement="right" data-trigger="manual" data-offset="0 72px" title="Information" data-content="When its off, Buyer can't buy this service directly." />
                    <span class="slider instant-slider direct_order"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <!--- form-group row Starts --->
        <div class="alert alert-info col-xs-12 margin-l-b-r">
            You have <b><?php echo $pendingProposal; ?></b> proposals remaining.
        </div>
        <?php if ($pendingProposal == 0) { ?>
            <style>
                .out-of-credit {
                    position: absolute;
                    top: 0;
                    left: 0;
                    padding: 30vh 0;
                    width: 100%;
                    height: 100%;
                    background-color: #e5e5e5;
                    z-index: 9001;
                    opacity: 0.9;
                    font-size: 45px;
                    font-weight: bolder;
                    text-align: center;
                    vertical-align: middle;
                    /* line-height: 90vh; */
                }

                .link_color_theme {
                    color: #00cedc;
                }
            </style>
            <div class="out-of-credit">
                Please <a href="<?= $site_url; ?>/membership_subs" class="link_color_theme">upgrade plan</a> to add more proposals
            </div>
        <?php } ?>
        <div class="col-md-3 padding-left"><?= $lang['label']['proposal_title']; ?></div>
        <div class="col-md-9 div-textarea">
            <textarea name="proposal_title" id="proposal_title" rows="3" required="" placeholder="Example: I can make program in core php." class="form-control " minlength="30" maxlength="100"></textarea>
            <small class="form-text text-danger"><?= ucfirst($form_errors['proposal_description'] ?? ""); ?></small>
            <span class="text-dark d-block font-size-small ">min: 30 max: 100 characters <span class="pull-right"><i class="text-danger" id="typed-characters">0</i> characters</span></span>
        </div>
    </div>
    <!--- form-group row Ends --->


    <div class="form-group row">
        <!--- form-group row Starts --->
        <div class="col-md-3 padding-left"> <?= $lang['label']['category']; ?> </div>
        <div class="col-md-9">
            <!-- category skill -->
            <div class="form-group">
                <select name="proposal_cat_id" id="skill_category_" class="form-control">
                    <?php
                    $q_skills_relation = $db->select("skills_relation", array("seller_id" => $login_seller_id));
                    $added_skill_categories = array(); // Array to keep track of added skill categories

                    if ($q_skills_relation->rowCount() > 0) {
                        while ($row_seller_skills = $q_skills_relation->fetch()) {
                            $skill_cat_id = $row_seller_skills->skill_cat_id;
                            $skill_category = $db->select("cats_meta", array("cat_id" => $skill_cat_id))->fetch();

                            if (!in_array($skill_category->cat_title, $added_skill_categories)) {
                                $added_skill_categories[] = $skill_category->cat_title; // Add to the tracking array
                    ?>
                                <option value="<?= $skill_cat_id; ?>" selected><?= $skill_category->cat_title; ?></option>
                    <?php
                            }
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-3 padding-left"> Branch </div>
        <div class="col-md-9">
            <!--  sub category skill-->
            <div class="form-group">
                <select name="proposal_child_id" id="skill_sub_category_" class="form-control">
                    <?php
                    $q_skills_relation = $db->select("skills_relation", array("seller_id" => $login_seller_id));
                    $added_skill_sub_categories = array(); // Array to keep track of added skill sub-categories

                    if ($q_skills_relation->rowCount() > 0) {
                        while ($row_seller_skills = $q_skills_relation->fetch()) {
                            $skill_child_id = $row_seller_skills->skill_child_id;
                            $skill_sub_category = $db->select("child_cats_meta", array("child_id" => $skill_child_id))->fetch();

                            if (!in_array($skill_sub_category->child_title, $added_skill_sub_categories)) {
                                $added_skill_sub_categories[] = $skill_sub_category->child_title; // Add to the tracking array
                    ?>
                                <option value="<?= $skill_child_id; ?>" selected><?= $skill_sub_category->child_title; ?></option>
                    <?php
                            }
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-3 padding-left"> Expertise </div>
        <div class="col-md-9">
            <!-- attribute skill -->
            <div class="form-group">
                <select name="proposal_attr_id" id="skill_attribute_" class="form-control">
                    <?php
                    $q_skills_relation = $db->select("skills_relation", array("seller_id" => $login_seller_id));
                    $added_sub_sub_categories = array(); // Array to keep track of added sub-sub-categories

                    if ($q_skills_relation->rowCount() > 0) {
                        while ($row_seller_skills = $q_skills_relation->fetch()) {
                            $skill_sub_child_id = $row_seller_skills->skill_sub_child_id;
                            $skill_attribute = $db->select("sub_subcategories", array("attr_id" => $skill_sub_child_id))->fetch();

                            if (!in_array($skill_attribute->sub_subcategory_name, $added_sub_sub_categories)) {
                                $added_sub_sub_categories[] = $skill_attribute->sub_subcategory_name; // Add to the tracking array
                    ?>
                                <option value="<?= $skill_sub_child_id; ?>" selected><?= $skill_attribute->sub_subcategory_name; ?></option>
                    <?php
                            }
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-3 padding-left"> skills </div>
        <div class="col-md-9">
            <!-- skill details -->
            <div class="form-group">
                <select name="skill_title_id" id="skill_title_" class="form-control">
                    <?php
                    $q_skills_relation = $db->select("skills_relation", array("seller_id" => $login_seller_id));
                    $added_skill_titles = array(); // Array to keep track of added skill titles

                    if ($q_skills_relation->rowCount() > 0) {
                        while ($row_seller_skills = $q_skills_relation->fetch()) {
                            $skill_id = $row_seller_skills->skill_id;
                            $skill_details = $db->select("seller_skills", array("skill_id" => $skill_id))->fetch();

                            if (!in_array($skill_details->skill_title, $added_skill_titles)) {
                                $added_skill_titles[] = $skill_details->skill_title; // Add to the tracking array
                    ?>
                                <option value="<?= $skill_id; ?>" selected><?= $skill_details->skill_title; ?></option>
                    <?php
                            }
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-3 padding-left"> </div>
        <div class="col-md-9">
            <div class="add-more-category-btn-div">
                <a href="<?= $site_url ?>/settings?professional_settings" target="_blank">
                    <span class="add-more-category-btn"> <i class="fa fa-plus"></i> Add more skills</span>
                </a>
            </div>
        </div>
    </div>
    <!--- form-group row Ends --->


    <div class="form-group row d-none">
        <!--- form-group row Starts --->
        <!-- <div class="col-md-3"><?= $lang['label']['delivery_time']; ?></div>
<div class="col-md-9">
<select name="delivery_id" class="form-control" required="">
<?php
$get_delivery_times = $db->select("delivery_times");
while ($row_delivery_times = $get_delivery_times->fetch()) {
    $delivery_id = $row_delivery_times->delivery_id;
    $delivery_proposal_title = $row_delivery_times->delivery_proposal_title;
?>
<option value="<?= $delivery_id; ?>" <?php if (@$form_data['delivery_id'] == $delivery_proposal_title) {
                                            echo "selected";
                                        } ?>><?= $delivery_proposal_title; ?></option>
<?php } ?>
</select>
</div>
<small class="form-text text-danger"><?= ucfirst($form_errors['delivery_id'] ?? ""); ?></small> -->
    </div>
    <!--- form-group row Ends --->


    <div class="form-group row d-none">
        <!--- form-group row Starts --->
        <!-- <div class="col-md-3"><?= $lang['label']['proposal_revisions']; ?></div>
  <div class="col-md-9">
    <select name="proposal_revisions" class="form-control" required="">
    <?php
    foreach ($revisions as $key => $rev) {
        echo "<option value='$key'>$rev</option>";
    }
    ?>
    </select>
    <small class="muted">Set this to 0 if this proposal is for instant delivery.</small>
  </div>
  <small class="form-text text-danger"><?= ucfirst($form_errors['proposal_revisions'] ?? ""); ?></small> -->
    </div>
    <!--- form-group row Ends --->


    <?php if ($enable_referrals == "yes") { ?>

        <div class="form-group row">
            <!--- form-group row Starts --->
            <label class="col-md-3 control-label padding-left"> <?= $lang['label']['enable_referrals']; ?> </label>
            <div class="col-md-9">
                <select name="proposal_enable_referrals" class="proposal_enable_referrals form-control input-left-padding">
                    <?php if (@$form_data['proposal_enable_referrals'] == "yes") { ?>
                        <option value="yes"> Yes</option>
                        <option value="no"> No</option>
                    <?php } else { ?>
                        <option value="no"> No</option>
                        <option value="yes"> Yes</option>
                    <?php } ?>
                </select>
                <small class="font-size-small">If enabled, other users can promote your proposal by sharing it on different platforms.</small>
                <small class="form-text text-danger"><?= ucfirst($form_errors['proposal_enable_referrals'] ?? ""); ?></small>
            </div>
        </div>
        <!--- form-group row Ends --->

        <div class="form-group row proposal_referral_money">
            <!--- form-group row Starts --->
            <label class="col-md-3 control-label"> <?= $lang['label']['promotion_commission']; ?> </label>
            <div class="col-md-9">
                <input type="number" name="proposal_referral_money" class="form-control" min="1" value="<?= @$form_data['proposal_referral_money']; ?>" placeholder="Figure should be in percentage e.g 20">
                <small>Figure should be in percentage. E.g 20 is the same as 20% from the sale of this proposal.</small>
                <br>
                <small> When another user promotes your proposal, how much would you like that user to get from the
                    sale? (in percentage)
                </small>
            </div>
        </div>
        <!--- form-group row Ends --->
    <?php } ?>

    <div class="form-group row">
        <!--- form-group row Starts --->
        <div class="col-md-3 padding-left"><?= $lang['label']['tags']; ?></div>
        <div class="col-md-9">
            <input type="text" name="proposal_tags" class="form-control input-left-padding" data-role="tagsinput">
            <small class="font-size-small">Press enter to add your own tags after adding word.</small>
            <br /><small class="font-size-small">Enter at leaset 2 characters to get suggestions.</small>
            <small class="form-text text-danger"><?= ucfirst($form_errors['proposal_tags'] ?? ""); ?></small>
        </div>
    </div>
    <!--- form-group row Ends --->
    <div class="form-group text-right mb-0 text-align-center">
        <!--- form-group Starts --->
        <?php if ($row_sellers->no_of_gigs > 0) { ?>
            <a href="view_proposals" class="btn btn-secondary"><?= $lang['button']['cancel']; ?></a>
            <input class="btn btn-success" type="submit" name="submit" value="<?= $lang['button']['save_continue']; ?>">
        <?php } ?>
    </div>
    <!--- form-group Starts --->

</form>
<style>
    #sub-sub-category {
        display: none;
    }

    .add-more-category-btn-div {
        width: 100%;
        height: 2.7rem;
        display: flex;
    }

    .add-more-category-btn-div a {
        margin: auto 0;
    }

    .add-more-category-btn {
        border: 1px solid lightgray;
        border-radius: 5px;
        padding: 0.7rem 1rem;
        background-color: #e5e5e5;
    }

    .add-more-category-btn:hover {
        border: 1px solid grey;
    }
</style>

<!--- form Ends -->
<script>
    const textAreaElement = document.querySelector("#proposal_title");
    const typedCharactersElement = document.querySelector("#typed-characters");
    const minimumCharacters = 100;

    textAreaElement.addEventListener("input", (event) => {
        const typedCharacters = textAreaElement.value.length + 1;
        if (typedCharacters > minimumCharacters) {
            return false;
        }
        typedCharactersElement.textContent = typedCharacters;
    });
</script>

<script>
    $(document).ready(function() {
        $('#sub-category').change(function() {
            var child_id = $(this).val();
            if (child_id) {
                $('#sub-sub-category').css('display', 'block');
                // Check if child_id is correct
                $.ajax({
                    type: 'POST',
                    url: 'fetch_sub_subcategories',
                    data: {
                        child_id: child_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log("Success response: ", response); // Check the response here
                        $('#sub-sub-category').html('<option value="" class="hidden">Select A Sub-Sub Category</option>');
                        $.each(response, function(index, subSubCategory) {
                            $('#sub-sub-category').append('<option value="' + subSubCategory.id + '">' + subSubCategory.name + '</option>');
                        });

                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + ", " + error);
                        console.log("Response: " + xhr.responseText); // Log the raw response text                        
                    }
                });
            } else {
                $('#sub-sub-category').html('<option value="" class="hidden">Select A Sub-Sub Category</option>');
            }
        });
    });
</script>
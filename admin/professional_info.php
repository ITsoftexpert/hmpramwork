<?php
@session_start();

if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login','_self');</script>";
} else {

    $categoryId = isset($_GET['professional_info']) ? $_GET['professional_info'] : null;
    $optionId = isset($_GET['id']) ? $_GET['id'] : null;
    $action = isset($_GET['action']) ? $_GET['action'] : null;

    $qCategory = $db->select("cats_meta", array("cat_id" => $categoryId, "language_id" => $adminLanguage));
    $oCategory = $qCategory->fetch();
    if (!$oCategory) {
        echo "<script>window.open('/index?view_cats','_self');</script>";
    }

    $title = "";
    $status = 1;
    if ($action == "edit") {
        $qOption = $db->select("professional_info", array("category_id" => $categoryId, "id" => $optionId));
        $oOption = $qOption->fetch();

        if (!$oCategory) {
            echo "<script>window.open('/index?professional_info={$categoryId}','_self');</script>";
        }

        $title = $oOption->title;
        $status = $oOption->status;
    }

    // DB Save
    if (isset($_POST['save'])) {
        $rules = array(
            "title" => "required",
        );

        $messages = array("title" => "Option Title Is Required.");

        $val = new Validator($_POST, $rules, $messages);
        if ($val->run() == false) {
            Flash::add("form_errors", $val->get_all_errors());
            Flash::add("form_data", $_POST);
            echo "<script> window.open('index?professional_info={$categoryId}','_self');</script>";
        } else {
            $title = $input->post('title');
            $status = $input->post('status');

            if ($action == 'edit') {
                $updateOpt = $db->update("professional_info", array("title" => $title, "category_id" => $categoryId, "status" => $status, "admin_id" => $admin_id), array("id" => $optionId));
                if ($updateOpt) {
                    $insert_log = $db->insert_log($admin_id, "professional_option", $optionId, "updated");

                    echo "<script>alert('One Option Has Been Updated.');</script>";
                    echo "<script>window.open('index?professional_info={$categoryId}','_self');</script>";
                }
            } else { // create
                $insertOpt = $db->insert("professional_info", ["title" => $title, "category_id" => $categoryId, "status" => $status, "admin_id" => $admin_id]);

                if ($insertOpt) {
                    $insert_id = $db->lastInsertId();
                    $insert_log = $db->insert_log($admin_id, "professional_option", $insert_id, "inserted");
                    echo "<script>alert('One Option Has Been Inserted.');</script>";
                    echo "<script>window.open('index?professional_info={$categoryId}','_self');</script>";
                }
            }
        }
    }

    $cat_title = $oCategory->cat_title;
?>


    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1><i class="menu-icon fa fa-cubes"></i> Categories/Professional Info </h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li><a href="index?view_cats">Categories</a></li>
                        <li class="active">Professional Info</li>
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
                    <div class="card-header d-flex justify-content-between align-items-center"><!--- card-header Starts --->
                        <h4 class="h4"> <i class="fa fa-money-bill-alt"></i> Manage Options <small class="text-muted"><?= $cat_title ?></small></h4>
                    </div><!--- card-header Ends --->

                    <div class="card-body"><!--- card-body Starts --->
                        <div class="row">
                            <div class="col-4 align-self-center border border-info">
                                <?php
                                $form_errors = Flash::render("form_errors");
                                $form_data = Flash::render("form_data");
                                if (is_array($form_errors)) {
                                ?>
                                    <div class="alert alert-danger mt-1"><!--- alert alert-danger Starts --->
                                        <ul class="list-unstyled mb-0">
                                            <?php $i = 0;
                                            foreach ($form_errors as $error) {
                                                $i++; ?>
                                                <li class="list-unstyled-item"><?= $i ?>. <?= ucfirst($error); ?></li>
                                            <?php } ?>
                                        </ul>
                                    </div><!--- alert alert-danger Ends --->
                                <?php } ?>
                                <form action="" method="post">
                                    <div class="form-group mt-2">
                                        <label for="formTitle">Title</label>
                                        <input type="text" class="form-control" name="title" id="formTitle" placeholder="Title" required value="<?= $title ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="formStatus">Status</label>
                                        <select class="form-control" id="formStatus" name="status">
                                            <option value="1" <?= $status == 1 ? "selected" : "" ?>>Active</option>
                                            <option value="0" <?= $status == 0 ? "selected" : "" ?>>Deactive</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <input class="form-control btn btn-info" name="save" type="submit" value="<?= $action == 'edit' ? 'Update' : 'Create' ?>">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr />
                        <h3>Options</h3>
                        <div class="table-responsive mt-2"><!--- table-responsive Starts --->
                            <table class="table table-bordered"><!--- table table-bordered table-hover Starts --->
                                <thead><!--- thead Starts --->
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead><!--- thead Ends --->

                                <tbody><!--- tbody Starts --->
                                    <?php
                                    $per_page = 8;

                                    if (isset($_GET['page'])) {
                                        $page = $input->get('page');
                                        if ($page == 0) {
                                            $page = 1;
                                        }
                                    } else {
                                        $page = 1;
                                    }

                                    $i = ($page * $per_page) - 8;

                                    /// Page will start from 0 and multiply by per page
                                    $start_from = ($page - 1) * $per_page;
                                    $qSelect = $db->query("SELECT * FROM  professional_info WHERE category_id = :category_id ORDER BY 1 DESC LIMIT :limit OFFSET :offset", "", array("category_id" => $categoryId, "limit" => $per_page, "offset" => $start_from));

                                    if ($qSelect->rowCount() > 0) {
                                        while ($oRow = $qSelect->fetch()) {

                                            $id = $oRow->id;
                                            $title = $oRow->title;
                                            $admin_id = $oRow->admin_id;
                                            $status = $oRow->status == 1 ? "Active" : "Deactive";
                                            $created_at = $oRow->created_at;

                                            $i++;

                                    ?>
                                            <tr>
                                                <td>
                                                    <?= $i; ?>
                                                </td>
                                                <td>
                                                    <?= $title; ?>
                                                </td>
                                                <td>
                                                <span class="badge badge-<?= $status == "Active" ? 'success' : "danger"; ?>"><?= $status; ?></span>
                                                </td>
                                                <td>
                                                    <?= date("Y-m-d, H:i", strtotime($created_at)); ?>
                                                </td>
                                                <td width="150px;">
                                                    <div class="dropdown"><!--- dropdown Starts --->
                                                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Actions</button>
                                                        <div class="dropdown-menu" style="margin-left:-125px;"><!--- dropdown-menu Starts --->
                                                            <a class="dropdown-item" href="index?professional_info=<?= $categoryId ?>&action=edit&id=<?= $id; ?>">
                                                                <i class="fa fa-info-circle"></i> Edit
                                                            </a>
                                                            <a class="dropdown-item" href="index?professional_info=<?= $categoryId ?>&action=delete&id=<?= $id; ?>" onclick="return confirm('Are you sure to delete this?')">
                                                                <i class="fa fa-trash"></i> Delete
                                                            </a>
                                                        </div><!--- dropdown-menu Ends --->
                                                    </div><!--- dropdown Ends --->
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td class="table-danger" colspan="5">0 record found. Please use above form to create new.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody><!--- tbody Ends --->

                            </table><!--- table table-bordered table-hover Ends --->

                        </div><!--- table-responsive Ends --->


                        <div class="d-flex justify-content-center"><!--- d-flex justify-content-center Starts --->

                            <ul class="pagination"><!--- pagination Starts --->
                                <?php
                                $query = $db->query("SELECT * FROM  professional_info WHERE category_id = :category_id", "", array("category_id" => $categoryId));
                                /// Count The Total Records
                                $total_records = $query->rowCount();
                                /// Using ceil function to divide the total records on per page
                                $total_pages = ceil($total_records / $per_page);
                                if ($total_records > 0) {
                                    $pageUrl ="index?professional_info={$categoryId}";
                                    echo "<li class='page-item'><a href='{$pageUrl}' class='page-link'> First Page </a></li>";
                                    echo "<li class='page-item " . (1 == $page ? "active" : "") . "'><a class='page-link' href='{$pageUrl}&page=1'>1</a></li>";
                                    $i = max(2, $page - 5);
                                    if ($i > 2)
                                        echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
                                    for (; $i < min($page + 6, $total_pages); $i++) {
                                        echo "<li class='page-item";
                                        if ($i == $page) {
                                            echo " active ";
                                        }
                                        echo "'><a href='{$pageUrl}&page={$i}' class='page-link'>" . $i . "</a></li>";
                                    }
                                    if ($i != $total_pages and $total_pages > 1) {
                                        echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
                                    }

                                    if ($total_pages > 1) {
                                        echo "<li class='page-item " . ($total_pages == $page ? "active" : "") . "'><a class='page-link' href='{$pageUrl}&page={$total_pages}'>$total_pages</a></li>";
                                    }

                                    echo "<li class='page-item'><a href='{$pageUrl}&page={$total_pages}' class='page-link'>Last Page </a></li>";
                                }
                                ?>
                            </ul><!--- pagination Ends --->
                        </div><!--- d-flex justify-content-center Ends --->
                    </div><!--- card-body Ends --->
                </div><!--- card Ends --->
            </div><!--- col-lg-12 Ends --->
        </div><!--- 3 row Ends --->
    </div>
<?php } ?>
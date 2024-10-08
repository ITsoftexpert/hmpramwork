<?php
@session_start();

if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login','_self');</script>";
    exit;
}

$categoryId = isset($_GET['id']) ? $_GET['id'] : null;
// edit, index (), create with category id
$action = isset($_GET['action']) ? $_GET['action'] : null;

//
$title = "";
if ($categoryId) {
    // if ($action)
}
$parentId = isset($_GET['category_id']) ? $_GET['category_id'] : null;

$qCategories = $db->query("SELECT * FROM professional_info WHERE parent_id IS NULL AND status = 1");
?>
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1><i class="menu-icon fa fa-rss"></i> Blog</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Categories</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid"><!--- container Starts --->

    <div class="row"><!-- row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <div class="card card-default"><!-- card card-default Starts -->

                <div class="card-header"><!-- card-header Starts -->
                    <i class="fa fa-money fa-fw"></i> Manage Categories
                </div><!-- card-header Ends -->

                <div class="card-body"><!-- card-body Starts -->
                <?php
                    if (in_array($action, [null, 'create', 'edit'])) {
                ?>
                    <div class="row">
                        <div class="col-4 align-self-center">
                            <h3>New Category</h3>
                            <form action="" method="post">
                                <div class="form-group mt-2">
                                    <label for="formTitle">Title</label>
                                    <input type="text" class="form-control" id="formTitle" placeholder="Title" required>
                                </div>
                                <div class="form-group">
                                    <label for="formStatus">Category</label>
                                    <select class="form-control" id="formStatus">
                                        <option value="0">Parent Category</option>
                                        <?php
                                        if ($qCategories->rowCount() > 0) {
                                            while($oCategory = $qCategories->fetch()) {
                                        ?>
                                        <option value="<?=$oCategory->id?>" <?=$oCategory->id == $parentId ? ' selected' : '';?>><?=$oCategory->title?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="formStatus">Status</label>
                                    <select class="form-control" id="formStatus">
                                        <option value="1">Active</option>
                                        <option value="0">Deactive</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input class="form-control btn btn-success" name="insert" type="submit" value="Save">
                                </div>
                                <input type="text" name="id" value="<?=$categoryId?>"
                            </form>
                        </div>
                    </div>
                    <hr />
                <?php } ?>
                    <h3>Category Name <small class="text-muted">Options: </small></h3>
                    <div class="table-responsive mt-2"><!--- table-responsive Starts -->
                        <table class="table table-bordered table-hover table-striped"><!--- table table-bordered table-hover table-striped Starts -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                $categories = $db->select("post_categories", "", "DESC");
                                while ($cat = $categories->fetch()) {
                                    $i++;
                                    $cat_id = $cat->id;

                                    // $insert = $db->insert("post_categories_meta",array("cat_id"=>$cat_id,"cat_name"=>$cat->cat_name,"language_id"=>$adminLanguage,"cat_creator"=>$cat->cat_creator));

                                    $post_category_meta = $db->select("post_categories_meta", array("cat_id" => $cat_id, "language_id" => $adminLanguage))->fetch();

                                    $language_title = $db->query("select * from languages where id = " . $post_category_meta->language_id)->fetch()->title;

                                ?>
                                    <tr>
                                        <td><?= $i; ?></td>
                                        <td><?= $post_category_meta->cat_name; ?></td>
                                        <td><?= $cat->date_time; ?></td>
                                        <td><?= $language_title; ?></td>
                                        <!-- <td><?= $cat->cat_creator; ?></td> -->
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-cog"></i> Actions
                                                </button>
                                                <div class="dropdown-menu" style="margin-left: -125px;">
                                                    <a class="dropdown-item" href="index?edit_post_cat=<?= $cat->id; ?>">
                                                        <i class="fa fa-pencil"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item" href="index?delete_post_cat=<?= $cat->id; ?>" onclick="if(!confirm('Are you sure you want to delete selected item.')){ return false; }">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table><!--- table table-bordered table-hover table-striped Starts -->
                    </div><!--- table-responsive Ends -->
                </div><!-- card-body Ends -->
            </div><!-- card card-default Ends -->
        </div>
    </div>
</div>

<?php

if (isset($_POST['insert'])) {

    $data = $input->post();
    $data['date_time'] = date("F d, Y");
    unset($data['insert']);

    $cat_image = $_FILES['cat_image']['name'];
    $tmp_cat_image = $_FILES['cat_image']['tmp_name'];

    $allowed = array('jpeg', 'jpg', 'gif', 'png', 'tif', 'ico', 'webp');
    $file_extension = pathinfo($cat_image, PATHINFO_EXTENSION);
    if (!in_array($file_extension, $allowed) & !empty($cat_image)) {
        echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";
    } else {

        uploadToS3("blog_cat_images/$cat_image", $tmp_cat_image);
        $isS3 = $enable_s3;
        $post_categories = $db->insert("post_categories", array("date_time" => $data['date_time'], 'cat_image' => $cat_image, 'isS3' => $isS3));
        if ($post_categories) {
            $insert_id = $db->lastInsertId();

            $get_languages = $db->select("languages");
            while ($row_languages = $get_languages->fetch()) {
                $id = $row_languages->id;
                $insert = $db->insert("post_categories_meta", ["cat_id" => $insert_id, "language_id" => $id]);
            }

            unset($data['date_time']);

            $update_meta = $db->update("post_categories_meta", $data, array("cat_id" => $insert_id, "language_id" => $adminLanguage));

            $insert_log = $db->insert_log($admin_id, "post_cat", $insert_id, "updated");

            echo "<script>alert('One Post Category has been Inserted Successfully.');</script>";
            echo "<script>window.open('index?post_categories','_self');</script>";
        }
    }
}

?>
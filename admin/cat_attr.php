<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../includes/db.php");



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input as needed
    $cat_id = $_POST['cat_id'] ?? '';
    $child_id = $_POST['child_id'] ?? '';
    $cat_attr = $_POST['cat_attr'] ?? '';

    // Replace spaces with hyphens if cat_attr has more than one word
    if (str_word_count($cat_attr) > 1) {
        $cat_attr_db = str_replace(' ', '-', $cat_attr);
        $cat_attr_show = $cat_attr;
    } else {
        $cat_attr_db = $cat_attr;
        $cat_attr_show = $cat_attr;
    }

    // Handle image upload
    // $target_dir = "/home/u890970355/domains/hiremyprofile.com/public_html/uploads/"; for server only
    $target_dir = "C:/xampp/htdocs/beta/uploads/";
    $image_name = basename($_FILES["cat_image"]["name"]);
    $target_file = $target_dir . $image_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["cat_image"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "<p class='text-light bg-danger p-3 mb-0'>File is not an image.</p>";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["cat_image"]["size"] > 500000) {
        echo "<p class='text-light bg-danger p-3 mb-0'>Sorry, your file is too large.</p>";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<p class='text-light bg-danger p-3 mb-0'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<p class='text-light bg-danger p-3 mb-0'>Sorry, your file was not uploaded.</p>";
    } else {
        if (move_uploaded_file($_FILES["cat_image"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars($image_name) . " has been uploaded.";
            // Insert data into cat_attribute table
            $insert_cat_attribute = $db->insert("cat_attribute", [
                "child_parent_id" => $cat_id,
                "child_id" => $child_id,
                "cat_attr" => $cat_attr_db,
                "language_id" => 1
            ]);

            // Retrieve last inserted ID
            $cat_attr_id = $db->lastInsertId();

            // Insert data into sub_subcategories table using retrieved cat_attr_id
            $insert_subsubcategory = $db->insert("sub_subcategories", [
                "subcategory_id" => $child_id,
                "sub_subcategory_name" => $cat_attr_show,
                "attr_id" => $cat_attr_id,
                "image" => $image_name, // Store the image name
                "language_id" => 1
            ]);

            if ($insert_cat_attribute && $insert_subsubcategory) {
                // Insertion successful
                echo "<p class='text-light bg-success p-3 mb-0'>Data inserted successfully into both tables!</p>";
            } else {
                // Insertion failed
                echo "<p class='text-light bg-danger p-3 mb-0'>Failed to insert data into both tables.</p>";
            }
        } else {
            echo "<p class='text-light bg-danger p-3 mb-0'>Sorry, there was an error uploading your file.</p>";
        }
    }
}
?>



<div class="containerFluid2">
    <h3 class="category_attr_heading">Category Attribute</h3>
    <div class="form_div_style">
        <form action="" method="post" enctype="multipart/form-data">
            <h3 class="text-center my-4">Add New Category Attribute</h3>

            <select class="select_style_width" name="cat_id" id="category" required="">
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

            <select class="select_style_width" name="child_id" id="sub-category" style="display: none;" required="">
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
                        <?= $child_title; ?>
                    </option>
                <?php } ?>
            </select>

            <input class="input_style_width" type="text" name="cat_attr" id="cat-attr" style="display: none;">
            <input class="input_style_width" type="file" name="cat_image" id="cat-image" style="display: none;">

            <div class="submit_btn_style_attr">
            <button type="submit" class="button_style_width mt-3 mb-4">Submit</button>
            </div>
        </form>
    </div>
</div>





<style>
    .containerFluid2 {
        width: 100%;
    }

    .input_style_width,
    .select_style_width,
    .button_style_width {
        width: 80%;
        margin: 1rem 10%;
        padding: 1rem;
        border: 1px solid lightgrey;
        border-radius: 5px;
    }

    .button_style_width {
        margin: auto;
        width: 50%;
        padding: 1rem;
        border: 1px solid green;
        background-color: green;
        color: white;
        border-radius: 5px;
    }

    .form_div_style {
        border: 1px solid white;
        width: 50%;
        margin: 3rem auto;
        display: block;
        border-radius: 5px;
        background-color: #fff;
    }

    .category_attr_heading {
        background-color: white;
        padding: 1rem;
    }
    .submit_btn_style_attr{
        display: flex;
        width: 100%;
    }
</style>

<script>
    function handleCategoryChange() {
        var selectedCategory = document.getElementById('category').value;
        var subCategoryOptions = document.querySelectorAll('.sub-category-option');

        subCategoryOptions.forEach(function(option) {
            var parentCategory = option.getAttribute('data-parent');
            if (parentCategory === selectedCategory) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });

        var subCategorySelect = document.getElementById('sub-category');
        var catAttrInput = document.getElementById('cat-attr');
        var catAttrimgInput = document.getElementById('cat-image');


        if (selectedCategory !== '') {
            subCategorySelect.style.display = 'block';
            catAttrInput.style.display = 'none';
            catAttrimgInput.style.display = 'none';

        } else {
            subCategorySelect.style.display = 'none';
            catAttrInput.style.display = 'none';
            catAttrimgInput.style.display = 'none';

        }
    }

    function handleSubCategoryChange() {
        var selectedSubCategory = document.getElementById('sub-category').value;
        var catAttrInput = document.getElementById('cat-attr');
        var catAttrimgInput = document.getElementById('cat-image');


        if (selectedSubCategory !== 'select-sub-category') {
            catAttrInput.style.display = 'block';
            catAttrimgInput.style.display = 'block';
        } else {
            catAttrInput.style.display = 'none';
            catAttrimgInput.style.display = 'none';

        }
    }

    document.getElementById('category').addEventListener('change', handleCategoryChange);
    document.getElementById('sub-category').addEventListener('change', handleSubCategoryChange);
</script>



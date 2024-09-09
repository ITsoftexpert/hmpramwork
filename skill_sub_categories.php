<?php
// session_start();
require_once("includes/db.php");
// Include your database connection file

if (isset($_POST['skill_cat_id'])) {
    $cat_id = $_POST['skill_cat_id'];
    $qSubCategories = $db->select("categories_children", array("child_parent_id" => $cat_id));
    echo '<option value="">Choose a sub category</option>'; 
    if ($qSubCategories->rowCount() > 0) {
        while ($subCategory = $qSubCategories->fetch()) {
            $sub_category_meta = $db->select("child_cats_meta", array("child_id" => $subCategory->child_id))->fetch();
            
            echo '<option value="' . $subCategory->child_id . '">' . $sub_category_meta->child_title . '</option>';
        }
    } else {
        echo '<option value="">No sub-categories found</option>';
    }
} else {
    echo '<option value="">Invalid category</option>';
} 
?>    
   
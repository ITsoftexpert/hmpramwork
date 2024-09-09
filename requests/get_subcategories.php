<?php
session_start();

require_once("../includes/db.php");

if (!isset($_SESSION['seller_user_name'])) {

    echo "<script>window.open('../login','_self')</script>";
}



if(isset($_POST['cat_id'])){
    $cat_id = $_POST['cat_id'];
    $get_subcats = $db->select("categories_children", ["child_parent_id" => $cat_id]);
    echo '<option value="" class="hidden">Select A Sub Category</option>';
    while($row_subcats = $get_subcats->fetch()){
        $sub_cat_id = $row_subcats->child_id;
        $get_meta = $db->select("child_cats_meta", ["child_id" => $sub_cat_id, "language_id" => $siteLanguage]);
        $sub_cat_title = $get_meta->fetch()->child_title;
        echo '<option value="'.$sub_cat_id.'">'.$sub_cat_title.'</option>';
    }
}
?>

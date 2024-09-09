<?php
session_start();

require_once("../includes/db.php");

if (!isset($_SESSION['seller_user_name'])) {

    echo "<script>window.open('../login','_self')</script>";
}


if(isset($_POST['sub_cat_id'])){
    $sub_cat_id = $_POST['sub_cat_id'];
    $get_sub_subcats = $db->select("cat_attribute", ["child_id" => $sub_cat_id]);
    echo '<option value="" class="hidden">Select A Sub Sub Category</option>';
    while($row_sub_subcats = $get_sub_subcats->fetch()){
        $sub_sub_cat_id = $row_sub_subcats->attr_id;

        $get_meta = $db->select("sub_subcategories", ["attr_id" => $sub_sub_cat_id, "language_id" => $siteLanguage]);
        $sub_sub_cat_title = $get_meta->fetch()->sub_subcategory_name;

        echo '<option value="'.$sub_sub_cat_id.'">'. $sub_sub_cat_title.'</option>';
    }
}
?>

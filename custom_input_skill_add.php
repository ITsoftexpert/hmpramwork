<?php
session_start();

require_once("includes/db.php");

if(isset($_POST['skill_sub_child_id'])){
    $skill_sub_id = $_POST['skill_sub_child_id'];
    $get_sub_subcats = $db->select("seller_skills", ["sub_child_id" => $skill_sub_id]);
    echo '<option value="" class="hidden">Select skill</option>';
    while($row_sub_subcats = $get_sub_subcats->fetch()){
        $sub_skill_sub_id = $row_sub_subcats->skill_id;
        $sub_sub_cat_title = $row_sub_subcats->skill_title;   
        echo '<option value="'.$sub_skill_sub_id.'">'. $sub_sub_cat_title.'</option>';
    }
}
?>

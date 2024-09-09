<?php
require_once("includes/db.php"); // Adjust to your database connection file

if (isset($_POST['sub_cat_id'])) {
    $sub_cat_id = $_POST['sub_cat_id'];
    $qProfessionalInfo = $db->select("professional_info", array("sub_category_id" => $sub_cat_id));
    
    if ($qProfessionalInfo->rowCount() > 0) {
        while ($professionalInfo = $qProfessionalInfo->fetch()) {
            echo '<div class="form-check">
                    <input class="form-check-input" type="checkbox" name="occupation[option_id][]" value="' . $professionalInfo->id . '" id="info_' . $professionalInfo->id . '">
                    <label class="form-check-label" for="info_' . $professionalInfo->id . '">
                        ' . $professionalInfo->title . '
                    </label>
                  </div>';
        }   
    } else {
        echo '<p>No professional info found</p>';
    }        
} else {
    echo '<p>Invalid sub-category</p>';
}
?>

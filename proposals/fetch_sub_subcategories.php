<?php

include("../includes/db.php");

// header('Content-Type: application/json');

if (isset($_POST['child_id'])) {
    $child_id = $_POST['child_id'];

    try {
        // Fetch sub-subcategories based on child_id
        $get_attr_cats = $db->select("cat_attribute", array("child_id" => $child_id));
        $sub_subcategories = [];

        while ($row_attr_cats = $get_attr_cats->fetch()) {
            $attr_id = $row_attr_cats->attr_id;
            $get_attr_meta = $db->select("sub_subcategories", array("attr_id" => $attr_id));
            $row_attr_meta = $get_attr_meta->fetch();

            if ($row_attr_meta) {
                $sub_subcategory_name = $row_attr_meta->sub_subcategory_name;
                $sub_subcategories[] = ['id' => $attr_id, 'name' => $sub_subcategory_name];
            }
        }

        // Return JSON response
        echo json_encode($sub_subcategories);
    } catch (Exception $e) {
        // Return error message as JSON
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    // If child_id is not provided
    echo json_encode(['error' => 'No child_id provided']);
}

exit; // Ensure no extra output beyond JSON data
?>

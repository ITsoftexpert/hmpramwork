<?php
require_once("includes/db.php");

// Check if the required POST variable is set
if (isset($_POST['subCategoryId'])) {
    $subCategoryId = $_POST['subCategoryId'];

    // Prepare a response array
    $response = [];

    // Query to fetch sub-category data based on subCategoryId
    $query = $db->prepare("SELECT * FROM sub_category_data_table WHERE sub_category_id = :subCategoryId");
    $query->bindParam(':subCategoryId', $subCategoryId, PDO::PARAM_INT);
    $query->execute();

    if ($query->rowCount() > 0) {
        // Fetch data and add to response
        while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
            $response[] = [
                'data_id' => $data['data_id'],
                'data_title' => $data['data_title'],
                'data_description' => $data['data_description']
                // Add any other fields you need from your data
            ];
        }
    } else {
        // Handle case where no data is found
        $response['error'] = 'No data found for the selected sub-category.';
    }

    // Output the response as JSON
    echo json_encode($response);
} else {
    // Handle case where 'subCategoryId' is not set
    echo json_encode(['error' => 'Invalid sub-category ID.']);
}
?>

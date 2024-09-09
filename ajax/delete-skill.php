<?php
$isAjax = 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');
if (!$isAjax) {
    die("No direct access.");
}

require_once("../includes/db.php");

$action = $_POST['action'];
$data = [];
$result = false;

switch ($action) {
    case "skills":
        $relationId = $_POST['id'];
        $deleted = $db->delete("skills_relation", array("relation_id" => $relationId));
        $result = $deleted->rowCount() == 1;
        $data = $result ? 'Deleted' : 'Failed';
        break;
    // Add more cases for other actions if needed
    default:
        $data['error'] = "Invalid action.";
        break;
}

header("Content-type: application/json");
echo json_encode(["data" => $data, "result" => $result]);
exit;
?>

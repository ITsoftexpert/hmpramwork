<?php

$isAjax = 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');
if (!$isAjax)
    die("No direct access.");

require_once("../includes/db.php");

$action = $_POST['action'];

$data = [];
$result = false;
switch ($action):
    case "get-category-option":
        $categoryId = $_POST['categoryId'];
        $query = $db->query("SELECT * FROM  professional_info WHERE category_id = :category_id AND status = :status ORDER BY 1", "", array("category_id" => $categoryId, "status" => 1));
        if ($query->rowCount() > 0) {
            $result = true;
            $data = $query->fetchAll();
        } else {
            $data['error'] = "0 record found";
        }
        break;
    default:
        $data['error'] = "Please specify action.";
endswitch;

header("Content-type: application/json");
echo json_encode(["data" => $data, "result" => $result]);
exit;

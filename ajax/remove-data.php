<?php

$isAjax = 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');
if (!$isAjax)
    die("No direct access.");

require_once("../includes/db.php");

$action = $_POST['action'];

$data = [];
$result = false;
switch ($action):
    case "skills":
        $relationId = $_POST['id'];
        $db->delete("skills_relation", array("relation_id" => $relationId));
        $result = true;
        $data = 'Deleted';
        break;
    case "offer-sent":
        $offerId = $_POST['id'];
        $db->delete("send_offers", array("offer_id" => $offerId));
        $result = true;
        $data = 'Deleted';
        break;
    default:
        $data['error'] = "Please specify action.";
endswitch;

header("Content-type: application/json");
echo json_encode(["data" => $data, "result" => $result]);
exit;

<?php

require_once("includes/db.php");
// $category = isset($_GET['category']) && $_GET['category'] != "" ? $_GET['category'] : null;
// $subCategory = isset($_GET['sub-category']) && $_GET['sub-category'] != "" ? $_GET['sub-category'] : null;

$rowSuggestions = "";
$qSuggestion = $db->select("proposals");
while ($oSuggestion = $qSuggestion->fetch()) {
    $rowSuggestions .= "" . $oSuggestion->proposal_tags . ", ";
}

// $rowSuggestions = substr($rowSuggestions , 0, -1);
// $suggestions = preg_split("/\,/", $rowSuggestions);
// array_filter($suggestions);
// $suggestions = array_unique($suggestions);

$rowSuggestions = preg_replace("/,([\s])+/", ",", $rowSuggestions);
$suggestions = explode(",", $rowSuggestions);

$suggestions = array_unique($suggestions);
$suggestions = array_values($suggestions);
$suggestions = array_filter($suggestions);

$data = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($suggestions as $suggestion) {
        if (strpos(strtolower($suggestion), strtolower($_POST['term'])) !== false) {
            $data[] = $suggestion;
        }
    }
} else {
    foreach ($suggestions as $suggestion) {
        if (strpos(strtolower($suggestion), strtolower($_GET['term'])) !== false) {
            $data[] = $suggestion;
        }
    }
}


header('Content-Type: application/json');
echo json_encode(['suggestions' => $data]);

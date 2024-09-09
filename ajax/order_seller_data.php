<?php

$isAjax = 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');
if (!$isAjax)
    die("No direct access.");

require_once("../includes/db.php");



//Get page number from Ajax POST
if (isset($_REQUEST["page"])) {
    $pageNumber = filter_var($_REQUEST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
    if (!is_numeric($pageNumber)) {
        die('Invalid page number!');
    } //incase of invalid page number
} else {
    $pageNumber = 1; //if there's no page number, set it to 1
}

$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
$sellerId = $_REQUEST['user_id'];
$status = $_REQUEST['status'];

$statusArray = ['yes', 'delivered', 'completed', 'cancelled'];
$noResult = "No records";
//get starting position to fetch the records
$pagePosition = (($pageNumber - 1) * $limit);

if (in_array($status, $statusArray)) {
    if ($status == 'yes') {
        $spQuery = "SELECT * FROM orders WHERE seller_id=:seller_id  AND order_active=:order_active";
        $sQuery = "SELECT * FROM orders WHERE seller_id=:seller_id  AND order_active=:order_active ORDER BY order_id DESC LIMIT " . $pagePosition . ", " . $limit;
        $sBind = ["seller_id" => $sellerId, "order_active" => $status];
        $noResult = $lang['selling_orders']['no_active'];
    } else {
        $spQuery = "SELECT * FROM orders WHERE seller_id=:seller_id  AND order_status=:order_status";
        $sQuery = "SELECT * FROM orders WHERE seller_id=:seller_id  AND order_status=:order_status ORDER BY order_id DESC LIMIT " . $pagePosition . ", " . $limit;
        $sBind = ["seller_id" => $sellerId, "order_status" => $status];
        $noResult = $status == 'delivered' ? $lang['selling_orders']['no_delivered'] : ($status == 'completed' ? $lang['selling_orders']['no_completed'] : ($status == 'cancelled' ? $lang['selling_orders']['no_cancelled'] : ''));
    }
} else {
    $spQuery = "SELECT * FROM orders WHERE seller_id=:seller_id";
    $sQuery = "SELECT * FROM orders WHERE seller_id=:seller_id ORDER BY order_id DESC LIMIT " . $pagePosition . ", " . $limit;
    $sBind = ["seller_id" => $sellerId];
    $noResult = $lang['selling_orders']['no_all'];
}

//get total number of records from database for pagination
$query = $db->query($spQuery, $sBind);
$rowCount = $query->rowCount();
$data = $query->fetch();

//break records into pages
$totalPages = ceil($rowCount / $limit);

//Limit our results within a specified range.
$query = $db->query($sQuery, $sBind);

if ($rowCount > 0) {

    //Display records fetched from database.
    $data = "";
    while ($oResult = $query->fetch()) { //fetch values
        $order_id = $oResult->order_id;
        $proposal_id = $oResult->proposal_id;
        $order_price = $oResult->order_price;
        $order_status = $oResult->order_status;
        $buyerId = $oResult->buyer_id;
        $order_number = $oResult->order_number;
        $order_duration = intval($oResult->order_duration);
        $order_date = $oResult->order_date;
        $order_due = date("F d, Y", strtotime($order_date . " + $order_duration days"));

        $select_proposals = $db->select("proposals", array("proposal_id" => $proposal_id));
        $row_proposals = $select_proposals->fetch();
        $proposal_title = $row_proposals->proposal_title;
        $proposal_img1 = getImageUrl2("proposals", "proposal_img1", $row_proposals->proposal_img1);

        $qOffers = $db->select("send_offers", array("sender_id" => $sellerId, "proposal_id" => $proposal_id));
        $cOffers = $qOffers->rowCount();
        $cRequests = 0;
        if ($cOffers > 0) {
            $oOffers = $qOffers->fetch();
            $requestId = $oOffers->request_id;

            $qRequests = $db->select("buyer_requests", array("request_id" => $requestId));
            $cRequests = $qRequests->rowCount();
            $oRequests = $qRequests->fetch();
            $requestTitle = $oRequests->request_title;
        }

        $data .= "<tr>";
        $data .= "<td>";
        $data .= "<a href='" . $site_url . "/order_details?order_id=" . $order_id . "' class='make-black'>
        <img class='order-proposal-image' src='" . $proposal_img1 . "'>
        <p class='order-proposal-title'>
        ";
        if ($cRequests > 0) {
            $data .= "<span class='d-block text-danger pb-1'>" . $requestTitle . "</span>";
        }
        $data .= $proposal_title . "</p>
        </a>";
        $data .= "</td>";
        $data .= "<td>" . $order_date . "</td>";
        $data .= "<td>" . $order_due . "</td>";
        $data .= "<td>" . showPrice($order_price) . "</td>";
        $data .= "<td><button class='btn btn-success'>" . ucwords($order_status) . "</button></td>";
        $data .= "</tr>";
    }

    /* We call the pagination function here to generate Pagination link for us.
As you can see I have passed several parameters to the function. */
    $paginationData = paginate($limit, $pageNumber, $rowCount, $totalPages);
} else {
    $data = "
    <tr class='table-danger'>
        <td colspan='5'><center><h3 class='pb-4 pt-4 heading_3'><i class='fa fa-meh-o'></i> {$noResult}</h3></center></td>
    </tr>
    ";
    $paginationData = null;
}

echo json_encode(["data" => $data, "pagination" => $paginationData]);
exit;

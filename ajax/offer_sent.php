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
$senderId = $_REQUEST['user_id'];

//get starting position to fetch the records
$pagePosition = (($pageNumber - 1) * $limit);
$whereLimit = " ORDER BY 1 DESC LIMIT $pagePosition, $limit";

$qPage =  $db->query("SELECT * FROM send_offers WHERE sender_id=:sender_id", array("sender_id" => $senderId));
$rowCount = $qPage->rowCount();

//break records into pages
$totalPages = ceil($rowCount / $limit);

if ($rowCount > 0) {
    $qOffers =  $db->query("SELECT * FROM send_offers WHERE sender_id=:sender_id $whereLimit", array("sender_id" => $senderId));
    //Display records fetched from database.
    $data = "";
    while ($oOffers = $qOffers->fetch()) {
        $request_id = $oOffers->request_id;
        $proposal_id = $oOffers->proposal_id;
        $description = $oOffers->description;
        $delivery_time = $oOffers->delivery_time;
        $amount = $oOffers->amount;

        $select_proposals = $db->select("proposals", array("proposal_id" => $proposal_id));
        $row_proposals = $select_proposals->fetch();
        $proposal_title = @$row_proposals->proposal_title;

        $get_requests = $db->select("buyer_requests", array("request_id" => $request_id));
        $row_requests = $get_requests->fetch();
        $request_id = $row_requests->request_id;
        $seller_id = $row_requests->seller_id;
        $cat_id = $row_requests->cat_id;
        $child_id = $row_requests->child_id;
        $request_title = $row_requests->request_title;
        $request_description = $row_requests->request_description;

        $get_meta = $db->select("cats_meta", array("cat_id" => $cat_id, "language_id" => $siteLanguage));
        $row_meta = $get_meta->fetch();
        $cat_title = $row_meta->cat_title;

        $get_meta = $db->select("child_cats_meta", array("child_id" => $child_id, "language_id" => $siteLanguage));
        $row_meta = $get_meta->fetch();
        $child_title = $row_meta->child_title;

        $select_request_seller = $db->select("sellers", array("seller_id" => $seller_id));
        $row_request_seller = $select_request_seller->fetch();
        $request_seller_user_name = $row_request_seller->seller_user_name;
        $request_seller_image = getImageUrl2("sellers", "seller_image", $row_request_seller->seller_image);

        $data .= "<tr class='offer_tr_{$oOffers->offer_id}'>";
        $data .= "<td>";
        if (!empty($request_seller_image))
            $data .= "<img src='{$request_seller_image}' class='request-img rounded-circle mt-0 contact-image'>";
        else
            $data .= '<img src="../user_images/empty-image.png" class="request-img rounded-circle mt-0 contact-image">';
        $data .= '<div class="request-description">
            <h6> ' . $request_seller_user_name . '</h6>
            <h5 class="text-success">' . $request_title . '</h5>
            <p class="lead mb-2">' . $request_description . '</p>
        ';
        if (!empty($request_file))
            $data .= '<a href="' . getImageUrl("buyer_requests", $request_file) . '" download>
            <i class="fa fa-arrow-circle-down"></i>' . $request_file . '
        </a>';
        $data .= '<ul class="request-category">
                <li>' . $cat_title . '</li>
                <li>' . $child_title . '</li>
            </ul>
        </div>';
        $data .= "</td>";
        $data .= '<td class="text-align-center">' . $delivery_time . '</td>
        <td class="text-align-center">' . $s_currency . $amount . '</td>
        <td class="width_45percent">
            <strong>' . $proposal_title . '</strong>
            <p>
               ' . $description . '
            </p>
            <p><a href="' . $site_url . '/offer-edit?id='. $oOffers->offer_id .'" class="text-primary">Edit</a> | <a href="javascript:;" class="text-danger withdrawOffer" data-id="' . $oOffers->offer_id . '">Withdraw</a> <p>
        </td>';
        $data .= "</tr>";
    }

    /* We call the pagination function here to generate Pagination link for us.
        As you can see I have passed several parameters to the function. */
        $paginationData = paginate($limit, $pageNumber, $rowCount, $totalPages);
} else {
    $data = "
    <tr class='table-danger'>
        <td colspan='5' class='box-shadow-head_3'><center><h3 class='pb-4 pt-4 heading_3'><i class='fa fa-meh-o'></i> You've sent any offer yet!</h3></center></td>
    </tr>
    ";
    $paginationData = null;
}

echo json_encode(["data" => $data, "pagination" => $paginationData]);
exit;

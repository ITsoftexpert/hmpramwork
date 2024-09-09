<?php

$isAjax = 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');
if (!$isAjax)
	die("No direct access.");

session_start();
require_once("../includes/db.php");

// Get page number from Ajax POST
if (isset($_REQUEST["page"])) {
	$pageNumber = filter_var($_REQUEST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
	if (!is_numeric($pageNumber)) {
		die('Invalid page number!');
	} //incase of invalid page number
} else {
	$pageNumber = 1; //if there's no page number, set it to 1
}

$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
$sellerId = $child_id = $input->post('user_id');

//get starting position to fetch the records
$pagePosition = (($pageNumber - 1) * $limit);

$select_login_seller = $db->select("sellers", array("seller_id" => $sellerId));
$row_login_seller = $select_login_seller->fetch();

$login_seller_user_name = $row_login_seller->seller_user_name;
$login_seller_id = $row_login_seller->seller_id;
$login_seller_offers = $row_login_seller->seller_offers;
$relevant_requests = $row_general_settings->relevant_requests;

$requests_query = get_buyer_request_query($login_seller_id);
if ($requests_query != "")
	$requests_query = " AND {$requests_query}";
	// if ($relevant_requests == "no") {
	// 	$requests_query = "";
	// }
; 


$data = "
		<tr class='table-danger '>
			<td colspan='5' class='box-shadow-bg-clr'><center><h3 class='pb-4 pt-4 heading_3'>
			Please create a proposal in order to find relevant job. <a href='{$site_url}/proposals/create_proposal' class='text-info'>Click here</a> to create proposal
		</h3></center></td>
		</tr>
	"; 
$paginationData = null;
$total = 0;
// if (!empty($requests_query) or $relevant_requests == "no") {
if (!empty($requests_query)) {
	$child_id = $input->post('child_id');
	$search = $input->post('search');
	$searchWhere = "";
	if ($search != '') {
		$searchWhere = " AND request_description LIKE '%{$search}%'";
	}
	if ($child_id == "all") {
		$spQuery = "SELECT * FROM buyer_requests WHERE request_status=:request_status {$requests_query} {$searchWhere} AND seller_id !=:seller_id";
		$sQuery = "SELECT * FROM buyer_requests WHERE request_status=:request_status {$requests_query} {$searchWhere} AND seller_id !=:seller_id ORDER BY 1 DESC LIMIT " . $pagePosition . ", " . $limit;
		$sBind = ["seller_id" => $sellerId, "request_status" => 'active'];
	} else {
		$spQuery = "SELECT * FROM buyer_requests WHERE request_status=:request_status AND child_id=:child_id {$searchWhere} AND seller_id !=:seller_id";
		$sQuery = "SELECT * FROM buyer_requests WHERE request_status=:request_status AND child_id=:child_id {$searchWhere} AND seller_id !=:seller_id ORDER BY 1 DESC LIMIT " . $pagePosition . ", " . $limit;
		$sBind = ["seller_id" => $sellerId, "child_id" => $child_id, "request_status" => 'active'];
	}

	//get total number of records from database for pagination
	$query = $db->query($spQuery, $sBind);
	$total = $query->rowCount();

	//Limit our results within a specified range.
	$query = $db->query($sQuery, $sBind);

	if ($total > 0) {
		//Display records fetched from database.
		$data = "";
		while ($oResult = $query->fetch()) { //fetch values
			$request_id = $oResult->request_id;
			$seller_id = $oResult->seller_id;
			$cat_id = $oResult->cat_id;
			$child_id = $oResult->child_id;
			$request_title = $oResult->request_title;
			$request_description = $oResult->request_description;
			$delivery_time = $oResult->delivery_time;
			$request_budget = $oResult->request_budget;
			$request_file = $oResult->request_file;
			$request_date = $oResult->request_date;

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

			$count_send_offers = $db->count("send_offers", array("request_id" => $request_id));
			$count_offers = $db->count("send_offers", array("request_id" => $request_id, "sender_id" => $login_seller_id));

			// if ($count_offers == 0) {
			$data .= "<tr id='request_tr_{$request_id}'>";
			$data .= "<td>";
			if (!empty($request_seller_image)) {
				$data .= "<img src='{$request_seller_image}' class='request-img rounded-circle'>";
			} else {
				$data .= "<img src='{$site_url}/user_images/empty-image.png' class='request-img rounded-circle'>";
			}
			$data .= "<div class='request-description'><!-- request-description Starts -->
			<h6> {$request_seller_user_name} </h6>
			<h5 class='text-success'> {$request_title} </h5>
			<p class='lead mb-2'> {$request_description} </p>";
			if (!empty($request_file)) {
				$data .= "<a href='" . getImageUrl('buyer_requests', $request_file) . "' download>
					<i class='fa fa-arrow-circle-down'></i> {$request_file}
				</a>";
			}
			$data .= "<ul class='request-category'>
				<li> {$cat_title}</li>
				<li> {$child_title} </li>
				</ul>
			</div>";
			$data .= "</td>";
			$data .= "<td class='text-align-center'>{$count_send_offers}</td>";
			$data .= "<td class='text-align-center'>{$request_date}</td>";
			if (strpos($delivery_time, 'days') === false) {
				$delivery_time .= ' days';
			}
			$data .= "<td class='text-align-center'>{$delivery_time} <a href='#' class='remove-link remove_request' data-remove-id='{$request_id}'> Remove Request </a></td>";
			$data .= "<td class='text-info font-weight-bold text-align-center'>$";
			if (!empty($request_budget))
				$data .= $request_budget;
			else
				$data .= "---";
			$data .= "<br />";

			if ($login_seller_offers == "0") {
				$data .= "<button class='btn btn-success btn-sm mt-4 send_button' data-send-id='0'>
						{$lang['button']['send_offer']}
					</button>";
			} else {
				$data .= "<button class='btn btn-success btn-sm mt-4 send_button' data-send-id='{$request_id}'>
						{$lang['button']['send_offer']}
					</button>";
			}
			$data .= "</td>";
			$data .= "</tr>";
			// $total++;
			// } // count_offers
		}

		//break records into pages
		$totalPages = ceil($total / $limit);

		$paginationData = paginate($limit, $pageNumber, $total, $totalPages);
	} else {
		$qProposals = $db->query("SELECT proposal_id FROM `proposals` where proposal_seller_id ='$sellerId' and proposal_status='active' ORDER BY 1 DESC");
		$cProposals = $qProposals->rowCount();
		if ($cProposals > 0)
			$data = "
				<tr class='table-danger'>
					<td colspan='5'><center><h3 class='pb-4 pt-4'><i class='fa fa-frown-o'></i> No requests that matches any of your proposals/services yet!.</h3></center></td>
				</tr>
			";
	}
}
echo json_encode(["data" => $data, "pagination" => $paginationData, "total" => $total]);
exit;

<?php

function base_url($url = '')
{
   global $site_url;
   echo $site_url . "/" . $url;
}

function showPrice($price, $class = '', $show_symbol = '')
{

   global $db;
   global $s_currency;
   global $currency_position;
   global $currency_format;

   if ($show_symbol == '') {
      $show_symbol = 'yes';
   }

   if (isset($_SESSION['siteCurrency'])) {

      $id = $_SESSION['siteCurrency'];
      $get_currency = $db->select("site_currencies", array('id' => $id));
      $row = $get_currency->fetch();
      $currency_id = $row->currency_id;
      $currency_position = $row->position;
      $currency_format = $row->format;
      $rate = $_SESSION['conversionRate'];

      $get_currencies = $db->select("currencies", array("id" => $currency_id));
      $row_currencies = $get_currencies->fetch();
      $site_currency = $row_currencies->symbol;
      $price = $price * $rate;
   } else {
      $site_currency = $s_currency;
   }

   // $price = $price / 100;

   $dec_point = '.';
   $thousands_sep = ',';
   if ($currency_format == 'european') {
      $dec_point = ',';
      $thousands_sep = '.';
   }
   if (is_int($price)) {
      $price = number_format($price, 2, $dec_point, $thousands_sep);
   } else {
      $price = number_format((float)$price, 2, $dec_point, $thousands_sep);
   }

   if (!empty($class)) {
      $price = "<span class='$class'>$price</span>";
   }

   if ($show_symbol == 'yes') {

      if ($currency_position == "left") {
         return $site_currency . $price;
      } else {
         return $price . $site_currency;
      }
   } else {
      return $price;
   }
}

function redirect($url)
{
   echo "<script>window.open('$url','_self');</script>";
}

function successRedirect($text, $url)
{
   echo "<script>alert_success('$text','$url');</script>";
}

function messageRedirect($text, $url)
{
   echo "<script>
	alert('$text');
	window.open('$url','_self');
	</script>";
}

function showMessage($text)
{
   echo "<script>alert('$text')</script>";
}

function sendPushMessage($notification_id)
{

   global $site_url;

   /// Added by Pixinal Studio For Push Notification
   $curl = curl_init();
   curl_setopt_array($curl, array(
      CURLOPT_URL => "$site_url/api/v1/single-notification/" . $notification_id,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET"
   ));
   $response = curl_exec($curl);
   $err = curl_error($curl);
   curl_close($curl);
   /// End For Push Notification

}

// Check if we are in a local environment
function is_localhost()
{

   // set the array for testing the local environment
   $whitelist = array('127.0.0.1', '::1');

   // check if the server is in the array
   if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
      // this is a local environment
      return true;
   }
}


/// Check Purchase Code

function check_purchase()
{
   return true;

   global $db;

   $get_app_license = $db->select("app_license");
   $row_app_license = $get_app_license->fetch();
   $purchase_code = $row_app_license->purchase_code;
   $license_type = $row_app_license->license_type;
   $website = $row_app_license->website;

   $curl = curl_init();
   curl_setopt_array($curl, array(
      CURLOPT_URL => "https://www.gigtodo.com/purchase-code-management-system/admin/check_purchase/",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => array('purchase_code' => $purchase_code, 'license_type' => $license_type, 'website' => $website),
   ));

   $response = curl_exec($curl);
   curl_close($curl);

   return true;
}

/**
 * Truncates the given string at the specified length.
 *
 * @param string $str The input string.
 * @param int $width The number of chars at which the string will be truncated.
 * @return string
 */
function truncate($str, $width = 25)
{
   return strtok(wordwrap($str, $width, "...\n"), "\n");
}

/**
 * Pagination function
 *
 * @param [type] $itemPerPage
 * @param [type] $currentPage
 * @param [type] $totalRecords
 * @param [type] $totalPages
 * @return void
 */
function paginate($itemPerPage, $currentPage, $totalRecords, $totalPages)
{
   $pagination = '';
   if ($totalPages > 0 && $totalPages != 1 && $currentPage <= $totalPages) { //verify total pages and current page number
      $pagination .= '<ul class="pagination justify-content-center">';

      $right_links = $currentPage + 3;
      $previous = $currentPage - 3; //previous link
      $nex = $currentPage + 1; //next link
      $first_link = true; //boolean var to decide our first link

      if ($currentPage > 1) {
         $previous_link = ($previous == 0) ? 1 : $previous;
         $pagination .= '<li class="page-item first"><a class="page-link" href="#" data-page="1" title="First">&laquo;</a></li>'; //first link
         $pagination .= '<li class="page-item"><a class="page-link" href="#" data-page="' . abs($previous_link) . '" title="Previous">&lt;</a></li>'; //previous link
         for ($i = ($currentPage - 2); $i < $currentPage; $i++) { //Create left-hand side links
            if ($i > 0) {
               $pagination .= '<li class="page-item"><a class="page-link" href="#" data-page="' . $i . '" title="Page' . $i . '">' . $i . '</a></li>';
            }
         }
         $first_link = false; //set first link to false
      }

      if ($first_link) { //if current active page is first link
         $pagination .= '<li class="page-item first active disabled"><a class="page-link" href="#">' . $currentPage . '<span class="sr-only">(current)</span></li>';
      } elseif ($currentPage == $totalPages) { //if it's the last active link
         $pagination .= '<li class="page-item last active disabled"><a class="page-link" href="#">' . $currentPage . '<span class="sr-only">(current)</span></li>';
      } else { //regular current link
         $pagination .= '<li class="page-item active disabled"><a class="page-link" href="#">' . $currentPage . '</a></li>';
      }

      for ($i = $currentPage + 1; $i < $right_links; $i++) { //create right-hand side links
         if ($i <= $totalPages) {
            $pagination .= '<li class="page-item"><a class="page-link" href="#" data-page="' . $i . '" title="Page ' . $i . '">' . $i . '</a></li>';
         }
      }
      if ($currentPage < $totalPages) {
         $next_link = ($i > $totalPages) ? $totalPages : $i;
         $pagination .= '<li class="page-item"><a class="page-link" href="#" data-page="' . $next_link . '" title="Next">&gt;</a></li>'; //next link
         $pagination .= '<li class="page-item last"><a class="page-link" href="#" data-page="' . $totalPages . '" title="Last">&raquo;</a></li>'; //last link
      }

      $pagination .= '</ul>';
   }
   return $pagination; //return pagination links
}


function pagination($itemPerPage, $currentPage, $totalRecords, $totalPages, $url)
{
   // echo $currentPage . PHP_EOL . $totalRecords . PHP_EOL . $totalPages;
   $pagination = '';

   if ($totalPages > 0 && $totalPages != 1 && $currentPage <= $totalPages) { //verify total pages and current page number
      $pagination .= '<ul class="pagination justify-content-center">';

      $right_links = $currentPage + 3;
      $previous = $currentPage - 3; //previous link
      $nex = $currentPage + 1; //next link
      $first_link = true; //boolean var to decide our first link

      if ($currentPage > 1) {
         $previous_link = ($previous == 0) ? 1 : $previous;
         $pagination .= '<li class="page-item first"><a class="page-link" href="' . $url . '1" title="First">&laquo;</a></li>'; //first link
         $pagination .= '<li class="page-item"><a class="page-link" href="' . $url . abs($previous_link) . '" title="Previous">&lt;</a></li>'; //previous link
         for ($i = ($currentPage - 2); $i < $currentPage; $i++) { //Create left-hand side links
            if ($i > 0) {
               $pagination .= '<li class="page-item"><a class="page-link" href="' . $url . $i . '" title="Page' . $i . '">' . $i . '</a></li>';
            }
         }
         $first_link = false; //set first link to false
      }

      if ($first_link) { //if current active page is first link
         $pagination .= '<li class="page-item first active disabled"><a class="page-link" href="' . $url . $currentPage . '">' . $currentPage . '<span class="sr-only">(current)</span></li>';
      } elseif ($currentPage == $totalPages) { //if it's the last active link
         $pagination .= '<li class="page-item active last disabled"><a href="' . $url . $currentPage . '" class="page-link">' . $currentPage . '</a></li>';
      } else { //regular current link
         $pagination .= '<li class="page-item active disabled"><a class="page-link" href="' . $url . $currentPage . '">' . $currentPage . '</a></li>';
      }

      for ($i = $currentPage + 1; $i < $right_links; $i++) { //create right-hand side links
         if ($i <= $totalPages) {
            $pagination .= '<li class="page-item"><a class="page-link" href="' . $url . $i . '" title="Page ' . $i . '">' . $i . '</a></li>';
         }
      }
      if ($currentPage < $totalPages) {
         $next_link = ($i > $totalPages) ? $totalPages : $i;
         $pagination .= '<li class="page-item"><a class="page-link" href="' . $url . $next_link . '" title="Next">&gt;</a></li>'; //next link
         $pagination .= '<li class="page-item last"><a class="page-link" href="' . $url . $totalPages . '" title="Last">&raquo;</a></li>'; //last link
      }

      $pagination .= '</ul>';
   }
   return $pagination; //return pagination links
}

// Membership checking
/**
 * get membership data
 *
 * @param Int $userId
 * @return array
 */
function get_memebership_data($userId)
{
   global $db;

   $qUser = $db->select("sellers", ["seller_id" => $userId]);
   $oUser = $qUser->fetch();

   $numGigs = $oUser->no_of_gigs;

   $qMember = $db->select("`memb_plan_detail` mp LEFT JOIN `membership_table` m ON mp.memb_tbl_id = m.id WHERE mp.seller_id = {$userId} AND mp.memb_status = 'Active' AND mp.memb_end_date >= CURRENT_TIMESTAMP ORDER BY mp.id DESC LIMIT 1");
   $oMember = $qMember->fetch();

   if ($oMember) {
      $startDate = $oMember->memb_start_date;
      $endDate = $oMember->memb_start_date;
      $gigsPerLife = $oMember->create_active_service;
      $bidsPerMonth = $oMember->bids_per_month;
      $skillsPerLife = $oMember->skills;
      // $getTotalProposals = $db->query("SELECT count(*) as total FROM `proposals` where proposal_seller_id = {$userId} AND proposal_status = 'active' AND (created_at BETWEEN '{$startDate}' AND '{$endDate}')");
      // $getTotalProposals = $db->query("SELECT count(*) as total FROM `proposals` where proposal_seller_id = {$userId} AND proposal_status = 'active' AND created_at >= '{$startDate}' AND created_at <= '{$endDate}'");
      $getTotalProposals = $db->query("SELECT count(*) as total FROM `proposals` where proposal_seller_id = {$userId} AND proposal_status = 'active'");
      $objTotalProposals = $getTotalProposals->fetch();
      $totalProposal = $objTotalProposals->total;
   } else {
      // Basic
      $qMember = $db->select("`membership_table`", ['id' => 1]);
      $oMember = $qMember->fetch();
      $gigsPerLife = $oMember ? $oMember->create_active_service : 1;
      $bidsPerMonth = $oMember ? $oMember->bids_per_month : 20;
      $skillsPerLife = $oMember ? $oMember->skills : 20;
      // update seller information
      $getTotalProposals = $db->query("SELECT count(*) as total FROM `proposals` where proposal_seller_id = {$userId} AND proposal_status = 'active'");
      $objTotalProposals = $getTotalProposals->fetch();
      $totalProposal = $objTotalProposals->total;
      $startDate = date("Y-m-d", strtotime("first day of this month")) . " 23:59:59";
      $endDate = date("Y-m-d", strtotime("last day of this month")) . " 23:59:59";
   }

   // COUNTS BIDS MONTHLY
   $getTotalOffersSent = $db->query("SELECT count(*) as total FROM `send_offers` where sender_id = {$userId} AND (created_at BETWEEN '{$startDate}' AND '{$endDate}')");
   $objTotalOfferSent = $getTotalOffersSent->fetch();
   $totalOfferSent = $objTotalOfferSent->total;
   // echo $bidsPerMonth . PHP_EOL . $totalOfferSent . PHP_EOL. $totalOfferSent >= $bidsPerMonth;

   // SKILLS
   $totalSkillsAdded = $db->count("skills_relation", ["seller_id" => $userId]);

   $data = [];
   $data['pending_gig'] = $totalProposal >= $gigsPerLife ? 0 : $gigsPerLife - $totalProposal;
   $data['pending_offer'] = $totalOfferSent >= $bidsPerMonth ? 0 : $bidsPerMonth - $totalOfferSent;
   $data['pending_skills'] = $totalSkillsAdded >= $skillsPerLife ? 0 : $skillsPerLife - $totalSkillsAdded;

   return $data;
}

// die and dump
function dd($data)
{
   echo "<pre>";
   print_r($data);
   exit;
}

// var dump
function dump($data)
{
   echo "<pre>";
   print_r($data);
}

// get seller info
function get_seller_info($userId)
{
   global $db;

   $select_login_seller = $db->select("sellers", ["seller_id" => $userId]);
   return $select_login_seller->fetch();
}

function get_buyer_request_query($userId)
{
   global $db;

   $query = "";

   $request_child_ids = [];

   $select_proposals = $db->query("SELECT DISTINCT proposal_child_id FROM `proposals` WHERE proposal_seller_id = {$userId} AND proposal_status = 'active' ORDER BY proposal_child_id;");
   if ($select_proposals->rowCount() > 0) {
      while ($row_proposals = $select_proposals->fetch()) {
         $proposal_child_id = $row_proposals->proposal_child_id;
         array_push($request_child_ids, $proposal_child_id);
      }
      $ids = implode(', ', $request_child_ids);
      $query = "child_id IN ({$ids})";
   }

   return $query;
}

// Here is a sample of the URLs this regex matches: (there can be more content after the given URL that will be ignored)

// http://youtu.be/dQw4w9WgXcQ
// http://www.youtube.com/embed/dQw4w9WgXcQ
// http://www.youtube.com/watch?v=dQw4w9WgXcQ
// http://www.youtube.com/?v=dQw4w9WgXcQ
// http://www.youtube.com/v/dQw4w9WgXcQ
// http://www.youtube.com/e/dQw4w9WgXcQ
// http://www.youtube.com/user/username#p/u/11/dQw4w9WgXcQ
// http://www.youtube.com/sandalsResorts#p/c/54B8C800269D7C1B/0/dQw4w9WgXcQ
// http://www.youtube.com/watch?feature=player_embedded&v=dQw4w9WgXcQ
// http://www.youtube.com/?feature=player_embedded&v=dQw4w9WgXcQ

// It also works on the youtube-nocookie.com URL with the same above options.
// It will also pull the ID from the URL in an embed code (both iframe and object tags)
function get_youtube_id($url)
{
   preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
   return $match[1];
}

function get_youtube_thumnail($YTId)
{
   return '<img src="https://img.youtube.com/vi/' . $YTId . '/hqdefault.jpg" width="250" />';
}

function getDevice()
{
   $u_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
   $operating_system = 'Unknown Device';

   //Get the operating_system name
   if ($u_agent) {
      if (preg_match('/linux/i', $u_agent)) {
         $operating_system = 'Linux';
      } elseif (preg_match('/macintosh|mac os x|mac_powerpc/i', $u_agent)) {
         $operating_system = 'Mac';
      } elseif (preg_match('/windows|win32|win98|win95|win16/i', $u_agent)) {
         $operating_system = 'Windows';
      } elseif (preg_match('/ubuntu/i', $u_agent)) {
         $operating_system = 'Ubuntu';
      } elseif (preg_match('/iphone/i', $u_agent)) {
         $operating_system = 'IPhone';
      } elseif (preg_match('/ipod/i', $u_agent)) {
         $operating_system = 'IPod';
      } elseif (preg_match('/ipad/i', $u_agent)) {
         $operating_system = 'IPad';
      } elseif (preg_match('/android/i', $u_agent)) {
         $operating_system = 'Android';
      } elseif (preg_match('/blackberry/i', $u_agent)) {
         $operating_system = 'Blackberry';
      } elseif (preg_match('/webos/i', $u_agent)) {
         $operating_system = 'Mobile';
      }
   } else {
      $operating_system = php_uname('s');
   }

   return $operating_system;
}

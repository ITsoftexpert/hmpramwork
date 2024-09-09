<?php (defined('BASEPATH')) or exit('No direct script access allowed');

function dd($data)
{
  echo "<pre>";
  print_r($data);
  exit;
}

function getGeoFromIP($ip)
{
  return unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip));
}

function sendEmail($to, $from, $subject, $message = "", $template = "", $tplData = [])
{
  $ci = &get_instance();

  $ci->load->config('email');
  $ci->load->library('email');
  $ci->email->set_newline("\r\n");

  $to_email = is_array($to) ? $to['to_email'] : $to;
  $to_name = is_array($to) ? $to['to_name'] : $to;
  $from_email = is_array($from) ? $from['from_email'] : $from;
  $from_name = is_array($from) ? $from['from_name'] : $from;

  $ci->email->from($from_email, $from_name);
  $ci->email->to($to_email, $to_name);
  $ci->email->subject($subject);

  if ($template != "") {
    $message = loadEmailTemplate($template, $tplData);
  }
  $ci->email->message($message);

  if ($ci->email->send()) {
    return true;
  } else {
    show_error($ci->email->print_debugger());
  }
}

function proceedSendEmails($userData, $adminData = [])
{
  $ci = &get_instance();

  $ci->load->library('email');
  $ci->load->model('general_model');

  $generailSetting = $ci->general_model->getGenerailSetting();

  $to['to_email'] = $userData['to'];
  $to['to_name'] = $userData['seller_user_name'];
  $from['from_email'] = isset($userData['from']) ? $userData['from'] : $generailSetting->site_email_address;
  $from['from_name'] = isset($userData['from_name']) ? $userData['from_name'] : $generailSetting->site_name;
  $subject = $userData['subject'];
  $template = $userData['template'];

  // EMAIL CONFIG
  $userData['site_url'] = $generailSetting->site_url;
  $userData['site_logo'] = $generailSetting->site_logo;
  $userData['site_name'] = $generailSetting->site_name;

  return sendEmail($to, $from, $subject, '', $template, $userData);

  // TODO NOTIFY IF $adminData has data
  //   $admins = $ci->general_model->getAdmins();
  // dd($admins->num_rows());
  // result_object
}

function loadEmailTemplate($file, $data = [])
{
  $ci = &get_instance();

  $main2 = ['email_confirmation', 'password_reset'];
  if (in_array($data['template'], $main2))
    $file = "main2";
  else
    $file = "main";


  return $ci->load->view("emails/{$file}.php", $data, TRUE);
}

function img_url($name)
{
  $ci = &get_instance();

  $generailSetting = $ci->general_model->getGenerailSetting();

  echo $generailSetting->site_url . "/images/email/" . $name;
}

function paginate($totalPages, $url)
{
  // Now start looping
  $baseUrl = [];
  for ($i = 1; $i <= $totalPages; $i++) {
    // And set the new URL for the next page
    $baseUrl[] = $url . $i;
  }

  return ($baseUrl);
}

function getImageUrl2($table, $field, $key)
{
  $ci = &get_instance();

  $field2 = $field . '_s3';
  $folder = getFolderName($table);

  if ($field == "seller_cover_image") {
    $folder = "cover_images";
  }

  $query = $ci->db->get_where($table, ["$field" => $key]);
  $row = $query->row();
  $isS3 = $row->$field2;

  if ($isS3 == 1) {
    return "$folder/$key";
  } else {

    if (empty($key)) {
      $key = "empty-image.png";
    }

    $main_folder = getMainFolderName($folder, $table);

    $key = rawurlencode($key);

    return "$main_folder/$folder/$key";
  }
}

function getFolderName($table)
{

  if ($table == "admins") {
    return "admin/admin_images";
  } elseif ($table == "general_settings") {
    return "images";
  } elseif ($table == "sellers") {
    return "user_images";
  } elseif ($table == "categories") {
    return "cat_images";
  } elseif ($table == "post_categories") {
    return "blog_cat_images";
  } elseif ($table == "proposals") {
    return "proposal_files";
  } elseif ($table == "order_conversations") {
    return "order_files";
  } elseif ($table == "instant_deliveries") {
    return "order_files";
  } elseif ($table == "inbox_messages") {
    return "conversations_files";
  } elseif ($table == "buyer_requests") {
    return "request_files";
  } elseif ($table == "languages") {
    return "images";
  } elseif ($table == "section_boxes") {
    return "box_images";
  } elseif ($table == "home_cards") {
    return "card_images";
  } elseif ($table == "home_section_slider") {
    return "home_slider_images";
  } elseif ($table == "slider") {
    return "slides_images";
  } elseif ($table == "support_tickets") {
    return "ticket_files";
  } elseif ($table == "support_conversations") {
    return "ticket_files";
  } elseif ($table == "knowledge_bank") {
    return "article_images";
  } elseif ($table == "posts") {
    return "post_images";
  }
}

function getMainFolderName($folder, $table)
{
  if ($folder == "proposal_files") {
    $main_folder = "proposals";
  } elseif ($folder == "request_files") {
    $main_folder = "requests";
  } elseif ($folder == "conversations_files") {
    $main_folder = "conversations";
  } elseif ($folder == "images" and $table == "languages") {
    $main_folder = "languages";
  } elseif ($folder == "article_images") {
    $main_folder = "article";
  } elseif ($folder == "admin_images") {
    $main_folder = "admin";
  } else {
    $main_folder = "";
  }

  return $main_folder;
}

function get_seller_info($userId)
{
  $ci = &get_instance();

  $select_login_seller = $ci->db->get_where("sellers", ["seller_id" => $userId]);
  return $select_login_seller->row();
}

function insert_log($admin_id, $work, $work_id, $status)
{
  $ci = &get_instance();
  $date = date("F d, Y H:i:s");

  $data = array(
    'admin_id' => $admin_id,
    'work' => $work,
    'work_id' => $work_id,
    'date' => $date,
    'status' => $status
  );

  return $ci->db->insert('admin_logs', $data);
}

function get_general_settings()
{
  $ci = &get_instance();

  $query = $ci->db->get("general_settings");
  return $query->row();
}

function searchQueryWhere($type, $filterType, $params)
{
  $online_sellers = [];
  $where_online = [];
  $where_country = "";
  $where_city = "";
  $where_level = "";
  $where_language = "";
  $values = [];
  $where_path = "";
  $where_cat = "";
  $where_delivery_times = "";
  $instant_delivery = $params['instant_delivery'] != "" ? 1 : 0;

  $ci = &get_instance();

  // If need to check online sellers
  if ($params['online_sellers'] != "") {
    $qSellers = $ci->db->get_where("sellers", ['seller_status' => "online"]);
    if ($qSellers->num_rows() > 0) {
      $oSellers = $qSellers->result_object();
      foreach ($oSellers as $oSeller) {
        array_push($online_sellers, $oSeller->seller_id);
      }

      if (count($online_sellers) > 0) {
        $where_online[] = "sellers.seller_id";
        $values['seller_id'] = implode(', ', $online_sellers);
      }
    }
  }

  if (is_array($params['country'])) {
    $where_country = "sellers.seller_country";
    $values['seller_country'] = "'" . implode("', '", $params['country']) . "'";
  }

  if (is_array($params['city'])) {
    $where_city = "sellers.seller_city";
    $values['seller_city'] = "'" . implode("', '", $params['city']) . "'";
  }

  if ($params['category_id'] != "") {
    $where_cat = "proposals.proposal_cat_id";
    $values['proposal_cat_id'] = is_array($params['category_id']) ? implode(', ', $params['category_id']) : $params['category_id'];
  }

  if (is_array($params['delivery_time_id'])) {
    $where_delivery_times = "delivery_id";
    $values['delivery_id'] = implode(', ', $params['delivery_time_id']);
  }

  if (is_array($params['seller_level_id'])) {
    $where_level = "level_id";
    $values['level_id'] = implode(', ', $params['seller_level_id']);
  }

  if (is_array($params['seller_language_id'])) {
    $where_language = "language_id";
    $values['language_id'] = implode(', ', $params['seller_language_id']);
  }

  $queryWhere = "WHERE proposals.proposal_status='active'";

  if ($filterType == "search") {
    $s = $params['s'];
    $queryWhere .= " AND proposals.proposal_title LIKE '%$s%' ";
  }

  if (count($where_online) > 0)
    $queryWhere .= addConcat($queryWhere) . " sellers.seller_id IN ({$values['seller_id']})";
  if ($instant_delivery == 1)
    $queryWhere .= " AND instant_deliveries.enable=1";
  if ($where_country != "")
    $queryWhere .= " AND {$where_country} IN ({$values['seller_country']})";
  if ($where_city != "")
    $queryWhere .= " AND {$where_city} IN ({$values['seller_city']})";
  if ($where_cat != "")
    $queryWhere .= " AND {$where_cat} IN ({$values['proposal_cat_id']})";
  if ($where_delivery_times != "")
    $queryWhere .= " AND {$where_delivery_times} IN ({$values['delivery_id']})";
  if ($where_level != "")
    $queryWhere .= " AND {$where_level} IN ({$values['level_id']})";
  if ($where_language != "")
    $queryWhere .= " AND {$where_language} IN ({$values['language_id']})";

  if ($type == "query_where")
    return $queryWhere;
  elseif ($type == "where_path")
    return $where_path;
  elseif ($type == "values")
    return $values;
}

function addConcat($query)
{
  if (strlen($query) == 5) {
    return "";
  } else {
    return " AND";
  }
}

function freelancersQueryWhere($type, $params)
{
  $ci = &get_instance();

  $online_sellers = [];
  $where_online = "";
  $where_level = "";
  $where_language = "";
  $where_skill = "";
  $values = [];
  $where_path = "";

  if ($params['online_sellers'] != "") {
    $qSellers = $ci->db->get_where("sellers", ['seller_status' => "online"]);
    if ($qSellers->num_rows() > 0) {
      $oSellers = $qSellers->result_object();
      foreach ($oSellers as $oSeller) {
        array_push($online_sellers, $oSeller->seller_id);
      }

      if (count($online_sellers) > 0) {
        if (count($online_sellers) > 0) {
          $where_online = "s.seller_id";
          $values['seller_id'] = implode(', ', $online_sellers);
        }
      }
    }
  }

  if (is_array($params['seller_level_id'])) {
    $where_level = "s.seller_level";
    $values['seller_level'] = "'" . implode("', '", $params['seller_level_id']) . "'";
  }

  if (is_array($params['seller_language_id'])) {
    $where_language = "s.seller_language";
    $values['seller_language'] = "'" . implode("', '", $params['seller_language_id']) . "'";
  }

  if (is_array($params['skill_id'])) {
    // Use implode() function to join
    $aSkills = $params['skill_id'];
    $skills = array_filter($aSkills);
    // comma in the array
    $where_skill = implode(', ', $skills);
    foreach ($skills as $value)
      $where_path .= "seller_skills[]=" . $value . "&";
  }

  $query_where = "WHERE";

  if ($where_online != "")
    $query_where .= addConcat($query_where) . " {$where_online} IN ({$values['seller_id']})";

  if ($where_level != "")
    $query_where .= addConcat($query_where) . " {$where_level} IN ({$values['seller_level']})";

  if ($where_language != "")
    $query_where .= addConcat($query_where) . " {$where_language} IN ({$values['seller_language']})";

  if ($type == "query_where") {
    return [$query_where != "WHERE" ? $query_where : "", $where_skill];
  } elseif ($type == "where_path") {
    return $where_path;
  } elseif ($type == "values") {
    return $values;
  }
}

function proposalUrl($string, $space = "-")
{
  if (preg_match('/[اأإء-ي]/ui', $string)) {
    return urlencode($string);
  } else {

    if (preg_match('/[\p{Cyrillic}]/u', $string)) {
      $string = str_replace(" ", "-", $string);
      return urlencode($string);
    } else {

      $turkcefrom = array("/Ğ/", "/Ü/", "/Ş/", "/İ/", "/Ö/", "/Ç/", "/ğ/", "/ü/", "/ş/", "/ı/", "/ö/", "/ç/");
      $turkceto = array("G", "U", "S", "I", "O", "C", "g", "u", "s", "i", "o", "c");

      $string = utf8_encode($string);
      if (function_exists('iconv')) {
        // $string = iconv('UTF-8', 'ASCII//TRANSLIT', mb_strtolower($string));
      }

      $string = preg_replace("/[^a-zA-Z0-9 \-]/", "", $string);
      $string = trim(preg_replace("/\\s+/", " ", $string));
      $string = strtolower($string);
      $string = str_replace(" ", $space, $string);

      $string = preg_replace("/[^0-9a-zA-ZÄzÜŞİÖÇğüşıöç]/", " ", $string);
      $string = preg_replace($turkcefrom, $turkceto, $string);
      $string = preg_replace("/ +/", " ", $string);
      $string = preg_replace("/ /", "-", $string);
      $string = preg_replace("/\s/", "", $string);
      $string = strtolower($string);
      $string = preg_replace("/^-/", "", $string);
      $string = preg_replace("/-$/", "", $string);
      return $string;
    }
  }
}

function insertPackages($proposalId)
{
  $ci = &get_instance();

  $ci->db->insert("proposal_packages", ["proposal_id" => $proposalId, "package_name" => 'Basic', "price" => 5]);
  $ci->db->insert("proposal_packages", ["proposal_id" => $proposalId, "package_name" => 'Standard', "price" => 10]);
  $ci->db->insert("proposal_packages", ["proposal_id" => $proposalId, "package_name" => 'Advance', "price" => 15]);

  return true;
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/controllers/api/APIAuth.php';

/**
 * Search class.
 *
 * @extends REST_Controller
 */
class Search extends APIAuth
{
    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The get pluck categories
     *
     * @return Response
     */
    public function getPluckCategories_get()
    {
        $data['message'] = "Data fetched successfully";
        $data['status'] = TRUE;
        $siteLanguage = 1;

        $getCategories = $this->db->get("categories");
        if ($getCategories->num_rows() > 0) {
            $oCategories = $getCategories->result_object();
            $categories = [];
            foreach ($oCategories as $key => $oCategory) {
                $catId = $oCategory->cat_id;
                $getMeta = $this->db->get_where("cats_meta", array("cat_id" => $catId, "language_id" => $siteLanguage));
                $rowMeta = $getMeta->row();
                $catTitle = $rowMeta->cat_title;

                $categories[$key] = new stdClass();
                $categories[$key]->category_id = $catId;
                $categories[$key]->category_title = $catTitle;
            }
            $data['data'] = $categories;
        }

        $this->response($data, 200);
    }

    /**
     * get my referrals
     *
     * @param [type] $userId
     * @return void
     */
    public function getSearchProperties_get()
    {
        $categoryId = $this->input->get("category_id");
        $setData = [
            'category_id' => $categoryId,
        ];
        $this->form_validation->set_data($setData);
        $this->form_validation->set_rules('category_id', 'Category Id', 'trim|required|numeric');

        if ($this->form_validation->run() === false) {
            $data['status'] = FALSE;
            $data['message'] = "Unprocessable Entity";
            $data['errors'] = $this->form_validation->error_array();
            $statusCode = 422;
            // validation not ok, send validation errors to the view
            $this->response($data, $statusCode);
        }

        $data['message'] = "Data fetched successfully";
        $data['status'] = TRUE;
        $siteLanguage = 1;

        $getCategory = $this->db->get_where('categories', ['cat_id' => $categoryId]);
        $oCategory = $getCategory->row();

        if (!$oCategory) {
            $data['status'] = FALSE;
            $data['message'] = "Invalid category ID";
            $statusCode = 404;
            // validation not ok, send validation errors to the view
            $this->response($data, $statusCode);
        }

        $catId = $oCategory->cat_id;
        $getMeta = $this->db->get_where("cats_meta", array("cat_id" => $catId, "language_id" => $siteLanguage));
        $rowMeta = $getMeta->row();
        $catArr = [];
        $catArr['title'] = $rowMeta->cat_title;
        $catArr['description'] = $rowMeta->cat_desc;
        $data['data']['category'] = $catArr;

        $getCountries = $this->db->query("SELECT DISTINCT proposal_seller_id FROM proposals WHERE proposal_cat_id={$categoryId} AND proposal_status='active'");
        $rowCount = $getCountries->num_rows();
        if ($rowCount > 0) {
            $oCountries = $getCountries->result_object();
            $countries = [];
            $cities = [];
            foreach ($oCountries as $key => $oCountry) {
                $sellerId = $oCountry->proposal_seller_id;
                $seller = $this->db->get_where("sellers", ['seller_id' => $sellerId])->row();
                $sellerCountry = $seller->seller_country;
                $sellerCity = $seller->seller_city;

                $countries[$key] = $sellerCountry;
                $cities[$key] = $sellerCity;
            }
            $data['data']['countries'] = array_unique(array_filter($countries));
            $data['data']['cities'] = array_unique(array_filter($cities));
        }

        $getDeliviries = $this->db->query("SELECT DISTINCT delivery_id FROM proposals WHERE proposal_cat_id={$categoryId} AND proposal_status='active'");
        $rowCount = $getDeliviries->num_rows();
        if ($rowCount > 0) {
            $oDeliviries = $getDeliviries->result_object();
            $deliviries = [];
            foreach ($oDeliviries as $key => $oDelivery) {
                $deliveryId = $oDelivery->delivery_id;
                $select_delivery_time = $this->db->get_where("delivery_times", array('delivery_id' => $deliveryId));
                $deliveryTitle = @$select_delivery_time->row()->delivery_title;
                $deliviries[$key] = new stdClass(); //the magic
                $deliviries[$key]->id = $deliveryId;
                $deliviries[$key]->title = $deliveryTitle;
            }
            $data['data']['deliveries'] = array_unique(array_filter($deliviries));
        }

        $getLevels = $this->db->query("SELECT DISTINCT level_id FROM proposals WHERE proposal_cat_id={$categoryId} AND proposal_status='active'");
        $rowCount = $getLevels->num_rows();
        if ($rowCount > 0) {
            $oLevels = $getLevels->result_object();
            $levels = [];
            foreach ($oLevels as $key => $oLevel) {
                $levelId = $oLevel->level_id;
                $levelTitle = @$this->db->get_where("seller_levels_meta", array("level_id" => $levelId, "language_id" => $siteLanguage))->row()->title;
                if (!empty($level_title)) {
                    $levels[$key] = new stdClass(); //the magic
                    $levels[$key]->id = $levelId;
                    $levels[$key]->title = $levelTitle;
                }
            }
            $data['data']['seller_levels'] = array_unique(array_filter($levels));
        }

        $getLangauges = $this->db->query("select DISTINCT language_id from proposals where not language_id='0' and proposal_cat_id={$categoryId} AND proposal_status='active'");
        $rowCount = $getLangauges->num_rows();
        if ($rowCount > 0) {
            $oLangauges = $getLangauges->result_object();
            $languages = [];
            foreach ($oLangauges as $key => $oLangauge) {
                $languageId = $oLangauge->language_id;
                $select_seller_languges = $this->db->get_where("seller_languages",array('language_id' => $languageId));
                $languageTitle = @$select_seller_languges->row()->language_title;
                if(!empty($language_title)){
                    $languages[$key] = new stdClass(); //the magic
                    $languages[$key]->id = $languageId;
                    $languages[$key]->title = $languageTitle;
                }
            }
            $data['data']['langauges'] = array_unique(array_filter($languages));
        }
        $this->response($data, 200);
    }

    /**
     * Hire by Category
     *
     * @return void
     */
    public function getSearch_get()
    {
        $categoryId = $this->input->get("category_id");
        $setData = [
            'category_id' => $categoryId,
        ];
        $this->form_validation->set_data($setData);
        $this->form_validation->set_rules('category_id', 'Category Id', 'trim|required|numeric');

        if ($this->form_validation->run() === false) {
            $data['message'] = "Unprocessable Entity";
            $data['errors'] = $this->form_validation->error_array();
            $statusCode = 422;
            // validation not ok, send validation errors to the view
            $this->response($data, $statusCode);
        }

        $getCategory = $this->db->get_where('categories', ['cat_id' => $categoryId]);
        $oCategory = $getCategory->row();

        if (!$oCategory) {
            $data['status'] = FALSE;
            $data['message'] = "Invalid category ID";
            $statusCode = 404;
            // validation not ok, send validation errors to the view
            $this->response($data, $statusCode);
        }

        // Get Search Params if present in requests
        $params = [];
        $params['category_id'] = $this->input->get("category_id") ?? null;
        $params['online_sellers'] = $this->input->get("online_sellers") ?? null;
        $params['instant_delivery'] = $this->input->get("instant_delivery") ?? null;
        $params['country'] = $this->input->get("country") ?? null;
        $params['city'] = $this->input->get("city") ?? null;
        $params['delivery_time_id'] = $this->input->get("delivery_time_id") ?? null;
        $params['seller_level_id'] = $this->input->get("seller_level_id") ?? null;
        $params['seller_language_id'] = $this->input->get("seller_language_id") ?? null;

        $filterType = "category";

        $queryWhere = searchQueryWhere("query_where", $filterType, $params);
        $whereValues = searchQueryWhere("values", $filterType, $params);

        $this->load->helper('url');
        $this->load->library("pagination");

        $page = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $limit = ($this->input->get("per_page")) ? $this->input->get("per_page") : 16;
        $pagePosition = (($page - 1) * $limit);
        $orderBy = $this->input->get("order") ?? "DESC";

        $whereLimit = " ORDER BY created_at {$orderBy} LIMIT {$limit} OFFSET {$pagePosition}";

        $spQuery = "SELECT DISTINCT proposals.* FROM proposals INNER JOIN sellers ON proposals.proposal_seller_id = sellers.seller_id INNER JOIN instant_deliveries ON proposals.proposal_id = instant_deliveries.proposal_id {$queryWhere}";
        $sQuery = "SELECT DISTINCT proposals.* FROM proposals INNER JOIN sellers ON proposals.proposal_seller_id = sellers.seller_id INNER JOIN instant_deliveries ON proposals.proposal_id = instant_deliveries.proposal_id {$queryWhere} {$whereLimit}";

        //get total number of records from database for pagination
        $query = $this->db->query($spQuery, $whereValues);
        $rowCount = $query->num_rows();
        $qProposals = $this->db->query($sQuery, $whereValues);

        if ($rowCount == 0) {
            $data['status'] = FALSE;
            $data['message'] = "Sorry, we don't found with these search criterias. Please try with other search information.";
            $statusCode = 200;

            $this->response($data, $statusCode);
        }

        $oProposals = $qProposals->result_object();
        $res = [];
        $siteLanguage = 1;
        foreach ($oProposals as $key => $oProposal) {
            $res[$key] = $oProposal;

            $proposalId = $oProposal->proposal_id;
            $proposalPrice = $oProposal->proposal_price;
            if ($proposalPrice == 0) {
                $get_p_1 = $this->db->get_where("proposal_packages", array("proposal_id" => $proposalId, "package_name" => "Basic"));
                $proposalPrice = $get_p_1->row()->price;
            }
            $res[$key]->proposal_price = $proposalPrice;
            $res[$key]->proposal_img1 = getImageUrl2("proposals", "proposal_img1", $oProposal->proposal_img1);

            // Seller
            $proposalSellerId = $oProposal->proposal_seller_id;
            $sellArr = [];
            $getSeller = $this->db->get_where("sellers", array("seller_id" => $proposalSellerId));
            $rowSeller = $getSeller->row();

            $sellArr['seller_user_name'] = $rowSeller->seller_user_name;
            $sellArr['seller_image'] = getImageUrl2("sellers", "seller_image", $rowSeller->seller_image);
            $sellArr['seller_level'] = $rowSeller->seller_level;
            $sellArr['seller_status'] = $rowSeller->seller_status;
            if ($sellArr['seller_image'] == "")
                $sellArr['seller_image'] = "empty-image.png";

            @$sellArr['seller_level'] = $this->db->get_where("seller_levels_meta", array("level_id" => $sellArr['seller_level'], "language_id" => $siteLanguage))->row()->title;

            // Seller Reviews
            $proposalReviews = [];
            $selectBuyerReviews = $this->db->get_where("buyer_reviews", array("proposal_id" => $proposalId));
            $countReviews = $selectBuyerReviews->num_rows();
            $total = 0;
            $averageRating = 0;
            if ($countReviews > 0) {
                $oBuyerReviews = $selectBuyerReviews->result_object();
                foreach($oBuyerReviews as $oBuyerReview) {
                    $proposalBuyerRating = $oBuyerReview->buyer_rating;
                    array_push($proposalReviews, $proposalBuyerRating);
                }
                $total = array_sum($proposalReviews);
                $averageRating = $total / count($proposalReviews);
            }
            $reviews['total'] = $total;
            $reviews['average_rating'] = $averageRating;
            $sellArr['reviews'] = $reviews;

            $res[$key]->seller = $sellArr;
        }
        $data['status'] = TRUE;
        $data['message'] = "Data fetched successfully";
        $data['data'] = $res;

        $data['data'] = $res;
        $baseUrl = "api/v1/categories/search?per_page={$page}&page=";

        $totalPages = ceil( $rowCount / $limit);
        $data['meta_data']['total'] = $rowCount;
        $data['meta_data']['per_page'] = $limit;
        $data['meta_data']['total_pages'] = $totalPages;
        $data['meta_data']['page'] = $page;
        $data['meta_data']['pagination'] = paginate($totalPages, $baseUrl);

        $this->response( $data, 200 );
    }

    public function index_get()
    {
        // Get Search Params if present in requests
        $params = [];
        $params['s'] = $this->input->get("s") ?? "";
        $params['category_id'] = $this->input->get("category_id") ?? null;
        $params['online_sellers'] = $this->input->get("online_sellers") ?? null;
        $params['instant_delivery'] = $this->input->get("instant_delivery") ?? null;
        $params['country'] = $this->input->get("country") ?? null;
        $params['city'] = $this->input->get("city") ?? null;
        $params['delivery_time_id'] = $this->input->get("delivery_time_id") ?? null;
        $params['seller_level_id'] = $this->input->get("seller_level_id") ?? null;
        $params['seller_language_id'] = $this->input->get("seller_language_id") ?? null;

        $filterType = "search";

        $queryWhere = searchQueryWhere("query_where", $filterType, $params);
        $whereValues = searchQueryWhere("values", $filterType, $params);

        $this->load->helper('url');
        $this->load->library("pagination");

        $page = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $limit = ($this->input->get("per_page")) ? $this->input->get("per_page") : 16;
        $pagePosition = (($page - 1) * $limit);
        $orderBy = $this->input->get("order") ?? "DESC";

        $whereLimit = " ORDER BY created_at {$orderBy} LIMIT {$limit} OFFSET {$pagePosition}";

        $spQuery = "SELECT DISTINCT proposals.* FROM proposals INNER JOIN sellers ON proposals.proposal_seller_id = sellers.seller_id INNER JOIN instant_deliveries ON proposals.proposal_id = instant_deliveries.proposal_id {$queryWhere}";
        $sQuery = "SELECT DISTINCT proposals.* FROM proposals INNER JOIN sellers ON proposals.proposal_seller_id = sellers.seller_id INNER JOIN instant_deliveries ON proposals.proposal_id = instant_deliveries.proposal_id {$queryWhere} {$whereLimit}";

        //get total number of records from database for pagination
        $query = $this->db->query($spQuery, $whereValues);
        $rowCount = $query->num_rows();
        $qProposals = $this->db->query($sQuery, $whereValues);

        if ($rowCount == 0) {
            $data['status'] = FALSE;
            $data['message'] = "Sorry, we don't found with these search criterias. Please try with other search information.";
            $statusCode = 200;

            $this->response($data, $statusCode);
        }

        $oProposals = $qProposals->result_object();
        $res = [];
        $siteLanguage = 1;
        foreach ($oProposals as $key => $oProposal) {
            $res[$key] = $oProposal;

            $proposalId = $oProposal->proposal_id;
            $proposalPrice = $oProposal->proposal_price;
            if ($proposalPrice == 0) {
                $get_p_1 = $this->db->get_where("proposal_packages", array("proposal_id" => $proposalId, "package_name" => "Basic"));
                $proposalPrice = $get_p_1->row()->price;
            }
            $res[$key]->proposal_price = $proposalPrice;
            $res[$key]->proposal_img1 = getImageUrl2("proposals", "proposal_img1", $oProposal->proposal_img1);

            // Seller
            $proposalSellerId = $oProposal->proposal_seller_id;
            $sellArr = [];
            $getSeller = $this->db->get_where("sellers", array("seller_id" => $proposalSellerId));
            $rowSeller = $getSeller->row();

            $sellArr['seller_user_name'] = $rowSeller->seller_user_name;
            $sellArr['seller_image'] = getImageUrl2("sellers", "seller_image", $rowSeller->seller_image);
            $sellArr['seller_level'] = $rowSeller->seller_level;
            $sellArr['seller_status'] = $rowSeller->seller_status;
            if ($sellArr['seller_image'] == "")
                $sellArr['seller_image'] = "empty-image.png";

            @$sellArr['seller_level'] = $this->db->get_where("seller_levels_meta", array("level_id" => $sellArr['seller_level'], "language_id" => $siteLanguage))->row()->title;

            // Seller Reviews
            $proposalReviews = [];
            $selectBuyerReviews = $this->db->get_where("buyer_reviews", array("proposal_id" => $proposalId));
            $countReviews = $selectBuyerReviews->num_rows();
            $total = 0;
            $averageRating = 0;
            if ($countReviews > 0) {
                $oBuyerReviews = $selectBuyerReviews->result_object();
                foreach($oBuyerReviews as $oBuyerReview) {
                    $proposalBuyerRating = $oBuyerReview->buyer_rating;
                    array_push($proposalReviews, $proposalBuyerRating);
                }
                $total = array_sum($proposalReviews);
                $averageRating = $total / count($proposalReviews);
            }
            $reviews['total'] = $total;
            $reviews['average_rating'] = $averageRating;
            $sellArr['reviews'] = $reviews;

            $res[$key]->seller = $sellArr;
        }
        $data['status'] = TRUE;
        $data['message'] = "Data fetched successfully";
        $data['data'] = $res;

        $data['data'] = $res;
        $baseUrl = "api/v1/search?per_page={$page}&page=";

        $totalPages = ceil( $rowCount / $limit);
        $data['meta_data']['total'] = $rowCount;
        $data['meta_data']['per_page'] = $limit;
        $data['meta_data']['total_pages'] = $totalPages;
        $data['meta_data']['page'] = $page;
        $data['meta_data']['pagination'] = paginate($totalPages, $baseUrl);

        $this->response( $data, 200 );

    }
}

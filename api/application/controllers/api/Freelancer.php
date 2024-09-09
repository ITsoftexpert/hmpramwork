<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/controllers/api/APIAuth.php';

/**
 * Freelancer class.
 *
 * @extends REST_Controller
 */
class Freelancer extends APIAuth
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
     * get freelancer sidebar
     *
     * @param [type] $userId
     * @return void
     */
    public function getSearchProperties_get()
    {
        $data['message'] = "Data fetched successfully";
        $data['status'] = TRUE;
        $siteLanguage = 1;

        $getSkills = $this->db->get("seller_skills");
        $rowCount = $getSkills->num_rows();

        if ($rowCount > 0) {
            $oSkills = $getSkills->result_object();
            $skills = [];
            foreach ($oSkills as $key => $oSkill) {
                $tagTitle = $oSkill->skill_title;
                $tagId = $oSkill->skill_id;
                if (!empty($tagTitle)) {
                    $skills[$tagId] = $tagTitle;
                }
            }
            $data['data']['skills'] = array_unique(array_filter($skills));
        }
        $getSellers = $this->db->query("select DISTINCT seller_level from sellers");
        $rowCount = $getSellers->num_rows();
        if ($rowCount > 0) {
            $oSellers = $getSellers->result_object();
            $levels = [];
            foreach ($oSellers as $key => $oSeller) {
                $levelId = $oSeller->seller_level;
				$levelTitle = $this->db->get_where("seller_levels_meta", array("level_id" => $levelId, "language_id" => $siteLanguage))->row()->title;
                $levels[$levelId] = $levelTitle;
            }
            $data['data']['seller_levels'] = array_unique(array_filter($levels));
        }

        $getLangauges = $this->db->query("select DISTINCT seller_language from sellers");
        $rowCount = $getLangauges->num_rows();
        if ($rowCount > 0) {
            $oLangauges = $getLangauges->result_object();
            $languages = [];
            foreach ($oLangauges as $key => $oLangauge) {
                $languageId = $oLangauge->seller_language;

                $select_seller_languges = $this->db->get_where("seller_languages",array('language_id' => $languageId));
                $languageTitle = @$select_seller_languges->row()->language_title;
                if(!empty($languageTitle)){
                    $languages[$languageId] = $languageTitle;
                }
            }
            $data['data']['langauges'] = array_unique(array_filter($languages));
        }
        $this->response($data, 200);
    }

    /**
     * Get freelancers
     *
     * @return void
     */
    public function index_get()
    {
        $params = [];
        $params['skill_id'] = $this->input->get("skill_id") ?? null;
        $params['online_sellers'] = $this->input->get("online_sellers") ?? null;
        $params['seller_level_id'] = $this->input->get("seller_level_id") ?? null;
        $params['seller_language_id'] = $this->input->get("seller_language_id") ?? null;

        $filterType = "search";

        $queryWhere = freelancersQueryWhere("query_where", $params);
        $where_path = freelancersQueryWhere("where_path", $params);
        $whereValues = freelancersQueryWhere("values", $params);

        $query_where_skills = isset($queryWhere) ? $queryWhere[1] : "";
	    $queryWhere = isset($queryWhere) ? $queryWhere[0] : "";

        $this->load->helper('url');
        $this->load->library("pagination");

        $page = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $limit = ($this->input->get("per_page")) ? $this->input->get("per_page") : 5;
        $pagePosition = (($page - 1) * $limit);
        $orderBy = $this->input->get("order") ?? "DESC";

        $whereLimit = " ORDER BY seller_level {$orderBy} LIMIT {$limit} OFFSET {$pagePosition}";

        if ($query_where_skills != "") {
            $q = "SELECT s.* FROM sellers s INNER JOIN proposals p ON s.seller_id = p.proposal_seller_id AND p.proposal_status='active' INNER JOIN skills_relation sr ON s.seller_id = sr.seller_id AND s.seller_id INNER JOIN seller_skills ss ON sr.skill_id = ss.skill_id AND ss.skill_id IN (" . $query_where_skills . ") $queryWhere GROUP by s.seller_id";
			$spQuery = "{$q};";
            $sQuery = "{$q} $whereLimit;";
		} else {
			$spQuery = "SELECT DISTINCT s.* FROM sellers s JOIN proposals ON s.seller_id=proposals.proposal_seller_id AND proposals.proposal_status='active' {$queryWhere}";
            $sQuery = "SELECT DISTINCT s.* FROM sellers s JOIN proposals ON s.seller_id=proposals.proposal_seller_id AND proposals.proposal_status='active' {$queryWhere}{$whereLimit}";
		}

        //get total number of records from database for pagination
        $query = $this->db->query($spQuery, $whereValues);
        $rowCount = $query->num_rows();
        $qSellers = $this->db->query($sQuery, $whereValues);

        if ($rowCount == 0) {
            $data['status'] = FALSE;
            $data['message'] = "Sorry, we don't found with these search criterias. Please try with other search information.";
            $statusCode = 200;

            $this->response($data, $statusCode);
        }

        $oSellers = $qSellers->result_object();
        $res = [];
        $siteLanguage = 1;
        foreach ($oSellers as $key => $oSeller) {
            $res[$key] = $oSeller;
            unset($res[$key]->seller_pass);
            unset($res[$key]->seller_ip);

            $res[$key]->seller_image = getImageUrl2("sellers", "seller_image", $oSeller->seller_image);
            $sellerId = $oSeller->seller_id;

            // Seller Reviews
            $sellerReviews = [];
            $selectBuyerReviews = $this->db->get_where("buyer_reviews", array("review_seller_id" => $sellerId));
            $countReviews = $selectBuyerReviews->num_rows();
            $total = 0;
            $averageRating = 0;
            if ($countReviews > 0) {
                $oBuyerReviews = $selectBuyerReviews->result_object();
                foreach($oBuyerReviews as $oBuyerReview) {
                    $proposalBuyerRating = $oBuyerReview->buyer_rating;
                    array_push($sellerReviews, $proposalBuyerRating);
                }
                $total = array_sum($sellerReviews);
                $averageRating = $total / count($sellerReviews);
            }
            $reviews['total'] = $total;
            $reviews['average_rating'] = $averageRating;

            $res[$key]->reviews = $reviews;
        }
        $data['status'] = TRUE;
        $data['message'] = "Data fetched successfully";
        $data['data'] = $res;

        $data['data'] = $res;
        $baseUrl = "api/v1/freelancers?per_page={$page}&page=";

        $totalPages = ceil( $rowCount / $limit);
        $data['meta_data']['total'] = $rowCount;
        $data['meta_data']['per_page'] = $limit;
        $data['meta_data']['total_pages'] = $totalPages;
        $data['meta_data']['page'] = $page;
        $data['meta_data']['pagination'] = paginate($totalPages, $baseUrl);

        $this->response( $data, 200 );
    }
}

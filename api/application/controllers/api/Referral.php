<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/controllers/api/APIAuth.php';

/**
 * Referral class.
 *
 * @extends REST_Controller
 */
class Referral extends APIAuth
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

        $this->load->model('Referral_model');
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
     */
    public function index_get($userId, $proposalId)
    {
        $getProposal = $this->db->get_where("proposals", array("proposal_id" => $userId, "proposal_seller_id" => $userId));

        $data['message'] = "No records";
        $data['status'] = FALSE;
        if ($getProposal->num_rows() == 0) {
            return $this->response($data, 200);
        }

        $this->load->helper('url');
        $this->load->library("pagination");

        $page = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $limit = ($this->input->get("per_page")) ? $this->input->get("per_page") : 10;
        $pagePosition = (($page - 1) * $limit);

        $response = $this->Referral_model->getProposalReferral($userId, $proposalId, $limit, $pagePosition);

        $total = $response['total'];
        // Approved earnings
        $select = $this->db->query("SELECT SUM(comission) AS total FROM proposal_referrals where proposal_id = '{$proposalId}' AND status='approved'");
        $total = $select->row()->total;
        $data['data']['approved_earnings'] = $total > 0 || $total !== null ? $total : "0";
        // Pending
        $select = $this->db->query("SELECT SUM(comission) AS total FROM proposal_referrals where proposal_id = '{$proposalId}' AND status='pending'");
        $total = $select->row()->total;
        $data['data']['pending_earnings'] = $total > 0 || $total !== null ? $total : "0";
        // Declined
        $select = $this->db->query("SELECT SUM(comission) AS total FROM proposal_referrals where proposal_id = '{$proposalId}' AND status='declined'");
        $total = $select->row()->total;
        $data['data']['declined_earnings'] = $total > 0 || $total !== null ? $total : "0";

        if ($total > 0) {
            $referrals = $response['data'];
            $data['message'] = "Data fetched successfully";
            $data['status'] = TRUE;
            $res = [];

            foreach ($referrals as $key => $oResult) {
                $res[$key] = $oResult;
                $orderId = $oResult->order_id;
                $referrerId = $oResult->referrer_id;
                $buyerId = $oResult->buyer_id;

                $select_referred = $this->db->get_where("sellers", array("seller_id" => $referrerId));
                $res[$key]->referred_user_name = $select_referred->row()->seller_user_name;

                $sel_buyer = $this->db->get_where("sellers", array("seller_id" => $buyerId));
                $res[$key]->buyer_user_name = $sel_buyer->row()->seller_user_name;

                $get_order = $this->db->get_where("orders", array("order_id" => $orderId));
                $row_order = $get_order->row();
                $res[$key]->order_number = @$row_order->order_number;
            }
            $data['data']['referrals'] = $res;
            $baseUrl = "api/v1/proposals/{$userId}/{$proposalId}/referrals?per_page={$page}&page=";

            $totalPages = ceil($response['total'] / $limit);
            $data['meta_data']['total'] = $response['total'];
            $data['meta_data']['per_page'] = $limit;
            $data['meta_data']['total_pages'] = $totalPages;
            $data['meta_data']['page'] = $page;
            $data['meta_data']['pagination'] = paginate($totalPages, $baseUrl);
        }

        $this->response($data, 200);
    }

    /**
     * get my referrals
     *
     * @param [type] $userId
     * @return void
     */
    public function getMyRefferals_get($userId)
    {
        $oSeller = get_seller_info($userId);
        $sellerReferral = $oSeller->seller_referral;

        $setting = get_general_settings();
        $referralMoney = $setting->referral_money;

        $this->load->helper('url');
        $this->load->library("pagination");

        $page = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $limit = ($this->input->get("per_page")) ? $this->input->get("per_page") : 10;
        $pagePosition = (($page - 1) * $limit);

        $response = $this->Referral_model->getMyReferrals($userId, $limit, $pagePosition);

        $total = $response['total'];
        $data['message'] = "No records";
        $data['status'] = FALSE;
        $data['data']['seller_referral'] = $sellerReferral;
        $data['data']['referral_money'] = $referralMoney;

        // Approved earnings
        $select = $this->db->query("SELECT SUM(comission) AS total FROM referrals where seller_id = '{$userId}' AND status='approved'");
        $total = $select->row()->total;
        $data['data']['approved_earnings'] = $total > 0 || $total !== null ? $total : "0";
        // Pending
        $select = $this->db->query("SELECT SUM(comission) AS total FROM referrals where seller_id = '{$userId}' AND status='pending'");
        $total = $select->row()->total;
        $data['data']['pending_earnings'] = $total > 0 || $total !== null ? $total : "0";
        // Declined
        $select = $this->db->query("SELECT SUM(comission) AS total FROM referrals where seller_id = '{$userId}' AND status='declined'");
        $total = $select->row()->total;
        $data['data']['declined_earnings'] = $total > 0 || $total !== null ? $total : "0";

        if ($total > 0) {
            $referrals = $response['data'];
            $data['message'] = "Data fetched successfully";
            $data['status'] = TRUE;
            $res = [];

            foreach ($referrals as $key => $oResult) {
                $res[$key] = $oResult;
                $referredId = $oResult->referred_id;
                $referrer = get_seller_info($referredId);
                $res[$key]->seller_user_name = $referrer->seller_user_name;
            }

            $data['data']['referrals'] = $res;
            $baseUrl = "api/v1/referrals/{$userId}/my-referrals?per_page={$page}&page=";

            $totalPages = ceil($response['total'] / $limit);
            $data['meta_data']['total'] = $response['total'];
            $data['meta_data']['per_page'] = $limit;
            $data['meta_data']['total_pages'] = $totalPages;
            $data['meta_data']['page'] = $page;
            $data['meta_data']['pagination'] = paginate($totalPages, $baseUrl);
        }

        $this->response($data, 200);
    }

    /**
     * get my proposal referrals
     *
     * @param [type] $userId
     * @return void
     */
    public function getMyProposalRefferals_get($userId)
    {
        $oSeller = get_seller_info($userId);
        $sellerReferral = $oSeller->seller_referral;

        $setting = get_general_settings();
        $referralMoney = $setting->referral_money;

        $this->load->helper('url');
        $this->load->library("pagination");

        $page = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $limit = ($this->input->get("per_page")) ? $this->input->get("per_page") : 10;
        $pagePosition = (($page - 1) * $limit);

        $response = $this->Referral_model->getMyProposalReferrals($userId, $limit, $pagePosition);

        $total = $response['total'];
        $data['message'] = "No records";
        $data['status'] = FALSE;
        $data['data']['seller_referral'] = $sellerReferral;
        $data['data']['referral_money'] = $referralMoney;

        // Approved earnings
        $select = $this->db->query("SELECT SUM(comission) AS total FROM proposal_referrals where referrer_id = '{$userId}' AND status='approved'");
        $total = $select->row()->total;
        $data['data']['approved_earnings'] = $total > 0 || $total !== null ? $total : "0";
        // Pending
        $select = $this->db->query("SELECT SUM(comission) AS total FROM proposal_referrals where referrer_id = '{$userId}' AND status='pending'");
        $total = $select->row()->total;
        $data['data']['pending_earnings'] = $total > 0 || $total !== null ? $total : "0";
        // Declined
        $select = $this->db->query("SELECT SUM(comission) AS total FROM proposal_referrals where referrer_id = '{$userId}' AND status='declined'");
        $total = $select->row()->total;
        $data['data']['declined_earnings'] = $total > 0 || $total !== null ? $total : "0";

        if ($total > 0) {
            $referrals = $response['data'];
            $data['message'] = "Data fetched successfully";
            $data['status'] = TRUE;
            $res = [];

            foreach ($referrals as $key => $oResult) {
                $res[$key] = $oResult;
                $proposalId = $oResult->proposal_id;
                $sellerId = $oResult->seller_id;
                $buyerId = $oResult->buyer_id;
                $seller = get_seller_info($sellerId);
                $res[$key]->seller_user_name = $seller->seller_user_name;
                $buyer = get_seller_info($buyerId);
                $res[$key]->buyer_user_name = $buyer->seller_user_name;

                $proposals = $this->db->get_where("proposals", array("proposal_id" => $proposalId));
                $oProposal = $proposals->row();
                $res[$key]->proposal_title = $oProposal->proposal_title;
            }

            $data['data']['referrals'] = $res;
            $baseUrl = "api/v1/referrals/{$userId}/my-proposals?per_page={$page}&page=";

            $totalPages = ceil($response['total'] / $limit);
            $data['meta_data']['total'] = $response['total'];
            $data['meta_data']['per_page'] = $limit;
            $data['meta_data']['total_pages'] = $totalPages;
            $data['meta_data']['page'] = $page;
            $data['meta_data']['pagination'] = paginate($totalPages, $baseUrl);
        }

        $this->response($data, 200);
    }
}

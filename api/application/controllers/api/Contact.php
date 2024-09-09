<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/controllers/api/APIAuth.php';

/**
 * Contact class.
 *
 * @extends REST_Controller
 */
class Contact extends APIAuth
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

        $this->load->model('Contact_model');
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
     */
    public function index_get($userId, $mode)
    {
        $this->load->helper('url');
        $this->load->library("pagination");

        $page = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $limit = ($this->input->get("per_page")) ? $this->input->get("per_page") : 10;
        $pagePosition = (($page - 1) * $limit);
        if ($mode == 'buyers')
            $response = $this->Contact_model->getSellerContacts($userId, $limit, $pagePosition);
        else
            $response = $this->Contact_model->getBuyerContacts($userId, $limit, $pagePosition);

        $total = $response['total'];
        $data['message'] = "No records";
        $data['status'] = FALSE;

        if ($total > 0) {
            $purchases = $response['data'];
            $data['message'] = "Data fetched successfully";
            $data['status'] = TRUE;
            $res = [];

            foreach ($purchases as $key => $oResult) {
                $res[$key] = $oResult;
                if ($mode == 'buyers') {
                    $buyerId = $oResult->buyer_id;
                    $select_buyer = $this->db->get_where("sellers", array("seller_id" => $buyerId));
                    $row_buyer = $select_buyer->row();

                    $res[$key]->buyer_user_name = $row_buyer->seller_user_name;
                    $res[$key]->buyer_image = getImageUrl2("sellers", "seller_image", $row_buyer->seller_image);
                    $res[$key]->buying_histories_url = "api/v1/contacts/{$userId}/{$mode}/{$buyerId}/buying-histories";
                } else {
                    $sellerId = $oResult->seller_id;
                    $select_buyer = $this->db->get_where("sellers", array("seller_id" => $sellerId));
                    $row_buyer = $select_buyer->row();

                    $res[$key]->seller_user_name = $row_buyer->seller_user_name;
                    $res[$key]->seller_image = getImageUrl2("sellers", "seller_image", $row_buyer->seller_image);
                    $res[$key]->selling_histories_url = "api/v1/contacts/{$userId}/{$mode}/{$sellerId}/selling-histories";
                }
            }
            $data['data'] = $res;
            $baseUrl = "api/v1/contacts/{$userId}/{$mode}?per_page={$page}&page=";

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
     * Get buyers/sellers buying or seller histories
     *
     * @param [type] $userId
     * @param [type] $mode
     * @param [type] $historyUserId
     * @return void
     */
    public function getHistories_get($userId, $mode, $historyUserId)
    {
        $this->load->helper('url');
        $this->load->library("pagination");

        $page = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $limit = ($this->input->get("per_page")) ? $this->input->get("per_page") : 10;
        $pagePosition = (($page - 1) * $limit);
        if ($mode == 'buyers')
            $response = $this->Contact_model->getSellerContactHistories($userId, $historyUserId, $limit, $pagePosition);
        else
            $response = $this->Contact_model->getBuyerContactHistories($userId, $historyUserId,  $limit, $pagePosition);

        $total = $response['total'];
        $data['message'] = "No records";
        $data['status'] = FALSE;

        if ($total > 0) {
            $purchases = $response['data'];
            $data['message'] = "Data fetched successfully";
            $data['status'] = TRUE;
            $res = [];

            foreach ($purchases as $key => $oResult) {
                $res[$key] = $oResult;

                $proposalId = $oResult->proposal_id;
                $orderDate = $oResult->order_date;
                $orderDuration = intval($oResult->order_duration);
                $res[$key]->order_due = date("F d, Y", strtotime($orderDate . " + $orderDuration days"));

                $prop = [];
                $select_proposals = $this->db->get_where("proposals",array("proposal_id" => $proposalId));
                $row_proposals = $select_proposals->row();
                $prop['proposal_title'] = $row_proposals->proposal_title;
                $prop['proposal_img1'] = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
                $res[$key]->proposal = $prop;
            }

            $data['data'] = $res;
            $historyMode = $mode == 'buyers' ? 'buying-histories' : 'selling-histories';
            $baseUrl = "api/v1/contacts/{$userId}/{$mode}/{$historyUserId}/{$historyMode}?per_page={$page}&page=";

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

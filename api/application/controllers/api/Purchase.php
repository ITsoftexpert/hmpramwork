<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/controllers/api/APIAuth.php';

/**
 * Purchase class.
 *
 * @extends REST_Controller
 */
class Purchase extends APIAuth
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

        $this->load->model('Purchase_model');
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
     */
    public function index_get($userId)
    {
        $this->load->helper('url');
        $this->load->library("pagination");

        $page = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $limit = ($this->input->get("per_page")) ? $this->input->get("per_page") : 10;
        $pagePosition = (($page - 1) * $limit);

        $response = $this->Purchase_model->getPurchases($userId, $limit, $pagePosition);

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

                $orderId = $oResult->order_id;
                $reason = $oResult->reason;
                $amount = $oResult->amount;
                $date = $oResult->date;
                $method = $oResult->method;

                if ($reason == "featured_listing" or $method == "featured_proposal_declined") {
                    $select_proposals = $this->db->get_where("proposals", array("proposal_id" => $orderId));
                    $row_proposals = $select_proposals->row();
                    $prop = [];
                    $prop['proposal_title'] = $row_proposals->proposal_title;
                    $prop['proposal_url'] = $row_proposals->proposal_url;

                    $res[$key]->proposal = $prop;
                  }
            }
            $data['data'] = $res;
            $baseUrl = "api/v1/purchases/{$userId}?per_page={$page}&page=";

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

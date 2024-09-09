<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/controllers/api/APIAuth.php';

/**
 * Order class.
 *
 * @extends REST_Controller
 */
class Order extends APIAuth
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

        $this->load->model('order_model');
    }

    /**
	 * Buyers Order function.
	 *
	 * @access public
	 * @return void
	 */
	public function orderBuyer_get($userId, $status)
    {
        $this->load->helper('url');
        $this->load->library("pagination");

        $page = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $limit = ($this->input->get("per_page")) ? $this->input->get("per_page") : 10;
        $pagePosition = (($page - 1) * $limit);

        $response = $this->order_model->getBuyerOrders($userId, $status, $limit, $pagePosition);

        $total = $response['total'];
        $data['message'] = "No records";
        $data['status'] = FALSE;

        if ($total > 0) {
            $orders = $response['data'];
            $data['message'] = "Data fetched successfully";
            $data['status'] = TRUE;
            $res = [];
            foreach ($orders as $key => $order) {
                $res[$key] = $order;

                $proposal_id = $order->proposal_id;
                $order_duration = substr($order->order_duration, 0, 1);
                $order_date = $order->order_date;
                $res[$key]->order_due = date("F d, Y", strtotime($order_date . " + $order_duration days"));

                $query = $this->db->get_where('proposals', array('proposal_id' => $proposal_id));
                $row = $query->row();
                $res[$key]->proposal_title = $row->proposal_title;

                $res[$key]->proposal_img1 = getImageUrl2("proposals", "proposal_img1", $row->proposal_img1);
            }

            $data['data'] = $res;
            $baseUrl = "api/v1/orders/{$userId}/buyers/{$status}?per_page={$page}&page=";

            $totalPages = ceil( $response['total'] / $limit);
            $data['meta_data']['total'] = $response['total'];
            $data['meta_data']['per_page'] = $limit;
            $data['meta_data']['total_pages'] = $totalPages;
            $data['meta_data']['page'] = $page;
            $data['meta_data']['pagination'] = paginate($totalPages, $baseUrl);
        }
        $this->response( $data, 200 );
    }

    /**
	 * Sellers Order function.
	 *
	 * @access public
	 * @return void
	 */
	public function orderSeller_get($userId, $status)
    {
        $this->load->helper('url');
        $this->load->library("pagination");

        $page = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $limit = ($this->input->get("per_page")) ? $this->input->get("per_page") : 10;
        $pagePosition = (($page - 1) * $limit);

        $response = $this->order_model->getSellerOrders($userId, $status, $limit, $pagePosition);

        $total = $response['total'];
        $data['message'] = "No records";
        $data['status'] = FALSE;

        if ($total > 0) {
            $orders = $response['data'];
            $data['message'] = "Data fetched successfully";
            $data['status'] = TRUE;
            $res = [];
            foreach ($orders as $key => $order) {
                $res[$key] = $order;

                $proposalId = $order->proposal_id;
                $order_duration = substr($order->order_duration, 0, 1);
                $order_date = $order->order_date;
                $res[$key]->order_due = date("F d, Y", strtotime($order_date . " + $order_duration days"));

                $query = $this->db->get_where('proposals', array('proposal_id' => $proposalId));
                $row = $query->row();
                $res[$key]->proposal_title = $row->proposal_title;

                $res[$key]->proposal_img1 = getImageUrl2("proposals", "proposal_img1", $row->proposal_img1);

                $qOffers = $this->db->query("SELECT * FROM send_offers WHERE sender_id = ? AND proposal_id = ?", [$userId, $proposalId]);
                $cOffers = $qOffers->num_rows();

                if ($cOffers > 0) {
                    $oOffers = $qOffers->row();
                    $requestId = $oOffers->request_id;

                    $qRequests = $this->db->query("SELECT * FROM  buyer_requests WHERE request_id = ?", [$requestId]);
                    $oRequests = $qRequests->row();
                    $res[$key]->request_title = $oRequests->request_title;
                }
            }

            $data['data'] = $res;
            $baseUrl = "api/v1/orders/{$userId}/buyers/{$status}?per_page={$page}&page=";

            $totalPages = ceil( $response['total'] / $limit);
            $data['meta_data']['total'] = $response['total'];
            $data['meta_data']['per_page'] = $limit;
            $data['meta_data']['total_pages'] = $totalPages;
            $data['meta_data']['page'] = $page;
            $data['meta_data']['pagination'] = paginate($totalPages, $baseUrl);
        }
        $this->response( $data, 200 );
    }

    /**
	 * Order Detail
	 *
	 * @access public
	 * @return void
	 */
	public function orderDetails_get($userId, $orderId)
    {
        $data['message'] = "No records";
        $data['status'] = FALSE;

        $response = $this->order_model->show($userId, $orderId);

        if ($response) {
            $data['message'] = "Data fetched successfully";
            $data['status'] = TRUE;
            $data['data'] = $response;
        }

        $this->response( $data, 200 );
    }
}

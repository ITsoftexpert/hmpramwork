<?php
/**
 * CustomerSupport class.
 *
 * @extends REST_Controller
 */
require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class CustomerSupport extends REST_Controller
{

    /**
     * CONSTRUCTOR | LOAD MODEL
     *
     * @return Response
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('CustomerSupport_model');
    }

    /**
     * INSERT | POST method.
     *
     * @return Response
     */
    public function index_post($userId)
    {
        $enquiryType = $this->input->post("enquiry_id");
        $this->form_validation->set_rules('enquiry_id', 'Enquiry Id', 'trim|required|integer');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');

        if ($enquiryType == 1 || $enquiryType == 2) {
            $this->form_validation->set_rules('order_number', 'Order No', 'trim|required|integer');
            $this->form_validation->set_rules('user_role', 'User Role', 'trim|required|in_list[seller,buyer]');
        }
        if ($this->form_validation->run() === false) {
            // validation not ok, send validation errors to the view
            $this->response([$this->form_validation->error_array()], 422);
        }

        $input = $this->input->post();
        if (isset($input["user_role"])) {
            $input['order_rule'] = $input["user_role"];
            unset($input['user_role']);
        }
        $date = date("h:i M d, Y");
        $input['sender_id'] = $userId;
        $input['number'] = mt_rand();
        $input['status'] = "open";
        $input['date'] = $date;

        $data['message'] = "Something went wrong while saving.";
        $data['status'] = FALSE;
        $statusCode = 200;

        $id = $this->CustomerSupport_model->insert($input);

        if ($id > 0) {
            $data['message'] = "Message submitted successfully!";
            $data['status'] = TRUE;
            $statusCode = 201;
            $data['data'] = ['customer_support_id' => $id];

            // ORDER DISPUTE SUPPORT
            if ($enquiryType == 1) {
                $order_number = $input["order_number"];
                $qOrder = $this->db->get_where('orders', ['order_id' => $order_number]);

                $oOrder = $qOrder->row_object();
                $orderSellerId = $oOrder->seller_id;
                $orderBuyerId = $oOrder->buyer_id;
                $defendedId = $userId == $orderSellerId ? $orderBuyerId : $orderSellerId;

                $this->db->insert("notifications", [
                    "receiver_id" => $defendedId,
                    "sender_id" => $userId,
                    "order_id" => $order_number,
                    "reason" => "dispute_raised",
                    "date" => $date,
                    "status" => "unread"]
                );
            }
        }

        return $this->response($data, $statusCode);
    }
}

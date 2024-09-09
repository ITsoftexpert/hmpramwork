<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/controllers/api/APIAuth.php';

/**
 * Coupon class.
 *
 * @extends REST_Controller
 */
class Coupon extends APIAuth
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

        $this->load->model('Coupon_model');
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
     */
    public function index_get($userId, $proposalId)
    {
        $this->load->helper('url');
        $this->load->library("pagination");

        $page = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $limit = ($this->input->get("per_page")) ? $this->input->get("per_page") : 10;
        $pagePosition = (($page - 1) * $limit);

        $response = $this->Coupon_model->getProposalCoupons($userId, $proposalId, $limit, $pagePosition);

        $total = $response['total'];
        $data['message'] = "No records";
        $data['status'] = FALSE;

        if ($total > 0) {
            $data['message'] = "Data fetched successfully";
            $data['status'] = TRUE;

            $data['data'] = $response['data'];
            $baseUrl = "api/v1/proposals/{$userId}/{$proposalId}/coupons?per_page={$page}&page=";

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
     * INSERT | POST method.
     *
     * @return Response
     */
    public function index_post($userId, $proposalId)
    {
        $this->form_validation->set_rules('coupon_title', 'Title', 'trim|required');
        $this->form_validation->set_rules('coupon_type', 'Type', 'trim|required');
        $this->form_validation->set_rules('coupon_price', 'Price', 'trim|required|numeric');
        $this->form_validation->set_rules('coupon_code', 'Code', 'trim|required|is_unique[coupons.coupon_code]', array('is_unique' => 'Coupon Code Has Been Applied Already.'));
        $this->form_validation->set_rules('coupon_limit', 'Limit', 'trim|required|numeric');

        if ($this->form_validation->run() === false) {
            // validation not ok, send validation errors to the view
            $this->response([$this->form_validation->error_array()], 422);
        } else {
            $input = $this->input->post();
            $input['proposal_id'] = $proposalId;

            $data['message'] = "Something went wrong while saving.";
            $data['status'] = FALSE;
            $statusCode = 200;

            $id = $this->Coupon_model->insert($input);

            if ($id > 0) {
                $data['message'] = "Coupon created successfully";
                $data['status'] = TRUE;
                $statusCode = 201;
                $data['data'] = ['coupon_id' => $id];
            }

            return $this->response($data, $statusCode);
        }
    }

    /**
     * UPDATE | PUT method.
     *
     * @return Response
     */
    public function index_put($userId, $proposalId, $couponId)
    {
        $data['message'] = "Coupon not found";
        $data['status'] = FALSE;
        $statusCode = 404;
        try {
            $getCoupon = $this->Coupon_model->show($couponId);

            if (!$getCoupon) throw new Exception('Coupon not found');

            if ($getCoupon->proposal_id == $proposalId && $getCoupon->coupon_id == $couponId) {

                $setData = [
                    'coupon_title' => $this->put('coupon_title'),
                    'coupon_type' => $this->put('coupon_type'),
                    'coupon_price' => $this->put('coupon_price'),
                    'coupon_code' => $this->put('coupon_code'),
                    'coupon_limit' => $this->put('coupon_limit'),
                ];

                $this->form_validation->set_data($setData);

                $this->form_validation->set_rules('coupon_title', 'Title', 'trim|required');
                $this->form_validation->set_rules('coupon_type', 'Type', 'trim|required');
                $this->form_validation->set_rules('coupon_price', 'Price', 'trim|required|numeric');
                if (trim($getCoupon->coupon_code) != trim($setData['coupon_code'])) {
                    $this->form_validation->set_rules('coupon_code', 'Code', 'trim|required|is_unique[coupons.coupon_code]', array('is_unique' => 'Coupon Code Has Been Applied Already.'));
                } else {
                    $this->form_validation->set_rules('coupon_code', 'Code', 'trim|required');
                }
                $this->form_validation->set_rules('coupon_limit', 'Limit', 'trim|required|numeric');

                if ($this->form_validation->run() === false) {
                    $data['message'] = "Unprocessable Entity";
                    $data['errors'] = $this->form_validation->error_array();
                    $statusCode = 422;
                    // validation not ok, send validation errors to the view
                    $this->response($data, $statusCode);
                }
                $updateBy['coupon_id'] = $couponId;
                $updateBy['proposal_id'] = $proposalId;

                if ($this->Coupon_model->update($setData, $updateBy) > 0) {
                    $data['message'] = "Coupon updated successfully";
                    $data['status'] = TRUE;
                    $this->response($data, 204);
                } else {
                    throw new Exception('Update fail');
                }
            } else {
                throw new Exception('Coupon not found');
            }
        } catch (\Throwable $e) {
            $data['message'] = $e->getMessage();
            $this->response($data, $statusCode);
        }
    }

    /**
     * DELETE method.
     *
     * @return Response
     */
    public function index_delete($userId, $proposalId, $couponId)
    {
        $data['message'] = "Coupon not found";
        $data['status'] = FALSE;
        $statusCode = 404;
        try {
            $updateBy['coupon_id'] = $couponId;
            $updateBy['proposal_id'] = $proposalId;
            if ($this->Coupon_model->delete($updateBy) > 0) {
                $data['message'] = "Coupon deleted successfully";
                $data['status'] = TRUE;
                $this->response($data, 200);
            } else {
                throw new Exception('Delete fail');
            }
        } catch (\Throwable $e) {
            $data['message'] = $e->getMessage();
            $this->response($data, $statusCode);
        }
    }
}

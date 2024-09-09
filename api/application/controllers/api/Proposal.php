<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/controllers/api/APIAuth.php';

/**
 * Proposal class.
 *
 * @extends REST_Controller
 */
class Proposal extends APIAuth
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

        $this->load->model('Proposal_model');
    }

    /**
     * create proposal | POST method.
     *
     * @return Response
     */
    public function index_post()
    {
        $this->form_validation->set_rules('proposal_title', 'Title', 'trim|required|callback_isProposalUrlExist');
        $this->form_validation->set_rules('proposal_cat_id', 'Category Id', 'trim|required|numeric');
        $this->form_validation->set_rules('proposal_child_id', 'Sub category Id', 'trim|required|numeric');
        $this->form_validation->set_rules('proposal_tags', 'Tags', 'trim|required');
        $this->form_validation->set_rules('proposal_enable_referrals', 'Enable referrals', 'trim|required');
        // $this->form_validation->set_rules('proposal_img1', 'Product Image', 'trim|required');

        if ((isset($_POST['proposal_enable_referrals']) && $_POST['proposal_enable_referrals'] == 'yes')) {
            $this->form_validation->set_rules('proposal_referral_money', 'Percentage Referral', 'trim|required');
        }

        if ($this->form_validation->run() === false) {
            // validation not ok, send validation errors to the view
            $this->response([$this->form_validation->error_array()], 422);
        }

        $response['message'] = "Something went wrong.";
        $response['status'] = FALSE;
        $statusCode = 400;

        $userId = $this->getUserId();

        $user = get_seller_info($userId);
        $sellerLevel = $user->seller_level;
        $sellerLanguage = $user->seller_language;

        $proposalReferralCode = mt_rand();
        $rowGeneralSettings = get_general_settings();
        $proposalEmail = $rowGeneralSettings->proposal_email;
        $siteEmailAddress = $rowGeneralSettings->site_email_address;
        $siteLogo = $rowGeneralSettings->site_logo;
        $enableReferrals = $rowGeneralSettings->enable_referrals;

        $proposalTitle = $this->input->post('proposal_title');
        $sanitizeUrl = proposalUrl($proposalTitle);

        $data = $this->input->post();

        $data['proposal_url'] = $sanitizeUrl;
        $data['proposal_seller_id'] = $userId;
        $data['proposal_featured'] = "no";
        if ($enableReferrals == "no") {
            $data['proposal_enable_referrals'] = "no";
        }
        if (isset($data['direct_order'])) {
            $direct_order = 2;
        } else {
            $direct_order = 1;
        }
        $data['proposal_price'] = 0;
        $data['delivery_id'] = $this->db->query("select * from delivery_times")->row()->delivery_id;
        $data['level_id'] = $sellerLevel;
        $data['language_id'] = $sellerLanguage;
        $data['proposal_status'] = "draft";
        $data['direct_order'] = $direct_order;

        $insertProposal = $this->db->insert("proposals", $data);

        if ($insertProposal) {
            $proposalId = $this->db->insert_id();

            $this->db->insert("instant_deliveries", ["proposal_id" => $proposalId]);

            $this->db->set('no_of_gigs', 'no_of_gigs-1');
            $this->db->where('seller_id', $userId);
            $this->db->update('sellers');

            insertPackages($proposalId);

            $response['message'] = "Proposal saved.";
            $response['status'] = TRUE;
            $response['data'] = [
                'proposal_id' => $proposalId
            ];
            $statusCode = 201;
        }
        return $this->response($response, $statusCode);
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
     */
    public function index_get($userId, $status)
    {
        $this->load->helper('url');
        $this->load->library("pagination");

        $page = ($this->input->get("page")) ? $this->input->get("page") : 1;
        $limit = ($this->input->get("per_page")) ? $this->input->get("per_page") : 10;
        $pagePosition = (($page - 1) * $limit);

        $response = $this->Proposal_model->getProposals($userId, $status, $limit, $pagePosition);

        $total = $response['total'];
        $data['message'] = "No records";
        $data['status'] = FALSE;

        if ($total > 0) {
            $proposals = $response['data'];
            $data['message'] = "Data fetched successfully";
            $data['status'] = TRUE;
            $res = [];

            if ($status == 'modification') {
                foreach ($proposals as $key => $oResult) {
                    $res[$key] = $oResult;
                    $proposalId = $oResult->proposal_id;

                    $select_modification = $this->db->query("SELECT * FROM proposal_modifications WHERE proposal_id = {$proposalId} ORDER BY 1 DESC");
                    $row_modification = $select_modification->row();
                    $res[$key]->modification_message = $row_modification->modification_message;
                }
            } else {
                foreach ($proposals as $key => $oResult) {
                    $res[$key] = $oResult;
                    $proposalId = $oResult->proposal_id;

                    $proposalPrice = $oResult->proposal_price;
                    if ($proposalPrice == 0) {
                        $get_p = $this->db->get_where("proposal_packages", array("proposal_id" => $proposalId, "package_name" => "Basic"));
                        $res[$key]->proposal_price = $get_p->row()->price;
                    }

                    $res[$key]->proposal_img1 = getImageUrl2("proposals", "proposal_img1", $oResult->proposal_img1);
                    $count_orders = $this->db->get_where("orders", array("proposal_id" => $proposalId));
                    $res[$key]->count_offers = $count_orders->num_rows();
                }
            }

            $data['data'] = $res;
            $baseUrl = "api/v1/proposals/{$userId}/{$status}?per_page={$page}&page=";

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
     * Delete proposal | DELTE method.
     *
     * @return Response
     */
    public function index_delete($proposalId)
    {
        $data['message'] = "No records";
        $data['status'] = FALSE;
        $userId = $this->getUserId();

        $this->db->where('proposal_id', $proposalId);
        $this->db->where('proposal_seller_id', $userId);
        $proposal = $this->db->get('proposals')->row();

        if ($proposal) {
            $countOrders = $this->db->get_where("orders", ["proposal_id" => $proposalId]);
            $qOrders = $this->db->query("SELECT * FROM orders WHERE proposal_id={$proposalId} AND order_active='yes' AND order_status IN ('progress', 'pending')");

            if ($qOrders->num_rows() > 0) {
                $data['message'] = "This proposal has pending or progressing order so can not delete.";
            } else {
                $updateData = [
                    "proposal_status" => 'deleted',
                ];

                $this->db->where('proposal_id', $proposalId);
                $this->db->update('proposals', $updateData);

                $this->db->delete('top_proposals', ['proposal_id' => $proposalId]);

                $data['message'] = "One proposal has been deleted.";
                $data['status'] = TRUE;
            }
        }
        $this->response($data, 200);
    }

    /**
     * UPDATE Status [PAUSE/approve] | PUT method.
     * approval: Submit for approval
     *
     * @return Response
     */
    public function updateStatus_put($userId, $id, $status)
    {
        $acceptedStatuses = ['pause', 'activate', 'approval'];

        $data['message'] = "Invalid Status or something with wrong.";
        $data['status'] = FALSE;
        $statusCode = 200;

        if (in_array($status, $acceptedStatuses)) {
            $updatBy['proposal_id'] = $id;
            $updatBy['proposal_seller_id'] = $userId;
            $getProposal = $this->db->get_where("proposals", $updatBy);

            if ($getProposal->num_rows() == 1) {
                if ($status == 'pause') {
                    $statusVal = "admin_pause";
                    insert_log(1, "proposal", $id, "paused");
                } else if ($status == 'activate') {
                    $currentStatus = $getProposal->row()->proposal_status;
                    if ($currentStatus == "pause") {
                        $user = get_seller_info($userId);
                        $pendingProposal = $user->no_of_gigs;

                        if ($pendingProposal > 0) {
                            $statusVal = "active";
                        } else {
                            $data['message'] = "Quata exceeds, Please upgrade you membership plan!";
                            return $this->response($data, $statusCode);
                        }
                    } elseif ($currentStatus == "admin_pause") {
                        $statusVal = "pending";
                    }
                } else if ($status == 'approval') {
                    $select_modification = $this->db->query("SELECT * FROM proposal_modifications WHERE proposal_id = {$id} ORDER BY 1 DESC");
                    $countModification = $select_modification->num_rows();

                    if ($countModification > 0) {
                        $statusVal = "pending";
                        $this->db->delete("proposal_modifications", array("proposal_id" => $id));
                    } else {
                        $data['message'] = "Proposal doesn't need for approval.";
                        return $this->response($data, $statusCode);
                    }
                }
                $updateData['proposal_status'] = $statusVal;

                $response = $this->Proposal_model->update($updateData, $updatBy);

                if ($response > 0) {
                    $data['message'] = "Proposal status updated successfully";
                    $data['status'] = TRUE;
                    $statusCode = 204;
                }
            }
        }

        return $this->response($data, $statusCode);
    }

    /**
     * Get proposal detail
     *
     * @param [type] $proposalId
     * @return void
     */
    public function getDetail_get($proposalId)
    {
        $this->db->where('proposal_id', $proposalId);
        $this->db->where('proposal_status', 'active');
        $proposal = $this->db->get('proposals')->row();

        $data['message'] = "No records";
        $data['status'] = FALSE;

        if ($proposal) {
            $data['message'] = "Data fetched successfully";
            $data['status'] = TRUE;
            $data['data'] = $proposal;
        }

        $this->response($data, 200);
    }

    public function isProposalUrlExist($proposalTitle)
    {
        $sanitizeUrl = proposalUrl($proposalTitle);
        $userId = $this->getUserId();

        $this->db->where('proposal_seller_id', $userId);
        $this->db->where('proposal_url', $sanitizeUrl);
        $isExistsQuery = $this->db->get('proposals');

        if ($isExistsQuery->num_rows() > 1) {
            $this->form_validation->set_message('isProposalUrlExist', 'Opps! Your Already Made A Proposal With Same Title Try Another.');
            return FALSE;
        }

        return TRUE;
    }

    /**
     * get edit proposal | GET method.
     *
     * @return Response
     */
    public function getEditProposalDetail_get($proposalId)
    {
        $userId = $this->getUserId();

        $editProposal = $this->db->get_where("proposals", ["proposal_id" => $proposalId, "proposal_seller_id" => $userId]);

        if ($editProposal->num_rows() == 0) {
            $data['message'] = "Proposal not found";
            $data['status'] = FALSE;

            $statusCode = 404;

            return $this->response($data, $statusCode);
        }

        $data['message'] = "Your request has been fetched successfully!";
        $data['status'] = TRUE;
        $data['data'] = $editProposal->row();

        $statusCode = 200;

        return $this->response($data, $statusCode);
    }

    /**
     * Edit proposal | POST method.
     *
     * @return Response
     */
    public function editProposal_post($proposalId)
    {
        $this->form_validation->set_rules('form', 'Form', 'trim|required');

        if ($this->form_validation->run() === false) {
            // validation not ok, send validation errors to the view
            $this->response([$this->form_validation->error_array()], 422);
        }

        $form = $this->input->post('form');

        $response['message'] = "Something went wrong.";
        $response['status'] = FALSE;
        $statusCode = 400;
        $result = FALSE;

        switch ($form) {
            case 'instant_delivery': // step 2
                $result = $this->submitInstantDelivery($proposalId);
                break;
            case 'price': // step 3
                $result = $this->submitPrice($proposalId);
                break;
            case 'description': // step 4
                $result = $this->submitDescription($proposalId);
                break;
            case 'requirements': // step 5
                $result = $this->submitRequirements($proposalId);
                break;
            case 'gallery': // step 6
                $result = $this->submitGallery($proposalId);
                break;
            case 'approval': // step 7
                $result = $this->submitApproval($proposalId);
                break;
            default: // step 1
                $result = $this->submitOverView($proposalId);
                break;
        }

        if ($result) {
            $response['message'] = "Proposal updated successfully!";
            $response['status'] = TRUE;
            $statusCode = 200;
        }

        return $this->response($response, $statusCode);
    }

    /**
     * Save step 1 overview
     *
     * @param  $proposalId
     * @return void
     */
    private function submitOverView($proposalId)
    {
        $this->form_validation->set_rules('proposal_title', 'Title', 'trim|required');
        $this->form_validation->set_rules('proposal_cat_id', 'Category Id', 'trim|required|numeric');
        $this->form_validation->set_rules('proposal_child_id', 'Sub category Id', 'trim|required|numeric');
        $this->form_validation->set_rules('proposal_tags', 'Tags', 'trim|required');
        $this->form_validation->set_rules('proposal_enable_referrals', 'Enable referrals', 'trim|required');

        if ((isset($_POST['proposal_enable_referrals']) && $_POST['proposal_enable_referrals'] == 'yes')) {
            $this->form_validation->set_rules('proposal_referral_money', 'Percentage Referral', 'trim|required');
        }

        if ($this->form_validation->run() === false) {
            // validation not ok, send validation errors to the view
            $this->response([$this->form_validation->error_array()], 422);
        }

        $rowGeneralSettings = get_general_settings();
        $enableReferrals = $rowGeneralSettings->enable_referrals;

        $data = $this->input->post();
        unset($data['form']);

        if ($enableReferrals == "no") {
            $data['proposal_enable_referrals'] = "no";
        }
        if (isset($data['direct_order'])) {
            $direct_order = 2;
        } else {
            $direct_order = 1;
        }
        $proposalStatus = $this->db->get_where('proposals', ['proposal_id' => $proposalId])->row()->proposal_status;
        if ($proposalStatus == 'active') {
            $data['proposal_status'] = 'pending';
        }
        $data['direct_order'] = $direct_order;

        $this->db->where('proposal_id', $proposalId);
        return $this->db->update("proposals", $data);
    }

    /**
     * Save step 2 Instant delivery
     *
     * @param  $proposalId
     * @return void
     */
    private function submitInstantDelivery($proposalId)
    {
        $this->form_validation->set_rules('enable', 'Enable instant delivery', 'trim|required');

        if ((isset($_POST['enable_instant_delivery']) && $_POST['enable_instant_delivery'] == 1)) {
            $this->form_validation->set_rules('message', 'Message', 'trim|required');
        }

        if ($this->form_validation->run() === false) {
            // validation not ok, send validation errors to the view
            $this->response([$this->form_validation->error_array()], 422);
        }

        $data = $this->input->post();
        unset($data['form']);

        $proposalStatus = $this->db->get_where('proposals', ['proposal_id' => $proposalId])->row()->proposal_status;
        if ($proposalStatus == 'active') {
            $this->db->where('proposal_id', $proposalId);
            $this->db->update("proposals", ['proposal_status' => 'pending']);
        }

        $this->db->where('proposal_id', $proposalId);
        return $this->db->update("instant_deliveries", $data);
    }

    /**
     * Save step 3 Price
     *
     * @param  $proposalId
     * @return void
     */
    private function submitPrice($proposalId)
    {
        $this->form_validation->set_rules('fixed_price', 'Fixed', 'trim|required');

        if ((isset($_POST['fixed_price']) && $_POST['fixed_price'] == 1)) {
            $this->form_validation->set_rules('proposal_price', 'Price', 'trim|required');
            $this->form_validation->set_rules('proposal_revisions', 'Proposal Revisions', 'trim|required');
            $this->form_validation->set_rules('delivery_id', 'Delivery Id', 'trim|required');
        } else {
            for ($i = 0; $i < 3; $i++) {
                $this->form_validation->set_rules("proposal_packages[" . $i . "][package_id]", "Package Id", "trim|required");
                $this->form_validation->set_rules("proposal_packages[" . $i . "][description]", "Description", "trim|required");
                $this->form_validation->set_rules("proposal_packages[" . $i . "][delivery_time]", "Delivery_time", "trim|required");
                $this->form_validation->set_rules("proposal_packages[" . $i . "][revisions]", "Revisions", "trim|required");
                $this->form_validation->set_rules("proposal_packages[" . $i . "][price]", "Price", "trim|required");
            }
        }

        if ($this->form_validation->run() === false) {
            // validation not ok, send validation errors to the view
            $this->response([$this->form_validation->error_array()], 422);
        }

        $fixedPrice = $this->input->post('fixed_price');
        $data = $this->input->post();
        unset($data['form'], $data['fixed_price']);

        if ($fixedPrice == 1) {
            $this->db->where('proposal_id', $proposalId);
            $this->db->update("proposals", $data);
        } else {
            $packages = $this->input->post('proposal_packages');

            // Update packages
            foreach ($packages as $package) {
                $packageId = $package['package_id'];
                $description = $package['description'];
                $deliveryTime = $package['delivery_time'];
                $revisions = $package['revisions'];
                $price = $package['price'];

                $this->db->where('package_id', $packageId);
                $this->db->update("proposal_packages", [
                    "description" => $description,
                    "delivery_time" => $deliveryTime,
                    "revisions" => $revisions,
                    "price" => $price
                ]);
            }
        }

        $proposalStatus = $this->db->get_where('proposals', ['proposal_id' => $proposalId])->row()->proposal_status;
        if ($proposalStatus == 'active') {
            $this->db->where('proposal_id', $proposalId);
            $this->db->update("proposals", ['proposal_status' => 'pending']);
        }

        return TRUE;
    }

    /**
     * Save step 4 Description
     *
     * @param  $proposalId
     * @return void
     */
    private function submitDescription($proposalId)
    {
        $detail = $this->input->post('proposal_desc');
        $faqs = $this->input->post('faq');

        if ($detail != '') {
            $this->db->where('proposal_id', $proposalId);
            $this->db->update("proposals", ['proposal_desc' => $detail]);
        }
        // FAQs
        if (is_array($faqs)) {
            foreach ($faqs as $faq) {
                $data = [
                    'proposal_id' => $proposalId,
                    'title' => $faq['title'],
                    'content' => $faq['content']
                ];

                $this->db->insert('proposals_faq', $data);
            }
        }

        $proposalStatus = $this->db->get_where('proposals', ['proposal_id' => $proposalId])->row()->proposal_status;
        if ($proposalStatus == 'active') {
            $this->db->where('proposal_id', $proposalId);
            $this->db->update("proposals", ['proposal_status' => 'pending']);
        }

        return TRUE;
    }

    /**
     * Save step 5 Requirements
     *
     * @param  $proposalId
     * @return void
     */
    private function submitRequirements($proposalId)
    {
        $detail = $this->input->post('buyer_instruction');

        if ($detail != '') {
            $this->db->where('proposal_id', $proposalId);
            $this->db->update("proposals", ['buyer_instruction' => $detail]);
        }

        $proposalStatus = $this->db->get_where('proposals', ['proposal_id' => $proposalId])->row()->proposal_status;
        if ($proposalStatus == 'active') {
            $this->db->where('proposal_id', $proposalId);
            $this->db->update("proposals", ['proposal_status' => 'pending']);
        }

        return TRUE;
    }

    /**
     * Save step 6 Gallery
     *
     * @param  $proposalId
     * @return void
     */
    private function submitGallery($proposalId)
    {
        $this->form_validation->set_rules('proposal_img1', 'Image', 'trim');

        if ($this->form_validation->run() === false) {
            // validation not ok, send validation errors to the view
            $this->response([$this->form_validation->error_array()], 422);
        }

        $ytUrl = $this->input->post('yt_url');
        $proposal_img1 = $this->input->post('proposal_img1');

        if ($ytUrl != '') {
            $this->db->where('proposal_id', $proposalId);
            $this->db->update("proposals", ['proposal_yt_url' => $ytUrl]);
        }

        if (isset($_FILES['proposal_img1'])) {
            $config['upload_path'] = '../proposals/proposal_files/';
            $config['allowed_types'] = 'gif|jpg|png';

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('proposal_img1')) {
                $error = ['error' => $this->upload->display_errors()];
                $this->response($error, 422);
            }

            $proposal_img1 = $this->upload->data('file_name');
        }

        if ($proposal_img1 != '') {
            $this->db->where('proposal_id', $proposalId);
            $this->db->update("proposals", ['proposal_img1' => $proposal_img1]);
        }

        $proposalStatus = $this->db->get_where('proposals', ['proposal_id' => $proposalId])->row()->proposal_status;
        if ($proposalStatus == 'active') {
            $this->db->where('proposal_id', $proposalId);
            $this->db->update("proposals", ['proposal_status' => 'pending']);
        }

        return TRUE;
    }

    /**
     * Save step 7 Submit for approval
     *
     * @param  $proposalId
     * @return void
     */
    private function submitApproval($proposalId)
    {
        $status = "pending";
        $this->db->where('proposal_id', $proposalId);
        $this->db->update("proposals", ['proposal_status' => $status]);

        return TRUE;
    }
}

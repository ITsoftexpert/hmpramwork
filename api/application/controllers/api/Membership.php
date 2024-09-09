<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/controllers/api/APIAuth.php';

/**
 * Membership class.
 *
 * @extends REST_Controller
 */
class Membership extends APIAuth
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

        $this->load->model('membership_model');
    }

    /**
     * Get membership data function.
     *
     * @access public
     * @return void
     */
    public function membershipData_get($userId)
    {
        $response = $this->membership_model->getMembershipData();

        $total = $response['total'];
        $data['message'] = "No records";
        $data['status'] = FALSE;

        if ($total > 0) {
            $memberships = $response['data'];
            $data['message'] = "Data fetched successfully";
            $data['status'] = TRUE;

            $userData = $this->membership_model->getUserMembershipData($userId);
            $rowPurchased = $userData['data'];

            $res = [];
            foreach ($memberships as $key => $rowPlan) {
                $res[$key] = $rowPlan;
                $planPriceMonth = $rowPlan->plan_price_month;
                $planId = $rowPlan->id;

                if ($rowPurchased == "" && $planPriceMonth == 0) {
                    $res[$key]->user_purchase_status = "Free (Current)";
                } else {
                    if (!$rowPurchased) {
                        $res[$key]->user_purchase_status = "Upgrade";
                    } else {
                        if ($rowPurchased->id == $planId) {
                            $res[$key]->user_purchase_status = "Activated";
                        } else if ($rowPlan->plan_price_month != 0 && $rowPurchased->plan_price_month > $rowPlan->plan_price_month) {
                            $res[$key]->user_purchase_status = "Downgrade";
                        } else if ($rowPlan->plan_price_month != 0 && $rowPurchased->plan_price_month < $rowPlan->plan_price_month) {
                            $res[$key]->user_purchase_status = "Upgrade";
                        } else {
                            $res[$key]->user_purchase_status = "Free";
                        }
                    }
                }
            }

            $data['data'] = $res;

            $data['meta_data']['total'] = $response['total'];
        }
        $this->response($data, 200);
    }
}

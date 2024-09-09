<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/controllers/api/APIAuth.php';

/**
 * Revenue class.
 *
 * @extends REST_Controller
 */
class Revenue extends APIAuth
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
     * Get revenue
     *
     * @return void
     */
    public function index_get($id)
    {
        $qSeller = $this->db->get_where("sellers", array("seller_id" => $id));
        $oSeller = $qSeller->row();

        if (!$oSeller) {
            $data['status'] = FALSE;
            $data['message'] = "User doesn't exists";
            $statusCode = 404;
            $this->response($data, $statusCode);
        }

        $sellerLevel = $oSeller->seller_level;
        $seller_payouts = $oSeller->seller_payouts;

        $qPayment = $this->db->get_where("seller_payment_settings", ["level_id" => $sellerLevel]);;
        $oPayment = $qPayment->row();
        $payout_day = $oPayment->payout_day;
        $payout_time = $oPayment->payout_time;
        $payout_anyday = $oPayment->payout_anyday;
        $payout_date = date("F $payout_day, Y") . " $payout_time";
        $payout_date = new DateTime($payout_date);
        $count_payout = 0;

        $get_payment_settings = $this->db->get_where("payment_settings");
        $row_payment_settings = $get_payment_settings->row();

        $withdrawal_limit = $row_payment_settings->withdrawal_limit;
        $enable_paypal = $row_payment_settings->enable_paypal;
        $enable_stripe = $row_payment_settings->enable_stripe;
        $enable_dusupay = $row_payment_settings->enable_dusupay;
        $enable_coinpayments = $row_payment_settings->enable_coinpayments;
        $enable_paystack = $row_payment_settings->enable_paystack;
        $enable_payoneer = $row_payment_settings->enable_payoneer;
        // $wish_do_manual_payouts = $row_general_settings->wish_do_manual_payouts;

        $qAccounts = $this->db->get_where("seller_accounts", array("seller_id" => $id));
        $oAccounts = $qAccounts->row();
        $current_balance = $oAccounts->current_balance;
        $used_purchases = $oAccounts->used_purchases;
        $pending_clearance = $oAccounts->pending_clearance;
        $withdrawn = $oAccounts->withdrawn;
        $withdrawal_limit = $row_payment_settings->withdrawal_limit;

        $data['status'] = TRUE;
        $data['message'] = "Data fetched successfully";

        $data['data'] = new stdClass(); //the magic
        $data['data']->withdraw_limit_text = "";

        if ($current_balance < $withdrawal_limit and empty($payout_anyday) & $seller_payouts == 0) {
            $data['data']->withdraw_limit_text = "You must have a minimum of at least $withdrawal_limit to withdraw.";
        }

        $data['data']->current_balance = $current_balance;
        $data['data']->withdrawals = $withdrawn;
        $data['data']->used_purchases = $used_purchases;
        $data['data']->pending_clearance = $pending_clearance;
        $data['data']->available_income = $current_balance;

        $qRevenues = $this->db->get_where("revenues", array("seller_id" => $id));
        $data['data']->revenues = [];
        if ($qRevenues->num_rows() > 0) {
            foreach ($qRevenues->result() as $key => $row) {
                $data['data']->revenues[$key]['revenue_id'] = $row->revenue_id;
                $data['data']->revenues[$key]['order_id'] = $row->order_id;
                $data['data']->revenues[$key]['reason'] = $row->reason;
                $data['data']->revenues[$key]['amount'] = $row->amount;
                $data['data']->revenues[$key]['date'] = $row->date;
                $data['data']->revenues[$key]['status'] = $row->status;
            }
        }

        $this->response( $data, 200 );
    }
}

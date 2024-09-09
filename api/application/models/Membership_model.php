<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Membership_model extends CI_Model
{
    /**
     * CONSTRUCTOR | LOAD DB
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Membership Data | GET method.
     *
     * @return Response
     */
    public function getMembershipData()
    {
        $getPlan = $this->db->get_where("membership_table", ["plan_status" => "Active"])->result_object();
        $rowCount = count($getPlan);

        return ['data' => $getPlan, 'total' => $rowCount];
    }

    /**
     * User Membership Data | GET method.
     *
     * @return Response
     */
    public function getUserMembershipData($userId)
    {
        $spQuery = "SELECT mp.* FROM `memb_plan_detail` mp LEFT JOIN `membership_table` m ON mp.memb_tbl_id = m.id WHERE mp.seller_id = ? AND mp.memb_status = 'Active' AND mp.memb_end_date >= CURRENT_TIMESTAMP ORDER BY mp.id DESC LIMIT 1";
        $sBind = [$userId];

        $query = $this->db->query($spQuery, $sBind)->row();

        return ['data' => $query];
    }
}

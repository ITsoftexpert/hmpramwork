<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Referral_model extends CI_Model
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
     * SHOW | GET method.
     *
     * @return Response
     */
    public function getProposalReferral($userId, $proposalId, $limit, $page)
    {
        $spQuery = "SELECT * FROM proposal_referrals WHERE proposal_id = ?";
        $sQuery = "SELECT * FROM proposal_referrals WHERE proposal_id = ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
        $sBind = [$proposalId];

        //get total number of records from database for pagination
        $query = $this->db->query($spQuery, $sBind);
        $rowCount = $query->num_rows();

        $query = $this->db->query($sQuery, $sBind)->result_object();

        return ['data' => $query, 'total' => $rowCount];
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
     */
    public function getMyReferrals($userId, $limit, $page)
    {
        $spQuery = "SELECT * FROM referrals WHERE seller_id = ?";
        $sQuery = "SELECT * FROM referrals WHERE seller_id = ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
        $sBind = [$userId];

        //get total number of records from database for pagination
        $query = $this->db->query($spQuery, $sBind);
        $rowCount = $query->num_rows();

        $query = $this->db->query($sQuery, $sBind)->result_object();

        return ['data' => $query, 'total' => $rowCount];
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
     */
    public function getMyProposalReferrals($userId, $limit, $page)
    {
        $spQuery = "SELECT * FROM proposal_referrals WHERE referrer_id = ?";
        $sQuery = "SELECT * FROM proposal_referrals WHERE referrer_id = ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
        $sBind = [$userId];

        //get total number of records from database for pagination
        $query = $this->db->query($spQuery, $sBind);
        $rowCount = $query->num_rows();

        $query = $this->db->query($sQuery, $sBind)->result_object();

        return ['data' => $query, 'total' => $rowCount];
    }
}

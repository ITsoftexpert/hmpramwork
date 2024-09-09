<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Coupon_model extends CI_Model
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
    public function getProposalCoupons($userId, $proposalId, $limit, $page)
    {
        $spQuery = "SELECT * FROM coupons WHERE proposal_id = ?";
        $sQuery = "SELECT * FROM coupons WHERE proposal_id = ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
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
    public function show($id)
    {
        $query = $this->db->get_where("coupons", ['coupon_id' => $id])->row();
        return $query;
    }

    /**
     * INSERT | POST method.
     *
     * @return Response
     */
    public function insert($data)
    {
        $this->db->insert('coupons', $data);
        return $this->db->insert_id();
    }

    /**
     * UPDATE | PUT method.
     *
     * @return Response
     */
    public function update($data, $updateCondition)
    {
        $data = $this->db->update('coupons', $data, $updateCondition);
        //echo $this->db->last_query();
        return $this->db->affected_rows();
    }

    /**
     * DELETE method.
     *
     * @return Response
     */
    public function delete($deleteCondition)
    {
        $this->db->delete('coupons', $deleteCondition);
        return $this->db->affected_rows();
    }
}

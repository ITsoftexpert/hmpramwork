<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proposal_model extends CI_Model {

    /**
     * CONSTRUCTOR | LOAD DB
    */
    public function __construct() {
       parent::__construct();
       $this->load->database();
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
    */
	public function getProposals($userId, $status, $limit, $page)
	{
        if ($status == "pause") {
            $whereCondition = "(proposal_status='pause' or proposal_status='admin_pause')";
        } else {
            $whereCondition = "proposal_status='{$status}'";
        }

        $spQuery = "SELECT * FROM proposals WHERE proposal_seller_id = ? AND {$whereCondition}";
        $sQuery = "SELECT * FROM proposals WHERE proposal_seller_id = ? AND {$whereCondition} ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
        $sBind = [$userId];

        //get total number of records from database for pagination
        $query = $this->db->query($spQuery, $sBind);
        $rowCount = $query->num_rows();

        $query = $this->db->query($sQuery, $sBind)->result_object();

        return ['data' => $query, 'total' => $rowCount];
	}

    /**
     * INSERT | POST method.
     *
     * @return Response
    */
    public function insert($data)
    {
        $this->db->insert('products',$data);
        return $this->db->insert_id();
    }

    /**
     * UPDATE | PUT method.
     *
     * @return Response
    */
    public function update($data, $updateCondition)
    {
        $data = $this->db->update('proposals', $data, $updateCondition);
        //echo $this->db->last_query();
		return $this->db->affected_rows();
    }

    /**
     * DELETE method.
     *
     * @return Response
    */
    public function delete($id)
    {
        $this->db->delete('products', array('id'=>$id));
        return $this->db->affected_rows();
    }
}

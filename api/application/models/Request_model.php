<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request_model extends CI_Model
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
     * Categories | GET method.
     *
     * @return Response
     */
    public function getSellerCategories($userId)
    {
        $query = $this->getBuyerRequestQuery($userId);
        if ($query != "")
            $query = "WHERE {$query}";
        $qCategories = $this->db->query("SELECT * FROM categories_children {$query}");
        $categories = ["all" => "All Subcategories"];
        if ($query != "") {
            foreach ($qCategories->result() as $row) {
                $child_id = $row->child_id;
                $get_meta = $this->db->get_where("child_cats_meta", array("child_id" => $child_id, "language_id" => 1));
                $row_meta = $get_meta->row();
                $child_title = $row_meta->child_title;
                $categories[$child_id] = $child_title;
            }
        }

        return ['data' => $categories];
    }

    /**
     * Buyer request | GET method.
     *
     * @return Response
     */
    public function getSellerBuyerRequests($userId, $categoryId, $searchKeyword, $limit, $page)
    {
        $requestsQuery = $this->getBuyerRequestQuery($userId);
        if ($requestsQuery != "") {
            $searchWhere = "";
            if ($searchKeyword != '')
                $searchWhere = " AND request_description LIKE '%{$searchKeyword}%'";

            if ($categoryId == "all") {
                $spQuery = "SELECT * FROM buyer_requests WHERE request_status = ? AND {$requestsQuery} {$searchWhere} AND seller_id != ?";
                $sQuery = "SELECT * FROM buyer_requests WHERE request_status = ? AND {$requestsQuery} {$searchWhere} AND seller_id != ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
                $sBind = ['active', $userId];
            } else {
                $spQuery = "SELECT * FROM buyer_requests WHERE request_status = ? AND child_id = ? {$searchWhere} AND seller_id != ?";
                $sQuery = "SELECT * FROM buyer_requests WHERE request_status = ? AND child_id = ? {$searchWhere} AND seller_id != ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
                $sBind = ['active', $userId, $categoryId];
            }
        }

        //get total number of records from database for pagination
        $query = $this->db->query($spQuery, $sBind);
        $rowCount = $query->num_rows();

        $query = $this->db->query($sQuery, $sBind)->result_object();

        return ['data' => $query, 'total' => $rowCount];
    }

    private function getBuyerRequestQuery($userId)
    {
        $query = "";

        $request_child_ids = [];

        $query = $this->db->query("SELECT DISTINCT proposal_child_id FROM `proposals` WHERE proposal_seller_id = {$userId} AND proposal_status = 'active' ORDER BY proposal_child_id;");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $proposal_child_id = $row->proposal_child_id;
                array_push($request_child_ids, $proposal_child_id);
            }
            $ids = implode(', ', $request_child_ids);
            $query = "child_id IN ({$ids})";
        }

        return $query;
    }

    /**
     * Sellers offers sent | GET method.
     *
     * @return Response
     */
    public function getSellerOffersSent($userId, $limit, $page)
    {
        $whereLimit = " ORDER BY 1 DESC LIMIT $page, $limit";

        $query = "";
        $qSender =  $this->db->get_where("send_offers", ["sender_id" => $userId]);
        $total = $qSender->num_rows();
        if ($total > 0) {
            $query =  $this->db->query("SELECT * FROM send_offers WHERE sender_id = ? $whereLimit", [$userId])->result_object();
        }

        return ['data' => $query, 'total' => $total];
    }

    /**
     * Manage request | GET method.
     *
     * @return Response
     */
    public function getManagerRequests($userId, $status, $limit, $page)
    {
        $spQuery = "SELECT * FROM buyer_requests WHERE request_status = ? AND seller_id = ?";
        $sQuery = "SELECT * FROM buyer_requests WHERE request_status = ? AND seller_id = ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
        $sBind = [$status, $userId];

        //get total number of records from database for pagination
        $query = $this->db->query($spQuery, $sBind);
        $rowCount = $query->num_rows();

        $query = $this->db->query($sQuery, $sBind)->result_object();

        return ['data' => $query, 'total' => $rowCount];
    }

    /**
     * UPDATE | PUT method.
     *
     * @return Response
    */
    public function update($data, $updateCondition)
    {
        $data = $this->db->update('buyer_requests', $data, $updateCondition);
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
        $this->db->delete('buyer_requests', $deleteCondition);
        $affectedRows = $this->db->affected_rows();

        if ($affectedRows > 0) {
            $this->db->delete("send_offers",array('request_id' => $deleteCondition['request_id']));
        }
        return $affectedRows;
    }
}

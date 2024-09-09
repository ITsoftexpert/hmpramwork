<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contact_model extends CI_Model
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
    public function getSellerContacts($userId, $limit, $page)
    {
        $spQuery = "SELECT * FROM my_buyers WHERE seller_id = ?";
        $sQuery = "SELECT * FROM my_buyers WHERE seller_id = ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
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
    public function getBuyerContacts($userId, $limit, $page)
    {
        $spQuery = "SELECT * FROM my_sellers WHERE buyer_id = ?";
        $sQuery = "SELECT * FROM my_sellers WHERE buyer_id = ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
        $sBind = [$userId];

        //get total number of records from database for pagination
        $query = $this->db->query($spQuery, $sBind);
        $rowCount = $query->num_rows();

        $query = $this->db->query($sQuery, $sBind)->result_object();

        return ['data' => $query, 'total' => $rowCount];
    }

    /**
     * Get buyer histories
     *
     * @param [type] $sellerId
     * @param [type] $buyerId
     * @param [type] $limit
     * @param [type] $page
     * @return void
     */
    public function getSellerContactHistories($sellerId, $buyerId, $limit, $page)
    {
        $spQuery = "SELECT * FROM orders WHERE seller_id = ? AND buyer_id = ?";
        $sQuery = "SELECT * FROM orders WHERE seller_id = ? AND buyer_id = ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
        $sBind = [$sellerId, $buyerId];

        //get total number of records from database for pagination
        $query = $this->db->query($spQuery, $sBind);
        $rowCount = $query->num_rows();

        $query = $this->db->query($sQuery, $sBind)->result_object();

        return ['data' => $query, 'total' => $rowCount];
    }

    /**
     * Get seller  histories
     *
     * @param [type] $sellerId
     * @param [type] $buyerId
     * @param [type] $limit
     * @param [type] $page
     * @return void
     */
    public function getBuyerContactHistories($buyerId, $sellerId, $limit, $page)
    {
        $spQuery = "SELECT * FROM orders WHERE buyer_id = ? AND seller_id = ?";
        $sQuery = "SELECT * FROM orders WHERE buyer_id = ? AND seller_id = ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
        $sBind = [$buyerId, $sellerId];

        //get total number of records from database for pagination
        $query = $this->db->query($spQuery, $sBind);
        $rowCount = $query->num_rows();

        $query = $this->db->query($sQuery, $sBind)->result_object();

        return ['data' => $query, 'total' => $rowCount];
    }
}

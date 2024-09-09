<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends CI_Model
{
    protected $table = 'orders';

    /**
     * CONSTRUCTOR | LOAD DB
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Buyers Orders | GET method.
     *
     * @return Response
     */
    public function getBuyerOrders($userId, $status, $limit, $page)
    {
        $statusArray = ['yes', 'delivered', 'completed', 'cancelled'];

        if (in_array($status, $statusArray)) {
            if ($status == 'yes') {
                $spQuery = "SELECT * FROM orders WHERE buyer_id = ?  AND order_active = ?";
                $sQuery = "SELECT * FROM orders WHERE buyer_id = ?  AND order_active = ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
                $sBind = [$userId, $status];
            } else {
                $spQuery = "SELECT * FROM orders WHERE buyer_id = ?  AND order_status = ?";
                $sQuery = "SELECT * FROM orders WHERE buyer_id = ?  AND order_status = ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
                $sBind = [$userId, $status];
            }
        } else {
            $spQuery = "SELECT * FROM orders WHERE buyer_id = ?";
            $sQuery = "SELECT * FROM orders WHERE buyer_id = ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
            $sBind = [$userId];
        }
        //get total number of records from database for pagination
        $query = $this->db->query($spQuery, $sBind);
        $rowCount = $query->num_rows();

        $query = $this->db->query($sQuery, $sBind)->result_object();

        return ['data' => $query, 'total' => $rowCount];
    }

    /**
     * Sellers Orders | GET method.
     *
     * @return Response
     */
    public function getSellerOrders($userId, $status, $limit, $page)
    {
        $statusArray = ['yes', 'delivered', 'completed', 'cancelled'];

        if (in_array($status, $statusArray)) {
            if ($status == 'yes') {
                $spQuery = "SELECT * FROM orders WHERE seller_id = ?  AND order_active = ?";
                $sQuery = "SELECT * FROM orders WHERE seller_id = ?  AND order_active = ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
                $sBind = [$userId, $status];
            } else {
                $spQuery = "SELECT * FROM orders WHERE seller_id = ?  AND order_status = ?";
                $sQuery = "SELECT * FROM orders WHERE seller_id = ?  AND order_status = ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
                $sBind = [$userId, $status];
            }
        } else {
            $spQuery = "SELECT * FROM orders WHERE seller_id = ?";
            $sQuery = "SELECT * FROM orders WHERE seller_id = ? ORDER BY 1 DESC LIMIT " . $page . ", " . $limit;
            $sBind = [$userId];
        }
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
    public function show($userId, $orderId)
    {
        $sql = "SELECT * FROM orders WHERE order_id = ? AND (seller_id = ? OR buyer_id = ?)";
        $query = $this->db->query($sql, [$orderId, $userId, $userId]);

        return $query->row();
        // $this->db->query('YOUR QUERY HERE');
        // $this->db->select('*');
        // $this->db->from('orders');
        // $this->db->where("order_id", $orderId);
        // $this->db->where("seller_id", $userId);
        // $this->db->or_where("buyer_id", $userId);
        // $this->db->get()->result();
        // echo $this->db->last_query(); die();
    }

    /**
     * INSERT | POST method.
     *
     * @return Response
     */
    public function insert($data)
    {
        $this->db->insert('products', $data);
        return $this->db->insert_id();
    }

    /**
     * UPDATE | PUT method.
     *
     * @return Response
     */
    public function update($data, $id)
    {
        $data = $this->db->update('products', $data, array('id' => $id));
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
        $this->db->delete('products', array('id' => $id));
        return $this->db->affected_rows();
    }
}

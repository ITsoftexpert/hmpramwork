<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerSupport_model extends CI_Model
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
     * INSERT | POST method.
     *
     * @return Response
     */
    public function insert($data)
    {
        $this->db->insert('support_tickets', $data);
        return $this->db->insert_id();
    }
}

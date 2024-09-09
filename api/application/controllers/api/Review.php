<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/controllers/api/APIAuth.php';

/**
 * Review class.
 *
 * @extends REST_Controller
 */
class Review extends APIAuth
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
     * SHOW | GET method.
     *
     * @return Response
     */
    public function index_get($proposalId)
    {
        $spQuery = "SELECT * FROM buyer_reviews WHERE proposal_id = ?";
        $sQuery = "SELECT * FROM buyer_reviews WHERE proposal_id = ? ORDER BY 1 DESC";
        $sBind = [$proposalId];

        //get total number of records from database for pagination
        $query = $this->db->query($spQuery, $sBind);
        $rowCount = $query->num_rows();

        $data['message'] = "No records";
        $data['status'] = FALSE;

        if ($rowCount > 0) {
            $query = $this->db->query($sQuery, $sBind)->result_object();

            $data['message'] = "Data fetched successfully";
            $data['status'] = TRUE;
            $res = [];

            foreach ($query as $key => $row) {
                $res[$key] = $row;
                $reviewBuyerId = $row->review_buyer_id;
                $this->db->where('seller_id', $reviewBuyerId);

                $rowBuyer = $this->db->get('sellers')->row();
                $res[$key]->buyer_image = getImageUrl2("sellers", "seller_image", $rowBuyer->seller_image);
            }

            $data['data'] = $res;
        }

        $this->response($data, 200);
    }
}

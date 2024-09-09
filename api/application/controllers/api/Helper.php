<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/controllers/api/APIAuth.php';

/**
 * Helper class.
 *
 * @extends REST_Controller
 */
class Helper extends APIAuth
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
     * Get occupation categories
     *
     * @return void
     */
    public function occupationsCategory_get()
    {
        $query = $this->db->get('categories');

        $data['message'] = "Data fetched successfully";
        $data['status'] = TRUE;
        foreach ($query->result() as $key=>$row) {
            $data['data'][$key]["cat_id"] = $row->cat_id;

            $this->db->where('cat_id', $row->cat_id);
            $categoryMeta = $this->db->get("cats_meta")->row();
            $data['data'][$key]["title"] = $categoryMeta->cat_title;
        }
            $this->response($data, 200);
    }

    /**
     * Get category options
     *
     * @param [type] $categoryId
     * @return void
     */
    public function occupationsCategoryOption_get($categoryId)
    {
        $this->db->where('category_id', $categoryId);
        $this->db->where('status', 1);
        $query = $this->db->get('professional_info');

        $rowCount = $query->num_rows();

        $total = $rowCount;
        $data['message'] = "No records";
        $data['status'] = FALSE;

        if ($rowCount > 0) {
            $data['message'] = "Data fetched successfully";
            $data['status'] = TRUE;
            $data['data'] = $query->result();
        }

        $this->response( $data, 200 );
    }

    /**
     * Get user available skills to add
     *
     * @return void
     */
    public function occupationsSkill_get()
    {
        $userId = $this->getUserId();

        $this->db->where('seller_id', $userId);
        $query = $this->db->get('skills_relation');

        $rowCount = $query->num_rows();

        $sSkills = [];
        $queryWhere = '';
        if ($rowCount > 0) {
            foreach ($query->result() as $key=>$row) {
                array_push($sSkills, $row->skill_id);
            }
        }
        $sSkills = implode(",", $sSkills);

        if (!empty($sSkills)) {
            $queryWhere = "WHERE NOT skill_id IN ($sSkills)";
        }

        $getSellerSkills = $this->db->query("SELECT * FROM seller_skills $queryWhere");

        $data['message'] = "Data fetched successfully";
        $data['status'] = TRUE;
        $data['data'] = $getSellerSkills->result();
        $this->response( $data, 200 );
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * User_model class.
 *
 * @extends CI_Model
 */
class General_model extends CI_Model
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
        $this->load->database();
    }

    function checkPlugin($plugin, $site = "")
    {
        if ($plugin == "videoPlugin" and $site == "site") {
            $videoPlugin = $this->db->get_where("plugins", array("folder" => $plugin, "status" => 1))->num_rows();
            if ($videoPlugin != 0) {
                $row_general_settings = $this->db->get("general_settings")->row();
                $opentok_api_key = $row_general_settings->opentok_api_key;
                $opentok_api_secret = $row_general_settings->opentok_api_secret;
                if (!empty($opentok_api_key) and !empty($opentok_api_secret)) {
                    return 1;
                } else {
                    return 0;
                }
            }
        } else {
            return $this->db->get_where("plugins", array("folder" => $plugin, "status" => 1))->num_rows();
        }
    }

    function getGenerailSetting()
    {
        return $this->db->get("general_settings")->row();
    }

    function getMembership($id)
    {
        return $this->db->get_where("membership_table", array("id" => $id))->row();
    }

    function getAdmins()
    {
        return $this->db->get("admins");
    }
}

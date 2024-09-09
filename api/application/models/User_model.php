<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * User_model class.
 *
 * @extends CI_Model
 */
class User_model extends CI_Model
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

	/**
	 * create_user function.
	 *
	 * @access public
	 * @param array $data
	 * @return bool true on success, false on failure
	 */
	public function create_user($data)
	{
		$this->load->model('general_model');
		$paymentGateway = $this->general_model->checkPlugin("paymentGateway", "site");

		$this->db->insert('sellers', $data);
		$insertId = $this->db->insert_id();

		if ($insertId) {
			$this->db->insert("seller_accounts", ["seller_id" => $insertId]);
			if ($paymentGateway == 1) {
				$this->db->insert("seller_settings", ["seller_id" => $insertId]);
			}
		}

		return $insertId;
	}

	/**
	 * resolve_user_login function.
	 *
	 * @access public
	 * @param mixed $username
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
	public function resolve_user_login($username, $password)
	{

		$this->db->select('seller_pass,');
		$this->db->from('sellers');
		$this->db->where('seller_user_name', $username);
		$hash = $this->db->get()->row('seller_pass');

		return $this->verify_password_hash($password, $hash);
	}

	/**
	 * get_user_id_from_username function.
	 *
	 * @access public
	 * @param mixed $username
	 * @return int the user id
	 */
	public function get_user_id_from_username($username)
	{

		$this->db->select('seller_id');
		$this->db->from('sellers');
		$this->db->where('seller_user_name', $username);

		return $this->db->get()->row('seller_id');
	}

	/**
	 * get_user function.
	 *
	 * @access public
	 * @param mixed $identity seller id or email
	 * @return object the user object
	 */
	public function get_user($identity)
	{
		$this->db->from('sellers');
		if (filter_var($identity, FILTER_VALIDATE_EMAIL))
			$this->db->where('seller_email', $identity);
		else
			$this->db->where('seller_id', $identity);
		return $this->db->get()->row();
	}

	/**
	 * hash_password function.
	 *
	 * @access private
	 * @param mixed $password
	 * @return string|bool could be a string on success, or bool false on failure
	 */
	private function hash_password($password)
	{

		return password_hash($password, PASSWORD_DEFAULT);
	}

	/**
	 * verify_password_hash function.
	 *
	 * @access private
	 * @param mixed $password
	 * @param mixed $hash
	 * @return bool
	 */
	private function verify_password_hash($password, $hash)
	{

		return password_verify($password, $hash);
	}

	public function emailExists(string $email): bool
	{
		return (bool)$this->db->where(['seller_email' => $email])->count_all_results('sellers');
	}

	public function updateUserData(array $data, int $userId)
	{
		$data = $this->db->update('sellers', $data, array('seller_id'=>$userId));

		return $this->db->affected_rows();
	}
}

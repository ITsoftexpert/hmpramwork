<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Auth class.
 *
 * @extends REST_Controller
 */
require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Auth extends REST_Controller
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
		$this->load->library('Authorization_Token');
		$this->load->model('user_model');
		$this->load->model('general_model');
	}

	/**
	 * register function.
	 *
	 * @access public
	 * @return void
	 */
	public function register_post()
	{
		// set validation rules
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('user_name', 'Username', 'trim|required|alpha_numeric|min_length[4]|is_unique[sellers.seller_user_name]', array('is_unique' => 'This username already exists. Please choose another one.'));
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[sellers.seller_email]', array('is_unique' => 'This email already exists. Please choose another one.'));
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('password_confirm', 'Confirm Password', 'trim|required|min_length[8]|matches[password]');

		if ($this->form_validation->run() === false) {
			// validation not ok, send validation errors to the view
			$this->response([$this->form_validation->error_array()], REST_Controller::HTTP_OK);
		} else {
			// set variables from the form
			$data['seller_user_name'] = $this->input->post('user_name');
			$data['seller_email'] = $this->input->post('email');
			$password = $this->input->post('password');
			$data['seller_name'] = $this->input->post('name');

			$ip = $this->input->ip_address();
			$geoPlugin = getGeoFromIP($ip);

			$data['seller_country'] = '';
			$data['seller_timezone'] = 'UTC';
			if ($geoPlugin['geoplugin_status'] == 200) {
				$data['seller_country'] = $geoPlugin['geoplugin_countryName'];
				$data['seller_timezone'] = $geoPlugin['geoplugin_timezone'];
			}
			$generalSetting = $this->general_model->getGenerailSetting();
			$signupEmail = $generalSetting->signup_email;
			$siteName = $generalSetting->site_name;
			$siteUrl = $generalSetting->site_url;

			$membership = $this->general_model->getMembership(1);
			$data["no_of_gigs"] = $membership->create_active_service;
			$data["bids_per_month"] = $membership->bids_per_month;
			$data["skills"] = $membership->skills;
			$data["comission_per_sale"] = $membership->percentage_per_project;
			$data["create_porfolio"] = $membership->create_portfolio;
			$data["project_bookmarks"] = $membership->project_bookmark;

			$date = date("F d, Y");

			$data['seller_verification'] = "ok";
			if ($signupEmail == "yes")
				$data['seller_verification'] = mt_rand();
			$referralCode = mt_rand();
			$data['seller_pass'] = password_hash($password, PASSWORD_DEFAULT);
			$data['seller_activity'] = $data['seller_register_date'] = date("Y-m-d H:i:s");
			$data['seller_level'] = 1;
			$data['seller_recent_delivery'] = "none";
			$data['seller_rating'] = 100;
			$data['seller_offers'] = 10;
			$data['seller_referral'] = $referralCode;
			$data['seller_ip'] = $ip;
			$data['seller_vacation'] = "off";
			$data['seller_status'] = "online";

			if ($res = $this->user_model->create_user($data)) {
				// user creation ok
				$token_data['seller_id'] = $res;
				$token_data['user_name'] = $data['seller_user_name'];
				$tokenData = $this->authorization_token->generateToken($token_data);

				if ($signupEmail == "yes") {
					$emailData = [];
					$emailData['template'] = "email_confirmation";
					$emailData['to'] = $data['seller_email'];
					$emailData['subject'] = "{$siteName} : Activate Your New Account!";
					$emailData['seller_user_name'] = $data['seller_user_name'];
					$emailData['data'] = ['verification_link' => $siteUrl . "/includes/verify_email?code=" . $data['seller_verification']];

					$emailAdminData = [];
					$emailAdminData['template'] = "new_user";
					$emailAdminData['subject'] = "{$siteName} : A New User Has Registered.";
					$emailAdminData['seller_user_name'] = $data['seller_user_name'];

					proceedSendEmails($emailData, $emailAdminData);
				}

				$final = [];
				$final['access_token'] = $tokenData;
				$final['status'] = true;
				$final['seller_id'] = $res;
				$final['message'] = 'Thank you for registering your new account!';
				$final['note'] = 'You have successfully register. Please check your email inbox to confirm your email address.';

				$this->response($final, REST_Controller::HTTP_OK);
			} else {
				// user creation failed, this should never happen
				$this->response(['There was a problem creating your new account. Please try again.'], REST_Controller::HTTP_OK);
			}
		}
	}

	/**
	 * password reset request function.
	 *
	 * @access public
	 * @return void
	 */
	public function passwordResetRequest_post()
	{
		// set validation rules
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

		if ($this->form_validation->run() === false) {
			// validation not ok, send validation errors to the view
			$this->response([$this->form_validation->error_array()], REST_Controller::HTTP_OK);
		} else {
			$email = $this->input->post('email');
			if ($this->user_model->emailExists($email)) {
				$user = $this->user_model->get_user($email);

				$generalSetting = $this->general_model->getGenerailSetting();
				$signupEmail = $generalSetting->signup_email;
				$siteName = $generalSetting->site_name;
				$siteUrl = $generalSetting->site_url;

				$emailData['template'] = "password_reset";
				$emailData['to'] = $user->seller_email;
				$emailData['subject'] = "{$siteName} : Password Reset";
				$emailData['seller_user_name'] = $user->seller_user_name;
				$emailData['data'] = ['reset_link' => $siteUrl . "/change_password?code=" . $user->seller_pass . "&username=" . $user->seller_user_name];

				proceedSendEmails($emailData);
			}

			$final['status'] = true;
			$final['message'] = 'success';
			$final['note'] = 'An email has been sent to your email address with instructions on how to change your password.';

			$this->response($final, REST_Controller::HTTP_OK);
		}
	}

	/**
	 * login function.
	 *
	 * @access public
	 * @return void
	 */
	public function login_post()
	{
		// set validation rules
		$this->form_validation->set_rules('user_name', 'Username', 'required|alpha_numeric');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == false) {
			// validation not ok, send validation errors to the view
			$this->response([$this->form_validation->error_array()], REST_Controller::HTTP_OK);
		} else {

			// set variables from the form
			$username = $this->input->post('user_name');
			$password = $this->input->post('password');

			if ($this->user_model->resolve_user_login($username, $password)) {

				$user_id = $this->user_model->get_user_id_from_username($username);
				$user    = $this->user_model->get_user($user_id);

				$userStatus = $user->seller_status;

				if ($userStatus == 'block-ban')
					$errMsg = "You have been blocked by the Admin. Please contact customer support.";
				else if ($userStatus == 'deactivated')
					$errMsg = "You have deactivated your account, please contact us for more details.";
				if (isset($errMsg)) {
					$this->response([$errMsg], REST_Controller::HTTP_OK);
				} else {
					// set session user datas
					$_SESSION['user_id']      = (int)$user->seller_id;
					$_SESSION['username']     = (string)$user->seller_user_name;
					$_SESSION['logged_in']    = (bool)true;

					// user login ok
					$token_data['seller_id'] = $user_id;
					$token_data['user_name'] = $user->seller_user_name;
					$tokenData = $this->authorization_token->generateToken($token_data);

					$updateForm['seller_ip'] = $this->input->ip_address();
					$updateForm['device_type'] = 'APP';

					$this->user_model->updateUserData($updateForm,  $_SESSION['user_id']);

					$final = array();
					$final['status'] = true;
					$final['message'] = 'Login success!';
					$final['note'] = 'You are now logged in.';
					$final['data'] = ['user_id' => $_SESSION['user_id'], 'access_token' => $tokenData];

					$this->response($final, REST_Controller::HTTP_OK);
				}
			} else {
				// login failed
				$this->response(['Opps! password or username is incorrect. Please try again.'], REST_Controller::HTTP_OK);
			}
		}
	}

	/**
	 * logout function.
	 *
	 * @access public
	 * @return void
	 */
	public function logout_post()
	{
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
			// remove session datas
			foreach ($_SESSION as $key => $value) {
				unset($_SESSION[$key]);
			}
			// user logout ok
			$this->response(['Logout success!'], REST_Controller::HTTP_OK);
		} else {
			// there user was not logged in, we cannot logged him out,
			// redirect him to site root
			// redirect('/');
			$this->response(['There was a problem. Please try again.'], REST_Controller::HTTP_OK);
		}
	}
}

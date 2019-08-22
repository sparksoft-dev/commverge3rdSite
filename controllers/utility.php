<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Utility extends CI_Controller {
	private $ENABLENPM = true;
	private $NPMHOST = '10.174.241.65';
	private $NPMPORT = '8080';
	private $NPMLOGIN = 'lek';
	private $NPMPASSWORD = 'seron';
	private $NPMAPI = '11.1.1.5';
	private $NPMTIMEOUT = '10';
	// $SESSIONXXX series is the main aaa db
	private $SESSIONUSERNAME = 'etplprov';
	private $SESSIONPASSWORD = 'etplprov';
	private $SESSIONHOST = '10.188.118.27';
	private $SESSIONPORT = '1521';
	private $SESSIONSCHEMA = 'eliteaaa';

	public function __construct(){
		parent::__construct();
		$this->load->model('settings');
		$cfg = $this->settings->loadFromFile();
		if (!is_null($cfg)) {
			$this->ENABLENPM = intval($cfg['ENABLENPM']) == 1 ? true : false;
			$this->NPMHOST = strval($cfg['NPMHOST']);
			$this->NPMPORT = strval($cfg['NPMPORT']);
			$this->NPMLOGIN = strval($cfg['NPMLOGIN']);
			$this->NPMPASSWORD = strval($cfg['NPMPASSWORD']);
			$this->NPMAPI = strval($cfg['NPMAPI']);
			$this->NPMTIMEOUT = intval($cfg['NPMTIMEOUT']);
			// $SESSIONXXX series is the main aaa db
			$this->SESSIONUSERNAME = strval($cfg['SESSIONUSERNAME']);
	    	$this->SESSIONPASSWORD = strval($cfg['SESSIONPASSWORD']);
	    	$this->SESSIONHOST = strval($cfg['SESSIONHOST']);
	    	$this->SESSIONPORT = strval($cfg['SESSIONPORT']);
	    	$this->SESSIONSCHEMA = strval($cfg['SESSIONSCHEMA']);
		}
	}

	public function index() {
		$portal = $this->session->userdata('portal');
		if ($portal !== false && $portal == 'service') { //previously logged in to /service when /utility url was entered
			/***********************************************
			 * log sysuser logout
			 ***********************************************/
			$this->load->model('sysusermain');
			$sessionUsername = $this->session->userdata('username');
			$this->sysusermain->setSysuserLoggedOut($sessionUsername);
			$this->sysusermain->setSysuserSession($sessionUsername, null);
			$this->session->sess_destroy();
		} else if ($portal !== false && $portal == 'admin') { //previously logged in to /admin when /utility url was entered
			/***********************************************
			 * log sysuser logout
			 ***********************************************/
			$this->load->model('sysusermain');
			$this->load->model('sysuser');
			$sessionUsername = $this->session->userdata('username');
			$this->sysusermain->setSysuserLoggedOut($sessionUsername);
			$this->sysusermain->setSysuserSession($sessionUsername, null);
			$this->sysuser->recordSysuserLogout($sessionUsername, $this->session->userdata('session'), mktime());
			$this->session->sess_destroy();
		}
		$utilUser = $this->session->userdata('util_user');
		log_message('info', 'util user:'.json_encode($utilUser));
		if ($utilUser !== false) {
			$month = date('m', time());
			$sDay = 1;
			$eDay = date('d', time());
			$year = date('Y', time());
			$data = array(
				'init' => true,
				'month' => $month,
				'start_day' => $sDay,
				'end_day' => $eDay,
				'year' => $year,
				'max' => 20);
			$this->load->view('customer_utility_usage', $data);
		} else {
			$this->load->model('realm');
			$realms = $this->realm->fetchAllNamesOnly();
			$data = array(
				'realms' => $realms,
				'allowBlankInRealm' => false);
			$this->load->view('customer_utility_index', $data);
		}
	}

	public function login() {
		if ($this->input->is_ajax_request()) {
			$username = trim($this->input->post('username'));
			$password = trim($this->input->post('password'));
			$realm = $this->input->post('realm');
			$this->load->model('subscribermain');
			$subscriber = $this->subscribermain->findByUserIdentity($username.'@'.$realm);
			log_message('info', 'username:'.$username.'|realm:'.$realm.'|password:'.$password);
			log_message('info', 'subscriber:'.json_encode($subscriber));
			if ($subscriber === false) {
				$response = array('status' => '0');
			} else {
				if ($subscriber['PASSWORD'] == $password) {
					$this->session->set_userdata('util_user', $username.'@'.$realm);
					$response = array('status' => '1', 'redirect' => 'utility');
				} else {
					$response = array('status' => '0');
				}
			}
			echo json_encode($response);
		} else {
			redirect('utility');
		}
	}
	public function logout() {
		$this->session->sess_destroy();
		redirect('utility');
	}
	public function usage($link = 0, $order = null, $startMonth = null, $startDay = null, $endDay = null, $startYear = null) {
		$username = $this->session->userdata('util_user');
		if ($username === false) {
			redirect('utility');
		}
		$parts = explode('@', $username);
		$name = $parts[0];
		$realm = isset($parts[1]) ? $parts[1] : '';
		if (intval($link) == 0) { //via form
			if ($username === false) {
				$month = date('m', time());
				$sDay = 1;
				$eDay = date('d', time());
				$year = date('Y', time());
				$data = array(
					'init' => true,
					'month' => $month,
					'start_day' => $sDay,
					'end_day' => $eDay,
					'year' => $year,
					'max' => 20);
				$this->load->view('customer_utility_usage', $data);
				return;
			} else {
				$this->load->model('usage');
				$startMonth = $this->input->post('start_month');
				$startDay = $this->input->post('start_day');
				$endDay = $this->input->post('end_day');
				$startYear = $this->input->post('start_year');
				$order = $this->input->post('order');
				$orderParts = explode('-', $order);
				$order = array('column' => $orderParts[0], 'dir' => $orderParts[1]);
				if ($startMonth === false || is_null($startMonth)) {
					redirect('utility/usage/1');
				}
			}
		} else {//via url
			if (is_null($order)) {
				$order = array('column' => 'event_timestamp', 'dir' => 'desc');
			} else {
				$orderParts = explode('-', $order);
				$order = array('column' => $orderParts[0], 'dir' => $orderParts[1]);
			}
		}
		$startdate = mktime(0, 0, 0, $startMonth, $startDay, $startYear);
		$enddate = mktime(0, 0, 0, $startMonth, $endDay, $startYear);
		log_message('info', date('Y-m-d H:i:s', $startdate));
		log_message('info', date('Y-m-d H:i:s', $enddate));
		log_message('info', json_encode($startMonth).' '.json_encode($startYear));
		$this->load->model('usage');
		$usages = $this->usage->fetchAllUsagesByUsername($name, $realm, $startdate, $enddate, $order);
		$count = $this->usage->countUsagesByUsername($name, $realm, $startdate, $enddate);
		$totalSessionTime = $this->usage->getTotalSessionTimeByUsername($name, $realm, $startdate, $enddate);
		$monthText = date('M', $startdate);
		$data = array(
			'init' => is_null($startMonth) ? true : false,
			'usages' => $usages,
			'count' => $count,
			'totalSessionTime' => $totalSessionTime,
			'order' => $order,
			'monthText' => $monthText,
			'month' => is_null($startMonth) ? date('m', time()) : $startMonth,
			'start_day' => is_null($startDay) ? 1 : $startDay,
			'end_day' => is_null($endDay) ? date('d', time()) : $endDay,
			'year' => is_null($startYear) ? date('Y', time()) : $startYear);
		$this->load->view('customer_utility_usage', $data);
	}
	public function changePassword() {
		$user = $this->session->userdata('util_user');
		if ($user === false || $user == '') {
			redirect('utility');
		}
		$current = $this->input->post('current');
		$password = $this->input->post('new_pwd');
		log_message('info', 'user:'.$user);
		log_message('info', 'current:'.$current);
		log_message('info', 'new:'.$password);
		if ($current === false || $password == false || $user === false || $user == '') {
			$this->load->view('customer_utility_change_password');
		} else {
			$data = array();
			$parts = explode('@', $user);
			$username = $parts[0];
			$realm = isset($parts[1]) ? $parts[1] : '';
			$this->load->model('subscribermain');
			$modifieddate = date('Y-m-d H:i:s', time());
			$old = $this->subscribermain->findByUserIdentity($user);
			log_message('info', 'data:'.json_encode($old));
			if ($old['PASSWORD'] != $current) {
				$data['error'] = 'Incorrect current password entered.';
				$this->load->view('customer_utility_change_password', $data);
				return;
			}

			$changed = $this->subscribermain->changeSubscriberPassword($username, $realm, $password, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			if (!$changed) {
				$data['error'] = 'Password change unsuccessful.';
				$this->load->view('customer_utility_change_password', $data);
			} else {
				if ($this->ENABLENPM) {
					$npmResult = true;
					$npmError = '';
					$npmFound = $this->subscribermain->npmFetchXML($username.'@'.$realm, $this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
					if (!$npmFound['found']) {
						$npmResult = false;
						$npmError = $npmFound['error'];
					} else {
						$subscriber = $npmFound['data'];
						$updated = $this->subscribermain->npmUpdateXML($subscriber['USERNAME'], $password, $subscriber['ACTIVATED'], array($subscriber['RADIUSPOLICY']), 
							$subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC'], 'N',
							$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
						if (!$updated['result']) {
							$npmResult = false;
							$npmError = $updated['error'];
						}
					}
					if (!$npmResult) {
						//revert password change
						log_message('info', 'old password: '.$old['PASSWORD']);
						$this->subscribermain->changeSubscriberPassword($username, $old['RBREALM'], $old['PASSWORD'], $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						$data['error'] = 'Unsuccessful password change. NPM: '.$npmError;
						$this->load->view('customer_utility_change_password', $data);
						return;
					}
				}
				$data['message'] = 'Password change successful.<br /> New password: '.$password;
				$this->load->view('customer_utility_change_password', $data);
			}
		}
	}

}
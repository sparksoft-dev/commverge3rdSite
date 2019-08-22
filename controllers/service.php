<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service extends CI_Controller {
	private $SUPERUSER = 'vinadmin';
	// $SESSIONXXX series is the main aaa db
	private $SESSIONHOST = '10.166.12.8';
	private $SESSIONPORT = '1521';
	private $SESSIONSCHEMA = 'aaadb';
	private $SESSIONUSERNAME = 'etplprov';
	private $SESSIONPASSWORD = 'etplprov';

	public function __construct(){
	    parent::__construct();
	    $this->load->model('settings');
	    $cfg = $this->settings->loadFromFile();
	    if (!is_null($cfg)) {
	    	$this->SUPERUSER = $cfg['SUPERUSER'];
	    	// $SESSIONXXX series is the main aaa db
	    	$this->SESSIONHOST = strval($cfg['SESSIONHOST']);
			$this->SESSIONPORT = strval($cfg['SESSIONPORT']);
			$this->SESSIONSCHEMA = strval($cfg['SESSIONSCHEMA']);
			$this->SESSIONUSERNAME = strval($cfg['SESSIONUSERNAME']);
			$this->SESSIONPASSWORD = strval($cfg['SESSIONPASSWORD']);
	    }
	}

	public function index() {
		$sessionUsername = $this->session->userdata('username');
		$portal = $this->session->userdata('portal');
		if ($portal !== false && $portal == 'admin') {
			/***********************************************
			 * log sysuser logout
			 ***********************************************/
			$this->load->model('sysusermain');
			$this->sysusermain->setSysuserLoggedOut($sessionUsername);
			$this->sysusermain->setSysuserSession($sessionUsername, null);
			$this->session->sess_destroy();
		}
		$utilUser = $this->session->userdata('util_user');
		if ($utilUser !== false) {
			$this->session->sess_destroy();	
		}
		$sessionUsername = $this->session->userdata('username');
		$realm = $this->session->userdata('realm');
		$portal = $this->session->userdata('portal');
		log_message('info', '@service|username:'.json_encode($sessionUsername));
		log_message('info', '@service|portal:'.json_encode($portal));
		if ($sessionUsername == '' || $sessionUsername === false) {
			$this->load->model('realm');
			$realms = $this->realm->fetchAllNamesOnly();
			$data = array(
				'allowBlankInRealm' => false,
				'realms' => $realms);
			if (intval($this->session->userdata('relogin')) == 1) {
				$data['loginErrorMessage'] = 'You have successfully changed your password. Log in to use the system.';
				$this->session->unset_userdata('relogin');
			} else if ($this->session->userdata('session_expired') !== false) {
				$data['loginErrorMessage'] = $this->session->userdata('session_expired');
				$this->session->unset_userdata('session_expired');
			} else if ($this->session->userdata('logged_out') !== false) {
				$data['loginErrorMessage'] = 'You have been logged out of the system.';
				$this->session->unset_userdata('logged_out');
			}
			$this->load->view('service_login', $data);
		} else {
			$allowedPagesJSON = $this->session->userdata('allowedPages');
			$thePage = $this->session->userdata('thePage');
			$data = array(
				'thePage' => $thePage === false ? 'main/welcome' : 'main/'.$this->session->userdata('thePage'),
				'username' => $sessionUsername,
				'portal' => $portal,
				'realm' => $realm,
				'allowedPages' => json_decode($allowedPagesJSON, true));
			$this->load->view('service', $data);
			$this->session->unset_userdata('thePage');
		}
	}
	public function showRequiredPasswordChangePage() {
		$username = $this->session->userdata('username');
		$message = $this->session->userdata('changePasswordMessage');
		$portal = $this->session->userdata('portal');

		$data = array(
			'username' => $username,
			'message' => $message);
		$this->load->view('service_require_password_change_notice', $data);
		$this->session->unset_userdata('username');
	}
	/***********************************************************************
	 * services
	 * page08
	 ***********************************************************************/
	public function showServicesIndex() {
		$this->redirectIfNoAccess('Services', 'service/showServicesIndex');
		$realm = $this->session->userdata('realm');
		$this->load->model('services');
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$yesterday = mktime(date('h'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'));
		$yesterday = date('D M d H:i:s T Y', $yesterday);
		// $services = $this->services->fetchAll2();
		$services = $this->services->fetchAll2New($this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
		$activeCounts = $this->services->fetchActiveUserCountsPerService($realm);
		$inactiveCounts = $this->services->fetchInactiveUserCountsPerService($realm);
		// $activeCounts = array();
		// $inactiveCounts = array();
		// for ($i = 0; $i < count($services); $i++) {
		// 	$activeCounts[]['COUNT'] = 0;
		// 	$inactiveCounts[]['COUNT'] = 0;
		// }
		log_message('info', 'services|'.json_encode($services));
		log_message('info', 'active__|'.json_encode($activeCounts));
		log_message('info', 'inactive|'.json_encode($inactiveCounts));
		$data = array(
			'yesterday' => $yesterday,
			'services' => $services,
			'realms' => $realms,
			'realm' => $realm,
			'disableRealm' => true,
			'hideDropdown' => true,
			'allowBlankInRealm' => false,
			'activeCounts' => $activeCounts,
			'inactiveCounts' => $inactiveCounts);
		$this->load->view('services_service', $data);
	}

	public function redirectIfNoAccess($page, $path) {
		$session = $this->session->userdata('session');
		$username = $this->session->userdata('username');
		$portal = $this->session->userdata('portal');
		log_message('info', 'session:'.json_encode($session).'|username:'.json_encode($username).'|portal:'.json_encode($portal));
		if ($username != $this->SUPERUSER) {
			$this->load->model('sysusermain');
			$sessionFromDb = $username !== false ? $this->sysusermain->getSysuserSession($username) : null;
			if (is_null($sessionFromDb)) {
				$this->session->set_userdata('logged_out', 'You have been logged out from the system.');
				$this->session->unset_userdata('username');
				redirect('main/noAccess');
			} else {
				$allowedPagesJSON = $this->session->userdata('allowedPages');
				log_message('debug', '                    ');
				log_message('debug', '          allowed pages: '.$allowedPagesJSON);
				log_message('debug', '                    ');
				$allowedPages = json_decode($allowedPagesJSON, true);
				$allowed = false;
				for ($i = 0; $i < count($allowedPages); $i++) {
					$p = $allowedPages[$i];
					if ($p['NM'] == $page) {
						$allowed = true;
						break;
					}
				}
				if (!$allowed) {
					$sysuser = $this->session->userdata('username');
					$sysuserIP = $this->session->userdata('ip_address');

					$this->load->model('sysuseractivitylog');
					$this->sysuseractivitylog->logSysuserIllegalPageAccess($page, $path, $sysuser, $sysuserIP, time());
					redirect('main/noAccess');
				}
			}
		}
	}
	public function concurrentLogin() {
		$this->load->view('service_existing_session_notice');
	}
	public function concurrentGoPassword() {
		$this->session->set_userdata('thePage', 'showChangeMyPasswordForm');
		redirect('service');
	}
}
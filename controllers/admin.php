<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
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
	    	$this->SESSIONHOST = $cfg['SESSIONHOST'];
	    	$this->SESSIONPORT = $cfg['SESSIONPORT'];
	    	$this->SESSIONSCHEMA = $cfg['SESSIONSCHEMA'];
	    	$this->SESSIONUSERNAME = $cfg['SESSIONUSERNAME'];
	    	$this->SESSIONPASSWORD = $cfg['SESSIONPASSWORD'];
	    }
	}

	public function index() {
		$sessionUsername = $this->session->userdata('username');
		$portal = $this->session->userdata('portal');
		if ($portal !== false && $portal == 'service') {
			/***********************************************
			 * log sysuser logout
			 ***********************************************/
			$this->load->model('sysusermain');
			$this->load->model('sysuser');
			$this->sysusermain->setSysuserLoggedOut($sessionUsername);
			$this->sysusermain->setSysuserSession($sessionUsername, null);
			$this->sysuser->recordSysuserLogout($sessionUsername, $this->session->userdata('session'), mktime());
			$this->session->sess_destroy();
		}
		$utilUser = $this->session->userdata('util_user');
		if ($utilUser !== false) {
			$this->session->sess_destroy();	
		}
		$sessionUsername = $this->session->userdata('username');
		$portal = $this->session->userdata('portal');
		log_message('info', '@admin|username:'.json_encode($sessionUsername));
		log_message('info', '@admin|portal:'.json_encode($portal));
		log_message('info', '@admin|session_expired:'.json_encode($this->session->userdata('session_expired')));
		log_message('info', '@admin|relogin:'.json_encode($this->session->userdata('relogin')));
		log_message('info', '@admin|logged_out:'.json_encode($this->session->userdata('logged_out')));
		if ($sessionUsername == '' || $sessionUsername === false) {
			if (intval($this->session->userdata('relogin')) == 1) {
				$this->load->view('admin_login', array('loginErrorMessage' => 'You have successfully changed your password. Log in to use the system.'));
				$this->session->unset_userdata('relogin');
			} else if ($this->session->userdata('session_expired') !== false) {
				$this->load->view('admin_login', array('loginErrorMessage' => $this->session->userdata('session_expired')));
				$this->session->unset_userdata('session_expired');
			} else if ($this->session->userdata('logged_out') !== false) {
				$this->load->view('admin_login', array('loginErrorMessage' => 'You have been logged out of the system.'));
				$this->session->unset_userdata('logged_out');
			} else {
				$this->load->view('admin_login');
			}
		} else {
			$allowedPagesJSON = $this->session->userdata('allowedPages');
			$allowedRealmsJSON = $this->session->userdata('allowedRealms');
			$thePage = $this->session->userdata('thePage');
			$pages = json_decode($allowedPagesJSON, true);
			$realms = json_decode($allowedRealmsJSON, true);
			$data = array(
				'thePage' => $thePage === false ? 'main/welcome' : 'main/'.$this->session->userdata('thePage'),
				'username' => $sessionUsername,
				'portal' => $portal,
				'realm' => '',
				'allowedPages' => $pages,
				'allowedRealms' => $realms);
			$this->load->view('admin', $data);	
			$this->session->unset_userdata('thePage');
		}
	}
	public function showRequiredPasswordChangePage() {
		$username = $this->session->userdata('username');
		$message = $this->session->userdata('changePasswordMessage');
		log_message('info', '_____'.$message);
		$portal = $this->session->userdata('portal');

		$data = array(
			'username' => $username,
			'message' => $message);
		$this->load->view('admin_require_password_change_notice', $data);
		$this->session->unset_userdata('username');
	}
	/***********************************************************************
	 * services
	 * page08
	 ***********************************************************************/
	public function showServicesIndex() {
		$this->redirectIfNoAccess('Services', 'admin/showServicesIndex');
		$realm = $this->input->post('realm');
		//if $realm === false, via url; if has a value (including ""), via form
		$realm = $realm === false || $realm == '' ? null : $realm;
		$this->load->model('services');
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$yesterday = mktime(date('h'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'));
		$yesterday = date('D M d H:i:s T Y', $yesterday);
		// $services = $this->services->fetchAll2();
		$services = $this->services->fetchAll2New($this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
		// log_message('info', json_encode($services));
		$activeCounts = $this->services->fetchActiveUserCountsPerService($realm);
		$inactiveCounts = $this->services->fetchInactiveUserCountsPerService($realm);
		// $activeCounts = array();
		// $inactiveCounts = array();
		// for ($i = 0; $i < count($services); $i++) {
		// 	$activeCounts[]['COUNT'] = 0;
		// 	$inactiveCounts[]['COUNT'] = 0;
		// }
		log_message('info', 'inactive '.count($inactiveCounts).': '.json_encode($inactiveCounts));
		log_message('info', 'active '.count($activeCounts).' '.json_encode($activeCounts));
		log_message('info', 'services '.count($services).' '.json_encode($services));
		$data = array(
			'yesterday' => $yesterday,
			'services' => $services,
			'realms' => $realms,
			'realm' => $realm == null ? '' : $realm,
			'allowBlankInRealm' => true,
			'activeCounts' => $activeCounts,
			'inactiveCounts' => $inactiveCounts);
		$this->load->view('services_admin', $data);
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
		$this->load->view('admin_existing_session_notice');
	}
	public function concurrentGoPassword() {
		$this->session->set_userdata('thePage', 'showChangeMyPasswordForm');
		redirect('admin');
	}
}
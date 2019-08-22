<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
	private $SUPERUSER = 'vinadmin';
	private $SUPERGROUP = 'VINADMIN';
	private $MAXLOGINTRIES = 3;
	private $SESSIONEXPIRYINMINUTES = 30;
	private $PASSWORDVALIDITYINDAYS = 30;
	private $PASSWORDHISTORYSIZE = 5;
	private $PASSWORDMINLENGTH = 8;
	private $PASSWORDREQUIRENUMBER = true;
	private $PASSWORDREQUIRESYMBOL = true;
	private $PASSWORDALLOWUSERNAME = false;
	private $ENABLENPM = true;
	private $NPMHOST = '10.174.241.65';
	private $NPMPORT = '8080';
	private $NPMLOGIN = 'lek';
	private $NPMPASSWORD = 'seron';
	private $NPMAPI = '11.1.1.5';
	private $NPMTIMEOUT = '10';
	private $USESESSIONTABLE2 = true;
	private $SESSIONHOST = '10.166.12.8';
	private $SESSIONPORT = '1521';
	private $SESSIONSCHEMA = 'aaadb';
	private $SESSIONUSERNAME = 'etplprov';
	private $SESSIONPASSWORD = 'etplprov';
	private $SESSIONHOST2 = '10.166.12.40';
	private $SESSIONPORT2 = '1521';
	private $SESSIONSCHEMA2 = 'aaadb';
	private $SESSIONUSERNAME2 = 'etplprov';
	private $SESSIONPASSWORD2 = 'etplprov';
	private $TBLMCONCHOST = '10.166.12.8';
	private $TBLMCONCPORT = '1521';
	private $TBLMCONCSCHEMA = 'aaadb';
	private $TBLMCONCUSERNAME = 'eliteaaa';
	private $TBLMCONCPASSWORD = 'eliteaaa';
	private $TBLMCONCHOST2 = '10.166.12.40';
	private $TBLMCONCPORT2 = '1521';
	private $TBLMCONCSCHEMA2 = 'aaadb';
	private $TBLMCONCUSERNAME2 = 'eliteaaa';
	private $TBLMCONCPASSWORD2 = 'eliteaaa';
	private $TBLMCOREHOST = '10.166.12.8';
	private $TBLMCOREPORT = '1521';
	private $TBLMCORESCHEMA = 'aaadb';
	private $TBLMCOREUSERNAME = 'qm';
	private $TBLMCOREPASSWORD = 'qm';
	private $TBLMCOREHOST2 = '10.166.12.40';
	private $TBLMCOREPORT2 = '1521';
	private $TBLMCORESCHEMA2 = 'aaadb';
	private $TBLMCOREUSERNAME2 = 'qm';
	private $TBLMCOREPASSWORD2 = 'qm';
	private $DELETESESSIONAPIHOST = '10.91.16.69';
	private $DELETESESSIONAPIPORT = '8095';
	private $DELETESESSIONAPISTUB = 'eliteradius/services/eliteRadiusDynAuthWS?wsdl';
	private $DELETESESSIONAPIHOST2 = '10.91.16.69';
	private $DELETESESSIONAPIPORT2 = '8095';
	private $DELETESESSIONAPISTUB2 = 'eliteradius/services/eliteRadiusDynAuthWS?wsdl';
	private $RMDBHOST = '10.81.54.36';
	private $RMDBPORT = '1521';
	private $RMDBSCHEMA = 'rmextwoXDB';
	private $RMDBUSERNAME = 'rm';
	private $RMDBPASSWORD = 'rm';
	private $DETOURAPIHOST = '10.91.16.69';
	private $DETOURAPIPORT = '8090';
	private $DETOURAPISTUB = 'rmapi/subscriberoperation?wsdl';
	private $RMAPIHOST = '10.81.54.34';
	private $RMAPIPORT = '8089';
	private $RMAPISTUB = 'policydesigner/services/SubscriberProvisioningService?wsdl';
	private $USEAPIMYSQL = false;
	private $APIMYSQLHOST = '222.127.121.141';
	private $APIMYSQLDATABASE = 'dsl_utility_extras';
	private $APIMYSQLUSERNAME = 'root';
	private $APIMYSQLPASSWORD = 'c0mmv3rg3';
	/**************************************************
	 * to enable ipv6 address, set $useIPv6 to true
	 **************************************************/
	private $useIPv6 = true;
	private $useSeparateSubnetForNetAddress = false;

	public function __construct(){
	    parent::__construct();
	    $this->load->model('settings');
	    $cfg = $this->settings->loadFromFile();
	    log_message('info', json_encode($cfg));
	    if (!is_null($cfg)) {
	    	$this->SUPERUSER = $cfg['SUPERUSER'];
	    	$this->MAXLOGINTRIES = intval($cfg['MAXLOGINTRIES']);
	    	$this->SESSIONEXPIRYINMINUTES = intval($cfg['SESSIONEXPIRYINMINUTES']);
	    	$this->PASSWORDVALIDITYINDAYS = intval($cfg['PASSWORDVALIDITYINDAYS']);
	    	$this->PASSWORDHISTORYSIZE = intval($cfg['PASSWORDHISTORYSIZE']);
	    	$this->PASSWORDMINLENGTH = intval($cfg['PASSWORDMINLENGTH']);
	    	$this->PASSWORDREQUIRENUMBER = strval($cfg['PASSWORDREQUIRENUMBER']) == 1 ? true : false;
	    	$this->PASSWORDREQUIRESYMBOL = strval($cfg['PASSWORDREQUIRESYMBOL']) == 1 ? true : false;
	    	$this->PASSWORDALLOWUSERNAME = strval($cfg['PASSWORDALLOWUSERNAME']) == 1 ? true : false;
	    	$this->ENABLENPM = intval($cfg['ENABLENPM']) == 1 ? true : false;
	    	$this->NPMHOST = strval($cfg['NPMHOST']);
	    	$this->NPMPORT = strval($cfg['NPMPORT']);
	    	$this->NPMLOGIN = strval($cfg['NPMLOGIN']);
	    	$this->NPMPASSWORD = strval($cfg['NPMPASSWORD']);
	    	$this->NPMAPI = strval($cfg['NPMAPI']);
	    	$this->NPMTIMEOUT = intval($cfg['NPMTIMEOUT']);
	    	$this->USESESSIONTABLE2 = intval($cfg['USESESSIONTABLE2']) == 1 ? true : false;
			$this->SESSIONHOST = strval($cfg['SESSIONHOST']);
			$this->SESSIONPORT = strval($cfg['SESSIONPORT']);
			$this->SESSIONSCHEMA = strval($cfg['SESSIONSCHEMA']);
			$this->SESSIONUSERNAME = strval($cfg['SESSIONUSERNAME']);
			$this->SESSIONPASSWORD = strval($cfg['SESSIONPASSWORD']);
			$this->SESSIONHOST2 = strval($cfg['SESSIONHOST2']);
			$this->SESSIONPORT2 = strval($cfg['SESSIONPORT2']);
			$this->SESSIONSCHEMA2 = strval($cfg['SESSIONSCHEMA2']);
			$this->SESSIONUSERNAME2 = strval($cfg['SESSIONUSERNAME2']);
			$this->SESSIONPASSWORD2 = strval($cfg['SESSIONPASSWORD2']);
			$this->TBLMCONCHOST = strval($cfg['TBLMCONCHOST']);
			$this->TBLMCONCPORT = strval($cfg['TBLMCONCPORT']);
			$this->TBLMCONCSCHEMA = strval($cfg['TBLMCONCSCHEMA']);
			$this->TBLMCONCUSERNAME = strval($cfg['TBLMCONCUSERNAME']);
			$this->TBLMCONCPASSWORD = strval($cfg['TBLMCONCPASSWORD']);
			$this->TBLMCONCHOST2 = strval($cfg['TBLMCONCHOST2']);
			$this->TBLMCONCPORT2 = strval($cfg['TBLMCONCPORT2']);
			$this->TBLMCONCSCHEMA2 = strval($cfg['TBLMCONCSCHEMA2']);
			$this->TBLMCONCUSERNAME2 = strval($cfg['TBLMCONCUSERNAME2']);
			$this->TBLMCONCPASSWORD2 = strval($cfg['TBLMCONCPASSWORD2']);
			$this->TBLMCOREHOST = strval($cfg['TBLMCOREHOST']);
			$this->TBLMCOREPORT = strval($cfg['TBLMCOREPORT']);
			$this->TBLMCORESCHEMA = strval($cfg['TBLMCORESCHEMA']);
			$this->TBLMCOREUSERNAME = strval($cfg['TBLMCOREUSERNAME']);
			$this->TBLMCOREPASSWORD = strval($cfg['TBLMCOREPASSWORD']);
			$this->TBLMCOREHOST2 = strval($cfg['TBLMCOREHOST2']);
			$this->TBLMCOREPORT2 = strval($cfg['TBLMCOREPORT2']);
			$this->TBLMCORESCHEMA2 = strval($cfg['TBLMCORESCHEMA2']);
			$this->TBLMCOREUSERNAME2 = strval($cfg['TBLMCOREUSERNAME2']);
			$this->TBLMCOREPASSWORD2 = strval($cfg['TBLMCOREPASSWORD2']);
			$this->DELETESESSIONAPIHOST = strval($cfg['DELETESESSIONAPIHOST']);
			$this->DELETESESSIONAPIPORT = strval($cfg['DELETESESSIONAPIPORT']);
			$this->DELETESESSIONAPISTUB = strval($cfg['DELETESESSIONAPISTUB']);
			$this->DELETESESSIONAPIHOST2 = strval($cfg['DELETESESSIONAPIHOST2']);
			$this->DELETESESSIONAPIPORT2 = strval($cfg['DELETESESSIONAPIPORT2']);
			$this->DELETESESSIONAPISTUB2 = strval($cfg['DELETESESSIONAPISTUB2']);
			$this->RMDBHOST = strval($cfg['RMDBHOST']);
			$this->RMDBPORT = strval($cfg['RMDBPORT']);
			$this->RMDBSCHEMA = strval($cfg['RMDBSCHEMA']);
			$this->RMDBUSERNAME = strval($cfg['RMDBUSERNAME']);
			$this->RMDBPASSWORD = strval($cfg['RMDBPASSWORD']);
			$this->DETOURAPIHOST = strval($cfg['DETOURAPIHOST']);
			$this->DETOURAPIPORT = strval($cfg['DETOURAPIPORT']);
			$this->DETOURAPISTUB = strval($cfg['DETOURAPISTUB']);
			$this->RMAPIHOST = strval($cfg['RMAPIHOST']);
			$this->RMAPIPORT = strval($cfg['RMAPIPORT']);
			$this->RMAPISTUB = strval($cfg['RMAPISTUB']);
			$this->USEAPIMYSQL = strval($cfg['USEAPIMYSQL']);
			$this->APIMYSQLHOST = strval($cfg['APIMYSQLHOST']);
			$this->APIMYSQLDATABASE = strval($cfg['APIMYSQLDATABASE']);
			$this->APIMYSQLUSERNAME = strval($cfg['APIMYSQLUSERNAME']);
			$this->APIMYSQLPASSWORD = strval($cfg['APIMYSQLPASSWORD']);
	    }
	}

	public function index() {
		$this->load->view('no_access');
	}
	public function welcome() {
		$username = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');
		$role = json_decode($this->session->userdata('role'), true);
		$data = array(
			'username' => $username,
			'ipaddress' => $sysuserIP,
			'rolename' => $role['ROLENAME'],
			'sessionexpire' => $this->SESSIONEXPIRYINMINUTES);
		$this->load->view('welcome', $data);
	}
	public function redirectIfNoAccess($page, $path) {
		$session = $this->session->userdata('session');
		$username = $this->session->userdata('username');
		$portal = $this->session->userdata('portal');
		log_message('info', 'session:'.json_encode($session).'|username:'.json_encode($username).'|portal:'.json_encode($portal));
		if ($username != $this->SUPERUSER) {
			$this->load->model('sysusermain');
			$sessionFromDb = $username !== false ? $this->sysusermain->getSysuserSession($username) : null;
			if (is_null($sessionFromDb) || $session != $sessionFromDb) {
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

	public function loginProcess() {
		if ($this->input->is_ajax_request()) {
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$realm = $this->input->post('realm');
			$portal = $this->input->post('portal');
			$portal = $portal === false ? 'admin' : 'service';
			$this->load->model('realm');
			$this->load->model('sysuserip');
			$this->load->model('sysusermain');
			$this->load->model('sysuser');
			//$sysuserIP = $this->session->userdata('ip_address');
			//$sysuserIP = $this->input->ip_address();
			$sysuserIP = $_SERVER["REMOTE_ADDR"];
			log_message('info', 'client ip address: '.$sysuserIP);
			log_message('info', '$_SERVER["REMOTE_ADDR"]: '.$_SERVER["REMOTE_ADDR"]);
            log_message('info', 'from session: '.$this->session->userdata("ip_address"));

			/*********************************************************
			 * bypass all checking if $username = $this->SUPERUSER
			 *********************************************************/
			if ($username == $this->SUPERUSER) {
				$this->load->model('tbldslbucket');
				$suRole = array('ROLEID' => 0, 'ROLENAME' => $this->SUPERGROUP, 'ROLELABEL' => $this->SUPERGROUP);
				$suPages = $this->tbldslbucket->fetchAllPagesForSU();
				$suRealms = $this->tbldslbucket->fetchAllRealmsForSU();
				$sess = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1).substr(md5(time()),1);
				$this->session->set_userdata('portal', $portal);
				$this->session->set_userdata('realm', $realm);
				$this->session->set_userdata('session', $sess);
				$this->session->set_userdata('username', $username);
				$this->session->set_userdata('role', json_encode($suRole));
				$this->session->set_userdata('allowedPages', json_encode($suPages));
				$this->session->set_userdata('allowedRealms', json_encode($suRealms));
				$data = array(
					'status' => '1',
					'redirect' => $portal == 'admin' ? 'admin' : 'service',
					'conc' => '0');
				echo json_encode($data);
				return;
			}

			/*********************************************************
			 * check if sysuser username exists
			 *********************************************************/
			$sysuserExists = $this->sysusermain->sysuserExists($username);
			log_message('info', 'sysuserExists:'.json_encode($sysuserExists));
			if (!$sysuserExists) {
				$data = array(
					'status' => '0',
					'loginErrorMessage' => 'Invalid login credentials.');
				$this->sysuser->recordSysuserLoginAttempt($username, $password, $sysuserIP, $this->sysuser->ERROR_USER_DOES_NOT_EXIST, time());
				echo json_encode($data);
				return;	
			}
			/*********************************************************
			 * check if sysuser is blocked
			 *********************************************************/
			$sysuserData = $this->sysusermain->fetchSysuserByUsername($username);
			log_message('info', 'sysuserData:'.json_encode($sysuserData));
			if ($sysuserData['BLOCKED'] == 1) {
				$data = array(
					'status' => '0',
					'loginErrorMessage' => 'User is currently blocked from the system.');
				$this->sysuser->recordSysuserLoginAttempt($username, $password, $sysuserIP, $this->sysuser->ERROR_USER_BLOCKED, time());
				echo json_encode($data);
				return;	
			}
			/*********************************************************
			 * check if sysuser's password is correct
			 *********************************************************/
			$this->load->library('encrypt');
			log_message('info', $password.'|'.$this->encrypt->decode($sysuserData['PASSWORD']));
			if ($password != $this->encrypt->decode($sysuserData['PASSWORD'])) {
				$this->sysusermain->incrementLoginTries($username);
				$loginAttempts = $this->sysusermain->getLoginTries($username);
				if ($loginAttempts >= $this->MAXLOGINTRIES) {
					$this->sysusermain->blockSysuser($username);
					$data = array(
						'status' => '0',
						'loginErrorMessage' => 'This user has been blocked from the system.');
					$this->sysuser->recordSysuserLoginAttempt($username, $password, $sysuserIP, $this->sysuser->ERROR_USER_BLOCKED, time());
					echo json_encode($data);
					return;
				}
				$data = array(
					'status' => '0',
					'loginErrorMessage' => 'Invalid login credentials.');
				echo json_encode($data);
				$this->sysuser->recordSysuserLoginAttempt($username, $password, $sysuserIP, $this->sysuser->ERROR_INCORRECT_PASSWORD, time());
				return;
			}

			/*********************************************************
			 * check if ip address sysuser used exists
			 *********************************************************/
			$sysuserIPExists = $this->sysuserip->ipExists($sysuserIP);
			log_message('info', '@AJAXlogin|sysuserIPExists: '.$sysuserIP.'|'.json_encode($sysuserIPExists));
			if (!$sysuserIPExists) {
				//check if ip is in any subnet
				$inSubnet = $this->sysuserip->ipIsInAnySubnet($sysuserIP);
				if (!$inSubnet) {
					$data = array(
						'status' => '0',
						'loginErrorMessage' => 'You cannot access the system from this location.');
					$this->sysuser->recordSysuserLoginAttempt($username, $password, $sysuserIP, $this->sysuser->ERROR_USER_NOACCESS_IPADDRESS, time());
					echo json_encode($data);
					return;
				}
			}

			$this->sysusermain->resetLoginTries($username);
			$this->session->set_userdata('portal', $portal);
			$this->session->set_userdata('realm', $realm);
			if ($sysuserData['REQUIRE_PASSWORD'] == 1) {
				$this->session->set_userdata('username', $username);
				$this->session->set_userdata('changePasswordMessage', 'You must change your password before you can continue using the system.');
				$data = array(
					'status' => 1,
					'redirect' => $portal == 'admin' ? 'admin/showRequiredPasswordChangePage' : 'service/showRequiredPasswordChangePage',
					'conc' => 0);
				echo json_encode($data);
				return;
			}
			/****************************************************
			 * check if last password change is more than the password validity change
			 ****************************************************/
			$lastPasswordChange = $sysuserData['PASSWORD_CHANGE_DATE'];
			log_message('info', 'last password change: '.json_encode($lastPasswordChange));
			if (!is_null($lastPasswordChange)) {
				$lastDotPos = strrpos($lastPasswordChange, '.');
				$cropped = substr($lastPasswordChange, 0, $lastDotPos);
				$lastPasswordChangeDate = date_create_from_format('d-M-y h.i.s', $cropped);
				$now = date_create();
				$interval = date_diff($lastPasswordChangeDate, $now);
				$daysPassed = intval($interval->format('%a'));
				if ($daysPassed >= $this->PASSWORDVALIDITYINDAYS) {
					$this->session->set_userdata('username', $username);
					$this->session->set_userdata('changePasswordMessage', 'Your password has expired. You must change your password before you can continue using the system.');
					$data = array(
						'status' => 1,
						'redirect' => $portal == 'admin' ? 'admin/showRequiredPasswordChangePage' : 'service/showRequiredPasswordChangePage',
						'conc' => 0);
					echo json_encode($data);
					return;
				}
			}
			/*********************************************************
			 * check if sysuser is allowed to login during said day and time
			 *********************************************************/
			$this->load->model('role');
			$role = $this->role->fetchById($sysuserData['ROLE']);
			log_message('info', 'role:'.json_encode($role));
			if ($role === false) {
				$data = array(
					'status' => '0',
					'loginErrorMessage' => 'User is not allowed to access the system.');
				$this->sysuser->recordSysuserLoginAttempt($username, $password, $sysuserIP, $this->sysuser->ERROR_USER_NOGROUP, time());
				echo json_encode($data);
				return;
			}
			$this->load->model('tbldslbucket');
			$times = $this->tbldslbucket->fetchTimesForRole($role['ROLEID']);
			log_message('info', 'allowedTimes:'.json_encode($times));
			$now = time();
			$day = date('w', $now); //0 (sun), ..., 6 (sat)
			$allowed = false;
			for ($i = 0; $i < count($times); $i++) {
				if (intval($day) + 31 == intval($times[$i]['NM'])) {
					$allowed = true;
					break;
				}
			}
			if (!$allowed) {
				$data = array(
					'status' => '0',
					'loginErrorMessage' => 'User is not allowed to use the system today.');
				$this->sysuser->recordSysuserLoginAttempt($username, $password, $sysuserIP, $this->sysuser->ERROR_USER_NOACCESS_DAY, time());
				echo json_encode($data);
				return;
			}
			$hour = date('H', $now);
			$allowed = false;
			for ($i = 0; $i < count($times); $i++) {
				if (intval($hour) == intval($times[$i]['NM'])) {
					$allowed = true;
					break;
				}
			}
			if (!$allowed) {
				$data = array(
					'status' => '0',
					'loginErrorMessage' => 'User is not allowed to use the system at this time.');
				$this->sysuser->recordSysuserLoginAttempt($username, $password, $sysuserIP, $this->sysuser->ERROR_USER_NOACCESS_TIME, time());
				echo json_encode($data);
				return;	
			}
			/*********************************************************
			 * check if sysuser is allowed on said realm
			 *********************************************************/
			$allowedRealms = $this->tbldslbucket->fetchRealmsForRole($sysuserData['ROLE']);
			log_message('info', 'allowedRealms:'.json_encode($allowedRealms));
			if ($portal == 'service') {
				$allowed = false;
				for ($i = 0; $i < count($allowedRealms); $i++) {
					if ($allowedRealms[$i]['NM'] == $realm) {
						$allowed = true;
						break;
					}
				}
				if (!$allowed) {
					$data = array(
						'status' => '0',
						'loginErrorMessage' => 'User is not allowed to access the selected realm.');
					$this->sysuser->recordSysuserLoginAttempt($username, $password, $sysuserIP, $this->sysuser->ERROR_USER_NOACCESS_REALM, time());
					echo json_encode($data);
					return;
				}
			}
			
			/*******************************************************
			 * check for sysuser's existing session:
			 * LOGGED_IN = 1 && SESSION_ID != null
			 *******************************************************/
			$concurrentLogin = false;
			if (intval($sysuserData['LOGGED_IN']) == 1 && !is_null($sysuserData['SESSION_ID'])) {
				$concurrentLogin = true;
			}
			log_message('info', '_____concurrent login: '.json_encode($concurrentLogin));
			$sess = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1).substr(md5(time()),1);
			$this->session->set_userdata('session', $sess);
			$this->sysusermain->setSysuserSession($username, $sess);
			$this->sysusermain->setSysuserLoggedIn($username);
			$this->sysuser->recordSysuserLogin($username, $sess, time(), $this->session->userdata('ip_address'));
			$allowedPages = $this->tbldslbucket->fetchPagesForRole($sysuserData['ROLE']);
			log_message('info', 'allowedPages:'.json_encode($allowedPages));
			$this->session->set_userdata('username', $username);
			$this->session->set_userdata('role', json_encode($role));
			$this->session->set_userdata('allowedPages', json_encode($allowedPages));
			$this->session->set_userdata('allowedRealms', json_encode($allowedRealms));

			/*******************************************************
			 * increment logged in count
			 *******************************************************/
			$hostname = gethostname();
			$this->sysuser->incrementLoggedInCount(time(), $hostname);
			$data = array(
				'status' => '1',
				'redirect' => $portal == 'admin' ? 'admin' : 'service',
				'conc' => $concurrentLogin ? '1' : '0',
				'concredirect' => 'concurrentLogin');
			echo json_encode($data);
		} else {
			redirect('main');
		}
	}
	public function processRequiredPasswordChange() {
		$sysuserIP = $this->session->userdata('ip_address');
		log_message('info', 'logged in from '.$sysuserIP);
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		$username = $this->input->post('username');
		$password1 = $this->input->post('password1');
		$password2 = $this->input->post('password2');
		$data = array(
			'username' => $username,
			'password1' => $password1,
			'password2' => $password2);
		if ($password1 != $password2) {
			$data['error'] = 'Your passwords do not match.';
			$this->load->view($portal == 'admin' ? 'admin_require_password_change_notice' : 'service_require_password_change_notice', $data);
			return;
		}
		$this->load->model('sysuser');
		$inHistory = $this->sysuser->passwordIsInHistory($password1, $username, $this->PASSWORDHISTORYSIZE);
		if ($inHistory) {
			$data['error'] = 'You have just recently used the password that you specified. You cannot use it yet.';
			$this->load->view($portal == 'admin' ? 'admin_require_password_change_notice' : 'service_require_password_change_notice', $data);
			return;
		}
		$this->load->model('util');
		$ok = $this->util->isPasswordAcceptable($password1);
		log_message('info', $password1.'|acceptable:'.json_encode($ok));
		if ($ok['acceptable'] === false || $password1 == $username) {
			$errorStr = 'The specified password is not acceptable.<br />'.
				'The password must have at least '.$this->PASSWORDMINLENGTH.' characters';
			if (!$this->PASSWORDALLOWUSERNAME) {
				$errorStr = $errorStr.'; must not be the same as your username';
			}
			if ($this->PASSWORDREQUIRENUMBER) {
				$errorStr = $errorStr.'; must contain at least one digit';
			}
			if ($this->PASSWORDREQUIRESYMBOL) {
				$errorStr = $errorStr.'; must contain at least one symbol';
			}
			$errorStr = $errorStr.'.';
			$data['error'] = $errorStr;
			$this->load->view($portal == 'admin' ? 'admin_require_password_change_notice' : 'service_require_password_change_notice', $data);
			return;
		}
		$this->load->model('sysusermain');
		$changed = $this->sysusermain->changeSysuserPassword($username, $password1, false);
		if (!$changed) {
			$data['error'] = 'An unknown error occurred. Please try again.';
			$this->load->view('change_my_password', $data);
			return;	
		}

		$this->load->model('sysuser');
		$this->sysuser->recordPasswordChange($username, $password1, time());
		$this->sysuser->cleanPasswordHistory($username, $this->PASSWORDHISTORYSIZE);
		$this->load->model('sysuseractivitylog');
		$this->sysuseractivitylog->logSysuserChangePassword($username, $username, $sysuserIP, time());
		$this->session->unset_userdata('username');
		$this->session->set_userdata('relogin', '1');
		redirect($portal);
	}
	public function logout() {
		$username = $this->session->userdata('username');
		$portal = $this->session->userdata('portal');
		if ($username != $this->SUPERUSER) {
			$this->load->model('sysusermain');
			$this->load->model('sysuser');
			$this->sysusermain->setSysuserLoggedOut($username);
			$this->sysusermain->setSysuserSession($username, null);
			$this->sysuser->recordSysuserLogout($username, $this->session->userdata('session'), time());
		}
		$this->session->sess_destroy();
		redirect($portal == 'admin' ? 'admin' : 'service');
	}

	/***********************************************************************
	 * search online session
	 * PAGEID = 1
	 ***********************************************************************/
	public function showOnlineSessionForm() {
		$this->redirectIfNoAccess('Search Online Session', 'main/showOnlineSessionForm');
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$data = array(
			'realms' => $realms,
			'allowBlankInRealm' => false);
		$this->load->view('search_online_session', $data);
	}
	public function showOnlineSessionDo() {
		$this->redirectIfNoAccess('Search Online Session', 'main/showOnlineSessionDo');
		$this->load->model('realm');
		$this->load->model('onlinesession');
		$this->load->model('subscribermain');
		$this->load->model('excludedplan');
		$username = $this->input->post('user');
		$realm = $this->input->post('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$excludedplans = $this->excludedplan->fetchAllNamesOnly();
		$subs = $this->subscribermain->findByUserIdentity($username.'@'.$realm);
		$plan = str_replace('~', '-', $subs['RADIUSPOLICY']);
		$sessions = $this->onlinesession->getSessions2($username, $realm, $this->USESESSIONTABLE2, 
			$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
			$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
		log_message('info', 'sessions: '.json_encode($sessions));
		$checkQm = false;
		try {
			$this->load->library('detour');
			$detourClient = new SoapClient('http://'.$this->DETOURAPIHOST.':'.$this->DETOURAPIPORT.'/'.$this->DETOURAPISTUB);
			$usageTmp = $this->detour->getVolumeUsage($username.'@'.$realm, $detourClient);
			$usage = array(
				'responseCode' => $usageTmp['responseCode'],
				'replyMessage' => $usageTmp['replyMessage'],
				'PLAN' => is_null($usageTmp['plan']) ? $plan : $usageTmp['plan'],
				'VOLUMEUSAGE' => $usageTmp['volumeUsage'],
				'VOLUMEQUOTA' => $usageTmp['volumeQuota'],
				'vodQuota' => $usageTmp['vodQuota'],
				'vodUsage' => $usageTmp['vodUsage'],
				'vodExpiry' => $usageTmp['vodExpiry']);
			log_message('debug', 'checkQm:'.json_encode($checkQm).',usage via api ('.$username.'@'.$realm.'):'.json_encode($usage));
			if (intval($usage['responseCode']) != 200 && $checkQm) { //always false so will never go here
				//onlinessession->getVolumeUsage data is never used
				$usage = $this->onlinesession->getVolumeUsageData($username, $realm, $plan, $this->SESSIONHOST2, $this->SESSIONPORT2, $this->SESSIONSCHEMA2,
					$this->SESSIONUSERNAME2, $this->SESSIONPASSWORD2);
				log_message('info', 'usage via tblvolumeusage ('.$username.'@'.$realm.') :'.json_encode($usage));	
			}
		} catch (Exception $e) {
			log_message('debug', 'error @ showOnlineSessionDo:'.json_encode($e));
			unset($usage);
		}
		$throttleData = false;
		$data = array(
			'realms' => $realms,
			'realm' => $realm,
			'allowBlankInRealm' => false,
			'user' => $username,
			'sessions' => $sessions,
			'usage' => $usage,
			'excludedplans' => $excludedplans,
			'eventDate' => isset($usage) ? $throttleData : false);
		$this->load->view('search_online_session', $data);
	}
	public function deleteOnlineSessionProcess() {
		$this->redirectIfNoAccess('Search Online Session', 'main/deleteOnlineSessionProcess');
		$concuserid = $this->input->post('user');
		$nasname = $this->input->post('nasname');
		$sessionid =  $this->input->post('sessionid');
		$username = $this->input->post('username');
		$ipv4 = $this->input->post('ipv4address');
		$nasip = $this->input->post('nasipaddress');

		$this->load->model('onlinesession');
		$parts = explode('@', $username);
		$sessions = $this->onlinesession->getSessions2($parts[0], $parts[1], $this->USESESSIONTABLE2,
			$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
			$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
		if (isset($sessions['data'])) {
			for ($i = 0; $i < count($sessions['data']); $i++) {
				$sess = $sessions['data'][$i];
				$deleted = $this->onlinesession->requestDisconnect($sess['USER_NAME'], $sess['NAS_IP_ADDRESS'], $sess['ACCT_SESSION_ID'], $this->USESESSIONTABLE2, 
					$this->DELETESESSIONAPIHOST, $this->DELETESESSIONAPIPORT, $this->DELETESESSIONAPISTUB,
					$this->DELETESESSIONAPIHOST2, $this->DELETESESSIONAPIPORT2, $this->DELETESESSIONAPISTUB2, 
					$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD, 
					$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2,
					$this->TBLMCOREHOST, $this->TBLMCOREPORT, $this->TBLMCORESCHEMA, $this->TBLMCOREUSERNAME, $this->TBLMCOREPASSWORD, 
					$this->TBLMCOREHOST2, $this->TBLMCOREPORT2, $this->TBLMCORESCHEMA2, $this->TBLMCOREUSERNAME2, $this->TBLMCOREPASSWORD2);
			}
		}
		$data = array(
			'username' => $username,
			'sessionid' => $sessionid,
			'ipv4' => $ipv4,
			'nasname' => $nasname,
			'deleted' => isset($sessions['data']) ? $deleted['result'] : true,
			'error' => isset($sessions['data']) ? ($deleted['result'] ? '' : 'Failed to delete session. '.$deleted['error']) : '',
			'message' => isset($sessions['data']) ? ($deleted['result'] ? 'Session deleted.' : '') : 'Session deleted.');
		if (isset($sessions['data']) && $deleted['result']) {
			/***********************************************
			 * get these from session variables
			 ***********************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$parts = explode('@', $username);
			log_message('info', $username.'|'.json_encode($parts));
			if ($sysuser != $this->SUPERUSER) {
				$this->load->model('subscriberaudittrail');
				$this->subscriberaudittrail->logSubscriberSessionDeletion($username, $parts[1], false, $sysuser, $sysuserIP, time());
			}
		}
		$this->load->view('delete_session_result', $data);
	}
	/**************************************************
	 * added october 2016
	 * second delete session stubs with nas update
	 **************************************************/
	public function showOnlineSessionForm2() {
		$this->redirectIfNoAccess('Search Online Session', 'main/showOnlineSessionForm');
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$data = array(
			'realms' => $realms,
			'allowBlankInRealm' => false);
		$this->load->view('search_online_session2', $data);
	}
	public function showOnlineSessionDo2() {
		$this->redirectIfNoAccess('Search Online Session', 'main/showOnlineSessionDo');
		$this->load->model('realm');
		$this->load->model('onlinesession');
		$this->load->model('subscribermain');
		$this->load->model('excludedplan');
		$excludedplans = $this->excludedplan->fetchAllNamesOnly();
		$username = $this->input->post('user');
		$realm = $this->input->post('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$subs = $this->subscribermain->findByUserIdentity($username.'@'.$realm);
		$plan = str_replace('~', '-', $subs['RADIUSPOLICY']);
		$sessions = $this->onlinesession->getSessions2($username, $realm, $this->USESESSIONTABLE2, 
			$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
			$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
		log_message('info', 'sessions: '.json_encode($sessions));
		$checkQm = false;
		try {
			$this->load->library('detour');
			$detourClient = new SoapClient('http://'.$this->DETOURAPIHOST.':'.$this->DETOURAPIPORT.'/'.$this->DETOURAPISTUB);
			$usageTmp = $this->detour->getVolumeUsage($username.'@'.$realm, $detourClient);
			$usage = array(
				'responseCode' => $usageTmp['responseCode'],
				'replyMessage' => $usageTmp['replyMessage'],
				'PLAN' => is_null($usageTmp['plan']) ? $plan : $usageTmp['plan'],
				'VOLUMEUSAGE' => $usageTmp['volumeUsage'],
				'VOLUMEQUOTA' => $usageTmp['volumeQuota'],
				'vodQuota' => $usageTmp['vodQuota'],
				'vodUsage' => $usageTmp['vodUsage'],
				'vodExpiry' => $usageTmp['vodExpiry']);
			log_message('debug', 'checkQm:'.json_encode($checkQm).',usage via api ('.$username.'@'.$realm.'):'.json_encode($usage));
			if (intval($usage['responseCode']) != 200 && $checkQm) { //always false so will never go here
				//onlinessession->getVolumeUsage data is never used
				$usage = $this->onlinesession->getVolumeUsageData($username, $realm, $plan, $this->SESSIONHOST2, $this->SESSIONPORT2, $this->SESSIONSCHEMA2,
					$this->SESSIONUSERNAME2, $this->SESSIONPASSWORD2);
				log_message('info', 'usage via tblvolumeusage ('.$username.'@'.$realm.') :'.json_encode($usage));	
			}
		} catch (Exception $e) {
			log_message('debug', 'error @ showOnlineSessionDo:'.json_encode($e));
			unset($usage);
		}
		/**************************************************
		 * september 2016
		 * nas location check (start)
		 **************************************************/
		log_message('debug', 'nas location check');
		$this->load->library('rm');
		$rmClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
		$rmUsername = $username.'@'.$realm;
		/*
		if ($this->RMAPIHOST == '10.244.4.130' || $this->RMAPIHOST == '10.244.4.131') {
			$getResult = $this->rm->getAccount($rmUsername, $rmClient);
		} else if ($this->RMAPIHOST == '10.81.54.34' || $this->RMAPIHOST == '10.81.54.35') {
		*/
			$getResult = $this->rm->wsGetSubscriberProfileByID($rmClient, $rmUsername);
		/*
		}
		*/
		log_message('debug', 'getResult:               '.json_encode($getResult));
		$subscriberIdentityFromRm = '';
		if ($getResult !== false) {
			if (intval($getResult['responseCode']) == 200) {
				$rmAccount = $getResult['account']->entry;
				foreach ($rmAccount as $rmNode) {
					/*
					if ($this->RMAPIHOST == '10.244.4.130' || $this->RMAPIHOST == '10.244.4.131') {
						if (strtolower($rmNode->key) == 'subscriberidentity') {
							$subscriberIdentityFromRm = isset($rmNode->value) ? $rmNode->value : '';
							break;
						}
					} else if ($this->RMAPIHOST == '10.81.54.34' || $this->RMAPIHOST == '10.81.54.35') {
					*/
						if (strtolower($rmNode->key) == 'subscriber_identity') {
							$subscriberIdentityFromRm = isset($rmNode->value) ? $rmNode->value : '';
							break;
						}
					/*
					}
					*/
				}
			}
		}
		log_message('debug', 'subscriberIdentityFromRm:'.$subscriberIdentityFromRm);
		$poundIndex = strpos($subscriberIdentityFromRm, '#L');
		$registeredBng = '';
		if ($poundIndex !== false) {
			$registeredBng = substr($subscriberIdentityFromRm, $poundIndex, strlen($subscriberIdentityFromRm));
		}
		log_message('debug', 'registered bng:          '.$registeredBng);
		$correctBngName = '';
		if (isset($sessions['data']) && count($sessions['data']) != 0) {
			$correctBngName = $sessions['data'][0]['NAS_IDENTIFIER'];
		}
		log_message('debug', 'connected bng (original):'.$correctBngName);
		$bngInfo = $this->onlinesession->getNasLocation($correctBngName, $registeredBng);
		log_message('debug', 'bngInfo:');
		log_message('debug', 'correct_bng:             '.json_encode($bngInfo['correct_bng']));
		log_message('debug', 'registered_bng:          '.json_encode($bngInfo['registered_bng']));
		log_message('debug', 'oldSubsIdentity:         '.$subscriberIdentityFromRm);
		/**************************************************
		 * nas location check (end)
		 **************************************************/
		$throttleData = false;
		$data = array(
			'realms' => $realms,
			'realm' => $realm,
			'allowBlankInRealm' => false,
			'user' => $username,
			'sessions' => $sessions,
			'usage' => $usage,
			// nas location check (start)
			'bngInfo' => $bngInfo,
			'oldSubsIdentity' => $subscriberIdentityFromRm,
			'excludedplans' => $excludedplans,
			// nas location check (end)
			'eventDate' => isset($usage) ? $throttleData : false);
		$this->load->view('search_online_session2', $data);
	}
	/**************************************************
	 * added september 2016
	 **************************************************/
	public function changeBngAreaRegistration() {
		$this->redirectIfNoAccess('Search Online Session', 'main/deleteOnlineSessionProcess');
		$concuserid = $this->input->post('user');
		$nasname = $this->input->post('nasname');
		$sessionid =  $this->input->post('sessionid');
		$username = $this->input->post('username');
		$ipv4 = $this->input->post('ipv4address');
		$nasip = $this->input->post('nasipaddress');
		$correctrmlocation = $this->input->post('correctrmlocation');
		$oldSubsIdentity = $this->input->post('oldsubsidentity');

		$this->load->model('onlinesession');
		$this->load->library('rm');
		$rmClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
		$newSubsIdentity = $username.$correctrmlocation;
		log_message('debug', 'changing subscriber identity ('.$oldSubsIdentity.' -> '.$newSubsIdentity.')');
		$migrateResult = $this->rm->wsMigrateSubscriber($rmClient, $oldSubsIdentity, $newSubsIdentity);
		log_message('debug', json_encode($migrateResult));
		if (intval($migrateResult['responseCode']) == 200 || intval($migrateResult['responseCode']) == 450) {
			if (intval($migrateResult['responseCode']) == 450) {
				log_message('debug', 'delete/purge/migrate#2 '.$newSubsIdentity);
				$deleteResult = $this->rm->wsDeleteSubscriberProfileV2($rmClient, $newSubsIdentity);
				log_message('debug', 'deleteResult: '.json_encode($deleteResult));
				$purgeResult = $this->rm->wsPurgeSubscriberV2($rmClient, $newSubsIdentity);
				log_message('debug', 'purgeResult: '.json_encode($purgeResult));
				$migrateResult = $this->rm->wsMigrateSubscriber($rmClient, $oldSubsIdentity, $newSubsIdentity);
				if (intval($migrateResult['responseCode']) != 200) {
					log_message('debug', 'migrate failed #2');
					$data = array(
						'username' => $username,
						'sessionid' => $sessionid,
						'ipv4' => $ipv4,
						'nasname' => $nasname,
						'deleted' => false,
						'error' => $migrateResult['responseMessage'],
						'message' => '');
					$this->load->view('delete_session_result2', $data);
					return;
				}
			}
			log_message('debug', 'migrate successful');
			//get sessions
			$parts = explode('@', $username);
			$sessions = $this->onlinesession->getSessions2($parts[0], $parts[1], $this->USESESSIONTABLE2,
				$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
				$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
			log_message('debug', 'sessions: '.(isset($sessions['data']) ? json_encode($sessions['data']) : 'none found'));
			//delete session
			if (isset($sessions['data'])) {
				for ($i = 0; $i < count($sessions['data']); $i++) {
					$sess = $sessions['data'][$i];
					$deleted = $this->onlinesession->requestDisconnect($sess['USER_NAME'], $sess['NAS_IP_ADDRESS'], $sess['ACCT_SESSION_ID'], $this->USESESSIONTABLE2, 
						$this->DELETESESSIONAPIHOST, $this->DELETESESSIONAPIPORT, $this->DELETESESSIONAPISTUB,
						$this->DELETESESSIONAPIHOST2, $this->DELETESESSIONAPIPORT2, $this->DELETESESSIONAPISTUB2, 
						$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD, 
						$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2,
						$this->TBLMCOREHOST, $this->TBLMCOREPORT, $this->TBLMCORESCHEMA, $this->TBLMCOREUSERNAME, $this->TBLMCOREPASSWORD, 
						$this->TBLMCOREHOST2, $this->TBLMCOREPORT2, $this->TBLMCORESCHEMA2, $this->TBLMCOREUSERNAME2, $this->TBLMCOREPASSWORD2);
				}
			}
			$data = array(
				'username' => $username,
				'sessionid' => $sessionid,
				'ipv4' => $ipv4,
				'nasname' => $nasname,
				'deleted' => isset($sessions['data']) ? $deleted['result'] : true,
				'error' => isset($sessions['data']) ? ($deleted['result'] ? '' : 'Failed to delete session. '.$deleted['error']) : '',
				'message' => isset($sessions['data']) ? ($deleted['result'] ? 'RM record updated and session deleted.' : '') : 'Rm record updated and session deleted.');
			if (isset($sessions['data']) && $deleted['result']) {
				/***********************************************
				 * get these from session variables
				 ***********************************************/
				$sysuser = $this->session->userdata('username');
				$sysuserIP = $this->session->userdata('ip_address');

				$parts = explode('@', $username);
				log_message('info', $username.'|'.json_encode($parts));
				if ($sysuser != $this->SUPERUSER) {
					$this->load->model('subscriberaudittrail');
					$this->subscriberaudittrail->logSubscriberSessionDeletion($username, $parts[1], false, $sysuser, $sysuserIP, time());
				}
			}
			$this->load->view('delete_session_result2', $data);
		} else {
			log_message('debug', 'migrate failed');
			$data = array(
				'username' => $username,
				'sessionid' => $sessionid,
				'ipv4' => $ipv4,
				'nasname' => $nasname,
				'deleted' => false,
				'error' => $migrateResult['responseMessage'],
				'message' => '');
			$this->load->view('delete_session_result2', $data);
		}
	}
	/***********************************************************************
	 * search online session (npm)
	 * PAGEID = 1
	 ***********************************************************************/
	public function showOnlineSessionFormNPM() {
		$this->redirectIfNoAccess('Search Online Session', 'main/showOnlineSessionFormNPM');
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$data = array(
			'realms' => $realms,
			'allowBlankInRealm' => false);
		$this->load->view('search_online_session_npm', $data);
	}
	public function showOnlineSessionDoNPM() {
		$this->redirectIfNoAccess('Search Online Session', 'main/showOnlineSessionDoNPM');
		$this->load->model('realm');
		$this->load->model('onlinesession');
		$username = $this->input->post('user');
		$realm = $this->input->post('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$sessions = $this->onlinesession->npmGetSessions($username, $realm, $this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD);
		$data = array(
			'realms' => $realms,
			'realm' => $realm,
			'allowBlankInRealm' => false,
			'user' => $username,
			'sessions' => $sessions);
		$this->load->view('search_online_session_npm', $data);
	}
	public function deleteOnlineSessionNPMProcess() {
		$this->redirectIfNoAccess('Search Online Session', 'main/deleteOnlineSessionNPMProcess');
		$sessionid = $this->input->post('sessionid');
		$nasid = $this->input->post('nasid');
		$acctsessionid = $this->input->post('acctsessionid');
		$subscriberaccount = $this->input->post('subscriberaccount');
		$sessionip = $this->input->post('sessionip');

		$this->load->model('onlinesession');
		$result = $this->onlinesession->npmClearSession($subscriberaccount, $this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD);
		$data = array(
			'username' => $subscriberaccount,
			'sessionid' => $sessionid,
			'ipv4' => $sessionip,
			'nasname' => $nasid,
			'error' => $result['status'] == false ? $result['error'] : '',
			'message' => $result['status'] == false ? '' : 'NPM session deleted.',
			'result' => $result['status']);
		if ($result['status']) {
			/***********************************************
			 * get these from session variables
			 ***********************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$parts = explode('@', $subscriberaccount);
			if ($sysuser != $this->SUPERUSER) {
				$this->load->model('subscriberaudittrail');
				$this->subscriberaudittrail->logSubscriberSessionDeletion($subscriberaccount, $parts[1], false, $sysuser, $sysuserIP, time());
			}
		}
		$this->load->view('delete_session_result_npm', $data);
	}
	/***********************************************************************
	 * search concurrent session
	 * PAGEID = 2
	 ***********************************************************************/
	public function showConcurrentSessionForm() {
		$this->redirectIfNoAccess('Search Concurrent Session', 'main/showConcurrentSessionForm');
		$this->load->view('under_construction');
		//$this->load->view('search_concurrent_session');
	}
	/***********************************************************************
	 * verify subscriber password
	 * PAGEID = 5
	 ***********************************************************************/
	public function showUserPasswordVerificationForm() {
		$this->redirectIfNoAccess('Verify Account Password', 'main/showUserPasswordVerificationForm');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$data = array(
			'realm' => $realm,
			'realms' => $realms);
		if ($portal == 'service') {
			$data['disableRealm'] = true;
		}
		$this->load->view('verify_user_password', $data);
	}
	public function processUserPasswordVerification() {
		$this->redirectIfNoAccess('Verify Account Password', 'main/processUserPasswordVerification');
		/********************************************
		 * get these from session variables
		 ********************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');
		
		$realm = $this->session->userdata('realm');
		$portal = $this->session->userdata('portal');
		if ($portal == 'admin') {
			$realm = $this->input->post('realm');
		}
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();

		$username = $this->input->post('userid');
		$password = trim($this->input->post('password'));
		$data = array(
			'realm' => $realm,
			'realms' => $realms,
			'userid' => $username,
			'password' => $password);
		if ($portal == 'service') {
			$data['disableRealm'] = true;
		}
		$cn = $username.'@'.$realm;
		$this->load->model('subscribermain');
		$subscriber = $this->subscribermain->findByUserIdentity($cn);
		if ($subscriber === false) {
			$data['error'] = 'The subscriber cannot be found.';
			$this->load->view('verify_user_password', $data);
			return;
		}
		if ($subscriber['PASSWORD'] != $password) {
			$data['error'] = 'Password is incorrect.';
			$this->load->view('verify_user_password', $data);
			return;
		}
		$data['message'] = 'Password is correct.';
		$this->load->view('verify_user_password', $data);
	}
	/***********************************************************************
	 * services
	 * PAGEID = 8
	 ***********************************************************************/
	public function showServicesIndex() {
		$this->redirectIfNoAccess('Services', 'main/showServicesIndex');
		$this->load->model('util');
		$data = array(
			'yesterday' => $this->util->getDateYesterday(true));
		$this->load->view('services_admin', $data);
	}
	//showCreateServiceForm is no longer used
	public function showCreateServiceForm() {
		$this->redirectIfNoAccess('Services', 'main/showCreateServiceForm');
		$this->load->view('services_create');
	}
	//processServiceCreation is no longer used
	public function processServiceCreation() {
		$this->redirectIfNoAccess('Services', 'main/processServiceCreation');
		/********************************************
		 * get these from session variables
		 ********************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('services');
		$servicename = trim($this->input->post('service'));
		$description = trim($this->input->post('description'));
		$remarks = trim($this->input->post('remarks'));
		$downlink = trim($this->input->post('downlink'));
		$uplink = trim($this->input->post('uplink'));
		$quota = trim($this->input->post('quota'));
		$throttling = trim($this->input->post('throttling'));
		$data = array(
			'servicename' => $servicename,
			'description' => $description,
			'remarks' => $remarks,
			'downlink' => $downlink,
			'uplink' => $uplink,
			'quota' => $quota,
			'throttling' => $throttling);
		$existing = $this->services->serviceExists($servicename);
		if ($existing) {
			$data['error'] = 'The service '.$servicename.' already exists.';
			$this->load->view('services_create', $data);
			return;
		}
		$created = $this->services->create($servicename, $description, $remarks, $downlink, $uplink, $quota, $throttling);
		if (!$created) {
			$data['error'] = 'Failed to create service: '.$servicename.'.';
			$this->load->view('services_create', $data);
			return;	
		}
		$data['message'] = 'Service '.$servicename.' created.';
		$data['servicename'] = '';
		$data['description'] = '';
		$data['remarks'] = '';
		$data['downlink'] = '';
		$data['uplink'] = '';
		$data['quota'] = '';
		$data['throttling'] = '';
		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('sysuseractivitylog');
			$this->sysuseractivitylog->logServiceCreation($servicename, $sysuser, $sysuserIP, time());
		}
		$this->load->view('services_create', $data);
	}
	//showModifyServiceForm is no longer used
	public function showModifyServiceForm($servicename) {
		$this->redirectIfNoAccess('Services', 'main/showModifyServiceForm');
		$this->load->model('services');
		$service = $this->services->fetch($servicename);
		$data = array(
			'servicename' => $service['SERVICENAME'],
			'description' => $service['DESCRIPTION'],
			'remarks' => $service['REMARKS'],
			'downlink' => $service['DOWNLINK'],
			'uplink' => $service['UPLINK'],
			'quota' => $service['QUOTA'],
			'throttling' => $service['THROTTLING']);
		$this->load->view('services_modify', $data);
	}
	//processServiceModification is no longer used
	public function processServiceModification() {
		$this->redirectIfNoAccess('Services', 'main/processServiceModification');
		/********************************************
		 * get these from session variables
		 ********************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('services');
		$servicename = trim($this->input->post('service'));
		$description = trim($this->input->post('description'));
		$remarks = trim($this->input->post('remarks'));
		$downlink = trim($this->input->post('downlink'));
		$uplink = trim($this->input->post('uplink'));
		$quota = trim($this->input->post('quota'));
		$throttling = trim($this->input->post('throttling'));
		$data = array(
			'servicename' => $servicename,
			'description' => $description == '' ? null : $description,
			'remarks' => $remarks == '' ? null : $remarks,
			'downlink' => $downlink == '' ? null : $downlink,
			'uplink' => $uplink == '' ? null : $uplink,
			'quota' => $quota == '' ? null : $quota,
			'throttling' => $throttling == '' ? null : $throttling);
		$modified = $this->services->modify($servicename, $description, $remarks, $downlink, $uplink, $quota, $throttling);
		if (!$modified) {
			$data['error'] = 'Failed to modify service: '.$servicename.'.';
			$this->load->view('services_modify', $data);
			return;
		}
		$data['message'] = 'Service '.$servicename.' modified.';
		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('sysuseractivitylog');
			$this->sysuseractivitylog->logServiceModification($servicename, $sysuser, $sysuserIP, time());
		}
		$this->load->view('services_modify', $data);
	}
	//processServiceDeletion is no longer used
	public function processServiceDeletion() {
		if ($this->input->is_ajax_request()) {
			/***********************************************
			 * get these from session variables
			 ***********************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$this->load->model('services');			
			$servicename = $this->input->post('service');
			$deleted = $this->services->delete($servicename);
			if (!$deleted) {
				echo json_encode(array('status' => '0'));
			} else {
				if ($sysuser != $this->SUPERUSER) {
					$this->load->model('sysuseractivitylog');
					$this->sysuseractivitylog->logServiceDeletion($servicename, $sysuser, $sysuserIP, time());
				}
				echo json_encode(array('status' => '1'));
			}
		} else {
			redirect('admin/showServicesIndex');
		}
	}
	/***********************************************************************
	 * change my (sysuser) password
	 * PAGEID = 10
	 ***********************************************************************/
	public function showChangeMyPasswordForm() {
		$this->redirectIfNoAccess('Change My Password', 'main/showChangeMyPasswordForm');
		$username = $this->session->userdata('username');
		$data = array(
			'username' => $username);
		$this->load->view('change_my_password', $data);
	}
	public function processMyPasswordChange() {
		$this->redirectIfNoAccess('Change My Password', 'main/processMyPasswordChange');
		/********************************************
		 * get these from session variables
		 ********************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$password = $this->input->post('currentpassword');
		$password1 = $this->input->post('newpassword1');
		$password2 = $this->input->post('newpassword2');
		$data = array(
			'username' => $sysuser);
		if ($password1 != $password2) {
			$data['error'] = 'Your passwords do not match.';
			$this->load->view('change_my_password', $data);
			return;
		}
		$password1 = trim($password1);
		$this->load->model('sysuser');
		$inHistory = $this->sysuser->passwordIsInHistory($password1, $sysuser, $this->PASSWORDHISTORYSIZE);
		if ($inHistory) {
			$data['error'] = 'You have just recently used the password that you specified. You cannot use it yet.';
			$this->load->view('change_my_password', $data);
			return;
		}
		$this->load->model('util');
		$ok = $this->util->isPasswordAcceptable($password1);
		log_message('info', $password1.'|acceptable:'.json_encode($ok));
		if ($ok['acceptable'] === false || $password1 == $sysuser) {
			$errorStr = 'The specified password is not acceptable.<br />'.
				'The password must have at least '.$this->PASSWORDMINLENGTH.' characters';
			if (!$this->PASSWORDALLOWUSERNAME) {
				$errorStr = $errorStr.'; must not be the same as your username';
			}
			if ($this->PASSWORDREQUIRENUMBER) {
				$errorStr = $errorStr.'; must contain at least one digit';
			}
			if ($this->PASSWORDREQUIRESYMBOL) {
				$errorStr = $errorStr.'; must contain at least one symbol';
			}
			$errorStr = $errorStr.'.';
			$data['error'] = $errorStr;
			$this->load->view('change_my_password', $data);
			return;
		}
		$this->load->model('sysusermain');
		$this->load->library('encrypt');
		$sysuserData = $this->sysusermain->fetchSysuserByUsername($sysuser);
		if ($this->encrypt->decode($sysuserData['PASSWORD']) != $password) {
			$data['error'] = 'The current password that you have specified is incorrect.';
			$this->load->view('change_my_password', $data);
			return;
		}

		$changed = $this->sysusermain->changeSysuserPassword($sysuserData['USERNAME'], $password1, false);
		if (!$changed) {
			$data['error'] = 'An unknown error occurred. Please try again.';
			$this->load->view('change_my_password', $data);
			return;	
		}

		$this->load->model('sysuser');
		$this->sysuser->recordPasswordChange($sysuserData['USERNAME'], $password1, time());
		$this->sysuser->cleanPasswordHistory($sysuserData['USERNAME'], $this->PASSWORDHISTORYSIZE);
		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('sysuseractivitylog');
			$this->sysuseractivitylog->logSysuserChangePassword($sysuser, $sysuser, $sysuserIP, time());
		}
		$data['message'] = 'You have successfully changed your password.';
		$this->load->view('change_my_password', $data);
	}
	/***********************************************************************
	 * admin search box
	 * PAGEID = 17
	 ***********************************************************************/
	public function searchProcess($keyword, $option) {
		$this->redirectIfNoAccess('Search', 'main/searchProcess');
		switch($option) {
			case '1': {
				$this->processAccountSearch($keyword);
				break;
			}
			case '2': {
				$this->processRealmSearch($keyword);
				break;
			}
			case '3': {
				$this->processServiceSearch($keyword);
				break;
			}
			case '4': {
				$this->processIpAddressSearch($keyword);
				break;
			}
		}
	}
	public function processAccountSearch($keyword) {
		$this->redirectIfNoAccess('Search', 'main/processAccountSearch');
		$allowedRealms = $this->session->userdata('allowedRealms');
		log_message('info', $allowedRealms);
		$allowedRealms = json_decode($allowedRealms, true);
		$keywordStr = str_replace('++', ' ', $keyword);
		$keywordStr = str_replace('~', '*', $keywordStr);
		$keywordStr = str_replace('-----', '@', $keywordStr);
		$exact = strpos($keywordStr, '*') === false ? true : false;
		$wildcard = 'both';
		if (!$exact) {
			if (strpos($keywordStr, '*') == 0) {
				$wildcard = 'before';
			} else if (strpos($keywordStr, '*') == (strlen($keywordStr) - 1)) {
				$wildcard = 'after';
			}
		}
		$this->load->model('subscribermain');
		$results = $this->subscribermain->searchSubscriber('USERNAME', str_replace('*', '', $keywordStr), $exact, $wildcard, 'admin', null, $allowedRealms);
		$data = array(
			'keyword' => $keywordStr,
			'results' => $results);
		$this->load->view('search_result_account', $data);
	}
	public function processRealmSearch($keyword) {
		$this->redirectIfNoAccess('Search', 'main/processRealmSearch');
		$keywordStr = str_replace('_', ' ', $keyword);
		$keywordStr = str_replace('~', '*', $keywordStr);
		$keywordStr = str_replace('-----', '@', $keywordStr);
		$exact = strpos($keywordStr, '*') === false ? true : false;
		$wildcard = 'both';
		if (!$exact) {
			if (strpos($keywordStr, '*') == 0) {
				$wildcard = 'before';
			} else if (strpos($keywordStr, '*') == (strlen($keywordStr) - 1)) {
				$wildcard = 'after';
			}
		}
		$this->load->model('realm');
		$results = $this->realm->searchRealm(str_replace('*', '', $keywordStr), $exact, $wildcard);
		log_message('info', '@search realm|'.json_encode($results));
		$data = array(
			'keyword' => $keywordStr,
			'results' => $results);
		$this->load->view('search_result_realm', $data);
	}
	public function processServiceSearch($keyword) {
		$this->redirectIfNoAccess('Search', 'main/processServiceSearch');
		//$keywordStr = str_replace('_', ' ', $keyword);
		$keywordStr = str_replace('~', '*', $keyword);
		$keywordStr = str_replace('-----', '@', $keywordStr);
		$exact = strpos($keywordStr, '*') === false ? true : false;
		$wildcard = 'both';
		if (!$exact) {
			if (strpos($keywordStr, '*') == 0) {
				$wildcard = 'before';
			} else if (strpos($keywordStr, '*') == (strlen($keywordStr) - 1)) {
				$wildcard = 'after';
			}
		}
		$this->load->model('services');
		// $results = $this->services->searchService(str_replace('*', '', $keywordStr), $exact, $wildcard);
		$results = $this->services->searchServiceNew(str_replace('*', '', $keywordStr), $exact, $wildcard,
			$this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
		$inactiveCounts = $this->services->searchServiceInactiveCounts(str_replace('*', '', $keywordStr), $exact, $wildcard);
		$activeCounts = $this->services->searchServiceActiveCounts(str_replace('*', '', $keywordStr), $exact, $wildcard);
		// $inactiveCounts = array();
		// $activeCounts = array();
		// for ($i = 0; $i < count($results); $i++) {
		// 	$inactiveCounts[]['COUNT'] = 0;
		// 	$activeCounts[]['COUNT'] = 0;
		// }
		$data = array(
			'keyword' => $keywordStr,
			'results' => $results,
			'inactiveCounts' => $inactiveCounts,
			'activeCounts' => $activeCounts);
		$this->load->view('search_result_service', $data);
	}
	public function processIpAddressSearch($keyword) {
		//$this->load->view('');
	}
	public function showSearchAccountServicesInfo($username, $realm, $radiuspolicy, $status, $additionalservice1, $additionalservice2, $ipaddress, $netaddress) {
		$this->redirectIfNoAccess('Search', 'main/showSearchAccountServicesInfo');
		$additionalservice1 = $additionalservice1 == '-' ? null : $additionalservice1;
		$additionalservice2 = $additionalservice2 == '-' ? null : $additionalservice2;
		$ipaddress = $ipaddress == '-' ? null : $ipaddress;
		$netaddress = $netaddress == '-' ? null : $netaddress;
		$location = null;
		if (!is_null($ipaddress)) {
			$this->load->model('ipaddress');
			$location = $this->ipaddress->getLocation($ipaddress, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
		}
		$data = array(
			'username' => $username,
			'realm' => $realm,
			'radiuspolicy' => $radiuspolicy,
			'status' => $status,
			'additionalservice1' => $additionalservice1,
			'additionalservice2' => $additionalservice2,
			'ipaddress' => $ipaddress,
			'location' => $location,
			'netaddress' => $netaddress);
		$this->load->view('search_result_account_services_info', $data);
	}
	/***********************************************************************
	 * service search box
	 * PAGEID = 17
	 ***********************************************************************/
	public function searchSubscriberProcess($keyword, $option) {
		$this->redirectIfNoAccess('Search', 'main/searchSubscriberProcess');
		$allowedRealms = $this->session->userdata('allowedRealms');
		log_message('info', 'allowed realms: '.$allowedRealms);
		$allowedRealms = json_decode($allowedRealms, true);
		$this->load->model('subscribermain');
		$options = array(
			'cn' => 'Username',
			'rbserviceno' => 'Service Number',
			'rborderno' => 'Order Number',
			'rbcustname' => 'Customer Name',
			'rbipaddress' => 'IP Address');
		$column = array(
			'cn' => 'USERNAME',
			'rbserviceno' => 'RBSERVICENUMBER',
			'rborderno' => 'RBORDERNUMBER',
			'rbcustname' => 'RBCUSTOMERNAME',
			'rbipaddress' => 'RBIPADDRESS');
		$optionStr = $column[$option];
		$keywordStr = str_replace('--SPC--', ' ', $keyword);
		$keywordStr = str_replace('-----', '@', $keywordStr);
		$keywordStr = str_replace('~', '*', $keywordStr);
		$hasSubnet = false;
		if ($option == 'rbipaddress' && strpos($keywordStr, 'SLSH') !== false) {
			$hasSubnet = true;
			$optionStr = 'RBMULTISTATIC';
			$keywordStr = str_replace('SLSH', '/', $keywordStr);
		}
		$exact = strpos($keywordStr, '*') === false ? true : false;
		$wildcard = 'both';
		if (!$exact) {
			if (strpos($keywordStr, '*') == 0) {
				$wildcard = 'before';
			} else if (strpos($keywordStr, '*') == (strlen($keywordStr) - 1)) {
				$wildcard = 'after';
			}
		}
		$sessionUsername = $this->session->userdata('username');
		$this->load->model('sysusermain');
		$this->load->model('role');
		$sysuserData = $this->sysusermain->fetchSysuserByUsername($sessionUsername);
		$roleData = $this->role->fetchById($sysuserData['ROLE']);
		$realm = $this->session->userdata('realm');
		$portal = $this->session->userdata('portal');
		log_message('info', 'realm:'.json_encode($realm).'|portal:'.json_encode($portal));
		log_message('info', 'options:'.json_encode($optionStr).'|keyword:'.str_replace('*', '', $keywordStr));
		$results = $this->subscribermain->searchSubscriber($optionStr, str_replace('*', '', $keywordStr), $exact, $wildcard, $portal, $portal == 'service' ? $realm : null, $allowedRealms);

		log_message('info', 'results|'.json_encode($results));

		$data = array(
			'username' => $sessionUsername,
			'role' => $roleData['ROLENAME'],
			'realm' => $realm,
			'portal' => $portal,
			'option' => ($option == 'rbipaddress' && $hasSubnet) ? 'Network Address' : $options[$option],
			'keyword' => $keywordStr,
			'results' => $results);
		$data['useIPv6'] = $this->useIPv6;
		$this->load->view('search_result_subscriber', $data);
	}
	public function searchSubscriberProcessReadOnly($keyword, $option) {
		$this->redirectIfNoAccess('Search-ReadOnly', 'main/searchSubscriberProcessReadOnly');
		$allowedRealms = $this->session->userdata('allowedRealms');
		log_message('info', 'allowed realms: '.$allowedRealms);
		$allowedRealms = json_decode($allowedRealms, true);
		$this->load->model('subscribermain');
		$options = array(
			'cn' => 'Username',
			'rbserviceno' => 'Service Number',
			'rborderno' => 'Order Number',
			'rbcustname' => 'Customer Name',
			'rbipaddress' => 'IP Address');
		$column = array(
			'cn' => 'USERNAME',
			'rbserviceno' => 'RBSERVICENUMBER',
			'rborderno' => 'RBORDERNUMBER',
			'rbcustname' => 'RBCUSTOMERNAME',
			'rbipaddress' => 'RBIPADDRESS');
		$optionStr = $column[$option];
		$keywordStr = str_replace('--SPC--', ' ', $keyword);
		$keywordStr = str_replace('-----', '@', $keywordStr);
		$keywordStr = str_replace('~', '*', $keywordStr);
		$hasSubnet = false;
		if ($option == 'rbipaddress' && strpos($keywordStr, 'SLSH') !== false) {
			$hasSubnet = true;
			$optionStr = 'RBMULTISTATIC';
			$keywordStr = str_replace('SLSH', '/', $keywordStr);
		}
		$exact = strpos($keywordStr, '*') === false ? true : false;
		$wildcard = 'both';
		if (!$exact) {
			if (strpos($keywordStr, '*') == 0) {
				$wildcard = 'before';
			} else if (strpos($keywordStr, '*') == (strlen($keywordStr) - 1)) {
				$wildcard = 'after';
			}
		}
		$sessionUsername = $this->session->userdata('username');
		$this->load->model('sysusermain');
		$this->load->model('role');
		$sysuserData = $this->sysusermain->fetchSysuserByUsername($sessionUsername);
		$roleData = $this->role->fetchById($sysuserData['ROLE']);
		$realm = $this->session->userdata('realm');
		$portal = $this->session->userdata('portal');
		log_message('info', 'realm:'.json_encode($realm).'|portal:'.json_encode($portal));
		$results = $this->subscribermain->searchSubscriber($optionStr, str_replace('*', '', $keywordStr), $exact, $wildcard, $portal, $portal == 'service' ? $realm : null, $allowedRealms);

		log_message('info', 'results|'.json_encode($results));

		$data = array(
			'username' => $sessionUsername,
			'role' => $roleData['ROLENAME'],
			'realm' => $realm,
			'portal' => $portal,
			'option' => ($option == 'rbipaddress' && $hasSubnet) ? 'Network Address' : $options[$option],
			'keyword' => $keywordStr,
			'results' => $results);
		$data['useIPv6'] = $this->useIPv6;
		$this->load->view('search_result_subscriber_readonly', $data);
	}
	/***********************************************************************
	 * reset subscriber password
	 * PAGEID = 18
	 ***********************************************************************/
	public function showUserPasswordResetForm() {
		$this->redirectIfNoAccess('Reset Subscriber Password', 'main/showUserPasswordResetForm');
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$data = array(
			'realms' => $realms);
		$this->load->view('reset_user_password', $data);
	}
	public function processUserPasswordReset() {
		$this->redirectIfNoAccess('Reset Subscriber Password', 'main/processUserPasswordReset');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$username = $this->input->post('userid');
		$realm = $this->input->post('realm');
		$this->load->model('realm');
		$this->load->model('subscribermain');
		$realms = $this->realm->fetchAllNamesOnly();
		$data = array(
			'username' => $username,
			'realm' => $realm,
			'realms' => $realms);
		$old = $this->subscribermain->findByUserIdentity($username.'@'.$realm);
		if ($old === false) {
			$data['error'] = 'The subscriber cannot be found.';
			$this->load->view('reset_user_password', $data);
			return;
		}
		$modifieddate = date('Y-m-d H:i:s', time());
		$this->load->model('util');
		$generatedPassword = $this->util->generateRandomString(10);
		$changed = $this->subscribermain->changeSubscriberPassword($username, $realm, $generatedPassword, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
		if (!$changed) {
			$data['error'] = 'Password reset unsuccessful.';
			$this->load->view('reset_user_password', $data);
			return;
		}

		/*******************************************
		 * do npm change password here
		 *******************************************/
		if($this->ENABLENPM) {
			$npmResult = true;
			$npmError = '';
			$npmFound = $this->subscribermain->npmFetchXML($username.'@'.$realm, $this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
			if (!$npmFound['found']) {
				$npmResult = false;
				$npmError = $npmFound['error'];
			} else {
				$subscriber = $npmFound['data'];
				$updated = $this->subscribermain->npmUpdateXML($subscriber['USERNAME'], $generatedPassword, $subscriber['ACTIVATED'], $subscriber['RADIUSPOLICY'], 
					$subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC'], 'N',
					$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
				if (!$updated['result']) {
					$npmResult = false;
					$npmError = $updated['error'];
				}
			}
			if (!$npmResult) {
				//revert password change
				$this->subscribermain->changeSubscriberPassword($old['USERNAME'], $old['RBREALM'], $old['PASSWORD'], $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				$data['error'] = 'Unsuccessful password reset. NPM: '.$npmError;
				$this->load->view('reset_user_password', $data);
				return;
			}
		}
		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('subscriberaudittrail');
			$this->subscriberaudittrail->logSubscriberPasswordChange($old['USERNAME'], $realm, $generatedPassword, $sysuser, $sysuserIP, time());
		}
		$data['message'] = $modifieddate.': Password reset successful for '.$old['USERNAME'].'. New password: '.$generatedPassword;
		// $dataMessage = $modifieddate.': Password change successful for '.$old['USERNAME'].'. New password: '.$generatedPassword;
		
		/*******************************************
		 * subscriber identity change at rm
		 *******************************************
		log_message('debug', 'changing subscriber identity');
		$this->load->library('rm');
		$rmClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
		$rmUsername = $username.'@'.$realm;
		$getResult = $this->rm->wsGetSubscriberProfileByID($rmClient, $rmUsername);
		log_message('debug', 'getResult: '.json_encode($getResult));
		if (intval($getResult['responseCode']) != 200) {
			$data['message'] = $dataMessage.'<br /><br />Account not found in RM.';
		} else {
			$rmAccount = $getResult['account']->entry;
			log_message('debug', 'rmAccount: '.json_encode($rmAccount));
			$oldSubsIdentity = '';
			foreach ($rmAccount as $node) {
				if ($node->key == 'SUBSCRIBER_IDENTITY') {
					$oldSubsIdentity = isset($node->value) ? $node->value : "";
					break;
				}
			}
			$hasExtras = strpos($oldSubsIdentity, '#');
			if ($hasExtras !== false) {
				log_message('debug', 'account has #: '.$rmUsername);
				$newSubsIdentity = substr($oldSubsIdentity, 0, $hasExtras);
				$migrateResult = $this->rm->wsMigrateSubscriber($rmClient, $oldSubsIdentity, $newSubsIdentity);
				if (intval($migrateResult['responseCode']) != 200) {
					log_message('debug', 'failed to migrate account: '.$rmUsername);
					$data['message'] = $dataMessage.'<br /><br />Failed to migrate account.';
				} else {
					log_message('debug', 'migrated account: '.$oldSubsIdentity.' -> '.$newSubsIdentity);
					//get sessions
					$this->load->model('onlinesession');
					$sessions = $this->onlinesession->getSessions2($username, $realm, $this->USESESSIONTABLE2,
						$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
						$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
					if (isset($sessions['data'])) {
						//run requestDisconnect
						for ($i = 0; $i < count($sessions['data']); $i++) {
							$sess = $sessions['data'][$i];
							$deleted = $this->onlinesession->requestDisconnectOnly($sess['USER_NAME'], $sess['NAS_IP_ADDRESS'], $sess['ACCT_SESSION_ID'], $this->USESESSIONTABLE2, 
								$this->DELETESESSIONAPIHOST, $this->DELETESESSIONAPIPORT, $this->DELETESESSIONAPISTUB,
								$this->DELETESESSIONAPIHOST2, $this->DELETESESSIONAPIPORT2, $this->DELETESESSIONAPISTUB2, 
								$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD, 
								$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2,
								$this->TBLMCOREHOST, $this->TBLMCOREPORT, $this->TBLMCORESCHEMA, $this->TBLMCOREUSERNAME, $this->TBLMCOREPASSWORD, 
								$this->TBLMCOREHOST2, $this->TBLMCOREPORT2, $this->TBLMCORESCHEMA2, $this->TBLMCOREUSERNAME2, $this->TBLMCOREPASSWORD2);
						}
						if (!$deleted['result']) {
							log_message('debug', 'session not deleted for '.$rmUsername);
							$data['message'] = $dataMessage.'<br /><br />Session Not Found. Please manually reset the modem.';	
						} else {
							$data['message'] = $dataMessage;
						}
					} else { //has no sessions
						log_message('debug', 'no session(s) found for '.$rmUsername);
						$data['message'] = $dataMessage.'<br /><br />Session Not Found. Please manually reset the modem.';
					}
					// either way run the requestDisconnectExtras
					$extrasDeleted = $this->onlinesession->requestDisconnectExtrasOnly($username.'@'.$realm, $this->USESESSIONTABLE2,
						$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD, 
						$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2,
						$this->TBLMCOREHOST, $this->TBLMCOREPORT, $this->TBLMCORESCHEMA, $this->TBLMCOREUSERNAME, $this->TBLMCOREPASSWORD, 
						$this->TBLMCOREHOST2, $this->TBLMCOREPORT2, $this->TBLMCORESCHEMA2, $this->TBLMCOREUSERNAME2, $this->TBLMCOREPASSWORD2);
				}
			} else {
				log_message('debug', 'account '.$rmUsername.' has no "#" at SUBSCRIBER_IDENTITY node');
				$data['message'] = $dataMessage;
			}
		}
		//*/
		$this->load->view('reset_user_password', $data);
	}
	/***********************************************************************
	 * system user accounts
	 * PAGEID = 20
	 ***********************************************************************/
	public function showSysuserAccountsIndex($order = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('System User Account', 'main/showSysuserAccountsIndex');
		$sysuser = $this->session->userdata('username');
		if ($order == null) {
			$order = array('column' => 'USERID', 'dir' => 'asc');
		} else {
			$parts = explode('-', $order);
			$order = array('column' => $parts[0], 'dir' => $parts[1]);
		}
		$this->load->model('sysusermain');
		$sysusers = $this->sysusermain->fetchAll($order, $start, $max, $this->SUPERUSER);
		$this->load->library('encrypt');
		$this->load->model('role');
		for ($i = 0; $i < count($sysusers); $i++) {
			$role = $this->role->fetchById($sysusers[$i]['ROLE']);
			$sysusers[$i]['ROLE'] = $role['ROLENAME'];
			$sysusers[$i]['PASSWORD'] = $this->encrypt->decode($sysusers[$i]['PASSWORD']);
		}
		$count = $this->sysusermain->countSysuers($this->SUPERUSER);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		$onlineCount = $this->sysusermain->countSysusersLoggedIn($this->SUPERUSER);
		$sysuserData = $this->sysusermain->fetchSysuserByUsername($sysuser);
		$roleData = $this->role->fetchById($sysuserData['ROLE']);
		$data = array(
			'su' => $this->SUPERUSER,
			'sysuserRole' => $roleData['ROLENAME'],
			'sysusers' => $sysusers,
			'loggedInSys' => $sysuser,
			'count' => $count,
			'start' => $start,
			'max' => $max,
			'pages' => $pages,
			'order' => $order,
			'onlineCount' => $onlineCount);
		$this->load->view('sysuser_accounts', $data);
	}
	public function showLoggedInSysusers($order = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('System User Account', 'main/showLoggedInSysusers');
		$sysuser = $this->session->userdata('username');
		if ($order == null) {
			$order = array('column' => 'USERID', 'dir' => 'asc');
		} else {
			$parts = explode('-', $order);
			$order = array('column' => $parts[0], 'dir' => $parts[1]);
		}
		$this->load->model('sysusermain');
		$sysusers = $this->sysusermain->fetchAllLoggedIn($order, $start, $max, $this->SUPERUSER);
		$this->load->library('encrypt');
		$this->load->model('role');
		if ($sysusers !== false) {
			for ($i = 0; $i < count($sysusers); $i++) {
				$role = $this->role->fetchById($sysusers[$i]['ROLE']);
				$sysusers[$i]['ROLE'] = $role['ROLENAME'];
				$sysusers[$i]['PASSWORD'] = $this->encrypt->decode($sysusers[$i]['PASSWORD']);
			}
		}
		$count = $this->sysusermain->countSysusersLoggedIn($this->SUPERUSER);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		$data = array(
			'su' => $this->SUPERUSER,
			'loggedInSys' => $sysuser,
			'sysusers' => $sysusers,
			'count' => $count,
			'start' => $start,
			'max' => $max,
			'pages' => $pages,
			'order' => $order);
		$this->load->view('sysuser_accounts_logged_in', $data);
	}
	public function searchSysuserForm() {
		$this->redirectIfNoAccess('System User Account', 'main/searchSysuserForm');
		$data = array(
			'show' => false);
		$this->load->view('sysuser_accounts_search', $data);
	}
	public function searchSysuserProcess() {
		$this->redirectIfNoAccess('System User Account', 'main/searchSysuserProcess');
		$sysuser = $this->session->userdata('username');

		$username = trim($this->input->post('username'));
		if ($username == '') {
			$data['error'] = 'No username specified';
			$data['show'] = false;
			$this->load->view('sysuser_accounts_search', $data);
			return;
		}

		$data = array(
			'username' => $username);
		$this->load->model('sysusermain');
		$sysuser = $this->sysusermain->fetchSysuserByUsername($username);
		if ($sysuser === false || $username == $this->SUPERUSER) {
			$data['error'] = 'Account does not exist.';
			$data['show'] = false;
			$this->load->view('sysuser_accounts_search', $data);
			return;
		}
		$this->load->library('encrypt');
		$this->load->model('role');
		$role = $this->role->fetchById($sysuser['ROLE']);
		$sysuser['ROLE'] = $role['ROLENAME'];
		$sysuser['PASSWORD'] = $this->encrypt->decode($sysuser['PASSWORD']);
		$data['sysuser'] = $sysuser;
		$data['show'] = true;
		$data['su'] = $this->SUPERUSER;
		$this->load->view('sysuser_accounts_search', $data);
	}
	public function createSysuserForm() {
		$this->redirectIfNoAccess('System User Account', 'main/createSysuserForm');
		$this->load->model('role');
		$roles = $this->role->fetchAll($this->SUPERGROUP);
		$data = array(
			'roles' => $roles);
		$this->load->view('sysuser_accounts_create', $data);
	}
	public function createSysuserProcess() {
		$this->redirectIfNoAccess('System User Account', 'main/createSysuserProcess');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$username = $this->input->post('username');
		$rolename = $this->input->post('usergroup');
		$firstname = $this->input->post('firstname');
		$lastname = $this->input->post('lastname');
		$email = $this->input->post('email');
		$this->load->model('role');
		$this->load->model('sysusermain');
		$this->load->model('util');
		$roles = $this->role->fetchAll($this->SUPERGROUP);
		$generatedPassword = $this->util->generateRandomString($this->PASSWORDMINLENGTH);
		$data = array(
			'role' => $rolename,
			'roles' => $roles,
			'username' => $username,
			'firstname' => $firstname,
			'lastname' => $lastname,
			'email' => $email);
		$exists = $this->sysusermain->sysuserExists($username);
		if ($exists || $username == $this->SUPERUSER) {
			$data['error'] = 'Account creation failed. Please select a different username.';
			$this->load->view('sysuser_accounts_create', $data);
			return;
		}
		if (trim($email) != '') {
			$this->load->helper('email');
			$valid = valid_email($email);
			if (!$valid) {
				$data['error'] = 'Not a valid email address';
				$this->load->view('sysuser_accounts_create', $data);
				return;
			}
		}
		$role = $this->role->fetchByName($rolename);
		$created = $this->sysusermain->create($username, $generatedPassword, $lastname, $firstname, $email == '' ? null : $email, $role['ROLEID']);
		$newsu = $this->sysusermain->fetchSysuserByUsername($username);
		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('sysuseractivitylog');
			$this->sysuseractivitylog->logSysuserCreation($username, $role['ROLENAME'], $sysuser, $sysuserIP, time());
		}
		$this->load->library('encrypt');
		$data['message'] = 'Account "<strong>'.$username.'</strong>" created. The generated password is "<strong>'.$this->encrypt->decode($newsu['PASSWORD']).'</strong>"';
		$data['username'] = '';
		$data['role'] = '';
		$data['firstname'] = '';
		$data['lastname'] = '';
		$data['email'] = '';
		$this->load->view('sysuser_accounts_create', $data);
	}
	public function bulkLoadSysuserAccountsProcess($step = null) {
		$this->redirectIfNoAccess('System User Account', 'main/bulkLoadSysuserAccountsProcess');
		if (is_null($step)) { //via form
			$step = $this->input->post('step');
			if ($step == 'upload') {
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx';
    			$config['max_size'] = '50000';
    			$this->load->library('upload', $config);
    			if (!$this->upload->do_upload('file')) { //upload fail
    				$data['step'] = 'upload';
    				$data['error'] = 'Upload failed: '.$this->upload->display_errors();
    				$this->load->view('sysuser_accounts_bulk_load', $data);
    			} else { //upload ok
    				$this->load->model('util');
    				$uploaded = $this->upload->data();
    				$valid = $this->util->verifyBulkSysuserFormat($uploaded['full_path']);
    				if (!$valid) {
    					$data['step'] = 'upload';
    					$data['error'] = 'Invalid file contents';
    					$this->load->view('sysuser_accounts_bulk_load', $data);
    				} else {
    					$rows = $this->util->verifyBulkSysuserData($uploaded['full_path'], 'create');
    					log_message('info', 'ROWS: '.json_encode($rows));
	    				$data = array(
	    					'step' => 'confirm',
	    					'path' => $uploaded['full_path'],
	    					'valid' => $rows['valid'],
	    					'vaildRowNumbers' => $rows['validRowNumbers'],
	    					'invalid' => $rows['invalid'],
	    					'invalidRowNumbers' => $rows['invalidRowNumbers']);
	    				$this->load->view('sysuser_accounts_bulk_load', $data);
    				}
    			}
			} else if ($step == 'create') {
				$now = date('Y-m-d H:i:s', time());
				$path = $this->input->post('path');
				$validRowNumbers = unserialize($this->input->post('validRowNumbers'));
				$invalidRowNumbers = unserialize($this->input->post('invalidRowNumbers'));
				log_message('info', 'VALID ROWS: '.json_encode($validRowNumbers));
				log_message('info', 'INVALID ROWS: '.json_encode($invalidRowNumbers));
				$createdRows = [];
				$notCreatedInvalidData = [];
				$this->load->model('sysusermain');
				$this->load->model('role');
				$this->load->model('util');
				$dataToCreate = $this->util->extractRowsToSysuser($path, $validRowNumbers);
				
				/************************************************************
				 * get this from one of the session variables
				 * $sysuser = $this->session->userdata('username');
				 ************************************************************/
				$sysuserLoggedIn = $this->session->userdata('username');
				$sysuserIP = $this->session->userdata('ip_address');

				$generatedPassword = $this->util->generateRandomString($this->PASSWORDMINLENGTH);
				for ($i = 0; $i < count($dataToCreate); $i++) {
					$sysuser = $this->sysusermain->rowDataToSysuserArray($dataToCreate[$i]);
					$sysuser['PASSWORD'] = $generatedPassword;
					log_message('info', $i.'|SYS: '.json_encode($sysuser));
					$exists = $this->sysusermain->sysuserExists($sysuser['USERNAME']);
					if ($exists) {
						log_message('info', $i.'|username exists');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedInvalidData[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('USERNAME' => 'Duplicate username.'));
						continue;
					}
					if (strlen($sysuser['USERNAME']) <= 3) {
						log_message('info', $i.'|username too short');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedInvalidData[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('USERNAME' => 'Username too short.'));
						continue;
					}
					$role = $this->role->fetchByName($sysuser['ROLE']);
					if ($role === false) {
						log_message('info', $i.'|role dne');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedInvalidData[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('ROLE' => 'No such role.'));
						continue;
					}
					if (!is_null($sysuser['EMAIL'])) {
						$this->load->helper('email');
						$valid = valid_email($sysuser['EMAIL']);
						if (!$valid) {
							log_message('info', $i.'|invalid email');
							$invalidRowNumbers[] = $validRowNumbers[$i];
							$notCreatedInvalidData[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('EMAIL' => 'Invalid email.'));
							continue;
						}
					}
					$result = $this->sysusermain->create($sysuser['USERNAME'], $sysuser['PASSWORD'], $sysuser['LASTNAME'], $sysuser['FIRSTNAME'], $sysuser['EMAIL'], $role['ROLEID']);
					if (!$result) {
						log_message('info', $i.'|sysuser not created');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedInvalidData[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('ALL' => 'Failed to create.'));
						continue;
					}
					$createdRows[] = $dataToCreate[$i];
					if ($sysuserLoggedIn != $this->SUPERUSER) {
						$this->load->model('sysuseractivitylog');
						$this->sysuseractivitylog->logSysuserCreation($sysuser['USERNAME'], $sysuser['ROLE'], $sysuserLoggedIn, $sysuserIP, time());
					}
				}
				log_message('info', '__________________________________________________________________________________');
				log_message('info', 'CREATED: '.json_encode($createdRows));
				log_message('info', 'INVALID DATA: '.json_encode($notCreatedInvalidData));
				$data = array(
					'step' => 'create',
					'path' => $path,
					'generatedPassword' => $generatedPassword,
					'created' => $createdRows,
					'invalidData' => $notCreatedInvalidData,
					'invalidRowNumbers' => $invalidRowNumbers);
				$this->load->view('sysuser_accounts_bulk_load', $data);
			} else if ($step == 'download') {
				echo 'download';
			}
		} else { //via url
			$this->load->view('sysuser_accounts_bulk_load');
		}
	}
	public function bulkModifySysuserAccountsProcess($step = null) {
		$this->redirectIfNoAccess('System User Account', 'main/bulkModifySysuserAccountsProcess');
		if (is_null($step)) { //via form
			$step = $this->input->post('step');
			if ($step == 'upload') {
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx';
    			$config['max_size'] = '50000';
    			$this->load->library('upload', $config);
    			if (!$this->upload->do_upload('file')) { //upload fail
    				$data['step'] = 'upload';
    				$data['error'] = 'Upload failed: '.$this->upload->display_errors();
    				$this->load->view('sysuser_accounts_bulk_modify', $data);
    			} else { //upload ok
    				$this->load->model('util');
    				$uploaded = $this->upload->data();
    				$valid = $this->util->verifyBulkSysuserFormat($uploaded['full_path']);
    				if (!$valid) {
    					$data['step'] = 'upload';
    					$data['error'] = 'Invalid file contents';
    					$this->load->view('sysuser_accounts_bulk_modify', $data);
    				} else {
    					$rows = $this->util->verifyBulkSysuserData($uploaded['full_path'], 'update');
    					log_message('info', 'ROWS: '.json_encode($rows));
	    				$data = array(
	    					'step' => 'confirm',
	    					'path' => $uploaded['full_path'],
	    					'valid' => $rows['valid'],
	    					'vaildRowNumbers' => $rows['validRowNumbers'],
	    					'invalid' => $rows['invalid'],
	    					'invalidRowNumbers' => $rows['invalidRowNumbers']);
	    				$this->load->view('sysuser_accounts_bulk_modify', $data);
    				}
    			}
			} else if ($step == 'update') {
				$now = date('Y-m-d H:i:s', time());
				$path = $this->input->post('path');
				$validRowNumbers = unserialize($this->input->post('validRowNumbers'));
				$invalidRowNumbers = unserialize($this->input->post('invalidRowNumbers'));
				log_message('info', 'VALID ROWS: '.json_encode($validRowNumbers));
				log_message('info', 'INVALID ROWS: '.json_encode($invalidRowNumbers));
				$createdRows = [];
				$notUpdatedInvalidData = [];
				$this->load->model('sysusermain');
				$this->load->model('role');
				$this->load->model('util');
				$dataToUpdate = $this->util->extractRowsToSysuser($path, $validRowNumbers);

				/************************************************************
				 * get this from one of the session variables
				 * $sysuser = $this->session->userdata('username');
				 ************************************************************/
				$sysuserLoggedIn = $this->session->userdata('username');
				$sysuserIP = $this->session->userdata('ip_address');

				for ($i = 0; $i < count($dataToUpdate); $i++) {
					$sysuser = $this->sysusermain->rowDataToSysuserArray($dataToUpdate[$i]);
					$exists = $this->sysusermain->sysuserExists($sysuser['USERNAME']);
					if (!$exists) {
						log_message('info', $i.'|sysuser dne');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notUpdatedInvalidData[] = array('rowdata' => $dataToUpdate[$i], 'errors' => array('USERNAME' => 'Account does not exist.'));
						continue;
					}
					$role = $this->role->fetchByName($sysuser['ROLE']);
					if ($role === false) {
						log_message('info', $i.'|role dne');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedInvalidData[] = array('rowdata' => $dataToUpdate[$i], 'errors' => array('ROLE' => 'No such role.'));
						continue;
					}
					if (!is_null($sysuser['EMAIL'])) {
						$this->load->helper('email');
						$valid = valid_email($sysuser['EMAIL']);
						if (!$valid) {
							log_message('info', $i.'|invalid email');
							$invalidRowNumbers[] = $validRowNumbers[$i];
							$notUpdatedInvalidData[] = array('rowdata' => $dataToUpdate[$i], 'errors' => array('EMAIL' => 'Invalid email.'));
							continue;
						}
					}
					$updated = $this->sysusermain->modify($sysuser['USERNAME'], $sysuser['LASTNAME'], $sysuser['FIRSTNAME'], $sysuser['EMAIL'], $role['ROLEID']);
					if (!$updated) {
						log_message('info', $i.'|sysuser not updated');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notUpdatedInvalidData[] = array('rowdata' => $dataToUpdate[$i], 'errors' => array('ALL' => 'Failed to update.'));
						continue;
					}
					$updatedRows[] = $dataToUpdate[$i];
					if ($sysuserLoggedIn != $this->SUPERUSER) {
						$this->load->model('sysuseractivitylog');
						$this->sysuseractivitylog->logSysuserModification($sysuser['USERNAME'], $sysuser['ROLE'], $sysuserLoggedIn, $sysuserIP, time());
					}
				}
				log_message('info', '__________________________________________________________________________________');
				log_message('info', 'UPDATED: '.json_encode($updatedRows));
				log_message('info', 'INVALID DATA: '.json_encode($notUpdatedInvalidData));
				$data = array(
					'step' => 'update',
					'path' => $path,
					'updated' => $updatedRows,
					'invalidData' => $notUpdatedInvalidData,
					'invalidRowNumbers' => $invalidRowNumbers);
				$this->load->view('sysuser_accounts_bulk_modify', $data);
			} else if ($step == 'download') {
				echo 'download';
			}
		} else { //via url
			$this->load->view('sysuser_accounts_bulk_modify');
		}
	}
	public function bulkDeleteSysuserAccountsProcess($step = null) {
		$this->redirectIfNoAccess('System User Account', 'main/bulkDeleteSysuserAccountsProcess');
		if (is_null($step)) { //via form
			$step = $this->input->post('step');
			if ($step == 'upload') {
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx';
    			$config['max_size'] = '50000';
    			$this->load->library('upload', $config);
    			if (!$this->upload->do_upload('file')) { //upload fail
    				$data['step'] = 'upload';
    				$data['error'] = 'Upload failed: '.$this->upload->display_errors();
    				$this->load->view('sysuser_accounts_bulk_load', $data);
    			} else { //upload ok
    				$this->load->model('util');
    				$uploaded = $this->upload->data();
    				$valid = $this->util->verifyBulkSysuserFormat($uploaded['full_path']);
    				if (!$valid) {
    					$data['step'] = 'upload';
    					$data['error'] = 'Invalid file contents';
    					$this->load->view('sysuser_accounts_bulk_delete', $data);
    				} else {
    					$rows = $this->util->verifyBulkSysuserData($uploaded['full_path'], 'delete');
    					log_message('info', 'ROWS: '.json_encode($rows));
	    				$data = array(
	    					'step' => 'confirm',
	    					'path' => $uploaded['full_path'],
	    					'valid' => $rows['valid'],
	    					'vaildRowNumbers' => $rows['validRowNumbers'],
	    					'invalid' => $rows['invalid'],
	    					'invalidRowNumbers' => $rows['invalidRowNumbers']);
	    				$this->load->view('sysuser_accounts_bulk_delete', $data);
    				}
    			}
			} else if ($step == 'delete') {
				$now = date('Y-m-d H:i:s', time());
				$path = $this->input->post('path');
				$validRowNumbers = unserialize($this->input->post('validRowNumbers'));
				$invalidRowNumbers = unserialize($this->input->post('invalidRowNumbers'));
				log_message('info', 'VALID ROWS: '.json_encode($validRowNumbers));
				log_message('info', 'INVALID ROWS: '.json_encode($invalidRowNumbers));
				$createdRows = [];
				$notDeletedInvalidData = [];
				$this->load->model('sysusermain');
				$this->load->model('role');
				$this->load->model('util');
				$dataToDelete = $this->util->extractRowsToSysuser($path, $validRowNumbers);
				
				/************************************************************
				 * get this from one of the session variables
				 * $sysuser = $this->session->userdata('username');
				 ************************************************************/
				$sysuserLoggedIn = $this->session->userdata('username');
				$sysuserIP = $this->session->userdata('ip_address');
				$deletedRows = array();

				for ($i = 0; $i < count($dataToDelete); $i++) {
					//$sysuser = $this->sysusermain->rowDataToSysuserArray($dataToDelete[$i]);
					$sysData = $this->sysusermain->fetchSysuserByUsername($dataToDelete[$i][0]);
					if ($sysData === false) {
						log_message('info', $i.'|username dne');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notDeletedInvalidData[] = array('rowdata' => $dataToDelete[$i], 'errors' => array('USERNAME' => 'Account does not exist.'));
						continue;
					}
					if (trim($dataToDelete[$i][1]) == '') {
						$role = $this->role->fetchById(intval($sysData['ROLE']));
						$dataToDelete[$i][1] = $role['ROLENAME'];
					}
					if (trim($dataToDelete[$i][2]) == '') {
						$dataToDelete[$i][2] = $sysData['FIRSTNAME'];
					}
					if (trim($dataToDelete[$i][3]) == '') {
						$dataToDelete[$i][3] = $sysData['LASTNAME'];
					}
					if (trim($dataToDelete[$i][4]) == '') {
						$dataToDelete[$i][4] = is_null($sysData['EMAIL']) ? '' : $sysData['EMAIL'];
					}
					$deleted = $this->sysusermain->delete($dataToDelete[$i][0]);
					if (!$deleted) {
						log_message('fail', $i.'|failed to delete');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notDeletedInvalidData[] = array('rowdata' => $dataToDelete[$i], 'errors' => array('ALL' => 'Failed to delete.'));
						continue;
					}
					$deletedRows[] = $dataToDelete[$i];
					if ($sysuserLoggedIn != $this->SUPERUSER) {
						$this->load->model('sysuseractivitylog');
						$this->sysuseractivitylog->logSysuserDeletion($dataToDelete[$i][0], $sysuserLoggedIn, $sysuserIP, time());
					}
				}
				log_message('info', '__________________________________________________________________________________');
				log_message('info', 'DELETED: '.json_encode($deletedRows));
				log_message('info', 'INVALID DATA: '.json_encode($notDeletedInvalidData));
				$data = array(
					'step' => 'delete',
					'path' => $path,
					'deleted' => $deletedRows,
					'invalidData' => $notDeletedInvalidData,
					'invalidRowNumbers' => $invalidRowNumbers);
				$this->load->view('sysuser_accounts_bulk_delete', $data);
			} else if ($step == 'download') {
				echo 'download';
			}
		} else { //via url
			$this->load->view('sysuser_accounts_bulk_delete');
		}
	}
	public function deleteSysuserProcess() {
		if ($this->input->is_ajax_request()) {
			/************************************************
			 * get sysuser from session variable
			 ************************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$username = $this->input->post('username');
			$this->load->model('sysusermain');
			if ($username == $this->SUPERUSER) {
				$data = array(
					'status' => '0',
					'msg' => 'This account cannot be deleted.');
				echo json_encode($data);
				return;
			}
			$deleted = $this->sysusermain->delete($username);
			if (!$deleted) {
				$data = array(
					'status' => '0',
					'msg' => 'Account deletion failed.');
				echo json_encode($data);
				//return;
			} else {
				if ($sysuser != $this->SUPERUSER) {
					$this->load->model('sysuseractivitylog');
					$this->sysuseractivitylog->logSysuserDeletion($username, $sysuser, $sysuserIP, time());
				}
				$data = array(
					'status' => '1',
					'redirect' => 'main/showSysuserAccountsIndex');
				echo json_encode($data);
			}
		} else {
			redirect('main/showSysuserAccountsIndex');
		}
	}
	public function modifySysuserGroupForm($id) {
		$this->redirectIfNoAccess('System User Account', 'main/modifySysuserGroupForm');
		$this->load->model('sysusermain');
		$sysuser = $this->sysusermain->fetchSysuserById($id);
		$this->load->model('role');
		$roles = $this->role->fetchAll($this->SUPERGROUP);
		$roleData = $this->role->fetchById($sysuser['ROLE']);
		$data = array(
			'sysuser' => $sysuser,
			'role' => $roleData['ROLENAME'],
			'su' => $sysuser['USERNAME'] == $this->SUPERUSER,
			'roles' => $roles);
		if ($sysuser['USERNAME'] == $this->SUPERUSER) {
			$data['error'] = 'This account cannot be modified.';
		}
		$this->load->view('sysuser_accounts_modify', $data);
	}
	public function modifySysuserGroupProcess() {
		$this->redirectIfNoAccess('System User Account', 'main/modifySysuserGroupProcess');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$username = $this->input->post('username');
		$role = $this->input->post('usergroup');
		$firstname = $this->input->post('firstname');
		$lastname = $this->input->post('lastname');
		$email = $this->input->post('email');

		$this->load->model('role');
		$roles = $this->role->fetchAll($this->SUPERGROUP);
		$roleData = $this->role->fetchByName($role);
		$this->load->model('sysusermain');
		$sysuserData = $this->sysusermain->fetchSysuserByUsername($username);
		$data = array(
			'sysuser' => $sysuserData,
			'role' => $roleData['ROLENAME'],
			'su' => $sysuserData['USERNAME'] == $this->SUPERUSER,
			'roles' => $roles);
		if ($email != '') {
			$this->load->helper('email');
			$valid = valid_email($email);
			if (!$valid) {
				$sysuserData = array(
					'USERNAME' => $username,
					'FIRSTNAME' => $firstname,
					'LASTNAME' => $lastname,
					'EMAIL' => $email);
				$data['sysuser'] = $sysuserData;
				$data['error'] = 'Account update failed. Invalid email address.';
				$this->load->view('sysuser_accounts_modify', $data);
				return;
			}
		}
		$changed = $this->sysusermain->modify($username, $lastname, $firstname, $email, $roleData['ROLEID']);
		if (!$changed) {
			$sysuserData = array(
				'USERNAME' => $username,
				'FIRSTNAME' => $firstname,
				'LASTNAME' => $lastname,
				'EMAIL' => $email);
			$data['sysuser'] = $sysuserData;
			$data['error'] = 'Account update failed.';
			$this->load->view('sysuser_accounts_modify', $data);
			return;
		}
		$sysuserData = $this->sysusermain->fetchSysuserByUsername($username);
		$data['sysuser'] = $sysuserData;

		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('sysuseractivitylog');
			$this->sysuseractivitylog->logSysuserModification($username, $roleData['ROLENAME'], $sysuser, $sysuserIP, time());
		}
		$data['message'] = 'Account '.$username.' modified.';
		$this->load->view('sysuser_accounts_modify', $data);
	}
	public function blockSysuserProcess() {
		if ($this->input->is_ajax_request()) {
			/************************************************
			 * get sysuser from session variable
			 ************************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$username = $this->input->post('username');
			$this->load->model('sysusermain');
			if ($username == $this->SUPERUSER) {
				$data = array(
					'status' => '0',
					'msg' => 'This account cannot be blocked.');
				echo json_encode($data);
				return;
			}
			$blocked = $this->sysusermain->blockSysuser($username);
			if (!$blocked) {
				$data = array(
					'status' => '0',
					'msg' => 'Failed to block account.');
				echo json_encode($data);
			} else {
				if ($sysuser != $this->SUPERUSER) {
					$this->load->model('sysuseractivitylog');
					$this->sysuseractivitylog->logSysuserBlock($username, $sysuser, $sysuserIP, time());
				}
				$data = array(
					'status' => '1',
					'redirect' => 'main/showSysuserAccountsIndex');
				echo json_encode($data);
			}
		} else {
			redirect('main/showSysuserAccountsIndex');
		}
	}
	public function unblockSysuserProcess() {
		if ($this->input->is_ajax_request()) {
			/************************************************
			 * get sysuser from session variable
			 ************************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$username = $this->input->post('username');
			$this->load->model('sysusermain');
			/*
			if ($username == $this->SUPERUSER) {
				$data = array(
					'status' => '0',
					'msg' => 'This account cannot be blocked.');
				echo json_encode($data);
				return;
			}
			*/
			$unblocked = $this->sysusermain->unblockSysuser($username);
			if (!$unblocked) {
				$data = array(
					'status' => '0',
					'msg' => 'Failed to unblock account.');
				echo json_encode($data);
			} else {
				if ($sysuser != $this->SUPERUSER) {
					$this->load->model('sysuseractivitylog');
					$this->sysuseractivitylog->logSysuserUnblock($username, $sysuser, $sysuserIP, time());
				}
				$data = array(
					'status' => '1',
					'redirect' => 'main/showSysuserAccountsIndex');
				echo json_encode($data);
			}
		} else {
			redirect('main/showSysuserAccountsIndex');
		}
	}
	public function logoutSysuserProcess() {
		if ($this->input->is_ajax_request()) {
			/************************************************
			 * get sysuser from session variable
			 ************************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$username = $this->input->post('username');
			$this->load->model('sysusermain');

			$loggedOut = $this->sysusermain->setSysuserLoggedOut($username);
			if (!$loggedOut) {
				$data = array(
					'status' => '0',
					'msg' => 'Failed to logout user.');
				echo json_encode($data);
			} else {
				if ($sysuser != $this->SUPERUSER) {
					$this->load->model('sysuser');
					$this->sysuser->recordSysuserLogout($username, $this->session->userdata('session'), time());
				}
				$data = array(
					'status' => '1',
					'redirect' => 'main/showSysuserAccountsIndex');
				echo json_encode($data);
			}
		} else {
			redirect('main/showSysuserAccountsIndex');
		}
	}
	/***********************************************************************
	 * system ip address
	 * PAGEID = 21
	 ***********************************************************************/
	public function showSysipaddressesIndex($order = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('System IP Account', 'main/showSysipaddressesIndex');
		if ($order == null) {
			$order = array('column' => 'ID', 'dir' => 'asc');
		} else {
			$parts = explode('-', $order);
			$order = array('column' => $parts[0], 'dir' => $parts[1]);
		}
		$this->load->model('sysuserip');
		$sysuserIPAddresses = $this->sysuserip->fetchAll(null, $order, $start, $max);
		$count = $this->sysuserip->countSysuserIPAddresses();
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		$data = array(
			'sysuserIPAddresses' => $sysuserIPAddresses,
			'count' => $count,
			'start' => $start,
			'max' => $max,
			'pages' => $pages,
			'order' => $order);
		$this->load->view('sys_ip_addresses', $data);
	}
	public function showCreateSysipaddressForm() {
		$this->redirectIfNoAccess('System IP Account', 'main/showCreateSysipaddressForm');
		$this->load->view('sys_ip_addresses_create');
	}
	public function processSysipaddressCreation() {
		$this->redirectIfNoAccess('System IP Account', 'main/processSysipaddressCreation');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('sysuserip');
		$ipaddress = trim($this->input->post('ipaddress'));
		$description = $this->input->post('description');
		$data = array(
			'ipaddress' => $ipaddress,
			'description' => $description);
		$hasSubnet = strpos($ipaddress, '/');
		if ($hasSubnet === false) {
			$valid = $this->sysuserip->ipValid($ipaddress);
		} else {
			$ipParts = explode('/', $ipaddress);
			$valid = $this->sysuserip->ipValid($ipParts[0]);
			$subnetValid = false;
			if ($valid) {
				if (intval($ipParts[1]) < 8 || intval($ipParts[1]) > 32) {
					$valid = false;	
				}
			}
		}
		if (!$valid) {
			$data['error'] = 'The IP address is invalid.';
			$this->load->view('sys_ip_addresses_create', $data);
			return;
		}
		$exists = $this->sysuserip->ipExists($ipaddress);
		if ($exists) {
			$data['error'] = 'The IP address already exists.';
			$this->load->view('sys_ip_addresses_create', $data);
			return;
		}
		/********************************************
		 * can create ip address
		 ********************************************/
		$created = $this->sysuserip->create($ipaddress, $description, null);
		if (!$created) {
			$data['error'] = 'Failed to created IP address. Please try again later.';
			$this->load->view('sys_ip_addresses_create', $data);
			return;
		}
		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('sysuseractivitylog');
			$this->sysuseractivitylog->logSysipCreation($ipaddress, $sysuser, $sysuserIP, time());
		}
		$data['ipaddress'] = '';
		$data['description'] = '';
		$data['message'] = 'Created system IP address : '.$ipaddress.'.';
		$this->load->view('sys_ip_addresses_create', $data);
	}
	public function showModifySysipaddressForm($ipaddress) {
		$this->redirectIfNoAccess('System IP Account', 'main/showModifySysipaddressForm');
		$this->load->model('sysuserip');
		$ipaddress = str_replace('-', '/', $ipaddress);
		$sysuserip = $this->sysuserip->fetchAll($ipaddress, null, null, null);
		$data = array(
			'ipaddress' => $sysuserip[0]['SYSTEMIP'],
			'description' => $sysuserip[0]['REMARKS'],
			'id' => $sysuserip[0]['ID']);
		$this->load->view('sys_ip_addresses_modify', $data);
	}
	public function processSysipaddressModification() {
		$this->redirectIfNoAccess('System IP Account', 'main/processSysipaddressModification');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('sysuserip');
		$id = $this->input->post('id');
		$ipaddress = $this->input->post('ipaddress');
		$description = $this->input->post('description');
		$data = array(
			'id' => $id,
			'ipaddress' => $ipaddress,
			'description' => $description);
		$modified = $this->sysuserip->modifyViaId($id, $ipaddress, $description);
		if (!$modified) {
			$data['error'] = 'Failed to modify IP address data. Please try again later.';
			$this->load->view('sys_ip_addresses_modify', $data);
			return;
		}
		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('sysuseractivitylog');
			$this->sysuseractivitylog->logSysipModification($ipaddress, $sysuser, $sysuserIP, time());
		}
		$data['message'] = 'Modified system IP address: '.$ipaddress.'.';
		$this->load->view('sys_ip_addresses_modify', $data);
	}
	public function processSysipaddressDeletion() {
		if ($this->input->is_ajax_request()) {
			/************************************************
			 * get sysuser from session variable
			 ************************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$this->load->model('sysuserip');
			$ip = $this->input->post('ip');
			$deleted = $this->sysuserip->delete($ip);
			if ($deleted) {
				if ($sysuser != $this->SUPERUSER) {
					$this->load->model('sysuseractivitylog');
					$this->sysuseractivitylog->logSysipDeletion($ip, $sysuser, $sysuserIP, time());
				}
				echo json_encode(array('status' => '1'));
			} else {
				echo json_encode(array('status' => '0'));
			}
		} else {
			redirect('main/showSysipaddressesIndex');
		}
	}
	/***********************************************************************
	 * realms
	 * PAGEID = 22
	 ***********************************************************************/
	public function showRealmsIndex() {
		$this->redirectIfNoAccess('Realm', 'main/showRealmsIndex');
		$this->load->model('realm');
		$realms = $this->realm->fetchAll(null, null, null);
		log_message('info', '_____'.json_encode($realms));
		$data = array(
			'realms' => $realms);
		$this->load->view('realms', $data);
	}
	public function showCreateRealmForm() {
		$this->redirectIfNoAccess('Realm', 'main/showCreateRealmForm');
		$this->load->model('realm');
		$data = array(
			'locations' => $this->realm->DEFAULTLOCATIONS);
		$this->load->view('realms_create', $data);
	}
	public function processRealmCreation() {
		$this->redirectIfNoAccess('Realm', 'main/processRealmCreation');
		/***********************************************************
		 * get this from session variable
		 ***********************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('realm');
		$realm = trim($this->input->post('realm'));
		$remarks = $this->input->post('location');
		$data = array(
			'realm' => $realm,
			'location' => $remarks,
			'locations' => $this->realm->DEFAULTLOCATIONS);
		$exists = $this->realm->realmExists($realm);
		if ($exists) {
			$data['error'] = 'Realm '.$realm.' exists.';
		} else {
			$created = $this->realm->create($realm, $remarks);
			if (!$created) {
				$data['error'] = 'Error creating realm: '.$realm.'.';
			} else {
				$data['realm'] = '';
				$data['location'] = '';
				$data['message'] = 'Created '.$realm.' realm.';
				if ($sysuser != $this->SUPERUSER) {
					$this->load->model('sysuseractivitylog');
					$this->sysuseractivitylog->logRealmCreation($realm, $sysuser, $sysuserIP, time());
				}
			}
		}
		$this->load->view('realms_create', $data);	
	}
	public function showModifyRealmForm($realm, $remarks) {
		$this->redirectIfNoAccess('Realm', 'main/showModifyRealmForm');
		$this->load->model('realm');
		$realmData = $this->realm->fetchByName($realm);
		$data = array(
			'realm' => $realm,
			'realmId' => $realmData['REALMID'],
			'realmOrigName' => $realmData['REALMNAME'],
			'location' => str_replace('_', ' ', $remarks));
		$this->load->view('realms_modify', $data);
	}
	public function processRealmModification() {
		$this->redirectIfNoAccess('Realm', 'main/processRealmModification');
		/***********************************************************
		 * get this from session variable
		 ***********************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('realm');
		$realm = trim($this->input->post('realm'));
		$remarks = trim($this->input->post('location'));
		$realmId = $this->input->post('realmid');
		$realmOrigName = $this->input->post('realmOrigName');
		$data = array(
			'realm' => $realm,
			'location' => $remarks,
			'realmId' => $realmId,
			'realmOrigName' => $realmOrigName);
		$modified = $this->realm->modify($realmId, $realm, $remarks);
		if (!$modified) {
			$data['error'] = 'Error modifying realm: '.$realmOrigName.'.';
		} else {
			$data['message'] = 'Modified '.$realmOrigName.' realm.';
			if ($sysuser != $this->SUPERUSER) {
				$this->load->model('sysuseractivitylog');
				$this->sysuseractivitylog->logRealmModification($realm, $sysuser, $sysuserIP, time());
			}
		}
		$this->load->view('realms_modify', $data);
	}
	public function processRealmDeletion() {
		if ($this->input->is_ajax_request()) {
			/***********************************************
			 * get these from session variables
			 ***********************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$this->load->model('realm');
			$realm = $this->input->post('realm');
			$deleted = $this->realm->delete($realm);
			if (!$deleted) {
				echo json_encode(array('status' => '0'));
			} else {
				if ($sysuser != $this->SUPERUSER) {
					$this->load->model('sysuseractivitylog');
					$this->sysuseractivitylog->logRealmDeletion($realm, $sysuser, $sysuserIP, time());
				}
				echo json_encode(array('status' => '1'));
			}
		} else {
			redirect('main/showRealmsIndex');
		}
	}
	/***********************************************************************
	 * realm user accounts
	 * PAGEID = 23
	 ***********************************************************************/
	public function realmUserAccountsForm() {
		$this->redirectIfNoAccess('Realm User Account', 'main/realmUserAccountsForm');
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();

		$data = array(
			'realms' => $realms,
			'allowBlankInRealm' => true,
			'show' => false);
		$this->load->view('realm_user_accounts', $data);
	}
	public function showRealmUserAccountsIndex($link = 0, $realm = 'null', $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Realm User Account', 'main/showRealmUserAccountsIndex');
		if (intval($link) == 0) { //via form
			$realm = $this->input->post('realm');
		} else { //via link
			$realm = $realm == 'null' ? '' : $realm;
		}
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$yesterday = mktime(date('h'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'));
		
		$data = array(
			'realms' => $realms,
			'allowBlankInRealm' => true,
			'show' => $realm == '' ? false : true,
			'yesterday' => date('Y-m-d', $yesterday));
		if ($realm != '') {
			$this->load->model('subscribermain');
			$subscribers = $this->subscribermain->findAllUsingColumn('RBREALM', $realm, $start, $max, array('column' => 'USER_IDENTITY', 'dir' => 'asc'));
			$count = $this->subscribermain->countAllUsingColumn('RBREALM', $realm);
			$pages = intval($count / $max);
			$last = $count % $max;
			if ($last > 0) {
				$pages = $pages + 1;
			}
			$data['subscribers'] = $subscribers;
			$data['count'] = $count;
			$data['pages'] = $pages;
			$data['realm'] = $realm;
			$data['start'] = $start;
			$data['max'] = $max;
		}
		$this->load->view('realm_user_accounts', $data);
	}
	public function showRealmUserAccountInfo($username, $realm) {
		$this->redirectIfNoAccess('Realm User Account', 'main/showRealmUserAccountInfo');
		$this->load->model('subscribermain');
		$cn = $username.'@'.$realm;
		$subscriber = false;
		$subscriber = $this->subscribermain->findByUserIdentity($cn);
		/******************************************************
		 * fetch informationHistory & sessionHistory
		 ******************************************************/
		$informationHistory = '';
		$sessionHistory = '';
		$data = array(
			'realm' => $realm,
			'subscriber' => $subscriber,
			'informationHistory' => $informationHistory,
			'sessionHistory' => $sessionHistory);
		$data['message'] = $subscriber === false ? 'Subscriber not found.' : '';
		$this->load->view('realm_user_accounts_show', $data);
	}
	/***********************************************************************
	 * change sysuser password
	 * PAGEID = 24
	 ***********************************************************************/
	public function showChangePasswordForm($username = null) {
		$this->redirectIfNoAccess('Change Account Password', 'main/showChangePasswordForm');
		$data = array(
			'username' => $username);
		$this->load->view('change_sysuser_password', $data);
	}
	public function processPasswordChange($username = null) {
		$this->redirectIfNoAccess('Change Account Password', 'main/processPasswordChange');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$username = $this->input->post('username');
		$password = $this->input->post('newpassword1');
		$password2 = $this->input->post('newpassword2');

		$data = array(
			'username' => $username);
		if (trim($password) != trim($password2)) {
			$data['error'] = 'The passwords do not match.';
			$this->load->view('change_sysuser_password', $data);
			return;
		}
		$password = trim($password);
		$this->load->model('sysuser');
		$hist = $this->sysuser->findSysuserPasswordHistory($password, $username, $this->PASSWORDHISTORYSIZE);
		log_message('info', 'password history:'.json_encode($hist));
		if ($hist !== false) {
			$data['error'] = 'The password that you specified has just been recently used for the account.'.
				'<br />Effective date: '.$hist['effective_date'].
				'<br />Please enter a password that has not been used in the last '.$this->PASSWORDHISTORYSIZE.' password changes.';
			$this->load->view('change_sysuser_password', $data);
			return;
		}
		$this->load->model('util');
		$ok = $this->util->isPasswordAcceptable($password);
		log_message('info', $password.'|acceptable:'.json_encode($ok));
		if ($ok['acceptable'] === false || $password == $username) {
			$errorStr = 'The specified password is not acceptable.<br />'.
				'The password must have at least '.$this->PASSWORDMINLENGTH.' characters';
			if (!$this->PASSWORDALLOWUSERNAME) {
				$errorStr = $errorStr.'; must not be the same as your username';
			}
			if ($this->PASSWORDREQUIRENUMBER) {
				$errorStr = $errorStr.'; must contain at least one digit';
			}
			if ($this->PASSWORDREQUIRESYMBOL) {
				$errorStr = $errorStr.'; must contain at least one symbol';
			}
			$errorStr = $errorStr.'.';
			$data['error'] = $errorStr;
			$this->load->view('change_sysuser_password', $data);
			return;
		}
		$this->load->model('sysusermain');
		$sysuserData = $this->sysusermain->fetchSysuserByUsername($username);
		//log_message('info', 'sysuserdata:'.json_encode($sysuserData));
		if ($sysuserData === false) {
			$data['error'] = 'The account does not exist.';
			$this->load->view('change_sysuser_password', $data);
			return;
		}
		if (intval($sysuserData['LOGGED_IN']) == 1 && !is_null($sysuserData['SESSION_ID'])) {
			$data['error'] = 'Cannot change password. User is currently logged in.';
			$this->load->view('change_sysuser_password', $data);
			return;
		}
		$changed = $this->sysusermain->changeSysuserPassword($username, $password, true);
		log_message('info', 'changed:'.json_encode($changed));
		if (!$changed) {
			$data['error'] = 'An unknown error occurred. Please try again.';
			$this->load->view('change_sysuser_password', $data);
			return;
		}
		$this->sysuser->recordPasswordChange($username, $password, time());
		$this->sysuser->cleanPasswordHistory($username, $this->PASSWORDHISTORYSIZE);
		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('sysuseractivitylog');
			$this->sysuseractivitylog->logSysuserChangePassword($username, $sysuser, $sysuserIP, time());
		}
		$data['message'] = 'You have successfully changed the account password. New password is: '.$password.'.';
		$this->load->view('change_sysuser_password', $data);
	}
	/***********************************************************************
	 * change subscriber password
	 * PAGEID = 19
	 ***********************************************************************/
	public function showChangeUserPasswordForm() {
		$this->redirectIfNoAccess('Change Subscriber Password', 'main/showChangeUserPasswordForm');
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$data = array(
			'realms' => $realms);
		$this->load->view('change_user_password', $data);
	}
	public function processChangeUserPassword() {
		$this->redirectIfNoAccess('Change Subscriber Password', 'main/processChangeUserPassword');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$username = $this->input->post('userid');
		$realm = $this->input->post('realm');
		$password = $this->input->post('password');
		$password = trim($password);
		$this->load->model('realm');
		$this->load->model('subscribermain');
		$realms = $this->realm->fetchAllNamesOnly();
		$data = array(
			'username' => $username,
			'realm' => $realm,
			'realms' => $realms);
		$old = $this->subscribermain->findByUserIdentity($username.'@'.$realm);
		if ($old === false) {
			$data['error'] = 'The subscriber cannot be found.';
			$this->load->view('change_user_password', $data);
			return;
		}
		$modifieddate = date('Y-m-d H:i:s', time());
		$changed = $this->subscribermain->changeSubscriberPassword($username, $realm, $password, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
		if (!$changed) {
			$data['error'] = 'Password change unsuccessful.';
			$this->load->view('change_user_password', $data);
			return;
		}

		/*******************************************
		 * do npm change password here
		 *******************************************/
		if ($this->ENABLENPM) {
			$npmResult = true;
			$npmError = '';
			$npmFound = $this->subscribermain->npmFetchXML($username.'@'.$realm, $this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
			if (!$npmFound['found']) {
				$npmResult = false;
				$npmError = $npmFound['error'];
			} else {
				$subscriber = $npmFound['data'];
				log_message('info', json_encode($subscriber));
				$updated = $this->subscribermain->npmUpdateXML($subscriber['USERNAME'], $password, $subscriber['ACTIVATED'], /*$subscriber['RADIUSPOLICY']*/str_replace('~', '-', $old['RADIUSPOLICY']), 
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
				$this->load->view('change_user_password', $data);
				return;
			}
		}
		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('subscriberaudittrail');
			$this->subscriberaudittrail->logSubscriberPasswordChange($old['USERNAME'], $realm, $password, $sysuser, $sysuserIP, time());
		}
		$data['message'] = $modifieddate.': Password change successful for '.$old['USERNAME'].'. New password: '.$password;
		// $dataMessage = $modifieddate.': Password change successful for '.$old['USERNAME'].'. New password: '.$password;

		/*******************************************
		 * subscriber identity change at rm
		 *******************************************
		log_message('debug', 'changing subscriber identity');
		$this->load->library('rm');
		$rmClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
		$rmUsername = $username.'@'.$realm;
		$getResult = $this->rm->wsGetSubscriberProfileByID($rmClient, $rmUsername);
		log_message('debug', 'getResult: '.json_encode($getResult));
		if (intval($getResult['responseCode']) != 200) {
			$data['message'] = $dataMessage.'<br /><br />Account not found in RM.';
		} else {
			$rmAccount = $getResult['account']->entry;
			log_message('debug', 'rmAccount: '.json_encode($rmAccount));
			$oldSubsIdentity = '';
			foreach ($rmAccount as $node) {
				if ($node->key == 'SUBSCRIBER_IDENTITY') {
					$oldSubsIdentity = isset($node->value) ? $node->value : "";
					break;
				}
			}
			$hasExtras = strpos($oldSubsIdentity, '#');
			if ($hasExtras !== false) {
				log_message('debug', 'account has #: '.$rmUsername);
				$newSubsIdentity = substr($oldSubsIdentity, 0, $hasExtras);
				$migrateResult = $this->rm->wsMigrateSubscriber($rmClient, $oldSubsIdentity, $newSubsIdentity);
				if (intval($migrateResult['responseCode']) != 200) {
					log_message('debug', 'failed to migrate account: '.$rmUsername);
					$data['message'] = $dataMessage.'<br /><br />Failed to migrate account.';
				} else {
					log_message('debug', 'migrated account: '.$oldSubsIdentity.' -> '.$newSubsIdentity);
					//get sessions
					$this->load->model('onlinesession');
					$sessions = $this->onlinesession->getSessions2($username, $realm, $this->USESESSIONTABLE2,
						$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
						$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
					if (isset($sessions['data'])) {
						//run requestDisconnect
						for ($i = 0; $i < count($sessions['data']); $i++) {
							$sess = $sessions['data'][$i];
							$deleted = $this->onlinesession->requestDisconnectOnly($sess['USER_NAME'], $sess['NAS_IP_ADDRESS'], $sess['ACCT_SESSION_ID'], $this->USESESSIONTABLE2, 
								$this->DELETESESSIONAPIHOST, $this->DELETESESSIONAPIPORT, $this->DELETESESSIONAPISTUB,
								$this->DELETESESSIONAPIHOST2, $this->DELETESESSIONAPIPORT2, $this->DELETESESSIONAPISTUB2, 
								$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD, 
								$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2,
								$this->TBLMCOREHOST, $this->TBLMCOREPORT, $this->TBLMCORESCHEMA, $this->TBLMCOREUSERNAME, $this->TBLMCOREPASSWORD, 
								$this->TBLMCOREHOST2, $this->TBLMCOREPORT2, $this->TBLMCORESCHEMA2, $this->TBLMCOREUSERNAME2, $this->TBLMCOREPASSWORD2);
						}
						if (!$deleted['result']) {
							log_message('debug', 'session not deleted for '.$rmUsername);
							$data['message'] = $dataMessage.'<br /><br />Session Not Found. Please manually reset the modem.';	
						} else {
							$data['message'] = $dataMessage;
						}
					} else { //has no sessions
						log_message('debug', 'no session(s) found for '.$rmUsername);
						$data['message'] = $dataMessage.'<br /><br />Session Not Found. Please manually reset the modem.';
					}
					// either way run the requestDisconnectExtras
					$extrasDeleted = $this->onlinesession->requestDisconnectExtrasOnly($username.'@'.$realm, $this->USESESSIONTABLE2,
						$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD, 
						$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2,
						$this->TBLMCOREHOST, $this->TBLMCOREPORT, $this->TBLMCORESCHEMA, $this->TBLMCOREUSERNAME, $this->TBLMCOREPASSWORD, 
						$this->TBLMCOREHOST2, $this->TBLMCOREPORT2, $this->TBLMCORESCHEMA2, $this->TBLMCOREUSERNAME2, $this->TBLMCOREPASSWORD2);
				}
			} else {
				log_message('debug', 'account '.$rmUsername.' has no "#" at SUBSCRIBER_IDENTITY node');
				$data['message'] = $dataMessage;
			}
		}
		//*/
		$this->load->view('change_user_password', $data);
	}
	/***********************************************************************
	 * system user groups
	 * PAGEID = 25
	 ***********************************************************************/
	public function showSysUserGroupsIndex() {
		$this->redirectIfNoAccess('System User Group', 'main/showSysUserGroupsIndex');
		$this->load->model('role');
		$roles = $this->role->fetchAll($this->SUPERGROUP);
		$data = array(
			'roles' => $roles);
		$this->load->view('sysuser_groups', $data);
	}
	public function sysUserGroupCreateForm() {
		$this->redirectIfNoAccess('System User Group', 'main/sysUserGroupCreateForm');
		$this->load->model('realm');
		$availableRealms = $this->realm->fetchAllNamesOnly();

		$data = array(
			'maxrealms' => 10,
			'availableRealms' => $availableRealms);
		$this->load->view('sysuser_groups_create', $data);
	}
	public function sysUserGroupCreateProcess() {
		$this->redirectIfNoAccess('System User Group', 'main/sysUserGroupCreateProcess');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('realm');
		$this->load->model('role');
		$availableRealms = $this->realm->fetchAllNamesOnly();
		$role = $this->input->post('usergroup');
		$label = $this->input->post('label');
		$maxRealms = $this->input->post('maxrealms');
		$realms = [];
		$realmsTemp = [];
		for ($i = 0; $i < intval($maxRealms); $i++) {
			if (!in_array(trim($this->input->post('realm'.$i)), $realmsTemp)) {
				$realmsTemp[] = trim($this->input->post('realm'.$i));
				$realms['realm'.$i] = trim($this->input->post('realm'.$i));
			} else {
				$realms['realm'.$i] = '';
			}
		}
		$data = array(
			'maxrealms' => $maxRealms,
			'role' => $role,
			'label' => $label,
			'realms' => $realms,
			'availableRealms' => $availableRealms);
		$roleExists = $this->role->roleExists($role);
		if ($roleExists) {
			$data['error'] = 'Please enter a different group name.';
			$this->load->view('sysuser_groups_create', $data);
			return;
		}
		$toAdd = [];
		foreach($realms as $keys => $value) {
			if (trim($value) != '') {
				$toAdd[] = $value;
			}
		}
		if (count($toAdd) == 0) {
			$data['error'] = 'Please select a realm.';
			$this->load->view('sysuser_groups_create', $data);
			return;
		}
		$created = $this->role->create($role, $label);
		if (!$created) {
			$data['error'] = 'Failed to create user group: '.$role.'.';
			$this->load->view('sysuser_groups_create', $data);
			return;
		}
		$role = $this->role->fetchByName($role);
		/***********************************************************
		 * add allowed realms ($toAdd) to table
		 ***********************************************************/
		$this->load->model('tbldslbucket');
		for ($i = 0; $i < count($toAdd); $i++) {
			$realm = $this->realm->fetchByName($toAdd[$i]);
			$this->tbldslbucket->addRealmToRole($role['ROLEID'], $realm['REALMID']);
		}

		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('sysuseractivitylog');
			$this->sysuseractivitylog->logUsergroupCreation($role['ROLENAME'], $sysuser, $sysuserIP, time());
		}
		$data['message'] = 'User group \''.$role['ROLENAME'].'\' created.';
		$data['role'] = '';
		$data['label'] = '';
		for ($i = 0; $i < intval($maxRealms); $i++) {
			$realms['realm'.$i] = '';
		}
		$data['realms'] = $realms;
		$this->load->view('sysuser_groups_create', $data);
	}
	public function sysUserGroupDeleteProcess() {
		if ($this->input->is_ajax_request()) {
			/***********************************************
			 * get these from session variables
			 ***********************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$roleId = $this->input->post('roleId');
			$roleName = $this->input->post('roleName');
			$this->load->model('tbldslbucket');
			//delete time
			$this->tbldslbucket->deleteAllTimesFromRole($roleId);
			//delete allowed realms
			$this->tbldslbucket->deleteAllRealmsFromRole($roleId);
			//delete all allowed pages
			$this->tbldslbucket->deleteAllPagesFromRole($roleId);
			//delete role
			$this->load->model('role');
			$this->role->deleteById($roleId);
			if ($sysuser != $this->SUPERUSER) {
				$this->load->model('sysuseractivitylog');
				$this->sysuseractivitylog->logUsergroupDeletion($roleName, $sysuser, $sysuserIP, time());
			}
			echo json_encode(array('status' => '1'));
		} else {
			redirect('main/showSysUserGroupsIndex');
		}
	}
	public function showSysUserGroupPages($roleId, $roleName) {
		$this->redirectIfNoAccess('System User Group', 'main/showSysUserGroupPages');
		$this->load->model('role');
		$this->load->model('tbldslbucket');
		$role = $this->role->fetchById($roleId);
		$pages = $this->tbldslbucket->fetchPagesForRole($roleId);
		log_message('info', json_encode($pages));
		$data = array(
			'role' => $role,
			'pages' => $pages);
		$this->load->view('sysuser_groups_allowed_pages', $data);
	}
	public function sysUserGroupPagesCreateForm($roleId) {
		$this->redirectIfNoAccess('System User Group', 'main/sysUserGroupPagesCreateForm');
		$this->load->model('role');
		$this->load->model('tbldslbucket');
		$availablePages = $this->tbldslbucket->fetchPagesNotInRole($roleId);
		$role = $this->role->fetchById($roleId);
		$data = array(
			'role' => $role,
			'allPages' => $availablePages,
			'message' => $availablePages === false ? 'All pages allowed.' : '');
		$this->load->view('sysuser_groups_allowed_pages_add', $data);
	}
	public function sysUserGroupPagesCreateProcess() {
		$this->redirectIfNoAccess('System User Group', 'main/sysUserGroupPagesCreateProcess');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$roleId = $this->input->post('roleid');
		$pages = $this->input->post('pages');
		$this->load->model('tbldslbucket');
		$this->load->model('role');
		$this->load->model('page');
		$role = $this->role->fetchById($roleId);
		$data = array(
			'role' => $role);
		$addedPages = [];
		for ($i = 0; $i < count($pages); $i++) {
			$page = $pages[$i];
			$added = $this->tbldslbucket->addPageToRole($role['ROLEID'], $page);
			if ($added) {
				$addedPages[] = intval($page);
			}
		}
		if (count($addedPages) == 0) {
			$data['error'] = 'There were no pages added.';
			$data['availablePages'] = $this->tbldslbucket->fetchPagesNotInRole($role['ROLEID']);
			$this->load->view('sysuser_groups_allowed_pages_add', $data);
			return;
		}
		$addedPagesStr = '';
		$pageNames = $this->page->fetchPageNames($addedPages);
		for ($i = 0; $i < count($pageNames); $i++) {
			$addedPagesStr = $addedPagesStr.$pageNames[$i];
			if ($i + 1 != count($pageNames)) {
				$addedPagesStr = $addedPagesStr.', ';
			}
		}
		$data['message'] = count($addedPages).' page'.(count($addedPages) == 1 ? ' was' : 's were').' added to '.$role['ROLENAME'].':<br />'.$addedPagesStr;
		$data['allPages'] = $this->tbldslbucket->fetchPagesNotInRole($role['ROLEID']);
		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('sysuseractivitylog');
			$this->sysuseractivitylog->logUsergroupModification($role['ROLENAME'], $sysuser, $sysuserIP, time());
		}
		$this->load->view('sysuser_groups_allowed_pages_add', $data);
	}
	public function sysUserGroupPagesDeleteProcess() {
		if ($this->input->is_ajax_request()) {
			/***********************************************
			 * get these from session variables
			 ***********************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$this->load->model('tbldslbucket');
			$roleId = $this->input->post('roleId');
			$pageId = $this->input->post('pageId');
			$deleted = $this->tbldslbucket->deletePageFromRole($roleId, $pageId);
			if ($deleted) {
				$this->load->model('role');
				$role = $this->role->fetchById($roleId);
				if ($sysuser != $this->SUPERUSER) {
					$this->load->model('sysuseractivitylog');
					$this->sysuseractivitylog->logUsergroupModification($role['ROLENAME'], $sysuser, $sysuserIP, time());
				}
				echo json_encode(array('status' => '1'));
			} else {
				echo json_encode(array('status' => '0'));
			}
		} else {
			redirect('main/showSysUserGroupsIndex');
		}
	}
	public function showSysUserGroupRealmsEdit($roleId, $roleName) {
		$this->redirectIfNoAccess('System User Group', 'main/showSysUserGroupRealmsEdit');
		$this->load->model('role');
		$this->load->model('realm');
		$this->load->model('tbldslbucket');
		$role = $this->role->fetchById($roleId);
		$allowedRealms = $this->tbldslbucket->fetchRealmsForRole($roleId);
		$availableRealms = $this->tbldslbucket->fetchRealmsNotInRole($roleId);
		$data = array(
			'role' => $role,
			'maxRealms' => 10,
			'allowedRealms' => $allowedRealms,
			'allowedRealmCount' => $allowedRealms === false ? 0 : count($allowedRealms),
			'availableRealms' => $availableRealms);
		$this->load->view('sysuser_groups_allowed_realms', $data);
	}
	public function sysUserGroupRealmsProcess() {
		$this->redirectIfNoAccess('System User Group', 'main/sysUserGroupRealmsProcess');
		/***********************************************
		 * get these from session variables
		 ***********************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$roleId = $this->input->post('usergroup');
		$maxRealms = $this->input->post('maxrealms');
		$additionalRealm = $this->input->post('additionalRealm');
		$this->load->model('role');
		$this->load->model('tbldslbucket');
		$this->load->model('realm');
		$role = $this->role->fetchById($roleId);
		$realm = $this->realm->fetchById($additionalRealm);
		$added = $this->tbldslbucket->addRealmToRole($roleId, $additionalRealm);
		$allowedRealms = $this->tbldslbucket->fetchRealmsForRole($role['ROLEID']);
		$data = array(
			'role' => $role,
			'maxRealms' => $maxRealms,
			'allowedRealms' => $allowedRealms,
			'allowedRealmCount' => $allowedRealms === false ? 0 : count($allowedRealms),
			'availableRealms' => $this->tbldslbucket->fetchRealmsNotInRole($role['ROLEID']));
		if (!$added) {
			$data['error'] = 'Failed to add realm: '.$realm['REALMNAME'].'.';
			$this->load->view('sysuser_groups_allowed_realms', $data);
			return;		
		}
		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('sysuseractivitylog');
			$this->sysuseractivitylog->logUsergroupModification($role['ROLENAME'], $sysuser, $sysuserIP, time());
		}
		$data['message'] = 'Added realm: '.$realm['REALMNAME'].'.';
		$this->load->view('sysuser_groups_allowed_realms', $data);
	}
	public function sysUserGroupRealmsDeleteProcess() {
		if ($this->input->is_ajax_request()) {
			/***********************************************
			 * get these from session variables
			 ***********************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$roleId = $this->input->post('roleId');
			$realmId = $this->input->post('realmId');
			log_message('info', json_encode($roleId).'|'.json_encode($realmId));
			$this->load->model('tbldslbucket');
			$deleted = $this->tbldslbucket->deleteRealmFromRole($roleId, $realmId);
			if ($deleted) {
				$this->load->model('role');
				$role = $this->role->fetchById($roleId);
				if ($sysuser != $this->SUPERUSER) {
					$this->load->model('sysuseractivitylog');
					$this->sysuseractivitylog->logUsergroupModification($role['ROLENAME'], $sysuser, $sysuserIP, time());
				}
				echo json_encode(array('status' => '1', 'redirect' => 'main/showSysUserGroupRealmsEdit/'.$role['ROLEID'].'/'.str_replace(' ', '_', $role['ROLENAME'])));
			} else {
				echo json_encode(array('status' => '0'));
			}
		} else {
			redirect('main/showSysUserGroupsIndex');
		}
	}
	public function showSysUserGroupAllowedDaysAndTimeEdit($roleId, $roleName) {
		$this->redirectIfNoAccess('System User Group', 'main/showSysUserGroupAllowedDaysAndTimeEdit');
		$this->load->model('role');
		$this->load->model('systime');
		$this->load->model('tbldslbucket');
		$role = $this->role->fetchById($roleId);
		$allowed = $this->tbldslbucket->fetchTimesForRole($role['ROLEID']);
		log_message('info', 'allowed times:'.json_encode($allowed));
		$timeData = false;
		if ($allowed !== false) {
			$timeData = array();
			for ($i = 0; $i < count($allowed); $i++) { //[0]: {'ID': 0-23/31-37, 'NM': 12AM-11PM/Sunday-Saturday}
				$t = $allowed[$i];
				log_message('info', '>>>'.json_encode($t));
				if ($t['NM'] == 0) {
					$timeData['T00'] = 1;
				} else if ($t['NM'] == 1) {
					$timeData['T01'] = 1;
				} else if ($t['NM'] == 2) {
					$timeData['T02'] = 1;
				} else if ($t['NM'] == 3) {
					$timeData['T03'] = 1;
				} else if ($t['NM'] == 4) {
					$timeData['T04'] = 1;
				} else if ($t['NM'] == 5) {
					$timeData['T05'] = 1;
				} else if ($t['NM'] == 6) {
					$timeData['T06'] = 1;
				} else if ($t['NM'] == 7) {
					$timeData['T07'] = 1;
				} else if ($t['NM'] == 8) {
					$timeData['T08'] = 1;
				} else if ($t['NM'] == 9) {
					$timeData['T09'] = 1;
				} else if ($t['NM'] == 10) {
					$timeData['T10'] = 1;
				} else if ($t['NM'] == 11) {
					$timeData['T11'] = 1;
				} else if ($t['NM'] == 12) {
					$timeData['T12'] = 1;
				} else if ($t['NM'] == 13) {
					$timeData['T13'] = 1;
				} else if ($t['NM'] == 14) {
					$timeData['T14'] = 1;
				} else if ($t['NM'] == 15) {
					$timeData['T15'] = 1;
				} else if ($t['NM'] == 16) {
					$timeData['T16'] = 1;
				} else if ($t['NM'] == 17) {
					$timeData['T17'] = 1;
				} else if ($t['NM'] == 18) {
					$timeData['T18'] = 1;
				} else if ($t['NM'] == 19) {
					$timeData['T19'] = 1;
				} else if ($t['NM'] == 20) {
					$timeData['T20'] = 1;
				} else if ($t['NM'] == 21) {
					$timeData['T21'] = 1;
				} else if ($t['NM'] == 22) {
					$timeData['T22'] = 1;
				} else if ($t['NM'] == 23) {
					$timeData['T23'] = 1;
				} else if ($t['NM'] == 31) {
					$timeData['S'] = 1;
				} else if ($t['NM'] == 32) {
					$timeData['M'] = 1;
				} else if ($t['NM'] == 33) {
					$timeData['T'] = 1;
				} else if ($t['NM'] == 34) {
					$timeData['W'] = 1;
				} else if ($t['NM'] == 35) {
					$timeData['Th'] = 1;
				} else if ($t['NM'] == 36) {
					$timeData['F'] = 1;
				} else if ($t['NM'] == 37) {
					$timeData['Sa'] = 1;
				}
			}
		}
		log_message('info', 'allowed times:'.json_encode($timeData));
		$data = array(
			'role' => $role,
			'allowedTimes' => $timeData);
		$this->load->view('sysuser_groups_modify_days_and_time', $data);
	}
	public function sysUserGroupAllowedDaysAndTimeEditProcess() {
		$this->redirectIfNoAccess('System User Group', 'main/sysUserGroupAllowedDaysAndTimeEditProcess');
		/***********************************************
		 * get these from session variables
		 ***********************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$roleId = $this->input->post('usergroup');
		$t00 = $this->input->post('T00');
		$t01 = $this->input->post('T01');
		$t02 = $this->input->post('T02');
		$t03 = $this->input->post('T03');
		$t04 = $this->input->post('T04');
		$t05 = $this->input->post('T05');
		$t06 = $this->input->post('T06');
		$t07 = $this->input->post('T07');
		$t08 = $this->input->post('T08');
		$t09 = $this->input->post('T09');
		$t10 = $this->input->post('T10');
		$t11 = $this->input->post('T11');
		$t12 = $this->input->post('T12');
		$t13 = $this->input->post('T13');
		$t14 = $this->input->post('T14');
		$t15 = $this->input->post('T15');
		$t16 = $this->input->post('T16');
		$t17 = $this->input->post('T17');
		$t18 = $this->input->post('T18');
		$t19 = $this->input->post('T19');
		$t20 = $this->input->post('T20');
		$t21 = $this->input->post('T21');
		$t22 = $this->input->post('T22');
		$t23 = $this->input->post('T23');
		$sun = $this->input->post('S');
		$mon = $this->input->post('M');
		$tue = $this->input->post('T');
		$wed = $this->input->post('W');
		$thu = $this->input->post('Th');
		$fri = $this->input->post('F');
		$sat = $this->input->post('Sa');
		$toRemove = array();
		$toAdd = array();
		$this->load->model('tbldslbucket');
		$this->load->model('systime');
		$time = $this->systime->fetchByTimeValue(0);
		if ($t00 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(1);
		if ($t01 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(2);
		if ($t02 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(3);
		if ($t03 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(4);
		if ($t04 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(5);
		if ($t05 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(6);
		if ($t06 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(7);
		if ($t07 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(8);
		if ($t08 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(9);
		if ($t09 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(10);
		if ($t10 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(11);
		if ($t11 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(12);
		if ($t12 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(13);
		if ($t13 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(14);
		if ($t14 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(15);
		if ($t15 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(16);
		if ($t16 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(17);
		if ($t17 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(18);
		if ($t18 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(19);
		if ($t19 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(20);
		if ($t20 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(21);
		if ($t21 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(22);
		if ($t22 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(23);
		if ($t23 == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(31);
		if ($sun == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(32);
		if ($mon == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(33);
		if ($tue == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(34);
		if ($wed == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(35);
		if ($thu == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(36);
		if ($fri == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		$time = $this->systime->fetchByTimeValue(37);
		if ($sat == 'Y') {
			$this->tbldslbucket->addTimeToRole($roleId, $time['TIMEID']);
		} else {
			$this->tbldslbucket->deleteTimeFromRole($roleId, $time['TIMEID']);
		}
		
		$this->load->model('role');
		$role = $this->role->fetchById($roleId);
		$allowed = $this->tbldslbucket->fetchTimesForRole($role['ROLEID']);
		log_message('info', '@save|'.json_encode($allowed));
		$timeData = false;
		if ($allowed !== false) {
			$timeData = array();
			for ($i = 0; $i < count($allowed); $i++) { //[0]: {'ID': 0-23/31-37, 'NM': 12AM-11PM/Sunday-Saturday}
				$t = $allowed[$i];
				log_message('info', '>>>'.json_encode($t));
				if ($t['NM'] == 0) {
					$timeData['T00'] = 1;
				} else if ($t['NM'] == 1) {
					$timeData['T01'] = 1;
				} else if ($t['NM'] == 2) {
					$timeData['T02'] = 1;
				} else if ($t['NM'] == 3) {
					$timeData['T03'] = 1;
				} else if ($t['NM'] == 4) {
					$timeData['T04'] = 1;
				} else if ($t['NM'] == 5) {
					$timeData['T05'] = 1;
				} else if ($t['NM'] == 6) {
					$timeData['T06'] = 1;
				} else if ($t['NM'] == 7) {
					$timeData['T07'] = 1;
				} else if ($t['NM'] == 8) {
					$timeData['T08'] = 1;
				} else if ($t['NM'] == 9) {
					$timeData['T09'] = 1;
				} else if ($t['NM'] == 10) {
					$timeData['T10'] = 1;
				} else if ($t['NM'] == 11) {
					$timeData['T11'] = 1;
				} else if ($t['NM'] == 12) {
					$timeData['T12'] = 1;
				} else if ($t['NM'] == 13) {
					$timeData['T13'] = 1;
				} else if ($t['NM'] == 14) {
					$timeData['T14'] = 1;
				} else if ($t['NM'] == 15) {
					$timeData['T15'] = 1;
				} else if ($t['NM'] == 16) {
					$timeData['T16'] = 1;
				} else if ($t['NM'] == 17) {
					$timeData['T17'] = 1;
				} else if ($t['NM'] == 18) {
					$timeData['T18'] = 1;
				} else if ($t['NM'] == 19) {
					$timeData['T19'] = 1;
				} else if ($t['NM'] == 20) {
					$timeData['T20'] = 1;
				} else if ($t['NM'] == 21) {
					$timeData['T21'] = 1;
				} else if ($t['NM'] == 22) {
					$timeData['T22'] = 1;
				} else if ($t['NM'] == 23) {
					$timeData['T23'] = 1;
				} else if ($t['NM'] == 31) {
					$timeData['S'] = 1;
				} else if ($t['NM'] == 32) {
					$timeData['M'] = 1;
				} else if ($t['NM'] == 33) {
					$timeData['T'] = 1;
				} else if ($t['NM'] == 34) {
					$timeData['W'] = 1;
				} else if ($t['NM'] == 35) {
					$timeData['Th'] = 1;
				} else if ($t['NM'] == 36) {
					$timeData['F'] = 1;
				} else if ($t['NM'] == 37) {
					$timeData['Sa'] = 1;
				}
			}
		}
		log_message('info', '@save|'.json_encode($timeData));
		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('sysuseractivitylog');
			$this->sysuseractivitylog->logUsergroupModification($role['ROLENAME'], $sysuser, $sysuserIP, time());
		}
		$data = array(
			'role' => $role,
			'allowedTimes' => $timeData,
			'message' => 'Allowed times updated.');
		$this->load->view('sysuser_groups_modify_days_and_time', $data);	
	}
	/***********************************************************************
	 * manage ip addresses
	 * PAGEID = 26
	 ***********************************************************************/
	public function showIpaddressesIndex($link = 0, $order = null, $ipaddress = null, $username = null, $location = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Manage IP Addresses', 'main/showIpaddressesIndex');
		$realm = $this->session->userdata('realm');
		$submit = ''; 
		$pages = 0;
		if (intval($link) == 0) { //via form
			log_message('info', '@FORM');
			$location = $this->input->post('location');
			$ipaddress = $this->input->post('ipaddress');
			$username = $this->input->post('username');
			$location = $location == '' ? null : $location;
			$ipaddress = trim($ipaddress) == '' ? null : trim($ipaddress);
			$username = trim($username) == '' ? null : trim($username);
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$submit = $this->input->post('btnRefresh');
			log_message('info', 'SUBMIT|'.json_encode($submit));
			$start = $submit == 'refresh' ? 0 : $start;
			$order = $this->input->post('order');
			$orderParts = explode('-', $order);
			$order = array('column' => $orderParts[0], 'dir' => $orderParts[1]);
		} else { //via link
			log_message('info', '@LINK');
			if ($order == null) {
				$order = array('column' => 'IPUSED', 'dir' => 'asc');
			} else {
				$parts = explode('-', $order);
				$order = array('column' => $parts[0], 'dir' => $parts[1]);
			}
			// [AJB] Removed 041818
			$location = is_null($location) ? $location : ($location == 'null' ? null : str_replace('--', ' ', $location));
			$ipaddress = is_null($ipaddress) ? $ipaddress : ($ipaddress == 'null' ? null : str_replace('_', ' ', $ipaddress));
			if (!is_null($username)) {
				if ($username == 'null') {
					$username = null;
				} else {
					$username = str_replace('_', ' ', $username);
					$username = str_replace('~', '*', $username);
					$username = str_replace('%', '@', $username);
				}
			}
			if (!is_null($ipaddress)) {
				$ipaddress = str_replace('~', '*', $ipaddress);
			}
		}
		$ipaddressStr = $ipaddress;
		$exact = strpos($ipaddress, '*') === false ? true : false;
		$wildcard = 'both';
		if (!$exact) {
			if (strpos($ipaddress, '*') == 0) {
				$wildcard = 'before';
			} else if (strpos($ipaddress, '*') == (strlen($ipaddress) - 1)) {
				$wildcard = 'after';
			}
			$ipaddress = substr($ipaddress, 0, strpos($ipaddress, '*'));
		}
		$this->load->model('ipaddress');
		//$locations = $this->ipaddress->LOCATIONS;
		$locations = $this->ipaddress->fetchAllLocationsFromExtras();
		$cn = is_null($username) ? null : $username;//.'@'.$realm;
		$ipaddresses = $this->ipaddress->fetchAll($ipaddress, $location, null, null, $cn, $exact, $wildcard, null, $start, $max, $order);
		$count = $this->ipaddress->countIPAddresses($ipaddress, $location, null, null, $cn, $exact, $wildcard, null);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		log_message('info', 'ORDER|'.json_encode($order));
		log_message('info', 'LOCATION|'.json_encode($location));
		log_message('info', 'IP|'.json_encode($ipaddress));
		log_message('info', 'USERNAME|'.json_encode($username));
		log_message('info', 'START|'.json_encode($start));
		log_message('info', 'MAX|'.json_encode($max));
		log_message('info', 'IPS|'.json_encode($ipaddresses));
		log_message('info', 'PAGES|'.json_encode($pages));
		log_message('info', 'COUNT|'.json_encode($count));
		$data = array(
			'ipaddresses' => $ipaddresses,
			'count' => $count,
			'start' => $start,
			'max' => $max,
			'pages' => $pages,
			'order' => $order,
			'locations' => $locations,
			'location' => $location,
			'ipaddress' => $ipaddressStr,
			'username' => $username);
		$data['useIPv6'] = $this->useIPv6;
		$this->load->view('manage_ip_addresses', $data);
	}
	public function createIpaddressForm() {
		$this->redirectIfNoAccess('Manage IP Addresses', 'main/createIpaddressForm');
		$this->load->model('ipaddress');
		//$locations = $this->ipaddress->LOCATIONS;
		$locations = $this->ipaddress->fetchAllLocationsFromExtras();
		$set = false;
		$location = $set ? 'VALERO' : '';
		$isGPON = false;
		/********************************************************
		 * Q: should the location be automatically assigned (fetch from session data)?
		 * or the ip creator can pick? --> creator can pick
		 ********************************************************/
		$data = array(
			'locations' => $locations,
			'location' => $location,
			'isGPON' => $isGPON,
			'set' => $set);
		$this->load->view('manage_ip_addresses_create', $data);
	}
	public function createIpaddressProcess() {
		$this->redirectIfNoAccess('Manage IP Addresses', 'main/createIpaddressProcess');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('ipaddress');
		//$locations = $this->ipaddress->LOCATIONS;
		$locations = $this->ipaddress->fetchAllLocationsFromExtras();
		$set = intval($this->input->post('set')) == 1 ? true : false;
		$location = $this->input->post('location');
		$ipaddress = $this->input->post('ipaddress');
		$isGPON = $this->input->post('isGPON');
		$data = array(
			'locations' => $locations,
			'location' => $location,
			'isGPON' => $isGPON == 'Y' ? true : false,
			'set' => $set,
			'ipaddress' => $ipaddress);
		$valid = $this->ipaddress->isIPValid($ipaddress);
		if (!$valid) {
			$data['error'] = 'The IP address is invalid.';
			$this->load->view('manage_ip_addresses_create', $data);
			return;
		}
		$exists = $this->ipaddress->ipExists($ipaddress, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
		if ($exists) {
			$data['error'] = 'The IP address already exists.';
			$this->load->view('manage_ip_addresses_create', $data);
			return;	
		}
		/************************************************
		 * can create ip address
		 ************************************************/
		$created = $this->ipaddress->create($ipaddress, $location, $isGPON, '', $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
		if (!$created) {
			$data['error'] = 'Failed to create IP address.';
			$this->load->view('manage_ip_addresses_create', $data);
			return;
		}
		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('sysuseractivitylog');
			$this->sysuseractivitylog->logIpAddressCreation($ipaddress, $sysuser, $sysuserIP, time()); //log ipaddress creation
		}
		$data['location'] = $set ? $location : '';
		$data['ipaddress'] = '';
		$data['isGPON'] = false;
		$data['message'] = 'Created IP address '.$ipaddress.' for '.$location;
		$this->load->view('manage_ip_addresses_create', $data);
	}
	public function showDeleteIpaddressRangeForm() {
		$this->redirectIfNoAccess('Manage IP Addresses', 'main/showDeleteIpaddressRangeForm');
		$this->load->view('manage_ip_addresses_delete_range');
	}
	public function deleteIpaddressRangeProcess() {
		$this->redirectIfNoAccess('Manage IP Addresses', 'main/deleteIpaddressRangeProcess');
		/*example: 120.120.120.45/28
		so
		/28 = 16 IP – 2(reserve gateway and broadcast) = 14 Free IP to be delete
		First IP (Gateway)= 120.120.120.32
		Last IP (Brodcast)= 120.120.120.47
		E-dedelete nya yung FREE na IP from 120.120.120.33 to 120.120.120.46

		http://www.ipaddressguide.com/cidr
		http://bp0.blogger.com/_YiHsbP4vJso/RiNS6tEJ30I/AAAAAAAAAFM/ScD4ygT1xy8/s1600-h/cidr.JPG*/

		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('util');
		$this->load->model('ipaddress');
		$this->load->model('sysuseractivitylog');
		$ipaddress = trim($this->input->post('ipaddress'));
		$enteredIP = $ipaddress;
		$data = array('ipaddress' => $ipaddress);
		$parts = explode('/', $ipaddress);
		if (count($parts) != 2) {
			$data['error'] = 'Invalid CIDR format';
			$this->load->view('manage_ip_addresses_delete_range', $data);
			return;
		}
		$validIP = $this->util->isIPValid($parts[0]);
		if (!$validIP) {
			$data['error'] = 'Invalid IP Address';
			$this->load->view('manage_ip_addresses_delete_range', $data);
			return;
		}
		$IPParts = explode('.', $parts[0]);
		if (intval($parts[1]) < 8 || intval($parts[1]) > 32 || is_nan($parts[1])) {
			$data['error'] = 'Invalid CIDR format';
			$this->load->view('manage_ip_addresses_delete_range', $data);
			return;
		}
		$cidr = array(
			'32' => 1,
			'31' => 2,
			'30' => 4,
			'29' => 8,
			'28' => 16,
			'27' => 32,
			'26' => 64,
			'25' => 128,
			'24' => 256);
		$IPCount = $cidr[$parts[1]];
		$start = 0;
		$end = 0;
		$deletedCount = 0;
		$notFoundCount = 0;
		$notDeletedCount = 0;
		for ($i = 0; $i < (256 / $IPCount); $i++) {
			if (($i * $IPCount) <= intval($IPParts[3]) && (intval($IPParts[3]) < ($i + 1) * $IPCount)) {
				$start = $i * $IPCount;
				$end = (($i + 1) * $IPCount) - 1;
			} 
		}
		$startIP = $IPParts[0].'.'.$IPParts[1].'.'.$IPParts[2].'.'.$start;
		$endIP = $IPParts[0].'.'.$IPParts[1].'.'.$IPParts[2].'.'.$end;
		for ($i = $start + 1; $i <= $end - 1; $i++) {
			$ipaddress = $IPParts[0].'.'.$IPParts[1].'.'.$IPParts[2].'.'.$i;
			log_message('info', $ipaddress);
			$found = $this->ipaddress->ipExists($ipaddress, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			if (!$found) {
				$notFoundCount++;
			} else {
				$isFree = $this->ipaddress->isFree($ipaddress, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				if ($isFree) {
					$this->ipaddress->delete($ipaddress, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if ($sysuser != $this->SUPERUSER) {
						$this->sysuseractivitylog->logIpAddressDeletion($ipaddress, $sysuser, $sysuserIP, time()); //log ip address deletion
					}
					$deletedCount++;
				} else {
					$notDeletedCount++;
				}
			}
		}
		$notDeletedMsg = '';
		if ($notDeletedCount == 1) {
			$notDeletedMsg = '<br />1 address was not deleted because it was marked as used.';
		} else if ($notDeletedCount > 1) {
			$notDeletedMsg = '<br />'.$notDeletedCount.' addresses were not deleted because it was marked as used.';
		}
		$notFoundMsg = '';
		if ($notFoundCount == 1) {
			$notFoundMsg = '<br />1 address was not in IP address pool.';
		} else if ($notFoundCount > 1) {
			$notFoundMsg = '<br />'.$notFoundCount.' addresses were not in IP address pool.';
		}
		$data['message'] = 'Deleted all unused IP addresses matching '.$enteredIP.' ('.$deletedCount.').'.$notDeletedMsg.$notFoundMsg;
		$this->load->view('manage_ip_addresses_delete_range', $data);
	}
	public function processBulkIpaddressCreation($step = null) {
		$this->redirectIfNoAccess('Manage IP Addresses', 'main/processBulkIpaddressCreation');
		if (is_null($step)) { //via form
			$step = $this->input->post('step');
			$realm = '';
			if ($step == 'upload') {
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx';
    			$config['max_size'] = '50000';
    			$this->load->library('upload', $config);
    			if (!$this->upload->do_upload('file')) { //upload fail
    				$data['step'] = 'upload';
    				$data['error'] = 'Upload failed: '.$this->upload->display_errors();
    				$this->load->view('manage_ip_addresses_bulk_create', $data);
    			} else { //upload ok
					$this->load->model('util');
    				$uploaded = $this->upload->data();
    				log_message('info', 'UPLOADED FILE: '.json_encode($uploaded));
    				$valid = $this->util->verifyBulkCreateIPFormat($uploaded['full_path']);
    				if (!$valid) {
    					$data['step'] = 'upload';
    					$data['error'] = 'Invalid file contents';
    					$data['realm'] = $realm;
    					$this->load->view('manage_ip_addresses_bulk_create', $data);
    				} else {
    					$rows = $this->util->verifyBulkCreateIPData($uploaded['full_path'], $realm);
    					log_message('info', 'ROWS: '.json_encode($rows));
	    				$data = array(
	    					'step' => 'confirm',
	    					'path' => $uploaded['full_path'],
	    					'valid' => $rows['valid'],
	    					'vaildRowNumbers' => $rows['validRowNumbers'],
	    					'invalid' => $rows['invalid'],
	    					'invalidRowNumbers' => $rows['invalidRowNumbers']);
	    				$this->load->view('manage_ip_addresses_bulk_create', $data);
    				}
    			}
			} else if ($step == 'create') {
				$now = time();
				$path = $this->input->post('path');
				$validRowNumbers = unserialize($this->input->post('validRowNumbers'));
				$invalidRowNumbers = unserialize($this->input->post('invalidRowNumbers'));
				log_message('info', 'VALID ROWS: '.json_encode($validRowNumbers));
				log_message('info', 'INVALID ROWS: '.json_encode($invalidRowNumbers));
				$createdRows = [];
				$notCreatedInvalidIP = [];
				$notCreatedExistingIP = [];
				$notCreatedInvalidLocation = [];
				$this->load->model('util');
				$this->load->model('ipaddress');
				//$locations = $this->ipaddress->LOCATIONS;
				$locations = $this->ipaddress->fetchAllLocationsFromExtras();
				$IPsToCreate = $this->util->extractRowsToCreateIP($path, $validRowNumbers);
				log_message('info', 'TO CREATE: '.json_encode($IPsToCreate));
				/*************************************************
				 * get this from session variable
				 *************************************************/
				$sysuser = $this->session->userdata('username');
				$sysuserIP = $this->session->userdata('ip_address');

				$this->load->model('sysuseractivitylog');
				$ipAddressCount = count($IPsToCreate);
				for ($i = 0; $i < $ipAddressCount; $i++) {
					$ipaddress = $this->ipaddress->rowDataToIPArray($IPsToCreate[$i]);
					$ipValid = $this->ipaddress->isIPValid($ipaddress['IPADDRESS']);
					if (!$ipValid) {
						log_message('info', $i.'|invalid ip: '.$ipaddress['IPADDRESS']);
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedInvalidIP[] = $IPsToCreate[$i];
						continue;
					}
					$exists = $this->ipaddress->ipExists($ipaddress['IPADDRESS'], $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if ($exists) {
						log_message('info', $i.'|ip exists: '.$ipaddress['IPADDRESS']);
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedExistingIP[] = $IPsToCreate[$i];
						continue;
					}
					$locationValid = false;
					for ($j = 0; $j < count($locations); $j++) {
						if (strcmp(strtolower($locations[$j]), strtolower($ipaddress['LOCATION'])) == 0) {
							$locationValid = true;
							break;
						}
					}
					if (!$locationValid) {
						log_message('info', $i.'|invalid location: '.$ipaddress['IPADDRESS'].', '.$ipaddress['LOCATION']);
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedInvalidLocation[] = $IPsToCreate[$i];
						continue;
					}
					$created = $this->ipaddress->create($ipaddress['IPADDRESS'], $ipaddress['LOCATION'], $ipaddress['GPONIP'], $ipaddress['DESCRIPTION'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					$createdRows[] = $IPsToCreate[$i];
					if ($sysuser != $this->SUPERUSER) {
						$this->sysuseractivitylog->logIpAddressCreation($ipaddress['IPADDRESS'], $sysuser, $sysuserIP, $now);
					}
				}
				log_message('info', '__________________________________________________________________________________');
				log_message('info', 'created|'.json_encode($createdRows));
				log_message('info', 'invalidIP|'.json_encode($notCreatedInvalidIP));
				log_message('info', 'existingIP|'.json_encode($notCreatedExistingIP));
				log_message('info', 'invalidLocation|'.json_encode($notCreatedInvalidLocation));
				log_message('info', 'invalidRows|'.json_encode($invalidRowNumbers));
				$data = array(
					'step' => 'create',
					'created' => $createdRows,
					'invalidIP' => $notCreatedInvalidIP,
					'existingIP' => $notCreatedExistingIP,
					'invalidLocation' => $notCreatedInvalidLocation,
					'invalidRowNumbers' => $invalidRowNumbers);
				$this->load->view('manage_ip_addresses_bulk_create', $data);
			} else if ($step == 'download') {
				echo 'download';
			}
		} else { //via link
			$this->load->view('manage_ip_addresses_bulk_create');
		}
	}
	public function freeupIpaddressProcess() {
		if ($this->input->is_ajax_request()) {
			$now = date('Y-m-d H:i:s', time());
			/***********************************************
			 * get these from session variables
			 ***********************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$this->load->model('ipaddress');
			$this->load->model('netaddress');
			$this->load->model('subscribermain');
			$this->load->model('sysuseractivitylog');
			$this->load->model('subscriberaudittrail');
			$ip = $this->input->post('ip');
			$username = $this->input->post('username');
			if ($this->ipaddress->freeUp($ip, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA)) {
				$subscriberOld = $this->subscribermain->findByUserIdentity($username);
				$subscriber = array(
					'USER_IDENTITY' => $subscriberOld['USER_IDENTITY'],
					'USERNAME' => $subscriberOld['USERNAME'],
					'BANDWIDTH' => $subscriberOld['BANDWIDTH'],
					'CUSTOMERSTATUS' => $subscriberOld['CUSTOMERSTATUS'],
					'PASSWORD' => $subscriberOld['PASSWORD'],
					'CUSTOMERREPLYITEM' => $subscriberOld['CUSTOMERREPLYITEM'],
					'CREATEDATE' => $subscriberOld['CREATEDATE'],
					'LASTMODIFIEDDATE' => $now,
					'RBCUSTOMERNAME' => $subscriberOld['RBCUSTOMERNAME'],
					'RBCREATEDBY' => $subscriberOld['RBCREATEDBY'],
					'RBADDITIONALSERVICE5' => $subscriberOld['RBADDITIONALSERVICE5'],
					'RBADDITIONALSERVICE4' => $subscriberOld['RBADDITIONALSERVICE4'],
					'RBADDITIONALSERVICE3' => $subscriberOld['RBADDITIONALSERVICE3'],
					'RBADDITIONALSERVICE2' => $subscriberOld['RBADDITIONALSERVICE2'],
					'RBADDITIONALSERVICE1' => $subscriberOld['RBADDITIONALSERVICE1'],
					'RBCHANGESTATUSDATE' => $subscriberOld['RBCHANGESTATUSDATE'],
					'RBCHANGESTATUSBY' => $subscriberOld['RBCHANGESTATUSBY'],
					'RBACTIVATEDDATE' => $subscriberOld['RBACTIVATEDDATE'],
					'RBACTIVATEDBY' => $subscriberOld['RBACTIVATEDBY'],
					'RBACCOUNTSTATUS' => $subscriberOld['RBACCOUNTSTATUS'],
					'RBSVCCODE2' => $subscriberOld['RBSVCCODE2'],
					'RBACCOUNTPLAN' => $subscriberOld['RBACCOUNTPLAN'],
					'CUSTOMERTYPE' => $subscriberOld['CUSTOMERTYPE'],
					'RBSERVICENUMBER' => $subscriberOld['RBSERVICENUMBER'], //should not change
					'RBCHANGESTATUSFROM' => $subscriberOld['CUSTOMERSTATUS'],
					'RBSECONDARYACCOUNT' => $subscriberOld['RBSECONDARYACCOUNT'],
					'RBUNLIMITEDACCESS' => $subscriberOld['RBUNLIMITEDACCESS'],
					'RBTIMESLOT' => $subscriberOld['RBTIMESLOT'],
					'RBORDERNUMBER' => $subscriberOld['RBORDERNUMBER'],
					'RBREMARKS' => $subscriberOld['RBREMARKS'],
					'RBREALM' => $subscriberOld['RBREALM'], //should not change
					'RBNUMBEROFSESSION' => $subscriberOld['RBNUMBEROFSESSION'],
					'RBMULTISTATIC' => null,
					'RBIPADDRESS' => null,
					'RBENABLED' => $subscriberOld['RBENABLED']);
				if (!is_null($subscriber['RBMULTISTATIC'])) {
					$this->netaddress->freeUp($subscriberOld['RBMULTISTATIC'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if ($sysuser != $this->SUPERUSER) {
						$this->sysuseractivitylog->logNetAddressFreeup($subscriberOld['RBMULTISTATIC'], $sysuser, $sysuserIP, time());
					}
				}
				$this->subscribermain->update($subscriber['USER_IDENTITY'], $subscriber,
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				if ($sysuser != $this->SUPERUSER) {
					$this->sysuseractivitylog->logIpAddressFreeup($ip, $sysuser, $sysuserIP, time());
					$this->subscriberaudittrail->logSubscriberModification($subscriber, $subscriberOld, $sysuser, $sysuserIP, $now, true);
				}
				$result = array(
					'status' => '1',
					'time' => $now);
				echo json_encode($result);
			} else {
				echo json_encode(array('status' => '0'));
			}
		} else {
			redirect('main/showIpaddressesIndex/1');
		}
	}
	public function deleteIpaddressProcess() {
		if ($this->input->is_ajax_request()) {
			/***********************************************
			 * get these from session variables
			 ***********************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$this->load->model('ipaddress');
			$ip = $this->input->post('ip');
			if ($this->ipaddress->delete($ip, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA)) {
				if ($sysuser != $this->SUPERUSER) {
					$this->load->model('sysuseractivitylog');
					$this->sysuseractivitylog->logIpAddressDeletion($ip, $sysuser, $sysuserIP, time());
				}
				echo json_encode(array('status' => '1'));
			} else {
				echo json_encode(array('status' => '0'));
			}
		} else {
			redirect('main/showIpaddressesIndex/1');
		}
	}
	/**************************************************
	 * added february 2017
	 * - for IPv6
	 **************************************************/
	public function showIpV6AddressesIndex($link = 0, $order = null, $ipaddress = null, $username = null, $location = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Manage IP Addresses', 'main/showIpV6AddressesIndex');
		$realm = $this->session->userdata('realm');
		$submit = ''; 
		$pages = 0;
		if (intval($link) == 0) { //via form
			log_message('info', 'via IPv6 form');
			$location = $this->input->post('location');
			$ipaddress = $this->input->post('ipaddress');
			$username = $this->input->post('username');
			$location = $location == '' ? null : $location;
			$ipaddress = trim($ipaddress) == '' ? null : trim($ipaddress);
			$username = trim($username) == '' ? null : trim($username);
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$submit = $this->input->post('btnRefresh');
			$start = $submit == 'refresh' ? 0 : $start;
			$order = $this->input->post('order');
			$orderParts = explode('-', $order);
			$order = array('column' => $orderParts[0], 'dir' => $orderParts[1]);
		} else { //via link
			log_message('info', 'via IPv6 link');
			if ($order == null) {
				$order = array('column' => 'IPV6USED', 'dir' => 'asc');
			} else {
				$parts = explode('-', $order);
				$order = array('column' => $parts[0], 'dir' => $parts[1]);
			}
			$location = is_null($location) ? $location : ($location == 'null' ? null : str_replace('_', ' ', $location));
			$ipaddress = is_null($ipaddress) ? $ipaddress : ($ipaddress == 'null' ? null : str_replace('_', ' ', $ipaddress));
			if (!is_null($username)) {
				if ($username == 'null') {
					$username = null;
				} else {
					$username = str_replace('_', ' ', $username);
					$username = str_replace('~', '*', $username);
					$username = str_replace('%', '@', $username);
				}
			}
			if (!is_null($ipaddress)) {
				$ipaddress = str_replace('~', '*', $ipaddress);
			}
			log_message('debug', 'order:'.json_encode($order));
			log_message('debug', 'location:'.$location);
			log_message('debug', 'username:'.$username);
		}
		$ipaddressStr = $ipaddress;
		$exact = strpos($ipaddress, '*') === false ? true : false;
		$wildcard = 'both';
		if (!$exact) {
			if (strpos($ipaddress, '*') == 0) {
				$wildcard = 'before';
			} else if (strpos($ipaddress, '*') == (strlen($ipaddress) - 1)) {
				$wildcard = 'after';
			}
			$ipaddress = substr($ipaddress, 0, strpos($ipaddress, '*'));
		}
		$this->load->model('ipaddress');
		$locations = $this->ipaddress->fetchAllLocationsFromExtras();
		$cn = is_null($username) ? null : $username;
		$ipaddresses = $this->ipaddress->fetchAllV6($ipaddress, $location, null, $cn, $exact, $wildcard, null, $start, $max, $order);
		$count = $this->ipaddress->countIPV6Addresses($ipaddress, $location, null, $cn, $exact, $wildcard, null);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		log_message('info', 'ORDER|'.json_encode($order));
		log_message('info', 'LOCATION|'.json_encode($location));
		log_message('info', 'IP|'.json_encode($ipaddress));
		log_message('info', 'USERNAME|'.json_encode($username));
		log_message('info', 'START|'.json_encode($start));
		log_message('info', 'MAX|'.json_encode($max));
		log_message('info', 'IPS|'.json_encode($ipaddresses));
		log_message('info', 'PAGES|'.json_encode($pages));
		log_message('info', 'COUNT|'.json_encode($count));
		$data = array(
			'ipaddresses' => $ipaddresses,
			'count' => $count,
			'start' => $start,
			'max' => $max,
			'pages' => $pages,
			'order' => $order,
			'locations' => $locations,
			'location' => $location,
			'ipaddress' => $ipaddressStr,
			'username' => $username);
		$this->load->view('manage_ipv6_addresses', $data);
	}
	public function createIpV6AddressForm() {
		$this->redirectIfNoAccess('Manage IP Addresses', 'main/createIpV6AddressForm');
		$this->load->model('ipaddress');
		$locations = $this->ipaddress->fetchAllLocationsFromExtras();
		$set = false;
		$location = $set ? 'VALERO' : '';
		$data = array(
			'locations' => $locations,
			'location' => $location,
			'set' => $set);
		$this->load->view('manage_ipv6_addresses_create', $data);
	}
	public function createIpV6AddressProcess() {
		$this->redirectIfNoAccess('Manage IP Addresses', 'main/createIpV6AddressProcess');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('ipaddress');
		$locations = $this->ipaddress->fetchAllLocationsFromExtras();
		$set = intval($this->input->post('set')) == 1 ? true : false;
		$location = $this->input->post('location');
		$ipaddress = $this->input->post('ipaddress');
		$data = array(
			'locations' => $locations,
			'location' => $location,
			'set' => $set,
			'ipaddress' => $ipaddress);
		$valid = $this->ipaddress->isIPV6Valid($ipaddress);
		if (!$valid) {
			$data['error'] = 'The IPv6 address is invalid.';
			$this->load->view('manage_ipv6_addresses_create', $data);
			return;
		}
		$exists = $this->ipaddress->ipV6Exists($ipaddress, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
		if ($exists) {
			$data['error'] = 'The IPv6 address already exists.';
			$this->load->view('manage_ipv6_addresses_create', $data);
			return;	
		}
		/************************************************
		 * can create ip address
		 ************************************************/
		$created = $this->ipaddress->createV6($ipaddress, $location, '', 
			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
		if (!$created) {
			$data['error'] = 'Failed to create IPv6 address.';
			$this->load->view('manage_ipv6_addresses_create', $data);
			return;
		}
		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('sysuseractivitylog');
			$this->sysuseractivitylog->logIpV6AddressCreation($ipaddress, $sysuser, $sysuserIP, time()); //log ipaddress creation
		}
		$data['location'] = $set ? $location : '';
		$data['ipaddress'] = '';
		$data['message'] = 'Created IPv6 address '.$ipaddress.' for '.$location;
		$this->load->view('manage_ipv6_addresses_create', $data);
	}
	public function processBulkIpV6AddressCreation($step = null) {
		$this->redirectIfNoAccess('Manage IP Addresses', 'main/processBulkIpV6AddressCreation');
		if (is_null($step)) { //via form
			$step = $this->input->post('step');
			$realm = '';
			if ($step == 'upload') {
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx';
				$config['max_size'] = '50000';
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('file')) { //upload fail
					$data['step'] = 'upload';
					$data['error'] = 'Upload failed: '.$this->upload->display_errors();
					$this->load->view('manage_ipv6_addresses_bulk_create', $data);
				} else { //upload ok
					$this->load->model('util');
					$uploaded = $this->upload->data();
					log_message('info', 'UPLOADED FILE: '.json_encode($uploaded));
					$valid = $this->util->verifyBulkCreateIPv6Format($uploaded['full_path']);
					if (!$valid) {
						$data['step'] = 'upload';
						$data['error'] = 'Invalid file contents';
						$data['realm'] = $realm;
						$this->load->view('manage_ipv6_addresses_bulk_create', $data);
					} else {
						$rows = $this->util->verifyBulkCreateIPv6Data($uploaded['full_path'], $realm);
						log_message('info', 'ROWS: '.json_encode($rows));
						$data = array(
							'step' => 'confirm',
							'path' => $uploaded['full_path'],
							'valid' => $rows['valid'],
							'vaildRowNumbers' => $rows['validRowNumbers'],
							'invalid' => $rows['invalid'],
							'invalidRowNumbers' => $rows['invalidRowNumbers']);
						$this->load->view('manage_ipv6_addresses_bulk_create', $data);
					}
				}
			} else if ($step == 'create') {
				$now = time();
				$path = $this->input->post('path');
				$validRowNumbers = unserialize($this->input->post('validRowNumbers'));
				$invalidRowNumbers = unserialize($this->input->post('invalidRowNumbers'));
				log_message('info', 'VALID ROWS: '.json_encode($validRowNumbers));
				log_message('info', 'INVALID ROWS: '.json_encode($invalidRowNumbers));
				$createdRows = [];
				$notCreatedInvalidIP = [];
				$notCreatedExistingIP = [];
				$notCreatedInvalidLocation = [];
				$this->load->model('util');
				$this->load->model('ipaddress');
				//$locations = $this->ipaddress->LOCATIONS;
				$locations = $this->ipaddress->fetchAllLocationsFromExtras();
				$IPsToCreate = $this->util->extractRowsToCreateIP($path, $validRowNumbers);
				log_message('info', 'TO CREATE: '.json_encode($IPsToCreate));
				/*************************************************
				 * get this from session variable
				 *************************************************/
				$sysuser = $this->session->userdata('username');
				$sysuserIP = $this->session->userdata('ip_address');

				$this->load->model('sysuseractivitylog');
				$ipAddressCount = count($IPsToCreate);
				for ($i = 0; $i < $ipAddressCount; $i++) {
					$ipAddress = $this->ipaddress->rowDataToIPArrayForV6($IPsToCreate[$i]);
					$ipv6Valid = $this->ipaddress->isIPV6Valid($ipAddress['IPV6ADDR']);
					if (!$ipv6Valid) {
						log_message('info', $i.'|invalid ipv6: '.$ipAddress['IPV6ADDR']);
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedInvalidIP[] = $IPsToCreate[$i];
						continue;
					}
					$exists = $this->ipaddress->ipV6Exists($ipAddress['IPV6ADDR'], 
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if ($exists) {
						log_message('info', $i.'|ip exists: '.$ipAddress['IPV6ADDR']);
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedExistingIP[] = $IPsToCreate[$i];
						continue;
					}
					$locationValid = false;
					for ($j = 0; $j < count($locations); $j++) {
						if (strcmp(strtolower($locations[$j]), strtolower($ipAddress['LOCATION'])) == 0) {
							$locationValid = true;
							break;
						}
					}
					if (!$locationValid) {
						log_message('info', $i.'|invalid location: '.$ipAddress['IPV6ADDR'].', '.$ipAddress['LOCATION']);
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedInvalidLocation[] = $IPsToCreate[$i];
						continue;
					}
					$created = $this->ipaddress->createV6($ipAddress['IPV6ADDR'], $ipAddress['LOCATION'], $ipAddress['DESCRIPTION'], 
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					$createdRows[] = $IPsToCreate[$i];
					if ($sysuser != $this->SUPERUSER) {
						$this->sysuseractivitylog->logIpV6AddressCreation($ipAddress['IPV6ADDR'], $sysuser, $sysuserIP, $now);
					}
				}
				log_message('info', '__________________________________________________________________________________');
				log_message('info', 'created|'.json_encode($createdRows));
				log_message('info', 'invalidIP|'.json_encode($notCreatedInvalidIP));
				log_message('info', 'existingIP|'.json_encode($notCreatedExistingIP));
				log_message('info', 'invalidLocation|'.json_encode($notCreatedInvalidLocation));
				log_message('info', 'invalidRows|'.json_encode($invalidRowNumbers));
				$data = array(
					'step' => 'create',
					'created' => $createdRows,
					'invalidIP' => $notCreatedInvalidIP,
					'existingIP' => $notCreatedExistingIP,
					'invalidLocation' => $notCreatedInvalidLocation,
					'invalidRowNumbers' => $invalidRowNumbers);
				$this->load->view('manage_ipv6_addresses_bulk_create', $data);
			} else if ($step == 'download') {
				echo 'download';
			}
		} else { //via link
			$this->load->view('manage_ipv6_addresses_bulk_create');
		}
	}
	public function freeupIpV6AddressProcess() {
		if ($this->input->is_ajax_request()) {
			$now = date('Y-m-d H:i:s', time());
			/***********************************************
			 * get these from session variables
			 ***********************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$this->load->model('ipaddress');
			$this->load->model('netaddress');
			$this->load->model('subscribermain');
			$this->load->model('sysuseractivitylog');
			$this->load->model('subscriberaudittrail');
			$ip = $this->input->post('ip');
			$username = $this->input->post('username');
			if ($this->ipaddress->freeUpV6($ip, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA)) {
				$subscriberOld = $this->subscribermain->findByUserIdentity($username);
				$subscriber = array(
					'USER_IDENTITY' => $subscriberOld['USER_IDENTITY'],
					'USERNAME' => $subscriberOld['USERNAME'],
					'BANDWIDTH' => $subscriberOld['BANDWIDTH'],
					'CUSTOMERSTATUS' => $subscriberOld['CUSTOMERSTATUS'],
					'PASSWORD' => $subscriberOld['PASSWORD'],
					'CUSTOMERREPLYITEM' => $subscriberOld['CUSTOMERREPLYITEM'],
					'CREATEDATE' => $subscriberOld['CREATEDATE'],
					'LASTMODIFIEDDATE' => $now,
					'RBCUSTOMERNAME' => $subscriberOld['RBCUSTOMERNAME'],
					'RBCREATEDBY' => $subscriberOld['RBCREATEDBY'],
					'RBADDITIONALSERVICE5' => $subscriberOld['RBADDITIONALSERVICE5'],
					'RBADDITIONALSERVICE4' => null, //placeholder for ipv6 address
					'RBADDITIONALSERVICE3' => $subscriberOld['RBADDITIONALSERVICE3'],
					'RBADDITIONALSERVICE2' => $subscriberOld['RBADDITIONALSERVICE2'],
					'RBADDITIONALSERVICE1' => $subscriberOld['RBADDITIONALSERVICE1'],
					'RBCHANGESTATUSDATE' => $subscriberOld['RBCHANGESTATUSDATE'],
					'RBCHANGESTATUSBY' => $subscriberOld['RBCHANGESTATUSBY'],
					'RBACTIVATEDDATE' => $subscriberOld['RBACTIVATEDDATE'],
					'RBACTIVATEDBY' => $subscriberOld['RBACTIVATEDBY'],
					'RBACCOUNTSTATUS' => $subscriberOld['RBACCOUNTSTATUS'],
					'RBSVCCODE2' => $subscriberOld['RBSVCCODE2'],
					'RBACCOUNTPLAN' => $subscriberOld['RBACCOUNTPLAN'],
					'CUSTOMERTYPE' => $subscriberOld['CUSTOMERTYPE'],
					'RBSERVICENUMBER' => $subscriberOld['RBSERVICENUMBER'], //should not change
					'RBCHANGESTATUSFROM' => $subscriberOld['CUSTOMERSTATUS'],
					'RBSECONDARYACCOUNT' => $subscriberOld['RBSECONDARYACCOUNT'],
					'RBUNLIMITEDACCESS' => $subscriberOld['RBUNLIMITEDACCESS'],
					'RBTIMESLOT' => $subscriberOld['RBTIMESLOT'],
					'RBORDERNUMBER' => $subscriberOld['RBORDERNUMBER'],
					'RBREMARKS' => $subscriberOld['RBREMARKS'],
					'RBREALM' => $subscriberOld['RBREALM'], //should not change
					'RBNUMBEROFSESSION' => $subscriberOld['RBNUMBEROFSESSION'],
					'RBMULTISTATIC' => $subscriberOld['RBMULTISTATIC'],
					'RBIPADDRESS' => $subscriberOld['RBIPADDRESS'],
					'RBENABLED' => $subscriberOld['RBENABLED']);
				$this->subscribermain->update($subscriber['USER_IDENTITY'], $subscriber, 
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				if ($sysuser != $this->SUPERUSER) {
					$this->sysuseractivitylog->logIpV6AddressFreeup($ip, $sysuser, $sysuserIP, time());
					$this->subscriberaudittrail->logSubscriberModification($subscriber, $subscriberOld, $sysuser, $sysuserIP, $now, true);
				}
				$result = array(
					'status' => '1',
					'time' => $now);
				echo json_encode($result);
			} else {
				echo json_encode(array('status' => '0'));
			}
		} else {
			redirect('main/showIpV6AddressesIndex/1');
		}
	}
	public function deleteIpV6AddressProcess() {
		if ($this->input->is_ajax_request()) {
			/***********************************************
			 * get these from session variables
			 ***********************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$this->load->model('ipaddress');
			$ip = $this->input->post('ip');
			if ($this->ipaddress->deleteV6($ip, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA)) {
				if ($sysuser != $this->SUPERUSER) {
					$this->load->model('sysuseractivitylog');
					$this->sysuseractivitylog->logIpV6AddressDeletion($ip, $sysuser, $sysuserIP, time());
				}
				echo json_encode(array('status' => '1'));
			} else {
				echo json_encode(array('status' => '0'));
			}
		} else {
			redirect('main/showIpV6AddressesIndex/1');
		}
	}
	/**************************************************
	 * /added february 2017
	 **************************************************/
	public function bulkIPSeedingProcess($step = null) {
		$this->redirectIfNoAccess('Manage IP Addresses', 'main/bulkIPSeedingProcess');
		$IdentifierAndLocation = array(
			'TLC_BNG-001' => 'North Luzon', 
			'SJN_BNG-001' => 'North NCR',
			'VAL_BNG-001' => 'South NCR',
			'NSB_BNG-001' => 'South Luzon',
			'LHG_BNG-001' => 'Visayas',
			'CDO_BNG-001' => 'Mindanao');
		$plansFile = '/webutil/web_files/policy/plans.txt';
		if (is_null($step)) { //via form
			$step = $this->input->post('step');
			if ($step == 'upload') {
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx';
    			$config['max_size'] = '50000';
    			$this->load->library('upload', $config);
    			if (!$this->upload->do_upload('file')) { //upload fail
    				$data['step'] = 'upload';
    				$data['error'] = 'Upload failed: '.$this->upload->display_errors();
    				$this->load->view('manage_ip_address_bulk_ip_seed', $data);
    			} else { //upload ok
    				$this->load->model('util');
    				$uploaded = $this->upload->data();
    				log_message('info', 'UPLOADED FILE: '.json_encode($uploaded));
    				$valid = $this->util->verifyBulkIPSeedingFormat($uploaded['full_path']);
    				if (!$valid) {
    					$data['step'] = 'upload';
    					$data['error'] = 'Invalid file contents';
    					$this->load->view('manage_ip_address_bulk_ip_seed', $data);
    				} else {
    					/*************************************************
						 * check existence of file that contains plans to consider
						 *************************************************/
    					$fh = fopen($plansFile, 'r');
						if ($fh === false) {
							$data['step'] = 'upload';
							$data['error'] = 'File that has list of plans cannot be found.';
							$this->load->view('manage_ip_address_bulk_ip_seed', $data);
						} else {
							$plansFromFile = array();
							while(($buffer = fgets($fh, 4096)) !== false) {
								$plansFromFile[] = trim($buffer);
							}
							if (count($plansFromFile) == 0) {
								$data['step'] = 'upload';
								$data['error'] = 'File that has list of plans is empty.';
								$this->load->view('manage_ip_address_bulk_ip_seed', $data);
							} else {
								$rows = $this->util->verifyBulkIPSeedingData($uploaded['full_path']);
		    					log_message('info', 'ROWS: '.json_encode($rows));
		    					$data = array(
									'step' => 'confirm',
									'path' => $uploaded['full_path'],
									'valid' => $rows['valid'],
									'vaildRowNumbers' => $rows['validRowNumbers'],
									'invalid' => $rows['invalid'],
									'invalidRowNumbers' => $rows['invalidRowNumbers']);
								$this->load->view('manage_ip_address_bulk_ip_seed', $data);
							}
						}
    				}
    			}
			} else if ($step == 'create') {
				$now = time();
				$path = $this->input->post('path');
				$validRowNumbers = unserialize($this->input->post('validRowNumbers'));
				$invalidRowNumbers = unserialize($this->input->post('invalidRowNumbers'));
				log_message('info', 'VALID ROWS: '.json_encode($validRowNumbers));
				log_message('info', 'INVALID ROWS: '.json_encode($invalidRowNumbers));
				$processedRows = array();
				$notProcessedInvalidIP = array();
				$notProcessedIPDNE = array();
				$notProcessedIPUsed = array();
				$notProcessedInvalidLocation = array();
				$notProcessedError = array();
				$this->load->model('util');
				$this->load->model('ipaddress');
				$IPsToUpdate = $this->util->extractRowsToReserve($path, $validRowNumbers);
				log_message('info', 'IPsToUpdate: '.json_encode($IPsToUpdate));
				$commonNASIdentifier = '';

				/*************************************************
				 * get this from session variable
				 *************************************************/
				$sysuser = $this->session->userdata('username');
				$sysuserIP = $this->session->userdata('ip_address');

				$this->load->model('sysuseractivitylog');
				$rowsToUpdate = array();
				for ($i = 0; $i < count($IPsToUpdate); $i++) {
					$row = $IPsToUpdate[$i];
					$theIP = $row[0];
					$theLocation = $row[1];
					$locationCode = '';
					$isValidIP = $this->ipaddress->isIPValid($theIP);
					if (!$isValidIP) {
						log_message('info', $i.'|invalid ip');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						unset($validRowNumbers[$i]);
						$notProcessedInvalidIP[] = $row;
						continue;
					}			
					$ipExists = $this->ipaddress->ipExists($theIP, 
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);	
					if (!$ipExists) {
						log_message('info', $i.'|ip dne');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						unset($validRowNumbers[$i]);
						$notProcessedIPDNE[] = $row;
						continue;
					}
					$ipFree = $this->ipaddress->isFree($theIP, 
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					log_message('info', $theIP.':'.json_encode($ipFree));
					if (!$ipFree) {
						log_message('info', $i.'|ip used');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						unset($validRowNumbers[$i]);
						$notProcessedIPUsed[] = $row;
						continue;
					}
					$locationValid = false;
					foreach ($IdentifierAndLocation as $locationKey => $locationValue) {
						if (trim($theLocation) == trim($locationValue)) {
							$locationValid = true;
							$locationCode = $locationKey;
							break;
						}
					}
					if (!$locationValid) {
						log_message('info', $i.'|invalid location');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						unset($validRowNumbers[$i]);
						$notProcessedInvalidLocation[] = $row;
						continue;
					} else {
						$row[] = $locationKey;
					}
					$rowsToUpdate[] = $row;
				}
				$validRowNumbers = array_values($validRowNumbers);
				log_message('info', 'ROWS TO UPDATE: '.json_encode($rowsToUpdate));
				log_message('info', 'VALIDROWNUMBERS: '.json_encode($validRowNumbers));

				/*************************************************
				 * fetch list of plans to consider
				 *************************************************/
				$fh = fopen($plansFile, 'r');
				$plansFromFile = array();
				while(($buffer = fgets($fh, 4096)) !== false) {
					$plansFromFile[] = trim($buffer);
				}
				log_message('info', 'plans@file:'.json_encode($plansFromFile));
				$this->load->model('onlinesession');
				$this->load->model('subscribermain');
				$m = 0;

				/*************************************************
				 * old implementation
				 *************************************************
				for ($i = 0; $i < count($rowsToUpdate); $i++) {
					$subsOk = false;
					$noMoreSubs = false;
					$subscriber = null;
					while (!$subsOk) {
						$account = $this->onlinesession->getRandomUsersWithSession($rowsToUpdate[$i][3], $m, 
							$this->SESSIONUSERNAME2, $this->SESSIONPASSWORD2, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// $account = $accounts[$m];
						log_message('info', 'm:'.$m.'|account:'.json_encode($account));
						if (is_null($account) || $account === false) {
							$noMoreSubs = true;
							break;
						}
						$subscriber = $this->subscribermain->findByUserIdentity($account['USER_NAME'], 
							$this->SESSIONUSERNAME2, $this->SESSIONPASSWORD2, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						//check if $subscriber['RADIUSPOLICY'] is in file
						log_message('info', 'checking:'.$account['USER_NAME'].'|'.str_replace('~', '-', $subscriber['RADIUSPOLICY']));
						for ($x = 0; $x < count($plansFromFile); $x++) {
							if (trim($plansFromFile[$x]) == str_replace('~', '-', $subscriber['RADIUSPOLICY'])) {
								$subsOk = true;
								break;
							}
						}
						log_message('info', 'm:'.$m.'|subsOk:'.json_encode($subsOk).'|noMoreSubs:'.json_encode($noMoreSubs));
						$m++;
					}
					if (!$noMoreSubs) {
						$subscriberNew = array();
						foreach ($subscriber as $sKey => $sValue) {
							$subscriberNew[$sKey] = $sValue;
						}
						$subscriberNew['RBIPADDRESS'] = $rowsToUpdate[$i][0];
						if (!is_null($subscriber['RBMULTISTATIC']) && trim($subscriber['RBMULTISTATIC']) != '') {
							$subscriberNew['CUSTOMERREPLYITEM'] = '0:8='.$rowsToUpdate[$i][0].',0:22='.$subscriber['RBMULTISTATIC'].',4874:1=OUTSIDE';
						} else {
							$subscriberNew['CUSTOMERREPLYITEM'] = '0:8='.$rowsToUpdate[$i][0].',4874:1=OUTSIDE';
						}
						$result = $this->subscribermain->update($account['USER_NAME'], $subscriberNew, 
							$this->SESSIONUSERNAME2, $this->SESSIONPASSWORD2, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						if (!$result) {
							$notProcessedError[] = $rowsToUpdate[$i];
							continue;
						} else {
							//update ipaddresses
							log_message('info', $i.'|free up:'.$subscriber['RBIPADDRESS']);
							$this->ipaddress->freeUp($subscriber['RBIPADDRESS'], 
								$this->SESSIONUSERNAME2, $this->SESSIONPASSWORD2, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							log_message('info', $i.'|mark as used:'.$subscriberNew['RBIPADDRESS']);
							$this->ipaddress->markAsUsed($subscriberNew['RBIPADDRESS'], $account['USER_NAME'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D', 
								$this->SESSIONUSERNAME2, $this->SESSIONPASSWORD2, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							//delete sessions
							$rowsToUpdate[$i][] = $account['USER_NAME'];
							$rowsToUpdate[$i][] = $subscriber['RBIPADDRESS'];
							$processedRows[] = $rowsToUpdate[$i];
							$cnParts = explode('@', $account['USER_NAME']);
							$sessions = $this->onlinesession->getSessions($cnParts[0], isset($cnParts[1]) ? $cnParts[1] : '', 
								$this->USESESSIONTABLE2, $this->SESSIONHOST2, $this->SESSIONPORT2, $this->SESSIONSCHEMA2, $this->SESSIONUSERNAME2, $this->SESSIONPASSWORD2);
							if (isset($sessions['data'])) {
								for ($j = 0; $j < count($sessions['data']); $j++) {
									$sess = $sessions['data'][$j];
									log_message('info', $i.'|deleting session:'.json_encode($sess));
									$deleted = $this->onlinesession->requestCOAExt($sess['USER_NAME'], $this->AAASOAPHOST, $this->AAASOAPPORT, $this->USESESSIONTABLE2, $this->AAASOAPHOST2, 
										$this->AAASOAPPORT2, $sess['ACCT_SESSION_ID'], $sess['NAS_IP_ADDRESS'],
										$this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD,
										$this->SESSIONHOST2, $this->SESSIONPORT2, $this->SESSIONSCHEMA2, $this->SESSIONUSERNAME2, $this->SESSIONPASSWORD2,
										$this->DELETESCHEMA, $this->QMUSERNAME, $this->QMPASSWORD);
								}
							}
						}
					} else {
						$notProcessedError[] = $rowsToUpdate[$i];
					}
				}
				/*************************************************
				 * old implementation
				 *************************************************/

				/*************************************************
				 * ver2
				 *************************************************/
				$commonNASIdentifier = $rowsToUpdate[0][3]; //assuming they all have the same
				$rowsFromConcTable = $this->onlinesession->getRandomUsersWithSession2($commonNASIdentifier, $plansFromFile, count($rowsToUpdate), 
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				log_message('info', 'rowsFromConcTable:'.json_encode($rowsFromConcTable));
				if ($rowsFromConcTable === false) {
					$rowsFromConcTable = array();
				}
				for ($i = 0; $i < count($rowsFromConcTable); $i++) {
					$fromConc = $rowsFromConcTable[$i];
					$subscriber = $this->subscribermain->findByUserIdentity($fromConc['USER_NAME'], 
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					$subscriberNew = array();
					foreach ($subscriber as $sKey => $sValue) {
						$subscriberNew[$sKey] = $sValue;
					}
					$subscriberNew['RBIPADDRESS'] = $rowsToUpdate[$i][0];
					if (!is_null($subscriber['RBMULTISTATIC']) && trim($subscriber['RBMULTISTATIC']) != '') {
						$subscriberNew['CUSTOMERREPLYITEM'] = '0:8='.$rowsToUpdate[$i][0].',0:22='.$subscriber['RBMULTISTATIC'].',4874:1=OUTSIDE';
					} else {
						$subscriberNew['CUSTOMERREPLYITEM'] = '0:8='.$rowsToUpdate[$i][0].',4874:1=OUTSIDE';
					}
					$result = $this->subscribermain->update($fromConc['USER_NAME'], $subscriberNew, 
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if (!$result) {
						$notProcessedError[] = $rowsToUpdate[$i];
						continue;
					} else {
						//update ipaddresses
						log_message('info', $i.'|free up:'.$subscriber['RBIPADDRESS']);
						$this->ipaddress->freeUp($subscriber['RBIPADDRESS'], 
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						log_message('info', $i.'|mark as used:'.$subscriberNew['RBIPADDRESS']);
						$this->ipaddress->markAsUsed($subscriberNew['RBIPADDRESS'], $fromConc['USER_NAME'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D', 
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						//delete sessions
						$rowsToUpdate[$i][] = $fromConc['USER_NAME'];
						$rowsToUpdate[$i][] = $subscriber['RBIPADDRESS'];
						$processedRows[] = $rowsToUpdate[$i];
						$cnParts = explode('@', $fromConc['USER_NAME']);
						$sessions = $this->onlinesession->getSessions2($cnParts[0], isset($cnParts[1]) ? $cnParts[1] : '', $this->USESESSIONTABLE2,
							$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
							$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
						if (isset($sessions['data'])) {
							for ($j = 0; $j < count($sessions['data']); $j++) {
								$sess = $sessions['data'][$j];
								log_message('info', $i.'|deleting session:'.json_encode($sess));
								$deleted = $this->onlinesession->requestDisconnect($sess['USER_NAME'], $sess['NAS_IP_ADDRESS'], $sess['ACCT_SESSION_ID'], $this->USESESSIONTABLE2, 
									$this->DELETESESSIONAPIHOST, $this->DELETESESSIONAPIPORT, $this->DELETESESSIONAPISTUB,
									$this->DELETESESSIONAPIHOST2, $this->DELETESESSIONAPIPORT2, $this->DELETESESSIONAPISTUB2, 
									$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD, 
									$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2,
									$this->TBLMCOREHOST, $this->TBLMCOREPORT, $this->TBLMCORESCHEMA, $this->TBLMCOREUSERNAME, $this->TBLMCOREPASSWORD, 
									$this->TBLMCOREHOST2, $this->TBLMCOREPORT2, $this->TBLMCORESCHEMA2, $this->TBLMCOREUSERNAME2, $this->TBLMCOREPASSWORD2);
							}
						}
					}
				}
				/*************************************************
				 * ver2
				 *************************************************/
				$data = array(
					'step' => 'create',
					'processedRows' => $processedRows,
					'notProcessedInvalidIP' => $notProcessedInvalidIP,
					'notProcessedIPDNE' => $notProcessedIPDNE,
					'notProcessedIPUsed' => $notProcessedIPUsed,
					'notProcessedInvalidLocation' => $notProcessedInvalidLocation,
					'notProcessedError' => $notProcessedError);
				$this->load->view('manage_ip_address_bulk_ip_seed', $data);
			} else if ($step == 'download') {
				echo "download";
			}
		} else { //via link
			$this->load->view('manage_ip_address_bulk_ip_seed');
		}
	}
	public function bulkIPUnassignProcess($step = null) {
		$this->redirectIfNoAccess('Manage IP Addresses', 'main/bulkIPUnassignProcess');
		if (is_null($step)) { //via form
			$step = $this->input->post('step');
			if ($step == 'upload') {
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx';
				$config['max_size'] = '50000';
    			$this->load->library('upload', $config);
    			if (!$this->upload->do_upload('file')) { //upload fail
    				$data['step'] = 'upload';
					$data['error'] = 'Upload failed: '.$this->upload->display_errors();
					$this->load->view('manage_ip_address_bulk_ip_unassign', $data);
    			} else { //upload ok
    				$this->load->model('util');
					$uploaded = $this->upload->data();
					log_message('info', 'UPLOADED FILE: '.json_encode($uploaded));
					$valid = $this->util->verifyBulkIPUnassignFormat($uploaded['full_path']);
					if (!$valid) {
						$data['step'] = 'upload';
    					$data['error'] = 'Invalid file contents';
    					$this->load->view('manage_ip_address_bulk_ip_unassign', $data);
					} else {
						$rows = $this->util->verifyBulkIPUnassignData($uploaded['full_path']);
    					log_message('info', 'ROWS: '.json_encode($rows));
		    			$data = array(
							'step' => 'confirm',
							'path' => $uploaded['full_path'],
							'valid' => $rows['valid'],
							'vaildRowNumbers' => $rows['validRowNumbers'],
							'invalid' => $rows['invalid'],
							'invalidRowNumbers' => $rows['invalidRowNumbers']);
						$this->load->view('manage_ip_address_bulk_ip_unassign', $data);		
					}
    			}
			} else if ($step == 'create') {
				$now = time();
				$path = $this->input->post('path');
				$validRowNumbers = unserialize($this->input->post('validRowNumbers'));
				$invalidRowNumbers = unserialize($this->input->post('invalidRowNumbers'));
				log_message('info', 'VALID ROWS: '.json_encode($validRowNumbers));
				log_message('info', 'INVALID ROWS: '.json_encode($invalidRowNumbers));
				$processedRows = array();
				$notProcessedInvalidIP = array();
				$notProcessedIPDNE = array();
				$notProcessedIPUnused = array();
				$notProcessedInvalidLocation = array();
				$notProcessedError = array();
				$this->load->model('util');
				$this->load->model('ipaddress');
				$this->load->model('subscribermain');
				$this->load->model('netaddress');
				$this->load->model('onlinesession');
				$IPsToUpdate = $this->util->extractRowsToReserve($path, $validRowNumbers);
				log_message('info', 'IPsToUpdate: '.json_encode($IPsToUpdate));

				/*************************************************
				 * get this from session variable
				 *************************************************/
				$sysuser = $this->session->userdata('username');
				$sysuserIP = $this->session->userdata('ip_address');

				$this->load->model('sysuseractivitylog');
				$rowsToUpdate = array();
				for ($i = 0; $i < count($IPsToUpdate); $i++) {
					$row = $IPsToUpdate[$i];
					$theIP = $row[0];
					$theLocation = $row[1];
					$locationCode = '';
					$isValidIP = $this->ipaddress->isIPValid($theIP);
					if (!$isValidIP) {
						log_message('info', $i.'|invalid ip');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						unset($validRowNumbers[$i]);
						$notProcessedInvalidIP[] = $row;
						continue;
					}
					$ipExists = $this->ipaddress->ipExists($theIP, 
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if (!$ipExists) {
						log_message('info', $i.'|ip dne');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						unset($validRowNumbers[$i]);
						$notProcessedIPDNE[] = $row;
						continue;
					}
					$ipFree = $this->ipaddress->isFree($theIP, 
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					log_message('info', $theIP.':'.json_encode($ipFree));
					if ($ipFree) {
						log_message('info', $i.'|ip unused');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						unset($validRowNumbers[$i]);
						$notProcessedIPUnused[] = $row;
						continue;
					}
					$rowsToUpdate[] = $row;
				}
				$validRowNumbers = array_values($validRowNumbers);
				log_message('info', 'ROWS TO UPDATE: '.json_encode($rowsToUpdate));
				log_message('info', 'VALIDROWNUMBERS: '.json_encode($validRowNumbers));

				for ($i = 0; $i < count($rowsToUpdate); $i++) {
					$subscriber = $this->subscribermain->findByIPAddress($rowsToUpdate[$i][0], 
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					log_message('info', $i.'|SUBSCRIBER:'.json_encode($subscriber));
					if (is_null($subscriber) || $subscriber === false) {
						$notProcessedIPUnused[] = $rowsToUpdate[$i];
						continue;
					}
					$subscriberNew = array();
					foreach ($subscriber as $sKey => $sValue) {
						$subscriberNew[$sKey] = $sValue;
					}
					$subscriberNew['RBIPADDRESS'] = null;
					$subscriberNew['RBMULTISTATIC'] = null;
					$subscriberNew['CUSTOMERREPLYITEM'] = null;
					log_message('info', 'SUBS-ORG:'.json_encode($subscriber));
					log_message('info', 'SUBS-NEW:'.json_encode($subscriberNew));
					$result = $this->subscribermain->update($subscriber['USER_IDENTITY'], $subscriberNew, 
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					log_message('info', $i.'|updated:'.json_encode($result));
					if (!$result) {
						$notProcessedError[] = $rowsToUpdate[$i];
						continue;
					}
					//update ipaddress (and net address)
					if (!is_null($subscriber['RBIPADDRESS'])) {
						$this->ipaddress->freeUp($subscriber['RBIPADDRESS'], 
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
					if (!is_null($subscriber['RBMULTISTATIC'])) {
						$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
					//delete sessions
					$rowsToUpdate[$i][] = $subscriber['USER_IDENTITY'];
					$processedRows[] = $rowsToUpdate[$i];
					$cnParts = explode('@', $subscriber['USER_IDENTITY']);
					$sessions = $this->onlinesession->getSessions2($cnParts[0], isset($cnParts[1]) ? $cnParts[1] : '', $this->USESESSIONTABLE2,
						$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
						$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
					if (isset($sessions['data'])) {
						for ($j = 0; $j < count($sessions['data']); $j++) {
							$sess = $sessions['data'][$j];
							log_message('info', $i.'|deleting session:'.json_encode($sess));
							$deleted = $this->onlinesession->requestDisconnect($sess['USER_NAME'], $sess['NAS_IP_ADDRESS'], $sess['ACCT_SESSION_ID'], $this->USESESSIONTABLE2, 
								$this->DELETESESSIONAPIHOST, $this->DELETESESSIONAPIPORT, $this->DELETESESSIONAPISTUB,
								$this->DELETESESSIONAPIHOST2, $this->DELETESESSIONAPIPORT2, $this->DELETESESSIONAPISTUB2, 
								$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD, 
								$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2,
								$this->TBLMCOREHOST, $this->TBLMCOREPORT, $this->TBLMCORESCHEMA, $this->TBLMCOREUSERNAME, $this->TBLMCOREPASSWORD, 
								$this->TBLMCOREHOST2, $this->TBLMCOREPORT2, $this->TBLMCORESCHEMA2, $this->TBLMCOREUSERNAME2, $this->TBLMCOREPASSWORD2);
						}
					}
				}
				log_message('info', 'processedRows:'.json_encode($processedRows));
				log_message('info', 'notProcessedInvalidIP:'.json_encode($notProcessedInvalidIP));
				log_message('info', 'notProcessedIPDNE:'.json_encode($notProcessedIPDNE));
				log_message('info', 'notProcessedIPUnused:'.json_encode($notProcessedIPUnused));
				log_message('info', 'notProcessedError:'.json_encode($notProcessedError));
				$data = array(
					'step' => 'create',
					'processedRows' => $processedRows,
					'notProcessedInvalidIP' => $notProcessedInvalidIP,
					'notProcessedIPDNE' => $notProcessedIPDNE,
					'notProcessedIPUnused' => $notProcessedIPUnused,
					'notProcessedError' => $notProcessedError);
				$this->load->view('manage_ip_address_bulk_ip_unassign', $data);
			} else if ($step == 'download') {
				echo "download";
			}
		} else { //via link
			$this->load->view('manage_ip_address_bulk_ip_unassign');
		}
	}
	public function manageBngCounts() {
		$this->redirectIfNoAccess('Manage IP Address', 'main/manageBngCounts');
		$this->load->model('bngcounter');
		$BNGData = $this->bngcounter->fetchAllBNGData($this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
		$data = array(
			'BNGData' => $BNGData);
		$this->load->view('manage_bng', $data);
	}
	public function ajaxCreateBNG() {
		if ($this->input->is_ajax_request()) {
			$newBNG = $this->input->post('newBNG');
			$newMaxIP = $this->input->post('newMaxIP');
			$this->load->model('bngcounter');
			$result = $this->bngcounter->addBNGItem($newBNG, $newMaxIP, 
				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			$BNGRow = $result ? 
				$this->bngcounter->fetchBNGData($newBNG, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA) : null;
			$data = array(
				'result' => $result ? '1' : '0',
				'BNGRow' => $BNGRow);
			echo json_encode($data);
		}
	}
	public function ajaxEditBNG() {
		if ($this->input->is_ajax_request()) {
			$reference = $this->input->post('ref');
			$column = $this->input->post('column');
			$newValue = $this->input->post('newValue');
			$this->load->model('bngcounter');
			$result = false;
			$error = null;
			if ($column == 'bng') {
				$existing = $this->bngcounter->fetchBNGData($newValue, 
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				if ($existing === false) {
					$result = $this->bngcounter->editBNGItem($reference, $newValue, null, null, 
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				} else {
					$result = false;
					$error = 'BNG already exists.';
				}
			} else if ($column == 'max-ip') {
				$result = $this->bngcounter->editBNGItem($reference, null, $newValue, null,
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			}
			$data = array(
				'result' => $result ? '1' : '0',
				'error' => is_null($error) ? '' : $error);
			echo json_encode($data);
		}
	}
	public function ajaxDeleteBNG() {
		if ($this->input->is_ajax_request()) {
			$reference = $this->input->post('ref');
			$this->load->model('bngcounter');
			$result = $this->bngcounter->deleteBNGItem($reference, 
				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			$data = array(
				'result' => $result ? '1' : '0');
			echo json_encode($data);
		}
	}
	public function ajaxSyncBNGCount() {
		if ($this->input->is_ajax_request()) {
			$this->load->model('bngcounter');
			$current = $this->bngcounter->fetchAllBNGData($this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			log_message('info', '@ajaxSyncBNGCount|current:'.json_encode($current));
			for ($i = 0; $i < count($current); $i++) {
				$referenceBNG = $current[$i]['BNG'];
				$count = $this->bngcounter->countBNG($referenceBNG, 
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				log_message('info', '@ajaxSyncBNGCount|'.$i.'|ref:'.$referenceBNG.'|count:'.json_encode($count));
				$this->bngcounter->editBNGItem($referenceBNG, null, null, $count, 
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			}
			$newData = $this->bngcounter->fetchAllBNGData($this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			log_message('info', '@ajaxSyncBNGCount:'.json_encode($newData));
			$data = array(
				'result' => '1',
				'newData' => $newData);
			echo json_encode($data);
		}
	}
	/***********************************************************************
	 * manage network addresses
	 * PAGEID = 27
	 ***********************************************************************/
	public function showNetaddressesIndex($link = 0, $order = null, $netaddress = null, $username = null, $location = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Manage Network Addresses', 'main/showNetaddressesIndex');
		$realm = $this->session->userdata('realm');
		$submit = ''; 
		$pages = 0;
		if (intval($link) == 0) { //via form
			log_message('info', '@FORM');
			$location = $this->input->post('location');
			$netaddress = $this->input->post('netaddress');
			$username = $this->input->post('username');
			$location = $location == '' ? null : $location;
			$netaddress = trim($netaddress) == '' ? null : trim($netaddress);
			$username = trim($username) == '' ? null : trim($username);
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$submit = $this->input->post('btnRefresh');
			log_message('info', 'SUBMIT|'.json_encode($submit));
			$start = $submit == 'refresh' ? 0 : $start;
			$order = $this->input->post('order');
			$orderParts = explode('-', $order);
			$order = array('column' => $orderParts[0], 'dir' => $orderParts[1]);
		} else { //via url/link
			log_message('info', '@LINK');
			if ($order == null) {
				$order = array('column' => 'NETUSED', 'dir' => 'asc');
			} else {
				$parts = explode('-', $order);
				$order = array('column' => $parts[0], 'dir' => $parts[1]);
			}
			// [AJB] Removed 041818
			$location = is_null($location) ? $location : ($location == 'null' ? null : str_replace('--', ' ', $location));
			$netaddress = is_null($netaddress) ? $netaddress : ($netaddress == 'null' ? null : str_replace('_', ' ', $netaddress));
			if (!is_null($username)) {
				if ($username == 'null') {
					$username = null;
				} else {
					$username = str_replace('_', ' ', $username);
					$username = str_replace('~', '*', $username);
					$username = str_replace('%', '@', $username);
				}
				if (!is_null($netaddress)) {
					$netaddress = str_replace('~', '*', $netaddress);
					$netaddress = str_replace('---', '/', $netaddress);
				}
			}
		}
		$netaddress = is_null($netaddress) ? $netaddress : str_replace('---', '/', $netaddress);
		$netaddressStr = $netaddress;
		$exact = strpos($netaddress, '*') === false ? true : false;
		$wildcard = 'both';
		if (!$exact) {
			if (strpos($netaddress, '*') == 0) {
				$wildcard = 'before';
			} else if (strpos($netaddress, '*') == (strlen($netaddress) - 1)) {
				$wildcard = 'after';
			}
			$netaddress = substr($netaddress, 0, strpos($netaddress, '*'));
		}
		$this->load->model('netaddress');
		$this->load->model('ipaddress');
		//$locations = $this->netaddress->LOCATIONS;
		$locations = $this->ipaddress->fetchAllLocationsFromExtras();
		$cn = is_null($username) ? null : $username;//.'@'.$realm;
		$netaddresses = $this->netaddress->fetchAll($netaddress, $location, null, $cn, $exact, $wildcard, null, $start, $max, $order);
		$count = $this->netaddress->countNetAddresses($netaddress, $location, null, $cn, $exact, $wildcard, null);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		log_message('info', 'ORDER|'.json_encode($order));
		log_message('info', 'LOCATION|'.json_encode($location));
		log_message('info', 'NET|'.json_encode($netaddress));
		log_message('info', 'USERNAME|'.json_encode($username));
		log_message('info', 'START|'.json_encode($start));
		log_message('info', 'MAX|'.json_encode($max));
		log_message('info', 'NETS|'.json_encode($netaddresses));
		log_message('info', 'PAGES|'.json_encode($pages));
		log_message('info', 'COUNT|'.json_encode($count));
		$data = array(
			'netaddresses' => $netaddresses,
			'count' => $count,
			'start' => $start,
			'max' => $max,
			'pages' => $pages,
			'order' => $order,
			'locations' => $locations,
			'location' => $location,
			'netaddress' => $netaddressStr,
			'username' => $username);
		$this->load->view('manage_net_addresses', $data);
	}
	public function createNetaddressForm() {
		$this->redirectIfNoAccess('Manage Network Addresses', 'main/createNetaddressForm');
		//$this->load->model('netaddress');
		$this->load->model('ipaddress');
		//$locations = $this->netaddress->LOCATIONS;
		$locations = $this->ipaddress->fetchAllLocationsFromExtras();
		$set = false;
		$location = $set ? 'VALERO' : '';
		/********************************************************
		 * Q: should the location be automatically assigned (fetch from session data)?
		 * or the ip creator can pick? --> creator can pick
		 ********************************************************/
		$data = array(
			'locations' => $locations,
			'location' => $location,
			'set' => $set);
		$this->load->view('manage_net_addresses_create', $data);
	}
	public function createNetaddressProcess() {
		$this->redirectIfNoAccess('Manage Network Addresses', 'main/createNetaddressProcess');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('netaddress');
		$this->load->model('ipaddress');
		$this->load->model('util');
		//$locations = $this->netaddress->LOCATIONS;
		$locations = $this->ipaddress->fetchAllLocationsFromExtras();
		$set = intval($this->input->post('set')) == 1 ? true : false;
		$location = $this->input->post('location');
		$netaddress = $this->input->post('netaddress');
		$data = array(
			'locations' => $locations,
			'location' => $location,
			'set' => $set,
			'netaddress' => $netaddress);
		$valid = $this->util->isIPValid($netaddress);
		if (!$valid) {
			$data['error'] = 'The Network address is invalid.';
			$this->load->view('manage_net_addresses_create', $data);
			return;
		}
		$exists = $this->netaddress->netExists($netaddress, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
		if ($exists) {
			$data['error'] = 'The Network address already exists.';
			$this->load->view('manage_net_addresses_create', $data);
			return;	
		}
		/************************************************
		 * can create net address
		 ************************************************/
		$this->netaddress->create($netaddress, $location, '', $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('sysuseractivitylog');
			$this->sysuseractivitylog->logNetAddressCreation($netaddress, $sysuser, $sysuserIP, time()); //log netaddress creation
		}
		$data['location'] = $set ? $location : '';
		$data['netaddress'] = '';
		$data['message'] = 'Created Network address '.$netaddress.' for '.$location;
		$this->load->view('manage_net_addresses_create', $data);
	}
	public function processBulkNetaddressCreation($step = null) {
		$this->redirectIfNoAccess('Manage Network Addresses', 'main/processBulkNetaddressCreation');
		if (is_null($step)) { //via form
			$step = $this->input->post('step');
			$realm = '';
			if ($step == 'upload') {
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx';
    			$config['max_size'] = '50000';
    			$this->load->library('upload', $config);
    			if (!$this->upload->do_upload('file')) { //upload fail
    				$data['step'] = 'upload';
    				$data['error'] = 'Upload failed: '.$this->upload->display_errors();
    				$this->load->view('manage_net_addresses_bulk_create', $data);
    			} else { //upload ok
					$this->load->model('util');
    				$uploaded = $this->upload->data();
    				log_message('info', 'UPLOADED FILE: '.json_encode($uploaded));
    				$valid = $this->util->verifyBulkCreateNetFormat($uploaded['full_path']);
    				if (!$valid) {
    					$data['step'] = 'upload';
    					$data['error'] = 'Invalid file contents';
    					$data['realm'] = $realm;
    					$this->load->view('manage_net_addresses_bulk_create', $data);
    				} else {
    					$rows = $this->util->verifyBulkCreateNetData($uploaded['full_path'], $realm);
    					log_message('info', 'ROWS: '.json_encode($rows));
	    				$data = array(
	    					'step' => 'confirm',
	    					'path' => $uploaded['full_path'],
	    					'valid' => $rows['valid'],
	    					'vaildRowNumbers' => $rows['validRowNumbers'],
	    					'invalid' => $rows['invalid'],
	    					'invalidRowNumbers' => $rows['invalidRowNumbers']);
	    				$this->load->view('manage_net_addresses_bulk_create', $data);
    				}
    			}
			} else if ($step == 'create') {
				$now = time();
				$path = $this->input->post('path');
				$validRowNumbers = unserialize($this->input->post('validRowNumbers'));
				$invalidRowNumbers = unserialize($this->input->post('invalidRowNumbers'));
				log_message('info', 'VALID ROWS: '.json_encode($validRowNumbers));
				log_message('info', 'INVALID ROWS: '.json_encode($invalidRowNumbers));
				$createdRows = [];
				$notCreatedInvalidNet = [];
				$notCreatedExistingNet = [];
				$notCreatedInvalidLocation = [];
				$this->load->model('util');
				$this->load->model('netaddress');
				$this->load->model('ipaddress');
				//$locations = $this->netaddress->LOCATIONS;
				$locations = $this->ipaddress->fetchAllLocationsFromExtras();
				$netsToCreate = $this->util->extractRowsToCreateNet($path, $validRowNumbers);
				log_message('info', 'TO CREATE: '.json_encode($netsToCreate));
				/*********************************************
				 * get this from session variables
				 *********************************************/
				$sysuser = $this->session->userdata('username');
				$sysuserIP = $this->session->userdata('ip_address');

				$this->load->model('sysuseractivitylog');
				for ($i = 0; $i < count($netsToCreate); $i++) {
					$netaddress = $this->netaddress->rowDataToNetArray($netsToCreate[$i]);
					$netValid = $this->netaddress->isNetValid($netaddress['NETADDRESS']);
					if (!$netValid) {
						log_message('info', $i.'|invalid net: '.$netaddress['NETADDRESS']);
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedInvalidNet[] = $netsToCreate[$i];
						continue;
					}
					$exists = $this->netaddress->netExists($netaddress['NETADDRESS'], $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if ($exists) {
						log_message('info', $i.'|net exists: '.$netaddress['NETADDRESS']);
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedExistingNet[] = $netsToCreate[$i];
						continue;
					}
					$locationValid = false;
					for ($j = 0; $j < count($locations); $j++) {
						if (strcmp(strtolower($locations[$j]), strtolower($netaddress['LOCATION'])) == 0) {
							$locationValid = true;
							break;
						}
					}
					if (!$locationValid) {
						log_message('info', $i.'|invalid location: '.$netaddress['LOCATION']);
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedInvalidLocation[] = $netsToCreate[$i];
						continue;
					}
					$created = $this->netaddress->create($netaddress['NETADDRESS'], $netaddress['LOCATION'], $netaddress['DESCRIPTION'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					$createdRows[] = $netsToCreate[$i];
					if ($sysuser != $this->SUPERUSER) {
						$this->sysuseractivitylog->logNetAddressCreation($netaddress['NETADDRESS'], $sysuser, $sysuserIP, $now);
					}
				}
				log_message('info', '__________________________________________________________________________________');
				log_message('info', 'created|'.json_encode($createdRows));
				log_message('info', 'invalidIP|'.json_encode($notCreatedInvalidNet));
				log_message('info', 'existingIP|'.json_encode($notCreatedExistingNet));
				log_message('info', 'invalidLocation|'.json_encode($notCreatedInvalidLocation));
				log_message('info', 'invalidRows|'.json_encode($invalidRowNumbers));
				$data = array(
					'step' => 'create',
					'created' => $createdRows,
					'invalidNet' => $notCreatedInvalidNet,
					'existingNet' => $notCreatedExistingNet,
					'invalidLocation' => $notCreatedInvalidLocation,
					'invalidRowNumbers' => $invalidRowNumbers);
				$this->load->view('manage_net_addresses_bulk_create', $data);
			} else if ($step == 'download') {
				echo 'download';
			}
		} else { //via link
			$this->load->view('manage_net_addresses_bulk_create');
		}
	}
	public function freeupNetaddressProcess() {
		if ($this->input->is_ajax_request()) {
			$now = date('Y-m-d H:i:s', time());
			/***********************************************
			 * get these from session variables
			 ***********************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$this->load->model('netaddress');
			$this->load->model('subscribermain');
			$this->load->model('sysuseractivitylog');
			$this->load->model('subscriberaudittrail');
			$net = $this->input->post('net');
			$username = $this->input->post('username');
			if ($this->netaddress->freeUp($net,
				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA)) {
				$subscriberOld = $this->subscribermain->findByUserIdentity($username);
				$subscriber = array(
					'USER_IDENTITY' => $subscriberOld['USER_IDENTITY'],
					'USERNAME' => $subscriberOld['USERNAME'],
					'BANDWIDTH' => $subscriberOld['BANDWIDTH'],
					'CUSTOMERSTATUS' => $subscriberOld['CUSTOMERSTATUS'],
					'PASSWORD' => $subscriberOld['PASSWORD'],
					'CUSTOMERREPLYITEM' => $subscriberOld['CUSTOMERREPLYITEM'],
					'CREATEDATE' => $subscriberOld['CREATEDATE'],
					'LASTMODIFIEDDATE' => $now,
					'RBCUSTOMERNAME' => $subscriberOld['RBCUSTOMERNAME'],
					'RBCREATEDBY' => $subscriberOld['RBCREATEDBY'],
					'RBADDITIONALSERVICE5' => $subscriberOld['RBADDITIONALSERVICE5'],
					'RBADDITIONALSERVICE4' => $subscriberOld['RBADDITIONALSERVICE4'],
					'RBADDITIONALSERVICE3' => $subscriberOld['RBADDITIONALSERVICE3'],
					'RBADDITIONALSERVICE2' => $subscriberOld['RBADDITIONALSERVICE2'],
					'RBADDITIONALSERVICE1' => $subscriberOld['RBADDITIONALSERVICE1'],
					'RBCHANGESTATUSDATE' => $subscriberOld['RBCHANGESTATUSDATE'],
					'RBCHANGESTATUSBY' => $subscriberOld['RBCHANGESTATUSBY'],
					'RBACTIVATEDDATE' => $subscriberOld['RBACTIVATEDDATE'],
					'RBACTIVATEDBY' => $subscriberOld['RBACTIVATEDBY'],
					'RBACCOUNTSTATUS' => $subscriberOld['RBACCOUNTSTATUS'],
					'RBSVCCODE2' => $subscriberOld['RBSVCCODE2'],
					'RBACCOUNTPLAN' => $subscriberOld['RBACCOUNTPLAN'],
					'CUSTOMERTYPE' => $subscriberOld['CUSTOMERTYPE'],
					'RBSERVICENUMBER' => $subscriberOld['RBSERVICENUMBER'], //should not change
					'RBCHANGESTATUSFROM' => $subscriberOld['CUSTOMERSTATUS'],
					'RBSECONDARYACCOUNT' => $subscriberOld['RBSECONDARYACCOUNT'],
					'RBUNLIMITEDACCESS' => $subscriberOld['RBUNLIMITEDACCESS'],
					'RBTIMESLOT' => $subscriberOld['RBTIMESLOT'],
					'RBORDERNUMBER' => $subscriberOld['RBORDERNUMBER'],
					'RBREMARKS' => $subscriberOld['RBREMARKS'],
					'RBREALM' => $subscriberOld['RBREALM'], //should not change
					'RBNUMBEROFSESSION' => $subscriberOld['RBNUMBEROFSESSION'],
					'RBMULTISTATIC' => null,
					'RBIPADDRESS' => $subscriberOld['RBIPADDRESS'],
					'RBENABLED' => $subscriberOld['RBENABLED']);
				$this->subscribermain->update($subscriber['USER_IDENTITY'], $subscriber,
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				if ($sysuser != $this->SUPERUSER) {
					$this->sysuseractivitylog->logNetAddressFreeup($net, $sysuser, $sysuserIP, time());
					$this->subscriberaudittrail->logSubscriberModification($subscriber, $subscriberOld, $sysuser, $sysuserIP, $now, true);
				}
				$result = array(
					'status' => '1',
					'time' => $now);
				echo json_encode($result);
			} else {
				echo json_encode(array('status' => '0'));
			}
		} else {
			redirect('main/showNetaddressesIndex/1');
		}
	}
	public function deleteNetaddressProcess() {
		if ($this->input->is_ajax_request()) {
			/***********************************************
			 * get these from session variables
			 ***********************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$this->load->model('netaddress');
			$net = $this->input->post('net');
			if ($this->netaddress->delete($net, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA)) {
				if ($sysuser != $this->SUPERUSER) {
					$this->load->model('sysuseractivitylog');
					$this->sysuseractivitylog->logNetAddressDeletion($net, $sysuser, $sysuserIP, time());
				}
				echo json_encode(array('status' => '1'));
			} else {
				echo json_encode(array('status' => '0'));
			}
		} else {
			redirect('main/showNetaddressesIndex/1');
		}
	}
	/***********************************************************************
	 * manage cabinets
	 ***********************************************************************/
	public function showCabinetsIndex($link = 0, $order = null, $cabinetName = null, $homingBng = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Manage Cabinets', 'main/showCabinetsIndex');
		$submit = '';
		$pages = 0;
		if (intval($link) == 0) { //via form
			log_message('info', 'via cabinet form');
			$cabinetName = $this->input->post('cabinetName');
			$homingBng = $this->input->post('homingBng');
			$cabinetName = trim($cabinetName) == '' ? null : $cabinetName;
			$homingBng = trim($homingBng) == '' ? null : $homingBng;
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$submit = $this->input->post('btnRefresh');
			$start = $submit == 'refresh' ? 0 : $start;
			$order = $this->input->post('order');
			$orderParts = explode('-', $order);
			$order = array('column' => $orderParts[0], 'dir' => $orderParts[1]);
		} else { //via link
			log_message('info', 'via cabinet link');
			if ($order == null) {
				$order = array('column' => 'name', 'dir' => 'asc');
			} else {
				$parts = explode('-', $order);
				$order = array('column' => $parts[0], 'dir' => $parts[1]);
			}
			$cabinetName = is_null($cabinetName) ? $cabinetName : ($cabinetName == 'null' ? null : str_replace('~~', ' ', $cabinetName));
			$homingBng = is_null($homingBng) ? $homingBng : ($homingBng == 'null' ? null : str_replace('~~', ' ', $homingBng));
			log_message('debug', 'order: '.json_encode($order));
			log_message('debug', 'cabinetName: '.$cabinetName);
			log_message('debug', 'homingBng: '.$homingBng);
		}
		$this->load->model('cabinet');
		$cabinets = $this->cabinet->getCabinets($cabinetName, $homingBng, $start, $max, $order);
		$count = $this->cabinet->countCabinets($cabinetName, $homingBng);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		log_message('info', 'ORDER|'.json_encode($order));
		log_message('info', 'CABINET NAME|'.json_encode($cabinetName));
		log_message('info', 'HOMING BNG|'.json_encode($homingBng));
		log_message('info', 'START|'.json_encode($start));
		log_message('info', 'MAX|'.json_encode($max));
		log_message('info', 'CABINETS|'.json_encode($cabinets));
		log_message('info', 'PAGES|'.json_encode($pages));
		log_message('info', 'COUNT|'.json_encode($count));
		$data = array(
			'cabinets' => $cabinets,
			'count' => $count,
			'start' => $start,
			'max' => $max,
			'pages' => $pages,
			'order' => $order,
			'cabinetName' => $cabinetName,
			'homingBng' => $homingBng);
		$this->load->view('cabinet_index', $data);
	}
	public function showCabinetCreateForm() {
		$this->redirectIfNoAccess('Manage Cabinets', 'main/showCabinetCreateForm');
		$this->load->model('cabinet');
		$locations = $this->cabinet->getLocations();
		$data = array('locations' => $locations);
		$this->load->view('cabinet_create', $data);
	}
	public function processCabinetCreation() {
		$this->redirectIfNoAccess('Manage Cabinets', 'main/processCabinetCreation');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('cabinet');
		$this->load->model('sysuseractivitylog');
		$cabinetName = $this->input->post('cabinetName');
		$cabinetBngId = $this->input->post('cabinetBng');
		$cabinetVlan = $this->input->post('cabinetVlan');
		$locations = $this->cabinet->getLocations();
		$data = array(
			'cabinetName' => $cabinetName,
			'cabinetBng' => $cabinetBngId,
			'cabinetVlan' => $cabinetVlan,
			'locations' => $locations);
		$disallowedCharacter = "/";
		$position = strpos($cabinetName, $disallowedCharacter);
		if ($position !== false) {
			$data['error'] = 'There\'s a disallowed character ('.$disallowedCharacter.') on the cabinet name '.$cabinetName;
			$this->load->view('cabinet_create', $data);
			return;
		}
		$cabinet = $this->cabinet->getCabinetWithName($cabinetName);
		if (!empty($cabinet)) {
			$data['error'] = 'Cabinet name '.$cabinetName.' is already used.';
			$this->load->view('cabinet_create', $data);
			return;
		}
		$result = $this->cabinet->addCabinet($cabinetName, $cabinetBngId, $cabinetVlan);
		if ($result) {
			if ($this->USEAPIMYSQL) {
				$apiConn = $this->checkSecondaryMysqlConnection($this->APIMYSQLHOST, $this->APIMYSQLDATABASE, $this->APIMYSQLUSERNAME, $this->APIMYSQLPASSWORD);
				if ($apiConn === false) {
					//revert creation
					$deleted = $this->cabinet->removeCabinetWithName($cabinetName);
					$data['error'] = 'No connection to API database.';
					$this->load->view('cabinet_create', $data);
					return;
				}
				$resultSecondary = $this->cabinet->addCabinetSecondary($apiConn, $cabinetName, $cabinetBngId, $cabinetVlan);
				if ($resultSecondary) {
					if ($sysuser != $this->SUPERUSER) {
						$this->sysuseractivitylog->logCabinetCreation($cabinetName, $cabinetBngId, $cabinetVlan, $sysuser, $sysuserIP, time());
					}
					$data['message'] = $cabinetName.' cabinet added.';
				} else {
					//revert creation
					$deleted = $this->cabinet->removeCabinetWithName($cabinetName);
					$data['error'] = 'Unable to create cabinet at API database.';
				}
			} else {
				if ($sysuser != $this->SUPERUSER) {
					$this->sysuseractivitylog->logCabinetCreation($cabinetName, $cabinetBngId, $cabinetVlan, $sysuser, $sysuserIP, time());
				}
				$data['message'] = $cabinetName.' cabinet added.';
			}
		} else {
			$data['error'] = 'Unable to create cabinet';
		}
		$this->load->view('cabinet_create', $data);
	}
	public function showCabinetModifyForm($id = 0) {
		$this->redirectIfNoAccess('Manage Cabinets', 'main/showCabinetModifyForm');
		$this->load->model('cabinet');
		$locations = $this->cabinet->getLocations();
		if (intval($id) == 0) {
			$data = array(
				'error' => 'Did not pick a cabinet to modify',
				'locations' => $locations);
			$this->load->view('cabinet_modify', $data);
			return;
		}
		$cabinet = $this->cabinet->getCabinetWithId($id);
		if (empty($cabinet)) {
			$data = array(
				'error' => 'Could not find cabinet with id = '.$id,
				'locations' => $locations);
			$this->load->view('cabinet_modify', $data);
			return;
		}
		$data = array(
			'locations' => $locations,
			'cabinetName' => $cabinet['name'],
			'cabinetBng' => $cabinet['bng_id'],
			'cabinetVlan' => $cabinet['data_vlan'],
			'cabinetId' => $cabinet['id']);
		$this->load->view('cabinet_modify', $data);
	}
	public function processCabinetModification() {
		$this->redirectIfNoAccess('Manage Cabinets', 'main/processCabinetModification');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('cabinet');
		$this->load->model('sysuseractivitylog');
		$cabinetId = $this->input->post('cabinetId');
		$cabinetName = $this->input->post('cabinetName');
		$cabinetBngId = $this->input->post('cabinetBng');
		$cabinetVlan = $this->input->post('cabinetVlan');
		$locations = $this->cabinet->getLocations();
		$data = array(
			'cabinetName' => $cabinetName,
			'cabinetBng' => $cabinetBngId,
			'cabinetVlan' => $cabinetVlan,
			'cabinetId' => $cabinetId,
			'locations' => $locations);
		$cabinet = $this->cabinet->getCabinetWithName($cabinetName);
		$disallowedCharacter = "/";
		$position = strpos($cabinetName, $disallowedCharacter);
		if ($position !== false) {
			$data['error'] = 'There\'s a disallowed character ('.$disallowedCharacter.') on the new cabinet name '.$cabinetName;
			$this->load->view('cabinet_modify', $data);
			return;
		}
		$original = $this->cabinet->getCabinetWithId($cabinetId);
		if (!empty($cabinet) && strtolower($cabinetName) != strtolower($original['name'])) {
			$data['error'] = 'Cabinet name '.$cabinetName.' is already used.';
			$this->load->view('cabinet_modify', $data);
			return;
		}
		$result = $this->cabinet->editCabinet($cabinetId, $cabinetName, $cabinetBngId, $cabinetVlan);
		if ($result) {
			if ($this->USEAPIMYSQL) {
				$apiConn = $this->checkSecondaryMysqlConnection($this->APIMYSQLHOST, $this->APIMYSQLDATABASE, $this->APIMYSQLUSERNAME, $this->APIMYSQLPASSWORD);
				if ($apiConn === false) {
					//revert update
					$reverted = $this->cabinet->editCabinet($original['id'], $original['name'], $original['bng_id'], $original['data_vlan']);
					$data['error'] = 'No connection to API database.';
					$this->load->view('cabinet_modify', $data);
					return;
				}
				$resultSecondary = $this->cabinet->editCabinetWithNameSecondary($apiConn, $original['name'], $cabinetName, $cabinetBngId, $cabinetVlan);
				if ($resultSecondary) {
					if ($sysuser != $this->SUPERUSER) {
						$this->sysuseractivitylog->logCabinetModification($cabinetId, $cabinetName, $cabinetBngId, $cabinetVlan, $sysuser, $sysuserIP, time());
					}
					$data['message'] = 'Changes saved.';
				} else {
					//revert update
					$reverted = $this->cabinet->editCabinet($original['id'], $original['name'], $original['bng_id'], $original['data_vlan']);
					$data['error'] = 'Unable to update cabinet at API database.';
				}
			} else {
				if ($sysuser != $this->SUPERUSER) {
					$this->sysuseractivitylog->logCabinetModification($cabinetId, $cabinetName, $cabinetBngId, $cabinetVlan, $sysuser, $sysuserIP, time());
				}
				$data['message'] = 'Changes saved.';
			}
		} else {
			$data['error'] = 'Unable to save changes.';
		}
		$this->load->view('cabinet_modify', $data);
	}
	public function deleteCabinetProcess() {
		if ($this->input->is_ajax_request()) {
			/***********************************************
			 * get these from session variables
			 ***********************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$this->load->model('cabinet');
			$this->load->model('sysuseractivitylog');
			$cabinetId = $this->input->post('id');
			$cabinet = $this->cabinet->getCabinetWithId($cabinetId);
			$result = $this->cabinet->removeCabinet($cabinetId);
			if ($result) {
				if ($this->USEAPIMYSQL) {
					$apiConn = $this->checkSecondaryMysqlConnection($this->APIMYSQLHOST, $this->APIMYSQLDATABASE, $this->APIMYSQLUSERNAME, $this->APIMYSQLPASSWORD);
					if ($apiConn === false) {
						//revert delete
						$reInserted = $this->cabinet->addCabinet($cabinet['name'], $cabinet['bng_id'], $cabinet['data_vlan']);
						$newCabinetObj = $this->cabinet->getCabinetWithName($cabinet['name']);
						echo json_encode(array('status' => '0', 'error' => 'No connection to API database.', 'id' => $newCabinetObj['id']));
					} else {
						$resultSecondary = $this->cabinet->removeCabinetWithNameSecondary($apiConn, $cabinet['name']);
						if ($resultSecondary) {
							if ($sysuser != $this->SUPERUSER) {
								$this->sysuseractivitylog->logCabinetDeletion($cabinet['name'], $sysuser, $sysuserIP, time());
							}
							echo json_encode(array('status' => '1'));
						} else {
							//revert delete
							$reInserted = $this->cabinet->addCabinet($cabinet['name'], $cabinet['bng_id'], $cabinet['data_vlan']);
							$newCabinetObj = $this->cabinet->getCabinetWithName($cabinet['name']);
							echo json_encode(array('status' => '0', 'error' => 'Unable to delete cabinet at API database.', 'id' => $newCabinetObj['id']));
						}
					}
				} else {
					if ($sysuser != $this->SUPERUSER) {
						$this->sysuseractivitylog->logCabinetDeletion($cabinet['name'], $sysuser, $sysuserIP, time());
					}
					echo json_encode(array('status' => '1'));
				}
			} else {
				echo json_encode(array('status' => '0'));
			}
		} else {
			redirect('main/showCabinetsIndex/1');
		}
	}
	public function processBulkLoadCabinets($step = null) {
		$this->redirectIfNoAccess('Manage Cabinets', 'main/processBulkLoadCabinets');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('cabinet');
		/**************************************************
		 * main/processBulkLoadCabinets accessed via form
		 **************************************************/
		if (is_null($step)) {
			$step = $this->input->post('step');
			if ($step == 'upload') {
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx';
				$config['max_size'] = '50000';
				$this->load->library('upload', $config);
				/**************************************************
				 * upload failed
				 **************************************************/
				if (!$this->upload->do_upload('file')) {
					$data['step'] = 'upload';
					$data['error'] = 'Upload failed: '.$this->upload->display_errors();
					$this->load->view('cabinet_bulk_create', $data);
				/**************************************************
				 * file uploaded
				 **************************************************/
				} else {
					$this->load->model('util');
					$uploaded = $this->upload->data();
					log_message('info', 'UPLOADED FILE: '.json_encode($uploaded));
					$valid = $this->util->verifyBulkCreateCabinetFormat($uploaded['full_path']);
					if (!$valid) {
						$data['step'] = 'upload';
						$data['error'] = 'Invalid file contents';
						$this->load->view('cabinet_bulk_create', $data);
					} else {
						$rows = $this->util->verifyBulkCreateCabinetData($uploaded['full_path']);
						log_message('info', 'ROWS: '.json_encode($rows));
						$data = array(
							'step' => 'confirm',
							'path' => $uploaded['full_path'],
							'valid' => $rows['valid'],
							'vaildRowNumbers' => $rows['validRowNumbers'],
							'invalid' => $rows['invalid'],
							'invalidRowNumbers' => $rows['invalidRowNumbers']);
						$this->load->view('cabinet_bulk_create', $data);
					}
				}
			} else if ($step == 'create') {
				$path = $this->input->post('path');
				$validRowNumbers = unserialize($this->input->post('validRowNumbers'));
				$invalidRowNumbers = unserialize($this->input->post('invalidRowNumbers'));
				log_message('info', 'VALID ROWS: '.json_encode($validRowNumbers));
				log_message('info', 'INVALID ROWS: '.json_encode($invalidRowNumbers));
				$createdRows = [];
				$notCreatedInvalidHomingBng = [];
				$notCreatedExistingCabinetName = [];
				$notCreatedDbError = [];
				$disallowedCharacter = "/";
				$this->load->model('util');
				$locationsTmp = $this->cabinet->getLocations();
				$locationsTmpCount = count($locationsTmp);
				$locations = [];
				$locationsLowercase = [];
				$locationsId = [];
				for ($i = 0; $i < $locationsTmpCount; $i++) {
					$locations[] = $locationsTmp[$i]['location'];
					$locationsLowercase[] = strtolower($locationsTmp[$i]['location']);
					$index = str_replace(' ', '~~', $locationsTmp[$i]['location']);
					$index = strtolower($index);
					$locationsId[$index] = $locationsTmp[$i]['id'];
				}
				// log_message('debug', json_encode($locations));
				// log_message('debug', json_encode($locationsId));
				$cabinetsToCreate = $this->util->extractRowsToCreateCabinet($path, $validRowNumbers);
				log_message('debug', 'TO CREATE: '.json_encode($cabinetsToCreate));
				$this->load->model('sysuseractivitylog');
				$cabinetsToCreateCount = count($cabinetsToCreate);
				$notCreatedDisallowedCharacter = [];
				for ($i = 0; $i < $cabinetsToCreateCount; $i++) {
					$cabinet = $this->cabinet->rowDataToCabinetArray($cabinetsToCreate[$i]);
					log_message('debug', $i.'|'.json_encode($cabinet));
					$position = strpos($cabinet['name'], $disallowedCharacter);
					if ($position !== false) {
						log_message('debug', $i.'|cabinet name has a disallowed character ('.$disallowedCharacter.')');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedDisallowedCharacter[] = $cabinetsToCreate[$i];
						continue;
					}
					$cabinetNameExists = $this->cabinet->cabinetExists(strtolower($cabinet['name']));
					if ($cabinetNameExists) {
						log_message('debug',  $i.'|cabinet name exists: '.$cabinet['name']);
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedExistingCabinetName[] = $cabinetsToCreate[$i];
						continue;
					}
					//convert homing bng string ($cabinet['homing_bng']) to its corresponding id
					$locationTmp = str_replace(' ', '~~', $cabinet['homing_bng']);
					$locationTmp = strtolower($locationTmp);
					$locationId = isset($locationsId[$locationTmp]) ? $locationsId[$locationTmp] : 0;
					$cabinetsToCreate[$i][4] = $locationId;
					$homingBngValid = in_array(strtolower($cabinet['homing_bng']), $locationsLowercase);
					if (!$homingBngValid) {
						log_message('debug', $i.'|invalid homing bng: '.$cabinet['homing_bng']);
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedInvalidHomingBng[] = $cabinetsToCreate[$i];
						continue;
					}
					$created = $this->cabinet->addCabinet($cabinet['name'], $locationId, $cabinet['data_vlan']);
					if ($created) {
						if ($this->USEAPIMYSQL) {
							$apiConn = $this->checkSecondaryMysqlConnection($this->APIMYSQLHOST, $this->APIMYSQLDATABASE, $this->APIMYSQLUSERNAME, $this->APIMYSQLPASSWORD);
							if ($apiConn === false) {
								log_message('debug', $i.'|no connection to api db: '.json_encode($cabinet));
								$deleted = $this->cabinet->removeCabinetWithName($cabinet['name']);
								log_message('debug', $i.'|reverted creation: '.($deleted ? 'success' : 'fail'));
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notCreatedDbError[] = $cabinetsToCreate[$i];
							} else {
								$createdSecondary = $this->cabinet->addCabinetSecondary($apiConn, $cabinet['name'], $locationId, $cabinet['data_vlan']);
								if ($createdSecondary) {
									$createdRows[] = $cabinetsToCreate[$i];
									if ($sysuser != $this->SUPERUSER) {
										$this->sysuseractivitylog->logCabinetCreation($cabinet['name'], $locationId, $cabinet['data_vlan'], $sysuser, $sysuserIP, time());
									}
								} else {
									log_message('debug', $i.'|unable to create at api db: '.json_encode($cabinet));
									$deleted = $this->cabinet->removeCabinetWithName($cabinet['name']);
									log_message('debug', $i.'|reverted creation: '.($deleted ? 'success' : 'fail'));
									$invalidRowNumbers[] = $validRowNumbers[$i];
									$notCreatedDbError[] = $cabinetsToCreate[$i];
								}
							}
						} else {
							$createdRows[] = $cabinetsToCreate[$i];
							if ($sysuser != $this->SUPERUSER) {
								$this->sysuseractivitylog->logCabinetCreation($cabinet['name'], $locationId, $cabinet['data_vlan'], $sysuser, $sysuserIP, time());
							}
						}
					} else {
						log_message('debug', $i.'|db error: '.json_encode($cabinet));
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedDbError[] = $cabinetsToCreate[$i];
					}
				}
				log_message('debug', '__________________________________________________________________________________');
				log_message('debug', 'created|'.json_encode($createdRows));
				log_message('debug', 'existing|'.json_encode($notCreatedExistingCabinetName));
				log_message('debug', 'invalidHomingBng|'.json_encode($notCreatedInvalidHomingBng));
				log_message('debug', 'dbError|'.json_encode($notCreatedDbError));
				$data = array(
					'step' => 'create',
					'created' => $createdRows,
					'existingCabinetName' => $notCreatedExistingCabinetName,
					'invalidHomingBng' => $notCreatedInvalidHomingBng,
					'disallowedCharacter' => $notCreatedDisallowedCharacter,
					'dbError' => $notCreatedDbError);
				$this->load->view('cabinet_bulk_create', $data);
			}
		/**************************************************
		 * main/processBulkLoadCabinets accessed via url
		 **************************************************/
		} else {
			$this->load->view('cabinet_bulk_create');
		}
	}
	public function processBulkModifyCabinets($step = null) {
		$this->redirectIfNoAccess('Manage Cabinets', 'main/processBulkModifyCabinets');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('cabinet');
		/**************************************************
		 * main/processBulkDeleteCabinets accessed via form
		 **************************************************/
		if (is_null($step)) {
			$step = $this->input->post('step');
			if ($step == 'upload') {
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx';
				$config['max_size'] = '50000';
				$this->load->library('upload', $config);
				/**************************************************
				 * upload failed
				 **************************************************/
				if (!$this->upload->do_upload('file')) {
					$data['step'] = 'upload';
					$data['error'] = 'Upload failed: '.$this->upload->display_errors();
					$this->load->view('cabinet_bulk_modify', $data);
				/**************************************************
				 * file uploaded
				 **************************************************/
				} else {
					$this->load->model('util');
					$uploaded = $this->upload->data();
					log_message('info', 'UPLOADED FILE: '.json_encode($uploaded));
					$valid = $this->util->verifyBulkModifyCabinetFormat($uploaded['full_path']);
					if (!$valid) {
						$data['step'] = 'upload';
						$data['error'] = 'Invalid file contents';
						$this->load->view('cabinet_bulk_modify', $data);
					} else {
						$rows = $this->util->verifyBulkModifyCabinetData($uploaded['full_path']);
						log_message('info', 'ROWS: '.json_encode($rows));
						$validCount = count($rows['valid']);
						$validMarks = [];
						$showValid = [];
						$showValidRowNumbers = [];
						$dne = [];
						for ($i = 0; $i < $validCount; $i++) {
							$row = $rows['valid'][$i];
							$cabinet = $this->cabinet->getCabinetWithName($row[0]);
							if ($cabinet === false || empty($cabinet) || is_null($cabinet)) {
								$dne[] = $row;
							} else {
								$mark = [];
								if (trim($row[1]) == '') {
									$row[1] = $cabinet['name'];
								} else {
									$mark['name'] = true;
								}
								if (trim($row[2]) == '') {
									$row[2] = $cabinet['homing_bng'];
								} else {
									$mark['homing_bng'] = true;
								}
								if (trim($row[3]) == '') {
									$row[3] = $cabinet['data_vlan'];
								} else {
									$mark['data_vlan'] = true;
								}
								$showValid[] = $row;
								$showValidRowNumbers[] = $rows['validRowNumbers'][$i];
								$validMarks[] = $mark;
							}
						}
						$data = array(
							'step' => 'confirm',
							'path' => $uploaded['full_path'],
							'valid' => $showValid,
							'validRowNumbers' => $showValidRowNumbers,
							'validMarks' => $validMarks,
							'invalid' => $rows['invalid'],
							'invalidRowNumbers' => $rows['invalidRowNumbers'],
							'dne' => $dne,
							'noChange' => $rows['noChange'],
							'noChangeRowNumbers' => $rows['noChangeRowNumbers']);
						$this->load->view('cabinet_bulk_modify', $data);
					}
				}
			} else if ($step == 'modify') {
				$path = $this->input->post('path');
				$validRowNumbers = unserialize($this->input->post('validRowNumbers'));
				log_message('info', 'VALID ROWS: '.json_encode($validRowNumbers));
				$edited = [];
				$notEditedDuplicateName = [];
				$notEditedInvalidHomingBng = [];
				$notEditedDbError = [];
				$markers = [];
				$this->load->model('util');
				$disallowedCharacter = "/";
				$locationsTmp = $this->cabinet->getLocations();
				$locationsTmpCount = count($locationsTmp);
				$locations = [];
				$locationsId = [];
				$notEditedHasDisallowedCharacter= [] ;
				for ($i = 0; $i < $locationsTmpCount; $i++) {
					$locations[] = strtolower($locationsTmp[$i]['location']);
					$index = str_replace(' ', '~~', $locationsTmp[$i]['location']);
					$index = strtolower($index);
					$locationsId[$index] = $locationsTmp[$i]['id'];
				}
				$cabinetsToUpdate = $this->util->extractRowsToUpdateCabinet($path, $validRowNumbers);
				log_message('debug', 'TO MODIFY: '.json_encode($cabinetsToUpdate));
				$this->load->model('sysuseractivitylog');
				$cabinetsToUpdateCount = count($cabinetsToUpdate);
				// log_message('debug', json_encode($locationsId));
				for ($i = 0; $i < $cabinetsToUpdateCount; $i++) {
					$cabinet = $this->cabinet->rowDataToCabinetArray($cabinetsToUpdate[$i]);
					$cabinetOld = $this->cabinet->getCabinetWithName($cabinet['name']);
					log_message('debug', $i."|".json_encode($cabinetOld));
					log_message('debug', $i."|".json_encode($cabinet));
					if (trim($cabinet['newName']) != '' && !is_null($cabinet['newName'])) {
						$position = strpos($cabinet['newName'], $disallowedCharacter);
						if ($position !== false) {
							log_message('debug', $i.'|new cabinet name has a disallowed character ('.$disallowedCharacter.')');
							$notEditedHasDisallowedCharacter[] = $cabinetsToUpdate[$i];
							continue;
						}
						$newCabinetNameExists = $this->cabinet->cabinetExists($cabinet['newName']);
						if ($newCabinetNameExists && $cabinet['name'] != $cabinet['newName']) {
							log_message('debug',  $i.'|new cabinet name exists: '.$cabinet['newName']);
							$notEditedDuplicateName[] = $cabinetsToUpdate[$i];
							continue;
						}
					} else {
						$cabinet['newName'] = $cabinetOld['name'];
					}
					if (trim($cabinet['homing_bng']) != '' && !is_null($cabinet['homing_bng'])) {
						//convert homing bng string ($cabinet['homing_bng']) to its corresponding id
						$locationTmp = str_replace(' ', '~~', $cabinet['homing_bng']);
						$locationTmp = strtolower($locationTmp);
						$locationId = isset($locationsId[$locationTmp]) ? $locationsId[$locationTmp] : 0;
						$cabinetsToUpdate[$i][4] = $locationId;
						$homingBngValid = in_array(strtolower($cabinet['homing_bng']), $locations);
						if (!$homingBngValid) {
							log_message('debug', $i.'|invalid new homing bng: '.$cabinet['homing_bng']);
							$notEditedInvalidHomingBng[] = $cabinetsToUpdate[$i];
							continue;
						}
					} else {
						$locationId = $cabinetOld['bng_id'];
						$cabinetsToUpdate[$i][4] = $locationId;
					}
					if (trim($cabinet['data_vlan']) == '' || is_null($cabinet['data_vlan'])) {
						$cabinet['data_vlan'] = $cabinetOld['data_vlan'];
					}
					// log_message('debug', ' ');
					// log_message('debug', ' ');
					// log_message('debug', 'id:'.$cabinetOld['id'].',newName:'.$cabinet['newName'].',homing_bng:'.$locationId.',vlan:'.$cabinet['data_vlan']);
					$updated = $this->cabinet->editCabinet($cabinetOld['id'], $cabinet['newName'], $locationId, $cabinet['data_vlan']);
					if (!$updated) {
						log_message('debug', $i.'|db error: '.json_encode($cabinet));
						$notEditedDbError[] = $cabinetsToUpdate[$i];
					} else {
						if ($this->USEAPIMYSQL) {
							$apiConn = $this->checkSecondaryMysqlConnection($this->APIMYSQLHOST, $this->APIMYSQLDATABASE, $this->APIMYSQLUSERNAME, $this->APIMYSQLPASSWORD);
							if ($apiConn === false) {
								log_message('debug', $i.'|no connection to api db: '.json_encode($cabinet));
								//revert update
								$reverted = $this->cabinet->editCabinet($cabinetOld['id'], $cabinetOld['name'], $locationId, $cabinetOld['data_vlan']);
								log_message('debug', $i.'|reverted update: '.($reverted ? 'success' : 'failed'));
								$notEditedDbError[] = $cabinetsToUpdate[$i];
							} else {
								$updatedSecondary = $this->cabinet->editCabinetWithNameSecondary($apiConn, 
									$cabinetOld['name'], $cabinet['newName'], $locationId, $cabinet['data_vlan']);
								if ($updatedSecondary) {
									$mark = [];
									if ($cabinetsToUpdate[$i][1] != '') {
										$mark['newName'] = true;
									}
									if ($cabinetsToUpdate[$i][2] != '') {
										$mark['homing_bng'] = true;
									}
									if ($cabinetsToUpdate[$i][3] != '') {
										$mark['data_vlan'] = true;
									}
									$markers[] = $mark;
									$cabinetsToUpdate[$i][1] = $cabinetsToUpdate[$i][1] != '' ? $cabinetsToUpdate[$i][1] : $cabinetOld['name'];
									$cabinetsToUpdate[$i][2] = $cabinetsToUpdate[$i][2] != '' ? $cabinetsToUpdate[$i][2] : $cabinetOld['homing_bng'];
									$cabinetsToUpdate[$i][3] = $cabinetsToUpdate[$i][3] != '' ? $cabinetsToUpdate[$i][3] : $cabinetOld['data_vlan'];
									$edited[] = $cabinetsToUpdate[$i];
									if ($sysuser != $this->SUPERUSER) {
										$this->sysuseractivitylog->logCabinetModification($cabinetOld['id'], $cabinet['newName'], $locationId, $cabinet['data_vlan'], 
											$sysuser, $sysuserIP, time());
									}
								} else {
									log_message('debug', $i.'|unable to update at api db: '.json_encode($cabinet));
									//revert update
									$reverted = $this->cabinet->editCabinet($cabinetOld['id'], $cabinetOld['name'], $locationId, $cabinetOld['data_vlan']);
									log_message('debug', $i.'|reverted update: '.($reverted ? 'success' : 'failed'));
									$notEditedDbError[] = $cabinetsToUpdate[$i];
								}
							}
						} else {
							$mark = [];
							if ($cabinetsToUpdate[$i][1] != '') {
								$mark['newName'] = true;
							}
							if ($cabinetsToUpdate[$i][2] != '') {
								$mark['homing_bng'] = true;
							}
							if ($cabinetsToUpdate[$i][3] != '') {
								$mark['data_vlan'] = true;
							}
							$markers[] = $mark;
							$cabinetsToUpdate[$i][1] = $cabinetsToUpdate[$i][1] != '' ? $cabinetsToUpdate[$i][1] : $cabinetOld['name'];
							$cabinetsToUpdate[$i][2] = $cabinetsToUpdate[$i][2] != '' ? $cabinetsToUpdate[$i][2] : $cabinetOld['homing_bng'];
							$cabinetsToUpdate[$i][3] = $cabinetsToUpdate[$i][3] != '' ? $cabinetsToUpdate[$i][3] : $cabinetOld['data_vlan'];
							$edited[] = $cabinetsToUpdate[$i];
							if ($sysuser != $this->SUPERUSER) {
								$this->sysuseractivitylog->logCabinetModification($cabinetOld['id'], $cabinet['newName'], $locationId, $cabinet['data_vlan'], 
									$sysuser, $sysuserIP, time());
							}
						}
					}
				}
				log_message('debug', '__________________________________________________________________________________');
				log_message('debug', 'edited|'.json_encode($edited));
				log_message('debug', 'invalidHomingBng|'.json_encode($notEditedInvalidHomingBng));
				log_message('debug', 'existingNewName|'.json_encode($notEditedDuplicateName));
				log_message('debug', 'dbError|'.json_encode($notEditedDbError));
				$data = array(
					'step' => 'modify',
					'edited' => $edited,
					'markers' => $markers,
					'existingNewName' => $notEditedDuplicateName,
					'invalidHomingBng' => $notEditedInvalidHomingBng,
					'disallowedCharacter' => $notEditedHasDisallowedCharacter,
					'dbError' => $notEditedDbError);
				$this->load->view('cabinet_bulk_modify', $data);
			}
		/**************************************************
		 * main/processBulkDeleteCabinets accessed via url
		 **************************************************/
		} else {
			$this->load->view('cabinet_bulk_modify');
		}
	}
	public function processBulkDeleteCabinets($step = null) {
		$this->redirectIfNoAccess('Manage Cabinets', 'main/processBulkDeleteCabinets');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('cabinet');
		/**************************************************
		 * main/processBulkDeleteCabinets accessed via form
		 **************************************************/
		if (is_null($step)) {
			$step = $this->input->post('step');
			if ($step == 'upload') {
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx';
				$config['max_size'] = '50000';
				$this->load->library('upload', $config);
				/**************************************************
				 * upload failed
				 **************************************************/
				if (!$this->upload->do_upload('file')) {
					$data['step'] = 'upload';
					$data['error'] = 'Upload failed: '.$this->upload->display_errors();
					$this->load->view('cabinet_bulk_delete', $data);
				/**************************************************
				 * file uploaded
				 **************************************************/
				} else {
					$this->load->model('util');
					$uploaded = $this->upload->data();
					log_message('info', 'UPLOADED FILE: '.json_encode($uploaded));
					$valid = $this->util->verifyBulkDeleteCabinetFormat($uploaded['full_path']);
					if (!$valid) {
						$data['step'] = 'upload';
						$data['error'] = 'Invalid file contents';
						$this->load->view('cabinet_bulk_delete', $data);
					} else {
						$rows = $this->util->verifyBulkDeleteCabinetData($uploaded['full_path']);
						log_message('info', 'ROWS: '.json_encode($rows));
						$validCabinets = [];
						$cabinetIdsToDelete = [];
						$dneCabinets = [];
						$rowCount = count($rows['valid']);
						for ($i = 0; $i < $rowCount; $i++) {
							$cabinetObj = $this->cabinet->getCabinetWithName(strtolower($rows['valid'][$i][0]));
							log_message('debug', $i.'|'.json_encode($cabinetObj));
							if ($cabinetObj === false || empty($cabinetObj)) {
								$dneCabinets[] = $rows['valid'][$i][0];
							} else {
								$validCabinets[] = $cabinetObj;
								$cabinetIdsToDelete[] = intval($cabinetObj['id']);
							}
						}
						log_message('debug', json_encode($cabinetIdsToDelete));
						$data = array(
							'step' => 'confirm',
							'path' => $uploaded['full_path'],
							'valid' => $validCabinets,
							'idsToDelete' => $cabinetIdsToDelete,
							'invalid' => $rows['invalid'],
							'dne' => $dneCabinets);
						$this->load->view('cabinet_bulk_delete', $data);
					}
				}
			} else if ($step == 'delete') {
				$path = $this->input->post('path');
				$idsToDelete = unserialize($this->input->post('idsToDelete'));
				$deletedRows = [];
				$notDeletedDbError = [];
				$idsToDeleteCount = count($idsToDelete);
				for ($i = 0; $i < $idsToDeleteCount; $i++) {
					$cabinetId = $idsToDelete[$i];
					$cabinet = $this->cabinet->getCabinetWithId($cabinetId);
					$deleted = $this->cabinet->removeCabinet($cabinetId);
					if ($deleted) {
						if ($this->USEAPIMYSQL) {
							$apiConn = $this->checkSecondaryMysqlConnection($this->APIMYSQLHOST, $this->APIMYSQLDATABASE, $this->APIMYSQLUSERNAME, $this->APIMYSQLPASSWORD);
							if ($apiConn === false) {
								//revert delete
								log_message('debug', 'no connection to api db: '.json_encode($cabinet));
								$reInserted = $this->cabinet->addCabinet($cabinet['name'], $cabinet['bng_id'], $cabinet['data_vlan']);
								log_message('debug', 'reinserted: '.($reInserted ? 'success' : 'failed'));
								$notDeletedDbError[] = $cabinet;
							} else {
								$deletedSecondary = $this->cabinet->removeCabinetWithNameSecondary($apiConn, $cabinet['name']);
								if ($deletedSecondary) {
									$deletedRows[] = $cabinet;
									if ($sysuser != $this->SUPERUSER) {
										$this->sysuseractivitylog->logCabinetDeletion($cabinet['name'], $sysuser, $sysuserIP, time());
									}
								} else {
									//revert delete
									log_message('debug', 'failed to delete at api db: '.json_encode($cabinet));
									$reInserted = $this->cabinet->addCabinet($cabinet['name'], $cabinet['bng_id'], $cabinet['data_vlan']);
									log_message('debug', 'reinserted: '.($reInserted ? 'success' : 'failed'));
									$notDeletedDbError[] = $cabinet;
								}
							}
						} else {
							$deletedRows[] = $cabinet;
							if ($sysuser != $this->SUPERUSER) {
								$this->sysuseractivitylog->logCabinetDeletion($cabinet['name'], $sysuser, $sysuserIP, time());
							}
						}
					} else {
						$notDeletedDbError[] = $cabinet;
					}
					/*
					if ($deleted) {
						$deletedRows[] = $cabinet;
						if ($sysuser != $this->SUPERUSER) {
							$this->sysuseractivitylog->logCabinetDeletion($cabinet['name'], $sysuser, $sysuserIP, time());
						}
					} else {
						$notDeletedDbError[] = $cabinet;
					}
					*/
				}
				$data = array(
					'step' => 'delete',
					'deleted' => $deletedRows,
					'notDeleted' => $notDeletedDbError);
				$this->load->view('cabinet_bulk_delete', $data);
			}
		/**************************************************
		 * main/processBulkDeleteCabinets accessed via url
		 **************************************************/
		} else {
			$this->load->view('cabinet_bulk_delete');
		}
	}
	/***********************************************************************
	 * manage locations
	 ***********************************************************************/
	public function showLocationsIndex($link = 0, $order = null, $location = null, $nasName = null, $nasIp = null, $rmLocation = null, $nasCode = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Manage Locations', 'main/showLocationsIndex');
		$submit = '';
		$pages = 0;
		if (intval($link) == 0) { //via form
			log_message('info', 'via location form');
			$location = $this->input->post('location');
			$nasName = $this->input->post('nasName');
			$nasIp = $this->input->post('nasIp');
			$rmLocation = $this->input->post('rmLocation');
			$nasCode = $this->input->post('nasCode');
			$location = trim($location) == '' ? null : $location;
			$nasName = trim($nasName) == '' ? null : $nasName;
			$nasIp = trim($nasIp) == '' ? null : $nasIp;
			$rmLocation = trim($rmLocation) == '' ? null : $rmLocation;
			$nasCode = trim($nasCode) == '' ? null : $nasCode;
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$submit = $this->input->post('btnRefresh');
			$start = $submit == 'refresh' ? 0 : $start;
			$order = $this->input->post('order');
			$orderParts = explode('-', $order);
			$order = array('column' => $orderParts[0], 'dir' => $orderParts[1]);
		} else { //via link
			log_message('info', 'via location link');
			if ($order == null) {
				$order = array('column' => 'l.location', 'dir' => 'asc');
			} else {
				$parts = explode('-', $order);
				$order = array('column' => $parts[0], 'dir' => $parts[1]);
			}
			$location = is_null($location) ? $location : ($location == 'null' ? null : str_replace('~~', ' ', $location));
			$nasName = is_null($nasName) ? $nasName : ($nasName == 'null' ? null : str_replace('~~', ' ', $nasName));
			$nasIp = is_null($nasIp) ? $nasIp : ($nasIp == 'null' ? null : str_replace('~~', ' ', $nasIp));
			$rmLocationTmp = str_replace('~~', ' ', $rmLocation);
			$rmLocation = is_null($rmLocation) ? $rmLocation : ($rmLocation == 'null' ? null : str_replace('---', '#', $rmLocation));
			$nasCode = is_null($nasCode) ? $nasCode : ($nasCode == 'null' ? null : str_replace('~~', ' ', $nasCode));
		}
		$this->load->model('cabinet');
		$locations = $this->cabinet->getLocationsV2($location, $nasName, $nasIp, $rmLocation, $nasCode, $start, $max, $order);
		$count = $this->cabinet->countLocations($location, $nasName, $nasIp, $rmLocation, $nasCode);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		log_message('info', 'ORDER|'.json_encode($order));
		log_message('info', 'LOCATION|'.json_encode($location));
		log_message('info', 'NAS NAME|'.json_encode($nasName));
		log_message('info', 'NAS IP|'.json_encode($nasIp));
		log_message('info', 'RM LOCATION|'.json_encode($rmLocation));
		log_message('info', 'NAS CODE|'.json_encode($nasCode));
		log_message('info', 'START|'.json_encode($start));
		log_message('info', 'MAX|'.json_encode($max));
		log_message('info', 'LOCATIONS|'.json_encode($locations));
		log_message('info', 'PAGES|'.json_encode($pages));
		log_message('info', 'COUNT|'.json_encode($count));
		$data = array(
			'locations' => $locations,
			'count' => $count,
			'start' => $start,
			'max' => $max,
			'pages' => $pages,
			'order' => $order,
			'location' => $location,
			'nasName' => $nasName,
			'nasIp' => $nasIp,
			'rmLocation' => $rmLocation,
			'nasCode' => $nasCode);
		$this->load->view('locations_index', $data);
	}
	public function showLocationCreateForm() {
		$this->redirectIfNoAccess('Manage Locations', 'main/showLocationCreateForm');
		// $this->load->model('cabinet');
		$this->load->view('locations_create');
	}
	public function processLocationCreation() {
		$this->redirectIfNoAccess('Manage Locations', 'main/processLocationCreation');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('cabinet');
		$this->load->model('sysuseractivitylog');
		$location = $this->input->post('location');
		$nasName = $this->input->post('nasName');
		$nasIp = $this->input->post('nasIp');
		$nasCode = $this->input->post('nasCode');
		$nasDescription = $this->input->post('nasDescription');
		$rmLocation = $this->input->post('rmLocation');
		$rmDescription = $this->input->post('rmDescription');
		$data = array(
			'location' => $location,
			'nasName' => $nasName,
			'nasIp' => $nasIp,
			'nasCode' => $nasCode,
			'nasDescription' => $nasDescription,
			'rmLocation' => $rmLocation,
			'rmDescription' => $rmDescription);
		$locationWithLocationName = $this->cabinet->getLocationWithLocationName($location);
		if (!empty($locationWithLocationName)) {
			$data['error'] = 'NAS Tag \''.$location.'\' already exists.';
			$this->load->view('locations_create', $data);
			return;
		}
		$locationWithNasName = $this->cabinet->getLocationWithNasName($nasName);
		if (!empty($locationWithNasName)) {
			$data['error'] = 'NAS Name \''.$nasName.'\' already exists.';
			$this->load->view('locations_create', $data);
			return;
		}
		$locationWithNasIp = $this->cabinet->getLocationWithNasIp($nasIp);
		if (!empty($locationWithNasIp)) {
			$data['error'] = 'NAS IP \''.$nasIp.'\' already exists.';
			$this->load->view('locations_create', $data);
			return;
		}
		//capitalize some variables
		$nasName = strtoupper($nasName);
		$nasIp = strtoupper($nasIp);
		$nasCode = strtoupper($nasCode);
		$nasDescription = strtoupper($nasDescription);
		$rmLocation = strtoupper($rmLocation);
		$rmDescription = strtoupper($rmDescription);
		$result = $this->cabinet->addLocation($location, $nasName, $nasIp, $nasDescription, $rmLocation, $rmDescription, $nasCode);
		if ($result) {
			if ($this->USEAPIMYSQL) {
				$apiConn = $this->checkSecondaryMysqlConnection($this->APIMYSQLHOST, $this->APIMYSQLDATABASE, $this->APIMYSQLUSERNAME, $this->APIMYSQLPASSWORD);
				if ($apiConn === false) {
					//revert creation
					$deleted = $this->cabinet->removeLocationWithName($location);
					$data['error'] = 'No connection to API database.';
					$this->load->view('locations_create', $data);
					return;
				}
				$resultSecondary = $this->cabinet->addLocationSecondary($apiConn, $location, $nasName, $nasIp, $nasDescription, $rmLocation, $rmDescription, $nasCode); 	
				if ($resultSecondary) {
					if ($sysuser != $this->SUPERUSER) {
						$this->sysuseractivitylog->logLocationCreation($location, $nasName, $nasIp, $nasCode, $nasDescription, $rmLocation, $rmDescription, 
							$sysuser, $sysuserIP, time());
					}
					$data['message'] = 'Location '.$location.' ('.$nasName.', '.$nasIp.') added.';
				} else {
					//revert creation
					$deleted = $this->cabinet->removeLocationWithName($location);
					$data['error'] = 'Unable to create cabinet at API database.';
				}
			} else {
				if ($sysuser != $this->SUPERUSER) {
					$this->sysuseractivitylog->logLocationCreation($location, $nasName, $nasIp, $nasCode, $nasDescription, $rmLocation, $rmDescription, 
						$sysuser, $sysuserIP, time());
				}
				$data['message'] = 'Location '.$location.' ('.$nasName.', '.$nasIp.') added.';
			}
		} else {
			$data['error'] = 'Unable to create Location.';
		}
		$this->load->view('locations_create', $data);
	}
	public function showLocationModifyForm($id = 0) {
		$this->redirectIfNoAccess('Manage Locations', 'main/showLocationModifyForm');
		$this->load->model('cabinet');
		if (intval($id) == 0) {
			$data = array(
				'error' => 'Did not select a Location to modify.');
			$this->load->view('locations_modify', $data);
			return;
		}
		$location = $this->cabinet->getLocationWithId($id);
		if (empty($location)) {
			$data = array(
				'error' => 'Could not find Location with id = '.$id);
			$this->load->view('vodparams_modify', $data);
			return;
		}
		$data = array(
			'location' => $location['location'],
			'nasName' => $location['nas_name'],
			'nasIp' => $location['nas_ip'],
			'nasCode' => $location['nas_code'],
			'nasDescription' => $location['nas_description'],
			'rmLocation' => $location['rm_location'],
			'rmDescription' => $location['rm_description'],
			'locationId' => $location['id']);
		$this->load->view('locations_modify', $data);
	}
	public function processLocationModification() {
		$this->redirectIfNoAccess('Manage Locations', 'main/processLocationModification');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('cabinet');
		$this->load->model('sysuseractivitylog');
		$locationId = $this->input->post('locationId');
		$location = $this->input->post('location');
		$nasName = $this->input->post('nasName');
		$nasIp = $this->input->post('nasIp');
		$nasCode = $this->input->post('nasCode');
		$nasDescription = $this->input->post('nasDescription');
		$rmLocation = $this->input->post('rmLocation');
		$rmDescription = $this->input->post('rmDescription');
		$data = array(
			'location' => $location,
			'nasName' => $nasName,
			'nasIp' => $nasIp,
			'nasCode' => $nasCode,
			'nasDescription' => $nasDescription,
			'rmLocation' => $rmLocation,
			'rmDescription' => $rmDescription,
			'locationId' => $locationId);
		$locationWithLocationName = $this->cabinet->getLocationWithLocationName($location);
		if (!empty($locationWithLocationName) && intval($locationId) != intval($locationWithLocationName['id'])) {
			$data['error'] = 'Location with NAS tag \''.$location.'\' is already used.';
			$this->load->view('locations_modify', $data);
			return;
		}
		$locationWithNasName = $this->cabinet->getLocationWithNasName($nasName);
		if (!empty($locationWithNasName) && intval($locationId) != intval($locationWithNasName['id'])) {
			$data['error'] = 'Location with NAS name \''.$nasName.'\' is already used.';
			$this->load->view('locations_modify', $data);
			return;
		}
		$locationWithNasIp = $this->cabinet->getLocationWithNasIp($nasIp);
		if (!empty($locationWithNasIp) && intval($locationId) != intval($locationWithNasIp['id'])) {
			$data['error'] = 'Location with NAS IP \''.$nasIp.'\' is already used.';
			$this->load->view('locations_modify', $data);
			return;
		}
		$original = $this->cabinet->getLocationWithId($locationId);
		$result = $this->cabinet->editLocation($locationId, $location, $nasName, $nasIp, $nasDescription, $rmLocation, $rmDescription, $nasCode);
		if ($result) {
			if ($this->USEAPIMYSQL) {
				$apiConn = $this->checkSecondaryMysqlConnection($this->APIMYSQLHOST, $this->APIMYSQLDATABASE, $this->APIMYSQLUSERNAME, $this->APIMYSQLPASSWORD);
				if ($apiConn === false) {
					//revert update
					$reverted = $this->cabinet->editLocation($locationId, $original['location'], $original['nas_name'], $original['nas_ip'], $original['nas_description'],
						$original['rm_location'], $original['rm_description'], $original['nas_code']);
					$data['error'] = 'No connection to API database.';
					$this->load->view('locations_modify', $data);
					return;
				}
				$locationIdSecondary = $this->cabinet->getLocationIdSecondary($apiConn, $original['location']);
				$resultSecondary = $this->cabinet->editLocationSecondary($apiConn, $locationIdSecondary, $location, $nasName, $nasIp, $nasDescription, 
					$rmLocation, $rmDescription, $nasCode);
				if ($resultSecondary) {
					if ($sysuser != $this->SUPERUSER) {
						$this->sysuseractivitylog->logLocationModification($locationId, $location, $nasName, $nasIp, $nasCode, $nasDescription, $rmLocation, $rmDescription, 
							$sysuser, $sysuserIP, time());
					}
					$data['message'] = 'Changes saved.';
				} else {
					//revert update
					$reverted = $this->cabinet->editLocation($locationId, $original['location'], $original['nas_name'], $original['nas_ip'], $original['nas_description'],
						$original['rm_location'], $original['rm_description'], $original['nas_code']);
					$data['error'] = 'Unable to update location at API database.';
				}
			} else {
				if ($sysuser != $this->SUPERUSER) {
					$this->sysuseractivitylog->logLocationModification($locationId, $location, $nasName, $nasIp, $nasCode, $nasDescription, $rmLocation, $rmDescription, 
						$sysuser, $sysuserIP, time());
				}
				$data['message'] = 'Changes saved.';
			}
		} else {
			$data['error'] = 'Unable to save changes.';
		}
		$this->load->view('locations_modify', $data);
	}
	public function deleteLocationProcess() {
		if ($this->input->is_ajax_request()) {
			/***********************************************
			 * get these from session variables
			 ***********************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$this->load->model('cabinet');
			$this->load->model('sysuseractivitylog');
			$locationId = $this->input->post('id');
			$location = $this->cabinet->getLocationWithId($locationId);
			$result = $this->cabinet->removeLocation($locationId);
			if ($result) {
				if ($this->USEAPIMYSQL) {
					$apiConn = $this->checkSecondaryMysqlConnection($this->APIMYSQLHOST, $this->APIMYSQLDATABASE, $this->APIMYSQLUSERNAME, $this->APIMYSQLPASSWORD);
					if ($apiConn === false) {
						log_message('debug', 'No connection to API database.');
						//revert delete
						$reInserted = $this->cabinet->addLocation($location['location'], $location['nas_name'], $location['nas_ip'], $location['nas_description'], 
							$location['rm_location'], $location['rm_description'], $location['nas_code']);
						$newLocationObj = $this->cabinet->getLocationWithLocationName($location['location']);
						echo json_encode(array('status' => '0', 'error' => 'No connection to API database.', 'id' => $newLocationObj['id']));
					} else {
						$locationIdSecondary = $this->cabinet->getLocationIdSecondary($apiConn, $location['location']);
						$resultSecondary = $this->cabinet->removeLocationSecondary($apiConn, $locationIdSecondary);
						if ($resultSecondary) {
							if ($sysuser != $this->SUPERUSER) {
								$this->sysuseractivitylog->logLocationDeletion($location['location'], $location['nas_name'], $location['nas_ip'], $location['nas_code'], 
									$location['nas_description'], $location['rm_location'], $location['rm_description'], $sysuser, $sysuserIP, time());
							}
							echo json_encode(array('status' => '1'));
						} else {
							log_message('debug', 'Unable to delete location at API database');
							//revert delete
							$reInserted = $this->cabinet->addLocation($location['location'], $location['nas_name'], $location['nas_ip'], $location['nas_description'], 
								$location['rm_location'], $location['rm_description'], $location['nas_code']);
							$newLocationObj = $this->cabinet->getLocationWithLocationName($location['location']);
							echo json_encode(array('status' => '0', 'error' => 'Unable to delete location at API database.', 'id' => $newLocationObj['id']));
						}
					}					
				} else {
					$this->sysuseractivitylog->logLocationDeletion($location['location'], $location['nas_name'], $location['nas_ip'], $location['nas_code'], 
									$location['nas_description'], $location['rm_location'], $location['rm_description'], $sysuser, $sysuserIP, time());
					echo json_encode(array('status' => '1'));
				}
			} else {
				echo json_encode(array('status' => '0'));
			}
		} else {
			redirect('main/shoLocationsIndex/1');
		}
	}
	/***********************************************************************
	 * manage vod params
	 ***********************************************************************/
	public function showVodparamsIndex($link = 0, $order = null, $oldVod = null, $newName = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Manage Vod Params', 'main/showVodparamsIndex');
		$submit = '';
		$pages = 0;
		if (intval($link) == 0) { //via form
			log_message('info', 'via vod param form');
			$oldVod = $this->input->post('oldVod');
			$newName = $this->input->post('newName');
			$oldVod = trim($oldVod) == '' ? null : $oldVod;
			$newName = trim($newName) == '' ? null : $newName;
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$submit = $this->input->post('btnRefresh');
			$start = $submit == 'refresh' ? 0 : $start;
			$order = $this->input->post('order');
			$orderParts = explode('-', $order);
			$order = array('column' => $orderParts[0], 'dir' => $orderParts[1]);
		} else { //via link
			log_message('info', 'via vod param link');
			if ($order == null) {
				$order = array('column' => 'old_vod', 'dir' => 'asc');
			} else {
				$parts = explode('-', $order);
				$order = array('column' => $parts[0], 'dir' => $parts[1]);
			}
			$oldVod = is_null($oldVod) ? $oldVod : ($oldVod == 'null' ? null : $oldVod);
			$newName = is_null($newName) ? $newName : ($newName == 'null' ? null : $newName);
		}
		$this->load->model('vodparams');
		$vodparams = $this->vodparams->getVodparams($oldVod, $newName, $start, $max, $order);
		$count = $this->vodparams->countVodparams($oldVod, $newName);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		log_message('info', 'ORDER|'.json_encode($order));
		log_message('info', 'OLD VOD|'.json_encode($oldVod));
		log_message('info', 'NEW NAME|'.json_encode($newName));
		log_message('info', 'START|'.json_encode($start));
		log_message('info', 'MAX|'.json_encode($max));
		log_message('info', 'PARAMS|'.json_encode($vodparams));
		log_message('info', 'PAGES|'.json_encode($pages));
		log_message('info', 'COUNT|'.json_encode($count));
		$data = array(
			'vodparams' => $vodparams,
			'count' => $count,
			'start' => $start,
			'max' => $max,
			'pages' => $pages,
			'order' => $order,
			'oldVod' => $oldVod,
			'newName' => $newName);
		$this->load->view('vodparams_index', $data);
	}
	public function showVodparamCreateForm() {
		$this->redirectIfNoAccess('Manage Vod Params', 'main/showVodparamCreateForm');
		// $this->load->model('vodparams');
		$this->load->view('vodparams_create');
	}
	public function processVodparamCreation() {
		$this->redirectIfNoAccess('Manage Vod Params', 'main/processVodparamCreation');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');
		
		$this->load->model('vodparams');
		$this->load->model('sysuseractivitylog');
		$oldVod = $this->input->post('oldVod');
		$newName = $this->input->post('newName');
		$now = date('Y-m-d H:i:s', time());
		$data = array(
			'oldVod' => $oldVod,
			'newName' => $newName);
		$param = $this->vodparams->getVodparamWithOldVod($oldVod);
		if (!empty($param)) {
			$data['error'] = 'Old VOD name '.$oldVod.' already exists.';
			$this->load->view('vodparams_create', $data);
			return;
		}
		$result = $this->vodparams->addVodparam($oldVod, $newName);
		if ($result) {
			if ($this->USEAPIMYSQL) {
				$apiConn = $this->checkSecondaryMysqlConnection($this->APIMYSQLHOST, $this->APIMYSQLDATABASE, $this->APIMYSQLUSERNAME, $this->APIMYSQLPASSWORD);
				if ($apiConn === false) {
					//revert creation
					$deleted = $this->vodparams->removeVodparamWithName($oldVod);
					$data['error'] = 'No connection to API database';
					$this->load->view('vodparams_create', $data);
					return;
				}
				$resultSecondary = $this->vodparams->addVodparamSecondary($apiConn, $oldVod, $newName);
				if ($resultSecondary) {
					if ($sysuser != $this->SUPERUSER) {
						$this->sysuseractivitylog->logVodparamCreation($oldVod, $newName, $sysuser, $sysuserIP, time());
					}
					$data['message'] = 'VOD param '.$oldVod.' / '.$newName.' added.';
				} else {
					//revert creation
					$deleted = $this->vodparams->removeVodparamWithName($oldVod);
					$data['error'] = 'Unable to create VOD param at API database.';
				}
			} else {
				if ($sysuser != $this->SUPERUSER) {
					$this->sysuseractivitylog->logVodparamCreation($oldVod, $newName, $sysuser, $sysuserIP, time());
				}
				$data['message'] = 'VOD param '.$oldVod.' / '.$newName.' added.';
			}
		} else {
			$data['error'] = 'Unable to create VOD param.';
		}
		$this->load->view('vodparams_create', $data);
	}
	public function showVodparamModifyForm($id = 0) {
		$this->redirectIfNoAccess('Manage Vod Params', 'main/showVodparamModifyForm');
		$this->load->model('vodparams');
		if (intval($id) == 0) {
			$data = array(
				'error' => 'Did not select a VOD param to modify.');
			$this->load->view('vodparams_modify', $data);
			return;
		}
		$param = $this->vodparams->getVodparamWithId($id);
		if (empty($param)) {
			$data = array(
				'error' => 'Could not find VOD param with id = '.$id);
			$this->load->view('vodparams_modify', $data);
			return;
		}
		$data = array(
			'oldVod' => $param['old_vod'],
			'newName' => $param['new_name'],
			'paramId' => $param['id']);
		$this->load->view('vodparams_modify', $data);
	}
	public function processVodparamModification() {
		$this->redirectIfNoAccess('Manage Vod Params', 'main/processVodparamModification');
		/************************************************
		 * get sysuser from session variable
		 ************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('vodparams');
		$this->load->model('sysuseractivitylog');
		$paramId = $this->input->post('paramId');
		$oldVod = $this->input->post('oldVod');
		$newName = $this->input->post('newName');
		$data = array(
			'oldVod' => $oldVod,
			'newName' => $newName,
			'paramId' => $paramId);
		$vodparam = $this->vodparams->getVodparamWithOldVod($oldVod);
		$original = $this->vodparams->getVodparamWithId($paramId);
		if (!empty($vodparam) && strtolower($oldVod) != strtolower($original['old_vod'])) {
			$data['error'] = 'Old VOD name '.$oldVod.' is already used.';
			$this->load->view('vodparams_modify', $data);
			return;
		}
		$result = $this->vodparams->editVodparam($paramId, $oldVod, $newName);
		if ($result) {
			if ($this->USEAPIMYSQL) {
				$apiConn = $this->checkSecondaryMysqlConnection($this->APIMYSQLHOST, $this->APIMYSQLDATABASE, $this->APIMYSQLUSERNAME, $this->APIMYSQLPASSWORD);
				if ($apiConn === false) {
					//revert update
					$reverted = $this->vodparams->editVodparam($original['id'], $original['old_vod'], $original['new_name']);
					$data['error'] = 'No connection to API database.';
					$this->load->view('vodparams_modify', $data);
					return;
				}
				$vodparamIdSecondary = $this->vodparams->getVodparamIdSecondary($apiConn, $original['old_vod']);
				$resultSecondary = $this->vodparams->editVodparamSecondary($apiConn, $vodparamIdSecondary, $oldVod, $newName);
				if ($resultSecondary) {
					if ($sysuser != $this->SUPERUSER) {
						$this->sysuseractivitylog->logVodparamModification($paramId, $oldVod, $newName, $sysuser, $sysuserIP, time());
					}
					$data['message'] = 'Changes saved.';
				} else {
					$reverted = $this->vodparams->editVodparam($original['id'], $original['old_vod'], $original['new_name']);
					$data['error'] = 'Unable to update cabinet at API database.';
				}
			} else {
				if ($sysuser != $this->SUPERUSER) {
					$this->sysuseractivitylog->logVodparamModification($paramId, $oldVod, $newName, $sysuser, $sysuserIP, time());
				}
				$data['message'] = 'Changes saved.';
			}
		} else {
			$data['error'] = 'Unable to save changes.';
		}
		$this->load->view('vodparams_modify', $data);
	}
	public function deleteVodparamProcess() {
		if ($this->input->is_ajax_request()) {
			/***********************************************
			 * get these from session variables
			 ***********************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			$this->load->model('vodparams');
			$this->load->model('sysuseractivitylog');
			$paramId = $this->input->post('id');
			$vodparam = $this->vodparams->getVodparamWithId($paramId);
			$result = $this->vodparams->removeVodparam($paramId);
			if ($result) {
				if ($this->USEAPIMYSQL) {
					$apiConn = $this->checkSecondaryMysqlConnection($this->APIMYSQLHOST, $this->APIMYSQLDATABASE, $this->APIMYSQLUSERNAME, $this->APIMYSQLPASSWORD);
					if ($apiConn === false) {
						//revert delete
						$reInserted = $this->vodparams->addVodparam($vodparam['old_vod'], $vodparam['new_name']);
						$newVodparamObj = $this->vodparams->getVodparamWithOldVod($vodparam['old_vod']);
						echo json_encode(array('status' => '0', 'error' => 'No connection to API database.', 'id' => $newVodparamObj['id']));
					} else {
						$vodparamIdSecondary = $this->vodparams->getVodparamIdSecondary($apiConn, $vodparam['old_vod']);
						$resultSecondary = $this->vodparams->removeVodparamSecondary($apiConn, $vodparamIdSecondary);
						if ($resultSecondary) {
							if ($sysuser != $this->SUPERUSER) {
								$this->sysuseractivitylog->logVodparamDeletion($vodparam['old_vod'], $vodparam['new_name'], $sysuser, $sysuserIP, time());
							}
							echo json_encode(array('status' => '1'));
						} else {
							//revert delete
							$reInserted = $this->vodparams->addVodparam($vodparam['old_vod'], $vodparam['new_name']);
							$newVodparamObj = $this->vodparams->getVodparamWithOldVod($vodparam['old_vod']);
							echo json_encode(array('status' => '0', 'error' => 'No connection to API database.', 'id' => $newVodparamObj['id']));
						}
					}
				} else {
					if ($sysuser != $this->SUPERUSER) {
						$this->sysuseractivitylog->logVodparamDeletion($vodparam['old_vod'], $vodparam['new_name'], $sysuser, $sysuserIP, time());
					}
					echo json_encode(array('status' => '1'));
				}
			} else {
				echo json_encode(array('status' => '0'));
			}
		} else {
			redirect('main/showVodparamsIndex/1');
		}
	}
	/***********************************************************************
	 * manage network addresses
	 * PAGEID = 27
	 ***********************************************************************/
	public function displayQuota(){
		$this->redirectIfNoAccess('Display Quota', 'main/displayQuota');
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$data = array(
			'realms' => $realms,
			'allowBlankInRealm' => false);
		$this->load->view('display_quota', $data);	
	}
	public function displayQuotaDo(){
		$this->redirectIfNoAccess('Display Quota', 'main/displayQuotaDo');
		$this->load->model('realm');
		$this->load->model('onlinesession');
		$this->load->model('excludedplan');
		$excludedplans = $this->excludedplan->fetchAllNamesOnly();
		$username = $this->input->post('user');
		$realm = $this->input->post('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$this->load->model('subscribermain');
		$subscriber = $this->subscribermain->findByUserIdentity($username.'@'.$realm);

		if ($subscriber === false) {
			$data = array(
				'found' => false,
				'user' => $username,
				'realm' => $realm,
				'realms' => $realms,
				'error' => 'User '.$username.'@'.$realm.' not found.');
			$this->load->view('display_quota', $data);
			return;
		} else {
			// $usage = array('responseCode' => 200, 'replyMessage' => 'Success', 'plan' => 'AL~15M~150G', 'volumeQuota' => 161061273600, 'volumeUsage' => 16786984448, 
			// 	'vodExpiry' => null, 'vodQuota' => null, 'vodUsage' => null);
			try {
				$checkQm = false;
				$plan = str_replace('~', '-', $subscriber['RADIUSPOLICY']);
				$this->load->library('detour');
				$detourClient = new SoapClient('http://'.$this->DETOURAPIHOST.':'.$this->DETOURAPIPORT.'/'.$this->DETOURAPISTUB);
				$usageTmp = $this->detour->getVolumeUsage($username.'@'.$realm, $detourClient);
				$usage = array(
					'responseCode' => $usageTmp['responseCode'],
					'replyMessage' => $usageTmp['replyMessage'],
					'PLAN' => $usageTmp['plan'],
					'VOLUMEUSAGE' => $usageTmp['volumeUsage'],
					'VOLUMEQUOTA' => $usageTmp['volumeQuota'],
					'vodQuota' => $usageTmp['vodQuota'],
					'vodUsage' => $usageTmp['vodUsage'],
					'vodExpiry' => $usageTmp['vodExpiry']);
				log_message('debug', 'checkQm:'.json_encode($checkQm).',usage via api ('.$username.'@'.$realm.'):'.json_encode($usage));
				if (intval($usage['responseCode']) != 200 && $checkQm) { //will never go here because $checkQm is fixed to false
					$usage = $this->onlinesession->getVolumeUsageData($username, $realm, $plan, $this->SESSIONHOST2, $this->SESSIONPORT2, $this->SESSIONSCHEMA2,
						$this->SESSIONUSERNAME2, $this->SESSIONPASSWORD2);
					log_message('info', 'usage via tblvolumeusage ('.$username.'@'.$realm.') :'.json_encode($usage));	
				}
			} catch (Exception $e) {
				log_message('debug', 'error @ displayQuotaDo:'.json_encode($e));
				unset($usage);
			}
			$throttleData = false;
			$data = array(
				'found' => true,
				'realms' => $realms,
				'realm' => $realm,
				'allowBlankInRealm' => false,
				'user' => $username,
				'subscriber' => $subscriber,
				'usage' => $usage,
				'excludedplans' => $excludedplans,
				'eventDate' => isset($usage) ? $throttleData : false);
			$this->load->view('display_quota', $data);
		}
	}
	/***********************************************************************
	 * popup windows
	 * 
	 ***********************************************************************/
	public function showAssignIPv6Form($username = null, $realm = null, $location = null, $cabinetForDropdown = null, $ipv6 = null, $subnet = null, $cabinet = null) {
		$this->load->model('ipaddress');
		$this->load->model('cabinet');
		$username = $username == '-' ? '' : $username;
		$realm = $realm == '-' ? '' : $realm;
		$location = $location == '-' ? null : $location;
		$location = str_replace('~', ' ', $location);
		$cabinet = $cabinet == '-' ? null : $cabinet;
		$cabinet = str_replace('~', ' ', $cabinet);
		$cabinetForDropdown = $cabinetForDropdown == '-' ? null : $cabinetForDropdown;
		$cabinetForDropdown = str_replace('~', ' ', $cabinetForDropdown);
		$ipv6addresses = $this->ipaddress->fetchAllUnusedV6($location);
		$locations = $this->ipaddress->fetchAllLocationsFromExtras();
		$cabinets = $this->cabinet->getCabinets();
		$location = is_null($location) ? '' : $location;
		$cabinet = is_null($cabinet) ? '' : $cabinet;
		$cabinetForDropdown = is_null($cabinetForDropdown) ? '' : $cabinetForDropdown;
		$data = array(
			'username' => $username,
			'realm' => $realm,
			'location' => $location,
			'locations' => $locations,
			'cabinets' => $cabinets,
			'cabinetForDropdown' => $cabinetForDropdown,
			'ipv6' => $ipv6,
			'subnet' => $subnet,
			'cabinet' => $cabinet,
			'ipv6addresses' => $ipv6addresses);
		$this->load->view('assign_ipv6_address', $data);
	}
	public function showAssignIPForm($username = null, $realm = null, $location = null, $isGPON = 'N', $cabinetForDropdown = null, $ip = null, $cabinet = null) {
		$this->load->model('ipaddress');
		$this->load->model('cabinet');
		$username = $username == '-' ? '' : $username;
		$realm = $realm == '-' ? '' : $realm;
		$location = $location == '-' ? null : $location;
		$location = str_replace('~', ' ', $location);
		$cabinet = $cabinet == '-' ? null : $cabinet;
		$cabinet = str_replace('~', ' ', $cabinet);
		$cabinetForDropdown = $cabinetForDropdown == '-' ? null : $cabinetForDropdown;
		$cabinetForDropdown = str_replace('~', ' ', $cabinetForDropdown);
		$ipaddresses = $this->ipaddress->fetchAllUnused($location, $isGPON);
		$locations = $this->ipaddress->fetchAllLocationsFromExtras();
		$cabinets = $this->cabinet->getCabinets();
		$location = is_null($location) ? '' : $location;
		$cabinet = is_null($cabinet) ? '' : $cabinet;
		$cabinetForDropdown = is_null($cabinetForDropdown) ? '' : $cabinetForDropdown;
		$data = array(
			'username' => $username,
			'realm' => $realm,
			'location' => $location,
			'isgpon' => $isGPON,
			'locations' => $locations,
			'cabinets' => $cabinets,
			'cabinetForDropdown' => $cabinetForDropdown,
			'ip' => $ip,
			'cabinet' => $cabinet,
			'ipaddresses' => $ipaddresses);
		$this->load->view('assign_ip_address', $data);
	}
	public function showAssignNetForm($username = null, $realm = null, $location = null, $cabinetForDropdown = null, $ip = null, $subnet = null, $cabinet = null, $pickSubnet = null) {
		$this->load->model('netaddress');
		$this->load->model('ipaddress');
		$this->load->model('cabinet');

		log_message('debug', 'location:'.json_encode($location).', cabinetForDropdown:'.json_encode($cabinetForDropdown).', ip:'.json_encode($ip).', subnet:'.json_encode($subnet).
			', cabinet:'.json_encode($cabinet).', pickSubnet:'.json_encode($pickSubnet));

		$username = $username == '-' ? '' : $username;
		$realm = $realm == '-' ? '' : $realm;
		$location = $location == '-' ? null : str_replace('~', ' ', $location);
		$cabinet = $cabinet == '-' ? null : str_replace('~', ' ', $cabinet);
		$cabinetForDropdown = $cabinetForDropdown == '-' ? null : str_replace('~', ' ', $cabinetForDropdown);
		$pickSubnet = $pickSubnet == '-' ? null : str_replace('~', ' ', $pickSubnet);

		// $subnetOptions = $this->netaddress->SUBNETS;
		$subnetOptions = $this->netaddress->fetchAvailableSubnets($this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
		log_message('debug', '                                         ');
		log_message('debug', '                                         ');
		log_message('debug', json_encode($subnetOptions));
		$locations = $this->ipaddress->fetchAllLocationsFromExtras();
		$cabinets = $this->cabinet->getCabinets();

		if (is_null($location) && is_null($cabinet) && is_null($cabinetForDropdown) && is_null($pickSubnet)) {
			$netaddresses = array();
		} else {
			$netaddresses = $this->netaddress->fetchAllUnusedWithLocationAndSubnet($location, $pickSubnet);
		}

		$location = is_null($location) ? '' : $location;
		$cabinet = is_null($cabinet) ? '' : $cabinet;
		$cabinetForDropdown = is_null($cabinetForDropdown) ? '' : $cabinetForDropdown;
		$pickSubnet = is_null($pickSubnet) ? '' : $pickSubnet;

		$data = array(
			'username' => $username,
			'realm' => $realm,
			'location' => $location,
			'locations' => $locations,
			'cabinets' => $cabinets,
			'cabinetForDropdown' => $cabinetForDropdown,
			'ip' => $ip,
			'subnet' => $subnet,
			'cabinet' => $cabinet,
			'subnetOptions' => $subnetOptions,
			'pickSubnet' => $pickSubnet,
			'netaddresses' => $netaddresses);
		$this->load->view('assign_net_address', $data);
	}
	public function showAssignNetFormOld($username = null, $realm = null, $location = null, $cabinetForDropdown = null, $ip = null, $subnet = null, $cabinet = null) {
		$this->load->model('netaddress');
		$this->load->model('ipaddress');
		$this->load->model('cabinet');
		$username = $username == '-' ? '' : $username;
		$realm = $realm == '-' ? '' : $realm;
		$location = $location == '-' ? null : $location;
		$location = str_replace('~', ' ', $location);
		$cabinet = $cabinet == '-' ? null : $cabinet;
		$cabinet = str_replace('~', ' ', $cabinet);
		$cabinetForDropdown = $cabinetForDropdown == '-' ? null : $cabinetForDropdown;
		$cabinetForDropdown = str_replace('~', ' ', $cabinetForDropdown);
		$netaddresses = $this->netaddress->fetchAllUnused($location);
		$locations = $this->ipaddress->fetchAllLocationsFromExtras();
		$cabinets = $this->cabinet->getCabinets();
		$location = is_null($location) ? '' : $location;
		$cabinet = is_null($cabinet) ? '' : $cabinet;
		$cabinetForDropdown = is_null($cabinetForDropdown) ? '' : $cabinetForDropdown;
		$data = array(
			'username' => $username,
			'realm' => $realm,
			'location' => $location,
			'locations' => $locations,
			'cabinets' => $cabinets,
			'cabinetForDropdown' => $cabinetForDropdown,
			'ip' => $ip,
			'subnet' => $subnet,
			'cabinet' => $cabinet,
			'netaddresses' => $netaddresses);
		$this->load->view('assign_net_address_old', $data);
	}
	public function findIpAddressToAssign() {
		if ($this->input->is_ajax_request()) {
			$ip = $this->input->post('ip');
			$this->load->model('ipaddress');
			$ipaddress = $this->ipaddress->findIpAddress($ip, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			if ($ipaddress === false || empty($ipaddress) || is_null($ipaddress)) {
				$data = array('found' => 0);
			} else {
				$this->load->model('cabinet');
				$cabinet = $this->cabinet->getCabinetWithLocation($ipaddress['LOCATION']);
				$data = array(
					'found' => 1,
					'used' => $ipaddress['IPUSED'],
					'gpon' => $ipaddress['GPONIP'],
					'location' => $ipaddress['LOCATION'],
					'cabinetId' => !isset($cabinet) || empty($cabinet) ? '' : $cabinet['id'],
					'cabinetForDropdown' => !isset($cabinet) || empty($cabinet) ? '' : $cabinet['name']);
			}
			echo json_encode($data);
		} else {
			redirect('main/showAssignIPForm');
		}
	}
	public function findCabinetToAssign() {
		if ($this->input->is_ajax_request()) {
			$cabinetName = $this->input->post('cabinet');
			$cabinetName = str_replace('~', ' ', $cabinetName);
			$this->load->model('cabinet');
			$cabinet = $this->cabinet->getCabinetWithName($cabinetName);
			if ($cabinet === false || empty($cabinet) || is_null($cabinet)) {
				$data = array('found' => 0);
			} else {
				$data = array(
					'found' => 1,
					'cabinetId' => $cabinet['id'],
					'cabinetForDropdown' => $cabinet['name'],
					'pickSubnet' => '-',
					'location' => $cabinet['homing_bng']);
			}
			echo json_encode($data);
		} else {
			redirect('main/showAssignIPForm');
		}
	}
	public function findNetAddressToAssign() {
		if ($this->input->is_ajax_request()) {
			$ip = $this->input->post('ip');
			$subnet = $this->input->post('subnet');
			$this->load->model('netaddress');
			$netaddress = $this->netaddress->findNetAddress($ip.'/'.$subnet, 
				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			if ($netaddress === false || empty($netaddress) || is_null($netaddress)) {
				$data = array('found' => 0);
			} else {
				$this->load->model('cabinet');
				$cabinet = $this->cabinet->getCabinetWithLocation($netaddress['LOCATION']);
				$data = array(
					'found' => 1,
					'used' => $netaddress['NETUSED'],
					'location' => $netaddress['LOCATION'],
					'cabinetId' => !isset($cabinet) || empty($cabinet) ? '' : $cabinet['id'],
					'cabinetForDropdown' => !isset($cabinet) || empty($cabinet) ? '' : $cabinet['name'],
					'pickSubnet' => $subnet,
					'ip' => $ip,
					'subnet' => $subnet);
			}
			echo json_encode($data);
		} else {
			redirect('main/showAssignNetForm');
		}
	}
	public function findIpv6AddressToAssign() {
		if ($this->input->is_ajax_request()) {
			$ipv6 = $this->input->post('ipv6');
			$ipv6 = str_replace('~', ':', $ipv6);
			$this->load->model('ipaddress');
			$ipv6address = $this->ipaddress->findIpV6Address($ipv6, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			if ($ipv6address === false || empty($ipv6address) || is_null($ipv6address)) {
				$data = array('found' => 0);
			} else {
				$this->load->model('cabinet');
				$cabinet = $this->cabinet->getCabinetWithLocation($ipv6address['LOCATION']);
				$data = array(
					'found' => 1,
					'used' => $ipv6address['IPV6USED'],
					'location' => $ipv6address['LOCATION'],
					'cabinetId' => !isset($cabinet) || empty($cabinet) ? '' : $cabinet['id'],
					'cabinetForDropdown' => !isset($cabinet) || empty($cabinet) ? '' : $cabinet['name']);
			}
			echo json_encode($data);
		} else {
			redirect('main/showAssignIPv6Form');
		}
	}
	/*
	public function findCabinetToAssignNet() {
		if ($this->input->is_ajax_request()) {
			$cabinetName = $this->input->post('cabinet');
			$this->load->model('cabinet');
			$cabinet = $this->cabinet->getCabinetWithName($cabinetName);
			if ($cabinet === false || empty($cabinet) || is_null($cabinet)) {
				$data = array('found' => 0);
			} else {
				$data = array(
					'found' => 1,
					'cabinetId' => $cabinet['id'])
			}
		} else {
			redirect('main/showAssignNetForm');
		}
	}
	*/
	public function checkTimeout() {
		if ($this->input->is_ajax_request()) {
			log_message('info', '_____@checkTimeout');
			$username = $this->session->userdata('username');
			$portal = $this->session->userdata('portal');
			log_message('info', 'portal:'.$portal.'|username:'.$username);
			//skip checking if su
			if ($username == $this->SUPERUSER) {
				$data = array(
					'expired' => '0',
					'logged_out' => '0');
				echo json_encode($data);
				return;
			}

			$this->load->model('sysusermain');
			$sessionFromDb = $username !== false ? $this->sysusermain->getSysuserSession($username) : null;
			if (is_null($sessionFromDb)) {
				$this->session->set_userdata('logged_out', 'You have been logged out from the system.');
				$this->session->unset_userdata('username');
				$data = array(
					'expired' => '1',
					'logged_out' => '1',
					'redirect' => $portal);
				echo json_encode($data);
				return;
			}
			$now = time();
			$this->load->model('sysusermain');
			$this->load->model('sysuseractivitylog');
			$this->load->model('subscriberaudittrail');
			//check with time from update in sysuser_activity_log table
			$lastActivity = $this->sysuseractivitylog->getLastActionDate($username);
			log_message('info', '_____last activity (sysuserlog):'.json_encode($lastActivity));
			if ($lastActivity !== false) {
				$parts = explode(' ', $lastActivity['timestamp']); //2014-03-27 05:28:33
				$date = $parts[0];
				$dateParts = explode('-', $date);
				$time = $parts[1];
				$timeParts = explode(':', $time);
				$last = mktime($timeParts[0], $timeParts[1], $timeParts[2], $dateParts[1], $dateParts[2], $dateParts[0]);
				$elapsed = date_diff(date_create(date('Y-m-d H:i:s', $now)), date_create(date('Y-m-d H:i:s', $last)));
				$hours = $elapsed->format('%h');
				$mins = $elapsed->format('%I');
				$minspassed = (intval($hours) * 60 + intval($mins));
				$fromlastactivity = (intval($hours) * 60 + intval($mins));
				log_message('info', 'now:'.date('Y-m-d H:i:s', $now));
				log_message('info', 'last activity:'.date('Y-m-d H:i:s', $last));
				log_message('info', 'elapsed:'.$hours.' hours, '.$mins.' mins'.'|'.$minspassed);
				if ($minspassed < $this->SESSIONEXPIRYINMINUTES) {
					$data = array(
						'expired' => '0',
						'logged_out' => '0', //-->this is used when someone else logs out another sysuser
						'redirect' => $portal,
						'fromlastactivity' => $fromlastactivity,
						'fromlastaudittrail' => 'n/a',
						'fromlogin' => 'n/a');
					echo json_encode($data);
					return;
				}
			}
			//check with time from update in subscriber_audittrail table
			$lastaudittrail = $this->subscriberaudittrail->getLastActionDate($username);
			log_message('info', '_____last activity (audittrail):'.json_encode($lastaudittrail));
			if ($lastaudittrail !== false) {
				$parts = explode(' ', $lastaudittrail['timestamp']); //2014-03-27 05:28:33
				$date = $parts[0];
				$dateParts = explode('-', $date);
				$time = $parts[1];
				$timeParts = explode(':', $time);
				$last = mktime($timeParts[0], $timeParts[1], $timeParts[2], $dateParts[1], $dateParts[2], $dateParts[0]);
				$elapsed = date_diff(date_create(date('Y-m-d H:i:s', $now)), date_create(date('Y-m-d H:i:s', $last)));
				$hours = $elapsed->format('%h');
				$mins = $elapsed->format('%I');
				$minspassed = (intval($hours) * 60 + intval($mins));
				$fromlastaudittrail = (intval($hours) * 60 + intval($mins));
				log_message('info', 'now:'.date('Y-m-d H:i:s', $now));
				log_message('info', 'last audittrail:'.date('Y-m-d H:i:s', $last));
				log_message('info', 'elapsed:'.$hours.' hours, '.$mins.' mins'.'|'.$minspassed);
				if ($minspassed < $this->SESSIONEXPIRYINMINUTES) {
					$data = array(
						'expired' => '0',
						'logged_out' => '0', //-->this is used when someone else logs out another sysuser
						'redirect' => $portal,
						'fromlastactivity' => $fromlastactivity,
						'fromlastaudittrail' => $fromlastaudittrail,
						'fromlogin' => 'n/a');
					echo json_encode($data);
					return;
				}
			}
			//check with time from login
			$loginTime = $this->sysusermain->getSysuserLoginTime($username);
			log_message('info', '___login time:'.json_encode($loginTime));
			log_message('info', json_encode($loginTime)); //11-APR-14 12.37.35.000000 PM
			$months = array(
				'jan' => 1,
				'feb' => 2,
				'mar' => 3,
				'apr' => 4,
				'may' => 5,
				'jun' => 6,
				'jul' => 7,
				'aug' => 8,
				'sep' => 9,
				'oct' => 10,
				'nov' => 11,
				'dec' => 12);
			$parts = explode(' ', strtolower($loginTime));
			$date = $parts[0];
			$dateParts = explode('-', $date);
			$time = $parts[1];
			$timeParts = explode('.', $time);
			$h = intval($timeParts[0]);
			$h = $parts[2] == 'pm' && $h != 12 ? $h + 12 : $h;
			$h = $parts[2] == 'am' && $h == 12 ? 0 : $h;
			$last = mktime($h, $timeParts[1], $timeParts[2], $months[$dateParts[1]], $dateParts[0], intval($dateParts[2]) > 50 ? intval('19'.$dateParts[2]) : intval('20'.$dateParts[2]));
			$elapsed = date_diff(date_create(date('Y-m-d H:i:s', $now)), date_create(date('Y-m-d H:i:s', $last)));
			$hours = $elapsed->format('%h');
			$mins = $elapsed->format('%I');
			$minspassed = (intval($hours) * 60) + intval($mins);
			$fromlogin = (intval($hours) * 60) + intval($mins);
			log_message('info', 'now:'.date('Y-m-d H:i:s', $now));
			log_message('info', 'last login:'.date('Y-m-d H:i:s', $last));
			log_message('info', 'elapsed:'.$hours.' hours, '.$mins.' mins'.'|'.$minspassed);
			if ($minspassed < $this->SESSIONEXPIRYINMINUTES) {
				$data = array(
					'expired' => '0',
					'logged_out' => '0', //-->this is used when someone else logs out another sysuser
					'redirect' => $portal,
					'fromlastactivity' => $fromlastactivity,
					'fromlastaudittrail' => isset($fromlastaudittrail) ? $fromlastaudittrail : 'n/a',
					'fromlogin' => $fromlogin);
				echo json_encode($data);
				return;
			}

			//if handle reaches this point, it has been more than the set time since last update in both sysuser_activity_log and subscriber_audittrail, and since login
			log_message('info', '_____expired');
			$this->sysusermain->setSysuserLoggedOut($username);
			$this->sysusermain->setSysuserSession($username, null);
			$this->load->model('sysuser');
			$this->sysuser->recordSysuserLogout($username, $this->session->userdata('session'), time());
			$this->session->sess_destroy();
			$this->session->set_userdata('session_expired', 'You have been logged out because your session has expired.');
			$data = array(
				'expired' => '1',
				'logged_out' => '0', //-->this is used when someone else logs out another sysuser
				'redirect' => $portal,
				'fromlastactivity' => $fromlastactivity,
				'fromlastaudittrail' => isset($$fromlastaudittrail) ? $fromlastaudittrail : 'n/a',
				'fromlogin' => $fromlogin);
			echo json_encode($data);
		} else {
			redirect('main/noAccess');
		}
	}
	public function noAccess() {
		if ($this->session->userdata('logged_out') !== false) {
			$portal = $this->session->userdata('portal');
			$this->load->view('no_access', array('reload_parent' => '1', 'portal' => $portal));
		} else {
			$this->load->view('no_access');
		}
	}

	/***********************************************************************
	 * RUN ONLY ONCE
	 * 
	 ***********************************************************************/
	public function generateSystemPages() {
		$this->load->model('page');
		$count = $this->page->countPages();
		if ($count == 0) {
			$this->page->create('Search Online Session');
			$this->page->create('Search Concurrent Session');
			$this->page->create('Account Usages');
			$this->page->create('Account Transaction');
			$this->page->create('Verify Account Password');
			$this->page->create('Usages');
			$this->page->create('Usage by IP Address');
			$this->page->create('Services');
			$this->page->create('Authentication Log');
			$this->page->create('Change My Password');
			$this->page->create('Create Primary Account');
			$this->page->create('Create Secondary Account');
			$this->page->create('Display Account');
			$this->page->create('Modify Account');
			$this->page->create('Delete Account');
			$this->page->create('Report Generation');
			$this->page->create('Search');
			$this->page->create('Reset Subscriber Password');
			$this->page->create('Change Subscriber Password');
			$this->page->create('System User Account');
			$this->page->create('System IP Account');
			$this->page->create('Realm');
			$this->page->create('Realm User Account');
			$this->page->create('Change Account Password');
			$this->page->create('System User Group');
			$this->page->create('Manage IP Addresses');
			$this->page->create('Manage Network Addresses');
			$this->page->create('System User Activity Logs');
			$this->page->create('System Account Usages');
			$this->page->create('Modify Account (Admin)');
			echo 'Pages generated.';
		} else {
			echo 'Pages already generated.';
		}
	}

	public function generateSystemTimes() {
		$this->load->model('systime');
		$count = $this->systime->countTimes();
		if ($count == 0) {
			$this->systime->create(0, '12:00 AM');
			$this->systime->create(1, '1:00 AM');
			$this->systime->create(2, '2:00 AM');
			$this->systime->create(3, '3:00 AM');
			$this->systime->create(4, '4:00 AM');
			$this->systime->create(5, '5:00 AM');
			$this->systime->create(6, '6:00 AM');
			$this->systime->create(7, '7:00 AM');
			$this->systime->create(8, '8:00 AM');
			$this->systime->create(9, '9:00 AM');
			$this->systime->create(10, '10:00 AM');
			$this->systime->create(11, '11:00 AM');
			$this->systime->create(12, '12:00 PM');
			$this->systime->create(13, '1:00 PM');
			$this->systime->create(14, '2:00 PM');
			$this->systime->create(15, '3:00 PM');
			$this->systime->create(16, '4:00 PM');
			$this->systime->create(17, '5:00 PM');
			$this->systime->create(18, '6:00 PM');
			$this->systime->create(19, '7:00 PM');
			$this->systime->create(20, '8:00 PM');
			$this->systime->create(21, '9:00 PM');
			$this->systime->create(22, '10:00 PM');
			$this->systime->create(23, '11:00 PM');
			$this->systime->create(31, 'Sunday');
			$this->systime->create(32, 'Monday');
			$this->systime->create(33, 'Tuesday');
			$this->systime->create(34, 'Wednesday');
			$this->systime->create(35, 'Thursday');
			$this->systime->create(36, 'Friday');
			$this->systime->create(37, 'Saturday');
			echo 'Available time periods generated.';
		} else {
			echo 'System times already generated.';
		}
	}
	public function generateSystemRealms() {

	}
	public function createsu($username = 'vinadmin', $password = 'password', $rolename = 'VINADMIN') {
		$this->load->model('sysusermain');
		$this->load->model('tbldslbucket');
		$this->load->model('page');
		$this->load->model('role');
		$this->load->model('realm');
		$this->load->model('systime');
		$suRolename = $rolename;
		$suUsername = $username;
		$suPassword = $password;
		//create role
		$this->role->create($suRolename, 'supergroup');
		$role = $this->role->fetchByName($suRolename);
		//allow all realms
		$realms = $this->realm->fetchAll(null, null, null);
		for ($i = 0; $i < count($realms); $i++) {
			$realm = $realms[$i];
			$this->tbldslbucket->addRealmToRole($role['ROLEID'], $realm['REALMID']);
		}
		//allow all pages
		$pages = $this->page->fetchAll();
		for ($i = 0; $i < count($pages); $i++) {
			$page = $pages[$i];
			$this->tbldslbucket->addPageToRole($role['ROLEID'], $page['PAGEID']);
		}
		//set time to allow all
		$times = $this->systime->fetchAll();
		for ($i = 0; $i < count($times); $i++) {
			$time = $times[$i];
			$this->tbldslbucket->addTimeToRole($role['ROLEID'], $time['TIMEID']);
		}

		//create su account
		$this->sysusermain->create($suUsername, $suPassword, 'su last name', 'su first name', 'su@email.com', $role['ROLEID']);
		echo 'su role: '.$suRolename.' created<br />';
		echo 'su account: '.$suUsername.'/'.$suPassword.' created';
	}
	public function createu($username = 'test', $password = 'password') {
		$this->load->model('sysusermain');
		$this->sysusermain->create($username, $password, 'u last name', 'u first name', 'u@email.com', 27);
		echo 'done';
	}

	public function checkSecondaryMysqlConnection($host, $database, $username, $password) {
		$apiMysqlConn = new mysqli($host, $username, $password, $database);
		if ($apiMysqlConn->connect_error) {
			return false;
		}
		return $apiMysqlConn;
	}

}
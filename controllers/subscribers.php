<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscribers extends CI_Controller {
	private $SUPERUSER = 'vinadmin';
	private $SUPERGROUP = 'VINADMIN';
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
	private $RMAPIHOST = '10.81.54.34';
	private $RMAPIPORT = '8089';
	private $RMAPISTUB = 'policydesigner/services/SubscriberProvisioningService?wsdl';
	private $VODAPIHOST = '10.166.12.11';
	private $VODAPIPORT = '8089';
	private $VODAPISTUB = 'rmapi/SubscriberVodServices?wsdl';
	private $USESECONDARYRMAPI = true;
	private $RMSECONDARYAPIHOST = '10.81.54.35';
	private $RMSECONDARYAPIPORT = '8089';
	private $RMSECONDARYAPISTUB = 'policydesigner/services/SubscriberProvisioningService?wsdl';
	private $USESECONDARYRMDB = true;
	private $RMSECONDARYDBHOST = '10.81.54.39';
	private $RMSECONDARYDBPORT = '1521';
	private $RMSECONDARYDBSCHEMA = 'rmextwoXDB';
	private $RMSECONDARYDBUSERNAME = 'rm';
	private $RMSECONDARYDBPASSWORD = 'rm';
	private $xlsRowLimit = 5000;
	/**************************************************
	 * to enable ipv6 address, set $useIPv6 to true
	 **************************************************/
	private $useIPv6 = true;
	private $netAddressSubnetMarker = '/';
	private $useSeparateSubnetForNetAddress = false;

	public function __construct(){
	    parent::__construct();
	    $this->load->model('settings');
	    $cfg = $this->settings->loadFromFile();
	    if (!is_null($cfg)) {
	    	$this->SUPERUSER = $cfg['SUPERUSER'];
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
			$this->RMAPIHOST = strval($cfg['RMAPIHOST']);
			$this->RMAPIPORT = strval($cfg['RMAPIPORT']);
			$this->RMAPISTUB = strval($cfg['RMAPISTUB']);
			$this->VODAPIHOST = strval($cfg['VODAPIHOST']);
			$this->VODAPIPORT = strval($cfg['VODAPIPORT']);
			$this->VODAPISTUB = strval($cfg['VODAPISTUB']);
			$this->USESECONDARYRMAPI = intval($cfg['USESECONDARYRMAPI']) == 1 ? true : false;
			$this->RMSECONDARYAPIHOST = strval($cfg['RMSECONDARYAPIHOST']);
			$this->RMSECONDARYAPIPORT = strval($cfg['RMSECONDARYAPIPORT']);
			$this->RMSECONDARYAPISTUB = strval($cfg['RMSECONDARYAPISTUB']);
			$this->USESECONDARYRMDB = intval($cfg['USESECONDARYRMDB']) == 1 ? true : false;
			$this->RMSECONDARYDBHOST = strval($cfg['RMSECONDARYDBHOST']);
			$this->RMSECONDARYDBPORT = strval($cfg['RMSECONDARYDBPORT']);
			$this->RMSECONDARYDBSCHEMA = strval($cfg['RMSECONDARYDBSCHEMA']);
			$this->RMSECONDARYDBUSERNAME = strval($cfg['RMSECONDARYDBUSERNAME']);
			$this->RMSECONDARYDBPASSWORD = strval($cfg['RMSECONDARYDBPASSWORD']);
	    }
	}

	/***********************************************************************
	 * create primary subscriber account
	 * PAGEID = 11
	 ***********************************************************************/
	public function showCreateSubscriberForm() {
		$this->redirectIfNoAccess('Create Primary Account', 'subscribers/showCreateSubscriberForm');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		//get services
		$this->load->model('services');
		// $allServices = $this->services->fetchAllNamesOnly2();
		$allServices = $this->services->fetchAllNamesOnlyNew($this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
		//get realms
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@showCreateSubscriberForm|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@showCreateSubscriberForm|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		$data = array(
			'allServicesFilter' => $allServices,
			'realms' => $realms,
			'message' => '',
			'proceed' => $checks['go'],
			'error' => $checks['msg']);
		if ($portal == 'service') {
			$data['realm'] = $realm;
			$data['disableRealm'] = true;
		}
		$data['useIPv6'] = $this->useIPv6;
		$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
		$this->load->view('create_user_account', $data);
	}
	public function processCreateSubscriber() {
		$this->redirectIfNoAccess('Create Primary Account', 'subscribers/processCreateSubscriber');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		//get services
		$this->load->model('services');
		// $allServices = $this->services->fetchAllNamesOnly2();
		$allServices = $this->services->fetchAllNamesOnlyNew($this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
		//get realms
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@processCreateSubscriber|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@processCreateSubscriber|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		$realm = $this->input->post('realm');
		$username = trim($this->input->post('username'));
		$cn = $username.'@'.$realm;
		$password = trim($this->input->post('password1'));
		// Disable dependency 5/17/19
		$acctplan = "";//$this->input->post('acctplan');
		// Default to Active 6/28/19
		$status = "Active"; //$this->input->post('status'); //'Active' or 'InActive'
		$custname = trim($this->input->post('custname'));
		$ordernum = trim($this->input->post('ordernum'));
		$servicenum = trim($this->input->post('servicenum'));
		$nonedsl = ""; //$this-svccode>input->post('nonedsl'); //'Yes' or 'No'
		$svccode = "";//str_replace('~', '-', $this->input->post('svccode'));
		$ipv6address = trim($this->input->post('ipv6address'));
		$ipaddress = trim($this->input->post('ipaddress'));
		$netaddress = trim($this->input->post('netaddress'));
		$svc_add1 = $this->input->post('svc_add1');
		$svc_add2 = $this->input->post('svc_add2');
		$remarks = trim($this->input->post('remarks'));
		$data = array(
			'allServicesFilter' => $allServices,
			'proceed' => $checks['go'],
			'realm' => $realm,
			'realms' => $realms,
			'username' => $username,
			'password' => $password,
			'acctplan' => $acctplan, //'Residential' or 'Business'
			'status' => $status,
			'custname' => $custname,
			'ordernum' => $ordernum,
			'servicenum' => $servicenum,
			'nonedsl' => $nonedsl, //'Yes' or 'No'
			'svccode' => $svccode,
			'ipv6address' => $ipv6address,
			'ipaddress' => $ipaddress,
			'netaddress' => $netaddress,
			'svc_add1' => $svc_add1,
			'svc_add2' => $svc_add2,
			'remarks' => $remarks);
		$data['useIPv6'] = $this->useIPv6;
		if ($portal == 'service') {
			$data['realm'] = $realm;
			$data['disableRealm'] = true;
		}
		if ($checks['go'] == false) {
			$data['error'] = $checks['msg'];
			$data['useIPv6'] = $this->useIPv6;
			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
			$this->load->view('create_user_account', $data);
			return;
		}
		$this->load->model('subscribermain');
		$this->load->model('ipaddress');
		$this->load->model('netaddress');
		/**************************************************
		 * check if username has uppercase characters
		 **************************************************/
		$usernameHasUppercase = preg_match('/[A-Z]{1}/', $username);
		if ($usernameHasUppercase) {
			$data['error'] = 'Account cannot be created. Username must not have uppercase characters.';
			$data['useIPv6'] = $this->useIPv6;
			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
			$this->load->view('create_user_account', $data);
			return;
		}
		/**************************************************
		 * check if username has special characters
		 **************************************************/
		$hasSpecialChars = preg_match('/[^a-zA-Z0-9._-]/', $username);
		if ($hasSpecialChars) {
			$data['error'] = 'Account cannot be created. Special characters found in username.';
			$data['useIPv6'] = $this->useIPv6;
			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
			$this->load->view('create_user_account', $data);
			return;
		}
		/**************************************************
		 * check if provided service number is already used
		 **************************************************/
		$serviceNumberExists = $this->subscribermain->serviceNumberExists($servicenum,
			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
		if ($serviceNumberExists) {
			$data['error'] = 'Account cannot be created. The '.$servicenum.' service number already exists in the database.';
			$data['useIPv6'] = $this->useIPv6;
			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
			$this->load->view('create_user_account', $data);
			return;
		}
		/**************************************************
		 * check if username length > 70
		 **************************************************/
		if (strlen($cn) > 70) {
			$data['error'] = 'Username (including realm) length exceeds the maximum (70) characters';
			$data['useIPv6'] = $this->useIPv6;
			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
			$this->load->view('create_user_account', $data);
			return;
		}
		/**************************************************
		 * check if username is already used
		 **************************************************/
		$exists = $this->subscribermain->subscriberExists($cn, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
		/**************************************************
		 * username is already used, return error
		 **************************************************/
		if ($exists) {
			$data['error'] = 'Account cannot be created. The username '.$cn.' already exists in the database.';
			$data['useIPv6'] = $this->useIPv6;
			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
			$this->load->view('create_user_account', $data);
		/**************************************************
		 * username can be used, proceed
		 **************************************************/
		} else {
			/**************************************************
			 * get this from one of the session variables
			 * $sysuser = $this->session->userdata('username');
			 **************************************************/
			$sysuser = $this->session->userdata('username');
			$sysuserIP = $this->session->userdata('ip_address');

			if ($status == 'T') {
				$ipaddress = '';
				$netaddress = '';
			}
			$createddate = date('Y-m-d H:i:s', time());
			$subscriber = array(
				'USER_IDENTITY' => $cn,
				'USERNAME' => $cn,
				'BANDWIDTH' => null,
				'CUSTOMERSTATUS' => $status,
				'PASSWORD' => $password,
				'CUSTOMERREPLYITEM' => null,
				'CREATEDATE' => $createddate,
				'LASTMODIFIEDDATE' => null,
				'RBCUSTOMERNAME' => $custname,
				'RBCREATEDBY' => $sysuser,
				'RBADDITIONALSERVICE5' => null,
				'RBADDITIONALSERVICE4' => $ipv6address == '' ? null : $ipv6address,
				'RBADDITIONALSERVICE3' => null,
				'RBADDITIONALSERVICE2' => $svc_add2 == '' ? null : $svc_add2,
				'RBADDITIONALSERVICE1' => $svc_add1 == '' ? null : $svc_add1,
				'RBCHANGESTATUSDATE' => null,
				'RBCHANGESTATUSBY' => null,
				'RBACTIVATEDDATE' => $status == 'Active' ? $createddate : null,
				'RBACTIVATEDBY' => $status == 'Active' ? $sysuser : null,
				'RBACCOUNTSTATUS' => 'Primary',
				'RBSVCCODE2' => null,
				'RBSVCCODE' => $svccode,
				'RADIUSPOLICY' => $svccode,
				'RBACCOUNTPLAN' => $svccode,
				'CUSTOMERTYPE' => $acctplan,
				'RBSERVICENUMBER' => $servicenum,
				'RBCHANGESTATUSFROM' => null,
				'RBSECONDARYACCOUNT' => null,
				'RBUNLIMITEDACCESS' => 1, //true
				'RBTIMESLOT' => 'Al0000-2400',
				'RBORDERNUMBER' => $ordernum == '' ? null : $ordernum,
				'RBREMARKS' => $remarks == '' ? null : $remarks,
				'RBREALM' => $realm,
				'RBNUMBEROFSESSION' => 1,
				'RBMULTISTATIC' => $netaddress == '' ? null : $netaddress,
				'RBIPADDRESS' => $ipaddress == '' ? null : $ipaddress,
				'RBENABLED' => $nonedsl);
			/**************************************************
			 * prepare value for CUSTOMERREPLYITEM
			 **************************************************/
			$subscriber['CUSTOMERREPLYITEM'] = $this->subscribermain->generateCustomerReplyItemValue(
				$subscriber['RBADDITIONALSERVICE4'], $subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC']);
			/**************************************************
			 * prepare value for RBENABLED
			 **************************************************/
			if (strtolower($subscriber['RBENABLED']) == 'yes' && strtolower($subscriber['CUSTOMERTYPE']) == 'residential') {
				//as is
			} else {
				$subscriber['RBENABLED'] = 'No';
			}
			/**************************************************
			 * check if subscriber is valid
			 **************************************************/
			$valid = $this->subscribermain->isValidNew($subscriber, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
			/**************************************************
			 * $subscriber data is invalid, return error
			 **************************************************/
			if (!$valid['status']) {
				$data['error'] = 'Account cannot be created. Some fields are either invalid or missing.';
				$data['useIPv6'] = $this->useIPv6;
				$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
				$this->load->view('create_user_account', $data);
			/**************************************************
			 * $subscriber data is valid
			 **************************************************/
			} else {
				/**************************************************
				 * check if provided ipv6 address is valid & usable, mark used
				 **************************************************/
				if (!is_null($subscriber['RBADDITIONALSERVICE4'])) {
					$ipv6Free = $this->ipaddress->isV6Free($subscriber['RBADDITIONALSERVICE4'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if (!$ipv6Free) {
						$data['error'] = 'Account creation failed. IPv6 address is already used.';
						$data['useIPv6'] = $this->useIPv6;
						$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						$this->load->view('create_user_account', $data);
					} else {
						$this->ipaddress->markV6AsUsed($subscriber['RBADDITIONALSERVICE4'], $subscriber['USERNAME'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
				}
				/**************************************************
				 * check if provided ip address is valid & usable, mark used
				 **************************************************/
				if (!is_null($subscriber['RBIPADDRESS'])) {
					$ipFree = $this->ipaddress->isFree($subscriber['RBIPADDRESS'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if (!$ipFree) {
						$data['error'] = 'Account creation failed. IP address is already used.';
						$data['useIPv6'] = $this->useIPv6;
						$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						$this->load->view('create_user_account', $data);
					} else {
						$this->ipaddress->markAsUsed($subscriber['RBIPADDRESS'], $subscriber['USERNAME'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
				}
				/**************************************************
				 * check if provided net address is valid & usable, mark used
				 **************************************************/
				if (!is_null($subscriber['RBMULTISTATIC'])) {
					$netFree = $this->netaddress->isFree($subscriber['RBMULTISTATIC'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if (!$netFree) {
						$data['error'] = 'Account creation failed. Network address is already used.';
						$data['useIPv6'] = $this->useIPv6;
						$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						$this->load->view('create_user_account', $data);
					} else {
						$this->netaddress->markAsUsed($subscriber['RBMULTISTATIC'], $subscriber['USERNAME'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
					/**************************************************
					 * set RBENABLED = 'No' if RBMULTISTATIC has value
					 **************************************************/
					log_message('info', '@load: '.$subscriber['USERNAME'].' has valid RBMULTISTATIC, setting RBENABLED to \'No\'');
					$subscriber['RBENABLED'] = 'No';
					$data['nonedsl'] = 'No';
				}
				/**************************************************
				 * uncomment the next line if RBENABLED is fixed to 'No' and FAP to 'N'
				 * FAP is dependent on the value of $subscriber['RBENABLED']: 'No' => 'N', 'Yes' => 'Y'
				 **************************************************/
				// $subscriber['RBENABLED'] = 'No';
				/**************************************************
				 * everything checks out, create account
				 **************************************************/
				$subscriber['RBREMARKS'] = date('Y-m-d H:i:s', time()).' '.$subscriber['RBREMARKS'];
				log_message('debug', '@processCreateSubscriber|subscriber:'.$subscriber['USERNAME'].'|RBENABLED:'.$subscriber['RBENABLED']);
				$result = $this->subscribermain->create($subscriber, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				log_message('info', 'RESULT:'.json_encode($result));
				/**************************************************
				 * unable to create account, return error
				 **************************************************/
				if (!$result) {
					$data['error'] = 'Account creation failed.';
					$data['useIPv6'] = $this->useIPv6;
					$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
					$this->load->view('create_user_account', $data);
				/**************************************************
				 * account created, do other stuff
				 **************************************************/
				} else {
					/**************************************************
					 * create account in npm
					 * - ENABLENPM will always be false so $npmResult['result'] will always be true (npm was removed a long time ago)
					 **************************************************/
					if ($this->ENABLENPM) {
						$subs = $this->subscribermain->npmFetchXML($cn, $this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
						/**************************************************
						 * if account already exists in npm, consider as failed
						 **************************************************/
						if ($subs['found']) {
							$npmResult['result'] = false;
							$npmResult['error'] = 'Account already exists.';
						/**************************************************
						 * create account in npm
						 **************************************************/
						} else {
							$npmResult = $this->subscribermain->npmCreateXML($subscriber['USERNAME'], $subscriber['PASSWORD'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'Y' : 'N',
								time(), str_replace('~', '-', $subscriber['RBACCOUNTPLAN']), $subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC'], 'N',
								$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
						}
					} else {
						$npmResult['result'] = true;
					}
					/**************************************************
					 * account failed to create in npm
					 **************************************************/
					if (!$npmResult['result']) {
						/**************************************************
						 * unmark ip/net if previously marked
						 **************************************************/
						if (!is_null($subscriber['RBIPADDRESS'])) {
							$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if (!is_null($subscriber['RBMULTISTATIC'])) {
							$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						/**************************************************
						 * delete subscriber
						 **************************************************/
						$deleted = $this->subscribermain->delete($subscriber['USERNAME'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						if (!$deleted) {
							$data['error'] = 'Account creation error. Unable to delete account after account creation in NPM failed.';
							$data['useIPv6'] = $this->useIPv6;
							$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
							$this->load->view('create_user_account', $data);
						} else {
							$data['error'] = 'NPM account creation failed. '.$npmResult['error'];
							$data['useIPv6'] = $this->useIPv6;
							$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
							$this->load->view('create_user_account', $data);
						}
					/**************************************************
					 * account created in npm
					 * goes here if npm is not enabled and account was created at oracle
					 **************************************************/
					} else {
						/**************************************************
						 * RM api
						 **************************************************/
						// Remove RM Dependencies 5/21/19
						// try {
						// 	$rmCh = curl_init();
						// 	curl_setopt($rmCh, CURLOPT_URL, 'http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
						// 	curl_setopt($rmCh, CURLOPT_RETURNTRANSFER, 1);
						// 	curl_setopt($rmCh, CURLOPT_CONNECTTIMEOUT, 3);
						// 	$rmOut = curl_exec($rmCh);
						// 	curl_close($rmCh);
							// if ($rmOut === false) {
							// 	$data['error'] = 'Account creation error. No connection to RM api.';
							// 	$data['useIPv6'] = $this->useIPv6;
							// 	$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
							// 	$this->load->view('create_user_account', $data);
							// 	log_message('debug', 'single create no connection to rm api, deleting account: '.$subscriber['USERNAME']);
							// 	$this->subscribermain->delete($subscriber['USERNAME'],
							// 		$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 	if (!is_null($subscriber['RBIPADDRESS'])) {
							// 		$unmark = $this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
							// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 		log_message('debug', 'unmark ip result: '.json_encode($unmark));
							// 	}
							// 	if (!is_null($subscriber['RBMULTISTATIC'])) {
							// 		$unmark = $this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
							// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 		log_message('debug', 'unmark net result: '.json_encode($unmark));
							// 	}
							// 	if (!is_null($subscriber['RBADDITIONALSERVICE4'])) {
							// 		$unmark = $this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
							// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 		log_message('debug', 'unmark ipv6 result: '.json_encode($unmark));
							// 	}
							// 	return;
							// }
							// $this->load->library('rm');
							// $rmClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
							/**************************************************
							 * if branch for handling old rm api
							 * - as of nov 18, 2016 this is no longer used
							 * - remove this later (including closing brace before the catch block)
							 **************************************************/
							/*
							if ($this->RMAPIHOST == '10.244.4.130' || $this->RMAPIHOST == '10.244.4.131') {
								$rmSubsUsername = array('key' => 'USERNAME' , 'value' => $subscriber['USERNAME']);
								$rmSubsIdentity = array('key' => 'SUBSCRIBERIDENTITY', 'value' => $subscriber['USERNAME']);
								$rmSubsCui = array('key' => 'CUI' , 'value' => $subscriber['USERNAME']);
								$rmSubsStatus = array('key' => 'SUBSCRIBERSTATUS', 'value' => 'Active');
								$rmSubsArea = array('key' => 'AREA', 'value' => $subscriber['CUSTOMERSTATUS']);
								$rmSubsPackage = array('key' => 'SUBSCRIBERPACKAGE', 'value' => str_replace('-', '~', $subscriber['RBACCOUNTPLAN']));
								$customerTypeForRM = '';
								if ($subscriber['CUSTOMERTYPE'] == 'Residential') {
									$customerTypeForRM = 'RESS';
								} else if ($subscriber['CUSTOMERTYPE'] == 'Business') {
									$customerTypeForRM = 'BUSS';
								} else {
									$customerTypeForRM = 'RESS';
								}
								$rmSubsCustomertype = array('key' => 'CUSTOMERTYPE', 'value' => $customerTypeForRM);
								$fapForRM = '';
								if ($subscriber['RBENABLED'] == 'Yes') {
									$fapForRM = 'Y';
								} else if ($subscriber['RBENABLED'] == 'No') {
									$fapForRM = 'N';
								} else {
									$fapForRM = 'N';
								}
								$rmSubsFap = array('key' => 'FAP', 'value' => $fapForRM);
								/**************************************************
								 * try inserting
								 **************************************************
								$rmParam = array($rmSubsUsername, $rmSubsIdentity, $rmSubsCui, $rmSubsStatus, $rmSubsArea, $rmSubsPackage, $rmSubsCustomertype, $rmSubsFap);
								$insertResult = $this->rm->createAccount($rmParam, $rmClient);
								if (intval($insertResult['responseCode']) == 200) {
									log_message('debug', 'create|inserted @ RM: '.$subscriber['USERNAME']);
								} else if (intval($insertResult['responseCode']) == 450) {
									log_message('debug', 'create|'.$subscriber['USERNAME'].' exists, update');
									$rmSubsPassword = array('key' => 'PASSWORD', 'value' => '');
									$rmParam = array($rmSubsStatus, $rmSubsArea, $rmSubsPackage, $rmSubsPassword, $rmSubsCustomertype, $rmSubsFap);
									$updateResult = $this->rm->updateAccount($subscriber['USERNAME'], $rmParam, $rmClient);
									if (intval($updateResult['responseCode']) == 200) {
										log_message('debug', 'create|updated @ RM: '.$subscriber['USERNAME']);
									} else {
										log_message('debug', 'create|failed to update @ RM:'.$subscriber['USERNAME'].'|'.$updateResult['responseMessage']);
										$this->subscribermain->delete($subscriber['USERNAME'],
											$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
										$data['error'] = $updateResult['responseMessage'];
										$this->load->view('create_user_account', $data);
										return;
									}
								} else {
									log_message('debug', 'create|failed to insert @ RM: '.$subscriber['USERNAME'].'|'.$insertResult['responseMessage']);
									$this->subscribermain->delete($subscriber['USERNAME'],
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									$data['error'] = $insertResult['responseMessage'];
									$this->load->view('create_user_account', $data);
									return;
								}
							} else if ($this->RMAPIHOST == '10.81.54.34' || $this->RMAPIHOST == '10.81.54.35') {
							*/
							// $rmUsername = $subscriber['USERNAME'];
							// $rmPlan = $subscriber['RADIUSPOLICY'];
							// // $rmStatus = strtoupper($subscriber['CUSTOMERSTATUS']);
							// $rmStatus = 'ACTIVE';
							// if (strtolower($subscriber['CUSTOMERTYPE']) == 'residential') {
							// 	$rmCustomerType = 'RESS';
							// } else if (strtolower($subscriber['CUSTOMERTYPE']) == 'business') {
							// 	$rmCustomerType = 'BUSS';
							// } else {
							// 	$rmCustomerType = 'RESS';
							// }
							// $fapForRM = '';
							// if ($subscriber['RBENABLED'] == 'Yes') {
							// 	$fapForRM = 'Y';
							// } else if ($subscriber['RBENABLED'] == 'No') {
							// 	$fapForRM = 'N';
							// } else {
							// 	$fapForRM = 'N';
							// }
							/**************************************************
							 * try inserting
							 **************************************************/
							// $nodes = array('PARAM3' => $fapForRM, 'AREA' => $subscriber['CUSTOMERSTATUS']);
							// log_message('debug', "rmUsername: ".$rmUsername);
							// $insertResult = $this->rm->wsAddSubscriber($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
							// log_message('debug', 'create|insertResult: '.json_encode($insertResult));
							// if (intval($insertResult['responseCode']) == 200) {
							// 	log_message('debug', 'create|inserted @ RM: '.$rmUsername);
							// } else if (intval($insertResult['responseCode']) == 450) {
							// 	log_message('debug', 'create|'.$rmUsername.' exists, update');
							// 	$updateResult = $this->rm->wsUpdateSubscriberProfile($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
							// 	log_message('debug', 'create|updateResult: '.json_encode($updateResult));
							// 	if (intval($updateResult['responseCode']) == 200) {
							// 		log_message('debug', 'create|updated @ RM: '.$rmUsername);
							// 	} else {
							// 		log_message('debug', 'create|failed to update @ RM:'.$rmUsername.'|'.$updateResult['responseCode'].": ".$updateResult['responseMessage']);
							// 		log_message('debug', 'create|RM says "already exists" to insert attempt but says "subscriber identity not found" on update attempt: '.
							// 			'username cleanup');
							// 		for ($mm = 0; $mm < 4; $mm++) {
							// 			if ($mm == 0) {
							// 				$thisUsername = $rmUsername;
							// 				$deleteResult = $this->rm->wsDeleteSubscriberProfile($rmClient, $thisUsername);
							// 				log_message('debug', 'create|delete (alternateId) result for '.$thisUsername.': '.$deleteResult['responseCode']);
							// 				$purgeResult = $this->rm->wsPurgeSubscriber($rmClient, $thisUsername);
							// 				log_message('debug', 'create|purge (alternateId) result for '.$thisUsername.': '.$purgeResult['responseCode']);
							// 				$deleteResult = $this->rm->wsDeleteSubscriberProfileV2($rmClient, $thisUsername);
							// 				log_message('debug', 'create|delete (subscriberID) result for '.$thisUsername.': '.$deleteResult['responseCode']);
							// 				$purgeResult = $this->rm->wsPurgeSubscriberV2($rmClient, $thisUsername);
							// 				log_message('debug', 'create|purge (subscriberID) result for '.$thisUsername.': '.$purgeResult['responseCode']);
							// 			} else {
							// 				$thisUsername = $rmUsername.'#L'.$mm;
							// 				$deleteResult = $this->rm->wsDeleteSubscriberProfileV2($rmClient, $thisUsername);
							// 				log_message('debug', 'create|delete (subscriberID) result for '.$thisUsername.': '.$deleteResult['responseCode']);
							// 				$purgeResult = $this->rm->wsPurgeSubscriberV2($rmClient, $thisUsername);
							// 				log_message('debug', 'create|purge (subscriberID) result for '.$thisUsername.': '.$purgeResult['responseCode']);
							// 			}
							// 		}
							// 		$insertResult = $this->rm->wsAddSubscriber($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
							// 		log_message('debug', 'create|insertResult: '.json_encode($insertResult));
							// 		if (intval($insertResult['responseCode']) == 200) {
							// 			log_message('debug', 'create|create attempt #2 successful');
							// 		} else {
							// 			log_message('debug', 'create|create attempt #2 failed: '.$insertResult['responseMessage']);
							// 			$this->subscribermain->delete($rmUsername,
							// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 			if (!is_null($subscriber['RBIPADDRESS'])) {
							// 				$unmark = $this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
							// 					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 				log_message('debug', 'unmark ip result: '.json_encode($unmark));
							// 			}
							// 			if (!is_null($subscriber['RBMULTISTATIC'])) {
							// 				$unmark = $this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
							// 					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 				log_message('debug', 'unmark net result: '.json_encode($unmark));
							// 			}
							// 			if (!is_null($subscriber['RBADDITIONALSERVICE4'])) {
							// 				$unmark = $this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
							// 					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 				log_message('debug', 'unmark ipv6 result: '.json_encode($unmark));
							// 			}
							// 			$data['error'] = $insertResult['responseMessage'];
							// 			$data['useIPv6'] = $this->useIPv6;
							// 			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
							// 			$this->load->view('create_user_account', $data);
							// 			return;
							// 		}
							// 	}
							// } else if (intval($insertResult['responseCode']) == 400) {
							// 	log_message('debug', 'responseCode = 400');
							// 	$deleteResult = $this->rm->wsDeleteSubscriberProfile($rmClient, $rmUsername);
							// 	log_message('debug', 'create|deleteResult: '.json_encode($deleteResult));
							// 	$purgeResult = $this->rm->wsPurgeSubscriber($rmClient, $rmUsername);
							// 	log_message('debug', 'create|purgeResult: '.json_encode($purgeResult));
							// 	$insertResult = $this->rm->wsAddSubscriber($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
							// 	log_message('debug', 'create|insertResult: '.json_encode($insertResult));
							// 	if (intval($insertResult['responseCode']) == 200) {
							// 		log_message('debug', 'create|create attempt #2 (from error code 400) successful');
							// 	} else {
							// 		log_message('debug', 'create|create attempt #2 (from error code 400) failed: '.$insertResult['responseMessage']);
							// 		$this->subscribermain->delete($rmUsername,
							// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 		if (!is_null($subscriber['RBIPADDRESS'])) {
							// 			$unmark = $this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
							// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 			log_message('debug', 'unmark ip result: '.json_encode($unmark));
							// 		}
							// 		if (!is_null($subscriber['RBMULTISTATIC'])) {
							// 			$unmark = $this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
							// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 			log_message('debug', 'unmark net result: '.json_encode($unmark));
							// 		}
							// 		if (!is_null($subscriber['RBADDITIONALSERVICE4'])) {
							// 			$unmark = $this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
							// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 			log_message('debug', 'unmark ipv6 result: '.json_encode($unmark));
							// 		}
							// 		$data['error'] = $insertResult['responseMessage'];
							// 		$data['useIPv6'] = $this->useIPv6;
							// 		$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
							// 		$this->load->view('create_user_account', $data);
							// 		return;
							// 	}
							// } else {
							// 	$fault = $insertResult['responseMessage'];
							// 	$toFind1 = 'ORA-00001';
							// 	$toFind2 = 'RM.SYS_C0015045';
							// 	$found1 = strpos($fault, $toFind1);
							// 	$found2 = strpos($fault, $toFind2);
							// 	if ($found1 && $found2) {
							// 		$updateResult = $this->rm->wsUpdateSubscriberProfile($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
							// 		log_message('debug', 'create|updateResult: '.json_encode($updateResult));
							// 		if (intval($updateResult['responseCode']) == 200) {
							// 			log_message('debug', 'create|updated @ RM (after INTERNAL ERROR): '.$subscriber['USERNAME']);
							// 		} else {
							// 			log_message('debug', 'create|failed to update @ RM (after INTERNAL ERROR):'.$subscriber['USERNAME'].'|'.$updateResult['responseMessage']);
							// 			$this->subscribermain->delete($subscriber['USERNAME'],
							// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 			if (!is_null($subscriber['RBIPADDRESS'])) {
							// 				$unmark = $this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
							// 					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 				log_message('debug', 'unmark ip result: '.json_encode($unmark));
							// 			}
							// 			if (!is_null($subscriber['RBMULTISTATIC'])) {
							// 				$unmark = $this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
							// 					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 				log_message('debug', 'unmark net result: '.json_encode($unmark));
							// 			}
							// 			if (!is_null($subscriber['RBADDITIONALSERVICE4'])) {
							// 				$unmark = $this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
							// 					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 				log_message('debug', 'unmark ipv6 result: '.json_encode($unmark));
							// 			}
							// 			$data['error'] = $updateResult['responseMessage'];
							// 			$data['useIPv6'] = $this->useIPv6;
							// 			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
							// 			$this->load->view('create_user_account', $data);
							// 			return;
							// 		}
							// 	} else {
							// 		log_message('debug', 'create|failed to insert @ RM: '.$subscriber['USERNAME'].'|'.$insertResult['responseMessage']);
							// 		$this->subscribermain->delete($subscriber['USERNAME'],
							// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 		if (!is_null($subscriber['RBIPADDRESS'])) {
							// 			$unmark = $this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
							// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 			log_message('debug', 'unmark ip result: '.json_encode($unmark));
							// 		}
							// 		if (!is_null($subscriber['RBMULTISTATIC'])) {
							// 			$unmark = $this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
							// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 			log_message('debug', 'unmark net result: '.json_encode($unmark));
							// 		}
							// 		if (!is_null($subscriber['RBADDITIONALSERVICE4'])) {
							// 			$unmark = $this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
							// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 			log_message('debug', 'unmark ipv6 result: '.json_encode($unmark));
							// 		}
							// 		$data['error'] = $insertResult['responseMessage'];
							// 		$data['useIPv6'] = $this->useIPv6;
							// 		$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
							// 		$this->load->view('create_user_account', $data);
							// 		return;
							// 	}
							// }
							/*
							}
							*/
						// } catch (Exception $e) {
						// 	log_message('debug', 'create|error @ RM:'.json_encode($e));
						// 	$this->subscribermain->delete($subscriber['USERNAME'],
						// 		$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 	if (!is_null($subscriber['RBIPADDRESS'])) {
						// 		$unmark = $this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		log_message('debug', 'unmark ip result: '.json_encode($unmark));
						// 	}
						// 	if (!is_null($subscriber['RBMULTISTATIC'])) {
						// 		$unmark = $this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		log_message('debug', 'unmark net result: '.json_encode($unmark));
						// 	}
						// 	if (!is_null($subscriber['RBADDITIONALSERVICE4'])) {
						// 		$unmark = $this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		log_message('debug', 'unmark ipv6 result: '.json_encode($unmark));
						// 	}
						// 	$data['error'] = json_encode($e);
						// 	$data['useIPv6'] = $this->useIPv6;
						// 	$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						// 	$this->load->view('create_user_account', $data);
						// 	return;
						// }
						/**************************************************
						 * /RM
						 **************************************************/
						if ($sysuser != $this->SUPERUSER) {
							$this->load->model('subscriberaudittrail');
							$this->subscriberaudittrail->logSubscriberCreation($subscriber, $sysuser, $sysuserIP, $createddate);
						}
						$data['message'] = 'Account creation successful.';
						$data['useIPv6'] = $this->useIPv6;
						$this->load->view('create_user_account_result', $data);
					}
				}
			}
		}
	}
	public function processBulkLoadSubscribers($step = null) {
		$this->redirectIfNoAccess('Create Primary Account', 'subscribers/processBulkLoadSubscribers');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/21/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@processBulkLoadSubscribers|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@processBulkLoadSubscribers|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		/**************************************************
		 * subscribers/processBulkLoadSubscribers accessed via form
		 **************************************************/
		if (is_null($step)) {
			$step = $this->input->post('step');
			$realm = '';
			/**************************************************
			 * upload step, checks uploaded xls
			 **************************************************/
			if ($step == 'upload') {
				//get realms
				$this->load->model('realm');
				$realms = $this->realm->fetchAllNamesOnly();
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx|application/vnd.ms-office|\"application/vnd.ms-office\"|"application/vnd.ms-office"';
    			$config['max_size'] = '50000';
    			$config['overwrite'] = true;
    			$this->load->library('upload', $config);
    			/**************************************************
				 * upload failed
				 **************************************************/
    			if (!$this->upload->do_upload('file')) {
    				$data['step'] = 'upload';
    				$data['error'] = 'Upload failed: '.$this->upload->display_errors();
    				$data['realm'] = $this->input->post('realm');
    				$data['realms'] = $realms;
    				$data['proceed'] = $checks['go'];
    				if ($portal == 'service') {
						$data['realm'] = $realm;
						$data['disableRealm'] = true;
					}
					$data['useIPv6'] = $this->useIPv6;
    				$this->load->view('bulk_load_users', $data);
    			/**************************************************
				 * file uploaded
				 **************************************************/
    			} else {
    				$realm = $this->input->post('realm');
					$this->load->model('util');
    				$uploaded = $this->upload->data();
    				log_message('info', 'UPLOADED FILE: '.json_encode($uploaded));
    				/**************************************************
					 * check column contents (specifically header) of xls
					 **************************************************/
    				if ($this->useIPv6) {
    					$valid = $this->util->verifyBulkLoadFormatV2($uploaded['full_path']);
    				} else {
    					$valid = $this->util->verifyBulkLoadFormat($uploaded['full_path']);
    				}
    				/**************************************************
					 * one (or more) of the xls header names did not match
					 **************************************************/
    				if (!$valid) {
    					$data['step'] = 'upload';
    					$data['error'] = 'Invalid file contents: "'.$uploaded['client_name'].'"';
    					$data['realm'] = $realm;
    					$data['realms'] = $realms;
    					$data['proceed'] = $checks['go'];
    					if ($portal == 'service') {
							$data['realm'] = $realm;
							$data['disableRealm'] = true;
						}
						$data['useIPv6'] = $this->useIPv6;
    					$this->load->view('bulk_load_users', $data);
    				/**************************************************
					 * xls headers match, continue
					 **************************************************/
    				} else {
    					$totalRowCount = $this->util->countRows($uploaded['full_path']);
    					log_message('info', 'fileRowCount:'.$totalRowCount);
    					/**************************************************
						 * there's a row limit (timeout issues), check if exceeded
						 **************************************************/
    					if ($totalRowCount > $this->xlsRowLimit) {
    						$data['step'] = 'upload';
    						$data['error'] = 'Due to memory constraints, please limit files to less than 5000 rows.';
    						$data['realm'] = $realm;
    						$data['realms'] = $realms;
    						$data['proceed'] = $checks['go'];
    						if ($portal == 'service') {
    							$data['realm'] = $realm;
    							$data['disableRealm'] = true;
    						}
    						$data['useIPv6'] = $this->useIPv6;
    						$this->load->view('bulk_load_users', $data);
							return;
    					}
    					/**************************************************
						 * check data contents
						 * USERNAME (col B: 0), STATUS (col E: 3), SERVICE NUMBER (col H: 6), and SERVICE (col J: 8) are required
						 **************************************************/
    					$rows = $this->util->verifyBulkLoadData($uploaded['full_path'], $realm);
						$invalid = $rows['invalid'];
    					$invalid2 = [];
    					$invalidCount = count($invalid);
						for ($i = 0; $i < $invalidCount; $i++) {
							$rw = $invalid[$i];
							$errs = [];
							if ($rw[0] == '') {
								$errs['USERNAME'] = 'Missing username.';
							} else if (strlen(trim($rw[0])) > 70) {
								$errs['USERNAME'] = 'Username length exceeded maximum (70).';
							} else if (preg_match('/[A-Z]{1}/', $rw[0])) {
								$errs['USERNAME'] = 'Username has uppercase characters.';
							} else if (preg_match('/[^a-zA-Z0-9._-]/', $rw[0])) {
								$errs['USERNAME'] = 'Username has special characters.';
							}
							// if ($rw[2] == '') {
							// 	$errs['CUSTOMERTYPE'] = 'Missing customer type.';
							// }
							// if ($rw[3] == '') {
							// 	$errs['CUSTOMERSTATUS'] = 'Missing customer status.';
							// }
							// if ($rw[5] == '') {
							// 	$errs['RBCUSTOMERNAME'] = 'Missing customer name.';
							// }
							if ($rw[6] == '') {
								$errs['RBSERVICENUMBER'] = 'Missing service number';
							}
							// if ($rw[7] = '') {
							// 	$errs['RBENABLED'] = 'Missing enabled.';
							// }
							// REMOVED DEPENDENCIES 5/17/19
							// if ($rw[8] == '') {
							// 	$errs['RADIUSPOLICY'] = 'Missing service.';
							// }
							$invalid2[] = array('rowdata' => $rw, 'errors' => $errs);
						}
    					log_message('info', 'ROWS: '.json_encode($rows));
	    				$data = array(
	    					'step' => 'confirm',
	    					'proceed' => $checks['go'],
	    					'error' => $checks['msg'],
	    					'path' => $uploaded['full_path'],
	    					'realm' => $realm,
	    					'valid' => $rows['valid'],
	    					'validRowNumbers' => $rows['validRowNumbers'],
	    					'invalid' => $invalid2,
	    					'invalidRowNumbers' => $rows['invalidRowNumbers']);
	    				$data['useIPv6'] = $this->useIPv6;
	    				$this->load->view('bulk_load_users', $data);
    				}
    			}
    		/**************************************************
			 * create step, goes here after showing results of xls content checking
			 **************************************************/
			} else if ($step == 'create') {
				if (!$checks['go']) {
					redirect('subscribers/processBulkLoadSubscribers/upload');
				}
				$now = date('Y-m-d H:i:s', time());
				$realm = $this->input->post('realm');
				$path = $this->input->post('path');
				$validRowNumbers = unserialize($this->input->post('validRowNumbers'));
				$invalidRowNumbers = unserialize($this->input->post('invalidRowNumbers'));
				log_message('info', 'VALID ROWS: '.json_encode($validRowNumbers));
				log_message('info', 'INVALID ROWS: '.json_encode($invalidRowNumbers));
				$createdRows = [];
				$custname = trim($this->input->post('custname'));
				$notCreatedUsernameExists = [];
				$notCreatedInvalidData = [];
				$notCreatedIPNetError = [];
				$notCreatedNPMError = [];
				$createdRowNumbers = [];
				$notCreatedUsernameExistsRowNumbers = [];
				$notCreatedInvalidDataRowNumbers = [];
				$notCreatedIPNetErrorRowNumbers = [];
				$notCreatedNPMErrorRowNumbers = [];
				$passwordsForCreated = [];
				$this->load->model('util');
				$this->load->model('subscribermain');
				$this->load->model('ipaddress');
				$this->load->model('netaddress');
				$this->load->model('cabinet');
				if ($this->useIPv6) {
					$dataToCreate = $this->util->extractRowsToCreateV2($path, $validRowNumbers);
				} else {
					$dataToCreate = $this->util->extractRowsToCreate($path, $validRowNumbers);
				}

				
				// 

				log_message('info', 'TO CREATE: '.json_encode($dataToCreate));
				/************************************************************
				 * get this from one of the session variables
				 * $sysuser = $this->session->userdata('username');
				 ************************************************************/
				$sysuser = $this->session->userdata('username');
				$sysuserIP = $this->session->userdata('ip_address');

				// $this->load->library('rm');
				$dataToCreateCount = count($dataToCreate);
				for($i = 0; $i < $dataToCreateCount; $i++) {
					// Default to Active 6/28/19
					// Remove white space 6/28/19
					$dataToCreate[$i][3] = 'Active';
					$dataToCreate[$i][2] = $custname;
					$dataToCreate[$i][7] = trim($dataToCreate[$i][7]);
					$dataToCreate[$i][8] = trim($dataToCreate[$i][8]);
					$dataToCreate[$i][9] = trim($dataToCreate[$i][9]);
					$dataToCreate[$i][10] = trim($dataToCreate[$i][10]);
					$dataToCreate[$i][11] = trim($dataToCreate[$i][11]);
					/**************************************************
					 * convert a row from xls to $subscriber array
					 **************************************************/
					if ($this->useIPv6) {
						$subscriber = $this->subscribermain->rowDataToSubscriberArrayV2($dataToCreate[$i], $realm, $sysuser, 'create');
					} else {
						$subscriber = $this->subscribermain->rowDataToSubscriberArray($dataToCreate[$i], $realm, $sysuser, 'create');
					}
					if (is_null($subscriber['PASSWORD']) || $subscriber['PASSWORD'] == '') {
						$generatedPassword = $this->util->generateRandomString(10);
						$subscriber['PASSWORD'] = $generatedPassword;
						$dataToCreate[$i][1] = $generatedPassword;
					}
					if (is_null($subscriber['CUSTOMERTYPE']) || $subscriber['CUSTOMERTYPE'] == '') {
						$subscriber['CUSTOMERTYPE'] = 'Residential';
						$dataToCreate[$i][2] = 'Residential';
					}
					if (is_null($subscriber['RBENABLED']) || $subscriber['RBENABLED'] == '') {
						$subscriber['RBENABLED'] = 'Yes';
						$dataToCreate[$i][7] = 'Yes';
					}
					/**************************************************
					 * prepare value for RBENABLED
					 **************************************************/
					if (strtolower($subscriber['RBENABLED']) == 'yes' && strtolower($subscriber['CUSTOMERTYPE']) == 'residential') {
						$subscriber['RBENABLED'] = 'Yes';
						$dataToCreate[$i][7] = 'Yes';
					} else {
						$subscriber['RBENABLED'] = 'No';
						$dataToCreate[$i][7] = 'No';
					}
					if (!is_null($subscriber['RBREMARKS'] && $subscriber['RBREMARKS'] != '')) {
						$subscriber['RBREMARKS'] = date('Y-m-d H:i:s', time()).' '.$subscriber['RBREMARKS'];
					}
					log_message('info', $i.'|SUBS: '.json_encode($subscriber));
					/**************************************************
					 * check if username exists at TBLCUSTOMER
					 **************************************************/
					$exists = $this->subscribermain->subscriberExists($subscriber['USER_IDENTITY'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					/**************************************************
					 * username exists, skip row ($dataToCreate[$i])
					 **************************************************/
					if ($exists) {
						log_message('info', $i.'|username exists');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notCreatedUsernameExistsRowNumbers[] = $validRowNumbers[$i];
						$notCreatedUsernameExists[] = $dataToCreate[$i];
						continue;
					/**************************************************
					 * username can be used
					 **************************************************/
					} else {
						/**************************************************
						 * check subscriber data validity
						 **************************************************/
						$valid = $this->subscribermain->isValidNew($subscriber,
							$this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
						/**************************************************
						 * subscriber data not valid
						 **************************************************/
						if (!$valid['status']) {
							log_message('info', $i.'|subscriber info not valid');
							$invalidRowNumbers[] = $validRowNumbers[$i];
							$notCreatedInvalidDataRowNumbers[] = $validRowNumbers[$i];
							$notCreatedInvalidData[] = array('rowdata' => $dataToCreate[$i], 'errors' => $valid['errors']);
							continue;
						/**************************************************
						 * subscriber data valid
						 **************************************************/
						} else {
							log_message('info', $i.'|username:'.$subscriber['USERNAME']);
							/**************************************************
							 * check username
							 **************************************************/
							$usernameOnly = substr($subscriber['USERNAME'], 0, strpos($subscriber['USERNAME'], '@'));
							$usernameHasUppercase = preg_match('/[A-Z]{1}/', $usernameOnly);
							if ($usernameHasUppercase) {
								log_message('info', $i.'|username has uppercase chars');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notCreatedInvalidDataRowNumbers[] = $validRowNumbers[$i];
								$notCreatedInvalidData[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('USERNAME' => 'The username has uppercase characters.'));
								continue;
							}
							$parts = explode('@', $subscriber['USERNAME']);
							$hasSpecialChars = preg_match('/[^a-zA-Z0-9._-]/', $parts[0]);
							if ($hasSpecialChars) {
								log_message('info', $i.'|username has special chars');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notCreatedInvalidDataRowNumbers[] = $validRowNumbers[$i];
								$notCreatedInvalidData[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('USERNAME' => 'The username has non alphanumeric characters.'));
								continue;
							}
							if (strlen(trim($subscriber['USERNAME'])) > 70) {
								log_message('info', $i.'|username length has exceeded the maximum');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notCreatedInvalidDataRowNumbers[] = $validRowNumbers[$i];
								$notCreatedInvalidData[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('USERNAME' => 'Username length has exceeded the maximum (70).'));
								continue;
							}
							/**************************************************
							 * check service number uniqueness
							 **************************************************/
							$serviceNumberExists = $this->subscribermain->serviceNumberExists(trim($subscriber['RBSERVICENUMBER']),
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if ($serviceNumberExists) {
								log_message('info', $i.'|duplicate service number');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notCreatedInvalidDataRowNumbers[] =$validRowNumbers[$i];
								$notCreatedInvalidData[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('RBSERVICENUMBER' => 'Duplicate service number.'));
								continue;
							}
							/**************************************************
							 * goes here if an ipv6 address is provided
							 **************************************************/
							if ($this->useIPv6 && !is_null($subscriber['RBADDITIONALSERVICE4'])) {
								/**************************************************
								 * checks ipv6 address validity
								 **************************************************/
								if (!$this->ipaddress->isIPV6Valid($subscriber['RBADDITIONALSERVICE4'])) {
									log_message('info', $i.'|ipv6address invalid, check if cabinet name');
									/**************************************************
									 * is it a valid cabinet name?
									 **************************************************/
									$cabinetName = $subscriber['RBADDITIONALSERVICE4'];
									$cabinetObj = $this->cabinet->getCabinetWithName($cabinetName);
									log_message('debug', $i.'|cabinetName: '.$cabinetName.', cabinetObj: '.json_encode($cabinetObj));
									if ($cabinetObj === false || empty($cabinetObj) || is_null($cabinetObj)) {
										log_message('info', $i.'|not a valid cabinet name: '.$cabinetName);
										$invalidRowNumbers[] = $validRowNumbers[$i];
										$notCreatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
										$notCreatedIPNetError[] = array('rowdata' => $dataToCreate[$i],
											'errors' => array('RBADDITIONALSERVICE4' => 'Invalid cabinet name ('.$cabinetName.').'));
										continue;
									}
									/**************************************************
									 * pick an unused ipv6 address with location = $cabinetObj['homing_bng'] (homing_bng = location -> renamed at model)
									 **************************************************/
									$nextAvailableIpV6Address = $this->ipaddress->getNextAvailableIpV6Address($cabinetObj['homing_bng'],
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									if ($nextAvailableIpV6Address === false || empty($nextAvailableIpV6Address) || is_null($nextAvailableIpV6Address)) {
										log_message('info', $i.'|no available ipv6 address');
										$invalidRowNumbers[] = $validRowNumbers[$i];
										$notCreatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
										$notCreatedIPNetError[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('RBADDITIONALSERVICE4' => 'No available IPv6 Address.'));
										continue;
									}
									$subscriber['RBADDITIONALSERVICE4'] = $nextAvailableIpV6Address['IPV6ADDR'];
									/**************************************************
									 * mark picked ipv6 address as used
									 **************************************************/
									$this->ipaddress->markV6AsUsed($subscriber['RBADDITIONALSERVICE4'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									$dataToCreate[$i][9] = $subscriber['RBADDITIONALSERVICE4'];
								/**************************************************
								 * ipv6 address is valid
								 **************************************************/
								} else {
									/**************************************************
									 * check if ipv6 address is in database (TBLIPV6ADDRESS)
									 **************************************************/
									$ipV6Exists = $this->ipaddress->ipV6Exists($subscriber['RBADDITIONALSERVICE4'],
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									/**************************************************
									 * ipv6 address not found in database
									 **************************************************/
									if (!$ipV6Exists) {
										log_message('info', $i.'|ipv6 does not exist');
										$invalidRowNumbers[] = $validRowNumbers[$i];
										$notCreatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
										$notCreatedIPNetError[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('RBADDITIONALSERVICE4' => 'Static IPv6 address not available in IPv6 pool.'));
										continue;
									/**************************************************
									 * ipv6 address is in database
									 **************************************************/
									} else {
										/**************************************************
										 * check if ipv6 is assigned to other account
										 **************************************************/
										$ipV6Free = $this->ipaddress->isV6Free($subscriber['RBADDITIONALSERVICE4'],
											$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
										/**************************************************
										 * ipv6 is already used
										 **************************************************/
										if (!$ipV6Free) {
											log_message('info', $i.'|ipv6 address used');
											$invalidRowNumbers[] = $validRowNumbers[$i];
											$notCreatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
											$notCreatedIPNetError[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('RBADDITIONALSERVICE4' => 'Static IPv6 used.'));
											continue;
										/**************************************************
										 * ipv6 is free, mark as used
										 **************************************************/
										} else {
											$this->ipaddress->markV6AsUsed($subscriber['RBADDITIONALSERVICE4'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
												$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
										}
									}
								}
							}
							/**************************************************
							 * goes here if an ip address is provided
							 **************************************************/
							if (!is_null($subscriber['RBIPADDRESS'])) {
								/**************************************************
								 * checks ip address validity
								 **************************************************/
								if (!$this->util->isIPValid($subscriber['RBIPADDRESS'])) {
									log_message('info', $i.'|ipaddress invalid, check if cabinet name');
									/**************************************************
									 * is it a valid cabinet name?
									 **************************************************/
									$cabinetName = $subscriber['RBIPADDRESS'];
									$cabinetObj = $this->cabinet->getCabinetWithName($cabinetName);
									log_message('debug', $i.'|cabinetName: '.$cabinetName.', cabinetObj: '.json_encode($cabinetObj));
									if ($cabinetObj === false || empty($cabinetObj) || is_null($cabinetObj)) {
										log_message('info', $i.'|not a valid cabinet name: '.$cabinetName);
										$invalidRowNumbers[] = $validRowNumbers[$i];
										$notCreatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
										$notCreatedIPNetError[] = array('rowdata' => $dataToCreate[$i],
											'errors' => array('RBIPADDRESS' => 'Invalid cabinet name ('.$cabinetName.').'));
										continue;
									}
									/**************************************************
									 * cabinet name is valid, check if gpon
									 **************************************************/
									$srv = strtolower($subscriber['RBACCOUNTPLAN']);
									$getGponIp = strpos($srv, 'gpon') !== false;
									/**************************************************
									 * pick an unused ip address with location = $cabinetObj['homing_bng'] (homing_bng = location -> renamed at model)
									 **************************************************/
									$nextAvailableIpAddress = $this->ipaddress->getNextAvailableIpAddress($cabinetObj['homing_bng'], $getGponIp,
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									if ($nextAvailableIpAddress === false || empty($nextAvailableIpAddress) || is_null($nextAvailableIpAddress)) {
										log_message('info', $i.'|no available ip address');
										$invalidRowNumbers[] = $validRowNumbers[$i];
										$notCreatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
										$notCreatedIPNetError[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('RBIPADDRESS' => 'No available IP Address.'));
										continue;
									}
									log_message('info', $i.'|assigning '.$nextAvailableIpAddress['IPADDRESS'].' to '.$subscriber['USERNAME']);
									$subscriber['RBIPADDRESS'] = $nextAvailableIpAddress['IPADDRESS'];
									/**************************************************
									 * mark picked ip address as used
									 **************************************************/
									$this->ipaddress->markAsUsed($subscriber['RBIPADDRESS'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									if ($this->useIPv6) {
										$dataToCreate[$i][10] = $subscriber['RBIPADDRESS'];
									} else {
										$dataToCreate[$i][9] = $subscriber['RBIPADDRESS'];
									}
								/**************************************************
								 * ip address is valid
								 **************************************************/
								} else {
									/**************************************************
									 * check if ip address is in database (TBLIPADDRESS)
									 **************************************************/
									$ipExists = $this->ipaddress->ipExists($subscriber['RBIPADDRESS'],
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									/**************************************************
									 * ip address not found in database
									 **************************************************/
									if (!$ipExists) {
										log_message('info', $i.'|ip does not exist');
										$invalidRowNumbers[] = $validRowNumbers[$i];
										$notCreatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
										$notCreatedIPNetError[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('RBIPADDRESS' => 'Static IP address not available in IP pool.'));
										continue;
									/**************************************************
									 * ip address is in database
									 **************************************************/
									} else {
										/**************************************************
										 * check if ip address should be gpon based from plan
										 **************************************************/
										$srv = strtolower($subscriber['RBACCOUNTPLAN']);
										$ipMustBeGPON = true;
										if (strpos($srv, 'gpon') === false) {
											$ipMustBeGPON = false;
										}
										$isGPON = $this->ipaddress->isGPON($subscriber['RBIPADDRESS'],
											$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
										/**************************************************
										 * ip must be gpon type but is not
										 **************************************************/
										if ($ipMustBeGPON && !$isGPON) {
											log_message('info', $i.'|ip address is not GPON (should be)');
											$invalidRowNumbers[] = $validRowNumbers[$i];
											$notCreatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
											$notCreatedIPNetError[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('RBIPADDRESS' => 'Static IP must be GPON type.'));
											continue;
										/**************************************************
										 * ip shold not be gpon type but is
										 **************************************************/
										} else if (!$ipMustBeGPON && $isGPON) {
											log_message('info', $i.'|ip address is GPON (should not be)');
											$invalidRowNumbers[] = $validRowNumbers[$i];
											$notCreatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
											$notCreatedIPNetError[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('RBIPADDRESS' => 'Static IP must not be GPON type.'));
											continue;
										}
										/**************************************************
										 * check if ip is assigned to other account
										 **************************************************/
										$ipFree = $this->ipaddress->isFree($subscriber['RBIPADDRESS'],
											$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
										/**************************************************
										 * ip is already used
										 **************************************************/
										if (!$ipFree) {
											log_message('info', $i.'|ip address used');
											$invalidRowNumbers[] = $validRowNumbers[$i];
											$notCreatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
											$notCreatedIPNetError[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('RBIPADDRESS' => 'Static IP used.'));
											continue;
										/**************************************************
										 * ip is free, mark as used
										 **************************************************/
										} else {
											$this->ipaddress->markAsUsed($subscriber['RBIPADDRESS'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
												$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
										}
									}
								}
							}
							/**************************************************
							 * goes here if net address is provided
							 **************************************************/
							if (!is_null($subscriber['RBMULTISTATIC'])) {
								/**************************************************
								 * checks if ip address was also provided. net address requires the presence of ip address
								 **************************************************/
								if (is_null($subscriber['RBIPADDRESS'])) {
									log_message('info', $i.'|has net but no ip');
									$invalidRowNumbers[] = $validRowNumbers[$i];
									$notCreatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
									$notCreatedIPNetError[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('RBMULTISTATIC' => 'Missing Static IP.', 'RBIPADDRESS' => 'Missing Static IP.'));
									continue;
								/**************************************************
								 * ip address was also provided
								 **************************************************/
								} else {
									/**************************************************
									 * check if net address is valid
									 **************************************************/
									if (!$this->util->isIPValid($subscriber['RBMULTISTATIC'])) {
										log_message('info', $i.'|net address not valid, check if it is a cabinet name, and if it there is a subnet given');
										$givenNetAddress = $subscriber['RBMULTISTATIC'];
										if ($this->useSeparateSubnetForNetAddress) {
											$netAddressParts = explode($this->netAddressSubnetMarker, $givenNetAddress);
											$partsCount = count($netAddressParts);
										} else {
											$partsCount = 1;
										}
										/**************************************************
										 * is it a valid cabinet name?
										 **************************************************/
										$cabinetName = $this->useSeparateSubnetForNetAddress ? $netAddressParts[0] : $givenNetAddress;
										$cabinetObj = $this->cabinet->getCabinetWithName($cabinetName);
										log_message('debug', $i.'|cabinetName: '.$cabinetName.', cabinetObj: '.json_encode($cabinetObj));
										/**************************************************
										 * not a valid cabinet name, unmark marked ip address earlier
										 **************************************************/
										if ($cabinetObj === false || empty($cabinetObj) || is_null($cabinetObj)) {
											log_message('info', $i.'|not a valid cabinet name');
											if (!is_null($subscriber['RBIPADDRESS'])) {
												$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
													$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
											}
											$invalidRowNumbers[] = $validRowNumbers[$i];
											$notCreatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
											$notCreatedIPNetError[] = array('rowdata' => $dataToCreate[$i],
												'errors' => array('RBMULTISTATIC' => 'Invalid cabinet name ('.$cabinetName.').'));
											continue;
										}
										/**************************************************
										 * was there a subnet given?
										 * only applies if $this->useSeparateSubnetForNetAddress is true, else $cabinetSubnet is null
										 **************************************************/
										if ($this->useSeparateSubnetForNetAddress && $partsCount == 2) {
											$cabinetSubnet = $netAddressParts[1];
											log_message('debug', $i.'|subnet restriction: '.$cabinetSubnet);
										} else {
											$cabinetSubnet = null;
											log_message('debug', $i.'|no subnet restriction');
										}
										/**************************************************
										 * cabinet name is valid
										 * pick an available net address (with or without subnet restriction)
										 **************************************************/
										$nextAvailableNetAddress = $this->netaddress->getNextAvailableNetAddressWithLocationAndSubnet($cabinetObj['homing_bng'], $cabinetSubnet,
											$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
										/**************************************************
										 * no available net address, unmark marked ip address earlier
										 **************************************************/
										if ($nextAvailableNetAddress === false || empty($nextAvailableNetAddress) || is_null($nextAvailableNetAddress)) {
											log_message('info', $i.'|no available net address');
											if (!is_null($subscriber['RBIPADDRESS'])) {
												$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
													$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
											}
											$invalidRowNumbers[] = $validRowNumbers[$i];
											$notCreatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
											$notCreatedIPNetError[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('RBMULTISTATIC' => 'No available Net Address.'));
											continue;
										}
										log_message('info', $i.'|assigning '.$nextAvailableNetAddress['NETADDRESS'].' to '.$subscriber['USERNAME']);
										$subscriber['RBMULTISTATIC'] = $nextAvailableNetAddress['NETADDRESS'];
										/**************************************************
										 * mark picked net address as used;
										 * setting RBENABLED to No since RBMULTISTATIC was assigned
										 **************************************************/
										$this->netaddress->markAsUsed($subscriber['RBMULTISTATIC'], $subscriber['USER_IDENTITY'],
											$subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
											$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);

										$subscriber['RBENABLED'] = 'No';
										$dataToCreate[$i][7] = 'No';
										if ($this->useIPv6) {
											$dataToCreate[$i][11] = $subscriber['RBMULTISTATIC'];
										} else {
											$dataToCreate[$i][10] = $subscriber['RBMULTISTATIC'];
										}
									/**************************************************
									 * net address is valid
									 **************************************************/
									} else {
										/**************************************************
										 * check if net address is in database
										 **************************************************/
										$netExists = $this->netaddress->netExists($subscriber['RBMULTISTATIC'],
											$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
										/**************************************************
										 * net address is not in database
										 **************************************************/
										if (!$netExists) {
											log_message('info', $i.'|net address does not exist');
											if (!is_null($subscriber['RBIPADDRESS'])) { //revert back changes to ip address
												$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
													$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
											}
											$invalidRowNumbers[] = $validRowNumbers[$i];
											$notCreatedIPNetErrorRowNumbers[] = $invalidRowNumbers[$i];
											$notCreatedIPNetError[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('RBMULTISTATIC' => 'Multi IP address not available in IP pool.'));
											continue;
										/**************************************************
										 * net address is in database
										 **************************************************/
										} else {
											/**************************************************
											 * check if net address is not assigned to an account
											 **************************************************/
											$netFree = $this->netaddress->isFree($subscriber['RBMULTISTATIC'],
												$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
											/**************************************************
											 * net address is used
											 **************************************************/
											if (!$netFree) {
												log_message('info', $i.'|net address used');
												if (!is_null($subscriber['RBIPADDRESS'])) { //revert back changes to ip address
													$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
														$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
												}
												$invalidRowNumbers[] = $validRowNumbers[$i];
												$notCreatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
												$notCreatedIPNetError[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('RBMULTISTATIC' => 'Multi IP used.'));
												continue;
											/**************************************************
											 * net address is available, mark as used
											 **************************************************/
											} else {
												$this->netaddress->markAsUsed($subscriber['RBMULTISTATIC'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
													$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
												/**************************************************
												 * RBMULTISTATIC value is valid, set RBENABLED = 'No';
												 **************************************************/
												log_message('info', '@bulkload: '.$subscriber['USERNAME'].' has valid RBMULTISTATIC, setting RBENABLED to \'No\'');
												$subscriber['RBENABLED'] = 'No';
												$dataToCreate[$i][7] = 'No';
											}
										}
									}
								}
							}
							/**************************************************
							 * uncomment the next line if RBENABLED is fixed to 'No' and FAP to 'N'
							 * FAP is dependent on the value of $subscriber['RBENABLED']: 'No' => 'N', 'Yes' => 'Y'
							 **************************************************/
							// $subscriber['RBENABLED'] = 'No';
							$subscriber['CUSTOMERREPLYITEM'] = $this->subscribermain->generateCustomerReplyItemValue(
								$subscriber['RBADDITIONALSERVICE4'], $subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC']);
							/**************************************************
							 * all is good, create account
							 **************************************************/
							// log_message('debug', '@processBulkLoadSubscribers|subscriber:'.$subscriber['USERNAME'].'|RBENABLED:'.$subscriber['RBENABLED']);
							$result = $this->subscribermain->create($subscriber,
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							/**************************************************
							 * account not created, some error
							 **************************************************/
							if (!$result) {
								log_message('info', $i.'|subscriber not created');
								if ($this->useIPv6 && !is_null($subscriber['RBADDITIONALSERVICE4'])) {
									$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
								}
								if (!is_null($subscriber['RBIPADDRESS'])) {
									$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
								}
								if (!is_null($subscriber['RBMULTISTATIC'])) {
									$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
								}
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notCreatedInvalidDataRowNumbers[] = $validRowNumbers[$i];
								$notCreatedInvalidData[] = array('rowdata' => $dataToCreate[$i], 'errors' => array('ALL' => 'Failed to create.'));
								continue;
							/**************************************************
							 * account created
							 **************************************************/
							} else {
								/**************************************************
								 * create account in npm (if enabled)
								 * - ENABLENPM will always be false so $npmResult['result'] will always be true (npm was removed a long time ago)
								 **************************************************/
								if ($this->ENABLENPM) {
									/**************************************************
									 * check if account is already in npm
									 **************************************************/
									$subs = $this->subscribermain->npmFetchXML($subscriber['USERNAME'], $this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
									/**************************************************
									 * return error if account is already in npm
									 **************************************************/
									if ($subs['found']) {
										$npmResult['result'] = false;
										$npmResult['error'] = 'Account already exists.';
									/**************************************************
									 * account not yet in npm, create
									 **************************************************/
									} else {
										$npmResult = $this->subscribermain->npmCreateXML($subscriber['USERNAME'], $subscriber['PASSWORD'],
											$subscriber['CUSTOMERSTATUS'] == 'Active' ? 'Y' : 'N', time(), str_replace('~', '-', $subscriber['RBACCOUNTPLAN']),
											$subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC'], 'N',
											$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
									}
								/**************************************************
								 * npm disabled, $npmResult['result'] defaults to true
								 **************************************************/
								} else {
									$npmResult['result'] = true;
								}
								/**************************************************
								 * account not created in npm, revert changes to ip/net address and delete subscriber
								 **************************************************/
								if (!$npmResult['result']) {
									if ($this->useIPv6 && !is_null($subscriber['RBADDITIONALSERVICE4'])) {
										$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
											$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									}
									if (!is_null($subscriber['RBIPADDRESS'])) {
										$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
											$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									}
									if (!is_null($subscriber['RBMULTISTATIC'])) {
										$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
											$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									}
									$this->subscribermain->delete($subscriber['USER_IDENTITY'],
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									$invalidRowNumbers[] = $validRowNumbers[$i];
									$notCreatedNPMErrorRowNumbers[] = $validRowNumbers[$i];
									$notCreatedNPMError[] = array('rowdata' => $dataToCreate[$i], 'errors' => $npmResult['error']);
									continue;
								/**************************************************
								 * account created in npm
								 **************************************************/
								} else {
									/**************************************************
									 * RM api
									 **************************************************/
									// Remove RM Dependencies 5/21/19
									// try {
									// 	$rmClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
										/**************************************************
										 * if branch for handling old rm api
										 * - as of nov 18, 2016 this is no longer used
										 * - remove this later (including closing brace before the catch block)
										 **************************************************/
										/*
										if ($this->RMAPIHOST == '10.244.4.130' || $this->RMAPIHOST == '10.244.4.131') {
											$rmSubsUsername = array('key' => 'USERNAME' , 'value' => $subscriber['USERNAME']);
											$rmSubsIdentity = array('key' => 'SUBSCRIBERIDENTITY', 'value' => $subscriber['USERNAME']);
											$rmSubsCui = array('key' => 'CUI', 'value' => $subscriber['USERNAME']);
											$rmSubsStatus = array('key' => 'SUBSCRIBERSTATUS', 'value' => 'Active');
											$rmSubsArea = array('key' => 'AREA', 'value' => $subscriber['CUSTOMERSTATUS']);
											$rmSubsPackage = array('key' => 'SUBSCRIBERPACKAGE', 'value' => str_replace('-', '~', $subscriber['RBACCOUNTPLAN']));
											$customerTypeForRM = '';
											if ($subscriber['CUSTOMERTYPE'] == 'Residential') {
												$customerTypeForRM = 'RESS';
											} else if ($subscriber['CUSTOMERTYPE'] == 'Business') {
												$customerTypeForRM = 'BUSS';
											} else {
												$customerTypeForRM = 'RESS';
											}
											$rmSubsCustomertype = array('key' => 'CUSTOMERTYPE', 'value' => $customerTypeForRM);
											$fapForRM = '';
											if ($subscriber['RBENABLED'] == 'Yes') {
												$fapForRM = 'Y';
											} else if ($subscriber['RBENABLED'] == 'No') {
												$fapForRM = 'N';
											} else {
												$fapForRM = 'N';
											}
											$rmSubsFap = array('key' => 'FAP', 'value' => $fapForRM);
											/**************************************************
											 * try inserting
											 **************************************************
											$rmInserted = true;
											$rmParam = array($rmSubsUsername, $rmSubsIdentity, $rmSubsCui, $rmSubsStatus, $rmSubsArea, $rmSubsPackage, $rmSubsCustomertype, $rmSubsFap);
											$insertResult = $this->rm->createAccount($rmParam, $rmClient);
											if (intval($insertResult['responseCode']) == 200) {
												log_message('debug', 'bulkcreate|inserted @ RM: '.$subscriber['USERNAME']);
											} else if (intval($insertResult['responseCode']) == 450) {
												log_message('debug', 'bulkcreate|'.$subscriber['USERNAME'].' exists, update');
												$rmSubsPassword = array('key' => 'PASSWORD', 'value' => '');
												$rmParam = array($rmSubsStatus, $rmSubsArea, $rmSubsPackage, $rmSubsPassword, $rmSubsCustomertype, $rmSubsFap);
												$updateResult = $this->rm->updateAccount($subscriber['USERNAME'], $rmParam, $rmClient);
												if (intval($updateResult['responseCode']) == 200) {
													log_message('debug', 'bulkcreate|updated @ RM: '.$subscriber['USERNAME']);
												} else {
													log_message('debug', 'bulkcreate|failed to update @ RM:'.$subscriber['USERNAME'].'|'.$updateResult['responseMessage']);
													$this->subscribermain->delete($subscriber['USERNAME'],
														$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
													$rmInserted = false;
												}
											} else {
												log_message('debug', 'bulkcreate|failed to insert @ RM: '.$subscriber['USERNAME'].'|'.$insertResult['responseMessage']);
												$this->subscribermain->delete($subscriber['USERNAME'],
													$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
												$rmInserted = false;
											}
										} else if ($this->RMAPIHOST == '10.81.54.34' || $this->RMAPIHOST == '10.81.54.35') {
										*/
										// $rmUsername = $subscriber['USERNAME'];
										// $rmPlan = $subscriber['RADIUSPOLICY'];
										// // $rmStatus = strtoupper($subscriber['CUSTOMERSTATUS']);
										// $rmStatus = 'ACTIVE';
										// if (strtolower($subscriber['CUSTOMERTYPE']) == 'residential') {
										// 	$rmCustomerType = 'RESS';
										// } else if (strtolower($subscriber['CUSTOMERTYPE']) == 'business') {
										// 	$rmCustomerType = 'BUSS';
										// } else {
										// 	$rmCustomerType = 'RESS';
										// }
										// $fapForRM = '';
										// if ($subscriber['RBENABLED'] == 'Yes') {
										// 	$fapForRM = 'Y';
										// } else if ($subscriber['RBENABLED'] == 'No') {
										// 	$fapForRM = 'N';
										// } else {
										// 	$fapForRM = 'N';
										// }
										/**************************************************
										 * try inserting
										 **************************************************/
										// $rmInserted = true;
										// $nodes = array('PARAM3' => $fapForRM, 'AREA' => $subscriber['CUSTOMERSTATUS']);
										// $insertResult = $this->rm->wsAddSubscriber($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
										// log_message('debug', 'bulkcreate|insertResult: '.json_encode($insertResult));
										// if (intval($insertResult['responseCode']) == 200) {
										// 	log_message('debug', 'bulkcreate|inserted @ RM: '.$subscriber['USERNAME']);
										// } else if (intval($insertResult['responseCode']) == 450) {
										// 	log_message('debug', 'bulkcreate|'.$subscriber['USERNAME'].' exists, update');
										// 	$updateResult = $this->rm->wsUpdateSubscriberProfile($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
										// 	log_message('debug', 'bulkcreate|updateResult: '.json_encode($updateResult));
										// 	if (intval($updateResult['responseCode']) == 200) {
										// 		log_message('debug', 'bulkcreate|updated @ RM: '.$subscriber['USERNAME']);
										// 	} else {
										// 		log_message('debug', 'bulkcreate|failed to update @ RM:'.$subscriber['USERNAME'].'|'.$updateResult['responseMessage']);
										// 		log_message('debug', 'bulkcreate|RM says "already exists" to insert attempt but says "subscriber identity not found" '.
										// 			'on update attempt: username cleanup');
										// 		for ($nn = 0; $nn < 4; $nn++) {
										// 			if ($nn == 0) {
										// 				$thisUsername = $rmUsername;
										// 				$deleteResult = $this->rm->wsDeleteSubscriberProfile($rmClient, $thisUsername);
										// 				log_message('debug', 'bulkcreate|delete (alternateId) result for '.$thisUsername.': '.$deleteResult['responseCode']);
										// 				$purgeResult = $this->rm->wsPurgeSubscriber($rmClient, $thisUsername);
										// 				log_message('debug', 'bulkcreate|purge (alternateId) result for '.$thisUsername.': '.$purgeResult['responseCode']);
										// 				$deleteResult = $this->rm->wsDeleteSubscriberProfileV2($rmClient, $thisUsername);
										// 				log_message('debug', 'bulkcreate|delete (subscriberID) result for '.$thisUsername.': '.$deleteResult['responseCode']);
										// 				$purgeResult = $this->rm->wsPurgeSubscriberV2($rmClient, $thisUsername);
										// 				log_message('debug', 'bulkcreate|purge (subscriberID) result for '.$thisUsername.': '.$purgeResult['responseCode']);
										// 			} else {
										// 				$thisUsername = $rmUsername.'#L'.$nn;
										// 				$deleteResult = $this->rm->wsDeleteSubscriberProfileV2($rmClient, $thisUsername);
										// 				log_message('debug', 'bulkcreate|delete (subscriberID) result for '.$thisUsername.': '.$deleteResult['responseCode']);
										// 				$purgeResult = $this->rm->wsPurgeSubscriberV2($rmClient, $thisUsername);
										// 				log_message('debug', 'bulkcreate|purge (subscriberID) result for '.$thisUsername.': '.$purgeResult['responseCode']);
										// 			}
										// 		}
										// 		$insertResult = $this->rm->wsAddSubscriber($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
										// 		log_message('debug', 'bulkcreate|insertResult: '.json_encode($insertResult));
										// 		if (intval($insertResult['responseCode']) == 200) {
										// 			log_message('debug', 'bulkcreate|create attempt #2 successful');
										// 		} else {
										// 			log_message('debug', 'bulkcreate|create attempt #2 failed: '.$insertResult['responseMessage']);
										// 			$this->subscribermain->delete($subscriber['USERNAME'],
										// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
										// 			$rmInserted = false;
										// 		}
										// 	}
										// } else if (intval($insertResult['responseCode']) == 400) {
										// 	log_message('debug', 'bulkcreate|responseCode = 400');
										// 	$deleteResult = $this->rm->wsDeleteSubscriberProfile($rmClient, $rmUsername);
										// 	log_message('debug', 'bulkcreate|deleteResult: '.json_encode($deleteResult));
										// 	$purgeResult = $this->rm->wsPurgeSubscriber($rmClient, $rmUsername);
										// 	log_message('debug', 'bulkcreate|purgeResult:' .json_encode($purgeResult));
										// 	$insertResult = $this->rm->wsAddSubscriber($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
										// 	log_message('debug', 'bulkcreate|insertResult: '.json_encode($insertResult));
										// 	if (intval($insertResult['responseCode']) == 200) {
										// 		log_message('debug', 'bulkcreate|create attempt #2 (from error code 400) successful');
										// 	} else {
										// 		log_message('debug', 'bulkcreate|create attempt #2 (from error code 400) failed: '.$insertResult['responseMessage']);
										// 		$this->subscribermain->delete($subscriber['USERNAME'],
										// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
										// 		$rmInserted = false;
										// 	}
										// } else {
										// 	$fault = $insertResult['responseMessage'];
										// 	$toFind1 = 'ORA-00001';
										// 	$toFind2 = 'RM.SYS_C0015045';
										// 	$found1 = strpos($fault, $toFind1);
										// 	$found2 = strpos($fault, $toFind2);
										// 	if ($found1 && $found2) {
										// 		$updateResult = $this->rm->wsUpdateSubscriberProfile($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
										// 		log_message('debug', 'bulkcreate|updateResult: '.json_encode($updateResult));
										// 		if (intval($updateResult['responseCode']) == 200) {
										// 			log_message('debug', 'bulkcreate|updated @ RM (after INTERNAL ERROR): '.$subscriber['USERNAME']);
										// 		} else {
										// 			log_message('debug', 'bulkcreate|failed to update @ RM (after INTERNAL ERROR):'.$subscriber['USERNAME'].'|'.$updateResult['responseMessage']);
										// 			$this->subscribermain->delete($subscriber['USERNAME'],
										// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
										// 			$data['error'] = $updateResult['responseMessage'];
										// 			$data['useIPv6'] = $this->useIPv6;
										// 			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
										// 			$this->load->view('create_user_account', $data);
										// 			return;
										// 		}
										// 	} else {
										// 		log_message('debug', 'bulkcreate|failed to insert @ RM: '.$subscriber['USERNAME'].'|'.$insertResult['responseMessage']);
										// 		$this->subscribermain->delete($subscriber['USERNAME'],
										// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
										// 		$rmInserted = false;
										// 	}
										// }
										/*
										}
										*/
									// } catch (Exception $e) {
									// 	log_message('debug', 'error @ RM:'.json_encode($e));
									// 	$rmInserted = false;
									// }
									// if (!$rmInserted) {
									// 	$this->subscribermain->delete($subscriber['USERNAME'],
									// 		$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									// 	if ($this->useIPv6 && !is_null($subscriber['RBADDITIONALSERVICE4'])) {
									// 		$unmark = $this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
									// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									// 		log_message('debug', 'unmark '.$subscriber['RBADDITIONALSERVICE4'].'|'.json_encode($unmark));
									// 	}
									// 	if (!is_null($subscriber['RBIPADDRESS'])) {
									// 		$unmark = $this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
									// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									// 		log_message('debug', 'unmark '.$subscriber['RBIPADDRESS'].'|'.json_encode($unmark));
									// 	}
									// 	if (!is_null($subscriber['RBMULTISTATIC'])) {
									// 		$unmark = $this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
									// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									// 		log_message('debug', 'unmark '.$subscriber['RBMULTISTATIC'].'|'.json_encode($unmark));
									// 	}
									// 	$invalidRowNumbers[] = $validRowNumbers[$i];
									// 	$notCreatedNPMErrorRowNumbers[] = $validRowNumbers[$i];
									// 	$notCreatedNPMError[] = array('rowdata' => $dataToCreate[$i], 'errors' => 'no connection to '.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
									// 	continue;
									// }
									/**************************************************
									 * /RM
									 **************************************************/
									$passwordsForCreated[] = $dataToCreate[$i][1];
									$createdRows[] = $dataToCreate[$i];
									$createdRowNumbers[] = $validRowNumbers[$i];
									if ($sysuser != $this->SUPERUSER) {
										$this->load->model('subscriberaudittrail');
										$this->subscriberaudittrail->logSubscriberCreation($subscriber, $sysuser, $sysuserIP, $now);
									}
								}
							}
						}
					}
				}
				/**************************************************
				 * for when there is a net address but no ip address in the xls row, both will be removed for the results output display
				 **************************************************/
				for ($i = 0; $i < count($createdRows); $i++) {
					$row = $createdRows[$i];
					if ($row[3] == 'T') {
						$createdRows[$i][9] = null;
						$createdRows[$i][10] = null;
					}
				}
				log_message('info', '__________________________________________________________________________________');
				log_message('info', 'CREATED: '.json_encode($createdRows));
				log_message('info', 'USERNAME EXISTS: '.json_encode($notCreatedUsernameExists));
				log_message('info', 'INVALID DATA: '.json_encode($notCreatedInvalidData));
				log_message('info', 'IPNET ERROR: '.json_encode($notCreatedIPNetError));
				log_message('info', 'NPM ERROR: '.json_encode($notCreatedNPMError));
				log_message('info', 'NOT CREATED (ROWS): '.json_encode($invalidRowNumbers));
				$data = array(
					'step' => 'create',
					'proceed' => $checks['go'],
					'error' => $checks['msg'],
					'path' => $path,
					'realm' => $realm,
					'created' => $createdRows,
					'usernameExists' => $notCreatedUsernameExists,
					'invalidData' => $notCreatedInvalidData,
					'ipNetError' => $notCreatedIPNetError,
					'npmError' => $notCreatedNPMError,
					'invalidRowNumbers' => $invalidRowNumbers,
					'createdRowNumbers' => $createdRowNumbers,
					'usernameExistsRowNumbers' => $notCreatedUsernameExistsRowNumbers,
					'invalidDataRowNumbers' => $notCreatedInvalidDataRowNumbers,
					'ipNetErrorRowNumbers' => $notCreatedIPNetErrorRowNumbers,
					'npmErrorRowNumbers' => $notCreatedNPMErrorRowNumbers,
					'createdPasswords' => count($passwordsForCreated) == 0 ? '' : str_replace('"', '|', json_encode($passwordsForCreated)));
				$data['useIPv6'] = $this->useIPv6;
				$this->load->view('bulk_load_users', $data);
			/**************************************************
			 * goes here if results are to be downloaded
			 **************************************************/
			} else if ($step == 'download') {
				log_message('info', '__________@extract');
				$path = $this->input->post('path');
				$set = trim($this->input->post('set'));
				$realm = $this->input->post('realm');
				$createdPasswords = $this->input->post('pw');
				log_message('info', $createdPasswords);
				$createdPasswords = str_replace('|', '"', $createdPasswords);
				log_message('info', $createdPasswords);
				$createdPasswords = json_decode($createdPasswords);
				$setdata = unserialize($this->input->post('setdata'));
				log_message('info', 'set:'.$set);
				$this->load->model('util');
				if ($this->useIPv6) {
					$this->util->writeBulkLoadOutputV2($path, $setdata, $set, $createdPasswords, $realm);
				} else {
					$this->util->writeBulkLoadOutput($path, $setdata, $set, $createdPasswords, $realm);
				}
			}
		/**************************************************
		 * subscribers/processBulkLoadSubscribers accessed via url
		 **************************************************/
		} else {
			//get realms
			$this->load->model('realm');
			$realms = $this->realm->fetchAllNamesOnly();
			$data = array(
				'realms' => $realms);
			if ($portal == 'service') {
				$data['realm'] = $realm;
				$data['disableRealm'] = true;
			}
			$data['proceed'] = $checks['go'];
			$data['error'] = $checks['msg'];
			$data['useIPv6'] = $this->useIPv6;
			$this->load->view('bulk_load_users', $data);
		}
	}
	public function processBulkCheckSubscribers($step = null) {
		$this->redirectIfNoAccess('Create Primary Account', 'subscribers/processBulkCheckSubscribers');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@processBulkCheckSubscribers|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@processBulkCheckSubscribers|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		/**************************************************
		 * subscribers/processBulkCheckSubscribers accessed via form
		 **************************************************/
		if (is_null($step)) { //via form
			$step = $this->input->post('step');
			$realm = '';
			/**************************************************
			 * upload step
			 **************************************************/
			if ($step == 'upload') {
				//get realms
				$this->load->model('realm');
				$realms = $this->realm->fetchAllNamesOnly();
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx|application/vnd.ms-office|\"application/vnd.ms-office\"|"application/vnd.ms-office"';
    			$config['max_size'] = '50000';
    			$config['overwrite'] = true;
    			$this->load->library('upload', $config);
    			/**************************************************
				 * upload failed
				 **************************************************/
    			if (!$this->upload->do_upload('file')) {
    				$data['step'] = 'upload';
    				$data['error'] = 'Upload failed: '.$this->upload->display_errors();
    				$data['realm'] = $this->input->post('realm');
    				$data['realms'] = $realms;
    				$data['proceed'] = $checks['go'];
    				if ($portal == 'service') {
						$data['realm'] = $realm;
						$data['disableRealm'] = true;
					}
					$data['useIPv6'] = $this->useIPv6;
    				$this->load->view('bulk_check_users', $data);
    			/**************************************************
				 * file uploaded
				 **************************************************/
    			} else {
    				$this->load->helper('file');
    				$realm = $this->input->post('realm');
					$this->load->model('util');
					$this->load->model('subscribermain');
					$this->load->model('services');
					$this->load->model('ipaddress');
					$this->load->model('netaddress');
					$this->load->model('cabinet');
					$uploaded = $this->upload->data();
					/**************************************************
					 * check headers of uploaded xls
					 **************************************************/
					if ($this->useIPv6) {
						$valid = $this->util->verifyBulkCheckFormatV2($uploaded['full_path']);
					} else {
						$valid = $this->util->verifyBulkCheckFormat($uploaded['full_path']);
					}
					/**************************************************
					 * one or more of the headers is not valid
					 **************************************************/
					if (!$valid) {
						$data['step'] = 'upload';
    					$data['error'] = 'Invalid file contents: "'.$uploaded['client_name'].'"';
    					$data['realm'] = $realm;
    					$data['realms'] = $realms;
    					$data['proceed'] = $checks['go'];
    					if ($portal == 'service') {
							$data['realm'] = $realm;
							$data['disableRealm'] = true;
						}
						$data['useIPv6'] = $this->useIPv6;
    					$this->load->view('bulk_check_users', $data);
					/**************************************************
					 * valid headers
					 **************************************************/
					} else {
						$totalRowCount = $this->util->countRows($uploaded['full_path']);
						log_message('info', 'fileRowCount:'.$totalRowCount);
						/**************************************************
						 * check if xls file exceeds max row count
						 **************************************************/
						if ($totalRowCount > $this->xlsRowLimit) {
							$data['step'] = 'upload';
							$data['error'] = 'Due to memory constraints, please limit files to less than 5000 rows.';
							$data['realm'] = $realm;
							$data['realms'] = $realms;
							$data['proceed'] = $checks['go'];
							if ($portal == 'service') {
								$data['realm'] = $realm;
								$data['disableRealm'] = true;
							}
							$data['useIPv6'] = $this->useIPv6;
							$this->load->view('bulk_check_users', $data);
							return;
						}
						/**************************************************
						 * process rows (just checks username)
						 **************************************************/
						$rows = $this->util->verifyBulkCheckData($uploaded['full_path'], $realm);
						log_message('info', 'rows@bulkcheck:'.json_encode($rows));
						$invalid = $rows['invalid'];
						$invalidRowNumbers = $rows['invalidRowNumbers'];
						$validTemp = $rows['valid'];
						$validTempRowNumbers = $rows['validRowNumbers'];
						log_message('info', 'invalid:'.count($invalid).'|validTemp:'.count($validTemp));
						$valid = [];
						$validRowNumbers = [];
						$existing = [];
						$existingData = [];
						$variousErrors = [];
						$variousErrorsRowNumbers = [];
						$existingRowNumbers = [];
						for ($i = 0; $i < count($validTemp); $i++) {
							log_message('info', $i.'|'.json_encode($validTemp[$i]));
							$exists = $this->subscribermain->findByUserIdentity($validTemp[$i][0].'@'.$realm);
							if ($exists !== false) { //false means dne
								$existing[] = $validTemp[$i];
								log_message('info', 'existing:'.json_encode($validTemp[$i]));
								$existingRowNumbers[] = $validTempRowNumbers[$i];
								continue;
							}
							$errs = [];
							// if (trim($validTemp[$i][2]) == '' || is_null($validTemp[$i][2])) {
							// 	$errs['CUSTOMERTYPE'] = 'Missing customer type';
							// }
							// Remove Status Validation 6/29/19
							// if (trim($validTemp[$i][3]) == '' || is_null($validTemp[$i][3])) {
							// 	$errs['CUSTOMERSTATUS'] = 'Missing customer status';
							// }
							// if (trim($validTemp[$i][5]) == '' || is_null($validTemp[$i][5])) {
							// 	$errs['RBCUSTOMERNAME'] = 'Missing customer name.';
							// }
							// Remove Validation 7/1/19
							// if (trim($validTemp[$i][6]) == '' || is_null($validTemp[$i][6])) {
							// 	$errs['RBSERVICENUMBER'] = 'Missing service number.';
							// } else {
							// 	$serviceNumberExists = $this->subscribermain->serviceNumberExists(strval($validTemp[$i][6]),
							// 		$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// 	if ($serviceNumberExists) {
							// 		$errs['RBSERVICENUMBER'] = 'Service number already exists.';
							// 	}
							// }
							// if (trim($validTemp[$i][7]) == '' || is_null($validTemp[$i][7])) {
							// 	$errs['RBENABLED'] = 'Missing redirection.';
							// }
							// REMOVE VALIDATION 5/17/19
							// if (trim($validTemp[$i][8]) == '' || is_null($validTemp[$i][8])) {
							// 	$errs['RBACCOUNTPLAN'] = 'Missing service.';
							// } else {
							// 	// $serviceExists = $this->services->serviceExists($validTemp[$i][8]);
							// 	$serviceExists = $this->services->serviceExistsNew2($validTemp[$i][8],
							// 		$this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
							// 	log_message('info', '@bulkcheck: '.$validTemp[$i][8].'|'.json_encode($serviceExists).'|'.$validTemp[$i][8]);
							// 	if (!$serviceExists) {
							// 		$errs['RBACCOUNTPLAN'] = 'Service does not exist.';
							// 	}
							// }
							if ($this->useIPv6) {
								if (trim($validTemp[$i][9]) == '' || is_null($validTemp[$i][9])) {
									//no action
								} else {
									$ipV6Valid = $this->ipaddress->isIPV6Valid($validTemp[$i][9]);
									if (!$ipV6Valid) {
										// check if it is a cabinet name
										$cabinetNameTmp = $validTemp[$i][9];
										$cabinetObj = $this->cabinet->getCabinetWithName($cabinetNameTmp);
										if ($cabinetObj === false || empty($cabinetObj) || is_null($cabinetObj)) {
											$errs['RBADDITIONALSERVICE4'] = 'Not a valid cabinet name nor a valid IPv6 address.';
										}
									} else {
										$ipV6Exists = $this->ipaddress->ipV6Exists($validTemp[$i][9],
											$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
										if (!$ipV6Exists) {
											$errs['RBADDITIONALSERVICE4'] = 'Static IPv6 address not available in IPv6 pool.';
										} else {
											$ipV6Free = $this->ipaddress->isV6Free($validTemp[$i][9],
												$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
											if (!$ipV6Free) {
												$errs['RBADDITIONALSERVICE4'] = 'Static IPv6 address used.';
											}
										}
									}
								}
								if (trim($validTemp[$i][10]) == '' || is_null($validTemp[$i][10])) {
									//no action
								} else {
									$ipValid = $this->util->isIPValid($validTemp[$i][10]);
									if (!$ipValid) {
										$cabinetNameTmp = $validTemp[$i][10];
										$cabinetObj = $this->cabinet->getCabinetWithName($cabinetNameTmp);
										if ($cabinetObj === false || empty($cabinetObj) || is_null($cabinetObj)) {
											$errs['RBIPADDRESS'] = 'Not a valid cabinet name nor a valid IP address.';
										}
									} else {
										// log_message('info', '@2');
										$ipExists = $this->ipaddress->ipExists($validTemp[$i][10],
											$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
										if (!$ipExists) {
											$errs['RBIPADDRESS'] = 'Static IP address not available in IP pool.';
										} else {
											$ipFree = $this->ipaddress->isFree($validTemp[$i][10],
												$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
											if (!$ipFree) {
												$errs['RBIPADDRESS'] = 'Static IP address used.';
											}
										}
									}
								}
								if (trim($validTemp[$i][11]) == '' || is_null($validTemp[$i][11])) {
									//no action
								} else {
									if ($validTemp[$i][10] == '' || is_null($validTemp[$i][10])) {
										$errs['RBMULTISTATIC'] = 'Has Multi IP but no Static IP.';
									} else {
										$netValid = $this->util->isIPValid($validTemp[$i][11]);
										if (!$netValid) {
											$cabinetNameTmp = $validTemp[$i][11];
											$cabinetObj = $this->cabinet->getCabinetWithName($cabinetNameTmp);
											if ($cabinetObj === false || empty($cabinetObj) || is_null($cabinetObj)) {
												$errs['RBMULTISTATIC'] = 'Not a valid cabinet name nor a valid multistatic IP.';
											}
										} else {
											$netExists = $this->netaddress->netExists($validTemp[$i][11],
												$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
											if (!$netExists) {
												$errs['RBMULTISTATIC'] =  'Multi IP address not available in IP pool.';
											} else {
												$netFree = $this->netaddress->isFree($validTemp[$i][11],
													$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
												if (!$netFree) {
													$errs['RBMULTISTATIC'] =  'Multi IP used.';
												}
											}
										}
									}
								}
							} else {
								if (trim($validTemp[$i][9]) == '' || is_null($validTemp[$i][9])) {
									//no action
								} else {
									$ipValid = $this->util->isIPValid($validTemp[$i][9]);
									if (!$ipValid) {
										$cabinetNameTmp = $validTemp[$i][9];
										$cabinetObj = $this->cabinet->getCabinetWithName($cabinetNameTmp);
										if ($cabinetObj === false || empty($cabinetObj) || is_null($cabinetObj)) {
											$errs['RBIPADDRESS'] = 'Not a valid cabinet name nor a valid IP address.';
										}
									} else {
										// log_message('info', '@2');
										$ipExists = $this->ipaddress->ipExists($validTemp[$i][9],
											$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
										if (!$ipExists) {
											$errs['RBIPADDRESS'] = 'Static IP address not available in IP pool.';
										} else {
											$ipFree = $this->ipaddress->isFree($validTemp[$i][9],
												$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
											if (!$ipFree) {
												$errs['RBIPADDRESS'] = 'Static IP address used.';
											}
										}
									}
								}
								if (trim($validTemp[$i][10]) == '' || is_null($validTemp[$i][10])) {
									//no action
								} else {
									if ($validTemp[$i][9] == '' || is_null($validTemp[$i][9])) {
										$errs['RBMULTISTATIC'] = 'Has Multi IP but no Static IP.';
									} else {
										$netValid = $this->util->isIPValid($validTemp[$i][10]);
										if (!$netValid) {
											$cabinetNameTmp = $validTemp[$i][10];
											$cabinetObj = $this->cabinet->getCabinetWithName($cabinetNameTmp);
											if ($cabinetObj === false || empty($cabinetObj) || is_null($cabinetObj)) {
												$errs['RBMULTISTATIC'] = 'Not a valid cabinet name nor a valid multistatic IP.';
											}
										} else {
											$netExists = $this->netaddress->netExists($validTemp[$i][10],
												$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
											if (!$netExists) {
												$errs['RBMULTISTATIC'] =  'Multi IP address not available in IP pool.';
											} else {
												$netFree = $this->netaddress->isFree($validTemp[$i][10],
													$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
												if (!$netFree) {
													$errs['RBMULTISTATIC'] =  'Multi IP used.';
												}
											}
										}
									}
								}
							}

							if (count($errs) == 0) {
								$valid[] = $validTemp[$i];
								$validRowNumbers[] = $validTempRowNumbers[$i];
							} else {
								$variousErrors[] = array('rowdata' => $validTemp[$i], 'errors' => $errs);
								$variousErrorsRowNumbers[] = $validTempRowNumbers[$i];
							}
						}
						$invalid2 = [];
						for ($i = 0; $i < count($existing); $i++) {
							$existingData[] = $this->subscribermain->findByUserIdentity($existing[$i][0].'@'.$realm);
						}
						$data = array(
							'step' => 'show',
							'proceed' => $checks['go'],
							'error' => $checks['msg'],
							'realm' => $realm,
							'path' => $uploaded['full_path'],
							'valid' => $valid,
							'validRowNumbers' => $validRowNumbers,
							'invalid' => $invalid2,
							'invalidRowNumbers' => $invalidRowNumbers,
							'existing' => $existingData,
							'existingRowNumbers' => $existingRowNumbers,
							'variousErrors' => $variousErrors,
							'variousErrorsRowNumbers' => $variousErrorsRowNumbers);
						$data['useIPv6'] = $this->useIPv6;
						$this->load->view('bulk_check_users', $data);
					}
    			}
    		/**************************************************
			 * download step
			 **************************************************/
			} else if ($step == 'download') {
				$path = $this->input->post('path');
				$set = $this->input->post('set');
				$realm = $this->input->post('realm');
				log_message('info', json_encode($this->input->post('setdata')));
				$setdata = unserialize($this->input->post('setdata'));
				log_message('info', json_encode($setdata));
				$this->load->model('util');
				log_message('info', $path.'|'.json_encode($setdata).'|'.$set.'|'.$realm);
				if ($this->useIPv6) {
					$this->util->writeBulkCheckOutputV2($path, $setdata, $set, $realm);
				} else {
					$this->util->writeBulkCheckOutput($path, $setdata, $set, $realm);
				}
			}
		/**************************************************
		 * subscriber/processBulkCheckSubscribers accessed via url
		 **************************************************/
		} else {
			//get realms
			$this->load->model('realm');
			$realms = $this->realm->fetchAllNamesOnly();
			$data = array(
				'realms' => $realms);
			if ($portal == 'service') {
				$data['realm'] = $realm;
				$data['disableRealm'] = true;
			}
			$data['proceed'] = $checks['go'];
			$data['error'] = $checks['msg'];
			$data['useIPv6'] = $this->useIPv6;
			$this->load->view('bulk_check_users', $data);
		}
	}
	/***********************************************************************
	 * create secondary subscriber account
	 * PAGEID = 12
	 ***********************************************************************/
	public function showCreateSubscriber2Form() {
		$this->redirectIfNoAccess('Create Secondary Account', 'subscribers/showCreateSubscriber2Form');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		/**************************************************
		 * check connections
		 **************************************************/
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@showCreateSubscriber2Form|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@showCreateSubscriber2Form|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		//get realms
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$data = array(
			'realms' => $realms);
		if ($portal == 'service') {
			$data['realm'] = $realm;
			$data['disableRealm'] = true;
		}
		$data['proceed'] = $checks['go'];
		$data['error'] = $checks['msg'];
		$this->load->view('create_user_account2', $data);
	}
	public function processCreateSubscriber2() {
		$this->redirectIfNoAccess('Create Secondary Account', 'subscribers/processCreateSubscriber2');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		//get realms
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();

		$realm = $this->input->post('realm');
		$username = $this->input->post('username');
		$username2 = $this->input->post('username2');
		log_message('info', 'realm:'.$realm);
		log_message('info', 'username:'.$username);
		log_message('info', 'username2:'.$username2);
		$data = array(
			'username' => $username,
			'username2' => $username2,
			'realms' => $realms,
			'realm' => $realm);
		$this->load->model('subscribermain');
		$old = $this->subscribermain->findByUserIdentity($username.'@'.$realm);
		if ($old === false) {
			$data['error'] = 'The user '.$username.'@'.$realm.' does not exist in the database.';
			$this->load->view('create_user_account2', $data);
			return;
		}
		$modifieddate = date('Y-m-d H:i:s', time());
		$new = array(
			'USER_IDENTITY' => $old['USER_IDENTITY'],
			'USERNAME' => $old['USERNAME'],
			'BANDWIDTH' => $old['BANDWIDTH'],
			'CUSTOMERSTATUS' => $old['CUSTOMERSTATUS'],
			'PASSWORD' => $old['PASSWORD'],
			'CUSTOMERREPLYITEM' => $old['CUSTOMERREPLYITEM'],
			'CREATEDATE' => $old['CREATEDATE'],
			'LASTMODIFIEDDATE' => $modifieddate,
			'RBCUSTOMERNAME' => $old['RBCUSTOMERNAME'],
			'RBCREATEDBY' => $old['RBCREATEDBY'],
			'RBADDITIONALSERVICE5' => $old['RBADDITIONALSERVICE5'],
			'RBADDITIONALSERVICE4' => $old['RBADDITIONALSERVICE4'],
			'RBADDITIONALSERVICE3' => $old['RBADDITIONALSERVICE3'],
			'RBADDITIONALSERVICE2' => $old['RBADDITIONALSERVICE2'],
			'RBADDITIONALSERVICE1' => $old['RBADDITIONALSERVICE1'],
			'RBCHANGESTATUSDATE' => $old['RBCHANGESTATUSDATE'],
			'RBCHANGESTATUSBY' => $old['RBCHANGESTATUSBY'],
			'RBACTIVATEDDATE' => $old['RBACTIVATEDDATE'],
			'RBACTIVATEDBY' => $old['RBACTIVATEDBY'],
			'RBACCOUNTSTATUS' => $old['RBACCOUNTSTATUS'],
			'RBSVCCODE2' => $old['RBSVCCODE2'],
			'RBACCOUNTPLAN' => $old['RBACCOUNTPLAN'],
			'CUSTOMERTYPE' => $old['CUSTOMERTYPE'],
			'RBSERVICENUMBER' => $old['RBSERVICENUMBER'], //should not change
			'RBCHANGESTATUSFROM' => $old['CUSTOMERSTATUS'],
			'RBSECONDARYACCOUNT' => $username2,
			'RBUNLIMITEDACCESS' => $old['RBUNLIMITEDACCESS'],
			'RBTIMESLOT' => $old['RBTIMESLOT'],
			'RBORDERNUMBER' => $old['RBORDERNUMBER'],
			'RBREMARKS' => $old['RBREMARKS'],
			'RBREALM' => $old['RBREALM'], //should not change
			'RBNUMBEROFSESSION' => $old['RBNUMBEROFSESSION'],
			'RBMULTISTATIC' => $old['RBMULTISTATIC'],
			'RBIPADDRESS' => $old['RBIPADDRESS'],
			'RBENABLED' => $old['RBENABLED']);
		$changed = $this->subscribermain->update($username.'@'.$realm, $new,
			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
		if (!$changed) {
			$data['error'] = 'Secondary account creation failed.';
			$this->load->view('create_user_account2', $data);
			return;
		}
		/***********************************************************
		 * get this from session variable
		 ***********************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		if ($sysuser != $this->SUPERUSER) {
			$this->load->model('subscriberaudittrail');
			$this->subscriberaudittrail->logSubscriberModification($new, $old, $sysuser, $sysuserIP, $modifieddate, true);
		}
		$data['message'] = 'Secondary account created';
		if ($portal == 'service') {
			$data['realm'] = $realm;
			$data['disableRealm'] = true;
		}
		$this->load->view('create_user_account2', $data);
	}
	/***********************************************************************
	 * display subscriber
	 * PAGEID = 13
	 ***********************************************************************/
	public function showDisplayAccountForm() {
		$this->redirectIfNoAccess('Display Account', 'subscribers/showDisplayAccountForm');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		//get realms
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$data = array(
			'show' => false,
			'found' => false,
			'realms' => $realms);
		if ($portal == 'service') {
			$data['realm'] = $realm;
			$data['disableRealm'] = true;
		}
		$this->load->view('display_user_account', $data);
	}
	public function showSubscriberInfo() {
		$this->redirectIfNoAccess('Display Account', 'subscribers/showSubscriberInfo');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		//get realms
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();

		$username = trim($this->input->post('username'));
		$realm = $this->input->post('realm');

		$this->load->model('subscribermain');
		$subscriber = $this->subscribermain->findByUserIdentity($username.'@'.$realm);

		if ($subscriber === false) {
			$data = array(
				'show' => true,
				'found' => false,
				'username' => $username,
				'realm' => $realm,
				'realms' => $realms,
				'error' => 'User '.$username.'@'.$realm.' not found.');
			if ($portal == 'service') {
				$data['realm'] = $realm;
				$data['disableRealm'] = true;
			}
			$this->load->view('display_user_account', $data);
			return;
		}
		$usage = '';
		$informationHistory = '';
		$sessionHistory = '';
		$data = array(
			'show' => true,
			'found' => true,
			'username' => $username,
			'realm' => $realm,
			'realms' => $realms,
			'subscriber' => $subscriber,
			'usage' => $usage,
			'sessionHistory' => $sessionHistory);
		if ($portal == 'service') {
			$data['realm'] = $realm;
			$data['disableRealm'] = true;
		}
		$this->load->view('display_user_account', $data);
	}
	public function showSubscriberInfoViaUrl($username, $realm) {
		$this->redirectIfNoAccess('Display Account', 'subscribers/showSubscriberInfoViaUrl');
		$portal = $this->session->userdata('portal');
		if ($portal == 'service') {
			$realm = $this->session->userdata('realm');
		}
		//get realms
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();

		$cn = $username.'@'.$realm;
		$this->load->model('subscribermain');
		$subscriber = $this->subscribermain->findByUserIdentity($username.'@'.$realm);
		if ($subscriber === false) {
			$data = array(
				'show' => true,
				'found' => false,
				'username' => $username,
				'realm' => $realm,
				'realms' => $realms,
				'error' => 'User '.$username.'@'.$realm.' not found.');
			if ($portal == 'service') {
				$data['realm'] = $realm;
				$data['disableRealm'] = true;
			}
			$this->load->view('display_user_account', $data);
			return;
		}
		$usage = '';
		$informationHistory = '';
		$sessionHistory = '';
		$data = array(
			'show' => true,
			'found' => true,
			'username' => $username,
			'realm' => $realm,
			'realms' => $realms,
			'subscriber' => $subscriber,
			'usage' => $usage,
			'sessionHistory' => $sessionHistory);
		if ($portal == 'service') {
			$data['realm'] = $realm;
			$data['disableRealm'] = true;
		}
		$this->load->view('display_user_account', $data);
	}
	/***********************************************************************
	 * modify subscriber
	 * PAGEID = 14
	 ***********************************************************************/
	public function showUpdateSubscriberForm() {
		$this->redirectIfNoAccess('Modify Account', 'subscribers/showUpdateSubscriberForm');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@showUpdateSubscriberForm|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@showUpdateSubscriberForm|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		//get realms
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$data = array(
			'show' => $checks['go'] ? false : true,
			'found' => false,
			'proceed' => $checks['go'],
			'error' => $checks['msg'],
			'realms' => $realms);
		if ($portal == 'service') {
			$data['realm'] = $realm;
			$data['disableRealm'] = true;
		}
		$data['useIPv6'] = $this->useIPv6;
		$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
		$this->load->view('modify_user_account', $data);
	}

	/*******************************************************************
		* modify subscriber via modify account (admin)

	********************************************************************/

	public function showUpdateSubscriberFormAdmin() {
		$this->redirectIfNoAccess('Modify Account (Admin)', 'subscribers/showUpdateSubscriberFormAdmin');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@showUpdateSubscriberFormAdmin|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@showUpdateSubscriberForm|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		//get realms
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$data = array(
			'show' => $checks['go'] ? false : true,
			'found' => false,
			'proceed' => $checks['go'],
			'error' => $checks['msg'],
			'realms' => $realms);
		if ($portal == 'service') {
			$data['realm'] = $realm;
			$data['disableRealm'] = true;
		}
		$data['useIPv6'] = $this->useIPv6;
		$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
		$this->load->view('modify_user_account_admin', $data);
	}

	public function loadUpdateSubscriberForm() {
		$this->redirectIfNoAccess('Modify Account', 'subscribers/loadUpdateSubscriberForm');
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@loadUpdateSubscriberForm|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@loadUpdateSubscriberForm|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		//get realms
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$username = trim($this->input->post('username'));
		$realm = $this->input->post('realm');
		$cn = $username.'@'.$realm;
		$this->load->model('subscribermain');
		/**************************************************
		 * fetch subscriber data
		 **************************************************/
		$subscriber = $this->subscribermain->findByUserIdentity($cn);
		log_message('info', 'LOAD (for modify): '.json_encode($subscriber));
		/**************************************************
		 * account does not exist, return message
		 **************************************************/
		if ($subscriber === false) {
			$data = array(
				'show' => true,
				'found' => false,
				'proceed' => $checks['go'],
				'message' => $checks['msg'],
				'username' => $username,
				'realm' => $realm,
				'realms' => $realms,
				'error' => 'User '.$cn.' not found.');
			if ($portal == 'service') {
				$data['realm'] = $realm;
				$data['disableRealm'] = true;
			}
			$data['useIPv6'] = $this->useIPv6;
			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
			$this->load->view('modify_user_account', $data);
		/**************************************************
		 * account found
		 **************************************************/
		} else {
			//get services
			$this->load->model('services');
			// $allServices = $this->services->fetchAllNamesOnly2();
			$allServices = $this->services->fetchAllNamesOnlyNew($this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
			/************************************************************
			 * fetch + format subscriber information and session histories
			 * blank for now
			 ************************************************************/
			$sessionHistory = '';
			$data = array(
				'show' => true,
				'found' => true,
				'proceed' => $checks['go'],
				'error' => $checks['msg'],
				'username' => $username,
				'realm' => $realm,
				'realms' => $realms,
				'subscriber' => $subscriber,
				'allServicesFilter' => $allServices,
				'svccode' => str_replace('~', '-', $subscriber['RBACCOUNTPLAN']),
				'svc_add1' => $subscriber['RBADDITIONALSERVICE1'],
				'svc_add2' => $subscriber['RBADDITIONALSERVICE2'],
				'current_remarks' => $subscriber['RBREMARKS'],
				'new_remarks' => '',
				//'informationHistory' => $subscriber['RBREMARKS'],
				'sessionHistory' => $sessionHistory);
			if ($portal == 'service') {
				$data['realm'] = $realm;
				$data['disableRealm'] = true;
			}
			$data['useIPv6'] = $this->useIPv6;
			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
			$this->load->view('modify_user_account', $data);
		}
	}

	/*****************************************************
		modify account (admin) form
	
	******************************************************/

	public function loadUpdateSubscriberFormAdmin() {
		$this->redirectIfNoAccess('Modify Account (Admin)', 'subscribers/loadUpdateSubscriberFormAdmin');
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@loadUpdateSubscriberFormAdmin|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@loadUpdateSubscriberForm|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		//get realms
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$username = trim($this->input->post('username'));
		$realm = $this->input->post('realm');
		$cn = $username.'@'.$realm;
		$this->load->model('subscribermain');
		/**************************************************
		 * fetch subscriber data
		 **************************************************/
		$subscriber = $this->subscribermain->findByUserIdentity($cn);
		log_message('info', 'LOAD (for modify): '.json_encode($subscriber));
		/**************************************************
		 * account does not exist, return message
		 **************************************************/
		if ($subscriber === false) {
			$data = array(
				'show' => true,
				'found' => false,
				'proceed' => $checks['go'],
				'message' => $checks['msg'],
				'username' => $username,
				'realm' => $realm,
				'realms' => $realms,
				'error' => 'User '.$cn.' not found.');
			if ($portal == 'service') {
				$data['realm'] = $realm;
				$data['disableRealm'] = true;
			}
			$data['useIPv6'] = $this->useIPv6;
			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
			$this->load->view('modify_user_account_admin', $data);
		/**************************************************
		 * account found
		 **************************************************/
		} else {
			//get services
			$this->load->model('services');
			// $allServices = $this->services->fetchAllNamesOnly2();
			$allServices = $this->services->fetchAllNamesOnlyNew($this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
			/************************************************************
			 * fetch + format subscriber information and session histories
			 * blank for now
			 ************************************************************/
			$sessionHistory = '';
			$data = array(
				'show' => true,
				'found' => true,
				'proceed' => $checks['go'],
				'error' => $checks['msg'],
				'username' => $username,
				'realm' => $realm,
				'realms' => $realms,
				'subscriber' => $subscriber,
				'allServicesFilter' => $allServices,
				'svccode' => str_replace('~', '-', $subscriber['RBACCOUNTPLAN']),
				'svc_add1' => $subscriber['RBADDITIONALSERVICE1'],
				'svc_add2' => $subscriber['RBADDITIONALSERVICE2'],
				'current_remarks' => $subscriber['RBREMARKS'],
				'new_remarks' => '',
				//'informationHistory' => $subscriber['RBREMARKS'],
				'sessionHistory' => $sessionHistory);
			if ($portal == 'service') {
				$data['realm'] = $realm;
				$data['disableRealm'] = true;
			}
			$data['useIPv6'] = $this->useIPv6;
			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
			$this->load->view('modify_user_account_admin', $data);
		}
	}


	public function loadUpdateSubscriberFormViaUrl($username, $realm) {
		$this->redirectIfNoAccess('Modify Account', 'subscribers/loadUpdateSubscriberFormViaUrl');
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@loadUpdateSubscriberFormViaUrl|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@loadUpdateSubscriberFormViaUrl|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		$portal = $this->session->userdata('portal');
		if ($portal == 'service') {
			$realm = $this->session->userdata('realm');
		}
		//get realms
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$cn = $username.'@'.$realm;
		$this->load->model('subscribermain');
		/**************************************************
		 * fetch subscriber data
		 **************************************************/
		$subscriber = $this->subscribermain->findByUserIdentity($cn);
		log_message('info', 'SEARCH: '.json_encode($subscriber));
		/**************************************************
		 * account not found, return message
		 **************************************************/
		if ($subscriber === false) {
			$data = array(
				'show' => true,
				'found' => false,
				'username' => $username,
				'realm' => $realm,
				'realms' => $realms,
				'error' => 'User '.$cn.' not found.');
			if ($portal == 'service') {
				$data['realm'] = $realm;
				$data['disableRealm'] = true;
			}
			$data['useIPv6'] = $this->useIPv6;
			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
			$this->load->view('modify_user_account', $data);
		/**************************************************
		 * account found
		 **************************************************/
		} else {
			//get services
			$this->load->model('services');
			// $allServices = $this->services->fetchAllNamesOnly2();
			$allServices = $this->services->fetchAllNamesOnlyNew($this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
			$sessionHistory = '';
			/************************************************************
			 * fetch + format subscriber information and session histories
			 * blank for now
			 ************************************************************/
			$data = array(
				'show' => true,
				'found' => true,
				'proceed' => $checks['go'],
				'error' => $checks['msg'],
				'username' => $username,
				'realm' => $realm,
				'realms' => $realms,
				'subscriber' => $subscriber,
				'allServicesFilter' => $allServices,
				'svccode' => str_replace('~', '-', $subscriber['RBACCOUNTPLAN']),
				'svc_add1' => $subscriber['RBADDITIONALSERVICE1'],
				'svc_add2' => $subscriber['RBADDITIONALSERVICE2'],
				'current_remarks' => $subscriber['RBREMARKS'],
				'new_remarks' => '',
				//'informationHistory' => $subscriber['RBREMARKS'],
				'sessionHistory' => $sessionHistory);
			if ($portal == 'service') {
				$data['realm'] = $realm;
				$data['disableRealm'] = true;
			}
			$data['useIPv6'] = $this->useIPv6;
			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
			$this->load->view('modify_user_account', $data);
		}
	}
	public function processUpdateSubscriber() {
		$this->redirectIfNoAccess('Modify Account', 'subscribers/processUpdateSubscriber');
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@processUpdateSubscriber|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@processUpdateSubscriber|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		$this->load->model('services');
		$this->load->model('subscribermain');
		$this->load->model('ipaddress');
		$this->load->model('netaddress');
		$this->load->model('util');
		//get services
		// $allServices = $this->services->fetchAllNamesOnly2();
		$allServices = $this->services->fetchAllNamesOnlyNew($this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
		//get realms
		$this->load->model('realm');
		$this->load->model('onlinesession');
		$realms = $this->realm->fetchAllNamesOnly();
		$realm = $this->input->post('realm');
		$username = trim($this->input->post('username'));
		$cn = $username.'@'.$realm;
		$password = trim($this->input->post('password1'));
		// Null value on acctplan, nonedsl, svccode 5/17/19
		$acctplan = ""; //$this->input->post('acctplan'); //'Residential' or 'Business'
		$status = $this->input->post('status');
		$custname = trim($this->input->post('custname'));
		$ordernum = trim($this->input->post('ordernum'));
		$servicenum = trim($this->input->post('servicenum'));
		$nonedsl = ""; //$this->input->post('nonedsl'); //'Yes' or 'No'
		$svccode = ""; //$this->input->post('svccode');
		$ipv6address = trim($this->input->post('ipv6address'));
		$ipaddress = trim($this->input->post('ipaddress'));
		$netaddress = trim($this->input->post('netaddress'));
		// $dynamicip = trim($this->input->post('dynamicip')); //uncomment this to enable dynamic pool (check also at views/modify_user_account.php)
		$dynamicip = '';
		$nasIdentifier = trim($this->input->post('nasIdentifier'));
		$svc_add1 = $this->input->post('svc_add1');
		$svc_add2 = $this->input->post('svc_add2');
		$newRemarks = trim($this->input->post('remarks'));
		$currentRemarks = trim($this->input->post('current_remarks'));
		$informationHistory = trim($this->input->post('informationHistory'));
		$sessionHistory = trim($this->input->post('sessionHistory'));

		/**************************************************
		 * get this from session variable
		 **************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		if ($status == 'T') {
			$ipaddress = '';
			$netaddress = '';
		}
		$modifieddate = date('Y-m-d H:i:s', time());

		$rbremarks = $currentRemarks.((!isset($newRemarks) || is_null($newRemarks) || $newRemarks == '') ? '' : ';'.date('Y-m-d H:i:s', time()).' '.$newRemarks);
		if (strlen($rbremarks) > 3500) {
			$rbremarks = substr($rbremarks, strlen($rbremarks) - 3500);
		}
		/*
		if (!isset($newRemarks) || is_null($newRemarks) || $newRemarks == '') {
			$rbremarks = $currentRemarks;
		} else {
			$rbremarks = $currentRemarks.';'.date('Y-m-d H:i:s', time()).' '.$newRemarks;
			if (strlen($rbremarks) > 3500) {
				$rbremarks = substr($rbremarks, 0, 3500);
			}
		}
		*/
		$subscriberOld = $this->subscribermain->findByUserIdentity($cn);
		/**************************************************
		 * prepare new subscriber data
		 **************************************************/
		$subscriber = array(
			'USER_IDENTITY' => $cn,
			'USERNAME' => $cn,
			'BANDWIDTH' => $subscriberOld['BANDWIDTH'],
			'CUSTOMERSTATUS' => $subscriberOld['CUSTOMERSTATUS'] == $status ? $subscriberOld['CUSTOMERSTATUS'] : $status,
			'PASSWORD' => $subscriberOld['PASSWORD'] == $password ? $subscriberOld['PASSWORD'] : $password,
			'CUSTOMERREPLYITEM' => $subscriberOld['CUSTOMERREPLYITEM'],
			'CREATEDATE' => $subscriberOld['CREATEDATE'],
			'LASTMODIFIEDDATE' => $modifieddate,
			'RBCUSTOMERNAME' => $subscriberOld['RBCUSTOMERNAME'] == $custname ? $subscriberOld['RBCUSTOMERNAME'] : $custname,
			'RBCREATEDBY' => $subscriberOld['RBCREATEDBY'],
			'RBADDITIONALSERVICE5' => $subscriberOld['RBADDITIONALSERVICE5'],
			'RBADDITIONALSERVICE4' => $ipv6address == '' ? null : ($subscriberOld['RBADDITIONALSERVICE4'] == $ipv6address ? $subscriberOld['RBADDITIONALSERVICE4'] : $ipv6address),
			'RBADDITIONALSERVICE3' => $dynamicip == '' ? null : ($subscriberOld['RBADDITIONALSERVICE3'] == $nasIdentifier ? $subscriberOld['RBADDITIONALSERVICE3'] : $nasIdentifier),
			'RBADDITIONALSERVICE2' => $svc_add2 == '' ? null : ($subscriberOld['RBADDITIONALSERVICE2'] == $svc_add2 ? $subscriberOld['RBADDITIONALSERVICE2'] : $svc_add2),
			'RBADDITIONALSERVICE1' => $svc_add1 == '' ? null : ($subscriberOld['RBADDITIONALSERVICE1'] == $svc_add1 ? $subscriberOld['RBADDITIONALSERVICE1'] : $svc_add1),
			'RBCHANGESTATUSDATE' => $subscriberOld['CUSTOMERSTATUS'] == $status ? $subscriberOld['RBCHANGESTATUSDATE'] : $modifieddate,
			'RBCHANGESTATUSBY' => $subscriberOld['CUSTOMERSTATUS'] == $status ? $subscriberOld['RBCHANGESTATUSBY'] : $sysuser,
			'RBACTIVATEDDATE' => $status == 'Active' && $subscriberOld['CUSTOMERSTATUS'] != 'Active' ? $modifieddate : $subscriberOld['RBACTIVATEDDATE'],
			'RBACTIVATEDBY' => $status == 'Active' && $subscriberOld['CUSTOMERSTATUS'] != 'Active' ? $sysuser : $subscriberOld['RBACTIVATEDBY'],
			'RBACCOUNTSTATUS' => $subscriberOld['RBACCOUNTSTATUS'],
			'RBSVCCODE2' => $subscriberOld['RBSVCCODE2'],
			'RBACCOUNTPLAN' => str_replace('~', '-', $subscriberOld['RBACCOUNTPLAN']) == $svccode ? str_replace('~', '-', $subscriberOld['RBACCOUNTPLAN']) : $svccode,
			'RADIUSPOLICY' => str_replace('~', '-', $subscriberOld['RADIUSPOLICY']) == $svccode ? str_replace('~', '-', $subscriberOld['RADIUSPOLICY']) : $svccode,
			'CUSTOMERTYPE' => $subscriberOld['CUSTOMERTYPE'] == $acctplan ? $subscriberOld['CUSTOMERTYPE'] : $acctplan,
			'RBSERVICENUMBER' => $servicenum,
			'RBCHANGESTATUSFROM' => $subscriberOld['CUSTOMERSTATUS'],
			'RBSECONDARYACCOUNT' => $subscriberOld['RBSECONDARYACCOUNT'],
			'RBUNLIMITEDACCESS' => $subscriberOld['RBUNLIMITEDACCESS'],
			'RBTIMESLOT' => $subscriberOld['RBTIMESLOT'],
			'RBORDERNUMBER' => $ordernum == '' ? null : ($subscriberOld['RBORDERNUMBER'] == $ordernum ? $subscriberOld['RBORDERNUMBER'] : $ordernum),
			'RBREMARKS' => $rbremarks,
			'RBREALM' => $subscriberOld['RBREALM'], //should not change
			'RBNUMBEROFSESSION' => $subscriberOld['RBNUMBEROFSESSION'],
			'RBMULTISTATIC' => $netaddress == '' ? null : ($subscriberOld['RBMULTISTATIC'] == $netaddress ? $subscriberOld['RBMULTISTATIC'] : $netaddress),
			'RBIPADDRESS' => $ipaddress == '' ? null : ($subscriberOld['RBIPADDRESS'] == $ipaddress ? $subscriberOld['RBIPADDRESS'] : $ipaddress),
			'RBENABLED' => $nonedsl);
		/**************************************************
		 * get CUSTOMERREPLYITEM value
		 **************************************************/
		$subscriber['CUSTOMERREPLYITEM'] = $this->subscribermain->generateCustomerReplyItemValue(
			$subscriber['RBADDITIONALSERVICE4'], $subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC']);
		log_message('debug', '               ');
		log_message('debug', 'CUSTOMERREPLYITEM:'.$subscriber['CUSTOMERREPLYITEM']);
		log_message('debug', '               ');
		if (is_null($subscriber['RBIPADDRESS']) && !is_null($subscriber['RBADDITIONALSERVICE3'])) {
			$subscriber['CUSTOMERREPLYITEM'] = $dynamicip;
		}
		/**************************************************
		 * update RBENABLED value
		 **************************************************/
		if (strtolower($subscriber['RBENABLED']) == 'yes' && strtolower($subscriber['CUSTOMERTYPE']) == 'residential') {
			$subscriber['RBENABLED'] = 'Yes';
		} else {
			$subscriber['RBENABLED'] = 'No';
		}
		log_message('info', 'current:'.json_encode($subscriberOld));
		log_message('info', 'new:'.json_encode($subscriber));
		/**************************************************
		 * data to pass to view on update fail
		 **************************************************/
		$data = array(
			'proceed' => $checks['go'],
			'error' => $checks['msg'],
			'show' => true,
			'found' => true,
			'username' => $username,
			'realm' => $realm,
			'realms' => $realms,
			'subscriber' => $subscriber,
			'allServicesFilter' => $allServices,
			'svccode' => $subscriber['RBACCOUNTPLAN'],
			'svc_add1' => $subscriber['RBADDITIONALSERVICE1'],
			'svc_add2' => $subscriber['RBADDITIONALSERVICE2'],
			'current_remarks' => $currentRemarks,
			'new_remarks' => $newRemarks,
			'informationHistory' => $informationHistory,
			'sessionHistory' => $sessionHistory);
		if ($checks['go'] == false) {
			log_message('debug', '@processUpdateSubscriber: '.json_encode($checks));
			// $data['proceed'] = $checks['go'];
			// $data['error'] = $checks['msg'];
			$data['useIPv6'] = $this->useIPv6;
			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
			$this->load->view('modify_user_account', $data);
			return;
		}
		/**************************************************
		 * goes here if $status (now in $subscriber['CUSTOMERSTATUS']) is 'T', delete account
		 **************************************************/
		if ($subscriber['CUSTOMERSTATUS'] == 'T') {
			/**************************************************
			 * free attached ip/ipv6/net address
			 **************************************************/
			if ($this->useIPv6 && !is_null($subscriberOld['RBADDITIONALSERVICE4'])) {
				$this->ipaddress->freeUpV6($subscriberOld['RBADDITIONALSERVICE4'],
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			}
			if (!is_null($subscriberOld['RBIPADDRESS'])) {
				$this->ipaddress->freeUp($subscriberOld['RBIPADDRESS'],
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			}
			if (!is_null($subscriberOld['RBMULTISTATIC'])) {
				$this->netaddress->freeUp($subscriberOld['RBMULTISTATIC'],
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			}
			$this->subscribermain->delete($subscriber['USER_IDENTITY'],
				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			/**************************************************
			 * if there were still changes made along with the 'T' status, those will be shown in the csv file
			 * if the old data is to be written in the csv, use $subscriberOld
			 **************************************************/
			$subscribers = array();
			$subscribers[] = $subscriber;
			$this->util->writeToDeletedSubscriberFile($subscribers, time());
			/**************************************************
			 * delete account in npm (if npm is enabled)
			 **************************************************/
			if ($this->ENABLENPM) {
				$npmDeleted = $this->subscribermain->npmRemoveSubscriber($subscriber['USERNAME'],
					$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
			}
			/**************************************************
			 * log account deletion
			 **************************************************/
			if ($sysuser != $this->SUPERUSER) {
				$this->load->model('subscriberaudittrail');
				$this->subscriberaudittrail->logSubscriberDeletion($subscriber, $sysuser, $sysuserIP, date('Y-m-d H:i:s', time()));
			}
			/**************************************************
			 * find sessions, delete if found
			 **************************************************/
			$parts = explode('@', $subscriber['USERNAME']);
			$this->load->model('onlinesession');
			$sessions = $this->onlinesession->getSessions2($parts[0], $parts[1], $this->USESESSIONTABLE2,
				$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
			$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
			log_message('info', 'sessions@terminate:'.json_encode($sessions));
			if ($sessions['status'] && isset($sessions['data'])) {
				for ($ii = 0; $ii < count($sessions['data']); $ii++) {
					$sess = $sessions['data'][$ii];
					$deleted = $this->onlinesession->requestDisconnect($sess['USER_NAME'], $sess['NAS_IP_ADDRESS'], $sess['ACCT_SESSION_ID'], $this->USESESSIONTABLE2,
						$this->DELETESESSIONAPIHOST, $this->DELETESESSIONAPIPORT, $this->DELETESESSIONAPISTUB,
						$this->DELETESESSIONAPIHOST2, $this->DELETESESSIONAPIPORT2, $this->DELETESESSIONAPISTUB2,
						$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
						$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2,
						$this->TBLMCOREHOST, $this->TBLMCOREPORT, $this->TBLMCORESCHEMA, $this->TBLMCOREUSERNAME, $this->TBLMCOREPASSWORD,
						$this->TBLMCOREHOST2, $this->TBLMCOREPORT2, $this->TBLMCORESCHEMA2, $this->TBLMCOREUSERNAME2, $this->TBLMCOREPASSWORD2);
				}
			}
			/**************************************************
			 * RM
			 **************************************************/
			// Remove Dependencies 5/21/19
			// $this->load->library('rm');
			// $rmClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
			/**************************************************
			 * if branch for handling old rm api
			 * - as of nov 18, 2016 this is no longer used
			 * - remove this later (including closing brace before /RM marker)
			 **************************************************/
			/*
			if ($this->RMAPIHOST == '10.244.4.130' || $this->RMAPIHOST == '10.244.4.131') {
				try {
					$rmResult['exists'] = true; //try delete
					if (!$rmResult['exists']) {
						log_message('debug', 'not found @ RM: '.$subscriber['USERNAME']);
					} else {
						$rmResult = $this->rm->deleteAccount($subscriber['USERNAME'], $rmClient);
						if (intval($rmResult['responseCode']) == 200 || intval($rmResult['responseCode']) == 201) {
							log_message('debug', 'deleted @ RM: '.$subscriber['USERNAME']);
						} else {
							log_message('debug', 'failed to delete @ RM: '.$subscriber['USERNAME'].' | '.$rmResult['responseMessage']);
						}
					}
				} catch (Exception $e) {
						log_message('debug', 'error @ RM:'.json_encode($e));
				}
			} else if ($this->RMAPIHOST == '10.81.54.34' || $this->RMAPIHOST == '10.81.54.35') {
			*/
			// try {
			// 	$deleteResult = $this->rm->wsDeleteSubscriberProfile($rmClient, $subscriber['USERNAME']);
			// 	if (intval($deleteResult['responseCode']) == 200 || intval($deleteResult['responseCode']) == 404) {
			// 		log_message('debug', 'deleted @ RM: '.$subscriber['USERNAME']);
			// 		if (intval($deleteResult['responseCode']) == 200) {
			// 			$purgeResult = $this->rm->wsPurgeSubscriber($rmClient, $subscriber['USERNAME']);
			// 			if (intval($purgeResult['responseCode']) == 200) {
			// 				log_message('debug', 'purged @ RM: '.$subscriber['USERNAME']);
			// 			} else {
			// 				log_message('debug', 'failed to purge @ RM: '.$subscriber['USERNAME'].' | '.$purgeResult['responseMessage']);
			// 			}
			// 		}
			// 	} else {
			// 		log_message('debug', 'failed to delete @ RM: '.$subscriber['USERNAME'].' | '.$deleteResult['responseMessage']);
			// 	}
			// } catch (Exception $e) {
			// 	log_message('debug', 'error @ RM:'.json_encode($e));
			// }
			/*
			}
			*/
			/**************************************************
			 * /RM
			 **************************************************/
			$dataOk = array(
				'subscriber' => $subscriber,
				'message' => 'Account deletion successful.',
				'informationHistory' => $informationHistory,
				'sessionHistory' => $sessionHistory);
			$dataOk['useIPv6'] = $this->useIPv6;
			$this->load->view('modify_user_account_result', $dataOk);
		} else {
			/**************************************************
			 * checking new subscriber data validity
			 **************************************************/
			$usernameHasUppercase = preg_match('/[A-Z]{1}/', $username);
			if ($usernameHasUppercase) {
				$data['error'] = 'Account modification failed. Username must not have uppercase characters.';
				$data['useIPv6'] = $this->useIPv6;
				$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
				$this->load->view('modify_user_account', $data);
				return;
			}
			/**************************************************
			 * check if username has special characters
			 **************************************************/
			$hasSpecialChars = preg_match('/[^a-zA-Z0-9._-]/', $username);
			if ($hasSpecialChars) {
				$data['error'] = 'Account modification failed. Username must only use alphanumeric characters.';
				$data['useIPv6'] = $this->useIPv6;
				$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
				$this->load->view('modify_user_account', $data);
				return;
			}
			/**************************************************
			 * check if new plan matches with gpon type or not: plans with 'gpon' string must have gpon type ip address
			 **************************************************/
			if (!is_null($subscriber['RBIPADDRESS'])) {
				$srv = strtolower($subscriber['RBACCOUNTPLAN']);
				$ipMustBeGPON = true;
				if (strpos($srv, 'gpon') === false) {
					$ipMustBeGPON = false;
				}
				$isGPON = $this->ipaddress->isGPON($subscriber['RBIPADDRESS'],
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				if ($ipMustBeGPON && !$isGPON) {
					$data['error'] = 'Static IP not allowed for GPON type profile';
					$data['useIPv6'] = $this->useIPv6;
					$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
					$this->load->view('modify_user_account', $data);
					return;
				} else if (!$ipMustBeGPON && $isGPON) {
					$data['error'] = 'Static IP not allowed for non-GPON type profile';
					$data['useIPv6'] = $this->useIPv6;
					$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
					$this->load->view('modify_user_account', $data);
					return;
				}
			}
			// $valid = $this->subscribermain->isValid($subscriber);
			$valid = $this->subscribermain->isValidNew($subscriber,
				$this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
			log_message('info', 'valid:'.json_encode($valid));
			/**************************************************
			 * new subscriber data has invalid fields
			 **************************************************/
			if (!$valid['status']) {
				$data['error'] = 'Account modification failed. Some fields are invalid.';
				$data['useIPv6'] = $this->useIPv6;
				$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
				$this->load->view('modify_user_account', $data);
			/**************************************************
			* ok to update
			**************************************************/
			} else {
				/**************************************************
				 * check if new servicenumber is unique
				 **************************************************/
				if ($subscriberOld['RBSERVICENUMBER'] != $subscriber['RBSERVICENUMBER']) {
					$serviceNumberExists = $this->subscribermain->serviceNumberExists($subscriber['RBSERVICENUMBER'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if ($serviceNumberExists) {
						$data['error'] = 'Account modification failed. Service number '.$subscriber['RBSERVICENUMBER'].' already exists in the database.';
						$data['useIPv6'] = $this->useIPv6;
						$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						$this->load->view('modify_user_account', $data);
						return;
					}
				}
				/**************************************************
				 * do changes to ip/ipv6/net addresses
				 **************************************************/
				if ($this->useIPv6) {
					if (!is_null($subscriberOld['RBADDITIONALSERVICE4']) && is_null($subscriber['RBADDITIONALSERVICE4'])) { //removing current ipv6 address
						$this->ipaddress->freeUpV6($subscriberOld['RBADDITIONALSERVICE4'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					} else if (is_null($subscriberOld['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4'])) { //no ipv6 -> with ipv6
						$ipv6Free = $this->ipaddress->isV6Free($subscriber['RBADDITIONALSERVICE4'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						if (!$ipv6Free) {
							$data['error'] = 'Account modification failed. IPv6 Address is already used.';
							$data['useIPv6'] = $this->useIPv6;
							$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
							$this->load->view('modify_user_account', $data);
						} else {
							$this->ipaddress->markV6AsUsed($subscriber['RBADDITIONALSERVICE4'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
					} else if (!is_null($subscriberOld['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4'])
						&& $subscriberOld['RBADDITIONALSERVICE4'] != $subscriber['RBADDITIONALSERVICE4']) { //changing ipv6 address
						$ipv6Free = $this->ipaddress->isV6Free($subscriber['RBADDITIONALSERVICE4'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						if (!$ipv6Free) {
							$data['error'] = 'Account modification failed. IPv6 address is already used.';
							$data['useIPv6'] = $this->useIPv6;
							$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
							$this->load->view('modify_user_account', $data);
						} else {
							$this->ipaddress->freeUpV6($subscriberOld['RBADDITIONALSERVICE4'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->ipaddress->markV6AsUsed($subscriber['RBADDITIONALSERVICE4'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
					}
				}
				if (!is_null($subscriberOld['RBIPADDRESS']) && is_null($subscriber['RBIPADDRESS'])) {  //removing current ip address
					$this->ipaddress->freeUp($subscriberOld['RBIPADDRESS'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				} else if (is_null($subscriberOld['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS'])) { //no ip -> with ip
					$ipFree = $this->ipaddress->isFree($subscriber['RBIPADDRESS'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if (!$ipFree) {
						$data['error'] = 'Account modification failed. IP Address is already used.';
						$data['useIPv6'] = $this->useIPv6;
						$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						$this->load->view('modify_user_account', $data);
					} else {
						$this->ipaddress->markAsUsed($subscriber['RBIPADDRESS'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
				} else if (!is_null($subscriberOld['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS']) && $subscriberOld['RBIPADDRESS'] != $subscriber['RBIPADDRESS']) { //changing ip address
					$ipFree = $this->ipaddress->isFree($subscriber['RBIPADDRESS'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if (!$ipFree) {
						$data['error'] = 'Account modification failed. IP Address is already used.';
						$data['useIPv6'] = $this->useIPv6;
						$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						$this->load->view('modify_user_account', $data);
					} else {
						$this->ipaddress->freeUp($subscriberOld['RBIPADDRESS'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						$this->ipaddress->markAsUsed($subscriber['RBIPADDRESS'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
				}
				if (!is_null($subscriberOld['RBMULTISTATIC']) && is_null($subscriber['RBMULTISTATIC'])) { //removing current net address
					$this->netaddress->freeUp($subscriberOld['RBMULTISTATIC'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				} else if (is_null($subscriberOld['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC'])) { //no net -> with net
					$netFree = $this->netaddress->isFree($subscriber['RBMULTISTATIC'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if (!$netFree) {
						$data['error'] = 'Account creation failed. Network address is already used.';
						$data['useIPv6'] = $this->useIPv6;
						$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						$this->load->view('modify_user_account', $data);
					} else {
						$this->netaddress->markAsUsed($subscriber['RBMULTISTATIC'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
				} else if (!is_null($subscriberOld['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC']) && $subscriberOld['RBMULTISTATIC'] != $subscriber['RBMULTISTATIC']) { //changing net address
					$netFree = $this->netaddress->isFree($subscriber['RBMULTISTATIC'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if (!$netFree) {
						$data['error'] = 'Account creation failed. Network address is already used.';
						$data['useIPv6'] = $this->useIPv6;
						$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						$this->load->view('modify_user_account', $data);
					} else {
						$this->netaddress->freeUp($subscriberOld['RBMULTISTATIC'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						$this->netaddress->markAsUsed($subscriber['RBMULTISTATIC'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
				}
				if (!is_null($subscriber['RBMULTISTATIC'])) {
					log_message('info', '@modify: '.$subscriber['USERNAME'].' has valid RBMULTISTATIC, setting RBENABLED to \'No\'');
					$subscriber['RBENABLED'] = 'No';
				}
				/**************************************************
				 * uncomment the next line if RBENABLED is fixed to 'No' and FAP to 'N'
				 * FAP is dependent on the value of $subscriber['RBENABLED']: 'No' => 'N', 'Yes' => 'Y'
				 **************************************************/
				// $subscriber['RBENABLED'] = 'No';
				/**************************************************
				 * update subscriber
				 **************************************************/
				log_message('debug', '@processUpdateSubscriber|subscriber:'.$subscriber['USERNAME'].'|RBENABLED:'.$subscriber['RBENABLED']);
				$updated = $this->subscribermain->update($cn, $subscriber,
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				log_message('info', 'updated:'.json_encode($updated));
				/**************************************************
				 * update failed
				 **************************************************/
				if (!$updated) {
					/**************************************************
					 * revert any changes made to ipv6/ip/net addresses
					 **************************************************/
					if ($this->useIPv6) {
						if (!is_null($subscriberOld['RBADDITIONALSERVICE4']) && is_null($subscriber['RBADDITIONALSERVICE4'])) { //re-mark old ipv6 as used
							$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						} else if (is_null($subscriberOld['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4'])) { //unmark ipv6
							$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						} else if (!is_null($subscriberOld['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4'])) { //swap back ipv6 addresses
							$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
					}
					if (!is_null($subscriberOld['RBIPADDRESS']) && is_null($subscriber['RBIPADDRESS'])) {  //re-mark old ip as used
						$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					} else if (is_null($subscriberOld['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS'])) { //unmark ip
						$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					} else if (!is_null($subscriberOld['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS'])) { //swap back ip addresses
						$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
					if (!is_null($subscriberOld['RBMULTISTATIC']) && is_null($subscriber['RBMULTISTATIC'])) { //re-mark old net as used
						$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					} else if (is_null($subscriberOld['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC'])) { //unmark net
						$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					} else if (!is_null($subscriberOld['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC'])) { //swap back netaddresses
						$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
					$data['error'] = 'Failed to update subscriber. An unknown error occurred';
					$data['useIPv6'] = $this->useIPv6;
					$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
					$this->load->view('modify_user_account', $data);
				/**************************************************
				 * account updated
				 **************************************************/
				} else {
					/**************************************************
					 * perform changes to ipv6/ip/net status (if any)
					 **************************************************/
					if ($subscriberOld['CUSTOMERSTATUS'] != $subscriber['CUSTOMERSTATUS']) {
						if ($this->useIPv6 && !is_null($subscriber['RBADDITIONALSERVICE4'])) {
							$this->ipaddress->updateV6Status($subscriber['RBADDITIONALSERVICE4'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if (!is_null($subscriber['RBIPADDRESS'])) {
							$this->ipaddress->updateStatus($subscriber['RBIPADDRESS'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if(!is_null($subscriber['RBMULTISTATIC'])) {
							$this->netaddress->updateStatus($subscriber['RBMULTISTATIC'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
					}
					/**************************************************
					 * delete sessions if plan (RBACCOUNTPLAN) is new
					 **************************************************/
					$newRP = trim($subscriber['RBACCOUNTPLAN']);
					$newRP = str_replace('~', '-', $newRP);
					$oldRP = trim($subscriberOld['RBACCOUNTPLAN']);
					$oldRP = str_replace('~', '-', $oldRP);
					log_message('debug', 'old plan:'.$oldRP.', new plan:'.$newRP);
					if ($newRP != $oldRP) {
						$sessions = $this->onlinesession->getSessions2($username, $realm, $this->USESESSIONTABLE2,
							$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
							$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
						if ($sessions['status'] && isset($sessions['data'])) {
							for ($ii = 0; $ii < count($sessions['data']); $ii++) {
								$sess = $sessions['data'][$ii];
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
					/************************************************************
					 * do npm update if enabled, if not $npmResult['result'] will default to true, essentially skipping the npm operation
					 ************************************************************/
					if ($this->ENABLENPM) {
						$inNPM = $this->subscribermain->npmFetchXML($subscriber['USERNAME'],
							$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
						log_message('info', 'in npm:'.json_encode($inNPM));
						if ($inNPM['found']) {
							$npmResult = $this->subscribermain->npmUpdateXML($subscriber['USERNAME'], $subscriber['PASSWORD'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'Y' : 'N',
								str_replace('~', '-', $subscriber['RBACCOUNTPLAN']), $subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC'], 'N',
								$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
						} else {
							$npmResult = $this->subscribermain->npmCreateXML($subscriber['USERNAME'], $subscriber['PASSWORD'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'Y' : 'N',
								time(), str_replace('~', '-', $subscriber['RBACCOUNTPLAN']), $subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC'], 'N',
								$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
						}
						log_message('info', 'result: '.json_encode($npmResult));
					} else {
						$npmResult['result'] = true;
					}
					if (!$npmResult['result']) {
						/************************************************************
						 * revert changes again, use $subscriberOld, revert ipv6/ip/net changes
						 ************************************************************/
						$this->subscribermain->update($cn, $subscriberOld,
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						if ($this->useIPv6) {
							if (!is_null($subscriberOld['RBADDITIONALSERVICE4']) && is_null($subscriber['RBADDITIONALSERVICE4'])) {  //re-mark old ipv6 as used
								$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							} else if (is_null($subscriberOld['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4'])) { //unmark ip
								$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							} else if (!is_null($subscriberOld['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4'])) { //swap back ip addresses
								$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
								$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
						}
						if (!is_null($subscriberOld['RBIPADDRESS']) && is_null($subscriber['RBIPADDRESS'])) {  //re-mark old ip as used
							$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						} else if (is_null($subscriberOld['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS'])) { //unmark ip
							$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						} else if (!is_null($subscriberOld['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS'])) { //swap back ip addresses
							$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if (!is_null($subscriberOld['RBMULTISTATIC']) && is_null($subscriber['RBMULTISTATIC'])) { //re-mark old net as used
							$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						} else if (is_null($subscriberOld['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC'])) { //unmark net
							$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						} else if (!is_null($subscriberOld['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC'])) { //swap back netaddresses
							$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						/************************************************************
						 * revert changes to ip/net status (if any)
						 ************************************************************/
						if ($subscriberOld['CUSTOMERSTATUS'] != $subscriber['CUSTOMERSTATUS']) {
							if ($this->useIPv6 && !is_null($subscriber['RBADDITIONALSERVICE4'])) {
								$this->ipaddress->updateV6Status($subscriber['RBADDITIONALSERVICE4'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
							if (!is_null($subscriber['RBIPADDRESS'])) {
								$this->ipaddress->updateStatus($subscriber['RBIPADDRESS'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
							if(!is_null($subscriber['RBMULTISTATIC'])) {
								$this->netaddress->updateStatus($subscriber['RBMULTISTATIC'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
						}
						$data['error'] = 'Failed to update. NPM: '.$npmResult['error'];
						$data['useIPv6'] = $this->useIPv6;
						$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						$this->load->view('modify_user_account', $data);
					} else {
						/**************************************************
						 * if account is to be deactivated, find sessions and delete them
						 **************************************************/
						if ($subscriber['CUSTOMERSTATUS'] == 'InActive') {
							$parts = explode('@', $subscriber['USERNAME']);
							$this->load->model('onlinesession');
							$sessions = $this->onlinesession->getSessions2($parts[0], $parts[1], $this->USESESSIONTABLE2,
								$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
								$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
							if ($sessions['status'] && isset($sessions['data'])) {
								for ($ii = 0; $ii < count($sessions['data']); $ii++) {
									$sess = $sessions['data'][$ii];
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
						/************************************************************
						 * increment bng count
						 ************************************************************/
						if (is_null($subscriber['RBIPADDRESS']) && !is_null($subscriber['RBADDITIONALSERVICE3'])) {
							$this->load->model('bngcounter');
							$this->bngcounter->incrementIPCount($subscriber['RBADDITIONALSERVICE3'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						/**************************************************
						 * update contents of RADIUSPOLICY at TBLCUSTOMER
						 **************************************************/
						// $this->subscribermain->clearRadiuspolicy($subscriber['USERNAME'], $this->SESSIONUSERNAME2, $this->SESSIONPASSWORD2, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						$this->subscribermain->updateRadiuspolicy($subscriber['USERNAME'], str_replace('~', '-', $subscriber['RADIUSPOLICY']),
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						/************************************************************
						 * RM
						 ************************************************************/
						// $this->load->library('rm');
						// $rmClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
						// $rmEdited = true;
						/**************************************************
						 * if branch for handling old rm api
						 * - as of nov 18, 2016 this is no longer used
						 * - remove this later (including closing brace before $rmEdited if block)
						 **************************************************/
						/*
						if ($this->RMAPIHOST == '10.244.4.130' || $this->RMAPIHOST == '10.244.4.131') {
							try {
								$rmSubsUsername = array('key' => 'USERNAME' , 'value' => $subscriber['USERNAME']);
								$rmSubsIdentity = array('key' => 'SUBSCRIBERIDENTITY', 'value' => $subscriber['USERNAME']);
								$rmSubsCui = array('key' => 'CUI', 'value' => $subscriber['USERNAME']);
								$rmSubsStatus = array('key' => 'SUBSCRIBERSTATUS', 'value' => 'Active');
								$rmSubsArea = array('key' => 'AREA', 'value' => $subscriber['CUSTOMERSTATUS']);
								$rmSubsPackage = array('key' => 'SUBSCRIBERPACKAGE', 'value' => str_replace('-', '~', $subscriber['RBACCOUNTPLAN']));
								$customerTypeForRM = '';
								if ($subscriber['CUSTOMERTYPE'] == 'Residential') {
									$customerTypeForRM = 'RESS';
								} else if ($subscriber['CUSTOMERTYPE'] == 'Business') {
									$customerTypeForRM = 'BUSS';
								} else {
									$customerTypeForRM = 'RESS';
								}
								$rmSubsCustomertype = array('key' => 'CUSTOMERTYPE', 'value' => $customerTypeForRM);
								$fapForRM = '';
								if ($subscriber['RBENABLED'] == 'Yes') {
									$fapForRM = 'Y';
								} else if ($subscriber['RBENABLED'] == 'No') {
									$fapForRM = 'N';
								} else {
									$fapForRM = 'N';
								}
								$rmSubsFap = array('key' => 'FAP', 'value' => $fapForRM);
								/**************************************************
								 * try updating
								 **************************************************
								$rmSubsPassword = array('key' => 'PASSWORD', 'value' => '');
								$rmParam = array($rmSubsPassword, $rmSubsStatus, $rmSubsArea, $rmSubsPackage, $rmSubsCustomertype, $rmSubsFap);
								$updateResult = $this->rm->updateAccount($subscriber['USERNAME'], $rmParam, $rmClient);
								if (intval($updateResult['responseCode']) == 200) {
									log_message('debug', 'modify|updated @ RM: '.$subscriber['USERNAME']);
								} else if (intval($updateResult['responseCode']) == 404) {
									log_message('debug', 'modify|'.$subscriber['USERNAME'].' not found, insert');
									$rmParam = array($rmSubsUsername, $rmSubsIdentity, $rmSubsCui, $rmSubsStatus, $rmSubsArea, $rmSubsPackage, $rmSubsCustomertype, $rmSubsFap);
									$insertResult = $this->rm->createAccount($rmParam, $rmClient);
									if (intval($insertResult['responseCode']) == 200) {
										log_message('debug', 'modify|inserted @ RM: '.$subscriber['USERNAME']);
									} else {
										log_message('debug', 'modify|failed to update @ RM: '.$subscriber['USERNAME'].'|'.$insertResult['responseMessage']);
										$rmEdited = false;
									}
								} else {
									log_message('debug', 'modify|failed to update @ RM: '.$subscriber['USERNAME'].'|'.$updateResult['responseMessage']);
									$rmEdited = false;
								}
							} catch (Exception $e) {
								log_message('debug', 'error @ RM:'.json_encode($e));
								$rmEdited = false;
							}
						} else if ($this->RMAPIHOST == '10.81.54.34' || $this->RMAPIHOST == '10.81.54.35') {
						*/
						// try {
						// 	$rmUsername = $subscriber['USERNAME'];
						// 	$rmPlan = $subscriber['RADIUSPOLICY'];
						// 	// $rmStatus = strtoupper($subscriber['CUSTOMERSTATUS']);
						// 	$rmStatus = 'ACTIVE';
						// 	if (strtolower($subscriber['CUSTOMERTYPE']) == 'residential') {
						// 		$rmCustomerType = 'RESS';
						// 	} else if (strtolower($subscriber['CUSTOMERTYPE']) == 'business') {
						// 		$rmCustomerType = 'BUSS';
						// 	} else {
						// 		$rmCustomerType = 'RESS';
						// 	}
						// 	$fapForRM = '';
						// 	if ($subscriber['RBENABLED'] == 'Yes') {
						// 		$fapForRM = 'Y';
						// 	} else if ($subscriber['RBENABLED'] == 'No') {
						// 		$fapForRM = 'N';
						// 	} else {
						// 		$fapForRM = 'N';
						// 	}
						// 	/**************************************************
						// 	 * try updating
						// 	 **************************************************/
						// 	$nodes = array('PARAM3' => $fapForRM, 'AREA' => $subscriber['CUSTOMERSTATUS']);
						// 	$updateResult = $this->rm->wsUpdateSubscriberProfile($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
						// 	log_message('debug', 'RM result code: '.$subscriber['USERNAME'].'|'.$updateResult ['responseCode'] );
						// 	if (intval($updateResult['responseCode']) == 200) {
						// 		log_message('debug', 'modify|updated @ RM: '.$subscriber['USERNAME']);
						// 	} else if (intval($updateResult['responseCode']) == 404) {
						// 		log_message('debug', 'modify|'.$subscriber['USERNAME'].' not found, insert');
						// 		$insertResult = $this->rm->wsAddSubscriber($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
						// 		if (intval($insertResult['responseCode']) == 200) {
						// 			log_message('debug', 'modify|inserted @ RM: '.$subscriber['USERNAME']);
						// 		} else if (intval($insertResult['responseCode']) == 400) {
						// 			// [AJB] [2018-10-08]

						// 			for ($mm = 0; $mm < 4; $mm++) {
						// 				if ($mm == 0) { 
						// 					$thisUsername = $rmUsername;
						// 					$deleteResult = $this->rm->wsDeleteSubscriberProfile($rmClient, $thisUsername);
						// 					log_message('debug', 'modify|delete (alternateId) result for '.$thisUsername.': '.$deleteResult['responseCode'].'|'.$deleteResult['responseMessage']);
						// 					$purgeResult = $this->rm->wsPurgeSubscriber($rmClient, $thisUsername);
						// 					log_message('debug', 'modify|purge (alternateId) result for '.$thisUsername.': '.$purgeResult['responseCode'].'|'.$purgeResult['responseMessage']);
						// 					$deleteResult = $this->rm->wsDeleteSubscriberProfileV2($rmClient, $thisUsername);
						// 					log_message('debug', 'modify|delete (subscriberID) result for '.$thisUsername.': '.$deleteResult['responseCode'].'|'.$deleteResult['responseMessage']);
						// 					$purgeResult = $this->rm->wsPurgeSubscriberV2($rmClient, $thisUsername);
						// 					log_message('debug', 'modify|purge (subscriberID) result for '.$thisUsername.': '.$purgeResult['responseCode'].'|'.$purgeResult['responseMessage']);
						// 				} else {
						// 					$thisUsername = $rmUsername.'#L'.$mm;
						// 					$deleteResult = $this->rm->wsDeleteSubscriberProfile($rmClient, $thisUsername);
						// 					log_message('debug', 'modify|delete (alternateId) result for '.$thisUsername.': '.$deleteResult['responseCode'].'|'.$deleteResult['responseMessage']);
						// 					$purgeResult = $this->rm->wsPurgeSubscriber($rmClient, $thisUsername);
						// 					log_message('debug', 'modify|purge (alternateId) result for '.$thisUsername.': '.$purgeResult['responseCode'].'|'.$purgeResult['responseMessage']);
						// 					$deleteResult = $this->rm->wsDeleteSubscriberProfileV2($rmClient, $thisUsername);
						// 					log_message('debug', 'modify|delete (subscriberID) result for '.$thisUsername.': '.$deleteResult['responseCode'].'|'.$deleteResult['responseMessage']);
						// 					$purgeResult = $this->rm->wsPurgeSubscriberV2($rmClient, $thisUsername);
						// 					log_message('debug', 'modify|purge (subscriberID) result for '.$thisUsername.': '.$purgeResult['responseCode'].'|'.$purgeResult['responseMessage']);
						// 				}
						// 			}

						// 			$insertResult = $this->rm->wsAddSubscriber($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
						// 			log_message('debug', 'modify|insertResult: '.json_encode($insertResult));
						// 			if (intval($insertResult['responseCode']) == 200) {
						// 				log_message('debug', 'modify|insert attempt #2 (from error code 400) successful');
						// 			} else {
						// 				log_message('debug', 'modify|insert attempt #2 (from error code 400) failed: '.$insertResult['responseMessage']);
						// 				$rmEdited = false;
						// 			}
						// 			// [AJB] [2018-10-08] 
						// 		} else {
						// 			log_message('debug', 'modify|failed to insert @ RM: '.$subscriber['USERNAME'].'|'.$insertResult['responseCode'].'|'.
						// 				$insertResult['responseMessage']);
						// 			$rmEdited = false;
						// 		}


						// 	} else {
						// 		log_message('debug', 'modify|failed to update @ RM: '.$subscriber['USERNAME'].'|'.$updateResult['responseCode'].'|'.
						// 			$updateResult['responseMessage']);
						// 		$rmEdited = false;
						// 	}
						// } catch (Exception $e) {
						// 	log_message('debug', 'error @ RM:'.json_encode($e));
						// 	$rmEdited = false;
						// }
						/*
						}
						*/
						// if (!$rmEdited) {
						// 	/************************************************************
						// 	 * revert changes again, use $subscriberOld, revert ipv6/ip/net changes
						// 	 ************************************************************/
						// 	$this->subscribermain->update($cn, $subscriberOld,
						// 		$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 	if ($this->useIPv6) {
						// 		if (!is_null($subscriberOld['RBADDITIONALSERVICE4']) && is_null($subscriber['RBADDITIONALSERVICE4'])) {  //re-mark old ipv6 as used
						// 			$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		} else if (is_null($subscriberOld['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4'])) { //unmark ipv6
						// 			$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
						// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		} else if (!is_null($subscriberOld['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4'])) { //swap back ipv6 addresses
						// 			$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
						// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 			$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		}
						// 	}
						// 	if (!is_null($subscriberOld['RBIPADDRESS']) && is_null($subscriber['RBIPADDRESS'])) {  //re-mark old ip as used
						// 		$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 	} else if (is_null($subscriberOld['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS'])) { //unmark ip
						// 		$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 	} else if (!is_null($subscriberOld['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS'])) { //swap back ip addresses
						// 		$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 	}
						// 	if (!is_null($subscriberOld['RBMULTISTATIC']) && is_null($subscriber['RBMULTISTATIC'])) { //re-mark old net as used
						// 		$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 	} else if (is_null($subscriberOld['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC'])) { //unmark net
						// 		$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 	} else if (!is_null($subscriberOld['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC'])) { //swap back netaddresses
						// 		$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 	}
						// 	/************************************************************
						// 	 * revert changes to ip/net status (if any)
						// 	 ************************************************************/
						// 	if ($subscriberOld['CUSTOMERSTATUS'] != $subscriber['CUSTOMERSTATUS']) {
						// 		if ($this->useIPv6 && !is_null($subscriber['RBADDITIONALSERVICE4'])) {
						// 			$this->ipaddress->updateV6Status($subscriber['RBADDITIONALSERVICE4'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		}
						// 		if (!is_null($subscriber['RBIPADDRESS'])) {
						// 			$this->ipaddress->updateStatus($subscriber['RBIPADDRESS'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		}
						// 		if(!is_null($subscriber['RBMULTISTATIC'])) {
						// 			$this->netaddress->updateStatus($subscriber['RBMULTISTATIC'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		}
						// 	}
						// 	$data['error'] = 'Failed to update. RM: '.$updateResult['responseMessage'];
						// 	$data['useIPv6'] = $this->useIPv6;
						// 	$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						// 	$this->load->view('modify_user_account', $data);
						// 	return;
						// }
						/************************************************************
						 * /RM
						 ************************************************************/

						/************************************************************
						 * at this point, should the $informationHistory and $sessionHistory be updated?
						 * these variables currently have data prior to the update
						 ************************************************************/
						if ($sysuser != $this->SUPERUSER) {
							$this->load->model('subscriberaudittrail');
							$this->subscriberaudittrail->logSubscriberModification($subscriber, $subscriberOld, $sysuser, $sysuserIP, $modifieddate, true);
						}
						$dataOk = array(
							'subscriber' => $subscriber,
							'message' => 'Account modification successful.',
							'sessionHistory' => $sessionHistory);
						$dataOk['useIPv6'] = $this->useIPv6;
						$this->load->view('modify_user_account_result', $dataOk);
					}
				}
			}
		}
	}

	/**************************************************************
		* process update subscriber (admin)

	***************************************************************/

	public function processUpdateSubscriberAdmin() {
		$this->redirectIfNoAccess('Modify Account (Admin)', 'subscribers/processUpdateSubscriberAdmin');
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@processUpdateSubscriberAdmin|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@processUpdateSubscriber|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		$this->load->model('services');
		$this->load->model('subscribermain');
		$this->load->model('ipaddress');
		$this->load->model('netaddress');
		$this->load->model('util');
		//get services
		// $allServices = $this->services->fetchAllNamesOnly2();
		$allServices = $this->services->fetchAllNamesOnlyNew($this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
		//get realms
		$this->load->model('realm');
		$this->load->model('onlinesession');
		$realms = $this->realm->fetchAllNamesOnly();
		$realm = $this->input->post('realm');
		$username = trim($this->input->post('username'));
		$cn = $username.'@'.$realm;
		$password = trim($this->input->post('password1'));
		// Null value on acctplan, nonedsl, svccode 5/17/19
		$acctplan = ""; //$this->input->post('acctplan'); //'Residential' or 'Business'
		$status = $this->input->post('status');
		$custname = trim($this->input->post('custname'));
		$ordernum = trim($this->input->post('ordernum'));
		$servicenum = trim($this->input->post('servicenum'));
		$nonedsl = ""; //$this->input->post('nonedsl'); //'Yes' or 'No'
		$svccode = ""; //$this->input->post('svccode');
		$ipv6address = trim($this->input->post('ipv6address'));
		$ipaddress = trim($this->input->post('ipaddress'));
		$netaddress = trim($this->input->post('netaddress'));
		// $dynamicip = trim($this->input->post('dynamicip')); //uncomment this to enable dynamic pool (check also at views/modify_user_account.php)
		$dynamicip = '';
		$nasIdentifier = trim($this->input->post('nasIdentifier'));
		$svc_add1 = $this->input->post('svc_add1');
		$svc_add2 = $this->input->post('svc_add2');
		$newRemarks = trim($this->input->post('remarks'));
		$currentRemarks = trim($this->input->post('current_remarks'));
		$informationHistory = trim($this->input->post('informationHistory'));
		$sessionHistory = trim($this->input->post('sessionHistory'));

		/**************************************************
		 * get this from session variable
		 **************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		if ($status == 'T') {
			$ipaddress = '';
			$netaddress = '';
		}
		$modifieddate = date('Y-m-d H:i:s', time());

		$rbremarks = $currentRemarks.((!isset($newRemarks) || is_null($newRemarks) || $newRemarks == '') ? '' : ';'.date('Y-m-d H:i:s', time()).' '.$newRemarks);
		if (strlen($rbremarks) > 3500) {
			$rbremarks = substr($rbremarks, strlen($rbremarks) - 3500);
		}
		/*
		if (!isset($newRemarks) || is_null($newRemarks) || $newRemarks == '') {
			$rbremarks = $currentRemarks;
		} else {
			$rbremarks = $currentRemarks.';'.date('Y-m-d H:i:s', time()).' '.$newRemarks;
			if (strlen($rbremarks) > 3500) {
				$rbremarks = substr($rbremarks, 0, 3500);
			}
		}
		*/
		$subscriberOld = $this->subscribermain->findByUserIdentity($cn);
		/**************************************************
		 * prepare new subscriber data
		 **************************************************/
		$subscriber = array(
			'USER_IDENTITY' => $cn,
			'USERNAME' => $cn,
			'BANDWIDTH' => $subscriberOld['BANDWIDTH'],
			'CUSTOMERSTATUS' => $subscriberOld['CUSTOMERSTATUS'] == $status ? $subscriberOld['CUSTOMERSTATUS'] : $status,
			'PASSWORD' => $subscriberOld['PASSWORD'] == $password ? $subscriberOld['PASSWORD'] : $password,
			'CUSTOMERREPLYITEM' => $subscriberOld['CUSTOMERREPLYITEM'],
			'CREATEDATE' => $subscriberOld['CREATEDATE'],
			'LASTMODIFIEDDATE' => $modifieddate,
			'RBCUSTOMERNAME' => $subscriberOld['RBCUSTOMERNAME'] == $custname ? $subscriberOld['RBCUSTOMERNAME'] : $custname,
			'RBCREATEDBY' => $subscriberOld['RBCREATEDBY'],
			'RBADDITIONALSERVICE5' => $subscriberOld['RBADDITIONALSERVICE5'],
			'RBADDITIONALSERVICE4' => $ipv6address == '' ? null : ($subscriberOld['RBADDITIONALSERVICE4'] == $ipv6address ? $subscriberOld['RBADDITIONALSERVICE4'] : $ipv6address),
			'RBADDITIONALSERVICE3' => $dynamicip == '' ? null : ($subscriberOld['RBADDITIONALSERVICE3'] == $nasIdentifier ? $subscriberOld['RBADDITIONALSERVICE3'] : $nasIdentifier),
			'RBADDITIONALSERVICE2' => $svc_add2 == '' ? null : ($subscriberOld['RBADDITIONALSERVICE2'] == $svc_add2 ? $subscriberOld['RBADDITIONALSERVICE2'] : $svc_add2),
			'RBADDITIONALSERVICE1' => $svc_add1 == '' ? null : ($subscriberOld['RBADDITIONALSERVICE1'] == $svc_add1 ? $subscriberOld['RBADDITIONALSERVICE1'] : $svc_add1),
			'RBCHANGESTATUSDATE' => $subscriberOld['CUSTOMERSTATUS'] == $status ? $subscriberOld['RBCHANGESTATUSDATE'] : $modifieddate,
			'RBCHANGESTATUSBY' => $subscriberOld['CUSTOMERSTATUS'] == $status ? $subscriberOld['RBCHANGESTATUSBY'] : $sysuser,
			'RBACTIVATEDDATE' => $status == 'Active' && $subscriberOld['CUSTOMERSTATUS'] != 'Active' ? $modifieddate : $subscriberOld['RBACTIVATEDDATE'],
			'RBACTIVATEDBY' => $status == 'Active' && $subscriberOld['CUSTOMERSTATUS'] != 'Active' ? $sysuser : $subscriberOld['RBACTIVATEDBY'],
			'RBACCOUNTSTATUS' => $subscriberOld['RBACCOUNTSTATUS'],
			'RBSVCCODE2' => $subscriberOld['RBSVCCODE2'],
			'RBACCOUNTPLAN' => str_replace('~', '-', $subscriberOld['RBACCOUNTPLAN']) == $svccode ? str_replace('~', '-', $subscriberOld['RBACCOUNTPLAN']) : $svccode,
			'RADIUSPOLICY' => str_replace('~', '-', $subscriberOld['RADIUSPOLICY']) == $svccode ? str_replace('~', '-', $subscriberOld['RADIUSPOLICY']) : $svccode,
			'CUSTOMERTYPE' => $subscriberOld['CUSTOMERTYPE'] == $acctplan ? $subscriberOld['CUSTOMERTYPE'] : $acctplan,
			'RBSERVICENUMBER' => $servicenum,
			'RBCHANGESTATUSFROM' => $subscriberOld['CUSTOMERSTATUS'],
			'RBSECONDARYACCOUNT' => $subscriberOld['RBSECONDARYACCOUNT'],
			'RBUNLIMITEDACCESS' => $subscriberOld['RBUNLIMITEDACCESS'],
			'RBTIMESLOT' => $subscriberOld['RBTIMESLOT'],
			'RBORDERNUMBER' => $ordernum == '' ? null : ($subscriberOld['RBORDERNUMBER'] == $ordernum ? $subscriberOld['RBORDERNUMBER'] : $ordernum),
			'RBREMARKS' => $rbremarks,
			'RBREALM' => $subscriberOld['RBREALM'], //should not change
			'RBNUMBEROFSESSION' => $subscriberOld['RBNUMBEROFSESSION'],
			'RBMULTISTATIC' => $netaddress == '' ? null : ($subscriberOld['RBMULTISTATIC'] == $netaddress ? $subscriberOld['RBMULTISTATIC'] : $netaddress),
			'RBIPADDRESS' => $ipaddress == '' ? null : ($subscriberOld['RBIPADDRESS'] == $ipaddress ? $subscriberOld['RBIPADDRESS'] : $ipaddress),
			'RBENABLED' => $nonedsl);
		/**************************************************
		 * get CUSTOMERREPLYITEM value
		 **************************************************/
		$subscriber['CUSTOMERREPLYITEM'] = $this->subscribermain->generateCustomerReplyItemValue(
			$subscriber['RBADDITIONALSERVICE4'], $subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC']);
		log_message('debug', '               ');
		log_message('debug', 'CUSTOMERREPLYITEM:'.$subscriber['CUSTOMERREPLYITEM']);
		log_message('debug', '               ');
		if (is_null($subscriber['RBIPADDRESS']) && !is_null($subscriber['RBADDITIONALSERVICE3'])) {
			$subscriber['CUSTOMERREPLYITEM'] = $dynamicip;
		}
		/**************************************************
		 * update RBENABLED value
		 **************************************************/
		if (strtolower($subscriber['RBENABLED']) == 'yes' && strtolower($subscriber['CUSTOMERTYPE']) == 'residential') {
			$subscriber['RBENABLED'] = 'Yes';
		} else {
			$subscriber['RBENABLED'] = 'No';
		}
		log_message('info', 'current:'.json_encode($subscriberOld));
		log_message('info', 'new:'.json_encode($subscriber));
		/**************************************************
		 * data to pass to view on update fail
		 **************************************************/
		$data = array(
			'proceed' => $checks['go'],
			'error' => $checks['msg'],
			'show' => true,
			'found' => true,
			'username' => $username,
			'realm' => $realm,
			'realms' => $realms,
			'subscriber' => $subscriber,
			'allServicesFilter' => $allServices,
			'svccode' => $subscriber['RBACCOUNTPLAN'],
			'svc_add1' => $subscriber['RBADDITIONALSERVICE1'],
			'svc_add2' => $subscriber['RBADDITIONALSERVICE2'],
			'current_remarks' => $currentRemarks,
			'new_remarks' => $newRemarks,
			'informationHistory' => $informationHistory,
			'sessionHistory' => $sessionHistory);
		if ($checks['go'] == false) {
			log_message('debug', '@processUpdateSubscriberAdmin: '.json_encode($checks));
			// $data['proceed'] = $checks['go'];
			// $data['error'] = $checks['msg'];
			$data['useIPv6'] = $this->useIPv6;
			$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
			$this->load->view('modify_user_account_admin', $data);
			return;
		}
		/**************************************************
		 * goes here if $status (now in $subscriber['CUSTOMERSTATUS']) is 'T', delete account
		 **************************************************/
		if ($subscriber['CUSTOMERSTATUS'] == 'T') {
			/**************************************************
			 * free attached ip/ipv6/net address
			 **************************************************/
			if ($this->useIPv6 && !is_null($subscriberOld['RBADDITIONALSERVICE4'])) {
				$this->ipaddress->freeUpV6($subscriberOld['RBADDITIONALSERVICE4'],
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			}
			if (!is_null($subscriberOld['RBIPADDRESS'])) {
				$this->ipaddress->freeUp($subscriberOld['RBIPADDRESS'],
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			}
			if (!is_null($subscriberOld['RBMULTISTATIC'])) {
				$this->netaddress->freeUp($subscriberOld['RBMULTISTATIC'],
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			}
			$this->subscribermain->delete($subscriber['USER_IDENTITY'],
				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
			/**************************************************
			 * if there were still changes made along with the 'T' status, those will be shown in the csv file
			 * if the old data is to be written in the csv, use $subscriberOld
			 **************************************************/
			$subscribers = array();
			$subscribers[] = $subscriber;
			$this->util->writeToDeletedSubscriberFile($subscribers, time());
			/**************************************************
			 * delete account in npm (if npm is enabled)
			 **************************************************/
			if ($this->ENABLENPM) {
				$npmDeleted = $this->subscribermain->npmRemoveSubscriber($subscriber['USERNAME'],
					$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
			}
			/**************************************************
			 * log account deletion
			 **************************************************/
			if ($sysuser != $this->SUPERUSER) {
				$this->load->model('subscriberaudittrail');
				$this->subscriberaudittrail->logSubscriberDeletion($subscriber, $sysuser, $sysuserIP, date('Y-m-d H:i:s', time()));
			}
			/**************************************************
			 * find sessions, delete if found
			 **************************************************/
			$parts = explode('@', $subscriber['USERNAME']);
			$this->load->model('onlinesession');
			$sessions = $this->onlinesession->getSessions2($parts[0], $parts[1], $this->USESESSIONTABLE2,
				$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
			$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
			log_message('info', 'sessions@terminate:'.json_encode($sessions));
			if ($sessions['status'] && isset($sessions['data'])) {
				for ($ii = 0; $ii < count($sessions['data']); $ii++) {
					$sess = $sessions['data'][$ii];
					$deleted = $this->onlinesession->requestDisconnect($sess['USER_NAME'], $sess['NAS_IP_ADDRESS'], $sess['ACCT_SESSION_ID'], $this->USESESSIONTABLE2,
						$this->DELETESESSIONAPIHOST, $this->DELETESESSIONAPIPORT, $this->DELETESESSIONAPISTUB,
						$this->DELETESESSIONAPIHOST2, $this->DELETESESSIONAPIPORT2, $this->DELETESESSIONAPISTUB2,
						$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
						$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2,
						$this->TBLMCOREHOST, $this->TBLMCOREPORT, $this->TBLMCORESCHEMA, $this->TBLMCOREUSERNAME, $this->TBLMCOREPASSWORD,
						$this->TBLMCOREHOST2, $this->TBLMCOREPORT2, $this->TBLMCORESCHEMA2, $this->TBLMCOREUSERNAME2, $this->TBLMCOREPASSWORD2);
				}
			}
			/**************************************************
			 * RM
			 **************************************************/
			// Remove Dependencies 5/21/19
			// $this->load->library('rm');
			// $rmClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
			/**************************************************
			 * if branch for handling old rm api
			 * - as of nov 18, 2016 this is no longer used
			 * - remove this later (including closing brace before /RM marker)
			 **************************************************/
			/*
			if ($this->RMAPIHOST == '10.244.4.130' || $this->RMAPIHOST == '10.244.4.131') {
				try {
					$rmResult['exists'] = true; //try delete
					if (!$rmResult['exists']) {
						log_message('debug', 'not found @ RM: '.$subscriber['USERNAME']);
					} else {
						$rmResult = $this->rm->deleteAccount($subscriber['USERNAME'], $rmClient);
						if (intval($rmResult['responseCode']) == 200 || intval($rmResult['responseCode']) == 201) {
							log_message('debug', 'deleted @ RM: '.$subscriber['USERNAME']);
						} else {
							log_message('debug', 'failed to delete @ RM: '.$subscriber['USERNAME'].' | '.$rmResult['responseMessage']);
						}
					}
				} catch (Exception $e) {
						log_message('debug', 'error @ RM:'.json_encode($e));
				}
			} else if ($this->RMAPIHOST == '10.81.54.34' || $this->RMAPIHOST == '10.81.54.35') {
			*/
			// try {
			// 	$deleteResult = $this->rm->wsDeleteSubscriberProfile($rmClient, $subscriber['USERNAME']);
			// 	if (intval($deleteResult['responseCode']) == 200 || intval($deleteResult['responseCode']) == 404) {
			// 		log_message('debug', 'deleted @ RM: '.$subscriber['USERNAME']);
			// 		if (intval($deleteResult['responseCode']) == 200) {
			// 			$purgeResult = $this->rm->wsPurgeSubscriber($rmClient, $subscriber['USERNAME']);
			// 			if (intval($purgeResult['responseCode']) == 200) {
			// 				log_message('debug', 'purged @ RM: '.$subscriber['USERNAME']);
			// 			} else {
			// 				log_message('debug', 'failed to purge @ RM: '.$subscriber['USERNAME'].' | '.$purgeResult['responseMessage']);
			// 			}
			// 		}
			// 	} else {
			// 		log_message('debug', 'failed to delete @ RM: '.$subscriber['USERNAME'].' | '.$deleteResult['responseMessage']);
			// 	}
			// } catch (Exception $e) {
			// 	log_message('debug', 'error @ RM:'.json_encode($e));
			// }
			/*
			}
			*/
			/**************************************************
			 * /RM
			 **************************************************/
			$dataOk = array(
				'subscriber' => $subscriber,
				'message' => 'Account deletion successful.',
				'informationHistory' => $informationHistory,
				'sessionHistory' => $sessionHistory);
			$dataOk['useIPv6'] = $this->useIPv6;
			$this->load->view('modify_user_account_result_admin', $dataOk);
		} else {
			/**************************************************
			 * checking new subscriber data validity
			 **************************************************/
			$usernameHasUppercase = preg_match('/[A-Z]{1}/', $username);
			if ($usernameHasUppercase) {
				$data['error'] = 'Account modification failed. Username must not have uppercase characters.';
				$data['useIPv6'] = $this->useIPv6;
				$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
				$this->load->view('modify_user_account_admin', $data);
				return;
			}
			/**************************************************
			 * check if username has special characters
			 **************************************************/
			$hasSpecialChars = preg_match('/[^a-zA-Z0-9._-]/', $username);
			if ($hasSpecialChars) {
				$data['error'] = 'Account modification failed. Username must only use alphanumeric characters.';
				$data['useIPv6'] = $this->useIPv6;
				$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
				$this->load->view('modify_user_account_admin', $data);
				return;
			}
			/**************************************************
			 * check if new plan matches with gpon type or not: plans with 'gpon' string must have gpon type ip address
			 **************************************************/
			if (!is_null($subscriber['RBIPADDRESS'])) {
				$srv = strtolower($subscriber['RBACCOUNTPLAN']);
				$ipMustBeGPON = true;
				if (strpos($srv, 'gpon') === false) {
					$ipMustBeGPON = false;
				}
				$isGPON = $this->ipaddress->isGPON($subscriber['RBIPADDRESS'],
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				if ($ipMustBeGPON && !$isGPON) {
					$data['error'] = 'Static IP not allowed for GPON type profile';
					$data['useIPv6'] = $this->useIPv6;
					$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
					$this->load->view('modify_user_account_admin', $data);
					return;
				} else if (!$ipMustBeGPON && $isGPON) {
					$data['error'] = 'Static IP not allowed for non-GPON type profile';
					$data['useIPv6'] = $this->useIPv6;
					$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
					$this->load->view('modify_user_account_admin', $data);
					return;
				}
			}
			// $valid = $this->subscribermain->isValid($subscriber);
			$valid = $this->subscribermain->isValidNew($subscriber,
				$this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
			log_message('info', 'valid:'.json_encode($valid));
			/**************************************************
			 * new subscriber data has invalid fields
			 **************************************************/
			if (!$valid['status']) {
				$data['error'] = 'Account modification failed. Some fields are invalid.';
				$data['useIPv6'] = $this->useIPv6;
				$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
				$this->load->view('modify_user_account_admin', $data);
			/**************************************************
			* ok to update
			**************************************************/
			} else {
				/**************************************************
				 * check if new servicenumber is unique
				 **************************************************/
				if ($subscriberOld['RBSERVICENUMBER'] != $subscriber['RBSERVICENUMBER']) {
					$serviceNumberExists = $this->subscribermain->serviceNumberExists($subscriber['RBSERVICENUMBER'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if ($serviceNumberExists) {
						$data['error'] = 'Account modification failed. Service number '.$subscriber['RBSERVICENUMBER'].' already exists in the database.';
						$data['useIPv6'] = $this->useIPv6;
						$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						$this->load->view('modify_user_account_admin', $data);
						return;
					}
				}
				/**************************************************
				 * do changes to ip/ipv6/net addresses
				 **************************************************/
				if ($this->useIPv6) {
					if (!is_null($subscriberOld['RBADDITIONALSERVICE4']) && is_null($subscriber['RBADDITIONALSERVICE4'])) { //removing current ipv6 address
						$this->ipaddress->freeUpV6($subscriberOld['RBADDITIONALSERVICE4'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					} else if (is_null($subscriberOld['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4'])) { //no ipv6 -> with ipv6
						$ipv6Free = $this->ipaddress->isV6Free($subscriber['RBADDITIONALSERVICE4'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						if (!$ipv6Free) {
							$data['error'] = 'Account modification failed. IPv6 Address is already used.';
							$data['useIPv6'] = $this->useIPv6;
							$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
							$this->load->view('modify_user_account_admin', $data);
						} else {
							$this->ipaddress->markV6AsUsed($subscriber['RBADDITIONALSERVICE4'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
					} else if (!is_null($subscriberOld['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4'])
						&& $subscriberOld['RBADDITIONALSERVICE4'] != $subscriber['RBADDITIONALSERVICE4']) { //changing ipv6 address
						$ipv6Free = $this->ipaddress->isV6Free($subscriber['RBADDITIONALSERVICE4'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						if (!$ipv6Free) {
							$data['error'] = 'Account modification failed. IPv6 address is already used.';
							$data['useIPv6'] = $this->useIPv6;
							$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
							$this->load->view('modify_user_account_admin', $data);
						} else {
							$this->ipaddress->freeUpV6($subscriberOld['RBADDITIONALSERVICE4'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->ipaddress->markV6AsUsed($subscriber['RBADDITIONALSERVICE4'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
					}
				}
				if (!is_null($subscriberOld['RBIPADDRESS']) && is_null($subscriber['RBIPADDRESS'])) {  //removing current ip address
					$this->ipaddress->freeUp($subscriberOld['RBIPADDRESS'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				} else if (is_null($subscriberOld['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS'])) { //no ip -> with ip
					$ipFree = $this->ipaddress->isFree($subscriber['RBIPADDRESS'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if (!$ipFree) {
						$data['error'] = 'Account modification failed. IP Address is already used.';
						$data['useIPv6'] = $this->useIPv6;
						$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						$this->load->view('modify_user_account_admin', $data);
					} else {
						$this->ipaddress->markAsUsed($subscriber['RBIPADDRESS'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
				} else if (!is_null($subscriberOld['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS']) && $subscriberOld['RBIPADDRESS'] != $subscriber['RBIPADDRESS']) { //changing ip address
					$ipFree = $this->ipaddress->isFree($subscriber['RBIPADDRESS'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if (!$ipFree) {
						$data['error'] = 'Account modification failed. IP Address is already used.';
						$data['useIPv6'] = $this->useIPv6;
						$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						$this->load->view('modify_user_account_admin', $data);
					} else {
						$this->ipaddress->freeUp($subscriberOld['RBIPADDRESS'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						$this->ipaddress->markAsUsed($subscriber['RBIPADDRESS'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
				}
				if (!is_null($subscriberOld['RBMULTISTATIC']) && is_null($subscriber['RBMULTISTATIC'])) { //removing current net address
					$this->netaddress->freeUp($subscriberOld['RBMULTISTATIC'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				} else if (is_null($subscriberOld['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC'])) { //no net -> with net
					$netFree = $this->netaddress->isFree($subscriber['RBMULTISTATIC'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if (!$netFree) {
						$data['error'] = 'Account creation failed. Network address is already used.';
						$data['useIPv6'] = $this->useIPv6;
						$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						$this->load->view('modify_user_account_admin', $data);
					} else {
						$this->netaddress->markAsUsed($subscriber['RBMULTISTATIC'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
				} else if (!is_null($subscriberOld['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC']) && $subscriberOld['RBMULTISTATIC'] != $subscriber['RBMULTISTATIC']) { //changing net address
					$netFree = $this->netaddress->isFree($subscriber['RBMULTISTATIC'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if (!$netFree) {
						$data['error'] = 'Account creation failed. Network address is already used.';
						$data['useIPv6'] = $this->useIPv6;
						$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						$this->load->view('modify_user_account_admin', $data);
					} else {
						$this->netaddress->freeUp($subscriberOld['RBMULTISTATIC'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						$this->netaddress->markAsUsed($subscriber['RBMULTISTATIC'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
				}
				if (!is_null($subscriber['RBMULTISTATIC'])) {
					log_message('info', '@modify: '.$subscriber['USERNAME'].' has valid RBMULTISTATIC, setting RBENABLED to \'No\'');
					$subscriber['RBENABLED'] = 'No';
				}
				/**************************************************
				 * uncomment the next line if RBENABLED is fixed to 'No' and FAP to 'N'
				 * FAP is dependent on the value of $subscriber['RBENABLED']: 'No' => 'N', 'Yes' => 'Y'
				 **************************************************/
				// $subscriber['RBENABLED'] = 'No';
				/**************************************************
				 * update subscriber
				 **************************************************/
				log_message('debug', '@processUpdateSubscriberAdmin|subscriber:'.$subscriber['USERNAME'].'|RBENABLED:'.$subscriber['RBENABLED']);
				$updated = $this->subscribermain->update($cn, $subscriber,
					$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				log_message('info', 'updated:'.json_encode($updated));
				/**************************************************
				 * update failed
				 **************************************************/
				if (!$updated) {
					/**************************************************
					 * revert any changes made to ipv6/ip/net addresses
					 **************************************************/
					if ($this->useIPv6) {
						if (!is_null($subscriberOld['RBADDITIONALSERVICE4']) && is_null($subscriber['RBADDITIONALSERVICE4'])) { //re-mark old ipv6 as used
							$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						} else if (is_null($subscriberOld['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4'])) { //unmark ipv6
							$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						} else if (!is_null($subscriberOld['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4'])) { //swap back ipv6 addresses
							$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
					}
					if (!is_null($subscriberOld['RBIPADDRESS']) && is_null($subscriber['RBIPADDRESS'])) {  //re-mark old ip as used
						$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					} else if (is_null($subscriberOld['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS'])) { //unmark ip
						$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					} else if (!is_null($subscriberOld['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS'])) { //swap back ip addresses
						$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
					if (!is_null($subscriberOld['RBMULTISTATIC']) && is_null($subscriber['RBMULTISTATIC'])) { //re-mark old net as used
						$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					} else if (is_null($subscriberOld['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC'])) { //unmark net
						$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					} else if (!is_null($subscriberOld['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC'])) { //swap back netaddresses
						$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
					$data['error'] = 'Failed to update subscriber. An unknown error occurred';
					$data['useIPv6'] = $this->useIPv6;
					$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
					$this->load->view('modify_user_account_admin', $data);
				/**************************************************
				 * account updated
				 **************************************************/
				} else {
					/**************************************************
					 * perform changes to ipv6/ip/net status (if any)
					 **************************************************/
					if ($subscriberOld['CUSTOMERSTATUS'] != $subscriber['CUSTOMERSTATUS']) {
						if ($this->useIPv6 && !is_null($subscriber['RBADDITIONALSERVICE4'])) {
							$this->ipaddress->updateV6Status($subscriber['RBADDITIONALSERVICE4'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if (!is_null($subscriber['RBIPADDRESS'])) {
							$this->ipaddress->updateStatus($subscriber['RBIPADDRESS'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if(!is_null($subscriber['RBMULTISTATIC'])) {
							$this->netaddress->updateStatus($subscriber['RBMULTISTATIC'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
					}
					/**************************************************
					 * delete sessions if plan (RBACCOUNTPLAN) is new
					 **************************************************/
					$newRP = trim($subscriber['RBACCOUNTPLAN']);
					$newRP = str_replace('~', '-', $newRP);
					$oldRP = trim($subscriberOld['RBACCOUNTPLAN']);
					$oldRP = str_replace('~', '-', $oldRP);
					log_message('debug', 'old plan:'.$oldRP.', new plan:'.$newRP);
					if ($newRP != $oldRP) {
						$sessions = $this->onlinesession->getSessions2($username, $realm, $this->USESESSIONTABLE2,
							$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
							$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
						if ($sessions['status'] && isset($sessions['data'])) {
							for ($ii = 0; $ii < count($sessions['data']); $ii++) {
								$sess = $sessions['data'][$ii];
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
					/************************************************************
					 * do npm update if enabled, if not $npmResult['result'] will default to true, essentially skipping the npm operation
					 ************************************************************/
					if ($this->ENABLENPM) {
						$inNPM = $this->subscribermain->npmFetchXML($subscriber['USERNAME'],
							$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
						log_message('info', 'in npm:'.json_encode($inNPM));
						if ($inNPM['found']) {
							$npmResult = $this->subscribermain->npmUpdateXML($subscriber['USERNAME'], $subscriber['PASSWORD'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'Y' : 'N',
								str_replace('~', '-', $subscriber['RBACCOUNTPLAN']), $subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC'], 'N',
								$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
						} else {
							$npmResult = $this->subscribermain->npmCreateXML($subscriber['USERNAME'], $subscriber['PASSWORD'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'Y' : 'N',
								time(), str_replace('~', '-', $subscriber['RBACCOUNTPLAN']), $subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC'], 'N',
								$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
						}
						log_message('info', 'result: '.json_encode($npmResult));
					} else {
						$npmResult['result'] = true;
					}
					if (!$npmResult['result']) {
						/************************************************************
						 * revert changes again, use $subscriberOld, revert ipv6/ip/net changes
						 ************************************************************/
						$this->subscribermain->update($cn, $subscriberOld,
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						if ($this->useIPv6) {
							if (!is_null($subscriberOld['RBADDITIONALSERVICE4']) && is_null($subscriber['RBADDITIONALSERVICE4'])) {  //re-mark old ipv6 as used
								$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							} else if (is_null($subscriberOld['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4'])) { //unmark ip
								$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							} else if (!is_null($subscriberOld['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4'])) { //swap back ip addresses
								$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
								$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
						}
						if (!is_null($subscriberOld['RBIPADDRESS']) && is_null($subscriber['RBIPADDRESS'])) {  //re-mark old ip as used
							$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						} else if (is_null($subscriberOld['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS'])) { //unmark ip
							$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						} else if (!is_null($subscriberOld['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS'])) { //swap back ip addresses
							$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if (!is_null($subscriberOld['RBMULTISTATIC']) && is_null($subscriber['RBMULTISTATIC'])) { //re-mark old net as used
							$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						} else if (is_null($subscriberOld['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC'])) { //unmark net
							$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						} else if (!is_null($subscriberOld['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC'])) { //swap back netaddresses
							$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						/************************************************************
						 * revert changes to ip/net status (if any)
						 ************************************************************/
						if ($subscriberOld['CUSTOMERSTATUS'] != $subscriber['CUSTOMERSTATUS']) {
							if ($this->useIPv6 && !is_null($subscriber['RBADDITIONALSERVICE4'])) {
								$this->ipaddress->updateV6Status($subscriber['RBADDITIONALSERVICE4'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
							if (!is_null($subscriber['RBIPADDRESS'])) {
								$this->ipaddress->updateStatus($subscriber['RBIPADDRESS'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
							if(!is_null($subscriber['RBMULTISTATIC'])) {
								$this->netaddress->updateStatus($subscriber['RBMULTISTATIC'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
						}
						$data['error'] = 'Failed to update. NPM: '.$npmResult['error'];
						$data['useIPv6'] = $this->useIPv6;
						$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						$this->load->view('modify_user_account_admin', $data);
					} else {
						/**************************************************
						 * if account is to be deactivated, find sessions and delete them
						 **************************************************/
						if ($subscriber['CUSTOMERSTATUS'] == 'InActive') {
							$parts = explode('@', $subscriber['USERNAME']);
							$this->load->model('onlinesession');
							$sessions = $this->onlinesession->getSessions2($parts[0], $parts[1], $this->USESESSIONTABLE2,
								$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
								$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
							if ($sessions['status'] && isset($sessions['data'])) {
								for ($ii = 0; $ii < count($sessions['data']); $ii++) {
									$sess = $sessions['data'][$ii];
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
						/************************************************************
						 * increment bng count
						 ************************************************************/
						if (is_null($subscriber['RBIPADDRESS']) && !is_null($subscriber['RBADDITIONALSERVICE3'])) {
							$this->load->model('bngcounter');
							$this->bngcounter->incrementIPCount($subscriber['RBADDITIONALSERVICE3'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						/**************************************************
						 * update contents of RADIUSPOLICY at TBLCUSTOMER
						 **************************************************/
						// $this->subscribermain->clearRadiuspolicy($subscriber['USERNAME'], $this->SESSIONUSERNAME2, $this->SESSIONPASSWORD2, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						$this->subscribermain->updateRadiuspolicy($subscriber['USERNAME'], str_replace('~', '-', $subscriber['RADIUSPOLICY']),
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						/************************************************************
						 * RM
						 ************************************************************/
						// $this->load->library('rm');
						// $rmClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
						// $rmEdited = true;
						/**************************************************
						 * if branch for handling old rm api
						 * - as of nov 18, 2016 this is no longer used
						 * - remove this later (including closing brace before $rmEdited if block)
						 **************************************************/
						/*
						if ($this->RMAPIHOST == '10.244.4.130' || $this->RMAPIHOST == '10.244.4.131') {
							try {
								$rmSubsUsername = array('key' => 'USERNAME' , 'value' => $subscriber['USERNAME']);
								$rmSubsIdentity = array('key' => 'SUBSCRIBERIDENTITY', 'value' => $subscriber['USERNAME']);
								$rmSubsCui = array('key' => 'CUI', 'value' => $subscriber['USERNAME']);
								$rmSubsStatus = array('key' => 'SUBSCRIBERSTATUS', 'value' => 'Active');
								$rmSubsArea = array('key' => 'AREA', 'value' => $subscriber['CUSTOMERSTATUS']);
								$rmSubsPackage = array('key' => 'SUBSCRIBERPACKAGE', 'value' => str_replace('-', '~', $subscriber['RBACCOUNTPLAN']));
								$customerTypeForRM = '';
								if ($subscriber['CUSTOMERTYPE'] == 'Residential') {
									$customerTypeForRM = 'RESS';
								} else if ($subscriber['CUSTOMERTYPE'] == 'Business') {
									$customerTypeForRM = 'BUSS';
								} else {
									$customerTypeForRM = 'RESS';
								}
								$rmSubsCustomertype = array('key' => 'CUSTOMERTYPE', 'value' => $customerTypeForRM);
								$fapForRM = '';
								if ($subscriber['RBENABLED'] == 'Yes') {
									$fapForRM = 'Y';
								} else if ($subscriber['RBENABLED'] == 'No') {
									$fapForRM = 'N';
								} else {
									$fapForRM = 'N';
								}
								$rmSubsFap = array('key' => 'FAP', 'value' => $fapForRM);
								/**************************************************
								 * try updating
								 **************************************************
								$rmSubsPassword = array('key' => 'PASSWORD', 'value' => '');
								$rmParam = array($rmSubsPassword, $rmSubsStatus, $rmSubsArea, $rmSubsPackage, $rmSubsCustomertype, $rmSubsFap);
								$updateResult = $this->rm->updateAccount($subscriber['USERNAME'], $rmParam, $rmClient);
								if (intval($updateResult['responseCode']) == 200) {
									log_message('debug', 'modify|updated @ RM: '.$subscriber['USERNAME']);
								} else if (intval($updateResult['responseCode']) == 404) {
									log_message('debug', 'modify|'.$subscriber['USERNAME'].' not found, insert');
									$rmParam = array($rmSubsUsername, $rmSubsIdentity, $rmSubsCui, $rmSubsStatus, $rmSubsArea, $rmSubsPackage, $rmSubsCustomertype, $rmSubsFap);
									$insertResult = $this->rm->createAccount($rmParam, $rmClient);
									if (intval($insertResult['responseCode']) == 200) {
										log_message('debug', 'modify|inserted @ RM: '.$subscriber['USERNAME']);
									} else {
										log_message('debug', 'modify|failed to update @ RM: '.$subscriber['USERNAME'].'|'.$insertResult['responseMessage']);
										$rmEdited = false;
									}
								} else {
									log_message('debug', 'modify|failed to update @ RM: '.$subscriber['USERNAME'].'|'.$updateResult['responseMessage']);
									$rmEdited = false;
								}
							} catch (Exception $e) {
								log_message('debug', 'error @ RM:'.json_encode($e));
								$rmEdited = false;
							}
						} else if ($this->RMAPIHOST == '10.81.54.34' || $this->RMAPIHOST == '10.81.54.35') {
						*/
						// try {
						// 	$rmUsername = $subscriber['USERNAME'];
						// 	$rmPlan = $subscriber['RADIUSPOLICY'];
						// 	// $rmStatus = strtoupper($subscriber['CUSTOMERSTATUS']);
						// 	$rmStatus = 'ACTIVE';
						// 	if (strtolower($subscriber['CUSTOMERTYPE']) == 'residential') {
						// 		$rmCustomerType = 'RESS';
						// 	} else if (strtolower($subscriber['CUSTOMERTYPE']) == 'business') {
						// 		$rmCustomerType = 'BUSS';
						// 	} else {
						// 		$rmCustomerType = 'RESS';
						// 	}
						// 	$fapForRM = '';
						// 	if ($subscriber['RBENABLED'] == 'Yes') {
						// 		$fapForRM = 'Y';
						// 	} else if ($subscriber['RBENABLED'] == 'No') {
						// 		$fapForRM = 'N';
						// 	} else {
						// 		$fapForRM = 'N';
						// 	}
						// 	/**************************************************
						// 	 * try updating
						// 	 **************************************************/
						// 	$nodes = array('PARAM3' => $fapForRM, 'AREA' => $subscriber['CUSTOMERSTATUS']);
						// 	$updateResult = $this->rm->wsUpdateSubscriberProfile($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
						// 	log_message('debug', 'RM result code: '.$subscriber['USERNAME'].'|'.$updateResult ['responseCode'] );
						// 	if (intval($updateResult['responseCode']) == 200) {
						// 		log_message('debug', 'modify|updated @ RM: '.$subscriber['USERNAME']);
						// 	} else if (intval($updateResult['responseCode']) == 404) {
						// 		log_message('debug', 'modify|'.$subscriber['USERNAME'].' not found, insert');
						// 		$insertResult = $this->rm->wsAddSubscriber($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
						// 		if (intval($insertResult['responseCode']) == 200) {
						// 			log_message('debug', 'modify|inserted @ RM: '.$subscriber['USERNAME']);
						// 		} else if (intval($insertResult['responseCode']) == 400) {
						// 			// [AJB] [2018-10-08]

						// 			for ($mm = 0; $mm < 4; $mm++) {
						// 				if ($mm == 0) { 
						// 					$thisUsername = $rmUsername;
						// 					$deleteResult = $this->rm->wsDeleteSubscriberProfile($rmClient, $thisUsername);
						// 					log_message('debug', 'modify|delete (alternateId) result for '.$thisUsername.': '.$deleteResult['responseCode'].'|'.$deleteResult['responseMessage']);
						// 					$purgeResult = $this->rm->wsPurgeSubscriber($rmClient, $thisUsername);
						// 					log_message('debug', 'modify|purge (alternateId) result for '.$thisUsername.': '.$purgeResult['responseCode'].'|'.$purgeResult['responseMessage']);
						// 					$deleteResult = $this->rm->wsDeleteSubscriberProfileV2($rmClient, $thisUsername);
						// 					log_message('debug', 'modify|delete (subscriberID) result for '.$thisUsername.': '.$deleteResult['responseCode'].'|'.$deleteResult['responseMessage']);
						// 					$purgeResult = $this->rm->wsPurgeSubscriberV2($rmClient, $thisUsername);
						// 					log_message('debug', 'modify|purge (subscriberID) result for '.$thisUsername.': '.$purgeResult['responseCode'].'|'.$purgeResult['responseMessage']);
						// 				} else {
						// 					$thisUsername = $rmUsername.'#L'.$mm;
						// 					$deleteResult = $this->rm->wsDeleteSubscriberProfile($rmClient, $thisUsername);
						// 					log_message('debug', 'modify|delete (alternateId) result for '.$thisUsername.': '.$deleteResult['responseCode'].'|'.$deleteResult['responseMessage']);
						// 					$purgeResult = $this->rm->wsPurgeSubscriber($rmClient, $thisUsername);
						// 					log_message('debug', 'modify|purge (alternateId) result for '.$thisUsername.': '.$purgeResult['responseCode'].'|'.$purgeResult['responseMessage']);
						// 					$deleteResult = $this->rm->wsDeleteSubscriberProfileV2($rmClient, $thisUsername);
						// 					log_message('debug', 'modify|delete (subscriberID) result for '.$thisUsername.': '.$deleteResult['responseCode'].'|'.$deleteResult['responseMessage']);
						// 					$purgeResult = $this->rm->wsPurgeSubscriberV2($rmClient, $thisUsername);
						// 					log_message('debug', 'modify|purge (subscriberID) result for '.$thisUsername.': '.$purgeResult['responseCode'].'|'.$purgeResult['responseMessage']);
						// 				}
						// 			}

						// 			$insertResult = $this->rm->wsAddSubscriber($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
						// 			log_message('debug', 'modify|insertResult: '.json_encode($insertResult));
						// 			if (intval($insertResult['responseCode']) == 200) {
						// 				log_message('debug', 'modify|insert attempt #2 (from error code 400) successful');
						// 			} else {
						// 				log_message('debug', 'modify|insert attempt #2 (from error code 400) failed: '.$insertResult['responseMessage']);
						// 				$rmEdited = false;
						// 			}
						// 			// [AJB] [2018-10-08] 
						// 		} else {
						// 			log_message('debug', 'modify|failed to insert @ RM: '.$subscriber['USERNAME'].'|'.$insertResult['responseCode'].'|'.
						// 				$insertResult['responseMessage']);
						// 			$rmEdited = false;
						// 		}


						// 	} else {
						// 		log_message('debug', 'modify|failed to update @ RM: '.$subscriber['USERNAME'].'|'.$updateResult['responseCode'].'|'.
						// 			$updateResult['responseMessage']);
						// 		$rmEdited = false;
						// 	}
						// } catch (Exception $e) {
						// 	log_message('debug', 'error @ RM:'.json_encode($e));
						// 	$rmEdited = false;
						// }
						/*
						}
						*/
						// if (!$rmEdited) {
						// 	/************************************************************
						// 	 * revert changes again, use $subscriberOld, revert ipv6/ip/net changes
						// 	 ************************************************************/
						// 	$this->subscribermain->update($cn, $subscriberOld,
						// 		$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 	if ($this->useIPv6) {
						// 		if (!is_null($subscriberOld['RBADDITIONALSERVICE4']) && is_null($subscriber['RBADDITIONALSERVICE4'])) {  //re-mark old ipv6 as used
						// 			$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		} else if (is_null($subscriberOld['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4'])) { //unmark ipv6
						// 			$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
						// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		} else if (!is_null($subscriberOld['RBADDITIONALSERVICE4']) && !is_null($subscriber['RBADDITIONALSERVICE4'])) { //swap back ipv6 addresses
						// 			$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
						// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 			$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		}
						// 	}
						// 	if (!is_null($subscriberOld['RBIPADDRESS']) && is_null($subscriber['RBIPADDRESS'])) {  //re-mark old ip as used
						// 		$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 	} else if (is_null($subscriberOld['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS'])) { //unmark ip
						// 		$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 	} else if (!is_null($subscriberOld['RBIPADDRESS']) && !is_null($subscriber['RBIPADDRESS'])) { //swap back ip addresses
						// 		$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 	}
						// 	if (!is_null($subscriberOld['RBMULTISTATIC']) && is_null($subscriber['RBMULTISTATIC'])) { //re-mark old net as used
						// 		$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 	} else if (is_null($subscriberOld['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC'])) { //unmark net
						// 		$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 	} else if (!is_null($subscriberOld['RBMULTISTATIC']) && !is_null($subscriber['RBMULTISTATIC'])) { //swap back netaddresses
						// 		$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USERNAME'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 	}
						// 	/************************************************************
						// 	 * revert changes to ip/net status (if any)
						// 	 ************************************************************/
						// 	if ($subscriberOld['CUSTOMERSTATUS'] != $subscriber['CUSTOMERSTATUS']) {
						// 		if ($this->useIPv6 && !is_null($subscriber['RBADDITIONALSERVICE4'])) {
						// 			$this->ipaddress->updateV6Status($subscriber['RBADDITIONALSERVICE4'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		}
						// 		if (!is_null($subscriber['RBIPADDRESS'])) {
						// 			$this->ipaddress->updateStatus($subscriber['RBIPADDRESS'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		}
						// 		if(!is_null($subscriber['RBMULTISTATIC'])) {
						// 			$this->netaddress->updateStatus($subscriber['RBMULTISTATIC'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
						// 				$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// 		}
						// 	}
						// 	$data['error'] = 'Failed to update. RM: '.$updateResult['responseMessage'];
						// 	$data['useIPv6'] = $this->useIPv6;
						// 	$data['useSeparateSubnetForNetAddress'] = $this->useSeparateSubnetForNetAddress;
						// 	$this->load->view('modify_user_account', $data);
						// 	return;
						// }
						/************************************************************
						 * /RM
						 ************************************************************/

						/************************************************************
						 * at this point, should the $informationHistory and $sessionHistory be updated?
						 * these variables currently have data prior to the update
						 ************************************************************/
						if ($sysuser != $this->SUPERUSER) {
							$this->load->model('subscriberaudittrail');
							$this->subscriberaudittrail->logSubscriberModification($subscriber, $subscriberOld, $sysuser, $sysuserIP, $modifieddate, true);
						}
						$dataOk = array(
							'subscriber' => $subscriber,
							'message' => 'Account modification successful.',
							'sessionHistory' => $sessionHistory);
						$dataOk['useIPv6'] = $this->useIPv6;
						$this->load->view('modify_user_account_result_admin', $dataOk);
					}
				}
			}
		}
	}

	public function processBulkUpdateSubscribers($step = null) {
		$this->redirectIfNoAccess('Modify Account', 'subscribers/processBulkUpdateSubscribers');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@processBulkUpdateSubscribers|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@processBulkUpdateSubscribers|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		/**************************************************
		 * subscribers/processBulkUpdateSubscribers accessed via form
		 **************************************************/
		if (is_null($step)) {
			$step = $this->input->post('step');
			$realm = '';
			/**************************************************
			 * upload step
			 **************************************************/
			if ($step == 'upload') {
				//get realms
				$this->load->model('realm');
				$realms = $this->realm->fetchAllNamesOnly();
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx|application/vnd.ms-office|\"application/vnd.ms-office\"|"application/vnd.ms-office"';
				$config['max_size'] = '50000';
				$config['overwrite'] = true;
				$this->load->library('upload', $config);
				/**************************************************
				 * upload failed
				 **************************************************/
				if (!$this->upload->do_upload('file')) {
					$data['step'] = 'upload';
					$data['error'] = 'Upload failed: '.$this->upload->display_errors();
					$data['realm'] = $this->input->post('realm');
					$data['realms'] = $realms;
					$data['proceed'] = $checks['go'];
					if ($portal == 'service') {
						$data['realm'] = $realm;
						$data['disableRealm'] = true;
					}
					$data['useIPv6'] = $this->useIPv6;
					$this->load->view('bulk_modify_users', $data);
				/**************************************************
				 * upload ok
				 **************************************************/
				} else {
					$realm = $this->input->post('realm');
					$this->load->model('util');
					$uploaded = $this->upload->data();
					log_message('info', 'UPLOADED FILE: '.json_encode($uploaded));
					/**************************************************
					 * check uploaded xls format (header check);
					 **************************************************/
					if ($this->useIPv6) {
						$valid = $this->util->verifyBulkUpdateFormatV2($uploaded['full_path']);
					} else {
						$valid = $this->util->verifyBulkUpdateFormat($uploaded['full_path']);
					}
					/**************************************************
					 * invalid xls file (some headers did not match)
					 **************************************************/
					if (!$valid) {
						$data['step'] = 'upload';
						$data['error'] = 'Invalid file contents: "'.$uploaded['client_name'].'"';
						$data['realm'] = $realm;
						$data['realms'] = $realms;
						$data['proceed'] = $checks['go'];
						if ($portal == 'service') {
							$data['realm'] = $realm;
							$data['disableRealm'] = true;
						}
						$data['useIPv6'] = $this->useIPv6;
						$this->load->view('bulk_modify_users', $data);
					/**************************************************
					 * valid xls file
					 **************************************************/
					} else {
    					$totalRowCount = $this->util->countRows($uploaded['full_path']);
						log_message('info', 'fileRowCount:'.$totalRowCount);
						/**************************************************
						 * check if xls row count exceeds maximum
						 **************************************************/
						if ($totalRowCount > $this->xlsRowLimit) {
							$data['step'] = 'upload';
							$data['error'] = 'Due to memory constraints, please limit files to less than 5000 rows.';
							$data['realm'] = $realm;
							$data['realms'] = $realms;
							$data['proceed'] = $checks['go'];
							if ($portal == 'service') {
								$data['realm'] = $realm;
								$data['disableRealm'] = true;
							}
							$data['useIPv6'] = $this->useIPv6;
							$this->load->view('bulk_modify_users', $data);
							return;
						}
						/**************************************************
						 * check xls row contents: basically only checks if a username is provided
						 **************************************************/
						$rows = $this->util->verifyBulkUpdateData($uploaded['full_path'], $realm);
						$this->load->model('subscribermain');
						log_message('info', 'ROWS: '.json_encode($rows));
						$validRowsTmp = [];
						$validMarks = [];
						$toDeleteTmp = [];
						$validRows = [];
						$toDelete = [];
						/**************************************************
						 * separate rows to be deleted (STATUS = 'T')
						 **************************************************/
						for ($i = 0; $i < count($rows['valid']); $i++) {
							if ($rows['valid'][$i][3] == 'T') {
								$toDeleteTmp[] = $rows['valid'][$i];
							} else {
								$validRowsTmp[] = $rows['valid'][$i];
							}
						}
						/**************************************************
						 * preparation for update (pre-confirm)
						 * if no data is provided on the not required column, current data will be placed instead
						 * $mark array indicates which info will be updated
						 **************************************************/
						for ($i = 0; $i < count($validRowsTmp); $i++) {
							$row = $validRowsTmp[$i];
							$subs = $this->subscribermain->findByUserIdentity($row[0]);
							log_message('info', '@SUBS:'.$i.'|'.json_encode($subs));
							$mark = [];
							if (trim($row[1]) == '') {
								$row[1] = $subs === false ? '' : $subs['PASSWORD'];
							} else {
								$mark['PASSWORD'] = true;
							}
							// Remove Dependencies 5/20/19
							// if (trim($row[2]) == '') {
							// 	$val = '';
							// 	if ($subs !== false) {
							// 		if ($subs['CUSTOMERTYPE'] == 'Residential' || $subs['CUSTOMERTYPE'] == 'R') {
							// 			$val = 'R';
							// 		} else if ($subs['CUSTOMERTYPE'] == 'Business' || $subs['CUSTOMERTYPE'] == 'B') {
							// 			$val = 'B';
							// 		}
							// 	}
							// 	$row[2] = $subs === false ? '' : $val;
							// } else {
							// 	$mark['CUSTOMERTYPE'] = true;
							// }
							// if (trim($row[3]) == '') {
							// 	$row[3] = $subs === false ? '' : ($subs['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D');
							// } else {
							// 	$mark['CUSTOMERSTATUS'] = true;
							// }
							if (trim($row[4]) == '') {
								$row[4] = $subs === false ? '' : (is_null($subs['RBORDERNUMBER']) ? '' : $subs['RBORDERNUMBER']);
							} else {
								$mark['RBORDERNUMBER'] = true;
							}
							if (trim($row[5]) == '') {
								$row[5] = $subs === false ? '' : $subs['RBCUSTOMERNAME'];
							} else {
								$mark['RBCUSTOMERNAME'] = true;
							}
							if (trim($row[6]) == '') {
								$row[6] = $subs === false ? '' : $subs['RBSERVICENUMBER'];
							} else {
								$mark['RBSERVICENUMBER'] = true;
							}
							// if (trim($row[7]) == '') {
							// 	$val = '';
							// 	if ($subs !== false) {
							// 		if ($subs['RBENABLED'] == 'Yes' || $subs['RBENABLED'] == 'Y') {
							// 			$val = 'Y';
							// 		} else if ($subs['RBENABLED'] == 'No' || $subs['RBENABLED'] == 'N') {
							// 			$val = 'N';
							// 		}
							// 	}
							// 	$row[7] = $subs === false ? '' : $val;
							// } else {
							// 	$mark['RBENABLED'] = true;
							// }
							// if (trim($row[8]) == '') {
							// 	$row[8] = $subs === false ? '' : $subs['RBACCOUNTPLAN'];
							// } else {
							// 	$mark['RBACCOUNTPLAN'] = true;
							// }
							if ($this->useIPv6) {
								if (trim($row[9]) == '') {
									$row[9] = $subs === false ? '' : is_null($subs['RBADDITIONALSERVICE4']) ? '' : $subs['RBADDITIONALSERVICE4'];
								} else {
									$mark['RBADDITIONALSERVICE4'] = true;
								}
								if (trim($row[10]) == '') {
									$row[10] = $subs === false ? '' : is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS'];
								} else {
									$mark['RBIPADDRESS'] = true;
								}
								if (trim($row[11]) == '') {
									$row[11] = $subs === '' ? '' : is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC'];
								} else {
									$mark['RBMULTISTATIC'] = true;
								}
							} else {
								if (trim($row[9]) == '') {
									$row[9] = $subs === false ? '' : is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS'];
								} else {
									$mark['RBIPADDRESS'] = true;
								}
								if (trim($row[10]) == '') {
									$row[10] = $subs === '' ? '' : is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC'];
								} else {
									$mark['RBMULTISTATIC'] = true;
								}
							}
							$validRows[] = $row;
							$validMarks[] = $mark;
						}
						/**************************************************
						 * get data of accounts to be deleted
						 **************************************************/
						for ($i = 0; $i < count($toDeleteTmp); $i++) {
							$row = $toDeleteTmp[$i];
							$subs = $this->subscribermain->findByUserIdentity($row[0]);
							if ($subs !== false) {
								if (trim($row[1]) == '') {
									$row[1] = $subs['PASSWORD'];
								}
								// if (trim($row[2]) == '') {
								// 	$val = '';
								// 	if ($subs['CUSTOMERTYPE'] == 'Residential' || $subs['CUSTOMERTYPE'] == 'R') {
								// 		$val = 'R';
								// 	} else if ($subs['CUSTOMERTYPE'] == 'Business' || $subs['CUSTOMERTYPE'] == 'B') {
								// 		$val = 'B';
								// 	}
								// 	$row[2] = $val;
								// }
								// if (trim($row[3]) == '') {
								// 	$row[3] = $subs['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D';
								// }
								if (trim($row[4]) == '') {
									$row[4] = $subs['RBORDERNUMBER'];
								}
								if (trim($row[5]) == '') {
									$row[5] = $subs['RBCUSTOMERNAME'];
								}
								if (trim($row[6]) == '') {
									$row[6] = $subs['RBSERVICENUMBER'];
								}
								// if (trim($row[7]) == '') {
								// 	$val = '';
								// 	if ($subs['RBENABLED'] == 'Yes' || $subs['RBENABLED'] == 'Y') {
								// 		$val = 'Y';
								// 	} else if ($subs['RBENABLED'] == 'No' || $subs['RBENABLED'] == 'N') {
								// 		$val = 'N';
								// 	}
								// 	$row[7] = $val;
								// }
								// if (trim($row[8]) == '') {
								// 	$row[8] = $subs['RBACCOUNTPLAN'];
								// }
								if ($this->useIPv6) {
									if (trim($row[9]) == '') {
										$row[9] = is_null($subs['RBADDITIONALSERVICE4']) ? '' : $subs['RBADDITIONALSERVICE4'];
									}
									if (trim($row[10]) == '') {
										$row[10] = is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS'];
									}
									if (trim($row[11]) == '') {
										$row[11] = is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC'];
									}
								} else {
									if (trim($row[9]) == '') {
										$row[9] = is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS'];
									}
									if (trim($row[10]) == '') {
										$row[10] = is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC'];
									}
								}
								$toDelete[] = $row;
							}
    					}

	    				$data = array(
	    					'step' => 'confirm',
	    					'proceed' => $checks['go'],
							'error' => $checks['msg'],
	    					'path' => $uploaded['full_path'],
	    					'realm' => $realm,
	    					'valid' => $validRows,
	    					'validMarks' => $validMarks,
	    					'validRowNumbers' => $rows['validRowNumbers'],
	    					'invalid' => $rows['invalid'],
	    					'invalidRowNumbers' => $rows['invalidRowNumbers'],
	    					'toDelete' => $toDelete);
	    				$data['useIPv6'] = $this->useIPv6;
	    				$this->load->view('bulk_modify_users', $data);
    				}
    			}
    		/**************************************************
			 * update step
			 **************************************************/
			} else if ($step == 'update') {
				if (!$checks['go']) {
					redirect('subscribers/processBulkUpdateSubscribers/upload');
				}
				$now = date('Y-m-d H:i:s', time());
				$realm = $this->input->post('realm');
				$path = $this->input->post('path');
				$validRowNumbers = unserialize($this->input->post('validRowNumbers'));
				$invalidRowNumbers = unserialize($this->input->post('invalidRowNumbers'));
				log_message('info', 'VALID ROWS: '.json_encode($validRowNumbers));
				log_message('info', 'INVALID ROWS: '.json_encode($invalidRowNumbers));
				$selectedStatus = $this->input->post('selectedstatus');
				$updatedRows = [];
				$updatedMarks = [];
				$notUpdatedUsernameDNE = [];
				$notUpdatedInvalidData = [];
				$notUpdatedIPNetError = [];
				$notUpdatedNPMError = [];
				$forDeletion = [];
				$forDeletionRowNumbers = [];
				$updatedRowNumbers = [];
				$notUpdatedUsernameDNERowNumbers = [];
				$notUpdatedInvalidDataRowNumbers = [];
				$notUpdatedIPNetErrorRowNumbers = [];
				$notUpdatedNPMErrorRowNumbers = [];
				$this->load->model('util');
				$this->load->model('subscribermain');
				$this->load->model('ipaddress');
				$this->load->model('netaddress');
				$this->load->model('services');
				$this->load->model('onlinesession');
				$this->load->model('cabinet');
				if ($this->useIPv6) {
					$dataToUpdate = $this->util->extractRowsToUpdateV2($path, $validRowNumbers);
				} else {
					$dataToUpdate = $this->util->extractRowsToUpdate($path, $validRowNumbers);
				}
				log_message('info', 'TO UPDATE: '.json_encode($dataToUpdate));

				/**************************************************
				 * get these from session variables
				 **************************************************/
				$sysuser = $this->session->userdata('username');
				$sysuserIP = $this->session->userdata('ip_address');
				// $this->load->library('rm');
				$dataToUpdateCount = count($dataToUpdate);
				for ($i = 0; $i < $dataToUpdateCount; $i++ ) {
					$dataToUpdate[$i][3] = $selectedStatus;
					$dataToUpdate[$i][2] = trim($dataToUpdate[$i][2]);
					$dataToUpdate[$i][7] = trim($dataToUpdate[$i][7]);
					$dataToUpdate[$i][8] = trim($dataToUpdate[$i][8]);
					$dataToUpdate[$i][9] = trim($dataToUpdate[$i][9]);
					$dataToUpdate[$i][10] = trim($dataToUpdate[$i][10]);
					$dataToUpdate[$i][11] = trim($dataToUpdate[$i][11]);
					/**************************************************
					 * convert xls row to $subscriber array
					 **************************************************/
					if ($this->useIPv6) {
						$subscriber = $this->subscribermain->rowDataToSubscriberUpdateArrayV2($dataToUpdate[$i], $realm, $sysuser);
					} else {
						$subscriber = $this->subscribermain->rowDataToSubscriberUpdateArray($dataToUpdate[$i], $realm, $sysuser);
					}
					log_message('info', $i.'|NEW : '.json_encode($subscriber));
					/**************************************************
					 * check if account exists, skip if not found
					 **************************************************/
					$exists = $this->subscribermain->subscriberExists($subscriber['USER_IDENTITY'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if (!$exists) {
						log_message('info', $i.'|subscriber does not exist');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notUpdatedUsernameDNERowNumbers[] = $validRowNumbers[$i];
						$notUpdatedUsernameDNE[] = $dataToUpdate[$i];
						continue;
					}
					/**************************************************
					 * account found, get current data
					 **************************************************/
					$subscriberOld = $this->subscribermain->findByUserIdentity($subscriber['USER_IDENTITY']);
					log_message('info', $i.'|OLD : '.json_encode($subscriberOld));
					/**************************************************
					 * copy columns that will not be changed by data from xls
					 **************************************************/
					$subscriber['BANDWIDTH'] = $subscriberOld['BANDWIDTH'];
					$subscriber['CREATEDATE'] = $subscriberOld['CREATEDATE'];
					$subscriber['RBADDITIONALSERVICE5'] = $subscriberOld['RBADDITIONALSERVICE5'];
					$subscriber['RBADDITIONALSERVICE4'] = is_null($subscriber['RBADDITIONALSERVICE4']) ? $subscriberOld['RBADDITIONALSERVICE4'] : $subscriber['RBADDITIONALSERVICE4'];
					$subscriber['RBADDITIONALSERVICE3'] = $subscriberOld['RBADDITIONALSERVICE3'];
					$subscriber['RBACCOUNTSTATUS'] = $subscriberOld['RBACCOUNTSTATUS'];
					$subscriber['RBSVCCODE2'] = $subscriberOld['RBSVCCODE2'];
					$subscriber['RBSECONDARYACCOUNT'] = $subscriberOld['RBSECONDARYACCOUNT'];
					$subscriber['RBUNLIMITEDACCESS'] = $subscriberOld['RBUNLIMITEDACCESS'];
					$subscriber['RBTIMESLOT'] = $subscriberOld['RBTIMESLOT'];
					$subscriber['RBREALM'] = $subscriberOld['RBREALM'];
					$subscriber['RBNUMBEROFSESSION'] = $subscriberOld['RBNUMBEROFSESSION'];
					$subscriber['RBCREATEDBY'] = $subscriberOld['RBCREATEDBY'];
					$subscriber['PASSWORD'] = is_null($subscriber['PASSWORD']) ? $subscriberOld['PASSWORD'] : $subscriber['PASSWORD'];
					$subscriber['RBCUSTOMERNAME'] = is_null($subscriber['RBCUSTOMERNAME']) ? $subscriberOld['RBCUSTOMERNAME'] : $subscriber['RBCUSTOMERNAME'];
					$subscriber['RBADDITIONALSERVICE2'] = is_null($subscriber['RBADDITIONALSERVICE2']) ? $subscriberOld['RBADDITIONALSERVICE2'] : $subscriber['RBADDITIONALSERVICE2'];
					$subscriber['RBADDITIONALSERVICE1'] = is_null($subscriber['RBADDITIONALSERVICE1']) ? $subscriberOld['RBADDITIONALSERVICE1'] : $subscriber['RBADDITIONALSERVICE1'];
					$subscriber['RBSVCCODE'] = is_null($subscriber['RBSVCCODE']) ? $subscriberOld['RBSVCCODE'] : $subscriber['RBSVCCODE'];
					$subscriber['CUSTOMERTYPE'] = is_null($subscriber['CUSTOMERTYPE']) ? $subscriberOld['CUSTOMERTYPE'] : $subscriber['CUSTOMERTYPE'];
					$subscriber['RBORDERNUMBER'] = is_null($subscriber['RBORDERNUMBER']) ? $subscriberOld['RBORDERNUMBER'] : $subscriber['RBORDERNUMBER'];
					$subscriber['RBENABLED'] = is_null($subscriber['RBENABLED']) ? $subscriberOld['RBENABLED'] : $subscriber['RBENABLED'];
					/**************************************************
					 * process new rbenabled
					 **************************************************/
					if (strtolower($subscriber['RBENABLED']) == 'yes' && strtolower($subscriber['CUSTOMERTYPE']) == 'residential') {
						$subscriber['RBENABLED'] = 'Yes';
					} else {
						$subscriber['RBENABLED'] = 'No';
					}
					/**************************************************
					 * process new status
					 **************************************************/
					if (!is_null($subscriber['CUSTOMERSTATUS'])) {
						if ($subscriber['CUSTOMERSTATUS'] == 'T') {
							log_message('info', $i.'|for deletion');
							$forDeletion[] = $this->subscribermain->findByUserIdentity($subscriber['USER_IDENTITY']);
							$forDeletionRowNumbers[] = $validRowNumbers[$i];
							continue;
						}
						if ($subscriber['CUSTOMERSTATUS'] != $subscriberOld['CUSTOMERSTATUS']) {
							$subscriber['RBCHANGESTATUSDATE'] = date('Y-m-d H:i:s', time());
							$subscriber['RBCHANGESTATUSFROM'] = $subscriberOld['CUSTOMERSTATUS'];
						} else {
							$subscriber['RBCHANGESTATUSDATE'] = $subscriberOld['RBCHANGESTATUSDATE'];
							$subscriber['RBCHANGESTATUSFROM'] = $subscriberOld['RBCHANGESTATUSFROM'];
						}
					} else {
						$subscriber['CUSTOMERSTATUS'] = $subscriberOld['CUSTOMERSTATUS'];
					}
					/**************************************************
					 * process new service number
					 **************************************************/
					if (!is_null($subscriber['RBSERVICENUMBER'])) {
						if ($subscriberOld['RBSERVICENUMBER'] != $subscriber['RBSERVICENUMBER']) {
							$serviceNumberExists = $this->subscribermain->serviceNumberExists(strval($subscriber['RBSERVICENUMBER']),
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if ($serviceNumberExists) {
								log_message('info', $i.'|new service number already exists');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedInvalidDataRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedInvalidData[] = array('rowdata' => $dataToUpdate[$i], 'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm), 'errors' => array('RBSERVICENUMBER' => 'Service number exists.'));
								continue;
							}
						}
					} else {
						$subscriber['RBSERVICENUMBER'] = $subscriberOld['RBSERVICENUMBER'];
					}
					/**************************************************
					 * check if plan is on database
					 **************************************************/
					if (!is_null($subscriber['RBACCOUNTPLAN'])) {
						// $serviceExists = $this->services->serviceExists($subscriber['RBACCOUNTPLAN']);
						$serviceExists = $this->services->serviceExistsNew2($subscriber['RBACCOUNTPLAN'],
							$this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
						if (!$serviceExists) {
							log_message('info', $i.'|service dne');
							$invalidRowNumbers[] = $validRowNumbers[$i];
							$notUpdatedInvalidDataRowNumbers[] = $validRowNumbers[$i];
							$notUpdatedInvalidData[] = array('rowdata' => $dataToUpdate[$i], 'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm), 'errors' => array('RBACCOUNTPLAN' => 'Service does not exist.'));
							continue;
						}
					}
					$ipSet = false;
					$netSet = false;
					$ipV6Set = false;
					/**************************************************
					 * if new plan OR ipaddress is entered, check for gpon
					 * (old plan + new ip / new plan + old ip / new plan + new ip)
					 **************************************************/
					/**************************************************
					 * new ip address is provided
					 **************************************************/
					if (!is_null($subscriber['RBIPADDRESS'])) {
						/**************************************************
						 * check provided ip validity
						 **************************************************/
						$validIp = $this->util->isIPValid($subscriber['RBIPADDRESS']);
						if (!$validIp) {
							log_message('info', $i.'|invalid ip, check if it is cabinet name: '.$subscriber['RBIPADDRESS']);
							$cabinetName = $subscriber['RBIPADDRESS'];
							$cabinetObj = $this->cabinet->getCabinetWithName($cabinetName);
							if ($cabinetObj === false || empty($cabinetObj) || is_null($cabinetObj)) {
								log_message('info', $i.'|invalid cabinet');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Invalid cabinet name ('.$cabinetName.').'));
								continue;
							}
							/**************************************************
							 * cabinet name is valid, get an unused ip address after checking if gpon type or not
							 **************************************************/
							$srv = !is_null($subscriber['RBACCOUNTPLAN']) ? strtolower($subscriber['RBACCOUNTPLAN']) : strtolower($subscriberOld['RBACCOUNTPLAN']);
							$needsGponIp = strpos($srv, 'gpon') !== false;
							$nextAvailableIpAddress = $this->ipaddress->getNextAvailableIpAddress($cabinetObj['homing_bng'], $needsGponIp,
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if ($nextAvailableIpAddress === false || empty($nextAvailableIpAddress) || is_null($nextAvailableIpAddress)) {
								log_message('info', $i.'|no available ip address');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'No available IP Address.'));
								continue;
							}
							/**************************************************
							 * assign new ip address
							 **************************************************/
							$subscriber['RBIPADDRESS'] = $nextAvailableIpAddress['IPADDRESS'];
							log_message('info', $i.'|mark new ip as used: '.$subscriber['RBIPADDRESS']);
							$this->ipaddress->markAsUsed($subscriber['RBIPADDRESS'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$ipSet = true;
							/**************************************************
							 * free up old ip address
							 **************************************************/
							if (!is_null($subscriberOld['RBIPADDRESS'])) {
								log_message('info', $i.'|free up old ip: '.$subscriberOld['RBIPADDRESS']);
								$this->ipaddress->freeUp($subscriberOld['RBIPADDRESS'],
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
							if ($this->useIPv6) {
								$dataToUpdate[$i][10] = $subscriber['RBIPADDRESS'];
							} else {
								$dataToUpdate[$i][9] = $subscriber['RBIPADDRESS'];
							}
						/**************************************************
						 * ip is valid, more checks follow
						 **************************************************/
						} else {
							/**************************************************
							 * check if ip address is at database
							 **************************************************/
							$ipExists = $this->ipaddress->ipExists($subscriber['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if (!$ipExists) {
								log_message('info', $i.'|ip does not exist');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Static IP address not available in IP pool.'));
								continue;
							}
							/**************************************************
							 * check if ip address can be used
							 **************************************************/
							$ipFree = $this->ipaddress->isFree($subscriber['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if (!$ipFree) {
								log_message('info', $i.'|ip not available');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Static IP used.'));
								continue;
							}
							/**************************************************
							 * check for gpon matching (plan & ip)
							 **************************************************/
							$srv = !is_null($subscriber['RBACCOUNTPLAN']) ? strtolower($subscriber['RBACCOUNTPLAN']) : strtolower($subscriberOld['RBACCOUNTPLAN']);
							$ipMustBeGPON = true;
							if (strpos($srv, 'gpon') === false) {
								$ipMustBeGPON = false;
							}
							$isGPON = $this->ipaddress->isGPON($subscriber['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if ($ipMustBeGPON && !$isGPON) {
								log_message('info', $i.'|ip address is not GPON (should be)');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Static IP must be GPON type.'));
								continue;
							} else if (!$ipMustBeGPON && $isGPON) {
								log_message('info', $i.'|ip address is GPON (should not be)');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Static IP must not be GPON type.'));
								continue;
							}
							/**************************************************
							 * ip can be used
							 **************************************************/
							log_message('info', $i.'|mark new ip as used: '.$subscriber['RBIPADDRESS']);
							$this->ipaddress->markAsUsed($subscriber['RBIPADDRESS'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$ipSet = true;
							/**************************************************
							 * free up old ip address
							 **************************************************/
							if (!is_null($subscriberOld['RBIPADDRESS'])) {
								log_message('info', $i.'|free up old ip: '.$subscriberOld['RBIPADDRESS']);
								$this->ipaddress->freeUp($subscriberOld['RBIPADDRESS'],
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
						}
					/**************************************************
					 * no ip address is provided (will not be changed)
					 **************************************************/
					} else {
						$subscriber['RBIPADDRESS'] = $subscriberOld['RBIPADDRESS'];
						/**************************************************
						 * new plan is provided
						 **************************************************/
						if (!is_null($subscriber['RBACCOUNTPLAN'])) {
							$srv = strtolower($subscriber['RBACCOUNTPLAN']);
							if (strpos($srv, 'gpon') === false) {
								$ipMustBeGPON = false;
							}
							/**************************************************
							 * check gpon matching (plan & ip)
							 **************************************************/
							$isGPON = $this->ipaddress->isGPON($subscriberOld['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if ($ipMustBeGPON && !$isGPON) {
								log_message('info', $i.'|ip address is not GPON (should be)');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Static IP must be GPON type.', 'RBACCOUNTPLAN' => 'Service is GPON type.'));
								continue;
							} else if (!$ipMustBeGPON && $isGPON) {
								log_message('info', $i.'|ip address is GPON (should not be)');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Static IP must not be GPON type.', 'RBACCOUNTPLAN' => 'Service is not GPON type.'));
								continue;
							}
						}
					}
					$subscriber['RBACCOUNTPLAN'] = !is_null($subscriber['RBACCOUNTPLAN']) ? $subscriber['RBACCOUNTPLAN'] : str_replace('~', '-', $subscriberOld['RBACCOUNTPLAN']);
					$subscriber['RADIUSPOLICY'] = !is_null($subscriber['RADIUSPOLICY']) ? $subscriber['RADIUSPOLICY'] : str_replace('~', '-', $subscriberOld['RADIUSPOLICY']);
					/**************************************************
					 * handle new net address
					 **************************************************/
					if (!is_null($subscriber['RBMULTISTATIC'])) {
						/**************************************************
						 * check if account has/will have ip address (cannot have net address without ip address)
						 **************************************************/
						if (is_null($subscriberOld['RBIPADDRESS'])) {
							if (!$ipSet) {
								log_message('info', $i.'|entering net without ip');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[$i] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Account has no Static IP.', 'RBMULTISTATIC' => 'Account has no Static IP.'));
								continue;
							}
						}
						/**************************************************
						 * check net address validity
						 **************************************************/
						$validNet = $this->util->isIPValid($subscriber['RBMULTISTATIC']);
						if (!$validNet) {
							log_message('info', $i.'|invalid net address, check if valid cabinet name and if a subnet is given');
							$givenNetAddress = $subscriber['RBMULTISTATIC'];
							if ($this->useSeparateSubnetForNetAddress) {
								$netAddressParts = explode($this->netAddressSubnetMarker, $givenNetAddress);
								$partsCount = count($netAddressParts);
							} else {
								$partsCount = 1;
							}
							/**************************************************
							 * is it a valid cabinet name?
							 **************************************************/
							$cabinetName = $this->useSeparateSubnetForNetAddress ? $netAddressParts[0] : $givenNetAddress;
							$cabinetObj = $this->cabinet->getCabinetWithName($cabinetName);
							log_message('debug', $i.'|cabinetName: '.$cabinetName.', cabinetObj: '.json_encode($cabinetObj));
							/**************************************************
							 * not a valid cabinet name, revert changes to ip address earlier
							 **************************************************/
							if ($cabinetObj === false || empty($cabinetObj) || is_null($cabinetObj)) {
								log_message('info', $i.'|invalid cabinet');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBMULTISTATIC' => 'Invalid cabinet name ('.$cabinetName.').'));
								if ($ipSet) {
									$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USER_IDENTITY'],
										$subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
								}
								continue;
							}
							/**************************************************
							 * was there a subnet given?
							 * only works if $this->useSeparateSubnetForNetAddress is true, else $cabinetSubnet is null
							 **************************************************/
							if ($this->useSeparateSubnetForNetAddress && $partsCount == 2) {
								$cabinetSubnet = $netAddressParts[1];
								log_message('debug', $i.'|subnet restriction: '.$cabinetSubnet);
							} else {
								$cabinetSubnet = null;
								log_message('debug', $i.'|no subnet restriction');
							}
							/**************************************************
							 * valid cabinet name, get first available net address (with or without subnet restriction)
							 **************************************************/
							$nextAvailableNetAddress = $this->netaddress->getNextAvailableNetAddressWithLocationAndSubnet($cabinetObj['homing_bng'], $cabinetSubnet,
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if ($nextAvailableNetAddress === false || empty($nextAvailableNetAddress) || is_null($nextAvailableNetAddress)) {
								log_message('info', $i.'|no available net address');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBMULTISTATIC' => 'No available Net Address.'));
								if ($ipSet) {
									$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USER_IDENTITY'],
										$subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
								}
								continue;
							}
							/**************************************************
							 * assign new net address
							 **************************************************/
							$subscriber['RBMULTISTATIC'] = $nextAvailableNetAddress['NETADDRESS'];
							log_message('info', $i.'|mark new net as used: '.$subscriber['RBMULTISTATIC']);
							$this->netaddress->markAsUsed($subscriber['RBMULTISTATIC'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$netSet = true;
							/**************************************************
							 * account has valid RBMULTISTATIC, set RBENABLED to 'No'
							 **************************************************/
							log_message('info', '@bulkmodify: '.$subscriber['USERNAME'].' has valid RBMULTISTATIC, setting RBENABLED to \'No\'');
							$subscriber['RBENABLED'] = 'No';
							/**************************************************
							 * free up old net address
							 **************************************************/
							if (!is_null($subscriberOld['RBMULTISTATIC'])) {
								log_message('info', $i.'|free up old net: '.$subscriberOld['RBMULTISTATIC']);
								$this->netaddress->freeUp($subscriberOld['RBMULTISTATIC'],
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
							if ($this->useIPv6) {
								$dataToUpdate[$i][11] = $subscriber['RBMULTISTATIC'];
							} else {
								$dataToUpdate[$i][10] = $subscriber['RBMULTISTATIC'];
							}
						/**************************************************
						 * net address is valid, more checks follow
						 **************************************************/
						} else {
							/**************************************************
							 * check if net address is in database
							 **************************************************/
							$netExists = $this->netaddress->netExists($subscriber['RBMULTISTATIC'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if (!$netExists) {
								log_message('info', $i.'|net address does not exist');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBMULTISTATIC' => 'Multi IP address not available in IP pool.'));
								if ($ipSet) {
									$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
								}
								continue;
							}
							/**************************************************
							 * check if net address is available
							 **************************************************/
							$netFree = $this->netaddress->isFree($subscriber['RBMULTISTATIC'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if (!$netFree) { //net address used
								log_message('info', $i.'|net address not available');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBMULTISTATIC' => 'Multi IP used.'));
								if ($ipSet) {
									$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
								}
								continue;
							}
							log_message('info', 'mark new net as used: '.$subscriber['RBMULTISTATIC']);
							/**************************************************
							 * net address can be used
							 **************************************************/
							$this->netaddress->markAsUsed($subscriber['RBMULTISTATIC'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$netSet = true;
							/**************************************************
							 * account has valid RBMULTISTATIC, set RBENABLED to 'No'
							 **************************************************/
							log_message('info', '@bulkmodify: '.$subscriber['USERNAME'].' has valid RBMULTISTATIC, setting RBENABLED to \'No\'');
							$subscriber['RBENABLED'] = 'No';
							/**************************************************
							 * free up old net address
							 **************************************************/
							if (!is_null($subscriberOld['RBMULTISTATIC'])) {
								log_message('info', $i.'|free up old net: '.$subscriberOld['RBMULTISTATIC']);
								$this->netaddress->freeUp($subscriberOld['RBMULTISTATIC'],
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
						}
					/**************************************************
					 * no new net address provided (will not be changed);
					 **************************************************/
					} else {
						$subscriber['RBMULTISTATIC'] = $subscriberOld['RBMULTISTATIC'];
					}
					/**************************************************
					 * append new remarks to previous, trim if new length is more than 3500 chars
					 **************************************************/
					$subscriber['RBREMARKS'] = is_null($subscriber['RBREMARKS']) ? $subscriberOld['RBREMARKS'] : $subscriberOld['RBREMARKS'].';'.
						date('Y-m-d H:i:s', time()).' '.$subscriber['RBREMARKS'];
					if (strlen($subscriber['RBREMARKS']) > 3500) {
						$subscriber['RBREMARKS'] = substr($subscriber['RBREMARKS'], strlen($subscriber['RBREMARKS']) - 3500);
					}
					/**************************************************
					 * handle new ipv6 address
					 **************************************************/
					if ($this->useIPv6 && !is_null($subscriber['RBADDITIONALSERVICE4'])) {
						/**************************************************
						 * check provided ipv6 validity
						 **************************************************/
						$validIpv6 = $this->ipaddress->isIPV6Valid($subscriber['RBADDITIONALSERVICE4']);
						if (!$validIpv6) {
							log_message('info', $i.'|invalid ipv6, check if it is cabinet name: '.$subscriber['RBADDITIONALSERVICE4']);
							$cabinetName = $subscriber['RBADDITIONALSERVICE4'];
							$cabinetObj = $this->cabinet->getCabinetWithName($cabinetName);
							if ($cabinetObj === false || empty($cabinetObj) || is_null($cabinetObj)) {
								log_message('info', $i.'|invalid cabinet');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Invalid cabinet name ('.$cabinetName.').'));
								continue;
							}
							/**************************************************
							 * cabinet name is valid, get an unused ipv6 address
							 **************************************************/
							$nextAvailableIpV6Address = $this->ipaddress->getNextAvailableIpV6Address($cabinetObj['homing_bng'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if ($nextAvailableIpV6Address === false || empty($nextAvailableIpV6Address) || is_null($nextAvailableIpV6Address)) {
								log_message('info', $i.'|no available ip address');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'No available IP Address.'));
								continue;
							}
							/**************************************************
							 * assign new ipv6 address
							 **************************************************/
							$subscriber['RBADDITIONALSERVICE4'] = $nextAvailableIpV6Address['IPV6ADDR'];
							log_message('info', $i.'|mark new ipv6 as used: '.$subscriber['RBADDITIONALSERVICE4']);
							$this->ipaddress->markV6AsUsed($subscriber['RBADDITIONALSERVICE4'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$ipV6Set = true;
							/**************************************************
							 * free up old ipv6 address
							 **************************************************/
							if (!is_null($subscriberOld['RBADDITIONALSERVICE4'])) {
								log_message('info', $i.'|free up old ipv6: '.$subscriberOld['RBADDITIONALSERVICE4']);
								$this->ipaddress->freeUpV6($subscriberOld['RBADDITIONALSERVICE4'],
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
							$dataToUpdate[$i][9] = $subscriber['RBADDITIONALSERVICE4'];
						/**************************************************
						 * ipv6 is valid, more checks follow
						 **************************************************/
						} else {
							/**************************************************
							 * check if ipv6 address is at database
							 **************************************************/
							$ipV6Exists = $this->ipaddress->ipV6Exists($subscriber['RBADDITIONALSERVICE4'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if (!$ipV6Exists) {
								log_message('info', $i.'|ipv6 does not exist');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBADDITIONALSERVICE4' => 'Static IPv6 address not available in IP pool.'));
								continue;
							}
							/**************************************************
							 * check if ipv6 address can be used
							 **************************************************/
							$ipV6Free = $this->ipaddress->isV6Free($subscriber['RBADDITIONALSERVICE4'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if (!$ipV6Free) {
								log_message('info', $i.'|ipv6 not available');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBADDITIONALSERVICE4' => 'Static IPv6 used.'));
								continue;
							}
							/**************************************************
							 * ipv6 can be used
							 **************************************************/
							log_message('info', $i.'|mark new ipv6 as used: '.$subscriber['RBADDITIONALSERVICE4']);
							$this->ipaddress->markV6AsUsed($subscriber['RBADDITIONALSERVICE4'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$ipV6Set = true;
							/**************************************************
							 * free up old ipv6 address
							 **************************************************/
							if (!is_null($subscriberOld['RBADDITIONALSERVICE4'])) {
								log_message('info', $i.'|free up old ipv6: '.$subscriberOld['RBADDITIONALSERVICE4']);
								$this->ipaddress->freeUpV6($subscriberOld['RBADDITIONALSERVICE4'],
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
						}
					/**************************************************
					 * no new ipv6 address provided (will not be changed)
					 **************************************************/
					} else {
						$subscriber['RBADDITIONALSERVICE4'] = $subscriberOld['RBADDITIONALSERVICE4'];
					}
					/**************************************************
					 * add reply items
					 **************************************************/
					$subscriber['CUSTOMERREPLYITEM'] = $this->subscribermain->generateCustomerReplyItemValue(
						$subscriber['RBADDITIONALSERVICE4'], $subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC']);
					/**************************************************
					 * update activated date column
					 **************************************************/
					if (!is_null($subscriber['CUSTOMERSTATUS'])) {
						if ($subscriberOld['CUSTOMERSTATUS'] != $subscriber['CUSTOMERSTATUS']) {
							if ($subscriberOld['CUSTOMERSTATUS'] == 'D' && $subscriber['CUSTOMERSTATUS'] == 'A') {
								$subscriber['RBACTIVATEDDATE'] = date('Y-m-d H:i:s', time());
							}
							$subscriber['RBCHANGESTATUSDATE'] = date('Y-m-d H:i:s', time());
						}
					}
					log_message('info', $i.'|CBND: '.json_encode($subscriber));
					/**************************************************
					 * uncomment the next line if RBENABLED is fixed to 'No' and FAP to 'N'
					 * FAP is dependent on the value of $subscriber['RBENABLED']: 'No' => 'N', 'Yes' => 'Y'
					 **************************************************/
					// $subscriber['RBENABLED'] = 'No';
					/**************************************************
					 * update account
					 **************************************************/
					log_message('debug', '@processBulkUpdateSubscribers|subscriber:'.$subscriber['USERNAME'].'|RBENABLED:'.$subscriber['RBENABLED']);
					$result = $this->subscribermain->update($subscriber['USER_IDENTITY'], $subscriber,
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					/**************************************************
					 * update failed
					 **************************************************/
					if (!$result) {
						log_message('info', $i.'|subscriber not updated');
						/**************************************************
						 * revert changes made to ip/net address, if any
						 **************************************************/
						if ($ipSet) {
							$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if ($netSet) {
							$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if ($this->useIPv6 && $ipV6Set) {
							$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notUpdatedInvalidDataRowNumbers[] = $validRowNumbers[$i];
						$notUpdatedInvalidData[] = array('rowdata' => $dataToUpdate[$i], 'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm), 'errors' => array('ALL' => 'Failed to update.'));
						continue;
					}
					/**************************************************
					 * update account in npm, if enabled (if not, $npmResult['result'] will default to true)
					 **************************************************/
					if ($this->ENABLENPM) {
						$inNPM = $this->subscribermain->npmFetchXML($subscriber['USERNAME'],
							$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
						log_message('info', 'in npm:'.json_encode($inNPM));
						if ($inNPM['found']) {
							$npmResult = $this->subscribermain->npmUpdateXML($subscriber['USERNAME'], $subscriber['PASSWORD'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'Y' : 'N',
								str_replace('~', '-', $subscriber['RBACCOUNTPLAN']), $subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC'], 'N',
								$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
						} else {
							$npmResult = $this->subscribermain->npmCreateXML($subscriber['USERNAME'], $subscriber['PASSWORD'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'Y' : 'N',
								time(), str_replace('~', '-', $subscriber['RBACCOUNTPLAN']), $subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC'], 'N',
								$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
						}
						log_message('info', 'result: '.json_encode($npmResult));
					} else {
						$npmResult['result'] = true;
					}
					/**************************************************
					 * npm update/insert failed, revert subscriber, ip/net changes
					 **************************************************/
					if (!$npmResult['result']) {
						log_message('info', $i.'|npm error');
						$this->subscribermain->update($subscriber['USER_IDENTITY'], $subscriberOld,
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						if ($ipSet) {
							$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if ($netSet) {
							$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if ($this->useIPv6 && $ipV6Set) {
							$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notUpdatedNPMErrorRowNumbers[] = $validRowNumbers[$i];
						$notUpdatedNPMError[] = array('rowdata' => $dataToUpdate[$i], 'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm), 'errors' => $npmResult['error']);
						continue;
					}
					/**************************************************
					 * if account is to be deactivated, find sessions and delete them
					 **************************************************/
					if ($subscriber['CUSTOMERSTATUS'] == 'InActive') {
						$uParts = explode('@', $subscriber['USERNAME']);
						$this->load->model('onlinesession');
						$sessions = $this->onlinesession->getSessions2($uParts[0], $uParts[1], $this->USESESSIONTABLE2,
							$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
							$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
						if ($sessions['status'] && isset($sessions['data'])) {
							for ($h = 0; $h < count($sessions['data']); $h++) {
								$sess = $sessions['data'][$h];
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
					/**************************************************
					 * delete sessions if plan (RBACCOUNTPLAN) is new
					 **************************************************/
					$newRP = trim($subscriber['RBACCOUNTPLAN']);
					$newRP = str_replace('~', '-', $newRP);
					$oldRP = trim($subscriberOld['RBACCOUNTPLAN']);
					$oldRP = str_replace('~', '-', $oldRP);
					if ($newRP != $oldRP) {
						$cnParts = explode('@', $subscriber['USER_IDENTITY']);
						$sessions = $this->onlinesession->getSessions2($cnParts[0], $cnParts[1], $this->USESESSIONTABLE2,
							$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
							$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
						if ($sessions['status'] && isset($sessions['data'])) {
							for ($g = 0; $g < count($sessions['data']); $g++) {
								$sess = $sessions['data'][$g];
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
					/**************************************************
					 * update contents of RADIUSPOLICY at TBLCUSTOMER
					 * RADIUSPOLICY column at TBLCUSTOMER is no longer used (May 2017)
					 **************************************************/
					// $this->subscribermain->clearRadiuspolicy($subscriber['USERNAME'], $this->SESSIONUSERNAME2, $this->SESSIONPASSWORD2, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// $this->subscribermain->updateRadiuspolicy($subscriber['USERNAME'], str_replace('~', '-', $subscriber['RADIUSPOLICY']),
					// 	$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					/**************************************************
					 * RM api
					 **************************************************/
					// Remove RM Dependency 6/25/2019
					// $rmEdited = true;
					// $rmClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
					/**************************************************
					 * if branch for handling old rm api
					 * - as of nov 18, 2016 this is no longer used
					 * - remove this later (including closing brace before $rmEdited if block)
					 **************************************************/
					/*
					if ($this->RMAPIHOST == '10.244.4.130' || $this->RMAPIHOST == '10.244.4.131') {
						try {
							$rmSubsUsername = array('key' => 'USERNAME' , 'value' => $subscriber['USERNAME']);
							$rmSubsIdentity = array('key' => 'SUBSCRIBERIDENTITY', 'value' => $subscriber['USERNAME']);
							$rmSubsCui = array('key' => 'CUI', 'value' => $subscriber['USERNAME']);
							$rmSubsStatus = array('key' => 'SUBSCRIBERSTATUS', 'value' => 'Active');
							$rmSubsArea = array('key' => 'AREA', 'value' => $subscriber['CUSTOMERSTATUS']);
							$rmSubsPackage = array('key' => 'SUBSCRIBERPACKAGE', 'value' => str_replace('-', '~', $subscriber['RBACCOUNTPLAN']));
							$customerTypeForRM = '';
							if ($subscriber['CUSTOMERTYPE'] == 'Residential') {
								$customerTypeForRM = 'RESS';
							} else if ($subscriber['CUSTOMERTYPE'] == 'Business') {
								$customerTypeForRM = 'BUSS';
							} else {
								$customerTypeForRM = 'RESS';
							}
							$rmSubsCustomertype = array('key' => 'CUSTOMERTYPE', 'value' => $customerTypeForRM);
							$fapForRM = '';
							if ($subscriber['RBENABLED'] == 'Yes') {
								$fapForRM = 'Y';
							} else if ($subscriber['RBENABLED'] == 'No') {
								$fapForRM = 'N';
							} else {
								$fapForRM = 'N';
							}
							$rmSubsFap = array('key' => 'FAP', 'value' => $fapForRM);
							/**************************************************
							 * try updating
							 **************************************************
							$rmSubsPassword = array('key' => 'PASSWORD', 'value' => '');
							$rmParam = array($rmSubsPassword, $rmSubsStatus, $rmSubsArea, $rmSubsPackage, $rmSubsCustomertype, $rmSubsFap);
							$updateResult = $this->rm->updateAccount($subscriber['USERNAME'], $rmParam, $rmClient);
							if (intval($updateResult['responseCode']) == 200) {
								log_message('debug', 'bulkmodify|updated @ RM: '.$subscriber['USERNAME']);
							} else if (intval($updateResult['responseCode']) == 404) {
								log_message('debug', 'bulkmodify|'.$subscriber['USERNAME'].' not found, insert');
								$rmParam = array($rmSubsUsername, $rmSubsIdentity, $rmSubsCui, $rmSubsStatus, $rmSubsArea, $rmSubsPackage, $rmSubsCustomertype, $rmSubsFap);
								$insertResult = $this->rm->createAccount($rmParam, $rmClient);
								if (intval($insertResult['responseCode']) == 200) {
									log_message('debug', 'bulkmodify|inserted @ RM: '.$subscriber['USERNAME']);
								} else {
									log_message('debug', 'bulkmodify|failed to update @ RM: '.$subscriber['USERNAME'].'|'.$insertResult['responseMessage']);
									$rmEdited = false;
								}
							} else {
								log_message('debug', 'bulkmodify|failed to update @ RM: '.$subscriber['USERNAME'].'|'.$updateResult['responseMessage']);
								$rmEdited = false;
							}
						} catch (Exception $e) {
							log_message('debug', 'error @ RM:'.json_encode($e));
							$rmEdited = false;
						}
					} else if ($this->RMAPIHOST == '10.81.54.34' || $this->RMAPIHOST == '10.81.54.35') {
					*/
					// try {
					// 	$rmUsername = $subscriber['USERNAME'];
					// 	// $rmPlan = $subscriber['RADIUSPOLICY'];
					// 	$rmPlan = $subscriber['RBACCOUNTPLAN'];
					// 	// $rmStatus = strtoupper($subscriber['CUSTOMERSTATUS']);
					// 	$rmStatus = 'ACTIVE';
					// 	if (strtolower($subscriber['CUSTOMERTYPE']) == 'residential') {
					// 		$rmCustomerType = 'RESS';
					// 	} else if (strtolower($subscriber['CUSTOMERTYPE']) == 'business') {
					// 		$rmCustomerType = 'BUSS';
					// 	} else {
					// 		$rmCustomerType = 'RESS';
					// 	}
					// 	$fapForRM = '';
					// 	if ($subscriber['RBENABLED'] == 'Yes') {
					// 		$fapForRM = 'Y';
					// 	} else if ($subscriber['RBENABLED'] == 'No') {
					// 		$fapForRM = 'N';
					// 	} else {
					// 		$fapForRM = 'N';
					// 	}
					// 	/**************************************************
					// 	 * try updating
					// 	 **************************************************/
					// 	$nodes = array('PARAM3' => $fapForRM, 'AREA' => $subscriber['CUSTOMERSTATUS']);
					// 	$updateResult = $this->rm->wsUpdateSubscriberProfile($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
					// 	if (intval($updateResult['responseCode']) == 200) {
					// 		log_message('debug', 'bulkmodify|updated @ RM: '.$subscriber['USERNAME']);
					// 	} else if (intval($updateResult['responseCode']) == 404) {
					// 		log_message('debug', 'bulkmodify|'.$subscriber['USERNAME'].' not found, insert');
					// 		$insertResult = $this->rm->wsAddSubscriber($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
					// 		if (intval($insertResult['responseCode']) == 200) {
					// 			log_message('debug', 'bulkmodify|inserted @ RM: '.$subscriber['USERNAME']);
					// 		} else {
					// 			log_message('debug', 'bulkmodify|failed to update @ RM: '.$subscriber['USERNAME'].'|'.$insertResult['responseMessage']);
					// 			$rmEdited = false;
					// 		}
					// 	} else {
					// 		log_message('debug', 'bulkmodify|failed to update @ RM: '.$subscriber['USERNAME'].'|'.$updateResult['responseMessage']);
					// 		$rmEdited = false;
					// 	}
					// } catch (Exception $e) {
					// 	log_message('debug', 'error @ RM:'.json_encode($e));
					// 	$rmEdited = false;
					// }
					// /*
					// }
					// */
					// if (!$rmEdited) {
					// 	$this->subscribermain->update($subscriber['USER_IDENTITY'], $subscriberOld,
					// 		$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// 	if ($ipSet) {
					// 		$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
					// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// 		$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
					// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// 	}
					// 	if ($netSet) {
					// 		$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
					// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// 		$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
					// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// 	}
					// 	if ($this->useIPv6 && $ipV6Set) {
					// 		$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
					// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// 		$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
					// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// 	}
					// 	$invalidRowNumbers[] = $validRowNumbers[$i];
					// 	$notUpdatedNPMErrorRowNumbers[] = $validRowNumbers[$i];
					// 	$notUpdatedNPMError[] = array('rowdata' => $dataToUpdate[$i], 'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
					// 		'errors' => isset($rmResult['responseMessage']) && !is_null($rmResult['responseMessage']) ? $rmResult['responseMessage'] : 'error at RM');
					// 	continue;
					// }
					/**************************************************
					 * /RM
					 **************************************************/
					$updatedRows[] = $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm); //$dataToUpdate[$i];
					/**************************************************
					 * mark updated columns
					 **************************************************/
					$mark = [];
					if (trim($dataToUpdate[$i][1]) != '') {
						$mark['PASSWORD'] = true;
					}
					if (trim($dataToUpdate[$i][2]) != '') {
						$mark['CUSTOMERTYPE'] = true;
					}
					if (trim($dataToUpdate[$i][3]) != '') {
						$mark['CUSTOMERSTATUS'] = true;
					}
					if (trim($dataToUpdate[$i][4]) != '') {
						$mark['RBORDERNUMBER'] = true;
					}
					if (trim($dataToUpdate[$i][5]) != '') {
						$mark['RBCUSTOMERNAME'] = true;
					}
					if (trim($dataToUpdate[$i][6]) != '') {
						$mark['RBSERVICENUMBER'] = true;
					}
					if (trim($dataToUpdate[$i][7]) != '') {
						$mark['RBENABLED'] = true;
					}
					if (trim($dataToUpdate[$i][8]) != '') {
						$mark['RBACCOUNTPLAN'] = true;
					}
					if ($this->useIPv6) {
						if (trim($dataToUpdate[$i][9]) != '') {
							$mark['RBADDITIONALSERVICE4'] = true;
						}
						if (trim($dataToUpdate[$i][10]) != '') {
							$mark['RBIPADDRESS'] = true;
						}
						if (trim($dataToUpdate[$i][11]) != '') {
							$mark['RBMULTISTATIC'] = true;
						}
					} else {
						if (trim($dataToUpdate[$i][9]) != '') {
							$mark['RBIPADDRESS'] = true;
						}
						if (trim($dataToUpdate[$i][10]) != '') {
							$mark['RBMULTISTATIC'] = true;
						}
					}
					$updatedMarks[] = $mark;
					$updatedRowNumbers[] = $validRowNumbers[$i];
					if ($sysuser != $this->SUPERUSER) {
						$this->load->model('subscriberaudittrail');
						$this->subscriberaudittrail->logSubscriberModification($subscriber, $subscriberOld, $sysuser, $sysuserIP, $now, true);
					}
				}
				/*
				for ($i = 0; $i < count($updatedRows); $i++) {
					$row = $updatedRows[$i];
					if ($row[3] == 'D') {
						$updatedRows[$i][9] = null;
						$updatedRows[$i][10] = null;
					}
				}
				*/
				/**************************************************
				 * handle accounts to be deleted
				 **************************************************/
				$this->load->model('subscriberaudittrail');
				$thisPdFilename = date('YmdHis', time()).'_forPd.txt';
				$appendCount = 0;
				$forDeletionCount = count($forDeletion);
				$this->load->library('rm');
				for ($i = 0; $i < $forDeletionCount; $i++) {
					log_message('info', '_________________________________delete:');
					log_message('info', json_encode($forDeletion[$i]));
					$this->subscribermain->delete($forDeletion[$i]['USER_IDENTITY'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if ($this->ENABLENPM) {
						$this->subscribermain->npmRemoveSubscriber($forDeletion[$i]['USER_IDENTITY'],
							$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
					}
					/**************************************************
					 * delete session if account has one/more
					 **************************************************/
					$this->load->model('onlinesession');
					$parts = explode('@', $forDeletion[$i]['USER_IDENTITY']);
					$sessions = $this->onlinesession->getSessions2($parts[0], $parts[1], $this->USESESSIONTABLE2,
						$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
						$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
					if ($sessions['status'] && isset($sessions['data'])) {
						for ($ii = 0; $ii < count($sessions['data']); $ii++) {
							$sess = $sessions['data'][$ii];
							$deleted = $this->onlinesession->requestDisconnect($sess['USER_NAME'], $sess['NAS_IP_ADDRESS'], $sess['ACCT_SESSION_ID'], $this->USESESSIONTABLE2,
								$this->DELETESESSIONAPIHOST, $this->DELETESESSIONAPIPORT, $this->DELETESESSIONAPISTUB,
								$this->DELETESESSIONAPIHOST2, $this->DELETESESSIONAPIPORT2, $this->DELETESESSIONAPISTUB2,
								$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
								$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2,
								$this->TBLMCOREHOST, $this->TBLMCOREPORT, $this->TBLMCORESCHEMA, $this->TBLMCOREUSERNAME, $this->TBLMCOREPASSWORD,
								$this->TBLMCOREHOST2, $this->TBLMCOREPORT2, $this->TBLMCORESCHEMA2, $this->TBLMCOREUSERNAME2, $this->TBLMCOREPASSWORD2);
						}
					}
					/**************************************************
					 * RM api (delete account)
					 **************************************************/
					$rmClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
					/**************************************************
					 * if branch for handling old rm api
					 * - as of nov 18, 2016 this is no longer used
					 * - remove this later (including closing brace before /RM marker)
					 **************************************************/
					/*
					if ($this->RMAPIHOST == '10.244.4.130' || $this->RMAPIHOST == '10.244.4.131') {
						$rmResult = $this->rm->accountExists($forDeletion[$i]['USER_IDENTITY'], $rmClient);
						if (!$rmResult['exists']) {
							log_message('debug', 'not found @ RM: '.$forDeletion[$i]['USER_IDENTITY']);
						} else {
							$rmResult = $this->rm->deleteAccount($forDeletion[$i]['USER_IDENTITY'], $rmClient);
							if (intval($rmResult['responseCode']) == 200) {
								log_message('debug', 'deleted @ RM: '.$forDeletion[$i]['USER_IDENTITY']);
							} else {
								log_message('debug', 'failed to delete @ RM: '.$forDeletion[$i]['USER_IDENTITY'].' | '.$rmResult['responseMessage']);
							}
						}
					} else if ($this->RMAPIHOST == '10.81.54.34' || $this->RMAPIHOST == '10.81.54.35') {
					*/
					$deleteResult = $this->rm->wsDeleteSubscriberProfile($rmClient, $forDeletion[$i]['USER_IDENTITY']);
					if (intval($deleteResult['responseCode']) == 200 || intval($deleteResult['responseCode']) == 404) {
						log_message('debug', 'deleted @ RM: '.$forDeletion[$i]['USER_IDENTITY']);
						$purgeResult = $this->rm->wsPurgeSubscriber($rmClient, $forDeletion[$i]['USER_IDENTITY']);
						if (intval($purgeResult['responseCode']) == 200) {
							log_message('debug', 'purged @ RM: '.$forDeletion[$i]['USER_IDENTITY']);
						} else {
							log_message('debug', 'failed to purge @ RM: '.$forDeletion[$i]['USER_IDENTITY'].' | '.$purgeResult['responseMessage']);
						}
					} else {
						log_message('debug', 'failed to delete @ RM: '.$forDeletion[$i]['USER_IDENTITY'].' | '.$deleteResult['responseMessage']);
					}
					/*
					}
					*/
					/**************************************************
					 * /RM
					 **************************************************/
					if ($sysuser != $this->SUPERUSER) {
						$this->subscriberaudittrail->logSubscriberDeletion($forDeletion[$i], $sysuser, $sysuserIP, $now);
					}
					if (!is_null($forDeletion[$i]['RBIPADDRESS'])) {
						$this->ipaddress->freeUp($forDeletion[$i]['RBIPADDRESS'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
					if (!is_null($forDeletion[$i]['RBMULTISTATIC'])) {
						$this->netaddress->freeUp($forDeletion[$i]['RBMULTISTATIC'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
					if ($this->useIPv6 && !is_null($forDeletion[$i]['RBADDITIONALSERVICE4'])) {
						$this->ipaddress->freeUpV6($forDeletion[$i]['RBADDITIONALSERVICE4'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
				}
				// log_message('debug', 'PD: ['.($forDeletionCount == $appendCount ? 'all accounts appended' : 'failed to append '.($forDeletionCount - $appendCount).' accounts').'] '.$theFilename);
				/**************************************************
				 * write deleted accounts to file
				 **************************************************/
				if ($forDeletionCount != 0) {
					$this->util->writeToDeletedSubscriberFile($forDeletion, time());
					$deletedXls = $this->util->writeDeletedSubscriberXlsForExtract($forDeletion, 'update');
				}
				log_message('info', '__________________________________________________________________________________');
				log_message('info', 'UPDATED: '.json_encode($updatedRows));
				log_message('info', 'USERNAME DNE: '.json_encode($notUpdatedUsernameDNE));
				log_message('info', 'INVALID DATA: '.json_encode($notUpdatedInvalidData));
				log_message('info', 'IPNET ERROR: '.json_encode($notUpdatedIPNetError));
				log_message('info', 'NPM ERROR: '.json_encode($notUpdatedNPMError));
				log_message('info', 'NOT CREATED (ROWS): '.json_encode($invalidRowNumbers));
				$data = array(
					'step' => 'update',
					'proceed' => $checks['go'],
					'error' => $checks['msg'],
					'path' => $path,
					'realm' => $realm,
					'updated' => $updatedRows,
					'updatedMarks' => $updatedMarks,
					'usernameDNE' => $notUpdatedUsernameDNE,
					'invalidData' => $notUpdatedInvalidData,
					'ipNetError' => $notUpdatedIPNetError,
					'npmError' => $notUpdatedNPMError,
					'invalidRowNumbers' => $invalidRowNumbers,
					'updatedRowNumbers' => $updatedRowNumbers,
					'usernameDNERowNumbers' => $notUpdatedUsernameDNERowNumbers,
					'invalidDataRowNumbers' => $notUpdatedInvalidDataRowNumbers,
					'ipNetErrorRowNumbers' => $notUpdatedIPNetErrorRowNumbers,
					'npmErrorRowNumbers' => $notUpdatedNPMErrorRowNumbers,
					'deleted' => $forDeletion,
					'deletedFile' => isset($deletedXls) ? $deletedXls : '');
				$data['useIPv6'] = $this->useIPv6;
				$this->load->view('bulk_modify_users', $data);
			/**************************************************
			 * download step
			 **************************************************/
			} else if ($step == 'download') {
				log_message('info', '__________@extract');
				$path = $this->input->post('path');
				$set = trim($this->input->post('set'));
				$realm = $this->input->post('realm');
				$setdata = unserialize($this->input->post('setdata'));
				$this->load->model('util');
				if ($set == 'deleted') {
					$file = $this->input->post('file');
					$this->util->fetchFile($file);
				} else {
					if ($this->useIPv6) {
						$this->util->writeBulkModifyOutputV2($path, $setdata, $set, $realm);
					} else {
						$this->util->writeBulkModifyOutput($path, $setdata, $set, $realm);
					}
				}
			}
		/**************************************************
		 * subscribers/processBulkUpdateSubscribers accessed via url
		 **************************************************/
		} else {
			//get realms
			$this->load->model('realm');
			$realms = $this->realm->fetchAllNamesOnly();
			$data = array(
				'realms' => $realms);
			if ($portal == 'service') {
				$data['realm'] = $realm;
				$data['disableRealm'] = true;
			}
			$data['proceed'] = $checks['go'];
			$data['error'] = $checks['msg'];
			$data['useIPv6'] = $this->useIPv6;
			$this->load->view('bulk_modify_users', $data);
		}
	}


	/*****************************************************
		* Bulk Modify (Admin)

	******************************************************/

	public function processBulkUpdateSubscribersAdmin($step = null) {
		$this->redirectIfNoAccess('Modify Account (Admin)', 'subscribers/processBulkUpdateSubscribersAdmin');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@processBulkUpdateSubscribersAdmin|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@processBulkUpdateSubscribers|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		/**************************************************
		 * subscribers/processBulkUpdateSubscribers accessed via form
		 **************************************************/
		if (is_null($step)) {
			$step = $this->input->post('step');
			$realm = '';
			/**************************************************
			 * upload step
			 **************************************************/
			if ($step == 'upload') {
				//get realms
				$this->load->model('realm');
				$realms = $this->realm->fetchAllNamesOnly();
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx|application/vnd.ms-office|\"application/vnd.ms-office\"|"application/vnd.ms-office"';
				$config['max_size'] = '50000';
				$config['overwrite'] = true;
				$this->load->library('upload', $config);
				/**************************************************
				 * upload failed
				 **************************************************/
				if (!$this->upload->do_upload('file')) {
					$data['step'] = 'upload';
					$data['error'] = 'Upload failed: '.$this->upload->display_errors();
					$data['realm'] = $this->input->post('realm');
					$data['realms'] = $realms;
					$data['proceed'] = $checks['go'];
					if ($portal == 'service') {
						$data['realm'] = $realm;
						$data['disableRealm'] = true;
					}
					$data['useIPv6'] = $this->useIPv6;
					$this->load->view('bulk_modify_users_admin', $data);
				/**************************************************
				 * upload ok
				 **************************************************/
				} else {
					$realm = $this->input->post('realm');
					$this->load->model('util');
					$uploaded = $this->upload->data();
					log_message('info', 'UPLOADED FILE: '.json_encode($uploaded));
					/**************************************************
					 * check uploaded xls format (header check);
					 **************************************************/
					if ($this->useIPv6) {
						$valid = $this->util->verifyBulkUpdateFormatV2($uploaded['full_path']);
					} else {
						$valid = $this->util->verifyBulkUpdateFormat($uploaded['full_path']);
					}
					/**************************************************
					 * invalid xls file (some headers did not match)
					 **************************************************/
					if (!$valid) {
						$data['step'] = 'upload';
						$data['error'] = 'Invalid file contents: "'.$uploaded['client_name'].'"';
						$data['realm'] = $realm;
						$data['realms'] = $realms;
						$data['proceed'] = $checks['go'];
						if ($portal == 'service') {
							$data['realm'] = $realm;
							$data['disableRealm'] = true;
						}
						$data['useIPv6'] = $this->useIPv6;
						$this->load->view('bulk_modify_users_admin', $data);
					/**************************************************
					 * valid xls file
					 **************************************************/
					} else {
    					$totalRowCount = $this->util->countRows($uploaded['full_path']);
						log_message('info', 'fileRowCount:'.$totalRowCount);
						/**************************************************
						 * check if xls row count exceeds maximum
						 **************************************************/
						if ($totalRowCount > $this->xlsRowLimit) {
							$data['step'] = 'upload';
							$data['error'] = 'Due to memory constraints, please limit files to less than 5000 rows.';
							$data['realm'] = $realm;
							$data['realms'] = $realms;
							$data['proceed'] = $checks['go'];
							if ($portal == 'service') {
								$data['realm'] = $realm;
								$data['disableRealm'] = true;
							}
							$data['useIPv6'] = $this->useIPv6;
							$this->load->view('bulk_modify_users_admin', $data);
							return;
						}
						/**************************************************
						 * check xls row contents: basically only checks if a username is provided
						 **************************************************/
						$rows = $this->util->verifyBulkUpdateData($uploaded['full_path'], $realm);
						$this->load->model('subscribermain');
						log_message('info', 'ROWS: '.json_encode($rows));
						$validRowsTmp = [];
						$validMarks = [];
						$toDeleteTmp = [];
						$validRows = [];
						$toDelete = [];
						/**************************************************
						 * separate rows to be deleted (STATUS = 'T')
						 **************************************************/
						for ($i = 0; $i < count($rows['valid']); $i++) {
							if ($rows['valid'][$i][3] == 'T') {
								$toDeleteTmp[] = $rows['valid'][$i];
							} else {
								$validRowsTmp[] = $rows['valid'][$i];
							}
						}
						/**************************************************
						 * preparation for update (pre-confirm)
						 * if no data is provided on the not required column, current data will be placed instead
						 * $mark array indicates which info will be updated
						 **************************************************/
						for ($i = 0; $i < count($validRowsTmp); $i++) {
							$row = $validRowsTmp[$i];
							$subs = $this->subscribermain->findByUserIdentity($row[0]);
							log_message('info', '@SUBS:'.$i.'|'.json_encode($subs));
							$mark = [];
							if (trim($row[1]) == '') {
								$row[1] = $subs === false ? '' : $subs['PASSWORD'];
							} else {
								$mark['PASSWORD'] = true;
							}
							// Remove Dependencies 5/20/19
							// if (trim($row[2]) == '') {
							// 	$val = '';
							// 	if ($subs !== false) {
							// 		if ($subs['CUSTOMERTYPE'] == 'Residential' || $subs['CUSTOMERTYPE'] == 'R') {
							// 			$val = 'R';
							// 		} else if ($subs['CUSTOMERTYPE'] == 'Business' || $subs['CUSTOMERTYPE'] == 'B') {
							// 			$val = 'B';
							// 		}
							// 	}
							// 	$row[2] = $subs === false ? '' : $val;
							// } else {
							// 	$mark['CUSTOMERTYPE'] = true;
							// }
							if (trim($row[3]) == '') {
								$row[3] = $subs === false ? '' : ($subs['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D');
							} else {
								$mark['CUSTOMERSTATUS'] = true;
							}
							if (trim($row[4]) == '') {
								$row[4] = $subs === false ? '' : (is_null($subs['RBORDERNUMBER']) ? '' : $subs['RBORDERNUMBER']);
							} else {
								$mark['RBORDERNUMBER'] = true;
							}
							if (trim($row[5]) == '') {
								$row[5] = $subs === false ? '' : $subs['RBCUSTOMERNAME'];
							} else {
								$mark['RBCUSTOMERNAME'] = true;
							}
							if (trim($row[6]) == '') {
								$row[6] = $subs === false ? '' : $subs['RBSERVICENUMBER'];
							} else {
								$mark['RBSERVICENUMBER'] = true;
							}
							// if (trim($row[7]) == '') {
							// 	$val = '';
							// 	if ($subs !== false) {
							// 		if ($subs['RBENABLED'] == 'Yes' || $subs['RBENABLED'] == 'Y') {
							// 			$val = 'Y';
							// 		} else if ($subs['RBENABLED'] == 'No' || $subs['RBENABLED'] == 'N') {
							// 			$val = 'N';
							// 		}
							// 	}
							// 	$row[7] = $subs === false ? '' : $val;
							// } else {
							// 	$mark['RBENABLED'] = true;
							// }
							// if (trim($row[8]) == '') {
							// 	$row[8] = $subs === false ? '' : $subs['RBACCOUNTPLAN'];
							// } else {
							// 	$mark['RBACCOUNTPLAN'] = true;
							// }
							if ($this->useIPv6) {
								if (trim($row[9]) == '') {
									$row[9] = $subs === false ? '' : is_null($subs['RBADDITIONALSERVICE4']) ? '' : $subs['RBADDITIONALSERVICE4'];
								} else {
									$mark['RBADDITIONALSERVICE4'] = true;
								}
								if (trim($row[10]) == '') {
									$row[10] = $subs === false ? '' : is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS'];
								} else {
									$mark['RBIPADDRESS'] = true;
								}
								if (trim($row[11]) == '') {
									$row[11] = $subs === '' ? '' : is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC'];
								} else {
									$mark['RBMULTISTATIC'] = true;
								}
							} else {
								if (trim($row[9]) == '') {
									$row[9] = $subs === false ? '' : is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS'];
								} else {
									$mark['RBIPADDRESS'] = true;
								}
								if (trim($row[10]) == '') {
									$row[10] = $subs === '' ? '' : is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC'];
								} else {
									$mark['RBMULTISTATIC'] = true;
								}
							}
							$validRows[] = $row;
							$validMarks[] = $mark;
						}
						/**************************************************
						 * get data of accounts to be deleted
						 **************************************************/
						for ($i = 0; $i < count($toDeleteTmp); $i++) {
							$row = $toDeleteTmp[$i];
							$subs = $this->subscribermain->findByUserIdentity($row[0]);
							if ($subs !== false) {
								if (trim($row[1]) == '') {
									$row[1] = $subs['PASSWORD'];
								}
								// if (trim($row[2]) == '') {
								// 	$val = '';
								// 	if ($subs['CUSTOMERTYPE'] == 'Residential' || $subs['CUSTOMERTYPE'] == 'R') {
								// 		$val = 'R';
								// 	} else if ($subs['CUSTOMERTYPE'] == 'Business' || $subs['CUSTOMERTYPE'] == 'B') {
								// 		$val = 'B';
								// 	}
								// 	$row[2] = $val;
								// }
								if (trim($row[3]) == '') {
									$row[3] = $subs['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D';
								}
								if (trim($row[4]) == '') {
									$row[4] = $subs['RBORDERNUMBER'];
								}
								if (trim($row[5]) == '') {
									$row[5] = $subs['RBCUSTOMERNAME'];
								}
								if (trim($row[6]) == '') {
									$row[6] = $subs['RBSERVICENUMBER'];
								}
								// if (trim($row[7]) == '') {
								// 	$val = '';
								// 	if ($subs['RBENABLED'] == 'Yes' || $subs['RBENABLED'] == 'Y') {
								// 		$val = 'Y';
								// 	} else if ($subs['RBENABLED'] == 'No' || $subs['RBENABLED'] == 'N') {
								// 		$val = 'N';
								// 	}
								// 	$row[7] = $val;
								// }
								// if (trim($row[8]) == '') {
								// 	$row[8] = $subs['RBACCOUNTPLAN'];
								// }
								if ($this->useIPv6) {
									if (trim($row[9]) == '') {
										$row[9] = is_null($subs['RBADDITIONALSERVICE4']) ? '' : $subs['RBADDITIONALSERVICE4'];
									}
									if (trim($row[10]) == '') {
										$row[10] = is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS'];
									}
									if (trim($row[11]) == '') {
										$row[11] = is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC'];
									}
								} else {
									if (trim($row[9]) == '') {
										$row[9] = is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS'];
									}
									if (trim($row[10]) == '') {
										$row[10] = is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC'];
									}
								}
								$toDelete[] = $row;
							}
    					}

	    				$data = array(
	    					'step' => 'confirm',
	    					'proceed' => $checks['go'],
							'error' => $checks['msg'],
	    					'path' => $uploaded['full_path'],
	    					'realm' => $realm,
	    					'valid' => $validRows,
	    					'validMarks' => $validMarks,
	    					'validRowNumbers' => $rows['validRowNumbers'],
	    					'invalid' => $rows['invalid'],
	    					'invalidRowNumbers' => $rows['invalidRowNumbers'],
	    					'toDelete' => $toDelete);
	    				$data['useIPv6'] = $this->useIPv6;
	    				$this->load->view('bulk_modify_users_admin', $data);
    				}
    			}
    		/**************************************************
			 * update step
			 **************************************************/
			} else if ($step == 'update') {
				if (!$checks['go']) {
					redirect('subscribers/processBulkUpdateSubscribersAdmin/upload');
				}
				$now = date('Y-m-d H:i:s', time());
				$realm = $this->input->post('realm');
				$path = $this->input->post('path');
				$validRowNumbers = unserialize($this->input->post('validRowNumbers'));
				$invalidRowNumbers = unserialize($this->input->post('invalidRowNumbers'));
				log_message('info', 'VALID ROWS: '.json_encode($validRowNumbers));
				log_message('info', 'INVALID ROWS: '.json_encode($invalidRowNumbers));
				$updatedRows = [];
				$updatedMarks = [];
				$notUpdatedUsernameDNE = [];
				$notUpdatedInvalidData = [];
				$notUpdatedIPNetError = [];
				$notUpdatedNPMError = [];
				$forDeletion = [];
				$forDeletionRowNumbers = [];
				$updatedRowNumbers = [];
				$notUpdatedUsernameDNERowNumbers = [];
				$notUpdatedInvalidDataRowNumbers = [];
				$notUpdatedIPNetErrorRowNumbers = [];
				$notUpdatedNPMErrorRowNumbers = [];
				$this->load->model('util');
				$this->load->model('subscribermain');
				$this->load->model('ipaddress');
				$this->load->model('netaddress');
				$this->load->model('services');
				$this->load->model('onlinesession');
				$this->load->model('cabinet');
				if ($this->useIPv6) {
					$dataToUpdate = $this->util->extractRowsToUpdateV2($path, $validRowNumbers);
				} else {
					$dataToUpdate = $this->util->extractRowsToUpdate($path, $validRowNumbers);
				}
				log_message('info', 'TO UPDATE: '.json_encode($dataToUpdate));

				/**************************************************
				 * get these from session variables
				 **************************************************/
				$sysuser = $this->session->userdata('username');
				$sysuserIP = $this->session->userdata('ip_address');
				// $this->load->library('rm');
				$dataToUpdateCount = count($dataToUpdate);
				for ($i = 0; $i < $dataToUpdateCount; $i++ ) {
					$dataToUpdate[$i][2] = trim($dataToUpdate[$i][2]);
					$dataToUpdate[$i][7] = trim($dataToUpdate[$i][7]);
					$dataToUpdate[$i][8] = trim($dataToUpdate[$i][8]);
					$dataToUpdate[$i][9] = trim($dataToUpdate[$i][9]);
					$dataToUpdate[$i][10] = trim($dataToUpdate[$i][10]);
					$dataToUpdate[$i][11] = trim($dataToUpdate[$i][11]);
					/**************************************************
					 * convert xls row to $subscriber array
					 **************************************************/
					if ($this->useIPv6) {
						$subscriber = $this->subscribermain->rowDataToSubscriberUpdateArrayV2($dataToUpdate[$i], $realm, $sysuser);
					} else {
						$subscriber = $this->subscribermain->rowDataToSubscriberUpdateArray($dataToUpdate[$i], $realm, $sysuser);
					}
					log_message('info', $i.'|NEW : '.json_encode($subscriber));
					/**************************************************
					 * check if account exists, skip if not found
					 **************************************************/
					$exists = $this->subscribermain->subscriberExists($subscriber['USER_IDENTITY'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if (!$exists) {
						log_message('info', $i.'|subscriber does not exist');
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notUpdatedUsernameDNERowNumbers[] = $validRowNumbers[$i];
						$notUpdatedUsernameDNE[] = $dataToUpdate[$i];
						continue;
					}
					/**************************************************
					 * account found, get current data
					 **************************************************/
					$subscriberOld = $this->subscribermain->findByUserIdentity($subscriber['USER_IDENTITY']);
					log_message('info', $i.'|OLD : '.json_encode($subscriberOld));
					/**************************************************
					 * copy columns that will not be changed by data from xls
					 **************************************************/
					$subscriber['BANDWIDTH'] = $subscriberOld['BANDWIDTH'];
					$subscriber['CREATEDATE'] = $subscriberOld['CREATEDATE'];
					$subscriber['RBADDITIONALSERVICE5'] = $subscriberOld['RBADDITIONALSERVICE5'];
					$subscriber['RBADDITIONALSERVICE4'] = is_null($subscriber['RBADDITIONALSERVICE4']) ? $subscriberOld['RBADDITIONALSERVICE4'] : $subscriber['RBADDITIONALSERVICE4'];
					$subscriber['RBADDITIONALSERVICE3'] = $subscriberOld['RBADDITIONALSERVICE3'];
					$subscriber['RBACCOUNTSTATUS'] = $subscriberOld['RBACCOUNTSTATUS'];
					$subscriber['RBSVCCODE2'] = $subscriberOld['RBSVCCODE2'];
					$subscriber['RBSECONDARYACCOUNT'] = $subscriberOld['RBSECONDARYACCOUNT'];
					$subscriber['RBUNLIMITEDACCESS'] = $subscriberOld['RBUNLIMITEDACCESS'];
					$subscriber['RBTIMESLOT'] = $subscriberOld['RBTIMESLOT'];
					$subscriber['RBREALM'] = $subscriberOld['RBREALM'];
					$subscriber['RBNUMBEROFSESSION'] = $subscriberOld['RBNUMBEROFSESSION'];
					$subscriber['RBCREATEDBY'] = $subscriberOld['RBCREATEDBY'];
					$subscriber['PASSWORD'] = is_null($subscriber['PASSWORD']) ? $subscriberOld['PASSWORD'] : $subscriber['PASSWORD'];
					$subscriber['RBCUSTOMERNAME'] = is_null($subscriber['RBCUSTOMERNAME']) ? $subscriberOld['RBCUSTOMERNAME'] : $subscriber['RBCUSTOMERNAME'];
					$subscriber['RBADDITIONALSERVICE2'] = is_null($subscriber['RBADDITIONALSERVICE2']) ? $subscriberOld['RBADDITIONALSERVICE2'] : $subscriber['RBADDITIONALSERVICE2'];
					$subscriber['RBADDITIONALSERVICE1'] = is_null($subscriber['RBADDITIONALSERVICE1']) ? $subscriberOld['RBADDITIONALSERVICE1'] : $subscriber['RBADDITIONALSERVICE1'];
					$subscriber['RBSVCCODE'] = is_null($subscriber['RBSVCCODE']) ? $subscriberOld['RBSVCCODE'] : $subscriber['RBSVCCODE'];
					$subscriber['CUSTOMERTYPE'] = is_null($subscriber['CUSTOMERTYPE']) ? $subscriberOld['CUSTOMERTYPE'] : $subscriber['CUSTOMERTYPE'];
					$subscriber['RBORDERNUMBER'] = is_null($subscriber['RBORDERNUMBER']) ? $subscriberOld['RBORDERNUMBER'] : $subscriber['RBORDERNUMBER'];
					$subscriber['RBENABLED'] = is_null($subscriber['RBENABLED']) ? $subscriberOld['RBENABLED'] : $subscriber['RBENABLED'];
					/**************************************************
					 * process new rbenabled
					 **************************************************/
					if (strtolower($subscriber['RBENABLED']) == 'yes' && strtolower($subscriber['CUSTOMERTYPE']) == 'residential') {
						$subscriber['RBENABLED'] = 'Yes';
					} else {
						$subscriber['RBENABLED'] = 'No';
					}
					/**************************************************
					 * process new status
					 **************************************************/
					if (!is_null($subscriber['CUSTOMERSTATUS'])) {
						if ($subscriber['CUSTOMERSTATUS'] == 'T') {
							log_message('info', $i.'|for deletion');
							$forDeletion[] = $this->subscribermain->findByUserIdentity($subscriber['USER_IDENTITY']);
							$forDeletionRowNumbers[] = $validRowNumbers[$i];
							continue;
						}
						if ($subscriber['CUSTOMERSTATUS'] != $subscriberOld['CUSTOMERSTATUS']) {
							$subscriber['RBCHANGESTATUSDATE'] = date('Y-m-d H:i:s', time());
							$subscriber['RBCHANGESTATUSFROM'] = $subscriberOld['CUSTOMERSTATUS'];
						} else {
							$subscriber['RBCHANGESTATUSDATE'] = $subscriberOld['RBCHANGESTATUSDATE'];
							$subscriber['RBCHANGESTATUSFROM'] = $subscriberOld['RBCHANGESTATUSFROM'];
						}
					} else {
						$subscriber['CUSTOMERSTATUS'] = $subscriberOld['CUSTOMERSTATUS'];
					}
					/**************************************************
					 * process new service number
					 **************************************************/
					if (!is_null($subscriber['RBSERVICENUMBER'])) {
						if ($subscriberOld['RBSERVICENUMBER'] != $subscriber['RBSERVICENUMBER']) {
							$serviceNumberExists = $this->subscribermain->serviceNumberExists(strval($subscriber['RBSERVICENUMBER']),
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if ($serviceNumberExists) {
								log_message('info', $i.'|new service number already exists');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedInvalidDataRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedInvalidData[] = array('rowdata' => $dataToUpdate[$i], 'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm), 'errors' => array('RBSERVICENUMBER' => 'Service number exists.'));
								continue;
							}
						}
					} else {
						$subscriber['RBSERVICENUMBER'] = $subscriberOld['RBSERVICENUMBER'];
					}
					/**************************************************
					 * check if plan is on database
					 **************************************************/
					if (!is_null($subscriber['RBACCOUNTPLAN'])) {
						// $serviceExists = $this->services->serviceExists($subscriber['RBACCOUNTPLAN']);
						$serviceExists = $this->services->serviceExistsNew2($subscriber['RBACCOUNTPLAN'],
							$this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
						if (!$serviceExists) {
							log_message('info', $i.'|service dne');
							$invalidRowNumbers[] = $validRowNumbers[$i];
							$notUpdatedInvalidDataRowNumbers[] = $validRowNumbers[$i];
							$notUpdatedInvalidData[] = array('rowdata' => $dataToUpdate[$i], 'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm), 'errors' => array('RBACCOUNTPLAN' => 'Service does not exist.'));
							continue;
						}
					}
					$ipSet = false;
					$netSet = false;
					$ipV6Set = false;
					/**************************************************
					 * if new plan OR ipaddress is entered, check for gpon
					 * (old plan + new ip / new plan + old ip / new plan + new ip)
					 **************************************************/
					/**************************************************
					 * new ip address is provided
					 **************************************************/
					if (!is_null($subscriber['RBIPADDRESS'])) {
						/**************************************************
						 * check provided ip validity
						 **************************************************/
						$validIp = $this->util->isIPValid($subscriber['RBIPADDRESS']);
						if (!$validIp) {
							log_message('info', $i.'|invalid ip, check if it is cabinet name: '.$subscriber['RBIPADDRESS']);
							$cabinetName = $subscriber['RBIPADDRESS'];
							$cabinetObj = $this->cabinet->getCabinetWithName($cabinetName);
							if ($cabinetObj === false || empty($cabinetObj) || is_null($cabinetObj)) {
								log_message('info', $i.'|invalid cabinet');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Invalid cabinet name ('.$cabinetName.').'));
								continue;
							}
							/**************************************************
							 * cabinet name is valid, get an unused ip address after checking if gpon type or not
							 **************************************************/
							$srv = !is_null($subscriber['RBACCOUNTPLAN']) ? strtolower($subscriber['RBACCOUNTPLAN']) : strtolower($subscriberOld['RBACCOUNTPLAN']);
							$needsGponIp = strpos($srv, 'gpon') !== false;
							$nextAvailableIpAddress = $this->ipaddress->getNextAvailableIpAddress($cabinetObj['homing_bng'], $needsGponIp,
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if ($nextAvailableIpAddress === false || empty($nextAvailableIpAddress) || is_null($nextAvailableIpAddress)) {
								log_message('info', $i.'|no available ip address');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'No available IP Address.'));
								continue;
							}
							/**************************************************
							 * assign new ip address
							 **************************************************/
							$subscriber['RBIPADDRESS'] = $nextAvailableIpAddress['IPADDRESS'];
							log_message('info', $i.'|mark new ip as used: '.$subscriber['RBIPADDRESS']);
							$this->ipaddress->markAsUsed($subscriber['RBIPADDRESS'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$ipSet = true;
							/**************************************************
							 * free up old ip address
							 **************************************************/
							if (!is_null($subscriberOld['RBIPADDRESS'])) {
								log_message('info', $i.'|free up old ip: '.$subscriberOld['RBIPADDRESS']);
								$this->ipaddress->freeUp($subscriberOld['RBIPADDRESS'],
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
							if ($this->useIPv6) {
								$dataToUpdate[$i][10] = $subscriber['RBIPADDRESS'];
							} else {
								$dataToUpdate[$i][9] = $subscriber['RBIPADDRESS'];
							}
						/**************************************************
						 * ip is valid, more checks follow
						 **************************************************/
						} else {
							/**************************************************
							 * check if ip address is at database
							 **************************************************/
							$ipExists = $this->ipaddress->ipExists($subscriber['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if (!$ipExists) {
								log_message('info', $i.'|ip does not exist');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Static IP address not available in IP pool.'));
								continue;
							}
							/**************************************************
							 * check if ip address can be used
							 **************************************************/
							$ipFree = $this->ipaddress->isFree($subscriber['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if (!$ipFree) {
								log_message('info', $i.'|ip not available');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Static IP used.'));
								continue;
							}
							/**************************************************
							 * check for gpon matching (plan & ip)
							 **************************************************/
							$srv = !is_null($subscriber['RBACCOUNTPLAN']) ? strtolower($subscriber['RBACCOUNTPLAN']) : strtolower($subscriberOld['RBACCOUNTPLAN']);
							$ipMustBeGPON = true;
							if (strpos($srv, 'gpon') === false) {
								$ipMustBeGPON = false;
							}
							$isGPON = $this->ipaddress->isGPON($subscriber['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if ($ipMustBeGPON && !$isGPON) {
								log_message('info', $i.'|ip address is not GPON (should be)');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Static IP must be GPON type.'));
								continue;
							} else if (!$ipMustBeGPON && $isGPON) {
								log_message('info', $i.'|ip address is GPON (should not be)');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Static IP must not be GPON type.'));
								continue;
							}
							/**************************************************
							 * ip can be used
							 **************************************************/
							log_message('info', $i.'|mark new ip as used: '.$subscriber['RBIPADDRESS']);
							$this->ipaddress->markAsUsed($subscriber['RBIPADDRESS'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$ipSet = true;
							/**************************************************
							 * free up old ip address
							 **************************************************/
							if (!is_null($subscriberOld['RBIPADDRESS'])) {
								log_message('info', $i.'|free up old ip: '.$subscriberOld['RBIPADDRESS']);
								$this->ipaddress->freeUp($subscriberOld['RBIPADDRESS'],
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
						}
					/**************************************************
					 * no ip address is provided (will not be changed)
					 **************************************************/
					} else {
						$subscriber['RBIPADDRESS'] = $subscriberOld['RBIPADDRESS'];
						/**************************************************
						 * new plan is provided
						 **************************************************/
						if (!is_null($subscriber['RBACCOUNTPLAN'])) {
							$srv = strtolower($subscriber['RBACCOUNTPLAN']);
							if (strpos($srv, 'gpon') === false) {
								$ipMustBeGPON = false;
							}
							/**************************************************
							 * check gpon matching (plan & ip)
							 **************************************************/
							$isGPON = $this->ipaddress->isGPON($subscriberOld['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if ($ipMustBeGPON && !$isGPON) {
								log_message('info', $i.'|ip address is not GPON (should be)');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Static IP must be GPON type.', 'RBACCOUNTPLAN' => 'Service is GPON type.'));
								continue;
							} else if (!$ipMustBeGPON && $isGPON) {
								log_message('info', $i.'|ip address is GPON (should not be)');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Static IP must not be GPON type.', 'RBACCOUNTPLAN' => 'Service is not GPON type.'));
								continue;
							}
						}
					}
					$subscriber['RBACCOUNTPLAN'] = !is_null($subscriber['RBACCOUNTPLAN']) ? $subscriber['RBACCOUNTPLAN'] : str_replace('~', '-', $subscriberOld['RBACCOUNTPLAN']);
					$subscriber['RADIUSPOLICY'] = !is_null($subscriber['RADIUSPOLICY']) ? $subscriber['RADIUSPOLICY'] : str_replace('~', '-', $subscriberOld['RADIUSPOLICY']);
					/**************************************************
					 * handle new net address
					 **************************************************/
					if (!is_null($subscriber['RBMULTISTATIC'])) {
						/**************************************************
						 * check if account has/will have ip address (cannot have net address without ip address)
						 **************************************************/
						if (is_null($subscriberOld['RBIPADDRESS'])) {
							if (!$ipSet) {
								log_message('info', $i.'|entering net without ip');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[$i] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Account has no Static IP.', 'RBMULTISTATIC' => 'Account has no Static IP.'));
								continue;
							}
						}
						/**************************************************
						 * check net address validity
						 **************************************************/
						$validNet = $this->util->isIPValid($subscriber['RBMULTISTATIC']);
						if (!$validNet) {
							log_message('info', $i.'|invalid net address, check if valid cabinet name and if a subnet is given');
							$givenNetAddress = $subscriber['RBMULTISTATIC'];
							if ($this->useSeparateSubnetForNetAddress) {
								$netAddressParts = explode($this->netAddressSubnetMarker, $givenNetAddress);
								$partsCount = count($netAddressParts);
							} else {
								$partsCount = 1;
							}
							/**************************************************
							 * is it a valid cabinet name?
							 **************************************************/
							$cabinetName = $this->useSeparateSubnetForNetAddress ? $netAddressParts[0] : $givenNetAddress;
							$cabinetObj = $this->cabinet->getCabinetWithName($cabinetName);
							log_message('debug', $i.'|cabinetName: '.$cabinetName.', cabinetObj: '.json_encode($cabinetObj));
							/**************************************************
							 * not a valid cabinet name, revert changes to ip address earlier
							 **************************************************/
							if ($cabinetObj === false || empty($cabinetObj) || is_null($cabinetObj)) {
								log_message('info', $i.'|invalid cabinet');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBMULTISTATIC' => 'Invalid cabinet name ('.$cabinetName.').'));
								if ($ipSet) {
									$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USER_IDENTITY'],
										$subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
								}
								continue;
							}
							/**************************************************
							 * was there a subnet given?
							 * only works if $this->useSeparateSubnetForNetAddress is true, else $cabinetSubnet is null
							 **************************************************/
							if ($this->useSeparateSubnetForNetAddress && $partsCount == 2) {
								$cabinetSubnet = $netAddressParts[1];
								log_message('debug', $i.'|subnet restriction: '.$cabinetSubnet);
							} else {
								$cabinetSubnet = null;
								log_message('debug', $i.'|no subnet restriction');
							}
							/**************************************************
							 * valid cabinet name, get first available net address (with or without subnet restriction)
							 **************************************************/
							$nextAvailableNetAddress = $this->netaddress->getNextAvailableNetAddressWithLocationAndSubnet($cabinetObj['homing_bng'], $cabinetSubnet,
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if ($nextAvailableNetAddress === false || empty($nextAvailableNetAddress) || is_null($nextAvailableNetAddress)) {
								log_message('info', $i.'|no available net address');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBMULTISTATIC' => 'No available Net Address.'));
								if ($ipSet) {
									$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USER_IDENTITY'],
										$subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
								}
								continue;
							}
							/**************************************************
							 * assign new net address
							 **************************************************/
							$subscriber['RBMULTISTATIC'] = $nextAvailableNetAddress['NETADDRESS'];
							log_message('info', $i.'|mark new net as used: '.$subscriber['RBMULTISTATIC']);
							$this->netaddress->markAsUsed($subscriber['RBMULTISTATIC'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$netSet = true;
							/**************************************************
							 * account has valid RBMULTISTATIC, set RBENABLED to 'No'
							 **************************************************/
							log_message('info', '@bulkmodify: '.$subscriber['USERNAME'].' has valid RBMULTISTATIC, setting RBENABLED to \'No\'');
							$subscriber['RBENABLED'] = 'No';
							/**************************************************
							 * free up old net address
							 **************************************************/
							if (!is_null($subscriberOld['RBMULTISTATIC'])) {
								log_message('info', $i.'|free up old net: '.$subscriberOld['RBMULTISTATIC']);
								$this->netaddress->freeUp($subscriberOld['RBMULTISTATIC'],
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
							if ($this->useIPv6) {
								$dataToUpdate[$i][11] = $subscriber['RBMULTISTATIC'];
							} else {
								$dataToUpdate[$i][10] = $subscriber['RBMULTISTATIC'];
							}
						/**************************************************
						 * net address is valid, more checks follow
						 **************************************************/
						} else {
							/**************************************************
							 * check if net address is in database
							 **************************************************/
							$netExists = $this->netaddress->netExists($subscriber['RBMULTISTATIC'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if (!$netExists) {
								log_message('info', $i.'|net address does not exist');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBMULTISTATIC' => 'Multi IP address not available in IP pool.'));
								if ($ipSet) {
									$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
								}
								continue;
							}
							/**************************************************
							 * check if net address is available
							 **************************************************/
							$netFree = $this->netaddress->isFree($subscriber['RBMULTISTATIC'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if (!$netFree) { //net address used
								log_message('info', $i.'|net address not available');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBMULTISTATIC' => 'Multi IP used.'));
								if ($ipSet) {
									$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
									$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
										$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
								}
								continue;
							}
							log_message('info', 'mark new net as used: '.$subscriber['RBMULTISTATIC']);
							/**************************************************
							 * net address can be used
							 **************************************************/
							$this->netaddress->markAsUsed($subscriber['RBMULTISTATIC'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$netSet = true;
							/**************************************************
							 * account has valid RBMULTISTATIC, set RBENABLED to 'No'
							 **************************************************/
							log_message('info', '@bulkmodify: '.$subscriber['USERNAME'].' has valid RBMULTISTATIC, setting RBENABLED to \'No\'');
							$subscriber['RBENABLED'] = 'No';
							/**************************************************
							 * free up old net address
							 **************************************************/
							if (!is_null($subscriberOld['RBMULTISTATIC'])) {
								log_message('info', $i.'|free up old net: '.$subscriberOld['RBMULTISTATIC']);
								$this->netaddress->freeUp($subscriberOld['RBMULTISTATIC'],
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
						}
					/**************************************************
					 * no new net address provided (will not be changed);
					 **************************************************/
					} else {
						$subscriber['RBMULTISTATIC'] = $subscriberOld['RBMULTISTATIC'];
					}
					/**************************************************
					 * append new remarks to previous, trim if new length is more than 3500 chars
					 **************************************************/
					$subscriber['RBREMARKS'] = is_null($subscriber['RBREMARKS']) ? $subscriberOld['RBREMARKS'] : $subscriberOld['RBREMARKS'].';'.
						date('Y-m-d H:i:s', time()).' '.$subscriber['RBREMARKS'];
					if (strlen($subscriber['RBREMARKS']) > 3500) {
						$subscriber['RBREMARKS'] = substr($subscriber['RBREMARKS'], strlen($subscriber['RBREMARKS']) - 3500);
					}
					/**************************************************
					 * handle new ipv6 address
					 **************************************************/
					if ($this->useIPv6 && !is_null($subscriber['RBADDITIONALSERVICE4'])) {
						/**************************************************
						 * check provided ipv6 validity
						 **************************************************/
						$validIpv6 = $this->ipaddress->isIPV6Valid($subscriber['RBADDITIONALSERVICE4']);
						if (!$validIpv6) {
							log_message('info', $i.'|invalid ipv6, check if it is cabinet name: '.$subscriber['RBADDITIONALSERVICE4']);
							$cabinetName = $subscriber['RBADDITIONALSERVICE4'];
							$cabinetObj = $this->cabinet->getCabinetWithName($cabinetName);
							if ($cabinetObj === false || empty($cabinetObj) || is_null($cabinetObj)) {
								log_message('info', $i.'|invalid cabinet');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'Invalid cabinet name ('.$cabinetName.').'));
								continue;
							}
							/**************************************************
							 * cabinet name is valid, get an unused ipv6 address
							 **************************************************/
							$nextAvailableIpV6Address = $this->ipaddress->getNextAvailableIpV6Address($cabinetObj['homing_bng'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if ($nextAvailableIpV6Address === false || empty($nextAvailableIpV6Address) || is_null($nextAvailableIpV6Address)) {
								log_message('info', $i.'|no available ip address');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBIPADDRESS' => 'No available IP Address.'));
								continue;
							}
							/**************************************************
							 * assign new ipv6 address
							 **************************************************/
							$subscriber['RBADDITIONALSERVICE4'] = $nextAvailableIpV6Address['IPV6ADDR'];
							log_message('info', $i.'|mark new ipv6 as used: '.$subscriber['RBADDITIONALSERVICE4']);
							$this->ipaddress->markV6AsUsed($subscriber['RBADDITIONALSERVICE4'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$ipV6Set = true;
							/**************************************************
							 * free up old ipv6 address
							 **************************************************/
							if (!is_null($subscriberOld['RBADDITIONALSERVICE4'])) {
								log_message('info', $i.'|free up old ipv6: '.$subscriberOld['RBADDITIONALSERVICE4']);
								$this->ipaddress->freeUpV6($subscriberOld['RBADDITIONALSERVICE4'],
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
							$dataToUpdate[$i][9] = $subscriber['RBADDITIONALSERVICE4'];
						/**************************************************
						 * ipv6 is valid, more checks follow
						 **************************************************/
						} else {
							/**************************************************
							 * check if ipv6 address is at database
							 **************************************************/
							$ipV6Exists = $this->ipaddress->ipV6Exists($subscriber['RBADDITIONALSERVICE4'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if (!$ipV6Exists) {
								log_message('info', $i.'|ipv6 does not exist');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBADDITIONALSERVICE4' => 'Static IPv6 address not available in IP pool.'));
								continue;
							}
							/**************************************************
							 * check if ipv6 address can be used
							 **************************************************/
							$ipV6Free = $this->ipaddress->isV6Free($subscriber['RBADDITIONALSERVICE4'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							if (!$ipV6Free) {
								log_message('info', $i.'|ipv6 not available');
								$invalidRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetErrorRowNumbers[] = $validRowNumbers[$i];
								$notUpdatedIPNetError[] = array('rowdata' => $dataToUpdate[$i],
									'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
									'errors' => array('RBADDITIONALSERVICE4' => 'Static IPv6 used.'));
								continue;
							}
							/**************************************************
							 * ipv6 can be used
							 **************************************************/
							log_message('info', $i.'|mark new ipv6 as used: '.$subscriber['RBADDITIONALSERVICE4']);
							$this->ipaddress->markV6AsUsed($subscriber['RBADDITIONALSERVICE4'], $subscriber['USER_IDENTITY'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$ipV6Set = true;
							/**************************************************
							 * free up old ipv6 address
							 **************************************************/
							if (!is_null($subscriberOld['RBADDITIONALSERVICE4'])) {
								log_message('info', $i.'|free up old ipv6: '.$subscriberOld['RBADDITIONALSERVICE4']);
								$this->ipaddress->freeUpV6($subscriberOld['RBADDITIONALSERVICE4'],
									$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							}
						}
					/**************************************************
					 * no new ipv6 address provided (will not be changed)
					 **************************************************/
					} else {
						$subscriber['RBADDITIONALSERVICE4'] = $subscriberOld['RBADDITIONALSERVICE4'];
					}
					/**************************************************
					 * add reply items
					 **************************************************/
					$subscriber['CUSTOMERREPLYITEM'] = $this->subscribermain->generateCustomerReplyItemValue(
						$subscriber['RBADDITIONALSERVICE4'], $subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC']);
					/**************************************************
					 * update activated date column
					 **************************************************/
					if (!is_null($subscriber['CUSTOMERSTATUS'])) {
						if ($subscriberOld['CUSTOMERSTATUS'] != $subscriber['CUSTOMERSTATUS']) {
							if ($subscriberOld['CUSTOMERSTATUS'] == 'D' && $subscriber['CUSTOMERSTATUS'] == 'A') {
								$subscriber['RBACTIVATEDDATE'] = date('Y-m-d H:i:s', time());
							}
							$subscriber['RBCHANGESTATUSDATE'] = date('Y-m-d H:i:s', time());
						}
					}
					log_message('info', $i.'|CBND: '.json_encode($subscriber));
					/**************************************************
					 * uncomment the next line if RBENABLED is fixed to 'No' and FAP to 'N'
					 * FAP is dependent on the value of $subscriber['RBENABLED']: 'No' => 'N', 'Yes' => 'Y'
					 **************************************************/
					// $subscriber['RBENABLED'] = 'No';
					/**************************************************
					 * update account
					 **************************************************/
					log_message('debug', '@processBulkUpdateSubscribersAdmin|subscriber:'.$subscriber['USERNAME'].'|RBENABLED:'.$subscriber['RBENABLED']);
					$result = $this->subscribermain->update($subscriber['USER_IDENTITY'], $subscriber,
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					/**************************************************
					 * update failed
					 **************************************************/
					if (!$result) {
						log_message('info', $i.'|subscriber not updated');
						/**************************************************
						 * revert changes made to ip/net address, if any
						 **************************************************/
						if ($ipSet) {
							$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if ($netSet) {
							$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if ($this->useIPv6 && $ipV6Set) {
							$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notUpdatedInvalidDataRowNumbers[] = $validRowNumbers[$i];
						$notUpdatedInvalidData[] = array('rowdata' => $dataToUpdate[$i], 'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm), 'errors' => array('ALL' => 'Failed to update.'));
						continue;
					}
					/**************************************************
					 * update account in npm, if enabled (if not, $npmResult['result'] will default to true)
					 **************************************************/
					if ($this->ENABLENPM) {
						$inNPM = $this->subscribermain->npmFetchXML($subscriber['USERNAME'],
							$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
						log_message('info', 'in npm:'.json_encode($inNPM));
						if ($inNPM['found']) {
							$npmResult = $this->subscribermain->npmUpdateXML($subscriber['USERNAME'], $subscriber['PASSWORD'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'Y' : 'N',
								str_replace('~', '-', $subscriber['RBACCOUNTPLAN']), $subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC'], 'N',
								$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
						} else {
							$npmResult = $this->subscribermain->npmCreateXML($subscriber['USERNAME'], $subscriber['PASSWORD'], $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'Y' : 'N',
								time(), str_replace('~', '-', $subscriber['RBACCOUNTPLAN']), $subscriber['RBIPADDRESS'], $subscriber['RBMULTISTATIC'], 'N',
								$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
						}
						log_message('info', 'result: '.json_encode($npmResult));
					} else {
						$npmResult['result'] = true;
					}
					/**************************************************
					 * npm update/insert failed, revert subscriber, ip/net changes
					 **************************************************/
					if (!$npmResult['result']) {
						log_message('info', $i.'|npm error');
						$this->subscribermain->update($subscriber['USER_IDENTITY'], $subscriberOld,
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						if ($ipSet) {
							$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if ($netSet) {
							$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if ($this->useIPv6 && $ipV6Set) {
							$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						$invalidRowNumbers[] = $validRowNumbers[$i];
						$notUpdatedNPMErrorRowNumbers[] = $validRowNumbers[$i];
						$notUpdatedNPMError[] = array('rowdata' => $dataToUpdate[$i], 'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm), 'errors' => $npmResult['error']);
						continue;
					}
					/**************************************************
					 * if account is to be deactivated, find sessions and delete them
					 **************************************************/
					if ($subscriber['CUSTOMERSTATUS'] == 'InActive') {
						$uParts = explode('@', $subscriber['USERNAME']);
						$this->load->model('onlinesession');
						$sessions = $this->onlinesession->getSessions2($uParts[0], $uParts[1], $this->USESESSIONTABLE2,
							$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
							$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
						if ($sessions['status'] && isset($sessions['data'])) {
							for ($h = 0; $h < count($sessions['data']); $h++) {
								$sess = $sessions['data'][$h];
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
					/**************************************************
					 * delete sessions if plan (RBACCOUNTPLAN) is new
					 **************************************************/
					$newRP = trim($subscriber['RBACCOUNTPLAN']);
					$newRP = str_replace('~', '-', $newRP);
					$oldRP = trim($subscriberOld['RBACCOUNTPLAN']);
					$oldRP = str_replace('~', '-', $oldRP);
					if ($newRP != $oldRP) {
						$cnParts = explode('@', $subscriber['USER_IDENTITY']);
						$sessions = $this->onlinesession->getSessions2($cnParts[0], $cnParts[1], $this->USESESSIONTABLE2,
							$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
							$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
						if ($sessions['status'] && isset($sessions['data'])) {
							for ($g = 0; $g < count($sessions['data']); $g++) {
								$sess = $sessions['data'][$g];
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
					/**************************************************
					 * update contents of RADIUSPOLICY at TBLCUSTOMER
					 * RADIUSPOLICY column at TBLCUSTOMER is no longer used (May 2017)
					 **************************************************/
					// $this->subscribermain->clearRadiuspolicy($subscriber['USERNAME'], $this->SESSIONUSERNAME2, $this->SESSIONPASSWORD2, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// $this->subscribermain->updateRadiuspolicy($subscriber['USERNAME'], str_replace('~', '-', $subscriber['RADIUSPOLICY']),
					// 	$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					/**************************************************
					 * RM api
					 **************************************************/
					// Remove RM Dependency 6/25/2019
					// $rmEdited = true;
					// $rmClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
					/**************************************************
					 * if branch for handling old rm api
					 * - as of nov 18, 2016 this is no longer used
					 * - remove this later (including closing brace before $rmEdited if block)
					 **************************************************/
					/*
					if ($this->RMAPIHOST == '10.244.4.130' || $this->RMAPIHOST == '10.244.4.131') {
						try {
							$rmSubsUsername = array('key' => 'USERNAME' , 'value' => $subscriber['USERNAME']);
							$rmSubsIdentity = array('key' => 'SUBSCRIBERIDENTITY', 'value' => $subscriber['USERNAME']);
							$rmSubsCui = array('key' => 'CUI', 'value' => $subscriber['USERNAME']);
							$rmSubsStatus = array('key' => 'SUBSCRIBERSTATUS', 'value' => 'Active');
							$rmSubsArea = array('key' => 'AREA', 'value' => $subscriber['CUSTOMERSTATUS']);
							$rmSubsPackage = array('key' => 'SUBSCRIBERPACKAGE', 'value' => str_replace('-', '~', $subscriber['RBACCOUNTPLAN']));
							$customerTypeForRM = '';
							if ($subscriber['CUSTOMERTYPE'] == 'Residential') {
								$customerTypeForRM = 'RESS';
							} else if ($subscriber['CUSTOMERTYPE'] == 'Business') {
								$customerTypeForRM = 'BUSS';
							} else {
								$customerTypeForRM = 'RESS';
							}
							$rmSubsCustomertype = array('key' => 'CUSTOMERTYPE', 'value' => $customerTypeForRM);
							$fapForRM = '';
							if ($subscriber['RBENABLED'] == 'Yes') {
								$fapForRM = 'Y';
							} else if ($subscriber['RBENABLED'] == 'No') {
								$fapForRM = 'N';
							} else {
								$fapForRM = 'N';
							}
							$rmSubsFap = array('key' => 'FAP', 'value' => $fapForRM);
							/**************************************************
							 * try updating
							 **************************************************
							$rmSubsPassword = array('key' => 'PASSWORD', 'value' => '');
							$rmParam = array($rmSubsPassword, $rmSubsStatus, $rmSubsArea, $rmSubsPackage, $rmSubsCustomertype, $rmSubsFap);
							$updateResult = $this->rm->updateAccount($subscriber['USERNAME'], $rmParam, $rmClient);
							if (intval($updateResult['responseCode']) == 200) {
								log_message('debug', 'bulkmodify|updated @ RM: '.$subscriber['USERNAME']);
							} else if (intval($updateResult['responseCode']) == 404) {
								log_message('debug', 'bulkmodify|'.$subscriber['USERNAME'].' not found, insert');
								$rmParam = array($rmSubsUsername, $rmSubsIdentity, $rmSubsCui, $rmSubsStatus, $rmSubsArea, $rmSubsPackage, $rmSubsCustomertype, $rmSubsFap);
								$insertResult = $this->rm->createAccount($rmParam, $rmClient);
								if (intval($insertResult['responseCode']) == 200) {
									log_message('debug', 'bulkmodify|inserted @ RM: '.$subscriber['USERNAME']);
								} else {
									log_message('debug', 'bulkmodify|failed to update @ RM: '.$subscriber['USERNAME'].'|'.$insertResult['responseMessage']);
									$rmEdited = false;
								}
							} else {
								log_message('debug', 'bulkmodify|failed to update @ RM: '.$subscriber['USERNAME'].'|'.$updateResult['responseMessage']);
								$rmEdited = false;
							}
						} catch (Exception $e) {
							log_message('debug', 'error @ RM:'.json_encode($e));
							$rmEdited = false;
						}
					} else if ($this->RMAPIHOST == '10.81.54.34' || $this->RMAPIHOST == '10.81.54.35') {
					*/
					// try {
					// 	$rmUsername = $subscriber['USERNAME'];
					// 	// $rmPlan = $subscriber['RADIUSPOLICY'];
					// 	$rmPlan = $subscriber['RBACCOUNTPLAN'];
					// 	// $rmStatus = strtoupper($subscriber['CUSTOMERSTATUS']);
					// 	$rmStatus = 'ACTIVE';
					// 	if (strtolower($subscriber['CUSTOMERTYPE']) == 'residential') {
					// 		$rmCustomerType = 'RESS';
					// 	} else if (strtolower($subscriber['CUSTOMERTYPE']) == 'business') {
					// 		$rmCustomerType = 'BUSS';
					// 	} else {
					// 		$rmCustomerType = 'RESS';
					// 	}
					// 	$fapForRM = '';
					// 	if ($subscriber['RBENABLED'] == 'Yes') {
					// 		$fapForRM = 'Y';
					// 	} else if ($subscriber['RBENABLED'] == 'No') {
					// 		$fapForRM = 'N';
					// 	} else {
					// 		$fapForRM = 'N';
					// 	}
					// 	/**************************************************
					// 	 * try updating
					// 	 **************************************************/
					// 	$nodes = array('PARAM3' => $fapForRM, 'AREA' => $subscriber['CUSTOMERSTATUS']);
					// 	$updateResult = $this->rm->wsUpdateSubscriberProfile($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
					// 	if (intval($updateResult['responseCode']) == 200) {
					// 		log_message('debug', 'bulkmodify|updated @ RM: '.$subscriber['USERNAME']);
					// 	} else if (intval($updateResult['responseCode']) == 404) {
					// 		log_message('debug', 'bulkmodify|'.$subscriber['USERNAME'].' not found, insert');
					// 		$insertResult = $this->rm->wsAddSubscriber($rmClient, $rmUsername, $rmPlan, $nodes, $rmStatus, $rmCustomerType);
					// 		if (intval($insertResult['responseCode']) == 200) {
					// 			log_message('debug', 'bulkmodify|inserted @ RM: '.$subscriber['USERNAME']);
					// 		} else {
					// 			log_message('debug', 'bulkmodify|failed to update @ RM: '.$subscriber['USERNAME'].'|'.$insertResult['responseMessage']);
					// 			$rmEdited = false;
					// 		}
					// 	} else {
					// 		log_message('debug', 'bulkmodify|failed to update @ RM: '.$subscriber['USERNAME'].'|'.$updateResult['responseMessage']);
					// 		$rmEdited = false;
					// 	}
					// } catch (Exception $e) {
					// 	log_message('debug', 'error @ RM:'.json_encode($e));
					// 	$rmEdited = false;
					// }
					// /*
					// }
					// */
					// if (!$rmEdited) {
					// 	$this->subscribermain->update($subscriber['USER_IDENTITY'], $subscriberOld,
					// 		$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// 	if ($ipSet) {
					// 		$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
					// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// 		$this->ipaddress->markAsUsed($subscriberOld['RBIPADDRESS'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
					// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// 	}
					// 	if ($netSet) {
					// 		$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
					// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// 		$this->netaddress->markAsUsed($subscriberOld['RBMULTISTATIC'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
					// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// 	}
					// 	if ($this->useIPv6 && $ipV6Set) {
					// 		$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
					// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// 		$this->ipaddress->markV6AsUsed($subscriberOld['RBADDITIONALSERVICE4'], $subscriberOld['USER_IDENTITY'], $subscriberOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D',
					// 			$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// 	}
					// 	$invalidRowNumbers[] = $validRowNumbers[$i];
					// 	$notUpdatedNPMErrorRowNumbers[] = $validRowNumbers[$i];
					// 	$notUpdatedNPMError[] = array('rowdata' => $dataToUpdate[$i], 'rowdata2' => $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm),
					// 		'errors' => isset($rmResult['responseMessage']) && !is_null($rmResult['responseMessage']) ? $rmResult['responseMessage'] : 'error at RM');
					// 	continue;
					// }
					/**************************************************
					 * /RM
					 **************************************************/
					$updatedRows[] = $this->subscribermain->findByUserIdentity($dataToUpdate[$i][0].'@'.$realm); //$dataToUpdate[$i];
					/**************************************************
					 * mark updated columns
					 **************************************************/
					$mark = [];
					if (trim($dataToUpdate[$i][1]) != '') {
						$mark['PASSWORD'] = true;
					}
					if (trim($dataToUpdate[$i][2]) != '') {
						$mark['CUSTOMERTYPE'] = true;
					}
					if (trim($dataToUpdate[$i][3]) != '') {
						$mark['CUSTOMERSTATUS'] = true;
					}
					if (trim($dataToUpdate[$i][4]) != '') {
						$mark['RBORDERNUMBER'] = true;
					}
					if (trim($dataToUpdate[$i][5]) != '') {
						$mark['RBCUSTOMERNAME'] = true;
					}
					if (trim($dataToUpdate[$i][6]) != '') {
						$mark['RBSERVICENUMBER'] = true;
					}
					if (trim($dataToUpdate[$i][7]) != '') {
						$mark['RBENABLED'] = true;
					}
					if (trim($dataToUpdate[$i][8]) != '') {
						$mark['RBACCOUNTPLAN'] = true;
					}
					if ($this->useIPv6) {
						if (trim($dataToUpdate[$i][9]) != '') {
							$mark['RBADDITIONALSERVICE4'] = true;
						}
						if (trim($dataToUpdate[$i][10]) != '') {
							$mark['RBIPADDRESS'] = true;
						}
						if (trim($dataToUpdate[$i][11]) != '') {
							$mark['RBMULTISTATIC'] = true;
						}
					} else {
						if (trim($dataToUpdate[$i][9]) != '') {
							$mark['RBIPADDRESS'] = true;
						}
						if (trim($dataToUpdate[$i][10]) != '') {
							$mark['RBMULTISTATIC'] = true;
						}
					}
					$updatedMarks[] = $mark;
					$updatedRowNumbers[] = $validRowNumbers[$i];
					if ($sysuser != $this->SUPERUSER) {
						$this->load->model('subscriberaudittrail');
						$this->subscriberaudittrail->logSubscriberModification($subscriber, $subscriberOld, $sysuser, $sysuserIP, $now, true);
					}
				}
				/*
				for ($i = 0; $i < count($updatedRows); $i++) {
					$row = $updatedRows[$i];
					if ($row[3] == 'D') {
						$updatedRows[$i][9] = null;
						$updatedRows[$i][10] = null;
					}
				}
				*/
				/**************************************************
				 * handle accounts to be deleted
				 **************************************************/
				$this->load->model('subscriberaudittrail');
				$thisPdFilename = date('YmdHis', time()).'_forPd.txt';
				$appendCount = 0;
				$forDeletionCount = count($forDeletion);
				// $this->load->library('rm');
				for ($i = 0; $i < $forDeletionCount; $i++) {
					log_message('info', '_________________________________delete:');
					log_message('info', json_encode($forDeletion[$i]));
					$this->subscribermain->delete($forDeletion[$i]['USER_IDENTITY'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					if ($this->ENABLENPM) {
						$this->subscribermain->npmRemoveSubscriber($forDeletion[$i]['USER_IDENTITY'],
							$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
					}
					/**************************************************
					 * delete session if account has one/more
					 **************************************************/
					$this->load->model('onlinesession');
					$parts = explode('@', $forDeletion[$i]['USER_IDENTITY']);
					$sessions = $this->onlinesession->getSessions2($parts[0], $parts[1], $this->USESESSIONTABLE2,
						$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
						$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
					if ($sessions['status'] && isset($sessions['data'])) {
						for ($ii = 0; $ii < count($sessions['data']); $ii++) {
							$sess = $sessions['data'][$ii];
							$deleted = $this->onlinesession->requestDisconnect($sess['USER_NAME'], $sess['NAS_IP_ADDRESS'], $sess['ACCT_SESSION_ID'], $this->USESESSIONTABLE2,
								$this->DELETESESSIONAPIHOST, $this->DELETESESSIONAPIPORT, $this->DELETESESSIONAPISTUB,
								$this->DELETESESSIONAPIHOST2, $this->DELETESESSIONAPIPORT2, $this->DELETESESSIONAPISTUB2,
								$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
								$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2,
								$this->TBLMCOREHOST, $this->TBLMCOREPORT, $this->TBLMCORESCHEMA, $this->TBLMCOREUSERNAME, $this->TBLMCOREPASSWORD,
								$this->TBLMCOREHOST2, $this->TBLMCOREPORT2, $this->TBLMCORESCHEMA2, $this->TBLMCOREUSERNAME2, $this->TBLMCOREPASSWORD2);
						}
					}
					/**************************************************
					 * RM api (delete account)
					 **************************************************/
					// $rmClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
					/**************************************************
					 * if branch for handling old rm api
					 * - as of nov 18, 2016 this is no longer used
					 * - remove this later (including closing brace before /RM marker)
					 **************************************************/
					/*
					if ($this->RMAPIHOST == '10.244.4.130' || $this->RMAPIHOST == '10.244.4.131') {
						$rmResult = $this->rm->accountExists($forDeletion[$i]['USER_IDENTITY'], $rmClient);
						if (!$rmResult['exists']) {
							log_message('debug', 'not found @ RM: '.$forDeletion[$i]['USER_IDENTITY']);
						} else {
							$rmResult = $this->rm->deleteAccount($forDeletion[$i]['USER_IDENTITY'], $rmClient);
							if (intval($rmResult['responseCode']) == 200) {
								log_message('debug', 'deleted @ RM: '.$forDeletion[$i]['USER_IDENTITY']);
							} else {
								log_message('debug', 'failed to delete @ RM: '.$forDeletion[$i]['USER_IDENTITY'].' | '.$rmResult['responseMessage']);
							}
						}
					} else if ($this->RMAPIHOST == '10.81.54.34' || $this->RMAPIHOST == '10.81.54.35') {
					*/
					// $deleteResult = $this->rm->wsDeleteSubscriberProfile($rmClient, $forDeletion[$i]['USER_IDENTITY']);
					// if (intval($deleteResult['responseCode']) == 200 || intval($deleteResult['responseCode']) == 404) {
					// 	log_message('debug', 'deleted @ RM: '.$forDeletion[$i]['USER_IDENTITY']);
					// 	$purgeResult = $this->rm->wsPurgeSubscriber($rmClient, $forDeletion[$i]['USER_IDENTITY']);
					// 	if (intval($purgeResult['responseCode']) == 200) {
					// 		log_message('debug', 'purged @ RM: '.$forDeletion[$i]['USER_IDENTITY']);
					// 	} else {
					// 		log_message('debug', 'failed to purge @ RM: '.$forDeletion[$i]['USER_IDENTITY'].' | '.$purgeResult['responseMessage']);
					// 	}
					// } else {
					// 	log_message('debug', 'failed to delete @ RM: '.$forDeletion[$i]['USER_IDENTITY'].' | '.$deleteResult['responseMessage']);
					// }
					/*
					}
					*/
					/**************************************************
					 * /RM
					 **************************************************/
					if ($sysuser != $this->SUPERUSER) {
						$this->subscriberaudittrail->logSubscriberDeletion($forDeletion[$i], $sysuser, $sysuserIP, $now);
					}
					if (!is_null($forDeletion[$i]['RBIPADDRESS'])) {
						$this->ipaddress->freeUp($forDeletion[$i]['RBIPADDRESS'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
					if (!is_null($forDeletion[$i]['RBMULTISTATIC'])) {
						$this->netaddress->freeUp($forDeletion[$i]['RBMULTISTATIC'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
					if ($this->useIPv6 && !is_null($forDeletion[$i]['RBADDITIONALSERVICE4'])) {
						$this->ipaddress->freeUpV6($forDeletion[$i]['RBADDITIONALSERVICE4'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					}
				}
				// log_message('debug', 'PD: ['.($forDeletionCount == $appendCount ? 'all accounts appended' : 'failed to append '.($forDeletionCount - $appendCount).' accounts').'] '.$theFilename);
				/**************************************************
				 * write deleted accounts to file
				 **************************************************/
				if ($forDeletionCount != 0) {
					$this->util->writeToDeletedSubscriberFile($forDeletion, time());
					$deletedXls = $this->util->writeDeletedSubscriberXlsForExtract($forDeletion, 'update');
				}
				log_message('info', '__________________________________________________________________________________');
				log_message('info', 'UPDATED: '.json_encode($updatedRows));
				log_message('info', 'USERNAME DNE: '.json_encode($notUpdatedUsernameDNE));
				log_message('info', 'INVALID DATA: '.json_encode($notUpdatedInvalidData));
				log_message('info', 'IPNET ERROR: '.json_encode($notUpdatedIPNetError));
				log_message('info', 'NPM ERROR: '.json_encode($notUpdatedNPMError));
				log_message('info', 'NOT CREATED (ROWS): '.json_encode($invalidRowNumbers));
				$data = array(
					'step' => 'update',
					'proceed' => $checks['go'],
					'error' => $checks['msg'],
					'path' => $path,
					'realm' => $realm,
					'updated' => $updatedRows,
					'updatedMarks' => $updatedMarks,
					'usernameDNE' => $notUpdatedUsernameDNE,
					'invalidData' => $notUpdatedInvalidData,
					'ipNetError' => $notUpdatedIPNetError,
					'npmError' => $notUpdatedNPMError,
					'invalidRowNumbers' => $invalidRowNumbers,
					'updatedRowNumbers' => $updatedRowNumbers,
					'usernameDNERowNumbers' => $notUpdatedUsernameDNERowNumbers,
					'invalidDataRowNumbers' => $notUpdatedInvalidDataRowNumbers,
					'ipNetErrorRowNumbers' => $notUpdatedIPNetErrorRowNumbers,
					'npmErrorRowNumbers' => $notUpdatedNPMErrorRowNumbers,
					'deleted' => $forDeletion,
					'deletedFile' => isset($deletedXls) ? $deletedXls : '');
				$data['useIPv6'] = $this->useIPv6;
				$this->load->view('bulk_modify_users_admin', $data);
			/**************************************************
			 * download step
			 **************************************************/
			} else if ($step == 'download') {
				log_message('info', '__________@extract');
				$path = $this->input->post('path');
				$set = trim($this->input->post('set'));
				$realm = $this->input->post('realm');
				$setdata = unserialize($this->input->post('setdata'));
				$this->load->model('util');
				if ($set == 'deleted') {
					$file = $this->input->post('file');
					$this->util->fetchFile($file);
				} else {
					if ($this->useIPv6) {
						$this->util->writeBulkModifyOutputV2($path, $setdata, $set, $realm);
					} else {
						$this->util->writeBulkModifyOutput($path, $setdata, $set, $realm);
					}
				}
			}
		/**************************************************
		 * subscribers/processBulkUpdateSubscribers accessed via url
		 **************************************************/
		} else {
			//get realms
			$this->load->model('realm');
			$realms = $this->realm->fetchAllNamesOnly();
			$data = array(
				'realms' => $realms);
			if ($portal == 'service') {
				$data['realm'] = $realm;
				$data['disableRealm'] = true;
			}
			$data['proceed'] = $checks['go'];
			$data['error'] = $checks['msg'];
			$data['useIPv6'] = $this->useIPv6;
			$this->load->view('bulk_modify_users_admin', $data);
		}
	}


	public function processBulkUnassignIPv6IPAndNetAddress($step = null) {
		$this->redirectIfNoAccess('Modify Account', 'subscribers/processBulkUnassignIPv6IPAndNetAddress');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@processBulkUnassignIPv6IPAndNetAddress|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@processBulkUnassignIPv6IPAndNetAddress|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		/**************************************************
		 * subscribers/processBulkUnassignIPv6IPAndNetAddress accessed via form
		 **************************************************/
		if (is_null($step)) {
			$step = $this->input->post('step');
			$realm = '';
			/**************************************************
			 * upload step
			 **************************************************/
			if ($step == 'upload') {
				//get realms
				$this->load->model('realm');
				$realms = $this->realm->fetchAllNamesOnly();
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx|application/vnd.ms-office|\"application/vnd.ms-office\"|"application/vnd.ms-office"';
				$config['max_size'] = '50000';
				$config['overwrite'] = true;
				$this->load->library('upload', $config);
				/**************************************************
				 * upload failed
				 **************************************************/
				if (!$this->upload->do_upload('file')) {
					$data['step'] = 'upload';
					$data['error'] = 'Upload failed: '.$this->upload->display_errors();
					$data['realm'] = $this->input->post('realm');
					$data['realms'] = $realms;
					$data['proceed'] = $checks['go'];
					if ($portal == 'service') {
						$data['realm'] = $realm;
						$data['disableRealm'] = true;
					}
					$data['useIPv6'] = $this->useIPv6;
					$this->load->view('bulk_unassign_ipv6_ip_net_address', $data);
				/**************************************************
				 * upload ok
				 **************************************************/
				} else {
					$realm = $this->input->post('realm');
					$this->load->model('util');
					$uploaded = $this->upload->data();
					log_message('info', 'UPLOADED FILE: '.json_encode($uploaded));
					/**************************************************
					 * check uploaded xls format (header check);
					 **************************************************/
					if ($this->useIPv6) {
						$valid = $this->util->verifyBulkUnassignIPv6IPAndNetFormatV2($uploaded['full_path']);
					} else {
						$valid = $this->util->verifyBulkUnassignIPv6IPAndNetFormat($uploaded['full_path']);
					}
					/**************************************************
					 * invalid xls file (some headers did not match)
					 **************************************************/
					if (!$valid) {
						$data['step'] = 'upload';
						$data['error'] = 'Invalid file contents: "'.$uploaded['client_name'].'"';
						$data['realm'] = $realm;
						$data['realms'] = $realms;
						$data['proceed'] = $checks['go'];
						if ($portal == 'service') {
							$data['realm'] = $realm;
							$data['disableRealm'] = true;
						}
						$data['useIPv6'] = $this->useIPv6;
						$this->load->view('bulk_unassign_ipv6_ip_net_address', $data);
					/**************************************************
					 * valid xls file
					 **************************************************/
					} else {
						$totalRowCount = $this->util->countRows($uploaded['full_path']);
						log_message('info', 'fileRowCount:'.$totalRowCount);
						/**************************************************
						 * check if xls row count exceeds maximum
						 **************************************************/
						if ($totalRowCount > $this->xlsRowLimit) {
							$data['step'] = 'upload';
							$data['error'] = 'Due to memory constraints, please limit files to less than '.$this->xlsRowLimit.' rows.';
							$data['realm'] = $realm;
							$data['realms'] = $realms;
							$data['proceed'] = $checks['go'];
							if ($portal == 'service') {
								$data['realm'] = $realm;
								$data['disableRealm'] = true;
							}
							$data['useIPv6'] = $this->useIPv6;
							$this->load->view('bulk_unassign_ipv6_ip_net_address', $data);
							return;
						}
						/**************************************************
						 * check xls row contents: basically only checks if a username is provided
						 **************************************************/
						$rows = $this->util->verifyBulkUnassignIPv6IPAndNetData($uploaded['full_path'], $realm);
						$this->load->model('subscribermain');
						log_message('info', 'ROWS: '.json_encode($rows));
						$noChangeRows = [];
						$noChangeRowNumbers = [];
						$validRows = [];
						$validRowNumbers = [];
						$dneRows = [];
						$dneRowNumbers = [];
						/**************************************************
						 * preparation for update (before unassign)
						 * $mark array indicates which info will be unassigned
						 **************************************************/
						$validTmp = $rows['valid'];
						$validTmpCount = count($rows['valid']);
						for ($i = 0; $i < $validTmpCount; $i++) {
							$thisRow = $validTmp[$i];
							$mark = [];
							$rowAddedToValid = false;
							if ($this->useIPv6) {
								//nothing to unassign (doesn't matter if subscriber actually has any address attached)
								if (strtolower(trim($thisRow[9])) != 'remove' && strtolower(trim($thisRow[10])) != 'remove' && strtolower(trim($thisRow[11])) != 'remove') {
									$noChangeRowNumbers[] = $rows['validRowNumbers'][$i];
									$noChangeRows[] = $thisRow;
								//see if there is something to unassign
								} else {
									$subs = $this->subscribermain->findByUserIdentity($thisRow[0]);
									log_message('debug', '                             ');
									log_message('debug', '@SUBS:'.json_encode($subs));
									log_message('debug', '                             ');
									if ($subs === false) {
										$thisRow[9] = '';
										$thisRow[10] = '';
										$thisRow[11] = '';
										$dneRows[] = $thisRow;
										$dneRowNumbers[] = $rows['validRowNumbers'][$i];
										continue;
									}
									if (strtolower(trim($thisRow[9])) == 'remove') {
										$mark['RBADDITIONALSERVICE4'] = true;
									}
									$thisRow[9] = is_null($subs['RBADDITIONALSERVICE4']) ? '' : $subs['RBADDITIONALSERVICE4'];
									if (isset($mark['RBADDITIONALSERVICE4']) && !is_null($subs['RBADDITIONALSERVICE4'])) {
										if (!$rowAddedToValid) {
											$rowAddedToValid = true;
										}
									}
									if (strtolower(trim($thisRow[10])) == 'remove') {
										$mark['RBIPADDRESS'] = true;
									}
									$thisRow[10] = is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS'];
									if (isset($mark['RBIPADDRESS']) && !is_null($subs['RBIPADDRESS'])) {
										if (!$rowAddedToValid) {
											$rowAddedToValid = true;
										}
									}
									if (strtolower(trim($thisRow[11])) == 'remove' || isset($mark['RBIPADDRESS'])) {
										$mark['RBMULTISTATIC'] = true;
									}
									$thisRow[11] = is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC'];
									if (isset($mark['RBMULTISTATIC']) && !is_null($subs['RBMULTISTATIC'])) {
										if (!$rowAddedToValid) {
											$rowAddedToValid = true;
										}
									}
									$validMarks[] = $mark;
									if ($rowAddedToValid) {
										$validRows[] = $thisRow;
										$validRowNumbers[] = $rows['validRowNumbers'][$i];
									} else {
										$noChangeRows[] = $thisRow;
										$noChangeRowNumbers[] = $rows['validRowNumbers'][$i];
									}
								}
							} else {
								//nothing to unassign (doesn't matter if subscriber actually has any address attached)
								if (strtolower(trim($thisRow[9])) != 'remove' && strtolower(trim($thisRow[10])) != 'remove') {
									$noChangeRowNumbers[] = $rows['validRowNumbers'][$i];
									$noChangeRows[] = $thisRow;
								//see if there is something to unassign
								} else {
									$subs = $this->subscribermain->findByUserIdentity($thisRow[0]);
									log_message('debug', '                             ');
									log_message('debug', '@SUBS:'.json_encode($subs));
									log_message('debug', '                             ');
									if ($subs === false) {
										$thisRow[9] = '';
										$thisRow[10] = '';
										$dneRows[] = $thisRow;
										$dneRowNumbers[] = $rows['validRowNumbers'][$i];
										continue;
									}
									if (strtolower(trim($thisRow[9])) == 'remove') {
										$mark['RBIPADDRESS'] = true;
									}
									$thisRow[9] = is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS'];
									if (isset($mark['RBIPADDRESS']) && !is_null($subs['RBIPADDRESS'])) {
										if (!$rowAddedToValid) {
											$rowAddedToValid = true;
										}
									}
									if (strtolower(trim($thisRow[10])) == 'remove' || isset($mark['RBIPADDRESS'])) {
										$mark['RBMULTISTATIC'] = true;
									}
									$thisRow[10] = is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC'];
									if (isset($mark['RBMULTISTATIC']) && !is_null($subs['RBMULTISTATIC'])) {
										if (!$rowAddedToValid) {
											$rowAddedToValid = true;
										}
									}
									$validMarks[] = $mark;
									if ($rowAddedToValid) {
										$validRows[] = $thisRow;
										$validRowNumbers[] = $rows['validRowNumbers'][$i];
									} else {
										$noChangeRows[] = $thisRow;
										$noChangeRowNumbers[] = $rows['validRowNumbers'][$i];
									}
								}
							}
						}
						$data = array(
							'step' => 'confirm',
							'proceed' => $checks['go'],
							'error' => $checks['msg'],
	    					'path' => $uploaded['full_path'],
	    					'realm' => $realm,
	    					'valid' => $validRows,
	    					'validMarks' => $validMarks,
	    					'validRowNumbers' => $validRowNumbers,
	    					'invalid' => $rows['invalid'],
	    					'invalidRowNumbers' => $rows['invalidRowNumbers'],
	    					'noChange' => $noChangeRows,
	    					'noChangeRowNumbers' => $noChangeRowNumbers,
	    					'dne' => $dneRows,
	    					'dneRowNumbers' => $dneRowNumbers);
						$data['useIPv6'] = $this->useIPv6;
	    				$this->load->view('bulk_unassign_ipv6_ip_net_address', $data);
					}
				}
			} else if ($step == 'unassign') {
				if (!$checks['go']) {
					redirect('subscribers/processBulkUpdateSubscribers/upload');
				}
				$now = date('Y-m-d H:i:s', time());
				$realm = $this->input->post('realm');
				$path = $this->input->post('path');
				$validRowNumbers = unserialize($this->input->post('validRowNumbers'));
				$invalidRowNumbers = unserialize($this->input->post('invalidRowNumbers'));
				$noChangeRowNumbers = unserialize($this->input->post('noChangeRowNumbers'));
				log_message('info', 'VALID ROWS: '.json_encode($validRowNumbers));
				log_message('info', 'INVALID ROWS: '.json_encode($invalidRowNumbers));
				log_message('info', 'NO CHANGE ROWS: '.json_encode($noChangeRowNumbers));
				$unassignedRows = [];
				$unassignedMarks = [];
				$notUnassignedRows = [];
				$unassignedRowNumbers = [];
				$notUnassignedRowNumbers = [];
				$this->load->model('util');
				$this->load->model('subscribermain');
				$this->load->model('ipaddress');
				$this->load->model('netaddress');
				$this->load->model('sysuseractivitylog');
				$this->load->model('subscriberaudittrail');
				if ($this->useIPv6) {
					$dataToUpdate = $this->util->extractRowsToUpdateV2($path, $validRowNumbers);
				} else {
					$dataToUpdate = $this->util->extractRowsToUpdate($path, $validRowNumbers);
				}
				log_message('info', 'TO UNASSIGN: '.json_encode($dataToUpdate));

				/**************************************************
				 * get these from session variables
				 **************************************************/
				$sysuser = $this->session->userdata('username');
				$sysuserIP = $this->session->userdata('ip_address');

				$dataToUpdateCount = count($dataToUpdate);
				for ($i = 0; $i < $dataToUpdateCount; $i++) {
					$removeIPv6 = false;
					$unassignedIPv6 = false;
					$removeIP = false;
					$removeNet = false;
					$unassignedIP = false;
					$unassingedNet = false;
					$mark = [];
					/**************************************************
					 * convert xls row to (smaller) $subscriber array containing only relevant addresses
					 **************************************************/
					if ($this->useIPv6) {
						$subscriber = $this->subscribermain->rowDataToSubscriberUpdateArrayV2($dataToUpdate[$i], $realm, $sysuser);
					} else {
						$subscriber = $this->subscribermain->rowDataToSubscriberUpdateArray($dataToUpdate[$i], $realm, $sysuser);
					}
					log_message('info', $i.'|NEW : '.json_encode($subscriber));
					/**************************************************
					 * existence check was already done after the upload step
					 * so at this point, only existing accounts will be processed
					 **************************************************/
					$subscriberOld = $this->subscribermain->findByUserIdentity($subscriber['USER_IDENTITY']);
					log_message('info', $i.'|OLD : '.json_encode($subscriberOld));
					/**************************************************
					 * start unassigning addresses
					 **************************************************/
					if ($this->useIPv6) {
						if (strtolower($subscriber['RBADDITIONALSERVICE4']) == 'remove' && !is_null($subscriberOld['RBADDITIONALSERVICE4'])) {
							$removeIPv6 = true;
							$subscriber['RBADDITIONALSERVICE4'] = $subscriberOld['RBADDITIONALSERVICE4'];
						}
					}
					if (strtolower($subscriber['RBIPADDRESS']) == 'remove' && !is_null($subscriberOld['RBIPADDRESS'])) {
						$removeIP = true;
						$subscriber['RBIPADDRESS'] = $subscriberOld['RBIPADDRESS'];
					}
					if (strtolower($subscriber['RBMULTISTATIC']) == 'remove' && !is_null($subscriberOld['RBMULTISTATIC']) || $removeIP) {
						$removeNet = true;
						$subscriber['RBMULTISTATIC'] = $subscriberOld['RBMULTISTATIC'];
						$subscriber['RBIPADDRESS'] = $subscriberOld['RBIPADDRESS'];
					}
					$replyItem = $this->subscribermain->generateCustomerReplyItemValue(
						($this->useIPv6 ? ($removeIPv6 ? null : $subscriberOld['RBADDITIONALSERVICE4']) : $subscriberOld['RBADDITIONALSERVICE4']),
						($removeIP ? null : $subscriberOld['RBIPADDRESS']),
						($removeNet ? null : $subscriberOld['RBMULTISTATIC']));
					log_message('debug', '                                 ');
					log_message('debug', $i.'|subscriber: '.json_encode($subscriber));
					log_message('debug', $i.'|new replyItem: '.(is_null($replyItem) ? 'null' : $replyItem));
					log_message('debug', '                                 ');
					$result = $this->subscribermain->unassignIPv6IPAndNetAddress($subscriber['USER_IDENTITY'], $removeIPv6, $removeIP, $removeNet, $replyItem,
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					// $result = false;
					/**************************************************
					 * removal at TBLCUSTOMER failed
					 **************************************************/
					if (!$result) {
						log_message('debug', $i.'|failed to remove addresses: '.($removeIPv6 ? 'ipv6' : '').' '.($removeIP ? 'ip' : '').' '.($removeNet ? 'net' : ''));
						$notUnassignedRows[] = array('subsdata' => $subscriber, 'errors' => array('ALL' => 'Failed to update.'));
						$notUnassignedRowNumbers[] = $validRowNumbers[$i];
						continue;
					}
					/**************************************************
					 * addresses attached to subscriber removed at TBLCUSTOMER
					 **************************************************/
					if ($this->useIPv6) {
						if ($removeIPv6) {
							$result = $this->ipaddress->freeUpV6($subscriberOld['RBADDITIONALSERVICE4'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
							// $result = true;
							$mark['RBADDITIONALSERVICE4'] = true;
							log_message('debug', $i.'|'.($result ? 'unassigned' : 'failed to unassign').' '.
								$subscriberOld['RBADDITIONALSERVICE4'].' from '.$subscriber['USER_IDENTITY']);
							if ($sysuser != $this->SUPERUSER && $result) {
								$this->sysuseractivitylog->logIpV6AddressFreeup($subscriberOld['RBADDITIONALSERVICE4'], $sysuser, $sysuserIP, time());
							}
						}
					}
					if ($removeIP) {
						$result = $this->ipaddress->freeUp($subscriberOld['RBIPADDRESS'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// $result = true;
						$mark['RBIPADDRESS'] = true;
						log_message('debug', $i.'|'.($result ? 'unassigned' : 'failed to unassign').' '.
							$subscriberOld['RBIPADDRESS'].' from '.$subscriber['USER_IDENTITY']);
						if ($sysuser != $this->SUPERUSER && $result) {
							$this->sysuseractivitylog->logIpAddressFreeup($subscriberOld['RBIPADDRESS'], $sysuser, $sysuserIP, time());
						}
					}
					if ($removeNet) {
						$result = $this->netaddress->freeUp($subscriberOld['RBMULTISTATIC'],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						// $result = true;
						$mark['RBMULTISTATIC'] = true;
						log_message('debug', $i.'|'.($result ? 'unassigned' : 'failed to unassign').' '.
							$subscriberOld['RBMULTISTATIC'].' from '.$subscriber['USER_IDENTITY']);
						if ($sysuser != $this->SUPERUSER && $result) {
							$this->sysuseractivitylog->logNetAddressFreeup($subscriberOld['RBMULTISTATIC'], $sysuser, $sysuserIP, time());
						}
					}
					$unassignedMarks[] = $mark;
					$unassignedRows[] = $subscriber;
					$unassignedRowNumbers[] = $validRowNumbers[$i];
					if ($sysuser != $this->SUPERUSER) {
						if ($this->useIPv6) {
							$subscriber['RBADDITIONALSERVICE4'] = strtolower(trim($subscriber['RBADDITIONALSERVICE4'])) == 'remove' ? null : $subscriberOld['RBADDITIONALSERVICE4'];
						}
						$subscriber['RBIPADDRESS'] = strtolower(trim($subscriber['RBIPADDRESS'])) == 'remove' ? null : $subscriberOld['RBIPADDRESS'];
						$subscriber['RBMULTISTATIC'] = strtolower(trim($subscriber['RBMULTISTATIC'])) == 'remove' ? null : $subscriberOld['RBMULTISTATIC'];
						$subscriber['RBREALM'] = $realm;
						log_message('debug', 'subscriber:'.json_encode($subscriber));
						log_message('debug', 'subscriberOld:'.json_encode($subscriberOld));
						$this->subscriberaudittrail->logSubscriberModification($subscriber, $subscriberOld, $sysuser, $sysuserIP, date('Y-m-d H:i:s', time()), true);
					}
				}
				$data = array(
					'step' => 'unassign',
					'proceed' => $checks['go'],
					'error' => $checks['msg'],
					'path' => $path,
					'realm' => $realm,
					'unassigned' => $unassignedRows,
					'unassignedMarks' => $unassignedMarks,
					'unassignedRowNumbers' => $unassignedRowNumbers,
					'notUnassigned' => $notUnassignedRows,
					'notUnassignedRowNumbers' => $notUnassignedRowNumbers);
				$data['useIPv6'] = $this->useIPv6;
				$this->load->view('bulk_unassign_ipv6_ip_net_address', $data);
			} else if ($step == 'download') {
				//not sure if it will be implemented yet
			}
		/**************************************************
		 * subscribers/processBulkUnassignIPv6IPAndNetAddress accessed via url
		 **************************************************/
		} else {
			//get realms
			$this->load->model('realm');
			$realms = $this->realm->fetchAllNamesOnly();
			$data = array(
				'realms' => $realms);
			if ($portal == 'service') {
				$data['realm'] = $realm;
				$data['disableRealm'] = true;
			}
			$data['proceed'] = $checks['go'];
			$data['error'] = $checks['msg'];
			$data['useIPv6'] = $this->useIPv6;
			$this->load->view('bulk_unassign_ipv6_ip_net_address', $data);
		}
	}
	/***********************************************************************
	 * delete subscriber
	 * PAGEID = 15
	 ***********************************************************************/
	public function showDeleteSubscriberForm() {
		$this->redirectIfNoAccess('Delete Account', 'subscribers/showDeleteSubscriberForm');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		//get realms
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@showDeleteSubscriberForm|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@showDeleteSubscriberForm|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		$data = array(
			'show' => $checks['go'] ? false : true,
			'found' => false,
			'proceed' => $checks['go'],
			'error' => $checks['msg'],
			'realms' => $realms);
		if ($portal == 'service') {
			$data['realm'] = $realm;
			$data['disableRealm'] = true;
		}
		$data['useIPv6'] = $this->useIPv6;
		$this->load->view('delete_user_account', $data);
	}
	public function loadDeleteSubscriberForm() {
		$this->redirectIfNoAccess('Delete Account', 'subscribers/loadDeleteSubscriberForm');
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@loadDeleteSubscriberForm|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@loadDeleteSubscriberForm|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		//get realms
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();

		$username = trim($this->input->post('username'));
		$realm = $this->input->post('realm');
		$cn = $username.'@'.$realm;
		$this->load->model('subscribermain');
		/**************************************************
		 * fetch account data
		 **************************************************/
		$subscriber = $this->subscribermain->findByUserIdentity($cn);
		log_message('info', 'SEARCH: '.json_encode($subscriber));
		/**************************************************
		 * account does not exist
		 **************************************************/
		if ($subscriber === false) {
			$data = array(
				'show' => true,
				'found' => false,
				'proceed' => $checks['go'],
				'message' => $checks['msg'],
				'username' => $username,
				'realm' => $realm,
				'realms' => $realms,
				'error' => 'User '.$cn.' not found.');
			if ($portal == 'service') {
				$data['realm'] = $realm;
				$data['disableRealm'] = true;
			}
			$data['useIPv6'] = $this->useIPv6;
			$this->load->view('delete_user_account', $data);
		/**************************************************
		 * account exists
		 **************************************************/
		} else {
			$data = array(
				'show' => true,
				'found' => true,
				'proceed' => $checks['go'],
				'error' => $checks['msg'],
				'username' => $username,
				'realm' => $realm,
				'realms' => $realms,
				'subscriber' => $subscriber);
			if ($portal == 'service') {
				$data['realm'] = $realm;
				$data['disableRealm'] = true;
			}
			$data['useIPv6'] = $this->useIPv6;
			$this->load->view('delete_user_account', $data);
		}
	}
	public function processDeleteSubscriber() {
		$this->redirectIfNoAccess('Delete Account', 'subscribers/processDeleteSubscriber');
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@processDeleteSubscriber|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@processDeleteSubscriber|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		//get realms
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();

		/**************************************************
		 * get this from session variable
		 **************************************************/
		$sysuser = $this->session->userdata('username');
		$sysuserIP = $this->session->userdata('ip_address');

		$this->load->model('subscribermain');
		$realm = $this->input->post('realm');
		$username = trim($this->input->post('username'));
		$cn = $username.'@'.$realm;
		$subscriber = $this->subscribermain->findByUserIdentity($cn);
		if (!$checks['go']) {
			$data = array(
				'show' => true,
				'found' => true,
				'proceed' => $checks['go'],
				'error' => $checks['msg'],
				'username' => $username,
				'realm' => $realm,
				'realms' => $realms,
				'subscriber' => $subscriber);
			$data['useIPv6'] = $this->useIPv6;
			$this->load->view('delete_user_account', $data);
			return;
		}
		$deleted = $this->subscribermain->delete($cn, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
		if (!$deleted) {
			$data = array(
				'show' => true,
				'found' => true,
				'username' => $username,
				'realm' => $realm,
				'realms' => $realms,
				'subscriber' => $subscriber,
				'error' => 'Failed to delete subscriber.');
			$data['useIPv6'] = $this->useIPv6;
			$this->load->view('delete_user_account', $data);
		} else {
			/**************************************************
			 * delete in npm
			 **************************************************/
			if ($this->ENABLENPM) {
				$npmResult = $this->subscribermain->npmRemoveSubscriber($subscriber['USERNAME'],
					$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
			} else {
				$npmResult['deleted'] = true;
			}
			if (!$npmResult['deleted']) {
				$this->subscribermain->create($subscriber, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				$data = array(
					'show' => true,
					'found' => true,
					'username' => $username,
					'realm' => $realm,
					'realm' => $realms,
					'subscriber' => $subscriber,
					'error' => 'Failed to delete subscriber. NPM: '.$npmResult['error']);
				$data['useIPv6'] = $this->useIPv6;
				$this->load->view('delete_user_account', $data);
			} else {
				/**************************************************
				 * freeup ipv6, ip and net addresses
				 **************************************************/
				if ($this->useIPv6 && !is_null($subscriber['RBADDITIONALSERVICE4']) || !is_null($subscriber['RBIPADDRESS'])) {
					$this->load->model('ipaddress');
				}
				if (!is_null($subscriber['RBADDITIONALSERVICE4'])) {
					$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				}
				if (!is_null($subscriber['RBIPADDRESS'])) {
					$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				}
				if (!is_null($subscriber['RBMULTISTATIC'])) {
					$this->load->model('netaddress');
					$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
				}
				//delete session if account has one
				$this->load->model('onlinesession');
				$sessions = $this->onlinesession->getSessions2($username, $realm, $this->USESESSIONTABLE2,
					$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
					$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
				if ($sessions['status'] && isset($sessions['data'])) {
					for ($ii = 0; $ii < count($sessions['data']); $ii++) {
						$sess = $sessions['data'][$ii];
						$deleted = $this->onlinesession->requestDisconnect($sess['USER_NAME'], $sess['NAS_IP_ADDRESS'], $sess['ACCT_SESSION_ID'], $this->USESESSIONTABLE2,
							$this->DELETESESSIONAPIHOST, $this->DELETESESSIONAPIPORT, $this->DELETESESSIONAPISTUB,
							$this->DELETESESSIONAPIHOST2, $this->DELETESESSIONAPIPORT2, $this->DELETESESSIONAPISTUB2,
							$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
							$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2,
							$this->TBLMCOREHOST, $this->TBLMCOREPORT, $this->TBLMCORESCHEMA, $this->TBLMCOREUSERNAME, $this->TBLMCOREPASSWORD,
							$this->TBLMCOREHOST2, $this->TBLMCOREPORT2, $this->TBLMCORESCHEMA2, $this->TBLMCOREUSERNAME2, $this->TBLMCOREPASSWORD2);
					}
				}
				/**************************************************
				 * rm
				 **************************************************/
				// Remove RM Dependency 6/25/2019
				// $this->load->library('rm');
				// $rmClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
				/**************************************************
				 * if branch for handling old rm api
				 * - as of nov 18, 2016 this is no longer used
				 * - remove this later (including closing brace before rm marker)
				 **************************************************/
				/*
				if ($this->RMAPIHOST == '10.244.4.130' || $this->RMAPIHOST == '10.244.4.131') {
					try {
						$rmResult['exists'] = true; //try delete
						if (!$rmResult['exists']) {
							log_message('debug', 'not found @ RM: '.$subscriber['USERNAME']);
						} else {
							$rmResult = $this->rm->deleteAccount($subscriber['USERNAME'], $rmClient);
							if (intval($rmResult['responseCode']) == 200 || intval($rmResult['responseCode']) == 201) {
								log_message('debug', 'deleted @ RM: '.$subscriber['USERNAME']);
							} else {
								log_message('debug', 'failed to delete @ RM: '.$subscriber['USERNAME'].' | '.$rmResult['responseMessage']);
							}
						}
					} catch (Exception $e) {
							log_message('debug', 'error @ RM:'.json_encode($e));
					}
				} else if ($this->RMAPIHOST == '10.81.54.34' || $this->RMAPIHOST == '10.81.54.35') {
				*/
				// try {
					/*
					for ($ii = 0; $ii < 4; $ii++) {
						if ($ii == 0) {
							$thisUsername = $subscriber['USERNAME'];
							$deleteResult = $this->rm->wsDeleteSubscriberProfile($rmClient, $thisUsername);
							log_message('debug', 'create|delete (alternateId) result for '.$thisUsername.': '.$deleteResult['responseCode']);
							$purgeResult = $this->rm->wsPurgeSubscriber($rmClient, $thisUsername);
							log_message('debug', 'create|purge (alternateId) result for '.$thisUsername.': '.$purgeResult['responseCode']);
							$deleteResult = $this->rm->wsDeleteSubscriberProfileV2($rmClient, $thisUsername);
							log_message('debug', 'create|delete (subscriberID) result for '.$thisUsername.': '.$deleteResult['responseCode']);
							$purgeResult = $this->rm->wsPurgeSubscriberV2($rmClient, $thisUsername);
							log_message('debug', 'create|purge (subscriberID) result for '.$thisUsername.': '.$purgeResult['responseCode']);
						} else {
							$thisUsername = $subscriber['USERNAME'].'#L'.$ii;
							$deleteResult = $this->rm->wsDeleteSubscriberProfileV2($rmClient, $thisUsername);
							log_message('debug', 'create|delete (subscriberID) result for '.$thisUsername.': '.$deleteResult['responseCode']);
							$purgeResult = $this->rm->wsPurgeSubscriberV2($rmClient, $thisUsername);
							log_message('debug', 'create|purge (subscriberID) result for '.$thisUsername.': '.$purgeResult['responseCode']);
						}
					}
					*/
					//*
				// 	$deleteResult = $this->rm->wsDeleteSubscriberProfile($rmClient, $subscriber['USERNAME']);
				// 	if (intval($deleteResult['responseCode']) == 200 || intval($deleteResult['responseCode']) == 404) {
				// 		log_message('debug', 'deleted @ RM: '.$subscriber['USERNAME']);
				// 		if (intval($deleteResult['responseCode']) == 200) {
				// 			$purgeResult = $this->rm->wsPurgeSubscriber($rmClient, $subscriber['USERNAME']);
				// 			if (intval($purgeResult['responseCode']) == 200) {
				// 				log_message('debug', 'purged @ RM: '.$subscriber['USERNAME']);
				// 			} else {
				// 				log_message('debug', 'failed to purge @ RM: '.$subscriber['USERNAME'].' | '.$purgeResult['responseMessage']);
				// 			}
				// 		}
				// 	} else {
				// 		log_message('debug', 'failed to delete @ RM: '.$subscriber['USERNAME'].' | '.$deleteResult['responseMessage']);
				// 	}
				// 	//*/
				// } catch (Exception $e) {
				// 	log_message('debug', 'error @ RM:'.json_encode($e));
				// }
				/*
				}
				*/
				/**************************************************
				 * rm
				 **************************************************/
				if ($sysuser != $this->SUPERUSER) {
					$this->load->model('subscriberaudittrail');
					$this->subscriberaudittrail->logSubscriberDeletion($subscriber, $sysuser, $sysuserIP, date('Y-m-d H:i:s', time()));
				}

				$subscribers = [];
				$subscribers[] = $subscriber;
				$this->load->model('util');
				$this->util->writeToDeletedSubscriberFile($subscribers, time());
				$data = array(
					'subscriber' => $subscriber,
					'message' => 'Account deleted');
				$data['useIPv6'] = $this->useIPv6;
				$this->load->view('delete_user_account_result', $data);
			}
		}
	}
	public function processBulkDeleteSubscribers($step = null) {
		$this->redirectIfNoAccess('Delete Account', 'subscribers/processBulkDeleteSubscribers');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		/**************************************************
		 * check connections
		 **************************************************/
		// RM Dependencies 6/20/2019
		// $clientCheck =  $this->isConnectedToRmV2();
		// $rmOk = $clientCheck === false ? false : true;
		$dbOk = $this->isConnectedToMainDbV2();
		$checks = $this->proceedWithAction($dbOk);
		// $checks = $this->proceedWithAction($dbOk, $rmOk);
		log_message('debug', '@processBulkDeleteSubscribers|dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		// log_message('debug', '@processBulkDeleteSubscribers|rmOk:'.json_encode($rmOk).',dbOk:'.json_encode($dbOk).'|proceed:'.json_encode($checks));
		/**************************************************
		 * subscribers/processBulkDeleteSubscribers accessed via form
		 **************************************************/
		if (is_null($step)) {
			$step = $this->input->post('step');
			$realm = '';
			/**************************************************
			 * upload step
			 **************************************************/
			if ($step == 'upload') {
				$this->load->model('realm');
				$realms = $this->realm->fetchAllNamesOnly();
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"'.
					'|application/x-msexcel|xls|xlsx|application/vnd.ms-office|\"application/vnd.ms-office\"|"application/vnd.ms-office"';
				$config['max_size'] = '50000';
				$config['overwrite'] = true;
				$this->load->library('upload', $config);
				/**************************************************
				 * file upload failed
				 **************************************************/
				if (!$this->upload->do_upload('file')) {
					$data['step'] = 'upload';
    				$data['error'] = 'Upload failed: '.$this->upload->display_errors();
    				$data['realm'] = $this->input->post('realm');
    				$data['realms'] = $realms;
    				$data['proceed'] = $checks['go'];
    				if ($portal == 'service') {
    					$data['realm'] = $realm;
    					$data['disableRealm'] = true;
    				}
    				$data['useIPv6'] = $this->useIPv6;
    				$this->load->view('bulk_delete_users', $data);
    			/**************************************************
				 * file uploaded
				 **************************************************/
				} else {
					$this->load->model('util');
					$realm = $this->input->post('realm');
					$uploaded = $this->upload->data();
					$totalRowCount = $this->util->countRows($uploaded['full_path']);
					log_message('info', 'fileRowCount:'.$totalRowCount);
					/**************************************************
					 * check if xls row count exceeds maximum
					 **************************************************/
					if ($totalRowCount > $this->xlsRowLimit) {
						$data['step'] = 'upload';
						$data['error'] = 'Due to memory constraints, please limit files to less than 5000 rows.';
						$data['realm'] = $this->input->post('realm');
						$data['realms'] = $realms;
						$data['proceed'] = $checks['go'];
						if ($portal == 'service') {
							$data['realm'] = $realm;
							$data['disableRealm'] = true;
						}
						$data['useIPv6'] = $this->useIPv6;
						$this->load->view('bulk_delete_users', $data);
						return;
					}
					/**************************************************
					 * check xls file headers, only USERNAME (B) is required
					 **************************************************/
					$valid = $this->util->verifyBulkDeleteFormat($uploaded['full_path']);
					/**************************************************
					 * xls valid
					 **************************************************/
					if (!$valid) {
						$data['step'] = 'upload';
    					$data['error'] = 'Invalid file contents: "'.$uploaded['client_name'].'"';
    					$data['realms'] = $realms;
    					$data['proceed'] = $checks['go'];
    					if ($portal == 'service') {
    						$data['realm'] = $realm;
    						$data['disableRealm'] = true;
    					}
    					$data['useIPv6'] = $this->useIPv6;
    					$this->load->view('bulk_delete_users', $data);
    				/**************************************************
					 * xls invalid (basically column b has wrong header)
					 **************************************************/
					} else {
						/**************************************************
						 * extract usernames that will be deleted
						 **************************************************/
						$rows = $this->util->verifyBulkDeleteData($uploaded['full_path'], $realm);
						$this->load->model('subscribermain');
						$valid = [];
						$validCN = [];
						$invalidCN = [];
						/**************************************************
						 * fetch each account data
						 **************************************************/
						for ($i = 0; $i < count($rows['valid']); $i++) {
							$subs = $this->subscribermain->findByUserIdentity($rows['valid'][$i][0]);
							if ($subs === false) {
								$invalidCN[] = $rows['valid'][$i][0];
							} else {
								$validCN[] = $rows['valid'][$i][0];
								$valid[] = $subs;
							}
						}
						log_message('info', '@upload|'.json_encode($invalidCN));
						log_message('info', '@upload|'.json_encode($validCN));
						$data = array(
							'step' => 'confirm',
							'proceed' => $checks['go'],
							'error' => $checks['msg'],
	    					'path' => $uploaded['full_path'],
	    					'realm' => $realm,
	    					'valid' => $valid,
	    					'validCN' => $validCN,
	    					'invalidCN' => $invalidCN);
						if ($portal == 'service') {
							$data['disableRealm'] = true;
						}
						$data['useIPv6'] = $this->useIPv6;
						$this->load->view('bulk_delete_users', $data);
					}
				}
			/**************************************************
			 * delete step
			 **************************************************/
			} else if ($step == 'delete') {
				if (!$checks['go']) {
					redirect('subscribers/processBulkDeleteSubscribers/upload');
				}
				$now = date('Y-m-d H:i:s', time());
				$realm = $this->input->post('realm');
				$path = $this->input->post('path');
				$validCN = unserialize($this->input->post('validCN'));
				$invalidCN = unserialize($this->input->post('invalidCN'));
				log_message('info', 'VALID CNs:'.json_encode($validCN));
				$deleted = [];
				$notDeleted = [];
				$notDeletedRowNumbers = [];
				$this->load->model('util');
				$this->load->model('subscribermain');
				// $this->load->library('rm');
				/**************************************************
				 * get this from session variable
				 **************************************************/
				$sysuser = $this->session->userdata('username');
				$sysuserIP = $this->session->userdata('ip_address');

				$this->load->model('subscriberaudittrail');
				$this->load->library('rm');
				$theFilename = date('YmdHis', time()).'_forPd.txt';
				$appendCount = 0;
				$validCNCount = count($validCN);
				// $rmClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
				for ($i = 0; $i < $validCNCount; $i++) {
					/**************************************************
					 * fetch subscriber info
					 **************************************************/
					$subscriber = $this->subscribermain->findByUserIdentity($validCN[$i]);
					/**************************************************
					 * subscriber is in table
					 **************************************************/
					if ($subscriber !== false) {
						/**************************************************
						 * free up ipv6/ip/net address if used
						 **************************************************/
						if (!is_null($subscriber['RBADDITIONALSERVICE4']) || !is_null($subscriber['RBIPADDRESS'])) {
							$this->load->model('ipaddress');
						}
						if ($this->useIPv6 && !is_null($subscriber['RBADDITIONALSERVICE4'])) {
							$this->ipaddress->freeUpV6($subscriber['RBADDITIONALSERVICE4'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if (!is_null($subscriber['RBIPADDRESS']))  {
							$this->ipaddress->freeUp($subscriber['RBIPADDRESS'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						if (!is_null($subscriber['RBMULTISTATIC'])) {
							$this->load->model('netaddress');
							$this->netaddress->freeUp($subscriber['RBMULTISTATIC'],
								$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						}
						/**************************************************
						 * delete account
						 **************************************************/
						$this->subscribermain->delete($validCN[$i],
							$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
						if ($this->ENABLENPM) {
							$this->subscribermain->npmRemoveSubscriber($validCN[$i],
								$this->NPMHOST, $this->NPMPORT, $this->NPMAPI, $this->NPMLOGIN, $this->NPMPASSWORD, $this->NPMTIMEOUT);
						}
						/**************************************************
						 * delete session if account has one (or more)
						 **************************************************/
						$this->load->model('onlinesession');
						$parts = explode('@', $validCN[$i]);
						$sessions = $this->onlinesession->getSessions2($parts[0], $parts[1], $this->USESESSIONTABLE2,
							$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
							$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
						if ($sessions['status'] && isset($sessions['data'])) {
							for ($ii = 0; $ii < count($sessions['data']); $ii++) {
								$sess = $sessions['data'][$ii];
								$deleted = $this->onlinesession->requestDisconnect($sess['USER_NAME'], $sess['NAS_IP_ADDRESS'], $sess['ACCT_SESSION_ID'], $this->USESESSIONTABLE2,
									$this->DELETESESSIONAPIHOST, $this->DELETESESSIONAPIPORT, $this->DELETESESSIONAPISTUB,
									$this->DELETESESSIONAPIHOST2, $this->DELETESESSIONAPIPORT2, $this->DELETESESSIONAPISTUB2,
									$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
									$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2,
									$this->TBLMCOREHOST, $this->TBLMCOREPORT, $this->TBLMCORESCHEMA, $this->TBLMCOREUSERNAME, $this->TBLMCOREPASSWORD,
									$this->TBLMCOREHOST2, $this->TBLMCOREPORT2, $this->TBLMCORESCHEMA2, $this->TBLMCOREUSERNAME2, $this->TBLMCOREPASSWORD2);
							}
						}
						/**************************************************
						 * RM api
						 **************************************************/
						/**************************************************
						 * if branch for handling old rm api
						 * - as of nov 18, 2016 this is no longer used
						 * - remove this later (including closing brace before /RM marker)
						 **************************************************/
						/*
						if ($this->RMAPIHOST == '10.244.4.130' || $this->RMAPIHOST == '10.244.4.131') {
							try {
								/**************************************************
								 * account not found in RM, nothing to delete
								 **************************************************
								$rmResult['exists'] = true; //try delete
								if (!$rmResult['exists']) {
									log_message('debug', 'not found @ RM: '.$subscriber['USERNAME']);
								/**************************************************
								 * account found in RM, delete
								 **************************************************
								} else {
									$rmResult = $this->rm->deleteAccount($subscriber['USERNAME'], $rmClient);
									if (intval($rmResult['responseCode']) == 200 || intval($rmResult['responseCode']) == 201) {
										log_message('debug', 'deleted @ RM: '.$subscriber['USERNAME']);
									} else {
										log_message('debug', 'failed to delete @ RM: '.$subscriber['USERNAME'].' | '.$rmResult['responseMessage']);
										$rmDeleted = false;
									}
								}
							} catch (Exception $e) {
								log_message('debug', 'error @ RM:'.json_encode($e));
								$rmDeleted = false;
							}
						} else if ($this->RMAPIHOST == '10.81.54.34' || $this->RMAPIHOST == '10.81.54.35') {
						*/
						// Remove RM Dependency 6/25/2019
						// try {
						// 	$rmDeleted = true;
						// 	$deleteResult = $this->rm->wsDeleteSubscriberProfile($rmClient, $subscriber['USERNAME']);
						// 	if (intval($deleteResult['responseCode']) == 200 || intval($deleteResult['responseCode']) == 404) {
						// 		log_message('debug', 'bulk delete|deleted @ RM: '.$subscriber['USERNAME']);
						// 		if (intval($deleteResult['responseCode']) == 200) {
						// 			$purgeResult = $this->rm->wsPurgeSubscriber($rmClient, $subscriber['USERNAME']);
						// 			if (intval($purgeResult['responseCode']) == 200) {
						// 				log_message('debug', 'bulk delete|purged @ RM: '.$subscriber['USERNAME']);
						// 			} else {
						// 				log_message('debug', 'bulk delete|failed to purge @ RM: '.$subscriber['USERNAME'].' | '.$purgeResult['responseMessage']);
						// 			}
						// 		}
								/*
								for ($ii = 1; $ii < 4; $ii++) {
									$thisUsername = $subscriber['USERNAME'].'#L'.$ii;
									$fetchResult = $this->rm->wsGetSubscriberProfileByID($rmClient, $thisUsername);
									log_message('debug', 'bulk delete|fetch result for '.$thisUsername.': '.$fetchResult['responseCode']);
									if (intval($fetchResult['responseCode']) == 200) {
										$deleteResult = $this->rm->wsDeleteSubscriberProfile($rmClient, $thisUsername);
										log_message('debug', 'bulk delete|delete result for '.$thisUsername.': '.$deleteResult['responseCode']);
										$purgeResult = $this->rm->wsPurgeSubscriber($rmClient, $thisUsername);
										log_message('debug', 'bulk delete|purge result for '.$thisUsername.': '.$purgeResult['responseCode']);
									}
								}
								*/
						// 	} else {
						// 		log_message('debug', 'bulk delete|failed to delete @ RM: '.$subscriber['USERNAME'].' | '.$deleteResult['responseMessage']);
						// 		$rmDeleted = false;
						// 	}
						// } catch (Exception $e) {
						// 	log_message('debug', 'bulk delete|error @ RM:'.json_encode($e));
						// 	$rmDeleted = false;
						// }
						/*
						}
						*/
						/**************************************************
						 * /RM
						 **************************************************/
						if ($sysuser != $this->SUPERUSER) {
							$this->subscriberaudittrail->logSubscriberDeletion($subscriber, $sysuser, $sysuserIP, date('Y-m-d H:i:s', time()));
						}
						$deleted[] = $subscriber;
					}
				}
				/**************************************************
				 * write deleted account info to file
				 **************************************************/
				if (count($deleted) != 0) {
					$this->util->writeToDeletedSubscriberFile($deleted, time());
					if ($this->useIPv6) {
						$deletedXls = $this->util->writeDeletedSubscriberXlsForExtractV2($deleted, 'delete');
					} else {
						$deletedXls = $this->util->writeDeletedSubscriberXlsForExtract($deleted, 'delete');
					}
					$dneUsersXls = $this->util->writeDNESubscribersForExtract($invalidCN);
				}
				$data = array(
					'step' => 'delete',
					'proceed' => $checks['go'],
					'error' => $checks['msg'],
					'deleted' => $deleted,
					'notDeleted' => $notDeleted,
					'invalidCN' => $invalidCN,
					'dne' => $dneUsersXls,
					'file' => $deletedXls);
				$data['useIPv6'] = $this->useIPv6;
				$this->load->view('bulk_delete_users', $data);
			/**************************************************
			 * download step
			 **************************************************/
			} else if ($step == 'download') {
				log_message('info', '__________@extract');
				$path = $this->input->post('path');
				$set = trim($this->input->post('set'));
				$realm = $this->input->post('realm');
				$setdata = unserialize($this->input->post('setdata'));
				$this->load->model('util');
				$file = $this->input->post('file');
				$this->util->fetchFile($file);
			}
		/**************************************************
		 * subscribers/processBulkDeleteSubscribers accessed via url
		 **************************************************/
		} else {
			//get realms
			$this->load->model('realm');
			$realms = $this->realm->fetchAllNamesOnly();
			$data = array(
				'realms' => $realms);
			if ($portal == 'service') {
				$data['realm'] = $this->session->userdata('realm');
				$data['disableRealm'] = true;
			}
			$data['proceed'] = $checks['go'];
			$data['error'] = $checks['msg'];
			$data['useIPv6'] = $this->useIPv6;
			$this->load->view('bulk_delete_users', $data);
		}
	}
	/***********************************************************************
	 * unsubscribe vod
	 ***********************************************************************/
	public function showUnsubscribeVodForm() {
		$this->redirectIfNoAccess('Delete Account', 'subscribers/showUnsubscribeVodForm');
		$data = array(
			'show' => false,
			'found' => false);
		$this->load->view('unsubscribe_vod', $data);
	}
	public function loadUnsubscribeVodForm() {
		$this->redirectIfNoAccess('Delete Account', 'subscribers/loadUnsubscribeVodForm');
		$username = trim($this->input->post('subsIdentity'));
		//fetch
		$vodWsdl = 'http://'.$this->VODAPIHOST.':'.$this->VODAPIPORT.'/'.$this->VODAPISTUB;
		log_message('debug', 'vodWsdl: '.$vodWsdl);
		try {
			$vodClient = new SoapClient($vodWsdl);
			$param = array('subscriberId' => $username);
			$addOns = $vodClient->wsListAddOnSubscriptions($param);
			log_message('debug', 'addOns:'.json_encode($addOns));
			$responseCode = $addOns->AddOnSubscriptionRes->responseCode;
			$responseMessage = $addOns->AddOnSubscriptionRes->responseMessage;
			if (intval($responseCode) == 200) {
				if (isset($addOns->AddOnSubscriptionRes->addOnSubscriptionDataRes) && !is_null($addOns->AddOnSubscriptionRes->addOnSubscriptionDataRes)) {
					$vodData = $addOns->AddOnSubscriptionRes->addOnSubscriptionDataRes;
					$data = array(
						'show' => true,
						'found' => true,
						'subsIdentity' => $username,
						'vodData' => $vodData);
				} else {
					$data = array(
						'show' => true,
						'found' => false,
						'subsIdentity' => $username,
						'error' => 'Account has no current subscription.');
				}
			} else {
				$data = array(
					'show' => true,
					'found' => false,
					'subsIdentity' => $username,
					'error' => $responseMessage);
			}
			$this->load->view('unsubscribe_vod', $data);
		} catch (Exception $e) {
			log_message('debug', 'error @ loadUnsubscribeVodForm:'.json_encode($e));
			$data = array(
				'show' => true,
				'found' => false,
				'error' => json_encode($e));
			$this->load->view('unsubscribe_vod', $data);
		}
	}
	public function processUnsubscribeVod() {
		$this->redirectIfNoAccess('Delete Account', 'subscribers/processUnsubscriberVod');
		$addOnPackageID = $this->input->post('addOnPackageID');
		$addOnPackageName = $this->input->post('addOnPackageName');
		$addOnSubscriptionID = $this->input->post('addOnSubscriptionID');
		$lastUpdateTime = $this->input->post('lastUpdateTime');
		$subscriberID = $this->input->post('subscriberID');
		$subscriptionEndTime = $this->input->post('subscriptionEndTime');
		$subscriptionStartTime = $this->input->post('subscriptionStartTime');
		$subscriptionStatusName = $this->input->post('subscriptionStatusName');
		// $subscriptionStatusValue = $this->input->post('subscriptionStatusValue');
		// $subscriptionTime = $this->input->post('subscriptionTime');
		$vodWsdl = 'http://'.$this->VODAPIHOST.':'.$this->VODAPIPORT.'/'.$this->VODAPISTUB;
		log_message('debug', 'vodWsdl: '.$vodWsdl);
		$data = array(
			'addOnPackageID' => $addOnPackageID,
			'addOnPackageName' => $addOnPackageName,
			'addOnSubscriptionID' => $addOnSubscriptionID,
			'lastUpdateTime' => $lastUpdateTime,
			'subscriberID' => $subscriberID,
			'subscriptionEndTime' => $subscriptionEndTime,
			'subscriptionStartTime' => $subscriptionStartTime,
			'subscriptionStatusName' => $subscriptionStatusName,
			// 'subscriptionStatusValue' => $subscriptionStatusValue,
			// 'subscriptionTime' => $subscriptionTime,
			'show' => true);
		try {
			$vodClient = new SoapClient($vodWsdl);
			$param = array('subscriberId' => $subscriberID, 'addOnSubscriptionID' => $addOnSubscriptionID);
			$result = $vodClient->wsChangeAddOnSubscription($param);
			$data['responseCode'] = isset($result->AddOnSubscriptionRes->responseCode) ? $result->AddOnSubscriptionRes->responseCode : 300;
			$data['message'] = isset($result->AddOnSubscriptionRes->responseMessage) ?
				(intval($result->AddOnSubscriptionRes->responseCode) == 200 ? 'Unsubscribed VOD' : $result->AddOnSubscriptionRes->responseMessage) :
				'No reply from API';
		} catch (Exception $e) {
			log_message('debug', 'error @ processUnsubscribeVod:'.json_encode($e));
			$data['show'] = false;
			$data['error'] = 'Error accessing API';
		}
		$this->load->view('unsubscribe_vod_result', $data);
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
				log_message('info', 'allowedpages:'.json_encode($allowedPages));
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

	public function ajaxAssignDynamicIP() {
		if ($this->input->is_ajax_request()) {
			$username = $this->input->post('username');
			$realm = $this->input->post('realm');
			$data = array();
			$this->load->model('onlinesession');
			$sessions = $this->onlinesession->getSessions2($username, $realm, $this->USESESSIONTABLE2,
				$this->TBLMCONCHOST, $this->TBLMCONCPORT, $this->TBLMCONCSCHEMA, $this->TBLMCONCUSERNAME, $this->TBLMCONCPASSWORD,
				$this->TBLMCONCHOST2, $this->TBLMCONCPORT2, $this->TBLMCONCSCHEMA2, $this->TBLMCONCUSERNAME2, $this->TBLMCONCPASSWORD2);
			log_message('info', '@ajaxAssignDynamicIP|sessions:'.json_encode($sessions));

			/**************************************************
			 * hardcoded sessions for testing
			 **************************************************
			$sessions = array();
			$session = array('USER_NAME' => 'abc02@globelines.com.ph', 'FRAMED_IP_ADDRESS' => '201.22.13.234', 'NAS_IDENTIFIER' => 'VAL_BNG-001');
			$sessions['status'] = true;
			$sessions['data'][] = $session;
			/**************************************************
			 * hardcoded sessions for testing
			 **************************************************/

			if ($sessions['status'] == false) {
				$data['session'] = '0';
				echo json_encode($data);
			} else {
				$data['session'] = '1';
				$this->load->model('bngcounter');
				$session = $sessions['data'][0];
				$nasName = $session['NAS_IDENTIFIER'];
				$ipv4 = $session['FRAMED_IP_ADDRESS'];
				$patternToAvoid = '/^10\.[1-4]\.\d{1,3}\.\d{1,3}$/';
				$match = preg_match($patternToAvoid, $ipv4);
				log_message('info', '@ajaxAssignDynamicIP|sessions:'.count($sessions['data']).'|nasName:'.$nasName.'|ipv4:'.$ipv4.'|match:'.json_encode($match));
				if ($match == 1) {
					$data['invalidIP'] = '1';
					echo json_encode($data);
				} else {
					$data['invalidIP'] = '0';
					$bngData = $this->bngcounter->fetchBNGData($nasName,
						$this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA);
					log_message('info', '@ajaxAssignDynamicIP|bngData:'.json_encode($bngData));
					if (intval($bngData['ASSIGNED_IP']) < intval($bngData['MAX_IP'])) {
						$data['unavailableIP'] = '0';
						$data['nasIdentifier'] = $nasName;
						echo json_encode($data);
					} else {
						$data['unavailableIP'] = '1';
						$data['nasIdentifier'] = $nasName;
						echo json_encode($data);
					}
				}
			}
		} else {

		}
	}

	public function isConnectedToRm($theUrl) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $theUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 9);
		$output = curl_exec($ch);
		curl_close($ch);
		log_message('debug', 'rm connection check: '.$theUrl.'|from curl:'.json_encode($output));
		if ($output !== false) {
			$this->load->library('rm');
			$someRandomUsername = 'abc01@globelines.com.ph';
			try {
				$testClient = new SoapClient('http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB);
				$testResult = $testClient->wsGetSubscriberProfileByID($testClient, $someRandomUsername);
				if (!isset($testResult->return->responseCode) || is_null($testResult->return->responseCode)) {
					log_message('debug', 'rm connection check function test: no reply returned (wsGetSubscriberProfileByID)');
					return false;
				} else {
					if ($testResult->return->responseCode == '') {
						log_message('debug', 'rm connection check function test: returned blank responseCode (wsGetSubscriberProfileByID)');
						return false;
					}
				}
				log_message('debug', 'rm connection check function test ok');
			} catch (Exception $e) {
				log_message('debug', 'exception @ isConnectedToRm:'.json_encode($e));
				return false;
			}
		}
		return $output === false ? false : true;
	}
	public function isConnectedToRmV2() {
		$rmUrl1 = 'http://'.$this->RMAPIHOST.':'.$this->RMAPIPORT.'/'.$this->RMAPISTUB;
		$rmUrl2 = 'http://'.$this->RMSECONDARYAPIHOST.':'.$this->RMSECONDARYAPIPORT.'/'.$this->RMSECONDARYAPISTUB;
		$this->load->library('rm');
		$someRandomUsername = 'abc001@globelines.com.ph';
		$isPrimaryOk = true;
		$isSecondaryOk = true;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $rmUrl1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 9);
		$output = curl_exec($ch);
		curl_close($ch);
		log_message('debug', 'rm connection check: '.$rmUrl1.'|from curl:'.json_encode($output));
		if ($output === false) {
			$isPrimaryOk = false;
		} else { //checking at url1 returns not false at curl, check functions
			try {
				$testClient1 = new SoapClient($rmUrl1);
				$testResult1 = $testClient1->wsGetSubscriberProfileByID($testClient1, $someRandomUsername);
				if (!isset($testResult1->return->responseCode) || is_null($testResult1->return->responseCode)) {
					log_message('debug', 'rm connection check function test: no reply returned (wsGetSubscriberProfileByID)');
					$isPrimaryOk = false;
				} else {
					if ($testResult1->return->responseCode == '') {
						log_message('debug', 'rm connection check function test: returned blank responseCode (wsGetSubscriberProfileByID)');
						$isPrimaryOk = false;
					}
				}
			} catch (Exception $e) {
				log_message('debug', 'exception at isConnectedToRmV2:'.json_encode($e));
				$isPrimaryOk = false;
			}
		}

		// $isPrimaryOk = false; //this is to force using secondary

		if ($this->USESECONDARYRMAPI) {
			//check secondary api
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $rmUrl2);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 9);
			$output = curl_exec($ch);
			curl_close($ch);
			log_message('debug', 'rm connection check: '.$rmUrl2.'|from curl:'.json_encode($output));
			if ($output === false) {
				$isSecondaryOk = false;
			} else { //checking at url2 returns not false at curl, check functions
				try {
					$testClient2 = new SoapClient($rmUrl2);
					$testResult2 = $testClient2->wsGetSubscriberProfileByID($testClient2, $someRandomUsername);
					if (!isset($testResult2->return->responseCode) || is_null($testResult2->return->responseCode)) {
						log_message('debug', 'rm connection check function test: no reply returned (wsGetSubscriberProfileByID)');
						$isSecondaryOk = false;
					} else {
						if ($testResult2->return->responseCode == '') {
							log_message('debug', 'rm connection check function test: returned blank responseCode (wsGetSubscriberProfileByID)');
							$isSecondaryOk = false;
						}
					}
				} catch (Exception $e) {
					log_message('debug', 'exception at isConnectedToRmV2:'.json_encode($e));
					$isSecondaryOk = false;
				}
			}

			if ($isPrimaryOk) {
				log_message('debug', '____________________using: '.$rmUrl1);
				return $testClient1;
			} else {
				if ($isSecondaryOk) {
					log_message('debug', '____________________using: '.$rmUrl2);
					return $testClient2;
				} else {
					return false;
				}
			}
		} else {
			if ($isPrimaryOk) {
				log_message('debug', '____________________using: '.$rmUrl1);
				return $testClient1;
			} else {
				return false;
			}
		}
	}
	public function isConnectedToMainDb($theUsername, $thePassword, $theHost, $thePort, $theSchema) {
		$connected = true;
		try {
			$theConn = oci_connect($theUsername, $thePassword, $theHost.':'.$thePort.'/'.$theSchema);
			if (!$theConn) {
				$e = oci_error();
				log_message('debug', 'oracle connection error: '.json_encode($e));
				$connected = false;
			}
			log_message('debug', 'oracle connection check: '.$theHost.':'.$thePort.'/'.$theSchema.'|ok');
		} catch (Exception $e) {
			log_message('debug', 'exception at oracle connection check :'.json_encode($e));
			$connected = false;
		}
		return $connected;
	}
	public function isConnectedToMainDbV2() {
		$connected = true;
		try {
			$theConn = oci_connect($this->SESSIONUSERNAME, $this->SESSIONPASSWORD, $this->SESSIONHOST.':'.$this->SESSIONPORT.'/'.$this->SESSIONSCHEMA);
			if (!$theConn) {
				$e = oci_error();
				log_message('debug', 'oracle connection error: '.json_encode($e));
				$connected = false;
			}
			log_message('debug', '____________________oracle connection check: '.$this->SESSIONHOST.':'.$this->SESSIONPORT.'/'.$this->SESSIONSCHEMA.'|ok');
		} catch (Exception $e) {
			log_message('debug', 'exception at oracle connection check :'.json_encode($e));
			$connected = false;
		}
		return $connected;
	}
	public function proceedWithAction($db) {
		// public function proceedWithAction($db, $rm) {
		$proceed = true;
		$errorMsg = '';
		// RM Dependencies 6/21/2019
		if ($db == false) {
			$errorMsg = 'No connection Oracle ('.$this->SESSIONHOST.':'.$this->SESSIONPORT.'/'.$this->SESSIONSCHEMA2.') ';
		// if ($rm == false && $db == false) {
		// 	$errorMsg = 'No connection Oracle ('.$this->SESSIONHOST.':'.$this->SESSIONPORT.'/'.$this->SESSIONSCHEMA2.') '.
		// 		'and RM ('.$this->RMDBHOST.':'.$this->RMDBPORT.'). Please reload page to re-check connections.';
			$proceed = false;
		} 
		 //  else if ($rm == false && $db == true) {
			// $errorMsg = 'No connection to RM ('.$this->RMDBHOST.':'.$this->RMDBPORT.'). Please reload page to re-check connection.';
			// $proceed = false; }
		//  	else if ($rm == true && $db == false) {
		// 	$errorMsg = 'No connection to Oracle ('.$this->SESSIONHOST.':'.$this->SESSIONPORT.'/'.$this->SESSIONSCHEMA.'). Please reload page to re-check connection.';
		// 	$proceed = false;
		// }
		return array(
			'go' => $proceed,
			'msg' => $errorMsg);
	}
	
}
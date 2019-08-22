<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

	public function soap5() {
		$url = '10.174.241.65:8080/NPM_API-11.1.1.5/user_mgmt?WSDL';
		echo $url.'<br />';
		echo 'functions:<br />';
		$login = 'lek';
		$password = 'seron';
		$options = array(
			'location' => 'http://'.$url,
			'uri' => 'http://10.174.241.65:8080/NPM_API-11.1.1.5/',
			'login' => $login,
			'password' => $password,
			'trace' => 1,
			'exceptions' => 0);
		$client = new SoapClient('http://'.urlencode($login).':'.urlencode($password).'@'.$url, $options);
		$fxns = $client->__getFunctions();
		for ($i = 0; $i < count($fxns); $i++) {
			echo json_encode($fxns[$i]).'<br />';
		}
	}
	public function soapget($realm = null, $username = null, $withRealm = 1) {
		if (is_null($username) || is_null($realm)) {
			echo 'no username and/or realm';
			return;
		}
		$url = '10.174.241.65:8080/NPM_API-11.1.1.5/user_mgmt?WSDL';
		echo $url.'<br />';
		echo 'functions:<br />';
		$login = 'lek';
		$password = 'seron';
		$options = array(
			'location' => 'http://'.$url,
			'uri' => 'http://10.174.241.65:8080/NPM_API-11.1.1.5/',
			'login' => $login,
			'password' => $password,
			'trace' => 1,
			'exceptions' => 0);
		$client = new SoapClient('http://'.urlencode($login).':'.urlencode($password).'@'.$url, $options);
		$cn = $withRealm == 1 ? $username.'@'.$realm : $username;
		$subs = $client->getSubscriberAccount($cn);
		echo json_encode($subs);
	}
	public function decryptpassword() {
		log_message('info', '@decryptpassword');
		$enc = $this->input->post('enc');
		log_message('info', json_encode($enc));
		$plaintext = null;
		if ($enc !== false && $enc !== '') {
			$this->load->library('encrypt');
			$plaintext = $this->encrypt->decode(trim($enc));
		}
		$this->load->view('decrypt', array('plaintext' => $plaintext));
	}
	public function getsysdata($username = null) {
		if (!is_null($username)) {
			$this->load->model('sysusermain');
			$sysuserData = $this->sysusermain->fetchSysuserByUsername($username);
			echo json_encode($sysuserData);
		} else {
			echo 'no username given';
		}
	}
	public function getsubsdata($username = null, $realm = null) {
		if (is_null($username) || is_null($realm)) {
			echo 'some input missing';
		} else {
			$this->load->model('subscribermain');
			$subscriber = $this->subscribermain->findByUserIdentity($username.'@'.$realm);
			echo json_encode($subscriber);
		}
	}
	public function counts($realm = null) {
		$this->utility = $this->load->database('utility', true);
		$this->utility->db_select();
		$sql = "select TBLRADIUSPOLICY.RADIUSPOLICYNAME as SERVICE, count(TBLCUSTOMER.USER_IDENTITY) as COUNT from TBLRADIUSPOLICY ".
			"left join TBLCUSTOMER on TBLCUSTOMER.RADIUSPOLICY = TBLRADIUSPOLICY.RADIUSPOLICYNAME and TBLCUSTOMER.CUSTOMERSTATUS = 'Active' ".(is_null($realm) ? "" : " and TBLCUSTOMER.RBREALM = '".$realm."' ").
			"group by TBLRADIUSPOLICY.RADIUSPOLICYNAME order by TBLRADIUSPOLICY.RADIUSPOLICYNAME ASC";
		$query = $this->utility->query($sql);
		echo json_encode($query->result_array());
	}
	public function conc() {
		$this->utility = $this->load->database('utility', true);
		$this->utility->db_select();
		$sql = "select CONCUSERID from TBLCONCURRENTUSERS";
		$query = $this->utility->query($sql);
		echo json_encode($query->result_array());
	}
	public function delsess($username = null, $realm = null) {
		//$wsdl = 'http://10.188.119.114:48050/eliteradius/services/eliteRadiusDynAuthWS?wsdl';
		$wsdl = 'http://10.188.119.26:8889/eliteradius/services/eliteRadiusDynAuthWS?wsdl';
		try {
			$client = new SoapClient($wsdl);
			$result = $client->requestCOAExt($username.'@'.$realm);
			echo json_encode($result);
		} catch (Exception $e) {
			echo json_encode($e);
		}
	}
	public function logdir() {
		$this->utility = $this->load->database('utility', true);
		$this->utility->db_select();
		$sql = "select value from v$parameter where name like '%background_dump_dest%'";
		$query = $this->utility->query($sql);
		echo json_encode($query->result_array());
	}

	public function index() {
		/*
		$this->output->enable_profiler(FALSE);
		$sections = array(
		    //'config'  => TRUE,
		    'queries' => TRUE
	    );
		$this->output->set_profiler_sections($sections);
		*/
		/*
		$this->load->model('subscriberaudittrail');
		//$result = $this->subscriberaudittrail->logSubscriberSessionDeletion('cn', 'realm', true, 'sysuser', '127.0.0.1', mktime());
		//$result = $this->subscriberaudittrail->logSubscriberSessionDeletion('cn', 'realm', false, 'sysuser', '127.0.0.1', null);
		//$result = $this->subscriberaudittrail->logSubscriberPasswordReset('cn', 'realm', 'newpassword', 'sysuser', '127.0.0.1', mktime());
		//$result = $this->subscriberaudittrail->logSubscriberPasswordReset('cn', 'realm', 'newpassword', 'sysuser', '127.0.0.1', null);
		//$result = $this->subscriberaudittrail->logSubscriberPasswordChange('cn', 'realm', 'newpassword', 'sysuser', '127.0.0.1', mktime());
		//$result = $this->subscriberaudittrail->logSubscriberPasswordChange('cn', 'realm', 'newpassword', 'sysuser', '127.0.0.1', null);
		$user = array(
			'cn' => 'cn', 'realm' => 'realm', 'pwd' => 'pwd', 'acctplan' => 'acctplan', 'status' => 'status', 'name' => 'name', 'orderno' => 'orderno', 'svcno' => 'svcno', 
			'enabled' => 'enabled', 'svccode' => 'svccode', 'ipaddr' => 'ipaddr', 'netaddr' => 'netaddr', 'addtlscv1' => 'addtlscv1', 'addtlscv2' => 'addtlscv2', 
			'remarks' => 'the quick brown fox jumps over the lazy dog. the quick brown fox jumps over the lazy dog. the quick brown fox jumps over the lazy dog.');
		$user2 = array('cn' => 'cn', 'realm' => 'realm', 'pwd' => 'pwd2', 'acctplan' => 'acctplan2', 'status' => 'status2', 'name' => 'name2', 'orderno' => 'orderno2', 'svcno' => 'svcno3', 
			'enabled' => 'enabled2', 'svccode' => 'svccode2', 'ipaddr' => 'ipaddr2', 'netaddr' => 'netaddr2', 'addtlscv1' => 'addtlscv1-2', 'addtlscv2' => 'addtlscv2-2', 
			'remarks' => 'the quick brown fox jumps over the lazy dog. the quick brown fox jumps over the lazy dog. the quick brown fox jumps over the lazy dog.');
		$start = 0;
		$max = 5;
		$order = array('column' => 'timestamp', 'dir' => 'asc');
		//$result = $this->subscriberaudittrail->logSubscriberCreation($user, 'sysuser', '127.0.0.1', mktime());
		//$result = $this->subscriberaudittrail->logSubscriberModification($user, $user2, 'sysuser', '127.0.0.1', null, false);
		//$result = $this->subscriberaudittrail->findAllSubscriberAuditTrail($start, $max);
		//$result = $this->subscriberaudittrail->countSubscriberAuditTrails();
		$startdate = mktime(0, 0, 0 , 2, 20, 2014);
		$enddate = mktime(0, 0, 0, 2, 22, 2014);
		$bySysuser = 'sysuser';
		$action = 'P';
		$username = 'cn';
		$ipaddress = '127.0.0.1';
		//$result = $this->subscriberaudittrail->findAllSubscriberAuditTrailWithParam($startdate, $enddate, $bySysuser, $action, $username, $ipaddress, $start, $max, $order);
		//$result = $this->subscriberaudittrail->countSubscriberAuditTrailsWithParam($startdate, $enddate, $bySysuser, $action, $username, $ipaddress);
		echo json_encode($result);
		*/
		/*
		$this->load->model('sysuseractivitylog');
		$page = 'page';
		$path = 'the\path';
		$sysuser = 'sysuser';
		$ipaddress = '127.0.0.1';
		$sysip = '127.0.0.2';
		$address = '127.0.0.3';
		$service = 'service';
		$type = 'type';
		$realm = 'realm';
		$date = mktime();
		$cn = 'cn';
		$group = 'group';
		*/
		//$result = $this->sysuseractivitylog->logSysuserIllegalPageAccess($page, $path, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logSysuserCreation($cn, $group, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logSysuserModification($cn, $group, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logSysuserDeletion($cn, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logSysuserForcedLogOut($cn, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logSysuserBlock($cn, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logSysuserBlock($cn, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logSysuserUnblock($cn, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logSysuserUnblock($cn, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logSysuserChangePassword($cn, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logSysuserChangePassword($cn, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logUsergroupCreation($group, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logUsergroupCreation($group, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logUsergroupModification($group, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logUsergroupModification($group, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logUsergroupDeletion($group, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logUsergroupDeletion($group, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logSysipCreation($sysip, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logSysipCreation($sysip, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logSysipModification($sysip, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logSysipModification($sysip, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logSysipDeletion($sysip, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logSysipDeletion($sysip, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logServiceCreation($service, $type, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logServiceCreation($service, $type, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logServiceModification($service, $type, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logServiceModification($service, $type, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logServiceDeletion($service, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logServiceDeletion($service, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logRealmCreation($realm, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logRealmCreation($realm, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logRealmModification($realm, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logRealmModification($realm, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logRealmDeletion($realm, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logRealmDeletion($realm, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logIpAddressCreation($address, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logIpAddressCreation($address, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logIpAddressModification($address, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logIpAddressModification($address, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logIpAddressDeletion($address, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logIpAddressDeletion($address, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logIpAddressFreeup($address, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logIpAddressFreeup($address, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logNetAddressCreation($address, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logNetAddressCreation($address, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logNetAddressModification($address, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logNetAddressModification($address, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logNetAddressDeletion($address, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logNetAddressDeletion($address, $sysuser, $ipaddress, null);
		//$result = $this->sysuseractivitylog->logNetAddressFreeup($address, $sysuser, $ipaddress, $date);
		//$result = $this->sysuseractivitylog->logNetAddressFreeup($address, $sysuser, $ipaddress, null);
		/*
		$start = 0;
		$max = 10;
		//$result = $this->sysuseractivitylog->findAllSysuserActivityLogs($start, $max);
		//$result = $this->sysuseractivitylog->countSysuserActivityLogs();
		$startdate = mktime(0, 0, 0, 2, 20, 2014);
		$enddate = mktime(0, 0, 0, 2, 22, 2014);
		$data = null;
		$action = 'Create';
		$bySysuser = 'sysuser';
		$order = array('column' => 'timestamp', 'dir' => 'asc');
		*/
		//$result = $this->sysuseractivitylog->countSysuserActivityLogsWithParams($startdate, $enddate, $data, $action, $bySysuser, $ipaddress);
		//echo $result.'<br />';
		//$result = $this->sysuseractivitylog->findAllSysuserActivityLogsWithParams($startdate, $enddate, $data, $action, $bySysuser, $ipaddress, $start, $max, $order);
		//echo json_encode($result);
		//$this->load->model('testmodel');
		//$this->testmodel->insertToSysuser();
		/*
		$status = 'A';
		$realm = 'realm';
		$service = 'service';
		$bandwidth = 'bw';
		$this->load->model('subscriber');
		echo json_encode($this->subscriber->countInactiveSubscribersWithService($service, $realm));
		*/
		//echo json_encode($this->subscriber->countActiveSubscribersWithService($realm, $service));
		//echo json_encode($this->subscriber->countSubscribersWithBandwidth($realm, $bandwidth));
		//echo json_encode($this->subscriber->reportSubscribersWithBandwidth($realm, $bandwidth, $start, $max));
		//echo json_encode($this->subscriber->countSubscribersWithStaticIpAndMultistaticIp($realm));
		//echo json_encode($this->subscriber->reportSubscribersWithStaticIpAndMultistaticIp($realm, $start, $max));
		//echo json_encode($this->subscriber->countSubscribersWithStaticIp($realm));
		//echo json_encode($this->subscriber->reportSubscribersWithStaticIp($realm, $start, $max));
		//echo json_encode($this->subscriber->countSubscribersCreatedWithinDates($realm, mktime(0, 0, 0, 2, 20, 2014), mktime(0, 0, 0, 2, 24, 2014)));
		//echo json_encode($this->subscriber->reportSubscribersCreatedWithinDates(null, mktime(0, 0, 0, 2, 20, 2014), mktime(0, 0, 0, 2, 24, 2014), $start, $max));
		//echo json_encode($this->subscriber->countSubscribersCreatedOnDate($realm, $date));
		//echo json_encode($this->subscriber->reportSubscribersCreatedOnDate(null, mktime(), $start, $max));
		//echo json_encode($this->subscriber->countSubscribersWithService($realm, $service));
		//echo json_encode($this->subscriber->reportSubscribersWithService($realm, $service, $start, $max));
		//echo json_encode($this->subscriber->countSubscribersWithStatus($realm, $status));
		//echo json_encode($this->subscriber->reportSubscribersWithStatus($realm, $status, $start, $max));
		//echo json_encode($this->subscriber->countSubscribersInRealm('realm'));
		//echo json_encode($this->subscriber->findSubscribersInRealm('realm', $start, $max));
		//echo json_encode($this->subscriber->findSubscriber('cn'));
		//echo json_encode($this->subscriber->countSubscribers());
		//echo json_encode($this->subscriber->findAll(0, 10));









		//$this->load->model('sysuser');
		//$datestr = '2014-02-22 08:39:17';
		//$d = strtotime($datestr);
		//echo date('U', $d);
		//echo json_encode($this->sysuser->countFailedSysuserLoginAttemptsWithParams(mktime(0, 0, 0, 2, 20, 2014), mktime(0, 0, 0, 2, 24, 2014), 'cn1', '127.0.0.1', 1));
		//echo json_encode($this->sysuser->findFailedSysuserLoginAttemptsWithParams(null, null, null, '127.0.0.1', null, 0, 10, array('column' => 'timestamp', 'dir' => 'asc')));
		//echo json_encode($this->sysuser->countFailedSysuserLoginAttempts());
		//echo json_encode($this->sysuser->findFailedSysuserLoginAttempts(0, 10));
		//echo json_encode($this->sysuser->getLoggedInCountsInDates(mktime(0, 0, 0, 2, 20, 2014), mktime(0, 0, 0, 2, 24, 2014), 'hostname', 0, 10));
		//echo json_encode($this->sysuser->countLoggedInCountsInDates(mktime(0, 0, 0, 2, 20, 2014), mktime(0, 0, 0, 2, 24, 2014), 'hostname'));
		//echo json_encode($this->sysuser->getLoggedInCountsInDay(22, 2, 2014, 'hostname'));
		//echo json_encode($this->sysuser->getLoggedInCountsInDay($day, $month, $year, $hostname));
		//echo json_encode($this->sysuser->countActiveSysuserUsagesByIpAddress('127.0.0.1'));
		//echo json_encode($this->sysuser->sumSesstimeOfActiveSysuserUsagesByIpAddress('127.0.0.1'));
		//echo json_encode($this->sysuser->findActiveSysuserUsagesByIpAddress('127.0.0.1', 0, 10));
		//echo json_encode($this->sysuser->countInactiveSysuserUsagesByIpAddress('127.0.0.2'));
		//echo json_encode($this->sysuser->sumSesstimeOfInactiveSysuserUsagesByIpAddress('127.0.0.2'));
		//echo json_encode($this->sysuser->findInactiveSysuserUsagesByIpAddress('127.0.0.2', 0, 10));
		//echo json_encode($this->sysuser->countSysuserUsagesByIpAddress('127.0.0.1'));
		//echo json_encode($this->sysuser->sumSesstimeOfSysuserUsagesByIpAddress('127.0.0.2'));
		//echo json_encode($this->sysuser->findSysuserUsagesByIpAddress('127.0.0.2', 0, 10));
		//echo json_encode($this->sysuser->countSysuserUsagesLoggedInAtTime(2014, 2, 22));
		//echo json_encode($this->sysuser->sumSesstimeOfSysuserUsagesLoggedInAtTime(2014, 2, 22));
		//echo json_encode($this->sysuser->findSysuserUsagesLoggedInAtTime(2014, 2, 22, 0, 10));
		//echo json_encode($this->sysuser->countSysuserUsagesByLoginDate(2014, 2, 20, 22));
		//echo json_encode($this->sysuser->sumSesstimeOfSysuserUsagesByLoginDate(2014, 2, 20, 22));
		//echo json_encode($this->sysuser->findSysuserUsagesByLoginDate(2014, 2, 20, 22, 0, 10));
		//echo json_encode($this->sysuser->cleanPasswordHistory('cn1', 3));
		//echo json_encode($this->sysuser->recordPasswordChange('cn1', 'new password 8', mktime()));
		//echo json_encode($this->sysuser->passwordIsInHistory('new password', 'cn1', 5));
		//echo json_encode($this->sysuser->getPasswordHistory('cn1', 5));
		//echo json_encode($this->sysuser->findSysuserPasswordHistory('new password', 'cn1', 5));
		//echo json_encode($this->sysuser->recordSysuserLogoutNoSession('cn2', mktime()));
		//echo json_encode($this->sysuser->recordSysuserLogout('cn1', 'sessioncn1', mktime()));
		//echo json_encode($this->sysuser->markSysuserAsNotLoggedIn('cn10', 'sessionidcn10'));
		//echo json_encode($this->sysuser->markSysuserAsLoggedIn('cn10', 'sessionidcn10'));
		//echo json_encode($this->sysuser->incrementSysuserLoginTriesCountWithThreshold('cn1', 6));
		//echo json_encode($this->sysuser->incrementSysuserLoginTriesCount('cn1'));
		//echo json_encode($this->sysuser->resetSysuserLoginTriesCount('cn1'));
		//echo json_encode($this->sysuser->getSysuserSessionId('cn1'));
		//echo json_encode($this->sysuser->recordSysuserLogin('cn2', 'sessioncn2', mktime(), '127.0.0.1'));
		//echo json_encode($this->sysuser->recordSysuserLoginAttempt('cn1', 'password used', '127.0.0.1', 1, mktime()));
		//echo json_encode($this->sysuser->recordPasswordChange('cn1', 'new password', mktime(0, 0, 0, 2, 28, 2014)));
		//echo json_encode($this->sysuser->findAllLoggedIn(0, 10));
		//echo json_encode($this->sysuser->setSysuserBlock('cn1', false));
		//echo json_encode($this->sysuser->findSysuser('cn'));
		//echo json_encode($this->sysuser->countLoggedInSysusers());
		//echo json_encode($this->sysuser->countSysusers());
		//echo json_encode($this->sysuser->findAll(0, 10));
		//echo json_encode($this->sysuser->deleteSysuser('cn61'));
		//echo json_encode($this->sysuser->changeSysuserPassword('cn1', 'new password'));
		//echo json_encode($this->sysuser->modifySysuserUsergroup('cn1', 'usergroupB'));
		/*
		echo $this->sysuser->createSysuser('cn9', 'usergroupD', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn10', 'usergroupD', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn11', 'usergroupD', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn12', 'usergroupE', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn13', 'usergroupE', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn14', 'usergroupE', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn15', 'usergroupE', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn16', 'usergroupE', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn17', 'usergroupF', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn18', 'usergroupF', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn19', 'usergroupF', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn20', 'usergroupF', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn21', 'usergroupF', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn22', 'usergroupF', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn23', 'usergroupG', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn24', 'usergroupG', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn25', 'usergroupG', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn26', 'usergroupG', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn27', 'usergroupG', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn28', 'usergroupH', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn29', 'usergroupH', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn30', 'usergroupH', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn31', 'usergroupH', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn32', 'usergroupH', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn33', 'usergroupH', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn34', 'usergroupH', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn35', 'usergroupI', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn36', 'usergroupI', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn37', 'usergroupI', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn38', 'usergroupI', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn39', 'usergroupI', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn40', 'usergroupI', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn41', 'usergroupI', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn42', 'usergroupI', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn43', 'usergroupJ', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn44', 'usergroupJ', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn45', 'usergroupJ', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn46', 'usergroupJ', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn47', 'usergroupJ', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn48', 'usergroupJ', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn49', 'usergroupJ', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn50', 'usergroupK', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn51', 'usergroupK', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn52', 'usergroupK', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn53', 'usergroupK', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn54', 'usergroupK', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn55', 'usergroupK', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn56', 'usergroupK', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn57', 'usergroupL', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn58', 'usergroupL', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn59', 'usergroupL', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn60', 'usergroupL', 'password').'<br />';
		echo $this->sysuser->createSysuser('cn61', 'usergroupL', 'password').'<br />';
		*/
	}
	
	public function freeip($ipaddress = null) {
		if (!is_null($ipaddress)) {
			$this->load->model('ipaddress');
			$result = $this->ipaddress->freeUp($ipaddress);
			echo $result ? 'done' : 'failed';
		} else {
			echo 'no ipaddress';
		}
	}
	public function freenet($netaddress = null, $subnet = null) {
		if (!is_null($netaddress)) {
			$this->load->model('netaddress');
			$result = $this->netaddress->freeUp($netaddress.'/'.$subnet);
			echo $result ? 'done' : 'failed';
		} else {
			echo 'no netdress';
		}
	}
	public function sess() {
		$this->session->set_userdata('mine', 'blabla');
		echo json_encode($this->session->all_userdata());
	}
	public function sess2() {
		echo json_encode($this->session->all_userdata());	
	}

	public function soap1() {
		$url = 'http://222.127.121.130:8089/eliteaaa/services/SessionManagerWS?wsdl';
		echo $url.'<br />';
		echo 'functions:<br />';
		$options = array(
			'location' => $url,
			'uri' => 'http://222.127.121.130:8089/eliteaaa/services/');
		$client = new SoapClient($url, $options);
		$fxns = $client->__getFunctions();
		var_dump($fxns);
	}
	public function soap2() {
		$url = 'http://222.127.121.130:8091/eliteradius/services/eliteRadiusDynAuthWS?wsdl';
		echo $url.'<br />';
		echo 'functions:<br />';
		$options = array(
			'location' => $url,
			'uri' => 'http://222.127.121.130:8091/eliteradius/services/',
			'login' => 'lek',
			'password' => 'seron');
		$client = new SoapClient($url, $options);
		$fxns = $client->__getFunctions();
		var_dump($fxns);
	}
	public function soap3() {
		$url = 'http://10.188.119.114:8089/eliteaaa/services/SessionManagerWS?wsdl';
		echo $url.'<br />';
		echo 'functions:<br />';
		$options = array(
			'location' => $url,
			'uri' => 'http://10.188.119.114:8089/eliteaaa/services/');
		$client = new SoapClient($url, $options);
		$fxns = $client->__getFunctions();
		var_dump($fxns);
	}
	public function soap4() {
		$url = 'http://10.174.241.65:8080/NPM_API-11.1.1.5/user_mgmt?WSDL';
		echo $url.'<br />';
		echo 'functions:<br />';
		$options = array(
			'location' => $url,
			'uri' => 'http://10.174.241.65:8080/NPM_API-11.1.1.5/',
			'login' => 'lek',
			'password' => 'seron');
		$client = new SoapClient($url, $options);
		$fxns = $client->__getFunctions();
		var_dump($fxns);
	}
	





	public function next() {
		$this->load->view('sysuser_accounts');
	}
	public function update() {
		$this->load->model('subscribermain');
		$subs = $this->subscribermain->findByUserIdentity('jwilson@GLOBELINES.COM.PH');
		echo '<table>';
		foreach(array_keys($subs) as $key) {
			echo '<tr>';
			echo '<td>'.$key.'</td><td>'.$subs[$key].'</td>';
			echo '</tr>';
		}
	}
	public function writecsv() {
		$this->load->model('util');
		$this->load->model('subscribermain');
		$data = [];
		$data[] = $this->subscribermain->findByUserIdentity('jwilson@GLOBELINES.COM.PH');
		$data[] = $this->subscribermain->findByUserIdentity('jadams@GLOBELINES.COM.PH');
		$data[] = $this->subscribermain->findByUserIdentity('pbriggs@GLOBELINES.COM.PH');
		$data[] = $this->subscribermain->findByUserIdentity('ctaub@GLOBELINES.COM.PH');
		$this->util->writeToDeletedSubscriberFile($data, mktime());
	}
	public function serviceactiveusers() {
		$this->load->model('services');
		//$counts = $this->services->fetchActiveUserCountsPerService('GLOBELINES.COM.PH');
		$counts = $this->services->fetchInactiveUserCountsPerService('GLOBELINES.COM.PH');
		echo json_encode($counts);
	}
	public function netex() {
		$this->load->model('netaddress');
		$addr = '128.0.0.38/30';
		//$addr = '128.0.0.12';
		echo json_encode($this->netaddress->netExists($addr));
	}
	public function bucket() {
		$this->load->model('tbldslbucket');
		$res = $this->tbldslbucket->fetchPagesForRole(1);
		echo json_encode($res).'<br /><br />';
		$res2 = $this->tbldslbucket->fetchPagesNotInRole(1);
		echo json_encode($res2).'<br />';
	}
	public function sysuser() {
		$this->load->model('sysusermain');
		//$this->sysusermain->create('admin2', 'password', 'sysuser', 'generic', 'generic@email.com', 1);
		$role = 7; //supergroup
		$this->load->model('tbldslbucket');
		$this->load->model('page');
		$this->load->model('realm');
		$pages = $this->page->fetchAll();
		for ($i = 0; $i < count($pages); $i++) {
			$this->tbldslbucket->addPageToRole($role, $pages[$i]['PAGEID']);
		}
		$realms = $this->realm->fetchAll(null, null, null);
		for ($i = 0; $i < count($realms); $i++) {
			$this->tbldslbucket->addRealmToRole($role, $realms[$i]['REALMID']);
		}
	}
	public function xls() {
		$this->load->model('testmodel');
		$this->testmodel->excelTest();
	}
	public function soap() {
		$this->load->model('testmodel');
		echo json_encode($this->testmodel->soapTest());
	}
	public function randSubs() {
		$this->load->model('testmodel');
		$this->testmodel->randSubs();
	}
	public function page() {
		//get services
		$allServices = array('service1', 'service2', 'service3', 'service4');
		$additionalServices = array('service1', 'service2', 'service3', 'service4');
		$additionalServices2 = array('service1', 'service2', 'service3', 'service4');
		//**********************************************************************************
		$data = array(
			'allServicesFilter' => $allServices,
			'additionalServicesFilter' => $additionalServices,
			'additionalServices2Filter' => $additionalServices2,
			'service' => '',
			'message' => '',
			'error' => '');
		$this->load->view('create_user_account', $data);
	}
	public function ul($step = null) {
		if (is_null($step)) { //via form
			$step = $this->input->post('step');
			if ($step == 'upload') { //process uploaded file
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'dslutility/uploads/';
				//array('application/vnd.ms-excel', 'application/octet-stream', 'application/excel', '\"application/excel\"', '"application/excel"')  application/x-msexcel  
				$config['allowed_types'] = 'application/vnd.ms-excel|application/octet-stream|application/excel|\"application/excel\"|"application/excel"|application/x-msexcel|xls|xlsx';
    			$config['max_size'] = '20000';
    			$this->load->library('upload', $config);
    			if (!$this->upload->do_upload('file')) { //upload fail
    				$error = array('error' => $this->upload->display_errors());
    				log_message('info', json_encode($error));
    			} else { //upload ok
    				$data = array('step' => 'confirm');
    				$uploaded = $this->upload->data();
    				echo json_encode($uploaded);
    				//$this->load->view('bulk_load_users', $data);
    			}
			} else {
				echo 'OTHERS';
			}
		} else { //via url
			$this->load->view('bulk_load_users');
		}
	}
	public function fxn() {
		$this->load->model('subscribermain');
		$cn = 'cn@realm';
		echo json_encode($this->subscribermain->subscriberExists($cn));
	}
	public function vars() {
		//$cn = 'cn@realm@realm';
		/*
		$cn = 'plainCN';
		$index = strrpos($cn, '@');
		echo $index.'-----<br />';
		$uname = $index == false ? $cn : substr($cn, 0, $index);
		echo $uname;
		*/
		$data = [];
		$data['IPADDRESS'] = '127.0.0.1';
		$data['LOCATION'] = 'somewhere';
		echo json_encode($data);
	}
	public function createipaddresses() {
		$this->load->model('ipaddress');
		$gpons = ['Y', 'N'];
		$locations = ['LOCATION1', 'LOCATION2', 'LOCATION3', 'LOCATION4', 'LOCATION5', 'LOCATION6'];
		$description = 'this is an ipaddress';
		for ($i = 0; $i < 255; $i++) {
			$ip = '127.0.2.'.($i + 1);
			//$this->ipaddress->create($ip, $locations[mt_rand(0, count($locations) - 1)], $gpons[mt_rand(0, count($gpons) - 1)], $description);
		}
	}
	public function createnetaddresses() {
		$this->load->model('netaddress');
		$locations = ['LOCATION1', 'LOCATION2', 'LOCATION3', 'LOCATION4', 'LOCATION5', 'LOCATION6'];
		$netsuffixes = ['/24', ''];
		$description = 'this is an netaddress';
		for ($i = 0; $i < 255; $i++) {
			$net = '128.0.1.'.($i + 1).$netsuffixes[mt_rand(0, count($netsuffixes) - 1)];
			//$this->netaddress->create($net, $locations[mt_rand(0, count($locations) - 1)], $description);
		}
	}
	public function iplocations() {
		$this->load->model('ipaddress');
		echo json_encode($this->ipaddress->fetchAllLocations());
	}
	public function unusedips() {
		$this->load->model('ipaddress');
		echo json_encode($this->ipaddress->fetchAllUnused('LOCATION1', 'Y'));
	}
	public function uploads() {
		echo $_SERVER['DOCUMENT_ROOT'].'dslutility/uploads<br />';
		echo $this->input->server('DOCUMENT_ROOT');
	}
	public function ipcheck() {
		echo $this->input->ip_address().'<br />';
		echo $this->input->valid_ip('192.168.0.1') ? 'valid' : 'not valid';
		echo '<br />';

		//$addr = '127.0.0.2/24';
		$addr = '127.0.0.3';
		$index = strrpos($addr, '/');
		if ($index == false) {
			echo $addr.'<br />';
		} else {
			$ippart = substr($addr, 0, $index);
			echo $ippart.'<br />';
		}
	}
	public function viewTest() {
		//$this->load->view('admin_existing_session_notice');
		//$this->load->view('admin_login');
		//$this->load->view('admin_require_password_change_notice');
		$this->load->view('admin');
		//$this->load->view('service');
		//$this->load->view('service_existing_session_notice');
		//$this->load->view('service_require_password_change_notice');
	}
	public function iframeContent() {
		//$this->load->view('welcome');
		//$this->load->view('change_my_password');
		//$this->load->view('search_online_session_npm');
		//$this->load->view('search_concurrent_session');
		//$this->load->view('verify_user_password');
		//$this->load->view('reset_user_password');
		//$this->load->view('change_user_password');
		//$this->load->view('change_sysuser_password');
		//$this->load->view('modify_user_account');
		//$this->load->view('search_result_account');
		//$this->load->view('search_result_realm');
		//$this->load->view('search_result_service');
		//$this->load->view('search_result_account_services_info');
		//$this->load->view('search_result_subscriber');
		//$this->load->view('delete_session_result');
		//$this->load->view('delete_session_result_npm');
		//$this->load->view('sysuser_accounts_search');
		//$this->load->view('sysuser_accounts_create');
		//$this->load->view('sysuser_accounts_modify');
		//$this->load->view('sysuser_groups_create');
		//$this->load->view('sysuser_groups_allowed_pages');
		//$this->load->view('sysuser_groups_allowed_pages_add');
		//$this->load->view('sysuser_groups_modify_days_and_time');
		//$this->load->view('sys_ip_addresses_create');
		//$this->load->view('sys_ip_addresses_modify');
		//$this->load->view('services_create');
		//$this->load->view('realms_modify');
		//$this->load->view('realm_user_accounts_show');
		//$this->load->view('manage_ip_addresses_create');
		//$this->load->view('manage_ip_addresses_delete_range');
		//$this->load->view('manage_net_addresses_create');
		//$this->load->view('manage_net_addresses_delete_range');
		//$this->load->view('display_user_account');
		//$this->load->view('create_user_account2');
		//$this->load->view('confirm_force_delete_session');
		//$this->load->view('confirm_force_delete_session_npm');
		//$this->load->view('create_user_account_result');
		//$this->load->view('modify_user_account_result');
		$this->load->view('logged_out_notice');
	}
}
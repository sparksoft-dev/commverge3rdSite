<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscribermain extends CI_Model {
	private $utility;
	private $extras;
	private $TABLENAME = 'TBLCUSTOMER';
	private $COLUMN_USERIDENTITY = 'USER_IDENTITY';
	private $COLUMN_USERNAME = 'USERNAME';
	private $COLUMN_BANDWIDTH = 'BANDWIDTH';
	private $COLUMN_CUSTOMERSTATUS = 'CUSTOMERSTATUS';
	private $COLUMN_PASSWORD = 'PASSWORD';
	private $COLUMN_CUSTOMERREPLYITEM = 'CUSTOMERREPLYITEM';
	private $COLUMN_CREATEDATE = 'CREATEDATE';
	private $COLUMN_LASTMODIFIEDDATE = 'LASTMODIFIEDDATE';
	private $COLUMN_RADIUSPOLICY = 'RADIUSPOLICY';
	private $COLUMN_RBCUSTOMERNAME = 'RBCUSTOMERNAME';
	private $COLUMN_RBCREATEDBY = 'RBCREATEDBY';
	private $COLUMN_RBADDITIONALSERVICE5 = 'RBADDITIONALSERVICE5';
	private $COLUMN_RBADDITIONALSERVICE4 = 'RBADDITIONALSERVICE4';
	private $COLUMN_RBADDITIONALSERVICE3 = 'RBADDITIONALSERVICE3';
	private $COLUMN_RBADDITIONALSERVICE2 = 'RBADDITIONALSERVICE2';
	private $COLUMN_RBADDITIONALSERVICE1 = 'RBADDITIONALSERVICE1';
	private $COLUMN_RBCHANGESTATUSDATE = 'RBCHANGESTATUSDATE';
	private $COLUMN_RBCHANGESTATUSBY = 'RBCHANGESTATUSBY';
	private $COLUMN_RBACTIVATEDDATE = 'RBACTIVATEDDATE';
	private $COLUMN_RBACTIVATEDBY = 'RBACTIVATEDBY';
	private $COLUMN_RBACCOUNTSTATUS = 'RBACCOUNTSTATUS';
	private $COLUMN_RBSVCCODE2 = 'RBSVCCODE2';
	private $COLUMN_RBSVCCODE = 'RBSVCCODE';
	private $COLUMN_CUSTOMERTYPE = 'CUSTOMERTYPE';
	private $COLUMN_RBSERVICENUMBER = 'RBSERVICENUMBER';
	private $COLUMN_RBCHANGESTATUSFROM = 'RBCHANGESTATUSFROM';
	private $COLUMN_RBSECONDARYACCOUNT = 'RBSECONDARYACCOUNT';
	private $COLUMN_RBUNLIMITEDACCESS = 'RBUNLIMITEDACCESS';
	private $COLUMN_RBTIMESLOT = 'RBTIMESLOT';
	private $COLUMN_RBORDERNUMBER = 'RBORDERNUMBER';
	private $COLUMN_RBREMARKS = 'RBREMARKS';
	private $COLUMN_RBREALM = 'RBREALM';
	private $COLUMN_RBNUMBEROFSESSION = 'RBNUMBEROFSESSION';
	private $COLUMN_RBMULTISTATIC = 'RBMULTISTATIC';
	private $COLUMN_RBIPADDRESS = 'RBIPADDRESS';
	private $COLUMN_RBENABLED = 'RBENABLED';
	private $PASSWORD_LENGTH = 12;
	public $STATUSES = ['A', 'D', 'T', 'Active', 'InActive'];
	
	//defaults (not used anymore, moved to settings.txt file)
	private $NPM_URI = '10.174.241.65:8080/NPM_API-11.1.1.5/';
	private $NPM_URL = '10.174.241.65:8080/NPM_API-11.1.1.5/user_mgmt?WSDL';
	private $NPM_LOGIN = 'lek';
	private $NPM_PASSWORD = 'lek';
	private $NPM_TIMEOUT = 10;

	// Remove Columns 5/20/19
	// public $DEFAULT_COLUMNS = ['ACCOUNT TYPE', 'REALM', 'USERNAME', 'CUSTOMERTYPE', 'STATUS', 'SERVICE', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2',
 //        'ORDER NUMBER', 'SERVICE NUMBER', 'CUSTOMER NAME', 'IP ADDRESS', 'NET ADDRESS', 'REMARKS'];
	
	public $DEFAULT_COLUMNS = ['ACCOUNT TYPE', 'REALM', 'USERNAME', 'STATUS', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2',
        'ORDER NUMBER', 'SERVICE NUMBER', 'CUSTOMER NAME', 'IP ADDRESS', 'NET ADDRESS', 'REMARKS'];

	function __construct() {
		parent::__construct();
		$this->utility = $this->load->database('utility', true);
		$this->extras = $this->load->database('extras', true);
	}

	public function create($s, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		try {
			$now = date('Y-m-d H:i:s', time());
			log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
			$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
			if (!$conn) {
				return false;
			}
			$sql = "insert into TBLCUSTOMER (CUI, USER_IDENTITY, USERNAME, BANDWIDTH, CUSTOMERSTATUS, PASSWORD, CUSTOMERREPLYITEM, CREATEDATE, LASTMODIFIEDDATE, RBACCOUNTPLAN, RADIUSPOLICY, ".
				"RBCUSTOMERNAME, RBCREATEDBY, RBADDITIONALSERVICE5, RBADDITIONALSERVICE4, RBADDITIONALSERVICE3, RBADDITIONALSERVICE2, RBADDITIONALSERVICE1, RBCHANGESTATUSDATE, ".
				"RBCHANGESTATUSBY, RBACTIVATEDDATE, RBACTIVATEDBY, RBACCOUNTSTATUS, RBSVCCODE2, RBSVCCODE, CUSTOMERTYPE, RBSERVICENUMBER, RBCHANGESTATUSFROM, ".
				"RBSECONDARYACCOUNT, RBUNLIMITEDACCESS, RBTIMESLOT, RBORDERNUMBER, RBREMARKS, RBREALM, RBNUMBEROFSESSION, RBMULTISTATIC, RBIPADDRESS, RBENABLED) values ".
				"(:cui, :useridentity, :username, null, :customerstatus, :password, ".
				($s['CUSTOMERREPLYITEM'] == '' || is_null($s['CUSTOMERREPLYITEM']) ? "null" : ":customerreplyitem").", ".
				"TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS'), null, :radiuspolicy, :radiuspolicy, :rbcustomername, :rbcreatedby, null, ".
				(is_null($s['RBADDITIONALSERVICE4']) ? "null" : ":rbadditionalservice4").", null, ".
				(is_null($s['RBADDITIONALSERVICE2']) ? "null" : ":rbadditionalservice2").", ".(is_null($s['RBADDITIONALSERVICE1']) ? "null" : ":rbadditionalservice1").", ".
				"null, null, ".($s['CUSTOMERSTATUS'] == 'Active' ? "TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS')" : "null").", ".
				($s['CUSTOMERSTATUS'] == 'Active' ? ":rbactivatedby" : "null").", :rbaccountstatus, null, :rbsvccode, :customertype, :rbservicenumber, null, null, 1, ".
				"'Al0000-2400', ".(is_null($s['RBORDERNUMBER']) ? "null" : ":rbordernumber").", ".(is_null($s['RBREMARKS']) ? "null" : ":rbremarks").", ".
				":rbrealm, 1, ".(is_null($s['RBMULTISTATIC']) ? "null" : ":rbmultistatic").", ".(is_null($s['RBIPADDRESS']) ? "null" : ":rbipaddress").", :rbenabled)";
			log_message('info', $sql);
			log_message('info', $s['RBACCOUNTPLAN']);
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':cui', strval($s['USER_IDENTITY']));
			oci_bind_by_name($compiled, ':useridentity', strval($s['USER_IDENTITY']));
			oci_bind_by_name($compiled, ':username', strval($s['USERNAME']));
			oci_bind_by_name($compiled, ':customerstatus', strval($s['CUSTOMERSTATUS']));
			oci_bind_by_name($compiled, ':password', strval($s['PASSWORD']));
			if ($s['CUSTOMERREPLYITEM'] == '' || is_null($s['CUSTOMERREPLYITEM'])) {		
				
			} else {
				oci_bind_by_name($compiled, ':customerreplyitem', strval($s['CUSTOMERREPLYITEM']));
			}
			oci_bind_by_name($compiled, ':now', substr($now, 2, strlen($now)));
			oci_bind_by_name($compiled, ":radiuspolicy", strval($s['RBACCOUNTPLAN']));
			oci_bind_by_name($compiled, ':rbcustomername', strval(str_replace("'", "''", $s['RBCUSTOMERNAME'])));
			oci_bind_by_name($compiled, ':rbcreatedby', strval($s['RBCREATEDBY']));
			if (!is_null($s['RBADDITIONALSERVICE4'])) {
				oci_bind_by_name($compiled, ':rbadditionalservice4', strval($s['RBADDITIONALSERVICE4']));
			}
			if (!is_null($s['RBADDITIONALSERVICE2'])) {
				oci_bind_by_name($compiled, ':rbadditionalservice2', strval($s['RBADDITIONALSERVICE2']));
			}
			if (!is_null($s['RBADDITIONALSERVICE1'])) {
				oci_bind_by_name($compiled, ':rbadditionalservice1', strval($s['RBADDITIONALSERVICE1']));
			}
			if ($s['CUSTOMERSTATUS'] == 'Active') {
				oci_bind_by_name($compiled, ':rbactivatedby', strval($s['RBACTIVATEDBY']));
			}
			oci_bind_by_name($compiled, ':rbaccountstatus', strval($s['RBACCOUNTSTATUS']));
			oci_bind_by_name($compiled, ':rbsvccode', strval($s['RBSVCCODE']));
			oci_bind_by_name($compiled, ':customertype', strval($s['CUSTOMERTYPE']));
			oci_bind_by_name($compiled, ':rbservicenumber', strval($s['RBSERVICENUMBER']));
			if (!is_null($s['RBORDERNUMBER'])) {
				oci_bind_by_name($compiled, ':rbordernumber', strval($s['RBORDERNUMBER']));
			}
			if (!is_null($s['RBREMARKS'])) {
				$s['RBREMARKS'] = trim($s['RBREMARKS']);
				oci_bind_by_name($compiled, ':rbremarks', strval(str_replace("'", "''", $s['RBREMARKS'])));
			}
			oci_bind_by_name($compiled, ':rbrealm', strval($s['RBREALM']));
			if (!is_null($s['RBMULTISTATIC'])) {
				oci_bind_by_name($compiled, ':rbmultistatic', strval($s['RBMULTISTATIC']));
			}
			if (!is_null($s['RBIPADDRESS'])) {
				oci_bind_by_name($compiled, ':rbipaddress', strval($s['RBIPADDRESS']));
			}
			oci_bind_by_name($compiled, ':rbenabled', strval($s['RBENABLED']));
			$result = oci_execute($compiled);
			return $result ? true : false;
		} catch (Exception $e) {
			log_message('info', 'error:'.json_encode($e));
			return false;
		}
	}
	public function generateCustomerReplyItemValue($ipv6Address, $ipAddress, $netAddress) {
		$customerReplyItem = null;
		if (is_null($ipv6Address) && is_null($ipAddress) && is_null($netAddress)) { // set customerreplyitem to null (previous line)
			// retain null value
		} else if (is_null($ipv6Address) && is_null($ipAddress) && !is_null($netAddress)) { // should not happen; do nothing, checking is done outside
			// should not happen
		} else if (is_null($ipv6Address) && !is_null($ipAddress) && is_null($netAddress)) { // ip address only
			$customerReplyItem = '0:8='.$ipAddress.',4874:1=OUTSIDE';
		} else if (is_null($ipv6Address) && !is_null($ipAddress) && !is_null($netAddress)) { // ip and net address
			$customerReplyItem = '0:8='.$ipAddress.',0:22='.$netAddress.',4874:1=OUTSIDE';
		} else if (!is_null($ipv6Address) && is_null($ipAddress) && is_null($netAddress)) { // ipv6 address only
			// Edited output for ipv6 only 8/2/2019
			// $customerReplyItem = '4874:159='.$ipv6Address.',4874:1=OUTSIDE';
			$customerReplyItem = '4874:161=pppoe-v6-client1-pd,0:123='.$ipv6Address;
		} else if (!is_null($ipv6Address) && is_null($ipAddress) && !is_null($netAddress)) { //  and net address; should not happen
			// should not happen
		} else if (!is_null($ipv6Address) && !is_null($ipAddress) && is_null($netAddress)) { // ipv6 and ip address
			// do nothing
			// $customerReplyItem = '4874:129='.$ipv6Address.',0:8='.$ipAddress.',4874:1=OUTSIDE';

		} else if (!is_null($ipv6Address) && !is_null($ipAddress) && !is_null($netAddress)) { // ipv6, ip and net address
			// do nothing
			// $customerReplyItem = '4874:129='.$ipv6Address.',0:8='.$ipAddress.',0:22='.$netAddress.',4874:1=OUTSIDE';
		}
		return $customerReplyItem;
	}
	public function update($cn, $s, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		try {
			if ($s['PASSWORD'] == '' || $s['PASSWORD'] == null) {
				$this->load->model('util');
				$s['PASSWORD'] = $this->util->generateRandomString($this->PASSWORD_LENGTH);
			}
			$now = date('Y-m-d H:i:s', time());
			log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
			$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
			if (!$conn) {
				return false;
			}
			$sql = "update TBLCUSTOMER set BANDWIDTH = ".(is_null($s['BANDWIDTH']) ? "null" : ":bandwidth").", CUSTOMERSTATUS = :customerstatus, PASSWORD = :password, ".
				"CUSTOMERREPLYITEM = ".(is_null($s['CUSTOMERREPLYITEM']) ? "null" : ":customerreplyitem").", LASTMODIFIEDDATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS'), ".
				"RBCUSTOMERNAME = :rbcustomername, RBADDITIONALSERVICE2 = ".(is_null($s['RBADDITIONALSERVICE2']) ? "null" : ":rbadditionalservice2").", ".
				"RBADDITIONALSERVICE1 = ".(is_null($s['RBADDITIONALSERVICE1']) ? "null" : ":rbadditionalservice1").", ".
				"RBADDITIONALSERVICE3 = ".(is_null($s['RBADDITIONALSERVICE3']) ? "null" : ":rbadditionalservice3").", ".
				"RBADDITIONALSERVICE4 = ".(is_null($s['RBADDITIONALSERVICE4']) ? "null" : ":rbadditionalservice4").", ".
				"RBADDITIONALSERVICE5 = ".(is_null($s['RBADDITIONALSERVICE5']) ? "null" : ":rbadditionalservice5").", ".
				"RBCHANGESTATUSDATE = ".(is_null($s['RBCHANGESTATUSDATE']) ? 
					"null" : 
					(strpos($s['RBCHANGESTATUSDATE'], 'AM') === false && strpos($s['RBCHANGESTATUSDATE'], 'PM') === false ? 
						"TO_TIMESTAMP(:rbchangestatusdate, 'RR-MM-DD HH24.MI.SS')" : 
						"TO_TIMESTAMP(:rbchangestatusdate, 'DD-MON-RR HH.MI.SS.FF ".substr($s['RBCHANGESTATUSDATE'], -2)."')")).", ".
				"RBCHANGESTATUSBY = ".(is_null($s['RBCHANGESTATUSBY']) ? "null" : ":rbchangestatusby").", ".
				"RBACTIVATEDDATE = ".(is_null($s['RBACTIVATEDDATE']) ? 
					"null" : 
					(strpos($s['RBACTIVATEDDATE'], 'AM') === false && strpos($s['RBACTIVATEDDATE'], 'PM') === false ?
						"TO_TIMESTAMP(:rbactivateddate, 'RR-MM-DD HH24.MI.SS')" :
						"TO_TIMESTAMP(:rbactivateddate, 'DD-MON-RR HH.MI.SS.FF ".substr($s['RBACTIVATEDDATE'], -2)."')")).", ".
				"RBACTIVATEDBY = ".(is_null($s['RBACTIVATEDBY']) ? "null" : ":rbactivatedby").", RBACCOUNTSTATUS = :rbaccountstatus, RBSVCCODE2 = :rbsvccode2, RBSVCCODE = :radiuspolicy, ".
				"RBACCOUNTPLAN = :radiuspolicy, RADIUSPOLICY = :radiuspolicy, CUSTOMERTYPE = :customertype, RBSERVICENUMBER = :rbservicenumber, RBCHANGESTATUSFROM = :rbchangestatusfrom, ".
				"RBSECONDARYACCOUNT = :rbsecondaryaccount, RBUNLIMITEDACCESS = ".(is_null($s['RBUNLIMITEDACCESS']) ? "1" : ":rbunlimitedaccess").", ".
				"RBTIMESLOT = :rbtimeslot, RBORDERNUMBER = ".(is_null($s['RBORDERNUMBER']) ? "null" : ":rbordernumber").", ".
				"RBREMARKS = :rbremarks, RBREALM = :rbrealm, RBNUMBEROFSESSION = ".(is_null($s['RBNUMBEROFSESSION']) ? "1" : ":rbnumberofsession").", ".
				"RBMULTISTATIC = ".(is_null($s['RBMULTISTATIC']) ? "null" : ":rbmultistatic").", RBIPADDRESS = ".(is_null($s['RBIPADDRESS']) ? "null" : ":rbipaddress").", ".
				"RBENABLED = :rbenabled ".
				"where USER_IDENTITY = :cn";
			log_message('info', $sql);
			$compiled = oci_parse($conn, $sql);
			if (!is_null($s['BANDWIDTH'])) {
				oci_bind_by_name($compiled, ':bandwidth', strval($s['BANDWIDTH']));
			}
			oci_bind_by_name($compiled, ':customerstatus', strval($s['CUSTOMERSTATUS']));
			oci_bind_by_name($compiled, ':password', strval($s['PASSWORD']));
			if (!is_null($s['CUSTOMERREPLYITEM'])) {
				oci_bind_by_name($compiled, ':customerreplyitem', strval($s['CUSTOMERREPLYITEM']));
			}
			oci_bind_by_name($compiled, ':now', strval($now));
			oci_bind_by_name($compiled, ':rbcustomername', strval($s['RBCUSTOMERNAME']));
			if (!is_null($s['RBADDITIONALSERVICE2'])) {
				oci_bind_by_name($compiled, ':rbadditionalservice2', strval($s['RBADDITIONALSERVICE2']));
			}
			if (!is_null($s['RBADDITIONALSERVICE1'])) {
				oci_bind_by_name($compiled, ':rbadditionalservice1', strval($s['RBADDITIONALSERVICE1']));
			}
			if (!is_null($s['RBADDITIONALSERVICE3'])) {
				oci_bind_by_name($compiled, ':rbadditionalservice3', strval($s['RBADDITIONALSERVICE3']));
			}
			if (!is_null($s['RBADDITIONALSERVICE4'])) {
				oci_bind_by_name($compiled, ':rbadditionalservice4', strval($s['RBADDITIONALSERVICE4']));
			}
			if (!is_null($s['RBADDITIONALSERVICE5'])) {
				oci_bind_by_name($compiled, ':rbadditionalservice5', strval($s['RBADDITIONALSERVICE5']));
			}
			if (!is_null($s['RBCHANGESTATUSDATE'])) {
				$rbchangestatusdate = (strpos($s['RBCHANGESTATUSDATE'], 'AM') === false && strpos($s['RBCHANGESTATUSDATE'], 'PM') === false) ?
					substr($s['RBCHANGESTATUSDATE'], 2, strlen($s['RBCHANGESTATUSDATE'])) : $s['RBCHANGESTATUSDATE'];
				oci_bind_by_name($compiled, ':rbchangestatusdate', strval($rbchangestatusdate));
			}
			if (!is_null($s['RBCHANGESTATUSBY'])) {
				oci_bind_by_name($compiled, ':rbchangestatusby', strval($s['RBCHANGESTATUSBY']));
			}
			if (!is_null($s['RBACTIVATEDDATE'])) {
				$rbactivateddate = (strpos($s['RBACTIVATEDDATE'], 'AM') === false && strpos($s['RBACTIVATEDDATE'], 'PM') === false) ? 
					substr($s['RBACTIVATEDDATE'], 2, strlen($s['RBACTIVATEDDATE'])) : $s['RBACTIVATEDDATE'];
					oci_bind_by_name($compiled, ':rbactivateddate', strval($rbactivateddate));
			}
			if (!is_null($s['RBACTIVATEDBY'])) {
				oci_bind_by_name($compiled, ':rbactivatedby', strval($s['RBACTIVATEDBY']));
			}
			oci_bind_by_name($compiled, ':rbaccountstatus', strval($s['RBACCOUNTSTATUS']));
			oci_bind_by_name($compiled, ':rbsvccode2', strval($s['RBSVCCODE2']));
			oci_bind_by_name($compiled, ':radiuspolicy', strval($s['RBACCOUNTPLAN']));
			oci_bind_by_name($compiled, ':customertype', strval($s['CUSTOMERTYPE']));
			oci_bind_by_name($compiled, ':rbservicenumber', strval($s['RBSERVICENUMBER']));
			oci_bind_by_name($compiled, ':rbchangestatusfrom', strval($s['RBCHANGESTATUSFROM']));
			oci_bind_by_name($compiled, ':rbsecondaryaccount', strval($s['RBSECONDARYACCOUNT']));
			if (!is_null($s['RBUNLIMITEDACCESS'])) {
				oci_bind_by_name($compiled, ':rbunlimitedaccess', strval($s['RBUNLIMITEDACCESS']));
			}
			oci_bind_by_name($compiled, ':rbtimeslot', strval($s['RBTIMESLOT']));
			if (!is_null($s['RBORDERNUMBER'])) {
				oci_bind_by_name($compiled, ':rbordernumber', strval($s['RBORDERNUMBER']));
			}
			$s['RBREMARKS'] = trim($s['RBREMARKS']);
			oci_bind_by_name($compiled, ':rbremarks', strval($s['RBREMARKS']));
			oci_bind_by_name($compiled, ':rbrealm', strval($s['RBREALM']));
			if (!is_null($s['RBNUMBEROFSESSION'])) {
				oci_bind_by_name($compiled, ':rbnumberofsession', strval($s['RBNUMBEROFSESSION']));
			}
			if (!is_null($s['RBMULTISTATIC'])) {
				oci_bind_by_name($compiled, ':rbmultistatic', strval($s['RBMULTISTATIC']));
			}
			if (!is_null($s['RBIPADDRESS'])) {
				oci_bind_by_name($compiled, ':rbipaddress', strval($s['RBIPADDRESS']));
			}
			oci_bind_by_name($compiled, ':rbenabled', strval($s['RBENABLED']));
			oci_bind_by_name($compiled, ':cn', strval($cn));
			$result = oci_execute($compiled);
			return $result ? true : false;
		} catch (Exception $e) {
			log_message('info', 'error:'.json_encode($e));
			return false;
		}
	}
	public function clearRadiuspolicy($cn, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		try {
			$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
			if (!$conn) {
				return false;
			}
			$sql = "update TBLRADIUSCUSTOMER set RADIUSPOLICY = '' where USERNAME = :cn";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':cn', strval($cn));
			$result = oci_execute($compiled);
			return $result ? true : false;
		} catch (Exception $e) {
			log_message('info', 'error @ clearRadiuspolicy:'.json_encode($e));
			return false;
		}
	}
	public function updateRadiuspolicy($cn, $plan, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		try {
			$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
			if (!$conn) {
				return false;
			}
			$sql = "update TBLRADIUSCUSTOMER set RADIUSPOLICY = :plan where USERNAME = :cn";
			$compiled = oci_parse($conn, $sql);
			$plan = str_replace('-', '~', $plan);
			oci_bind_by_name($compiled, ':plan', strval($plan));
			oci_bind_by_name($compiled, ':cn', strval($cn));
			$result = oci_execute($compiled);
			return $result ? true : false;
		} catch (Exception $e) {
			log_message('info', 'error @ updateRadiuspolicy:'.json_encode($e));
			return false;	
		}
	}
	public function changeSubscriberPassword($uusername, $realm, $upassword, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', time());
		$query = "update TBLCUSTOMER set PASSWORD = '".$password."', RBREALM = '".$realm."', LASTMODIFIEDDATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') where USERNAME = '".$username.'@'.$realm."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
		*/
		$now = date('Y-m-d H:i:s', time());
		$cn = $uusername.'@'.$realm;
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "update TBLCUSTOMER set PASSWORD = :password, RBREALM = :rbrealm, LASTMODIFIEDDATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') where USERNAME = :cn";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':password', strval($upassword));
		oci_bind_by_name($compiled, ':rbrealm', strval($realm));
		oci_bind_by_name($compiled, ':now', strval($now));
		oci_bind_by_name($compiled, ':cn', strval($cn));
		$result = oci_execute($compiled);
		return $result ? true : false;
	}
	public function unassignIPv6IPAndNetAddress($cn, $ipv6 = false, $ip = false, $net = false, $replyItem = null, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		$now = date('Y-m-d H:i:s', time());
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "update TBLCUSTOMER set LASTMODIFIEDDATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS')".
			($ipv6 ? ", RBADDITIONALSERVICE4 = null" : "").
			($ip ? ", RBIPADDRESS = null" : "").
			($net ? ", RBMULTISTATIC = null" : "").
			", CUSTOMERREPLYITEM = :replyitem".
			" where USERNAME = :cn";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':now', strval($now));
		oci_bind_by_name($compiled, ':replyitem', strval($replyItem));
		oci_bind_by_name($compiled, ':cn', strval($cn));
		$result = oci_execute($compiled);
		return $result ? true : false;
	}
	public function delete($cn, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$this->utility->where($this->COLUMN_USERIDENTITY, $cn)
			->delete($this->TABLENAME);
		return $this->utility->affected_rows() == 0 ? false : true;
		*/
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "delete from TBLCUSTOMER where USER_IDENTITY = :cn";
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':cn', strval($cn));
		$result = oci_execute($compiled);
		return $result ? true : false;
	}
	public function subscriberExists($cn, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$count = $this->utility->from($this->TABLENAME)
			->where($this->COLUMN_USERIDENTITY, $cn)
			->count_all_results();
		return $count == 0 ? false : true;
		*/
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select count(*) as CNT from TBLCUSTOMER where USER_IDENTITY = :cn";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':cn', strval($cn));
		$result = oci_execute($compiled);
		$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		return intval($row['CNT']) == 0 ? false : true;
	}
	public function serviceNumberExists($serviceNumber, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$count = $this->utility->from($this->TABLENAME)
			->where($this->COLUMN_RBSERVICENUMBER, $serviceNumber)
			->count_all_results();
		return $count == 0 ? false : true;
		*/
		if ($serviceNumber == 'dec') {
			return false;
		}
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select count(*) as CNT from TBLCUSTOMER where RBSERVICENUMBER = :rbservicenumber";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':rbservicenumber', strval($serviceNumber));
		$result = oci_execute($compiled);
		$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		return intval($row['CNT']) == 0 ? false : true;
	}
	public function findByUserIdentity($cn, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		///*
		$this->utility->db_select();
		// $query = $this->utility->select('*')
		$query = $this->utility->select('USER_IDENTITY, USERNAME, BANDWIDTH, CUSTOMERSTATUS, PASSWORD, CUSTOMERREPLYITEM, CREATEDATE, LASTMODIFIEDDATE, RADIUSPOLICY, '.
				'RBACCOUNTPLAN, CUSTOMERTYPE, CUI, RBCUSTOMERNAME, RBCREATEDBY, RBADDITIONALSERVICE5, RBADDITIONALSERVICE4, RBADDITIONALSERVICE3, '.
				'RBADDITIONALSERVICE2, RBADDITIONALSERVICE1, RBCHANGESTATUSDATE, RBCHANGESTATUSBY, RBACTIVATEDDATE, RBACTIVATEDBY, RBACCOUNTSTATUS, RBSVCCODE, RBSVCCODE2, '.
				'RBSERVICENUMBER, RBCHANGESTATUSFROM, RBSECONDARYACCOUNT, RBUNLIMITEDACCESS, RBTIMESLOT, RBORDERNUMBER, RBREMARKS, RBREALM, RBNUMBEROFSESSION, '.
				'RBMULTISTATIC, RBIPADDRESS, RBENABLED, EXPIRYDATE, CREDITLIMIT, CONCURRENTLOGINPOLICY, ACCESSPOLICY, IPPOOLNAME, CALLINGSTATIONID, HOTLINEPOLICY, '.
				'MACVALIDATION')
			->from($this->TABLENAME)
			->where($this->COLUMN_USERIDENTITY, $cn)
			->get();
		return $query->num_rows() == 0 ? false : $query->row_array();
		//*/
		/*
		$now = date('Y-m-d H:i:s', time());
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select * from TBLCUSTOMER where USER_IDENTITY = :cn";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':cn', $cn);
		$result = oci_execute($compiled);
		if (!$result) {
			return false;
		}
		$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		return $row;
		*/
	}
	public function findByUserIdentityAddressesOnly($cn, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		$this->utility->db_select();
		$query = $this->utility->select('USER_IDENTITY, USERNAME, RBADDITIONALSERVICE4, RBMULTISTATIC, RBIPADDRESS')
			->from($this->TABLENAME)
			->where($this->COLUMN_USERIDENTITY, $cn)
			->get();
		return $query->num_rows() == 0 ? false : $query->row_array();
	}
	public function findByIPAddress($ipaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select * from TBLCUSTOMER where RBIPADDRESS = :ipaddress";
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		$result = oci_execute($compiled);
		$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		return $row;
	}
	public function findAll($order) {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_USERIDENTITY.', '.$this->COLUMN_USERNAME.', '.$this->COLUMN_BANDWIDTH.', '.
			$this->COLUMN_CUSTOMERSTATUS.', '.$this->COLUMN_PASSWORD.', '.$this->COLUMN_CUSTOMERREPLYITEM.', '.$this->COLUMN_CREATEDATE.', '.$this->COLUMN_LASTMODIFIEDDATE.', '.
			$this->COLUMN_RBCUSTOMERNAME.', '.$this->COLUMN_RBCREATEDBY.', '.$this->COLUMN_RBADDITIONALSERVICE5.', '.$this->COLUMN_RBADDITIONALSERVICE4.', '.
			$this->COLUMN_RBADDITIONALSERVICE3.', '.$this->COLUMN_RBADDITIONALSERVICE2.', '.$this->COLUMN_RBADDITIONALSERVICE1.', '.$this->COLUMN_RBCHANGESTATUSDATE.', '.
			$this->COLUMN_RBCHANGESTATUSBY.', '.$this->COLUMN_RBACTIVATEDDATE.', '.$this->COLUMN_RBACTIVATEDBY.', '.$this->COLUMN_RBACCOUNTSTATUS.', '.$this->COLUMN_RBSVCCODE2.', '.
			$this->COLUMN_RADIUSPOLICY.', '.$this->COLUMN_CUSTOMERTYPE.', '.$this->COLUMN_RBSERVICENUMBER.', '.$this->COLUMN_RBCHANGESTATUSFROM.', '.
			$this->COLUMN_RBSECONDARYACCOUNT.', '.$this->COLUMN_RBUNLIMITEDACCESS.', '.$this->COLUMN_RBTIMESLOT.', '.$this->COLUMN_RBORDERNUMBER.', '.$this->COLUMN_RBREMARKS.', '.
			$this->COLUMN_RBREALM.', '.$this->COLUMN_RBNUMBEROFSESSION.', '.$this->COLUMN_RBMULTISTATIC.', '.$this->COLUMN_RBIPADDRESS.', '.$this->COLUMN_RBENABLED)
			->from($this->TABLENAME)
			->order_by($order['column'], $order['dir'])
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function findAllUsingColumn($column, $value, $start, $max, $order) {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_USERIDENTITY.', '.$this->COLUMN_USERNAME.', '.$this->COLUMN_BANDWIDTH.', RBACCOUNTPLAN, '.
			$this->COLUMN_CUSTOMERSTATUS.', '.$this->COLUMN_PASSWORD.', '.$this->COLUMN_CUSTOMERREPLYITEM.', '.$this->COLUMN_CREATEDATE.', '.$this->COLUMN_LASTMODIFIEDDATE.', '.
			$this->COLUMN_RBCUSTOMERNAME.', '.$this->COLUMN_RBCREATEDBY.', '.$this->COLUMN_RBADDITIONALSERVICE5.', '.$this->COLUMN_RBADDITIONALSERVICE4.', '.
			$this->COLUMN_RBADDITIONALSERVICE3.', '.$this->COLUMN_RBADDITIONALSERVICE2.', '.$this->COLUMN_RBADDITIONALSERVICE1.', '.$this->COLUMN_RBCHANGESTATUSDATE.', '.
			$this->COLUMN_RBCHANGESTATUSBY.', '.$this->COLUMN_RBACTIVATEDDATE.', '.$this->COLUMN_RBACTIVATEDBY.', '.$this->COLUMN_RBACCOUNTSTATUS.', '.$this->COLUMN_RBSVCCODE2.', '.
			$this->COLUMN_RADIUSPOLICY.', '.$this->COLUMN_CUSTOMERTYPE.', '.$this->COLUMN_RBSERVICENUMBER.', '.$this->COLUMN_RBCHANGESTATUSFROM.', '.
			$this->COLUMN_RBSECONDARYACCOUNT.', '.$this->COLUMN_RBUNLIMITEDACCESS.', '.$this->COLUMN_RBTIMESLOT.', '.$this->COLUMN_RBORDERNUMBER.', '.$this->COLUMN_RBREMARKS.', '.
			$this->COLUMN_RBREALM.', '.$this->COLUMN_RBNUMBEROFSESSION.', '.$this->COLUMN_RBMULTISTATIC.', '.$this->COLUMN_RBIPADDRESS.', '.$this->COLUMN_RBENABLED)
			->from($this->TABLENAME)
			->where($column, $value)
			->limit($start == 0 ? $max + 1 : $max, $start == 0 ? $start : $start + 1)
			->order_by($order['column'], $order['dir'])
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function searchSubscriber($column, $value, $exact, $wildcardLocation, $portal, $realm, $allowedRealms) { //wildcardLocation = 'before'/'after'/'both'
		log_message('info', 'column:'.$column);
		log_message('info', 'value:'.$value);
		log_message('info', 'exact:'.json_encode($exact));
		log_message('info', 'wildcardLocation:'.$wildcardLocation);
		log_message('info', 'realm:'.json_encode($realm));
		log_message('info', 'allowedRealms: '.json_encode($allowedRealms));
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_USERIDENTITY.', '.$this->COLUMN_USERNAME.', '.$this->COLUMN_BANDWIDTH.', RADIUSPOLICY, RBACCOUNTPLAN, '.	
			$this->COLUMN_CUSTOMERSTATUS.', '.$this->COLUMN_PASSWORD.', '.$this->COLUMN_CUSTOMERREPLYITEM.', '.$this->COLUMN_CREATEDATE.', '.$this->COLUMN_LASTMODIFIEDDATE.', '.
			$this->COLUMN_RBCUSTOMERNAME.', '.$this->COLUMN_RBCREATEDBY.', '.$this->COLUMN_RBADDITIONALSERVICE5.', '.$this->COLUMN_RBADDITIONALSERVICE4.', '.
			$this->COLUMN_RBADDITIONALSERVICE3.', '.$this->COLUMN_RBADDITIONALSERVICE2.', '.$this->COLUMN_RBADDITIONALSERVICE1.', '.$this->COLUMN_RBCHANGESTATUSDATE.', '.
			$this->COLUMN_RBCHANGESTATUSBY.', '.$this->COLUMN_RBACTIVATEDDATE.', '.$this->COLUMN_RBACTIVATEDBY.', '.$this->COLUMN_RBACCOUNTSTATUS.', '.$this->COLUMN_RBSVCCODE2.', '.
			$this->COLUMN_RADIUSPOLICY.', '.$this->COLUMN_CUSTOMERTYPE.', '.$this->COLUMN_RBSERVICENUMBER.', '.$this->COLUMN_RBCHANGESTATUSFROM.', '.
			$this->COLUMN_RBSECONDARYACCOUNT.', '.$this->COLUMN_RBUNLIMITEDACCESS.', '.$this->COLUMN_RBTIMESLOT.', '.$this->COLUMN_RBORDERNUMBER.', '.$this->COLUMN_RBREMARKS.', '.
			$this->COLUMN_RBREALM.', '.$this->COLUMN_RBNUMBEROFSESSION.', '.$this->COLUMN_RBMULTISTATIC.', '.$this->COLUMN_RBIPADDRESS.', '.$this->COLUMN_RBENABLED)
			->from($this->TABLENAME);
		if ($portal == 'admin') {
			if ($exact) {
				if ($column == 'RBIPADDRESS') {
					$this->utility->where($column, $value);
				} else if ($column == 'USERNAME') {
					$whereStr = '';
					if (strpos($value, '@') === false) {
						for ($i = 0; $i < count($allowedRealms); $i++) {
							log_message('info', $i.'|'.json_encode($allowedRealms[$i]).'|'.gettype($allowedRealms[$i]));
							$whereStr = $whereStr."USERNAME = '".$value.'@'.$allowedRealms[$i]['NM']."' ";
							if ($i != count($allowedRealms) - 1) {
								$whereStr = $whereStr.' or ';
							}
						}
					} else {
						$whereStr .= "USERNAME = ".$this->utility->escape($value)." ";
					}
					log_message('info', $whereStr);
					$this->utility->where($whereStr);
				} else {
					$this->utility->where("regexp_like(".$column.", '^".$value."$', 'c')");
				}
			} else {
				if ($column != 'RBIPADDRESS') {
					if ($wildcardLocation == 'after') {
						$this->utility->where("regexp_like(".$column.", '^".$value."', 'c')");
					} else if ($wildcardLocation == 'before') {
						$this->utility->where("regexp_like(".$column.", '".$value."$', 'c')");
					} else if ($wildcardLocation == 'both') {
						$this->utility->where("regexp_like(".$column.", '".$value."', 'c')");
					}
					$whereStr = '';
					$whereStr = $whereStr."(";
					for ($i = 0; $i < count($allowedRealms); $i++) {
						log_message('info', $i.'|'.json_encode($allowedRealms[$i]).'|'.gettype($allowedRealms[$i]));
						$whereStr = $whereStr."RBREALM = '".$allowedRealms[$i]['NM']."' ";
						if ($i != count($allowedRealms) - 1) {
							$whereStr = $whereStr.' or ';
						}
					}
					$whereStr = $whereStr.")";
					$this->utility->where($whereStr);
				} else {
					$this->utility->like($column, $value, $wildcardLocation);
				}
			}
		} else if ($portal == 'service') {
			if ($exact) {
				if ($column == 'USERNAME') {
					//$this->utility->where("regexp_like(".$column.", '".$value.'@'.$realm."', 'c')");
					$this->utility->where($column, $value.'@'.$realm);
				}  else if ($column == 'RBCUSTOMERNAME' || $column == 'RBORDERNUMBER' || $column == 'RBSERVICENUMBER') {
					$this->utility->where("regexp_like(".$column.", '^".$value."$', 'c')");
				} else if ($column == 'RBIPADDRESS') {
					$this->utility->where($column, $value);
				} else {
					$this->utility->where("regexp_like(".$column.", '^".$value."$', 'c')");
				}
			} else {
				if ($column != 'RBIPADDRESS') {
					if ($wildcardLocation == 'after') {
						$this->utility->where("regexp_like(".$column.", '^".$value."', 'c')");
					} else if ($wildcardLocation == 'before') {
						$this->utility->where("regexp_like(".$column.", '".$value."$', 'c')");
					} else if ($wildcardLocation == 'both') {
						$this->utility->where("regexp_like(".$column.", '".$value."', 'c')");
					}
				} else {
					$this->utility->like($column, $value, $wildcardLocation);
				}
			}
			if (!is_null($realm)) {
				$this->utility->where($this->COLUMN_RBREALM, $realm);
			}
		}
		$query = $this->utility->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countAllUsingColumn($column, $value) {
		$this->utility->db_select();
		$count = $this->utility->from($this->TABLENAME)
			->where($column, $value)
			->count_all_results();
		return $count;
	}
	//will be replaced by isValidNew
	public function isValid($subscriber) {
		$errors = array();
		if ($subscriber[$this->COLUMN_USERIDENTITY] == null || trim($subscriber[$this->COLUMN_USERIDENTITY]) == '' || $subscriber[$this->COLUMN_USERNAME] == null || 
			$subscriber[$this->COLUMN_USERNAME] == '') {
			$errors['USERNAME'] = 'The username is missing/invalid.';
			return array('status' => false, 'errors' => $errors);
		}
		/*
		$index = strrpos($subscriber[$this->COLUMN_USERIDENTITY], '@');
		$username = $index === false ? $subscriber[$this->COLUMN_USERIDENTITY] : substr($subscriber[$this->COLUMN_USERIDENTITY], 0, $index);
		if ($subscriber[$this->COLUMN_PASSWORD] == null || trim($subscriber[$this->COLUMN_PASSWORD]) == '') {
			$errors['PASSWORD'] = 'The password is missing/invalid.';
			return array('status' => false, 'errors' => $errors);
		}
		*/
		if ($subscriber[$this->COLUMN_CUSTOMERTYPE] == null || trim($subscriber[$this->COLUMN_CUSTOMERTYPE]) == '') {
			$errors['CUSTOMERTYPE'] = 'The customer type is missing/invalid.';
			return array('status' => false, 'errors' => $errors);
		}
		if ($subscriber[$this->COLUMN_CUSTOMERSTATUS] == null || trim($subscriber[$this->COLUMN_CUSTOMERSTATUS]) == '') {
			$errors['CUSTOMERSTATUS'] = 'The customer status is missing/invalid.';
			return array('status' => false, 'errors' => $errors);
		} else {
			$found = false;
			for ($i = 0; $i < count($this->STATUSES); $i++) {
				if ($subscriber[$this->COLUMN_CUSTOMERSTATUS] == $this->STATUSES[$i]) {
					$found = true;
				}
			}
			if (!$found) {
				$errors['CUSTOMERSTATUS'] = 'The customer status is missing/invalid.';
				return array('status' => false, 'errors' => $errors);
			}
		}
		/*
		if ($subscriber[$this->COLUMN_RBCUSTOMERNAME] == null || trim($subscriber[$this->COLUMN_RBCUSTOMERNAME]) == '') {
			$errors['RBCUSTOMERNAME'] = 'The customer name is missing/invalid.';
			return array('status' => false, 'errors' => $errors);
		} 
		*/       
		if ($subscriber[$this->COLUMN_RBSERVICENUMBER] == null || trim($subscriber[$this->COLUMN_RBSERVICENUMBER]) == '') {
			$errors['RBSERVICENUMBER'] = 'The service number is missing/invalid.';
			return array('status' => false, 'errors' => $errors);
		}
		$this->load->model('services');
		$services = $this->services->fetchAllNamesOnly2();
		$additionalServices = $this->services->fetchAllNamesOnly();
		if ($subscriber['RADIUSPOLICY'] != null && trim($subscriber['RADIUSPOLICY']) != '') {
			$found = false;
			for ($i = 0; $i < count($services); $i++) {
				if ($subscriber['RADIUSPOLICY'] == $services[$i]) {
					$found = true;
					break;
				}
			}
			if (!$found) {
				$errors['RADIUSPOLICY'] = 'The service is missing/invalid.';
				return array('status' => false, 'errors' => $errors);
			}
		} else {
			$errors['RADIUSPOLICY'] = 'The service is missing/invalid.';
			return array('status' => false, 'errors' => $errors);
		}
		return array('status' => true);
	}
	public function isValidNew($subscriber, $theHost, $thePort, $theSchema, $theUsername, $thePassword) {
		$errors = array();
		if ($subscriber[$this->COLUMN_USERIDENTITY] == null || trim($subscriber[$this->COLUMN_USERIDENTITY]) == '' || $subscriber[$this->COLUMN_USERNAME] == null || 
			$subscriber[$this->COLUMN_USERNAME] == '') {
			$errors['USERNAME'] = 'The username is missing/invalid.';
			return array('status' => false, 'errors' => $errors);
		}
		// REMOVE VALIDATION 5/17/19
		// if ($subscriber[$this->COLUMN_CUSTOMERTYPE] == null || trim($subscriber[$this->COLUMN_CUSTOMERTYPE]) == '') {
		// 	$errors['CUSTOMERTYPE'] = 'The customer type is missing/invalid.';
		// 	return array('status' => false, 'errors' => $errors);
		// }
		if ($subscriber[$this->COLUMN_CUSTOMERSTATUS] == null || trim($subscriber[$this->COLUMN_CUSTOMERSTATUS]) == '') {
			$errors['CUSTOMERSTATUS'] = 'The customer status is missing/invalid.';
			return array('status' => false, 'errors' => $errors);
		} else {
			$found = false;
			for ($i = 0; $i < count($this->STATUSES); $i++) {
				if ($subscriber[$this->COLUMN_CUSTOMERSTATUS] == $this->STATUSES[$i]) {
					$found = true;
				}
			}
			if (!$found) {
				$errors['CUSTOMERSTATUS'] = 'The customer status is missing/invalid.';
				return array('status' => false, 'errors' => $errors);
			}
		}    
		if ($subscriber[$this->COLUMN_RBSERVICENUMBER] == null || trim($subscriber[$this->COLUMN_RBSERVICENUMBER]) == '') {
			$errors['RBSERVICENUMBER'] = 'The service number is missing/invalid.';
			return array('status' => false, 'errors' => $errors);
		}
		$this->load->model('services');
		// $services = $this->services->fetchAllNamesOnly2();
		
		

		$services = $this->services->fetchAllNamesOnlyNew($theHost, $thePort, $theSchema, $theUsername, $thePassword);
		// if ($subscriber['RADIUSPOLICY'] != null && trim($subscriber['RADIUSPOLICY']) != '') {
		// 	$found = false;
		// 	for ($i = 0; $i < count($services); $i++) {
		// 		if ($subscriber['RADIUSPOLICY'] == $services[$i]) {
		// 			$found = true;
		// 			break;
		// 		}
		// 	}
		// 	if (!$found) {
		// 		$errors['RADIUSPOLICY'] = 'The service is missing/invalid.';
		// 		return array('status' => false, 'errors' => $errors);
		// 	}
		// } else {
		// 	$errors['RADIUSPOLICY'] = 'The service is missing/invalid.';
		// 	return array('status' => false, 'errors' => $errors);
		// }
		return array('status' => true);
	}
	public function rowDataToSubscriberArray($data, $realm, $sysuser, $action) {
		$now = date('Y-m-d H:i:s', time());
		if (strtoupper($data[2]) == 'R') {
			$data[2] = 'Residential';
		} else if (strtoupper($data[2]) == 'B') {
			$data[2] = 'Business';
		}
		if (strtoupper($data[3]) == 'A') {
			$data[3] = 'Active';
		} else if (strtoupper($data[3]) == 'D') {
			$data[3] = 'InActive';
		}
		if (strtoupper($data[7]) == 'Y') {
			$data[7] = 'Yes';
		} else if (strtoupper($data[7]) == 'N') {
			$data[7] = 'No';
		}
		$subscriber = array(
			$this->COLUMN_USERIDENTITY => $data[0].'@'.$realm,
			$this->COLUMN_USERNAME => $data[0].'@'.$realm,
			$this->COLUMN_BANDWIDTH => null,
			$this->COLUMN_CUSTOMERSTATUS => $data[3],
			$this->COLUMN_PASSWORD => $data[1],
			$this->COLUMN_CUSTOMERREPLYITEM => null,
			$this->COLUMN_CREATEDATE => $action == 'create' ? $now : null,
			$this->COLUMN_LASTMODIFIEDDATE => $action == 'create' ? null : $now,
			$this->COLUMN_RBCUSTOMERNAME => $data[5],
			$this->COLUMN_RBCREATEDBY => $sysuser,
			$this->COLUMN_RBADDITIONALSERVICE5 => null,
			$this->COLUMN_RBADDITIONALSERVICE4 => null,
			$this->COLUMN_RBADDITIONALSERVICE3 => null,
			$this->COLUMN_RBADDITIONALSERVICE2 => $data[12] == '' ? null : $data[12],
			$this->COLUMN_RBADDITIONALSERVICE1 => $data[11] == '' ? null : $data[11],
			$this->COLUMN_RBCHANGESTATUSDATE => null,
			$this->COLUMN_RBCHANGESTATUSBY => $sysuser,
			$this->COLUMN_RBACTIVATEDDATE => $data[3] == 'Active' ? $now : null,
			$this->COLUMN_RBACTIVATEDBY => $data[3] == 'Active' ? $sysuser : null,
			$this->COLUMN_RBACCOUNTSTATUS => 'Primary',
			$this->COLUMN_RBSVCCODE2 => null,
			$this->COLUMN_RBSVCCODE => $data[8],
			'RBACCOUNTPLAN' => $data[8],
			'RADIUSPOLICY' => $data[8],
			$this->COLUMN_CUSTOMERTYPE => $data[2],
			$this->COLUMN_RBSERVICENUMBER => $data[6],
			$this->COLUMN_RBCHANGESTATUSFROM => null,
			$this->COLUMN_RBSECONDARYACCOUNT => null,
			$this->COLUMN_RBUNLIMITEDACCESS => 1, //true
			$this->COLUMN_RBTIMESLOT => 'Al0000-2400',
			$this->COLUMN_RBORDERNUMBER => $data[4] == '' ? null : $data[4],
			$this->COLUMN_RBREMARKS => $data[13] == '' ? null : $data[13],
			$this->COLUMN_RBREALM => $realm,
			$this->COLUMN_RBNUMBEROFSESSION => 1,
			$this->COLUMN_RBMULTISTATIC => $data[10] == '' ? null : $data[10],
			$this->COLUMN_RBIPADDRESS => $data[9] == '' ? null : $data[9],
			$this->COLUMN_RBENABLED => $data[7]);
		if (!is_null($subscriber[$this->COLUMN_RBIPADDRESS]) && is_null($subscriber[$this->COLUMN_RBMULTISTATIC])) {
			$subscriber[$this->COLUMN_CUSTOMERREPLYITEM] = '0:8='.$subscriber[$this->COLUMN_RBIPADDRESS].',4874:1=OUTSIDE';
		} else if (!is_null($subscriber[$this->COLUMN_RBIPADDRESS]) && !is_null($subscriber[$this->COLUMN_RBMULTISTATIC])) {
			$subscriber[$this->COLUMN_CUSTOMERREPLYITEM] = '0:8='.$subscriber[$this->COLUMN_RBIPADDRESS].',0:22='.$subscriber[$this->COLUMN_RBMULTISTATIC].',4874:1=OUTSIDE';
		}
		return $subscriber;
	}
	public function rowDataToSubscriberArrayV2($data, $realm, $sysuser, $action) {
		$now = date('Y-m-d H:i:s', time());
		if (strtoupper($data[2]) == 'R') {
			$data[2] = 'Residential';
		} else if (strtoupper($data[2]) == 'B') {
			$data[2] = 'Business';
		}
		if (strtoupper($data[3]) == 'A') {
			$data[3] = 'Active';
		} else if (strtoupper($data[3]) == 'D') {
			$data[3] = 'InActive';
		}
		if (strtoupper($data[7]) == 'Y') {
			$data[7] = 'Yes';
		} else if (strtoupper($data[7]) == 'N') {
			$data[7] = 'No';
		}
		$subscriber = array(
			$this->COLUMN_USERIDENTITY => $data[0].'@'.$realm,
			$this->COLUMN_USERNAME => $data[0].'@'.$realm,
			$this->COLUMN_BANDWIDTH => null,
			$this->COLUMN_CUSTOMERSTATUS => $data[3],
			$this->COLUMN_PASSWORD => $data[1],
			$this->COLUMN_CUSTOMERREPLYITEM => null,
			$this->COLUMN_CREATEDATE => $action == 'create' ? $now : null,
			$this->COLUMN_LASTMODIFIEDDATE => $action == 'create' ? null : $now,
			$this->COLUMN_RBCUSTOMERNAME => $data[5],
			$this->COLUMN_RBCREATEDBY => $sysuser,
			$this->COLUMN_RBADDITIONALSERVICE5 => null,
			$this->COLUMN_RBADDITIONALSERVICE4 => $data[9] == '' ? null : $data[9],
			$this->COLUMN_RBADDITIONALSERVICE3 => null,
			$this->COLUMN_RBADDITIONALSERVICE2 => $data[13] == '' ? null : $data[13],
			$this->COLUMN_RBADDITIONALSERVICE1 => $data[12] == '' ? null : $data[12],
			$this->COLUMN_RBCHANGESTATUSDATE => null,
			$this->COLUMN_RBCHANGESTATUSBY => $sysuser,
			$this->COLUMN_RBACTIVATEDDATE => $data[3] == 'Active' ? $now : null,
			$this->COLUMN_RBACTIVATEDBY => $data[3] == 'Active' ? $sysuser : null,
			$this->COLUMN_RBACCOUNTSTATUS => 'Primary',
			$this->COLUMN_RBSVCCODE2 => null,
			$this->COLUMN_RBSVCCODE => $data[8],
			'RBACCOUNTPLAN' => $data[8],
			'RADIUSPOLICY' => $data[8],
			$this->COLUMN_CUSTOMERTYPE => $data[2],
			$this->COLUMN_RBSERVICENUMBER => $data[6],
			$this->COLUMN_RBCHANGESTATUSFROM => null,
			$this->COLUMN_RBSECONDARYACCOUNT => null,
			$this->COLUMN_RBUNLIMITEDACCESS => 1, //true
			$this->COLUMN_RBTIMESLOT => 'Al0000-2400',
			$this->COLUMN_RBORDERNUMBER => $data[4] == '' ? null : $data[4],
			$this->COLUMN_RBREMARKS => $data[14] == '' ? null : $data[14],
			$this->COLUMN_RBREALM => $realm,
			$this->COLUMN_RBNUMBEROFSESSION => 1,
			$this->COLUMN_RBMULTISTATIC => $data[11] == '' ? null : $data[11],
			$this->COLUMN_RBIPADDRESS => $data[10] == '' ? null : $data[10],
			$this->COLUMN_RBENABLED => $data[7]);
		// get customereplyitem value
		$subscriber[$this->COLUMN_CUSTOMERREPLYITEM] = $this->generateCustomerReplyItemValue(
			$subscriber[$this->COLUMN_RBADDITIONALSERVICE4], $subscriber[$this->COLUMN_RBIPADDRESS], $subscriber[$this->COLUMN_RBMULTISTATIC]);
		return $subscriber;
	}
	public function rowDataToSubscriberUpdateArray($data, $realm, $sysuser) {
		/**************************************************
		 * if array item is null, that column will not be updated
		 **************************************************/
		$now = date('Y-m-d H:i:s', time());
		if (strtoupper($data[2]) == 'R') {
			$data[2] = 'Residential';
		} else if (strtoupper($data[2]) == 'B') {
			$data[2] = 'Business';
		}
		if (strtoupper($data[3]) == 'A') {
			$data[3] = 'Active';
		} else if (strtoupper($data[3]) == 'D') {
			$data[3] = 'InActive';
		}
		if (strtoupper($data[7]) == 'Y') {
			$data[7] = 'Yes';
		} else if (strtoupper($data[7]) == 'N') {
			$data[7] = 'No';
		}
		$subscriber = array(
			$this->COLUMN_USERIDENTITY => $data[0].'@'.$realm,
			$this->COLUMN_USERNAME => $data[0].'@'.$realm,
			$this->COLUMN_BANDWIDTH => null,
			$this->COLUMN_CUSTOMERSTATUS => $data[3] == '' ? null : $data[3],
			$this->COLUMN_PASSWORD => $data[1] == '' ? null : $data[1],
			$this->COLUMN_CUSTOMERREPLYITEM => null,
			$this->COLUMN_CREATEDATE => null,
			$this->COLUMN_LASTMODIFIEDDATE => $now,
			$this->COLUMN_RBCUSTOMERNAME => $data[5] == '' ? null : $data[5],
			$this->COLUMN_RBCREATEDBY => null,
			$this->COLUMN_RBADDITIONALSERVICE5 => null,
			$this->COLUMN_RBADDITIONALSERVICE4 => null,
			$this->COLUMN_RBADDITIONALSERVICE3 => null,
			$this->COLUMN_RBADDITIONALSERVICE2 => $data[12] == '' ? null : $data[12],
			$this->COLUMN_RBADDITIONALSERVICE1 => $data[11] == '' ? null : $data[11],
			$this->COLUMN_RBCHANGESTATUSDATE => null,
			$this->COLUMN_RBCHANGESTATUSBY => $sysuser,
			$this->COLUMN_RBACTIVATEDDATE => $data[3] == '' ? null : ($data[3] == 'Active' ? $now : null),
			$this->COLUMN_RBACTIVATEDBY => $data[3] == '' ? null : ($data[3] == 'Active' ? $sysuser : null),
			$this->COLUMN_RBACCOUNTSTATUS => null,
			$this->COLUMN_RBSVCCODE2 => null,
			$this->COLUMN_RBSVCCODE => $data[8] == '' ? null : $data[8],
			'RBACCOUNTPLAN' => $data[8] == '' ? null : $data[8],
			'RADIUSPOLICY' => $data[8] == '' ? null : $data[8],
			$this->COLUMN_CUSTOMERTYPE => $data[2] == '' ? null : $data[2],
			$this->COLUMN_RBSERVICENUMBER => $data[6] == '' ? null : $data[6],
			$this->COLUMN_RBCHANGESTATUSFROM => null,
			$this->COLUMN_RBSECONDARYACCOUNT => null,
			$this->COLUMN_RBUNLIMITEDACCESS => null, //true
			$this->COLUMN_RBTIMESLOT => null, //'Al0000-2400',
			$this->COLUMN_RBORDERNUMBER => $data[4] == '' ? null : $data[4],
			$this->COLUMN_RBREMARKS => $data[13] == '' ? null : $data[13],
			$this->COLUMN_RBREALM => null, //$realm,
			$this->COLUMN_RBNUMBEROFSESSION => null, //1,
			$this->COLUMN_RBMULTISTATIC => $data[10] == '' ? null : $data[10],
			$this->COLUMN_RBIPADDRESS => $data[9] == '' ? null : $data[9],
			$this->COLUMN_RBENABLED => $data[7] == '' ? null : $data[7]);
		return $subscriber;
	}
	public function rowDataToSubscriberUpdateArrayV2($data, $realm, $sysuser) {
		/**************************************************
		 * if array item is null, that column will not be updated
		 **************************************************/
		$now = date('Y-m-d H:i:s', time());
		if (strtoupper($data[2]) == 'R') {
			$data[2] = 'Residential';
		} else if (strtoupper($data[2]) == 'B') {
			$data[2] = 'Business';
		}
		if (strtoupper($data[3]) == 'A') {
			$data[3] = 'Active';
		} else if (strtoupper($data[3]) == 'D') {
			$data[3] = 'InActive';
		}
		if (strtoupper($data[7]) == 'Y') {
			$data[7] = 'Yes';
		} else if (strtoupper($data[7]) == 'N') {
			$data[7] = 'No';
		}
		$subscriber = array(
			$this->COLUMN_USERIDENTITY => $data[0].'@'.$realm,
			$this->COLUMN_USERNAME => $data[0].'@'.$realm,
			$this->COLUMN_BANDWIDTH => null,
			$this->COLUMN_CUSTOMERSTATUS => $data[3] == '' ? null : $data[3],
			$this->COLUMN_PASSWORD => $data[1] == '' ? null : $data[1],
			$this->COLUMN_CUSTOMERREPLYITEM => null,
			$this->COLUMN_CREATEDATE => null,
			$this->COLUMN_LASTMODIFIEDDATE => $now,
			$this->COLUMN_RBCUSTOMERNAME => $data[5] == '' ? null : $data[5],
			$this->COLUMN_RBCREATEDBY => null,
			$this->COLUMN_RBADDITIONALSERVICE5 => null,
			$this->COLUMN_RBADDITIONALSERVICE4 => $data[9] == '' ? null : $data[9],
			$this->COLUMN_RBADDITIONALSERVICE3 => null,
			$this->COLUMN_RBADDITIONALSERVICE2 => $data[13] == '' ? null : $data[13],
			$this->COLUMN_RBADDITIONALSERVICE1 => $data[12] == '' ? null : $data[12],
			$this->COLUMN_RBCHANGESTATUSDATE => null,
			$this->COLUMN_RBCHANGESTATUSBY => $sysuser,
			$this->COLUMN_RBACTIVATEDDATE => $data[3] == '' ? null : ($data[3] == 'Active' ? $now : null),
			$this->COLUMN_RBACTIVATEDBY => $data[3] == '' ? null : ($data[3] == 'Active' ? $sysuser : null),
			$this->COLUMN_RBACCOUNTSTATUS => null,
			$this->COLUMN_RBSVCCODE2 => null,
			$this->COLUMN_RBSVCCODE => $data[8] == '' ? null : $data[8],
			'RBACCOUNTPLAN' => $data[8] == '' ? null : $data[8],
			'RADIUSPOLICY' => $data[8] == '' ? null : $data[8],
			$this->COLUMN_CUSTOMERTYPE => $data[2] == '' ? null : $data[2],
			$this->COLUMN_RBSERVICENUMBER => $data[6] == '' ? null : $data[6],
			$this->COLUMN_RBCHANGESTATUSFROM => null,
			$this->COLUMN_RBSECONDARYACCOUNT => null,
			$this->COLUMN_RBUNLIMITEDACCESS => null, //true
			$this->COLUMN_RBTIMESLOT => null, //'Al0000-2400',
			$this->COLUMN_RBORDERNUMBER => $data[4] == '' ? null : $data[4],
			$this->COLUMN_RBREMARKS => $data[14] == '' ? null : $data[14],
			$this->COLUMN_RBREALM => null, //$realm,
			$this->COLUMN_RBNUMBEROFSESSION => null, //1,
			$this->COLUMN_RBMULTISTATIC => $data[11] == '' ? null : $data[11],
			$this->COLUMN_RBIPADDRESS => $data[10] == '' ? null : $data[10],
			$this->COLUMN_RBENABLED => $data[7] == '' ? null : $data[7]);
		return $subscriber;
	}
	/*
	public function rowDataToSubscriberAddressesArray($data, $realm) {
		$now = date('Y-m-d H:i:s', time());
		$subscriber = array(
			$this->COLUMN_USERIDENTITY => $data[0].'@'.$realm,
			$this->COLUMN_USERNAME => $data[0].'@'.$realm,
			$this->COLUMN_RBMULTISTATIC => $data[10] == '' ? null : $data[10],
			$this->COLUMN_RBIPADDRESS => $data[9] == '' ? null : $data[9],
			$this->COLUMN_LASTMODIFIEDDATE => $now);
		return $subscriber;
	}
	public function rowDataToSubscriberAddressesArrayV2($data, $realm) {
		$now = date('Y-m-d H:i:s', time());
		$subscriber = array(
			$this->COLUMN_USERIDENTITY => $data[0].'@'.$realm,
			$this->COLUMN_USERNAME => $data[0].'@'.$realm,
			$this->COLUMN_RBMULTISTATIC => $data[11] == '' ? null : $data[11],
			$this->COLUMN_RBIPADDRESS => $data[10] == '' ? null : $data[10],
			$this->COLUMN_RBADDITIONALSERVICE4 => $data[9] == '' ? null : $data[9],
			$this->COLUMN_LASTMODIFIEDDATE => $now);
		return $subscriber;
	}
	*/
	public function fetchPlanBoosts() {
		$this->utility->db_select();
		$query = $this->utility->select('boost')
			->from('TBLSPEEDBOOST')
			->order_by('boost', 'desc')
			->get();
		//return array('_DAY', '_NGT', '_INS', '_2Mb', '_3Mb', '_4Mb', '_7Mb', '_9Mb', '_10Mb', '_12Mb', '_13Mb', '_14Mb', '_50Mb');
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	/*****************************************************************************
	 * $activated = 'Y' / 'N'
	 * $creationdate = Y-m-d H:i:s format
	 * $services = array('service1', 'service2', ...)
	 * $preauth = 'N'
	 *****************************************************************************/
	public function npmCreateXML($cn, $password, $activated, $creationdate, $service, $ipaddress, $netaddress, $preauth,
		$npmHost, $npmPort, $npmApi, $npmLogin, $npmPassword, $npmTimeout) {

		// $serviceHasDAY = strpos($service, '_DAY') !== false ? true : false;
		// $serviceHasNGT = strpos($service, '_NGT') !== false ? true : false;
		// $serviceHasINS = strpos($service, '_INS') !== false ? true : false;
		// $serviceHas2Mb = strpos($service, '_2Mb') !== false ? true : false;
		// $serviceHas3Mb = strpos($service, '_3Mb') !== false ? true : false;
		// $serviceHas4Mb = strpos($service, '_4Mb') !== false ? true : false;
		$boosts = $this->fetchPlanBoosts();
		log_message('info', json_encode($boosts));
		$hasBoost = array();
		for ($j = 0; $j < count($boosts); $j++) {
			$hasBoost[] = strpos($service, $boosts[$j]['boost']) !== false ? true : false;
		}
		$noBoost = true;
		for ($j = 0; $j < count($hasBoost); $j++) {
			if ($hasBoost[$j]) {
				$noBoost = false;
			}
		}

		$planNodes = explode('_', $service);
		$planStr = '';
		// if (($serviceHasDAY == false && $serviceHasNGT == false && $serviceHasINS == false) && !($serviceHas2Mb || $serviceHas3Mb || $serviceHas4Mb)) {
		// 	$planStr = '<ServiceSubscription><ServiceId>'.$service.'</ServiceId></ServiceSubscription>';
		// } else {
		// 	for ($n = 0; $n < count($planNodes); $n++) {
		// 		$planStr = $planStr.'<ServiceSubscription><ServiceId>'.$planNodes[$n].'</ServiceId></ServiceSubscription>';
		// 	}
		// }
		if ($noBoost) {
			$planStr = '<ServiceSubscription><ServiceId>'.$service.'</ServiceId></ServiceSubscription>';
		} else {
			for ($n = 0; $n < count($planNodes); $n++) {
				$planStr = $planStr.'<ServiceSubscription><ServiceId>'.$planNodes[$n].'</ServiceId></ServiceSubscription>';
			}
		}

		$xmlStr = ''.
			'<?xml version="1.0" encoding="UTF-8"?>'.
			'<SubscriberAccount>'.
				'<Name>'.$cn.'</Name>'.
				'<Password>'.$password.'</Password>'.
				'<Activated>'.$activated.'</Activated>'.
				'<CreationDate>'.date('Y-m-d H:i:s', $creationdate).'</CreationDate>'.
				'<ServiceSubscriptions>'.
					'<ServiceSubscription>'.
						'<ServiceId>EVO_ACCESS_POST</ServiceId>'.
					'</ServiceSubscription>'.
					$planStr.
				'</ServiceSubscriptions>';
		if (!is_null($ipaddress)) {
			$xmlStr = $xmlStr.
				'<CircuitAttributes>'.
					'<CircuitAttribute>'.
						'<SessionFilter><Session-Name>'.$cn.'</Session-Name></SessionFilter>'.
						(is_null($ipaddress) ? '' : '<Static-IP-Address>'.$ipaddress.'</Static-IP-Address>').
						(is_null($netaddress) ? '' : '<Framed-Route>'.$netaddress.' '.$ipaddress.'</Framed-Route>').
					'</CircuitAttribute>'.
				'</CircuitAttributes>';
		}
		$xmlStr = $xmlStr.'</SubscriberAccount>';
		log_message('info', $xmlStr);

		log_message('info', $npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/user_mgmt?WSDL');
		log_message('info', $npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/');
		log_message('info', 'http://'.urlencode($npmLogin).':'.urlencode($npmPassword).'@'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/user_mgmt?WSDL');
		$options = array(
			'location' => 'http://'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/user_mgmt?WSDL',
			'uri' => 'http://'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/',
			'login' => $npmLogin,
			'password' => $npmPassword,
			'trace' => 1,
			'connection_timeout' => $npmTimeout);
		$client = null;
		try {
			$client = new SoapClient('http://'.urlencode($npmLogin).':'.urlencode($npmPassword).'@'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/user_mgmt?WSDL', $options);
			$result = $client->addSubscriberAccountXML($xmlStr);
			if (!is_null($result)) {
				return array('result' => false, 'error' => json_encode($result));
			} else {
				return array('result' => true);
			}
		} catch (Exception $e) {
			$err = $this->extractErrorMsg($e);
			return array('result' => false, 'error' => $err);
		}
	}
	public function npmFetchXML($cn, $npmHost, $npmPort, $npmApi, $npmLogin, $npmPassword, $npmTimeout) {
		$options = array(
			'location' => 'http://'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/user_mgmt?WSDL',
			'uri' => 'http://'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/',
			'login' => $npmLogin,
			'password' => $npmPassword,
			'trace' => 1,
			'connection_timeout' => $npmTimeout);
		try {
			$client = new SoapClient('http://'.urlencode($npmLogin).':'.urlencode($npmPassword).'@'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/user_mgmt?WSDL', $options);
			$result = $client->getSubscriberAccountXML($cn);
			$subscriber = $this->xmlStrToNPMSubsArray($result);
			return array('found' => true, 'data' => $subscriber);
		} catch (Exception $e) {
			$err = $this->extractErrorMsg($e);
			return array('found' => false, 'error' => $err);
		}
	}
	public function npmUpdateXML($cn, $password, $activated, $service, $ipaddress, $netaddress, $preauth,
		$npmHost, $npmPort, $npmApi, $npmLogin, $npmPassword, $npmTimeout) {
		$data = $this->npmFetchXML($cn, $npmHost, $npmPort, $npmApi, $npmLogin, $npmPassword, $npmTimeout);
		if (!$data['found']) { //subscriber not in npm
			return $data['error'];
		}
		$subscriber = $data['data'];

		// $serviceHasDAY = strpos($service, '_DAY') !== false ? true : false;
		// $serviceHasNGT = strpos($service, '_NGT') !== false ? true : false;
		// $serviceHasINS = strpos($service, '_INS') !== false ? true : false;
		// $serviceHas2Mb = strpos($service, '_2Mb') !== false ? true : false;
		// $serviceHas3Mb = strpos($service, '_3Mb') !== false ? true : false;
		// $serviceHas4Mb = strpos($service, '_4Mb') !== false ? true : false;
		
		$boosts = $this->fetchPlanBoosts();
		$hasBoost = array();
		for ($j = 0; $j < count($boosts); $j++) {
			$hasBoost[] = strpos($service, $boosts[$j]['boost']) !== false ? true : false;
		}
		$noBoost = true;
		for ($j = 0; $j < count($hasBoost); $j++) {
			if ($hasBoost[$j]) {
				$noBoost = false;
			}
		}

		$planNodes = explode('_', $service);
		$planStr = '';
		// if (($serviceHasDAY == false && $serviceHasNGT == false && $serviceHasINS == false) && !($serviceHas2Mb || $serviceHas3Mb || $serviceHas4Mb)) {
		// 	$planStr = '<ServiceSubscription><ServiceId>'.$service.'</ServiceId></ServiceSubscription>';
		// } else {
		// 	for ($n = 0; $n < count($planNodes); $n++) {
		// 		$planStr = $planStr.'<ServiceSubscription><ServiceId>'.$planNodes[$n].'</ServiceId></ServiceSubscription>';
		// 	}
		// }

		if ($noBoost) {
			$planStr = '<ServiceSubscription><ServiceId>'.$service.'</ServiceId></ServiceSubscription>';
		} else {
			for ($n = 0; $n < count($planNodes); $n++) {
				$planStr = $planStr.'<ServiceSubscription><ServiceId>'.$planNodes[$n].'</ServiceId></ServiceSubscription>';
			}
		}

		$xmlStr = ''.
			'<?xml version="1.0" encoding="UTF-8"?>'.
				'<SubscriberAccount>'.
					'<Name>'.$cn.'</Name>'.
					'<Password>'.$password.'</Password>'.
					'<Activated>'.$activated.'</Activated>'.
					'<CreationDate>'.$subscriber['CREATED'].'</CreationDate>'.
					'<ServiceSubscriptions>'.
						'<ServiceSubscription>'.
							'<ServiceId>EVO_ACCESS_POST</ServiceId>'.
						'</ServiceSubscription>'.
						$planStr.
					'</ServiceSubscriptions>';
		$netAddrParts = explode(' ', trim($netaddress));
		if (!is_null($ipaddress)) {
			$xmlStr = $xmlStr.
					'<CircuitAttributes>'.
						'<CircuitAttribute>'.
							'<SessionFilter><Session-Name>'.$cn.'</Session-Name></SessionFilter>'.
							(is_null($ipaddress) ? '' : '<Static-IP-Address>'.$ipaddress.'</Static-IP-Address>').
							(is_null($netaddress) ? '' : '<Framed-Route>'.$netaddress.(count($netAddrParts) == 1 ? ' '.$ipaddress : '').'</Framed-Route>').
						'</CircuitAttribute>'.
					'</CircuitAttributes>';
		}
		$xmlStr = $xmlStr.
				'</SubscriberAccount>';
		log_message('info', $xmlStr);

		$options = array(
			'location' => 'http://'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/user_mgmt?WSDL',
			'uri' => 'http://'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/',
			'login' => $npmLogin,
			'password' => $npmPassword,
			'trace' => 1,
			'connection_timeout' => $npmTimeout);
		$client = null;
		try {
			$client = new SoapClient('http://'.urlencode($npmLogin).':'.urlencode($npmPassword).'@'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/user_mgmt?WSDL', $options);
			$result = $client->updateSubscriberAccountXML($xmlStr);
			if (!is_null($result)) {
				return array('result' => false, 'error' => json_encode($result));
			} else {
				if ($activated == 'N') {
					$this->load->model('onlinesession');
					$this->onlinesession->npmClearSession($cn, $npmHost, $npmPort, $npmApi, $npmLogin, $npmPassword);
					sleep(10);
				}
				return array('result' => true);
			}
		} catch (Exception $e) {
			$err = $this->extractErrorMsg($e);
			return array('result' => false, 'error' => $err);
		}
	}
	public function npmRemoveSubscriber($cn, $npmHost, $npmPort, $npmApi, $npmLogin, $npmPassword, $npmTimeout) {
		$options = array(
			'location' => 'http://'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/user_mgmt?WSDL',
			'uri' => 'http://'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/',
			'login' => $npmLogin,
			'password' => $npmPassword,
			'trace' => 1,
			'connection_timeout' => $npmTimeout);
		$client = null;
		$result = null;
		try {
			$client = new SoapClient('http://'.urlencode($npmLogin).':'.urlencode($npmPassword).'@'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/user_mgmt?WSDL', $options);
			$subs = $this->npmFetchXML($cn, $npmHost, $npmPort, $npmApi, $npmLogin, $npmPassword, $npmTimeout);
			if ($subs['found']) {
				// $result = $client->removeSubscriberAccount($cn);
				// return array('deleted' => true);
			} else {
				return array('deleted' => true);
			}
		} catch (Exception $e) {
			$err = $this->extractErrorMsg($e);
			return array('deleted' => false, 'error' => $err);
		}
		$s = $subs['data'];
		try {
			$netParts = explode(' ', $s['RBMULTISTATIC']);
			$deactivated = $this->npmUpdateXML($s['USERNAME'], $s['PASSWORD'], 'N', $s['RADIUSPOLICY'], $s['RBIPADDRESS'], $netParts[0], 'N',
				$npmHost, $npmPort, $npmApi, $npmLogin, $npmPassword, $npmTimeout);
			sleep(2);
			if ($deactivated['result']) {
				$this->load->model('onlinesession');
				$parts = explode('@', $s['USERNAME']);
				$sessions = $this->onlinesession->npmGetSessions($parts[0], isset($parts[1]) ? $parts[1] : "", $npmHost, $npmPort, $npmApi, $npmLogin, $npmPassword);
				if ($sessions['data'] !== false) {
					$this->onlinesession->npmClearSession($cn, $npmHost, $npmPort, $npmApi, $npmLogin, $npmPassword);
					sleep(10);
				}
			} else {
				return array('deleted' => false, 'error' => 'failed to deactivate account: '.$s['USERNAME']);
			}
		} catch (Exception $e) {
			$err = $this->extractErrorMsg($e);
			return array('deleted' => false, 'error' => $err);			
		}
		try {
			$client->removeSubscriberAccount($cn);
			$check = $this->npmFetchXML($s['USERNAME'], $npmHost, $npmPort, $npmApi, $npmLogin, $npmPassword, $npmTimeout);
			if ($check['found']) {
				return array('deleted' => false, 'error' => 'failed to delete account');
			} else {
				return array('deleted' => true);
			}
		} catch (Exception $e) {
			$err = $this->extractErrorMsg($e);
			log_message('info', $err);
			return array('deleted' => false, 'error' => $err);
		}
	}
	public function xmlStrToNPMSubsArray($xmlStr) {
		$namestart = strpos($xmlStr, '<Name>');
		$nameend = strpos($xmlStr, '</Name>');
		$name = substr($xmlStr, $namestart + strlen('<Name>'), $nameend - $namestart - strlen('</Name>') + 1);
		$passwordstart = strpos($xmlStr, '<Password>');
		$passwordend = strpos($xmlStr, '</Password>');
		$password = substr($xmlStr, $passwordstart + strlen('<Password>'), $passwordend - $passwordstart - strlen('</Password>') + 1);
		$activatedstart = strpos($xmlStr, '<Activated>');
		$activatedend = strpos($xmlStr, '</Activated>');
		$activated = substr($xmlStr, $activatedstart + strlen('<Activated>'), $activatedend - $activatedstart - strlen('</Activated>') + 1);
		$createdstart = strpos($xmlStr, '<CreationDate>');
		$createdend = strpos($xmlStr, '</CreationDate>');
		$created = substr($xmlStr, $createdstart + strlen('<CreationDate>'), $createdend - $createdstart - strlen('</CreationDate>') + 1);
		$planstart = strpos($xmlStr, '<ServiceId>');
		$planend = strpos($xmlStr, '</ServiceId>');
		$plan1 = substr($xmlStr, $planstart + strlen('<ServiceId>'), $planend - $planstart - strlen('</ServiceId>') + 1);
		$planstart = strpos($xmlStr, '<ServiceId>', $planstart + 2);
		$planend = strpos($xmlStr, '</ServiceId>', $planstart);
		$plan2 = substr($xmlStr, $planstart + strlen('<ServiceId>'), $planend - $planstart - strlen('</ServiceId>') + 1);
		$ipstart = strpos($xmlStr, '<Static-IP-Address>');
		if ($ipstart !== false) {
			$ipend = strpos($xmlStr, '</Static-IP-Address>');
			$ip = substr($xmlStr, $ipstart + strlen('<Static-IP-Address>'), $ipend - $ipstart - strlen('</Static-IP-Address>') + 1);
		}
		$netstart = strpos($xmlStr, '<Framed-Route>');
		if ($netstart !== false) {
			$netend = strpos($xmlStr, '</Framed-Route>');
			$net = substr($xmlStr, $netstart + strlen('<Framed-Route>'), $netend - $netstart - strlen('</Framed-Route>') + 1);
		}
		$subscriber = array(
			'USERNAME' => $name,
			'PASSWORD' => $password,
			'ACTIVATED' => $activated,
			'CREATED' => $created,
			'RADIUSPOLICY' => $plan1 == 'EVO_ACCESS_POST' ? $plan2 : $plan1,
			'RBIPADDRESS' => $ipstart !== false ? $ip : null,
			'RBMULTISTATIC' => $netstart !== false ? $net : null);
		return $subscriber;
	}
	public function extractErrorMsg($errorObj) {
		$error = json_encode($errorObj);
		$start = '"detail":{"';
		$end = '":{"enc_type"';
		$errorP1 = substr($error, strpos($error, $start) + strlen($start), strpos($error, $end) - strpos($error, $start) - strlen($end) + 2);
		$start = '{"message":"';
		$end = '},"enc_stype';
		$errorP2 = substr($error, strpos($error, $start) + strlen($start), strpos($error, $end) - strpos($error, $start) - strlen($end) - 1);
		return $errorP1.': '.$errorP2;
	}

	/*****************************************************************************
	 * report generation
	 *****************************************************************************/
	public function reportSubscribersWithStatus($realm, $status, $start, $max) {
		$this->utility->db_select();
		$this->utility->select('*')
			->from($this->TABLENAME);
		if (!is_null($realm)) {
			$this->utility->where($this->COLUMN_RBREALM, $realm);
		}
		$this->utility->where($this->COLUMN_CUSTOMERSTATUS, $status);
		if (is_null($realm)) {
			$this->utility->order_by($this->COLUMN_RBREALM, 'ASC');
		}
		$query = $this->utility->order_by($this->COLUMN_USERNAME, 'ASC')
			->limit($start == 0 ? $max + 1 : $max, $start == 0 ? $start : $start + 1)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function reportSubscribersWithStatusAll($realm, $status) {
		$this->utility->db_select();
		$this->utility->select('*')
			->from($this->TABLENAME);
		if (!is_null($realm)) {
			$this->utility->where($this->COLUMN_RBREALM, $realm);
		}
		$this->utility->where($this->COLUMN_CUSTOMERSTATUS, $status);
		if (is_null($realm)) {
			$this->utility->order_by($this->COLUMN_RBREALM, 'ASC');
		}
		$query = $this->utility->order_by($this->COLUMN_USERNAME, 'ASC')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSubscribersWithStatus($realm, $status) {
		$this->utility->db_select();
		$this->utility->from($this->TABLENAME);
		if (!is_null($realm)) {
			$this->utility->where($this->COLUMN_RBREALM, $realm);
		}
		$count = $this->utility->where($this->COLUMN_CUSTOMERSTATUS, $status)
			->count_all_results();
		return $count;
	}
	public function reportSubscribersWithService($realm, $service, $start, $max) {
		$this->utility->db_select();
		$this->utility->select('*')
			->from($this->TABLENAME);
		if (!is_null($realm)) {
			$this->utility->where($this->COLUMN_RBREALM, $realm);
		}
		// $this->utility->where($this->COLUMN_RADIUSPOLICY, str_replace('-', '~', $service));
		$this->utility->where('RBACCOUNTPLAN', str_replace('~', '-', $service));
		if (is_null($realm)) {
			$this->utility->order_by($this->COLUMN_RBREALM, 'ASC');
		}
		$query = $this->utility->order_by($this->COLUMN_USERNAME, 'ASC')
			->limit($start == 0 ? $max + 1 : $max, $start == 0 ? $start : $start + 1)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function reportSubscribersWithServiceAll($realm, $service) {
		$this->utility->db_select();
		$this->utility->select('*')
			->from($this->TABLENAME);
		if (!is_null($realm)) {
			$this->utility->where($this->COLUMN_RBREALM, $realm);
		}
		// $this->utility->where($this->COLUMN_RADIUSPOLICY, str_replace('-', '~', $service));
		$this->utility->where('RBACCOUNTPLAN', str_replace('~', '-', $service));
		if (is_null($realm)) {
			$this->utility->order_by($this->COLUMN_RBREALM, 'ASC');
		}
		$query = $this->utility->order_by($this->COLUMN_USERNAME, 'ASC')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSubscribersWithService($realm, $service) {
		$this->utility->db_select();
		$this->utility->from($this->TABLENAME);
		if (!is_null($realm)) {
			$this->utility->where($this->COLUMN_RBREALM, $realm);
		}
		$count = $this->utility->where('RBACCOUNTPLAN', str_replace('~', '-', $service))
		// $count = $this->utility->where($this->COLUMN_RADIUSPOLICY, str_replace('-', '~', $service))
			->count_all_results();
		return $count;
	}
	public function reportSubscribersCreatedWithinDates($realm, $datestart, $dateend, $start, $max) {
		$this->utility->db_select();
		$this->utility->select('*')
			->from($this->TABLENAME);
		if (!is_null($realm)) {
			$this->utility->where($this->COLUMN_RBREALM, $realm);
		}
		$startStr = date('Y-m-d', $datestart);
		$endStr = date('Y-m-d', $dateend);
		if ($startStr == $endStr) {
			$dateend = $dateend + (60 * 60 * 24);
			$endStr = date('Y-m-d', $dateend);
			$this->utility->where($this->COLUMN_CREATEDATE." >= TO_TIMESTAMP('".substr($startStr, 2, strlen($startStr))."', 'RR-MM-DD')")
				->where($this->COLUMN_CREATEDATE." < TO_TIMESTAMP('".substr($endStr, 2, strlen($endStr))."', 'RR-MM-DD')");
		} else {
			$this->utility->where($this->COLUMN_CREATEDATE." >= TO_TIMESTAMP('".substr($startStr, 2, strlen($startStr))."', 'RR-MM-DD')")
				->where($this->COLUMN_CREATEDATE." <= TO_TIMESTAMP('".substr($endStr, 2, strlen($endStr))."', 'RR-MM-DD')");
		}
		if (is_null($realm)) {
			$this->utility->order_by($this->COLUMN_RBREALM, 'ASC');
		}
		$query = $this->utility->order_by($this->COLUMN_USERNAME, 'ASC')
			->limit($start == 0 ? $max + 1 : $max, $start == 0 ? $start : $start + 1)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function reportSubscribersCreatedWithinDatesAll($realm, $datestart, $dateend) {
		$this->utility->db_select();
		$this->utility->select('*')
			->from($this->TABLENAME);
		if (!is_null($realm)) {
			$this->utility->where($this->COLUMN_RBREALM, $realm);
		}
		$startStr = date('Y-m-d', $datestart);
		$endStr = date('Y-m-d', $dateend);
		if ($startStr == $endStr) {
			$dateend = $dateend + (60 * 60 * 24);
			$endStr = date('Y-m-d', $dateend);
			$this->utility->where($this->COLUMN_CREATEDATE." >= TO_TIMESTAMP('".substr($startStr, 2, strlen($startStr))."', 'RR-MM-DD')")
				->where($this->COLUMN_CREATEDATE." < TO_TIMESTAMP('".substr($endStr, 2, strlen($endStr))."', 'RR-MM-DD')");
		} else {
			$this->utility->where($this->COLUMN_CREATEDATE." >= TO_TIMESTAMP('".substr($startStr, 2, strlen($startStr))."', 'RR-MM-DD')")
				->where($this->COLUMN_CREATEDATE." <= TO_TIMESTAMP('".substr($endStr, 2, strlen($endStr))."', 'RR-MM-DD')");
		}
		if (is_null($realm)) {
			$this->utility->order_by($this->COLUMN_RBREALM, 'ASC');
		}
		$query = $this->utility->order_by($this->COLUMN_USERNAME, 'ASC')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSubscribersCreatedWithinDates($realm, $datestart, $dateend) {
		$this->utility->db_select();
		$this->utility->from($this->TABLENAME);
		if (!is_null($realm)) {
			$this->utility->where($this->COLUMN_RBREALM, $realm);
		}
		$startStr = date('Y-m-d', $datestart);
		$endStr = date('Y-m-d', $dateend);
		$count = 0;
		if ($startStr == $endStr) {
			$dateend = $dateend + (60 * 60 * 24);
			$endStr = date('Y-m-d', $dateend);
			$count = $this->utility->where($this->COLUMN_CREATEDATE." >= TO_TIMESTAMP('".substr($startStr, 2, strlen($startStr))."', 'RR-MM-DD')")
				->where($this->COLUMN_CREATEDATE." < TO_TIMESTAMP('".substr($endStr, 2, strlen($endStr))."', 'RR-MM-DD')")
				->count_all_results();
		} else {
			$count = $this->utility->where($this->COLUMN_CREATEDATE." >= TO_TIMESTAMP('".substr($startStr, 2, strlen($startStr))."', 'RR-MM-DD')")
				->where($this->COLUMN_CREATEDATE." <= TO_TIMESTAMP('".substr($endStr, 2, strlen($endStr))."', 'RR-MM-DD')")
				->count_all_results();
		}
		return $count;
	}
	public function reportSubscribersWithStaticIp($realm, $start, $max) {
		$this->utility->db_select();
		$this->utility->select('*')
			->from($this->TABLENAME);
		if (!is_null($realm)) {
			$this->utility->where($this->COLUMN_RBREALM, $realm);
		}
		$this->utility->where("RBIPADDRESS IS NOT NULL");
		if (is_null($realm)) {
			$this->utility->order_by($this->COLUMN_RBREALM, 'ASC');
		}
		$query = $this->utility->order_by($this->COLUMN_USERNAME, 'ASC')
			->limit($start == 0 ? $max + 1 : $max, $start == 0 ? $start : $start + 1)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function reportSubscribersWithStaticIpV2($realm, $start, $max) {
		$this->utility->db_select();
		$this->utility->select('CUST.*, IP.LOCATION')
			->from($this->TABLENAME.' CUST')
			->join('TBLIPADDRESS IP', 'CUST.RBIPADDRESS = IP.IPADDRESS');
		if (!is_null($realm)) {
			$this->utility->where('CUST.'.$this->COLUMN_RBREALM, $realm);
		}
		$this->utility->where("CUST.RBIPADDRESS IS NOT NULL");
		if (is_null($realm)) {
			$this->utility->order_by('CUST.'.$this->COLUMN_RBREALM, 'ASC');
		}
		$query = $this->utility->order_by('CUST.'.$this->COLUMN_USERNAME, 'ASC')
			->limit($start == 0 ? $max + 1 : $max, $start == 0 ? $start : $start + 1)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function reportSubscribersWithStaticIpAll($realm) {
		$this->utility->db_select();
		$this->utility->select('*')
			->from($this->TABLENAME);
		if (!is_null($realm)) {
			$this->utility->where($this->COLUMN_RBREALM, $realm);
		}
		$this->utility->where("RBIPADDRESS IS NOT NULL");
		if (is_null($realm)) {
			$this->utility->order_by($this->COLUMN_RBREALM, 'ASC');
		}
		$query = $this->utility->order_by($this->COLUMN_USERNAME, 'ASC')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSubscribersWithStaticIp($realm) {
		$this->utility->db_select();
		$this->utility->from($this->TABLENAME);
		if (!is_null($realm)) {
			$this->utility->where($this->COLUMN_RBREALM, $realm);
		}
		$count = $this->utility->where("RBIPADDRESS IS NOT NULL")
			->count_all_results();
		return $count;
	}
	public function reportSubscribersWithStaticIpAndMultistaticIp($realm, $start, $max) {
		$this->utility->db_select();
		$this->utility->select('*')
			->from($this->TABLENAME);
		if (!is_null($realm)) {
			$this->utility->where($this->COLUMN_RBREALM, $realm);
		}
		$this->utility->where("RBIPADDRESS IS NOT NULL")
			->where("RBMULTISTATIC IS NOT NULL");
		if (is_null($realm)) {
			$this->utility->order_by($this->COLUMN_RBREALM, 'ASC');
		}
		$query = $this->utility->order_by($this->COLUMN_USERNAME, 'ASC')
			->limit($start == 0 ? $max + 1 : $max, $start == 0 ? $start : $start + 1)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function reportSubscribersWithStaticIpAndMultistaticIpV2($realm, $start, $max) {
		$this->utility->db_select();
		$this->utility->select('CUST.*, IP.LOCATION')
			->from($this->TABLENAME.' CUST')
			->join('TBLIPADDRESS IP', 'CUST.RBIPADDRESS = IP.IPADDRESS');
		if (!is_null($realm)) {
			$this->utility->where('CUST.'.$this->COLUMN_RBREALM, $realm);
		}
		$this->utility->where("CUST.RBIPADDRESS IS NOT NULL")
			->where("CUST.RBMULTISTATIC IS NOT NULL");
		if (is_null($realm)) {
			$this->utility->order_by('CUST.'.$this->COLUMN_RBREALM, 'ASC');
		}
		$query = $this->utility->order_by('CUST.'.$this->COLUMN_USERNAME, 'ASC')
			->limit($start == 0 ? $max + 1 : $max, $start == 0 ? $start : $start + 1)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function reportSubscribersWithStaticIpAndMultistaticIpAll($realm) {
		$this->utility->db_select();
		$this->utility->select('*')
			->from($this->TABLENAME);
		if (!is_null($realm)) {
			$this->utility->where($this->COLUMN_RBREALM, $realm);
		}
		$this->utility->where("RBIPADDRESS IS NOT NULL")
			->where("RBMULTISTATIC IS NOT NULL");
		if (is_null($realm)) {
			$this->utility->order_by($this->COLUMN_RBREALM, 'ASC');
		}
		$query = $this->utility->order_by($this->COLUMN_USERNAME, 'ASC')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSubscribersWithStaticIpAndMultistaticIp($realm) {
		$this->utility->db_select();
		$this->utility->from($this->TABLENAME);
		if (!is_null($realm)) {
			$this->utility->where($this->COLUMN_RBREALM, $realm);
		}
		$count = $this->utility->where("RBIPADDRESS IS NOT NULL")
			->where("RBMULTISTATIC IS NOT NULL")
			->count_all_results();
		return $count;
	}
	public function reportCappedSubscribers($realm, $datestart, $dateend, $start, $max) {
		$this->extras->db_select();
		$this->extras->select('id, username, radiuspolicy, upload_octets, download_octets, total_octets, hsqvalue, capped_date')
			->from('capped_users')
			->where('(DATE(capped_date) > DATE("'.date('Y-m-d H:i:s', $datestart).'") or DATE(capped_date) = DATE("'.date('Y-m-d H:i:s', $datestart).'"))')
			->where('(DATE(capped_date) < DATE("'.date('Y-m-d H:i:s', $dateend).'") or DATE(capped_date) = DATE("'.date('Y-m-d H:i:s', $dateend).'"))');
		if (!is_null($realm)) {
			$this->extras->where("instr(username, '".$realm."') > 0");
		}
		$query = $this->extras->order_by('capped_date', 'desc')
			->limit($max, $start)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countCappedSubscribers($realm, $datestart, $dateend) {
		$this->extras->db_select();
		$this->extras->from('capped_users')
			->where('(DATE(capped_date) > DATE("'.date('Y-m-d H:i:s', $datestart).'") or DATE(capped_date) = DATE("'.date('Y-m-d H:i:s', $datestart).'"))')
			->where('(DATE(capped_date) < DATE("'.date('Y-m-d H:i:s', $dateend).'") or DATE(capped_date) = DATE("'.date('Y-m-d H:i:s', $dateend).'"))');
		if (!is_null($realm)) {
			$this->extras->where("instr(username, '".$realm."') > 0");
		}
		$count = $this->extras->count_all_results();
		return $count;
	}

	public function createSubscriberXML($subscriber) {
		$xml = new SimpleXMLElement('<SubscriberAccount />');
		$xml->addChild('Name', $subscriber['USERNAME']);
		$xml->addChild('Password', $subscriber['PASSWORD']);
		$theStatus = $subscriber['CUSTOMERSTATUS'] == 'Active' ? 'Y' : 'N';
		$xml->addChild('Activated', $theStatus);
		$serviceSubscriptions = $xml->addChild('ServiceSubscriptions');
		$evoAccessPost = $serviceSubscriptions->addChild('ServiceSubscription');
		$evoAccessPost->addChild('ServiceId', 'EVO_ACCESS_POST');
		$planNode = $serviceSubscriptions->addChild('ServiceSubscription');
		$thePlan = str_replace('~', '-', $subscriber['RADIUSPOLICY']);
		$planNode->addChild('ServiceId', $thePlan);
		$xml->addChild('ServiceNumber', $subscriber['RBSERVICENUMBER']);
		$xml->addChild('OrderNumber', $subscriber['RBORDERNUMBER']);
		return $xml;
	}
	public function createSubscriberDeleteList($pathTo, $filename, $subscribers) {
		$subscriberCount = count($subscribers);
		if ($subscriberCount == 0) {
			return false;
		}
		for ($i = 0; $i < $subscriberCount; $i++) {
			$thisSubscriber = $subscribers[$i];
			file_put_contents($pathTo.$filename, $thisSubscriber."\n", FILE_APPEND | LOCK_EX);
		}
		return true;
	}
}
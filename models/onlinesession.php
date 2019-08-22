<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Onlinesession extends CI_Model {
	private $utility;
	private $extras;
	private $WSDL = 'http://10.188.119.114:48050/eliteradius/services/eliteRadiusDynAuthWS?wsdl';
	private $AAASOAPHOST = '10.188.119.114';
	private $AAASOAPPORT = '48050';

	private $NPM_URI = '10.174.241.65:8080/NPM_API-11.1.1.5/';
	private $NPM_URL = '10.174.241.65:8080/NPM_API-11.1.1.5/session_mgmt?WSDL';
	private $NPM_LOGIN = 'lek';
	private $NPM_PASSWORD = 'seron';

	private $SESSIONHOST2 = '10.188.119.155';
	private $SESSIONPORT2 = '1521';
	private $SESSIONSCHEMA2 = 'eliteaaa';
	private $SESSIONUSERNAME2 = 'etplprov';
	private $SESSIONPASSWORD2 = 'etplprov';
	private $AAASOAPHOST2 = '10.188.119.114';
	private $AAASOAPPORT2 = '48050';

	function __construct() {
		parent::__construct();
		$this->utility = $this->load->database('utility', true);
		$this->extras = $this->load->database('extras', true);
	}

	public function getSessions2($username, $realm, $checkOtherTable, $host1, $port1, $schema1, $username1, $password1, $host2, $port2, $schema2, $username2, $password2) {
		$sessions = array();
		log_message('debug', '@'.$host1.':'.$port1.'/'.$schema1.', '.$username1.'/'.$password1);
		log_message('debug', '@'.$host2.':'.$port2.'/'.$schema2.', '.$username2.'/'.$password2);
		$url1 = $host1.':'.$port1.'/'.$schema1;
		$conn1 = oci_connect($username1, $password1, $url1);
		if ($conn1 === false) {
			return array('status' => false, 'message' => 'Could not connect to first session database.');
		}
		$sql = "select * from TBLMCONCURRENTUSERS where USER_NAME = :username";
		$compiled = oci_parse($conn1, $sql);
		$usernameFull = $username.'@'.$realm;
		oci_bind_by_name($compiled, ':username', $usernameFull);
		$result = oci_execute($compiled);
		if ($result === false) {
			return array('status' => false, 'message' => 'Query (1) error.');
		}
		while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
			$sessions[] = $row;
		}
		$skip2 = false;
		if (count($sessions) != 0) {
			$location = '1';
			$skip2 = true;
		} else {
			$location = '2';
		}
		if ($checkOtherTable && !$skip2) {
			log_message('debug', '                                                                 ');
			$url2 = $host2.':'.$port2.'/'.$schema2;
			$conn2 = oci_connect($username2, $password2, $url2);
			if ($conn2 === false) {
				return array('status' => false, 'message' => 'Could not connect to second session database.');
			}
			$sql = "select * from TBLMCONCURRENTUSERS where USER_NAME = :username";
			$compiled = oci_parse($conn2, $sql);
			oci_bind_by_name($compiled, ':username', $usernameFull);
			$result = oci_execute($compiled);
			if ($result === false) {
				return array('status' => false, 'message' => 'Query (2) error.');
			}
			while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
				log_message('debug', '                                 '.json_encode($row));
				$sessions[] = $row;
			}
		}
		log_message('info', '@getSessions|'.$username.'@'.$realm.'|'.json_encode($sessions));
		if (count($sessions) == 0) {
			return array('status' => false, 'message' => 'No sessions found.');
		} else {
			return array('status' => true, 'data' => $sessions, 'location' => $location);
		}
	}
	public function getSessions($username, $realm, $checkOtherTable, $sessionHost2, $sessionPort2, $sessionSchema2, $sessionUsername2, $sessionPassword2) {
		$sessions = array();
		$location = '';
		$this->utility->db_select();
		$query = $this->utility->select('*')
			->from('TBLCONCURRENTUSERS')
			->where('USER_NAME', $username.'@'.$realm)
			->get();
		$fromTable1 = $query->num_rows() == 0 ? false : $query->result_array();
		if ($fromTable1 !== false) {
			for ($i = 0; $i < count($fromTable1); $i++) {
				$sessions[] = $fromTable1[$i];
			}
		}
		log_message('info', '@getSessions|'.$username.'|otherTable:'.json_encode($checkOtherTable).'|'.json_encode($sessions));
		$skip2 = false;
		if (count($sessions) != 0) {
			$location = '1';
			$skip2 = true;
		} else {
			$location = '2';
		}
		if ($checkOtherTable && !$skip2) {
			try {
				$url = $sessionHost2.':'.$sessionPort2.'/'.$sessionSchema2;
				$conn = oci_connect($sessionUsername2, $sessionPassword2, $url);
				if ($conn === false) {
					return array('status' => false, 'message' => 'Could not connect to second database.');
				}
				$sql = "select * ".
					"from TBLCONCURRENTUSERS where USER_NAME = '".$username."@".$realm."'";
				$compiled = oci_parse($conn, $sql);
				$result = oci_execute($compiled);
				if ($result === false) {
					return array('status' => false, 'message' => 'Select error.');
				}
				while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
					$sessions[] = $row;
				}
			} catch (Exception $e) {
				log_message('info', 'There was a problem connecting to second table.');
			}				
		}
		log_message('info', '@getSessions|'.$username.'|'.json_encode($sessions));
		if (count($sessions) == 0) {
			return array('status' => false, 'message' => 'No sessions found.');
		} else {
			return array('status' => true, 'data' => $sessions, 'location' => $location);
		}
	}
	public function getVolumeUsageData($username, $realm, $plan, $sessionHost2 = null, $sessionPort2 = null, $sessionSchema2 = null, 
			$sessionUsername2 = null, $sessionPassword2 = null) {
		$usageData = null;
		$this->utility->db_select();
		$usernameFull = $username.'@'.$realm;
		$planFixed = str_replace('~', '-', $plan);
		$query = $this->utility->select('USERID, POLICYGROUPNAME, UPLOADOCTETS, DOWNLOADOCTETS, TOTALOCTETS, HSQVALUE')
			->from('TBLVOLUMEUSAGE')
			->where('USERID', $usernameFull)
			->where('POLICYGROUPNAME', $planFixed)
			->get();
		$from1 = $query->result_array();
		if (count($from1) != 0) {
			$usageData = array('USERID' => $from1[0]['USERID'], 'PLAN' => $from1[0]['POLICYGROUPNAME'], 'UPLOADOCTETS' => $from1[0]['UPLOADOCTETS'],
				'DOWNLOADOCTETS' => $from1[0]['DOWNLOADOCTETS'], 'VOLUMEUSAGE' => $from1[0]['TOTALOCTETS'], 'VOLUMEQUOTA' => $from1[0]['HSQVALUE']);
		}
		log_message('info', 'from db1:'.json_encode($usageData));
		$url = $sessionHost2.':'.$sessionPort2.'/'.$sessionSchema2;
		$conn = oci_connect($sessionUsername2, $sessionPassword2, $url);
		$sql = "select USERID, POLICYGROUPNAME as PLAN, UPLOADOCTETS, DOWNLOADOCTETS, TOTALOCTETS as VOLUMEUSAGE, HSQVALUE as VOLUMEQUOTA from TBLVOLUMEUSAGE ".
			"where USERID = :username and POLICYGROUPNAME = :plan ";
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':username', $usernameFull);
		oci_bind_by_name($compiled, ':plan', $planFixed);
		$result = oci_execute($compiled);
		$tmp = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		// test
		// $tmp = array('USERID' => 'dole312@globelines.com.ph', 'POLICYGROUPNAME' => 'LG-3M-7G', 'UPLOADOCTETS' => '5', 'DOWNLOADOCTETS' => '15', 'TOTALOCTETS' => '20', 'HSQVALUE' => '7516192768');
		log_message('info', 'from db2:'.json_encode($tmp));
		if (is_null($usageData)) {
			if ($tmp === false || is_null($tmp)) {

			} else {
				$usageData = $tmp;
			}
		} else {
			if ($tmp === false || is_null($tmp)) {

			} else {
				$dlSum = floatval($tmp['DOWNLOADOCTETS']) + floatval($usageData['DOWNLOADOCTETS']);
				$ulSum = floatval($tmp['UPLOADOCTETS']) + floatval($usageData['UPLOADOCTETS']);
				$sum = floatval($tmp['VOLUMEUSAGE']) + floatval($usageData['VOLUMEUSAGE']);
				$usageData['DOWNLOADOCTETS'] = strval($dlSum);
				$usageData['UPLOADOCTETS'] = strval($ulSum);
				$usageData['VOLUMEUSAGE'] = strval($sum);
			}
		}
		log_message('info', 'end:'.json_encode($usageData));
		if (is_null($usageData)) {
			return false;
		} else {
			return $usageData;
		}
	}
	public function requestCOAExt($username, $aaaSoapUrl, $aaaSoapPort, $checkOtherTable, $aaaSoapUrl2, $aaaSoapPort2, $sessionid, $nasip,
		$sessionHost = null, $sessionPort = null, $sessionSchema = null, $sessionUsername = null, $sessionPassword = null,
		$sessionHost2 = null, $sessionPort2 = null, $sessionSchema2 = null, $sessionUsername2 = null, $sessionPassword2 = null,
		$deleteschema = null, $qmusername = null, $qmpassword = null) {
		$wsdl = 'http://'.$aaaSoapUrl.':'.$aaaSoapPort.'/eliteradius/services/eliteRadiusDynAuthWS?wsdl';
		$wsdl2 = 'http://'.$aaaSoapUrl2.':'.$aaaSoapPort2.'/eliteradius/services/eliteRadiusDynAuthWS?wsdl';
		log_message('debug', 'delete session wsdls: '.$wsdl);
		log_message('debug', 'delete session wsdls: '.$wsdl2);
		try {
			$client = new SoapClient($wsdl);
			$map = array('0:4' => $nasip, '0:44' => $sessionid);
			log_message('info', '@requestCOAExt|'.$username.'|map:'.json_encode($map));
		} catch (Exception $e) {
			$error = json_encode($e);
			log_message('info', $error);	
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			return array('result' => false, 'error' => $err);
		}

		try {
			$result = $client->requestDisconnect($username, $map);
			log_message('info', '@requestCOAExt|'.$username.'|result1: '.json_encode($result));
			log_message('info', '@requestCOAExt|otherTable:'.$checkOtherTable);
			if ($checkOtherTable) {
				$client2 = new SoapClient($wsdl2);
				$result = $client2->requestDisconnect($username, $map);
				log_message('info', '@requestCOAExt|'.$username.'|result2: '.json_encode($result));
			}
			//*
			//delete in ELITEAAA.TBLMCONCURRENTUSERS (1)
			$sql = "delete from ".$deleteschema.".TBLMCONCURRENTUSERS where USER_NAME = '".$username."'";
			$url = $sessionHost.':'.$sessionPort.'/'.$sessionSchema;
			$conn = oci_connect($sessionUsername, $sessionPassword, $url);
			log_message('info', '@requestCOAExt|'.$username.'|url:'.$url.'|username:'.$sessionUsername.'|password:'.$sessionPassword.'|conn:'.($conn === false ? 'fail' : 'ok'));
			$compiled = oci_parse($conn, $sql);
			$result = oci_execute($compiled);
			log_message('info', '@requestCOAExt|'.$username.'|delete@TBLMCONCURRENTUSERS1:'.json_encode($result));
			if ($checkOtherTable) {
				//delete in ELITEAAA.TBLMCONCURRENTUSERS (2)
				$url2 = $sessionHost2.':'.$sessionPort2.'/'.$sessionSchema2;
				$conn2 = oci_connect($sessionUsername, $sessionPassword, $url2);
				log_message('info', '@requestCOAExt|'.$username.'|url:'.$url2.'|username:'.$sessionUsername2.'|password:'.$sessionPassword2.'|conn:'.($conn2 === false ? 'fail' : 'ok'));
				$compiled2 = oci_parse($conn2, $sql);
				$result2 = oci_execute($compiled2);
				log_message('info', '@requestCOAExt|'.$username.'|delete@TBLMCONCURRENTUSERS2:'.json_encode($result2));
			}
			//*
			//delete in QM.TBLMCORESESSIONS (1)
			$qmUrl = $sessionHost.':'.$sessionPort.'/'.$sessionSchema;
			$qmConn = oci_connect($qmusername, $qmpassword, $qmUrl);
			log_message('info', '@requestCOAExt|'.$username.'|url:'.$qmUrl.'|conn:'.($qmConn === false ? 'fail' : 'ok'));
			$qmSql = "delete from TBLMCORESESSIONS where USERIDENTITY = '".$username."'";
			$qmCompiled = oci_parse($qmConn, $qmSql);
			$qmResult = oci_execute($qmCompiled);
			log_message('info', '@requestCOAExt|'.$username.'|delete@TBLMCORESESSIONS1:'.json_encode($qmResult));
			if ($checkOtherTable) {
				//delete in QM.TBLMCORESESSIONS (1)
				$qmUrl2 = $sessionHost2.':'.$sessionPort2.'/'.$sessionSchema2;
				$qmConn2 = oci_connect($qmusername, $qmpassword, $qmUrl2);
				log_message('info', '@requestCOAExt|'.$username.'|url:'.$qmUrl2.'|conn:'.($qmConn2 === false ? 'fail' : 'ok'));
				$qmSql2 = "delete from TBLMCORESESSIONS where USERIDENTITY = '".$username."'";
				$qmCompiled2 = oci_parse($qmConn2, $qmSql2);
				$qmResult = oci_execute($qmCompiled2);
				log_message('info', '@requestCOAExt|'.$username.'|delete@TBLMCORESESSIONS2:'.json_encode($qmResult));
			}
			//*/
			return array('result' => true);
		} catch (Exception $e) {
			log_message('info', json_encode($e));
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			return array('result' => false, 'error' => $err);
		}
		
		
		/*
		$xmltodelete = '<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:elit="http://eliteRadiusDynAuthWS.ws.service.radius.aaa.elitecore.com"><soapenv:Header/><soapenv:Body><elit:requestCOA soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><in0 xsi:type="soapenc:string" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/">'.$username.'</in0><in1 xsi:type="x-:Map" xmlns:x-="http://xml.apache.org/xml-soap"><!--Zero or more repetitions:--><item xsi:type="x-:mapItem"><key xsi:type="xsd:string">0:4</key><value xsi:type="xsd:string">'.$nasip.'</value></item><item xsi:type="x-:mapItem"><key xsi:type="xsd:string">0:44</key><value xsi:type="xsd:string">'.$sessionid.'</value></item></in1></elit:requestCOA></soapenv:Body></soapenv:Envelope>';

		$client = new SoapClient($wsdl);
		try{
			log_message('info', 'fx: '.json_encode($client->__getFunctions()));
			//$result = $client->requestCOAExt($xmltodelete);
			$result = $client->requestDisconnect($xmltodelete);
			log_message('info', 'res: '.json_encode($result));

			if ($checkOtherTable) {
				$client2 = new SoapClient($wsdl2);
				log_message('info', 'fx: '.json_encode($client2->__getFunctions()));
				//$result = $client2->requestCOAExt($xmltodelete);
				$result = $client2->requestDisconnect($xmltodelete);
				log_message('info', 'res2: '.json_encode($result));
			}
			return array('result' => true);
		}
		catch (Exception $e) {
			log_message('info', json_encode($e));
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			return array('result' => false, 'error' => $err);
		}
		*/
	}
	// added july 2016
	public function requestDisconnect($username, $nasIp, $sessionId, $checkOtherTable, 
		$deleteSessionApiHost, $deleteSessionApiPort, $deleteSessionApiStub,
		$deleteSessionApiHost2, $deleteSessionApiPort2, $deleteSessionApiStub2, 
		$tblmConcHost, $tblmConcPort, $tblmConcSchema, $tblmConcUsername, $tblmConcPassword, 
		$tblmConcHost2, $tblmConcPort2, $tblmConcSchema2, $tblmConcUsername2, $tblmConcPassword2,
		$tblmCoreHost, $tblmCorePort, $tblmCoreSchema, $tblmCoreUsername, $tblmCorePassword, 
		$tblmCoreHost2, $tblmCorePort2, $tblmCoreSchema2, $tblmCoreUsername2, $tblmCorePassword2) {

		$deleteSessionApiWsdl = 'http://'.$deleteSessionApiHost.':'.$deleteSessionApiPort.'/'.$deleteSessionApiStub;
		$deleteSessionApiWsdl2 = 'http://'.$deleteSessionApiHost2.':'.$deleteSessionApiPort2.'/'.$deleteSessionApiStub2;
		log_message('debug', 'delete session wsdl1: '.$deleteSessionApiWsdl);
		log_message('debug', 'delete session wsdl2: '.$deleteSessionApiWsdl2);

		$map = array('0:4' => $nasIp, '0:44' => $sessionId);
		$deleteAtTblMConcurrentUsersSql = "delete from TBLMCONCURRENTUSERS where USER_NAME = :username";
		$deleteAtTblMCoreSessionsSql = "delete from TBLMCORESESSIONS where USERIDENTITY = :username";
		log_message('debug', '@requestDisconnect|username: '.$username.', map: '.json_encode($map));
		//connecting to api (1)
		// Remove Dependencies 5/20/19
		// try {
		// 	$deleteSessionClient = new SoapClient($deleteSessionApiWsdl);
		// } catch (Exception $e) {
		// 	$error = json_encode($e);
		// 	log_message('debug', 'error at getRequestDisconnect (1): '.$error);
		// 	$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		// 	$end = strpos($error, '","faultcode"');
		// 	$err = substr($error, $start, $end - $start);
		// 	return array('result' => false, 'error' => $err);
		// }
		// //deleting via api (1)
		// try {
		// 	$result = $deleteSessionClient->requestDisconnect($username, $map);
		// 	log_message('debug', 'requestDisconnect result (1): '.json_encode($result));
		// } catch (Exception $e) {
		// 	$error = json_encode($e);
		// 	log_message('debug', 'error at getRequestDisconnect (2): '.$error);
		// 	$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		// 	$end = strpos($error, '","faultcode"');
		// 	$err = substr($error, $start, $end - $start);
		// 	return array('result' => false, 'error' => $err);
		// }
		//deleting at tblmconcurrentusers (1)
		$tblmConcUrl = $tblmConcHost.':'.$tblmConcPort.'/'.$tblmConcSchema;
		$conn1 = oci_connect($tblmConcUsername, $tblmConcPassword, $tblmConcUrl);
		log_message('debug', 'TBLMCONCURRENTUSERS (1) url: '.$tblmConcUrl.', '.$tblmConcUsername.'/'.$tblmConcPassword.' | conn: '.($conn1 === false ? 'fail' : 'ok'));
		if ($conn1 !== false) {
			$compiled1 = oci_parse($conn1, $deleteAtTblMConcurrentUsersSql);
			oci_bind_by_name($compiled1, ':username', $username);
			$result1 = oci_execute($compiled1);
			log_message('debug', 'TBLMCONCURRENTUSERS (1) result: '.json_encode($result1));
		}
		//deleting at tblmcoresessions (1)
		$tblmCoreUrl = $tblmCoreHost.':'.$tblmCorePort.'/'.$tblmCoreSchema;
		$conn1 = oci_connect($tblmCoreUsername, $tblmCorePassword, $tblmCoreUrl);
		log_message('debug', 'TBLMCORESESSIONS (1) url: '.$tblmCoreUrl.', '.$tblmCoreUsername.'/'.$tblmCorePassword.' | conn: '.($conn1 === false ? 'fail' : 'ok'));
		if ($conn1 !== false) {
			$compiled1 = oci_parse($conn1, $deleteAtTblMCoreSessionsSql);
			oci_bind_by_name($compiled1, ':username', $username);
			$result1 = oci_execute($compiled1);
			log_message('debug', 'TBLMCORESESSIONS (1) result: '.json_encode($result1));
		}
		//other table
		log_message('debug', 'checkOtherTable: '.json_encode($checkOtherTable));
		if ($checkOtherTable) {
			// Remove Dependencies 5/20/19
			//connecting to api (2)
			// try {
			// 	$deleteSessionClient2 = new SoapClient($deleteSessionApiWsdl2);
			// } catch (Exception $e) {
			// 	$error = json_encode($e);
			// 	log_message('debug', 'error at getRequestDisconnect (3): '.$error);
			// 	$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			// 	$end = strpos($error, '","faultcode"');
			// 	$err = substr($error, $start, $end - $start);
			// 	return array('result' => false, 'error' => $err);
			// }
			// //deleting via api (2)
			// try {
			// 	$result2 = $deleteSessionClient2->requestDisconnect($username, $map);
			// 	log_message('debug', 'requestDisconnect result (2): '.json_encode($result2));
			// } catch (Exception $e) {
			// 	$error = json_encode($e);
			// 	log_message('debug', 'error at getRequestDisconnect (4): '.$error);
			// 	$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			// 	$end = strpos($error, '","faultcode"');
			// 	$err = substr($error, $start, $end - $start);
			// 	return array('result' => false, 'error' => $err);
			// }
			//deleting at tblmconcurrentusers (2)
			$tblmConcUrl2 = $tblmConcHost2.':'.$tblmConcPort2.'/'.$tblmConcSchema2;
			$conn2 = oci_connect($tblmConcUsername2, $tblmConcPassword2, $tblmConcUrl2);
			log_message('debug', 'TBLMCONCURRENTUSERS (2) url: '.$tblmConcUrl2.', '.$tblmConcUsername2.'/'.$tblmConcPassword2.' | conn: '.($conn2 === false ? 'fail' : 'ok'));
			if ($conn2 !== false) {
				$compiled2 = oci_parse($conn2, $deleteAtTblMConcurrentUsersSql);
				oci_bind_by_name($compiled2, ':username', $username);
				$result2 = oci_execute($compiled2);
				log_message('debug', 'TBLMCONCURRENTUSERS (2) result: '.json_encode($result2));
			}
			//deleting at tblmcoresessions (2)
			$tblmCoreUrl2 = $tblmCoreHost2.':'.$tblmCorePort2.'/'.$tblmCoreSchema2;
			$conn2 = oci_connect($tblmCoreUsername2, $tblmCorePassword2, $tblmCoreUrl2);
			log_message('debug', 'TBLMCORESESSIONS (2) url: '.$tblmCoreUrl2.', '.$tblmCoreUsername2.'/'.$tblmCorePassword2.' | conn: '.($conn2 === false ? 'fail' : 'ok'));
			if ($conn2 !== false) {
				$compiled2 = oci_parse($conn2, $deleteAtTblMCoreSessionsSql);
				oci_bind_by_name($compiled2, ':username', $username);
				$result2 = oci_execute($compiled2);
				log_message('debug', 'TBLMCORESESSIONS (2) result: '.json_encode($result2));
			}
		}
		return array('result' => true);
	}
	/****************************************
	 * added august 2016
	 ****************************************/
	/****************************************
	 * only triggers requestDisconnect
	 ****************************************/
	public function requestDisconnectOnly($username, $nasIp, $sessionId, $checkOtherTable,
		$deleteSessionApiHost, $deleteSessionApiPort, $deleteSessionApiStub,
		$deleteSessionApiHost2, $deleteSessionApiPort2, $deleteSessionApiStub2) {

		$deleteSessionApiWsdl = 'http://'.$deleteSessionApiHost.':'.$deleteSessionApiPort.'/'.$deleteSessionApiStub;
		$deleteSessionApiWsdl2 = 'http://'.$deleteSessionApiHost2.':'.$deleteSessionApiPort2.'/'.$deleteSessionApiStub2;
		log_message('debug', 'delete session wsdl1: '.$deleteSessionApiWsdl);
		log_message('debug', 'delete session wsdl2: '.$deleteSessionApiWsdl2);

		$map = array('0:4' => $nasIp, '0:44' => $sessionId);
		log_message('debug', '@requestDisconnect|username: '.$username.', map: '.json_encode($map));
		//connecting to api (1)
		try {
			$deleteSessionClient = new SoapClient($deleteSessionApiWsdl);
		} catch (Exception $e) {
			$error = json_encode($e);
			log_message('debug', 'error at getRequestDisconnect (1): '.$error);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			return array('result' => false, 'error' => $err);
		}
		//deleting via api (1)
		try {
			$result = $deleteSessionClient->requestDisconnect($username, $map);
			log_message('debug', 'requestDisconnect result (1): '.json_encode($result));
		} catch (Exception $e) {
			$error = json_encode($e);
			log_message('debug', 'error at getRequestDisconnect (2): '.$error);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			return array('result' => false, 'error' => $err);
		}
		log_message('debug', 'checkOtherTable: '.json_encode($checkOtherTable));
		if ($checkOtherTable) {
			//connecting to api (2)
			try {
				$deleteSessionClient2 = new SoapClient($deleteSessionApiWsdl2);
			} catch (Exception $e) {
				$error = json_encode($e);
				log_message('debug', 'error at getRequestDisconnect (3): '.$error);
				$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
				$end = strpos($error, '","faultcode"');
				$err = substr($error, $start, $end - $start);
				return array('result' => false, 'error' => $err);
			}
			//deleting via api (2)
			try {
				$result2 = $deleteSessionClient2->requestDisconnect($username, $map);
				log_message('debug', 'requestDisconnect result (2): '.json_encode($result2));
			} catch (Exception $e) {
				$error = json_encode($e);
				log_message('debug', 'error at getRequestDisconnect (4): '.$error);
				$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
				$end = strpos($error, '","faultcode"');
				$err = substr($error, $start, $end - $start);
				return array('result' => false, 'error' => $err);
			}
		}
		return array('result' => true);
	}
	/****************************************
	 * only deletes from tblmconcurrentusers and tblmcoresessions
	 ****************************************/
	public function requestDisconnectExtrasOnly($username, $checkOtherTable,
		$tblmConcHost, $tblmConcPort, $tblmConcSchema, $tblmConcUsername, $tblmConcPassword, 
		$tblmConcHost2, $tblmConcPort2, $tblmConcSchema2, $tblmConcUsername2, $tblmConcPassword2,
		$tblmCoreHost, $tblmCorePort, $tblmCoreSchema, $tblmCoreUsername, $tblmCorePassword, 
		$tblmCoreHost2, $tblmCorePort2, $tblmCoreSchema2, $tblmCoreUsername2, $tblmCorePassword2) {

		$deleteAtTblMConcurrentUsersSql = "delete from TBLMCONCURRENTUSERS where USER_NAME = :username";
		$deleteAtTblMCoreSessionsSql = "delete from TBLMCORESESSIONS where USERIDENTITY = :username";
		//deleting at tblmconcurrentusers (1)
		$tblmConcUrl = $tblmConcHost.':'.$tblmConcPort.'/'.$tblmConcSchema;
		$conn1 = oci_connect($tblmConcUsername, $tblmConcPassword, $tblmConcUrl);
		log_message('debug', 'TBLMCONCURRENTUSERS (1) url: '.$tblmConcUrl.', '.$tblmConcUsername.'/'.$tblmConcPassword.' | conn: '.($conn1 === false ? 'fail' : 'ok'));
		if ($conn1 !== false) {
			$compiled1 = oci_parse($conn1, $deleteAtTblMConcurrentUsersSql);
			oci_bind_by_name($compiled1, ':username', $username);
			$result1 = oci_execute($compiled1);
			log_message('debug', 'TBLMCONCURRENTUSERS (1) result: '.json_encode($result1));
		}
		//deleting at tblmcoresessions (1)
		$tblmCoreUrl = $tblmCoreHost.':'.$tblmCorePort.'/'.$tblmCoreSchema;
		$conn1 = oci_connect($tblmCoreUsername, $tblmCorePassword, $tblmCoreUrl);
		log_message('debug', 'TBLMCORESESSIONS (1) url: '.$tblmCoreUrl.', '.$tblmCoreUsername.'/'.$tblmCorePassword.' | conn: '.($conn1 === false ? 'fail' : 'ok'));
		if ($conn1 !== false) {
			$compiled1 = oci_parse($conn1, $deleteAtTblMCoreSessionsSql);
			oci_bind_by_name($compiled1, ':username', $username);
			$result1 = oci_execute($compiled1);
			log_message('debug', 'TBLMCORESESSIONS (1) result: '.json_encode($result1));
		}
		log_message('debug', 'checkOtherTable: '.json_encode($checkOtherTable));
		if ($checkOtherTable) {
			//deleting at tblmconcurrentusers (2)
			$tblmConcUrl2 = $tblmConcHost2.':'.$tblmConcPort2.'/'.$tblmConcSchema2;
			$conn2 = oci_connect($tblmConcUsername2, $tblmConcPassword2, $tblmConcUrl2);
			log_message('debug', 'TBLMCONCURRENTUSERS (2) url: '.$tblmConcUrl2.', '.$tblmConcUsername2.'/'.$tblmConcPassword2.' | conn: '.($conn2 === false ? 'fail' : 'ok'));
			if ($conn2 !== false) {
				$compiled2 = oci_parse($conn2, $deleteAtTblMConcurrentUsersSql);
				oci_bind_by_name($compiled2, ':username', $username);
				$result2 = oci_execute($compiled2);
				log_message('debug', 'TBLMCONCURRENTUSERS (2) result: '.json_encode($result2));
			}
			//deleting at tblmcoresessions (2)
			$tblmCoreUrl2 = $tblmCoreHost2.':'.$tblmCorePort2.'/'.$tblmCoreSchema2;
			$conn2 = oci_connect($tblmCoreUsername2, $tblmCorePassword2, $tblmCoreUrl2);
			log_message('debug', 'TBLMCORESESSIONS (2) url: '.$tblmCoreUrl2.', '.$tblmCoreUsername2.'/'.$tblmCorePassword2.' | conn: '.($conn2 === false ? 'fail' : 'ok'));
			if ($conn2 !== false) {
				$compiled2 = oci_parse($conn2, $deleteAtTblMCoreSessionsSql);
				oci_bind_by_name($compiled2, ':username', $username);
				$result2 = oci_execute($compiled2);
				log_message('debug', 'TBLMCORESESSIONS (2) result: '.json_encode($result2));
			}
		}
		return array('result' => true);
	}
	/******************************************************************************************
	 * NOTE: none of the controllers use this function anymore
	 ******************************************************************************************/
	public function getThrottledOrBoostedData($username, $checkOtherTable = false, $sessionHost2 = null, $sessionPort2 = null, $sessionSchema2 = null, 
		$sessionUsername2 = null, $sessionPassword2 = null, $location = '1') {
		if ($location == '1') {
			$this->utility->db_select();
			$query = $this->utility->select('*')
				->from('TBLTHROTTLEBOOSTRESET')
				->where('USERNAME', $username)
				->order_by('EDRID', 'DESC')
				->get();
			if ($query->num_rows() == 0) {
				$query2 = $this->utility->select('*')
					->from('TBLTHROTTLEDOWN')
					->where('USERNAME', $username)
					->order_by('EDRID', 'DESC')
					->get();
				if ($query2->num_rows() == 0) {
					return false;
				} else {
					$data2 = $query2->row_array();
					return array('eventDate' => $data2['EVENTDATE'], 'event' => 'down');
				}
			} else {
				$data = $query->row_array();
				return array('eventDate' => $data['EVENTDATE'], 'event' => 'up');	
			}
		} else if ($location == '2') {
			$rows = 0;
			$rowHolder = array();
			$url = $sessionHost2.':'.$sessionPort2.'/'.$sessionSchema2;
			$conn = oci_connect($sessionUsername2, $sessionPassword2, $url);
			$sql = "select * from TBLTHROTTLEBOOSTRESET where USERNAME = '".$username."' order by EDRID desc";
			log_message('info', 'boost2:'.$sql);
			$compiled = oci_parse($conn, $sql);
			$result = oci_execute($compiled);
			log_message('info', 'boostresult2:'.json_encode($result));
			while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) !== false) {
				$rows++;
				$rowHolder[] = $row;
			}
			if ($rows == 0) {
				$sql = "select * from TBLTHROTTLEDOWN where USERNAME = '".$username."' order by EDRID desc";
				log_message('info', 'down2:'.$sql);
				$compiled = oci_parse($conn, $sql);
				$result = oci_execute($compiled);
				log_message('info', 'downresult2:'.json_encode($result));
				while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) !== false) {
					$rows++;
					$rowHolder[] = $row;
				}
				if ($rows == 0) {
					return false;
				} else {
					return array('eventDate' => $rowHolder[0]['EVENTDATE'], 'event' => 'down');
				}
			} else {
				return array('eventDate' => $rowHolder[0]['EVENTDATE'], 'event' => 'up');
			}
		}
	}
	public function getRandomUsersWithSession($nasIdentifier, $offset, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select * from (".
			"select inner_query.*, rownum rnum from (".
				"select * from TBLCONCURRENTUSERS where NAS_IDENTIFIER = :nasidentifier and ".
					"(regexp_like(FRAMED_IP_ADDRESS, '^10.\d{3}', 'i') or regexp_like(FRAMED_IP_ADDRESS, '^10.\d{2}', 'i')) order by USER_NAME asc) ".
			"inner_query where rownum < :max) where rnum > :min";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':nasidentifier', strval($nasIdentifier));
		oci_bind_by_name($compiled, ':min', strval($offset));
		oci_bind_by_name($compiled, ':max', strval(intval($offset) + 2));
		$result = oci_execute($compiled);
		// $accounts = array();
		// while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) !== false) {
		// 	$accounts[] = $row;
		// }
		// return $accounts;
		$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		return $row;
	}
	public function getRandomUsersWithSession2($nasIdentifier, $plans, $max, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$plansStr = '';
		for ($i = 0; $i < count($plans); $i++) {
			$plansStr .= "'".$plans[$i]."',";
		}
		$plansStr = substr($plansStr, 0, strlen($plansStr) - 1);
		$sql = "select * from (".
			"select inner_query.*, rownum rnum from (".
				"select * from TBLCONCURRENTUSERS ".
				"left join TBLCUSTOMER on TBLCUSTOMER.USER_IDENTITY = TBLCONCURRENTUSERS.USER_NAME ".
				"where TBLCONCURRENTUSERS.NAS_IDENTIFIER = :nasidentifier ".
					"and (regexp_like(TBLCONCURRENTUSERS.FRAMED_IP_ADDRESS, '^10.\d{3}', 'i') or regexp_like(TBLCONCURRENTUSERS.FRAMED_IP_ADDRESS, '^10.\d{2}', 'i')) ".
					"and TBLCUSTOMER.RBACCOUNTPLAN in (".$plansStr.") ".
				"order by TBLCONCURRENTUSERS.USER_NAME asc) ".
			"inner_query where rownum < :max)";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':nasidentifier', strval($nasIdentifier));
		oci_bind_by_name($compiled, ':max', strval($max));
		$result = oci_execute($compiled);
		$rows = array();
		while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) !== false) {
			$rows[] = $row;
		}
		return $rows;
	}
	/****************************************
	 * added september 2016
	 ****************************************/
	public function getNasLocation($currentNasName, $registeredBng) {
		$data = array();
		$this->extras->db_select();
		$query = $this->extras->select('nas_name, nas_ip, nas_description, rm_location, rm_description')
			->from('nas_location')
			->where('nas_name', $currentNasName)
			->get();
		$data['correct_bng'] = $query->row_array();
		$query = $this->extras->select('nas_description, rm_location')
			->from('nas_location')
			->where('rm_location', $registeredBng)
			->limit(1)
			->get();
		$data['registered_bng'] = $query->row_array();
		return $data;
	}
	public function npmGetSessions($username, $realm, $npmHost, $npmPort, $npmApi, $npmLogin, $npmPassword) {
		$options = array(
			'location' => 'http://'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/session_mgmt?WSDL', //'http://'.$this->NPM_URL, 
			'uri' => 'http://'.$npmHost.':'.$npmPort.'NPM_API-'.$npmApi.'/', //'http://'.$this->NPM_URI,
			'login' => $npmLogin, //$this->NPM_LOGIN,
			'password' => $npmPassword, //$this->NPM_PASSWORD,
			'trace' => 1);
		$noneIndicator = 'ns0:SubscriberSession[0]';
		try {
			$client = new SoapClient('http://'.urlencode($npmLogin).':'.urlencode($npmPassword).'@'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/session_mgmt?WSDL', $options);
			$result = $client->getSubscriberSessions($username.'@'.$realm);
			log_message('info', '+++++'.json_encode($result));
			//$result = '<ns0:ArrayOfSubscriberSession id="ID1" xsi:type="enc:Array" enc:arrayType="ns0:SubscriberSession[0]"/>';
			//$result = '<ns0:ArrayOfSubscriberSession id="ID1" xsi:type="enc:Array" enc:arrayType="ns0:SubscriberSession[1]"/><ns0:SubscriberSession><sessionId xsi:type="xsd:string">'.$username.'@'.$realm.'</sessionId><acctSessionId xsi:type="xsd:string">acctSessionId</acctSessionId><callingStationId xsi:type="xsd:string">callingStationId</callingStationId><circuitType xsi:type="xsd:string">circuitType</circuitType><context xsi:type="xsd:string">context</context><sessionIp xsi:type="xsd:string">sessionIp</sessionIp><macAddress xsi:type="xsd:string">macAddress</macAddress><medium xsi:type="xsd:string">medium</medium><nasId xsi:type="xsd:string">nasId</nasId><NASPortId xsi:type="xsd:string">NASPortId</NASPortId><NASPortType xsi:type="xsd:string">NASPortType</NASPortType><nasType xsi:type="xsd:string">nasType</nasType><startTime xsi:type="xsd:dateTime">startTime</startTime><subscriberAccount xsi:type="xsd:string">'.$username.'@'.$realm.'</subscriberAccount></ns0:SubscriberSession>';
			//$result = '<ns0:ArrayOfSubscriberSession id="ID1" xsi:type="enc:Array" enc:arrayType="ns0:SubscriberSession[3]"/><ns0:SubscriberSession><sessionId xsi:type="xsd:string">'.$username.'@'.$realm.'</sessionId><acctSessionId xsi:type="xsd:string">acctSessionId</acctSessionId><callingStationId xsi:type="xsd:string">callingStationId</callingStationId><circuitType xsi:type="xsd:string">circuitType</circuitType><context xsi:type="xsd:string">context</context><sessionIp xsi:type="xsd:string">sessionIp</sessionIp><macAddress xsi:type="xsd:string">macAddress</macAddress><medium xsi:type="xsd:string">medium</medium><nasId xsi:type="xsd:string">nasId</nasId><NASPortId xsi:type="xsd:string">NASPortId</NASPortId><NASPortType xsi:type="xsd:string">NASPortType</NASPortType><nasType xsi:type="xsd:string">nasType</nasType><startTime xsi:type="xsd:dateTime">startTime</startTime><subscriberAccount xsi:type="xsd:string">'.$username.'@'.$realm.'</subscriberAccount></ns0:SubscriberSession><ns0:SubscriberSession><sessionId xsi:type="xsd:string">sessionId2</sessionId><acctSessionId xsi:type="xsd:string">acctSessionId2</acctSessionId><callingStationId xsi:type="xsd:string">callingStationId2</callingStationId><circuitType xsi:type="xsd:string">circuitType2</circuitType><context xsi:type="xsd:string">context2</context><sessionIp xsi:type="xsd:string">sessionIp2</sessionIp><macAddress xsi:type="xsd:string">macAddress2</macAddress><medium xsi:type="xsd:string">medium2</medium><nasId xsi:type="xsd:string">nasId2</nasId><NASPortId xsi:type="xsd:string">NASPortId2</NASPortId><NASPortType xsi:type="xsd:string">NASPortType2</NASPortType><nasType xsi:type="xsd:string">nasType2</nasType><startTime xsi:type="xsd:dateTime">startTime2</startTime><subscriberAccount xsi:type="xsd:string">subscriberAccount2</subscriberAccount></ns0:SubscriberSession><ns0:SubscriberSession><sessionId xsi:type="xsd:string">sessionId3</sessionId><acctSessionId xsi:type="xsd:string">acctSessionId3</acctSessionId><callingStationId xsi:type="xsd:string">callingStationId3</callingStationId><circuitType xsi:type="xsd:string">circuitType3</circuitType><context xsi:type="xsd:string">context3</context><sessionIp xsi:type="xsd:string">sessionIp3</sessionIp><macAddress xsi:type="xsd:string">macAddress3</macAddress><medium xsi:type="xsd:string">medium3</medium><nasId xsi:type="xsd:string">nasId3</nasId><NASPortId xsi:type="xsd:string">NASPortId3</NASPortId><NASPortType xsi:type="xsd:string">NASPortType3</NASPortType><nasType xsi:type="xsd:string">nasType3</nasType><startTime xsi:type="xsd:dateTime">startTime3</startTime><subscriberAccount xsi:type="xsd:string">subscriberAccount3</subscriberAccount></ns0:SubscriberSession>';
			//if (strpos($result, $noneIndicator) === false) { //sessions found
			if (count($result) != 0) {
				$result = json_encode($result);
				$countStr = substr($result, strpos($result, '[') + 1, strpos($result, ']') - (strpos($result, '[') + 1));
				$count = intval($countStr);
				$sessions = array();
				if ($count == 1) {
					$session = array();
					if (strpos($result, '</sessionId>') !== false) {
						$ind = '<sessionId xsi:type="xsd:string">';
						$start = strpos($result, $ind);
						$end = strpos($result, '</sessionId>');
						$str = substr($result, $start + strlen($ind), $end - ($start + strlen($ind)));
						$session['sessionId'] = $str;
					} else {
						$session['sessionId'] = null;
					}
					if (strpos($result, '</acctSessionId>') !== false) {
						$ind = '<acctSessionId xsi:type="xsd:string">';
						$start = strpos($result, $ind);
						$end = strpos($result, '</acctSessionId>');
						$str = substr($result, $start + strlen($ind), $end - ($start + strlen($ind)));
						$session['acctSessionId'] = $str;
					} else {
						$session['acctSessionId'] = null;
					}
					if (strpos($result, '</callingStationId>') !== false) {
						$ind = '<callingStationId xsi:type="xsd:string">';
						$start = strpos($result, $ind);
						$end = strpos($result, '</callingStationId>');
						$str = substr($result, $start + strlen($ind), $end - ($start + strlen($ind)));
						$session['callingStationId'] = $str;
					} else {
						$session['callingStationId'] = null;
					}
					if (strpos($result, '</circuitType>') !== false) {
						$ind = '<circuitType xsi:type="xsd:string">';
						$start = strpos($result, $ind);
						$end = strpos($result, '</circuitType>');
						$str = substr($result, $start + strlen($ind), $end - ($start + strlen($ind)));
						$session['circuitType'] = $str;
					} else {
						$session['circuitType'] = null;
					}
					if (strpos($result, '</context>') !== false) {
						$ind = '<context xsi:type="xsd:string">';
						$start = strpos($result, $ind);
						$end = strpos($result, '</context>');
						$str = substr($result, $start + strlen($ind), $end - ($start + strlen($ind)));
						$session['context'] = $str;
					} else {
						$session['context'] = null;
					}
					if (strpos($result, '</sessionIp>') !== false) {
						$ind = '<sessionIp xsi:type="xsd:string">';
						$start = strpos($result, $ind);
						$end = strpos($result, '</sessionIp>');
						$str = substr($result, $start + strlen($ind), $end - ($start + strlen($ind)));
						$session['sessionIp'] = $str;
					} else {
						$session['sessionIp'] = null;
					}
					if (strpos($result, '</macAddress>') !== false) {
						$ind = '<macAddress xsi:type="xsd:string">';
						$start = strpos($result, $ind);
						$end = strpos($result, '</macAddress>');
						$str = substr($result, $start + strlen($ind), $end - ($start + strlen($ind)));
						$session['macAddress'] = $str;
					} else {
						$session['macAddress'] = null;
					}
					if (strpos($result, '</medium>') !== false) {
						$ind = '<medium xsi:type="xsd:string">';
						$start = strpos($result, $ind);
						$end = strpos($result, '</medium>');
						$str = substr($result, $start + strlen($ind), $end - ($start + strlen($ind)));
						$session['medium'] = $str;
					} else {
						$session['medium'] = null;
					}
					if (strpos($result, '</nasId>') !== false) {
						$ind = '<nasId xsi:type="xsd:string">';
						$start = strpos($result, $ind);
						$end = strpos($result, '</nasId>');
						$str = substr($result, $start + strlen($ind), $end - ($start + strlen($ind)));
						$session['nasId'] = $str;
					} else {
						$session['nasId'] = null;
					}
					if (strpos($result, '</NASPortId>') !== false) {
						$ind = '<NASPortId xsi:type="xsd:string">';
						$start = strpos($result, $ind);
						$end = strpos($result, '</NASPortId>');
						$str = substr($result, $start + strlen($ind), $end - ($start + strlen($ind)));
						$session['NASPortId'] = $str;
					} else {
						$session['NASPortId'] = null;
					}
					if (strpos($result, '</NASPortType>') !== false) {
						$ind = '<NASPortType xsi:type="xsd:string">';
						$start = strpos($result, $ind);
						$end = strpos($result, '</NASPortType>');
						$str = substr($result, $start + strlen($ind), $end - ($start + strlen($ind)));
						$session['NASPortType'] = $str;
					} else {
						$session['NASPortType'] = null;
					}
					if (strpos($result, '</nasType>') !== false) {
						$ind = '<nasType xsi:type="xsd:string">';
						$start = strpos($result, $ind);
						$end = strpos($result, '</nasType>');
						$str = substr($result, $start + strlen($ind), $end - ($start + strlen($ind)));
						$session['nasType'] = $str;
					} else {
						$session['nasType'] = null;
					}
					if (strpos($result, '</startTime>') !== false) {
						$ind = '<startTime xsi:type="xsd:dateTime">';
						$start = strpos($result, $ind);
						$end = strpos($result, '</startTime>');
						$str = substr($result, $start + strlen($ind), $end - ($start + strlen($ind)));
						$session['startTime'] = $str;
					} else {
						$session['startTime'] = null;
					}
					if (strpos($result, '</subscriberAccount>') !== false) {
						$ind = '<subscriberAccount xsi:type="xsd:string">';
						$start = strpos($result, $ind);
						$end = strpos($result, '</subscriberAccount>');
						$str = substr($result, $start + strlen($ind), $end - ($start + strlen($ind)));
						$session['subscriberAccount'] = $str;
					} else {
						$session['subscriberAccount'] = null;
					}
					log_message('info', 'session: '.json_encode($session));
					$sessions[] = $session;
				} else if ($count > 1) {
					$strs = explode('</ns0:SubscriberSession>', $result);
					for ($i = 0; $i < $count; $i++) {
						$sessStr = $strs[$i];
						$session = array();
						if (strpos($sessStr, '</sessionId>') !== false) {
							$ind = '<sessionId xsi:type="xsd:string">';
							$start = strpos($sessStr, $ind);
							$end = strpos($sessStr, '</sessionId>');
							$str = substr($sessStr, $start + strlen($ind), $end - ($start + strlen($ind)));
							$session['sessionId'] = $str;
						} else {
							$session['sessionId'] = null;
						}
						if (strpos($sessStr, '</acctSessionId>') !== false) {
							$ind = '<acctSessionId xsi:type="xsd:string">';
							$start = strpos($sessStr, $ind);
							$end = strpos($sessStr, '</acctSessionId>');
							$str = substr($sessStr, $start + strlen($ind), $end - ($start + strlen($ind)));
							$session['acctSessionId'] = $str;
						} else {
							$session['acctSessionId'] = null;
						}
						if (strpos($sessStr, '</callingStationId>') !== false) {
							$ind = '<callingStationId xsi:type="xsd:string">';
							$start = strpos($sessStr, $ind);
							$end = strpos($sessStr, '</callingStationId>');
							$str = substr($sessStr, $start + strlen($ind), $end - ($start + strlen($ind)));
							$session['callingStationId'] = $str;
						} else {
							$session['callingStationId'] = null;
						}
						if (strpos($sessStr, '</circuitType>') !== false) {
							$ind = '<circuitType xsi:type="xsd:string">';
							$start = strpos($sessStr, $ind);
							$end = strpos($sessStr, '</circuitType>');
							$str = substr($sessStr, $start + strlen($ind), $end - ($start + strlen($ind)));
							$session['circuitType'] = $str;
						} else {
							$session['circuitType'] = null;
						}
						if (strpos($sessStr, '</context>') !== false) {
							$ind = '<context xsi:type="xsd:string">';
							$start = strpos($sessStr, $ind);
							$end = strpos($sessStr, '</context>');
							$str = substr($sessStr, $start + strlen($ind), $end - ($start + strlen($ind)));
							$session['context'] = $str;
						} else {
							$session['context'] = null;
						}
						if (strpos($sessStr, '</sessionIp>') !== false) {
							$ind = '<sessionIp xsi:type="xsd:string">';
							$start = strpos($sessStr, $ind);
							$end = strpos($sessStr, '</sessionIp>');
							$str = substr($sessStr, $start + strlen($ind), $end - ($start + strlen($ind)));
							$session['sessionIp'] = $str;
						} else {
							$session['sessionIp'] = null;
						}
						if (strpos($sessStr, '</macAddress>') !== false) {
							$ind = '<macAddress xsi:type="xsd:string">';
							$start = strpos($sessStr, $ind);
							$end = strpos($sessStr, '</macAddress>');
							$str = substr($sessStr, $start + strlen($ind), $end - ($start + strlen($ind)));
							$session['macAddress'] = $str;
						} else {
							$session['macAddress'] = null;
						}
						if (strpos($sessStr, '</medium>') !== false) {
							$ind = '<medium xsi:type="xsd:string">';
							$start = strpos($sessStr, $ind);
							$end = strpos($sessStr, '</medium>');
							$str = substr($sessStr, $start + strlen($ind), $end - ($start + strlen($ind)));
							$session['medium'] = $str;
						} else {
							$session['medium'] = null;
						}
						if (strpos($sessStr, '</nasId>') !== false) {
							$ind = '<nasId xsi:type="xsd:string">';
							$start = strpos($sessStr, $ind);
							$end = strpos($sessStr, '</nasId>');
							$str = substr($sessStr, $start + strlen($ind), $end - ($start + strlen($ind)));
							$session['nasId'] = $str;
						} else {
							$session['nasId'] = null;
						}
						if (strpos($sessStr, '</NASPortId>') !== false) {
							$ind = '<NASPortId xsi:type="xsd:string">';
							$start = strpos($sessStr, $ind);
							$end = strpos($sessStr, '</NASPortId>');
							$str = substr($sessStr, $start + strlen($ind), $end - ($start + strlen($ind)));
							$session['NASPortId'] = $str;
						} else {
							$session['NASPortId'] = null;
						}
						if (strpos($sessStr, '</NASPortType>') !== false) {
							$ind = '<NASPortType xsi:type="xsd:string">';
							$start = strpos($sessStr, $ind);
							$end = strpos($sessStr, '</NASPortType>');
							$str = substr($sessStr, $start + strlen($ind), $end - ($start + strlen($ind)));
							$session['NASPortType'] = $str;
						} else {
							$session['NASPortType'] = null;
						}
						if (strpos($sessStr, '</nasType>') !== false) {
							$ind = '<nasType xsi:type="xsd:string">';
							$start = strpos($sessStr, $ind);
							$end = strpos($sessStr, '</nasType>');
							$str = substr($sessStr, $start + strlen($ind), $end - ($start + strlen($ind)));
							$session['nasType'] = $str;
						} else {
							$session['nasType'] = null;
						}
						if (strpos($sessStr, '</startTime>') !== false) {
							$ind = '<startTime xsi:type="xsd:dateTime">';
							$start = strpos($sessStr, $ind);
							$end = strpos($sessStr, '</startTime>');
							$str = substr($sessStr, $start + strlen($ind), $end - ($start + strlen($ind)));
							$session['startTime'] = $str;
						} else {
							$session['startTime'] = null;
						}
						if (strpos($sessStr, '</subscriberAccount>') !== false) {
							$ind = '<subscriberAccount xsi:type="xsd:string">';
							$start = strpos($sessStr, $ind);
							$end = strpos($sessStr, '</subscriberAccount>');
							$str = substr($sessStr, $start + strlen($ind), $end - ($start + strlen($ind)));
							$session['subscriberAccount'] = $str;
						} else {
							$session['subscriberAccount'] = null;
						}
						log_message('info', 'session '.$i.': '.json_encode($session));
						$sessions[] = $session;
					}
				}
				return array('status' => true, 'data' => $sessions);
			} else {
				return array('status' => true, 'data' => false);
			}
		} catch (Exception $e) {
			$err = $this->extractErrorMsg($e);
			return array('status' => false, 'data' => false, 'error' => $err);
		}
	}
	public function npmClearSession($username, $npmHost, $npmPort, $npmApi, $npmLogin, $npmPassword) {
		$options = array(
			'location' => 'http://'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/session_mgmt?WSDL', //'http://'.$this->NPM_URL, 
			'uri' => 'http://'.$npmHost.':'.$npmPort.'NPM_API-'.$npmApi.'/', //'http://'.$this->NPM_URI,
			'login' => $npmLogin, //$this->NPM_LOGIN,
			'password' => $npmPassword, //$this->NPM_PASSWORD,
			'trace' => 1);
		try {
			$client = new SoapClient('http://'.urlencode($npmLogin).':'.urlencode($npmPassword).'@'.$npmHost.':'.$npmPort.'/NPM_API-'.$npmApi.'/session_mgmt?WSDL', $options);
			$result = $client->clearSubSessionBySubAcctName($username);
			log_message('info', 'result: '.json_encode($result));
			return array('status' => true);
		} catch (Exception $e) {
			$err = $this->extractErrorMsg($e);
			return array('status' => false, 'error' => $err);
		}
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

	/*
	public function findByServiceType($serviceType) {
		$client = new SoapClient($this->wsdl);
		$params = array('serviceType' => $serviceType);
		$result = $client->findByServiceType($params);
		return $result;
	}
	public function findByFramedIPAddress($ipAddress) {
		$client = new SoapClient($this->wsdl);
		$params = array('ipAddress' => $ipAddress);
		$result = $client->findByFramedIPAddress($params);
		return $result;
	}
	public function findByUserName($userName) {
		$client = new SoapClient($this->$wsdl);
		$params = array('userName' => $userName);
		$result = $client->findByUserName($params);
		return $result;
	}
	public function findByAttribute($attribute, $value) {
		$client = new SoapClient($this->wsdl);
		$params = array('attribute' => $attribute, 'value' => $value);
		$result = $client->findByAttribute($params);
		return $result;
	}
	*/
}
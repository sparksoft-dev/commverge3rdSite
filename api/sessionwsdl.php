<?php
require_once '/var/www/html/executables/api/lib/nusoap.php';
require_once '/var/www/html/executables/api/authenticateresultcodes.php';
require_once '/var/www/html/executables/api/sessionwsdlresultcodes.php';
require_once '/var/www/html/executables/api/authentication.php';
require_once '/var/www/html/executables/api/detour.php';
require_once '/var/www/html/executables/api/aaa.php';
date_default_timezone_set( 'Asia/Manila' );
ini_set( 'memory_limit', '512M' );
/**************************************************
 * log-related
 **************************************************/
define( "AUTHENTICATE", false );
define( "MYSQLSOCKET", '/var/lib/mysql/mysql.sock' );
define( "MYSQLPORT", 3306 );
// $apiAccessLogDir = '/util/api_log/';
$apiAccessLogDir = '/webutil/web_logs/api/ASIC/';
if (!is_dir($apiAccessLogDir)) {
    mkdir($apiAccessLogDir, 0755, true);
}
$logFile         = '_'.date( 'Ymd', time() ); #.'.log';
/**************************************************
 * soap server variables and functions
 **************************************************/
//$host = 'https://222.127.121.130';
$host = 'https://localhost';
$namespace = $host.'/executables/api/sessionwsdl.php';
$server = new soap_server();
$server->configureWSDL( 'sessionwsdl', $namespace );
/**************************************************
 * get session vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsSearchOnlineSessionRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array( 'name' => 'username', 'type' => 'xsd:string' ) ) );
$server->wsdl->addComplexType(
	'wsSearchOnlineSessionResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode'    => array( 'name' => 'responseCode', 'type' => 'xsd:long' ),
		'username'        => array( 'name' => 'username', 'type' => 'xsd:string' ),    //USER_NAME/GROUP_NAME/CUI or $username
		'sessionId'       => array( 'name' => 'sessionId', 'type' => 'xsd:string' ),   //ACCT_SESSION_ID
		'status'          => array( 'name' => 'status', 'type' => 'xsd:string' ),
		'subscriberIPv4'  => array( 'name' => 'subscriberIPv4', 'type' => 'xsd:string' ), //FRAMED_IP_ADDRESS
		'nasName'         => array( 'name' => 'nasName', 'type' => 'xsd:string' ),    //NAS_IDENTIFIER
		'nasIPv4'         => array( 'name' => 'nasIPv4', 'type' => 'xsd:string' ),    //NAS_IP_ADDRESS
		'nasPort'         => array( 'name' => 'nasPort', 'type' => 'xsd:string' ),    //NAS_PORT
		'sessionState'    => array( 'name' => 'sessionState', 'type' => 'xsd:string' ),  //SESSION_STATUS
		'concurrencyId'   => array( 'name' => 'concurrencyId', 'type' => 'xsd:string' ), //CONCUSERID
		'macAddress'      => array( 'name' => 'macAddress', 'type' => 'xsd:string' ),   //MAC_ADDRESS
		'startTime'       => array( 'name' => 'startTime', 'type' => 'xsd:string' ),   //START_TIME
		'servicePlan'     => array( 'name' => 'servicePlan', 'type' => 'xsd:string' ),  //planID (getVolumeUsage)
		'usageLimit'      => array( 'name' => 'usageLimit', 'type' => 'xsd:long' ),   //volumeQuota (getVolumeUsage)
		'currentUsage'    => array( 'name' => 'currentUsage', 'type' => 'xsd:long' ),  //volumeUsage (getVolumeUsage)
		'usagePercentage' => array( 'name' => 'usagePercentage', 'type' => 'xsd:long' ), //to compute
		'usageStatus'     => array( 'name' => 'usageStatus', 'type' => 'xsd:string' ),  //to compute
		'vodExpiry'       => array( 'name' => 'vodExpiry', 'type' => 'xsd:string' ),   //vodDetails->vodExpiry (getVolumeUsage)
		'vodQuota'        => array( 'name' => 'vodQuota', 'type' => 'xsd:long' ),    //vodDetails->vodQuota (getVolumeUsage)
		'vodUsage'        => array( 'name' => 'vodUsage', 'type' => 'xsd:long' ),    //vodDetails->vodUsage (getVolumeUsage)
		'vodPercentage'   => array( 'name' => 'vodPercentage', 'type' => 'xsd:long' ),  //to compute
		'vodStatus'       => array( 'name' => 'vodStatus', 'type' => 'xsd:string' ),   //to compute
		'recordFound'     => array( 'name' => 'recordFound', 'type' => 'xsd:long' ) ) );  //count rows returned from tblconcurrentusers at both dbs
$server->register(
	'wsSearchOnlineSession',
	array( 'param'  => 'tns:wsSearchOnlineSessionRequest' ),
	array( 'return' => 'tns:wsSearchOnlineSessionResponse' ),
	$namespace,
	'urn:sessionwsdl#wsSearchOnlineSession',
	'rpc',
	'encoded',
	'Search current session of the subscriber' );
/**************************************************
 * delete session vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsDeleteOnlineSessionRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username'  => array( 'name' => 'username', 'type' => 'xsd:string' ),
		'sessionId' =>  array( 'name' => 'sessionId', 'type' => 'xsd:string' ),
		'nasIPv4'   => array( 'name' => 'nasIPv4', 'type' => 'xsd:string' ) ) );
$server->wsdl->addComplexType(
	'wsDeleteOnlineSessionResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode'   => array( 'name' => 'responseCode', 'type' => 'xsd:long' ),
		'username'       => array( 'name' => 'username', 'type' => 'xsd:string' ),
		'sessionId'      => array( 'name' => 'sessionId', 'type' => 'xsd:string' ),
		'subscriberIPv4' => array( 'name' => 'subscriberIPv4', 'type' => 'xsd:string' ) ) );
$server->register(
	'wsDeleteOnlineSession',
	array( 'param' => 'tns:wsDeleteOnlineSessionRequest' ),
	array( 'return' => 'tns:wsDeleteOnlineSessionResponse' ),
	$namespace,
	'urn:sessionwsdl#wsDeleteOnlineSession',
	'rpc',
	'encoded',
	'Disconnect current session of the subscriber' );
/**************************************************
 * functions
 **************************************************/
function wsSearchOnlineSession( $param ) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsSearchOnlineSession';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false );
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode( $param ) );
	$username = $param['username'];
	$returnObj = array(
		'responseCode'    => S_SESSION_FOUND,
		'username'        => $username,
		'sessionId'       => '',
		'status'          => '',
		'subscriberIPv4'  => '',
		'nasName'         => '',
		'nasIPv4'         => '',
		'nasPort'         => '',
		'sessionState'    => '',
		'concurrencyId'   => '',
		'macAddress'      => '',
		'startTime'       => '',
		'servicePlan'     => '',
		'usageLimit'      => 0,
		'currentUsage'    => 0,
		'usagePercentage' => 0,
		'usageStatus'     => '',
		'vodExpiry'       => '',
		'vodQuota'        => 0,
		'vodUsage'        => 0,
		'vodPercentage'   => 0,
		'vodStatus'       => '',
		'recordFound'     => 0 );
	$config = generateConnectionUrls();
	if ( $config === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file' );
		$returnObj['responseCode'] = S_CONFIG_FILE_READ_ERROR;
		return $returnObj;
	}
	/**************************************************
	 * database connections
	 **************************************************/
	$useTblm = $config['useTblm'];
	$useSecondary = $config['useSecondary'];
	$primarySessionConn = null;
	if ( $useTblm ) {
		$primarySessionConn = oci_connect( $config['primaryMconcurrentUsername'], $config['primaryMconcurrentPassword'], $config['primaryMconcurrentUrl'] );
	} else {
		$primarySessionConn = oci_connect( $config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl'] );
	}
	if ( $primarySessionConn === false ) {
		if ( $useTblm ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_PRIMARY_SESSION_DB_CONNECT_ERROR.'] No connection to primary session table: '.
				$config['primaryMconcurrentUrl'].', '.$config['primaryMconcurrentUsername'].'/'.$config['primaryMconcurrentPassword'] );
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_PRIMARY_SESSION_DB_CONNECT_ERROR.'] No connection to primary session table: '.
				$config['primarySessionUrl'].', '.$config['primarySessionUsername'].'/'.$config['primarySessionPassword'] );
		}
		$returnObj['responseCode'] = S_PRIMARY_SESSION_DB_CONNECT_ERROR;
		return $returnObj;
	}
	$secondarySessionConn = null;
	if ( $useSecondary ) {
		if ( $useTblm ) {
			$secondarySessionConn = oci_connect( $config['secondaryMconcurrentUsername'], $config['secondaryMconcurrentPassword'], $config['secondaryMconcurrentUrl'] );
		} else {
			$secondarySessionConn = oci_connect( $config['secondarySessionUsername'], $config['secondarySessionPassword'], $config['secondarySessionUrl'] );
		}
		if ( $secondarySessionConn === false ) {
			if ( $useTblm ) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_SECONDARY_SESSON_DB_CONNECT_ERROR.'] No connection to secondary session table: '.
					$config['secondaryMconcurrentUrl'].', '.$config['secondaryMconcurrentUsername'].'/'.$config['secondaryMconcurrentPassword'] );
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_SECONDARY_SESSON_DB_CONNECT_ERROR.'] No connection to secondary session table: '.
					$config['secondarySessionUrl'].', '.$config['secondarySessionUsername'].'/'.$config['secondarySessionPassword'] );
			}
			$returnObj['responseCode'] = S_SECONDARY_SESSON_DB_CONNECT_ERROR;
			return $returnObj;
		}
	}
	$mysqlConn = new mysqli( $config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ( $mysqlConn->connect_error ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database' );
		$returnObj['responseCode'] = S_MYSQL_DB_CONNECT_ERROR;
		return $returnObj;
	}

	/**************************************************
	 * AAA database connection Added: 122717 (include aaa.php)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' respopnse to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}

	/**************************************************
	 * authenticate client
	 **************************************************/
	if ( AUTHENTICATE ) {
		$authenticate = authenticate( $mysqlConn, $client['login'], $client['password'], $functionName, $clientIp );
		if ( !$authenticate['continue'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message'] );
			$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, $authenticate['code'] );
			if ( !$lrq['result'] ) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
			}
			$returnObj['responseCode'] = $authenticate['code'];
			return $returnObj;
		}
		$proceed = checkRequestWindow( $mysqlConn, $functionName, array( 200 ), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds'] );
		if ( $proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
			$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message'] );
			$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, $proceed['code'] );
			if ( !$lrq['result'] ) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
			}
			$returnObj['responseCode'] = $proceed['code'];
			return $returnObj;
		} else {
			// writeToFile($logFile, 'Valid request: ['.$proceed['code'].'] '.$proceed['message']);
		}
	}
	/**************************************************
	 * fetch session(s)
	 **************************************************/
	$sessions = array();
	// $sql = 'select * '.
	$sql = "select USER_NAME, ACCT_SESSION_ID, FRAMED_IP_ADDRESS, NAS_IDENTIFIER, NAS_IP_ADDRESS, NAS_PORT, SESSION_STATUS, CONCUSERID, \"MAC_ADDRESS\", START_TIME ".
		"from ".( $useTblm ? "TBLMCONCURRENTUSERS" : "TBLCONCURRENTUSERS" )." ".
		"where USER_NAME = :username";
	$compiled = oci_parse( $primarySessionConn, $sql );
	oci_bind_by_name( $compiled, ':username', $username );
	$result = oci_execute( $compiled );
	if ( $result === false ) {
		$error = oci_error( $compiled );
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_SESSION_DB_QUERY_ERROR.'] Query error (primary): '.$sql.'|'.$error['message'] );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_SESSION_DB_QUERY_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = S_SESSION_DB_QUERY_ERROR;
		return $returnObj;
	}
	while ( ( $row = oci_fetch_array( $compiled, OCI_ASSOC + OCI_RETURN_NULLS ) ) != false ) {
		$sessions[] = $row;
	}
	$sessionCount = count( $sessions );
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found '.$sessionCount.' session(s) at '.( $useTblm ? $config['primaryMconcurrentUrl'] : $config['primarySessionUrl'] ) );
	if ( $useSecondary ) {
		$compiled = oci_parse( $secondarySessionConn, $sql );
		oci_bind_by_name( $compiled, ':username', $username );
		$result = oci_execute( $compiled );
		if ( $result === false ) {
			$error = oci_error( $compiled );
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_SESSION_DB_QUERY_ERROR.'] Query error (secondary): '.$sql.'|'.$error['message'] );
			$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_SESSION_DB_QUERY_ERROR );
			if ( !$lrq['result'] ) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
			}
			$returnObj['responseCode'] = S_SESSION_DB_QUERY_ERROR;
			return $returnObj;
		}
		while ( ( $row = oci_fetch_array( $compiled, OCI_ASSOC + OCI_RETURN_NULLS ) ) != false ) {
			$sessions[] = $row;
		}
		$sessionCount = count( $sessions );
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found '.$sessionCount.' session(s) at '.( $useTblm ? $config['secondaryMconcurrentUrl'] : $config['secondarySessionUrl'] ) );
	}
	$returnObj['recordFound'] = $sessionCount;
	if ( $sessionCount == 0 ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_NO_SESSIONS_FOUND.'] No session(s) found' );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_NO_SESSIONS_FOUND );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = S_NO_SESSIONS_FOUND;
		return $returnObj;
	}
	/**************************************************
	 * get usage
	 **************************************************/
	$detourClient = null;
	try {
		$detourClient = new SoapClient( 'http://'.$config['primaryDetourUrl'] );
	} catch ( Exception $e ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_DETOUR_CLIENT_ERROR.'] Error at client creation ('.
			$config['primaryDetourUrl'].'): '.json_encode( $e ) );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_DETOUR_CLIENT_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = S_DETOUR_CLIENT_ERROR;
		return $returnObj;
	}
	if ( is_null( $detourClient ) ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_DETOUR_CLIENT_ERROR.'] Detour client is null' );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_DETOUR_CLIENT_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = S_DETOUR_CLIENT_ERROR;
		return $returnObj;
	}
	$usage = Detour::getVolumeUsage( $username, $detourClient ); //no need to put this in a try catch since the function already has one inside
	if ( $usage === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_DETOUR_CLIENT_ERROR.'] Error at getVolumeUsage' );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_DETOUR_CLIENT_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = S_DETOUR_CLIENT_ERROR;
		return $returnObj;
	}
	if ( intval( $usage['responseCode'] ) != 200 ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_DETOUR_CLIENT_ERROR.'] Error at getVolumeUsage: '.$usage['replyMessage'] );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_DETOUR_CLIENT_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = S_DETOUR_CLIENT_ERROR;
		return $returnObj;
	}
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Usage: '.json_encode( $usage ) );
	//EDITED 05162019 $returnObj['servicePlan'] = str_replace( '~', '-', $usage['plan'] );
	$returnObj['servicePlan'] = '';
	/**************************************************
	 * calculate percentages
	 **************************************************/
	$volumeUsage = floatval( $usage['volumeUsage'] );
	$volumeQuota = floatval( $usage['volumeQuota'] );
	$usagePercentage = $volumeQuota == 0 ? 0 : ( $volumeUsage / $volumeQuota );
	if ( $usagePercentage < 0.9 ) {
		$usageStatus = 'Normal';
	} else if ( $usagePercentage >= 0.9 && $usagePercentage < 1 ) {
			$usageStatus = 'Warning';
		} else if ( $usagePercentage >= 1 ) {
			$usageStatus = 'Exceeded';
		}
	$usagePercentage = round( $usagePercentage * 100, 2 );
	// $volumeUsage = convertToBytes($volumeUsage);
	// $volumeQuota = convertToBytes($volumeQuota);
	/*EDITED 05162019 
	$returnObj['currentUsage'] = $volumeUsage;
	$returnObj['usageLimit'] = $volumeQuota;
	$returnObj['usagePercentage'] = $usagePercentage;
	$returnObj['usageStatus'] = $usageStatus;
	EDITED 05162019 */
	
	$returnObj['currentUsage'] = null;
	$returnObj['usageLimit'] = null;
	$returnObj['usagePercentage'] = null;
	$returnObj['usageStatus'] = '';
	if ( !is_null( $usage['vodExpiry'] ) ) {
		$vodUsage = floatval( $usage['vodUsage'] );
		$vodQuota = floatval( $usage['vodQuota'] );
		$vodPercentage = $vodQuota == 0 ? 0 : $vodUsage / $vodQuota;
		if ( $vodPercentage < 0.9 ) {
			$vodStatus = 'Normal';
		} else if ( $vodPercentage >= 0.9 && $vodPercentage < 1 ) {
				$vodStatus = 'Warning';
			} else if ( $vodStatus >= 1 ) {
				$vodStatus = 'Exceeded';
			}
		$vodPercentage = round( $vodPercentage * 100, 2 );
		// $vodUsage = convertToBytes($vodUsage);
		// $vodQuota = convertToBytes($vodQuota);
		/*EDITED 05162019 
		$returnObj['vodExpiry']     = $usage['vodExpiry'];
		$returnObj['vodUsage']      = $vodUsage;
		$returnObj['vodQuota']      = $vodQuota;
		$returnObj['vodPercentage'] = $vodPercentage;
		$returnObj['vodStatus']     = $vodStatus;
		EDITED 05162019 */
		
		$returnObj['vodExpiry']     = '';
		$returnObj['vodUsage']      = null;
		$returnObj['vodQuota']      = null;
		$returnObj['vodPercentage'] = null;
		$returnObj['vodStatus']     = '';
	}

	/**************************************************
	 * GET STATUS Added: 122717
	 **************************************************/
	$account = Aaa::getSubscriberWithUsername($aaaConn, $username);
	$accountStatus = '';
	$isAccountExisting = true;
	if (!$account['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$account['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	if (empty($account['data']) || $account['data'] === false || is_null($account['data'])) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found. Checking session existence.');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$isAccountExisting = false;
	}

	if($isAccountExisting) {
		$accountStatus = $account['data']['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D';
	} else {
		$accountStatus = 'T';
	}

	/**************************************************
	 * get data of first session
	 **************************************************/
	$firstSession = $sessions[0];
	$returnObj['sessionId']      = $firstSession['ACCT_SESSION_ID'];
	$returnObj['subscriberIPv4'] = $firstSession['FRAMED_IP_ADDRESS'];
	$returnObj['nasName']        = $firstSession['NAS_IDENTIFIER'];
	$returnObj['nasIPv4']        = $firstSession['NAS_IP_ADDRESS'];
	$returnObj['nasPort']        = $firstSession['NAS_PORT'];
	$returnObj['sessionState']   = $firstSession['SESSION_STATUS'];
	$returnObj['concurrencyId']  = $firstSession['CONCUSERID'];
	$returnObj['macAddress']     = $firstSession['MAC_ADDRESS'];
	$returnObj['startTime']      = $firstSession['START_TIME'];
	$returnObj['status']         = $accountStatus;	// added: 010818
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_SESSION_FOUND.'] returned '.json_encode( $returnObj ) );
	$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_SESSION_FOUND );
	if ( !$lrq['result'] ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
	}
	return $returnObj;
}
function wsDeleteOnlineSession( $param ) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsDeleteOnlineSession';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false );
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode( $param ) );
	$username = $param['username'];
	$sessionId = $param['sessionId'];
	$nasIPv4 = $param['nasIPv4'];
	$returnObj = array(
		'responseCode' => S_SESSION_DELETED,
		'username' => $username,
		'sessionId' => $sessionId,
		'subscriberIPv4' => '' );
	$config = generateConnectionUrls();
	if ( $config === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file' );
		$returnObj['responseCode'] = S_CONFIG_FILE_READ_ERROR;
		return $returnObj;
	}
	/**************************************************
	 * database connections
	 **************************************************/
	$useTblm = $config['useTblm'];
	$useSecondary = $config['useSecondary'];
	$primarySessionConn = null;
	if ( $useTblm ) {
		$primarySessionConn = oci_connect( $config['primaryMconcurrentUsername'], $config['primaryMconcurrentPassword'], $config['primaryMconcurrentUrl'] );
	} else {
		$primarySessionConn = oci_connect( $config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl'] );
	}
	if ( $primarySessionConn === false ) {
		if ( $useTblm ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_PRIMARY_SESSION_DB_CONNECT_ERROR.'] No connection to primary session table: '.
				$config['primaryMconcurrentUrl'].', '.$config['primaryMconcurrentUsername'].'/'.$config['primaryMconcurrentPassword'] );
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_PRIMARY_SESSION_DB_CONNECT_ERROR.'] No connection to primary session table: '.
				$config['primarySessionUrl'].', '.$config['primarySessionUsername'].'/'.$config['primarySessionPassword'] );
		}
		$returnObj['responseCode'] = S_PRIMARY_SESSION_DB_CONNECT_ERROR;
		return $returnObj;
	}
	$secondarySessionConn = null;
	if ( $useSecondary ) {
		if ( $useTblm ) {
			$secondarySessionConn = oci_connect( $config['secondaryMconcurrentUsername'], $config['secondaryMconcurrentPassword'], $config['secondaryMconcurrentUrl'] );
		} else {
			$secondarySessionConn = oci_connect( $config['secondarySessionUsername'], $config['secondarySessionPassword'], $config['secondarySessionUrl'] );
		}
		if ( $secondarySessionConn === false ) {
			if ( $useTblm ) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_SECONDARY_SESSON_DB_CONNECT_ERROR.'] No connection to secondary session table: '.
					$config['secondaryMconcurrentUrl'].', '.$config['secondaryMconcurrentUsername'].'/'.$config['secondaryMconcurrentPassword'] );
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_SECONDARY_SESSON_DB_CONNECT_ERROR.'] No connection to secondary session table: '.
					$config['secondarySessionUrl'].', '.$config['secondarySessionUsername'].'/'.$config['secondarySessionPassword'] );
			}
			$returnObj['responseCode'] = S_SECONDARY_SESSON_DB_CONNECT_ERROR;
			return $returnObj;
		}
	}
	$mysqlConn = new mysqli( $config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET  );
	if ( $mysqlConn->connect_error ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database' );
		$returnObj['responseCode'] = S_MYSQL_DB_CONNECT_ERROR;
		return $returnObj;
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if ( AUTHENTICATE ) {
		$authenticate = authenticate( $mysqlConn, $client['login'], $client['password'], $functionName, $clientIp );
		if ( !$authenticate['continue'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message'] );
			$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, $authenticate['code'] );
			if ( !$lrq['result'] ) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
			}
			$returnObj['responseCode'] = $authenticate['code'];
			return $returnObj;
		}
		$proceed = checkRequestWindow( $mysqlConn, $functionName, array( 200 ), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds'] );
		if ( $proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
			$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message'] );
			$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, $proceed['code'] );
			if ( !$lrq['result'] ) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
			}
			$returnObj['responseCode'] = $proceed['code'];
			return $returnObj;
		} else {
			// writeToFile($apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.$proceed['message']);
		}
	}
	/**************************************************
	 * fetch session specified by username (USER_NAME), sessionId (ACCT_SESSION_ID) and nasIPv4 (NAS_IP_ADDRESS)
	 **************************************************/
	$session = null;
	$sql = "select USER_NAME, FRAMED_IP_ADDRESS ".
		"from ".( $useTblm ? "TBLMCONCURRENTUSERS" : "TBLCONCURRENTUSERS" )." ".
		"where USER_NAME = :username and ACCT_SESSION_ID = :sessionId and NAS_IP_ADDRESS = :nasIPv4";
	$compiled = oci_parse( $primarySessionConn, $sql );
	oci_bind_by_name( $compiled, ':username', $username );
	oci_bind_by_name( $compiled, ':sessionId', $sessionId );
	oci_bind_by_name( $compiled, ':nasIPv4', $nasIPv4 );
	$result = oci_execute( $compiled );
	if ( $result === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_SESSION_DB_QUERY_ERROR.'] Query error (primary): '.$sql );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_SESSION_DB_QUERY_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = S_SESSION_DB_QUERY_ERROR;
		return $returnObj;
	}
	$session = oci_fetch_array( $compiled, OCI_ASSOC + OCI_RETURN_NULLS );
	if ( $useSecondary && ( empty( $session ) || $session === false || is_null( $session ) ) ) {
		$compiled = oci_parse( $secondarySessionConn, $sql );
		oci_bind_by_name( $compiled, ':username', $username );
		oci_bind_by_name( $compiled, ':sessionId', $sessionId );
		oci_bind_by_name( $compiled, ':nasIPv4', $nasIPv4 );
		$result = oci_execute( $compiled );
		if ( $result === false ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_SESSION_DB_QUERY_ERROR.'] Query error (secondary): '.$sql );
			$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_SESSION_DB_QUERY_ERROR );
			if ( !$lrq['result'] ) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
			}
			$returnObj['responseCode'] = S_SESSION_DB_QUERY_ERROR;
			return $returnObj;
		}
		$session = oci_fetch_array( $compiled, OCI_ASSOC + OCI_RETURN_NULLS );
	}
	if ( empty( $session ) || $session === false || is_null( $session ) ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_NO_SESSIONS_FOUND.'] Session not found' );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_NO_SESSIONS_FOUND );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = S_NO_SESSIONS_FOUND;
		return $returnObj;
	}
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Session: '.json_encode( $session ) );
	$returnObj['subscriberIPv4'] = $session['FRAMED_IP_ADDRESS'];
	/**************************************************
	 * delete session (api)
	 **************************************************/
	$deleteSessionClient = null;
	try {
		$deleteSessionClient = new SoapClient( 'http://'.$config['primaryDeleteSessionUrl'] );
	} catch ( Exception $e ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_DELETE_SESSION_CLIENT_ERROR.'] Error at client creation ('.
			$config['primaryDeleteSessionUrl'].'): '.json_encode( $e ) );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_DELETE_SESSION_CLIENT_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = S_DELETE_SESSION_CLIENT_ERROR;
		return $returnObj;
	}
	if ( is_null( $deleteSessionClient ) ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_DELETE_SESSION_CLIENT_ERROR.'] Delete session client is null' );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_DELETE_SESSION_CLIENT_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = S_DELETE_SESSION_CLIENT_ERROR;
		return $returnObj;
	}
	$map = array( '0:4' => $nasIPv4, '0:44' => $sessionId );
	try {
		$deleteResult = $deleteSessionClient->requestDisconnect( $username, $map );
	} catch ( Exception $e ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_REQUEST_DISCONNECT_ERROR.'] Error at requestDisconnect: '.json_encode( $e ) );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_REQUEST_DISCONNECT_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = S_REQUEST_DISCONNECT_ERROR;
		return $returnObj;
	}
	if ( intval( $deleteResult ) != 41 ) { //41 = deleted, 42 = not
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_SESSION_NOT_DELETED_ERROR.'] Unable to delete session' );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_SESSION_NOT_DELETED_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = S_SESSION_NOT_DELETED_ERROR;
		return $returnObj;
	}
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Session deleted via api' );
	/**************************************************
	 * delete at tblmconcurrentusers and tblmcoresessions
	 **************************************************/
	$primaryMconcurrentConn = oci_connect( $config['primaryMconcurrentUsername'], $config['primaryMconcurrentPassword'], $config['primaryMconcurrentUrl'] );
	if ( $primaryMconcurrentConn === false ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_PRIMARY_TBLMCONC_DB_CONNECT_ERROR.'] No connection to primary TBLMCONCURRENTUSERS table: '.
			$config['primaryMconcurrentUrl'].', '.$config['primaryMconcurrentUsername'].'/'.$config['primaryMconcurrentPassword'] );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_PRIMARY_TBLMCONC_DB_CONNECT_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = S_PRIMARY_TBLMCONC_DB_CONNECT_ERROR;
		return $returnObj;
	}
	$primaryMcoreConn = oci_connect( $config['primaryMcoreUsername'], $config['primaryMcorePassword'], $config['primaryMcoreUrl'] );
	if ( $primaryMcoreConn === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_PRIMARY_TBLMCORE_DB_CONNECT_ERROR.'] No connection to primary TBLMCORESESSIONS table: '.
			$config['primaryMcoreUrl'].', '.$config['primaryMcoreUsername'].'/'.$config['primaryMcorePassword'] );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_PRIMARY_TBLMCORE_DB_CONNECT_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = S_PRIMARY_TBLMCORE_DB_CONNECT_ERROR;
		return $returnObj;
	}
	$sqlForTblmconcurrentusers = "delete from TBLMCONCURRENTUSERS where USER_NAME = :username";
	$compiled = oci_parse( $primaryMconcurrentConn, $sqlForTblmconcurrentusers );
	oci_bind_by_name( $compiled, ':username', $username );
	$result = oci_execute( $compiled );
	if ( $result === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_TBLMCONC_DB_QUERY_ERROR.'] Query error (primary): '.$sqlForTblmconcurrentusers );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_TBLMCONC_DB_QUERY_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = S_TBLMCONC_DB_QUERY_ERROR;
		return $returnObj;
	}
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleted at TBLMCONCURRENTUSERS (primary)' );
	$sqlForTblmcoresessions = "delete from TBLMCORESESSIONS where USERIDENTITY = :username";
	$compiled = oci_parse( $primaryMcoreConn, $sqlForTblmcoresessions );
	oci_bind_by_name( $compiled, ':username', $username );
	$result = oci_execute( $compiled );
	if ( $result === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_TBLMCORE_DB_QUERY_ERROR.'] Query error (primary): '.$sqlForTblmcoresessions );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_TBLMCORE_DB_QUERY_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = S_TBLMCORE_DB_QUERY_ERROR;
		return $returnObj;
	}
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleted at TBLMCORESESSIONS (primary)' );
	if ( $useSecondary ) {
		$secondaryMconcurrentConn = oci_connect( $config['secondaryMconcurrentUsername'], $config['secondaryMconcurrentPassword'], $config['secondaryMconcurrentUrl'] );
		if ( $secondaryMconcurrentConn === false ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_SECONDARY_TBLMCONC_DB_CONNECT_ERROR.'] No connection to secondary TBLMCONCURRENTUSERS table: '.
				$config['secondaryMconcurrentUrl'].', '.$config['secondaryMconcurrentUsername'].'/'.$config['secondaryMconcurrentPassword'] );
			$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_SECONDARY_TBLMCONC_DB_CONNECT_ERROR );
			if ( !$lrq['result'] ) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
			}
			$returnObj['responseCode'] = S_SECONDARY_TBLMCONC_DB_CONNECT_ERROR;
			return $returnObj;
		}
		$secondaryMcoreConn = oci_connect( $config['secondaryMcoreUsername'], $config['secondaryMcorePassword'], $config['secondaryMcoreUrl'] );
		if ( $secondaryMcoreConn === false ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_SECONDARY_TBLMCORE_DB_CONNECT_ERROR.'] No connection to secondary TBLMCORESESSIONS table: '.
				$config['secondaryMcoreUrl'].', '.$config['secondaryMcoreUsername'].'/'.$config['secondaryMcorePassword'] );
			$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_SECONDARY_TBLMCORE_DB_CONNECT_ERROR );
			if ( !$lrq['result'] ) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
			}
			$returnObj['responseCode'] = S_SECONDARY_TBLMCORE_DB_CONNECT_ERROR;
			return $returnObj;
		}
		$compiled = oci_parse( $secondaryMconcurrentConn, $sqlForTblmconcurrentusers );
		oci_bind_by_name( $compiled, ':username', $username );
		$result = oci_execute( $compiled );
		if ( $result === false ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_TBLMCONC_DB_QUERY_ERROR.'] Query error (secondary): '.$sqlForTblmconcurrentusers );
			$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_TBLMCONC_DB_QUERY_ERROR );
			if ( !$lrq['result'] ) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
			}
			$returnObj['responseCode'] = S_TBLMCONC_DB_QUERY_ERROR;
			return $returnObj;
		}
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleted at TBLMCONCURRENTUSERS (secondary)' );
		$compiled = oci_parse( $secondaryMcoreConn, $sqlForTblmcoresessions );
		oci_bind_by_name( $compiled, ':username', $username );
		$result = oci_execute( $compiled );
		if ( $result === false ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_TBLMCORE_DB_QUERY_ERROR.'] Query error (secondary): '.$sqlForTblmcoresessions );
			$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_TBLMCORE_DB_QUERY_ERROR );
			if ( !$lrq['result'] ) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
			}
			$returnObj['responseCode'] = S_TBLMCORE_DB_QUERY_ERROR;
			return $returnObj;
		}
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleted at TBLMCORESESSIONS (secondary)' );
	}
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.S_SESSION_DELETED.'] returned '.json_encode( $returnObj ) );
	$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, S_SESSION_DELETED );
	if ( !$lrq['result'] ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
	}
	return $returnObj;
}

function generateConnectionUrls() {
	$configFile = 'config.txt';
	$fileHandle = fopen( $configFile, 'r' );
	if ( $fileHandle === false ) {
		return false;
	}
	$lines = array();
	while ( ( $buffer = fgets( $fileHandle, 4096 ) ) !== false ) {
		$line = trim( $buffer );
		if ( $line != '' ) {
			$lines[] = trim( $buffer );
		}
	}
	$useSessionTable2          = explode( '=', $lines[0] );
	$useTblmConcurrentUsers    = explode( '=', $lines[1] );
	$sessionHost               = explode( '=', $lines[2] );
	$sessionPort               = explode( '=', $lines[3] );
	$sessionSchema             = explode( '=', $lines[4] );
	$sessionUsername           = explode( '=', $lines[5] );
	$sessionPassword           = explode( '=', $lines[6] );
	$sessionHost2              = explode( '=', $lines[7] );
	$sessionPort2              = explode( '=', $lines[8] );
	$sessionSchema2            = explode( '=', $lines[9] );
	$sessionUsername2          = explode( '=', $lines[10] );
	$sessionPassword2          = explode( '=', $lines[11] );
	$tblmConcHost              = explode( '=', $lines[12] );
	$tblmConcPort              = explode( '=', $lines[13] );
	$tblmConcSchema            = explode( '=', $lines[14] );
	$tblmConcUsername          = explode( '=', $lines[15] );
	$tblmConcPassword          = explode( '=', $lines[16] );
	$tblmConcHost2             = explode( '=', $lines[17] );
	$tblmConcPort2             = explode( '=', $lines[18] );
	$tblmConcSchema2           = explode( '=', $lines[19] );
	$tblmConcUsername2         = explode( '=', $lines[20] );
	$tblmConcPassword2         = explode( '=', $lines[21] );
	$tblmCoreHost              = explode( '=', $lines[22] );
	$tblmCorePort              = explode( '=', $lines[23] );
	$tblmCoreSchema            = explode( '=', $lines[24] );
	$tblmCoreUsername          = explode( '=', $lines[25] );
	$tblmCorePassword          = explode( '=', $lines[26] );
	$tblmCoreHost2             = explode( '=', $lines[27] );
	$tblmCorePort2             = explode( '=', $lines[28] );
	$tblmCoreSchema2           = explode( '=', $lines[29] );
	$tblmCoreUsername2         = explode( '=', $lines[30] );
	$tblmCorePassword2         = explode( '=', $lines[31] );
	$detourApiHost             = explode( '=', $lines[32] );
	$detourApiPort             = explode( '=', $lines[33] );
	$detourApiStub             = explode( '=', $lines[34] );
	$detourApiHost2            = explode( '=', $lines[35] );
	$detourApiPort2            = explode( '=', $lines[36] );
	$detourApiStub2            = explode( '=', $lines[37] );
	$deleteSessionApiHost      = explode( '=', $lines[38] );
	$deleteSessionApiPort      = explode( '=', $lines[39] );
	$deleteSessionApiStub      = explode( '=', $lines[40] );
	$deleteSessionApiHost2     = explode( '=', $lines[41] );
	$deleteSessionApiPort2     = explode( '=', $lines[42] );
	$deleteSessionApiStub2     = explode( '=', $lines[43] );
	$mysqlHost                 = explode( '=', $lines[44] );
	$mysqlDatabase             = explode( '=', $lines[45] );
	$mysqlUsername             = explode( '=', $lines[46] );
	$mysqlPassword             = explode( '=', $lines[47] );
	$requestPerWindow          = explode( '=', $lines[48] );
	$requestWindowInSeconds    = explode( '=', $lines[49] );
	$requestBlockTimeInSeconds = explode( '=', $lines[50] );
	$configObj = array(
		'useSecondary'                 => intval( $useSessionTable2[1] ) == 1,
		'useTblm'                      => intval( $useTblmConcurrentUsers[1] ) == 1,
		'primarySessionUrl'            => $sessionHost[1].':'.$sessionPort[1].'/'.$sessionSchema[1],
		'primarySessionUsername'       => $sessionUsername[1],
		'primarySessionPassword'       => $sessionPassword[1],
		'secondarySessionUrl'          => $sessionHost2[1].':'.$sessionPort2[1].'/'.$sessionSchema2[1],
		'secondarySessionUsername'     => $sessionUsername2[1],
		'secondarySessionPassword'     => $sessionPassword2[1],
		'primaryMconcurrentUrl'        => $tblmConcHost[1].':'.$tblmConcPort[1].'/'.$tblmConcSchema[1],
		'primaryMconcurrentUsername'   => $tblmConcUsername[1],
		'primaryMconcurrentPassword'   => $tblmConcPassword[1],
		'secondaryMconcurrentUrl'      => $tblmConcHost2[1].':'.$tblmConcPort2[1].'/'.$tblmConcSchema2[1],
		'secondaryMconcurrentUsername' => $tblmConcUsername2[1],
		'secondaryMconcurrentPassword' => $tblmConcPassword2[1],
		'primaryMcoreUrl'              => $tblmCoreHost[1].':'.$tblmCorePort[1].'/'.$tblmCoreSchema[1],
		'primaryMcoreUsername'         => $tblmCoreUsername[1],
		'primaryMcorePassword'         => $tblmCorePassword[1],
		'secondaryMcoreUrl'            => $tblmCoreHost2[1].':'.$tblmCorePort2[1].'/'.$tblmCoreSchema2[1],
		'secondaryMcoreUsername'       => $tblmCoreUsername2[1],
		'secondaryMcorePassword'       => $tblmCorePassword2[1],
		'primaryDetourUrl'             => $detourApiHost[1].':'.$detourApiPort[1].'/'.$detourApiStub[1],
		'secondaryDetourUrl'           => $detourApiHost2[1].':'.$detourApiPort2[1].'/'.$detourApiStub[1],
		'primaryDeleteSessionUrl'      => $deleteSessionApiHost[1].':'.$deleteSessionApiPort[1].'/'.$deleteSessionApiStub[1],
		'secondaryDeleteSessionUrl'    => $deleteSessionApiHost2[1].':'.$deleteSessionApiPort2[1].'/'.$deleteSessionApiStub2[1],
		'mysqlHost'                    => $mysqlHost[1],
		'mysqlDatabase'                => $mysqlDatabase[1],
		'mysqlUsername'                => $mysqlUsername[1],
		'mysqlPassword'                => $mysqlPassword[1],
		'requestPerWindow'             => $requestPerWindow[1],
		'requestWindowInSeconds'       => $requestWindowInSeconds[1],
		'requestBlockTimeInSeconds'    => $requestBlockTimeInSeconds[1] );
	return $configObj;
}
function writeToFile( $file, $msg, $timeStamp = true, $timeOnly = true ) {
	file_put_contents( $file, ( $timeStamp ? ( $timeOnly ? date( 'H:i:s', time() )." " : date( 'Y-m-d H:i:s', time() )." " ) : "" ).$msg."\n", FILE_APPEND | LOCK_EX );
}
function convertToBytes( $floatValue ) {
	$formatted = number_format( $floatValue, 0, '.', ',' );
	$parts = explode( ',', $formatted );
	$partCount = count( $parts );
	$returnValue = '';
	if ( $partCount >= 4 ) {
		$crop = substr( $formatted, strrpos( $formatted, ',' ) );
		$gb = $floatValue / ( 1024 * 1024 * 1024 );
		$gb = round( $gb, 2 );
		$returnValue = $gb.' GB';
	} else if ( $partCount == 3 ) {
			$crop = substr( $formatted, strrpos( $formatted, ',' ) );
			$mb = $floatValue / ( 1024 * 1024 );
			$mb = round( $mb, 2 );
			$returnValue = $mb.' MB';
		} else if ( $partCount == 2 ) {
			$kb = $floatValue / 1024;
			$kb = round( $kb, 2 );
			$returnValue = $kb.' KB';
		} else if ( $partCount == 1 ) {
			$b = $formatted.' B';
			$returnValue = $b;
		}
	return $returnValue;
}



$HTTP_RAW_POST_DATA = isset( $HTTP_RAW_POST_DATA ) ? $HTTP_RAW_POST_DATA : '';
$server->service( $HTTP_RAW_POST_DATA );

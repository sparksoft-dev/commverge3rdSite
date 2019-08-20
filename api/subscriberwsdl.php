<?php
require_once '/var/www/html/executables/api/lib/nusoap.php';
require_once '/var/www/html/executables/api/authenticateresultcodes.php';
require_once '/var/www/html/executables/api/subscriberwsdlresultcodes.php';
require_once '/var/www/html/executables/api/authentication.php';
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
$namespace = $host.'/executables/api/subscriberwsdl.php';
$server    = new soap_server();
$server->configureWSDL( 'subscriberwsdl', $namespace );
/**************************************************
 * change password vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsChangePasswordRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username'    => array( 'name' => 'username', 'type' => 'xsd:string' ),
		'newPassword' => array( 'name' => 'newPassword', 'type' => 'xsd:string' ) ) );
$server->wsdl->addComplexType(
	'wsChangePasswordResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array( 'name' => 'responseCode', 'type' => 'xsd:long' ),
		'username'     => array( 'name' => 'username', 'type' => 'xsd:string' ),
		'newPassword'  => array( 'name' => 'newPassword', 'type' => 'xsd:string' ),
		'changedDate'  => array( 'name' => 'changedDate', 'type' => 'xsd:string' ) ) );
$server->register(
	'wsChangePassword',
	array( 'param' => 'tns:wsChangePasswordRequest' ),
	array( 'return' => 'tns:wsChangePasswordResponse' ),
	$namespace,
	'urn:subscriberwsdl#wsChangePassword',
	'rpc',
	'encoded',
	'Change subscriber password in AAA' );


/**************************************************
 * reset password vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsResetPasswordRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array( 'name' => 'username', 'type' => 'xsd:string' ) ) );
$server->wsdl->addComplexType(
	'wsResetPasswordResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array( 'name' => 'responseCode', 'type' => 'xsd:long' ),
		'username'     => array( 'name' => 'username', 'type' => 'xsd:string' ),
		'newPassword'  => array( 'name' => 'newPassword', 'type' => 'xsd:string' ),
		'changedDate'  => array( 'name' => 'changedDate', 'type' => 'xsd:string' ) ) );
$server->register(
	'wsResetPassword',
	array( 'param'  => 'tns:wsResetPasswordRequest' ),
	array( 'return' => 'tns:wsResetPasswordResponse' ),
	$namespace,
	'urn:subscriberwsdl#wsResetPassword',
	'rpc',
	'encoded',
	'Reset subscriber password with auto generated string' );
/**************************************************
 * functions
 **************************************************/
function wsChangePassword( $param ) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsChangePassword';
	$client       = getClientLogin();
	$clientIp     = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false );
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode( $param ) );
	$username  = $param['username'];
	$password  = $param['newPassword'];
	$returnObj = array(
		'responseCode' => P_PASSWORD_UPDATE_SUCCESS,
		'username' => $username,
		'newPassword' => $password,
		'changedDate' => '' );
	$config = generateConnectionUrls();
	if ( $config === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.P_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file' );
		$returnObj['responseCode'] = P_CONFIG_FILE_READ_ERROR;
		return $returnObj;
	}
	/**************************************************
	 * database connections
	 * TBLCUSTOMER & TBLCONCURRENTUSERS have the same access
	 **************************************************/
	$dbConn = oci_connect( $config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl'] );
	if ( $dbConn === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.P_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database: '.
			$config['primarySessionUrl'].', '.$config['primarySessionUsername'].'/'.$config['primarySessionPassword'] );
		$returnObj['responseCode'] = P_ORACLE_DB_CONNECT_ERROR;
		return $returnObj;
	}
	$mysqlConn = new mysqli( $config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET  );
	if ( $mysqlConn->connect_error ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.P_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database' );
		$returnObj['responseCode'] = P_MYSQL_DB_CONNECT_ERROR;
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
	 * check if $password is provided and is not empty string
	 **************************************************/
	if ( trim( $password ) == '' ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.P_PASSWORD_REQUIRED.'] No password provided' );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, P_PASSWORD_REQUIRED );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = P_PASSWORD_REQUIRED;
		return $returnObj;
	}
	/**************************************************
	 * check if $username exists
	 **************************************************/
	$sql = "select USERNAME, PASSWORD from TBLCUSTOMER where USERNAME = :username";
	$compiled = oci_parse( $dbConn, $sql );
	oci_bind_by_name( $compiled, ':username', $username );
	$result = oci_execute( $compiled );
	if ( $result === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.P_ORACLE_DB_QUERY_ERROR.'] Query error: '.$sql );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, P_ORACLE_DB_QUERY_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = P_ORACLE_DB_QUERY_ERROR;
		return $returnObj;
	}
	$account = oci_fetch_array( $compiled, OCI_ASSOC + OCI_RETURN_NULLS );
	if ( $account === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.P_USERNAME_NOT_FOUND.'] Username not found' );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, P_USERNAME_NOT_FOUND );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = P_USERNAME_NOT_FOUND;
		return $returnObj;
	}
	/**************************************************
	 * update password
	 **************************************************/
	$now = date( 'Y-m-d H:i:s', time() );
	$sql = "update TBLCUSTOMER set PASSWORD = :password, LASTMODIFIEDDATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') ".
		"where USERNAME = :username";
	$compiled = oci_parse( $dbConn, $sql );
	oci_bind_by_name( $compiled, ':password', $password );
	oci_bind_by_name( $compiled, ':username', $username );
	oci_bind_by_name( $compiled, ':now', $now );
	$result = oci_execute( $compiled );
	if ( $result === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.P_ORACLE_DB_QUERY_ERROR.'] Query error: '.$sql );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, P_ORACLE_DB_QUERY_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = P_ORACLE_DB_QUERY_ERROR;
		return $returnObj;
	}
	$returnObj['changedDate'] = date( 'Y-m-d H:i:s', time() );
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.P_PASSWORD_UPDATE_SUCCESS.'] Password updated | returned '.json_encode( $returnObj ) );
	$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, P_PASSWORD_UPDATE_SUCCESS );
	if ( !$lrq['result'] ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
	}
	return $returnObj;
}
function wsResetPassword( $param ) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsResetPassword';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false );
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." reqest from ".$clientIp.": ".json_encode( $param ) );
	$username = $param['username'];
	$returnObj = array(
		'responseCode' => 200,
		'username' => $username,
		'newPassword' => '',
		'changedDate' => '' );
	$config = generateConnectionUrls();
	if ( $config === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.P_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file' );
		$returnObj['responseCode'] = P_CONFIG_FILE_READ_ERROR;
		return $returnObj;
	}
	/**************************************************
	 * generate new password
	 **************************************************/
	$generatedPassword = generateRandomString( 10 );
	/**************************************************
	 * database connections
	 * TBLCUSTOMER & TBLCONCURRENTUSERS have the same access
	 **************************************************/
	$dbConn = oci_connect( $config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl'] );
	if ( $dbConn === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.P_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database: '.
			$config['primarySessionUrl'].', '.$config['primarySessionUsername'].'/'.$config['primarySessionPassword'] );
		$returnObj['responseCode'] = P_ORACLE_DB_CONNECT_ERROR;
		return $returnObj;
	}
	$mysqlConn = new mysqli( $config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET  );
	if ( $mysqlConn->connect_error ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.P_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database' );
		$returnObj['responseCode'] = P_MYSQL_DB_CONNECT_ERROR;
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
	 * check if $username exists
	 **************************************************/
	$sql = "select USERNAME, PASSWORD from TBLCUSTOMER where USERNAME = :username";
	$compiled = oci_parse( $dbConn, $sql );
	oci_bind_by_name( $compiled, ':username', $username );
	$result = oci_execute( $compiled );
	if ( $result === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.P_ORACLE_DB_QUERY_ERROR.'] Query error: '.$sql );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, P_ORACLE_DB_QUERY_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = P_ORACLE_DB_QUERY_ERROR;
		return $returnObj;
	}
	$account = oci_fetch_array( $compiled, OCI_ASSOC + OCI_RETURN_NULLS );
	if ( $account === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.P_USERNAME_NOT_FOUND.'] Username not found' );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, P_USERNAME_NOT_FOUND );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = P_USERNAME_NOT_FOUND;
		return $returnObj;
	}
	/**************************************************
	 * update password
	 **************************************************/
	$now = date( 'Y-m-d H:i:s', time() );
	$sql = "update TBLCUSTOMER set PASSWORD = :password, LASTMODIFIEDDATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') ".
		"where USERNAME = :username";
	$compiled = oci_parse( $dbConn, $sql );
	oci_bind_by_name( $compiled, ':password', $generatedPassword );
	oci_bind_by_name( $compiled, ':username', $username );
	oci_bind_by_name( $compiled, ':now', $now );
	$result = oci_execute( $compiled );
	if ( $result === false ) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.P_ORACLE_DB_QUERY_ERROR.'] Query error: '.$sql );
		$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, P_ORACLE_DB_QUERY_ERROR );
		if ( !$lrq['result'] ) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request' );
		}
		$returnObj['responseCode'] = P_ORACLE_DB_QUERY_ERROR;
		return $returnObj;
	}
	$returnObj['newPassword'] = $generatedPassword;
	$returnObj['changedDate'] = date( 'Y-m-d H:i:s', time() );
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.P_PASSWORD_RESET_SUCCESS.'] Password updated | returned '.json_encode( $returnObj ) );
	$lrq = logRequest( $mysqlConn, $clientIp, $functionName, $param, P_PASSWORD_RESET_SUCCESS );
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
function generateRandomString( $len ) {
	$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$charLen = strlen( $characters ) - 1;
	$rand = '';
	for ( $i = 0; $i < $len; $i++ ) {
		$rand .= $characters[mt_rand( 0, $charLen )];
	}
	return $rand;
}


$HTTP_RAW_POST_DATA = isset( $HTTP_RAW_POST_DATA ) ? $HTTP_RAW_POST_DATA : '';
$server->service( $HTTP_RAW_POST_DATA );

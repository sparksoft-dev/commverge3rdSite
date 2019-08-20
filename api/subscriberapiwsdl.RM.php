<?php
require_once 'lib/nusoap.php';
require_once 'authenticateresultcodes.php';
require_once 'subscriberapiwsdlresultcodes.php';
require_once 'authentication.php';
require_once 'rm.php';
require_once 'aaa.php';
date_default_timezone_set('Asia/Manila');
ini_set('memory_limit', '512M');
/**************************************************
 * log-related
 **************************************************/
define("AUTHENTICATE", false);
define( "MYSQLSOCKET", '/var/lib/mysql/mysql.sock' );
define( "MYSQLPORT", 3306 );
define( "RMENABLED", true );
// $apiAccessLogDir = '/util/api_log/';
// $apiAccessLogDir = '/webutil/web_logs/api/';
// $logFile = $apiAccessLogDir.'capone-'.date('Ymd', time()); #.'.log';

$apiAccessLogDir = '/webutil/web_logs/api/CAPONE/';
if (!is_dir($apiAccessLogDir)) {
    mkdir($apiAccessLogDir, 0755, true);
}
$logFile         = '_'.date( 'Ymd', time() ); #.'.log';

/**************************************************
 * soap server variables and functions
 **************************************************/
$host = 'https://222.127.121.130';
$namespace = $host.'/executables/api/subscriberapiwsdl.php';
$server = new soap_server();
$server->configureWSDL('subscriberapiwsdl', $namespace);
/**************************************************
 * wsCreateAccount vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsCreateAccountRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username'      => array('name' => 'username', 'type' => 'xsd:string'),
		'password'      => array('name' => 'password', 'type' => 'xsd:string'),
		'customerType'  => array('name' => 'customerType', 'type' => 'xsd:string'),
		'status'        => array('name' => 'status', 'type' => 'xsd:string'),
		'customerName'  => array('name' => 'customerName', 'type' => 'xsd:string'),
		'orderNumber'   => array('name' => 'orderNumber', 'type' => 'xsd:string'),
		'serviceNumber' => array('name' => 'serviceNumber', 'type' => 'xsd:string'),
		'plan'          => array('name' => 'plan', 'type' => 'xsd:string'),
		'ipAddress'     => array('name' => 'ipAddress', 'type' => 'xsd:string'),
		'ipv6Address'   => array('name' => 'ipv6Address', 'type' => 'xsd:string'),
		'netAddress'    => array('name' => 'netAddress', 'type' => 'xsd:string'),
		'nasLocation'   => array('name' => 'nasLocation', 'type' => 'xsd:string'),
		'remarks'       => array('name' => 'remarks', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsCreateAccountResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsCreateAccount',
	array('param' => 'tns:wsCreateAccountRequest'),
	array('param' => 'tns:wsCreateAccountResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsCreateAccount',
	'rpc',
	'encoded',
	'Provision new subscriber in AAA and RM');
/**************************************************
 * wsModifyAccount vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsModifyAccountRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username'      => array('name' => 'username', 'type' => 'xsd:string'),
		'password'      => array('name' => 'password', 'type' => 'xsd:string'),
		'customerType'  => array('name' => 'customerType', 'type' => 'xsd:string'),
		'status'        => array('name' => 'status', 'type' => 'xsd:string'),
		'customerName'  => array('name' => 'customerName', 'type' => 'xsd:string'),
		'orderNumber'   => array('name' => 'orderNumber', 'type' => 'xsd:string'),
		'serviceNumber' => array('name' => 'serviceNumber', 'type' => 'xsd:string'),
		'plan'          => array('name' => 'plan', 'type' => 'xsd:string'),
		'ipAddress'     => array('name' => 'ipAddress', 'type' => 'xsd:string'),
		'ipv6Address'   => array('name' => 'ipv6Address', 'type' => 'xsd:string'),
		'netAddress'    => array('name' => 'netAddress', 'type' => 'xsd:string'),
		'nasLocation'   => array('name' => 'nasLocation', 'type' => 'xsd:string'),
		'remarks'       => array('name' => 'remarks', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsModifyAccountResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsModifyAccount',
	array('param' => 'tns:wsModifyAccountRequest'),
	array('param' => 'tns:wsModifyAccountResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsModifyAccount',
	'rpc',
	'encoded',
	'Update subscriber data in AAA and RM');
/**************************************************
 * wsDeleteAccount vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsDeleteAccountRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array('name' => 'username', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsDeleteAccountResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:string'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsDeleteAccount',
	array('param' => 'tns:wsDeleteAccountRequest'),
	array('param' => 'tns:wsDeleteAccountResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsDeleteAccount',
	'rpc',
	'encoded',
	'Permanently delete subscriber account in AAA and RM');
/**************************************************
 * wsChangePlan vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsChangePlanRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array('name' => 'username', 'type' => 'xsd:string'),
		'plan' => array('name' => 'plan', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsChangePlanResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsChangePlan',
	array('param' => 'tns:wsChangePlanRequest'),
	array('param' => 'tns:wsChangePlanResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsChangePlan',
	'rpc',
	'encoded',
	'Replace existing plan subscription of a subscriber account');
/**************************************************
 * wsChangeUsername vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsChangeUsernameRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'oldUsername' => array('name' => 'oldUsername', 'type' => 'xsd:string'),
		'newUsername' => array('name' => 'newUsername', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsChangeUsernameResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsChangeUsername',
	array('param' => 'tns:wsChangeUsernameRequest'),
	array('param' => 'tns:wsChangeUsernameResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsChangeUsername',
	'rpc',
	'encoded',
	'Change the existing username of subscriber account in AAA and RM');
/**************************************************
 * wsDeactivateAccount vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsDeactivateAccountRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array('name' => 'username', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsDeactivateAccountResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsDeactivateAccount',
	array('param' => 'tns:wsDeactivateAccountRequest'),
	array('param' => 'tns:wsDeactivateAccountResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsDeactivateAccount',
	'rpc',
	'encoded',
	'Temporary Disconnect subscriber account in AAA and RM');
/**************************************************
 * wsActivateAccount vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsActivateAccountRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array('name' => 'username', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsActivateAccountResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsActivateAccount',
	array('param' => 'tns:wsActivateAccountRequest'),
	array('param' => 'tns:wsActivateAccountResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsActivateAccount',
	'rpc',
	'encoded',
	'Activate Disconnected subscriber account in AAA and RM');
/**************************************************
 * wsAddIPAddress vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsAddIPAddressRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array('name' => 'username', 'type' => 'xsd:string'),
		'ipAddress' => array('name' => 'ipAddress', 'type' => 'xsd:string'),
		'cabinetName' => array('name' => 'cabinetName', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsAddIPAddressResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsAddIPAddress',
	array('param' => 'tns:wsAddIPAddressRequest'),
	array('param' => 'tns:wsAddIPAddressResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsAddIPAddress',
	'rpc',
	'encoded',
	'Assign Static IP Address to a subscriber');
/**************************************************
 * wsAddIPv6Address vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsAddIPv6AddressRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array('name' => 'username', 'type' => 'xsd:string'),
		'ipv6Address' => array('name' => 'ipv6Address', 'type' => 'xsd:string'),
		'cabinetName' => array('name' => 'cabinetName', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsAddIPv6AddressResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsAddIPv6Address',
	array('param' => 'tns:wsAddIPv6AddressRequest'),
	array('param' => 'tns:wsAddIPv6AddressResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsAddIPv6Address',
	'rpc',
	'encoded',
	'Assign Static IPv6 Address to a subscriber');
/**************************************************
 * wsAddNetAddress vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsAddNetAddressRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array('name' => 'username', 'type' => 'xsd:string'),
		'netAddress' => array('name' => 'netAddress', 'type' => 'xsd:string'),
		'cabinetName' => array('name' => 'cabinetName', 'type' => 'xsd:string'),
		'range' => array('name' => 'range', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsAddNetAddressResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsAddNetAddress',
	array('param' => 'tns:wsAddNetAddressRequest'),
	array('param' => 'tns:wsAddNetAddressResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsAddNetAddress',
	'rpc',
	'encoded',
	'Assign Multi-Static IP Address to a subscriber');
/**************************************************
 * wsRemoveIPAddress vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsRemoveIPAddressRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array('name' => 'username', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsRemoveIPAddressResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsRemoveIPAddress',
	array('param' => 'tns:wsRemoveIPAddressRequest'),
	array('param' => 'tns:wsRemoveIPAddressResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsRemoveIPAddress',
	'rpc',
	'encoded',
	'Unassign Static IP Address to a subscriber');
/**************************************************
 * wsRemoveIPv6Address vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsRemoveIPv6AddressRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array('name' => 'username', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsRemoveIPv6AddressResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsRemoveIPv6Address',
	array('param' => 'tns:wsRemoveIPv6AddressRequest'),
	array('param' => 'tns:wsRemoveIPv6AddressResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsRemoveIPv6Address',
	'rpc',
	'encoded',
	'Unassign Static IPv6 Address to a subscriber');
/**************************************************
 * wsRemoveNetAddress vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsRemoveNetAddressRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array('name' => 'username', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsRemoveNetAddressResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsRemoveNetAddress',
	array('param' => 'tns:wsRemoveNetAddressRequest'),
	array('param' => 'tns:wsRemoveNetAddressResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsRemoveNetAddress',
	'rpc',
	'encoded',
	'Unassign or remove Multi-Static IP Address to a subscriber');
/**************************************************
 * wsAddLocation vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsAddLocationRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array('name' => 'username', 'type' => 'xsd:string'),
		'cabinetName' => array('name' => 'cabinetName', 'type' => 'xsd:string'),
		'location' => array('name' => 'location', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsAddLocationResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsAddLocation',
	array('param' => 'tns:wsAddLocationRequest'),
	array('param' => 'tns:wsAddLocationResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsAddLocation',
	'rpc',
	'encoded',
	'Assign RM Location to subscriber account');
/**************************************************
 * wsSearchAccount vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsSearchAccountRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array('name' => 'username', 'type' => 'xsd:string'),
		'serviceNumber' => array('name' => 'serviceNumber', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsSearchAccountResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode'  => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage'  => array('name' => 'replyMessage', 'type' => 'xsd:string'),
		'username'      => array('name' => 'username', 'type' => 'xsd:string'),
		'name'          => array('name' => 'name', 'type' => 'xsd:string'),
		'serviceNumber' => array('name' => 'serviceNumber', 'type' => 'xsd:string'),
		'status'        => array('name' => 'status', 'type' => 'xsd:string'),
		'servicePlan'   => array('name' => 'servicePlan', 'type' => 'xsd:string'),
		'customerType'  => array('name' => 'customerType', 'type' => 'xsd:string'),
		'redirected'    => array('name' => 'redirected', 'type' => 'xsd:string'),
		'staticIPv6'    => array('name' => 'staticIPv6', 'type' => 'xsd:string'),
		'staticIP'      => array('name' => 'staticIP', 'type' => 'xsd:string'),
		'multiIP'       => array('name' => 'multiIP', 'type' => 'xsd:string'),
		'orderNumber'   => array('name' => 'orderNumber', 'type' => 'xsd:string')));
$server->register(
	'wsSearchAccount',
	array('param' => 'tns:wsSearchAccountRequest'),
	array('param' => 'tns:wsSearchAccountResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsSearchAccount',
	'rpc',
	'encoded',
	'Search AAA subscriber account');
/**************************************************
 * wsSearchCabinet vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsSearchCabinetRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'cabinetName' => array('name' => 'cabinetName', 'type' => 'xsd:string'),
		'vlan' => array('name' => 'vlan', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsSearchCabinetResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string'),
		'cabinetName'  => array('name' => 'cabinetName', 'type' => 'xsd:string'),
		'location'     => array('name' => 'location', 'type' => 'xsd:string'),
		'vlan'         => array('name' => 'vlan', 'type' => 'xsd:string')));
$server->register(
	'wsSearchCabinet',
	array('param' => 'tns:wsSearchCabinetRequest'),
	array('param' => 'tns:wsSearchCabinetResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsSearchCabinet',
	'rpc',
	'encoded',
	'Search cabinet name of VLAN in DSL Utility');
/**************************************************
 * wsSearchVOD vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsSearchVODRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array('name' => 'username', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsSearchVODResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string'),
		'username' => array('name' => 'username', 'type' => 'xsd:string'),
		'vodName' => array('name' => 'vodName', 'type' => 'xsd:string'),
		'subscriptionStartTime' => array('name' => 'subscriptionStartTime', 'type' => 'xsd:string'),
		'expiryDate' => array('name' => 'expiryDate', 'type' => 'xsd:string')));
$server->register(
	'wsSearchVOD',
	array('param' => 'tns:wsSearchVODRequest'),
	array('param' => 'tns:wsSearchVODResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsSearchVOD',
	'rpc',
	'encoded',
	'Search existing VOD details of a subscriber account');
/**************************************************
 * wsAddVOD vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsAddVODRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array('name' => 'username', 'type' => 'xsd:string'),
		'addOnVodInGb' => array('name' => 'addOnVodInGb', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsAddVODResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsAddVOD',
	array('param' => 'tns:wsAddVODRequest'),
	array('param' => 'tns:wsAddVODResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsAddVOD',
	'rpc',
	'encoded',
	'Add VOD to subscriber account');
/**************************************************
 * wsDeleteVOD vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsDeleteVODRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'username' => array('name' => 'username', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsDeleteVODResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsDeleteVOD',
	array('param' => 'tns:wsDeleteVODRequest'),
	array('param' => 'tns:wsDeleteVODResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsDeleteVOD',
	'rpc',
	'encoded',
	'Delete existing VOD to subscriber account');
/**************************************************
 * wsCheckIPAddress vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsCheckIPAddressRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'ipAddress' => array('name' => 'ipAddress', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsCheckIPAddressResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsCheckIPAddress',
	array('param' => 'tns:wsCheckIPAddressRequest'),
	array('param' => 'tns:wsCheckIPAddressResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsCheckIPAddress',
	'rpc',
	'encoded',
	'Check the status of static IP address');
/**************************************************
 * wsCheckIPv6Address vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsCheckIPv6AddressRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'ipv6Address' => array('name' => 'ipv6Address', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsCheckIPv6AddressResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsCheckIPv6Address',
	array('param' => 'tns:wsCheckIPv6AddressRequest'),
	array('param' => 'tns:wsCheckIPv6AddressResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsCheckIPv6Address',
	'rpc',
	'encoded',
	'Check the status of Static IPv6 Address');
/**************************************************
 * wsCheckNetAddress vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsCheckNetAddressRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'netAddress' => array('name' => 'netAddress', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'wsCheckNetAddressResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string')));
$server->register(
	'wsCheckNetAddress',
	array('param' => 'tns:wsCheckNetAddressRequest'),
	array('param' => 'tns:wsCheckNetAddressResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsCheckNetAddress',
	'rpc',
	'encoded',
	'Check the status of multi-static IP address');
/**************************************************
 * wsGetAvailableIPAddress vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsGetAvailableIPAddressRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'bngHostname' => array('name' => 'bngHostname', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'IPData',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'staticIP' => array('name' => 'staticIP', 'type' => 'xsd:string'),
		'status' => array('name' => 'status', 'type' => 'xsd:string'),
		'location' => array('name'=> 'location', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'IPArray',
	'complexType',
	'array',
	'all',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:IPData[]')),
	'tns:IPData');
$server->wsdl->addComplexType(
	'wsGetAvailableIPAddressResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string'),
		'ipAddresses' => array('name' => 'ipAddresses', 'type' => 'tns:IPArray')));
$server->register(
	'wsGetAvailableIPAddress',
	array('param' => 'tns:wsGetAvailableIPAddressRequest'),
	array('param' => 'tns:wsGetAvailableIPAddressResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsGetAvailableIPAddress',
	'rpc',
	'encoded',
	'Extract all available static IP addresses');
/**************************************************
 * wsGetAvailableIPv6Address vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsGetAvailableIPv6AddressRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'bngHostname' => array('name' => 'bngHostname', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'IPv6Data',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'staticIP' => array('name' => 'staticIP', 'type' => 'xsd:string'),
		'status' => array('name' => 'status', 'type' => 'xsd:string'),
		'location' => array('name' => 'location', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'IPv6Array',
	'complexType',
	'array',
	'all',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:IPv6Data[]')),
	'tns:IPv6Data');
$server->wsdl->addComplexType(
	'wsGetAvailableIPv6AddressResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string'),
		'ipAddresses' => array('name' => 'ipAddresses', 'type' => 'tns:IPv6Array')));
$server->register(
	'wsGetAvailableIPv6Address',
	array('param' => 'tns:wsGetAvailableIPv6AddressRequest'),
	array('param' => 'tns:wsGetAvailableIPv6AddressResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsGetAvailableIPv6Address',
	'rpc',
	'encoded',
	'Extract All Available Static IPv6 Address');
/**************************************************
 * wsGetAvailableNetAddress vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsGetAvailableNetAddressRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'bngHostname' => array('name' => 'bngHostname', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'NetData',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'multiIP' => array('name' => 'multiIP', 'type' => 'xsd:string'),
		'status' => array('name' => 'status', 'type' => 'xsd:string'),
		'location' => array('name' => 'location', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'NetArray',
	'complexType',
	'array',
	'all',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:NetData[]')),
	'tns:NetData');
$server->wsdl->addComplexType(
	'wsGetAvailableNetAddressResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string'),
		'netAddresses' => array('name' => 'netAddresses', 'type' => 'tns:NetArray')));
$server->register(
	'wsGetAvailableNetAddress',
	array('param' => 'tns:wsGetAvailableNetAddressRequest'),
	array('param' => 'tns:wsGetAvailableNetAddressResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsGetAvailableNetAddress',
	'rpc',
	'encoded',
	'Extract all available multi-static IP addresses');
/**************************************************
 * wsGetAssignedIPAddress vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsGetAssignedIPAddressRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'bngHostname' => array('name' => 'bngHostname', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'IPAssignedData',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'staticIP' => array('name' => 'staticIP', 'type' => 'xsd:string'),
		'status' => array('name' => 'status', 'type' => 'xsd:string'),
		'location' => array('name' => 'location', 'type' => 'xsd:string'),
		'username' => array('name' => 'username', 'type' => 'xsd:string'),
		'dateAssigned' => array('name' => 'dateAssigned', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'IPAssignedArray',
	'complexType',
	'array',
	'all',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:IPAssignedData[]')),
	'tns:IPAssignedData');
$server->wsdl->addComplexType(
	'wsGetAssignedIPAddressResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string'),
		'ipAddresses' => array('name' => 'ipAddresses', 'type' => 'tns:IPAssignedArray')));
$server->register(
	'wsGetAssignedIPAddress',
	array('param' => 'tns:wsGetAssignedIPAddressRequest'),
	array('param' => 'tns:wsGetAssignedIPAddressResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsGetAssignedIPAddress',
	'rpc',
	'encoded',
	'Extract all assigned static IP addresses');
/**************************************************
 * wsGetAssignedIPv6Address vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsGetAssignedIPv6AddressRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'bngHostname' => array('name' => 'bngHostname', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'IPv6AssignedData',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'staticIP' => array('name' => 'staticIP', 'type' => 'xsd:string'),
		'status' => array('name' => 'status', 'type' => 'xsd:string'),
		'location' => array('name' => 'location', 'type' => 'xsd:string'),
		'username' => array('name' => 'username', 'type' => 'xsd:string'),
		'dateAssigned' => array('name' => 'dateAssigned', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'IPv6AssignedArray',
	'complexType',
	'array',
	'all',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:IPv6AssignedData[]')),
	'tns:IPv6AssignedData');
$server->wsdl->addComplexType(
	'wsGetAssignedIPv6AddressResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string'),
		'ipAddresses' => array('name' => 'ipAddresses', 'type' => 'tns:IPv6AssignedArray')));
$server->register(
	'wsGetAssignedIPv6Address',
	array('param' => 'tns:wsGetAssignedIPv6AddressRequest'),
	array('param' => 'tns:wsGetAssignedIPv6AddressResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsGetAssignedIPv6Address',
	'rpc',
	'encoded',
	'Extract All Assigned Static IPv6 Addresses');
/**************************************************
 * wsGetAssignedNetAddress vars
 **************************************************/
$server->wsdl->addComplexType(
	'wsGetAssignedNetAddressRequest',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'bngHostname' => array('name' => 'bngHostname', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'NetAssignedData',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'multiIP' => array('name' => 'multiIP', 'type' => 'xsd:string'),
		'status' => array('name' => 'status', 'type' => 'xsd:string'),
		'location' => array('name' => 'location', 'type' => 'xsd:string'),
		'username' => array('name' => 'username', 'type' => 'xsd:string'),
		'dateAssigned' => array('name' => 'dateAssigned', 'type' => 'xsd:string')));
$server->wsdl->addComplexType(
	'NetAssignedArray',
	'complexType',
	'array',
	'all',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:NetAssignedData[]')),
	'tns:NetAssignedData');
$server->wsdl->addComplexType(
	'wsGetAssignedNetAddressResponse',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'responseCode' => array('name' => 'responseCode', 'type' => 'xsd:long'),
		'replyMessage' => array('name' => 'replyMessage', 'type' => 'xsd:string'),
		'netAddresses' => array('name' => 'netAddresses', 'type' => 'tns:NetAssignedArray')));
$server->register(
	'wsGetAssignedNetAddress',
	array('param' => 'tns:wsGetAssignedNetAddressRequest'),
	array('param' => 'tns:wsGetAssignedNetAddressResponse'),
	$namespace,
	'urn:subscriberapiwsdl#wsGetAssignedNetAddress',
	'rpc',
	'encoded',
	'Extract all assigned multi-static IP addresses');
/**************************************************
 * functions
 **************************************************/
function wsCreateAccount($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsCreateAccount';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now           = date('Y-m-d H:i:s', time());
	$username      = trim($param['username']);
	$password      = trim($param['password']);
	$customerType  = trim($param['customerType']);
	$status        = trim($param['status']);
	$customerName  = trim($param['customerName']);
	$orderNumber   = trim($param['orderNumber']);
	$serviceNumber = trim($param['serviceNumber']);
	$plan          = str_replace('~', '-', trim($param['plan']));
	$ipAddress     = trim($param['ipAddress']);
	$ipv6Address   = trim($param['ipv6Address']);
	$netAddress    = trim($param['netAddress']);
	$nasLocation   = trim($param['nasLocation']);
	$remarks       = trim($param['remarks']);
	$returnObj = array(
		'responseCode' => F_SUBSCRIBER_CREATED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file. {username: '.$username.'}');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' respopnse to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM database connection
	 **************************************************/
	if( RMENABLED ){
		if (!$config['useAaaForPlans']) {
			$rmConn = oci_connect($config['primaryRmDbUsername'], $config['primaryRmDbPassword'], $config['primaryRmDbUrl']);
			if ($rmConn === false) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_DB_CONNECT_ERROR.'] No connection to RM database. {username: '.$username.'}');
				$returnObj['responseCode'] = F_RM_DB_CONNECT_ERROR;
				$returnObj['replyMessage'] = 'RM error occurred';
				return $returnObj;
			}
		} else {
			$rmConn = false;
		}
	} else {
		$rmConn = false;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database. {username: '.$username.'}');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM client
	 **************************************************/
	if( RMENABLED ){
		try {
			$rmApiClient = new SoapClient('http://'.$config['primaryRmUrl']);
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryRmUrl']);
			$returnObj['responseCode'] = F_RM_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'RM Error Occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * get radiuspolicy names
	 **************************************************/
	$planNamesTmp = Aaa::getPlanNames($aaaConn, $rmConn, $config['useAaaForPlans']);
	if (!$planNamesTmp['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_PLAN_LIST_FETCH_ERROR.'] Error getting plan names: '.$planNamesTmp['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_PLAN_LIST_FETCH_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_PLAN_LIST_FETCH_ERROR;
		$returnObj['replyMessage'] = ($config['useAaaForPlans'] ? 'AAA' : 'RM').' database error occurred';
		return $returnObj;
	}
	$planNames = $planNamesTmp['data'];
	unset($planNamesTmp);
	/**************************************************
	 * get plan boosts
	 **************************************************/
	$planBoostsTmp = Aaa::getSpeedBoosts($aaaConn);
	if (!$planBoostsTmp['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_BOOST_LIST_FETCH_ERROR.'] Error getting speed boosts: '.$planBoostsTmp['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_BOOST_LIST_FETCH_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_BOOST_LIST_FETCH_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$planBoosts = $planBoostsTmp['data'];
	unset($planBoostsTmp);
	/**************************************************
	 * get realms
	 **************************************************/
	$realmsTmp = Aaa::getRealms($aaaConn);
	if (!$realmsTmp['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REALM_LIST_FETCH_ERROR.'] Error getting realms: '.$realmsTmp['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REALM_LIST_FETCH_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REALM_LIST_FETCH_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$realms = $realmsTmp['data'];
	unset($realmsTmp);
	/**************************************************
	 * input-specific conditions: username (required)
	 **************************************************/
	if ($username == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Required values are needed';
		return $returnObj;
	}
	$isUsernameValid = true;
	// check for uppercase characters
	$usernameHasUppercase = preg_match('/[A-Z]{1}/', $username);
	if ($usernameHasUppercase) {
		$isUsernameValid = false;
	}
	// check for non-alphanumeric characters
	$usernameHasSpecialChars = preg_match('/[^a-zA-Z0-9.@_-]/', $username);
	if ($usernameHasSpecialChars) {
		$isUsernameValid = false;
	}
	if (!$isUsernameValid) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_USERNAME_HAS_DISALLOWED_CHARACTERS.'] Invalid username. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_USERNAME_HAS_DISALLOWED_CHARACTERS);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_INVALID_USERNAME_HAS_DISALLOWED_CHARACTERS;
		$returnObj['replyMessage'] = 'Invalid username';
		return $returnObj;
	}
	// check for username length
	if (strlen($username) > Aaa::$USERNAME_MAX_LENGTH) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CHARACTER_LIMIT_EXCEEDED.'] Maximum characters exceeded: username. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_CHARACTER_LIMIT_EXCEEDED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_CHARACTER_LIMIT_EXCEEDED;
		$returnObj['replyMessage'] = 'Maximum characters exceeded';
		return $returnObj;
	}
	// check if a realm is included
	$planParts = explode('@', $username);
	if (!isset($planParts[1])) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_USERNAME_NO_REALM.'] Username has no realm. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_USERNAME_NO_REALM);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_INVALID_USERNAME_NO_REALM;
		$returnObj['replyMessage'] = 'Invalid username';
		return $returnObj;
	}
	$realmFound = in_array($planParts[1], $realms);
	if (!$realmFound) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_USERNAME_UNLISTED_REALM.'] Username has invalid realm. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_USERNAME_UNLISTED_REALM);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_INVALID_USERNAME_UNLISTED_REALM;
		$returnObj['replyMessage'] = 'Invalid username';
		return $returnObj;
	}
	// check for username uniqueness
	$account = Aaa::getSubscriberWithUsername($aaaConn, $username);
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
	if ($account['data'] !== false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_ALREADY_USED.'] Username already exists. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_ALREADY_USED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_ALREADY_USED;
		$returnObj ['replyMessage'] = 'Username already exists';
		return $returnObj;
	}
	/**************************************************
	 * input-specific conditions: password (required)
	 **************************************************/
	if ($password == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: password. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Required values are needed';
		return $returnObj;
	}
	// check for password length
	if (strlen($password) > Aaa::$PASSWORD_MAX_LENGTH) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CHARACTER_LIMIT_EXCEEDED.'] Maximum characters exceeded: password. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_CHARACTER_LIMIT_EXCEEDED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_CHARACTER_LIMIT_EXCEEDED;
		$returnObj['replyMessage'] = 'Maximum characters exceeded';
		return $returnObj;
	}
	/**************************************************
	 * input-specific conditions: customerType (optional)
	 * -case insensitive
	 **************************************************/
	if ($customerType == '') {
		$customerType = 'Residential';
	} else {
		// check for allowed customer types
		if (!in_array(strtoupper($customerType), Aaa::$ALLOWED_CUSTOMER_TYPE_VALUES)) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_CUSTOMER_TYPE.'] Invalid customer type value. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_CUSTOMER_TYPE);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_INVALID_CUSTOMER_TYPE;
			$returnObj['replyMessage'] = 'Invalid customer type value';
			return $returnObj;
		}
		$customerType = strtoupper(substr($customerType, 0, 1)).strtolower(substr($customerType, 1, strlen($customerType)));
	}
	/**************************************************
	 * input-specific conditions: status (required)
	 * - case insensitive
	 **************************************************/
	if ($status == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: status. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Required values are needed';
		return $returnObj;
	}
	// check for allowed customer status
	if (!in_array(strtoupper($status), Aaa::$ALLOWED_CUSTOMER_STATUS_VALUES)) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_CUSTOMER_STATUS.'] Invalid customer status value. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_CUSTOMER_STATUS);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_INVALID_CUSTOMER_STATUS;
		$returnObj['replyMessage'] = 'Invalid customer status value';
		return $returnObj;
	}
	$status = strtoupper($status);
	/**************************************************
	 * input-specific conditions: customerName (optional)
	 **************************************************/
	if ($customerName != '') {
		// check for customer name length
		if (strlen($customerName) > Aaa::$CUSTOMER_NAME_MAX_LENGTH) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CHARACTER_LIMIT_EXCEEDED.'] Maximum characters exceeded: customerName. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_CHARACTER_LIMIT_EXCEEDED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_CHARACTER_LIMIT_EXCEEDED;
			$returnObj['replyMessage'] = 'Maximum characters exceeded';
			return $returnObj;
		}
	} else {
		$customerName = null;
	}
	/**************************************************
	 * input-specific conditions: orderNumber (optional)
	 * - set to null if not provided
	 **************************************************/
	if ($orderNumber == '') {
		$orderNumber = null;
	}
	/**************************************************
	 * input-specific conditions: serviceNumber (required)
	 **************************************************/
	if ($serviceNumber == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: serviceNumber. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Required values are needed';
		return $returnObj;
	}
	// check for serviceNumber uniqueness
	$account = Aaa::getSubscriberWithServiceNumber($aaaConn, $serviceNumber);
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
	if ($account['data'] !== false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SERVICE_NUMBER_ALREADY_USED.'] Service number already owned by other account. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_SERVICE_NUMBER_ALREADY_USED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_SERVICE_NUMBER_ALREADY_USED;
		$returnObj['replyMessage'] = 'Service number already owned by other account';
		return $returnObj;
	}
	/**************************************************
	 * input-specific conditions: plan (required)
	 * - vod not allowed
	 **************************************************/
	if ($plan == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: plan. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Required values are needed';
		return $returnObj;
	}
	// separate plan from vod part (if any)
	$vodVariables = Aaa::separateVodFromPlan($plan);
	$planHasVod = $vodVariables['hasVod'];
	$planVodValue = $vodVariables['vodValue'];
	$planCleaned = $vodVariables['find'];
	if ($planHasVod) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_PLAN_HAS_VOD.'] Plan has vod value ('.$planVodValue.')');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_PLAN_HAS_VOD);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_PLAN_HAS_VOD;
		$returnObj['replyMessage'] = 'Plan does not exist';
		return $returnObj;
	}
	// reorder boosts (if any)
	$fixPlanVariables = Aaa::fixPlan($planCleaned, $planNames, $planBoosts);
	$foundPlan = $fixPlanVariables['found'];
	$fixedPlan = $fixPlanVariables['fixed'];
	if (!$foundPlan) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNLISTED_PLAN.'] Plan does not exist. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNLISTED_PLAN);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_UNLISTED_PLAN;
		$returnObj['replyMessage'] = 'Plan does not exist';
		return $returnObj;
	}
	/**************************************************
	 * input-specific conditions: nasLocation (optional)
	 **************************************************/
	if ($nasLocation != '') {
		// check for allowed location
		if (!in_array(strtoupper($nasLocation), Aaa::$ALLOWED_NAS_LOCATION_VALUES)) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_LOCATION.'] Invalid location. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_LOCATION);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_INVALID_LOCATION;
			$returnObj['replyMessage'] = 'Invalid location';
			return $returnObj;
		}
		$nasLocation = strtoupper($nasLocation);
	} else {
		$nasLocation = null;
	}
	/**************************************************
	 * input-specific conditions: remarks (optional)
	 **************************************************/
	if ($remarks != '') {
		// check for remarks length
		if (strlen($remarks) > Aaa::$REMARKS_MAX_LENGTH) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CHARACTER_LIMIT_EXCEEDED.'] Maximum characters exceeded: remarks. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_CHARACTER_LIMIT_EXCEEDED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_CHARACTER_LIMIT_EXCEEDED;
			$returnObj['replyMessage'] = 'Maximum characters exceeded';
			return $returnObj;
		}
	} else {
		$remarks = null;
	}
	/**************************************************
	 * input-specific conditions: ipv6Address (optional)
	 * - set to null if not provided
	 **************************************************/
	$ipv6AddressMarked = false;
	if ($ipv6Address != '') {
		// check for ipv6 address existence
		$ipv6Obj = Aaa::getIPv6Address($aaaConn, $ipv6Address);
		if (!$ipv6Obj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$ipv6Obj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		if ($ipv6Obj['data'] === false) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNLISTED_IPV6_ADDRESS.'] IPv6 address does not exist. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNLISTED_IPV6_ADDRESS);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_UNLISTED_IPV6_ADDRESS;
			$returnObj['replyMessage'] = 'IPv6 address does not exist';
			return $returnObj;
		}
		// check for ipv6 address availability
		if ($ipv6Obj['data']['IPV6USED'] == 'Y') {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.' ['.F_UNAVAILABLE_IPV6_ADDRESS.'] IPv6 address is not available. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNAVAILABLE_IPV6_ADDRESS);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_UNAVAILABLE_IPV6_ADDRESS;
			$returnObj['replyMessage'] = 'IPv6 address is not available';
			return $returnObj;
		}
		$mark = Aaa::markIPv6Address($aaaConn, $ipv6Address, true, $username, $status);
		if (!$mark['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$mark['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked as used: '.$ipv6Address);
		$ipv6AddressMarked = true;
	} else {
		$ipv6Address = null;
	}
	/**************************************************
	 * input-specific conditions: ipAddress (optional)
	 **************************************************/
	if ($ipAddress == '') {
		$ipAddress = null;
	}
	$ipAddressMarked = false;
	if (!is_null($ipAddress)) {
		// check for ip address format correctness
		if (filter_var($ipAddress, FILTER_VALIDATE_IP) === false) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_IP_ADDRESS.'] Invalid IP address. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_IP_ADDRESS);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_INVALID_IP_ADDRESS;
			$returnObj['replyMessage'] = 'IP address does not exist';
			// revert marked ipv6 address (if any)
			if (!is_null($ipv6Address) && $ipv6AddressMarked) {
				$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
				}
			}
			return $returnObj;
		}
		// check for ip address existence
		$ipObj = Aaa::getIPAddress($aaaConn, $ipAddress);
		if (!$ipObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$ipObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			// revert marked ipv6 address (if any)
			if (!is_null($ipv6Address) && $ipv6AddressMarked) {
				$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
				}
			}
			return $returnObj;
		}
		if ($ipObj['data'] === false) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNLISTED_IP_ADDRESS.'] IP address does not exist. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNLISTED_IP_ADDRESS);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_UNLISTED_IP_ADDRESS;
			$returnObj['replyMessage'] = 'IP address does not exist';
			// revert marked ipv6 address (if any)
			if (!is_null($ipv6Address) && $ipv6AddressMarked) {
				$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
				}
			}
			return $returnObj;
		}
		// check for ip address availability
		if ($ipObj['data']['IPUSED'] == 'Y') {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNAVAILABLE_IP_ADDRESS.'] IP address is not available. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNAVAILABLE_IP_ADDRESS);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_UNAVAILABLE_IP_ADDRESS;
			$returnObj['replyMessage'] = 'IP address is not available';
			// revert marked ipv6 address (if any)
			if (!is_null($ipv6Address) && $ipv6AddressMarked) {
				$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
				}
			}
			return $returnObj;
		}
		$mark = Aaa::markIPAddress($aaaConn, $ipAddress, true, $username, $status);
		if (!$mark['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$mark['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			// revert marked ipv6 address (if any)
			if (!is_null($ipv6Address) && $ipv6AddressMarked) {
				$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
				}
			}
			return $returnObj;
		}
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked as used: '.$ipAddress);
		$ipAddressMarked = true;
	}
	/**************************************************
	 * input-specific conditions: netAddress (optional)
	 **************************************************/
	if ($netAddress == '') {
		$netAddress = null;
	}
	$netAddressMarked = false;
	if (!is_null($netAddress)) {
		if (is_null($ipAddress)) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_HAS_NO_STATIC_IP_ADDRESS.'] Account has no static IP address. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_HAS_NO_STATIC_IP_ADDRESS);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_HAS_NO_STATIC_IP_ADDRESS;
			$returnObj['replyMessage'] = 'IP address is required';
			// revert marked ipv6 address (if any)
			if (!is_null($ipv6Address) && $ipv6AddressMarked) {
				$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
				}
			}
			return $returnObj;
		}
		// check for net address format correctness
		$netAddressParts = explode('/', $netAddress);
		if (count($netAddressParts) != 2 || filter_var($netAddressParts[0], FILTER_VALIDATE_IP) === false) {
			// revert marked ipv6 address (if any)
			if (!is_null($ipAddress) && $ipAddressMarked) {
				$unmark = Aaa::markIPAddress($aaaConn, $ipAddress, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipAddress);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipAddress);
				}
			}
			// revert marked ip address (if any)
			if (!is_null($ipv6Address) && $ipv6AddressMarked) {
				$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
				}
			}
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_NET_ADDRESS.'] Multi-static IP address does not exist (invalid net address). {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_NET_ADDRESS);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_INVALID_NET_ADDRESS;
			$returnObj['replyMessage'] = 'Multi-static IP address does not exist';
			return $returnObj;
		}
		// check for net address existence
		$netObj = Aaa::getNetAddress($aaaConn, $netAddress);
		if (!$netObj['result']) {
			// revert marked ip address (if any)
			if (!is_null($ipAddress) && $ipAddressMarked) {
				$unmark = Aaa::markIPAddress($aaaConn, $ipAddress, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipAddress);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipAddress);
				}
			}
			// revert marked ipv6 address (if any)
			if (!is_null($ipv6Address) && $ipv6AddressMarked) {
				$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
				}
			}
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$netObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		if ($netObj['data'] === false) {
			// revert marked ip address (if any)
			if (!is_null($ipAddress) && $ipAddressMarked) {
				$unmark = Aaa::markIPAddress($aaaConn, $ipAddress, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipAddress);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipAddress);
				}
			}
			// revert marked ipv6 address (if any)
			if (!is_null($ipv6Address) && $ipv6AddressMarked) {
				$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
				}
			}
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNLISTED_NET_ADDRESS.'] Multi-static IP address does not exist. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNLISTED_NET_ADDRESS);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_UNLISTED_NET_ADDRESS;
			$returnObj['replyMessage'] = 'Multi-static IP address does not exist';
			return $returnObj;
		}
		// check for net address availability
		if ($netObj['data']['NETUSED'] == 'Y') {
			// revert marked ip address (if any)
			if (!is_null($ipAddress) && $ipAddressMarked) {
				$unmark = Aaa::markIPAddress($aaaConn, $ipAddress, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipAddress);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipAddress);
				}
			}
			// revert marked ipv6 address (if any)
			if (!is_null($ipv6Address) && $ipv6AddressMarked) {
				$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
				}
			}
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNAVAILABLE_NET_ADDRESS.'] Multi-static IP address is not available. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNAVAILABLE_NET_ADDRESS);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_UNAVAILABLE_NET_ADDRESS;
			$returnObj['replyMessage'] = 'Multi-static IP address is not available';
			return $returnObj;
		}
		$mark = Aaa::markNetAddress($aaaConn, $netAddress, true, $username, $status);
		if (!$mark['result']) {
			// revert marked ip address (if any)
			if (!is_null($ipAddress) && $ipAddressMarked) {
				$unmark = Aaa::markIPAddress($aaaConn, $ipAddress, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipAddress);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipAddress);
				}
			}
			// revert marked ipv6 address (if any)
			if (!is_null($ipv6Address) && $ipv6AddressMarked) {
				$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
				}
			}
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$mark['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked as used: '.$netAddress);
		$netAddressMarked = true;
	}


	/**************************************************
	 * insert into AAA
	 **************************************************/
	$customerReplyItem = Aaa::generateCustomerReplyItemValue($ipv6Address, $ipAddress, $netAddress);
	$aaaInsert = Aaa::createSubscriber($aaaConn, $username, $password, $status, $serviceNumber, $fixedPlan, $customerType,
		$customerName, $orderNumber, $ipv6Address, $ipAddress, $netAddress, $remarks, $customerReplyItem);
	// aaa insert failed
	if (!$aaaInsert['result']) {
		// revert marked ip address (if any)
		if (!is_null($ipAddress) && $ipAddressMarked) {
			$unmark = Aaa::markIPAddress($aaaConn, $ipAddress, false, $username, $status);
			if (!$unmark['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipAddress);
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipAddress);
			}
		}
		// revert marked net address (if any)
		if (!is_null($netAddress) && $netAddressMarked) {
			$unmark = Aaa::markNetAddress($aaaConn, $netAddress, false, $username, $status);
			if (!$unmark['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$netAddress);
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$netAddress);
			}
		}
		// revert marked ipv6 address (if any)
		if (!is_null($ipv6Address) && $ipv6AddressMarked) {
			$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
			if (!$unmark['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
			}
		}
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$aaaInsert['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	// aaa insert successful
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Inserted at AAA');
	/**************************************************
	 * figure out X in #LX (for migrating)
	 **************************************************/
	$usernameExtra = '';
	if (!is_null($ipAddress) && $ipAddressMarked) {
		// migrating when ip address given (not necessary, as of 03-02-2017)
		/*
		$locationObj = Aaa::getLocationObjWithLocationString($mysqlConn, $ipObj['data']['LOCATION']);
		if ($locationObj['result'] && !empty($locationObj['data'])) {
			$usernameExtra = $locationObj['data']['rm_location'];
		}
		*/
	} else {
		if (!is_null($nasLocation)) {
			$locationObj = Aaa::getLocationObjWithNasCode($mysqlConn, $nasLocation);
			if ($locationObj['result'] && !empty($locationObj['data'])) {
				$usernameExtra = $locationObj['data'][0]['rm_location'];
			}
		}
	}
	/**************************************************
	 * insert into RM
	 **************************************************/
	if( RMENABLED ){
		$rmUsername = $username;
		$rmPlan = $fixedPlan;
		$rmStatus = 'ACTIVE';
		$rmCustomerType = $customerType == 'Residential' ? 'RESS' : 'BUSS';
		$rmParam3 = is_null($netAddress) ? 'Y' : 'N';
		$rmNodes = array('PARAM3' => $rmParam3, 'AREA' => ($status == 'A' ? 'Active' : 'InActive'));
		$rmInsert = Rm::wsAddSubscriber($rmApiClient, $rmUsername, $rmPlan, $rmNodes, $rmStatus, $rmCustomerType);


		// rm insert successful

		if (intval($rmInsert['responseCode']) == 200) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SUBSCRIBER_CREATED.'] Inserted at RM. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_SUBSCRIBER_CREATED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			// migrate if $usernameExtra != ''
			if ($usernameExtra != '') {
				$rmMigrate = Rm::wsMigrateSubscriber($rmApiClient, $rmUsername, $rmUsername.$usernameExtra);
				if (intval($rmMigrate['responseCode']) == 200) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", $rmUsername.' migrated to '.$rmUsername.$usernameExtra);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to migrate to '.$rmUsername.$usernameExtra.'|'.$rmMigrate['responseCode'].'|'.$rmMigrate['responseMessage']);
				}
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Will not migrate account');
			}
			return $returnObj;
		// subscriber identity exists, update instead
		} else if (intval($rmInsert['responseCode']) == 450) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updating at RM');
			$rmUpdate = Rm::wsUpdateSubscriberProfile($rmApiClient, $rmUsername, $rmPlan, $rmNodes, $rmStatus, $rmCustomerType);
			// rm update successful
			if (intval($rmUpdate['responseCode']) == 200) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SUBSCRIBER_CREATED.'] Updated at RM. {username: '.$username.'}');
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_SUBSCRIBER_CREATED);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				return $returnObj;
			// subscriber identity duplicate error: delete/purge all variants then re-insert
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Delete/purge, then re-insert|'.$rmUpdate['responseCode'].'|'.$rmUpdate['responseMessage']);
				// deleting/purging all variants of #LX (X -> 1 to 3), including no #LX
				for ($i = 0; $i < 4; $i++) {
					if ($i == 0) {
						$useThisUsername = $username;
						$rmDelete = Rm::wsDeleteSubscriberProfile($rmApiClient, $useThisUsername);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "Delete (alternateId) result for ".$useThisUsername.": ".$rmDelete['responseCode'].'|'.$rmDelete['responseMessage']);
						$rmPurge = Rm::wsPurgeSubscriber($rmApiClient, $useThisUsername);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "Purge (alternateId) result for ".$useThisUsername.": ".$rmPurge['responseCode'].'|'.$rmPurge['responseMessage']);
						$rmDelete = Rm::wsDeleteSubscriberProfileV2($rmApiClient, $useThisUsername);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "Delete (subscriberID) result for ".$useThisUsername.": ".$rmDelete['responseCode'].'|'.$rmDelete['responseMessage']);
						$rmPurge = Rm::wsPurgeSubscriberV2($rmApiClient, $useThisUsername);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "Purge (subscriberID) result for ".$useThisUsername.": ".$rmPurge['responseCode'].'|'.$rmPurge['responseMessage']);
					} else {
						$useThisUsername = $username.'#L'.$z;
						$rmDelete = Rm::wsDeleteSubscriberProfileV2($rmApiClient, $useThisUsername);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "Delete (subscriberID) result for ".$useThisUsername.": ".$rmDelete['responseCode'].'|'.$rmDelete['responseMessage']);
						$rmPurge = Rm::wsPurgeSubscriberV2($rmApiClient, $useThisUsername);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "Purge (subscriberID) result for ".$useThisUsername.": ".$rmPurge['responseCode'].'|'.$rmPurge['responseMessage']);
					}
				}
				// retry rm insert
				$rmInsert = Rm::wsAddSubscriber($rmApiClient, $rmUsername, $rmPlan, $rmNodes, $rmStatus, $rmCustomerType);
				// insert retry success
				if (intval($rmInsert['responseCode']) == 200) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SUBSCRIBER_CREATED.'] Second insert attempt successful. {username: '.$username.'}');
					$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_SUBSCRIBER_CREATED);
					if (!$lrq['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
					}
					return $returnObj;
				// insert retry failed
				} else {
					// revert AAA insert
					$aaaDelete = Aaa::deleteSubscriber($aaaConn, $username);
					if (!$aaaDelete['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$aaaDelete['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to delete '.$username);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Reverted insert to '.$username);
					}
					// revert marked ip address (if any)
					if (!is_null($ipAddress) && $ipAddressMarked) {
						$unmark = Aaa::markIPAddress($aaaConn, $ipAddress, false, $username, $status);
						if (!$unmark['result']) {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipAddress);
						} else {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipAddress);
						}
					}
					// revert marked net address (if any)
					if (!is_null($netAddress) && $netAddressMarked) {
						$unmark = Aaa::markNetAddress($aaaConn, $netAddress, false, $username, $status);
						if (!$unmark['result']) {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$netAddress);
						} else {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$netAddress);
						}
					}
					// revert marked ipv6 address (if any)
					if (!is_null($ipv6Address) && $ipv6AddressMarked) {
						$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
						if (!$unmark['result']) {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
						} else {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
						}
					}
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_GENERIC_RM_ERROR.'] Second insert attempt failed: '.
						$rmInsert['responseCode'].'|'.$rmInsert['responseMessage']);
					$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_GENERIC_RM_ERROR);
					if (!$lrq['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
					}
					$returnObj['responseCode'] = F_GENERIC_RM_ERROR;
					$returnObj['replyMessage'] = 'RM error occurred';
					return $returnObj;
				}
			}
		// other response codes returned by original insert attempt
		} else {
			$fault = $rmInsert['responseMessage'];
			// special case for RM.SYS_C0015045 internal error
			$toFind1 = 'ORA-00001';
			$toFind2 = 'RM.SYS_C0015045';
			$found1 = strpos($fault, $toFind1);
			$found2 = strpos($fault, $toFind2);
			if ($found1 && $found2) {
				$rmUpdate = Rm::wsUpdateSubscriberProfile($rmApiClient, $rmUsername, $rmPlan, $rmNodes, $rmStatus, $rmCustomerType);
				// rm update for special case successful
				if (intval($rmUpdate['responseCode']) == 200) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SUBSCRIBER_CREATED.'] Updated account after unique constraint INTERNAL ERROR. {username: '.$username.'}');
					$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_SUBSCRIBER_CREATED);
					if (!$lrq['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
					}
					return $returnObj;
				// rm update for special case failed
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_GENERIC_RM_ERROR.'] Error at RM: '.
						$rmUpdate['responseCode'].'|'.$rmUpdate['responseMessage']);
					$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_GENERIC_RM_ERROR);
					if (!$lrq['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
					}
					// revert marked ip address (if any)
					if (!is_null($ipAddress) && $ipAddressMarked) {
						$unmark = Aaa::markIPAddress($aaaConn, $ipAddress, false, $username, $status);
						if (!$unmark['result']) {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipAddress);
						} else {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipAddress);
						}
					}
					// revert marked net address (if any)
					if (!is_null($netAddress) && $netAddressMarked) {
						$unmark = Aaa::markNetAddress($aaaConn, $netAddress, false, $username, $status);
						if (!$unmark['result']) {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$netAddress);
						} else {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$netAddress);
						}
					}
					// revert marked ipv6 address (if any)
					if (!is_null($ipv6Address) && $ipv6AddressMarked) {
						$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
						if (!$unmark['result']) {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
						} else {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
						}
					}
					$returnObj['responseCode'] = 501;
					$returnObj['replyMessage'] = 'RM error occurred';
					return $returnObj;
				}
			// accounts for other response codes returned by original insert
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_GENERIC_RM_ERROR.'] Error at RM: '.
					$rmInsert['responseCode'].'|'.$rmInsert['responseMessage']);
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_GENERIC_RM_ERROR);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				// revert AAA insert
				$aaaDelete = Aaa::deleteSubscriber($aaaConn, $username);
				if (!$aaaDelete['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$aaaDelete['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to delete '.$username);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Reverted insert to '.$username);
				}
				// revert marked ip address (if any)
				if (!is_null($ipAddress) && $ipAddressMarked) {
					$unmark = Aaa::markIPAddress($aaaConn, $ipAddress, false, $username, $status);
					if (!$unmark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipAddress);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipAddress);
					}
				}
				// revert marked net address (if any)
				if (!is_null($netAddress) && $netAddressMarked) {
					$unmark = Aaa::markNetAddress($aaaConn, $netAddress, false, $username, $status);
					if (!$unmark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$netAddress);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$netAddress);
					}
				}
				// revert marked ipv6 address (if any)
				if (!is_null($ipv6Address) && $ipv6AddressMarked) {
					$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
					if (!$unmark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
					}
				}
				$returnObj['responseCode'] = 501;
				$returnObj['replyMessage'] = 'RM error occurred';
				return $returnObj;
			}
		}
	} else {
		return $returnObj;
	}
}
function wsModifyAccount($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsModifyAccount';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$username      = trim($param['username']);
	$password      = ltrim($param['password']);
	$customerType  = ltrim($param['customerType']);
	$status        = ltrim($param['status']);
	$customerName  = ltrim($param['customerName']);
	$orderNumber   = ltrim($param['orderNumber']);
	$serviceNumber = ltrim($param['serviceNumber']);
	$plan          = str_replace('~', '-', ltrim($param['plan']));
	$ipAddress     = ltrim($param['ipAddress']);
	$ipv6Address   = ltrim($param['ipv6Address']);
	$netAddress    = ltrim($param['netAddress']);
	$nasLocation   = ltrim($param['nasLocation']);
	$remarks       = ltrim($param['remarks']);
	$returnObj = array(
		'responseCode' => F_SUBSCRIBER_UPDATED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file. {username: '.$username.'}');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$aaaConn2 = oci_connect($config['secondarySessionUsername'], $config['secondarySessionPassword'], $config['secondarySessionUrl']);
	if ($aaaConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to AAA (secondary) database. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA (secondary) database error occurred';
		return $returnObj;
	}
	$tblmConcConn = oci_connect($config['primaryMconcurrentUsername'], $config['primaryMconcurrentPassword'], $config['primaryMconcurrentUrl']);
	if ($tblmConcConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCONCURRENTUSERS table. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmConcConn2 = oci_connect($config['secondaryMconcurrentUsername'], $config['secondaryMconcurrentPassword'], $config['secondaryMconcurrentUrl']);
	if ($tblmConcConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCONCURRENTUSERS table. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn = oci_connect($config['primaryMcoreUsername'], $config['primaryMcorePassword'], $config['primaryMcoreUrl']);
	if ($tblmCoreConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCORESESSIONS table. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn2 = oci_connect($config['secondaryMcoreUsername'], $config['secondaryMcorePassword'], $config['secondaryMcoreUrl']);
	if ($tblmCoreConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCORESESSIONS table. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM database connection
	 **************************************************/
	if(RMENABLED){
		if (!$config['useAaaForPlans']) {
			$rmConn = oci_connect($config['primaryRmDbUsername'], $config['primaryRmDbPassword'], $config['primaryRmDbUrl']);
			if ($rmConn === false) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_DB_CONNECT_ERROR.'] No connection to RM database. {username: '.$username.'}');
				$returnObj['responseCode'] = F_RM_DB_CONNECT_ERROR;
				$returnObj['replyMessage'] = 'RM error occurred';
				return $returnObj;
			}
		} else {
			$rmConn = false;
		}
	} else {
		$rmConn = false;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database. {username: '.$username.'}');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM client
	 **************************************************/
	if(RMENABLED){
		try {
			$rmApiClient = new SoapClient('http://'.$config['primaryRmUrl'])	;
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryRmUrl']);
			$returnObj['responseCode'] = F_RM_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'RM Error Occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * delete session client
	 **************************************************/
	try {
		$deleteSessionClient = new SoapClient('http://'.$config['primaryDeleteSessionUrl']);
	} catch (Exception $e) {
		$error = json_encode($e);
		$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		$end = strpos($error, '","faultcode"');
		$err = substr($error, $start, $end - $start);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryDeleteSessionUrl']);
		$returnObj['responseCode'] = F_SESSION_API_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Delete session client connection error occurred';
		return $returnObj;
	}
	if ($config['useSecondary']) {
		try {
			$deleteSessionClientSecondary = new SoapClient('http://'.$config['secondaryDeleteSessionUrl']);
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_SECONDARY_API_CONNECT_ERROR.'] Unable to connect to '.
				$config['secondaryDeleteSessionUrl']);
			$returnObj['responseCode'] = F_SESSION_SECONDARY_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'Delete session client connection (secondary) error occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * get radiuspolicy names
	 **************************************************/
	$planNamesTmp = Aaa::getPlanNames($aaaConn, $rmConn, $config['useAaaForPlans']);
	if (!$planNamesTmp['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_PLAN_LIST_FETCH_ERROR.'] Error getting plan names: '.$planNamesTmp['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_PLAN_LIST_FETCH_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_PLAN_LIST_FETCH_ERROR;
		$returnObj['replyMessage'] = ($config['useAaaForPlans'] ? 'AAA' : 'RM').' database error occurred';
		return $returnObj;
	}
	$planNames = $planNamesTmp['data'];
	unset($planNamesTmp);
	/**************************************************
	 * get plan boosts
	 **************************************************/
	$planBoostsTmp = Aaa::getSpeedBoosts($aaaConn);
	if (!$planBoostsTmp['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_BOOST_LIST_FETCH_ERROR.'] Error getting speed boosts: '.$planBoostsTmp['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_BOOST_LIST_FETCH_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_BOOST_LIST_FETCH_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$planBoosts = $planBoostsTmp['data'];
	unset($planBoostsTmp);
	/**************************************************
	 * get realms
	 **************************************************/
	$realmsTmp = Aaa::getRealms($aaaConn);
	if (!$realmsTmp['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REALM_LIST_FETCH_ERROR.'] Error getting realms: '.$realmsTmp['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REALM_LIST_FETCH_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REALM_LIST_FETCH_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$realms = $realmsTmp['data'];
	unset($realmsTmp);
	/**************************************************
	 * input-specific conditions: username (required)
	 **************************************************/
	if ($username == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Required values are needed';
		return $returnObj;
	}
	/*
	$isUsernameValid = true;
	$usernameHasUppercase = preg_match('/[A-Z]{1}/', $username);
	if ($usernameHasUppercase) {
		$isUsernameValid = false;
	}
	$usernameHasSpecialChars = preg_match('/[^a-zA-Z0-9.@_-]/', $username);
	if ($usernameHasSpecialChars) {
		$isUsernameValid = false;
	}
	if (!$isUsernameValid) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_USERNAME_HAS_DISALLOWED_CHARACTERS.'] Invalid username');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_USERNAME_HAS_DISALLOWED_CHARACTERS);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_INVALID_USERNAME_HAS_DISALLOWED_CHARACTERS;
		$returnObj['replyMessage'] = 'Invalid username';
		return $returnObj;
	}
	*/
	// check for username length
	if (strlen($username) > Aaa::$USERNAME_MAX_LENGTH) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CHARACTER_LIMIT_EXCEEDED.'] Maximum characters exceeded: username');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_CHARACTER_LIMIT_EXCEEDED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_CHARACTER_LIMIT_EXCEEDED;
		$returnObj['replyMessage'] = 'Maximum characters exceeded';
		return $returnObj;
	}
	/*
	$planParts = explode('@', $username);
	if (!isset($planParts[1])) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_USERNAME_NO_REALM.'] Username has no realm');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_USERNAME_NO_REALM);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_INVALID_USERNAME_NO_REALM;
		$returnObj['replyMessage'] = 'Invalid username';
		return $returnObj;
	}
	$realmFound = in_array($planParts[1], $realms);
	if (!$realmFound) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_USERNAME_UNLISTED_REALM.'] Username has invalid realm');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_USERNAME_UNLISTED_REALM);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_INVALID_USERNAME_UNLISTED_REALM;
		$returnObj['replyMessage'] = 'Invalid username';
		return $returnObj;
	}
	*/
	// check for username existence
	$accountOld = Aaa::getSubscriberWithUsername($aaaConn, $username);
	if (!$accountOld['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$accountOld['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	if ($accountOld['data'] === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj ['replyMessage'] = 'Username not found';
		return $returnObj;
	}
	/**************************************************
	 * input-specific conditions: password (required)
	 **************************************************/
	if ($password == '') {
		/* REMOVED 11082017
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: password. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Required values are needed';
		return $returnObj;
		*/
		$password = $accountOld['data']['PASSWORD'];
	}

	// check for password length
	if (strlen($password) > Aaa::$PASSWORD_MAX_LENGTH) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CHARACTER_LIMIT_EXCEEDED.'] Maximum characters exceeded: password. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_CHARACTER_LIMIT_EXCEEDED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_CHARACTER_LIMIT_EXCEEDED;
		$returnObj['replyMessage'] = 'Maximum characters exceeded';
		return $returnObj;
	}
	/**************************************************
	 * input-specific conditions: customerType (optional)
	 * - case insensitive
	 **************************************************/
	if ($customerType == '') {
		//$customerType = 'Residential';
		$customerType = $accountOld['data']['CUSTOMERTYPE'];
	} else {
		// check for allowed customer type
		if (!in_array(strtoupper($customerType), Aaa::$ALLOWED_CUSTOMER_TYPE_VALUES)) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_CUSTOMER_TYPE.'] Invalid customer type value. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_CUSTOMER_TYPE);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_INVALID_CUSTOMER_TYPE;
			$returnObj['replyMessage'] = 'Invalid customer type value';
			return $returnObj;
		}
		$customerType = strtoupper(substr($customerType, 0, 1)).strtolower(substr($customerType, 1, strlen($customerType)));
	}
	/**************************************************
	 * input-specific conditions: status (required)
	 * -case insensitive
	 **************************************************/
	if ($status == '') {
		/* REMOVED 11082017
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: status. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Required values are needed';
		return $returnObj;
		*/
		$status = ( ($accountOld['data']['CUSTOMERSTATUS'] == "Active") ? "A" : "D" ) ;
	}

	// check for allowed status
	if (!in_array(strtoupper($status), Aaa::$ALLOWED_CUSTOMER_STATUS_VALUES)) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_CUSTOMER_STATUS.'] Invalid customer status value. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_CUSTOMER_STATUS);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_INVALID_CUSTOMER_STATUS;
		$returnObj['replyMessage'] = 'Invalid customer status value';
		return $returnObj;
	}
	$status = strtoupper($status);
	/**************************************************
	 * input-specific conditions: customerName (optional)
	 **************************************************/
	if ($customerName != '') {
		// check for customer name length
		if (strlen($customerName) > Aaa::$CUSTOMER_NAME_MAX_LENGTH) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CHARACTER_LIMIT_EXCEEDED.'] Maximum characters exceeded: customerName. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_CHARACTER_LIMIT_EXCEEDED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_CHARACTER_LIMIT_EXCEEDED;
			$returnObj['replyMessage'] = 'Maximum characters exceeded';
			return $returnObj;
		}
	} else {
		$oldCustomerName = $accountOld['data']['RBCUSTOMERNAME'];
		$customerName = is_null($oldCustomerName) || empty($oldCustomerName) || $oldCustomerName == '' ? null : $oldCustomerName;
	}
	/**************************************************
	 * input-specific conditions: orderNumber (optional)
	 * -set to null if not provided
	 **************************************************/
	if ($orderNumber == '') {
		$oldOrderNumber = $accountOld['data']['RBORDERNUMBER'];
		$orderNumber = is_null($oldOrderNumber) || empty($oldOrderNumber) || $oldOrderNumber == '' ? null : $oldOrderNumber;
	}
	/**************************************************
	 * input-specific conditions: serviceNumber (required)
	 **************************************************/
	if ($serviceNumber == '') {
		/* REMOVED 11082017
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: serviceNumber. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Required values are needed';
		return $returnObj;
		*/
		$serviceNumber = $accountOld['data']['RBSERVICENUMBER'];
	}

	// check for serviceNumber uniqueness (allowed if current serviceNumber)
	$account = Aaa::getSubscriberWithServiceNumber($aaaConn, $serviceNumber);
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
	if ($account['data'] !== false && $account['data']['USER_IDENTITY'] != $username) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SERVICE_NUMBER_ALREADY_USED.'] Service number already owned by other account. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_SERVICE_NUMBER_ALREADY_USED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_SERVICE_NUMBER_ALREADY_USED;
		$returnObj['replyMessage'] = 'Service number already owned by other account';
		return $returnObj;
	}
	/**************************************************
	 * input-specific conditions: plan (required)
	 **************************************************/
	if ($plan == '') {
		/* REMOVED 11082017
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: plan. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Required values are needed';
		return $returnObj;
		*/
		$plan = $accountOld['data']['RBACCOUNTPLAN'];
	}

	// separate plan from vod part (if any)
	$vodVariables = Aaa::separateVodFromPlan($plan);
	$planHasVod = $vodVariables['hasVod'];
	$planVodValue = $vodVariables['vodValue'];
	$planCleaned = $vodVariables['find'];
	// vod not allowed
	if ($planHasVod) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_PLAN_HAS_VOD.'] Plan has vod value ('.$planVodValue.')');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_PLAN_HAS_VOD);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_PLAN_HAS_VOD;
		$returnObj['replyMessage'] = 'Plan does not exist';
		return $returnObj;
	}
	// reorder boosts (if any)
	$fixPlanVariables = Aaa::fixPlan($planCleaned, $planNames, $planBoosts);
	$foundPlan = $fixPlanVariables['found'];
	$fixedPlan = $fixPlanVariables['fixed'];
	if (!$foundPlan) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNLISTED_PLAN.'] Plan does not exist. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNLISTED_PLAN);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_UNLISTED_PLAN;
		$returnObj['replyMessage'] = 'Plan does not exist';
		return $returnObj;
	}
	/**************************************************
	 * input-specific conditions: nasLocation (optional)
	 **************************************************/
	if ($nasLocation != '') {
		// check for allowed location
		if (!in_array(strtoupper($nasLocation), Aaa::$ALLOWED_NAS_LOCATION_VALUES)) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_LOCATION.'] Invalid location. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_LOCATION);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_INVALID_LOCATION;
			$returnObj['replyMessage'] = 'Invalid location';
			return $returnObj;
		}
		$nasLocation = strtoupper($nasLocation);
	} else {
		$nasLocation = null;
	}
	/**************************************************
	 * input-specific conditions: remarks (optional)
	 **************************************************/
	if ($remarks != '') {
		$remarks = $accountOld['data']['RBREMARKS'].';'.$remarks;
		// check for remarks (including appended part) length
		if (strlen($remarks) > Aaa::$REMARKS_MAX_LENGTH) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CHARACTER_LIMIT_EXCEEDED.'] Maximum characters exceeded: remarks. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_CHARACTER_LIMIT_EXCEEDED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_CHARACTER_LIMIT_EXCEEDED;
			$returnObj['replyMessage'] = 'Maximum characters exceeded';
			return $returnObj;
		}
	} else {
		$remarks = $accountOld['data']['RBREMARKS'];
	}
	/**************************************************
	 * input-specific conditions: ipv6Address (optional)
	 * - set to null if not provided
	 **************************************************/
	$oldIpv6Address = $accountOld['data']['RBADDITIONALSERVICE4'] == '' || is_null($accountOld['data']['RBADDITIONALSERVICE4']) ? null : $accountOld['data']['RBADDITIONALSERVICE4'];
	$ipv6AddressMarked = false;
	$oldIpv6AddressRemoved = false;

	// set to NULL if passed parameters are null
	if (strtoupper(trim($ipv6Address)) == 'NULL') {
		$ipv6Address = null;
	}

	// individual checking
	// NOTES 110917:
	// if (IP6N is not null)
	// 		if (IP6N == '') IP6N = IP6O
	// 		else check if IP6N is available
	// else
	// 		IP6N = IP6O
	if (!is_null($ipAddress)) {
		if ($ipv6Address != '') {
			// check for ipv6 address existence
			$ipv6Obj = Aaa::getIPv6Address($aaaConn, $ipv6Address);
			if (!$ipv6Obj['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$ipv6Obj['error']);
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
				$returnObj['replyMessage'] = 'AAA database error occurred';
				return $returnObj;
			}
			if ($ipv6Obj['data'] === false) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNLISTED_IPV6_ADDRESS.'] IPv6 address does not exist. {username: '.$username.'}');
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNLISTED_IPV6_ADDRESS);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				$returnObj['responseCode'] = F_UNLISTED_IPV6_ADDRESS;
				$returnObj['replyMessage'] = 'IPv6 address does not exist';
				return $returnObj;
			}
			// check for ipv6 address availability
			if ($ipv6Obj['data']['IPV6USED'] == 'Y' && $ipv6Obj['data']['USERNAME'] != $username) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNAVAILABLE_IPV6_ADDRESS.'] IPv6 address is not available. {username: '.$username.'}');
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNAVAILABLE_IPV6_ADDRESS);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				$returnObj['responseCode'] = F_UNAVAILABLE_IPV6_ADDRESS;
				$returnObj['replyMessage'] = 'IPv6 address is not available';
				return $returnObj;
			}
		} else {
			$ipv6Address = $oldIpv6Address;
		}
	} else {
		$ipv6Address = $oldIpv6Address;
	}

	// mark new ipv6 address
	if (!is_null($ipv6Address) && $oldIpv6Address != $ipv6Address) {
		$mark = Aaa::markIPv6Address($aaaConn, $ipv6Address, true, $username, $status);
		if (!$mark['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$mark['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked (new) '.$ipv6Address);
		$ipv6AddressMarked = true;
	}
	// remove old ipv6 address (if it had)
	if (!is_null($oldIpv6Address) && $oldIpv6Address != $ipv6Address) {
		$unmark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, false, $username, $status);
		if (!$unmark['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$umark['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked (old) '.$oldIpv6Address);
		$oldIpv6AddressRemoved = true;
	}
	/**************************************************
	 * input-specific conditions: ipAddress & netAddress (optional)
	 **************************************************/

	// if ($ipAddress == '') {
	// 	$ipAddress = null;
	// }
	// if ($netAddress == '') {
	// 	$netAddress = null;
	// }

	$paramIpAddressIsNull  = false;
	$paramNetAddressIsNull = false;

	// set to NULL if passed parameters are null
	if (strtoupper(trim($ipAddress)) == 'NULL') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'IP Address was set to NULL');
		$ipAddress = null;
		$paramIpAddressIsNull = true;
	}
	if (strtoupper(trim( $netAddress )) == 'NULL') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'NET Address was set to NULL');
		$netAddress = null;
		$paramNetAddressIsNull = true;
	}

	// get old data
	$oldIpAddress = $accountOld['data']['RBIPADDRESS'] == '' || is_null($accountOld['data']['RBIPADDRESS']) ? null : $accountOld['data']['RBIPADDRESS'];
	$oldNetAddress = $accountOld['data']['RBMULTISTATIC'] == '' || is_null($accountOld['data']['RBMULTISTATIC']) ? null : $accountOld['data']['RBMULTISTATIC'];
	$oldStatus = strtoupper($accountOld['data']['CUSTOMERSTATUS']) == 'ACTIVE' ? 'A' : 'D';


	// individual checking
	// NOTES 110917:
	// if (IPN is not null)
	// 		if (IPN == '') IPN = IPO
	// 		else check if IPN is available
	// else
	// 		IPN = IPO
	if (!is_null($ipAddress)) {
		if($ipAddress == ''){
			$ipAddress = $oldIpAddress;
		} else {
			// check for ip address format
			if (filter_var($ipAddress, FILTER_VALIDATE_IP) === false) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_IP_ADDRESS.'] Invalid IP address');
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_IP_ADDRESS);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				$returnObj['responseCode'] = F_INVALID_IP_ADDRESS;
				$returnObj['replyMessage'] = 'IP address does not exist';
				// revert ipv6 address marking
				if ($ipv6AddressMarked) {
					$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
					if (!$unmark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
					}
				}
				// revert old ipv6 address unmarking
				if ($oldIpv6AddressRemoved) {
					$mark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, true, $username, $status);
					if (!$mark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark '.$oldIpv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked '.$oldIpv6Address);
					}
				}
				return $returnObj;
			}
			// check for ip address existence
			$ipObj = Aaa::getIPAddress($aaaConn, $ipAddress);
			if (!$ipObj['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$ipObj['error']);
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
				$returnObj['replyMessage'] = 'AAA database error occurred';
				// revert ipv6 address marking
				if ($ipv6AddressMarked) {
					$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
					if (!$unmark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
					}
				}
				// revert old ipv6 address unmarking
				if ($oldIpv6AddressRemoved) {
					$mark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, true, $username, $status);
					if (!$mark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark '.$oldIpv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked '.$oldIpv6Address);
					}
				}
				return $returnObj;
			}
			if ($ipObj['data'] === false) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNLISTED_IP_ADDRESS.'] IP address does not exist. {username: '.$username.'}');
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNLISTED_IP_ADDRESS);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				$returnObj['responseCode'] = F_UNLISTED_IP_ADDRESS;
				$returnObj['replyMessage'] = 'IP address does not exist';
				// revert ipv6 address marking
				if ($ipv6AddressMarked) {
					$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
					if (!$unmark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
					}
				}
				// revert old ipv6 address unmarking
				if ($oldIpv6AddressRemoved) {
					$mark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, true, $username, $status);
					if (!$mark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark '.$oldIpv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked '.$oldIpv6Address);
					}
				}
				return $returnObj;
			}
			// check for ip address availability
			if ($ipObj['data']['IPUSED'] == 'Y' && $ipObj['data']['USERNAME'] != $username) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNAVAILABLE_IP_ADDRESS.'] IP address is not available. {username: '.$username.'}');
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNAVAILABLE_IP_ADDRESS);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				$returnObj['responseCode'] = F_UNAVAILABLE_IP_ADDRESS;
				$returnObj['replyMessage'] = 'IP address is not available';
				// revert ipv6 address marking
				if ($ipv6AddressMarked) {
					$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
					if (!$unmark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
					}
				}
				// revert old ipv6 address unmarking
				if ($oldIpv6AddressRemoved) {
					$mark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, true, $username, $status);
					if (!$mark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark '.$oldIpv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked '.$oldIpv6Address);
					}
				}
				return $returnObj;
			}
		}
	} else {
		$ipAddress = $oldIpAddress;
	}

	// NOTES 110917:
	// if (NPN is not null)
	// 		if (NPN == '') NPN = NPO
	// 		else check if IPN is available
	// else
	// 		NPN = NPO
	if (!is_null($netAddress)) {
		if($netAddress == ''){
			$netAddress = $oldNetAddress;
		} else {
			// check for net address format
			$netAddressParts = explode('/', $netAddress);
			if (count($netAddressParts) != 2 || filter_var($netAddressParts[0], FILTER_VALIDATE_IP) === false) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_NET_ADDRESS.'] Multi-static IP address does not exist (invalid net address). {username: '.$username.'}');
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_NET_ADDRESS);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				$returnObj['responseCode'] = F_INVALID_NET_ADDRESS;
				$returnObj['replyMessage'] = 'Multi-static IP address does not exist';
				// revert ipv6 address marking
				if ($ipv6AddressMarked) {
					$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
					if (!$unmark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
					}
				}
				// revert old ipv6 address unmarking
				if ($oldIpv6AddressRemoved) {
					$mark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, true, $username, $status);
					if (!$mark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark '.$oldIpv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked '.$oldIpv6Address);
					}
				}
				return $returnObj;
			}
			$netObj = Aaa::getNetAddress($aaaConn, $netAddress);
			if (!$netObj['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$netObj['error']);
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
				$returnObj['replyMessage'] = 'AAA database error occurred';
				// revert ipv6 address marking
				if ($ipv6AddressMarked) {
					$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
					if (!$unmark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
					}
				}
				// revert old ipv6 address unmarking
				if ($oldIpv6AddressRemoved) {
					$mark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, true, $username, $status);
					if (!$mark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark '.$oldIpv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked '.$oldIpv6Address);
					}
				}
				return $returnObj;
			}
			// check for net address existence
			if ($netObj['data'] === false) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNLISTED_NET_ADDRESS.'] Multi-static IP address does not exist. {username: '.$username.'}');
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNLISTED_NET_ADDRESS);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				$returnObj['responseCode'] = F_UNLISTED_NET_ADDRESS;
				$returnObj['replyMessage'] = 'Multi-static IP address does not exist';
				// revert ipv6 address marking
				if ($ipv6AddressMarked) {
					$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
					if (!$unmark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
					}
				}
				// revert old ipv6 address unmarking
				if ($oldIpv6AddressRemoved) {
					$mark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, true, $username, $status);
					if (!$mark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark '.$oldIpv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked '.$oldIpv6Address);
					}
				}
				return $returnObj;
			}
			// check for net address availability
			if ($netObj['data']['NETUSED'] == 'Y' && $netObj['data']['USERNAME'] != $username) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNAVAILABLE_NET_ADDRESS.'] Multi-static IP address is not available. {username: '.$username.'}');
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNAVAILABLE_NET_ADDRESS);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				$returnObj['responseCode'] = F_UNAVAILABLE_NET_ADDRESS;
				$returnObj['replyMessage'] = 'Multi-static IP address is not available';
				// revert ipv6 address marking
				if ($ipv6AddressMarked) {
					$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
					if (!$unmark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
					}
				}
				// revert old ipv6 address unmarking
				if ($oldIpv6AddressRemoved) {
					$mark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, true, $username, $status);
					if (!$mark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark '.$oldIpv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked '.$oldIpv6Address);
					}
				}
				return $returnObj;
			}
		}
	}

	// if net address is null and passed params is NULL
	if(is_null($netAddress) && $paramIpAddressIsNull)
		$ipAddress = null;


	// ip and net address scenarios
	if (is_null($ipAddress) && is_null($netAddress)) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Removing (or doesn\'t already have) ip and net address');
	} else if (is_null($ipAddress) && !is_null($netAddress)) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_IP_ADDRESS_SHOULD_HAVE_A_VALUE.'] End result is no ip with net: not allowed. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_IP_ADDRESS_SHOULD_HAVE_A_VALUE);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_IP_ADDRESS_SHOULD_HAVE_A_VALUE;
		$returnObj['replyMessage'] = 'IP address is required';
		return $returnObj;
	} else if (!is_null($ipAddress) && is_null($netAddress)) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Has/will have ip address, no net address');
	} else if (!is_null($ipAddress) && !is_null($netAddress)) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Has/will have both ip and net address');
	}


	$ipAddressMarked = false;
	$netAddressMarked = false;
	$oldIpAddressRemoved = false;
	$oldNetAddressRemoved = false;
	// mark new ip address
	if (!is_null($ipAddress) && $oldIpAddress != $ipAddress) {
		$mark = Aaa::markIPAddress($aaaConn, $ipAddress, true, $username, $status);
		if (!$mark['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$mark['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			// revert ipv6 address marking
			if ($ipv6AddressMarked) {
				$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
				}
			}
			// revert old ipv6 address unmarking
			if ($oldIpv6AddressRemoved) {
				$mark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, true, $username, $status);
				if (!$mark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark '.$oldIpv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked '.$oldIpv6Address);
				}
			}
			return $returnObj;
		}
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked (new): '.$ipAddress);
		$ipAddressMarked = true;
	}
	// remove old ip address
	if (!is_null($oldIpAddress) && $oldIpAddress != $ipAddress) {
		$unmark = Aaa::markIPAddress($aaaConn, $oldIpAddress, false, $username, $status);
		if (!$unmark['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$unmark['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			// revert ip address marking (if done)
			if ($ipAddressMarked) {
				$unmark = Aaa::markIPAddress($aaaConn, $ipAddress, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to unmark (new) '.$ipAddress);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked (new) '.$ipAddress);
				}
			}
			// revert ipv6 address marking
			if ($ipv6AddressMarked) {
				$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
				}
			}
			// revert old ipv6 address unmarking
			if ($oldIpv6AddressRemoved) {
				$mark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, true, $username, $status);
				if (!$mark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark '.$oldIpv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked '.$oldIpv6Address);
				}
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked (old): '.$oldIpAddress);
		$oldIpAddressRemoved = true;
	}
	// mark new net address
	if (!is_null($netAddress) && $oldNetAddress != $netAddress) {
		$mark = Aaa::markNetAddress($aaaConn, $netAddress, true, $username, $status);
		if (!$mark['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$mark['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			// revert ip address marking (if done)
			if ($ipAddressMarked) {
				$unmark = Aaa::markIPAddress($aaaConn, $ipAddress, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to unmark (new) '.$ipAddress);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked (new) '.$ipAddress);
				}
			}
			// revert old ip address unmarking (if done)
			if ($oldIpAddressRemoved) {
				$mark = Aaa::markIPAddress($aaaConn, $oldIpAddress, true, $username, $oldStatus);
				if (!$mark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to mark (old) '.$oldIpAddress);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked (old) '.$oldIpAddress);
				}
			}
			// revert ipv6 address marking
			if ($ipv6AddressMarked) {
				$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
				}
			}
			// revert old ipv6 address unmarking
			if ($oldIpv6AddressRemoved) {
				$mark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, true, $username, $status);
				if (!$mark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark '.$oldIpv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked '.$oldIpv6Address);
				}
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked (new): '.$netAddress);
		$netAddressMarked = true;
	}
	// remove old net address
	if (!is_null($oldNetAddress) && $oldNetAddress != $netAddress) {
		$unmark = Aaa::markNetAddress($aaaConn, $oldNetAddress, false, $username, $status);
		if (!$unmark['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$unmark['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			// revert ip address marking (if done)
			if ($ipAddressMarked) {
				$unmark = Aaa::markIPAddress($aaaConn, $ipAddress, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to unmark (new) '.$ipAddress);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked (new) '.$ipAddress);
				}
			}
			// revert old ip address unmarking (if done)
			if ($oldIpAddressRemoved) {
				$mark = Aaa::markIPAddress($aaaConn, $oldIpAddress, true, $username, $oldStatus);
				if (!$mark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to mark (old) '.$oldIpAddress);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked (old) '.$oldIpAddress);
				}
			}
			// revert net address marking (if done)
			if ($netAddressMarked) {
				$unmark = Aaa::markNetAddress($aaaConn, $netAddress, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to unmark (new) '.$netAddress);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked (new) '.$netAddress);
				}
			}
			// revert ipv6 address marking
			if ($ipv6AddressMarked) {
				$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
				}
			}
			// revert old ipv6 address unmarking
			if ($oldIpv6AddressRemoved) {
				$mark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, true, $username, $status);
				if (!$mark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark '.$oldIpv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked '.$oldIpv6Address);
				}
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked (old): '.$oldNetAddress);
		$oldNetAddressRemoved = true;
	}
	/**************************************************
	 * update in AAA
	 **************************************************/
	$customerReplyItem = Aaa::generateCustomerReplyItemValue($ipv6Address, $ipAddress, $netAddress);
	$aaaUpdate = Aaa::modifySubscriber($aaaConn, $username, $password, $oldStatus, $status, $serviceNumber, $fixedPlan, $customerType,
		$customerName, $orderNumber, $ipv6Address, $ipAddress, $netAddress, $remarks, $customerReplyItem);
	// aaa update failed
	if (!$aaaUpdate['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$aaaUpdate['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		// revert ip address marking (if done)
		if ($ipAddressMarked) {
			$unmark = Aaa::markIPAddress($aaaConn, $ipAddress, false, $username, $status);
			if (!$unmark['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to unmark (new) '.$ipAddress);
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked (new) '.$ipAddress);
			}
		}
		// revert old ip address unmarking (if done)
		if ($oldIpAddressRemoved) {
			$mark = Aaa::markIPAddress($aaaConn, $oldIpAddress, true, $username, $oldStatus);
			if (!$mark['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to mark (old) '.$oldIpAddress);
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked (old) '.$oldIpAddress);
			}
		}
		// revert net address marking (if done)
		if ($netAddressMarked) {
			$unmark = Aaa::markNetAddress($aaaConn, $netAddress, false, $username, $status);
			if (!$unmark['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to unmark (new) '.$netAddress);
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked (new) '.$netAddress);
			}
		}
		// revert old net address unmarkinf (if done)
		if ($oldNetAddressRemoved) {
			$mark = Aaa::markNetAddress($aaaConn, $oldNetAddress, true, $username, $oldStatus);
			if (!$mark['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to mark (old) '.$oldNetAddress);
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked (old) '.$oldNetAddress);
			}
		}
		// revert ipv6 address marking
		if ($ipv6AddressMarked) {
			$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
			if (!$unmark['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
			}
		}
		// revert old ipv6 address unmarking
		if ($oldIpv6AddressRemoved) {
			$mark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, true, $username, $status);
			if (!$mark['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark '.$oldIpv6Address);
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked '.$oldIpv6Address);
			}
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	// aaa update successful
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated at AAA');
	/**************************************************
	 * figure out X in #LX
	 **************************************************/
	$usernameExtra = '';
	if (!is_null($ipAddress) && $ipAddressMarked) {
		// migrating when ip address is given
		/*
		$locationObj = Aaa::getLocationObjWithLocationString($mysqlConn, $ipObj['data']['LOCATION']);
		if (!$locationObj['result'] && !empty($locationObj['data'])) {
			$usernameExtra = $locationObj['data']['rm_location'];
		}
		*/
	} else {
		if (!is_null($nasLocation)) {
			$locationObj = Aaa::getLocationObjWithNasCode($mysqlConn, $nasLocation);
			if ($locationObj['result'] && !empty($locationObj['data'])) {
				$usernameExtra = $locationObj['data'][0]['rm_location'];
			}
		}
	}
	/**************************************************
	 * update in RM
	 **************************************************/
	if (RMENABLED){
		$rmUsername = $username;
		$rmPlan = $fixedPlan;
		$rmStatus = 'ACTIVE';
		$rmCustomerType = $customerType == 'Residential' ? 'RESS' : 'BUSS';
		$rmParam3 = ($netAddressMarked || (!is_null($oldNetAddress) && !$oldNetAddressRemoved)) ? 'N' : 'Y';
		$rmNodes = array('PARAM3' => $rmParam3, 'AREA' => ($status == 'A' ? 'Active' : 'InActive'));
		$rmUpdate = Rm::wsUpdateSubscriberProfile($rmApiClient, $rmUsername, $rmPlan, $rmNodes, $rmStatus, $rmCustomerType);
		// rm update successful
		if (intval($rmUpdate['responseCode']) == 200) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SUBSCRIBER_UPDATED.'] Updated at RM. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_SUBSCRIBER_UPDATED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			// migrate if $usernameExtra != ''
			if ($usernameExtra != '') {
				$rmMigrate = Rm::wsMigrateSubscriber($rmApiClient, $rmUsername, $rmUsername.$usernameExtra);
				if (intval($rmMigrate['responseCode']) == 200) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", $rmUsername.' migrated to '.$rmUsername.$usernameExtra);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to migrate to '.$rmUsername.$usernameExtra.'|'.$rmMigrate['responseCode'].'|'.$rmMigrate['responseMessage']);
				}
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Will not migrate account');
			}
			// delete session if plan is new or ip/net address changed
			if (strtoupper($accountOld['data']['RBACCOUNTPLAN']) != strtoupper($fixedPlan) || $ipAddressMarked || $netAddressMarked || $oldIpAddressRemoved || $oldNetAddressRemoved) {
				$sessions = Aaa::getSubscriberSessions(
					$config['useTblm'] ? $tblmConcConn : $aaaConn,
					$config['useTblm'] ? $tblmConcConn2 : $aaaConn2,
					$config['useTblm'],
					$username);
				if (!$sessions['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$sessions['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to get sessions');
				} else {
					// account has no sessions
					if (empty($sessions['data'])) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found 0 sessions');
					// deleting sessions: 2x api + tblmconcurrentusers + tblmcoresessions
					} else {
						$sessionCount = count($sessions['data']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found '.$sessionCount.' sessions');
						for ($i = 0; $i < $sessionCount; $i++) {
							$session = $sessions['data'][$i];
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
							$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClient, $session, $username);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
							$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn, $username);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
							$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn, $username);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
							if ($config['useSecondary']) {
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
								$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClientSecondary, $session, $username);
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
								$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn2, $username);
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
								$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn2, $username);
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
							}
						}
					}
				}
			}
			return $returnObj;
		// account not found in rm, will try to insert
		} else if (intval($rmUpdate['responseCode']) == 404) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Inserting to RM');
			$rmInsert = Rm::wsAddSubscriber($rmApiClient, $rmUsername, $rmPlan, $rmNodes, $rmStatus, $rmCustomerType);
			// insert success
			if (intval($rmInsert['responseCode']) == 200) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SUBSCRIBER_UPDATED.'] Inserted at RM. {username: '.$username.'}');
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_SUBSCRIBER_UPDATED);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				// delete session (its possible that account already exists in AAA)
				if (strtoupper($accountOld['data']['RBACCOUNTPLAN']) != strtoupper($fixedPlan) ||
					$ipAddressMarked || $netAddressMarked || $oldIpAddressRemoved || $oldNetAddressRemoved) {
					$sessions = Aaa::getSubscriberSessions(
						$config['useTblm'] ? $tblmConcConn : $aaaConn,
						$config['useTblm'] ? $tblmConcConn2 : $aaaConn2,
						$config['useTblm'],
						$username);
					if (!$sessions['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$sessions['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to get sessions');
					} else {
						// account has no sessions
						if (empty($sessions['data'])) {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found 0 sessions');
						// deleting sessions: 2x api + tblmconcurrentusers + tblmcoresessions
						} else {
							$sessionCount = count($sessions['data']);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found '.$sessionCount.' sessions');
							for ($i = 0; $i < $sessionCount; $i++) {
								$session = $sessions['data'][$i];
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
								$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClient, $session, $username);
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
								$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn, $username);
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
								$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn, $username);
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
								if ($config['useSecondary']) {
									writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
									$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClientSecondary, $session, $username);
									writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
									writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
									$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn2, $username);
									writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
									writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
									$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn2, $username);
									writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
								}
							}
						}
					}
				}
				return $returnObj;
			// insert failed
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_GENERIC_RM_ERROR.'] Unable to insert at RM. {username: '.$username.'}');
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_GENERIC_RM_ERROR);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				$oldAccount = $accountOld['data'];
				// reverting aaa update
				$aaaRevertUpdate = Aaa::modifySubscriber($aaaConn, $username, $oldAccount['PASSWORD'], $oldAccount['CUSTOMERSTATUS'], $status, $oldAccount['RBSERVICENUMBER'],
					(is_null($oldAccount['RADIUSPOLICY']) || $oldAccount['RADIUSPOLICY'] == '' ? $oldAccount['RBACCOUNTPLAN'] : str_replace('~', '-', $oldAccount['RADIUSPOLICY'])),
					$oldAccount['CUSTOMERTYPE'],
					(is_null($oldAccount['RBCUSTOMERNAME']) || $oldAccount['RBCUSTOMERNAME'] == '' ? null : $oldAccount['RBCUSTOMERNAME']),
					$oldAccount['RBORDERNUMBER'],
					(is_null($oldAccount['RBADDITIONALSERVICE4']) || $oldAccount['RBADDITIONALSERVICE4'] == '' ? null : $oldAccount['RBADDITIONALSERVICE4']),
					(is_null($oldAccount['RBIPADDRESS']) || $oldAccount['RBIPADDRESS'] == '' ? null : $oldAccount['RBIPADDRESS']),
					(is_null($oldAccount['RBMULTISTATIC']) || $oldAccount['RBMULTISTATIC'] == '' ? null : $oldAccount['RBMULTISTATIC']),
					$oldAccount['RBREMARKS'],
					(is_null($oldAccount['RBADDITIONALSERVICE4'] || $oldAccount['RBADDITIONALSERVICE4'] == '' ? null : $oldAccount['RBADDITIONALSERVICE4'])));
				if (!$aaaRevertUpdate['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to revert: '.json_encode($oldAccount));
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Reverted in AAA');
				}
				// revert ip address marking (if done)
				if ($ipAddressMarked) {
					$unmark = Aaa::markIPAddress($aaaConn, $ipAddress, false, $username, $status);
					if (!$unmark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to unmark (new) '.$ipAddress);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked (new) '.$ipAddress);
					}
				}
				// revert old ip address unmarking (if done)
				if ($oldIpAddressRemoved) {
					$mark = Aaa::markIPAddress($aaaConn, $oldIpAddress, true, $username, $oldStatus);
					if (!$mark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to mark (old) '.$oldIpAddress);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked (old) '.$oldIpAddress);
					}
				}
				// revert net address marking (if done)
				if ($netAddressMarked) {
					$unmark = Aaa::markNetAddress($aaaConn, $netAddress, false, $username, $status);
					if (!$unmark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to unmark (new) '.$netAddress);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked (new) '.$netAddress);
					}
				}
				// revert old net address unmarking (if done)
				if ($oldNetAddressRemoved) {
					$mark = Aaa::markNetAddress($aaaConn, $oldNetAddress, true, $username, $oldStatus);
					if (!$mark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to mark (old) '.$oldNetAddress);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked (old) '.$oldNetAddress);
					}
				}
				// revert ipv6 address marking
				if ($ipv6AddressMarked) {
					$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
					if (!$unmark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
					}
				}
				// revert old ipv6 address unmarking
				if ($oldIpv6AddressRemoved) {
					$mark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, true, $username, $status);
					if (!$mark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark '.$oldIpv6Address);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked '.$oldIpv6Address);
					}
				}
				$returnObj['responseCode'] = F_GENERIC_RM_ERROR;
				$returnObj['replyMessage'] = 'RM error occurred';
				return $returnObj;
			}
		// other response codes returned (not 200 nor 404)
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_GENERIC_RM_ERROR.'] RM error: '.$rmUpdate['responseCode'].'|'.$rmUpdate['replyMessage']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_GENERIC_RM_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$oldAccount = $accountOld['data'];
			// revert aaa update
			$aaaRevertUpdate = Aaa::modifySubscriber($aaaConn, $username, $oldAccount['PASSWORD'], $oldAccount['CUSTOMERSTATUS'], $status, $oldAccount['RBSERVICENUMBER'],
				(is_null($oldAccount['RADIUSPOLICY']) || $oldAccount['RADIUSPOLICY'] == '' ? $oldAccount['RBACCOUNTPLAN'] : str_replace('~', '-', $oldAccount['RADIUSPOLICY'])),
				$oldAccount['CUSTOMERTYPE'],
				(is_null($oldAccount['RBCUSTOMERNAME']) || $oldAccount['RBCUSTOMERNAME'] == '' ? null : $oldAccount['RBCUSTOMERNAME']),
				$oldAccount['RBORDERNUMBER'],
				(is_null($oldAccount['RBADDITIONALSERVICE4']) || $oldAccount['RBADDITIONALSERVICE4'] == '' ? null : $oldAccount['RBADDITIONALSERVICE4']),
				(is_null($oldAccount['RBIPADDRESS']) || $oldAccount['RBIPADDRESS'] == '' ? null : $oldAccount['RBIPADDRESS']),
				(is_null($oldAccount['RBMULTISTATIC']) || $oldAccount['RBMULTISTATIC'] == '' ? null : $oldAccount['RBMULTISTATIC']),
				$oldAccount['RBREMARKS'],
				(is_null($oldAccount['RBADDITIONALSERVICE4']) || $oldAccount['RBADDITIONALSERVICE4'] == '' ? null : $oldAccount['RBADDITIONALSERVICE4']));
			if (!$aaaRevertUpdate['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to revert: '.json_encode($oldAccount));
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Reverted in AAA');
			}
			// revert ip address marking (if done)
			if ($ipAddressMarked) {
				$unmark = Aaa::markIPAddress($aaaConn, $ipAddress, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to unmark (new) '.$ipAddress);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked (new) '.$ipAddress);
				}
			}
			// revert old ip address unmarking (if done)
			if ($oldIpAddressRemoved) {
				$mark = Aaa::markIPAddress($aaaConn, $oldIpAddress, true, $username, $oldStatus);
				if (!$mark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to mark (old) '.$oldIpAddress);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked (old) '.$oldIpAddress);
				}
			}
			// revert net address marking (if done)
			if ($netAddressMarked) {
				$unmark = Aaa::markNetAddress($aaaConn, $netAddress, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to unmark (new) '.$netAddress);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked (new) '.$netAddress);
				}
			}
			// revert old net address unmarking (if done)
			if ($oldNetAddressRemoved) {
				$mark = Aaa::markNetAddress($aaaConn, $oldNetAddress, true, $username, $oldStatus);
				if (!$mark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to mark (old) '.$oldNetAddress);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked (old) '.$oldNetAddress);
				}
			}
			// revert ipv6 address marking
			if ($ipv6AddressMarked) {
				$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $status);
				if (!$unmark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark '.$ipv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked '.$ipv6Address);
				}
			}
			// revert old ipv6 address unmarking
			if ($oldIpv6AddressRemoved) {
				$mark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, true, $username, $status);
				if (!$mark['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark '.$oldIpv6Address);
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked '.$oldIpv6Address);
				}
			}
			$returnObj['responseCode'] = F_GENERIC_RM_ERROR;
			$returnObj['replyMessage'] = 'RM error occurred';
			return $returnObj;
		}
	} else {
		return $returnObj;
	}
}
function wsDeleteAccount($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsDeleteAccount';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$username = trim($param['username']);
	$returnObj = array(
		'responseCode' => F_SUBSCRIBER_DELETED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file. {username: '.$username.'}');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connections (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$aaaConn2 = oci_connect($config['secondarySessionUsername'], $config['secondarySessionPassword'], $config['secondarySessionUrl']);
	if ($aaaConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to AAA (secondary) database. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA (secondary) database error occurred';
		return $returnObj;
	}
	$tblmConcConn = oci_connect($config['primaryMconcurrentUsername'], $config['primaryMconcurrentPassword'], $config['primaryMconcurrentUrl']);
	if ($tblmConcConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCONCURRENTUSERS table. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmConcConn2 = oci_connect($config['secondaryMconcurrentUsername'], $config['secondaryMconcurrentPassword'], $config['secondaryMconcurrentUrl']);
	if ($tblmConcConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCONCURRENTUSERS table. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn = oci_connect($config['primaryMcoreUsername'], $config['primaryMcorePassword'], $config['primaryMcoreUrl']);
	if ($tblmCoreConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCORESESSIONS table. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn2 = oci_connect($config['secondaryMcoreUsername'], $config['secondaryMcorePassword'], $config['secondaryMcoreUrl']);
	if ($tblmCoreConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCORESESSIONS table. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM database connection
	 **************************************************/
	if (RMENABLED){
		if (!$config['useAaaForPlans']) {
			$rmConn = oci_connect($config['primaryRmDbUsername'], $config['primaryRmDbPassword'], $config['primaryRmDbUrl']);
			if ($rmConn === false) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_DB_CONNECT_ERROR.'] No connection to RM database. {username: '.$username.'}');
				$returnObj['responseCode'] = F_RM_DB_CONNECT_ERROR;
				$returnObj['replyMessage'] = 'RM error occurred';
				return $returnObj;
			}
		} else {
			$rmConn = false;
		}
	} else {
		$rmConn = false;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database. {username: '.$username.'}');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM client
	 **************************************************/
	if (RMENABLED){
		try {
			$rmApiClient = new SoapClient('http://'.$config['primaryRmUrl'])	;
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryRmUrl']);
			$returnObj['responseCode'] = F_RM_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'RM error occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * delete session client
	 **************************************************/
	try {
		$deleteSessionClient = new SoapClient('http://'.$config['primaryDeleteSessionUrl']);
	} catch (Exception $e) {
		$error = json_encode($e);
		$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		$end = strpos($error, '","faultcode"');
		$err = substr($error, $start, $end - $start);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryDeleteSessionUrl']);
		$returnObj['responseCode'] = 501;
		$returnObj['replyMessage'] = 'Delete session client connection error occurred';
		return $returnObj;
	}
	if ($config['useSecondary']) {
		try {
			$deleteSessionClientSecondary = new SoapClient('http://'.$config['secondaryDeleteSessionUrl']);
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_SECONDARY_API_CONNECT_ERROR.'] Unable to connect to '.
				$config['secondaryDeleteSessionUrl']);
			$returnObj['responseCode'] = 501;
			$returnObj['replyMessage'] = 'Delete session client connection (secondary) error occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: username (required)
	 **************************************************/
	if ($username == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Username is required';
		return $returnObj;
	}
	// check for username existence
	$account = Aaa::getSubscriberWithUsername($aaaConn, $username);
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
	if ($account['data'] === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj ['replyMessage'] = 'Username not found';
		return $returnObj;
	}
	$accountOld = $account['data'];
	/**************************************************
	 * delete from  AAA
	 **************************************************/
	$aaaDelete = Aaa::deleteSubscriber($aaaConn, $username);
	// aaa delete failed
	if (!$aaaDelete['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$aaaDelete['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	// aaa delete successful
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleted at AAA');
	/**************************************************
	 * unmark used ip address
	 **************************************************/
	$customerStatus = $accountOld['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D';
	$ipAddress = is_null($accountOld['RBIPADDRESS']) || $accountOld['RBIPADDRESS'] == '' ? null : $accountOld['RBIPADDRESS'];
	if (!is_null($ipAddress)) {
		$unmark = Aaa::markIPAddress($aaaConn, $ipAddress, false, $username, $customerStatus);
		if (!$unmark['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark: '.$ipAddress);
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked as unused: '.$ipAddress);
		}
	}
	/**************************************************
	 * unmark used net address
	 **************************************************/
	$netAddress = is_null($accountOld['RBMULTISTATIC']) || $accountOld['RBMULTISTATIC'] == '' ? null : $accountOld['RBMULTISTATIC'];
	if (!is_null($netAddress)) {
		$unmark = Aaa::markNetAddress($aaaConn, $netAddress, false, $username, $customerStatus);
		if (!$unmark['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark: '.$netAddress);
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked as unused: '.$netAddress);
		}
	}
	/**************************************************
	 * unmark used ipv6 address
	 **************************************************/
	$ipv6Address = is_null($accountOld['RBADDITIONALSERVICE4']) || $accountOld['RBADDITIONALSERVICE4'] == '' ? null : $accountOld['RBADDITIONALSERVICE4'];
	if (!is_null($ipv6Address)) {
		$unmark = Aaa::markIPv6Address($aaaConn, $ipv6Address, false, $username, $customerStatus);
		if (!$unmark['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark: '.$ipv6Address);
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked as unused: '.$ipv6Address);
		}
	}
	if (RMENABLED){
		/**************************************************
		 * delete in RM
		 **************************************************/
		$deleteSession = false;
		$rmUsername = $username;
		$rmDelete = Rm::wsDeleteSubscriberProfile($rmApiClient, $rmUsername);
		// rm delete successful, do purge
		if (intval($rmDelete['responseCode']) == 200) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SUBSCRIBER_DELETED.'] Deleted at RM');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_SUBSCRIBER_DELETED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$rmPurge = Rm::wsPurgeSubscriber($rmApiClient, $rmUsername);
			if (intval($rmPurge['responseCode']) == 200) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Purged at RM');
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'failed to purge: '.$rmPurge['responseCode'].'|'.$rmPurge['responseMessage']);
			}
			$deleteSession = true;
		// no account to delete, still ok
		} else if (intval($rmDelete['responseCode']) == 404) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SUBSCRIBER_DELETED.'] Username does not exist at RM. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_SUBSCRIBER_DELETED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$deleteSession = true;
		// rm delete failed
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_GENERIC_RM_ERROR.'] Failed to delete: '.
				$rmDelete['responseCode'].'|'.$rmDelete['responseMessage']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_GENERIC_RM_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			// revert aaa delete
			$password = $accountOld['PASSWORD'];
			// customerStatus was set earlier
			$serviceNumber = $accountOld['RBSERVICENUMBER'];
			$plan = str_replace('~', '-', $accountOld['RBACCOUNTPLAN']);
			$customerType = $accountOld['CUSTOMERTYPE'];
			$customerName = is_null($accountOld['RBCUSTOMERNAME']) || $accountOld['RBCUSTOMERNAME'] == '' ? null : $accountOld['RBCUSTOMERNAME'];
			$orderNumber = is_null($accountOld['RBORDERNUMBER']) || $accountOld['RBORDERNUMBER'] == '' ? null : $accountOld['RBORDERNUMBER'];
			// ipAddress and netAddress were set earlier: ipAddress & netAddress
			$remarks = is_null($accountOld['RBREMARKS']) || $accountOld['RBREMARKS'] == '' ? null : $accountOld['RBREMARKS'];
			$aaaInsert = Aaa::createSubscriber($aaaConn, $username, $password, $customerStatus, $serviceNumber, $plan, $customerType, $customerName, $orderNumber,
				$ipv6Address, $ipAddress, $netAddress, $remarks);
			// aaa re-insert failed
			if (!$aaaInsert['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$aaaInsert['error']);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to re-insert account: '.json_encode($accountOld));
			// aaa re-insert successful
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Re-inserted account: '.json_encode($accountOld));
				// revert ip & net address unmarking
				if (!is_null($ipAddress)) {
					$mark = Aaa::markIPAddress($aaaConn, $ipAddress, true, $username, $customerStatus);
					if (!$mark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to re-mark: '.$ipAddress);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Reverted unmarking: '.$ipAddress);
					}
				}
				if (!is_null($netAddress)) {
					$mark = Aaa::markNetAddress($aaaConn, $netAddress, true, $username, $customerStatus);
					if (!$mark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to re-mark: '.$netAddress);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Reverted unmarking: '.$netAddress);
					}
				}
			}
			$returnObj['responseCode'] = F_GENERIC_RM_ERROR;
			$returnObj['replyMessage'] = 'RM error occurred';
		}
	} else {
		$deleteSession = true;
	}
	/**************************************************
	 * delete session
	 **************************************************/
	if ($deleteSession) {
		$sessions = Aaa::getSubscriberSessions(
			$config['useTblm'] ? $tblmConcConn : $aaaConn,
			$config['useTblm'] ? $tblmConcConn2 : $aaaConn2,
			$config['useTblm'],
			$username);
		if (!$sessions['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$sessions['error']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to get sessions');
		} else {
			// account has no session
			if (empty($sessions['data'])) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found 0 sessions');
			// account has session(s)
			} else {
				$sessionCount = count($sessions['data']);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found '.$sessionCount.' sessions');
				// delete sessions: 2x api + tblmconcurrentusers + tblmcoresessions
				for ($i = 0; $i < $sessionCount; $i++) {
					$session = $sessions['data'][$i];
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
					$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClient, $session, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
					$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
					$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					if ($config['useSecondary']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
						$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClientSecondary, $session, $username);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
						$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn2, $username);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
						$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn2, $username);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					}
				}
			}
		}
	}
	return $returnObj;
}
function wsChangePlan($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsChangePlan';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$username = trim($param['username']);
	$plan = str_replace('~', '-', trim($param['plan']));
	$returnObj = array(
		'responseCode' => F_PLAN_UPDATED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connections (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$aaaConn2 = oci_connect($config['secondarySessionUsername'], $config['secondarySessionPassword'], $config['secondarySessionUrl']);
	if ($aaaConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to AAA (secondary) database');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA (secondary) database error occurred';
		return $returnObj;
	}
	$tblmConcConn = oci_connect($config['primaryMconcurrentUsername'], $config['primaryMconcurrentPassword'], $config['primaryMconcurrentUrl']);
	if ($tblmConcConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmConcConn2 = oci_connect($config['secondaryMconcurrentUsername'], $config['secondaryMconcurrentPassword'], $config['secondaryMconcurrentUrl']);
	if ($tblmConcConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn = oci_connect($config['primaryMcoreUsername'], $config['primaryMcorePassword'], $config['primaryMcoreUrl']);
	if ($tblmCoreConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn2 = oci_connect($config['secondaryMcoreUsername'], $config['secondaryMcorePassword'], $config['secondaryMcoreUrl']);
	if ($tblmCoreConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM database connection
	 **************************************************/
	if (RMENABLED){
		if (!$config['useAaaForPlans']) {
			$rmConn = oci_connect($config['primaryRmDbUsername'], $config['primaryRmDbPassword'], $config['primaryRmDbUrl']);
			if ($rmConn === false) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_DB_CONNECT_ERROR.'] No connection to RM database');
				$returnObj['responseCode'] = F_RM_DB_CONNECT_ERROR;
				$returnObj['replyMessage'] = 'RM error occurred';
				return $returnObj;
			}
		} else {
			$rmConn = false;
		}
	} else {
		$rmConn = false;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM client
	 **************************************************/
	if (RMENABLED){
		try {
			$rmApiClient = new SoapClient('http://'.$config['primaryRmUrl'])	;
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryRmUrl']);
			$returnObj['responseCode'] = F_RM_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'RM Error Occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * delete session client
	 **************************************************/
	try {
		$deleteSessionClient = new SoapClient('http://'.$config['primaryDeleteSessionUrl']);
	} catch (Exception $e) {
		$error = json_encode($e);
		$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		$end = strpos($error, '","faultcode"');
		$err = substr($error, $start, $end - $start);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryDeleteSessionUrl']);
		$returnObj['responseCode'] = 501;
		$returnObj['replyMessage'] = 'Delete session client connection error occurred';
		return $returnObj;
	}
	if ($config['useSecondary']) {
		try {
			$deleteSessionClientSecondary = new SoapClient('http://'.$config['secondaryDeleteSessionUrl']);
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_SECONDARY_API_CONNECT_ERROR.'] Unable to connect to '.
				$config['secondaryDeleteSessionUrl']);
			$returnObj['responseCode'] = F_SESSION_SECONDARY_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'Delete session client connection (secondary) error occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * get radiuspolicy names
	 **************************************************/
	$planNamesTmp = Aaa::getPlanNames($aaaConn, $rmConn, $config['useAaaForPlans']);
	if (!$planNamesTmp['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_PLAN_LIST_FETCH_ERROR.'] Error getting plan names: '.$planNamesTmp['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_PLAN_LIST_FETCH_ERROR;
		$returnObj['replyMessage'] = ($config['useAaaForPlans'] ? 'AAA' : 'RM').' database error occurred';
		return $returnObj;
	}
	$planNames = $planNamesTmp['data'];
	unset($planNamesTmp);
	/**************************************************
	 * get plan boosts
	 **************************************************/
	$planBoostsTmp = Aaa::getSpeedBoosts($aaaConn);
	if (!$planBoostsTmp['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_BOOST_LIST_FETCH_ERROR.'] Error getting speed boosts: '.$planBoostsTmp['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_BOOST_LIST_FETCH_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_BOOST_LIST_FETCH_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$planBoosts = $planBoostsTmp['data'];
	unset($planBoostsTmp);
	/**************************************************
	 * input-specific conditions: username (required)
	 **************************************************/
	if ($username == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Username is required';
		return $returnObj;
	}
	// check for account existence
	$account = Aaa::getSubscriberWithUsername($aaaConn, $username);
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
	if ($account['data'] === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj ['replyMessage'] = 'Username not found';
		return $returnObj;
	}
	/**************************************************
	 * input-specific conditions: plan (required)
	 **************************************************/
	if ($plan == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: plan');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Username and plan are required';
		return $returnObj;
	}
	// separate plan from vod part (if any)
	$vodVariables = Aaa::separateVodFromPlan($plan);
	$planHasVod = $vodVariables['hasVod'];
	$planVodValue = $vodVariables['vodValue'];
	$planCleaned = $vodVariables['find'];
	// vod is not allowed
	if ($planHasVod) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_PLAN_HAS_VOD.'] Plan has vod value');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_PLAN_HAS_VOD);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_PLAN_HAS_VOD;
		$returnObj['replyMessage'] = 'Plan does not exist';
		return $returnObj;
	}
	// reorder boosts (if any)
	$fixPlanVariables = Aaa::fixPlan($planCleaned, $planNames, $planBoosts);
	$foundPlan = $fixPlanVariables['found'];
	$fixedPlan = $fixPlanVariables['fixed'];
	if (!$foundPlan) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNLISTED_PLAN.'] Plan does not exist');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNLISTED_PLAN);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_UNLISTED_PLAN;
		$returnObj['replyMessage'] = 'Plan does not exist';
		return $returnObj;
	}
	/**************************************************
	 * update plan in AAA
	 * - does not care if new plan is the same as old plan, will update
	 **************************************************/
	$aaaUpdate = Aaa::updateSubscriberPlan($aaaConn, $username, $fixedPlan);
	// plan update failed
	if (!$aaaUpdate['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$aaaUpdate['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	// plan update successful
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Plan updated at AAA');
	/**************************************************
	 * update plan in RM
	 **************************************************/
	if (RMENABLED){
		$rmUsername = $username;
		$rmPlan = $fixedPlan;
		$rmUpdate = Rm::wsUpdateSubscriberProfile($rmApiClient, $rmUsername, $rmPlan, null, null, null);
		// rm update successful
		if ($rmUpdate['responseCode'] == 200) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_PLAN_UPDATED.'] Plan updated at RM');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_PLAN_UPDATED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$sessions = Aaa::getSubscriberSessions(
				$config['useTblm'] ? $tblmConcConn : $aaaConn,
				$config['useTblm'] ? $tblmConcConn2 : $aaaConn2,
				$config['useTblm'],
				$username);
			if (!$sessions['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$sessions['error']);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to get sessions');
			} else {
				// account has no session
				if (empty($sessions['data'])) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found 0 sessions');
				// account has session(s)
				} else {
					$sessionCount = count($sessions['data']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found '.$sessionCount.' sessions');
					// delete sessions: 2x api + tblmconcurrentusers + tblmcoresessions
					for ($i = 0; $i < $sessionCount; $i++) {
						$session = $sessions['data'][$i];
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
						$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClient, $session, $username);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
						$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn, $username);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
						$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn, $username);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
						if ($config['useSecondary']) {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
							$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClientSecondary, $session, $username);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
							$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn2, $username);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
							$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn2, $username);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
						}
					}
				}
			}
		// rm update failed
		} else {
			// revert plan update in AAA
			$aaaUpdate = Aaa::updateSubscriberPlan($aaaConn, $username, $account['data']['RBACCOUNTPLAN']);
			if (!$aaaUpdate['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to revert plan to '.$account['data']['RBACCOUNTPLAN']);
			}
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_GENERIC_RM_ERROR.'] Failed to update plan: '.
				$rmUpdate['responseCode'].'|'.$rmUpdate['replyMessage']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_GENERIC_RM_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_PLAN_UPDATED;
			$returnObj['replyMessage'] = 'RM error occurred';
		}
	}
	return $returnObj;
}
function wsChangeUsername($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsChangeUsername';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$oldUsername = trim($param['oldUsername']);
	$newUsername = trim($param['newUsername']);
	$returnObj = array(
		'responseCode' => 200,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connections (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$aaaConn2 = oci_connect($config['secondarySessionUsername'], $config['secondarySessionPassword'], $config['secondarySessionUrl']);
	if ($aaaConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to AAA (secondary) database');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA (secondary) database error occurred';
		return $returnObj;
	}
	$tblmConcConn = oci_connect($config['primaryMconcurrentUsername'], $config['primaryMconcurrentPassword'], $config['primaryMconcurrentUrl']);
	if ($tblmConcConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmConcConn2 = oci_connect($config['secondaryMconcurrentUsername'], $config['secondaryMconcurrentPassword'], $config['secondaryMconcurrentUrl']);
	if ($tblmConcConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn = oci_connect($config['primaryMcoreUsername'], $config['primaryMcorePassword'], $config['primaryMcoreUrl']);
	if ($tblmCoreConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn2 = oci_connect($config['secondaryMcoreUsername'], $config['secondaryMcorePassword'], $config['secondaryMcoreUrl']);
	if ($tblmCoreConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM database connection
	 **************************************************/
	if (RMENABLED){
		if (!$config['useAaaForPlans']) {
			$rmConn = oci_connect($config['primaryRmDbUsername'], $config['primaryRmDbPassword'], $config['primaryRmDbUrl']);
			if ($rmConn === false) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_DB_CONNECT_ERROR.'] No connection to RM database');
				$returnObj['responseCode'] = F_RM_DB_CONNECT_ERROR;
				$returnObj['replyMessage'] = 'RM error occurred';
				return $returnObj;
			}
		} else {
			$rmConn = false;
		}
	} else {
		$rmconn = false;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM client
	 **************************************************/
	if (RMENABLED){
		try {
			$rmApiClient = new SoapClient('http://'.$config['primaryRmUrl'])	;
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryRmUrl']);
			$returnObj['responseCode'] = F_RM_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'RM Error Occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * delete session client
	 **************************************************/
	try {
		$deleteSessionClient = new SoapClient('http://'.$config['primaryDeleteSessionUrl']);
	} catch (Exception $e) {
		$error = json_encode($e);
		$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		$end = strpos($error, '","faultcode"');
		$err = substr($error, $start, $end - $start);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryDeleteSessionUrl']);
		$returnObj['responseCode'] = F_SESSION_API_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Delete session client connection error occurred';
		return $returnObj;
	}
	if ($config['useSecondary']) {
		try {
			$deleteSessionClientSecondary = new SoapClient('http://'.$config['secondaryDeleteSessionUrl']);
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_SECONDARY_API_CONNECT_ERROR.'] Unable to connect to '.
				$config['secondaryDeleteSessionUrl']);
			$returnObj['responseCode'] = F_SESSION_SECONDARY_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'Delete session client connection (secondary) error occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: oldUsername (required)
	 **************************************************/
	if ($oldUsername == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: oldUsername');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'oldUsername and newUsername are required';
		return $returnObj;
	}
	/**************************************************
	 * input-specific conditions: newUsername (required)
	 **************************************************/
	if ($newUsername == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: newUsername');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'oldUsername and newUsername are required';
		return $returnObj;
	}
	// check for old username existence
	$accountOld = Aaa::getSubscriberWithUsername($aaaConn, $oldUsername);
	if (!$accountOld['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$accountOld['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	if ($accountOld['data'] === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Old username not found');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj['replyMessage'] = 'Old username not found';
		return $returnObj;
	}
	// check for new username existence
	$accountNew = Aaa::getSubscriberWithUsername($aaaConn, $newUsername);
	if (!$accountNew['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$accountNew['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	if ($accountNew['data'] !== false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_NEW_USERNAME_ALREADY_USED.'] New username already exists');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_NEW_USERNAME_ALREADY_USED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_NEW_USERNAME_ALREADY_USED;
		$returnObj['replyMessage'] = 'New username already exists';
		return $returnObj;
	}
	// delete account with oldUsername
	$aaaDelete = Aaa::deleteSubscriber($aaaConn, $oldUsername);
	// aaa delete failed
	if (!$aaaDelete['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$aaaDelete['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	// aaa delete successful
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleted at AAA');
	$accountOldData = $accountOld['data'];
	$password = $accountOldData['PASSWORD'];
	$status = strtoupper($accountOldData['CUSTOMERSTATUS']) == 'ACTIVE' ? 'A' : 'D';
	$serviceNumber = $accountOldData['RBSERVICENUMBER'];
	$fixedPlan = is_null($accountOldData['RADIUSPOLICY']) || $accountOldData['RADIUSPOLICY'] == '' || empty($accountOldData['RADIUSPOLICY']) ?
		str_replace('~', '-', $accountOldData['RBACCOUNTPLAN']) : str_replace('~', '-', $accountOldData['RADIUSPOLICY']);
	$customerType = $accountOldData['CUSTOMERTYPE'];
	$customerName = is_null($accountOldData['RBCUSTOMERNAME']) || empty($accountOldData['RBCUSTOMERNAME']) || $accountOldData['RBCUSTOMERNAME'] == '' ?
		null : $accountOldData['RBCUSTOMERNAME'];
	$orderNumber = is_null($accountOldData['RBORDERNUMBER']) || empty($accountOldData['RBORDERNUMBER']) || $accountOldData['RBORDERNUMBER'] == '' ?
		null : $accountOldData['RBORDERNUMBER'];
	$ipv6Address = is_null($accountOldData['RBADDITIONALSERVICE4']) || empty($accountOldData['RBADDITIONALSERVICE4']) || $accountOldData['RBADDITIONALSERVICE4'] == '' ?
		null : $accountOldData['RBADDITIONALSERVICE4'];
	$ipAddress = is_null($accountOldData['RBIPADDRESS']) || empty($accountOldData['RBIPADDRESS']) || $accountOldData['RBIPADDRESS'] == '' ?
		null : $accountOldData['RBIPADDRESS'];
	$netAddress = is_null($accountOldData['RBMULTISTATIC']) || empty($accountOldData['RBMULTISTATIC']) || $accountOldData['RBMULTISTATIC'] == '' ?
		null : $accountOldData['RBMULTISTATIC'];
	$remarks = is_null($accountOldData['RBREMARKS']) || empty($accountOldData['RBREMARKS']) || $accountOldData['RBREMARKS'] == '' ?
		null : $accountOldData['RBREMARKS'];
	// re-insert oldUsername data with newUsername
	$aaaInsert = Aaa::createSubscriber($aaaConn, $newUsername, $password, $status, $serviceNumber, $fixedPlan, $customerType,
		$customerName, $orderNumber, $ipv6Address, $ipAddress, $netAddress, $remarks);
	// aaa insert failed
	if (!$aaaInsert['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$aaaInsert['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	// aaa insert successful
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Inserted at AAA');
	// update usernames at ip and net address tables
	if (!is_null($ipAddress)) {
		$mark = Aaa::markIPAddress($aaaConn, $ipAddress, true, $newUsername, $status);
		if (!$mark['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to update username for ip address: '.$ipAddress);
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated ip table with new username: '.$ipAddress);
		}
	}
	if (!is_null($netAddress)) {
		$mark = Aaa::markNetAddress($aaaConn, $netAddress, true, $newUsername, $status);
		if (!$mark['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to update username for net address: '.$netAddress);
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated net table with new username: '.$netAddress);
		}
	}

	if (RMENABLED){
		// at this point all changes to aaa tblcustomer and ip/net tables are done, rm delete/purge/insert next
		$deleteSession = false;
		$okToRmInsert = false;
		$rmUsername = $oldUsername;
		$rmDelete = Rm::wsDeleteSubscriberProfile($rmApiClient, $rmUsername);
		// rm delete successful, do purge
		$rmDeleteRespCode = intval($rmDelete['responseCode']);
		if (in_array($rmDeleteRespCode, array(200, 404))) {
			$deleteSession = true;
			if ($rmDeleteRespCode == 200) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleted at RM');
				$rmPurge = Rm::wsPurgeSubscriber($rmApiClient, $rmUsername);
				if (intval($rmPurge['responseCode']) == 200) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Purged at RM');
					$okToRmInsert = true;
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to purge: '.$rmPurge['responseCode'].'|'.$rmPurge['responseMessage']);
				}
			} else if ($rmDeleteRespCode == 404) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Username does not exist at RM');
				$okToRmInsert = true;
			}
			if ($okToRmInsert) {
				$rmPlan = $fixedPlan;
				$rmStatus = 'ACTIVE';
				$rmCustomerType = $customerType == 'Residential' ? 'RESS' : 'BUSS';
				$rmParam3 = is_null($netAddress) ? 'Y' : 'N';
				$rmNodes = array('PARAM3' => $rmParam3, 'AREA' => ($status == 'A' ? 'Active' : 'InActive'));
				$rmInsert = Rm::wsAddSubscriber($rmApiClient, $newUsername, $rmPlan, $rmNodes, $rmStatus, $rmCustomerType);
				if (intval($rmInsert['responseCode']) == 200) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_UPDATED.'] Inserted at rm');
					$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_UPDATED);
					if (!$lrq['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
					}
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_GENERIC_RM_ERROR.'] Unable to insert to rm: '.
						$rmInsert['responseCode'].'|'.$rmInsert['responseMessage']);
					$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_GENERIC_RM_ERROR);
					if (!$lrq['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
					}
					$aaaDelete = Aaa::deleteSubscriber($aaaConn, $newUsername);
					if (!$aaaDelete['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$aaaDelete['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to delete account with new username');
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleted new account');
						$aaaInsert = Aaa::createSubscriber($aaaConn, $oldUsername, $password, $customerStatus, $serviceNumber, $plan, $customerType,
							$customerName, $orderNumber, $ipv6Address, $ipAddress, $netAddress, $remarks);
						if (!$aaaInsert['result']) {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$aaaInsert['error']);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to re-insert old account');
						} else {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Re-inserted old account');
						}
						if (!is_null($ipAddress)) {
							$mark = Aaa::markIPAddress($aaaConn, $ipAddress, true, $oldUsername, $status);
							if (!$mark['result']) {
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to revert update at ip table: '.$ipAddress);
							} else {
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Reverted update at ip table');
							}
						}
						if (!is_null($netAddress)) {
							$mark = Aaa::markNetAddress($aaaConn, $netAddress, true, $oldUsername, $status);
							if (!$mark['result']) {
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to revert update at net table: '.$netAddress);
							} else {
								writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Reverted update at net table');
							}
						}
					}
					$returnObj['responseCode'] = F_GENERIC_RM_ERROR;
					$returnObj['replyMessage'] = 'RM error occurred';
					return $returnObj;
				}
			}
		// rm delete failed
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_GENERIC_RM_ERROR.'] Failed to delete: '.
				$rmDelete['responseCode'].'|'.$rmDelete['responseMessage']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_GENERIC_RM_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			// delete in aaa (newUsername) then re-insert (oldUsername);
			$aaaDelete = Aaa::deleteSubscriber($aaaConn, $newUsername);
			if (!$aaaDelete['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$aaaDelete['error']);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to delete account with new username');
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleted new account');
				$aaaInsert = Aaa::createSubscriber($aaaConn, $oldUsername, $password, $customerStatus, $serviceNumber, $plan, $customerType,
					$customerName, $orderNumber, $ipv6Address, $ipAddress, $netAddress, $remarks);
				if (!$aaaInsert['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$aaaInsert['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to re-insert old account');
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Re-inserted old account');
				}
				if (!is_null($ipAddress)) {
					$mark = Aaa::markIPAddress($aaaConn, $ipAddress, true, $oldUsername, $status);
					if (!$mark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to revert update at ip table: '.$ipAddress);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Reverted update at ip table');
					}
				}
				if (!is_null($netAddress)) {
					$mark = Aaa::markNetAddress($aaaConn, $netAddress, true, $oldUsername, $status);
					if (!$mark['result']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to revert update at net table: '.$netAddress);
					} else {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Reverted update at net table');
					}
				}
			}
			$returnObj['responseCode'] = F_GENERIC_RM_ERROR;
			$returnObj['replyMessage'] = 'RM error occurred';
		}
	} else {
		$deleteSession = true;
	}
	/**************************************************
	 * delete session
	 **************************************************/
	if ($deleteSession) {
		$sessions = Aaa::getSubscriberSessions(
			$config['useTblm'] ? $tblmConcConn : $aaaConn,
			$config['useTblm'] ? $tblmConcConn2 : $aaaConn2,
			$config['useTblm'],
			$oldUsername);
		if (!$sessions['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$sessions['error']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to get sessions');
		} else {
			// account has no session
			if (empty($sessions['data'])) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found 0 sessions');
			// account has session(s)
			} else {
				$sessionCount = count($sessions['data']);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found '.$sessionCount.' sessions');
				// delete sessions: 2x api + tblmconcurrentusers + tblmcoresessions
				for ($i = 0; $i < $sessionCount; $i++) {
					$session = $sessions['data'][$i];
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
					$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClient, $session, $oldUsername);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
					$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn, $oldUsername);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
					$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn, $oldUsername);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					if ($config['useSecondary']) {
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
						$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClientSecondary, $session, $oldUsername);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
						$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn2, $oldUsername);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
						$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn2, $oldUsername);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					}
				}
			}
		}
	}
	return $returnObj;
}
function wsDeactivateAccount($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsDeactivateAccount';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$username = trim($param['username']);
	$returnObj = array(
		'responseCode' => F_SUBSCRIBER_DEACTIVATED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$aaaConn2 = oci_connect($config['secondarySessionUsername'], $config['secondarySessionPassword'], $config['secondarySessionUrl']);
	if ($aaaConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to AAA (secondary) database');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA (secondary) database error occurred';
		return $returnObj;
	}
	$tblmConcConn = oci_connect($config['primaryMconcurrentUsername'], $config['primaryMconcurrentPassword'], $config['primaryMconcurrentUrl']);
	if ($tblmConcConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmConcConn2 = oci_connect($config['secondaryMconcurrentUsername'], $config['secondaryMconcurrentPassword'], $config['secondaryMconcurrentUrl']);
	if ($tblmConcConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn = oci_connect($config['primaryMcoreUsername'], $config['primaryMcorePassword'], $config['primaryMcoreUrl']);
	if ($tblmCoreConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn2 = oci_connect($config['secondaryMcoreUsername'], $config['secondaryMcorePassword'], $config['secondaryMcoreUrl']);
	if ($tblmCoreConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM database connection
	 **************************************************/
	if (RMENABLED){
		if (!$config['useAaaForPlans']) {
			$rmConn = oci_connect($config['primaryRmDbUsername'], $config['primaryRmDbPassword'], $config['primaryRmDbUrl']);
			if ($rmConn === false) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_DB_CONNECT_ERROR.'] No connection to RM database');
				$returnObj['responseCode'] = F_RM_DB_CONNECT_ERROR;
				$returnObj['replyMessage'] = 'RM error occurred';
				return $returnObj;
			}
		} else {
			$rmConn = false;
		}
	} else {
		$rmConn = false;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM client
	 **************************************************/
	if (RMENABLED){
		try {
			$rmApiClient = new SoapClient('http://'.$config['primaryRmUrl'])	;
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryRmUrl']);
			$returnObj['responseCode'] = F_RM_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'RM Error Occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * delete session client
	 **************************************************/
	try {
		$deleteSessionClient = new SoapClient('http://'.$config['primaryDeleteSessionUrl']);
	} catch (Exception $e) {
		$error = json_encode($e);
		$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		$end = strpos($error, '","faultcode"');
		$err = substr($error, $start, $end - $start);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryDeleteSessionUrl']);
		$returnObj['responseCode'] = F_SESSION_API_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Delete session client connection error occurred';
		return $returnObj;
	}
	if ($config['useSecondary']) {
		try {
			$deleteSessionClientSecondary = new SoapClient('http://'.$config['secondaryDeleteSessionUrl']);
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_SECONDARY_API_CONNECT_ERROR.'] Unable to connect to '.
				$config['secondaryDeleteSessionUrl']);
			$returnObj['responseCode'] = F_SESSION_SECONDARY_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'Delete session client connection (secondary) error occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: username (required)
	 **************************************************/
	if ($username == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Username is required';
		return $returnObj;
	}
	// check for account existence
	$account = Aaa::getSubscriberWithUsername($aaaConn, $username);
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
	if ($account['data'] === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj ['replyMessage'] = 'Username not found';
		return $returnObj;
	}
	/**************************************************
	 * deactivate account in AAA
	 * - does not care if current status is D/InActive, will still update
	 **************************************************/
	$oldStatus = strtoupper($account['data']['CUSTOMERSTATUS']) == 'ACTIVE' ? 'A' : 'D';
	$newStatus = 'D';
	$aaaUpdate = Aaa::updateSubscriberStatus($aaaConn, $username, $newStatus);
	// aaa update failed
	if (!$aaaUpdate['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] AAA database error occurred');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	// aaa update succesful
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated at AAA');
	/**************************************************
	 * deactivate account in RM
	 **************************************************/
	if (RMENABLED){
		$rmUsername = $username;
		$nodes = array('AREA' => 'InActive');
		$rmUpdate = Rm::wsUpdateSubscriberProfile($rmApiClient, $username, null, $nodes, null, null);
		// rm update successful
		if (intval($rmUpdate['responseCode']) == 200) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SUBSCRIBER_DEACTIVATED.'] Updated at RM');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_SUBSCRIBER_DEACTIVATED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			// update status at ip/ipv6/net address tables (if any)
			$ipv6Address = is_null($account['data']['RBADDITIONALSERVICE4']) || $account['data']['RBADDITIONALSERVICE4'] == '' || empty($account['data']['RBADDITIONALSERVICE4'])
				? null : $account['data']['RBADDITIONALSERVICE4'];
			$ipAddress = is_null($account['data']['RBIPADDRESS']) || $account['data']['RBIPADDRESS'] == '' || empty($account['data']['RBIPADDRESS'])
				? null : $account['data']['RBIPADDRESS'];
			$netAddress = is_null($account['data']['RBMULTISTATIC']) || $account['data']['RBMULTISTATIC'] == '' || empty($account['data']['RBMULTISTATIC'])
				? null : $account['data']['RBMULTISTATIC'];
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'ipv6Address: '.(is_null($ipv6Address) ? 'none' : $ipv6Address).
				', ipAddress: '.(is_null($ipAddress) ? 'none' : $ipAddress).
				', netAddress: '.(is_null($netAddress) ? 'none' : $netAddress));
			if (!is_null($ipv6Address)) {
				$update = Aaa::updateStatusAtIPv6Table($aaaConn, $ipv6Address, $newStatus, $username);
				if (!$update['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$update['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to update status at ipv6 table');
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated status at ipv6 table');
				}
			}
			if (!is_null($ipAddress)) {
				$update = Aaa::updateStatusAtIPTable($aaaConn, $ipAddress, $newStatus, $username);
				if (!$update['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$update['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to update status at ip table');
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated status at ip table');
				}
			}
			if (!is_null($netAddress)) {
				$update = Aaa::updateStatusAtNetTable($aaaConn, $netAddress, $newStatus, $username);
				if (!$update['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$update['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to update status at net table');
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated status at net table');
				}
			}
			// delete sessions (if found)
			$sessions = Aaa::getSubscriberSessions(
				$config['useTblm'] ? $tblmConcConn : $aaaConn,
				$config['useTblm'] ? $tblmConcConn2 : $aaaConn2,
				$config['useTblm'],
				$username);
			if (!$sessions['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$sessions['error']);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to get sessions');
			} else {
				// account has no session
				if (empty($sessions['data'])) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found 0 sessions');
				// account has session(s)
				} else {
					$sessionCount = count($sessions['data']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found '.$sessionCount.' sessions');
					// delete sessions: 2x api + tblmconcurrentusers + tblmcoresessions
					for ($i = 0; $i < $sessionCount; $i++) {
						$session = $sessions['data'][$i];
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
						$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClient, $session, $username);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
						$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn, $username);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
						$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn, $username);
						writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
						if ($config['useSecondary']) {
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
							$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClientSecondary, $session, $username);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
							$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn2, $username);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
							$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn2, $username);
							writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
						}
					}
				}
			}
		// rm update failed
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_GENERIC_RM_ERROR.'] Failed to update status: '.
				$rmUpdate['responseCode'].'|'.$rmUpdate['replyMessage']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_GENERIC_RM_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			// revert aaa status update
			$aaaUpdate = Aaa::updateSubscriberStatus($aaaConn, $username, $oldStatus);
			if (!$aaaUpdate['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to revert status to '.$oldStatus);
			}
			$returnObj['responseCode'] = F_GENERIC_RM_ERROR;
			$returnObj['replyMessage'] = 'RM error occurred';
		}
	}
	return $returnObj;
}
function wsActivateAccount($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsActivateAccount';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$username = trim($param['username']);
	$returnObj = array(
		'responseCode' => F_SUBSCRIBER_ACTIVATED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}

	$aaaConn2 = oci_connect($config['secondarySessionUsername'], $config['secondarySessionPassword'], $config['secondarySessionUrl']);
	if ($aaaConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to AAA (secondary) database. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA (secondary) database error occurred';
		return $returnObj;
	}

	$tblmConcConn = oci_connect($config['primaryMconcurrentUsername'], $config['primaryMconcurrentPassword'], $config['primaryMconcurrentUrl']);
	if ($tblmConcConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCONCURRENTUSERS table. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}

	$tblmConcConn2 = oci_connect($config['secondaryMconcurrentUsername'], $config['secondaryMconcurrentPassword'], $config['secondaryMconcurrentUrl']);
	if ($tblmConcConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCONCURRENTUSERS table. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}

	$tblmCoreConn = oci_connect($config['primaryMcoreUsername'], $config['primaryMcorePassword'], $config['primaryMcoreUrl']);
	if ($tblmCoreConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCORESESSIONS table. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}

	$tblmCoreConn2 = oci_connect($config['secondaryMcoreUsername'], $config['secondaryMcorePassword'], $config['secondaryMcoreUrl']);
	if ($tblmCoreConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCORESESSIONS table. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}

	/**************************************************
	 * delete session client
	 **************************************************/
	try {
		$deleteSessionClient = new SoapClient('http://'.$config['primaryDeleteSessionUrl']);
	} catch (Exception $e) {
		$error = json_encode($e);
		$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		$end = strpos($error, '","faultcode"');
		$err = substr($error, $start, $end - $start);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryDeleteSessionUrl']);
		$returnObj['responseCode'] = F_SESSION_API_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Delete session client connection error occurred';
		return $returnObj;
	}
	if ($config['useSecondary']) {
		try {
			$deleteSessionClientSecondary = new SoapClient('http://'.$config['secondaryDeleteSessionUrl']);
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_SECONDARY_API_CONNECT_ERROR.'] Unable to connect to '.
				$config['secondaryDeleteSessionUrl']);
			$returnObj['responseCode'] = F_SESSION_SECONDARY_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'Delete session client connection (secondary) error occurred';
			return $returnObj;
		}
	}

	/**************************************************
	 * RM database connection
	 **************************************************/
	if (RMENABLED){
		if (!$config['useAaaForPlans']) {
			$rmConn = oci_connect($config['primaryRmDbUsername'], $config['primaryRmDbPassword'], $config['primaryRmDbUrl']);
			if ($rmConn === false) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_DB_CONNECT_ERROR.'] No connection to RM database');
				$returnObj['responseCode'] = F_RM_DB_CONNECT_ERROR;
				$returnObj['replyMessage'] = 'RM error occurred';
				return $returnObj;
			}
		} else {
			$rmConn = false;
		}
	} else {
		$rmConn = false;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM client
	 **************************************************/
	if (RMENABLED){
		try {
			$rmApiClient = new SoapClient('http://'.$config['primaryRmUrl'])	;
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryRmUrl']);
			$returnObj['responseCode'] = F_RM_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'RM Error Occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: username (required)
	 **************************************************/
	if ($username == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Username is required';
		return $returnObj;
	}
	// check for account existence
	$account = Aaa::getSubscriberWithUsername($aaaConn, $username);
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
	if ($account['data'] === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj ['replyMessage'] = 'Username not found';
		return $returnObj;
	}

	/**
	 * Get subscribers session
	 */
	$sessions = Aaa::getSubscriberSessions(
		$config['useTblm'] ? $tblmConcConn : $aaaConn,
		$config['useTblm'] ? $tblmConcConn2 : $aaaConn2,
		$config['useTblm'],
		$username);
	if (!$sessions['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$sessions['error']);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to get sessions');
	} else {
		// account has no session
		if (empty($sessions['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found 0 sessions');
		// account has session(s)
		} else {
			$sessionCount = count($sessions['data']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found '.$sessionCount.' sessions');
			// delete sessions: 2x api + tblmconcurrentusers + tblmcoresessions
			for ($i = 0; $i < $sessionCount; $i++) {
				$session = $sessions['data'][$i];
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
				$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClient, $session, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
				$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
				$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				if ($config['useSecondary']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
					$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClientSecondary, $session, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
					$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn2, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
					$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn2, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				}
			}
		}
	}


	/**************************************************
	 * activate account in AAA
	 * - does not care if current status is A/Active, will still update
	 **************************************************/
	$oldStatus = strtoupper($account['data']['CUSTOMERSTATUS']) == 'ACTIVE' ? 'A' : 'D';
	$newStatus = 'A';
	$aaaUpdate = Aaa::updateSubscriberStatus($aaaConn, $username, $newStatus);
	// aaa status update failed
	if (!$aaaUpdate['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] AAA database error occurred');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	// aaa status update successful
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated at AAA');
	/**************************************************
	 * activate account in RM
	 **************************************************/
	if (RMENABLED){
		$rmUsername = $username;
		$nodes = array('AREA' => 'Active');
		$rmUpdate = Rm::wsUpdateSubscriberProfile($rmApiClient, $username, null, $nodes, null, null);
		// rm update successful
		if (intval($rmUpdate['responseCode']) == 200) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SUBSCRIBER_ACTIVATED.'] Updated at RM');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_SUBSCRIBER_ACTIVATED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			// update status at ip/ipv6/net address tables (if any)
			$ipv6Address = is_null($account['data']['RBADDITIONALSERVICE4']) || $account['data']['RBADDITIONALSERVICE4'] == '' || empty($account['data']['RBADDITIONALSERVICE4'])
				? null : $account['data']['RBADDITIONALSERVICE4'];
			$ipAddress = is_null($account['data']['RBIPADDRESS']) || $account['data']['RBIPADDRESS'] == '' || empty($account['data']['RBIPADDRESS'])
				? null : $account['data']['RBIPADDRESS'];
			$netAddress = is_null($account['data']['RBMULTISTATIC']) || $account['data']['RBMULTISTATIC'] == '' || empty($account['data']['RBMULTISTATIC'])
				? null : $account['data']['RBMULTISTATIC'];
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'ipv6Address: '.(is_null($ipv6Address) ? 'none' : $ipv6Address).
				', ipAddress: '.(is_null($ipAddress) ? 'none' : $ipAddress).
				', netAddress: '.(is_null($netAddress) ? 'none' : $netAddress));
			if (!is_null($ipv6Address)) {
				$update = Aaa::updateStatusAtIPv6Table($aaaConn, $ipv6Address, $newStatus, $username);
				if (!$update['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$update['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to update status at ipv6 table');
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated status at ipv6 table');
				}
			}
			if (!is_null($ipAddress)) {
				$update = Aaa::updateStatusAtIPTable($aaaConn, $ipAddress, $newStatus, $username);
				if (!$update['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$update['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to update status at ip table');
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated status at ip table');
				}
			}
			if (!is_null($netAddress)) {
				$update = Aaa::updateStatusAtNetTable($aaaConn, $netAddress, $newStatus, $username);
				if (!$update['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$update['error']);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to update status at net table');
				} else {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated status at net table');
				}
			}
		// rm update failed
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_GENERIC_RM_ERROR.'] Failed to update status: '.
				$rmUpdate['responseCode'].'|'.$rmUpdate['replyMessage']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_GENERIC_RM_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			// revert aaa status update
			$aaaUpdate = Aaa::updateSubscriberStatus($aaaConn, $username, $oldStatus);
			if (!$aaaUpdate['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to revert status to '.$oldStatus);
			}
			$returnObj['responseCode'] = F_GENERIC_RM_ERROR;
			$returnObj['replyMessage'] = 'RM error occurred';
		}
	}
	return $returnObj;
}
function wsAddIPAddress($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsAddIPAddress';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$username = trim($param['username']);
	$ipAddress = trim($param['ipAddress']);
	$cabinetName = trim($param['cabinetName']);
	$returnObj = array(
		'responseCode' => F_IP_ADDRESS_ADDED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$aaaConn2 = oci_connect($config['secondarySessionUsername'], $config['secondarySessionPassword'], $config['secondarySessionUrl']);
	if ($aaaConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to AAA (secondary) database');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA (secondary) database error occurred';
		return $returnObj;
	}
	$tblmConcConn = oci_connect($config['primaryMconcurrentUsername'], $config['primaryMconcurrentPassword'], $config['primaryMconcurrentUrl']);
	if ($tblmConcConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmConcConn2 = oci_connect($config['secondaryMconcurrentUsername'], $config['secondaryMconcurrentPassword'], $config['secondaryMconcurrentUrl']);
	if ($tblmConcConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn = oci_connect($config['primaryMcoreUsername'], $config['primaryMcorePassword'], $config['primaryMcoreUrl']);
	if ($tblmCoreConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn2 = oci_connect($config['secondaryMcoreUsername'], $config['secondaryMcorePassword'], $config['secondaryMcoreUrl']);
	if ($tblmCoreConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM client
	 **************************************************/
	if (RMENABLED){
		try {
			$rmApiClient = new SoapClient('http://'.$config['primaryRmUrl'])	;
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryRmUrl']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", $err);
			$returnObj['responseCode'] = F_RM_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'RM Error Occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * delete session client
	 **************************************************/
	try {
		$deleteSessionClient = new SoapClient('http://'.$config['primaryDeleteSessionUrl']);
	} catch (Exception $e) {
		$error = json_encode($e);
		$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		$end = strpos($error, '","faultcode"');
		$err = substr($error, $start, $end - $start);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryDeleteSessionUrl']);
		$returnObj['responseCode'] = F_SESSION_API_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Delete session client connection error occurred';
		return $returnObj;
	}
	if ($config['useSecondary']) {
		try {
			$deleteSessionClientSecondary = new SoapClient('http://'.$config['secondaryDeleteSessionUrl']);
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_SECONDARY_API_CONNECT_ERROR.'] Unable to connect to '.
				$config['secondaryDeleteSessionUrl']);
			$returnObj['responseCode'] = F_SESSION_SECONDARY_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'Delete session client connection (secondary) error occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: username (required)
	 **************************************************/
	if ($username == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Username is required';
		return $returnObj;
	}
	// check for account existence
	$accountOld = Aaa::getSubscriberWithUsername($aaaConn, $username);
	if (!$accountOld['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$accountOld['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	if ($accountOld['data'] === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj ['replyMessage'] = 'Username not found';
		return $returnObj;
	}
	/**************************************************
	 * input-specific conditions: ipaddress/cabinetname (at least one should be provided, ipaddress takes preference)
	 **************************************************/
	if ($ipAddress == '') {
		$ipAddress = null;
	}
	if ($cabinetName == '') {
		$cabinetName = null;
	}
	if (is_null($ipAddress)) {
		if (is_null($cabinetName)) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRES_IP_ADDRESS.'] no ip address or cabinet name provided');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRES_IP_ADDRESS);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_REQUIRES_IP_ADDRESS;
			$returnObj['replyMessage'] = 'IP address is required';
			return $returnObj;
		}
		// check for cabinet name existence
		$cabinetObj = Aaa::getCabinet($mysqlConn, $cabinetName);
		if (!$cabinetObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$cabinetObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		if ($cabinetObj['data'] === false || empty($cabinetObj['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CABINET_NAME_UNLISTED.'] Cabinet name does not exist');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_CABINET_NAME_UNLISTED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_CABINET_NAME_UNLISTED;
			$returnObj['replyMessage'] = 'Cabinet name does not exist';
			return $returnObj;
		}
		// check for availability of ip address with given cabinet name
		$ipObj = Aaa::getIPAddressWithCabinet($aaaConn, $mysqlConn, $cabinetName);
		if (!$ipObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$ipObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		if ($ipObj['data'] === false || empty($ipObj['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_NO_AVAILABLE_IP_ADDRESS.'] No available IP address');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_NO_AVAILABLE_IP_ADDRESS);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_NO_AVAILABLE_IP_ADDRESS;
			$returnObj['replyMessage'] = 'No available IP address';
			return $returnObj;
		}
		$ipAddress = $ipObj['data']['IPADDRESS'];
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Selected IP address: '.$ipAddress);
	} else {
		// check for ip address format
		if (filter_var($ipAddress, FILTER_VALIDATE_IP) === false) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_IP_ADDRESS_TO_ADD.'] Invalid IP address');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_IP_ADDRESS_TO_ADD);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_INVALID_IP_ADDRESS_TO_ADD;
			$returnObj['replyMessage'] = 'IP address does not exist';
			return $returnObj;
		}
		// check for ip address existence
		$ipObj = Aaa::getIPAddress($aaaConn, $ipAddress);
		if (!$ipObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$ipObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		if ($ipObj['data'] === false) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNLISTED_IP_ADDRESS_TO_ADD.'] IP address does not exist');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNLISTED_IP_ADDRESS_TO_ADD);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_UNLISTED_IP_ADDRESS_TO_ADD;
			$returnObj['replyMessage'] = 'IP address does not exist';
			return $returnObj;
		}
		// check for ip address availability
		if ($ipObj['data']['IPUSED'] == 'Y') {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNAVAILABLE_IP_ADDRESS_TO_ADD.'] IP address is not available');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNAVAILABLE_IP_ADDRESS_TO_ADD);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_UNAVAILABLE_IP_ADDRESS_TO_ADD;
			$returnObj['replyMessage'] = 'IP address is not available';
			return $returnObj;
		}
	}
	$oldIpAddress = $accountOld['data']['RBIPADDRESS'] == '' || is_null($accountOld['data']['RBIPADDRESS']) ? null : $accountOld['data']['RBIPADDRESS'];
	$oldNetAddress = $accountOld['data']['RBMULTISTATIC'] = '' | is_null($accountOld['data']['RBMULTISTATIC']) ? null : $accountOld['data']['RBMULTISTATIC'];
	$oldIpv6Address = $accountOld['data']['RBADDITIONALSERVICE4'] == '' || is_null($accountOld['data']['RBADDITIONALSERVICE4']) ? null : $accountOld['data']['RBADDITIONALSERVICE4'];
	$currentStatus = strtoupper($accountOld['data']['CUSTOMERSTATUS']) == 'ACTIVE' ? 'A' : 'D';
	/**************************************************
	 * update ip address
	 **************************************************/
	$customerReplyItem = Aaa::generateCustomerReplyItemValue($oldIpv6Address, $ipAddress, $oldNetAddress);
	$aaaUpdate = Aaa::updateSubscriberIPv6IpNetAddress($aaaConn, $username, $oldIpv6Address, $ipAddress, $oldNetAddress, $customerReplyItem);
	// aaa ip address update failed
	if (!$aaaUpdate['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$aaaUpdate['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	// aaa ip address update succesful
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_IP_ADDRESS_ADDED.'] Updated in AAA');
	$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_IP_ADDRESS_ADDED);
	if (!$lrq['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
	}
	// mark new ip address
	$mark = Aaa::markIPAddress($aaaConn, $ipAddress, true, $username, $currentStatus);
	if (!$mark['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark ip address: '.$ipAddress);
	} else {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked as used: '.$ipAddress);
	}
	// unmark old ip address (if any)
	if (!is_null($oldIpAddress)) {
		$unmark = Aaa::markIPAddress($aaaConn, $oldIpAddress, false, $username, $currentStatus);
		if (!$unmark['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark ip address: '.$oldIpAddress);
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked as unused: '.$oldIpAddress);
		}
	}
	//delete session
	$sessions = Aaa::getSubscriberSessions(
		$config['useTblm'] ? $tblmConcConn : $aaaConn,
		$config['useTblm'] ? $tblmConcConn2 : $aaaConn2,
		$config['useTblm'],
		$username);
	if (!$sessions['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$sessions['error']);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to get sessions');
	} else {
		// account has no session
		if (empty($sessions['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found 0 sessions');
		// account has session(s)
		} else {
			$sessionCount = count($sessions['data']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found '.$sessionCount.' sessions');
			// delete sessions: 2x api + tblmconcurrentusers + tblmcoresessions
			for ($i = 0; $i < $sessionCount; $i++) {
				$session = $sessions['data'][$i];
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
				$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClient, $session, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
				$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
				$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				if ($config['useSecondary']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
					$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClientSecondary, $session, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
					$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn2, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
					$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn2, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				}
			}
		}
	}
	return $returnObj;
}
function wsAddIPv6Address($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsAddIPv6Address';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$username = trim($param['username']);
	$ipv6Address = trim($param['ipv6Address']);
	$cabinetName = trim($param['cabinetName']);
	$returnObj = array(
		'responseCode' => F_IPV6_ADDRESS_ADDED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$aaaConn2 = oci_connect($config['secondarySessionUsername'], $config['secondarySessionPassword'], $config['secondarySessionUrl']);
	if ($aaaConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to AAA (secondary) database');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA (secondary) database error occurred';
		return $returnObj;
	}
	$tblmConcConn = oci_connect($config['primaryMconcurrentUsername'], $config['primaryMconcurrentPassword'], $config['primaryMconcurrentUrl']);
	if ($tblmConcConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmConcConn2 = oci_connect($config['secondaryMconcurrentUsername'], $config['secondaryMconcurrentPassword'], $config['secondaryMconcurrentUrl']);
	if ($tblmConcConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn = oci_connect($config['primaryMcoreUsername'], $config['primaryMcorePassword'], $config['primaryMcoreUrl']);
	if ($tblmCoreConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn2 = oci_connect($config['secondaryMcoreUsername'], $config['secondaryMcorePassword'], $config['secondaryMcoreUrl']);
	if ($tblmCoreConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM client
	 **************************************************/
	if (RMENABLED){
		try {
			$rmApiClient = new SoapClient('http://'.$config['primaryRmUrl']);
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryRmUrl']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", $err);
			$returnObj['responseCode'] = F_RM_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'RM Error Occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * delete session client
	 **************************************************/
	try {
		$deleteSessionClient = new SoapClient('http://'.$config['primaryDeleteSessionUrl']);
	} catch (Exception $e) {
		$error = json_encode($e);
		$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		$end = strpos($error, '","faultcode"');
		$err = substr($error, $start, $end - $start);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryDeleteSessionUrl']);
		$returnObj['responseCode'] = F_SESSION_API_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Delete session client connection error occurred';
		return $returnObj;
	}
	if ($config['useSecondary']) {
		try {
			$deleteSessionClientSecondary = new SoapClient('http://'.$config['secondaryDeleteSessionUrl']);
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_SECONDARY_API_CONNECT_ERROR.'] Unable to connect to '.
				$config['secondaryDeleteSessionUrl']);
			$returnObj['responseCode'] = F_SESSION_SECONDARY_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'Delete session client connection (secondary) error occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: username (required)
	 **************************************************/
	if ($username == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Username is required';
		return $returnObj;
	}
	// check for account existence
	$accountOld = Aaa::getSubscriberWithUsername($aaaConn, $username);
	if (!$accountOld['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$accountOld['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	if ($accountOld['data'] === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj ['replyMessage'] = 'Username not found';
		return $returnObj;
	}
	/**************************************************
	 * input-specific conditions: ipaddress/cabinetname (at least one should be provided, ipaddress takes preference)
	 **************************************************/
	if ($ipv6Address == '') {
		$ipv6Address = null;
	}
	if ($cabinetName == '') {
		$cabinetName = null;
	}
	if (is_null($ipv6Address)) {
		if (is_null($cabinetName)) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRES_IPV6_ADDRESS.'] no ip address or cabinet name provided');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRES_IPV6_ADDRESS);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
			$returnObj['responseCode'] = F_REQUIRES_IPV6_ADDRESS;
			$returnObj['replyMessage'] = 'IPv6 address is required';
			return $returnObj;
		}
		// check for cabinet name existence
		$cabinetObj = Aaa::getCabinet($mysqlConn, $cabinetName);
		if (!$cabinetObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$cabinetObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		if ($cabinetObj['data'] === false || empty($cabinetObj['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CABINET_NAME_UNLISTED.'] Cabinet name does not exist');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_CABINET_NAME_UNLISTED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_CABINET_NAME_UNLISTED;
			$returnObj['replyMessage'] = 'Cabinet name does not exist';
			return $returnObj;
		}
		// check for availability of ip address with given cabinet name
		$ipObj = Aaa::getIPv6AddressWithCabinet($aaaConn, $mysqlConn, $cabinetName);
		if (!$ipObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$ipObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		if ($ipObj['data'] === false || empty($ipObj['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_NO_AVAILABLE_IPV6_ADDRESS.'] No available IPv6 address');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_NO_AVAILABLE_IPV6_ADDRESS);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_NO_AVAILABLE_IPV6_ADDRESS;
			$returnObj['replyMessage'] = 'No available IPv6 address';
			return $returnObj;
		}
		$ipv6Address = $ipObj['data']['IPV6ADDR'];
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Selected IPv6 address: '.$ipv6Address);
	} else {
		// check for ipv6 address validity/format
		$hasSubnet = strpos($ipv6Address, '/') !== false;
		if (!$hasSubnet) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_IPV6_ADDRESS_TO_ADD.'] Invalid ipv6 address (no subnet)');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_IPV6_ADDRESS_TO_ADD);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_INVALID_IPV6_ADDRESS_TO_ADD;
			$returnObj['replyMessage'] = 'IPv6 address does not exist';
			return $returnObj;
		}
		$parts = explode('/', $ipv6Address);
		$toCheck = $parts[0];
		if (filter_var($toCheck, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNLISTED_IPV6_ADDRESS_TO_ADD.'] Invalid ipv6 address');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNLISTED_IPV6_ADDRESS_TO_ADD);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_INVALID_IPV6_ADDRESS_TO_ADD;
			$returnObj['replyMessage'] = 'IPv6 address does not exist';
			return $returnObj;
		}
		// check for ip address existence
		$ipObj = Aaa::getIPv6Address($aaaConn, $ipv6Address);
		if (!$ipObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$ipObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		if ($ipObj['data'] === false) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNLISTED_IPV6_ADDRESS_TO_ADD.'] IPv6 address does not exist');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNLISTED_IPV6_ADDRESS_TO_ADD);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_UNLISTED_IPV6_ADDRESS_TO_ADD;
			$returnObj['replyMessage'] = 'IPv6 address does not exist';
			return $returnObj;
		}
		// check for ip address availability
		if ($ipObj['data']['IPV6USED'] == 'Y') {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNAVAILABLE_IPV6_ADDRESS_TO_ADD.'] IP address is not available');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNAVAILABLE_IPV6_ADDRESS_TO_ADD);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_UNAVAILABLE_IPV6_ADDRESS_TO_ADD;
			$returnObj['replyMessage'] = 'IP address is not available';
			return $returnObj;
		}
	}
	$oldIpv6Address = $accountOld['data']['RBADDITIONALSERVICE4'] == '' || is_null($accountOld['data']['RBADDITIONALSERVICE4']) ? null : $accountOld['data']['RBADDITIONALSERVICE4'];
	$oldIpAddress = $accountOld['data']['RBIPADDRESS'] == '' || is_null($accountOld['data']['RBIPADDRESS']) ? null : $accountOld['data']['RBIPADDRESS'];
	$oldNetAddress = $accountOld['data']['RBMULTISTATIC'] == '' || is_null($accountOld['data']['RBMULTISTATIC']) ? null : $accountOld['data']['RBMULTISTATIC'];
	$currentStatus = strtoupper($accountOld['data']['CUSTOMERSTATUS']) == 'ACTIVE' ? 'A' : 'D';
	/**************************************************
	 * update ipv6 address (RBADDITIONALSERVICE4)
	 **************************************************/
	$customerReplyItem = Aaa::generateCustomerReplyItemValue($ipv6Address, $oldIpAddress, $oldNetAddress);
	$aaaUpdate = Aaa::updateSubscriberIPv6IpNetAddress($aaaConn, $username, $ipv6Address, $oldIpAddress, $oldNetAddress, $customerReplyItem);
	// aaa ipv6 address update failed
	if (!$aaaUpdate['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$aaaUpdate['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	// aaa ipv6 address update succesful
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_IPV6_ADDRESS_ADDED.'] Updated in AAA');
	$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_IPV6_ADDRESS_ADDED);
	if (!$lrq['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
	}
	// mark new ipv6 address
	$mark = Aaa::markIPv6Address($aaaConn, $ipv6Address, true, $username, $currentStatus);
	if (!$mark['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark ipv6 address: '.$ipv6Address);
	} else {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked as used: '.$ipv6Address);
	}
	// unmark old ipv6 address (if any)
	if (!is_null($oldIpv6Address)) {
		$unmark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, false, $username, $currentStatus);
		if (!$unmark['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark ipv6 address: '.$oldIpv6Address);
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked as unused: '.$oldIpv6Address);
		}
	}
	//delete session
	$sessions = Aaa::getSubscriberSessions(
		$config['useTblm'] ? $tblmConcConn : $aaaConn,
		$config['useTblm'] ? $tblmConcConn2 : $aaaConn2,
		$config['useTblm'],
		$username);
	if (!$sessions['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$sessions['error']);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to get sessions');
	} else {
		// account has no session
		if (empty($sessions['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found 0 sessions');
		// account has session(s)
		} else {
			$sessionCount = count($sessions['data']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found '.$sessionCount.' sessions');
			// delete sessions: 2x api + tblmconcurrentusers + tblmcoresessions
			for ($i = 0; $i < $sessionCount; $i++) {
				$session = $sessions['data'][$i];
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
				$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClient, $session, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
				$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
				$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				if ($config['useSecondary']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
					$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClientSecondary, $session, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
					$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn2, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
					$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn2, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				}
			}
		}
	}
	return $returnObj;
}
function wsAddNetAddress($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsAddNetAddress';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$username = trim($param['username']);
	$netAddress = trim($param['netAddress']);
	$cabinetName = trim($param['cabinetName']);
	$range = trim($param['range']);
	$returnObj = array(
		'responseCode' => F_NET_ADDRESS_ADDED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connections (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$aaaConn2 = oci_connect($config['secondarySessionUsername'], $config['secondarySessionPassword'], $config['secondarySessionUrl']);
	if ($aaaConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to AAA (secondary) database');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA (secondary) database error occurred';
		return $returnObj;
	}
	$tblmConcConn = oci_connect($config['primaryMconcurrentUsername'], $config['primaryMconcurrentPassword'], $config['primaryMconcurrentUrl']);
	if ($tblmConcConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmConcConn2 = oci_connect($config['secondaryMconcurrentUsername'], $config['secondaryMconcurrentPassword'], $config['secondaryMconcurrentUrl']);
	if ($tblmConcConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn = oci_connect($config['primaryMcoreUsername'], $config['primaryMcorePassword'], $config['primaryMcoreUrl']);
	if ($tblmCoreConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn2 = oci_connect($config['secondaryMcoreUsername'], $config['secondaryMcorePassword'], $config['secondaryMcoreUrl']);
	if ($tblmCoreConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM client
	 **************************************************/
	if (RMENABLED){
		try {
			$rmApiClient = new SoapClient('http://'.$config['primaryRmUrl'])	;
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryRmUrl']);
			$returnObj['responseCode'] = F_RM_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'RM Error Occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * delete session client
	 **************************************************/
	try {
		$deleteSessionClient = new SoapClient('http://'.$config['primaryDeleteSessionUrl']);
	} catch (Exception $e) {
		$error = json_encode($e);
		$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		$end = strpos($error, '","faultcode"');
		$err = substr($error, $start, $end - $start);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryDeleteSessionUrl']);
		$returnObj['responseCode'] = F_SESSION_API_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Delete session client connection error occurred';
		return $returnObj;
	}
	if ($config['useSecondary']) {
		try {
			$deleteSessionClientSecondary = new SoapClient('http://'.$config['secondaryDeleteSessionUrl']);
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_SECONDARY_API_CONNECT_ERROR.'] Unable to connect to '.
				$config['secondaryDeleteSessionUrl']);
			$returnObj['responseCode'] = F_SESSION_SECONDARY_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'Delete session client connection (secondary) error occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: username (required)
	 **************************************************/
	if ($username == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Username is required';
		return $returnObj;
	}
	// check for account existence
	$accountOld = Aaa::getSubscriberWithUsername($aaaConn, $username);
	if (!$accountOld['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$accountOld['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	if ($accountOld['data'] === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj ['replyMessage'] = 'Username not found';
		return $returnObj;
	}
	/**************************************************
	 * input-specific conditions: netaddress/cabinetname (at least one should be provided)
	 **************************************************/
	if ($netAddress == '') {
		$netAddress = null;
	}
	if ($cabinetName == '') {
		$cabinetName = null;
	}
	if ($range == '') {
		$range = null;
	}
	if (is_null($netAddress)) {
		if (is_null($cabinetName)) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRES_NET_ADDRESS.'] No net address or cabinet name provided');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRES_NET_ADDRESS);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_REQUIRES_NET_ADDRESS;
			$returnObj['replyMessage'] = 'Multi-static IP address is required';
			return $returnObj;
		}
		// check for cabinet name existence
		$cabinetObj = Aaa::getCabinet($mysqlConn, $cabinetName);
		if (!$cabinetObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$cabinetObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		if ($cabinetObj['data'] === false || empty($cabinetObj['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CABINET_NAME_UNLISTED_2.'] Cabinet name does not exist');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_CABINET_NAME_UNLISTED_2);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_CABINET_NAME_UNLISTED_2;
			$returnObj['replyMessage'] = 'Cabinet name does not exist';
			return $returnObj;
		}
		// check for availability of net address with given cabinet name
		$netObj = Aaa::getNetAddressWithCabinet($aaaConn, $mysqlConn, $cabinetName, $range);
		if (!$netObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$netObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		if ($netObj['data'] === false || empty($netObj['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_NO_AVAILABLE_NET_ADDRESS.'] No available net address');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_NO_AVAILABLE_NET_ADDRESS);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_NO_AVAILABLE_NET_ADDRESS;
			$returnObj['replyMessage'] = 'No available multi-static IP address';
			return $returnObj;
		}
		$netAddress = $netObj['data']['NETADDRESS'];
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Selected net address: '.$netAddress);
	} else {
		// check for net address format
		$netAddressParts = explode('/', $netAddress);
		if (count($netAddressParts) != 2 || filter_var($netAddressParts[0], FILTER_VALIDATE_IP) === false) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_INVALID_NET_ADDRESS_TO_ADD.'] Invalid net address');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_INVALID_NET_ADDRESS_TO_ADD);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_INVALID_NET_ADDRESS_TO_ADD;
			$returnObj['replyMessage'] = 'Multi-static IP does not exist';
			return $returnObj;
		}
		// check for net address existence
		$netObj = Aaa::getNetAddress($aaaConn, $netAddress);
		if (!$netObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$netObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		if ($netObj['data'] === false) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNLISTED_NET_ADDRESS_TO_ADD.'] Net address does not exist');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNLISTED_NET_ADDRESS_TO_ADD);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_UNLISTED_NET_ADDRESS_TO_ADD;
			$returnObj['replyMessage'] = 'Multi-static IP does not exist';
			return $returnObj;
		}
		// check for net address availability
		if ($netObj['data']['NETUSED'] == 'Y') {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNAVAILABLE_NET_ADDRESS_TO_ADD.'] Net address is not available');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNAVAILABLE_NET_ADDRESS_TO_ADD);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_UNAVAILABLE_NET_ADDRESS_TO_ADD;
			$returnObj['replyMessage'] = 'Multi-static IP Address is not available';
			return $returnObj;
		}
	}
	$oldIpv6Address = $accountOld['data']['RBADDITIONALSERVICE4'] == '' || is_null($accountOld['data']['RBADDITIONALSERVICE4']) ? null : $accountOld['data']['RBADDITIONALSERVICE4'];
	$oldIpAddress = $accountOld['data']['RBIPADDRESS'] == '' || is_null($accountOld['data']['RBIPADDRESS']) ? null : $accountOld['data']['RBIPADDRESS'];
	$oldNetAddress = $accountOld['data']['RBMULTISTATIC'] = '' | is_null($accountOld['data']['RBMULTISTATIC']) ? null : $accountOld['data']['RBMULTISTATIC'];
	$currentStatus = strtoupper($accountOld['data']['CUSTOMERSTATUS']) == 'ACTIVE' ? 'A' : 'D';
	// check for ip address existence
	if (is_null($oldIpAddress)) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_HAS_NO_STATIC_IP_ADDRESS.'] Account has no ip address');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_HAS_NO_STATIC_IP_ADDRESS);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_HAS_NO_STATIC_IP_ADDRESS;
		$returnObj['replyMessage'] = 'Account has no static IP address';
		return $returnObj;
	}
	/**************************************************
	 * update net address
	 **************************************************/
	$customerReplyItem = Aaa::generateCustomerReplyItemValue($oldIpv6Address, $oldIpAddress, $netAddress);
	$aaaUpdate = Aaa::updateSubscriberIPv6IpNetAddress($aaaConn, $username, $oldIpv6Address, $oldIpAddress, $netAddress, $customerReplyItem);
	// aaa net address update failed
	if (!$aaaUpdate['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$aaaUpdate['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	// aaa net address update succesful
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_NET_ADDRESS_ADDED.'] Net address updated');
	$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_NET_ADDRESS_ADDED);
	if (!$lrq['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
	}
	//marn new net address
	$mark = Aaa::markNetAddress($aaaConn, $netAddress, true, $username, $currentStatus);
	if (!$mark['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$mark['error']);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to mark net address: '.$netAddress);
	} else {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Marked as used: '.$netAddress);
	}
	// unmark old net address (if any)
	if (!is_null($oldNetAddress)) {
		$unmark = Aaa::markNetAddress($aaaConn, $oldNetAddress, false, $username, $currentStatus);
		if (!$unmark['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$unmark['error']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to unmark net address: '.$oldNetAddress);
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unmarked net address: '.$oldNetAddress);
		}
	}
	// update rbenabled and fap if account previously had no net address
	$aaaUpdate = Aaa::updateSubscriberRbenabled($aaaConn, $username, 'No');
	if (!$aaaUpdate['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$aaaUpdate['error']);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to update RBENABLED');
	} else {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated RBENABLED');
		// update rm param3
		$nodes = array('PARAM3' => 'N');
		$rmUpdate = Rm::wsUpdateSubscriberProfile($rmApiClient, $username, null, $nodes, null, null);
		// rm update successful
		if (intval($rmUpdate['responseCode']) == 200) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated PARAM3');
		// rm update failed
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'RM error: '.$rmUpdate['responseCode'].'|'.$rmUpdate['responseMessage']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to update PARAM3');
		}
	}
	// delete session
	$sessions = Aaa::getSubscriberSessions(
		$config['useTblm'] ? $tblmConcConn : $aaaConn,
		$config['useTblm'] ? $tblmConcConn2 : $aaaConn2,
		$config['useTblm'],
		$username);
	if (!$sessions['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$sessions['error']);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to get sessions');
	} else {
		// account has no session
		if (empty($sessions['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found 0 sessions');
		// account has session(s)
		} else {
			$sessionCount = count($sessions['data']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found '.$sessionCount.' sessions');
			// delete sessions: 2x api + tblmconcurrentusers + tblmcoresessions
			for ($i = 0; $i < $sessionCount; $i++) {
				$session = $sessions['data'][$i];
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
				$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClient, $session, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
				$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
				$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				if ($config['useSecondary']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
					$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClientSecondary, $session, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
					$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn2, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
					$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn2, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				}
			}
		}
	}
	return $returnObj;
}
function wsRemoveIPAddress($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsRemoveIPAddress';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$username = trim($param['username']);
	$returnObj = array(
		'responseCode' => F_IP_ADDRESS_REMOVED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$aaaConn2 = oci_connect($config['secondarySessionUsername'], $config['secondarySessionPassword'], $config['secondarySessionUrl']);
	if ($aaaConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to AAA (secondary) database');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA (secondary) database error occurred';
		return $returnObj;
	}
	$tblmConcConn = oci_connect($config['primaryMconcurrentUsername'], $config['primaryMconcurrentPassword'], $config['primaryMconcurrentUrl']);
	if ($tblmConcConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmConcConn2 = oci_connect($config['secondaryMconcurrentUsername'], $config['secondaryMconcurrentPassword'], $config['secondaryMconcurrentUrl']);
	if ($tblmConcConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn = oci_connect($config['primaryMcoreUsername'], $config['primaryMcorePassword'], $config['primaryMcoreUrl']);
	if ($tblmCoreConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn2 = oci_connect($config['secondaryMcoreUsername'], $config['secondaryMcorePassword'], $config['secondaryMcoreUrl']);
	if ($tblmCoreConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM client
	 **************************************************/
	if (RMENABLED){
		try {
			$rmApiClient = new SoapClient('http://'.$config['primaryRmUrl'])	;
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryRmUrl']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", $err);
			$returnObj['responseCode'] = F_RM_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'RM Error Occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * delete session client
	 **************************************************/
	try {
		$deleteSessionClient = new SoapClient('http://'.$config['primaryDeleteSessionUrl']);
	} catch (Exception $e) {
		$error = json_encode($e);
		$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		$end = strpos($error, '","faultcode"');
		$err = substr($error, $start, $end - $start);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryDeleteSessionUrl']);
		$returnObj['responseCode'] = F_SESSION_API_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Delete session client connection error occurred';
		return $returnObj;
	}
	if ($config['useSecondary']) {
		try {
			$deleteSessionClientSecondary = new SoapClient('http://'.$config['secondaryDeleteSessionUrl']);
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_SECONDARY_API_CONNECT_ERROR.'] Unable to connect to '.
				$config['secondaryDeleteSessionUrl']);
			$returnObj['responseCode'] = F_SESSION_SECONDARY_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'Delete session client connection (secondary) error occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: username (required)
	 **************************************************/
	if ($username == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Username is required';
		return $returnObj;
	}
	// check for account existence
	$accountOld = Aaa::getSubscriberWithUsername($aaaConn, $username);
	if (!$accountOld['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$accountOld['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$accountOldData = $accountOld['data'];
	if ($accountOldData === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj ['replyMessage'] = 'Username not found';
		return $returnObj;
	}
	$oldStatus = strtoupper($accountOld['data']['CUSTOMERSTATUS']) == 'ACTIVE' ? 'A' : 'D';
	// check if account has ip address
	$oldIpAddress = $accountOldData['RBIPADDRESS'] == '' || is_null($accountOldData['RBIPADDRESS']) ? null : $accountOldData['RBIPADDRESS'];
	if (is_null($oldIpAddress) || empty($oldIpAddress) || $oldIpAddress == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_HAS_NO_STATIC_IP_ADDRESS_2.'] Username does not have an ip address');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_HAS_NO_STATIC_IP_ADDRESS_2);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_HAS_NO_STATIC_IP_ADDRESS_2;
		$returnObj['replyMessage'] = 'Username does not have an IP address';
		return $returnObj;
	}
	// check if account has net address
	$oldNetAddress = $accountOldData['RBMULTISTATIC'] == '' || is_null($accountOldData['RBMULTISTATIC']) ? null : $accountOldData['RBMULTISTATIC'];
	if (!is_null($oldNetAddress) || !empty($oldNetAddress) || $oldNetAddress != '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REMOVING_IP_WHILE_NET_STILL_ATTACHED.'] Removing ip address while a '.
			'net address is still attached');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REMOVING_IP_WHILE_NET_STILL_ATTACHED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REMOVING_IP_WHILE_NET_STILL_ATTACHED;
		$returnObj['replyMessage'] = 'Multi-static IP address still exists';
		return $returnObj;
	}
	// check if account has ipv6 address
	$oldIpv6Address = $accountOldData['RBADDITIONALSERVICE4'] == '' || is_null($accountOldData['RBADDITIONALSERVICE4']) ? null : $accountOldData['RBADDITIONALSERVICE4'];
	// update account info
	$customerReplyItem = Aaa::generateCustomerReplyItemValue($oldIpv6Address, null, $oldNetAddress);
	$aaaUpdate = Aaa::updateSubscriberIPv6IpNetAddress($aaaConn, $username, $oldIpv6Address, null, $oldNetAddress, $customerReplyItem);
	// account update info failed
	if (!$aaaUpdate['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$aaaUpdate['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated in AAA');
	// account update info success, unmark old ip address
	$unmark = Aaa::markIPAddress($aaaConn, $oldIpAddress, false, $username, $oldStatus);
	if (!$unmark['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$unmark['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_IP_ADDRESS_REMOVED.'] IP address unmarked: '.$oldIpAddress);
	$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_IP_ADDRESS_REMOVED);
	if (!$lrq['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
	}
	// delete session
	$sessions = Aaa::getSubscriberSessions(
		$config['useTblm'] ? $tblmConcConn : $aaaConn,
		$config['useTblm'] ? $tblmConcConn2 : $aaaConn2,
		$config['useTblm'],
		$username);
	if (!$sessions['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$sessions['error']);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to get sessions');
	} else {
		// account has no session
		if (empty($sessions['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found 0 sessions');
		// account has session(s)
		} else {
			$sessionCount = count($sessions['data']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found '.$sessionCount.' sessions');
			// delete sessions: 2x api + tblmconcurrentusers + tblmcoresessions
			for ($i = 0; $i < $sessionCount; $i++) {
				$session = $sessions['data'][$i];
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
				$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClient, $session, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
				$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
				$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				if ($config['useSecondary']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
					$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClientSecondary, $session, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
					$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn2, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
					$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn2, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				}
			}
		}
	}
	return $returnObj;
}
function wsRemoveIPv6Address($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsRemoveIPv6Address';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$username = trim($param['username']);
	$returnObj = array(
		'responseCode' => F_IPV6_ADDRESS_REMOVED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$aaaConn2 = oci_connect($config['secondarySessionUsername'], $config['secondarySessionPassword'], $config['secondarySessionUrl']);
	if ($aaaConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to AAA (secondary) database');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA (secondary) database error occurred';
		return $returnObj;
	}
	$tblmConcConn = oci_connect($config['primaryMconcurrentUsername'], $config['primaryMconcurrentPassword'], $config['primaryMconcurrentUrl']);
	if ($tblmConcConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmConcConn2 = oci_connect($config['secondaryMconcurrentUsername'], $config['secondaryMconcurrentPassword'], $config['secondaryMconcurrentUrl']);
	if ($tblmConcConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn = oci_connect($config['primaryMcoreUsername'], $config['primaryMcorePassword'], $config['primaryMcoreUrl']);
	if ($tblmCoreConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn2 = oci_connect($config['secondaryMcoreUsername'], $config['secondaryMcorePassword'], $config['secondaryMcoreUrl']);
	if ($tblmCoreConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM client
	 **************************************************/
	if (RMENABLED){
		try {
			$rmApiClient = new SoapClient('http://'.$config['primaryRmUrl'])	;
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryRmUrl']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", $err);
			$returnObj['responseCode'] = F_RM_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'RM Error Occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * delete session client
	 **************************************************/
	try {
		$deleteSessionClient = new SoapClient('http://'.$config['primaryDeleteSessionUrl']);
	} catch (Exception $e) {
		$error = json_encode($e);
		$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		$end = strpos($error, '","faultcode"');
		$err = substr($error, $start, $end - $start);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryDeleteSessionUrl']);
		$returnObj['responseCode'] = F_SESSION_API_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Delete session client connection error occurred';
		return $returnObj;
	}
	if ($config['useSecondary']) {
		try {
			$deleteSessionClientSecondary = new SoapClient('http://'.$config['secondaryDeleteSessionUrl']);
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_SECONDARY_API_CONNECT_ERROR.'] Unable to connect to '.
				$config['secondaryDeleteSessionUrl']);
			$returnObj['responseCode'] = F_SESSION_SECONDARY_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'Delete session client connection (secondary) error occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: username (required)
	 **************************************************/
	if ($username == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Username is required';
		return $returnObj;
	}
	// check for account existence
	$accountOld = Aaa::getSubscriberWithUsername($aaaConn, $username);
	if (!$accountOld['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$accountOld['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$accountOldData = $accountOld['data'];
	if ($accountOldData === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj ['replyMessage'] = 'Username not found';
		return $returnObj;
	}
	$oldStatus = strtoupper($accountOld['data']['CUSTOMERSTATUS']) == 'ACTIVE' ? 'A' : 'D';
	// check if account has ipv6 address
	$oldIpv6Address = $accountOldData['RBADDITIONALSERVICE4'];
	if (is_null($oldIpv6Address) || empty($oldIpv6Address) || $oldIpv6Address == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_HAS_NO_IPV6_ADDRESS.'] Username does not have an ipv6 address');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_HAS_NO_IPV6_ADDRESS);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_HAS_NO_IPV6_ADDRESS;
		$returnObj['replyMessage'] = 'Username does not have an IPv6 address';
		return $returnObj;
	}
	$oldIpAddress = $accountOldData['RBIPADDRESS'] == '' || is_null($accountOldData['RBIPADDRESS']) ? null : $accountOldData['RBIPADDRESS'];
	$oldNetAddress = $accountOldData['RBMULTISTATIC'] == '' || is_null($accountOldData['RBMULTISTATIC']) ? null : $accountOldData['RBMULTISTATIC'];
	// update account info
	$customerReplyItem = Aaa::generateCustomerReplyItemValue(null, $oldIpAddress, $oldNetAddress);
	$aaaUpdate = Aaa::updateSubscriberIPv6IpNetAddress($aaaConn, $username, null, $oldIpAddress, $oldNetAddress, $customerReplyItem);
	// account update info failed
	if (!$aaaUpdate['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$aaaUpdate['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated in AAA');
	// account update info success, unmark old ipv6 address
	$unmark = Aaa::markIPv6Address($aaaConn, $oldIpv6Address, false, $username, $oldStatus);
	if (!$unmark['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$unmark['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_IPV6_ADDRESS_REMOVED.'] IPv6 address unmarked: '.$oldIpv6Address);
	$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_IPV6_ADDRESS_REMOVED);
	if (!$lrq['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
	}
	// delete session
	$sessions = Aaa::getSubscriberSessions(
		$config['useTblm'] ? $tblmConcConn : $aaaConn,
		$config['useTblm'] ? $tblmConcConn2 : $aaaConn2,
		$config['useTblm'],
		$username);
	if (!$sessions['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$sessions['error']);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to get sessions');
	} else {
		// account has no session
		if (empty($sessions['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found 0 sessions');
		// account has session(s)
		} else {
			$sessionCount = count($sessions['data']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found '.$sessionCount.' sessions');
			// delete sessions: 2x api + tblmconcurrentusers + tblmcoresessions
			for ($i = 0; $i < $sessionCount; $i++) {
				$session = $sessions['data'][$i];
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
				$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClient, $session, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
				$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
				$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				if ($config['useSecondary']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
					$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClientSecondary, $session, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
					$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn2, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
					$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn2, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				}
			}
		}
	}
	return $returnObj;
}
function wsRemoveNetAddress($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsRemoveNetAddress';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$username = trim($param['username']);
	$returnObj = array(
		'responseCode' => F_NET_ADDRESS_REMOVED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$aaaConn2 = oci_connect($config['secondarySessionUsername'], $config['secondarySessionPassword'], $config['secondarySessionUrl']);
	if ($aaaConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to AAA (secondary) database');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA (secondary) database error occurred';
		return $returnObj;
	}
	$tblmConcConn = oci_connect($config['primaryMconcurrentUsername'], $config['primaryMconcurrentPassword'], $config['primaryMconcurrentUrl']);
	if ($tblmConcConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmConcConn2 = oci_connect($config['secondaryMconcurrentUsername'], $config['secondaryMconcurrentPassword'], $config['secondaryMconcurrentUrl']);
	if ($tblmConcConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCONCURRENTUSERS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn = oci_connect($config['primaryMcoreUsername'], $config['primaryMcorePassword'], $config['primaryMcoreUrl']);
	if ($tblmCoreConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to primary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$tblmCoreConn2 = oci_connect($config['secondaryMcoreUsername'], $config['secondaryMcorePassword'], $config['secondaryMcoreUrl']);
	if ($tblmCoreConn2 === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_SECONDARY_DB_CONNECT_ERROR.'] No connection to secondary TBLMCORESESSIONS table');
		$returnObj['responseCode'] = F_ORACLE_SECONDARY_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM client
	 **************************************************/
	if (RMENABLED){
		try {
			$rmApiClient = new SoapClient('http://'.$config['primaryRmUrl'])	;
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryRmUrl']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", $err);
			$returnObj['responseCode'] = F_RM_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'RM Error Occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * delete session client
	 **************************************************/
	try {
		$deleteSessionClient = new SoapClient('http://'.$config['primaryDeleteSessionUrl']);
	} catch (Exception $e) {
		$error = json_encode($e);
		$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		$end = strpos($error, '","faultcode"');
		$err = substr($error, $start, $end - $start);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryDeleteSessionUrl']);
		$returnObj['responseCode'] = F_SESSION_API_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Delete session client connection error occurred';
		return $returnObj;
	}
	if ($config['useSecondary']) {
		try {
			$deleteSessionClientSecondary = new SoapClient('http://'.$config['secondaryDeleteSessionUrl']);
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SESSION_SECONDARY_API_CONNECT_ERROR.'] Unable to connect to '.
				$config['secondaryDeleteSessionUrl']);
			$returnObj['responseCode'] = F_SESSION_SECONDARY_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'Delete session client connection (secondary) error occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: username (required)
	 **************************************************/
	if ($username == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Username is required';
		return $returnObj;
	}
	// check for account existence
	$accountOld = Aaa::getSubscriberWithUsername($aaaConn, $username);
	if (!$accountOld['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$accountOld['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$accountOldData = $accountOld['data'];
	if ($accountOldData === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj ['replyMessage'] = 'Username not found';
		return $returnObj;
	}
	// check if account has net address
	$oldNetAddress = is_null($accountOldData['RBMULTISTATIC']) || empty($accountOldData['RBMULTISTATIC']) || $accountOldData['RBMULTISTATIC'] == '' ?
		null : $accountOldData['RBMULTISTATIC'];
	if (is_null($oldNetAddress)) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_HAS_NO_NET_ADDRESS.'] Username does not have multi-static IP address');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_HAS_NO_NET_ADDRESS);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_HAS_NO_NET_ADDRESS;
		$returnObj['replyMessage'] = 'Username does not have multi-static IP address';
		return $returnObj;
	}
	$oldIpv6Address = is_null($accountOldData['RBADDITIONALSERVICE4']) || empty($accountOldData['RBADDITIONALSERVICE4']) || $accountOldData['RBADDITIONALSERVICE4'] == '' ?
		null : $accountOldData['RBADDITIONALSERVICE4'];
	$oldIpAddress = is_null($accountOldData['RBIPADDRESS']) || empty($accountOldData['RBIPADDRESS']) || $accountOldData['RBIPADDRESS'] == '' ?
		null : $accountOldData['RBIPADDRESS'];
	$oldStatus = strtoupper($accountOld['data']['CUSTOMERSTATUS']) == 'ACTIVE' ? 'A' : 'D';
	// update account info
	$customerReplyItem = Aaa::generateCustomerReplyItemValue($oldIpv6Address, $oldIpAddress, null);
	$aaaUpdate = Aaa::updateSubscriberIPv6IpNetAddress($aaaConn, $username, $oldIpv6Address, $oldIpAddress, null, $customerReplyItem);
	// account update info failed
	if (!$aaaUpdate['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$aaaUpdate['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated in AAA');
	// account update info success, unmark old net address
	$unmark = Aaa::markNetAddress($aaaConn, $oldNetAddress, false, $username, $oldStatus);
	if (!$unmark['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$unmark['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_NET_ADDRESS_REMOVED.'] Net address unmarked: '.$oldNetAddress);
	$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_NET_ADDRESS_REMOVED);
	if (!$lrq['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
	}
	// update rbenabled
	$aaaUpdate = Aaa::updateSubscriberRbenabled($aaaConn, $username, 'Yes');
	if (!$aaaUpdate['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$aaaUpdate['error']);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to update RBENABLED');
	} else {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated RBENABLED');
		// update rm param3
		$nodes = array('PARAM3' => 'Y');
		$rmUpdate = Rm::wsUpdateSubscriberProfile($rmApiClient, $username, null, $nodes, null, null);
		// rm update successful
		if (intval($rmUpdate['responseCode']) == 200) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Updated PARAM3');
		// rm update failed
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'RM error: '.$rmUpdate['responseCode'].'|'.$rmUpdate['responseMessage']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Failed to update PARAM3');
		}
	}
	// delete session
	$sessions = Aaa::getSubscriberSessions(
		$config['useTblm'] ? $tblmConcConn : $aaaConn,
		$config['useTblm'] ? $tblmConcConn2 : $aaaConn2,
		$config['useTblm'],
		$username);
	if (!$sessions['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Query error: '.$sessions['error']);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to get sessions');
	} else {
		// account has no session
		if (empty($sessions['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found 0 sessions');
		// account has session(s)
		} else {
			$sessionCount = count($sessions['data']);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Found '.$sessionCount.' sessions');
			// delete sessions: 2x api + tblmconcurrentusers + tblmcoresessions
			for ($i = 0; $i < $sessionCount; $i++) {
				$session = $sessions['data'][$i];
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
				$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClient, $session, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
				$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
				$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn, $username);
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				if ($config['useSecondary']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting via api');
					$result = Aaa::deleteSubscriberSessionUsingClient($deleteSessionClientSecondary, $session, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCONCURRENTUSERS');
					$result = Aaa::deleteSubscriberSessionAtTblmConcurrentusers($tblmConcConn2, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Deleting at TBLMCORESESSIONS');
					$result = Aaa::deleteSubscriberSessionAtTblmCoresessions($tblmCoreConn2, $username);
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Result: '.json_encode($result));
				}
			}
		}
	}
	return $returnObj;
}
function wsAddLocation($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsAddLocation';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$username = trim($param['username']);
	$cabinetName = trim($param['cabinetName']);
	$location = trim($param['location']);
	$returnObj = array(
		'responseCode' => F_LOCATION_ADDED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * RM client
	 **************************************************/
	if (RMENABLED){
		try {
			$rmApiClient = new SoapClient('http://'.$config['primaryRmUrl'])	;
		} catch (Exception $e) {
			$error = json_encode($e);
			$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
			$end = strpos($error, '","faultcode"');
			$err = substr($error, $start, $end - $start);
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_RM_API_CONNECT_ERROR.'] Unable to connect to '.$config['primaryRmUrl']);
			$returnObj['responseCode'] = F_RM_API_CONNECT_ERROR;
			$returnObj['replyMessage'] = 'RM Error Occurred';
			return $returnObj;
		}
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: username (required)
	 **************************************************/
	if ($username == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Username is required';
		return $returnObj;
	}
	// check for account existence
	$accountOld = Aaa::getSubscriberWithUsername($aaaConn, $username);
	if (!$accountOld['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$accountOld['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	if ($accountOld['data'] === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj ['replyMessage'] = 'Username not found';
		return $returnObj;
	}
	/**************************************************
	 * input-specific conditions: cabinetname/location (at least one should be provided)
	 **************************************************/
	if ($location == '') {
		$location = null;
	}
	if ($cabinetName == '') {
		$cabinetName = null;
	}
	if (is_null($location)) {
		if (is_null($cabinetName)) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRES_LOCATION.'] No location or cabinet name provided');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRES_LOCATION);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_REQUIRES_LOCATION;
			$returnObj['replyMessage'] = 'Location is required';
			return $returnObj;
		}
		// check for cabinet existence
		$locationObj = Aaa::getLocationObjWithCabinetName($mysqlConn, $cabinetName);
		if (!$locationObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$locationObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		if ($locationObj['data'] === false || empty($locationObj['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CABINET_NAME_UNLISTED_3.'] Cabinet name doest not exist');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_CABINET_NAME_UNLISTED_3);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_CABINET_NAME_UNLISTED_3;
			$returnObj['replyMessage'] = 'Cabinet name does not exist';
			return $returnObj;
		}
		$usernameExtra = $locationObj['data']['rm_location'];
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Will migrate to: '.$usernameExtra);
	} else {
		//check for location existence
		$locationObj = Aaa::getLocationObjWithNasCode($mysqlConn, $location);
		if (!$locationObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$locationObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		if ($locationObj['data'] === false || empty($locationObj['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNLISTED_LOCATION.'] Location does not exist');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNLISTED_LOCATION);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_UNLISTED_LOCATION;
			$returnObj['replyMessage'] = 'Location does not exist';
			return $returnObj;
		}
		$usernameExtra = $locationObj['data'][0]['rm_location'];
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Will migrate to: '.$usernameExtra);
	}

	if (RMENABLED){
		$rmMigrate = Rm::wsMigrateSubscriber($rmApiClient, $username, $username.$usernameExtra);
		if (intval($rmMigrate['responseCode']) == 200) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_LOCATION_ADDED.'] '.$rmUsername.' migrated to '.$rmUsername.$usernameExtra);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_LOCATION_ADDED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_GENERIC_RM_ERROR.'] Failed to migrate to '.
				$rmUsername.$usernameExtra.'|'.$rmMigrate['responseCode'].'|'.$rmMigrate['responseMessage']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_GENERIC_RM_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_GENERIC_RM_ERROR;
			$returnObj['replyMessage'] = 'RM error occurred';
		}
	}
	return $returnObj;
}
function wsSearchAccount($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsSearchAccount';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$username = trim($param['username']);
	$serviceNumber = trim($param['serviceNumber']);
	$returnObj = array(
		'responseCode' => F_SUBSCRIBER_INFO_FOUND,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200, 300), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions:
	 * - username (required if serviceNumber is empty not provided)
	 * - serviceNumber (can be provided alone or with username)
	 **************************************************/
	// no username and serviceNumber provided
	if ($username == '' && $serviceNumber == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username and/or serviceNumber');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Username is required because there is no value on serviceNumber field';
		return $returnObj;
	// only serviceNumber provided
	} else if ($username == '' && $serviceNumber != '') {
		// check for account with given serviceNumber existence
		$accountObj = Aaa::getSubscriberWithServiceNumber($aaaConn, $serviceNumber);
		if (!$accountObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$accountObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		if (empty($accountObj['data']) || $accountObj['data'] === false || is_null($accountObj['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SERVICE_NUMBER_NOT_FOUND.'] Service number not found');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_SERVICE_NUMBER_NOT_FOUND);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_SERVICE_NUMBER_NOT_FOUND;
			$returnObj['replyMessage'] = 'Service number not found';
			return $returnObj;
		}
	// only username provided
	} else if ($username != '' && $serviceNumber == '') {
		// check for account with given username
		$accountObj = Aaa::getSubscriberWithUsername($aaaConn, $username);
		if (!$accountObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$accountObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		if (empty($accountObj['data']) || $accountObj['data'] === false || is_null($accountObj['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
			$returnObj['replyMessage'] = 'Username not found';
			return $returnObj;
		}
	// username and serviceNumber provided
	} else if ($username != '' && $serviceNumber != '') {
		// check for account with provided username and serviceNumber
		$accountObj = Aaa::getSubscriberWithUserameAndServiceNumber($aaaConn, $username, $serviceNumber);
		if (!$accountObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$accountObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'AAA database error occurred';
			return $returnObj;
		}
		if (empty($accountObj['data']) || $accountObj['data'] === false || is_null($accountObj['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_AND_SERVICE_NUMBER_NOT_FOUND.'] Username (and service number) not found');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_AND_SERVICE_NUMBER_NOT_FOUND);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_USERNAME_AND_SERVICE_NUMBER_NOT_FOUND;
			$returnObj['replyMessage'] = 'Username not found';
			return $returnObj;
		}
	}
	$account = $accountObj['data'];
	$returnObj['username'] = $account['USER_IDENTITY'];
	$returnObj['name'] = is_null($account['RBCUSTOMERNAME']) || empty($account['RBCUSTOMERNAME']) || $account['RBCUSTOMERNAME'] == '' ?
		'' : $account['RBCUSTOMERNAME'];
	$returnObj['serviceNumber'] = $account['RBSERVICENUMBER'];
	$returnObj['status'] = $account['CUSTOMERSTATUS'];
	$returnObj['servicePlan'] = is_null($account['RADIUSPOLICY']) || empty($account['RADIUSPOLICY']) || $account['RADIUSPOLICY'] == '' ?
		str_replace('~', '-', $account['RBACCOUNTPLAN']) : str_replace('~', '-', $account['RADIUSPOLICY']);
	$returnObj['customerType'] = $account['CUSTOMERTYPE'];
	$returnObj['redirected'] = $account['RBENABLED'];
	$returnObj['staticIPv6'] = is_null($account['RBADDITIONALSERVICE4']) || empty($account['RBADDITIONALSERVICE4']) || $account['RBADDITIONALSERVICE4'] == '' ?
		'' : $account['RBADDITIONALSERVICE4'];
	$returnObj['staticIP'] = is_null($account['RBIPADDRESS']) || empty($account['RBIPADDRESS']) || $account['RBIPADDRESS'] == '' ?
		'' : $account['RBIPADDRESS'];
	$returnObj['multiIP'] = is_null($account['RBMULTISTATIC']) || empty($account['RBMULTISTATIC']) || $account['RBMULTISTATIC'] == '' ?
		'' : $account['RBMULTISTATIC'];
	$returnObj['orderNumber'] = is_null($account['RBORDERNUMBER']) || empty($account['RBORDERNUMBER']) || $account['RBORDERNUMBER'] == '' ?
		'' : $account['RBORDERNUMBER'];
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_SUBSCRIBER_INFO_FOUND.'] Account info returned');
	$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_SUBSCRIBER_INFO_FOUND);
	if (!$lrq['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
	}
	return $returnObj;
}
function wsSearchCabinet($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsSearchCabinet';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$cabinetName = trim($param['cabinetName']);
	$vlan = trim($param['vlan']);
	$returnObj = array(
		'responseCode' => F_CABINET_FOUND,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- wsSearchAccount response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- wsSearchAccount response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions:
	 * - vlan (required if cabinetName is empty/not provided)
	 * - cabinetName (this takes priority)
	 **************************************************/
	// cabinetName is provided, vlan is ignored (even if there's a value)
	if ($cabinetName != '') {
		$cabinetObj = Aaa::getCabinetWithName($mysqlConn, $cabinetName);
		if (!$cabinetObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_QUERY_ERROR.'] Query error: '.$cabinetObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_MYSQL_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_MYSQL_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'Mysql database error occurred';
			return $returnObj;
		}
		if (empty($cabinetObj['data']) || $cabinetObj['data'] === false || is_null($cabinetObj['data'])) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CABINET_UNLISTED.'] Cabinet not found');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_CABINET_UNLISTED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_CABINET_UNLISTED;
			$returnObj['replyMessage'] = 'Cabinet not found';
			return $returnObj;
		}
	} else {
		// vlan is provided
		if ($vlan != '') {
			$cabinetObj = Aaa::getCabinetWithVlan($mysqlConn, $vlan);
			if (!$cabinetObj['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_QUERY_ERROR.'] Query error: '.$cabinetObj['error']);
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_MYSQL_DB_QUERY_ERROR);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				$returnObj['responseCode'] = F_MYSQL_DB_QUERY_ERROR;
				$returnObj['replyMessage'] = 'Mysql database error occurred';
				return $returnObj;
			}
			if (empty($cabinetObj['data']) || $cabinetObj['data'] === false || is_null($cabinetObj['data'])) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CABINET_UNLISTED.'] Cabinet not found');
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_CABINET_UNLISTED);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				$returnObj['responseCode'] = F_CABINET_UNLISTED;
				$returnObj['replyMessage'] = 'Cabinet not found';
				return $returnObj;
			}
		// cabinetName and vlan is not provided
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: cabinetName or vlan');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
			$returnObj['replyMessage'] = 'Either a cabinet name or vlan is required';
			return $returnObj;
		}
	}
	$returnObj['cabinetName'] = $cabinetObj['data']['name'];
	$returnObj['location'] = $cabinetObj['data']['location'];
	$returnObj['vlan'] = $cabinetObj['data']['vlan'];
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CABINET_FOUND.'] Cabinet info returned');
	$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_CABINET_FOUND);
	if (!$lrq['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
	}
	return $returnObj;
}
function wsSearchVOD($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsSearchVOD';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." called: ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$username = trim($param['username']);
	$returnObj = array(
		'responseCode' => F_VOD_INFO_FOUND,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file. {username: '.$username.'}');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database. {username: '.$username.'}');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * VOD client
	 **************************************************/
	try {
		$vodClient = new SoapClient('http://'.$config['primaryVodUrl']);
	} catch (Exception $e) {
		$error = json_encode($e);
		$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		$end = strpos($error, '","faultcode"');
		$err = substr($error, $start, $end - $start);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_CLIENT_ERROR.'] Unable to connect to '.$config['primaryVodUrl']);
		$returnObj['responseCode'] = F_VOD_CLIENT_ERROR;
		$returnObj['replyMessage'] = 'RM error occurred';
		return $returnObj;
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: username (required)
	 **************************************************/
	if ($username == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Username is required';
		return $returnObj;
	}
	// check for account existence
	$accountObj = Aaa::getSubscriberWithUsername($aaaConn, $username);
	if (!$accountObj['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$accountObj['sql']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	if ($accountObj['data'] === false || empty($accountObj['data']) || is_null($accountObj['data'])) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj['replyMessage'] = 'Username not found';
		return $returnObj;
	}
	$param = array('subscriberId' => $username);
	try {
		$addOns = $vodClient->wsListAddOnSubscriptions($param);
	} catch (Exception $e) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ERROR_AT_WSLISTADDONSUBSCRIPTIONS.'] Error on wsListAddOnSubscriptions call: '.json_encode($e));
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ERROR_AT_WSLISTADDONSUBSCRIPTIONS);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ERROR_AT_WSLISTADDONSUBSCRIPTIONS;
		$returnObj['replyMessage'] = 'RM error occurred';
		return $returnObj;
	}
	$addOnResponseCode = $addOns->AddOnSubscriptionRes->responseCode;
	$addOnReplyMessage = $addOns->AddOnSubscriptionRes->responseMessage;
	// fetch vod info success
	if (intval($addOnResponseCode) == 200) {
		if (isset($addOns->AddOnSubscriptionRes->addOnSubscriptionDataRes)) {
			$vodData = $addOns->AddOnSubscriptionRes->addOnSubscriptionDataRes;
			$returnObj['username'] = $username;
			$returnObj['vodName'] = isset($vodData->addOnPackageName) ? $vodData->addOnPackageName : '';
			$subscriptionStartTimeValue = '';
			if (isset($vodData->subscriptionStartTime) && !is_null($vodData->subscriptionStartTime)) {
				$subscriptionStartTimeInSeconds = intval($vodData->subscriptionStartTime / 1000);
				$dateObj = new DateTime('@'.$subscriptionStartTimeInSeconds);
				$dateObj->setTimezone(new DateTimeZone("Asia/Manila"));
				$subscriptionStartTimeValue = $dateObj->format('Y-m-d H:i:s');
			}
			$returnObj['subscriptionStartTime'] = $subscriptionStartTimeValue;
			$expiryDateValue = '';
			if (isset($vodData->subscriptionEndTime) && !is_null($vodData->subscriptionEndTime)) {
				$expiryDateInSeconds = intval($vodData->subscriptionEndTime / 1000);
				$dateObj = new DateTime('@'.$expiryDateInSeconds);
				$dateObj->setTimezone(new DateTimeZone("Asia/Manila"));
				$expiryDateValue = $dateObj->format('Y-m-d H:i:s');
			}
			$returnObj['expiryDate'] = $expiryDateValue;
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_INFO_FOUND.'] Returned VOD info. {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_INFO_FOUND);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ERROR_AT_WSLISTADDONSUBSCRIPTIONS_RETURN_VALUE.'] No addOnSubscriptionDataRes '.
				'node returned (VOD not found)');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ERROR_AT_WSLISTADDONSUBSCRIPTIONS_RETURN_VALUE);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_ERROR_AT_WSLISTADDONSUBSCRIPTIONS_RETURN_VALUE;
			$returnObj['replyMessage'] = 'VOD not found';
		}
	// account not found in rm
	} else if (intval($addOnResponseCode) == 302) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_WSLISTADDONSUBSCRIPTIONS_DID_NOT_FIND_USERNAME.'] Api returned 302 (username not found). {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_WSLISTADDONSUBSCRIPTIONS_DID_NOT_FIND_USERNAME);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_WSLISTADDONSUBSCRIPTIONS_DID_NOT_FIND_USERNAME;
		$returnObj['replyMessage'] = 'Username not found';
	// other errors
	} else {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_CLIENT_ERROR.'] VOD client error occurred: '.$addOnResponseCode.'|'.$addOnReplyMessage);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_CLIENT_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_VOD_CLIENT_ERROR;
		$returnObj['replyMessage'] = 'RM error occurred';
	}
	return $returnObj;
}
function wsAddVOD($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsAddVOD';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$username = trim($param['username']);
	$addOnVodInGb = trim($param['addOnVodInGb']);
	$returnObj = array(
		'responseCode' => F_VOD_ADDED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file. {username: '.$username.'}');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection500
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database. {username: '.$username.'}');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * VOD client
	 **************************************************/
	try {
		$vodClient = new SoapClient('http://'.$config['primaryVodUrl']);
	} catch (Exception $e) {
		$error = json_encode($e);
		$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		$end = strpos($error, '","faultcode"');
		$err = substr($error, $start, $end - $start);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_CLIENT_ERROR.'] Unable to connect to '.$config['primaryVodUrl']);
		$returnObj['responseCode'] = F_VOD_CLIENT_ERROR;
		$returnObj['replyMessage'] = 'RM error occurred';
		return $returnObj;
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: username (required)
	 **************************************************/
	if ($username == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Username and addOnVodInGb are required';
		return $returnObj;
	}
	// check for account existence
	$accountObj = Aaa::getSubscriberWithUsername($aaaConn, $username);
	if (!$accountObj['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$accountObj['sql']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	if ($accountObj['data'] === false || empty($accountObj['data']) || is_null($accountObj['data'])) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj['replyMessage'] = 'Username not found';
		return $returnObj;
	}
	/**************************************************
	 * input-specific conditions: addOnVodInGb (required)
	 **************************************************/
	if ($addOnVodInGb == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: addOnVodInGb. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Required values are needed';
		return $returnObj;
	}
	$onlyNumbers = !preg_match('/[^0-9]/', $addOnVodInGb);
	if (!$onlyNumbers) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_NON_INTEGER_VOD_VALUE.'] Vod value not an integer. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_NON_INTEGER_VOD_VALUE);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_NON_INTEGER_VOD_VALUE;
		$returnObj['replyMessage'] = 'Invalid VOD value';
		return $returnObj;
	}
	// get new vod name for old
	$oldVodFormat = 'VOD_'.str_replace('-', '~', $accountObj['data']['RBACCOUNTPLAN']).'_'.$addOnVodInGb.'VB';
	$newVodObj = Aaa::getNewVodValue($mysqlConn, $oldVodFormat);
	if ($newVodObj === false || empty($newVodObj) || is_null($newVodObj)) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_NOT_FOUND.'] VOD not found: '.$oldVodFormat);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_VOD_NOT_FOUND;
		$returnObj['replyMessage'] = 'VOD not found';
		return $returnObj;
	}
	$newVodFormat = $newVodObj['data']['new_name'];
	$param = array('packageName' => $newVodFormat);
	try {
		$vodPackageObj = $vodClient->wsListAddOnPackagesFor($param);
	} catch (Exception $e) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_CLIENT_ERROR.'] Error on wsListAddOnPackagesFor call: '.json_encode($e));
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_CLIENT_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_VOD_CLIENT_ERROR;
		$returnObj['replyMessage'] = 'RM error occurred';
		return $returnObj;
	}
	// check for ListAddOnResponse node
	if (!isset($vodPackageObj->ListAddOnResponse) || is_null($vodPackageObj->ListAddOnResponse)) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_CLIENT_ERROR.'] No ListAddOnResponse node returned from wsListAddOnPackagesFor');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_CLIENT_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_VOD_CLIENT_ERROR;
		$returnObj['replyMessage'] = 'RM error occurred';
		return $returnObj;
	}
	// check for responseCode node
	$vodPackageReturn = $vodPackageObj->ListAddOnResponse;
	if (!isset($vodPackageReturn->responseCode) || is_null($vodPackageReturn->responseCode)) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_CLIENT_ERROR.'] No responseCode returned from ListAddOnResponse. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_CLIENT_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_VOD_CLIENT_ERROR;
		$returnObj['replyMessage'] = 'RM error occurred';
		return $returnObj;
	}
	$vodResponseCode = $vodPackageReturn->responseCode;
	$vodReplyMessage = isset($vodPackageReturn->responseMessage) || !is_null($vodPackageReturn->responseMessage) ? $vodPackageReturn->responseMessage : '';
	// vod package fetch failed
	if (intval($vodResponseCode) != 200) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_PACKAGE_FETCH_FAILED.'] VOD package fetch failed: '.$vodResponseCode.'|'.$vodReplyMessage);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_PACKAGE_FETCH_FAILED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_VOD_PACKAGE_FETCH_FAILED;
		$returnObj['replyMessage'] = 'VOD not found';
		return $returnObj;
	}
	// check for addOnPackageRes node
	if (!isset($vodPackageReturn->addOnPackageRes) || is_null($vodPackageReturn->addOnPackageRes)) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_CLIENT_ERROR.'] No addOnPackageRes node returned. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_CLIENT_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_VOD_CLIENT_ERROR;
		$returnObj['replyMessage'] = 'RM error occurred';
		return $returnObj;
	}
	$packageNode = $vodPackageReturn->addOnPackageRes;
	// check for addOnPackageID node
	if (!isset($packageNode->addOnPackageID) || is_null($packageNode->addOnPackageID)) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_CLIENT_ERROR.'] No addOnPackageID node returned. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_CLIENT_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_VOD_CLIENT_ERROR;
		$returnObj['replyMessage'] = 'RM error occurred';
		return $returnObj;
	}
	$vodPackageId = $packageNode->addOnPackageID;
	try {
		$param = array('subscriberId' => $username, 'vodId' => $vodPackageId);
		$addVodResult = $vodClient->wsSubscribeVoD($param);
	} catch (Exception $e) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_CLIENT_ERROR.'] Error on wsSubscribeVoD call: '.json_encode($e));
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_CLIENT_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_VOD_CLIENT_ERROR;
		$returnObj['replyMessage'] = 'RM error occurred';
		return $returnObj;
	}
	// will eventually remove this once old api url is no longer used
	if (strpos($config['primaryVodUrl'], 'SubscriberVodServices') !== false) {
		$addVodResponseCode = $addVodResult->WsSubscribeVoDResponse->responseCode;
		$addVodReplyMessage = $addVodResult->WsSubscribeVoDResponse->replyMessage;
	} else {
		$addVodResponseCode = $addVodResult->return->responseCode;
		$addVodReplyMessage = $addVodResult->return->replyMessage;
	}
	// vod subscription success
	if (intval($addVodResponseCode) == 200) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_ADDED.'] VOD applied to account. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_ADDED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
	// vod subscription failed
	} else {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_CLIENT_ERROR.'] Failed to apply vod: '.$addVodResponseCode.'|'.$addVodReplyMessage);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_CLIENT_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_VOD_CLIENT_ERROR;
		$returnObj['replyMessage'] = 'RM error occurred';
	}
	return $returnObj;
}
function wsDeleteVOD($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsDeleteVOD';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$username = trim($param['username']);
	$returnObj = array(
		'responseCode' => F_VOD_DELETED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file. {username: '.$username.'}');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database. {username: '.$username.'}');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database. {username: '.$username.'}');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * VOD client
	 **************************************************/
	try {
		$vodClient = new SoapClient('http://'.$config['primaryVodUrl']);
	} catch (Exception $e) {
		$error = json_encode($e);
		$start = strpos($error, '"faultstring":"') + strlen('"faultstring":"');
		$end = strpos($error, '","faultcode"');
		$err = substr($error, $start, $end - $start);
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_CLIENT_ERROR.'] Unable to connect to '.$config['primaryVodUrl']);
		$returnObj['responseCode'] = F_VOD_CLIENT_ERROR;
		$returnObj['replyMessage'] = 'RM error occurred';
		return $returnObj;
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: username (required)
	 **************************************************/
	if ($username == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: username. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'Username is required';
		return $returnObj;
	}
	// check for account existence
	$accountObj = Aaa::getSubscriberWithUsername($aaaConn, $username);
	if (!$accountObj['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$accountObj['sql']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	if ($accountObj['data'] === false || empty($accountObj['data']) || is_null($accountObj['data'])) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Username not found. {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj['replyMessage'] = 'Username not found';
		return $returnObj;
	}
	$param = array('subscriberId' => $username);
	try {
		$addOns = $vodClient->wsListAddOnSubscriptions($param);
	} catch (Exception $e) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_CLIENT_ERROR.'] Error on wsListAddOnSubscriptions call: '.json_encode($e));
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_CLIENT_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_VOD_CLIENT_ERROR;
		$returnObj['replyMessage'] = 'RM error occurred';
		return $returnObj;
	}
	$addOnResponseCode = $addOns->AddOnSubscriptionRes->responseCode;
	$addOnReplyMessage = $addOns->AddOnSubscriptionRes->responseMessage;
	if (intval($addOnResponseCode) == 200) {
		// account has vod
		if (isset($addOns->AddOnSubscriptionRes->addOnSubscriptionDataRes)) {
			$vodData = $addOns->AddOnSubscriptionRes->addOnSubscriptionDataRes;
			$subscriptionId = $vodData->addOnSubscriptionID;
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'VOD subscription to delete: '.$subscriptionId);
			$param = array('subscriberId' => $username, 'addOnSubscriptionID' => $subscriptionId);
			try {
				$vodDelete = $vodClient->wsChangeAddOnSubscription($param);
			} catch (Exception $e) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_CLIENT_ERROR.'] Error on wsChangeAddOnSubscription call: '.json_encode($e));
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_CLIENT_ERROR);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				$returnObj['responseCode'] = F_VOD_CLIENT_ERROR;
				$returnObj['replyMessage'] = 'RM error occurred';
				return $returnObj;
			}
			$vodDeleteResponseCode = $vodDelete->AddOnSubscriptionRes->responseCode;
			$vodDeleteReplyMessage = $vodDelete->AddOnSubscriptionRes->responseMessage;
			// failed to delete vod
			if (intval($vodDeleteResponseCode) != 200) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_CLIENT_ERROR.'] Unable to delete VOD: '.
					$vodDeleteResponseCode.'|'.$vodDeleteReplyMessage);
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_CLIENT_ERROR);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
				$returnObj['responseCode'] = F_VOD_CLIENT_ERROR;
				$returnObj['replyMessage'] = 'RM error occurred';
			// vod deleted
			} else {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_DELETED.'] VOD deleted. {username: '.$username.'}');
				$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_DELETED);
				if (!$lrq['result']) {
					writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
				}
			}
		// account has no vod
		} else {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_NOT_FOUND.'] No addOnSubscriptionDataRes node returned (VOD not found). {username: '.$username.'}');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_NOT_FOUND);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_VOD_NOT_FOUND;
			$returnObj['replyMessage'] = 'No existing VOD';
		}
	// username not found
	} else if (intval($addOnResponseCode) == 302) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_USERNAME_NOT_FOUND.'] Api returned 302 (username not found). {username: '.$username.'}');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_USERNAME_NOT_FOUND);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_USERNAME_NOT_FOUND;
		$returnObj['replyMessage'] = 'Username not found';
	// other error
	} else {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_VOD_CLIENT_ERROR.'] VOD client error occurred: '.$addOnResponseCode.'|'.$addOnReplyMessage);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_VOD_CLIENT_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_VOD_CLIENT_ERROR;
		$returnObj['replyMessage'] = 'RM error occurred';
	}
	return $returnObj;
}
function wsCheckIPAddress($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsCheckIPAddress';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$ipAddress = trim($param['ipAddress']);
	$returnObj = array(
		'responseCode' => F_IP_ADDRESS_AVAILABLE,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: ipaddress (required)
	 **************************************************/
	if ($ipAddress == '') {
		$ipAddress = null;
	}
	if (is_null($ipAddress)) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_IP_ADDRESS_REQUIRED.'] IP address is required');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_IP_ADDRESS_REQUIRED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_IP_ADDRESS_REQUIRED;
		$returnObj['replyMessage'] = 'IP address is required';
		return $returnObj;
	}
	// check ip address format
	if (filter_var($ipAddress, FILTER_VALIDATE_IP) === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_IP_ADDRESS_INVALID.'] Invalid IP address');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_IP_ADDRESS_INVALID);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_IP_ADDRESS_INVALID;
		$returnObj['replyMessage'] = 'IP address does not exist';
		return $returnObj;
	}
	// check ip address existence
	$ipObj = Aaa::getIPAddress($aaaConn, $ipAddress);
	if (!$ipObj['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$ipObj['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	if ($ipObj['data'] === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_IP_ADDRESS_UNLISTED.'] IP address does not exist');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_IP_ADDRESS_UNLISTED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_IP_ADDRESS_UNLISTED;
		$returnObj['replyMessage'] = 'IP address does not exist';
		return $returnObj;
	}
	// check ip address availability
	if (strtoupper($ipObj['data']['IPUSED']) == 'Y') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_IP_ADDRESS_UNAVAILABLE.'] IP address is used');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_IP_ADDRESS_UNAVAILABLE);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_IP_ADDRESS_UNAVAILABLE;
		$returnObj['replyMessage'] = 'Success. IP address is not available';
	} else if (strtoupper($ipObj['data']['IPUSED']) == 'N') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_IP_ADDRESS_AVAILABLE.'] IP address is available');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_IP_ADDRESS_AVAILABLE);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['replyMessage'] = 'Success. IP address is available';
	}
	return $returnObj;
}
function wsCheckIPv6Address($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsCheckIPv6Address';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$ipAddress = trim($param['ipv6Address']);
	$returnObj = array(
		'responseCode' => F_IPV6_ADDRESS_AVAILABLE,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: ipaddress (required)
	 **************************************************/
	if ($ipAddress == '') {
		$ipAddress = null;
	}
	if (is_null($ipAddress)) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_IPV6_ADDRESS_REQUIRED.'] IPv6 address is required');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_IPV6_ADDRESS_REQUIRED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_IPV6_ADDRESS_REQUIRED;
		$returnObj['replyMessage'] = 'IPv6 address is required';
		return $returnObj;
	}
	// check ip address existence
	$ipObj = Aaa::getIPv6Address($aaaConn, $ipAddress);
	if (!$ipObj['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$ipObj['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	if ($ipObj['data'] === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_IPV6_ADDRESS_UNLISTED.'] IPv6 address does not exist');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_IPV6_ADDRESS_UNLISTED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_IPV6_ADDRESS_UNLISTED;
		$returnObj['replyMessage'] = 'IPv6 address does not exist';
		return $returnObj;
	}
	// check ip address availability
	if (strtoupper($ipObj['data']['IPV6USED']) == 'Y') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_IPV6_ADDRESS_UNAVAILABLE.'] IPv6 address is used');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_IPV6_ADDRESS_UNAVAILABLE);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_IPV6_ADDRESS_UNAVAILABLE;
		$returnObj['replyMessage'] = 'Success. IPv6 address is not available';
	} else if (strtoupper($ipObj['data']['IPV6USED']) == 'N') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_IPV6_ADDRESS_AVAILABLE.'] IPv6 address is available');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_IPV6_ADDRESS_AVAILABLE);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['replyMessage'] = 'Success. IPv6 address is available';
	}
	return $returnObj;
}
function wsCheckNetAddress($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsCheckNetAddress';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$netAddress = trim($param['netAddress']);
	$returnObj = array(
		'responseCode' => F_NET_ADDRESS_AVAILABLE,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: netaddress (required)
	 **************************************************/
	if ($netAddress == '') {
		$netAddress = null;
	}
	if (is_null($netAddress)) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_NET_ADDRESS_REQUIRED.'] Net address is required');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_NET_ADDRESS_REQUIRED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_NET_ADDRESS_REQUIRED;
		$returnObj['replyMessage'] = 'Multi-static IP address is required';
		return $returnObj;
	}
	// check net address format
	$netAddressParts = explode('/', $netAddress);
	if (count($netAddressParts) != 2 || filter_var($netAddressParts[0], FILTER_VALIDATE_IP) === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_NET_ADDRESS_INVALID.'] Invalid net address');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_NET_ADDRESS_INVALID);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_NET_ADDRESS_INVALID;
		$returnObj['replyMessage'] = 'Multi-static IP address does not exist';
		return $returnObj;
	}
	// check net address existence
	$netObj = Aaa::getNetAddress($aaaConn, $netAddress);
	if (!$netObj['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$netObj['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	if ($netObj['data'] === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_NET_ADDRESS_UNLISTED.'] Net address does not exist');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_NET_ADDRESS_UNLISTED);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_NET_ADDRESS_UNLISTED;
		$returnObj['replyMessage'] = 'Multi-static IP address does not exist';
		return $returnObj;
	}
	// check net address availability
	if (strtoupper($netObj['data']['NETUSED']) == 'Y') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_NET_ADDRESS_UNAVAILABLE.'] Net address is used');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_NET_ADDRESS_UNAVAILABLE);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_NET_ADDRESS_UNAVAILABLE;
		$returnObj['replyMessage'] = 'Success. Multi-static IP address is not available';
	} else if (strtoupper($netObj['data']['NETUSED'] == 'N')) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_NET_ADDRESS_AVAILABLE.'] Net address is available');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_NET_ADDRESS_AVAILABLE);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['replyMessage'] = 'Success. Multi-static IP address is available';
	}
	return $returnObj;
}
function wsGetAvailableIPAddress($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsGetAvailableIPAddress';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$bngHostname = trim($param['bngHostname']);
	$returnObj = array(
		'responseCode' => F_AVAILABLE_IP_ADDRESSES_RETURNED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: bngHostname (required)
	 **************************************************/
	if ($bngHostname == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: bngHostname');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'BNG hostname is required';
		return $returnObj;
	}
	if (strtolower($bngHostname) == 'all') {
		$locationStr = 'all';
	} else {
		// check location existence
		$locationObj = Aaa::getLocationObjWithBngName($mysqlConn, $bngHostname);
		if (!$locationObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_QUERY_ERROR.'] Query error: '.$locationObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_MYSQL_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_MYSQL_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'Mysql database error occurred';
			return $returnObj;
		}
		$locations = $locationObj['data'];
		if ($locations === false || empty($locations) || is_null($locations)) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_BNG_UNLISTED.'] BNG hostname does not exist');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_BNG_UNLISTED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_BNG_UNLISTED;
			$returnObj['replyMessage'] = 'BNG hostname doest not exist';
			return $returnObj;
		}
		// only getting first since all elements will have the same locations
		$locationStr = $locations[0]['location'];
	}
	// fetch ip addresses
	$ipAddressObj = Aaa::getAvailableIpAddressWithBngHostname($aaaConn, $locationStr);
	if (!$ipAddressObj['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$ipAddressObj['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$ipAddresses = array();
	// format ip addresses
	foreach ($ipAddressObj['data'] as $ipObj) {
		// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", json_encode($ipObj));
		$item = array(
			'staticIP' => $ipObj['IPADDRESS'],
			'status' => $ipObj['IPUSED'],
			'location' => $ipObj['LOCATION']);
		$ipAddresses[] = $item;
	}
	$returnObj['ipAddresses'] = $ipAddresses;
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_AVAILABLE_IP_ADDRESSES_RETURNED.'] Returned '.count($ipAddressObj['data']).' ip addresses');
	$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_AVAILABLE_IP_ADDRESSES_RETURNED);
	if (!$lrq['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
	}
	return $returnObj;
}
function wsGetAvailableIPv6Address($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsGetAvailableIPv6Address';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$bngHostname = trim($param['bngHostname']);
	$returnObj = array(
		'responseCode' => F_AVAILABLE_IPV6_ADDRESSES_RETURNED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: bngHostname (required)
	 **************************************************/
	if ($bngHostname == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: bngHostname');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'BNG hostname is required';
		return $returnObj;
	}
	if (strtolower($bngHostname) == 'all') {
		$locationStr = 'all';
	} else {
		// check location existence
		$locationObj = Aaa::getLocationObjWithBngName($mysqlConn, $bngHostname);
		if (!$locationObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_QUERY_ERROR.'] Query error: '.$locationObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_MYSQL_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_MYSQL_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'Mysql database error occurred';
			return $returnObj;
		}
		$locations = $locationObj['data'];
		if ($locations === false || empty($locations) || is_null($locations)) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_BNG_UNLISTED.'] BNG hostname does not exist');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_BNG_UNLISTED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_BNG_UNLISTED;
			$returnObj['replyMessage'] = 'BNG hostname doest not exist';
			return $returnObj;
		}
		// only getting first since all elements will have the same locations
		$locationStr = $locations[0]['location'];
	}
	// fetch ip addresses
	$ipAddressObj = Aaa::getAvailableIpV6AddressWithBngHostname($aaaConn, $locationStr);
	if (!$ipAddressObj['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$ipAddressObj['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$ipAddresses = array();
	// format ip addresses
	foreach ($ipAddressObj['data'] as $ipObj) {
		// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", json_encode($ipObj));
		$item = array(
			'staticIP' => $ipObj['IPV6ADDR'],
			'status' => $ipObj['IPV6USED'],
			'location' => $ipObj['LOCATION']);
		$ipAddresses[] = $item;
	}
	$returnObj['ipAddresses'] = $ipAddresses;
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_AVAILABLE_IPV6_ADDRESSES_RETURNED.'] Returned '.count($ipAddressObj['data']).' ipv6 addresses');
	$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_AVAILABLE_IPV6_ADDRESSES_RETURNED);
	if (!$lrq['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
	}
	return $returnObj;
}
function wsGetAvailableNetAddress($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsGetAvailableNetAddress';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$bngHostname = trim($param['bngHostname']);
	$returnObj = array(
		'responseCode' => F_AVAILABLE_NET_ADDRESSES_RETURNED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: bngHostname (required)
	 **************************************************/
	if ($bngHostname == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: bngHostname');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'BNG hostname is required';
		return $returnObj;
	}
	if (strtolower($bngHostname) == 'all') {
		$locationStr = 'all';
	} else {
		// check location existence
		$locationObj = Aaa::getLocationObjWithBngName($mysqlConn, $bngHostname);
		if (!$locationObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_QUERY_ERROR.'] Query error: '.$locationObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_MYSQL_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_MYSQL_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'Mysql database error occurred';
			return $returnObj;
		}
		$locations = $locationObj['data'];
		if ($locations === false || empty($locations) || is_null($locations)) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_BNG_UNLISTED.'] BNG hostname does not exist');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_BNG_UNLISTED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_BNG_UNLISTED;
			$returnObj['replyMessage'] = 'BNG hostname doest not exist';
			return $returnObj;
		}
		// only getting first since all elements will have the same locations
		$locationStr = $locations[0]['location'];
	}
	// fetch net addresses
	$netAddressObj = Aaa::getAvailableNetAddressWithBngHostname($aaaConn, $locationStr);
	if (!$netAddressObj['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$netAddressObj['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$netAddresses = array();
	// format net addresses
	foreach ($netAddressObj['data'] as $netObj) {
		$item = array(
			'multiIP' => $netObj['NETADDRESS'],
			'status' => $netObj['NETUSED'],
			'location' => $netObj['LOCATION']);
		$netAddresses[] = $item;
	}
	$returnObj['netAddresses'] = $netAddresses;
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_AVAILABLE_NET_ADDRESSES_RETURNED.'] Returned '.count($netAddressObj['data']).' net addresses');
	$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_AVAILABLE_NET_ADDRESSES_RETURNED);
	if (!$lrq['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
	}
	return $returnObj;
}
function wsGetAssignedIPAddress($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsGetAssignedIPAddress';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$bngHostname = trim($param['bngHostname']);
	$returnObj = array(
		'responseCode' => F_UNAVAILABLE_IP_ADDRESSES_RETURNED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: bngHostname (required)
	 **************************************************/
	if ($bngHostname == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: bngHostname');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'BNG hostname is required';
		return $returnObj;
	}
	if (strtolower($bngHostname) == 'all') {
		$locationStr = 'all';
	} else {
		// check location existence
		$locationObj = Aaa::getLocationObjWithBngName($mysqlConn, $bngHostname);
		if (!$locationObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_QUERY_ERROR.'] Query error: '.$locationObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_MYSQL_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_MYSQL_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'Mysql database error occurred';
			return $returnObj;
		}
		$locations = $locationObj['data'];
		if ($locations === false || empty($locations) || is_null($locations)) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_BNG_UNLISTED.'] BNG hostname does not exist');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_BNG_UNLISTED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_BNG_UNLISTED;
			$returnObj['replyMessage'] = 'BNG hostname doest not exist';
			return $returnObj;
		}
		// only getting first since all elements will have the same locations
		$locationStr = $locations[0]['location'];
	}
	// fetch ip addresses
	$ipAddressObj = Aaa::getAssignedIpAddressWithBngHostname($aaaConn, $locationStr);
	if (!$ipAddressObj['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$ipAddressObj['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$ipAddresses = array();
	// format ip addresses
	// $i = 0;
	foreach ($ipAddressObj['data'] as $ipObj) {
		$item = array(
			'staticIP' => $ipObj['IPADDRESS'],
			'status' => $ipObj['IPUSED'],
			'location' => $ipObj['LOCATION'],
			'username' => $ipObj['USERNAME'],
			'dateAssigned' => $ipObj['MODIFIED_DATE']);
		$ipAddresses[] = $item;
		// $i++;
		// if ($i == 5) {
		// 	break;
		// }
	}
	$returnObj['ipAddresses'] = $ipAddresses;
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNAVAILABLE_IP_ADDRESSES_RETURNED.'] Returned '.count($ipAddressObj['data']).' ip addresses');
	$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNAVAILABLE_IP_ADDRESSES_RETURNED);
	if (!$lrq['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
	}
	return $returnObj;
}
function wsGetAssignedIPv6Address($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsGetAssignedIPv6Address';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$bngHostname = trim($param['bngHostname']);
	$returnObj = array(
		'responseCode' => F_UNAVAILABLE_IPV6_ADDRESSES_RETURNED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: bngHostname (required)
	 **************************************************/
	if ($bngHostname == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: bngHostname');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'BNG hostname is required';
		return $returnObj;
	}
	if (strtolower($bngHostname) == 'all') {
		$locationStr = 'all';
	} else {
		// check location existence
		$locationObj = Aaa::getLocationObjWithBngName($mysqlConn, $bngHostname);
		if (!$locationObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_QUERY_ERROR.'] Query error: '.$locationObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_MYSQL_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_MYSQL_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'Mysql database error occurred';
			return $returnObj;
		}
		$locations = $locationObj['data'];
		if ($locations === false || empty($locations) || is_null($locations)) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_BNG_UNLISTED.'] BNG hostname does not exist');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_BNG_UNLISTED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_BNG_UNLISTED;
			$returnObj['replyMessage'] = 'BNG hostname doest not exist';
			return $returnObj;
		}
		// only getting first since all elements will have the same locations
		$locationStr = $locations[0]['location'];
	}
	// fetch ip addresses
	$ipAddressObj = Aaa::getAssignedIpV6AddressWithBngHostname($aaaConn, $locationStr);
	if (!$ipAddressObj['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$ipAddressObj['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$ipAddresses = array();
	// format ip addresses
	// $i = 0;
	foreach ($ipAddressObj['data'] as $ipObj) {
		$item = array(
			'staticIP' => $ipObj['IPV6ADDR'],
			'status' => $ipObj['IPV6USED'],
			'location' => $ipObj['LOCATION'],
			'username' => $ipObj['USERNAME'],
			'dateAssigned' => $ipObj['MODIFIED_DATE']);
		$ipAddresses[] = $item;
		// $i++;
		// if ($i == 5) {
		// 	break;
		// }
	}
	$returnObj['ipAddresses'] = $ipAddresses;
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNAVAILABLE_IPV6_ADDRESSES_RETURNED.'] Returned '.count($ipAddressObj['data']).' ipv6 addresses');
	$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNAVAILABLE_IPV6_ADDRESSES_RETURNED);
	if (!$lrq['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
	}
	return $returnObj;
}
function wsGetAssignedNetAddress($param) {
	global $logFile;
	global $apiAccessLogDir;
	$functionName = 'wsGetAssignedNetAddress';
	$client = getClientLogin();
	$clientIp = getClientIpAddress();
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "", false, false);
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "-------------------- ".$functionName." request from ".$clientIp.": ".json_encode($param));
	$now = date('Y-m-d H:i:s', time());
	$bngHostname = trim($param['bngHostname']);
	$returnObj = array(
		'responseCode' => F_UNAVAILABLE_NET_ADDRESSES_RETURNED,
		'replyMessage' => 'Success');
	/**************************************************
	 * read config file
	 **************************************************/
	$config = generateConnectionUrls();
	// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'config: '.json_encode($config));
	if ($config === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_CONFIG_FILE_READ_ERROR.'] Error occurred when reading config file');
		$returnObj['responseCode'] = F_CONFIG_FILE_READ_ERROR;
		$returnObj['replyMessage'] = 'Error reading config file';
		return $returnObj;
	}
	/**************************************************
	 * AAA database connection (TBLCUSTOMER & TBLCONCURRENTUSERS have the same access)
	 **************************************************/
	$aaaConn = oci_connect($config['primarySessionUsername'], $config['primarySessionPassword'], $config['primarySessionUrl']);
	if ($aaaConn === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_CONNECT_ERROR.'] No connection to AAA database');
		$returnObj['responseCode'] = F_ORACLE_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * mysql database connection
	 **************************************************/
	$mysqlConn = new mysqli($config['mysqlHost'], $config['mysqlUsername'], $config['mysqlPassword'], $config['mysqlDatabase'], MYSQLPORT, MYSQLSOCKET );
	if ($mysqlConn->connect_error) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_CONNECT_ERROR.'] No connection to Mysql database');
		$returnObj['responseCode'] = F_MYSQL_DB_CONNECT_ERROR;
		$returnObj['replyMessage'] = 'Mysql database error occurred';
		return $returnObj;
	}
	/**************************************************
	 * authenticate client
	 **************************************************/
	if (AUTHENTICATE) {
		$authenticate = authenticate($mysqlConn, $client['login'], $client['password'], $functionName, $clientIp);
		if (!$authenticate['continue']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': '.$authenticate['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $authenticate['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $authenticate['code'];
			$returnObj['replyMessage'] = substr($authenticate['message'], strpos($authenticate['message'], '] ') + 2, strlen($authenticate['message']));
			return $returnObj;
		}
		$proceed = checkRequestWindow($mysqlConn, $functionName, array(200), $config['requestPerWindow'], $config['requestWindowInSeconds'], $config['requestBlockTimeInSeconds']);
		if ($proceed['code'] == R_REQUEST_BLOCKED_NO_SUCH_FUNCTION || $proceed['code'] == R_REQUEST_BLOCKED_START_WAIT_TIME ||
				$proceed['code'] == R_REQUEST_BLOCKED_WITHIN_WAIT_TIME || $proceed['code'] == R_REQUEST_BLOCKED_QUERY_ERROR) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.$proceed['code'].']'.$proceed['message']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, $proceed['code']);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = $proceed['code'];
			$returnObj['replyMessage'] = $proceed['message'];
			return $returnObj;
		} else {
			// writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Valid request: ['.$proceed['code'].'] '.(isset($proceed['message']) ? $proceed['message'] : ''));
		}
	}
	/**************************************************
	 * input-specific conditions: bngHostname (required)
	 **************************************************/
	if ($bngHostname == '') {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_REQUIRED_INPUTS_MISSING.'] Incomplete required inputs: bngHostname');
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_REQUIRED_INPUTS_MISSING);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_REQUIRED_INPUTS_MISSING;
		$returnObj['replyMessage'] = 'BNG hostname is required';
		return $returnObj;
	}
	if (strtolower($bngHostname) == 'all') {
		$locationStr = 'all';
	} else {
		//check location existence
		$locationObj = Aaa::getLocationObjWithBngName($mysqlConn, $bngHostname);
		if (!$locationObj['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_MYSQL_DB_QUERY_ERROR.'] Query error: '.$locationObj['error']);
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_MYSQL_DB_QUERY_ERROR);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_MYSQL_DB_QUERY_ERROR;
			$returnObj['replyMessage'] = 'Mysql database error occurred';
			return $returnObj;
		}
		$locations = $locationObj['data'];
		if ($locations === false || empty($locations) || is_null($locations)) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_BNG_UNLISTED.'] BNG hostname does not exist');
			$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_BNG_UNLISTED);
			if (!$lrq['result']) {
				writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
			}
			$returnObj['responseCode'] = F_BNG_UNLISTED;
			$returnObj['replyMessage'] = 'BNG hostname doest not exist';
			return $returnObj;
		}
		// only getting first since all elements will have the same locations
		$locationStr = $locations[0]['location'];
	}
	// fetch net addresses
	$netAddressObj = Aaa::getAssignedNetAddressWIthBngHostname($aaaConn, $locationStr);
	if (!$netAddressObj['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_ORACLE_DB_QUERY_ERROR.'] Query error: '.$netAddressObj['error']);
		$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_ORACLE_DB_QUERY_ERROR);
		if (!$lrq['result']) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
		}
		$returnObj['responseCode'] = F_ORACLE_DB_QUERY_ERROR;
		$returnObj['replyMessage'] = 'AAA database error occurred';
		return $returnObj;
	}
	$netAddresses = array();
	// format net addresses
	foreach ($netAddressObj['data'] as $netObj) {
		$item = array(
			'multiIP' => $netObj['NETADDRESS'],
			'status' => $netObj['NETUSED'],
			'location' => $netObj['LOCATION'],
			'username' => $netObj['USERNAME'],
			'dateAssigned' => $netObj['MODIFIED_DATE']);
		$netAddresses[] = $item;
	}
	$returnObj['netAddresses'] = $netAddresses;
	writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", '----- '.$functionName.' response to '.$clientIp.': ['.F_UNAVAILABLE_NET_ADDRESSES_RETURNED.'] Returned '.count($netAddressObj['data']).' net addresses');
	$lrq = logRequest($mysqlConn, $clientIp, $functionName, $param, F_UNAVAILABLE_NET_ADDRESSES_RETURNED);
	if (!$lrq['result']) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", 'Unable to log request');
	}
	return $returnObj;
}




function generateConnectionUrls() {
	$configFile = 'subscriberapiconfig.txt';
	$fileHandle = fopen($configFile, 'r');
	if ($fileHandle === false) {
		return false;
	}
	$lines = array();
	while (($buffer = fgets($fileHandle, 4096)) !== false) {
		$line = trim($buffer);
		if ($line != '') {
			$lines[] = trim($buffer);
		}
	}
	$useSessionTable2          = explode('=', $lines[0]);
	$useTblmConcurrentUsers    = explode('=', $lines[1]);
	$sessionHost               = explode('=', $lines[2]);
	$sessionPort               = explode('=', $lines[3]);
	$sessionSchema             = explode('=', $lines[4]);
	$sessionUsername           = explode('=', $lines[5]);
	$sessionPassword           = explode('=', $lines[6]);
	$sessionHost2              = explode('=', $lines[7]);
	$sessionPort2              = explode('=', $lines[8]);
	$sessionSchema2            = explode('=', $lines[9]);
	$sessionUsername2          = explode('=', $lines[10]);
	$sessionPassword2          = explode('=', $lines[11]);
	$tblmConcHost              = explode('=', $lines[12]);
	$tblmConcPort              = explode('=', $lines[13]);
	$tblmConcSchema            = explode('=', $lines[14]);
	$tblmConcUsername          = explode('=', $lines[15]);
	$tblmConcPassword          = explode('=', $lines[16]);
	$tblmConcHost2             = explode('=', $lines[17]);
	$tblmConcPort2             = explode('=', $lines[18]);
	$tblmConcSchema2           = explode('=', $lines[19]);
	$tblmConcUsername2         = explode('=', $lines[20]);
	$tblmConcPassword2         = explode('=', $lines[21]);
	$tblmCoreHost              = explode('=', $lines[22]);
	$tblmCorePort              = explode('=', $lines[23]);
	$tblmCoreSchema            = explode('=', $lines[24]);
	$tblmCoreUsername          = explode('=', $lines[25]);
	$tblmCorePassword          = explode('=', $lines[26]);
	$tblmCoreHost2             = explode('=', $lines[27]);
	$tblmCorePort2             = explode('=', $lines[28]);
	$tblmCoreSchema2           = explode('=', $lines[29]);
	$tblmCoreUsername2         = explode('=', $lines[30]);
	$tblmCorePassword2         = explode('=', $lines[31]);
	$detourApiHost             = explode('=', $lines[32]);
	$detourApiPort             = explode('=', $lines[33]);
	$detourApiStub             = explode('=', $lines[34]);
	$detourApiHost2            = explode('=', $lines[35]);
	$detourApiPort2            = explode('=', $lines[36]);
	$detourApiStub2            = explode('=', $lines[37]);
	$deleteSessionApiHost      = explode('=', $lines[38]);
	$deleteSessionApiPort      = explode('=', $lines[39]);
	$deleteSessionApiStub      = explode('=', $lines[40]);
	$deleteSessionApiHost2     = explode('=', $lines[41]);
	$deleteSessionApiPort2     = explode('=', $lines[42]);
	$deleteSessionApiStub2     = explode('=', $lines[43]);
	$vodApiHost                = explode('=', $lines[44]);
	$vodApiPort                = explode('=', $lines[45]);
	$vodApiStub                = explode('=', $lines[46]);
	$vodApiHost2               = explode('=', $lines[47]);
	$vodApiPort2               = explode('=', $lines[48]);
	$vodApiStub2               = explode('=', $lines[49]);
	$rmApiHost                 = explode('=', $lines[50]);
	$rmApiPort                 = explode('=', $lines[51]);
	$rmApiStub                 = explode('=', $lines[52]);
	$rmDbHost                  = explode('=', $lines[53]);
	$rmDbPort                  = explode('=', $lines[54]);
	$rmDbSchema                = explode('=', $lines[55]);
	$rmDbUsername              = explode('=', $lines[56]);
	$rmDbPassword              = explode('=', $lines[57]);
	$useRmApi2                 = explode('=', $lines[58]);
	$rmApiHost2                = explode('=', $lines[59]);
	$rmApiPort2                = explode('=', $lines[60]);
	$rmApiStub2                = explode('=', $lines[61]);
	$useRmDb2                  = explode('=', $lines[62]);
	$rmDbHost2                 = explode('=', $lines[63]);
	$rmDbPort2                 = explode('=', $lines[64]);
	$rmDbSchema2               = explode('=', $lines[65]);
	$rmDbUsername2             = explode('=', $lines[66]);
	$rmDbPassword2             = explode('=', $lines[67]);
	$mysqlHost                 = explode('=', $lines[68]);
	$mysqlDatabase             = explode('=', $lines[69]);
	$mysqlUsername             = explode('=', $lines[70]);
	$mysqlPassword             = explode('=', $lines[71]);
	$useAaaForPlans            = explode('=', $lines[72]);
	$useMysqlGui               = explode('=', $lines[73]);
	$guiMysqlHost              = explode('=', $lines[74]);
	$guiMysqlDatabase          = explode('=', $lines[75]);
	$guiMysqlUsername          = explode('=', $lines[76]);
	$guiMysqlPassword          = explode('=', $lines[77]);
	$requestPerWindow          = explode('=', $lines[78]);
	$requestWindowInSeconds    = explode('=', $lines[79]);
	$requestBlockTimeInSeconds = explode('=', $lines[80]);
	$configObj = array(
		'useSecondary'                 => intval($useSessionTable2[1]) == 1,
		'useTblm'                      => intval($useTblmConcurrentUsers[1]) == 1,
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
		'primaryVodUrl'                => $vodApiHost[1].':'.$vodApiPort[1].'/'.$vodApiStub[1],
		'secondaryVodUrl'              => $vodApiHost2[1].':'.$vodApiPort2[1].'/'.$vodApiStub2[1],
		'primaryRmHost'                => $rmApiHost[1],
		'primaryRmUrl'                 => $rmApiHost[1].':'.$rmApiPort[1].'/'.$rmApiStub[1],
		'primaryRmDbUrl'               => $rmDbHost[1].':'.$rmDbPort[1].'/'.$rmDbSchema[1],
		'primaryRmDbUsername'          => $rmDbUsername[1],
		'primaryRmDbPassword'          => $rmDbPassword[1],
		'useSecondaryRmApi'            => intval($useRmApi2) == 1,
		'secondaryRmHost'              => $rmApiHost2[1],
		'secondaryRmUrl'               => $rmApiHost2[1].':'.$rmApiPort2[1].'/'.$rmApiStub2[1],
		'useSecondaryRmDb'             => intval($useRmDb2) == 1,
		'secondaryRmDbUrl'             => $rmDbHost2[1].':'.$rmDbPort2[1].'/'.$rmDbSchema2[1],
		'secondaryRmDbUsername'        => $rmDbUsername2[1],
		'secondaryRbDbPassword'        => $rmDbPassword2[1],
		'mysqlHost'                    => $mysqlHost[1],
		'mysqlDatabase'                => $mysqlDatabase[1],
		'mysqlUsername'                => $mysqlUsername[1],
		'mysqlPassword'                => $mysqlPassword[1],
		'useAaaForPlans'               => intval($useAaaForPlans[1]) == 1,
		'useMysqlGui'                  => intval($useMysqlGui[1]) == 1,
		'guiMysqlHost'                 => $guiMysqlHost[1],
		'guiMysqlDatabase'             => $guiMysqlDatabase[1],
		'guiMysqlUsername'             => $guiMysqlUsername[1],
		'guiMysqlPassword'             => $guiMysqlPassword[1],
		'requestPerWindow'             => $requestPerWindow[1],
		'requestWindowInSeconds'       => $requestWindowInSeconds[1],
		'requestBlockTimeInSeconds'    => $requestBlockTimeInSeconds[1]);

	// [AJB] Override config when RMENABLED is set to false.
	if( !RMENABLED )
		$configObj['useAaaForPlans'] = true;

	return $configObj;
}
function checkMysqlConnection($connection, $host, $username, $password, $database, $logFile) {
	$sql = "select count(*) from tblreportquota";
	$result = $connection->query($sql);
	if ($result === false) {
		writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "mysql has gone away. reconnecting...");
		$connection = new mysqli($host, $username, $password, $database);
		if ($connection->connect_error) {
			writeToFile( $apiAccessLogDir.$functionName.$logFile.".log", "unable to reconnect to mysql. exiting.");
			return false;
		}
	}
	return $connection;
}
function writeToFile($file, $msg, $timeStamp = true, $timeOnly = true) {
	file_put_contents($file, ($timeStamp ? ($timeOnly ? date('H:i:s', time())." " : date('Y-m-d H:i:s', time())." ") : "").$msg."\n", FILE_APPEND | LOCK_EX);
}


$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

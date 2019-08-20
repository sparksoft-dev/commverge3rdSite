<?php
$url = 'https://222.127.121.130:443/executables/api/subscriberapiwsdl.php?wsdl';
if (!isset($argv[1])) {
	exit;
}
$num = trim($argv[1]);
$tmp = substr('0000'.$num, -4, 4);
$log = '/webutil/web_logs/api/batch_create'.$tmp.'.log';

$username = 'detourtest'.$tmp.'@globelines.com.ph';
$password = 'As24353Ef#e';
$customerType = 'Business';
$status = 'A';
$orderNumber = 'ORDERNUMBER'.$tmp;
$serviceNumber = 'SERVICENUMBER'.$tmp;
$plan = '512_DOP';
$params = array(
	'username' => $username, 
	'password' => $password, 
	'customerType' => $customerType, 
	'status' => $status,
	'customerName' => '',
	'orderNumber' => $orderNumber,
	'serviceNumber' => $serviceNumber,
	'plan' => $plan,
	'ipAddress' => '',
	'ipv6Address' => '',
	'netAddress' => '',
	'nasLocation' => '',
	'remarks' => '');

try {
	$start = microtime(true);
	// echo "     start: ".$start."\n";
	writeToFile($log, 'start: '.$start, true, false);
	$client = new SoapClient($url);
	$result = $client->wsCreateAccount($params);
	$end = microtime(true);
	// echo "     end:   ".$end."|".$result->responseCode."\n";
	writeToFile($log, 'end:   '.$end, true, false);
	writeToFile($log, 'time:  '.round((($end - $start) * 1000), 3).' ms, responseCode: '.$result->responseCode, true, false);
	// writeToFile($log, 'time:  '.(($end - $start) * 1000), 3).' ms, responseCode: '.$result->responseCode, true, false);
} catch (Exception $e) {
	// echo '     error:'.date('Y-m-d H:i:s', time())."|".json_encode($e)."\n";
	writeToFile($log, "error:".date('Y-m-d H:i:s', time())."|".json_encode($e));
}


// echo ($end - $start)."\n";


function writeToFile($file, $msg, $timeStamp = true, $timeOnly = true) {
	file_put_contents($file, ($timeStamp ? ($timeOnly ? date('H:i:s', time())." " : date('Y-m-d H:i:s', time())." ") : "").$msg."\n", FILE_APPEND | LOCK_EX);
}
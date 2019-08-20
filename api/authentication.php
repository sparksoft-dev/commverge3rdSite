<?php
function getClientIpAddress() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {	//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {	//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}
function getClientLogin() {
	$client = array('login' => '', 'password' => '');
	if (isset($_SERVER['PHP_AUTH_USER'])) {
		$client['login'] = $_SERVER['PHP_AUTH_USER'];
	}
	if (isset($_SERVER['PHP_AUTH_PW'])) {
		$client['password'] = $_SERVER['PHP_AUTH_PW'];
	}
	return $client;
}
/**************************************************
 * return values for 'result'
 * -1: login and/or both password is/are missing
 * -2: login name not recognized
 * -3: username/password pair does not match
 * -4: login name is deactivated
 *  1: login/password is valid
 **************************************************/
function getCredentials($conn, $login = null, $password = null) {
	if (is_null($login) || is_null($password) || $login == '' || $password == '') {
		return array('result' => -1);
	}
	$sql = "select * from `logins` where `login` = '".$login."'";
	$result = $conn->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	if (is_null($row)) {
		return array('result' => -2);
	}
	if ($row['password'] != md5($password)) {
		return array('result' => -3);
	}
	if (intval($row['active']) == 0) {
		return array('result' => -4);
	}
	return array('result' => 1, 'credentials' => $row);
}
/**************************************************
 * return values for 'result'
 * -1: loginId and/or both functionName is/are missing
 * -2: loginId cannot use function
 *  1: loginId can use function
 **************************************************/
function loginCanUseFunction($conn, $loginId = null, $functionName = null) {
	if (is_null($loginId) || is_null($functionName) || $loginId == '') {
		return array('result' => -1);
	}
	$sql = "select count(*) cnt from `functions` f ".
		"left join `access` a on a.function_id = f.id ".
		"where lower(f.name) = '".strtolower($functionName)."' and a.login_id = ".$loginId;
	$result = $conn->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$count = $row['cnt'];
	if ($count == 0) {
		return array('result' => -2);
	} else {
		return array('result' => 1);
	}
}
/**************************************************
 * return values for 'result'
 * -1: ipAddress is missing
 * -2: cannot use the api from the given ip address
 * -3: given ip address is not usable
 *  1: client ip address is acceptable
 **************************************************/
function loginCanUseFromThisLocation($conn, $ipAddress = null) {
	if (is_null($ipAddress)) {
		return array('result' => -1);
	}
	$sql = "select `id`, `usable` from `allowed_ip` ".
		"where `ip_address` = '".$ipAddress."'";
	$result = $conn->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	if (is_null($row)) {
		return array('result' => -2);
	}
	if (intval($row['usable']) == 0) {
		return array('result' => -3);
	}
	return array('result' => 1);
}
function authenticate($conn, $login = null, $password = null, $functionName = null, $ipAddress = null) {
	$resultObj = array(
		'continue' => false,
		'code' => A_AUTHENTICATE_SUCCESS,
		'message' => '');
	$auth = getCredentials($conn, $login, $password);
	if ($auth['result'] == -1) {
		$resultObj['code'] = A_AUTHENTICATE_ERROR_INCOMPLETE_INPUT;
		$resultObj['message'] = '['.$resultObj['code'].'] Unable to authenticate. Username and/or password is missing';
		return $resultObj;
	}
	if ($auth['result'] == -2) {
		$resultObj['code'] = A_AUTHENTICATE_ERROR_LOGIN_UNLISTED;
		$resultObj['message'] = '['.$resultObj['code'].'] Unable to authenticate. Username not listed';
		return $resultObj;
	}
	if ($auth['result'] == -3) {
		$resultObj['code'] = A_AUTHENTICATE_ERROR_INCORRECT_PASSWORD;
		$resultObj['message'] = '['.$resultObj['code'].'] Unable to authenticate. Incorrect password';
		return $resultObj;
	}
	if ($auth['result'] == -4) {
		$resultObj['code'] = A_AUTHENTICATE_ERROR_LOGIN_DISABLED;
		$resultObj['message'] = '['.$resultObj['code'].'] Unable to authenticate. Username/password pair is currently disabled';
		return $resultObj;
	}
	$creds = $auth['credentials'];
	$functionUse = loginCanUseFunction($conn, $creds['id'], $functionName);
	if ($functionUse['result'] == -1) {
		$resultObj['code'] = A_AUTHENTICATE_ERROR_NO_CREDENTIALS_FOUND;
		$resultObj['message'] = '['.$resultObj['code'].'] Unable to authenticate. No credentials for function use checking';
		return $resultObj;
	}
	if ($functionUse['result'] == -2) {
		$resultObj['code'] = A_AUTHENTICATE_ERROR_FUNCTION_DISALLOWED;
		$resultObj['message'] = '['.$resultObj['code'].'] Username is not allowed to use function';
		return $resultObj;
	}
	$locationUse = loginCanUseFromThisLocation($conn, $ipAddress);
	if ($locationUse['result'] == -1) {
		$resultObj['code'] = A_AUTHENTICATE_ERROR_CLIENT_IP_NOT_FOUND;
		$resultObj['message'] = '['.$resultObj['code'].'] Unable to identify client ip address';
		return $resultObj;
	}
	if ($locationUse['result'] == -2) {
		$resultObj['code'] = A_AUTHENTICATE_ERROR_CLIENT_IP_UNLISTED;
		$resultObj['message'] = '['.$resultObj['code'].'] Client ip address is not listed';
		return $resultObj;
	}
	if ($locationUse['result'] == -3) {
		$resultObj['code'] = A_AUTHENTICATE_ERROR_CLIENT_IP_DISABLED;
		$resultObj['message'] = '['.$resultObj['code'].'] Client ip address is listed as unusable';
		return $resultObj;
	}
	$resultObj['continue'] = true;
	return $resultObj;
}
/**************************************************
 * return values for 'result'
 * true: request was logged
 * false: request wasn't logged
 **************************************************/
function logRequest($conn, $ipAddress, $function, $params, $responseCode) {
	$now = date('Y-m-d H:i:s', time());
	$sql = "insert into `request_log` (`client`, `function`, `params`, `response_code`, `date`) values ".
		"('".$ipAddress."', '".$function."', '".json_encode($params)."', ".$responseCode.", '".$now."')";
	$result = $conn->query($sql);
	if ($result === false) {
		return array('result' => false, 'message' => 'Failed to log request');
	} else {
		return array('result' => true);
	}
}
/**************************************************
 * return values for 'result'
 * -1: no function listed
 * -2: block time started (including current request)
 * -3: still blocked
 * -9: query error
 *  0: limit not yet reached (current request pushes through)
 * 	1: function unblocked (current request pushes through)
 **************************************************/
function checkRequestWindow($conn, $function, $respCodesToSkip, $requestLimit, $requestWindow, $blockTime) {
	$sql = "select `name`, `disabled`, `date_disabled` from `functions` ".
		"where `name` = '".$function."'";
	$result = $conn->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	if (is_null($row)) {
		return array('result' => -1, 'code' => R_REQUEST_BLOCKED_NO_SUCH_FUNCTION, 'message' => 'This function is not listed');
	}
	$disabled = $row['disabled'];
	$when = $row['date_disabled'];
	if (intval($disabled) == 0) { // not disabled, check most recent requests)
		$entries = array();
		$i = 0;
		$respCodeConditionStr = '(';
		$respCodesToSkipLength = count($respCodesToSkip);
		for ($j = 0; $j < $respCodesToSkipLength; $j++) {
			$respCodeConditionStr .= $respCodesToSkip[$j];
			if ($j != $respCodesToSkipLength - 1) {
				$respCodeConditionStr .= ', ';
			}
		}
		$respCodeConditionStr .= ')';
		$sql = "select `date` from `request_log` ".
			"where `function` = '".$function."' and `response_code` not in ".$respCodeConditionStr." ".
			"order by `id` desc ".
			"limit ".$requestLimit;
		$result = $conn->query($sql);
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			if ($i == 0 || $i == ($requestLimit - 1)) {
				$entries[] = $row;
			}
			$i++;
  		}
  		if (count($entries) < $requestLimit) {
  			return array('result' => 0, 'code' => R_REQUEST_VALID);
  		}
  		$lastFailedRequestDate = new DateTime($entries[0]['date']);
  		$firstFailedRequestDate = new DateTime($entries[1]['date']);
  		$diff = $lastFailedRequestDate->getTimestamp() - $firstFailedRequestDate->getTimestamp();
  		if ($diff <= $requestWindow) { // set functions.disabled to 1
  			$now = date('Y-m-d H:i:s', time());
  			$sql = "update `functions` set `disabled` = 1, `date_disabled` = '".$now."' where `name` = '".$function."'";
  			$result = $conn->query($sql);
  			if ($result !== false) {
  				return array('result' => -2, 'code' => R_REQUEST_BLOCKED_START_WAIT_TIME, 'message' => 'Function is blocked');	
  			} else {
  				return array('result' => -9, 'code' => R_REQUEST_BLOCKED_QUERY_ERROR, 'message' => 'Query error: '.$sql);
  			}
  		} else { // nothing to do if time elapsed between current failed request and (current - $requestLimit)'s failed request is greater than $requestWindow
  			return array('result' => 0, 'code' => R_REQUEST_VALID);
  		}
	} else { // was disabled, check elapsed time since
		$dateDisabled = new DateTime($row['date_disabled']);
		$now = time();
		//check if block time has elapsed
		$diff = $now - $dateDisabled->getTimestamp();
		if ($diff > $blockTime) { // unblock function and allow current request to push through
			$sql = "update `functions` set `disabled` = 0, `date_disabled` = null where `name` = '".$function."'";
			$result = $conn->query($sql);
			if ($result !== false) { // blocking has been lifted, requst pushes through
				return array('result' => 1, 'code' => R_REQUEST_BLOCKING_LIFTED);
			} else {
				return array('result' => -9, 'code' => R_REQUEST_BLOCKED_QUERY_ERROR, 'message' => 'Query error: '.$sql);
			}
		} else { // request is still in block time
			return array('result' => -3, 'code' => R_REQUEST_BLOCKED_WITHIN_WAIT_TIME, 'message' => 'Function is still blocked, '.($blockTime - $diff).' seconds remaining');
		}
	}
}
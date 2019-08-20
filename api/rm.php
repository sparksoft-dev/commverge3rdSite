<?php
class Rm {

	public static function accountExists($username, $client) {
		$param = array('subscriberID' => $username);
		try {
			$result = $client->wsGetSubscriberProfileByID($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			$data = array(
				'responseCode' => $responseCode,
				'responseMessage' => $responseMessage,
				'exists' => intval($responseCode) == 200 ? true : false);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
	public static function getAccount($username, $client) {
		$param = array('subscriberID' => $username);
		try {
			$result = $client->wsGetSubscriberProfileByID($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			$data = array(
				'responseCode' => $responseCode,
				'responseMessage' => $responseMessage,
				'account' => intval($responseCode) == 200 ? $result->return->subscriberProfile : false);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
	/**************************************************
	 * keyValuePairArray:
	 * array(
	 *	array('key' => 'X', 'value' => 'X'),
	 *	array('key' => 'X', 'value' => 'X'), ...)
	 **************************************************/
	public static function listAccounts($keyValuePairArray, $client) {
		$param = array('subscriberProfileCriteria' => $keyValuePairArray);
		try {
			$result = $client->wsListSubscriberProfiles($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			$accounts = isset($result->return->subscriberProfile) ? $result->return->subscriberProfile : false;
			$count = isset($result->return->subscriberProfile) ? count($result->return->subscriberProfile) : 0;
			$data = array(
				'responseCode' => $responseCode,
				'responseMessage' => $responseMessage,
				'count' => $count,
				'accounts' => $accounts);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
	/**************************************************
	 * subs (minimum keys):
	 * array(
	 *	array('key' => 'USERNAME', 'value' => 'X'),
	 * 	array('key' => 'SUBSCRIBERIDENTITY', 'value' => 'X'),
	 * 	array('key' => 'SUBSCRIBERPACKAGE', 'value' => 'X'),
	 * 	array('key' => 'SUBSCRIBERSTATUS', 'value' => 'X'), ...)
	 **************************************************/
	public static function createAccount($subs, $client) {
		$param = array('subscriberProfile' => $subs);
		try {
			$result = $client->wsAddSubscriberProfile($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			$data = array(
				'responseCode' => $responseCode,
				'responseMessage' => $responseMessage);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
	/**************************************************
	 * list = array of subs (minimum keys):
	 * array (
	 * 	array(
	 *		array('key' => 'USERNAME', 'value' => 'X'),
	 * 		array('key' => 'SUBSCRIBERIDENTITY', 'value' => 'X'),
	 * 		array('key' => 'SUBSCRIBERPACKAGE', 'value' => 'X'),
	 * 		array('key' => 'SUBSCRIBERSTATUS', 'value' => 'X'), ...),
	 * 	array(
	 *		array('key' => 'USERNAME', 'value' => 'X'),
	 * 		array('key' => 'SUBSCRIBERIDENTITY', 'value' => 'X'),
	 * 		array('key' => 'SUBSCRIBERPACKAGE', 'value' => 'X'),
	 * 		array('key' => 'SUBSCRIBERSTATUS', 'value' => 'X'), ...),...)
	 **************************************************/
	public static function createAccountBulk($list, $client) {
		$param = array('subscriberProfile' => $list);
		try {
			$result = $client->wsAddSubscriberBulk($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			$data = array(
				'responseCode' => $responseCode,
				'responseMessage' => $responseMessage);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
	/**************************************************
	 * subs:
	 * array(
	 * 	array('key' => 'SUBSCRIBERPACKAGE', 'value' => 'X'),
	 * 	array('key' => 'SUBSCRIBERSTATUS', 'value' => 'X'), ...)
	 * username: username@realm
	 **************************************************/
	public static function updateAccount($username, $subs, $client) {
		$param = array('subscriberID' => $username, 'subscriberProfile' => $subs);
		try {
			$result = $client->wsUpdateSubscriberProfile($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			$data = array(
				'responseCode' => $responseCode,
				'responseMessage' => $responseMessage);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
	public static function deleteAccount($username, $client) {
		$param = array('subscriberID' => $username);
		try {
			$result = $client->wsDeleteSubscriberProfile($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			$data = array(
				'responseCode' => $responseCode,
				'responseMessage' => $responseMessage);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}

	public static function getCustomerTypeAndFap($username, $client) {
		$param = array('subscriberID' => $username);
		try {
			$result = $client->wsGetSubscriberProfileByID($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			if (intval($responseCode) != 200) {
				return false;
			}
			$account = $result->return->subscriberProfile;
			$attrCount = count($account->entry);
			$typeFound = false;
			$fapFound = false;
			$ret = array('CUSTOMERTYPE' => '', 'FAP' => '');
			for ($i = 0; $i < $attrCount; $i++) {
				$thisEntry = $account->entry[$i];
				if ($thisEntry->key == 'CUSTOMERTYPE') {
					$ret['CUSTOMERTYPE'] = (isset($thisEntry->value) && !is_null($thisEntry->value)) ? $thisEntry->value : '';
					$typeFound = true;
				}
				if ($thisEntry->key == 'FAP') {
					$ret['FAP'] = (isset($thisEntry->value) && !is_null($thisEntry->value)) ? $thisEntry->value : '';
					$fapFound = true;
				}
				if ($typeFound && $fapFound) {
					break;
				}
			}
			return $ret;
		} catch (Exception $e) {
			return false;
		}
	}
	public static function getCustomerType($username, $client) {
		$param = array('subscriberID' => $username);
		try {
			$result = $client->wsGetSubscriberProfileByID($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			if (intval($responseCode) != 200) {
				return false;
			}
			$account = $result->return->subscriberProfile;
			$attrCount = count($account->entry);
			$value = '';
			for ($i = 0; $i < $attrCount; $i++) {
				$thisEntry = $account->entry[$i];
				if ($thisEntry->key == 'CUSTOMERTYPE') {
					$value = (isset($thisEntry->value) && !is_null($thisEntry->value)) ? $thisEntry->value : '';
				}
			}
			return $value;
		} catch (Exception $e) {
			return false;
		}
	}
	public static function getFap($username, $client) {
		$param = array('subscriberID' => $username);
		try {
			$result = $client->wsGetSubscriberProfileByID($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			if (intval($responseCode) != 200) {
				return false;
			}
			$account = $result->return->subscriberProfile;
			$attrCount = count($account->entry);
			$value = '';
			for ($i = 0; $i < $attrCount; $i++) {
				$thisEntry = $account->entry[$i];
				if ($thisEntry->key == 'FAP') {
					$value = (isset($thisEntry->value) && !is_null($thisEntry->value)) ? $thisEntry->value : '';
				}
			}
			return $value;
		} catch (Exception $e) {
			return false;
		}
	}
	public static function getSubscriberPackage($username, $client) {
		$param = array('subscriberID' => $username);
		try {
			$result = $client->wsGetSubscriberProfileByID($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			if (intval($responseCode) != 200) {
				return false;
			}
			$account = $result->return->subscriberProfile;
			$attrCount = count($account->entry);
			$value = '';
			for ($i = 0; $i < $attrCount; $i++) {
				$thisEntry = $account->entry[$i];
				if ($thisEntry->key == 'SUBSCRIBERPACKAGE') {
					$value = (isset($thisEntry->value) && !is_null($thisEntry->value)) ? str_replace('~', '-', $thisEntry->value) : '';
				}
			}
			return $value;
		} catch (Exception $e) {
			return false;
		}
	}
	public static function getSipUrl($username, $client) {
		$param = array('subscriberID' => $username);
		try {
			$result = $client->wsGetSubscriberProfileByID($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			if (intval($responseCode) != 200) {
				return false;
			}
			$account = $result->return->subscriberProfile;
			$attrCount = count($account->entry);
			$value = '';
			for ($i = 0; $i < $attrCount; $i++) {
				$thisEntry = $account->entry[$i];
				if ($thisEntry->key == 'SIPURL') {
					$value = (isset($thisEntry->value) && !is_null($thisEntry->value)) ? $thisEntry->value : '';
				}
			}
			return $value;
		} catch (Exception $e) {
			return false;
		}
	}

	/**************************************************
	 * v2 functions: july 2016
	 **************************************************/
	/**************************************************
	 * wsAddSubscriber
	 * params:
	 * 		$username = the username (e.g. abc01@globelines.com.ph); required
	 *		$plan = plan name (it's ok if still on dashes); required
	 *		$status = 'Active' or 'InActive'
	 *		$customerType = 'RESS', 'BUSS', etc.
	 *		$nodes = array of other nodes (e.g. array('AREA' => $area, 'CUI' => $cui, ...))
	 **************************************************/
	public static function wsAddSubscriber($client, $username = null, $plan = null, $nodes = null, $status = null, $customerType = null) {
		if (is_null($username) || is_null($plan)) {
			return false;
		}
		$subs = array(
			'packageName' => str_replace('-', '~', $plan),
			'subscriberIdentity' => $username,
			'entry' => array(
				array('key' => 'USERNAME', 'value' => $username)));
		if (!is_null($nodes)) {
			foreach ($nodes as $key => $value) {
				$subs['entry'][] = array('key' => $key, 'value' => $value);
			}
		}
		if (!is_null($status)) {
			$subs['entry'][] = array('key' => 'STATUS', 'value' => $status);
		}
		if (!is_null($customerType)) {
			$subs['entry'][] = array('key' => 'CUSTOMER_TYPE', 'value' => $customerType);
		}
		$param = array(
			'subscriberProfile' => $subs);
		try {
			$result = $client->wsAddSubscriber($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			$data = array(
				'responseCode' => $responseCode,
				'responseMessage' => $responseMessage);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
	/**************************************************
	 * wsUpdateSubscriberProfile
	 * - same params as wsAddSubscriber
	 * - if subscriberIdentity is not found, it will be inserted
	 **************************************************/
	public static function wsUpdateSubscriberProfile($client, $username = null, $plan = null, $nodes = null, $status = null, $customerType = null) {
		if (is_null($username)) {
			return false;
		}
		$subs = array(
			'subscriberIdentity' => $username,
			'entry' => array(
				array('key' => 'USERNAME', 'value' => $username)));
		// if (!is_null($plan)) {
		// 	$subs['packageName'] = str_replace('-', '~', $plan);
		// }
		if (!is_null($nodes)) {
			foreach ($nodes as $key => $value) {
				$subs['entry'][] = array('key' => $key, 'value' => $value);
			}
		}
		if (!is_null($plan)) {
			$subs['entry'][] = array('key' => 'DATA_PACKAGE', 'value' => str_replace('-', '~', $plan));
		}
		if (!is_null($status)) {
			$subs['entry'][] = array('key' => 'STATUS', 'value' => $status);
		}
		if (!is_null($customerType)) {
			$subs['entry'][] = array('key' => 'CUSTOMER_TYPE', 'value' => $customerType);
		}
		$param = array(
			'subscriberProfile' => $subs,
			'alternateId' => $username);
		try {
			$result = $client->wsUpdateSubscriberProfile($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			$data = array(
				'responseCode' => $responseCode,
				'responseMessage' => $responseMessage);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
	/**************************************************
	 * wsDeleteSubscriberProfile
	 * params ($username):
	 * 	$theUsername
	 * - $theUsername is required
	 **************************************************/
	public static function wsDeleteSubscriberProfile($client, $username = null) {
		if (is_null($username)) {
			return false;
		}
		$param = array('alternateId' => $username);
		try {
			$result = $client->wsDeleteSubscriberProfile($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			$data = array(
				'responseCode' => $responseCode,
				'responseMessage' => $responseMessage);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
	public static function wsDeleteSubscriberProfileV2($client, $username = null) {
		if (is_null($username)) {
			return false;
		}
		$param = array('subscriberID' => $username);
		try {
			$result = $client->wsDeleteSubscriberProfile($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			$data = array(
				'responseCode' => $responseCode,
				'responseMessage' => $responseMessage);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
	public static function wsPurgeSubscriber($client, $username = null) {
		if (is_null($username)) {
			return false;
		}
		$param = array('alternateId' => $username);
		try {
			$result = $client->wsPurgeSubscriber($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			$data = array(
				'responseCode' => $responseCode,
				'responseMessage' => $responseMessage);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
	public static function wsPurgeSubscriberV2($client, $username = null) {
		if (is_null($username)) {
			return false;
		}
		$param = array('subscriberID' => $username);
		try {
			$result = $client->wsPurgeSubscriber($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			$data = array(
				'responseCode' => $responseCode,
				'responseMessage' => $responseMessage);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
	/**************************************************
	 * wsGetSubscriberProfileByID
	 * params ($username):
	 * 	$theUsername
	 * - $theUsername is required
	 **************************************************/
	public static function wsGetSubscriberProfileByID($client, $username = null) {
		if (is_null($username)) {
			return false;
		}
		$param = array('alternateID' => $username);
		try {
			$result = $client->wsGetSubscriberProfileByID($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			$data = array(
				'responseCode' => $responseCode,
				'responseMessage' => $responseMessage,
				'account' => intval($responseCode) == 200 ? $result->return->subscriberProfile : false);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
	/**************************************************
	 * wsMigrateSubscriber
	 * params ($oldSubsIdentity, $newSubsIdentity)
	 * - both are required
	 **************************************************/
	public static function wsMigrateSubscriber($client, $oldSubsIdentity = null, $newSubsIdentity = null) {
		if (is_null($oldSubsIdentity) || is_null($newSubsIdentity)) {
			return false;
		}
		$param = array('currentSubscriberIdentity' => $oldSubsIdentity, 'newSubscriberIdentity' => $newSubsIdentity);
		try {
			$result = $client->wsMigrateSubscriber($param);
			$responseCode = $result->return->responseCode;
			$responseMessage = $result->return->responseMessage;
			$data = array(
				'responseCode' => $responseCode,
				'responseMessage' => $responseMessage);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
}
?>
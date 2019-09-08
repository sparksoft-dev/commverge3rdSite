<?php
class Aaa {

	public static $USERNAME_MAX_LENGTH = 70;
	public static $PASSWORD_MAX_LENGTH = 20;
	public static $CUSTOMER_NAME_MAX_LENGTH = 100;
	public static $REMARKS_MAX_LENGTH = 100;
	public static $ALLOWED_CUSTOMER_TYPE_VALUES = array('RESIDENTIAL', 'BUSINESS');
	public static $ALLOWED_CUSTOMER_STATUS_VALUES = array('A', 'D');
	public static $ALLOWED_NAS_LOCATION_VALUES = array('NLZ', 'SLZ', 'VIZ');

	// public static function createSubscriber($conn, $username, $password, $status, $serviceNumber, $plan, $customerType = 'Residential', 
	// 	$customerName = null, $orderNumber = null, $ipv6Address = null, $ipAddress = null, $netAddress = null, $remarks = null, $customerReplyItem = null) {

	// add $conn2 for AAA2 DB connection 8/8/2019
	public static function createSubscriber($conn, $username, $password, $status, $serviceNumber, $plan, $customerType = 'Residential', 
		$customerName = null, $orderNumber = null, $ipv6Address = null, $ipAddress = null, $netAddress = null, $remarks = null, $customerReplyItem = null, $conn2, $conn3) {
		$now = date('Y-m-d H:i:s', time());
		$insertDate = substr($now, 2, strlen($now));
		$rbEnabled = is_null($netAddress) ? 'Yes' : 'No';
		$additionalService1 = 'GQDIAL';
		$additionalService2 = 'GQWIFI';
		$customerStatus = $status == 'A' ? 'Active' : 'InActive';
		$rbAccountStatus = 'Primary';
		$rbTimeslot = 'Al0000-2400';
		$rbCreatedBy = 'API';
		$usernameParts = explode('@', $username);
		$realm = isset($usernameParts[1]) ? $usernameParts[1] : null;
		$plan = str_replace('~', '-', $plan);
		try {
			$sql = "insert into TBLCUSTOMER (".
				"CUI, USER_IDENTITY, USERNAME, BANDWIDTH, ".
				"CUSTOMERSTATUS, PASSWORD, CUSTOMERREPLYITEM, CREATEDATE, ".
				"LASTMODIFIEDDATE, RBACCOUNTPLAN, RADIUSPOLICY, RBCUSTOMERNAME, ".
				"RBCREATEDBY, RBADDITIONALSERVICE5, RBADDITIONALSERVICE4, RBADDITIONALSERVICE3, ".
				"RBADDITIONALSERVICE2, RBADDITIONALSERVICE1, RBCHANGESTATUSDATE, RBCHANGESTATUSBY, ".
				"RBACTIVATEDDATE, RBACTIVATEDBY, RBACCOUNTSTATUS, RBSVCCODE2, ".
				"RBSVCCODE, CUSTOMERTYPE, RBSERVICENUMBER, RBCHANGESTATUSFROM, ".
				"RBSECONDARYACCOUNT, RBUNLIMITEDACCESS, RBTIMESLOT, RBORDERNUMBER, ".
				"RBREMARKS, RBREALM, RBNUMBEROFSESSION, RBMULTISTATIC, ".
				"RBIPADDRESS, RBENABLED) values (".
				":cui, :useridentity, :username, null, ".
				":customerstatus, :password, ".(is_null($customerReplyItem) ? "null" : ":customerreplyitem").", TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS'), ".
				"null, :rbaccountplan, :radiuspolicy, ".(is_null($customerName) ? "null" : ":rbcustomername").", ".
				":rbcreatedby, null, ".(is_null($ipv6Address) ? "null" : ":ipv6Address").", null, ".
				":rbadditionalservice2, :rbadditionalservice1, null, null, ".
				($status == 'A' ? "TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS')" : "null").", ".($status == 'A' ? ":rbactivatedby" : "null").", :rbaccountstatus, null, ".
				":rbsvccode, :customertype, :rbservicenumber, null, ".
				"null, 1, :rbtimeslot, ".(is_null($orderNumber) ? "null" : ":rbordernumber").", ".
				(is_null($remarks) ? "null" : ":rbremarks").", ".(is_null($realm) ? "null" : ":rbrealm").", 1, ".(is_null($netAddress) ? "null" : ":netaddress").", ".
				(is_null($ipAddress) ? "null" : ":ipaddress").", :rbenabled)";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':cui', $username);
			oci_bind_by_name($compiled, ':useridentity', $username);
			oci_bind_by_name($compiled, ':username', $username);
			oci_bind_by_name($compiled, ':customerstatus', $customerStatus);
			oci_bind_by_name($compiled, ':password', $password);
			if (!is_null($customerReplyItem)) {
				oci_bind_by_name($compiled, ':customerreplyitem', $customerReplyItem);
			}
			oci_bind_by_name($compiled, ':rbaccountplan', $plan);
			oci_bind_by_name($compiled, ':radiuspolicy', $plan);
			if (!is_null($customerName)) {
				oci_bind_by_name($compiled, ':rbcustomername', $customerName);
			}
			if (!is_null($ipv6Address)) {
				oci_bind_by_name($compiled, ':ipv6Address', $ipv6Address);
			}
			oci_bind_by_name($compiled, ':rbcreatedby', $rbCreatedBy);
			oci_bind_by_name($compiled, ':rbadditionalservice2', $additionalService2);
			oci_bind_by_name($compiled, ':rbadditionalservice1', $additionalService1);
			if ($status == 'A') {
				oci_bind_by_name($compiled, ':rbactivatedby', $rbCreatedBy);
			}
			oci_bind_by_name($compiled, ':rbaccountstatus', $rbAccountStatus);
			oci_bind_by_name($compiled, ':rbsvccode', $plan);
			oci_bind_by_name($compiled, ':customertype', $customerType);
			oci_bind_by_name($compiled, ':rbservicenumber', $serviceNumber);
			oci_bind_by_name($compiled, ':rbtimeslot', $rbTimeslot);
			if (!is_null($orderNumber)) {
				oci_bind_by_name($compiled, ':rbordernumber', $orderNumber);
			}
			if (!is_null($remarks)) {
				oci_bind_by_name($compiled, ':rbremarks', $remarks);
			}
			if (!is_null($realm)) {
				oci_bind_by_name($compiled, ':rbrealm', $realm);
			}
			if (!is_null($netAddress)) {
				oci_bind_by_name($compiled, ':netaddress', $netAddress);
			}
			if (!is_null($ipAddress)) {
				oci_bind_by_name($compiled, ':ipaddress', $ipAddress);
			}
			oci_bind_by_name($compiled, ':rbenabled', $rbEnabled);
			oci_bind_by_name($compiled, ':now', $insertDate);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			} else {
				// return array('result' => true);

				// if successful, insert to AAA DB2
				try{
					$sql = "insert into TBLCUSTOMER (".
					"CUI, USER_IDENTITY, USERNAME, BANDWIDTH, ".
					"CUSTOMERSTATUS, PASSWORD, CUSTOMERREPLYITEM, CREATEDATE, ".
					"LASTMODIFIEDDATE, RBACCOUNTPLAN, RADIUSPOLICY, RBCUSTOMERNAME, ".
					"RBCREATEDBY, RBADDITIONALSERVICE5, RBADDITIONALSERVICE4, RBADDITIONALSERVICE3, ".
					"RBADDITIONALSERVICE2, RBADDITIONALSERVICE1, RBCHANGESTATUSDATE, RBCHANGESTATUSBY, ".
					"RBACTIVATEDDATE, RBACTIVATEDBY, RBACCOUNTSTATUS, RBSVCCODE2, ".
					"RBSVCCODE, CUSTOMERTYPE, RBSERVICENUMBER, RBCHANGESTATUSFROM, ".
					"RBSECONDARYACCOUNT, RBUNLIMITEDACCESS, RBTIMESLOT, RBORDERNUMBER, ".
					"RBREMARKS, RBREALM, RBNUMBEROFSESSION, RBMULTISTATIC, ".
					"RBIPADDRESS, RBENABLED) values (".
					":cui, :useridentity, :username, null, ".
					":customerstatus, :password, ".(is_null($customerReplyItem) ? "null" : ":customerreplyitem").", TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS'), ".
					"null, :rbaccountplan, :radiuspolicy, ".(is_null($customerName) ? "null" : ":rbcustomername").", ".
					":rbcreatedby, null, ".(is_null($ipv6Address) ? "null" : ":ipv6Address").", null, ".
					":rbadditionalservice2, :rbadditionalservice1, null, null, ".
					($status == 'A' ? "TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS')" : "null").", ".($status == 'A' ? ":rbactivatedby" : "null").", :rbaccountstatus, null, ".
					":rbsvccode, :customertype, :rbservicenumber, null, ".
					"null, 1, :rbtimeslot, ".(is_null($orderNumber) ? "null" : ":rbordernumber").", ".
					(is_null($remarks) ? "null" : ":rbremarks").", ".(is_null($realm) ? "null" : ":rbrealm").", 1, ".(is_null($netAddress) ? "null" : ":netaddress").", ".
					(is_null($ipAddress) ? "null" : ":ipaddress").", :rbenabled)";

					$compiled = oci_parse($conn2, $sql);
					oci_bind_by_name($compiled, ':cui', $username);
					oci_bind_by_name($compiled, ':useridentity', $username);
					oci_bind_by_name($compiled, ':username', $username);
					oci_bind_by_name($compiled, ':customerstatus', $customerStatus);
					oci_bind_by_name($compiled, ':password', $password);
					if (!is_null($customerReplyItem)) {
						oci_bind_by_name($compiled, ':customerreplyitem', $customerReplyItem);
					}
					oci_bind_by_name($compiled, ':rbaccountplan', $plan);
					oci_bind_by_name($compiled, ':radiuspolicy', $plan);
					if (!is_null($customerName)) {
						oci_bind_by_name($compiled, ':rbcustomername', $customerName);
					}
					if (!is_null($ipv6Address)) {
						oci_bind_by_name($compiled, ':ipv6Address', $ipv6Address);
					}
					oci_bind_by_name($compiled, ':rbcreatedby', $rbCreatedBy);
					oci_bind_by_name($compiled, ':rbadditionalservice2', $additionalService2);
					oci_bind_by_name($compiled, ':rbadditionalservice1', $additionalService1);
					if ($status == 'A') {
						oci_bind_by_name($compiled, ':rbactivatedby', $rbCreatedBy);
					}
					oci_bind_by_name($compiled, ':rbaccountstatus', $rbAccountStatus);
					oci_bind_by_name($compiled, ':rbsvccode', $plan);
					oci_bind_by_name($compiled, ':customertype', $customerType);
					oci_bind_by_name($compiled, ':rbservicenumber', $serviceNumber);
					oci_bind_by_name($compiled, ':rbtimeslot', $rbTimeslot);
					if (!is_null($orderNumber)) {
						oci_bind_by_name($compiled, ':rbordernumber', $orderNumber);
					}
					if (!is_null($remarks)) {
						oci_bind_by_name($compiled, ':rbremarks', $remarks);
					}
					if (!is_null($realm)) {
						oci_bind_by_name($compiled, ':rbrealm', $realm);
					}
					if (!is_null($netAddress)) {
						oci_bind_by_name($compiled, ':netaddress', $netAddress);
					}
					if (!is_null($ipAddress)) {
						oci_bind_by_name($compiled, ':ipaddress', $ipAddress);
					}
					oci_bind_by_name($compiled, ':rbenabled', $rbEnabled);
					oci_bind_by_name($compiled, ':now', $insertDate);
					//Added OCI_NO_AUTO_COMMIT to not to commit immediately 8/22/2019
					$result = oci_execute($compiled, OCI_NO_AUTO_COMMIT);
					if ($result === false) {
						oci_rollback($conn);
						$error = oci_error($compiled);
						return array('result2' => false, 'sql' => $sql, 'error' => json_encode($error));
					} else {
						// return array('result2' => true);

						// if successful, insert to AAA DB2
						try{
							$sql = "insert into TBLCUSTOMER (".
							"CUI, USER_IDENTITY, USERNAME, BANDWIDTH, ".
							"CUSTOMERSTATUS, PASSWORD, CUSTOMERREPLYITEM, CREATEDATE, ".
							"LASTMODIFIEDDATE, RBACCOUNTPLAN, RADIUSPOLICY, RBCUSTOMERNAME, ".
							"RBCREATEDBY, RBADDITIONALSERVICE5, RBADDITIONALSERVICE4, RBADDITIONALSERVICE3, ".
							"RBADDITIONALSERVICE2, RBADDITIONALSERVICE1, RBCHANGESTATUSDATE, RBCHANGESTATUSBY, ".
							"RBACTIVATEDDATE, RBACTIVATEDBY, RBACCOUNTSTATUS, RBSVCCODE2, ".
							"RBSVCCODE, CUSTOMERTYPE, RBSERVICENUMBER, RBCHANGESTATUSFROM, ".
							"RBSECONDARYACCOUNT, RBUNLIMITEDACCESS, RBTIMESLOT, RBORDERNUMBER, ".
							"RBREMARKS, RBREALM, RBNUMBEROFSESSION, RBMULTISTATIC, ".
							"RBIPADDRESS, RBENABLED) values (".
							":cui, :useridentity, :username, null, ".
							":customerstatus, :password, ".(is_null($customerReplyItem) ? "null" : ":customerreplyitem").", TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS'), ".
							"null, :rbaccountplan, :radiuspolicy, ".(is_null($customerName) ? "null" : ":rbcustomername").", ".
							":rbcreatedby, null, ".(is_null($ipv6Address) ? "null" : ":ipv6Address").", null, ".
							":rbadditionalservice2, :rbadditionalservice1, null, null, ".
							($status == 'A' ? "TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS')" : "null").", ".($status == 'A' ? ":rbactivatedby" : "null").", :rbaccountstatus, null, ".
							":rbsvccode, :customertype, :rbservicenumber, null, ".
							"null, 1, :rbtimeslot, ".(is_null($orderNumber) ? "null" : ":rbordernumber").", ".
							(is_null($remarks) ? "null" : ":rbremarks").", ".(is_null($realm) ? "null" : ":rbrealm").", 1, ".(is_null($netAddress) ? "null" : ":netaddress").", ".
							(is_null($ipAddress) ? "null" : ":ipaddress").", :rbenabled)";
							
							$compiled = oci_parse($conn3, $sql);
							oci_bind_by_name($compiled, ':cui', $username);
							oci_bind_by_name($compiled, ':useridentity', $username);
							oci_bind_by_name($compiled, ':username', $username);
							oci_bind_by_name($compiled, ':customerstatus', $customerStatus);
							oci_bind_by_name($compiled, ':password', $password);
							if (!is_null($customerReplyItem)) {
								oci_bind_by_name($compiled, ':customerreplyitem', $customerReplyItem);
							}
							oci_bind_by_name($compiled, ':rbaccountplan', $plan);
							oci_bind_by_name($compiled, ':radiuspolicy', $plan);
							if (!is_null($customerName)) {
								oci_bind_by_name($compiled, ':rbcustomername', $customerName);
							}
							if (!is_null($ipv6Address)) {
								oci_bind_by_name($compiled, ':ipv6Address', $ipv6Address);
							}
							oci_bind_by_name($compiled, ':rbcreatedby', $rbCreatedBy);
							oci_bind_by_name($compiled, ':rbadditionalservice2', $additionalService2);
							oci_bind_by_name($compiled, ':rbadditionalservice1', $additionalService1);
							if ($status == 'A') {
								oci_bind_by_name($compiled, ':rbactivatedby', $rbCreatedBy);
							}
							oci_bind_by_name($compiled, ':rbaccountstatus', $rbAccountStatus);
							oci_bind_by_name($compiled, ':rbsvccode', $plan);
							oci_bind_by_name($compiled, ':customertype', $customerType);
							oci_bind_by_name($compiled, ':rbservicenumber', $serviceNumber);
							oci_bind_by_name($compiled, ':rbtimeslot', $rbTimeslot);
							if (!is_null($orderNumber)) {
								oci_bind_by_name($compiled, ':rbordernumber', $orderNumber);
							}
							if (!is_null($remarks)) {
								oci_bind_by_name($compiled, ':rbremarks', $remarks);
							}
							if (!is_null($realm)) {
								oci_bind_by_name($compiled, ':rbrealm', $realm);
							}
							if (!is_null($netAddress)) {
								oci_bind_by_name($compiled, ':netaddress', $netAddress);
							}
							if (!is_null($ipAddress)) {
								oci_bind_by_name($compiled, ':ipaddress', $ipAddress);
							}
							oci_bind_by_name($compiled, ':rbenabled', $rbEnabled);
							oci_bind_by_name($compiled, ':now', $insertDate);
							//Added OCI_NO_AUTO_COMMIT to not to commit immediately 8/22/2019
							$result = oci_execute($compiled, OCI_NO_AUTO_COMMIT);
							if ($result === false) {
								oci_rollback($conn2);
								oci_rollback($conn);
								$error = oci_error($compiled);
								return array('result3' => false, 'sql' => $sql, 'error' => json_encode($error));
							} else {
								return array('result3' => true);	
							}

						} catch (Exception $e) {
							return array('result3' => false, 'sql' => $sql, 'error' => json_encode($e));
						}

					}

				} catch (Exception $e) {
					return array('result2' => false, 'sql' => $sql, 'error' => json_encode($e));
				}

			}		
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}

	public static function modifySubscriber($conn, $username, $password, $oldStatus, $newStatus, $serviceNumber, $plan, $customerType = 'Residential', 
		$customerName = null, $orderNumber = null, $ipv6Address = null, $ipAddress = null, $netAddress = null, $remarks = null, $customerReplyItem = null) {
		$now = date('Y-m-d H:i:s', time());
		$updateDate = substr($now, 2, strlen($now));
		$rbEnabled = is_null($netAddress) ? 'Yes' : 'No';
		$customerStatus = $newStatus == 'A' ? 'Active' : 'InActive';
		$statusChanged = $oldStatus != $newStatus;
		$rbChangeStatusBy = 'API';
		$plan = str_replace('~', '-', $plan);
		try {
			$sql = "update TBLCUSTOMER set ".
				"CUSTOMERSTATUS = :customerstatus, PASSWORD = :password, CUSTOMERREPLYITEM = ".(is_null($customerReplyItem) ? "null" : ":customerreplyitem").", ".
				"LASTMODIFIEDDATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS'), RBCUSTOMERNAME = ".(is_null($customerName) ? "null" : ":rbcustomername").", ".
				"RBSVCCODE = :rbsvccode, RBACCOUNTPLAN = :rbaccountplan, RADIUSPOLICY = :radiuspolicy, CUSTOMERTYPE = :customertype, ".
				"RBSERVICENUMBER = :rbservicenumber, RBORDERNUMBER = ".(is_null($orderNumber) ? "null" : ":rbordernumber").", ".
				"RBMULTISTATIC = ".(is_null($netAddress) ? "null" : ":netaddress").", RBIPADDRESS = ".(is_null($ipAddress) ? "null" : ":ipaddress").", ".
				($statusChanged ? "RBCHANGESTATUSDATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS'), RBCHANGESTATUSBY = :rbchangestatusby, " : "").
				($statusChanged && $newStatus == 'A' ? "RBACTIVATEDDATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS'), RBACTIVATEDBY = :rbactivatedby, " : "").
				"RBADDITIONALSERVICE4 = ".(is_null($ipv6Address) ? "null" : ":ipv6Address").", ".
				"RBENABLED = :rbenabled, RBREMARKS = ".(is_null($remarks) ? "null" : ":remarks")." ".
				"where USER_IDENTITY = :username";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':customerstatus', $customerStatus);
			oci_bind_by_name($compiled, ':password', $password);
			if (!is_null($customerReplyItem)) {
				oci_bind_by_name($compiled, ':customerreplyitem', $customerReplyItem);
			}
			oci_bind_by_name($compiled, ':now', $updateDate);
			if (!is_null($customerName)) {
				oci_bind_by_name($compiled, ':rbcustomername', $customerName);
			}
			oci_bind_by_name($compiled, ':rbsvccode', $plan);
			oci_bind_by_name($compiled, ':rbaccountplan', $plan);
			oci_bind_by_name($compiled, ':radiuspolicy', $plan);
			oci_bind_by_name($compiled, ':customertype', $customerType);
			oci_bind_by_name($compiled, ':rbservicenumber', $serviceNumber);
			if (!is_null($orderNumber)) {
				oci_bind_by_name($compiled, ':rbordernumber', $orderNumber);
			}
			if (!is_null($netAddress)) {
				oci_bind_by_name($compiled, ':netaddress', $netAddress);
			}
			if (!is_null($ipAddress)) {
				oci_bind_by_name($compiled, ':ipaddress', $ipAddress);
			}
			if ($statusChanged) {
				oci_bind_by_name($compiled, ':rbchangestatusby', $rbChangeStatusBy);
			}
			if ($statusChanged && $newStatus == 'A') {
				oci_bind_by_name($compiled, ':rbactivatedby', $rbChangeStatusBy);
			}
			oci_bind_by_name($compiled, ':rbenabled', $rbEnabled);
			if (!is_null($ipv6Address)) {
				oci_bind_by_name($compiled, ':ipv6Address', $ipv6Address);
			}
			if (!is_null($remarks)) {
				oci_bind_by_name($compiled, ':remarks', $remarks);
			}
			oci_bind_by_name($compiled, ':username', $username);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			return array('result' => true);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function updateSubscriberPlan($conn, $username, $plan) {
		$now = date('Y-m-d H:i:s', time());
		$updateDate = substr($now, 2, strlen($now));
		$plan = str_replace('~', '-', $plan);
		try {
			$sql = "update TBLCUSTOMER set ".
				"RADIUSPOLICY = :plan, RBACCOUNTPLAN = :plan, RBSVCCODE = :plan, LASTMODIFIEDDATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') ".
				"where USER_IDENTITY = :username";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':plan', $plan);
			oci_bind_by_name($compiled, ':now', $updateDate);
			oci_bind_by_name($compiled, ':username', $username);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			return array('result' => true);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function updateSubscriberStatus($conn, $username, $newStatus) {
		$now = date('Y-m-d H:i:s', time());
		$updateDate = substr($now, 2, strlen($now));
		$customerStatus = $newStatus == 'A' ? 'Active' : 'InActive';
		$rbChangeStatusBy = 'API';
		try {
			$sql = "update TBLCUSTOMER set ".
				"CUSTOMERSTATUS = :customerstatus, ".
				"RBCHANGESTATUSDATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS'), RBCHANGESTATUSBY = :rbchangestatusby, ".
				($newStatus == 'A' ? "RBACTIVATEDDATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS'), RBACTIVATEDBY = :rbactivatedby, " : "").
				"LASTMODIFIEDDATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') ".
				"where USER_IDENTITY = :username";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':customerstatus', $customerStatus);
			oci_bind_by_name($compiled, ':rbchangestatusby', $rbChangeStatusBy);
			if ($newStatus == 'A') {
				oci_bind_by_name($compiled, ':rbactivatedby', $rbChangeStatusBy);	
			}
			oci_bind_by_name($compiled, ':now', $updateDate);
			oci_bind_by_name($compiled, ':username', $username);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			return array('result' => true);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function updateSubscriberIpV6Address($conn, $username, $ipv6Address) {
		$now = date('Y-m-d H:i:s', time());
		$updateDate = substr($now, 2, strlen($now));
		$customerReplyItem = null; // will be updated also but as of feb 23 2016, format is not yet known
		try {
			$sql = "update TBLCUSTOMER set ".
				"RBADDITIONALSERVICE4 = ".(is_null($ipv6Address) ? "null" : ":ipv6Address").", ".
				// "CUSTOMERREPLYITEM = :customerreplyitem, ".
				"LASTMODIFIEDDATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24-MI-SS') ".
				"where USER_IDENTITY = :username";
			$compiled = oci_parse($conn, $sql);
			if (!is_null($ipv6Address)) {
				oci_bind_by_name($compiled, ':ipv6Address', $ipv6Address);
			}
			oci_bind_by_name($compiled, ':now', $updateDate);
			oci_bind_by_name($compiled, ':username', $username);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			return array('result' => true);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function generateCustomerReplyItemValue($ipv6Address, $ipAddress, $netAddress) {
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
			$customerReplyItem = '4874:129='.$ipv6Address.',4874:1=OUTSIDE';
		} else if (!is_null($ipv6Address) && is_null($ipAddress) && !is_null($netAddress)) { //  and net address; should not happen
			// should not happen
		} else if (!is_null($ipv6Address) && !is_null($ipAddress) && is_null($netAddress)) { // ipv6 and ip address
			$customerReplyItem = '4874:129='.$ipv6Address.',0:8='.$ipAddress.',4874:1=OUTSIDE';
		} else if (!is_null($ipv6Address) && !is_null($ipAddress) && !is_null($netAddress)) { // ipv6, ip and net address
			$customerReplyItem = '4874:129='.$ipv6Address.',0:8='.$ipAddress.',0:22='.$netAddress.',4874:1=OUTSIDE';
		}
		return $customerReplyItem;
	}
	public static function updateSubscriberIPv6IpNetAddress($conn, $username, $ipv6Address, $ipAddress, $netAddress, $customerReplyItem) {
		$now = date('Y-m-d H:i:s', time());
		$updateDate = substr($now, 2, strlen($now));
		try {
			$sql = "update TBLCUSTOMER set ".
				"RBIPADDRESS = ".(is_null($ipAddress) ? "null" : ":ipaddress").", ".
				"RBMULTISTATIC = ".(is_null($netAddress) ? "null" : ":netaddress").", ".
				"RBADDITIONALSERVICE4 = ".(is_null($ipv6Address) ? "null" : ":ipv6address").", ".
				"RBENABLED = ".(is_null($netAddress) ? "'Yes'" : "'No'").", ".
				"CUSTOMERREPLYITEM = ".(is_null($customerReplyItem) ? "null" : ":customerreplyitem").", LASTMODIFIEDDATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') ".
				"where USER_IDENTITY = :username";
			$compiled = oci_parse($conn, $sql);
			if (!is_null($ipAddress)) {
				oci_bind_by_name($compiled, ':ipaddress', $ipAddress);	
			}
			if (!is_null($netAddress)) {
				oci_bind_by_name($compiled, ':netaddress', $netAddress);
			}
			if (!is_null($ipv6Address)) {
				oci_bind_by_name($compiled, ':ipv6address', $ipv6Address);
			}
			if (!is_null($customerReplyItem)) {
				oci_bind_by_name($compiled, ':customerreplyitem', $customerReplyItem);
			}
			oci_bind_by_name($compiled, ':now', $updateDate);
			oci_bind_by_name($compiled, ':username', $username);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			return array('result' => true);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function updateSubscriberCustomerreplyitem($conn, $username, $ipv6Address, $ipAddress, $netAddress) {
		$now = date('Y-m-d H:i:s', time());
		$updateDate = substr($now, 2, strlen($now));
		$customerReplyItem = null;
		if (is_null($ipv6Address) && is_null($ipAddress) && !is_null($netAddress)) { //has net address only: should not happen

		} else if (is_null($ipv6Address) && !is_null($ipAddress) && is_null($netAddress)) { //has ip address only
			$customerReplyItem = '0:8='.$ipAddress.',4874:1=OUTSIDE';
		} else if (is_null($ipv6Address) && !is_null($ipAddress) && !is_null($netAddress)) { //has ip address and net address
			$customerReplyItem = '0:8='.$ipAddress.',0:22='.$netAddress.',4874:1=OUTSIDE';
		} else if (!is_null($ipv6Address) && is_null($ipAddress) && is_null($netAddress)) { //has ipv6 address only
			// $customerReplyItem = '';
		} else if (!is_null($ipv6Address) && is_null($ipAddress) && !is_null($netAddress)) { //has ipv6 address and net address: should not happen

		} else if (!is_null($ipv6Address) && !is_null($ipAddress) && is_null($netAddress)) { //has ipv6 address and ip address
			// $customerReplyItem = '';
		} else if (!is_null($ipv6Address) && !is_null($ipAddress) && !is_null($netAddress)) { //has all three
			// $customerReplyItem = '';
		}
		try {
			$sql = "update TBLCUSTOMER set ";
				"CUSTOMERREPLYITEM = ".(is_null($customerReplyItem) ? "null" : ":customerreplyitem").", LASTMODIFIEDDATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') ".
				"where USER_IDENTITY = :username";
			$compiled = oci_parse($conn, $sql);
			if (!is_null($customerReplyItem)) {
				oci_bind_by_name($compiled, ':customerreplyitem', $customerReplyItem);
			}
			oci_bind_by_name($compiled, ':now', $updateDate);
			oci_bind_by_name($compiled, ':username', $username);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			return array('result' => true);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function updateStatusAtIPv6Table($conn, $ipaddress, $status, $username) {
		$now = date('Y-m-d H:i:s', time());
		$updateDate = substr($now, 2, strlen($now));
		try {
			$sql = "update TBLIPV6ADDRESS set ".
				"STATUS = :status, MODIFIED_DATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24-MI-SS') ".
				"where USERNAME = :username and IPV6ADDR = :ipaddress";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':status', $status);
			oci_bind_by_name($compiled, ':now', $updateDate);
			oci_bind_by_name($compiled, ':username', $username);
			oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			return array('result' => true);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function updateStatusAtIPTable($conn, $ipaddress, $status, $username) {
		$now = date('Y-m-d H:i:s', time());
		$updateDate = substr($now, 2, strlen($now));
		try {
			$sql = "update TBLIPADDRESS set ".
				"STATUS = :status, MODIFIED_DATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24-MI-SS') ".
				"where USERNAME = :username and IPADDRESS = :ipaddress";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':status', $status);
			oci_bind_by_name($compiled, ':now', $updateDate);
			oci_bind_by_name($compiled, ':username', $username);
			oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			return array('result' => true);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function updateStatusAtNetTable($conn, $netaddress, $status, $username) {
		$now = date('Y-m-d H:i:s', time());
		$updateDate = substr($now, 2, strlen($now));
		try {
			$sql = "update TBLNETADDRESS set ".
				"STATUS = :status, MODIFIED_DATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24-MI-SS') ".
				"where USERNAME = :username and NETADDRESS = :netaddress";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':status', $status);
			oci_bind_by_name($compiled, ':now', $updateDate);
			oci_bind_by_name($compiled, ':username', $username);
			oci_bind_by_name($compiled, ':netaddress', $netaddress);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			return array('result' => true);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function updateSubscriberRbenabled($conn, $username, $value) {
		$now = date('Y-m-d H:i:s', time());
		$updateDate = substr($now, 2, strlen($now));
		try {
			$sql = "update TBLCUSTOMER set ".
				"RBENABLED = :rbenabled, LASTMODIFIEDDATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') ".
				"where USER_IDENTITY = :username";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':rbenabled', $value);
			oci_bind_by_name($compiled, ':now', $updateDate);
			oci_bind_by_name($compiled, ':username', $username);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			return array('result' => true);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getSubscriberWithUsername($conn, $username) {
		try {
			$sql = "select USER_IDENTITY, USERNAME, BANDWIDTH, CUSTOMERSTATUS, PASSWORD, CUSTOMERREPLYITEM, CREATEDATE, LASTMODIFIEDDATE, RADIUSPOLICY, ".
				"RBACCOUNTPLAN, CUSTOMERTYPE, CUI, RBCUSTOMERNAME, RBCREATEDBY, RBADDITIONALSERVICE5, RBADDITIONALSERVICE4, RBADDITIONALSERVICE3, ".
				"RBADDITIONALSERVICE2, RBADDITIONALSERVICE1, RBCHANGESTATUSDATE, RBCHANGESTATUSBY, RBACTIVATEDDATE, RBACTIVATEDBY, RBACCOUNTSTATUS, RBSVCCODE, ".
				"RBSVCCODE2, RBSERVICENUMBER, RBCHANGESTATUSFROM, RBSECONDARYACCOUNT, RBUNLIMITEDACCESS, RBTIMESLOT, RBORDERNUMBER, RBREMARKS, RBREALM, ".
				"RBNUMBEROFSESSION, RBMULTISTATIC, RBIPADDRESS, RBENABLED, EXPIRYDATE, CREDITLIMIT, CONCURRENTLOGINPOLICY, ACCESSPOLICY, IPPOOLNAME, ".
				"CALLINGSTATIONID, HOTLINEPOLICY, MACVALIDATION ".
				"from TBLCUSTOMER ".
				"where USER_IDENTITY = :username";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':username', $username);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
			return array('result' => true, 'data' => $row);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getSubscriberWithServiceNumber($conn, $serviceNumber) {
		try {
			$sql = "select USER_IDENTITY, USERNAME, BANDWIDTH, CUSTOMERSTATUS, PASSWORD, CUSTOMERREPLYITEM, CREATEDATE, LASTMODIFIEDDATE, RADIUSPOLICY, ".
				"RBACCOUNTPLAN, CUSTOMERTYPE, CUI, RBCUSTOMERNAME, RBCREATEDBY, RBADDITIONALSERVICE5, RBADDITIONALSERVICE4, RBADDITIONALSERVICE3, ".
				"RBADDITIONALSERVICE2, RBADDITIONALSERVICE1, RBCHANGESTATUSDATE, RBCHANGESTATUSBY, RBACTIVATEDDATE, RBACTIVATEDBY, RBACCOUNTSTATUS, RBSVCCODE, ".
				"RBSVCCODE2, RBSERVICENUMBER, RBCHANGESTATUSFROM, RBSECONDARYACCOUNT, RBUNLIMITEDACCESS, RBTIMESLOT, RBORDERNUMBER, RBREMARKS, RBREALM, ".
				"RBNUMBEROFSESSION, RBMULTISTATIC, RBIPADDRESS, RBENABLED, EXPIRYDATE, CREDITLIMIT, CONCURRENTLOGINPOLICY, ACCESSPOLICY, IPPOOLNAME, ".
				"CALLINGSTATIONID, HOTLINEPOLICY, MACVALIDATION ".
				"from TBLCUSTOMER ".
				"where RBSERVICENUMBER = :servicenumber";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':servicenumber', $serviceNumber);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
			return array('result' => true, 'data' => $row);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getSubscriberWithUserameAndServiceNumber($conn, $username, $serviceNumber) {
		try {
			$sql = "select USER_IDENTITY, USERNAME, BANDWIDTH, CUSTOMERSTATUS, PASSWORD, CUSTOMERREPLYITEM, CREATEDATE, LASTMODIFIEDDATE, RADIUSPOLICY, ".
				"RBACCOUNTPLAN, CUSTOMERTYPE, CUI, RBCUSTOMERNAME, RBCREATEDBY, RBADDITIONALSERVICE5, RBADDITIONALSERVICE4, RBADDITIONALSERVICE3, ".
				"RBADDITIONALSERVICE2, RBADDITIONALSERVICE1, RBCHANGESTATUSDATE, RBCHANGESTATUSBY, RBACTIVATEDDATE, RBACTIVATEDBY, RBACCOUNTSTATUS, RBSVCCODE, ".
				"RBSVCCODE2, RBSERVICENUMBER, RBCHANGESTATUSFROM, RBSECONDARYACCOUNT, RBUNLIMITEDACCESS, RBTIMESLOT, RBORDERNUMBER, RBREMARKS, RBREALM, ".
				"RBNUMBEROFSESSION, RBMULTISTATIC, RBIPADDRESS, RBENABLED, EXPIRYDATE, CREDITLIMIT, CONCURRENTLOGINPOLICY, ACCESSPOLICY, IPPOOLNAME, ".
				"CALLINGSTATIONID, HOTLINEPOLICY, MACVALIDATION ".
				"from TBLCUSTOMER ".
				"where USER_IDENTITY = :username and RBSERVICENUMBER = :servicenumber";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':username', $username);
			oci_bind_by_name($compiled, ':servicenumber', $serviceNumber);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
			return array('result' => true, 'data' => $row);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getSubscriberSessions($primaryConn, $secondaryConn, $useTblmconcurrentusers, $username) {
		$sessions = array();
		try {
			$sql = "select USER_NAME, ACCT_SESSION_ID, FRAMED_IP_ADDRESS, NAS_IDENTIFIER, NAS_IP_ADDRESS, NAS_PORT, SESSION_STATUS, CONCUSERID, MAC_ADDRESS, START_TIME ".
				"from ".($useTblmconcurrentusers ? "TBLMCONCURRENTUSERS" : "TBLCONCURRENTUSERS")." ".
				"where USER_NAME = :username";
			$compiled = oci_parse($primaryConn, $sql);
			oci_bind_by_name($compiled, ':username', $username);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => 'Primary: '.$sql, 'error' => json_encode($error));
			}
			while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
				$sessions[] = $row;
			}
			if (!is_null($secondaryConn)) {
				$compiled = oci_parse($secondaryConn, $sql);
				oci_bind_by_name($compiled, ':username', $username);
				$result = oci_execute($compiled);
				if ($result === false) {
					$error = oci_error($compiled);
					return array('result' => false, 'sql' => 'Secondary: '.$sql, 'error' => json_encode($error));
				}
				while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
					$sessions[] = $row;
				}
			}
			return array('result' => true, 'data' => $sessions);			
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function deleteSubscriberSessionUsingClient($apiClient, $session, $username) {
		$map = array('0:4' => $session['NAS_IP_ADDRESS'], '0:44' => $session['ACCT_SESSION_ID']);
		try {
			$result = $apiClient->requestDisconnect($username, $map);
			return array('result' => true, 'value' => $result);
		} catch (Exception $e) {
			return array('result' => false, 'error' => json_encode($e));
		}
	}
	public static function deleteSubscriberSessionAtTblmConcurrentusers($conn, $username) {
		try {
			$sql = "delete from TBLMCONCURRENTUSERS where USER_NAME = :username";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':username', $username);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			return array('result' => true);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function deleteSubscriberSessionAtTblmCoresessions($conn, $username) {
		try {
			$sql = "delete from TBLMCORESESSIONS where USERIDENTITY = :username";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':username', $username);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			return array('result' => true);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function deleteSubscriber($conn, $username) {
		try {
			$sql = "delete from TBLCUSTOMER where USER_IDENTITY = :username";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':username', $username);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			return array('result' => true);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getIPAddress($conn, $ipAddress) {
		try {
			$sql = "select ID, IPADDRESS, LOCATION, IPUSED, GPONIP, USERNAME, STATUS ".
				"from TBLIPADDRESS ".
				"where IPADDRESS = :ipaddress";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':ipaddress', $ipAddress);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
			return array('result' => true, 'data' => $row);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getIPv6Address($conn, $ipAddress) {
		try {
			$sql = "select ID, IPV6ADDR, LOCATION, IPV6USED, USERNAME, STATUS ".
				"from TBLIPV6ADDRESS ".
				"where IPV6ADDR = :ipaddress";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':ipaddress', $ipAddress);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
			return array('result' => true, 'data' => $row);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getNetAddress($conn, $netAddress) {
		try {
			$sql = "select ID, NETADDRESS, LOCATION, NETUSED, USERNAME, STATUS ".
				"from TBLNETADDRESS ".
				"where NETADDRESS = :netaddress";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':netaddress', $netAddress);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
			return array('result' => true, 'data' => $row);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getCabinet($mysqlConn, $cabinetName) {
		try {
			//check if cabinet exists
			$sql = "select ci.id, ci.name ".
				"from cabinet_info ci ".
				"where ci.name = '".strtoupper($cabinetName)."'";
			$result = $mysqlConn->query($sql);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			return array('result' => true, 'data' => $row);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getIPAddressWithCabinet($conn, $mysqlConn, $cabinetName) {
		try {
			/**************************************************
			 * find location given cabinet name
			 **************************************************/
			$sql = "select ci.id, ci.name, ci.homing_bng, l.location ".
				"from cabinet_info ci ".
				"left join locations l on l.id = ci.homing_bng ".
				"where ci.name = '".$cabinetName."'";
			$result = $mysqlConn->query($sql);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			if ($row === false || empty($row) || is_null($row)) {
				return array('result' => false, 'sql' => $sql, 'error' => 'No mapping for given cabinet name');
			}
			$location = $row['location'];
			/**************************************************
			 * fetch next available ip address with provided location
			 * - no need to see if gpon or not
			 **************************************************/
			$sql = "select * from (".
				"select inner_query.*, rownum rnum from (".
					"select IPADDRESS, LOCATION, IPUSED, GPONIP ".
					"from TBLIPADDRESS ".
					"where IPUSED = 'N' and LOCATION = :location ".
					"order by IPADDRESS asc".
				") inner_query where rownum < 2)";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':location', $location);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
			return array('result' => true, 'data' => $row);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getIPv6AddressWithCabinet($conn, $mysqlConn, $cabinetName) {
		try {
			/**************************************************
			 * find location given cabinet name
			 **************************************************/
			$sql = "select ci.id, ci.name, ci.homing_bng, l.location ".
				"from cabinet_info ci ".
				"left join locations l on l.id = ci.homing_bng ".
				"where ci.name = '".$cabinetName."'";
			$result = $mysqlConn->query($sql);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			if ($row === false || empty($row) || is_null($row)) {
				return array('result' => false, 'sql' => $sql, 'error' => 'No mapping for given cabinet name');
			}
			$location = $row['location'];
			/**************************************************
			 * fetch next available ip address with provided location
			 **************************************************/
			$sql = "select * from (".
				"select inner_query.*, rownum rnum from (".
					"select IPV6ADDR, LOCATION, IPV6USED ".
					"from TBLIPV6ADDRESS ".
					"where IPV6USED = 'N' and LOCATION = :location ".
					"order by IPV6ADDR asc".
				") inner_query where rownum < 2)";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':location', $location);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
			return array('result' => true, 'data' => $row);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getNetAddressWithCabinet($conn, $mysqlConn, $cabinetName, $range) {
		try {
			/**************************************************
			 * find location given cabinet name
			 **************************************************/
			$sql = "select ci.id, ci.name, ci.homing_bng, l.location ".
				"from cabinet_info ci ".
				"left join locations l on l.id = ci.homing_bng ".
				"where ci.name = '".$cabinetName."'";
			$result = $mysqlConn->query($sql);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			if ($row === false || empty($row) || is_null($row)) {
				return array('result' => false, 'sql' => $sql, 'error' => 'No mapping for given cabinet name');
			}
			$location = $row['location'];
			/**************************************************
			 * fetch next available net address with provided location
			 **************************************************/
			$sql = "select * from (".
				"select inner_query.*, rownum rnum from (".
					"select NETADDRESS, LOCATION, NETUSED ".
					"from TBLNETADDRESS ".
					"where NETUSED = 'N' and LOCATION = :location ".(is_null($range) ? "" : "and regexp_like(NETADDRESS, '/".$range."$', 'c') ").
					"order by NETADDRESS asc".
				") inner_query where rownum < 2)";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':location', $location);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
			return array('result' => true, 'data' => $row);		
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getLocationObjWithNasCode($mysqlConn, $nasCode) {
		$locations = array();
		try {
			/**************************************************
			 * query may return more than one row, 
			 * - just use the first element since all of the rows will have the same nas_code and rm_location
			 **************************************************/
			$sql = "select nl.nas_name, nl.nas_ip, nl.rm_location, nl.nas_code, l.location ".
				"from nas_location nl ".
				"left join locations l on l.nas_id = nl.id ".
				"where nl.nas_code = '".strtoupper($nasCode)."' ".
				"order by nl.nas_name asc";
			$result = $mysqlConn->query($sql);
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$locations[] = $row;
			}
			$result->free();
			return array('result' => true, 'data' => $locations);			
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getLocationObjWithCabinetName($mysqlConn, $cabinetName) {
		try {
			$sql = "select nl.nas_name, nl.nas_ip, nl.rm_location, nl.nas_code, ci.name, l.location ".
				"from nas_location nl ".
				"left join cabinet_info ci on ci.homing_bng = nl.id ".
				"left join locations l on l.nas_id = nl.id ".
				"where ci.name = '".strtoupper($cabinetName)."'";
			$result = $mysqlConn->query($sql);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$result->free();
			return array('result' => true, 'data' => $row);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getLocationObjWithLocationString($mysqlConn, $location) {
		try {
			$sql = "select nl.nas_name, nl.nas_ip, nl.rm_location, nl.nas_code, ci.name, l.location ".
				"from nas_location nl ".
				"left join cabinet_info ci on ci.homing_bng = nl.id ".
				"left join locations l on l.nas_id = nl.id ".
				"where l.location = '".$location."'";
			$result = $mysqlConn->query($sql);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$result->free();
			return array('result' => true, 'data' => $row);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));	
		}
	}
	public static function getLocationObjWithBngName($mysqlConn, $bngName) {
		$locations = array();
		try {
			/**************************************************
			 * query may return more than one row, 
			 * - just use the first element since all of the rows will have the same nas_code and rm_location
			 **************************************************/
			$sql = "select nl.nas_name, nl.nas_ip, nl.rm_location, nl.nas_code, ci.name, l.location ".
				"from nas_location nl ".
				"left join cabinet_info ci on ci.homing_bng = nl.id ".
				"left join locations l on l.nas_id = nl.id ".
				"where nl.nas_name = '".$bngName."'";
			$result = $mysqlConn->query($sql);
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$locations[] = $row;
			}
			$result->free();
			return array('result' => true, 'data' => $locations);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getAvailableIpAddressWithBngHostname($conn, $location) {
		$ipAddresses = array();
		try {
			// $sql = "select IPADDRESS as staticIP, LOCATION as location, STATUS as status ".
			$sql = "select IPADDRESS, LOCATION, IPUSED, STATUS ".
				"from TBLIPADDRESS ".
				"where IPUSED = 'N'".(strtolower($location) != 'all' ? " and LOCATION = :location" : "")." ".
				"order by IPADDRESS asc";
			$compiled = oci_parse($conn, $sql);
			if (strtolower($location) != 'all') {
				oci_bind_by_name($compiled, ':location', $location);
			}
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
				$ipAddresses[] = $row;
			}
			return array('result' => true, 'data' => $ipAddresses);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getAvailableIpV6AddressWithBngHostname($conn, $location) {
		$ipAddresses = array();
		try {
			$sql = "select IPV6ADDR, LOCATION, IPV6USED, STATUS ".
				"from TBLIPV6ADDRESS ".
				"where IPV6USED = 'N' ".(strtolower($location) != 'all' ? " and LOCATION = :location" : "")." ".
				"order by IPV6ADDR asc";
			$compiled = oci_parse($conn, $sql);
			if (strtolower($location) != 'all') {
				oci_bind_by_name($compiled, ':location', $location);
			}
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
				$ipAddresses[] = $row;
			}
			return array('result' => true, 'data' => $ipAddresses);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getAvailableNetAddressWithBngHostname($conn, $location) {
		$netAddresses = array();
		try {
			// $sql = "select NETADDRESS as multiIP, LOCATION as location, STATUS as status ".
			$sql = "select NETADDRESS, LOCATION, NETUSED, STATUS ".
				"from TBLNETADDRESS ".
				"where NETUSED = 'N'".(strtolower($location) != 'all' ? " and LOCATION = :location" : "")." ".
				"order by NETADDRESS asc";
			$compiled = oci_parse($conn, $sql);
			if (strtolower($location) != 'all') {
				oci_bind_by_name($compiled, ':location', $location);
			}
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
				$netAddresses[] = $row;
			}
			return array('result' => true, 'data' => $netAddresses);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getAssignedIpAddressWithBngHostname($conn, $location) {
		$ipAddresses = array();
		try {
			// $sql = "select IPADDRESS as staticIP, LOCATION as location, STATUS as status, USERNAME as username, MODIFIED_DATE as dateAssigned ".
			$sql = "select IPADDRESS, LOCATION, IPUSED, STATUS, USERNAME, MODIFIED_DATE ".
				"from TBLIPADDRESS ".
				"where IPUSED = 'Y'".(strtolower($location) != 'all' ? " and LOCATION = :location" : "")." ".
				"order by IPADDRESS asc";
			$compiled = oci_parse($conn, $sql);
			if (strtolower($location) != 'all') {
				oci_bind_by_name($compiled, ':location', $location);
			}
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
				$ipAddresses[] = $row;
			}
			return array('result' => true, 'data' => $ipAddresses);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getAssignedIpV6AddressWithBngHostname($conn, $location) {
		$ipAddresses = array();
		try {
			$sql = "select IPV6ADDR, LOCATION, IPV6USED, STATUS, USERNAME, MODIFIED_DATE ".
				"from TBLIPV6ADDRESS ".
				"where IPV6USED = 'Y'".(strtolower($location) != 'all' ? " and LOCATION = :location" : "")." ".
				"order by IPV6ADDR asc";
			$compiled = oci_parse($conn, $sql);
			if (strtolower($location) != 'all') {
				oci_bind_by_name($compiled, ':location', $location);
			}
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
				$ipAddresses[] = $row;
			}
			return array('result' => true, 'data' => $ipAddresses);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getAssignedNetAddressWIthBngHostname($conn, $location) {
		$netAddresses = array();
		try {
			// $sql = "select NETADDRESS as multiIP, LOCATION as location, STATUS as status, USERNAME as username, MODIFIED_DATE as dateAssigned ".
			$sql = "select NETADDRESS, LOCATION, NETUSED, STATUS, USERNAME, MODIFIED_DATE ".
				"from TBLNETADDRESS ".
				"where NETUSED = 'Y'".(strtolower($location) != 'all' ? " and LOCATION = :location" : "")." ".
				"order by NETADDRESS asc";
			$compiled = oci_parse($conn, $sql);
			if (strtolower($location) != 'all') {
				oci_bind_by_name($compiled, ':location', $location);
			}
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
				$netAddresses[] = $row;
			}
			return array('result' => true, 'data' => $netAddresses);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function markIPAddress($conn, $ipAddress, $isUsed, $username, $status) {
		$now = date('Y-m-d H:i:s', time());
		$updateDate = substr($now, 2, strlen($now));
		try {
			if ($isUsed) {
				$sql = "update TBLIPADDRESS set IPUSED = 'Y', USERNAME = :username, STATUS = :status, MODIFIED_DATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') ".
					"where IPADDRESS = :ipaddress";
				$compiled = oci_parse($conn, $sql);
				oci_bind_by_name($compiled, ':username', $username);
				oci_bind_by_name($compiled, ':status', $status);
			} else {
				$sql = "update TBLIPADDRESS set IPUSED = 'N', USERNAME = '-', STATUS = null, MODIFIED_DATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') ".
					"where IPADDRESS = :ipaddress";
				$compiled = oci_parse($conn, $sql);
			}
			oci_bind_by_name($compiled, ':now', $updateDate);
			oci_bind_by_name($compiled, ':ipaddress', $ipAddress);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			return array('result' => true);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function markIPv6Address($conn, $ipAddress, $isUsed, $username, $status) {
		$now = date('Y-m-d H:i:s', time());
		$updateDate = substr($now, 2, strlen($now));
		try {
			if ($isUsed) {
				$sql = "update TBLIPV6ADDRESS set IPV6USED = 'Y', USERNAME = :username, STATUS = :status, MODIFIED_DATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') ".
					"where IPV6ADDR = :ipaddress";
				$compiled = oci_parse($conn, $sql);
				oci_bind_by_name($compiled, ':username', $username);
				oci_bind_by_name($compiled, ':status', $status);
			} else {
				$sql = "update TBLIPV6ADDRESS set IPV6USED = 'N', USERNAME = '-', STATUS = null, MODIFIED_DATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') ".
					"where IPV6ADDR = :ipaddress";
				$compiled = oci_parse($conn, $sql);
			}
			oci_bind_by_name($compiled, ':now', $updateDate);
			oci_bind_by_name($compiled, ':ipaddress', $ipAddress);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			return array('result' => true);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function markNetAddress($conn, $netAddress, $isUsed, $username, $status) {
		$now = date('Y-m-d H:i:s', time());
		$updateDate = substr($now, 2, strlen($now));
		try {
			if ($isUsed) {
				$sql = "update TBLNETADDRESS set NETUSED = 'Y', USERNAME = :username, STATUS = :status, MODIFIED_DATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') ".
					"where NETADDRESS = :netaddress";
				$compiled = oci_parse($conn, $sql);
				oci_bind_by_name($compiled, ':username', $username);
				oci_bind_by_name($compiled, ':status', $status);
			} else {
				$sql = "update TBLNETADDRESS set NETUSED = 'N', USERNAME = '-', STATUS = null, MODIFIED_DATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') ".
					"where NETADDRESS = :netaddress";
				$compiled = oci_parse($conn, $sql);
			}
			oci_bind_by_name($compiled, ':now', $updateDate);
			oci_bind_by_name($compiled, ':netaddress', $netAddress);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			return array('result' => true);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}	
	}
	public static function getPlanFromTblservices($conn, $plan) {
		$plan = str_replace('~', '-', $plan);
		try {
			$sql = "select * ".
				"from TBLSERVICES ".
				"where SERVICENAME = :plan";
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':plan', $plan);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
			return array('result' => true, 'data' => $row);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getPlanNames($aaaConn, $rmConn, $useAaa) {
		$planNames = array();
		$conn = null;
		try {
			if ($useAaa) {
				$sql = "select REPLACE(SERVICENAME, '~', '-') as RADIUSPOLICYNAME ".
					"from TBLSERVICES ".
					"order by SERVICENAME asc";
				$conn = $aaaConn;
			} else {
				$sql = "select REPLACE(PLANNAME, '~', '-') as RADIUSPOLICYNAME ".
					"from VWRADIUSPOLICY ".
					"order by PLANNAME asc";
				$conn = $rmConn;
			}
			$compiled = oci_parse($conn, $sql);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
				$planNames[] = $row['RADIUSPOLICYNAME'];
			}
			return array('result' => true, 'data' => $planNames);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getSpeedBoosts($conn) {
		$speedBoosts = array();
		try {
			$sql = 'select "boost" from TBLSPEEDBOOST order by "boost" desc';
			$compiled = oci_parse($conn, $sql);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
				$speedBoosts[] = '_'.$row['boost'];
			}
			return array('result' => true, 'data' => $speedBoosts);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getRealms($conn) {
		$realms = array();
		try {
			$sql = "select REALMNAME from TBLREALM";
			$compiled = oci_parse($conn, $sql);
			$result = oci_execute($compiled);
			if ($result === false) {
				$error = oci_error($compiled);
				return array('result' => false, 'sql' => $sql, 'error' => json_encode($error));
			}
			while(($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
				$realms[] = $row['REALMNAME'];
			}
			return array('result' => true, 'data' => $realms);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getNewVodValue($mysqlConn, $oldVodName) {
		try {
			$sql = "select vp.old_vod, vp.new_name ".
				"from vod_params vp ".
				"where vp.old_vod = '".$oldVodName."'";
			$result = $mysqlConn->query($sql);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$result->free();
			return array('result' => true, 'data' => $row);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getCabinetWithName($mysqlConn, $cabinetName, $isCaseSensitive = true) {
		try {
			$sql = "select ci.name, ci.data_vlan as vlan, l.location ".
				"from `cabinet_info` ci ".
				"left join `locations` l on ci.homing_bng = l.id ".
				"where ".($isCaseSensitive ? "binary ci.name = '".$cabinetName."'" : "lower(ci.name) = '".strtolower($cabinetName)."'");
			$result = $mysqlConn->query($sql);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$result->free();
			return array('result' => true, 'data' => $row);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}
	public static function getCabinetWithVlan($mysqlConn, $vlan) {
		try {
			$sql = "select ci.name, ci.data_vlan as vlan, l.location ".
				"from `cabinet_info` ci ".
				"left join `locations` l on ci.homing_bng = l.id ".
				"where ci.data_vlan = ".$vlan;
			$result = $mysqlConn->query($sql);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$result->free();
			return array('result' => true, 'data' => $row);
		} catch (Exception $e) {
			return array('result' => false, 'sql' => $sql, 'error' => json_encode($e));
		}
	}





	public static function separateVodFromPlan($plan) {
		$thisHasVod = false;
		$thisVodValue = '';
		$plusPos = strrpos($plan, '+');
		$vbPos = strrpos($plan, 'VB');
		$vbPos = $vbPos == false ? 0 : $vbPos;
		$planLen = strlen($plan);
		$diff = $vbPos - $plusPos;
		$diffFromEnd = $planLen - $vbPos;
		if (($diff == 2 || $diff == 3 || $diff == 4) && $diffFromEnd == 2) {
			$thisHasVod = true;
			$thisVodValue = substr($plan, $plusPos + 1);
			$plan = substr($plan, 0, $plusPos);
		}
		return array('hasVod' => $thisHasVod, 'vodValue' => $thisVodValue, 'find' => $plan);
	}
	public static function fixPlan($fixThisPlan, $plans, $boosts) {
		$found = false;
		$fixed = '';
		$toFixHasDay = strpos($fixThisPlan, $boosts[2]) !== false ? true : false;
		$toFixHasNgt = strpos($fixThisPlan, $boosts[0]) !== false ? true : false;
		$toFixHasIns = strpos($fixThisPlan, $boosts[1]) !== false ? true : false;
		$planCount = count($plans);
		for ($j = 0; $j < $planCount; $j++) {
			$currentPlan = $plans[$j];
			$day = false;
			$ngt = false;
			$ins = false;
			if (strpos($currentPlan, '_DAY') !== false) { //has DAY
				$day = true;
			}
			if (strpos($currentPlan, '_NGT') !== false) { //has NGT
				$ngt = true;
			}
			if (strpos($currentPlan, '_INS') !== false) { //has INS
				$ins = true;
			}
			if ($day == false && $ngt == false && $ins == false) {			//000
				//do nothing since currentPlan has no _DAY, _NGT, nor _INS
				if ($currentPlan == $fixThisPlan) {
					$found = true;
					$fixed = $currentPlan;
					break;
				}
			} else if ($day == false && $ngt == false && $ins == true) {	//001
				//as is because currentPlan only has _INS, will always match
				if ($currentPlan == $fixThisPlan) {
					$found = true;
					$fixed = $currentPlan;
					break;
				}
			} else if ($day == false && $ngt == true && $ins == false) {	//010
				//as is because currentPlan only has _NGT, will always match
				if ($currentPlan == $fixThisPlan) {
					$found = true;
					$fixed = $currentPlan;
					break;
				}
			} else if (($day == false && $ngt == true && $ins ==  true) && ($toFixHasDay == false && $toFixHasNgt == true && $toFixHasIns == true)) {	//011
				//has _NGT and _INS
				$endIndex = strpos($currentPlan, '_NGT') < strpos($currentPlan, '_INS') ? strpos($currentPlan, '_NGT') : strpos($currentPlan, '_INS');
				$basePlan = substr($currentPlan, 0, $endIndex);
				if (strpos($fixThisPlan, $basePlan) !== false) {
					$found = true;
					$fixed = $currentPlan;
					break;
				}
			} else if ($day == true && $ngt == false && $ins == false) {	//100
				//as is because currentPlan only has _DAY, will always match
				if ($currentPlan == $fixThisPlan) {
					$found = true;
					$fixed = $currentPlan;
					break;
				}
			} else if (($day == true && $ngt == false && $ins == true) && ($toFixHasDay == true && $toFixHasNgt == false && $toFixHasIns == true)) {	//101
				//has _DAY and _INS
				$endIndex = strpos($currentPlan, '_DAY') < strpos($currentPlan, '_INS') ? strpos($currentPlan, '_DAY') : strpos($currentPlan, '_INS');
				$basePlan = substr($currentPlan, 0, $endIndex);
				if (strpos($fixThisPlan, $basePlan) !== false) {
					$found = true;
					$fixed = $currentPlan;
					break;
				}
			} else if (($day == true && $ngt == true && $ins == false) && ($toFixHasDay == true && $toFixHasNgt == true && $toFixHasIns == false)) {	//110
				//has _DAY and _NGT
				$endIndex = strpos($currentPlan, '_DAY') < strpos($currentPlan, '_NGT') ? strpos($currentPlan, '_DAY') : strpos($currentPlan, '_NGT');
				$basePlan = substr($currentPlan, 0, $endIndex);
				if (strpos($fixThisPlan, $basePlan) !== false) {
					$found = true;
					$fixed = $currentPlan;
					break;
				}
			} else if (($day == true && $ngt == true && $ins == true) && ($toFixHasDay == true && $toFixHasNgt == true && $toFixHasIns == true)) {		//111
				//has all three
				$index = array(strpos($currentPlan, '_DAY'), strpos($currentPlan, '_NGT'), strpos($currentPlan, '_INS'));
				sort($index);
				$basePlan = substr($currentPlan, 0, $index[0]);
				if (strpos($fixThisPlan, $basePlan) !== false) {
					$found = true;
					$fixed = $currentPlan;
					break;
				}
			}
		}
		return array('found' => $found, 'fixed' => $fixed);
	}
}
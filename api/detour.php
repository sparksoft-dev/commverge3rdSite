<?php
class Detour {

	public static function getSubscriberDetails($ipaddress, $client) {
		$param = array('ipAddress' => $ipaddress);
		try {
			$result = $client->wsGetSubscriberDetails($param);
			$responseCode = (isset($result->SubscriberDetails) && !is_null($result->SubscriberDetails)) ? $result->SubscriberDetails->responseCode : null;
			$replyMessage = (isset($result->SubscriberDetails) && !is_null($result->SubscriberDetails)) ? $result->SubscriberDetails->replyMessage : null;
			$subscriber = (!is_null($responseCode) && intval($responseCode) == 200) ? 
				(isset($result->SubscriberDetails->subscriberID) ? $result->SubscriberDetails->subscriberID : null) : null;
			$status = (!is_null($responseCode) && intval($responseCode) == 200) ? 
				(isset($result->SubscriberDetails->accountStatus) ? $result->SubscriberDetails->accountStatus : null) : null;
			$plan = (!is_null($responseCode) && intval($responseCode) == 200) ? (isset($result->SubscriberDetails->planID) ? $result->SubscriberDetails->planID : null) : null;
			$session = (!is_null($responseCode) && intval($responseCode) == 200) ? (isset($result->SubscriberDetails->sessionID) ? $result->SubscriberDetails->sessionID : null) : null;
			$volumeQuota = (!is_null($responseCode) && intval($responseCode) == 200) ? $result->SubscriberDetails->volumeQuota : 0;
			$volumeUsage = (!is_null($responseCode) && intval($responseCode) == 200) ? $result->SubscriberDetails->volumeUsage : 0;
			$vodDetails = null;
			$vodExpiry = null;
			$vodQuota = null;
			$vodUsage = null;
			if (intval($responseCode) == 200) {
				if (isset($result->SubscriberDetails->vodDetails) && !is_null($result->SubscriberDetails->vodDetails)) {
					$vodExpiry = $result->SubscriberDetails->vodDetails->vodExpiry;
					$vodQuota = $result->SubscriberDetails->vodDetails->vodQuota;
					$vodUsage = $result->SubscriberDetails->vodDetails->vodUsage;
				}
			}
			$data = array(
				'responseCode' => $responseCode,
				'replyMessage' => $replyMessage,
				'subscriber' => $subscriber,
				'status' => $status,
				'plan' => $plan,
				'session' => $session,
				'volumeQuota' => $volumeQuota,
				'volumeUsage' => $volumeUsage,
				'vodExpiry' => $vodExpiry,
				'vodQuota' => $vodQuota,
				'vodUsage' => $vodUsage);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
	public static function getVolumeUsage($username, $client) {
		$param = array('subscriberId' => $username);
		try {
			$result = $client->wsGetVolumeUsage($param);
			$responseCode = (isset($result->SubscriberDetails) && !is_null($result->SubscriberDetails)) ? $result->SubscriberDetails->responseCode : null;
			$replyMessage = (isset($result->SubscriberDetails) && !is_null($result->SubscriberDetails)) ? $result->SubscriberDetails->replyMessage : null;
			$plan = (!is_null($responseCode) && intval($responseCode) == 200) ?
				(isset($result->SubscriberDetails->planID) && !is_null($result->SubscriberDetails->planID) ? 
					$result->SubscriberDetails->planID : 
					null) : 
				null;
			$volumeQuota = (!is_null($responseCode) && intval($responseCode) == 200) ? $result->SubscriberDetails->volumeQuota : 0;
			$volumeUsage = (!is_null($responseCode) && intval($responseCode) == 200) ? $result->SubscriberDetails->volumeUsage : 0;
			$vodDetails = null;
			$vodExpiry = null;
			$vodQuota = null;
			$vodUsage = null;
			if (intval($responseCode) == 200) {
				if (isset($result->SubscriberDetails->vodDetails) && !is_null($result->SubscriberDetails->vodDetails)) {
					$vodExpiry = isset($result->SubscriberDetails->vodDetails->vodExpiry) && !is_null($result->SubscriberDetails->vodDetails->vodExpiry) ? 
						$result->SubscriberDetails->vodDetails->vodExpiry : null;
					$vodQuota = isset($result->SubscriberDetails->vodDetails->vodQuota) && !is_null($result->SubscriberDetails->vodDetails->vodQuota) ? 
						$result->SubscriberDetails->vodDetails->vodQuota : null;
					$vodUsage = isset($result->SubscriberDetails->vodDetails->vodUsage) && !is_null($result->SubscriberDetails->vodDetails->vodUsage) ? 
						$result->SubscriberDetails->vodDetails->vodUsage : null;
				}
			}
			$data = array(
				'responseCode' => $responseCode,
				'replyMessage' => $replyMessage,
				'plan' => $plan,
				'volumeQuota' => $volumeQuota,
				'volumeUsage' => $volumeUsage,
				'vodExpiry' => $vodExpiry,
				'vodQuota' => $vodQuota,
				'vodUsage' => $vodUsage);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
	public static function resumeService($username, $session, $param1, $client) {
		$param = array('subscriberId' => $username, 'sessionId' => $session, 'param1' => $param1);
		try {
			$result = $client->wsResumeService($param);
			$responseCode = $result->WsResumeSericeResponse->responseCode;
			$replyMessage = $result->WsResumeSericeResponse->replyMessage;
			$data = array(
				'responseCode' => $responseCode,
				'replyMessage' => $replyMessage);
			return $data;
		} catch (Exception $e) {
			return false;
		}
	}
}
?>
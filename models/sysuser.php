<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sysuser extends CI_Model {
	private $extras;
	public $ERROR_INCORRECT_PASSWORD = 1;
    public $ERROR_USER_DOES_NOT_EXIST = 5;
    public $ERROR_USER_BLOCKED = 2;
    public $ERROR_USER_NOACCESS_TIME = 4;
    public $ERROR_USER_NOACCESS_DAY = 3;
    public $ERROR_USER_NOACCESS_REALM = 6;
    public $ERROR_USER_NOACCESS_IPADDRESS = 7;
    public $ERROR_USER_NOGROUP = 8;
    public $ERROR_UNKNOWN = -1;

	function __construct() {
		parent::__construct();
		$this->extras = $this->load->database('extras', true);
	}

	public function getPasswordHistory($username, $historysize) {
		$this->extras->db_select();
		$query = $this->extras->select('id, cn, usersecret, effective_date')
			->from('sysuser_password_history')
			->where('cn', $username)
			->order_by('effective_date', 'desc')
			->limit($historysize)
			->get();
		return $query->result_array();
	}
	public function findSysuserPasswordHistory($password, $username, $historysize) {
		$this->extras->db_select();
		$history = $this->getPasswordHistory($username, $historysize);
		if (count($history) != 0) {
			$size = count($history);
			for ($i = 0; $i < $size; $i++) {
				if ($history[$i]['usersecret'] == $password) {
					return $history[$i];
				}
			}
		}
		return false;
	}
	public function passwordIsInHistory($password, $username, $historysize) {
		$this->extras->db_select();
		$history = $this->getPasswordHistory($username, $historysize);
		if (count($history) != 0) {
			$size = count($history);
			for ($i = 0; $i < $size; $i++) {
				if ($history[$i]['usersecret'] == $password) {
					return true;
				}
			}
		}
		return false;
	}
	public function cleanPasswordHistory($username, $size) {
		$this->extras->db_select();
		$query = $this->extras->select('id, cn, usersecret, effective_date')
			->from('sysuser_password_history')
			->where('cn', $username)
			->order_by('effective_date', 'desc')
			->limit($size)
			->get();
		$history = $query->result_array();
		if (count($history) >= 1) {
			$first = $history[count($history) - 1];
			$date = $first['effective_date'];
			$this->extras->where('cn', $username)
				->where('effective_date <', $date)
				->delete('sysuser_password_history');
		}
		return true;
	}
	public function recordPasswordChange($username, $password, $effectiveDate) {
		$this->extras->db_select();
		$data = array(
			'cn' => $username,
			'usersecret' => $password,
			'effective_date' => date('Y-m-d H:i:s', $effectiveDate));
		$this->extras->insert('sysuser_password_history', $data);
		return $this->extras->affected_rows() == 0 ? false : true;
	}
	public function recordSysuserLoginAttempt($username, $password, $ipaddress, $errorCode, $timestamp) {
		$this->extras->db_select();
		$data = array(
			'username' => $username,
			'password' => $password,
			'ipaddress' => $ipaddress,
			'error_code' => $errorCode,
			'timestamp' => date('Y-m-d H:i:s', $timestamp));
		$this->extras->insert('sysuser_login_attempt', $data);
		return $this->extras->affected_rows() == 0 ? false : true;
	}
	public function recordSysuserLogout($cn, $sessionId, $logoutTime) {
		$this->extras->db_select();
		$query = $this->extras->select('id, host_ipaddress, hostname, username, session_id, ipaddress, login_time, logout_time, minutes')
			->from('sysuser_usage')
			->where('username', $cn)
			->where('session_id', $sessionId)
			->where('logout_time is null')
			->get();
		if ($query->num_rows() == 0) {
			return false;
		} else {
			$row = $query->row_array();
			$minDiff = intval(date('U', $logoutTime)) - date('U', strtotime($row['login_time']));
			$minDiff = intval($minDiff / 60);
			$data = array(
				'logout_time' => date('Y-m-d H:i:s', $logoutTime),
				'minutes' => $minDiff);
			$this->extras->where('id', $row['id'])
				->update('sysuser_usage', $data);
			return $this->extras->affected_rows() == 0 ? false : true;
		}
	}
	public function recordSysuserLogoutNoSession($cn, $logoutTime) {
		$this->extras->db_select();
		$query = $this->extras->select('id, host_ipaddress, hostname, username, session_id, ipaddress, login_time, logout_time, minutes')
			->from('sysuser_usage')
			->where('username', $cn)
			->where('logout_time is null')
			->get();
		if ($query->num_rows() == 0) {
			return false;
		} else {
			$row = $query->row_array();
			$minDiff = intval(date('U', $logoutTime)) - date('U', strtotime($row['login_time']));
			$minDiff = intval($minDiff / 60);
			$data = array(
				'logout_time' => date('Y-m-d H:i:s', $logoutTime),
				'minutes' => $minDiff);
			$this->extras->where('id', $row['id'])
				->update('sysuser_usage', $data);
			return $this->extras->affected_rows() == 0 ? false : true;
		}
	}
	public function recordSysuserLogin($cn, $sessionId, $loginTime, $ipAddress) {
		$this->extras->db_select();
		$data = array(
			'username' => $cn,
			'session_id' => $sessionId,
			'login_time' => date('Y-m-d H:i:s', $loginTime),
			'ipaddress' => $ipAddress,
			'host_ipaddress' => $_SERVER['SERVER_ADDR'], //found in Config.SERVER_IPADDRESS
			'hostname' => $_SERVER['SERVER_NAME']); //found in Config.HOSTNAME
		$this->extras->insert('sysuser_usage', $data);
		return $this->extras->affected_rows() == 0 ? false : true;
	}
	public function getSysuserSessionId($cn) {
		$this->extras->db_select();
		$query = $this->extras->select('session_id')
			->from('sysuser')
			->where('cn', $cn)
			->get();
		if ($query->num_rows() == 0) {
			return false;
		} else {
			$row = $query->row_array();
			return $row['session_id'];
		}
	}
	public function resetSysuserLoginTriesCount($cn) {
		$this->extras->db_select();
		$data = array(
			'login_tries' => 0,
			'modified_date' => date('Y-m-d H:i:s', mktime()));
		$this->extras->where('cn', $cn)
			->update('sysuser', $data);
		return $this->extras->affected_rows() == 0 ? false : true;
	}
	public function incrementSysuserLoginTriesCount($cn) {
		$this->extras->db_select();
		$query = $this->extras->select('login_tries')
			->from('sysuser')
			->where('cn', $cn)
			->get();
		if ($query->num_rows() == 0) {
			return false;
		} else {
			$row = $query->row_array();
			$tries = intval($row['login_tries']) + 1;
			$data = array(
				'login_tries' => $tries,
				'modified_date' => date('Y-m-d H:i:s', mktime()));
			$this->extras->where('cn', $cn)
				->update('sysuser', $data);
			return $this->extras->affected_rows() == 0 ? false : $tries;
		}
	}
	public function incrementSysuserLoginTriesCountWithThreshold($cn, $triesThreshold) {
		$this->extras->db_select();
		$query = $this->extras->select('login_tries')
			->from('sysuser')
			->where('cn', $cn)
			->get();
		if ($query->num_rows() == 0) {
			return false;
		} else {
			$row = $query->row_array();
			$tries = intval($row['login_tries']) + 1;
			$blockUser = false;
			if ($tries >= $triesThreshold) {
				$blockUser = true;
			}
			$data = array(
				'login_tries' => $tries,
				'blocked' => $blockUser ? 1 : 0,
				'modified_date' => date('Y-m-d H:i:s', mktime()));
			$this->extras->where('cn', $cn)
				->update('sysuser', $data);
			return $this->extras->affected_rows() == 0 ? false : $tries;
		}
	}
	public function markSysuserAsLoggedIn($cn, $sessionId) {
		$this->extras->db_select();
		$data = array(
			'logged_in' => 1,
			'session_id' => $sessionId,
			'login_tries' => 0,
			'modified_date' => date('Y-m-d H:i:s', mktime()));
		$this->extras->where('cn', $cn)
			->update('sysuser', $data);
		return $this->extras->affected_rows() == 0 ? false : true;
	}
	public function markSysuserAsNotLoggedIn($cn, $sessionId) {
		$this->extras->db_select();
		$query = $this->extras->select('session_id')
			->from('sysuser')
			->where('cn', $cn)
			->get();
		if ($query->num_rows() == 0) {
			return true;
		} else {
			$row = $query->row_array();
			if ($row['session_id'] != $sessionId) {
				return false;
			} else {
				$data = array(
					'logged_in' => 0,
					'session_id' => '',
					'modified_date' => date('Y-m-d H:i:s', mktime()));
				$this->extras->where('cn', $cn)
					->update('sysuser', $data);
				return $this->extras->affected_rows() == 0 ? false : true;
			}
		}
	}
	public function markSysuserAsNotLoggedInNoSession($cn) {
		$this->extras->db_select();
		$query = $this->extras->select('session_id')
			->from('sysuser')
			->where('cn', $cn)
			->get();
		if ($query->num_rows() == 0) {
			return true;
		} else {
			$data = array(
				'logged_in' => 0,
				'session_id' => '',
				'modified_date' => date('Y-m-d H:i:s', mktime()));
			$this->extras->where('cn', $cn)
				->update('sysuser', $data);
			return $this->extras->affected_rows() == 0 ? false : true;
		}
	}
	/**********************************************
	 * todo
	 **********************************************/
	public function createSysusers($sysusers, $password, $sysusersOk, $sysusersError) {
		//sysusers, sysusersOk, sysusersError = array(array('cn' => $cn, ..., 'created_date' => $date), array('cn' => $cn, ..., 'created_date' => $date))
		$this->extras->db_select();
		$now = mktime();
		$size = count($sysusers);
		for ($i = 0; $i < $size; $i++) {
			$sysuser = $sysusers[$i];
			$data = array(
				'usersecret' => $sysuser['usersecret'],
				'modified_date' => date('Y-m-d H:i:s', $now),
				'created_date' => date('Y-m-d H:i:s', $now));
		}
	}
	/**********************************************
	 * end todo
	 **********************************************/
	public function createSysuser($cn, $usergroup, $usersecret) {
		$this->extras->db_select();
		$now = mktime();
		$data = array(
			'cn' => $cn,
			'rbusername' => $cn,
			'rbusergroup' => $usergroup,
			'usersecret' => $usersecret,
			'logged_in' => 0,
			'session_id' => '',
			'require_password_change' => 1,
			'last_password_change_date' => null,
			'modified_date' => date('Y-m-d H:i:s', $now),
			'created_date' => date('Y-m-d H:i:s', $now));
		$this->extras->insert('sysuser', $data);
		return $this->extras->affected_rows() == 0 ? false : true;
	}
	public function modifySysuserUsergroup($cn, $usergroup) {
		$this->extras->db_select();
		$data = array(
			'rbusergroup' => $usergroup,
			'modified_date' => date('Y-m-d H:i:s', mktime()));
		$this->extras->where('cn', $cn)
			->update('sysuser', $data);
		return $this->extras->affected_rows() == 0 ? false : true;
	}
	public function changeSysuserPassword($cn, $password) {
		$this->extras->db_select();
		$now = mktime();
		$data = array(
			'usersecret' => $password,
			'last_password_change_date' => date('Y-m-d H:i:s', $now),
			'require_password_change' => 0,
			'modified_date' => date('Y-m-d H:i:s', $now));
		$this->extras->where('cn', $cn)
			->update('sysuser', $data);
		return $this->extras->affected_rows() == 0 ? false : true;
	}
	public function deleteSysuser($cn) {
		$this->extras->db_select();
		$this->extras->where('cn', $cn)
			->delete('sysuser');
		return $this->extras->affected_rows() == 0 ? false : true;
	}
	public function findAll($start, $max) {
		$this->extras->db_select();
		$query = $this->extras->select('cn, rbusername, rbusergroup, usersecret, logged_in, session_id, blocked, login_tries, require_password_change,
			last_password_change_date, modified_date, created_date')
			->from('sysuser')
			->order_by('cn', 'asc')
			->limit($max, $start)
			->get();
		return $query->result_array();
	}
	public function countSysusers() {
		$this->extras->db_select();
		$count = $this->extras->count_all_results('sysuser');
		return $count;
	}
	public function countLoggedInSysusers() {
		$this->extras->db_select();
		$count = $this->extras->select('cn')
			->from('sysuser')
			->where('logged_in', 1)
			->count_all_results();
		return $count;
	}
	public function findSysuser($cn) {
		$this->extras->db_select();
		$query = $this->extras->select('cn, rbusername, rbusergroup, usersecret, logged_in, session_id, blocked, login_tries, require_password_change,
			last_password_change_date, modified_date, created_date')
			->from('sysuser')
			->where('cn', $cn)
			->get();
		return $query->num_rows() == 0 ? false : $query->row_array();
	}
	/**********************************************
	 * todo
	 **********************************************/
	public function updateSysuser($sysuser) {
		//$sysuser = array('cn' => $cn, ..., 'created_date' => $date)
		$this->extras->db_select();
		$data = array(
			'modified_date' => $sysuser['modified_date']);
		$this->extras->where('cn', $sysuser['cn'])
			->update('sysuser', $data);
		return $this->extras->affected_rows() == 0 ? false : true;
	}
	/**********************************************
	 * end todo
	 **********************************************/
	public function setSysuserBlock($cn, $blocked) {
		//$blocked: true/false
		$this->extras->db_select();
		$blockedNew = $blocked ? 1 : 0;
		$query = $this->extras->select('cn, blocked')
			->from('sysuser')
			->where('cn', $cn)
			->get();
		if ($query->num_rows() == 0) {
			return false;
		} else {
			$row = $query->row_array();
			if ($row['blocked'] != $blockedNew) {
				$data = array(
					'blocked' => $blockedNew,
					'modified_date' => date('Y-m-d H:i:s', mktime()));
				$this->extras->where('cn', $cn)
					->update('sysuser', $data);
				return $this->extras->affected_rows() == 0 ? false : true;
			}
		}
	}
	public function findAllLoggedIn($start, $max) {
		$this->extras->db_select();
		$query = $this->extras->select('cn, rbusername, rbusergroup, usersecret, logged_in, session_id, blocked, login_tries, require_password_change,
			last_password_change_date, modified_date, created_date')
			->from('sysuser')
			->where('logged_in', 1)
			->order_by('cn', 'asc')
			->limit($max, $start)
			->get();
		return $query->result_array();
	}

	public function findSysuserUsagesByLoginDate($sYear, $sMonth, $sDayBegin, $sDayEnd, $start, $max) {
		$this->extras->db_select();
		$query = $this->extras->select('id, host_ipaddress, hostname, username, session_id, ipaddress, login_time, logout_time, minutes')
			->from('sysuser_usage')
			->where('DATE(login_time) between str_to_date("'.$sYear.', '.$sMonth.', '.$sDayBegin.'", "%Y, %m, %d") and 
				str_to_date("'.$sYear.', '.$sMonth.', '.$sDayEnd.'", "%Y, %m, %d")')
			->order_by('login_time', 'desc')
			->limit($max, $start)
			->get();
		return $query->result_array();
	}
	public function sumSesstimeOfSysuserUsagesByLoginDate($sYear, $sMonth, $sDayBegin, $sDayEnd) {
		$this->extras->db_select();
		$query = $this->extras->select_sum('minutes')
			->from('sysuser_usage')
			->where('DATE(login_time) between str_to_date("'.$sYear.', '.$sMonth.', '.$sDayBegin.'", "%Y, %m, %d") and 
				str_to_date("'.$sYear.', '.$sMonth.', '.$sDayEnd.'", "%Y, %m, %d")')
			->get();
		$row = $query->row_array();
		return $row['minutes'];
	}
	public function countSysuserUsagesByLoginDate($sYear, $sMonth, $sDayBegin, $sDayEnd) {
		$this->extras->db_select();
		$count = $this->extras->from('sysuser_usage')
			->where('DATE(login_time) between str_to_date("'.$sYear.', '.$sMonth.', '.$sDayBegin.'", "%Y, %m, %d") and 
				str_to_date("'.$sYear.', '.$sMonth.', '.$sDayEnd.'", "%Y, %m, %d")')
			->count_all_results();
		return $count;
	}
	public function findSysuserUsagesLoggedInAtTime($sYear, $sMonth, $sDay, $start, $max) {
		$this->extras->db_select();
		$query = $this->extras->select('id, host_ipaddress, hostname, username, session_id, ipaddress, login_time, logout_time, minutes')
			->from('sysuser_usage')
			->where('login_time is not null')
			->where('logout_time is not null')
			->where('str_to_date("'.$sYear.', '.$sMonth.', '.$sDay.'", "%Y, %m, %d") between DATE(login_time) and DATE(logout_time)')
			->order_by('login_time', 'desc')
			->limit($max, $start)
			->get();
		return $query->result_array();
	}
	public function sumSesstimeOfSysuserUsagesLoggedInAtTime($sYear, $sMonth, $sDay) {
		$this->extras->db_select();
		$query = $this->extras->select_sum('minutes')
			->from('sysuser_usage')
			->where('login_time is not null')
			->where('logout_time is not null')
			->where('str_to_date("'.$sYear.', '.$sMonth.', '.$sDay.'", "%Y, %m, %d") between DATE(login_time) and DATE(logout_time)')
			->get();
		$row = $query->row_array();
		return $row['minutes'];
	}
	public function countSysuserUsagesLoggedInAtTime($sYear, $sMonth, $sDay) {
		$this->extras->db_select();
		$count = $this->extras->from('sysuser_usage')
			->where('login_time is not null')
			->where('logout_time is not null')
			->where('str_to_date("'.$sYear.', '.$sMonth.', '.$sDay.'", "%Y, %m, %d") between DATE(login_time) and DATE(logout_time)')
			->count_all_results();
		return $count;
	}
	public function findSysuserUsagesByIpAddress($ipaddress, $start, $max) {
		$this->extras->db_select();
		$query = $this->extras->select('id, host_ipaddress, hostname, username, session_id, ipaddress, login_time, logout_time, minutes')
			->from('sysuser_usage')
			->where('ipaddress', $ipaddress)
			->order_by('login_time', 'desc')
			->limit($max, $start)
			->get();
		return $query->result_array();
	}
	public function sumSesstimeOfSysuserUsagesByIpAddress($ipaddress) {
		$this->extras->db_select();
		$query = $this->extras->select_sum('minutes')
			->from('sysuser_usage')
			->where('ipaddress', $ipaddress)
			->get();
		$row = $query->row_array();
		return $row['minutes'];
	}
	public function countSysuserUsagesByIpAddress($ipaddress) {
		$this->extras->db_select();
		$count = $this->extras->from('sysuser_usage')
			->where('ipaddress', $ipaddress)
			->count_all_results();
		return $count;
	}
	public function findInactiveSysuserUsagesByIpAddress($ipaddress, $start, $max) {
		$this->extras->db_select();
		$query = $this->extras->select('id, host_ipaddress, hostname, username, session_id, ipaddress, login_time, logout_time, minutes')
			->from('sysuser_usage')
			->where('ipaddress', $ipaddress)
			->where('logout_time is not null')
			->order_by('login_time', 'desc')
			->limit($max, $start)
			->get();
		return $query->result_array();
	}
	public function sumSesstimeOfInactiveSysuserUsagesByIpAddress($ipaddress) {
		$this->extras->db_select();
		$query = $this->extras->select_sum('minutes')
			->from('sysuser_usage')
			->where('ipaddress', $ipaddress)
			->where('logout_time is not null')
			->get();
		$row = $query->row_array();
		return $row['minutes'];
	}
	public function countInactiveSysuserUsagesByIpAddress($ipaddress) {
		$this->extras->db_select();
		$count = $this->extras->from('sysuser_usage')
			->where('ipaddress', $ipaddress)
			->where('logout_time is not null')
			->count_all_results();
		return $count;
	}
	public function findActiveSysuserUsagesByIpAddress($ipaddress, $start, $max) {
		$this->extras->db_select();
		$query = $this->extras->select('id, host_ipaddress, hostname, username, session_id, ipaddress, login_time, logout_time, minutes')
			->from('sysuser_usage')
			->where('ipaddress', $ipaddress)
			->where('logout_time is null')
			->order_by('login_time', 'desc')
			->limit($max, $start)
			->get();
		return $query->result_array();
	}
	public function sumSesstimeOfActiveSysuserUsagesByIpAddress($ipaddress) {
		$this->extras->db_select();
		$query = $this->extras->select_sum('minutes')
			->from('sysuser_usage')
			->where('ipaddress', $ipaddress)
			->where('logout_time is null')
			->get();
		$row = $query->row_array();
		return $row['minutes'];
	}
	public function countActiveSysuserUsagesByIpAddress($ipaddress) {
		$this->extras->db_select();
		$count = $this->extras->from('sysuser_usage')
			->where('ipaddress', $ipaddress)
			->where('logout_time is null')
			->count_all_results();
		return $count;
	}
	public function getLoggedInCountsInDay($day, $month, $year, $hostname) {
		$this->extras->db_select();
		$this->extras->select('id, hostname, date, day, month, year, hour, count')
			->from('loggedin_sysusers_count');
		if (!is_null($hostname)) {
			$this->extras->where('hostname', $hostname);
		}
		$query = $this->extras->where('day', $day)
			->where('month', $month)
			->where('year', $year)
			->order_by('date', 'asc')
			->order_by('hour', 'asc')
			->get();
		return $query->result_array();
	}
	public function countLoggedInCountsInDates($startdate, $enddate, $hostname) {
		$this->extras->db_select();
		$this->extras->from('loggedin_sysusers_count');
		if (!is_null($hostname)) {
			$this->extras->where('hostname', $hostname);
		}
		$count = $this->extras->where('date between DATE("'.date('Y-m-d H:i:s', $startdate).'") and DATE("'.date('Y-m-d H:i:s', $enddate).'")')
			->count_all_results();
		return $count;
	}
	public function getLoggedInCountsInDates($startdate, $enddate, $hostname, $start, $max) {
		$this->extras->db_select();
		$query = $this->extras->select('id, hostname, date, day, month, year, hour, count')
			->from('loggedin_sysusers_count')
			->where('hostname', $hostname)
			->where('date between DATE("'.date('Y-m-d H:i:s', $startdate).'") and DATE("'.date('Y-m-d H:i:s', $enddate).'")')
			->order_by('date', 'asc')
			->order_by('hour', 'asc')
			->limit($max, $start)
			->get();
		return $query->result_array();
	}
	public function findFailedSysuserLoginAttempts($start, $max) {
		$this->extras->db_select();
		$query = $this->extras->select('id, username, password, ipaddress, error_code, timestamp')
			->from('sysuser_login_attempt')
			->where('error_code !=', 0)
			->order_by('timestamp', 'desc')
			->limit($max, $start)
			->get();
		return $query->result_array();
	}
	public function countFailedSysuserLoginAttempts() {
		$this->extras->db_select();
		$count = $this->extras->from('sysuser_login_attempt')
			->where('error_code !=', 0)
			->count_all_results();
		return $count;
	}
	public function findFailedSysuserLoginAttemptsWithParams($startdate, $enddate, $username, $ipaddress, $errorCode, $start, $max, $order) {
		//$order = array('column' => $col, 'dir' => $dir);
		$this->extras->db_select();
		$this->extras->select('id, username, password, ipaddress, error_code, timestamp')
			->from('sysuser_login_attempt');
		if ($startdate != null) {
			$this->extras->where('(DATE(timestamp) > DATE("'.date('Y-m-d H:i:s', $startdate).'") or DATE(timestamp) = DATE("'.date('Y-m-d H:i:s', $startdate).'"))');
		}
		if ($enddate != null) {
			$this->extras->where('(DATE(timestamp) < DATE("'.date('Y-m-d H:i:s', $enddate).'") or DATE(timestamp) = DATE("'.date('Y-m-d H:i:s', $enddate).'"))');
		}
		if ($username != null) {
			$this->extras->where('username', $username);
		}
		if ($ipaddress != null) {
			$this->extras->where('ipaddress', $ipaddress);
		}
		if ($errorCode != null) {
			$this->extras->where('error_code', $errorCode);
		}
		if ($order != null) {
			$this->extras->order_by($order['column'], $order['dir']);
		} else {
			$this->extras->order_by('timestamp', 'desc');
		}
		$this->extras->limit($max, $start);
		$query = $this->extras->get();
		return $query->result_array();
	}
	public function countFailedSysuserLoginAttemptsWithParams($startdate, $enddate, $username, $ipaddress, $errorCode) {
		$this->extras->db_select();
		$this->extras->from('sysuser_login_attempt');
		if ($startdate != null) {
			$this->extras->where('DATE(timestamp) > DATE("'.date('Y-m-d H:i:s', $startdate).'") or DATE(timestamp) = DATE("'.date('Y-m-d H:i:s', $startdate).'")');
		}
		if ($enddate != null) {
			$this->extras->where('DATE(timestamp) < DATE("'.date('Y-m-d H:i:s', $enddate).'") or DATE(timestamp) = DATE("'.date('Y-m-d H:i:s', $enddate).'")');
		}
		if ($username != null) {
			$this->extras->where('username', $username);
		}
		if ($ipaddress != null) {
			$this->extras->where('ipaddress', $ipaddress);
		}
		if ($errorCode != null) {
			$this->extras->where('error_code', $errorCode);
		}
		$count = $this->extras->count_all_results();
		return $count;
	}

	public function incrementLoggedInCount($now, $hostname) {
		$this->extras->db_select();
		$day = date('d', $now);
		$month = date('m', $now);
		$year = date('Y', $now);
		$hour = date('H', $now);
		$date = date('Y-m-d', $now);
		$query = $this->extras->select('count')
			->from('loggedin_sysusers_count')
			->where('day', $day)
			->where('month', $month)
			->where('year', $year)
			->where('hour', $hour)
			->get();
		if ($query->num_rows() == 0) {
			$data = array(
				'hostname' => $hostname,
				'date' => $date,
				'day' => $day,
				'month' => $month,
				'year' => $year,
				'hour' => $hour,
				'count' => 1);
			$this->extras->insert('loggedin_sysusers_count', $data);
			return $this->extras->affected_rows() == 0 ? false : true;
		} else {
			$result = $query->row_array();
			$count = $result['count'];
			$data = array(
				'count' => $count + 1,
				'date' => $date);
			$this->extras->where('day', $day)
				->where('month', $month)
				->where('year', $year)
				->where('hour', $hour)
				->update('loggedin_sysusers_count', $data);
			return $this->extras->affected_rows() == 0 ? false : true;
		}
	}

	public function getErrorReason($code) {
		$reason = '';
		switch ($code) {
			case $this->ERROR_INCORRECT_PASSWORD: {
				$reason = 'Incorrect pasword';
				break;
			}
			case $this->ERROR_USER_DOES_NOT_EXIST: {
				$reason = 'Non-existent user';
				break;
			}
			case $this->ERROR_USER_BLOCKED: {
				$reason = 'Blocked user';
				break;
			}
			case $this->ERROR_USER_NOACCESS_TIME: {
				$reason = 'Time restriction';
				break;
			}
			case $this->ERROR_USER_NOACCESS_DAY: {
				$reason = 'Day restriction';
				break;
			}
			case $this->ERROR_USER_NOACCESS_IPADDRESS: {
				$reason = 'IP address restriction';
				break;
			}
			case $this->ERROR_USER_NOACCESS_REALM: {
				$reason = 'Realm restriction';
				break;
			}
			case $this->ERROR_USER_NOGROUP: {
				$reason = 'Group does not exist';
				break;
			}
			case $this->ERROR_UNKNOWN: {
				$reason = 'Unknown error';
				break;
			}
		}
		return $reason;
	}
}
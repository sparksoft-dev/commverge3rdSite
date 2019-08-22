<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usage extends CI_Model {
	private $extras;

	function __construct() {
		parent::__construct();
		$this->extras = $this->load->database('extras', true);
	}

	public function fetchUsagesByUsername($username, $realm, $startdate, $enddate, $start, $max, $order) {
		$this->extras->db_select();
		$this->extras->select('*')
			->from('usages')
			->where('user_name', $username.'@'.$realm)
			->where('stop_time is not null');
		if (!is_null($startdate)) {
			$this->extras->where('start_time > '.$startdate);
		}
		if (!is_null($enddate)) {
			$this->extras->where('start_time < '.$enddate);
		}
		if ($order != null) {
			$this->extras->order_by($order['column'].' + 0 '.$order['dir']);
		} else {
			$this->extras->order_by('id', 'asc');
		}
		$this->extras->limit($max, $start);
		$query = $this->extras->get();
		return $query->result_array();
	}
	public function countUsagesByUsername($username, $realm, $startdate, $enddate) {
		$this->extras->db_select();
		$this->extras->from('usages')
			->where('user_name', $username.'@'.$realm)
			->where('stop_time is not null');
		if (!is_null($startdate)) {
			$this->extras->where('start_time > '.$startdate);
		}
		if (!is_null($enddate)) {
			$this->extras->where('start_time < '.$enddate);
		}
		$count = $this->extras->count_all_results();
		return $count;
	}
	public function fetchAllUsagesByUsername($username, $realm, $startdate, $enddate, $order) {
		$this->extras->db_select();
		$this->extras->select('*')
			->from('usages')
			->where('user_name', $username.'@'.$realm)
			->where('stop_time is not null');
		if (!is_null($startdate)) {
			$this->extras->where('start_time > '.$startdate);
		}
		if (!is_null($enddate)) {
			$this->extras->where('start_time < '.$enddate);
		}
		if ($order != null) {
			if ($order['column'] == 'innove_source_access' || $order['column'] == 'calling_station_id') {
				$this->extras->order_by($order['column'].' + 0 '.$order['dir'])
					->order_by('id', 'asc');
			} else {
				$this->extras->order_by($order['column'].' + 0 '.$order['dir']);
			}
		} else {
			$this->extras->order_by('id', 'asc');
		}
		$query = $this->extras->get();
		return $query->result_array();
	}
	public function getTotalSessionTimeByUsername($username, $realm, $startdate, $enddate) {
		$this->extras->db_select();
		$this->extras->select_sum('acct_session_time')
			->from('usages')
			->where('user_name', $username.'@'.$realm)
			->where('stop_time is not null');
		if (!is_null($startdate)) {
			$this->extras->where('start_time > '.$startdate);
		}
		if (!is_null($enddate)) {
			$this->extras->where('start_time < '.$enddate);
		}
		$query = $this->extras->get();
		$result = $query->row_array();
		return $result['acct_session_time'];
	}
	public function fetchUsageByIPAddress($ipaddress, $startdate, $enddate, $start, $max, $order) {
		$this->extras->db_select();
		$this->extras->select('*')
			->from('usages')
			->where('framed_ip_address', $ipaddress)
			->where('stop_time is not null');
		if (!is_null($startdate)) {
			$this->extras->where('start_time > '.$startdate);
		}
		if (!is_null($enddate)) {
			$this->extras->where('start_time < '.$enddate);
		}
		if ($order != null) {
			$this->extras->order_by($order['column'].' + 0 '.$order['dir']);
		} else {
			$this->extras->order_by('id', 'asc');
		}
		$this->extras->limit($max, $start);
		$query = $this->extras->get();
		return $query->result_array();
	}
	public function countUsagesByIPAddress($ipaddress, $startdate, $enddate) {
		$this->extras->db_select();
		$this->extras->from('usages')
			->where('framed_ip_address', $ipaddress)
			->where('stop_time is not null');
		if (!is_null($startdate)) {
			$this->extras->where('start_time > '.$startdate);
		}
		if (!is_null($enddate)) {
			$this->extras->where('start_time < '.$enddate);
		}
		$count = $this->extras->count_all_results();
		return $count;
	}
	public function getTotalSessionTimeByIPAddress($ipaddress, $startdate, $enddate) {
		$this->extras->db_select();
		$this->extras->select_sum('acct_session_time')
			->from('usages')
			->where('framed_ip_address', $ipaddress)
			->where('stop_time is not null');
		if (!is_null($startdate)) {
			$this->extras->where('start_time > '.$startdate);
		}
		if (!is_null($enddate)) {
			$this->extras->where('start_time < '.$enddate);
		}
		$query = $this->extras->get();
		$result = $query->row_array();
		return $result['acct_session_time'];
	}
	public function fetchUsageBySummary($realm, $type, $startdate, $enddate, $start, $max) {
		$this->extras->db_select();
		$this->extras->select('*')
			->from('usages')
			->where('stop_time is not null');
		if (!is_null($realm)) {
			$this->extras->where("user_name like '%".$realm."'");
		}
		if (!is_null($startdate)) {
			$this->extras->where('start_time > '.$startdate);
		}
		if (!is_null($enddate)) {
			$this->extras->where('start_time < '.$enddate);
		}
		if ($type == 2) {
			$this->extras->order_by('nas_ip_address');
		} else if ($type == 3) {
			$this->extras->order_by('nas_ip_address');
			$this->extras->order_by('user_name');
		}
		$this->extras->limit($max, $start);
		$this->extras->order_by('id', 'desc');
		$query = $this->extras->get();
		return $query->result_array();
	}
	public function countUsageBySummary($realm, $type, $startdate, $enddate) {
		$this->extras->db_select();
		$this->extras->from('usages')
			->where('stop_time is not null');
		if (!is_null($realm)) {
			$this->extras->where("user_name like '%".$realm."'");
		}
		if (!is_null($startdate)) {
			$this->extras->where('start_time > '.$startdate);
		}
		if (!is_null($enddate)) {
			$this->extras->where('start_time < '.$enddate);
		}
		$count = $this->extras->count_all_results();
		log_message('info', 'count:'.json_encode($count));
		return $count;
	}
	public function getTotalSessionTimeBySummary($realm, $type, $startdate, $enddate) {
		$this->extras->db_select();
		$this->extras->select_sum('acct_session_time')
			->from('usages')
			->where('stop_time is not null');
		if (!is_null($realm)) {
			$this->extras->where("user_name like '%".$realm."'");
		}
		if (!is_null($startdate)) {
			$this->extras->where('start_time > '.$startdate);
		}
		if (!is_null($enddate)) {
			$this->extras->where('start_time < '.$enddate);
		}
		$query = $this->extras->get();
		$result = $query->row_array();
		return $result['acct_session_time'];
	}
	/****************************************
	 * new implementation 3-23-2015 [start]
	 ****************************************/
	public function fetchUsagesByUsername2($username, $realm, $year, $month, $day, $start, $max, $order) {
		try {
			$dayStart = mktime(0, 0, 0, $month, $day, $year);
			$dayEnd = mktime(23, 59, 59, $month, $day, $year);
			$this->extras->db_select();
			$sql = "select user_name, calling_station_id, acct_session_id, framed_ip_address, nas_port_id, nas_identifier, ".
				"min(acct_status_type) as status_start, max(acct_status_type) as status_end, ".
				"max(acct_input_octets) as end_upload, max(acct_output_octets) as end_download, ".
				"max(event_timestamp) - min(event_timestamp) as duration, ".
				"min(event_timestamp) as ts_min, max(event_timestamp) as ts_max ".
				"from usages_new ".
				"where user_name = ? ".
				"and event_timestamp between ? and ? ".
				"group by acct_session_id ".
				"order by ".$order['column']." ".$order['dir']." ".
				"limit ".$start.", ".$max;
			log_message('info', 'fetch by username: '.$sql);
			$query = $this->extras->query($sql, array($username."@".$realm, $dayStart, $dayEnd));
			return $query->result_array();
		} catch (Exception $e) {
			log_message('debug', json_encode($e));
			return false;
		}
	}
	public function countUsagesByUsername2($username, $realm, $year, $month, $day) {
		try {
			$dayStart = mktime(0, 0, 0, $month, $day, $year);
			$dayEnd = mktime(23, 59, 59, $month, $day, $year);
			$this->extras->db_select();
			$sql = "select count(*) as cnt from (".
				"select user_name ".
				"from usages_new ".
				"where user_name = ? ".
				"and event_timestamp between ? and ? ".
				"group by acct_session_id".
				") rowcount";
			log_message('info', 'count by username: '.$sql);
			$query = $this->extras->query($sql, array($username."@".$realm, $dayStart, $dayEnd));
			$result = $query->row_array();
			return $result['cnt'];
		} catch (Exception $e) {
			log_message('debug', json_encode($e));
			return false;	
		}
	}
	public function getTotalSessionTimeByUsername2($username, $realm, $year, $month, $day) {
		try {
			$dayStart = mktime(0, 0, 0, $month, $day, $year);
			$dayEnd = mktime(23, 59, 59, $month, $day, $year);
			$this->extras->db_select();
			$sql = "select sum(duration) as total_seconds from (".
				"select user_name, max(event_timestamp) - min(event_timestamp) as duration ".
				"from usages_new ".
				"where user_name = ? ".
				"and event_timestamp between ? and ? ".
				"group by acct_session_id".
				") rowcount";
			log_message('info', 'session time by username: '.$sql);
			$query = $this->extras->query($sql, array($username."@".$realm, $dayStart, $dayEnd));
			$result = $query->row_array();
			return $result['total_seconds'];
		} catch (Exception $e) {
			log_message('debug', json_encode($e));
			return false;		
		}
	}
	public function fetchUsageByIPAddress2($ipaddress, $year, $month, $day, $start, $max, $order) {
		try {
			$dayStart = mktime(0, 0, 0, $month, $day, $year);
			$dayEnd = mktime(23, 59, 59, $month, $day, $year);
			$this->extras->db_select();
			$sql = "select user_name, calling_station_id, acct_session_id, framed_ip_address, nas_port_id, nas_identifier, ".
				"min(acct_status_type) as status_start, max(acct_status_type) as status_end, ".
				"max(acct_input_octets) as end_upload, max(acct_output_octets) as end_download, ".
				"max(event_timestamp) - min(event_timestamp) as duration, ".
				"min(event_timestamp) as ts_min, max(event_timestamp) as ts_max ".
				"from usages_new ".
				"where framed_ip_address = ? ".
				"and event_timestamp between ? and ? ".
				"group by acct_session_id ".
				"order by ".$order['column']." ".$order['dir']." ".
				"limit ".$start.", ".$max;
			log_message('info', 'fetch by ip: '.$sql);
			$query = $this->extras->query($sql, array($ipaddress, $dayStart, $dayEnd));
			return $query->result_array();
		} catch (Exception $e) {
			log_message('debug', json_encode($e));
			return false;	
		}
	}
	public function countUsagesByIPAddress2($ipaddress, $year, $month, $day) {
		try {
			$dayStart = mktime(0, 0, 0, $month, $day, $year);
			$dayEnd = mktime(23, 59, 59, $month, $day, $year);
			$this->extras->db_select();
			$sql = "select count(*) as cnt from (".
				"select user_name ".
				"from usages_new ".
				"where framed_ip_address = ? ".
				"and event_timestamp between ? and ? ".
				"group by acct_session_id".
				") rowcount";
			log_message('info', 'count by ip: '.$sql);
			$query = $this->extras->query($sql, array($ipaddress, $dayStart, $dayEnd));
			$result = $query->row_array();
			return $result['cnt'];
		} catch (Exception $e) {
			log_message('debug', json_encode($e));
			return false;	
		}
	}
	public function getTotalSessionTimeByIPAddress2($ipaddress, $year, $month, $day) {
		try {
			$dayStart = mktime(0, 0, 0, $month, $day, $year);
			$dayEnd = mktime(23, 59, 59, $month, $day, $year);
			$this->extras->db_select();
			$sql = "select sum(duration) as total_seconds from (".
				"select user_name, max(event_timestamp) - min(event_timestamp) as duration ".
				"from usages_new ".
				"where framed_ip_address = ? ".
				"and event_timestamp between ? and ? ".
				"group by acct_session_id".
				") rowcount";
			log_message('info', 'session time by ip: '.$sql);
			$query = $this->extras->query($sql, array($ipaddress, $dayStart, $dayEnd));
			$result = $query->row_array();
			return $result['total_seconds'];
		} catch (Exception $e) {
			log_message('debug', json_encode($e));
			return false;
		}
	}
	public function getMinId($realm, $year, $month, $day, $hour) {
		try {
			$periodStart = mktime($hour, 0, 0, $month, $day, $year);
			$this->extras->db_select();
			$sql = "select min(id) as id_min from usages_new where event_timestamp = ?".(is_null($realm) ? "" : " and innove_realm = ?");
			log_message('info', 'get min for timestamp ('.$periodStart.', '.date("Y-m-d H:i:s", $periodStart).'): '.$sql);
			$params = array();
			$params[] = strval($periodStart);
			if (!is_null($realm)) {
				$params[] = $realm;
			}
			$query = $this->extras->query($sql, $params);
			$result = $query->row_array();
			return $result['id_min'];
		} catch (Exception $e) {
			log_message('debug', json_encode($e));
			return false;
		}
	}
	public function getMaxId($realm, $year, $month, $day, $hour) {
		try {
			$periodEnd = mktime($hour, 59, 59, $month, $day, $year);
			$this->extras->db_select();
			$sql = "select max(id) as id_max from usages_new where event_timestamp = ?".(is_null($realm) ? "" : " and innove_realm = ?");
			log_message('info', 'get max for timestamp ('.$periodEnd.', '.date("Y-m-d H:i:s", $periodEnd).'): '.$sql);
			$params = array();
			$params[] = strval($periodEnd);
			if (!is_null($realm)) {
				$params[] = $realm;
			}
			$query = $this->extras->query($sql, $params);
			$result = $query->row_array();
			return $result['id_max'];
		} catch (Exception $e) {
			log_message('debug', json_encode($e));
			return false;
		}
	}
	public function fetchUsageBySummary2($idStart, $idEnd, $type, $start, $max) {
		try {
			$this->extras->db_select();
			$sql = "select distinct user_name, calling_station_id, nas_identifier, nas_port_id ".
				"from usages_new ".
				"where id between ? and ? ".
				"order by ";
			if ($type == 2) {
				$sql .= "nas_identifier asc, ";
			} else if ($type == 3) {
				$sql .= "nas_identifier asc, nas_port_id asc, ";
			}
			$sql .= "user_name asc ".
				"limit ".$start.", ".$max;
			log_message('info', 'fetch by summary: '.$sql);
			$query = $this->extras->query($sql, array($idStart, $idEnd));
			return $query->result_array();
		} catch (Exception $e) {
			log_message('debug', json_encode($e));
			return false;
		}
	}
	public function countUsageBySummary2($idStart, $idEnd) {
		try {
			$this->extras->db_select();
			$sql = "select count(distinct user_name) as cnt from usages_new ".
				"where id between ? and ?";
			log_message('info', 'count by summary: '.$sql);
			$query = $this->extras->query($sql, array($idStart, $idEnd));
			$result = $query->row_array();
			log_message('info', $result['cnt']);
			return $result['cnt'];
		} catch (Exception $e) {
			log_message('debug', json_encode($e));
			return false;
		}
	}
	/*
	public function getTotalSessionTimeBySummary2($realm, $type, $year, $month, $day) {
		try {
			$this->extras->db_select();
			$sql = "select sum(duration) as total_seconds from (".
				"select user_name, from_unixtime(min(event_timestamp)) as event_ts_start, max(event_timestamp) - min(event_timestamp) as duration ".
				"from usages_new ".
				(is_null($realm) ? "" : "where innove_realm = ? ").
				"group by acct_session_id ".
				"having date(event_ts_start) = ? ".
				") rowcount";
			log_message('info', 'session time by summary: '.$sql);
			$params = array();
			if (!is_null($realm)) {
				$params[] = $realm;
			}
			$params[] = $year."-".$month."-".$day;
			$query = $this->extras->query($sql, $params);
			$result = $query->row_array();
			log_message('info', $result['total_seconds']);
			return $result['total_seconds'];
		} catch (Exception $e) {
			log_message('debug', json_encode($e));
			return false;
		}
	}
	*/
	/****************************************
	 * new implementation 3-23-2015 [end]
	 ****************************************/
	public function fetchAccountTransactions($username, $realm, $startdate, $enddate, $start, $max) {
		$this->extras->db_select();
		$this->extras->select('*')
			->from('sysuser_activity_log')
			->where('sysuser', $username);
		if (!is_null($startdate)) {
			$this->extras->where('(DATE(timestamp) > DATE("'.date('Y-m-d H:i:s', $startdate).'") or DATE(timestamp) = DATE("'.date('Y-m-d H:i:s', $startdate).'"))');
		}
		if (!is_null($enddate)) {
			$this->extras->where('(DATE(timestamp) < DATE("'.date('Y-m-d H:i:s', $enddate).'") or DATE(timestamp) = DATE("'.date('Y-m-d H:i:s', $enddate).'"))');
		}
		$query = $this->extras->order_by('timestamp', 'desc')
			->limit($max, $start)
			->get();
		return $query->result_array();
	}
	public function countAccountTransactions($username, $realm, $startdate, $enddate) {
		$this->extras->db_select();
		$this->extras->from('sysuser_activity_log')
			->where('sysuser', $username);
		if (!is_null($startdate)) {
			$this->extras->where('(DATE(timestamp) > DATE("'.date('Y-m-d H:i:s', $startdate).'") or DATE(timestamp) = DATE("'.date('Y-m-d H:i:s', $startdate).'"))');
		}
		if (!is_null($enddate)) {
			$this->extras->where('(DATE(timestamp) < DATE("'.date('Y-m-d H:i:s', $enddate).'") or DATE(timestamp) = DATE("'.date('Y-m-d H:i:s', $enddate).'"))');
		}
		$count = $this->extras->count_all_results();
		return $count;
	}
	public function fetchAuthenticationLogs($username, $realm, $month, $day, $year, $hour, $start, $max) {
		$this->extras->db_select();
		$query = $this->extras->select('*')
			->from('authentication_log')
			->where('username', $username.'@'.$realm)
			->where('month(date_and_time) = '.$month)
			->where('day(date_and_time) = '.$day)
			->where('year(date_and_time) = '.$year)
			->where('hour(date_and_time) = '.$hour)
			->order_by('date_and_time', 'desc')
			->limit($max, $start)
			->get();
		return $query->result_array();
	}
	public function countAuthenticationLogs($username, $realm, $month, $day, $year, $hour) {
		$this->extras->db_select();
		$count = $this->extras->from('authentication_log')
			->where('username', $username.'@'.$realm)
			->where('month(date_and_time) = '.$month)
			->where('day(date_and_time) = '.$day)
			->where('year(date_and_time) = '.$year)
			->where('hour(date_and_time) = '.$hour)
			->count_all_results();
		return $count;
	}

}
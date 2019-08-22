<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ipaddress extends CI_Model {
	private $utility;
	private $extras;
	private $TABLENAME = 'TBLIPADDRESS';
	private $COLUMN_ID = 'ID';
	private $COLUMN_IPADDRESS = 'IPADDRESS';
	private $COLUMN_LOCATION = 'LOCATION';
	private $COLUMN_IPUSED = 'IPUSED';
	private $COLUMN_GPONIP = 'GPONIP';
	private $COLUMN_DESCRIPTION = 'DESCRIPTION';
	private $COLUMN_USERNAME = 'USERNAME';
	private $COLUMN_STATUS = 'STATUS';
	private $COLUMN_MODIFIEDDATE = 'MODIFIED_DATE';
	public $LOCATIONS = ['VALERO', 'SAN JUAN', 'TARLAC', 'BATANGAS', 'CEBU', 'DAVAO', 'MAKATI', 'LAHUG'];
	/**************************************************
	 * added february 2017
	 * - for IPv6
	 **************************************************/
	private $TABLENAME_V6 = 'TBLIPV6ADDRESS';
	private $COLUMN_IPADDRESS_V6 = 'IPV6ADDR';
	private $COLUMN_IPUSED_V6 = 'IPV6USED';
	/**************************************************
	 * /added february 2017
	 **************************************************/

	function __construct() {
		parent::__construct();
		$this->utility = $this->load->database('utility', true);
		$this->extras = $this->load->database('extras', true);
	}

	/**************************************************
	 * added february 2017
	 * - for IPv6
	 **************************************************/
	public function createV6($ipaddress, $location, $description, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		$now = date('Y-m-d H:i:s', time());
		$now = substr($now, 2, strlen($now));
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "insert into ".$this->TABLENAME_V6." (".
			$this->COLUMN_IPADDRESS_V6.", ".
			$this->COLUMN_LOCATION.", ".
			$this->COLUMN_IPUSED_V6.", ".
			$this->COLUMN_DESCRIPTION.", ".
			$this->COLUMN_USERNAME.", ".
			$this->COLUMN_STATUS.", ".
			$this->COLUMN_MODIFIEDDATE.") values ".
			"(:ipaddress, :location, 'N', :description, '-', null, TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS'))";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		oci_bind_by_name($compiled, ':location', $location);
		oci_bind_by_name($compiled, ':description', $description);
		oci_bind_by_name($compiled, ':now', $now);
		$result = oci_execute($compiled);
		return $result ? true : false;
	}
	public function markV6AsUsed($ipaddress, $cn, $status, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$exists = $this->ipV6Exists($ipaddress, $username, $password, $host, $port, $schema);
		if (is_null($username) || is_null($status)) {
			return false;
		}
		if (!$exists) {
			return false;
		}
		$free = $this->isV6Free($ipaddress, $username, $password, $host, $port, $schema);
		if (!$free) {
			return false;
		}
		$now = date('Y-m-d H:i:s', time());
		$sql = "update ".$this->TABLENAME_V6." set ".
			$this->COLUMN_IPUSED_V6." = 'Y', ".
			$this->COLUMN_USERNAME." = :cn, ".
			$this->COLUMN_STATUS." = :status, ".
			$this->COLUMN_MODIFIEDDATE." = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') ".
			"where ".$this->COLUMN_IPADDRESS_V6." = :ipaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':cn', $cn);
		oci_bind_by_name($compiled, ':status', $status);
		$now = substr($now, 2, strlen($now));
		oci_bind_by_name($compiled, ':now', $now);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		$result = oci_execute($compiled);
		return $result;
	}
	public function updateV6Status($ipaddress, $status, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "update ".$this->TABLENAME_V6." set ".
			$this->COLUMN_STATUS." = :status, ".
			$this->COLUMN_MODIFIEDDATE." = TO_TIMESTAMP(:now,'RR-MM-DD HH24:MI:SS') ".
			"where ".$this->COLUMN_IPADDRESS_V6." = :ipaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':status', $status);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		$now = date('Y-m-d H:i:s', time());
		$now = substr($now, 2, strlen($now));
		oci_bind_by_name($compiled, ':now', $now);
		$result = oci_execute($compiled);
		return $result;
	}
	public function deleteV6($ipaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$free = $this->isV6Free($ipaddress, $username, $password, $host, $port, $schema);
		if ($free) {
			$sql = "delete from ".$this->TABLENAME_V6." where ".$this->COLUMN_IPADDRESS_V6." = :ipaddress";
			log_message('info', $sql);
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
			$result = oci_execute($compiled);
			return $result;
		} else {
			return false;
		}
	}
	public function freeUpV6($ipaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "update ".$this->TABLENAME_V6." set ".
			$this->COLUMN_IPUSED_V6." = 'N', ".
			$this->COLUMN_USERNAME." = '-', ".
			$this->COLUMN_STATUS." = null, ".
			$this->COLUMN_MODIFIEDDATE." = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') ".
			"where ".$this->COLUMN_IPADDRESS_V6." = :ipaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		$now = date('Y-m-d H:i:s', time());
		$now = substr($now, 2, strlen($now));
		oci_bind_by_name($compiled, ':now', $now);
		$result = oci_execute($compiled);
		return $result;
	}
	public function ipV6Exists($ipaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select count(*) as CNT from ".$this->TABLENAME_V6." where ".$this->COLUMN_IPADDRESS_V6." = :ipaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		$result = oci_execute($compiled);
		$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		return intval($row['CNT']) == 0 ? false : true;
	}
	public function isIPV6Valid($ipaddress) {
		$hasSubnet = strpos($ipaddress, '/') !== false;
		if ($hasSubnet) {
			$parts = explode('/', $ipaddress);
			$thisAddress = $parts[0];
		} else {
			$thisAddress = $ipaddress;
		}
		return filter_var($thisAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
	}
	public function isV6Free($ipaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select ".$this->COLUMN_IPUSED_V6." from ".$this->TABLENAME_V6." where ".$this->COLUMN_IPADDRESS_V6." = :ipaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		$result = oci_execute($compiled);
		$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		if ($row === false) {
			return false;
		} else {
			return $row[$this->COLUMN_IPUSED_V6] == 'N' ? true : false;
		}
	}
	public function fetchAllUnusedV6($location) {
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_ID.', '.$this->COLUMN_IPADDRESS_V6)
			->from($this->TABLENAME_V6)
			->where($this->COLUMN_IPUSED_V6, 'N');
		if (!is_null($location)) {
			$this->utility->where($this->COLUMN_LOCATION, $location);
		}
		$query = $this->utility->order_by($this->COLUMN_IPADDRESS_V6, 'asc')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function fetchAllUsedV6($location) {
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_ID.', '.$this->COLUMN_IPADDRESS_V6)
			->from($this->TABLENAME_V6)
			->where($this->COLUMN_IPUSED_V6, 'Y');
		if (!is_null($location)) {
			$this->utility->where($this->COLUMN_LOCATION, $location);
		}
		$query = $this->utility->order_by($this->COLUMN_IPADDRESS_V6, 'asc')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function fetchAllV6($ipaddress, $location, $isUsed, $username, $exact, $wildcardLocation, $status, $start, $max, $order) {
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_ID.', '.
			$this->COLUMN_IPADDRESS_V6.', '.
			$this->COLUMN_LOCATION.', '.
			$this->COLUMN_IPUSED_V6.', '.
			$this->COLUMN_DESCRIPTION.', '.
			$this->COLUMN_USERNAME.', '.
			$this->COLUMN_STATUS.', '.
			$this->COLUMN_MODIFIEDDATE)
			->from($this->TABLENAME_V6);
		if (!is_null($ipaddress)) {
			if ($exact) {
				$this->utility->where($this->COLUMN_IPADDRESS_V6, $ipaddress);
			} else {
				if ($wildcardLocation == 'after') {
					$this->utility->where("regexp_like(".$this->COLUMN_IPADDRESS_V6.", '^".$ipaddress."', 'c')");
				} else if ($wildcardLocation == 'before') {
					$this->utility->where("regexp_like(".$this->COLUMN_IPADDRESS_V6.", '".$ipaddress."$', 'c')");
				} else if ($wildcardLocation == 'both') {
					$this->utility->where("regexp_like(".$this->COLUMN_IPADDRESS_V6.", '".$ipaddress."', 'c')");
				}
			}
		}
		if (!is_null($location)) {
			$this->utility->where($this->COLUMN_LOCATION, $location);
		}
		if (!is_null($isUsed)) {
			$this->utility->where($this->COLUMN_IPUSED_V6, $isUsed);
		}
		if (!is_null($username)) {
			$this->utility->where($this->COLUMN_USERNAME, $username);
		}
		if (!is_null($status)) {
			$this->utility->where($this->COLUMN_STATUS, $status);
		}
		if (is_null($order)) {
			$this->utility->order_by($this->COLUMN_IPADDRESS_V6, 'asc');
		} else {
			if ($order['column'] != $this->COLUMN_IPADDRESS_V6) {
				$this->utility->order_by($order['column'], $order['dir']);
			}
			$this->utility->order_by($this->COLUMN_IPADDRESS_V6, $order['column'] == $this->COLUMN_IPADDRESS_V6 ? $order['dir'] : 'asc');
		}
		$query = $this->utility->limit($start == 0 ? $max + 1 : $max, $start == 0 ? $start : $start + 1)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countIPV6Addresses($ipaddress, $location, $isUsed, $username, $exact, $wildcardLocation, $status) {
		$this->utility->db_select();
		$this->utility->from($this->TABLENAME_V6);
		if (!is_null($ipaddress)) {
			if ($exact) {
				$this->utility->where($this->COLUMN_IPADDRESS_V6, $ipaddress);
			} else {
				if ($wildcardLocation == 'after') {
					$this->utility->where("regexp_like(".$this->COLUMN_IPADDRESS_V6.", '^".$ipaddress."', 'c')");
				} else if ($wildcardLocation == 'before') {
					$this->utility->where("regexp_like(".$this->COLUMN_IPADDRESS_V6.", '".$ipaddress."$', 'c')");
				} else if ($wildcardLocation == 'both') {
					$this->utility->where("regexp_like(".$this->COLUMN_IPADDRESS_V6.", '".$ipaddress."', 'c')");
				}
			}
		}
		if (!is_null($location)) {
			$this->utility->where($this->COLUMN_LOCATION, $location);
		}
		if (!is_null($isUsed)) {
			$this->utility->where($this->COLUMN_IPUSED_V6, $isUsed);
		}
		if (!is_null($username)) {
			$this->utility->where($this->COLUMN_USERNAME, $username);
		}
		if (!is_null($status)) {
			$this->utility->where($this->COLUMN_STATUS, $status);
		}
		$count = $this->utility->count_all_results();
		return $count;
	}
	public function rowDataToIPV6Array($data) {
		$now = date('Y-m-d H:i:s', time());
		$ipaddress = array(
			$this->COLUMN_IPADDRESS => trim($data[0]),
			$this->COLUMN_LOCATION => trim($data[1]),
			$this->COLUMN_IPUSED => 'N',
			$this->COLUMN_DESCRIPTION => is_null($data[2]) ? '' : $data[2],
			$this->COLUMN_USERNAME => '',
			$this->COLUMN_STATUS => null,
			$this->COLUMN_MODIFIEDDATE => $now);
		return $ipaddress;
	}
	public function findIpV6Address($ipaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select * from ".$this->TABLENAME_V6." where ".$this->COLUMN_IPADDRESS_V6." = :ipaddress";
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		$result = oci_execute($compiled);
		$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		return $row;
	}
	/**************************************************
	 * /added february 2017
	 **************************************************/

	public function create($ipaddress, $location, $isGPON, $description, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', time());
		$query = "insert into TBLIPADDRESS (IPADDRESS, LOCATION, IPUSED, GPONIP, DESCRIPTION, USERNAME, STATUS, MODIFIED_DATE) values ".
			"('".$ipaddress."', '".$location."', 'N', '".$isGPON."', '".$description."', '-', null, TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS'))";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
		//*/
		///*
		$now = date('Y-m-d H:i:s', time());
		$now = substr($now, 2, strlen($now));
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "insert into TBLIPADDRESS (IPADDRESS, LOCATION, IPUSED, GPONIP, DESCRIPTION, USERNAME, STATUS, MODIFIED_DATE) values ".
			"(:ipaddress, :location, 'N', :isgpon, :description, '-', null, TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS'))";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		oci_bind_by_name($compiled, ':location', $location);
		oci_bind_by_name($compiled, ':isgpon', $isGPON);
		oci_bind_by_name($compiled, ':description', $description);
		oci_bind_by_name($compiled, ':now', $now);
		$result = oci_execute($compiled);
		return $result ? true : false;
		//*/
	}
	/*
	public function modify($ipaddressRef, $ipaddress, $location, $ipUsed, $isGPON, $description, $username, $status) {
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', time());
		$data = [];
		if ($ipaddressRef != $ipaddress) { //ipaddress to be changed
			$newExists = $this->ipExists($ipaddress);
			if (!$newExists) { //new ip address not yet in pool
				$data[$this->COLUMN_IPADDRESS] = $ipaddress;
			} else { //new ip address already in pool
				return false;
			}
		}
		if (!is_null($location)) {
			$data[$this->COLUMN_LOCATION] = $location;
		}
		if (!is_null($ipUsed)) {
			$data[$this->COLUMN_IPUSED] = $ipUsed;
			if ($ipUsed == 'Y') {
				if (is_null($username) || is_null($status)) { //cannot be set to 'Y' without username and status
					return false;
				}
			} else {
				$data[$this->COLUMN_USERNAME] = '';
				$data[$this->COLUMN_STATUS] = null;
			}
		}
		if (!is_null($isGPON)) {
			$data[$this->COLUMN_GPONIP] = $isGPON;
		}
		if (!is_null($description)) {
			$data[$this->COLUMN_DESCRIPTION] = $description;
		}
		$this->utility->where($this->COLUMN_IPADDRESS, $ipaddressRef)
			->update($this->TABLENAME, $data);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	*/
	public function markAsUsed($ipaddress, $cn, $status, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', time());
		$exists = $this->ipExists($ipaddress);
		if (is_null($username) || is_null($status)) {
			return false;
		}
		if (!$exists) {
			return false;
		}
		$free = $this->isFree($ipaddress);
		if (!$free) {
			return false;
		}
		$now = date('Y-m-d H:i:s', time());
		$query = "update TBLIPADDRESS set IPUSED = 'Y', USERNAME = '".$username."', STATUS = '".$status."', MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') ".
			"where IPADDRESS = '".$ipaddress."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
		//*/
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$exists = $this->ipExists($ipaddress, $username, $password, $host, $port, $schema);
		if (is_null($username) || is_null($status)) {
			return false;
		}
		if (!$exists) {
			return false;
		}
		$free = $this->isFree($ipaddress, $username, $password, $host, $port, $schema);
		if (!$free) {
			return false;
		}
		$now = date('Y-m-d H:i:s', time());
		$sql = "update TBLIPADDRESS set IPUSED = 'Y', USERNAME = :cn, STATUS = :status, MODIFIED_DATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') where IPADDRESS = :ipaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':cn', $cn);
		oci_bind_by_name($compiled, ':status', $status);
		$now = substr($now, 2, strlen($now));
		oci_bind_by_name($compiled, ':now', $now);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		$result = oci_execute($compiled);
		return $result;
	}
	public function updateStatus($ipaddress, $status, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', time());
		$query = "update TBLIPADDRESS set STATUS = '".$status."', MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') ".
			"where IPADDRESS = '".$ipaddress."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
		//*/
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "update TBLIPADDRESS set STATUS = :status, MODIFIED_DATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') where IPADDRESS = :ipaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':status', $status);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		$now = date('Y-m-d H:i:s', time());
		$now = substr($now, 2, strlen($now));
		oci_bind_by_name($compiled, ':now', $now);
		$result = oci_execute($compiled);
		return $result;
	}
	public function delete($ipaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$free = $this->isFree($ipaddress);
		if ($free) {
			$this->utility->where($this->COLUMN_IPADDRESS, $ipaddress)
				->delete($this->TABLENAME);
			return $this->utility->affected_rows() == 0 ? false : true;
		} else {
			return false;
		}
		//*/
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$free = $this->isFree($ipaddress, $username, $password, $host, $port, $schema);
		if ($free) {
			$sql = "delete from TBLIPADDRESS where IPADDRESS = :ipaddress";
			log_message('info', $sql);
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
			$result = oci_execute($compiled);
			return $result;
		} else {
			return false;
		}
	}
	public function freeUp($ipaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', time());
		$query = "update TBLIPADDRESS set IPUSED = 'N', USERNAME = '-', STATUS = null, MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') ".
			"where IPADDRESS = '".$ipaddress."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
		//*/
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "update TBLIPADDRESS set IPUSED = 'N', USERNAME = '-', STATUS = null, MODIFIED_DATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') where IPADDRESS = :ipaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		$now = date('Y-m-d H:i:s', time());
		$now = substr($now, 2, strlen($now));
		oci_bind_by_name($compiled, ':now', $now);
		$result = oci_execute($compiled);
		return $result;
	}
	public function ipExists($ipaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$count = $this->utility->from($this->TABLENAME)
			->where($this->COLUMN_IPADDRESS, $ipaddress)
			->count_all_results();
		return $count == 0 ? false : true;
		//*/
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select count(*) as CNT from ".$this->TABLENAME." where ".$this->COLUMN_IPADDRESS." = :ipaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		$result = oci_execute($compiled);
		$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		return intval($row['CNT']) == 0 ? false : true;
	}
	public function isIPValid($ipaddress) {
		return $this->input->valid_ip($ipaddress);
	}
	public function isFree($ipaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_IPUSED)
			->from($this->TABLENAME)
			->where($this->COLUMN_IPADDRESS, $ipaddress)
			->get();
		if ($query->num_rows() == 0) {
			return false;
		} else {
			$row = $query->row_array();
			return $row[$this->COLUMN_IPUSED] == 'N' ? true : false;
		}
		//*/
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select ".$this->COLUMN_IPUSED." from ".$this->TABLENAME." where ".$this->COLUMN_IPADDRESS." = :ipaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		$result = oci_execute($compiled);
		$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		if ($row === false) {
			return false;
		} else {
			return $row[$this->COLUMN_IPUSED] == 'N' ? true : false;
		}
	}
	public function isGPON($ipaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_GPONIP)
			->from($this->TABLENAME)
			->where($this->COLUMN_IPADDRESS, $ipaddress)
			->get();
		if ($query->num_rows() == 0) {
			return false;
		} else {
			$row = $query->row_array();
			return $row[$this->COLUMN_GPONIP] == 'Y' ? true : false;
		}
		//*/
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select ".$this->COLUMN_GPONIP." from ".$this->TABLENAME." where ".$this->COLUMN_IPADDRESS." = :ipaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		$result = oci_execute($compiled);
		$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		if ($row === false) {
			return false;
		} else {
			return $row[$this->COLUMN_GPONIP] == 'Y' ? true : false;
		}
	}
	public function fetchAllUnused($location, $isGPON) {
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_ID.', '.$this->COLUMN_IPADDRESS)
			->from($this->TABLENAME)
			->where($this->COLUMN_IPUSED, 'N');
		if (!is_null($location)) {
			$this->utility->where($this->COLUMN_LOCATION, $location);
		}
		if (!is_null($isGPON)) {
			$this->utility->where($this->COLUMN_GPONIP, $isGPON);
		}
		$query = $this->utility->order_by('IPADDRESS', 'asc')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function fetchAllUsed($location, $isGPON) {
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_ID.', '.$this->COLUMN_IPADDRESS)
			->from($this->TABLENAME)
			->where($this->COLUMN_IPUSED, 'Y');
		if (!is_null($location)) {
			$this->utility->where($this->COLUMN_LOCATION, $location);
		}
		if (!is_null($isGPON)) {
			$this->utility->where($this->COLUMN_GPONIP, $isGPON);
		}
		$query = $this->utility->order_by('IPADDRESS', 'asc')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();	
	}
	public function fetchAll($ipaddress, $location, $isUsed, $isGPON, $username, $exact, $wildcardLocation, $status, $start, $max, $order) {
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_ID.', '.
			$this->COLUMN_IPADDRESS.', '.
			$this->COLUMN_LOCATION.', '.
			$this->COLUMN_IPUSED.', '.
			$this->COLUMN_GPONIP.', '.
			$this->COLUMN_DESCRIPTION.', '.
			$this->COLUMN_USERNAME.', '.
			$this->COLUMN_STATUS.', '.
			$this->COLUMN_MODIFIEDDATE)
			->from($this->TABLENAME);
		if (!is_null($ipaddress)) {
			if ($exact) {
				$this->utility->where($this->COLUMN_IPADDRESS, $ipaddress);
			} else {
				if ($wildcardLocation == 'after') {
					$this->utility->where("regexp_like(IPADDRESS, '^".$ipaddress."', 'c')");
				} else if ($wildcardLocation == 'before') {
					$this->utility->where("regexp_like(IPADDRESS, '".$ipaddress."$', 'c')");
				} else if ($wildcardLocation == 'both') {
					$this->utility->where("regexp_like(IPADDRESS, '".$ipaddress."', 'c')");
				}
			}
		}
		if (!is_null($location)) {
			$this->utility->where($this->COLUMN_LOCATION, $location);
		}
		if (!is_null($isUsed)) {
			$this->utility->where($this->COLUMN_IPUSED, $isUsed);
		}
		if (!is_null($isGPON)) {
			$this->utility->where($this->COLUMN_GPONIP, $isGPON);
		}
		if (!is_null($username)) {
			$this->utility->where($this->COLUMN_USERNAME, $username);
		}
		if (!is_null($status)) {
			$this->utility->where($this->COLUMN_STATUS, $status);
		}
		if (is_null($order)) {
			$this->utility->order_by('IPADDRESS', 'asc');
		} else {
			if ($order['column'] != 'IPADDRESS') {
				$this->utility->order_by($order['column'], $order['dir']);
			}
			$this->utility->order_by('IPADDRESS', $order['column'] == 'IPADDRESS' ? $order['dir'] : 'asc');
		}
		$query = $this->utility->limit($start == 0 ? $max + 1 : $max, $start == 0 ? $start : $start + 1)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countIPAddresses($ipaddress, $location, $isUsed, $isGPON, $username, $exact, $wildcardLocation, $status) {
		$this->utility->db_select();
		$this->utility->from($this->TABLENAME);
		if (!is_null($ipaddress)) {
			if ($exact) {
				$this->utility->where($this->COLUMN_IPADDRESS, $ipaddress);
			} else {
				if ($wildcardLocation == 'after') {
					$this->utility->where("regexp_like(IPADDRESS, '^".$ipaddress."', 'c')");
				} else if ($wildcardLocation == 'before') {
					$this->utility->where("regexp_like(IPADDRESS, '".$ipaddress."$', 'c')");
				} else if ($wildcardLocation == 'both') {
					$this->utility->where("regexp_like(IPADDRESS, '".$ipaddress."', 'c')");
				}
			}
		}
		if (!is_null($location)) {
			$this->utility->where($this->COLUMN_LOCATION, $location);
		}
		if (!is_null($isUsed)) {
			$this->utility->where($this->COLUMN_IPUSED, $isUsed);
		}
		if (!is_null($isGPON)) {
			$this->utility->where($this->COLUMN_GPONIP, $isGPON);
		}
		if (!is_null($username)) {
			$this->utility->where($this->COLUMN_USERNAME, $username);
		}
		if (!is_null($status)) {
			$this->utility->where($this->COLUMN_STATUS, $status);
		}
		$count = $this->utility->count_all_results();
		return $count;
	}
	public function fetchAllLocationsFromExtras() {
		$this->extras->db_select();
		$query = $this->extras->select('location')
			->from('locations')
			->order_by('id', 'asc')
			->get();
		$result = $query->result_array();
		$locations = array();
		for ($i = 0; $i < count($result); $i++) {
			$locations[] = $result[$i]['location'];
		}
		return $locations;
	}
	public function fetchAllLocations() {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_LOCATION)
			->distinct()
			->from($this->TABLENAME)
			->order_by($this->COLUMN_LOCATION, 'asc')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function getLocation($ipaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_LOCATION)
			->from($this->TABLENAME)
			->where($this->COLUMN_IPADDRESS, $ipaddress)
			->get();
		return $query->num_rows() == 0 ? false : $query->row_array();
		//*/
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select ".$this->COLUMN_LOCATION." from ".$this->TABLENAME." where ".$this->COLUMN_IPADDRESS." = :ipaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		$result = oci_execute($compiled);
		$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		return $row;
	}
	public function rowDataToIPArray($data) {
		$now = date('Y-m-d H:i:s', time());
		$ipaddress = array(
			$this->COLUMN_IPADDRESS => trim($data[0]),
			$this->COLUMN_LOCATION => trim($data[1]),
			$this->COLUMN_IPUSED => 'N',
			$this->COLUMN_GPONIP => trim($data[2]),
			$this->COLUMN_DESCRIPTION => is_null($data[3]) ? '' : $data[3],
			$this->COLUMN_USERNAME => '',
			$this->COLUMN_STATUS => null,
			$this->COLUMN_MODIFIEDDATE => $now);
		return $ipaddress;
	}
	public function rowDataToIPArrayForV6($data) {
		$now = date('Y-m-d H:i:s', time());
		$ipaddress = array(
			$this->COLUMN_IPADDRESS_V6 => trim($data[0]).trim($data[1]),
			$this->COLUMN_LOCATION => trim($data[2]),
			$this->COLUMN_IPUSED_V6 => 'N',
			$this->COLUMN_DESCRIPTION => is_null($data) ? '' : $data[3],
			$this->COLUMN_USERNAME => '',
			$this->COLUMN_STATUS => null,
			$this->COLUMN_MODIFIEDDATE => $now);
		return $ipaddress;
	}
	public function findIpAddress($ipaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select * from ".$this->TABLENAME." where ".$this->COLUMN_IPADDRESS." = :ipaddress";
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':ipaddress', $ipaddress);
		$result = oci_execute($compiled);
		$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		return $row;
	}
	/**************************************************
	 * added november 2016
	 * - for getting ip address via cabinet
	 **************************************************/
	public function getNextAvailableIpAddress($location, $isGPON, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_IPADDRESS)
			->from($this->TABLENAME)
			->where($this->COLUMN_IPUSED, 'N')
			->where($this->COLUMN_LOCATION, $location)
			->where($this->COLUMN_GPONIP, $isGPON ? 'Y' : 'N')
			->order_by($this->COLUMN_IPADDRESS, 'asc')
			->limit(2); //translates to rnum < 2 (gets rnum = 1)
		$query = $this->utility->get();
		return $query->row_array();
	}
	public function getNextAvailableIpV6Address($location, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_IPADDRESS_V6)
			->from($this->TABLENAME_V6)
			->where($this->COLUMN_IPUSED_V6, 'N')
			->where($this->COLUMN_LOCATION, $location)
			->order_by($this->COLUMN_IPADDRESS_V6, 'asc')
			->limit(2); //translates to rnum < 2 (gets rnum = 1)
		$query = $this->utility->get();
		return $query->row_array();
	}
}
	<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Netaddress extends CI_Model {
	private $utility;
	private $TABLENAME = 'TBLNETADDRESS';
	private $COLUMN_ID = 'ID';
	private $COLUMN_NETADDRESS = 'NETADDRESS';
	private $COLUMN_LOCATION = 'LOCATION';
	private $COLUMN_NETUSED = 'NETUSED';
	private $COLUMN_DESCRIPTION = 'DESCRIPTION';
	private $COLUMN_USERNAME = 'USERNAME';
	private $COLUMN_STATUS = 'STATUS';
	private $COLUMN_MODIFIEDDATE = 'MODIFIED_DATE';
	public $LOCATIONS = ['VALERO', 'SAN JUAN', 'TARLAC', 'BATANGAS', 'CEBU', 'DAVAO', 'MAKATI', 'LAHUG'];
	public $SUBNETS = ['28', '29', '30'];

	function __construct() {
		parent::__construct();
		$this->utility = $this->load->database('utility', true);
	}

	public function create($netaddress, $location, $description, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', time());
		$query = "insert into TBLNETADDRESS (NETADDRESS, LOCATION, NETUSED, DESCRIPTION, USERNAME, STATUS, MODIFIED_DATE) values ".
			"('".$netaddress."', '".$location."','N', '".$description."', '-', null, TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS'))";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
		//*/
		//*
		$now = date('Y-m-d H:i:s', time());
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "insert into TBLNETADDRESS (NETADDRESS, LOCATION, NETUSED, DESCRIPTION, USERNAME, STATUS, MODIFIED_DATE) values ".
			"(:netaddress, :location, 'N', :description, '-', null, TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS'))";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':netaddress', $netaddress);
		oci_bind_by_name($compiled, ':location', $location);
		oci_bind_by_name($compiled, ':description', $description);
		$now = substr($now, 2, strlen($now));
		oci_bind_by_name($compiled, ':now', $now);
		$result = oci_execute($compiled);
		return $result;
		//*/
	}
	/*
	public function modify($netaddressRef, $netaddress, $location, $netUsed, $description, $username, $status) {
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', time());
		$data = [];
		if ($netaddressRef != $netaddress) { //net address to be changed
			$newExists = $this->netExists();
			if (!$newExists) { //net address not yet in pool
				$data[$this->COLUMN_NETADDRESS] = $netaddress;
			} else { //net address already in pool
				return false;
			}
		}
		if (!is_null($location)) {
			$data[$this->COLUMN_LOCATION] = $location;
		}
		if (!is_null($netUsed)) {
			$data[$this->COLUMN_NETUSED] = $netUsed;
			if ($netUsed == 'Y') {
				if (is_null($username) || is_null($status)) { //cannot be set to 'Y' without username and status
					return false;
				}
			} else {
				$data[$this->COLUMN_USERNAME] = '';
				$data[$this->COLUMN_STATUS] = null;
			}
		}
		if (!is_null($description)) {
			$data[$this->COLUMN_DESCRIPTION] = $description;
		}
		$this->utility->where($this->COLUMN_NETADDRESS, $netaddressRef)
			->update($this->TABLENAME, $data);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	//*/
	public function markAsUsed($netaddress, $cn, $status, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', time());
		$exists = $this->netExists($netaddress);
		if (is_null($username) || is_null($status)) {
			return false;
		}
		if (!$exists) {
			return false;
		}
		$free = $this->isFree($netaddress);
		if (!$free) {
			return false;
		}
		$now = date('Y-m-d H:i:s', time());
		$query = "update TBLNETADDRESS set NETUSED = 'Y', USERNAME = '".$username."', STATUS = '".$status."', MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') ".
			"where NETADDRESS = '".$netaddress."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
		*/
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$exists = $this->netExists($netaddress, $username, $password, $host, $port, $schema);
		if (is_null($username) || is_null($status)) {
			return false;
		}
		if (!$exists) {
			return false;
		}
		$free = $this->isFree($netaddress, $username, $password, $host, $port, $schema);
		if (!$free) {
			return false;
		}
		$now = date('Y-m-d H:i:s', time());
		$sql = "update TBLNETADDRESS set NETUSED = 'Y', USERNAME = :cn, STATUS = :status, MODIFIED_DATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') where NETADDRESS = :netaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':cn', $cn);
		oci_bind_by_name($compiled, ':status', $status);
		$now = substr($now, 2, strlen($now));
		oci_bind_by_name($compiled, ':now', $now);
		oci_bind_by_name($compiled, ':netaddress', $netaddress);
		$result = oci_execute($compiled);
		return $result;
	}
	public function updateStatus($netaddress, $status, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', time());
		$query = "update TBLNETADDRESS set STATUS = '".$status."', MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') ".
			"where NETADDRESS = '".$netaddress."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
		//*/
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "update TBLNETADDRESS set STATUS = :status, MODIFIED_DATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') where NETADDRESS = :netaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':status', $status);
		oci_bind_by_name($compiled, ':netaddress', $netaddress);
		$now = date('Y-m-d H:i:s', time());
		$now = substr($now, 2, strlen($now));
		oci_bind_by_name($compiled, ':now', $now);
		$result = oci_execute($compiled);
		return $result;
	}
	public function delete($netaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$free = $this->isFree($netaddress);
		if ($free) {
			$this->utility->where($this->COLUMN_NETADDRESS, $netaddress)
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
		$free = $this->isFree($netaddress, $username, $password, $host, $port, $schema);
		if ($free) {
			$sql = "delete from TBLNETADDRESS where NETADDRESS = :netaddress";
			log_message('info', $sql);
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':netaddress', $netaddress);
			$result = oci_execute($compiled);
			return $result;
		} else {
			return false;
		}
	}
	public function freeUp($netaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', time());
		$query = "update TBLNETADDRESS set NETUSED = 'N', USERNAME = '-', STATUS = null, MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') ".
			"where NETADDRESS = '".$netaddress."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
		//*/
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "update TBLNETADDRESS set NETUSED = 'N', USERNAME = '-', STATUS = null, MODIFIED_DATE = TO_TIMESTAMP(:now, 'RR-MM-DD HH24:MI:SS') where NETADDRESS = :netaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':netaddress', $netaddress);
		$now = date('Y-m-d H:i:s', time());
		$now = substr($now, 2, strlen($now));
		oci_bind_by_name($compiled, ':now', $now);
		$result = oci_execute($compiled);
		return $result;
	}
	public function netExists($netaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$count = $this->utility->from($this->TABLENAME)
			->where($this->COLUMN_NETADDRESS, $netaddress)
			->count_all_results();
		return $count == 0 ? false : true;
		//*/
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select count(*) as CNT from ".$this->TABLENAME." where ".$this->COLUMN_NETADDRESS." = :netaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':netaddress', $netaddress);
		$result = oci_execute($compiled);
		$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		return intval($row['CNT']) == 0 ? false : true;
	}
	public function isNetValid($netaddress) {
		$parts = explode('/', $netaddress);
		$address = $parts[0];
		$subnet = '';
		if (count($parts) == 1) {
			return false;
		} else if (count($parts) == 2) {
			$subnet = $parts[1];
			if ($this->input->valid_ip($address)) {
				return intval($subnet) < 1 ? false : true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	public function isFree($netaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		/*
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_NETUSED)
			->from($this->TABLENAME)
			->where($this->COLUMN_NETADDRESS, $netaddress)
			->get();
		if ($query->num_rows() == 0) {
			return false;
		} else {
			$row = $query->row_array();
			return $row[$this->COLUMN_NETUSED] == 'N' ? true : false;
		}
		//*/
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select ".$this->COLUMN_NETUSED." from ".$this->TABLENAME." where ".$this->COLUMN_NETADDRESS." = :netaddress";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':netaddress', $netaddress);
		$result = oci_execute($compiled);
		$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		if ($row === false) {
			return false;
		} else {
			return $row[$this->COLUMN_NETUSED] == 'N' ? true : false;
		}
	}
	public function fetchAllUnused($location) {
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_ID.', '.$this->COLUMN_NETADDRESS)
			->from($this->TABLENAME)
			->where($this->COLUMN_NETUSED, 'N');
		if (!is_null($location)) {
			$this->utility->where($this->COLUMN_LOCATION, $location);
		}
		$query = $this->utility->order_by('NETADDRESS', 'asc')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function fetchAllUnusedWithLocationAndSubnet($location, $subnet) {
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_ID.', '.$this->COLUMN_NETADDRESS)
			->from($this->TABLENAME)
			->where($this->COLUMN_NETUSED, 'N');
		if (!is_null($location)) {
			$this->utility->where($this->COLUMN_LOCATION, $location);
		}
		if (!is_null($subnet)) {
			$this->utility->where("regexp_like(".$this->COLUMN_NETADDRESS.", '/".$subnet."$', 'c')");
		}
		$query = $this->utility->order_by($this->COLUMN_NETADDRESS, 'asc')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function fetchAvailableSubnets($username = null, $password = null, $host = null, $port = null, $schema = null) {
		log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select distinct SUBSTR(NETADDRESS, LENGTH(NETADDRESS) - 1, 2) as SUBNET from TBLNETADDRESS order by SUBNET asc";
		log_message('info', $sql);
		$compiled = oci_parse($conn, $sql);
		$result = oci_execute($compiled);
		while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) !== false) {
			$subnets[] = $row['SUBNET'];
		}
		return $subnets;
		/*
		$this->utility->db_select();
		$this->utility->select('SUBSTR(NETADDRESS, LENGTH(NETADDRESS) - 1, 2) as SUBNET')
			->distinct('SUBNET')
			->from($this->TABLENAME)
			->order_by('SUBNET', 'asc')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
		*/
	}
	public function fetchAllUsed($location) {
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_ID.', '.$this->COLUMN_NETADDRESS)
			->from($this->TABLENAME)
			->where($this->COLUMN_NETUSED, 'Y');
		if (!is_null($location)) {
			$this->utility->where($this->COLUMN_LOCATION, $location);
		}
		$query = $this->utility->order_by('NETADDRESS', 'asc')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function fetchAll($netaddress, $location, $isUsed, $username, $exact, $wildcardLocation, $status, $start, $max, $order) {
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_ID.', '.$this->COLUMN_NETADDRESS.', '.$this->COLUMN_LOCATION.', '.$this->COLUMN_NETUSED.', '.$this->COLUMN_DESCRIPTION.', '.
			$this->COLUMN_USERNAME.', '.$this->COLUMN_STATUS.', '.$this->COLUMN_MODIFIEDDATE)
			->from($this->TABLENAME);
		if (!is_null($netaddress)) {
			if ($exact) {
				$this->utility->where($this->COLUMN_NETADDRESS, $netaddress);
			} else {
				if ($wildcardLocation == 'after') {
					$this->utility->where("regexp_like(NETADDRESS, '^".$netaddress."', 'c')");
				} else if ($wildcardLocation == 'before') {
					$this->utility->where("regexp_like(NETADDRESS, '".$netaddress."$', 'c')");
				} else if ($wildcardLocation == 'both') {
					$this->utility->where("regexp_like(NETADDRESS, '".$netaddress."', 'c')");
				}
			}
		}
		if (!is_null($location)) {
			$this->utility->where($this->COLUMN_LOCATION, $location);
		}
		if (!is_null($isUsed)) {
			$this->utility->where($this->COLUMN_NETUSED, $isUsed);
		}
		if (!is_null($username)) {
			$this->utility->where($this->COLUMN_USERNAME, $username);
		}
		if (!is_null($status)) {
			$this->utility->where($this->COLUMN_STATUS, $status);
		}
		if (is_null($order)) {
			$this->utility->order_by('NETADDRESS', 'asc');
		} else {
			if ($order['column'] != 'NETADDRESS') {
				$this->utility->order_by($order['column'], $order['dir']);
			}
			$this->utility->order_by('NETADDRESS', $order['column'] == 'NETADDRESS' ? $order['dir'] : 'asc');
		}
		$query = $this->utility->limit($start == 0 ? $max + 1 : $max, $start == 0 ? $start : $start + 1)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countNetAddresses($netaddress, $location, $isUsed, $username, $exact, $wildcardLocation, $status) {
		$this->utility->db_select();
		$this->utility->from($this->TABLENAME);
		if (!is_null($netaddress)) {
			if ($exact) {
				$this->utility->where($this->COLUMN_NETADDRESS, $netaddress);
			} else {
				if ($wildcardLocation == 'after') {
					$this->utility->where("regexp_like(NETADDRESS, '^".$netaddress."', 'c')");
				} else if ($wildcardLocation == 'before') {
					$this->utility->where("regexp_like(NETADDRESS, '".$netaddress."$', 'c')");
				} else if ($wildcardLocation == 'both') {
					$this->utility->where("regexp_like(NETADDRESS, '".$netaddress."', 'c')");
				}
			}
		}
		if (!is_null($location)) {
			$this->utility->where($this->COLUMN_LOCATION, $location);
		}
		if (!is_null($isUsed)) {
			$this->utility->where($this->COLUMN_NETUSED, $isUsed);
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
	public function fetchAllLocations() {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_LOCATION)
			->distinct()
			->from($this->TABLENAME)
			->order_by($this->COLUMN_LOCATION, 'ASC')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function rowDataToNetArray($data) {
		$now = date('Y-m-d H:i:s', time());
		$netaddress = array(
			$this->COLUMN_NETADDRESS => trim($data[0]).trim($data[1]),
			$this->COLUMN_LOCATION => trim($data[2]),
			$this->COLUMN_NETUSED => 'N',
			$this->COLUMN_DESCRIPTION => is_null($data[3]) ? '' : $data[3],
			$this->COLUMN_USERNAME => '',
			$this->COLUMN_STATUS => null,
			$this->COLUMN_MODIFIEDDATE => $now);
		return $netaddress;
	}
	public function findNetAddress($netaddress, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
		if (!$conn) {
			return false;
		}
		$sql = "select * from ".$this->TABLENAME." where ".$this->COLUMN_NETADDRESS." = :netaddress";
		$compiled = oci_parse($conn, $sql);
		oci_bind_by_name($compiled, ':netaddress', $netaddress);
		$result = oci_execute($compiled);
		$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
		return $row;
	}
	//added november 2016
	public function getNextAvailableNetAddress($location, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		//params from $username to $schema can be used to change the function contents to using generic php and not codeigniter's active record
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_NETADDRESS)
			->from($this->TABLENAME)
			->where($this->COLUMN_NETUSED, 'N')
			->where($this->COLUMN_LOCATION, $location)
			->order_by($this->COLUMN_NETADDRESS, 'asc')
			->limit(2); //translates to rnum < 2 (gets rnum = 1)
		$query = $this->utility->get();
		return $query->row_array();
	}
	public function getNextAvailableNetAddressWithLocationAndSubnet($location, $subnet = null, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		//see comment in getNextAvailableNetAddress
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_NETADDRESS)
			->from($this->TABLENAME)
			->where($this->COLUMN_NETUSED, 'N')
			->where($this->COLUMN_LOCATION, $location);
		if (!is_null($subnet)) {
			$this->utility->where("regexp_like(".$this->COLUMN_NETADDRESS.", '/".$subnet."$', 'c')");
		}
		$query = $this->utility->order_by($this->COLUMN_NETADDRESS, 'asc')
			->limit(2)
			->get();
		return $query->row_array();
	}
}
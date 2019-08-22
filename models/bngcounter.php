<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bngcounter extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->utility = $this->load->database('utility', true);
	}

	public function countBNG($bng, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		try {
			$now = date('Y-m-d H:i:s', time());
			log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
			$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
			if (!$conn) {
				return false;
			}
			$sql = "select count(*) as CNT from TBLCUSTOMER where RBADDITIONALSERVICE3 = :bng";
			log_message('info', $sql);
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':bng', $bng);
			$result = oci_execute($compiled);
			$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
			return $row['CNT'];
		} catch (Exception $e) {
			log_message('debug', 'error @ bngcounter.countBNG:'.json_encode($e));
			return false;
		}
	}
	public function fetchBNGData($bng, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		try {
			$now = date('Y-m-d H:i:s', time());
			log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
			$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
			if (!$conn) {
				return false;
			}
			$sql = "select * from TBLBNGCOUNTER where BNG = :bng";
			log_message('info', $sql);
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':bng', $bng);
			$result = oci_execute($compiled);
			$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
			return $row;
		} catch (Exception $e) {
			log_message('info', 'error @ bngcounter.fetchBNGData:'.json_encode($e));
			return false;
		}
	}
	public function fetchAllBNGData($username = null, $password = null, $host = null, $port = null, $schema = null) {
		try {
			$now = date('Y-m-d H:i:s', time());
			log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
			$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
			if (!$conn) {
				return false;
			}
			$sql = "select * from TBLBNGCOUNTER order by BNG asc";
			log_message('info', $sql);
			$compiled = oci_parse($conn, $sql);
			$result = oci_execute($compiled);
			$rows = array();
			while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) !== false) {
				$rows[] = $row;
			}
			return $rows;
		} catch (Exception $e) {
			log_message('info', 'error @ bngcounter.fetchAllBNGData:'.json_encode($e));
			return false;
		}
	}
	public function incrementIPCount($bng, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		try {
			$data = $this->fetchBNGData($bng, $username, $password, $host, $port, $schema);
			if ($data === false) {
				return false;
			}
			log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
			$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
			if (!$conn) {
				return false;
			}
			$sql = "update TBLBNGCOUNTER set ASSIGNED_IP = :newcount where BNG = :bng";
			log_message('info', $sql);
			$compiled = oci_parse($conn, $sql);
			$newCount = intval($data['ASSIGNED_IP']) + 1;
			oci_bind_by_name($compiled, ':newcount', $newCount);
			oci_bind_by_name($compiled, ':bng', $bng);
			$result = oci_execute($compiled);
			return $result;
		} catch (Exception $e) {
			log_message('info', 'error @ bngcounter.incrementIPCount:'.json_encode($e));
			return false;
		}
	}
	public function updateMaxIP($bng, $maxIP, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		try {
			$now = date('Y-m-d H:i:s', time());
			log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
			$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
			if (!$conn) {
				return false;
			}
			$sql = "update TBLBNGCOUNTER set MAX_IP = :maxip where BNG = :bng";
			log_message('info', $sql);
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':maxip', intval($maxIP));
			oci_bind_by_name($compiled, ":bng", $bng);
			$result = oci_execute($compiled);
			return $result;
		} catch (Exception $e) {
			log_message('info', 'error @ bngcounter.updateMaxIP:'.json_encode($e));
			return false;
		}
	}
	public function addBNGItem($bng, $max, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		try {
			$now = date('Y-m-d H:i:s', time());
			log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
			$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
			if (!$conn) {
				return false;
			}
			$sql = "insert into TBLBNGCOUNTER (BNG, MAX_IP, ASSIGNED_IP) values (:bng, :max, 0)";
			log_message('info', $sql);
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':bng', strval($bng));
			oci_bind_by_name($compiled, ':max', intval($max));
			$result = oci_execute($compiled);
			return $result;
		} catch (Exception $e) {
			log_message('info', 'error @ bngcounter.addBNGItem:'.json_encode($e));
			return false;
		}
	}
	public function editBNGItem($bngRef = null, $bng = null, $max = null, $assigned = null, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		try {
			if (is_null($bngRef)) {
				return false;
			}
			if (is_null($bng) && is_null($max) && is_null($assigned)) {
				return false;
			}
			$now = date('Y-m-d H:i:s', time());
			log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
			$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
			if (!$conn) {
				return false;
			}
			$first = false;
			$sql = "update TBLBNGCOUNTER set ";
			if (!is_null($bng)) {
				$sql .= "BNG = :bng";
				$first = true;
			}
			if (!is_null($max)) {
				if ($first) {
					$sql .= ", ";
				} else {
					$first = true;
				}
				$sql .= "MAX_IP = :max";
			}
			if (!is_null($assigned)) {
				if ($first) {
					$sql .= ", ";
				} else {
					$first = true;
				}
				$sql .= "ASSIGNED_IP = :assigned";
			}
			$sql .= " where BNG = :bngref";
			log_message('info', $sql);
			$compiled = oci_parse($conn, $sql);
			if (!is_null($bng)) {
				oci_bind_by_name($compiled, ':bng', strval($bng));
			}
			if (!is_null($max)) {
				oci_bind_by_name($compiled, ':max', intval($max));
			}
			if (!is_null($assigned)) {
				oci_bind_by_name($compiled, ':assigned', intval($assigned));
			}
			oci_bind_by_name($compiled, ':bngref', $bngRef);
			$result = oci_execute($compiled);
			return $result;
		} catch (Exception $e) {
			log_message('info', 'error @ bngcounter.editBNGItem:'.json_encode($e));
			return false;
		}
	}
	public function deleteBNGItem($bng, $username = null, $password = null, $host = null, $port = null, $schema = null) {
		try {
			$now = date('Y-m-d H:i:s', time());
			log_message('info', 'connection: '.$host.':'.$port.'/'.$schema.', '.$username.'|'.$password);
			$conn = oci_connect($username, $password, $host.':'.$port.'/'.$schema);
			if (!$conn) {
				return false;
			}
			$sql = "delete from TBLBNGCOUNTER where BNG = :bng";
			log_message('info', $sql);
			$compiled = oci_parse($conn, $sql);
			oci_bind_by_name($compiled, ':bng', $bng);
			$result = oci_execute($compiled);
			return $result;
		} catch (Exception $e) {
			log_message('info', 'error @ bngcounter.deleteBNGItem');
			return false;
		}
	}
}
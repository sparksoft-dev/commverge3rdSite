<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sysuserip extends CI_Model {
	private $utility;
	private $COLUMN_ID = 'ID';
	private $COLUMN_SYSTEMIP = 'SYSTEMIP';
	private $COLUMN_REMARKS = 'REMARKS';
	private $COLUMN_IPOWNER = 'SYSOWNER';
	private $TABLENAME = 'TBLSYSIP';

	function __construct() {
		parent::__construct();
		$this->utility = $this->load->database('utility', true);
	}

	public function create($systemip, $remarks, $ipowner) {
		$this->utility->db_select();
		$data = array(
			$this->COLUMN_SYSTEMIP => $systemip,
			$this->COLUMN_REMARKS => $remarks,
			$this->COLUMN_IPOWNER => $ipowner);
		$this->utility->insert($this->TABLENAME, $data);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function modify($systemipRef, $remarks, $ipowner) {
		$this->utility->db_select();
		$data = array(
			$this->COLUMN_REMARKS => $remarks);
		if (!is_null($ipowner)) {
			$data[$this->COLUMN_IPOWNER] = $ipowner;
		}
		$this->utility->where($this->COLUMN_SYSTEMIP, $systemipRef)
			->update($this->TABLENAME, $data);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function modifyViaId($id, $ipaddress, $remarks) {
		$this->utility->db_select();
		$data = array(
			$this->COLUMN_SYSTEMIP => $ipaddress,
			$this->COLUMN_REMARKS => $remarks);
		$this->utility->where($this->COLUMN_ID, $id)
			->update($this->TABLENAME, $data);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function delete($systemip) {
		$this->utility->db_select();
		$this->utility->where($this->COLUMN_SYSTEMIP, $systemip)
			->delete($this->TABLENAME);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function ipExists($systemip) {
		$this->utility->db_select();
		$count = $this->utility->from($this->TABLENAME)
			->where($this->COLUMN_SYSTEMIP, $systemip)
			->count_all_results();
		return $count == 0 ? false : true;
	}
	public function ipIsInAnySubnet($systemip) {
		$ipParts = explode('.', $systemip);
		$firstTwo = $ipParts[0].'.'.$ipParts[1];
		$this->utility->db_select();
		$query = $this->utility->select('*')
			->from($this->TABLENAME)
			->where("regexp_like(SYSTEMIP, '^".$firstTwo."', 'c')")
			->where("regexp_like(SYSTEMIP, '(*)/(*)', 'c')")
			->get();
		if ($query->num_rows() == 0) {
			return false;
		} else {
			$result = $query->result_array();
			$cidr = array(
				'32' => 1,
				'31' => 2,
				'30' => 4,
				'29' => 8,
				'28' => 16,
				'27' => 32,
				'26' => 64,
				'25' => 128,
				'24' => 256);
			$found = false;
			for ($j = 0; $j < count($result); $j++) {
				$withSubnet = $result[$j];
				// log_message('info', json_encode($withSubnet));
				$parts = explode('/', $withSubnet['SYSTEMIP']);
				$IPParts = explode('.', $parts[0]);
				$IPCount = $cidr[$parts[1]];
				$start = 0;
				$end = 0;
				for ($i = 0; $i < (256 / $IPCount); $i++) {
					if (($i * $IPCount) <= intval($IPParts[3]) && (intval($IPParts[3]) < ($i + 1) * $IPCount)) {
						$start = $i * $IPCount;
						$end = (($i + 1) * $IPCount) - 1;
					}
				}
				$startIP = $IPParts[0].'.'.$IPParts[1].'.'.$IPParts[2].'.'.$start;
				$endIP = $IPParts[0].'.'.$IPParts[1].'.'.$IPParts[2].'.'.$end;
				for ($i = $start + 1; $i <= $end - 1; $i++) {
					$ipaddress = $IPParts[0].'.'.$IPParts[1].'.'.$IPParts[2].'.'.$i;
					log_message('info', $i.' | '.$ipaddress);
					if (strval($ipaddress) == strval($systemip)) {
						$found = true;
						break;
					}
				}
				if ($found) {
					break;
				}
			}
			return $found;
		}
	}
	public function ipValid($systemip) {
		return $this->input->valid_ip($systemip);
	}
	public function fetchAll($sysuserip, $order, $start, $max) {
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_ID.', '.$this->COLUMN_SYSTEMIP.', '.$this->COLUMN_REMARKS.', '.$this->COLUMN_IPOWNER)
			->from($this->TABLENAME);
		if (!is_null($sysuserip)) {
			$this->utility->where($this->COLUMN_SYSTEMIP, $sysuserip);
		}
		if (is_null($order)) {
			$this->utility->order_by($this->COLUMN_ID, 'asc');
		} else {
			$this->utility->order_by($order['column'], $order['dir']);
		}
		if (!is_null($max) && !is_null($start)) {
			$this->utility->limit($start == 0 ? $max + 1 : $max, $start == 0 ? $start : $start + 1);
		}
		$query = $this->utility->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSysuserIPAddresses() {
		$this->utility->db_select();
		$count = $this->utility->from($this->TABLENAME)
			->count_all_results();
		return $count;
	}
}
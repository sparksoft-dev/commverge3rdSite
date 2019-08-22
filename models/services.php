<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends CI_Model {
	private $utility;
	private $COLUMN_ID = 'SERVICEID';
	private $COLUMN_SERVICENAME = 'SERVICENAME';
	private $COLUMN_DESCRIPTION = 'DESCRIPTION';
	private $COLUMN_REMARKS = 'REMARKS';
	private $COLUMN_DOWNLINK = 'DOWNLINK';
	private $COLUMN_UPLINK = 'UPLINK';
	private $COLUMN_QUOTA = 'QUOTA';
	private $COLUMN_THROTTLING = 'THROTTLING';
	//private $TABLENAME = 'TBLSERVICES';
	private $TABLENAME = 'TBLRADIUSPOLICY';

	function __construct() {
		parent::__construct();
		$this->utility = $this->load->database('utility', true);
	}
	//no longer used
	public function create($servicename, $description, $remarks, $downlink, $uplink, $quota, $throttling) {
		$this->utility->db_select();
		if (is_null($servicename)) {
			return false;
		}
		$data = array(
			$this->COLUMN_SERVICENAME => $servicename,
			$this->COLUMN_DESCRIPTION => $description == '' ? null : $description,
			$this->COLUMN_REMARKS => $remarks == '' ? null : $remarks,
			$this->COLUMN_DOWNLINK => $downlink == '' ? null : $downlink,
			$this->COLUMN_UPLINK => $uplink == '' ? null : $uplink,
			$this->COLUMN_QUOTA => $quota == '' ? null : $quota,
			$this->COLUMN_THROTTLING => $throttling == '' ? null : $throttling);
		$this->utility->insert($this->TABLENAME, $data);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	//no longer used
	public function modify($servicename, $description, $remarks, $downlink, $uplink, $quota, $throttling) {
		$this->utility->db_select();
		$data = [];
		if (!is_null($description)) {
			$data[$this->COLUMN_DESCRIPTION] = $description;
		}
		if (!is_null($remarks)) {
			$data[$this->COLUMN_REMARKS] = $remarks;
		}
		if (!is_null($downlink)) {
			$data[$this->COLUMN_DOWNLINK] = $downlink;
		}
		if (!is_null($uplink)) {
			$data[$this->COLUMN_UPLINK] = $uplink;
		}
		if (!is_null($quota)) {
			$data[$this->COLUMN_QUOTA] = $quota;
		}
		if (!is_null($throttling)) {
			$data[$this->COLUMN_THROTTLING] = $throttling;
		}
		if (count($data) == 0) {
			return false;
		}
		$this->utility->where($this->COLUMN_SERVICENAME, $servicename)
			->update($this->TABLENAME, $data);
		return $this->utility->affected_rows() == 0 ? false : true;	
	}
	//no longer used
	public function delete($servicename) {
		$this->utility->db_select();
		$this->utility->where($this->COLUMN_SERVICENAME, $servicename)
			->delete($this->TABLENAME);
		return $this->utility->affected_rows() == 0 ? false : true;	
	}
	//will be replaced by serviceExistsNew
	public function serviceExists($servicename) {
		$this->utility->db_select();
		$count = $this->db->from($this->TABLENAME)
			->where('RADIUSPOLICYNAME', $servicename)
			->count_all_results();
		return $count == 0 ? false : true;
	}
	public function serviceExistsNew($servicename, $theHost, $thePort, $theSchema, $theUsername, $thePassword) {
		try {
			$theUrl = $theHost.':'.$thePort.'/'.$theSchema;
			$conn = oci_connect($theUsername, $thePassword, $theUrl);
			if ($conn === false) {
				log_message('debug', 'no connection to '.$theUrl);
				return false;
			}
			$sql = 'select count(*) as CNT from VWRADIUSPOLICY where PLANNAME = :plan';
			$compiled = oci_parse($conn, $sql);
			$servicename = str_replace('-', '~', $servicename);
			oci_bind_by_name($compiled, ':plan', $servicename);
			$result = oci_execute($compiled);
			if ($result === false) {
				return false;
			}
			$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
			if (intval($row['CNT']) > 0) {
				return true;
			} else {
				return false;
			}
		} catch (Exception $e) {
			log_message('debug', 'error @ serviceExistsNew:'.json_encode($e));
			return false;
		}
	}
	public function serviceExistsNew2($servicename, $theHost, $thePort, $theSchema, $theUsername, $thePassword) {
		try {
			$theUrl = $theHost.':'.$thePort.'/'.$theSchema;
			$conn = oci_connect($theUsername, $thePassword, $theUrl);
			if ($conn === false) {
				log_message('debug', 'no connection to '.$theUrl);
				return false;
			}
			$sql = "select count(*) as CNT from TBLSERVICES where SERVICENAME = :plan";
			$compiled = oci_parse($conn, $sql);
			$servicename = str_replace('~', '-', $servicename);
			oci_bind_by_name($compiled, ':plan', $servicename);
			$result = oci_execute($compiled);
			if ($result === false) {
				return false;
			}
			$row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS);
			if (intval($row['CNT']) > 0) {
				return true;
			} else {
				return false;
			}
		} catch (Exception $e) {
			log_message('debug', 'error @ serviceExistsNew2:'.json_encode($e));
			return false;
		}
	}
	//no longer used
	public function fetch($servicename) {
		$this->utility->db_select();
		$query = $this->utility->select('*')
			->from($this->TABLENAME)
			->where('RADIUSPOLICYNAME', $servicename)
			->get();
		return $query->num_rows() == 0 ? false : $query->row_array();
	}
	//no longer used
	public function fetchAll() {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_ID.', '.$this->COLUMN_SERVICENAME.', '.$this->COLUMN_DESCRIPTION.', '.$this->COLUMN_REMARKS.', '.
			$this->COLUMN_DOWNLINK.', '.$this->COLUMN_UPLINK.', '.$this->COLUMN_QUOTA.', '.$this->COLUMN_THROTTLING)
			->from($this->TABLENAME)
			->order_by($this->COLUMN_ID, 'ASC')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	//will be replaced by fetchAll2New
	public function fetchAll2() {
		$this->utility->db_select();
		$sql = "select DISTINCT * from TBLRADIUSPOLICY order by RADIUSPOLICYNAME ASC";
		$query = $this->utility->query($sql);
		/*
		$query = $this->utility->select('DISTINCT *')
			->from('TBLRADIUSPOLICY')
			->order_by('RADIUSPOLICYNAME', 'ASC')
			->get();
		*/
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function fetchAll2New($theHost, $thePort, $theSchema, $theUsername, $thePassword) {
		try {
			/*
			$theUrl = $theHost.':'.$thePort.'/'.$theSchema;
			$conn = oci_connect($theUsername, $thePassword, $theUrl);
			if ($conn === false) {
				log_message('debug', 'no connection to '.$theUrl);
				return false;
			}
			$sql = "select REPLACE(VWR.PLANNAME, '~', '-') as RADIUSPOLICYNAME, VWR.DESCRIPTION from VWRADIUSPOLICY VWR order by VWR.PLANNAME asc";
			$compiled = oci_parse($conn, $sql);
			$result = oci_execute($compiled);
			if ($result === false) {
				return false;
			}
		
			$rows = array();
			while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) !== false) {
				$rows[] = $row;
			}
			return $rows;
			*/
			$this->utility->db_select();
			$sql = "select REPLACE(TBLSERVICES.SERVICENAME, '~', '-') as RADIUSPOLICYNAME, TBLSERVICES.DESCRIPTION as DESCRIPTION ".
				"from TBLSERVICES order by TBLSERVICES.SERVICENAME asc";
			$query = $this->utility->query($sql);
			return $query->result_array();
		} catch (Exception $e) {
			log_message('debug', 'error @ fetchAll2New:'.json_encode($e));
			return false;
		}
	}
	//no longer used
	public function fetchAllNamesOnly() {
		$this->utility->db_select();
		$query = $this->utility->select('RADIUSPOLICYNAME') 
			->from($this->TABLENAME)
			->get();
		if ($query->num_rows() == 0) {
			return false;
		} else {
			$results = $query->result_array();
			$services = [];
			for($i = 0; $i < count($results); $i++) {
				$services[] = $results[$i]['RADIUSPOLICYNAME'];
			}
			return $services;
		}
	}
	//will be replaced by fetchAllNamesOnlyNew
	public function fetchAllNamesOnly2() {
		$this->utility->db_select();
		$query = $this->utility->select('*')
			->from('TBLRADIUSPOLICY')
			->order_by('RADIUSPOLICYNAME', 'ASC')
			->get();
		if($query->num_rows() == 0) {
			return false;
		} else {
			$results = $query->result_array();
			$services = [];
			for($i = 0; $i < count($results); $i++) {
				$services[] = $results[$i]['RADIUSPOLICYNAME'];
			}
			return $services;	
		}
	}
	public function fetchAllNamesOnlyNew($theHost, $thePort, $theSchema, $theUsername, $thePassword) {
		try {
			/*
			$theUrl = $theHost.':'.$thePort.'/'.$theSchema;
			$conn = oci_connect($theUsername, $thePassword, $theUrl);
			if ($conn === false) {
				log_message('debug', 'no connection to '.$theUrl);
				return false;
			}
			$sql = 'select PLANNAME from VWRADIUSPOLICY order by PLANNAME asc';
			$compiled = oci_parse($conn, $sql);
			$result = oci_execute($compiled);
			if ($result === false) {
				return false;
			}
			$services = array();
			while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) !== false) {
				$services[] = str_replace('~', '-', $row['PLANNAME']);
			}
			return $services;
			*/
			$this->utility->db_select();
			$sql = "select TBLSERVICES.SERVICENAME as PLANNAME from TBLSERVICES order by TBLSERVICES.SERVICENAME asc";
			$query = $this->utility->query($sql);
			$results = $query->result_array();
			$services = array();
			for ($i = 0; $i < count($results); $i++) {
				$services[] = str_replace('~', '-', $results[$i]['PLANNAME']);
			}
			return $services;
		} catch (Exception $e) {
			log_message('debug', 'error @ fetchAllNamesOnlyNew:'.json_encode($e));
			return false;
		}
	}
	//to be replaced by searchServiceNew
	public function searchService($keyword, $exact, $wildcardLocation) {
		$this->utility->db_select();
		$this->utility->select('*')
			->from($this->TABLENAME);
		if ($exact) {
			$this->utility->where('RADIUSPOLICYNAME', $keyword);
		} else {
			$this->utility->like('RADIUSPOLICYNAME', $keyword, $wildcardLocation);
		}
		$query = $this->utility->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function searchServiceNew($keyword, $exact, $wildcardLocation, $theHost, $thePort, $theSchema, $theUsername, $thePassword) {
		try {
			/*
			$theUrl = $theHost.':'.$thePort.'/'.$theSchema;
			$conn = oci_connect($theUsername, $thePassword, $theUrl);
			if ($conn === false) {
				log_message('debug', 'no connection to '.$theUrl);
				return false;
			}
			$sql = "select REPLACE(VWR.PLANNAME, '~', '-') as RADIUSPOLICYNAME, VWR.DESCRIPTION, '' as REMARKS from VWRADIUSPOLICY VWR ";
			if ($exact) {
				$sql .= "where REPLACE(VWR.PLANNAME, '~', '-') = '".$keyword."' ";
			} else {
				if ($wildcardLocation == 'before') {
					$sql .= "where regexp_like(REPLACE(VWR.PLANNAME, '~', '-'), '".$keyword."$', 'c') ";
				} else if ($wildcardLocation == 'after') {
					$sql .= "where regexp_like(REPLACE(VWR.PLANNAME, '~', '-'), '^".$keyword."', 'c') ";
				} else if ($wildcardLocation == 'both') {
					$sql .= "where regexp_like(REPLACE(VWR.PLANNAME, '~', '-'), '".$keyword."', 'c') ";
				}
			}
			$sql .= "order by VWR.PLANNAME asc";
			log_message('info', '-----------------------------'.$sql);
			$compiled = oci_parse($conn, $sql);
			$result = oci_execute($compiled);
			if ($result === false) {
				return false;
			}
			$services = array();
			while (($row = oci_fetch_array($compiled, OCI_ASSOC + OCI_RETURN_NULLS)) !== false) {
				$services[] = $row;
			}
			return $services;
			*/
			$this->utility->db_select();
			$sql = "select REPLACE(TBLSERVICES.SERVICENAME, '~', '-') as RADIUSPOLICYNAME, TBLSERVICES.DESCRIPTION, '' as REMARKS from TBLSERVICES ";
			if ($exact) {
				$sql .= "where REPLACE(TBLSERVICES.SERVICENAME, '~', '-') = '".$keyword."'";
			} else {
				if ($wildcardLocation == 'before') {
					$sql .= "where regexp_like(REPLACE(TBLSERVICES.SERVICENAME, '~', '-'), '".$keyword."$', 'c') ";
				} else if ($wildcardLocation == 'after') {
					$sql .= "where regexp_like(REPLACE(TBLSERVICES.SERVICENAME, '~', '-'), '^".$keyword."', 'c') ";
				} else if ($wildcardLocation == 'both') {
					$sql .= "where regexp_like(REPLACE(TBLSERVICES.SERVICENAME, '~', '-'), '".$keyword."', 'c') ";
				}
			}
			$sql .= "order by TBLSERVICES.SERVICENAME asc";
			$query = $this->utility->query($sql);
			return $query->result_array();
		} catch (Exception $e) {
			log_message('debug', 'error @ searchServiceNew:'.json_encode($e));
		}
	}
	//these last four functions unused, counts temporarily set to zero
	public function searchServiceActiveCounts($keyword, $exact, $wildcardLocation) {
		$this->utility->db_select();
		$sql = "select TBLSERVICES.SERVICENAME as service, count(TBLCUSTOMER.USERNAME) as count from TBLSERVICES ".
			"left join TBLCUSTOMER on REPLACE(TBLCUSTOMER.RADIUSPOLICY, '~', '-') = REPLACE(TBLSERVICES.SERVICENAME, '~', '-') and TBLCUSTOMER.CUSTOMERSTATUS = 'Active' ".
			"where regexp_like(REPLACE(TBLSERVICES.SERVICENAME, '~', '-'), '".($exact ? "^".$keyword."$" : "^".$keyword)."', 'c') ".
			"group by TBLSERVICES.SERVICENAME ".
			"order by TBLSERVICES.SERVICENAME asc";
		$query = $this->utility->query($sql);
		return $query->result_array();	
	}
	public function searchServiceInactiveCounts($keyword, $exact, $wildcardLocation) {
		$this->utility->db_select();
		$sql = "select TBLSERVICES.SERVICENAME as service, count(TBLCUSTOMER.USERNAME) as count from TBLSERVICES ".
			"left join TBLCUSTOMER on REPLACE(TBLCUSTOMER.RADIUSPOLICY, '~', '-') = REPLACE(TBLSERVICES.SERVICENAME, '~', '-') and TBLCUSTOMER.CUSTOMERSTATUS = 'InActive' ".
			"where regexp_like(REPLACE(TBLSERVICES.SERVICENAME, '~', '-'), '".($exact ? "^".$keyword."$" : "^".$keyword)."', 'c') ".
			"group by TBLSERVICES.SERVICENAME ".
			"order by TBLSERVICES.SERVICENAME asc";
		$query = $this->utility->query($sql);
		return $query->result_array();
	}
	public function fetchActiveUserCountsPerService($realm) {
		$this->utility->db_select();
		$sql = "select TBLSERVICES.SERVICENAME as service, count(TBLCUSTOMER.USER_IDENTITY) as count from TBLSERVICES ".
			"left join TBLCUSTOMER on REPLACE(TBLCUSTOMER.RADIUSPOLICY, '~', '-') = REPLACE(TBLSERVICES.SERVICENAME, '~', '-') and TBLCUSTOMER.CUSTOMERSTATUS = 'Active' ".(is_null($realm) ? "" : " and TBLCUSTOMER.RBREALM = '".$realm."' ").
			"group by TBLSERVICES.SERVICENAME order by TBLSERVICES.SERVICENAME ASC";
		$query = $this->utility->query($sql);
		return $query->result_array();
	}
	public function fetchInactiveUserCountsPerService($realm) {
		$this->utility->db_select();
		$sql = "select TBLSERVICES.SERVICENAME as service, count(TBLCUSTOMER.USER_IDENTITY) as count from TBLSERVICES ".
			"left join TBLCUSTOMER on REPLACE(TBLCUSTOMER.RADIUSPOLICY, '~', '-') = REPLACE(TBLSERVICES.SERVICENAME, '~', '-') and TBLCUSTOMER.CUSTOMERSTATUS = 'InActive' ".(is_null($realm) ? "" : " and TBLCUSTOMER.RBREALM = '".$realm."' ").
			"group by TBLSERVICES.SERVICENAME order by TBLSERVICES.SERVICENAME ASC";
		$query = $this->utility->query($sql);
		return $query->result_array();	
	}
}
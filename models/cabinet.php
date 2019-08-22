<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cabinet extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->extras = $this->load->database('extras', true);
	}

	/**************************************************
	 * cabinet functions
	 **************************************************/
	public function addCabinet($name, $bng, $vlan) {
		$this->extras->db_select();
		$data = array(
			'name' => $name,
			'homing_bng' => $bng,
			'data_vlan' => $vlan);
		$this->extras->insert('cabinet_info', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function editCabinet($id, $name, $bng, $vlan) {
		$this->extras->db_select();
		$data = array(
			'name' => $name,
			'homing_bng' => $bng,
			'data_vlan' => $vlan);
		$this->extras->where('id', $id);
		$this->extras->update('cabinet_info', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function editCabinetWithName($name, $newName, $bng, $vlan) {
		$this->extras->db_select();
		$data = array(
			'name' => $newName,
			'homing_bng' => $bng,
			'data_vlan' => $vlan);
		$this->extras->where('name', $name);
		$this->extras->update('cabinet_info', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function removeCabinet($id) {
		$this->extras->db_select();
		$data = array(
			'id' => $id);
		$this->extras->delete('cabinet_info', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function removeCabinetWithName($name) {
		$this->extras->db_select();
		$data = array(
			'name' => $name);
		$this->extras->delete('cabinet_info', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function getCabinets($cabinetName = null, $homingBng = null, $start = 0, $max = 20, $order = array('column' => 'name', 'dir' => 'asc')) {
		$this->extras->db_select();
		$this->extras->select('ci.id, ci.name, l.location as homing_bng, ci.data_vlan')
			->from('cabinet_info ci')
			->join('locations l', 'l.id = ci.homing_bng');
		if (!is_null($cabinetName)) {
			$this->extras->where('lower(ci.name)', strtolower($cabinetName));
		}
		if (!is_null($homingBng)) {
			$this->extras->where('lower(l.location)', strtolower($homingBng));
		}
		$query = $this->extras->order_by($order['column'], $order['dir'])
			->limit($max, $start)
			->get();
		return $query->result_array();
	}
	public function countCabinets($cabinetName = null, $homingBng = null) {
		$this->extras->db_select();
		$this->extras->select('count(ci.name) as cnt')
			->from('cabinet_info ci')
			->join('locations l', 'l.id = ci.homing_bng');
		if (!is_null($cabinetName)) {
			$this->extras->where('lower(ci.name)', strtolower($cabinetName));
		}
		if (!is_null($homingBng)) {
			$this->extras->where('lower(l.location)', strtolower($homingBng));
		}
		$query = $this->extras->get();
		$result = $query->row_array();
		return $result['cnt'];
	}
	public function getCabinetWithId($id) {
		$this->extras->db_select();
		$query = $this->extras->select('ci.id, ci.name, ci.homing_bng as bng_id, l.location as homing_bng, ci.data_vlan')
			->from('cabinet_info ci')
			->join('locations l', 'l.id = ci.homing_bng')
			->where('ci.id', $id)
			->get();
		return $query->row_array();
	}
	public function getCabinetWithName($name) {
		$this->extras->db_select();
		$query = $this->extras->select('ci.id, ci.name, ci.homing_bng as bng_id, l.location as homing_bng, ci.data_vlan')
			->from('cabinet_info ci')
			->join('locations l', 'l.id = ci.homing_bng')
			->where('lower(ci.name)', strtolower($name))
			->get();
		return $query->row_array();
	}
	public function cabinetExists($name) {
		$this->extras->db_select();
		$query = $this->extras->select('count(*) as cnt')
			->from('cabinet_info')
			->where('lower(name)', strtolower($name))
			->get();
		$result = $query->row_array();
		if (empty($result)) {
			return false;
		} else {
			return $result['cnt'] != 0;
		}
	}
	public function getCabinetWithLocation($location) {
		$this->extras->db_select();
		$query = $this->extras->select('ci.id, ci.name, ci.homing_bng as bng_id, l.location as homing_bng, ci.data_vlan')
			->from('cabinet_info ci')
			->join('locations l', 'l.id = ci.homing_bng')
			->where('lower(l.location)', strtolower($location))
			->get();
		return $query->row_array();
	}
	public function rowDataToCabinetArray($data) {
		$cabinet = array(
			'name' => $data[0],
			'newName' => $data[1],
			'homing_bng' => $data[2],
			'data_vlan' => $data[3]);
		return $cabinet;
	}

	/**************************************************
	 * secondary cabinet_info database functions
	 **************************************************/
	public function addCabinetSecondary($conn, $name, $bng, $vlan) {
		$sql = "insert into `cabinet_info` (`name`, `homing_bng`, `data_vlan`) values ".
			"('".$name."', ".$bng.", ".$vlan.")";
		$result = $conn->query($sql);
		return $result !== false;
	}
	public function editCabinetSecondary($conn, $id, $name, $bng, $vlan) {
		$sql = "update `cabinet_info` set `name` = '".$name."', `homing_bng` = ".$bng.", `data_vlan` = ".$vlan." ".
			"where `id` = ".$id;
		$result = $conn->query($sql);
		return $result !== false;
	}
	public function editCabinetWithNameSecondary($conn, $name, $newName, $bng, $vlan) {
		$sql = "update `cabinet_info` set `name` = '".$newName."', `homing_bng` = ".$bng.", `data_vlan` = ".$vlan." ".
			"where `name` = '".$name."'";
		$result = $conn->query($sql);
		return $result !== false;
	}
	public function removeCabinetSecondary($conn, $id) {
		$sql = "delete from `cabinet_info` where `id` = ".$id;
		$result = $conn->query($sql);
		return $result !== false;
	}
	public function removeCabinetWithNameSecondary($conn, $name) {
		$sql = "delete from `cabinet_info` where `name` = '".$name."'";
		$result = $conn->query($sql);
		return $result !== false;
	}

	/**************************************************
	 * location functions
	 **************************************************/
	public function addLocation($locationName, $nasName, $nasIp, $nasDescription, $rmLocation, $rmDescription, $nasCode) {
		$this->extras->db_select();
		$data = array(
			'nas_name' => $nasName,
			'nas_ip' => $nasIp,
			'nas_description' => $nasDescription,
			'rm_location' => $rmLocation,
			'rm_description' => $rmDescription,
			'nas_code' => $nasCode);
		$this->extras->insert('nas_location', $data);
		if ($this->extras->affected_rows() != 0) {
			$insertId = $this->extras->insert_id();
			$data = array(
				'location' => $locationName,
				'nas_id' => $insertId);
			$this->extras->insert('locations', $data);
			return (($this->extras->affected_rows() == 0) ? false : true);
		} else {
			return false;
		}
	}
	public function editLocation($id, $locationName, $nasName, $nasIp, $nasDescription, $rmLocation, $rmDescription, $nasCode) {
		$this->extras->db_select();
		$data = array(
			'nas_name' => $nasName,
			'nas_ip' => $nasIp,
			'nas_description' =>  $nasDescription,
			'rm_location' => $rmLocation,
			'rm_description' => $rmDescription,
			'nas_code' => $nasCode);
		$this->extras->where('id', $id);
		$this->extras->update('nas_location', $data);
		// $updated = $this->extras->affected_rows() != 0;
		$data = array(
			'location' => $locationName);
		$this->extras->where('nas_id', $id);
		$this->extras->update('locations', $data);
		// return ($this->extras->affected_rows() != 0 || $updated);
		return true;
	}
	public function removeLocation($id) {
		$this->extras->db_select();
		$data = array(
			'id' => $id);
		$this->extras->delete('nas_location', $data);
		$data = array(
			'nas_id' => $id);
		$this->extras->delete('locations', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function removeLocationWithName($locationName) {
		$this->extras->db_select();
		$location = $this->getLocationWithLocationName($locationName);
		$data = array(
			'id' => $location['id']);
		$this->extras->delete('nas_location', $data);
		$data = array(
			'nas_id' => $location['id']);
		$this->extras->delete('locations', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function getLocationsV2($locationName = null, $nasName = null, $nasIp = null, $rmLocation = null, $nasCode = null, 
			$start = 0, $max = 20, $order = array('column' => 'location', 'dir' => 'asc')) {
		$this->extras->db_select();
		$this->extras->select('nl.id, l.location, nl.nas_name, nl.nas_ip, nl.nas_description, nl.rm_location, nl.rm_description, nl.nas_code')
			->from('nas_location nl')
			->join('locations l', 'l.nas_id = nl.id');
		if (!is_null($locationName)) {
			$this->extras->where('lower(l.location)', strtolower($locationName));
		}
		if (!is_null($nasName)) {
			$this->extras->where('lower(nl.nas_name)', strtolower($nasName));
		}
		if (!is_null($nasIp)) {
			$this->extras->where('lower(nl.nas_ip)', strtolower($nasIp));
		}
		if (!is_null($rmLocation)) {
			$this->extras->where('lower(nl.rm_location)', strtolower($rmLocation));
		}
		if (!is_null($nasCode)) {
			$this->extras->where('lower(nl.nas_code)', strtolower($nasCode));
		}
		$this->extras->order_by($order['column'], $order['dir']);
		if ($order['column'] != 'location') {
			$this->extras->order_by('l.location', 'asc');
		}
		$query = $this->extras->limit($max, $start)
			->get();
		return $query->result_array();
	}
	public function countLocations($locationName = null, $nasName = null, $nasIp = null, $rmLocation = null, $nasCode = null) {
		$this->extras->db_select();
		$this->extras->select('count(l.location) as cnt')
			->from('nas_location nl')
			->join('locations l', 'l.nas_id = nl.id');
		if (!is_null($locationName)) {
			$this->extras->where('lower(l.location)', strtolower($locationName));
		}
		if (!is_null($nasName)) {
			$this->extras->where('lower(nl.nas_name)', strtolower($nasName));
		}
		if (!is_null($nasIp)) {
			$this->extras->where('lower(nl.nas_ip)', strtolower($nasIp));
		}
		if (!is_null($rmLocation)) {
			$this->extras->where('lower(nl.rm_location)', strtolower($rmLocation));
		}
		if (!is_null($nasCode)) {
			$this->extras->where('lower(nl.nas_code)', strtolower($nasCode));
		}
		$query = $this->extras->get();
		$result = $query->row_array();
		return $result['cnt'];
	}
	public function getLocationWithId($id) {
		$this->extras->db_select();
		$query = $this->extras->select('nl.id, l.location, nl.nas_name, nl.nas_ip, nl.nas_description, nl.rm_location, nl.rm_description, nl.nas_code')
			->from('nas_location nl')
			->join('locations l', 'l.nas_id = nl.id')
			->where('nl.id', $id)
			->get();
		return $query->row_array();
	}
	public function getLocationWithNasName($nasName) {
		$this->extras->db_select();
		$query = $this->extras->select('nl.id, l.location, nl.nas_name, nl.nas_ip, nl.nas_description, nl.rm_location, nl.rm_description, nl.nas_code')
			->from('nas_location nl')
			->join('locations l', 'l.nas_id = nl.id')
			->where('lower(nl.nas_name)', strtolower($nasName))
			->get();
		return $query->row_array();
	}
	public function getLocationWithNasIp($nasIp) {
		$this->extras->db_select();
		$query = $this->extras->select('nl.id, l.location, nl.nas_name, nl.nas_ip, nl.nas_description, nl.rm_location, nl.rm_description, nl.nas_code')
			->from('nas_location nl')
			->join('locations l', 'l.nas_id = nl.id')
			->where('lower(nl.nas_ip)', strtolower($nasIp))
			->get();
		return $query->row_array();
	}
	public function getLocationWithLocationName($locationName) {
		$this->extras->db_select();
		$query = $this->extras->select('nl.id, l.location, nl.nas_name, nl.nas_ip, nl.nas_description, nl.rm_location, nl.rm_description, nl.nas_code')
			->from('nas_location nl')
			->join('locations l', 'l.nas_id = nl.id')
			->where('lower(l.location)', strtolower($locationName))
			->get();
		return $query->row_array();
	}
	public function getLocations() {
		$this->extras->db_select();
		$query = $this->extras->select('id, location')
			->from('locations')
			->order_by('id', 'asc')
			->get();
		return $query->result_array();
	}

	/**************************************************
	 * secondary location functions
	 **************************************************/
	public function getLocationIdSecondary($conn, $locationName) {
		$sql = "select `nas_id` from `locations` where lower(`location`) = '".strtolower($locationName)."'";
		$result = $conn->query($sql);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		if (empty($row) || $row === false) {
			return false;
		} else {
			return $row['nas_id'];
		}
	}
	public function addLocationSecondary($conn, $locationName, $nasName, $nasIp, $nasDescription, $rmLocation, $rmDescription, $nasCode) {
		$sql = "insert into `nas_location` (`nas_name`, `nas_ip`, `nas_description`, `rm_location`, `rm_description`, `nas_code`) values ".
			"('".$nasName."', '".$nasIp."', '".$nasDescription."', '".$rmLocation."', '".$rmDescription."', '".$nasCode."')";
		$result = $conn->query($sql);
		if ($result === false) {
			return false;
		}
		$sql = "select `id` from `nas_location` where `nas_name` = '".$nasName."' and `nas_ip` = '".$nasIp."' order by `id` desc limit 1";
		log_message('debug', $sql);
		$result = $conn->query($sql);
		
		log_message('debug', '                                  ');
		log_message('debug', '                                  ');
		log_message('debug', '                                  ');
		log_message('debug', '1'.json_encode($result));

		if ($result === false) {
			return false;
		}
		$row = $result->fetch_array(MYSQLI_ASSOC);

		log_message('debug', '                                  ');
		log_message('debug', '                                  ');
		log_message('debug', '                                  ');
		log_message('debug', '2'.json_encode($row));

		if (empty($row)) {
			return false;
		}
		$id = $row['id'];
		$sql = "insert into `locations` (`location`, `nas_id`) values ".
			"('".$locationName."', ".$id.")";
		$result = $conn->query($sql);
		return $result !== false;
	}
	public function editLocationSecondary($conn, $id, $locationName, $nasName, $nasIp, $nasDescription, $rmLocation, $rmDescription, $nasCode) {
		$sql = "update `nas_location` set ".
			"`nas_name` = '".$nasName."', ".
			"`nas_ip` = '".$nasIp."', ".
			"`nas_description` = '".$nasDescription."', ".
			"`rm_location` = '".$rmLocation."', ".
			"`rm_description` = '".$rmDescription."', ".
			"`nas_code` = '".$nasCode."' ".
			"where `id` = ".$id;
		$result = $conn->query($sql);
		if ($result === false) {
			return false;
		}
		$sql = "update `locations` set `location` = '".$locationName."' where `nas_id` = ".$id;
		$result = $conn->query($sql);
		return $result !== false;
	}
	public function removeLocationSecondary($conn, $id) {
		$sql = "delete from `nas_location` where `id` = ".$id;
		$result = $conn->query($sql);
		if ($result === false) {
			return false;
		}
		$sql = "delete from `locations` where `nas_id` = ".$id;
		$result = $conn->query($sql);
		return $result !== false;
	}
}
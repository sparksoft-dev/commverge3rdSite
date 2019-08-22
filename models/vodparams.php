<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vodparams extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->extras = $this->load->database('extras', true);
	}

	public function addVodparam($oldVod, $newName) {
		$now = date('Y-m-d H:i:s', time());
		$this->extras->db_select();
		$data = array(
			'old_vod' => $oldVod,
			'new_name' => $newName,
			'date_added' => $now);
		$this->extras->insert('vod_params', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function editVodparam($id, $oldVod, $newName) {
		$this->extras->db_select();
		$data = array(
			'old_vod' => $oldVod,
			'new_name' => $newName);
		$this->extras->where('id', $id);
		$this->extras->update('vod_params', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function removeVodparam($id) {
		$this->extras->db_select();
		$data = array(
			'id' => $id);
		$this->extras->delete('vod_params', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function removeVodparamWithName($name) {
		$this->extras->db_select();
		$data = array(
			'old_vod' => $name);
		$this->extras->delete('vod_params', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function getVodparams($oldVod = null, $newName = null, $start = 0, $max = 20, $order = array('column' => 'old_vod', 'dir' => 'asc')) {
		$this->extras->db_select();
		$this->extras->select('vp.id, vp.old_vod, vp.new_name, vp.date_added')
			->from('vod_params vp');
		if (!is_null($oldVod)) {
			$this->extras->where('lower(vp.old_vod)', strtolower($oldVod));
		}
		if (!is_null($newName)) {
			$this->extras->where('lower(vp.new_name)', strtolower($newName));
		}
		$this->extras->order_by($order['column'], $order['dir']);
		if ($order['column'] == 'new_name') {
			$this->extras->order_by('old_vod', 'asc');
		}
		$query = $this->extras->limit($max, $start)
			->get();
		return $query->result_array();
	}
	public function countVodparams($oldVod = null, $newName = null) {
		$this->extras->db_select();
		$this->extras->select('count(vp.old_vod) as cnt')
			->from('vod_params vp');
		if (!is_null($oldVod)) {
			$this->extras->where('lower(vp.old_vod)', strtolower($oldVod));
		}
		if (!is_null($newName)) {
			$this->extras->where('lower(vp.new_name)', strtolower($newName));
		}
		$query = $this->extras->get();
		$result = $query->row_array();
		return $result['cnt'];
	}
	public function getVodparamWithId($id) {
		$this->extras->db_select();
		$query = $this->extras->select('vp.id, vp.old_vod, vp.new_name, vp.date_added')
			->from('vod_params vp')
			->where('vp.id', $id)
			->get();
		return $query->row_array();
	}
	public function getVodparamWithOldVod($oldVod) {
		$this->extras->db_select();
		$query = $this->extras->select('vp.id, vp.old_vod, vp.new_name, vp.date_added')
			->from('vod_params vp')
			->where('lower(vp.old_vod)', strtolower($oldVod))
			->get();
		return $query->row_array();
	}
	public function vodparamExists($oldVod) {
		$this->extras->db_select();
		$query = $this->extras->select('count(*) as cnt')
			->from('vod_params')
			->where('lower(old_vod)', strtolower($oldVod))
			->get();
		$result = $query->row_array();
		if (empty($result)) {
			return false;
		} else {
			return $result['cnt'] != 0;
		}
	}
	public function rowDataToVodparamArray($data) {
		$vodparam = array(
			'old_vod' => $data[0],
			'new_name' => $data[1]);
		return $vodparam;
	}

	/**************************************************
	 * secondary vod_params database functions
	 **************************************************/
	public function getVodparamIdSecondary($conn, $oldVod) {
		$sql = "select `id` from `vod_params` where lower(`old_vod`) = '".strtolower($oldVod)."'";
		$result = $conn->query($sql);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		if (empty($row) || $row === false) {
			return false;
		} else {
			return $row['id'];
		}
	}
	public function addVodparamSecondary($conn, $oldVod, $newName) {
		$now = date('Y-m-d H:i:s', time());
		$sql = "insert into `vod_params` (`old_vod`, `new_name`, `date_added`) values ".
			"('".$oldVod."', '".$newName."', '".$now."')";
		$result = $conn->query($sql);
		return $result !== false;
	}
	public function editVodparamSecondary($conn, $id, $oldVod, $newName) {
		$sql = "update `vod_params` set `old_vod` = '".$oldVod."', `new_name` = '".$newName."' ".
			"where `id` = ".$id;
		$result = $conn->query($sql);
		return $result !== false;
	}
	public function removeVodparamSecondary($conn, $id) {
		$sql= "delete from `vod_params` where `id` = ".$id;
		$result = $conn->query($sql);
		return $result !== false;
	}
}
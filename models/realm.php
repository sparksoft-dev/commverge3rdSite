<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Realm extends CI_Model {
	private $utility;
	private $TABLENAME = 'TBLREALM';
	private $COLUMN_ID = 'REALMID';
	private $COLUMN_REALMNAME = 'REALMNAME';
	private $COLUMN_REMARKS = 'REMARKS'; // --> 'default ip location'
	public $DEFAULTLOCATIONS = ['VALERO', 'SAN JUAN', 'TARLAC', 'BATANGAS', 'CEBU', 'DAVAO'];

	function __construct() {
		parent::__construct();
		$this->utility = $this->load->database('utility', true);
	}

	public function create($name, $remarks) {
		$this->utility->db_select();
		$data = array(
			$this->COLUMN_REALMNAME => $name,
			$this->COLUMN_REMARKS => $remarks);
		$this->utility->insert($this->TABLENAME, $data);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function modify($id, $realm, $remarks) {
		$this->utility->db_select();
		$data = array(
			$this->COLUMN_REALMNAME => $realm,
			$this->COLUMN_REMARKS => $remarks);
		$this->utility->where($this->COLUMN_ID, $id)
			->update($this->TABLENAME, $data);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function delete($realm) {
		$this->utility->db_select();
		$this->utility->where($this->COLUMN_REALMNAME, $realm)
			->delete($this->TABLENAME);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function countRealms() {
		$this->utility->db_select();
		$count = $this->utility->from($this->TABLENAME)
			->count_all_results();
		return $count;
	}
	public function realmExists($name) {
		$this->utility->db_select();
		$count = $this->utility->from($this->TABLENAME)
			->where($this->COLUMN_REALMNAME, $name)
			->count_all_results();
		return $count == 0 ? false : true;
	}
	public function fetchById($realmId) {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_ID.', '.$this->COLUMN_REALMNAME.', '.$this->COLUMN_REMARKS)
			->from($this->TABLENAME)
			->where($this->COLUMN_ID, $realmId)
			->get();
		return $query->num_rows() == 0 ? false : $query->row_array();
	}
	public function fetchByName($realmName) {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_ID.', '.$this->COLUMN_REALMNAME.', '.$this->COLUMN_REMARKS)
			->from($this->TABLENAME)
			->where($this->COLUMN_REALMNAME, $realmName)
			->get();
		return $query->num_rows() == 0 ? false : $query->row_array();	
	}
	public function fetchAll($order, $start, $max) {
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_ID.', '.$this->COLUMN_REALMNAME.', '.$this->COLUMN_REMARKS)
			->from($this->TABLENAME);
		if (is_null($order)) {
			$this->utility->order_by($this->COLUMN_ID, 'asc');
		} else {
			$this->utility->order_by($order['column'], $order['dir']);
		}
		if (!is_null($max) && !is_null($start)) {
			$this->utility->limit($max, $start);
		}
		$query = $this->utility->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function fetchAllNamesOnly() {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_REALMNAME)
			->from($this->TABLENAME)
			->order_by($this->COLUMN_ID, 'asc')
			->get();
		if ($query->num_rows() == 0) {
			return false;
		} else {
			$results = $query->result_array();
			$realms = [];
			for ($i = 0; $i < count($results); $i++) {
				$realms[] = $results[$i][$this->COLUMN_REALMNAME];
			}
			return $realms;
		}
	}
	public function searchRealm($keyword, $exact, $wildcardLocation) {
		$this->utility->db_select();
		$this->utility->select($this->COLUMN_ID.', '.$this->COLUMN_REALMNAME.', '.$this->COLUMN_REMARKS)
			->from($this->TABLENAME);
		if ($exact) {
			$this->utility->where($this->COLUMN_REALMNAME, $keyword);
		} else {
			$this->utility->like($this->COLUMN_REALMNAME, $keyword, $wildcardLocation);
		}
		$query = $this->utility->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	/*
	public function countUsersInRealmById($realmId) {
		$this->utility->db_select();
		$realm = $this->fetchById($realmId);
		$count = $this->utility->from('subscriber')
			->where('RBREALM', $realm[$this->COLUMN_REALMNAME])
			->count_all_results();
		return $count;
	}
	public function countUsersInRealmByName($realmName) {
		$this->utility->db_select();
		$count = $this->utility->from('subscriber')
			->where('RBREALM', $realmName)
			->count_all_results();
		return $count;
	}
	*/
}
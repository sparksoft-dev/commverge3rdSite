<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role extends CI_Model {
	private $utility;
	private $COLUMN_ROLEID = 'ROLEID';
	private $COLUMN_ROLENAME = 'ROLENAME';
	private $COLUMN_ROLELABEL = 'ROLELABEL';
	private $TABLENAME = 'TBLDSLROLES';

	function __construct() {
		parent::__construct();
		$this->utility = $this->load->database('utility', true);
	}

	public function create($name, $label) {
		$this->utility->db_select();
		$data = array(
			$this->COLUMN_ROLENAME => $name,
			$this->COLUMN_ROLELABEL => $label);
		$this->utility->insert($this->TABLENAME, $data);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function delete($name) {
		$this->utility->db_select();
		$this->utility->where($this->COLUMN_ROLENAME, $name)
			->delete($this->TABLENAME);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function deleteById($roleId) {
		$this->utility->db_select();
		$this->utility->where($this->COLUMN_ROLEID, $roleId)
			->delete($this->TABLENAME);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function roleExists($name) {
		$this->utility->db_select();
		$count = $this->utility->from($this->TABLENAME)
			->where($this->COLUMN_ROLENAME, $name)
			->count_all_results();
		return $count == 0 ? false : true;
	}
	public function fetchById($roleId) {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_ROLEID.', '.$this->COLUMN_ROLENAME.', '.$this->COLUMN_ROLELABEL)
			->from($this->TABLENAME)
			->where($this->COLUMN_ROLEID, $roleId)
			->get();
		return $query->num_rows() == 0 ? false : $query->row_array();
	}
	public function fetchByName($roleName) {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_ROLEID.', '.$this->COLUMN_ROLENAME.', '.$this->COLUMN_ROLELABEL)
			->from($this->TABLENAME)
			->where($this->COLUMN_ROLENAME, $roleName)
			->get();
		return $query->num_rows() == 0 ? false : $query->row_array();	
	}
	public function fetchAll($role) {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_ROLEID.', '.$this->COLUMN_ROLENAME.', '.$this->COLUMN_ROLELABEL)
			->from($this->TABLENAME)
			->where($this->COLUMN_ROLENAME.' !=', $role)
			->order_by($this->COLUMN_ROLEID, 'asc')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
}
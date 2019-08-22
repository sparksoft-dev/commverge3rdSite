<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Systime extends CI_Model {
	private $utility;
	private $COLUMN_ID = 'TIMEID';
	private $COLUMN_TIMEVALUE = 'TIMEVALUE';
	private $COLUMN_TIMELABEL = 'TIMELABEL';
	private $TABLENAME = 'TBLSYSTIME';

	function __construct() {
		parent::__construct();
		$this->utility = $this->load->database('utility', true);
	}

	/************************************************************
	 * time format:
	 * $time = array('S' => 1/0, 'M' => 1/0, ..., 'F' => 1/0, 'Sa' => 1/0,
	 *     'T00' => 1/0, 'T01' => 1/0, ..., 'T22' => 1/0, 'T23' => 1/0);
	 * json_encode($time);
	 ************************************************************/
	public function create($value, $label) {
		$this->utility->db_select();
		$data = array(
			$this->COLUMN_TIMEVALUE => $value,
			$this->COLUMN_TIMELABEL => $label);
		$this->utility->insert($this->TABLENAME, $data);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function delete($value) {
		$this->utility->db_select();
		$this->utility->where($this->COLUMN_ID, $id)
			->delete($this->TABLENAME);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function countTimes() {
		$this->utility->db_select();
		$count = $this->utility->from($this->TABLENAME)
			->count_all_results();
		return $count;
	}
	public function fetchAll() {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_ID.', '.$this->COLUMN_TIMEVALUE.', '.$this->COLUMN_TIMELABEL)
			->from($this->TABLENAME)
			->order_by($this->COLUMN_ID, 'asc')
			->get();
		return $query->result_array();
	}
	public function fetchByTimevalue($value) {
		$this->utility->db_select();
		$query = $this->utility->select('*')
			->from($this->TABLENAME)
			->where($this->COLUMN_TIMEVALUE, $value)
			->get();
		return $query->row_array();
	}
	/*
	public function fetchById($id) {
		$query = $this->utility->select($this->TIMEVALUE.', '.$this->TIMELABEL)
			->from($this->TABLENAME)
			->where($this->COLUMN_ID, $id)
			->get();
		return $query->num_rows() == 0 ? false : $query->row_array();
	}
	*/
}
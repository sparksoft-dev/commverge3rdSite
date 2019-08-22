<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Model {
	private $utility;
	private $COLUMN_PAGEID = 'PAGEID';
	private $COLUMN_PAGENAME = 'PAGENAME';
	private $TABLENAME = 'TBLDSLPAGE';

	function __construct() {
		parent::__construct();
		$this->utility = $this->load->database('utility', true);
	}

	public function create($name) {
		$this->utility->db_select();
		$data = array(
			$this->COLUMN_PAGENAME => $name);
		$this->utility->insert($this->TABLENAME, $data);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function delete($name) {
		$this->utility->db_select();
		$this->utility->where($this->COLUMN_PAGENAME, $name)
			->delete($this->TABLENAME);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function countPages() {
		$this->utility->db_select();
		$count = $this->utility->from($this->TABLENAME)
			->count_all_results();
		return $count;
	}
	public function fetchAll() {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_PAGEID.', '.$this->COLUMN_PAGENAME)
			->from($this->TABLENAME)
			->order_by($this->COLUMN_PAGEID, 'asc')
			->get();
		return $query->result_array();
	}
	public function fetchPageNames($arrayIds) {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_PAGENAME)
			->from($this->TABLENAME)
			->where_in($this->COLUMN_PAGEID, $arrayIds)
			->get();
		$result = $query->result_array();
		$pageNames = [];
		for ($i = 0; $i < count($result); $i++) {
			$pageNames[] = $result[$i][$this->COLUMN_PAGENAME];
		}
		return $pageNames;
	}
}
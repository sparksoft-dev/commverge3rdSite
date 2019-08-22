<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migrationreport extends CI_Model {
	private $utility;
	private $extras;

	function __construct() {
		parent::__construct();
		$this->utility = $this->load->database('utility', true);
		$this->extras = $this->load->database('extras', true);
	}

	//mysql side
	public function fetchPlanMap($plan, $start, $max) {
		$this->extras->db_select();
		$query = $this->extras->select('*')
			->from('migrate_plan_map')
			->where('plan', $plan)
			->order_by('plan', 'asc')
			->order_by('username', 'sc')
			->limit($max, $start)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countPlanMap($plan) {
		$this->extras->db_select();
		$count = $this->extras->from('migrate_plan_map')
			->where('plan', $plan)
			->count_all_results();
		return $count;
	}
	public function fetchPlanCounts() {
		$this->extras->db_select();
		$query = $this->extras->select('*')
			->from('migrate_plan_count')
			->order_by('plan', 'asc')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countPlanTotals() {
		$this->extras->db_select();
		$query = $this->extras->select_sum('pcount')
			->from('migrate_plan_count')
			->get();
		return $query->row_array();
	}

	//oracle side
	public function fetchPlanMapInOracle($plan, $start, $max) {
		$this->utility->db_select();
		$query = $this->utility->select('USERNAME, RADIUSPOLICY')
			->from('TBLCUSTOMER')
			->where('RADIUSPOLICY', $plan)
			->order_by('RADIUSPOLICY', 'asc')
			->order_by('USERNAME', 'asc')
			->limit($start == 0 ? $max + 1 : $max, $start == 0 ? $start : $start + 1)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countPlanMapInOracle($plan) {
		$this->utility->db_select();
		$count = $this->utility->from('TBLCUSTOMER')
			->where('RADIUSPOLICY', $plan)
			->count_all_results();
		return $count;
	}
	public function fetchPlanCountsInOracle() {
		$this->utility->db_select();
		$sql = "select TBLRADIUSPOLICY.RADIUSPOLICYNAME as service, count(TBLCUSTOMER.USER_IDENTITY) as count from TBLRADIUSPOLICY ".
			"left join TBLCUSTOMER on REPLACE(TBLCUSTOMER.RADIUSPOLICY, '~', '-') = TBLRADIUSPOLICY.RADIUSPOLICYNAME ".
			"group by TBLRADIUSPOLICY.RADIUSPOLICYNAME ".
			"order by TBLRADIUSPOLICY.RADIUSPOLICYNAME ASC";
		$query = $this->utility->query($sql);
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countPlanTotalsInOracle() {
		$this->utility->db_select();
		$count = $this->utility->from('TBLCUSTOMER')
			->where('RADIUSPOLICY is not null')
			->count_all_results();
		return $count;
	}
}
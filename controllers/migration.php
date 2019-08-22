<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration extends CI_Controller {

	public function index() {
		redirect('planCounts');
	}
	public function planCounts() {
		$this->load->model('migrationreport');
		$counts = $this->migrationreport->fetchPlanCounts();
		$countsTotal = $this->migrationreport->countPlanTotals();
		$countsOracle = $this->migrationreport->fetchPlanCountsInOracle();
		$countsOracleTotal = $this->migrationreport->countPlanTotalsInOracle();
		$data = array(
			'counts' => $counts,
			'countsTotal' => $countsTotal,
			'countsOracle' => $countsOracle,
			'countsOracleTotal' => $countsOracleTotal);
		$this->load->view('migration_plan_counts', $data);
	}
	public function planMap($from = null, $plan = null, $start = 0) {
		if (is_null($from) || is_null($plan)) {
			redirect('planCounts');
		}
		$plan = str_replace('%%', ' ', $plan);
		$plan = str_replace('~', '-', $plan);
		$this->load->model('migrationreport');
		$max = 50;
		if ($from == 'file') {
			$map = $this->migrationreport->fetchPlanMap($plan, $start, $max);
			$count = $this->migrationreport->countPlanMap($plan);
		} else if ($from == 'oracle') {
			$map = $this->migrationreport->fetchPlanMapInOracle(str_replace('-', '~', $plan), $start, $max);
			$count = $this->migrationreport->countPlanMapInOracle(str_replace('-', '~', $plan));
		}
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		$data = array(
			'map' => $map,
			'count' => $count,
			'pages' => $pages,
			'start' => $start,
			'max' => $max,
			'source' => $from,
			'plan' => $plan);
		$this->load->view('migration_plan_map', $data);
	}

}
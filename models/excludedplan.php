<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Excludedplan extends CI_Model {
	private $extras;

	function __construct() {
		parent::__construct();
		$this->extras = $this->load->database('extras', true);
	}

	public function fetchAllNamesOnly() {
		// return ['UV-5M-1300G', 'UV-10M-1400G', 'UV-15M-1500G', 'UV-20M-1500G', 'UV-50M-1500G', 'UV-100M-2000G'];
		$this->extras->db_select();
		$query = $this->extras->select('excludedname')
			->from('excludedplan')
			->get();

		if ($query->num_rows() == 0) {
			return false;
		} else {
			$results = $query->result_array();
			$excludedplans = [];
			for ($i = 0; $i < count($results); $i++) {
				$excludedplans[] = $results[$i]['excludedname'];
			}
			return $excludedplans;
		}
	}

}

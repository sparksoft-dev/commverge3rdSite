<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller {
	private $SUPERUSER = 'vinadmin';
	private $SUPERGROUP = 'VINADMIN';
	private $PER = 5000;
	// $SESSIONXXX series is the main aaa db
	private $SESSIONHOST = '10.166.12.8';
	private $SESSIONPORT = '1521';
	private $SESSIONSCHEMA = 'aaadb';
	private $SESSIONUSERNAME = 'etplprov';
	private $SESSIONPASSWORD = 'etplprov';

	public function __construct(){
	    parent::__construct();
	    $this->load->model('settings');
	    $cfg = $this->settings->loadFromFile();
	    if (!is_null($cfg)) {
	    	$this->SUPERUSER = $cfg['SUPERUSER'];
	    	$this->SESSIONHOST = strval($cfg['SESSIONHOST']);
	    	// $SESSIONXXX series is the main aaa db
			$this->SESSIONPORT = strval($cfg['SESSIONPORT']);
			$this->SESSIONSCHEMA = strval($cfg['SESSIONSCHEMA']);
			$this->SESSIONUSERNAME = strval($cfg['SESSIONUSERNAME']);
			$this->SESSIONPASSWORD = strval($cfg['SESSIONPASSWORD']);
	    }
	}

	/***********************************************************************
	 * report generation
	 * PAGEID = 16
	 ***********************************************************************/
	public function showReportGenerationPage() {
		$this->redirectIfNoAccess('Report Generation', 'reports/showReportGenerationPage');
		$this->load->view('report_generation');
	}
	public function generateSubscriberStatusReport($link = 0, $count = null, $status = 'Active', $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Report Generation', 'reports/generateSubscriberStatusReport');
		$portal = $this->session->userdata('portal');
		$realm = $portal === 'service' ? $this->session->userdata('realm') : null;
		$this->load->model('subscribermain');
		$this->load->model('util');
		//$yesterday = $this->util->getDateYesterday(true);
		// $yesterday = mktime(date('h'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'));
		$yesterday = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
		$yesterday = date('D M d H:i:s T Y', $yesterday);
		$submit = '';
		$pages = 0;
		if (intval($link) == 0) { //via form
			$status = trim($this->input->post('status'));
			$count = trim($this->input->post('hiddenCount'));
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$submit = $this->input->post('submit');
			$start = $submit == 'list' ? 0 : $start;
		} else { //via link
			$count = $count == 'null' ? null : $count;
		}
		$subscribers = null;
		if (!is_null($count)) {
			$subscribers = $this->subscribermain->reportSubscribersWithStatus($realm, $status, $start, $max);
			$count = $this->subscribermain->countSubscribersWithStatus($realm, $status);
			$pages = intval($count / $max);
			$last = $count % $max;
			if ($last > 0) {
				$pages = $pages + 1;
			}
		}
		if ($submit == 'extract' || $submit == 'extract (2)' || $submit == 'extract (3)') {
			/*
			 * flow no longer goes through here as extraction is handled by ajax
			 */
			/*
			$headers = $this->subscribermain->DEFAULT_COLUMNS; //'ACCOUNT TYPE', 'REALM', 'USERNAME', 'ACCOUNT PLAN', 'STATUS', 'SERVICE', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'ORDER NUMBER', 'SERVICE NUMBER', 'CUSTOMER NAME', 'IP ADDRESS', 'NET ADDRESS', 'REMARKS'
			$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];
			$title = 'Subscriber Status Report';
			try {
				if ($status == 'Active') {
					$per = 5000;
				} else {
					$per = 1000;
				}
				log_message('info', 'count:'.$count.'|submit:'.$submit);
				if ($count < 200000)  {
					$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title);
					if ($per > $count) {
						$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, 0, $count);
						$this->util->writeSubscriberReportCsv($path, $data);
					} else {
						$loops = intval($count / $per);
						$remaining = $count % $per;
						for ($k = 0; $k < $loops * $per; $k+=$per) {
							$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, $k, $per);
							$this->util->writeSubscriberReportCsv($path, $data);
						}
						if ($remaining != 0) {
							$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, $loops * $per, $remaining);
							$this->util->writeSubscriberReportCsv($path, $data);
						}
					}
					$this->util->fetchLargeFile($path);
				} else if ($count >= 200000 && $count < 400000) {
					if ($submit == 'extract') {
						$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title."1");
						$count = 200000;
						$loops = intval($count / $per);
						for ($k = 0; $k < $loops * $per; $k+=$per) {
							$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, $k, $per);
							$this->util->writeSubscriberReportCsv($path, $data);
						}
						$this->util->fetchLargeFile($path);
					} else if ($submit == 'extract (2)') {
						$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title."2");
						$count = $count - 200000;
						if ($per > $count) {
							$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, 200000, $count);
							$this->util->writeSubscriberReportCsv($path, $data);
						} else {
							$loops = intval($count / $per);
							$remaining = $count % $per;
							for ($k = 0; $k < $loops * $per; $k+=$per) {
								$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, $k + 200000, $per);
								$this->util->writeSubscriberReportCsv($path, $data);
							}
							if ($remaining != 0) {
								$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, 200000 + ($loops * $per), $remaining);
								$this->util->writeSubscriberReportCsv($path, $data);
							}
						}
						$this->util->fetchLargeFile($path);
					}
				} else if ($count >= 400000) {
					if ($submit == 'extract') {
						$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title."1");
						$count = 200000;
						$loops = intval($count / $per);
						for ($k = 0; $k < $loops * $per; $k+=$per) {
							$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, $k, $per);
							$this->util->writeSubscriberReportCsv($path, $data);
						}
						$this->util->fetchLargeFile($path);
					} else if ($submit == 'extract (2)') {
						$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title."2");
						$count = $count - 200000;
						if ($per > $count) {
							$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, 200000, $count);
							$this->util->writeSubscriberReportCsv($path, $data);
						} else {
							$loops = intval($count / $per);
							$remaining = $count % $per;
							for ($k = 0; $k < $loops * $per; $k+=$per) {
								$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, $k + 200000, $per);
								$this->util->writeSubscriberReportCsv($path, $data);
							}
							if ($remaining != 0) {
								$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, 200000 + ($loops * $per), $remaining);
								$this->util->writeSubscriberReportCsv($path, $data);
							}
						}
						$this->util->fetchLargeFile($path);
					} else if ($submit == 'extract (3)') {
						$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title."3");
						$count = $count - 400000;
						if ($per > $count) {
							$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, 400000, $count);
							$this->util->writeSubscriberReportCsv($path, $data);
						} else {
							$loops = intval($count / $per);
							$remaining = $count % $per;
							for ($k = 0; $k < $loops * $per; $k+=$per) {
								$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, $k + 400000, $per);
								$this->util->writeSubscriberReportCsv($path, $data);
							}
							if ($remaining != 0) {
								$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, 400000 + ($loops * $per), $remaining);
								$this->util->writeSubscriberReportCsv($path, $data);
							}
						}
						$this->util->fetchLargeFile($path);
					}
				}
			} catch (Exception $e) {
				log_message('info', 'ERROR:'.json_encode($e));
				$data = array(
					'subscribers' => $subscribers,
					'yesterday' => $yesterday,
					'status' => $status,
					'count' => $count,
					'start' => $start,
					'max' => $max,
					'pages' => $pages,
					'error' => 'Error extracting entries.');
				$this->load->view('report_generation_status', $data);
			}
			*/
		} else {
			$data = array(
				'subscribers' => $subscribers,
				'yesterday' => $yesterday,
				'status' => $status,
				'count' => $count,
				'start' => $start,
				'max' => $max,
				'pages' => $pages,
				'error' => '');
			$this->load->view('report_generation_status', $data);
		}
	}
	public function generateSubscriberPackageReport($link = 0, $count = null, $service = '', $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Report Generation', 'reports/generateSubscriberPackageReport');
		$portal = $this->session->userdata('portal');
		$realm = $portal === 'service' ? $this->session->userdata('realm') : null;
		$this->load->model('services');
		// $serviceFilter = $this->services->fetchAllNamesOnly2();
		$serviceFilter = $this->services->fetchAllNamesOnlyNew($this->SESSIONHOST, $this->SESSIONPORT, $this->SESSIONSCHEMA, $this->SESSIONUSERNAME, $this->SESSIONPASSWORD);
		$this->load->model('subscribermain');
		$this->load->model('util');
		//$yesterday = $this->util->getDateYesterday(true);
		// $yesterday = mktime(date('h'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'));
		$yesterday = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
		$yesterday = date('D M d H:i:s T Y', $yesterday);
		$submit = '';
		$pages = 0;
		if (intval($link) == 0) { //via form
			$service = trim($this->input->post('service'));
			$service = str_replace('%%', ' ', $service);
			$count = trim($this->input->post('hiddenCount'));
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$submit = $this->input->post('submit');
			$start = $submit == 'list' ? 0 : $start;
		} else { //via link
			$count = $count == 'null' ? null : $count;
			$service = str_replace('%%', ' ', $service);
			log_message('info', '@package report:'.$service);
		}
		$subscribers = null;
		if (!is_null($count)) {
			$subscribers = $this->subscribermain->reportSubscribersWithService($realm, $service, $start, $max);
			$count = $this->subscribermain->countSubscribersWithService($realm, $service);
			$pages = intval($count / $max);
			$last = $count % $max;
			if ($last > 0) {
				$pages = $pages + 1;
			}
		}
		if ($submit == 'extract') {
			/*
			 * flow no longer goes through here as extraction is handled by ajax
			 */
			/*
			$headers = $this->subscribermain->DEFAULT_COLUMNS; //'ACCOUNT TYPE', 'REALM', 'USERNAME', 'ACCOUNT PLAN', 'STATUS', 'SERVICE', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'ORDER NUMBER', 'SERVICE NUMBER', 'CUSTOMER NAME', 'IP ADDRESS', 'NET ADDRESS', 'REMARKS'
			$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];
			$title = 'Subscriber Package Report';
			try {
				$per = 5000;
				$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title);
				if ($per > $count) {
					$data = $this->subscribermain->reportSubscribersWithService($realm, $service, 0, $count);
					$this->util->writeSubscriberReportCsv($path, $data);
				} else {
					$loops = intval($count / $per);
					$remaining = $count % $per;
					for ($k = 0; $k < $loops * $per; $k+=$per) {
						$data = $this->subscribermain->reportSubscribersWithService($realm, $service, $k, $per);
						$this->util->writeSubscriberReportCsv($path, $data);
					}
					if ($remaining != 0) {
						$data = $this->subscribermain->reportSubscribersWithService($realm, $service, $loops * $per, $remaining);
						$this->util->writeSubscriberReportCsv($path, $data);
					}
				}
				$this->util->fetchLargeFile($path);
			} catch (Exception $e) {
				$data = array(
					'subscribers' => $subscribers,
					'yesterday' => $yesterday,
					'serviceFilter' => $serviceFilter,
					'service' => $service,
					'count' => $count,
					'start' => $start,
					'max' => $max,
					'pages' => $pages,
					'error' => 'Error extracting entries.');
				$this->load->view('report_generation_package', $data);
			}
			*/
		} else {
			$data = array(
				'subscribers' => $subscribers,
				'yesterday' => $yesterday,
				'serviceFilter' => $serviceFilter,
				'service' => $service,
				'count' => $count,
				'start' => $start,
				'max' => $max,
				'pages' => $pages,
				'error' => '');
			$this->load->view('report_generation_package', $data);
		}
	}
	public function generateSubscriberCreationReport($link = 0, $count = null, $datestart = null, $dateend = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Report Generation', 'reports/generateSubscriberCreationReport');
		$portal = $this->session->userdata('portal');
		$realm = $portal === 'service' ? $this->session->userdata('realm') : null;
		$this->load->model('subscribermain');
		$this->load->model('util');
		//$yesterday = $this->util->getDateYesterday(true);
		// $yesterday = mktime(date('h'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'));
		$yesterday = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
		$yesterday = date('D M d H:i:s T Y', $yesterday);
		$submit = '';
		$pages = 0;
		if (intval($link) == 0) { //via form
			$sDay = trim($this->input->post('start_day'));
			$sMonth = trim($this->input->post('start_month'));
			$sYear = trim($this->input->post('start_year'));
			$eDay = trim($this->input->post('end_day'));
			$eMonth = trim($this->input->post('end_month'));
			$eYear = trim($this->input->post('end_year'));
			$datestart = mktime(0, 0, 0, $sMonth, $sDay, $sYear);
			$dateend = mktime(0, 0, 0, $eMonth, $eDay, $eYear);
			$count = trim($this->input->post('hiddenCount'));
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$submit = $this->input->post('submit');
			$start = $submit == 'list' ? 0 : $start;
		} else { //via link
			$count = $count == 'null' ? null : $count;
			$datestartParts = is_null($datestart) ? null : explode('-', $datestart);//Y-m-d
			$datestart = is_null($datestart) ? null : mktime(0, 0, 0, $datestartParts[1], $datestartParts[2], $datestartParts[0]);
			$dateendParts = is_null($dateend) ? null : explode('-', $dateend);
			$dateend = is_null($dateend) ? null : mktime(0, 0, 0, $dateendParts[1], $dateendParts[2], $dateendParts[0]);
		}
		$subscribers = null;
		if (!is_null($count)) {
			$subscribers = $this->subscribermain->reportSubscribersCreatedWithinDates($realm, $datestart, $dateend, $start, $max);
			$count = $this->subscribermain->countSubscribersCreatedWithinDates($realm, $datestart, $dateend);
			$pages = intval($count / $max);
			$last = $count % $max;
			if ($last > 0) {
				$pages = $pages + 1;
			}
		}
		if ($submit == 'extract') {
			/*
			 * flow no longer goes through here as extraction is handled by ajax
			 */
			/*
			$headers = $this->subscribermain->DEFAULT_COLUMNS; //'ACCOUNT TYPE', 'REALM', 'USERNAME', 'ACCOUNT PLAN', 'STATUS', 'SERVICE', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'ORDER NUMBER', 'SERVICE NUMBER', 'CUSTOMER NAME', 'IP ADDRESS', 'NET ADDRESS', 'REMARKS'
			$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];
			$title = 'Subscriber Creation Report';
			try {
				$per = 5000;
				$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title);
				if ($per > $count) {
					$data = $this->subscribermain->reportSubscribersCreatedWithinDates($realm, $datestart, $dateend, 0, $count);
					$this->util->writeSubscriberReportCsv($path, $data);
				} else {
					$loops = intval($count / $per);
					$remaining = $count % $per;
					for ($k = 0; $k < $loops * $per; $k+=$per) {
						$data = $this->subscribermain->reportSubscribersCreatedWithinDates($realm, $datestart, $dateend, $k, $per);
						$this->util->writeSubscriberReportCsv($path, $data);
					}
					if ($remaining != 0) {
						$data = $this->subscribermain->reportSubscribersCreatedWithinDates($realm, $datestart, $dateend, $loops * $per, $remaining);
						$this->util->writeSubscriberReportCsv($path, $data);
					}
				}
				$this->util->fetchLargeFile($path);
			} catch (Exception $e) {
				$data = array(
					'subscribers' => $subscribers,
					'yesterday' => $yesterday,
					'datestart' => is_null($datestart) ? $datestart : date('Y-m-d', $datestart),
					'dateend' => is_null($dateend) ? $dateend : date('Y-m-d', $dateend),
					'count' => $count,
					'start' => $start,
					'max' => $max,
					'pages' => $pages,
					'error' => 'Error extracting entries.');
				$this->load->view('report_generation_creation_date', $data);
			}
			*/
		} else {
			$data = array(
				'subscribers' => $subscribers,
				'yesterday' => $yesterday,
				'datestart' => is_null($datestart) ? $datestart : date('Y-m-d', $datestart),
				'dateend' => is_null($dateend) ? $dateend : date('Y-m-d', $dateend),
				'count' => $count,
				'start' => $start,
				'max' => $max,
				'pages' => $pages,
				'error' => '');
			$this->load->view('report_generation_creation_date', $data);
		}
	}
	public function generateSubscriberIpReport($link = 0, $count = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Report Generation', 'reports/generateSubscriberIpReport');
		$portal = $this->session->userdata('portal');
		$realm = $portal === 'service' ? $this->session->userdata('realm') : null;
		$this->load->model('subscribermain');
		$this->load->model('util');
		//$yesterday = $this->util->getDateYesterday(true);
		// $yesterday = mktime(date('h'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'));
		$yesterday = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
		$yesterday = date('D M d H:i:s T Y', $yesterday);
		$submit = '';
		$pages = 0;
		if (intval($link) == 0) { //via form
			$count = trim($this->input->post('hiddenCount'));
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$submit = $this->input->post('submit');
			$start = $submit == 'list' ? 0 : $start;
		} else { //via link
			$count = $count == 'null' ? null : $count;
		}
		$subscribers = null;
		if (!is_null($count)) {
			$subscribers = $this->subscribermain->reportSubscribersWithStaticIpV2($realm, $start, $max);
			$count = $this->subscribermain->countSubscribersWithStaticIp($realm);
			$pages = intval($count / $max);
			$last = $count % $max;
			if ($last > 0) {
				$pages = $pages + 1;
			}
		}
		if ($submit == 'extract') {
			/*
			 * flow no longer goes through here as extraction is handled by ajax
			 */
			/*
			$headers = $this->subscribermain->DEFAULT_COLUMNS; //'ACCOUNT TYPE', 'REALM', 'USERNAME', 'ACCOUNT PLAN', 'STATUS', 'SERVICE', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'ORDER NUMBER', 'SERVICE NUMBER', 'CUSTOMER NAME', 'IP ADDRESS', 'NET ADDRESS', 'REMARKS'
			$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];
			$title = 'Subscriber IP Report';
			try {
				$per = 5000;
				$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title);
				if ($per > $count) {
					$data = $this->subscribermain->reportSubscribersWithStaticIp($realm, 0, $count);
					$this->util->writeSubscriberReportCsv($path, $data);
				} else {
					$loops = intval($count / $per);
					$remaining = $count % $per;
					for ($k = 0; $k < $loops * $per; $k+=$per) {
						$data = $this->subscribermain->reportSubscribersWithStaticIp($realm, $k, $per);
						$this->util->writeSubscriberReportCsv($path, $data);
					}
					if ($remaining != 0) {
						$data = $this->subscribermain->reportSubscribersWithStaticIp($realm, $loops * $per, $remaining);
						$this->util->writeSubscriberReportCsv($path, $data);
					}
				}
				$this->util->fetchLargeFile($path);
			} catch (Exception $e) {
				$data = array(
					'subscribers' => $subscribers,
					'yesterday' => $yesterday,
					'count' => $count,
					'start' => $start,
					'max' => $max,
					'pages' => $pages,
					'error' => 'Error extracting entries.');
				$this->load->view('report_generation_ip', $data);
			}
			*/
		} else {
			$data = array(
				'subscribers' => $subscribers,
				'yesterday' => $yesterday,
				'count' => $count,
				'start' => $start,
				'max' => $max,
				'pages' => $pages,
				'error' => '');
			$this->load->view('report_generation_ip', $data);
		}
	}
	public function generateSubscriberIpNetReport($link = 0, $count = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Report Generation', 'reports/generateSubscriberIpNetReport');
		$portal = $this->session->userdata('portal');
		$realm = $portal === 'service' ? $this->session->userdata('realm') : null;
		$this->load->model('subscribermain');
		$this->load->model('util');
		//$yesterday = $this->util->getDateYesterday(true);
		// $yesterday = mktime(date('h'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'));
		$yesterday = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
		$yesterday = date('D M d H:i:s T Y', $yesterday);
		$submit = '';
		$pages = 0;
		if (intval($link) == 0) { //via form
			$count = trim($this->input->post('hiddenCount'));
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$submit = $this->input->post('submit');
			$start = $submit == 'list' ? 0 : $start;
		} else { //via link
			$count = $count == 'null' ? null : $count;
		}
		$subscribers = null;
		if (!is_null($count)) {
			$subscribers = $this->subscribermain->reportSubscribersWithStaticIpAndMultistaticIpV2($realm, $start, $max);
			$count = $this->subscribermain->countSubscribersWithStaticIpAndMultistaticIp($realm);
			$pages = intval($count / $max);
			$last = $count % $max;
			if ($last > 0) {
				$pages = $pages + 1;
			}
		}
		if ($submit == 'extract') {
			/*
			 * flow no longer goes through here as extraction is handled by ajax
			 */
			/*
			$headers = $this->subscribermain->DEFAULT_COLUMNS; //'ACCOUNT TYPE', 'REALM', 'USERNAME', 'ACCOUNT PLAN', 'STATUS', 'SERVICE', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'ORDER NUMBER', 'SERVICE NUMBER', 'CUSTOMER NAME', 'IP ADDRESS', 'NET ADDRESS', 'REMARKS'
			$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];
			$title = 'Subscriber IPNet Report';
			try {
				$per = 5000;
				$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title);
				if ($per > $count) {
					$data = $this->subscribermain->reportSubscribersWithStaticIpAndMultistaticIp($realm, 0, $count);
					$this->util->writeSubscriberReportCsv($path, $data);
				} else {
					$loops = intval($count / $per);
					$remaining = $count % $per;
					for ($k = 0; $k < $loops * $per; $k+=$per) {
						$data = $this->subscribermain->reportSubscribersWithStaticIpAndMultistaticIp($realm, $k, $per);
						$this->util->writeSubscriberReportCsv($path, $data);
					}
					if ($remaining != 0) {
						$data = $this->subscribermain->reportSubscribersWithStaticIpAndMultistaticIp($realm, $loops * $per, $remaining);
						$this->util->writeSubscriberReportCsv($path, $data);
					}
				}
				$this->util->fetchLargeFile($path);
			} catch (Exception $e) {
				$data = array(
					'subscribers' => $subscribers,
					'yesterday' => $yesterday,
					'count' => $count,
					'start' => $start,
					'max' => $max,
					'pages' => $pages,
					'error' => 'Error extracting entries.');
				$this->load->view('report_generation_ip_net', $data);
			}
			*/
		} else {
			$data = array(
				'subscribers' => $subscribers,
				'yesterday' => $yesterday,
				'count' => $count,
				'start' => $start,
				'max' => $max,
				'pages' => $pages,
				'error' => '');
			$this->load->view('report_generation_ip_net', $data);
		}
	}
	public function generateSubscriberBandwidthReport($link = 0, $count = null, $bandwidth = '', $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Report Generation', 'reports/generateSubscriberBandwidthReport');
		$portal = $this->session->userdata('portal');
		$realm = $portal == 'service' ? $this->session->userdata('realm') : null;
		$this->load->model('subscriber');
		$this->load->model('util');
		//$yesterday = $this->util->getDateYesterday(true);
		// $yesterday = mktime(date('h'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'));
		$yesterday = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
		$yesterday = date('D M d H:i:s T Y', $yesterday);
		$submit = '';
		$pages = 0;
		if (intval($link) == 0) { //via form
			$bandwidth = trim($this->input->post('bandwidth'));
			$bandwidth = str_replace('_', ' ', $bandwidth);
			$count = trim($this->input->post('hiddenCount'));
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$submit = $this->input->post('submit');
			$start = $submit == 'list' ? 0 : $start;
		} else { //via link
			$count = $count == 'null' ? null : $count;
			$bandwidth = str_replace('_', ' ', $bandwidth);
		}
		$subscribers = null;
		if (!is_null($count)) {
			$subscribers = $this->subscriber->reportSubscribersWithBandwidth($realm, $bandwidth, $start, $max);
			$count = $this->subscriber->countSubscribersWithBandwidth($realm, $bandwidth);
			$pages = intval($count / $max);
			$last = $count % $max;
			if ($last > 0) {
				$pages = $pages + 1;
			}
		}
		if ($submit == 'extract') {
			$headers = $this->subscriber->DEFAULT_COLUMNS; //'ACCOUNT TYPE', 'REALM', 'USERNAME', 'ACCOUNT PLAN', 'STATUS', 'SERVICE', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'ORDER NUMBER', 'SERVICE NUMBER', 'CUSTOMER NAME', 'IP ADDRESS', 'NET ADDRESS', 'REMARKS'
			$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];
			$title = 'Subscriber Bandwidth Report';
			try {
				$subscribers = $this->subscribermain->reportSubscribersWithBandwithAll($realm, $bandwidth);
				$this->util->writeSubscriberReport($subscribers, $headers, $columns, $title);
			} catch (Exception $e) {
				$data = array(
					'subscribers' => $subscribers,
					'yesterday' => $this->util->getDateYesterday(true),
					'bandwidth' => $bandwidth,
					'count' => $count,
					'start' => $start,
					'max' => $max,
					'pages' => $pages,
					'error' => 'Error extracting entries.');
				$this->load->view('report_generation_bandwidth', $data);
			}
		} else {
			$data = array(
				'subscribers' => $subscribers,
				'yesterday' => $this->util->getDateYesterday(true),
				'bandwidth' => $bandwidth,
				'count' => $count,
				'start' => $start,
				'max' => $max,
				'pages' => $pages,
				'error' => '');
			$this->load->view('report_generation_bandwidth', $data);
		}
	}
	public function generateSubscriberCappedReport($link = 0, $count = null, $datestart = null, $dateend = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Report Generation', 'reports/generateSubscriberCappedReport');
		$portal = $this->session->userdata('portal');
		$realm = $portal == 'service' ? $this->session->userdata('realm') : null;
		$this->load->model('subscribermain');
		$this->load->model('util');
		$yesterday = mktime(date('H') - 1, date('i'), date('s'), date('m'), date('d'), date('Y'));
		//$yesterday = mktime(date('h'), date('i'), date('s'), date('m'), date('d'), date('Y'));
		$yesterday = date('D M d H:i:s T Y', $yesterday);
		$submit = '';
		$pages = 0;
		if (intval($link) == 0) { //via form
			$sMonth = $this->input->post('start_month');
			$sDay = $this->input->post('start_day');
			$eDay = $this->input->post('end_day');
			$sYear = $this->input->post('start_year');
			log_message('info', 'date:'.$sMonth.' '.$sDay.' '.$eDay.' '.$sYear);
			$datestart = mktime(0, 0, 0, $sMonth, $sDay, $sYear);
			$dateend = mktime(0, 0, 0, $sMonth, $eDay, $sYear);
			$count = trim($this->input->post('hiddenCount'));
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$submit = $this->input->post('submit');
			$start = $submit == 'list' ? 0 : $start;
		} else { //via link
			$count = $count == 'null' ? null : $count;
			$datestartParts = is_null($datestart) ? null : explode('-', $datestart);//Y-m-d
			$datestart = is_null($datestart) ? null : mktime(0, 0, 0, $datestartParts[1], $datestartParts[2], $datestartParts[0]);
			$dateendParts = is_null($dateend) ? null : explode('-', $dateend);
			$dateend = is_null($dateend) ? null : mktime(0, 0, 0, $dateendParts[1], $dateendParts[2], $dateendParts[0]);
		}
		$subscribers = null;
		if (!is_null($count)) {
			$subscribers = $this->subscribermain->reportCappedSubscribers($realm, $datestart, $dateend, $start, $max);
			$count = $this->subscribermain->countCappedSubscribers($realm, $datestart, $dateend);
			$pages = intval($count / $max);
			$last = $count % $max;
			if ($last > 0) {
				$pages = $pages + 1;
			}
		}
		if ($submit == 'extract') {
			/*
			$headers = ['USERNAME', 'SERVICE', 'DOWNLOAD DATA', 'UPLOAD DATA', 'TOTAL DATA', 'CAP', 'DESCRIPTION', 'TIMESTAMP'];
			$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
			$title = 'Subscriber Capped Report';
			try {
				$per = 5000;
				$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title);
				if ($per > $count) {
					$data = $this->subscribermain->reportCappedSubscribers(0, $count);
					$this->util->writeSubscriberCappedReportCsv($path, $data);
				} else {
					$loops = intval($count / $per);
					$remaining = $count % $per;
					for ($k = 0; $k < $loops * $per; $k+=$per) {
						$data = $this->subscribermain->reportCappedSubscribers($k, $per);
						$this->util->writeSubscriberCappedReportCsv($path, $data);
					}
					if ($remaining != 0) {
						$data = $this->subscribermain->reportCappedSubscribers($loops * $per, $remaining);
						$this->util->writeSubscriberCappedReportCsv($path, $data);
					}
				}
				$this->util->fetchLargeFile($path);
			} catch (Exception $e) {
				$data = array(
					'subscribers' => $subscribers,
					'yesterday' => $yesterday,
					'count' => $count,
					'start' => $start,
					'max' => $max,
					'pages' => $pages,
					'error' => 'Error extracting entries.');
				$this->load->view('report_generation_capped', $data);
			}
			*/
		} else {
			log_message('info', 'SUBS:'.count($subscribers).'|'.json_encode($subscribers));
			$data = array(
				'subscribers' => $subscribers,
				'yesterday' => $yesterday,
				'datestart' => is_null($datestart) ? $datestart : date('Y-m-d', $datestart),
				'dateend' => is_null($dateend) ? $dateend : date('Y-m-d', $dateend),
				'count' => $count,
				'start' => $start,
				'max' => $max,
				'pages' => $pages,
				'error' => '');
			$this->load->view('report_generation_capped', $data);
		}
	}

	public function ajaxGenerateSubscriberStatusReport() {
		if ($this->input->is_ajax_request()) {
			$portal = $this->session->userdata('portal');
			$realm = $portal === 'service' ? $this->session->userdata('realm') : null;
			$status = $this->input->post('status');
			$this->load->model('subscribermain');
			$this->load->model('util');
			$count = $this->subscribermain->countSubscribersWithStatus($realm, $status);
			$headers = $this->subscribermain->DEFAULT_COLUMNS; //'ACCOUNT TYPE', 'REALM', 'USERNAME', 'ACCOUNT PLAN', 'STATUS', 'SERVICE', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'ORDER NUMBER', 'SERVICE NUMBER', 'CUSTOMER NAME', 'IP ADDRESS', 'NET ADDRESS', 'REMARKS'
			$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];
			$title = 'Subscriber Status Report';
			$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title);
			if ($this->PER > $count) {
				$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, 0, $count);
				$this->util->writeSubscriberReportCsv($path, $data);
			} else {
				log_message('debug', 'max_execution_time set to 1800s');
				ini_set('max_execution_time', 1800);
				ini_set('memory_limit', '512M');
				$loops = intval($count / $this->PER);
				$remaining = $count % $this->PER;
				// for testing ----------------------------------------------------------
				// $remaining = $loops > 18 ? 0 : $remaining;
				// $loops = $loops > 18 ? 18 : $loops;
				// for testing ----------------------------------------------------------
				log_message('debug', 'loop count: '.$loops.' | remaining: '.$remaining);
				for ($k = 0; $k < $loops * $this->PER; $k+=$this->PER) {
					log_message('info', 'subscriber status report loop '.($k + 1).' - '.($k + $this->PER));
					$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, $k, $this->PER);
					$this->util->writeSubscriberReportCsv($path, $data);
				}
				if ($remaining != 0) {
					log_message('info', 'subscriber status report loop '.(($loops * $this->PER) + 1).' - '.(($loops * $this->PER) + $remaining));
					$data = $this->subscribermain->reportSubscribersWithStatus($realm, $status, $loops * $this->PER, $remaining);
					$this->util->writeSubscriberReportCsv($path, $data);
				}
			}
			//zip file so it won't be too big (as of 1/9/2017, active file is over 100mb)
			try {
				$zip = new ZipArchive();
				$zipPathFilename = str_replace('.csv', '.zip', $path);
				if ($zip->open($zipPathFilename, ZipArchive::CREATE) !== true) {
					log_message('debug', 'failed to open zip file: '.$zipPathFilename);
					$data = array('status' => '0');
				} else {
					$parts = explode('/', $path);
					$filename = $parts[count($parts) - 1];
					$zip->addFile($path, $filename);
					$zip->close();
					log_message('debug', $filename.' added to '.$zipPathFilename);
					$data = array('status' => '1', 'full_path' => base_url().substr($zipPathFilename, strpos($zipPathFilename, 'deletedusers')));
					unlink($path);
					log_message('debug', 'deleted '.$path);
				}
			} catch (Exception $e) {
				log_message('debug', 'failed to create zip');
				$data = array('status' => '0');
			}
			echo json_encode($data);
			log_message('debug', 'sent \$data array to browser');
		} else {
			redirect('reports/generateSubscriberStatusReport/1');
		}
	}
	public function ajaxGenerateSubscriberPackageReport() {
		if ($this->input->is_ajax_request()) {
			$portal = $this->session->userdata('portal');
			$realm = $portal === 'service' ? $this->session->userdata('realm') : null;
			$service = $this->input->post('service');
			$this->load->model('subscribermain');
			$this->load->model('util');
			$count = $this->subscribermain->countSubscribersWithService($realm, $service);
			$headers = $this->subscribermain->DEFAULT_COLUMNS; //'ACCOUNT TYPE', 'REALM', 'USERNAME', 'ACCOUNT PLAN', 'STATUS', 'SERVICE', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'ORDER NUMBER', 'SERVICE NUMBER', 'CUSTOMER NAME', 'IP ADDRESS', 'NET ADDRESS', 'REMARKS'
			$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];
			$title = 'Subscriber Package Report';
			$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title);
			if ($this->PER > $count) {
				$data = $this->subscribermain->reportSubscribersWithService($realm, $service, 0, $count);
				$this->util->writeSubscriberReportCsv($path, $data);
			} else {
				$loops = intval($count / $this->PER);
				$remaining = $count % $this->PER;
				for ($k = 0; $k < $loops * $this->PER; $k+=$this->PER) {
					$data = $this->subscribermain->reportSubscribersWithService($realm, $service, $k, $this->PER);
					$this->util->writeSubscriberReportCsv($path, $data);
				}
				if ($remaining != 0) {
					$data = $this->subscribermain->reportSubscribersWithService($realm, $service, $loops * $this->PER, $remaining);
					$this->util->writeSubscriberReportCsv($path, $data);
				}
			}
			$parts = explode('/', $path);
			$filename = $parts[count($parts) - 1];
			$data = array('status' => '1', 'filename' => $filename);
			echo json_encode($data);
		} else {
			redirect('reports/generateSubscriberPackageReport/1');
		}
	}
	public function ajaxGenerateSubscriberCreationReport() {
		if ($this->input->is_ajax_request()) {
			$portal = $this->session->userdata('portal');
			$realm = $portal === 'service' ? $this->session->userdata('realm') : null;
			$this->load->model('subscribermain');
			$this->load->model('util');
			$sMonth = $this->input->post('startMonth');
			$sDay = $this->input->post('startDay');
			$sYear = $this->input->post('startYear');
			$eMonth = $this->input->post('endMonth');
			$eDay = $this->input->post('endDay');
			$eYear = $this->input->post('endYear');
			log_message('info', 'dates:'.$sMonth.' '.$sDay.' '.$sYear.' '.$eMonth.' '.$eDay.' '.$eYear);
			$datestart = mktime(0, 0, 0, $sMonth, $sDay, $sYear);
			$dateend = mktime(0, 0, 0, $eMonth, $eDay, $eYear);
			$count = $this->subscribermain->countSubscribersCreatedWithinDates($realm, $datestart, $dateend);
			log_message('info', 'count:'.$count);
			$headers = $this->subscribermain->DEFAULT_COLUMNS; //'ACCOUNT TYPE', 'REALM', 'USERNAME', 'ACCOUNT PLAN', 'STATUS', 'SERVICE', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'ORDER NUMBER', 'SERVICE NUMBER', 'CUSTOMER NAME', 'IP ADDRESS', 'NET ADDRESS', 'REMARKS'
			$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];
			$title = 'Subscriber Creation Report';
			$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title);
			if ($this->PER > $count) {
				$data = $this->subscribermain->reportSubscribersCreatedWithinDates($realm, $datestart, $dateend, 0, $count);
				$this->util->writeSubscriberReportCsv($path, $data);
			} else {
				$loops = intval($count / $this->PER);
				$remaining = $count % $this->PER;
				for ($k = 0; $k < $loops * $this->PER; $k+=$this->PER) {
					$data = $this->subscribermain->reportSubscribersCreatedWithinDates($realm, $datestart, $dateend, $k, $this->PER);
					$this->util->writeSubscriberReportCsv($path, $data);
				}
				if ($remaining != 0) {
					$data = $this->subscribermain->reportSubscribersCreatedWithinDates($realm, $datestart, $dateend, $loops * $this->PER, $remaining);
					$this->util->writeSubscriberReportCsv($path, $data);
				}
			}
			log_message('info', 'path:'.$path);
			$parts = explode('/', $path);
			$filename = $parts[count($parts) - 1];
			$data = array('status' => '1', 'filename' => $filename);
			echo json_encode($data);
		} else {
			redirect('reports/generateSubscriberCreationReport');
		}
	}
	public function ajaxGenerateSubscriberIpReport() {
		if ($this->input->is_ajax_request()) {
			$portal = $this->session->userdata('portal');
			$realm = $portal === 'service' ? $this->session->userdata('realm') : null;
			$this->load->model('subscribermain');
			$this->load->model('util');
			$count = $this->subscribermain->countSubscribersWithStaticIp($realm);
			$headers = $this->subscribermain->DEFAULT_COLUMNS; //'ACCOUNT TYPE', 'REALM', 'USERNAME', 'ACCOUNT PLAN', 'STATUS', 'SERVICE', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'ORDER NUMBER', 'SERVICE NUMBER', 'CUSTOMER NAME', 'IP ADDRESS', 'NET ADDRESS', 'REMARKS'
			//inserting 'HOMING BNG' between 'NET ADDRESS' and 'REMARKS'
			$initialHeaderCount = count($headers);
			$lastHeader = $headers[$initialHeaderCount - 1];
			$headers[$initialHeaderCount - 1] = 'HOMING BNG';
			$headers[] = $lastHeader;
			$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
			$title = 'Subscriber IP Report';
			$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title);
			if ($this->PER > $count) {
				$data = $this->subscribermain->reportSubscribersWithStaticIpV2($realm, 0, $count);
				$this->util->writeSubscriberReportCsvV2($path, $data);
			} else {
				$loops = intval($count / $this->PER);
				$remaining = $count % $this->PER;
				for ($k = 0; $k < $loops * $this->PER; $k+=$this->PER) {
					$data = $this->subscribermain->reportSubscribersWithStaticIpV2($realm, $k, $this->PER);
					$this->util->writeSubscriberReportCsvV2($path, $data);
				}
				if ($remaining != 0) {
					$data = $this->subscribermain->reportSubscribersWithStaticIpV2($realm, $loops * $this->PER, $remaining);
					$this->util->writeSubscriberReportCsvV2($path, $data);
				}
			}
			$parts = explode('/', $path);
			$filename = $parts[count($parts) - 1];
			$data = array('status' => '1', 'filename' => $filename);
			echo json_encode($data);
		} else {
			redirect('reports/generateSubscriberIpReport');
		}
	}
	public function ajaxGenerateSubscriberIpNetReport() {
		if ($this->input->is_ajax_request()) {
			$portal = $this->session->userdata('portal');
			$realm = $portal === 'service' ? $this->session->userdata('realm') : null;
			$this->load->model('subscribermain');
			$this->load->model('util');
			$count = $this->subscribermain->countSubscribersWithStaticIpAndMultistaticIp($realm);
			$headers = $this->subscribermain->DEFAULT_COLUMNS; //'ACCOUNT TYPE', 'REALM', 'USERNAME', 'ACCOUNT PLAN', 'STATUS', 'SERVICE', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'ORDER NUMBER', 'SERVICE NUMBER', 'CUSTOMER NAME', 'IP ADDRESS', 'NET ADDRESS', 'REMARKS'
			//inserting 'HOMING BNG' between 'NET ADDRESS' and 'REMARKS'
			$initialHeaderCount = count($headers);
			$lastHeader = $headers[$initialHeaderCount - 1];
			$headers[$initialHeaderCount - 1] = 'HOMING BNG';
			$headers[] = $lastHeader;
			$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
			$title = 'Subscriber IPNet Report';
			$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title);
			if ($this->PER > $count) {
				$data = $this->subscribermain->reportSubscribersWithStaticIpAndMultistaticIpV2($realm, 0, $count);
				$this->util->writeSubscriberReportCsvV2($path, $data);
			} else {
				$loops = intval($count / $this->PER);
				$remaining = $count % $this->PER;
				for ($k = 0; $k < $loops * $this->PER; $k+=$this->PER) {
					$data = $this->subscribermain->reportSubscribersWithStaticIpAndMultistaticIpV2($realm, $k, $this->PER);
					$this->util->writeSubscriberReportCsvV2($path, $data);
				}
				if ($remaining != 0) {
					$data = $this->subscribermain->reportSubscribersWithStaticIpAndMultistaticIpV2($realm, $loops * $this->PER, $remaining);
					$this->util->writeSubscriberReportCsvV2($path, $data);
				}
			}
			$parts = explode('/', $path);
			$filename = $parts[count($parts) - 1];
			$data = array('status' => '1', 'filename' => $filename);
			echo json_encode($data);
		} else {
			redirect('reports/generateSubscriberIpNetReport');
		}
	}
	public function ajaxGenerateSubscriberCappedReport() {
		if ($this->input->is_ajax_request()) {
			$portal = $this->session->userdata('portal');
			$realm = $portal == 'service' ? $this->session->userdata('realm') : null;
			$this->load->model('subscribermain');
			$this->load->model('util');
			$sMonth = $this->input->post('startMonth');
			$sDay = $this->input->post('startDay');
			$eDay = $this->input->post('endDay');
			$sYear = $this->input->post('startYear');
			$datestart = mktime(0, 0, 0, $sMonth, $sDay, $sYear);
			$dateend = mktime(0, 0, 0, $sMonth, $eDay, $sYear);
			$count = $this->subscribermain->countCappedSubscribers($realm, $datestart, $dateend);
			$headers = ['USERNAME', 'SERVICE', 'UPLOAD DATA', 'DOWNLOAD DATA', 'TOTAL DATA', 'CAP', 'TIMESTAMP'];
			$columns = ['A', 'B', 'C', 'D', 'E', 'F'];
			$title = 'Subscriber Capped Report';
			$path = $this->util->createSubscriberReportCsvEmptyFile($headers, $title);
			if ($this->PER > $count) {
				$data = $this->subscribermain->reportCappedSubscribers($realm, $datestart, $dateend, 0, $count);
				$this->util->writeSubscriberCappedReportCsv($path, $data);
			} else {
				$loops = intval($count / $this->PER);
				$remaining = $count % $this->PER;
				for ($k = 0; $k < $loops * $this->PER; $k+=$this->PER) {
					$data = $this->subscribermain->reportCappedSubscribers($realm, $datestart, $dateend, $k, $this->PER);
					$this->util->writeSubscriberCappedReportCsv($path, $data);
				}
				if ($remaining != 0) {
					$data = $this->subscribermain->reportCappedSubscribers($realm, $datestart, $dateend, $loops * $this->PER, $remaining);
					$this->util->writeSubscriberCappedReportCsv($path, $data);
				}
			}
			$parts = explode('/', $path);
			$filename = $parts[count($parts) - 1];
			$data = array('status' => '1', 'filename' => $filename);
			echo json_encode($data);
		} else {
			redirect('reports/generateSubscriberCappedReport');
		}
	}
	public function ajaxGenerateCabinetReport() {
		if ($this->input->is_ajax_request()) {
			$column = $this->input->post('column');
			$dir = $this->input->post('dir');
			$this->load->model('cabinet');
			$this->load->model('util');
			$count = $this->cabinet->countCabinets();
			$loops = intval($count / $this->PER);
			$remaining = $count % $this->PER;
			log_message('debug', 'loop count: '.$loops.' | remaining: '.$remaining);
			if ($remaining != 0) {
				$loops++;
			}
			log_message('debug', 'updated loop count: '.$loops);
			$title = 'Cabinet Report';
			$headers = array('Cabinet Name', 'Homing BNG', 'Data VLAN');
			$path = $this->util->createCabinetReportCsvEmptyFile($headers, $title);
			for ($k = 0; $k < $loops * $this->PER; $k+=$this->PER) {
				$cabinets = $this->cabinet->getCabinets(null, null, $k, $this->PER, array('column' => $column, 'dir' => $dir));
				$this->util->writeCabinetReportCsv($path, $cabinets);
			}
			$parts = explode('/', $path);
			$filename = $parts[count($parts) - 1];
			$data = array('status' => '1', 'full_path' => base_url().substr($path, strpos($path, 'deletedusers')), 'filename' => $filename);
			echo json_encode($data);
		} else {
			redirect('main/showCabinetsIndex/1');
		}
	}

	public function sendFileToClient($filename) {
		$this->load->model('util');
		$docroot = $_SERVER['DOCUMENT_ROOT'];
		$deletedTempDir = '';
		if (substr($docroot, strlen($docroot) - 1, 1) == '/') {
			$deletedTempDir = $docroot.'deletedusers/tmp/';
		} else {
			$deletedTempDir = $docroot.'/deletedusers/tmp/';
		}
		$path = $deletedTempDir.$filename;
		$this->util->fetchLargeFile($path);
	}

	public function redirectIfNoAccess($page, $path) {
		$session = $this->session->userdata('session');
		$username = $this->session->userdata('username');
		$portal = $this->session->userdata('portal');
		log_message('info', 'session:'.json_encode($session).'|username:'.json_encode($username).'|portal:'.json_encode($portal));
		if ($username != $this->SUPERUSER) {
			$this->load->model('sysusermain');
			$sessionFromDb = $username !== false ? $this->sysusermain->getSysuserSession($username) : null;
			if (is_null($sessionFromDb) || $session != $sessionFromDb) {
				$this->session->set_userdata('logged_out', 'You have been logged out from the system.');
				$this->session->unset_userdata('username');
				redirect('main/noAccess');
			} else {
				$allowedPagesJSON = $this->session->userdata('allowedPages');
				log_message('debug', '                    ');
				log_message('debug', '          allowed pages: '.$allowedPagesJSON);
				log_message('debug', '                    ');
				$allowedPages = json_decode($allowedPagesJSON, true);
				$allowed = false;
				for ($i = 0; $i < count($allowedPages); $i++) {
					$p = $allowedPages[$i];
					if ($p['NM'] == $page) {
						$allowed = true;
						break;
					}
				}
				if (!$allowed) {
					$sysuser = $this->session->userdata('username');
					$sysuserIP = $this->session->userdata('ip_address');

					$this->load->model('sysuseractivitylog');
					$this->sysuseractivitylog->logSysuserIllegalPageAccess($page, $path, $sysuser, $sysuserIP, time());
					redirect('main/noAccess');
				}
			}
		}
	}
}
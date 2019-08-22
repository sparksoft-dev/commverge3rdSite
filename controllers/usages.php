<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usages extends CI_Controller {
	private $SUPERUSER = 'vinadmin';
	private $SUPERGROUP = 'VINADMIN';

	public function __construct(){
	    parent::__construct();
	    $this->load->model('settings');
	    $cfg = $this->settings->loadFromFile();
	    if (!is_null($cfg)) {
	    	$this->SUPERUSER = $cfg['SUPERUSER']; 
	    }
	}

	/***********************************************************************
	 * account usages
	 * PAGEID = 3
	 ***********************************************************************/
	public function showAccountUsagesForm() {
		$this->redirectIfNoAccess('Account Usages', 'usages/showAccountUsagesForm');
		$this->load->model('realm');
		$sMonth = date('m', time());
		$sDay = 1;
		$eDay = date('d', time());
		$sYear = date('Y', time());
		$realms = $this->realm->fetchAllNamesOnly();
		$data = array(
			'init' => true,
			'start_month' => $sMonth,
			'start_day' => $sDay,
			'end_day' => $eDay,
			'start_year' => $sYear,
			'realms' => $realms,
			'allowBlankInRealm' => false,
			'max' => 20);
		$this->load->view('account_usages', $data);
	}
	public function accountUsagesProcess($link = 0, $order = null, $username = null, $realm = null, $startMonth = null, $startDay = null, $endDay = null, $startYear = null,
		$start = 0, $max = 20) {
		$this->redirectIfNoAccess('Account Usages', 'usages/accountUsagesProcess');
		$submit = '';
		if (intval($link) == 0) { //via form
			$username = trim($this->input->post('userid'));
			$realm = $this->input->post('realm');
			$startMonth = $this->input->post('start_month');
			$startDay = $this->input->post('start_day');
			$startYear = $this->input->post('start_year');
			$endDay = $this->input->post('end_day');
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$order = $this->input->post('order');
			$orderParts = explode('-', $order);
			$order = array('column' => $orderParts[0], 'dir' => $orderParts[1]);
			$submit = $this->input->post('submit');
		} else { //via link
			if ($order == null) {
				$order = array('column' => 'timestamp', 'dir' => 'desc');
			} else {
				$parts = explode('-', $order);
				$order = array('column' => $parts[0], 'dir' => $parts[1]);
			}
			$realm = $realm == 'null' ? null : $realm;
			$username = $username == 'null' ? null : $username;
		}
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$sMonth = $startMonth == null ? date('m', time()) : $startMonth;
		$sDay = $startDay == null ? date('d', time()) : $startDay;
		$sYear = $startYear == null ? date('Y', time()) : $startYear;
		$eDay = $endDay == null ? date('d', time()) : $endDay;
		$startdate = mktime(0, 0, 0, $sMonth, $sDay, $sYear);
		$enddate = mktime(0, 0, 0, $sMonth, $eDay + 1, $sYear);
		$this->load->model('usage');
		$usages = $this->usage->fetchUsagesByUsername($username, $realm, $startdate, $enddate - 1, $start, $max, $order);
		$count = $this->usage->countUsagesByUsername($username, $realm, $startdate, $enddate - 1);
		$totalSessionTime = $this->usage->getTotalSessionTimeByUsername($username, $realm, $startdate, $enddate);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		if ($submit == 'extract') {
			//previous utility had no extract function
		} else {
			$pagedata = array(
				'init' => false,
				'usages' => $usages,
				'count' => $count,
				'totalSessionTime' => $totalSessionTime,
				'start' => $start,
				'max' => $max,
				'pages' => $pages,
				'order' => $order,
				'username' => $username,
				'realms' => $realms,
				'realm' => $realm,
				'start_month' => $sMonth,
				'start_day' => $sDay,
				'start_year' => $sYear,
				'end_day' => $eDay);
			$this->load->view('account_usages', $pagedata);
		}
	}
	public function showAccountUsagesForm2() {
		$this->redirectIfNoAccess('Account Usages', 'usages/showAccountusagesForm2');
		$this->load->model('realm');
		$sMonth = date('m', time());
		// Revert 8/5/2019
		$eDay = date('d', time());
		$sDay = intval($eDay) - 7;
		$sDay = $sDay < 1 ? 1 : $sDay;
		$sDay = date('d', time());
		$sYear = date('Y', time());
		$realms = $this->realm->fetchAllNamesOnly();
		$data = array(
			'init' => true,
			'start_month' => $sMonth,
			'start_day' => $sDay,
			'end_day' => $eDay,
			'start_year' => $sYear,
			'realms' => $realms,
			'allowBlankInRealm' => false,
			'max' => 20);
		$this->load->view('account_usages2', $data);
	}
	public function accountUsagesProcess2($link = 0, $order = null, $username = null, $realm = null, $year = null, $month = null, $day = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Account Usages', 'usages/accountUsagesProcess2');
		$submit = '';
		if (intval($link) == 0) { //via form
			$username = trim($this->input->post('userid'));
			$realm = $this->input->post('realm');
			$month = $this->input->post('start_month');
			$day = $this->input->post('start_day');
			// Revert 8/5/2019
			$day2 = $this->input->post('end_day');
			$year = $this->input->post('start_year');
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$order = $this->input->post('order');
			$orderParts = explode('-', $order);
			$order = array('column' => $orderParts[0], 'dir' => $orderParts[1]);
			$submit = $this->input->post('submit');
		} else { //via link
			if (is_null($order)) {
				$order = array('column' => 'ts_min', 'dir' => 'desc');
			} else {
				$parts = explode('-', $order);
				$order = array('column' => $parts[0], 'dir' => $parts[1]);
			}
			$realm = $realm == 'null' ? null : $realm;
			$username = $username == 'null' ? null : $username;
		}
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$sMonth = is_null($month) ? date('m', time()) : $month;
		$sDay = is_null($day) ? date('d', time()) : $day;
		// Revert 8/5/2019
		$eDay = is_null($day2) ? date('d', time()) : $day2;
		$sYear = is_null($year) ? date('Y', time()) : $year;
		$this->load->model('usage');
		$usages = $this->usage->fetchUsagesByUsername2($username, $realm, $sYear, $sMonth, $sDay, $start, $max, $order);
		$count = $this->usage->countUsagesByUsername2($username, $realm, $sYear, $sMonth, $sDay);
		$totalSessionTime = $this->usage->getTotalSessionTimeByUsername2($username, $realm, $sYear, $sMonth, $sDay);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		if ($submit == 'extract') {
			//previous utility had no extract function
		} else {
			$pagedata = array(
				'init' => false,
				'usages' => $usages,
				'count' => $count,
				'totalSessionTime' => $totalSessionTime,
				'start' => $start,
				'max' => $max,
				'pages' => $pages,
				'order' => $order,
				'username' => $username,
				'realms' => $realms,
				'realm' => $realm,
				'start_month' => $sMonth,
				'start_day' => $sDay,
				// Revert 8/5/2019
				'end_day' => $eDay,
				'start_year' => $sYear);
			$this->load->view('account_usages2', $pagedata);
		}
	}
	/***********************************************************************
	 * account transaction
	 * PAGEID = 4
	 ***********************************************************************/
	public function showAccountTransactionForm() {
		$this->redirectIfNoAccess('Account Transaction', 'usages/showAccountTransactionForm');
		$this->load->model('realm');
		$sMonth = date('m', time());
		$sDay = 1;
		$sYear = date('Y', time());
		$eMonth = date('m', time());
		$eDay = date('d', time());
		$eYear = date('Y', time());
		$realms = $this->realm->fetchAllNamesOnly();
		$data = array(
			'init' => true,
			'start_month' => $sMonth,
			'start_day' => $sDay,
			'start_year' => $sYear,
			'end_month' => $eMonth,
			'end_day' => $eDay,
			'end_year' => $eYear,
			'realms' => $realms,
			'allowBlankInRealm' => false,
			'max' => 20);
		$this->load->view('account_transaction', $data);
	}
	public function accountTransactionProcess($link = 0, $username = null, /*$realm = null,*/ $startMonth = null, $startDay = null, $startYear = null,
		$endMonth = null, $endDay = null, $endYear = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Account Transaction', 'usages/accountTransactionProcess');
		$realm = 'dummy';
		$submit = '';
		if (intval($link) == 0) { //via form
			$username = trim($this->input->post('userid'));
			//$realm = $this->input->post('realm');
			$startMonth = $this->input->post('start_month');
			$startDay = $this->input->post('start_day');
			$startYear = $this->input->post('start_yaer');
			$endMonth = $this->input->post('end_month');
			$endDay = $this->input->post('end_day');
			$endYear = $this->input->post('end_year');
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$submit = $this->input->post('submit');
		} else { //via link
			//$realm = $realm == 'null' ? null : $realm;
			$username = $username == 'null' ? null : $username;
		}
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$sMonth = $startMonth == null ? date('m', time()) : $startMonth;
		$sDay = $startDay == null ? 1 : $startDay;
		$sYear = $startYear == null ? date('Y', time()) : $startYear;
		$eMonth = $endMonth == null ? date('m', time()) : $endMonth;
		$eDay = $endDay == null ? date('d', time()) : $endDay;
		$eYear = $endYear == null ? date('Y', time()) : $endYear;
		$startdate = mktime(0, 0, 0, $sMonth, $sDay, $sYear);
		$enddate = mktime(0, 0, 0, $eMonth, $eDay, $eYear);
		$this->load->model('usage');
		$transactions = $this->usage->fetchAccountTransactions($username, $realm, $startdate, $enddate, $start, $max);
		$count = $this->usage->countAccountTransactions($username, $realm, $startdate, $enddate);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		if ($submit == 'extract') {
			//previous utility had no extract function
		} else {
			$pagedata = array(
				'init' => false,
				'transactions' => $transactions,
				'count' => $count,
				'start' => $start,
				'max' => $max,
				'pages' => $pages,
				'username' => $username,
				'realms' => $realms,
				//'realm' => $realm,
				'allowBlankInRealm' => false,
				'start_month' => $sMonth,
				'start_day' => $sDay,
				'start_year' => $sYear,
				'end_month' => $eMonth,
				'end_day' => $eDay,
				'end_year' => $eYear);
			$this->load->view('account_transaction', $pagedata);
		}
	}
	/***********************************************************************
	 * usages
	 * PAGEID = 6
	 ***********************************************************************/
	public function showUsagesForm() {
		$this->redirectIfNoAccess('Usages', 'usages/showUsagesForm');
		//$this->load->view('under_construction');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$sMonth = date('m', time());
		$sDay = 1;
		$eDay = date('d', time());
		$sYear = date('Y', time());
		$data = array(
			'init' => true,
			'realms' => $realms,
			'allowBlankInRealm' => false,
			'portal' => $portal,
			'start_month' => $sMonth,
			'start_day' => $sDay,
			'start_year' => $sYear,
			'max' => 20);
		if ($portal == 'service') {
			$data['realm'] = $realm;
			$data['disableRealm'] = true;
			$data['hideDropdown'] = true;
		}
		$this->load->view('usages', $data);
	}
	public function usagesProcess($link = 0, $realm = null, $summaryType = null, $startMonth = null, $startDay = null, $startYear = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Usages', 'usages/usagesProcess');
		$submit = '';
		$portal = $this->session->userdata('portal');
		if (intval($link) == 0) { //via form
			if ($portal == 'service') {
				$realm = $this->session->userdata('realm');
			} else {
				$choice = $this->input->post('choice'); // if choice == 1, set realm = null 
				$realm = intval($choice) == 1 ? null : $this->input->post('realm');
			}
			$summaryType = $this->input->post('group');
			$satype = $this->input->post('satype');
			$startMonth = $this->input->post('start_month');
			$startDay = $this->input->post('start_day');
			$startYear = $this->input->post('start_year');
			$start = $this->input->post('start');
			$max = $this->input->post('max');
		} else { //via link
			$realm = $realm == 'null' ? null : $realm;
		}
		$sMonth = $startMonth == null ? date('m', time()) : $startMonth;
		$sDay = $startDay == null ? date('d', time()) : $startDay;
		$sYear = $startYear == null ? date('Y', time()) : $startYear;
		$startdate = mktime(0, 0, 0, $sMonth, $sDay, $sYear);
		$enddate = mktime(0, 0, 0, $sMonth, $sDay + 1, $sYear);
		log_message('info', date('Y-m-d H:i:s', $startdate).' - '.date('Y-m-d H:i:s', $enddate));
		log_message('info', $startdate.' - '.$enddate);
		$this->load->model('usage');
		$usages = $this->usage->fetchUsageBySummary($realm, intval($summaryType), $startdate, $enddate, $start, $max);
		$count = $this->usage->countUsageBySummary($realm, intval($summaryType), $startdate, $enddate);
		$totalSessionTime = $this->usage->getTotalSessionTimeBySummary($realm, intval($summaryType), $startdate, $enddate - 1);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		if ($submit == 'extract') {
			//previous utility had no extract function
		} else {
			$this->load->model('realm');
			$realms = $this->realm->fetchAllNamesOnly();
			$pagedata = array(
				'init' => false,
				'usages' => $usages,
				'count' => $count,
				'totalSessionTime' => $totalSessionTime,
				'start' => $start,
				'max' => $max,
				'pages' => $pages,
				'start_month' => $sMonth,
				'start_day' => $sDay,
				'start_year' => $sYear,
				'realm' => $realm,
				'realms' => $realms,
				'allowBlankInRealm' => false,
				'portal' => $portal,
				'summaryType' => $summaryType);
			if ($portal == 'service') {
				$pagedata['realm'] = $realm;
				$pagedata['disableRealm'] = true;
				$pagedata['hideDropdown'] = true;
			}
			$this->load->view('usages', $pagedata);
		}
	}
	public function showUsagesForm2() {
		$this->redirectIfNoAccess('Usages', 'usages/showUsagesForm2');
		$portal = $this->session->userdata('portal');
		$realm = $this->session->userdata('realm');
		$this->load->model('realm');
		$realms = $this->realm->fetchAllNamesOnly();
		$sMonth = date('m', time());
		$sDay = date('d', time());
		$sYear = date('Y', time());
		$sHour = date('H', time());
		$data = array(
			'init' => true,
			'realms' => $realms,
			'allowBlankInRealm' => false,
			'portal' => $portal,
			'start_month' => $sMonth,
			'start_day' => $sDay,
			'start_year' => $sYear,
			'start_hour' => $sHour,
			'max' => 20);
		if ($portal == 'service') {
			$data['realm'] = $realm;
			$data['disableRealm'] = true;
			$data['hideDropdown'] = true;
		}
		$this->load->view('usages2', $data);
	}
	public function usagesProcess2($link = 0, $realm = null, $summaryType = null, $year = null, $month = null, $day = null, $hour = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Usages', 'usages/usagesProcess2');
		$submit = '';
		$portal = $this->session->userdata('portal');
		if (intval($link) == 0) { //via form
			if ($portal == 'service') {
				$realm = $this->session->userdata('realm');
			} else {
				$choice = $this->input->post('choice'); // if choice == 1, set realm = null 
				$realm = intval($choice) == 1 ? null : $this->input->post('realm');
			}
			$summaryType = $this->input->post('group');
			$satype = $this->input->post('satype');
			$month = $this->input->post('start_month');
			$day = $this->input->post('start_day');
			$year = $this->input->post('start_year');
			$hour = $this->input->post('start_hour');
			$start = $this->input->post('start');
			$max = $this->input->post('max');
		} else { //via link
			$realm = $realm == 'null' ? null : $realm;
		}
		$sMonth = is_null($month) ? date('m', time()) : $month;
		$sDay = is_null($day) ? date('d', time()) : $day;
		$sYear = is_null($year) ? date('Y', time()) : $year;
		$sHour = is_null($hour) ? date('H', time()) : $hour;
		$this->load->model('usage');
		$idMin = $this->usage->getMinId($realm, $sYear, $month, $day, $hour);
		$idMax = $this->usage->getMaxId($realm, $sYear, $month, $day, $hour);
		$usages = $this->usage->fetchUsageBySummary2($idMin, $idMax, intval($summaryType), $start, $max);
		$count = $this->usage->countUsageBySummary2($idMin, $idMax);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		if ($submit == 'extract') {
			//previous utility had no extract function
		} else {
			$this->load->model('realm');
			$realms = $this->realm->fetchAllNamesOnly();
			$pagedata = array(
				'init' => false,
				'usages' => $usages,
				'count' => $count,
				// 'totalSessionTime' => $totalSessionTime,
				'start' => $start,
				'max' => $max,
				'pages' => $pages,
				'start_month' => $sMonth,
				'start_day' => $sDay,
				'start_year' => $sYear,
				'start_hour' => $sHour,
				'realm' => $realm,
				'realms' => $realms,
				'allowBlankInRealm' => false,
				'portal' => $portal,
				'summaryType' => $summaryType);
			if ($portal == 'service') {
				$pagedata['realm'] = $realm;
				$pagedata['disableRealm'] = true;
				$pagedata['hideDropdown'] = true;
			}
			$this->load->view('usages2', $pagedata);
		}
	}
	/***********************************************************************
	 * authentication log
	 * PAGEID = 9
	 ***********************************************************************/
	public function showAuthenticationLogForm() {
		$this->redirectIfNoAccess('Authentication Log', 'usages/showAuthenticationLogForm');
		$allowedRealms = $this->session->userdata('allowedRealms');
		$allowedRealms = json_decode($allowedRealms, true);
		$realms = array();
		for ($i = 0; $i < count($allowedRealms); $i++) {
			if (is_null($allowedRealms[$i]['NM'])) {
				continue;
			}
			$realms[] = $allowedRealms[$i]['NM'];
		}
		log_message('info', '----------'.json_encode($realms));
		$month = date('m', time());
		$day = date('d', time());
		$year = date('Y', time());
		$data = array(
			'init' => true,
			'month' => $month,
			'day' => $day,
			'year' => $year,
			'realms' => $realms,
			'allowBlankInRealm' => false,
			'max' => 20);
		$this->load->view('authentication_log', $data);
	}
	public function authenticationLogProcess($link = 0, $username = null, $realm = null, $month = null, $day = null, $year = null, $hour = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Authentication Log', 'usages/authenticationLogProcess');
		$allowedRealms = $this->session->userdata('allowedRealms');
		$allowedRealms = json_decode($allowedRealms, true);
		$realms = array();
		for ($i = 0; $i < count($allowedRealms); $i++) {
			$realms[] = $allowedRealms[$i]['NM'];
		}
		$submit = '';
		if (intval($link) == 0) { //via form
			$username = trim($this->input->post('userid'));
			$realm = $this->input->post('realm');
			$month = $this->input->post('month');
			$day = $this->input->post('day');
			$year = $this->input->post('year');
			$hour = trim($this->input->post('hour'));
			$start = 0;//$this->input->post('start');
			$max = $this->input->post('max');
			$submit = $this->input->post('submit');
		} else { //via link
			$username = $username == 'null' ? null : $username;
			$realm = $realm == 'null' ? null : $realm;
			$hour = $hour == 'null' ? null : $hour;
		}
		$this->load->model('usage');
		$authLogs = $this->usage->fetchAuthenticationLogs($username, $realm, $month, $day, $year, $hour, $start, $max);
		$count = $this->usage->countAuthenticationLogs($username, $realm, $month, $day, $year, $hour);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		if ($submit == 'etract') {

		} else {
			$pagedata = array(
				'init' => false,
				'authLogs' => $authLogs,
				'hour' => $hour,
				'count' => $count,
				'pages' => $pages,
				'start' => $start,
				'max' => $max,
				'username' => $username,
				'realm' => $realm,
				'realms' => $realms,
				'allowBlankInRealm' => false,
				'month' => $month,
				'day' => $day,
				'year' => $year);
			$this->load->view('authentication_log', $pagedata);
		}
	}
	/***********************************************************************
	 * usage by ip address
	 * PAGEID = 7
	 ***********************************************************************/
	public function showUsageByIpAddressForm() {
		$this->redirectIfNoAccess('Usage by IP Address', 'usages/showUsageByIpAddressForm');
		$sMonth = date('m', time());
		$sDay = 1;
		$eDay = date('d', time());
		$sYear = date('Y', time());
		$data = array(
			'init' => true,
			'start_month' => $sMonth,
			'start_day' => $sDay,
			'end_day' => $eDay,
			'start_year' => $sYear,
			'max' => 20);
		$this->load->view('usage_by_ip_address', $data);
	}
	public function usageByIpAddressProcess($link = 0, $order = null, $ipaddress = null, $startMonth = null, $startDay = null, $endDay = null, $startYear = null,
		$start = 0, $max = 20) {
		$this->redirectIfNoAccess('Usage by IP Address', 'usages/usageByIpAddressProcess');
		$submit = '';
		if (intval($link) == 0) { //via form
			$ipaddress = trim($this->input->post('str_ip'));
			$startMonth = $this->input->post('start_month');
			$startDay = $this->input->post('start_day');
			$startYear = $this->input->post('start_year');
			$endDay = $this->input->post('end_day');
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$order = $this->input->post('order');
			$orderParts = explode('-', $order);
			$order = array('column' => $orderParts[0], 'dir' => $orderParts[1]);
			$submit = $this->input->post('submit');
		} else { //via link
			if ($order == null) {
				$order = array('column' => 'timestamp', 'dir' => 'desc');
			} else {
				$parts = explode('-', $order);
				$order = array('column' => $parts[0], 'dir' => $parts[1]);
			}
			$ipaddress = $ipaddress == 'null' ? null : $ipaddress;
		}
		$sMonth = $startMonth == null ? date('m', time()) : $startMonth;
		$sDay = $startDay == null ? date('d', time()) : $startDay;
		$sYear = $startYear == null ? date('Y', time()) : $startYear;
		$eDay = $endDay == null ? date('d', time()) : $endDay;
		$startdate = mktime(0, 0, 0, $sMonth, $sDay, $sYear);
		$enddate = mktime(0, 0, 0, $sMonth, $eDay + 1, $sYear);
		$this->load->model('usage');
		$usages = $this->usage->fetchUsageByIPAddress($ipaddress, $startdate, $enddate - 1, $start, $max, $order);
		$count = $this->usage->countUsagesByIPAddress($ipaddress, $startdate, $enddate - 1);
		$totalSessionTime = $this->usage->getTotalSessionTimeByIPAddress($ipaddress, $startdate, $enddate);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		if ($submit == 'extract') {
			//previous utility had no extract function
		} else {
			$pagedata = array(
				'init' => false,
				'usages' => $usages,
				'count' => $count,
				'totalSessionTime' => $totalSessionTime,
				'start' => $start,
				'max' => $max,
				'pages' => $pages,
				'order' => $order,
				'ipaddress' => $ipaddress,
				'start_month' => $sMonth,
				'start_day' => $sDay,
				'start_year' => $sYear,
				'end_day' => $eDay);
			$this->load->view('usage_by_ip_address', $pagedata);
		}
	}
	public function showUsageByIpAddressForm2() {
		$this->redirectIfNoAccess('Usage by IP Address', 'usages/showUsageByIpAddressForm2');
		$sMonth = date('m', time());
		// $eDay = date('d', time());
		// $sDay = intval($eDay) - 7;
		// $sDay = $sDay < 1 ? 1 : $sDay;
		$sDay = date('d', time());
		$sYear = date('Y', time());
		$data = array(
			'init' => true,
			'start_month' => $sMonth,
			'start_day' => $sDay,
			// 'end_day' => $eDay,
			'start_year' => $sYear,
			'max' => 20);
		$this->load->view('usage_by_ip_address2', $data);
	}
	public function usageByIpAddressProcess2($link = 0, $order = null, $ipaddress = null, $year = null, $month = null, $day = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('Usage by IP Address', 'usages/usageByIpAddressProcess2');
		$submit = '';
		if (intval($link) == 0) { //via form
			$ipaddress = trim($this->input->post('str_ip'));
			$year = $this->input->post('start_year');
			$month = $this->input->post('start_month');
			$day = $this->input->post('start_day');
			// $day2 = $this->input->post('end_day');
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$order = $this->input->post('order');
			$orderParts = explode('-', $order);
			$order = array('column' => $orderParts[0], 'dir' => $orderParts[1]);
			$submit = $this->input->post('submit');
		} else { //via link
			if (is_null($order)) {
				$order = array('column' => 'ts_min', 'dir' => 'desc');
			} else {
				$parts = explode('-', $order);
				$order = array('column' => $parts[0], 'dir' => $parts[1]);
			}
			$ipaddress = $ipaddress == 'null' ? null : $ipaddress;
		}
		$sMonth = is_null($month) ? date('m', time()) : $month;
		$sDay = is_null($day) ? date('d', time()) : $day;
		// $eDay = is_null($day2) ? date('d', time()) : $day2;
		$sYear = is_null($year) ? date('Y', time()) : $year;
		$this->load->model('usage');
		$usages = $this->usage->fetchUsageByIPAddress2($ipaddress, $sYear, $sMonth, $sDay, $start, $max, $order);
		$count = $this->usage->countUsagesByIPAddress2($ipaddress, $sYear, $sMonth, $sDay);
		$totalSessionTime = $this->usage->getTotalSessionTimeByIPAddress2($ipaddress, $sYear, $sMonth, $sDay);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		if ($submit == 'extract') {
			//previous utility had no extract function
		} else {
			$pagedata = array(
				'init' => false,
				'usages' => $usages,
				'count' => $count,
				'totalSessionTime' => $totalSessionTime,
				'start' => $start,
				'max' => $max,
				'pages' => $pages,
				'order' => $order,
				'ipaddress' => $ipaddress,
				'start_month' => $sMonth,
				'start_day' => $sDay,
				// 'end_day' => $eDay,
				'start_year' => $sYear);
			$this->load->view('usage_by_ip_address2', $pagedata);
		}
	}
	/***********************************************************************
	 * system user account usages
	 * PAGEID = 29
	 ***********************************************************************/
	public function searchSysuserUsagesByIpAddressForm() {
		$this->redirectIfNoAccess('System Account Usages', 'usages/searchSysuserUsagesByIpAddressForm');
		$data = array(
			'init' => true,
			'max' => 20);
		$this->load->view('sysuser_usages_search_by_ip_address', $data);
	}
	public function searchSysuserUsagesByIpAddressProcess($link = 0, $ipaddress = null, $filter = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('System Account Usages', 'usages/searchSysuserUsagesByIpAddressProcess');
		if (intval($link) == 0) { //via form
			$ipaddress = trim($this->input->post('ipaddress'));
			$filter = $this->input->post('filter');
			$start = $this->input->post('start');
			$max = $this->input->post('max');
		} else { //via link
			$ipaddress = $ipaddress == 'null' ? null : $ipaddress;
			$filter = $filter == 'null' ? null : $filter;
		}
		$this->load->model('sysuser');
		$usages = null;
		$count = 0;
		$totalDuration = 0;
		$label = '';
		if (!is_null($filter) && $filter == 'online') {
			$usages = $this->sysuser->findActiveSysuserUsagesByIpAddress($ipaddress, $start, $max);
			$count = $this->sysuser->countActiveSysuserUsagesByIpAddress($ipaddress);
			$totalDuration = $this->sysuser->sumSesstimeOfActiveSysuserUsagesByIpAddress($ipaddress);
			$label = 'Online Users';
		} else if (!is_null($filter) && $filter == 'offline') {
			$usages = $this->sysuser->findInactiveSysuserUsagesByIpAddress($ipaddress, $start, $max);
			$count = $this->sysuser->countInactiveSysuserUsagesByIpAddress($ipaddress);
			$totalDuration = $this->sysuser->sumSesstimeOfInactiveSysuserUsagesByIpAddress($ipaddress);
			$label = 'Offline Users';
		} else {
			$usages = $this->sysuser->findSysuserUsagesByIpAddress($ipaddress, $start, $max);
			$count = $this->sysuser->countSysuserUsagesByIpAddress($ipaddress);
			$totalDuration = $this->sysuser->sumSesstimeOfSysuserUsagesByIpAddress($ipaddress);
			$label = 'All Users';
		}
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		$pagedata = array(
			'init' => false,
			'usages' => $usages,
			'count' => $count,
			'pages' => $pages,
			'label' => $label,
			'totalDuration' => $totalDuration,
			'ipaddress' => $ipaddress,
			'filter' => $filter,
			'start' => $start,
			'max' => $max);
		$this->load->view('sysuser_usages_search_by_ip_address', $pagedata);
	}
	public function searchSysuserUsagesByLoginDateForm() {
		$this->redirectIfNoAccess('System Account Usages', 'usages/searchSysuserUsagesByLoginDateForm');
		$sMonth = date('m', time());
		$sDay = 1;
		$eDay = date('d', time());
		$sYear = date('Y', time());
		$data = array(
			'init' => true,
			'start_month' => $sMonth,
			'start_day' => $sDay,
			'end_day' => $eDay,
			'start_year' => $sYear,
			'max' => 20);
		$this->load->view('sysuser_usages_search_by_login_date', $data);
	}
	public function searchSysuserUsagesByLoginDateProcess($link = 0, $startMonth = null, $startDay = null, $endDay = null, $startYear = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('System Account Usages', 'usages/searchSysuserUsagesByLoginDateProcess');
		if (intval($link) == 0) { //via form
			$startMonth = $this->input->post('start_month');
			$startDay = $this->input->post('start_day');
			$startYear = $this->input->post('start_year');
			$endDay = $this->input->post('end_day');
			$start = $this->input->post('start');
			$max = $this->input->post('max');
		} else { //via link

		}
		$sMonth = $startMonth == null ? date('m', time()) : $startMonth;
		$sDay = $startDay == null ? date('d', time()) : $startDay;
		$sYear = $startYear == null ? date('Y', time()) : $startYear;
		$eDay = $endDay == null ? date('d', time()) : $endDay;
		$startdate = mktime(0, 0, 0, $sMonth, $sDay, $sYear);
		$enddate = mktime(0, 0, 0, $sMonth, $eDay, $sYear);
		$this->load->model('sysuser');
		$usages = $this->sysuser->findSysuserUsagesByLoginDate($sYear, $sMonth, $sDay, $eDay, $start, $max);
		$count = $this->sysuser->countSysuserUsagesByLoginDate($sYear, $sMonth, $sDay, $eDay);
		$totalDuration = $this->sysuser->sumSesstimeOfSysuserUsagesByLoginDate($sYear, $sMonth, $sDay, $eDay);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		$pagedata = array(
			'init' => false,
			'usages' => $usages,
			'count' => $count,
			'pages' => $pages,
			'totalDuration' => $totalDuration,
			'start_month' => $sMonth,
			'start_day' => $sDay,
			'start_year' => $sYear,
			'end_day' => $eDay,
			'start' => $start,
			'max' => $max);
		$this->load->view('sysuser_usages_search_by_login_date', $pagedata);
	}
	public function searchSysuserUsagesLoggedInAtForm() {
		$this->redirectIfNoAccess('System Account Usages', 'usages/searchSysuserUsagesLoggedInAtForm');
		$sMonth = date('m', time());
		$sDay = date('d', time());
		$sYear = date('Y', time());
		$data = array(
			'init' => true,
			'start_month' => $sMonth,
			'start_day' => $sDay,
			'start_year' => $sYear,
			'max' => 20);
		$this->load->view('sysuser_usages_search_logged_in_at', $data);
	}
	public function searchSysuserUsagesLoggedInAtProcess($link = 0, $startMonth = null, $startDay = null, $startYear = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('System Account Usages', 'usages/searchSysuserUsagesLoggedInAtProcess');
		if (intval($link) == 0) { //via form
			$startMonth = $this->input->post('start_month');
			$startDay = $this->input->post('start_day');
			$startYear = $this->input->post('start_year');
			$start = $this->input->post('start');
			$max = $this->input->post('max');
		} else { //via link

		}
		$sMonth = $startMonth == null ? date('m', time()) : $startMonth;
		$sDay = $startDay == null ? date('d', time()) : $startDay;
		$sYear = $startYear == null ? date('Y', time()) : $startYear;
		$this->load->model('sysuser');
		$usages = $this->sysuser->findSysuserUsagesLoggedInAtTime($sYear, $sMonth, $sDay, $start, $max);
		$count = $this->sysuser->countSysuserUsagesLoggedInAtTime($sYear, $sMonth, $sDay);
		$totalDuration = $this->sysuser->sumSesstimeOfSysuserUsagesLoggedInAtTime($sYear, $sMonth, $sDay);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		$pagedata = array(
			'init' => false,
			'usages' => $usages,
			'count' => $count,
			'pages' => $pages,
			'totalDuration' => $totalDuration,
			'start_month' => $sMonth,
			'start_day' => $sDay,
			'start_year' => $sYear,
			'start' => $start,
			'max' => $max);
		$this->load->view('sysuser_usages_search_logged_in_at', $pagedata);
	}
	public function searchSysuserLoggedInHistoricalCountForm() {
		$this->redirectIfNoAccess('System Account Usages', 'usages/searchSysuserLoggedInHistoricalCountForm');
		$sMonth = date('m', time());
		$sDay = 1;
		$sYear = date('Y', time());
		$eMonth = date('m', time());
		$eDay = date('d', time());
		$eYear = date('Y', time());
		$data = array(
			'init' => true,
			'start_month' => $sMonth,
			'start_day' => $sDay,
			'start_year' => $sYear,
			'end_month' => $eMonth,
			'end_day' => $eDay,
			'end_year' => $eYear);
		$this->load->view('sysuser_usages_historical_count', $data);
	}
	public function searchSysuserLoggedInHistoricalCountProcess($link = 0, $hostname = null, $threshold = 0, $startMonth = null, $startDay = null, $startYear = null,
		$endMonth = null, $endDay = null, $endYear = null, $currentMonth = null, $currentDay = null, $currentYear = null) {
		$this->redirectIfNoAccess('System Account Usages', 'usages/searchSysuserLoggedInHistoricalCountProcess');
		$submit = '';
		if (intval($link) == 0) { //via form
			$hostname = trim($this->input->post('hostname'));
			$hostname = $hostname == '' ? null : $hostname;
			$startMonth = $this->input->post('start_month');
			$startDay = $this->input->post('start_day');
			$startYear = $this->input->post('start_year');
			$endMonth = $this->input->post('end_month');
			$endDay = $this->input->post('end_day');
			$endYear = $this->input->post('end_year');
			$currentMonth = $this->input->post('current_month');
			$currentDay = $this->input->post('current_day');
			$currentYear = $this->input->post('current_year');
			$currentMonth = is_null($currentMonth) || $currentMonth === false || $currentMonth == '' ? $startMonth : $currentMonth;
			$currentDay = is_null($currentDay) || $currentDay === false || $currentDay == '' ? $startDay : $currentDay;
			$currentYear = is_null($currentYear) || $currentYear === false || $currentYear == '' ? $startYear : $currentYear;
			$threshold = $this->input->post('threshold');
			$threshold = is_null($threshold) || $threshold === false || $threshold == '' ? null : trim($threshold);
			$submit = $this->input->post('submit');
		} else { //via link
			$hostname = $hostname == 'null' ? null : $hostname;
			$threshold = $threshold = 'null' ? null : $threshold;
		}
		$this->load->model('sysuser');
		$dayCounts = $this->sysuser->getLoggedInCountsInDay($currentDay, $currentMonth, $currentYear, $hostname);

		$formatted = array();
		if (count($dayCounts) != 0) {
			for ($i = 0; $i < 24; $i++) {
				$found = true;
				$index = 0;
				for ($j = 0; $j < count($dayCounts); $j++) {
					$row = $dayCounts[$j];
					if ($i == intval($row['hour'])) {
						$index = $j;
						$found = true;
						break;
					} else {
						$found = false;
					}
				}
				if (!$found) {
					$formatted[] = array('hostname' => $dayCounts[0]['hostname'], 'date' => $dayCounts[0]['date'], 'day' => $dayCounts[0]['day'], 'month' => $dayCounts[0]['month'], 
						'year' => $dayCounts[0]['year'], 'hour' => strval($i), 'count' => '0');		
				} else {
					$formatted[] = array(
						'hostname' => $dayCounts[$index]['hostname'], 'date' => $dayCounts[$index]['date'], 'day' => $dayCounts[$index]['day'], 'month' => $dayCounts[$index]['month'], 
						'year' => $dayCounts[$index]['year'], 'hour' => strval($dayCounts[$index]['hour']), 'count' => $dayCounts[$index]['count']);
				}
			}
		}
		if ($submit == 'extract') {
			$this->load->model('util');
			$this->util->writeToLoggedInHistoricalCount($formatted);
		} else {
			$pagedata = array(
				'init' => false,
				'counts' => $formatted,
				'hostname' => $hostname,
				'threshold' => $threshold,
				'start_month' => $startMonth,
				'start_day' => $startDay,
				'start_year' => $startYear,
				'end_month' => $endMonth,
				'end_day' => $endDay,
				'end_year' => $endYear,
				'current_month' => $currentMonth,
				'current_day' => $currentDay,
				'current_year' => $currentYear);
			$this->load->view('sysuser_usages_historical_count', $pagedata);	
		}
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
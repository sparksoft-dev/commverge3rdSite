<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activitylogs extends CI_Controller {
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
	 * activity logs
	 * PAGEID = 28
	 ***********************************************************************/
	public function showActivityLogsMainPage() {
		$this->redirectIfNoAccess('System User Activity Logs', 'activitylogs/showActivityLogsMainPage');
		$this->load->view('sysuser_activity_logs');
	}
	public function showSubscriberAuditTrailForm() {
		$this->redirectIfNoAccess('System User Activity Logs', 'activitylogs/showSubscriberAuditTrailForm');
		$this->load->model('subscriberaudittrail');
		$this->load->model('tbldslbucket');
		$allowedRealms = $this->tbldslbucket->fetchAllRealmsForSUNamesOnly();
		$sMonth = date('m', mktime());
		$sYear = date('Y', mktime());
		$sDay = date('d', mktime());
		$eMonth = date('m', mktime());
		$eYear = date('Y', mktime());
		$eDay = date('d', mktime());
		$data = array(
			'init' => true,
			'start_month' => $sMonth,
			'start_year' => $sYear,
			'start_day' => $sDay,
			'end_month' => $eMonth,
			'end_year' => $eYear,
			'end_day' => $eDay,
			'max' => 20,
			'realms' => $allowedRealms,
			'error' => '');
		$this->load->view('subscriber_audit_trail', $data);
	}
	public function showSubscriberAuditTrail($link = 0, $order = null, $sysuser = null, $ipaddress = null, $username = null, $realm = null, $action = null,
		$startMonth = null, $startYear = null, $startDay = null, $endMonth = null, $endYear = null, $endDay = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('System User Activity Logs', 'activitylogs/showSubscriberAuditTrail');
		$this->load->model('tbldslbucket');
		$allowedRealms = $this->tbldslbucket->fetchAllRealmsForSUNamesOnly();
		$submit = '';
		if (intval($link) == 0) { //via form
			$sysuser = trim($this->input->post('sysuser'));
			$ipaddress = trim($this->input->post('ipaddress'));
			$username = trim($this->input->post('userid'));
			$realm = trim($this->input->post('realm'));
			$action = trim($this->input->post('action'));
			$sysuser = $sysuser == '' ? null : $sysuser;
			$ipaddress = $ipaddress == '' ? null : $ipaddress;
			$username = $username == '' ? null : $username;
			$realm = $realm == '' ? null : $realm;
			$action = $action == '' ? null : $action;
			$username = ($username != null && strlen($username) > 0 && $realm != null && strlen($realm) > 0) ? $username.'@'.$realm : null;
			$startMonth = $this->input->post('start_month');
			$startYear = $this->input->post('start_year');
			$startDay = $this->input->post('start_day');
			$endMonth = $this->input->post('end_month');
			$endYear = $this->input->post('end_year');
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
			$sysuser = $sysuser == 'null' ? null : str_replace('_', ' ', $sysuser);
			$ipaddress = $ipaddress == 'null' ? null : str_replace('_', ' ', $ipaddress);
			$username = $username == 'null' ? null : str_replace('_', ' ', $username);
			$action = $action == 'null' ? null : str_replace('_', ' ', $action);
			$realm = $realm == 'null' ? null : str_replace('_', ' ', $realm);
			$username = ($username != null && strlen($username) > 0 && $realm != null && strlen($realm) > 0) ? $username.'@'.$realm : null;
		}
		$sMonth = $startMonth == null ? date('m', mktime()) : $startMonth;
		$sDay = $startDay == null ? date('d', mktime()) : $startDay;
		$sYear = $startYear == null ? date('Y', mktime()) : $startYear;
		$startdate = mktime(0, 0, 0, $sMonth, $sDay, $sYear);
		$eMonth = $endMonth == null ? date('m', mktime()) : $endMonth;
		$eDay = $endDay == null ? date('d', mktime()) : $endDay;
		$eYear = $endYear == null ? date('Y', mktime()) : $endYear;
		$enddate = mktime(0, 0, 0, $eMonth, $eDay, $eYear);
		$actionFilter = array(
			'C' => 'Create',
			'U' => 'Modify',
			'R' => 'Reset password',
			'P' => 'Change password',
			'D' => 'Delete session');
		$this->load->model('subscriberaudittrail');
		$audits = $this->subscriberaudittrail->findAllSubscriberAuditTrailWithParam($startdate, $enddate, $sysuser, $action, $username, $ipaddress, $start, $max, $order);
		$count = $this->subscriberaudittrail->countSubscriberAuditTrailsWithParam($startdate, $enddate, $sysuser, $action, $username, $ipaddress);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		if ($submit == 'extract') { //create excel file
			try {
				$this->load->model('util');
				$headers = $this->subscriberaudittrail->DEFAULT_COLUMNS;//"SUBSCRIBER", "ACTION", "INFO", "SYSTEM USER", "IP ADDRESS", "TIMESTAMP"
				$columns = ['A', 'B', 'C', 'D', 'E', 'F'];
				$title = 'Subscriber Audit Trail';
				$this->util->writeSubscriberAuditTrail($audits, $headers, $columns, $title);
			} catch (Exception $e) {
				if ($username != null) {
					$usernameParts = explode('@', $username);
					$username = $usernameParts[0];
				}
				$pagedata = array(
					'init' => false,
					'audits' => $audits,
					'count' => $count,
					'start' => $start,
					'max' => $max,
					'pages' => $pages,
					'order' => $order,
					'sysuser' => $sysuser,
					'ipaddress' => $ipaddress,
					'action' => $action,
					'actionFilter' => $actionFilter,
					'realm' => $realm,
					'realms' => $allowedRealms,
					'username' => $username,
					'start_month' => $sMonth,
					'start_year' => $sYear,
					'start_day' => $sDay,
					'end_month' => $eMonth,
					'end_year' => $eYear,
					'end_day'=> $eDay,
					'error' => 'Error extracting entries.');
				$this->load->view('subscriber_audit_trail', $pagedata);
			}
		} else {
			if ($username != null) {
				$usernameParts = explode('@', $username);
				$username = $usernameParts[0];
			}
			$pagedata = array(
				'init' => false,
				'audits' => $audits,
				'count' => $count,
				'start' => $start,
				'max' => $max,
				'pages' => $pages,
				'order' => $order,
				'sysuser' => $sysuser,
				'ipaddress' => $ipaddress,
				'action' => $action,
				'actionFilter' => $actionFilter,
				'realm' => $realm,
				'realms' => $allowedRealms,
				'username' => $username,
				'start_month' => $sMonth,
				'start_year' => $sYear,
				'start_day' => $sDay,
				'end_month' => $eMonth,
				'end_year' => $eYear,
				'end_day'=> $eDay,
				'error' => '');
			$this->load->view('subscriber_audit_trail', $pagedata);
		}
	}
	public function showFailedSysuserLoginAttemptsForm() {
		$this->redirectIfNoAccess('System User Activity Logs', 'activitylogs/showFailedSysuserLoginAttemptsForm');
		$this->load->model('sysuser');
		$sMonth = date('m', mktime());
		$sYear = date('Y', mktime());
		$sDay = date('d', mktime());
		$eMonth = date('m', mktime());
		$eYear = date('Y', mktime());
		$eDay = date('d', mktime());
		$reasonValueFilter = [
			$this->sysuser->ERROR_INCORRECT_PASSWORD,
			$this->sysuser->ERROR_USER_BLOCKED,
			$this->sysuser->ERROR_USER_NOACCESS_DAY,
			$this->sysuser->ERROR_USER_NOACCESS_TIME,
			$this->sysuser->ERROR_USER_DOES_NOT_EXIST,
			$this->sysuser->ERROR_USER_NOACCESS_REALM,
			$this->sysuser->ERROR_USER_NOACCESS_IPADDRESS,
			$this->sysuser->ERROR_USER_NOGROUP,
			$this->sysuser->ERROR_UNKNOWN];
		$reasonStringFilter = [
			$this->sysuser->getErrorReason($this->sysuser->ERROR_INCORRECT_PASSWORD),
			$this->sysuser->getErrorReason($this->sysuser->ERROR_USER_BLOCKED),
			$this->sysuser->getErrorReason($this->sysuser->ERROR_USER_NOACCESS_DAY),
			$this->sysuser->getErrorReason($this->sysuser->ERROR_USER_NOACCESS_TIME),
			$this->sysuser->getErrorReason($this->sysuser->ERROR_USER_DOES_NOT_EXIST),
			$this->sysuser->getErrorReason($this->sysuser->ERROR_USER_NOACCESS_REALM),
			$this->sysuser->getErrorReason($this->sysuser->ERROR_USER_NOACCESS_IPADDRESS),
			$this->sysuser->getErrorReason($this->sysuser->ERROR_USER_NOGROUP),
			$this->sysuser->getErrorReason($this->sysuser->ERROR_UNKNOWN)];
		$data = array(
			'init' => true,
			'reasonValueFilter' => $reasonValueFilter,
			'reasonStringFilter' => $reasonStringFilter,
			'start_month' => $sMonth,
			'start_year' => $sYear,
			'start_day' => $sDay,
			'end_month' => $eMonth,
			'end_year' => $eYear,
			'end_day'=> $eDay,
			'max' => 20,
			'error' => '');
		$this->load->view('failed_login_attempts', $data);
	}
	public function showFailedSysuserLoginAttempts($link = 0, $order = null, $ipaddress = null, $username = null, $reason = null,
		$startMonth = null, $startYear = null, $startDay = null, $endMonth = null, $endYear = null, $endDay = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('System User Activity Logs', 'activitylogs/showFailedSysuserLoginAttempts');
		if (intval($link) == 0) { //via form
			$ipaddress = trim($this->input->post('ipaddress'));
			$username = trim($this->input->post('username'));
			$reason = trim($this->input->post('reason'));
			$ipaddress = $ipaddress == '' ? null : $ipaddress;
			$username = $username == '' ? null : $username;
			$reason = $reason == '' ? null : $reason;
			$startMonth = $this->input->post('start_month');
			$startYear = $this->input->post('start_year');
			$startDay = $this->input->post('start_day');
			$endMonth = $this->input->post('end_month');
			$endYear = $this->input->post('end_year');
			$endDay = $this->input->post('end_day');
			$start = $this->input->post('start');
			$max = $this->input->post('max');
			$order = $this->input->post('order');
			$orderParts = explode('-', $order);
			$order = array('column' => $orderParts[0], 'dir' => $orderParts[1]);
		} else { //via link
			if ($order == null) {
				$order = array('column' => 'timestamp', 'dir' => 'desc');
			} else {
				$parts = explode('-', $order);
				$order = array('column' => $parts[0], 'dir' => $parts[1]);
			}
			$ipaddress = $ipaddress == 'null' ? null : str_replace('_', ' ', $ipaddress);
			$username = $username == 'null' ? null : str_replace('_', ' ', $username);
			$reason = $reason == 'null' ? null : str_replace('_', ' ', $reason);
		}
		$sMonth = $startMonth == null ? date('m', mktime()) : $startMonth;
		$sDay = $startDay == null ? date('d', mktime()) : $startDay;
		$sYear = $startYear == null ? date('Y', mktime()) : $startYear;
		$startdate = mktime(0, 0, 0, $sMonth, $sDay, $sYear);
		$eMonth = $endMonth == null ? date('m', mktime()) : $endMonth;
		$eDay = $endDay == null ? date('d', mktime()) : $endDay;
		$eYear = $endYear == null ? date('Y', mktime()) : $endYear;
		$enddate = mktime(0, 0, 0, $eMonth, $eDay, $eYear);
		$this->load->model('sysuser');
		$reasonValueFilter = [
			$this->sysuser->ERROR_INCORRECT_PASSWORD,
			$this->sysuser->ERROR_USER_BLOCKED,
			$this->sysuser->ERROR_USER_NOACCESS_DAY,
			$this->sysuser->ERROR_USER_NOACCESS_TIME,
			$this->sysuser->ERROR_USER_DOES_NOT_EXIST,
			$this->sysuser->ERROR_USER_NOACCESS_REALM,
			$this->sysuser->ERROR_USER_NOACCESS_IPADDRESS,
			$this->sysuser->ERROR_USER_NOGROUP,
			$this->sysuser->ERROR_UNKNOWN];
		$reasonStringFilter = [
			$this->sysuser->getErrorReason($this->sysuser->ERROR_INCORRECT_PASSWORD),
			$this->sysuser->getErrorReason($this->sysuser->ERROR_USER_BLOCKED),
			$this->sysuser->getErrorReason($this->sysuser->ERROR_USER_NOACCESS_DAY),
			$this->sysuser->getErrorReason($this->sysuser->ERROR_USER_NOACCESS_TIME),
			$this->sysuser->getErrorReason($this->sysuser->ERROR_USER_DOES_NOT_EXIST),
			$this->sysuser->getErrorReason($this->sysuser->ERROR_USER_NOACCESS_REALM),
			$this->sysuser->getErrorReason($this->sysuser->ERROR_USER_NOACCESS_IPADDRESS),
			$this->sysuser->getErrorReason($this->sysuser->ERROR_USER_NOGROUP),
			$this->sysuser->getErrorReason($this->sysuser->ERROR_UNKNOWN)];
		$attempts = $this->sysuser->findFailedSysuserLoginAttemptsWithParams($startdate, $enddate, $username, $ipaddress, $reason, $start, $max, $order);
		$count = $this->sysuser->countFailedSysuserLoginAttemptsWithParams($startdate, $enddate, $username, $ipaddress, $reason);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		$pagedata = array(
			'init' => false,
			'attempts' => $attempts,
			'count' => $count,
			'start' => $start,
			'max' => $max,
			'pages' => $pages,
			'order' => $order,
			'ipaddress' => $ipaddress,
			'username' => $username,
			'reason' => $reason,
			'reasonValueFilter' => $reasonValueFilter,
			'reasonStringFilter' => $reasonStringFilter,
			'start_month' => $sMonth,
			'start_year' => $sYear,
			'start_day' => $sDay,
			'end_month' => $eMonth,
			'end_year' => $eYear,
			'end_day'=> $eDay,
			'error' => '');
		$this->load->view('failed_login_attempts', $pagedata);
	}
	public function showSystemUserActivitiesForm() {
		$this->redirectIfNoAccess('System User Activity Logs', 'activitylogs/showSystemUserActivitiesForm');
		$this->load->model('sysuseractivitylog');
		$sMonth = date('m', mktime());
		$sYear = date('Y', mktime());
		$sDay = date('d', mktime());
		$eMonth = date('m', mktime());
		$eYear = date('Y', mktime());
		$eDay = date('d', mktime());
		$dataFilter = [
			$this->sysuseractivitylog->DATATYPE_SYSUSER, 
			$this->sysuseractivitylog->DATATYPE_SYSUSERGROUP, 
			$this->sysuseractivitylog->DATATYPE_SYSIPACCOUNT,
			$this->sysuseractivitylog->DATATYPE_SERVICE, 
			$this->sysuseractivitylog->DATATYPE_REALM,
			$this->sysuseractivitylog->DATATYPE_IPADDRESS,
			$this->sysuseractivitylog->DATATYPE_NETADDRESS,
			$this->sysuseractivitylog->DATATYPE_ILLEGALACCESS];
		$actionFilter = [
			$this->sysuseractivitylog->ACTION_CREATE,
			$this->sysuseractivitylog->ACTION_MODIFY,
			$this->sysuseractivitylog->ACTION_DELETE,
			$this->sysuseractivitylog->ACTION_FREEUP,
			$this->sysuseractivitylog->ACTION_LOGOUT,
			$this->sysuseractivitylog->ACTION_BLOCK,
			$this->sysuseractivitylog->ACTION_UNBLOCK,
			$this->sysuseractivitylog->ACTION_CHANGEPASSWORD,
			$this->sysuseractivitylog->ACTION_ACCESS];
		$data = array(
			'init' => true,
			'data_filter' => $dataFilter,
			'data' => '',
			'action' => '',
			'action_filter' => $actionFilter,
			'start_month' => $sMonth,
			'start_year' => $sYear,
			'start_day' => $sDay,
			'end_month' => $eMonth,
			'end_year' => $eYear,
			'end_day'=> $eDay,
			'max' => 20,
			'error' => '');
		$this->load->view('sysuser_activities', $data);
	}
	public function showSystemUserActivities($link = 0, $order = null, $sysuser = null, $ipaddress = null, $data = null, $action = null,
		$startMonth = null, $startYear = null, $startDay = null, $endMonth = null, $endYear = null, $endDay = null, $start = 0, $max = 20) {
		$this->redirectIfNoAccess('System User Activity Logs', 'activitylogs/showSystemUserActivities');
		$submit = '';
		if (intval($link) == 0) { //via form
			$sysuser = trim($this->input->post('sysuser'));
			$ipaddress = trim($this->input->post('ipaddress'));
			$data = trim($this->input->post('data'));
			$action = trim($this->input->post('action'));
			$sysuser = $sysuser == '' ? null : $sysuser;
			$ipaddress = $ipaddress = '' ? null : $ipaddress;
			$data = $data == '' ? null : $data;
			$action = $action == '' ? null : $action;
			$startMonth = $this->input->post('start_month');
			$startYear = $this->input->post('start_year');
			$startDay = $this->input->post('start_day');
			$endMonth = $this->input->post('end_month');
			$endYear = $this->input->post('end_year');
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
			$sysuser = $sysuser == 'null' ? null : str_replace('_', ' ', $sysuser);
			$ipaddress = $ipaddress == 'null' ? null : str_replace('_', ' ', $ipaddress);
			$data = $data == 'null' ? null : str_replace('_', ' ', $data);
			$action = $action == 'null' ? null : str_replace('_', ' ', $action);
		}
		$sMonth = $startMonth == null ? date('m', mktime()) : $startMonth;
		$sDay = $startDay == null ? date('d', mktime()) : $startDay;
		$sYear = $startYear == null ? date('Y', mktime()) : $startYear;
		$startdate = mktime(0, 0, 0, $sMonth, $sDay, $sYear);
		$eMonth = $endMonth == null ? date('m', mktime()) : $endMonth;
		$eDay = $endDay == null ? date('d', mktime()) : $endDay;
		$eYear = $endYear == null ? date('Y', mktime()) : $endYear;
		$enddate = mktime(0, 0, 0, $eMonth, $eDay, $eYear);
		$this->load->model('sysuseractivitylog');
		$dataFilter = [
			$this->sysuseractivitylog->DATATYPE_SYSUSER, 
			$this->sysuseractivitylog->DATATYPE_SYSUSERGROUP, 
			$this->sysuseractivitylog->DATATYPE_SYSIPACCOUNT,
			$this->sysuseractivitylog->DATATYPE_SERVICE, 
			$this->sysuseractivitylog->DATATYPE_REALM,
			$this->sysuseractivitylog->DATATYPE_IPADDRESS,
			$this->sysuseractivitylog->DATATYPE_NETADDRESS,
			$this->sysuseractivitylog->DATATYPE_ILLEGALACCESS];
		$actionFilter = [
			$this->sysuseractivitylog->ACTION_CREATE,
			$this->sysuseractivitylog->ACTION_MODIFY,
			$this->sysuseractivitylog->ACTION_DELETE,
			$this->sysuseractivitylog->ACTION_FREEUP,
			$this->sysuseractivitylog->ACTION_LOGOUT,
			$this->sysuseractivitylog->ACTION_BLOCK,
			$this->sysuseractivitylog->ACTION_UNBLOCK,
			$this->sysuseractivitylog->ACTION_CHANGEPASSWORD,
			$this->sysuseractivitylog->ACTION_ACCESS];
		$logs = $this->sysuseractivitylog->findAllSysuserActivityLogsWithParams($startdate, $enddate, $data, $action, $sysuser, $ipaddress, $start, $max, $order);
		$count = $this->sysuseractivitylog->countSysuserActivityLogsWithParams($startdate, $enddate, $data, $action, $sysuser, $ipaddress);
		$pages = intval($count / $max);
		$last = $count % $max;
		if ($last > 0) {
			$pages = $pages + 1;
		}
		if ($submit == 'extract') { //create excel file
			try {
				$this->load->model('util');
				$headers = $this->sysuseractivitylog->DEFAULT_COLUMNS;//DATA TYPE", "ACTION", "INFO", "SYSTEM USER", "IP ADDRESS", "TIMESTAMP"
				$columns = ['A', 'B', 'C', 'D', 'E', 'F'];
				$title = 'System User Activities';
				$this->util->writeSystemUserActivities($logs, $headers, $columns, $title);
			} catch (Exception $e) {
				$pagedata = array(
					'init' => false,
					'logs' => $logs,
					'count' => $count,
					'start' => $start,
					'max' => $max,
					'pages' => $pages,
					'order' => $order,
					'sysuser' => $sysuser,
					'ipaddress' => $ipaddress,
					'data_filter' => $dataFilter,
					'data' => $data,
					'action_filter' => $actionFilter,
					'action' => $action,
					'start_month' => $sMonth,
					'start_year' => $sYear,
					'start_day' => $sDay,
					'end_month' => $eMonth,
					'end_year' => $eYear,
					'end_day'=> $eDay,
					'error' => 'Error extracting entries.');
				$this->load->view('sysuser_activities', $pagedata);
			}
		} else {
			$pagedata = array(
				'init' => false,
				'logs' => $logs,
				'count' => $count,
				'start' => $start,
				'max' => $max,
				'pages' => $pages,
				'order' => $order,
				'sysuser' => $sysuser,
				'ipaddress' => $ipaddress,
				'data_filter' => $dataFilter,
				'data' => $data,
				'action_filter' => $actionFilter,
				'action' => $action,
				'start_month' => $sMonth,
				'start_year' => $sYear,
				'start_day' => $sDay,
				'end_month' => $eMonth,
				'end_year' => $eYear,
				'end_day'=> $eDay,
				'error' => '');
			$this->load->view('sysuser_activities', $pagedata);
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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscriberaudittrail extends CI_Model {
	private $extras;
	public $ACTION_CREATE = "C";
    public $ACTION_MODIFY = "U";
    public $ACTION_DELETE = "D";
    public $ACTION_STATUSCHANGE = "U";
    public $ACTION_PASSWORDRESET = "R";
    public $ACTION_DELETESESSION = "D";
    public $ACTION_PASSWORDCHANGE = "P";
    /*
    public $ORDER_TIMESTAMP_ASC = "order by timestamp asc";
    public $ORDER_TIMESTAMP_DESC = "order by timestamp desc";
    public $ORDER_ACTION_ASC = "order by action asc";
    public $ORDER_ACTION_DESC = "order by action desc";
    public $ORDER_USERNAME_ASC = "order by subscriberCn asc";
    public $ORDER_USERNAME_DESC = "order by subscriberCn desc";
    public $ORDER_SYSUSER_ASC = "order by sysuser asc";
    public $ORDER_SYSUSER_DESC = "order by sysuser desc";
    public $ORDER_IPADDRESS_ASC = "order by ipaddress asc";
    public $ORDER_IPADDRESS_DESC = "order by ipaddress desc";
    */
    public $DEFAULT_COLUMNS = ['SUBSCRIBER', 'ACTION', 'INFO', 'SYSTEM USER', 'IP ADDRESS', 'TIMESTAMP'];

	function __construct() {
		parent::__construct();
		$this->extras = $this->load->database('extras', true);
	}

	public function logSubscriberSessionDeletion($cn, $realm, $force, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'subscriber_cn' => $cn,
			'subscriber_realm' => $realm,
			'action' => $this->ACTION_DELETESESSION,
			'remarks' => $force ? 'Force deleted' : 'Hanged session',
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => $date == null ? date('Y-m-d H:i:s', mktime()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('subscriber_audittrail', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logSubscriberPasswordReset($cn, $realm, $newpassword, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'subscriber_cn' => $cn,
			'subscriber_realm' => $realm,
			'action' => $this->ACTION_PASSWORDRESET,
			'extra1' => $newpassword,
			'remarks' => 'New password: '.$newpassword,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => $date == null ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $date));
		$this->extras->insert('subscriber_audittrail', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);	
	}
	public function logSubscriberPasswordChange($cn, $realm, $newpassword, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'subscriber_cn' => $cn,
			'subscriber_realm' => $realm,
			'action' => $this->ACTION_PASSWORDCHANGE,
			'extra1' => $newpassword,
			'remarks' => 'New password: '.$newpassword,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => $date == null ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $date));
		$this->extras->insert('subscriber_audittrail', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);		
	}
	public function logSubscriberCreation($subscriber, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$remarks = 'PASSWORD='.$subscriber['PASSWORD'].', CUSTOMERTYPE='.$subscriber['CUSTOMERTYPE'].', CUSTOMERSTATUS='.$subscriber['CUSTOMERSTATUS'].', '.
			'RBCUSTOMERNAME='.$subscriber['RBCUSTOMERNAME'].', RBORDERNUMBER='.$subscriber['RBORDERNUMBER'].', RBSERVICENUMBER='.$subscriber['RBSERVICENUMBER'].', '.
			'RBENABLED='.$subscriber['RBENABLED'].', RADIUSPOLICY='.$subscriber['RBACCOUNTPLAN'].', RBIPADDRESS='.$subscriber['RBIPADDRESS'].', '.
			'RBMULTISTATIC='.$subscriber['RBMULTISTATIC'].', '.'RBADDITIONALSERVICE1='.$subscriber['RBADDITIONALSERVICE1'].', '.
			'RBADDITIONALSERVICE2='.$subscriber['RBADDITIONALSERVICE2'].', RBREMARKS='.$subscriber['RBREMARKS'].'|';
		if (strlen($remarks) > 2000) {
			$remarks = substr($remarks, 0, 2000);
		}
		$data = array(
			'subscriber_cn' => $subscriber['USER_IDENTITY'], //subscriber cn
			'subscriber_realm' => $subscriber['RBREALM'], //subscriber realm
			'action' => $this->ACTION_CREATE,
			'remarks' => $remarks,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => $date);
		$this->extras->insert('subscriber_audittrail', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);		
	}
	public function logSubscriberModification($user, $oldUser, $sysuser, $ipaddress, $date, $ignoreNull) {
		//$user, $oldUser = array('password' => $whatever, 'CUSTOMERTYPE' => $whatever, ... 'remarks' => $whatever)
		$this->extras->db_select();
		$remarks = '';
		if ($user['PASSWORD'] != $oldUser['PASSWORD'] && ($user['PASSWORD'] != null || $ignoreNull)) {
			$remarks = $remarks.'PASSWORD: '.$oldUser['PASSWORD'].' > '.$user['PASSWORD'];
		} else {
			$remarks = $remarks.'PASSWORD: '.$user['PASSWORD'];
		}
		if ($user['CUSTOMERTYPE'] != $oldUser['CUSTOMERTYPE'] && ($user['CUSTOMERTYPE'] != null || $ignoreNull)) {
			$remarks = $remarks.', CUSTOMERTYPE: '.$oldUser['CUSTOMERTYPE'].' > '.$user['CUSTOMERTYPE'];
		} else {
			$remarks = $remarks.', CUSTOMERTYPE: '.$user['CUSTOMERTYPE'];
		}
		if ($user['CUSTOMERSTATUS'] != $oldUser['CUSTOMERSTATUS'] && ($user['CUSTOMERSTATUS'] != null || $ignoreNull)) {
			$remarks = $remarks.', CUSTOMERSTATUS: '.$oldUser['CUSTOMERSTATUS'].' > '.$user['CUSTOMERSTATUS'];
		} else {
			$remarks = $remarks.', CUSTOMERSTATUS: '.$user['CUSTOMERSTATUS'];
		}
		if ($user['USERNAME'] != $oldUser['USERNAME'] && ($user['USERNAME'] != null || $ignoreNull)) {
			$remarks = $remarks.', USERNAME: '.$oldUser['USERNAME'].' > '.$user['USERNAME'];
		} else {
			$remarks = $remarks.', USERNAME: '.$user['USERNAME'];
		}
		if ($user['RBORDERNUMBER'] != $oldUser['RBORDERNUMBER'] && ($user['RBORDERNUMBER'] != null || $ignoreNull)) {
			$remarks = $remarks.', RBORDERNUMBER: '.$oldUser['RBORDERNUMBER'].' > '.$user['RBORDERNUMBER'];
		} else {
			$remarks = $remarks.', RBORDERNUMBER: '.$user['RBORDERNUMBER'];
		}
		if ($user['RBSERVICENUMBER'] != $oldUser['RBSERVICENUMBER'] && ($user['RBSERVICENUMBER'] != null || $ignoreNull)) {
			$remarks = $remarks.', RBSERVICENUMBER: '.$oldUser['RBSERVICENUMBER'].' > '.$user['RBSERVICENUMBER'];
		} else {
			$remarks = $remarks.', RBSERVICENUMBER: '.$user['RBSERVICENUMBER'];
		}
		if ($user['RBENABLED'] != $oldUser['RBENABLED'] && ($user['RBENABLED'] != null || $ignoreNull)) {
			$remarks = $remarks.', RBENABLED: '.$oldUser['RBENABLED'].' > '.$user['RBENABLED'];
		} else {
			$remarks = $remarks.', RBENABLED: '.$user['RBENABLED'];
		}
		if ($user['RBACCOUNTPLAN'] != $oldUser['RBACCOUNTPLAN'] && ($user['RBACCOUNTPLAN'] != null || $ignoreNull)) {
			$remarks = $remarks.', RBACCOUNTPLAN: '.str_replace('~', '-', $oldUser['RBACCOUNTPLAN']).' > '.str_replace('~', '-', $user['RBACCOUNTPLAN']);
		} else {
			$remarks = $remarks.', RBACCOUNTPLAN: '.str_replace('~', '-', $user['RBACCOUNTPLAN']);
		}
		if ($user['RBIPADDRESS'] != $oldUser['RBIPADDRESS'] && ($user['RBIPADDRESS'] != null || $ignoreNull)) {
			$remarks = $remarks.', RBIPADDRESS: '.$oldUser['RBIPADDRESS'].' > '.$user['RBIPADDRESS'];
		} else {
			$remarks = $remarks.', RBIPADDRESS: '.$user['RBIPADDRESS'];
		}
		if ($user['RBMULTISTATIC'] != $oldUser['RBMULTISTATIC'] && ($user['RBMULTISTATIC'] != null || $ignoreNull)) {
			$remarks = $remarks.', RBMULTISTATIC: '.$oldUser['RBMULTISTATIC'].' > '.$user['RBMULTISTATIC'];
		} else {
			$remarks = $remarks.', RBMULTISTATIC: '.$user['RBMULTISTATIC'];
		}
		if ($user['RBADDITIONALSERVICE1'] != $oldUser['RBADDITIONALSERVICE1'] && ($user['RBADDITIONALSERVICE1'] != null || $ignoreNull)) {
			$remarks = $remarks.', RBADDITIONALSERVICE1: '.$oldUser['RBADDITIONALSERVICE1'].' > '.$user['RBADDITIONALSERVICE1'];
		} else {
			$remarks = $remarks.', RBADDITIONALSERVICE1: '.$user['RBADDITIONALSERVICE1'];
		}
		if ($user['RBADDITIONALSERVICE2'] != $oldUser['RBADDITIONALSERVICE2'] && ($user['RBADDITIONALSERVICE2'] != null || $ignoreNull)) {
			$remarks = $remarks.', RBADDITIONALSERVICE2: '.$oldUser['RBADDITIONALSERVICE2'].' > '.$user['RBADDITIONALSERVICE2'];
		} else {
			$remarks = $remarks.', RBADDITIONALSERVICE2: '.$user['RBADDITIONALSERVICE2'];
		}
		if (strlen($remarks) > 2000) {
			$remarks = substr($remarks, 0, 2000);
		}
		$data = array(
			'subscriber_cn' => $user['USER_IDENTITY'], //subscriber cn
			'subscriber_realm' => $user['RBREALM'], //subscriber realm
			'action' => $this->ACTION_MODIFY,
			'remarks' => $remarks,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => $date);
		$this->extras->insert('subscriber_audittrail', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logSubscriberDeletion($subscriber, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'subscriber_cn' => $subscriber['USER_IDENTITY'],
			'subscriber_realm' => $subscriber['RBREALM'],
			'action' => $this->ACTION_DELETE,
			'remarks' => '',
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => $date);
		$this->extras->insert('subscriber_audittrail', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function findAllSubscriberAuditTrail($start, $max) {
		$this->extras->db_select();
		$query = $this->extras->select('id, subscriber_cn, subscriber_realm, action, extra1, extra2, extra3, remarks, sysuser, ipaddress, timestamp')
			->from('subscriber_audittrail')
			->order_by('timestamp', 'desc')
			->limit($max, $start)
			->get();
		return $query->result_array();
	}
	public function countSubscriberAuditTrails() {
		$this->extras->db_select();
		$count = $this->extras->count_all_results('subscriber_audittrail');
		return $count;
	}
	public function findAllSubscriberAuditTrailWithParam($startdate, $enddate, $bySysuser, $action, $username, $ipaddress, $start, $max, $order) {
		//$order = array('column' => [column], 'dir' => [asc/desc])
		//date('Y-m-d H:i:s')
		$this->extras->db_select();
		$this->extras->select("id, subscriber_cn, subscriber_realm, action, extra1, extra2, extra3, remarks, sysuser, ipaddress, timestamp")
			->from('subscriber_audittrail');
		if ($startdate != null) {
			$this->extras->where('(DATE(timestamp) > DATE("'.date('Y-m-d H:i:s', $startdate).'") or DATE(timestamp) = DATE("'.date('Y-m-d H:i:s', $startdate).'"))');
		}
		if ($enddate != null) {
			$this->extras->where('(DATE(timestamp) < DATE("'.date('Y-m-d H:i:s', $enddate).'") or DATE(timestamp) = DATE("'.date('Y-m-d H:i:s', $enddate).'"))');
		}
		if ($bySysuser != null) {
			$this->extras->where('sysuser', $bySysuser);
		}
		if ($action != null) {
			$this->extras->where('action', $action);
		}
		if ($username != null) {
			$this->extras->where('subscriber_cn', $username);
		}
		if ($ipaddress != null) {
			$this->extras->where('ipaddress', $ipaddress);
		}
		if ($order != null) {
			$this->extras->order_by($order['column'], $order['dir']);
		} else {
			$this->extras->order_by('timestamp', 'desc');
		}
		$this->extras->limit($max, $start);
		$query = $this->extras->get();
		return $query->result_array();
	}
	public function countSubscriberAuditTrailsWithParam($startdate, $enddate, $bySysuser, $action, $username, $ipaddress) {
		//$order = array('column' => [column], 'dir' => [asc/desc])
		//date('Y-m-d H:i:s')
		$this->extras->db_select();
		$this->extras->from('subscriber_audittrail');
		if ($startdate != null) {
			$this->extras->where('(DATE(timestamp) > DATE("'.date('Y-m-d H:i:s', $startdate).'") or DATE(timestamp) = DATE("'.date('Y-m-d H:i:s', $startdate).'"))');
		}
		if ($enddate != null) {
			$this->extras->where('(DATE(timestamp) < DATE("'.date('Y-m-d H:i:s', $enddate).'") or DATE(timestamp) = DATE("'.date('Y-m-d H:i:s', $enddate).'"))');
		}
		if ($bySysuser != null) {
			$this->extras->where('sysuser', $bySysuser);
		}
		if ($action != null) {
			$this->extras->where('action', $action);
		}
		if ($username != null) {
			$this->extras->where('subscriber_cn', $username);
		}
		if ($ipaddress != null) {
			$this->extras->where('ipaddress', $ipaddress);
		}
		$this->extras->order_by('timestamp', 'desc');
		$count = $this->extras->count_all_results();
		return $count;
	}

	public function getLastActionDate($username) {
		$this->extras->db_select();
		$query = $this->extras->select('timestamp')
			->from('subscriber_audittrail')
			->where('sysuser', $username)
			->order_by('timestamp', 'desc')
			->limit(1, 0)
			->get();
		return $query->num_rows() == 0 ? false : $query->row_array();
	}
}
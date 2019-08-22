<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sysusermain extends CI_Model {
	private $utility;
	private $COLUMN_USERID = 'USERID';
	private $COLUMN_USERNAME = 'USERNAME';
	private $COLUMN_PASSWORD = 'PASSWORD';
	private $COLUMN_LASTNAME = 'LASTNAME';
	private $COLUMN_FIRSTNAME = 'FIRSTNAME';
	private $COLUMN_EMAIL = 'EMAIL';
	private $COLUMN_ROLE = 'ROLE';
	private $COLUMN_LOGGEDIN = 'LOGGED_IN';
	private $COLUMN_SESSIONID = 'SESSION_ID';
	private $COLUMN_BLOCKED = 'BLOCKED';
	private $COLUMN_LOGINTRIES = 'LOGIN_TRIES';
	private $COLUMN_REQUIREPASSWORD = 'REQUIRE_PASSWORD';
	private $COLUMN_PASSWORDCHANGEDATE = 'PASSWORD_CHANGE_DATE';
	private $COLUMN_MODIFIEDDATE = 'MODIFIED_DATE';
	private $COLUMN_CREATIONDATE = 'CREATION_DATE';
	private $TABLENAME = 'TBLSYSUSER';

	function __construct() {
		parent::__construct();
		$this->utility = $this->load->database('utility', true);
	}

	public function create($username, $password, $lastname, $firstname, $email, $role) {
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', mktime());
		$this->load->library('encrypt');
		$query = "insert into tblsysuser (USERNAME, PASSWORD, LASTNAME, FIRSTNAME, EMAIL, ROLE, LOGGED_IN, SESSION_ID, BLOCKED, LOGIN_TRIES, REQUIRE_PASSWORD, PASSWORD_CHANGE_DATE, MODIFIED_DATE, CREATION_DATE) ".
			"VALUES ('".$username."', '".$this->encrypt->encode($password)."', '".$lastname."', '".$firstname."', '".$email."', '".$role."', 0, NULL, 0, 0, 1, NULL, NULL, ".
			"TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS'))";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function delete($username) {
		$this->utility->db_select();
		$this->utility->where("USERNAME = '".$username."'")
			->delete($this->TABLENAME);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function modify($username, $lastname, $firstname, $email, $role) {
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', mktime());
		$query = "update TBLSYSUSER set LASTNAME = '".$lastname."', FIRSTNAME = '".$firstname."', EMAIL = '".$email."',".
			" MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS'), ROLE = '".$role."' where USERNAME = '".$username."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function fetchSysuserById($id) {
		$this->utility->db_select();
		$query = $this->utility->select('*')
			->from($this->TABLENAME)
			->where($this->COLUMN_USERID, $id)
			->get();
		return $query->num_rows() == 0 ? false : $query->row_array();
	}
	public function fetchSysuserByUsername($username) {
		$this->utility->db_select();
		$query = $this->utility->select('*')
			->from($this->TABLENAME)
			->where("USERNAME = '".$username."'")
			->get();
		return $query->num_rows() == 0 ? false : $query->row_array();
	}
	public function sysuserExists($username) {
		$this->utility->db_select();
		$count = $this->utility->from($this->TABLENAME)
			->where($this->COLUMN_USERNAME, $username)
			->count_all_results();
		return $count == 0 ? false : true;	
	}
	public function changeSysuserRole($username, $role) {
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', mktime());
		$query = "update TBLSYSUSER set ROLE = '".$role."', MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') where USERNAME = '".$username."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function blockSysuser($username) {
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', mktime());
		$query = "update TBLSYSUSER set BLOCKED = 1, MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') where USERNAME = '".$username."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function unblockSysuser($username) {
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', mktime());
		$query = "update TBLSYSUSER set BLOCKED = 0, LOGIN_TRIES = 0, MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') where USERNAME = '".$username."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function setSysuserLoggedIn($username) {
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', mktime());
		$query = "update TBLSYSUSER set LOGGED_IN = 1, MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') where USERNAME = '".$username."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function setSysuserLoggedOut($username) {
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', mktime());
		$query = "update TBLSYSUSER set LOGGED_IN = 0, SESSION_ID = null, MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') where USERNAME = '".$username."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function setSysuserSession($username, $session) {
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', mktime());
		$query = "update TBLSYSUSER set SESSION_ID = '".$session."', MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') where USERNAME = '".$username."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function setSysuserRequirePasswordChange($username) {
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', mktime());
		$query = "update TBLSYSUSER set REQUIRE_PASSWORD = 1, MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') where USERNAME = '".$username."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function setSysuserNotRequirePasswordChange($username) {
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', mktime());
		$query = "update TBLSYSUSER set REQUIRE_PASSWORD = 0, MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') where USERNAME = '".$username."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function changeSysuserPassword($username, $password, $makeRequired) {
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', mktime());
		$this->load->library('encrypt');
		$query = "update TBLSYSUSER set PASSWORD = '".$this->encrypt->encode($password)."', PASSWORD_CHANGE_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS'), ".
			"MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS'), REQUIRE_PASSWORD = ".($makeRequired ? 1 : 0)." where USERNAME = '".$username."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function resetLoginTries($username) {
		$this->utility->db_select();
		$now = date('Y-m-d H:i:s', mktime());
		$query = "update TBLSYSUSER set LOGIN_TRIES = 0, MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') where USERNAME = '".$username."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function getLoginTries($username) {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_LOGINTRIES)
			->from($this->TABLENAME)
			->where($this->COLUMN_USERNAME, $username)
			->get();
		$result = $query->row_array();
		return $result[$this->COLUMN_LOGINTRIES];
	}
	public function incrementLoginTries($username) {
		$this->utility->db_select();
		$now = date('Y-M-d H:i:s', mktime());
		$query = $this->utility->select($this->COLUMN_LOGINTRIES)
			->from($this->TABLENAME)
			->where($this->COLUMN_USERNAME, $username)
			->get();
		$result = $query->row_array();
		$loginTries = intval($result[$this->COLUMN_LOGINTRIES]);
		$query = "update TBLSYSUSER set LOGIN_TRIES = ".($loginTries + 1).", MODIFIED_DATE = TO_TIMESTAMP('".substr($now, 2, strlen($now))."', 'RR-MM-DD HH24:MI:SS') ".
			"where USERNAME = '".$username."'";
		$this->utility->query($query);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function getSysuserPassword($username) {
		$this->utility->db_select();
		$this->load->library('encrypt');
		$query = $this->utility->select($this->COLUMN_PASSWORD)
			->where($this->COLUMN_USERNAME, $username)
			->get();
		$result = $query->row_array();
		return $this->encrypt->decode($result[$this->COLUMN_PASSWORD]);
	}
	public function getSysuserLoginTime($username) {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_MODIFIEDDATE)
			->from($this->TABLENAME)
			->where($this->COLUMN_USERNAME, $username)
			->get();
		$result = $query->row_array();
		return $result[$this->COLUMN_MODIFIEDDATE];
	}
	public function getLastPasswordChange($username) {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_PASSWORDCHANGEDATE)
			->from($this->TABLENAME)
			->where($this->COLUMN_USERNAME, $username)
			->get();
		$result = $query->row_array();
		return $result[$this->COLUMN_PASSWORDCHANGEDATE];
	}
	public function getSysuserSession($username) {
		$this->utility->db_select();
		$query = $this->utility->select($this->COLUMN_SESSIONID)
			->from($this->TABLENAME)
			->where($this->COLUMN_USERNAME, $username)
			->get();
		$result = $query->row_array();
		return $result[$this->COLUMN_SESSIONID];
	}

	public function fetchAll($order, $start, $max, $su) {
		$this->utility->db_select();
		$this->utility->select('*')
			->from($this->TABLENAME)
			->where($this->COLUMN_USERNAME.' !=', $su);
		if (is_null($order)) {
			$this->utility->order_by($this->COLUMN_USERID, 'ASC');
		} else {
			if ($order['column'] == 'BLOCKED' || $order['column'] == 'LOGGED_IN') {
				$this->utility->order_by($order['column'], $order['dir'])
					->order_by($this->COLUMN_USERNAME, 'asc');
			} else {
				$this->utility->order_by($order['column'], $order['dir']);
			}
		}
		if (!is_null($max) && !is_null($start)) {
			$this->utility->limit($start == 0 ? $max + 1 : $max, $start == 0 ? $start : $start + 1);
		}
		$query = $this->utility->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSysuers($su) {
		$this->utility->db_select();
		$count = $this->utility->from($this->TABLENAME)
			->where($this->COLUMN_USERNAME.' !=', $su)
			->count_all_results();
		return $count;
	}
	public function fetchAllLoggedIn($order, $start, $max, $su) {
		$this->utility->db_select();
		$this->utility->select('*')
			->from($this->TABLENAME)
			->where($this->COLUMN_LOGGEDIN, 1)
			->where($this->COLUMN_USERNAME.' !=', $su);
		if (is_null($order)) {
			$this->utility->order_by($this->COLUMN_USERID, 'ASC');
		} else {
			$this->utility->order_by($order['column'], $order['dir']);
		}
		if (!is_null($max) && !is_null($start)) {
			$this->utility->limit($start == 0 ? $max + 1 : $max, $start == 0 ? $start : $start + 1);
		}
		$query = $this->utility->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSysusersLoggedIn($su) {
		$this->utility->db_select();
		$count = $this->utility->from($this->TABLENAME)
			->where($this->COLUMN_LOGGEDIN, 1)
			->where($this->COLUMN_USERNAME.' !=', $su)
			->count_all_results();
		return $count;
	}

	public function rowDataToSysuserArray($data) {
		$now = date('Y-m-d H:i:s', time());
		$sysuser = array(
			$this->COLUMN_USERNAME => trim($data[0]),
			$this->COLUMN_PASSWORD => null,
			$this->COLUMN_ROLE => trim($data[1]),
			$this->COLUMN_FIRSTNAME => trim($data[2]),
			$this->COLUMN_LASTNAME => trim($data[3]),
			$this->COLUMN_EMAIL => trim($data[4]) == '' ? null : trim($data[4]),
			$this->COLUMN_LOGGEDIN => 0,
			$this->COLUMN_LOGINTRIES => 0,
			$this->COLUMN_SESSIONID => null,
			$this->COLUMN_BLOCKED => 0,
			$this->COLUMN_REQUIREPASSWORD => 1,
			$this->COLUMN_PASSWORDCHANGEDATE => null,
			$this->COLUMN_MODIFIEDDATE => null,
			$this->COLUMN_CREATIONDATE => $now);
		return $sysuser;
	}
}
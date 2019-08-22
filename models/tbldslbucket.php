<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tbldslbucket extends CI_Model {
	private $utility;
	private $COLUMN_ID = 'ID';
	private $COLUMN_ROLEID = 'ROLEID';
	private $COLUMN_PAGEID = 'PAGEID';
	private $COLUMN_TIMEID = 'TIMEID';
	private $COLUMN_REALMID = 'REALMID';
	private $TABLENAME = 'TBLDSLBUCKET';

	function __construct() {
		parent::__construct();
		$this->utility = $this->load->database('utility', true);
	}

	/*********************************************************
	 * pages
	 *********************************************************/
	public function addPageToRole($roleId, $pageId) {
		$this->utility->db_select();
		$data = array(
			$this->COLUMN_ROLEID => $roleId,
			$this->COLUMN_PAGEID => $pageId,
			$this->COLUMN_TIMEID => -1,
			$this->COLUMN_REALMID => -1);
		$this->utility->insert($this->TABLENAME, $data);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function deletePageFromRole($roleId, $pageId) {
		$this->utility->db_select();
		$this->utility->where($this->COLUMN_ROLEID, $roleId)
			->where($this->COLUMN_PAGEID, $pageId)
			->where($this->COLUMN_TIMEID, -1)
			->where($this->COLUMN_REALMID, -1)
			->delete($this->TABLENAME);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function fetchPagesForRole($roleId) {
		$this->utility->db_select();
		$query = $this->utility->select('TBLDSLBUCKET.'.$this->COLUMN_PAGEID.' as ID, TBLDSLPAGE.PAGENAME as NM')
			->from($this->TABLENAME)
			->join('TBLDSLPAGE', 'TBLDSLBUCKET.'.$this->COLUMN_PAGEID.' = TBLDSLPAGE.PAGEID', 'left')
			->where('TBLDSLBUCKET.'.$this->COLUMN_ROLEID, intval($roleId))
			->where('TBLDSLBUCKET.'.$this->COLUMN_TIMEID, -1)
			->where('TBLDSLBUCKET.'.$this->COLUMN_REALMID, -1)
			->order_by('TBLDSLBUCKET.'.$this->COLUMN_ID, 'ASC')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function fetchPagesNotInRole($roleId) {
		$this->utility->db_select();
		$query = $this->utility->select('TBLDSLPAGE.PAGEID as ID, TBLDSLPAGE.PAGENAME as NM')
			->from('TBLDSLPAGE')
			->where('TBLDSLPAGE.PAGEID not in (select TBLDSLBUCKET.'.$this->COLUMN_PAGEID.' from '.$this->TABLENAME.' '.
				'where TBLDSLBUCKET.'.$this->COLUMN_ROLEID.' = '.$roleId.' and TBLDSLBUCKET.'.$this->COLUMN_TIMEID.' = -1 and TBLDSLBUCKET.'.$this->COLUMN_REALMID.' = -1)')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function deleteAllPagesFromRole($roleId) {
		$this->utility->db_select();
		$this->utility->where($this->COLUMN_ROLEID, $roleId)
			->where($this->COLUMN_TIMEID, -1)
			->where($this->COLUMN_REALMID, -1)
			->delete($this->TABLENAME);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function fetchAllPagesForSU() {
		$this->utility->db_select();
		$query = $this->utility->select('TBLDSLPAGE.PAGEID as ID, TBLDSLPAGE.PAGENAME as NM')
			->from('TBLDSLPAGE')
			->order_by('TBLDSLPAGE.PAGEID', 'ASC')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	/*********************************************************
	 * realms
	 *********************************************************/
	public function addRealmToRole($roleId, $realmId) {
		$this->utility->db_select();
		$data = array(
			$this->COLUMN_ROLEID => intval($roleId),
			$this->COLUMN_PAGEID => -1,
			$this->COLUMN_TIMEID => -1,
			$this->COLUMN_REALMID => $realmId);
		$this->utility->insert($this->TABLENAME, $data);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function deleteRealmFromRole($roleId, $realmId) {
		$this->utility->db_select();
		$this->utility->where($this->COLUMN_ROLEID, $roleId)
			->where($this->COLUMN_PAGEID, -1)
			->where($this->COLUMN_TIMEID, -1)
			->where($this->COLUMN_REALMID, $realmId)
			->delete($this->TABLENAME);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function fetchRealmsForRole($roleId) {
		$this->utility->db_select();
		$query = $this->utility->select('TBLDSLBUCKET.'.$this->COLUMN_REALMID.' as ID, TBLREALM.REALMNAME as NM')
			->from($this->TABLENAME)
			->join('TBLREALM', 'TBLDSLBUCKET.'.$this->COLUMN_REALMID.' = TBLREALM.REALMID', 'left')
			->where('TBLDSLBUCKET.'.$this->COLUMN_ROLEID, intval($roleId))
			->where('TBLDSLBUCKET.'.$this->COLUMN_PAGEID, -1)
			->where('TBLDSLBUCKET.'.$this->COLUMN_TIMEID, -1)
			->order_by('TBLDSLBUCKET.'.$this->COLUMN_ID, 'ASC')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function fetchRealmsNotInRole($roleId) {
		$this->utility->db_select();
		$query = $this->utility->select('TBLREALM.REALMID as ID, TBLREALM.REALMNAME as NM')
			->from('TBLREALM')
			->where('TBLREALM.REALMID not in (select "TBLDSLBUCKET"."'.$this->COLUMN_REALMID.'" from "'.$this->TABLENAME.
				'" where "TBLDSLBUCKET"."'.$this->COLUMN_ROLEID.'" = '.intval($roleId).' and "TBLDSLBUCKET"."'.$this->COLUMN_PAGEID.'" = -1 and "TBLDSLBUCKET"."'.$this->COLUMN_TIMEID.'" = -1)')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function deleteAllRealmsFromRole($roleId) {
		$this->utility->db_select();
		$this->utility->where($this->COLUMN_ROLEID, $roleId)
			->where($this->COLUMN_PAGEID, -1)
			->where($this->COLUMN_TIMEID, -1)
			->delete($this->TABLENAME);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function fetchAllRealmsForSU() {
		$this->utility->db_select();
		$query = $this->utility->select('TBLREALM.REALMID as ID, TBLREALM.REALMNAME as NM')
			->from('TBLREALM')
			->order_by('TBLREALM.REALMID', 'ASC')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function fetchAllRealmsForSUNamesOnly() {
		$this->utility->db_select();
		$query = $this->utility->select('TBLREALM.REALMID as ID, TBLREALM.REALMNAME as NM')
			->from('TBLREALM')
			->order_by('TBLREALM.REALMID', 'ASC')
			->get();
		if ($query->num_rows() == 0) {
			return false;
		} else {
			$results = $query->result_array();
			$realms = [];
			for ($i = 0; $i < count($results); $i++) {
				$realms[] = $results[$i]['NM'];
			}
			return $realms;
		}
	}
	/*********************************************************
	 * times
	 *********************************************************/
	public function timeForRoleExists($roleId, $timeId) {
		$this->utility->db_select();
		$count = $this->utility->from($this->TABLENAME)
			->where($this->COLUMN_ROLEID, intval($roleId))
			->where($this->COLUMN_TIMEID, intval($timeId))
			->where($this->COLUMN_PAGEID, -1)
			->where($this->COLUMN_REALMID, -1)
			->count_all_results();
		return $count == 0 ? false : true;
	}
	public function addTimeToRole($roleId, $timeId) {
		$this->utility->db_select();
		$exist = $this->timeForRoleExists(intval($roleId), intval($timeId));
		if (!$exist) {
			$this->utility->db_select();
			$data = array(
				$this->COLUMN_ROLEID => intval($roleId),
				$this->COLUMN_PAGEID => -1,
				$this->COLUMN_TIMEID => intval($timeId),
				$this->COLUMN_REALMID => -1);
			$this->utility->insert($this->TABLENAME, $data);
			return $this->utility->affected_rows() == 0 ? false : true;
		}
	}
	public function deleteTimeFromRole($roleId, $timeId) {
		$this->utility->db_select();
		$this->utility->where($this->COLUMN_ROLEID, intval($roleId))
			->where($this->COLUMN_PAGEID, -1)
			->where($this->COLUMN_TIMEID, intval($timeId))
			->where($this->COLUMN_REALMID, -1)
			->delete($this->TABLENAME);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
	public function fetchTimesForRole($roleId) {
		$this->utility->db_select();
		$query = $this->utility->select('TBLDSLBUCKET.'.$this->COLUMN_TIMEID.' as ID, TBLSYSTIME.TIMEVALUE as NM')
			->from($this->TABLENAME)
			->join('TBLSYSTIME', 'TBLDSLBUCKET.'.$this->COLUMN_TIMEID.' = TBLSYSTIME.TIMEID', 'left')
			->where('TBLDSLBUCKET.'.$this->COLUMN_ROLEID, intval($roleId))
			->where('TBLDSLBUCKET.'.$this->COLUMN_PAGEID, -1)
			->where('TBLDSLBUCKET.'.$this->COLUMN_REALMID, -1)
			->where('TBLSYSTIME.TIMEVALUE is not null')
			->order_by('TBLDSLBUCKET.'.$this->COLUMN_ID, 'ASC')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function deleteAllTimesFromRole($roleId) {
		$this->utility->db_select();
		$this->utility->where($this->COLUMN_ROLEID, intval($roleId))
			->where($this->COLUMN_PAGEID, -1)
			->where($this->COLUMN_REALMID, -1)
			->delete($this->TABLENAME);
		return $this->utility->affected_rows() == 0 ? false : true;
	}
}
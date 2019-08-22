<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sysuseractivitylog extends CI_Model {
	private $extras;
	public $DATATYPE_SYSUSER = "System User";
    public $DATATYPE_SYSUSERGROUP = "System User Group";
    public $DATATYPE_SYSIPACCOUNT = "System IP Account";
    public $DATATYPE_SERVICE = "Service";
    public $DATATYPE_REALM = "Realm";
    public $DATATYPE_IPADDRESS = "IP Address";
    /**************************************************
	 * added february 2017
	 * - for IPv6
	 **************************************************/
    public $DATATYPE_IPADDRESS_V6 = "IPv6 Address";
    /**************************************************
	 * /added february 2017
	 **************************************************/
    public $DATATYPE_NETADDRESS = "Net Address";
    public $DATATYPE_ILLEGALACCESS = "Illegal Access";
    public $DATATYPE_CABINET = "Cabinet";
    /**************************************************
	 * added april 2017
	 **************************************************/
    public $DATATYPE_VODPARAM = "VOD Param";
    public $DATATYPE_LOCATION = "Location";
    /**************************************************
	 * /added april 2017
	 **************************************************/
    public $ACTION_ACCESS = "Access";
    public $ACTION_CREATE = "Create";
    public $ACTION_MODIFY = "Modify";
    public $ACTION_DELETE = "Delete";
    public $ACTION_FREEUP = "Free up";
    public $ACTION_LOGOUT = "Force log out";
    public $ACTION_BLOCK = "Block";
    public $ACTION_UNBLOCK = "Unblock";
    public $ACTION_CHANGEPASSWORD = "Change password";
    public $DEFAULT_COLUMNS = ['DATA TYPE', 'ACTION', 'INFO', 'SYSTEM USER', 'IP ADDRESS', 'TIMESTAMP'];

	function __construct() {
		parent::__construct();
		$this->extras = $this->load->database('extras', true);
	}

	public function logSysuserIllegalPageAccess($page, $path, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_ILLEGALACCESS,
			'action' => $this->ACTION_ACCESS,
			'info' => $path,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logSysuserCreation($cn, $group, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_SYSUSER,
			'action' => $this->ACTION_CREATE,
			'info' => 'username='.$cn.', group='.$group,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logSysuserModification($cn, $group, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_SYSUSER,
			'action' => $this->ACTION_MODIFY,
			'info' => 'username='.$cn.', group='.$group,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);	
	}
	public function logSysuserDeletion($cn, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_SYSUSER,
			'action' => $this->ACTION_DELETE,
			'info' => 'username='.$cn,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);	
	}
	public function logSysuserForcedLogOut($cn, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_SYSUSER,
			'action' => $this->ACTION_LOGOUT,
			'info' => 'username='.$cn,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);	
	}
	public function logSysuserBlock($cn, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_SYSUSER,
			'action' => $this->ACTION_BLOCK,
			'info' => 'username='.$cn,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logSysuserUnblock($cn, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_SYSUSER,
			'action' => $this->ACTION_UNBLOCK,
			'info' => 'username='.$cn,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logSysuserChangePassword($cn, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_SYSUSER,
			'action' => $this->ACTION_CHANGEPASSWORD,
			'info' => 'username='.$cn,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logUsergroupCreation($group, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_SYSUSERGROUP,
			'action' => $this->ACTION_CREATE,
			'info' => 'group='.$group,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logUsergroupModification($group, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_SYSUSERGROUP,
			'action' => $this->ACTION_MODIFY,
			'info' => 'group='.$group,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logUsergroupDeletion($group, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_SYSUSERGROUP,
			'action' => $this->ACTION_DELETE,
			'info' => 'group='.$group,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logSysipCreation($sysip, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_SYSIPACCOUNT,
			'action' => $this->ACTION_CREATE,
			'info' => 'ipaddress='.$sysip,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);	
	}
	public function logSysipModification($sysip, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_SYSIPACCOUNT,
			'action' => $this->ACTION_MODIFY,
			'info' => 'ipaddress='.$sysip,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);	
	}
	public function logSysipDeletion($sysip, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_SYSIPACCOUNT,
			'action' => $this->ACTION_DELETE,
			'info' => 'ipaddress='.$sysip,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logServiceCreation($service, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_SERVICE,
			'action' => $this->ACTION_CREATE,
			'info' => 'service='.$service,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logServiceModification($service, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_SERVICE,
			'action' => $this->ACTION_MODIFY,
			'info' => 'service='.$service,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' =>is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);	
	}
	public function logServiceDeletion($service, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_SERVICE,
			'action' => $this->ACTION_DELETE,
			'info' => 'service='.$service,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' =>is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logRealmCreation($realm, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_REALM,
			'action' => $this->ACTION_CREATE,
			'info' => 'realm='.$realm,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logRealmModification($realm, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_REALM,
			'action' => $this->ACTION_MODIFY,
			'info' => 'realm='.$realm,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logRealmDeletion($realm, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_REALM,
			'action' => $this->ACTION_DELETE,
			'info' => 'realm='.$realm,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logIpAddressCreation($address, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_IPADDRESS,
			'action' => $this->ACTION_CREATE,
			'info' => 'ipaddress='.$address,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logIpAddressModification($address, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_IPADDRESS,
			'action' => $this->ACTION_MODIFY,
			'info' => 'ipaddress='.$address,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logIpAddressDeletion($address, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_IPADDRESS,
			'action' => $this->ACTION_DELETE,
			'info' => 'ipaddress='.$address,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logIpAddressFreeup($address, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_IPADDRESS,
			'action' => $this->ACTION_FREEUP,
			'info' => 'ipaddress='.$address,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	/**************************************************
	 * added february 2017
	 * - for IPv6
	 **************************************************/
	public function logIpV6AddressCreation($address, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_IPADDRESS_V6,
			'action' => $this->ACTION_CREATE,
			'info' => 'ipv6address='.$address,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logIpV6AddressModification($address, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_IPADDRESS_V6,
			'action' => $this->ACTION_MODIFY,
			'info' => 'ipv6address='.$address,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logIpV6AddressDeletion($address, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_IPADDRESS_V6,
			'action' => $this->ACTION_DELETE,
			'info' => 'ipv6address='.$address,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logIpV6AddressFreeup($address, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_IPADDRESS_V6,
			'action' => $this->ACTION_FREEUP,
			'info' => 'ipv6address='.$address,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	/**************************************************
	 * /added february 2017
	 **************************************************/
	public function logNetAddressCreation($address, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_NETADDRESS,
			'action' => $this->ACTION_CREATE,
			'info' => 'netaddress='.$address,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logNetAddressModification($address, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_NETADDRESS,
			'action' => $this->ACTION_MODIFY,
			'info' => 'netaddress='.$address,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logNetAddressDeletion($address, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_NETADDRESS,
			'action' => $this->ACTION_DELETE,
			'info' => 'netaddress='.$address,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logNetAddressFreeup($address, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_NETADDRESS,
			'action' => $this->ACTION_FREEUP,
			'info' => 'netaddress='.$address,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logCabinetCreation($cabinet, $bng, $vlan, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_CABINET,
			'action' => $this->ACTION_CREATE,
			'info' => 'cabinet='.$cabinet.', bng='.$bng.', vlan='.$vlan,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logCabinetModification($id, $cabinet, $bng, $vlan, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_CABINET,
			'action' => $this->ACTION_MODIFY,
			'info' => 'id='.$id.', cabinet='.$cabinet.', bng='.$bng.', vlan='.$vlan,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logCabinetDeletion($cabinet, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_CABINET,
			'action' => $this->ACTION_DELETE,
			'info' => 'cabinet='.$cabinet,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	/**************************************************
	 * added april 2017
	 **************************************************/
	public function logVodparamCreation($oldVod, $newName, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_VODPARAM,
			'action' => $this->ACTION_CREATE,
			'info' => 'old_vod='.$oldVod.', new_name='.$newName,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logVodparamModification($id, $oldVod, $newName, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_VODPARAM,
			'action' => $this->ACTION_MODIFY,
			'info' => 'id='.$id.', old_vod='.$oldVod.', new_name='.$newName,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logVodparamDeletion($oldVod, $newName, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_VODPARAM,
			'action' => $this->ACTION_DELETE,
			'info' => 'old_vod='.$oldVod.', new_name='.$newName,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logLocationCreation($location, $nasName, $nasIp, $nasCode, $nasDescription, $rmLocation, $rmDescription, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_LOCATION,
			'action' => $this->ACTION_CREATE,
			'info' => 'location='.$location.', nas_name='.$nasName.', nas_ip='.$nasIp.', nas_code='.$nasCode.', nas_description='.$nasDescription.
				', rm_location='.$rmLocation.', rm_description='.$rmDescription,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logLocationModification($id, $location, $nasName, $nasIp, $nasCode, $nasDescription, $rmLocation, $rmDescription, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_LOCATION,
			'action' => $this->ACTION_MODIFY,
			'info' => 'id='.$id.', location='.$location.', nas_name='.$nasName.', nas_ip='.$nasIp.', nas_code='.$nasCode.', nas_description='.$nasDescription.
				', rm_location='.$rmLocation.', rm_description='.$rmDescription,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	public function logLocationDeletion($location, $nasName, $nasIp, $nasCode, $nasDescription, $rmLocation, $rmDescription, $sysuser, $ipaddress, $date) {
		$this->extras->db_select();
		$data = array(
			'datatype' => $this->DATATYPE_LOCATION,
			'action' => $this->ACTION_DELETE,
			'info' => 'location='.$location.', nas_name='.$nasName.', nas_ip='.$nasIp.', nas_code='.$nasCode.', nas_description='.$nasDescription.
				', rm_location='.$rmLocation.', rm_description='.$rmDescription,
			'sysuser' => $sysuser,
			'ipaddress' => $ipaddress,
			'timestamp' => is_null($date) ? date('Y-m-d H:i:s', time()) : date('Y-m-d H:i:s', $date));
		$this->extras->insert('sysuser_activity_log', $data);
		return (($this->extras->affected_rows() == 0) ? false : true);
	}
	/**************************************************
	 * /added april 2017
	 **************************************************/
	public function findAllSysuserActivityLogs($start, $max) {
		$this->extras->db_select();
		$query = $this->extras->select('id, datatype, action, info, sysuser, ipaddress, timestamp')
			->from('sysuser_activity_log')
			->order_by('timestamp', 'desc')
			->limit($max, $start)
			->get();
		return $query->result_array();
	}
	public function countSysuserActivityLogs() {
		$this->extras->db_select();
		$count = $this->extras->count_all_results('sysuser_activity_log');
		return $count;
	}
	public function findAllSysuserActivityLogsWithParams($startdate, $enddate, $data, $action, $bySysuser, $ipaddress, $start, $max, $order) {
		//$order = array('column' => [column], 'dir' => [asc/desc])
		//date('Y-m-d H:i:s')
		$this->extras->db_select();
		$this->extras->select('id, datatype, action, info, sysuser, ipaddress, timestamp')
			->from('sysuser_activity_log');
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
		if ($ipaddress != null) {
			$this->extras->where('ipaddress', $ipaddress);
		}
		if ($data != null) {
			$this->extras->where('datatype', $data);
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
	public function countSysuserActivityLogsWithParams($startdate, $enddate, $data, $action, $bySysuser, $ipaddress) {
		//$order = array('column' => [column], 'dir' => [asc/desc])
		//date('Y-m-d H:i:s')
		$this->extras->db_select();
		$this->extras->from('sysuser_activity_log');
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
		if ($ipaddress != null) {
			$this->extras->where('ipaddress', $ipaddress);
		}
		if ($data != null) {
			$this->extras->where('datatype', $data);
		}
		$this->extras->order_by('timestamp', 'desc');
		$count = $this->extras->count_all_results();
		return $count;
	}

	public function getLastActionDate($username) {
		$this->extras->db_select();
		$query = $this->extras->select('timestamp')
			->from('sysuser_activity_log')
			->where('sysuser', $username)
			->order_by('timestamp', 'desc')
			->limit(1, 0)
			->get();
		return $query->num_rows() == 0 ? false : $query->row_array();
	}
}
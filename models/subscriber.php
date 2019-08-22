<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscriber extends CI_Model {
	private $extras;
	public $STATUS_CODES = ['A', 'D', 'E', 'T', 'H', 'K', 'B'];
	public $STATUS_STRINGS = ['Active', 'Deactivated', 'Expired', 'Terminated', 'On Hold', 'Barred', 'Mark for Deletion'];
	public $DEFAULT_COLUMNS = ['ACCOUNT TYPE', 'REALM', 'USERNAME', 'ACCOUNT PLAN', 'STATUS', 'SERVICE', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2',
        'ORDER NUMBER', 'SERVICE NUMBER', 'CUSTOMER NAME', 'IP ADDRESS', 'NET ADDRESS', 'REMARKS'];

	function __construct() {
		parent::__construct();
		$this->extras = $this->load->database('extras', true);
	}

	public function findAll($start, $max) {
		$this->extras->db_select();
		$query = $this->extras->select('id, cn, rbbandwidth, rbactivecount2, rbinactivecount2, rbdefaultiplocation, rbusergroup, rballowedrealms, rbsvcdesc,
			rbpagetitle, rbactivecount, rbinactivecount, rbstatus, rbservicenumber, rbserviceno, rbchangestatusfrom, rbsecondaryaccount, usersecret,
			rbweeklylimit, rbunlimitedaccess, rbtimeslot, rbstaticip, rbservicessg, rbservicepvc, rbordernumber, rbservicedslam, rbsatype, rbremarks,
			rbreason, rbrealm, rbprofileenabled, rbnumberofsession, rbmultistatic, rbmothername, rbmotherbday, rborderno, rbmonthlylimit, rblastname,
			rbipaddress, rbinstallationaddress, rbhintquestion, rbhintanswer, rbfirstname, rbemail5, rbemail4, rbemail3, rbenabled, rbemail2, rbemail,
			rbdefaultreply, rbdailylimit, rbcustomername, rbcreateddate, rbcreatedby, rbadditionalservice5, rbcustname, rbadditionalservice4,
			rbadditionalservice3, rbadditionalservice2, rbadditionalservice1, rbchangestatusdate, rbchangestatusby, rbactivateddate, rbactivatedby,
			rbaccountstatus, rbusername, rbsvccode2, rbsvccode, rbaccountplan, last_updated_date')
			->from('subscriber')
			->limit($max, $start)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSubscribers() {
		$this->extras->db_select();
		$count = $this->extras->count_all_results('subscriber');
		return $count;
	}
	public function findSubscriber($cn) {
		$this->extras->db_select();
		$query = $this->extras->select('id, cn, rbbandwidth, rbactivecount2, rbinactivecount2, rbdefaultiplocation, rbusergroup, rballowedrealms, rbsvcdesc,
			rbpagetitle, rbactivecount, rbinactivecount, rbstatus, rbservicenumber, rbserviceno, rbchangestatusfrom, rbsecondaryaccount, usersecret,
			rbweeklylimit, rbunlimitedaccess, rbtimeslot, rbstaticip, rbservicessg, rbservicepvc, rbordernumber, rbservicedslam, rbsatype, rbremarks,
			rbreason, rbrealm, rbprofileenabled, rbnumberofsession, rbmultistatic, rbmothername, rbmotherbday, rborderno, rbmonthlylimit, rblastname,
			rbipaddress, rbinstallationaddress, rbhintquestion, rbhintanswer, rbfirstname, rbemail5, rbemail4, rbemail3, rbenabled, rbemail2, rbemail,
			rbdefaultreply, rbdailylimit, rbcustomername, rbcreateddate, rbcreatedby, rbadditionalservice5, rbcustname, rbadditionalservice4,
			rbadditionalservice3, rbadditionalservice2, rbadditionalservice1, rbchangestatusdate, rbchangestatusby, rbactivateddate, rbactivatedby,
			rbaccountstatus, rbusername, rbsvccode2, rbsvccode, rbaccountplan, last_updated_date')
			->from('subscriber')
			->where('cn', $cn)
			->get();
		return $query->num_rows() == 0 ? false : $query->row_array();
	}
	public function findSubscribersInRealm($realm, $start, $max) {
		$this->extras->db_select();
		$query = $this->extras->select('id, cn, rbbandwidth, rbactivecount2, rbinactivecount2, rbdefaultiplocation, rbusergroup, rballowedrealms, rbsvcdesc,
			rbpagetitle, rbactivecount, rbinactivecount, rbstatus, rbservicenumber, rbserviceno, rbchangestatusfrom, rbsecondaryaccount, usersecret,
			rbweeklylimit, rbunlimitedaccess, rbtimeslot, rbstaticip, rbservicessg, rbservicepvc, rbordernumber, rbservicedslam, rbsatype, rbremarks,
			rbreason, rbrealm, rbprofileenabled, rbnumberofsession, rbmultistatic, rbmothername, rbmotherbday, rborderno, rbmonthlylimit, rblastname,
			rbipaddress, rbinstallationaddress, rbhintquestion, rbhintanswer, rbfirstname, rbemail5, rbemail4, rbemail3, rbenabled, rbemail2, rbemail,
			rbdefaultreply, rbdailylimit, rbcustomername, rbcreateddate, rbcreatedby, rbadditionalservice5, rbcustname, rbadditionalservice4,
			rbadditionalservice3, rbadditionalservice2, rbadditionalservice1, rbchangestatusdate, rbchangestatusby, rbactivateddate, rbactivatedby,
			rbaccountstatus, rbusername, rbsvccode2, rbsvccode, rbaccountplan, last_updated_date')
			->from('subscriber')
			->where('rbrealm', $realm)
			->limit($max, $start)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSubscribersInRealm($realm) {
		$this->extras->db_select();
		$count = $this->extras->from('subscriber')
			->where('rbrealm', $realm)
			->count_all_results();
		return $count;
	}
	public function reportSubscribersWithStatus($realm, $status, $start, $max) {
		$this->extras->db_select();
		$this->extras->select('id, cn, rbbandwidth, rbactivecount2, rbinactivecount2, rbdefaultiplocation, rbusergroup, rballowedrealms, rbsvcdesc,
			rbpagetitle, rbactivecount, rbinactivecount, rbstatus, rbservicenumber, rbserviceno, rbchangestatusfrom, rbsecondaryaccount, usersecret,
			rbweeklylimit, rbunlimitedaccess, rbtimeslot, rbstaticip, rbservicessg, rbservicepvc, rbordernumber, rbservicedslam, rbsatype, rbremarks,
			rbreason, rbrealm, rbprofileenabled, rbnumberofsession, rbmultistatic, rbmothername, rbmotherbday, rborderno, rbmonthlylimit, rblastname,
			rbipaddress, rbinstallationaddress, rbhintquestion, rbhintanswer, rbfirstname, rbemail5, rbemail4, rbemail3, rbenabled, rbemail2, rbemail,
			rbdefaultreply, rbdailylimit, rbcustomername, rbcreateddate, rbcreatedby, rbadditionalservice5, rbcustname, rbadditionalservice4,
			rbadditionalservice3, rbadditionalservice2, rbadditionalservice1, rbchangestatusdate, rbchangestatusby, rbactivateddate, rbactivatedby,
			rbaccountstatus, rbusername, rbsvccode2, rbsvccode, rbaccountplan, last_updated_date')
			->from('subscriber');
		if ($realm != null) {
			$this->extras->where('rbrealm', $realm);
		}
		$query = $this->extras->where('rbstatus', $status)
			->limit($max, $start)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSubscribersWithStatus($realm, $status) {
		$this->extras->db_select();
		$this->extras->from('subscriber');
		if ($realm != null) {
			$this->extras->where('rbrealm', $realm);
		}
		$count = $this->extras->where('rbstatus', $status)
			->count_all_results();
		return $count;
	}
	public function reportSubscribersWithService($realm, $service, $start, $max) {
		$this->extras->db_select();
		$this->extras->select('id, cn, rbbandwidth, rbactivecount2, rbinactivecount2, rbdefaultiplocation, rbusergroup, rballowedrealms, rbsvcdesc,
			rbpagetitle, rbactivecount, rbinactivecount, rbstatus, rbservicenumber, rbserviceno, rbchangestatusfrom, rbsecondaryaccount, usersecret,
			rbweeklylimit, rbunlimitedaccess, rbtimeslot, rbstaticip, rbservicessg, rbservicepvc, rbordernumber, rbservicedslam, rbsatype, rbremarks,
			rbreason, rbrealm, rbprofileenabled, rbnumberofsession, rbmultistatic, rbmothername, rbmotherbday, rborderno, rbmonthlylimit, rblastname,
			rbipaddress, rbinstallationaddress, rbhintquestion, rbhintanswer, rbfirstname, rbemail5, rbemail4, rbemail3, rbenabled, rbemail2, rbemail,
			rbdefaultreply, rbdailylimit, rbcustomername, rbcreateddate, rbcreatedby, rbadditionalservice5, rbcustname, rbadditionalservice4,
			rbadditionalservice3, rbadditionalservice2, rbadditionalservice1, rbchangestatusdate, rbchangestatusby, rbactivateddate, rbactivatedby,
			rbaccountstatus, rbusername, rbsvccode2, rbsvccode, rbaccountplan, last_updated_date')
			->from('subscriber');
		if ($realm != null) {
			$this->extras->where('rbrealm', $realm);
		}
		$query = $this->extras->where('rbsvccode = "'.$service.'" or rbadditionalservice1 = "'.$service.'" or rbadditionalservice2 = "'.$service.'"')
			->limit($max, $start)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSubscribersWithService($realm, $service) {
		$this->extras->db_select();
		$this->extras->from('subscriber');
		if ($realm != null) {
			$this->extras->where('rbrealm', $realm);
		}
		$count = $this->extras->where('rbsvccode = "'.$service.'" or rbadditionalservice1 = "'.$service.'" or rbadditionalservice2 = "'.$service.'"')
			->count_all_results();
		return $count;
	}
	public function reportSubscribersCreatedOnDate($realm, $date, $start, $max) {
		$this->extras->db_select();
		$this->extras->select('id, cn, rbbandwidth, rbactivecount2, rbinactivecount2, rbdefaultiplocation, rbusergroup, rballowedrealms, rbsvcdesc,
			rbpagetitle, rbactivecount, rbinactivecount, rbstatus, rbservicenumber, rbserviceno, rbchangestatusfrom, rbsecondaryaccount, usersecret,
			rbweeklylimit, rbunlimitedaccess, rbtimeslot, rbstaticip, rbservicessg, rbservicepvc, rbordernumber, rbservicedslam, rbsatype, rbremarks,
			rbreason, rbrealm, rbprofileenabled, rbnumberofsession, rbmultistatic, rbmothername, rbmotherbday, rborderno, rbmonthlylimit, rblastname,
			rbipaddress, rbinstallationaddress, rbhintquestion, rbhintanswer, rbfirstname, rbemail5, rbemail4, rbemail3, rbenabled, rbemail2, rbemail,
			rbdefaultreply, rbdailylimit, rbcustomername, rbcreateddate, rbcreatedby, rbadditionalservice5, rbcustname, rbadditionalservice4,
			rbadditionalservice3, rbadditionalservice2, rbadditionalservice1, rbchangestatusdate, rbchangestatusby, rbactivateddate, rbactivatedby,
			rbaccountstatus, rbusername, rbsvccode2, rbsvccode, rbaccountplan, last_updated_date')
			->from('subscriber');
		if ($realm != null) {
			$this->extras->where('rbrealm', $realm);
		}
		$query = $this->extras->where('DATE(rbcreateddate)', date('Y-m-d H:i:s', $date))
			->limit($max, $start)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSubscribersCreatedOnDate($realm, $date) {
		$this->extras->db_select();
		$this->extras->from('subscriber');
		if ($realm != null) {
			$this->extras->where('rbrealm', $realm);
		}
		$count = $this->extras->where('DATE(rbcreateddate)', date('Y-m-d H:i:s', $date))
			->count_all_results();
		return $count;
	}
	public function reportSubscribersCreatedWithinDates($realm, $datestart, $dateend, $start, $max) {
		$this->extras->db_select();
		$this->extras->select('id, cn, rbbandwidth, rbactivecount2, rbinactivecount2, rbdefaultiplocation, rbusergroup, rballowedrealms, rbsvcdesc,
			rbpagetitle, rbactivecount, rbinactivecount, rbstatus, rbservicenumber, rbserviceno, rbchangestatusfrom, rbsecondaryaccount, usersecret,
			rbweeklylimit, rbunlimitedaccess, rbtimeslot, rbstaticip, rbservicessg, rbservicepvc, rbordernumber, rbservicedslam, rbsatype, rbremarks,
			rbreason, rbrealm, rbprofileenabled, rbnumberofsession, rbmultistatic, rbmothername, rbmotherbday, rborderno, rbmonthlylimit, rblastname,
			rbipaddress, rbinstallationaddress, rbhintquestion, rbhintanswer, rbfirstname, rbemail5, rbemail4, rbemail3, rbenabled, rbemail2, rbemail,
			rbdefaultreply, rbdailylimit, rbcustomername, rbcreateddate, rbcreatedby, rbadditionalservice5, rbcustname, rbadditionalservice4,
			rbadditionalservice3, rbadditionalservice2, rbadditionalservice1, rbchangestatusdate, rbchangestatusby, rbactivateddate, rbactivatedby,
			rbaccountstatus, rbusername, rbsvccode2, rbsvccode, rbaccountplan, last_updated_date')
			->from('subscriber');
		if ($realm != null) {
			$this->extras->where('rbrealm', $realm);
		}
		$query = $this->extras->where('(DATE(rbcreateddate) between "'.date('Y-m-d H:i:s', $datestart).'" and "'.date('Y-m-d H:i:s', $dateend).'")')
			->limit($max, $start)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSubscribersCreatedWithinDates($realm, $datestart, $dateend) {
		$this->extras->db_select();
		$this->extras->from('subscriber');
		if ($realm != null) {
			$this->extras->where('rbrealm', $realm);
		}
		$count = $this->extras->where('(DATE(rbcreateddate) between "'.date('Y-m-d H:i:s', $datestart).'" and "'.date('Y-m-d H:i:s', $dateend).'")')
			->count_all_results();
		return $count;
	}
	public function reportSubscribersWithStaticIp($realm, $start, $max) {
		$this->extras->db_select();
		$this->extras->select('id, cn, rbbandwidth, rbactivecount2, rbinactivecount2, rbdefaultiplocation, rbusergroup, rballowedrealms, rbsvcdesc,
			rbpagetitle, rbactivecount, rbinactivecount, rbstatus, rbservicenumber, rbserviceno, rbchangestatusfrom, rbsecondaryaccount, usersecret,
			rbweeklylimit, rbunlimitedaccess, rbtimeslot, rbstaticip, rbservicessg, rbservicepvc, rbordernumber, rbservicedslam, rbsatype, rbremarks,
			rbreason, rbrealm, rbprofileenabled, rbnumberofsession, rbmultistatic, rbmothername, rbmotherbday, rborderno, rbmonthlylimit, rblastname,
			rbipaddress, rbinstallationaddress, rbhintquestion, rbhintanswer, rbfirstname, rbemail5, rbemail4, rbemail3, rbenabled, rbemail2, rbemail,
			rbdefaultreply, rbdailylimit, rbcustomername, rbcreateddate, rbcreatedby, rbadditionalservice5, rbcustname, rbadditionalservice4,
			rbadditionalservice3, rbadditionalservice2, rbadditionalservice1, rbchangestatusdate, rbchangestatusby, rbactivateddate, rbactivatedby,
			rbaccountstatus, rbusername, rbsvccode2, rbsvccode, rbaccountplan, last_updated_date')
			->from('subscriber');
		if ($realm != null) {
			$this->extras->where('rbrealm', $realm);
		}
		$query = $this->extras->where('rbipaddress is not null')
			->limit($max, $start)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSubscribersWithStaticIp($realm) {
		$this->extras->db_select();
		$this->extras->from('subscriber');
		if ($realm != null) {
			$this->extras->where('rbrealm', $realm);
		}
		$count = $this->extras->where('rbipaddress is not null')
			->count_all_results();
		return $count;
	}
	public function reportSubscribersWithStaticIpAndMultistaticIp($realm, $start, $max) {
		$this->extras->db_select();
		$this->extras->select('id, cn, rbbandwidth, rbactivecount2, rbinactivecount2, rbdefaultiplocation, rbusergroup, rballowedrealms, rbsvcdesc,
			rbpagetitle, rbactivecount, rbinactivecount, rbstatus, rbservicenumber, rbserviceno, rbchangestatusfrom, rbsecondaryaccount, usersecret,
			rbweeklylimit, rbunlimitedaccess, rbtimeslot, rbstaticip, rbservicessg, rbservicepvc, rbordernumber, rbservicedslam, rbsatype, rbremarks,
			rbreason, rbrealm, rbprofileenabled, rbnumberofsession, rbmultistatic, rbmothername, rbmotherbday, rborderno, rbmonthlylimit, rblastname,
			rbipaddress, rbinstallationaddress, rbhintquestion, rbhintanswer, rbfirstname, rbemail5, rbemail4, rbemail3, rbenabled, rbemail2, rbemail,
			rbdefaultreply, rbdailylimit, rbcustomername, rbcreateddate, rbcreatedby, rbadditionalservice5, rbcustname, rbadditionalservice4,
			rbadditionalservice3, rbadditionalservice2, rbadditionalservice1, rbchangestatusdate, rbchangestatusby, rbactivateddate, rbactivatedby,
			rbaccountstatus, rbusername, rbsvccode2, rbsvccode, rbaccountplan, last_updated_date')
			->from('subscriber');
		if ($realm != null) {
			$this->extras->where('rbrealm', $realm);
		}
		$query = $this->extras->where('rbipaddress is not null')
			->where('rbmultistatic is not null')
			->limit($max, $start)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSubscribersWithStaticIpAndMultistaticIp($realm) {
		$this->extras->db_select();
		$this->extras->from('subscriber');
		if ($realm != null) {
			$this->extras->where('rbrealm', $realm);
		}
		$count = $this->extras->where('rbipaddress is not null')
			->where('rbmultistatic is not null')
			->count_all_results();
		return $count;
	}
	public function reportSubscribersWithBandwidth($realm, $bandwidth, $start, $max) {
		$this->extras->db_select();
		$this->extras->select('id, cn, rbbandwidth, rbactivecount2, rbinactivecount2, rbdefaultiplocation, rbusergroup, rballowedrealms, rbsvcdesc,
			rbpagetitle, rbactivecount, rbinactivecount, rbstatus, rbservicenumber, rbserviceno, rbchangestatusfrom, rbsecondaryaccount, usersecret,
			rbweeklylimit, rbunlimitedaccess, rbtimeslot, rbstaticip, rbservicessg, rbservicepvc, rbordernumber, rbservicedslam, rbsatype, rbremarks,
			rbreason, rbrealm, rbprofileenabled, rbnumberofsession, rbmultistatic, rbmothername, rbmotherbday, rborderno, rbmonthlylimit, rblastname,
			rbipaddress, rbinstallationaddress, rbhintquestion, rbhintanswer, rbfirstname, rbemail5, rbemail4, rbemail3, rbenabled, rbemail2, rbemail,
			rbdefaultreply, rbdailylimit, rbcustomername, rbcreateddate, rbcreatedby, rbadditionalservice5, rbcustname, rbadditionalservice4,
			rbadditionalservice3, rbadditionalservice2, rbadditionalservice1, rbchangestatusdate, rbchangestatusby, rbactivateddate, rbactivatedby,
			rbaccountstatus, rbusername, rbsvccode2, rbsvccode, rbaccountplan, last_updated_date')
			->from('subscriber');
		if ($realm != null) {
			$this->extras->where('rbrealm', $realm);
		}
		$query = $this->extras->where('rbbandwidth', $bandwidth)
			->limit($max, $start)
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function countSubscribersWithBandwidth($realm, $bandwidth) {
		$this->extras->db_select();
		$this->extras->from('subscriber');
		if ($realm != null) {
			$this->extras->where('rbrealm', $realm);
		}
		$count = $this->extras->where('rbbandwidth', $bandwidth)
			->count_all_results();
		return $count;
	}
	public function countActiveSubscribersWithService($realm, $service) {
		$this->extras->db_select();
		$this->extras->from('subscriber')
			->where('rbstatus', 'A')
			->where('rbsvccode = "'.$service.'" or rbadditionalservice1 = "'.$service.'" or rbadditionalservice2 = "'.$service.'"');
		if ($realm != null) {
			$this->extras->where('rbrealm', $realm);
		}
		$count = $this->extras->count_all_results();
		return $count;
	}
	public function countInactiveSubscribersWithService($service, $realm) {
		$this->extras->db_select();
		$this->extras->from('subscriber')
			->where('rbstatus !=', 'A')
			->where('rbsvccode = "'.$service.'" or rbadditionalservice1 = "'.$service.'" or rbadditionalservice2 = "'.$service.'"');
		if ($realm != null) {
			$this->extras->where('rbrealm', $realm);
		}
		$count = $this->extras->count_all_results();
		return $count;
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Model {
	//defaults
	public $SUPERUSER = 'vinadmin';
	public $MAXLOGINTRIES = 3;
	public $SESSIONEXPIRYINMINUTES = 30;
	public $PASSWORDVALIDITYINDAYS = 30;
	public $PASSWORDHISTORYSIZE = 5;
	public $PASSWORDMINLENGTH = 8;
	public $PASSWORDREQUIRENUMBER = true;
	public $PASSWORDREQUIRESYMBOL = true;
	public $PASSWORDALLOWUSERNAME = false;

	function __construct() {
		parent::__construct();
	}

	public function loadFromFile() {
		$config = $_SERVER['DOCUMENT_ROOT'].'/sysconfig/config.txt';
		$cfg = null;
		$fh = @fopen($config, 'r');
		if ($fh !== false) {
			while(($buffer = fgets($fh, 4096)) !== false) {
				$lines[] = trim($buffer);
			}
			$superuser = explode('=', trim($lines[0]));
			$logintries = explode('=', trim($lines[1]));
			$sessionexpiry = explode('=', trim($lines[2]));
			$passwordvalidity = explode('=', trim($lines[3]));
			$passwordhistorysize = explode('=', trim($lines[4]));
			$passwordminlength = explode('=', trim($lines[5]));
			$passwordrequiresymbol = explode('=', trim($lines[6]));
			$passwordrequirenumber = explode('=', trim($lines[7]));
			$passwordallowusername = explode('=', trim($lines[8]));
			$enablenpm = explode('=', trim($lines[9]));
			$npmhost = explode('=', trim($lines[10]));
			$npmport = explode('=', trim($lines[11]));
			$npmlogin = explode('=', trim($lines[12]));
			$npmpassword = explode('=', trim($lines[13]));
			$npmapi = explode('=', trim($lines[14]));
			$npmtimeout = explode('=', trim($lines[15]));
			$usesessiontable2 = explode('=', trim($lines[16]));
			$sessionhost = explode('=', trim($lines[17]));
			$sessionport = explode('=', trim($lines[18]));
			$sessionschema = explode('=', trim($lines[19]));
			$sessionusername = explode('=', trim($lines[20]));
			$sessionpassword = explode('=', trim($lines[21]));
			$sessionhost2 = explode('=', trim($lines[22]));
			$sessionport2 = explode('=', trim($lines[23]));
			$sessionschema2 = explode('=', trim($lines[24]));
			$sessionusername2 = explode('=', trim($lines[25]));
			$sessionpassword2 = explode('=', trim($lines[26]));
			$tblmconchost = explode('=', trim($lines[27]));
			$tblmconcport = explode('=', trim($lines[28]));
			$tblmconcschema = explode('=', trim($lines[29]));
			$tblmconcusername = explode('=', trim($lines[30]));
			$tblmconcpassword = explode('=', trim($lines[31]));
			$tblmconchost2 = explode('=', trim($lines[32]));
			$tblmconcport2 = explode('=', trim($lines[33]));
			$tblmconcschema2 = explode('=', trim($lines[34]));
			$tblmconcusername2 = explode('=', trim($lines[35]));
			$tblmconcpassword2 = explode('=', trim($lines[36]));
			$tblmcorehost = explode('=', trim($lines[37]));
			$tblmcoreport = explode('=', trim($lines[38]));
			$tblmcoreschema = explode('=', trim($lines[39]));
			$tblmcoreusername = explode('=', trim($lines[40]));
			$tblmcorepassword = explode('=', trim($lines[41]));
			$tblmcorehost2 = explode('=', trim($lines[42]));
			$tblmcoreport2 = explode('=', trim($lines[43]));
			$tblmcoreschema2 = explode('=', trim($lines[44]));
			$tblmcoreusername2 = explode('=', trim($lines[45]));
			$tblmcorepassword2 = explode('=', trim($lines[46]));
			$deletesessionapihost = explode('=', trim($lines[47]));
			$deletesessionapiport = explode('=', trim($lines[48]));
			$deletesessionapistub = explode('=', trim($lines[49]));
			$deletesessionapihost2 = explode('=', trim($lines[50]));
			$deletesessionapiport2 = explode('=', trim($lines[51]));
			$deletesessionapistub2 = explode('=', trim($lines[52]));
			$rmapihost = explode('=', trim($lines[53]));
			$rmapiport = explode('=', trim($lines[54]));
			$rmapistub = explode('=', trim($lines[55]));
			$rmdbhost = explode('=', trim($lines[56]));
			$rmdbport = explode('=', trim($lines[57]));
			$rmdbschema = explode('=', trim($lines[58]));
			$rmdbusername = explode('=', trim($lines[59]));
			$rmdbpassword = explode('=', trim($lines[60]));
			$detourapihost = explode('=', trim($lines[61]));
			$detourapiport = explode('=', trim($lines[62]));
			$detourapistub = explode('=', trim($lines[63]));
			$vodapihost = explode('=', trim($lines[64]));
			$vodapiport = explode('=', trim($lines[65]));
			$vodapistub = explode('=', trim($lines[66]));
			$usesecondaryrmapi = explode('=', trim($lines[67]));
			$rmsecondaryapihost = explode('=', trim($lines[68]));
			$rmsecondaryapiport = explode('=', trim($lines[69]));
			$rmsecondaryapistub = explode('=', trim($lines[70]));
			$usesecondaryrmdb = explode('=', trim($lines[71]));
			$rmsecondarydbhost = explode('=', trim($lines[72]));
			$rmsecondarydbport = explode('=', trim($lines[73]));
			$rmsecondarydbschema = explode('=', trim($lines[74]));
			$rmsecondarydbusername = explode('=', trim($lines[75]));
			$rmsecondarydbpassword = explode('=', trim($lines[76]));
			$useapimysql = explode('=', trim($lines[77]));
			$apimysqlhost = explode('=', trim($lines[78]));
			$apimysqldatabase = explode('=', trim($lines[79]));
			$apimysqlusername = explode('=', trim($lines[80]));
			$apimysqlpassword = explode('=', trim($lines[81]));

			$cfg = array(
				$superuser[0] => trim($superuser[1]),
				$logintries[0] => trim($logintries[1]),
				$sessionexpiry[0] => trim($sessionexpiry[1]),
				$passwordvalidity[0] => trim($passwordvalidity[1]),
				$passwordhistorysize[0] => trim($passwordhistorysize[1]),
				$passwordminlength[0] => trim($passwordminlength[1]),
				$passwordrequiresymbol[0] => trim($passwordrequiresymbol[1]),
				$passwordrequirenumber[0] => trim($passwordrequirenumber[1]),
				$passwordallowusername[0] => trim($passwordallowusername[1]),
				$enablenpm[0] => trim($enablenpm[1]),
				$npmhost[0] => trim($npmhost[1]),
				$npmport[0] => trim($npmport[1]),
				$npmlogin[0] => trim($npmlogin[1]),
				$npmpassword[0] => trim($npmpassword[1]),
				$npmapi[0] => trim($npmapi[1]),
				$npmtimeout[0] => trim($npmtimeout[1]),
				$usesessiontable2[0] => trim($usesessiontable2[1]),
				$sessionhost[0] => trim($sessionhost[1]),
				$sessionport[0] => trim($sessionport[1]),
				$sessionschema[0] => trim($sessionschema[1]),
				$sessionusername[0] => trim($sessionusername[1]),
				$sessionpassword[0] => trim($sessionpassword[1]),
				$sessionhost2[0] => trim($sessionhost2[1]),
				$sessionport2[0] => trim($sessionport2[1]),
				$sessionschema2[0] => trim($sessionschema2[1]),
				$sessionusername2[0] => trim($sessionusername2[1]),
				$sessionpassword2[0] => trim($sessionpassword2[1]),
				$tblmconchost[0] => trim($tblmconchost[1]),
				$tblmconcport[0] => trim($tblmconcport[1]),
				$tblmconcschema[0] => trim($tblmconcschema[1]),
				$tblmconcusername[0] => trim($tblmconcusername[1]),
				$tblmconcpassword[0] => trim($tblmconcpassword[1]),
				$tblmconchost2[0] => trim($tblmconchost2[1]),
				$tblmconcport2[0] => trim($tblmconcport2[1]),
				$tblmconcschema2[0] => trim($tblmconcschema2[1]),
				$tblmconcusername2[0] => trim($tblmconcusername2[1]),
				$tblmconcpassword2[0] => trim($tblmconcpassword2[1]),
				$tblmcorehost[0] => trim($tblmcorehost[1]),
				$tblmcoreport[0] => trim($tblmcoreport[1]),
				$tblmcoreschema[0] => trim($tblmcoreschema[1]),
				$tblmcoreusername[0] => trim($tblmcoreusername[1]),
				$tblmcorepassword[0] => trim($tblmcorepassword[1]),
				$tblmcorehost2[0] => trim($tblmcorehost2[1]),
				$tblmcoreport2[0] => trim($tblmcoreport2[1]),
				$tblmcoreschema2[0] => trim($tblmcoreschema2[1]),
				$tblmcoreusername2[0] => trim($tblmcoreusername2[1]),
				$tblmcorepassword2[0] => trim($tblmcorepassword2[1]),
				$deletesessionapihost[0] => trim($deletesessionapihost[1]),
				$deletesessionapiport[0] => trim($deletesessionapiport[1]),
				$deletesessionapistub[0] => trim($deletesessionapistub[1]),
				$deletesessionapihost2[0] => trim($deletesessionapihost2[1]),
				$deletesessionapiport2[0] => trim($deletesessionapiport2[1]),
				$deletesessionapistub2[0] => trim($deletesessionapistub2[1]),
				$rmapihost[0] => trim($rmapihost[1]),
				$rmapiport[0] => trim($rmapiport[1]),
				$rmapistub[0] => trim($rmapistub[1]),
				$rmdbhost[0] => trim($rmdbhost[1]),
				$rmdbport[0] => trim($rmdbport[1]),
				$rmdbschema[0] => trim($rmdbschema[1]),
				$rmdbusername[0] => trim($rmdbusername[1]),
				$rmdbpassword[0] => trim($rmdbpassword[1]),
				$detourapihost[0] => trim($detourapihost[1]),
				$detourapiport[0] => trim($detourapiport[1]),
				$detourapistub[0] => trim($detourapistub[1]),
				$vodapihost[0] => trim($vodapihost[1]),
				$vodapiport[0] => trim($vodapiport[1]),
				$vodapistub[0] => trim($vodapistub[1]),
				$usesecondaryrmapi[0] => trim($usesecondaryrmapi[1]),
				$rmsecondaryapihost[0] => trim($rmsecondaryapihost[1]),
				$rmsecondaryapiport[0] => trim($rmsecondaryapiport[1]),
				$rmsecondaryapistub[0] => trim($rmsecondaryapistub[1]),
				$usesecondaryrmdb[0] => trim($usesecondaryrmdb[1]),
				$rmsecondarydbhost[0] => trim($rmsecondarydbhost[1]),
				$rmsecondarydbport[0] => trim($rmsecondarydbport[1]),
				$rmsecondarydbschema[0] => trim($rmsecondarydbschema[1]),
				$rmsecondarydbusername[0] => trim($rmsecondarydbusername[1]),
				$rmsecondarydbpassword[0] => trim($rmsecondarydbpassword[1]),
				$useapimysql[0] => trim($useapimysql[1]),
				$apimysqlhost[0] => trim($apimysqlhost[1]),
				$apimysqldatabase[0] => trim($apimysqldatabase[1]),
				$apimysqlusername[0] => trim($apimysqlusername[1]),
				$apimysqlpassword[0] => trim($apimysqlpassword[1])
			);
		}
		return $cfg;
	}
}
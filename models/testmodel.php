<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testmodel extends CI_Model {
	private $main;
	private $extras;

	public $variable = "THIS";

	function __construct() {
		parent::__construct();
		$this->main = $this->load->database('utility', true);
		$this->extras = $this->load->database('extras', true);
	}
	
	public function getUser1() {
		$this->main->db_select();
		$query = $this->main->select('id, username, password')
			->from('user')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function getUser2() {
		$this->extras->db_select();
		$query = $this->extras->select('id, parent, first_name, last_name')
			->from('test_table')
			->get();
		return $query->num_rows() == 0 ? false : $query->result_array();
	}
	public function dateTest() {
		$this->extras->db_select();
		$query = $this->extras->select('*')
			->from('sysuser_activity_log')
			->where('DATE(timestamp) <', date("Y-m-d")) //date('yyyy-mm-dd hh:mm:ss')
			->get();
		return $query->result_array();
	}
	public static function staticfxn() {
		$str1 = 'TEST';
		$str2 = 'TEST';
		//echo "from static function: ".date('Y-m-d H:i:s');
		echo $str1 == $str2 ? 'same' : 'not';
	}
	public function sumTest() {
		$this->extras->db_select();
		$query = $this->extras->select_sum('user_id')
			->from('expense')
			->get();
		$row = $query->row_array();
		return $row['user_id'];
	}
	public function insertToSysuser() {
		$this->extras->db_select();
		$data = array(
			array(
				'cn' => 'cn1', 'rbusername' => 'username1', 'rbusergroup' => 'usergroupA', 'usersecret' => 'password1', 'logged_in' => 0, 'session_id' => '',
				'blocked' => 0, 'login_tries' => 0, 'require_password_change' => 0, 'last_password_change_date' => null, 
				'modified_date' => date('Y-m-d H:i:s', mktime()), 'created_date' => date('Y-m-d H:i:s', mktime())),
			array(
				'cn' => 'cn2', 'rbusername' => 'username2', 'rbusergroup' => 'usergroupA', 'usersecret' => 'password1', 'logged_in' => 0, 'session_id' => '',
				'blocked' => 0, 'login_tries' => 0, 'require_password_change' => 0, 'last_password_change_date' => null,
				'modified_date' => date('Y-m-d H:i:s', mktime()), 'created_date' => date('Y-m-d H:i:s', mktime())),
			array(
				'cn' => 'cn3', 'rbusername' => 'username3', 'rbusergroup' => 'usergroupB', 'usersecret' => 'password1', 'logged_in' => 0, 'session_id' => '',
				'blocked' => 0, 'login_tries' => 0, 'require_password_change' => 0, 'last_password_change_date' => null, 
				'modified_date' => date('Y-m-d H:i:s', mktime()), 'created_date' => date('Y-m-d H:i:s', mktime())),
			array(
				'cn' => 'cn4', 'rbusername' => 'username4', 'rbusergroup' => 'usergroupB', 'usersecret' => 'password1', 'logged_in' => 0, 'session_id' => '',
				'blocked' => 0, 'login_tries' => 0, 'require_password_change' => 0, 'last_password_change_date' => null, 
				'modified_date' => date('Y-m-d H:i:s', mktime()), 'created_date' => date('Y-m-d H:i:s', mktime())),
			array(
				'cn' => 'cn5', 'rbusername' => 'username5', 'rbusergroup' => 'usergroupC', 'usersecret' => 'password1', 'logged_in' => 0, 'session_id' => '',
				'blocked' => 0, 'login_tries' => 0, 'require_password_change' => 0, 'last_password_change_date' => null, 
				'modified_date' => date('Y-m-d H:i:s', mktime()), 'created_date' => date('Y-m-d H:i:s', mktime())),
			array(
				'cn' => 'cn6', 'rbusername' => 'username6', 'rbusergroup' => 'usergroupC', 'usersecret' => 'password1', 'logged_in' => 0, 'session_id' => '',
				'blocked' => 0, 'login_tries' => 0, 'require_password_change' => 0, 'last_password_change_date' => null, 
				'modified_date' => date('Y-m-d H:i:s', mktime()), 'created_date' => date('Y-m-d H:i:s', mktime())),
			array(
				'cn' => 'cn7', 'rbusername' => 'username7', 'rbusergroup' => 'usergroupD', 'usersecret' => 'password1', 'logged_in' => 0, 'session_id' => '',
				'blocked' => 0, 'login_tries' => 0, 'require_password_change' => 0, 'last_password_change_date' => null, 
				'modified_date' => date('Y-m-d H:i:s', mktime()), 'created_date' => date('Y-m-d H:i:s', mktime()))
			);
		$this->extras->insert_batch('sysuser', $data);
	}
	public function excelTest() {
		$this->load->library('excel');
		$sheet = new PHPExcel();
		$sheet->getProperties()->setTitle('Test spreadsheet')->setDescription('Test spreadsheet');
		$sheet->setActiveSheetIndex(0);
		$sheet->getDefaultStyle()->getFont()->setName('Calibri');
		$sheet->getDefaultStyle()->getFont()->setSize(8);
		$sheetWriter = PHPExcel_IOFactory::createWriter($sheet, "Excel2007");

		// currency format, € with < 0 being in red color
		$currencyFormat = '#,#0.## \€;[Red]-#,#0.## \€';
		// number format, with thousands separator and two decimal points.
		$numberFormat = '#,#0.##;[Red]-#,#0.##';

		// writer already created the first sheet for us, let's get it
		$objSheet = $sheet->getActiveSheet();
		// rename the sheet
		$objSheet->setTitle('My sales report');

		// let's bold and size the header font and write the header
		// as you can see, we can specify a range of cells, like here: cells from A1 to A4
		$objSheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(12);

		// write header
		$objSheet->getCell('A1')->setValue('Product');
		$objSheet->getCell('B1')->setValue('Quanity');
		$objSheet->getCell('C1')->setValue('Price');
		$objSheet->getCell('D1')->setValue('Total Price');

		// we could get this data from database, but for simplicty, let's just write it
		$objSheet->getCell('A2')->setValue('Motherboard');
		$objSheet->getCell('B2')->setValue(10);
		$objSheet->getCell('C2')->setValue(5);
		$objSheet->getCell('D2')->setValue('=B2*C2');

		$objSheet->getCell('A3')->setValue('Processor');
		$objSheet->getCell('B3')->setValue(6);
		$objSheet->getCell('C3')->setValue(3);
		$objSheet->getCell('D3')->setValue('=B3*C3');

		$objSheet->getCell('A4')->setValue('Memory');
		$objSheet->getCell('B4')->setValue(10);
		$objSheet->getCell('C4')->setValue(2.5);
		$objSheet->getCell('D4')->setValue('=B4*C4');

		$objSheet->getCell('A5')->setValue('TOTAL');
		$objSheet->getCell('B5')->setValue('=SUM(B2:B4)');
		$objSheet->getCell('C5')->setValue('-');
		$objSheet->getCell('D5')->setValue('=SUM(D2:D4)');

		// bold and resize the font of the last row
		$objSheet->getStyle('A5:D5')->getFont()->setBold(true)->setSize(12);

		// set number and currency format to columns
		$objSheet->getStyle('B2:B5')->getNumberFormat()->setFormatCode($numberFormat);
		$objSheet->getStyle('C2:D5')->getNumberFormat()->setFormatCode($currencyFormat);
		
		// autosize the columns
		$objSheet->getColumnDimension('A')->setAutoSize(true);
		$objSheet->getColumnDimension('B')->setAutoSize(true);
		$objSheet->getColumnDimension('C')->setAutoSize(true);
		$objSheet->getColumnDimension('D')->setAutoSize(true);

		// write the file
		//$sheetWriter->save('test.xlsx');

		//output file
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="test_sheet'.date('dMy').'.xlsx"');
		header('Cache-Control: max-age=0');

		$sheetWriter->save('php://output');
		/*
		// include PHPExcel
		require('PHPExcel.php');
		// create new PHPExcel object
		$objPHPExcel = new PHPExcel;
		// set default font
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
		// set default font size
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(8);
		// create the writer
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");

		// currency format, € with < 0 being in red color
		$currencyFormat = '#,#0.## \€;[Red]-#,#0.## \€';
		// number format, with thousands separator and two decimal points.
		$numberFormat = '#,#0.##;[Red]-#,#0.##';

		// writer already created the first sheet for us, let's get it
		$objSheet = $objPHPExcel->getActiveSheet();
		// rename the sheet
		$objSheet->setTitle('My sales report');

		// let's bold and size the header font and write the header
		// as you can see, we can specify a range of cells, like here: cells from A1 to A4
		$objSheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(12);

		// write header
		$objSheet->getCell('A1')->setValue('Product');
		$objSheet->getCell('B1')->setValue('Quanity');
		$objSheet->getCell('C1')->setValue('Price');
		$objSheet->getCell('D1')->setValue('Total Price');

		// we could get this data from database, but for simplicty, let's just write it
		$objSheet->getCell('A2')->setValue('Motherboard');
		$objSheet->getCell('B2')->setValue(10);
		$objSheet->getCell('C2')->setValue(5);
		$objSheet->getCell('D2')->setValue('=B2*C2');

		$objSheet->getCell('A3')->setValue('Processor');
		$objSheet->getCell('B3')->setValue(6);
		$objSheet->getCell('C3')->setValue(3);
		$objSheet->getCell('D3')->setValue('=B3*C3');

		$objSheet->getCell('A4')->setValue('Memory');
		$objSheet->getCell('B4')->setValue(10);
		$objSheet->getCell('C4')->setValue(2.5);
		$objSheet->getCell('D4')->setValue('=B4*C4');

		$objSheet->getCell('A5')->setValue('TOTAL');
		$objSheet->getCell('B5')->setValue('=SUM(B2:B4)');
		$objSheet->getCell('C5')->setValue('-');
		$objSheet->getCell('D5')->setValue('=SUM(D2:D4)');

		// bold and resize the font of the last row
		$objSheet->getStyle('A5:D5')->getFont()->setBold(true)->setSize(12);

		// set number and currency format to columns
		$objSheet->getStyle('B2:B5')->getNumberFormat()->setFormatCode($numberFormat);
		$objSheet->getStyle('C2:D5')->getNumberFormat()->setFormatCode($currencyFormat);

		// create some borders
		// first, create the whole grid around the table
		$objSheet->getStyle('A1:D5')->getBorders()->
		getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		// create medium border around the table
		$objSheet->getStyle('A1:D5')->getBorders()->
		getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		// create a double border above total line
		$objSheet->getStyle('A5:D5')->getBorders()->
		getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
		// create a medium border on the header line
		$objSheet->getStyle('A1:D1')->getBorders()->
		getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);

		// autosize the columns
		$objSheet->getColumnDimension('A')->setAutoSize(true);
		$objSheet->getColumnDimension('B')->setAutoSize(true);
		$objSheet->getColumnDimension('C')->setAutoSize(true);
		$objSheet->getColumnDimension('D')->setAutoSize(true);

		// write the file
		$objWriter->save('test.xlsx');
		*/
	}
	public function soapTest() {
		///*
		$wsdl = 'http://footballpool.dataaccess.eu/data/info.wso?wsdl';
		$client = new SoapClient($wsdl);
		//$result = $client->TopGoalScorers(array('iTopN' => 5));
		$result = $client->AllPlayersWithYellowOrRedCards(array('bSortedByName' => true, 'bSortedByYellowCards' => false, 'bSortedByRedCards' => false));
		return $result;
		//*/
		/*
		$wsdl = 'http://192.168.63.128:8099/aaa/services/SessionManagerWS';
		$client = new SoapClient($wsdl);
		$result = $client->findByServiceType(array('serviceType' => 'service'));
		return $result;
		*/
	}
	public function randSubs() {
		$realms = ['realm1', 'realm2', 'realm3'];
		$rbstaticips = [null, '127.0.0.1', '127.0.0.2', '127.0.0.3'];
		$rbmultistaticips = [null, '128.0.0.1', '128.0.0.2', '128.0.0.3'];
		$rbbandwidths = [null, 'bandwidth', 'bandwidth2', 'bandwidth3'];
		$rbstatuses = ['A', 'B', 'K', 'D'];
		$rbsvccodes = [null, 'svccode1', 'svccode2', 'svccode3'];
		$this->extras->db_select();
		for ($i = 161; $i <= 180; $i++) {
			$data = array(
				'cn' => strval('cn'.$i),
				'rbrealm' => $realms[mt_rand(0, count($realms) - 1)],
				'rbcreateddate' => '2014-02-23 09:20:00',
				'rbstaticip' => $rbstaticips[mt_rand(0, count($rbstaticips) - 1)],
				'rbmultistatic' => $rbmultistaticips[mt_rand(0, count($rbmultistaticips) - 1)],
				'rbbandwidth' => $rbbandwidths[mt_rand(0, count($rbbandwidths) - 1)],
				'rbstatus' => $rbstatuses[mt_rand(0, count($rbstatuses) - 1)],
				'rbsvccode' => $rbsvccodes[mt_rand(0, count($rbsvccodes) - 1)],
				'last_updated_date' => date('Y-m-d H:i:s', mktime()));
			//$this->extras->insert('subscriber', $data);
		}
	}
}
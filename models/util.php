<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Util extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	public function getDateYesterday($dateOnly) {
		$yesterday = mktime(date('h'), date('i'), date('s'), date('m'), date('d') - 1, date('Y'));
		return $dateOnly ? date('Y-m-d', $yesterday) : date('Y-m-d H:i:s', $yesterday);
	}
	public function isIPValid($ipaddress) {
		$parts = explode('/', $ipaddress);
		$address = $parts[0];
		$subnet = '';
		if (count($parts) == 1) {
			log_message('info', 'no subnet|'.json_encode($this->input->valid_ip($address)));
			return $this->input->valid_ip($address);
		} else if (count($parts) == 2) {
			log_message('info', 'has subnet');
			$subnet = $parts[1];
			if ($this->input->valid_ip($address)) {
				log_message('info', 'valid ip|'.intval($subnet).'|'.json_encode(intval($subnet) < 1));
				return intval($subnet) < 1 ? false : true;
			} else {
				log_message('info', 'invalid ip');
				return false;
			}
		} else {
			return false;
		}
	}
	public function isIPv6Valid($ipaddress) {
		$parts = explode('/', $ipaddress);
		$address = $parts[0];
		$subnet = '';
		if (count($parts) == 1) {
			log_message('info', 'ipv6 has no subnet | '.$ipaddress);
			return false;
		} else if (count($parts) == 2) {
			log_message('info', 'has subnet | '.$ipaddress);
			$subnet = $parts[1];
		} else {
			return false;
		}
	}
	public function generateRandomString($len) {
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$charLen = strlen($characters) - 1;
		$rand = '';
		for ($i = 0; $i < $len; $i++) {
			$rand .= $characters[mt_rand(0, $charLen)];
		}
		return $rand;
	}
	public function isPasswordAcceptable($password) {
		$this->load->model('settings');
		if ($this->settings->PASSWORDMINLENGTH > strlen($password)) {
			return array('acceptable' => false, 'reason' => 'Password is too short');
		}
		if ($this->settings->PASSWORDREQUIRENUMBER) {
			$hasNumber = preg_match('/\d{1}/', $password);
			if (!$hasNumber) {
				return array('acceptable' => false, 'reason' => 'Password must have at least one digit');
			}
		}
		if ($this->settings->PASSWORDREQUIRESYMBOL) {
			$hasSymbol = preg_match('/\W{1}/', $password);
			if (!$hasSymbol) {
				return array('acceptable' => false, 'reason' => 'Password must have at least one symbol');
			}
		}
		return array('acceptable' => true);
	}

	//file writers
	public function writeSystemUserActivities($data, $headers, $columns, $title) {
		$this->load->library('excel');
		$sheet = new PHPExcel();
		$sheet->getProperties()->setTitle($title)->setDescription($title);
		$sheet->setActiveSheetIndex(0);
		$sheet->getDefaultStyle()->getFont()->setName('Calibri');
		$sheet->getDefaultStyle()->getFont()->setSize(8);
		$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel2007");
		$objSheet = $sheet->getActiveSheet();
		$objSheet->setTitle($title);
		$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(12);
		for ($i = 0; $i < count($columns); $i++) {
			$cell = strval($columns[$i].'1');
			$objSheet->getCell($cell)->setValue(strval($headers[$i]));
		}
		for ($i = 0; $i < count($data); $i++) {
			for ($j = 0; $j < count($headers); $j++) {
				$cell = strval($columns[$j].($i + 2));
				switch ($columns[$j]) {
					case 'A': {
						$objSheet->getCell($cell)->setValue($data[$i]['datatype']);
						break;
					}
					case 'B': {
						$objSheet->getCell($cell)->setValue($data[$i]['action']);
						break;
					}
					case 'C': {
						$objSheet->getCell($cell)->setValue($data[$i]['info']);
						break;
					}
					case 'D' : {
						$objSheet->getCell($cell)->setValue($data[$i]['sysuser']);
						break;
					}
					case 'E': {
						$objSheet->getCell($cell)->setValue($data[$i]['ipaddress']);
						break;
					}
					case 'F': {
						$objSheet->getCell($cell)->setValue($data[$i]['timestamp']);
						break;
					}
				}
			}
		}
		for ($i = 0; $i < count($columns); $i++) {
			$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.str_replace(' ', '', $title).'_'.date('dMY').'.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}
	public function writeSubscriberAuditTrail($data, $headers, $columns, $title) {
		$this->load->library('excel');
		$sheet = new PHPExcel();
		$sheet->getProperties()->setTitle($title)->setDescription($title);
		$sheet->setActiveSheetIndex(0);
		$sheet->getDefaultStyle()->getFont()->setName('Calibri');
		$sheet->getDefaultStyle()->getFont()->setSize(8);
		$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel2007");
		$objSheet = $sheet->getActiveSheet();
		$objSheet->setTitle($title);
		$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(12);
		for ($i = 0; $i < count($columns); $i++) {
			$cell = strval($columns[$i].'1');
			$objSheet->getCell($cell)->setValue(strval($headers[$i]));
		}
		for ($i = 0; $i < count($data); $i++) {
			for ($j = 0; $j < count($headers); $j++) {
				$cell = strval($columns[$j].($i + 2));
				switch ($columns[$j]) {
					case 'A': {
						$objSheet->getCell($cell)->setValue($data[$i]['subscriber_cn']);
						break;
					}
					case 'B': {
						$objSheet->getCell($cell)->setValue($data[$i]['action']);
						break;
					}
					case 'C': {
						$objSheet->getCell($cell)->setValue($data[$i]['remarks']);
						break;
					}
					case 'D' : {
						$objSheet->getCell($cell)->setValue($data[$i]['sysuser']);
						break;
					}
					case 'E': {
						$objSheet->getCell($cell)->setValue($data[$i]['ipaddress']);
						break;
					}
					case 'F': {
						$objSheet->getCell($cell)->setValue($data[$i]['timestamp']);
						break;
					}
				}
			}
		}
		for ($i = 0; $i < count($columns); $i++) {
			//remove if statement to allow resizing of column 'C'
			if ($columns[$i] != 'C') {
				$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
			}
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.str_replace(' ', '', $title).'_'.date('dMY').'.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}
	public function createSubscriberReportCsvEmptyFile($headers, $title) {
		$docroot = $_SERVER['DOCUMENT_ROOT'];
		$deletedTempDir = '';
		if (substr($docroot, strlen($docroot) - 1, 1) == '/') {
			$deletedTempDir = $docroot.'deletedusers/tmp/';
		} else {
			$deletedTempDir = $docroot.'/deletedusers/tmp/';
		}
		$filename = str_replace(' ', '', $title).'_'.date('dMY-His').'.csv';
		$fp = fopen($deletedTempDir.$filename, 'w');
		fputcsv($fp, $headers);
		fclose($fp);
		return $deletedTempDir.$filename;
	}
	public function writeSubscriberReportCsv($filename, $data) {
		$fp = fopen($filename, 'a');
		foreach ($data as $line) {
			$parts = explode('@', $line['USERNAME']);
			fputcsv($fp, array(
				is_null($line['RBACCOUNTSTATUS']) ? '' : $line['RBACCOUNTSTATUS'],
				isset($parts[1]) ? $parts[1] : '',
				is_null($line['USERNAME']) ? '' : $line['USERNAME'],
				// Remove Columns 5/20/19
				// is_null($line['CUSTOMERTYPE']) ? '' : $line['CUSTOMERTYPE'],
				is_null($line['CUSTOMERSTATUS']) ? '' : ($line['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D'),
				// is_null($line['RADIUSPOLICY']) ? '' : str_replace('~', '-', $line['RADIUSPOLICY']),
				// is_null($line['RBACCOUNTPLAN']) ? '' : str_replace('~', '-', $line['RBACCOUNTPLAN']),
				is_null($line['RBADDITIONALSERVICE1']) ? '' : $line['RBADDITIONALSERVICE1'],
				is_null($line['RBADDITIONALSERVICE2']) ? '' : $line['RBADDITIONALSERVICE2'],
				is_null($line['RBORDERNUMBER']) ? '' : $line['RBORDERNUMBER'],
				is_null($line['RBSERVICENUMBER']) ? '' : $line['RBSERVICENUMBER'],
				is_null($line['RBCUSTOMERNAME']) ? '' : $line['RBCUSTOMERNAME'],
				is_null($line['RBIPADDRESS']) ? '' : $line['RBIPADDRESS'],
				is_null($line['RBMULTISTATIC']) ? '' : $line['RBMULTISTATIC'],
				is_null($line['RBREMARKS']) ? '' : str_replace(array("\r\n", "\n", "\r"), "; ", $line['RBREMARKS'])));
		}
		fclose($fp);
	}
	public function writeSubscriberReportCsvV2($filename, $data) {
		$fp = fopen($filename, 'a');
		foreach ($data as $line) {
			$parts = explode('@', $line['USERNAME']);
			fputcsv($fp, array(
				is_null($line['RBACCOUNTSTATUS']) ? '' : $line['RBACCOUNTSTATUS'],
				isset($parts[1]) ? $parts[1] : '',
				is_null($line['USERNAME']) ? '' : $line['USERNAME'],
				// Remove Columns 5/20/19
				// is_null($line['CUSTOMERTYPE']) ? '' : $line['CUSTOMERTYPE'],
				is_null($line['CUSTOMERSTATUS']) ? '' : ($line['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D'),
				// is_null($line['RADIUSPOLICY']) ? '' : str_replace('~', '-', $line['RADIUSPOLICY']),
				// is_null($line['RBACCOUNTPLAN']) ? '' : str_replace('~', '-', $line['RBACCOUNTPLAN']),
				is_null($line['RBADDITIONALSERVICE1']) ? '' : $line['RBADDITIONALSERVICE1'],
				is_null($line['RBADDITIONALSERVICE2']) ? '' : $line['RBADDITIONALSERVICE2'],
				is_null($line['RBORDERNUMBER']) ? '' : $line['RBORDERNUMBER'],
				is_null($line['RBSERVICENUMBER']) ? '' : $line['RBSERVICENUMBER'],
				is_null($line['RBCUSTOMERNAME']) ? '' : $line['RBCUSTOMERNAME'],
				is_null($line['RBIPADDRESS']) ? '' : $line['RBIPADDRESS'],
				is_null($line['RBMULTISTATIC']) ? '' : $line['RBMULTISTATIC'],
				is_null($line['LOCATION']) ? '' : $line['LOCATION'],
				is_null($line['RBREMARKS']) ? '' : str_replace(array("\r\n", "\n", "\r"), "; ", $line['RBREMARKS'])));
		}
		fclose($fp);
	}
	public function writeSubscriberReport($data, $headers, $columns, $title) {
		$this->load->library('excel');
		$sheet = new PHPExcel();
		$sheet->getProperties()->setTitle($title)->setDescription($title);
		$sheet->setActiveSheetIndex(0);
		$sheet->getDefaultStyle()->getFont()->setName('Calibri');
		$sheet->getDefaultStyle()->getFont()->setSize(8);
		$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel2007");
		$objSheet = $sheet->getActiveSheet();
		$objSheet->setTitle($title);
		$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(12);
		for ($i = 0; $i < count($columns); $i++) {
			$cell = strval($columns[$i].'1');
			$objSheet->getCell($cell)->setValue(strval($headers[$i]));
		}
		for ($i = 0; $i < count($data); $i++) {
			for ($j = 0; $j < count($headers); $j++) {
				$cell = strval($columns[$j].($i + 2));
				switch ($columns[$j]) {
					case 'A': {
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['RBACCOUNTSTATUS']) ? '' : $data[$i]['RBACCOUNTSTATUS']);
						break;
					}
					case 'B': {
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['RBREALM']) ? '' : $data[$i]['RBREALM']);
						break;
					}
					case 'C': {
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['USERNAME']) ? '' : $data[$i]['USERNAME']);
						break;
					}
					case 'D' : {
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['CUSTOMERTYPE']) ? '' : $data[$i]['CUSTOMERTYPE']);
						break;
					}
					case 'E': {
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['CUSTOMERSTATUS']) ? '' : ($data[$i]['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D'));
						break;
					}
					case 'F': {
						// $objSheet->getCell($cell)->setValue(is_null($data[$i]['RADIUSPOLICY']) ? '' : $data[$i]['RADIUSPOLICY']);
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['RBACCOUNTPLAN']) ? '' : $data[$i]['RBACCOUNTPLAN']);
						break;
					}
					case 'G': {
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['RBADDITIONALSERVICE1']) ? '' : $data[$i]['RBADDITIONALSERVICE1']);
						break;	
					}
					case 'H': {
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['RBADDITIONALSERVICE2']) ? '' : $data[$i]['RBADDITIONALSERVICE2']);
						break;	
					}
					case 'I': {
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['RBORDERNUMBER']) ? '' : $data[$i]['RBORDERNUMBER']);
						break;	
					}
					case 'J': {
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['RBSERVICENUMBER']) ? '' : $data[$i]['RBSERVICENUMBER']);
						break;	
					}
					case 'K': {
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['RBCUSTOMERNAME']) ? '' : $data[$i]['RBCUSTOMERNAME']);
						break;	
					}
					case 'L': {
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['RBIPADDRESS']) ? '' : $data[$i]['RBIPADDRESS']);
						break;	
					}
					case 'M': {
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['RBMULTISTATIC']) ? '' : $data[$i]['RBMULTISTATIC']);
						break;	
					}
					case 'N': {
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['RBREMARKS']) ? '' : $data[$i]['RBREMARKS']);
						break;	
					}
				}
			}
		}
		for ($i = 0; $i < count($columns); $i++) {
			$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.str_replace(' ', '', $title).'_'.date('dMY').'.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}
	public function createSubscriberReportEmptyFile($headers, $columns, $title) {
		$this->load->library('excel');
		$sheet = new PHPExcel();
		$sheet->getProperties()->setTitle($title)->setDescription($title);
		$sheet->setActiveSheetIndex(0);
		$sheet->getDefaultStyle()->getFont()->setName('Calibri');
		$sheet->getDefaultStyle()->getFont()->setSize(8);
		$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel2007");
		$objSheet = $sheet->getActiveSheet();
		$objSheet->setTitle($title);
		$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(12);
		for ($i = 0; $i < count($columns); $i++) {
			$cell = strval($columns[$i].'1');
			$objSheet->getCell($cell)->setValue(strval($headers[$i]));
		}
		$docroot = $_SERVER['DOCUMENT_ROOT'];
		$deletedTempDir = '';
		if (substr($docroot, strlen($docroot) - 1, 1) == '/') {
			$deletedTempDir = $docroot.'deletedusers/tmp/';
		} else {
			$deletedTempDir = $docroot.'/deletedusers/tmp/';
		}
		$filename = str_replace(' ', '', $title).'_'.date('dMY').'.xlsx';
		$writer->save($deletedTempDir.$filename);
		return strval($deletedTempDir.$filename);
	}
	public function createCabinetReportCsvEmptyFile($headers, $title) {
		$docroot = $_SERVER['DOCUMENT_ROOT'];
		$deletedTempDir = '';
		if (substr($docroot, strlen($docroot) - 1, 1) == '/') {
			$deletedTempDir = $docroot.'deletedusers/tmp/';
		} else {
			$deletedTempDir = $docroot.'/deletedusers/tmp/';
		}
		$filename = str_replace(' ', '', $title).'_'.date('dMY-His').'.csv';
		$fp = fopen($deletedTempDir.$filename, 'w');
		fputcsv($fp, $headers);
		fclose($fp);
		return $deletedTempDir.$filename;
	}
	public function writeCabinetReportCsv($filename, $data) {
		$fp = fopen($filename, 'a');
		foreach ($data as $line) {
			fputcsv($fp, array(
				$line['name'],
				$line['homing_bng'],
				$line['data_vlan']));
		}
		fclose($fp);
	}
	//not used as of october 4, 2016
	public function appendToSubscriberReportFile($filename, $headers, $columns, $data) {
		$inputFileType = PHPExcel_IOFactory::identify($filename);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$xls = $objReader->load($filename);

		try {
			$xls->setActiveSheetIndex(0);
			$rowCount = $xls->getSheet(0)->getHighestRow();
			log_message('info', 'start at row:'.$rowCount);
			$start = $rowCount + 1;
			for ($i = 0; $i < count($data); $i++) {
				for ($j = 0; $j < count($headers); $j++) {
					$cell = strval($columns[$j].($i + $start));
					switch ($columns[$j]) {
						case 'A': {
							$xls->getActiveSheet()->getCell($cell)->setValue(is_null($data[$i]['RBACCOUNTSTATUS']) ? '' : $data[$i]['RBACCOUNTSTATUS']);
							break;
						}
						case 'B': {
							$xls->getActiveSheet()->getCell($cell)->setValue(is_null($data[$i]['RBREALM']) ? '' : $data[$i]['RBREALM']);
							break;
						}
						case 'C': {
							$xls->getActiveSheet()->getCell($cell)->setValue(is_null($data[$i]['USERNAME']) ? '' : $data[$i]['USERNAME']);
							break;
						}
						case 'D' : {
							$xls->getActiveSheet()->getCell($cell)->setValue(is_null($data[$i]['CUSTOMERTYPE']) ? '' : $data[$i]['CUSTOMERTYPE']);
							break;
						}
						case 'E': {
							$xls->getActiveSheet()->getCell($cell)->setValue(is_null($data[$i]['CUSTOMERSTATUS']) ? '' : ($data[$i]['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D'));
							break;
						}
						case 'F': {
							// $xls->getActiveSheet()->getCell($cell)->setValue(is_null($data[$i]['RADIUSPOLICY']) ? '' : str_replace('~', '-', $data[$i]['RADIUSPOLICY']));
							$xls->getActiveSheet()->getCell($cell)->setValue(is_null($data[$i]['RBACCOUNTPLAN']) ? '' : str_replace('~', '-', $data[$i]['RBACCOUNTPLAN']));
							break;
						}
						case 'G': {
							$xls->getActiveSheet()->getCell($cell)->setValue(is_null($data[$i]['RBADDITIONALSERVICE1']) ? '' : $data[$i]['RBADDITIONALSERVICE1']);
							break;	
						}
						case 'H': {
							$xls->getActiveSheet()->getCell($cell)->setValue(is_null($data[$i]['RBADDITIONALSERVICE2']) ? '' : $data[$i]['RBADDITIONALSERVICE2']);
							break;	
						}
						case 'I': {
							$xls->getActiveSheet()->getCell($cell)->setValue(is_null($data[$i]['RBORDERNUMBER']) ? '' : $data[$i]['RBORDERNUMBER']);
							break;	
						}
						case 'J': {
							$xls->getActiveSheet()->getCell($cell)->setValue(is_null($data[$i]['RBSERVICENUMBER']) ? '' : $data[$i]['RBSERVICENUMBER']);
							break;	
						}
						case 'K': {
							$xls->getActiveSheet()->getCell($cell)->setValue(is_null($data[$i]['RBCUSTOMERNAME']) ? '' : $data[$i]['RBCUSTOMERNAME']);
							break;	
						}
						case 'L': {
							$xls->getActiveSheet()->getCell($cell)->setValue(is_null($data[$i]['RBIPADDRESS']) ? '' : $data[$i]['RBIPADDRESS']);
							break;	
						}
						case 'M': {
							$xls->getActiveSheet()->getCell($cell)->setValue(is_null($data[$i]['RBMULTISTATIC']) ? '' : $data[$i]['RBMULTISTATIC']);
							break;	
						}
						case 'N': {
							$xls->getActiveSheet()->getCell($cell)->setValue(is_null($data[$i]['RBREMARKS']) ? '' : $data[$i]['RBREMARKS']);
							break;	
						}
					}
				}
			}
			for ($i = 0; $i < count($columns); $i++) {
				$xls->getActiveSheet()->getColumnDimension($columns[$i])->setAutoSize(true);	
			}
			$writer = PHPExcel_IOFactory::createWriter($xls, "Excel2007");
			$writer->save($filename);
			unset($data);
		} catch (Exception $e) {
			log_message('info', 'ERR:'.json_encode($e));
		}
	}
	public function writeSubscriberCappedReportCsv($filename, $data) {
		$fp = fopen($filename, 'a');
		foreach ($data as $line) {
			$upload = '';
			if (isset($line['upload_octets'])) {
				$val = $line['upload_octets'] / (1024 * 1024 * 1024);
				$parts = explode('.', strval($val));
				$upload = $parts[0].(isset($parts[1]) ? '.'.substr($parts[1], 0, 2) : '');
			}
			$download = '';
			if (isset($line['download_octets'])) {
				$val = $line['download_octets'] / (1024 * 1024 * 1024);
				$parts = explode('.', $val);
				$download = $parts[0].(isset($parts[1]) ? '.'.substr($parts[1], 0, 2) : '');
			}
			$total = '';
			if(isset($line['total_octets'])) {
				$val = $line['total_octets'] / (1024 * 1024 * 1024);
				$parts = explode('.', $val);
				$total = $parts[0].(isset($parts[1]) ? '.'.substr($parts[1], 0, 2) : '');
			}
			$hsq = '';
			if (isset($line['hsqvalue'])) {
				$val = $line['hsqvalue'] / (1024 * 1024 * 1024);
				$parts = explode('.', $val);
				$hsq = $parts[0].(isset($parts[1]) ? '.'.substr($parts[1], 0, 2) : '');
			}
			$timestamp = '';
			if (isset($line['capped_date'])) {
				$timestamp = $line['capped_date'];
				// $parts = explode(' ', $line['capped_date']);
				// $timeParts = explode('.', $parts[1]);
				// $timestamp = $parts[0].' '.$timeParts[0].':'.$timeParts[1].':'.$timeParts[2].' '.$parts[2];
			}
			fputcsv($fp, array(
				is_null($line['username']) ? '' : $line['username'],
				is_null($line['radiuspolicy']) ? '' : $line['radiuspolicy'],
				$upload, $download, $total, $hsq,
				$timestamp));
		}
		fclose($fp);
	}
	public function writeSubscriberCappedReport($data, $headers, $columns, $title) {
		$this->load->library('excel');
		$sheet = new PHPExcel();
		$sheet->getProperties()->setTitle($title)->setDescription($title);
		$sheet->setActiveSheetIndex(0);
		$sheet->getDefaultStyle()->getFont()->setName('Calibri');
		$sheet->getDefaultStyle()->getFont()->setSize(8);
		$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel2007");
		$objSheet = $sheet->getActiveSheet();
		$objSheet->setTitle($title);
		$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(12);
		for ($i = 0; $i < count($columns); $i++) {
			$cell = strval($columns[$i].'1');
			$objSheet->getCell($cell)->setValue(strval($headers[$i]));
		}
		for ($i = 0; $i < count($data); $i++) {
			for ($j = 0; $j < count($headers); $j++) {
				$cell = strval($columns[$j].($i + 2));
				switch ($columns[$j]) {
					case 'A': {
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['USERID']) ? '' : $data[$i]['USERID']);
						break;
					}
					case 'B': {
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['POLICYGROUPNAME']) ? '' : $data[$i]['POLICYGROUPNAME']);
						break;
					}
					case 'C': {
						if (isset($data[$i]['UPLOADOCTETS'])) {
							$val = $data[$i]['UPLOADOCTETS'] / (1024 * 1024 * 1024);
							$parts = explode('.', strval($val));
							$entry = $parts[0].(isset($parts[1]) ? '.'.substr($parts[1], 0, 2) : '');
						} else {
							$entry = '';
						}
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['UPLOADOCTETS']) ? '' : $entry);
						break;
					}
					case 'D' : {
						if (isset($data[$i]['DOWNLOADOCTETS'])) {
							$val = $data[$i]['DOWNLOADOCTETS'] / (1024 * 1024 * 1024);
							$parts = explode('.', strval($val));
							$entry = $parts[0].(isset($parts[1]) ? '.'.substr($parts[1], 0, 2) : '');
						} else {
							$entry = '';
						}
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['DOWNLOADOCTETS']) ? '' : $entry);
						break;
					}
					case 'E': {
						if (isset($data[$i]['TOTALOCTETS'])) {
							$val = $data[$i]['TOTALOCTETS'] / (1024 * 1024 * 1024);
							$parts = explode('.', strval($val));
							$entry = $parts[0].(isset($parts[1]) ? '.'.substr($parts[1], 0, 2) : '');
						} else {
							$entry = '';
						}
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['TOTALOCTETS']) ? '' : $entry);
						break;
					}
					case 'F': {
						if (isset($data[$i]['HSQVALUE'])) {
							$val = $data[$i]['HSQVALUE'] / (1024 * 1024 * 1024);
							$parts = explode('.', strval($val));
							$entry = $parts[0].(isset($parts[1]) ? '.'.substr($parts[1], 0, 2) : '');
						} else {
							$entry = '';
						}
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['HSQVALUE']) ? '' : $entry);
						break;
					}
					case 'G': {
						$objSheet->getCell($cell)->setValue(is_null($data[$i]['DESCRIPTION']) ? '' : $data[$i]['DESCRIPTION']);
						break;	
					}
				}
			}
		}
		for ($i = 0; $i < count($columns); $i++) {
			$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.str_replace(' ', '', $title).'_'.date('dMY').'.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	//file readers
	/****************************************************************************
	 * FORMAT (load)				FORMAT (update)			FORMAT (delete)
	 * B: USERNAME 					B: USERNAME 			B: USERNAME
	 * C: PASSWORD 					C: PASSWORD
	 * D: CUSTOMERTYPE 				D: CUSTOMERTYPE
	 * E: CUSTOMERSTATUS			E: CUSTOMERSTATUS
	 * F: RBORDERNUMBER 			F: RBORDERNUMBER
	 * G: RBCUSTOMERNAME 			G: RBCUSTOMERNAME
	 * H: RBSERVICENUMBER 			H: RBSERVICENUMBER
	 * I: RBENABLED 				I: RBENABLED
	 * J: RADIUSPOLICY 				J: RADIUSPOLICY
	 * K: RBIPADDRESS 				K: RBIPADDRESS
	 * L: RBMULTISTATIC 			L: RBMULTISTATIC
	 * M: RBADDITIONALSERVICE1
	 * N: RBADDITIONALSERVICE2
	 * O: RBREMARKS
	 ****************************************************************************/
	public function countRows($uploadedFile) {
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$xls->setActiveSheetIndex(0);
		$rowCount = $xls->getSheet(0)->getHighestRow();
		return $rowCount;
	}
	public function verifyBulkCheckFormat($uploadedFile) {
		$headers = array(
			'B1' => 'USERNAME',
			'C1' => 'PASSWORD',
			'D1' => 'CUSTOMERTYPE',
			'E1' => 'STATUS',
			'F1' => 'ORDER NUMBER',
			'G1' => 'CUSTOMER NAME',
			'H1' => 'SERVICE NUMBER',
			'I1' => 'REDIRECTED',
			'J1' => 'SERVICE',
			'K1' => 'IP ADDRESS/CABINET NAME',
			'L1' => 'NET ADDRESS/CABINET NAME',
			'M1' => 'ADDITIONAL SERVICE 1',
			'N1' => 'ADDITIONAL SERVICE 2',
			'O1' => 'REMARKS');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		try {
			$xls = $reader->load($uploadedFile);
		} catch (Exception $e) {
			$error = json_encode($e);
			log_message('info', 'error@verifybulkcheckformat:'.$e);
		}
		for ($i = 0; $i < count($headers); $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;	
	}
	public function verifyBulkCheckFormatV2($uploadedFile) {
		$headers = array(
			'B1' => 'USERNAME',
			'C1' => 'PASSWORD',
			'D1' => 'CUSTOMERTYPE',
			'E1' => 'STATUS',
			'F1' => 'ORDER NUMBER',
			'G1' => 'CUSTOMER NAME',
			'H1' => 'SERVICE NUMBER',
			'I1' => 'REDIRECTED',
			'J1' => 'SERVICE',
			'K1' => 'IPV6 ADDRESS/CABINET NAME',
			'L1' => 'IP ADDRESS/CABINET NAME',
			'M1' => 'NET ADDRESS/CABINET NAME',
			'N1' => 'ADDITIONAL SERVICE 1',
			'O1' => 'ADDITIONAL SERVICE 2',
			'P1' => 'REMARKS');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		try {
			$xls = $reader->load($uploadedFile);
		} catch (Exception $e) {
			$error = json_encode($e);
			log_message('info', 'error@verifybulkcheckformat:'.$e);
		}
		for ($i = 0; $i < count($headers); $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;	
	}
	public function verifyBulkCheckData($uploadedFile, $realm) {
		$valid = [];
		$invalid = [];
		$invalidRowNumbers = [];
		$validRowNumbers = [];
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$xls->setActiveSheetIndex(0);
		$rowCount = $xls->getSheet(0)->getHighestRow();
		log_message('info', 'ROW COUNT: '.$rowCount);
		if ($rowCount <= 1) {
			return false;
		} else {
			for ($i = 0; $i < $rowCount - 1; $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.($i + 2).':P'.($i + 2));
				log_message('info', $i.'|VERIFY: '.json_encode($row));
				if (trim($row[0][0]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				} else {
					$hasUpperCase = preg_match('/[A-Z]{1}/', $row[0][0]);
					if ($hasUpperCase) {
						$invalid[] = $row[0];
						$invalidRowNumbers[] = ($i + 2);
						continue;
					}
					$hasSpecialChars = preg_match('/[^a-zA-Z0-9._-]/', $row[0][0]);
					if ($hasSpecialChars) {
						$invalid[] = $row[0];
						$invalidRowNumbers[] = ($i + 2);
						continue;
					}
				}
				$valid[] = $row[0];
				$validRowNumbers[] = ($i + 2);
			}
			return array('valid' => $valid, 'invalid' => $invalid, 'validRowNumbers' => $validRowNumbers, 'invalidRowNumbers' => $invalidRowNumbers);
		}
	}
	public function verifyBulkLoadFormat($uploadedFile) {
		$headers = array(
			'B1' => 'USERNAME',
			'C1' => 'PASSWORD',
			'D1' => 'CUSTOMERTYPE',
			'E1' => 'STATUS',
			'F1' => 'ORDER NUMBER',
			'G1' => 'CUSTOMER NAME',
			'H1' => 'SERVICE NUMBER',
			'I1' => 'REDIRECTED',
			'J1' => 'SERVICE',
			'K1' => 'IP ADDRESS/CABINET NAME',
			'L1' => 'NET ADDRESS/CABINET NAME',
			'M1' => 'ADDITIONAL SERVICE 1',
			'N1' => 'ADDITIONAL SERVICE 2',
			'O1' => 'REMARKS');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		for ($i = 0; $i < count($headers); $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;
	}
	public function verifyBulkLoadFormatV2($uploadedFile) {
		$headers = array(
			'B1' => 'USERNAME',
			'C1' => 'PASSWORD',
			'D1' => 'CUSTOMERTYPE',
			'E1' => 'STATUS',
			'F1' => 'ORDER NUMBER',
			'G1' => 'CUSTOMER NAME',
			'H1' => 'SERVICE NUMBER',
			'I1' => 'REDIRECTED',
			'J1' => 'SERVICE',
			'K1' => 'IPV6 ADDRESS/CABINET NAME',
			'L1' => 'IP ADDRESS/CABINET NAME',
			'M1' => 'NET ADDRESS/CABINET NAME',
			'N1' => 'ADDITIONAL SERVICE 1',
			'O1' => 'ADDITIONAL SERVICE 2',
			'P1' => 'REMARKS');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		for ($i = 0; $i < count($headers); $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;
	}
	public function verifyBulkLoadData($uploadedFile, $realm) {
		$valid = [];
		$invalid = [];
		$invalidRowNumbers = [];
		$validRowNumbers = [];
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$xls->setActiveSheetIndex(0);
		$rowCount = $xls->getSheet(0)->getHighestRow();
		log_message('info', 'ROW COUNT: '.$rowCount);
		if ($rowCount <= 1) {
			return false;
		} else {
			for ($i = 0; $i < $rowCount - 1; $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.($i + 2).':P'.($i + 2));
				log_message('info', $i.'|VERIFY: '.json_encode($row));
				/**************************************************
				 * check USERNAME column (col B)
				 **************************************************/
				if (trim($row[0][0]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				} else {
					$hasUpperCase = preg_match('/[A-Z]{1}/', $row[0][0]);
					if ($hasUpperCase) {
						$invalid[] = $row[0];
						$invalidRowNumbers[] = ($i + 2);
						continue;
					}
					$hasSpecialChars = preg_match('/[^a-zA-Z0-9._-]/', $row[0][0]);
					if ($hasSpecialChars) {
						$invalid[] = $row[0];
						$invalidRowNumbers[] = ($i + 2);
						continue;
					}
					$row[0][0] = $row[0][0].'@'.$realm;
				}
				/**************************************************
				 * check STATUS column (col E)
				 **************************************************/
				// Remove as required field 6/28/19
				// if (trim($row[0][3]) == '') {
				// 	$invalid[] = $row[0];
				// 	$invalidRowNumbers[] = ($i + 2);
				// 	continue;
				// }
				// Disable required field 6/24/2019
				// if (trim($row[0][5]) == '') {
				// 	$invalid[] = $row[0];
				// 	$invalidRowNumbers[] = ($i + 2);
				// 	continue;
				// }
				/**************************************************
				 * check SERVICE NUMBER column (col H)
				 **************************************************/
				if (trim($row[0][6]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				// if (trim($row[0][7]) == '') {
				// 	$invalid[] = $row[0];
				// 	$invalidRowNumbers[] = ($i + 2);
				// 	continue;
				// }
				/**************************************************
				 * check SERVICE column (col J)
				 **************************************************/
				// Remove Dependencies 5/21/19
				// if (trim($row[0][8]) == '') {
				// 	$invalid[] = $row[0];
				// 	$invalidRowNumbers[] = ($i + 2);
				// 	continue;
				// }


				$valid[] = $row[0];
				$validRowNumbers[] = ($i + 2);
			}
			return array('valid' => $valid, 'invalid' => $invalid, 'validRowNumbers' => $validRowNumbers, 'invalidRowNumbers' => $invalidRowNumbers);
		}
	}

	public function extractRowsToCreate($path, $rows) {
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($path);
		$xls->setActiveSheetIndex(0);
		$data = [];
		for ($i = 0; $i < count($rows); $i++) {			
			$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':O'.$rows[$i]);
			$data[] = $row[0];
		}		
		return $data;
	}
	public function extractRowsToCreateV2($path, $rows) {
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($path);
		$xls->setActiveSheetIndex(0);
		$data = [];
		for ($i = 0; $i < count($rows); $i++) {
			$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':P'.$rows[$i]);
			$data[] = $row[0];
			
		}		
		return $data;
	}
	public function writeBulkCheckOutput($path, $rows, $set, $realm) {
		$title = '';
		$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
		$writeHeaders = ['', 'USERNAME', 'PASSWORD', 'CUSTOMERTYPE', 'STATUS', 'ORDER NUMBER', 'CUSTOMER NAME', 'SERVICE NUMBER', 'REDIRECTED', 'SERVICE', 'IP ADDRESS/CABINET NAME', 'NET ADDRESS/CABINET NAME', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'REMARKS'];
		$headers = array(
			'A1' => '',
			'B1' => 'USERNAME',
			'C1' => 'PASSWORD',
			'D1' => 'CUSTOMERTYPE',
			'E1' => 'STATUS',
			'F1' => 'ORDER NUMBER',
			'G1' => 'CUSTOMER NAME',
			'H1' => 'SERVICE NUMBER',
			'I1' => 'REDIRECTED',
			'J1' => 'SERVICE',
			'K1' => 'IP ADDRESS/CABINET NAME',
			'L1' => 'NET ADDRESS/CABINET NAME',
			'M1' => 'ADDITIONAL SERVICE 1',
			'N1' => 'ADDITIONAL SERVICE 2',
			'O1' => 'REMARKS');
		$this->load->library('excel');
		$redFontColor = new PHPExcel_Style_Color();
		$redFontColor->setRGB('FF0000');
		if ($set == 'existing') {
			$reader = PHPExcel_IOFactory::createReader('Excel5');
			$reader->setReadDataOnly(true);
			$xls = $reader->load($path);
			$xls->setActiveSheetIndex(0);
			$subscribers = [];
			$this->load->model('subscribermain');
			for ($i = 0; $i < count($rows); $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':O'.$rows[$i]);
				$userIdentity = $row[0][0].'@'.$realm;
				$subscribers[] = $this->subscribermain->findByUserIdentity($userIdentity);
			}
			$title = 'Bulk Check - Existing Users';
			$sheet = new PHPExcel();
			$sheet ->getProperties()->setTitle($title)->setDescription($title);
			$sheet->setActiveSheetIndex(0);	
			$sheet->getDefaultStyle()->getFont()->setName('Calibri');
			$sheet->getDefaultStyle()->getFont()->setSize(11);
			$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel5");
			$objSheet = $sheet->getActiveSheet();
			$objSheet->setTitle($title);
			$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(11);
			$objSheet->getStyle('B1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('D1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('E1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('G1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('H1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('I1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('J1')->getFont()->setColor($redFontColor);
			for ($i = 0; $i < count($columns); $i++) {
				$cell = strval($columns[$i].'1');
				$objSheet->getCell($cell)->setValue(strval($writeHeaders[$i]));
			}
			for ($i = 0; $i < count($subscribers); $i++) {
				$subs = $subscribers[$i];
				for ($j = 0; $j < count($headers); $j++) {
					$cell = strval($columns[$j].($i + 2));
					switch ($columns[$j]) {
						case 'A': {
							$objSheet->getCell($cell)->setValue($realm);
							break;
						}
						case 'B': {
							$username = explode('@', $subs['USERNAME']);
							$objSheet->getCell($cell)->setValue($username[0]);
							break;
						}
						case 'C': {
							$objSheet->getCell($cell)->setValue(is_null($subs['PASSWORD']) ? '' : $subs['PASSWORD']);
							break;
						}
						case 'D' : {
							$val = '';
							if ($subs['CUSTOMERTYPE'] == 'Residential' || $subs['CUSTOMERTYPE'] == 'R') {
								$val = 'R';
							} else if ($subs['CUSTOMERTYPE'] == 'Business' || $subs['CUSTOMERTYPE'] == 'B') {
								$val = 'B';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;
						}
						case 'E': {
							$objSheet->getCell($cell)->setValue($subs['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D');
							break;
						}
						case 'F': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBORDERNUMBER']) ? '' : $subs['RBORDERNUMBER']);
							break;
						}
						case 'G': {
							$objSheet->getCell($cell)->setValue($subs['RBCUSTOMERNAME']);
							break;	
						}
						case 'H': {
							$objSheet->getCell($cell)->setValue($subs['RBSERVICENUMBER']);
							break;	
						}
						case 'I': {
							$val = '';
							if ($subs['RBENABLED'] == 'Yes' || $subs['RBENABLED'] == 'Y') {
								$val = 'Y';
							} else if ($subs['RBENABLED'] == 'No' || $subs['RBENABLED'] == 'N') {
								$val = 'N';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;	
						}
						case 'J': {
							$objSheet->getCell($cell)->setValue(str_replace('~', '-', $subs['RBACCOUNTPLAN']));
							break;	
						}
						case 'K': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS']);
							break;	
						}
						case 'L': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC']);
							break;	
						}
						case 'M': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE1']) ? '' : $subs['RBADDITIONALSERVICE1']);
							break;	
						}
						case 'N': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE2']) ? '' : $subs['RBADDITIONALSERVICE2']);
							break;	
						}
						case 'O': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBREMARKS']) ? '' : $subs['RBREMARKS']);
							break;
						}
					}
				}
			}
			for ($i = 0; $i < count($columns); $i++) {
				$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
			}
			//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.str_replace(' ', '', $title).'_'.date('dMY').'.xls"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		} else {
			$reader = PHPExcel_IOFactory::createReader('Excel5');
			$reader->setReadDataOnly(true);
			$xls = $reader->load($path);
			$xls->setActiveSheetIndex(0);
			$data = [];
			for ($i = 0; $i < count($rows); $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':O'.$rows[$i]);
				$data[] = $row[0];
			}
			//$title = ($set == 'valid' || $set == 'various') ? 'Bulk Check - Valid Users' : 'Bulk Check - Invalid Users';
			if ($set == 'valid') {
				$title = 'Bulk Check - Valid Users';
			} else if ($set == 'various') {
				$title = 'Bulk Check - Various Errors';
			} else if ($set == 'invalid') {
				$title = 'Bulk Check - Invalid Users';
			}
			log_message('info', 'title: '.$title);
			$sheet = new PHPExcel();
			$sheet ->getProperties()->setTitle($title)->setDescription($title);
			$sheet->setActiveSheetIndex(0);	
			$sheet->getDefaultStyle()->getFont()->setName('Calibri');
			$sheet->getDefaultStyle()->getFont()->setSize(11);
			$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel5");
			$objSheet = $sheet->getActiveSheet();
			$objSheet->setTitle($title);
			$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(11);
			$objSheet->getStyle('B1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('D1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('E1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('G1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('H1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('I1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('J1')->getFont()->setColor($redFontColor);
			for ($i = 0; $i < count($columns); $i++) {
				$cell = strval($columns[$i].'1');
				$objSheet->getCell($cell)->setValue(strval($writeHeaders[$i]));
			}
			for ($i = 0; $i < count($columns); $i++) {
				$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
			}
			for ($i = 0; $i < count($data); $i++) {
				$subs = $data[$i];
				for ($j = 0; $j < count($headers); $j++) {
					$cell = strval($columns[$j].($i + 2));
					switch ($columns[$j]) {
						case 'B': {
							$objSheet->getCell($cell)->setValue($subs[0]);
							break;
						}
						case 'C': {
							$objSheet->getCell($cell)->setValue($subs[1]);
							break;
						}
						case 'D' : {
							$val = '';
							if ($subs[2] == 'Residential' || $subs[2] == 'R') {
								$val = 'R';
							} else if ($subs[2] == 'Business' || $subs[2] == 'B') {
								$val = 'B';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;
						}
						case 'E': {
							$objSheet->getCell($cell)->setValue($subs[3]);
							break;
						}
						case 'F': {
							$objSheet->getCell($cell)->setValue($subs[4]);
							break;
						}
						case 'G': {
							$objSheet->getCell($cell)->setValue($subs[5]);
							break;	
						}
						case 'H': {
							$objSheet->getCell($cell)->setValue($subs[6]);
							break;	
						}
						case 'I': {
							$val = '';
							if ($subs[7] == 'Yes' || $subs[7] == 'Y') {
								$val = 'Y';
							} else if ($subs[7] == 'No' || $subs[7] == 'N') {
								$val = 'N';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;	
						}
						case 'J': {
							$objSheet->getCell($cell)->setValue(str_replace('~', '-', $subs[8]));
							break;	
						}
						case 'K': {
							$objSheet->getCell($cell)->setValue($subs[9]);
							break;	
						}
						case 'L': {
							$objSheet->getCell($cell)->setValue($subs[10]);
							break;	
						}
						case 'M': {
							$objSheet->getCell($cell)->setValue($subs[11]);
							break;	
						}
						case 'N': {
							$objSheet->getCell($cell)->setValue($subs[12]);
							break;	
						}
						case 'O': {
							$objSheet->getCell($cell)->setValue($subs[13]);
							break;
						}
					}
				}
			}
			//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.str_replace(' ', '', $title).'_'.date('dMY').'.xls"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		}
	}
	public function writeBulkCheckOutputV2($path, $rows, $set, $realm) {
		$title = '';
		$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P'];
		$writeHeaders = ['', 'USERNAME', 'PASSWORD', 'CUSTOMERTYPE', 'STATUS', 'ORDER NUMBER', 'CUSTOMER NAME', 'SERVICE NUMBER', 'REDIRECTED', 'SERVICE', 'IPV6 ADDRESS/CABINET NAME', 'IP ADDRESS/CABINET NAME', 'NET ADDRESS/CABINET NAME', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'REMARKS'];
		$headers = array(
			'A1' => '',
			'B1' => 'USERNAME',
			'C1' => 'PASSWORD',
			'D1' => 'CUSTOMERTYPE',
			'E1' => 'STATUS',
			'F1' => 'ORDER NUMBER',
			'G1' => 'CUSTOMER NAME',
			'H1' => 'SERVICE NUMBER',
			'I1' => 'REDIRECTED',
			'J1' => 'SERVICE',
			'K1' => 'IPV6 ADDRESS/CABINET NAME',
			'L1' => 'IP ADDRESS/CABINET NAME',
			'M1' => 'NET ADDRESS/CABINET NAME',
			'N1' => 'ADDITIONAL SERVICE 1',
			'O1' => 'ADDITIONAL SERVICE 2',
			'P1' => 'REMARKS');
		$this->load->library('excel');
		$redFontColor = new PHPExcel_Style_Color();
		$redFontColor->setRGB('FF0000');
		if ($set == 'existing') {
			$reader = PHPExcel_IOFactory::createReader('Excel5');
			$reader->setReadDataOnly(true);
			$xls = $reader->load($path);
			$xls->setActiveSheetIndex(0);
			$subscribers = [];
			$this->load->model('subscribermain');
			for ($i = 0; $i < count($rows); $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':P'.$rows[$i]);
				$userIdentity = $row[0][0].'@'.$realm;
				$subscribers[] = $this->subscribermain->findByUserIdentity($userIdentity);
			}
			$title = 'Bulk Check - Existing Users';
			$sheet = new PHPExcel();
			$sheet ->getProperties()->setTitle($title)->setDescription($title);
			$sheet->setActiveSheetIndex(0);	
			$sheet->getDefaultStyle()->getFont()->setName('Calibri');
			$sheet->getDefaultStyle()->getFont()->setSize(11);
			$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel5");
			$objSheet = $sheet->getActiveSheet();
			$objSheet->setTitle($title);
			$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(11);
			$objSheet->getStyle('B1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('D1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('E1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('G1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('H1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('I1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('J1')->getFont()->setColor($redFontColor);
			for ($i = 0; $i < count($columns); $i++) {
				$cell = strval($columns[$i].'1');
				$objSheet->getCell($cell)->setValue(strval($writeHeaders[$i]));
			}
			for ($i = 0; $i < count($subscribers); $i++) {
				$subs = $subscribers[$i];
				for ($j = 0; $j < count($headers); $j++) {
					$cell = strval($columns[$j].($i + 2));
					switch ($columns[$j]) {
						case 'A': {
							$objSheet->getCell($cell)->setValue($realm);
							break;
						}
						case 'B': {
							$username = explode('@', $subs['USERNAME']);
							$objSheet->getCell($cell)->setValue($username[0]);
							break;
						}
						case 'C': {
							$objSheet->getCell($cell)->setValue(is_null($subs['PASSWORD']) ? '' : $subs['PASSWORD']);
							break;
						}
						case 'D' : {
							$val = '';
							if ($subs['CUSTOMERTYPE'] == 'Residential' || $subs['CUSTOMERTYPE'] == 'R') {
								$val = 'R';
							} else if ($subs['CUSTOMERTYPE'] == 'Business' || $subs['CUSTOMERTYPE'] == 'B') {
								$val = 'B';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;
						}
						case 'E': {
							$objSheet->getCell($cell)->setValue($subs['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D');
							break;
						}
						case 'F': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBORDERNUMBER']) ? '' : $subs['RBORDERNUMBER']);
							break;
						}
						case 'G': {
							$objSheet->getCell($cell)->setValue($subs['RBCUSTOMERNAME']);
							break;	
						}
						case 'H': {
							$objSheet->getCell($cell)->setValue($subs['RBSERVICENUMBER']);
							break;	
						}
						case 'I': {
							$val = '';
							if ($subs['RBENABLED'] == 'Yes' || $subs['RBENABLED'] == 'Y') {
								$val = 'Y';
							} else if ($subs['RBENABLED'] == 'No' || $subs['RBENABLED'] == 'N') {
								$val = 'N';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;	
						}
						case 'J': {
							$objSheet->getCell($cell)->setValue(str_replace('~', '-', $subs['RBACCOUNTPLAN']));
							break;	
						}
						case 'K': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE4']) ? '' : $subs['RBADDITIONALSERVICE4']);
							break;
						}
						case 'L': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS']);
							break;	
						}
						case 'M': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC']);
							break;	
						}
						case 'N': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE1']) ? '' : $subs['RBADDITIONALSERVICE1']);
							break;	
						}
						case 'O': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE2']) ? '' : $subs['RBADDITIONALSERVICE2']);
							break;	
						}
						case 'P': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBREMARKS']) ? '' : $subs['RBREMARKS']);
							break;
						}
					}
				}
			}
			for ($i = 0; $i < count($columns); $i++) {
				$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
			}
			//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.str_replace(' ', '', $title).'_'.date('dMY').'.xls"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		} else {
			$reader = PHPExcel_IOFactory::createReader('Excel5');
			$reader->setReadDataOnly(true);
			$xls = $reader->load($path);
			$xls->setActiveSheetIndex(0);
			$data = [];
			for ($i = 0; $i < count($rows); $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':P'.$rows[$i]);
				$data[] = $row[0];
			}
			//$title = ($set == 'valid' || $set == 'various') ? 'Bulk Check - Valid Users' : 'Bulk Check - Invalid Users';
			if ($set == 'valid') {
				$title = 'Bulk Check - Valid Users';
			} else if ($set == 'various') {
				$title = 'Bulk Check - Various Errors';
			} else if ($set == 'invalid') {
				$title = 'Bulk Check - Invalid Users';
			}
			log_message('info', 'title: '.$title);
			$sheet = new PHPExcel();
			$sheet ->getProperties()->setTitle($title)->setDescription($title);
			$sheet->setActiveSheetIndex(0);	
			$sheet->getDefaultStyle()->getFont()->setName('Calibri');
			$sheet->getDefaultStyle()->getFont()->setSize(11);
			$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel5");
			$objSheet = $sheet->getActiveSheet();
			$objSheet->setTitle($title);
			$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(11);
			$objSheet->getStyle('B1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('D1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('E1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('G1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('H1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('I1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('J1')->getFont()->setColor($redFontColor);
			for ($i = 0; $i < count($columns); $i++) {
				$cell = strval($columns[$i].'1');
				$objSheet->getCell($cell)->setValue(strval($writeHeaders[$i]));
			}
			for ($i = 0; $i < count($columns); $i++) {
				$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
			}
			for ($i = 0; $i < count($data); $i++) {
				$subs = $data[$i];
				for ($j = 0; $j < count($headers); $j++) {
					$cell = strval($columns[$j].($i + 2));
					switch ($columns[$j]) {
						case 'B': {
							$objSheet->getCell($cell)->setValue($subs[0]);
							break;
						}
						case 'C': {
							$objSheet->getCell($cell)->setValue($subs[1]);
							break;
						}
						case 'D' : {
							$val = '';
							if ($subs[2] == 'Residential' || $subs[2] == 'R') {
								$val = 'R';
							} else if ($subs[2] == 'Business' || $subs[2] == 'B') {
								$val = 'B';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;
						}
						case 'E': {
							$objSheet->getCell($cell)->setValue($subs[3]);
							break;
						}
						case 'F': {
							$objSheet->getCell($cell)->setValue($subs[4]);
							break;
						}
						case 'G': {
							$objSheet->getCell($cell)->setValue($subs[5]);
							break;	
						}
						case 'H': {
							$objSheet->getCell($cell)->setValue($subs[6]);
							break;	
						}
						case 'I': {
							$val = '';
							if ($subs[7] == 'Yes' || $subs[7] == 'Y') {
								$val = 'Y';
							} else if ($subs[7] == 'No' || $subs[7] == 'N') {
								$val = 'N';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;	
						}
						case 'J': {
							$objSheet->getCell($cell)->setValue(str_replace('~', '-', $subs[8]));
							break;	
						}
						case 'K': {
							$objSheet->getCell($cell)->setValue($subs[9]);
							break;
						}
						case 'L': {
							$objSheet->getCell($cell)->setValue($subs[10]);
							break;	
						}
						case 'M': {
							$objSheet->getCell($cell)->setValue($subs[11]);
							break;	
						}
						case 'N': {
							$objSheet->getCell($cell)->setValue($subs[12]);
							break;	
						}
						case 'O': {
							$objSheet->getCell($cell)->setValue($subs[13]);
							break;	
						}
						case 'P': {
							$objSheet->getCell($cell)->setValue($subs[14]);
							break;
						}
					}
				}
			}
			//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.str_replace(' ', '', $title).'_'.date('dMY').'.xls"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		}
	}
	public function writeBulkLoadOutput($path, $rows, $set, $pws, $realm) {
		$title = '';
		$columns = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
		$writeHeaders = ['USERNAME', 'PASSWORD', 'CUSTOMERTYPE', 'STATUS', 'ORDER NUMBER', 'CUSTOMER NAME', 'SERVICE NUMBER', 'REDIRECTED', 'SERVICE', 'IP ADDRESS/CABINET NAME', 'NET ADDRESS/CABINET NAME', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'REMARKS'];
		$headers = array(
			'B1' => 'USERNAME',
			'C1' => 'PASSWORD',
			'D1' => 'CUSTOMERTYPE',
			'E1' => 'STATUS',
			'F1' => 'ORDER NUMBER',
			'G1' => 'CUSTOMER NAME',
			'H1' => 'SERVICE NUMBER',
			'I1' => 'REDIRECTED',
			'J1' => 'SERVICE',
			'K1' => 'IP ADDRESS/CABINET NAME',
			'L1' => 'NET ADDRESS/CABINET NAME',
			'M1' => 'ADDITIONAL SERVICE 1',
			'N1' => 'ADDITIONAL SERVICE 2',
			'O1' => 'REMARKS');
		$this->load->library('excel');
		$redFontColor = new PHPExcel_Style_Color();
		$redFontColor->setRGB('FF0000');
		if ($set == 'created') {
			$title = 'Bulk Load - Created Users';
		} else if ($set == 'usernameExists') {
			$title = 'Bulk Load - Existing Users';
		} else if ($set == 'invalidData') {
			$title = 'Bulk Load - Invalid Data';
		} else if ($set == 'ipNetError') {
			$title = 'Bulk Load - IP Net Error';
		} else if ($set == 'npmError') {
			$title = 'Bulk Load - NPM Error';
		}
		if ($set == 'usernameExists' || $set == 'created') {
			$reader = PHPExcel_IOFactory::createReader('Excel5');
			$reader->setReadDataOnly(true);
			$xls = $reader->load($path);
			$xls->setActiveSheetIndex(0);
			$subscribers = [];
			$this->load->model('subscribermain');
			for ($i = 0; $i < count($rows); $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':O'.$rows[$i]);
				$userIdentity = $row[0][0].'@'.$realm;
				$subscribers[] = $this->subscribermain->findByUserIdentity($userIdentity);
			}
			$sheet = new PHPExcel();
			$sheet ->getProperties()->setTitle($title)->setDescription($title);
			$sheet->setActiveSheetIndex(0);	
			$sheet->getDefaultStyle()->getFont()->setName('Calibri');
			$sheet->getDefaultStyle()->getFont()->setSize(11);
			$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel5");
			$objSheet = $sheet->getActiveSheet();
			$objSheet->setTitle($title);
			$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(11);
			$objSheet->getStyle('B1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('D1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('E1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('G1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('H1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('I1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('J1')->getFont()->setColor($redFontColor);
			for ($i = 0; $i < count($columns); $i++) {
				$cell = strval($columns[$i].'1');
				$objSheet->getCell($cell)->setValue(strval($writeHeaders[$i]));
			}
			for ($i = 0; $i < count($subscribers); $i++) {
				$subs = $subscribers[$i];
				for ($j = 0; $j < count($headers); $j++) {
					$cell = strval($columns[$j].($i + 2));
					switch ($columns[$j]) {
						case 'B': {
							$username = explode('@', $subs['USERNAME']);
							$objSheet->getCell($cell)->setValue($username[0]);
							break;
						}
						case 'C': {
							$objSheet->getCell($cell)->setValue(is_null($subs['PASSWORD']) ? '' : $subs['PASSWORD']);
							break;
						}
						case 'D' : {
							$val = '';
							if ($subs['CUSTOMERTYPE'] == 'Residential' || $subs['CUSTOMERTYPE'] == 'R') {
								$val = 'R';
							} else if ($subs['CUSTOMERTYPE'] == 'Business' || $subs['CUSTOMERTYPE'] == 'B') {
								$val = 'B';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;
						}
						case 'E': {
							$objSheet->getCell($cell)->setValue($subs['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D');
							break;
						}
						case 'F': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBORDERNUMBER']) ? '' : $subs['RBORDERNUMBER']);
							break;
						}
						case 'G': {
							$objSheet->getCell($cell)->setValue($subs['RBCUSTOMERNAME']);
							break;	
						}
						case 'H': {
							$objSheet->getCell($cell)->setValue($subs['RBSERVICENUMBER']);
							break;	
						}
						case 'I': {
							$val = '';
							if ($subs['RBENABLED'] == 'Yes' || $subs['RBENABLED'] == 'Y') {
								$val = 'Y';
							} else if ($subs['RBENABLED'] == 'No' || $subs['RBENABLED'] == 'N') {
								$val = 'N';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;	
						}
						case 'J': {
							$objSheet->getCell($cell)->setValue(str_replace('~', '-', $subs['RBACCOUNTPLAN']));
							break;	
						}
						case 'K': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS']);
							break;	
						}
						case 'L': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC']);
							break;	
						}
						case 'M': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE1']) ? '' : $subs['RBADDITIONALSERVICE1']);
							break;	
						}
						case 'N': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE2']) ? '' : $subs['RBADDITIONALSERVICE2']);
							break;	
						}
						case 'O': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBREMARKS']) ? '' : $subs['RBREMARKS']);
							break;
						}
					}
				}
			}
			for ($i = 0; $i < count($columns); $i++) {
				$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
			}
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.str_replace(' ', '', $title).'_'.date('dMY').'.xls"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		} else {
			$reader = PHPExcel_IOFactory::createReader('Excel5');
			$reader->setReadDataOnly(true);
			$xls = $reader->load($path);
			$xls->setActiveSheetIndex(0);
			$data = [];
			for ($i = 0; $i < count($rows); $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':O'.$rows[$i]);
				$data[] = $row[0];
			}
			$sheet = new PHPExcel();
			$sheet ->getProperties()->setTitle($title)->setDescription($title);
			$sheet->setActiveSheetIndex(0);	
			$sheet->getDefaultStyle()->getFont()->setName('Calibri');
			$sheet->getDefaultStyle()->getFont()->setSize(11);
			$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel5");
			$objSheet = $sheet->getActiveSheet();
			$objSheet->setTitle($title);
			$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(11);
			$objSheet->getStyle('B1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('D1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('E1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('G1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('H1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('I1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('J1')->getFont()->setColor($redFontColor);
			for ($i = 0; $i < count($columns); $i++) {
				$cell = strval($columns[$i].'1');
				$objSheet->getCell($cell)->setValue(strval($writeHeaders[$i]));
			}
			for ($i = 0; $i < count($columns); $i++) {
				$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
			}
			for ($i = 0; $i < count($data); $i++) {
				$subs = $data[$i];
				log_message('info', json_encode($subs));
				for ($j = 0; $j < count($headers); $j++) {
					$cell = strval($columns[$j].($i + 2));
					switch ($columns[$j]) {
						case 'B': {
							$objSheet->getCell($cell)->setValue($subs[0]);
							break;
						}
						case 'C': {
							$objSheet->getCell($cell)->setValue($subs[1]);
							break;
						}
						case 'D' : {
							$val = '';
							if ($subs[2] == 'Residential' || $subs[2] == 'R') {
								$val = 'R';
							} else if ($subs[2] == 'Business' || $subs[2] == 'B') {
								$val = 'B';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;
						}
						case 'E': {
							$objSheet->getCell($cell)->setValue($subs[3]);
							break;
						}
						case 'F': {
							$objSheet->getCell($cell)->setValue($subs[4]);
							break;
						}
						case 'G': {
							$objSheet->getCell($cell)->setValue($subs[5]);
							break;	
						}
						case 'H': {
							$objSheet->getCell($cell)->setValue($subs[6]);
							break;	
						}
						case 'I': {
							$val = '';
							if ($subs[7] == 'Yes' || $subs[7] == 'Y') {
								$val = 'Y';
							} else if ($subs[7] == 'No' || $subs[7] == 'N') {
								$val = 'N';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;	
						}
						case 'J': {
							$objSheet->getCell($cell)->setValue(str_replace('~', '-', $subs[8]));
							break;	
						}
						case 'K': {
							$objSheet->getCell($cell)->setValue($subs[9]);
							break;	
						}
						case 'L': {
							$objSheet->getCell($cell)->setValue($subs[10]);
							break;	
						}
						case 'M': {
							$objSheet->getCell($cell)->setValue($subs[11]);
							break;	
						}
						case 'N': {
							$objSheet->getCell($cell)->setValue($subs[12]);
							break;	
						}
						case 'O': {
							$objSheet->getCell($cell)->setValue($subs[13]);
							break;
						}
					}
				}
			}
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.str_replace(' ', '', $title).'_'.date('dMY').'.xls"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		}
	}
	public function writeBulkLoadOutputV2($path, $rows, $set, $pws, $realm) {
		$title = '';
		$columns = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P'];
		$writeHeaders = ['USERNAME', 'PASSWORD', 'CUSTOMERTYPE', 'STATUS', 'ORDER NUMBER', 'CUSTOMER NAME', 'SERVICE NUMBER', 'REDIRECTED', 'SERVICE', 'IPV6 ADDRESS/CABINET NAME', 'IP ADDRESS/CABINET NAME', 'NET ADDRESS/CABINET NAME', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'REMARKS'];
		$headers = array(
			'B1' => 'USERNAME',
			'C1' => 'PASSWORD',
			'D1' => 'CUSTOMERTYPE',
			'E1' => 'STATUS',
			'F1' => 'ORDER NUMBER',
			'G1' => 'CUSTOMER NAME',
			'H1' => 'SERVICE NUMBER',
			'I1' => 'REDIRECTED',
			'J1' => 'SERVICE',
			'K1' => 'IPV6 ADDRESS/CABINET NAME',
			'L1' => 'IP ADDRESS/CABINET NAME',
			'M1' => 'NET ADDRESS/CABINET NAME',
			'N1' => 'ADDITIONAL SERVICE 1',
			'O1' => 'ADDITIONAL SERVICE 2',
			'P1' => 'REMARKS');
		$this->load->library('excel');
		$redFontColor = new PHPExcel_Style_Color();
		$redFontColor->setRGB('FF0000');
		if ($set == 'created') {
			$title = 'Bulk Load - Created Users';
		} else if ($set == 'usernameExists') {
			$title = 'Bulk Load - Existing Users';
		} else if ($set == 'invalidData') {
			$title = 'Bulk Load - Invalid Data';
		} else if ($set == 'ipNetError') {
			$title = 'Bulk Load - IP Net Error';
		} else if ($set == 'npmError') {
			$title = 'Bulk Load - NPM Error';
		}
		if ($set == 'usernameExists' || $set == 'created') {
			$reader = PHPExcel_IOFactory::createReader('Excel5');
			$reader->setReadDataOnly(true);
			$xls = $reader->load($path);
			$xls->setActiveSheetIndex(0);
			$subscribers = [];
			$this->load->model('subscribermain');
			for ($i = 0; $i < count($rows); $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':P'.$rows[$i]);
				$userIdentity = $row[0][0].'@'.$realm;
				$subscribers[] = $this->subscribermain->findByUserIdentity($userIdentity);
			}
			$sheet = new PHPExcel();
			$sheet ->getProperties()->setTitle($title)->setDescription($title);
			$sheet->setActiveSheetIndex(0);	
			$sheet->getDefaultStyle()->getFont()->setName('Calibri');
			$sheet->getDefaultStyle()->getFont()->setSize(11);
			$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel5");
			$objSheet = $sheet->getActiveSheet();
			$objSheet->setTitle($title);
			$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(11);
			$objSheet->getStyle('B1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('D1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('E1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('G1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('H1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('I1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('J1')->getFont()->setColor($redFontColor);
			for ($i = 0; $i < count($columns); $i++) {
				$cell = strval($columns[$i].'1');
				$objSheet->getCell($cell)->setValue(strval($writeHeaders[$i]));
			}
			for ($i = 0; $i < count($subscribers); $i++) {
				$subs = $subscribers[$i];
				for ($j = 0; $j < count($headers); $j++) {
					$cell = strval($columns[$j].($i + 2));
					switch ($columns[$j]) {
						case 'B': {
							$username = explode('@', $subs['USERNAME']);
							$objSheet->getCell($cell)->setValue($username[0]);
							break;
						}
						case 'C': {
							$objSheet->getCell($cell)->setValue(is_null($subs['PASSWORD']) ? '' : $subs['PASSWORD']);
							break;
						}
						case 'D' : {
							$val = '';
							if ($subs['CUSTOMERTYPE'] == 'Residential' || $subs['CUSTOMERTYPE'] == 'R') {
								$val = 'R';
							} else if ($subs['CUSTOMERTYPE'] == 'Business' || $subs['CUSTOMERTYPE'] == 'B') {
								$val = 'B';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;
						}
						case 'E': {
							$objSheet->getCell($cell)->setValue($subs['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D');
							break;
						}
						case 'F': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBORDERNUMBER']) ? '' : $subs['RBORDERNUMBER']);
							break;
						}
						case 'G': {
							$objSheet->getCell($cell)->setValue($subs['RBCUSTOMERNAME']);
							break;	
						}
						case 'H': {
							$objSheet->getCell($cell)->setValue($subs['RBSERVICENUMBER']);
							break;	
						}
						case 'I': {
							$val = '';
							if ($subs['RBENABLED'] == 'Yes' || $subs['RBENABLED'] == 'Y') {
								$val = 'Y';
							} else if ($subs['RBENABLED'] == 'No' || $subs['RBENABLED'] == 'N') {
								$val = 'N';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;	
						}
						case 'J': {
							$objSheet->getCell($cell)->setValue(str_replace('~', '-', $subs['RBACCOUNTPLAN']));
							break;	
						}
						case 'K': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE4']) ? '' : $subs['RBADDITIONALSERVICE4']);
							break;
						}
						case 'L': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS']);
							break;	
						}
						case 'M': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC']);
							break;	
						}
						case 'N': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE1']) ? '' : $subs['RBADDITIONALSERVICE1']);
							break;	
						}
						case 'O': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE2']) ? '' : $subs['RBADDITIONALSERVICE2']);
							break;	
						}
						case 'P': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBREMARKS']) ? '' : $subs['RBREMARKS']);
							break;
						}
					}
				}
			}
			for ($i = 0; $i < count($columns); $i++) {
				$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
			}
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.str_replace(' ', '', $title).'_'.date('dMY').'.xls"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		} else {
			$reader = PHPExcel_IOFactory::createReader('Excel5');
			$reader->setReadDataOnly(true);
			$xls = $reader->load($path);
			$xls->setActiveSheetIndex(0);
			$data = [];
			for ($i = 0; $i < count($rows); $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':P'.$rows[$i]);
				$data[] = $row[0];
			}
			$sheet = new PHPExcel();
			$sheet ->getProperties()->setTitle($title)->setDescription($title);
			$sheet->setActiveSheetIndex(0);	
			$sheet->getDefaultStyle()->getFont()->setName('Calibri');
			$sheet->getDefaultStyle()->getFont()->setSize(11);
			$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel5");
			$objSheet = $sheet->getActiveSheet();
			$objSheet->setTitle($title);
			$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(11);
			$objSheet->getStyle('B1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('D1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('E1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('G1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('H1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('I1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('J1')->getFont()->setColor($redFontColor);
			for ($i = 0; $i < count($columns); $i++) {
				$cell = strval($columns[$i].'1');
				$objSheet->getCell($cell)->setValue(strval($writeHeaders[$i]));
			}
			for ($i = 0; $i < count($columns); $i++) {
				$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
			}
			for ($i = 0; $i < count($data); $i++) {
				$subs = $data[$i];
				log_message('info', json_encode($subs));
				for ($j = 0; $j < count($headers); $j++) {
					$cell = strval($columns[$j].($i + 2));
					switch ($columns[$j]) {
						case 'B': {
							$objSheet->getCell($cell)->setValue($subs[0]);
							break;
						}
						case 'C': {
							$objSheet->getCell($cell)->setValue($subs[1]);
							break;
						}
						case 'D' : {
							$val = '';
							if ($subs[2] == 'Residential' || $subs[2] == 'R') {
								$val = 'R';
							} else if ($subs[2] == 'Business' || $subs[2] == 'B') {
								$val = 'B';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;
						}
						case 'E': {
							$objSheet->getCell($cell)->setValue($subs[3]);
							break;
						}
						case 'F': {
							$objSheet->getCell($cell)->setValue($subs[4]);
							break;
						}
						case 'G': {
							$objSheet->getCell($cell)->setValue($subs[5]);
							break;	
						}
						case 'H': {
							$objSheet->getCell($cell)->setValue($subs[6]);
							break;	
						}
						case 'I': {
							$val = '';
							if ($subs[7] == 'Yes' || $subs[7] == 'Y') {
								$val = 'Y';
							} else if ($subs[7] == 'No' || $subs[7] == 'N') {
								$val = 'N';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;	
						}
						case 'J': {
							$objSheet->getCell($cell)->setValue(str_replace('~', '-', $subs[8]));
							break;	
						}
						case 'K': {
							$objSheet->getCell($cell)->setValue($subs[9]);
							break;
						}
						case 'L': {
							$objSheet->getCell($cell)->setValue($subs[10]);
							break;
						}
						case 'M': {
							$objSheet->getCell($cell)->setValue($subs[11]);
							break;	
						}
						case 'N': {
							$objSheet->getCell($cell)->setValue($subs[12]);
							break;	
						}
						case 'O': {
							$objSheet->getCell($cell)->setValue($subs[13]);
							break;	
						}
						case 'P': {
							$objSheet->getCell($cell)->setValue($subs[14]);
							break;
						}
					}
				}
			}
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.str_replace(' ', '', $title).'_'.date('dMY').'.xls"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		}
	}
	public function writeBulkModifyOutput($path, $rows, $set, $realm) {
		$title = '';
		$columns = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
		$writeHeaders = ['USERNAME', 'PASSWORD', 'CUSTOMERTYPE', 'STATUS', 'ORDER NUMBER', 'CUSTOMER NAME', 'SERVICE NUMBER', 'REDIRECTED', 'SERVICE', 'IP ADDRESS/CABINET NAME', 'NET ADDRESS/CABINET NAME', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'REMARKS'];
		$headers = array(
			'B1' => 'USERNAME',
			'C1' => 'PASSWORD',
			'D1' => 'CUSTOMERTYPE',
			'E1' => 'STATUS',
			'F1' => 'ORDER NUMBER',
			'G1' => 'CUSTOMER NAME',
			'H1' => 'SERVICE NUMBER',
			'I1' => 'REDIRECTED',
			'J1' => 'SERVICE',
			'K1' => 'IP ADDRESS/CABINET NAME',
			'L1' => 'NET ADDRESS/CABINET NAME',
			'M1' => 'ADDITIONAL SERVICE 1',
			'N1' => 'ADDITIONAL SERVICE 2',
			'O1' => 'REMARKS');
		$this->load->library('excel');
		$redFontColor = new PHPExcel_Style_Color();
		$redFontColor->setRGB('FF0000');
		if ($set == 'updated') {
			$title = 'Bulk Modify - Updated Users';
		} else if ($set == 'usernameDNE') {
			$title = 'Bulk Modify - No Such Users';
		} else if ($set == 'invalidData') {
			$title = 'Bulk Modify - Invalid Data';
		} else if ($set == 'ipNetError') {
			$title = 'Bulk Modify - IP Net Error';
		} else if ($set == 'npmError') {
			$title = 'Bulk Modify - NPM Error';
		}
		if ($set == 'updated' || $set == 'invalidData' || $set == 'ipNetError' || $set == 'npmError') {
			$reader = PHPExcel_IOFactory::createReader('Excel5');
			$reader->setReadDataOnly(true);
			$xls = $reader->load($path);
			$xls->setActiveSheetIndex(0);
			$subscribers = [];
			$this->load->model('subscribermain');
			for ($i = 0; $i < count($rows); $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':O'.$rows[$i]);
				$userIdentity = $row[0][0].'@'.$realm;
				$subscribers[] = $this->subscribermain->findByUserIdentity($userIdentity);
			}
			$sheet = new PHPExcel();
			$sheet ->getProperties()->setTitle($title)->setDescription($title);
			$sheet->setActiveSheetIndex(0);	
			$sheet->getDefaultStyle()->getFont()->setName('Calibri');
			$sheet->getDefaultStyle()->getFont()->setSize(11);
			$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel5");
			$objSheet = $sheet->getActiveSheet();
			$objSheet->setTitle($title);
			$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(11);
			$objSheet->getStyle('B1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('D1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('E1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('G1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('H1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('I1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('J1')->getFont()->setColor($redFontColor);
			for ($i = 0; $i < count($columns); $i++) {
				$cell = strval($columns[$i].'1');
				$objSheet->getCell($cell)->setValue(strval($writeHeaders[$i]));
			}
			for ($i = 0; $i < count($subscribers); $i++) {
				$subs = $subscribers[$i];
				for ($j = 0; $j < count($headers); $j++) {
					$cell = strval($columns[$j].($i + 2));
					switch ($columns[$j]) {
						case 'B': {
							$username = explode('@', $subs['USERNAME']);
							$objSheet->getCell($cell)->setValue($username[0]);
							break;
						}
						case 'C': {
							$objSheet->getCell($cell)->setValue(is_null($subs['PASSWORD']) ? '' : $subs['PASSWORD']);
							break;
						}
						case 'D' : {
							$val = '';
							if ($subs['CUSTOMERTYPE'] == 'Residential' || $subs['CUSTOMERTYPE'] == 'R') {
								$val = 'R';
							} else if ($subs['CUSTOMERTYPE'] == 'Business' || $subs['CUSTOMERTYPE'] == 'B') {
								$val = 'B';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;
						}
						case 'E': {
							$objSheet->getCell($cell)->setValue($subs['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D');
							break;
						}
						case 'F': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBORDERNUMBER']) ? '' : $subs['RBORDERNUMBER']);
							break;
						}
						case 'G': {
							$objSheet->getCell($cell)->setValue($subs['RBCUSTOMERNAME']);
							break;	
						}
						case 'H': {
							$objSheet->getCell($cell)->setValue($subs['RBSERVICENUMBER']);
							break;	
						}
						case 'I': {
							$val = '';
							if ($subs['RBENABLED'] == 'Yes' || $subs['RBENABLED'] == 'Y') {
								$val = 'Y';
							} else if ($subs['RBENABLED'] == 'No' || $subs['RBENABLED'] == 'N') {
								$val = 'N';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;	
						}
						case 'J': {
							$objSheet->getCell($cell)->setValue(str_replace('~', '-', $subs['RBACCOUNTPLAN']));
							break;	
						}
						case 'K': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS']);
							break;	
						}
						case 'L': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC']);
							break;	
						}
						case 'M': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE1']) ? '' : $subs['RBADDITIONALSERVICE1']);
							break;	
						}
						case 'N': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE2']) ? '' : $subs['RBADDITIONALSERVICE2']);
							break;	
						}
						case 'O': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBREMARKS']) ? '' : $subs['RBREMARKS']);
							break;
						}
					}
				}
			}
			for ($i = 0; $i < count($columns); $i++) {
				$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
			}
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.str_replace(' ', '', $title).'_'.date('dMY').'.xls"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		} else {
			$reader = PHPExcel_IOFactory::createReader('Excel5');
			$reader->setReadDataOnly(true);
			$xls = $reader->load($path);
			$xls->setActiveSheetIndex(0);
			$data = [];
			for ($i = 0; $i < count($rows); $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':O'.$rows[$i]);
				$data[] = $row[0];
			}
			$sheet = new PHPExcel();
			$sheet ->getProperties()->setTitle($title)->setDescription($title);
			$sheet->setActiveSheetIndex(0);	
			$sheet->getDefaultStyle()->getFont()->setName('Calibri');
			$sheet->getDefaultStyle()->getFont()->setSize(11);
			$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel5");
			$objSheet = $sheet->getActiveSheet();
			$objSheet->setTitle($title);
			$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(11);
			$objSheet->getStyle('B1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('D1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('E1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('G1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('H1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('I1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('J1')->getFont()->setColor($redFontColor);
			for ($i = 0; $i < count($columns); $i++) {
				$cell = strval($columns[$i].'1');
				$objSheet->getCell($cell)->setValue(strval($writeHeaders[$i]));
			}
			for ($i = 0; $i < count($columns); $i++) {
				$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
			}
			for ($i = 0; $i < count($data); $i++) {
				$subs = $data[$i];
				for ($j = 0; $j < count($headers); $j++) {
					$cell = strval($columns[$j].($i + 2));
					switch ($columns[$j]) {
						case 'B': {
							$objSheet->getCell($cell)->setValue($subs[0]);
							break;
						}
						case 'C': {
							$objSheet->getCell($cell)->setValue($subs[1]);
							break;
						}
						case 'D' : {
							$val = '';
							if ($subs[2] == 'Residential' || $subs[2] == 'R') {
								$val = 'R';
							} else if ($subs[2] == 'Business' || $subs[2] == 'B') {
								$val = 'B';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;
						}
						case 'E': {
							$objSheet->getCell($cell)->setValue($subs[3]);
							break;
						}
						case 'F': {
							$objSheet->getCell($cell)->setValue($subs[4]);
							break;
						}
						case 'G': {
							$objSheet->getCell($cell)->setValue($subs[5]);
							break;	
						}
						case 'H': {
							$objSheet->getCell($cell)->setValue($subs[6]);
							break;	
						}
						case 'I': {
							$val = '';
							if ($subs[7] == 'Yes' || $subs[7] == 'Y') {
								$val = 'Y';
							} else if ($subs[7] == 'No' || $subs[7] == 'N') {
								$val = 'N';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;	
						}
						case 'J': {
							$objSheet->getCell($cell)->setValue(str_replace('~', '-', $subs[8]));
							break;	
						}
						case 'K': {
							$objSheet->getCell($cell)->setValue($subs[9]);
							break;	
						}
						case 'L': {
							$objSheet->getCell($cell)->setValue($subs[10]);
							break;	
						}
						case 'M': {
							$objSheet->getCell($cell)->setValue($subs[11]);
							break;	
						}
						case 'N': {
							$objSheet->getCell($cell)->setValue($subs[12]);
							break;	
						}
						case 'O': {
							$objSheet->getCell($cell)->setValue($subs[13]);
							break;
						}
					}
				}
			}
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.str_replace(' ', '', $title).'_'.date('dMY').'.xls"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		}
	}
	public function writeBulkModifyOutputV2($path, $rows, $set, $realm) {
		$title = '';
		$columns = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P'];
		$writeHeaders = ['USERNAME', 'PASSWORD', 'CUSTOMERTYPE', 'STATUS', 'ORDER NUMBER', 'CUSTOMER NAME', 'SERVICE NUMBER', 'REDIRECTED', 'SERVICE', 'IPV6 ADDRESS/CABINET NAME', 'IP ADDRESS/CABINET NAME', 'NET ADDRESS/CABINET NAME', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'REMARKS'];
		$headers = array(
			'B1' => 'USERNAME',
			'C1' => 'PASSWORD',
			'D1' => 'CUSTOMERTYPE',
			'E1' => 'STATUS',
			'F1' => 'ORDER NUMBER',
			'G1' => 'CUSTOMER NAME',
			'H1' => 'SERVICE NUMBER',
			'I1' => 'REDIRECTED',
			'J1' => 'SERVICE',
			'K1' => 'IPV6 ADDRESS/CABINET NAME',
			'L1' => 'IP ADDRESS/CABINET NAME',
			'M1' => 'NET ADDRESS/CABINET NAME',
			'N1' => 'ADDITIONAL SERVICE 1',
			'O1' => 'ADDITIONAL SERVICE 2',
			'P1' => 'REMARKS');
		$this->load->library('excel');
		$redFontColor = new PHPExcel_Style_Color();
		$redFontColor->setRGB('FF0000');
		if ($set == 'updated') {
			$title = 'Bulk Modify - Updated Users';
		} else if ($set == 'usernameDNE') {
			$title = 'Bulk Modify - No Such Users';
		} else if ($set == 'invalidData') {
			$title = 'Bulk Modify - Invalid Data';
		} else if ($set == 'ipNetError') {
			$title = 'Bulk Modify - IP Net Error';
		} else if ($set == 'npmError') {
			$title = 'Bulk Modify - NPM Error';
		}
		if ($set == 'updated' || $set == 'invalidData' || $set == 'ipNetError' || $set == 'npmError') {
			$reader = PHPExcel_IOFactory::createReader('Excel5');
			$reader->setReadDataOnly(true);
			$xls = $reader->load($path);
			$xls->setActiveSheetIndex(0);
			$subscribers = [];
			$this->load->model('subscribermain');
			for ($i = 0; $i < count($rows); $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':P'.$rows[$i]);
				$userIdentity = $row[0][0].'@'.$realm;
				$subscribers[] = $this->subscribermain->findByUserIdentity($userIdentity);
			}
			$sheet = new PHPExcel();
			$sheet ->getProperties()->setTitle($title)->setDescription($title);
			$sheet->setActiveSheetIndex(0);	
			$sheet->getDefaultStyle()->getFont()->setName('Calibri');
			$sheet->getDefaultStyle()->getFont()->setSize(11);
			$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel5");
			$objSheet = $sheet->getActiveSheet();
			$objSheet->setTitle($title);
			$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(11);
			$objSheet->getStyle('B1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('D1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('E1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('G1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('H1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('I1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('J1')->getFont()->setColor($redFontColor);
			for ($i = 0; $i < count($columns); $i++) {
				$cell = strval($columns[$i].'1');
				$objSheet->getCell($cell)->setValue(strval($writeHeaders[$i]));
			}
			for ($i = 0; $i < count($subscribers); $i++) {
				$subs = $subscribers[$i];
				for ($j = 0; $j < count($headers); $j++) {
					$cell = strval($columns[$j].($i + 2));
					switch ($columns[$j]) {
						case 'B': {
							$username = explode('@', $subs['USERNAME']);
							$objSheet->getCell($cell)->setValue($username[0]);
							break;
						}
						case 'C': {
							$objSheet->getCell($cell)->setValue(is_null($subs['PASSWORD']) ? '' : $subs['PASSWORD']);
							break;
						}
						case 'D' : {
							$val = '';
							if ($subs['CUSTOMERTYPE'] == 'Residential' || $subs['CUSTOMERTYPE'] == 'R') {
								$val = 'R';
							} else if ($subs['CUSTOMERTYPE'] == 'Business' || $subs['CUSTOMERTYPE'] == 'B') {
								$val = 'B';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;
						}
						case 'E': {
							$objSheet->getCell($cell)->setValue($subs['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D');
							break;
						}
						case 'F': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBORDERNUMBER']) ? '' : $subs['RBORDERNUMBER']);
							break;
						}
						case 'G': {
							$objSheet->getCell($cell)->setValue($subs['RBCUSTOMERNAME']);
							break;	
						}
						case 'H': {
							$objSheet->getCell($cell)->setValue($subs['RBSERVICENUMBER']);
							break;	
						}
						case 'I': {
							$val = '';
							if ($subs['RBENABLED'] == 'Yes' || $subs['RBENABLED'] == 'Y') {
								$val = 'Y';
							} else if ($subs['RBENABLED'] == 'No' || $subs['RBENABLED'] == 'N') {
								$val = 'N';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;	
						}
						case 'J': {
							$objSheet->getCell($cell)->setValue(str_replace('~', '-', $subs['RBACCOUNTPLAN']));
							break;	
						}
						case 'K': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBIPADDRESS']) ? '' : $subs['RBADDITIONALSERVICE4']);
							break;
						}
						case 'L': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS']);
							break;
						}
						case 'M': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC']);
							break;	
						}
						case 'N': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE1']) ? '' : $subs['RBADDITIONALSERVICE1']);
							break;	
						}
						case 'O': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE2']) ? '' : $subs['RBADDITIONALSERVICE2']);
							break;	
						}
						case 'P': {
							$objSheet->getCell($cell)->setValue(is_null($subs['RBREMARKS']) ? '' : $subs['RBREMARKS']);
							break;
						}
					}
				}
			}
			for ($i = 0; $i < count($columns); $i++) {
				$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
			}
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.str_replace(' ', '', $title).'_'.date('dMY').'.xls"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		} else {
			$reader = PHPExcel_IOFactory::createReader('Excel5');
			$reader->setReadDataOnly(true);
			$xls = $reader->load($path);
			$xls->setActiveSheetIndex(0);
			$data = [];
			for ($i = 0; $i < count($rows); $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':P'.$rows[$i]);
				$data[] = $row[0];
			}
			$sheet = new PHPExcel();
			$sheet ->getProperties()->setTitle($title)->setDescription($title);
			$sheet->setActiveSheetIndex(0);	
			$sheet->getDefaultStyle()->getFont()->setName('Calibri');
			$sheet->getDefaultStyle()->getFont()->setSize(11);
			$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel5");
			$objSheet = $sheet->getActiveSheet();
			$objSheet->setTitle($title);
			$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(11);
			$objSheet->getStyle('B1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('D1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('E1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('G1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('H1')->getFont()->setColor($redFontColor);
			// $objSheet->getStyle('I1')->getFont()->setColor($redFontColor);
			$objSheet->getStyle('J1')->getFont()->setColor($redFontColor);
			for ($i = 0; $i < count($columns); $i++) {
				$cell = strval($columns[$i].'1');
				$objSheet->getCell($cell)->setValue(strval($writeHeaders[$i]));
			}
			for ($i = 0; $i < count($columns); $i++) {
				$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
			}
			for ($i = 0; $i < count($data); $i++) {
				$subs = $data[$i];
				for ($j = 0; $j < count($headers); $j++) {
					$cell = strval($columns[$j].($i + 2));
					switch ($columns[$j]) {
						case 'B': {
							$objSheet->getCell($cell)->setValue($subs[0]);
							break;
						}
						case 'C': {
							$objSheet->getCell($cell)->setValue($subs[1]);
							break;
						}
						case 'D' : {
							$val = '';
							if ($subs[2] == 'Residential' || $subs[2] == 'R') {
								$val = 'R';
							} else if ($subs[2] == 'Business' || $subs[2] == 'B') {
								$val = 'B';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;
						}
						case 'E': {
							$objSheet->getCell($cell)->setValue($subs[3]);
							break;
						}
						case 'F': {
							$objSheet->getCell($cell)->setValue($subs[4]);
							break;
						}
						case 'G': {
							$objSheet->getCell($cell)->setValue($subs[5]);
							break;	
						}
						case 'H': {
							$objSheet->getCell($cell)->setValue($subs[6]);
							break;	
						}
						case 'I': {
							$val = '';
							if ($subs[7] == 'Yes' || $subs[7] == 'Y') {
								$val = 'Y';
							} else if ($subs[7] == 'No' || $subs[7] == 'N') {
								$val = 'N';
							}
							$objSheet->getCell($cell)->setValue($val);
							break;	
						}
						case 'J': {
							$objSheet->getCell($cell)->setValue(str_replace('~', '-', $subs[8]));
							break;	
						}
						case 'K': {
							$objSheet->getCell($cell)->setValue($subs[9]);
							break;
						}
						case 'L': {
							$objSheet->getCell($cell)->setValue($subs[10]);
							break;
						}
						case 'M': {
							$objSheet->getCell($cell)->setValue($subs[11]);
							break;	
						}
						case 'N': {
							$objSheet->getCell($cell)->setValue($subs[12]);
							break;	
						}
						case 'O': {
							$objSheet->getCell($cell)->setValue($subs[13]);
							break;	
						}
						case 'P': {
							$objSheet->getCell($cell)->setValue($subs[14]);
							break;
						}
					}
				}
			}
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.str_replace(' ', '', $title).'_'.date('dMY').'.xls"');
			header('Cache-Control: max-age=0');
			$writer->save('php://output');
		}
	}
	public function verifyBulkUpdateFormat($uploadedFile) {
		$headers = array(
			'B1' => 'USERNAME',
			'C1' => 'PASSWORD',
			'D1' => 'CUSTOMERTYPE',
			'E1' => 'STATUS',
			'F1' => 'ORDER NUMBER',
			'G1' => 'CUSTOMER NAME',
			'H1' => 'SERVICE NUMBER',
			'I1' => 'REDIRECTED',
			'J1' => 'SERVICE',
			'K1' => 'IP ADDRESS/CABINET NAME',
			'L1' => 'NET ADDRESS/CABINET NAME',
			'M1' => 'ADDITIONAL SERVICE 1',
			'N1' => 'ADDITIONAL SERVICE 2',
			'O1' => 'REMARKS');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		for ($i = 0; $i < count($headers); $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;
	}
	public function verifyBulkUpdateFormatV2($uploadedFile) {
		$headers = array(
			'B1' => 'USERNAME',
			'C1' => 'PASSWORD',
			'D1' => 'CUSTOMERTYPE',
			'E1' => 'STATUS',
			'F1' => 'ORDER NUMBER',
			'G1' => 'CUSTOMER NAME',
			'H1' => 'SERVICE NUMBER',
			'I1' => 'REDIRECTED',
			'J1' => 'SERVICE',
			'K1' => 'IPV6 ADDRESS/CABINET NAME',
			'L1' => 'IP ADDRESS/CABINET NAME',
			'M1' => 'NET ADDRESS/CABINET NAME',
			'N1' => 'ADDITIONAL SERVICE 1',
			'O1' => 'ADDITIONAL SERVICE 2',
			'P1' => 'REMARKS');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		for ($i = 0; $i < count($headers); $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;
	}
	public function verifyBulkUpdateData($uploadedFile, $realm) {
		$valid = [];
		$invalid = [];
		$invalidRowNumbers = [];
		$validRowNumbers = [];
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$xls->setActiveSheetIndex(0);
		$rowCount = $xls->getSheet(0)->getHighestRow();
		log_message('info', 'ROW COUNT: '.$rowCount);
		if ($rowCount <= 1) {
			return false;
		} else {
			for ($i = 0; $i < $rowCount - 1; $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.($i + 2).':P'.($i + 2));
				$row[0][0] = $row[0][0].'@'.$realm;
				log_message('info', $i.'|VERIFY: '.json_encode($row));
				if (trim($row[0][0]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				$valid[] = $row[0];
				$validRowNumbers[] = ($i + 2);
			}
			return array('valid' => $valid, 'invalid' => $invalid, 'validRowNumbers' => $validRowNumbers, 'invalidRowNumbers' => $invalidRowNumbers);
		}
	}
	public function verifyBulkUnassignIPv6IPAndNetData($uploadedFile, $realm) {
		$valid = [];
		$invalid = [];
		$invalidRowNumbers = [];
		$validRowNumbers = [];
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$xls->setActiveSheetIndex(0);
		$rowCount = $xls->getSheet(0)->getHighestRow();
		log_message('info', 'ROW COUNT: '.$rowCount);
		if ($rowCount <= 1) {
			return false;
		} else {
			for ($i = 0; $i < $rowCount - 1; $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.($i + 2).':P'.($i + 2));
				log_message('info', $i.'|VERIFY: '.json_encode($row));
				if (trim($row[0][0]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				$row[0][0] = $row[0][0].'@'.$realm;
				$valid[] = $row[0];
				$validRowNumbers[] = ($i + 2);
			}
			return array('valid' => $valid, 'invalid' => $invalid, 'validRowNumbers' => $validRowNumbers, 'invalidRowNumbers' => $invalidRowNumbers);
		}
	}
	public function verifyBulkUnassignIPv6IPAndNetFormat($uploadedFile) {
		return $this->verifyBulkUpdateFormat($uploadedFile);
		/*
		$headers = array(
			'B1' => 'USERNAME',
			'C1' => 'PASSWORD',
			'D1' => 'CUSTOMERTYPE',
			'E1' => 'STATUS',
			'F1' => 'ORDER NUMBER',
			'G1' => 'CUSTOMER NAME',
			'H1' => 'SERVICE NUMBER',
			'I1' => 'REDIRECTED',
			'J1' => 'SERVICE',
			'K1' => 'IP ADDRESS/CABINET NAME',
			'L1' => 'NET ADDRESS/CABINET NAME',
			'M1' => 'ADDITIONAL SERVICE 1',
			'N1' => 'ADDITIONAL SERVICE 2',
			'O1' => 'REMARKS');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		for ($i = 0; $i < count($headers); $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;
		*/
	}
	public function verifyBulkUnassignIPv6IPAndNetFormatV2($uploadedFile) {
		return $this->verifyBulkUpdateFormatV2($uploadedFile);
		/*
		$headers = array(
			'B1' => 'USERNAME',
			'C1' => 'PASSWORD',
			'D1' => 'CUSTOMERTYPE',
			'E1' => 'STATUS',
			'F1' => 'ORDER NUMBER',
			'G1' => 'CUSTOMER NAME',
			'H1' => 'SERVICE NUMBER',
			'I1' => 'REDIRECTED',
			'J1' => 'SERVICE',
			'K1' => 'IPV6 ADDRESS/CABINET NAME',
			'L1' => 'IP ADDRESS/CABINET NAME',
			'M1' => 'NET ADDRESS/CABINET NAME',
			'N1' => 'ADDITIONAL SERVICE 1',
			'O1' => 'ADDITIONAL SERVICE 2',
			'P1' => 'REMARKS');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		for ($i = 0; $i < count($headers); $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;
		*/
	}
	public function extractRowsToUpdate($path, $rows) {
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($path);
		$xls->setActiveSheetIndex(0);
		$data = [];
		for ($i = 0; $i < count($rows); $i++) {
			$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':O'.$rows[$i]);
			$data[] = $row[0];
		}
		return $data;
	}
	public function extractRowsToUpdateV2($path, $rows) {
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($path);
		$xls->setActiveSheetIndex(0);
		$data = [];
		for ($i = 0; $i < count($rows); $i++) {
			$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':P'.$rows[$i]);
			$data[] = $row[0];
		}
		return $data;
	}
	public function verifyBulkDeleteFormat($uploadedFile) {
		$headers = array(
			'B1' => 'USERNAME');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		for ($i = 0; $i < count($headers); $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;
	}
	public function verifyBulkDeleteData($uploadedFile, $realm) {
		$valid = [];
		$invalid = [];
		$invalidRowNumbers = [];
		$validRowNumbers = [];
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$xls->setActiveSheetIndex(0);
		$rowCount = $xls->getSheet(0)->getHighestRow();
		log_message('info', 'ROW COUNT: '.$rowCount);
		if ($rowCount <= 1) {
			return false;
		} else {
			for ($i = 0; $i < $rowCount - 1; $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.($i + 2));
				$row[0][0] = $row[0][0].'@'.$realm;
				log_message('info', $i.'|VERIFY: '.json_encode($row));
				if (trim($row[0][0]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				$valid[] = $row[0];
				$validRowNumbers[] = ($i + 2);
			}
			return array('valid' => $valid, 'invalid' => $invalid, 'validRowNumbers' => $validRowNumbers, 'invalidRowNumbers' => $invalidRowNumbers);
		}
	}
	/*
	public function extractRowsToDelete($path, $rows) {
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($path);
		$xls->setActiveSheetIndex(0);
		$data = [];
		for ($i = 0; $i < count($rows); $i++) {
			$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i]);
			$data[] = $row[0];
		}
		return $data;
	}
	*/
	public function writeToDeletedSubscriberFile($subscribers, $date) {
		$data = [];
		for ($i = 0; $i < count($subscribers); $i++) {
			if (!isset($subscribers[$i])) {
				continue;
			}
			$subscriber = $subscribers[$i];
			$data[] = array(
				$subscriber['RBREALM'], $subscriber['USERNAME'], $subscriber['PASSWORD'], 
				($subscriber['CUSTOMERTYPE'] == 'Residential' || $subscriber['CUSTOMERTYPE'] == 'R' ? 
					'R' : 
					($subscriber['CUSTOMERTYPE'] == 'Business' || $subscriber['CUSTOMERTYPE'] == 'B' ? 
						'B' : 
						'')), 
				$subscriber['CUSTOMERSTATUS'], 
				$subscriber['RBCUSTOMERNAME'], $subscriber['RBORDERNUMBER'], $subscriber['RBSERVICENUMBER'], str_replace('~', '-', $subscriber['RBACCOUNTPLAN']), 
				$subscriber['RBREMARKS']);
		}
		// $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/deletedusers/Deleted_Accounts_'.date('mdY-Hi', $date).'.csv', 'w');
		// $fp = fopen('/util/deleted_customer/Deleted_Accounts_'.date('mdY-Hi', $date).'.csv', 'w'); //tb
		$fp = fopen('/webutil/web_logs/deleted_subs/Deleted_Accounts_'.date('mdY-Hi', $date).'.csv', 'w'); //prod
		foreach ($data as $line) {
		    fputcsv($fp, $line);
		}
		fclose($fp);
	}
	public function writeDeletedSubscriberXlsForExtract($subscribers, $operation) {
		$title = '';
		if ($operation == 'delete') {
			$title = 'Bulk Delete - Deleted Users';
		} else if ($operation == 'update') {
			$title = 'Bulk Modify - Deleted Users';
		}
		$columns = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
		$writeHeaders = ['USERNAME', 'PASSWORD', 'CUSTOMERTYPE', 'STATUS', 'ORDER NUMBER', 'CUSTOMER NAME', 'SERVICE NUMBER', 'REDIRECTED', 'SERVICE', 'IP ADDRESS/CABINET NAME', 'NET ADDRESS/CABINET NAME', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'REMARKS'];
		$headers = array(
			'B1' => 'USERNAME',
			'C1' => 'PASSWORD',
			'D1' => 'CUSTOMERTYPE',
			'E1' => 'STATUS',
			'F1' => 'ORDER NUMBER',
			'G1' => 'CUSTOMER NAME',
			'H1' => 'SERVICE NUMBER',
			'I1' => 'REDIRECTED',
			'J1' => 'SERVICE',
			'K1' => 'IP ADDRESS/CABINET NAME',
			'L1' => 'NET ADDRESS/CABINET NAME',
			'M1' => 'ADDITIONAL SERVICE 1',
			'N1' => 'ADDITIONAL SERVICE 2',
			'O1' => 'REMARKS');
		$this->load->library('excel');
		$redFontColor = new PHPExcel_Style_Color();
		$redFontColor->setRGB('FF0000');
		
		$sheet = new PHPExcel();
		$sheet ->getProperties()->setTitle($title)->setDescription($title);
		$sheet->setActiveSheetIndex(0);	
		$sheet->getDefaultStyle()->getFont()->setName('Calibri');
		$sheet->getDefaultStyle()->getFont()->setSize(11);
		$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel5");
		$objSheet = $sheet->getActiveSheet();
		$objSheet->setTitle($title);
		$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(11);
		$objSheet->getStyle('B1')->getFont()->setColor($redFontColor);
		// $objSheet->getStyle('D1')->getFont()->setColor($redFontColor);
		$objSheet->getStyle('E1')->getFont()->setColor($redFontColor);
		// $objSheet->getStyle('G1')->getFont()->setColor($redFontColor);
		$objSheet->getStyle('H1')->getFont()->setColor($redFontColor);
		// $objSheet->getStyle('I1')->getFont()->setColor($redFontColor);
		$objSheet->getStyle('J1')->getFont()->setColor($redFontColor);
		for ($i = 0; $i < count($columns); $i++) {
			$cell = strval($columns[$i].'1');
			$objSheet->getCell($cell)->setValue(strval($writeHeaders[$i]));
		}
		for ($i = 0; $i < count($subscribers); $i++) {
			if (!isset($subscribers[$i])) {
				continue;
			}
			$subs = $subscribers[$i];
			for ($j = 0; $j < count($headers); $j++) {
				$cell = strval($columns[$j].($i + 2));
				switch ($columns[$j]) {
					case 'B': {
						$username = explode('@', $subs['USERNAME']);
						$objSheet->getCell($cell)->setValue($username[0]);
						break;
					}
					case 'C': {
						$objSheet->getCell($cell)->setValue(is_null($subs['PASSWORD']) ? '' : $subs['PASSWORD']);
						break;
					}
					case 'D' : {
						$val = '';
						if ($subs['CUSTOMERTYPE'] == 'Residential' || $subs['CUSTOMERTYPE'] == 'R') {
							$val = 'R';
						} else if ($subs['CUSTOMERTYPE'] == 'Business' || $subs['CUSTOMERTYPE'] == 'B') {
							$val = 'B';
						}
						$objSheet->getCell($cell)->setValue($val);
						break;
					}
					case 'E': {
						$objSheet->getCell($cell)->setValue($subs['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D');
						break;
					}
					case 'F': {
						$objSheet->getCell($cell)->setValue(is_null($subs['RBORDERNUMBER']) ? '' : $subs['RBORDERNUMBER']);
						break;
					}
					case 'G': {
						$objSheet->getCell($cell)->setValue($subs['RBCUSTOMERNAME']);
						break;	
					}
					case 'H': {
						$objSheet->getCell($cell)->setValue($subs['RBSERVICENUMBER']);
						break;	
					}
					case 'I': {
						$val = '';
						if ($subs['RBENABLED'] == 'Yes' || $subs['RBENABLED'] == 'Y') {
							$val = 'Y';
						} else if ($subs['RBENABLED'] == 'No' || $subs['RBENABLED'] == 'N') {
							$val = 'N';
						}
						$objSheet->getCell($cell)->setValue($val);
						break;	
					}
					case 'J': {
						$objSheet->getCell($cell)->setValue(str_replace('~', '-', $subs['RBACCOUNTPLAN']));
						break;	
					}
					case 'K': {
						$objSheet->getCell($cell)->setValue(is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS']);
						break;	
					}
					case 'L': {
						$objSheet->getCell($cell)->setValue(is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC']);
						break;	
					}
					case 'M': {
						$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE1']) ? '' : $subs['RBADDITIONALSERVICE1']);
						break;	
					}
					case 'N': {
						$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE2']) ? '' : $subs['RBADDITIONALSERVICE2']);
						break;	
					}
					case 'O': {
						$objSheet->getCell($cell)->setValue(is_null($subs['RBREMARKS']) ? '' : $subs['RBREMARKS']);
						break;
					}
				}
			}
		}
		for ($i = 0; $i < count($columns); $i++) {
			$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
		}
		$docroot = $_SERVER['DOCUMENT_ROOT'];
		$deletedTempDir = '';
		if (substr($docroot, strlen($docroot) - 1, 1) == '/') {
			$deletedTempDir = $docroot.'deletedusers/tmp/';
		} else {
			$deletedTempDir = $docroot.'/deletedusers/tmp/';
		}
		$filename = str_replace(' ', '', $title).'_'.date('dMY').'.xls';
		$writer->save($deletedTempDir.$filename);
		return strval($deletedTempDir.$filename);
	}
	public function writeDeletedSubscriberXlsForExtractV2($subscribers, $operation) {
		$title = '';
		if ($operation == 'delete') {
			$title = 'Bulk Delete - Deleted Users';
		} else if ($operation == 'update') {
			$title = 'Bulk Modify - Deleted Users';
		}
		$columns = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P'];
		$writeHeaders = ['USERNAME', 'PASSWORD', 'CUSTOMERTYPE', 'STATUS', 'ORDER NUMBER', 'CUSTOMER NAME', 'SERVICE NUMBER', 'REDIRECTED', 'SERVICE', 'IPV6 ADDRESS/CABINET NAME', 'IP ADDRESS/CABINET NAME', 'NET ADDRESS/CABINET NAME', 'ADDITIONAL SERVICE 1', 'ADDITIONAL SERVICE 2', 'REMARKS'];
		$headers = array(
			'B1' => 'USERNAME',
			'C1' => 'PASSWORD',
			'D1' => 'CUSTOMERTYPE',
			'E1' => 'STATUS',
			'F1' => 'ORDER NUMBER',
			'G1' => 'CUSTOMER NAME',
			'H1' => 'SERVICE NUMBER',
			'I1' => 'REDIRECTED',
			'J1' => 'SERVICE',
			'K1' => 'IPV6 ADDRESS/CABINET NAME',
			'L1' => 'IP ADDRESS/CABINET NAME',
			'M1' => 'NET ADDRESS/CABINET NAME',
			'N1' => 'ADDITIONAL SERVICE 1',
			'O1' => 'ADDITIONAL SERVICE 2',
			'P1' => 'REMARKS');
		$this->load->library('excel');
		$redFontColor = new PHPExcel_Style_Color();
		$redFontColor->setRGB('FF0000');
		
		$sheet = new PHPExcel();
		$sheet ->getProperties()->setTitle($title)->setDescription($title);
		$sheet->setActiveSheetIndex(0);	
		$sheet->getDefaultStyle()->getFont()->setName('Calibri');
		$sheet->getDefaultStyle()->getFont()->setSize(11);
		$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel5");
		$objSheet = $sheet->getActiveSheet();
		$objSheet->setTitle($title);
		$objSheet->getStyle(strval($columns[0].'1:'.$columns[count($columns) - 1].'1'))->getFont()->setBold(true)->setSize(11);
		$objSheet->getStyle('B1')->getFont()->setColor($redFontColor);
		// $objSheet->getStyle('D1')->getFont()->setColor($redFontColor);
		$objSheet->getStyle('E1')->getFont()->setColor($redFontColor);
		// $objSheet->getStyle('G1')->getFont()->setColor($redFontColor);
		$objSheet->getStyle('H1')->getFont()->setColor($redFontColor);
		// $objSheet->getStyle('I1')->getFont()->setColor($redFontColor);
		$objSheet->getStyle('J1')->getFont()->setColor($redFontColor);
		for ($i = 0; $i < count($columns); $i++) {
			$cell = strval($columns[$i].'1');
			$objSheet->getCell($cell)->setValue(strval($writeHeaders[$i]));
		}
		for ($i = 0; $i < count($subscribers); $i++) {
			if (!isset($subscribers[$i])) {
				continue;
			}
			$subs = $subscribers[$i];
			for ($j = 0; $j < count($headers); $j++) {
				$cell = strval($columns[$j].($i + 2));
				switch ($columns[$j]) {
					case 'B': {
						$username = explode('@', $subs['USERNAME']);
						$objSheet->getCell($cell)->setValue($username[0]);
						break;
					}
					case 'C': {
						$objSheet->getCell($cell)->setValue(is_null($subs['PASSWORD']) ? '' : $subs['PASSWORD']);
						break;
					}
					case 'D' : {
						$val = '';
						if ($subs['CUSTOMERTYPE'] == 'Residential' || $subs['CUSTOMERTYPE'] == 'R') {
							$val = 'R';
						} else if ($subs['CUSTOMERTYPE'] == 'Business' || $subs['CUSTOMERTYPE'] == 'B') {
							$val = 'B';
						}
						$objSheet->getCell($cell)->setValue($val);
						break;
					}
					case 'E': {
						$objSheet->getCell($cell)->setValue($subs['CUSTOMERSTATUS'] == 'Active' ? 'A' : 'D');
						break;
					}
					case 'F': {
						$objSheet->getCell($cell)->setValue(is_null($subs['RBORDERNUMBER']) ? '' : $subs['RBORDERNUMBER']);
						break;
					}
					case 'G': {
						$objSheet->getCell($cell)->setValue($subs['RBCUSTOMERNAME']);
						break;	
					}
					case 'H': {
						$objSheet->getCell($cell)->setValue($subs['RBSERVICENUMBER']);
						break;	
					}
					case 'I': {
						$val = '';
						if ($subs['RBENABLED'] == 'Yes' || $subs['RBENABLED'] == 'Y') {
							$val = 'Y';
						} else if ($subs['RBENABLED'] == 'No' || $subs['RBENABLED'] == 'N') {
							$val = 'N';
						}
						$objSheet->getCell($cell)->setValue($val);
						break;	
					}
					case 'J': {
						$objSheet->getCell($cell)->setValue(str_replace('~', '-', $subs['RBACCOUNTPLAN']));
						break;	
					}
					case 'K': {
						$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE4']) ? '' : $subs['RBADDITIONALSERVICE4']);
						break;
					}
					case 'L': {
						$objSheet->getCell($cell)->setValue(is_null($subs['RBIPADDRESS']) ? '' : $subs['RBIPADDRESS']);
						break;
					}
					case 'M': {
						$objSheet->getCell($cell)->setValue(is_null($subs['RBMULTISTATIC']) ? '' : $subs['RBMULTISTATIC']);
						break;	
					}
					case 'N': {
						$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE1']) ? '' : $subs['RBADDITIONALSERVICE1']);
						break;	
					}
					case 'O': {
						$objSheet->getCell($cell)->setValue(is_null($subs['RBADDITIONALSERVICE2']) ? '' : $subs['RBADDITIONALSERVICE2']);
						break;	
					}
					case 'P': {
						$objSheet->getCell($cell)->setValue(is_null($subs['RBREMARKS']) ? '' : $subs['RBREMARKS']);
						break;
					}
				}
			}
		}
		for ($i = 0; $i < count($columns); $i++) {
			$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
		}
		$docroot = $_SERVER['DOCUMENT_ROOT'];
		$deletedTempDir = '';
		if (substr($docroot, strlen($docroot) - 1, 1) == '/') {
			$deletedTempDir = $docroot.'deletedusers/tmp/';
		} else {
			$deletedTempDir = $docroot.'/deletedusers/tmp/';
		}
		$filename = str_replace(' ', '', $title).'_'.date('dMY').'.xls';
		$writer->save($deletedTempDir.$filename);
		return strval($deletedTempDir.$filename);
	}
	public function writeDNESubscribersForExtract($usernames) {
		$title = 'Bulk Delete - No Such Users';
		$columns = ['B'];
		$writeHeaders = ['USERNAME'];
		$headers = array(
			'B1' => 'USERNAME');
		$this->load->library('excel');
		$redFontColor = new PHPExcel_Style_Color();
		$redFontColor->setRGB('FF0000');
		$sheet = new PHPExcel();
		$sheet ->getProperties()->setTitle($title)->setDescription($title);
		$sheet->setActiveSheetIndex(0);	
		$sheet->getDefaultStyle()->getFont()->setName('Calibri');
		$sheet->getDefaultStyle()->getFont()->setSize(11);
		$writer = PHPExcel_IOFactory::createWriter($sheet, "Excel5");
		$objSheet = $sheet->getActiveSheet();
		$objSheet->setTitle($title);
		for ($i = 0; $i < count($columns); $i++) {
			$cell = strval($columns[$i].'1');
			$objSheet->getCell($cell)->setValue(strval($writeHeaders[$i]));
		}
		for ($i = 0; $i < count($usernames); $i++) {
			$username = $usernames[$i];
			for ($j = 0; $j < count($headers); $j++) {
				$cell = strval($columns[$j].($i + 2));
				switch ($columns[$j]) {
					case 'B': {
						$parts = explode('@', $username);
						$objSheet->getCell($cell)->setValue($parts[0]);
						break;
					}
				}
			}
		}
		for ($i = 0; $i < count($columns); $i++) {
			$objSheet->getColumnDimension($columns[$i])->setAutoSize(true);	
		}
		$docroot = $_SERVER['DOCUMENT_ROOT'];
		$deletedTempDir = '';
		if (substr($docroot, strlen($docroot) - 1, 1) == '/') {
			$deletedTempDir = $docroot.'deletedusers/tmp/';
		} else {
			$deletedTempDir = $docroot.'/deletedusers/tmp/';
		}
		$filename = str_replace(' ', '', $title).'_'.date('dMY').'.xls';
		$writer->save($deletedTempDir.$filename);
		return strval($deletedTempDir.$filename);
	}
	public function fetchFile($path) {
		if (file_exists($path)) {
			$parts = explode("/", $path);
			$filename = $parts[count($parts) - 1];
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');
			ob_clean();
		    flush();
			readfile($path);
		}
	}
	public function fetchLargeFile($path) {
		if (file_exists($path)) {
			$parts = explode("/", $path);
			$filename = $parts[count($parts) - 1];
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');
			ob_clean();
		    flush();
			readfile($path);
		}
	}

	public function writeToLoggedInHistoricalCount($counts) {
		log_message('info', json_encode($counts));
		$data = [];
		$dateFormatted = '';
		$data[] = array('DATE', 'DAY OF WEEK', 'HOUR', 'HOSTNAME', 'COUNT');
		for ($i = 0; $i < count($counts); $i++) {
			$row = $counts[$i];
			//H i s m d Y
			$date = mktime(0, 0, 0, $row['month'], $row['day'], $row['year']);
			$dayOfWeek = date('l', $date);
			$dateFormatted = date('Y-m-d', $date);
			$data[] = array($dateFormatted, $dayOfWeek, $row['hour'], $row['hostname'], $row['count']);
		}
		$filename = 'C-LoggedInCounts_'.$dateFormatted;
		header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
		header('Cache-Control: max-age=0');
		$out = fopen('php://output', 'w');
		for ($i = 0; $i < count($data); $i++) {
			fputcsv($out, $data[$i]);
		}
		fclose($out);
	}

	/****************************************************************************
	 * FORMAT (IP bulk create)
	 * B: IPADDRESS
	 * C: LOCATION
	 * D: GPONIP
	 * E: DESCRIPTION
	 ****************************************************************************/
	public function verifyBulkCreateIPFormat($uploadedFile) {
		$headers = array(
			'B1' => 'IP ADDRESS',
			'C1' => 'BNG LOCATION',
			'D1' => 'IS GPON',
			'E1' => 'DESCRIPTION');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$headerCount = count($headers);
		for ($i = 0; $i < $headerCount; $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;
	}
	public function verifyBulkCreateIPData($uploadedFile) {
		$valid = [];
		$invalid = [];
		$invalidRowNumbers = [];
		$validRowNumbers = [];
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$xls->setActiveSheetIndex(0);
		$rowCount = $xls->getSheet(0)->getHighestRow();
		log_message('info', 'ROW COUNT: '.$rowCount);
		if ($rowCount <= 1) {
			return false;
		} else {
			for ($i = 0; $i < $rowCount - 1; $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.($i + 2).':E'.($i + 2));
				log_message('info', $i.'|VERIFY: '.json_encode($row));
				if (trim($row[0][0]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				if (trim($row[0][1]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				if (trim($row[0][2]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				if (strlen(trim($row[0][3])) > 50) {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				$valid[] = $row[0];
				$validRowNumbers[] = ($i + 2);
			}
			return array('valid' => $valid, 'invalid' => $invalid, 'validRowNumbers' => $validRowNumbers, 'invalidRowNumbers' => $invalidRowNumbers);
		}
	}
	public function extractRowsToCreateIP($path, $rows) {
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($path);
		$xls->setActiveSheetIndex(0);
		$data = [];
		$rowCount = count($rows);
		for ($i = 0; $i < $rowCount; $i++) {
			$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':E'.$rows[$i]);
			$data[] = $row[0];
		}
		return $data;
	}

	/****************************************************************************
	 * FORMAT (IPv6 bulk create)
	 * B: IPv6 ADDRESS
	 * C: LOCATION
	 * D: DESCRIPTION
	 ****************************************************************************/
	public function verifyBulkCreateIPv6Format($uploadedFile) {
		$headers = array(
			'B1' => 'IPv6 ADDRESS',
			'C1' => 'SUBNET',
			'D1' => 'BNG LOCATION',
			'E1' => 'DESCRIPTION');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$headerCount = count($headers);
		for ($i = 0; $i < $headerCount; $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;
	}
	public function verifyBulkCreateIPv6Data($uploadedFile) {
		$valid = [];
		$invalid = [];
		$invalidRowNumbers = [];
		$validRowNumbers = [];
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$xls->setActiveSheetIndex(0);
		$rowCount = $xls->getSheet(0)->getHighestRow();
		log_message('info', 'ROW COUNT: '.$rowCount);
		if ($rowCount <= 1) {
			return false;
		} else {
			for ($i = 0; $i < $rowCount - 1; $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.($i + 2).':E'.($i + 2));
				log_message('info', $i.'|VERIFY: '.json_encode($row));
				if (trim($row[0][0]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				if (trim($row[0][1]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				if (trim($row[0][2]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				if (strlen(trim($row[0][3])) > 50) {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				$valid[] = $row[0];
				$validRowNumbers[] = ($i + 2);
			}
			return array('valid' => $valid, 'invalid' => $invalid, 'validRowNumbers' => $validRowNumbers, 'invalidRowNumbers' => $invalidRowNumbers);
		}
	}
	public function extractRowsToCreateIpv6($path, $rows) {
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($path);
		$xls->setActiveSheetIndex(0);
		$data = [];
		$rowCount = count($rows);
		for ($i = 0; $i < $rowCount; $i++) {
			$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':E'.$rows[$i]);
			$data[] = $row[0];
		}
		return $data;
	}

	/****************************************************************************
	 * FORMAT (Cabinet bulk create/modify/delete)
	 * B: CABINET NAME
	 * C: NEW CABINET NAME (optional; used only in bulk edit)
	 * D: HOMING BNG (location)
	 * E: DATA VLAN
	 ****************************************************************************/
	public function verifyBulkCreateCabinetFormat($uploadedFile) {
		$headers = array(
			'B1' => 'CABINET NAME',
			'C1' => 'NEW CABINET NAME',
			'D1' => 'HOMING BNG',
			'E1' => 'DATA VLAN');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$headerCount = count($headers);
		for ($i = 0; $i < $headerCount; $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;
	}
	public function verifyBulkCreateCabinetData($uploadedFile) {
		$valid = [];
		$invalid = [];
		$invalidRowNumbers = [];
		$validRowNumbers = [];
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$xls->setActiveSheetIndex(0);
		$rowCount = $xls->getSheet(0)->getHighestRow();
		log_message('info', 'ROW COUNT: '.$rowCount);
		if ($rowCount <= 1) {
			return false;
		} else {
			for ($i = 0; $i < $rowCount - 1; $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.($i + 2).':E'.($i + 2));
				log_message('info', $i.'|VERIFY: '.json_encode($row));
				if (trim($row[0][0]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				if (trim($row[0][2]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				if (trim($row[0][3]) == '') {
					// $invalid[] = $row[0];
					// $invalidRowNumbers[] = ($i + 2);
					// continue;
					$row[0][3] = 0;
				}
				$valid[] = $row[0];
				$validRowNumbers[] = ($i + 2);
			}
			return array('valid' => $valid, 'invalid' => $invalid, 'validRowNumbers' => $validRowNumbers, 'invalidRowNumbers' => $invalidRowNumbers);
		}
	}
	public function extractRowsToCreateCabinet($path, $rows) {
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($path);
		$xls->setActiveSheetIndex(0);
		$data = [];
		$rowCount = count($rows);
		for ($i = 0; $i < $rowCount; $i++) {
			$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':E'.$rows[$i]);
			$data[] = $row[0];
		}
		return $data;
	}
	public function verifyBulkDeleteCabinetFormat($uploadedFile) {
		$headers = array(
			'B1' => 'CABINET NAME');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$headerCount = count($headers);
		for ($i = 0; $i < $headerCount; $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;
	}
	public function verifyBulkDeleteCabinetData($uploadedFile) {
		$valid = [];
		$invalid = [];
		$invalidRowNumbers = [];
		$validRowNumbers = [];
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$xls->setActiveSheetIndex(0);
		$rowCount = $xls->getSheet(0)->getHighestRow();
		log_message('info', 'ROW COUNT: '.$rowCount);
		if ($rowCount <= 1) {
			return false;
		} else {
			for ($i = 0; $i < $rowCount - 1; $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.($i + 2).':E'.($i + 2));
				log_message('info', $i.'|VERIFY: '.json_encode($row));
				if (trim($row[0][0]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				$valid[] = $row[0];
				$validRowNumbers[] = ($i + 2);
			}
			return array('valid' => $valid, 'invalid' => $invalid, 'validRowNumbers' => $validRowNumbers, 'invalidRowNumbers' => $invalidRowNumbers);
		}
	}
	public function verifyBulkModifyCabinetFormat($uploadedFile) {
		$headers = array(
			'B1' => 'CABINET NAME',
			'C1' => 'NEW CABINET NAME',
			'D1' => 'HOMING BNG',
			'E1' => 'DATA VLAN');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$headerCount = count($headers);
		for ($i = 0; $i < $headerCount; $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;
	}
	public function verifyBulkModifyCabinetData($uploadedFile) {
		$valid = [];
		$invalid = [];
		$invalidRowNumbers = [];
		$validRowNumbers = [];
		$noChange = [];
		$noChangeRowNumbers = [];
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$xls->setActiveSheetIndex(0);
		$rowCount = $xls->getSheet(0)->getHighestRow();
		log_message('info', 'ROW COUNT: '.$rowCount);
		if ($rowCount <= 1) {
			return false;
		} else {
			for ($i = 0; $i < $rowCount - 1; $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.($i + 2).':E'.($i + 2));
				log_message('info', $i.'|VERIFY: '.json_encode($row));
				if (trim($row[0][0]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				if (trim($row[0][1]) == '' && trim($row[0][2]) == '' && trim($row[0][3]) == '') {
					$noChange[] = $row[0];
					$noChangeRowNumbers[] = ($i + 2);
					continue;
				}
				$valid[] = $row[0];
				$validRowNumbers[] = ($i + 2);
			}
			return array(
				'valid' =>  $valid,
				'validRowNumbers' => $validRowNumbers,
				'invalid' => $invalid,
				'invalidRowNumbers' => $invalidRowNumbers,
				'noChange' => $noChange,
				'noChangeRowNumbers' => $noChangeRowNumbers);
		}
	}
	public function extractRowsToUpdateCabinet($path, $rows) {
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($path);
		$xls->setActiveSheetIndex(0);
		$data = [];
		for ($i = 0; $i < count($rows); $i++) {
			$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':E'.$rows[$i]);
			$data[] = $row[0];
		}
		return $data;
	}

	/****************************************************************************
	 * FORMAT (bulk IP seeding / unassign)
	 * B: IPADDRESS
	 * C: LOCATION
	 * D: DESCRIPTION
	 ****************************************************************************/
	public function verifyBulkIPSeedingFormat($uploadedFile) {
		$headers = array(
			'B1' => 'IP ADDRESS',
			'C1' => 'BNG LOCATION', 
			'D1' => 'DESCRIPTION');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		for ($i = 0; $i < count($headers); $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;
	}
	public function verifyBulkIPSeedingData($uploadedFile) {
		$valid = array();
		$invalid = array();
		$invalidRowNumbers = array();
		$validRowNumbers = array();
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$xls->setActiveSheetIndex(0);
		$rowCount = $xls->getSheet(0)->getHighestRow();
		log_message('info', 'ROW COUNT: '.$rowCount);
		if ($rowCount <= 1) {
			return false;
		} else {
			for ($i = 0; $i < $rowCount - 1; $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.($i + 2).':D'.($i + 2));
				log_message('info', $i.'|VERIFY: '.json_encode($row));
				if (trim($row[0][0]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				if (trim($row[0][1]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				$valid[] = $row[0];
				$validRowNumbers[] = ($i + 2);
			}
			return array('valid' => $valid, 'invalid' => $invalid, 'validRowNumbers' => $validRowNumbers, 'invalidRowNumbers' => $invalidRowNumbers);
		}
	}
	public function extractRowsToReserve($path, $rows) {
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($path);
		$xls->setActiveSheetIndex(0);
		$data = array();
		for ($i = 0; $i < count($rows); $i++) {
			$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':D'.$rows[$i]);
			$data[] = $row[0];
		}
		return $data;
	}
	public function verifyBulkIPUnassignFormat($uploadedFile) {
		$headers = array(
			'B1' => 'IP ADDRESS',
			'C1' => 'BNG LOCATION', 
			'D1' => 'DESCRIPTION');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		for ($i = 0; $i < count($headers); $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;
	}
	public function verifyBulkIPUnassignData($uploadedFile) {
		$valid = array();
		$invalid = array();
		$invalidRowNumbers = array();
		$validRowNumbers = array();
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$xls->setActiveSheetIndex(0);
		$rowCount = $xls->getSheet(0)->getHighestRow();
		log_message('info', 'ROW COUNT: '.$rowCount);
		if ($rowCount <= 1) {
			return false;
		} else {
			for ($i = 0; $i < $rowCount - 1; $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.($i + 2).':D'.($i + 2));
				log_message('info', $i.'|VERIFY: '.json_encode($row));
				if (trim($row[0][0]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				$valid[] = $row[0];
				$validRowNumbers[] = ($i + 2);
			}
			return array('valid' => $valid, 'invalid' => $invalid, 'validRowNumbers' => $validRowNumbers, 'invalidRowNumbers' => $invalidRowNumbers);
		}
	}

	/****************************************************************************
	 * FORMAT (Net bulk create)
	 * B: IPADDRESS
	 * C: SUBNET
	 * D: LOCATION
	 * E: DESCRIPTION
	 ****************************************************************************/
	public function verifyBulkCreateNetFormat($uploadedFile) {
		$headers = array(
			'B1' => 'MULTI-STATIC IP ADDRESS',
			'C1' => 'SUBNET',
			'D1' => 'BNG LOCATION',
			'E1' => 'DESCRIPTION');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		for ($i = 0; $i < count($headers); $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;	
	}
	public function verifyBulkCreateNetData($uploadedFile) {
		$valid = [];
		$invalid = [];
		$invalidRowNumbers = [];
		$validRowNumbers = [];
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$xls->setActiveSheetIndex(0);
		$rowCount = $xls->getSheet(0)->getHighestRow();
		log_message('info', 'ROW COUNT: '.$rowCount);
		if ($rowCount <= 1) {
			return false;
		} else {
			for ($i = 0; $i < $rowCount - 1; $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.($i + 2).':E'.($i + 2));
				log_message('info', $i.'|VERIFY: '.json_encode($row));
				if (trim($row[0][0]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				if (trim($row[0][1]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				if (trim($row[0][2]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				if (strlen(trim($row[0][3])) > 50) {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				$valid[] = $row[0];
				$validRowNumbers[] = ($i + 2);
			}
			return array('valid' => $valid, 'invalid' => $invalid, 'validRowNumbers' => $validRowNumbers, 'invalidRowNumbers' => $invalidRowNumbers);
		}
	}
	public function extractRowsToCreateNet($path, $rows) {
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($path);
		$xls->setActiveSheetIndex(0);
		$data = [];
		for ($i = 0; $i < count($rows); $i++) {
			$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':E'.$rows[$i]);
			$data[] = $row[0];
		}
		return $data;
	}

	/****************************************************************************
	 * FORMAT (Sysuser bulk create/modify/delete) only USERNAME required for delete
	 * B: USERNAME
	 * C: ROLE
	 * D: FIRSTNAME
	 * E: LASTNAME
	 * F: EMAIL (optional)
	 ****************************************************************************/
	public function verifyBulkSysuserFormat($uploadedFile) {
		$headers = array(
			'B1' => 'USERNAME',
			'C1' => 'ROLE',
			'D1' => 'FIRSTNAME',
			'E1' => 'LASTNAME',
			'F1' => 'EMAIL');
		$keys = array_keys($headers);
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		for ($i = 0; $i < count($headers); $i++) {
			$header = $xls->getSheet(0)->getCell($keys[$i])->getValue();
			if (strtolower($header) != strtolower($headers[$keys[$i]])) {
				return false;
			}
		}
		return true;	
	}
	public function verifyBulkSysuserData($uploadedFile, $action) {
		$valid = [];
		$invalid = [];
		$invalidRowNumbers = [];
		$validRowNumbers = [];
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($uploadedFile);
		$xls->setActiveSheetIndex(0);
		$rowCount = $xls->getSheet(0)->getHighestRow();
		log_message('info', 'ROW COUNT: '.$rowCount);
		if ($rowCount <= 1) {
			return false;
		} else {
			for ($i = 0; $i < $rowCount - 1; $i++) {
				$row = $xls->getActiveSheet()->rangeToArray('B'.($i + 2).':F'.($i + 2));
				log_message('info', $i.'|VERIFY: '.json_encode($row));
				if (trim($row[0][0]) == '') {
					$invalid[] = $row[0];
					$invalidRowNumbers[] = ($i + 2);
					continue;
				}
				if ($action == 'create' || $action == 'update') {
					if (trim($row[0][1]) == '') {
						$invalid[] = $row[0];
						$invalidRowNumbers[] = ($i + 2);
						continue;
					}
					if (trim($row[0][2]) == '') {
						$invalid[] = $row[0];
						$invalidRowNumbers[] = ($i + 2);
						continue;
					}
					if (trim($row[0][2]) == '') {
						$invalid[] = $row[0];
						$invalidRowNumbers[] = ($i + 2);
						continue;
					}
				}
				$valid[] = $row[0];
				$validRowNumbers[] = ($i + 2);
			}
			return array('valid' => $valid, 'invalid' => $invalid, 'validRowNumbers' => $validRowNumbers, 'invalidRowNumbers' => $invalidRowNumbers);
		}
	}
	public function extractRowsToSysuser($path, $rows) {
		$this->load->library('excel');
		$reader = PHPExcel_IOFactory::createReader('Excel5');
		$reader->setReadDataOnly(true);
		$xls = $reader->load($path);
		$xls->setActiveSheetIndex(0);
		$data = [];
		for ($i = 0; $i < count($rows); $i++) {
			$row = $xls->getActiveSheet()->rangeToArray('B'.$rows[$i].':F'.$rows[$i]);
			$data[] = $row[0];
		}
		return $data;
	}
}

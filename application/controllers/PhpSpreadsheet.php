<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(FCPATH . 'vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
//use PhpOffice\PhpSpreadsheet\Writer\html;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class PhpSpreadsheet extends CI_Controller {

   public function __construct()
   {
      parent::__construct();

      $this->load->library(array('ion_auth', 'form_validation', 'template'));
      $this->load->helper('bootstrap_helper');
   }

	public function index(){

		$data = array();
		$this->template->backend('PhpSpreadsheet_v', $data);
	}

	public function example1()
	{
		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();

		// Set document properties
		$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		    ->setLastModifiedBy('Maarten Balliauw')
		    ->setTitle('Office 2007 XLSX Test Document')
		    ->setSubject('Office 2007 XLSX Test Document')
		    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		    ->setKeywords('office 2007 openxml php')
		    ->setCategory('Test result file');
		
		// Add some data
		$spreadsheet->setActiveSheetIndex(0)
		    ->setCellValue('A1', 'Hello')
		    ->setCellValue('B2', 'world!')
		    ->setCellValue('C1', 'Hello')
		    ->setCellValue('D2', 'world!');

		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('Simple');

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$spreadsheet->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Xlsx)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="01simple.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

	public function example2(){
		$filename = 'example2';

		$spreadsheet = new Spreadsheet();  /*----Spreadsheet object-----*/
		$Excel_writer = new Xls($spreadsheet);  /*----- Excel (Xls) Object*/

		$spreadsheet->setActiveSheetIndex(0);
		$activeSheet = $spreadsheet->getActiveSheet();

		$activeSheet->setCellValue('A1' , 'New file content')->getStyle('A1')->getFont()->setBold(true);

		// Rename worksheet
		$activeSheet->setTitle('Simple');
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); /*-- $filename is  xsl filename ---*/
		header('Cache-Control: max-age=0');
		$Excel_writer->save('php://output');

	}

	public function create_sheet(){

		$filename = 'example2';

		$spreadsheet = new Spreadsheet();  /*----Spreadsheet object-----*/
		$Excel_writer = new Xls($spreadsheet);  /*----- Excel (Xls) Object*/

		//$spreadsheet->setActiveSheetIndexByName('DataSheet')
		$spreadsheet->setActiveSheetIndex(0)
					->setCellValue('A1' , 'New file content')
					->getStyle('A1')
					->getFont()
					->setBold(true);
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('Simple');

		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(1)
					->setCellValue('A1' , 'New file content 2')
					->getStyle('A1')
					->getFont()
					->setBold(true);
		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('Simple2');


		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); /*-- $filename is  xsl filename ---*/
		header('Cache-Control: max-age=0');
		$Excel_writer->save('php://output');
	}

	public function export_employees(){
		$filename = 'employees';

		$this->load->model('Employees_model', 'employees');
		
		$fields = $this->db->field_data('employees');

		$query  = $this->employees->get_all();

		$spreadsheet = new Spreadsheet(); 
		$Excel_writer = new Xls($spreadsheet);

		$column = 1;
        $field_name = array();
        foreach ($fields as $field) {
            $field_name[] = $field->name;

			$spreadsheet->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, 1, $field->name)
					->getStyleByColumnAndRow($column, 1)
					->getFont()
					->setBold(true);
			$column++;
        }

        $row = 2;
        foreach ($query as $record) {
        	$column = 1;        	
        	foreach ($field_name as $value) {
        		$spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($column, $row, $record->{$value});
        		$column++;
        	}
			$row++;
        }

		$spreadsheet->getActiveSheet()->setTitle('Employees');

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); /*-- $filename is  xsl filename ---*/
		header('Cache-Control: max-age=0');
		$Excel_writer->save('php://output');
	}

	public function export_products(){

		$filename = urlencode("Data".date("Y-m-d H:i:s").".xls");

		$this->load->model('Products_model', 'products');
		
		$fields = $this->db->field_data('products');

		$query = $this->products
		         ->with_categories('fields:CategoryName')
		         ->with_suppliers('fields:CompanyName')
		         ->get_all();

		$spreadsheet = new Spreadsheet(); 
		$Excel_writer = new Xls($spreadsheet);

		$column = 1;
        $field_name = array();
        foreach ($fields as $field) {
            $field_name[] = $field->name;

			$spreadsheet->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, 1, $field->name)
					->getStyleByColumnAndRow($column, 1)
					->getFont()
					->setBold(true);
			$column++;
        }

        $row = 2;
        foreach ($query as $record) {
        	$column = 1;        	
        	foreach ($field_name as $value) {

        		switch ($value) {
        			case 'SupplierID':
        				$CompanyName = !empty($record->suppliers->CompanyName) ? $record->suppliers->CompanyName : '';
        				$spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($column, $row, $CompanyName);
        				break;
        		    case 'CategoryID':
        		    	$CategoryName = !empty($record->categories->CategoryName) ? $record->categories->CategoryName : ''; 
        				$spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($column, $row, $CategoryName);
        				break;	
        			default:
        				$spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($column, $row, $record->{$value});
        				break;
        		}
        		
        		$column++;
        	}
			$row++;
        }

		$spreadsheet->getActiveSheet()->setTitle('Products');

		$spreadsheet->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Xlsx)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$Excel_writer->save('php://output');
	}

	public function export_data(){

		$filename = urlencode("Data".date("Y-m-d H:i:s").".xls");

		$this->load->model(array('Employees_model'=>'employees', 'Products_model'=>'products'));
		
		$fields = $this->db->field_data('products');

		$query = $this->products
		         ->with_categories('fields:CategoryName')
		         ->with_suppliers('fields:CompanyName')
		         ->get_all();

		$spreadsheet = new Spreadsheet(); 
		$Excel_writer = new Xls($spreadsheet);

		$column = 1;
        $field_name = array();
        foreach ($fields as $field) {
            $field_name[] = $field->name;

			$spreadsheet->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow($column, 1, $field->name)
					->getStyleByColumnAndRow($column, 1)
					->getFont()
					->setBold(true);
			$column++;
        }

        $row = 2;
        foreach ($query as $record) {
        	$column = 1;        	
        	foreach ($field_name as $value) {

        		switch ($value) {
        			case 'SupplierID':
        				$CompanyName = !empty($record->suppliers->CompanyName) ? $record->suppliers->CompanyName : '';
        				$spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($column, $row, $CompanyName);
        				break;
        		    case 'CategoryID':
        		    	$CategoryName = !empty($record->categories->CategoryName) ? $record->categories->CategoryName : ''; 
        				$spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($column, $row, $CategoryName);
        				break;	
        			default:
        				$spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($column, $row, $record->{$value});
        				break;
        		}
        		
        		$column++;
        	}
			$row++;
        }

		$spreadsheet->getActiveSheet()->setTitle('Products');

		//Create New Sheet
		$spreadsheet->createSheet();

		$fields = $this->db->field_data('employees');

		$query  = $this->employees->get_all();

		$column = 1;
        $field_name = array();
        foreach ($fields as $field) {
            $field_name[] = $field->name;

			$spreadsheet->setActiveSheetIndex(1)
					->setCellValueByColumnAndRow($column, 1, $field->name)
					->getStyleByColumnAndRow($column, 1)
					->getFont()
					->setBold(true);
			$column++;
        }

        $row = 2;
        foreach ($query as $record) {
        	$column = 1;        	
        	foreach ($field_name as $value) {
        		$spreadsheet->setActiveSheetIndex(1)->setCellValueByColumnAndRow($column, $row, $record->{$value});
        		$column++;
        	}
			$row++;
        }

		$spreadsheet->getActiveSheet()->setTitle('Employees');


		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); /*-- $filename is  xsl filename ---*/
		header('Cache-Control: max-age=0');
		$Excel_writer->save('php://output');
	}

	public function export_dosen()
	{
		$filename = urlencode("Data".date("Y-m-d H:i:s").".xls");
		
		$fields = $this->db->field_data('dosen');

		$this->load->model('Dosen_model','dosen');

		$getdata = $this->dosen->get_all();
		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();

		// Set document properties
		$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		    ->setLastModifiedBy('Maarten Balliauw')
		    ->setTitle('Office 2007 XLSX Test Document')
		    ->setSubject('Office 2007 XLSX Test Document')
		    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		    ->setKeywords('office 2007 openxml php')
		    ->setCategory('Test result file');
		
		// Add some data

		$spreadsheet->setActiveSheetIndex(0)
		    ->setCellValue('A1','NPP')
			->setCellValue('B1', 'Nama');

		$x = 2;

		foreach($getdata as $get)
		{
			$spreadsheet->setActiveSheetIndex(0)
		    ->setCellValue('A'.$x, $get->npp)
			->setCellValue('B'.$x, $get->nama);
			$x++;
		}

		

		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('Simple');

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$spreadsheet->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Xlsx)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
		exit;
	}

}

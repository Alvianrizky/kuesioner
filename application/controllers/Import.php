<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(FCPATH . 'vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
//use PhpOffice\PhpSpreadsheet\Writer\html;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Import extends CI_Controller
{
    protected $page_header = 'Import Management';

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Import_model'=> 'imp', 'Dosen_model' => 'dosen'));
        $this->load->library(array('ion_auth', 'form_validation', 'template'));
        $this->load->helper('bootstrap_helper');
    }

    public function import()
    {
        if (!$this->ion_auth->logged_in()){            
            redirect('auth/login', 'refresh');
        }

        $data['page_header']   = $this->page_header;
        $data['panel_heading'] = 'Mahasiswa Kelas List';
        $data['page']         = '';

       
        $this->template->backend('import_v', $data);
    }

    public function upload()
    {
        $data = array();
        $data['title'] = 'Import Excel Sheet | TechArise';
        $data['breadcrumbs'] = array('Home' => '#');
        date_default_timezone_set('asia/jakarta');
        $code = 0;

        $this->form_validation->set_rules('fileURL','Upload File','callback_checkFileValidation');

        if($this->form_validation->run() == false)
        {
            $code = 1;
            $notifications = validation_errors('<p>', '</p>'); 
        }
        else
        {
            if(!empty($_FILES['fileURL']['name']))
            {
                $extension = pathinfo($_FILES['fileURL']['name'],PATHINFO_EXTENSION);
                if($extension == 'csv')
                {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                }
                elseif($extension == 'xlsx')
                {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }
                else
                {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                }

                
                $spreadsheet = $reader->load($_FILES['fileURL']['tmp_name']);
                $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null,true, true, true);

                $flag = true;
                $i = 0;       
                $updatedata = array();
                //$primarydata = array();
                foreach($allDataInSheet as $value)
                {
                    if($flag)
                    {
                        $flag = false;
                         continue;
                    }
                    
                                        
                    //$primarydata[$i]= $value['A'];
                    $query = $this->dosen->where('npp', $value['A'])->get();
                    if(!empty($query)){
                        $updatedata[$i]['npp'] = $value['A'];
                        $updatedata[$i]['nama'] = $value['B'];
                        $updatedata[$i]['created_at'] = date('Y-m-d H:i:s');
                    }
                    else{
                        $insertdata[$i]['npp'] = $value['A'];
                        $insertdata[$i]['nama'] = $value['B'];
                        $insertdata[$i]['created_at'] = date('Y-m-d H:i:s');
                    }


                    //$data = array('hidden'=> form_hidden('aksi', !empty($row->mhsKelasID) ? 'update' : 'create'));

                    $i++;
                }

                if(!empty($insertdata)){
                    $this->imp->setBatchImport($insertdata);
                    $this->imp->importData();
                }
                
                if(!empty($updatedata)){
                    $this->imp->setBatchUpdate($updatedata);
                    $this->imp->updateData('npp');
                }               
            }
            $error =  $this->db->error();
            if($error['code'] <> 0){               
               $code = 1;               
               $notifications = $error['code'].' : '.$error['message'];
            }
            else{               
               $notifications = 'Success Import Data';
            }

        }
        $notifications = ($code == 0) ? notifications('success', $notifications) : notifications('error', $notifications);
      
        echo json_encode(array('message' => $notifications, 'code' => $code));
        //redirect('Import/import', 'refresh');
    }

    public function checkFileValidation($string)
    {
        $file_mimes = array('text/x-comma-separated-values',
                            'text/comma-separated-values',
                            'application/octet-stream',
                            'application/vnd.ms-excel',
                            'application/x-csv',
                            'text/x-csv',
                            'text/csv',
                            'application/csv',
                            'application/excel',
                            'application/vnd.msexcel',
                            'text/plain',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'        
                            );
        if(isset($_FILES['fileURL']['name']))
        {
            $arr_file = explode('.',$_FILES['fileURL']['name']);
            $extension = end($arr_file);
            if($extension == 'xlsx' || $extension == 'xls' || $extension == 'csv' && in_array($_FILES['fileURL']['type'], $file_mimes))
            {
                return true;
            }
            else
            {
                $this->form_validation->set_message('checkFileValidation', 'Please choose correct file');
                return false;
            }
        }
        else
        {
            $this->form_validation->set_message('checkFileValidation', 'Please choose a file');
                return false;
        }
        
    }
}
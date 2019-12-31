<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require(FCPATH . 'vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Mahasiswa extends CI_Controller {

   protected $page_header = 'Mahasiswa Management';

   public function __construct()
   {
      parent::__construct();


      $this->load->model(array('Import_model'=> 'imp', 'Mahasiswa_model' => 'mahasiswa'));
      $this->load->library(array('ion_auth', 'form_validation', 'template'));
      $this->load->helper('bootstrap_helper','download');
   }

	public function index()
	{  
      
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $data['page_header']   = $this->page_header;
      $data['panel_heading'] = 'Mahasiswa List';
      $data['page']         = '';

      $this->template->backend('mahasiswa_v', $data);
   }
   
   public function download($fileName = NULL)
   {
      if($fileName)
      {
         $file = realpath("assets/download")."\\".$fileName;
         if(file_exists($file))
         {
            $data = file_get_contents($file);
            force_download($fileName,$data);
         }
      }
   }

   public function get_mahasiswa()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $list = $this->mahasiswa->get_datatables();
      $data = array();
      $no = isset($_POST['start']) ? $_POST['start'] : 0;
      foreach ($list as $field) { 
         $id = $field->nim;

         $url_view   = 'view_data('.$id.');';
         $url_update = 'update_data('.$id.');';
         $url_delete = 'delete_data('.$id.');';

         $no++;
         $row = array();
         $row[] = ajax_button($url_view, $url_update, $url_delete);
         $row[] = $no;
         $row[] = $field->nim;
         $row[] = $field->nama;
         $row[] = $field->angkatan;

         $data[] = $row;
      }
      
      $draw = isset($_POST['draw']) ? $_POST['draw'] : null;

      $output = array(
         "draw" => $draw,
         "recordsTotal" => $this->mahasiswa->count_rows(),
         "recordsFiltered" => $this->mahasiswa->count_filtered(),
         "data" => $data,
      );
      echo json_encode($output);
   }


   public function view()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $id = $this->input->post('nim');

      $query = $this->mahasiswa->where('nim', $id)->get();
      if($query){
         $data = array('nim' => $query->nim,
            'nama' => $query->nama,
            'angkatan' => $query->angkatan);
      }

      echo json_encode($data);
   }

   public function form_data()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $row = array();
      if($this->input->post('nim')){
         $id      = $this->input->post('nim');
         $query   = $this->mahasiswa->where('nim', $id)->get(); 
         if($query){
            $row = array(
               'nim'    => $query->nim,
               'nama'   => $query->nama,
               'angkatan' => $query->angkatan
            );
         }
         $row = (object) $row;
      }

      $data = array('hidden'=> form_hidden('aksi', !empty($row->nim) ? 'update' : 'create'),
             'nim' => form_input(array('name'=>'nim', 'id'=>'nim', 'class'=>'form-control', 'value'=>!empty($row->nim) ? $row->nim : '')),
             'nama' => form_input(array('name'=>'nama', 'id'=>'nama', 'class'=>'form-control', 'value'=>!empty($row->nama) ? $row->nama : '')),
             'angkatan' => form_input(array('name'=>'angkatan', 'id'=>'angkatan', 'class'=>'form-control', 'value'=>!empty($row->angkatan) ? $row->angkatan : ''))
            );

      echo json_encode($data);
   }


   public function save_mahasiswa()
   {   
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }
      
      $rules = array(
            'insert' => array(                     
                     array('field' => 'nim', 'label' => 'NIM', 'rules' => 'trim|required|is_unique[mahasiswa.nim]|max_length[8]'),
                     array('field' => 'nama', 'label' => 'Nama', 'rules' => 'trim|required|max_length[150]'),
                     array('field' => 'angkatan', 'label' => 'Angkatan', 'rules' => 'trim|required|max_length[150]')          
                     ),
            'update' => array(
                     array('field' => 'nim', 'label' => 'NIM', 'rules' => 'trim|required|max_length[8]'),
                     array('field' => 'nama', 'label' => 'Nama', 'rules' => 'trim|required|max_length[150]'),
                     array('field' => 'angkatan', 'label' => 'Angkatan', 'rules' => 'trim|required|max_length[150]') 
                     )                   
            );
        
      $row = array('nim' => $this->input->post('nim'),
            'nama' => $this->input->post('nama'),
            'angkatan' => $this->input->post('angkatan'));
      
      
      $code = 0;

      if($this->input->post('aksi') == 'create'){

         $this->form_validation->set_rules($rules['insert']);

         if ($this->form_validation->run() == true) {
            
            $this->mahasiswa->insert($row);

            $error =  $this->db->error();
            if($error['code'] <> 0){
               $code = 1;
               $notifications = $error['code'].' : '.$error['message'];
            }
            else{
               $notifications = 'Success Insert Data';
            }
         }
         else{
            $code = 1;
            $notifications = validation_errors('<p>', '</p>'); 
         }

      }

      else{
         
         $this->form_validation->set_rules($rules['update']);

         if ($this->form_validation->run() == true) {

            $id = $this->input->post('nim');

            $this->mahasiswa->where('nim', $id)->update($row);
            
            $error =  $this->db->error();
            if($error['code'] <> 0){               
               $code = 1;               
               $notifications = $error['code'].' : '.$error['message'];
            }
            else{               
               $notifications = 'Success Update Data';
            }
         }
         else{
            $code = 1;
            $notifications = validation_errors('<p>', '</p>'); 
         }
      }

      $notifications = ($code == 0) ? notifications('success', $notifications) : notifications('error', $notifications);
      
      echo json_encode(array('message' => $notifications, 'code' => $code));
   }

   public function delete()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $code = 0;

      $id = $this->input->post('nim');

      $this->mahasiswa->where('nim', $id)->delete();

      $error =  $this->db->error();
      if($error['code'] <> 0){
         $code = 1;
         $notifications = $error['code'].' : '.$error['message'];
      }
      else{
         $notifications = 'Success Delete Data';
      }

      $notifications = ($code == 0) ? notifications('success', $notifications) : notifications('error', $notifications);
      
      echo json_encode(array('message' => $notifications, 'code' => $code));
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
                    $query = $this->mahasiswa->where('nim', $value['A'])->get();
                    if(!empty($query)){
                        $updatedata[$i]['nim'] = $value['A'];
                        $updatedata[$i]['nama'] = $value['B'];
                        $updatedata[$i]['angkatan'] = $value['C'];
                        $updatedata[$i]['created_at'] = date('Y-m-d H:i:s');
                    }
                    else{
                        $insertdata[$i]['nim'] = $value['A'];
                        $insertdata[$i]['nama'] = $value['B'];
                        $insertdata[$i]['angkatan'] = $value['C'];
                        $insertdata[$i]['created_at'] = date('Y-m-d H:i:s');
                    }


                    //$data = array('hidden'=> form_hidden('aksi', !empty($row->mhsKelasID) ? 'update' : 'create'));

                    $i++;
                }

                if(!empty($insertdata)){
                    $this->imp->setBatchImport($insertdata);
                    $this->imp->importData('mahasiswa');
                }
                
                if(!empty($updatedata)){
                    $this->imp->setBatchUpdate($updatedata);
                    $this->imp->updateData('mahasiswa','nim');
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

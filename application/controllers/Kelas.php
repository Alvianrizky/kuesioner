<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Kelas extends CI_Controller {

   private $page_header = 'Kelas Management';

   public function __construct()
   {
      parent::__construct();


      $this->load->model(array('kelas_model'=>'kelas','Import_model'=> 'imp', 'MhsKelas_model'=>'mhskelas', 'DsnKelas_Model'=>'dsnkelas'));
      $this->load->library(array('ion_auth', 'form_validation', 'template', 'table'));
      $this->load->helper('bootstrap_helper');
   }

	public function index()
	{  
      
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }
     
      $data['page_header']   = $this->page_header;
      $data['panel_heading'] = 'Kelas List';
      $data['page']         = '';

      $this->template->backend('kelas_v', $data);
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

   public function get_kelas()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $list = $this->kelas->get_datatables();
      $data = array();
      $no = isset($_POST['start']) ? $_POST['start'] : 0;
      foreach ($list as $field) { 
         $id = $field->kelasID;

         $url_view   = 'view_data('.$id.');';
         $url_update = 'update_data('.$id.');';
         $url_delete = 'delete_data('.$id.');';

         $no++;
         $row = array();
         $row[] = ajax_button($url_view, $url_update, $url_delete);
         $row[] = $no;
         $row[] = $field->nama_kelas;

         $data[] = $row;
      }
      
      $draw = isset($_POST['draw']) ? $_POST['draw'] : null;

      $output = array(
         "draw" => $draw,
         "recordsTotal" => $this->kelas->count_rows(),
         "recordsFiltered" => $this->kelas->count_filtered(),
         "data" => $data,
      );
      echo json_encode($output);
   }


   public function view()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $id = $this->input->post('kelasID');

      $query = $this->kelas->where('kelasID', $id)->get();
      if($query){
         $data = array('kelasID' => $query->kelasID,
             'nama_kelas' => $query->nama_kelas);
      }

      echo json_encode($data);
   }

   public function form_data()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $row = array();
      if($this->input->post('kelasID')){
         $id      = $this->input->post('kelasID');
         $query   = $this->kelas->where('kelasID', $id)->get(); 
         if($query){
            $row = array('kelasID' => $query->kelasID,
             'nama_kelas' => $query->nama_kelas);
         }
         $row = (object) $row;
      }

      $data = array('hidden'=> form_hidden('kelasID', !empty($row->kelasID) ? $row->kelasID : ''),
                  'nama_kelas' => form_input(array('name'=>'nama_kelas', 'id'=>'nama_kelas', 'class'=>'form-control', 'value'=>!empty($row->nama_kelas) ? $row->nama_kelas : ''))
               );

      echo json_encode($data);
   }


   public function save_kelas()
   {   
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $rules = array(
            'insert' => array(array('field' => 'nama_kelas', 'label' => 'Nama Kelas', 'rules' => 'trim|is_unique[kelas.nama_kelas]|required|max_length[255]')),
            'update' => array(array('field' => 'nama_kelas', 'label' => 'Nama Kelas', 'rules' => 'trim|max_length[255]'))                  
            );
        
      $row  = array('nama_kelas' => $this->input->post('nama_kelas'));

      $code = 0;

      if($this->input->post('kelasID') == null){

         $this->form_validation->set_rules($rules['insert']);

         if ($this->form_validation->run() == true) {
            
            $this->kelas->insert($row);

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

            $id = $this->input->post('kelasID');

            $this->kelas->where('kelasID', $id)->update($row);
            
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

      $id = $this->input->post('kelasID');

      $this->kelas->where('kelasID', $id)->delete();

      $error =  $this->db->error();
      if($error['code'] <> 0){    
         $code = 1;
         $notifications = $error['code'].' : '.$error['message'];
      }
      else{
         $count_mhskelas = $this->mhskelas->count_rows(array('kelasID'=>$id));
         if($count_mhskelas > 0)
            $this->mhskelas->where('kelasID', $id)->delete();
         
         $count_dsnkelas = $this->dsnkelas->count_rows(array('kelasID'=>$id));
         if($count_dsnkelas > 0)
            $this->dsnkelas->where('kelasID', $id)->delete();

         $notifications = 'Success Delete Data';
      }

      $notifications = ($code == 0) ? notifications('success', $notifications) : notifications('error', $notifications);
      
      echo json_encode(array('message' => $notifications, 'code' => $code));
   }

   public function cek_delete()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $code = 0;
      $notifications = '';

      $id = $this->input->post('kelasID');

      $count_mhskelas = $this->mhskelas->count_rows(array('kelasID'=>$id));
      $count_dsnkelas = $this->dsnkelas->count_rows(array('kelasID'=>$id));

      if($count_mhskelas > 0 || $count_dsnkelas > 0){
         $code = 1;
         $notifications = 'Terdapat Data Pada Tabel Mahasiswa Kelas Sejumlah '.$count_mhskelas.' &  Dosen Kelas Sejumlah '.$count_dsnkelas.' Yang Terhubung Ke Kelas Ini. Menghapus Kelas Ini Akan Menyebabkan Beberapa Data Mahasiswa Kelas & Dosen Kelas Akan Terhapus.';
      }
      else{
         $code = 0;
      }
      
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
                    $query = $this->kelas->where('nama_kelas', $value['A'])->get();
                    if(!empty($query)){
                        // $updatedata[$i]['kelasID'] = $value['A'];
                        $updatedata[$i]['nama_kelas'] = $value['A'];
                        $updatedata[$i]['created_at'] = date('Y-m-d H:i:s');
                    }
                    else{
                        // $insertdata[$i]['kelasID'] = $value['A'];
                        $insertdata[$i]['nama_kelas'] = $value['A'];
                        $insertdata[$i]['created_at'] = date('Y-m-d H:i:s');
                    }


                    //$data = array('hidden'=> form_hidden('aksi', !empty($row->mhsKelasID) ? 'update' : 'create'));

                    $i++;
                }

                if(!empty($insertdata)){
                    $this->imp->setBatchImport($insertdata);
                    $this->imp->importData('kelas');
                }
                
                if(!empty($updatedata)){
                    $this->imp->setBatchUpdate($updatedata);
                    $this->imp->updateData('kelas','kelasID');
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

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require(FCPATH . 'vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Groupdosen extends CI_Controller {

   protected $page_header = 'Group Dosen Management';

   public function __construct()
   {
      parent::__construct();


      $this->load->model(array('Import_model'=> 'imp', 'Group_dosen' => 'group', 'Prodi_model' => 'prodi'));
      $this->load->library(array('ion_auth', 'form_validation', 'template'));
      $this->load->helper('bootstrap_helper','download');
   }

	public function index()
	{  
      
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $data['page_header']   = $this->page_header;
      $data['panel_heading'] = 'Group Pertanyaan List';
      $data['page']         = '';

      $this->template->backend('groupdosen_v', $data);
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

   public function get_group()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $list = $this->group->get_datatables();
      $data = array();
      $no = isset($_POST['start']) ? $_POST['start'] : 0;
      foreach ($list as $field) { 
         $id = $field->groupID;

         $url_view   = 'view_data('.$id.');';
         $url_update = 'update_data('.$id.');';
         $url_delete = 'delete_data('.$id.');';

         $no++;
         $row = array();
         $row[] = ajax_button($url_view, $url_update, $url_delete);
         $row[] = $no;
         $row[] = $field->groupID;
         $row[] = !empty($field->nama_prodi) ? $field->nama_prodi : 'Semua Prodi';
         $row[] = $field->nama;
         $row[] = $field->jawaban;
         $row[] = $field->nilai;

         $data[] = $row;
      }
      
      $draw = isset($_POST['draw']) ? $_POST['draw'] : null;

      $output = array(
         "draw" => $draw,
         "recordsTotal" => $this->group->count_rows(),
         "recordsFiltered" => $this->group->count_filtered(),
         "data" => $data,
      );
      echo json_encode($output);
   }


   public function view()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $id = $this->input->post('groupID');

      $query = $this->group
         ->with_programstudi('fields:nama_prodi')
         ->where('groupID', $id)
         ->get();

      $data = array();

      if($query){
         $data = array('groupID' => $query->groupID,
            'prodiID' => $query->programstudi->nama_prodi,
            'nama' => $query->nama,
            'jawaban' => $query->jawaban,
            'nilai' => $query->nilai);
      }

      echo json_encode($data);
   }

   public function form_data()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }
      

      $opt_prodi     = $this->prodi->as_dropdown('nama_prodi')->get_all();
      //array_unshift($opt_prodi, 'Semua Prodi');
      $opt_prodi = array(1=>'Semua Prodi') + $opt_prodi;
      $row = array();
      if($this->input->post('groupID')){
         $id      = $this->input->post('groupID');
         $query   = $this->group->where('groupID', $id)->get(); 
         if($query){
            $row = array(
               'groupID'    => $query->groupID,
               'prodiID' => $query->prodiID,
               'nama'   => $query->nama,
               'jawaban' => $query->jawaban,
               'nilai'   => $query->nilai
            );
         }
         $row = (object) $row;
      }

      $uni = "".date('ymdhis')."";

      $data = array('hidden'=> form_hidden('aksi', !empty($row->groupID) ? 'update' : 'create'),
             'groupID' => form_input(array('name'=>'groupID', 'id'=>'groupID', 'class'=>'form-control', 'value'=>!empty($row->groupID) ? $row->groupID : $uni)),
             'prodiID' => form_dropdown('prodiID', $opt_prodi, !empty($row->prodiID) ? $row->prodiID : '', 'class="form-control select2"'),
             'nama' => form_input(array('name'=>'nama', 'id'=>'nama', 'class'=>'form-control', 'value'=>!empty($row->nama) ? $row->nama : '')),
             'jawaban' => form_input(array('name'=>'jawaban', 'id'=>'jawaban', 'class'=>'form-control', 'value'=>!empty($row->jawaban) ? $row->jawaban : '')),
             'nilai' => form_input(array('name'=>'nilai', 'id'=>'nilai', 'class'=>'form-control', 'value'=>!empty($row->nilai) ? $row->nilai : ''))
            );

      echo json_encode($data);
   }


   public function save_group()
   {   
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }
      
      $rules = array(
            'insert' => array(                     
                     array('field' => 'groupID', 'label' => 'GroupID', 'rules' => 'trim|required|is_unique[group_pertanyaan.groupID]|max_length[12]'),
                     array('field' => 'prodiID', 'label' => 'Nama Prodi', 'rules' => 'trim|required|max_length[150]'),
                     array('field' => 'nama', 'label' => 'Nama', 'rules' => 'trim|required|max_length[150]'),
                     array('field' => 'jawaban', 'label' => 'Jawaban', 'rules' => 'trim|required|max_length[150]'),
                     array('field' => 'nilai', 'label' => 'Nilai', 'rules' => 'trim|required|max_length[150]')          
                     ),
            'update' => array(
                     array('field' => 'groupID', 'label' => 'groupID', 'rules' => 'trim|required|max_length[12]'),
                     array('field' => 'prodiID', 'label' => 'Nama Prodi', 'rules' => 'trim|required|max_length[150]'),
                     array('field' => 'nama', 'label' => 'Nama', 'rules' => 'trim|required|max_length[150]'),
                     array('field' => 'jawaban', 'label' => 'Jawaban', 'rules' => 'trim|required|max_length[150]'),
                     array('field' => 'nilai', 'label' => 'Nilai', 'rules' => 'trim|required|max_length[150]') 
                     )                   
            );
        
      $row = array('groupID' => $this->input->post('groupID'),
            'prodiID' => $this->input->post('prodiID'),
            'nama' => $this->input->post('nama'),
            'jawaban' => $this->input->post('jawaban'),
            'nilai' => $this->input->post('nilai'));
      
      
      $code = 0;

      if($this->input->post('aksi') == 'create'){

         $this->form_validation->set_rules($rules['insert']);

         if ($this->form_validation->run() == true) {
            
            $this->group->insert($row);

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

            $id = $this->input->post('groupID');

            $this->group->where('groupID', $id)->update($row);
            
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

      $id = $this->input->post('groupID');

      $this->group->where('groupID', $id)->delete();

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
                    $query = $this->group->where('groupID', $value['A'])->get();
                    if(!empty($query)){
                        $updatedata[$i]['groupID'] = $value['A'];
                        $updatedata[$i]['prodiID'] = $value['B'];
                        $updatedata[$i]['nama'] = $value['C'];
                        $updatedata[$i]['jawaban'] = $value['D'];
                        $updatedata[$i]['nilai'] = $value['E'];
                        $updatedata[$i]['created_at'] = date('Y-m-d H:i:s');
                    }
                    else{
                        $insertdata[$i]['groupID'] = $value['A'];
                        $insertdata[$i]['prodiID'] = $value['B'];
                        $insertdata[$i]['nama'] = $value['C'];
                        $insertdata[$i]['jawaban'] = $value['D'];
                        $insertdata[$i]['nilai'] = $value['E'];
                        $insertdata[$i]['created_at'] = date('Y-m-d H:i:s');
                    }


                    //$data = array('hidden'=> form_hidden('aksi', !empty($row->mhsKelasID) ? 'update' : 'create'));

                    $i++;
                }

                if(!empty($insertdata)){
                    $this->imp->setBatchImport($insertdata);
                    $this->imp->importData('group_pertanyaan');
                }
                
                if(!empty($updatedata)){
                    $this->imp->setBatchUpdate($updatedata);
                    $this->imp->updateData('group_pertanyaan','groupID');
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

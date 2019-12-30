<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DsnKelas extends CI_Controller {

   protected $page_header = 'Dosen Kelas Management';

   public function __construct()
   {
      parent::__construct();


      $this->load->model(array('Kelas_model'=>'kelas', 'ThnAkademik_model'=>'akademik', 'Dosen_model'=>'dosen', 'DsnKelas_model' => 'dsnkelas'));
      $this->load->library(array('ion_auth', 'form_validation', 'template'));
      $this->load->helper('bootstrap_helper');
   }

	public function index()
	{  
      
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $data['page_header']   = $this->page_header;
      $data['panel_heading'] = 'Product List';
      $data['page']         = '';

      $this->template->backend('dsnKelas_v', $data);
	}

   public function get_dsnkelas()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $list = $this->dsnkelas->get_datatables();
      $data = array();
      $no = isset($_POST['start']) ? $_POST['start'] : 0;
      foreach ($list as $field) { 
         $id = $field->dsnKelasID;

         $url_view   = 'view_data('.$id.');';
         $url_update = 'update_data('.$id.');';
         $url_delete = 'delete_data('.$id.');';

         $no++;
         $row = array();
         $row[] = ajax_button($url_view, $url_update, $url_delete);
         $row[] = $no;
         $row[] = $field->dsnKelasID;
         $row[] = $field->nama_kelas;
         $row[] = $field->nama;
         $row[] = $field->thnAkademik;

         $data[] = $row;
      }
      
      $draw = isset($_POST['draw']) ? $_POST['draw'] : null;

      $output = array(
         "draw" => $draw,
         "recordsTotal" => $this->dsnkelas->count_rows(),
         "recordsFiltered" => $this->dsnkelas->count_filtered(),
         "data" => $data,
      );
      echo json_encode($output);
   }


   public function view()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $id = $this->input->post('dsnKelasID');

     $query = $this->dsnkelas
         ->with_kelas('fields:nama_kelas')
         ->with_dosen('fields:nama')
         ->with_tahunakademik('fields:thnAkademik')
         ->where('dsnKelasID', $id)
         ->get();

      $data = array();
      if($query){
         $data = array('dsnKelasID' => $query->dsnKelasID,
                        'kelasID' => $query->kelas->nama_kelas,
                        'npp' => $query->dosen->nama,
                        'thnAkademikID' => $query->akademik->thnAkademik
                     );
                  }

      echo json_encode($data);
   }

   public function form_data()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $opt_kelas     = $this->kelas->as_dropdown('nama_kelas')->get_all();
      $opt_dosen     = $this->dosen->as_dropdown('nama')->get_all();
      $opt_akademik     = $this->akademik->as_dropdown('thnAkademik')->get_all();

      $row = array();
      if($this->input->post('dsnKelasID')){
         $id      = $this->input->post('dsnKelasID');
         $query   = $this->dsnkelas->where('dsnKelasID', $id)->get(); 
         if($query){
            $row = array(
            'dsnKelasID'       => $query->dsnKelasID,
            'kelasID'     => $query->kelasID,
            'npp'      => $query->npp,
            'thnAkademikID'      => $query->thnAkademikID
            );
         }
         $row = (object) $row;
      }

      $data = array('hidden'=> form_hidden('aksi', !empty($row->dsnKelasID) ? 'update' : 'create'),
             'dsnKelasID' => form_input(array('name'=>'dsnKelasID', 'id'=>'datepicker', 'class'=>'form-control', 'value'=>!empty($row->dsnKelasID) ? $row->dsnKelasID : '')),
             'kelasID' => form_dropdown('kelasID', $opt_kelas, !empty($row->kelasID) ? $row->kelasID : '', 'class="chosen-select"'),
             'npp' => form_dropdown('npp', $opt_dosen, !empty($row->npp) ? $row->npp : '', 'class="chosen-select"'),
             'thnAkademikID' => form_dropdown('thnAkademikID', $opt_akademik, !empty($row->thnAkademikID) ? $row->thnAkademikID : '', 'class="chosen-select"')
            );

      echo json_encode($data);
   }


   public function save_dsnkelas()
   {   
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $rules = array(
            'insert' => array(                     
                     array('field' => 'dsnKelasID', 'label' => 'Dosen Kelas ID', 'rules' => 'trim|required|is_unique[dosen_kelas.dsnKelasID]|max_length[6]'), 
                     array('field' => 'kelasID', 'label' => 'Nama Kelas', 'rules' => 'max_length[11]'),
                     array('field' => 'npp', 'label' => 'Nama Dosen', 'rules' => 'max_length[11]'),
                     array('field' => 'thnAkademikID', 'label' => 'Tahun Akademik', 'rules' => 'max_length[20]')
                     ),
            'update' => array(
                     array('field' => 'dsnKelasID', 'label' => 'Dosen Kelas ID', 'rules' => 'trim|required|max_length[6]'), 
                     array('field' => 'kelasID', 'label' => 'Nama Kelas', 'rules' => 'max_length[11]'),
                     array('field' => 'npp', 'label' => 'Nama Dosen', 'rules' => 'max_length[11]'),
                     array('field' => 'thnAkademikID', 'label' => 'Tahun Akademik', 'rules' => 'max_length[20]')
                     ),             
            );
        
      $row = array('dsnKelasID' => $this->input->post('dsnKelasID'),
            'kelasID' => $this->input->post('kelasID'),
            'npp' => $this->input->post('npp'),
            'thnAkademikID'      => $this->input->post('thnAkademikID'));

      $code = 0;

      if($this->input->post('aksi') == 'create'){

         $this->form_validation->set_rules($rules['insert']);

         if ($this->form_validation->run() == true) {
            
            $this->dsnkelas->insert($row);

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

            $id = $this->input->post('dsnKelasID');

            $this->dsnkelas->where('dsnKelasID', $id)->update($row);
            
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

      $id = $this->input->post('dsnKelasID');

      $this->dsnkelas->where('dsnKelasID', $id)->delete();

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
}

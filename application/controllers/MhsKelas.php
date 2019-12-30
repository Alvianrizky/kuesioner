<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mhskelas extends CI_Controller {

   protected $page_header = 'Mahasiswa Kelas Management';

   public function __construct()
   {
      parent::__construct();


      $this->load->model(array('Kelas_model'=>'kelas', 'Mahasiswa_model'=>'mahasiswa', 'ThnAkademik_model'=>'akademik', 'MhsKelas_model' => 'mhskelas'));
      $this->load->library(array('ion_auth', 'form_validation', 'template'));
      $this->load->helper('bootstrap_helper');
   }

	public function index()
	{  
      
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $data['page_header']   = $this->page_header;
      $data['panel_heading'] = 'Mahasiswa Kelas List';
      $data['page']         = '';

      $this->template->backend('mhskelas_v', $data);
	}

   public function get_mhskelas()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $list = $this->mhskelas->get_datatables();
      $data = array();
      $no = isset($_POST['start']) ? $_POST['start'] : 0;
      foreach ($list as $field) { 
         $id = $field->mhsKelasID;

         $url_view   = 'view_data('.$id.');';
         $url_update = 'update_data('.$id.');';
         $url_delete = 'delete_data('.$id.');';

         $no++;
         $row = array();
         $row[] = ajax_button($url_view, $url_update, $url_delete);
         $row[] = $no;
         $row[] = $field->mhsKelasID;
         $row[] = $field->nama_kelas;
         $row[] = $field->nama;
         $row[] = $field->thnAkademik;

         $data[] = $row;
      }
      
      $draw = isset($_POST['draw']) ? $_POST['draw'] : null;

      $output = array(
         "draw" => $draw,
         "recordsTotal" => $this->mhskelas->count_rows(),
         "recordsFiltered" => $this->mhskelas->count_filtered(),
         "data" => $data,
      );
      echo json_encode($output);
   }


   public function view()
   {
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $id = $this->input->post('mhsKelasID');

     $query = $this->mhskelas
         ->with_kelas('fields:nama_kelas')
         ->with_mahasiswa('fields:nama')
         ->with_tahunakademik('fields:thnAkademik')
         ->where('mhsKelasID', $id)
         ->get();

      $data = array();
      if($query){
         $data = array('mhsKelasID' => $query->mhsKelasID,
            'kelasID' => $query->kelas->nama_kelas,
            'nim' => $query->mahasiswa->nama,
            'thnAkademikID' => $query->tahunakademik->thnAkademik
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
      $opt_mahasiswa     = $this->mahasiswa->as_dropdown('nama')->get_all();
      $opt_akademik     = $this->akademik->as_dropdown('thnAkademik')->get_all();

      $row = array();
      if($this->input->post('mhsKelasID')){
         $id      = $this->input->post('mhsKelasID');
         $query   = $this->mhskelas->where('mhsKelasID', $id)->get(); 
         if($query){
            $row = array(
            'mhsKelasID'       => $query->mhsKelasID,
            'kelasID'     => $query->kelasID,
            'nim'      => $query->nim,
            'thnAkademikID'      => $query->thnAkademikID
            );
         }
         $row = (object) $row;
      }

      $data = array('hidden'=> form_hidden('aksi', !empty($row->mhsKelasID) ? 'update' : 'create'),
             'mhsKelasID' => form_input(array('name'=>'mhsKelasID', 'id'=>'datepicker', 'class'=>'form-control', 'value'=>!empty($row->mhsKelasID) ? $row->mhsKelasID : '')),
             'kelasID' => form_dropdown('kelasID', $opt_kelas, !empty($row->kelasID) ? $row->kelasID : '', 'class="chosen-select"'),
             'nim' => form_dropdown('nim', $opt_mahasiswa, !empty($row->nim) ? $row->nim : '', 'class="chosen-select", multiple="true"'),
             'thnAkademikID' => form_dropdown('thnAkademikID', $opt_akademik, !empty($row->thnAkademikID) ? $row->thnAkademikID : '', 'class="chosen-select"')
            );

      echo json_encode($data);
   }


   public function save_mhskelas()
   {   
      if (!$this->ion_auth->logged_in()){            
         redirect('auth/login', 'refresh');
      }

      $rules = array(
            'insert' => array(                     
                     array('field' => 'mhsKelasID', 'label' => 'Mahasiswa Kelas ID', 'rules' => 'trim|required|is_unique[mahasiswa_kelas.mhsKelasID]|max_length[6]'), 
                     array('field' => 'kelasID', 'label' => 'Nama Kelas', 'rules' => 'max_length[11]'),
                     array('field' => 'nim', 'label' => 'Nama Mahasiswa', 'rules' => 'max_length[11]'),
                     array('field' => 'thnAkademikID', 'label' => 'Tahun Akademik', 'rules' => 'max_length[20]')
                     ),
            'update' => array(
                     array('field' => 'mhsKelasID', 'label' => 'Mahasiswa Kelas ID', 'rules' => 'trim|required|max_length[6]'), 
                     array('field' => 'kelasID', 'label' => 'Nama Kelas', 'rules' => 'max_length[11]'),   
                     array('field' => 'nim', 'label' => 'Nama Mahasiswa', 'rules' => 'max_length[11]'),
                     array('field' => 'thnAkademikID', 'label' => 'Tahun Akademik', 'rules' => 'max_length[20]')
                     )                  
            );
        
      $row = array('mhsKelasID' => $this->input->post('mhsKelasID'),
            'kelasID' => $this->input->post('kelasID'),
            'nim' => $this->input->post('nim'),
            'thnAkademikID'      => $this->input->post('thnAkademikID'));

      $code = 0;

      if($this->input->post('aksi') == 'create'){

         $this->form_validation->set_rules($rules['insert']);

         if ($this->form_validation->run() == true) {
            
            $this->mhskelas->insert($row);

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

            $id = $this->input->post('mhsKelasID');

            $this->mhskelas->where('mhsKelasID', $id)->update($row);
            
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

      $id = $this->input->post('mhsKelasID');

      $this->mhskelas->where('mhsKelasID', $id)->delete();

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
